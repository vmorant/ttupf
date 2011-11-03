Horari Index<br />

<?php

	// Creem vector de sessions a partir de la qual es generara una taula d'horari.
	$sessionsArray = array();
	foreach($utas as $uta):
		$sessions = $uta->getAssignatura()->getSessions()->getData();
		foreach($sessions as $session):
			$tipus = $session->getTipus();
			switch($tipus[0]) {
				case 'P':
					if($session->getGrupPractiques() == $uta->getGrupPractiques()) {
						$sessionsArray[] = $session;
					}
					break;
				case 'T':
					if($session->getGrupTeoria() == $uta->getGrupTeoria()) {
						$sessionsArray[] = $session;
					}
					break;
				case 'S':
					if($session->getGrupSeminari() == $uta->getGrupSeminari()) {
						$sessionsArray[] = $session;
					}
					break;
				default:
					break;
			}
		endforeach;
	endforeach;
 
	// Generem la taula d'horari a partir de les sessions trobades.
	$sessionsTable = new sessionsTable($sessionsArray);

	echo $sessionsTable->toString();

