<?php

/**
 * horari actions.
 *
 * @package    ttupf
 * @subpackage horari
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class horariActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function executeIndex(sfWebRequest $request)
	{
		$this->utas = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->getData();
	}

	public function executeActualitza(sfWebRequest $request)
	{
		Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true );
		$query = new Doctrine_Query();
		$query->from('CarreraCurs');
		$carreresCursos = $query->execute();
		
		$this->logMessage('CarreresCursos és: '.$carreresCursos, 'Debug');
		
		foreach($carreresCursos as $carreraCurs):
			$taulaSessions = Doctrine::getTable('Sessio');
			$taulaSessions->actualitza($carreraCurs);
		endforeach;
	}
	
	public function executeConfig(sfWebRequest $request)
	{
		Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true );
		$query = new Doctrine_Query();
		$query->from('CarreraCurs');
		$this->carreresCursos = $query->execute();
	}
}
