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

$_parametros["grupo-desconto"] = LimpaVariavel($_parametros["grupo-desconto"]);
$_parametros["grupo-comissao"] = LimpaVariavel($_parametros["grupo-comissao"]);

/*
 * Chama modal alterar
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".grupo WHERE GRU_GRUPO = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Grupo</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grupo-descricao" class="control-label">Descrição:</label>
                                    <input type="text" class="form-control" name="grupo-descricao" id="grupo-descricao" placeholder="Informe a descrição do grupo" value="<?=$retorno["GRU_DESC"]?>">
                                    <input type="hidden" name="grupo-id" id="grupo-id" value="<?=$retorno["GRU_GRUPO"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grupo-tipo" class="control-label">Tipo:</label>
                                    <select name="grupo-tipo" id="grupo-tipo" class="form-control">
                                        <option value="1" <?=$retorno['GRU_TIPO'] == 1 ? 'selected' : ''?>>Produto</option>
                                        <option value="2" <?=$retorno['GRU_TIPO'] == 2 ? 'selected' : ''?>>Serviço</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grupo-desconto" class="control-label">Desconto %:</label>
                                    <input type="text" class="form-control" name="grupo-desconto" id="grupo-desconto" placeholder="Informe a % de desconto" value="<?=$retorno["GRU_DESCONTO"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grupo-comissao" class="control-label">Comissão %:</label>
                                    <input type="text" class="form-control" name="grupo-comissao" id="grupo-comissao" placeholder="Informe a % de comissão" value="<?=$retorno["GRU_COMISSAO"]?>">
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
 * Cria novo grupo
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["grupo-descricao"]) || empty($_parametros["grupo-tipo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["grupo-descricao"]) && empty($_parametros["grupo-tipo"])): ?>
                            <h2>Informe a descrição e tipo do grupo!</h2>
                        <?php elseif(empty($_parametros["grupo-descricao"])): ?>
                            <h2>Informe a descrição do grupo!</h2>
                        <?php else: ?>
                            <h2>Informe o tipo do grupo!</h2>
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
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".grupo (GRU_DESC, GRU_TIPO, GRU_DESCONTO, GRU_COMISSAO) VALUES(?, ?, ?, ?)");
            $statement->bindParam(1, $_parametros["grupo-descricao"]);
            $statement->bindParam(2, $_parametros["grupo-tipo"]);
            $statement->bindParam(3, $_parametros["grupo-desconto"]);
            $statement->bindParam(4, $_parametros["grupo-comissao"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Grupo Cadastrado!</h2>
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
 * Lista grupos
 * */
else if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".grupo ORDER BY GRU_DESC");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Descrição</th>
                <th class="text-center">Desconto</th>
                <th class="text-center">% Comissão por Grupo</th>
                <th class="text-right">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td><?=($row["GRU_DESC"]);?></td>
                <td class="text-center"><?=$row["GRU_DESCONTO"];?></td>
                <td class="text-center"><?=$row["GRU_COMISSAO"];?></td>
                <td class="actions text-right">
                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$row["GRU_GRUPO"];?>)"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row["GRU_GRUPO"];?>)"><i class="fa fa-trash-o"></i></a>
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
 * Atualiza grupo
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["grupo-descricao"]) || empty($_parametros["grupo-tipo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["grupo-descricao"]) && empty($_parametros["grupo-tipo"])): ?>
                            <h2>Informe a descrição e tipo do grupo!</h2>
                        <?php elseif(empty($_parametros["grupo-descricao"])): ?>
                            <h2>Informe a descrição do grupo!</h2>
                        <?php else: ?>
                            <h2>Informe o tipo do grupo!</h2>
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
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".grupo SET GRU_DESC = ?, GRU_TIPO = ?, GRU_DESCONTO = ?, GRU_COMISSAO = ? WHERE GRU_GRUPO = ?");
            $statement->bindParam(1, $_parametros["grupo-descricao"]);
            $statement->bindParam(2, $_parametros["grupo-tipo"]);
            $statement->bindParam(3, $_parametros["grupo-desconto"]);
            $statement->bindParam(4, $_parametros["grupo-comissao"]);
            $statement->bindParam(5, $_parametros["grupo-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Grupo Atualizado!</h2>
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
 * Exclui grupo
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".grupo WHERE GRU_GRUPO = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Grupo Excluído!</h2>
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