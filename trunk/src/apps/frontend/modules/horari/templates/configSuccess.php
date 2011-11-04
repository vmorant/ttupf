Horari Configuració<br />

<?php 
	foreach($carreresCursos as $carreraCurs):
		echo $carreraCurs->getNom()."<br />";
	endforeach;