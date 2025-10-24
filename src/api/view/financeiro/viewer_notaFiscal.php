<?php 

use Database\MySQL;
use Functions\NFeFocus;

$pdo = MySQL::acessabd();

$_ref = explode("-",$_parametros['_chaveid']);


try{

        $statement = $pdo->prepare("SELECT nfse_url 
        FROM bd_gestorpet.notas 
        WHERE 
        nf_empresaemissao = ? and 
        nf_empresaid = ? and 
        nf_controle = ? and 
        nf_livro = ? ");            

        
        $statement->bindParam(1, $_ref['0']);
        $statement->bindParam(2, $_ref['2']);
        $statement->bindParam(3, $_ref['3']);
        $statement->bindParam(4, $_ref['4']);
        $statement->execute();
      
        $response =  $statement->fetchAll(\PDO::FETCH_OBJ);
        echo $response[0]->nfse_url;

      
    }
    catch (\Exception $fault){
                   // $response = $fault;
                  
    }
  
?>