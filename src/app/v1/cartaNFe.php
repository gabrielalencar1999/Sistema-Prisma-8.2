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


try {

  $_id =  strip_tags(trim($_GET['idcartax910']));

 
     //buscar numero da OS
     $sq = "Select EMAIL,empresa_vizCodInt from " . $_SESSION['BASE'] . ".parametro";
     $statement = $pdo->query("$sq");
        $retorno = $statement->fetch();  
        $email = $retorno["EMAIL"];
        $_vizCodInterno = $retorno['empresa_vizCodInt'];
        if($_id != "" and $_SESSION['BASE'] != "") { 
          $sql = "SELECT *,date_format(evNfe_dataReg,'%d/%m/%Y') as dt,date_format(evNfe_dataReg,'%H:%i') as hr FROM " . $_SESSION['BASE'] . ".NFE_EVENTO WHERE evNfe_id = $_id";
          
        }else{
          $sql = "SELECT *,date_format(evNfe_dataReg,'%d/%m/%Y') as dt,date_format(evNfe_dataReg,'%H:%i') as hr FROM " . $_SESSION['BASE'] . ".NFE_EVENTO 
          WHERE evNfe_nidDados  = '".$_POST['id-nota']."' and  evNfe_nNEvento  = '".$_POST['xEvento']."'";

       }
       
     
        $statement = $pdo->query("$sql");
        $retorno = $statement->fetch();  
        $chave = $retorno["evNfe_nChave"];
        $protocolo = $retorno["evNfe_nProt"];
        $orgao = $retorno["evNfe_cUF"];
        $numeroNF = $retorno["evNfe_nNumero"];
        $motivo = $retorno["evNfe_xMotivo"];
        $datareg = $retorno["dt"];
        $horareg = $retorno["hr"];
        $evento = $retorno["evNfe_nNEvento"];
        $tipoevento = $retorno["evNfe_codigevento"];
        $serie = $retorno["evNfe_serie"];
        $emp = $retorno["evNfe_empresa"];
        

      //buscar dados empresa
      $sql2 = "SELECT * FROM " . $_SESSION['BASE'] . ".empresa
      WHERE empresa_id  = '".$emp."' ";
      $statement2 = $pdo->query("$sql2");
      $retorno2 = $statement2->fetch(PDO::FETCH_OBJ);

      $nomeEmitente = $retorno2->empresa_nome;
      $numrua = $retorno2->empresa_numero;
      $endereco = $retorno2->empresa_endereco." Nº ".$numrua;
      $bairro = $retorno2->empresa_bairro;
      $cep = $retorno2->Cep;
      $cidade = $retorno2->empresa_cidade;
      $estado = $retorno2->empresa_uf;
      $EMAIL = $retorno2->empresa_email;
      $inscricao = $retorno2->empresa_inscricao;
  
      $cnpj = preg_replace('/[^0-9]/', '', (string) $retorno2->empresa_cnpj);
      $cnpj_empresa = $cnpj;
      $cnpj_empresa = substr($cnpj_empresa, 0, 2) . '.' .
      substr($cnpj_empresa, 2, 3) . '.' .
      substr($cnpj_empresa, 5, 3) . '/' .
      substr($cnpj_empresa, 8, 4) . '-' .
      substr($cnpj_empresa, -2);
      $telefone = "(".substr($retorno2->empresa_telefone,0,2).")". substr($retorno2->empresa_telefone,2,11);

      if($empresa > 1){
      $logo  = $_SESSION['CODIGOCLI']."nf$empresa.jpg";
      }else{  
      $logo  = $_SESSION['CODIGOCLI']."nf.jpg";
      }
        $caminhoLogo = "../../logos/$logo";
        

        
        

      //  $id = $danfe->montaDANFE();
     //   $pdf = $danfe->render();



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
  //####################################################################################
 
  $pdf = new FPDF("P", "mm", array(210,297));
  $pdf->AddPage(); //Acrescenta uma página ao arquivo
  $pdf->SetAutoPageBreak(false,0);
  $pdf->Image($caminhoLogo ,29,15, 30);
  $pdf->SetFont('Arial','B',16); //Define o estilo da fonte, características como Negrito(bold), Itálico ou Sublinhado(U), verifique quais fontes a sua biblioteca utiliza.
 

       //Dados da NF do cabeçalho
       $texto = "CNPJ: ";       
       $texto .= "$cnpj_empresa";
       $pdf->SetFont($fontePadrao,'',10);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 1; $alinhamento = 'C';
       $pdf->SetXY(5, 5);
       $pdf->Cell(80,7,$texto,$borda,0,$alinhamento);

        //dados e logo
        $texto = $nomeEmitente;//$emitente;   
        $pdf->SetFont($fontePadrao,'',7);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 28);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

        $texto = "$endereco";   
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 31);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

       
        $CEP  = substr($CEP,0,5)."-".substr($CEP,5,3);
        $texto = "$bairro - ".$cidade." - ".$estado. " - ".$CEP;   
        $pdf->SetFont($fontePadrao,'',6);     
        $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
        $borda = 0; $alinhamento = 'C';
        $pdf->SetXY(5, 34);
        $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);

        $texto = $email;
        if($email != "") {
          $pdf->SetFont($fontePadrao,'',7);     
          $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
          $borda = 0; $alinhamento = 'C';
          $pdf->SetXY(5, 37);
          $pdf->Cell(80,10,$texto,$borda,0,$alinhamento);
        }
        



       $texto = "CARTA DE CORREÇÃO ELETRÔNICA"; //QUADRADO NUMERO NF SUPERIOR      
       $pdf->SetFont($fontePadrao,'B',12);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(75, 8);
       $pdf->Cell(140,10,$texto,$borda,0,$alinhamento);

     

       $pdf->SetXY(85, 5);
       $borda = 1;
       $texto = "";
       $pdf->Cell(115,16,$texto,$borda,0,$alinhamento);

       


       

       $pdf->SetXY(5, 12);
       $borda = 1;
       $texto = "";
       $pdf->Cell(80,39,$texto,$borda,0,$alinhamento);



       //COdigo barra
     
       $chave_acesso = $chave;   
       $pdf->SetFont($fontePadrao,'',7);     
       $borda = 0; $alinhamento = 'C';
     //  $texto = "|||||||||||||||||||||||||||||||||||||";
       $pdf->SetXY(50, 25);
      // $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);
       //codigo de barras
     // $pdf->Code128($x+(($w-$bW)/2),$y+2,$chave_acesso,$bW,$bH);
       //$pdf->Image("../../logos/Code128code.png" ,127,22,70);

       $code = $chave_acesso; 
       $pdf->Code128(127,22,$code,70,13);
       $pdf->SetXY(50,45);

       $texto = 'CHAVE DE ACESSO';
       $pdf->SetFont($fontePadrao,'b',7);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(85, 33);
       $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);

       $texto =  Mask("#### #### #### #### #### #### #### #### #### #### ####",$chave);
       $pdf->SetFont($fontePadrao,'',9);     
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(85, 36);
       $pdf->Cell(35,10,$texto,$borda,0,$alinhamento);


       $texto = 'PROTOCOLO ';
       $pdf->SetFont($fontePadrao,'b',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(85, 45);      
       $pdf->MultiCell(75, 2,  $texto, 0,$alinhamento,false);

       $texto = $protocolo;
       $pdf->SetFont($fontePadrao,'',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(85, 48);      
       $pdf->MultiCell(75, 2,  $texto, 0,$alinhamento,false);


       $texto = 'DATA/HORA AUTORIZAÇÃO ';
       $pdf->SetFont($fontePadrao,'b',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(145, 45);      
       $pdf->MultiCell(75, 2,  $texto, 0,$alinhamento,false);

       $texto = "$datareg AS $horareg";
       $pdf->SetFont($fontePadrao,'',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(145, 48);      
       $pdf->MultiCell(75, 2,  $texto, 0,$alinhamento,false);
     

       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(85, 21);
       $borda = 1;
       $texto = "";
       $pdf->Cell(115,15,$texto,$borda,0,$alinhamento);


       //chave acesso        
       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(85, 36);
       $borda = 1;
       $texto = "";
       $pdf->Cell(115,7,$texto,$borda,0,$alinhamento);

            
       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(85, 43);
       $borda = 1;
       $texto = "";
       $pdf->Cell(115,8,$texto,$borda,0,$alinhamento);


        //segunda linha     
       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(5, 51);
       $borda = 1;
       $texto = "";
       $pdf->Cell(50,15,$texto,$borda,0,$alinhamento);
       $texto = 'NÚMERO NF';
       $pdf->SetFont($fontePadrao,'B',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(5, 52);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);
       $texto = $numeroNF;
       $pdf->SetFont($fontePadrao,'',10);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(5, 59);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);


       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(55, 51);
       $borda = 1;
       $texto = "";
       $pdf->Cell(50,15,$texto,$borda,0,$alinhamento);
       $texto = 'SÉRIE';
       $pdf->SetFont($fontePadrao,'B',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(50, 52);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);
       $texto = $serie;
       $pdf->SetFont($fontePadrao,'B',10);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(50, 59);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);

       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(105, 51);
       $borda = 1;
       $texto = "";
       $pdf->Cell(50,15,$texto,$borda,0,$alinhamento);
       $texto = 'TIPO EVENTO';
       $pdf->SetFont($fontePadrao,'B',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(105, 52);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);
       $texto = $tipoevento;
       $pdf->SetFont($fontePadrao,'B',10);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(105, 59);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);


       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(155, 51);
       $borda = 1;
       $texto = "";
       $pdf->Cell(45,15,$texto,$borda,0,$alinhamento);
       $texto = 'EVENTO';
       $pdf->SetFont($fontePadrao,'B',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(155, 52);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);
       $texto = $evento;
       $pdf->SetFont($fontePadrao,'B',10);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'C';
       $pdf->SetXY(155, 59);      
       $pdf->MultiCell(50, 2,  $texto, 0,$alinhamento,false);



       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(5, 66);
       $borda = 1;
       $texto = "";
       $pdf->Cell(195,50,$texto,$borda,0,$alinhamento);
       $texto = 'DESCRIÇÃO DO EVENTO CARTA DE CORREÇÃO';
       $pdf->SetFont($fontePadrao,'B',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(6, 66);      
       $pdf->MultiCell(180, 10,  $texto, 0,$alinhamento,false);
       $texto = $motivo;
       $pdf->SetFont($fontePadrao,'',9);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(6, 74);      
       $pdf->MultiCell(180, 4,  $texto, 0,$alinhamento,false);


       
       $borda = 1; $alinhamento = 'L';
       $pdf->SetXY(5, 116);
       $borda = 1;
       $texto = "";
       $pdf->Cell(195,25,$texto,$borda,0,$alinhamento);
       $texto = 'CONDIÇÃO DE USO DA CARTA DE CORREÇÃO';
       $pdf->SetFont($fontePadrao,'B',7);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(6, 114);      
       $pdf->MultiCell(180, 10,  $texto, 0,$alinhamento,false);
       $texto = "A Carta de Correcao e disciplinada pelo paragrafo 1o-A do art. 7o do Convenio S/N, de 15 de dezembro de 1970 e pode ser utilizada para regularizacao de erro ocorrido na emissao de documento fiscal, desde que o erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da operacao ou da prestacao; II - a correcao de dados cadastrais que implique mudanca do remetente ou do destinatario; III - a data de emissao ou de saida.";
       $pdf->SetFont($fontePadrao,'',9);   
       $texto =   iconv('UTF-8', 'ISO-8859-1', $texto);
       $borda = 0; $alinhamento = 'L';
       $pdf->SetXY(6, 120);      
       $pdf->MultiCell(190,4, $texto, 0,$alinhamento,false);




    $pdf->Output();// fechamos o arquivo


    
} catch (PDOException $e) {
    echo $e;
  
}

   
              
          
       
