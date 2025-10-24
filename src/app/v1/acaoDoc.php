<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
use Functions\Acesso;

$pdo = MySQL::acessabd();

$_retviewerFoto = Acesso::customizacao('14'); //retorno = 1 bloqueia fotos status da O.S atualizado

date_default_timezone_set('America/Sao_Paulo');

$arquivo_temp	=	$_FILES["input"]["tmp_name"];	//CAMINHO TEMPORÁRIO
$arquivo_name	=	$_FILES["input"]["name"];		//NOME DO ARQUIVO
$arquivo_size	=	$_FILES["input"]["size"];		//TAMANHO DO ARQUIVO
$arquivo_type	=	$_FILES["input"]["type"];		//TIPO DO ARQUIVO

$mes = date("m");
$data = date("Y-m-d-His");


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

$_OS = $_POST["_idos"]; 
$arqnome = explode('.',$arquivo_name);
$_arquivo  = $_SESSION['CODIGOCLI']."_".$data."_".$arqnome[0];
$_caminho = "../docs/".$_SESSION['CODIGOCLI']."/";
$arquivo_caminho = "../docs/".$_SESSION['CODIGOCLI']."/$_arquivo";
/*
$exif = exif_read_data($_FILES["input"]["name"], 0, true);
foreach ($exif as $key => $section) {
    foreach ($section as $name => $val) {
        echo "$key.$name: $val<br />\n";
    }
}
echo "xxxx";
exit();
*/
$_liberado = 1;

if($_retviewerFoto == '0' or $_retviewerFoto == ''){ 
    $_liberado = 1;
}else{
    $query = ("SELECT trackO_id  FROM ".$_SESSION['BASE'].".trackOrdem   WHERE   trackO_chamada = '".$_OS."' and datahora_trackfim = '0000-00-00 00:00:00' ORDER BY trackO_id DESC ");

    $stm = $pdo->prepare($query);                      	
    $stm->execute();	       
          if ( $stm->rowCount() > 0 ){
            $_liberado = 1;
          }else{
            $_liberado = 0;
            ?>
            
                        <div class="bg-icon pull-request">
                            <i class="fa fa-3x fa-times-circle-o text-danger"></i>
                            <h4>OPS! Foto bloqueada,atendimento já finalizado</h4> 
                        </div>
            
            <?PHP 
          }
}

if($_liberado == '1'){ 
    try {

    if(is_dir($_caminho))
    {
       
            if (isset($_POST['imgBase64'])) {
                if (strpos($_POST['imgBase64'], "data:image/png;base64,") === 0) {
                    $arquivo_caminho = $arquivo_caminho.".png";
                    $tipo = "png";
               $fd = fopen($arquivo_caminho , "wb"); //png
                    $data = base64_decode(substr($_POST['imgBase64'], strlen("data:image/png;base64,")));
                } else if (strpos($_POST['imgBase64'], "data:image/jpg;base64,") === 0) {
                    $arquivo_caminho = $arquivo_caminho.".jpg";
                   
                    $tipo = "jpg";
                    $fd = fopen($arquivo_caminho, "wb"); //jpeg
                    $data = base64_decode(substr($_POST['imgBase64'], strlen("data:image/jpg;base64,")));
                }
            
                if ($fd) {
                    fwrite($fd, $data);
                    fclose($fd);
             
                   // echo "Resized image saved on server";
                } else {
                   // echo "Error crating the file on server";
                }
             
               
             }

            $insertArquivo = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".foto(
                arquivo_data,arquivo_OS,arquivo_imagem,arquivo_tipo)
            VALUES ('$data_hora', ?, ?, ?)");
            $insertArquivo->bindParam(1, $_OS);
             $insertArquivo->bindParam(2,$arquivo_caminho);
             $insertArquivo->bindParam(3, $tipo);
            $insertArquivo->execute();
        //}
    }
    else
    {
        //echo "A Pasta não Existe";
        //mkdir(dirname(__FILE__).$dir, 0777, true);
        mkdir("../docs/".$_SESSION['CODIGOCLI']."/", 0777, true);
        
    }

   

    } catch (PDOException $e) {
        echo $e;
    
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
          //      $src = 'data:image/'.$_tipo.';base64,'.$_img;

                ?>
                    <img src="<?=$_img;?>" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfoto('<?=$_idos;?>','<?=$_idref;?>')">
                <?php
               
             }   		
			}	
         
   
              
          
       
