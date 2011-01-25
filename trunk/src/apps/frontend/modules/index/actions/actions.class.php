<?php

/**
 * index actions.
 *
 * @package    ttupf
 * @subpackage index
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class indexActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    echo "- Estat:<br>";
	
    if($this->getUser()->isAuthenticated()){
    	echo "-- Acreditat<br>";
    	
    	$this->usuari_id = $this->getUser()->getGuardUser()->getId();		      	
		echo "--- Nom d'usuari: ".$this->getUser()->getGuardUser()->getUsername()."<br>";
	
		$this->utas = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->getData();
		$this->utas_size = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->count();
		
		if($this->utas_size > 0){
			echo "--- Horari configurat<br>";
			echo "---- Assignatures:<br>";
			
			foreach($this->utas as $this->uta):
				echo "----- ".$this->uta->getAssignatura()->getNom()."<br>";	
				$this->sessions = $this->uta->getAssignatura()->getSessions()->getData();
				foreach($this->sessions as $this->sessio):
					$fecha = $this->sessio->getDataHoraInici();
					echo $fecha." -> ";
					echo "----- ".$this->sessio."<br>";
				endforeach;
			endforeach;	
		}
		else{
			echo "Horari sense configurar<br>";
		}
    }
    else{
    	echo "Sense credencials";
    }
  }
  
    public function executeAcercaDe(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
    public function executeCond(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
}
