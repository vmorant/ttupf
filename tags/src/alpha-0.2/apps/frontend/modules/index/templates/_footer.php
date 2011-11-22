<?php

	echo "Footer: ";
	foreach($continguts as $contingut):
		echo link_to($contingut->getNom(), 'index_contingut', $contingut)." ";
	endforeach;