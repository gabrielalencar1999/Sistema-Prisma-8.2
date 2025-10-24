<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();
$sql = "Select Cod_Tecnico_Execucao,CODIGO_CHAMADA,DATA_ATEND_PREVISTO,GARANTIA,CODIGO_CONSUMIDOR,
            sitmob_descricao,sitmob_cor,sitmob_img,g_sigla,SIT_TRACKMOB,sitmob_cortable,sitmob_cortfont,g_cor
            from ".$_SESSION['BASE'].".chamada      
            left join ".$_SESSION['BASE'].".situacao_trackmob ON sitmob_id = SIT_TRACKMOB
            left join ".$_SESSION['BASE'].".situacao_garantia ON g_id = GARANTIA
            where  DATA_ATEND_PREVISTO >  '2022-10-25'
           ";   
$stm = $pdo->prepare("$sql");               
   
//$stm->execute();	
   
	  if ( $stm->rowCount() > 0 ){
	  
		  while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$garantia = $linha->GARANTIA; 
			$chamada = $linha->CODIGO_CHAMADA;
			$dtaberturaSel = $linha->DATA_ATEND_PREVISTO;
			$cliente = $linha->CODIGO_CONSUMIDOR;
			$tecnico = $linha->Cod_Tecnico_Execucao;
			$insert = "INSERT INTO ".$_SESSION['BASE'].".trackOrdem (trackO_data,trackO_chamada,trackO_ordem,trackO_tecnico,trackO_periodo,trackO_garantia,trackO_idcli) 
			VALUES ('" . $dtaberturaSel. "','" .$chamada . "','0','$tecnico','0','$garantia','$cliente')";
			$stm2 = $pdo->prepare("$insert");                   
   		//	$stm2->execute();	

		}
	}

?>