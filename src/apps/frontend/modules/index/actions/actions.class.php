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
  	public function executeIndex(sfWebRequest $request) {	
    	$this->usuariAcreditat = $this->getUser()->isAuthenticated();
    	
    	if($this->usuariAcreditat) {
	    	$this->utas = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->getData();
			$this->utas_size = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->count();
			
			if($this->utas_size > 0) {
				$this->redirect($this->generateUrl('default', array('module' => 'horari', 'action' => 'index')));
			}
			else {
				$this->redirect($this->generateUrl('default', array('module' => 'horari', 'action' => 'config')));
			}
    	}
    	else {
    		echo "L'usuari no està acreditat. S'hauria de mostrar l'índex amb totes les seves opcions.";
    	}
  	}
  
    public function executeContingut(sfWebRequest $request) {
    	echo "Contingut";
  	}
    
	public function executeLogin(sfWebRequest $request) {
		$this->forward('index', 'index');
  	}
}
