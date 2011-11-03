<?php

/**
 * UsuariTeAssignatura filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUsuariTeAssignaturaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'grup_practiques' => new sfWidgetFormFilterInput(),
      'grup_seminari'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'grup_practiques' => new sfValidatorPass(array('required' => false)),
      'grup_seminari'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('usuari_te_assignatura_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsuariTeAssignatura';
  }

  public function getFields()
  {
    return array(
      'usuari_id'       => 'Number',
      'assignatura_id'  => 'Number',
      'grup_practiques' => 'Text',
      'grup_seminari'   => 'Text',
    );
  }
}
