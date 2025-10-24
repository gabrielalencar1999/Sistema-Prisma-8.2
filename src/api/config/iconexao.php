<?php



if($_SESSION['BASE'] == "") { 
	?>
	<script>
			alert("Seu login expirou. Efetue o login novamente !!!");
			//location.href="http://gestorpet.com.br/app/";
	</script>
	<?php
} else { 

	$servidor = MAIN_DB_HOST;
	$user_conect = MAIN_DB_USER;
	$senha = MAIN_DB_PASS;
	$banco_conect = $_SESSION['BASE'];
	$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);
	/*

	$servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
	$user_conect = 'admin';
	$senha = '';
	$banco_conect = 'bd_tecfast';
	$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
	$_SESSION['BASE'] = 'bd_tecfast';
	*/

}
?>
