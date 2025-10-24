<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');
use Database\MySQL;
$pdo = MySQL::acessabd();
?>
        <!-- DataTables -->
        <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Movimentação de Estoque</h4>
                <p class="text-muted page-title-alt">Verifique o histórico de movimentação dos estoques.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span>Filtros</button>
                    <button class="btn btn-success waves-effect waves-light" onclick="imprimeConteudo()"><span class="btn-label"><i class="md md-print"></i></span>Imprimir</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="listagem">
                   
                    <table id="datatable-buttons" class="table table-striped table-bordered"  cellspacing="0" width="100%">
                        <thead>
                        <tr>
                        <th class="text-center">ID</th>
                            <th class="text-center">Descrição</th>
                            <th class="text-center">Movimentação</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Almox</th>
                            <th class="text-center">Cód.</th>
                            <th class="text-center">N° Documento</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">Qtde</th>
                            <th class="text-center">Usuário</th>
                            <th class="text-center">Saldo Atual</th>
                            <th class="text-center">Motivo</th>
                            <th class="text-center">Projeto/Custo</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="alert alert-warning text-center">
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
                <h4 class="modal-title">Filtros de Movimentação</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mov-dataini">Período de:</label>
                                <input type="date" name="mov-dataini" id="mov-dataini" class="form-control" value="<?=date('Y-m-d')?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mov-datafim">Até:</label>
                                <input type="date" name="mov-datafim" id="mov-datafim" class="form-control" value="<?=date('Y-m-d')?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                                <label for="mov-tipo">Tipo:</label>
                                <select class="form-control" name="mov-tipo" id="mov-tipo">
                                    <?php
                                    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tabmovtoestoque ORDER BY Descricao");
                                    $retorno = $statement->fetchAll();
                                    ?>
                                    <option value="0">Todos</option>
                                    <?php
                                    foreach ($retorno as $row) {
                                        ?>
                                        <option value="<?=$row["Tipo_Movto_Estoque"]?>"><?=$row["Descricao"]?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mov-almox">Almoxarifado:</label>
                                <select class="form-control" name="mov-almox" id="mov-almox">
                                    <?php
                                    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".almoxarifado ORDER BY Descricao");
                                    $retorno = $statement->fetchAll();
                                    ?>
                                    <option value="0">Todos</option>
                                    <?php
                                    foreach ($retorno as $row) {
                                        ?>
                                        <option value="<?=$row["Codigo_Almox"]?>"><?=utf8_encode($row["Descricao"])?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-md-6">
                             <div class="form-group m-r-10">
                                <label for="produto-filtro">Filtro: </label>
                                <select class="form-control" name="produto-filtro" id="produto-filtro" >
                                  <option value="9"></option>
                                    <option value="0">Cód. Interno</option>
                                    <option value="1">Cód. Barra</option>
                                    <option value="3">Cód. Fabricante</option>
                                    <option value="2">Descrição</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mov-item">Item:</label>
                                <input type="text" class="form-control" name="mov-item" id="mov-item">
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
        <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.colVis.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

        <script src="assets/pages/datatables.init.js?v=1"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>
<script src="assets/js/printThis.js"></script>

<script type="text/javascript">

        $(document).ready(function () {

        $('#datatable').dataTable();
        $('#datatable-keytable').DataTable({keys: true});
        $('#datatable-responsive').DataTable();
        $('#datatable-colvid').DataTable({
            "dom": 'C<"clear">lfrtip',
            "colVis": {
                "buttonText": "Change columns"
            }
        });
        $('#datatable-scroller').DataTable({
            ajax: "assets/plugins/datatables/json/scroller-demo.json",
            deferRender: true,
            scrollY: 380,
            scrollCollapse: true,
            scroller: true
        });
        var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
        var table = $('#datatable-fixed-col').DataTable({
            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            }
        });
        });
        TableManageButtons.init();

$(formOS).submit(function(){ //pesquisa os
    var $_keyid =   "S00001";                     
    $('#_keyform').val($_keyid);   
    if($('#oksalva').val() == 0 ) { 
        $('#custom-modal-fechar').modal('show');
      }else{
        var dados = $("#formOS :input").serializeArray();
        dados = JSON.stringify(dados);		
                    
        $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
        $('#_chaveid').val($('#numOS').val());   
        document.getElementById('form1').action = '';     
        $("#form1").submit(); 
         });
     }  
    });

    function _lista() {
     
        var $_keyid = "ACMVEST";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
                handleDataTableButtons();
            });
            
    }

    function imprimeConteudo() {
        var $_keyid = "ACMVEST";
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