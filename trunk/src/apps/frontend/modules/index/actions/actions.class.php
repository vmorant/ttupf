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
    echo "Hey!";
    echo $this->getUser();
    if(($this->getUser()->isAuthenticated()) && (1)){
    	echo "Loguejat i Configurat";
    }
    if($this->getUser()->isAuthenticated()){
    	echo "Loguejat";
    }
    if(1){
    	echo "Configurat";
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
