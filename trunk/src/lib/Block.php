<?php 
/**
 * @author   "Enric León" <nothingbuttumbleweed@gmail.com>
 */
  
class Block
{
	private $sessions;
	private $timed;
	
	public function Block() {
		$this->sessions = array();
		$this->timed = array(); 
	}
	
	public function newSession() {
		$this->sessions[] = new Sessio();
		$this->timed[] = false;
	}
	
	public function deleteSessions() {
		$this->sessions = array();
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
	
	public function getTimed($key) {
		return $this->timed[$key];
	}
	
	public function setTimed($key) {
		$this->timed[$key] = true;
	}
	
	public function setSessionAula($aula) {
		$this->getActualSession()->setAula($aula);
	}
	
	// Set the group like P101 or S101 or A or B or C or 1 or 2.
	//TODO handle cases when the grip is inherit
	public function setSessionGroup($group, $courseyear_group) {
		if(strtolower($group[0]) == 'p') {
			////echo "Prac ".strtolower($group[0])."<br />";
			$this->getActualSession()->setGrupPractiques($group);
			return 'p';
		}
		else if(strtolower($group[0]) == 's') {
			////echo "Sem ".strtolower($group[0])."<br />";
			$this->getActualSession()->setGrupSeminari($group);
			return 's';
		}
		else {			
			////echo "Teo ".strtolower($group[0])."<br />";
			if(!$this->getActualSession()->isGroupSet()) {
				if(!is_numeric($group)) {
					$this->getActualSession()->setGrupTeoria($courseyear_group.$group);
				}
				else {
					$this->getActualSession()->setGrupTeoria($group);
				}
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
				////echo $aula."<br />";
				if($key == 0) {
					$ct_aulas = $aula;
				}
				else {
					$ct_aulas = $ct_aulas." ".$aula;
				}
			endforeach;
			////echo "IMPORTANT --> ".$ct_aulas."<br />";
			foreach($this->getSessions() as $session):
				if(!$session->isAulaSet()) {
					$session->setAula($ct_aulas);
				}
			endforeach;
		}
	}
	
	public function setHours($line, $period) {
		$hours = Array();
				
		$has_hour = "/(?<![0-9])([0-2]?[0-9][:|.][0-5][0-9])(?![0-9])/";

		if(preg_match_all($has_hour, $line, $hours)) {
			if(sizeof($hours) > 1) {
				foreach($this->getSessions() as $key => $session):
					if(!$this->getTimed($key)) {
						$hour = explode(":", $hours[0][0]);
						$start = $session->getDataHoraInici();
						$start = new DateTime($start);
						$start->setTime($hour[0], $hour[1], 00);	
						$session->setDataHoraInici($start->format('Y-m-d H:i:s'));						

						$hour = explode(":", $hours[0][1]);
						$end = $session->getDataHoraInici();
						$end = new DateTime($end);
						$end->setTime($hour[0], $hour[1], 00);							
						$session->setDataHoraFi($end->format('Y-m-d H:i:s'));						
					
						$this->setTimed($key);
					}
				endforeach;
			}
			else {
				foreach($this->getSessions() as $key => $session):
					if(!$this->getTimed($key)) {
						$hour = explode(":", $hours[0][0]);
						$end = $session->getDataHoraInici();
						$end = new DateTime($end);
						$end->setTime($hour[0], $hour[1], 00);
						$session->setDataHoraFi($end->format('Y-m-d H:i:s'));							
	
						$this->setTimed($key);
					}
				endforeach;
			}
		}
	}

	public function setDefaultType() {
		foreach($this->getSessions() as $session):
			if(!$session->isTypeSet()) {
				if($session->getGrupSeminari()) {
					$session->setTipus("SEMINARIS");
				}
				else if($session->getGrupPractiques()) {
					$session->setTipus("PRÀCTIQUES");
				}
				else if($session->getGrupTeoria("TEORIA")) {
					$session->setTipus();
				}
			}
		endforeach;
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
			////echo "<br />ES GUARDA LA SESSIO<br /><br />";
			////echo $session->getDataHoraInici()." - ";
			////echo $session->getDataHoraFi()."<br />";
			$session->save();
		endforeach;
	}
}