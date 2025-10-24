<?php

use Database\MySQL;
use Functions\Configuracoes;
$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


if($acao == 1){
	//INCLUIR NOVA ESPÉCIE
	?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Nova Avaliação</h4>
	</div>
	
	<div class="modal-body">
		<form id="form2" name="form2">
			<div class="row" style="padding:15px;">  
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-12">
							<label class="control-label">Nova Avaliação</label>    
							<input class="form-control" name="newAvaliacao" id="newAvaliacao" placeholder="Descrição">
						</div>                                           
					</div> 
					<div class="row">
						<div class="col-sm-12"><br>
							<button type="button" id="_00005" class="btn btn-success">Criar</button>
						</div>   
					</div>
				</div>
			</div>			
		</form>	
	</div>	
	<script>
	
		var search2 = $(_00005).click(function(){       
			var $_keyid =   "avaliacao_00002";    			
			var dados = $("#form2 :input").serializeArray();
			dados = JSON.stringify(dados);			

			$.post("page_return.php", {_keyform:$_keyid,dados:dados , acao:"insertAvaliacao"}, function(result){		
				if(result == 1){ 
				
				}else{
					_lista();
					$("#divAlt").html(
					'	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p> Avaliação incluída com <strong>Sucesso !!!</strong></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/></div></div></div>'
					);	
				}
			});					
			});
		</script>			
	<?php
	
}
if ($acao["acao"] == 2) {
 	//LISTA CATEGORIAS
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tipo_avaliacao ORDER BY tipaval_id ASC");
    $retorno = $statement->fetchAll();
	$tamanhoDiv = 25*$statement->rowCount();
	//$tamanhoDiv = 50+$tamanhoDiv;
        foreach ($retorno as $rst) { ?>
            
            <div class="col-sm-2">
				<div class="box-categoria">
					<div class="box" style="padding-bottom:75px; height:80px; ">  <!---margin-top:-33px;--->   
						<div class="col-xs-4">
								<div class="bar-widget">
									<div class="iconbox" style="margin-right:0px; background-color:#00a8e6"  data-toggle="modal" data-target="#custom-modal-alterar" onclick="alterarCategoria('<?=$rst['tipaval_id'];?>')">
										<i id="alte<?=$rst['tipaval_id'];?>" class="fa fa-pencil"></i>
									</div>
								</div>
						</div>
						<div class="col-xs-8">
							<p class="categoria_title" style=" margin-top:9px;"><b style="font-size:14px;"><?=$rst['tipaval_descricao'];?></b></p>
						</div>
					</div>
					<!----
					<hr>
					<h5 data-toggle="modal" data-target="#custom-modal-alterar" onclick="_addSub('<?=$rst['tipaval_id'];?>')"><b class="addSub">Adicionar Sintomas</b></h5>
					<?php
						$sql = "SELECT * FROM ". $_SESSION['BASE'] .".avaliacao where avl_tipo = '".$rst['tipaval_id']."' ORDER BY avl_sintomas ASC";
						$stm = $pdo->prepare($sql);	
						$stm->execute();
						 // retornar os dados em formato de objeto
								
					?>
					<div style="overflow-y:auto; max-height:204px;">
						<table class="table table-bordered">
							<?php while ($value = $stm->fetch(PDO::FETCH_OBJ)) { ?>
								<tr>
									<td><a style="cursor:pointer" data-toggle="modal" data-target="#custom-modal-alterar" onclick="alterarSubCategoria('<?=$value->avl_id;?>')"><i class="fa fa-pencil"></i></a></td>
									<td><?=$value->avl_sintomas;?></td>
									<td><a style="cursor:pointer" data-toggle="modal" data-target="#custom-modal-alterar" onclick="excluirSubCategoria('<?=$value->avl_id;?>')"><i class="fa fa-times"></i></a></td>
								</tr>
							<?php } ?>
						</table>
					</div>
					--->
				</div>
			</div>
            <?php
        }
}
//================================================================================================================================================================
//================================================================================================================================================================
//================================================================================================================================================================

