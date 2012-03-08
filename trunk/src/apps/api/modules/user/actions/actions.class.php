<?php

/**
 * user actions.
 *
 * @package    ttupf
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
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
  public function executeLogin(sfWebRequest $request) {
    $this->forward('user', 'index');
  }
  public function executeLogout(sfWebRequest $request) {
    $this->forward('sfGuardAuth', 'signout');
  }
}
