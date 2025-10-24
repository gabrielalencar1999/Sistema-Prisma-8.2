<?php
require_once('../../../api/config/config.inc.php');



$servername = MAIN_DB_HOST;
$username   = MAIN_DB_USER;
$password   = MAIN_DB_PASS;
$db_name    = "bd_prisma";
$conexao = mysqli_connect($servername, $username, $password, $db_name);

$sql = mysqli_query($conexao,"SELECT client_id,client_secret FROM info.consumidor where  CODIGO_CONSUMIDOR = '9071'") or die("Erro autenticação base ");
$resultado	= mysqli_fetch_assoc($sql);

$client_id      = $resultado['client_id'];// 'b9853dca759bbf19c1d291ba41085c377eb6db99';
$client_secret  = $resultado['client_secret'];//'c931c104d8bfe576ffb61e91dc82828139e4097f3e2a83aa7724381704d8';

$sql_access_token = mysqli_query($conexao,"SELECT * FROM token where  refresh_token = '' AND idlogin = '9071' ") or die("Erro autenticação base ");
$resultado_access_token	= mysqli_fetch_assoc($sql_access_token);

$access_token   = $resultado_access_token['access_token'];
$refresh_token  = $resultado_access_token['refresh_token'];
?>