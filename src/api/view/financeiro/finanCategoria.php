<?php 
use Functions\Financeiro; 
	if($_parametros != ""){
		if(count($_parametros) > 0) {	
			$_bd = array(
				'_bd' =>$_SESSION['BASE']    
			);
			
			$_parametros =  array_merge($_parametros, $_bd);
			$_retorno = Financeiro::insert_categoria($_parametros);	
			
			if($_retorno != "ok"){
				echo($_retorno);
				exit();
			}	
		}
	}

	//BUSCA 
	$_parametrosCategoria = array('_bd' =>$_SESSION['BASE']);
	if($_POST['acao'] == ""){
		//alterar REGISTRO entra aqui
		
		// $x = vem da tela modalAlterar.php (pra diferenciar o modal "filtros" e "novo lancamento")
		if($x == "1"){
			$financeiro_tipo = 0;
		}
		$_parametrosCategoria['tipoCategoria'] = $financeiro_tipo;
	}else{
		$_parametrosCategoria['tipoCategoria'] = $_POST['acao'];
	}
	$_parametrosCategoria =  array_merge($_parametrosCategoria);

	//print_r($_parametrosCategoria['tipoCategoria']);
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
	<a onclick="selCategoria('<?=$value->id_categoria;?>')"><div class="bar-widget">
		<div class="iconbox" style="margin-right:0px; background-color:<?=$value->cor_categoria;?>">
			<i class="<?=$value->icon_categoria;?>"></i>
		</div>
	</div>
	<p class="categoria_title"><b style="font-size:14px;"><?=$value->descricao_categoria?></b><br><?=$descTipo;?></p>
	</a>
</div>   
	<?php } ?> 

	

