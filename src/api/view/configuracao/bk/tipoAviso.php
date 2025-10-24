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
                <h4 class="page-title m-t-15">Avisos</h4>
                <p class="text-muted page-title-alt">Mensagens de aviso</p>
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
                    <h4 class="modal-title">Incluir Novo Mensagem</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                    <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label  class="control-label">Referência:</label>
                                    <input type="text" class="form-control" name="aviso_ref" id="aviso_ref" value="">   
                                    <input type="hidden" class="form-control" name="aviso-id" id="aviso-id" value="">  
                                                                
                                
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label  class="control-label">Título:</label>
                                    <input type="text" class="form-control" name="aviso_titulo" id="aviso_titulo" value="">
                                   
                                </div>
                            </div>
                        </div>  
                          
                       
                        <div class="row">
                            <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">  
                                                <label>Data Início</label>                                                     
                                                <input type="date" class="form-control" name="_dataIni"  id="_dataIni" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">  
                                                <label>Final</label>                                                     
                                                <input type="date" class="form-control" name="_dataFim"  id="_dataFim" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="fotosdetalheI">
                                 
                                        </div>
                                    </div>
                                        
                                            
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label f class="control-label">Texto da Mensagem:</label>
                                    <textarea  name="textoaviso" id="textoaviso"  class="form-control" rows="8"></textarea>
                                </div>
                            </div>
                        </div>   
                           
                     
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label  class="control-label">Anexar Arquivo:<code>Imagem (850px X 500px)</code></label>
                                    <input type="file" class="filestyle" name="arquivo-anexoI" id="arquivo-anexoI" accept="x-png,image/gif,image/jpeg,image/png" data-placeholder="Sem arquivos">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-white waves-effect" onclick="uploadImageI()">Carregar Imagem</button>
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
        var $_keyid = "acao_tipAviso_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
        });
    }

    function _incluir() {
        var $_keyid = "acao_tipAviso_0001";
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
        var $_keyid = "acao_tipAviso_0001";
        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#listagem").html(result);
                $('#datatable-responsive').DataTable();
        });
    }

    function _altera() {
        var $_keyid = "acao_tipAviso_0001";
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
        var $_keyid = "acao_tipAviso_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
        });
    }

    function uploadImage() {
       
  
            var dados = $("#form-altera :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregandoA('#fotosdetalhe');          
            var form_data = new FormData(document.getElementById("form-altera"));
           
            $.ajax({
                url: 'acaoAnexoAviso.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(retorno) {
                    $('#fotosdetalhe').html(retorno);                    
                }
            });
}
function uploadImageI() {
  
  
  var dados = $("#form-inclui :input").serializeArray();
  dados = JSON.stringify(dados);
  _carregandoA('#fotosdetalheI');          
  var form_data = new FormData(document.getElementById("form-inclui"));
 
  $.ajax({
      url: 'acaoAnexoAviso.php',
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      type: 'post',
      success: function(retorno) {
          $('#fotosdetalheI').html(retorno);                    
      }
  });
}

function _carregandoA(_idmodal) {

$(_idmodal).html('' +
    '<div class="bg-icon pull-request" >' +
    '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
   
    '</div>');

}

</script>

</body>
</html>