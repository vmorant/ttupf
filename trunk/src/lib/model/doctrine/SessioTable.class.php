<?php

/**
 * SessioTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SessioTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object SessioTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Sessio');
    }
			
	private $logger;
	private $courseYear;
	private $sessions;

	public function actualitza($courseYear, $date = NULL)
	{	
		$this->logger = sfContext::getInstance()->getLogger();
	
		$this->courseYear = $courseYear;
		$this->sessions = array();

		$timetableDOM = new simple_html_dom();		
		$timetableDOM->load_file($courseYear->getUrlHorari());
				

		foreach($courseYear->getAssignatures() as $assignatura):
			foreach($assignatura->getSessions() as $sessio):
				$this->sessions[] = $sessio;
			endforeach;
		endforeach;

		// Date of first week is in the second table, second row, second cell.
		$firstWeek = $timetableDOM->find('table', 1)->find('tr', 1)->find('td', 1)->plaintext;

		$currWeekNum = $this->calculateCurrentWeek($firstWeek, $date);
		$this->logger->info('Current week number is ' . $currWeekNum);

		$currWeekDOM = $timetableDOM->find('table', $currWeekNum);

		if($date){
			$currWeekStartDate = $this->weekStartDate(new DateTime($date));
		}
		else{
			$currWeekStartDate = $this->weekStartDate(new DateTime());
		}
		$this->deleteWeekSessions($currWeekStartDate->format('d.m.Y H:i:s'));

		$this->logger->info("Current week start date is ". $currWeekStartDate->format('d.m.Y H:i:s'));

		// Create sessions for each period in the timetable and save them to the database.
		$rows = $currWeekDOM->find('tr');
		
		
		for($row = 0; $row < sizeof($rows); $row++){
			if($row > 1){
				$cells = $rows[$row]->find('td');
				$period = new Period($cells[0]->plaintext);
				$period->setCourseYear($this->courseYear);
				$this->logger->info("Start time is " . $period->getStart()->format("H:i:s") . ". End time is " . $period->getEnd()->format("H:i:s"));
				for($cell = 0; $cell < sizeof($cells); $cell++){				
					if($cell > 0){
						// Parse the start/end times for this row's period.
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
						$this->logger->debug("Periodinfoarray is: " . var_export($cells[$cell]->plaintext, true));

						$periodInfo = html_entity_decode($cells[$cell]->plaintext, ENT_QUOTES, 'UTF-8');
						$periodInfoArray = explode("\n", $periodInfo);
						foreach($periodInfoArray as $i => $value):
							unset($periodInfoArray[$i]);
							$periodInfoArray[] = trim($value);
						endforeach;
						// Re-index the array starting from zero.
						$period->setDetails(array_values($periodInfoArray));
						$this->logger->debug("Trimmed periodinfoarray is: " . var_export($period->getDetails(), true));
						
						echo "parsejant<br />";
						$this->parseSession($period);
					}
				}
				//unset($period);
			}
		}
	}

	/**
	 * Given a starting date, calculate the number of weeks that have passed since then.
	 */
	private function calculateCurrentWeek($dateFirstWeek, $date = NULL)
	{
		$dateFirstWeek = new DateTime($dateFirstWeek);
		// Si s'ha especificat una data retornem el número de setmana d'aquella data.
		if($date){
			$today = new DateTime($date);
		}
		else{
			$today = new DateTime();
		}
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
		/* Here is where the state machine goes*/
		$current_state = 0;
		
		$periodPlainTextArray = $period->getDetails();
		
		foreach ($periodPlainTextArray as $key => $row) {
		    $keys[$key]  = $key; 
		}

		array_multisort($keys, SORT_DESC, $periodPlainTextArray);
		
		$periodPlainTextArray[] = "";
		
		foreach($periodPlainTextArray as $key => $row):
//			////echo utf8_decode($row)."<br />";
			//$block->lineType($row);
//			$block->isThereAGroup($row);
			if($current_state != -1) {
				////echo $row."<br />";	
				$current_state = $period->stateMachine($current_state, $row);
			} else {
				////echo "Something gets wrong.<br />";
			}
		endforeach;
		
		if(sizeof($period->getCurrentBlock()) != 0) {
			echo "HA ACABAT BE<br />";
			
		}
		
		////echo "/**********************<br />";
	
/*		$sessionPlainTextArray = $period->getDetails();
		// When there is only one element in the array it's probably a bank holiday and not the 
		// coursename.
		if(sizeof($sessionPlainTextArray) > 1){
			$course = Doctrine_Core::getTable('Assignatura')
				->findOneByNom($sessionPlainTextArray[0]);
			if($course){
				$this->logger->debug("Found course " . $sessionPlainTextArray[0] . " in database, id is " . $course->getId());
			}
			// Course doesn't exist, so create it and set its attributes.
			else {
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
		endforeach;*/
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
		
		foreach($bdSessions as $bdSessio):
			$iterDay = new DateTime($bdSessio->getDataHoraInici());
			
			if($iterDay->format('%a') - 7 < $firstDayWeek->format('%a')) {
				$bdSessio->delete();
			}
		endforeach;
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
	private $details;
	private $blockArray;
	private $courseyear;

	public function Period($timeString)
	{
		$this->blockArray = array();
	
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
	
	public function stateMachine($current_state, $line) {
		//$line = iconv("ISO-8859-1//TRANSLIT", "UTF-8", $line);
		echo "^".utf8_decode($line)."$<br />";
		switch($current_state) {
			case 0:
				echo "// Estat A<br />";
				////echo $line."<br />";

				$hasThings = $this->lineType($line);
				
				// Seguent estat
				if($hasThings[1] && ($this->isThereAGroup($line)) && $hasThings[0]) {
					echo "__Aula i Grup --> B, Tipus --> D<br />";
					$this->doStateA();
					$this->doStateB($line);
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[1] && $this->isThereAGroup($line)) {
					echo "__Aula i Grup --> B<br />";
					$this->doStateA();
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[1]) {
					echo "__Aula --> B<br />";
					$this->doStateA();
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[0]) {
					echo "__Tipus --> D<br />";
					$this->doStateA();
					$this->doStateD($line);
					return 3;
				}
				else if(!$line || ((preg_match("/^[\s]{1,}/", $line) || preg_match("/^[-]{3,}/", $line)))) {
					echo "__Linea Buida<br />";
					return 0;
				}
				else {
					echo "__Linea Invalida<br />";
					return 0;
				}
				break;
			case 1:
				echo "// Estat B<br />";
				echo $line."<br />";
				$hasThings = $this->lineType($line);

				// Seguent estat
				if($hasThings[1] && $this->isThereAGroup($line)) {
					echo "__Aula i Grup --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[1]) {
					echo "__Aula --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[3]) {
					echo "__Hora --> C<br />";
					$this->doStateC($line);
					return 2;
				}
				else if (($groups = $this->isThereAGroup($line)) && $hasThings[0]) {
					echo "__Tipus i Grup --> D<br />";
					foreach($groups as $group):
						$this->getCurrentBlock()->setSessionGroup($group);	
					endforeach;
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[0]) {
					echo "__Tipus --> D<br />";
					$this->doStateD($line);
					return 3;
				}
				else {
					////echo "Something went wrong in state B<br />";
				}
				break;
			case 2:
				//TODO: Maybe can be created some way from c to e
				echo "// Estat C<br />";
				////echo $line."<br />";
				$hasThings = $this->lineType($line);
				
				// Seguent estat
				if($hasThings[1] && $this->isThereAGroup($line)) {
					echo "__Aula i Grup --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[0]) {
					echo "__Tipus --> D<br />";
					$this->doStateD($line);
					return 3;
				}
				else {
					echo "Something went wrong in state C<br />";
				}
				break;
			case 3:
				echo "// Estat D<br />";
				//echo "-----------------_->".$line."<-------<br />";
				
				if($line == NULL) {
					echo "HEYYYYY";
					$this->unsetCurrentBlock();
					return -1;
				}
				
				$hasThings = $this->lineType($line);
				
				// Seguent estat
				if($hasThings[2]) {
					echo "__Assignatura --> E<br />";
					$this->doStateE($line);					
					return 5;
				}
				else if($hasThings[1] && $this->isThereAGroup($line) && $hasThings[0]) {
					echo "__Aula i Grup --> B, Tipus --> D<br />";
					$this->doStateB($line);
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[1] && $this->isThereAGroup($line)) {
					echo "__Aula i Grup --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[3]) {
					echo "__Hora --> C<br />";
					$this->doStateC($line);
					return 2;
				}
				else if($hasThings[1]) {
					echo "__Aula --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else {
					echo "Dia Festiu o l'assignatura no te nom<br />";
				}
				break;
			case 4:
				echo "// Estat F<br />";
				echo $line."<br />";
				
				// Seguent estat
				$this->doStateF($line);
				return 5;
				break;
			case 5:
				echo "// Estat E, Bloc acabat<br />";
				//echo $line."<br />";				
				if(preg_match("/^[\s]{1,}/", $line) || preg_match("/^[-]{3,}/", $line)) {
					return 0;
				}
				break;
			default:
				break;
		}
	}
	
	public function doStateA() {
		$this->blockArray[] = new Block();
		echo "Creant NOU Bloc<br />";
	}
	
	public function doStateB($line) {
		$isSeminar = false;
		$isPractical = false;
		$isTheory = true;
		
		if($groups = $this->isThereAGroup($line)) {
			foreach($groups as $group):
				echo "G->".$group."<br />";
				switch(strtolower($group[0]))  {
					case 's':
						$isSeminar = true;
						$isPractical = false;
						$isTheory = false;
						break;
					case 'p':
						$isPractical = true;
						$isTheory = false;
						break;
					default:
						break;
				}
			endforeach;
			foreach($groups as $group):
				if(($isPractical && strtolower($group[0]) == 'p') || ($isSeminar && strtolower($group[0]) == 's') || ($isTheory && sizeof($group) == 1)) {
					$this->getCurrentBlock()->newSession();
					$this->getCurrentBlock()->setSessionGroup($group);
					//fes una nova sessio i enxufali els grups
				}
			endforeach;
		}
		else {
			$this->getCurrentBlock()->newSession();
			$this->getCurrentBlock()->setSessionGroup($this->courseyear->getGrupTeoria());
			//fes una nova sessio i enxufali el grup de teo que li pertoca per carreraCurs
		}
		$this->getCurrentBlock()->setAulas($line);
	}
	
	public function doStateC($line) {
		$this->getCurrentBlock()->setHours($line, $this);
	}
	
	public function doStateD($line) {
		$this->getCurrentBlock()->setType($line);
		return;
	}
	
	public function doStateE($line) {
		//echo "// Estat 6<br />";
		//echo utf8_decode($line)."<br />";
		
		$course = NULL;
		
		$assignatures = Doctrine_Core::getTable('Assignatura')
			->findByCarreraCursId($this->courseyear->getId());
		
		foreach($assignatures as $assignatura) {
			if($assignatura->getNom() == $line) {
				$course = $assignatura;
			}
		}

		if($course){
			$this->logger->debug("Found course " . $line . " in database, id is " . $course->getId());
		}
		// Course doesn't exist, so create it and set its attributes.
		else {
			$this->logger->debug("Course " . $line . " doesn't exist, creating.");
			$course = new Assignatura();
			$course->setNom($line);
			$course->setCarreraCurs($this->courseyear);
			$course->save();
			//echo utf8_decode($line)."<br />";
		}
		
		$this->getCurrentBlock()->setAssignatura($course);
		$this->getCurrentBlock()->setDefaultHours($this->getStart()->format('Y-m-d H:i:s'), $this->getEnd()->format('Y-m-d H:i:s'));
		$this->getCurrentBlock()->saveSessions();
		return;
	}
	
	public function doStateF($line) {
		return;
	}

	public function getBlockArray() {
		return $this->blockArray;
	}

	public function setCourseYear($courseYear)
	{
		$this->courseyear = $courseYear;
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
	
	public function getCurrentBlock() {
		if(sizeof($this->blockArray) > 0) {
			return $this->blockArray[sizeof($this->blockArray) - 1];
		}
	}
	
	public function unsetCurrentBlock() {
		$this->getCurrentBlock()->deleteSessions();
	}
	
		// Looks for a group, and makes use of the $linetype to search that will be like {0 => Aulagrup, 1 => Tipus}
	public function isThereAGroup($group_string) {
		$groups = Array();
		$groups_iter = Array();

		// Look for a SXXX or PXXX as many as exists in the aulagrup line
		$group_sp_pattern_aulagrup = "/([SsPp]+[0-9]{3}+)/";
		// Look for the <<<<theory>>>> group in the type line or aulagrup line. Doesn't work with lowercase group identifier
		$group_t_pattern_type_or_aulagrup = "/[pP]*[-|\s]*(?<![A-Za-z0-9])([A-Z0-9])[-|\s|:][\s]*/";

		$hasThings = Array();
		$hasThings = $this->lineType($group_string);
		
		if($hasThings[0] || $hasThings[1]) {
			////echo utf8_decode($group_string)."<br />";
			if(preg_match_all($group_sp_pattern_aulagrup, $group_string, $groups_iter)) {
				////echo "Match!:<br />";
				foreach($groups_iter[1] as $row):
					 ////echo "--> ".$row."<br />";
					 $groups[] = $row;
				endforeach;
			}
			else
			{
				////echo "No match found<br />";
			}
			////echo utf8_decode($group_string)."<br />";
			if(preg_match_all($group_t_pattern_type_or_aulagrup, $group_string, $groups_iter)) {
				////echo "Match!:<br />";
				foreach($groups_iter[1] as $row):
					 ////echo "--> ".$row."<br />";
					 $groups[] = $row;
				endforeach;
			}
			else
			{
				////echo "No match found<br />";
			}
		}
		//foreach($groups as $row):
		//	////echo "____".$row."<br />";
		//endforeach;
		return $groups;
	}
	
	public function lineType($line) {
		$line = $line;
		$types = Array();
		$aulas = Array();
		$assignaturas = Array();
		$hours = Array();
		
		$hasThings = Array();
		$hasThings[] = false;
		$hasThings[] = false;
		$hasThings[] = false;
		$hasThings[] = false;

		// If line has at least 1 uppercase word is type => 0
		$has_type = "/[ÀÁÈÉÍÏÒÓÚÜÑA-Z]{3,}/";
		// If line is like PXXX: XX.XXX or SXXX: XX.XXX or SXXX - XX.XXX is aulagrup => 1
		$has_aula = "/(?<![0-9])([0-9]{2}.[A-Za-z0-9][0-9]{2})/";
		// This regex is a miracle understandable. Sorry xD NO FUNCIONA DEL TOT, de moment detecta l'assignatura bé però
		// només pot contenir una paraula en majuscula i al final. aula => 2
		$has_assignatura = "/^((?:(?:[ÀÁÇÈÉÍÏÒÓÚÜÑA-Z]?[àáçèéíïòóúüña-z\'\·[:space:]]+)+)+[ÀÁÈÉÍÏÒÓÚÜÑA-Z]*)$/";
		// If line matches at least one hour XX:XX hora =>3
		$has_hour = "/(?<![0-9])([0-2]?[0-9]\s?[:|.]\s?[0-5][0-9])(?![0-9])/";
		
		if(preg_match_all($has_type, $line, $types)) {
			////echo "__TYPE<br />";
			$hasThings[0] = true;
		}
		if(preg_match_all($has_aula, $line, $aulas)) {
			////echo "__AULA<br />";
			$hasThings[1] = true;
		}
		if(preg_match_all($has_assignatura, $line, $assignaturas)) {
			////echo "__ASSIGNATURA<br />";
			$hasThings[2] = true;
		}
		if(preg_match_all($has_hour, $line, $hours)) {
			////echo "__HORA<br />";
			$hasThings[3] = true;
		}
		return $hasThings;
	}
}

class Block
{
	private $sessions;
	
	public function Block() {
		$this->sessions = array();
	}
	
	public function newSession() {
		$this->sessions[] = new Sessio();
	}
	
	public function deleteSessions() {
		$this->sessions = array();
	}
	
	public function getActualSession() {
		if(!$this->isEmpty($this->sessions)) {
			return $this->sessions[sizeof($this->sessions) - 1];
		}
		else {
			return -1;
		}
	}
	
	public function getSessions() {
		return $this->sessions;
	}
	
	public function setSessionAula($aula) {
		$this->getActualSession()->setAula($aula);
	}
	
	// Set the group like P101 or S101 or A or B or C or 1 or 2.
	//TODO handle cases when the grip is inherit
	public function setSessionGroup($group) {
		if(strtolower($group[0]) == 'p') {
			//echo "Prac ".strtolower($group[0])."<br />";
			$this->getActualSession()->setGrupPractiques($group);
			return 'p';
		}
		else if(strtolower($group[0]) == 's') {
			//echo "Sem ".strtolower($group[0])."<br />";
			$this->getActualSession()->setGrupSeminari($group);
			return 's';
		}
		else {			
			//echo "Teo ".strtolower($group[0])."<br />";
			if(!$this->getActualSession()->isGroupSet()) {
				$this->getActualSession()->setGrupTeoria($group);
			}
			return 't';
		}
	}
	
	public function isEmpty($array) {
		if(sizeof($array)) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function setAulas($line) {
		$aulas = Array();
		$ct_aulas = Array();
		
		// If line is like PXXX: XX.XXX or SXXX: XX.XXX or SXXX - XX.XXX is aulagrup => 1
		$has_aula = "/(?<![0-9])([0-9]{2}.[A-Za-z0-9][0-9]{2})/";
		
		if(preg_match_all($has_aula, $line, $aulas)) {
			foreach($aulas[0] as $key => $aula):
				//echo $aula."<br />";
				if($key == 0) {
					$ct_aulas = $aula;
				}
				else {
					$ct_aulas = $ct_aulas." ".$aula;
				}
			endforeach;
			//echo "IMPORTANT --> ".$ct_aulas."<br />";
			foreach($this->getSessions() as $session):
				if(!$session->isAulaSet()) {
					$session->setAula($ct_aulas);
				}
			endforeach;
		}
	}
	
	public function setHours($line, $period) {
		$hours = Array();
		
		$has_hour = "/(?<![0-9])([0-2]?[0-9]\s?[:|.]\s?[0-5][0-9])(?![0-9])/";

		if(preg_match_all($has_hour, $line, $hours)) {
			if(sizeof($hours) > 1) {
				foreach($this->getSessions() as $session):
					if(!$session->isDateTimeSet()) {
						//echo $hours[0][0]."<br />";
						//echo $hours[0][1]."<br />";
//						$session->setDataHoraInici($hours[0][0]);
//						$session->setDataHoraFi($hours[0][1]);
						$session->setDataHoraInici($period->getStart()->format('Y-m-d H:i:s'));
						$session->setDataHoraFi($period->getEnd()->format('Y-m-d H:i:s'));
					}
				endforeach;
			}
			else {
				foreach($this->getSessions() as $session):
					if(!$session->isDateTimeSet()) {
						//echo $hours[0][0]."<br />";
//						$session->setDataHoraFi($hours[0][0]);
						$session->setDataHoraFi($period->getEnd()->format('Y-m-d H:i:s'));
					}
				endforeach;
			}
		}
	}
	
	public function setType($line) {
		foreach($this->getSessions() as $session):
			if(!$session->isTypeSet()) {
				$session->setTipus($line);
			}
		endforeach;
	}
	
	public function setAssignatura($course) {
		foreach($this->getSessions() as $session):
			$session->setAssignatura($course);
		endforeach;
	}
	
	public function saveSessions() {
		foreach($this->getSessions() as $session):
			echo "<br />ES GUARDA LA SESSIO<br /><br />";
			echo $session->getDataHoraInici()." - ";
			echo $session->getDataHoraFi()."<br />";
			$session->save();
		endforeach;
	}
	
	public function setDefaultHours($start, $end) {
		foreach($this->getSessions() as $session):
				$session->setDataHoraInici($start);
				//echo $session->getDataHoraInici()."<br />";
				$session->setDataHoraFi($end);
				//echo $session->getDataHoraFi()."<br />";
		endforeach;
	}
}

