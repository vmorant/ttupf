<?php

/**
 * First step in the configuration of a subject. Allows a user to select a degree for which to display subjects.
 */

class ConfigureSelectDegreeForm extends sfForm
{
	public function configure()
	{
		$this->setWidgets(array(
					'carreres' => new sfWidgetFormDoctrineChoice(array(
						'multiple' => false,
						'expanded' => true,
						'model' => 'CarreraCurs',
					))));
	}
}
