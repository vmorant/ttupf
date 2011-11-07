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
	* Executes index action.
	*
	* Es pot especificar la data a mostrar a la URL: 
	* /horari/index/X/Y/Z
	*  - X és el dia
	*  - Y és el mes
	*  - Z és l'any amb quatre xifres
  *
  * @param sfRequest $request A request object
  */
	public function executeIndex(sfWebRequest $request)
	{
		// S'ha demanat data específica
		if($request->getParameter('any')){
			// S'ha de normalitzar el dia i mes per a tenir dos dígits,
			// DateTime->format() ho fa.
			$this->data = new DateTime($request->getParameter('any') . "/" . $request->getParameter('mes') . "/" . $request->getParameter('dia'));
			$this->data = $this->data->format('d/m/Y');
		}
		$this->utas = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->getData();
	}

	/**
	 * Actualitza les sessions d'una setmana. Es pot especificar la data a la URL
	 * /horari/actualitza/X/Y/Z on X és el dia, Y el mes i Z l'any amb
	 * quatre xifres. Si no s'especifica data s'actualitza les sessions de la
	 * setmana actual.
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeActualitza(sfWebRequest $request)
	{
		Doctrine_Manager::connection()->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true );
		$query = new Doctrine_Query();
		$query->from('CarreraCurs');
		$carreresCursos = $query->execute();

		// És un paràmetre opcional del mètode actualitza, l'inicialitzem a NULL.
		$date = NULL;

		// S'ha especificat una data per actualitzar
		if($request->getParameter('any')){
			$date = $request->getParameter('any') . '/' . $request->getParameter('mes') . '/' . $request->getParameter('dia');
		}	

		foreach($carreresCursos as $carreraCurs):
			$taulaSessions = Doctrine::getTable('Sessio');
			$taulaSessions->actualitza($carreraCurs, $date);
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
