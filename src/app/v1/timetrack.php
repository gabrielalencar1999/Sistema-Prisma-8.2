<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();

if($_SESSION['LOGADO'] == '' or $_SESSION['BASE']== ''){   ?> 
   <div style="padding-top: 80px ;" class="alert alert-danger alert-dismissable"> <span ><strong>SUA SESSÃO EXPIROU !!!</strong></span></div>
   <?php    exit();
 }

$_idatendimento = $_POST["_id"];

   //buscar dados 
   $sql = "Select 
   DATE_FORMAT(TIMEDIFF(now(), datahora_trackini),'%H:%i') as dif 
   from " . $_SESSION['BASE'] . ".trackOrdem                                  
   where  
   trackO_id =  '$_idatendimento'  ";     
   $stm = $pdo->prepare("$sql");                   
      
   $stm->execute();	
      
         if ( $stm->rowCount() > 0 ){
         
             while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
           {
              echo  $linha->dif;  

           }
        }

                ?>