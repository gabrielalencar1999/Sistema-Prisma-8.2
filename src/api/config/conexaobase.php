<?php

	$servidor = 'prisma-legacy-mysql';
	$user_conect = MYSQL_USER;
	$senha = MYSQL_PASSWORD;
	$banco_conect = MYSQL_DATABASE;
	$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);
	

?>
