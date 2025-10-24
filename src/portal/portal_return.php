<?php
session_start();
header("Content-type: text/html; charset=utf-8");
require_once('../api/config/config.inc.php');
require '../api/vendor/autoload.php';

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

if (isset($_POST['_var'])) {
    $variable = strip_tags(trim($_POST['_var']));
}



$_parametros = serializeToArray(json_decode($_POST['dados'],true));

$_retorno_html = Acesso::rotas($_chaveform); 
//echo $_retorno_html;
    if(  $_retorno_html == ""){     
        require_once('pageNotFound.php');
    }else{              
        require_once($_retorno_html);      
    } 


   
?>