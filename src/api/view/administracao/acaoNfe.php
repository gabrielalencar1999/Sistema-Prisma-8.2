<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   


use Functions\NFeService;
use Database\MySQL;
use NFePHP\NFe\Common\Standardize;

//use NFePHP\NFe\Extras\Danfe;
$nfed_chave = "";


$pdo = MySQL::acessabd();

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




function SomarData($data, $dias, $meses, $ano)
{
   //passe a data no formato dd/mm/yyyy 
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
     $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

$_acao = $_POST['acao'];

$idemp = $_parametros["id-empresa"];

$query = $pdo->query("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  
FROM  " . $_SESSION['BASE'] . ".parametro  ");
$retornoItem = $query->fetch();

$_vizCodInterno = $retornoItem['empresa_vizCodInt'];

 if ($_acao == 1) {
    if (empty($_parametros["nf-empresa"]) || empty($_parametros["nf-operacao"])  || empty($_parametros["nf-Contribuinte"])) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h3>Preencha todas as informações Dados NF</div></h3>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
      

            if($_parametros["reservar"] == "on"){
                //PROXIMO NUMERO 
                     // incrementa número de NF
                $consulta = $pdo->query("SELECT nfed_numeronf FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_id = '".$_parametros["nf-id"]."' ");
                $ret = $consulta->fetch();
                

                if ($ret['nfed_numeronf'] == 0 or $ret['nfed_numeronf'] == "") {            
                                        
                    $_sql = "SELECT  empresa_nf,empresa_uf,serie_nfe_producao FROM ". $_SESSION['BASE'] . ".empresa where empresa_id = '".$_parametros["nf-empresa"]."'  ";
                    $consulta = $pdo->query("$_sql");
                    $ret = $consulta->fetch();  
                                                         
    
                    $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_numeronf = ?, nfed_serie = ? WHERE nfed_id = ?");
                    $update->bindParam(1, $ret['empresa_nf']) ;                     
                    $update->bindParam(2, $ret['serie_nfe_producao']);            
                    $update->bindParam(3, $_parametros["nf-id"]); 
                    $update->execute();
    
                    $update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET empresa_nf = empresa_nf + 1  where empresa_id = '".$_parametros["nf-empresa"]."' ");
                    $update->bindParam(1, $empresa);
                    $update->execute();
                  
                }
            }
            $sql = "SELECT NAT_CODIGO,NAT_DESCRICAO,NAT_TIPO FROM ".$_SESSION['BASE'].".cfop where ID = '" . $_parametros["nf-operacao"] . "' limit 1";        
            $statement = $pdo->query("$sql");
            $retorno = $statement->fetchAll();
            $IMPOSTO = 0;
            foreach ($retorno as $row) {      
                $nfed_cfopCod = $row['NAT_CODIGO'];      
                $nfed_cfopdesc = $row['NAT_DESCRICAO'];
                $nfed_tributado = $row['NAT_TIPO']; // tipo igual 0 tributado  1 nao tributado                
            }


            if($nfed_tributado == 0) {            
            //BUSCAR CALCULO TRIBUTOS
             $sql = "SELECT vlrtotal_nfeitens,impostonacional FROM " . $_SESSION['BASE'] . ".NFE_ITENS  
            left join minhaos_cep.impostost ON 	codigoncm = item_nmc          
            WHERE id_nfedados = '" . $_parametros["nf-id"] . "'";        
            $statement = $pdo->query("$sql");
            $retorno = $statement->fetchAll();
            $IMPOSTO = 0;
            foreach ($retorno as $row) {                
                $IMPOSTO = $row["impostonacional"];	
                $IMPOSTOVLR = $IMPOSTOVLR + (($row['vlrtotal_nfeitens'])*$IMPOSTO/100 );
            }
                
        
            $info_tributos = "-Tributos Totais Incidentes(Lei Federal 12.741/2012) R$ ".number_format(($IMPOSTOVLR), 2, ',', '.'); 
          }else{
            $info_tributos = "";
          }
         
            $infoadd = $_parametros["nf-informacaoAdicionais"].$_parametros["nf-nfed_informacaoTributos"];
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".NFE_DADOS SET  
            nfed_empresa = ?, nfed_operacao = ?, nfed_tipocontribuinte = ?, nfed_modalidade = ?, nfed_finalizade = ?,
            nfed_tranportadora = ?,
            nfed_bruto = ?,nfed_liquido = ?, 	nfed_qtdevolume = ?, nfed_marca = ?, nfed_especie = ?, 
            nfed_numerovolume = ?,  nfed_codpgto = ?, nfed_textofatura = ?, nfed_informacaoAdicionais = ?,
            nfed_cfopdesc = ?,nfed_cfop = ?, nfed_chavedev1 = ?,
            nfed_dNome = ?,nfed_dEdereco = ?,nfed_dBairro = ?,nfed_dCidade = ?,nfed_dUF = ?,nfed_dTelefone = ?,nfed_cpfcnpj = ?,
            nfed_dCEP = ?,	nfed_email = ?, nfed_dnumrua = ?, nfed_ie = ?, nfed_cfopid = ?, nfed_informacaoTributos = ?,
            nfed_tipodocumento = ?
            WHERE nfed_id = ? ");
            
         
            //nf-operacao descricao natureza da operacao
            $statement->bindParam(1, $_parametros["nf-empresa"]);
            $statement->bindParam(2, $_parametros["nf-destinooperacao"]);
            $statement->bindParam(3, $_parametros["nf-Contribuinte"]);
            $statement->bindParam(4, $_parametros["nf-tipofrete"]); //frete conta emitente 
            $statement->bindParam(5, $_parametros["nf-finalidade"]);
            $statement->bindParam(6, $_parametros["nf-transportadora"]);
            $statement->bindParam(7, LimpaVariavel($_parametros["nf-pesobruto"]));
            $statement->bindParam(8, LimpaVariavel($_parametros["nf-pesoliquido"]));
            $statement->bindParam(9, $_parametros["nf-qtdetransportadora"]);
            $statement->bindParam(10, $_parametros["nf-marca"]);
            $statement->bindParam(11, $_parametros["nf-especie"]);
            $statement->bindParam(12, $_parametros["nf-volume"]);
            $statement->bindParam(13, $_parametros["nf-formapgto"]);
            $statement->bindParam(14, $_parametros["nf-informacaoFatura"]);
            $statement->bindParam(15, $infoadd);
            $statement->bindParam(16, $nfed_cfopdesc);
            $statement->bindParam(17, $nfed_cfopCod);
            $statement->bindParam(18, $_parametros["nf-chavedev"]);
            $statement->bindParam(19, $_parametros["nomecliente"]);
            $statement->bindParam(20, $_parametros["NF-endereco"]);
            $statement->bindParam(21, $_parametros["NF-bairro"]);
            $statement->bindParam(22, $_parametros["NF-Cidade"]);
            $statement->bindParam(23, $_parametros["NF-estado"]);
            $statement->bindParam(24, $_parametros["NF-telefone"]);
            $statement->bindParam(25, $_parametros["NF-CGC_CPF"]);
            $statement->bindParam(26, $_parametros["NF-CEP"]);
            $statement->bindParam(27, $_parametros["NF-EMAIL"]);
            $statement->bindParam(28, $_parametros["NF-numrua"]);
            $statement->bindParam(29, $_parametros["NF-ie"]);
            $statement->bindParam(30, $_parametros["nf-operacao"]);
            $statement->bindParam(31,  $info_tributos);
            $statement->bindParam(32,  $_parametros["nf-tipo"]);
            $statement->bindParam(33, $_parametros["nf-id"]);
            $statement->execute();

            //informacaoFatura
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                          
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Dados salvo com sucesso! </h2>
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
        exit();
    }
}

if($_vizCodInterno == 1) { 
    $_COD = "CODIGO_FABRICANTE";   
}else{
    $_COD = "codigoproduto_nfeitens";   
}

/*
 * Lista Produtos Notas
 * */
if ($_acao == 2) {
   
    $contultaNF = $pdo->query("SELECT nfed_numeronf 
    FROM ".$_SESSION['BASE'].". NFE_DADOS WHERE nfed_numeronf > '0' and  nfed_id = '" . $_parametros["id-nota"] . "'");
    $retornoNF = $contultaNF->fetch(PDO::FETCH_ASSOC);
    $nfed_chave = $retornoNF["nfed_chave"];

    $sql = "SELECT $_COD,id_nfeitens,item_nfeitens,descricao_nfeitens,unidade_nfeitens,quantidade,
    vlrunitario_nfeitens,
    vBC_nfeitens,item_nmc,situacaotributario_nfeitens,nfe_itensIPI,pICMS_nfeitens,nfe_itensvlrimpostoDevol,nfe_itensPimpostoDevol,
    nfe_itensBaseIcms, vICMS_nfeitens,nfe_itensvlrIPI,
    nfe_itensvlrOutros,nfe_itensvlrDesconto,nfe_itensNumPedido,vlrtotal_nfeitens,
    cfop_nfeitens FROM " . $_SESSION['BASE'] . ".NFE_ITENS 
    LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON codigoproduto_nfeitens = codigo_fornecedor 
    WHERE id_nfedados = '" . $_parametros["id-nota"] . "' ORDER BY id_nfeitens ASC";

    $statement = $pdo->query("$sql");
    $retorno = $statement->fetchAll();
    $totalNota = 0.0;
    ?>
    <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
    <?php
    if ($nfed_chave == "") {
        ?>
        <div class="row text-right">
            <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-buscar">Buscar Produtos<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
        </div>
        <?php
    }
        ?>
        <thead>
        <tr>       
            <th>Seq</th>            
            <th>Cód.</th>   
            <th>Descrição</th>   
            <th>NCM</th>  
            <th class="text-center">Unidade</th>              
            <th class="text-center">Qtde</th>
            <th class="text-center">Vlr Unitário</th>
            <th class="text-center">Vlr Total</th>
            <th class="text-center">Sit Tributária</th>
            <th class="text-center">Aliq. Icms</th>
            <th class="text-center">Base Icms</th>
            <th class="text-center">Vlr Icms</th>           
            <th class="text-center">Vlr Outros</th>
            <th class="text-center">Vlr Desconto</th>
            <th class="text-center">Nº Ped</th>    
            <?=$nfed_chave == "" ? "<th class='text-right'>Ação</th>": ""?>        
        </tr>
        </thead>
        <tbody>
        <?php   
        $seq = 1;
        foreach ($retorno as $row) {
            $_xpICMS = $row["pICMS_nfeitens"];
            $baseIcms= $row["vBC_nfeitens"];
            $vlrIcms = $row["vICMS_nfeitens"];
            if($row["cfop_nfeitens"] == "0" OR $row["cfop_nfeitens"] == "") { 
                             $consultaCfop = $pdo->query("SELECT NAT_CODIGO,NAT_FINALIDADE,NAT_OPERACAO,NAT_CST,NAT_modBC,NAT_pICMS,NAT_modBCST,NAT_pICMSST,NAT_PIS,NAT_COFINS,NAT_ORIGEM FROM ".$_SESSION['BASE'].".cfop where
                 ID = '" . $_parametros['id-cfop']. "' limit 1 ");
                $resultCfop = $consultaCfop->fetch();
          
                $_ret = $resultCfop['NAT_FINALIDADE'];
                $_ret =  $_ret.";".$resultCfop['NAT_OPERACAO'];
                $_origem = $resultCfop["NAT_ORIGEM"];
                $_xCst = $resultCfop["NAT_CST"];
                $_xmodBC = $resultCfop["NAT_modBC"];
                $_xpICMS = $resultCfop["NAT_pICMS"];
                
                $_xvICMS = 0;
                $_xNmodBCST = $resultCfop["NAT_modBCST"];
                $_xpICMSST = $resultCfop["NAT_pICMSST"];
                if($_xpICMS > 0){
                    $vlrIcms  = ($_xpICMS * $row["vlrunitario_nfeitens"]/100);
                    $baseIcms = $row["vlrunitario_nfeitens"];
                }
                $_xPIS = $resultCfop["NAT_PIS"];
                $_xCOFINS = $resultCfop["NAT_COFINS"];
        
                if($_xCst == 0  and $_xCst != "00") {
                    $_xCst =  $result['SIT_TRIBUTARIA'];
                }
                if($_xPIS == 0 ) {
                    $_xPIS =  "07";
                }
                if($_xCOFINS == 0 ) {
                    $_xCOFINS =  "04";
                }

                $statement2 = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_ITENS  SET 
                cfop_nfeitens = ?,
                situacaotributario_nfeitens = ?,
                vICMS_nfeitens = ?,
                vBC_nfeitens = ?,
                pICMS_nfeitens = ?,
                origemimposto_nfeitens = ?, 
                pisCST_nfeitens = ?, 
                cofins_nfeitens = ?                
                WHERE id_nfeitens = ? ");                    
             
                $statement2->bindParam(1,$resultCfop['NAT_CODIGO']);             
                $statement2->bindParam(2, $_xCst);
                $statement2->bindParam(3, $vlrIcms);
                $statement2->bindParam(4, $baseIcms );
                $statement2->bindParam(5, $_xpICMS);               
                $statement2->bindParam(6, $_origem);      
                $statement2->bindParam(7, $_xPIS);   
                $statement2->bindParam(8, $_xCOFINS);                                   
                $statement2->bindParam(9, $row["id_nfeitens"]);               
                $statement2->execute(); 
            }
            ?>
            <tr class="gradeX"> 
                 <td><?=$seq;?></td>   
                <td><?=$row["$_COD"];?></td>   
                
                <td ><?=strlen($row["descricao_nfeitens"]) > 20 ? substr($row["descricao_nfeitens"], 0, 20) . "<br>" : $row["descricao_nfeitens"] ?></td>
                <td ><?=$row["item_nmc"]?></td>                       
                <td class="text-center"><?=$row["unidade_nfeitens"]?></td>
                <td class="text-center"><?=$row["quantidade"]?></td>
                <td class="text-center"><?=number_format($row["vlrunitario_nfeitens"],2,',','.')?></td>
                <td class="text-center"><?=number_format($row["vlrtotal_nfeitens"],2,',','.')?></td>
                <td class="text-center"><?=$row["situacaotributario_nfeitens"]?></td> 
                <td class="text-center"><?=number_format($_xpICMS,2,',','.')?></td>  
                <td class="text-center"><?=number_format($baseIcms,2,',','.')?></td>
            
                <td class="text-center"><?=number_format($vlrIcms,2,',','.')?></td> 
              
                <td class="text-center"><?=number_format($row["nfe_itensvlrOutros"],2,',','.')?></td>  
                <td class="text-center"><?=number_format($row["nfe_itensvlrDesconto"],2,',','.')?></td>  
                <td class="text-center"><?=$row["nfe_itensNumPedido"];?></td>    
                <?php
            if ($nfed_chave == "") {
                ?>
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default edit-row"  onclick="_buscaDadosProdEdit(<?=$row["id_nfeitens"]?>)"><i class="fa fa-pencil "></i></a>
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_exProduto(<?=$row["id_nfeitens"]?>)"><i class="fa fa-trash-o "></i></a>
                </td>
                <?php
            }
            ?>    
            </tr>
            <?php
            $totalNota += $row["vlrtotal_nfeitens"]+$row["nfe_itensvlrOutros"]-$row["nfe_itensvlrDesconto"];
            $seq++;
        }
        ?>
        </tbody>
    </table>
    <div class="alert alert-info text-right">
        Total <strong>R$<?=number_format($totalNota, 2, ',', '.')?></strong>
    </div>
    <?php
    
    $sq = "UPDATE  ". $_SESSION['BASE'] .".NFE_DADOS SET nfed_totalnota = '". $totalNota."' WHERE nfed_id = '".  $_parametros["id-nota"] ."' ";
    $consulta = $pdo->query("$sq");
    $result = $consulta->fetch();
   
}

//buscar produto nfe
if ($_acao == 6){
//empresa_vizCodInt codigo visualização interno
$query = ("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  from  " . $_SESSION['BASE'] . ".parametro  ");
$consulta = $pdo->query($query); 
$result = $consulta->fetch();

    $_vizCodInterno = $result["empresa_vizCodInt"];    

    if($_vizCodInterno == 1){
    
        $COD = 'CODIGO_FABRICANTE';
    }else {
        $COD = 'Codigo_Barra';
        } 

$busca = $_parametros["id-filtro"];

   // if (is_numeric($_parametros["id-filtro"]) && strlen($_parametros["id-filtro"]) >= 13) {
        

        if ($_parametros["sel-filtro"] == 0) {
            $consulta = $pdo->query("SELECT $COD,DESCRICAO,Codigo_Barra,CODIGO_FORNECEDOR,Tab_Preco_5 FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FORNECEDOR = '". $_parametros["id-filtro"] ."' LIMIT 10");
        }
        else if ($_parametros["sel-filtro"] == 1) {
            $consulta = $pdo->query("SELECT $COD,DESCRICAO,Codigo_Barra,CODIGO_FORNECEDOR,Tab_Preco_5 FROM ". $_SESSION['BASE'] .".itemestoque WHERE Codigo_Barra = '". $_parametros["id-filtro"] ."' LIMIT 10");
        }
        else if ($_parametros["sel-filtro"] == 3) {
            $consulta = $pdo->query("SELECT $COD,DESCRICAO,Codigo_Barra,CODIGO_FORNECEDOR,Tab_Preco_5 FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FABRICANTE = '". $_parametros["id-filtro"] ."' LIMIT 10");
           // $consultaProduto = $pdo->query("SELECT CODIGO_SIMILAR,CODIGO,CODIGO_FORNECEDOR,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante,itemestoque.MODELO_APLICADO AS modelox ,Codigo_Barra,Nome_Modelo,Nome_linha,itemestoque.COD_FABRICANTE  AS fab,Arq_Foto1,UNIDADE_MEDIDA,itemestoque.ENDERECO1,itemestoque.ENDERECO2,Cod_Class_Fiscal,CFOPD,CFOPF,grupo.GRU_DESC,linha_descricao,Cod_Class_Fiscal,CFOPD,CFOPF,DATE_FORMAT(DATA_CADASTRO,'%d/%m/%Y') AS datacad,".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE,ENDERECO3,itemestoque.ENDERECO3,ENDERECO_COMP,MODELO_APLICADO, Qtde_Reserva_Tecnica FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE LEFT JOIN ".$_SESSION['BASE'].".grupo ON grupo.GRU_GRUPO = itemestoque.GRU_GRUPO LEFT JOIN ".$_SESSION['BASE'].".linha ON linha.linha_codigo = itemestoque.CODIGO_LINHA LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE Codigo_Almox  = 1 and  Ind_Prod <> 2 and $grupoPesquisa ".$_SESSION['BASE'].".itemestoque.CODIGO_FABRICANTE = '$busca' $_filativo GROUP BY CODIGO,CODIGO_FORNECEDOR,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME, itemestoque.MODELO_APLICADO,Nome_Modelo,Nome_linha,itemestoque.ENDERECO1,itemestoque.ENDERECO2,ENDERECO_COMP,MODELO_APLICADO ORDER BY itemestoque.DESCRICAO LIMIT 100");
        }
         else if ($_parametros["sel-filtro"] == 4) {
            $consulta = $pdo->query("SELECT $COD,DESCRICAO,Codigo_Barra,CODIGO_FORNECEDOR,Tab_Preco_5 FROM ". $_SESSION['BASE'] .".itemestoque WHERE Codigo_Referencia_Fornec  like '".str_pad(trim($_parametros["id-filtro"]), 18, '0', STR_PAD_LEFT)."' LIMIT 10");
          
        }
        //Codigo_Referencia_Fornec
        else {
            $consulta = $pdo->query("SELECT $COD,DESCRICAO,Codigo_Barra,CODIGO_FORNECEDOR,Tab_Preco_5 FROM ". $_SESSION['BASE'] .".itemestoque WHERE DESCRICAO like '%". $_parametros["id-filtro"] ."%' LIMIT 100" );           
        }

        $result = $consulta->fetchAll();
        ?>
        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
            <thead>
            <tr>
                
                <th>Descrição</th>                         
                <th>Cód. Fabricante</th>
                <th>Valor</th>
                <th class="text-center">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($result as $row) {
                ?>
                <tr class="gradeX">
                        
                    <td ><?=strlen($row["DESCRICAO"]) > 59 ? substr($row["DESCRICAO"], 0, 50) . "<br>".substr($row["DESCRICAO"], 50, 80) : $row["DESCRICAO"] ?></td>
                    <td ><?=$row["$COD"]?></td>             
                 
                    <td><input type="text" id="a_<?=$row["CODIGO_FORNECEDOR"]?>" name="a_<?=$row["CODIGO_FORNECEDOR"]?>" style="text-align: right; width:120px" value="<?=number_format($row["Tab_Preco_5"],2,',','.')?>" placeholder="0,00" onKeyPress="return(moeda(this,'.',',',event))"> </td>
                    <td class="actions text-center">
                        <a href="javascript:void(0);" class="on-default edit-row" title="Adicionar Direto" onclick="_incluirDadosProdDireto(<?=$row["CODIGO_FORNECEDOR"];?>,'#a_<?=$row["CODIGO_FORNECEDOR"]?>')" style="margin-right: 10px;"><i class="fa   fa-plus-square fa-2x"></i></a>
                        <a href="javascript:void(0);" class="on-default edit-row" title="Adicionar Personalizado" onclick="_buscaDadosProd(<?=$row["CODIGO_FORNECEDOR"];?>)"><i class="fa  fa-plus-square-o fa-2x text-inverse "></i></a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
   
            ?>
            </tbody>
        </table>
        <?php
    
}

if ($_acao == 7) {
 

   
        $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,UNIDADE_MEDIDA,DESCRICAO,Tab_Preco_5,Cod_Class_Fiscal,SIT_TRIBUTARIA,IND_SUBTTRIBUTARIA,CFOPD,CFOPF, CODIGO_FABRICANTE 
        FROM ". $_SESSION['BASE'] .".itemestoque WHERE CODIGO_FORNECEDOR = '". $_parametros["id-produto"] ."' ");
        $result = $consulta->fetch();
        
        $sql = "SELECT nfed_cfopid,nfed_cfop,nfed_operacao  FROM ". $_SESSION['BASE'] .". NFE_DADOS WHERE nfed_id = '". $_parametros["id-nota"] ."'";
        $consultaNF = $pdo->query("$sql");
        $resultNF = $consultaNF->fetch();
        $cfopID = $resultNF["nfed_cfopid"];
        $cfop = $resultNF["nfed_cfop"];
        $NAT_OPERACAO  = $resultNF["nfed_operacao"];
     //  $xx =  $cfop;

        $sittributario = $result["SIT_TRIBUTARIA"] ;       
        $NCM =  $result["Cod_Class_Fiscal"]  ?? '';
      

                                                    if ($NCM === '00000000' || strlen($NCM) !== 8) {
                                                        $stmt = $pdo->prepare("
                                                            SELECT tabncm_ncm 
                                                            FROM bd_prisma.tab_ncmproduto  
                                                            WHERE tabncm_codfabricante = ? 
                                                            LIMIT 1
                                                        ");
                                                       
                                                        $stmt->execute([$result["CODIGO_FABRICANTE"] ?? null]);

                                                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $NCM =  $row['tabncm_ncm'];
                                                         
                                                            //update
                                                              $stmt = $pdo->prepare(" UPDATE ". $_SESSION['BASE'] .".itemestoque SET Cod_Class_Fiscal = ?  WHERE CODIGO_FORNECEDOR = ?    LIMIT 1");
                                                               $stmt->execute([$NCM, $_parametros["id-produto"] ?? null]); 
                                                        }
                                                    }
        $CFOPD = $result["CFOPD"] ;
        $CFOPF = $result["CFOPF"] ;
    
        if(trim($CFOPD) != "" and $NAT_OPERACAO == 1 and $cfop == '5102' or  trim($CFOPD) != "" and $NAT_OPERACAO == 1 and $cfop == '5405') {
            $cfop = $CFOPD ;
           }
           if(trim($CFOPF) != "" and $NAT_OPERACAO == 2 and $cfop == '6102' or trim($CFOPF) != "" and $NAT_OPERACAO == 2 and $cfop == '6405') {
            $cfop = $CFOPF ;
           }
        
       

/*
        $sql = "SELECT NAT_FINALIDADE,NAT_OPERACAO FROM ".$_SESSION['BASE'].".cfop where ID = '" . $_parametros["nf-operacao"] . "' limit 1";        
        $statement = $pdo->query("$sql");
        $retorno = $statement->fetchAll();  
        foreach ($retorno as $row) {                    
            $_ret = $row['NAT_FINALIDADE'];
            $_ret =  $_ret.";".$row['NAT_OPERACAO'];
        }
        */
      
        $consultaCfop = $pdo->query("SELECT NAT_FINALIDADE,NAT_OPERACAO,NAT_CST,NAT_modBC,NAT_pICMS,NAT_modBCST,NAT_pICMSST,NAT_PIS,NAT_COFINS FROM ".$_SESSION['BASE'].".cfop where ID = '" . $cfopID. "' limit 1 ");
        $resultCfop = $consultaCfop->fetch();
     
        $_ret = $resultCfop['NAT_FINALIDADE'];
        $_ret =  $_ret.";".$resultCfop['NAT_OPERACAO'];
        $_xCst = $resultCfop["NAT_CST"];
        $_xmodBC = $resultCfop["NAT_modBC"];
        $_xpICMS = $resultCfop["NAT_pICMS"];
        $_xvICMS = 0;
        $_xNmodBCST = $resultCfop["NAT_modBCST"];
        $_xpICMSST = $resultCfop["NAT_pICMSST"];
        if($_xpICMS > 0){
            $_xvICMS  = ($_xpICMS * $result["Tab_Preco_5"]/100);
            $_totalbase = $result["Tab_Preco_5"];
        }
        $_xPIS = $resultCfop["NAT_PIS"];
        $_xCOFINS = $resultCfop["NAT_COFINS"];
        
//$xx = $cfop ;
       // if($_xCst == 0  and $_xCst != "00") {
            if($cfop == '5102' or  $cfop == '5405'){
                $_xCst =   $sittributario; 
            }
           
     //  }
      
   
        if($_xPIS == 0 ) {
            $_xPIS =  "08";
        }
        if($_xCOFINS == 0 ) {
            $_xCOFINS =  "08";
        }

      
        $sqlCEST = "SELECT tabnct_cest FROM bd_prisma.tab_ncmcest  WHERE tabnc_ncm = '". $result["Cod_Class_Fiscal"]."' AND tabnct_cest <> '' LIMIT 1";
        $consultaCEST = $pdo->query("$sqlCEST");
        $resultCEST = $consultaCEST->fetch();
        $CEST = $resultCEST["tabnct_cest"];


    ?>
    <form method="post" action="" name="form-produto" id="form-produto">
        <input type="hidden" id="_campocalc" name="_campocalc" value="">
    <?php 
                                   $SQL = "SELECT * FROM ".$_SESSION['BASE'].".nota_ent_item WHERE NFE_CODIGO = '". $_parametros["id-produto"]."' ORDER BY NFE_CHAVE  DESC LIMIT 1";
                                 
                                    $consultaItem = $pdo->query("$SQL");
                                    $retornoItem = $consultaItem->fetch();
                                   ?>
  <div class="row">
        <div class="col-lg-12">
                        <div class="portlet">
                            <div class="portlet-heading portlet-default">
                                <h3 class="portlet-title text-dark">
                                   Impostos - NF entrada                      
                                </h3>
                                <div class="portlet-widgets">                                  
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default" class="collapsed" aria-expanded="false"><i class="ion-minus-round"></i></a>                                  
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="bg-default" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="portlet-body">
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
                                            <td>Conversão: <span ><strong>-</strong></span></td>    
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
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    <div class="row">
        <div class="form-group col-md-7">
            <label for="descricao" class="control-label">Descrição: </label>           
            <input type="text" name="produto-descricao" class="form-control" id="produto-descricao" value="<?=$result["DESCRICAO"]?>">
            <input type="hidden" name="produto-id" id="produto-id" value="<?=$result["CODIGO_FORNECEDOR"]?>">
            <input type="hidden" name="produto-idBASE" id="produto-idBASE" value="<?=$_parametros["id-nota"]?>">
            <input type="hidden" name="produto-idEMPRESA" id="produto-idEMPRESA" value="<?=$_parametros["id-empresa"]?>">
        </div>
        <div class="form-group col-md-1">
            <label for="produto-quantidade" class="control-label">Quant:</label>
            <input type="number" name="produto-quantidade" id="produto-quantidade" class="form-control" value="1">
            <input type="hidden" name="produto-fornecedor" id="produto-fornecedor" value="<?=$_parametros["id-fornecedor"]?>">
        </div>
       
        <div class="form-group col-md-4">
            <label for="produto-valor" class="control-label">Vlr Produto:</label>
            <div class="input-group">
                <input type="text" name="produto-valor" id="produto-valor" class="form-control" value="<?=number_format($result["Tab_Preco_5"],2,',','.')?>">
               
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-1">
            <label  class="control-label">ICMS ST:</label>    
            <?php 
             if($result['IND_SUBTTRIBUTARIA'] == '0') { $sitribut = "Não";}else{$sitribut = "Sim";} 
            ?>
            <input type="text" name="produto-sittributaria" id="produto-sittributaria" class="form-control" value="<?=$sitribut;?>">          
        </div>
        <div class="form-group col-md-2">
            <label  class="control-label">CEST:</label>           
            <input type="text" name="produto-cest" id="produto-cest" class="form-control" value="<?=$CEST?>">          
        </div>
         <div class="form-group col-md-1">
            <label  class="control-label">Unidade:</label>           
            <input type="text" name="produto-unid" id="produto-unid" class="form-control" value="<?=$result["UNIDADE_MEDIDA"]?>">
          
        </div>
      
        <div class="form-group col-md-2">
            <label  class="control-label">NCM:</label>           
            <input type="text" name="produto-ncm" id="produto-ncm" class="form-control" value="<?=$NCM?>">
          
        </div>
        <div class="form-group col-md-2">
            <label for="produto-quantidade" class="control-label">CST/CSON</label>
            <input type="number" name="produto-sittributaria" id="produto-sittributaria" class="form-control" value="<?=$_xCst;?>"> 
            
        </div>
        <div class="form-group col-md-3">
            <label for="produto-cfop" class="control-label">CFOP:</label>
            <input type="number" name="produto-cfop" id="produto-cfop" class="form-control" value="<?=$cfop;?>">
            
        </div>
       
    </div>
    <div class="row">
        <div class="form-group col-md-1">
            <label for="produto-valor" class="control-label">Aliq.IPI:</label>
            <div class="input-group">
                <input type="text" name="produto-aliqIPI" id="produto-aliqIPI" class="form-control" value="0">               
            </div>
        </div>
        <div class="form-group col-md-1">
            <label for="produto-valor" class="control-label">Vlr.IPI:</label>
            <div class="input-group">
                <input type="text" name="produto-vlrIPI" id="produto-vlrIPI" class="form-control" value="0">               
            </div>
        </div>
        <div class="form-group col-md-2">
            <label for="produto-valor" class="control-label">Nº Pedido:</label>
            <div class="input-group">
                <input type="text" name="produto-npedido" id="produto-npedido" class="form-control" value="0">               
            </div>
        </div>
         <div class="form-group col-md-2">
            <label for="produto-ipi" class="control-label">Vlr Outros:</label>
            <input type="text" name="produto-vlroutros" id="produto-vlroutros" class="form-control">            
        </div>
        <div class="form-group col-md-2">
            <label for="produto-valor" class="control-label">Vlr Desconto:</label>
            <div class="input-group">
                <input type="text" name="produto-vlrdesconto" id="produto-vlrdesconto" class="form-control" value="0">               
            </div>
        </div>
        <div class="form-group col-md-3">
            <label for="produto-frete" class="control-label">Vlr Frete:</label>
            <div class="input-group">
                <input type="text" name="produto-frete" id="produto-frete" class="form-control" value="0">               
            </div>
        </div>
       
    </div>
    <!-- novas campos -->
    <div class="row">
        <div class="form-group col-md-2">
            <label  class="control-label">Aliq.ICMS:</label>           
            <input type="text" name="produto-aliqIcms" id="produto-aliqIcms" class="form-control" onblur="somaBase('produto-aliqIcms')" value="<?=$_xpICMS;?>">          
        </div>
        <div class="form-group col-md-2">
            <label  class="control-label">Vlr Base ICMS:</label>
            <input type="text" name="produto-baseIcms" id="produto-baseIcms" class="form-control" onblur="somaBase('produto-aliqIcms')" value="<?=number_format($_totalbase,2,',','.')?>">
        
        </div>
        <div class="form-group col-md-2">
            <label class="control-label">Vlr ICMS:</label>
           
            <input type="text" name="produto-vlrIcms" id="produto-vlrIcms" class="form-control" value="<?=number_format($_xvICMS,2,',','.')?>">
            
        </div>
        <div class="form-group col-md-1">
            <label class="control-label">Origem</label>
            <input type="text" name="produto-origemIcms" id="produto-origemIcms" class="form-control" value="0">
            
        </div>
        <div class="form-group col-md-1">
            <label class="control-label">Mod.Base</label>
            <input type="text" name="produto-modBC" id="produto-modBC" class="form-control" value="<?=$_xmodBC;?>">
            
        </div>
       
        <div class="form-group col-md-1">
            <label class="control-label">Pis</label>
            <input type="text" name="produto-pisIcms" id="produto-pisIcms" class="form-control" value="<?=$_xPIS;?>">
            
        </div>
        <div class="form-group col-md-2">
            <label class="control-label">Cofins</label>
            <input type="text" name="produto-cofinsIcms" id="produto-cofinsIcms" class="form-control" value="<?=$_xCOFINS;?>">
            
        </div>
       
    </div>
    <div class="row">
        <div class="col-lg-12">
                        <div class="portlet">
                            <div class="portlet-heading portlet-default">
                                <h3 class="portlet-title text-dark">
                                 ICMS substituição / ICMS st retido
                                </h3>
                                <div class="portlet-widgets">                                  
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#bg-default2" class="collapsed" aria-expanded="false"><i class="ion-minus-round"></i></a>                                  
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="bg-default2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="portlet-body">
                                     <div class="row">
                                        <div class="form-group col-md-2">
                                            <label  class="control-label">Aliq.ICMS(ST):</label>           
                                            <input type="text" name="produto-aliqIcmsST" id="produto-aliqIcmsST" class="form-control"  onblur="somaBase('produto-aliqIcmsST')" value="<?=$_xpICMSST;?>">          
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label  class="control-label">Vlr Base ICMS(ST):</label>
                                            <input type="text" name="produto-baseIcmsST" id="produto-baseIcmsST" class="form-control"  value="<?=number_format($_totalbaseST,2,',','.')?>">                                        
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label">Vlr ICMS(ST):</label>                                        
                                            <input type="text" name="produto-vlrIcmsST" id="produto-vlrIcmsST" class="form-control" value="<?=number_format($_xvICMSST,2,',','.')?>">                                            
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label">MVA(ST)</label>
                                            <input type="text" name="produto-mva" id="produto-mva" class="form-control" value="<?=$_xmva;?>">                                            
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label">Aliq.FCP(ST)</label>
                                            <input type="text" name="produto-fcpST" id="produto-fcpST" class="form-control" onblur="somaBase('produto-fcpST')" value="<?=$_xfcpST;?>">                                            
                                        </div>
                                    
                                        <div class="form-group col-md-2">
                                            <label class="control-label">Vlr FCP(ST)</label>
                                            <input type="text" name="produto-vlrfcpST" id="produto-vlrfcpST" class="form-control" value="<?=$_xvfcpST;?>">            
                                        </div>    
                                    
                                    </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label  class="control-label">Aliq.ICMS(ST) <br> Retido:</label>           
                                                <input type="text" name="produto-aliqIcmsSTret" id="produto-aliqIcmsSTret" class="form-control"  onblur="somaBase('produto-aliqIcmsSTret')" value="<?=$_xpICMSSTret;?>">          
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label  class="control-label">Vlr Base ICMS(ST) <br> Retido:</label>
                                                <input type="text" name="produto-baseIcmsSTret" id="produto-baseIcmsSTret" class="form-control" value="<?=number_format($_totalbaseSTret,2,',','.')?>">
                                            
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Vlr ICMS(ST) <br> Retido:</label>
                                            
                                                <input type="text" name="produto-vlrIcmsSTret" id="produto-vlrIcmsSTret"  onblur="somaBase('produto-aliqIcmsST')" class="form-control" value="<?=number_format($_xvICMSSTret,2,',','.')?>">
                                                
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Vlr ICMS <br>Sustituto:</label>
                                                <input type="text" name="produto-vlrIcmsSubstituto" id="produto-vlrIcmsSubstituto" class="form-control" value="<?=number_format($_xvICMSsubstituto,',','.')?>">
                                                
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Aliq.FCP(ST)<br>Retido:</label>
                                                <input type="text" name="produto-modBCSTret" id="produto-modBCSTret" class="form-control" onblur="somaBase('produto-modBCSTret')"  value="<?=$_xmodBCSTret;?>">            
                                            </div>       
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Vlr FCP(FCP) <br>Retido</label>
                                                <input type="text" name="produto-vlrfcpSTret" id="produto-vlrfcpSTret" class="form-control" value="<?=$_xvlfcpSTret;?>">            
                                            </div> 
                                        
                                        </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>
  
    <div class="row">
        <div class="form-group col-md-11">
            <label  class="control-label">Informação Adicional Produto</label>           
            <input type="text" name="produto-infAdProd" id="produto-infAdProd" class="form-control" value="<?=$infAdProd;?>">          
        </div>
  </div>
  
        <div class="input-group-btn">
                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="_adicionaProduto()">Incluir<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
                </div>
    </form>
    <?php
}

if ($_acao == 8) {
    
 //alteração
    $sq = "SELECT * FROM ". $_SESSION['BASE'] .".NFE_ITENS WHERE id_nfeitens = '". $_parametros["id-produto"] ."' ";
    $consulta = $pdo->query("$sq");
    $result = $consulta->fetch();

    ?>
    <form method="post" action="" name="form-produto" id="form-produto">
    <input type="hidden" id="_campocalc" name="_campocalc" value="">
        <?php 
                                   $SQL = "SELECT * FROM ".$_SESSION['BASE'].".nota_ent_item WHERE NFE_CODIGO = '". $result["codigoproduto_nfeitens"]."' ORDER BY NFE_CHAVE  DESC LIMIT 1";                                 
                                    $consultaItem = $pdo->query("$SQL");
                                    $retornoItem = $consultaItem->fetch();
                                   ?>
  <div class="row">
        <div class="col-lg-10">
                        <div class="portlet">
                            <div class="portlet-heading portlet-default">
                                <h3 class="portlet-title text-dark">
                                Impostos - NF entrada 
                                   
                                </h3>
                                <div class="portlet-widgets">                                  
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#bg-default" class="collapsed" aria-expanded="false"><i class="ion-minus-round"></i></a>                                  
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="bg-default" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="portlet-body">
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
                                            <td>Conversão: <span ><strong>-</strong></span></td>    
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
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    <div class="row">
        <div class="form-group col-md-5">
            <label for="descricao" class="control-label">Descrição:</label>           
            <input type="text" name="produto-descricao" class="form-control" id="produto-descricao" value="<?=$result["descricao_nfeitens"]?>">
            <input type="hidden" name="produto-id" id="produto-id" value="<?=$result["id_nfeitens"]?>">         
     
        </div>
        <div class="form-group col-md-2">
            <label for="produto-quantidade" class="control-label">Quant:</label>
            <input type="number" name="produto-quantidade" id="produto-quantidade" class="form-control" value="<?=$result["quantidade"];?>">            
        </div>       
        <div class="form-group col-md-5">
            <label for="produto-valor" class="control-label">Vlr Produto:</label>
            <div class="input-group">
                <input type="text" name="produto-valor" id="produto-valor" class="form-control" value="<?=number_format($result["vlrunitario_nfeitens"],2,',','.')?>">
               
            </div>
        </div>
    </div>
    <div class="row">
    <div class="form-group col-md-1">
            <label  class="control-label">ICMS ST:</label>    
            <?php 
             if($result['IND_SUBTTRIBUTARIA'] == '0') { $sitribut = "Não";}else{$sitribut = "Sim";} 
            ?>
            <input type="text" name="produto-sittributaria" id="produto-sittributaria" class="form-control" value="<?=$sitribut;?>">          
        </div>
        <div class="form-group col-md-2">
            <label  class="control-label">CEST:</label>           
            <input type="text" name="produto-cest" id="produto-cest" class="form-control" value="<?=$result["item_cest"]?>">          
        </div>
         <div class="form-group col-md-1">
            <label  class="control-label">Unidade: </label>           
            <input type="text" name="produto-unid" id="produto-unid" class="form-control" value="<?=$result["unidade_nfeitens"]?>">          
        </div>
   
        <div class="form-group col-md-2">
            <label  class="control-label">NCM:</label>           
            <input type="text" name="produto-ncm" id="produto-ncm" class="form-control" value="<?=$result["item_nmc"]?>">
          
        </div>
        <div class="form-group col-md-2">
            <label for="produto-quantidade" class="control-label">CST/CSON:</label>
            <input type="number" name="produto-sittributaria" id="produto-sittributaria" class="form-control" value="<?=$result["situacaotributario_nfeitens"];?>">
            
        </div>
        <div class="form-group col-md-2">
            <label for="produto-cfop" class="control-label">CFOP:</label>
            <input type="number" name="produto-cfop" id="produto-cfop" class="form-control" value="<?=$result["cfop_nfeitens"];?>">
            
        </div>
       
    </div>
    <div class="row">
         <div class="form-group col-md-2">
            <label for="produto-valor" class="control-label">Aliq.IPI Dev:</label>
            <div class="input-group">
                <input type="text" name="produto-aliqIPI" id="produto-aliqIPIdev" class="form-control" value="<?=number_format($result["nfe_itensPimpostoDevol"],2,',','.')?>">               
            </div>
        </div>
        <div class="form-group col-md-2">
            <label for="produto-valor" class="control-label">Vlr.IPI Dev:</label>
            <div class="input-group">
                <input type="text" name="produto-vlrIPI" id="produto-vlrIPIdev" class="form-control" value="<?=number_format($result["nfe_itensvlrimpostoDevol"],2,',','.')?>">               
            </div>
        </div>
        <?php /*
         <div class="form-group col-md-1">
            <label for="produto-valor" class="control-label">Nº Pedido:</label>
            <div class="input-group">
                <input type="text" name="produto-npedido" id="produto-npedido" class="form-control" value="<?=$result["nfe_itensNumPedido"];?>">               
            </div>
        </div>
        */?>
         <div class="form-group col-md-2">
            <label for="produto-ipi" class="control-label">Vlr Outras Despesas:</label>
            <input type="text" name="produto-vlroutros" id="produto-vlroutros" class="form-control" value="<?=number_format($result["nfe_itensvlrOutros"],2,',','.')?>">            
        </div>
        <div class="form-group col-md-2">
            <label for="produto-valor" class="control-label">Vlr Desconto:</label>
            <div class="input-group">
                <input type="text" name="produto-vlrdesconto" id="produto-vlrdesconto" class="form-control" value="<?=number_format($result["nfe_itensvlrDesconto"],2,',','.')?>">               
            </div>
        </div>
        <div class="form-group col-md-2">
            <label for="produto-frete" class="control-label">Vlr Frete:</label>
            <div class="input-group">
                <input type="text" name="produto-frete" id="produto-frete" class="form-control" value="<?=number_format($result["nfe_itensfrete"],2,',','.')?>">               
            </div>
        </div>
       
    </div>
    <div class="row">
    <div class="form-group col-md-2">
            <label  class="control-label">Aliq.ICMS.:</label>           
            <input type="text" name="produto-aliqIcms" id="produto-aliqIcms" class="form-control"  onblur="somaBase('produto-aliqIcms')" value="<?=number_format($result["pICMS_nfeitens"],2,',','.');?>">          
        </div>
        <div class="form-group col-md-2">
            <label  class="control-label">Vlr Base ICMS:</label>
            <input type="text" name="produto-baseIcms" id="produto-baseIcms" class="form-control" onblur="somaBase('produto-aliqIcms')" value="<?=number_format($result["vBC_nfeitens"],2,',','.')?>">
            
        </div>
        <div class="form-group col-md-2">
            <label class="control-label">Vlr ICMS:</label>
            <input type="text" name="produto-vlrIcms" id="produto-vlrIcms" class="form-control" value="<?=number_format($result["vICMS_nfeitens"],2,',','.')?>">
            
        </div>
        <div class="form-group col-md-1">
            <label class="control-label">Origem</label>
            <input type="text" name="produto-origemIcms" id="produto-origemIcms" class="form-control" value="<?=$result["origemimposto_nfeitens"];?>">
            
        </div>
        <div class="form-group col-md-1">
            <label class="control-label">Mod.Base</label>
            <input type="text" name="produto-modBC" id="produto-modBC" class="form-control" value="<?=$result["modBC_nfeitens"];?>">
            
        </div>
        <div class="form-group col-md-1">
            <label class="control-label">Pis</label>
            <input type="text" name="produto-pisIcms" id="produto-pisIcms" class="form-control" value="<?=$result["pisCST_nfeitens"];?>">
            
        </div>
        <div class="form-group col-md-1">
            <label class="control-label">Cofins</label>
            <input type="text" name="produto-cofinsIcms" id="produto-cofinsIcms" class="form-control" value="<?=$result["cofins_nfeitens"];?>">            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
                        <div class="portlet">
                            <div class="portlet-heading portlet-default">
                                <h3 class="portlet-title text-dark">
                                 ICMS substituição / ICMS st retido
                                </h3>
                                <div class="portlet-widgets">                                  
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#bg-default2" class="collapsed" aria-expanded="false"><i class="ion-minus-round"></i></a>                                  
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="bg-default2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="portlet-body">
                                     <div class="row">
                                        <div class="form-group col-md-2">
                                            <label  class="control-label">Aliq.ICMS(ST):</label>           
                                            <input type="text" name="produto-aliqIcmsST" id="produto-aliqIcmsST" class="form-control"  onblur="somaBase('produto-aliqIcmsST')" value="<?=number_format($result["pICMSST_nfeitens"],2,',','.')?>">          
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label  class="control-label">Vlr Base ICMS(ST):</label>
                                            <input type="text" name="produto-baseIcmsST" id="produto-baseIcmsST" class="form-control" value="<?=number_format($result["vBCST_nfeitens"],2,',','.')?>">                                        
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label">Vlr ICMS(ST):</label>                                        
                                            <input type="text" name="produto-vlrIcmsST" id="produto-vlrIcmsST" class="form-control" value="<?=number_format($result["vICMSST_nfeitens"],2,',','.')?>">                                            
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label">MVA(ST)</label>
                                            <input type="text" name="produto-mva" id="produto-mva" class="form-control" value="<?=number_format($result["mva_nfeitens"],2,',','.')?>">                                            
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label">Aliq.FCP(ST)</label>
                                            <input type="text" name="produto-fcpST" id="produto-fcpST" class="form-control" onblur="somaBase('produto-fcpST')" value="<?=number_format($result["fcpST_nfeitens"],2,',','.')?>">                                            
                                        </div>
                                    
                                        <div class="form-group col-md-2">
                                            <label class="control-label">Vlr FCP(ST)</label>
                                            <input type="text" name="produto-vlrfcpST" id="produto-vlrfcpST" class="form-control"  value="<?=number_format($result["vlrfcpST_nfeitens"],2,',','.')?>">            
                                        </div>    
                                    
                                    </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label  class="control-label">Aliq.ICMS(ST) <br> Retido:</label>           
                                                <input type="text" name="produto-aliqIcmsSTret" id="produto-aliqIcmsSTret" class="form-control"  onblur="somaBase('produto-aliqIcmsSTret')" value="<?=number_format($result["aliqIcmsSTret_nfeitens"],2,',','.')?>">          
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label  class="control-label">Vlr Base ICMS(ST) <br> Retido:</label>
                                                <input type="text" name="produto-baseIcmsSTret" id="produto-baseIcmsSTret" class="form-control" onblur="somaBase('produto-aliqIcmsSTret')" value="<?=number_format($result["baseIcmsSTret_nfeitens"],2,',','.')?>">
                                            
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Vlr ICMS(ST) <br> Retido:</label>
                                            
                                                <input type="text" name="produto-vlrIcmsSTret" id="produto-vlrIcmsSTret" onblur="somaBase('produto-aliqIcmsSTret')" class="form-control" value="<?=number_format($result["vlrIcmsSTret_nfeitens"],2,',','.')?>">
                                                
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Vlr ICMS <br>Sustituto:</label>
                                                <input type="text" name="produto-vlrIcmsSubstituto" id="produto-vlrIcmsSubstituto" class="form-control" value="<?=number_format($result["vlrIcmsSubstituto_nfeitens"],2,',','.')?>">
                                                
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Aliq.FCP(ST)<br>Retido:</label>
                                                <input type="text" name="produto-modBCSTret" id="produto-modBCSTret" class="form-control"  onblur="somaBase('produto-modBCSTret')" value="<?=number_format($result["modBCSTret_nfeitens"],2,',','.')?>">            
                                            </div>       
                                            <div class="form-group col-md-2">
                                                <label class="control-label">Vlr FCP(FCP) <br>Retido</label>
                                                <input type="text" name="produto-vlrfcpSTret" id="produto-vlrfcpSTret" class="form-control" value="<?=number_format($result["vlrfcpSTret_nfeitens"],2,',','.')?>">            
                                            </div> 
                                        
                                        </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-10">
            <label  class="control-label">Informação Adicional Produto</label>           
            <input type="text" name="produto-infAdProd" id="produto-infAdProd" class="form-control" value="<?=$result["infAdProd"];?>">          
        </div>
  </div>
        <div class="input-group-btn">
                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="_updateProduto()"> Salvar </button>
                </div>
    </form>
    
    <?php
}


//adicionar produtos
if ($_acao == 99) {    
 if (empty($_parametros["vlrmanualprod"]) || floatval($_parametros["vlrmanualprod"]) < 0.0) {
        ?>      
                        <h2>Informe o valor do produto!</h2>    
        <?php
    }   
    else {
        $valor = str_replace(".", "",  $_parametros["vlrmanualprod"]);
        $valor = str_replace(",", ".",  $valor);
        $valor = str_replace("'", "",  $valor );
        
        $frete = 0;
        $_desconto = 0;
        $_outros =  0;
        $vlrIcms = 0;
        $vlrIPI = 0;
        $aliqIPI = 0;
        $aliqIPI = 0;
        $baseIcms = 0;
        $base = 0;
        $totalProdutos = floatval($valor);
       
    }


    $consulta = $pdo->query("SELECT DESCRICAO,Cod_Class_Fiscal,UNIDADE_MEDIDA,SIT_TRIBUTARIA,CFOPD,CFOPF,CODIGO_FABRICANTE FROM ". $_SESSION['BASE'] .".itemestoque 
    WHERE CODIGO_FORNECEDOR = '". $_parametros["idmanualprod"] ."' LIMIT 1" );           
    $result = $consulta->fetchAll();   
        foreach ($result as $row) {          
          $descricao = $row["DESCRICAO"];
          $ncm =   preg_replace('/[^0-9]/', '', $row["Cod_Class_Fiscal"]);
          $unidade = $row["UNIDADE_MEDIDA"] ;
          $sittributario = $row["SIT_TRIBUTARIA"] ;
          $CFOPD = $row["CFOPD"] ;
          $CFOPF = $row["CFOPF"] ;
        }

        $NCM =  $ncm ?? '';

                                                    if ($NCM === '00000000' || strlen($NCM) !== 8) {
                                                        $stmt = $pdo->prepare("
                                                            SELECT tabncm_ncm 
                                                            FROM bd_prisma.tab_ncmproduto  
                                                            WHERE tabncm_codfabricante = ? 
                                                            LIMIT 1
                                                        ");
                                                        $stmt->execute([$row["CODIGO_FABRICANTE"] ?? null]);

                                                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $NCM = $row['tabncm_ncm'];
                                                            $ncm =  $NCM;
                                                            //update
                                                              $stmt = $pdo->prepare(" UPDATE ". $_SESSION['BASE'] .".itemestoque SET Cod_Class_Fiscal = ?  WHERE CODIGO_FORNECEDOR = ?    LIMIT 1");
                                                               $stmt->execute([$NCM, $_parametros["idmanualprod"] ?? null]); 
                                                        }
                                                    }

        $cfop = 0;
        $pis = '08';
        $confis = '08';

       $sql = "SELECT NAT_CODIGO,NAT_PIS,NAT_CST,NAT_pICMS FROM ".$_SESSION['BASE'].".cfop where ID = '" . $_parametros["nf-operacao"] . "' limit 1";        
       $statement = $pdo->query("$sql");
       $retorno = $statement->fetchAll();  
       foreach ($retorno as $rowcp) {                
           $cfop = $rowcp['NAT_CODIGO'];    
           $cstcfop = $rowcp['NAT_CST'];   
           if($rowcp['NAT_PIS'] != "") {
            $pis = $rowcp['NAT_PIS'];   
           }
           if($rowcp['NAT_COFINS'] != "") {
            $confis = $rowcp['NAT_COFINS'];   
           }

           $_xpICMS = $rowcp["NAT_pICMS"];
                
                $_xvICMS = 0;
                $_xNmodBCST = $resultCfop["NAT_modBCST"];
                $_xpICMSST = $resultCfop["NAT_pICMSST"];
                if($_xpICMS > 0){
                    $vlrIcms  = ($_xpICMS * $row["vlrunitario_nfeitens"]/100);
                    $baseIcms = $totalProdutos;
                    $base = $_xpICMS;
                }
           
       }
       /*
       if(trim($CFOPD) != "" and $_parametros["nf-destinooperacao"] == 1) {
        $cfop = $CFOPD ;
       }
       if(trim($CFOPF) != "" and $_parametros["nf-destinooperacao"] == 2) {
        $cfop = $CFOPF ;
       }
       */
      if(trim($CFOPD) != "" and $_parametros["nf-destinooperacao"] == 1 and $cfop == '5102' or  trim($CFOPD) != "" and $_parametros["nf-destinooperacao"] == 1 and $cfop == '5405') {
        $cfop = $CFOPD ;
       }
       if(trim($CFOPF) != "" and $_parametros["nf-destinooperacao"] == 2 and $cfop == '6102' or trim($CFOPF) != "" and $_parametros["nf-destinooperacao"] == 2 and $cfop == '6405') {
        $cfop = $CFOPF ;
       }

       if( $unidade == "" ) {
        $unidade = "UN";
       }
       
       $qtde = 1;
       if($sittributario == "") {
        $sittributario = 102;
       }

       if($cfop == '5102' or  $cfop == '5405'){
   
       }else{
        $sittributario =   $cstcfop;
       }

       if(trim($ncm) != ""){
        $sqlCEST = "SELECT tabnct_cest FROM bd_prisma.tab_ncmcest  WHERE tabnc_ncm = '".$ncm."' AND tabnct_cest <> '' LIMIT 1";
        $consultaCEST = $pdo->query("$sqlCEST");
        $resultCEST = $consultaCEST->fetch();
        $CEST = $resultCEST["tabnct_cest"];
   
       }
     
       $aliquota = 0;
       $empresa = "1";
       $pedido = 0;
       $icms = 0;
      
       $modbc = 0;   $modbcst = 4;
       $addinfo = 0;
            
        try {
            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_ITENS (id_nfedados,codigoproduto_nfeitens,descricao_nfeitens,cfop_nfeitens,unidade_nfeitens,quantidade,vlrunitario_nfeitens,vlrtotal_nfeitens,	vlrunitarioTributario_nfeitens,situacaotributario_nfeitens,vICMS_nfeitens,vBC_nfeitens,pICMS_nfeitens,item_notaempresa,	item_nmc,nfe_itensvlrOutros,nfe_itensvlrDesconto,nfe_itensNumPedido,nfe_itensfrete,origemimposto_nfeitens,pisCST_nfeitens,cofins_nfeitens,modBC_nfeitens,nfe_itensPimpostoDevol,
            nfe_itensvlrimpostoDevol,mva_nfeitens,fcpST_nfeitens,vlrfcpST_nfeitens,aliqIcmsSTret_nfeitens,baseIcmsSTret_nfeitens,vlrIcmsSTret_nfeitens,vlrIcmsSubstituto_nfeitens,
            modBCSTret_nfeitens,vlrfcpSTret_nfeitens,
            modBCST_nfeitens,vBCST_nfeitens,pICMSST_nfeitens,vICMSST_nfeitens,item_cest)
             VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $statement->bindParam(1, $_parametros["nf-id"]);         
            $statement->bindParam(2, $_parametros["idmanualprod"]);
            $statement->bindParam(3, $descricao);
            $statement->bindParam(4, $cfop);
            $statement->bindParam(5, $unidade);
            $statement->bindParam(6, $qtde);
            $statement->bindParam(7, $valor);
            $statement->bindParam(8, $totalProdutos);
            $statement->bindParam(9, $totalProdutos);
            $statement->bindParam(10, $sittributario);
            $statement->bindParam(11, $vlrIcms);
            $statement->bindParam(12, $baseIcms);
            $statement->bindParam(13, $base);
            $statement->bindParam(14, $empresa);
            $statement->bindParam(15, $ncm);
            $statement->bindParam(16, $_outros);
            $statement->bindParam(17, $_desconto);
            $statement->bindParam(18, $pedido);   
            $statement->bindParam(19, $frete);      
            $statement->bindParam(20, $icms);  
            $statement->bindParam(21, $pis);  
            $statement->bindParam(22,  $confis);  
            $statement->bindParam(23, $modbc);         
            $statement->bindParam(24, $aliqIPI); 
            $statement->bindParam(25, $vlrIPI);
            
            $statement->bindParam(26, $mva);
            $statement->bindParam(27, $fcpST);
            $statement->bindParam(28, $vlrfcpST);
            $statement->bindParam(29, $aliqIcmsSTret);
            $statement->bindParam(30, $baseIcmsSTret);
            $statement->bindParam(31, $vlrIcmsSTret);
            $statement->bindParam(32, $vlrIcmsSubstituto);
            $statement->bindParam(33, $modBCSTret);
            $statement->bindParam(34, $vlrfcpSTret);

            $statement->bindParam(35, $modbcst);
            $statement->bindParam(36, $aliqIcmsST);
            $statement->bindParam(37, $baseIcmsST);
            $statement->bindParam(38, $vlrIcmsST);
            $statement->bindParam(39, $CEST);
            
                   
            $statement->execute(); 
           
            ?>
                          <div style="text-align: right;"><h6> Último Reg.Incluído</h6>
                            <h5> <?= $descricao?> Incluído! </h5>
                            </div>
            
            <?php
        } catch (PDOException $e) {
            ?>
           
                        <h2><?="Erro: " . $e->getMessage()?></h2>
            <?php
        }
   

}

//adicionar produtos
if ($_acao == 9) {

    if (empty($_parametros["produto-quantidade"])  < 0 || $_parametros["produto-quantidade"] == "") { //|| intval($_parametros["produto-quantidade"]
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
    else if ($_parametros["produto-idEMPRESA"] == '' ) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Selecione a Empresa </h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        $valor = str_replace(".", "",  $_parametros["produto-valor"]);
        $valor = str_replace(",", ".",  $valor);
        $valor = str_replace("'", "",  $valor );
        
        $frete = str_replace(".", "",  $_parametros["produto-frete"]);
        $frete = str_replace(",", ".",  $frete);
        $frete = str_replace("'", "",  $frete );

        $_desconto = str_replace(".", "",   $_parametros["produto-vlrdesconto"]);
        $_desconto = str_replace(",", ".",   $_desconto);
        $_desconto = str_replace("'", "",  $_desconto );

        $_outros = str_replace(".", "",  $_parametros["produto-vlroutros"]);
        $_outros = str_replace(",", ".",  $_outros);
        $_outros = str_replace("'", "",  $_outros );

        $vlrIcms = str_replace(".", "",  $_parametros["produto-vlrIcms"]);
        $vlrIcms = str_replace(",", ".",  $vlrIcms);
        $vlrIcms = str_replace("'", "",  $vlrIcms );

        $vlrIPI = str_replace(".", "",  $_parametros["produto-vlrIPI"]);
        $vlrIPI = str_replace(",", ".",  $vlrIPI);
        $vlrIPI = str_replace("'", "",  $vlrIPI );

        $aliqIPI = str_replace(",", ".",  $_parametros["produto-aliqIPI"]);
        $aliqIPI = str_replace("'", "",  $aliqIPI );

        $baseIcms = str_replace(".", "",  $_parametros["produto-baseIcms"]);
        $baseIcms = str_replace(",", ".", $baseIcms);
        $baseIcms = str_replace("'", "",  $baseIcms );

        $totalProdutos = ($_parametros["produto-quantidade"]) * floatval($valor);

        $fcpST = str_replace(".", "",  $_parametros["produto-fcpST"]);
        $fcpST = str_replace(",", ".",  $fcpST);
        $fcpST = str_replace("'", "",  $fcpST );

        $vlrfcpST = str_replace(".", "",  $_parametros["produto-vlrfcpST"]);
        $vlrfcpST = str_replace(",", ".",  $vlrfcpST);
        $vlrfcpST = str_replace("'", "",  $vlrfcpST );

        $aliqIcmsSTret = str_replace(".", "",  $_parametros["produto-aliqIcmsSTret"]);
        $aliqIcmsSTret = str_replace(",", ".", $aliqIcmsSTret);
        $aliqIcmsSTret = str_replace("'", "",  $aliqIcmsSTret );

        $baseIcmsSTret = str_replace(".", "",  $_parametros["produto-baseIcmsSTret"]);
        $baseIcmsSTret = str_replace(",", ".", $baseIcmsSTret);
        $baseIcmsSTret = str_replace("'", "",  $baseIcmsSTret );

        $vlrIcmsSTret = str_replace(".", "",  $_parametros["produto-vlrIcmsSTret"]);
        $vlrIcmsSTret = str_replace(",", ".", $vlrIcmsSTret);
        $vlrIcmsSTret = str_replace("'", "",  $vlrIcmsSTret );

        $vlrIcmsSubstituto = str_replace(".", "",  $_parametros["produto-vlrIcmsSubstituto"]);
        $vlrIcmsSubstituto = str_replace(",", ".", $vlrIcmsSubstituto);
        $vlrIcmsSubstituto = str_replace("'", "",  $vlrIcmsSubstituto );

        $mva = str_replace(".", "",  $_parametros["produto-mva"]);
        $mva = str_replace(",", ".",  $mva);
        $mva = str_replace("'", "",  $mva );

        $modBCSTret = str_replace(".", "",  $_parametros["produto-modBCSTret"]);
        $modBCSTret = str_replace(",", ".",  $modBCSTret);
        $modBCSTret = str_replace("'", "",  $modBCSTret );

        $vlrfcpSTret = str_replace(".", "",  $_parametros["produto-vlrfcpSTret"]);
        $vlrfcpSTret = str_replace(",", ".",  $vlrfcpSTret);
        $vlrfcpSTret = str_replace("'", "",  $vlrfcpSTret );

        $aliqIcmsST = str_replace(".", "",  $_parametros["produto-aliqIcmsST"]);
        $aliqIcmsST = str_replace(",", ".",  $aliqIcmsST);
        $aliqIcmsST = str_replace("'", "",  $aliqIcmsST );

        $baseIcmsST = str_replace(".", "",  $_parametros["produto-baseIcmsST"]);
        $baseIcmsST = str_replace(",", ".",  $baseIcmsST);
        $baseIcmsST = str_replace("'", "",  $baseIcmsST );

        $vlrIcmsST = str_replace(".", "",  $_parametros["produto-vlrIcmsST"]);
        $vlrIcmsST = str_replace(",", ".",  $vlrfcpSTret);
        $vlrIcmsST = str_replace("'", "",  $vlrIcmsST );

        $modbcst = 4;
        

        $ncm =   preg_replace('/[^0-9]/', '', $_parametros["produto-ncm"]);
        $cest =   preg_replace('/[^0-9]/', '', $_parametros["produto-cest"]);
        
            
        try {
            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_ITENS (id_nfedados,codigoproduto_nfeitens,descricao_nfeitens,cfop_nfeitens,unidade_nfeitens,quantidade,vlrunitario_nfeitens,vlrtotal_nfeitens,	vlrunitarioTributario_nfeitens,situacaotributario_nfeitens,vICMS_nfeitens,vBC_nfeitens,pICMS_nfeitens,item_notaempresa,	item_nmc,nfe_itensvlrOutros,nfe_itensvlrDesconto,nfe_itensNumPedido,nfe_itensfrete,origemimposto_nfeitens,pisCST_nfeitens,cofins_nfeitens,modBC_nfeitens,infAdProd,nfe_itensPimpostoDevol,nfe_itensvlrimpostoDevol,
            mva_nfeitens,fcpST_nfeitens,vlrfcpST_nfeitens,aliqIcmsSTret_nfeitens,baseIcmsSTret_nfeitens,vlrIcmsSTret_nfeitens,vlrIcmsSubstituto_nfeitens,
            modBCSTret_nfeitens,vlrfcpSTret_nfeitens,
            modBCST_nfeitens,vBCST_nfeitens,pICMSST_nfeitens,vICMSST_nfeitens,item_cest) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $statement->bindParam(1, $_parametros["produto-idBASE"]);         
            $statement->bindParam(2, $_parametros["produto-id"]);
            $statement->bindParam(3, $_parametros["produto-descricao"]);
            $statement->bindParam(4, $_parametros["produto-cfop"]);
            $statement->bindParam(5, $_parametros["produto-unid"]);
            $statement->bindParam(6, $_parametros["produto-quantidade"]);
            $statement->bindParam(7, $valor);
            $statement->bindParam(8, $totalProdutos);
            $statement->bindParam(9, $totalProdutos);
            $statement->bindParam(10, $_parametros["produto-sittributaria"]);
            $statement->bindParam(11, $vlrIcms);
            $statement->bindParam(12, $baseIcms);
            $statement->bindParam(13, $_parametros["produto-aliqIcms"]);
            $statement->bindParam(14, $_parametros["produto-idEMPRESA"]);
            $statement->bindParam(15, $ncm );
            $statement->bindParam(16, $_outros);
            $statement->bindParam(17, $_desconto);
            $statement->bindParam(18, $_parametros["produto-npedido"]);   
            $statement->bindParam(19, $frete);      
            $statement->bindParam(20, $_parametros["produto-origemIcms"]);  
            $statement->bindParam(21, $_parametros["produto-pisIcms"]);  
            $statement->bindParam(22, $_parametros["produto-cofinsIcms"]);  
            $statement->bindParam(23, $_parametros["produto-modBC"]);  
            $statement->bindParam(24, $_parametros["produto-infAdProd"]); 
            $statement->bindParam(25, $aliqIPI); 
            $statement->bindParam(26, $vlrIPI); 

            $statement->bindParam(27, $mva);
            $statement->bindParam(28, $fcpST);
            $statement->bindParam(29, $vlrfcpST);
            $statement->bindParam(30, $aliqIcmsSTret);
            $statement->bindParam(31, $baseIcmsSTret);
            $statement->bindParam(32, $vlrIcmsSTret);
            $statement->bindParam(33, $vlrIcmsSubstituto);
            $statement->bindParam(34, $modBCSTret);
            $statement->bindParam(35, $vlrfcpSTret);

            $statement->bindParam(36, $modbcst);
            $statement->bindParam(37, $baseIcmsST);
            $statement->bindParam(38, $aliqIcmsST);         
            $statement->bindParam(39, $vlrIcmsST);
            $statement->bindParam(40, $cest);
            
            
           $statement->execute(); 
           
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Incluído! <?=$mva;?> </h2>
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


//update produtos
if ($_acao == 10) {


        try {
            
            $valor = str_replace(".", "",  $_parametros["produto-valor"]);
            $valor = str_replace(",", ".",  $valor);
            $valor = str_replace("'", "",  $valor );
            
            $frete = str_replace(".", "",  $_parametros["produto-frete"]);
            $frete = str_replace(",", ".",  $frete);
            $frete = str_replace("'", "",  $frete );

            $_desconto = str_replace(".", "",   $_parametros["produto-vlrdesconto"]);
            $_desconto = str_replace(",", ".",   $_desconto);
            $_desconto = str_replace("'", "",  $_desconto );

            $_outros = str_replace(".", "",  $_parametros["produto-vlroutros"]);
            $_outros = str_replace(",", ".",  $_outros);
            $_outros = str_replace("'", "",  $_outros );

            $vlrIcms = str_replace(".", "",  $_parametros["produto-vlrIcms"]);
            $vlrIcms = str_replace(",", ".",  $vlrIcms);
            $vlrIcms = str_replace("'", "",  $vlrIcms );

            $vlrIPI = str_replace(".", "",  $_parametros["produto-vlrIPI"]);
            $vlrIPI = str_replace(",", ".",  $vlrIPI);
            $vlrIPI = str_replace("'", "",  $vlrIPI );

            $aliqIPI = str_replace(",", ".",  $_parametros["produto-aliqIPI"]);
            $aliqIPI = str_replace("'", "",  $aliqIPI );

            $baseIcms = str_replace(".", "",  $_parametros["produto-baseIcms"]);
            $baseIcms = str_replace(",", ".",   $baseIcms);
            $baseIcms = str_replace("'", "",  $baseIcms );

            $totalProdutos = ($_parametros["produto-quantidade"]) * floatval($valor);

            $fcpST = str_replace(".", "",  $_parametros["produto-fcpST"]);
            $fcpST = str_replace(",", ".",  $fcpST);
            $fcpST = str_replace("'", "",  $fcpST );

            $vlrfcpST = str_replace(".", "",  $_parametros["produto-vlrfcpST"]);
            $vlrfcpST = str_replace(",", ".",  $vlrfcpST);
            $vlrfcpST = str_replace("'", "",  $vlrfcpST );

            $aliqIcmsSTret = str_replace(".", "",  $_parametros["produto-aliqIcmsSTret"]);
            $aliqIcmsSTret = str_replace(",", ".", $aliqIcmsSTret);
            $aliqIcmsSTret = str_replace("'", "",  $aliqIcmsSTret );

            $baseIcmsSTret = str_replace(".", "",  $_parametros["produto-baseIcmsSTret"]);
            $baseIcmsSTret = str_replace(",", ".", $baseIcmsSTret);
            $baseIcmsSTret = str_replace("'", "",  $baseIcmsSTret );

            $vlrIcmsSTret = str_replace(".", "",  $_parametros["produto-vlrIcmsSTret"]);
            $vlrIcmsSTret = str_replace(",", ".", $vlrIcmsSTret);
            $vlrIcmsSTret = str_replace("'", "",  $vlrIcmsSTret );

            $vlrIcmsSubstituto = str_replace(".", "",  $_parametros["produto-vlrIcmsSubstituto"]);
            $vlrIcmsSubstituto = str_replace(",", ".", $vlrIcmsSubstituto);
            $vlrIcmsSubstituto = str_replace("'", "",  $vlrIcmsSubstituto );

            $mva = str_replace(".", "",  $_parametros["produto-mva"]);
            $mva = str_replace(",", ".",  $mva);
            $mva = str_replace("'", "",  $mva );

            $modBCSTret = str_replace(".", "",  $_parametros["produto-modBCSTret"]);
            $modBCSTret = str_replace(",", ".",  $modBCSTret);
            $modBCSTret = str_replace("'", "",  $modBCSTret );

            $vlrfcpSTret = str_replace(".", "",  $_parametros["produto-vlrfcpSTret"]);
            $vlrfcpSTret = str_replace(",", ".",  $vlrfcpSTret);
            $vlrfcpSTret = str_replace("'", "",  $vlrfcpSTret );

            $aliqIcmsST = str_replace(".", "",  $_parametros["produto-aliqIcmsST"]);
            $aliqIcmsST = str_replace(",", ".",  $aliqIcmsST);
            $aliqIcmsST = str_replace("'", "",  $aliqIcmsST );
    
            $baseIcmsST = str_replace(".", "",  $_parametros["produto-baseIcmsST"]);
            $baseIcmsST = str_replace(",", ".",  $baseIcmsST);
            $baseIcmsST = str_replace("'", "",  $baseIcmsST );
    
            $vlrIcmsST = str_replace(".", "",  $_parametros["produto-vlrIcmsST"]);
            $vlrIcmsST = str_replace(",", ".",  $vlrIcmsST);
            $vlrIcmsST = str_replace("'", "",  $vlrIcmsST );

            $modbcst = 4;

            $ncm =   preg_replace('/[^0-9]/', '', $_parametros["produto-ncm"]);
            $cest =   preg_replace('/[^0-9]/', '', $_parametros["produto-cest"]);

            $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_ITENS  SET descricao_nfeitens = ?,cfop_nfeitens = ?,unidade_nfeitens = ?,quantidade = ?,vlrunitario_nfeitens = ?,vlrtotal_nfeitens = ?,	vlrunitarioTributario_nfeitens = ?,situacaotributario_nfeitens = ?,vICMS_nfeitens = ?,vBC_nfeitens = ?,pICMS_nfeitens = ?,item_notaempresa = ?,	item_nmc = ?,nfe_itensvlrOutros = ?,nfe_itensvlrDesconto = ?,nfe_itensNumPedido = ?, nfe_itensfrete = ?
            , origemimposto_nfeitens = ?, pisCST_nfeitens = ?, cofins_nfeitens = ?, infAdProd = ?
            , nfe_itensPimpostoDevol = ?, nfe_itensvlrimpostoDevol = ?,
            mva_nfeitens = ?,fcpST_nfeitens = ?,vlrfcpST_nfeitens = ?,aliqIcmsSTret_nfeitens = ?,baseIcmsSTret_nfeitens = ?,
            vlrIcmsSTret_nfeitens = ?,vlrIcmsSubstituto_nfeitens = ?,
            modBCSTret_nfeitens = ?,vlrfcpSTret_nfeitens = ?,           
            modBCST_nfeitens = ?,vBCST_nfeitens= ?,pICMSST_nfeitens= ?,vICMSST_nfeitens = ?, item_cest = ?, modBC_nfeitens = ?
            WHERE id_nfeitens = ? ");
                
            $statement->bindParam(1, $_parametros["produto-descricao"]);
            $statement->bindParam(2, $_parametros["produto-cfop"]);
            $statement->bindParam(3, $_parametros["produto-unid"]);
            $statement->bindParam(4, $_parametros["produto-quantidade"]);
            $statement->bindParam(5, $valor);
            $statement->bindParam(6, $totalProdutos);
            $statement->bindParam(7, $totalProdutos);
            $statement->bindParam(8, $_parametros["produto-sittributaria"]);
            $statement->bindParam(9, $vlrIcms);
            $statement->bindParam(10, $baseIcms );
            $statement->bindParam(11, $_parametros["produto-aliqIcms"]);
            $statement->bindParam(12, $_parametros["produto-idEMPRESA"]);
            $statement->bindParam(13,  $ncm );
            $statement->bindParam(14, $_outros);
            $statement->bindParam(15, $_desconto);
            $statement->bindParam(16, $_parametros["produto-npedido"]);  
            $statement->bindParam(17, $frete);    
            $statement->bindParam(18, $_parametros["produto-origemIcms"]);      
            $statement->bindParam(19, $_parametros["produto-pisIcms"]);   
            $statement->bindParam(20, $_parametros["produto-cofinsIcms"]);      
            $statement->bindParam(21, $_parametros["produto-infAdProd"]);   
            $statement->bindParam(22, $aliqIPI);
            $statement->bindParam(23, $vlrIPI);    
            
            $statement->bindParam(24, $mva);
            $statement->bindParam(25, $fcpST);
            $statement->bindParam(26, $vlrfcpST);
            $statement->bindParam(27, $aliqIcmsSTret);
            $statement->bindParam(28, $baseIcmsSTret);
            $statement->bindParam(29, $vlrIcmsSTret);
            $statement->bindParam(30, $vlrIcmsSubstituto);
            $statement->bindParam(31, $modBCSTret);
            $statement->bindParam(32, $vlrfcpSTret);

            $statement->bindParam(33, $modbcst);
            $statement->bindParam(34, $baseIcmsST);
            $statement->bindParam(35, $aliqIcmsST);
            $statement->bindParam(36, $vlrIcmsST);
            $statement->bindParam(37, $cest);
            $statement->bindParam(38, $_parametros["produto-modBC"]);
            
            $statement->bindParam(39, $_parametros["produto-id"]);               
            $statement->execute(); 


            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto Alterado ! </h2>
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


//delete produtos
if ($_acao == 11) {

        // $_parametros["produto-id"]
        ?>
         <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                 <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline"></i>
                </div>
                <h3><span id="textexclui">Deseja realmente excluir ?</span> </h3>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <span id="textexcluibt">
                        <button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_exProdutoAction(<?=$_parametros["id-exclusao"];?>);">Excluir</button>
                    </span>
                </p>
                </div>
                </div>
                </div>
              
        <?php
    

}


if ($_acao == 12) {
        
        $statement = $pdo->prepare("DELETE FROM ".$_SESSION['BASE'].".NFE_ITENS  
        WHERE id_nfeitens = ? ");       
        print_r($_parametros);
        
        $statement->bindParam(1, $_parametros["id-exclusao"]);      
        $statement->execute();       

}


//TRANSPORADOR
if ($_acao == 13) {
 
    try {

        $sql = "SELECT RAZAO_SOCIAL,CNPJ,INSCR_ESTADUAL,TELEFONE,ENDERECO,BAIRRO,CIDADE,UF,CEP FROM " . $_SESSION['BASE'] . ".fabricante 
                WHERE CODIGO_FABRICANTE = '" . $_parametros["id-filtro"] . "'";
   
        $statement = $pdo->query("$sql");
        $retorno = $statement->fetch();       
        ?>
        <div class="row" >
            <div class="form-group col-md-12"  style="padding-left: 10px;">
                <?=$retorno['RAZAO_SOCIAL']." (".$retorno['CNPJ'].")";?>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12"  style="padding-left: 10px;">
                <?=$retorno['ENDERECO']." Bairro:".$retorno['BAIRRO']." Cidade:".$retorno['CIDADE']."-".$retorno['PR'];?>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
       
                    <h2><?="Erro: " . $e->getMessage()?></h2>
               
        <?php
    }

}


//totais
if ($_acao == 14) {
 
    try {

        $sql = "SELECT  nfed_codpgto 
        FROM " . $_SESSION['BASE'] . ".NFE_DADOS 
        WHERE nfed_id = '" . $_parametros["id-nota"] . "'";               
        $statement = $pdo->query("$sql");
        $row = $statement->fetch(); 
        $codpgto = $row["nfed_codpgto"];
        if($codpgto == ""){
            $codpgto = 4;
        }

        $sql = "SELECT sum(vBC_nfeitens)   as tbase,
                        sum(vICMS_nfeitens)   as ticms,                    
                        sum(nfe_itensvlrIPI)   as tipi,
                        sum(nfe_itensBaseIcms)   as tbasecalculoicms,
                        sum(nfe_itensvlrOutros)   as tOutros,
                        sum(vlrtotal_nfeitens)   as tProdutos,
                        sum(nfe_itensvlrDesconto) as tDesconto,
                        sum(nfe_itensfrete) as tFrete
                        FROM " . $_SESSION['BASE'] . ".NFE_ITENS               
                         WHERE id_nfedados = '" . $_parametros["id-nota"] . "'";        
                  //  LEFT JOIN " . $_SESSION['BASE'] . ".NFE_DADOS ON nfed_id = id_nfedados      
        $statement = $pdo->query("$sql");
        $row = $statement->fetch();    
        
        $total = $row["tProdutos"] - $row["tDesconto"] + $row["tFrete"] + $row["tOutros"]+$row["tipi"];
        ?>
<div class="row" >
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Base Calculo</label>
                                                <input id="nf-baseT" name="nf-baseT" type="text" class="form-control" value="<?=number_format($row["tbase"],2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Icms</label>
                                                <input id="nf-icmsT" name="nf-icmsT" type="text" class="form-control" value="<?=number_format($row["ticms"],2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Base Calculo Icms ST</label>
                                                <input id="nf-baseicmsT" name="nf-baseicmsT" type="text" class="form-control" value="<?=number_format(0,2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Icms ST</label>
                                                <input id="nf-totalicmsT" name="nf-totalicmsT" type="text" class="form-control" value="<?=number_format(0,2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total PIS</label>
                                                <input id="nf-pis" name="nf-pis" type="text" class="form-control" value="<?=number_format($row["tpis"],2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Outros Despesas</label>
                                                <input id="nf-outroDespesas" name="nf-outroDespesas" type="text" class="form-control" value="<?=number_format($row['tOutros'],2,',','.');?>" readonly> 
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Produtos</label>
                                                <input id="nf-totalprodutos" name="nf-totalprodutos" type="text" class="form-control" value="<?=number_format($row["tProdutos"],2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Frete</label>
                                                <input id="nf-totalfrete" name="nf-totalfrete" type="text" class="form-control" value="<?=number_format($row["tFrete"],2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total do IPI</label>
                                                <input id="nf-totalprodutos" name="nf-totalprodutos" type="text" class="form-control" value="<?=number_format($row["tipi"],2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total do IPI Devolução</label>
                                                <input id="nf-totalipiDev" name="nf-totalipiDev" type="text" class="form-control" value="<?=number_format(0,2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total do Confis</label>
                                                <input id="nf-totalConfis" name="nf-totalConfis" type="text" class="form-control" value="<?=number_format(0,2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Desconto</label>
                                                <input id="nf-totaldesconto" name="nf-totaldesconto" type="text" class="form-control" value="<?=number_format($row["tDesconto"],2,',','.');?>" readonly> 
                                            </div>
                                        </div> 
                                        <div class="row" >
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Nota</label>
                                                <input id="nf-totalfinal" name="nf-totalfinal" type="text" class="form-control"  value="<?=number_format($total,2,',','.');?>" readonly> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                               
                                                <label class="control-label " >Forma pagamento</label>
                                               
                                               
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".tiporecebimpgto ORDER BY nome");
                                            $retornoEmp = $statement->fetchAll();
                                            ?>
                                            <select name="nf-formapgto" id="nf-formapgto" class="form-control" >
                                                
                                                <?php
                                                foreach ($retornoEmp as $row2) {
                                                    ?>
                                                    <option value="<?=$row2["id"]?>" <?=$row2["id"] == $codpgto ? "selected" : ""?>><?=$row2["nome"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            </div>
                                        </div>   
                                        <?php
    } catch (PDOException $e) {
        ?>
       
                    <h2><?="Erro: " . $e->getMessage()?></h2>
               
        <?php
    }

}



//validando nfe
if ($_acao == 15) {
 
        echo "Validando informações";
        try {
            date_default_timezone_set('America/Sao_Paulo');

            //verificar preenchimento

            //verificar cpf ou cnpj

            //verificar preenchimento produto 

            //verifificar devolução


        
if( $idemp == "") {
    $idemp  = 1;
}
          
            // Instância NFeService
            $nfe = new NFeService($idemp, 55);
    
            //Gera e assina XML
            $numero_pedido = $_parametros['id-nota'];
          
            $xml = $nfe->gerarNFe($_parametros['id-nota']);
    
            $livro = 0;
            $dataNFC = date('Y-m-d H:m:s');
            $livro = 0; $numeroNFCe = 0;
        
            /*   
           
          
            $insert = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_pedido, nfed_numlivro, nfed_xml, nfed_hora,nfed_numeronf) VALUES(?, ?, ?, ?,?)");
            $insert->bindParam(1, $numero_pedido);
            $insert->bindParam(2, $livro);
            $insert->bindParam(3, $xml);
            $insert->bindParam(4, $dataNFC);
            $insert->bindParam(5, $numeroNFCe);
            $insert->execute();
        */
      //  $recibo = $nfe->consultaRecibo('41240108911728000187550020000000041000001130');
      
      $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ? WHERE nfed_id = ? ");
      $update->bindParam(1, $xml);
      $update->bindParam(2, $numero_pedido);   
      $update->execute();
      
        $signedXML = $nfe->assinaNFe($xml);
        

     
            //Grava XML no banco e incrementa número de NF
            $consulta = $pdo->query("SELECT nfed_numeronf FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_id = '$numero_pedido' AND nfed_numlivro = '$livro'");
            $ret = $consulta->fetch();

           if ($ret['nfed_numeronf'] == 0) {
       
                $dataNFC = date('Y-m-d H:m:s');                         
                $_sql = "SELECT  empresa_nf,empresa_uf FROM ". $_SESSION['BASE'] . ".empresa where empresa_id = '$idemp'  ";
                $consulta = $pdo->query("$_sql");
                $ret = $consulta->fetch(PDO::FETCH_OBJ);
                $numeroNFCe = $ret->empresa_nf;
                $UFEMPRESA = $ret->empresa_uf;
                $dataNFC = date('Y-m-d H:m:s');

                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_numeronf = ?, nfed_xml = ?, nfed_hora = ? WHERE nfed_id = ? AND nfed_numlivro = ?");
                $update->bindParam(1, $numeroNFCe);
                $update->bindParam(2, $signedXML);
                $update->bindParam(3, $dataNFC);
                $update->bindParam(4, $numero_pedido);
                $update->bindParam(5, $livro);
                $update->execute();

                $update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".saidaestoque 
                SET SAIDA_NFE = '".$numeroNFCe."'  WHERE NUMERO= '$numero_pedido' AND num_livro = '$livro'");
                $update->execute();
                
                $update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET empresa_nf = empresa_nf + 1  where empresa_id = '$idemp' ");
                $update->bindParam(1, $empresa);
                $update->execute();
            
                
           } else {
                $_sql = "SELECT  empresa_uf FROM ". $_SESSION['BASE'] . ".empresa  where empresa_id = '$idemp'";
                $consulta = $pdo->query("$_sql");
                $ret = $consulta->fetch(PDO::FETCH_OBJ);
            
                $UFEMPRESA = $ret->empresa_uf;
            
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ? WHERE nfed_id = ? AND nfed_numlivro = ?");
                $update->bindParam(1, $signedXML);
                $update->bindParam(2, $numero_pedido);
                $update->bindParam(3, $livro);
                $update->execute();
          
         }

        
            $consulta = $pdo->query("SELECT nfed_xml,nfed_recibo FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_id = '$numero_pedido' AND nfed_numlivro = '$livro'");
            $xml = $consulta->fetch(PDO::FETCH_OBJ);

            //Transmite XML        
            if($UFEMPRESA =="BAXXX") {                
                 $recibo = $nfe->transmitirNFE_assincrono($xml->nfed_xml);
                   sleep(1);
            }else{
                  $recibo = $nfe->transmitirNFE($xml->nfed_xml);
              
            }

  //  $recibo =$xml->nfed_recibo;

   //    echo "-----------recibo---------------" ;
            $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET nfed_recibo = ?  WHERE nfed_id = ?");
            $update->bindParam(1, $recibo);       
            $update->bindParam(2, $numero_pedido); 
            $update->execute();

            $PADRAOGERAL = 1;

            if($UFEMPRESA != "BAxxxx" ) {
               //   if($PADRAOGERAL == 1){
                         $st = new Standardize();
                        $stResponse = $st->toStd($recibo);
                     //  print_r($stResponse->protNFe->infProt->cStat);
                        $Stat = $stResponse->protNFe->infProt->cStat;
                        if($Stat == ""){
                            $Stat = $stResponse->cStat;
                            $_retmotivo =  $_retmotivo .$stResponse->xMotivo;
                        }
                        $_retmotivo =  $_retmotivo .$stResponse->protNFe->infProt->xMotivo;
                        $_CHAVE  = $stResponse->protNFe->infProt->chNFe;
                      
                        $nfed_protocolo = $stResponse->protNFe->infProt->nProt;
                   //     print_r($stResponse );  
                    //    echo "-----------d---------------" ;
                     //   print_r( $_CHAVE );  
                      //  echo "-----------e---------------" ;
                        //$_CHAVE = '31230219511716000181550020000000011000001005';
                        if ($Stat != '103' and  $Stat != "100") {
                  
                            ?>
                                <div class="row">
                                                    <div class="col-sm-12" align="center">			
                                                        <p><strong> OPS </strong> !!!<?php echo "($Stat)".$_retmotivo ;?></p>
                                                    </div>
                                                </div>                               
                                                <div class="row">
                                                    <div class="col-sm-12" align="center">			
                                                    <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                                    </div>
                                                </div>
                                             
                                        <?php
                               exit();
                        } 

                        $recibo= $nfe->consultaChave($_CHAVE);
   
                      //    echo "-----------recibo---------------" ;
                    //      print_r($recibo );  
                    //      echo "-----------A---------------" ;
                        $xmlProtocolado = $nfe->autorizaXml($xml->nfed_xml,$recibo);
                       

                            $dataProtocolo = date('Y-m-d H:m:s');

                                $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
                                nfed_dataautorizacao = ?, 
                                nfed_xml_protocolado = ?, 
                                nfed_motivo = ?,											
                                nfed_protocolo =  ?,
                                nfed_chave = ?
                                WHERE nfed_id = ? AND nfed_numlivro = ?");
                                $update->bindParam(1, $dataProtocolo);
                                $update->bindParam(2, $xmlProtocolado);
                                $update->bindParam(3, $_retmotivo);				
                                $update->bindParam(4, $nfed_protocolo);	
                                $update->bindParam(5, $_CHAVE);   								
                                $update->bindParam(6, $numero_pedido);
                                $update->bindParam(7, $livro);
                                $update->execute();

                                ?>
                                            <div class="row">
                                                    <div class="col-sm-12" align="center">			
                                                        <p><strong> NF-e </strong>, Gerada com Sucesso !!!</p>
                                                    </div>
                                                </div>
                                            
                                                <div class="row">
                                                    <div class="col-sm-12" align="center">	
                                                    <button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_imprimirnf()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFe</button>
                                                    <button type="button"  class="btn btn-info  waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarnf_fim()" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-send"></i></span>Enviar Email</button>

                                                    <button type="button"  onclick="_fechar()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                                    <div id="retenvionf"></div>
                                                    </div>
                                                </div>
                                                
                                        <?php
                                exit();
                              
                //fim minas gerais
            }else{
             
                $st = new Standardize();
                $stResponse = $st->toStd($recibo);
                $nRec = $stResponse->infRec->nRec;    
                
        
            $verificaProtocolo = new Standardize();
            //$verificaProtocolo = $verificaProtocolo->toStd($protocolo);
            $verificaProtocolo = $verificaProtocolo->toStd($recibo);  
          
        //  print_r($verificaProtocolo);
            $cStat2  =    $verificaProtocolo->cStat;    
            $xMotivo2  =    $verificaProtocolo->xMotivo;    
            $cStat  =    $verificaProtocolo->protNFe->infProt->cStat;    
            $xMotivo  =    $xMotivo2." ".$verificaProtocolo->protNFe->infProt->xMotivo;   

            $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET nfed_recibo = ?, nfed_motivo = ? WHERE nfed_id = ?");
            $update->bindParam(1, $recibo);       
            $update->bindParam(2, $xMotivo);
            $update->bindParam(3, $numero_pedido); 
            $update->execute();

            if ($cStat != '100' and $cStat2 != '103' ) {
             
                ?>
                <div class="row">
                                    <div class="col-sm-12" align="center">			
                                        <p><strong>OPS - </strong> !!!<?php echo "($cStat)".$xMotivo ;?></p>
                                    </div>
                                </div>                               
                                <div class="row">
                                    <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                    </div>
                                </div>
                             
                        <?php
                   exit();
            } else {
                     sleep(2);
                     $recibo = $nfe->consultaRecibo($nRec);
                  //   print_r( $recibo);
                  //   echo '----------------<BR><BR><BR>';
                     $st = new Standardize();
                     $stResponse = $st->toStd($recibo);

             
                    $_CHAVE = $stResponse->protNFe->infProt->chNFe;
                    $nfed_protocolo = $stResponse->protNFe->infProt->nProt;
                    $_retmotivo = $stResponse->protNFe->infProt->xMotivo;
                    $cStat = $stResponse->protNFe->infProt->cStat;
                    if($cStat == ""){
                        $cStat = $stResponse->cStat;
                        $_retmotivo =  $_retmotivo .$stResponse->xMotivo;
                    }

                   

                    if ($cStat == '105' ) {
                        sleep(5);
                        $recibo = $nfe->consultaRecibo($nRec);
                      
                         $st = new Standardize();
                         $stResponse = $st->toStd($recibo);
                       
                 
                        $_CHAVE = $stResponse->protNFe->infProt->chNFe;
                        $nfed_protocolo = $stResponse->protNFe->infProt->nProt;
                        $_retmotivo = $stResponse->protNFe->infProt->xMotivo;
                        $cStat = $stResponse->protNFe->infProt->cStat;
                        if($cStat == ""){
                            $cStat = $stResponse->cStat;
                            $_retmotivo =  $_retmotivo .$stResponse->xMotivo;
                        }

                    }

                    
            if ($cStat != '100' ) {
             
                ?>
                <div class="row">
                                    <div class="col-sm-12" align="center">			
                                        <p><strong> </strong>!<?php echo "($cStat)".$_retmotivo."<br>".$xMotivo?></p>
                                    </div>
                                </div>                               
                                <div class="row">
                                    <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                    </div>
                                </div>
                             
                        <?php
                   exit();
            } else {
               
                
                    $dataProtocolo = date('Y-m-d H:m:s');
                //echo "autoriza";
                    $xmlProtocolado = $nfe->autorizaXml($xml->nfed_xml,$recibo);
            //	print_r($xmlProtocolado);
                    $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
                    nfed_dataautorizacao = ?, 
                    nfed_xml_protocolado = ?, 
                    nfed_motivo = ?,											
                    nfed_protocolo =  ?,
                    nfed_chave = ?
                    WHERE nfed_id = ? AND nfed_numlivro = ?");
                    $update->bindParam(1, $dataProtocolo);
                    $update->bindParam(2, $xmlProtocolado);
                    $update->bindParam(3, $_retmotivo);				
                    $update->bindParam(4, $nfed_protocolo);	
                    $update->bindParam(5, $_CHAVE);   								
                    $update->bindParam(6, $numero_pedido);
                    $update->bindParam(7, $livro);
                    $update->execute();

                   
                                    ?>
                                    <div class="row">
                                                        <div class="col-sm-12" align="center">			
                                                            <p><strong> NF-e </strong>, Gerada com Sucesso !!!</p>
                                                        </div>
                                                    </div>
                                            
                                                    <div class="row">
                                                        <div class="col-sm-12" align="center">	
                                                        <button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_imprimirnf()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFe</button>
                                                        <button type="button"  class="btn btn-info  waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarnf_fim()" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-send"></i></span>Enviar Email</button>
                                                        <button type="button"  onclick="_fechar()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                                        <div id="retenvionf"></div>
                                                    </div>
                                                        </div>
                                                    </div>
                                                    
                                            <?php
                                    exit();
                 }
                                
            }



            }

  

        } catch (\Exception $e) {
           // echo $e;
           
            ?>
            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <p><strong> Ops Ocorreu Erro</strong>, Envio Receita !!!<?php echo $x;?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <?php 
                                    
                                    echo $e->getmessage();
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                </div>
                            </div>
                            
                    <?php
            exit();
           
        }
    
    
}


//CANCELAMENTO nfe
if ($_acao == 16) {
    try{
        date_default_timezone_set('America/Sao_Paulo');      

        if( $idemp == "") {
            $idemp  = 1;
        }
        // Instância NFeService
    //  $nfe = new NFeService($idemp, 55);

      
    if( $_parametros['xmodelo'] == '55'){
        $nfe = new NFeService($idemp, 55);
    }else{
        $nfe = new NFeService($idemp, 65);
    }

        $chave =  $_parametros['xchave'];
        $xJust =   trim($_parametros['xJust']);
        $xJust =  str_replace('*',"-",$xJust);
        $xJust = str_replace("•", "-", $xJust);
       // $xJust = urlencode($xJust);
        $nProt =  $_parametros['xnProt'];

        if( strlen($xJust) < 15 or $xJust == "") { ?>
        
            <div class="row">
                <div class="col-sm-12" align="center">			
                    <p><strong> Motivo da Justificativa não pode ser inferior a 15 caracteres</strong> !!!</p>
                </div>
                </div>                               
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <button type="button"  onclick="fecharModalC()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                </div>
                            </div>
                         
                    <?php
                    exit();

}

        $numero_pedido = $_parametros['id-nota'];
        
        $livro = 0;
     
        $retcancelamento = $nfe->CancelarNF($chave, $xJust, $nProt);
      //  print_r($retcancelamento);
    //   echo "----------------------";
        $st = new Standardize();
        $stResponse = $st->toStd($retcancelamento);
        $cStat = $stResponse->retEvento->infEvento->cStat;
        $xMotivo = $stResponse->retEvento->infEvento->xMotivo;
        $xcancelada = 0;
     
        if( $cStat != "135") {
            $xcancelada = 0;
            ?>
        
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> Ops </strong> !!!<?php echo $xMotivo ;?></p>
                        </div>
                        </div>                               
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModalC()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                        </div>
                                    </div>
                                 
                            <?php

        }else{
            $xcancelada = 1;
            ?>
        
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> Executado</strong> !!!<?php echo $xMotivo ;?></p>
                        </div>
                        </div>                               
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModalC()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                        </div>
                                    </div>
                                
                            <?php
        }
        
       $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
       nfed_cancelada = ?,
       nfed_motEcarta = ?, 
       nfed_xmlcancelado = ?
       WHERE nfed_cancelada = '0' and  nfed_id = ? AND nfed_numlivro = ?");
       $update->bindParam(1, $xcancelada);
       $update->bindParam(2, $xJust);
       $update->bindParam(3, $retcancelamento);     					
       $update->bindParam(4, $numero_pedido);
       $update->bindParam(5, $livro);
       $update->execute();
    } catch (\Exception $e) {
        echo $e->getMessage();
    }


}

if ($_acao == 17) {?>
    <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline text-danger"></i>
                </div>
                <h3><span>você deseja realmente cancelar a NF-e ?</span> </h3>
                <p>
                    <button class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_CancelarNF()">Cancelar</button>
                </p>
                <div>
                </div>

    <?php }

if ($_acao == 18) {

    $DEL = $pdo->prepare("DELETE  FROM ".$_SESSION['BASE'].".NFE_DADOS
    WHERE nfed_numeronf = '0' and  nfed_id = ? ");
    $DEL->bindParam(1, $_parametros["id-nota"]);
    $DEL->execute();
 }

 if ($_acao == 19) {	 

    $sql = "SELECT NAT_FINALIDADE,NAT_OPERACAO,NAT_TIPODOCUMENTO FROM ".$_SESSION['BASE'].".cfop where ID = '" . $_parametros["nf-operacao"] . "' limit 1";        
    $statement = $pdo->query("$sql");
    $retorno = $statement->fetchAll();  
    foreach ($retorno as $row) {                
        $_ret = $row['NAT_FINALIDADE'];
        $_ret =  $_ret.";".$row['NAT_OPERACAO'].";".$row['NAT_TIPODOCUMENTO'];
    }
    echo $_ret ;

 }

 if ($_acao == 199) {	 

    $sql = "SELECT NAT_CODIGO,NAT_DESCRICAO,ID FROM ".$_SESSION['BASE'].".cfop  WHERE   NAT_OPERACAO = '" . $_parametros["nf-destinooperacao"] . "' ORDER BY NAT_CODIGO";        
    $statement = $pdo->query("$sql");
    $retornoOp = $statement->fetchAll();  
?>        
    <option value="">Selecione</option>
        <?php
           foreach ($retornoOp as $row) {
        ?>
         <option value="<?=$row["ID"]?>"><?=$row["NAT_CODIGO"]."-".($row["NAT_DESCRICAO"])?></option>
        <?php
        }
                                        
  
    exit();

 }

 
//pre validacao e visualização nfe
if ($_acao == 20) {
 
    echo "Validando informações - PRE-VISUALIZAÇÃO";
 
    try {
        date_default_timezone_set('America/Sao_Paulo');
    

        if( $idemp == "") {
            $idemp  = 1;
        }
        // Instância NFeService
        $nfe = new NFeService($idemp, 55);

        //Gera e assina XML
        $numero_pedido = $_parametros['id-nota'];
        $xml = $nfe->gerarNFe($_parametros['id-nota']);

        $livro = 0;
        $dataNFC = date('Y-m-d H:m:s');
        $livro = 0; $numeroNFCe = 0;
     
  
    $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ? WHERE nfed_id = ? ");
    $update->bindParam(1, $xml);
    $update->bindParam(2, $numero_pedido);   
    $update->execute();
  
    $signedXML = $nfe->assinaNFe($xml);

 
        //Grava XML no banco e incrementa número de NF
        $consulta = $pdo->query("SELECT nfed_numeronf FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_id = '$numero_pedido' AND nfed_numlivro = '$livro'");
        $ret = $consulta->fetch();

       if ($ret['nfed_numeronf'] == 0) {
   
            $dataNFC = date('Y-m-d H:m:s');                         
            $_sql = "SELECT  empresa_nf FROM ". $_SESSION['BASE'] . ".empresa  where empresa_id = '$idemp'";
            $consulta = $pdo->query("$_sql");
            $ret = $consulta->fetch(PDO::FETCH_OBJ);
            $numeroNFCe = $ret->empresa_nf;
            $dataNFC = date('Y-m-d H:m:s');

            
       } else {
     
     
        $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?  WHERE nfed_id = ?");
        $update->bindParam(1, $signedXML);    
        $update->bindParam(2, $numero_pedido);
  
        $update->execute();
    
      
     }
     ?>
     <div class="row">
                         <div class="col-sm-12" align="center">			
                             <p><strong> NF-e </strong>, Gerada com Sucesso !!!</p>
                         </div>
                     </div>
                
                     <div class="row">
                         <div class="col-sm-12" align="center">	
                         <button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_previsualizacao()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>PRÉ VISUALIZAÇÃO NFe</button>
                     
                        
                       
                         </div>
                     </div>
                     
             <?php

exit();
    


    } catch (\Exception $e) {
       // echo $e;
        
        ?>
        <div class="row">
                            <div class="col-sm-12" align="center">			
                                <p><strong> Ops Ocorreu Erro</strong> !!!<?php echo $x;?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">			
                                <?php 
                                
                                echo $e->getmessage();
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">			
                            <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                            </div>
                        </div>
                        
                <?php
        exit();
    }



}


//buscar retorno receita
if ($_acao == 21) {
 
    echo "Buscando informações Receita";
 
    try {
        date_default_timezone_set('America/Sao_Paulo');
    

        if( $idemp == "") {
            $idemp  = 1;
        }
        // Instância NFeService
        $nfe = new NFeService($idemp, 55);

        $numero_pedido = $_parametros['id-nota'];

 
        //Grava XML no banco e incrementa número de NF
        $consulta = $pdo->query("SELECT nfed_xml,nfed_recibo,nfed_numeronf FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_xml_protocolado = '' AND nfed_id = '$numero_pedido' ");
        $ret = $consulta->fetch();
    

       if ($ret['nfed_numeronf'] != 0) {
   
            $dataNFC = date('Y-m-d H:m:s'); 
            
            $xmlString = $ret['nfed_recibo'];

            $xmlNFE  =  $ret['nfed_xml'];
          

            // Carregar o XML ignorando namespaces
            $xml = simplexml_load_string($xmlString, null, LIBXML_NOCDATA);
            // Usando preg_match para capturar a tag <chNFe>
            if (preg_match('/<chNFe>(.*?)<\/chNFe>/', $xmlString, $matches)) {
                $chNFe = $matches[1];
                echo "CHAVE : " . $chNFe . "<br>";
            } else {
                echo "Tag <chNFe> não encontrada." . PHP_EOL;
            }
                    
            $_CHAVE = $chNFe;

         

        $recibo= $nfe->consultaChave($_CHAVE);
       
         $st = new Standardize();
         $stResponse = $st->toStd($recibo);
      //  print_r($stResponse->protNFe->infProt->cStat);
         $Stat = $stResponse->protNFe->infProt->cStat;
         if($Stat == ""){
             $Stat = $stResponse->cStat;
             $_retmotivo =  $_retmotivo .$stResponse->xMotivo;
         }
         $_retmotivo =  $_retmotivo .$stResponse->protNFe->infProt->xMotivo;
      //   $_CHAVE  = $stResponse->protNFe->infProt->chNFe;
       
         $nfed_protocolo = $stResponse->protNFe->infProt->nProt;
      //   $recibo = '<nferesultmsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NFeConsultaProtocolo4"><retconssitnfe versao="4.00" xmlns="http://www.portalfiscal.inf.br/nfe"><tpamb>1</tpamb><veraplic>SP_NFE_PL009_V4</veraplic><cstat>100</cstat><xmotivo>Autorizado o uso da NF-e</xmotivo><cuf>35</cuf><dhrecbto>2025-01-22T14:19:28-03:00</dhrecbto><chnfe>35250139004825000110550020000009781000018633</chnfe><protnfe versao="4.00"><infprot><tpamb>1</tpamb><veraplic>SP_NFE_PL009_V4</veraplic><chnfe>35250139004825000110550020000009781000018633</chnfe><dhrecbto>2025-01-22T11:24:22-03:00</dhrecbto><nprot>135250195075512</nprot><digval>TvM7AqXaNgLH3J4DbQ1ixjtJEUM=</digval><cstat>100</cstat><xmotivo>Autorizado o uso da NF-e</xmotivo></infprot></protnfe></retconssitnfe></nferesultmsg>';
/*
             // Usando preg_match para capturar a tag <chNFe>
             if (preg_match('/<cstat>(.*?)<\/cstat>/', $recibo, $matches)) {
                $cstat = $matches[1];
              //  echo "cstat : " . $cstat . "<br>";
            }

               // Usando preg_match para capturar a tag <chNFe>
               if (preg_match('/<xmotivo>(.*?)<\/xmotivo>/', $recibo, $matches)) {
                $xmotivo = $matches[1];
              //  echo "xmotivo : " . $xmotivo . "<br>";
            }
   
         */

        //   echo "----- $_CHAVE------recibo---------------" ;
        //   print_r($recibo );  
      //  echo "-----------A----------$ $Stat -----" ;


           if($Stat == '100') {
           // echo "cstat : " .  $Stat . "<br>";
         
             $xmlProtocolado = $nfe->autorizaXml($xmlNFE,$recibo);

           // print_r($xmlProtocolado);
                  // Usando preg_match para capturar a tag <chNFe>
                  if (preg_match('/<nprot>(.*?)<\/nprot>/', $recibo, $matches)) {
                    $nfed_protocolo = $matches[1];             
                }
             

                  $dataProtocolo = date('Y-m-d H:m:s');

                      $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
                      nfed_dataautorizacao = ?, 
                      nfed_xml_protocolado = ?, 
                      nfed_motivo = ?,											
                      nfed_protocolo =  ?,
                      nfed_chave = ?
                      WHERE nfed_id = ? ");
                      $update->bindParam(1, $dataProtocolo);
                      $update->bindParam(2, $xmlProtocolado);
                      $update->bindParam(3, $xmotivo );				
                      $update->bindParam(4, $nfed_protocolo);	
                      $update->bindParam(5, $_CHAVE);   								
                      $update->bindParam(6, $numero_pedido);
                      $update->execute();

                      ?>
                                  <div class="row">
                                          <div class="col-sm-12" align="center">			
                                              <p><strong> NF-e </strong>, Gerada com Sucesso !!!</p>
                                          </div>
                                      </div>
                                  
                                      <div class="row">
                                          <div class="col-sm-12" align="center">	
                                          <button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_imprimirnf()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFe</button>
                                          <button type="button"  class="btn btn-info  waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarnf_fim()" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-send"></i></span>Enviar Email</button>

                                          <button type="button"  onclick="_fechar()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                          <div id="retenvionf"></div>
                                          </div>
                                      </div>
                                      
                              <?php
                      exit();
                    }else{
                        ?>
                        <div class="row">
                                            <div class="col-sm-12" align="center">			
                                                <p><strong> Ops Ocorreu Erro</strong> !!!</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12" align="center">			
                                           
                                                
                                                <?php echo $_retmotivo;?>
                                            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12" align="center">			
                                            <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                            </div>
                                        </div>
                                        
                                <?php
                        exit();
                    } 

            
       } else {
        ?>
                        <div class="row">
                                            <div class="col-sm-12" align="center">			
                                                <p><strong> Não existe nota enviada Sefaz para Atualizar</strong> !!!</p>
                                            </div>
                                        </div>
                                       
                                        <div class="row">
                                            <div class="col-sm-12" align="center">			
                                            <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                            </div>
                                        </div>
                                        
                                <?php
                        exit();

       }
     

exit();
    


    } catch (\Exception $e) {
       // echo $e;
        
        ?>
        <div class="row">
                            <div class="col-sm-12" align="center">			
                                <p><strong> Ops Ocorreu Erro</strong> !!!<?php echo $x;?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">			
                                <?php 
                                
                                echo $e->getmessage();
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">			
                            <button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                            </div>
                        </div>
                        
                <?php
        exit();
    }



}

//inutilizacao nfe
if ($_acao == 22) {
    try{
        date_default_timezone_set('America/Sao_Paulo');      

        if( $idemp == "") {
            $idemp  = 1;
        }
        // Instância NFeService
       
        if( $_parametros['xmodelo'] == '55'){
            $nfe = new NFeService($idemp, 55);
        }else{
            $nfe = new NFeService($idemp, 65);
        }
        

        if($_parametros['xJust'] == ""){
            $_parametros['xJust'] = "NAO UTILIZADO NUMERO NF PELO SISTEMA";
        }

        $chave =  $_parametros['xchave'];
        $xJust =   trim($_parametros['xJust']);
        $xJust =  str_replace('*',"-",$xJust);
        //$xJust = urlencode($xJust);
        $nProt =  $_parametros['xnProt'];
        $nSerie =  $_parametros['xnSerie']; 
        $nIni  =  $_parametros['xnNF']; 
        $nFin  =  $nIni;
      

        
        if( $nSerie == 0) { ?>
             <div class="row">
                <div class="col-sm-12" align="center">			
                    <p><strong> Ops !!! Série NF incorreta para inutilização, fale com suporte</strong> !!!</p>
                </div>
                </div>                               
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <button type="button"  onclick="_fechar()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                </div>
                            </div>
                         
                    <?php
                    exit();

       
        }

        if( strlen($xJust) < 15 or $xJust == "") { ?>
        
            <div class="row">
                <div class="col-sm-12" align="center">			
                    <p><strong> Motivo da Justificativa não pode ser inferior a 15 caracteres</strong> !!!</p>
                </div>
                </div>                               
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <button type="button"  onclick="_fechar()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                </div>
                            </div>
                         
                    <?php
                    exit();

}

        $numero_pedido = $_parametros['id-nota'];
        
        $livro = 0;
   
        $retcancelamento = $nfe->InutilizarNF($nSerie,$nIni, $nFin,$xJust);
    
    //// echo "----------------------";
   //  print_r( $retcancelamento);
  // echo "----------------------";
        $xJust = ($xJust);
        $st = new Standardize();
        $stResponse = $st->toStd($retcancelamento);
        $cStat = $stResponse->retEvento->infEvento->cStat;
        $xMotivo = $stResponse->retEvento->infEvento->xMotivo;
        if( $cStat == "") {
            $cStat = $stResponse->infInut->cStat;
            $xMotivo = $stResponse->infInut->xMotivo;
        }
     
    //  print_r($stResponse);
     // echo "----------------------";
      //  $xINUTILIZADA = 0;
      
    // echo "-- $cStat------ $xMotivo-------------";
        if( $cStat != "102") {
            $xINUTILIZADA = 0;
            ?>
        
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> Ops </strong> !!!<?=  print_r($retcancelamento);?></p>
                        </div>
                        </div>                               
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModalI()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                        </div>
                                    </div>
                                 
                            <?php

        }else{
            $xINUTILIZADA= 2;
            ?>
        
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> Executado</strong> !!!<?php echo $xMotivo ;?></p>
                        </div>
                        </div>                               
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModalC()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                        </div>
                                    </div>
                                
                            <?php
        }
        
       $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
       nfed_cancelada = ?,
       nfed_motEcarta = ?, 
       nfed_xmlcancelado = ?
       WHERE nfed_cancelada = '0' and  nfed_id = ? ");
       $update->bindParam(1, $xINUTILIZADA);
       $update->bindParam(2, $xJust);
       $update->bindParam(3, $retcancelamento);     					
       $update->bindParam(4, $numero_pedido);    
       $update->execute();
      // echo " $xINUTILIZADA-$xJust-$retcancelamento-$numero_pedido)";
    } catch (\Exception $e) {
        echo $e->getMessage();
    }


}


//carta nfe
if ($_acao == 23) {
    try{
        date_default_timezone_set('America/Sao_Paulo');      

        if( $idemp == "") {
            $idemp  = 1;
        }
        $_tipoevento = 2; //1-cancelamento 2 carta correção 3 inutilização
        $codigoevento = '110110';
        $modelo = "55";
        // Instância NFeService
        $nfe = new NFeService($idemp, 55);

        $chave =  $_parametros['xchave'];
        $xJust =   trim($_parametros['xJust']);
        $xJust =  str_replace('*',"-",$xJust);
        $xJust = ($xJust);
        $nProt =  $_parametros['xnProt'];
        $nSerie =  $_parametros['xnSerie']; 
        $nIni  =  $_parametros['xnNF']; 
        $nFin  =  $nIni;
        if( strlen($xJust) < 15 or $xJust == "") { ?>
        
            <div class="row">
                <div class="col-sm-12" align="center">			
                    <p><strong> Motivo da Justificativa não pode ser inferior a 15 caracteres</strong> !!!</p>
                </div>
                </div>                               
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <button type="button"  onclick="fecharModalI()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                </div>
                            </div>
                         
                    <?php
                    exit();

}

        $numero_pedido = $_parametros['id-nota'];
        $numeroevento = 1;
        $sql = "SELECT evNfe_nNEvento     FROM " . $_SESSION['BASE'] . ".NFE_EVENTO               
         WHERE evNfe_mod = '55' and evNfe_nNumero = '".$_parametros['xnNF']."' order by evNfe_id DESC limit 1";       
     
        $statement = $pdo->query("$sql");
        $row = $statement->fetch();   
        
        if($statement->rowCount() > 0){       
            $numeroevento =$row['evNfe_nNEvento'] + 1;
        }
    

       $retcancelamento = $nfe->cartaNF($chave, $xJust, $numeroevento);
       
        $st = new Standardize();
        $stResponse = $st->toStd($retcancelamento);
        $cStat = $stResponse->retEvento->infEvento->cStat;
        $xMotivo =  $stResponse->retEvento->infEvento->xMotivo;
        $xEvento = $stResponse->retEvento->infEvento->xEvento;
        $xProtocolo= $stResponse->retEvento->infEvento->nProt;
        $dtregistro = $stResponse->retEvento->infEvento->dhRegEvento;
        $dtregistro = substr($dtregistro,0,10)." ".$dtregistro =substr($dtregistro,11,8);


   
        if( $cStat != "135") {
            //$xINUTILIZADA = 0;
            ?>
        
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> Ops </strong> !!!<?php echo "($cStat)".$xMotivo ;?><?=  print_r($retcancelamento);?></p>
                        </div>
                    </div>                               
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModalI()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                        </div>
                            </div>
                                 
                            <?php

        }elseif($cStat == '135' || $cStat == '136') {
           
            //DEU CERTO
            //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
          //  $xml = Complements::toAuthorize($tools->lastRequest, $response);
          
            ?>
        
            <div class="row">
                <div class="col-sm-12" align="center">			
                    <p><strong> Executado</strong> !!!<?php echo $xMotivo ;?></p>
                </div>
                </div>                               
                    <div class="row">
                        <div class="col-sm-12" align="center">
                            <button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00044"  onclick="_imprimirCarta('<?=$numeroevento;?>')"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir Carta</button>			
                            <button type="button" class="btn btn-white waves-effect waves-light" onclick="fecharModalI()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                </div>
                            </div>
                        <?php
        
        }

        
            else{
           // $xINUTILIZADA= 2;
            ?>
        
        <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> Ops </strong> !!! Falha de envio</p>
                        </div>
                        </div>                               
                            <div class="row">
                                <div class="col-sm-12" align="center">			
                                    <button type="button"  onclick="fecharModalI()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
                                        </div>
                                    </div>                                
                            <?php
        }
        try{
            
            $update = $pdo->prepare("INSERT  INTO ".$_SESSION['BASE'].".NFE_EVENTO (                       
                evNfe_cStat,
                evNfe_xMotivo,            
                evNfe_mod,
                evNfe_empresa,
                evNfe_serie,
                evNfe_nNumero,
                evNfe_nNEvento,
                evNfe_Recbto,
                evNfe_nProt,
                evNfe_nidDados,evNfe_tipoevento,evNfe_nChave,
                evNfe_dataReg,evNfe_codigevento) VALUES (
                   ?,            
                    ?,
                    ?,            
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,?,?,?)");
            $update->bindParam(1, $cStat);
            $update->bindParam(2, $xJust);
            $update->bindParam(3, $modelo);     					
            $update->bindParam(4, $idemp );
            $update->bindParam(5, $nSerie);
            $update->bindParam(6, $_parametros['xnNF']);
            $update->bindParam(7, $numeroevento);                     
            $update->bindParam(8, $retcancelamento);
            $update->bindParam(9, $xProtocolo);  
            $update->bindParam(10, $_parametros['id-nota']);
            $update->bindParam(11, $_tipoevento);
            $update->bindParam(12, $_parametros['xchave']);
            $update->bindParam(13, $dtregistro);
            $update->bindParam(14, $codigoevento);
            $update->execute();

           // $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_EVENTO SET   evNfe_xmlret = ?   where evNfe_nProt = ? ");
          // $update->bindParam(1, $retXml);
          //  $update->bindParam(2, $xProtocolo);  
           // $update->execute();
            

         } catch (\Exception $e) {
            echo $e->getMessage();
                
         }
        
  
    } catch (\Exception $e) {
             echo $e->getMessage();
  
    }


}

//faturas e duplicatas nfe
if ($_acao == 24) {
    
   
    $id_basenf = $_parametros['id-nota'];

    $statement = $pdo->query("SELECT *,DATE_FORMAT(Nfat_vencimento , '%d/%m/%Y') AS data_vencimento FROM ".$_SESSION['BASE'].".NFE_FATURA    
     WHERE Nfat_idnf = '".$_parametros["id-nota"]."' ORDER BY Nfat_vencimento");
    $retorno = $statement->fetchAll();
    $totalFatura = 0.0;
 ?>
    <table id="datatable-responsive-fatura" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">       
            <div class="row text-right">
                <button id="cadastrar-fatura" type="button" class="btn btn-success waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-fatura" onclick="_faturaModal()">Incluir Fatura<span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button>
            </div>
        <thead>
        <tr>
            <th>Nº Fatura</th>
            <th>Vencimento</th>              
            <th class="text-center">Valor</th>
            <th class='text-right'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            $_idexcluir = $row["Nfat_id"];
            ?>
            <tr class="gradeX">
                <td><?=$row["Nfat_numerofat"]?></td>
                <td><?=$row["data_vencimento"]?></td>                            
                <td class="text-center"><?=number_format($row["Nfat_valor"],2,',','.')?></td>        
                <td class="actions text-right">
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idexcluir(<?=$_idexcluir?>)"><i class="fa fa-trash-o fa-2x"></i></a>
                </td>
          
            </tr>
            <?php
            $totalFatura += $row["Nfat_valor"];
        }
        ?>
        </tbody>
    </table>
    <div class="alert alert-info text-right">
        Total <strong>R$<?=number_format($totalFatura, 2, ',', '.')?></strong>
    </div>
<?php

}

//faturas e duplicatas incluir modal
if ($_acao == 25) {
    $sql = "SELECT nfed_totalnota from ".$_SESSION['BASE'].".NFE_DADOS where nfed_id = '".$_parametros['id-nota']."'"; 
    $statement = $pdo->query("$sql");
    $ret = $statement->fetch();    

  
    ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Cadastro de Fatura</h4>
        </div>
        <div class="modal-body">
           
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-fatura" id="form-fatura">
                <div class="row">
                    <div class="form-group clearfix col-md-4">
                        <label class="control-label " for="fatura-tipopagamento">Selecione condições Pgto:</label>
                        <?php
                        $statement = $pdo->query("SELECT * from ".$_SESSION['BASE'].".tiporecebimfatura");
                        $pagamento = $statement->fetchAll();
                        ?>
                        <select name="fatura-tipopagamento" id="fatura-tipopagamento" class="form-control" onchange="_listarParcelasFatura()">
                            <option value="">Selecione</option>
                            <?php
                            foreach ($pagamento as $row) {
                                ?>
                                <option value="<?=$row["trfat_id"]?>"><?=$row["trfat_descricao"]?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="hidden" name="fatura-nota" id="fatura-nota" value="<?=$_parametros["id-nota"]?>">
                       <input type="hidden" name="fatura-valort" id="fatura-valort" value="<?=$ret["nfed_totalnota"]?>">
                    </div>
                   
                    </div>
                    <div id="retparcela">
                  

                    </div>
                </form>
          
        </div>
    </div>
    <?php

}

//faturas e duplicatas cadastrar
if ($_acao == 26) { 


  
    ?>
                            <div class="row">
                                <div class="form-group clearfix col-md-3">
                                <strong>Vencimento</strong>
                                  
                                </div>
                                <div class="form-group clearfix col-md-3">
                                 <strong> Valor</strong>                                    
                                </div>
                                <div class="form-group clearfix col-md-4">
                                 <strong> Nº Fatura</strong>                                    
                                </div>
                            </div>
    <?php
    
                        $statement = $pdo->query("SELECT * from ".$_SESSION['BASE'].".tiporecebimfatura where trfat_id = '".$_parametros['fatura-tipopagamento']."'");
                        $pagamento = $statement->fetchAll();
                
                            foreach ($pagamento as $row) {
                                $dias =  $row['trfat_dias'];
                                $parcela =  $row['trfat_parcela'];
                                $vlr =  number_format(( $_parametros['fatura-valort']/$parcela ), 2, ',', '.');
                              
                            //loop do parcelamento----------------------
                            $Linha = 0;
                            while($Linha < $parcela) {

                                $Linha++; 
                                $numerofat = str_pad( $Linha, 3, 0, STR_PAD_LEFT);
                                
                                if($data_atual == ""){
                                    $data_atual = date('d/m/Y');
                                }							
                                $data12 = SomarData($data_atual, $dias, 0, 0); 
                                $dia = substr("$data12",0,2); 
                                $mes = substr("$data12",3,2); 
                                $ano = substr("$data12",6,4); 
                                $data_atual = "$dia/$mes/$ano";

                                $_vencimento = "$ano-$mes-$dia";
                            ?>
                      
                            <div class="row">
                                <div class="form-group clearfix col-md-3">

                                    <input id="fatura-vencimento<?=$Linha;?>" name="fatura-vencimento<?=$Linha;?>" type="date" class="form-control" value="<?=$_vencimento;?>">
                                </div>
                                <div class="form-group clearfix col-md-3">
                                   
                                    <div class="input-group">
                                        <input id="fatura-valor<?=$Linha;?>" name="fatura-valor<?=$Linha;?>" type="text" class="form-control" value="<?=$vlr;?>">
                                    
                                    </div>
                                </div>
                                <div class="form-group clearfix col-md-2">
                                   
                                    <div class="input-group">
                                        <input id="fatura-numero<?=$Linha;?>" name="fatura-numero<?=$Linha;?>" type="text" class="form-control" value="<?=$numerofat ;?>" maxlength="3">
                                    
                                    </div>
                                </div>
                            </div>
                            <?php 
                            }
                        } ?>
                    <div class="form-group clearfix col-md-4">
                      
                        <div class="input-group">                           
                            <div class="input-group-btn">
                                <button id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_cadastraFatura()">Cadastrar<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                            </div>
                        </div>
                    </div>
    
<?php
}

//faturas e duplicatas gerar parcelas
if ($_acao == 27) {
    $dia       = date('d');
    $mes       = date('m');
    $ano       = date('Y');
    $hora = date("H:i:s");
    
    $dataatual      = $ano . "-" . $mes . "-" . $dia;
    $statement = $pdo->query("SELECT * from ".$_SESSION['BASE'].".tiporecebimfatura where trfat_id = '".$_parametros['fatura-tipopagamento']."'");
    $pagamento = $statement->fetchAll();

    foreach ($pagamento as $row) {
     
        $parcela =  $row['trfat_parcela'];
            
                            //loop do parcelamento----------------------
                            $Linha = 0;
                            while($Linha < $parcela) {

                                $Linha++; 
                                $_fatvalor = LimpaVariavel($_parametros["fatura-valor$Linha"]);   
                                $_fatvencimento = $_parametros["fatura-vencimento$Linha"];
                                                 
                               $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".NFE_FATURA
                                 (Nfat_idnf,Nfat_vencimento,Nfat_datacad,Nfat_valor,Nfat_numerofat) VALUES (?,?,?,?,?)");
                                $statement->bindParam(1, $_parametros["fatura-nota"]);
                                $statement->bindParam(2, $_fatvencimento);
                                $statement->bindParam(3, $dataatual);
                                $statement->bindParam(4, $_fatvalor);  
                                $statement->bindParam(5, $_parametros["fatura-numero$Linha"]);                              
                                $statement->execute();

                            }
    }

   
}

//faturas e duplicatas excluir
if ($_acao == 28) {
    $statement = $pdo->prepare("DELETE FROM ".$_SESSION['BASE'].".NFE_FATURA  WHERE Nfat_id = ? ");       
    
    $statement->bindParam(1, $_parametros['id-exclusao']);      
    $statement->execute();    
}


//CALCULAR ICMS
if ($_acao == 29) {

  // print_r($_parametros); 
  $_CAMPOCALCULAR   = $_parametros['_campocalc'];
  $_PORCENTO  = $_parametros['produto-aliqIcms'];
  $_PORCENTOST  = $_parametros['produto-aliqIcmsST'];
  $_PORCENTOFCP  = $_parametros['produto-fcpST'];
  $_PORCENTOSTret = $_parametros['produto-aliqIcmsSTret'];
  $_PORCENTOmodBCSTret  = $_parametros['produto-modBCSTret'];
  
  $_QTDE = $_parametros['produto-quantidade'];
  $_VLRCALCULADO = 0;

  $_BASEICMS  = str_replace(".", "",  $_parametros["produto-baseIcms"]);
  $_BASEICMS = str_replace(",", ".",  $_BASEICMS);
  $_BASEICMS = str_replace("'", "",  $_BASEICMS );

  $_VALORPRODUTO = str_replace(".", "",  $_parametros["produto-valor"]);
  $_VALORPRODUTO = str_replace(",", ".",  $_VALORPRODUTO);
  $_VALORPRODUTO = str_replace("'", "",  $_VALORPRODUTO );

  $_BASEICMS_ST  = str_replace(".", "",  $_parametros["produto-aliqIcmsST"]);
  $_BASEICMS_ST = str_replace(",", ".",  $_BASEICMS_ST);
  $_BASEICMS_ST = str_replace("'", "",  $_BASEICMS_ST );

  $_BASEICMS_STret  = str_replace(".", "",  $_parametros["produto-vlrIcmsSTret"]);
  $_BASEICMS_STret = str_replace(",", ".",  $_BASEICMS_STret);
  $_BASEICMS_STret = str_replace("'", "",  $_BASEICMS_STret );


  
  
  if($_CAMPOCALCULAR  == 'produto-aliqIcms'){
   
    if($_BASEICMS > 0) {
        $_VLRCALCULADO = $_BASEICMS*($_PORCENTO/100);
    }else{
        $_VLRCALCULADO = ($_VALORPRODUTO*$_QTDE)*($_PORCENTO/100);
        $_BASEICMS = ($_VALORPRODUTO*$_QTDE);
    }
  }

  if($_CAMPOCALCULAR  == 'produto-aliqIcmsST'){
   
    if($_BASEICMS_ST > 0) {
        $_VLRCALCULADO = $_BASEICMS_ST*($_PORCENTOST/100);
        $_BASEICMS = $_BASEICMS_ST;
    }else{
        $_VLRCALCULADO = ($_VALORPRODUTO*$_QTDE)*($_PORCENTOST/100);
        $_BASEICMS = ($_VALORPRODUTO*$_QTDE);
    }
  }

  if($_CAMPOCALCULAR  == 'produto-fcpST'){
   
    if($_BASEICMS_ST > 0) {
        $_VLRCALCULADO = $_BASEICMS_ST*($_PORCENTOFCP/100);
        $_BASEICMS = $_BASEICMS_ST;
    } elseif($_BASEICMS > 0){
        $_VLRCALCULADO = $_BASEICMS*($_PORCENTOFCP/100);
      
    }
    
    else{
        $_VLRCALCULADO = ($_VALORPRODUTO*$_QTDE)*($_PORCENTOFCP/100);
        $_BASEICMS = ($_VALORPRODUTO*$_QTDE);
    }
  }

  
  if($_CAMPOCALCULAR  == 'produto-aliqIcmsSTret'){
   
    if($_BASEICMS_STret > 0) {
        $_VLRCALCULADO = $_BASEICMS_STret*($_PORCENTOSTret/100);
        $_BASEICMS = $_BASEICMS_STret;
    }else{
        $_VLRCALCULADO = ($_VALORPRODUTO*$_QTDE)*($_PORCENTOSTret/100);
        $_BASEICMS = ($_VALORPRODUTO*$_QTDE);
    }
  }

  if($_CAMPOCALCULAR  == 'produto-aliqIcmsSTret'){
   
    if($_BASEICMS_STret > 0) {
        $_VLRCALCULADO = $_BASEICMS_STret*($_PORCENTOSTret/100);
        $_BASEICMS = $_BASEICMS_STret;
    }else{
        $_VLRCALCULADO = ($_VALORPRODUTO*$_QTDE)*($_PORCENTOSTret/100);
        $_BASEICMS = ($_VALORPRODUTO*$_QTDE);
    }
  }
  if($_CAMPOCALCULAR  == 'produto-modBCSTret'){
   
    if($_BASEICMS_STret > 0) {
        $_VLRCALCULADO = $_BASEICMS_STret*($_PORCENTOmodBCSTret/100);
        $_BASEICMS = $_BASEICMS_STret;
    }  
    else{
        $_VLRCALCULADO = ($_VALORPRODUTO*$_QTDE)*($_PORCENTOmodBCSTret/100);
        $_BASEICMS = ($_VALORPRODUTO*$_QTDE);
    }
  }

  

  echo "$_CAMPOCALCULAR;".number_format($_BASEICMS, 2, ',', '.').";".number_format($_VLRCALCULADO, 2, ',', '.');

}