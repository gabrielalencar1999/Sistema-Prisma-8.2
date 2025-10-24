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
 * Inclui Notas
 * */
if ($acao["acao"] == 1) {
    $consultaNF = $pdo->query("SELECT NFE_NRO FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["nf-num"]."' AND NFE_FORNEC = '".$_parametros["nf-fornec"]."'");
    $retornoNF = $consultaNF->fetch();


    if (!$retornoNF) {
        date_default_timezone_set('America/Sao_Paulo');
        $data_entrada = date("Y-m-d H:i:s", time());
        try {
            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_base (NFE_NRO, NFE_FORNEC, NFE_DATAENTR) VALUES (?, ?, ?)");
            $statement->bindParam(1, $_parametros["nf-num"]);
            $statement->bindParam(2, $_parametros["nf-fornec"]);
            $statement->bindParam(3, $data_entrada);
            $statement->execute();
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Nota Cadastrada!</h2>
                            <button class="btn btn-success waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_alterar(<?=$_parametros["nf-num"]?>, <?=$_parametros["nf-fornec"]?>)">Ir para Cadastro</button>
                            <input type="hidden" id="retorno-nota" name="retorno-nota" value="true">
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
    else {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Nota Fiscal já cadastrada!</h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/*
 * Lista Notas
 * */
else if ($acao["acao"] == 2) {
    if($_parametros["nf-fornecedor"] != "" ) { 
        $filtro2 = " AND NFE_FORNEC = '".$_parametros["nf-fornecedor"]."' ";
      
    }
    empty($_parametros["nf-fornecedor"]) ?: $_parametros["nf-fornecedor"] = "AND NFE_FORNEC = '". $_parametros["nf-fornecedor"] ."' ";
    empty($_parametros["nf-empresa"]) ?: $_parametros["nf-empresa"] = " AND empresa = '". $_parametros["nf-empresa"] ."'";
    $_parametros["nf-final"] =  date('Y-m-d', strtotime('+1 days', strtotime($_parametros["nf-final"])));
    
    if($_parametros["_numeronf"] != "" ) { 
        $filtro = " AND NFE_NRO = '".$_parametros["_numeronf"]."' OR NFE_NRO = '".$_parametros["_numeronf"]."'  $filtro2  ";
    }

    try {
      
        $statement = $pdo->query("SELECT NFE_ESTOK,NFE_CPGOK,NFE_NRO,NOME,NFE_FORNEC,CODIGO_FABRICANTE,NFE_TOTALNF,NFE_Conferido,NFE_INFOADD,DATE_FORMAT(NFE_DATAENTR, '%d/%m/%Y') AS NF_ENTRADA FROM ". $_SESSION['BASE'] .".nota_ent_base LEFT JOIN ". $_SESSION['BASE'] .".fabricante ON CODIGO_FABRICANTE = NFE_FORNEC LEFT JOIN ". $_SESSION['BASE'] .".empresa ON empresa_id = empresa WHERE NFE_DATAENTR BETWEEN '". $_parametros['nf-inicial'] ."' AND '". $_parametros['nf-final'] ."' ".$_parametros["nf-fornecedor"] . $_parametros["nf-empresa"]." $filtro ORDER BY NFE_DATAENTR, NFE_RAZSOC");
        $retorno = $statement->fetchAll();
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
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>N° NF</th>
            <th>Fornecedor</th>          
            <th style="width:80px ;">Dt Entrada</th>
            <th>Valor NF</th>
            <th class="text-center" style="width:50px ;">Conf.NF</th>
            <th class="text-center" style="width:300px ;">Obs</th>
            <th class="text-right" style="width:30px ;">Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalFF = 0.0;
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td><?=$row["NFE_NRO"]?></td>
                <td><?=($row["NOME"])?></td>               
                <td><?=$row["NF_ENTRADA"]?></td>
                <td>R$ <?=number_format($row["NFE_TOTALNF"], 2, ',', '.')?></td>
                <td class="text-center">
                    <span class="badge badge-<?=$row["NFE_ESTOK"] == "-1" ? "success" : "inverse" ?> m-l-0" style="font-size: 12px;"><i class="fa  fa-wrench "></i></span>
                    <span class="badge badge-<?=$row["NFE_CPGOK"] == "-1" ? "success" : "inverse" ?> m-l-0" style="font-size: 12px;"><i class="fa  fa-money"></i></span>                 
                    
                </td>
                <td style="width:200px ;"><?=$row["NFE_INFOADD"]?></td>
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default edit-row" style="padding-right: 10px;" onclick="_alterar(<?=$row["NFE_NRO"];?>,<?=$row["CODIGO_FABRICANTE"];?>)"><i class="fa fa-pencil"></i></a>

                    <a href="javascript:void(0);" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row["NFE_NRO"]?>, <?=$row["NFE_FORNEC"]?>)"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
            <?php
            $totalFF += $row["NFE_TOTALNF"];
        }
        ?>
        </tbody>
    </table>
    <div class="alert alert-info">
        Total <strong>R$<?=number_format($totalFF, 2, ',', '.')?></strong>
    </div>
    <?php
}
/*
 * Excluir Notas
 * */
else if ($acao["acao"] == 4) {
    $consultaNF = $pdo->query("SELECT NFE_Conferido,NFE_CPGOK FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
    $retornoNF = $consultaNF->fetch();

    if ($retornoNF["NFE_Conferido"] == "-1" or $retornoNF["NFE_CPGOK"] == "-1") {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Nota Fiscal com financeiro já lançado!</h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $consultaNF = $pdo->query("SELECT NFE_ALMOX FROM ". $_SESSION['BASE'] .".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
            $resultNF = $consultaNF->fetch();

            $consultaProd = $pdo->query("SELECT NFE_CHAVE, NFE_NRO, NFE_FORNEC, NFE_CODIGO, NFE_QTDADE, NFE_VLRUNI, NFE_TOTALITEM, NFE_ESTOK FROM ". $_SESSION['BASE'] .".nota_ent_item WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
            $resultProd = $consultaProd->fetchAll();

            foreach ($resultProd as $row) {
                if (intval($row["NFE_ESTOK"]) == 0) {
                    $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_CHAVE = ?");
                    $statement->bindParam(1, $row["NFE_CHAVE"]);
                    $statement->execute();
                }
                else {
                    date_default_timezone_set('America/Sao_Paulo');
                    $codigoMov = "S";
                    $tipMov = "E";
                    $numDocumento = $row["NFE_NRO"]."-".$row["NFE_FORNEC"];
                    $row["NFE_VLRUNI"] = number_format($row["NFE_VLRUNI"], 2, ".", "");
                    $inventario = "0";
                    $row["NFE_TOTALITEM"] = number_format($row["NFE_TOTALITEM"], 2, ".", "");
                    $motivo = "Exclusao da NF";
                    $dataMov = date("Y-m-d H:i:s", time());

                    $consultaQuantidade = $pdo->query("SELECT Qtde_Disponivel FROM ". $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '".$row["NFE_CODIGO"]."' AND Codigo_Almox = '".$resultNF["NFE_ALMOX"]."'");
                    $retorno = $consultaQuantidade->fetch();;
                    $quantidadeAtual = intval($retorno["Qtde_Disponivel"]) - intval($row["NFE_QTDADE"]);

                    $updateEstoque = $pdo->prepare("UPDATE ". $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                    $updateEstoque->bindParam(1, $quantidadeAtual);
                    $updateEstoque->bindParam(2, $row["NFE_CODIGO"]);
                    $updateEstoque->bindParam(3, $resultNF["NFE_ALMOX"]);
                    $updateEstoque->execute();

                    $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_CHAVE = ?");
                    $statement->bindParam(1, $row["NFE_CHAVE"]);
                    $statement->execute();

                    $insertMov = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde, Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertMov->bindParam(1, $row["NFE_CODIGO"]);
                    $insertMov->bindParam(2, $row["NFE_QTDADE"]);
                    $insertMov->bindParam(3, $resultNF["NFE_ALMOX"]);
                    $insertMov->bindParam(4, $codigoMov);
                    $insertMov->bindParam(5, $tipMov);
                    $insertMov->bindParam(6, $numDocumento);
                    $insertMov->bindParam(7, $row["NFE_VLRUNI"]);
                    $insertMov->bindParam(8, $inventario);
                    $insertMov->bindParam(9, $row["NFE_TOTALITEM"]);
                    $insertMov->bindParam(10, $_SESSION["IDUSER"]);
                    $insertMov->bindParam(11,$motivo);
                    $insertMov->bindParam(12, $quantidadeAtual);
                    $insertMov->bindParam(13,$dataMov);
                    $insertMov->execute();
                }
            }

            $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_base WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
            $statement->bindParam(1, $_parametros["id-nota"]);
            $statement->bindParam(2, $_parametros["id-fornecedor"]);
            $statement->execute();

            $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_pgto WHERE NFE_NRO = ? AND NFE_FORNEC = ? AND NFE_LANCADO = '0'");
            $statement->bindParam(1, $_parametros["id-nota"]);
            $statement->bindParam(2, $_parametros["id-fornecedor"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Nota Excluída!</h2>
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
                    <div class="modal-body" id="imagem-carregando">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}