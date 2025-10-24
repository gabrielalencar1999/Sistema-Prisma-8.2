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
                <h4 class="page-title m-t-15">Mensagem whatsapp</h4>
                <p class="text-muted page-title-alt">Cadastre suas mensagens.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button id="addToTable" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir">Incluir <i class="fa fa-plus"></i></button>
                    
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box table-responsive" id="listagem">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Inclui-->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog text-left modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Incluir Nova Mensagem</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Título:</label>
                                    <input type="text" class="form-control" name="whats_titulo" id="whats_titulo" value="<?=$retorno["whats_titulo"]?>">
                                    <input type="hidden" name="whats-id" id="whats-id" value="<?=$retorno["whats_id"]?>">
                                </div>
                            </div>
                         
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label  class="control-label">Ativo:</label>
                                    <select name="ativo-whats" id="ativo-whats" class="form-control">
                                                <option value="1"<?=$retorno["whats_ativo"] == "1" ? "selected" : ""?>>Sim</option>
                                                <option value="0"<?=$retorno["whats_ativo"] == "0" ? "selected" : ""?>>Não</option>
                                            </select>                                   
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label  class="control-label">Open Ticket:</label>
                                    <select name="openticket-whats" id="openticket-whats" class="form-control">
                                                <option value="1"<?=$retorno["dontOpenTicket"] == "1" ? "selected" : ""?>>Sim</option>
                                                <option value="0"<?=$retorno["dontOpenTicket"] == "0" ? "selected" : ""?>>Não</option>
                                            </select>                                   
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label  class="control-label">Id Template:</label>
                                    <input type="hidden" class="form-control" name="whats-qtde" id="whats-qtde" value="<?=$retorno["whats_maxenvio"]?>">   
                                    <input type="text" class="form-control" name="whats-template" id="whats-template" value="<?=$retorno["msg_template"]?>">                         
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Mensagem:</label>
                                    <textarea  name="textowats" id="textowats"  class="form-control" rows="16"><?=$retorno["whats_mensagem"]?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6"  style="margin-left: 10px;">
                                        <h5 class="m-t-20"><b>TAGS: </b>   
                                        </h5>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-md-3" style="margin-left: 10px;">                                                  
                                
                                <code>[NOME]</code>
                                <code>[ENDERECO]</code>
                                <code>[COMPLEMENTO]</code>
                                <code>[BAIRRO]</code>					
                                <code>[CPFCNPJ]</code>
                                <code>[CIDADE]</code>
                                <code>[UF]</code>
                                <code>[DDD]</code>
                                <code>[EMAIL]</code>
                                <code>[FONES]</code>
                                <code>[FONECELULAR1]</code>
                                <code>[FONECELULAR2]</code>
                                <code>[FONEFIXO]</code>
                                
                        
                                </div>
                                <div class="col-md-3">
                                    <code>[NUMEROOS]</code>
                                    <code>[PRODUTO]</code>
                                    <code>[DTATENDIMENTO]</code>
                                    <code>[NOMEATENDENTE]</code>
                                    <code>[NOMETECNICO]</code>
                                    <code>[DEFEITORECLAMADO]</code>
                                    <code>[DEFEITOCOSTATADO]</code>
                                    <code>[SERVICOEXECUTADO]</code>
                                    <code>[OBSERVACAO]</code>
                                    <code>[MODELO]</code>			
                                    <code>[SERIE]</code>
                                    <code>[MARCA]</code>
                                    <code>[HORARIOATENDIMENTO]</code>
                                    <code>[VLRSERVICOS]</code>
                                    <code>[VLRPECAS]</code>
                                    <code>[TOTAL]</code>
                                    <code>[TOTALDESCONTO]</code>
                                   <code>[DESCRICAOPECAS]</code>  
                                    <code>[DETALHAMENTO_ORCAMENTO]</code>      
                                     <code>[LINKNFSE]</code>  
                            
                                </div>
                                <div class="col-md-3">                               
                                    <code>[EMPRESANOME]</code>
                                    <code>[EMPRESATELEFONE]</code>
                                                            
                                </div>
                            </div>
                            </div>
                        </div>
                        
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluir()">Salvar</button>
                </div>
            </div>
        </div>
</div>

<!-- Modal Alterar-->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">
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
                    <h2>Deseja realmente excluir a mensagem ? </h2>
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
    <div class="modal-dialog  ">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
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

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "whats_00002";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
        });
    }

    function _incluir() {
        var $_keyid = "whats_00002";
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
        var $_keyid = "whats_00002";
        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#listagem").html(result);
                $('#datatable-responsive').DataTable();
        });
    }

    function _altera() {
        var $_keyid = "whats_00002";
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
        var $_keyid = "whats_00002";
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