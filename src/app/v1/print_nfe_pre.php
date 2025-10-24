<?php
session_start();

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
require_once '../FPDF/fpdf.php';

use Functions\NFeService;
use NFePHP\NFe\Common\PdfNFePHP;
use Database\MySQL;

$pdo = MySQL::acessabd();

function remove($_texto) {
	$_texto =    str_replace(")", "", $_texto);
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	return $_texto;
} 

function Mask($mask,$str){

  $str = str_replace(" ","",$str);

  for($i=0;$i<strlen($str);$i++){
      $mask[strpos($mask,"#")] = $str[$i];
  }

  return $mask;

}


function isInteger($number) {
  return intval($number) == $number;
}


try {

 
     //buscar numero da OS
     $sq = "Select EMAIL,empresa_vizCodInt from " . $_SESSION['BASE'] . ".parametro";
     $statement = $pdo->query("$sq");
        $retorno = $statement->fetch();  
     
        $email = $retorno["EMAIL"];
        $_vizCodInterno = $retorno['empresa_vizCodInt'];
        if($_vizCodInterno == 1) { 
          $_COD = "CODIGO_FABRICANTE";   
        }else{
          $_COD = "codigoproduto_nfeitens";   
        }
 
       $sql = "SELECT nfed_xml FROM " . $_SESSION['BASE'] . ".NFE_DADOS 
        WHERE nfed_id  = '".$_POST['id-nota']."'";

        $statement = $pdo->query("$sql");
        $retorno = $statement->fetch();  
        $xml = $retorno["nfed_xml"];

       
        $docxml = "$xml";   

        //contar paginas
        $contagemadicional = preg_match_all('/\binfAdProd>\b/i', $xml); 
        $totalpg  =  $contagemadicional +  preg_match_all('/\bnItem\b/i', $xml); 

        $_ctagem = 0;
        $_contador  = $totalpg - 14;
        $FOLHAFIM = 1;
        if(($totalpg) <= 14 ) {
          $FOLHAFIM = 1;
        }else{
          $FOLHAFIM = 2;
         while( 0 <= $_contador ) {  //32    
            if($_ctagem <= 32){   
              $_ctagem++;
              $_contador =   $_contador - 1;
           //   echo " $_ctagem /  $_contador <Br>";
            }else{
              $FOLHAFIM++;
           //   echo " FOLHA $FOLHAFIM <Br>";
              $_contador =   $_contador - 1;
              $_ctagem = 0;
            
          }
        }

        }
     

     //buscar dados empresa
     $sql2 = "SELECT nfed_empresa,empresa_tipo FROM " . $_SESSION['BASE'] . ".NFE_DADOS
     LEFT JOIN  " . $_SESSION['BASE'] . ".empresa ON empresa_id = nfed_empresa
     
            WHERE nfed_id  = '".$_POST['id-nota']."' ";

    $statement2 = $pdo->query("$sql2");
    $retorno2 = $statement2->fetch(PDO::FETCH_OBJ);

    $empresa = $retorno2->nfed_empresa;
    $empresa_tipo = $retorno2->empresa_tipo;

    if($empresa > 1){
      $logo  = $_SESSION['CODIGOCLI']."nf$empresa.jpg";
    }else{  
     $logo  = $_SESSION['CODIGOCLI']."nf.jpg";
  }
        $caminhoLogo = "../../logos/$logo";
 
//VERIFICAR QTDE REGISTRO PARA PAGINAÇÃO
if(  $empresa_tipo == 1){
  $sql2 = "SELECT situacaotributario_nfeitens,codigoproduto_nfeitens FROM " . $_SESSION['BASE'] . ".NFE_ITENS 
  WHERE id_nfedados  = '".$_POST['id-nota']."' and length(situacaotributario_nfeitens) > 3 order by id_nfeitens ASC";
}else{
  $sql2 = "SELECT situacaotributario_nfeitens,codigoproduto_nfeitens FROM " . $_SESSION['BASE'] . ".NFE_ITENS 
  WHERE id_nfedados  = '".$_POST['id-nota']."' and length(situacaotributario_nfeitens) > 2 order by id_nfeitens ASC";
}

$statement2 = $pdo->query("$sql2");
$retorno2 = $statement2->fetchAll();

$TOTALREG = $statement2->rowCount();

$linhaM = 2;

    $pdf = new FPDF("P", "mm", array(210,297));
    $pdf->AddPage(); //Acrescenta uma página ao arquivo
    $pdf->SetAutoPageBreak(false,0);
    $pdf->Image($caminhoLogo ,30,22, 30);
    $pdf->SetFont('Arial','B',16); //Define o estilo da fonte, características como Negrito(bold), Itálico ou Sublinhado(U), verifique quais fontes a sua biblioteca utiliza.
   
    if ( !empty($xml) ) {
      $danfe = new DomDocument;
      $xml   = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);


$objJsonDocument = json_encode($xml);
$arrOutput = json_decode($objJsonDocument, TRUE);

/*
echo "<pre>";
print_r($arrOutput);
echo "</pre>";

exit();*/
 }

 
    
 //$pdf->Cell(40,10,$arrOutput['infNFe']['@attributes']['Id']); //cria uma área retangular com o texto dentro
$fontePadrao = 'Arial';
$xInic = 1;
$yInic = 1;
$pag = 1;
$maxH = 210;
$maxW = 297;
$w = 80;// 80;
$x = $xInic;
$y = $yInic;
$w1 = $w;


$w = 85;//85;
$w3 = $w;
$wCanhoto = 25;

$h=32;
$oldX = $x;
$oldY += $h;

$texto = "SEM VALOR FISCAL ";
$pdf->SetFont($fontePadrao,'',16); 
$borda = 0; $alinhamento = 'L';
$pdf->SetXY(140, 40);
$pdf->Cell(60,6,$texto,$borda,0,$alinhamento); 


$pdf->SetXY(80, 270);
$pdf->Cell(60,6,$texto,$borda,0,$alinhamento); 

$pdf->SetFont($fontePadrao,'',12); 
$pdf->SetXY(100, 240);
$pdf->Cell(60,6,$texto,$borda,0,$alinhamento); 
  //####################################################################################
        //Dados da NF do cabeçalho
        $texto = "RECEBEMOS DE " ;
        $texto .= $arrOutput['infNFe']['emit']['xFant'];//$emitente;        
        $texto .= " OS PRODUTOS E/OU SERVIÇOS CONSTANTES DA NOTA FISCAL ELETRÔNICA INDICADA ABAIXO";
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(5, 5);
        $pdf->Cell(160,6,$texto,$borda,0,$alinhamento);

        $texto = "DATA RECEBIMENTO ";      
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(5, 9);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        $pdf->SetXY(5, 11);
        $borda = 1;
        $texto = "";
        $pdf->Cell(50,8,$texto,$borda,0,$alinhamento);

        

        $texto = "IDENTIFICAÇÃO E ASSINATURA DO INDENTIFICADOR";      
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(55, 9);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        $pdf->SetXY(55, 11);
        $borda = 1;
        $texto = "";
        $pdf->Cell(110,8,$texto,$borda,0,$alinhamento);

       
        $texto = "NF-e"; //QUADRADO NUMERO NF SUPERIOR      
        $pdf->SetFont($fontePadrao,'B',10);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(180, 4);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "Nº   ".str_pad($arrOutput['infNFe']['ide']['nNF'] , 8 , '0' , STR_PAD_LEFT);
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(175, 8);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "SÉRIE:   ".$arrOutput['infNFe']['ide']['serie'];
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(175, 12);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(170, 5);
        $borda = 1;
        $texto = "";
        $pdf->Cell(30,14,$texto,$borda,0,$alinhamento);

        $texto = "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(5, 15);
        $pdf->Cell(180,10,$texto,$borda,0,$alinhamento);


     
        //dados e logo
        $_razao = $arrOutput['infNFe']['emit']['xNome'];
        if(strlen($_razao) > 50){
          $texto = $arrOutput['infNFe']['emit']['xNome'];//$emitente;   
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 36);
          //$pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
          $pdf->MultiCell(80, 3,  $texto, 0,$alinhamento,false);
        }else{
          $texto = $arrOutput['infNFe']['emit']['xNome'];//$emitente;   
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 35);
          $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
        }


        $texto = "".$arrOutput['infNFe']['emit']['enderEmit']['xLgr']. " ".$arrOutput['infNFe']['emit']['enderEmit']['nro'];   
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 38);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

        $CEP = $arrOutput['infNFe']['emit']['enderEmit']['CEP'];
        $CEP  = substr($CEP,0,5)."-".substr($CEP,5,3);
        $texto = "".$arrOutput['infNFe']['emit']['enderEmit']['xBairro']. " - ".$arrOutput['infNFe']['emit']['enderEmit']['xMun']. " - ".$arrOutput['infNFe']['emit']['enderEmit']['UF'] . " - ".$CEP;   
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 41);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

        $texto = $email;
        if($email != "") {
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 44);
          $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
        }
        

        $pdf->SetXY(5, 21);
        $borda = 1;
        $texto = "";
        $pdf->Cell(80,30,$texto,$borda,0,$alinhamento);


        //centro
        $texto = "DANFE";
        $pdf->SetFont($fontePadrao,'B',10);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 21);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        
        $texto = "Documento Auxiliar";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 24);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "da Nota Fiscal Eletrônica";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 27);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "Eletrônica";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 30);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "0 - Entrada";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 34);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "1 - Saída";
        $pdf->SetFont($fontePadrao,'',7);     
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 37);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(110, 38);
        $borda = 1;
        $texto = $arrOutput['infNFe']['ide']['tpNF'];
        $pdf->Cell(5,5,$texto,$borda,0,$alinhamento);

        $texto = "Nº   ".str_pad($arrOutput['infNFe']['ide']['nNF'] , 8 , '0' , STR_PAD_LEFT);
        $pdf->SetFont($fontePadrao,'B',9);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 41);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        $_FOLHA = 1;
        $texto = "SÉRIE:   ".$arrOutput['infNFe']['ide']['serie']."   FOLHA: ".$_FOLHA." de ". $FOLHAFIM;
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 44);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(85, 21);
        $borda = 1;
        $texto = "";
        $pdf->Cell(40,30,'',$borda,0,$alinhamento);

        //COdigo barra
      
        $chave_acesso = $arrOutput['protNFe']['infProt']['chNFe'];   
        $pdf->SetFont($fontePadrao,'',7);     
        $borda = 0; $alinhamento = 'L';
      //  $texto = "|||||||||||||||||||||||||||||||||||||";
        $pdf->SetXY(125, 25);
       // $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        //codigo de barras
      // $pdf->Code128($x+(($w-$bW)/2),$y+2,$chave_acesso,$bW,$bH);
      $pdf->Image("../../logos/Code128code.png" ,127,22,70);
