<?php

/**
 * Sessio form base class.
 *
 * @method Sessio getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSessioForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'data_hora_inici' => new sfWidgetFormDateTime(),
      'data_hora_fi'    => new sfWidgetFormDateTime(),
      'assignatura_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Assignatura'), 'add_empty' => false)),
      'aula'            => new sfWidgetFormInputText(),
      'tipus'           => new sfWidgetFormInputText(),
      'grup_seminari'   => new sfWidgetFormInputText(),
      'grup_practiques' => new sfWidgetFormInputText(),
      'grup_teoria'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'data_hora_inici' => new sfValidatorDateTime(),
      'data_hora_fi'    => new sfValidatorDateTime(),
      'assignatura_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Assignatura'))),
      'aula'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tipus'           => new sfValidatorString(array('max_length' => 255)),
      'grup_seminari'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'grup_practiques' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'grup_teoria'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sessio[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Sessio';
  }

}
