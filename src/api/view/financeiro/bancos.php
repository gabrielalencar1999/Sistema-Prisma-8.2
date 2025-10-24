<?php
use Functions\Financeiro; 


$_parametrosbanco = array(
	'_bd' =>$_SESSION['BASE']
);
$_retorno = Financeiro::bancos($_parametrosbanco);


?>


<select type="text" class="form-control" name="financeiro_tipoPagamento" id="financeiro_tipoPagamento">
<option value="" ></option>
<?php foreach($_retorno as $value){ ?>
	<option value="<?=$value->BCO_ID;?>" ><?=$value->BCO_NOME;?> - <?=$value->BCO_CONTA;?></option>
<?php } ?>
<select>