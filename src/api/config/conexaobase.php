<?php

	$servidor = MAIN_DB_HOST;
	$user_conect = MAIN_DB_USER;
	$senha = MAIN_DB_PASS;
	$banco_conect = MAIN_DB_SCHEMA;
	$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);
	

?>
