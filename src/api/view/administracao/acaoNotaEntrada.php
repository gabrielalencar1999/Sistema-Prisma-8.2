<?php include("../../api/config/iconexao.php");

use Database\MySQL;
use Functions\APIecommerce;
$pdo = MySQL::acessabd();
use Functions\Estoque;


/*
 * Função para limpar variáveis, caso necessário
 * */
function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

$loginuser = $_SESSION['tecnico'];
$loginNOME = $_SESSION['APELIDO'];

$consultaPar = $pdo->query("SELECT Ind_Gera_Treinamento FROM ".$_SESSION['BASE'].".parametro");
						$retPar = $consultaPar->fetch(PDO::FETCH_OBJ);				
						$Ind_Gera_Treinamento =  $retPar->Ind_Gera_Treinamento;

/*
 * Chama modal de fatura
 * */
if ($acao["acao"] == 0) {
    ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Cadastro de Fatura</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-fatura" id="form-fatura">
                    <div class="form-group clearfix col-md-2">
                        <label class="control-label " for="fatura-tipopagamento">Tipo Pgto:</label>
                        <?php
                        $statement = $pdo->query("SELECT * from ".$_SESSION['BASE'].".tiporecebimpgto");
                        $pagamento = $statement->fetchAll();
                        ?>
                        <select name="fatura-tipopagamento" id="fatura-tipopagamento" class="form-control">
                            <option value="">Selecione</option>
                            <?php
                            foreach ($pagamento as $row) {
                                ?>
                                <option value="<?=$row["id"]?>"><?=$row["nome"]?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="hidden" name="fatura-nota" id="fatura-nota" value="<?=$_parametros["id-nota"]?>">
                    </div>
                    <div class="form-group clearfix col-md-3">
                        <label class="control-label " for="fatura-caixa">Caixa:</label>
                        <?php
                        $statement = $pdo->query("SELECT Livro_Numero,Descricao FROM ".$_SESSION['BASE'].".livro_caixa_numero ORDER BY ind_caixa,Descricao");
                        $caixa = $statement->fetchAll();
                        ?>
                        <select name="fatura-caixa" id="fatura-caixa" class="form-control">
                            <option value="">Selecione</option>
                            <?php
                            foreach ($caixa as $row) {
                                ?>
                                <option value="<?=$row["Livro_Numero"]?>"><?=$row["Descricao"]?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="hidden" name="fatura-fornecedor" id="fatura-fornecedor" value="<?=$_parametros["id-fornecedor"]?>">
                    </div>
                    <div class="form-group clearfix col-md-3">
                        <label class="control-label " for="fatura-vencimento">Vencimento:</label>
                        <input id="fatura-vencimento" name="fatura-vencimento" type="date" class="form-control" value="<?=date("Y-m-d", strtotime('+1 month'))?>">
                    </div>
                    <div class="form-group clearfix col-md-4">
                        <label class="control-label" for="fatura-valor">Valor:</label>
                        <div class="input-group">
                            <input id="fatura-valor" name="fatura-valor" type="text" class="form-control">
                            <div class="input-group-btn">
                                <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_cadastraFatura()">Cadastrar<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
/*
 * Cria Parcela Fatura
 * */
else if ($acao["acao"] == 1) {
    if ( empty($_parametros["fatura-vencimento"]) || empty($_parametros["fatura-valor"])) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Preencha todas as informações da Fatura!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $consulta = $pdo->query("SELECT  max(nfe_parc) AS PARCELA FROM ". $_SESSION['BASE'] .".nota_ent_pgto WHERE NFE_NRO = '".$_parametros["fatura-nota"]."' AND NFE_FORNEC = '".$_parametros["fatura-fornecedor"]."'");
            $parcela = $consulta->fetch();
            $parcela = intval($parcela["PARCELA"]) + 1;

            $_parametros["fatura-valor"] = LimpaVariavel($_parametros["fatura-valor"]);

            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".nota_ent_pgto (NFE_FORNEC,NFE_NRO,NFE_PARC,NFE_DATAVENC,NFE_VALOR,NFE_CAIXA,NFE_CAIXADEBITO) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bindParam(1, $_parametros["fatura-fornecedor"]);
            $statement->bindParam(2, $_parametros["fatura-nota"]);
            $statement->bindParam(3, $parcela);
            $statement->bindParam(4, $_parametros["fatura-vencimento"]);
            $statement->bindParam(5, $_parametros["fatura-valor"]);
            $statement->bindParam(6, $_parametros["fatura-tipopagamento"]);
            $statement->bindParam(7, $_parametros["fatura-caixa"]);
            $statement->execute();
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Fatura Cadastrada!</h2>
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
 * Lista Faturas
 * */
else if ($acao["acao"] == 2) {
    $contultaNF = $pdo->query("SELECT NFE_Conferido,NFE_ESTOK,NFE_CPGOK  FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
    $retornoNF = $contultaNF->fetch();

    $statement = $pdo->query("SELECT *,DATE_FORMAT(NFE_DATAVENC , '%d/%m/%Y') AS data_vencimento FROM ".$_SESSION['BASE'].".nota_ent_pgto LEFT JOIN ".$_SESSION['BASE'].".bancos ON BCO_ID = NFE_PORTADOR LEFT JOIN ".$_SESSION['BASE'].".tiporecebimpgto ON NFE_CAIXA = id LEFT JOIN ".$_SESSION['BASE'].".livro_caixa_numero ON Livro_Numero = NFE_CAIXADEBITO WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."' ORDER BY NFE_PARC");
    $retorno = $statement->fetchAll();
    $totalFatura = 0.0;
 ?>
    <table id="datatable-responsive-fatura" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <?php
         if ($retornoNF["NFE_Conferido"] == "-1" or $retornoNF["NFE_CPGOK"] == "-1" ) { 
         }else{
            ?>
            <div class="row text-right">
                <button id="cadastrar-fatura" type="button" class="btn btn-success waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-fatura" onclick="_faturaModal()">Incluir Fatura<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
            </div>
            <?php
        }
        ?>
        <thead>
        <tr>
            <th>Parcela</th>
            <th>Vencimento</th>
            <th class="text-center">Tipo Pgto</th>
            <th class="text-center">Débito Caixa</th>
            <th class="text-center">Valor</th>
            <th class='text-right'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td><?=$row["NFE_PARC"]?></td>
                <td><?=$row["data_vencimento"]?></td>
                <td class="text-center"><?=$row["nome"]?></td>
                <td class="text-center"><?=$row["Descricao"]?></td>
                <td class="text-center"><?=number_format($row["NFE_VALOR"],2,',','.')?></td>
            <?php
            if ($retornoNF["NFE_Conferido"] == "-1" or $row["NFE_LANCADO"] == "-1") { ?>
           
                <td class="actions text-right">
                     <span class="text-success"><i class="fa fa-2x   fa-check"></i></span>
                </td>
                   
            <?php
            }else{
                ?>
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idexcluir(<?=$row["NFE_PARC"]?>, false)"><i class="fa fa-trash-o fa-2x"></i></a>
                </td>
                <?php
            }
            ?>
            </tr>
            <?php
            $totalFatura += $row["NFE_VALOR"];
        }
        ?>
        </tbody>
    </table>
    <div class="alert alert-info text-right">
        Total <strong>R$<?=number_format($totalFatura, 2, ',', '.')?></strong>
    </div>
<?php
}
/*
 * Editar nota
 * */
else if ($acao["acao"] == 3) {
    if(empty($_parametros["nf-emissao"]) || empty($_parametros["nf-valornfe"])) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe
                            <?php
                                if(empty($_parametros["nf-emissao"]) && empty($_parametros["nf-valornfe"])) {
                                    echo "a Emissão e Valor da Nota!";
                                }
                                else if(empty($_parametros["nf-entrada"])) {
                                    echo "a Data de Entrada da Nota!";
                                }
                                else if(empty($_parametros["nf-emissao"])) {
                                    echo "a Data de Emissão da Nota!";
                                }
                                else {
                                    echo "o Valor Total da Nota!";
                                }
                            ?></h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        <input type="hidden" id="retorno-nota" name="retorno-nota" value="false">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            date_default_timezone_set('America/Sao_Paulo');
            $data_emissao = $_parametros["nf-emissao"];
            $_parametros["nf-emissao"] = date("".$_parametros["nf-emissao"]." H:i:s", time());
            $_parametros["nf-entrada"] = date("".$_parametros["nf-entrada"]." H:i:s", time());
            $_parametros["nf-ipi"] = LimpaVariavel($_parametros["nf-ipi"]);
            $_parametros["nf-baseicms"] = LimpaVariavel($_parametros["nf-baseicms"]);
            $_parametros["nf-icms"] = LimpaVariavel($_parametros["nf-icms"]);
            $_parametros["nf-frete"] = LimpaVariavel($_parametros["nf-frete"]);
            $_parametros["nf-iss"] = LimpaVariavel($_parametros["nf-iss"]);
            $_parametros["nf-desconto"] = LimpaVariavel($_parametros["nf-desconto"]);
            $_parametros["nf-valornfe"] = LimpaVariavel($_parametros["nf-valornfe"]);
            $numdocumento = $_parametros["nf-num"]."-".$_parametros["nf-fornecedor"];
           // $NFE_ESTOK = "-1";
            $movimento = "E";
            $inventario = "0";
            $motivo = "Entrada por NF";
            $datamov = date("Y-m-d H:i:s", time());

            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_base SET  NFE_FORNEC = ?, NFE_NRO = ?, NFE_SERIE = ?, NFE_DATAEMIS = ?, NFE_COND_PAGTO = ?, NFE_ALMOX = ?, NFE_DATAENTR = ?, NFE_TOTALIPI = ?, NFE_BASEICM = ?, NFE_TOTALICM = ?, NFE_TOTALFRETE = ?, NFE_TOTALISS = ?, NFE_TOTALNF = ?, NFE_INFOADD = ?, NFE_CCGRUPO = ?,  NFE_EQP_PREFIXO = ?, NFE_TOTALDESC = ?, NFe_Cod_Nat_Operacao = ?, NFE_Cod_Fiscal = ?, NFE_Cod_Transp = ?, NFE_Num_Conhecimento = ?, empresa = ?, NFE_GRUPO = ?, NFE_CHAVE = ? WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
            $statement->bindParam(1, $_parametros["nf-fornecedor"]);
            $statement->bindParam(2, $_parametros["nf-num"]);
            $statement->bindParam(3, $_parametros["nf-serie"]);
            $statement->bindParam(4, $_parametros["nf-emissao"]);
            $statement->bindParam(5, $_parametros["nf-codpagamento"]);
            $statement->bindParam(6, $_parametros["nf-almox"]);
            $statement->bindParam(7, $_parametros["nf-entrada"]);
            $statement->bindParam(8, $_parametros["nf-ipi"]);
            $statement->bindParam(9, $_parametros["nf-baseicms"]);
            $statement->bindParam(10, $_parametros["nf-icms"]);
            $statement->bindParam(11, $_parametros["nf-frete"]);
            $statement->bindParam(12, $_parametros["nf-iss"]);
            $statement->bindParam(13, $_parametros["nf-valornfe"]);
            $statement->bindParam(14, $_parametros["nf-observacoes"]);
            $statement->bindParam(15, $_parametros["nf-contdespesa"]);         
            $statement->bindParam(16, $_parametros["nf-projeto"]);
            $statement->bindParam(17, $_parametros["nf-desconto"]);
            $statement->bindParam(18, $_parametros["nf-operacao"]);
            $statement->bindParam(19, $_parametros["nf-numor"]);
            $statement->bindParam(20, $_parametros["nf-transportadora"]);
            $statement->bindParam(21, $_parametros["nf-numconhecimento"]);
            $statement->bindParam(22, $_parametros["nf-empresa"]);
            $statement->bindParam(23, $_parametros["nf-grupo"]);
            $statement->bindParam(24, $_parametros["nf-chave"]);
            $statement->bindParam(25, $_parametros["nf-num"]);
            $statement->bindParam(26, $_parametros["nf-fornecedor"]);
            $statement->execute();

            $categoria = $_parametros["nf-grupo"];
            $subcategoria = $_parametros["nf-contdespesa"];
            $totaregitem = 0;
            if ($_parametros["nf-indestoque"] == 'true') {

                    $consultaItens = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".nota_ent_item  WHERE NFE_NRO = '".$_parametros["nf-num"]."' AND NFE_FORNEC = '".$_parametros["nf-fornecedor"]."' AND NFE_ESTOK = '0'");
                    $retorno = $consultaItens->fetchAll();

                    foreach ($retorno as $row) {
                        $iditem = $row["NFE_CODIGO"];
                    
                        $qtde = $row["NFE_QTDADE"];
                        $valor = $row["NFE_VLRUNI"];
                        $total = $row["NFE_TOTALITEM"];
                        $_status = $row["nfitemstatus"];
                        $_cfop = $row["NFE_CFOP"];

                        $consultaQuantidade = $pdo->query("SELECT Qtde_Disponivel,PRECO_CUSTO,customedio,Tab_Preco_5, Tab_Preco_4,Tab_Preco_3,Tab_Preco_2,Tab_Preco_1
                        FROM ". $_SESSION['BASE'] .".itemestoquealmox INNER JOIN ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR = Codigo_Item  WHERE Codigo_Item  = '".$iditem."' AND Codigo_Almox = '".$_parametros["nf-almox"]."'");
                        $retornoQuantidade = $consultaQuantidade->fetch();
                      
                    
                        if ($retornoQuantidade == null) {
                            $insereAlmox = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".itemestoquealmox (Qtde_Disponivel,Codigo_Item,Codigo_Almox) VALUES (?, ?, ?)");
                            $insereAlmox->bindParam(1, $qtde);
                            $insereAlmox->bindParam(2,$iditem);
                            $insereAlmox->bindParam(3,$_parametros["nf-almox"]);
                        
                            $insereAlmox->execute();
                        }

                    
                        $qtde_A = $retornoQuantidade["Qtde_Disponivel"];
                        $custo_A = $retornoQuantidade["customedio"];
                        $custo = $retornoQuantidade["PRECO_CUSTO"];
                        $qtde_atual = $retornoQuantidade["Qtde_Disponivel"] + $qtde;
                        
                        $custo_A > 0.01 ?: $custo_A = $custo;
                        $vl_A =  $qtde_A * $custo_A;
                        $vl_B = $qtde * $valor;
                        $customedio = (($vl_A+$vl_B)/$qtde_atual);
                        $customedio = number_format($customedio, 2, '.', '');

                            //VERIFICAR TABELA CFOP VENDA PARA ATUALIZAR CUSTO NA COMPRA
                        $sql_cfop = "SELECT cfopvenda FROM bd_prisma.cfop_venda WHERE cfopvenda = '".$_cfop."'";
                        $stmCfop = $pdo->prepare(" $sql_cfop ");            
                        $stmCfop->execute();

                       
                      //  if($_cfop == "5102 " OR  $_cfop == "6102" OR  $_cfop == "3102" OR  $_cfop == "5405" OR $_cfop == "6405"  OR $_cfop == "6401"  OR $_cfop == "6401" OR $_cfop == "6101" OR $_cfop == "6403" OR $_cfop == "5403" ) { 
                        if ($stmCfop->rowCount() > 0 ){                
                                        if($_status != 2) { //2 é que foi salvo
                                        //historico
                                            $_parametros2 = array(                
                                                'tipo' =>'3',
                                                'login' =>$loginuser,
                                                'loginnome' => $loginNOME,
                                                'codigoitem' =>$iditem,
                                                'vlrcustoatual' =>$valor,
                                                'vlrvendaatual' =>$retornoQuantidade["Tab_Preco_5"],
                                                'vendaAnterior' =>$retornoQuantidade["Tab_Preco_5"],
                                                'custoAnterior' =>$custo,
                                                'vlrcustoTab1'=>0,                 
                                                'vlrcustoTab2'=>0, 
                                                'vlrcustoTab3'=>0, 
                                                'vlrcustoTab4'=>0, 
                                                'vlrcustoAnTab1'=>$retornoQuantidade["Tab_Preco_1"], 
                                                'vlrcustoAnTab2'=>$retornoQuantidade["Tab_Preco_2"], 
                                                'vlrcustoAnTab3'=>$retornoQuantidade["Tab_Preco_3"], 
                                                'vlrcustoAnTab4'=>$retornoQuantidade["Tab_Preco_4"] 
                                            );
                                        
                                            $ret =  Estoque::gravarAlteracaoPrecoCadastro($_parametros2);
                                    
                                        $updateItemEstoque =  $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".itemestoque SET PRECO_CUSTO = ?, VALOR_ULT_ENTRADA = ?, DATA_ULT_ENTRADA = ?, customedio = ? WHERE CODIGO_FORNECEDOR = ?");
                                        $updateItemEstoque->bindParam(1, $valor);
                                        $updateItemEstoque->bindParam(2, $valor);
                                        $updateItemEstoque->bindParam(3, $_parametros["nf-entrada"]);
                                        $updateItemEstoque->bindParam(4, $customedio);
                                        $updateItemEstoque->bindParam(5, $iditem);
                                        $updateItemEstoque->execute();
                                      
                                    }else{                               

                                        $updateItemEstoque =  $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".itemestoque SET VALOR_ULT_ENTRADA = ?, DATA_ULT_ENTRADA = ?, customedio = ? WHERE CODIGO_FORNECEDOR = ?");
                                        $updateItemEstoque->bindParam(1, $valor);
                                        $updateItemEstoque->bindParam(2, $_parametros["nf-entrada"]);
                                        $updateItemEstoque->bindParam(3, $customedio);
                                        $updateItemEstoque->bindParam(4, $iditem);
                                        $updateItemEstoque->execute();                                      
                                        
                                    }
                        }
                   
                        $updateItemFornecedor =  $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".itemestoquealmox SET Qtde_Disponivel = '".$qtde_atual."' WHERE Codigo_Item  = '".$iditem."' AND Codigo_Almox = '".$_parametros["nf-almox"]."'");
                        $updateItemFornecedor->execute();
                      

                        if($Ind_Gera_Treinamento == 1) {									
                            $retapp = APIecommerce::bling_saldoEstoque($iditem,$valor,0,$qtde, "E","NF-e");	
                        }
                        $sql = $sql."UPDATE itemestoquealmox SET Qtde_Disponivel = '".$qtde_atual."' WHERE Codigo_Item  = '".$iditem."' AND Codigo_Almox = '".$_parametros["nf-almox"]."'";
                    //  $sql =  "UPDATE itemestoquealmox SET Qtde_Disponivel = '$qtde_atual' WHERE Codigo_Item  = '$iditem' AND Codigo_Almox = '".$_parametros["nf-almox"]."'";

                        $updateItemFornecedor =  $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".itemestoquefornecedor SET valor_ult_compra = ?, data_ult_compra = ? WHERE codigo_item = ? AND codigo_fabricante = ?");
                        $updateItemFornecedor->bindParam(1, $valor);
                        $updateItemFornecedor->bindParam(2, $_parametros["nf-entrada"]);
                        $updateItemFornecedor->bindParam(3, $iditem);
                        $updateItemFornecedor->bindParam(4, $_parametros["nf-fornecedor"]);
                        $updateItemFornecedor->execute();
                        $NFE_ESTOK = "-1";
                        $totaregitem = 1;

                        $updateNotaItem =  $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_item SET NFE_ESTOK = ? WHERE NFE_CODIGO  = ? AND NFE_NRO = ? AND NFE_FORNEC = ?");
                        $updateNotaItem->bindParam(1, $NFE_ESTOK);
                        $updateNotaItem->bindParam(2, $iditem);
                        $updateNotaItem->bindParam(3,$_parametros["nf-num"]);
                        $updateNotaItem->bindParam(4,$_parametros["nf-fornecedor"]);
                        $updateNotaItem->execute();

                        $insertMovimento = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".itemestoquemovto (Codigo_Item, Qtde, Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Vl_Custo_medio, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $insertMovimento->bindParam(1, $iditem);
                        $insertMovimento->bindParam(2,$qtde);
                        $insertMovimento->bindParam(3,$_parametros["nf-almox"]);
                        $insertMovimento->bindParam(4, $movimento);
                        $insertMovimento->bindParam(5, $movimento);
                        $insertMovimento->bindParam(6, $customedio);
                        $insertMovimento->bindParam(7, $numdocumento);
                        $insertMovimento->bindParam(8, $valor);
                        $insertMovimento->bindParam(9, $inventario);
                        $insertMovimento->bindParam(10, $total);
                        $insertMovimento->bindParam(11, $_SESSION["tecnico"]);
                        $insertMovimento->bindParam(12, $motivo);
                        $insertMovimento->bindParam(13, $qtde_atual);
                        $insertMovimento->bindParam(14, $datamov);
                        $insertMovimento->execute();
                    }

                    if($totaregitem == 1) {                    
                        $consultaNF = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_base SET NFE_ESTOK = ? WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
                        $consultaNF->bindParam(1, $NFE_ESTOK);                
                        $consultaNF->bindParam(2, $_parametros["nf-num"]);
                        $consultaNF->bindParam(3, $_parametros["nf-fornecedor"]);
                        $consultaNF->execute();
                    }
                }

            
          

            if ($_parametros["nf-financeiro"] == 'true') {

                $consultaParcelas = $pdo->query("SELECT max(nfe_parc) AS PARCELA FROM ". $_SESSION['BASE'] .".nota_ent_pgto WHERE NFE_NRO = '".$_parametros["nf-num"]."' AND NFE_FORNEC = '".$_parametros["nf-fornecedor"]."'");
                $retornoParcelas = $consultaParcelas->fetch();

                $total_parcela = $retornoParcelas["PARCELA"];
                $referencia = "Ref. A Nota Fiscal ".$_parametros["nf-num"];
                $situacaoA = 1;
                $situacaoB = 2;
                $conferidoA = 0;                
                $conferidoB = -1;
              
                

                $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".nota_ent_pgto WHERE NFE_NRO = '".$_parametros["nf-num"]."' AND NFE_FORNEC = '".$_parametros["nf-fornecedor"]."' AND NFE_LANCADO = '0'");
                $retorno = $consulta->fetchAll();

                foreach ($retorno as $row) {
                    $parcela = $row["NFE_PARC"];
                    $documento = $row["NFE_PARC"];
                    $portador= $row["NFE_PORTADOR"];
                    $data_vencimento = $row["NFE_DATAVENC"];
                    $valor_documento = $row["NFE_VALOR"];
                    $tipopgto = $row["NFE_CAIXA"];
                    $caixa = $row["NFE_CAIXADEBITO"];
                    $NFE_FINOK = "-1";
                 

                    $updatePgto = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_pgto SET NFE_LANCADO = ? WHERE NFE_PARC  = ? AND NFE_NRO = ? AND NFE_FORNEC = ?");
                    $updatePgto->bindParam(1, $NFE_FINOK);
                    $updatePgto->bindParam(2, $parcela);
                    $updatePgto->bindParam(3, $_parametros["nf-num"]);
                    $updatePgto->bindParam(4, $_parametros["nf-fornecedor"]);
                    $updatePgto->execute();

                    $consultaLiquida = $pdo->query("SELECT Ind_liquida, Num_Livro FROM ". $_SESSION['BASE'] .".tiporecebimpgto WHERE id = '$tipopgto'");
                    $retornoLiquida = $consultaLiquida->fetch();
                    $ind_liquida =  $retornoLiquida['Ind_liquida'];
                    $ind_livro = $retornoLiquida['Num_Livro'];

                    if($ind_liquida != "S") {
                

                        $descricao = $referencia;
                        $sql="insert into ".$_SESSION['BASE'].".financeiro (financeiro_parcela,financeiro_totalParcela,financeiro_codigoCliente,financeiro_nome,financeiro_documento,financeiro_historico,financeiro_emissao,financeiro_vencimento,financeiro_vencimentoOriginal,financeiro_valor,financeiro_situacaoID,financeiro_tipo,financeiro_grupo,financeiro_subgrupo) values (?,?,?,?,?,?,NOW(),?,?,?,'0','1',?,?)";
                        $stm = $pdo->prepare($sql);	
                        $stm->bindParam(1,$parcela);
                        $stm->bindParam(2,$total_parcela);
                        $stm->bindParam(3,$_parametros["nf-fornecedor"]);
                        $stm->bindParam(4,$_parametros["nf-fornecedornome"]);
                        $stm->bindParam(5,$_parametros["nf-num"]);
                        $stm->bindParam(6,$descricao);
                        $stm->bindParam(7,$data_vencimento);
                        $stm->bindParam(8,$data_vencimento);
                        $stm->bindParam(9,$valor_documento);    
                        $stm->bindParam(10,$categoria);
                        $stm->bindParam(11,$subcategoria);                     
		                $stm->execute();	
                        $totaregfatura = 1;
                        
                    }
                    else {
                     

                        $descricao = $referencia;
                        $sql="insert into ".$_SESSION['BASE'].".financeiro (financeiro_parcela,financeiro_totalParcela,financeiro_codigoCliente,financeiro_nome,financeiro_documento,financeiro_historico,financeiro_emissao,financeiro_vencimento,financeiro_vencimentoOriginal,financeiro_valor,financeiro_dataFim,financeiro_valorFim,financeiro_situacaoID,financeiro_tipo,financeiro_grupo,financeiro_subgrupo) values (?,?,?,?,?,?,NOW(),?,?,?,NOW(),?,'0','1',?,?)";
                        $stm = $pdo->prepare($sql);	
                        $stm->bindParam(1,$parcela);
                        $stm->bindParam(2,$total_parcela);
                        $stm->bindParam(3,$_parametros["nf-fornecedor"]);
                        $stm->bindParam(4,$_parametros["nf-fornecedornome"]);
                        $stm->bindParam(5,$_parametros["nf-num"]);
                        $stm->bindParam(6,$descricao);
                        $stm->bindParam(7,$data_vencimento);
                        $stm->bindParam(8,$data_vencimento);
                        $stm->bindParam(9,$valor_documento);
                        $stm->bindParam(10,$valor_documento);
                        $stm->bindParam(11,$categoria);
                        $stm->bindParam(12,$subcategoria);   
		                $stm->execute();
                        $totaregfatura = 1;

                        if (intval($ind_livro) > 0) {
                            $data_lancamento = date("Y-m-d H:m:s", time());
                            $historico = "Ref. NF ".$_parametros["nf-num"]." - ".$_parametros["nf-fornecedornome"]."";

                            $inserLivro = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".livro_caixa (Livro_Numero, Livro_caixa_historico, Livro_caixa_valor_entrada, Livro_caixa_valor_saida, Livro_caixa_data_lancamento, Livro_caixa_data_hora_lancamento, Livro_caixa_usuario_lancamento, Livro_conta, Livro_caixa_Cod_Pagamento, Livro_Num_Docto, Livro_caixa__data_hora_alterado, Livro_idfinanceiro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $inserLivro->bindParam(1, $ind_livro);
                            $inserLivro->bindParam(2, $historico);
                            $inserLivro->bindParam(3, $conferidoA);
                            $inserLivro->bindParam(4, $valor_documento);
                            $inserLivro->bindParam(5, $data_vencimento);
                            $inserLivro->bindParam(6, $data_lancamento);
                            $inserLivro->bindParam(7, $_SESSION["IDUSER"]);
                            $inserLivro->bindParam(8, $_parametros["nf-contdespesa"]);
                            $inserLivro->bindParam(9, $tipopgto);
                            $inserLivro->bindParam(10, $_parametros["nf-num"]);
                            $inserLivro->bindParam(11, $data_vencimento);
                            $inserLivro->bindParam(12, $conferidoB);
                            //$inserLivro->execute();
                        }
                    }
                }
                if($totaregfatura == 1) {
                    $consultaNF = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_base SET  NFE_CPGOK = ? WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
                    $consultaNF->bindParam(1, $NFE_FINOK);                  
                    $consultaNF->bindParam(2, $_parametros["nf-num"]);
                    $consultaNF->bindParam(3, $_parametros["nf-fornecedor"]);
                    $consultaNF->execute();
                }
                

               
            }
           
            /*
            $NFE_OK = '0'; $NFE_ESTOK  $NFE_FINOK
            if( $totaregitem == 0 and  $totaregfatura == 0 or $totaregitem == 0 and  $totaregfatura == 1 or  $totaregitem == 1 and  $totaregfatura == 1 ) {
                if ($_parametros["nf-financeiro"] == 'true' or  $_parametros["nf-indestoque"] == 'true') {  
                  $NFE_OK = '-1';
                }
            }
           
        
            $consultaNF = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_base SET NFE_ESTOK = ?, NFE_LANCADO = ? WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
            $consultaNF->bindParam(1, $NFE_ESTOK);
            $consultaNF->bindParam(2, $NFE_FINOK);
            $consultaNF->bindParam(3, $_parametros["nf-num"]);
            $consultaNF->bindParam(4, $_parametros["nf-fornecedor"]);
            $consultaNF->execute();
            */
            
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">                          
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Nota Atualizada!  </h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
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
                        <input type="hidden" id="retorno-nota" name="retorno-nota" value="false">
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Exclui Fatura
 * */
