<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

/*
 * Função para limpar variáveis, caso necessário
 * */
function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

/*
 * Chama modal de alter
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".contasdedespesas WHERE ID = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Filtros de contas</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contas-descricao-altera" class="control-label">Descrição:</label>
                                    <input type="text" name="contas-descricao-altera" id="contas-descricao-altera" class="form-control" value="<?=$retorno["Ctd_descr"]?>">
                                    <input type="hidden" name="contas-id" id="contas-id" value="<?=$retorno["ID"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contas-tipo-altera" class="control-label">Tipo:</label>
                                    <select name="contas-tipo-altera" id="contas-tipo-altera" class="form-control" onchange="_buscaSelect(this.value, '#select-grupo-altera')">
                                        <option value="0" selected>Selecione</option>
                                        <option <?=$retorno["TIPOC"] == 1 ? "selected" : ""?> value="1">Receita</option>
                                        <option <?=$retorno["TIPOC"] == 2 ? "selected" : ""?> value="2">Despesa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="select-grupo-altera">
                                    <label for="contas-grupo-altera" class="control-label">Grupo:</label>
                                    <select name="contas-grupo" id="contas-grupo" class="form-control">
                                        <option value="0">Selecione</option>
                                        <?php
                                        $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".grupodespesa WHERE grupodesp_tipo = ". $retorno["TIPOC"] ." ORDER BY grupodesp_descricao");
                                        $result = $consulta->fetchAll();
                                        foreach ($result as $row) {
                                            ?><option <?=$retorno["grupodespesa"] == $row["grupodesp_codigo"] ? "selected" : ""?> value="<?=$row["grupodesp_codigo"];?>"><?=$row["grupodesp_descricao"];?></option><?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_altera()">Alterar</button>
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
 * Cria Contas
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["contas-descricao"]) || empty($_parametros["contas-tipo"]) || empty($_parametros["contas-grupo"])) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe descrição, tipo e grupo!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".contasdedespesas (Ctd_descr, TIPOC, grupodespesa) VALUES(?, ?, ?)");
            $statement->bindParam(1, $_parametros["contas-descricao"]);
            $statement->bindParam(2, $_parametros["contas-tipo"]);
            $statement->bindParam(3, $_parametros["contas-grupo"]);
            $statement->execute();
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Conta Cadastrada!</h2>
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
 * Lista Contas
 * */
else if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".contasdedespesas WHERE TIPOC = ". $_parametros['contas-tipo'] ." AND grupodespesa = ". $_parametros['contas-grupo'] ." ");
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
        foreach ($retorno as $row) {
        ?>
            <tr class="gradeX">
                <td><?=utf8_encode($row["Ctd_descr"])?></td>
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$row["ID"];?>)"><i class="fa fa-pencil"></i></a>
                    <a href="javascript:void(0);" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row["ID"];?>)"><i class="fa fa-trash-o"></i></a>
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
 * Atualiza Conta
 * */
else if ($acao["acao"] == 3) {
    try {
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".contasdedespesas SET Ctd_descr = ?, TIPOC = ?, grupodespesa = ? WHERE ID = ?");
        $statement->bindParam(1, $_parametros["contas-descricao-altera"]);
        $statement->bindParam(2, $_parametros["contas-tipo-altera"]);
        $statement->bindParam(3, $_parametros["contas-grupo"]);
        $statement->bindParam(4, $_parametros["contas-id"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Conta Atualizada!</h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
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
/*
 * Exclui Conta
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".contasdedespesas WHERE ID = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Conta Excluída!</h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
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
/*
 * Busca select grupo connforme escolha do usuário em tipo
 * */
else if ($acao["acao"] == 5){
    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".grupodespesa WHERE grupodesp_tipo = ". $_parametros['id-filtro'] ." ORDER BY grupodesp_descricao");
    $result = $consulta->fetchAll();

    ?><label for="contas-grupo" class="control-label">Grupo:</label>
    <select name="contas-grupo" id="contas-grupo" class="form-control">
    <option value="0">Selecione</option><?php
    foreach ($result as $row) {
        ?><option value="<?=$row["grupodesp_codigo"];?>"><?=$row["grupodesp_descricao"];?></option><?php
    }
    ?></select><?php
}