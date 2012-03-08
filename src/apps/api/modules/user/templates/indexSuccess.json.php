<?php
  	$json_data = array();
  	$json_data["code"] = 1;

	if($tokens)
	{
		$json_data["code"] = 0;
		$json_data["key"] = $tokens[sizeof($tokens) - 1]["token"];
		$json_data["secret"] = $tokens[sizeof($tokens) - 1]["secret"];
	}

	echo json_encode($json_data);