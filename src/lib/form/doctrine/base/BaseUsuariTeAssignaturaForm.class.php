<?php

/**
 * UsuariTeAssignatura form base class.
 *
 * @method UsuariTeAssignatura getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsuariTeAssignaturaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'usuari_id'       => new sfWidgetFormInputHidden(),
      'assignatura_id'  => new sfWidgetFormInputHidden(),
      'grup_practiques' => new sfWidgetFormInputText(),
      'grup_seminari'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'usuari_id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('usuari_id')), 'empty_value' => $this->getObject()->get('usuari_id'), 'required' => false)),
      'assignatura_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('assignatura_id')), 'empty_value' => $this->getObject()->get('assignatura_id'), 'required' => false)),
      'grup_practiques' => new sfValidatorInteger(array('required' => false)),
      'grup_seminari'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('usuari_te_assignatura[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsuariTeAssignatura';
  }

}
