<?php 
session_start();

$servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
	$user_conect = 'admin';
	$senha = '';
	$banco_conect = 'bd_novacibras';
	$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);

$empresa = $_SESSION['BASE_ID'];

 $dia       = date("d"); 
 $mes       = date("m"); 
 $ano       = date("Y"); 

 $data_atual      = $dia."/".$mes."/".$ano; 

 $dataini = $_POST['dataini'];
 $dataini = "01/05/2023";
 $datafim = $_POST['datafim'];
 $datafim = "31/05/2023";

 if($dataini == "") { $dataini =  $data_atual ; $datafim = $data_atual  ; }
  $diaInicial = substr("$dataini",0,2); 
  $mesInicial = substr("$dataini",3,2); 
  $anoInicial = substr("$dataini",6,4); 
  $dataini = $anoInicial."-".$mesInicial."-".$diaInicial; 

  $diaFinal = substr("$datafim",0,2); 
  $mesFinal = substr("$datafim",3,2); 
  $anoFinal = substr("$datafim",6,4); 
  $datafim = $anoFinal."-".$mesFinal."-".$diaFinal; 

  $cliente = '9015';
 
 $dir = "arquivos/".$cliente;
	
	if(is_dir("nome_pasta"))
		{
			//echo "A Pasta Existe";
		}
		else
		{
			//echo "A Pasta não Existe";
			//mkdir(dirname(__FILE__).$dir, 0777, true);
			mkdir("arquivos/".$cliente."/", 0777, true);
			
		}
  

$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

foreach ( $ri as $file ) {
    $file->isDir() ?  rmdir($file) : unlink($file);
}

$registro = 0;

$sql = "SELECT nfed_xml_protocolado,nfed_chave
FROM bd_novacibras.NFE_DADOS    
WHERE nfed_xml_protocolado like '%CIBRAS%' and nfed_chave <> '' AND  nfed_cancelada = 0 AND             
nfed_modelo <> '55' and nfed_dataautorizacao >= '2023-05-01 00:00' AND nfed_dataautorizacao <= '2023-05-31 23:59:59' $fil";


$result = mysqli_query($mysqli, $sql)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {

	$Idchave = $rst['nfed_chave'];
// Abre ou cria o arquivo bloco1.txt
// "a" representa que o arquivo é aberto para ser escrito
$arquivo = "arquivos/".$cliente."/NFCe".$Idchave.".xml";


$fp = fopen($arquivo, "a");

// Escreve "exemplo de escrita" no bloco1.txt
$escreve = fwrite($fp, $rst['nfed_xml_protocolado']);

// Fecha o arquivo
fclose($fp); 
		}
	/*	
		$diretorio = "arquivos/".$cliente."/";

// Instancia a Classe Zip
$zip = new ZipArchive();
// Cria o Arquivo Zip, caso não consiga exibe mensagem de erro e finaliza script
if($zip->open($diretorio.'nfe'.$mesInicial.$anoInicial.'.zip', ZIPARCHIVE::CREATE) == TRUE)
{
// Insere os arquivos que devem conter no arquivo zip

$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if(substr($filename,-3) == "xml"){
    $zip->addFile($diretorio.$filename,$filename);
}}

//echo 'Arquivo criado com sucesso.';
}
else
{
exit('O Arquivo não pode ser criado.');
}

// Fecha arquivo Zip aberto
$zip->close();


 /*


$arquivo = 'nfe'.$mesInicial.$anoInicial.'.zip';
$diretorio = "arquivos/".$_SESSION['LOGADO']."/";
$filenameCSV = $diretorio.$mesInicial.$anoInicial.'.csv';
$filenameExcel = $diretorio.$mesInicial.$anoInicial.'.xls';

$fp = fopen($filenameCSV, "a");


 
  
	
	$escreve = fwrite($fp, "Numero da Nota;Serie;Mod;Data Emissao;Total Nota;Chave;Cnpj emitente;Nome;cpf/cnpj;nome;\n");
	

 
 
 
 $types = array( 'xml' );
$path = 'arquivos/'.$_SESSION['LOGADO'].'/';

$dir = new DirectoryIterator($path);
foreach ($dir as $fileInfo) {
    $ext = strtolower( $fileInfo->getExtension() );
	if ($ext == "xml") {
    $xml = simplexml_load_file($path.$fileInfo->getFilename());
	
	 $total = $total + (float)$xml->NFe->infNFe->total->ICMSTot->vNF;
	 $_chave = $xml->protNFe->infProt->chNFe;
	 $emissao = $xml->NFe->infNFe->ide->dhEmi;
	 $documento = $xml->NFe->infNFe->dest->CPF;
	 if($documento == "") { 
		$documento = $xml->NFe->infNFe->dest->CNPJ;
	 }
	
	 $emissao = substr($emissao,8,2)."/".substr($emissao,5,2)."/".substr($emissao,0,4);
	 $escreve = fwrite($fp, $xml->NFe->infNFe->ide->nNF.";"
	 .$xml->NFe->infNFe->ide->serie.";"
	 .$xml->NFe->infNFe->ide->mod.";"
	 .$emissao.";"
	 .$xml->NFe->infNFe->total->ICMSTot->vNF.";"
	 .'"'.$_chave.'";'
	 .$xml->NFe->infNFe->emit->CNPJ.";"
	 .$xml->NFe->infNFe->emit->xNome.";"
	 .$documento.";"
	 .$xml->NFe->infNFe->dest->xNome.";\n");
		
	}
}
 
  
	$escreve = fwrite($fp, ";;;TOTAL;$total\n"); 
	
 


// Fecha o arquivo
fclose($fp); 


include("PHPExcel/Classes/PHPExcel/IOFactory.php");
    $objReader = PHPExcel_IOFactory::createReader('CSV');
    $objReader->setDelimiter(";"); // define que a separa��o dos dados � feita por ponto e v�rgula
    $objReader->setInputEncoding('UTF-8'); // habilita os caracteres latinos.
    $objPHPExcel = $objReader->load($filenameCSV); //indica qual o arquivo CSV que ser� convertido
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($filenameExcel); // Resultado da convers�o; um arquivo do EXCEL  
 */
 
 
   



		