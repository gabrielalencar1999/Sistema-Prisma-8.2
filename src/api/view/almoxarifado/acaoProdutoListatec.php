<?php
include("../../api/config/iconexao.php");
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

//empresa_vizCodInt codigo visualização interno
$query = ("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  from  " . $_SESSION['BASE'] . ".parametro  ");
$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {
    $_vizCodInterno = $rst["empresa_vizCodInt"];
    
}

/**
 * Cadastra Produto
 */
if ($acao["acao"] == 1) {
    /*
    if (empty($_parametros["produto-codigo"]) || empty($_parametros["produto-descricao"]) || empty($_parametros["produto-situacao"]) || empty($_parametros["produto-ncm"]) || empty($_parametros["produto-unidade"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o código, descrição, unidade, situação tributária e NCM do produto!</h2>
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
     
        try {
            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoque(CODIGO_FORNECEDOR, Codigo_Barra, DESCRICAO, Descricao_Reduzida, GRU_GRUPO, CODIGO_LINHA, PRECO_CUSTO, Tab_Preco_5, Perc_Tab_preco5, Tab_Preco_1, Perc_Tab_preco1, Tab_Preco_2, Perc_Tab_preco2, Tab_Preco_3, Perc_Tab_preco3, Tab_Preco_4, Perc_Tab_preco4, prod_ativo, prod_ativosite, peso, customedio, PRECO_CONSUM_2, PRECO_CONSUM_FABRICA, QTDE_EST_MINIMO, Estoque_Maximo, item_qtde_A, item_qtde_B, Multiplo_Compra, Dias_Validade, ENDERECO1, ENDERECO2, item_lote_A, item_lote_B, item_validade_A, item_validade_B, item_extraA, item_extraB, promocao, impressora, GRUPO_PECAS, MODELO_APLICADO, Nome_linha, DATA_ULT_ENTRADA, PERC_ICMS, Perc_Icms_reducao, PERC_IPI, PERC_IPI_DEVOLUCAO, SIT_TRIBUTARIA, CFOPD, CFOPF, msg_red_Icms, Cod_Class_Fiscal, ind_ingrediente, ind_com_ingrediente, Qtde_Reserva_tecnica, descricao_etiqueta, nome_produtor, cnpj_produtor, peso_liquido, ingrediente, ingrediente2, ingrediente3, ingrediente4, vlr_energecico, vlr_gorudraTrans, vlr_gorduraTotal, vlr_gorduraSaturada, vlr_carboidrato, vlr_proteina, vlr_fibra, calcio, ferro, vlr_sodio, porcao, desc_base,UNIDADE_MEDIDA,CODIGO_FABRICANTE,ENDERECO3,ENDERECO_COMP) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?)");
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

          
            $statement->execute();

            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES (?, '1')");
            $statement->bindParam(1, $_parametros["produto-codigo"]);
            
            $statement->execute();

            $consultaParametro = $pdo->query("SELECT Ult_Cod_Peca FROM ".$_SESSION['BASE'].".parametro");
            $retornoParametro = $consultaParametro->fetch();

            if (($retornoParametro["Ult_Cod_Peca"]+1) == $_parametros["produto-codigo"]) {
                $idPecaAtt = intval($retornoParametro["Ult_Cod_Peca"]) + 1;

                $updateParametro = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".parametro SET Ult_Cod_Peca = :id");
                $updateParametro->bindParam(":id", $idPecaAtt);
                $updateParametro->execute();
            }

         
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
    */
}
/*
 * Listar Produtos
 * */
