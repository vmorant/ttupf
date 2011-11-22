<?php

/**
 * Assignatura form base class.
 *
 * @method Assignatura getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAssignaturaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'nom'             => new sfWidgetFormInputText(),
      'carrera_curs_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CarreraCurs'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'nom'             => new sfValidatorString(array('max_length' => 255)),
      'carrera_curs_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('CarreraCurs'))),
    ));

    $this->widgetSchema->setNameFormat('assignatura[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Assignatura';
  }

}
