<?php

	echo "Opcions: ";
	echo link_to("Inicia Sessió", array("module" => "index", "action" => "login"))." ";
	echo link_to("Registrar-se", array("module" => "user", "action" => "new"));