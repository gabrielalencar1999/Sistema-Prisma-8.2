<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php')?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Contas de Despesas e Receitas</h4>
                <p class="text-muted page-title-alt">Cadastre suas contas de Receitas e Despesas.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro">Filtros <i class="fa fa-gears"></i></button>
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal-incluir">Incluir <i class="fa fa-plus"></i></button>
                    <button class="btn btn-success waves-effect waves-light" onclick="_fechar()">Fechar <i class="fa fa-remove"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="listagem">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th class="text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2"><p class="text-center">Selecione os filtros.</p></td>
                            </tr>
                        </tbody>
                    </table>
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
                <h4 class="modal-title">Filtros de contas</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contas-tipo-filtro" class="control-label">Tipo:</label>
                                <select name="contas-tipo" id="contas-tipo-filtro" class="form-control" onchange="_buscaSelect(this.value, '#select-grupo-filtro')">
                                    <option value="0" selected>Selecione</option>
                                    <option value="1">Receita</option>
                                    <option value="2">Despesa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="select-grupo-filtro">
                                <label for="contas-grupo-filtro" class="control-label">Grupo:</label>
                                <select name="contas-grupo" id="contas-grupo-filtro" class="form-control">
                                    <option value="0">Selecione</option>
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

<!-- Modal Incluir -->
<div id="custom-width-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:25%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Incluir Conta</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contas-descricao" class="control-label">Descrição:</label>
                                <input type="text" name="contas-descricao" id="contas-descricao" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contas-tipo" class="control-label">Tipo:</label>
                                <select name="contas-tipo" id="contas-tipo" class="form-control" onchange="_buscaSelect(this.value, '#select-grupo-incluir')">
                                    <option value="0" selected>Selecione</option>
                                    <option value="1">Receita</option>
                                    <option value="2">Despesa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="select-grupo-incluir">
                                <label for="contas-grupo" class="control-label">Grupo:</label>
                                <select name="contas-grupo" id="contas-grupo" class="form-control">
                                    <option value="0">Selecione</option>
                                </select>
                            </div>
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

<!-- Modal Alterar -->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
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
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="_chaveid" name="_chaveid">
    <input type="hidden" id="id-filtro" name="id-filtro">
    <input type="hidden" id="id-altera" name="id-altera">
    <input type="hidden" id="id-exclusao" name="id-exclusao">
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

<script type="text/javascript">
    function _fechar() {
        var $_keyid = "_Nc00005";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id)
        var $_keyid = "acaodespesa_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
            });
    }

    function _incluir() {
        var $_keyid = "acaodespesa_0001";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                $("#form-inclui :input").val("");
                _lista();
            });
    }

    function _lista() {
        var $_keyid = "acaodespesa_0001";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _altera() {
        var $_keyid = "acaodespesa_0001";
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });
    }

    function _idexcluir(id) {
        $('#id-exclusao').val(id);
    }

    function _excluir() {
        var $_keyid = "acaodespesa_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });

    }

    function _buscaSelect(id, retorno) {
        $("#id-filtro").val(id);
        var $_keyid = "acaodespesa_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $(retorno).html(result);
            });
    }
</script>

</body>
</html>