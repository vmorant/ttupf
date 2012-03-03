<?php
	$count = 0;
	
	$json_data["code"] = 1;
	$json_data["sessions"] = 0;
	
	foreach($sessionsArray as $session):
			$dataHoraInici = $session->getDataHoraInici();
			
			$session_date = date('d/m/Y', strtotime($dataHoraInici));

			if($session_date == $data) {
				$hora = explode(" ", $dataHoraInici);
				$json_data["sessio".$count] = array("hora" => $hora[1], "assignatura" => $session->getAssignatura()->getNom(), "tipus" => $session->getTipus(), "aula" => $session->getAula());
				$count++;
			}
	endforeach;
	
	if($count != 0) {
		$json_data["code"] = 0;
		$json_data["sessions"] = $count;
	}
	
	echo json_encode($json_data);