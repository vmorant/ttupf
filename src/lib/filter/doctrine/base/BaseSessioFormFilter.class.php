<?php

/**
 * Sessio filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSessioFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'data_hora_inici' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'data_hora_fi'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'assignatura_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Assignatura'), 'add_empty' => true)),
      'aula'            => new sfWidgetFormFilterInput(),
      'tipus'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'grup_seminari'   => new sfWidgetFormFilterInput(),
      'grup_practiques' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'data_hora_inici' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'data_hora_fi'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'assignatura_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Assignatura'), 'column' => 'id')),
      'aula'            => new sfValidatorPass(array('required' => false)),
      'tipus'           => new sfValidatorPass(array('required' => false)),
      'grup_seminari'   => new sfValidatorPass(array('required' => false)),
      'grup_practiques' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sessio_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Sessio';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'data_hora_inici' => 'Date',
      'data_hora_fi'    => 'Date',
      'assignatura_id'  => 'ForeignKey',
      'aula'            => 'Text',
      'tipus'           => 'Text',
      'grup_seminari'   => 'Text',
      'grup_practiques' => 'Text',
    );
  }
}
