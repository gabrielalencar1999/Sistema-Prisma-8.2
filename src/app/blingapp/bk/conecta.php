<?php
// Conex閬攐 com banco de dados - PHP 7
//c贸digo desenvolvido por Maycon Braga - mayconbraga.com.br
//d煤vidas: mayconbraga@plataformafly.com.br

$servername = "localhost";
$username   = "";
$password   = "";
$db_name    = "";

$conexao = mysqli_connect($servername, $username, $password, $db_name);


$sql_access_token = mysqli_query($conexao,"SELECT * FROM token") or die("Erro");
$resultado_access_token	= mysqli_fetch_assoc($sql_access_token);

$access_token   = $resultado_access_token['access_token'];
$refresh_token  = $resultado_access_token['refresh_token'];
$client_id      = '';
$client_secret  = '';
?>