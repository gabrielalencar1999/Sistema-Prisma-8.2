<?php

use Database\MySQL;
use Functions\Configuracoes;
$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}



$certificado = $_FILES['arquivo-certificado'];
$_FILES['arquivo-certificado'] = !empty($_FILES['arquivo-certificado']) ? base64_encode(file_get_contents($_FILES['arquivo-certificado']["tmp_name"])) : '';
$certificado =$_FILES['arquivo-certificado'] ;
$certificadoSenha = $_POST['senha-certificado'];
$empresa = $_POST['empresa'];

 //certificado
 if($certificado != ""){

    $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'] .".empresa SET
    arquivo_certificado_base64 = ?
    WHERE empresa_id = '".$empresa ."'");
    $statement->bindParam(1, $certificado);    
    $statement->execute();   
}
//SENHA certificado
if($certificadoSenha != ""){
    $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".empresa SET
    senha_certificado = ?
    WHERE empresa_id = '".$empresa ."'");
    $statement->bindParam(1, $certificadoSenha);    
    $statement->execute();           
}


