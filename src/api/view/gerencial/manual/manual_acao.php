<?php 
use Database\MySQL;
use Functions\Configuracoes;
$pdo = MySQL::acessabd();

	$acao = $_POST['acao'];
	if($acao == ""){
		$acao = $_GET['acao'];
	}
	
	if($acao == "alterarDestaque"){
		
		$id1 = $_POST['destaque1'];
		$id2 = $_POST['destaque2'];
		$id3 = $_POST['destaque3'];
		$id4 = $_POST['destaque4'];
		
		$sql="update salavirtual set destaque_sv = '0' where destaque_sv = '1' or destaque_sv = '2' or  destaque_sv = '3' or  destaque_sv = '4'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		
		$sql="update salavirtual set destaque_sv = '1' where id_sv = '$id1'";
		$stm = $pdo->prepare($sql);
		$stm->execute();

		$sql="update " . $_SESSION['BASE'] . ".salavirtual set destaque_sv = '2' where id_sv = '$id2'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		
		$sql="update " . $_SESSION['BASE'] . ".salavirtual set destaque_sv = '3' where id_sv = '$id3'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		
		$sql="update " . $_SESSION['BASE'] . ".salavirtual set destaque_sv = '4' where id_sv = '$id4'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
	}
	if($acao == "salvarCodigoPreview"){
		$codigo = $_POST['codigo'];
		$id = $_POST['id'];
		
		$titulo = $_POST['titulo'];
		$subtitulo = $_POST['subtitulo'];
		$cor = $_POST['cor'];
		$icon = $_POST['icon'];
		$tipo = $_POST['tipo'];
		
		
		$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where id_sv = '$id'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		if($stm->rowCount() > 0){
			$sql="update " . $_SESSION['BASE'] . ".salavirtual set textoTemp_sv = '$codigo', ativo_sv = '-1' where id_sv = '$id'";
			$stm = $pdo->prepare($sql);
			$stm->execute();
		}else{
			$sql="insert into " . $_SESSION['BASE'] . ".salavirtual (texto_sv,titulo_sv,subtitulo_sv,cor_sv,icon_sv,tipo_sv,ativo_sv) values('$codigo','$titulo','$subtitulo','$cor','$icon','$tipo','-1')";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
		}
	}

	if($acao == "salvarCodigo"){
		
		$codigo = $_POST['codigo'];
		$id = $_POST['id'];
		$titulo = $_POST['titulo'];
		$subtitulo = $_POST['subtitulo'];
		$cor = $_POST['cor'];
		$icon = $_POST['icon'];
		$tipo = $_POST['tipo'];
		
		
		$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where id_sv = '$id'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		if($stm->rowCount() > 0){
			$sql="update " . $_SESSION['BASE'] . ".salavirtual set 
			texto_sv = '$codigo',
			titulo_sv = '$titulo',
			subtitulo_sv = '$subtitulo',
			cor_sv = '$cor',
			icon_sv = '$icon',
			tipo_sv = '$tipo'
			where id_sv = '$id'";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
		}else{
			$sql="insert into " . $_SESSION['BASE'] . ".salavirtual (texto_sv,titulo_sv,subtitulo_sv,cor_sv,icon_sv,tipo_sv,ativo_sv) values('$codigo','$titulo','$subtitulo','$cor','$icon','$tipo','-1')";
			$stm = $pdo->prepare($sql);
			$stm->execute();
		}
	}
	if($acao == "deletar"){
		
		$id = $_POST['id'];
		
		$sql="delete from " . $_SESSION['BASE'] . ".salavirtual where id_sv  = '$id'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
	}
	if($acao == "upvideo"){
		
		$tmp_file = $_FILES['video']['tmp_name'];
		$filename = $_FILES['video']['name'];
		$mimeType = $_FILES['video']['type'];
		
		echo($filename);
		move_uploaded_file($tmp_file, 'documentos/videos/'.$filename);
		
		//header('location:painelSV.php');
		
	}
	echo('xxxx'.$acao);
	if($acao == "statusAtivo"){
		$id = $_POST['id'];
		$valor = $_POST['valor'];
		
		$sql="update " . $_SESSION['BASE'] . ".salavirtual set ativo_sv ='$valor' where id_sv = '$id'";
		$stm = $pdo->prepare($sql);
		//$stm->execute();
	}

	
	if($acao == "removeDestaque"){
		$id = $_POST['id'];

		$sql="update " . $_SESSION['BASE'] . ".salavirtual set destaque_sv ='0' where id_sv = '$id'";
		echo($sql);

		//$stm = $pdo->prepare($sql);
		//$stm->execute();

	}
?>