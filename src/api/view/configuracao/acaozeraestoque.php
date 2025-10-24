<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

/*
 * Lista Almoxarifados
 * */
if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".almoxarifado ORDER BY Descricao");
    $retorno = $statement->fetchAll();

    ?>
    <label for="zera-almox" class="control-label">Almoxarifado: </label>
    <select name="zera-almox" id="zera-almox" class="form-control">
        <option value="">Selecione</option>
    <?php
    foreach ($retorno as $row) {
        ?>
        <option value="<?=$row["Codigo_Almox"]?>"><?=utf8_encode($row["Descricao"])?></option>
        <?php
    }
    ?>
    </select>
    <button class="btn btn-danger waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-zerar"><span class="btn-label"><i class="fa fa-warning"></i></span>Zerar</button>
    <?php

}
/*
 * Zera Almoxarifados
 * */
else if ($acao["acao"] == 3) {
    try {
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = '0' WHERE Codigo_Almox = :id");
        $statement->bindParam(':id', $_parametros["zera-almox"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Estoque Zerado!</h2>
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