<?php

/**
 * Assignatura form base class.
 *
 * @method Assignatura getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAssignaturaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'nom'                => new sfWidgetFormInputText(),
      'carrera_curs_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CarreraCurs'), 'add_empty' => false)),
      'sf_guard_user_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'nom'                => new sfValidatorString(array('max_length' => 255)),
      'carrera_curs_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('CarreraCurs'))),
      'sf_guard_user_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('assignatura[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Assignatura';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['sf_guard_user_list']))
    {
      $this->setDefault('sf_guard_user_list', $this->object->sfGuardUser->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->savesfGuardUserList($con);

    parent::doSave($con);
  }

  public function savesfGuardUserList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sf_guard_user_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->sfGuardUser->getPrimaryKeys();
    $values = $this->getValue('sf_guard_user_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('sfGuardUser', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('sfGuardUser', array_values($link));
    }
  }

}
