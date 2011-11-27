<?php

/**
 * Second step in the configuration of a subject. Allows the user to select
 * a subject from a degree (chosen in the previous step). Only subjects that
 * the user isn't subscribed to are displayed.
 */

class ConfigureSelectSubjectForm extends CarreraCursForm
{
	public function configure()
	{
		$this->setWidgets(array(
					'assignatures' => new sfWidgetFormDoctrineChoice(array(
						'multiple' => false,
						'expanded' => false,
						'model' => 'Assignatura',
						'query' => Doctrine_Query::create()
						->select('a.id')
						->from('Assignatura a')
						->where('a.carrera_curs_id = ?', $this->getObject()->getId())
						->andWhere('a.id NOT IN (SELECT uta.assignatura_id from UsuariTeAssignatura uta where uta.usuari_id = ?)', sfContext::getInstance()->getUser()->getGuardUser()->getId())
					))));
	}
}
