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
$pdo = MySQL::acessabd();



?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Agenda</h4>
                <p class="text-muted page-title-alt">Verifique o histórico de agendamentos.</p>              
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span>Filtros</button>
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir"><span class="btn-label"><i class="fa fa-plus"></i></span> Incluir</button>
                    <button class="btn btn-success waves-effect waves-light" onclick="imprimeConteudo()"><span class="btn-label"><i class="md md-print"></i></span>Imprimir</button>
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="listagem">
                    <div class="alert alert-warning text-center">
                        <strong>Atenção!</strong> Selecione os filtros para listar os movimentos.
                    </div>
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
                <h4 class="modal-title">Filtros de Agendamento</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agenda-dataini">Período de:</label>
                                <input type="date" name="agenda-dataini" id="agenda-dataini" class="form-control" value="<?=date('Y-m-d')?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agenda-datafim">Até:</label>
                                <input type="date" name="agenda-datafim" id="agenda-datafim" class="form-control" value="<?=date('Y-m-d')?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agenda-situacao">Situação:</label>
                                <select class="form-control" name="agenda-situacao" id="agenda-situacao">
                                    <option value="0">Todas</option>
                                    <option value="1">Aberta</option>
                                    <option value="2">Encerrada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agenda-prioridade">Prioridade:</label>
                                <select class="form-control" name="agenda-prioridade" id="agenda-prioridade">
                                    <option value="0">Todas</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agenda-documento">Documento:</label>
                                <input type="text" class="form-control" name="agenda-documento" id="agenda-documento">
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agenda-usuario">Usuário:</label>
                                <select class="form-control" name="agenda-usuario" id="agenda-usuario">
                                    <?php
                                    $statement = $pdo->query("SELECT usuario_CODIGOUSUARIO, usuario_LOGIN,usuario_APELIDO FROM ".$_SESSION['BASE'].".usuario ORDER BY usuario_APELIDO");
                                    $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
                                    ?>
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row->usuario_CODIGOUSUARIO?>"><?=$row->usuario_APELIDO?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_lista()" data-dismiss="modal">Buscar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incluir agendamento -->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Incluir Agendamento</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="agenda-data">Data Agendamento:</label>
                            <input type="date" class="form-control" id="agenda-data" name="agenda-data" value="<?=date("Y-m-d")?>">
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="agenda-prioridade">Prioridade:</label>
                            <select class="form-control" name="agenda-prioridade" id="agenda-prioridade">
                                <option value="1" selected>Baixa</option>
                                <option value="2">Média</option>
                                <option value="3">Alta</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="agenda-situacao">Situação:</label>
                            <select class="form-control" name="agenda-situacao" id="agenda-situacao">
                                <option value="1" selected>Aberta</option>
                                <option value="2">Encerrada</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="agenda-cliente">Nome/Empresa:</label>
                            <input type="text" class="form-control" id="agenda-cliente" name="agenda-cliente">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="agenda-telefone">Telefone:</label>
                            <input type="text" class="form-control" id="agenda-telefone" name="agenda-telefone">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="agenda-contato">Contato:</label>
                            <input type="text" class="form-control" id="agenda-contato" name="agenda-contato">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="agenda-documento">Documento:</label>
                            <input type="text" class="form-control" id="agenda-documento" name="agenda-documento">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="agenda-usuario">Usuário:</label>
                            <select class="form-control" name="agenda-usuario" id="agenda-usuario">
                                <?php
                                $statement = $pdo->query("SELECT usuario_CODIGOUSUARIO, usuario_LOGIN FROM ".$_SESSION['BASE'].".usuario  ORDER BY usuario_NOME");
                                $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
                                ?>
                                <option value="0">Todos</option>
                                <?php foreach ($retorno as $row): ?>
                                    <option value="<?=$row->usuario_CODIGOUSUARIO?>"><?=$row->usuario_LOGIN?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="agenda-assunto">Assunto:</label>
                            <textarea class="form-control" name="agenda-assunto" id="agenda-assunto" cols="30" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="agenda-motivo">Motivo:</label>
                            <textarea class="form-control" name="agenda-motivo" id="agenda-motivo" cols="30" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluir()">Salvar</button>
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
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Imprime -->
<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="tablea-impressa"></div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">
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
    window.onload = function () {
        _lista();
    }

    function _fechar() {

                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACAGND";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
            });
    }

    function _incluir() {
        var $_keyid = "ACAGND";
        var dados = $("#form-inclui").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                $("#form-inclui :input").val("");
                _lista();
            });
    }

    function _lista() {
        var $_keyid = "ACAGND";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _altera() {
        var $_keyid = "ACAGND";
        var dados = $("#form-altera").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });
    }

    function imprimeConteudo() {
        var $_keyid = "ACAGND";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $('#tablea-impressa').html(result);
            });
        $('#tablea-impressa').printThis();
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

  
</script>

</body>
</html>