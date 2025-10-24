<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Arquivos de Balança</h4>
                <p class="text-muted page-title-alt">Gere o TXT e para importação na balança selecionada.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <form action="javascript:void(0)" name="form-gera" id="form-gera" method="post" class="form-inline">
                        <div class="form-group">
                            <label for="">Selecione a balança: </label>
                            <select name="" id="" class="form-control">
                                <option value="0">Selecione</option>
                                <option value="1">Toledo Prix - Cód B. 4</option>
                                <option value="2">Toledo Prix - Cód B. 6</option>
                                <option value="3">Ramuza</option>
                                <option value="4">Micheletti - 1</option>
                                <option value="5">Micheletti - 2</option>
                                <option value="6">Filizola</option>
                            </select>
                            <button class="btn btn-success waves-effect waves-light" onclick="_gera()"><span class="btn-label"><i class="fa fa-plus"></i></span> Gerar</button>
                        </div>
                    </form>
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

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
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

    function _gera() {
        var impressora = $('#form-gera :input').val();
        var $_keyid = "ACARQBL";
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid, acao: impressora},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
            });
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