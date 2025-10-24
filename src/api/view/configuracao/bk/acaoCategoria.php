<?php

use Database\MySQL;
use Functions\Financeiro;
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
	//INCLUIR NOVA CATEGORIA
	?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Nova Categoria</h4>
	</div>
	
	<div class="modal-body">
		<form id="form2" name="form2">
			<div class="row" style="padding:15px;">  
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-8">
							<label class="control-label">Nova Categoria</label>    
							<input class="form-control" name="newCategoria" id="newCategoria" placeholder="Descricao">
						</div>  
						<div class="col-sm-4">
							<label class="control-label">Tipo Categoria</label>    
							<select class="form-control" name="tipoCategoria" id="tipoCategoria">
								<option value="2">Ambos</option>
								<option value="0">Receita</option>
								<option value="1">Despesa</option>
							</select>
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
							<label class="control-label">Icones</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaIcon.php'); ?>
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
					var $_keyid =   "_Fl00007";    			
					var dados = $("#form2 :input").serializeArray();
					dados = JSON.stringify(dados);
					
					var newCategoria = $("#newCategoria").val();
					var tipoCategoria = $("#tipoCategoria").val();
					var cor = $("#cor").val();
					var icon = $("#icon").val();
					
					if(newCategoria == ""){
						alert("Necessário preencher categoria");
					}else{
						if(tipoCategoria == ""){
							alert("Necessário selecionar Tipo Categoria");
						}else{
							if(cor == ""){
								alert("Necessário selecionar a cor");
							}else{
								if(icon == ""){
									alert("Necessário selecionar um icone");
								}else{
									$.post("page_return.php", {_keyform:$_keyid,dados:dados , acao:tipoCategoria}, function(result){		
										if(result == 1){ 
										
										}else{
											_lista();
											$("#divAlt").html(
											'	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p> Categoria incluída com <strong>Sucesso !!!</strong></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/></div></div></div>'
											);	

										}
									});
								}
							}
						}
					}
					/*					
					*/
					
			});
		</script>			
	<?php
	
}
if ($acao["acao"] == 2) {
 	//LISTA CATEGORIAS
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".categoria ORDER BY id_categoria ASC");
    $retorno = $statement->fetchAll();
	$tamanhoDiv = 25*$statement->rowCount();
	//$tamanhoDiv = 50+$tamanhoDiv;
    ?><div class="container-fluid" style="background-color:#EBEFF2;overflow-x:scroll !important; padding:20px; padding-top:30px; min-width:<?=$tamanhoDiv;?>%;"><?php
        foreach ($retorno as $rst) {
			if($rst['tipo_categoria'] == 0){
				$descTipo = "Receita";
			}
			if($rst['tipo_categoria'] == 1){
				$descTipo = "Despesa";
			}
			if($rst['tipo_categoria'] == 2){
				$descTipo = "Ambos";
			}
            ?>
            <div class="box-categoria">
			
				<!----
				<div class="altCat"><i class="fa fa-times"></i></div>
				
				<br><br><br>
				--->
				<div class="box" style="padding-bottom:75px; height:80px; ">  <!---margin-top:-33px;--->   
						<div class="bar-widget">
							<div class="iconbox" style="margin-right:0px; background-color:<?=$rst['cor_categoria'];?>" onmouseover="alt('<?=$rst['id_categoria'];?>')" onmouseout="off('<?=$rst['id_categoria'];?>')" data-toggle="modal" data-target="#custom-modal-alterar" onclick="alterarCategoria('<?=$rst['id_categoria'];?>')">
								<i id="iconn<?=$rst['id_categoria'];?>" class="<?=$rst['icon_categoria'];?>"  style=""></i>
								<i id="alte<?=$rst['id_categoria'];?>" class="fa fa-pencil" style="display:none;"></i>
							</div>
						</div>
						<p class="categoria_title" style=" margin-top:9px;" onmouseover="alt('<?=$rst['id_categoria'];?>')" onmouseout="off('<?=$rst['id_categoria'];?>')"><b style="font-size:14px;"><?=$rst['descricao_categoria'];?></b><br><?=$descTipo;?></p>
				</div>
				<hr>
				<h5 data-toggle="modal" data-target="#custom-modal-alterar" onclick="_addSub('<?=$rst['id_categoria'];?>')"><b class="addSub">Adicionar Subcategoria</b></h5>
				<?php
					$sql = "SELECT * FROM ". $_SESSION['BASE'] .".subcategoria where ref_subcategoria = '".$rst['id_categoria']."' ORDER BY id_subcategoria ASC";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
					 // retornar os dados em formato de objeto
							
				?>
				<div style="overflow-y:auto; max-height:204px;">
					<table class="table table-bordered">
						<?php while ($value = $stm->fetch(PDO::FETCH_OBJ)) { ?>
							<tr>
								<td><a style="cursor:pointer" data-toggle="modal" data-target="#custom-modal-alterar" onclick="alterarSubCategoria('<?=$value->id_subcategoria;?>')"><i class="fa fa-pencil"></i></a></td>
								<td><?=$value->descricao_subcategoria;?></td>
								<td><a style="cursor:pointer" data-toggle="modal" data-target="#custom-modal-alterar" onclick="excluirSubCategoria('<?=$value->id_subcategoria;?>')"><i class="fa fa-times"></i></a></td>
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
	
	$sql="select * from ". $_SESSION['BASE'] .".categoria where id_categoria = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$idcategoria = $value->id_categoria;
			$iconCastegoria = $value->icon_categoria;
			$corCategoria = $value->cor_categoria;
			$descricaoCategoria = $value->descricao_categoria;
			$tipoCategoria = $value->tipo_categoria;
			
			if($value->tipo_categoria == 0){
				$descTipo = "Receita";		
			}
			if($value->tipo_categoria == 1){
				$descTipo = "Despesa";
			}
			if($value->tipo_categoria == 2){
				$descTipo = "Ambos";
			}
		
		}
	}else{
		exit();
	}
	
	?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Alterar Categoria</h4>
	</div>
	
	<div class="modal-body">
	<form id="form1" name="form1">
		<div class="row">
			<div class="col-sm-4 text-center" >
				<div class="box">
					<a onclick="selCategoria('<?=$idcategoria;?>')"><div class="bar-widget">
						<div class="iconbox" id="view" style="margin-right:0px; background-color:<?=$corCategoria;?>">
							<i class="<?=$iconCastegoria;?>"></i>
						</div>
					</div>
					<p class="categoria_title"><b style="font-size:14px;" id="text1"><?=$descricaoCategoria?></b><br><span id="text2"><?=$descTipo;?></span></p>
					</a>
				</div>
			</div>
			<div class="col-sm-8">
					<div class="col-xs-12">
						<label>Descrição</label>
						<input type="text" class="form-control" id="descricao" name="descricao" value="<?=$descricaoCategoria;?>" onkeyup="caategor()">
					</div>				
					<div class="col-xs-12" style="margin-top:10px;">
						<label>Tipo</label>
						<select class="form-control" id="tipo" name="tipo" onchange="caategor()">
							<option value="0" <?php if($tipoCategoria == 0){ echo'selected="selected"';}?>>Receita</option>
							<option value="1" <?php if($tipoCategoria == 1){ echo'selected="selected"';}?>>Despesa</option>
							<option value="2" <?php if($tipoCategoria == 2){ echo'selected="selected"';}?>>Ambos</option>
						</select>
					</div>
			</div>
			<div class="container-fluid">
				<div class="row">
						<div class="col-xs-12" style="margin-top:10px;">
							<label class="control-label">Cor</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaCor.php'); ?>
						</div>
						<div class="col-xs-12" style="margin-top:10px;">
							<label class="control-label">Icones</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaIcon.php'); ?>
						</div>
						<div class="col-xs-12" style="margin-top:10px;"><br>
							<button type="button" id="_00030" class="btn btn-success btn-block">Salvar</button>
							<input type="hidden" class="form-control" id="id_categoria" name="id_categoria" value="<?=$idcategoria;?>">
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
			var tipo = $('#tipo :selected').text();
			var cor = $("#cor").val();
			var icon = $("#icon").val();
			
			
			$("#view").css("backgroundColor",cor);
			$("#view").html('<i class="'+icon+'"></i>');
			$("#text1").html(descricao);
			$("#text2").html(tipo);
		}
			var alte = $(_00030).click(function(){       
					var $_keyid =   "categoria_00002";    			
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
			<h4 class="modal-title">Nova Subcategoria</h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row">
					<div class="col-sm-9" align="center">
						<input type="text" id="nomeSubcategoria" name="nomeSubcategoria" class="form-control" value="" placeholder="Descrição subcategoria">
						<input type="hidden" id="idCategoria" name="idCategoria" class="form-control" value="<?=$variable;?>">
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
	
	$sql="select * from ". $_SESSION['BASE'] .".subcategoria where id_subcategoria = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$descricao = $value->descricao_subcategoria;
		}
	}	
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Alterar Subcategoria</h4>
		</div>
	
		<div class="modal-body">
			<form id="form3" name="form3">
				<div class="row">
					<div class="col-sm-9" align="center">
						<input type="text" id="nomeSubcategoria" name="nomeSubcategoria" class="form-control" value="<?=$descricao;?>" placeholder="Descrição subcategoria">
						<input type="hidden" id="idsubCategoria" name="idsubCategoria" class="form-control" value="<?=$variable;?>">
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
	
	$sql="select * from ". $_SESSION['BASE'] .".subcategoria where id_subcategoria = '$variable'";
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$descricao = $value->descricao_subcategoria;
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
						<h4>Deseja excluir a subcategoria <b>"<?=$descricao;?>"</b>?</h4>
						<input type="hidden" id="idsubCategoria" name="idsubCategoria" class="form-control" value="<?=$variable;?>">
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
if($acao == "altCat_001"){
	//ALTERA CATEGORIA

	$id_categoria = $_parametros['id_categoria'];
	$descricao = $_parametros['descricao'];
	$tipo = $_parametros['tipo'];
	$icon = $_parametros['icon'];
	$cor = $_parametros['cor'];
	
	
	$sql="update ". $_SESSION['BASE'] .".categoria set 
	descricao_categoria = '$descricao',
	tipo_categoria = '$tipo',
	cor_categoria = '$cor',
	icon_categoria = '$icon'
	where 
	id_categoria = '$id_categoria'
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
			<p> Categoria alterado com <strong>Sucesso !!!</strong></p>
		</div>

		<div class="row">
			<div class="col-sm-12" align="center">
			 <img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/>						  
			</div>
		</div> 						
	</div>	
	<?php
	
}
if($acao == "alteraSubcategoria"){
	
	if($_parametros != ""){
		$descricaoSubcategoria = $_parametros['nomeSubcategoria'];
		$variable = $_parametros['idsubCategoria'];
		
		if($descricaoSubcategoria != ""){
			
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			
			$exe = Financeiro::update_subcategoria($_parametros);
		}
		$_parametros = "";
	}	
}
if($acao == "excluirSubcategoria"){
	
	if($_parametros != ""){

		$variable = $_parametros['idsubCategoria'];
		
		if($variable != ""){
			
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			
			$exe = Financeiro::delete_subcategoria($_parametros);
		}
		$_parametros = "";
	}	
}

