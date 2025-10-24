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
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".linha WHERE linha_codigo = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Linha</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linha-descricao" class="control-label">Descrição:</label>
                                    <input type="text" class="form-control" name="linha-descricao" id="linha-descricao" value="<?=$retorno["linha_descricao"]?>">
                                    <input type="hidden" name="linha-id" id="linha-id" value="<?=$retorno["linha_codigo"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linha-tipo" class="control-label">Tipo:</label>
                                    <select name="linha-tipo" id="linha-tipo" class="form-control">
                                        <option value="1" <?=$retorno['linha_tipo'] == 1 ? 'selected' : ''?>>Produto</option>
                                        <option value="2" <?=$retorno['linha_tipo'] == 2 ? 'selected' : ''?>>Serviço</option>
                                    </select>
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
 * Cadastra Linha
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["linha-descricao"]) || empty($_parametros["linha-tipo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["linha-descricao"]) && empty($_parametros["linha-tipo"])): ?>
                            <h2>Informe a descrição e tipo da linha!</h2>
                        <?php elseif(empty($_parametros["linha-descricao"])): ?>
                            <h2>Informe a descrição da linha!</h2>
                        <?php else: ?>
                            <h2>Informe o tipo da linha!</h2>
                        <?php endif ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".linha (linha_descricao, linha_tipo) VALUES(?, ?)");
            $statement->bindParam(1, $_parametros["linha-descricao"]);
            $statement->bindParam(2, $_parametros["linha-tipo"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Linha Cadastrada!</h2>
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
 * Lista Linhas
 * */
else if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".linha ORDER BY linha_descricao");
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
                <td><?=($rst["linha_descricao"])?></td>
                <td class="actions text-right">
                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$rst["linha_codigo"]?>)"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst["linha_codigo"];?>)"><i class="fa fa-trash-o"></i></a>
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
 * Atualiza Linha
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["linha-descricao"]) || empty($_parametros["linha-tipo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["linha-descricao"]) && empty($_parametros["linha-tipo"])): ?>
                            <h2>Informe a descrição e tipo da linha!</h2>
                        <?php elseif(empty($_parametros["linha-descricao"])): ?>
                            <h2>Informe a descrição da linha!</h2>
                        <?php else: ?>
                            <h2>Informe o tipo da linha!</h2>
                        <?php endif ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".linha SET linha_descricao = ?, linha_tipo = ? WHERE linha_codigo = ?");
            $statement->bindParam(1, $_parametros["linha-descricao"]);
            $statement->bindParam(2, $_parametros["linha-tipo"]);
            $statement->bindParam(3, $_parametros["linha-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Linha Atualizada!</h2>
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
 * Exclui Linha
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".linha WHERE linha_codigo = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Linha Excluída!</h2>
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