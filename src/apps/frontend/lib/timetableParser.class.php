<?php
/**
 * Afegeix les classes de la setmana actual a la base de dades.
 * Fa servir la llibreria simplehtmldom v.1.5.
 */

class timetableParser
{

	private $logger;
	private $courseYear;

	public function timetableParser($courseYear)
	{
		$this->logger = sfContext::getInstance()->getLogger();
		$this->courseYear = $courseYear;
		$timetableDOM = new simple_html_dom();
		$timetableDOM->load_file($courseYear->getUrlHorari());

		// Date of first week is in the second table, second row, second cell.
		$firstWeek = $timetableDOM->find('table', 1)->find('tr', 1)->find('td', 1)->plaintext;

		$currWeekNum = $this->calculateCurrentWeek($firstWeek);
		$this->logger->info('Current week number is ' . $currWeekNum);

		$currWeekDOM = $timetableDOM->find('table', $currWeekNum);

		$currWeekStartDate = $this->weekStartDate(new DateTime());

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
						$this->logger->info("Start time is " . $period->getStart() . ". End time is " . $period->getEnd());
					}
					else{
						if(trim($cells[$cell]->plaintext) === '') {
							$this->logger->debug("Skipping cell [".$row."][".$cell."], it's empty.");
							continue;
						}
						// Session start date is the week's start date + current day of week. Time is provided in the first cell of each row
						$session = new Sessio();
						$sessionStartDateTime = new DateTime($currWeekStartDate->format('d.m.Y'));
						$sessionStartDateTime->add(new DateInterval('P'.strval($cell-1).'D'));
						$sessionStartDateTime->setTime($period->start->format('H'), $period->start->format('i'));
						$session->setDataHoraInici($sessionStartDateTime->format('Y-m-d H:i:s'));
						$this->logger->debug('SessionStartDateTime is: ' . $sessionStartDateTime->format('d.m.Y H:i:s'));

						$sessionEndDateTime = $sessionStartDateTime->setTime($period->end->format('H'), $period->end->format('i'));
						$session->setDataHoraFi($sessionEndDateTime->format('Y-m-d H:i:s'));
						$this->logger->debug('SessionEndDateTime is: ' . $sessionEndDateTime->format('d.m.Y H:i:s'));

						// Actually parse each cell's content. Need to extract Course name, period type (Theory/Practical/Seminar), seminar/practical group and class.
						$sessionInfo = html_entity_decode($cells[$cell]->plaintext, ENT_QUOTES, 'UTF-8');
						$sessionInfoArray = explode("\n", $sessionInfo);
						foreach($sessionInfoArray as $i => $value):
							unset($sessionInfoArray[$i]);
							$sessionInfoArray[] = trim($value);
						endforeach;
						$sessionInfoArray = array_values($sessionInfoArray);
						$this->logger->debug("Trimmed sessioninfoarray is: " . var_export($sessionInfoArray, true));
						$parseErrorCode = $this->parseSession($session, $sessionInfoArray);
						if(!$parseErrorCode){
							$this->logger->debug("session object still exists, saving.");
							$session->save();
						}
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
	private function parseSession($sessionObject, $sessionPlainTextArray)
	{
		// When there is only one element in the array it's probably a bank holiday and not the 
		// coursename.
		if(sizeof($sessionPlainTextArray) > 1){
			$course = Doctrine_Core::getTable('Assignatura')
				->findOneByNom($sessionPlainTextArray[0]);
			// Course doesn't exist, so create it and set its attributes.
			if(!$course) {
				$course = new Assignatura();
				$course->setNom($sessionPlainTextArray[0]);
				$course->setCarreraCurs($this->courseYear);
			}
		}
		// Parse period cell by looking for lines that have '[Aula|Pnnn|Xnnn]: nn.nnn'. The bit before
		// the colon tells us whether it's theory (Aula), practical (Pnnn) or seminar (Snnn). The bit
		// after the colon always tells us the classroom.
		foreach($sessionPlainTextArray as $line):
			$matches = array();
			$this->logger->debug("Line is: " . $line);
			// The tilde (~) is the separator for the regular expression. 
			// $regex matches strings of the type "Aula: 52.119".
			$regex = "/(.+)[:|-] ([0-9]{2}.[0-9]{3})/";
			// Aula (or [P|S]XXX) will be in $matches[1], classroom will be in $matches[2].
			$preg_match = preg_match($regex, $line, $matches);
			$this->logger->debug("Preg match result is: " . intval($preg_match)); 
			if($preg_match){
				switch($matches[1][0]){
				case 'A':
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
					$this->logger->error("No s'ha pogut parsejar el tipus de sessió.");
					return -1;
				}
				
				$sessionObject->setAssignatura($course);
				$sessionObject->setAula($matches[2]);
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
}

/**
 * Given the plaintext from a period cell of a timetable, create an object with start time hours and minutes and end time hours and minutes.
 */
class Period
{
	private $logger;
	public $start;
	public $end;

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

	public function setStart($hours, $mins)
	{
		$this->start->setTime($hours, $mins);
	}

	public function getStart()
	{
		return $this->start->format("H:i:s");
	}

	public function setEnd($hours, $mins)
	{
		$this->end->setTime($hours, $mins);
	}

	public function getEnd()
	{
		return $this->end->format("H:i:s");
	}
}
