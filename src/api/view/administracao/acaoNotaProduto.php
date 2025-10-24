<?php include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();
use Functions\Estoque;
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

function ajustaData($data) {
    if (!empty($data)) {
        $data = trim($data);
        $data = str_replace("/", "-", $data);
        $data = date('Y-m-d', strtotime($data));
    }
    return $data;
}

$loginuser = $_SESSION['tecnico'];
$loginNOME = $_SESSION['APELIDO'];

$query = $pdo->query("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC,UF  
FROM  " . $_SESSION['BASE'] . ".parametro  ");
$retornoItem = $query->fetch();

$_vizCodInterno = $retornoItem['empresa_vizCodInt'];
$_UFEMPRESA = $retornoItem['UF'];



if ($acao["acao"] == 0) {

    $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".parametro");
    $parametro = $consulta->fetch(\PDO::FETCH_OBJ);
    
    try {
        $contultaNF = $pdo->query("SELECT NFE_TOTALFRETE FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
        $retornoNF = $contultaNF->fetch();

        $consultaItemTotal = $pdo->query("SELECT NFE_TOTALITEM, sum(NFE_TOTALITEM) AS TOTAL FROM ".$_SESSION['BASE'].".nota_ent_item WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
        $retornoItemTotal = $consultaItemTotal->fetch();

        $consultaItem = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".nota_ent_item WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."' AND NFE_CODIGO = '".$_parametros["id-filtro"]."'");
        $retornoItem = $consultaItem->fetch();

        $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FORNECEDOR = '".$_parametros["id-filtro"]."'");
        $result = $consulta->fetch();
        //buscar preço sugerido
        $consulta = $pdo->query("SELECT PRECO FROM ". $_SESSION['BASE'] .".sugeridoselx WHERE COD_ITEM_ESTOQUE = '".$result["CODIGO_FABRICANTE"]."'");
        $resultSugerido = $consulta->fetch();

        $valorFrete = $retornoNF["NFE_TOTALFRETE"];
        $totalNF = $retornoItemTotal["TOTAL"];
        $porcentagemFrete = ($retornoItem["NFE_TOTALITEM"] * 100) / $totalNF;
        $freteItem = (($porcentagemFrete / 100) * $valorFrete) / $retornoItem["NFE_QTDADE"];
        $ativa_salvar = 1;

        //VERIFICAR TABELA CFOP VENDA PARA ATUALIZAR CUSTO NA COMPRA
        $sql_cfop = "SELECT cfopvenda FROM bd_prisma.cfop_venda WHERE cfopvenda = '".$retornoItem["NFE_CFOP"]."'";
        $stmCfop = $pdo->prepare(" $sql_cfop ");            
        $stmCfop->execute();
        if ($stmCfop->rowCount() > 0 ){        
        //if($retornoItem["NFE_CFOP"] == "5102" OR  $retornoItem["NFE_CFOP"] == "6102" OR  $retornoItem["NFE_CFOP"] == "3102"  OR  $retornoItem["NFE_CFOP"] == "5405"   OR  $retornoItem["NFE_CFOP"] == "6405"  OR  $retornoItem["NFE_CFOP"] == "6101"  OR  $retornoItem["NFE_CFOP"] == "6403" OR $retornoItem["NFE_CFOP"] == "5403"   OR $retornoItem["NFE_CFOP"] == "6401"   OR $retornoItem["NFE_CFOP"] == "6105"   OR $retornoItem["NFE_CFOP"] == "5105" ) { 
            $precoCusto = $freteItem + $retornoItem["NFE_VLRUNI"];            
            $ativa_salvar = 1;           
        }else{
            $precoCusto = $result["PRECO_CUSTO"];
            $ativa_salvar = 0;
        }
       
        if($resultSugerido["PRECO"] > 0 and $result["Perc_Tab_preco5"] <= 0) {
            $precoVenda = $resultSugerido["PRECO"] + ($resultSugerido["PRECO"] * 0.20); 
            $porcsugerido = 20;
        }else{
            $precoVenda = $result["Perc_Tab_preco5"] > 0 ? $precoCusto + ($precoCusto * ($result["Perc_Tab_preco5"] / 100)) : $precoVenda = $result["Tab_Preco_5"];
            $porcsugerido = $result["Perc_Tab_preco5"];
        }
      
        $precoTab1 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco1"] / 100));
        $precoTab2 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco2"] / 100));
        $precoTab3 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco3"] / 100));
        $precoTab4 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco4"] / 100));

        $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".nota_ent_item SET nfitemstatus  = '1' WHERE NFE_CHAVE = ? and nfitemstatus is null");
        $statement->bindParam(1, $_parametros["id-chave"]);
        $statement->execute();

    
        ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" data-toggle="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Produto - Nota Entrada</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                    <div class="row">
                        <div class="form-group col-xs-3">
                            <label for="produto-codigo">Cód. Produto:</label>
                            <input type="text" class="form-control" id="produto-codigo" value="<?=$result["CODIGO_FORNECEDOR"]?>" disabled>
                            <input type="hidden" name="produto-codigo" value="<?=$result["CODIGO_FORNECEDOR"]?>">
                            <input type="hidden" name="produto-chave" value="<?=$_parametros["id-chave"]?>">
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="produto-codbarra">Cód. Barras:</label>
                            <input type="text" class="form-control" id="produto-codbarra" name="produto-codbarra" value="<?=$result["Codigo_Barra"]?>" disabled>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="produto-codfabricante">Cód. Fabricante:</label>
                            <input type="text" class="form-control" id="produto-codfabricante" name="produto-codfabricante" value="<?=$result["CODIGO_FABRICANTE"]?>" >
                        </div>
                        <div class="form-group col-xs-2">
                            <label for="produto-codfabricante">Cód. Similar:</label>
                            <input type="text" class="form-control" id="produto-codsimilar" name="produto-codsimilar" value="<?=$result["CODIGO_SIMILAR"]?>"  >
                        </div>
                        <div class="form-group col-xs-1">
                            <label for="produto-codfabricante" style="font-size:9px">Trocar Similar</label>
                            <?php if($result["CODIGO_SIMILAR"] != "") { ?>
                                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_trocaProduto('<?=$_parametros["id-chave"]?>','<?=$result["CODIGO_SIMILAR"]?>')"><i class="fa  fa-random"></i></button>
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="produto-descricao">Descrição:</label>
                            <input type="text" class="form-control" id="produto-descricao" name="produto-descricao" value="<?=$result["DESCRICAO"]?>">
                        </div>
                        <div class="form-group col-xs-6">
                            <label for="produto-resumo">Desc. Resumida:</label>
                            <input type="text" class="form-control" id="produto-resumo" name="produto-resumo" value="<?=$result["Descricao_Reduzida"]?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-3">
                            <label for="produto-grupo">Grupo:</label>
                            <?php
                            $consultaGrupo = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".grupo ORDER BY GRU_DESC");
                            $retornoGrupo = $consultaGrupo->fetchAll();
                            ?>
                            <select name="produto-grupo" id="produto-grupo" class="form-control">
                                <option value="">Selecione</option>
                            <?php
                            foreach ($retornoGrupo as $row) {
                                ?>
                                <option value="<?=$row["GRU_GRUPO"]?>" <?=$row["GRU_GRUPO"] == $result["GRU_GRUPO"] ? "selected" : "" ?>><?=$row["GRU_DESC"]?></option>
                                <?php
                            }
                            ?>
                            </select>
                        </div>
                        <div class="form-group col-xs-3">
                            <label for="produto-linha">Linha:</label>
                            <?php
                            $consultaLinha = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".linha ORDER BY linha_descricao");
                            $retornoLinha = $consultaLinha->fetchAll();
                            ?>
                            <select name="produto-linha" id="produto-linha" class="form-control">
                                <option value="">Selecione</option>
                                <?php
                                foreach ($retornoLinha as $row) {
                                    ?>
                                    <option value="<?=$row["linha_codigo"]?>" <?=$row["linha_codigo"] == $result["CODIGO_LINHA"] ? "selected" : "" ?>><?=$row["linha_descricao"]?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                       
                        <div class="form-group col-xs-1">
                            <label for="produto-lotea">Lote A:</label>
                            <input type="text" class="form-control" id="produto-lotea" name="produto-lotea" value="<?=$result["item_lote_A"]?>">
                        </div>
                        <div class="form-group col-xs-1">
                            <label for="produto-loteb">Lote B:</label>
                            <input type="text" class="form-control" id="produto-loteb" name="produto-loteb" value="<?=$result["item_lote_B"]?>">
                        </div>
                    
                 
                        <div class="form-group col-xs-2">
                            <label for="produto-venda-a">Venda A:</label>
                            <input type="date" class="form-control" id="produto-venda-a" name="produto-venda-a" value="<?=$result["item_validade_A"]?>">
                        </div>
                        <div class="form-group col-xs-2">
                            <label for="produto-venda-b">Venda B:</label>
                            <input type="date" class="form-control" id="produto-venda-b" name="produto-venda-b" value="<?=$result["item_validade_B"]?>">
                        </div>
                    
                    </div>

                     <div class="row">
                          <div class="form-group col-xs-5">
                                            <div class="row">
                                            <div class="form-group col-xs-4">
                                                <label for="produto-custo">Preço de Custo:</label>
                                                <input type="text" class="form-control" id="produto-custo" name="produto-custo" value="<?=number_format($precoCusto,2,',','.')?>">
                                            </div>
                                            <div class="form-group col-xs-4">
                                                <label for="produto-custoant">Custo Atual:</label>
                                                <input type="text" class="form-control" id="produto-custoantD" name="produto-custoantD" value="<?=number_format($result["PRECO_CUSTO"],2,',','.')?>" disabled>
                                                <input type="hidden" class="form-control" id="produto-custoant" name="produto-custoant" value="<?=number_format($result["PRECO_CUSTO"],2,',','.')?>" >
                                            </div>
                                            <div class="form-group col-xs-4">
                                                <label for="produto-custoant">Custo Médio:</label>
                                                <input type="text" class="form-control" id="produto-customedio" name="produto-customedio" value="<?=number_format($result["customedio"],2,',','.')?>" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-5">
                                                <label for="produto-venda">Preço de Venda:</label>
                                                <input type="text" class="form-control" id="produto-venda" name="produto-venda" value="<?=number_format($precoVenda, 2, ',', '.')?>">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label for="produto-vendapor">%</label>
                                                <input type="text" class="form-control" id="produto-vendapor" name="produto-vendapor" value="<?=$porcsugerido;?>" onkeyup="recalculaProduto('#produto-venda', this.value, <?=$precoCusto?>)">
                                            </div>
                                            <div class="form-group col-xs-5">
                                                <label for="produto-vlanterior">PV. Anterior:</label>
                                                <input type="text" class="form-control" id="produto-vlanteriorD" name="produto-vlanteriorD" value="<?=number_format($result["Tab_Preco_5"], 2, ',', '.')?>" disabled>
                                                <input type="hidden" class="form-control" id="produto-vlanterior" name="produto-vlanterior" value="<?=number_format($result["Tab_Preco_5"], 2, ',', '.')?>">
                                            </div>
                                        </div>
                                        <?php if($parametro->visualiza_tab1 == '-1' ){ ?>
                                        <div class="row">
                                            <div class="form-group col-xs-5">
                                                <label for="produto-tab1"><?=$parametro->label_tab1;?>:</label>
                                                <input type="text" class="form-control" id="produto-tab1" name="produto-tab1" value="<?=number_format($precoTab1, 2, ',', '.')?>">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label for="produto-tab1-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab1-por" name="produto-tab1-por" value="<?=$result["Perc_Tab_preco1"]?>" onkeyup="recalculaProduto('#produto-tab1', this.value, <?=$precoCusto?>)">
                                               
                                            </div>
                                            <div class="form-group col-xs-5">
                                                <label for="produto-tab1ant"><?=$parametro->label_tab1;?> Anterior:</label>
                                                <input type="text" class="form-control" id="produto-tab1-antd" name="produto-tab1-antD" value="<?=number_format($result["Tab_Preco_1"], 2, ',', '.')?>" disabled>
                                                <input type="hidden" class="form-control" id="produto-tab1-ant" name="produto-tab1-ant" value="<?=number_format($result["Tab_Preco_1"], 2, ',', '.')?>" >
                                            </div>
                                        </div>
                                        <?php } 
                                            if($parametro->visualiza_tab2 == '-1' ){
                                                            ?>
                                        <div class="row">
                                            <div class="form-group col-xs-5">
                                                <label for="produto-tab2"><?=$parametro->label_tab2;?>:</label>
                                                <input type="text" class="form-control" id="produto-tab2" name="produto-tab2" value="<?=number_format($precoTab2, 2, ',', '.')?>">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label for="produto-tab2-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab2-por" name="produto-tab2-por" value="<?=$result["Perc_Tab_preco2"]?>" onchange="recalculaProduto('#produto-tab2', this.value, <?=$precoCusto?>)">
                                            </div>
                                            <div class="form-group col-xs-5">
                                                <label for="produto-tab2-ant"><?=$parametro->label_tab2;?> Anterior:</label>
                                                <input type="text" class="form-control" id="produto-tab2-antD" name="produto-tab2-antD" value="<?=number_format($result["Tab_Preco_2"], 2, ',', '.')?>" disabled>
                                                <input type="hidden" class="form-control" id="produto-tab2-ant" name="produto-tab2-ant" value="<?=number_format($result["Tab_Preco_2"], 2, ',', '.')?>" >
                                            </div>
                                        </div>
                                        <?php } 
                                            if($parametro->visualiza_tab3 == '-1' ){
                                                            ?>
                                        <div class="row">
                                            <div class="form-group col-xs-5">
                                                <label for="produto-tab3"><?=$parametro->label_tab3?>:</label>
                                                <input type="text" class="form-control" id="produto-tab3" name="produto-tab3" value="<?=number_format($precoTab3, 2, ',', '.')?>">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label for="produto-tab3-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab3-por" name="produto-tab3-por" value="<?=$result["Perc_Tab_preco3"]?>" onchange="recalculaProduto('#produto-tab3', this.value, <?=$precoCusto?>)">
                                            </div>
                                            <div class="form-group col-xs-5">
                                                <label for="produto-tab3-ant"><?=$parametro->label_tab3;?> Anterior:</label>
                                                <input type="text" class="form-control" id="produto-tab3-antD" name="produto-tab3-antD" value="<?=number_format($result["Tab_Preco_3"], 2, ',', '.')?>" disabled>
                                                <input type="hidden" class="form-control" id="produto-tab3-ant" name="produto-tab3-ant" value="<?=number_format($result["Tab_Preco_3"], 2, ',', '.')?>" >
                                            </div>
                                        </div>
                                        <?php } 
                                        if($parametro->visualiza_tab4 == '-1' ){
                                                            ?>
                                                    <div class="row">
                                                        <div class="form-group col-xs-5">
                                                            <label for="produto-tab4"><?=$parametro->label_tab4;?>:</label>
                                                            <input type="text" class="form-control" id="produto-tab4" name="produto-tab4" value="<?=number_format($precoTab4, 2, ',', '.')?>">
                                                        </div>
                                                        <div class="form-group col-xs-2">
                                                            <label for="produto-tab4-por">%:</label>
                                                            <input type="text" class="form-control" id="produto-tab4-por" name="produto-tab4-por" value="<?=$result["Perc_Tab_preco4"]?>" onchange="recalculaProduto('#produto-tab4', this.value, <?=$precoCusto?>)">
                                                        </div>
                                                        <div class="form-group col-xs-5">
                                                            <label for="produto-tab4-ant"><?=$parametro->label_tab4;?> Anterior</label>
                                                            <input type="text" class="form-control" id="produto-tab4-antD" name="produto-tab4-antD" value="<?=number_format($result["Tab_Preco_4"], 2, ',', '.')?>" disabled>
                                                            <input type="hidden" class="form-control" id="produto-tab4-ant" name="produto-tab4-ant" value="<?=number_format($result["Tab_Preco_5"], 2, ',', '.')?>" >
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                        </div>
                       
                        <div class="form-group col-xs-7">
                            <div class=" card-box" style="background: url(assets/images/agsquare.png); margin-left:10px">
                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                    <thead>
                                        <tr>                                           
                                            <th>Impostos</th>
                                            <th></th>
                                            <th></th>													  
                                        </tr>
                                    </thead>
                                    <tbody>		
                                         <tr>                                          
                                            <td>CFOP NF: <span"><strong><?=$retornoItem['NFE_CFOP'];?> </strong></span></td>                                            
                                            <td>CSON/CST:<span ><strong><?=$retornoItem['NFE_CSTCSON'];?></strong></span></td>
                                            <td>Vlr Produto: <span ><strong><?=number_format($retornoItem['NF_CUSTO_ORIG'], 2, ',', '.');?></strong></span></td>    
                                        </tr>
                                        <tr>                                          
                                            <td>Frete R$ <span class="text-custom"><?=number_format($retornoItem['NF_FRETE'], 2, ',', '.');?></span></td>
                                            <td>Desconto R$  <span class="text-custom"><?=number_format($retornoItem['NF_DESCONTO'], 2, ',', '.');?></span></td>
                                            <td >Vlr Outros R$ <span class="text-custom"><?=number_format($retornoItem['NF_OUTROS'], 2, ',', '.');?></span></td>                                           
                                        </tr>
                                        <?php if($retornoItem['NF_IPI_vIPI'] > 0)	 { ?>
                                        <tr>                                          
                                            <td>Base IPI R$ <span class="text-custom"><?=number_format($retornoItem['NF_IPI_vBC'], 2, ',', '.');?></span></td>
                                            <td>% IPI: <span class="text-custom"><?=$retornoItem['NFE_IPI'];?></span></td>
                                            <td >Vlr R$:<span class="text-custom"><?=number_format($retornoItem['NF_IPI_vIPI'], 2, ',', '.');?></span></td>                                           
                                        </tr>
                                        <?php  } 
                                        if($retornoItem['NF_vICMS']> 0)	 { ?>
                                        <tr>                                          
                                            <td>Base ICMS R$ <span class="text-custom"><?=number_format($retornoItem['NF_ICMS_vBC'], 2, ',', '.');?></span></td>
                                            <td>% ICMS: <span class="text-custom"><?=$retornoItem['NF_pICMS'];?></span></td>
                                            <td >Vlr R$:<span class="text-custom"><?=number_format($retornoItem['NF_vICMS'], 2, ',', '.');?></span></td>                                           
                                        </tr>
                                        <?php  } 
                                        if($retornoItem['NF_vICMSST']> 0)	 { ?>
                                        <tr>                                          
                                            <td>Base ICMS ST R$ <span class="text-custom"><?=number_format($retornoItem['NF_vBCST'], 2, ',', '.');?></span></td>
                                            <td>% ICMS ST: <span class="text-custom"><?=$retornoItem['NF_pICMSST'];?></span></td>
                                            <td >Vlr R$:<span class="text-custom"><?=number_format($retornoItem['NF_vICMSST'], 2, ',', '.');?></span></td>                                           
                                        </tr> 
                                        <?php  } 
                                        
                                        if($retornoItem['NF_vIFCPST']> 0)	 { ?>
                                            <tr>                                          
                                                <td>Base FCP ST R$ <span class="text-custom"><?=number_format($retornoItem['NF_vFCPST'], 2, ',', '.');?></span></td>
                                                <td>% FCP ST: <span class="text-custom"><?=$retornoItem['NF_pFCPST'];?></span></td>
                                                <td >Vlr R$:<span class="text-custom"><?=number_format($retornoItem['NF_vIFCPST'], 2, ',', '.');?></span></td>                                           
                                            </tr> 
                                            <?php  } 
                                        if($retornoItem['NF_CONFIS_vCONFIS']> 0)	 { ?>                                       
                                        <tr>                                          
                                            <td>Base COFINS ST R$ <span class="text-custom"><?=number_format($retornoItem['NF_CONFIS_vBC'], 2, ',', '.');?></span></td>
                                            <td>% COFINS : <span class="text-custom"><?=$retornoItem['NF_CONFIS_pCONFIS'];?></span></td>
                                            <td >Vlr R$:<span class="text-custom"><?=number_format($retornoItem['NF_CONFIS_vCONFIS'], 2, ',', '.');?></span></td>                                           
                                        </tr>
                                        <?php  } 
                                        if($retornoItem['NF_PIS_vPIS']> 0)	 { ?>  
                                        <tr>                                          
                                            <td>Base PIS  R$ <span class="text-custom"><?=number_format($retornoItem['NF_PIS_vBC'], 2, ',', '.');?></span></td>
                                            <td>% PIS: <span class="text-custom"><?=$retornoItem['NF_PIS_pPIS'];?></span></td>
                                            <td >Vlr R$:<span class="text-custom"><?=number_format($retornoItem['NF_PIS_vPIS'], 2, ',', '.');?></span></td>                                           
                                        </tr>
                                        <?php  } 
                                        ?>  
                                        <tr>                                          
                                            <td>CST IPI: <span class="text-custom"><?=$retornoItem['NF_IPI_CST'];?></span></td>
                                            <td>CST PIS: <span class="text-custom"><?=$retornoItem['NF_IPI_CST'];?></span></td>
                                            <td>CST COFINS:<span class="text-custom"><?=$retornoItem['NF_CONFIS_CST'];?></span></td>                                           
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <?php if( $ativa_salvar  == 0) { ?>                              
                                <div class="alert alert-warning text-center" style="margin-left:10px">
                                        Opção salvar desativada para essa CFOP
                                            </div> 
                                <?php } ?>
                        
                        </div>
                    </div>
                    
                   
      
                </form>
            </div>
            <div class="modal-footer">
                <?php if( $ativa_salvar  == 1) { ?>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_alteraProduto()">Salvar</button>
                <?php  } ?>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div class="bg-icon pull-request">
                    <i class="fa fa-5x fa-check-circle-o"></i>
                    <h2>Produto não localizado.<?=$e;?></h2>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/*
 * Adiciona produto
 * */
else if ($acao["acao"] == 1) {
 
   
   empty($_parametros["produto-ipi"]) ?: $_parametros["produto-ipi"] = LimpaVariavel($_parametros["produto-ipi"]);
  //  empty($_parametros["produto-cst"]) ?: $_parametros["produto-cst"] = LimpaVariavel($_parametros["produto-ipi"]);
    
    empty($_parametros["produto-valor"]) ?: $_parametros["produto-valor"] = LimpaVariavel($_parametros["produto-valor"]);

    $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".nota_ent_base INNER JOIN ".$_SESSION['BASE'].".fabricante ON NFE_FORNEC = CODIGO_FABRICANTE WHERE NFE_NRO = '".$_parametros["produto-nota"]."' AND NFE_FORNEC = '".$_parametros["produto-fornecedor"]."'");
    $retornoNF = $consulta->fetch();

    if (empty($_parametros["produto-quantidade"]) || intval($_parametros["produto-quantidade"]) < 0) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a quantidade de produtos!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else if (empty($_parametros["produto-valor"]) || floatval($_parametros["produto-valor"]) < 0.0) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o valor do produto!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        $totalProdutos = ($_parametros["produto-quantidade"]) * floatval($_parametros["produto-valor"]);
        try {
            

            $consulta = $pdo->query("SELECT  max(NFE_ITEM) AS seq FROM ". $_SESSION['BASE'] .".nota_ent_item WHERE NFE_IDBASE = '".$_parametros["produto-idchave"]."' ");
            $seqret = $consulta->fetch();
            $seq = intval($seqret["seq"]) + 1;

            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_IPI,NFE_DESCRICAO,NFE_CSTCSON,NFE_IDBASE,NFE_ITEM) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");

            $statement->bindParam(1, $_parametros["produto-fornecedor"]);
            $statement->bindParam(2, $_parametros["produto-nota"]);
            $statement->bindParam(3, $_parametros["produto-id"]);
            $statement->bindParam(4, $_parametros["produto-quantidade"]);
            $statement->bindParam(5, $_parametros["produto-valor"]);
            $statement->bindParam(6, $totalProdutos);
            $statement->bindParam(7, $_parametros["produto-ipi"]);
            $statement->bindParam(8, $_parametros["produto-descricao"]);
            $statement->bindParam(9, $_parametros["produto-cst"]);
            $statement->bindParam(10, $_parametros["produto-idchave"]);
            $statement->bindParam(11,$seq );
          
            $statement->execute();
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Incluído!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-buscar">Fechar</button>
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
 * Lista Produtos Notas
 * */
else if ($acao["acao"] == 2) {
    $query = $pdo->query("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  
    FROM  " . $_SESSION['BASE'] . ".parametro  ");
    $retornoItem = $query->fetch();

    $_vizCodInterno = $retornoItem['empresa_vizCodInt'];
  
    $contultaNF = $pdo->query("SELECT NFE_Conferido,NFE_ESTOK,NFE_CPGOK 
    FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
    $retornoNF = $contultaNF->fetch();

    $statement = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".nota_ent_item 
    LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON NFE_CODIGO = codigo_fornecedor WHERE NFE_NRO = '" . $_parametros["id-nota"] . "' AND NFE_FORNEC = '" . $_parametros["id-fornecedor"] . "' ORDER BY NFE_ITEM");
    $retorno = $statement->fetchAll();
    $totalNota = 0.0;
    ?>
    <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%">
    <?php
    if($retornoNF["NFE_Conferido"] == '-1' or $retornoNF["NFE_CPGOK"] == '-1' and $retornoNF["NFE_ESTOK"] == '-1'){
       // $disable = "sim";
       ?>
       <div class="row text-right">
       <button id="et" type="button" class="btn  waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-etiqueta">Etiqueta<span class="btn-label btn-label-right"><i class="fa  fa-barcode"></i></span></button>
       </div>
       <?php
    }else{
 
        ?>
        <div class="row text-right">
            <button id="et" type="button" class="btn  waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-etiqueta">Etiqueta<span class="btn-label btn-label-right"><i class="fa  fa-barcode"></i></span></button>
            <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-buscar">Buscar Peças/Produtos<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
        </div>
        <?php
    }
        ?>
        <thead>
        <tr>
        <th>Seq</th>
        <th></th>
            <th>Descrição</th>
            
            <th>Cód.Barra</th>
            <?php
                if($_vizCodInterno == 1) { ?>
                 <th>Cod.Fabricante</th>
                <?php 
                } ?>
            <th class="text-center">Qtde</th>
            <th class="text-center">Valor</th>                  
            <th class="text-center">Total</th>
            <th class="text-center">IPI</th>
            <th class="text-center">Pedido</th>
           
            <th class='text-right'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php   
        foreach ($retorno as $row) {
            switch ($row["nfitemstatus"]) {
                case 0:
                     $cor = "inverse";
                    break;
                case 1:
                     $cor = "warning";
                    
                    break;
                case 2:
                     $cor = "success";                   
                    break;
                default:
                      $cor = "inverse";       
            }
            ?>
            <tr class="gradeX">
            <td class="text-center"><?=$row["NFE_ITEM"]?></td>
            <td class="text-center" id="id_<?=$row["NFE_CHAVE"]?>">                
                                    <span class="badge badge-<?=$cor;?> m-l-0" style="font-size: 12px;"><i class="fa fa-dot-circle-o"></i></span>                                    
                    
                </td>
                <td   style="min-width:300px ;"><?=$row["DESCRICAO"]?></td>
               
                <td><?=$row["Codigo_Barra"];?></td>
                <?php
                if($_vizCodInterno == 1) { ?>
                 <th><?=$row["CODIGO_FABRICANTE"];?></th>
                <?php 
                } ?>
                
               
                <td class="text-center"><?=$row["NFE_QTDADE"]?></td>
                <td class="text-center"><?=number_format($row["NFE_VLRUNI"],2,',','.')?></td>
                
               
                <td class="text-center"><?=number_format($row["NFE_TOTALITEM"],2,',','.')?></td>
                <td class="text-center"><?=$row["NFE_IPI"]?></td>
                <td><?=$row["NF_xPed"];?></td>
            <?php
            if($retornoNF["NFE_Conferido"] == '-1' or $retornoNF["NFE_CPGOK"] == '-1' and $retornoNF["NFE_ESTOK"] == '-1'){
                ?>
           
                <td class="actions text-right">
                <a href="javascript:void(0);" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-calcular" onclick="_calculaModal(<?=$row["CODIGO_FORNECEDOR"];?>,<?=$row["NFE_CHAVE"]?>,'<?=$cor;?>')"><i class="fa fa-calculator fa-2x"></i></a>
                     <span class="text-success"><i class="fa fa-2x   fa-check"></i></span>
                </td>
                   <?php
             }else{
                ?>
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-calcular" onclick="_calculaModal(<?=$row["CODIGO_FORNECEDOR"];?>,<?=$row["NFE_CHAVE"]?>,'<?=$cor;?>')"><i class="fa fa-calculator fa-2x"></i></a>
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idexcluir(<?=$row["NFE_CHAVE"]?>, true)"><i class="fa fa-trash-o fa-2x"></i></a>
                </td>
                <?php
            }
            ?>
            </tr>
            <?php
            $totalNota += $row["NFE_TOTALITEM"];
        }
        ?>
        </tbody>
    </table>
    <div class="alert alert-info text-right">
        Total <strong>R$<?=number_format($totalNota, 2, ',', '.')?></strong>
    </div>
    <?php
}
/*
 * Calcula produto
 * */
else if ($acao["acao"] == 3) {
    empty($_parametros["produto-venda-a"]) ?: $_parametros["produto-venda-a"] = ajustaData($_parametros["produto-venda-a"]);
    empty($_parametros["produto-venda-b"]) ?: $_parametros["produto-venda-b"] = ajustaData($_parametros["produto-venda-b"]);
    empty($_parametros["produto-custo"]) ?: $_parametros["produto-custo"] = LimpaVariavel($_parametros["produto-custo"]);
    empty($_parametros["produto-venda"]) ?: $_parametros["produto-venda"] = LimpaVariavel($_parametros["produto-venda"]);
    empty($_parametros["produto-vendapor"]) ?: $_parametros["produto-vendapor"] = LimpaVariavel($_parametros["produto-vendapor"]);
    empty($_parametros["produto-tab1"]) ?: $_parametros["produto-tab1"] = LimpaVariavel($_parametros["produto-tab1"]);
    empty($_parametros["produto-tab1-por"]) ?: $_parametros["produto-tab1-por"] = LimpaVariavel($_parametros["produto-tab1-por"]);
    empty($_parametros["produto-tab2"]) ?: $_parametros["produto-tab2"] = LimpaVariavel($_parametros["produto-tab2"]);
    empty($_parametros["produto-tab2-por"]) ?: $_parametros["produto-tab2-por"] = LimpaVariavel($_parametros["produto-tab2-por"]);
    empty($_parametros["produto-tab3"]) ?: $_parametros["produto-tab3"] = LimpaVariavel($_parametros["produto-tab3"]);
    empty($_parametros["produto-tab3-por"]) ?: $_parametros["produto-tab3-por"] = LimpaVariavel($_parametros["produto-tab3-por"]);
    empty($_parametros["produto-tab4"]) ?: $_parametros["produto-tab4"] = LimpaVariavel($_parametros["produto-tab4"]);
    empty($_parametros["produto-tab4-por"]) ?: $_parametros["produto-tab4-por"] = LimpaVariavel($_parametros["produto-tab4-por"]);
    empty($_parametros["produto-codfabricante"]) ?: $_parametros["produto-codfabricante"] = LimpaVariavel($_parametros["produto-codfabricante"]);
    empty($_parametros["produto-codsimilar"]) ?: $_parametros["produto-codsimilar"] = LimpaVariavel($_parametros["produto-codsimilar"]);
    
    try {
        $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET DESCRICAO = ?, Descricao_Reduzida = ?, GRU_GRUPO = ?, CODIGO_LINHA = ?, PRECO_CUSTO = ?, Tab_Preco_5 = ?, Perc_Tab_preco5 = ?, Tab_Preco_1 = ?, Perc_Tab_preco1 = ?, Tab_Preco_2 = ?, Perc_Tab_preco2 = ?, Tab_Preco_3 = ?, Perc_Tab_preco3 = ?, Tab_Preco_4 = ?, Perc_Tab_preco4 = ?, item_lote_A = ?, item_lote_B = ?, item_validade_A = ?, item_validade_B = ?,CODIGO_FABRICANTE = ? ,CODIGO_SIMILAR = ? WHERE CODIGO_FORNECEDOR = ?");
        $statement->bindParam(1, $_parametros["produto-descricao"]);
        $statement->bindParam(2, $_parametros["produto-resumo"]);
        $statement->bindParam(3, $_parametros["produto-grupo"]);
        $statement->bindParam(4, $_parametros["produto-linha"]);
        $statement->bindParam(5, $_parametros["produto-custo"]);
        $statement->bindParam(6, $_parametros["produto-venda"]);
        $statement->bindParam(7, $_parametros["produto-vendapor"]);
        $statement->bindParam(8, $_parametros["produto-tab1"]);
        $statement->bindParam(9, $_parametros["produto-tab1-por"]);
        $statement->bindParam(10, $_parametros["produto-tab2"]);
        $statement->bindParam(11, $_parametros["produto-tab2-por"]);
        $statement->bindParam(12, $_parametros["produto-tab3"]);
        $statement->bindParam(13, $_parametros["produto-tab3-por"]);
        $statement->bindParam(14, $_parametros["produto-tab4"]);
        $statement->bindParam(15, $_parametros["produto-tab4-por"]);
        $statement->bindParam(16, $_parametros["produto-lotea"]);
        $statement->bindParam(17, $_parametros["produto-loteb"]);
        $statement->bindParam(18, $_parametros["produto-venda-a"]);
        $statement->bindParam(19, $_parametros["produto-venda-b"]);
        $statement->bindParam(20, $_parametros["produto-codfabricante"]);        
        $statement->bindParam(21, $_parametros["produto-codsimilar"]);
        $statement->bindParam(22, $_parametros["produto-codigo"]);
        
       
        
        $statement->execute();


           //historico
           $_parametros2 = array(                
            'tipo' =>'2',
            'login' =>$loginuser,
            'loginnome' => $loginNOME,
            'codigoitem' => $_parametros["produto-codigo"],
            'vlrcustoatual' =>$_parametros["produto-custo"],
            'vlrvendaatual' =>$_parametros["produto-venda"],
            'vendaAnterior' =>$_parametros["produto-vlanterior"],
            'custoAnterior' =>$_parametros["produto-custoant"],
            'vlrcustoTab1'=>$_parametros["produto-tab1"],                 
            'vlrcustoTab2'=>$_parametros["produto-tab2"], 
            'vlrcustoTab3'=>$_parametros["produto-tab3"], 
            'vlrcustoTab4'=>$_parametros["produto-tab4"], 
            'vlrcustoAnTab1'=>$_parametros["produto-tab1-ant"], 
            'vlrcustoAnTab2'=>$_parametros["produto-tab2-ant"], 
            'vlrcustoAnTab3'=>$_parametros["produto-tab3-ant"], 
            'vlrcustoAnTab4'=>$_parametros["produto-tab4-ant"] 
        );
       //$_parametros2 =  array_merge($_parametros2);
        $ret =  Estoque::gravarAlteracaoPrecoCadastro($_parametros2);

        $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".nota_ent_item SET nfitemstatus  = '2' WHERE NFE_CHAVE = ? ");
        $statement->bindParam(1, $_parametros["produto-chave"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Produto Atualizado!  </h2>
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
/*
 * Exclui produto
 * */
else if ($acao["acao"] == 4) {
    $consulta = $pdo->query("SELECT NFE_NRO, NFE_FORNEC, NFE_CODIGO, NFE_QTDADE, NFE_VLRUNI, NFE_TOTALITEM, NFE_ESTOK FROM ". $_SESSION['BASE'] .".nota_ent_item WHERE NFE_CHAVE = '". $_parametros["id-exclusao"] ."'");
    $resultProd = $consulta->fetch();

    $consultaNF = $pdo->query("SELECT NFE_ALMOX FROM ". $_SESSION['BASE'] .".nota_ent_base WHERE NFE_NRO = '".$resultProd["NFE_NRO"]."' AND NFE_FORNEC = '".$resultProd["NFE_FORNEC"]."'");
    $resultNF = $consultaNF->fetch();

    if (intval($resultProd["NFE_ESTOK"]) == 0) {
        try {
            $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_CHAVE = :id");
            $statement->bindParam(':id', $_parametros["id-exclusao"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Excluído!</h2>
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
    else {
        date_default_timezone_set('America/Sao_Paulo');
        $codigoMov = "S";
        $tipMov = "E";
        $numDocumento = $resultProd["NFE_NRO"]."-".$resultProd["NFE_FORNEC"];
        $resultProd["NFE_VLRUNI"] = number_format($resultProd["NFE_VLRUNI"], 2, ".", "");
        $inventario = "0";
        $resultProd["NFE_TOTALITEM"] = number_format($resultProd["NFE_TOTALITEM"], 2, ".", "");
        $motivo = "Exclusao Item Entr. NF";
        $dataMov = date("Y-m-d H:i:s", time());

        try {
            $consultaQuantidade = $pdo->query("SELECT Qtde_Disponivel FROM ". $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '".$resultProd["NFE_CODIGO"]."' AND Codigo_Almox = '".$resultNF["NFE_ALMOX"]."'");
            $retorno = $consultaQuantidade->fetch();;
            $quantidadeAtual = intval($retorno["Qtde_Disponivel"]) - intval($resultProd["NFE_QTDADE"]);

            $updateEstoque = $pdo->prepare("UPDATE ". $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
            $updateEstoque->bindParam(1, $quantidadeAtual);
            $updateEstoque->bindParam(2, $resultProd["NFE_CODIGO"]);
            $updateEstoque->bindParam(3, $resultNF["NFE_ALMOX"]);
            $updateEstoque->execute();

            $excluiProduto = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_CHAVE = :id");
            $excluiProduto->bindParam(':id', $_parametros["id-exclusao"]);
            $excluiProduto->execute();

            $insertMov = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde, Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertMov->bindParam(1, $resultProd["NFE_CODIGO"]);
            $insertMov->bindParam(2, $resultProd["NFE_QTDADE"]);
            $insertMov->bindParam(3, $resultNF["NFE_ALMOX"]);
            $insertMov->bindParam(4, $codigoMov);
            $insertMov->bindParam(5, $tipMov);
            $insertMov->bindParam(6, $numDocumento);
            $insertMov->bindParam(7, $resultProd["NFE_VLRUNI"]);
            $insertMov->bindParam(8, $inventario);
            $insertMov->bindParam(9, $resultProd["NFE_TOTALITEM"]);
            $insertMov->bindParam(10, $_SESSION["IDUSER"]);
            $insertMov->bindParam(11,$motivo);
            $insertMov->bindParam(12, $quantidadeAtual);
            $insertMov->bindParam(13,$dataMov);
            $insertMov->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Excluído!</h2>
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
/*
 * Busca produto para incluir
 * */
else if ($acao["acao"] == 8) {
    
     $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".itemestoque
      WHERE CODIGO_FABRICANTE = '". $_parametros["produto-codsimilar"] ."' ");
     $result = $consulta->fetch();
     echo $result["CODIGO_FORNECEDOR"];
     $consulta = $pdo->query("UPDATE ". $_SESSION['BASE'] .".nota_ent_item 
     SET NFE_CODIGO = '".$result["CODIGO_FORNECEDOR"]."'
     WHERE NFE_CHAVE = '". $_parametros["produto-chave"] ."' ");
     $result = $consulta->fetch();


 
 }
else if ($acao["acao"] == 6){
   $_pesquisrpor  = $_parametros["sel-filtro"] ;

    if ($_pesquisrpor!= 'descricao') {
        $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".itemestoque WHERE ".$_pesquisrpor." = '". $_parametros["id-filtro"] ."'");
        $result = $consulta->fetchAll();       
        ?>
     
        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Descrição</th>               
                <th>Cód. Fabricante</th>
                <th>Cód. Barras</th>
                <th>Valor</th>
                <th class="text-center">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($result as $row) {
                ?>
                <tr class="gradeX">
                    <td title="<?=$row["DESCRICAO"]?>"><?=$row["DESCRICAO"]?></td>
                  
                    <td><?=$row["CODIGO_FABRICANTE"]?></td>
                    <td><?=$row["Codigo_Barra"]?></td>
                    <td><?=number_format($row["PRECO_CUSTO"],2,',','.')?></td>
                    <td class="actions text-center">
                        <a href="javascript:void(0);" class="on-default edit-row" title="Adicionar Produto" onclick="_buscaDadosProd(<?=$row["Codigo"];?>,<?=$row["CODIGO_FORNECEDOR"];?>)"><i class="fa  fa-plus-square-o fa-2x"></i></a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php

    }
    
   
    else {
   
        $consulta = $pdo->query("SELECT Codigo,DESCRICAO,Codigo_Barra,itemestoque.CODIGO_FABRICANTE as cod,NOME,PRECO_CUSTO,CODIGO_FORNECEDOR,customedio 
        FROM ". $_SESSION['BASE'] .".itemestoque 
        LEFT JOIN ". $_SESSION['BASE'] .".fabricante ON itemestoque.COD_FABRICANTE = fabricante.CODIGO_FABRICANTE 
        WHERE  DESCRICAO LIKE '%". $_parametros["id-filtro"] ."%' limit 500");
        $result = $consulta->fetchAll();
       
        ?>
        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Descrição</th>
                <th>Fornecedor</th>
                <th>Código</th>
                <th>Cód. Barras</th>
                <th>Valor</th>
                <th class="text-center">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($result as $row) {
                ?>
                <tr class="gradeX">
                <td title="<?=$row["DESCRICAO"]?>"><?=$row["DESCRICAO"]?></td>
                    <td><?=strlen($row["NOME"]) > 17 ? substr($row["NOME"], 0, 15)."...": $row["NOME"]?></td>
                    <td><?=$row["cod"]?></td>
                    <td><?=$row["Codigo_Barra"]?></td>
                    <td><?=number_format($row["PRECO_CUSTO"],2,',','.')?></td>
                    <td class="actions text-center">
                        <a href="javascript:void(0);" class="on-default edit-row" title="Adicionar Produto" onclick="_buscaDadosProd(<?=$row["Codigo"];?>,<?=$row["CODIGO_FORNECEDOR"];?>)"><i class="fa fa-plus-square-o fa-2x"></i></a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <code>* Obs:limite visualização 500 itens </code>
        <?php
    }
}
/*
 * Preenche form-produto
 * */
else if ($acao["acao"] == 7) {
    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FORNECEDOR = '". $_parametros["id-prodfornec"] ."' AND Codigo = '". $_parametros["id-produto"] ."'");
    $result = $consulta->fetch();
    ?>
    <form method="post" action="" name="form-produto" id="form-produto">
        <div class="row">
                <div class="form-group col-md-3">
                    <label for="descricao" class="control-label">Descrição:</label>
                    <input type="text" class="form-control" name="descricao" id="descricao" value="<?=$result["DESCRICAO"]?>" >
                    <input type="hidden" name="produto-descricao" id="produto-descricao" value="<?=$result["DESCRICAO"]?>">
                    <input type="hidden" name="produto-id" id="produto-id" value="<?=$result["CODIGO_FORNECEDOR"]?>">
                    <input type="hidden" name="produto-idchave" id="produto-idchave" value="<?=$_parametros["id-chave"]?>">
                
                </div>
                <div class="form-group col-md-2">
                    <label for="produto-quantidade" class="control-label">Quant:</label>
                    <input type="number" name="produto-quantidade" id="produto-quantidade" class="form-control">
                    <input type="hidden" name="produto-fornecedor" id="produto-fornecedor" value="<?=$_parametros["id-fornecedor"]?>">
                </div>
                <div class="form-group col-md-5">
                    <label for="produto-valor" class="control-label">Valor:</label>
                    <div class="input-group">
                        <input type="text" name="produto-valor" id="produto-valor" class="form-control" value="<?=number_format($result["PRECO_CUSTO"],2,',','.')?>">
                        
                    </div>
                </div>
               
        </div>
        <div class="row">
                 <div class="form-group col-md-2">
                    <label for="produto-quantidade" class="control-label">CST/CSON:</label>
                    <input type="text" name="produto-cst" id="produto-cst" class="form-control">
                   
                </div>
                <div class="form-group col-md-2">
                    <label for="produto-ipi" class="control-label">IPI:</label>
                    <input type="text" name="produto-ipi" id="produto-ipi" class="form-control">
                    <input type="hidden" name="produto-nota" id="produto-nota" value="<?=$_parametros["id-nota"]?>">
                </div>
                <div class="form-group col-md-1">
                         <div class="input-group-btn" style="padding-top:25px">
                            <button type="button" class="btn btn-success waves-effect waves-light" onclick="_adicionaProduto()">Incluir<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
                        </div>
                        </div>

        </div>
       
    </form>
    <?php
}