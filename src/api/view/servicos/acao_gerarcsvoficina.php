<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;
use Functions\NFeService;
use NFePHP\NFe\Common\Standardize;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia ;


$_acao = $_POST["acao"];


if($_acao == 0 ) {   //botao csv roteiro
	?>
 		<button class="btn btn-white waves-effect waves-light"  onclick="_gerarcsv(1)"> <i class="fa  fa-file-excel-o"></i> Gerar Roteiro</button>
	<?php

}


if($_acao == 1 ) {   //gerar csv roteiro
	$dir = "docs/".$_SESSION['CODIGOCLI'];
    
    $arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/RoteiroOficina.csv";
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

		$dataini = $_parametros['_dataIni'];   
		// $datafim = $_parametros['_dataFim']; 
		$datafim = $dataini; 
  
		 $ordem = $_parametros['ordem'];
  
		 $dia       = date('d'); 
		 $mes       = date('m'); 
		 $ano       = date('Y'); 
		// $data_atual      = $dia."/".$mes."/".$ano; 
		$data_atual = $ano."-".$mes."-".$dia; ;
		//  $data_atual2      = "01/".$mes."/".$ano; 
		$data     = $ano."-".$mes."-".$dia; 
  
		   
	   if($dataini == "" ) {
		  $date = date("Ymd");
		 $nextdate = addDayIntoDate($date,1);    // Adiciona 15 dias
			  $ano = substr ( $nextdate, 0, 4 );
			  $mes = substr ( $nextdate, 4, 2 );
			  $dia =  substr ( $nextdate, 6, 2 ); 
			  $data_prevista      = $ano."-".$mes."-".$dia;
			
			  $dataini =   $data_prevista ;
			  $datafim =   $data_prevista ;
	   }
  
	   $situacao =   $_parametros['situacao'];

  
	   if($situacao != "" ) {
			  $sit = " and  SituacaoOS_Elx = '$situacao'";            
	   }

	  $_os =  $_parametros['_os'];
	   if($_os != "" ) { 
		  $_filos = " OR CODIGO_CHAMADA = '$_os'  ";
		  $_filosAnd = " AND CODIGO_CHAMADA = '$_os'  ";
	   }
  
	   $_garantia =  $_parametros['garantia'];
	   if($_garantia != "" ) {        
		  $_filgarantia = " AND GARANTIA = '$_garantia'  ";
	   }
  
	   if($filtro == "") { $filtro = "DATA_ENTOFICINA";} //DATA_ATEND_PREVISTO
  
	   if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 
  
	   $tecnico_of = $_parametros['tecnico_of'];	

	   if($tecnico_of != "" ) { 
		  $filofcina = " and COD_TEC_OFICINA = '$tecnico_of'  ";
		 
		 
	   }
	   
	   $_sql = "Select g_sigla,CODIGO_CHAMADA,Nome_Consumidor,FONE_CELULAR,FONE_COMERCIAL,FONE_RESIDENCIAL,
	   Nome_Rua,Num_Rua,BAIRRO,CIDADE,CEP,
	   Obs_Atend_Externo,situacaoos_elx.DESCRICAO,DDD,
		DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA,
				   DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS DATA_A,
				   DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E,date_format(Hora_Marcada,'%T') as horaA,
				   date_format(Hora_Marcada_Ate,'%T') as horaB
				   , A.usuario_APELIDO  as tecnico, B.usuario_APELIDO  as atendente,sitmobOF_descricao,
				    chamada.descricao AS DESCA,Modelo,serie
				   from ". $_SESSION['BASE'] .".chamada               
				   left join ". $_SESSION['BASE'] .".situacaoos_elx on COD_SITUACAO_OS = SituacaoOS_Elx
				   left join ". $_SESSION['BASE'] .".situacao_oficina on sitmobOF_id = SIT_OFICINA
				   left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
				   left join ". $_SESSION['BASE'] .".usuario as A on COD_TEC_OFICINA = usuario_CODIGOUSUARIO
				   left join ". $_SESSION['BASE'] .".usuario as B on CODIGO_ATENDENTE = B.usuario_CODIGOUSUARIO
				   left join ". $_SESSION['BASE'] .".situacao_garantia  on g_id = garantia
					  where  $filtro between	'$dataini' and  '$datafim' 
				   and  ISNULL(DATA_ENCERRAMENTO)  $filofcina  $sit $_filgarantia $_filosAnd or 
				   $filtro between	'$dataini' and ' $datafim'  and  DATA_ENCERRAMENTO = '0000-00-00' $filofcina$filofcina $sit $_filgarantia $_filosAnd $_filos
				   order by $ordem";
					 

			$statement = $pdo->query("$_sql");		   
			$retorno = $statement->fetchAll(PDO::FETCH_OBJ);
	
			$fp = fopen($arquivo_caminho,"w+");// Escreve "exemplo de escrita" no bloco1.txt
		   // $_itemlinha = "Codigo;Descrição;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
		   
			$_itemlinhatitulo = "Nº OS;Garantia;Tec.Oficina;Sit Oficina;Dt Abertura;Dt Atendimento;Cliente;Telefone;Cep;Endereço;Bairro;Cidade;Observação;Situação;Produto,Modelo,Serie,Atendente";
			fwrite($fp,$_itemlinhatitulo."\r\n");

			foreach ($retorno as $row) {
				    $garantia = $linha->g_sigla;	
				if($garantia == "") {
					$garantia =  "FG";
				  }      								
				$_itemlinha = $row->CODIGO_CHAMADA.";".$garantia.";".$row->tecnico.";".$row->DESCRICAO.";".$row->DATA.";".$row->DATA_A.";".$row->Nome_Consumidor.";".$row->DDD." - ".$row->FONE_CELULAR." ".$row->FONE_COMERCIAL." ".$row->FONE_RESIDENCIAL.";".$row->CEP.";".$row->Nome_Rua." ".$row->Num_Rua.";".$row->BAIRRO.";".$row->CIDADE.";".$row->Obs_Atend_Externo.";".$row->DESCA.";".$row->Modelo.";".$row->serie.";".$row->atendente;
				fwrite($fp,$_itemlinha."\r\n");			
			}
					  fclose($fp);   					 					
						?>
					<div >
						<a href="<?=$arquivo_caminho;?>" target="_blank"><button type="button" class=" btn btn-primary waves-effect waves-light">   <span><i class="ion-ios7-cloud-download m-r-5"></i> Download Roteiro Técnico</span> </button></a>
						<button id="voltarbt" type="button" class="btn btn-white waves-effect waves-light" onclick="_gerarcsv(0)"><i class="fa fa-times"></i></button>
					</div>
					<?php
						
		
	
	}

	 


?> 