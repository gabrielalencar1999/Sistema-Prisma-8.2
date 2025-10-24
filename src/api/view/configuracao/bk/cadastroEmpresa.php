<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php');

use Database\MySQL;
use Functions\NFeFocus;

$pdo = MySQL::acessabd();


$consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".parametro");
$retorno = $consulta->fetch(\PDO::FETCH_OBJ);

$consultaempresa = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa");
$retornoEmpresa = $consultaempresa->fetch(\PDO::FETCH_OBJ);
/*
$consulta = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".item_lista_servico ORDER BY id");
$servicos = $consulta->fetchAll(\PDO::FETCH_OBJ);
*/
?>
<style>
    .pp1{
        border:1px solid #4a5671;
        padding-right:0px;
        padding-left:0px;
        padding-bottom:8px;
        width:14.25%;
        float:left;
    }
    .pp2{
        color:#FFF;
        background-color:#4a5671;
        margin-top:0px;
        text-align:center;
    }
    .pp3{
        padding-left:5px;
        padding-right:5px;
    }
</style>
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
                            <li class="">
                                <a href="#navpills-21" style="display:none ;" data-toggle="tab" aria-expanded="false">Configurações</a>
                            </li>
                            <li class="" style="display:none ;">
                                <a href="#navpills-31" data-toggle="tab" aria-expanded="false">Meu site</a>
                            </li>
                            <li class="" style="display:none ;">
                                <a href="#navpills-41" data-toggle="tab" aria-expanded="false">Dados Bancários</a>
                            </li>
                        </ul>
                        <form action="acaoCadastroEmpresa" method="post" name="form-alterar" id="form-alterar">
                            <div class="tab-content br-n pn">
                                <div id="navpills-11" class="tab-pane active">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="razao">Razão Social<b class="text-danger">*</b>:</label>
                                            <input type="text" name="razao" class="form-control" id="razao" value="<?=$retorno->RAZAO_SOCIAL?>" required>
                                            <input type="hidden" name="acao" id="acao" value="1">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="fantasia">Nome Fantasia<b class="text-danger">*</b>:</label>
                                            <input type="text" name="fantasia" class="form-control" id="fantasia" value="<?=$retorno->NOME_FANTASIA?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cnpj">CNPJ<b class="text-danger">*</b>:</label>
                                            <input type="text" name="cnpj" class="form-control" id="cnpj" value="<?=$retorno->CGC?>" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="cep">CEP<b class="text-danger">*</b>:</label>
                                            <input type="text" name="cep" class="form-control" id="cep" value="<?=$retorno->Cep?>" require>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="rua">Endereço<b class="text-danger">*</b>:</label>
                                            <input type="text" name="rua" class="form-control" id="rua" value="<?=$retorno->ENDERECO?>" required>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="numero">N°:</label>
                                            <input type="number" name="numero" class="form-control" id="numero" value="<?=$retorno->NumRua?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="complemento">Complemento:</label>
                                            <input type="text" name="complemento" class="form-control" id="complemento" value="<?=$retorno->Complemento_Endereco?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="bairro">Bairro<b class="text-danger">*</b>:</label>
                                            <input type="text" name="bairro" class="form-control" id="bairro" value="<?=$retorno->BAIRRO?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cidade">Cidade<b class="text-danger">*</b>:</label>
                                            <input type="text" name="cidade" class="form-control" id="cidade" value="<?=$retorno->CIDADE?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="uf">Estado<b class="text-danger">*</b>:</label>
                                            <select name="uf" id="uf" class="form-control" required>
                                                <option value=""<?=$retorno->UF == "" ? " selected" : ""?>>Selecione</option>
                                                <?php
                                                $consultaUF = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".estado ORDER BY nome_estado");
                                                $resultUF = $consultaUF->fetchAll(\PDO::FETCH_OBJ);

                                                foreach ($resultUF as $row): ?>
                                                    <option value="<?=$row->estado_sigla?>"<?=$row->estado_sigla == $retorno->UF ? " selected" : ""?>><?=$row->nome_estado?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cpf-cnpj-contabilidade">CPF/CNPJ Contabilidade:</label>
                                            <input name="cpf-cnpj-contabilidade" type="text" class="form-control" id="cpf-cnpj-contabilidade" value="<?=$retorno->cpf_cnpj_contabilidade?>"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="telefone">Telefone<b class="text-danger">*</b>:</label>
                                            <input name="telefone" type="text" class="form-control" id="telefone" value="<?=$retorno->DDD?><?=$retorno->TELEFONE?>" size="55" maxlength="50" required/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="email">Email<b class="text-danger">*</b>:</label>
                                            <input name="email" type="email" class="form-control" id="email" value="<?=$retorno->EMAIL?>" required/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contato-nome">Contato Nome:</label>
                                            <input name="contato-nome" type="text" class="form-control" id="contato-nome" value="<?=$retorno->contato_nome?>"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contato-telefone">Contato Telefone:</label>
                                            <input name="contato-telefone" type="text" class="form-control" id="contato-telefone" value="<?=$retorno->contato_telefone?>" size="55" maxlength="50"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contato-email">Contato Email:</label>
                                            <input name="contato-email" type="email" class="form-control" id="contato-email" value="<?=$retorno->contato_email?>"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="regime-tributario">Regime Tributário<b class="text-danger">*</b>:</label>
                                            <select name="regime-tributario" id="regime-tributario" class="form-control" required>
                                                <option value="">Selecione</option>
                                                <option value="1" <?=$retornoEmpresa->empresa_tipo == 1 ? 'selected' : ''?>>Simples Nacional</option>
                                                <option value="2" <?=$retornoEmpresa->empresa_tipo == 2 ? 'selected' : ''?>>Simples Nacional - Excesso de Receita Bruta</option>
                                                <option value="3" <?=$retornoEmpresa->empresa_tipo == 3 ? 'selected' : ''?>>Regime Normal</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" id="item-servico-select">
                                            <label for="item-servico">Item Serviço:</label>
                                            <select name="item-servico" id="item-servico" class="form-control" >
                                                <option value="">Selecione</option>
                                                <?php foreach($servicos as $row): ?>
                                                <option value="<?=$row->id?>" <?=$retorno->item_lista_servico == $row->id ? 'selected' : ''?>><?=$row->descricao?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="inscricao-estadual" id="label-inscricao-estadual">Inscrição Estadual<?=$retorno->habilita_nfe || $retorno->habilita_nfce ? '<b class="text-danger">*</b>' : ''?>:</label>
                                            <input type="number" name="inscricao-estadual" id="inscricao-estadual" class="form-control" value="<?=$retorno->INSC_ESTADUAL?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="inscricao-municipal" id="label-inscricao-municipal">Inscrição Municipal<?=$retorno->habilita_nfse ? '<b class="text-danger">*</b>' : ''?>:</label>
                                            <input type="number" name="inscricao-municipal" id="inscricao-municipal" class="form-control" value="<?=$retorno->inscricao_municipal?>">
                                        </div>
                                       
                                        
                                    </div>
                                </div>
                                <?php /*
                                <div id="navpills-21" class="tab-pane">
                                    <div class="form-group col-xs-12">
                                        <div class="row">
                                            <input type="checkbox" id="habilita-nfe" name="habilita-nfe" data-plugin="switchery" data-color="#34d3eb" <?=$retorno->habilita_nfe ? 'checked' : ''?>/>
                                            <label for="habilita-nfe">NFe</label>
                                        </div>
                                        <div class="row" id="dados-nfe" <?=$retorno->habilita_nfe ? '' : 'style="display: none"' ?>>
                                            <div class="col-md-6">
                                                <div class="col-xs-12 text-left">
                                                    <h4 class="page-title m-b-15">Produção</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="serie-nfe-prod">Série<b class="text-danger">*</b>:</label>
                                                    <input type="number" name="serie-nfe-prod" id="serie-nfe-prod" class="form-control" value="<?=$retorno->serie_nfe_producao?>" <?=$retorno->habilita_nfe ? 'required' : '' ?>>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="proxima-nfe-prod">Próximo Numero<b class="text-danger">*</b>:</label>
                                                    <input type="number" name="proxima-nfe-prod" id="proxima-nfe-prod" class="form-control" value="<?=$retorno->proximo_numero_nfe_producao?>" <?=$retorno->habilita_nfe ? 'required' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-xs-12 text-left">
                                                    <h4 class="page-title m-b-15">Homologação</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="serie-nfe-hom">Série:</label>
                                                    <input type="number" name="serie-nfe-hom" id="serie-nfe-hom" class="form-control" value="<?=$retorno->serie_nfe_homologacao?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="proxima-nfe-hom">Próximo Numero:</label>
                                                    <input type="number" name="proxima-nfe-hom" id="proxima-nfe-hom" class="form-control" value="<?=$retorno->proximo_numero_nfe_homologacao?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-xs-12">
                                      
                                    <!--
                                        <div class="row">
                                            <input type="checkbox" id="habilita-nfse" name="habilita-nfse" data-plugin="switchery" data-color="#34d3eb" <?=$retorno->habilita_nfse ? 'checked' : ''?>/>
                                            <label for="habilita-nfse">NFSe</label>
                                        </div>
                                      
                                        <div class="row" id="dados-nfse" <?=$retorno->habilita_nfse ? '' : 'style="display: none"' ?>>
                                            <div class="col-md-6">
                                                <div class="col-xs-12 text-left">
                                                    <h4 class="page-title m-b-15">Produção</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="serie-nfse-prod">Série<b class="text-danger">*</b>:</label>
                                                    <input type="number" name="serie-nfse-prod" id="serie-nfse-prod" class="form-control" value="<?=$retorno->serie_nfse_producao?>" <?=$retorno->habilita_nfse ? 'required' : '' ?>>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="proxima-nfse-prod">Próximo Numero<b class="text-danger">*</b>:</label>
                                                    <input type="number" name="proxima-nfse-prod" id="proxima-nfse-prod" class="form-control" value="<?=$retorno->proximo_numero_nfse_producao?>" <?=$retorno->habilita_nfse ? 'required' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-xs-12 text-left">
                                                    <h4 class="page-title m-b-15">Homologação</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="serie-nfe-hom">Série:</label>
                                                    <input type="number" name="serie-nfse-hom" id="serie-nfse-hom" class="form-control" value="<?=$retorno->serie_nfse_homologacao?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="proxima-nfse-hom">Próximo Numero:</label>
                                                    <input type="number" name="proxima-nfse-hom" id="proxima-nfse-hom" class="form-control" value="<?=$retorno->proximo_numero_nfse_homologacao?>">
                                                </div>
                                            </div>
                                            <div class="row m-l-5">
                                                <div class="col-md-6 text-left m-b-20">
                                                    <h4 class="page-title m-b-15">Login Prefeitura</h4>
                                                    <p>Algumas prefeituras utilizam usuário e senha para realizar a emissão de NFSe. Se for o caso para este município os dados podem ser informados a seguir:</p>
                                                </div>
                                                <div class="col-md-3 form-group m-t-20">
                                                    <label for="usuario-nfse">Usuário:</label>
                                                    <input type="text" name="usuario-nfse" id="usuario-nfse" class="form-control" value="<?=$retorno->login_prefeitura?>">
                                                </div>
                                                <div class="col-md-3 form-group m-t-20">
                                                    <label for="senha-nfse">Senha:</label>
                                                    <input type="password" name="senha-nfse" id="senha-nfse" class="form-control" value="<?=$retorno->senha_prefeitura?>">
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <div class="row">    
                                            <input type="checkbox" id="habilita-nfce" name="habilita-nfce" data-plugin="switchery" data-color="#34d3eb" <?=$retorno->habilita_nfce ? 'checked' : ''?>/>
                                            <label for="habilita-nfce">NFCe</label>
                                        </div>
                                        <div class="row" id="dados-nfce" <?=$retorno->habilita_nfce ? '' : 'style="display: none"' ?>>
                                            <div class="col-md-6">
                                                <div class="col-xs-12 text-left">
                                                    <h4 class="page-title m-b-15">Produção</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="serie-nfce-prod">Série<b class="text-danger">*</b>:</label>
                                                    <input type="number" name="serie-nfce-prod" id="serie-nfce-prod" class="form-control" value="<?=$retornoEmpresa->serie_nfce_producao?>" <?=$retorno->habilita_nfce ? 'required' : '' ?>>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="proxima-nfce-prod">Próximo Numero<b class="text-danger">*</b>:</label>
                                                    <input type="number" name="proxima-nfce-prod" id="proxima-nfce-prod" class="form-control" value="<?=$retornoEmpresa->proximo_numero_nfce_producao?>" <?=$retorno->habilita_nfce ? 'required' : '' ?>>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="token-nfce-prod">ID Token<b class="text-danger">*</b>:</label>
                                                    <input type="text" name="token-nfce-prod" id="token-nfce-prod" class="form-control" value="<?=$retornoEmpresa->id_token_nfce_producao?>" <?=$retorno->habilita_nfce ? 'required' : '' ?>>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="csc-nfce-prod">CSC<b class="text-danger">*</b>:</label>
                                                    <input type="text" name="csc-nfce-prod" id="csc-nfce-prod" class="form-control" value="<?=$retornoEmpresa->csc_nfce_producao?>" <?=$retorno->habilita_nfce ? 'required' : '' ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-xs-12 text-left">
                                                    <h4 class="page-title m-b-15">Homologação</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="serie-nfce-hom">Série:</label>
                                                    <input type="number" name="serie-nfce-hom" id="serie-nfce-hom" class="form-control" value="<?=$rretornoEmpresaetorno->serie_nfce_homologacao?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="proxima-nfce-hom">Próximo Numero:</label>
                                                    <input type="number" name="proxima-nfce-hom" id="proxima-nfce-hom" class="form-control" value="<?=$retornoEmpresa->proximo_numero_nfce_homologacao?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="token-nfce-hom">ID Token:</label>
                                                    <input type="text" name="token-nfce-hom" id="token-nfce-hom" class="form-control" value="<?=$retornoEmpresa->id_token_nfce_homologacao?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="csc-nfce-hom">CSC:</label>
                                                    <input type="text" name="csc-nfce-hom" id="csc-nfce-hom" class="form-control" value="<?=$retornoEmpresa->csc_nfce_homologacao?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 m-b-20">
                                            <h4 class="page-title" style="text-decoration:underline;">Horário de funcionamento</h4>
                                        </div>
                                        <!----DOMINGO----------->
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Domingo</b></h5>
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="domingo_inicio" id="domingo_inicio" value="<?=$retorno->domingo_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="domingo_fim" id="domingo_fim" value="<?=$retorno->domingo_fim;?>"/>
                                            </div>                                        
                                        </div>      
                                        <!----SEGUNDA----------->       
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Segunda-feira</b></h5>                                
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="segunda_inicio" id="segunda_inicio" value="<?=$retorno->segunda_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="segunda_fim" id="segunda_fim" value="<?=$retorno->segunda_fim;?>"/>
                                            </div>                                     
                                        </div>
                                        <!----TERCA----------->
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Terça-feira</b></h5>
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="terca_inicio" id="terca_inicio" value="<?=$retorno->terca_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="terca_fim" id="terca_fim" value="<?=$retorno->terca_fim;?>"/>
                                            </div>                                        
                                        </div>
                                        <!----QUARTA----------->
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Quarta-feira</b></h5>
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="quarta_inicio" id="quarta_inicio" value="<?=$retorno->quarta_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="quarta_fim" id="quarta_fim" value="<?=$retorno->quarta_fim;?>"/>
                                            </div>                                         
                                        </div>
                                        <!----QUINTA----------->
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Quinta-feira</b></h5>
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="quinta_inicio" id="quinta_inicio" value="<?=$retorno->quinta_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="quinta_fim" id="quinta_fim" value="<?=$retorno->quinta_fim;?>"/>
                                            </div>
                                           
                                        </div>
                                        <!----SEXTA----------->
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Sexta-feira</b></h5>
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="sexta_inicio" id="sexta_inicio" value="<?=$retorno->sexta_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="sexta_fim" id="sexta_fim" value="<?=$retorno->sexta_fim;?>"/>
                                            </div>
                                           
                                        </div>
                                        <!----SABADO----------->
                                        <div class="pp1">
                                            <h5 class="pp2"><b>Sábado</b></h5>
                                            <div class="col-xs-12 pp3">
                                                <p>Entrada</p>
                                                <input  type="time" class="form-control" name="sabado_inicio" id="sabado_inicio" value="<?=$retorno->sabado_ini;?>"/>
                                            </div>
                                            <div class="col-xs-12 pp3">
                                                <p>Saída</p>
                                                <input  type="time" class="form-control" name="sabado_fim" id="sabado_fim" value="<?=$retorno->sabado_fim;?>"/>
                                            </div>
                                           
                                        </div>                                                                                                                       
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 m-b-20">
                                            <h4 class="page-title"  style="text-decoration:underline;">Outras informações</h4>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="ultimaos">Próximo Pedido:</label>
                                            <input name="ultimaos" type="text" class="form-control" id="ultimaos" value="<?=$parametro->Num_Pedido_Venda?>"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="ano">Próximo Produto:</label>
                                            <input name="ano" type="text" class="form-control" id="ano" value="<?=$parametro->Ult_Cod_Peca?>"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="extraA">Legenda Extra A - Produto:</label>
                                            <input name="extraA" type="text" class="form-control" id="extraA" value="<?=$parametro->extra_A_label?>" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="extraB">Legenda Extra B - Produto:</label>
                                            <input name="extraB" type="text" class="form-control" id="extraB" value="<?=$parametro->extra_B_label?>" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="taxa">Desconto Ticket %:</label>
                                            <input name="taxa" type="text" class="form-control" id="taxa" value="<?=$parametro->des_Comis_Fatur?>" size="6" maxlength="4" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="codigopermissao">Código de Autorização Geral:</label>
                                            <input name="codigopermissao" type="text" class="form-control" id="codigopermissao" value="<?=$parametro->codigopermissao?>" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="codigopermissao2">Código de Autorização Cancelamento:</label>
                                            <input name="codigopermissao2" type="text" class="form-control" id="codigopermissao2" value="<?=$parametro->codigopermissaocancelamento?>" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="aliquota">Aliquota %:</label>
                                            <input name="aliquota" type="text" class="form-control" id="aliquota" value="<?=$retorno->aliquota_nota?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group col-md-12">
                                                <label for="tab_1">Legenda - Tabela 1:</label>
                                                <input name="tab_1" type="text" class="form-control" id="tab_1" value="<?=$parametro->label_tab1;?>" />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input name="ind_tab1" type="checkbox" id="ind_tab1" value="-1"  <?=$parametro->visualiza_tab1 == '-1' ? 'checked' : '' ?>/>
                                                    <label for="ind_tab1">Visualiza Consulta</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group col-md-12">
                                                <label for="tab_2">Legenda - Tabela 2:</label>
                                                <input name="tab_2" type="text" class="form-control" id="tab_2" value="<?=$parametro->label_tab2?>" />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="ind_tab2" id="ind_tab2" value="-1" <?=$parametro->visualiza_tab2 == '-1' ? 'checked' : '' ?>/>
                                                    <label for="ind_tab2">Visualiza Consulta</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group col-md-12">
                                                <label for="tab_3">Legenda - Tabela 3:</label>
                                                <input name="tab_3" type="text" class="form-control" id="tab_3" value="<?=$parametro->label_tab3?>" />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="ind_tab3" id="ind_tab3" value="-1" <?=$parametro->visualiza_tab3 == '-1' ? 'checked' : '' ?>/>
                                                    <label for="ind_tab3">Visualiza Consulta</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group col-md-12">
                                                <label for="tab_4">Legenda - Tabela 4:</label>
                                                <input name="tab_4" type="text" class="form-control" id="tab_4"  value="<?=$parametro->label_tab4?>" />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="ind_tab4" id="ind_tab4" value="-1" <?=$parametro->visualiza_tab4 == '-1' ? 'checked' : '' ?>/>
                                                    <label for="ind_tab4">Visualiza Consulta</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group col-md-12">
                                                <label for="tab_5">Legenda - Tabela 5 (Valor de Venda):</label>
                                                <input name="tab_5" type="text" class="form-control " id="tab_5" value="<?=$parametro->label_tab5?>" />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox" name="ind_tab5" id="ind_tab5" value="-1" <?=$parametro->visualiza_tab5 == '-1' ? 'checked' : '' ?>/>
                                                    <label for="ind_tab5">Visualiza Consulta</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div id="navpills-31" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="dominio-site">Dominio:</label>
                                            <input class="form-control" type="text" id="dominio-site" name="dominio-site" value="<?=$retorno->dominio_site?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="porta-site">Porta:</label>
                                            <input class="form-control" type="text" id="porta-site" name="porta-site" value="<?=$retorno->porta_site?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="usuario-site">Usuário:</label>
                                            <input class="form-control" type="text" id="usuario-site" name="usuario-site" value="<?=$retorno->usuario_site;?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="senha-site">Senha:</label>
                                            <input class="form-control" type="password" id="senha-site" name="senha-site" value="">
                                        </div>
                                    </div>
                                </div>
                                <div id="navpills-41" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="Banco">Banco:</label>
                                            <select class="form-control" name="banco" id="banco">
                                                <option value="1" <?=$parametro->banco == 1 ? 'selected' : ''?>>BRADESCO</option>
                                                <option value="2"  <?=$parametro->banco == 2 ? 'selected' : ''?>>ITAU</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="carteira">Carteira:</label>
                                            <input name="carteira" type="text" class="form-control" id="carteira" value="<?=$parametro->carteira?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="agencia">Agência:</label>
                                            <input name="agencia" type="text" class="form-control" id="agencia" value="<?=$parametro->agencia?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="contacorrente">Conta Corrente:</label>
                                            <input name="contacorrente" type="text" class="form-control" id="contacorrente" value="<?=$parametro->conta?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="cedente">Cedente:</label>
                                            <input name="cedente" type="text" class="form-control" id="cedente" value="<?=$parametro->cedente?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="sequencia">Sequência Nosso Número:</label>
                                            <input name="sequencia" type="text" class="form-control" id="sequencia" value="<?=$parametro->sequencia?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="juro">Juros por Atraso ao Mês %:</label>
                                            <input name="juro" type="text" class="form-control" id="juro" value="<?=number_format($parametro->parametro_juro, 2, ',', '.')?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="multa">Multa ao Mês:</label>
                                            <input name="multa" type="text" class="form-control" id="multa" value="<?=number_format($parametro->parametro_multa, 2, ',', '.')?>" />
                                        </div>
                                    </div>
                                </div>
                                    <?php */?>
                                <div class="row text-left m-t-20">
                                <button type="button" class="btn btn-default waves-effect waves-light m-l-5" id="voltar">Fechar</button>
                                    <!---  <button type="button" class="btn btn-success waves-effect waves-light" id="salvardados">Salvar</button>   --->                              
                                    <button type="button" class="btn btn-info waves-effect waves-light m-l-5" id="cadastro-" data-toggle="modal" data-target="#custom-modal-certificado" >Certificado A1</button>
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
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;"></div>