else if ($acao["acao"] == 2) {
  
    // $consultaUsuario = $pdo->query("SELECT usuario_perfil2 FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION["IDUSER"]."'");
    // $retornoUsuario = $consultaUsuario->fetch();

    // $consultaPermissao = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".permissao WHERE permissao_id = '".$retornoUsuario["usuario_perfil2"]."'");
    // $retornoPermissao = $consultaPermissao->fetch();

    $consultaColunas = $pdo->query("SELECT visualiza_tab1,visualiza_tab2,visualiza_tab3,visualiza_tab4,visualiza_tab5,
    visualiza_tab1tec,visualiza_tab2tec,visualiza_tab3tec,visualiza_tab4tec,visualiza_tab5tec,     
    label_tab1,label_tab2,label_tab3,label_tab4,label_tab5,visualiza_tab6
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
        WHERE Codigo_Almox  = 1 and Ind_Prod <> 2 and $grupoPesquisa CODIGO_FORNECEDOR = '$busca' $_filativo 
        GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
    }
    else if ($_parametros["produto-filtro"] == 1) {
        $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE Codigo_Almox  = 1 and  Ind_Prod <> 2 and $grupoPesquisa Codigo_Barra = '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
    }
    else if ($_parametros["produto-filtro"] == 3) {
        $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE Codigo_Almox  = 1 and  Ind_Prod <> 2 and $grupoPesquisa ".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE = '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
    }
    else {
        $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE Codigo_Almox  = 1 and  Ind_Prod <> 2 and $grupoPesquisa DESCRICAO LIKE '%$busca%' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
    }

    $retornoProdutos = $consultaProduto->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Descrição</th>  
            <?php if($_vizCodInterno == 1){
                ?>
                <th class="text-center">Cód.Fabricante</th>
            <?php }else { ?>
                <th class="text-center">Cód. Barras</th>
            <?php } ?>         
            <th class="text-center">Estoque</th>
            <th class="text-center">Endereço</th>
            <th class="text-center">Especificação</th>
            <?=$retornoColunas["visualiza_tab5tec"] == -1 ? "<th class='text-center'>".$retornoColunas["label_tab5"]."</th>" : ""?> 
            <?=$retornoColunas["visualiza_tab4tec"] == -1 ? "<th class='text-center'>".$retornoColunas["label_tab4"]."</th>" : ""?>
            <?=$retornoColunas["visualiza_tab3tec"] == -1 ? "<th class='text-center'>".$retornoColunas["label_tab3"]."</th>" : ""?>
            <?=$retornoColunas["visualiza_tab2tec"] == -1 ? "<th class='text-center'>".$retornoColunas["label_tab2"]."</th>" : ""?>
            <?=$retornoColunas["visualiza_tab1tec"] == -1 ? "<th class='text-center'>".$retornoColunas["label_tab1"]."</th>" : ""?>
            
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retornoProdutos as $row) {
            ?>
            <tr class="gradeX">
                <td><?=$row["DESCRICAO"]?></td>
                <?php if($_vizCodInterno == 1){
                ?>
                  <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
                <?php }else { ?>
                    <td class="text-center"><?=$row["Codigo_Barra"]?></td>
                <?php } 
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
                
                if($row["Qtde_Reserva_Tecnica"] > 0 ) { 
                    $reserva = '<span class="badge  badge-warning">R</span>';
                }else{
                    $reserva  = "";
                }
                ?>

              
             
                
             
                <td class="text-center"><a href="#" data-toggle="modal" data-target="#custom-modal-estoque" onclick="_estoque('<?=$row['CODIGO_FORNECEDOR']?>')"><?=$row["tot_item"]?></a><?=$reserva;?>
              
            </td>
                <td class="text-center"><?=$ender;?></td>
                <td class="text-center"><?=$row["MODELO_APLICADO"]?></td>
              
               <?=$retornoColunas["visualiza_tab5tec"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_5"],2,',','.')."</td>" : ""?>
               <?=$retornoColunas["visualiza_tab4tec"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_4"],2,',','.')."</td>" : ""?>
               <?=$retornoColunas["visualiza_tab3tec"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_3"],2,',','.')."</td>" : ""?>
               <?=$retornoColunas["visualiza_tab2tec"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_2"],2,',','.')."</td>" : ""?>
               <?=$retornoColunas["visualiza_tab1tec"] == -1 ? "<td class='text-center'>".number_format($row["Tab_Preco_1"],2,',','.')."</td>" : ""?>
            </tr>
            <?php
             //verificar se existe peca similar
             if($row["CODIGO_SIMILAR"] != "" and  $_parametros["produto-filtro"] == 3 and  	$row['CODIGO_SIMILAR'] != $row['CODIGO_FABRICANTE']) {
                    $consultaProdutoSimilar = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE Codigo_Almox  = 1 and CODIGO_SIMILAR = '".$row['CODIGO_FABRICANTE']."' GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
                    $retornoProdutosSimilar = $consultaProdutoSimilar->fetchAll();
                    foreach ($retornoProdutosSimilar as $row) {
                        ?>
                        <tr class="gradeX">
                            <td><?=(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"],0,37)."..." : $row["DESCRICAO"])?></td>
                            <?php if($_vizCodInterno == 1){
                            ?>
                              <td class="text-center"><?=$row["CODIGO_FABRICANTE"]?></td>
                            <?php }else { ?>
                                <td class="text-center"><?=$row["Codigo_Barra"]?></td>
                            <?php } ?>                         
                          
                            
                            <td class="text-center"><a href="#" data-toggle="modal" data-target="#custom-modal-estoque" onclick="_estoque('<?=$row['CODIGO_FORNECEDOR']?>')"><?=$row["tot_item"]?></a><?=$reserva;?></td>
                            <td class="text-center"><?=$row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"]." ".$row["ENDERECO_COMP"];?></td>
                            <td class="text-center"><?=$row["MODELO_APLICADO"]?></td>
                            <td class='text-center'><?=number_format($row["Tab_Preco_5"],2,',','.');?></td>
                        </tr>
                        <?php
                      
                    }
             }
        }
        ?>
        </tbody>
    </table>
    <?php
}


else if ($acao["acao"] == 9) { //ver estoqque
    try { ?>
       
        <?php   
        $id = $_parametros['id-altera']; 
    
         $consultaProduto = $pdo->query("Select * from ".$_SESSION['BASE'].".itemestoquealmox
         left join ".$_SESSION['BASE'].".almoxarifado on almoxarifado.Codigo_Almox = itemestoquealmox.Codigo_Almox 
         where Codigo_Item = '$id' and Qtde_Disponivel > 0 OR Codigo_Item = '$id' and Qtde_Disponivel < 0 ");
          
        $retornoProdutos = $consultaProduto->fetchAll();  
       
             
       ?><strong>Estoque por Almoxarifado</strong>
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

          foreach ($retorno as $row) {
                       $aux = $i % 2;	
                           
                           if ($aux == 0)	{	
                               $cor = "#F2F2F2";}
                           else { 
                               $cor = "#FFFFFF";}	
        
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
else if ($acao["acao"] == 3) {
    if (empty($_parametros["produto-codigo"]) || empty($_parametros["produto-descricao"]) || empty($_parametros["produto-situacao"]) || empty($_parametros["produto-ncm"])  || empty($_parametros["produto-unidade"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o código, descrição, unidade, situação tributária e NCM do produto!</h2>
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
     

        try {
            $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET CODIGO_FORNECEDOR = ?, Codigo_Barra = ?, DESCRICAO = ?, Descricao_Reduzida = ?, GRU_GRUPO = ?, CODIGO_LINHA = ?, PRECO_CUSTO = ?, Tab_Preco_5 = ?, Perc_Tab_preco5 = ?, Tab_Preco_1 = ?, Perc_Tab_preco1 = ?, Tab_Preco_2 = ?, Perc_Tab_preco2 = ?, Tab_Preco_3 = ?, Perc_Tab_preco3 = ?, Tab_Preco_4 = ?, Perc_Tab_preco4 = ?, prod_ativo = ?, prod_ativosite = ?, peso = ?, customedio = ?, PRECO_CONSUM_2 = ?, PRECO_CONSUM_FABRICA = ?, QTDE_EST_MINIMO = ?, Estoque_Maximo = ?, item_qtde_A = ?, item_qtde_B = ?, Multiplo_Compra = ?, Dias_Validade = ?, ENDERECO1 = ?, ENDERECO2 = ?, item_lote_A = ?, item_lote_B = ?, item_validade_A = ?, item_validade_B = ?, item_extraA = ?, item_extraB = ?, promocao = ?, impressora = ?, GRUPO_PECAS = ?, MODELO_APLICADO = ?, Nome_linha = ?, DATA_ULT_ENTRADA = ?, PERC_ICMS = ?, Perc_Icms_reducao = ?, PERC_IPI = ?, PERC_IPI_DEVOLUCAO = ?, SIT_TRIBUTARIA = ?, CFOPD = ?, CFOPF = ?, msg_red_Icms = ?, Cod_Class_Fiscal = ?, ind_ingrediente = ?, ind_com_ingrediente = ?, Qtde_Reserva_tecnica = ?, descricao_etiqueta = ?, nome_produtor = ?, cnpj_produtor = ?, peso_liquido = ?, ingrediente = ?, ingrediente2 = ?, ingrediente3 = ?, ingrediente4 = ?, vlr_energecico = ?, vlr_gorudraTrans = ?, vlr_gorduraTotal = ?, vlr_gorduraSaturada = ?, vlr_carboidrato = ?, vlr_proteina = ?, vlr_fibra = ?, calcio = ?, ferro = ?, vlr_sodio = ?, porcao = ?, desc_base = ? , UNIDADE_MEDIDA = ?,
             CODIGO_FABRICANTE = ?, ENDERECO3 = ?, ENDERECO_COMP = ? WHERE CODIGO_FORNECEDOR = ?");
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
            $statement->bindParam(80, $_parametros["produto-codigo"]);
       
            
            $statement->execute();
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
else if ($acao["acao"] == 4) {
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
