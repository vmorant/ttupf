<?php 
/**
 * @author   "Enric León" <nothingbuttumbleweed@gmail.com>
 */

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
		$start = explode(':', $timeArray[0]);
		$start[0] = ltrim($start[0]);

		$this->logger->debug('start array is: ' . var_export($start, true));

		// same with end
		$end = explode(':', $timeArray[1]);
		// end minutes has a trailing 'h' we need to trim
		$end[1] = rtrim($end[1]);
		
		$this->logger->debug('end array is: ' .var_export($end, true));
		$this->start->setTime(intval($start[0]), intval($start[1]));
		$this->end->setTime(intval($end[0]), intval($end[1]));
	}
	
	public function stateMachine($current_state, $line) {
//		$line = iconv("ISO-8859-1", "UTF-8", $line);
		////echo "^".$line."$<br />";
		switch($current_state) {
			case 0:
				////echo "// Estat A<br />";
				//////echo $line."<br />";

				$hasThings = $this->lineType($line);
				
				// Seguent estat
				if($hasThings[1] && ($this->isThereAGroup($line)) && $hasThings[0]) {
					//echo "__Aula i Grup --> B, Tipus --> D<br />";
					$this->doStateA();
					$this->doStateB($line);
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[1] && $this->isThereAGroup($line)) {
					//echo "__Aula i Grup --> B<br />";
					$this->doStateA();
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[1]) {
					//echo "__Aula --> B<br />";
					$this->doStateA();
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[0]) {
					//echo "__Tipus --> D<br />";
					$this->doStateA();
					$this->doStateD($line);
					return 3;
				}
				else if(!$line || ((preg_match("/^[\s]{1,}/", $line) || preg_match("/^[-]{3,}/", $line)))) {
					//echo "__Linea Buida<br />";
					return 0;
				}
				else {
					//echo "__Linea Invalida<br />";
					return 0;
				}
				break;
			case 1:
				//echo "// Estat B<br />";
				////echo $line."<br />";
				$hasThings = $this->lineType($line);

				// Seguent estat
				if($hasThings[1] && $this->isThereAGroup($line)) {
					//echo "__Aula i Grup --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[1]) {
					//echo "__Aula --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[3]) {
					//echo "__Hora --> C<br />";
					$this->doStateC($line);
					return 2;
				}
				else if (($groups = $this->isThereAGroup($line)) && $hasThings[0]) {
					//echo "__Tipus i Grup --> D<br />";
					foreach($groups as $group):
						$this->getCurrentBlock()->setSessionGroup($group, $this->courseyear->getGrupTeoria());	
					endforeach;
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[0]) {
					//echo "__Tipus --> D<br />";
					$this->doStateD($line);
					return 3;
				}
				else {
					//echo "Something went wrong in state B<br />";
				}
				break;
			case 2:
				//TODO: Maybe can be created some way from c to e
				//echo "// Estat C<br />";
				//////echo $line."<br />";
				$hasThings = $this->lineType($line);
				
				// Seguent estat
				if($hasThings[1] && $this->isThereAGroup($line)) {
					//echo "__Aula i Grup --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[0]) {
					//echo "__Tipus --> D<br />";
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[2]) {
					//echo "__Assignatura --> E<br />";
					$this->doStateE($line);					
					return 5;
				}
				else {
					//echo "Line:\"".$line."\"<br />";
					//echo "State C: Something is wrong in this line.<br />";
				}
				break;
			case 3:
				//echo "// Estat D<br />";
				////echo "-----------------_->".$line."<-------<br />";
				
				if($line == NULL) {
					////echo "HEYYYYY";
					$this->unsetCurrentBlock();
					return -1;
				}
				
				$hasThings = $this->lineType($line);
				
				// Seguent estat
				if($hasThings[2]) {
					//echo "__Assignatura --> E<br />";
					$this->doStateE($line);					
					return 5;
				}
				else if($hasThings[1] && $this->isThereAGroup($line) && $hasThings[0]) {
					//echo "__Aula i Grup --> B, Tipus --> D<br />";
					$this->doStateB($line);
					$this->doStateD($line);
					return 3;
				}
				else if($hasThings[1] && $this->isThereAGroup($line)) {
					//echo "__Aula i Grup --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else if($hasThings[3]) {
					//echo "__Hora --> C<br />";
					$this->doStateC($line);
					return 2;
				}
				else if($hasThings[1]) {
					//echo "__Aula --> B<br />";
					$this->doStateB($line);
					return 1;
				}
				else {
					//echo "Dia Festiu o l'assignatura no te nom<br />";
				}
				break;
			case 4:
				//echo "// Estat F<br />";
				////echo $line."<br />";
				
				// Seguent estat
				$this->doStateF($line);
				return 5;
				break;
			case 5:
				//echo "// Estat E, Bloc acabat<br />";
				////echo $line."<br />";				
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
		////echo "Creant NOU Bloc<br />";
	}
	
	public function doStateB($line) {
		$isSeminar = false;
		$isPractical = false;
		$isTheory = true;
		
		if($groups = $this->isThereAGroup($line)) {
			foreach($groups as $group):
				////echo "G->".$group."<br />";
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
					$this->getCurrentBlock()->setSessionGroup($group, $this->courseyear->getGrupTeoria());
					$this->getCurrentBlock()->getActualSession()->setDataHoraInici($this->getStart()->format('Y-m-d H:i:s'));
					$this->getCurrentBlock()->getActualSession()->setDataHoraFi($this->getEnd()->format('Y-m-d H:i:s'));
					//fes una nova sessio i enxufali els grups
				}
			endforeach;
		}
		else {
			$this->getCurrentBlock()->newSession();
			$this->getCurrentBlock()->setSessionGroup($this->courseyear->getGrupTeoria(), $this->courseyear->getGrupTeoria());
			$this->getCurrentBlock()->getActualSession()->setDataHoraInici($this->getStart()->format('Y-m-d H:i:s'));
			$this->getCurrentBlock()->getActualSession()->setDataHoraFi($this->getEnd()->format('Y-m-d H:i:s'));
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
		////echo "// Estat 6<br />";
		////echo "EL que hi ha a line: ".bin2hex($line)."<br>";
		
		$course = Doctrine_Query::create()
			->select('a.id')
			->from('Assignatura a')
			->where('a.carrera_curs_id = ?', $this->courseyear->getId())
			->andWhere('a.nom = ?', $line)
			->fetchOne();

		if($course){
			$this->logger->debug("Found course " . $line . " in database, id is " . $course->getId());
		}
		// Course doesn't exist, so create it and set its attributes.
		else {
			////echo $line."<br />";
			$this->logger->debug("Course " . $line . " doesn't exist, creating.");
			$course = new Assignatura();
			$course->setNom($line);
			$course->setCarreraCurs($this->courseyear);
			$course->save();
			
		}
		
		$this->getCurrentBlock()->setAssignatura($course);
		$this->getCurrentBlock()->setDefaultType();
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
			//////echo utf8_decode($group_string)."<br />";
			if(preg_match_all($group_sp_pattern_aulagrup, $group_string, $groups_iter)) {
				//////echo "Match!:<br />";
				foreach($groups_iter[1] as $row):
					 //////echo "--> ".$row."<br />";
					 $groups[] = $row;
				endforeach;
			}
			else
			{
				//////echo "No match found<br />";
			}
			//////echo utf8_decode($group_string)."<br />";
			if(preg_match_all($group_t_pattern_type_or_aulagrup, $group_string, $groups_iter)) {
				//////echo "Match!:<br />";
				foreach($groups_iter[1] as $row):
					 //////echo "--> ".$row."<br />";
					 $groups[] = $row;
				endforeach;
			}
			else
			{
				//////echo "No match found<br />";
			}
		}
		//foreach($groups as $row):
		//	//////echo "____".$row."<br />";
		//endforeach;
		return $groups;
	}
	
	public function lineType($line) {
		////echo $line."<br>";
	
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
		$has_type = "/[ÀÁÈÉÍÏÒÓÚÜÑA-Z]{4,}/";
		// If line is like PXXX: XX.XXX or SXXX: XX.XXX or SXXX - XX.XXX is aulagrup => 1
		$has_aula = "/(?<![0-9])([0-9]{2}.[A-Za-z0-9][0-9]{2})/";
		// This regex is a miracle understandable. Sorry xD NO FUNCIONA DEL TOT, de moment detecta l'assignatura bé però
		// només pot contenir una paraula en majuscula i al final. aula => 2
		$has_assignatura = "/^((?:(?:[ÀÁÇÈÉÍÏÒÓÚÜÑA-Z]?[àáçèéíïòóúüña-z\'\’\·[:space:]]+)+)+[ÀÁÈÉÍÏÒÓÚÜÑA-Z]*)$/";
		// If line matches at least one hour XX:XX hora =>3
		$has_hour = "/(?<![0-9])([0-2]?[0-9][:|.][0-5][0-9])(?![0-9])/";
		
		////echo "Mirant la linia: ".utf8_decode($line)."<br>";
		
		if(preg_match_all($has_type, $line, $types)) {
			sfContext::getInstance()->getLogger()->debug("__TYPE");
			////echo "__TYPE<br />";
			$hasThings[0] = true;
		}
		if(preg_match_all($has_aula, $line, $aulas)) {
			sfContext::getInstance()->getLogger()->debug("__AULA");
			////echo "__AULA<br />";
			$hasThings[1] = true;
		}
		if(preg_match_all($has_assignatura, $line, $assignaturas)) {
			sfContext::getInstance()->getLogger()->debug("__ASSIGNATURA");
			////echo "__ASSIGNATURA<br />";
			$hasThings[2] = true;
		}
		if(preg_match_all($has_hour, $line, $hours)) {
			sfContext::getInstance()->getLogger()->debug("__HORA");
			////echo "__HORA<br />";
			$hasThings[3] = true;
		}
		return $hasThings;
	}
}