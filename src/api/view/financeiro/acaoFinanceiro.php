<?php 
use Functions\Financeiro;

	if($_parametros != ""){

		$_bd = array(
			'_bd' =>$_SESSION['BASE']    
		);
			
			
		$_parametros =  array_merge($_parametros, $_bd);
		$exe = Financeiro::insert_financeiro($_parametros);
		$_parametros = ""; 
	}
