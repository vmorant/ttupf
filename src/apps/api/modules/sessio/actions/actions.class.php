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
		//$user és l'objecte sfGuardUser
		$user = $request->getParameter('sfGuardUser');
		$utas = $user->getUsuariTeAssignatures()->getData();
		
		//TODO maybe some grup_practiques or grip-seminari has many groups. We have to support thet
		// Creem vector de sessions a partir de la qual es generara una taula d'horari.
		$this->sessionsArray = array();
		foreach($utas as $uta):
			$sessions = $uta->getAssignatura()->getSessions()->getData();
			foreach($sessions as $session):
				if($session->getGrupSeminari()) {
					if($session->getGrupSeminari() == $uta->getGrupSeminari()) {
						$this->sessionsArray[] = $session;
					}
				}
				else if($session->getGrupPractiques()) {
					if($session->getGrupPractiques() == $uta->getGrupPractiques()) {
							$this->sessionsArray[] = $session;
					}
				}
				else if($session->getGrupTeoria()) {
					if($session->getGrupTeoria() == $uta->getGrupTeoria()) {
						$this->sessionsArray[] = $session;
					}
				}
			endforeach;
		endforeach;
		
		//Un cop obtingudes les sessions per aquel día, s'ordenen en funcio de l'hora.
		foreach ($this->sessionsArray as $key => $sessio) {
		    $dates[$key] = $sessio->getDataHoraInici(); 
		}

		if(isset($dates)){
			array_multisort($dates, SORT_ASC, $this->sessionsArray);
		}

/*		$this->test = array();

		foreach($utas as $key => $uta):
			$this->test["Assignatura ".$key] = $uta->getAssignatura()->getNom();
		endforeach;
		
		return $this->setTemplate('test');
*/	}
}
