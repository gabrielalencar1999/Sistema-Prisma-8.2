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
 * Cadastra Fornecedor
 * */
if ($acao["acao"] == 1) {
    if (empty($_parametros["fornecedor-nome"]) || $_parametros["fornecedor-tipo"] == 0) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php
                        if (empty($_parametros["fornecedor-nome"])) {
                            ?><h2>Informe o nome do fornecedor!</h2><?php
                        }
                        else if ($_parametros["fornecedor-nome"] == 0) {
                            ?><h2>Informe o tipo do fornecedor!</h2><?php
                        }
                        else {
                            ?><h2>Informe o nome e o tipo do fornecedor!</h2><?php
                        }
                        ?>

                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        $endereco = $_parametros["fornecedor-endereco"].", ".$_parametros["fornecedor-num"];
        $_parametros["fornecedor-telefone"] = LimpaVariavel($_parametros["fornecedor-telefone"]);
        $_parametros["fornecedor-cnpj"] = LimpaVariavel($_parametros["fornecedor-cnpj"]);
        $_parametros["fornecedor-inscricao"] = LimpaVariavel($_parametros["fornecedor-inscricao"]);
        try {
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".fabricante (NOME, RAZAO_SOCIAL, ativo, CEP, ENDERECO, BAIRRO, CIDADE, UF, TELEFONE, FOR_CONTATO1, CNPJ, INSCR_ESTADUAL, for_Tipo, atividade, email, site) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bindParam(1, $_parametros["fornecedor-nome"]);
            $statement->bindParam(2, $_parametros["fornecedor-razao"]);
            $statement->bindParam(3, $_parametros["fornecedor-status"]);
            $statement->bindParam(4, $_parametros["fornecedor-cep"]);
            $statement->bindParam(5, $endereco);
            $statement->bindParam(6, $_parametros["fornecedor-bairro"]);
            $statement->bindParam(7, $_parametros["fornecedor-cidade"]);
            $statement->bindParam(8, $_parametros["fornecedor-uf"]);
            $statement->bindParam(9, $_parametros["fornecedor-telefone"]);
            $statement->bindParam(10, $_parametros["fornecedor-contato"]);
            $statement->bindParam(11, $_parametros["fornecedor-cnpj"]);
            $statement->bindParam(12, $_parametros["fornecedor-inscricao"]);
            $statement->bindParam(13, $_parametros["fornecedor-tipo"]);
            $statement->bindParam(14, $_parametros["fornecedor-atividade"]);
            $statement->bindParam(15, $_parametros["fornecedor-email"]);
            $statement->bindParam(16, $_parametros["fornecedor-site"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Fornecedor Cadastrado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" onclick="fechar()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Listar Fornecedores
 * */
else if ($acao["acao"] == 2) {
    $_parametros['fornecedor-pesquisa'] = empty($_parametros['fornecedor-pesquisa']) ? " " : $_parametros['fornecedor-pesquisa'];
    $filtro = $_parametros['fornecedor-filtro'] == 1 ? "WHERE NOME LIKE '%".$_parametros['fornecedor-pesquisa']."%'" : "WHERE RAZAO_SOCIAL LIKE '%".$_parametros['fornecedor-pesquisa']."%'";

    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante $filtro ORDER BY NOME");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Nome Fantasia</th>
            <th>Razão Social</th>
            <th class="text-center">Telefones</th>
            <th class="text-center">Contato</th>
            <th class="text-center">Situação</th>
            <th class="text-center">Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td><?=empty($row["NOME"]) ? "Não informado" : $row["NOME"]?></td>
                <td><?=$row["RAZAO_SOCIAL"]?></td>
                <td class="text-center"><?=empty($row["TELEFONE"]) ? "Não informado" : $row["TELEFONE"]?></td>
                <td class="text-center"><?=empty($row["FOR_CONTATO1"]) ? "Não informado" : $row["FOR_CONTATO1"]?></td>
                <td class="text-center">
                    <span class="label label-table label-<?=$row["ativo"] == "0" ? "success" : "inverse" ?>"><?=$row["ativo"] == "0" ? "Sim" : "Não"?></span>
                </td>
                <td class="actions text-center">
                    <a href="#" class="on-default edit-row" onclick="_alterar(<?=$row['CODIGO_FABRICANTE']?>)"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row['CODIGO_FABRICANTE']?>)"><i class="fa fa-trash-o"></i></a>
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
    if (empty($_parametros["fornecedor-nome"]) || $_parametros["fornecedor-tipo"] == 0) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php
                        if (empty($_parametros["fornecedor-nome"]) && $_parametros["fornecedor-nome"] == 0) {
                            ?><h2>Informe o nome e o tipo do fornecedor!</h2><?php
                        }
                        else if ($_parametros["fornecedor-nome"] == 0) {
                            ?><h2>Informe o tipo do fornecedor!</h2><?php
                        }
                        else {
                            ?><h2>Informe o nome do fornecedor!</h2><?php
                        }
                        ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        $endereco = $_parametros["fornecedor-endereco"].", ".$_parametros["fornecedor-num"];
        $_parametros["fornecedor-telefone"] = LimpaVariavel($_parametros["fornecedor-telefone"]);
        $_parametros["fornecedor-cnpj"] = LimpaVariavel($_parametros["fornecedor-cnpj"]);
        $_parametros["fornecedor-inscricao"] = LimpaVariavel($_parametros["fornecedor-inscricao"]);
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".fabricante SET NOME = ?, RAZAO_SOCIAL = ?, ativo = ?, CEP = ?, ENDERECO = ?, BAIRRO = ?, CIDADE = ?, UF = ?, TELEFONE = ?, FOR_CONTATO1 = ?, CNPJ = ?, INSCR_ESTADUAL = ?, for_Tipo = ?, atividade = ?, email = ?, site = ? WHERE CODIGO_FABRICANTE = ?");
            $statement->bindParam(1, $_parametros["fornecedor-nome"]);
            $statement->bindParam(2, $_parametros["fornecedor-razao"]);
            $statement->bindParam(3, $_parametros["fornecedor-status"]);
            $statement->bindParam(4, $_parametros["fornecedor-cep"]);
            $statement->bindParam(5, $endereco);
            $statement->bindParam(6, $_parametros["fornecedor-bairro"]);
            $statement->bindParam(7, $_parametros["fornecedor-cidade"]);
            $statement->bindParam(8, $_parametros["fornecedor-uf"]);
            $statement->bindParam(9, $_parametros["fornecedor-telefone"]);
            $statement->bindParam(10, $_parametros["fornecedor-contato"]);
            $statement->bindParam(11, $_parametros["fornecedor-cnpj"]);
            $statement->bindParam(12, $_parametros["fornecedor-inscricao"]);
            $statement->bindParam(13, $_parametros["fornecedor-tipo"]);
            $statement->bindParam(14, $_parametros["fornecedor-atividade"]);
            $statement->bindParam(15, $_parametros["fornecedor-email"]);
            $statement->bindParam(16, $_parametros["fornecedor-site"]);
            $statement->bindParam(17, $_parametros["fornecedor-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Fornecedor Atualizado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" onclick="fechar()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
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
    $consultaNF = $pdo->query("SELECT NFE_FORNEC FROM ". $_SESSION['BASE'] . ".nota_ent_base WHERE NFE_FORNEC =  '".$_parametros["id-exclusao"]."'");
    $retornoNF = $consultaNF->fetchAll();

    $consultaProd = $pdo->query("SELECT COD_FABRICANTE FROM ". $_SESSION['BASE'] . ".itemestoque WHERE COD_FABRICANTE =  '".$_parametros["id-exclusao"]."'");
    $retornoProd = $consultaProd->fetchAll();

    if (count($retornoNF) != 0 || count($retornoProd) != 0) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php
                        if (count($retornoNF) != 0 && count($retornoProd) != 0) {
                            ?><h2>Existem notas e produtos deste fornecedor!</h2><?php
                        }
                        else if (count($retornoProd) != 0) {
                            ?><h2>Existem produtos deste fornecedor!</h2><?php
                        }
                        else {
                            ?><h2>Existem notas deste fornecedor!</h2><?php
                        }
                        ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".fabricante WHERE CODIGO_FABRICANTE = :id");
            $statement->bindParam(':id', $_parametros["id-exclusao"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Fabricante Excluído!</h2>
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
                    <div class="modal-body" id="imagem-carregando">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}