<?php

/**
 * Entrada filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEntradaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'contingut_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contingut'), 'add_empty' => true)),
      'nom'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'longitud'     => new sfWidgetFormFilterInput(),
      'amplada'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'contingut_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Contingut'), 'column' => 'id')),
      'nom'          => new sfValidatorPass(array('required' => false)),
      'longitud'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amplada'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('entrada_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Entrada';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'contingut_id' => 'ForeignKey',
      'nom'          => 'Text',
      'longitud'     => 'Number',
      'amplada'      => 'Number',
    );
  }
}
