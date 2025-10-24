<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


date_default_timezone_set('America/Sao_Paulo');

use Database\MySQL;

$pdo = MySQL::acessabd();


$_acao = $_POST["acao"];

if($_acao == 0) {  

	$_filempresarel = $_parametros['osempresa'];

	$sqlrel = "Select * from " . $_SESSION['BASE'] . ".relatorio_OS where  rel_empresa=  '0'  or rel_empresa = '$_filempresarel'  order by rel_ordem ASC ";
	$consultaRel = $pdo->query($sqlrel);
	$retornoRel = $consultaRel->fetchAll(\PDO::FETCH_OBJ);
	  ?> 
	  <select name="osimpressao" id="osimpressao" class="form-control input-sm">
		 <?php
			foreach ($retornoRel as $rowRel) {                                                                
		  ?>
		  <option value="<?=$rowRel->rel_id;?>" <?php if ($rowRel->rel_id ==  '1') { ?>selected="selected" <?php } ?> ><?=$nomeEmp;?> <?=$rowRel->rel_descricao;?></option>        
		   <?php
		   $nomeEmp = "";
		  } ?>                                                          
	  </select>
  
  <?php
}

?> 