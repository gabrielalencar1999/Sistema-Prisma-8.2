<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
use Functions\Acesso;
$pdo = MySQL::acessabd();

$liberado = array();

$id = $_parametros['id'];
unset($_parametros['id']);
//print_r($_parametros);

foreach ($_parametros as $permissoes){
	if($permissoes != ""){
		$liberado[] = $permissoes;
	}
}

try {
	$empresa_id = $_SESSION['BASE_ID'];
		
	//deleta todas permissoes do usuario
	$sql= $pdo->prepare("delete from " . $_SESSION['BASE'] . ".telas_acesso where tela_user = '$id' ");		
	$sql->execute();	
	
	foreach($liberado as $perm){
		$sql= $pdo->prepare("insert into " . $_SESSION['BASE'] . ".telas_acesso  (tela_descricao,tela_user,tela_idEmpresa) values ('$perm','$id', '$empresa_id') ");		
		$sql->execute();
	}


	//verifica se usuario atualizado é o proprio usuario logado
	
	if($_SESSION['IDUSER'] == $id){
		//permissoes
		$permissoes = Acesso::permissao($_SESSION['IDUSER']);
		//print_r($permissoes);
		//echo'<br>XXXXX<br>';
	}
	
	?>
	<div class="modal-dialog text-center">
		<div class="modal-content">
			<div class="modal-body">
				<div class="bg-icon pull-request">
					<img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
					<h2>Permissões alterada com sucesso!</h2>
					<button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	<?php
} catch (PDOException $e) {
	?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h2><?="Erro: " . $e->getMessage()?></h2>
			</div>
		</div>
	</div>
	<?php
}

?>