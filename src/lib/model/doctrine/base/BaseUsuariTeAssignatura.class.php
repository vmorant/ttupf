<?php

/**
 * BaseUsuariTeAssignatura
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $usuari_id
 * @property integer $assignatura_id
 * @property string $grup_practiques
 * @property string $grup_seminari
 * @property sfGuardUser $sfGuardUser
 * @property Assignatura $Assignatura
 * 
 * @method integer             getUsuariId()        Returns the current record's "usuari_id" value
 * @method integer             getAssignaturaId()   Returns the current record's "assignatura_id" value
 * @method string              getGrupPractiques()  Returns the current record's "grup_practiques" value
 * @method string              getGrupSeminari()    Returns the current record's "grup_seminari" value
 * @method sfGuardUser         getSfGuardUser()     Returns the current record's "sfGuardUser" value
 * @method Assignatura         getAssignatura()     Returns the current record's "Assignatura" value
 * @method UsuariTeAssignatura setUsuariId()        Sets the current record's "usuari_id" value
 * @method UsuariTeAssignatura setAssignaturaId()   Sets the current record's "assignatura_id" value
 * @method UsuariTeAssignatura setGrupPractiques()  Sets the current record's "grup_practiques" value
 * @method UsuariTeAssignatura setGrupSeminari()    Sets the current record's "grup_seminari" value
 * @method UsuariTeAssignatura setSfGuardUser()     Sets the current record's "sfGuardUser" value
 * @method UsuariTeAssignatura setAssignatura()     Sets the current record's "Assignatura" value
 * 
 * @package    ttupf
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUsuariTeAssignatura extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('usuari_te_assignatura');
        $this->hasColumn('usuari_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('assignatura_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('grup_practiques', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('grup_seminari', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser', array(
             'local' => 'usuari_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Assignatura', array(
             'local' => 'assignatura_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}