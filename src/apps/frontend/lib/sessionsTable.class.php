<?php

/**
 * Classe per generar una taula HTML a partir d'un vector de sessions.
 */
//TODO en cas d'haver practiques i seminari alhora, mostrar les dues en una cel·la en lloc de dues files amb hora repetida.
class sessionsTable
{
	private $sessionsArray;
	/**
	 * Conté el codi HTML de la taula
	 */
	private $table;
	private $date;

	public function sessionsTable($sessions){
		$this->sessionsArray = $sessions;
		$haveClassesToday = false;

		//Agafem com a date el dia actual
		$this->date = date('d/m/Y', mktime(0, 0, 0, date('m')  , date('d'), date('Y')));
	
		//Agafem un dia determinat pel qual sabem que hi han sessions si no estem en l'entorn de debug:
		if(sfConfig::get('sf_environment') == "dev") {
			$this->date = date('d/m/Y', mktime(0, 0, 0, date(10)  , date(24), date(2011)));
		}

		//TODO s'hauria de mostrar la data de les sessions de la taula; de moment mostra la data de la primera sessio del vector.
		$this->table = "<table id='sessions'><thead><tr><th>".$this->date."</th></tr></thead>";
		
		foreach($this->sessionsArray as $session):
			$dataHoraInici = $session->getDataHoraInici();
			
			$session_date = date('d/m/Y', strtotime($dataHoraInici));
			
			//TODO seleccionar sessions del dia actual per defecte, permetre escollir altres dies.
			if($session_date == $this->date) {
				$haveClassesToday = true;
				$hora = explode(" ", $dataHoraInici);
				$this->table .= "<tr><td>".$hora[1]."</td><td>".$session->getAssignatura()->getNom()."<br />".$session->getTipus()."<br />".$session->getAula()."</td></tr>";
			}			
		endforeach;
		
		$this->table .= "</table>";
	}

	public function toString(){
		if($this->haveClassesToday) {
			return $this->table;
		} else {
			return $this->date."<br />Fantàstic! Tens el dia lliure.<br />";
		}
	}
}
