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
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Recebiveis</h4>
                <p class="text-muted page-title-alt">Conciliação Cartões</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span> Filtros</button>
                   
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal-incluir-csv"><span class="btn-label"><i class="fa fa-plus"></i></span> CSV</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card-box table-responsive" id="listagem"></div>
        </div>
    </div>
</div>

<!-- Modal Filtro -->
<div id="custom-modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" data-backdrop="static" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Filtros de contas</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="nf-inicial">Período de: </label>
                                <input type="date" class="form-control" name="nf-inicial" id="nf-inicial" value="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="nf-final">Até: </label>
                                <input type="date" class="form-control" name="nf-final" id="nf-final" value="<?=date("Y-m-d")?>">
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


<!-- Modal Incluir CSV-->
<div id="custom-width-modal-incluir-csv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Incluir Arquivo CSV</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" id="form-csv" name="form-csv">
                    <div class="form-group">
                        <label class="control-label">Selecione o CSV:</label>
                        <input type="file" class="filestyle" name="retorno-csv" id="retorno-csv" accept="text/csv"  data-placeholder="Sem arquivos">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluiCSV()">Processar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="ret-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-csv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <i class="md-5x md-highlight-remove"></i>
                    <h2>Formato de arquivo inválido!</h2>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal detalhes -->
<div id="custom-modal-detalhes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="viewer-detalhes">


            </div>
        </div>
    </div>
</div>


<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="_chaveid" name="_chaveid">
    <input type="hidden" id="id-nota" name="id-nota">
    <input type="hidden" id="id-fornecedor" name="id-fornecedor">
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
<script src="../../../api/view/administracao/acaoXML.php"></script>
<script src="assets/js/jquery.app.js"></script>

<script type="text/javascript">
    window.onload = function () {
        _lista();
    }

    function _fechar() {
        var $_keyid = "_Na00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }


    function _incluiCSV() {
        var $_keyid = "_Fl00016";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        var form_data = new FormData(document.getElementById("form-csv"));
        aguarde();
        $.ajax({
            url: 'acaorecebivelCSV.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
              
             if(data == "anexado") {
              
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                function(result){
                
                    $("#ret-carregando").html(result);
                });
               
             }else{
                $("#custom-modal-result").modal('show').html(data);
             }
      
                
            }
        });
    }

    function _lista() {
        var $_keyid = "_Fl00016";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    } 
/*
    function isCsv(input)
    {
        var value = input.value;
        var res = value.substr(value.lastIndexOf('.')) == '.csv';
        if (!res) {
            input.value = "";
            $('#custom-width-modal-incluir-csv').modal('hide');
            $('#custom-modal-csv').modal('show');
        }
        return res;
    }
*/

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguarde() {
        $('#ret-carregando').html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
</script>

</body>
</html>

