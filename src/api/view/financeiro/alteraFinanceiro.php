<?php 
use Functions\Financeiro;

	if($_parametros != ""){

		$_bd = array(
			'_bd' =>$_SESSION['BASE']    
		);
			
			
		$_parametros =  array_merge($_parametros, $_bd);
		$exe = Financeiro::altera_financeiro($_parametros);
	
		//var_dump($exe);
		$_parametros = ""; 
	}
