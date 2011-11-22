<?php

/**
 * ContingutTeOpcio form base class.
 *
 * @method ContingutTeOpcio getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseContingutTeOpcioForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'contingut_id' => new sfWidgetFormInputHidden(),
      'opcio_id'     => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'contingut_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('contingut_id')), 'empty_value' => $this->getObject()->get('contingut_id'), 'required' => false)),
      'opcio_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('opcio_id')), 'empty_value' => $this->getObject()->get('opcio_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('contingut_te_opcio[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ContingutTeOpcio';
  }

}
