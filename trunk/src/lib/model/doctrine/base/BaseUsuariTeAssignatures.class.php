<?php

/**
 * BaseUsuariTeAssignatures
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $usuari_id
 * @property integer $assignatura_id
 * @property integer $grup_teoria
 * @property integer $grup_practiques
 * @property integer $grup_seminari
 * @property sfGuardUser $sfGuardUser
 * @property Assignatura $Assignatura
 * 
 * @method integer              getUsuariId()        Returns the current record's "usuari_id" value
 * @method integer              getAssignaturaId()   Returns the current record's "assignatura_id" value
 * @method integer              getGrupTeoria()      Returns the current record's "grup_teoria" value
 * @method integer              getGrupPractiques()  Returns the current record's "grup_practiques" value
 * @method integer              getGrupSeminari()    Returns the current record's "grup_seminari" value
 * @method sfGuardUser          getSfGuardUser()     Returns the current record's "sfGuardUser" value
 * @method Assignatura          getAssignatura()     Returns the current record's "Assignatura" value
 * @method UsuariTeAssignatures setUsuariId()        Sets the current record's "usuari_id" value
 * @method UsuariTeAssignatures setAssignaturaId()   Sets the current record's "assignatura_id" value
 * @method UsuariTeAssignatures setGrupTeoria()      Sets the current record's "grup_teoria" value
 * @method UsuariTeAssignatures setGrupPractiques()  Sets the current record's "grup_practiques" value
 * @method UsuariTeAssignatures setGrupSeminari()    Sets the current record's "grup_seminari" value
 * @method UsuariTeAssignatures setSfGuardUser()     Sets the current record's "sfGuardUser" value
 * @method UsuariTeAssignatures setAssignatura()     Sets the current record's "Assignatura" value
 * 
 * @package    ttupf
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUsuariTeAssignatures extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('usuari_te_assignatures');
        $this->hasColumn('usuari_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('assignatura_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('grup_teoria', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('grup_practiques', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('grup_seminari', 'integer', null, array(
             'type' => 'integer',
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