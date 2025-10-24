<?php
use Functions\Financeiro; 
	$_parametrosCategoria = array(
		'_bd' =>$_SESSION['BASE']
	);
	
	$_parametrosCategoria =  array_merge($_parametrosCategoria);

	//print_r($_parametrosCategoria);
	$_retorno = Financeiro::categoriaIcon($_parametrosCategoria);
	$ci = 1;
	foreach($_retorno as $value){
		?>
		<a id="icon<?=$ci;?>" class="styleIcon   <?php if($iconCastegoria == $value->icon){ echo 'styleIcon2';}?>" onclick="seli('<?=$value->icon;?>','<?=$ci;?>');"><i  class="<?=$value->icon;?>"></i></a>
		
<?php $ci++; 
	} 
?>	<input type="hidden" name="icon" id="icon" value="<?=$iconCastegoria;?>" >