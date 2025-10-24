<?php 
use Functions\Financeiro; 


$_parametrosTipoPagamento = array(
	'_bd' =>$_SESSION['BASE']
);
$_retorno = Financeiro::tipoPagamento($_parametrosTipoPagamento);


?>


<select type="text" class="form-control" name="financeiro_tipoPagamento_filtro" id="financeiro_tipoPagamento_filtro">
<option value="" ></option>
<?php foreach($_retorno as $value){ ?>
	<option <?php if($value->id == $financeiro_tipoPagamento){ echo'selected="selected"';}?> value="<?=$value->id;?>" ><?=$value->nome;?></option>
<?php } ?>
<select>