<!-- Modal Colaborador -->
<div id="custom-modal-certificado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">
                    Certificado A1
                </h4>
            </div>
            <form action="form2" id="form2" method="post">
                <div class="modal-body">
                    <div class="row">
                                        <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="">Empresa:</label>
                                                                            <select name="empresa" id="empresa" class="form-control">
                                                                              
                                                                                <?php
                                                                                $consulta_empresa = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".empresa ORDER BY empresa_nome");
                                                                                $result_empresa = $consulta_empresa->fetchAll();

                                                                                foreach ($result_empresa as $row)
                                                                                {
                                                                                    ?>
                                                                                    <option value="<?=$row["empresa_id"]?>"<?=$row["empresa_id"] == $rst["usuario_empresa"] ? " selected" : ""?>><?=($row["empresa_nome"])?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                        <input type="hidden" name="acao" id="acao" value="2">
                                                    </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                            <label for="arquivo-certificado">Novo Certificado:</label>
                                                <input type="file" name="arquivo-certificado" id="arquivo-certificado" class="filestyle">
                                                <input type="hidden" id="certificado" value=""/>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Senha do Certificado</label>
                                                <input type="text" name="senha_certificado" id="senha_certificado" class="form-control" value="" />
                                            </div>
                                        </div>
                
                </div>
                <div class="modal-footer">
               <div id="_ret"></div>
                   <button type="button" class="btn btn-success waves-effect waves-light" onclick="_validarcertificado()">Atualizar</button>
                   
               </div>
            </form>
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
            success: function (su) {
                $('#custom-modal-result').modal('show').html(su);
                console.log(su);
            }
        });
    });

    $('#form-colaborador').submit(function(e) {
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
            success: function (su) {
                $('#custom-modal-colaborador').modal('hide');
                $('#custom-modal-result').modal('show').html(su);
                console.log(su);
            }
        });
    });
});



$(function () {
    $('#voltar').click(function () {
        var id = "_Nc00005";
        $('#_keyform').val(id);
        $('#form1').submit();
    });

    $('#salvardados').click(function () {
        var $_keyid =   "ACCDT";
        var dados = $("#form-alterar :input").serializeArray();
        dados = JSON.stringify(dados);
        $('#custom-modal-result').modal('show');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                alert(result);
                $("#custom-modal-result").html(result);
        });
    });



    function  _alterar(){
      alert("teste");
        var $_keyid =   "ACCDT";
        var dados = $("#form-alterar :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                $("#custom-modal-result").html(result);
        });

    };

    



 
});



function  _validarcertificado(){  
      
   
          var form_data = new FormData(document.getElementById("form2"));
         
          $.ajax({
              url: 'acaoCertificado.php',
              dataType: 'text',
              cache: false,
              contentType: false,
              processData: false,
              data: form_data,
              type: 'post',
              success: function(retorno) {
                
                  $("#_ret").html(retorno);                
              }
          });

  };
</script>
</body>
</html>