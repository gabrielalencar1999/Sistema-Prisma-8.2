<?php 

require_once('../../../api/config/config.inc.php');
require FILE_BASE_API . '/vendor/autoload.php';

$cep = $_GET['cep'];

$cep =  str_replace(".", "", $cep);
$cep =  str_replace(",", "", $cep);
$cep =  str_replace("-", "", $cep);
$cep =  str_replace("/", "", $cep);

use Database\MySQL;

$pdo = MySQL::acessabd();

try {
	
	$stm = $pdo;
	
	$sql = "SELECT * FROM minhaos_cep.cep WHERE Cep = ? ";
	$stm = $pdo->prepare($sql);	
	$stm->bindParam(1, $cep, \PDO::PARAM_INT, 15);
	$stm->execute();

if($stm->rowCount() > 0){	
	
	$result = $stm->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $value){	
		$Cidade = $value['Cidade'];
		$Rua = $value['Rua'];
		$Bairro = $value['Bairro'];
		$UF = $value['UF'];
	}

}else{
	
	function buscaEndereco($cepDestino){
		$cep = preg_replace("/[^0-9]/","",$cepDestino);
		$url = "http://viacep.com.br/ws/$cep/xml/";
		$xml = simplexml_load_file($url);
		return $xml;
	}

	$endereco = (buscaEndereco($cep));
	
	$Rua = $endereco->logradouro;
	$Bairro = $endereco->bairro;
	$Cidade = $endereco->localidade;
	$UF = $endereco->uf;

	
	if($Rua != ""){
		
		$sql = "Insert into minhaos_cep.cep (Cep,UF,Cidade,Rua,Bairro) values('$cep','$UF','$Cidade','$Rua','$Bairro')";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		
	}
	
}
	

	echo("$Rua|$Bairro|$Cidade|$UF");

}catch (\Exception $fault){  }
