<?php 

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   

$_acao = $_POST["acao"];

$_cssmenu = "line-height:15px;font-weight:0;padding:0px 10px;text-transform:none";

           
            $sql = "Select *         
            from chamada 
            WHERE $_filtro = '".$_parametros['_idref']."'";
            $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
            while($row = mysqli_fetch_array($resultado)){
                    $codigoos =  $row["CODIGO_CHAMADA"] ; 
                   // $_sitelx  =  $row["CODIGO_CHAMADA"] ;
                
   
            }

            //atendimento não iniciado
            ?>
            <input type="hidden" id="_idos" name="_idos"  value="<?=$_parametros['_idref'];?>"> 
            <input type="hidden" id="_idcliente" name="_idcliente"  value="<?=$_idcliente;?>"> 
            <input type="hidden" id="_almox" name="_almox"  value="<?=$_parametros['_idref'];?>"> 
            <input type="hidden" name="_idexpeca" id="_idexpeca" value="" />
            <input type="hidden" name="_idfoto" id="_idfoto" value="" />
            <input type="hidden" name="_idalt" id="_idalt" value="" />
            <input type="hidden" name="_motivoselecionado" id="_motivoselecionado" value="" />
            <input type="hidden" name="_idstatustrack" id="_idstatustrack" value="" />f
           
            
            
         
    

?>