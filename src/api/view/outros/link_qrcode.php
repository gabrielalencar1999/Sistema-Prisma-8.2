<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;

$pdo = MySQL::acessabd();
?>
<div class="row">
<div class="col-sm-12 col-xs-12 text-center" >
<label>LINK </label> <br>
<?php

$consulta = $pdo->query("SELECT CGC  from ". $_SESSION['BASE'] .".parametro");
$retorno = $consulta->fetchAll();
foreach ($retorno as $row) {
   $cnpj =  $row["CGC"];                                                            
}

$codigocodificado = base64_encode( $_SESSION['CODIGOCLI'].$cnpj. $_SESSION['CODIGOCLI']);
$_link = "https://sistemaprisma.com.br/portal/?f=$codigocodificado";
//$_link = "http://localhost:8080//portal/?f=$codigocodificado";
    ?>
    <input name="linkqrcode"  type="text" id="linkqrcode"  value="<?=$_link;?>" class="form-control " style="margin-bottom: 5px;" />
    <button type="button" class="btn btn-success btn-sm btn-custom waves-effect waves-light"  onclick="_copy()"><i class="fa fa-copy"></i> Copiar</button>
    <a href="<?=$_link."TX";?>" target="_blank"><button type="button" class="btn btn-warning btn-sm btn-custom waves-effect waves-light" ><i class="fa fa-copy"></i> Totem</button></a>
</div>
</div>
<hr>
<div class="row">
<?php /*
<div class="col-sm-12 col-xs-12 text-center " >
<label>Nº do telefone p/ envio WhatsApp </label>
    <input name="foneqrcode"  type="text" id="foneqrcode"  value="" class="form-control "  placeholder="(99)99999-9999" /><br>
    <button type="button" class="btn btn-success btn-block " data-toggle="modal" data-target="#custom-modal-aparelho"> <i class="fa  fa-external-link-square"></i> Enviar  Link</button>
</div>
</div>
*/ ?>

       
