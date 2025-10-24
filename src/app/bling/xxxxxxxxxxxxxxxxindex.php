<?php /*
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
    
$pdo = MySQL::acessabd();
	 */


    // Capturar os parâmetros da query string
    $response_type = $_GET['response_type'] ;
    $client_id = $_GET['client_id'] ;
    $state = $_GET['state'];
    
    // Exibir a URL completa e os parâmetros capturados
echo 'URL completa: ' . htmlspecialchars($full_url) . '<br>';
echo 'response_type: ' . htmlspecialchars($response_type) . '<br>';
echo 'client_id: ' . htmlspecialchars($client_id) . '<br>';
echo 'state: ' . htmlspecialchars($state) . '<br>';
    // Exibir a URL completa e os parâmetros capturados
    $texto = $texto. 'URL completa: ' . htmlspecialchars($full_url) . '<br>';
    $texto = $texto. 'response_type: ' . htmlspecialchars($response_type) . '<br>';
    $texto = $texto. 'client_id: ' . htmlspecialchars($client_id) . '<br>';
    $texto = $texto. 'state: ' . htmlspecialchars($state) . '<br>';

$arquivo = fopen("input.txt", "a");
fwrite($arquivo, $texto);
fclose($arquivo);

echo "https://gestor.sistemaprisma.com.br/blingapp/callback?code=8521791563472106326454c818bd19f60febdcf3&state=".$state ;
/*

$_sql = "INSERT INTO bd_prisma.linkret  (retdata,rethora,retstring) 
VALUES (current_date(),now(),'$json')";	
$stm = $pdo->prepare($_sql);	
$stm->execute();
*/
