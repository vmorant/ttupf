<?php
/**
 * Afegeix les classes de la setmana actual a la base de dades.
 * Fa servir la llibreria simplehtmldom v.1.5.
 */

class timetableParser
{

	private $logger;
	private $courseYear;
	private $sessions;

	public function timetableParser($courseYear, $sessions)
	{
		$this->logger = sfContext::getInstance()->getLogger();
		$this->courseYear = $courseYear;
		$timetableDOM = new simple_html_dom();
		$timetableDOM->load_file($courseYear->getUrlHorari());
		
		$this->sessions = $sessions;

		// Date of first week is in the second table, second row, second cell.
		$firstWeek = $timetableDOM->find('table', 1)->find('tr', 1)->find('td', 1)->plaintext;

		$currWeekNum = $this->calculateCurrentWeek($firstWeek);
		$this->logger->info('Current week number is ' . $currWeekNum);

		$currWeekDOM = $timetableDOM->find('table', $currWeekNum);

		$currWeekStartDate = $this->weekStartDate(new DateTime());
		$this->deleteWeekSessions($currWeekStartDate->format('d.m.Y H:i:s'));

		$this->logger->info("Current week start date is ". $currWeekStartDate->format('d.m.Y H:i:s'));

		// Create sessions for each period in the timetable and save them to the database.
		$rows = $currWeekDOM->find('tr');
		for($row = 0; $row < sizeof($rows); $row++){
			if($row > 1){
				$cells = $rows[$row]->find('td');
				for($cell = 0; $cell < sizeof($cells); $cell++){
					if($cell == 0){
						// Parse the start/end times for this row's period.
						$period = new Period($cells[$cell]->plaintext);
						$this->logger->info("Start time is " . $period->getStart()->format("H:i:s") . ". End time is " . $period->getEnd()->format("H:i:s"));
					}
					else{
						if(trim($cells[$cell]->plaintext) === '') {
							$this->logger->debug("Skipping cell [".$row."][".$cell."], it's empty.");
							continue;
						}
						// Session start date is the week's start date + current day of week. Time is provided in the first cell of each row
						
						$sessionStartDateTime = new DateTime($currWeekStartDate->format('d.m.Y'));
						$sessionStartDateTime->add(new DateInterval('P'.strval($cell-1).'D'));
						$sessionStartDateTime->setTime($period->getStart()->format('H'), $period->getStart()->format('i'));
						
						$period->setStart($sessionStartDateTime);
						$this->logger->debug('SessionStartDateTime is: ' . $sessionStartDateTime->format('d.m.Y H:i:s'));

						// Agafem el timestamp de l'hora que acaba la classe i la guardem a la variable period
						$sessionEndDateTime = new DateTime($currWeekStartDate->format('d.m.Y'));						
						$sessionEndDateTime->add(new DateInterval('P'.strval($cell-1).'D'));
						$sessionEndDateTime = $sessionEndDateTime->setTime($period->getEnd()->format('H'), $period->getEnd()->format('i'));
						
						$period->setEnd($sessionEndDateTime);
						$this->logger->debug('SessionEndDateTime is: ' . $sessionEndDateTime->format('d.m.Y H:i:s'));

						// Get the plaintext for the period and insert each line into an array.
						$periodInfo = html_entity_decode($cells[$cell]->plaintext, ENT_QUOTES, 'UTF-8');
						$periodInfoArray = explode("\n", $periodInfo);
						foreach($periodInfoArray as $i => $value):
							unset($periodInfoArray[$i]);
							$periodInfoArray[] = trim($value);
						endforeach;
						// Re-index the array starting from zero.
						$period->setDetails(array_values($periodInfoArray));
						$this->logger->debug("Trimmed periodinfoarray is: " . var_export($period->getDetails(), true));
						
						$this->parseSession($period);
					}
				}
			}
		}
	}

