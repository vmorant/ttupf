<?php

/**
 * UsuariEspiaUsuari form base class.
 *
 * @method UsuariEspiaUsuari getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsuariEspiaUsuariForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'usuari_base'     => new sfWidgetFormInputHidden(),
      'usuari_objectiu' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'usuari_base'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('usuari_base')), 'empty_value' => $this->getObject()->get('usuari_base'), 'required' => false)),
      'usuari_objectiu' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('usuari_objectiu')), 'empty_value' => $this->getObject()->get('usuari_objectiu'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('usuari_espia_usuari[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsuariEspiaUsuari';
  }

}
