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

	public function sessionsTable($sessions){
		$this->sessionsArray = $sessions;
		$date = explode(" ", $this->sessionsArray[0]->getDataHoraInici());
		$date = $date[0];
		//TODO s'hauria de mostrar la data de les sessions de la taula; de moment mostra la data de la primera sessio del vector.
		$this->table = "<table id='sessions'><thead><tr><th>".$date."</th></tr></thead>";
		
		foreach($this->sessionsArray as $session):
			$dataHoraInici = $session->getDataHoraInici();
			$dia = date('d', strtotime($dataHoraInici));
			$mes = date('m', strtotime($dataHoraInici));
			$any = date('Y', strtotime($dataHoraInici));
			
			//TODO seleccionar sessions del dia actual per defecte, permetre escollir altres dies.
			if(($dia == 1) && ($mes = 3) && ($any == 2011)) {
				$hora = explode(" ", $dataHoraInici);
				$this->table .= "<tr><td>".$hora[1]."</td><td>".$session->getAssignatura()->getNom()."<br />".$session->getTipus()."<br />".$session->getAula()."</td></tr>";
			}
		endforeach;
		
		$this->table .= "</table>";
	}

	public function toString(){
		return $this->table;
	}
}
