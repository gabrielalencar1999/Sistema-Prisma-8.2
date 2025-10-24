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
$_retbloqueia = Acesso::customizacao('17'); //bloqueia e visualiza somente transfencia para tecnicos

$pdo = MySQL::acessabd();

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
    $consulta = $pdo->query("SELECT req_status,A.Descricao as descA,B.Descricao as descB,req_almoxarifado,req_almoxarifadoPara
     FROM ".$_SESSION['BASE'].".requisicao
    left join ".$_SESSION['BASE'].".almoxarifado as A ON  req_almoxarifado = A.Codigo_Almox
    left join ".$_SESSION['BASE'].".almoxarifado as B ON  req_almoxarifadoPara = B.Codigo_Almox
     where req_numero = '$reqnumber'");
    $retorno = $consulta->fetch();
    $status = $retorno['req_status'];
    $deA = $retorno['descA'];

  
    $almoxA = $retorno['req_almoxarifado'];
    $almoxB = $retorno['req_almoxarifadoPara'];
    $paraB = $retorno['descB'];

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

$_idtecnico = $_SESSION["tecnico"];
$query = ("SELECT usuario_perfil2
FROM usuario  where usuario_CODIGOUSUARIO = '$_idtecnico'");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while($pegar = mysqli_fetch_array($result)){
    $_perfiltecnico = $pegar["usuario_perfil2"] ;
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
                </h4>                
            </div>
            <div class="btn-group pull-right m-t-5">   
           <!-- <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;"  onclick="_relatorio()">Imprimir</button> -->              
              <button tabindex="10" id="cf" type="button" class="btn btn-default waves-effect waves-light m-l-5" data-toggle="modal" data-target="#custom-modal-conf" onclick="_conferirFull()"><i class="fa  fa-check-square-o"></i> Conferir</button>
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
                            $consulta = $pdo->query("SELECT Codigo_Almox, Descricao FROM ".$_SESSION['BASE'].".almoxarifado WHERE almox_ativo = 1  ORDER BY Descricao");
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
                            $consulta = $pdo->query("SELECT Codigo_Almox, Descricao FROM ".$_SESSION['BASE'].".almoxarifado WHERE almox_ativo = 1 ORDER BY Descricao");
                            $retorno = $consulta->fetchAll();

                                ?><option value="0">Selecione</option><?php
                            
                            foreach ($retorno as $row) {
                                ?><option value="<?=$row['Codigo_Almox']?>" <?php if($almoxB == $row['Codigo_Almox']) {  ?> selected="selected" <?php } ?>><?=$row['Descricao']?></option><?php
                            }
                            ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2 col-xs-6">
                            <label for="projeto-mov">Projeto/Custo:</label>
                            <select tabindex="4"  name="projeto-mov" id="projeto-mov" class="form-control input-sm" >
                            <?php
                            $consulta = $pdo->query("SELECT projeto_id, projeto_descricao FROM ".$_SESSION['BASE'].".projeto ORDER BY projeto_descricao");
                            $retorno = $consulta->fetchAll();

                                ?><option value="0">Selecione</option><?php
                            
                            foreach ($retorno as $row) {
                                ?><option value="<?=$row['projeto_id']?>"><?=$row['projeto_descricao']?></option><?php
                            }
                            ?>
                            </select>
                        </div>
                        <?php }else{ ?>
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
                            <input type="hidden" class="form-control input-sm" name="almoxodest-mov" id="almoxodest-mov" value="<?=$almoxA;?>" >
                        </div>

                        <?php } ?>
                    
                        </div>
               

                </form>
                <div class="row card-box" id="listagem"></div>
  
                
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
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" id="btn-excluir" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_excluir()">Excluir</button>
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




      <!-- Modal Buscar Produtos -->
                   
      <div id="custom-modal-buscar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog modal-lg text-left">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Buscar Peças</h4>
                                </div>
                                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form3" id="form3">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-2 " >
                                                            <select name="filtrarbusca" id="filtrarbusca" class="form-control input-sm">
                                                                    <option value="codigobarra">Cód.Barra/Fornecedor</option>
                                                                    <option value="codigo">Cód. Interno</option>
                                                                    <option value="descricao" selected="">Descrição</option>
                                                                    <option value="modelo">Modelo</option>
                                                                    <option value="endereco">Endereço</option>                    
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-8 " >    
                                                            <input type="text" id="busca-produto" name="busca-produto" class="form-control input-sm" placeholder="Descrição, Cód. barras, modelo, valor">
                                                        </div>
                                                        <div class="col-sm-1 " >
                                                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_prodservicos(1)"><i class="fa fa-search"></i></button>
                                                        </div>                       
                                                    </div>
                                                    <div class="row" id="retorno-produto" >
                                                        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%" style="margin-top:10px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Codigo </th>
                                                                    <th>Descrição</th>                                    
                                                                    <th>Cod Barra/Fornec.</th>
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

    <!-- Modal conferencia -->
 <div id="custom-modal-conf" name="custom-modal-conf" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >×</button>
                    <h4 class="modal-title">Conferência</h4>
                </div>
                <form name="form19" id="form19" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div class="modal-body" id="_conffull">

                    </div>
                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" data-dismiss="modal" aria-hidden="true" onclick="_lista()">× FECHAR</button>
                </form>
            </div>
        </div>
    </div>


<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="id-busca" name="id-busca" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">
    <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">
    
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
         		
    /*   document.querySelector('body').addEventListener('keydown', function(event) {
        var key = event.keyCode;
                    if(key == '13'){            
                alert("enter")        ;
                if($("#codigoBarras").focus
                $("#codigoBarras").focus();
            }
        });
    */
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
        var $_keyid = "RE0001tec";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
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

    function _lista() {
        var $_keyid = "ACRQESTtec";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid, dados, acao: 2},
            function(result){
                $("#listagem").html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    
    function  _conferirFull() {
      
      var $_keyid = "ACRQESTtec";     
      var dados = $("#form1 :input").serializeArray();
      dados = JSON.stringify(dados);
      aguarde();

      $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 100},
          function(result){          
              $("#_conffull").html(result);     
          });
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