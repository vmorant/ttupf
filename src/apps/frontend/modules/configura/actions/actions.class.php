<?php

/**
 * configura actions.
 *
 * @package    ttupf
 * @subpackage configura
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class configuraActions extends sfActions
{
 /**
	* Executes index action, first step out of 3 to add a new subject.
	* Here the form for choosing a degree is shown.
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
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
			$this->redirect('configura');
		} else {
			$this->forward404(sprintf('No s\'ha pogut desar la configuració.'));
		}
	}
}