else if ($acao["acao"] == 4) {
    $consultaNF = $pdo->query("SELECT NFE_Conferido FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
    $resultNF = $consultaNF->fetch();

    try {
        $statement = $pdo->prepare("DELETE FROM ".$_SESSION['BASE'].".nota_ent_pgto WHERE NFE_PARC = '".$_parametros["id-exclusao"]."' AND NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Fatura Excluída!</h2>
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
 * Busca select contas connforme escolha do usuário em grupo
 * */
else if ($acao["acao"] == 5){
    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".subcategoria WHERE  ref_subcategoria = ". $_parametros['id-filtro'] ." ORDER BY descricao_subcategoria");
    $result = $consulta->fetchAll();
    ?>
    <label for="nf-contdespesa" class="control-label">SubCategoria:</label>
    <select name="nf-contdespesa" id="nf-contdespesa" class="form-control">
        <option value="">Selecione</option>
    <?php
    foreach ($result as $row) {
        ?>
        <option value="<?=$row["id_subcategoria"]?>"><?=($row["descricao_subcategoria"])?></option>
        <?php
    }
    ?>
    </select>
    <?php
}
/*
 * Lista resumo
 * */
else if ($acao["acao"] == 6) {
    $_parametros["total-nota"] = LimpaVariavel($_parametros["total-nota"]);
    ?>
    <div class="col-md-4">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-warning pull-left">
                <i class="md  md-shopping-cart text-warning"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark">Total Nota</h3>
                <h4 class="text-muted">R$ <b class="counterup"><?=number_format($_parametros["total-nota"], "2", ".", "")?></b></h4>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-info pull-left">
                <i class="md md-assignment text-info"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark">Total Produtos</h3>
                <?php
                $consultaPr = $pdo->prepare("SELECT sum(NFE_TOTALITEM) as TOTAL_PRODUTOS FROM " . $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_NRO = '" . $_parametros["id-nota"] . "' AND NFE_FORNEC = '" . $_parametros["id-fornecedor"] . "'");
                $consultaPr->execute();
                $consultaPr = $consultaPr->fetch();
                $consultaPr = number_format($consultaPr["TOTAL_PRODUTOS"], 2, ".", "");
                ?>
                <h4 class="text-muted">R$ <b class="counterup"><?=$consultaPr?></b></h4>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-success pull-left">
                <i class="md md-attach-money text-success"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark">Total Fatura</h3>
                <?php
                $consultaFa = $pdo->prepare("SELECT sum(NFE_VALOR) as TOTAL_FATURA FROM " . $_SESSION['BASE'] . ".nota_ent_pgto WHERE NFE_NRO = '" . $_parametros["id-nota"] . "' AND NFE_FORNEC = '" . $_parametros["id-fornecedor"] . "'");
                $consultaFa->execute();
                $consultaFa = $consultaFa->fetch();
                $consultaFa = number_format($consultaFa["TOTAL_FATURA"], 2, ".", "");
                ?>
                <h4 class="text-muted">R$ <b class="counterup"><?=$consultaFa?></b></h4>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php
}
/*
 * Atualiza Total Nota
 * */
else if ($acao["acao"] == 7) {
    $_parametros["total-nota"] = empty($_parametros["total-nota"]) ? $_parametros["total-nota"] : LimpaVariavel($_parametros["total-nota"]);

    $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".nota_ent_base SET  NFE_TOTALNF = ? WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
    $statement->bindParam(1, $_parametros["total-nota"]);
    $statement->bindParam(2, $_parametros["nf-num"]);
    $statement->bindParam(3, $_parametros["nf-fornecedor"]);
    $statement->execute();
}

