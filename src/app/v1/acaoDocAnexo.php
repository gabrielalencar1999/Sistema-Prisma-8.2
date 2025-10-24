<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

$mes = date("m");
$data = date("Y-m-d-His");
$datahora = date("Y-m-d H:i:s");


try {
    
$arquivo_temp	=	$_FILES["arquivo-anexo"]["tmp_name"];	//CAMINHO TEMPORÁRIO
$arquivo_name	=	$_FILES["arquivo-anexo"]["name"];		//NOME DO ARQUIVO
$arquivo_size	=	$_FILES["arquivo-anexo"]["size"];		//TAMANHO DO ARQUIVO
$arquivo_type	=	$_FILES["arquivo-anexo"]["type"];		//TIPO DO ARQUIVO

$_OS = $_POST["_idosanexo"]; 
$arqnome = explode('.',$arquivo_name);
$tipo = substr($arquivo_name,-3);
$_arquivo  = $_SESSION['CODIGOCLI']."_".$data."_".$arqnome[0];
$_caminho = "../docs/".$_SESSION['CODIGOCLI']."/";
$arquivo_caminho = "../docs/".$_SESSION['CODIGOCLI']."/$_arquivo";

    if($tipo == 'exe'  or $tipo == "EXE" or $arquivo_type == 'application/x-msdownload'){
       
        ?>          
        <div class="modal-content text-center">           
             <i class="fa fa-3x fa-times-circle-o"></i>
             <h4>OPS !!! arquivo EXE não permitido</h4>     
         </div>
       <?php    

    }else{
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
           
        }else{
        
               if(is_dir($_caminho))
            {

                if (!copy("$arquivo_temp", "$arquivo_caminho.$tipo")) {
                    $errors= error_get_last();
                    ?>
                    <div class="modal-dialog">
                        <div class="modal-content text-center">
                            <div class="modal-body" id="imagem-carregando">
                                <div class="bg-icon pull-request">
                                    <i class="fa fa-3x fa-times-circle-o"></i>
                                    <h4><?php var_dump($_REQUEST); ?> ops!!! algo deu errado <?php //"COPY ERROR: ".$errors['type']?></h4>
                                   
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                   
                }else{

              
                $_arq = $arquivo_caminho.".".$tipo;
                $insertArquivo = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".foto(
                    arquivo_data,arquivo_OS,arquivo_imagem,arquivo_tipo,arquivo_size,arquivo_nome)
                VALUES ('$datahora', ?, ?, ?, ?,?)");
                $insertArquivo->bindParam(1, $_OS);
                $insertArquivo->bindParam(2, $_arq);
                $insertArquivo->bindParam(3, $tipo);
                $insertArquivo->bindParam(4, $arquivo_size);
                $insertArquivo->bindParam(5, $arquivo_name);
                $insertArquivo->execute();
            }

            }
            else
            {

                mkdir("../docs/".$_SESSION['CODIGOCLI']."/", 0777, true);
                
            }
        }

    }
   
    //buscar dados 
    $sql = "Select * from ".$_SESSION['BASE'].".foto where arquivo_OS = '".$_OS."'  ";     
    $stm = $pdo->prepare("$sql");                   
   	
    $stm->execute();	
       
          if ( $stm->rowCount() > 0 ){
          
              while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
			{
                $_img = $linha->arquivo_imagem; 
                $_tipo =  $linha->arquivo_tipo; 
                $_idref = $linha->arquivo_id;  
                $_idos = $linha->arquivo_OS;  
                $_nome = $linha->arquivo_nome;
          //      $src = 'data:image/'.$_tipo.';base64,'.$_img;

          if($_tipo == 'GIF' or
          $_tipo == 'gif' or
          $_tipo == 'jpg' or
          $_tipo == 'jpeg' or
          $_tipo == 'JPG' or
          $_tipo == 'JPEG' or
          $_tipo == 'png' or
          $_tipo == 'PNG'  ){
              ?>
                <a href="<?=$_img;?>" target="_blank"><img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" ></a>
          <?php
              
          }else{
              if($_tipo == 'pdf'  or $_tipo == 'PDF' ) {
                  $icone = "fa-file-pdf-o";
              }
              if($_tipo == 'XLS'  or $_tipo == 'xls' or $_tipo == 'xlsx' or $_tipo == 'XLSX') {
                  $icone = " fa-file-excel-o";
              }
              if($_tipo == 'doc'  or $_tipo == 'DOC' or $_tipo == 'docx' or $_tipo == 'DOCX') {
                  $icone = "fa-file-word-o";
              }
              if($icone == ""){
                  $icone = " fa-file";
              }
            
           
             
              ?>
             <a href="<?=$_img;?>" target="_blank"> <button type="button" class="btn btn-icon waves-effect waves-light "> <i class="fa <?=$icone;?>"></i> <?=$_nome;?> </button></a>
              
          <?php

          }
             }   		
			}	
  
   

} catch (PDOException $e) {
 
    ?>
    <div class="modal-content text-center">           
            <i class="fa fa-3x fa-times-circle-o"></i>
               <h4>ops!!! algo deu errado <?=$e;?></h4>     
    </div>
  <?php
}




