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

	public function actualitza($courseYear, $date = NULL)
	{	
		$this->logger = sfContext::getInstance()->getLogger();
	
		$this->courseYear = $courseYear;
		$this->sessions = array();

		$timetableDOM = new simple_html_dom();
		//echo $this->courseYear."<br>";
		//echo $timetableDOM->load_file($courseYear->getUrlHorari())."<br>";

		$timetableDOM = file_get_html($courseYear->getUrlHorari());
		
		if(!($timetableDOM && is_object($timetableDOM) && isset($timetableDOM->nodes))) {
			$this->logger->debug("La pàgina HTML és invàlida: ".$this->courseYear);
			return -1;
		}

		// Date of first week is in the first table, second row, second cell.
		$firstWeek = $timetableDOM->find('table', 0)->find('tr', 1)->find('td', 1)->plaintext;
		
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
		//echo "////// /////// /////// Current week start date is ". $currWeekStartDate->format('d.m.Y H:i:s');

		// Create sessions for each period in the timetable and save them to the database.
		$rows = $currWeekDOM->find('tr');
		
		
		for($row = 0; $row < sizeof($rows); $row++){
			if($row > 1)
			{
				$cells = $rows[$row]->find('div');
				$period = new Period($cells[0]->plaintext);
				$period->setCourseYear($this->courseYear);
				$this->logger->info("Start time is " . $period->getStart()->format("H:i:s") . ". End time is " . $period->getEnd()->format("H:i:s"));
				for($cell = 0; $cell < sizeof($cells); $cell++){				
					if($cell > 0)
					{
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
						//echo "*******LA PRUEBA DEL DELITO: ".$cells[$cell]->plaintext."**********<br>";
						// Get the plaintext for the period and insert each line into an array.
						$this->logger->debug("Periodinfoarray is: " . var_export($cells[$cell]->plaintext, true), 'err');

						$periodInfo = html_entity_decode($cells[$cell]->plaintext, ENT_QUOTES, 'UTF-8');
						$periodInfo = Encoding::toUTF8($periodInfo);
						$periodInfoArray = explode("\n", $periodInfo);
						foreach($periodInfoArray as $i => $value):
							unset($periodInfoArray[$i]);
							$periodInfoArray[] = trim($value);
						endforeach;
						// Re-index the array starting from zero.
						$period->setDetails(array_values($periodInfoArray));
						$this->logger->debug("Trimmed periodinfoarray is: " . var_export($period->getDetails(), true));
						
						//echo "parsejant<br />";
						$this->parseSession($period);
					}
				}
				unset($period);
			}
		}
	}

	/**
	 * Given a starting date, calculate the number of weeks that have passed since then.
	 */
	private function calculateCurrentWeek($dateFirstWeek, $date = NULL)
	{
		$dateFirstWeek = ltrim($dateFirstWeek);
		$dateFirstWeek = rtrim($dateFirstWeek);

		$dateFirstWeek = explode("/", $dateFirstWeek);
//		echo $dateFirstWeek;
		
		$dateFirstWeek = new DateTime($dateFirstWeek[2]."/".$dateFirstWeek[1]."/".$dateFirstWeek[0]);
		// Si s'ha especificat una data retornem el número de setmana d'aquella data.
		
//		$dateFirstWeek = new Datetime($dateFirstWeek);
		
		if($date){
			$today = new DateTime($date);
		}
		else{
			$today = new DateTime();
		}
		
		$dateFirstWeek = $dateFirstWeek->format('z');
		$today = $today->format('z');
		
		//echo "Today: ".$today."<br>";
		//echo "DateFirstWeek: ".$dateFirstWeek."<br>";

		if($today >= $dateFirstWeek) {
			$interval = $today - $dateFirstWeek;
	
			// Add one because week number starts at 1 not zero
			$currWeek = floor($interval / 7);
			
			//echo "Numero de semana: ".$currWeek."<br>";
			return $currWeek;
		}
		else {
			//echo "No te sentit mirar l'horari d'abans que comenci el trimestre";

		}

		return -1;		
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
		$firstDay = new DateTime($firstDayWeek);
		$lastDay = new DateTime($firstDayWeek);
		$lastDay->add(new DateInterval('P7D'));

		$weekSessionsCarreraCurs = Doctrine_Query::create()
			->select('s.id')
			->from('Sessio s, Assignatura a')
			->where('s.data_hora_inici < ?', $lastDay->format('Y-m-d H:i:s'))
			->andWhere('s.data_hora_inici > ?', $firstDay->format('Y-m-d H:i:s'))
			->andWhere('s.assignatura_id = a.id')
			->andWhere('a.carrera_curs_id = ?', $this->courseYear->getId())
			->groupBy('s.id')
			->execute();
		
		foreach($weekSessionsCarreraCurs as $sessio):
			$sessio->delete();
		endforeach;
		
		return 0;
	}
}