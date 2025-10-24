<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

$consultaParametro = $pdo->query("SELECT Tipo_Autoriz,extra_A_label, extra_B_label,empresa_labelEnderA,empresa_labelEnderB,
                                empresa_labelEnderC,empresa_cadestoqueReceita,empresa_cadestoqueSite
                                FROM ". $_SESSION['BASE'] .".parametro");
                                $retornoParametro = $consultaParametro->fetch();

//empresa_vizCodInt codigo visualização interno
$query = ("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  from  " . $_SESSION['BASE'] . ".parametro  ");
$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {
    $_vizCodInterno = $rst["empresa_vizCodInt"];
    $_label_EderA = $rst["empresa_labelEnderA"];
    $_label_EderB = $rst["empresa_labelEnderB"];
    $_label_EderC= $rst["empresa_labelEnderC"];
    $_prodreceita = $rst["empresa_cadestoqueReceita"];
    $_prodsite= $rst["empresa_cadestoqueSite"];
}


//$consultaUsuario = $pdo->query("SELECT usuario_perfil2 FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION["IDUSER"]."'");
//$retornoUsuario = $consultaUsuario->fetch();

$consultaPermissao = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".permissao WHERE permissao_id = '".$retornoUsuario["usuario_perfil2"]."'");
$retornoPermissao = $consultaPermissao->fetch();

$consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".parametro");
$parametro = $consulta->fetch(\PDO::FETCH_OBJ);

$consulta = $pdo->query("SELECT *, DATE_FORMAT(DATA_ULT_ENTRADA,'%Y-%m-%d') as datault,DATE_FORMAT(Dt_Ult_alteracaovlVenda,'%d/%m/%Y') as Dt_Ult_alteracaovlVenda FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FORNECEDOR = '$_chaveid'");
$result = $consulta->fetch();


$precoCusto = $result["PRECO_CUSTO"];
/*
$precoVenda = $result["Perc_Tab_preco5"] > 0 ? $precoCusto + ($precoCusto * ($result["Perc_Tab_preco5"] / 100)) : $precoVenda = $result["Tab_Preco_5"];
$precoTab1 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco1"] / 100));
$precoTab2 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco2"] / 100));
$precoTab3 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco3"] / 100));
$precoTab4 = $precoCusto + ($precoCusto * ($result["Perc_Tab_preco4"] / 100));
*/
$precoVenda = $result["Tab_Preco_5"];
$precoTab1 = $result["Tab_Preco_4"];
$precoTab2 = $result["Tab_Preco_3"];
$precoTab3 = $result["Tab_Preco_2"];
$precoTab4 = $result["Tab_Preco_1"];
$dtultaltvenda  = $result["Dt_Ult_alteracaovlVenda"];
$precoVendaAnt = $result["vl_Ant_alteracaovlVenda"];
$unidade_medida = $result["UNIDADE_MEDIDA"];
if($unidade_medida == ""){
    $unidade_medida = "UN";
}

