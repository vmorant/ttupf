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
  	}
  
    public function executeContingut(sfWebRequest $request) {
    	$this->contingut_actual = $this->getRoute()->getObject();
	    $this->continguts = Doctrine::getTable('Contingut')
			->createQuery('q')
	    	->execute();
		
		if($this->contingut_actual->getId() == 1) {
			$this->form = new sfForm();
  			$this->form->setWidgets(array(
    			'nom'    => new sfWidgetFormInputText(),
    			'email'   => new sfWidgetFormInputText(array('default' => 'me@example.com')),
    			'assumpte' => new sfWidgetFormChoice(array('choices' => array('Subject A', 'Subject B', 'Subject C'))),
    			'missatge' => new sfWidgetFormTextarea(),
  			));
		}
		
		$this->opcions = $this->contingut_actual->getContingutTeOpcions();
  	}
    
	public function executeLogin(sfWebRequest $request) {
		$this->forward('index', 'index');
  	}
}
