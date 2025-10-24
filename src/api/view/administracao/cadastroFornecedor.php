<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >

<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();
$id = $_chaveid;

$statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante WHERE CODIGO_FABRICANTE = '$id'");
$retorno = $statement->fetch();
$endereco = explode(", ", $retorno["ENDERECO"]);
?>

<div class="wrapper">
    <div class="container">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-xs-12">
                <h4 class="page-title m-t-15">Cadastro de Fornecedores</h4>
                <p class="text-muted page-title-alt">Cadastre seus fornecedores.</p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <div class="panel-body">
                        <form action="javascript:void(0)" method="post" enctype="multipart/form-data" <?=empty($id) ? "name='form-inclui' id='form-inclui'" : "name='form-altera' id='form-altera'"?>>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-nome">Nome Fantasia<strong class="text-danger">*</strong>:</label>
                                    <input type="text" class="form-control" id="fornecedor-nome" name="fornecedor-nome" value="<?=$retorno["NOME"]?>" required>
                                    <input type="hidden" id="fornecedor-id" name="fornecedor-id" value="<?=$retorno["CODIGO_FABRICANTE"]?>">
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-razao">Razão Social:</label>
                                    <input type="text" class="form-control" id="fornecedor-razao" name="fornecedor-razao" value="<?=$retorno["RAZAO_SOCIAL"]?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-4">
                                    <label for="fornecedor-status">Status:</label>
                                    <select name="fornecedor-status" id="fornecedor-status" class="form-control">
                                        <option value="0" <?=$retorno["ativo"] == 0 ? "selected" : ""?>>Ativo</option>
                                        <option value="-1" <?=$retorno["ativo"] == -1 ? "selected" : ""?>>Inativo</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="fornecedor-tipo">Tipo<strong class="text-danger">*</strong>:</label>
                                    <select name="fornecedor-tipo" id="fornecedor-tipo" class="form-control" required>
                                        <option value="">Selecione</option>
                                        <?php
                                        $consultaTip = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tabtipocliente ORDER BY Descricao_Cliente");
                                        $resultTip = $consultaTip->fetchAll();

                                        foreach ($resultTip as $row)
                                        {
                                            ?>
                                            <option value="<?=$row["Cod_Tipo_Cliente"]?>" <?=$retorno["for_Tipo"] == $row["Cod_Tipo_Cliente"] ? "selected" : ""?>><?=$row["Descricao_Cliente"]?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="fornecedor-atividade">Atividade:</label>
                                    <input type="text" class="form-control" id="fornecedor-atividade" name="fornecedor-atividade" value="<?=$retorno["atividade"]?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-4">
                                    <label for="cep">CEP:</label>
                                    <input type="text" class="form-control" id="cep" name="fornecedor-cep" value="<?=$retorno["CEP"]?>">
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="rua">Endereço:</label>
                                    <input type="text" class="form-control" id="rua" name="fornecedor-endereco" value="<?=$endereco[0]?>">
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="fornecedor-num">N°:</label>
                                    <input type="number" class="form-control" id="fornecedor-num" name="fornecedor-num" value="<?=$endereco[1]?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-4">
                                    <label for="bairro">Bairro:</label>
                                    <input type="text" class="form-control" id="bairro" name="fornecedor-bairro" value="<?=$retorno["BAIRRO"]?>">
                                </div>
                                <div class="form-group col-xs-4">
                                    <label for="cidade">Cidade:</label>
                                    <input type="text" class="form-control" id="cidade" name="fornecedor-cidade" value="<?=$retorno["CIDADE"]?>">
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
                                            <option value="<?=$row["estado_sigla"]?>" <?=$retorno["UF"] == $row["estado_sigla"] ? "selected" : ""?>><?=$row["nome_estado"]?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-telefone">Telefone:</label>
                                    <input type="text" class="form-control" id="fornecedor-telefone" name="fornecedor-telefone" value="<?=$retorno["TELEFONE"]?>">
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-contato">Contato:</label>
                                    <input type="text" class="form-control" id="fornecedor-contato" name="fornecedor-contato" value="<?=$retorno["FOR_CONTATO1"]?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-cnpj">CNPJ:</label>
                                    <input type="text" class="form-control" id="fornecedor-cnpj" name="fornecedor-cnpj" value="<?=$retorno["CNPJ"]?>">
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-inscricao">IE:</label>
                                    <input type="text" class="form-control" id="fornecedor-inscricao" name="fornecedor-inscricao" value="<?=$retorno["INSCR_ESTADUAL"]?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-email">E-mail:</label>
                                    <input type="email" class="form-control" id="fornecedor-email" name="fornecedor-email" value="<?=$retorno["email"]?>">
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="fornecedor-site">Site:</label>
                                    <input type="url" class="form-control" id="fornecedor-site" name="fornecedor-site" value="<?=$retorno["site"]?>">
                                </div>
                            </div>
                            <div class="form-group text-left">
                                <button class="btn btn-success waves-effect waves-light" type="submit" >Salvar</button>
                                <button class="btn btn-default waves-effect waves-light m-l-5" id="voltar" type="button">Voltar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-footer">
                    <p class="text-danger">
                        Campos marcados com * são de preenchimento obrigatório.
                    </p>
                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="result">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
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

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>

<script type="text/javascript">

    $('#voltar').click(function () {
        var $_keyid = "CAFORNCLT";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    });

    $('#form-inclui').submit(function (e) {
        e.preventDefault();

        var $_keyid = "ACCAFORNC";
        var dados = $(this).serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
            });
    });


    $('#form-altera').submit(function (e) {
        e.preventDefault();

        var $_keyid = "ACCAFORNC";
        var dados = $(this).serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
            });
    });

    function fechar() {
        var $_keyid = "CAFORNCLT";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    };
    
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