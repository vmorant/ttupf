<h1>Horari</h1>

<?php
	use_helper('Debug');
 
	// Generem la taula d'horari a partir de les sessions trobades.
	$sessionsTable = new sessionsTable($sessionsArray);

	// S'ha demanat l'horari d'una data en concret
	if(isset($data)){
		$sessionsTable->setDate($data);
	}
	$sessionsTable->generaHtml();
	echo $sessionsTable->toString();

	// Generem enllaços dia anterior/dia seguent.

	// S'ha de fer un createFromFormat perquè algú va decidir fer que $data
	// fos d/m/Y en lloc de Y/m/d, i aquest primer format confon al constructor
	// normal.
	$diaAnterior = DateTime::createFromFormat('d/m/Y', $sessionsTable->getDate());
	$diaAnterior->sub(new DateInterval('P1D'));
	log_message("Dia anterior: " . $diaAnterior->format('d/m/Y'), 'Debug');
	$parametres_anterior = 'dia=' . $diaAnterior->format('d') . '&mes=' . $diaAnterior->format('m') . '&any=' . $diaAnterior->format('Y');

	// Afegint dos dies al dia anterior tenim el dia seguent
	$diaSeguent = $diaAnterior->add(new DateInterval('P2D'));
	log_message("Dia seguent: " . $diaSeguent->format('d/m/Y'), 'Debug');
	$parametres_seguent = 'dia=' . $diaSeguent->format('d') . '&mes=' . $diaSeguent->format('m') . '&any=' . $diaSeguent->format('Y');

	echo '<ul id="canvi_dia"><li>'.link_to('« Dia anterior', 'horari/index', array('query_string' => $parametres_anterior)).'</li><li>'.link_to('Dia seguent »', 'horari/index', array('query_string' => $parametres_seguent)).'</li></ul>';
