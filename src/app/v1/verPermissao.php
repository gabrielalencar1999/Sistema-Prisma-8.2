<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;
$pdo = MySQL::acessabd();

$permissao = $_POST['permissao'];

$stm = $pdo->prepare("select * from ".$_SESSION['BASE'].".telas_acesso 
where tela_descricao = '$permissao' 
and tela_user = '".$_SESSION['tecnico']."' ");
$stm->execute();
if($stm->rowCount() == 0){
    echo 'Desculpe, você não possuí permissão para acessar!';
}


