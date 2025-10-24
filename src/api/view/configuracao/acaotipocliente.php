<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}
/*
 * Chama modal altera
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tipocliente WHERE tipo_CODIGO_TIPO = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar tipo de cliente</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cliente-descricao" class="control-label">Descrição:</label>
                                    <input type="text" class="form-control" name="cliente-descricao" id="cliente-descricao" value="<?=$retorno["tipo_DESCRICAO"]?>">
                                    <input type="hidden" name="cliente-id" id="cliente-id" value="<?=$retorno["tipo_CODIGO_TIPO"]?>">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_altera()">Salvar</button>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}
/*
 * Cadastra Cliente
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["cliente-descricao"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a descrição do cliente!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".tipocliente (tipo_DESCRICAO) VALUES(?)");
            $statement->bindParam(1, $_parametros["cliente-descricao"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cliente Cadastrado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Lista Clientes
 * */
else if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tipocliente ORDER BY tipo_DESCRICAO");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Descrição</th>
                <th class="text-right">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst) {
        ?>
            <tr class="gradeX">
                <td><?=utf8_encode($rst["tipo_DESCRICAO"])?></td>
                <td class="actions text-right">
                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$rst["tipo_CODIGO_TIPO"]?>)"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst["tipo_CODIGO_TIPO"];?>)"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
/*
 * Atualiza Cliente
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["cliente-descricao"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a descrição do cliente!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".tipocliente SET tipo_DESCRICAO = ? WHERE tipo_CODIGO_TIPO = ?");
            $statement->bindParam(1, $_parametros["cliente-descricao"]);
            $statement->bindParam(2, $_parametros["cliente-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cliente Atualizado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Excluí Cliente
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".tipocliente WHERE tipo_CODIGO_TIPO = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Cliente Excluído!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}
