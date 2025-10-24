<?php session_start();
date_default_timezone_set('America/Sao_Paulo');
header("Content-type: text/html; charset=utf-8");
;
require_once('../api/config/config.inc.php');
require '../api/vendor/autoload.php';

use Functions\Acesso;

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
//$_SESSION['LINK'] = 'index.php';
$_chaveform = strip_tags(trim($_POST['_keyform']));
$_chaveid = strip_tags(trim($_POST['_chaveid']));
/*
$_idfrefGO = $_GET['refid'];
$_idfref = base64_decode($_idfrefGO);
*/
$_os = strip_tags(trim($_POST['numberOS178']));
$_telefone = strip_tags(trim($_POST['fonenumberOS178']));
$_ref = strip_tags(trim($_GET['ref']));
if($_ref != "") {
    $_chaveform = 'cli_00001';
}


if(!empty($_chaveform) ) {  //buscar pasta html
    $_retorno_html = Acesso::rotas($_chaveform);

    if(empty($_retorno_html)){
        require_once('pageNotFound.php');
    }
    else{     
       
      //  echo " $_os|$_telefone xxx";

        require_once($_retorno_html);
      //  include('footer.php');
    }
}
else{

    require_once('../api/view/acesso/menu_os.php');
 
}

/*
foreach($_retorno as $key=>$value){
    echo ( $value->usuario_LOGIN)."<br>";
  }
*/

?>