Horari Index

<?php

	echo "<br><br>--- Horari configurat<br>";
	echo "---- Assignatures:<br>";

	foreach($utas as $uta):
		$sessions = $uta->getAssignatura()->getSessions()->getData();
		
		foreach($sessions as $sessio):
			$dia = date('d', strtotime($sessio->getDataHoraInici()));
			$mes = date('m', strtotime($sessio->getDataHoraInici()));
			$any = date('Y', strtotime($sessio->getDataHoraInici()));
			
			if(($dia == 1) && ($mes = 3) && ($any == 2011)) {
				echo "----- ".$sessio->getDataHoraInici()." -> ".$sessio->getAssignatura()->getNom()." -> ".$sessio->getTipus()."<br>";
			}
		endforeach;
	endforeach;	
