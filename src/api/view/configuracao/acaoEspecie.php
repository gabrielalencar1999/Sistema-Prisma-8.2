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
		<h4 class="modal-title">Nova Espécie</h4>
	</div>
	
	<div class="modal-body">
		<form id="form2" name="form2">
			<div class="row" style="padding:15px;">  
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-12">
							<label class="control-label">Nova Espécie</label>    
							<input class="form-control" name="newEspecie" id="newEspecie" placeholder="Nome">
						</div>                                           
					</div> 
					<div class="row">
						<div class="col-sm-12"><br>
							<label class="control-label">Cor</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaCor.php'); ?>
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
		function selc(cor,id){
			
			var div = "#cor" + id;
			$("#cor").val(cor);

			$(".catCor").removeClass("selCor");				
			$(div).addClass("selCor");


		}
		function seli(icon,id){
				
				var div = "#icon" + id;
				$("#icon").val(icon);

				$(".styleIcon").removeClass("styleIconX");				
				$(div).addClass("styleIconX");


		}	
		var search2 = $(_00005).click(function(){       
					var $_keyid =   "especie_00003";    			
					var dados = $("#form2 :input").serializeArray();
					dados = JSON.stringify(dados);			

					var cor = $("#cor").val();
					
					if(cor == ""){
						alert("Necessário selecionar a cor");
					}else{
						$.post("page_return.php", {_keyform:$_keyid,dados:dados , acao:"insertEspecie"}, function(result){		
							if(result == 1){ 
							
							}else{
								_lista();
								$("#divAlt").html(
								'	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p> Espécie incluída com <strong>Sucesso !!!</strong></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/></div></div></div>'
								);	
							}
						});
					}

					/*					
					*/
					
			});
		</script>			
	<?php
	
}
if ($acao["acao"] == 2) {
 	//LISTA CATEGORIAS
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".especie ORDER BY especie_id ASC");
    $retorno = $statement->fetchAll();
	$tamanhoDiv = 25*$statement->rowCount();
	//$tamanhoDiv = 50+$tamanhoDiv;
    ?><div class="container-fluid" style="background-color:#EBEFF2;overflow-x:scroll !important; padding:20px; padding-top:30px; min-width:<?=$tamanhoDiv;?>%;"><?php
        foreach ($retorno as $rst) { ?>
            <div class="box-categoria">
			
				<!----
				<div class="altCat"><i class="fa fa-times"></i></div>
				
				<br><br><br>
				--->
				<div class="box" style="padding-bottom:75px; height:80px; ">  <!---margin-top:-33px;--->   
						<div class="bar-widget">
							<div class="iconbox" style="margin-right:0px; background-color:<?=$rst['especie_cor'];?>" onmouseover="alt('<?=$rst['especie_id'];?>')" onmouseout="off('<?=$rst['especie_id'];?>')" data-toggle="modal" data-target="#custom-modal-alterar" onclick="alterarCategoria('<?=$rst['especie_id'];?>')">
								<i id="iconn<?=$rst['especie_id'];?>" class="fa fa-paw"  style=""></i>
								<i id="alte<?=$rst['especie_id'];?>" class="fa fa-pencil" style="display:none;"></i>
							</div>
						</div>
						<p class="categoria_title" style=" margin-top:9px;" onmouseover="alt('<?=$rst['especie_id'];?>')" onmouseout="off('<?=$rst['especie_id'];?>')"><b style="font-size:14px;"><?=$rst['especie_nome'];?></b><br><?=$descTipo;?></p>
				</div>
				<hr>
				<h5 data-toggle="modal" data-target="#custom-modal-alterar" onclick="_addSub('<?=$rst['especie_id'];?>')"><b class="addSub">Adicionar Raça</b></h5>
				<?php
					$sql = "SELECT * FROM ". $_SESSION['BASE'] .".raca where raca_idespecie = '".$rst['especie_id']."' ORDER BY raca_nome ASC";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
					 // retornar os dados em formato de objeto
							
				?>
				<div style="overflow-y:auto; max-height:204px;">
					<table class="table table-bordered">
						<?php while ($value = $stm->fetch(PDO::FETCH_OBJ)) { ?>
							<tr>
								<td><a style="cursor:pointer" data-toggle="modal" data-target="#custom-modal-alterar" onclick="alterarSubCategoria('<?=$value->raca_id;?>')"><i class="fa fa-pencil"></i></a></td>
								<td><?=$value->raca_nome;?></td>
								<td><a style="cursor:pointer" data-toggle="modal" data-target="#custom-modal-alterar" onclick="excluirSubCategoria('<?=$value->raca_id;?>')"><i class="fa fa-times"></i></a></td>
							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
            <?php
        }
        ?>
        </div>
    <?php
}
//================================================================================================================================================================
//================================================================================================================================================================
//================================================================================================================================================================