if($acao == 3){
	
	$sql="select * from ". $_SESSION['BASE'] .".tipo_avaliacao where tipaval_id = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$idAvaliacao = $value->tipaval_id;
			$descricaoAvaliacao = $value->tipaval_descricao;		
		}
	}else{
		exit();
	}
	
	?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Alterar Avaliação</h4>
	</div>
	
	<div class="modal-body">
	<form id="form2" name="form2">
		<div class="row">
			<div class="col-sm-4 text-center" >
				<div class="box">
					<a onclick="selCategoria('<?=$idAvaliacao;?>')"><div class="bar-widget">
						<div class="iconbox" id="view" style="margin-right:0px; background-color:#00a8e6">
							<i class="fa fa-plus"></i>
						</div>
					</div>
					<p class="categoria_title"><b style="font-size:14px;" id="text1"><?=$descricaoAvaliacao?></b></p>
					</a>
				</div>
			</div>
			<div class="col-sm-8">
					<div class="col-xs-12">
						<label>Descrição</label>
						<input type="text" class="form-control" id="descricao" name="descricao" value="<?=$descricaoAvaliacao;?>" onkeyup="caategor()">
					</div>				
			</div>
			<div class="container-fluid">
				<div class="row">
						<div class="col-xs-12" style="margin-top:10px;"><br>
							<button type="button" id="_00030" class="btn btn-success btn-block">Salvar</button>
							<input type="hidden" class="form-control" id="id_avaliacao" name="id_avaliacao" value="<?=$idAvaliacao;?>">
						</div>  					
					</div>
				
				</div>
			</div>
			</form>
		</div>
	</div>
	<script>
		function caategor(){
			var descricao = $("#descricao").val();
			
			$("#text1").html(descricao);
		}
		var alte = $(_00030).click(function(){       
				var $_keyid =   "avaliacao_00002";    			
				var dados = $("#form2 :input").serializeArray();
				dados = JSON.stringify(dados);
				
				$.post("page_return.php", {_keyform:$_keyid,dados:dados , acao:"altAvaliacao_001"}, function(result){		
					if(result == 1){ }else{	
						_lista();
						$("#divAlt").html(result);
					}
				});
				
		});		
	</script>
		
	<?php
	
	
}
if($acao == 4){
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Novo Sintoma</h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row">
					<div class="col-sm-9" align="center">
						<input type="text" id="nomesintoma" name="nomesintoma" class="form-control" value="" placeholder="Sintoma">
						<input type="hidden" id="idAvaliacao" name="idAvaliacao" class="form-control" value="<?=$variable;?>">
					</div>
					<div class="col-sm-3" align="center">
						<button type="button" class="btn btn-success btn-block" onclick="gerarSintoma()">Criar</button>
					</div>
				</div>
			</form>
		</div>
	<?php
}
if($acao == 5){
	
	$sql="select * from ".$_SESSION['BASE'].".avaliacao where avl_id = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$descricao = $value->avl_sintomas;
		}
	}	
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Alterar Sintoma</h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row">
					<div class="col-sm-9" align="center">
						<input type="text" id="nomeSintoma" name="nomeSintoma" class="form-control" value="<?=$descricao;?>" placeholder="Descrição Raça">
						<input type="hidden" id="idSintoma" name="idSintoma" class="form-control" value="<?=$variable;?>">
					</div>
					<div class="col-sm-3" align="center">
						<button type="button" class="btn btn-success btn-block" onclick="altSintoma()">Salvar</button>
					</div>
				</div>
			</form>
		</div>
	<?php
}

if($acao == 6){
	
	$sql="select * from ". $_SESSION['BASE'] .".avaliacao where avl_id = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$descricao = $value->avl_sintomas;
		}
	}	
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title"></h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row text-center">
					<div class="col-sm-12">
						<h4>Deseja excluir o sintoma <b>"<?=$descricao;?>"</b>?</h4>
						<input type="hidden" id="idsintoma" name="idsintoma" class="form-control" value="<?=$variable;?>">
					</div>
					<div class="col-sm-3" align="center"></div>
					<div class="col-sm-3" align="center">
						<button type="button" class="btn btn-default btn-block" data-dismiss="modal" aria-hidden="true">Não</button>
					</div>
					<div class="col-sm-3" align="center">
						<button type="button" class="btn btn-success btn-block" onclick="excluirSubcat()">Sim</button>
					</div>
				</div>
			</form>
		</div>
	<?php
}
if($acao == "insertAvaliacao"){
	if($_parametros != ""){
		if(count($_parametros) > 0) {	
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			$_retorno = Configuracoes::insert_avaliacao($_parametros);	
			
			if($_retorno != "ok"){
				echo($_retorno);
				exit();
			}	
		}
	}	
	
}
if($acao == "insertSintoma"){
	if($_parametros != ""){
		if(count($_parametros) > 0) {	
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			$_retorno = Configuracoes::insert_sintoma($_parametros);	
			echo($_retorno);
			if($_retorno != "ok"){
				echo($_retorno);
				exit();
			}	
		}
	}
}
if($acao == "altAvaliacao_001"){
	//ALTERA AVALIACAO

	$id_avaliacao = $_parametros['id_avaliacao'];
	$descricao = $_parametros['descricao'];
	
	
	$sql="update ". $_SESSION['BASE'] .".tipo_avaliacao set 
	tipaval_descricao = '$descricao'
	where 
	tipaval_id = '$id_avaliacao'
	";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title"></h4>
	</div>
	<div class="modal-body">                                 						   
		<div class="col-sm-12" align="center">			
			<p> Avaliação alterado com <strong>Sucesso !!!</strong></p>
		</div>

		<div class="row">
			<div class="col-sm-12" align="center">
			 <img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/>						  
			</div>
		</div> 						
	</div>	
	<?php
	
}
if($acao == "alteraSintoma"){
	
	if($_parametros != ""){
		$descricaoSintoma = $_parametros['nomeSintoma'];
		$variable = $_parametros['idSintoma'];
		
		if($descricaoSintoma != ""){
			
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			
			$exe = Configuracoes::update_sintoma($_parametros);
		}
		$_parametros = "";
	}	
}
if($acao == "excluirAvaliacao"){
	
	if($_parametros != ""){

		$variable = $_parametros['idsintoma'];
		
		if($variable != ""){
			
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			
			$exe = Configuracoes::delete_sintoma($_parametros);
		}
		$_parametros = "";
	}	
}

