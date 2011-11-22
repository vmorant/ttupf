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
    	else{
    		$this->contingut_actual = Doctrine::getTable('Contingut')->find(array(4));

			if($this->contingut_actual->getActionPart() != NULL) {
				eval($this->contingut_actual->getActionPart());
			}
			
			$this->opcions = $this->contingut_actual->getContingutTeOpcions();
			$this->view_part = $this->contingut_actual->getViewPart();
    	}
  	}
  
	public function executeContingut(sfWebRequest $request) {
    	$this->contingut_actual = $this->getRoute()->getObject();
    	$this->forward404Unless($this->contingut_actual->getEsContingut());
    	
	    $this->continguts = Doctrine::getTable('Contingut')
			->createQuery('q')
			->where('q.es_contingut = true')
	    	->execute();
		
		if($this->contingut_actual->getActionPart() != NULL) {
			eval($this->contingut_actual->getActionPart());
		}
	
		$this->opcions = $this->contingut_actual->getContingutTeOpcions();
		
		$this->view_part = $this->contingut_actual->getViewPart();
  	}
    
	public function executeLogin(sfWebRequest $request) {
		$this->forward('index', 'index');
  	}
}
