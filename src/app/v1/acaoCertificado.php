<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


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

$arquivo_name	=	$_FILES["arquivo-certificado"]["name"];		//NOME DO ARQUIVO
$tipo = substr($arquivo_name,-3);
if($tipo == 'exe'  or $tipo == "EXE" or $arquivo_type == 'application/x-msdownload'){
       
    ?>          
    <div class="modal-content text-center">           
         <i class="fa fa-3x fa-times-circle-o"></i>
         <h4>OPS !!! arquivo <?=$tipo;?> não permitido</h4>     
     </div>
   <?php
   exit();
}
$_FILES['arquivo-certificado'] = !empty($_FILES['arquivo-certificado']) ? base64_encode(file_get_contents($_FILES['arquivo-certificado']["tmp_name"])) : '';
$certificado =$_FILES['arquivo-certificado'] ;
$certificadoSenha = $_POST['senha_certificado'];
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
?>
<div class="alert alert-success alert-dismissable " style="margin-top: 5px; text-align: center ; " >
                                <h4 >Atualizado com sucesso </h4>								
                                </div> 
                                
                           


