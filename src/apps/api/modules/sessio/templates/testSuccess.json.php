<?php
	$count = 0;
	foreach($sessionsArray as $session):
			$dataHoraInici = $session->getDataHoraInici();
			
			$session_date = date('d/m/Y', strtotime($dataHoraInici));
			
			if($session_date == date('d/m/Y', mktime(0, 0, 0, date('m')  , date('d'), date('Y')))) {
				$hora = explode(" ", $dataHoraInici);
				$data["sessio".$count] = array("hora" => $hora[1], "assignatura" => $session->getAssignatura()->getNom(), "tipus" => $session->getTipus(), "aula" => $session->getAula());
				$count++;
			}
	endforeach;
	echo json_encode($data);