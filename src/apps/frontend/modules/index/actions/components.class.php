<?php

/**
 * index components.
 *
 * @package    ttupf
 * @subpackage index
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class indexComponents extends sfComponents
{
 /**
  * Executes footer component
  *
  * @param sfRequest $request A request object
  */
  	public function executeFooter(sfWebRequest $request) {
	    $this->continguts = Doctrine::getTable('Contingut')
			->createQuery('q')
			->where('q.es_contingut = true')
	    	->execute();
  	}
}