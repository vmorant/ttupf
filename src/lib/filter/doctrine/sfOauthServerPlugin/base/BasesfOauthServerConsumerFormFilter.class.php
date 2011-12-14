<?php

/**
 * sfOauthServerConsumer filter form base class.
 *
 * @package    ttupf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesfOauthServerConsumerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'consumer_key'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'consumer_secret' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'protocole'       => new sfWidgetFormFilterInput(),
      'base_domain'     => new sfWidgetFormFilterInput(),
      'callback'        => new sfWidgetFormFilterInput(),
      'scope'           => new sfWidgetFormFilterInput(),
      'number_query'    => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'developers_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'consumer_key'    => new sfValidatorPass(array('required' => false)),
      'consumer_secret' => new sfValidatorPass(array('required' => false)),
      'name'            => new sfValidatorPass(array('required' => false)),
      'description'     => new sfValidatorPass(array('required' => false)),
      'protocole'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'base_domain'     => new sfValidatorPass(array('required' => false)),
      'callback'        => new sfValidatorPass(array('required' => false)),
      'scope'           => new sfValidatorPass(array('required' => false)),
      'number_query'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'developers_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_oauth_server_consumer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addDevelopersListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.sfOauthServerDeveloper sfOauthServerDeveloper')
      ->andWhereIn('sfOauthServerDeveloper.user_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'sfOauthServerConsumer';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'consumer_key'    => 'Text',
      'consumer_secret' => 'Text',
      'name'            => 'Text',
      'description'     => 'Text',
      'protocole'       => 'Number',
      'base_domain'     => 'Text',
      'callback'        => 'Text',
      'scope'           => 'Text',
      'number_query'    => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'developers_list' => 'ManyKey',
    );
  }
}
