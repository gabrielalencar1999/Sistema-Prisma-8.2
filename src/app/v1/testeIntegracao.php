<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


exit(); //

use Database\MySQL;

$pdo = MySQL::acessabd();

use Functions\APIecommerce;

date_default_timezone_set('America/Sao_Paulo');

try {


    $codigoFornecedor = '200235';
    $vlrcusto = "19.20";
    $vlrvenda = "35.00";
    $qtde = "1";
    $operacao = "B";
    $operacao = "E";
    $operacao = "S";
    $obs = "venda 123";

    $_ret = APIecommerce::bling_saldoEstoque($codigoFornecedor,$vlrcusto,$vlrvenda,$qtde, $operacao,$obs);

    print_r($_ret);

     

} catch (PDOException $e) {
    echo $e;
  
}


         
   
              
          
       