	/**
	 * Given a starting date, calculate the number of weeks that have passed since then.
	 */
	private function calculateCurrentWeek($dateFirstWeek)
	{
		$dateFirstWeek = new DateTime($dateFirstWeek);
		$today = new DateTime();
		$interval = $today->diff($dateFirstWeek);
		// Add one because week number starts at 1 not zero
		$currWeek = floor($interval->format('%a') / 7) + 1;
		return $currWeek;
	}

	/**
	 * Calculates the date of the first day of the week for a given date.
	 */
	private function weekStartDate($date)
	{
		return $date->sub(new DateInterval('P'.strval(intval($date->format('N'))-1).'D'));
	}

	/**
	 * Attempts to parse a session's content to extract the course id, session type, practical/seminar group and classroom. Takes a session object and an array that contains the lines that make up the session info.
	 */
	private function parseSession($period)
	{
		$sessionPlainTextArray = $period->getDetails();
		// When there is only one element in the array it's probably a bank holiday and not the 
		// coursename.
		if(sizeof($sessionPlainTextArray) > 1){
			$course = Doctrine_Core::getTable('Assignatura')
				->findOneByNom($sessionPlainTextArray[0]);
			if($course){
				$this->logger->debug("Found course " . $sessionPlainTextArray[0] . " in database, id is " . $course->getId());
			}
			// Course doesn't exist, so create it and set its attributes.
			if(!$course) {
				$this->logger->debug("Course " . $sessionPlainTextArray[0] . " doesn't exist, creating.");
				$course = new Assignatura();
				$course->setNom($sessionPlainTextArray[0]);
				$course->setCarreraCurs($this->courseYear);
				$course->save();
			}
		}
		// Parse period cell by looking for lines that have '[Aula|Pnnn|Xnnn]: nn.nnn'. The bit before
		// the colon tells us whether it's theory (Aula), practical (Pnnn) or seminar (Snnn). The bit
		// after the colon always tells us the classroom.
		foreach($sessionPlainTextArray as $key => $line):
			$matches = array();
			$this->logger->debug("Line is: " . $line);
			// The tilde (~) is the separator for the regular expression. 
			// $regex matches strings of the type "Aula: 52.119". The .+? after the colon is because
			// sometimes its a space and sometimes a &nbsp;.
			$regex = "/(.+)[:|-].+?([0-9]{2}.[[:alnum:]]{3})/";	
			// Aula (or [P|S]XXX) will be in $matches[1], classroom will be in $matches[2].
			$preg_match = preg_match($regex, $line, $matches);
			$this->logger->debug("Preg match result is: " . intval($preg_match)); 
			if($preg_match){
				$sessionObject = new Sessio();
				switch($matches[1][0]){
				case 'A':
					// Mirem si la linea on hi ha la informació amb l'aula de la teoria és la primera o la 
					// última. Si és la primera és el primer grup de teoría. Si no és el segon.
					if($key == 2) {
						$sessionObject->setGrupTeoria('1');
					} else if($key == sizeof($sessionPlainTextArray) - 1) {
						$sessionObject->setGrupTeoria('2');
					}
					$sessionObject->setTipus('TEORIA');
					$this->logger->debug("Setting type to theory.");
					break;
				case 'P':
					$sessionObject->setTipus('PRÀCTIQUES');
					$sessionObject->setGrupPractiques($matches[1]);
					$this->logger->debug("Setting type to practical.");
					break;
				case 'S':
					$sessionObject->setTipus('SEMINARIS');
					$sessionObject->setGrupSeminari($matches[1]);
					$this->logger->debug("Setting type to seminar.");
					break;
				default:
					unset($sessionObject);
					$this->logger->debug("No s'ha pogut parsejar el tipus de sessió.");
					return -1;
				}
				
				$sessionObject->setAssignatura($course);
				$sessionObject->setAula($matches[2]);
				$sessionObject->setDataHoraInici($period->getStart()->format('Y-m-d H:i:s'));
				$sessionObject->setDataHoraFi($period->getEnd()->format('Y-m-d H:i:s'));

				$this->logger->debug("session object still exists, saving.");
				$sessionObject->save();
			}
			else{
				if(sizeof($sessionPlainTextArray) <= 2){
					return -1;
				}
			}
		endforeach;
		return 0;
	}