?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Peças e Produtos </h4>
                <p class="text-muted page-title-alt"></p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                <button id="salvar" type="button" class="btn btn-success waves-effect waves-light mb-auto" <?=$result == NULL ? "onclick='_incluirProduto()'" : "onclick='_alterarProduto()'" ?>><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>    
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <div class="panel-body">
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados Gerais</a>
                            </li>
                            <li>
                                <a href="#navpills-21" data-toggle="tab" aria-expanded="false">Outros</a>
                            </li>
                            <li>
                                <a href="#navpills-31" data-toggle="tab" aria-expanded="false">Impostos</a>
                            </li>
                            <li>
                                <a href="#navpills-41" data-toggle="tab" aria-expanded="false" onclick="_listaFornecedor()">Fornecedores</a>
                            </li>
                            <li>
                                <a href="#navpills-42" data-toggle="tab" aria-expanded="false" onclick="_listaHistorico()">Histórico</a>
                            </li>
                            <?php if($_prodreceita == 1) { ?>
                            <li>
                                <a href="#navpills-51" data-toggle="tab" aria-expanded="false" onclick="liberaIngrediente()">Receita</a>
                            </li>
                           
                                <li>
                                  <a href="#navpills-61" data-toggle="tab" aria-expanded="false" name="form->Info. Nutri"></a>
                                </li>
                            <?php }
                          
                           if($_prodreceita == 1) { ?>
                                <li>
                                <a href="#navpills-71" data-toggle="tab" aria-expanded="false">Imagem Site</a>
                            </li>
                            <?php } ?>
                        </ul>
                        <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" <?=$result == NULL ? "id='form-inclui'" : "id='form-altera'" ?> data-parsley-validate novalidate>
                            <div class="tab-content br-n pn">
                                <div id="navpills-11" class="tab-pane active">
                                    <div class="card">
                                        <div class="row">
                                            <div class="form-group col-xs-3">
                                                <label for="produto-codigo">Cód. Produto:</label>
                                                <?php
                                                    if ($result != null) {
                                                        $_opcaoEnder = "a";
                                                        ?>
                                                        <input type="text" class="form-control" id="produto-codigo" value="<?=$result["CODIGO_FORNECEDOR"]?>" disabled>
                                                        <input type="hidden" name="produto-codigo" value="<?=$result["CODIGO_FORNECEDOR"]?>">
                                                        <input type="hidden" id="produto-id" name="produto-id" value="<?=$result["Codigo"]?>">
                                                        <input type="hidden" id="_p" name="_p" value="">
                                                       
                                                        <?php
                                                    }
                                                    else {
                                                        $_opcaoEnder = "i";

                                                        $consulta = $pdo->query("SELECT Ult_Cod_Peca FROM ".$_SESSION["BASE"].".parametro");
                                                        $retorno = $consulta->fetch();

                                                        $idPecaAtt = intval($retorno["Ult_Cod_Peca"]) + 1;
                                                        $updateParametro = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".parametro SET Ult_Cod_Peca = :id");
                                                        $updateParametro->bindParam(":id", $idPecaAtt);
                                                        $updateParametro->execute();

                                                        ?>
                                                         <input type="text" class="form-control" id="produto-codigoTemp" name="produto-codigoTemp" value="<?=$retorno["Ult_Cod_Peca"]?>" readonly>
                                                        <input type="hidden" class="form-control" id="produto-codigo" name="produto-codigo" value="<?=$retorno["Ult_Cod_Peca"]?>">
                                                        <input type="hidden" id="_p" name="_p" value="">
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-3">
                                                <label for="produto-codsku">Cód. SKU:</label>
                                                <input type="text" class="form-control" id="produto-codsku" name="produto-codsku" value="<?=ltrim($result["Codigo_Referencia_Fornec"], '0');?>  " >
                                            </div>
                                            <div class="form-group col-xs-3">
                                                <label for="produto-codbarra">Cód. Barras:</label>
                                                <input type="text" class="form-control" id="produto-codbarra" name="produto-codbarra" value="<?=$result["Codigo_Barra"]?>" >
                                            </div>
                                            <div class="form-group col-xs-3">
                                                <label for="produto-codbarra">Cód. Fabricante:</label>
                                                <input type="text" class="form-control" id="produto-codfabricante" name="produto-codfabricante" value="<?=$result["CODIGO_FABRICANTE"]?>" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-descricao">Descrição:<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="produto-descricao" name="produto-descricao" value="<?=$result["DESCRICAO"]?>">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-resumo">Desc. Resumida:<span style="color:red">*</span></label>
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
                                                        <option value="<?=$row["GRU_GRUPO"]?>" <?=$row["GRU_GRUPO"] == $result["GRU_GRUPO"] ? "selected" : "" ?>><?=($row["GRU_DESC"])?></option>
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
                                            <div class="form-group col-md-2 col-xs-2">
                                                <label for="produto-vlanterior">Unidade<span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="produto-unidade" name="produto-unidade" value="<?=$unidade_medida;?>" required>
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label for="produto-custo"  class="text-primary">Preço de Custo:</label>
                                                <input type="text" class="form-control" id="produto-custo" name="produto-custo" value="<?=number_format($precoCusto,2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label for="produto-custoant">Custo Anterior:</label>
                                                <p><?=number_format($result["PRECO_CUSTO"],2,',','.')?></p>
                                                <input type="hidden" class="form-control" id="produto-custoant" name="produto-custoant" value="<?=number_format($result["PRECO_CUSTO"],2,',','.')?>"  placeholder="0,00">
                                            </div>
                                        </div>
                                      
                                      
                                        <div class="row">
                                            <div class="form-group col-md-5 col-xs-4">
                                                <label for="produto-venda">Preço Venda:</label>
                                                <input type="text" class="form-control" id="produto-venda" name="produto-venda" value="<?=number_format($precoVenda, 2, ',', '.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                                <input type="hidden" class="form-control" id="produto-vendahist" name="produto-vendahist" value="<?=number_format($precoVenda, 2, ',', '.')?>" >
                                            </div>
                                            <div class="form-group col-md-1 col-xs-2">
                                                <label for="produto-vendapor">%</label>
                                                <input type="text" class="form-control" id="produto-vendapor" name="produto-vendapor" value="<?=$result["Perc_Tab_preco5"]?>"  onchange="recalculaProduto('#produto-venda', this.value)">
                                            </div>
                                            
                                            <div class="form-group col-md-3 col-xs-3">
                                                <label for="produto-vlanterior">Dt Alteração Preço Venda</label>
                                                <input type="text" class="form-control" id="produto-dtultvenda" name="produto-dtultveda" value="<?=$dtultaltvenda;?>"  disabled>
                                            </div>
                                            <div class="form-group col-md-3 col-xs-3">
                                                <label for="produto-vlanterior">Preço Venda Anterior</label>
                                                <input type="text" class="form-control" id="produto-vva" name="produto-vva" value="<?=number_format($precoVendaAnt, 2, ',', '.')?>"  disabled>
                                            </div>
                                        
                                          
                                        </div>
                                        <?php if($parametro->visualiza_tab1 == '-1' ){ ?>
                                        <div class="row">
                                            <div class="form-group col-md-5 col-xs-4">
                                                <label for="produto-tab1"><?=$parametro->label_tab1;?>:</label>
                                                <input type="text" class="form-control" id="produto-tab1" name="produto-tab1" value="<?=number_format($result["Tab_Preco_1"], 2, ',', '.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                            <div class="form-group col-md-1 col-xs-2">
                                                <label for="produto-tab1-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab1-por" name="produto-tab1-por" value="<?=$result["Perc_Tab_preco1"]?>" onchange="recalculaProduto('#produto-tab1', this.value)">
                                            </div>
                                            <div class="form-group col-md-6 col-xs-6">
                                                <label for="produto-tab1-ant"><?=$parametro->label_tab1;?> Anterior:</label>
                                                <p><?=number_format($result["Tab_Preco_1"],2,',','.')?></p>
                                                <input type="hidden" class="form-control" id="produto-tab1-ant" name="produto-tab1-ant" value="<?=number_format($result["Tab_Preco_1"],2,',','.')?>"  placeholder="0,00">
                                            </div>
                                        </div>
                                        <?php } 
                                            if($parametro->visualiza_tab2 == '-1' ){
                                        ?>
                                        <div class="row">
                                            <div class="form-group col-md-5 col-xs-4">
                                                <label for="produto-tab2"><?=$parametro->label_tab2;?>:</label>
                                                <input type="text" class="form-control" id="produto-tab2" name="produto-tab2" value="<?=number_format($result["Tab_Preco_2"], 2, ',', '.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                            <div class="form-group col-md-1 col-xs-2">
                                                <label for="produto-tab2-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab2-por" name="produto-tab2-por" value="<?=$result["Perc_Tab_preco2"]?>" onchange="recalculaProduto('#produto-tab2', this.value)">
                                            </div>
                                            <div class="form-group col-md-6 col-xs-6">
                                                <label for="produto-tab2-ant"><?=$parametro->label_tab2;?> Anterior:</label>
                                                <p><?=number_format($result["Tab_Preco_2"],2,',','.')?></p>
                                                <input type="hidden" class="form-control" id="produto-tab2-ant" name="produto-tab2-ant" value="<?=number_format($result["Tab_Preco_2"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00" >
                                            </div>
                                        </div>
                                        <?php } 
                                            if($parametro->visualiza_tab3 == '-1' ){
                                        ?>
                                        <div class="row">
                                            <div class="form-group col-md-5 col-xs-4">
                                                <label for="produto-tab3"><?=$parametro->label_tab3?>:</label>
                                                <input type="text" class="form-control" id="produto-tab3" name="produto-tab3" value="<?=number_format($result["Tab_Preco_3"], 2, ',', '.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                            <div class="form-group col-md-1 col-xs-2">
                                                <label for="produto-tab3-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab3-por" name="produto-tab3-por" value="<?=$result["Perc_Tab_preco3"]?>" onchange="recalculaProduto('#produto-tab3', this.value)">
                                            </div>
                                            <div class="form-group col-md-6 col-xs-6">
                                                <label for="produto-tab3-ant"><?=$parametro->label_tab3;?> Anterior:</label>
                                                <p><?=number_format($result["Tab_Preco_3"],2,',','.')?></p>
                                                <input type="hidden" class="form-control" id="produto-tab3-ant" name="produto-tab3-ant" value="<?=number_format($result["Tab_Preco_3"],2,',','.')?>"onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                        </div>
                                        <?php } 
                                            if($parametro->visualiza_tab4 == '-1' ){
                                        ?>
                                        <div class="row">
                                            <div class="form-group col-md-5 col-xs-4">
                                                <label for="produto-tab4"><?=$parametro->label_tab4;?>:</label>
                                                <input type="text" class="form-control" id="produto-tab4" name="produto-tab4" value="<?=number_format($result["Tab_Preco_4"], 2, ',', '.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                            <div class="form-group col-md-1 col-xs-2">
                                                <label for="produto-tab4-por">%:</label>
                                                <input type="text" class="form-control" id="produto-tab4-por" name="produto-tab4-por" value="<?=$result["Perc_Tab_preco4"]?>" onchange="recalculaProduto('#produto-tab4', this.value)">
                                            </div>
                                            <div class="form-group col-md-6 col-xs-6">
                                                <label for="produto-tab4-ant"><?=$parametro->label_tab4;?> Anterior:</label>
                                                <p><?=number_format($result["Tab_Preco_4"],2,',','.')?></p>
                                                <input type="hidden" class="form-control" id="produto-tab4-ant" name="produto-tab4-ant" value="<?=number_format($result["Tab_Preco_4"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="row">
                                            <p><strong>Status Produto:</strong></p>
                                            <div class="checkbox checkbox-primary">
                                                <div class="col-md-1 col-xs-3">
                                                    <input type="checkbox" name="produto-ativo" id="produto-ativo" <?=$result["prod_ativo"] == 0 ? "checked" : ""?>>
                                                    <label for="produto-ativo">Ativo</label>
                                                </div>
                                                <div class="col-md-2 col-xs-3">
                                                    <input type="checkbox" name="produto-ativosite" id="produto-ativosite" <?=$result["prod_ativosite"] == 0 ? "checked" : ""?>>
                                                    <label for="produto-ativosite">Ativo Site</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="navpills-21" class="tab-pane">
                                    <div class="card">
                                        <div class="row">
                                        <div class="form-group col-xs-3">
                                                <label for="produto-peso">Cód.Similar/Substituto:</label>
                                                <input type="text" class="form-control" id="produto-similar" name="produto-codsimilar" value="<?=$result["CODIGO_SIMILAR"];?>" >
                                            </div>
                                            <div class="form-group col-xs-3">
                                                <label for="produto-peso">Peso do Produto:</label>
                                                <input type="text" class="form-control" id="produto-peso" name="produto-peso" value="<?=number_format($result["peso"],3,',','.')?>" >
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-customed">Custo Médio:</label>
                                                <input type="text" class="form-control" id="produto-customed" name="produto-customed" value="<?=number_format($result["customedio"],2,',','.')?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-desconto">Desconto %:</label>
                                                <input type="text" class="form-control" id="produto-desconto" name="produto-desconto" value="<?=$result["PRECO_CONSUM_2"]?>" >
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-promo">PREÇO SUGERIDO:</label>
                                                <input type="text" class="form-control" id="produto-promo" name="produto-promo" value="<?=number_format($result["PRECO_CONSUM_FABRICA"],2,',','.')?>" >
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-min">Estoque Min:</label>
                                                <input type="text" class="form-control" id="produto-min" name="produto-min" value="<?=$result["QTDE_EST_MINIMO"]?>" >
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-max">Estoque Max:</label>
                                                <input type="text" class="form-control" id="produto-max" name="produto-max" value="<?=$result["Estoque_Maximo"]?>" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-qnta">Quant. A:</label>
                                                <input type="text" class="form-control" id="produto-qnta" name="produto-qnta" value="<?=$result["item_qtde_A"]?>" >
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-qntb">Quant. B:</label>
                                                <input type="text" class="form-control" id="produto-qntb" name="produto-qntb" value="<?=$result["item_qtde_B"]?>" >
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-qtndesc">Quant. Desconto:</label>
                                                <input type="text" class="form-control" id="produto-qtndesc" name="produto-qtndesc" value="<?=$result["Multiplo_Compra"]?>" >
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-diasval">Dias Validade:</label>
                                                <input type="number" class="form-control" id="produto-diasval" name="produto-diasval" value="<?=$result["Dias_Validade"]?>" >
                                            </div>
                                        </div>
                                       

                                        <div class="row">
                                                <div class="col-md-4 col-xs-4">
                                                    <label><?=empty($retornoParametro["empresa_labelEnderA"]) ? "Endereço A": $retornoParametro["empresa_labelEnderA"]?>:</label>
                                                    <select name="_enderA" id="_enderA" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderB('<?=$_opcaoEnder;?>')">
                                                        <option value="0">-</option>
                                                        <?php 
                                                        $consulta_produto = "Select `le_enderA`
                                                                            from " . $_SESSION['BASE'] . ".localestoque group by le_enderA ORDER BY le_ordemA ASC";
                                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                        while ($rst = mysqli_fetch_array($resultado)) {
                                                        ?>
                                                            <option value="<?= $rst['le_enderA']; ?>" <?php if($rst['le_enderA'] == $result["ENDERECO1"]) { ?>selected="selected" <?php } ?>><?= $rst['le_enderA'];?></option>
                                                        <?php

                                                        }
                                                   
                                                        ?>
                                                    </select>
                                                </div>
                                            <div class="col-md-4 col-xs-4">
                                                <label><?=empty($retornoParametro["empresa_labelEnderB"]) ? "Edereço B": $retornoParametro["empresa_labelEnderB"]?>:</label></label>
                                                <span id="_enderBcm">
                                                <select name="_enderB" id="_enderB" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderC('<?=$_opcaoEnder;?>')">
                                                        <option value="">-</option>
                                                        <?php 
                                                        $consulta_produto = "Select `le_enderB`
                                                                            from " . $_SESSION['BASE'] . ".localestoque 
                                                                            where le_enderA = '".$result["ENDERECO1"]."' group by le_enderB ORDER BY le_ordemA ASC";
                                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                        while ($rst = mysqli_fetch_array($resultado)) {
                                                        ?>
                                                            <option value="<?= $rst['le_enderB']; ?>" <?php if($rst['le_enderB'] == $result["ENDERECO2"]) { ?>selected="selected" <?php } ?>><?= $rst['le_enderB'];?></option>
                                                        <?php

                                                        }
                                                   
                                                        ?>
                                                    </select>
                                                    
                                                </span>

                                            </div>
                                            <div class="col-md-4 col-xs-4">
                                                <label><?=empty($retornoParametro["empresa_labelEnderC"]) ? "Endereço C": $retornoParametro["empresa_labelEnderC"]?>:</label></label>
                                                <span id="_enderCcm">
                                                <select name="_enderC" id="_enderC" class="form-control input-sm" style="height:40px;font-size:large" >
                                                        <option value="">-</option>
                                                        <?php 
                                                        $consulta_produto = "Select `le_enderC`
                                                                            from " . $_SESSION['BASE'] . ".localestoque 
                                                                            where le_enderA = '".$result["ENDERECO1"]."' and le_enderB = '".$result["ENDERECO2"]."'  group by le_enderC ORDER BY le_ordemA ASC";
                                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                        while ($rst = mysqli_fetch_array($resultado)) {
                                                        ?>
                                                            <option value="<?= $rst['le_enderC']; ?>" <?php if($rst['le_enderC'] == $result["ENDERECO3"]) { ?>selected="selected" <?php } ?>><?= $rst['le_enderC'];?></option>
                                                        <?php

                                                        }
                                                   
                                                        ?>
                                                    </select>
                                                
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">                                           
                                            <div class="form-group col-xs-12">
                                                <label for="produto-loteb">Complemento Endereço:</label>
                                                <input type="text" class="form-control" id="_enderComplemento" name="_enderComplemento" value="<?=$result["ENDERECO_COMP"]?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-lotea">Lote A:</label>
                                                <input type="text" class="form-control" id="produto-lotea" name="produto-lotea" value="<?=$result["item_lote_A"]?>">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-loteb">Lote B:</label>
                                                <input type="text" class="form-control" id="produto-loteb" name="produto-loteb" value="<?=$result["item_lote_B"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-venda-a">Venda A:</label>
                                                <input type="date" class="form-control" id="produto-venda-a" name="produto-venda-a" value="<?=$result["item_validade_A"]?>">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-venda-b">Venda B:</label>
                                                <input type="date" class="form-control" id="produto-venda-b" name="produto-venda-b" value="<?=$result["item_validade_B"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-extraa"><?=empty($retornoParametro["extra_A_label"]) ? "Extra A" : $retornoParametro["extra_A_label"]?>:</label>
                                                <select name="produto-extraa" id="produto-extraa" class="form-control">
                                                    <option value="0">Selecione</option>
                                                    <?php
                                                    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".extra_a");
                                                    $retorno = $consulta->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        ?><option value="<?=$row["extraA_id"]?>" <?=$row["extraA_id"] == $result["item_extraA"] ? "selected" : ""?>><?=$row["extraA_descricao"]?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-extrab"><?=empty($retornoParametro["extra_B_label"]) ? "Extra B": $retornoParametro["extra_B_label"]?>:</label>
                                                <select name="produto-extrab" id="produto-extrab" class="form-control">
                                                    <option value="0">Selecione</option>
                                                    <?php
                                                    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".extra_b");
                                                    $retorno = $consulta->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        ?><option value="<?=$row["extraB_id"]?>" <?=$row["extraB_id"] == $result["item_extraB"] ? "selected" : ""?>><?=$row["extraB_descricao"]?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-msgetiqueta">Mensagem Etiqueta:</label>
                                                <input type="text" class="form-control" id="produto-msgetiqueta" name="produto-msgetiqueta" value="<?=$result["promocao"]?>">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-impressora">Impressora:</label>
                                                <select name="produto-impressora" id="produto-impressora" class="form-control">
                                                    <option value="0">Selecione</option>
                                                    <?php
                                                    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".impressora");
                                                    $retorno = $consulta->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        ?><option value="<?=$row["impressora_id"]?>" <?=$row["impressora_id"] == $result["impressora"] ? "selected" : ""?>><?=$row["impressora_descricao"]?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-lista">Lista:</label>
                                                <input type="text" class="form-control" id="produto-lista" name="produto-lista" value="<?=$result["GRUPO_PECAS"]?>">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-especificacao">Especificação:</label>
                                                <input type="text" class="form-control" id="produto-especificacao" name="produto-especificacao" value="<?=$result["MODELO_APLICADO"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                <label for="produto-descweb">Descrição Web:</label>
                                                <input type="text" class="form-control" id="produto-descweb" name="produto-descweb" value="<?=$result["Nome_linha"]?>">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="produto-ultent">Data ult. ent:</label>
                                                <input type="date" class="form-control" id="produto-ultent" name="produto-ultent" value="<?=$result["datault"]?>" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="navpills-31" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-icms">ICMS %:</label>
                                            <input type="text" class="form-control" id="produto-icms" name="produto-icms" value="<?=number_format($result["PERC_ICMS"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-redicms">Redução ICMS %:</label>
                                            <input type="text" class="form-control" id="produto-redicms" name="produto-redicms" value="<?=number_format($result["Perc_Icms_reducao"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-ipi">IPI %:</label>
                                            <input type="text" class="form-control" id="produto-ipi" name="produto-ipi" value="<?=number_format($result["PERC_IPI"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-devipi">Devolução IPI %:</label>
                                            <input type="text" class="form-control" id="produto-devipi" name="produto-devipi" value="<?=number_format($result["PERC_IPI_DEVOLUCAO"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-devipi">Vlr ICMS Substituto :</label>
                                            <input type="text" class="form-control" id="produto-icmssubstituto" name="produto-icmssubstituto" value="<?=number_format($result["VLR_SUBSTITUTO"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                        </div>
                                        <div class="form-group col-md-1 col-xs-2">
                                            <label for="produto-devipi">PIS:</label>
                                            <select name="produto-pis" id="produto-pis" class="form-control">
                                                    <option value="0" <?=$result["id_pis"] == 0 ? "selected" : ""?> >Selecione</option>
                                                    <?php
                                                    $consulta = $pdo->query("SELECT * FROM bd_prisma.tab_pis");
                                                    $retorno = $consulta->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        ?><option value="<?=$row["pis_id"]?>" <?=$row["pis_id"] == $result["id_pis"] ? "selected" : ""?>><?=$row["pis_id"]?>-<?=$row["pis_desc"]?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                        </div>
                                      
                                        
                                    </div>
                                    <?php if($result["SIT_TRIBUTARIA"] == ""){
                                             $sittribuaria = 102;
                                        }else{
                                            $sittribuaria = $result["SIT_TRIBUTARIA"];
                                        }
                                        ?>
                                    <div class="row">
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-situacao">CST/CSON - <code>Sit.Tributária</code> </label>
                                            <input type="number" class="form-control" id="produto-situacao" name="produto-situacao" max = "3" onkeyup="MaxLength(this)" value="<?=$sittribuaria;?>">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-cfopuf">CFOP dentro UF:</label>
                                            <input type="number" class="form-control" id="produto-cfopuf" name="produto-cfopuf" max="4" onkeyup="MaxLength(this)" value="<?=$result["CFOPD"]?>">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-cfop">CFOP fora UF:</label>
                                            <input type="number" class="form-control" id="produto-cfop" name="produto-cfop" max="4" onkeyup="MaxLength(this)" value="<?=$result["CFOPF"]?>">
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-msgicms">Aliq.ICMS ST%:</label>
                                            <input type="text" class="form-control" id="produto-percICMSST" name="produto-percICMSST" value="<?=number_format($result["PERC_ICMSST"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-msgicms">Aliq.ICMS ST RET%:</label>
                                            <input type="text" class="form-control" id="produto-percICMSSTRET" name="produto-percICMSSTRET" value="<?=number_format($result["PERC_ICMSSTRET"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            <input type="hidden" class="form-control" id="produto-msgicms" name="produto-msgicms" size="90" maxlength="150" value="<?=$result["msg_red_Icms"]?>">
                                        </div>
                                        <div class="form-group col-md-1 col-xs-2">
                                            <label for="produto-devipi">COFINS:</label>
                                            <select name="produto-cofins" id="produto-cofins" class="form-control">
                                                    <option value="0"  <?=$result["id_pis"] == 0 ? "selected" : ""?>>Selecione</option>
                                                    <?php
                                                    $consulta = $pdo->query("SELECT * FROM bd_prisma.tab_cofins");
                                                    $retorno = $consulta->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        ?><option value="<?=$row["cofins_id"]?>" <?=$row["cofins_id"] == $result["id_cofins"] ? "selected" : ""?>><?=$row["cofins_id"]?>-<?=$row["cofins_desc"]?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3 col-xs-6">
                                          <?php
                                                    // Verificar se existe NCM quando for zerado ou inválido
                                                    $descricaoNCM = "";
                                                    $NCM = $result["Cod_Class_Fiscal"] ?? '';

                                                    if ($NCM === '00000000' || strlen($NCM) !== 8) {
                                                        $stmt = $pdo->prepare("
                                                            SELECT tabncm_ncm 
                                                            FROM bd_prisma.tab_ncmproduto  
                                                            WHERE tabncm_codfabricante = ? 
                                                            LIMIT 1
                                                        ");
                                                        $stmt->execute([$result["CODIGO_FABRICANTE"] ?? null]);

                                                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $NCM = $row['tabncm_ncm'];
                                                            //update
                                                              $stmt = $pdo->prepare(" UPDATE ". $_SESSION['BASE'] .".itemestoque SET Cod_Class_Fiscal = ?  WHERE CODIGO_FORNECEDOR = ?    LIMIT 1");
                                                               $stmt->execute([$NCM, $_chaveid ?? null]); 
                                                        }
                                                    }

                                                 $stmt = $pdo->prepare("
                                                        SELECT descricao 
                                                        FROM minhaos_cep.impostost 
                                                        WHERE codigoncm = :ncm 
                                                        LIMIT 1
                                                    ");

                                                    $stmt->execute([':ncm' => $NCM ?? null]);
                                                    $retorno = $stmt->fetch(PDO::FETCH_ASSOC);

                                                    // Exemplo de uso:
                                                    if ($retorno) {
                                                        $descricaoNCM = $retorno['descricao'];
                                                    }
                                                                                                     
                                                    ?>


                                            <label for="produto-ncm">Class. Fiscal/NCM:<span style="color:red">*</span></label>
                                            <input type="number" class="form-control" id="produto-ncm" name="produto-ncm" onkeyup="verificaNcm(this.value)" min="1" max="99999999" value="<?=$NCM?>" required>
                                        </div>
                                        <div class="col-md-4 col-xs-6">
                                            <label for="descricao-ncm">Descrição NCM:</label>
                                       
                                            <input class="form-control" name="descricao-ncm" id="descricao-ncm" value="<?=($descricaoNCM )?>" disabled>
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-msgicms">MVA %:</label>
                                            <input type="text" class="form-control" id="produto-mva" name="produto-mva" value="<?=number_format($result["MVA"],2,',','.')?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                            
                                        </div>
                                        <div class="form-group col-md-2 col-xs-2">
                                            <label for="produto-msgicms">Subst.Tributária</label>
                                            <select name="substTributaria" id="substTributaria" class="form-control">
                                                    <option value="0" <?php if($result['IND_SUBTTRIBUTARIA'] == '0') { echo "selected";} ?>>Não</option>
                                                    <option value="1" <?php if($result['IND_SUBTTRIBUTARIA'] == '1') { echo "selected";} ?>>Sim</option>
                                                </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="navpills-41" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-xs-4">
                                            <label for="produto-fornecedor">Fornecedor:</label>
                                            <select name="produto-fornecedor" id="produto-fornecedor" class="form-control">
                                                <option value="0">Selecione</option>
                                                <?php
                                                $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante ORDER BY NOME");
                                                $retorno = $consulta->fetchAll();
                                                foreach ($retorno as $row) {
                                                    ?><option value="<?=$row["CODIGO_FABRICANTE"]?>"><?=$row["NOME"]?></option><?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-xs-8">
                                            <label for="produto-codfornec">Cód. Fornecedor/SKU:</label>
                                            <div class="input-group">
                                                <input type="text" name="produto-codfornec" id="produto-codfornec" class="form-control">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="_incluirFornecedor()">Incluir<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="listagem"></div>
                                </div>
                                <div id="navpills-42" class="tab-pane">                                   
                                    <div class="row" id="listagemhistorico"></div>
                                </div>
                                <div id="navpills-51" class="tab-pane">
                                    <div class="row m-b-10">
                                        <div class="form-group col-md-2">
                                            <label for="produto-ingrediente">É ingrediente:</label>
                                            <select name="produto-ingrediente" id="produto-ingrediente" class="form-control">
                                                <option value="-1" <?=$result["ind_ingrediente"] == -1 ? "selected" : ""?>>Sim</option>
                                                <option value="0" <?=$result["ind_ingrediente"] == 0 ? "selected" : ""?>>Não</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="produto-cingrdiente">Tem ingrediente:</label>
                                            <select name="produto-cingrdiente" id="produto-cingrdiente" class="form-control" onclick="liberaIngrediente()">
                                                <option value="-1" <?=$result["ind_com_ingrediente"] == -1 ? "selected" : ""?>>Sim</option>
                                                <option value="0" <?=$result["ind_com_ingrediente"] == 0 ? "selected" : ""?>>Não</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="produto-qtnreceita">Quant. P/Receita:</label>
                                            <input type="text" class="form-control" name="produto-qtnreceita" id="produto-qtnreceita" value="<?=number_format($result["Qtde_Reserva_tecnica"],3,',','.')?>">
                                        </div>
                                        <div id="ingrediente-incluir" style="display: none;">
                                            <div class="form-group col-md-3">
                                                <label for="ingrdiente-id">Ingrediente:</label>
                                                <select name="ingrdiente-id" id="ingrdiente-id" class="form-control">
                                                    <option value="0" selected>Selecione</option>
                                                    <?php
                                                    $consulta = $pdo->query("SELECT DESCRICAO, CODIGO_FORNECEDOR FROM ". $_SESSION['BASE'] .".itemestoque WHERE ind_ingrediente = '-1' ORDER BY DESCRICAO");
                                                    $retorno = $consulta->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        ?><option value="<?=$row["CODIGO_FORNECEDOR"]?>"><?=$row["DESCRICAO"]?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="ingrediente-qtn">Quant. Ingrediente:</label>
                                                <div class="input-group">
                                                    <input type="text" name="ingrediente-qtn" id="ingrediente-qtn" class="form-control">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="_incluirIngrediente()">Incluir<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="ingrediente-lista" style="display: none;"></div>
                                    </div>
                                </div>
                                <div id="navpills-61" class="tab-pane">
                                    <div class="row">
                                        <div class="row">
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-descricao-emb">Desc. Embalagem:</label>
                                                <input type="text" class="form-control" name="produto-descricao-emb" id="produto-descricao-emb" value="<?=$result["descricao_etiqueta"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-nome-produtor">Nome Produtor:</label>
                                                <input type="text" class="form-control" name="produto-nome-produtor" id="produto-nome-produtor" value="<?=$result["nome_produtor"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-cnpj-produtor">CNPJ Produtor:</label>
                                                <input type="text" class="form-control" name="produto-cnpj-produtor" id="produto-cnpj-produtor" value="<?=$result["cnpj_produtor"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-pesoliquido">Peso Líquido:</label>
                                                <input type="text" class="form-control" name="produto-pesoliquido" id="produto-pesoliquido" value="<?=$result["peso_liquido"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <p class="text-left m-b-5"><strong>Ingredientes:</strong></p>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <input type="text" class="form-control" name="produto-ingrediente-1" id="produto-ingrediente-1" placeholder="1° Linha" value="<?=$result["ingrediente"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <input type="text" class="form-control" name="produto-ingrediente-2" id="produto-ingrediente-2" placeholder="2° Linha" value="<?=$result["ingrediente2"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <input type="text" class="form-control" name="produto-ingrediente-3" id="produto-ingrediente-3" placeholder="3° Linha" value="<?=$result["ingrediente3"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <input type="text" class="form-control" name="produto-ingrediente-4" id="produto-ingrediente-4" placeholder="4° Linha" value="<?=$result["ingrediente4"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-valorcalorico">Valor Calorico:</label>
                                                <input type="text" class="form-control" name="produto-valorcalorico" id="produto-valorcalorico" value="<?=$result["vlr_energecico"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-gorduratrans">Gorduras Trans:</label>
                                                <input type="text" class="form-control" name="produto-gorduratrans" id="produto-gorduratrans" value="<?=$result["vlr_gorudraTrans"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-gordurastotais">Gorduras Totais:</label>
                                                <input type="text" class="form-control" name="produto-gordurastotais" id="produto-gordurastotais" value="<?=$result["vlr_gorduraTotal"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-colesterol">Colesterol:</label>
                                                <input type="text" class="form-control" name="produto-colesterol" id="produto-colesterol" value="<?=$result["vlr_gorduraSaturada"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-carboidratos">Carboidratos:</label>
                                                <input type="text" class="form-control" name="produto-carboidratos" id="produto-carboidratos" value="<?=$result["vlr_carboidrato"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-proteinas">Proteinas:</label>
                                                <input type="text" class="form-control" name="produto-proteinas" id="produto-proteinas" value="<?=$result["vlr_proteina"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-fibra">Fibra Alimentar:</label>
                                                <input type="text" class="form-control" name="produto-fibra" id="produto-fibra" value="<?=$result["vlr_fibra"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-calcio">Calcio:</label>
                                                <input type="text" class="form-control" name="produto-calcio" id="produto-calcio" value="<?=$result["calcio"]?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-ferro">Ferro:</label>
                                                <input type="text" class="form-control" name="produto-ferro" id="produto-ferro" value="<?=$result["ferro"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-sodio">Sódio:</label>
                                                <input type="text" class="form-control" name="produto-sodio" id="produto-sodio" value="<?=$result["vlr_sodio"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-porcao">Porção:</label>
                                                <input type="text" class="form-control" name="produto-porcao" id="produto-porcao" value="<?=$result["porcao"]?>">
                                            </div>
                                            <div class="form-group col-md-3 col-xs-6">
                                                <label for="produto-valorbase">Valor B. Dieta:</label>
                                                <input type="text" class="form-control" name="produto-valorbase" id="produto-valorbase" value="<?=$result["desc_base"]?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div id="navpills-71" class="tab-pane">
                                    <div class="row"></div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="btn-group pull-right">                
                <?=$result == NULL ? "" : "<button type='button' class='btn btn-danger waves-effect waves-light m-l-5' data-toggle='modal' data-target='#custom-modal-excluir' onclick='_idexcluir(".$result['Codigo'].", -1)'><span class='btn-label'><i class='fa fa-times'></i></span>Excluir</button>"?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar-->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar-->
<div id="custom-modal-nf" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div class="bg-icon pull-request" id="resultadoNF">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir-->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir o <b id="nome-exclusao"></b>?</h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" id="btn-excluir" tabindex="1" style="display: inline-block;" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="id-nf" name="id-nf" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">
    <input type="hidden" id="id-ncm" name="id-ncm" value="">
    <input type="hidden" id="id-produto" name="id-produto" value="<?=$result['Codigo']?>">
</form>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/routes.js"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>

<script type="text/javascript">
  
                
    function _fechar() {
        var $_keyid = "PRDLT";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACPRD";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").modal('show').html(result);
            });
    }

    function _buscadadosnf(idproduto,idnf) {
        $('#id-altera').val(idproduto);
        $('#id-nf').val(idnf);
        var $_keyid = "ACPRD";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 9},
            function(result){
                $("#custom-modal-nf").modal('show').html(result);
            });
    }

    
    function _alterarNF(nota, fabricante){
        var $_keyid = "NFENT";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(nota+"|"+fabricante);
        $('#form1').submit();

    }
    function _incluirProduto() {
        var $_keyid = "ACPRDLT";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
            });
    }

    function _alterarProduto() {
        var $_keyid = "ACPRDLT";
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
            });
    }

    function _excluirProduto() {
        var $_keyid = "ACPRDLT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                setTimeout(() => {
                    _fechar();
                }, 3000);
            });
    }

    function _incluirFornecedor() {
        var $_keyid = "ACPRD";
        if ($("#form-inclui").length) {
            var dados = $("#form-inclui :input").serializeArray();
        }
        else {
            var dados = $("#form-altera :input").serializeArray();
        }
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
                _listaFornecedor();
            });
    }
    

    function _listaFornecedor() {
        var $_keyid = "ACPRD";

        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive-fornecedor').DataTable();
            });
    }

        function _listaHistorico() {
                var $_keyid = "ACPRD";

                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#listagemhistorico');

                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 22},
                    function(result){
                        $('#listagemhistorico').html(result);
                       
                    });
            }

   
    function _alteraFornecedor() {
        var $_keyid = "ACPRD";
        var dados = $("#form-altera-fornecedor :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").html(result);
                _listaFornecedor();
            });
    }

    function _excluirFornecedor() {
        var $_keyid = "ACPRD";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _listaFornecedor();
            });
    }

    function _incluirIngrediente() {
        var $_keyid = "ACPRD";
        if ($("#form-inclui").length) {
            var dados = $("#form-inclui :input").serializeArray();
        }
        else {
            var dados = $("#form-altera :input").serializeArray();
        }
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
                listaIngrediente();
            });
    }    

    function listaIngrediente() {
        var $_keyid = "ACPRD";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#ingrediente-lista');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){
                $('#ingrediente-lista').html(result);
                $('#datatable-responsive-ingrediente').DataTable();
            });
    }

    function _excluirIngrediente() {
        var $_keyid = "ACPRD";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
            function(result){
                $("#custom-modal-result").html(result);
                listaIngrediente();
            });
    }

    function _idexcluir(primeiro_id, segundo_id) {
        if (!segundo_id) {
            $('#id-exclusao').val(primeiro_id);
            $('#nome-exclusao').html('Fornecedor');
            $('#btn-excluir').attr('onclick', '_excluirFornecedor()');
        }
        else if (segundo_id != -1) {
            $('#id-exclusao').val(primeiro_id+"-"+segundo_id);
            $('#nome-exclusao').html('Ingrediente');
            $('#btn-excluir').attr('onclick', '_excluirIngrediente()');
        }
        else {
            $('#nome-exclusao').html('Produto');
            $('#btn-excluir').attr('onclick', '_excluirProduto()');
        }
    }

    function liberaIngrediente() {
        var opcao = $('#produto-cingrdiente').val();
        var div_inclui = document.getElementById('ingrediente-incluir');
        var div_lista = document.getElementById('ingrediente-lista');
        if (opcao == -1) {
            div_inclui.style.display = 'block';
            div_lista.style.display = 'block';
            listaIngrediente()
        }
        else {
            div_inclui.style.display = 'none';
            div_lista.style.display = 'none';
        }
    }

    function MaxLength(object) {
        if (object.value.length > object.max) {
            object.value = "";
        }
    }

    function verificaNcm(ncm) {
      
        if (ncm.length === 8) {
            $('#id-ncm').val(ncm);
            var $_keyid = "ACPRD";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 88},
                function(result){                 
                    $('#descricao-ncm').val(result)
                });
        }
        else if(ncm.length > 8){
            $("#produto-ncm").val("");
        }
    }

    function buscaEnderB(_tipo) {
            var $_keyid = "ACPRD";
            $('#_p').val(_tipo);
            if(_tipo == "i"){
                var dados = $("#form-inclui :input").serializeArray();
            }else{
                var dados = $("#form-altera :input").serializeArray();
            }
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 7
                },
                function(result) {                   
                    $("#_enderBcm").html(result);
                    $("#_enderCcm").html('-');
                });
        }

        function buscaEnderC(_tipo) {
            var $_keyid = "ACPRD"; 
            if(_tipo == "i"){
                var dados = $("#form-inclui :input").serializeArray();
            }else{
                var dados = $("#form-altera :input").serializeArray();
            }
            
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 8
                },
                function(result) {                    
                    $("#_enderCcm").html(result);
                });
        }

  


        function recalculaProduto(id, porcentagem) {
    // Pega o valor do custo do produto
    var precoCusto = $('#produto-custo').val();

    // Se porcentagem estiver preenchida
    if (porcentagem !== "") {
        // Converte precoCusto para número flutuante
        precoCusto = parseFloat(precoCusto.replace(',', '.'));

        // Trata a porcentagem, trocando vírgula por ponto e convertendo para número
        porcentagem = parseFloat(porcentagem.replace(',', '.'));

        // Calcula o preço com a porcentagem aplicada
        var preco = precoCusto + (precoCusto * (porcentagem / 100));

        // Formata o preço com duas casas decimais e trocando o ponto por vírgula
        preco = preco.toFixed(2).replace('.', ',');

        // Define o valor do input especificado por 'id'
        $(id).val(preco);
    } else {
        // Se porcentagem não estiver preenchida, define valor 0,00
        $(id).val('0,00');
    }
}

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    //FORMATA MOEDA FINALIZA PAGAMENTO
	function moeda(a, e, r, t) {
		let n = ""
		  , h = j = 0
		  , u = tamanho2 = 0
		  , l = ajd2 = ""
		  , o = window.Event ? t.which : t.keyCode;
		if (13 == o || 8 == o)
			return !0;
		if (n = String.fromCharCode(o),
		-1 == "0123456789".indexOf(n))
			return !1;
		for (u = a.value.length,
		h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
			;
		for (l = ""; h < u; h++)
			-1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
		if (l += n,
		0 == (u = l.length) && (a.value = ""),
		1 == u && (a.value = "0" + r + "0" + l),
		2 == u && (a.value = "0" + r + l),
		u > 2) {
			for (ajd2 = "",
			j = 0,
			h = u - 3; h >= 0; h--)
				3 == j && (ajd2 += e,
				j = 0),
				ajd2 += l.charAt(h),
				j++;
			for (a.value = "",
			tamanho2 = ajd2.length,
			h = tamanho2 - 1; h >= 0; h--)
				a.value += ajd2.charAt(h);
			a.value += r + l.substr(u - 2, u)
		}
		return !1
	}
</script>

</body>
</html>