if($acao == 3){
	
	$sql="select * from ". $_SESSION['BASE'] .".especie where especie_id = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$idespecie = $value->especie_id;
			$corEspecie = $value->especie_cor;
			$descricaoEspecie = $value->especie_nome;		
		}
	}else{
		exit();
	}
	
	?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Alterar Espécie</h4>
	</div>
	
	<div class="modal-body">
	<form id="form1" name="form1">
		<div class="row">
			<div class="col-sm-4 text-center" >
				<div class="box">
					<a onclick="selCategoria('<?=$idespecie;?>')"><div class="bar-widget">
						<div class="iconbox" id="view" style="margin-right:0px; background-color:<?=$corEspecie;?>">
							<i class="fa fa-paw"></i>
						</div>
					</div>
					<p class="categoria_title"><b style="font-size:14px;" id="text1"><?=$descricaoEspecie?></b></p>
					</a>
				</div>
			</div>
			<div class="col-sm-8">
					<div class="col-xs-12">
						<label>Descrição</label>
						<input type="text" class="form-control" id="descricao" name="descricao" value="<?=$descricaoEspecie;?>" onkeyup="caategor()">
					</div>				
			</div>
			<div class="container-fluid">
				<div class="row">
						<div class="col-xs-12" style="margin-top:10px;">
							<label class="control-label">Cor</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaCor.php'); ?>
						</div>
						<div class="col-xs-12" style="margin-top:10px;"><br>
							<button type="button" id="_00030" class="btn btn-success btn-block">Salvar</button>
							<input type="hidden" class="form-control" id="id_especie" name="id_especie" value="<?=$idespecie;?>">
						</div>  					
					</div>
				
				</div>
			</div>
			</form>
		</div>
	</div>
	<script>
		function selc(cor,id){
			
			var div = "#cor" + id;
			$("#cor").val(cor);

			$(".catCor").removeClass("selCor");				
			$(div).addClass("selCor");
			
			caategor();

		}
		function seli(icon,id){
				
				var div = "#icon" + id;
				$("#icon").val(icon);

				$(".styleIcon").removeClass("styleIconX");				
				$(div).addClass("styleIconX");
				
				caategor();

		}
		function caategor(){
			var descricao = $("#descricao").val();
			var cor = $("#cor").val();
			
			
			$("#view").css("backgroundColor",cor);
			$("#text1").html(descricao);
			$("#text2").html(tipo);
		}
			var alte = $(_00030).click(function(){       
					var $_keyid =   "especie_00005";    			
					var dados = $("#form1 :input").serializeArray();
					dados = JSON.stringify(dados);
					
					$.post("page_return.php", {_keyform:$_keyid,dados:dados , acao:"altCat_001"}, function(result){		
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
			<h4 class="modal-title">Nova Raça</h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row">
					<div class="col-sm-9" align="center">
						<input type="text" id="nomeraca" name="nomeraca" class="form-control" value="" placeholder="Descrição Raça">
						<input type="hidden" id="idEspecie" name="idEspecie" class="form-control" value="<?=$variable;?>">
					</div>
					<div class="col-sm-3" align="center">
						<button type="button" class="btn btn-success btn-block" onclick="gerarSub()">Criar</button>
					</div>
				</div>
			</form>
		</div>
	<?php
}
if($acao == 5){
	
	$sql="select * from ".$_SESSION['BASE'].".raca where raca_id = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$descricao = $value->raca_nome;
		}
	}	
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Alterar Raça</h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row">
					<div class="col-sm-9" align="center">
						<input type="text" id="nomeRaca" name="nomeRaca" class="form-control" value="<?=$descricao;?>" placeholder="Descrição Raça">
						<input type="hidden" id="idraca" name="idraca" class="form-control" value="<?=$variable;?>">
					</div>
					<div class="col-sm-3" align="center">
						<button type="button" class="btn btn-success btn-block" onclick="altSub()">Criar</button>
					</div>
				</div>
			</form>
		</div>
	<?php
}

if($acao == 6){
	
	$sql="select * from ". $_SESSION['BASE'] .".raca where raca_id = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$descricao = $value->raca_nome;
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
						<h4>Deseja excluir a raça <b>"<?=$descricao;?>"</b>?</h4>
						<input type="hidden" id="idraca" name="idraca" class="form-control" value="<?=$variable;?>">
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
if($acao == "insertEspecie"){
	if($_parametros != ""){
		if(count($_parametros) > 0) {	
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			$_retorno = Configuracoes::insert_especie($_parametros);	
			
			if($_retorno != "ok"){
				echo($_retorno);
				exit();
			}	
		}
	}	
	
}
if($acao == "insertRaca"){
	if($_parametros != ""){
		if(count($_parametros) > 0) {	
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			$_retorno = Configuracoes::insert_raca($_parametros);	
			
			if($_retorno != "ok"){
				echo($_retorno);
				exit();
			}	
		}
	}
}
if($acao == "altCat_001"){
	//ALTERA ESPECIE

	$id_especie = $_parametros['id_especie'];
	$descricao = $_parametros['descricao'];
	$cor = $_parametros['cor'];
	
	
	$sql="update ". $_SESSION['BASE'] .".especie set 
	especie_nome = '$descricao',
	especie_cor = '$cor'
	where 
	especie_id = '$id_especie'
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
			<p> Espécie alterado com <strong>Sucesso !!!</strong></p>
		</div>

		<div class="row">
			<div class="col-sm-12" align="center">
			 <img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/>						  
			</div>
		</div> 						
	</div>	
	<?php
	
}
if($acao == "alteraRaca"){
	
	if($_parametros != ""){
		$descricaoSubcategoria = $_parametros['nomeRaca'];
		$variable = $_parametros['idraca'];
		
		if($descricaoSubcategoria != ""){
			
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			
			$exe = Configuracoes::update_raca($_parametros);
		}
		$_parametros = "";
	}	
}
if($acao == "excluirRaca"){
	
	if($_parametros != ""){

		$variable = $_parametros['idraca'];
		
		if($variable != ""){
			
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			
			$exe = Configuracoes::delete_raca($_parametros);
		}
		$_parametros = "";
	}	
}

