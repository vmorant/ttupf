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
    $this->forward('default', 'module');
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