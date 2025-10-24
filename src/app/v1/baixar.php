<?php 
 
 session_start();
 require_once('../../api/config/config.inc.php');
 require '../../api/vendor/autoload.php';
 include("../../api/config/iconexao.php");
 
 use Database\MySQL;
 
 $pdo = MySQL::acessabd();
 
 $cliente = $_SESSION['BASE_ID'];

 $Idchave = $_GET['id'];

 $arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/NFe".$Idchave.".xml";

 $sql= "SELECT nfed_xml_protocolado FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_chave = '$Idchave' ";
 $statement = $pdo->query("$sql");
 $retorno = $statement->fetchAll();
 
 foreach ($retorno as $row) {  
     $_xml =$row['nfed_xml_protocolado'];
 }

 $dir = "docs/".$_SESSION['CODIGOCLI'];
	
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


$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
fwrite($fp,trim($_xml));
fclose($fp); 


  if(isset($arquivo_caminho) && file_exists($arquivo_caminho)){ // faz o teste se a variavel não esta vazia e se o arquivo realmente existe
     
    header("Content-Type: application/xml"); // informa o tipo do arquivo ao navegador
    header("Content-Length:".filesize($arquivo_caminho)); // informa o tamanho do arquivo ao navegador
    header("Content-Disposition: attachment;filename=".basename($arquivo_caminho)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
	 readfile($arquivo_caminho); // lê o arquivo
      exit;
      }?>