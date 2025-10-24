<?php 
	session_start();
	
	require_once('../../../api/config/config.inc.php');
	require FILE_BASE_API . '/vendor/autoload.php';
	
	$codLoja = $_POST['login'];
	$user = $_POST['user'];
	$senha = md5($_POST['senha']);
	
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
		$stm = $pdo->prepare($sql);	
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
				$_SESSION["CODIGOCLI"] = $codLoja;		
				$_SESSION['company'] = "PRISMA";		

				?>
				<script>location.href = "caixa.php";</script>
				<?php
			}
		}else{
			$erroLogin = 2;
		}
		

		
		if($erroLogin == 1){
			$css1 = "border-color:red";
			$mensagemErro1 = "C�digo de acesso incorreto!";
		}
		if($erroLogin == 2){
			$css2 = "border-color:red";
			$mensagemErro2 = "Usu�rio PDV incorreto E/ou Senha do usu�rio incorreto";
		
			$css3 = "border-color:red";
			$mensagemErro3 = "Usu�rio PDV incorreto E/ou Senha do usu�rio incorreto";
		}
	}
?>
<html>
	<head>
		<title>PRISMA | Caixa Online</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="icon" href="img/caixaIcon.png" type="image/png">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	</head>
	<body style="background-image:url('img/background.png'); background-size:cover;">
		<div class="container">
			<div class="row">
				<div class="col-lg-3"></div>
				<form action="index.php" method="post">
				<div class="col-lg-6">
					<div style="width:100%; background-color:#FFF; border-radius:4px; margin-top:10%; padding:15px;">
						
						<div class="row">
							<div class="col-xs-12" style="text-align:center; padding-top:3%;">
								<img src="img/prisma.png" width="40%">
							</div>
						</div>
						<form action="index.php" method="post">
						<div class="row">
							<div class="col-xs-12" style="padding:15%; padding-bottom:0px; padding-top:10%;">
								<label>C�digo de acesso:</label>
								<input id="login" name="login" type="password" class="form-control" placeholder="****" value="<?=$loja;?>" required style="<?=$css1;?>">
								<span style="font-size:10px; color:red;">&nbsp;<?=$mensagemErro1;?></span>
							</div>
							
						</div>
						<div class="row">
							<div class="col-xs-12" style="padding:15%; padding-bottom:0px; padding-top:5%;">
								<label>Usu�rio PDV:</label>
								<input id="user" name="user" type="text" class="form-control" value="<?=$user;?>" required style="<?=$css2;?>">
								<span style="font-size:10px; color:red;">&nbsp;<?=$mensagemErro2;?></span>
							</div>							
						</div>
						<div class="row">
							<div class="col-xs-12" style="padding:15%; padding-bottom:0px; padding-top:5%;">
								<label>Senha do usu�rio:</label>
								<input id="senha" name="senha" type="password" class="form-control" placeholder="****" value="" required style="<?=$css3;?>">
								<span style="font-size:10px; color:red;">&nbsp;<?=$mensagemErro3;?></span>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12" style="padding:15%; padding-bottom:0px; padding-top:5%;">
								<button type="submit" class="btn btn-primary btn-block">ACESSAR</button>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12" style="padding:15%; padding-bottom:5%; padding-top:5%;">
								<div id="resp">&nbsp;</div>
							</div>
						</div>
						</form>
					</div>
				</div>
				</form>
				<div class="col-lg-3"></div>
		</div>
		</div>
	</body>
</html>