<?php

/**
 * Assignatura filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAssignaturaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nom'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'carrera_curs_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CarreraCurs'), 'add_empty' => true)),
      'sf_guard_user_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'nom'                => new sfValidatorPass(array('required' => false)),
      'carrera_curs_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('CarreraCurs'), 'column' => 'id')),
      'sf_guard_user_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('assignatura_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addSfGuardUserListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.UsuariTeAssignatura UsuariTeAssignatura')
      ->andWhereIn('UsuariTeAssignatura.usuari_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Assignatura';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'nom'                => 'Text',
      'carrera_curs_id'    => 'ForeignKey',
      'sf_guard_user_list' => 'ManyKey',
    );
  }
}
