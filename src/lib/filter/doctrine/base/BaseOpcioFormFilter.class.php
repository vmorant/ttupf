<?php

/**
 * Opcio filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOpcioFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nom'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'module' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'action' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'nom'    => new sfValidatorPass(array('required' => false)),
      'module' => new sfValidatorPass(array('required' => false)),
      'action' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('opcio_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Opcio';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'nom'    => 'Text',
      'module' => 'Text',
      'action' => 'Text',
    );
  }
}
