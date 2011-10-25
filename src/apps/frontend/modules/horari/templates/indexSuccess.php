Horari Index<br />

<?php

	// Creem vector de sessions a partir de la qual es generara una taula d'horari.
	$sessionsArray = array();
	foreach($utas as $uta):
		$sessions = $uta->getAssignatura()->getSessions()->getData();
		foreach($sessions as $session):
			$sessionsArray[] = $session;
		endforeach;
	endforeach;
 
	// Generem la taula d'horari a partir de les sessions trobades.
	$sessionsTable = new sessionsTable($sessionsArray);

	echo $sessionsTable->toString();

