<?php

/**
 * sessio actions.
 *
 * @package    ttupf
 * @subpackage sessio
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sessioActions extends sfActions
{
	public function preExecute()
	{
		$sfoauth = new sfOauth(sfContext::getInstance(),$this->getModuleName(),$this->getActionName());
		$sfoauth->connectEvent();
		sfConfig::set('sf_web_debug',false);
		
		//$this->forward404Unless($request->getParameter('username') && $request->getParameter('password'));
	}
	
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function executeTest(sfWebRequest $request)
	{
		$this->test = array('test' => "Text de prova");
		return $this->setTemplate('test');
	}
}
