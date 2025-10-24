<?php
	session_start();
	
	use Database\MySQL;

	$pdo = MySQL::acessabd();

	require_once('../../api/config/config.inc.php');
	require FILE_BASE_API . '/vendor/autoload.php';
	
	// $codLoja = "1020";
	// $user = "supervisor";
	// $senha = md5("ttitts01!@");
	
	if($codLoja != ""){
		$sql = "Select consumidor_base,Nome_Fantasia from consumidor where CODIGO_CONSUMIDOR = ? ";
		$stm = $pdo->prepare($sql);	
		$stm->bindParam(1,$codLoja, \PDO::PARAM_INT,6);	
		$stm->execute();
		if ($stm->rowCount() > 0 ){
			
			$loja = $codLoja;
			
			while ($value = $stm->fetch(PDO::FETCH_OBJ)){
				$_SESSION['BASE'] = $value->consumidor_base;
				$_SESSION['fantasia'] = $value->Nome_Fantasia;
			}
		}else{
			$erroLogin = 1;
		}
		
		$sql = "select * from " . $_SESSION['BASE'] . ".usuario WHERE usuario_LOGIN = ? and usuario_SENHA = ?";
		$stm = MySQL::acessabd()->prepare($sql);	
		$stm->bindParam(1,$user);	
		$stm->bindParam(2,$senha);	
		$stm->execute();
		if ($stm->rowCount() > 0 ){
			while ($value = $stm->fetch(PDO::FETCH_OBJ)){
				unset($_SESSION["numberPedido"]);
				$_SESSION["tecnico"]  = $value->usuario_CODIGOUSUARIO;
				$_SESSION["login"]  = $value->usuario_LOGIN;
				$_SESSION["nomeUser"]  = $value->usuario_NOME;
				$_SESSION["nivel"] = 'CAIXA';
				$_SESSION["perfil"] = $value->usuario_PERFIL;	
				$_SESSION["log"] ='S';
				$_SESSION["empresa"] = $value->usuario_empresa;		
				$_SESSION["chave_loja"] = $codLoja;		
				$_SESSION['company'] = "GESTORPET";		

				?>
				<script>location.href = "caixa.php";</script>
				<?php
			}
		}
	}
?>