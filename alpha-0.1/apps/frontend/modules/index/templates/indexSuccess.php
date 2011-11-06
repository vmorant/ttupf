<?php

	slot('opcions');
		echo "Opcions: ";
		foreach($opcions as $opcio):
			echo link_to($opcio->getOpcio()->getNom(), url_for(array('module' => $opcio->getOpcio()->getModule(), 'action' => $opcio->getOpcio()->getAction())))." ";
		endforeach;
	end_slot();
	
	echo "<h1>".$contingut_actual->getNom()."</h1><br>";
	echo $sf_data->getRaw('view_part');