<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

$consulta = $pdo->query("SELECT extra_A_label, extra_B_label FROM ".$_SESSION['BASE'].".parametro");
$parametro = $consulta->fetch();

$extra_a = $parametro['extra_A_label'];
$extra_b = $parametro['extra_B_label'];
?>
<style type="text/css">
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000;}
</style>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Relatórios</h4>
                <p class="text-muted page-title-alt">Selecione o tipo de relatório para consulta.</p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <form action="javascript:void(0)" name="form-inclui" id="form-inclui" method="post">
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-6">
                                <label for="relatorio-tipo">Relatório:</label>
                                <select name="relatorio-tipo" id="relatorio-tipo" class="form-control" onchange="verificaRelatorio()">
                                    <option value="0">Selecione</option>
                                    <option value="1">Relação de Produtos - Com Estoque</option>
                                    <option value="2">Relação Geral de Produtos - Código Barras</option>
                                    <option value="3">Relação de Produtos - Estoque Mínimo</option>
                                   <!-- <option value="4">Relação Geral de Produtos - Não Vendidos por período</option> 
                                    <option value="5">Relação de Produtos - Por Lista</option>-->
                                    <option value="6">Relação de Notas Fiscais de Entrada</option>
                                    <option value="7">Relação de Geral Produtos - Filtros</option>
                                    <option value="8">Relação de Produtos - Por Validade</option>
                                    <option value="9">Relação Geral - Endereços </option>
                                     <!-- <option value="10">Exportar Electrolux - Sell Out </option>-->
                                </select>
                            </div>
                            <div class="form-group col-md-2 col-xs-2" id="input-endereco" style="display: none;">
                                <label for="relatorio-endereco">Com Endereços:</label>
                                <select name="relatorio-endereco" id="relatorio-endereco" class="form-control">
                                    <option value="0"></option>
                                    <option value="1">Sim</option>                                   
                                </select>
                            </div>
                            <div class="form-group col-md-2 col-xs-6" id="input-estoque" style="display: none;">
                                <label for="relatorio-estoque">Filtrar Estoque:</label>
                                <select name="relatorio-estoque" id="relatorio-estoque" class="form-control">
                                    <option value="0">Todos</option>
                                    <option value="1">Com Estoque</option>
                                    <option value="2">Sem Estoque</option>
                                </select>
                            </div>
                              <div class="form-group col-md-2 col-xs-6" id="input-estoqueminimo" style="display: none;">
                                <label for="relatorio-estoque">Filtrar Mínimo:</label>
                                <select name="relatorio-estoqueminimo" id="relatorio-estoqueminimo" class="form-control">
                                    <option value="0">Todos</option>
                                    <option value="1">Abaixo Mínimo</option>
                                   
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-extra-a" style="display: none;">
                                <label for="relatorio-extra-a"><?=$extra_a?>:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".extra_a ORDER BY extraA_descricao");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-extra-a" id="relatorio-extra-a" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['extraA_id']?>"><?=$row['extraA_descricao']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-extra-b" style="display: none;"> 
                                <label for="relatorio-extra-b"><?=$extra_b?>:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".extra_b ORDER BY extraB_descricao");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-extra-b" id="relatorio-extra-b" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['extraB_id']?>"><?=$row['extraB_descricao']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-grupo" style="display: none;"> 
                                <label for="relatorio-grupo">Grupo:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".grupo ORDER BY GRU_DESC");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-grupo" id="relatorio-grupo" class="form-control">
                                    <option value="">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['GRU_GRUPO']?>"><?=$row['GRU_DESC']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-linha" style="display: none;"> 
                                <label for="relatorio-linha">Linha:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".linha ORDER BY linha_descricao");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-linha" id="relatorio-linha" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['linha_codigo']?>"><?=$row['linha_descricao']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-fornecedor" style="display: none;"> 
                                <label for="relatorio-fornecedor">Fornecedor:</label>
                                <?php
                                $consulta = $pdo->query("SELECT CODIGO_FABRICANTE, NOME FROM ".$_SESSION['BASE'].".fabricante ORDER BY NOME");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-fornecedor" id="relatorio-fornecedor" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['CODIGO_FABRICANTE']?>"><?=$row['NOME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                           
                            <div class="form-group col-md-3 col-xs-6" id="input-lista" style="display: none;"> 
                                <label for="relatorio-lista">Lista:</label>
                                <?php
                                $consulta = $pdo->query("SELECT GRUPO_PECAS FROM ".$_SESSION['BASE'].".itemestoque WHERE GRUPO_PECAS <> '' GROUP BY GRUPO_PECAS ORDER BY GRUPO_PECAS");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-lista" id="relatorio-lista" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['GRUPO_PECAS']?>"><?=$row['GRUPO_PECAS']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 col-xs-6" id="input-almox" style="display: none;"> 
                                <label >Almoxarifado:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".almoxarifado ORDER BY Descricao");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="rel-almox" id="rel-almox" class="form-control">
                                    <option value="0">Todos</option>
                                   
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['Codigo_Almox']?>"><?=$row['Descricao']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-csv" style="display: none;"> 
                                <label >Tipo:</label>
                              
                                <select name="relatorio-arquivo" id="relatorio-arquivo" class="form-control">
                                    <option value="1">Visualizar</option>
                                    <option value="2">Gerar CSV</option>
                                  
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6" id="input-dias" style="display: none;">
                                <label for="relatorio-dias">Dias para Vencer:</label>
                                <input type="number" name="relatorio-dias" id="relatorio-dias" class="form-control">
                            </div>
                           
                        </div>
                        <div class="row"  id="input-LOCAL" style="display: none;">
                                                <div class="col-md-2 col-xs-12">
                                                    <label><?=empty($retornoParametro["empresa_labelEnderA"]) ? "Endereço A": $retornoParametro["empresa_labelEnderA"]?>:</label>
                                                    <select name="_enderA" id="_enderA" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderB('<?=$_opcaoEnder;?>')">
                                                        <option value="">-</option>
                                                        <?php 
                                                        $consulta_produto = "Select `le_enderA`
                                                                            from " . $_SESSION['BASE'] . ".localestoque group by le_enderA ORDER BY le_enderA ASC";
                                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                        while ($rst = mysqli_fetch_array($resultado)) {
                                                        ?>
                                                            <option value="<?= $rst['le_enderA']; ?>" <?php if($rst['le_enderA'] == $result["ENDERECO1"]) { ?>selected="selected" <?php } ?>><?= $rst['le_enderA'];?></option>
                                                        <?php

                                                        }
                                                   
                                                        ?>
                                                    </select>
                                                </div>
                                            <div class="col-md-2 col-xs-12">
                                                <label><?=empty($retornoParametro["empresa_labelEnderB"]) ? "Edereço B": $retornoParametro["empresa_labelEnderB"]?>:</label></label>
                                                <span id="_enderBcm">
                                                <select name="_enderB" id="_enderB" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderC('<?=$_opcaoEnder;?>')">
                                                        <option value="">-</option>
                                                        <?php 
                                                        $consulta_produto = "Select `le_enderB`
                                                                            from " . $_SESSION['BASE'] . ".localestoque 
                                                                            where le_enderA = '".$result["ENDERECO1"]."' group by le_enderB ORDER BY le_ordemA ASC";
                                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                        while ($rst = mysqli_fetch_array($resultado)) {
                                                        ?>
                                                            <option value="<?= $rst['le_enderB']; ?>" <?php if($rst['le_enderB'] == $result["ENDERECO2"]) { ?>selected="selected" <?php } ?>><?= $rst['le_enderB'];?></option>
                                                        <?php

                                                        }
                                                   
                                                        ?>
                                                    </select>
                                                    
                                                </span>

                                            </div>
                                            <div class="col-md-2 col-xs-12">
                                                <label><?=empty($retornoParametro["empresa_labelEnderC"]) ? "Endereço C": $retornoParametro["empresa_labelEnderC"]?>:</label></label>
                                                <span id="_enderCcm">
                                                <select name="_enderC" id="_enderC" class="form-control input-sm" style="height:40px;font-size:large" >
                                                        <option value="">-</option>
                                                        <?php 
                                                        $consulta_produto = "Select `le_enderC`
                                                                            from " . $_SESSION['BASE'] . ".localestoque 
                                                                            where le_enderA = '".$result["ENDERECO1"]."' and le_enderB = '".$result["ENDERECO2"]."'  group by le_enderC ORDER BY le_ordemA ASC";
                                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                        while ($rst = mysqli_fetch_array($resultado)) {
                                                        ?>
                                                            <option value="<?= $rst['le_enderC']; ?>" <?php if($rst['le_enderC'] == $result["ENDERECO3"]) { ?>selected="selected" <?php } ?>><?= $rst['le_enderC'];?></option>
                                                        <?php

                                                        }
                                                   
                                                        ?>
                                                    </select>
                                                
                                                </span>
                                            </div>
                                        </div>
                        <div class="row" >
                             <div class="form-group col-md-1 col-xs-6" id="input-dataini"  style="display: none;">
                                <label for="relatorio-dataini">Período de:</label>
                                <input type="date" name="relatorio-dataini" id="relatorio-dataini" class="form-control" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="form-group col-md-1 col-xs-6" id="input-datafim" style="display: none;">
                                <label for="relatorio-datafim">Até:</label>
                                <input type="date" name="relatorio-datafim" id="relatorio-datafim" class="form-control" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="form-group col-md-2 col-xs-12" id="input-tipodoc" style="display: none;" > 
                                <label >Informe Tipo Arquivo:</label>                              
                                <select name="relatorio-tipodoc" id="relatorio-tipodoc" class="form-control">
                                    <option value="1">Ordem Serviço</option>
                                    <option value="2">Vendas</option>                                  
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-xs-6 m-t-26">
                                <input type="hidden" name="relatorio-tabela" id="relatorio-tabela">
                                <button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5" onclick="_relatorio()"><span class="btn-label"><i class="fa fa-check"></i></span>Gerar</button>
                            </div>
                        </div>
                     
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Relatório -->
<div id="custom-modal-relatorio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Imprime -->
<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
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
    <input type="hidden" id="id-busca" name="id-busca" value="">
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

