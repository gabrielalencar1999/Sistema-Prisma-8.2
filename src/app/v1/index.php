<?php session_start();

date_default_timezone_set('America/Sao_Paulo');
header("Content-type: text/html; charset=utf-8");

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/conexaobase.php");
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

if($_SESSION['BASE'] == "" ) {
  
  //verifica 
   $url = explode("?",  $_SERVER["REQUEST_URI"]);
 
  if($url[1] != "" ) {
    $url = explode("=",$url[1]);
  
    $idtecsession = explode(";",base64_decode($url[1]));
   

                //fazer login novamente
                      //buscar dados da base informatica
         $consulta = "Select consumidor_base,Nome_Fantasia from info.consumidor where CODIGO_CONSUMIDOR = '".strip_tags($idtecsession[1])."'";
         $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
         $row = mysqli_num_rows($executa);
         while($rstP = mysqli_fetch_array($executa))	{	
                  $_SESSION['CODIGOCLI'] = $idtecsession[1];
                  $_SESSION['BASE'] = $rstP['consumidor_base'];
                  $_SESSION['fantasia'] = $rstP['Nome_Fantasia'];
         }
        
      
         //BUSCAR SESSION
         $_SESSION['LOGADO'] = $idtecsession[1];
         $sql_sel = "SELECT * FROM ".$_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '".strip_tags($idtecsession[0])."'";    						
         $rs_sel = mysqli_query($mysqli,$sql_sel);
         //Testas as condicoes
         $numrows = mysqli_num_rows($rs_sel);
         $rst_sel = mysqli_fetch_array($rs_sel);         
         if ($numrows!=0){	       
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
   
    
  
  }
}
//$_SESSION['LINK'] = 'index.php';
$_chaveform = strip_tags(trim($_POST['_keyform']));
$_chaveid = strip_tags(trim($_POST['_chaveid']));

if(!empty($_chaveform) ) {  //buscar pasta html
    $_retorno_html = Acesso::rotas($_chaveform);

    if(empty($_retorno_html)){
        require_once('pageNotFound.php');
    }
    else{
     
        require_once($_retorno_html);
      //  include('footer.php');
    }
}
else{
       //     $_retorno = Acesso::autenticacao($_parametros);


//echo $_SESSION['BASE'];
            if($_SESSION['BASE'] == "" ) {
                //$message = $_retorno->message;
                require_once('../../index.php');
            } else {              
            
              require_once('../../api/view/acesso/menuacesso.php');
            
              include('footer.php');
    }
}

/*
foreach($_retorno as $key=>$value){
    echo ( $value->usuario_LOGIN)."<br>";
  }
*/

?>