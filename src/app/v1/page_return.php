<?php
session_start();
header("Content-type: text/html; charset=utf-8");
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Functions\Acesso;
use Database\MySQL;
$pdo = MySQL::acessabd();

function serializeToArray($data){
    foreach ($data as $d) {
        if( substr($d["name"], -1) == "]" ){
            $d["name"] = explode("[", str_replace("]", "", $d["name"]));
            switch (sizeof($d["name"])) {
                case 2:
                    $a[$d["name"][0]][$d["name"][1]] = $d["value"];
                break;

                case 3:
                    $a[$d["name"][0]][$d["name"][1]][$d["name"][2]] = $d["value"];
                break;

                case 4:
                    $a[$d["name"][0]][$d["name"][1]][$d["name"][2]][$d["name"][3]] = $d["value"];
                break;
            }
        }else{
            $a[$d["name"]] = $d["value"];
        } // if
    } // foreach

    return $a;
}




$_chaveform = strip_tags(trim($_POST['_keyform']));

if($_chaveform == 'REGPONTO_0002') { 
    
    $_parametros = serializeToArray(json_decode($_POST['dados'],true));

}else {


if (isset($_POST['_var'])) {
    $variable = strip_tags(trim($_POST['_var']));
}
//$_parametros = serializeToArray(json_decode(stripslashes($_POST['dados']),true));

$_parametros = serializeToArray(json_decode($_POST['dados'],true));
if (isset($_POST["acao"])) {
    $acao = strip_tags(trim($_POST['acao']));

  
}
if (isset($_POST["numOS"])) {
    $numOS = strip_tags(trim($_POST['numOS']));
}

$idtecsession = "";
$idtecsession2 = "";

$idtecsession = isset($_parametros['idtecsession']);
$idtecsession2 =  isset($_parametros['idtecsession2']);

if( isset($_POST['idtecsession']) != '' or $idtecsession2 != '' or $idtecsession !="" ){
    if($_parametros['idtecsession'] != "") {
        $idtecsession = $_parametros['idtecsession'];
    }else{
        if($_POST['idtecsession'] != "") {
            $idtecsession = $_POST['idtecsession'];
        }else{
            $idtecsession = $_parametros['idtecsession2'];
        }
      
    }
   
}else{
    $idtecsession = base64_encode($_SESSION["tecnico"].";".$_SESSION['CODIGOCLI'].";".$_SESSION["nivel"]);
}
$idtecsessionhist = $idtecsession.$_SESSION['BASE'];

if($_SESSION['LOGADO'] == '' or $_SESSION['BASE']== ''){ 
   
    if($idtecsession != "") { 
        $idtecsession = explode(";",base64_decode($idtecsession ));
        $idtecsessionhist = "$idtecsession[1]";
    }
  
  //verifica 
  $url = explode("?",  $_SERVER["REQUEST_URI"]);
 
  if($url[1] != "" or $idtecsession[1] != "") {
if($idtecsession != "") {

}else{
    $url = explode("=",$url[1]);
    $idtecsession = explode(";",base64_decode($url[1]));
}

   

                //fazer login novamente
                      //buscar dados da base informatica
       
         $stm = $pdo->prepare("Select consumidor_base,Nome_Fantasia from info.consumidor where CODIGO_CONSUMIDOR = '".$idtecsession[1]."'");
         $stm->execute();
         foreach ($stm->fetchAll() as $rstP) {
            $_SESSION['CODIGOCLI'] = $idtecsession[1];
            $_SESSION['BASE'] = $rstP['consumidor_base'];
            $_SESSION['fantasia'] = $rstP['Nome_Fantasia'];

         }
        
        
      
         //BUSCAR SESSION
         $_SESSION['LOGADO'] = $idtecsession[1];
         $sql_sel = "SELECT * FROM ".$_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '".$idtecsession[0]."'";    						
         $stm = $pdo->prepare($sql_sel);
         $stm->execute();
         //Testas as condicoes
         foreach ($stm->fetchAll() as $rst_sel) {  
            $_SESSION["tecnico"]  = $rst_sel['usuario_CODIGOUSUARIO'];       
            $_SESSION['APELIDO'] = $rst_sel['usuario_APELIDO'];
            $idUser =  $rst_sel['usuario_CODIGOUSUARIO'];
            $_SESSION["login"] = $rst_sel['usuario_LOGIN'];
            $_SESSION["nivel"] = $rst_sel['usuario_tecnico']; // 1 tecnico
            $_SESSION["perfil"] =$rst_sel['usuario_perfil2'];
            $_SESSION["log"] ='S';		
            $_SESSION["empresa"] =$rst_sel['usuario_empresa'];
            $_SESSION["login"] = $login; 
              //BUSCA PERMISSÕES PARA USUARIO
              $stm = $pdo->prepare("select * from ".$_SESSION['BASE'].".telas_acesso where tela_user = '".$idUser."' ");
              $stm->execute();
              if($stm->rowCount() > 0){
                 $array = array();
                foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
                    //PERMISSOES PRINCIPAIS=============================================================================================================
                    if($rst->tela_descricao == "999"){ $_SESSION['per999'] = $rst->tela_descricao; }//USUARIO PERFIL FINANCEIRO
                    
                    if($rst->tela_descricao == "1"){ $_SESSION['per001'] = $rst->tela_descricao; }//AGENDA
                    if($rst->tela_descricao == "2"){ $_SESSION['per002'] = $rst->tela_descricao; }//GESTAO DE CLIENTE
                    if($rst->tela_descricao == "3"){ $_SESSION['per003'] = $rst->tela_descricao; }//MENU PRINCIPAL - SERVIÇOS
                    if($rst->tela_descricao == "4"){ $_SESSION['per004'] = $rst->tela_descricao; }//ANALISE
                    if($rst->tela_descricao == "5"){ $_SESSION['per005'] = $rst->tela_descricao; }//CLIENTES
                    if($rst->tela_descricao == "6"){ $_SESSION['per006'] = $rst->tela_descricao; }//REQUISICAO
                    //if($rst->tela_descricao == "306"){ $_SESSION['per306'] = $rst->tela_descricao; }//FINANCEIRO 2
                    if($rst->tela_descricao == "7"){ $_SESSION['per007'] = $rst->tela_descricao; }//ADMINISTRATIVO
                    if($rst->tela_descricao == "8"){ $_SESSION['per008'] = $rst->tela_descricao; }//SERVICOS
                    if($rst->tela_descricao == "9"){ $_SESSION['per009'] = $rst->tela_descricao; }//ESTOQUE
                    if($rst->tela_descricao == "10"){ $_SESSION['per010'] = $rst->tela_descricao; }//CONFIGURACOES
                    if($rst->tela_descricao == "11"){ $_SESSION['per011'] = $rst->tela_descricao; }//MEU SALDO
                    if($rst->tela_descricao == "12"){ $_SESSION['per012'] = $rst->tela_descricao; }//MINHA CONTA
                    if($rst->tela_descricao == "13"){ $_SESSION['per013'] = $rst->tela_descricao; }//CAIXA PDV
                    if($rst->tela_descricao == "14"){ $_SESSION['per014'] = $rst->tela_descricao; }//VENDAS
                    if($rst->tela_descricao == "15"){ $_SESSION['per015'] = $rst->tela_descricao; }//ADMINSTRATIVO
                    if($rst->tela_descricao == "16"){ $_SESSION['per016'] = $rst->tela_descricao; }//FINANCEIRO
                         

              }

         }
  
        }
    
  
  }else{
    ?> 
    <div style="padding-top: 80px ;" class="alert alert-danger alert-dismissable"> <span ><strong>SUA SESSÃO EXPIROU !!!</strong></span></div>
    <?php    exit();
  }
}

}
//sleep(8000);

    $_retorno_html = Acesso::rotas($_chaveform); 
//echo $_retorno_html;
    if(  $_retorno_html == ""){     
        require_once('pageNotFound.php');
    }else{              
        require_once($_retorno_html);      
    } 


   
?>