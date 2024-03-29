<?php

/**
 * BasesfOauthServerConsumer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $consumer_key
 * @property string $consumer_secret
 * @property string $name
 * @property text $description
 * @property integer $protocole
 * @property string $base_domain
 * @property string $callback
 * @property string $scope
 * @property integer $number_query
 * @property Doctrine_Collection $Developers
 * @property Doctrine_Collection $sfOauthServerRequestToken
 * @property Doctrine_Collection $sfOauthServerAccessToken
 * @property Doctrine_Collection $sfOauthServerUserScope
 * @property Doctrine_Collection $sfOauthServerDeveloper
 * 
 * @method string                getConsumerKey()               Returns the current record's "consumer_key" value
 * @method string                getConsumerSecret()            Returns the current record's "consumer_secret" value
 * @method string                getName()                      Returns the current record's "name" value
 * @method text                  getDescription()               Returns the current record's "description" value
 * @method integer               getProtocole()                 Returns the current record's "protocole" value
 * @method string                getBaseDomain()                Returns the current record's "base_domain" value
 * @method string                getCallback()                  Returns the current record's "callback" value
 * @method string                getScope()                     Returns the current record's "scope" value
 * @method integer               getNumberQuery()               Returns the current record's "number_query" value
 * @method Doctrine_Collection   getDevelopers()                Returns the current record's "Developers" collection
 * @method Doctrine_Collection   getSfOauthServerRequestToken() Returns the current record's "sfOauthServerRequestToken" collection
 * @method Doctrine_Collection   getSfOauthServerAccessToken()  Returns the current record's "sfOauthServerAccessToken" collection
 * @method Doctrine_Collection   getSfOauthServerUserScope()    Returns the current record's "sfOauthServerUserScope" collection
 * @method Doctrine_Collection   getSfOauthServerDeveloper()    Returns the current record's "sfOauthServerDeveloper" collection
 * @method sfOauthServerConsumer setConsumerKey()               Sets the current record's "consumer_key" value
 * @method sfOauthServerConsumer setConsumerSecret()            Sets the current record's "consumer_secret" value
 * @method sfOauthServerConsumer setName()                      Sets the current record's "name" value
 * @method sfOauthServerConsumer setDescription()               Sets the current record's "description" value
 * @method sfOauthServerConsumer setProtocole()                 Sets the current record's "protocole" value
 * @method sfOauthServerConsumer setBaseDomain()                Sets the current record's "base_domain" value
 * @method sfOauthServerConsumer setCallback()                  Sets the current record's "callback" value
 * @method sfOauthServerConsumer setScope()                     Sets the current record's "scope" value
 * @method sfOauthServerConsumer setNumberQuery()               Sets the current record's "number_query" value
 * @method sfOauthServerConsumer setDevelopers()                Sets the current record's "Developers" collection
 * @method sfOauthServerConsumer setSfOauthServerRequestToken() Sets the current record's "sfOauthServerRequestToken" collection
 * @method sfOauthServerConsumer setSfOauthServerAccessToken()  Sets the current record's "sfOauthServerAccessToken" collection
 * @method sfOauthServerConsumer setSfOauthServerUserScope()    Sets the current record's "sfOauthServerUserScope" collection
 * @method sfOauthServerConsumer setSfOauthServerDeveloper()    Sets the current record's "sfOauthServerDeveloper" collection
 * 
 * @package    ttupf
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasesfOauthServerConsumer extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sf_oauth_server_consumer');
        $this->hasColumn('consumer_key', 'string', 40, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 40,
             ));
        $this->hasColumn('consumer_secret', 'string', 40, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 40,
             ));
        $this->hasColumn('name', 'string', 256, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 256,
             ));
        $this->hasColumn('description', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('protocole', 'integer', null, array(
             'type' => 'integer',
             'default' => 1,
             ));
        $this->hasColumn('base_domain', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
             ));
        $this->hasColumn('callback', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
             ));
        $this->hasColumn('scope', 'string', 256, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 256,
             ));
        $this->hasColumn('number_query', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('sfGuardUser as Developers', array(
             'refClass' => 'sfOauthServerDeveloper',
             'local' => 'consumer_id',
             'foreign' => 'user_id'));

        $this->hasMany('sfOauthServerRequestToken', array(
             'local' => 'id',
             'foreign' => 'consumer_id'));

        $this->hasMany('sfOauthServerAccessToken', array(
             'local' => 'id',
             'foreign' => 'consumer_id'));

        $this->hasMany('sfOauthServerUserScope', array(
             'local' => 'id',
             'foreign' => 'consumer_id'));

        $this->hasMany('sfOauthServerDeveloper', array(
             'local' => 'id',
             'foreign' => 'consumer_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}