//C set
/*
        $code='35230509482190000103550010000070051000001455';
        $pdf->Code128(127,23,$code,70,12);
        $pdf->SetXY(50,145);
      
*/
       


        $texto = 'CHAVE DE ACESSO';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(125, 33);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

           
        $texto =  Mask("#### #### #### #### #### #### #### #### #### #### ####",$chave_acesso);
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(125, 36);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);


        $texto = 'Consulta de autenticidade no portal nacional da NF-e  www.nfe.fazenda.gov.br/portal ou no site da Sefaz Autorizadora';
        $pdf->SetFont($fontePadrao,'B',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(125, 45);      
        $pdf->MultiCell(75, 2,  $texto, 0,$alinhamento,false);


       

        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(125, 21);
        $borda = 1;
        $texto = "";
        $pdf->Cell(75,15,$texto,$borda,0,$alinhamento);

        //chave acesso        
        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(125, 36);
        $borda = 1;
        $texto = "";
        $pdf->Cell(75,7,$texto,$borda,0,$alinhamento);

             
        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(125, 43);
        $borda = 1;
        $texto = "";
        $pdf->Cell(75,8,$texto,$borda,0,$alinhamento);

        
        //natureza da operação          
        $texto = "NATUREZA DA OPERAÇÃO";
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(5, 49);
        $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(5, 53);
        $texto = $arrOutput['infNFe']['ide']['natOp'];
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);
 
        $pdf->SetXY(5, 51);
        $pdf->SetFont($fontePadrao,'',7);     
        $borda = 1;
        $texto ='';
        $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);

                
          $texto = "PROTOCOLO DE AUTORIZAÇÃO DE USO";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(125, 49);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(125, 53);
          $texto = $arrOutput['protNFe']['infProt']['nProt']."  ".$arrOutput['protNFe']['infProt']['dhRecbto'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(125, 51);
          $pdf->SetFont($fontePadrao,'',7);     
          $borda = 1;
          $texto ='';
          $pdf->Cell(75,8,$texto,$borda,0,$alinhamento);

          $texto = "INSCRIÇÃO ESTADUAL";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(5, 57);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(5, 61);
          $texto =$arrOutput['infNFe']['emit']['IE'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(5, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);

           
          $texto = "INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(65, 57);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
     
          $pdf->SetXY(65, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(70,8,$texto,$borda,0,$alinhamento);

          $texto = "CNPJ";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(135, 57);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
          $CNPJ = substr($arrOutput['infNFe']['emit']['CNPJ'],0,2).".".substr($arrOutput['infNFe']['emit']['CNPJ'],2,3).".".substr($arrOutput['infNFe']['emit']['CNPJ'],5,3)."/".substr($arrOutput['infNFe']['emit']['CNPJ'],8,4)."-".substr($arrOutput['infNFe']['emit']['CNPJ'],12,2).

          $pdf->SetXY(135, 61);
          $texto = $CNPJ;
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
     
          $pdf->SetXY(135, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(65,8,$texto,$borda,0,$alinhamento);

          //destinatio emitente            
          $texto = "DESTINATÁRIO / REMETENTE";
          $pdf->SetFont($fontePadrao,'B',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(4, 66);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);

          $texto = "NOME / RAZÃO SOCIAL";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(5, 70);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(5,74);
          $texto =$arrOutput['infNFe']['dest']['xNome'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(5, 72);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);

          $texto = "CNPJ / CPF";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(125, 70);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);

          $cpfcnpj =remove($arrOutput['infNFe']['dest']['CPF'].$arrOutput['infNFe']['dest']['CNPJ']);
          if(strlen($cpfcnpj)==11) //cpf
          {
              

              $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
              substr($cpfcnpj, 3, 3) . '.' .
              substr($cpfcnpj, 6, 3) . '-' .
              substr($cpfcnpj, 9, 2);
          } else {
              

              $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                      substr($cpfcnpj, 2, 3) . '.' .
                                      substr($cpfcnpj, 5, 3) . '/' .
                                      substr($cpfcnpj, 8, 4) . '-' .
                                      substr($cpfcnpj, -2);

          } 
  
          $pdf->SetXY(125,74);
          $texto = $cpfcnpj;
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(125, 72);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(40,8,$texto,$borda,0,$alinhamento);



          $texto = "DATA DA EMISSÃO";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(166, 70);
          $pdf->Cell(20,8,$texto,$borda,0,$alinhamento);

          $pdf->SetXY(165,74);
          $texto =substr($arrOutput['infNFe']['ide']['dhEmi'],8,2)."/".substr($arrOutput['infNFe']['ide']['dhEmi'],5,2)."/".substr($arrOutput['infNFe']['ide']['dhEmi'],0,4);
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(40,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(165, 72);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

          
          $texto = "ENDEREÇO";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(5, 78);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(5,82);
          $texto = $arrOutput['infNFe']['dest']['enderDest']['xLgr']." ".$arrOutput['infNFe']['dest']['enderDest']['nro'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(45,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(5, 80);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(80,8,$texto,$borda,0,$alinhamento);

          $texto = "BAIRRO / DISTRITO";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(85, 78);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(85,82);
          $texto = $arrOutput['infNFe']['dest']['enderDest']['xBairro'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
          
          $pdf->SetXY(85, 80);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);


          
          $texto = "CEP";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(145, 78);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(145,82);
          $CEP = $arrOutput['infNFe']['dest']['enderDest']['CEP'];
          $texto  = substr($CEP,0,5)."-".substr($CEP,5,3);       
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(145, 80);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(20,8,$texto,$borda,0,$alinhamento);



          $texto = "DATA DA SAÍDA";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(165, 78);
          $pdf->Cell(20,8,$texto,$borda,0,$alinhamento);

          $pdf->SetXY(165,82);
          //$texto =substr($arrOutput['infNFe']['ide']['dhEmi'],8,2)."/".substr($arrOutput['infNFe']['ide']['dhEmi'],5,2)."/".substr($arrOutput['infNFe']['ide']['dhEmi'],0,4);
          $texto = '';
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(40,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(165, 80);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

          $texto = "MUNICÍPIO";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(5, 86);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(5,90);
          $texto = $arrOutput['infNFe']['dest']['enderDest']['xMun'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(55,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(5, 88);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(55,8,$texto,$borda,0,$alinhamento);

                  
          $texto = "FONE / FAX";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(61, 86);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(61,90);
          $texto = $arrOutput['infNFe']['dest']['enderDest']['fone'];
        //  $texto  = substr($CEP,0,5)."-".substr($CEP,5,3);       
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(60, 88);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

          $texto = "UF";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(96, 86);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(96,90);
          $texto = $arrOutput['infNFe']['dest']['enderDest']['UF'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(10,8,$texto,$borda,0,$alinhamento);
          
          $pdf->SetXY(95, 88);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(10,8,$texto,$borda,0,$alinhamento);

          $texto = "INSCRIÇÃO ESTADUAL";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(106, 86);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(106,90);
          $texto  = $arrOutput['infNFe']['dest']['IE'];     
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(105, 88);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
          


          $texto = "HORA DA SAÍDA";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(165, 86);
          $pdf->Cell(20,8,$texto,$borda,0,$alinhamento);

          $pdf->SetXY(165, 88);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        
          //FATURA / DUPLICATA          
          $texto = "FATURA / DUPLICATA";
          $pdf->SetFont($fontePadrao,'B',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(4, 94);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);

          $pdf->SetXY(5, 100);             
          $borda = 1;
          $texto =$value['nDup']."  ".$value['dVenc']."  ".$value['vDup'];
          $pdf->Cell(195,8,$texto,$borda,0,$alinhamento);
          $_xfat = $_xfat + 45;

          $_xfat = 5;
  
          
         $dups = $arrOutput['infNFe']['cobr']['dup'];

        // Se for um array associativo (1 duplicata só), transforma em um array de 1 elemento
        if (isset($dups['nDup'])) {
            $dups = [$dups];
        }

        foreach ($dups as $value) {
            if ($value['vDup'] > 0) {
                $_dtfat = explode("-", $value['dVenc']);
                $_dtfat = $_dtfat[2] . "/" . $_dtfat[1] . "/" . $_dtfat[0];
                $pdf->SetXY($_xfat, 100);
                $borda = 1;
                $pdf->SetFont($fontePadrao, '', 7);
                $texto = $value['nDup'] . "   " . $_dtfat . "      " . number_format($value['vDup'], 2, ",", ".");
                $pdf->Cell(40, 8, $texto, $borda, 0, $alinhamento);
                $_xfat = $_xfat + 40;
            }
        }


        //FATURA / DUPLICATA          
        $texto = "CÁLCULO DO IMPOSTO";
        $pdf->SetFont($fontePadrao,'B',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(4, 106);
        $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);

        $texto = "BASE DE CÁLCULO DO ICMS";
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(5, 110);
        $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5,114);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vBC'], 2, ",", ".");    
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(5, 112);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(45,8,$texto,$borda,0,$alinhamento);

        $texto = "VALOR DO ICMS";
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(51, 110);
        $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(51,114);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vICMS'], 2, ",", ".");    
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(50, 112);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $texto = 'BASE DE CÁLC. ICMS S.T.';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 110);
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86,114);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vBCST'], 2, ",", ".");     
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(85, 112);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $texto = 'VALOR DO ICMS SUBST.';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(121, 110);
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(122,114);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vST'], 2, ",", ".");       
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(40,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(120, 112);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(45,8,$texto,$borda,0,$alinhamento);

      
        $texto = 'VALOR TOTAL DOS PRODUTOS';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(165, 110);
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(165, 114);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vProd'], 2, ",", ".");     
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(165, 112);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

    
        $texto = 'VALOR DO FRETE';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(5, 118);
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 122);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vFrete'], 2, ",", ".");    
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(5, 120);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

        $texto = 'VALOR DO SEGURO';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(40, 118);
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);
       
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(40, 122);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vSeg'], 2, ",", ".");     
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(39, 120);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

        $texto = 'DESCONTO';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(75, 118);
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(75, 122);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vDesc'], 2, ",", ".");    
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(73, 120);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

        $texto = 'OUTRAS DESPESAS';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(108, 118);
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(108, 122);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vOutro'], 2, ",", ".");   
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(107, 120);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

        $texto = 'VALOR TOTAL DO IPI';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(138, 118);
        $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(138, 122);
        $texto  = number_format($arrOutput['infNFe']['total']['ICMSTot']['vIPI'], 2, ",", ".");     
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(137, 120);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(28,8,$texto,$borda,0,$alinhamento);

        $texto = 'VALOR TOTAL DA NOTA';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(165, 118);
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(165, 122);
        $texto  =  number_format($arrOutput['infNFe']['total']['ICMSTot']['vNF'], 2, ",", ".");     
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(165, 120);             
        $borda = 1;
        $texto ='';
        $pdf->Cell(34,8,$texto,$borda,0,$alinhamento);

        //TRANPORTADOR
         //FATURA / DUPLICATA          
         $texto = "TRANSPORTADOR / VOLUMES TRANSPORTADOS";
         $pdf->SetFont($fontePadrao,'B',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(4, 127);
         $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
 
         $texto = "NOME / RAZÃO SOCIAL";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(5, 131);
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(5,135);
         $texto = '';
         $texto  = $arrOutput['infNFe']['transp']['transporta']['xNome'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(85,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(5, 133);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(84,8,$texto,$borda,0,$alinhamento);

         $texto = "FRETE POR CONTA";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(90, 131);
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(90,135);
         $tipofrete = $arrOutput['infNFe']['transp']['modFrete'];
         if($tipofrete == 0) { 
          $texto = "Emitente";
         }
         if($tipofrete == 1) { 
          $texto = "Destinatario";
         }
         if($tipofrete == 2) { 
          $texto = "Terceiros";
         }
         if($tipofrete == 9) { 
          $texto = "Sem Frete";
         }
       
      
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(90,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(89, 133);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

         $texto = "CÓDIGO ANTT";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(115, 131);
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(115,135);
         $texto = "";
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(20,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(114, 133);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

         $texto = "PLACA DO VEÍCULO";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(140, 131);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(140,135);
         $texto = "";
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(139, 133);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

         $texto = "UF";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(165, 131);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(165,135);
         $texto  = $arrOutput['infNFe']['transp']['transporta']['UF'];     
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(164, 133);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(10,8,$texto,$borda,0,$alinhamento);

         $texto = "CPF/CNPJ";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(175, 131);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

         $cpfcnpjT = remove($arrOutput['infNFe']['transp']['transporta']['CNPJ']);

         if(strlen($cpfcnpjT)==11) //cpf
         {             

             $cpfcnpjT = substr($cpfcnpjT, 0, 3) . '.' .
             substr($cpfcnpjT, 3, 3) . '.' .
             substr($cpfcnpjT, 6, 3) . '-' .
             substr($cpfcnpjT, 9, 2);
         } else {              

             $cpfcnpjT = substr($cpfcnpjT, 0, 2) . '.' .
             substr($cpfcnpjT, 2, 3) . '.' .
             substr($cpfcnpjT, 5, 3) . '/' .
             substr($cpfcnpjT, 8, 4) . '-' .
             substr($cpfcnpjT, -2);

         } 
 

 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(175,135);
         $texto  = $cpfcnpjT;
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(174, 133);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

         $texto = "ENDEREÇO";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(5, 139);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(5,143);
         $texto  = $arrOutput['infNFe']['transp']['transporta']['xEnder'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(5, 141);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(95,8,$texto,$borda,0,$alinhamento);

         $texto = "MUNICÍPIO";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(101, 139);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(101,143);
         $texto  = $arrOutput['infNFe']['transp']['transporta']['xMun'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(100, 141);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(45,8,$texto,$borda,0,$alinhamento);

         $texto = "UF";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(146, 139);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(146,143);
         $texto  = $arrOutput['infNFe']['transp']['transporta']['UF'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(145, 141);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(10,8,$texto,$borda,0,$alinhamento);

         $texto = "INSCRIÇÃO ESTADUAL";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(156, 139);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(156,143);
         $texto  = $arrOutput['infNFe']['transp']['transporta']['IE'];   
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(155, 141);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(44,8,$texto,$borda,0,$alinhamento);

         $texto = "QUANTIDADE";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(5, 147);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(5,150);
         $texto  = $arrOutput['infNFe']['transp']['vol']['qVol'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(5, 149);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

         $texto = "ESPÉCIE";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(35, 147);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(35,150);
         $texto  = $arrOutput['infNFe']['transp']['vol']['esp'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(35, 149);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

         $texto = "MARCA";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(65, 147);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(65,150);
         $texto  = $arrOutput['infNFe']['transp']['vol']['marca'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(65, 149);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

         $texto = "NUMERAÇÃO";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(95, 147);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(95,150);
         $texto  = $arrOutput['infNFe']['transp']['vol']['nVol'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $pdf->SetXY(95, 149);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);

         $texto = "PESO BRUTO";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(125, 147);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);
 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(125,150);
         $texto  = $arrOutput['infNFe']['transp']['vol']['pesoB'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
         isInteger($texto) ? $texto =   iconv('UTF-8', 'ISO-8859-1', intval($texto)) : $texto =   iconv('UTF-8', 'ISO-8859-1', number_format($texto, 3, ",", "."));       
         $pdf->Cell(25,8,$texto." KG",$borda,0,$alinhamento);
 
         $pdf->SetXY(125, 149);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(35,8,$texto,$borda,0,$alinhamento);
         
         $texto = "PESO LÍQUIDO";
         $pdf->SetFont($fontePadrao,'',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(160, 147);
         $pdf->Cell(25,8,$texto,$borda,0,$alinhamento);

 
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(160,150);
         $texto  = $arrOutput['infNFe']['transp']['vol']['pesoL'];    
        // $texto  = $arrOutput['infNFe']['total']['ICMSTot']['vBC'];    
         $pdf->SetFont($fontePadrao,'',7);     
              isInteger($texto) ? $texto =   iconv('UTF-8', 'ISO-8859-1', intval($texto)) : $texto =   iconv('UTF-8', 'ISO-8859-1', number_format($texto, 3, ",", "."));      ;  
         $pdf->Cell(25,8,$texto." KG",$borda,0,$alinhamento);
 
         $pdf->SetXY(160, 149);             
         $borda = 1;
         $texto ='';
         $pdf->Cell(39,8,$texto,$borda,0,$alinhamento);

         //DADOS PRODUTOS
         $texto = "DADOS DOS PRODUTOS / SERVIÇOS";
         $pdf->SetFont($fontePadrao,'B',7);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 0; $alinhamento = 'L';
         $pdf->SetXY(4, 155);
         $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);

     
 
         $texto = "CÓD. PRODUTO";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'L';
         $pdf->SetXY(5, 161);
         $pdf->Cell(18,7,$texto,$borda,0,$alinhamento);

         $texto = "DESCRIÇÃO DO PRODUTO";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'L';
         $pdf->SetXY(23, 161); 
         $pdf->Cell(45,7,$texto,$borda,0,$alinhamento);             
       //  $pdf->MultiCell(75, 4,$texto, 0, 'L', 0,6, '', '', true, 0, false, true, 40, 'T');
      
       $texto = "NCM / SH";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(68, 161);
       $pdf->Cell(14,7,$texto,$borda,0,$alinhamento);

       $texto = "CST";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(82, 161);
       $pdf->Cell(6,7,$texto,$borda,0,$alinhamento);

       $texto = "CFOP";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(88, 161);
       $pdf->Cell(7,7,$texto,$borda,0,$alinhamento);

       $texto = "UND";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(95, 161);
       $pdf->Cell(7,7,$texto,$borda,0,$alinhamento);
     
       $texto = "QTDE";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(102, 161);
       $pdf->Cell(12,7,$texto,$borda,0,$alinhamento);

       $texto = "VALOR UNIT";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(114, 161);
       $pdf->Cell(15,7,$texto,$borda,0,$alinhamento);

       $texto = "VALOR TOTAL";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(129, 161);
       $pdf->Cell(16,7,$texto,$borda,0,$alinhamento);

       $texto = "BC ICMS";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(145, 161);
       $pdf->Cell(12,7,$texto,$borda,0,$alinhamento);

       $texto = "VALOR ICMS";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(157, 161);
       $pdf->Cell(15,7,$texto,$borda,0,$alinhamento);

       $texto = "VALOR IPI";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(172, 161);
       $pdf->Cell(12,7,$texto,$borda,0,$alinhamento);

       $texto = "ALIQUOTAS";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(184, 161);
       $pdf->Cell(16,4,$texto,$borda,0,$alinhamento);

       $texto = "ICMS";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(184, 165);
       $pdf->Cell(8,3,$texto,$borda,0,$alinhamento);

       $texto = "IPI";
       $pdf->SetFont($fontePadrao,'B',6);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(192, 165);
       $pdf->Cell(8,3,$texto,$borda,0,$alinhamento);

       
       
       $altura = 162;
       $ULTIMOITEM= 0;
       $ULTIMOADD = 0;
       $ITEM = 0;
       $_FOLHA = 1;
    //LOOP PRODUTOS
    //echo  print_r($arrOutput['infNFe']['det']);


    foreach ($arrOutput['infNFe']['det'] as $value) {
    
       
      if ($ULTIMOITEM <= 14 and ($altura+$linhaM) <= 240 ) {
        $i++; 
    
     
                  if( is_array($value)){
                    if( $_infAdProd == ""){
                      $_infAdProd  = $value['infAdProd'];
                    }        
                  
                  }else{     
                    if( $_infAdProd == ""){
                      $_infAdProd  = trim($value);
                  }                  
                  } 
                 ;
                  $_aliIpiDEV  =  $arrOutput['infNFe']['det']["impostoDevol"]["pDevol"];
                  $_aliIpiDEVvlr  =  $arrOutput['infNFe']['det']["impostoDevol"]['IPI']["vIPIDevol"];
              

                  if(count($value['@attributes']) > 0 ) { 
                    
                   
                    $prod = 1;
                  // if( $prod == 0){
                      $_codproduto  = $value['prod']['cProd'];
                      $_codigoEan = $value['prod']['cEAN'];
                      $_descricao = $value['prod']['xProd'];
                    
                  
                      $_cfop = $value['prod']["CFOP"];
                      $_ncm = $value['prod']['NCM'];      
                  
                      $_uni = $value['prod']['uCom'];
                      $_qtde = $value['prod']['qCom'];
                      //number_format($arrOutput['infNFe']['total']['ICMSTot']['vBC'], 2, ",", ".");    
                      $_valorunit = number_format($value['prod']['vUnTrib'], 2, ",", ".");   
                      $_valortotal = number_format($value['prod']['vProd'], 2, ",", "."); 
                      if($_basecalculo == "")  {

                     
                      $_basecalculo = '0,00';
                      $_valoricms = '0,00';
                      $_valoripi = '0,00';
                      $_aliIcms = '0,00';
                      $_aliIpi = '0,00';
                    }
                  // }
                //   if( $prod == 1){

                  $_aliIpi = number_format($value['IPI']['IPITrib']['pIPI'], 2, ",", ".");  
                  $_valoripi = number_format($value['IPI']['IPITrib']['vIPI'], 2, ",", ".");

                  if($_aliIpiDEV != "") {
                    $_aliIpi = $_aliIpiDEV;
                    $_valoripi= $_aliIpiDEVvlr;
                  }
         
                  $_cst  = $value['imposto']['ICMS']['ICMSSN102']['CSOSN'];
                  if($_cst == ""){
                   $_cst  = $value['imposto']['ICMS']['ICMSSN500']['CSOSN'];
                // $_cst  = '2500';
                  }
                  if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMSSN103']['CSOSN'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMSSN101']['CSOSN'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMSSN400']['CSOSN'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMSSN900']['CSOSN'];
                   
                    $_basecalculo = $value['imposto']['ICMS']['ICMSSN900']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMSSN900']['vICMS'];
                    $_picms = $value['imposto']['ICMS']['ICMSSN900']['pICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS00']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS00']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS00']['vICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS00']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS00']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS00']['vICMS'];
               
                   }
                  if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS10']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS00']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS00']['vICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS20']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS20']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS20']['vICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS30']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS30']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS30']['vICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS41']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS41']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS41']['vICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS40']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS40']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS40']['vICMS'];
               
                   }
                   if($_cst == ""){
                    $_cst  = $value['imposto']['ICMS']['ICMS60']['CST'];
                    $_basecalculo = $value['imposto']['ICMS']['ICMS60']['vBC'];
                    $_valoricms = $value['imposto']['ICMS']['ICMS60']['vICMS'];
               
                   }

                
                
                           //VERIFICAR OUTRAS CST
                           if($TOTALREG > 0) {
                            $sql3 = "SELECT situacaotributario_nfeitens,codigoproduto_nfeitens 
                            FROM " . $_SESSION['BASE'] . ".NFE_ITENS
                            INNER JOIN  " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = codigoproduto_nfeitens
                            WHERE id_nfedados  = '".$_POST['id-nota']."' and $_COD  = '$_codproduto'";     
                     
                                $statement3 = $pdo->query($sql3);
                                  $retorno3 = $statement3->fetch();
                               
                                  $_cstnovo =  $retorno3['situacaotributario_nfeitens'];
                                  if($_cstnovo !=""){
                                    $_cst  = $_cstnovo; 
                                  //  $_cst  = '4900';
                                 //   $_cst  = '2500';
                                  }
                               
                     
                           }
                 

                //    }
                    
                  

                  }
                  
                  else{
                    if( $prod == 0){
                        $_codproduto  = $value['cProd'];
                        $_codigoEan = $value['cEAN'];
                        $_descricao = $value['xProd'];
                        $_cfop = $value["CFOP"];
                        $_ncm = $value['NCM'];      
                        
                        $_uni = $value['uCom'];
                        $_qtde = $value['qCom'];
                        $_valorunit = number_format($value['vUnTrib'], 2, ",", ".");   
                        $_valortotal = number_format($value['vProd'], 2, ",", ".");  
                  
                        if($_basecalculo == "")  {
                            $_basecalculo = '0,00';
                            $_valoricms = '0,00';
                            $_valoripi = '0,00';
                            $_aliIcms = '0';
                            $_aliIpi = '0';
                        }
                  }
                
                  if( $prod == 1){
                
                    $_aliIpi = number_format($value['IPI']['IPITrib']['pIPI'], 2, ",", ".");
                    $_valoripi = number_format($value['IPI']['IPITrib']['vIPI'], 2, ",", ".");

                    if($_aliIpiDEV != "") {
                      $_aliIpi = $_aliIpiDEV;
                      $_valoripi= $_aliIpiDEVvlr;
                    }

                    $_cst  = $value['ICMS']['ICMSSN102']['CSOSN'];
                    if($_cst  == "" ) {
                      $_cst  = $value['ICMS']['ICMSSN500']['CSOSN'];
                    //  $_cst  = '2500';
                    }
                    if($_cst  == "") {
                      $_cst  = $value['ICMS']['ICMSSN900']['CSOSN'];
                      $_basecalculo = $value['ICMS']['ICMSSN900']['vBC'];
                      $_valoricms = $value['ICMS']['ICMSSN900']['vICMS'];
                      $_aliIcms = number_format($value['ICMS']['ICMSSN900']['pICMS'], 2, ",", ".");   
                       
                    }
                    if($_cst  == "") {
                      $_cst  = $value['ICMS']['ICMSSN103']['CSOSN'];
                    //  $_cst  = '2500';
                    }
                    if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMSSN101']['CSOSN'];
                 
                     }
                    if($_cst  == "") {
                      $_cst  = $value['ICMS']['ICMSSN400']['CSOSN'];
                    //  $_cst  = '2500';
                    }
                    if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS00']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS00']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS00']['vICMS'];
                 
                     }
                    if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS10']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS00']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS00']['vICMS'];
                 
                     }
                     if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS20']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS20']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS20']['vICMS'];
                 
                     }
                     if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS30']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS30']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS30']['vICMS'];
                 
                     }
                     if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS40']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS40']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS40']['vICMS'];
                 
                     }
                     if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS41']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS41']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS41']['vICMS'];
                 
                     }
                     if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS60']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS60']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS60']['vICMS'];
                 
                     }
                     if($_cst == ""){
                      $_cst  = $value['ICMS']['ICMS90']['CST'];
                      $_basecalculo = $value['ICMS']['ICMS90']['vBC'];
                      $_valoricms = $value['ICMS']['ICMS90']['vICMS'];
                      $_aliIcms = $value['ICMS']['ICMS90']['pICMS'];
                     }
                 
                  
                     //VERIFICAR OUTRAS CST
                     if($TOTALREG > 0) {
                      $sql3 = "SELECT situacaotributario_nfeitens,codigoproduto_nfeitens 
                      FROM " . $_SESSION['BASE'] . ".NFE_ITENS
                      INNER JOIN  " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = codigoproduto_nfeitens
                      WHERE id_nfedados  = '".$_POST['id-nota']."' and $_COD  = '$_codproduto'";
                  
                          $statement3 = $pdo->query($sql3);
                            $retorno3 = $statement3->fetch();
                         
                            $_cstnovo =  $retorno3['situacaotributario_nfeitens'];
                            if($_cstnovo !=""){
                              $_cst  = $_cstnovo; 
                            //  $_cst  = '4900';
                           //   $_cst  = '2500';
                            }
               
                     }
                    
                  }
   
              
                   
                    
                  }
        
                 
              if($_descricao != ""  and strlen($_descricao) > 2 and $value['pDevol'] == "" ) {
                                  
            
               
             
              
                  if( $prod == 1) {  
                
                    $ITEM++;       
                   $ULTIMOITEM++;
                        
                              $linhaM = $linhaM+6;
                              $texto = $_codproduto;
                              $pdf->SetFont($fontePadrao,'',6);     
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'L';
                              $pdf->SetXY(5, ($altura+$linhaM));
                              $pdf->Cell(18,6,$texto,$borda,0,$alinhamento);
              
                              
                              $texto = substr($_descricao,0,66);               
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'L';
                              $pdf->SetXY(23, ($altura+$linhaM)); 
                              /*
                              if(strlen($_descricao) < 33 ) {
                                  $pdf->Cell(45,6,$texto,$borda,0,$alinhamento);   
                              }else { 
                                  $pdf->MultiCell(45, 3,$texto, 1, 'L', 0,6, '', '', true, 0, false, true, 40, 'T');
                              }
                              */
                            //            
                            $pdf->MultiCell(45, 3,$texto, 0, 'L', false);
                            $pdf->SetXY(23, ($altura+$linhaM)); 
                            $pdf->MultiCell(45, 6,"", 1, 'L', false);    
                              
                              $texto = $_ncm;            
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(68, ($altura+$linhaM));
                              $pdf->Cell(14,6,$texto,$borda,0,$alinhamento);
                           
                              $texto = $_cst;                  
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(82, ($altura+$linhaM));
                              $pdf->Cell(6,6,$texto,$borda,0,$alinhamento);
              
                              $texto = $_cfop;                  
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(88, ($altura+$linhaM));
                              $pdf->Cell(7,6,$texto,$borda,0,$alinhamento);
              
                              $texto = $_uni ;                
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(95, ($altura+$linhaM));
                              $pdf->Cell(7,6,$texto,$borda,0,$alinhamento);
                          
                              $texto = $_qtde;                  
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(102, ($altura+$linhaM));
                              $pdf->Cell(12,6,$texto,$borda,0,$alinhamento);
              
                              $texto = $_valorunit;                   
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(114, ($altura+$linhaM));
                              $pdf->Cell(15,6,$texto,$borda,0,$alinhamento);
              
                              $texto = $_valortotal ;                    
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(129, ($altura+$linhaM));
                              $pdf->Cell(16,6,$texto,$borda,0,$alinhamento);
              
                              $texto = "$_basecalculo"; //BC ICMS                   
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(145,($altura+$linhaM));
                              $pdf->Cell(12,6,$texto,$borda,0,$alinhamento);
              
                              $texto = "$_valoricms"; //VALOR ICMS                    
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(157, ($altura+$linhaM));
                              $pdf->Cell(15,6,$texto,$borda,0,$alinhamento);
              
                              $texto = "$_valoripi"; //VALOR IPI                  
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(172, ($altura+$linhaM));
                              $pdf->Cell(12,6,$texto,$borda,0,$alinhamento);
              
                              
                              $texto = "$_aliIcms"; //ICMS                  
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(184, ($altura+$linhaM));
                              $pdf->Cell(8,6,$texto,$borda,0,$alinhamento);
              
                              $texto = "$_aliIpi"; //IPI             ;     
                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                              $borda = 1; $alinhamento = 'C';
                              $pdf->SetXY(192, ($altura+$linhaM));
                              $pdf->Cell(8,6,$texto,$borda,0,$alinhamento);

                           
                            
                              $prod = 0;
                          }
                              $prod = 1;
                  }
                 
                               if( $_infAdProd != "" and strlen($_infAdProd) > 2) {                                                       
                                $linhaM = $linhaM+6;
                                $ULTIMOADD++;     
                                $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                $borda = 1; $alinhamento = 'L';
                                $pdf->SetXY(5,  ($altura+$linhaM));                    
                                $pdf->Cell(194,6, $_infAdProd ,$borda,0,$alinhamento);   
                                $_infAdProd  = "";     
                              
                               
                             }

      }
    }
  //altura maxima 248

            //CALCULO DO ISSQN        
            $texto = "DADOS ADICIONAIS";
            $pdf->SetFont($fontePadrao,'B',8);     
            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
            $borda = 0; $alinhamento = 'L';
            $pdf->SetXY(4, 252);
            $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);


            $texto = "INFORMAÇÕES COMPLEMENTARES";
            $pdf->SetFont($fontePadrao,'',6);     
            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
            $borda = 0; $alinhamento = 'L';
            $pdf->SetXY(5, 256);
            $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);     
            
            

            $texto = $arrOutput['infNFe']['infAdic']['infCpl']." ".$arrOutput['infNFe']['infAdic']['obsCont']['xTexto']  ;
            $pdf->SetFont($fontePadrao,'',7);     
            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
            $borda = 0; $alinhamento = 'L';
            $pdf->SetXY(5, 262);
           // $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);  
            $pdf->MultiCell(140, 3,  $texto, 0,$alinhamento,false);
           

            $pdf->SetXY(5, 258);             
            $borda = 1;
            $texto ='';
            $pdf->Cell(140,35,$texto,$borda,0,$alinhamento);

            $texto = "RESERVADO AO FISCO";
            $pdf->SetFont($fontePadrao,'',6);     
            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
            $borda = 0; $alinhamento = 'L';
            $pdf->SetXY(146, 257);
            $pdf->Cell(30,8,$texto,$borda,0,$alinhamento);     

            $pdf->SetXY(145, 258);             
            $borda = 1;
            $texto ='';
            $pdf->Cell(55,35,$texto,$borda,0,$alinhamento);


            $totPag = intval($FOLHAFIM);
            $n = 2;
            $i = 0;
            //loop para páginas seguintes
            for ($n = 2; $n <= $totPag; $n++) {  //32     
              $_FOLHA++;       
              $pdf->AddPage(); //Acrescenta uma página ao arquivo
              $pdf->SetAutoPageBreak(false,0);
              $pdf->Image($caminhoLogo ,29,23, 30);
              $pdf->SetFont('Arial','B',16); //Define o estilo da fonte, características como Negrito(bold), Itálico ou Sublinhado(U), verifique quais fontes a sua biblioteca utiliza.
                  
              //$pdf->Cell(40,10,$arrOutput['NFe']['infNFe']['@attributes']['Id']); //cria uma área retangular com o texto dentro
              $fontePadrao = 'Arial';
              $xInic = 1;
              $yInic = 1;
              $pag = 1;
              $maxH = 210;
              $maxW = 297;
              $w = 80;// 80;
              $x = $xInic;
              $y = $yInic;
              $w1 = $w;
    
    
              $w = 85;//85;
              $w3 = $w;
              $wCanhoto = 25;
    
              $h=32;
              $oldX = $x;
              $oldY += $h;

        //dados e logo
        $_razao = $arrOutput['infNFe']['emit']['xNome'];
        if(strlen($_razao) > 50){
          $texto = $arrOutput['infNFe']['emit']['xNome'];//$emitente;   
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 36);
          //$pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
          $pdf->MultiCell(80, 3,  $texto, 0,$alinhamento,false);
        }else{
          $texto = $arrOutput['infNFe']['emit']['xNome'];//$emitente;   
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 35);
          $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
        }


        $texto = "".$arrOutput['infNFe']['emit']['enderEmit']['xLgr']. " ".$arrOutput['infNFe']['emit']['enderEmit']['nro'];   
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 38);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

        $CEP = $arrOutput['infNFe']['emit']['enderEmit']['CEP'];
        $CEP  = substr($CEP,0,5)."-".substr($CEP,5,3);
        $texto = "".$arrOutput['infNFe']['emit']['enderEmit']['xBairro']. " - ".$arrOutput['infNFe']['emit']['enderEmit']['xMun']. " - ".$arrOutput['infNFe']['emit']['enderEmit']['UF'] . " - ".$CEP;   
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 41);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

        $texto = $email;
        if($email != "") {
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 44);
          $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
        }
        

        $pdf->SetXY(5, 21);
        $borda = 1;
        $texto = "";
        $pdf->Cell(80,30,$texto,$borda,0,$alinhamento);


        //centro
        $texto = "DANFE";
        $pdf->SetFont($fontePadrao,'B',10);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 21);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        
        $texto = "Documento Auxiliar";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 24);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "da Nota Fiscal Eletrônica";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 27);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "Eletrônica";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(86, 30);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "0 - Entrada";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 34);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $texto = "1 - Saída";
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 37);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(110, 38);
        $borda = 1;
        $texto = $arrOutput['infNFe']['ide']['tpNF'];
        $pdf->Cell(5,5,$texto,$borda,0,$alinhamento);

        $texto = "Nº   ".str_pad($arrOutput['infNFe']['ide']['nNF'] , 8 , '0' , STR_PAD_LEFT);
        $pdf->SetFont($fontePadrao,'B',9);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 41);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        $_FOLHA = 1;
        $texto = "SÉRIE:   ".$arrOutput['infNFe']['ide']['serie']."   FOLHA: ".$_FOLHA." de ". $FOLHAFIM;
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(86, 44);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(85, 21);
        $borda = 1;
        $texto = "";
        $pdf->Cell(40,30,'',$borda,0,$alinhamento);

        //COdigo barra
      
        $chave_acesso = $arrOutput['protNFe']['infProt']['chNFe'];   
        $pdf->SetFont($fontePadrao,'',7);     
        $borda = 0; $alinhamento = 'L';
      //  $texto = "|||||||||||||||||||||||||||||||||||||";
        $pdf->SetXY(125, 25);
       // $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
        //codigo de barras
      // $pdf->Code128($x+(($w-$bW)/2),$y+2,$chave_acesso,$bW,$bH);
      $pdf->Image("../../logos/Code128code.png" ,127,22,70);
