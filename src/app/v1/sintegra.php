<?php 
include("../../api/config/conexaobase.php");


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
            case "6401": //saida 5405 
                        //saida 6108  pessoa fisica  
                        //saida 6403  pessoa juridica
                $valor = '2403'; //entrada 
                break;
            case "6101":
                $valor = '2102'; 
                //5102
                // 6102
                // 6108
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
            case "6353":
                  $valor = '2353';
                  break;        
            case "5353":
                 $valor = '1353';
                    break;  
            case "5102":
                  $valor = '1102'; 
                   //saida 5102   6102 
                   //6108 não contribuinte pj       
            case "5405":
                     $valor = '1403'; 
                     //saida 5405   6403 
                     //6108 não contribuinte
                  break;     

        }  
        
        if($valor == "") {
            
          if(substr($CFOP,0,1) == '5'){
              $_ix = "1";
          }else{
              $_ix = "2";
          }
              $valor  = $_ix.substr($CFOP,-3);        
          }
                   
      }else{
          $valor  = "1".substr($CFOP,-3);//1102; 
      }

    return $valor;
}




$servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
$user_conect = 'admin';
$senha = '';
$banco_conect = '9029_maqservice';
$_SESSION['BASE'] = $banco_conect;
$_SESSION['CODIGOCLI'] = '9000';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
           
  $tiponf = "SINTEGRA" ;


 if($_parametros['nf-inicial'] == '') {
     $_parametros['nf-inicial'] = date('Y-m-d');
     $_parametros['nf-final'] = date('Y-m-d');
 } 
 
 
if($_parametros['_numeronf'] != '') {
 $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
}

$_parametros['nf-inicial'] = '2024-05-01';
$_parametros['nf-final'] = '2024-05-31';

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


$dir = "docs/".$_SESSION['CODIGOCLI'];

$arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/SINTEGRA".$Idchave.".txt";

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

 
     //REGISTRO 10
     //10195117160001813674185290013 FUNCIONAL ASSISTENCIA TECNICA EM ELJuiz de Fora                  MG32321565972023020120230228331
     $_sql = "SELECT empresa_contatosSintegra,empresa_cnpj,empresa_inscricao,empresa_razaosocial,empresa_cidade,empresa_telefone,empresa_uf,
      empresa_bairro,empresa_endereco,empresa_numero,empresa_complemento,CEP
     FROM ".$_SESSION['BASE'].".empresa limit 1";  
     $consultaMovRequisicao = "$_sql";
     $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     while($row = mysqli_fetch_array($mov))									 
         {
         $CNPJ  = str_pad($row['empresa_cnpj'], 14 , ' ' , STR_PAD_RIGHT); 
         $INSC_ESTADUAL  = str_pad($row['empresa_inscricao'], 14 , ' ' , STR_PAD_RIGHT); 
         $RAZAO_SOCIAL_CONTRIBUINTE = str_pad(substr($row["empresa_razaosocial"],0,35), 35 , ' ' , STR_PAD_RIGHT);
         
         $MUNICIPIO = str_pad($row['empresa_cidade'], 30 , ' ' , STR_PAD_RIGHT);
         $UF =  str_pad($row['empresa_uf'],2 , ' ' , STR_PAD_RIGHT);
         $UF_EMPRESA = $UF;
         $FAX = str_pad($row['empresa_telefone'], 10 , '0' , STR_PAD_RIGHT);
         $DATA_INICIAL = $Aano.$Ames.$Adia;
         $DATA_FINAL = $AanoFinal.$AmesFinal.$AdiaFinal;
        // $COD_IDENTIFICACAO = "8";
         $COD_CONVENIO = "3";
         $COD_NATUREZA = "3";
         $COD_FINALIDADE = "1";
   
     $TIPO = "10";    
     $linha = "10".$CNPJ.$INSC_ESTADUAL.$RAZAO_SOCIAL_CONTRIBUINTE.$MUNICIPIO.$UF.$FAX.$DATA_INICIAL.$DATA_FINAL.$COD_IDENTIFICACAO.$COD_CONVENIO.$COD_NATUREZA.$COD_FINALIDADE."\r\n";

     //REGISTRO 11

     $TIPO = "11";
     $ENDERECO =  str_pad($row['empresa_endereco'], 34 , ' ' , STR_PAD_RIGHT); 
     $NUMERO_ENDER  = str_pad($row['empresa_numero'], 5 , '0' , STR_PAD_RIGHT); 
     $COMPLEMENTO =  str_pad($row['empresa_complemento'], 22 , ' ' , STR_PAD_RIGHT); 
     $BAIRRO = str_pad($row['empresa_bairro'], 15 , ' ' , STR_PAD_RIGHT); 
     $CEP  = str_pad($row['CEP'], 8 , ' ' , STR_PAD_RIGHT); 
     $NOME_CONTATO = str_pad($row['empresa_contatosSintegra'], 28 , ' ' , STR_PAD_RIGHT); 
     $TELEFONE =  str_pad($row['empresa_telefone'], 12 , '0' , STR_PAD_RIGHT); 

     $linha = $linha."11".$ENDERECO.$NUMERO_ENDER.$COMPLEMENTO.$BAIRRO.$CEP.$NOME_CONTATO.$TELEFONE."\r\n";
     }

    //REGISTRO 50 - COMPRA
    $_sql = "SELECT nota_ent_item.NFE_CFOP as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE, date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
           SUM(NF_CUSTO_ORIG*NFE_QTDADE) AS NFE_TOTALNFITEM,SUM(NF_IPI_vIPI+NF_vICMSST+NF_FRETE) AS NFE_TOTALIMPOSTO,
            NFE_TOTALNF,NFE_BASEICM,NFE_TOTALICM
           FROM ".$_SESSION['BASE'].".nota_ent_base
           LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
           LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC
           LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao    
           WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
           and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353'  
           OR nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
           and nota_ent_item.NFE_CFOP IS NULL and cfop.NAT_CODIGO <> 2353 and cfop.NAT_CODIGO <> '1353' 
           group by nota_ent_item.NFE_CFOP,NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";
    $consultaMovRequisicao = "$_sql";
  

    $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
    while($row = mysqli_fetch_array($mov))									 
        {
            $TIPO = "50";
            //  $CODIGO_PRODUTO = $row['NFE_CODIGO'];
              $CFOP = trim($row['NFE_CFOP']);
             
              if($CFOP == "0" or $CFOP == "") {
                $CFOP = $row['NAT_CODIGO'];
              }
           
           
              $CFOP = CFOP($CFOP);

             $totalnf = $row["NFE_TOTALNFITEM"]+$row["NFE_TOTALIMPOSTO"];

           //  echo $row['NFE_NRO']."|".$row["NFE_TOTALNFITEM"]."+".$row["NFE_TOTALIMPOSTO"]." <br>";
             //$totalnf = $row["NFE_TOTALNF"];
                                   
              if($totalnf == 0 OR $totalnf  == ""){
                $totalnf = $row["NFE_TOTALNF"];
              }
            
  
        $INSCR_ESTADUAL_A = $row['INSCR_ESTADUAL'];
       
       if(trim($INSCR_ESTADUAL_A) == ""){
            $INSCR_ESTADUAL_A = "ISENTO";
        }

        //telefonia
        $tipo_modelo  = 55;
        if($CFOP >= '1300' and  $CFOP <= '1306'){
          $tipo_modelo = '22';
        }

        //TELECOMUNICAAO
        if($CFOP == '1303' and  $CFOP <= '1303'){
          $tipo_modelo = '21';
        }

        //tranportaDORA
        if($CFOP == '1353' OR  $CFOP == '2353'){
          $tipo_modelo = '57';
        }

        //ENERGIA
        if($CFOP == '1253' ){
          $tipo_modelo = '06';
        }

        $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ']), 14 , '0' , STR_PAD_LEFT);
        $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
        $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
        $UF = str_pad($row['UF'] , 2, ' ' , STR_PAD_RIGHT);
        $MODELO_NFE = str_pad($tipo_modelo , 2, '0' , STR_PAD_RIGHT);
        $SERIE = Str_pad($row['NFE_SERIE']  , 3 , ' ' , STR_PAD_RIGHT);
        $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
        $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
        $EMITENTE = "T";
        $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($totalnf, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
     
      //28/06  $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
          $BASE_CALC_ICMS = str_pad(0  , 13 , '0' , STR_PAD_LEFT);
      //28/06  $VALOR_ICMS = str_pad(str_replace(".","",number_format($row["NFE_TOTALICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
          $VALOR_ICMS = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
         $VALOR_ISENTO = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
        $VALOR_OUTRO = str_pad(str_replace(".","",number_format($totalnf, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
     
        $ALIQUOTA_ICMS = str_pad("0"  ,4 , '0' , STR_PAD_LEFT);
        $SITUACAO_NFE = str_pad("N" , 1 , ' ' , STR_PAD_LEFT);   

        $linha = $linha."50".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTO.$VALOR_OUTRO.$ALIQUOTA_ICMS.$SITUACAO_NFE."\r\n";

      
        $reg =  $reg + 1;
        
       
    }

     //REGISTRO 50 - VENDAS 
     $_sql = "SELECT  date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao,
     0 as NFE_BASEICM, 0 as NFE_TOTALICM,
     nfed_numeronf,nfed_cfop,nfed_totalnota,nfed_cpfcnpj,nfed_ie,nfed_dUF
     FROM ".$_SESSION['BASE'].".NFE_DADOS      
     WHERE nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0 
     and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
     AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' ";
     $consultaMovRequisicao = "$_sql";
     $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
  
     while($row = mysqli_fetch_array($mov))									 
         {
         $TIPO = "50";
       //  $CODIGO_PRODUTO = $row['NFE_CODIGO'];
         $CFOP = $row['nfed_cfop'];            
  
         $INSCR_ESTADUAL_A = $row['nfed_ie'];
       
        if(trim($INSCR_ESTADUAL_A) == ""){
             $INSCR_ESTADUAL_A = "ISENTO";
         }

          //telefonia
        $tipo_modelo  = 55;
         //telefonia
     
         if($CFOP >= '1300' and  $CFOP <= '1306'){
           $tipo_modelo = '22';
         }
           //TELECOMUNICAAO
        if($CFOP == '1303' and  $CFOP <= '1303'){
          $tipo_modelo = '21';
        }
 
         //tranportaDORA
         if($CFOP == '1353' OR  $CFOP == '2353'){
           $tipo_modelo = '57';
         }
 
         //ENERGIA
         if($CFOP == '1253' ){
           $tipo_modelo = '06';
         }
 
         $CNPJ_A  = str_pad(LimpaVariavelDoc($row['nfed_cpfcnpj']), 14 , '0' , STR_PAD_LEFT);
         $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
         $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
         $UF = str_pad($row['nfed_dUF'], 2, ' ' , STR_PAD_RIGHT);
         $MODELO_NFE = str_pad($tipo_modelo , 2, '0' , STR_PAD_RIGHT);
         $SERIE = Str_pad("2"  , 3 , ' ' , STR_PAD_RIGHT);
         $NUMERO_NFE = Str_pad(substr($row['nfed_numeronf'],-6)  , 6 , '0' , STR_PAD_LEFT);
         $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
         $EMITENTE = "P";
         $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($row["nfed_totalnota"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
      
        //28/06 $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
        //28/06 $VALOR_ICMS = str_pad(str_replace(".","",number_format($row["NFE_TOTALICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
         $BASE_CALC_ICMS = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
         $VALOR_ICMS = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
         $VALOR_ISENTO = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
         $VALOR_OUTRO = str_pad(str_replace(".","",number_format($row["nfed_totalnota"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
         $ALIQUOTA_ICMS = str_pad("0"  ,4 , '0' , STR_PAD_LEFT);
         $SITUACAO_NFE = str_pad("N" , 1 , ' ' , STR_PAD_RIGHT);
        
 
         $linha = $linha."50".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTO.$VALOR_OUTRO.$ALIQUOTA_ICMS.$SITUACAO_NFE."\r\n";
 
       
         $reg =  $reg + 1;
         
        
     }


    if( $reg > 0) { 
     $regfinal =  $regfinal + $reg;
     $TOTAL_TIPO = str_pad("50" , 2 , '0' , STR_PAD_RIGHT);
     $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
     $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
     $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
   }
   $reg  = 0;

    //REGISTRO 51//REGISTRO 51
     /*
 
    $_sql = "SELECT  nota_ent_item.NFE_CFOP as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_TOTALIPI, date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
    SUM(NFE_VLRUNI*NFE_QTDADE) AS NFE_TOTALNFITEM,NFE_BASEICM,NFE_TOTALICM,NFE_TOTALNF
    FROM ".$_SESSION['BASE'].".nota_ent_base
    LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  =nota_ent_base. NFE_FORNEC
    LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
    LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao
    WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
    and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353' 
    AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
    AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307' 
    AND nota_ent_item.NFE_CFOP <> '1308' 
    group by nota_ent_item.NFE_CFOP,NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";
//echo $_sql;
    //OR
    //nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
    //AND nota_ent_item.NFE_CFOP > '1400' and cfop.NAT_CODIGO > '1400' 

    $consultaMovRequisicao = "$_sql";
     $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     while($row = mysqli_fetch_array($mov))									 
         {

        
          //  $CFOP = $row['NAT_CODIGO'];
          $CFOP = $row['NFE_CFOP'];
            if($CFOP == "0" or $CFOP == "") {
            $CFOP = $row['NAT_CODIGO'];
          }
        
          $CFOP = CFOP($CFOP);

       $totalnf = $row["NFE_TOTALNFITEM"];
      //  $totalnf = $row["NFE_TOTALNF"];
          if($totalnf == 0 OR $totalnf  == ""){
            $totalnf = $row["NFE_TOTALNF"];
          }

            
        $TIPO = "51";
        $INSCR_ESTADUAL_A = $row['INSCR_ESTADUAL'];
       if(trim($INSCR_ESTADUAL_A) == ""){
  
            $INSCR_ESTADUAL_A = "ISENTO";
        }
        $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
        $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
      
        $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
        $UF = str_pad($row['UF'] , 2, ' ' , STR_PAD_RIGHT);
       
        $SERIE = Str_pad($row['NFE_SERIE'] , 3 , ' ' , STR_PAD_RIGHT);
        $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
        $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
        $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($totalnf, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
        
        $VALOR_IPI = str_pad(str_replace(".","",number_format($row['NFE_TOTALIPI'], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
        $VALOR_ISENTO = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
        $VALOR_OUTRO = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
        $BRANCO20 =  str_pad(" "  , 20, ' ' , STR_PAD_LEFT);          
        $SITUACAO_NFE = Str_pad("N"  , 1 , '0' , STR_PAD_LEFT);
      
        $linha = $linha."51".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$SERIE.$NUMERO_NFE.$CFOP.$VALOR_TOTAL.$VALOR_IPI.$VALOR_ISENTO.$VALOR_OUTRO.$BRANCO20.$SITUACAO_NFE."\r\n";
        $reg =  $reg + 1;
    }
    
    if( $reg > 0) { 
     $regfinal =  $regfinal + $reg;
     $TOTAL_TIPO = str_pad("51" , 2 , '0' , STR_PAD_RIGHT);
     $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
     $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
     $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
   }
   */
   $reg  = 0;
        //REGISTRO 53
        $_sql = "SELECT nota_ent_item.NFE_CFOP as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_TOTALIPI, date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
                SUM(NF_CUSTO_ORIG*NFE_QTDADE) AS NFE_TOTALNFITEM, NFE_TOTALNF,NFE_BASEICM,NFE_TOTALICM,SUM(NFE_TOTALFRETE) AS VALOR_DESPESA,
                SUM(NF_vICMSST) AS VALOR_ST, SUM(NF_vBCST) AS VALOR_BASEST
                FROM ".$_SESSION['BASE'].".nota_ent_base
                LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = NFE_FORNEC
                LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
                LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao
                WHERE nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
                and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353' 
               AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
               AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307' 
               AND nota_ent_item.NFE_CFOP <> '1308'                       
                group by nota_ent_item.NFE_CFOP,NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";
                // OR nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
                //AND nota_ent_item.NFE_CFOP > '1400' and cfop.NAT_CODIGO > '1400'     
       $consultaMovRequisicao = "$_sql";
       $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     
       while($row = mysqli_fetch_array($mov))									 
           {
  
          //  $CFOP = $row['NAT_CODIGO'];
          $CFOP = $row['NFE_CFOP'];
            if($CFOP == "0" or $CFOP == "") {
            $CFOP = $row['NAT_CODIGO'];
          }
        
          $CFOP = CFOP($CFOP);
         // $totalnf = $row["NFE_TOTALNFITEM"];
         $totalnf = $row["NFE_TOTALNF"];
          if($totalnf == 0 OR $totalnf  == ""){
            $totalnf = $row["NFE_TOTALNF"];
          }

        $TIPO = "53";
        $INSCR_ESTADUAL_A = $row['INSCR_ESTADUAL'];
       if(trim($INSCR_ESTADUAL_A) == ""){
            $INSCR_ESTADUAL_A = "ISENTO";
        }

          //telefonia
          $tipo_modelo  = 55;
       //   if($CFOP >= '1300' and  $CFOP <= '1306'){
        //    $tipo_modelo = '22';
        //  }
        
        $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
       $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
      
        $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
        $UF = str_pad($row['UF'] , 2, ' ' , STR_PAD_RIGHT);
        $MODELO_NFE = str_pad($tipo_modelo , 2, '0' , STR_PAD_RIGHT);
        $SERIE = Str_pad($row['NFE_SERIE']  , 3 , ' ' , STR_PAD_RIGHT);
        $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
        $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
        $EMITENTE = "T";

       // $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
                   
       
        $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["VALOR_BASEST"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
        $VALOR_ICMS_RETIRO = str_pad(str_replace(".","",number_format($row["VALOR_ST"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
       // $VALOR_DESPESA = Str_pad(0, 13 , '0' , STR_PAD_LEFT);
       $VALOR_DESPESA = str_pad(str_replace(".","",number_format($row["VALOR_DESPESA"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
      
        $SITUACAO_NFE = Str_pad("N"  , 1 , '0' , STR_PAD_LEFT);
       // $CODIGO_ANTECIPACAO = Str_pad($CODIGO_ANTECIPACAO  , 1 , '0' , STR_PAD_RIGHT);
        $BRANCOS29 = str_pad(" "  , 30, ' ' , STR_PAD_LEFT);   
        

        $linha = $linha."53".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$BASE_CALC_ICMS.$VALOR_ICMS_RETIRO.$VALOR_DESPESA.$SITUACAO_NFE.$BRANCOS29."\r\n";
        $reg =  $reg + 1;
       }
        //REGISTRO 53 vendas
        $_sql = "SELECT nfed_id, date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao,
                0 as NFE_BASEICM, 0 as NFE_TOTALICM,
                nfed_numeronf,nfed_cfop,nfed_totalnota,nfed_cpfcnpj,nfed_ie,nfed_dUF
                FROM ".$_SESSION['BASE'].".NFE_DADOS      
                WHERE  nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0 
                and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
                AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";
       $consultaMovRequisicao = "$_sql";
       $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     
       while($row = mysqli_fetch_array($mov))									 
           {
  
            $_sqlITEM = "SELECT SUM(nfe_itensvlrOutros) AS VALOROUTROS
            FROM ".$_SESSION['BASE'].".NFE_ITENS      
            WHERE id_nfedados = '".$row['nfed_id']."'";
        
            $movITEM = mysqli_query($mysqli, $_sqlITEM) or die(mysqli_error($mysqli));
            $VALOR_DESPESA = 0;
            while($rowITEM = mysqli_fetch_array($movITEM))									 
                {
                    $VALOR_DESPESA  = $rowITEM ["VALOROUTROS"];
                }

   

        $TIPO = "53"; //vendas

        $INSCR_ESTADUAL_A = $row['nfed_ie'];
        if(trim($INSCR_ESTADUAL_A) == ""){
            $INSCR_ESTADUAL_A = "ISENTO";
        }

     
        $CNPJ_A  = str_pad(LimpaVariavelDoc($row['nfed_cpfcnpj']), 14 , '0' , STR_PAD_LEFT);
        $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc( $INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
        $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
        $UF = str_pad($row['nfed_dUF'] , 2, ' ' , STR_PAD_RIGHT);
        $MODELO_NFE = str_pad('55' , 2, '0' , STR_PAD_RIGHT);
        $SERIE = Str_pad(2  , 3 , ' ' , STR_PAD_RIGHT);
        $NUMERO_NFE = Str_pad(substr($row['nfed_numeronf'],-6)  , 6 , '0' , STR_PAD_LEFT);
        $CFOP = Str_pad($row['nfed_cfop'] , 4 , '0' , STR_PAD_RIGHT);
        $EMITENTE = "P";
        $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);              
        $VALOR_ICMS_RETIRO = Str_pad(0 , 13 , '0' , STR_PAD_LEFT);
        
        $VALOR_DESPESA = str_pad(str_replace(".","",number_format($VALOR_DESPESA, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);              
        $SITUACAO_NFE = Str_pad("N"  , 1 , '0' , STR_PAD_LEFT);
       // $CODIGO_ANTECIPACAO = Str_pad($CODIGO_ANTECIPACAO  , 1 , '0' , STR_PAD_RIGHT);
        $BRANCOS29 = str_pad(" "  , 30, ' ' , STR_PAD_LEFT);   
        

        $linha = $linha."53".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$BASE_CALC_ICMS.$VALOR_ICMS_RETIRO.$VALOR_DESPESA.$SITUACAO_NFE.$BRANCOS29."\r\n";
        $reg =  $reg + 1;
       }
       if( $reg > 0) { 
         $regfinal =  $regfinal + $reg;
         $TOTAL_TIPO = str_pad("53" , 2 , '0' , STR_PAD_RIGHT);
         $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
         $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
         $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
       }
       $reg  = 0;
     
     //registro 54
     $_sql = "SELECT CNPJ,UF,NF_SKU,NFE_SERIE,nota_ent_base.NFE_NRO as NUMERONF,nota_ent_item.NFE_CFOP as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO ,NFE_ITEM,NFE_CODIGO,
     NF_ICMS_ST,
      NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NF_IPI_vIPI,NF_CUSTO_ORIG,
      NF_vICMSST AS VALOR_ST, NF_vBCST AS VALOR_BASEST,nota_ent_item.NF_FRETE AS FRETE
      FROM ".$_SESSION['BASE'].".nota_ent_base
      LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = NFE_FORNEC
      LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
      LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao
      WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
      and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353' 
      AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
      AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307' 
      AND nota_ent_item.NFE_CFOP <> '1308' 
     
";



        $consultaMovRequisicao = "$_sql";
        // OR nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
      //AND nota_ent_item.NFE_CFOP > '1400' and cfop.NAT_CODIGO > '1400' 

        $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

        while($row = mysqli_fetch_array($mov))									 
            {
 
        
     $CODIGO_PRODUTO = $row['NFE_CODIGO'];
      //REGISTRO 54
            
      $CODIGO_PRODUTO = $row['NFE_CODIGO'];
      //  $CFOP = $row['NAT_CODIGO'];
      $CFOP = $row['NFE_CFOP'];
        if($CFOP == "0" or $CFOP == "") {
        $CFOP = $row['NAT_CODIGO'];
      }
    
    $CFOP = CFOP($CFOP);
    $qtde = explode(".",$row['NFE_QTDADE']);
    $NUMERO_ITEMv =$row["NFE_ITEM"];
    if($NUMERO_ITEMv == 0 ) {
        $NUMERO_ITEM  = $NUMERO_ITEM + 1;
    }else{
        $NUMERO_ITEM =$row["NFE_ITEM"];
    }

     $TIPO = "54";
    
     $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
   //  $CNPJ  = str_pad($CNPJ  , 14 , '0' , STR_PAD_RIGHT);
     $MODELO = 55;

         //telefonia
         $tipo_modelo  = 55;
       //  if($CFOP >= '1300' and  $CFOP <= '1306'){
       //    $tipo_modelo = '22';
       //  }

     $MODELO_NFE = str_pad($tipo_modelo , 2 , '0' , STR_PAD_RIGHT);
     $SERIE_NFE = str_pad($row['NFE_SERIE'], 3 , ' ' , STR_PAD_RIGHT);
     $NUMERO_NFE = str_pad(substr($row['NUMERONF'],-6) , 6 , '0' , STR_PAD_LEFT);
     $CFOP = str_pad($CFOP, 4 , '0' , STR_PAD_RIGHT);
     $CST_SIT_TRIBUTARIA = Str_pad(substr($row['NF_ICMS_ST'],-3)  , 3 , '0' , STR_PAD_RIGHT);
     $NUMERO_ITEM = Str_pad($NUMERO_ITEM  , 3 , '0' , STR_PAD_LEFT);
     $CODIGO_PRODUTO = Str_pad($CODIGO_PRODUTO, 14 , ' ' , STR_PAD_RIGHT);
     $QTDE_PRODUTO = Str_pad($qtde[0] , 8 , '0' , STR_PAD_LEFT).Str_pad($qtde[1] , 3 , '0' , STR_PAD_RIGHT);
    // $VALOR_PRODUTO = str_pad(str_replace(".","",)  , 12 , '0' , STR_PAD_LEFT);
    // $VALOR_PRODUTO =str_replace(".","",number_format(($row["NFE_VLRUNI"]+$row["NF_IPI_vIPI"])*$row['NFE_QTDADE'], 2, '.', '')); 
    // $VALOR_PRODUTO = number_format(($row["NF_CUSTO_ORIG"]*$row['NFE_QTDADE'])+$row["NF_IPI_vIPI"], 2, '.', ''); atlerado 30/11
     $VALOR_PRODUTO = number_format(($row["NF_CUSTO_ORIG"]*$row['NFE_QTDADE'])+$row["FRETE"]+$row["NF_IPI_vIPI"]+$row["VALOR_ST"], 2, '.', '');
     //echo  $NUMERO_NFE.'-  '.$row["NF_CUSTO_ORIG"].'*'.$row["NFE_QTDADE"].')+'.$row["NF_IPI_vIPI"].'+'.$row["VALOR_ST"].', 2, )<Br>';
     $VALOR_PRODUTO =str_replace(".","",$VALOR_PRODUTO); 
     $VALOR_PRODUTO  = str_pad( $VALOR_PRODUTO, 12 , '0' , STR_PAD_LEFT);
     $VALOR_DESCONTO =str_replace(".","",$row["NF_FRETE"]); 
     $VALOR_DESCONTO = Str_pad($VALOR_DESCONTO , 12 , '0' , STR_PAD_LEFT); //FRETE
     //Aproveitando o assunto, gostaríamos de solicitar, que a configuração do Sintegra seja realizada de forma os registros de entradas e saídas não contenham os dados de Base de Calculo de ICMS, Alíquota de ICMS e Valor de ICMS. Nem no Registro 50,  if(trim($INSCR_ESTADUAL_A) == ""){, 54
    
    // $BASE_ICMS  = str_pad(str_replace(".","",number_format($row["VALOR_BASEST"], 2, '.', ''))  , 12 , '0' , STR_PAD_LEFT);
    // $BASE_ICMS_SUBSTITUICAO = str_pad(str_replace(".","",number_format($row["VALOR_ST"], 2, '.', ''))  , 12 , '0' , STR_PAD_LEFT);
     $BASE_ICMS  = str_pad(0 , 12 , '0' , STR_PAD_LEFT);
     $BASE_ICMS_SUBSTITUICAO = str_pad(0 , 12 , '0' , STR_PAD_LEFT);
     $VALOR_IPI = Str_pad(0 , 12 , '0' , STR_PAD_LEFT);
     $ALIQUOTA_ICMS = Str_pad(0  , 4 , '0' , STR_PAD_LEFT);
     
     $linha =  $linha."54".$CNPJ_A.$MODELO_NFE.$SERIE_NFE.$NUMERO_NFE.$CFOP.$CST_SIT_TRIBUTARIA.$NUMERO_ITEM.$CODIGO_PRODUTO.$QTDE_PRODUTO.$VALOR_PRODUTO.$VALOR_DESCONTO.$BASE_ICMS.$BASE_ICMS_SUBSTITUICAO.$VALOR_IPI.$ALIQUOTA_ICMS."\r\n";
     $reg =  $reg + 1;
 }

   //registro 54 vendas
   $_sql = "SELECT nfed_id, date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao,
   0 as NFE_BASEICM, 0 as NFE_TOTALICM,
   nfed_numeronf,nfed_cfop,nfed_totalnota,
   quantidade,situacaotributario_nfeitens,codigoproduto_nfeitens,vlrunitario_nfeitens,nfed_cpfcnpj,nfed_ie,nfed_dUF
   FROM ".$_SESSION['BASE'].".NFE_DADOS   
   LEFT JOIN ".$_SESSION['BASE'].".NFE_ITENS ON id_nfedados  = nfed_id   
   WHERE  nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0
   and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
   AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' 
   ORDER BY nfed_id asc";
    
        
                        $consultaMovRequisicao = "$_sql";

                        $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

                        while($row = mysqli_fetch_array($mov))									 
                            {

                                if($row['nfed_numeronf'] != $_nf){
                                    $NUMERO_ITEM =1;
                                }else{
                                    $NUMERO_ITEM  = $NUMERO_ITEM + 1;
                                }
                        
                                $CFOP = $row['nfed_cfop'];

                        $qtde = explode(".",$row['quantidade']);
                 
                        $TIPO = "54";

                        $CNPJ_A  = str_pad(LimpaVariavelDoc($row['nfed_cpfcnpj'])  , 14 , '0' , STR_PAD_LEFT);
                        //  $CNPJ  = str_pad($CNPJ  , 14 , '0' , STR_PAD_RIGHT);
                        $MODELO = 55;
                            //telefonia
                        $tipo_modelo  = 55;
                     //   if($CFOP >= '1300' and  $CFOP <= '1306'){
                      //    $tipo_modelo = '22';
                      //  }
                         $MODELO_NFE = str_pad($tipo_modelo , 2 , '0' , STR_PAD_RIGHT);
                        $SERIE_NFE = str_pad(2, 3 , ' ' , STR_PAD_RIGHT);
                        $NUMERO_NFE = str_pad(substr($row['nfed_numeronf'],-6) , 6 , '0' , STR_PAD_LEFT);
                        $CFOP = str_pad($CFOP, 4 , '0' , STR_PAD_RIGHT);
                        $CST_SIT_TRIBUTARIA = Str_pad(substr($row['situacaotributario_nfeitens'],0,-3) , 3 , '0' , STR_PAD_RIGHT);
                        $NUMERO_ITEM = Str_pad($NUMERO_ITEM  , 3 , '0' , STR_PAD_LEFT);
                        $CODIGO_PRODUTO = Str_pad($row['codigoproduto_nfeitens'], 14 , ' ' , STR_PAD_RIGHT);
                        $QTDE_PRODUTO = Str_pad($qtde[0] , 8 , '0' , STR_PAD_LEFT).Str_pad($qtde[1] , 3 , '0' , STR_PAD_RIGHT);
                        // $VALOR_PRODUTO = str_pad(str_replace(".","",)  , 12 , '0' , STR_PAD_LEFT);
                        $VALOR_PRODUTO =str_replace(".","",number_format($row["vlrunitario_nfeitens"]*$row['quantidade'], 2, '.', '')); 
                        $VALOR_PRODUTO  = str_pad( $VALOR_PRODUTO, 12 , '0' , STR_PAD_LEFT);
                        $VALOR_DESCONTO = Str_pad(0  , 12 , '0' , STR_PAD_LEFT);
                        //Aproveitando o assunto, gostaríamos de solicitar, que a configuração do Sintegra seja realizada de forma os registros de entradas e saídas não contenham os dados de Base de Calculo de ICMS, Alíquota de ICMS e Valor de ICMS. Nem no Registro 50, 70, 54
                        $BASE_ICMS = Str_pad(0  , 12 , '0' , STR_PAD_LEFT);
                        $BASE_ICMS_SUBSTITUICAO = Str_pad(0  , 12 , '0' , STR_PAD_LEFT);
                        $VALOR_IPI = Str_pad(0 , 12 , '0' , STR_PAD_LEFT);
                        $ALIQUOTA_ICMS = Str_pad(0  , 4 , '0' , STR_PAD_LEFT);

                        $linha =  $linha."54".$CNPJ_A.$MODELO_NFE.$SERIE_NFE.$NUMERO_NFE.$CFOP.$CST_SIT_TRIBUTARIA.$NUMERO_ITEM.$CODIGO_PRODUTO.$QTDE_PRODUTO.$VALOR_PRODUTO.$VALOR_DESCONTO.$BASE_ICMS.$BASE_ICMS_SUBSTITUICAO.$VALOR_IPI.$ALIQUOTA_ICMS."\r\n";
                        $reg =  $reg + 1;

                        $_nf = $row['nfed_numeronf'];
                        }
 if( $reg > 0) { 
     $regfinal =  $regfinal + $reg;
   $TOTAL_TIPO = str_pad("54" , 2 , '0' , STR_PAD_RIGHT);
   $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
   $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
   $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
 }
 $reg  = 0;

  //REGISTRO 60R
  //Resumo Mensal (60R): Registro de mercadoria/produto ou serviço processado em EC

  /*   $TIPO = "60";
     $SUBTIPO  = "R";
     $MES_ANO = str_pad($MES_ANO , 6 , '0' , STR_PAD_RIGHT);
     $CODIGO_PRODUTO = Str_pad($CODIGO_PRODUTO  , 14 , ' ' , STR_PAD_RIGHT);
     $CODIGO_SERVICO = Str_pad($CODIGO_SERVICO  , 14 , ' ' , STR_PAD_RIGHT);
     $QTDE_PRODUTO = Str_pad($QTDE_PRODUTO  , 11 , '0' , STR_PAD_RIGHT);
     $VALOR_PRODUTO = Str_pad($VALOR_PRODUTO  , 16 , '0' , STR_PAD_RIGHT);
     $VALOR_PRODUTO_BRUTO = Str_pad($VALOR_PRODUTO_BRUTO  , 16 , '0' , STR_PAD_RIGHT);
     $VALOR_PRODUTO_ACUMULADO = Str_pad($VALOR_PRODUTO_ACUMULADO  , 16 , '0' , STR_PAD_RIGHT);
     $BASE_ICMS = Str_pad($VALOR_ICMS  , 16 , '0' , STR_PAD_RIGHT);
     $SITUACAO_TRIBUTARIA_ALIQ = Str_pad($SITUACAO_TRIBUTARIA_ALIQ  , 4 , '0' , STR_PAD_RIGHT);
     $SITUACAO_TRIBUTARIA_ALIQ_PROD = Str_pad($SITUACAO_TRIBUTARIA_ALIQ_PROD  , 4 , '0' , STR_PAD_RIGHT);
     $BRANCOS = Str_pad($BRANCOS  , 53 , ' ' , STR_PAD_RIGHT);


     $linha = "60R".$MES_ANO.$CODIGO_PRODUTO.$CODIGO_SERVICO.$QTDE_PRODUTO.$VALOR_PRODUTO.$VALOR_PRODUTO_BRUTO.$VALOR_PRODUTO_ACUMULADO.$BASE_ICMS.$SITUACAO_TRIBUTARIA_ALIQ.$SITUACAO_TRIBUTARIA_ALIQ_PROD.$BRANCOS."\r\n";
     */

//REGISTRO 61
//REGISTRO 61 - Documentos fiscais não emitidos por equipamento emissor de cupom fiscal

 $_sql = "Select nfed_serie,nfed_totalnota,nfed_basecalculo,nfed_numeronf,
 date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao from ".$_SESSION['BASE'].".NFE_DADOS
     where nfed_numeronf > 0 and nfed_cancelada = 0 and nfed_chave <> '' and nfed_modelo <> '55' and nfed_modelo <> '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' and nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";                          
     $consultaMovRequisicao = "$_sql";
     $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     while($row = mysqli_fetch_array($mov))									 
         {
     $TIPO = "61";
     $BRANCOS = Str_pad(''  , 14 , ' ' , STR_PAD_RIGHT);
     $BRANCOS1 = Str_pad(''  , 14 , ' ' , STR_PAD_RIGHT);
     $DATA_EMISSAO = str_pad($row['dtemissao'], 8, '0' , STR_PAD_RIGHT);
     $MODELO = Str_pad('65'  , 2 , '0' , STR_PAD_RIGHT);
     $SERIE = Str_pad("2" , 3 , ' ' , STR_PAD_RIGHT);
     $SUB_SERIE = Str_pad('' , 2 , ' ' , STR_PAD_RIGHT);            
     $NUMERO_INICIAL = Str_pad($row['nfed_numeronf'] ,6 , '0' , STR_PAD_LEFT);
     $NUMERO_FINAL = Str_pad($row['nfed_numeronf'] ,6 , '0' , STR_PAD_LEFT);
     $VALOR_TOTAL = str_pad(str_replace(".","",number_format($row["nfed_totalnota"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
     $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format(0, 2))  , 13 , '0' , STR_PAD_LEFT);
     $VALOR_ICMS =  str_pad(str_replace(".","",number_format(0, 2))  , 12 , '0' , STR_PAD_LEFT);
     $VALOR_ISENTA =  str_pad(str_replace(".","",number_format(0, 2))  , 13 , '0' , STR_PAD_LEFT);
     $VALOR_OUTRA = str_pad(str_replace(".","",number_format(0, 2))  , 13 , '0' , STR_PAD_LEFT);
     $ALIQUOTA_ICMS = Str_pad(0  , 4 , '0' , STR_PAD_LEFT);
     $BRANCOS3 = Str_pad(''  , 1 , ' ' , STR_PAD_RIGHT);

     $linha = $linha."61".$BRANCOS.$BRANCOS1.$DATA_EMISSAO.$MODELO.$SERIE.$SUB_SERIE.$NUMERO_INICIAL.$NUMERO_FINAL.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTA.$VALOR_OUTRA.$ALIQUOTA_ICMS.$BRANCOS3."\r\n";
     $reg =  $reg + 1;
 }



//REGISTRO 61R
$_sql = "Select CODIGO_ITEM,QUANTIDADE,Valor_unitario_desc,
date_format(nfed_dataautorizacao,'%m%Y') as dtemissao from ".$_SESSION['BASE'].".NFE_DADOS
 left join ".$_SESSION['BASE'].".saidaestoqueitem on NUMERO = nfed_pedido
 where nfed_numeronf > 0  and nfed_cancelada = 0 and nfed_chave <> '' and nfed_modelo <> '55' and nfed_modelo <> '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' and nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";                          
 $consultaMovRequisicao = "$_sql";
     $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     while($row = mysqli_fetch_array($mov))									 
         {
     $TIPO = "61R";
     $MES_ANO = Str_pad($row["dtemissao"]  , 6 , ' 0' , STR_PAD_RIGHT);
     $CODIGO_PRODUTO = Str_pad($row["CODIGO_ITEM"] , 14 , ' ' , STR_PAD_RIGHT);
     $QTDE_PRODUTO = str_pad($row["QUANTIDADE"]  , 13, '0' , STR_PAD_RIGHT);
     $VALOR_BRUTO_PRODUTO = str_pad(str_replace(".","",number_format($row['Valor_unitario_desc'], 2, '.', ''))  , 16 , '0' , STR_PAD_LEFT);
     $BASE_CALC_ICMS = Str_pad(0 , 16 , '0' , STR_PAD_LEFT);
     $ALIQUOTA_ICMS = Str_pad(0 , 4 , '0' , STR_PAD_LEFT);
     $BRANCOS = Str_pad(''  ,54 , ' ' , STR_PAD_RIGHT);

     if($row["CODIGO_ITEM"]== 1) { 
        $diverso = "1";
     }
     if(trim($CODIGO_PRODUTO)!= ""){

     $linha = $linha."61R".$MES_ANO.$CODIGO_PRODUTO.$QTDE_PRODUTO.$VALOR_BRUTO_PRODUTO.$BASE_CALC_ICMS.$ALIQUOTA_ICMS.$BRANCOS."\r\n";
    
     $reg =  $reg + 1;
    }
 }
 if( $reg > 0) { 
    $regfinal =  $regfinal + $reg;
  $TOTAL_TIPO = str_pad("61" , 2 , '0' , STR_PAD_RIGHT);
  $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
  $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
  $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
}
$reg  = 0;

//REGISTRO 70 transporte
$_sql = "SELECT cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,
 date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
NFE_TOTALNF,NFE_BASEICM,NFE_TOTALICM
FROM ".$_SESSION['BASE'].".nota_ent_base
LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC
LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao    
WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
and cfop.NAT_CODIGO  = '2353'  or
 nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' and
cfop.NAT_CODIGO ='1353'
group by NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";

$consultaMovRequisicao = "$_sql";
$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

while($row = mysqli_fetch_array($mov))									 
    {
    $TIPO = "70";
  //  $CODIGO_PRODUTO = $row['NFE_CODIGO'];
    $CFOP = $row['NAT_CODIGO'];
       

  // $CNPJ_A  = str_pad(LimpaVariavelDoc($CNPJ), 14 , '0' , STR_PAD_LEFT);
    $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
   // $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSC_ESTADUAL) , 14 , ' ' , STR_PAD_LEFT);
    $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($row['INSCR_ESTADUAL']) , 14 , ' ' , STR_PAD_RIGHT);
    $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
    $UF = str_pad($UF_EMPRESA , 2, ' ' , STR_PAD_RIGHT);
    $MODELO_NFE = str_pad('08' , 2, '0' , STR_PAD_RIGHT);
    $SERIE = Str_pad("U"  ,1 , ' ' , STR_PAD_RIGHT);
    $subSERIE = "  ";
   // $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
   $NUMERO_NFE = substr(Str_pad( $row['NFE_NRO'] , 6 , '0' , STR_PAD_LEFT),-6) ;
    $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
  
    $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($row["NFE_TOTALNF"], 2, '.', ''))  , 14 , '0' , STR_PAD_LEFT);
 
  
    $BASE_CALC_ICMS = str_pad("0"  , 14 , '0' , STR_PAD_LEFT);
    $VALOR_ICMS = str_pad("0"  , 13, '0' , STR_PAD_LEFT);
    $VALOR_ISENTO = str_pad("0"  , 14 , '0' , STR_PAD_LEFT);
    $VALOR_OUTRO = str_pad(str_replace(".","",number_format($row["NFE_TOTALNF"], 2, '.', ''))  , 14 , '0' , STR_PAD_LEFT);
    $ALIQUOTA_ICMS = str_pad("0"  ,4 , '0' , STR_PAD_LEFT);
    $SITUACAO_NFE = str_pad("N" , 1 , ' ' , STR_PAD_RIGHT);
    $MODALIDADEX = "1"; 
  
    $linha = $linha."70".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$subSERIE.$NUMERO_NFE.$CFOP.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTO.$VALOR_OUTRO.$MODALIDADEX.$SITUACAO_NFE."\r\n";

  
    $reg =  $reg + 1;
    
   
}


if( $reg > 0) { 
$regfinal =  $regfinal + $reg;
$TOTAL_TIPO = str_pad("70" , 2 , '0' , STR_PAD_RIGHT);
$TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
$_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
$_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
}
$reg  = 0;

//REGISTRO 75
$delete = "DELETE FROM ".$_SESSION['BASE'].".temp_sintegraitem ";
mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));



$_sql = "INSERT INTO ".$_SESSION['BASE'].".temp_sintegraitem (CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA)
SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA
 FROM ".$_SESSION['BASE'].".nota_ent_base
 LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
 left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = NFE_CODIGO
 WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' 
 AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
 AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
 AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307'
  AND nota_ent_item.NFE_CFOP <> '1308'    AND nota_ent_item.NFE_CFOP <> '' 
  OR nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' 
  AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59'  
 group by CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA";
 
mysqli_query($mysqli, $_sql) or die(mysqli_error($mysqli));


$_sql = "INSERT INTO ".$_SESSION['BASE'].".temp_sintegraitem (CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA) 
SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA
FROM ".$_SESSION['BASE'].".NFE_DADOS   
LEFT JOIN ".$_SESSION['BASE'].".NFE_ITENS ON id_nfedados  = nfed_id   
left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = codigoproduto_nfeitens
WHERE  nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0
and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' 
ORDER BY nfed_id asc";
mysqli_query($mysqli, $_sql) or die(mysqli_error($mysqli));

$_sql = "INSERT INTO ".$_SESSION['BASE'].".temp_sintegraitem (CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA) 
Select CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA from ".$_SESSION['BASE'].".NFE_DADOS
left join ".$_SESSION['BASE'].".saidaestoqueitem on NUMERO = nfed_pedido
left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = CODIGO_ITEM
where nfed_numeronf > 0  and nfed_cancelada = 0 and nfed_chave <> '' and nfed_modelo <> '55' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' and nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";
mysqli_query($mysqli, $_sql) or die(mysqli_error($mysqli));


$_sql = "SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA
FROM ".$_SESSION['BASE'].".temp_sintegraitem  
group by CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA";
$consultaMovRequisicao = "$_sql";
$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
while($row = mysqli_fetch_array($mov))									 
     {
 $TIPO = "75";
 $DATAINICIAL = $Aano.$Ames.$Adia;
 $DATAFINAL = $AanoFinal.$AmesFinal.$AdiaFinal;
 $CODIGO_PRODUTO = str_pad($row['CODIGO_FORNECEDOR'] , 14 , ' ' , STR_PAD_RIGHT);
 $NCM = str_pad($row['Cod_Class_Fiscal']   , 8 , ' ' , STR_PAD_RIGHT);
 $DESCRICAO = str_pad(substr(trim($row['DESCRICAO']),0,53)  , 53, ' ' , STR_PAD_RIGHT);
 $UN = str_pad($row['UNIDADE_MEDIDA']  , 6 , ' ' , STR_PAD_RIGHT);
 $ALIQUOTA_IPI = str_pad('0'  , 5 , '0' , STR_PAD_LEFT);
 $ALIQUOTA_ICMS = str_pad('0'  , 4 , '0' , STR_PAD_LEFT);
 $ALIQUOTA_REDUCAO_ICMS = str_pad('0'  , 5 , '0' , STR_PAD_LEFT);
 $BASE_CALC_ICMS_REDUCAO = str_pad('0'  , 13 , '0' , STR_PAD_LEFT);

      
 if($row['DESCRICAO'] != "") {
      
    $linha =  $linha."75".$DATAINICIAL.$DATA_FINAL.$CODIGO_PRODUTO.$NCM.$DESCRICAO.$UN.$ALIQUOTA_IPI.$ALIQUOTA_ICMS.$ALIQUOTA_REDUCAO_ICMS.$BASE_CALC_ICMS_REDUCAO."\r\n";

 $reg =  $reg + 1;
}
       }
       if( $reg > 0) { 
        if( $diverso != "") {
            $CODIGO_PRODUTO = str_pad("1" , 14 , ' ' , STR_PAD_RIGHT);
            $linha =  $linha."75".$DATAINICIAL.$DATA_FINAL.$CODIGO_PRODUTO.$NCM.$DESCRICAO.$UN.$ALIQUOTA_IPI.$ALIQUOTA_ICMS.$ALIQUOTA_REDUCAO_ICMS.$BASE_CALC_ICMS_REDUCAO."\r\n";
            $reg =  $reg + 1;

        }
         $regfinal =  $regfinal + $reg;
         $TOTAL_TIPO = str_pad("75" , 2 , '0' , STR_PAD_RIGHT);
         $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);

        $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
         $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
       }
       $reg  = 0;
//88
 $_sql = "SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA,Codigo_Barra
 FROM ".$_SESSION['BASE'].".nota_ent_base
 LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
 left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = NFE_CODIGO
 WHERE  CODIGO_FORNECEDOR <> '1' and nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' ";
 $consultaMovRequisicao = "$_sql";

 $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
 while($row = mysqli_fetch_array($mov))									 
     {
 $TIPO = "88";
 $subtipop = "EAN";
 $VERSAO = "13";
 $CODIGO_PRODUTO = str_pad($row['CODIGO_FORNECEDOR'] , 14 , ' ' , STR_PAD_RIGHT);

 $DESCRICAO = str_pad(substr($row['DESCRICAO'],0,53)  , 53, ' ' , STR_PAD_RIGHT);
 $UN = str_pad($row['UNIDADE_MEDIDA']  , 6 , ' ' , STR_PAD_RIGHT);
 $CODIGO_barra = str_pad($row['Codigo_Barra'] , 14 , ' ' , STR_PAD_RIGHT);
 $BRANCOS = Str_pad(''  ,32 , ' ' , STR_PAD_RIGHT);

 $linha =  $linha."88".$subtipop.$VERSAO.$CODIGO_PRODUTO.$DESCRICAO.$UN.$CODIGO_barra.$BRANCOS."\r\n";


$reg =  $reg + 1;
}
if( $reg > 0) { 
 $regfinal =  $regfinal + $reg;
$TOTAL_TIPO = str_pad("88" , 2 , '0' , STR_PAD_RIGHT);
$TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
$_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
$_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
}
$reg  = 0;

//REGISTRO 90

     $TIPO = "90";
     $CNPJ  = str_pad($CNPJ  , 14 , '0' , STR_PAD_LEFT);
     $INSCR_ESTADUAL = str_pad($INSC_ESTADUAL  , 14 , ' ' , STR_PAD_LEFT);
     $TOTAL_TIPO = str_pad($TOTAL_TIPO  , 2 , '0' , STR_PAD_RIGHT);
     
  
     $NUMERO_REG_90 = str_pad('2'  , 1 , '0' , STR_PAD_RIGHT);
     $X = 95-(strlen($_totalizador));          
     $branco  = str_pad(' '  ,  $X , ' ' , STR_PAD_RIGHT);
     $branco2  = str_pad(' '  , 98-(strlen($regfinal)) , ' ' , STR_PAD_RIGHT);
     $linha = $linha."90".$CNPJ.$INSCR_ESTADUAL.$_totalizador.$branco.$NUMERO_REG_90."\r\n";
     
     $_totalizadorGeral = $_totalizadorGeral + 4;

     

     $branco2  = str_pad(' '  ,85 , ' ' , STR_PAD_RIGHT);
  
     $_totalizadorGeral = str_pad($_totalizadorGeral  , 8 , '0' , STR_PAD_LEFT);
     $linha = $linha."90".$CNPJ.$INSCR_ESTADUAL."99".$_totalizadorGeral.$branco2.$NUMERO_REG_90."\r\n";

     $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
     fwrite($fp,$linha);
     fclose($fp); 
  


exit();
	
			