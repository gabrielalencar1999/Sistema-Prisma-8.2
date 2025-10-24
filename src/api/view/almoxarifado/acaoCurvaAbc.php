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
 * Listar Produtos
 * */

 $_qtdemax = $_parametros["curva-qtdemax"];
 
 if($_qtdemax < 100 ) { $_qtdemax = 100;}

if ($acao["acao"] == 2) {

if($_parametros["curva-tipo"] == 1) {
    //vendas

    $empresa = $_parametros["curva-empresa"] == 0 ? "" : " AND saidaestoqueitem.num_livro = '".$_parametros["curva-empresa"]."'  ";
    $grupo = $_parametros["curva-grupo"] == 0 ? "" : " AND GRU_GRUPO = '".$_parametros["curva-grupo"]."'";
    $classificacao = $_parametros["curva-classificacao"] == 1 ? "qtde" : "totalped";

    $consultaTotal = $pdo->query("SELECT sum(VALOR_TOTAL) as total FROM ".$_SESSION['BASE'].".saidaestoqueitem LEFT JOIN ".$_SESSION['BASE'].".itemestoque on CODIGO_ITEM = CODIGO_FORNECEDOR
    WHERE DATA_COMPRA BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."' AND saidaestoqueitem_cancelado = '0' $empresa $grupo LIMIT 0,$_qtdemax");
    $retornoTotal = $consultaTotal->fetch();

    $totalgeral = $retornoTotal['total'];

    $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO, sum(QUANTIDADE) AS qtde, sum(VALOR_TOTAL) AS totalped
     FROM ".$_SESSION['BASE'].".saidaestoqueitem LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_ITEM = CODIGO_FORNECEDOR
    WHERE DATA_COMPRA BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."' AND saidaestoqueitem_cancelado = '0' $empresa $grupo
     GROUP BY CODIGO_FORNECEDOR,CODIGO_FABRICANTE, DESCRICAO ORDER BY $classificacao DESC LIMIT 0,$_qtdemax");
    $retorno = $consulta->fetchAll();

 
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th class="text-center">Descrição</th>
            <th class="text-center">Código</th>
            <th class="text-center">Quantidade</th>
            <th class="text-center" style="vertical-align: middle">Est.Atual</th>
            <th class="text-center">Valor Total</th>
            <th class="text-center">% Sobre Valor Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;

        foreach ($retorno as $row) { 								    
            $aux = $i % 2;
            $i++;

            if ($i < 6 ) {
                $cor = $aux == 0 ? "background-color: #FEEFD3 !important" : "background-color: #FDDA99";	
            } 
            else if ($i > 5 && $i < 11) {
                $cor = $aux == 0 ? "background-color: #F0F5FF !important" : "background-color: #CFE1FE";
            }
            else {
                $cor = $aux == 0 ? "background-color: #F2F2F2 !important" : "background-color: #FFFFFF";	 
            }
            $consultaProduto = $pdo->query("SELECT sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
            FROM ".$_SESSION['BASE'].".itemestoquealmox   
             LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox       
            WHERE almox_totaliza  = '1' AND Codigo_Item = '".$row["CODIGO_FORNECEDOR"]."' ");        
            $retornoProdutos = $consultaProduto->fetch();
            ?>
            <tr class="gradeX" style="<?=$cor?>">
                <td class="text-center"><?=$i." - ".(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
                <td class="text-center"><?=$row["qtde"]?></td>
                <td class="text-center"><?=$retornoProdutos['tot_item']?></td>
                <td class="text-center"><?=number_format($row["totalped"], 2, ',', '.')?></td>
                <td class="text-center"><?=number_format(($row["totalped"]*100/$totalgeral),2,',','.')."%"?></td>
            </tr>
            <?php
            $somaQtde = $somaQtde + $row["qtde"];
            $somaTotal = $somaTotal + $row["totalped"];
            $somaPorcento = $somaPorcento + ($row["totalped"]*100/$totalgeral);
        }
    }else{
        //os
        

     //$empresa = $_parametros["curva-empresa"] == 0 ? "" : " AND chamadapeca.num_livro = '".$_parametros["curva-empresa"]."'  ";
     $grupo = $_parametros["curva-grupo"] == 0 ? "" : " AND GRU_GRUPO = '".$_parametros["curva-grupo"]."'";
     $classificacao = $_parametros["curva-classificacao"] == 1 ? "qtde" : "totalped";
        $sql = "SELECT sum(Valor_Peca*Qtde_peca) as total FROM ".$_SESSION['BASE'].".chamadapeca LEFT JOIN ".$_SESSION['BASE'].".itemestoque on Codigo_Peca_OS = CODIGO_FORNECEDOR
        WHERE TIPO_LANCAMENTO = 0 and Data_baixa BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."'  $empresa $grupo LIMIT 0,$_qtdemax";
      
     $consultaTotal = $pdo->query("$sql");
     $retornoTotal = $consultaTotal->fetch();

     $totalgeral = $retornoTotal['total'];

     $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,DESCRICAO, sum(Qtde_peca) AS qtde, sum(Valor_Peca*Qtde_peca) AS totalped,CODIGO_FABRICANTE FROM ".$_SESSION['BASE'].".chamadapeca LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON Codigo_Peca_OS = CODIGO_FORNECEDOR
     WHERE Data_baixa BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."'  $empresa $grupo GROUP BY CODIGO_FORNECEDOR,CODIGO_FABRICANTE, DESCRICAO ORDER BY $classificacao DESC LIMIT 0,$_qtdemax");
     $retorno = $consulta->fetchAll();
   
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th class="text-center">Descrição</th>
            <th class="text-center">Código</th>
            <th class="text-center">Quantidade</th>
            <th class="text-center" style="vertical-align: middle">Est.Atual</th>
            <th class="text-center">Valor Total</th>
            <th class="text-center">% Sobre Valor Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;

        foreach ($retorno as $row) { 		
            $consultaProduto = $pdo->query("SELECT sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
            FROM ".$_SESSION['BASE'].".itemestoquealmox   
             LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox       
            WHERE almox_totaliza  = '1' AND Codigo_Item = '".$row["CODIGO_FORNECEDOR"]."' ");        
            $retornoProdutos = $consultaProduto->fetch();						    
            $aux = $i % 2;
            $i++;

            if ($i < 6 ) {
                $cor = $aux == 0 ? "background-color: #FEEFD3 !important" : "background-color: #FDDA99";	
            } 
            else if ($i > 5 && $i < 11) {
                $cor = $aux == 0 ? "background-color: #F0F5FF !important" : "background-color: #CFE1FE";
            }
            else {
                $cor = $aux == 0 ? "background-color: #F2F2F2 !important" : "background-color: #FFFFFF";	 
            }
            ?>
            <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["CODIGO_FABRICANTE"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["qtde"]?></td>
                                    <td class="text-center"><?=$retornoProdutos['tot_item']?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["totalped"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["totalped"]*100/$totalgeral),2,',','.')."%"?></td>
                                </tr>
            <?php
            $somaQtde = $somaQtde + $row["qtde"];
            $somaTotal = $somaTotal + $row["totalped"];
            $somaPorcento = $somaPorcento + ($row["totalped"]*100/$totalgeral);
        }
    
    }
        ?>
        </tbody>
        <tfoot>
            <tr class="alert alert-info">
                <td class="text-right" colspan="2"><strong>Total:</strong></td>
                <td class="text-center"><?=$somaQtde?></td>
                <td class="text-center"></td>
                <td class="text-center"><?=number_format($somaTotal, 2, ',', '.')?></td>
                <td class="text-center"><?=number_format($somaPorcento, 2, ',', '.')."%"?></td>
            </tr>
        </tfoot>
    </table>
    <?php
}
/**
 * Imprime Movimentos Filtro
 */
if ($acao["acao"] == 5) {

    if($_parametros["curva-tipo"] == 1) {
        //vendas
                    
                    try {
                        $empresa = $_parametros["curva-empresa"] == 0 ? "" : " AND saidaestoqueitem.num_livro = '".$_parametros["curva-empresa"]."'  ";
                        $grupo = $_parametros["curva-grupo"] == 0 ? "" : " AND GRU_GRUPO = '".$_parametros["curva-grupo"]."'";
                        $classificacao = $_parametros["curva-classificacao"] == 1 ? "qtde" : "totalped";

                        $consultaTotal = $pdo->query("SELECT sum(VALOR_TOTAL) as total FROM ".$_SESSION['BASE'].".saidaestoqueitem LEFT JOIN ".$_SESSION['BASE'].".itemestoque on CODIGO_ITEM = CODIGO_FORNECEDOR
                        WHERE DATA_COMPRA BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."' AND saidaestoqueitem_cancelado = '0' $empresa $grupo LIMIT 0,$_qtdemax");
                        $retornoTotal = $consultaTotal->fetch();

                        $totalgeral = $retornoTotal['total'];

                        $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,DESCRICAO, sum(QUANTIDADE) AS qtde, sum(VALOR_TOTAL) AS totalped,CODIGO_FABRICANTE FROM ".$_SESSION['BASE'].".saidaestoqueitem LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_ITEM = CODIGO_FORNECEDOR
                        WHERE DATA_COMPRA BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."' AND saidaestoqueitem_cancelado = '0' 
                        $empresa $grupo GROUP BY CODIGO_FORNECEDOR,CODIGO_FABRICANTE, DESCRICAO ORDER BY $classificacao DESC LIMIT 0,$_qtdemax");
                        $retorno = $consulta->fetchAll();

                   
                        ?>
                        <table class="table table-bordered" width="90%">
                            <thead>
                            <tr style="font-size: small">
                                <th class="text-center" style="vertical-align: middle">Descrição</th>
                                <th class="text-center" style="vertical-align: middle">Código</th>
                                <th class="text-center" style="vertical-align: middle">Qtde</th>
                                <th class="text-center" style="vertical-align: middle">Est.Atual</th>
                                <th class="text-center" style="vertical-align: middle">Valor Total</th>
                                <th class="text-center" style="vertical-align: middle">% Sobre Valor Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $consultaProduto = $pdo->query("SELECT sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
                                FROM ".$_SESSION['BASE'].".itemestoquealmox   
                                 LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox       
                                WHERE almox_totaliza  = '1' AND Codigo_Item = '".$row["CODIGO_FORNECEDOR"]."' ");        
                                $retornoProdutos = $consultaProduto->fetch();
                                ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["CODIGO_FABRICANTE"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["qtde"]?></td>
                                    <td class="text-center"><?=$retornoProdutos['tot_item']?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["totalped"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["totalped"]*100/$totalgeral),2,',','.')."%"?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right" colspan="2"><strong>Total:</strong></td>
                                    <td class="text-center"><?=$somaQtde?></td>
                                 
                                    <td class="text-center"><?=number_format($somaTotal, 2, ',', '.')?></td>
                                    <td class="text-center"><?=number_format($somaPorcento, 2, ',', '.')."%"?></td>
                                </tr>
                            </tfoot>
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
                }else{ //OS
                    try {
                        //$empresa = $_parametros["curva-empresa"] == 0 ? "" : " AND chamadapeca.num_livro = '".$_parametros["curva-empresa"]."'  ";
                        $grupo = $_parametros["curva-grupo"] == 0 ? "" : " AND GRU_GRUPO = '".$_parametros["curva-grupo"]."'";
                        $classificacao = $_parametros["curva-classificacao"] == 1 ? "qtde" : "totalped";

                        $consultaTotal = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as total FROM ".$_SESSION['BASE'].".chamadapeca LEFT JOIN ".$_SESSION['BASE'].".itemestoque on Codigo_Peca_OS = CODIGO_FORNECEDOR
                        WHERE TIPO_LANCAMENTO = 0 and Data_baixa BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."'  $empresa $grupo LIMIT 0,$_qtdemax");
                        $retornoTotal = $consultaTotal->fetch();

                        $totalgeral = $retornoTotal['total'];

                        $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO, sum(Qtde_peca) AS qtde, sum(Valor_Peca*Qtde_peca) AS totalped FROM ".$_SESSION['BASE'].".chamadapeca LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON Codigo_Peca_OS = CODIGO_FORNECEDOR
                        WHERE Data_baixa BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."'  $empresa $grupo GROUP BY CODIGO_FORNECEDOR,CODIGO_FABRICANTE, DESCRICAO ORDER BY $classificacao DESC LIMIT 0,$_qtdemax");
                        $retorno = $consulta->fetchAll();
                        ?>
                        <table class="table table-bordered" width="90%">
                            <thead>
                            <tr style="font-size: small">
                                <th class="text-center" style="vertical-align: middle">Descrição</th>
                                <th class="text-center" style="vertical-align: middle">Código</th>
                                <th class="text-center" style="vertical-align: middle">Qtde</th>
                                <th class="text-center" style="vertical-align: middle">Est.Atual</th>
                                <th class="text-center" style="vertical-align: middle">Valor Total</th>
                                <th class="text-center" style="vertical-align: middle">% Sobre Valor Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                $consultaProduto = $pdo->query("SELECT sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
                                FROM ".$_SESSION['BASE'].".itemestoquealmox   
                                 LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox       
                                WHERE almox_totaliza  = '1' AND Codigo_Item = '".$row["CODIGO_FORNECEDOR"]."' ");        
                                $retornoProdutos = $consultaProduto->fetch();
                                ?>
                                <tr style="font-size: small">
                                    <td class="text-center" style="vertical-align: middle"><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["CODIGO_FABRICANTE"]?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=$row["qtde"]?></td>
                                    <td class="text-center"><?=$retornoProdutos['tot_item']?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format($row["totalped"], 2, ',', '.')?></td>
                                    <td class="text-center" style="vertical-align: middle"><?=number_format(($row["totalped"]*100/$totalgeral),2,',','.')."%"?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right" colspan="2"><strong>Total:</strong></td>
                                    <td class="text-center"><?=$somaQtde?></td>
                                    <td class="text-center"><?=number_format($somaTotal, 2, ',', '.')?></td>
                                    <td class="text-center"><?=number_format($somaPorcento, 2, ',', '.')."%"?></td>
                                </tr>
                            </tfoot>
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
}


/**
 * CSV Movimentos Filtro
 */
if ($acao["acao"] == 6) {

    if($_parametros["curva-tipo"] == 1) {
        //vendas
                    
                    try {
                        $empresa = $_parametros["curva-empresa"] == 0 ? "" : " AND saidaestoqueitem.num_livro = '".$_parametros["curva-empresa"]."'  ";
                        $grupo = $_parametros["curva-grupo"] == 0 ? "" : " AND GRU_GRUPO = '".$_parametros["curva-grupo"]."'";
                        $classificacao = $_parametros["curva-classificacao"] == 1 ? "qtde" : "totalped";

                        $consultaTotal = $pdo->query("SELECT sum(VALOR_TOTAL) as total FROM ".$_SESSION['BASE'].".saidaestoqueitem LEFT JOIN ".$_SESSION['BASE'].".itemestoque on CODIGO_ITEM = CODIGO_FORNECEDOR
                        WHERE DATA_COMPRA BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."' AND saidaestoqueitem_cancelado = '0' $empresa $grupo LIMIT 0,$_qtdemax");
                        $retornoTotal = $consultaTotal->fetch();

                        $totalgeral = $retornoTotal['total'];

                        $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,DESCRICAO, sum(QUANTIDADE) AS qtde, sum(VALOR_TOTAL) AS totalped,CODIGO_FABRICANTE FROM ".$_SESSION['BASE'].".saidaestoqueitem LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_ITEM = CODIGO_FORNECEDOR
                        WHERE DATA_COMPRA BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."' AND saidaestoqueitem_cancelado = '0' 
                        $empresa $grupo GROUP BY CODIGO_FORNECEDOR,CODIGO_FABRICANTE, DESCRICAO ORDER BY $classificacao DESC LIMIT 0,$_qtdemax");
                        $retorno = $consulta->fetchAll();

                        //ver csv
                      
                            $nomearquivo = "Prisma_CurvaABC_Vendas";
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
                            $_itemlinha = "Código;Descrição;Quantidade;Est.Atual;Valor Total;% Sobre Valor Total";
                            fwrite($fp,$_itemlinha."\r\n");
                            foreach ($retorno as $row) {
                                $consultaProduto = $pdo->query("SELECT sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
                                FROM ".$_SESSION['BASE'].".itemestoquealmox   
                                 LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox       
                                WHERE almox_totaliza  = '1' AND Codigo_Item = '".$row["CODIGO_FORNECEDOR"]."' ");        
                                $retornoProdutos = $consultaProduto->fetch();
                           
                                   $cod = $row["CODIGO_FABRICANTE"];
                                   $desc = $row["DESCRICAO"];
                                   $qtde =$row["qtde"];
                                   $estoqueatual = $retornoProdutos["tot_item"];
                                   $total =  number_format($row["totalped"], 2, ',', '.');
                                   $porcentagem = number_format(($row["totalped"]*100/$totalgeral),2,',','.');
                                  
                                   $_itemlinha = "$cod;$desc;$qtde;$estoqueatual; $total;$porcentagem";
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
                        } catch (PDOException $e) {
                            ?>
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body" id="imagem-carregando">
                                        <h2>Ops !!! Algo deu errado, fale com suporte</h2>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                  
                    
                }else{ //OS
                    try {
                        //$empresa = $_parametros["curva-empresa"] == 0 ? "" : " AND chamadapeca.num_livro = '".$_parametros["curva-empresa"]."'  ";
                        $grupo = $_parametros["curva-grupo"] == 0 ? "" : " AND GRU_GRUPO = '".$_parametros["curva-grupo"]."'";
                        $classificacao = $_parametros["curva-classificacao"] == 1 ? "qtde" : "totalped";

                        $consultaTotal = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as total FROM ".$_SESSION['BASE'].".chamadapeca LEFT JOIN ".$_SESSION['BASE'].".itemestoque on Codigo_Peca_OS = CODIGO_FORNECEDOR
                        WHERE TIPO_LANCAMENTO = 0 and Data_baixa BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."'  $empresa $grupo LIMIT 0,$_qtdemax");
                        $retornoTotal = $consultaTotal->fetch();

                        $totalgeral = $retornoTotal['total'];

                        $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO, sum(Qtde_peca) AS qtde, sum(Valor_Peca*Qtde_peca) AS totalped FROM ".$_SESSION['BASE'].".chamadapeca LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON Codigo_Peca_OS = CODIGO_FORNECEDOR
                        WHERE Data_baixa BETWEEN '".$_parametros['curva-dataini']."' AND '".$_parametros['curva-datafim']."'  $empresa $grupo GROUP BY CODIGO_FORNECEDOR,CODIGO_FABRICANTE, DESCRICAO ORDER BY $classificacao DESC LIMIT 0,$_qtdemax");
                        $retorno = $consulta->fetchAll();
                       //ver csv
                      
                       $nomearquivo = "Prisma_CurvaABC_OS";
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
                       $_itemlinha = "Código;Descrição;Quantidade;Est.Atual;Valor Total;% Sobre Valor Total";
                       fwrite($fp,$_itemlinha."\r\n");
                       foreach ($retorno as $row) {
                           $consultaProduto = $pdo->query("SELECT sum(itemestoquealmox.Qtde_Disponivel) AS tot_item 
                           FROM ".$_SESSION['BASE'].".itemestoquealmox   
                            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox       
                           WHERE almox_totaliza  = '1' AND Codigo_Item = '".$row["CODIGO_FORNECEDOR"]."' ");        
                           $retornoProdutos = $consultaProduto->fetch();
                      
                              $cod = $row["CODIGO_FABRICANTE"];
                              $desc = $row["DESCRICAO"];
                              $qtde =$row["qtde"];
                              $estoqueatual = $retornoProdutos["tot_item"];
                              $total =  number_format($row["totalped"], 2, ',', '.');
                              $porcentagem = number_format(($row["totalped"]*100/$totalgeral),2,',','.');
                             
                              $_itemlinha = "$cod;$desc;$qtde;$estoqueatual; $total;$porcentagem";
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
                   } catch (PDOException $e) {
                       ?>
                       <div class="modal-dialog">
                           <div class="modal-content">
                               <div class="modal-body" id="imagem-carregando">
                                   <h2>Ops !!! Algo deu errado, fale com suporte</h2>
                               </div>
                           </div>
                       </div>
                       <?php
                   }
                }
}