	/**
	 * Takes an array containing the lines of a session and a session object. Parses the
	 * group-classroom line and updates the appropriate attributes of the session object
	 * depending on whether its a practical, seminar, or theory session.
	 */
	private function parseGroupClass($sessionPlainTextArray, $sessionObject)
	{
		// The "[group:] classroom" string isn't consistent with the separation between group
		// and classroom. Sometimes it's a colon, sometimes a dash, so delete all punctuation.
		$sessionPlainTextArray[2] = preg_replace("/\p{P}/u", "", $sessionPlainTextArray[2]);
		// Group is first element, classroom second element
		$groupClass = explode(" ", $sessionPlainTextArray[2]);
		switch($sessionPlainTextArray[1]) {
			case "SEMINARIS":
				$sessionObject->setGrupSeminari($groupClass[0]);
				break;
			case "PRÀCTIQUES":
				$sessionObject->setGrupPractiques($groupClass[0]);
		}
		// Set the classroom for all cases.
		$sessionObject->setAula($groupClass[1]);
	}

	/**
	 * Esborra les sessions d'aquella setmana, i se li passa per entrada el primer dia de la setmana
	 */
	private function deleteWeekSessions($firstDayWeek) {
		$bdSessions = $this->sessions;
		
		$firstDayWeek = new DateTime($firstDayWeek);
		
		foreach($bdSessions as $bdSessio) {
			$iterDay = new DateTime($bdSessio->getDataHoraInici());
			
			if($iterDay->format('%a') - 7 < $firstDayWeek->format('%a')) {
				$bdSessio->delete();
			}
		}
		return 0;
	}
}

/**
 * Given the plaintext from a period cell of a timetable, create an object with start time hours and minutes and end time hours and minutes.
 */
class Period
{
	private $logger;
	private $start;
	private $end;

	public function Period($timeString)
	{
		$this->logger = sfContext::getInstance()->getLogger();
		$this->start = new DateTime();
		$this->end = new DateTime();

		$this->logger->debug('timestring is ' . $timeString);
		$timeArray = explode('-', $timeString);
		$this->logger->debug('after exploding by \'-\' it\'s: ' . var_export($timeArray, true));
		// start has two elements, [0] is hour [1] is minutes
		$start = explode('.', $timeArray[0]);
		$start[0] = ltrim($start[0]);

		$this->logger->debug('start array is: ' . var_export($start, true));

		// same with end
		$end = explode('.', $timeArray[1]);
		// end minutes has a trailing 'h' we need to trim
		$end[1] = rtrim($end[1], ' h ');
		$end[1] = ltrim($end[1]);

		$this->logger->debug('end array is: ' .var_export($end, true));
		$this->start->setTime(intval($start[0]), intval($start[1]));
		$this->end->setTime(intval($end[0]), intval($end[1]));
	}

	public function setStartTime($hours, $mins)
	{
		$this->start->setTime($hours, $mins);
	}

	public function getStart()
	{
		return $this->start;
	}

	public function setStart($start)
	{
		$this->start = $start;
	}

	public function setEndTime($hours, $mins)
	{
		$this->end->setTime($hours, $mins);
	}

	public function getEnd()
	{
		return $this->end;
	}

	public function setEnd($end)
	{
		$this->end = $end;
	}

	public function setDate($year, $month, $day)
	{
		$this->start->setDate($year, $month, $day);
		$this->end->setDate($year, $month, $day);
	}

	public function setDetails($details)
	{
		$this->details = $details;
	}

	public function getDetails()
	{
		return $this->details;
	}
}
