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

$_parametros = serializeToArray(json_decode($_POST['dados'],true));
if (isset($_POST["acao"])) {
    $acao = strip_tags(trim($_POST['acao']));  
}


$_chaveform = strip_tags(trim($_POST['_keyform']));
$_chaveid = strip_tags(trim($_POST['_chaveid']));

$_ref = strip_tags(trim($_GET['f']));


    require_once('../api/view/acesso/menu_portal.php');
 

?>