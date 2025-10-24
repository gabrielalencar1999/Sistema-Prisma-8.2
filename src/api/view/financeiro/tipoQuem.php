<?php 
use Functions\Financeiro; 

if($_POST['acao'] == "" or $_POST['acao'] == 0){
	?>
	<label class="control-label">Nome</label> 
	<input type="text" class="form-control" placeholder="selecione..." disabled>	
	<?php
	exit();
	
//===============================================================================================================	
//===============================================================================================================	
//===============================================================================================================	
}else{

$_parametrosQuem = array(
	'_bd' =>$_SESSION['BASE']
);
$_parametrosQuem['tipo'] = $_POST['acao'];
$_parametrosQuem['idconsumidor'] = $_POST['idconsumidor'];
$_parametrosQuem['idOS'] = $_POST['idOS'];
$_retornoTipoQm = Financeiro::buscaQuem($_parametrosQuem);

if($_POST['tipoPesq'] != ""){
	$tipoPesq = '2';
}else{
	$onchange = 'onchange="texto()"';
}

?>

	<label class="control-label">Nome</label>    
	<select type="text" class="form-control" name="financeiro_codigoCliente<?=$tipoPesq;?>" id="financeiro_codigoCliente<?=$tipoPesq?>" <?=$onchange;?> >
	<option value="" ></option>
	<?php foreach($_retornoTipoQm as $valuee){ 

		if($_POST['acao'] == 1){
			$idQuem = $valuee->CODIGO_CONSUMIDOR;
			$descricaoQuem = $valuee->Nome_Consumidor;
		}
		if($_POST['acao'] == 2){
			$idQuem = $valuee->CODIGO_FABRICANTE;
			$descricaoQuem = $valuee->NOME." (".$valuee->CNPJ.")";
		}
		if($_POST['acao'] == 3){
			$idQuem = $valuee->usuario_CODIGOUSUARIO;
			$descricaoQuem = $valuee->usuario_APELIDO;
		}
		
	?>
		<option <?php if($idQuem == $financeiro_codigoCliente){ echo'selected="selected"';}?> value="<?=$idQuem;?>" ><?=$descricaoQuem?></option>
	<?php } ?>
	<select>
<?php } ?>