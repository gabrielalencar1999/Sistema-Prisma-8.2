<?php
use Functions\Financeiro; 
	$_parametrosCategoria = array(
		'_bd' =>$_SESSION['BASE']
	);
	
	$_parametrosCategoria =  array_merge($_parametrosCategoria);

	//print_r($_parametrosCategoria);
	$_retorno = Financeiro::categoriaCor($_parametrosCategoria);
	$ci = 1;
	foreach($_retorno as $value){
?>
		<div id="cor<?=$ci;?>" class="catCor  <?php if($corCategoria == $value->codigo_cor ){ echo 'selCor';}?>" style="background-color:<?=$value->codigo_cor;?>;" onclick="selc('<?=$value->codigo_cor;?>','<?=$ci;?>');"></div>
		
<?php 
	$ci++;
	}
?>
	<input type="hidden" name="cor" id="cor" value="<?=$corCategoria;?>" >