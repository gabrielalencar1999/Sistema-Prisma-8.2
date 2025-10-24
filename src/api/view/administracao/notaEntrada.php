<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>

<body >
<?php
require_once('navigatorbar.php');
use Database\MySQL;
$pdo = MySQL::acessabd();

$dadosNF = explode('|', $_chaveid);

$consulta = $pdo->query("SELECT *,DATE_FORMAT(NFE_DATAEMIS, '%Y-%m-%d') AS data_emissao, DATE_FORMAT(NFE_DATAENTR, '%Y-%m-%d') AS data_entrada,DATE_FORMAT(NFE_DATAREMES, '%Y-%m-%d') AS data_remessa FROM ".$_SESSION['BASE'].".nota_ent_base INNER JOIN ".$_SESSION['BASE'].".fabricante ON NFE_FORNEC = CODIGO_FABRICANTE WHERE NFE_NRO = '".$dadosNF[0]."' AND NFE_FORNEC = '".$dadosNF[1]."'");
$retornoNF = $consulta->fetch();
$_idnfbase = $retornoNF["NFE_ID"];
$disable = "";
if($retornoNF["NFE_Conferido"] == '-1' or $retornoNF["NFE_CPGOK"] == '-1' and $retornoNF["NFE_ESTOK"] == '-1'){
    
}else{
    $disable = "nao";
}
?>
<div class="wrapper">
    <div class="container" style="width: 95%;">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Cadastro Notas de Entrada</h4>
                <p class="text-muted page-title-alt">Cadastre suas notas de entrada.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button id="voltar" type="button" class="btn btn-default waves-effect waves-light m-l-5" onclick="_fechar()"><span class="btn-label"><i class="fa fa-arrow-left"></i></span>Voltar</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box table-responsive">
                    <div class="panel-body">
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="true">1. Dados da NF</a>
                            </li>
                            <li class="">
                                <a href="#navpills-21" data-toggle="tab" aria-expanded="false" onclick="_listaProdutos();">2. Produtos</a>
                            </li>
                            <li class="">
                                <a href="#navpills-31" data-toggle="tab" aria-expanded="false" onclick="_listaFatura();">3. Fatura</a>
                            </li>
                            <li class="">
                                <a href="#navpills-41" data-toggle="tab" aria-expanded="false" onclick="_resumo();">4. Resumo</a>
                            </li>
                        </ul>
                            <div class="tab-content br-n pn">
                                <!-- Dados da NF -->
                                <div id="navpills-11" class="tab-pane active">
                                    <form action="javascript:void(0)" id="form-nota" name="form-nota" method="post">
                                        <div class="row">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label" for="nf-chave">Chave NFe:</label>
                                            <input class="form-control" id="nf-chave" name="nf-chave" type="number" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["NFE_CHAVE"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-num">N° NFe:</label>
                                            <input id="nf-num" name="nf-num" type="number" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$dadosNF[0]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label " for="nf-fornecedornome">Fornecedor:</label>
                                            <input id="nf-fornecedornome" name="nf-fornecedornome" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["NOME"]?>">
                                            <input type="hidden" name="nf-fornecedor" id="nf-fornecedor" value="<?=$retornoNF["NFE_FORNEC"]?>">
                                            <input type="hidden" name="NFE_ID" id="NFE_ID" value="<?=$retornoNF["NFE_ID"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-empresa">Empresa:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa ORDER BY empresa_nome");
                                            $totalempresa = $statement->rowCount();
                                            $retornoEmp = $statement->fetchAll();
                                            ?>
                                            <select name="nf-empresa" id="nf-empresa" class="form-control" <?=$disable ?  "" : "disabled"?>>
                                            <?php if($totalempresa > 1)  { ?>                                           
                                                <option value="">Selecione</option>
                                                <?php }
                                              
                                                foreach ($retornoEmp as $row) {
                                                    ?>
                                                    <option value="<?=$row["empresa_id"]?>" <?=$row["empresa_id"] == $retornoNF["empresa"] ? "selected" : ""?>><?=$row["empresa_nome"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-operacao">Nat. Operação:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".cfop ORDER BY NAT_CODIGO");
                                            $retornoOp = $statement->fetchAll();
                                          
                                            ?>
                                            <select name="nf-operacao" id="nf-operacao" class="form-control" <?=$disable ?  "" : "disabled"?>>
                                                <option value="">Selecione</option>
                                                <?php
                                                foreach ($retornoOp as $row) {
                                                    ?>
                                                    <option value="<?=$row["ID"]?>" <?=$row["ID"] == $retornoNF["NFe_Cod_Nat_Operacao"] ? "selected" : ""?>><?=$row["NAT_CODIGO"]."-".($row["NAT_DESCRICAO"])?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-emissao">Data de Emissão:</label>
                                            <input id="nf-emissao" name="nf-emissao" type="date" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["data_emissao"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-entrada">Data de Entrada:</label>
                                            <input id="nf-entrada" name="nf-entrada" type="date" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["data_entrada"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-almox">Almoxarifado:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".almoxarifado");
                                            $retornoAl = $statement->fetchAll();
                                            ?>
                                            <select name="nf-almox" id="nf-almox" class="form-control" >
                                                <?php
                                                foreach ($retornoAl as $row) {
                                                    ?>
                                                    <option value="<?=$row["Codigo_Almox"]?>" <?=$row["Codigo_Almox"] == $retornoNF["NFE_ALMOX"] ? "selected" : ""?>><?=$row["Descricao"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-projeto">Pro. C. de Custo:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".projeto ORDER BY projeto_descricao");
                                            $retornoPr = $statement->fetchAll();
                                            ?>
                                            <select name="nf-projeto" id="nf-projeto" class="form-control" <?=$disable ?  "" : "disabled"?>>
                                                <option value="">Selecione</option>
                                                <?php
                                                foreach ($retornoPr as $row) {
                                                    ?>
                                                    <option value="<?=$row["projeto_id"]?>" <?=$row["projeto_id"] == $retornoNF["NFE_EQP_PREFIXO"] ? "selected" : ""?>><?=$row["projeto_descricao"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-numor">N° OS/OR:</label>
                                            <input id="nf-numor" name="nf-numor" type="number" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["NFE_Cod_Fiscal"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                        <label class="control-label" for="nf-grupo">Categoria:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".categoria WHERE ativo_categoria = 0 and tipo_categoria <> '0' ORDER BY descricao_categoria");
                                            $retornoGr = $statement->fetchAll();
                                            ?>
                                            <select name="nf-grupo" id="nf-grupo" class="form-control" onchange="_buscaSelect(this.value, '#contas-despesa')" <?=$disable ?  "" : "disabled"?>>
                                                <option value="0">Selecione</option>
                                                <?php
                                                foreach ($retornoGr as $row) {
                                                    ?>
                                                    <option value="<?=$row["id_categoria"]?>" <?=$row["id_categoria"] == $retornoNF["NFE_GRUPO"] ? "selected" : ""?>><?=$row["descricao_categoria"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6" id="contas-despesa">
                                            <label class="control-label" for="nf-contdespesa">Sub Categoria:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".subcategoria WHERE  ref_subcategoria = '".$retornoNF["NFE_GRUPO"]."' ORDER BY descricao_subcategoria");
                                            $retornoCd = $statement->fetchAll();
                                            ?>
                                            <select name="nf-contdespesa" id="nf-contdespesa" class="form-control" <?=$disable ?  "" : "disabled"?>>
                                                <option value="0"><?=empty($retornoNF["NFE_GRUPO"]) ? "Selecione o Categoria" : "Selecione"?></option>
                                                <?php
                                                if (!empty($retornoNF["NFE_GRUPO"])) {
                                                    foreach ($retornoCd as $row) {
                                                        ?>
                                                          <option value="<?=$row["id_subcategoria"]?>" <?=$row["id_subcategoria"] == $retornoNF["NFE_CCGRUPO"] ? "selected" : ""?>><?=($row["descricao_subcategoria"])?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-codpagamento">Cond. Pgto:</label>
                                            <input id="nf-codpagamento" name="nf-codpagamento" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["NFE_COND_PAGTO"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-serie">Série:</label>
                                            <input id="nf-serie" name="nf-serie" type="number" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["NFE_SERIE"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-baseicms">Base ICMS:</label>
                                            <input id="nf-baseicms" name="nf-baseicms" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_BASEICM"], 2, ',', '.')?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-icms">Valor ICMS:</label>
                                            <input id="nf-icms" name="nf-icms" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_TOTALICM"], 2, ',', '.')?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-ipi">Valor IPI</label>
                                            <input id="nf-ipi" name="nf-ipi" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_TOTALIPI"], 2, ',', '.')?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-iss">Valor ISS:</label>
                                            <input id="nf-iss" name="nf-iss" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_TOTALISS"], 2, ',', '.')?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-frete">Frete:</label>
                                            <input id="nf-frete" name="nf-frete" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_TOTALFRETE"], 2, ',', '.')?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-desconto">Desconto:</label>
                                            <input id="nf-desconto" name="nf-desconto" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_TOTALDESC"], 2, ',', '.')?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-transportadora">Transportadora:</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".fabricante WHERE for_Tipo = '4'");
                                            $retornoTr = $statement->fetchAll();
                                            ?>
                                            <select name="nf-transportadora" id="nf-transportadora" class="form-control" <?=$disable ?  "" : "disabled"?>>
                                                <option value="">Selecione</option>
                                                <?php
                                                foreach ($retornoTr as $row) {
                                                    ?>
                                                    <option value="<?=$row["CODIGO_FABRICANTE"]?>" <?=$row["CODIGO_FABRICANTE"] == $retornoNF["NFE_Cod_Transp"] ? "selected" : ""?>><?=utf8_encode($row["NOME"])?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-numconhecimento">N° Com:</label>
                                            <input id="nf-numconhecimento" name="nf-numconhecimento" type="number" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$retornoNF["NFE_Num_Conhecimento"]?>">
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label class="col-lg-2 control-label " for="nf-valornfe">Total NFe:</label>
                                            <input id="nf-valornfe" name="nf-valornfe" type="text" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=number_format($retornoNF["NFE_TOTALNF"],2,',','.')?>">
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label" for="nf-observacoes">Observações:</label>
                                            <textarea id="nf-observacoes" name="nf-observacoes" type="text" <?=$disable ?  "" : "disabled"?> class="form-control"><?=$retornoNF["NFE_INFOADD"]?></textarea>
                                            <input type="hidden" id="nf-financeiro" name="nf-financeiro">
                                            <input type="hidden" id="nf-indestoque" name="nf-indestoque">
                                         
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <!-- Produtos -->
                                <div id="navpills-21" class="tab-pane">
                                    <div class="card-box table-responsive" id="listagem-produtos"></div>
                                    <span class="badge badge-inverse " style="font-size: 10px;"><i class="fa fa-dot-circle-o"></i > Padrão</span>
                                    <span class="badge badge-warning " style="font-size: 10px;"><i class="fa fa-dot-circle-o"></i> Visualizado</span>
                                    <span class="badge badge-success " style="font-size: 10px;"><i class="fa fa-dot-circle-o"></i> Salvo</span>
                                </div>
                                <!-- Fatura -->
                                <div id="navpills-31" class="tab-pane">
                                    <div class="card-box table-responsive" id="listagem-fatura"></div>
                                </div>
                                <!-- Resumo -->
                                <div id="navpills-41" class="tab-pane">
                                    <div class="row" id="resumo-nota"></div>
                                    <?php
                                    if ($disable) {
                                        ?>
                                        <div class="row text-center" id="nota-salvar">
                                            <div class="row">                                                
                                               
                                                <?php   if ($retornoNF["NFE_Conferido"] == "-1" or $retornoNF["NFE_ESTOK"] == "-1" ) { ?>
                                                                                                    
                                                    <h5><i class="fa fa-circle m-r-5" style="color: #FF7C0A;"></i> Estoque Confirmado</h5>
                                                <?php }else{ ?>
                                                    <input id="checkbox-h" type="checkbox" >                                                
                                                <label for="checkbox-h">
                                                    Confirmar Entrada Estoque 
                                                </label>
                                             
                                              
                                                <?php } ?>
                                            </div>
                                            <div class="row">
                                                <?php   if ($retornoNF["NFE_Conferido"] == "-1" or $retornoNF["NFE_CPGOK"] == "-1" ) { ?>
                                                 
                                                <h5><i class="fa fa-circle m-r-5" style="color: #FF7C0A;"></i>Financeiro Conferido</h5>
                                                <?php }else{ ?>
                                                    <input id="checkbox-f" type="checkbox">
                                                    <label for="checkbox-f">
                                                        Confirmar lançamento Financeiro
                                                    </label>
                                                <?php } ?>
                                              
                                            </div>
                                            <div class="row m-b-30">
                                                <button id="salvar" type="button" class="btn btn-success waves-effect waves-light mb-auto" onclick="_salvar()"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>
                                            </div>
                                        </div>
                                        <div class="row" id="nota-alerta">
                                            <div class="alert alert-warning">
                                                <strong>Atenção!</strong> Só é possível salvar e alterar os dados da nota enquanto não for confirmado as 2 opcões, após aconfirmação a nota será bloqueada.
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal etiqueta -->
<div id="custom-modal-etiqueta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_limpaCamposProduto()">×</button>
                <h4 class="modal-title">Selecione o Modelo </h4>
            </div>
            <div class="modal-body">
            
            <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="formprint" id="formprint">
            <input type="hidden" id="id-notaprint" name="id-notaprint" class="form-control input-sm" value="">
                <div class="row" style="margin-bottom:  10px;" >
                                                        <div class="col-sm-2 " ><label>Formato</label> 
                                                            <select name="filtrarbuscaModelo" id="filtrarbuscaModelo" class="form-control input-sm">
                                                                    <option value="A4"  >A4</option>                                                                   
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-2 " >   <label>Largura (cm)</label> 
                                                             <input type="text" id="largura" name="largura" class="form-control input-sm" value="60" disabled>
                                                        </div>
                                                        <div class="col-sm-2 " ><label> Altura (cm) </label>    
                                                          <input type="text" id="altura" name="altura" class="form-control input-sm" value="20" disabled> 
                                                        </div>
                                                    </div>
                                                    </form>
               
            </div>
                              
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-white " onclick="_imprimir()"><i class="fa fa-print"></i> Visualizar</button>
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" onclick="_limpaCamposProduto()">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar -->
<div id="custom-modal-buscar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_limpaCamposProduto()">×</button>
                <h4 class="modal-title">Buscar Produtos - Nota Entrada</h4>
            </div>
            <div class="modal-body">
                
                <div class="row" style="margin-bottom:  10px;" >
                                                        <div class="col-sm-2 " >
                                                            <select name="filtrarbusca" id="filtrarbusca" class="form-control input-sm">
                                                                    <option value="CODIGO_FABRICANTE"  selected="">Cód.Fabricante</option>
                                                                    <option value="codigobarra">Cód.Barra</option>
                                                                    <option value="CODIGO_FORNECEDOR">Cód. Interno</option>
                                                                    <option value="Codigo_Referencia_Fornec">Cod.Sku</option>
                                                                    <option value="descricao">Descrição</option>   
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-8 " >    
                                                            <input type="text" id="busca-produto" name="busca-produto" class="form-control input-sm" placeholder="Descrição, Cód. Fabricante / Barras / SKU ">
                                                        </div>
                                                        <div class="col-sm-1 " >
                                                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_buscaProduto($('#busca-produto').val())"><i class="fa fa-search"></i></button>
                                                        </div>                       
                                                    </div>
                <div class="row" id="retorno-produto">
                    <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" onclick="_limpaCamposProduto()">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incluir -->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-buscar" aria-hidden="true">×</button>
                <h4 class="modal-title">Incluir Produto - Nota Entrada</h4>
            </div>
            <div class="modal-body">
                <div class="row" id="nota-produto">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Calcular -->
<div id="custom-modal-calcular" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" id="modal-calcula"></div>
</div>

<!-- Modal Fatura -->
<div id="custom-modal-fatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left" id="modal-fatura"></div>
</div>

<!-- Modal Excluir Produto -->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir o produto? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluir();">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir Fatura-->
<div id="custom-modal-excluir-fatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir a fatura? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirFatura();">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;"></div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="id-nota" name="id-nota">
    <input type="hidden" id="id-produto" name="id-produto">
    <input type="hidden" id="total-nota" name="total-nota">
    <input type="hidden" id="id-fornecedor" name="id-fornecedor">
    <input type="hidden" id="id-prodfornec" name="id-prodfornec">
    <input type="hidden" id="id-filtro" name="id-filtro">
    <input type="hidden" id="sel-filtro" name="sel-filtro">
    <input type="hidden" id="id-chave" name="id-chave" value="<?=$_idnfbase;?>">
    <input type="hidden" id="id-exclusao" name="id-exclusao">
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
<script src="assets/js/jquery.realmask.js"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- Counter Up  -->
<script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
<script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<script type="text/javascript">



    function _fechar() {
        var $_keyid = "NFENTLT";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _faturaModal() {
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform: $_keyid,dados:dados, acao: 0},
            function (result){
            $('#modal-fatura').html(result);
            }
        );
    }

    function _cadastraFatura() {
        var $_keyid = "ACNFENT";
        var dados = $("#form-fatura :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform: $_keyid,dados:dados, acao: 1},
            function (result){
                $('#custom-modal-result').html(result);
                _listaFatura();
            }
        );
    }

    function _listaFatura() {
        $('#id-nota').val($('#nf-num').val());
        $('#id-fornecedor').val($('#nf-fornecedor').val());
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem-fatura');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem-fatura').html(result);
                $('#datatable-responsive-fatura').DataTable();
            });
    }

    function _excluirFatura() {
        $('#custom-modal-excluir-fatura').modal('hide');
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        $("#custom-modal-result").modal('show')

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _listaFatura();
            });
    }

    function _buscaSelect(id, retorno) {
        $("#id-filtro").val(id);
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $(retorno).html(result);
            });
    }

    function _resumo() {
        $('#id-nota').val(<?=$dadosNF[0]?>);
        $('#id-fornecedor').val(<?=$dadosNF[1]?>);
        $('#total-nota').val($('#nf-valornfe').val());
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#resumo-nota');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){
                $('#resumo-nota').html(result);
                $('.counterup').counterUp({
                    delay: 60,
                    time: 1200
                });
            });
    }


    function _salvar() {
        $('#nf-indestoque').val("");
        $('#nf-financeiro').val("");

        if($("#checkbox-h").is(':checked')){
            $('#nf-indestoque').val('true');
        }
        if($("#checkbox-f").is(':checked')){
            $('#nf-financeiro').val('true');

            var $_keyid = "ACNFENT";
            var dados = $("#form-nota :input,text ").serializeArray();
            dados = JSON.stringify(dados);
           
            aguardeListagem('#custom-modal-result');
            $('#custom-modal-result').modal('show');

            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
                function(result){
                    $('#custom-modal-result').html(result);
                    if($('#retorno-nota').val() === "true") {
                        $("#form-nota :input").prop("disabled", true);
                        $("#nota-salvar").remove();
                        $("#nota-alerta").remove();
                    }
                });
        } else {
            var $_keyid = "ACNFENT";
            var dados = $("#form-nota :input,text").serializeArray();
            dados = JSON.stringify(dados);
           
            aguardeListagem('#custom-modal-result');
            $('#custom-modal-result').modal('show');

            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
                function(result){
                    $('#custom-modal-result').html(result);

                   // if($('#retorno-nota').val() === "true") {
                     //   $('#nf-almox').prop("disabled", true);
                   // }
                });
        }
    }

    function _calculaModal(id,_chave,_corstatus) {
        if(_corstatus == 'inverse') {
            _corstatus = 'warning';
        }
        $('#id-filtro').val(id);
        $('#id-chave').val(_chave);        
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $('#modal-calcula').html(result);
                $('#id_'+_chave).html('<span class="badge badge-'+_corstatus+' m-l-0" style="font-size: 12px;"><i class="fa fa-dot-circle-o"></i></span> ');
            }
        );
    }
    

    function _trocaProduto(_CHAVE,IDSIMILAR) {      
        var $_keyid = "ACNFENTPR";        
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#modal-calcula');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 8},
            function(result){             
                _calculaModal(result);
                _listaProdutos();
            });
    }


    function _adicionaProduto() {
        $('#custom-modal-incluir').modal('hide');
        var $_keyid = "ACNFENTPR";
        var dados = $("#form-produto :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
                _listaProdutos();
            });
    }
    
    function _listaProdutos() {
        $('#id-nota').val($('#nf-num').val());
        $('#id-fornecedor').val($('#nf-fornecedor').val());
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem-produtos');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem-produtos').html(result);
                $('#datatable-responsive-produtos').DataTable();
            });
    }

    function _alteraProduto() {
        var $_keyid = "ACNFENTPR";
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").html(result);
                
                $('#id_'+ $('#id-chave').val()).html('<span class="badge badge-success m-l-0" style="font-size: 12px;"><i class="fa fa-dot-circle-o"></i></span> ');
            });
    }

    function _idexcluir(id, modal) {
        if (modal) {
            $('#custom-modal-excluir').modal('show');
        }
        else {
            $('#custom-modal-excluir-fatura').modal('show');
        }
        $('#id-exclusao').val(id);
        $('#id-nota').val($('#nf-num').val());
        $('#id-fornecedor').val($('#nf-fornecedor').val());
    }

    function _excluir() {
        $('#custom-modal-excluir').modal('hide')
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        $("#custom-modal-result").modal('show');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _listaProdutos();
            });
    }

    function _buscaProduto(valor) {

        $("#id-filtro").val(valor);
        $("#sel-filtro").val( $("#filtrarbusca").val());
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){
                $("#retorno-produto").html(result);
                $('#datatable-responsive-produtos-busca').DataTable();
            });
    }

    function _buscaDadosProd(id, fabricante) {
        $('#custom-modal-buscar').modal('hide');
        $("#id-produto").val(id);
        $("#id-prodfornec").val(fabricante);
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
            function(result){
                $('#custom-modal-incluir').modal('show');
                $("#nota-produto").html(result);
            });
    }

    function _limpaCamposProduto() {
        _buscaProduto(-1);
        $("#busca-produto").val("");
        $("#descricao").val("");
        $("#produto-descricao").val("");
        $("#produto-id").val("");
        $("#produto-quantidade").val("");
        $("#produto-fornecedor").val("");
        $("#produto-ipi").val("");
        $("#produto-nota").val("");
        $("#produto-valor").val("");
    }

    function recalculaProduto(id, porcentagem, precoCusto) {

        if (porcentagem !== "") {
            precoCusto = parseFloat(precoCusto);
            porcentagem = porcentagem.toString().replace(',','.');
            porcentagem = parseFloat(porcentagem);
            var preco = precoCusto + (precoCusto * (porcentagem / 100));
            preco = preco.toFixed(2);
            preco = preco.toString().replace('.',',');
            $(id).val(preco);
        }
        else {
            $(id).val('0,0');
        }
    }

    
    function _imprimir() {
        
        $('#id-notaprint').val($('#id-chave').val());
         document.getElementById('formprint').action = 'EtiquetaPDF.php';    
            $('#formprint').attr('target', '_blank');
            $("#formprint").submit();

            document.getElementById('formprint').action = 'javascript:void(0)';  
    } 

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
</script>

</body>
</html>