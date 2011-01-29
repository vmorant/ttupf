<?php

	foreach($continguts as $contingut):
		echo link_to($contingut->getNom(), url_for('index_contingut', $contingut))."<br>";
	endforeach;