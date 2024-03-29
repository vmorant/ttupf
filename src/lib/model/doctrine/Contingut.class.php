<?php

/**
 * Contingut
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ttupf
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Contingut extends BaseContingut
{
	public function slugify($text)
	{ 	
		$notAllowed = array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�", "?", " ");
		$allowed = array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E", "", "");

	    // replace and lowercase
	    $text = strtolower(str_replace($notAllowed, $allowed, $text));

	    return $text;
	}
	
	public function getNomSlug()
	{
	  return $this->slugify($this->getNom());
	}
}
