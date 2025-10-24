<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


include("../../api/config/conexaobase.php");

$_SESSION["BASE"] = "";
$servidor = 'p.com';
$user_conect = '';
$senha = '#';
$banco_conect = '';

$conn = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690

// Buscar todos os bancos de dados (exceto os internos do MySQL)
$result = $conn->query("SHOW DATABASES");
if (!$result) {
    die("Erro ao listar bancos de dados: " . $conn->error);
}

$ignorar = ['information_schema', 'mysql', 'performance_schema', 'sys', 'bd_gestorpet','info','bd_prisma','minhaos_cep'];
while ($row = $result->fetch_array()) {
    $database = $row[0];
  //  echo $database."<br>";

    if (in_array($database, $ignorar)) {
        continue;
    
    }
         $executa = "ALTER TABLE $database.financeiro ADD `financeiro_usucom` INT NOT NULL COMMENT 'usuario comissao O.S e vendas filtro' AFTER `financeiro_nsu`;";
         echo  $executa ;
       // $mysqli->query($executa);
         echo  $executa."<br>" ;
  }

       
      
          

exit();

	
				
?>	



		
	


