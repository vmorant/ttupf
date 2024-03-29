<?php

/**
 * BaseAssignatura
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $nom
 * @property integer $carrera_curs_id
 * @property CarreraCurs $CarreraCurs
 * @property Doctrine_Collection $sfGuardUser
 * @property Doctrine_Collection $Sessions
 * @property Doctrine_Collection $UsuariTeAssignatures
 * 
 * @method string              getNom()                  Returns the current record's "nom" value
 * @method integer             getCarreraCursId()        Returns the current record's "carrera_curs_id" value
 * @method CarreraCurs         getCarreraCurs()          Returns the current record's "CarreraCurs" value
 * @method Doctrine_Collection getSfGuardUser()          Returns the current record's "sfGuardUser" collection
 * @method Doctrine_Collection getSessions()             Returns the current record's "Sessions" collection
 * @method Doctrine_Collection getUsuariTeAssignatures() Returns the current record's "UsuariTeAssignatures" collection
 * @method Assignatura         setNom()                  Sets the current record's "nom" value
 * @method Assignatura         setCarreraCursId()        Sets the current record's "carrera_curs_id" value
 * @method Assignatura         setCarreraCurs()          Sets the current record's "CarreraCurs" value
 * @method Assignatura         setSfGuardUser()          Sets the current record's "sfGuardUser" collection
 * @method Assignatura         setSessions()             Sets the current record's "Sessions" collection
 * @method Assignatura         setUsuariTeAssignatures() Sets the current record's "UsuariTeAssignatures" collection
 * 
 * @package    ttupf
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAssignatura extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('assignatura');
        $this->hasColumn('nom', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('carrera_curs_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('CarreraCurs', array(
             'local' => 'carrera_curs_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('sfGuardUser', array(
             'refClass' => 'UsuariTeAssignatura',
             'local' => 'assignatura_id',
             'foreign' => 'usuari_id'));

        $this->hasMany('Sessio as Sessions', array(
             'local' => 'id',
             'foreign' => 'assignatura_id'));

        $this->hasMany('UsuariTeAssignatura as UsuariTeAssignatures', array(
             'local' => 'id',
             'foreign' => 'assignatura_id'));
    }
}