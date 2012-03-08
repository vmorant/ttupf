<?php

/**
 * sfGuardAuth actions.
 *
 * @package    ttupf
 * @subpackage sfGuardAuth
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardAuthActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeSignin($request)
  {
    $user = $this->getUser();

    if ($user->isAuthenticated())
    {
		return $this->redirect('user/index.json');
    }

    $message = 'Authentification required';

    $this->form = new sfGuardFormSignin;

    if (isset($_SERVER['PHP_AUTH_USER']))
    {
      $request->setParameter('signin', array(
        'username' =>$_SERVER['PHP_AUTH_USER'],
        'password' =>$_SERVER['PHP_AUTH_PW'],
      ));

      $this->form->bind($request->getParameter('signin'));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();
        
        $user = $this->getUser()->signin($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

		$request->setParameter('user', $user);
		return $this->redirect('user/index.json');
      }
      else
      {
        $message = $this->form->getErrorSchema();
      }
    }

    $header_message = "Basic realm=\"$message\"";

    $this->getResponse()->setStatusCode(401);
    $this->getResponse()->setHttpHeader('WWW_Authenticate', $header_message);

    return sfView::NONE;
  }
  
  public function executeSignout($request)
  {
    $this->getUser()->signOut();

    $signoutUrl = '@homepage';

    $this->redirect('' != $signoutUrl ? $signoutUrl : '@homepage');
  }

  public function executeSecure($request)
  {
    $this->getResponse()->setStatusCode(403);
  }

  public function executePassword($request)
  {
    throw new sfException('This method is not yet implemented.');
  }
}
