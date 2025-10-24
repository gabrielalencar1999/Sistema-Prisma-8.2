<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   
?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;
use Functions\Acesso;

$pdo = MySQL::acessabd();

$_retvieweConf = Acesso::customizacao('11'); //libera para inclusao novas peças na situação conferencia
$_retbloqueia = Acesso::customizacao('17'); //bloqueia e visualiza somente transfencia para tecnicos

$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
   
}



$reqnumber = $_POST['_chaveid'];

if($reqnumber == "") {

    $consulta = $pdo->query("SELECT Num_Requisicao FROM ".$_SESSION['BASE'].".parametro");
    $retorno = $consulta->fetch();
    $reqnumber = $retorno['Num_Requisicao'];

    $_SQL = "UPDATE   ".$_SESSION['BASE'].".parametro
     SET Num_Requisicao = '".($reqnumber+1)."' ";
    $stm = $pdo->prepare($_SQL);	
   
    $stm->execute();

   
}else{
    $consulta = $pdo->query("SELECT req_status,A.Descricao as descA,B.Descricao as descB,req_almoxarifado,req_almoxarifadoPara,usuario_APELIDO,req_titulo
     FROM ".$_SESSION['BASE'].".requisicao
       left join ".$_SESSION['BASE'].".usuario on usuario_CODIGOUSUARIO = req_criacao	
       left join ".$_SESSION['BASE'].".almoxarifado as A ON  req_almoxarifado = A.Codigo_Almox
       left join ".$_SESSION['BASE'].".almoxarifado as B ON  req_almoxarifadoPara = B.Codigo_Almox
       where req_numero = '$reqnumber'");
    $retorno = $consulta->fetch();
    $status = $retorno['req_status'];
    $deA = $retorno['descA'];
  
    $req_titulo = $retorno['req_titulo'];
    
    $almoxA = $retorno['req_almoxarifado'];
    $almoxB = $retorno['req_almoxarifadoPara'];
    $paraB = $retorno['descB'];
    $_usuarioC = $retorno['usuario_APELIDO'];

    if($deA != ""){
        $_dest == "";
    }else{
        $_dest == "none";
        
    }

    

    $consulta = $pdo->query("SELECT Tipo_Mov
    FROM ".$_SESSION['BASE'].".movtorequisicao_historico   
    where Num_Movto = '$reqnumber'");
   $retorno = $consulta->fetch();
 
   $atipo = $retorno['Tipo_Mov'];

    
}

$filtroAmox = "";
if($_SESSION['per231'] == '231') {
    $_idtecnico = $_SESSION["tecnico"];
    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_perfil2 
    FROM usuario  where usuario_CODIGOUSUARIO = '$_idtecnico'");
    $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
    while($pegar = mysqli_fetch_array($result)){
        $filtroAmox = "AND Codigo_Almox = '1' or Codigo_Almox = '".$pegar["usuario_almox"]."'" ;
        $_perfiltecnico = $pegar["usuario_perfil2"] ;
    }
}else{
     $_idtecnico = $_SESSION["tecnico"];
    $query = ("SELECT usuario_perfil2
    FROM usuario  where usuario_CODIGOUSUARIO = '$_idtecnico'");
    $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
    while($pegar = mysqli_fetch_array($result)){
        $_perfiltecnico = $pegar["usuario_perfil2"] ;
    }  
  

}

if($_perfiltecnico == 8 or $_perfiltecnico == 9){
    //não faz nada
}else{
    $_retbloqueia = 0;
}



?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Requisição de Estoque                                             
            </div>
            <div class="btn-group pull-right m-t-20">                   
                    <?php 
                        if($status == "1" or $status == ""  or $status == "2" or $status == "3") { 
                            if($status == "1" or $status == ""  or $status == "3") { ?>
                                <button tabindex="10" id="sv" type="button" class="btn btn-success waves-effect waves-light  m-l-5"  onclick="_salvar()"><i class="fa   fa-floppy-o"></i> Salvar</button>
                             <?php if($_SESSION['per231'] == '') { ?>
                                <button tabindex="10" id="cf" type="button" class="btn btn-default waves-effect waves-light m-l-5" data-toggle="modal" data-target="#custom-modal-conf" onclick="_conferirFull()"><i class="fa  fa-check-square-o"></i> Conferir</button>
                                <?php } ?>
                           <?php }else{ ?>
                                <button tabindex="10" id="sv" type="button" class="btn btn-success waves-effect waves-light  m-l-5"  onclick="_salvarp()"><i class="fa   fa-floppy-o"></i> Salvar</button>
                                <?php if($_SESSION['per231'] == '') { ?>
                                <button tabindex="10" id="cf" type="button" class="btn btn-default waves-effect waves-light m-l-5" data-toggle="modal" data-target="#custom-modal-conf" onclick="_conferirFullDev()"><i class="fa  fa-check-square-o"></i> Conferir</button>
                                <?php } ?>
                           <?php } ?>
                              
                        <?php }else{ ?>
                            <button tabindex="10" id="sv" type="button" class="btn btn-success waves-effect waves-light  m-l-5"  onclick="_salvarp()"><i class="fa   fa-floppy-o"></i> Salvar</button>
                        <?php }
                       
                        ?>
                           <button type="button" class="btn btn-inverse waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;"  onclick="_relatorio()">Imprimir</button>
                           <button id="voltar" type="button" class="btn btn-white waves-effect waves-light m-l-5" onclick="_fechar()"><i class="fa fa-times"></i></button>
                    </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">               
                  <form action="javascript:void(0)" name="form-inclui" id="form-inclui" method="post">
                    <div class="row">
                        <div class="form-group col-md-1 col-xs-6">
                            <label for="num-mov">N° Doc:</label>
                          
                            <input type="number" class="form-control input-sm"  value="<?=$reqnumber?>" disabled>
                            <input type="hidden" name="num-mov" id="num-mov" value="<?=$reqnumber?>">
                            <input type="hidden" name="_codpesq" id="_codpesq" value="">
                        
                            <input type="hidden" name="_codup" id="_codup" value="">
                            <input type="hidden" name="_codpesquisaOS" id="_codpesquisaOS" value="">
                        </div>
                     
                        <div class="form-group col-md-1 col-xs-6">
                            <label for="data-mov">Data:</label>
                            <input type="text" class="form-control input-sm"  value="<?=date('d/m/Y')?>" disabled>
                            <input type="hidden" name="data-mov" id="data-mov" class="form-control input-sm"  value="<?=date('Y-m-d')?>">
                        </div>
                        <?php if($status == "" ) { ?>
                        <div class="form-group col-md-2 col-xs-6">
                            <label for="tipo-mov">Tipo. Mov:</label>
                            <select tabindex="1"  name="tipo-mov" id="tipo-mov" class="form-control input-sm"  onchange="_transferenciaAlmox()" >
                            <?php
                            if($_retbloqueia == '0'){                           
                                $consulta = $pdo->query("SELECT Tipo_Movto_Estoque, Descricao FROM ".$_SESSION['BASE'].".tabmovtoestoque WHERE mov_visualiza = '1' ORDER BY Descricao");
                            }else{
                                $consulta = $pdo->query("SELECT Tipo_Movto_Estoque, Descricao FROM ".$_SESSION['BASE'].".tabmovtoestoque WHERE mov_visualiza = '1' and Tipo_Movto_Estoque = 'T' ORDER BY Descricao");
                            }
                            $retorno = $consulta->fetchAll();

                                ?><option value="0">Selecione</option><?php
                            
                            foreach ($retorno as $row) {
                                ?><option value="<?=$row['Tipo_Movto_Estoque']?>" <?php if($atipo == $row['Tipo_Movto_Estoque']) {  ?> selected="selected" <?php } ?>><?=$row['Descricao']?></option><?php
                            }
                            ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-2 col-xs-6">
                            <label for="almoxorin-mov">Almoxarifado:</label> 
                            
                            <select tabindex="2"  name="almoxorin-mov" id="almoxorin-mov" class="form-control input-sm" >
                            <?php
                           
                            $consulta = $pdo->query("SELECT Codigo_Almox, Descricao FROM ".$_SESSION['BASE'].".almoxarifado WHERE almox_ativo = 1  $filtroAmox ORDER BY Descricao");
                            $retorno = $consulta->fetchAll();

                                ?><option value="0">Selecione</option><?php
                            
                            foreach ($retorno as $row) {
                                ?><option value="<?=$row['Codigo_Almox']?>" <?php if($almoxA == $row['Codigo_Almox']) {  ?> selected="selected" <?php } ?>> <?=$row['Descricao']?></option><?php
                            }
                            ?>
                            </select>
                        </div>                        
                      
                        <div class="form-group col-md-2 col-xs-6" id="destino-mov" style="display: <?=$_dest;?>;">
                            <label for="almoxodest-mov">Para Almox:</label>
                            <select tabindex="3"  name="almoxodest-mov" id="almoxodest-mov" class="form-control input-sm" >
                            <?php
                            $consulta = $pdo->query("SELECT Codigo_Almox, Descricao FROM ".$_SESSION['BASE'].".almoxarifado WHERE almox_ativo = 1 $filtroAmox ORDER BY Descricao");
                            $retorno = $consulta->fetchAll();

                                ?><option value="0">Selecione</option><?php
                            
                            foreach ($retorno as $row) {
                                ?><option value="<?=$row['Codigo_Almox']?>" <?php if($almoxB == $row['Codigo_Almox']) {  ?> selected="selected" <?php } ?>><?=$row['Descricao']?></option><?php
                            }
                            ?>
                            </select>
                        </div>
                     
                        <?php }else{ ?>
                            <div class="form-group col-md-2 col-xs-6">
                                <label for="data-mov">Usuário Criação:</label>
                                <input type="text" class="form-control input-sm"  value="<?=$_usuarioC;?>" disabled>                                
                              </div>
                            <div class="form-group col-md-2 col-xs-6">
                            <label for="tipo-mov">Tipo. Mov:</label>
                            <select tabindex="5"  name="tipo-mov" id="tipo-mov" class="form-control input-sm" >
                            <?php
                            $consulta = $pdo->query("SELECT Tipo_Movto_Estoque, Descricao FROM ".$_SESSION['BASE'].".tabmovtoestoque WHERE Tipo_Movto_Estoque = '$atipo '");
                            $retorno = $consulta->fetchAll();

                                ?><?php
                            
                            foreach ($retorno as $row) {
                                ?><option value="<?=$row['Tipo_Movto_Estoque']?>" <?php if($atipo == $row['Tipo_Movto_Estoque']) {  ?> selected="selected" <?php } ?>><?=$row['Descricao']?></option><?php
                            }
                            ?>
                            </select>
                        </div>
                            <div class="form-group col-md-2 col-xs-6" >
                            <label for="almoxodest-mov">Do Almox:</label>
                              <input type="text" class="form-control input-sm"  value="<?=$deA;?>" disabled>
                              <input type="hidden" class="form-control input-sm" name="almoxorin-mov" id="almoxorin-mov" value="<?=$almoxA;?>" >
                              
                        </div>
                        <div class="form-group col-md-2 col-xs-6">
                            <label for="projeto-mov">Para Almox:</label>
                            <input type="text" class="form-control input-sm"  value="<?=$paraB;?>" disabled>
                            <input type="hidden" class="form-control input-sm" name="almoxodest-mov" id="almoxodest-mov" value="<?=$almoxB;?>" >
                        </div>
                        <?php } ?>
                        <div class="form-group col-md-2 col-xs-12" >
                               
                        <label >Tit.Ref:</label>  <input type="text" class="form-control input-sm" id="tituloreq"  name="tituloreq" value="<?=$req_titulo;?>" >   
                      </div>
                        </div>
                    <?php if($status == "1" or $status == "" or $status == "3" or $_retvieweConf == '1' and  $status == "2") { ?>
                        <div class="row"> 
                                            <?php if($_vizCodInterno == 1){ 
                                                $filtrar = "CODIGO_FABRICANTE"; ?>
                                               <input type="hidden" text class="form-control  input-sm" id="_filtrar"  name="_filtrar"  value="CODIGO_FABRICANTE">            
                                                <?php
                                            }else{
                                                ?>
                                                <div class="col-sm-1">
                                                <label>por</label>
                                                <Select  class="form-control  input-sm" id="_filtrar"  name="_filtrar" > 
                                                    <option value="Codigo_Barra" <?php if($filtrar == "Codigo_Barra" ) { ?> selected <?php } ?> >Cód. Barra</option>
                                                    <option value="CODIGO_FABRICANTE" <?php if($filtrar == "CODIGO_FABRICANTE" ) { ?> selected <?php } ?> >Cód. Fabricante</option>
                                                    <option value="CODIGO_FORNECEDOR" <?php if($filtrar == "CODIGO_FORNECEDOR" ) { ?> selected <?php } ?> >Cód. Interno</option>                                                           
                                                </Select>   
                                            </div> 
                                            <?php } ?>
                                             
                                            
                                        <div class="col-sm-2 col-xs-6">
                                            <label>Código</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                 <button type="button" data-toggle="modal" data-target="#custom-modal-buscar" class="btn waves-effect waves-light btn-primary input-sm" style="padding-top:5px;"><i class="fa fa-search"></i></button>
                                                </span>
                                                <input type="text" tabindex="6"  name="codbarra-mov" id="codbarra-mov" class="form-control  input-sm" onblur="_idprodutobusca(this.value)" onKeyDown="TABEnter('','qnt-mov')" value="" placeholder="Peça/Produto">
                                            </div>
                                           
                                        </div> 
                                        <div class="col-sm-4 col-xs-6" >
                                            <label>Descrição</label>
                                            <input tabindex="7"  type="text" class="form-control input-sm" name="_desc" id="_desc" onKeyDown="TABEnter('','qnt-mov')" value="" readonly>                                                                              
                                        </div> 
                                        <div class="col-sm-1 col-xs-3">        
                                            <label>Qtde</label>
                                            <input tabindex="8"  type="text" class="form-control input-sm" name="qnt-mov" id="qnt-mov" onKeyDown="TABEnter('','OS')" value="">        
                                        </div> 
                                        <?php /*
                                        <div class="form-group col-xs-3 col-md-1">
                                            <label for="os">OS</label>
                                            <input tabindex="9" type="text" name="OS" id="OS" class="form-control input-sm" onKeyDown="TABEnter('','cadastrar')">
                                        </div>
*/?>
                                        <div class="form-group col-xs-4 col-md-1">
                                           <label for="os">OS</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                <button type="button" data-toggle="modal" data-target="#custom-modal-buscarOS" class="btn waves-effect waves-light btn-white input-sm" style="padding-top:5px;"><i class="fa  fa-binoculars"></i></button>
                                                </span>
                                                <input type="text" tabindex="9" name="OS" id="OS" class="form-control  input-sm"    onKeyDown="TABEnter('','cadastrar')" >
                                            </div>                                           
                                        </div>                                                                                                                       
                                            <input tabindex="9" type="hidden" name="motivo-mov" id="motivo-mov" class="form-control input-sm" onKeyDown="TABEnter('','cadastrar')">                                                          
                                        <div class="col-sm-2 col-xs-4" style="margin-top: 25px;">       
                                        <button tabindex="10" id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto" onclick="_incluir()"><i class="fa fa-plus"></i></button>      
                                        <span class="badge badge-inverse m-l-0" id="_qtdealmox" name="_qtdealmox" style="padding:0,5px ;">-</span>
                                        
                                        </div> 
                                        <?php /*
                                        <div class="col-md-1 col-xs-1 text-right" style="margin-top: 25px;">       
                                            <button id="cadastrar" type="button" class="btn btn-primary waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-buscar">Produtos<span class="btn-label btn-label-right"><i class="fa fa-search"></i></span></button>
                                        </div> 
                                        */?>
                                      
                               
                                </div>
                        <?php  } ?>

                </form>
               
                <div class="row card-box" id="listagem"></div>
               
                <?php if($status != "4" and $_SESSION['per231'] == '') { 
                    ?>
                    <div class="row text-center" ><button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5"  data-toggle="modal" data-target="#custom-modal-final" onclick="_fim()"><span class="btn-label"><i class="fa fa-check"></i></span>Finalizar</button></div>
                 <?php } ?>
                
            </div>
            
        </div>
    </div>
   
</div>

<!-- Modal Alterar-->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Modal Retorno -->
    <div id="custom-modal-atendimento" name="custom-modal-atendimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Conferência (<?=$_SESSION['login'];?>)</h4>
                </div>
                <form name="form9" id="form9" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div class="modal-body" id="_conf">

                    </div>
                </form>
            </div>
        </div>
    </div>
 <!-- Modal conferencia -->
 <div id="custom-modal-conf" name="custom-modal-conf" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_lista()">×</button>
                    <h4 class="modal-title">Conferência (<?=$_SESSION['login'];?>)</h4>
                </div>
                <form name="form19" id="form19" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div class="modal-body" id="_conffull">

                    </div>
                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" data-dismiss="modal" aria-hidden="true" onclick="_lista()">× FECHAR</button>
                </form>
            </div>
        </div>
    </div>
<!-- Modal Excluir-->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir ?</h2>
                    <p>
                        <button type="button" class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" id="btn-excluir" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_excluir()">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
               
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-final" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div id="idresult">

                </div>
            </div>
        </div>
    </div>
</div>


    <!-- print -->
    <div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">            
            <div class="modal-content text-center">
                <div class="modal-body" id="_printviewer">
                    Gerando impressão
                </div>
            </div>
        </div>
    </div>

      <!-- Modal Buscar Produtos -->
                   
      <div id="custom-modal-buscar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog modal-lg text-left">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Pesquisar Peças e Produtos</h4>
                                </div>
                                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form3" id="form3">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-2 " >
                                                            <select name="filtrarbusca" id="filtrarbusca" class="form-control input-sm">
                                                                    <option value="CODIGO_FABRICANTE">Cód.Fabricante</option>
                                                                    <option value="codigobarra">Cód.Barra</option>
                                                                    <option value="codigo">Cód. Interno</option>
                                                                    <option value="Codigo_Referencia_Fornec">Cod.Sku</option>
                                                                    <option value="descricao" selected="">Descrição</option>   
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-8 " >    
                                                            <input type="text" id="busca-produto" name="busca-produto" class="form-control input-sm" placeholder="Descrição, Cód. Fabricante / Barras / SKU ">
                                                        </div>
                                                        <div class="col-sm-1 " >
                                                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_prodservicos(1)"><i class="fa fa-search"></i></button>
                                                        </div>                       
                                                    </div>
                                                    <div class="row" id="retorno-produto" >
                                                        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%" style="margin-top:10px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Descrição</th>                                    
                                                                    <th>Código</th>
                                                                    <th>Valor</th>
                                                                    <th>Estoque</th>
                                                                    <th>End</th>
                                                                    
                                                                </tr>
                                                            </thead>                                                            
                                                            <tbody id="tbody_item">                                                        
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                </form>
                                <div class="modal-footer">
                                
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" >Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Modal Buscar OS-->
                   
      <div id="custom-modal-buscarOS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog  text-left">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"   onclick="_fecharOS()">x</button>
                                    <h4 class="modal-title">Buscar Peças da O.S</h4>
                                </div>
                                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form33" id="form33">
                                                <div class="modal-body">
                                                    <div class="row">
                                                       
                                                        <div class="col-sm-4 " >    
                                                            <input type="number" id="busca-OS" name="busca-OS" class="form-control input-sm" placeholder="Número da O.S">
                                                        </div>
                                                        <div class="col-sm-1 " >
                                                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_buscaOS()"><i class="fa  fa-search"></i></button>
                                                        </div>                       
                                                    </div>
                                                    <div class="row" id="retorno-pecasos" >
                                                        <table id="datatable-responsive-produtos-buscaos" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%" style="margin-top:10px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Codigo </th>
                                                                    <th>Descrição</th>                                    
                                                                    <th>Qtde</th>                                                                   
                                                                    <th>Estoque</th>
                                                                    <th>Ação</th>
                                                                    
                                                                </tr>
                                                            </thead>                                                            
                                                            <tbody id="tbody_itemos">    
                                                                                                             
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                </form>
                                <div class="modal-footer">
                                
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" onclick="_fecharOS()">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-req" name="id-req" value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="qt-altera" name="qt-altera" value="">
    <input type="hidden" id="qt-alteratemp" name="qt-alteratemp" value="">
    <input type="hidden" id="dev-altera" name="dev-altera" value="">
    <input type="hidden" id="_tituloreq" name="_tituloreq" value="">
    <input type="hidden" id="status-altera" name="status-altera" value="">
    <input type="hidden" id="id-busca" name="id-busca" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">
  
    <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">
    <input type="hidden" id="_keyidordem" name="_keyidordem" value="">
</form>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/routes.js"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>
<script src="assets/js/printThis.js"></script>


<script type="text/javascript">
         		
  document.querySelector('body').addEventListener('keydown', function(event) {
        var key = event.keyCode;
                    if(key == '13'){            
                    
               
                if (document.getElementById("btatualizar_item") !== null) {
                    document.getElementById("btatualizar_item").click();

                } 
            
                
            }
        });
    
    function TABEnter(oEvent,tabA){
   
        var oEvent = (oEvent)? oEvent : event;
        var oTarget =(oEvent.target)? oEvent.target : oEvent.srcElement;
        if(oEvent.keyCode==13){
        if(oTarget.type=="text" && oEvent.keyCode==13){
            $('#'+tabA).focus();
        }                              
          
        if (oTarget.type=="radio" && oEvent.keyCode==13) {
            $('#'+tabA).focus();
        }
        
          
        }
    }
    

    window.onload = function () {
        $('#id-busca').val($('#num-mov').val());
        _lista();
        /*
        setTimeout(() => {
            if (jQuery("#datatable-responsive tbody td").length > 1) {
                $('#form-inclui :input').attr('disabled', 'disabled');
            }
        }, 200);
        */
    }

    function _fechar() {
        var $_keyid = "RE0001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _idprodutobusca(_id) {    
        if(_id != "")      {
            _buscaProdutoCod(_id);
        }
           
            }
    
    function _buscaProdutoCod(id) {
        var $_keyid = "ACRQEST";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
            function(result){       
             
                var ret = JSON.parse(result); 
          
                        $("#_codpesq").val(ret.CODIGO_FORNECEDOR);
                        $('#_desc').val(ret.DESCRICAO);
                        $('#_vlr').val(ret.Tab_Preco_5);                       
                        document.getElementById("_qtdealmox").innerHTML = "Qtd: "+ret.tot_item;
                         
                
            });
                                             
                
        }


    function _incluir() {
        var $_keyid = "ACRQEST";
        var codpesq_A =  $("#_codpesq").val();
        var codbarramov_A =   $('#codbarra-mov').val();
        var movmotivo_A = $('#mov-motivo').val();  
        var qntmov_A =  $('#qnt-mov').val();
        var desc_A =  $('#_desc').val();
        var vlr_A =  $('#_vlr').val();
        var OS_Aa  = $('#OS').val();

        if(codpesq_A != ""){       
                    var dados = $("#form-inclui :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $("#_codpesq").val("");
                                $('#codbarra-mov').val("");
                                $('#mov-motivo').val("");                    
                                $('#qnt-mov').val("");
                                $('#_desc').val("");
                                $('#_vlr').val("");
                                $('#OS').val("");
                    aguarde();

                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                        function(result){
                        
                            if(result == "") {
                                $("#_codpesq").val("");
                                $('#codbarra-mov').val("");
                                $('#mov-motivo').val("");                    
                                $('#qnt-mov').val("");
                                $('#_desc').val("");
                                $('#_vlr').val("");
                                $('#OS').val("");
                                _lista();
                        
                                $("#codbarra-mov").focus();                         
                            }else{
                                $("#custom-modal-result").modal('show').html(result);
                                $("#_codpesq").val(codpesq_A);
                                $('#codbarra-mov').val(codbarramov_A);
                                $('#mov-motivo').val(movmotivo_A);                    
                                $('#qnt-mov').val(qntmov_A);
                                $('#_desc').val(desc_A);
                                $('#_vlr').val(vlr_A);
                                $('#OS').val(OS_Aa);
                                
                            }
                            
                            
                        });
             }

    }

    
    function _incluirOS(_idref) {
        
        $('#_codpesquisaOS').val(_idref);

        var $_keyid = "S00009";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 333
        }, function(result) {
      
            var ret = JSON.parse(result);

            if(ret.CODIGO_FABRICANTE != "") {    
                         
                
                $("#_codpesq").val(ret.Codigo_Peca_OS);
                $('#codbarra-mov').val(ret.CODIGO_FABRICANTE);
                $('#mov-motivo').val("");                    
                $('#qnt-mov').val(ret.Qtde_peca);
                $('#_desc').val(ret.Minha_Descricao);
                $('#_vlr').val(ret.Valor_Peca);
                $('#OS').val(ret.Numero_OS);
           

                var $_keyid = "ACRQEST";
                var dados = $("#form-inclui :input").serializeArray();
                 dados = JSON.stringify(dados);
                  $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                    function(result){
                    
                    
                        if(result == "") {
                            _lista();
                            $("#_codpesq").val("");
                            $('#codbarra-mov').val("");
                            $('#mov-motivo').val("");                    
                            $('#qnt-mov').val("");
                            $('#_desc').val("");
                            $('#_vlr').val("");
                            $('#OS').val("");
                            $("#codbarra-mov").focus();   
                                        
                        }else{
                            $("#custom-modal-buscarOS").modal('hide');
                            $("#custom-modal-result").modal('show').html(result);
                           
                            
                        }
                
                
            });
          }
        });

     

    }
    function _fecharOS() {
                        $("#busca-OS").val("");     
                         $("#tbody_itemos ").html("");   
    }
    



    function _lista() {
        var $_keyid = "ACRQEST";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid, dados, acao: 2},
            function(result){
                $("#listagem").html(result);
                $('#datatable-responsive').DataTable({"bPaginate": false});
            });
    }

    function _alterar() {
        document.getElementById("btfinalizarreq").disabled = true;
        $('#_tituloreq').val($('#tituloreq').val());

        var $_keyid = "ACRQEST";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 16},
            function(result){
                if(result == "") {                
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
                function(result){
                    $("#custom-modal-final").modal('show').html(result); 
                                            
                });
            }else{
                   $("#idresult").html(result);   
            }
                                        
            });
    }

    


    
    function _devolver() {
        var $_keyid = "ACRQEST";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        document.getElementById("btfinalizarreq").disabled = true;    

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 14},
            function(result){
                $("#custom-modal-final").modal('show').html(result);                           
            });
    }
 

    function _salvar() {
        var $_keyid = "ACRQEST";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){      
                if(result == "") {
                    _fechar() ;  
                }else{
                    $("#custom-modal-result").modal('show').html(result);
                }                
            });

    }
    function _salvarp() {
        var $_keyid = "ACRQEST";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 66},
            function(result){      
                if(result == "") {
                    _fechar() ;  
                }else{
                    $("#custom-modal-result").modal('show').html(result);
                }                
            });

    }

    function _fim() {
        
      
        var $_keyid = "ACRQEST";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        
                 $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 8},
                    function(result){              
                        $("#idresult").html(result);   
                        document.getElementById("btfinalizarreq").disabled = false;                        
                    });  
                
          
    }
    

  

    function  _conferir(_id) {
      
        var $_keyid = "ACRQEST";       
        $('#id-altera').val(_id);
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 10},
            function(result){          
                $("#_conf").html(result);     
            });
    }

    
    function  _conferirFull() {
      
      var $_keyid = "ACRQEST";     
      var dados = $("#form1 :input").serializeArray();
      dados = JSON.stringify(dados);
      aguarde();

      $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 100},
          function(result){          
              $("#_conffull").html(result);     
          });
   }

   function  _conferirFullDev() {
      
      var $_keyid = "ACRQEST";     
      var dados = $("#form1 :input").serializeArray();
      dados = JSON.stringify(dados);
      aguarde();

      $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 200},
          function(result){          
              $("#_conffull").html(result);     
          });
   }

  function  _atualizaQT(_acao,_ref,_status,_n) {
    var $_keyid = "ACRQEST";     
    $('#id-altera').val(_ref);
    $('#id-req').val($('#num-mov').val());
    
    var _calc = $('#'+_ref).val();
    if(_acao == "a" &&  _calc >= 0){
        _calc = Number($('#'+_ref).val()) + 1;
    }
    if(_acao == "m"  &&  _calc > 0){        
        _calc = Number($('#'+_ref).val()) - 1;
     }

    $('#'+_ref).val(_calc);
  
    if( Number($('#'+_ref).val()) >  Number($('#qte'+_ref).val())){
       
        $('#'+_ref).val($('#qte'+_ref).val()); 
        $('#dev'+_ref).val(0)  ; 
    }else{
        
        $('#dev'+_ref).val( Number($('#qte'+_ref).val())-Number($('#'+_ref).val()))  ; 
    }
   
   
    $('#qt-altera').val($('#'+_ref).val());
    $('#dev-altera').val($('#dev'+_ref).val());
    $('#status-altera').val(_status);
  

      var dados = $("#form1 :input").serializeArray();
      dados = JSON.stringify(dados);
   
      $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: _n},
          function(result){     
            if(_n == 102 || _n == 202) {          
               $('#s'+_ref).html(result);     
            }
            
          })

  }

  
  function  _atualizaQTD(_acao,_ref,_status,_n) {
    var $_keyid = "ACRQEST";     
    $('#id-altera').val(_ref);
    $('#id-req').val($('#num-mov').val());
    
    var _calc = $('#'+_ref).val();
    if(_acao == "a" &&  _calc >= 0){
        _calc = Number($('#'+_ref).val()) + 1;
    }
    if(_acao == "m"  &&  _calc > 0){        
        _calc = Number($('#'+_ref).val()) - 1;
     }

    $('#'+_ref).val(_calc);
  
    if( Number($('#'+_ref).val()) >  Number($('#entr'+_ref).val())){
       
        $('#'+_ref).val($('#entr'+_ref).val()); 
        $('#dev'+_ref).val(0)  ; 
    }else{
        
        $('#dev'+_ref).val( Number($('#entr'+_ref).val())-Number($('#'+_ref).val()))  ; 
    }
   
   
    $('#qt-altera').val($('#'+_ref).val());
    $('#dev-altera').val($('#dev'+_ref).val());
    $('#status-altera').val(_status);
  

      var dados = $("#form1 :input").serializeArray();
      dados = JSON.stringify(dados);
   
      $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: _n},
          function(result){     
            if(_n == 102 || _n == 202) {          
               $('#s'+_ref).html(result);     
            }
            
          })

  }

  function  _atualizaQTDDevolvida(_acao,_ref,_status,_n) {
    var $_keyid = "ACRQEST";     
    $('#id-altera').val(_ref);
    $('#id-req').val($('#num-mov').val());
    
    var _calc = $('#'+_ref).val();
    if(_acao == "a" &&  _calc >= 0){
        _calc = Number($('#'+_ref).val()) + 1;
    }
    if(_acao == "m"  &&  _calc > 0){        
        _calc = Number($('#'+_ref).val()) - 1;
     }

    $('#'+_ref).val(_calc);
  
  //  if( Number($('#'+_ref).val()) >  Number($('#entr'+_ref).val())){
       
  //      $('#'+_ref).val($('#entr'+_ref).val()); 
  //      $('#dev'+_ref).val(0)  ; 
  //  }else{
        
  //      $('#dev'+_ref).val( Number($('#entr'+_ref).val())-Number($('#'+_ref).val()))  ; 
  //  }
   
   
    $('#qt-alteratemp').val($('#'+_ref).val());
   // $('#dev-altera').val($('#dev'+_ref).val());
   // $('#status-altera').val(_status);
  

      var dados = $("#form1 :input").serializeArray();
      dados = JSON.stringify(dados);

      $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: _n},
          function(result){     
         
           // if( _n == 301) {          
            //   $('#s'+_ref).html(result);     
          //  }
            
          })

  }
  
  

    function   _salvaritem(_id) {
        
        var $_keyid = "ACRQEST";
        var dados = $("#form9 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 11},
            function(result){          
                $("#confresult").html(result);   
                _lista();
           
            });
    }

    function   _devolvidoitem(_id) {
        
        var $_keyid = "ACRQEST";
        document.getElementById("btfinalizarreq").disabled = false;   
        var dados = $("#form9 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 12},
            function(result){          
                $("#confresult").html(result); 
                _lista();
            });
    }
    
    
    function   _calcula() {
        var $_keyid = "ACRQEST";
        var dados = $("#form9 :input").serializeArray();
        dados = JSON.stringify(dados);
  
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 13},
            function(result){       
             
                $("#_retornocalculo").html(result); 
                
            });
  
    }

    function _excluir() {
        var $_keyid = "ACRQEST";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 9},
            function(result){
          
                _lista();
            });
    }

    function _idatualizaMovItem() {
      
        var $_keyid = "ACRQEST";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 17},
            function(result){  
                _lista();
            });
    }

    function _idexcluir(id) {
        $('#id-exclusao').val(id);
    }

    function _transferenciaAlmox() {
        var tipo = $('#tipo-mov').val();
        var destino = document.getElementById('destino-mov');
        
        if (tipo == "T") {
            destino.style.display = 'block';
            $('#codigo-mov').removeClass('col-md-2').addClass('col-md-3');
            $('#descricao-mov').removeClass('col-md-2').addClass('col-md-3');
            $('#mov-motivo').removeClass('col-md-2').addClass('col-md-3');
        }
        else {
            destino.style.display = 'none';
            $('#codigo-mov').removeClass('col-md-3').addClass('col-md-2');
            $('#descricao-mov').removeClass('col-md-3').addClass('col-md-2');
            $('#mov-motivo').removeClass('col-md-3').addClass('col-md-2');
        }
    }

    function _relatorio() {
        var $_keyid = "ACRQESTRPT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){               
               // $("#custom-modal-relatorio").modal('show').html(result);
               $("#_printviewer").html(result);
               $('#_printviewer').printThis();
            });

    
    }
    function _buscaOS() {      
      var $_keyid =   "S00009";        
      var dados = $("#form33 :input").serializeArray();
      dados = JSON.stringify(dados);    
      aguardeListagem('#datatable-responsive-produtos-buscaOS');        
              $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 33}, function(result){	       
                    $('#tbody_itemos').html(result);                                                                                       
               });
      }

    function _prodservicos(_id) {
      
    var $_keyid =   "S00009";   
   
    var dados = $("#form3 :input").serializeArray();
    dados = JSON.stringify(dados);
  
    aguardeListagem('#datatable-responsive-produtos-busca');
      
            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 1}, function(result){	
          
                $('#retorno-produto').html(result); 
                $('#datatable-responsive-produtos-busca').DataTable(
                    {"pageLength": 25, "bFilter": false, "dom": 'rtip', "info": false,
                      "language": {
                            "paginate": {
                            "previous": " < ",
                            "next": " >>"
                            }
                    }
                    } 
                    );                                                                                       
             });
    }

    function _idprodutosel(_id) {
        $('#_codpesq').val(_id);
        $('#custom-modal-buscar').modal('hide');
        _buscaProdutoCodPesq(_id);
        }

        
        function _buscaProdutoCodPesq(id) {    
        
        $('#_keyidpesquisa').val(id);         
        var $_keyid =   "S00009";    
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);                              
        $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 2}, function(result){					                          		                                                    ;
            var ret = JSON.parse(result);                    
                    $("#codbarra-mov").val(ret.CODIGO_FORNECEDOR);
                  $("#codbarra-mov").focus();
                                                                                            
        });  
    }
    function atualizar_item(id) {    
        
        $('#_codup').val(id);       
        var $_keyid = "ACRQEST";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 15},
            function(result){
             
            if(result == "") {
                    _lista();
                    $("#_codpesq").val("");
                    $('#codbarra-mov').val("");
                    $('#mov-motivo').val("");
                    $('#qnt-mov').val("");
                    $('#_desc').val("");
                    $('#_vlr').val("");
                    $("#custom-modal-result").html('');
                    $("#codbarra-mov").focus();
                }else{
                    $("#custom-modal-result").html(result);
                    
                }
                
                
            });
    }

    
    function _order(idref) {
        $("#_keyidordem").val(idref);

     }
    

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    $('#datatable-responsive-produtos-busca').DataTable(
                    { "lengthChange": false,"pageLength": 25, "bFilter": false, "dom": 'rtip', "info": false,
                      "language": {
                            "paginate": {
                            "previous": " < ",
                            "next": " >"
                            }
                    }
                    } 
                    );  
              
</script>
</body>
</html>