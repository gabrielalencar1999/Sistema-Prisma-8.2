<?php include("../../api/config/iconexao.php")?>
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
                <h4 class="page-title m-t-15">Curva ABC</h4>
                <p class="text-muted page-title-alt">Identifique seus principais produtos em cada peíodo.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span>Filtros</button>
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-csv" onclick="imprimeCSV()"><i class="fa  fa-file-excel-o"></i></button>
                    <button class="btn btn-inverse waves-effect waves-light" onclick="imprimeConteudo()"><span class="btn-label"><i class="md md-print"></i></span>Imprimir</button>
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="listagem">
                    <div class="alert alert-warning text-center">
                        <strong>Atenção!</strong> Selecione os filtros para listar os produtos.
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
                <h4 class="modal-title">Filtros Curva ABC</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label >Filtrar por: </label>
                                <select class="form-control" name="curva-tipo" id="curva-tipo">
                                    <option value="1" >Vendas</option>
                                    <option value="2" >Ordem Serviço</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label >Max. Registro</label>
                                <select class="form-control" name="curva-qtdemax" id="curva-qtdemax">
                                    <option value="100" >100</option>
                                    <option value="500" >500</option>
                                    <option value="1000" >1000</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="curva-empresa">Empresa: </label>
                                <select class="form-control" name="curva-empresa" id="curva-empresa">
                                    <?php
                                    $consulta = $pdo->query("SELECT empresa_id, empresa_nome FROM ".$_SESSION['BASE'].".empresa ORDER BY empresa_nome");
                                    $retorno = $consulta->fetchAll();
                                    ?>
                                    <option value="0">Todas</option>
                                    <?php
                                    foreach ($retorno as $row) {
                                        ?><option value="<?=$row['empresa_id']?>"><?=$row['empresa_nome']?></option><?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="curva-grupo">Grupo: </label>
                                <select class="form-control" name="curva-grupo" id="curva-grupo">
                                <?php
                                $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".grupo ORDER BY GRU_DESC");
                                $retorno = $statement->fetchAll();
                                ?>
                                    <option value="0">Todos</option>
                                <?php
                                foreach ($retorno as $row) {
                                    ?>
                                    <option value="<?=$row["GRU_GRUPO"]?>"><?=($row["GRU_DESC"])?></option>
                                    <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="curva-dataini">Período de:</label>
                                <input type="date" name="curva-dataini" id="curva-dataini" class="form-control" value="<?=date('Y-m-d')?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="curva-datafim">Até:</label>
                                <input type="date" name="curva-datafim" id="curva-datafim" class="form-control" value="<?=date('Y-m-d')?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="curva-classificacao">Classificação: </label>
                                <select class="form-control" name="curva-classificacao" id="curva-classificacao">
                                    <option value="1" >Quantidade</option>
                                    <option value="2" >Valor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="_lista()" data-dismiss="modal">Filtrar</button>
               
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

<!-- Modal Rel -->
<div id="custom-modal-csv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    
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

    function _lista() {
        var $_keyid = "ACCVABC";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function imprimeConteudo() {
        var $_keyid = "ACCVABC";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $('#tablea-impressa').html(result);
            });
        $('#tablea-impressa').printThis();
    }
    function imprimeCSV() {
        var $_keyid = "ACCVABC";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){
                $('#custom-modal-csv').html(result);
            });
     
    }

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function _fechar() {
                var $_keyid = "_Na00006";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
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