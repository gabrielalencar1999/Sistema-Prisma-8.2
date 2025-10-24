<?php
include("../../api/config/iconexao.php");
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

$acao = $_POST["acao"];

if ($_POST["acao"] == 10) {
    
    try {

        if($_parametros['_dataIni'] == '') {
            $_parametros['_dataIni'] = date('Y-m-d');
            $_parametros['_dataFim'] = date('Y-m-d');
        } 
                        
          $sql = "SELECT B.NFE_FORNEC AS FORN,D.NOME as razao,A.NFE_NRO AS nf ,B.NFE_TOTALNF ,B.NFE_CHAVE,B.NFE_DATAENTR ,
        DATE_FORMAT(B.NFE_DATAENTR, '%d/%m/%Y') AS DT, SUM(A.NFE_QTDADE) as qt, A.NFE_VLRUNI  as vl,NFE_INFOADD
        FROM ". $_SESSION['BASE'] .".nota_ent_item AS A
        LEFT JOIN ". $_SESSION['BASE'] .".nota_ent_base AS B ON B.NFE_ID = A.NFE_IDBASE 
        LEFT JOIN ". $_SESSION['BASE'] .".fabricante AS D ON CODIGO_FABRICANTE = B.NFE_FORNEC 
        WHERE  B.NFE_DATAENTR >= '".$_parametros['_dataIni']." 00:00'  AND  B.NFE_DATAENTR  <= '".$_parametros['_dataFim']." 23:59:59'  AND
        A.NFE_CODIGO   = '".$_parametros['id-alteradv']."'  
        GROUP BY B.NFE_NRO ,B.NFE_TOTALNF ,B.NFE_CHAVE,B.NFE_DATAENTR 
        ORDER BY B.NFE_DATAENTR DESC";     
		$ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
		$TotalReg = mysqli_num_rows($ex);

		?>
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="text-center">Nº NF</th>
                    <th class="text-center" style="width:250px ;">Fornecedor</th>
                    <th class="text-left">Data Entrada </th>
					<th class="text-left">Qtde</th>
                    <th class="text-left">Valor </th>
					<th class="text-left">Total</th>
                    <th class="text-left">Observação</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($rtoslist = mysqli_fetch_array($ex)) {
					$i++;
				?>
					<tr class="gradeX">
						<td class="text-center"> <a href="javascript:void(0);" class="on-default edit-row" onclick="_alterarNF('<?=$rtoslist["nf"]; ?>',<?= $rtoslist["FORN"]; ?>)"><?= $rtoslist["nf"]; ?></td>
                        <td class="text-center"><?= $rtoslist["razao"]; ?></td>
                        <td class="text-left" ><?= $rtoslist['DT']; ?></td>
                        <td class="text-center" ><?= $rtoslist["qt"]; ?></td>
						<td class="text-center" ><?=  number_format($rtoslist["vl"], 2, ',', '.') ?></td>
                      	<td class="text-left"><?= number_format($rtoslist["qt"]*$rtoslist["vl"], 2, ',', '.'); ?></td>						
                          <td style="width:200px ;"><?=$rtoslist["NFE_INFOADD"]?></td>
					</tr>
				<?php }
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
    exit();
}
/*
 * Chama modal altera fornecedor
 * */
if ($acao == 0) {
    try {
        $consultaProduto = $pdo->query("SELECT itemf_id,codigo_fabricante, codigo_item, Codigo_fornecedor, valor_ult_compra, data_ult_compra, valor_ult_cotacao, data_ult_cotacao
         FROM ".$_SESSION['BASE'].".itemestoquefornecedor WHERE itemf_id = '".$_parametros['id-altera']."'");
        $retornoProduto = $consultaProduto->fetch();
        ?>
        <div class="modal-dialog modal-lg text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Fornecedor</h4>
                </div>
                <div class="modal-body" id="imagem-carregando">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera-fornecedor" id="form-altera-fornecedor">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="produto-fornecedor">Fornecedor:</label>
                                <input type="hidden" id="produto-id" name="produto-id" value="<?=$retornoProduto["codigo_item"]?>">
                                <input type="hidden" id="produto-idfabric" name="produto-idfabric" value="<?=$retornoProduto["itemf_id"]?>">
                                <select name="produto-fornecedor" id="produto-fornecedor" class="form-control" disabled>
                                    <option value="0">Selecione</option>
                                    <?php
                                    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante ORDER BY NOME");
                                    $retorno = $consulta->fetchAll();
                                    foreach ($retorno as $row) {
                                        ?><option value="<?=$row["CODIGO_FABRICANTE"]?>" <?=$row["CODIGO_FABRICANTE"] == $retornoProduto["codigo_fabricante"] ? "selected" : ""?>><?=$row["NOME"]?></option><?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="produto-codfornec">Cód. Fornecedor:</label>
                                <input type="text" class="form-control" id="produto-codfornec" name="produto-codfornec" value="<?=$retornoProduto["Codigo_fornecedor"]?>" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="produto-vrlcotacao">Valor Ult. Cotação:</label>
                                <input type="text" class="form-control" id="produto-vrlcotacao" name="produto-vrlcotacao" value="<?=$retornoProduto["valor_ult_cotacao"]?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="produto-datacotacao">Data Ult. Cotação:</label>
                                <input type="date" class="form-control" name="produto-datacotacao" id="produto-datacotacao" value="<?=$retornoProduto["data_ult_cotacao"]?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label >Excluir</label>
                                <select name="prodexcluir" id="prodexcluir" class="form-control" >
                                  <option value="0">Não</option>
                                  <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_alteraFornecedor()">Salvar</button>
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
/*
 * Cadastra Fornecedor
 * */
else if ($acao == 1) {
    if (empty($_parametros["produto-codigo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                            <h2>Preencha o código do produto!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else if ($_parametros["produto-fornecedor"] == 0 || empty($_parametros["produto-codfornec"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php
                        if ($_parametros["produto-fornecedor"]  == 0) {
                            ?><h2>Informe o fornecedor!</h2><?php
                        }
                        else if (empty($_parametros["produto-codfornec"])) {
                            ?><h2>Informe o código do fornecedor!</h2><?php
                        }
                        else {
                            ?><h2>Informe o código e o fornecedor!</h2><?php
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
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".itemestoquefornecedor (codigo_fabricante, codigo_item, Codigo_fornecedor) VALUES(?, ?, ?)");
            $statement->bindParam(1, $_parametros["produto-fornecedor"]);
            $statement->bindParam(2, $_parametros["produto-codigo"]);
            $statement->bindParam(3, $_parametros["produto-codfornec"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                           
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Incluído!</h2>
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
/*
 * Cadastra Ingrediente
 * */
if ($acao == 5) {
    if (empty($_parametros["produto-codigo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                            <h2>Preencha o código do produto!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else if ($_parametros["ingrdiente-id"] == 0 || empty($_parametros["ingrediente-qtn"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php
                        if ($_parametros["ingrdiente-id"]  == 0) {
                            ?><h2>Informe o ingrediente!</h2><?php
                        }
                        else if (empty($_parametros["ingrediente-qtn"])) {
                            ?><h2>Informe a quantidade!</h2><?php
                        }
                        else {
                            ?><h2>Informe o ingrediente e quantidade!</h2><?php
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
            $_parametros["ingrediente-qtn"] = LimpaVariavel($_parametros["ingrediente-qtn"]);
            $consultaIngrediente = $pdo->query("SELECT PRECO_CUSTO FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FORNECEDOR = '".$_parametros["ingrdiente-tipo"]."'");
            $retornoIngrediente = $consultaIngrediente->fetch();
            $valorIngrediente = floatval($retornoIngrediente["ingrediente-qtn"]) * floatval($_parametros["ingrediente-qtn"]);
            $valorIngrediente = number_format($valorIngrediente, 2, '.', '');
            
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".itemestoque_receita (codigo_receita, codigo_ingrediente, qtde, valor_prod) VALUES(?, ?, ?, ?)");
            $statement->bindParam(1, $_parametros["produto-codigo"]);
            $statement->bindParam(2, $_parametros["ingrdiente-id"]);
            $statement->bindParam(3, $_parametros["ingrediente-qtn"]);
            $statement->bindParam(4, $valorIngrediente);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Ingrediente Cadastrado!</h2>
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
/**
 * Lista Fornecedores
 */
else if ($acao == 2) {
    $consultaProduto = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".itemestoque WHERE Codigo = '".$_parametros['id-produto']."'");
    $retornoProduto = $consultaProduto->fetch();
    $consultaFornecedor= $pdo->query("SELECT itemf_id, NOME, Codigo_fornecedor, valor_ult_compra, data_ult_compra, valor_ult_cotacao, data_ult_cotacao,itemestoquefornecedor.codigo_item as CODIGOFORNECEDOR,itemestoquefornecedor.codigo_fabricante FROM ".$_SESSION['BASE'].".itemestoquefornecedor LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoquefornecedor.codigo_fabricante 
    WHERE codigo_item = '".$retornoProduto['CODIGO_FORNECEDOR']."' and itemest_excluido = '0' ");
    $retornoFornecedor = $consultaFornecedor->fetchAll();
    ?>
    <table id="datatable-responsive-fornecedor" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Fornecedor</th>
            <th>Cód. Fornecedor</th>
            <th class="text-center">Valor Ult. Compra</th>
            <th class="text-center">Data Ult. Compra</th>
            <th class="text-center">Valor Ult. Cotação</th>
            <th class="text-center">Data Ult. Cotação</th>
            <th class='text-center'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retornoFornecedor as $row) {
            ?>
            <tr class="gradeX">
                <td><?=(strlen($row["NOME"]) > 39 ? substr($row["NOME"],0,37)."..." : $row["NOME"])?></td>
                <td><?=$row["Codigo_fornecedor"]?></td>
                <td class="text-center"><?=number_format($row["valor_ult_compra"], 2, ',', '.')?></td>
                <td class="text-center"><?=$row["data_ult_compra"] == "0000-00-00" ? "-" : date("d/m/Y", strtotime($row["data_ult_compra"]))?></td>
                <td class="text-center"><?=number_format($row["valor_ult_cotacao"], 2, ',', '.')?></td>
                <td class="text-center"><?=$row["data_ult_cotacao"] == "0000-00-00" ? "-" : date("d/m/Y", strtotime($row["data_ult_cotacao"]))?></td>
                <td class="actions text-center">
                    <a href="#" class="on-default edit-row" onclick="_buscadadosnf('<?=$row['CODIGOFORNECEDOR']?>','<?=$row['codigo_fabricante']?>')"><i class="fa  fa-files-o fa-lg"></i></a>
                    <a href="#" class="on-default edit-row" onclick="_buscadados(<?=$row['itemf_id']?>)"><i class="fa fa-pencil fa-lg"></i></a>
                  <!--  <a href="javascript:void(0);" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row['itemf_id']?>)"><i class="fa fa-trash-o fa-lg"></i></a>-->
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
    exit();
}
else if ($acao == 22) {
    $consultaProduto = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".itemestoque WHERE Codigo = '".$_parametros['id-produto']."'");
    $retornoProduto = $consultaProduto->fetch();
    
              $consultaProduto = $pdo->query("
            SELECT * 
            FROM ".$_SESSION['BASE'].".itemestoque_preco LEFT JOIN bd_prisma.tipo_altitem ON tpalt_id = ipc_tipo
            WHERE ipc_codigoitem =  '".$retornoProduto['CODIGO_FORNECEDOR']."' ORDER BY ipc_datahora  desc
            ");
            $retornoProduto = $consultaProduto->fetchAll();
            ?>
<div style="max-height: 300px; overflow-y: auto;">
            <table id="datatable-responsive-fornecedor" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
              
                <th>Tipo</th>
                <th>Data/Hora</th>                      
                <th>Usuário</th>
                <th>Custo Atual</th>
                <th>Custo Anterior</th>
                <th>Venda Atual</th>
                <th>Venda Anterior</th>
              
                <th>vlr Tab1</th>
                <th>vlr Ant Tab 1</th>
                <th>vlr Tab 2</th>
                <th>vlr Ant Tab 2</th>
                <th>vlr Tab 3</th>
                <th>vlr Ant Tab 3</th>
                <th>vlr Tab 4</th>
                <th>vlr Ant Tab 4</th>
             
            </tr>
            </thead>
            <tbody>
            <?php foreach ($retornoProduto as $row): ?>
                <tr>
                  
                    <td class="text-center"><?= $row["tpalt_desc"] ?></td>
                    <td class="text-center"><?= date("d/m/Y H:i:s", strtotime($row["ipc_datahora"])) ?></td>
                   
                 
                    <td class="text-center"><?= $row["ipc_loginuser"] ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoatual"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoanterior"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrvendaatual"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrvendaanterior"], 2, ',', '.') ?></td>
                  
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoTab1"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoAnTab1"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoTab2"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoAnTab2"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoTab3"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoAnTab3"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoTab4"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["ipc_vlrcustoAnTab4"], 2, ',', '.') ?></td>
                   
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
</div>
<?php 
    exit();
}
/**
 * Lista Ingredientes
 */
else if ($acao == 6) {
    $consultaProduto = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".itemestoque WHERE Codigo = '".$_parametros['id-produto']."'");
    $retornoProduto = $consultaProduto->fetch();
    $consultaIngrediente= $pdo->query("SELECT DESCRICAO, qtde, valor_prod, codigo_ingrediente, codigo_receita FROM ".$_SESSION['BASE'].".itemestoque_receita LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON codigo_ingrediente = CODIGO_FORNECEDOR WHERE codigo_receita = '".$retornoProduto['CODIGO_FORNECEDOR']."'");
    $retornoIngrediente = $consultaIngrediente->fetchAll();
    ?>
    <table id="datatable-responsive-ingrediente" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th class="text-center">Valor</th>
            <th class='text-right'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retornoIngrediente as $row) {
            ?>
            <tr class="gradeX">
                <td><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                <td><?=number_format($row["qtde"], 3, ',', '.')?></td>
                <td class="text-center"><?=number_format($row["valor_prod"], 2, '.', '.')?></td>
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row['codigo_receita']?>, <?=$row['codigo_ingrediente']?>)"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}

else if ($acao == 7) {
    $_p =  $_parametros["_p"]; 
    $EA =  $_parametros["_enderA"]; 
    $consulta_produto = "Select `le_enderB`
    from " . $_SESSION['BASE'] . ".localestoque 
    WHERE le_enderA = '$EA' and le_enderB <> ''  group by le_enderB ORDER by `le_id` ASC";

    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
    $_reg = mysqli_num_rows($resultado);
    if($_reg > 0) {
?>
    <select name="_enderB" id="_enderB" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderC('<?=$_p;?>')">
        <option value="">-</option>
        <?php
     
        while ($rst = mysqli_fetch_array($resultado)) {
        ?>
            <option value="<?= $rst['le_enderB']; ?>"><?= $rst['le_enderB']; ?></option>
        <?php

        }
        ?>
    </select>
<?php
    }
}
/**
 * Busca NCM
 */
else if ($_POST["acao"] == 88) {
 
     $consulta = $pdo->query("SELECT descricao FROM minhaos_cep.impostost WHERE codigoncm = '".$_parametros['id-ncm']."'");
     $retorno = $consulta->fetch();
 
     if ($retorno == null) {
         echo "NCM não encontrado na base";
     }
     else {
         echo ($retorno["descricao"]);
     }
 }

else if ($acao == 8) {
   
    $EA =  $_parametros["_enderA"];
    $EB = $_parametros["_enderB"];
    
     $consulta_produto = "Select  `le_enderC`
                          from " . $_SESSION['BASE'] . ".localestoque 
                          WHERE le_enderA = '$EA'  AND le_enderB = '$EB' and le_enderC <> ''
                          group by le_enderC ORDER BY le_id ASC";
        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
        $_reg = mysqli_num_rows($resultado);
        if($_reg > 0) {
?>
    <select name="_enderC" id="_enderC" class="form-control input-sm" style="height:40px;font-size:large"> 
        <option value="">-</option>
        <?php
       
        while ($rst = mysqli_fetch_array($resultado)) {
        ?>
            <option value="<?= $rst['le_enderC']; ?>"><?= $rst['le_enderC']; ?></option>
        <?php

        }
        ?>
    </select>
    
    <?php
    }
}
/*
 * Atualiza Fornecedor
 * */
else if ($acao == 3) {
    $_parametros["produto-vrlcotacao"] = LimpaVariavel($_parametros["produto-vrlcotacao"]);
    try {
     
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".itemestoquefornecedor SET valor_ult_cotacao = ?, data_ult_cotacao = ?, 	itemest_excluido = ?
        WHERE itemf_id = ? ");
        $statement->bindParam(1, $_parametros["produto-vrlcotacao"]);
        $statement->bindParam(2, $_parametros["produto-datacotacao"]);
        $statement->bindParam(3, $_parametros["prodexcluir"]);
        $statement->bindParam(4, $_parametros["produto-idfabric"]);             
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                       
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h3>Produto Fornecedor Atualizado!</h3>
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
/**
 * Excluir Fornecedor
 */
else if ($acao == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".itemestoquefornecedor WHERE itemf_id = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Produto Excluído!</h2>
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
/**
 * Excluir Ingrediente
 */
else if ($acao == 7) {
    $_parametros["id-exclusao"] = explode("-",$_parametros["id-exclusao"]);
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".itemestoque_receita WHERE codigo_receita = :id_receita AND codigo_ingrediente = :id_ingrediente");
        $statement->bindParam(':id_receita', $_parametros["id-exclusao"][0]);
        $statement->bindParam(':id_ingrediente', $_parametros["id-exclusao"][1]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Ingrediente Excluído!</h2>
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
 else if ($acao == 9) {
    try {
      

        ?>
        <div class="modal-dialog modal-lg text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Notas Entrada Fornecedor</h4>
                </div>
                <div class="modal-body" id="imagem-carregando">
              
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera-fornecedor" id="form-altera-fornecedor">
                    <input type="hidden" id="id-alteradv" name="id-alteradv" value="<?=$_parametros['id-altera'];?>">
                    <div class="row">
                             
                                <div class="col-md-3">
                                <label for="field-1" class="control-label">Data de</label>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="_dataIni" id="_dataIni" value="<?= $data_ini; ?>">
                                    </div>
                                </div>
                           
                                <div class="col-md-3">
                                <label for="field-1" class="control-label">até </label>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="_dataFim" id="_dataFim" value="<?= $data_fim; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-top: 25px;">
                                <button type="button" id="_btfiltro" class="btn btn-info waves-effect waves-light" onclick="_buscadadosnfDv()">Filtrar</button>
                                </div>
                            </div>
                    
                    <div class="row">
                            <div class="form-group col-md-12" id="dvRetNF">
                            <?php                      
  

        $sql = "SELECT B.NFE_FORNEC AS FORN,D.NOME as razao,A.NFE_NRO AS nf ,B.NFE_TOTALNF ,B.NFE_CHAVE,B.NFE_DATAENTR ,
        DATE_FORMAT(B.NFE_DATAENTR, '%d/%m/%Y') AS DT, SUM(A.NFE_QTDADE) as qt, A.NFE_VLRUNI  as vl,NFE_INFOADD,
        A.NFE_CFOP AS CFOP
        FROM ". $_SESSION['BASE'] .".nota_ent_item AS A
        LEFT JOIN ". $_SESSION['BASE'] .".nota_ent_base AS B ON B.NFE_ID = A.NFE_IDBASE 
        LEFT JOIN ". $_SESSION['BASE'] .".fabricante AS D ON CODIGO_FABRICANTE = B.NFE_FORNEC 
        WHERE  A.NFE_CODIGO   = '".$_parametros['id-altera']."'  
        GROUP BY B.NFE_NRO ,B.NFE_TOTALNF ,B.NFE_CHAVE,B.NFE_DATAENTR 
        ORDER BY B.NFE_DATAENTR DESC";
     
		$ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
		$TotalReg = mysqli_num_rows($ex);

		?>
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="text-center">Nº NF</th>
                    <th class="text-center">Fornecedor</th>
                    <th class="text-left">Data Entrada </th>
                    <th class="text-left">CFOP</th>                   
					<th class="text-left">Qtde</th>
                    <th class="text-left">Valor</th>
					<th class="text-left">Total</th>
                    <th class="text-left" style="width:250px ;">Observação</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($rtoslist = mysqli_fetch_array($ex)) {
					$i++;
				?>
					<tr class="gradeX">
						<td class="text-center"> <a href="javascript:void(0);" class="on-default edit-row" onclick="_alterarNF('<?=$rtoslist["nf"]; ?>',<?= $rtoslist["FORN"]; ?>)"><?= $rtoslist["nf"]; ?></td>
                        <td class="text-center"><?= $rtoslist["razao"]; ?></td>
                        <td class="text-left" ><?= $rtoslist['DT']; ?></td>
                        <td class="text-center" ><?= $rtoslist["CFOP"]; ?></td>
                        <td class="text-center" ><?= $rtoslist["qt"]; ?></td>                       
						<td class="text-center" ><?=  number_format($rtoslist["vl"], 2, ',', '.') ?></td>
                      	<td class="text-left"><?= number_format($rtoslist["qt"]*$rtoslist["vl"], 2, ',', '.'); ?></td>						
                         <td ><?=$rtoslist["NFE_INFOADD"]?></td>
					</tr>
				<?php }
				?>
			</tbody>
		</table>
                            </div>                     
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                 
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