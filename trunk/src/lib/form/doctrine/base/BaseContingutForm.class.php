<?php

/**
 * Contingut form base class.
 *
 * @method Contingut getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseContingutForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'nom'          => new sfWidgetFormInputText(),
      'action_part'  => new sfWidgetFormTextarea(),
      'view_part'    => new sfWidgetFormTextarea(),
      'es_contingut' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'nom'          => new sfValidatorString(array('max_length' => 255)),
      'action_part'  => new sfValidatorString(array('max_length' => 700)),
      'view_part'    => new sfValidatorString(array('max_length' => 700)),
      'es_contingut' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('contingut[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Contingut';
  }

}
