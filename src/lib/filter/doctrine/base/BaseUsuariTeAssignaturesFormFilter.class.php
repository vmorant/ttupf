<?php

/**
 * UsuariTeAssignatures filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUsuariTeAssignaturesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'usuari_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'assignatura_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Assignatura'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'usuari_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'assignatura_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Assignatura'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('usuari_te_assignatures_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsuariTeAssignatures';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'usuari_id'      => 'Number',
      'assignatura_id' => 'ForeignKey',
    );
  }
}