<!-- Datatables -->
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

    function _fechar() {
        var $_keyid = "_Na00006";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function buscaEnderB(_tipo) {
            var $_keyid = "ACPRD";
            var dados = $("#form-inclui :input").serializeArray();
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 7
                },
                function(result) {                   
                    $("#_enderBcm").html(result);
                    $("#_enderCcm").html('-');
                });
        }

        function buscaEnderC(_tipo) {
            var $_keyid = "ACPRD"; 
            var dados = $("#form-inclui :input").serializeArray();
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 8
                },
                function(result) {                    
                    $("#_enderCcm").html(result);
                });
        }


    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACRLTS";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                result = jQuery.parseJSON( result );
                
                $("#produto-etq").val(result.produto);
                $('#valor-etq').val(result.promocao);

            });
        
        setTimeout(() => {
            if ($("#produto-etq").val() == "Produto não encontrado") {
            $('#valida-etq').val('0');
            }
            else {
                $('#valida-etq').val('1');
            }
        }, 900);
    }

    function _relatorio() {
        $('#relatorio-tabela').val(1);
        var $_keyid = "ACRLTS";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde("#custom-modal-relatorio");
        $("#custom-modal-relatorio").modal('show');
      
            $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                $("#custom-modal-relatorio").html(result);
                if($('#relatorio-arquivo').val() == 1){
                $('#tabela-relatorio').DataTable();
                }
            });


       
    }

    function imprimeModal() {
        $('#relatorio-tabela').val(0);
        var $_keyid = "ACRLTS";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                $("#tablea-impressa").html(result);
            });
        $("#tablea-impressa").printThis();
    }

    function verificaRelatorio() {
        var relatorio = $('#relatorio-tipo').val();

        $('#input-estoque').hide();
        $('#input-estoqueminimo').hide();        
        $('#input-extra-a').hide();
        $('#input-extra-b').hide();
        $('#input-grupo').hide();
        $('#input-linha').hide();
        $('#input-fornecedor').hide();
        $('#input-dataini').hide();
        $('#input-datafim').hide();
        $('#input-lista').hide();
        $('#input-almox').hide();
        $('#input-dias').hide();
        $('#input-endereco').hide();
        $('#input-LOCAL').hide();
        $('#input-tipodoc').hide();
        $('#input-csv').hide();   
       

        switch (relatorio) {
            case "1":                
                $('#input-endereco').show();
                break;
            case "2":                
                $('#input-endereco').show();
                break;    
            case "3":
                $('#input-grupo').show();
                $('#input-endereco').show();
                $('#input-estoqueminimo').show();   
                 $('#input-csv').show(); 
                break;
            case "4":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-endereco').show();
                break;
            case "5":
                $('#input-lista').show();
                $('#input-endereco').show();
                break;
            case "6":
                $('#input-fornecedor').show();
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-endereco').show();
                break;
            case "7":
                $('#input-estoque').show();
                $('#input-extra-a').show();
                $('#input-extra-b').show();
                $('#input-grupo').show();
                $('#input-linha').show();
                $('#input-almox').show();
                $('#input-endereco').show();
                break;
            case "8":
                $('#input-estoque').show();
                $('#input-dias').show();
                $('#input-endereco').show();
                break;
            case "9":
                $('#input-estoque').show();     
                $('#input-almox').show(); 
                $('#input-csv').show();   
                $('#input-LOCAL').show(); 
                    
                break;
            case "10":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-tipodoc').show();
                break;
               
            default:
                break;
        }
    }

    function aguarde(id) {
        $(id).html('' +
            '<div class="modal-dialog">' +
                '<div class="modal-content text-center">' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                    '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
                    '</div>' +
                '</div>' +
            '</div>');
    }
</script>
</body>
</html>