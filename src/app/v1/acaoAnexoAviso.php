<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();

try {
    
    if($_POST["aviso-id"]!= ""){
        $arquivo_temp	=	$_FILES["arquivo-anexo"]["tmp_name"];	//CAMINHO TEMPORÁRIO
        $arquivo_name	=	$_FILES["arquivo-anexo"]["name"];		//NOME DO ARQUIVO
        $arquivo_size	=	$_FILES["arquivo-anexo"]["size"];		//TAMANHO DO ARQUIVO
        $arquivo_type	=	$_FILES["arquivo-anexo"]["type"];		//TIPO DO ARQUIVO
    }else{
        $arquivo_temp	=	$_FILES["arquivo-anexoI"]["tmp_name"];	//CAMINHO TEMPORÁRIO
        $arquivo_name	=	$_FILES["arquivo-anexoI"]["name"];		//NOME DO ARQUIVO
        $arquivo_size	=	$_FILES["arquivo-anexoI"]["size"];		//TAMANHO DO ARQUIVO
        $arquivo_type	=	$_FILES["arquivo-anexoI"]["type"];		//TIPO DO ARQUIVO
    }

$conteudo_arquivo = file_get_contents($arquivo_temp);


if($arquivo_name == "") { 
    echo "Selecione o Arquivo";
     exit(); 
    }

 $arqnome = explode('.',$arquivo_name);
$tipo = substr($arquivo_name,-3);

if($tipo == 'jpg'  or $tipo == "png"){
}else{
    echo "Selecione arquivo JPG ou PNG";
     exit(); 

}

if($arquivo_size > 999){
    $arquivo_size /= 1024;
}

if(round($arquivo_size) > 999){
    ?>          
    <div class="modal-content text-center">           
         <i class="fa fa-3x fa-times-circle-o"></i>
         <h4>OPS !!! arquivo não pode ser maior que 1MB (<?=round($arquivo_size);?>)</h4>     
     </div>
   <?php
   exit();
   
}
    
// Converte para base64
$base64 = base64_encode($conteudo_arquivo);

if($_POST["aviso-id"]!= ""){


   $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".avisos SET  av_imagem = ?  WHERE 	av_id = ?");
   $statement->bindParam(1, $base64);
   $statement->bindParam(2, $_POST["aviso-id"]);
   $statement->execute();
}else{

}
   ?>
  <img src="data:image/png;base64,<?=$base64;?>" class="img-responsive img-thumbnail" width="200" /> 
  <textarea  name="aviso-imagem" id="aviso-imagem"  class="form-control" style="display: none;"><?=$base64;?></textarea>
   <?php

} catch (PDOException $e) {
 
    ?>
    <div class="modal-content text-center">           
            <i class="fa fa-3x fa-times-circle-o"></i>
               <h4>ops!!! algo deu errado <?=$e;?></h4>     
    </div>
  <?php
}




