<?php

/**
 * sfOauthServerConsumer form base class.
 *
 * @method sfOauthServerConsumer getObject() Returns the current form's model object
 *
 * @package    ttupf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfOauthServerConsumerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'consumer_key'    => new sfWidgetFormInputText(),
      'consumer_secret' => new sfWidgetFormInputText(),
      'name'            => new sfWidgetFormTextarea(),
      'description'     => new sfWidgetFormInputText(),
      'protocole'       => new sfWidgetFormInputText(),
      'base_domain'     => new sfWidgetFormTextarea(),
      'callback'        => new sfWidgetFormTextarea(),
      'scope'           => new sfWidgetFormTextarea(),
      'number_query'    => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'developers_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'consumer_key'    => new sfValidatorString(array('max_length' => 40)),
      'consumer_secret' => new sfValidatorString(array('max_length' => 40)),
      'name'            => new sfValidatorString(array('max_length' => 256)),
      'description'     => new sfValidatorPass(),
      'protocole'       => new sfValidatorInteger(array('required' => false)),
      'base_domain'     => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'callback'        => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'scope'           => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'number_query'    => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'developers_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfOauthServerConsumer', 'column' => array('consumer_key'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfOauthServerConsumer', 'column' => array('consumer_secret'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_oauth_server_consumer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfOauthServerConsumer';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['developers_list']))
    {
      $this->setDefault('developers_list', $this->object->Developers->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveDevelopersList($con);

    parent::doSave($con);
  }

  public function saveDevelopersList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['developers_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Developers->getPrimaryKeys();
    $values = $this->getValue('developers_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Developers', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Developers', array_values($link));
    }
  }

}
