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
    echo "Estat:<br>";
	
    if($this->getUser()->isAuthenticated()){
    	echo "Acreditat<br>";
    	
    	$this->usuari_id = $this->getUser()->getGuardUser()->getId();
   
       	$this->utas = Doctrine_Core::getTable('UsuariTeAssignatures')
       		->createQuery('a')
      		->execute();
      	
		echo "Usuari id: ".$this->usuari_id."<br>";
	
		$this->configurat = false;
	
		foreach ($this->utas as $uta):
    	
	    	if($uta->getUsuariId() == $this->usuari_id){
    			$this->configurat = true;
    			echo $uta->getAssignaturaId()."<br>";
    		}
    	
		endforeach;
    			
		if($this->configurat){
   			echo "Configurat";
		}
		else
			echo "No configurat";

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
