<?php
/*
 * 
  This file is part of the sfOauthServerPlugin package.
 * (c) Jean-Baptiste Cayrou <lordartis@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 *  * Filter executed before each action secured by OAuth
 * It checks if request are correct and if the consumer has credentials to access to this action
 * @see sfBasicSecurityFilter
 */

class sfOauthFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
	//load oauth configuration
	$actionInstance = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();
	$sfoauth = new sfOauth($this->context,$actionInstance->getModuleName(),$actionInstance->getActionName());
	
	$request  =  $this->context->getRequest();
	$req = OAuthRequest::from_request();
	
	SfContext::getInstance()->getLogger()->debug("Abans de comprovar la versió");
	
	if($req->get_parameter('oauth_version', NULL) == "1.0") // OAuth 1.0
	{
		SfContext::getInstance()->getLogger()->debug("Versio 1.0");
	    $oauthServer = new sfoauthserver(new sfOAuthDataStore());
	    $oauthServer->verify_request($req);
	}
	else if($request->getParameter('oauth_version', NULL) != NULL) // OAuth 2.0
	{
		throw new OAuthException('not supported version');
	}
	else
	{
		SfContext::getInstance()->getLogger()->debug("No hi ha versio");
		throw new OAuthException('oauth_version parameter missing');
	}

	SfContext::getInstance()->getLogger()->debug("Configura coses");
	$token = $req->get_parameter('oauth_token');
	
	$sfToken = Doctrine::getTable('sfOauthServerAccessToken')->findOneByToken($token);
	$user = $sfToken->getUser(); // Select user concerned

	$consumer = $sfToken->getConsumer();
	$consumer->increaseNumberQuery();
	$request->setParameter('sfGuardUser',$user); // save this user in a parameter 'user'
	$request->setParameter('sfOauthConsumer',$consumer); // save consumer in a parameter 'consumer'
    $credential = $sfoauth->getOauthCredential();

	SfContext::getInstance()->getLogger()->debug("Acaba de configurar coses");

    if (null !== $credential && !$sfToken->hasCredential($credential)) {
    	throw new OAuthException('Unauthorized Access');
    } // chek if the consumer is allowed to access to this action

    // this aplpication has access, continue
    $filterChain->execute();
  }  
}
