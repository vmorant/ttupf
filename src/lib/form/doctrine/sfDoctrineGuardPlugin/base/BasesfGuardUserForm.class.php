<?php

/**
 * sfGuardUser form base class.
 *
 * @method sfGuardUser getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'first_name'           => new sfWidgetFormInputText(),
      'last_name'            => new sfWidgetFormInputText(),
      'email_address'        => new sfWidgetFormInputText(),
      'username'             => new sfWidgetFormInputText(),
      'algorithm'            => new sfWidgetFormInputText(),
      'salt'                 => new sfWidgetFormInputText(),
      'password'             => new sfWidgetFormInputText(),
      'is_active'            => new sfWidgetFormInputCheckbox(),
      'is_super_admin'       => new sfWidgetFormInputCheckbox(),
      'last_login'           => new sfWidgetFormDateTime(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'groups_list'          => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
      'assignatura_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Assignatura')),
      'usuaris_espiats_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'sf_guard_user_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'applications_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfOauthServerConsumer')),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'first_name'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'last_name'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email_address'        => new sfValidatorString(array('max_length' => 255)),
      'username'             => new sfValidatorString(array('max_length' => 128)),
      'algorithm'            => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'salt'                 => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'             => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'is_active'            => new sfValidatorBoolean(array('required' => false)),
      'is_super_admin'       => new sfValidatorBoolean(array('required' => false)),
      'last_login'           => new sfValidatorDateTime(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'groups_list'          => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
      'assignatura_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Assignatura', 'required' => false)),
      'usuaris_espiats_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'sf_guard_user_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'applications_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfOauthServerConsumer', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_guard_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['groups_list']))
    {
      $this->setDefault('groups_list', $this->object->Groups->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['permissions_list']))
    {
      $this->setDefault('permissions_list', $this->object->Permissions->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['assignatura_list']))
    {
      $this->setDefault('assignatura_list', $this->object->Assignatura->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['usuaris_espiats_list']))
    {
      $this->setDefault('usuaris_espiats_list', $this->object->UsuarisEspiats->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['sf_guard_user_list']))
    {
      $this->setDefault('sf_guard_user_list', $this->object->sfGuardUser->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['applications_list']))
    {
      $this->setDefault('applications_list', $this->object->Applications->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveGroupsList($con);
    $this->savePermissionsList($con);
    $this->saveAssignaturaList($con);
    $this->saveUsuarisEspiatsList($con);
    $this->savesfGuardUserList($con);
    $this->saveApplicationsList($con);

    parent::doSave($con);
  }

  public function saveGroupsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['groups_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Groups->getPrimaryKeys();
    $values = $this->getValue('groups_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Groups', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Groups', array_values($link));
    }
  }

  public function savePermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Permissions->getPrimaryKeys();
    $values = $this->getValue('permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Permissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Permissions', array_values($link));
    }
  }

  public function saveAssignaturaList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['assignatura_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Assignatura->getPrimaryKeys();
    $values = $this->getValue('assignatura_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Assignatura', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Assignatura', array_values($link));
    }
  }

  public function saveUsuarisEspiatsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['usuaris_espiats_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->UsuarisEspiats->getPrimaryKeys();
    $values = $this->getValue('usuaris_espiats_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UsuarisEspiats', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UsuarisEspiats', array_values($link));
    }
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

  public function saveApplicationsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['applications_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Applications->getPrimaryKeys();
    $values = $this->getValue('applications_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Applications', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Applications', array_values($link));
    }
  }

}
