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
		$utas = $this->getUser()->getGuardUser()->getUsuariTeAssignatures()->getData();
		
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
		
		//Un cop obtingudes les sessions per aquel día, s'ordenen en nuncio de l'hora.
		foreach ($this->sessionsArray as $key => $sessio) {
		    $dates[$key]  = $sessio->getDataHoraInici(); 
		}

		if(isset($dates)){
			array_multisort($dates, SORT_ASC, $this->sessionsArray);
		}
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
	
 /**
	* Executes config action, first step out of 3 to add a new subject.
	* Here the form for choosing a degree is shown.
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig(sfWebRequest $request)
  {
		$this->form = new ConfigureSelectDegreeForm();
  }

	/**
	 * Second step, choose a subject.
	 */
	public function executeAssignatura(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
		$this->forward404Unless($degree = Doctrine_Core::getTable('CarreraCurs')->find(array($request->getParameter('carreres'))), sprintf('CarreraCurs amb id \'%s\' no existeix.', $request->getParameter('id')));

		$this->form = new ConfigureSelectSubjectForm($degree);
	}

	/**
	 * Final step, choose theory, practical and seminar groups. The UsuariTeAssignatura
	 * object is created here, which is bad if the process is interrupted as it leaves
	 * a subject added but without the groups configured.
	 */
	public function executeGrups(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
		$this->forward404Unless($assignatura = Doctrine_Core::getTable('Assignatura')->find(array($request->getParameter('assignatures'))), sprintf('No s\'ha pogut desar la subscripció, torna-ho a intentar.'));

		$uta = new UsuariTeAssignatura();
		$uta->setAssignaturaId($assignatura->getId());
		$uta->setUsuariId($this->getUser()->getGuardUser()->getId());
		$uta->save();

		$this->form = new ConfigureSelectGroupsForm($uta);
	}
	
	/**
	 * Edit the newly created subscription after the groups has been submitted.
	 */
	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
		$this->forward404Unless($uta = Doctrine_Core::getTable('UsuariTeAssignatura')->find(array($request->getParameter('usuari_id'), $request->getParameter('assignatura_id'))), sprintf('No s\'ha pogut desar la configuració.'));

		$form = new ConfigureSelectGroupsForm($uta);
		$form->bind($request->getParameter($form->getName()));

		if($form->isValid()){
			$form->save();

			// Allow user to add another subject without extra clicks.
			$this->redirect('horari');
		} else {
			$this->forward404(sprintf('No s\'ha pogut desar la configuració.'));
		}
	}
}
