<?php
include("../../api/config/iconexao.php");
use Database\MySQL;

$pdo = MySQL::acessabd();
use Functions\Acesso;
use Functions\Estoque;

$tabelaView= Acesso::customizacao('18');
if($tabelaView == "") {
    $tabelaView = 0;
}

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

$_acao = $_POST['acao'];
$loginuser = $_SESSION['tecnico'];
$loginNOME = $_SESSION['APELIDO'];
//empresa_vizCodInt codigo visualização interno
$query = ("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  from  " . $_SESSION['BASE'] . ".parametro  ");
$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {
    $_vizCodInterno = $rst["empresa_vizCodInt"];
    
}

$query = ("SELECT empresa_tipo  from  " . $_SESSION['BASE'] . ".empresa  limit 1 ");
$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {   
    $tipoEmpresa =   $rst["empresa_tipo"];
}

if ($_acao == 7) {
 
 
  $ret =  Acesso::customizacaoUsuario('U','p01');

    exit();
}
/**
 * Cadastra Produto
 */
if ($_acao == 1) {
    if (empty($_parametros["produto-codigo"]) || empty($_parametros["produto-descricao"]) || empty($_parametros["produto-situacao"]) AND  $tipoEmpresa == 1 || empty($_parametros["produto-ncm"]) || empty($_parametros["produto-unidade"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Verifique se a descrição, unidade, situação tributária e NCM do produto!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        date_default_timezone_set('America/Sao_Paulo');
        !empty($_parametros["produto-codbarra"]) ?: $_parametros["produto-codbarra"] = $_parametros["produto-codigo"];
        $_parametros["produto-codfabricante"] = LimpaVariavel($_parametros["produto-codfabricante"]);
        $_parametros["produto-custo"] = LimpaVariavel($_parametros["produto-custo"]);
        $_parametros["produto-custoant"] = LimpaVariavel($_parametros["produto-custoant"]);
        $_parametros["produto-venda"] = LimpaVariavel($_parametros["produto-venda"]);
        $_parametros["produto-vendapor"] = LimpaVariavel($_parametros["produto-vendapor"]);
        $_parametros["produto-vlanterior"] = LimpaVariavel($_parametros["produto-vlanterior"]);
        $_parametros["produto-tab1"] = LimpaVariavel($_parametros["produto-tab1"]);
        $_parametros["produto-tab1-por"] = LimpaVariavel($_parametros["produto-tab1-por"]);
        $_parametros["produto-tab1-ant"] = LimpaVariavel($_parametros["produto-tab1-ant"]);
        $_parametros["produto-tab2"] = LimpaVariavel($_parametros["produto-tab2"]);
        $_parametros["produto-tab2-por"] = LimpaVariavel($_parametros["produto-tab2-por"]);
        $_parametros["produto-tab2-ant"] = LimpaVariavel($_parametros["produto-tab2-ant"]);
        $_parametros["produto-tab3"] = LimpaVariavel($_parametros["produto-tab3"]);
        $_parametros["produto-tab3-por"] = LimpaVariavel($_parametros["produto-tab3-por"]);
        $_parametros["produto-tab3-ant"] = LimpaVariavel($_parametros["produto-tab3-ant"]);
        $_parametros["produto-tab4"] = LimpaVariavel($_parametros["produto-tab4"]);
        $_parametros["produto-tab4-por"] = LimpaVariavel($_parametros["produto-tab4-por"]);
        $_parametros["produto-tab4-ant"] = LimpaVariavel($_parametros["produto-tab4-ant"]);
        $_parametros["produto-peso"] = LimpaVariavel($_parametros["produto-peso"]);
        $_parametros["produto-customed"] = LimpaVariavel($_parametros["produto-customed"]);
        $_parametros["produto-desconto"] = LimpaVariavel($_parametros["produto-desconto"]);
        $_parametros["produto-ultent"] = date("".$_parametros["produto-ultent"]." H:i:s", time());
        $_parametros["produto-promo"] = LimpaVariavel($_parametros["produto-promo"]);
        $_parametros["produto-min"] = LimpaVariavel($_parametros["produto-min"]);
        $_parametros["produto-max"] = LimpaVariavel($_parametros["produto-max"]);
        $_parametros["produto-qnta"] = LimpaVariavel($_parametros["produto-qnta"]);
        $_parametros["produto-qntb"] = LimpaVariavel($_parametros["produto-qntb"]);
        $_parametros["produto-qtndesc"] = LimpaVariavel($_parametros["produto-qtndesc"]);
        $_parametros["produto-icms"] = LimpaVariavel($_parametros["produto-icms"]);
        $_parametros["produto-redicms"] = LimpaVariavel($_parametros["produto-redicms"]);
        $_parametros["produto-ipi"] = LimpaVariavel($_parametros["produto-ipi"]);
        $_parametros["produto-devipi"] = LimpaVariavel($_parametros["produto-devipi"]);
        $_parametros["produto-qtnreceita"] = LimpaVariavel($_parametros["produto-qtnreceita"]);
        $_parametros["produto-unidade"] = LimpaVariavel($_parametros["produto-unidade"]);
        $_parametros["_enderComplemento"] = LimpaVariavel($_parametros["_enderComplemento"]);
        $_parametros["_enderC"] = LimpaVariavel($_parametros["_enderC"]);

        $_parametros["icmssubstituto"] = LimpaVariavel($_parametros["icmssubstituto"]);
        $_parametros["percICMSST"] = LimpaVariavel($_parametros["percICMSST"]);
        $_parametros["percICMSSTRET"] = LimpaVariavel($_parametros["percICMSSTRET"]);
        $_parametros["mva"] = LimpaVariavel($_parametros["mva"]);
        $_parametros["substTributaria"] = LimpaVariavel($_parametros["substTributaria"]);
     
        try {
            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoque(CODIGO_FORNECEDOR, Codigo_Barra, DESCRICAO, Descricao_Reduzida, GRU_GRUPO, CODIGO_LINHA, PRECO_CUSTO, Tab_Preco_5, Perc_Tab_preco5, Tab_Preco_1, Perc_Tab_preco1, Tab_Preco_2, Perc_Tab_preco2, Tab_Preco_3, Perc_Tab_preco3, Tab_Preco_4, Perc_Tab_preco4, prod_ativo, prod_ativosite, peso, customedio, PRECO_CONSUM_2, PRECO_CONSUM_FABRICA, QTDE_EST_MINIMO, Estoque_Maximo, item_qtde_A, item_qtde_B, Multiplo_Compra, Dias_Validade, ENDERECO1, ENDERECO2, item_lote_A, item_lote_B, item_validade_A, item_validade_B, item_extraA, item_extraB, promocao, impressora, GRUPO_PECAS, MODELO_APLICADO, Nome_linha, DATA_ULT_ENTRADA, PERC_ICMS, Perc_Icms_reducao, PERC_IPI, PERC_IPI_DEVOLUCAO, SIT_TRIBUTARIA, CFOPD, CFOPF, msg_red_Icms, Cod_Class_Fiscal, ind_ingrediente, ind_com_ingrediente, Qtde_Reserva_tecnica, descricao_etiqueta, nome_produtor, cnpj_produtor, peso_liquido, ingrediente, ingrediente2, ingrediente3, ingrediente4, vlr_energecico, vlr_gorudraTrans, vlr_gorduraTotal, vlr_gorduraSaturada, vlr_carboidrato, vlr_proteina, vlr_fibra, calcio, ferro, vlr_sodio, porcao, desc_base,UNIDADE_MEDIDA,CODIGO_FABRICANTE,ENDERECO3,ENDERECO_COMP,
            PERC_ICMSST,PERC_ICMSSTRET,VLR_SUBSTITUTO,MVA,IND_SUBTTRIBUTARIA,id_pis,id_cofins) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ? , ?, ?, ?, ?, ?, ?)");
            $statement->bindParam(1, $_parametros["produto-codigo"]);
            $statement->bindParam(2, $_parametros["produto-codbarra"]);
            $statement->bindParam(3, $_parametros["produto-descricao"]);
            $statement->bindParam(4, $_parametros["produto-resumo"]);
            $statement->bindParam(5, $_parametros["produto-grupo"]);
            $statement->bindParam(6, $_parametros["produto-linha"]);
            $statement->bindParam(7, $_parametros["produto-custo"]);
            $statement->bindParam(8, $_parametros["produto-venda"]);
            $statement->bindParam(9, $_parametros["produto-vendapor"]);
            $statement->bindParam(10, $_parametros["produto-tab1"]);
            $statement->bindParam(11, $_parametros["produto-tab1-por"]);
            $statement->bindParam(12, $_parametros["produto-tab2"]);
            $statement->bindParam(13, $_parametros["produto-tab2-por"]);
            $statement->bindParam(14, $_parametros["produto-tab3"]);
            $statement->bindParam(15, $_parametros["produto-tab3-por"]);
            $statement->bindParam(16, $_parametros["produto-tab4"]);
            $statement->bindParam(17, $_parametros["produto-tab4-por"]);
            $statement->bindParam(18, $_parametros["produto-ativo"]);
            $statement->bindParam(19, $_parametros["produto-ativosite"]);
            $statement->bindParam(20, $_parametros["produto-peso"]);
            $statement->bindParam(21, $_parametros["produto-customed"]);
            $statement->bindParam(22, $_parametros["produto-desconto"]);
            $statement->bindParam(23, $_parametros["produto-promo"]);
            $statement->bindParam(24, $_parametros["produto-min"]);
            $statement->bindParam(25, $_parametros["produto-max"]);
            $statement->bindParam(26, $_parametros["produto-qnta"]);
            $statement->bindParam(27, $_parametros["produto-qntb"]);
            $statement->bindParam(28, $_parametros["produto-qtndesc"]);
            $statement->bindParam(29, $_parametros["produto-diasval"]);
            $statement->bindParam(30, $_parametros["_enderA"]);
            $statement->bindParam(31, $_parametros["_enderB"]);
            $statement->bindParam(32, $_parametros["produto-lotea"]);
            $statement->bindParam(33, $_parametros["produto-loteb"]);
            $statement->bindParam(34, $_parametros["produto-venda-a"]);
            $statement->bindParam(35, $_parametros["produto-venda-b"]);
            $statement->bindParam(36, $_parametros["produto-extraa"]);
            $statement->bindParam(37, $_parametros["produto-extrab"]);
            $statement->bindParam(38, $_parametros["produto-msgetiqueta"]);
            $statement->bindParam(39, $_parametros["produto-impressora"]);
            $statement->bindParam(40, $_parametros["produto-lista"]);
            $statement->bindParam(41, $_parametros["produto-especificacao"]);
            $statement->bindParam(42, $_parametros["produto-descweb"]);
            $statement->bindParam(43, $_parametros["produto-ultent"]);
            $statement->bindParam(44, $_parametros["produto-icms"]);
            $statement->bindParam(45, $_parametros["produto-redicms"]);
            $statement->bindParam(46, $_parametros["produto-ipi"]);
            $statement->bindParam(47, $_parametros["produto-devipi"]);
            $statement->bindParam(48, $_parametros["produto-situacao"]);
            $statement->bindParam(49, $_parametros["produto-cfopuf"]);
            $statement->bindParam(50, $_parametros["produto-cfop"]);
            $statement->bindParam(51, $_parametros["produto-msgicms"]);
            $statement->bindParam(52, $_parametros["produto-ncm"]);
            $statement->bindParam(53, $_parametros["produto-ingrediente"]);
            $statement->bindParam(54, $_parametros["produto-cingrdiente"]);
            $statement->bindParam(55, $_parametros["produto-qtnreceita"]);
            $statement->bindParam(56, $_parametros["produto-descricao-emb"]);
            $statement->bindParam(57, $_parametros["produto-nome-produtor"]);
            $statement->bindParam(58, $_parametros["produto-cnpj-produtor"]);
            $statement->bindParam(59, $_parametros["produto-pesoliquido"]);
            $statement->bindParam(60, $_parametros["produto-ingrediente-1"]);
            $statement->bindParam(61, $_parametros["produto-ingrediente-2"]);
            $statement->bindParam(62, $_parametros["produto-ingrediente-3"]);
            $statement->bindParam(63, $_parametros["produto-ingrediente-4"]);
            $statement->bindParam(64, $_parametros["produto-valorcalorico"]);
            $statement->bindParam(65, $_parametros["produto-gorduratrans"]);
            $statement->bindParam(66, $_parametros["produto-gordurastotais"]);
            $statement->bindParam(67, $_parametros["produto-colesterol"]);
            $statement->bindParam(68, $_parametros["produto-carboidratos"]);
            $statement->bindParam(69, $_parametros["produto-proteinas"]);
            $statement->bindParam(70, $_parametros["produto-fibra"]);
            $statement->bindParam(71, $_parametros["produto-calcio"]);
            $statement->bindParam(72, $_parametros["produto-ferro"]);
            $statement->bindParam(73, $_parametros["produto-sodio"]);
            $statement->bindParam(74, $_parametros["produto-porcao"]);
            $statement->bindParam(75, $_parametros["produto-valorbase"]);
            $statement->bindParam(76, $_parametros["produto-unidade"]);
            $statement->bindParam(77, $_parametros["produto-codfabricante"]);
            $statement->bindParam(78, $_parametros["_enderC"]);
            $statement->bindParam(79, $_parametros["_enderComplemento"]);

            $statement->bindParam(80, $_parametros["produto-percICMSST"]);
            $statement->bindParam(81, $_parametros["produto-icmssubstituto"]);
            $statement->bindParam(82, $_parametros["produto-percICMSSTRET"]);
            $statement->bindParam(83, $_parametros["produto-mva"]);
            $statement->bindParam(84, $_parametros["substTributaria"]);
            $statement->bindParam(85, $_parametros["produto-pis"]);
            $statement->bindParam(86, $_parametros["produto-cofins"]);

          
            $statement->execute();

            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquealmox (Codigo_Item,Codigo_Almox,codref_fabricante) VALUES (?, '1', ?)");
            $statement->bindParam(1, $_parametros["produto-codigo"]);
            $statement->bindParam(2, $_parametros["produto-codfabricante"]);
            $statement->execute();
/*
            $consultaParametro = $pdo->query("SELECT Ult_Cod_Peca FROM ".$_SESSION['BASE'].".parametro");
            $retornoParametro = $consultaParametro->fetch();

            if (($retornoParametro["Ult_Cod_Peca"]+1) == $_parametros["produto-codigo"]) {
                $idPecaAtt = intval($retornoParametro["Ult_Cod_Peca"]) + 1;

                $updateParametro = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".parametro SET Ult_Cod_Peca = :id");
                $updateParametro->bindParam(":id", $idPecaAtt);
                $updateParametro->execute();
            }
            */

         
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Cadastrado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
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
 * Listar Produtos
 * */
else if ($_acao == 5) {
    $busca = $_parametros["descfull"];
    if(trim($busca) == "") { 
        ?>
            <div class="alert alert-warning text-center">
                        <strong>Atenção!</strong> Informe o Código para Pesquisa
                    </div>
        <?php
exit();
    }

    $consultaColunas = $pdo->query("SELECT visualiza_tab1,visualiza_tab2,visualiza_tab3,visualiza_tab4,
    visualiza_tab5,label_tab1,label_tab2,label_tab3,label_tab4,label_tab5,visualiza_tab6 
    FROM ".$_SESSION['BASE'].".parametro");
    $retornoColunas = $consultaColunas->fetch();
    
    $sq = "SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
    LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
    LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
    WHERE  
    CODIGO_FORNECEDOR = '$busca' and  almox_totaliza  = 1 OR
    CODIGO_FABRICANTE = '$busca' and  almox_totaliza  = 1  OR
    Codigo_Barra = '$busca'  and  almox_totaliza  = 1  OR
    Codigo_Referencia_Fornec = '".str_pad(trim($busca), 18, '0', STR_PAD_LEFT)."'  and  almox_totaliza  = 1 
    GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100";

    $consultaProduto = $pdo->query("$sq");


$retornoProdutos = $consultaProduto->fetchAll();
$umReg  = count($retornoProdutos) ;
?>
  <input type="text" id="umReg" name="umReg"  value="<?=$umReg;?>" style="display: none;">
  
 
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive " cellspacing="0" width="100%">
    <thead>
    <tr style="padding:6px;">
    <th  style="min-width: 350px;">Descrição</th>  
        <?php if($_vizCodInterno == 1){
            ?>
            <th class="text-center">Cód.Fabricante</th>
        <?php }else { ?>
            <th class="text-center">Cód. Barras</th>
        <?php } ?>
       
        <th class="text-center">Cód.Int</th>
        <th class="text-center">Unidade</th>
        <th class="text-center">Estoque</th>
        <th class="text-center">Endereço</th>
       
        <?=$retornoColunas["visualiza_tab5"] == -1 ? "<th class='text-center'>".$retornoColunas["label_tab5"]."</th>" : ""?>
        <?=$retornoColunas["visualiza_tab4"] == -1  && $tabelaView ==0  ? "<th class='text-center'>".$retornoColunas["label_tab4"]."</th>" : ""?>
        <?=$retornoColunas["visualiza_tab3"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab3"]."</th>" : ""?>
        <?=$retornoColunas["visualiza_tab2"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab2"]."</th>" : ""?>
        <?=$retornoColunas["visualiza_tab1"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab1"]."</th>" : ""?>
        
        <th class='text-center'>Ação</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($retornoProdutos as $row) {
        $ender = "";
        $idcodfornec =$row['CODIGO_FORNECEDOR'];
        ?>
        <tr class="gradeX">
        <td ><a href="#"  onclick="_estoqueD('<?=$row['CODIGO_FORNECEDOR']?>')"><?=(strlen($row["DESCRICAO"]) > 201 ? substr($row["DESCRICAO"],0,200)."..." : $row["DESCRICAO"])?></a></td>
            <?php if($_vizCodInterno == 1){
            ?>
              <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
            <?php }else { ?>
                <td class="text-center"><?=$row["Codigo_Barra"]?></td>
            <?php } 
             if($row["ENDERECO1"] != ""){
                $ender = $row["ENDERECO1"];
                if($row["ENDERECO2"] != ""){
                    $ender =   $ender."/".$row["ENDERECO2"];
                    if($row["ENDERECO3"] != ""){
                        $ender =   $ender."/".$row["ENDERECO3"];
                    }
                }
                /*
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
                          */
             }
    
            $ender = $ender." ".$row["ENDERECO_COMP"];
            
            if($row["Qtde_Reserva_Tecnica"] > 0 ) { 
                $reserva = '<span class="badge  badge-warning">R</span>';
            }else{
                $reserva  = "";
            }
            ?>

          
         
            <td class="text-center"><?=$row["CODIGO_FORNECEDOR"]?></td>
            <td class="text-center"><?=$row["UNIDADE_MEDIDA"]?></td>
            <td class="text-center"><a href="#" data-toggle="modal" data-target="#custom-modal-estoque" onclick="_estoque('<?=$row['CODIGO_FORNECEDOR']?>')"><?=$row["tot_item"]?></a><?=$reserva;?>
          
        </td>
            <td class="text-center"><?=$ender;?></td>           
            <?=$retornoColunas["visualiza_tab5"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_5"],2,',','.')."</td>" : ""?>
            <?=$retornoColunas["visualiza_tab4"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_4"],2,',','.')."</td>" : ""?>
            <?=$retornoColunas["visualiza_tab3"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_3"],2,',','.')."</td>" : ""?>
            <?=$retornoColunas["visualiza_tab2"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_2"],2,',','.')."</td>" : ""?>
            <?=$retornoColunas["visualiza_tab1"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_1"],2,',','.')."</td>" : ""?>
         
            <td class="actions text-center">
                <a href="#" class="on-default edit-row" onclick="_buscadadosnf('<?=$row['CODIGO_FORNECEDOR']?>','0')" style="padding-right: 10px;"><i class="fa  fa-files-o fa-lg"></i></a>
                <a href="#" class="on-default edit-row" onclick="_alterar(<?=$row['CODIGO_FORNECEDOR']?>)"><i class="fa fa-pencil"></i></a>
            </td>
        </tr>
        <?php
         //verificar se existe peca similar
         if($row["CODIGO_SIMILAR"] != ""  and  	$row['CODIGO_SIMILAR'] != $row['CODIGO_FABRICANTE']) {
          
                $consultaProdutoSimilar = $pdo->query("SELECT Qtde_Reserva_Tecnica,CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
                LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR
                LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
                WHERE almox_totaliza  = 1 and CODIGO_SIMILAR = '".$row['CODIGO_FABRICANTE']."' GROUP BY Qtde_Reserva_Tecnica,CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
              
                $retornoProdutosSimilar = $consultaProdutoSimilar->fetchAll();
                foreach ($retornoProdutosSimilar as $row) {
                    if($row["Qtde_Reserva_Tecnica"] > 0 ) { 
                        $reserva = '<span class="badge  badge-warning">R</span>';
                    }else{
                        $reserva  = "";
                    }
                    ?>
                    <tr class="gradeX">
                        <td ><a href="#" onclick="_estoqueD('<?=$row['CODIGO_FORNECEDOR']?>')"><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></a></td>
                        <?php if($_vizCodInterno == 1){
                        ?>
                          <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
                        <?php }else { ?>
                            <td class="text-center"><?=$row["Codigo_Barra"]?></td>
                        <?php } ?>
                      
                        <td class="text-center"><?=$row["CODIGO_FORNECEDOR"]?></td>
                        <td class="text-center"><?=$row["UNIDADE_MEDIDA"]?></td>
                        <td class="text-center"><a href="#" data-toggle="modal" data-target="#custom-modal-estoque" onclick="_estoque('<?=$row['CODIGO_FORNECEDOR']?>')"><?=$row["tot_item"]?></a><?=$reserva;?></td>
                        <td class="text-center"><?=$row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"]." ".$row["ENDERECO_COMP"];?></td>
                        <?=$retornoColunas["visualiza_tab5"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_5"],2,',','.')."</td>" : ""?>
                        <?=$retornoColunas["visualiza_tab4"] == -1  && $tabelaView ==0? "<td class='text-center'>".number_format($row["Tab_Preco_4"],2,',','.')."</td>" : ""?>                        
                        <?=$retornoColunas["visualiza_tab3"] == -1  && $tabelaView ==0? "<td class='text-center'>".number_format($row["Tab_Preco_3"],2,',','.')."</td>" : ""?>
                        <?=$retornoColunas["visualiza_tab2"] == -1  && $tabelaView ==0? "<td class='text-center'>".number_format($row["Tab_Preco_2"],2,',','.')."</td>" : ""?>
                        <?=$retornoColunas["visualiza_tab1"] == -1  && $tabelaView ==0? "<td class='text-center'>".number_format($row["Tab_Preco_1"],2,',','.')."</td>" : ""?>
                        <td class="actions text-center">
                        <a href="#" class="on-default edit-row" onclick="_buscadadosnf('<?=$row['CODIGO_FORNECEDOR']?>','0')" style="padding-right: 10px;"><i class="fa  fa-files-o fa-lg"></i></a>
                            <a href="#" class="on-default edit-row" onclick="_alterar(<?=$row['CODIGO_FORNECEDOR']?>)"><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                    <?php
                  
                }
         }
    }
    ?>
    <input type="text" id="umRegCod" name="umRegCod"  value="<?= $idcodfornec;?>" style="display: none;">
    <?php

}
else if ($_acao == 2) {

    $consultaColunas = $pdo->query("SELECT visualiza_tab1,visualiza_tab2,visualiza_tab3,visualiza_tab4,
    visualiza_tab5,label_tab1,label_tab2,label_tab3,label_tab4,label_tab5,visualiza_tab6 
    FROM ".$_SESSION['BASE'].".parametro");
    $retornoColunas = $consultaColunas->fetch();

    $busca = empty($_parametros["produto-pesquisa"]) ? "' '" : $_parametros["produto-pesquisa"];
    $grupo_id = $_parametros["produto-grupo"];
    $ativo = $_parametros["produto-status"];

    $grupoPesquisa = $grupo_id == 0 ? "" : "grupo.GRU_GRUPO = '$grupo_id' AND ";
    
    if ($ativo == 1 || $ativo == 2) {
        $ativo = $ativo == 1 ? "0" : $ativo;
        $_filativo = "AND prod_ativo =  '$ativo'";
    }
    else if ($ativo == 3) {
        $_filativo = "AND prod_ativosite =  '0'";
    }
    else {
        $_filativo = "";
    }

    $_labelcampopesquisa = 'Cod.Int ';
    if ($_parametros["produto-filtro"] == 0) {
        $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,
        sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,
        Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,
        itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,
        Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,
        grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad ,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE
        ,ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica
        FROM ".$_SESSION['BASE'].".itemestoque 
        LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE 
        LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO 
        LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
        LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
        LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
        WHERE almox_totaliza  = 1 and Ind_Prod <> 2 and $grupoPesquisa CODIGO_FORNECEDOR = '$busca' $_filativo 
        GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
          $_campopesquisa = 'CODIGO_FORNECEDOR';
    }
    else if ($_parametros["produto-filtro"] == 1) {
        $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
        LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
        LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
        WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa Codigo_Barra = '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
        $_campopesquisa = 'Codigo_Barra';
    }
    else if ($_parametros["produto-filtro"] == 3) {
        if (strpos($busca, '*') !== false) {
            //echo 'Existe testar na string';
            $busca = str_replace('*','%',$busca);
            $_sql = "SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
            WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa ".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE like '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100";
           
            $consultaProduto = $pdo->query("$_sql");
        }else{
            $busca = str_replace('*','%',$busca);
         
            $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
            WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa ".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE = '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");

        }
        $_campopesquisa = 'CODIGO_FORNECEDOR';
        $_sql = "SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
            WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa ".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE = '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100";
     
    }
    else if ($_parametros["produto-filtro"] == 4) {
    
            //echo 'Existe testar na string';
            $busca = str_replace('*','%',$busca);
            $_sql = "SELECT TRIM(LEADING '0' FROM Codigo_Referencia_Fornec) AS Codigo_Referencia_Fornec ,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
            WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa ".$_SESSION['BASE'].".itemestoque.Codigo_Referencia_Fornec like '".str_pad(trim($busca), 18, '0', STR_PAD_LEFT)."' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100";
           
            $consultaProduto = $pdo->query("$_sql");
       
            $_campopesquisa = 'Codigo_Referencia_Fornec';
            $_labelcampopesquisa = 'Cod.SKU';
    }
    else {
        if (strpos($busca, '*') !== false) {
            $busca = str_replace('*','%',$busca);
            $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
            WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa itemestoque.DESCRICAO LIKE '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");

        }else{
            $busca = str_replace('*','%',$busca);
            $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
            WHERE almox_totaliza  = 1 and  Ind_Prod <> 2 and $grupoPesquisa itemestoque.DESCRICAO LIKE '%$busca%' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");

        }
        $_campopesquisa = 'CODIGO_FORNECEDOR';
      
    }

    $retornoProdutos = $consultaProduto->fetchAll();
    $umReg  = count($retornoProdutos) ;
   //<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
    ?>
  
  <input type="text" id="umReg" name="umReg"  value="<?=$umReg;?>" style="display: none;">
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%">
        <thead>
        <tr style="padding:6px;">
            <th  style="min-width: 350px;">Descrição</th>  
            <?php if($_vizCodInterno == 1){
                ?>
                <th class="text-center">Cód.Fabricante</th>
            <?php }else { ?>
                <th class="text-center">Cód. Barras</th>
            <?php } ?>
           
            <th class="text-center"><?=$_labelcampopesquisa ;?></th>
            <th class="text-center">Unidade</th>
            <th class="text-center">Est</th>
            <th class="text-center">Endereço</th>
          
            <?=$retornoColunas["visualiza_tab5"] == -1  ? "<th class='text-center'>".$retornoColunas["label_tab5"]."</th>" : ""?> 
            <?=$retornoColunas["visualiza_tab4"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab4"]."</th>" : ""?>
            <?=$retornoColunas["visualiza_tab3"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab3"]."</th>" : ""?>
            <?=$retornoColunas["visualiza_tab2"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab2"]."</th>" : ""?>
            <?=$retornoColunas["visualiza_tab1"] == -1  && $tabelaView ==0 ? "<th class='text-center'>".$retornoColunas["label_tab1"]."</th>" : ""?>
            <th class='text-center'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retornoProdutos as $row) {
            $idcodfornec = $row['CODIGO_FORNECEDOR'];
            $ender =  "";
            ?>
            <tr class="gradeX">
            <td ><a href="#"  onclick="_estoqueD('<?=$row['CODIGO_FORNECEDOR']?>')"><?=(strlen($row["DESCRICAO"]) > 201 ? substr($row["DESCRICAO"],0,200)."..." : $row["DESCRICAO"])?></a>
            <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
                <?php  
                 if($row["ENDERECO1"] != ""){
                    $ender = $row["ENDERECO1"];
                    if($row["ENDERECO2"] != ""){
                        $ender =   $ender."/".$row["ENDERECO2"];
                        if($row["ENDERECO3"] != ""){
                            $ender =   $ender."/".$row["ENDERECO3"];
                        }
                    }
/*
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
                     */
                 }
                $ender = $ender." ".$row["ENDERECO_COMP"];
                
                if($row["Qtde_Reserva_Tecnica"] > 0 ) { 
                    $reserva = '<span class="badge  badge-warning">R</span>';
                }else{
                    $reserva  = "";
                }
                ?>

              
             
                <td class="text-center"><?=$row[$_campopesquisa];?></td>
                <td class="text-center"><?=$row["UNIDADE_MEDIDA"]?></td>
                <td class="text-center"><a href="#" data-toggle="modal" data-target="#custom-modal-estoque" onclick="_estoque('<?=$row['CODIGO_FORNECEDOR']?>')"><?=$row["tot_item"]?></a><?=$reserva;?>
              
            </td>
                <td class="text-center"><?=$ender;?></td>
                <?=$retornoColunas["visualiza_tab5"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_5"],2,',','.')."</td>" : ""?>
                <?=$retornoColunas["visualiza_tab4"] == -1  && $tabelaView == 0 ? "<td class='text-center'>".number_format($row["Tab_Preco_4"],2,',','.')."</td>" : ""?>
                
                <?=$retornoColunas["visualiza_tab3"] == -1  && $tabelaView == 0 ?  "<td class='text-center'>".number_format($row["Tab_Preco_3"],2,',','.')."</td>" : ""?>
                <?=$retornoColunas["visualiza_tab2"] == -1  && $tabelaView == 0 ? "<td class='text-center'>".number_format($row["Tab_Preco_2"],2,',','.')."</td>" : ""?>
                            
                <?=$retornoColunas["visualiza_tab1"] == -1  && $tabelaView == 0 ? "<td class='text-center'>".number_format($row["Tab_Preco_1"],2,',','.')."</td>" : ""?>
                <td class="actions text-center">
             
                    <a href="#" class="on-default edit-row" onclick="_buscadadosnf('<?=$row['CODIGO_FORNECEDOR']?>','0')" style="padding-right: 10px;"><i class="fa  fa-files-o fa-lg"></i></a>
                    <a href="#" class="on-default edit-row" onclick="_alterar(<?=$row['CODIGO_FORNECEDOR']?>)"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            <?php
             //verificar se existe peca similar
             if($row["CODIGO_SIMILAR"] != "" and  $_parametros["produto-filtro"] == 3 and  	$row['CODIGO_SIMILAR'] != $row['CODIGO_FABRICANTE']) {
                $_sqlsimilar = "SELECT Qtde_Reserva_Tecnica,CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA 
                LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
                LEFT JOIN ".$_SESSION['BASE'].".almoxarifado ON almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
                WHERE almox_totaliza  = 1 and ".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE = '".$row['CODIGO_SIMILAR']."'              
                GROUP BY Qtde_Reserva_Tecnica,CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100";
                    $consultaProdutoSimilar = $pdo->query("$_sqlsimilar");
            
                    $retornoProdutosSimilar = $consultaProdutoSimilar->fetchAll();
                    foreach ($retornoProdutosSimilar as $row) {
                        if($row["Qtde_Reserva_Tecnica"] > 0 ) { 
                            $reserva = '<span class="badge  badge-warning">R</span>';
                        }else{
                            $reserva  = "";
                        }
                        ?>
                        <tr class="gradeX">
                        <td ><a href="#"  onclick="_estoqueD('<?=$row['CODIGO_FORNECEDOR']?>')"><?=(strlen($row["DESCRICAO"]) > 201 ? substr($row["DESCRICAO"],0,200)."..." : $row["DESCRICAO"])?></a>
                           
                            <?php if($_vizCodInterno == 1){
                            ?>
                              <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
                            <?php }else { ?>
                                <td class="text-center"><?=$row["Codigo_Barra"]?></td>
                            <?php } ?>
                          
                            <td class="text-center"><?=$row["CODIGO_FORNECEDOR"]?></td>
                            <td class="text-center"><?=$row["UNIDADE_MEDIDA"]?></td>
                            <td class="text-center"><a href="#" data-toggle="modal" data-target="#custom-modal-estoque" onclick="_estoque('<?=$row['CODIGO_FORNECEDOR']?>')"><?=$row["tot_item"]?></a><?=$reserva;?></td>
                            <td class="text-center"><?=$row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"]." ".$row["ENDERECO_COMP"];?></td>
                          
                            <?=$retornoColunas["visualiza_tab5"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_5"],2,',','.')."</td>" : ""?>
                            <?=$retornoColunas["visualiza_tab4"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_4"],2,',','.')."</td>" : ""?>
                            
                            <?=$retornoColunas["visualiza_tab3"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_3"],2,',','.')."</td>" : ""?>
                            <?=$retornoColunas["visualiza_tab2"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_2"],2,',','.')."</td>" : ""?>
                            <?=$retornoColunas["visualiza_tab1"] == -1  && $tabelaView ==0 ? "<td class='text-center'>".number_format($row["Tab_Preco_1"],2,',','.')."</td>" : ""?>
                            <td class="actions text-center">
                                <a href="#" class="on-info edit-row" onclick="_buscadadosnf('<?=$row['CODIGO_FORNECEDOR']?>','0')" style="padding-right: 10px;"><i class="fa  fa-files-o fa-lg"></i></a>
                                <a href="#" class="on-default edit-row" onclick="_alterar(<?=$row['CODIGO_FORNECEDOR']?>)"><i class="fa fa-pencil"></i></a>
                            </td>
                        </tr>
                        <?php
                      
                    }
             }
        }
        ?>
        </tbody>
    </table>
  
    <input type="text" id="umRegCod" name="umRegCod"  value="<?=$idcodfornec;?>" style="display: none;">
    <?php

    
 
}

else if ($_acao == 6  ) { //ver estoqque
    try { 
        
        if($_SESSION['per402'] != '402' ) {
            //
            echo "Você não tem permissão detalhamento";
            exit();
        }

    
        $layout = Acesso::customizacaoUsuario('C','p01');

        $id = $_parametros['id-altera']; 

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

        //DADOS DO PRODUTO
    
    
        $consultaP = $pdo->query("Select Codigo,DESCRICAO,CODIGO_FORNECEDOR,ENDERECO1,ENDERECO2,ENDERECO3,QTDE_EST_MINIMO,PRECO_CONSUM_FABRICA,PRECO_CUSTO,
        PERC_IPI,MODELO_APLICADO,DATE_FORMAT(DATA_ULT_ENTRADA,'%d/%m/%Y')AS DT,VALOR_ULT_ENTRADA, DATE_FORMAT(Dt_Ult_alteracaovlVenda,'%d/%m/%Y') as DTULTVENDA,
        vl_Ant_alteracaovlVenda,CODIGO_FABRICANTE
         from ".$_SESSION['BASE'].".itemestoque       
        where CODIGO_FORNECEDOR = '$id' ");
         
       $retornoP = $consultaP->fetch();  

       $IPI = $retornoP["PERC_IPI"];
       $DTENTRADA = $retornoP["DT"];
       $DTULTVENDA = $retornoP["DTULTVENDA"];
       $precoVendaAnt = $retornoP["vl_Ant_alteracaovlVenda"];
       $especificacao = str_replace("/"," ",$retornoP['MODELO_APLICADO']);
       $especificacao = str_replace(","," ", $especificacao);
    

       $consultaProduto = $pdo->query("Select sum(Qtde_Disponivel) as qt from ".$_SESSION['BASE'].".itemestoquealmox         
       where Codigo_Item = '$id'  ");
          
        $retornoProdutos = $consultaProduto->fetchAll();  
        foreach ($retornoProdutos as $row) {
            $t = $row["qt"];
        }

        if($retornoP['DT'] == '00/00/0000') {
                //atualiza dt ult entrada
                $SQL = "Select NFE_CODIGO,NFE_IPI,NFE_VLRUNI,NFE_DATAENTR,
                DATE_FORMAT(NFE_DATAENTR,'%d/%m/%Y')AS DT
                 from ".$_SESSION['BASE'].".nota_ent_item
                 LEFT JOIN  ".$_SESSION['BASE'].".nota_ent_base ON NFE_ID =  NFE_IDBASE        
                where NFE_CODIGO = '$id'";
            
                $consultaNF = $pdo->query("$SQL");
                $retornoNF= $consultaNF->fetchAll();  
                foreach ($retornoNF as $RET) {
                    $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".itemestoque
                     SET DATA_ULT_ENTRADA = ?, 
                     PERC_IPI = ?
                      WHERE Codigo = ?  limit 1");
                    $statement->bindParam(1, $RET["NFE_DATAENTR"]);
                    $statement->bindParam(2, $RET["NFE_IPI"]);                    
                    $statement->bindParam(3, $retornoP["Codigo"]);
                    $statement->execute();
                    $IPI = $RET["NFE_IPI"];
                    $DTENTRADA = $RET["DT"];
                   

                }
        }


       
       //LAYOUT PADRAO   
 
       if($layout ==  0) {
      
       ?>

        <div class="row ">           
            <div class="col-md-12">
            <div style="text-align: center">
            <div class="col-lg-12">
                        <div class="portlet" style="margin-bottom: 1px">
                            <div class="portlet-heading bg-inverse">
                                <h3 class="portlet-title">
                                <?=$retornoP["DESCRICAO"];?>
                                </h3>
                                <div class="portlet-widgets bg-warning" style="border-width: 0px;border-radius: 20px; width: 100%;"><a data-toggle="collapse" data-parent="#accordion1" href="#bg-inverse" class="collapsed" aria-expanded="false"  style="color:#000 ;"><span style="font: size 12px;"><strong>Total Est: <?=$t;?> </strong></span><i class="ion-minus-round"></i>&nbsp; </a>
                               
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="bg-inverse" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <table class="table table-striped table-bordered nowrap "  style="font-size: 11px ; font-weight: bold; " cellspacing="0" width="100%">
                               <tbody>  
                                <?php 
                               $consultaProduto = $pdo->query("Select * from ".$_SESSION['BASE'].".itemestoquealmox
                               left join ".$_SESSION['BASE'].".almoxarifado on almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
                               where Codigo_Item = '$id' and Qtde_Disponivel > 0 OR Codigo_Item = '$id' and Qtde_Disponivel < 0 ");
                                
                              $retornoProdutos = $consultaProduto->fetchAll();  
                                foreach ($retornoProdutos as $row) {
                                    ?>
                                    <tr>
                                    <td  class="text-center"><?=$row["Descricao"];?> </td>
                                      <td  class="text-center" > <?=strtoupper($row["Qtde_Disponivel"]);?>   
                                     </tr>
                                     <?php
                                }
                                ?>
                            
                            
                                                    
                        </tbody>
                            </table>
                            </div>
                        </div>
                    </div></div>
              <strong>
                
                 <table class="table table-striped table-bordered dt-responsive nowrap "  style="font-size: 11px; " cellspacing="0" width="100%">
                           
                            <tbody>
                            <tr>
                                <td class="text-center">Ref.Fabricante</td>
                                <td class="text-center">  <?=$retornoP["CODIGO_FABRICANTE"];?>
                                    <input type="hidden"  id="id-codfabricante" name="id-codfabricante" value="<?=$retornoP["CODIGO_FABRICANTE"];?>"></td>
                            </tr>  
                          
                            <tr>
                                <td class="text-center">Est.Mímino</td>
                                <td class="text-center"><?=$retornoP["QTDE_EST_MINIMO"];?></td>
                            </tr>    
                            <tr>
                                <td class="text-center">Dt. Últ Entr</td>
                                <td class="text-center"><?=$DTENTRADA;?>
                            </td>
                            <tr>
                                <td class="text-center">Vlr. Últ Entr+IPI</td>
                                <td class="text-center">R$ <?=number_format(($retornoP["PRECO_CUSTO"] + ($retornoP["PRECO_CUSTO"]*$retornoP["PERC_IPI"]/100)),2,',','.'); ?>
                            </td>
                            <tr>
                                <td class="text-center">% IPI</td>
                                <td class="text-center"><?=number_format($IPI,2,',','.')?>
                            </td>
                            <tr>
                                <td class="text-center">Vlr.Sugerido</td>
                                <td class="text-center">R$ <?=number_format($retornoP["PRECO_CONSUM_FABRICA"],2,',','.')?>
                            </td>
                            <tr>
                                <td class="text-center">Dt. Alteração Vlr.Venda</td>
                                <td class="text-center"><?=$DTULTVENDA;?>
                            </td>
                            <tr>
                                <td class="text-center">Vlr Anterior Venda</td>
                                <td class="text-center">R$ <?=number_format($precoVendaAnt,2,',','.')?>
                            </td>                            
                         
                            <tr>
                                <td class="text-center">Especificação</td>
                                <td class="text-center"><?=$especificacao;?>
                            </td>
                            </tr>        
                        
                            </tbody>
                  </table>
                  </strong>
                       
            </div>
        </div>
        <div class="row "  id="listparceiro">   
            <?php /*
             <div class="col-lg-12" style="text-align: center; ">       
         
          
                <div  align="center">
                        <a href="#"  onclick="_estoqueP('<?=$row['CODIGO_FORNECEDOR']?>','<?=$row['CODIGO_FABRICNTE']?>','<?=$row['CODIGO_REFERE']?>')"><?=(strlen($row["DESCRICAO"]) > 201 ? substr($row["DESCRICAO"],0,200)."..." : $row["DESCRICAO"])?> <img src="assets/images/small/pesqparceiropeca.png" alt="image" class="img-responsive" width="100"></a>
                </div>
            </div>
            <div class="col-lg-12">  
                <div class=" bg-white" > 
                  <span class="badge badge-inverse m-l-0">Localizar os estoque parceiros </span>
                </div>
           </div>  
           */?>
        </div>
    
   


   </div>  
        
       <?php 
       
    }else{ //layout personalizado 
        ?>


<div class="row ">           
    <div class="col-md-6">
         <div style="text-align: center">
             <div class="col-lg-12">              
                <div class=" bg-inverse">     
                    <h4  style="color:#ffffff"> <?=$retornoP["DESCRICAO"];?>     </h4>
                </div>
            </div>      
      <strong>
        
         <table class="table table-striped table-bordered dt-responsive nowrap "  style="font-size: 11px; " cellspacing="0" width="100%">
                   
                    <tbody>
                    <tr>
                                <td class="text-center">Ref.Fabricante</td>
                                <td class="text-center">  <?=$retornoP["CODIGO_FABRICANTE"];?>
                                    <input type="hidden"  id="id-codfabricante" name="id-codfabricante" value="<?=$retornoP["CODIGO_FABRICANTE"];?>"></td>
                            </tr>  
                    <tr>
                        <td class="text-center">Est.Mímino</td>
                        <td class="text-center"><?=$retornoP["QTDE_EST_MINIMO"];?></td>
                    </tr>    
                    <tr>
                        <td class="text-center">Dt. Últ Entr</td>
                        <td class="text-center"><?=$DTENTRADA;?>
                    </td>
                    <tr>
                        <td class="text-center">Vlr. Últ Entr+IPI</td>
                        <td class="text-center">R$ <?=number_format(($retornoP["PRECO_CUSTO"] + ($retornoP["PRECO_CUSTO"]*$retornoP["PERC_IPI"]/100)),2,',','.'); ?>
                    </td>
                    <tr>
                        <td class="text-center">% IPI</td>
                        <td class="text-center"><?=number_format($IPI,2,',','.')?>
                    </td>
                    <tr>
                        <td class="text-center">Vlr.Sugerido</td>
                        <td class="text-center">R$ <?=number_format($retornoP["PRECO_CONSUM_FABRICA"],2,',','.')?>
                    </td>
                    <tr>
                        <td class="text-center">Dt. Alteração Vlr.Venda</td>
                        <td class="text-center"><?=$DTULTVENDA;?>
                    </td>
                    <tr>
                        <td class="text-center">Vlr Anterior Venda</td>
                        <td class="text-center">R$ <?=number_format($precoVendaAnt,2,',','.')?>
                    </td>                            
                 
                    <tr>
                        <td class="text-center">Especificação</td>
                        <td class="text-center"><?=$especificacao;?>
                    </td>
                    </tr>        
                
                    </tbody>
          </table>
          </strong>
          </div>        
    </div>
    <div class="col-md-3">
         <div style="text-align: center">
             <div class="col-lg-12">  
                    <div class=" bg-warning" >     
                    <h4> Estoque por Almoxarifado   </h4>
                </div>
                <div style=" height: 260px; overflow: auto; border: 1px solid #ccc; padding: 10px;">
                        <table class="table table-striped table-bordered nowrap "  style="font-size: 11px ; font-weight: bold; " cellspacing="0" width="100%">
                            <tbody>  
                                <?php 
                            $consultaProduto = $pdo->query("Select * from ".$_SESSION['BASE'].".itemestoquealmox
                            left join ".$_SESSION['BASE'].".almoxarifado on almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
                            where Codigo_Item = '$id' and Qtde_Disponivel > 0 OR Codigo_Item = '$id' and Qtde_Disponivel < 0 ");
                                
                            $retornoProdutos = $consultaProduto->fetchAll();  
                                foreach ($retornoProdutos as $row) {
                                    ?>
                                    <tr>
                                    <td  class="text-center"><?=$row["Descricao"];?> </td>
                                    <td  class="text-center" > <?=strtoupper($row["Qtde_Disponivel"]);?>   
                                    </tr>
                                    <?php
                                }
                                ?>
                            
                            
                                                    
                                </tbody>
                        </table>
                    </div>
              
            </div>
      
      
          </div>        
    </div>

    <div class="col-md-3" style="text-align: center; " id="listparceiro">       
        
                <div class="col-lg-12" >  
                 <div  align="center">
                 <a href="#"  onclick="_estoqueP('<?=$row['CODIGO_FORNECEDOR']?>','<?=$row['CODIGO_FABRICNTE']?>','<?=$row['CODIGO_REFERE']?>')"><?=(strlen($row["DESCRICAO"]) > 201 ? substr($row["DESCRICAO"],0,200)."..." : $row["DESCRICAO"])?> <img src="assets/images/small/pesqparceiropeca.png" alt="image" class="img-responsive" width="100"></a>
                 </div>
                </div>
                <div class="col-lg-12">  
                    <div class=" bg-white" > 
                      <span class="badge badge-inverse m-l-0">Localizar os estoque parceiros </span>
                   </div>
                   </div>  
          
      
      
          </div>        
    </div>
        <?php

        
    }// fim - layout personalizado
    } catch (PDOException $e) {
        ?>
       
                    <h4><?="Erro: " . $e->getMessage()?></h4>
                <
        <?php
    }
}

else if ($_acao == 9) { //ver estoqque
    try { 
        
      

        $id = $_parametros['id-altera']; 

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

        //DADOS DO PRODUTO
    
    
        $consultaP = $pdo->query("Select CODIGO_FORNECEDOR,ENDERECO1,ENDERECO2,ENDERECO3,QTDE_EST_MINIMO,PRECO_CONSUM_FABRICA,PRECO_CUSTO,
        PERC_IPI,MODELO_APLICADO,Qtde_Reserva_Tecnica 
         from ".$_SESSION['BASE'].".itemestoque       
        where CODIGO_FORNECEDOR = '$id' ");
         
       $retornoP = $consultaP->fetch();  
    
         $consultaProduto = $pdo->query("Select * from ".$_SESSION['BASE'].".itemestoquealmox
         left join ".$_SESSION['BASE'].".almoxarifado on almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
         where Codigo_Item = '$id' and Qtde_Disponivel > 0 OR Codigo_Item = '$id' and Qtde_Disponivel < 0 ");
          
        $retornoProdutos = $consultaProduto->fetchAll();  
        $t = 0;
             
       ?>
        <div class="row ">
            <div class="col-md-12">
           
            <div class="card-box">
               
            <div class="text-left"><span class="label label-inverse" style="font-size: 85%;"> Estoque por Almoxarifado </span></div>
                            <table  class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Almoxarifado</th>
                                    <th class="text-center">Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                    foreach ($retornoProdutos as $row) {
                                        $t = $t + $row["Qtde_Disponivel"];
                            
                                ?>
                            
                                <tr>
                                <td  class="text-center"><?=$row["Descricao"];?> </td>
                                <td  class="text-center" > <?=strtoupper($row["Qtde_Disponivel"]);?>      </td>
                            </tr>
                            
                            <?php
                                        
                        }
                        
                        ?>
                        
                        </tbody>
                            </table>
                            <div class="text-right"> Qtde Total: <strong><?=$t;?> </strong> </div>
                            <?php if($retornoP['Qtde_Reserva_Tecnica'] > 0) {
                                ?>
                                <div class="text-right"> Qtde Reservado: <strong><?=$retornoP['Qtde_Reserva_Tecnica'];?> </strong> </div>
                                <?php
                            } ?>
                              <div class="text-left"><span class="label label-inverse" style="font-size: 85%;"> Anotação Reserva </span></div>
                            <table class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Ordem Servi&ccedil;o</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-center">Data Inclus&atilde;o OS</th>
                                </tr>
                            </thead> 
                            <tbody>
                            <?php
                            $consultaOS = $pdo->query("Select reserva,Numero_OS,DATE_FORMAT(Data_entrada,'%d/%m/%Y') as dt	
                                                        from ".$_SESSION['BASE'].".chamadapeca 
                                                        where Codigo_Peca_OS = '$id'  and reserva > 0 ");           
                            $retorno = $consultaOS->fetchAll();  
                            $tr = 0;
                            foreach ($retorno as $row) {
                                        $aux = $i % 2;	
                                            
                                            if ($aux == 0)	{	
                                                $cor = "#F2F2F2";}
                                            else { 
                                                $cor = "#FFFFFF";}	

                                                $tr = $tr + $row["reserva"];
                            
                                ?>
                            
                                    <tr>
                                        <td class="text-center"><?=$row["Numero_OS"];?></td>
                                        <td class="text-center"><?=$row["reserva"];?></td>
                                        <td class="text-center"><?=$row["dt"];?></td>
                            
                            </tr>          
                            <?php
                                    
                        }
                        
                        ?>
                            </tbody>
                            </table>
                            <div class="text-right"> Qtde Total: <strong><?=$tr;?> </strong> </div>
                         
                              <div class="text-left"><span class="label label-inverse" style="font-size: 85%;"> O.S em requisição (Montagem/Conferência) </span><code>Lista de <?=date('Y');?></code></div>
                            <table class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="text-center">Requisição</th>
                                    <th class="text-center">Ordem Servi&ccedil;o</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-center">Data </th>
                                </tr>
                            </thead> 
                            <tbody>
                            <?php
                            $dia       = date('d');
                            $mes       = date('m');
                            $ano       = date('Y');                                                    
                            
                            $_datafim      = $ano . "-" . $mes . "-" . $dia;
                            $consultaOS = $pdo->query("SELECT req_numero,Qtde,Codigo_Chamada,date_format(req_data,'%d/%m/%Y') as dt  FROM ".$_SESSION['BASE'].".requisicao  
                            LEFT JOIN ".$_SESSION['BASE'].".movtorequisicao_historico ON  req_numero = Num_Movto
                            WHERE  Codigo_Item = '$id'  and req_status = 2 and req_data >= '$_datafim' OR codigo_Item = '$id'  and req_status = 3 and req_data >= '$_datafim'  ");           
                            $retorno = $consultaOS->fetchAll();  
                            $trr = 0;
                            foreach ($retorno as $row) {
                                        $aux = $i % 2;	
                                            
                                            if ($aux == 0)	{	
                                                $cor = "#F2F2F2";}
                                            else { 
                                                $cor = "#FFFFFF";}	

                                                $trr = $trr + $row["Qtde"];
                            
                                ?>
                            
                                    <tr>
                                         <td class="text-center"><?=$row["req_numero"];?></td>
                                        <td class="text-center"><?=$row["Codigo_Chamada"];?></td>
                                        <td class="text-center"><?=$row["Qtde"];?></td>
                                        <td class="text-center"><?=$row["dt"];?></td>
                            
                            </tr>          
                            <?php
                                    
                        }
                        
                        ?>
                            </tbody>
                            </table>
                            <div class="text-right"> Qtde Total: <strong><?=$trr;?> </strong> </div>
               </div>
            </div>
            <!--
            <div class="col-md-6">
                 <strong>Detalhe</strong>
                <div class="card-box">
                    <div class="profile-widget text-center">
                        <ul class="list-inline widget-list clearfix">
                            <li class="col-md-4">Total Estoque<span><?=$t;?></span></li>
                            <li class="col-md-4">Est.Mímino <span><?=$retornoP["QTDE_EST_MINIMO"];?></span></li>
                            <li class="col-md-4">Qtde Solicitada<span>-</span></li>
                            <li class="col-md-4"><?=$_label_EderA;?><span><?=$retornoP["ENDERECO1"];?></span></li>
                            <li class="col-md-4"><?=$_label_EderB;?> <span><?=$retornoP["ENDERECO2"];?></span></li>
                            <li class="col-md-4"><?=$_label_EderC;?><span><?=$retornoP["ENDERECO3"];?></span></li>
                            <?php $custoipi = $retornoP["PRECO_CUSTO"] + ($retornoP["PRECO_CUSTO"]*$retornoP["PERC_IPI"]/100); ?>
                            <li class="col-md-4">Vlr. Últ Entr+IPI<span>R$ <?=number_format($custoipi,2,',','.')?></span></li>
                            <li class="col-md-4">% IPI<span><?=number_format($retornoP["PERC_IPI"],2,',','.')?></span></li>
                            <li class="col-md-4">Vlr.Sugerido <span>R$ <?=number_format($retornoP["PRECO_CONSUM_FABRICA"],2,',','.')?></span></li>
                           
                        </ul>
                        <ul class="list-inline widget-list clearfix">
                             <li class="col-md-4">Especificação<span><?=$retornoP['MODELO_APLICADO'];?></span></li>
                        </ul>
                        
                       
                    </div>
                </div>        
            </div>
                    -->
        
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
 * Atualiza Produto
 */
else if ($_acao == 3) {
    if (empty($_parametros["produto-codigo"]) || empty($_parametros["produto-descricao"]) || empty($_parametros["produto-situacao"]) AND  $tipoEmpresa == 1 || empty($_parametros["produto-ncm"])  || empty($_parametros["produto-unidade"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Verifique a descrição, unidade, situação tributária e NCM do produto! </h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        date_default_timezone_set('America/Sao_Paulo');
        $_parametros["produto-custo"] = LimpaVariavel($_parametros["produto-custo"]);      
        $_parametros["produto-custoant"] = LimpaVariavel($_parametros["produto-custoant"]);
        $_parametros["produto-venda"] = LimpaVariavel($_parametros["produto-venda"]);
        $_parametros["produto-vendahist"] = LimpaVariavel($_parametros["produto-vendahist"]);
        $_parametros["produto-vendapor"] = LimpaVariavel($_parametros["produto-vendapor"]);
        $_parametros["produto-vlanterior"] = LimpaVariavel($_parametros["produto-vlanterior"]);
        $_parametros["produto-tab1"] = LimpaVariavel($_parametros["produto-tab1"]);
        $_parametros["produto-tab1-por"] = LimpaVariavel($_parametros["produto-tab1-por"]);
        $_parametros["produto-tab1-ant"] = LimpaVariavel($_parametros["produto-tab1-ant"]);
        $_parametros["produto-tab2"] = LimpaVariavel($_parametros["produto-tab2"]);
        $_parametros["produto-tab2-por"] = LimpaVariavel($_parametros["produto-tab2-por"]);
        $_parametros["produto-tab2-ant"] = LimpaVariavel($_parametros["produto-tab2-ant"]);
        $_parametros["produto-tab3"] = LimpaVariavel($_parametros["produto-tab3"]);
        $_parametros["produto-tab3-por"] = LimpaVariavel($_parametros["produto-tab3-por"]);
        $_parametros["produto-tab3-ant"] = LimpaVariavel($_parametros["produto-tab3-ant"]);
        $_parametros["produto-tab4"] = LimpaVariavel($_parametros["produto-tab4"]);
        $_parametros["produto-tab4-por"] = LimpaVariavel($_parametros["produto-tab4-por"]);
        $_parametros["produto-tab4-ant"] = LimpaVariavel($_parametros["produto-tab4-ant"]);
        $_parametros["produto-peso"] = LimpaVariavel($_parametros["produto-peso"]);
        $_parametros["produto-customed"] = LimpaVariavel($_parametros["produto-customed"]);
        $_parametros["produto-desconto"] = LimpaVariavel($_parametros["produto-desconto"]);
        $_parametros["produto-ultent"] = date("".$_parametros["produto-ultent"]." H:i:s", time());
        $_parametros["produto-promo"] = LimpaVariavel($_parametros["produto-promo"]);
        $_parametros["produto-min"] = LimpaVariavel($_parametros["produto-min"]);
        $_parametros["produto-max"] = LimpaVariavel($_parametros["produto-max"]);
        $_parametros["produto-qnta"] = LimpaVariavel($_parametros["produto-qnta"]);
        $_parametros["produto-qntb"] = LimpaVariavel($_parametros["produto-qntb"]);
        $_parametros["produto-qtndesc"] = LimpaVariavel($_parametros["produto-qtndesc"]);
        $_parametros["produto-icms"] = LimpaVariavel($_parametros["produto-icms"]);
        $_parametros["produto-redicms"] = LimpaVariavel($_parametros["produto-redicms"]);
        $_parametros["produto-ipi"] = LimpaVariavel($_parametros["produto-ipi"]);
        $_parametros["produto-devipi"] = LimpaVariavel($_parametros["produto-devipi"]);
        $_parametros["produto-qtnreceita"] = LimpaVariavel($_parametros["produto-qtnreceita"]);
        $_parametros["produto-unidade"] = LimpaVariavel($_parametros["produto-unidade"]);
        $_parametros["_enderComplemento"] = LimpaVariavel($_parametros["_enderComplemento"]);
        $_parametros["_enderC"] = LimpaVariavel($_parametros["_enderC"]);
        $_parametros["produto-codsimilar"] = LimpaVariavel($_parametros["produto-codsimilar"]);

        $_parametros["icmssubstituto"] = LimpaVariavel($_parametros["icmssubstituto"]);
        $_parametros["percICMSST"] = LimpaVariavel($_parametros["percICMSST"]);
        $_parametros["percICMSSTRET"] = LimpaVariavel($_parametros["percICMSSTRET"]);
        $_parametros["mva"] = LimpaVariavel($_parametros["mva"]);
        $_parametros["substTributaria"] = LimpaVariavel($_parametros["substTributaria"]);

        if($_parametros["produto-vendahist"] != $_parametros["produto-venda"]){
            $updtcusto = "Dt_Ult_alteracaovlVenda = CURRENT_DATE(), vl_Ant_alteracaovlVenda = '".$_parametros["produto-vendahist"]."',";
        }
       
     

        try {
            $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET $updtcusto CODIGO_FORNECEDOR = ?, Codigo_Barra = ?, DESCRICAO = ?, Descricao_Reduzida = ?, GRU_GRUPO = ?, CODIGO_LINHA = ?, PRECO_CUSTO = ?, Tab_Preco_5 = ?, Perc_Tab_preco5 = ?, Tab_Preco_1 = ?, Perc_Tab_preco1 = ?, Tab_Preco_2 = ?, Perc_Tab_preco2 = ?, Tab_Preco_3 = ?, Perc_Tab_preco3 = ?, Tab_Preco_4 = ?, Perc_Tab_preco4 = ?, prod_ativo = ?, prod_ativosite = ?, peso = ?, customedio = ?, PRECO_CONSUM_2 = ?, PRECO_CONSUM_FABRICA = ?, QTDE_EST_MINIMO = ?, Estoque_Maximo = ?, item_qtde_A = ?, item_qtde_B = ?, Multiplo_Compra = ?, Dias_Validade = ?, ENDERECO1 = ?, ENDERECO2 = ?, item_lote_A = ?, item_lote_B = ?, item_validade_A = ?, item_validade_B = ?, item_extraA = ?, item_extraB = ?, promocao = ?, impressora = ?, GRUPO_PECAS = ?, MODELO_APLICADO = ?, Nome_linha = ?, DATA_ULT_ENTRADA = ?, PERC_ICMS = ?, Perc_Icms_reducao = ?, PERC_IPI = ?, PERC_IPI_DEVOLUCAO = ?, SIT_TRIBUTARIA = ?, CFOPD = ?, CFOPF = ?, msg_red_Icms = ?, Cod_Class_Fiscal = ?, ind_ingrediente = ?, ind_com_ingrediente = ?, Qtde_Reserva_tecnica = ?, descricao_etiqueta = ?, nome_produtor = ?, cnpj_produtor = ?, peso_liquido = ?, ingrediente = ?, ingrediente2 = ?, ingrediente3 = ?, ingrediente4 = ?, vlr_energecico = ?, vlr_gorudraTrans = ?, vlr_gorduraTotal = ?, vlr_gorduraSaturada = ?, vlr_carboidrato = ?, vlr_proteina = ?, vlr_fibra = ?, calcio = ?, ferro = ?, vlr_sodio = ?, porcao = ?, desc_base = ? , UNIDADE_MEDIDA = ?,
             CODIGO_FABRICANTE = ?, ENDERECO3 = ?, ENDERECO_COMP = ? ,CODIGO_SIMILAR = ?,
             PERC_ICMSST = ?, PERC_ICMSSTRET = ?, VLR_SUBSTITUTO = ?, MVA = ?, IND_SUBTTRIBUTARIA = ?, Codigo_Referencia_Fornec = ?, id_pis = ?, id_cofins = ?
              WHERE CODIGO_FORNECEDOR = ?");
            $statement->bindParam(1, $_parametros["produto-codigo"]);
            $statement->bindParam(2, $_parametros["produto-codbarra"]);
            $statement->bindParam(3, $_parametros["produto-descricao"]);
            $statement->bindParam(4, $_parametros["produto-resumo"]);
            $statement->bindParam(5, $_parametros["produto-grupo"]);
            $statement->bindParam(6, $_parametros["produto-linha"]);
            $statement->bindParam(7, $_parametros["produto-custo"]);
            $statement->bindParam(8, $_parametros["produto-venda"]);
            $statement->bindParam(9, $_parametros["produto-vendapor"]);
            $statement->bindParam(10, $_parametros["produto-tab1"]);
            $statement->bindParam(11, $_parametros["produto-tab1-por"]);
            $statement->bindParam(12, $_parametros["produto-tab2"]);
            $statement->bindParam(13, $_parametros["produto-tab2-por"]);
            $statement->bindParam(14, $_parametros["produto-tab3"]);
            $statement->bindParam(15, $_parametros["produto-tab3-por"]);
            $statement->bindParam(16, $_parametros["produto-tab4"]);
            $statement->bindParam(17, $_parametros["produto-tab4-por"]);
            $statement->bindParam(18, $_parametros["produto-ativo"]);
            $statement->bindParam(19, $_parametros["produto-ativosite"]);
            $statement->bindParam(20, $_parametros["produto-peso"]);
            $statement->bindParam(21, $_parametros["produto-customed"]);
            $statement->bindParam(22, $_parametros["produto-desconto"]);
            $statement->bindParam(23, $_parametros["produto-promo"]);
            $statement->bindParam(24, $_parametros["produto-min"]);
            $statement->bindParam(25, $_parametros["produto-max"]);
            $statement->bindParam(26, $_parametros["produto-qnta"]);
            $statement->bindParam(27, $_parametros["produto-qntb"]);
            $statement->bindParam(28, $_parametros["produto-qtndesc"]);
            $statement->bindParam(29, $_parametros["produto-diasval"]);
            $statement->bindParam(30, $_parametros["_enderA"]);
            $statement->bindParam(31, $_parametros["_enderB"]);
            $statement->bindParam(32, $_parametros["produto-lotea"]);
            $statement->bindParam(33, $_parametros["produto-loteb"]);
            $statement->bindParam(34, $_parametros["produto-venda-a"]);
            $statement->bindParam(35, $_parametros["produto-venda-b"]);
            $statement->bindParam(36, $_parametros["produto-extraa"]);
            $statement->bindParam(37, $_parametros["produto-extrab"]);
            $statement->bindParam(38, $_parametros["produto-msgetiqueta"]);
            $statement->bindParam(39, $_parametros["produto-impressora"]);
            $statement->bindParam(40, $_parametros["produto-lista"]);
            $statement->bindParam(41, $_parametros["produto-especificacao"]);
            $statement->bindParam(42, $_parametros["produto-descweb"]);
            $statement->bindParam(43, $_parametros["produto-ultent"]);
            $statement->bindParam(44, $_parametros["produto-icms"]);
            $statement->bindParam(45, $_parametros["produto-redicms"]);
            $statement->bindParam(46, $_parametros["produto-ipi"]);
            $statement->bindParam(47, $_parametros["produto-devipi"]);
            $statement->bindParam(48, $_parametros["produto-situacao"]);
            $statement->bindParam(49, $_parametros["produto-cfopuf"]);
            $statement->bindParam(50, $_parametros["produto-cfop"]);
            $statement->bindParam(51, $_parametros["produto-msgicms"]);
            $statement->bindParam(52, $_parametros["produto-ncm"]);
            $statement->bindParam(53, $_parametros["produto-ingrediente"]);
            $statement->bindParam(54, $_parametros["produto-cingrdiente"]);
            $statement->bindParam(55, $_parametros["produto-qtnreceita"]);
            $statement->bindParam(56, $_parametros["produto-descricao-emb"]);
            $statement->bindParam(57, $_parametros["produto-nome-produtor"]);
            $statement->bindParam(58, $_parametros["produto-cnpj-produtor"]);
            $statement->bindParam(59, $_parametros["produto-pesoliquido"]);
            $statement->bindParam(60, $_parametros["produto-ingrediente-1"]);
            $statement->bindParam(61, $_parametros["produto-ingrediente-2"]);
            $statement->bindParam(62, $_parametros["produto-ingrediente-3"]);
            $statement->bindParam(63, $_parametros["produto-ingrediente-4"]);
            $statement->bindParam(64, $_parametros["produto-valorcalorico"]);
            $statement->bindParam(65, $_parametros["produto-gorduratrans"]);
            $statement->bindParam(66, $_parametros["produto-gordurastotais"]);
            $statement->bindParam(67, $_parametros["produto-colesterol"]);
            $statement->bindParam(68, $_parametros["produto-carboidratos"]);
            $statement->bindParam(69, $_parametros["produto-proteinas"]);
            $statement->bindParam(70, $_parametros["produto-fibra"]);
            $statement->bindParam(71, $_parametros["produto-calcio"]);
            $statement->bindParam(72, $_parametros["produto-ferro"]);
            $statement->bindParam(73, $_parametros["produto-sodio"]);
            $statement->bindParam(74, $_parametros["produto-porcao"]);
            $statement->bindParam(75, $_parametros["produto-valorbase"]);
            $statement->bindParam(76, $_parametros["produto-unidade"]);
            $statement->bindParam(77, $_parametros["produto-codfabricante"]);
            $statement->bindParam(78, $_parametros["_enderC"]);
            $statement->bindParam(79, $_parametros["_enderComplemento"]);
            $statement->bindParam(80, $_parametros["produto-codsimilar"]);

            $statement->bindParam(81, $_parametros["produto-percICMSST"]);
            $statement->bindParam(82, $_parametros["produto-icmssubstituto"]);
            $statement->bindParam(83, $_parametros["produto-percICMSSTRET"]);
            $statement->bindParam(84, $_parametros["produto-mva"]);
            $statement->bindParam(85, $_parametros["substTributaria"]);
            $statement->bindParam(86, str_pad(trim($_parametros["produto-codsku"]), 18, '0', STR_PAD_LEFT));
            $statement->bindParam(87, $_parametros["produto-pis"]);
            $statement->bindParam(88, $_parametros["produto-cofins"]);
            $statement->bindParam(89, $_parametros["produto-codigo"]);
            
            
            $statement->execute();
            
            $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoquealmox SET codref_fabricante =  ? WHERE 	Codigo_Item  = ? ");
            $statement->bindParam(1, $_parametros["produto-codfabricante"]);
            $statement->bindParam(2, $_parametros["produto-codigo"]);           
            $statement->execute();

            //historico
            $_parametros = array(                
                'tipo' =>'1',
                'login' =>$loginuser,
                'loginnome' => $loginNOME,
                'codigoitem' =>$_parametros["produto-codigo"],
                'vlrcustoatual' =>$_parametros["produto-custo"],
                'vlrvendaatual' =>$_parametros["produto-venda"],
                'vendaAnterior' =>$_parametros["produto-custoant"],
                'custoAnterior' =>$_parametros["produto-vendahist"],
                'vlrcustoTab1'=>$_parametros["produto-tab1"],                 
                'vlrcustoTab2'=>$_parametros["produto-tab2"], 
                'vlrcustoTab3'=>$_parametros["produto-tab3"], 
                'vlrcustoTab4'=>$_parametros["produto-tab4"], 
                'vlrcustoAnTab1'=>$_parametros["produto-tab1-ant"], 
                'vlrcustoAnTab2'=>$_parametros["produto-tab2-ant"], 
                'vlrcustoAnTab3'=>$_parametros["produto-tab3-ant"], 
                'vlrcustoAnTab4'=>$_parametros["produto-tab4-ant"] 
            );
            $_parametros =  array_merge($_parametros);
            $ret =  Estoque::gravarAlteracaoPrecoCadastro($_parametros);
          //  $_parametros = ""; 
            
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Atualizado!</h2>
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
 * Excluir Produto
 */
else if ($_acao == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".itemestoque WHERE Codigo = :id");
        $statement->bindParam(':id', $_parametros["id-produto"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Produto Excluído!</h2>
                        <button class="btn btn-default waves -effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
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

else if ($_acao == 10) { //localizar parceiros
    try {
        $codfabricante = $_parametros['id-reffabr']; 
        ?>
        <div style="text-align: center">
        <div class="col-lg-12">  
               <div style="background-color: #e6e6e6;" >     
               <h4> Estoque de Parceiros   </h4>
           </div>
           <div style=" height: 260px; overflow: auto; border: 1px solid #ccc; padding: 10px;">
                   <table class="table table-striped table-bordered nowrap "  style="font-size: 11px ; font-weight: bold; " cellspacing="0" width="100%">
                       <tbody>  
                           <?php 
                           $totparceiros = 0;  $totregistros = 0;
                           $consultaEmp= $pdo->query("SELECT idref FROM info.consumidor WHERE CODIGO_CONSUMIDOR =  '".$_SESSION['CODIGOCLI']."'");
                           $retornoEmp = $consultaEmp->fetch();
                           $idsolicitante =  $retornoEmp['idref'];

                            $sq = "Select eve_loginsolicitante,eve_loginautorizador,consumidor_base,Nome_Fantasia FROM bd_prisma.estoquebvinculoemp
                                    LEFT JOIN  info.consumidor ON eve_loginautorizador = idref
                                    where eve_loginsolicitante  = '". $idsolicitante."' and eve_status = 2 ";
                             
                            $consultaParceiro = $pdo->query($sq);
                            $retornoParceiro = $consultaParceiro->fetchAll();  
                            foreach ($retornoParceiro as $parceiro) {
                                $totparceiros++;
                                 $sql = "Select * from ".$parceiro['consumidor_base'].".itemestoquealmox                                     
                                        where codref_fabricante = '$codfabricante' and (Qtde_Disponivel-Qtde_Reservada) > 0 AND Codigo_Almox = '1'";
                                 
                                        $consultaProduto = $pdo->query($sql);
                                        
                                        $retornoProdutos = $consultaProduto->fetchAll();  
                                            foreach ($retornoProdutos as $row) {
                                                $totregistros++;
                                                ?>
                                                <tr>
                                                <td  class="text-center"><?=$parceiro['Nome_Fantasia'];?> </td>
                                                <td  class="text-center" > <?=strtoupper($row["Qtde_Disponivel"]);?>   
                                                </tr>
                                                <?php
                                            }
                                }
                           ?>
                       
                       
                                               
                           </tbody>
                   </table>
               </div>
               Foram encontrado <?=$totregistros;?> registros de <?=$totparceiros;?> parceiros
         
       </div>
 
 
     </div> 
     <?php
    } catch (PDOException $e) {
        ?>
       
                    <h4><?="Erro: " . $e->getMessage()?></h4>
               
        <?php
    }
}

