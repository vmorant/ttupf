<?php

/**
 * UsuariTeAssignatures form base class.
 *
 * @method UsuariTeAssignatures getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsuariTeAssignaturesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'usuari_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'assignatura_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Assignatura'), 'add_empty' => false)),
      'grup_practiques' => new sfWidgetFormInputText(),
      'grup_seminari'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'usuari_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'assignatura_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Assignatura'))),
      'grup_practiques' => new sfValidatorInteger(array('required' => false)),
      'grup_seminari'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('usuari_te_assignatures[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsuariTeAssignatures';
  }

}
