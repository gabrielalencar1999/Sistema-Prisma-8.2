<?php
use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function ajustaData($data) {
    $data = trim($data);
    $data = str_replace("/", "-", $data);
    $data = date('Y-m-d', strtotime($data));
    return $data;
}

empty($_parametros["projeto-inicio"]) ?: $_parametros["projeto-inicio"] = ajustaData($_parametros["projeto-inicio"]);
empty($_parametros["projeto-fim"]) ?: $_parametros["projeto-fim"] = ajustaData($_parametros["projeto-fim"]);
empty($_parametros["projeto-valor"]) ?: $_parametros["projeto-valor"] = LimpaVariavel($_parametros["projeto-valor"]);

/*
 * Chama modal altera
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".projeto WHERE projeto_id = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <input type="hidden" name="projeto-fim" id="projeto-fim" class="form-control" value="<?=$retorno["projeto_datafinal"]?>">
        <input type="hidden" name="projeto-inicio" id="projeto-inicio" class="form-control" value="<?=$retorno["projeto_datainicial"]?>">
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="grupo-descricao" class="control-label">Descrição:</label>
                                    <input type="text" class="form-control" name="projeto-descricao" id="projeto-descricao" value="<?=$retorno["projeto_descricao"]?>">
                                    <input type="hidden" name="projeto-id" id="projeto-id" value="<?=$retorno["projeto_id"]?>">
                                </div>
                            </div>
                     
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="projeto-valor" class="control-label">Valor P/ Projeto:</label>
                                    <input type="text" name="projeto-valor" id="projeto-valor" class="form-control" value="<?=number_format($retorno["valorprojeto"],2,',','.');?>">
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
 * Inclui Projeto
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["projeto-descricao"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a descrição!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $data = date('m-d-Y');
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".projeto (projeto_descricao, projeto_datainicial, projeto_datafinal, projeto_datacadastro, valorprojeto) VALUES(?, ?, ? ,? ,?)");
            $statement->bindParam(1, $_parametros["projeto-descricao"]);
            $statement->bindParam(2, $_parametros["projeto-inicio"]);
            $statement->bindParam(3, $_parametros["projeto-fim"]);
            $statement->bindParam(4, $data);
            $statement->bindParam(5, $_parametros["projeto-valor"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cadastrado !</h2>
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
 * Lista Projetos
 * */
else if ($acao["acao"] == 2) {
    $consulta = $pdo->query("SELECT *, DATE_FORMAT(projeto_datainicial,'%d/%m/%Y') AS dataini,DATE_FORMAT(projeto_datafinal,'%d/%m/%Y') AS datafim FROM ". $_SESSION['BASE'] .".projeto ORDER BY projeto_descricao");
    $result = $consulta->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Descrição</th>
          
            <th>Valor P/ Projeto</th>
            <th class="text-center">Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $row)
        {
            ?>
            <tr class="gradeX">
                <td><?=$row["projeto_descricao"];?></td>
             
                <td>R$ <?=number_format($row["valorprojeto"],2,',','.');?></td>
                <td class="actions text-center">
                    <a href="#"  style="margin: 20px;" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$row["projeto_id"]?>)"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row["projeto_id"];?>)"><i class="fa fa-trash-o"></i></a>
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
 * Atualiza Projeto
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["projeto-descricao"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a descrição!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".projeto SET projeto_descricao = ?, projeto_datainicial = ?, projeto_datafinal = ?, valorprojeto = ? WHERE projeto_id = ?");
            $statement->bindParam(1, $_parametros["projeto-descricao"]);
            $statement->bindParam(2, $_parametros["projeto-inicio"]);
            $statement->bindParam(3, $_parametros["projeto-fim"]);
            $statement->bindParam(4, $_parametros["projeto-valor"]);
            $statement->bindParam(5, $_parametros["projeto-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="100"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Atualizado!</h2>
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
 * Excluí Grupo
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".projeto WHERE projeto_id = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2> Excluído!</h2>
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

