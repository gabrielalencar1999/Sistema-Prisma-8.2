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
 * Listar Movimento
 * */
if ($acao["acao"] == 2) {

    $projetoPesquisa = $_parametros['mov-projeto'] == 0 ? "" : " AND itemestoquemovto.movim_projeto = '".$_parametros['mov-projeto']."'";
    $almoxPesquisa = $_parametros['mov-almox'] == 0 ? "" : " AND itemestoquemovto.Codigo_Almox = '".$_parametros['mov-almox']."'";
    //$itemPesquisa = $_parametros['mov-item'] == 0 ? "" : " AND itemestoquemovto.Codigo_Item = '".$_parametros['mov-item']."'";
    $tipoPesquisa = $_parametros['mov-tipo'] == '0' ? "" : " AND itemestoquemovto.Tipo_Movimento = '".$_parametros['mov-tipo']."'";

    if($_parametros["produto-filtro"] == 0 ){
        $itemPesquisa = " AND itemestoque.CODIGO_FORNECEDOR = '".$_parametros['mov-item']."'";
        $_pesquisa = "itemestoque.CODIGO_FORNECEDOR  as COD";
    }else if($_parametros["produto-filtro"] == 1 ) { //cod barra
        $itemPesquisa = " AND itemestoque.Codigo_Barra = '".$_parametros['mov-item']."'";
        $_pesquisa = "itemestoque.Codigo_Barra  as COD";
    }else if($_parametros["produto-filtro"] == 3 ) {//codigo barra
        $itemPesquisa = " AND itemestoque.CODIGO_FABRICANTE = '".$_parametros['mov-item']."'";
        $_pesquisa = "itemestoque.CODIGO_FABRICANTE as COD";
    }else{
        $_pesquisa = "itemestoque.CODIGO_FABRICANTE  as COD";
    }
    /* if ($_parametros["produto-filtro"] == 0) {
        $consultaProduto = $pdo->query("CALL ".$_SESSION['BASE'].".obtem_produto_por_codigo_fornecedor($busca, $grupo_id, $ativo)");
    }
    else if ($_parametros["produto-filtro"] == 1) {
        $consultaProduto = $pdo->query("CALL ".$_SESSION['BASE'].".obtem_produto_por_codigo_barras($busca, $grupo_id, $ativo)");
    }
    else {
        $consultaProduto = $pdo->query("CALL ".$_SESSION['BASE'].".obtem_produto_por_descricao($busca, $grupo_id, $ativo)");
    } */

    try {
    
        $consultaMov = $pdo->query("SELECT projeto_descricao,itemestoquemovto.Numero_Movimento,almoxarifado.Codigo_Almox as amox,
        itemestoquemovto.Usuario_Movto,PRECO_CUSTO,
        itemestoque.DESCRICAO , 
        almoxarifado.Descricao AS almox,
        itemestoquemovto.Codigo_Almox,
        $_pesquisa,
        itemestoquemovto.Data_Movimento, DATE_FORMAT(Data_Movimento, '%d/%m/%Y %T') AS dataB,
        tabmovtoestoque.Tipo_Movto_Estoque,
        itemestoquemovto.Codigo_Movimento,
        itemestoquemovto.Tipo_Movimento,
        itemestoquemovto.Qtde,
        itemestoquemovto.Valor_unitario,
        itemestoquemovto.Numero_Documento,
        tabmovtoestoque.Descricao AS tabdesc,
        almoxarifado.Descricao,
        itemestoquemovto.Vl_Custo_medio,
        itemestoquemovto.Saldo_Atual,
        itemestoquemovto.motivo AS mot,usuario_NOME,Codigo_Chamada 
        FROM  ".$_SESSION['BASE'].".itemestoquemovto  
        LEFT JOIN ".$_SESSION['BASE'].".tabmovtoestoque on tabmovtoestoque.Tipo_Movto_Estoque = itemestoquemovto.Tipo_Movimento
        LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = itemestoquemovto.Codigo_Item
        LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON itemestoquemovto.Codigo_Almox = almoxarifado.Codigo_Almox
        LEFT JOIN ".$_SESSION['BASE'].".projeto ON projeto_id = movim_projeto
        LEFT JOIN " . $_SESSION['BASE'] . ".usuario on itemestoquemovto.Usuario_Movto = usuario_CODIGOUSUARIO
        WHERE  DATE_FORMAT(Data_Movimento, '%Y-%m-%d')  between '".$_parametros['mov-dataini']."' AND '".$_parametros['mov-datafim']."' $projetoPesquisa $almoxPesquisa $itemPesquisa $tipoPesquisa
        ORDER BY itemestoquemovto.Numero_Movimento");
        $retornoMov = $consultaMov->fetchAll();
        ?>
       <!-- <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%">-->
       <table id="datatable-buttons" class="table table-striped table-bordered"  cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="text-center">Nº O.S</th>
                <th class="text-center">Descrição</th>
                <th class="text-center">Movimentação</th>
                <th class="text-center">Data</th>
                <th class="text-center">Almox</th>
                <th class="text-center">Cód.</th>
                <th class="text-center">N° Documento</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Qtde</th>
                <th class="text-center">Usuário</th>
                <th class="text-center">Saldo Atual</th>
                <th class="text-center">Motivo</th>
                
            </thead>
            <tbody>
            <?php
            foreach ($retornoMov as $row) {
                ?>
                <tr class="gradeX">
                    <td class="text-center" style="vertical-align: middle"><?=$row["Codigo_Chamada"]?></td>
                    <td class="text-center"><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                    <td class="text-center"><?=$row["tabdesc"]."/".$row["Codigo_Movimento"]?></td>
                    <td class="text-center"><?=$row["dataB"]?></td>
                    <td class="text-center"><?=$row["almox"]?></td>
                    <td class="text-center"><?=$row["COD"]?></td>
                    <td class="text-center"><?=$row["Numero_Documento"]?></td>
                    <td class="text-center"><?=number_format($row["Valor_unitario"], 2, ',', '.')?></td>
                    <td class="text-center"><?=$row["Qtde"]?></td>
                    <td class="text-center"><?=$row["usuario_NOME"]?></td>
                    <td class="text-center"><?=$row["Saldo_Atual"]?></td>
                    <td class="text-center"><?=$row["mot"]?></td>
                    
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
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
/**
 * Imprime Movimentos Filtro
 */
if ($acao["acao"] == 5) {

    $projetoPesquisa = $_parametros['mov-projeto'] == 0 ? "" : " AND itemestoquemovto.movim_projeto = '".$_parametros['mov-projeto']."'";
    $almoxPesquisa = $_parametros['mov-almox'] == 0 ? "" : " AND itemestoquemovto.Codigo_Almox = '".$_parametros['mov-almox']."'";
    //$itemPesquisa = $_parametros['mov-item'] == 0 ? "" : " AND itemestoquemovto.Codigo_Item = '".$_parametros['mov-item']."'";
    $tipoPesquisa = $_parametros['mov-tipo'] == '0' ? "" : " AND itemestoquemovto.Tipo_Movimento = '".$_parametros['mov-tipo']."'";

    if($_parametros["produto-filtro"] == 0 ){
        $itemPesquisa = " AND itemestoque.CODIGO_FORNECEDOR = '".$_parametros['mov-item']."'";
        $_pesquisa = "itemestoque.CODIGO_FORNECEDOR  as COD";
    }else if($_parametros["produto-filtro"] == 1 ) { //cod barra
        $itemPesquisa = " AND itemestoque.Codigo_Barra = '".$_parametros['mov-item']."'";
        $_pesquisa = "itemestoque.Codigo_Barra  as COD";
    }else if($_parametros["produto-filtro"] == 3 ) {//codigo barra
        $itemPesquisa = " AND itemestoque.CODIGO_FABRICANTE = '".$_parametros['mov-item']."'";
        $_pesquisa = "itemestoque.CODIGO_FABRICANTE as COD";
    }else{
        $_pesquisa = "itemestoque.CODIGO_FORNECEDOR  as COD";
    }
    /* if ($_parametros["produto-filtro"] == 0) {
        $consultaProduto = $pdo->query("CALL ".$_SESSION['BASE'].".obtem_produto_por_codigo_fornecedor($busca, $grupo_id, $ativo)");
    }
    else if ($_parametros["produto-filtro"] == 1) {
        $consultaProduto = $pdo->query("CALL ".$_SESSION['BASE'].".obtem_produto_por_codigo_barras($busca, $grupo_id, $ativo)");
    }
    else {
        $consultaProduto = $pdo->query("CALL ".$_SESSION['BASE'].".obtem_produto_por_descricao($busca, $grupo_id, $ativo)");
    } */
    try {
        $consultaMov = $pdo->query("SELECT projeto_descricao,itemestoquemovto.Numero_Movimento,almoxarifado.Codigo_Almox as amox,
        itemestoquemovto.Usuario_Movto,PRECO_CUSTO,
        itemestoque.DESCRICAO,
        itemestoque.Tab_Preco_5,
        almoxarifado.Descricao AS almox,
        itemestoquemovto.Codigo_Almox,
        itemestoquemovto.Codigo_Item,
        itemestoquemovto.Data_Movimento, DATE_FORMAT(Data_Movimento, '%d/%m/%Y %T') AS dataB,
        tabmovtoestoque.Tipo_Movto_Estoque,
        itemestoquemovto.Codigo_Movimento,
        itemestoquemovto.Tipo_Movimento,
        itemestoquemovto.Qtde,
        itemestoquemovto.Valor_unitario,
        itemestoquemovto.Numero_Documento,
        tabmovtoestoque.Descricao AS tabdesc,
        almoxarifado.Descricao,
        itemestoquemovto.Vl_Custo_medio,
        itemestoquemovto.Saldo_Atual,
        itemestoquemovto.motivo AS mot,usuario_NOME 
        FROM  ".$_SESSION['BASE'].".itemestoquemovto  
        LEFT JOIN ".$_SESSION['BASE'].".tabmovtoestoque on tabmovtoestoque.Tipo_Movto_Estoque = itemestoquemovto.Tipo_Movimento
        LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = itemestoquemovto.Codigo_Item
        LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON itemestoquemovto.Codigo_Almox = almoxarifado.Codigo_Almox
        LEFT JOIN ".$_SESSION['BASE'].".projeto ON projeto_id = movim_projeto
        LEFT JOIN " . $_SESSION['BASE'] . ".usuario on itemestoquemovto.Usuario_Movto = usuario_CODIGOUSUARIO
        WHERE  DATE_FORMAT(Data_Movimento, '%Y-%m-%d')  between '".$_parametros['mov-dataini']."' AND '".$_parametros['mov-datafim']."' $projetoPesquisa $almoxPesquisa $itemPesquisa $tipoPesquisa
        ORDER BY itemestoquemovto.Numero_Movimento");
        $retornoMov = $consultaMov->fetchAll();
        ?>
        <table class="table table-bordered" width="90%">
            <thead>
            <tr style="font-size: small">
            
        
                <th class="text-center" style="vertical-align: middle">Data</th>
                <th class="text-center" style="vertical-align: middle">Movimento</th>
                <th class="text-center" style="vertical-align: middle">Cód. Item</th>
                <th class="text-center" style="vertical-align: middle">Descrição</th>
                <th class="text-center" style="vertical-align: middle">Vlr. Venda</th>
                <th class="text-center" style="vertical-align: middle">Vlr. Custo</th>
                <th class="text-center" style="vertical-align: middle">Qtde</th>
                <th class="text-center" style="vertical-align: middle">Total Venda</th>
                <th class="text-center" style="vertical-align: middle">Total Custo</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($retornoMov as $row) {
                ?>
                <tr style="font-size: small">
              
                    
                    <td class="text-center" style="vertical-align: middle"><?=$row["dataB"]?></td>
                    <td class="text-center" style="vertical-align: middle"><?=$row["tabdesc"]."/".$row["Codigo_Movimento"]?></td>
                    <td class="text-center" style="vertical-align: middle"><?=$row["Codigo_Item"]?></td>
                    <td class="text-center" style="vertical-align: middle"><?=utf8_encode(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',', '.')?></td>
                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                    <td class="text-center" style="vertical-align: middle"><?=$row["Qtde"]?></td>
                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["PRECO_CUSTO"] * $row["Qtde"]), 2, ',', '.')?></td>
                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["Tab_Preco_5"] * $row["Qtde"]), 2, ',', '.')?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
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