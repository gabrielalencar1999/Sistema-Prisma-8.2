<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

// $consultaUsuario = $pdo->query("SELECT usuario_perfil2 FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION["IDUSER"]."'");
// $retornoUsuario = $consultaUsuario->fetch();

// $consultaPermissao = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".permissao WHERE permissao_id = '".$retornoUsuario["usuario_perfil2"]."'");
// $retornoPermissao = $consultaPermissao->fetch();
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Listagem Estoque</h4>
                <p class="text-muted page-title-alt">Consulta</p>
            </div>
            <div class="btn-group pull-right m-t-5">
                <div class="m-b-5">
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span>Filtros</button>
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="listagem">
                    <div class="alert alert-warning text-center">
                         Selecione os filtro para listar os produtos.
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
                <h4 class="modal-title">Filtros de produtos</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="produto-filtro">Filtro: </label>
                                <select class="form-control" name="produto-filtro" id="produto-filtro" >
                                    <option value="0">Cód. Interno</option>
                                    <option value="1">Cód. Barra</option>
                                    <option value="3"  selected="selected">Cód. Fabricante</option>
                                    <option value="2">Descrição</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="produto-pesquisa">Pesquisar: </label>
                                <input type="text" class="form-control" name="produto-pesquisa" id="produto-pesquisa" value="" onKeyDown="TABEnter('','_pesq')">
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button id="_pesq" type="button" class="btn btn-success waves-effect waves-light" onclick="_lista()" data-dismiss="modal">Buscar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Estoque -->
<div id="custom-modal-estoque" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Estoque</h4>
            </div>
            <div class="modal-body" id="id_estoque">
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
         
            </div>
        </div>
    </div>
</div>
<!-- Modal Incluir-->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Incluir Fornecedor</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <label for="fornecedor-nome">Nome Fantasia:</label>
                            <input type="text" class="form-control" id="fornecedor-nome" name="fornecedor-nome">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <label for="fornecedor-razao">Razão Social:</label>
                            <input type="text" class="form-control" id="fornecedor-razao" name="fornecedor-razao">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-8">
                            <label for="fornecedor-status">Status:</label>
                            <select name="fornecedor-status" id="fornecedor-status" class="form-control">
                                <option value="0" selected>Ativo</option>
                                <option value="-1">Inativo</option>
                            </select>
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="cep">CEP:</label>
                            <input type="text" class="form-control" id="cep" name="fornecedor-cep">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-8">
                            <label for="rua">Endereço:</label>
                            <input type="text" class="form-control" id="rua" name="fornecedor-endereco">
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="fornecedor-num">N°:</label>
                            <input type="number" class="form-control" id="fornecedor-num" name="fornecedor-num">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <label for="bairro">Bairro:</label>
                            <input type="text" class="form-control" id="bairro" name="fornecedor-bairro">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-8">
                            <label for="cidade">Cidade:</label>
                            <input type="text" class="form-control" id="cidade" name="fornecedor-cidade">
                        </div>
                        <div class="form-group col-xs-4">
                            <label for="uf">Estado:</label>
                            <select name="fornecedor-uf" id="uf" class="form-control">
                                <option value="0">Selecione</option>
                                <?php
                                $consultaUF = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".estado ORDER BY nome_estado");
                                $resultUF = $consultaUF->fetchAll();

                                foreach ($resultUF as $row)
                                {
                                    ?>
                                    <option value="<?=$row["estado_sigla"]?>"><?=utf8_encode($row["nome_estado"])?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-telefone">Telefone:</label>
                            <input type="text" class="form-control" id="fornecedor-telefone" name="fornecedor-telefone">
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-contato">Contato:</label>
                            <input type="text" class="form-control" id="fornecedor-contato" name="fornecedor-contato">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-cnpj">CNPJ:</label>
                            <input type="text" class="form-control" id="fornecedor-cnpj" name="fornecedor-cnpj">
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-inscricao">IE:</label>
                            <input type="text" class="form-control" id="fornecedor-inscricao" name="fornecedor-inscricao">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-tipo">Tipo:</label>
                            <select name="fornecedor-tipo" id="fornecedor-tipo" class="form-control">
                                <option value="0">Selecione</option>
                                <?php
                                $consultaTip = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tabtipocliente ORDER BY Descricao_Cliente");
                                $resultTip = $consultaTip->fetchAll();

                                foreach ($resultTip as $row)
                                {
                                    ?>
                                    <option value="<?=$row["Cod_Tipo_Cliente"]?>"><?=utf8_encode($row["Descricao_Cliente"])?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-atividade">Atividade:</label>
                            <input type="text" class="form-control" id="fornecedor-atividade" name="fornecedor-atividade">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-email">E-mail:</label>
                            <input type="email" class="form-control" id="fornecedor-email" name="fornecedor-email">
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="fornecedor-site">Site:</label>
                            <input type="url" class="form-control" id="fornecedor-site" name="fornecedor-site">
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

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
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
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>

<script type="text/javascript">

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

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACPRDLT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
            });
    }

    function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }
    
    function TABEnter(oEvent,tabA){
   
   var oEvent = (oEvent)? oEvent : event;
   var oTarget =(oEvent.target)? oEvent.target : oEvent.srcElement;
   if(oEvent.keyCode==13){
   if(oTarget.type=="text" && oEvent.keyCode==13){
    _lista(); 
    $(' #custom-modal-filtro').modal('hide');
   }                        

     
   }
}


    function _incluir() {
        var $_keyid = "ACPRDLT";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                $("#form-inclui :input").val("");
                _lista();
              ;

            });
    }

    function _lista() {
        var $_keyid = "ACPRDLTtec";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _alterar(id){
        var $_keyid = "PRD";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(id);
        $('#form1').submit();

    }

    function _estoque(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACPRDLT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#id_estoque');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 9},
            function(result){
                $("#id_estoque").html(result);
              
            });
    }

    

    function _idexcluir(id) {
        $('#id-exclusao').val(id);
    }

    function _excluir() {
        var $_keyid = "ACPRDLT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });
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

    $('#custom-modal-filtro').modal('show');
    $('#produto-pesquisa').focus();
  
</script>

</body>
</html>