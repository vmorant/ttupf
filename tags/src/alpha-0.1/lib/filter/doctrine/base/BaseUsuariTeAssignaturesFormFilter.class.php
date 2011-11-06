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
      'grup_practiques' => new sfWidgetFormFilterInput(),
      'grup_seminari'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'grup_practiques' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'grup_seminari'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
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
      'usuari_id'       => 'Number',
      'assignatura_id'  => 'Number',
      'grup_practiques' => 'Number',
      'grup_seminari'   => 'Number',
    );
  }
}
