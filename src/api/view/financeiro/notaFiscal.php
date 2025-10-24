<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php
require_once('navigatorbar.php');

use Database\MySQL;
$pdo = MySQL::acessabd();



?>
<input type="hidden" id="emitenota" name="emitenota" value="<?=$_POST['emitenota'];?>" />
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Nota Fiscal</h4>           
                <p class="text-muted page-title-alt">Verifique seus pedidos e emita suas notas.</p>
            </div>
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                        <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span> Filtros</button>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="card-box table-responsive" id="listagem">
                <div class="ajax_load_box">
                    <div class="ajax_load_box_circle"></div>
                    <div class="ajax_load_box_title">Aguarde, carrengando...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtro -->
<div id="custom-modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Filtros de pedido</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="pedido-inicial">Período de:</label>
                                <input type="date" class="form-control" name="pedido-inicial" id="pedido-inicial" value="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="pedido-final">Até:</label>
                                <input type="date" class="form-control" name="pedido-final" id="pedido-final" value="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="pedido-num">N° Pedido:</label>
                                <input type="number" class="form-control" name="pedido-num" id="pedido-num">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="pedido-situacao">Situação:</label>
                                <select class="form-control" name="pedido-situacao" id="pedido-situacao">
                                    <option value="">Todos</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="autorizado">Autorizado</option>
                                    <option value="processando">Processando</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="pedido-colaborador">Colaborador:</label>
                                <select class="form-control" name="pedido-colaborador" id="pedido-colaborador">
                                <?php
                                    $statement = $pdo->query("SELECT u.usuario_APELIDO, u.usuario_CODIGOUSUARIO
                                     FROM bd_gestorpet.usuario u 
                                     INNER JOIN bd_gestorpet.colaborador e ON u.usuario_CODIGOUSUARIO = e.colaborador_usuario 
                                    WHERE e.colaborador_empresa = '".$_SESSION['BASE_ID']."' ORDER BY u.usuario_APELIDO");
                                    $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
                                ?>
                                    <option value="0">Todos</option>
                                <?php foreach ($retorno as $row): ?>
                                    <option value="<?=$row->usuario_CODIGOUSUARIO?>"><?=$row->usuario_APELIDO?></option>
                                <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="pedido-empresa">Empresa:</label>
                                <select class="form-control" name="pedido-empresa" id="pedido-empresa">
                                <?php
                                    $statement = $pdo->query("SELECT e.id, e.nome_fantasia FROM bd_gestorpet.empresa_cadastro e WHERE e.grupo_id = '".$_SESSION['BASE_ID']."' ORDER BY e.nome_fantasia");
                                    $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
                                ?>
                                    <option value="0">Todas</option>
                                <?php foreach ($retorno as $row): ?>
                                    <option value="<?=$row->id?>"><?=$row->nome_fantasia?></option>
                                <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                    <label for="pedido-empresa">Detalhado:</label>
                                    <select class="form-control" name="nf-detalhado" id="nf-detalhado">
                                        <option value="0" selected>Não</option>
                                        <option value="1" >sim</option>
                                    </select>    
                            </div>
                    </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" id="button-filtro" data-dismiss="modal">Buscar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir Produto-->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir o grupo? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_excluir();">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="_printviewer">
                Gerando impressão
            </div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="_chaveid" name="_chaveid">
    <input type="hidden" id="id-altera" name="id-altera">
    <input type="hidden" id="cpf" name="cpf">
    <input type="hidden" id="pedido-dados" name="pedido-dados">
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

<!-- Bootstrap -->
<script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

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
    window.onload = function () {
        $('#button-filtro').click();
    }

    function ajax_load(action, id) {
        ajax_load_div = $("#" + id);

        if (action === "open") {
            ajax_load_div.fadeIn(200).css("display", "flex");
        }

        if (action === "close") {
            ajax_load_div.fadeOut(200);
        }
    }

    $('#button-filtro').click(function () {
        var $_keyid = "ACNTFCE";
        var dados = $("#form-filtro").serializeArray();
        dados = JSON.stringify(dados);
        ajax_load("open", "lista-pedidos");

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                ajax_load("close", "lista-pedidos");
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    });

    function _emiteNota() {
        var form = $('#form-valores');
     
        var data = form.serializeArray();
        var dados = JSON.stringify(data);
        var keyForm = form.attr("action");
    
        $.post("page_return.php", {_keyform:keyForm,dados:dados, acao: 1},
            function(result){
              
                $('#retnf').html(result);
                result = result.replace(/^\s+/, "");
          
                    var res = result.substring(0, 14);
                 
                     if (res == '<div id="ok11"') {
                        clearInterval(myTimer);
                       
                    }else{
                            bnc = "1";
                            
                            var myTimer = setInterval(function () {

                            $.post("page_return.php", {_keyform:keyForm,dados:dados, acao: 6},
                            function(result){
                            
                                $('#retnf').html(result);
                            
                                result = result.replace(/^\s+/, "");
                            
                                    var res = result.substring(0, 14);
                                
                                    if (res == '<div id="ok10"') {
                                        clearInterval(myTimer);
                                    
                                    }
                            });
                            bnc =  parseInt(bnc) +  parseInt('1');

                                }, 3000);
            }
            
            });
    }

    function _emiteNotaNfce() {
      
        $("#cpf").val($("#cpf-cnpj").val());
        var $_keyid = 'ACNTFCE';
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $('#result-nfce').html('<img src="assets/images/loading.gif" class="img-responsive center-block" width="50" alt="processando, aguarde."><br>Processando Aguarde');
      
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 9},
            function(result){     
                        
                $('#result-nfce').html(result);
            });        
    }

    function _imprimeNotaNfce(pedido){
        $('#pedido-dados').val(pedido);
        var $_keyid = "_PDV000022";
								$('#_keyform').val($_keyid);
								var dados = $("#form1 :input").serializeArray();
								dados = JSON.stringify(dados);
								$('#_printviewer').html("");
								//verificar cartao


								$.post("page_return.php", {_keyform: $_keyid,dados:dados},function (result){     
                                   
									$('#_printviewer').html(result);
                                    $('#_printviewer').printThis();
																
								});
    }

    function _buscadados(id) {
        $('#id-altera').val(id)
        var $_keyid = 'ACNTFCE';
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        ajax_load("open");

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                ajax_load("close");
                $("#custom-modal-alterar").modal('show').html(result);
            });
    };

    function _cancelarnf(pedido) {
        $('#pedido-dados').val(pedido);
        var $_keyid = "ACNTFCE";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        ajax_load("open", "dados-nf");

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
            function(result){
              //  alert(result);
                ajax_load("close", "dados-nf");               
                $('#statusnf').html(result);
            });
    };

    function _dadosnf(pedido) {
        $('#pedido-dados').val(pedido);
        var $_keyid = "ACNTFCE";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        ajax_load("open", "dados-nf");

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                ajax_load("close", "dados-nf");
                $('#custom-modal-alterar').modal('hide');
                $('#custom-modal-result').modal('show').html(result);
            });
    };

    
    function _dadosnfce(pedido) {
        $('#pedido-dados').val(pedido);
        var $_keyid = "ACNTFCE";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        ajax_load("open", "dados-nf");

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                ajax_load("close", "dados-nf");
                $('#custom-modal-alterar').modal('hide');
                $('#custom-modal-result').modal('show').html(result);
            });
    };

    function _alterar(nota, fabricante){
        var $_keyid = "NFENT";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(nota+"|"+fabricante);
        $('#form1').submit();

    };

    
    function _imprimeNotaNfse($_refnf){
        var $_keyid = "NTFCEPR";
        $('#_chaveid').val($_refnf);
       var dados = $("#form1 :input").serializeArray();
       dados = JSON.stringify(dados);

       $.post("page_return.php", {_keyform:$_keyid,dados:dados},
           function(result){
          
                });
    }

    $(document).on('keyup', '#nota-aliquota', function(e) {
        var base = $('#nota-base').val();
        var aliquota = $(this).val();
        var inss = base * (aliquota / 100);
        $('#nota-iss').val(inss.toFixed(2));
    });


    function chama_modal(){
        var id = $("#emitenota").val();
        if(id != ""){
            var $_keyid = "ACNTFCE";
            $.post("page_return.php", {_keyform:$_keyid,id:id, acao:"monta_info"},
            function(result){
                _buscadados(result);
            
            });  
        }
 
    }

    setTimeout(() => {
        chama_modal();
    }, "600");

</script>
</body>
</html>

