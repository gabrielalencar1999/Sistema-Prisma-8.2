<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

if ($_SESSION['COLABORADOR_ID'] != $_SESSION['BASE_ID']) {
    $consulta = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".empresa_cadastro c LEFT JOIN " . $_SESSION['BASE'] . ".empresa_dados d ON c.id = d.id WHERE c.id = '".$_SESSION['COLABORADOR_ID']."'");
    $retorno = $consulta->fetch(\PDO::FETCH_OBJ);
} else {
    $retorno = null;
}

$consulta = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".item_lista_servico ORDER BY id");
$servicos = $consulta->fetchAll(\PDO::FETCH_OBJ);

?>
<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <div class="ajax_load_box_title">Aguarde, carrengando...</div>
    </div>
</div>
<div class="wrapper">
    <div class="container">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-xs-12">
                <h4 class="page-title m-t-15">Dados Cadastrais</h4>
                <p class="text-muted page-title-alt">Cadastre os dados de sua empresa, parametros fiscais entre outros.</p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <div class="panel-body">
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados Cadastrais</a>
                            </li>
                        </ul>
                        <form action="acaoCadastroEmpresa" method="post" name="form-alterar" id="form-alterar">
                            <div class="tab-content br-n pn">
                                <div id="navpills-11" class="tab-pane active">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="razao">Razão Social<b class="text-danger">*</b>:</label>
                                            <input type="text" name="razao" class="form-control" id="razao" value="<?=$retorno != null ? $retorno->razao_social : '' ?>" required>
                                            <input type="hidden" name="empresa" id="empresa" value="<?=$retorno != null ? $retorno->id : '' ?>">
                                            <input type="hidden" name="acao" id="acao" value="<?=$retorno != null ? '4' : '3'?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="fantasia">Nome Fantasia<b class="text-danger">*</b>:</label>
                                            <input type="text" name="fantasia" class="form-control" id="fantasia" value="<?=$retorno != null ? $retorno->nome_fantasia : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cnpj">CNPJ<b class="text-danger">*</b>:</label>
                                            <input type="text" name="cnpj" class="form-control" id="cnpj" value="<?=$retorno != null ? $retorno->cnpj : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="cep">CEP<b class="text-danger">*</b>:</label>
                                            <input type="text" name="cep" class="form-control" id="cep" value="<?=$retorno != null ? $retorno->cep : '' ?>" require>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="rua">Endereço<b class="text-danger">*</b>:</label>
                                            <input type="text" name="rua" class="form-control" id="rua" value="<?=$retorno != null ? $retorno->logradouro : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="numero">N°:</label>
                                            <input type="number" name="numero" class="form-control" id="numero" value="<?=$retorno != null ? $retorno->numero : '' ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="complemento">Complemento:</label>
                                            <input type="text" name="complemento" class="form-control" id="complemento" value="<?=$retorno != null ? $retorno->complemento : '' ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="bairro">Bairro<b class="text-danger">*</b>:</label>
                                            <input type="text" name="bairro" class="form-control" id="bairro" value="<?=$retorno != null ? $retorno->bairro : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cidade">Cidade<b class="text-danger">*</b>:</label>
                                            <input type="text" name="cidade" class="form-control" id="cidade" value="<?=$retorno != null ? $retorno->municipio : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="uf">Estado<b class="text-danger">*</b>:</label>
                                            <select name="uf" id="uf" class="form-control" require?>d>
                                                <option value="">Selecione</option>
                                                <?php
                                                $consultaUF = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".estado ORDER BY nome_estado");
                                                $resultUF = $consultaUF->fetchAll(\PDO::FETCH_OBJ);

                                                foreach ($resultUF as $row): ?>
                                                    <option value="<?=$row->estado_sigla?>"<?=$retorno != null && $row->estado_sigla == $retorno->uf ? " selected" : ""?>><?=$row->nome_estado?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cpf-cnpj-contabilidade">CPF/CNPJ Contabilidade:</label>
                                            <input name="cpf-cnpj-contabilidade" type="text" class="form-control" id="cpf-cnpj-contabilidade" value="<?=$retorno != null ? $retorno->cpf_cnpj_contabilidade : '' ?>"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="telefone">Telefone<b class="text-danger">*</b>:</label>
                                            <input name="telefone" type="text" class="form-control" id="telefone" value="<?=$retorno != null ? $retorno->telefone : '' ?>" size="55" maxlength="50" required/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="email">Email<b class="text-danger">*</b>:</label>
                                            <input name="email" type="email" class="form-control" id="email" value="<?=$retorno != null ? $retorno->email : '' ?>" required/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contato-nome">Contato Nome:</label>
                                            <input name="contato-nome" type="text" class="form-control" id="contato-nome" value="<?=$retorno != null ? $retorno->contato_nome : '' ?>"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contato-telefone">Contato Telefone:</label>
                                            <input name="contato-telefone" type="text" class="form-control" id="contato-telefone" value="<?=$retorno != null ? $retorno->contato_telefone : '' ?>" size="55" maxlength="50"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contato-email">Contato Email:</label>
                                            <input name="contato-email" type="email" class="form-control" id="contato-email" value="<?=$retorno != null ? $retorno->contato_email : '' ?>"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="regime-tributario">Regime Tributário<b class="text-danger">*</b>:</label>
                                            <select name="regime-tributario" id="regime-tributario" class="form-control" required>
                                                <option value="">Selecione</option>
                                                <option value="1" <?=$retorno != null && $retorno->regime_tributario == 1 ? 'selected' : ''?>>Simples Nacional</option>
                                                <option value="2" <?=$retorno != null && $retorno->regime_tributario == 2 ? 'selected' : ''?>>Simples Nacional - Excesso de Receita Bruta</option>
                                                <option value="3" <?=$retorno != null && $retorno->regime_tributario == 3 ? 'selected' : ''?>>Regime Normal</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" id="item-servico-select">
                                            <label for="item-servico">Item Serviço<b class="text-danger">*</b>:</label>
                                            <select name="item-servico" id="item-servico" class="form-control" required>
                                                <option value="">Selecione</option>
                                                <?php foreach($servicos as $row): ?>
                                                <option value="<?=$row->id?>" <?=$retorno->item_lista_servico == $row->id ? 'selected' : ''?>><?=$row->descricao?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="inscricao-municipal" id="label-inscricao-municipal">Inscrição Municipal<b class="text-danger">*</b>:</label>
                                            <input type="number" name="inscricao-municipal" id="inscricao-municipal" class="form-control" value="<?=$retorno != null ? $retorno->inscricao_municipal : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="aliquota">Aliquota<b class="text-danger">*</b>:</label>
                                            <input type="text" name="aliquota" id="aliquota" class="form-control" value="<?=$retorno != null ? $retorno->aliquota_nota : '' ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="serie-nfse-prod">Série<b class="text-danger">*</b>:</label>
                                            <input type="number" name="serie-nfse-prod" id="serie-nfse-prod" class="form-control" value="<?=$retorno != null ? $retorno->serie_nfse_producao : '' ?>" <?=$retorno != null && $retorno->habilita_nfse ? 'required' : '' ?>>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="proxima-nfse-prod">Próximo Numero<b class="text-danger">*</b>:</label>
                                            <input type="number" name="proxima-nfse-prod" id="proxima-nfse-prod" class="form-control" value="<?=$retorno != null ? $retorno->proximo_numero_nfse_producao : '' ?>" <?=$retorno != null && $retorno->habilita_nfse ? 'required' : '' ?>>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="arquivo-logo">Arquivo Logo:</label>
                                            <input type="file" name="arquivo-logo" id="arquivo-logo" class="filestyle">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="arquivo-certificado">Arquivo Certificado<?=$retorno == null ? '<b class="text-danger">*</b>' : ''?>:</label>
                                            <input type="file" name="arquivo-certificado" id="arquivo-certificado" class="filestyle" <?=$retorno == null ? 'required' : ''?>>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="senha-certificado" id="label-senha-certificado">Senha Certificado <?=$retorno == null ? '<b class="text-danger">*</b>' : ''?>:</label>
                                            <input type="password" name="senha-certificado" id="senha-certificado" class="form-control" <?=$retorno == null ? 'required' : ''?>>
                                        </div>
                                        <div class="col-md-6 text-left m-b-20">
                                            <h4 class="page-title m-b-15">Login Prefeitura</h4>
                                            <p>Algumas prefeituras utilizam usuário e senha para realizar a emissão de NFSe. Se for o caso para este município os dados podem ser informados a seguir:</p>
                                        </div>
                                        <div class="col-md-3 form-group m-t-20">
                                            <label for="usuario-nfse">Usuário:</label>
                                            <input type="text" name="usuario-nfse" id="usuario-nfse" class="form-control" value="<?=$retorno != null ? $retorno->login_prefeitura : '' ?>">
                                        </div>
                                        <div class="col-md-3 form-group m-t-20">
                                            <label for="senha-nfse">Senha:</label>
                                            <input type="password" name="senha-nfse" id="senha-nfse" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-left m-t-20">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Salvar</button>
                                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" id="voltar">Voltar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-footer">
                    <strong>
                        Campos marcados com <b class="text-danger">*</b> são de preenchimento obrigatório.
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static"></div>

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
<script src="assets/plugins/switchery/js/switchery.min.js"></script>

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
$(document).ready(function (){
    $('#form-alterar').submit(function(e) {
        e.preventDefault();

        var data = new FormData(this);
        var action = 'functions/' + $(this).attr("action") + '.php';

        $.ajax({
            url: action,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: 'post',
            beforeSend: function (load) {
                ajax_load("open");
            },
            success: function (su) {
                ajax_load("close");
                $('#custom-modal-result').modal('show').html(su);
            }
        });
    });

    function ajax_load(action) {
        ajax_load_div = $(".ajax_load");

        if (action === "open") {
            ajax_load_div.fadeIn(200).css("display", "flex");
        }

        if (action === "close") {
            ajax_load_div.fadeOut(200);
        }
    }
});

$(function () {
    $('#voltar').click(function () {
        var id = '_Am00001';
        $('#_keyform').val(id);
        $('#form1').submit();
    });
});
</script>
</body>
</html>