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
                <h4 class="page-title m-t-15">Notas de Entrada</h4>
                <p class="text-muted page-title-alt">Verifique suas notas de entrada.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span> Filtros</button>
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal-incluir-nota"><span class="btn-label"><i class="fa fa-plus"></i></span> Incluir NF</button>
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal-incluir-xml"><span class="btn-label"><i class="fa fa-plus"></i></span> XML</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card-box table-responsive" id="listagem"></div>
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
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="nf-fornecedor">Fornecedor: </label>
                                <select class="form-control" name="nf-fornecedor" id="nf-fornecedor">
                                <?php
                                $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante ORDER BY nome");
                                $retorno = $statement->fetchAll();
                                ?>
                                    <option value="">Selecione</option>
                                <?php
                                foreach ($retorno as $row) {
                                ?>
                                    <option value="<?=$row["CODIGO_FABRICANTE"]?>"><?=$row["NOME"]?></option>
                                <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">                        
                                    <label for="field-1" class="control-label">Nº Nota</label>                           
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="_numeronf" name="_numeronf">
                                    </div>                               
                        </div>
                    </div>

                    <div class="row m-b-10">
                            <div class="col-md-6" >  
                                <label for="nf-fornecedor">Fitrar Data por </label>
                                <select class="form-control" name="rel-dt" id="rel-dt">
                                    <option value="NFE_DATAENTR">DT Entrada</option>
                                    <option value="NFE_DATAEMIS">DT Emissão</option>
                                </select>
                            </div>   
                        <div class="col-md-6" >  
                                <label for="nf-fornecedor">Rel.Substituição Tribuário por NF </label>
                                <select class="form-control" name="rel-fornecedor" id="rel-fornecedor">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                        </div>                                     
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-6" id="lotecomst">  
                             <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarcomBase()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar C/ Substituição Tributária</button>
                             
                        </div>
                        <div class="col-md-6" id="lotesemst">  
                             <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarsemBase()" id="_bt000447" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar S/ Substituição Tributária</button>
                        </div>                      
                    </div>
                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_lista()" data-dismiss="modal">Buscar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incluir Nota-->
<div id="custom-width-modal-incluir-nota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Incluir Nota Fiscal</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" id="form-inclui" name="form-inclui">
                    <div class="form-group col-md-4">
                        <label for="nf-num">N° da Nota</label>
                        <input type="number" class="form-control" id="nf-num" name="nf-num">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="nf-fornec">Fornecedor</label>
                        <select class="form-control" name="nf-fornec" id="nf-fornec">
                            <?php
                            $statement = $pdo->query("SELECT CODIGO_FABRICANTE,CNPJ,NOME FROM ". $_SESSION['BASE'] .".fabricante ORDER BY nome");
                            $retorno = $statement->fetchAll();
                            ?>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($retorno as $row) {
                                ?>
                                <option value="<?=$row["CODIGO_FABRICANTE"]?>"> <?=$row["NOME"]?> (<?=$row["CNPJ"]?>)</option>
                                <?php
                            }
                            ?>
                        </select>
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

<!-- Modal Incluir XML-->
<div id="custom-width-modal-incluir-xml" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Incluir XML</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" id="form-xml" name="form-xml">
                    <div class="form-group">
                        <label class="control-label">Selecione o XML:</label>
                        <input type="file" class="filestyle" name="nota-xml" id="nota-xml" accept="text/xml" onchange="return isXml(this)" data-placeholder="Sem arquivos">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluiXML()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir Produto-->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir a Nota? </h2>
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
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-xml" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
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

    $(document).ready(function () {

$(formOS).submit(function(){ //pesquisa os
     
     var $_keyid =   "S00001";                     
     $('#_keyform').val($_keyid);   
                             
         var dados = $("#formOS :input").serializeArray();
         dados = JSON.stringify(dados);		
                    
         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
           $('#_chaveid').val($('#numOS').val());   
           $("#form1").submit();  

  });

 });
      
                 
});

    function _fechar() {
        var $_keyid = "_Na00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id)
        var $_keyid = "";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
            });
    }

    function _incluir() {
        var $_keyid = "ACNFENTLT";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });
    }

    function _incluiXML() {
        var form_data = new FormData(document.getElementById("form-xml"));
        aguarde();
        $.ajax({
            url: 'acaoXML.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                $("#custom-modal-result").modal('show').html(data);
            }
        });
    }

    function _lista() {
        var $_keyid = "ACNFENTLT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _alterar(nota, fabricante){
        var $_keyid = "NFENT";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(nota+"|"+fabricante);
        $('#form1').submit();

    }

    function _idexcluir(nota, fornecedor) {
        $('#id-nota').val(nota);
        $('#id-fornecedor').val(fornecedor);
    }

    function _excluir() {
        var $_keyid = "ACNFENTLT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });

    }

    function _buscaSelect(id, retorno) {
        $("#id-filtro").val(id);
        var $_keyid = "";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $(retorno).html(result);
            });
    }

    function isXml(input)
    {
        var value = input.value;
       // var res = value.substr(value.lastIndexOf('.')) == '.xml';
        var res = /\.xml$/i.test(value);
        if (!res) {
            input.value = "";
            $('#custom-width-modal-incluir-xml').modal('hide');
            $('#custom-modal-xml').modal('show');
        }
        return res;
    }

    function gerarsemBase(){
        
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#lotesemst');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 8},
            function(result){
            
                $('#lotesemst').html(result);
               
            });
    }


    function gerarcomBase(){
        
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#lotecomst');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
            function(result){
            
                $('#lotecomst').html(result);
               
            });
    }



  

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
</script>

</body>
</html>

