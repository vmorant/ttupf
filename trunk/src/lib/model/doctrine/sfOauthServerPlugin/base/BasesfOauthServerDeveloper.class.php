<?php

/**
 * BasesfOauthServerDeveloper
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $consumer_id
 * @property integer $user_id
 * @property boolean $admin
 * @property sfOauthServerConsumer $Consumer
 * @property sfGuardUser $User
 * 
 * @method integer                getConsumerId()  Returns the current record's "consumer_id" value
 * @method integer                getUserId()      Returns the current record's "user_id" value
 * @method boolean                getAdmin()       Returns the current record's "admin" value
 * @method sfOauthServerConsumer  getConsumer()    Returns the current record's "Consumer" value
 * @method sfGuardUser            getUser()        Returns the current record's "User" value
 * @method sfOauthServerDeveloper setConsumerId()  Sets the current record's "consumer_id" value
 * @method sfOauthServerDeveloper setUserId()      Sets the current record's "user_id" value
 * @method sfOauthServerDeveloper setAdmin()       Sets the current record's "admin" value
 * @method sfOauthServerDeveloper setConsumer()    Sets the current record's "Consumer" value
 * @method sfOauthServerDeveloper setUser()        Sets the current record's "User" value
 * 
 * @package    ttupf
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasesfOauthServerDeveloper extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sf_oauth_server_developer');
        $this->hasColumn('consumer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('admin', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfOauthServerConsumer as Consumer', array(
             'local' => 'consumer_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('sfGuardUser as User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));
    }
}