<?php 
include("../../api/config/conexaobase.php");

$servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
$user_conect = 'admin';
$senha = '5m5IQK4lgWZ3rasuSnnC';
$banco_conect = 'bd_tecfast';
$_SESSION['BASE'] = "bd_tecfast";
$_SESSION['CODIGOCLI'] = '9000';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
function LimpaVariavel($_texto)
{
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	$_texto =    str_replace("NULL", "", $_texto);
  return $_texto;
}

function LimpaVariavelDoc($valor){
    $valor = trim($valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


function CFOP($CFOP){
    if("6"  == substr($CFOP,0,1) or "2"  == substr($CFOP,0,1)){
         
        switch ($CFOP) {
            case "6401":
                $valor = '2403';
                break;
            case "6101":
                $valor = '2102';
                break;
            case "6949":
                $valor = '2949';
                break;
            case "6102":
                $valor = '2102';
                break;
             case "6403":
                $valor = '2403';
                break;    

        }  
        
         if($valor == "") {
                $valor  = "2".substr($CFOP,-3);        
            }
                     
      }else{
        $valor  = "1".substr($CFOP,-3);//1102; 
      }

    return $valor;
}



           
  $tiponf = "SINTEGRA" ;


 if($_parametros['nf-inicial'] == '') {
     $_parametros['nf-inicial'] = date('Y-m-d');
     $_parametros['nf-final'] = date('Y-m-d');
 } 
 
 
if($_parametros['_numeronf'] != '') {
 $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
}

$_parametros['nf-inicial'] = '2023-10-01';
$_parametros['nf-final'] = '2023-10-31';

$cliente = $_SESSION['BASE_ID'];
$mesInicial = explode("-", $_parametros['nf-final'] );   

$AdiaFinal= $mesInicial[2];
$AmesFinal= $mesInicial[1];
$AanoFinal  = $mesInicial[0];

$mesInicial = explode("-", $_parametros['nf-inicial'] );   

$Adia= $mesInicial[2];
$Ames= $mesInicial[1];
$Aano  = $mesInicial[0];
$nomearquivo = $Ames."_".$Aano;

//gerar COM Situação tributaria
 
try {
  $porfornecedor = $_parametros['rel-fornecedor'];
  $filtrarDT = 'NFE_DATAEMIS';
  
   if($_parametros['_numeronf'] != '') {
      $filnumero = " AND nota_ent_item.NFE_NRO = '".$_parametros['_numeronf']."' OR nota_ent_item.NFE_NRO = '".$_parametros['_numeronf']."'";
      $filnumero2 = " AND NFE_NRO = '".$_parametros['_numeronf']."' OR NFE_NRO = '".$_parametros['_numeronf']."'";
  }
  $tiponf = "REL_ComSituacaoTributaria" ;
  
  if($_parametros['nf-inicial'] == '') {
      $_parametros['nf-inicial'] = date('Y-m-d');
      $_parametros['nf-final'] = date('Y-m-d');
  } 
  
  

$cliente = $_SESSION['BASE_ID'];

$mesInicial = explode("-", $_parametros['nf-inicial'] );   

$Ames= $mesInicial[1];
$Aano  = $mesInicial[0];
$nomearquivo = $Ames."_".$Aano;


$dir = "docs/".$_SESSION['CODIGOCLI'];

$arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/Rel_comSubstituicaoTributaria_".$nomearquivo.".csv";
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

  $porfornecedor = 1;
  //LISTA PADRAO SEM FORNECEDOR
 //LISTA PADRAO SEM FORNECEDOR

       if($porfornecedor == 0) { 
/*
        $_sql = "SELECT NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
        FROM ".$_SESSION['BASE'].".nota_ent_base
        LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
        LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
        LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal       
        LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC    
        WHERE  NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' ";
  */
    $_sql = "SELECT nota_ent_item.NFE_NRO,fabricante.NOME,nota_ent_item.NFE_CFOP,NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,fabricante.UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
    FROM ".$_SESSION['BASE'].".nota_ent_base
    LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
    LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
    LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal   
    LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE   = nota_ent_base.NFE_FORNEC       
    LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC    
    WHERE  nota_ent_item.NFE_CFOP <> '6949' and  nota_ent_item.NFE_CFOP <> '6910' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero ";

        $statement = $pdo->query("$_sql");
       
        $retorno = $statement->fetchAll();

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
       // $_itemlinha = "Codigo;Descrição;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
        $_itemlinha = "Fornecedor;Numero NF;Codigo;Descrição;cfop;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
        //UF
          //  $_xml =$row['nfed_xml_protocolado'];
          $NUMERO_NFE = $row['NFE_NRO'];
          $VALORNF = number_format($row2['NFE_TOTALNF'], 2, ',', '.');
          $FORNECEDOR = $row['NOME'];
           // $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi
           $vlsemipi = $row['NF_CUSTO_ORIG']; // VALOR ipi
            $vlTotalsemipi = $vlsemipi*$row['NFE_QTDADE'] ;  //35,20
            //$vlicmsOrigem = $row['NF_vICMS'];
            $vlicmsOrigem =  $vlTotalsemipi*$icms/100;

            $picms = $row["NF_pICMS"];
            if( $picms == 0){
                if($row['NFE_CFOP'] == 5102){
                $picms = 18;
                }else{
               // $picms = 12;
                }
            }
           // 
            $mva = $row['ncmmva_mva'];
           if($picms < 18){
          
          
            //  $vlicmsOrigem = $vlTotalsemipi*(12/100); // VALOR DO ICMS NO PR 4,22
              $vlcomipi = ($row['NFE_TOTALITEM']); // VALOR ipi 36,12
          
              $vlmva= ($vlcomipi*($row['ncmmva_mva']/100))  ; // VALOR ICMS ST 13,558
              $BASESTR =$vlcomipi+ $vlmva+$vlicmsOrigem;
              $vlricmsDestino =  $BASESTR*(18/100);   //9,69    
              $vlicmsDiferenca  = $vlricmsDestino-$vlicmsOrigem;
              if($picms <= 4){
                  $vlicmsDiferenca = 0;
                }
          
          }else{
          $vlcomipi = 0;
          $vlrbase  = 0;
          $vlicmsOrigem = 0;
          $BASESTR = 0;
          $vlicmsDestino  = 0;
          $vlicmsDiferenca= 0;
          }
          
          
          
          //  $_itemlinha = $row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".$row['NFE_IPI'].";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
          $_itemlinha = $FORNECEDOR.";".$NUMERO_NFE.";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
              fwrite($fp,$_itemlinha."\r\n");
           
                        }
                        fclose($fp);           
     } else { 
      //gera com fornecedor
      $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
      $_itemlinha = "Fornecedor;Codigo;Descrição;cfop;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
      fwrite($fp,$_itemlinha."\r\n");
      
      $_sql2 = "SELECT NFE_NRO,NFE_FORNEC,NFE_ID,NFE_TOTALNF,NOME
      FROM ".$_SESSION['BASE'].".nota_ent_base    
      LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE   = NFE_FORNEC             
      WHERE  ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero2 
      GROUP BY NFE_NRO,NFE_FORNEC,NFE_ID,NFE_TOTALNF,NOME";
      $statement2 = $pdo->query("$_sql2");
      $retorno2 = $statement2->fetchAll();
      foreach ($retorno2 as $row2) {
          $NUMERO_NFE = $row2['NFE_NRO'];
          $VALORNF = number_format($row2['NFE_TOTALNF'], 2, ',', '.');
          $FORNECEDOR = $row2['NOME'];
          $IDNFE = $row2['NFE_ID'] ;

      

      $_sql = "SELECT nota_ent_item.NFE_NRO,fabricante.NOME,nota_ent_item.NFE_CFOP,NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,fabricante.UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
      FROM ".$_SESSION['BASE'].".nota_ent_base
      INNER JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
      LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
      LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal  
      LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE   = nota_ent_base.NFE_FORNEC  
      LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC            
      WHERE  NFE_IDG = ''  AND  NFE_IDBASE = '$IDNFE' and nota_ent_item.NFE_CFOP <> '6949' and  nota_ent_item.NFE_CFOP <> '6910' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero";
      $statement = $pdo->query("$_sql");
      if($statement->rowCount() > 0){
          $_itemlinha = "$NUMERO_NFE - $FORNECEDOR";
          fwrite($fp,$_itemlinha."\r\n");
          $_itemlinha = "$VALORNF";
          fwrite($fp,$_itemlinha."\r\n");

     
      $retorno = $statement->fetchAll();       
      foreach ($retorno as $row) {            
         // $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi            
         $vlsemipi = $row['NF_CUSTO_ORIG']; // VALOR ipi
         if(  $vlsemipi == 0) {
          $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi
         }
          $vlTotalsemipi = $vlsemipi*$row['NFE_QTDADE'] ;  //35,20
          //$vlicmsOrigem = $row['NF_vICMS'];
          $vlicmsOrigem =  $vlTotalsemipi*$icms/100;
          $picms = $row["NF_pICMS"];
          if( $picms == 0){
              if($row['NFE_CFOP'] == 5102){
              $picms = 18;
              }else{
             // $picms = 12;
              }
          }
         // $picms = $row["NF_pICMS"];
          $mva = $row['ncmmva_mva'];
         if($picms < 18){
        
        
          //  $vlicmsOrigem = $vlTotalsemipi*(12/100); // VALOR DO ICMS NO PR 4,22
            $vlcomipi = ($row['NFE_TOTALITEM']); // VALOR ipi 36,12
        
            $vlmva= ($vlcomipi*($row['ncmmva_mva']/100))  ; // VALOR ICMS ST 13,558
            $BASESTR =$vlcomipi+ $vlmva+$vlicmsOrigem;
            $vlricmsDestino =  $BASESTR*(18/100);   //9,69    
            $vlicmsDiferenca  = $vlricmsDestino-$vlicmsOrigem;
            if($picms <= 4){
                $vlicmsDiferenca = 0;
              }
        
        }else{
        $vlcomipi = 0;
        $vlrbase  = 0;
        $vlicmsOrigem = 0;
        $BASESTR = 0;
        $vlicmsDestino  = 0;
        $vlicmsDiferenca= 0;
        }
        
        
        
       
       // $_itemlinha = $FORNECEDOR.";".$NUMERO_NFE.";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
         //   fwrite($fp,$_itemlinha."\r\n");
            
    
       $_itemlinha = ";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
       
          fwrite($fp,$_itemlinha."\r\n");
      }
         
                      }
                      $_itemlinha = "";
                      fwrite($fp,$_itemlinha."\r\n");      

   }   
  
   fclose($fp);     
        }  
                  ?>
              <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarcomBase()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar C/ Substituição Tributária</button>
                  <?php

} catch (PDOException $e) {
  ?>
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body" id="imagem-carregando">
              <h2><?="Erro: " . $e->getMessage()?></h2>
          </div>
      </div>
  </div>
  <?php
}




$arquivo = $tiponf."_".$nomearquivo.'.csv';

if( file_exists($arquivo_caminho)){ 
?><a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
<?php
}else{
  echo "Sem registros nesse periodo";
}
	
			