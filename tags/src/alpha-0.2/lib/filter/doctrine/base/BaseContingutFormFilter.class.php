<?php

/**
 * Contingut filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseContingutFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nom'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'action_part'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'view_part'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'es_contingut' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'nom'          => new sfValidatorPass(array('required' => false)),
      'action_part'  => new sfValidatorPass(array('required' => false)),
      'view_part'    => new sfValidatorPass(array('required' => false)),
      'es_contingut' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('contingut_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Contingut';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'nom'          => 'Text',
      'action_part'  => 'Text',
      'view_part'    => 'Text',
      'es_contingut' => 'Boolean',
    );
  }
}
