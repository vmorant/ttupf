<?php

/**
 * Classe per generar una taula HTML a partir d'un vector de sessions.
 */
//TODO en cas d'haver practiques i seminari alhora, mostrar les dues en una cel·la en lloc de dues files amb hora repetida.
class sessionsTable
{
	private $logger;
	private $sessionsArray;
	/**
	 * Conté el codi HTML de la taula
	 */
	private $table;
	private $date;
	private $haveClassesToday;

	public function sessionsTable($sessions){
		$this->logger = SfContext::getInstance()->getLogger();
		$this->sessionsArray = $sessions;
		$this->haveClassesToday = false;

		//Agafem com a date el dia actual
		$this->date = date('d/m/Y', mktime(0, 0, 0, date('m')  , date('d'), date('Y')));
		}

	/**
	 * Genera el codi HTML de la taula d'horari
	 */
	public function generaHtml(){
		$this->table = "<table id='sessions'><thead><tr><th>".$this->date."</th></tr></thead>";
		
		foreach($this->sessionsArray as $session):
			$dataHoraInici = $session->getDataHoraInici();
			
			$session_date = date('d/m/Y', strtotime($dataHoraInici));
			
			if($session_date == $this->date) {
				$this->haveClassesToday = true;
				$hora = explode(" ", $dataHoraInici);
				$this->table .= "<tr><td>".$hora[1]."</td><td>".$session->getAssignatura()->getNom()."<br />".$session->getTipus()."<br />".$session->getAula()."</td></tr>";
			}
			else{
				$this->logger->debug('Wrong day for session');
				$this->logger->debug('session_date is: ' . $session_date . ' specified date is: ' . $this->date);
			}
		endforeach;
		
		$this->table .= "</table>";
		$this->logger->debug("Html generated is: " . $this->table);
	}

	public function toString(){
		if($this->haveClassesToday) {
			return $this->table;
		} else {
			return $this->date."<br />Fantàstic! Tens el dia lliure.<br />";
		}
	}

	/**
	 * Fixa la data a mostrar.
	 *
	 * @param String $date Data a mostrar en format mes/dia/any 
	 */
	public function setDate($date){
		$this->date = $date;
	}

	public function getDate(){
		return $this->date;
	}
}
