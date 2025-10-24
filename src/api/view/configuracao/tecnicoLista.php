<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php')?>
<style>
.circle-image { 
  border-radius: 50%; 
  overflow: hidden; 
  width: 120px; 
  height: 120px; 
  margin-left:37%;
} 
.circle-image img { 
  width: 100%; 
  height: 100%; 
  margin-top:11px;
  
}
</style>
<div class="wrapper"  >
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-6">
                    <h4 class="page-title m-t-15">Usuários</h4>
                    <p class="text-muted page-title-alt">Cadastre seus funcionários e usuário atribua permissões e parâmetros.</p>
                </div>
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                       <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                        <button id="addToTable" class="btn btn-success waves-effect waves-light" onclick="_incluir()">Incluir <i class="fa fa-plus"></i></button>
                        <button id="addToTable" class="btn btn-default waves-effect waves-light" onclick="_fechar()">Fechar <i class="fa fa-remove"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box table-responsive" id="tecnico-listagem">
                        <div class="bg-icon pull-request text-center">
                            <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                            <h2>Aguarde, carregando dados...</h2>
                        </div>
                    </div>
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
                    <h2>Deseja realmente excluir o usuário? </h2>
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

<form id="form2" name="form2" action="javascript:void(0)">
            <div id="modalfiltro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Filtros</h4>
                        </div>
                        <div class="modal-body">
                       
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Nome</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="_nome" name="_nome">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Situação</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">                                        
                                        <select name="ativo" id="ativo"  class="form-control input-sm">
                                            <option value="Sim">Ativo</option>
                                            <option value="Nao">Desativado</option>    
                                            <option value="bloc">Bloqueado</option>     
                                            <option value="0">Todos</option>                                  
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button" onclick="_00003()" class="btn btn-info waves-effect waves-light">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
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

<!--Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<script type="text/javascript">
    window.onload = function () {
        _lista();
    }

    function _fechar() {
        var $_keyid = "_Nc00005";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _incluir() {
        var $_keyid = "tecnico_00001";
        $('#_keyform').val($_keyid);
        $("#form1").submit();
    }

    function _lista() {
        var $_keyid = "acaoTecnico_00001";
        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#tecnico-listagem").html(result);
                $('#datatable-responsive').DataTable();
        });
    }

    function _alterar(id){
        var $_keyid = "tecnico_00001";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(id);
        $('#form1').submit();

    }

    
    function _00003(){
        var $_keyid = "acaoTecnico_00001";
        var dados = $("#form2 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 2
        }, function(result) {
            $("#tecnico-listagem").html(result);
             $('#datatable-responsive').DataTable();
             $('#modalfiltro').modal('hide');
        });

    }


    function _permissoes(id){
        var $_keyid = "permissao_00001";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(id);
        $('#form1').submit();

    }

    function _idexcluir(id) {
        $('#id-exclusao').val(id);
    }

    function _excluir() {
        var $_keyid = "acaoTecnico_00001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
        });
    }

</script>

</body>
</html>