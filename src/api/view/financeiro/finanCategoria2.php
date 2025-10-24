<?php 
use Functions\Financeiro; 

	//BUSCA 
	$_parametrosCategoria = array('_bd' =>$_SESSION['BASE']);
	$_parametrosCategoria['tipoCategoria'] = $_POST['acao'];
	$_parametrosCategoria =  array_merge($_parametrosCategoria);

	//print_r($_parametrosCategoria);
	$_retorno = Financeiro::categoria($_parametrosCategoria);
	//print_r($_retorno);
	
	foreach($_retorno as $value){
		
		if($value->tipo_categoria == '0'){
			$descTipo = "Receita";
		}else{
			if($value->tipo_categoria == '1'){
				$descTipo = "Despesa";
			}else{
				$descTipo = "Ambos";
			}
		}
?>
<div class="box">
	<a onclick="selCategoria2('<?=$value->id_categoria;?>')"><div class="bar-widget">
		<div class="iconbox" style="margin-right:0px; background-color:<?=$value->cor_categoria;?>">
			<i class="<?=$value->icon_categoria;?>"></i>
		</div>
	</div>
	<p class="categoria_title"><b style="font-size:14px;"><?=$value->descricao_categoria?></b><br><?=$descTipo;?></p>
	</a>
</div>   
	<?php } ?> 

	