//C set
/*
        $code='35230509482190000103550010000070051000001455';
        $pdf->Code128(127,23,$code,70,12);
        $pdf->SetXY(50,145);
      
*/
       


        $texto = 'CHAVE DE ACESSO';
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(125, 33);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

           
        $texto =  Mask("#### #### #### #### #### #### #### #### #### #### ####",$chave_acesso);
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(125, 36);
        $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);


        $texto = 'Consulta de autenticidade no portal nacional da NF-e  www.nfe.fazenda.gov.br/portal ou no site da Sefaz Autorizadora';
        $pdf->SetFont($fontePadrao,'B',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(125, 45);      
        $pdf->MultiCell(75, 2,  $texto, 0,$alinhamento,false);


       

        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(125, 21);
        $borda = 1;
        $texto = "";
        $pdf->Cell(75,15,$texto,$borda,0,$alinhamento);

        //chave acesso        
        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(125, 36);
        $borda = 1;
        $texto = "";
        $pdf->Cell(75,7,$texto,$borda,0,$alinhamento);

             
        $borda = 1; $alinhamento = 'L';
        $pdf->SetXY(125, 43);
        $borda = 1;
        $texto = "";
        $pdf->Cell(75,8,$texto,$borda,0,$alinhamento);

        
        //natureza da operação          
        $texto = "NATUREZA DA OPERAÇÃO";
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(5, 49);
        $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);

        $pdf->SetXY(5, 53);
        $texto = $arrOutput['infNFe']['ide']['natOp'];
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
        $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);
 
        $pdf->SetXY(5, 51);
        $pdf->SetFont($fontePadrao,'',7);     
        $borda = 1;
        $texto ='';
        $pdf->Cell(120,8,$texto,$borda,0,$alinhamento);

                
          $texto = "PROTOCOLO DE AUTORIZAÇÃO DE USO";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(125, 49);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(125, 53);
          $texto = $arrOutput['protNFe']['infProt']['nProt']."  ".$arrOutput['protNFe']['infProt']['dhRecbto'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(125, 51);
          $pdf->SetFont($fontePadrao,'',7);     
          $borda = 1;
          $texto ='';
          $pdf->Cell(75,8,$texto,$borda,0,$alinhamento);

          $texto = "INSCRIÇÃO ESTADUAL";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(5, 57);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
  
          $pdf->SetXY(5, 61);
          $texto =$arrOutput['infNFe']['emit']['IE'];
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
   
          $pdf->SetXY(5, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);

           
          $texto = "INSCRIÇÃO ESTADUAL DO SUBST. TRIBUT.";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(65, 57);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
     
          $pdf->SetXY(65, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(70,8,$texto,$borda,0,$alinhamento);

          $texto = "CNPJ";
          $pdf->SetFont($fontePadrao,'',6);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'L';
          $pdf->SetXY(135, 57);
          $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);
          $CNPJ = substr($arrOutput['infNFe']['emit']['CNPJ'],0,2).".".substr($arrOutput['infNFe']['emit']['CNPJ'],2,3).".".substr($arrOutput['infNFe']['emit']['CNPJ'],5,3)."/".substr($arrOutput['infNFe']['emit']['CNPJ'],8,4)."-".substr($arrOutput['infNFe']['emit']['CNPJ'],12,2).

          $pdf->SetXY(135, 61);
          $texto = $CNPJ;
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);      
          $pdf->Cell(60,8,$texto,$borda,0,$alinhamento);
     
          $pdf->SetXY(135, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(65,8,$texto,$borda,0,$alinhamento);

    
          $pdf->SetXY(135, 59);             
          $borda = 1;
          $texto ='';
          $pdf->Cell(65,8,$texto,$borda,0,$alinhamento);
 
        //DADOS PRODUTOS
        $texto = "DADOS DOS PRODUTOS / SERVIÇOS";
        $pdf->SetFont($fontePadrao,'B',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'L';
        $pdf->SetXY(4, 66);
        $pdf->Cell(6,8,$texto,$borda,0,$alinhamento);

         $altura = "72";

  
         $texto = "CÓD. PRODUTO";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'L';
         $pdf->SetXY(5, $altura);
         $pdf->Cell(18,8,$texto,$borda,0,$alinhamento);

         $texto = "DESCRIÇÃO DO PRODUTO";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'L';
         $pdf->SetXY(23, $altura); 
         $pdf->Cell(45,8,$texto,$borda,0,$alinhamento);             
         //  $pdf->MultiCell(75, 4,$texto, 0, 'L', 0,6, '', '', true, 0, false, true, 40, 'T');

         $texto = "NCM / SH";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(68, $altura);
         $pdf->Cell(15,8,$texto,$borda,0,$alinhamento);

         $texto = "CST";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(83, $altura);
         $pdf->Cell(5,8,$texto,$borda,0,$alinhamento);

         $texto = "CFOP";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(88, $altura);
         $pdf->Cell(7,8,$texto,$borda,0,$alinhamento);

         $texto = "UND";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(95, $altura);
         $pdf->Cell(7,8,$texto,$borda,0,$alinhamento);

         $texto = "QTDE";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(102, $altura);
         $pdf->Cell(12,8,$texto,$borda,0,$alinhamento);

         $texto = "VALOR UNIT";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(114, $altura);
         $pdf->Cell(15,8,$texto,$borda,0,$alinhamento);

         $texto = "VALOR TOTAL";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(129, $altura);
         $pdf->Cell(16,8,$texto,$borda,0,$alinhamento);

         $texto = "BC ICMS";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(145, $altura);
         $pdf->Cell(12,8,$texto,$borda,0,$alinhamento);

         $texto = "VALOR ICMS";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(157, $altura);
         $pdf->Cell(15,8,$texto,$borda,0,$alinhamento);

         $texto = "VALOR IPI";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(172, $altura);
         $pdf->Cell(12,8,$texto,$borda,0,$alinhamento);

         $texto = "ALIQUOTAS";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(184, $altura);
         $pdf->Cell(15,4,$texto,$borda,0,$alinhamento);

         $texto = "ICMS";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(184, $altura+4);
         $pdf->Cell(8,4,$texto,$borda,0,$alinhamento);

         $texto = "IPI-";
         $pdf->SetFont($fontePadrao,'B',6);     
         $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
         $borda = 1; $alinhamento = 'C';
         $pdf->SetXY(192, $altura+4);
         $pdf->Cell(7,4,$texto,$borda,0,$alinhamento);

          
     
          $i = 0;
          $CONTADORPAGINA++;
          $novacontagem = 1;
          $linhaM = 2;
          $ITEM = 0;
        //  $ULTIMOITEM = $ULTIMOITEM -2;
          //LOOP PRODUTOS
          foreach ($arrOutput['infNFe']['det'] as $value) {
           
            $ITEM++;     
          
                
            if ($i >= ($ULTIMOITEM) ) {
           
              if($linhaM <= 188) {    
                      $novacontagem++;
                      if( is_array($value)){
                        if( $_infAdProd == ""){
                          $_infAdProd  = $value['infAdProd'];
                        }        
                       
                      }else{     
                        if( $_infAdProd == ""){
                          $_infAdProd  = trim($value);
                       }
                      
                      }
              
              
                      if(count($value['@attributes']) > 0 ) { 
                                  
                                 
                                  $prod = 1;
                                // if( $prod == 0){
                                    $_codproduto  = $value['prod']['cProd'];
                                    $_codigoEan = $value['prod']['cEAN'];
                                    $_descricao = $value['prod']['xProd'];
                                  
                                
                                    $_cfop = $value['prod']["CFOP"];
                                    $_ncm = $value['prod']['NCM'];      
                                
                                    $_uni = $value['prod']['uCom'];
                                    $_qtde = $value['prod']['qCom'];
                                    //number_format($arrOutput['infNFe']['total']['ICMSTot']['vBC'], 2, ",", ".");    
                                    $_valorunit = number_format($value['prod']['vUnTrib'], 2, ",", ".");   
                                    $_valortotal = number_format($value['prod']['vProd'], 2, ",", "."); 
                                    if($_basecalculo == "")  {
              
                                   
                                    $_basecalculo = '0,00';
                                    $_valoricms = '0,00';
                                    $_valoripi = '0,00';
                                    $_aliIcms = '0,00';
                                    $_aliIpi = '0,00';
                                  }
                                // }
                              //   if( $prod == 1){
              
                                $_aliIpi = number_format($value['IPI']['IPITrib']['pIPI'], 2, ",", ".");  
                                $_valoripi = number_format($value['IPI']['IPITrib']['vIPI'], 2, ",", ".");
                                if($_aliIpiDEV != "") {
                                  $_aliIpi = $_aliIpiDEV;
                                  $_valoripi= $_aliIpiDEVvlr;
                                }
                       
                                $_cst  = $value['imposto']['ICMS']['ICMSSN102']['CSOSN'];
                                if($_cst == ""){
                                 $_cst  = $value['imposto']['ICMS']['ICMSSN500']['CSOSN'];
                              // $_cst  = '2500';
                                }
                                if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMSSN103']['CSOSN'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMSSN101']['CSOSN'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMSSN400']['CSOSN'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMSSN900']['CSOSN'];
                                 
                                  $_basecalculo = $value['imposto']['ICMS']['ICMSSN900']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMSSN900']['vICMS'];
                                  $_picms = $value['imposto']['ICMS']['ICMSSN900']['pICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS00']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS00']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS00']['vICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS00']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS00']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS00']['vICMS'];
                             
                                 }
                                if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS10']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS00']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS00']['vICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS20']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS20']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS20']['vICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS30']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS30']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS30']['vICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS41']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS41']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS41']['vICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS40']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS40']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS40']['vICMS'];
                             
                                 }
                                 if($_cst == ""){
                                  $_cst  = $value['imposto']['ICMS']['ICMS60']['CST'];
                                  $_basecalculo = $value['imposto']['ICMS']['ICMS60']['vBC'];
                                  $_valoricms = $value['imposto']['ICMS']['ICMS60']['vICMS'];
                             
                                 }
              
                               
                               
                                         //VERIFICAR OUTRAS CST
                                         if($TOTALREG > 0) {
                                          $sql3 = "SELECT situacaotributario_nfeitens,codigoproduto_nfeitens 
                                          FROM " . $_SESSION['BASE'] . ".NFE_ITENS
                                          INNER JOIN  " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = codigoproduto_nfeitens
                                          WHERE id_nfedados  = '".$_POST['id-nota']."' and $_COD  = '$_codproduto'";     
                                   
                                              $statement3 = $pdo->query($sql3);
                                                $retorno3 = $statement3->fetch();
                                             
                                                $_cstnovo =  $retorno3['situacaotributario_nfeitens'];
                                                if($_cstnovo !=""){
                                                  $_cst  = $_cstnovo; 
                                                //  $_cst  = '4900';
                                               //   $_cst  = '2500';
                                                }
                                             
                                   
                                         }
                               
              
                              //    }
                                  
                                
              
                      } else {
                                  if( $prod == 0){
                                      $_codproduto  = $value['cProd'];
                                      $_codigoEan = $value['cEAN'];
                                      $_descricao = $value['xProd'];
                                      $_cfop = $value["CFOP"];
                                      $_ncm = $value['NCM'];      
                                      
                                      $_uni = $value['uCom'];
                                      $_qtde = $value['qCom'];
                                      $_valorunit = number_format($value['vUnTrib'], 2, ",", ".");   
                                      $_valortotal = number_format($value['vProd'], 2, ",", ".");  
                                
                                      if($_basecalculo == "")  {
                                          $_basecalculo = '0,00';
                                          $_valoricms = '0,00';
                                          $_valoripi = '0,00';
                                          $_aliIcms = '0';
                                          $_aliIpi = '0';
                                      }
                                  }
                              
                                      if( $prod == 1){
                                    
                                        $_aliIpi = number_format($value['IPI']['IPITrib']['pIPI'], 2, ",", ".");
                                        $_valoripi = number_format($value['IPI']['IPITrib']['vIPI'], 2, ",", ".");
                                        if($_aliIpiDEV != "") {
                                          $_aliIpi = $_aliIpiDEV;
                                          $_valoripi= $_aliIpiDEVvlr;
                                        }
                    
                                        $_cst  = $value['ICMS']['ICMSSN102']['CSOSN'];
                                        if($_cst  == "" ) {
                                          $_cst  = $value['ICMS']['ICMSSN500']['CSOSN'];
                                        //  $_cst  = '2500';
                                        }
                                        if($_cst  == "") {
                                          $_cst  = $value['ICMS']['ICMSSN900']['CSOSN'];
                                          $_basecalculo = $value['ICMS']['ICMSSN900']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMSSN900']['vICMS'];
                                          $_aliIcms = number_format($value['ICMS']['ICMSSN900']['pICMS'], 2, ",", ".");   
                                          
                                        }
                                        if($_cst  == "") {
                                          $_cst  = $value['ICMS']['ICMSSN103']['CSOSN'];
                                        //  $_cst  = '2500';
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMSSN101']['CSOSN'];
                                    
                                        }
                                        if($_cst  == "") {
                                          $_cst  = $value['ICMS']['ICMSSN400']['CSOSN'];
                                        //  $_cst  = '2500';
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS00']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS00']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS00']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS10']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS00']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS00']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS20']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS20']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS20']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS30']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS30']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS30']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS40']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS40']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS40']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS41']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS41']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS41']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS60']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS60']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS60']['vICMS'];
                                    
                                        }
                                        if($_cst == ""){
                                          $_cst  = $value['ICMS']['ICMS90']['CST'];
                                          $_basecalculo = $value['ICMS']['ICMS90']['vBC'];
                                          $_valoricms = $value['ICMS']['ICMS90']['vICMS'];
                                          $_aliIcms = $value['ICMS']['ICMS90']['pICMS'];
                                    
                                        }
                                      
                                     
                                        //VERIFICAR OUTRAS CST
                                        if($TOTALREG > 0) {
                                          $sql3 = "SELECT situacaotributario_nfeitens,codigoproduto_nfeitens 
                                          FROM " . $_SESSION['BASE'] . ".NFE_ITENS
                                          INNER JOIN  " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = codigoproduto_nfeitens
                                          WHERE id_nfedados  = '".$_POST['id-nota']."' and $_COD  = '$_codproduto'";
                                      
                                              $statement3 = $pdo->query($sql3);
                                                $retorno3 = $statement3->fetch();
                                            
                                                $_cstnovo =  $retorno3['situacaotributario_nfeitens'];
                                                if($_cstnovo !=""){
                                                  $_cst  = $_cstnovo; 
                                                //  $_cst  = '4900';
                                              //   $_cst  = '2500';
                                                }
                                  
                                        }
                                        
                                      }
                                        
                   }
                                    
                            
                                    
                  if($_descricao != ""  and strlen($_descricao) > 2 and $value['impostoDevol']['pDevol'] == "" ) {                                                
                                    
                                if( $prod == 1) {                          
                              
                                      
                                      $linhaM = $linhaM+6;
                                            $texto = $_codproduto;
                                            $pdf->SetFont($fontePadrao,'',7);     
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'L';
                                            $pdf->SetXY(5, ($altura+$linhaM));
                                            $pdf->Cell(18,6,$texto,$borda,0,$alinhamento);
                            
                                            
                                            $texto = substr($_descricao,0,66);               
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'L';
                                            $pdf->SetXY(23, ($altura+$linhaM)); 
                                            /*
                                            if(strlen($_descricao) < 33 ) {
                                                $pdf->Cell(45,6,$texto,$borda,0,$alinhamento);   
                                            }else { 
                                                $pdf->MultiCell(45, 3,$texto, 1, 'L', 0,6, '', '', true, 0, false, true, 40, 'T');
                                            }
                                            */
                                          //            
                                          $pdf->MultiCell(45, 3,$texto, 0, 'L', false);
                                          $pdf->SetXY(23, ($altura+$linhaM)); 
                                          $pdf->MultiCell(45, 6,"", 1, 'L', false);    
                                            
                                            $texto = $_ncm;            
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(68, ($altura+$linhaM));
                                            $pdf->Cell(15,6,$texto,$borda,0,$alinhamento);
                                         
                                            $texto = $_cst;                  
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(83, ($altura+$linhaM));
                                            $pdf->Cell(5,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = $_cfop;                  
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(88, ($altura+$linhaM));
                                            $pdf->Cell(7,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = $_uni ;                
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(95, ($altura+$linhaM));
                                            $pdf->Cell(7,6,$texto,$borda,0,$alinhamento);
                                        
                                            $texto = $_qtde;                  
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(102, ($altura+$linhaM));
                                            $pdf->Cell(12,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = $_valorunit;                   
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(114, ($altura+$linhaM));
                                            $pdf->Cell(15,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = $_valortotal ;                    
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(129, ($altura+$linhaM));
                                            $pdf->Cell(16,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = "$_basecalculo"; //BC ICMS                   
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(145,($altura+$linhaM));
                                            $pdf->Cell(12,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = "$_valoricms"; //VALOR ICMS                    
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(157, ($altura+$linhaM));
                                            $pdf->Cell(15,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = "$_valoripi"; //VALOR IPI                  
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(172, ($altura+$linhaM));
                                            $pdf->Cell(12,6,$texto,$borda,0,$alinhamento);
                            
                                            
                                            $texto = "$_aliIcms"; //ICMS                  
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(184, ($altura+$linhaM));
                                            $pdf->Cell(8,6,$texto,$borda,0,$alinhamento);
                            
                                            $texto = "$_aliIpi"."$_aliIpiDEV"; //IPI             ;     
                                            $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                            $borda = 1; $alinhamento = 'C';
                                            $pdf->SetXY(192, ($altura+$linhaM));
                                            $pdf->Cell(8,6,$texto,$borda,0,$alinhamento);
              
                                         
                                            $i++; 
                                            $ULTIMOITEM++;
                                            $prod = 0;
                                        }
                                            $prod = 1;
                                            }
                                            if( $_infAdProd != "" and strlen($_infAdProd) > 2) {
                                              $linhaM = $linhaM+6;
                                              $TOTALINTESXML = $TOTALINTESXML + 1;
                                             // $texto =  $_infAdProd."--".$TOTALINTESXML ;               
                                              $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
                                              $borda = 1; $alinhamento = 'L';
                                              $pdf->SetXY(5,  ($altura+$linhaM));                    
                                              $pdf->Cell(194,6, $_infAdProd ,$borda,0,$alinhamento);   
                                              $_infAdProd  = "";
                                        
                                           }
                    }
                  }
                    else {
                      $i++;
                  }
                  
                }


            }


  $pdf->Output();// fechamos o arquivo


    
} catch (PDOException $e) {
    echo $e;
  
}

   
              
          
       
