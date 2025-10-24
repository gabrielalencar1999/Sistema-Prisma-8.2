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

function diasDatas($data_inicial,$data_final) {
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24)); 
    return $dias;
}

if($_parametros["relatorio-endereco"] == 1){
    $_campos_endereco = ",itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,itemestoque.ENDERECO_COMP";
    $_filtro_endereco = "1";
}
if($_parametros["relatorio-grupo"] != ''){ //estoque minimo
    $filtrogrupo = " and GRU_GRUPO = '".$_parametros["relatorio-grupo"]."'";
  ;
}





        $consulta = $pdo->query("Select empresa_vizCodInt from ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetchAll();
        foreach ($retorno as $row) {
            $_vizCodInterno = $row["empresa_vizCodInt"];
        }
        if( $_vizCodInterno == 1) {
            $_codviewer = "CODIGO_FABRICANTE";
          }else{
            $_codviewer = "CODIGO_FORNECEDOR";
          }
          
       
   
/*
 * Incluir Relatório
 * */
if ($_parametros["relatorio-tipo"] == 1) {
    try {
        $sl = "SELECT CODIGO,$_codviewer,itemestoquealmox.Qtde_Disponivel,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,
        Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5
        $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque      
        LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR
        WHERE Qtde_Disponivel > 0 ORDER BY itemestoque.DESCRICAO";

        $consulta = $pdo->query("$sl");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 class="text-center">Relatório de Produtos - Com estoque</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                   
                                    <th class="text-center" style="vertical-align: middle">Codigo</th>
                                    <th class="text-center" style="vertical-align: middle">Descricao</th>
                                    <th class="text-center" style="vertical-align: middle">Estoque</th>
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                    <th class="text-center" style="vertical-align: middle">Vlr. Custo</th>
                                    <th class="text-center" style="vertical-align: middle">Total Custo</th>
                                    <th class="text-center" style="vertical-align: middle">Vlr. Venda</th>
                                    <th class="text-center" style="vertical-align: middle">Total Venda</th>
                                    <th class="text-center" style="vertical-align: middle">%</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                }
                                ?>
                                <tr style="font-size: small">
                                   
                                    <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["Qtde_Disponivel"]?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["Qtde_Disponivel"] * $row["PRECO_CUSTO"]), 2, ',' ,'.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',' ,'.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["Tab_Preco_5"] * $row["Qtde_Disponivel"]), 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["PRECO_CUSTO"] > 0 ? number_format((((($row["Tab_Preco_5"] * $row["Qtde_Disponivel"]) / ($row["PRECO_CUSTO"] * $row["Qtde_Disponivel"])) -1) * 100), 2, ',', '.')."%" : "0"?></td>
                                </tr>
                                <?php
                                $vlrCusto = $vlrCusto + ($row["Qtde_Disponivel"]*$row["PRECO_CUSTO"]);
                                $vlrVendaT = $vlrVendaT + ($row["Tab_Preco_5"]*$row["Qtde_Disponivel"]);
                                $qtde = $qtde + $row["Qtde_Disponivel"];
                                $custo = $custo + ($row["Qtde_Disponivel"]*$row["PRECO_CUSTO"]);    
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right"  colspan="3"><strong>Total</strong></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$qtde?></td>
                                   
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"></td>
                                    <?php } ?>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($vlrCusto, 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($vlrVendaT, 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <h3 class="text-center">Relatório de Produtos - Com estoque</h3>
            <table class="bordasimples" width="100%" >
                <thead>
                    <tr style="font-size: small" >
                        
                        <th class="text-center" style="vertical-align: middle">Codigo</th>
                        <th class="text-center" style="vertical-align: middle">Descricao</th>
                        <th class="text-center" style="vertical-align: middle">Estoque</th>
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                        <th class="text-center" style="vertical-align: middle">Vlr. Custo</th>
                        <th class="text-center" style="vertical-align: middle">Total Custo</th>
                        <th class="text-center" style="vertical-align: middle">Vlr. Venda</th>
                        <th class="text-center" style="vertical-align: middle">Total Venda</th>
                        <th class="text-center" style="vertical-align: middle">%</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($retorno as $row) {
                    $ender = ""; 
                    if($_filtro_endereco == 1) { 
                        if($row["ENDERECO1"] != ""){
                            $ender = $row["ENDERECO1"];
                            if(substr($row["ENDERECO1"],0,1) == "R"){
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"];
                                    if($row["ENDERECO3"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO3"];
                                    }
                                }
                            }else{
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                }
                            }
                        }
                        $ender = $ender." ".$row["ENDERECO_COMP"];
                    }
                    ?>
                    <tr style="font-size: small" >
                    
                        <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                        <td class="text-left" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["Qtde_Disponivel"]?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                        <td class="text-right" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                        <td class="text-right" style="vertical-align: middle"><?=number_format(($row["Qtde_Disponivel"] * $row["PRECO_CUSTO"]), 2, ',' ,'.')?></td>
                        <td class="text-right" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',' ,'.')?></td>
                        <td class="text-right" style="vertical-align: middle"><?=number_format(($row["Tab_Preco_5"] * $row["Qtde_Disponivel"]), 2, ',', '.')?></td>
                        <td class="text-right" style="vertical-align: middle"><?=$row["PRECO_CUSTO"] > 0 ? number_format((((($row["Tab_Preco_5"] * $row["Qtde_Disponivel"]) / ($row["PRECO_CUSTO"] * $row["Qtde_Disponivel"])) -1) * 100), 2, ',', '.')."%" : "0"?></td>
                    </tr>
                    <?php
                    $vlrCusto = $vlrCusto + ($row["Qtde_Disponivel"]*$row["PRECO_CUSTO"]);
                    $vlrVendaT = $vlrVendaT + ($row["Tab_Preco_5"]*$row["Qtde_Disponivel"]);
                    $qtde = $qtde + $row["Qtde_Disponivel"];
                    $custo = $custo + ($row["Qtde_Disponivel"]*$row["PRECO_CUSTO"]);    
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right" style="vertical-align: middle" colspan="2"><strong>Total</strong></td>
                        <td  style="vertical-align: middle"> <?=$qtde?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"></td>
                                    <?php } ?>
                        <td class="text-right" style="vertical-align: middle"></td>
                        <td style="vertical-align: middle; text-right:right"><?=number_format($vlrCusto, 2, ',', '.')?></td>
                        <td class="text-right" style="vertical-align: middle"></td>
                        <td class="text-right" style="vertical-align: middle"><?=number_format($vlrVendaT, 2, ',', '.')?></td>
                        <td class="text-right" style="vertical-align: middle"></td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 2) {
    try {
        $sql = "SELECT CODIGO,$_codviewer,itemestoquealmox.Qtde_Disponivel AS qt,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,itemestoque.QTDE_EST_MINIMO AS min,itemestoque.Estoque_Maximo AS max,codigo_barra,Tab_Preco_5
        $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR ORDER BY itemestoque.Codigo_Barra";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 class="text-center">Relatório Geral de Produtos - Código de Barras</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" style="vertical-align: middle">Código de Barras</th>
                                    <th class="text-center" style="vertical-align: middle">Descrição</th>
                                    <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                    <th class="text-center" style="vertical-align: middle">Custo</th>
                                    <th class="text-center" style="vertical-align: middle">Vlr. Venda</th>
                                    <th class="text-center" style="vertical-align: middle">Total</th>
                                    <th class="text-center" style="vertical-align: middle">%</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                }
                                ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["Codigo_Barra"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["qt"]?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',' ,'.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["qt"] * $row["Tab_Preco_5"]), 2, ',' ,'.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["PRECO_CUSTO"] > 0 ? number_format(((($row["Tab_Preco_5"] /($row["PRECO_CUSTO"])) -1) * 100), 2, ',', '.')."%" : "0"?></td>
                                </tr>
                                <?php
                                $qtde = $qtde + $row["qt"];   
                                $custo = $custo + ($row["qt"]*$row["PRECO_CUSTO"]);
                                $TT = $TT + ($row["qt"]*$row["Tab_Preco_5"]);
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right" colspan="2"><strong>Total</strong></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$qtde?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"></td>
                                    <?php } ?>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($custo, 2, ',', '.')?></td>
                                 
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($TT, 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"></td>
                                    <td class="text-center" style="vertical-align: middle"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <h3 class="text-center">Relatório Geral de Produtos - Código de Barras</h3>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" style="vertical-align: middle">Código de Barras</th>
                        <th class="text-center" style="vertical-align: middle">Descrição</th>
                        <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                        <th class="text-center" style="vertical-align: middle">Custo</th>
                        <th class="text-center" style="vertical-align: middle">Vlr. Venda</th>
                        <th class="text-center" style="vertical-align: middle">Total</th>
                        <th class="text-center" style="vertical-align: middle">%</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($retorno as $row) {
                    $ender = ""; 
                    if($_filtro_endereco == 1) { 
                        if($row["ENDERECO1"] != ""){
                            $ender = $row["ENDERECO1"];
                            if(substr($row["ENDERECO1"],0,1) == "R"){
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"];
                                    if($row["ENDERECO3"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO3"];
                                    }
                                }
                            }else{
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                }
                            }
                        }
                        $ender = $ender." ".$row["ENDERECO_COMP"];
                    }
                    ?>
                    <tr style="font-size: small">
                        <td class="text-center" style="vertical-align: middle"><?=$row["Codigo_Barra"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["qt"]?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',' ,'.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format(($row["qt"] * $row["Tab_Preco_5"]), 2, ',' ,'.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["PRECO_CUSTO"] > 0 ? number_format(((($row["Tab_Preco_5"] /($row["PRECO_CUSTO"])) -1) * 100), 2, ',', '.')."%" : "0"?></td>
                    </tr>
                    <?php
                    $qtde = $qtde + $row["qt"];   
                    $custo = $custo + ($row["qt"]*$row["PRECO_CUSTO"]);
                    $TT = $TT + ($row["qt"]*$row["Tab_Preco_5"]);
                }
                ?>
                </tbody>
                <tfoot>
                    <tr style="font-size: small">
                        <td class="text-right"  colspan="2"><strong>Total</strong></td>
                        <td class="text-center" style="vertical-align: middle"><?=$qtde?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($custo, 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($TT, 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"></td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 3) {
    try {
        $grupo = empty($_parametros["relatorio-grupo"]) ? "" : " AND GRU_GRUPO = '".$_parametros["relatorio-grupo"]."'";

        $filmimimo = $_parametros["relatorio-estoqueminimo"] == 0  ? "" : " AND itemestoquealmox.Qtde_Disponivel < itemestoque.QTDE_EST_MINIMO"; 
        $sql= "SELECT 
                itemestoque.UNIDADE_MEDIDA,
                itemestoque.CODIGO_FABRICANTE,
                itemestoquealmox.Qtde_Disponivel,
                itemestoque.PRECO_CUSTO,
                itemestoque.DESCRICAO,
                itemestoque.QTDE_EST_MINIMO AS min,
                itemestoque.Estoque_Maximo AS max,
                itemestoque.Descricao   AS Descricao,
                almoxarifado.Descricao AS descc
            FROM 
               ".$_SESSION['BASE'].".itemestoque
            LEFT JOIN 
                ".$_SESSION['BASE'].".itemestoquealmox ON itemestoque.CODIGO_FORNECEDOR = itemestoquealmox.CODIGO_ITEM
            LEFT JOIN 
                ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox
            WHERE 
                itemestoque.QTDE_EST_MINIMO IS NOT NULL
                AND itemestoque.QTDE_EST_MINIMO > 0
                $filmimimo
                $filtrogrupo
            ORDER BY 
                itemestoque.DESCRICAO;";
               
        $consulta = $pdo->query($sql);         
        $retorno = $consulta->fetchAll();
         //ver csv
    if ($_parametros["relatorio-arquivo"] == 2){
        $nomearquivo = "Prisma_RelEstoqueMinimo";
        $dir = "docs/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "Código Fabricante;Descrição;Unidade;Almoxarifado;Endereço;Est.Atual;Est.Minimo;Est.Max;Custo";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
            $codint = $row["CODIGO_FORNECEDOR"];
            $cod = $row["CODIGO_FABRICANTE"];
            $desc = $row["DESCRICAO"];
            $qtde =$row["qtde"];
            $min =$row["min"];
            $max =$row["max"];
            $almoxarifado =$row["descc"];
            $Qtde_Disponivel = $row['Qtde_Disponivel'];
            $unidade  =$row["UNIDADE_MEDIDA"];
            $custo = number_format($row["PRECO_CUSTO"], 2, ',', '.');
            $enderA =  $row["ENDERECO1"];
            $enderB = $row["ENDERECO2"];
            $enderC = $row["ENDERECO3"];
            $endereco = $enderA."/".$enderB."/".$enderC;
            $_itemlinha = "$cod;$desc;$unidade;$almoxarifado;$endereco;$Qtde_Disponivel;$min;$max;$custo";
            fwrite($fp,$_itemlinha."\r\n");

        }
        fclose($fp);   
       
            $arquivo = $nomearquivo.'.csv';
        
            if( file_exists($arquivo_caminho)){ 
            ?>
             <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                            <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                               
                            </div>
                        </div>
                    </div>
        <?php
            }else{ ?>
 <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                                <h2>Sem registros nesse periodo</h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv

    }else{

   
        
        //if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 class="text-center">Relatório de Produtos - Estoque Mínimo </h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" style="vertical-align: middle">Codigo</th>
                                    <th class="text-center" style="vertical-align: middle">Descriçao</th>
                                    <th class="text-center" style="vertical-align: middle">Unidade</th>
                                    <th class="text-center" style="vertical-align: middle">Almoxarifado</th>
                                
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                    <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
                                    <th class="text-center" style="vertical-align: middle">Estoque Min.</th>
                                    <th class="text-center" style="vertical-align: middle">Estoque Máx.</th>
                                    <th class="text-center" style="vertical-align: middle">Custo</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($retorno as $row):
                             $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                } ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 50 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["UNIDADE_MEDIDA"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["descc"]?></td>
                               
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["Qtde_Disponivel"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["min"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["max"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
       /* }
        else {
            ?>
            <h3 class="text-center">Relatório de Produtos - Com estoque mínimo</h3>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" style="vertical-align: middle">Codigo</th>
                        <th class="text-center" style="vertical-align: middle">Descriçao</th>
                        <th class="text-center" style="vertical-align: middle">Unidade</th>
                        <th class="text-center" style="vertical-align: middle">Almoxarifado</th>
            
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                        <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
                        <th class="text-center" style="vertical-align: middle">Estoque Min.</th>
                        <th class="text-center" style="vertical-align: middle">Estoque Máx.</th>
                        <th class="text-center" style="vertical-align: middle">Custo</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($retorno as $row): 
                 $ender = ""; 
                    if($_filtro_endereco == 1) { 
                        if($row["ENDERECO1"] != ""){
                            $ender = $row["ENDERECO1"];
                            if(substr($row["ENDERECO1"],0,1) == "R"){
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"];
                                    if($row["ENDERECO3"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO3"];
                                    }
                                }
                            }else{
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                }
                            }
                        }
                        $ender = $ender." ".$row["ENDERECO_COMP"];
                    }?>
                    <tr style="font-size: small">
                        <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                        <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 50 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["UNIDADE_MEDIDA"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["descc"]?></td>                        
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>									 
                        <td class="text-center" style="vertical-align: middle"><?=$row["Qtde_Disponivel"]?></td>                      
                        <td class="text-center" style="vertical-align: middle"><?=$row["min"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["max"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        }*/

         }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 4) {
    try {
        $consulta = $pdo->query("SELECT $_codviewer,DESCRICAO,,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
        $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque 
        LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE NOT EXISTS  (SELECT  * FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE saidaestoqueitem.DATA_COMPRA 
        BETWEEN '".$_parametros['relatorio-dataini']."' AND '".$_parametros['relatorio-datafim']."' AND CODIGO_ITEM = itemestoque.CODIGO_FORNECEDOR)
        GROUP BY $_codviewer,DESCRICAO ORDER BY DESCRICAO");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                    <h3 class="text-center">Relatório Geral de Produtos - Não vendidos por período</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" style="vertical-align: middle">Codigo</th>
                                  
                                    <th class="text-center" style="vertical-align: middle">Descricao</th>
                                    <th class="text-center" style="vertical-align: middle">Estoque</th>
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($retorno as $row):
                             $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                } ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                                    
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["tot_item"]?></td>
                                     <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <h3 class="text-center">Relatório Geral de Produtos - Não vendidos por período</h3>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" style="vertical-align: middle">Codigo</th>
           
                        <th class="text-center" style="vertical-align: middle">Descricao</th>
                        <th class="text-center" style="vertical-align: middle">Estoque</th>
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($retorno as $row): ?>
                    <tr style="font-size: small">
                        <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                       
                        <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["tot_item"]?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 5) {
    try {
        $lista = empty($_parametros['relatorio-lista']) ? "<> ''" : "= '".$_parametros['relatorio-lista']."'";

        $consulta = $pdo->query("SELECT $_codviewer,DESCRICAO,GRUPO_PECAS,Codigo_Barra,Tab_Preco_5 
        $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque WHERE GRUPO_PECAS $lista ORDER BY grupo_pecas, DESCRICAO");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" colspan="3" style="vertical-align: middle">LISTA DE PRODUTOS</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($retorno as $row): 
                             $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                }?>
                                <?php $grupoAtual = $row["GRUPO_PECAS"];?>
                                <?php if ($grupo != $grupoAtual): ?>
                                    <?php $grupo = $grupoAtual; ?>
                                    <tr style="font-size: small">
                                        <td class="text-center" colspan="3" style="vertical-align: middle"><strong><?=$grupo?></strong></td>
                                    </tr> 
                                <?php endif; ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["Tab_Preco_5"]), 2, ',', '.')?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" colspan="3" style="vertical-align: middle">LISTA DE PRODUTOS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($retorno as $row): 
                     $ender = ""; 
                        if($_filtro_endereco == 1) { 
                            if($row["ENDERECO1"] != ""){
                                $ender = $row["ENDERECO1"];
                                if(substr($row["ENDERECO1"],0,1) == "R"){
                                    if($row["ENDERECO2"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO2"];
                                        if($row["ENDERECO3"] != ""){
                                            $ender =   $ender."/".$row["ENDERECO3"];
                                        }
                                    }
                                }else{
                                    if($row["ENDERECO2"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                    }
                                }
                            }
                            $ender = $ender." ".$row["ENDERECO_COMP"];
                        }?>
                        <?php $grupoAtual = $row["GRUPO_PECAS"];?>
                        <?php if ($grupo != $grupoAtual): ?>
                            <?php $grupo = $grupoAtual; ?>
                            <tr style="font-size: small">
                                <td class="text-center" colspan="3" style="vertical-align: middle"><strong><?=$grupo?></strong></td>
                            </tr> 
                        <?php endif; ?>
                        <tr style="font-size: small">
                            <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                            <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                            <td class="text-center" style="vertical-align: middle"><?=number_format(($row["Tab_Preco_5"]), 2, ',', '.')?></td>
                            <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 6) {
    try {
        $fornecedor = empty($_parametros['relatorio-fornecedor']) ? "" : " AND NFE_FORNEC = '".$_parametros['relatorio-fornecedor']."'";

        $consulta = $pdo->query("SELECT NFE_FORNEC,NFE_NRO,DATE_FORMAT(NFE_DATAENTR, '%d/%m/%Y' ) AS
        Data,NFE_TOTALNF,nome $_campos_endereco FROM ".$_SESSION['BASE'].".nota_ent_base INNER JOIN ".$_SESSION['BASE'].".fabricante ON NFE_FORNEC =  CODIGO_FABRICANTE WHERE NFE_DATAENTR BETWEEN '".$_parametros['relatorio-dataini']."' AND '".$_parametros['relatorio-datafim']."' $fornecedor ORDER BY NFE_DATAENTR, NOME");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 class="text-center">Relatório de Entradas de Notas Fiscais</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" style="vertical-align: middle">N° da Nota</th>
                                    <th class="text-center" style="vertical-align: middle">Fornecedor</th>
                                    <th class="text-center" style="vertical-align: middle">Data Entrada</th>
                                    <th class="text-center" style="vertical-align: middle">Valor</th>
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                }
                                ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["NFE_FORNEC"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["nome"])> 20 ? substr($row["nome"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["Data"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["NFE_TOTALNF"], 2, ',', '.')?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                </tr>
                                <?php
                                $tot = $tot + $row["NFE_TOTALNF"];
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right"  colspan="3"><strong>Total</strong></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($tot, 2, ',', '.')?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <h3 class="text-center">Relatório de Entradas de Notas Fiscais</h3>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" style="vertical-align: middle">N° da Nota</th>
                        <th class="text-center" style="vertical-align: middle">Fornecedor</th>
                        <th class="text-center" style="vertical-align: middle">Data Entrada</th>
                        <th class="text-center" style="vertical-align: middle">Valor</th>
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($retorno as $row) {
                    $ender = ""; 
                    if($_filtro_endereco == 1) { 
                        if($row["ENDERECO1"] != ""){
                            $ender = $row["ENDERECO1"];
                            if(substr($row["ENDERECO1"],0,1) == "R"){
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"];
                                    if($row["ENDERECO3"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO3"];
                                    }
                                }
                            }else{
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                }
                            }
                        }
                        $ender = $ender." ".$row["ENDERECO_COMP"];
                    }
                    ?>
                    <tr style="font-size: small">
                        <td class="text-center" style="vertical-align: middle"><?=$row["NFE_FORNEC"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=(strlen($row["nome"])> 20 ? substr($row["nome"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["Data"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["NFE_TOTALNF"], 2, ',', '.')?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                    </tr>
                    <?php
                    $tot = $tot + $row["NFE_TOTALNF"];
                }
                ?>
                </tbody>
                <tfoot>
                    <tr style="font-size: small">
                        <td class="text-right"  colspan="3"><strong>Total</strong></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($tot, 2, ',', '.')?></td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 7) {
    try {
        $grupo = empty($_parametros['relatorio-grupo']) ? "" : " AND GRU_GRUPO = '".$_parametros['relatorio-grupo']."'";
        $extra_a = empty($_parametros['relatorio-extra-a']) ? "" : " AND item_extraA = '".$_parametros['relatorio-extra-a']."'";
        $extra_b = empty($_parametros['relatorio-extra-b']) ? "" : " AND item_extraB = '".$_parametros['relatorio-extra-b']."'";
        $linha = empty($_parametros['relatorio-linha']) ? "" : " AND CODIGO_LINHA = '".$_parametros['relatorio-linha']."'";
        
        if (empty($_parametros['relatorio-estoque'])) {
            $estoque = "";
        }
        else if ($_parametros['relatorio-estoque'] == 1) {
            $estoque = " AND Qtde_Disponivel > 0";
        }
        else {
            $estoque = " AND Qtde_Disponivel <= 0";
        }

        $sql = "SELECT CODIGO,$_codviewer,itemestoquealmox.Qtde_Disponivel AS qt,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,itemestoque.PRECO_CONSUM_2,itemestoque.PRECO_OFICINA,itemestoque.QTDE_EST_MINIMO 
        AS min,itemestoque.Estoque_Maximo AS max,codigo_barra,Tab_Preco_5,Codigo_Barra,DATE_FORMAT(item_validade_A, '%d/%m/%Y' ) AS
        DataA,DATE_FORMAT(item_validade_B, '%d/%m/%Y' ) AS DataB $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque 
        LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
        WHERE $_codviewer LIKE '%%' $extra_a $extra_b $grupo $estoque $linha ORDER BY itemestoque.DESCRICAO limit 10 ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-exlg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 class="text-center">Relatório Geral de Produtos</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" style="vertical-align: middle">Código</th>
                                    <th class="text-center" style="vertical-align: middle">Descrição</th>
                                    <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                    <th class="text-center" style="vertical-align: middle">Custo</th>
                                    <th class="text-center" style="vertical-align: middle">Valor Venda</th>
                                    <th class="text-center" style="vertical-align: middle">Valor Total</th>
                                    <th class="text-center" style="vertical-align: middle">%</th>
                                    <th class="text-center" style="vertical-align: middle">Desconto %</th>
                                    <th class="text-center" style="vertical-align: middle">Pontos</th>
                                    <th class="text-center" style="vertical-align: middle">Data Validade A</th>
                                    <th class="text-center" style="vertical-align: middle">Data Validade B</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                }
                                ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["qt"]?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["qt"] * $row["Tab_Preco_5"]), 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$rst["PRECO_CUSTO"] > 0 ?  number_format(((($row["Tab_Preco_5"] / ($row["PRECO_CUSTO"])) -1 ) * 100), 2, ',' ,'.')."%" : "0"?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CONSUM_2"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_OFICINA"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["DataA"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["DataB"]?></td>
                                </tr>
                                <?php
                                $qtde = $qtde + $row["qt"];
                                $custo = $custo + ($row["qt"]*$row["PRECO_CUSTO"]);
                                $TT = $TT + ($row["qt"]*$row["Tab_Preco_5"]);
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right"  colspan="2"><strong>Total</strong></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$qtde?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"></td>
                                     <?php  } ?>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($custo, 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($TT, 2, ',', '.')?></td>
                                    <td class="text-center" colspan="5" style="vertical-align: middle"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <h3 class="text-center">Relatório de Entradas de Notas Fiscais</h3>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" style="vertical-align: middle">Código</th>
                        <th class="text-center" style="vertical-align: middle">Descrição</th>
                        <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                        <th class="text-center" style="vertical-align: middle">Custo</th>
                        <th class="text-center" style="vertical-align: middle">Valor Venda</th>
                        <th class="text-center" style="vertical-align: middle">Valor Total</th>
                        <th class="text-center" style="vertical-align: middle">%</th>
                        <th class="text-center" style="vertical-align: middle">Desconto %</th>
                        <th class="text-center" style="vertical-align: middle">Pontos</th>
                        <th class="text-center" style="vertical-align: middle">Data Validade A</th>
                        <th class="text-center" style="vertical-align: middle">Data Validade B</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($retorno as $row) {
                    $ender = ""; 
                    if($_filtro_endereco == 1) { 
                        if($row["ENDERECO1"] != ""){
                            $ender = $row["ENDERECO1"];
                            if(substr($row["ENDERECO1"],0,1) == "R"){
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"];
                                    if($row["ENDERECO3"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO3"];
                                    }
                                }
                            }else{
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                }
                            }
                        }
                        $ender = $ender." ".$row["ENDERECO_COMP"];
                    }
                    ?>
                    <tr style="font-size: small">
                        <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                        <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["qt"]?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["Tab_Preco_5"], 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format(($row["qt"] * $row["Tab_Preco_5"]), 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$rst["PRECO_CUSTO"] > 0 ?  number_format(((($row["Tab_Preco_5"] / ($row["PRECO_CUSTO"])) -1 ) * 100), 2, ',' ,'.')."%" : "0"?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CONSUM_2"], 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_OFICINA"], 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["DataA"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["DataB"]?></td>
                    </tr>
                    <?php
                    $qtde = $qtde + $row["qt"];
                    $custo = $custo + ($row["qt"]*$row["PRECO_CUSTO"]);
                    $TT = $TT + ($row["qt"]*$row["Tab_Preco_5"]);
                }
                ?>
                </tbody>
                <tfoot>
                    <tr style="font-size: small">
                        <td class="text-right"  colspan="2"><strong>Total</strong></td>
                        <td class="text-center" style="vertical-align: middle"><?=$qtde?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"></td>
                                     <?php  } ?>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($custo, 2, ',', '.')?></td>
                        <td class="text-center" style="vertical-align: middle"></td>
                        <td class="text-center" style="vertical-align: middle"><?=number_format($TT, 2, ',', '.')?></td>
                        <td class="text-center" colspan="5" style="vertical-align: middle"></td>
                    </tr>
                </tfoot>
            </table>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 8) {
    try {
        if (empty($_parametros['relatorio-estoque'])) {
            $estoque = "";
        }
        else if ($_parametros['relatorio-estoque'] == 1) {
            $estoque = " AND Qtde_Disponivel > 0";
        }
        else {
            $estoque = " AND Qtde_Disponivel <= 0";
        }
   

        $data = date("Y-m-d", mktime(0, 0, 0, date("m"), (intval(date("d")) + $_parametros['relatorio-dias']), date("Y")));
        
        $consulta = $pdo->query("SELECT sum(Qtde_Disponivel) AS qtde,$_codviewer,DESCRICAO,item_lote_A,item_lote_B,item_validade_B,item_validade_A, DATE_FORMAT(item_validade_A,'%d/%m/%Y') AS validade, DATE_FORMAT(item_validade_B,'%d/%m/%Y') AS validadeB 
        $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque
        INNER JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = codigo_fornecedor WHERE item_validade_A <= '$data' AND item_validade_A <> '0000-00-00' $estoque OR
        item_validade_B <= '$data' AND item_validade_B <> '0000-00-00' $estoque   GROUP BY CODIGO_BARRA,DESCRICAO,item_lote_A,item_lote_B,item_validade_B,item_validade_A ORDER BY item_validade_A");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-exlg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 class="text-center">Relatório de Produtos - Por validade</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th class="text-center" style="vertical-align: middle">Código</th>
                                    <th class="text-center" style="vertical-align: middle">Descrição</th>
                                    <th class="text-center" style="vertical-align: middle">Quantidade</th>
                                    <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                                    <th class="text-center" style="vertical-align: middle">Lote A</th>
                                    <th class="text-center" style="vertical-align: middle">Validade A</th>
                                    <th class="text-center" style="vertical-align: middle">Lote B</th>
                                    <th class="text-center" style="vertical-align: middle">Validade B</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $ender = ""; 
                                if($_filtro_endereco == 1) { 
                                    if($row["ENDERECO1"] != ""){
                                        $ender = $row["ENDERECO1"];
                                        if(substr($row["ENDERECO1"],0,1) == "R"){
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"];
                                                if($row["ENDERECO3"] != ""){
                                                    $ender =   $ender."/".$row["ENDERECO3"];
                                                }
                                            }
                                        }else{
                                            if($row["ENDERECO2"] != ""){
                                                $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                            }
                                        }
                                    }
                                    $ender = $ender." ".$row["ENDERECO_COMP"];
                                }
                                ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["qt"]?></td>
                                    <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                    <td class="text-center" style="vertical-align: middle"><?=$row["item_lote_A"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["validade"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["item_lote_B"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["validadeB"]?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <h3 class="text-center">Relatório de Produtos - Por validade</h3>
            <table class="table table-bordered" width="90%">
                <thead>
                    <tr style="font-size: small">
                        <th class="text-center" style="vertical-align: middle">Código</th>
                        <th class="text-center" style="vertical-align: middle">Descrição</th>
                        <th class="text-center" style="vertical-align: middle">Quantidade</th>
                        <?php if($_filtro_endereco == '1') { ?>
                                        <th class="text-center" style="vertical-align: middle">Endereço</th>
                                    
                                    <?php } ?>
                        <th class="text-center" style="vertical-align: middle">Lote A</th>
                        <th class="text-center" style="vertical-align: middle">Validade A</th>
                        <th class="text-center" style="vertical-align: middle">Lote B</th>
                        <th class="text-center" style="vertical-align: middle">Validade B</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($retorno as $row) {
                    $ender = ""; 
                    if($_filtro_endereco == 1) { 
                        if($row["ENDERECO1"] != ""){
                            $ender = $row["ENDERECO1"];
                            if(substr($row["ENDERECO1"],0,1) == "R"){
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"];
                                    if($row["ENDERECO3"] != ""){
                                        $ender =   $ender."/".$row["ENDERECO3"];
                                    }
                                }
                            }else{
                                if($row["ENDERECO2"] != ""){
                                    $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                                }
                            }
                        }
                        $ender = $ender." ".$row["ENDERECO_COMP"];
                    }
                    ?>
                    <tr style="font-size: small">
                        <td class="text-center" style="vertical-align: middle"><?=$row["$_codviewer"];?></td>
                        <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["qtde"]?></td>
                        <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                        <td class="text-center" style="vertical-align: middle"><?=$row["item_lote_A"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["validade"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["item_lote_B"]?></td>
                        <td class="text-center" style="vertical-align: middle"><?=$row["validadeB"]?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        }
                } catch (PDOException $e) {
                    ?>
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                                <h2><?="Erro: " . $e->getMessage()?></h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
} else if ($_parametros["relatorio-tipo"] == 9) { //relatorio completo
          
    if (empty($_parametros['relatorio-estoque'])) {
        $estoque = "";
    }
    else if ($_parametros['relatorio-estoque'] == 1) {
        $estoque = " WHERE Qtde_Disponivel > 0";
    }
    else {
        $estoque = " WHERE Qtde_Disponivel = 0";
    }

    if ($_parametros['rel-almox'] > 0) {    
       
        if($estoque != ""){
            $estoque = $estoque." AND  Codigo_Almox = '".$_parametros['rel-almox']."'  ";
        }else{
            $estoque = $estoque." WHERE Codigo_Almox = '".$_parametros['rel-almox']."'";
        }
    }

    
    if ($_parametros['_enderA'] != '') {    
            $filtroEndereco = $filtroEndereco ."AND itemestoque.ENDERECO1 = '".$_parametros['_enderA']."'";       
    }
    if ($_parametros['_enderB'] != '') {    
        $filtroEndereco = $filtroEndereco ."AND itemestoque.ENDERECO2 = '".$_parametros['_enderB']."'";       
    }

    if ($_parametros['_enderC'] != '') {    
        $filtroEndereco = $filtroEndereco ."AND itemestoque.ENDERECO3 = '".$_parametros['_enderC']."'";       
    }




    $data = date("Y-m-d", mktime(0, 0, 0, date("m"), (intval(date("d")) + $_parametros['relatorio-dias']), date("Y")));
    $sq = "SELECT sum(Qtde_Disponivel) AS qtde,CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO,Qtde_Reserva_Tecnica,
     ".$_SESSION['BASE'].".itemestoque.ENDERECO1,".$_SESSION['BASE'].".itemestoque.ENDERECO2,".$_SESSION['BASE'].".itemestoque.ENDERECO3
     $_campos_endereco FROM ".$_SESSION['BASE'].".itemestoque
    INNER JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = codigo_fornecedor  $estoque $filtroEndereco GROUP BY CODIGO_FABRICANTE,DESCRICAO,Qtde_Reserva_Tecnica,
    ENDERECO1,ENDERECO2,ENDERECO3 ORDER BY DESCRICAO";

    $consulta = $pdo->query($sq);
    $retorno = $consulta->fetchAll();

    //ver csv
    if ($_parametros["relatorio-arquivo"] == 2){
        $nomearquivo = "Prisma_RelEnderecos";
        $dir = "docs/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "Código interno;Código Fabricante;Descrição;Quantidade;Qt.Reservado;Endereço A;Endereço B;Endereço C";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
            $codint = $row["CODIGO_FORNECEDOR"];
            $cod = $row["CODIGO_FABRICANTE"];
            $desc = $row["DESCRICAO"];
            $qtde =$row["qtde"];
            $reserva = $row["Qtde_Reserva_Tecnica"];
            $enderA =  $row["ENDERECO1"];
            $enderB = $row["ENDERECO2"];
            $enderC = $row["ENDERECO3"];
            $_itemlinha = "$codint;$cod;$desc;$qtde;$reserva; $enderA;$enderB;$enderC";
            fwrite($fp,$_itemlinha."\r\n");

        }
        fclose($fp);   
       
            $arquivo = $nomearquivo.'.csv';
        
            if( file_exists($arquivo_caminho)){ 
            ?>
             <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                            <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                               
                            </div>
                        </div>
                    </div>
        <?php
            }else{ ?>
 <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                                <h2>Sem registros nesse periodo</h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv

    }else{

  
     
 
        ?>
        <div class="modal-dialog modal-exlg">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h3 class="text-center">Relatório Geral - Por Endereço</h3>
                    <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                        <thead>
                            <tr style="font-size: small">
                                <th class="text-center" style="vertical-align: middle">Código Fabricante</th>
                                <th class="text-center" style="vertical-align: middle">Descrição</th>
                                <th class="text-center" style="vertical-align: middle">Quantidade</th>
                                <th class="text-center" style="vertical-align: middle">Qt.Reservado</th>
                                <th class="text-center" style="vertical-align: middle">Endereço A</th>
                                <th class="text-center" style="vertical-align: middle">Endereço B</th>
                                <th class="text-center" style="vertical-align: middle">Endereço C</th>                             
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($retorno as $row) {
                            ?>
                            <tr style="font-size: small">
                                <td class="text-center" style="vertical-align: middle"><?=$row["CODIGO_FABRICANTE"]?></td>
                                <td class="text-center" style="vertical-align: middle"><?=$row["DESCRICAO"]?></td>
                                <td class="text-center" style="vertical-align: middle"><?=$row["qtde"]?></td>
                                <?php if($_filtro_endereco == 1) {  ?>
                                        <td class="text-center" style="vertical-align: middle"><?=$ender;?></td>
                                     <?php  } ?>
									 
                                <td class="text-center" style="vertical-align: middle"><?=$row["Qtde_Reserva_Tecnica"]?></td>
                                <td class="text-center" style="vertical-align: middle"><?=$row["ENDERECO1"]?></td>
                                <td class="text-center" style="vertical-align: middle"><?=$row["ENDERECO2"]?></td>
                                <td class="text-center" style="vertical-align: middle"><?=$row["ENDERECO3"]?></td>
                             
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                </div>
            </div>
        </div>
        <?php
          }
} else if ($_parametros["relatorio-tipo"] == 10) { //relatorio completo




    $datainiP =$_parametros["relatorio-dataini"];
    $datafimP = $_parametros["relatorio-datafim"];
    $dias = diasDatas($datainiP,$datafimP);

    if($dias > 366) {        
        ?>
        <div class="modal-dialog">
                   <div class="modal-content">
                   <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                </div>
                       <div class="modal-body" id="imagem-carregando">
                            <strong>PERÍODO NÃO PODE SER SUPERIOR A 366 DIAS</strong>                          
                       </div>
                   </div>
               </div>
     <?php
     exit();
    }
   
    $_DATEANO = explode("-",$datainiP);
    $_DATEANOf = explode("-",$datafimP);

    $codigoElectroux = 1; //tabela marcas 
    
    //ver csv
    if ($_parametros["relatorio-tipodoc"] == 1){   //OS   
        $nomearquivo = "atendimento_agrupados_por_ano_e_mes_".$_DATEANO[0].$_DATEANO[1].$_DATEANO[2]."_".$_DATEANOf[0].$_DATEANOf[1].$_DATEANOf[2];
    }else{ //vendas
        $nomearquivo = "vendas_agrupados_por_ano_e_mes_".$_DATEANO[0].$_DATEANO[1].$_DATEANO[2]."_".$_DATEANOf[0].$_DATEANOf[1].$_DATEANOf[2]; 
    }
        $dir = "docs/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

     
        if ($_parametros["relatorio-tipodoc"] == 1){   //OS   
           

            $sq = "SELECT Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(Data_entrada, '%Y-%m') AS mes_ano,DATE_FORMAT(DATA_ULT_SAIDA, '%Y-%m') AS data_atualizacao 
            ,SUM(Qtde_peca) AS total_quantidade,  SUM(PRECO_CUSTO) AS total_valor 
            FROM   ".$_SESSION['BASE'].".chamadapeca
            INNER JOIN ".$_SESSION['BASE'].".itemestoque ON Codigo_Peca_OS = CODIGO_FORNECEDOR
            WHERE COD_FABRICANTE = '$codigoElectroux' and  Codigo_Referencia_Fornec <> '' AND Data_entrada BETWEEN '".$_parametros['relatorio-dataini']."' AND '".$_parametros['relatorio-datafim']."'
            GROUP BY Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(Data_entrada, '%Y-%m') ORDER BY  DATE_FORMAT(Data_entrada, '%Y-%m');";            
        
        }else{ //vendas
           

            $sq = "SELECT Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(DATA_COMPRA, '%Y-%m') AS mes_ano,DATE_FORMAT(DATA_ULT_SAIDA, '%Y-%m') AS data_atualizacao 
            ,SUM(QUANTIDADE) AS total_quantidade,  SUM(PRECO_CUSTO) AS total_valor 
            FROM   ".$_SESSION['BASE'].".saidaestoqueitem
            INNER JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_ITEM = CODIGO_FORNECEDOR
            WHERE COD_FABRICANTE = '$codigoElectroux' and  Codigo_Referencia_Fornec <> '' AND DATA_COMPRA BETWEEN '".$_parametros['relatorio-dataini']."' AND '".$_parametros['relatorio-datafim']."'
            GROUP BY Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(DATA_COMPRA, '%Y-%m') ORDER BY  DATE_FORMAT(DATA_COMPRA, '%Y-%m');";
            
        
        }

        $consulta = $pdo->query($sq);
        $retorno = $consulta->fetchAll();

   
       /*
        1	cod410	codigo do item	Texto		Sim	
        2	event_date	ano e mês evento	Data		Sim	yyyy-mm
        3	description	descricão do item	Inteiro		Sim	
        4	quantity	quantidade realizada no mês	Inteiro		Sim	
        5	group	product group gerencial	Texto		Sim	
        6	net sales	valor total	Decimal		Sim	
        7	product_line	product line	Texto		Sim	
        8	status           	cod 410 ativo ou inativo	Texto		Sim	active/inactive
        9	updated_at	última data de alteração do cod410	Data e Hora			
*/

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "cod410;event_date;description;quantity;group;net sales;product_line;status;updated_at";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
            $cod410= $row["Codigo_Referencia_Fornec"];
            $event_date= $row["mes_ano"];
            $description= $row["DESCRICAO"];
            $quantity= $row["total_quantidade"];
            $group= "";
            $netsales= 0;
            $product_line= "";
            $status= "";
            $updated_at= $row["data_atualizacao"];
            $_itemlinha = "$cod410;$event_date;$description;$quantity;$group;$netsales;$product_line;$status;$updated_at";
            fwrite($fp,$_itemlinha."\r\n");
        }

        fclose($fp);   
       
            $arquivo = $nomearquivo.'.csv';
        
            if( file_exists($arquivo_caminho)){ 
            ?>
             <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                            <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                               
                            </div>
                        </div>
                    </div>
        <?php
            }else{ ?>
 <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                                <h2>Sem registros nesse periodo</h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv
        
       
}
