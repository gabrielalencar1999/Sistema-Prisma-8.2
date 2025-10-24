 <?php 
require_once('../../../api/config/config.inc.php');
require '../../../api/vendor/autoload.php';
$_SESSION['BASE'] = "bd_novo";
include("../../../api/config/iconexao.php");



date_default_timezone_set('America/Sao_Paulo');



use Database\MySQL;

$pdo = MySQL::acessabd();



  try {
	  
	 // loop em arquivos 
	foreach(glob('*.xml') as $xmlFile){

	 $xml = simplexml_load_file($xmlFile);
     $px++;

  //  $xml = simplexml_load_file($arquivo_caminho);

    $_numeronota = $xml->NFe->infNFe->ide->nNF;
    $_emissao =  substr($xml->NFe->infNFe->ide->dhEmi,0,10);
    $_totalnf = $xml->NFe->infNFe->total->ICMSTot->vNF;
    $_chave  = $xml->protNFe->infProt->chNFe;
    $_cnpj = $xml->NFe->infNFe->emit->CNPJ;
    $_ie = $xml->NFe->infNFe->emit->IEST;
    $_razao = $xml->NFe->infNFe->emit->xNome;
    $_fantasia = $xml->NFe->infNFe->emit->xFant;

    $_endereco  = $xml->NFe->infNFe->emit->enderEmit->xLgr;
    $_numero = $xml->NFe->infNFe->emit->enderEmit->nro;
    $_bairro  = $xml->NFe->infNFe->emit->enderEmit->xBairro;
    $_cidade  = $xml->NFe->infNFe->emit->enderEmit->xMun;
    $_uf  = $xml->NFe->infNFe->emit->enderEmit->UF;
    $_cep = $xml->NFe->infNFe->emit->enderEmit->CEP;
    $_telefone  = $xml->NFe->infNFe->emit->enderEmit->fone;

    $_total = $xml->NFe->infNFe->total->ICMSTot->vNF;
    $_baseicms = $xml->NFe->infNFe->total->ICMSTot->vBCST;
    $_frete = $xml->NFe->infNFe->total->ICMSTot->vFrete;
    $_desconto = $xml->NFe->infNFe->total->ICMSTot->vDesc;
    $_icms = $xml->NFe->infNFe->total->ICMSTot->vICMS;
    $_ipi= $xml->NFe->infNFe->total->ICMSTot->vIPI;
    $_outros = $xml->NFe->infNFe->total->ICMSTot->vOutro;

    $_totalnota =   $xml->NFe->infNFe->total->ICMSTot->vProd;

    /*
     * Verifica existencia do fabricante
     * */
   $sql = "SELECT CODIGO_FABRICANTE FROM ".$_SESSION['BASE'].".fabricante WHERE CNPJ = '$_cnpj'";

    $consultaFabricante = $pdo->query("SELECT CODIGO_FABRICANTE FROM ".$_SESSION['BASE'].".fabricante WHERE CNPJ = '$_cnpj'");
    $retornoFabricante = $consultaFabricante->fetch();
   
    if ($retornoFabricante != false) {
        $fabricanteID = $retornoFabricante["CODIGO_FABRICANTE"];
    }
    else {
      
        $insereFabricante = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".fabricante(NOME, RAZAO_SOCIAL, CNPJ, INSCR_ESTADUAL, TELEFONE, ENDERECO, BAIRRO, CIDADE, UF, CEP) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insereFabricante->bindParam(1, $_fantasia);
        $insereFabricante->bindParam(2, $_razao);
        $insereFabricante->bindParam(3, $_cnpj);
        $insereFabricante->bindParam(4, $_ie);
        $insereFabricante->bindParam(5, $_telefone);
        $insereFabricante->bindParam(6, $_endereco);
        $insereFabricante->bindParam(7, $_bairro);
        $insereFabricante->bindParam(8, $_cidade);
        $insereFabricante->bindParam(9, $_uf);
        $insereFabricante->bindParam(10, $_cep);
        $insereFabricante->execute();

        $consultaFabricante = $pdo->query("SELECT CODIGO_FABRICANTE FROM ".$_SESSION['BASE'].".fabricante WHERE CNPJ = '$_cnpj'");
        $retornoFabricante = $consultaFabricante->fetch();

        $fabricanteID = $retornoFabricante["CODIGO_FABRICANTE"];
    }

    /*
     * Verifica existência da nota
     * */
    $consultaNF = $pdo->query("SELECT NFE_DATAEMIS FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_FORNEC = '$fabricanteID' AND NFE_NRO = '$_numeronota'");
    $retornoNF = $consultaNF->fetch();

    if (!$retornoNF) {
        $data_entrada = date("Y-m-d H:m:s");
        $_parcela = 0;
        $_produto = 0;

        $insereNF = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_base (NFE_NRO, NFE_FORNEC, NFE_DATAENTR, NFE_CHAVE, NFE_TOTALNF, NFE_TOTALFRETE, NFE_TOTALICM, NFE_TOTALIPI, NFE_BASEICM, NFE_TOTALDESC, NFE_DATAEMIS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insereNF->bindParam(1,$_numeronota);
        $insereNF->bindParam(2,$fabricanteID);
        $insereNF->bindParam(3,$data_entrada);
        $insereNF->bindParam(4,$_chave);
        $insereNF->bindParam(5,$_total);
        $insereNF->bindParam(6,$_frete);
        $insereNF->bindParam(7,$_icms);
        $insereNF->bindParam(8,$_ipi);
        $insereNF->bindParam(9,$_baseicms);
        $insereNF->bindParam(10,$_desconto);
        $insereNF->bindParam(11,$_emissao);
	
        $insereNF->execute();
$S = "SELECT NFE_ID FROM ".$_SESSION['BASE'].".nota_ent_base
WHERE NFE_NRO = '$_numeronota' AND  NFE_FORNEC = '$fabricanteID' LIMIT 1";

        $consultaF = $pdo->query("$S");
        $retornoF = $consultaF->fetch();

        $NFE_IDBASE = $retornoF["NFE_ID"];

        //$NFE_IDBASE

        /*
         * Cadastro de Faturas
         * */
        foreach ($xml->NFe->infNFe->cobr->dup as $name) {
            $_numerofatura = $name->{'nFat'};
            $_dVenc = $name->{'dVenc'};
            $_valor = $name->{'vDup'};
            $_parcela++;

            $insereFatura = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_pgto (NFE_FORNEC,NFE_NRO,NFE_PARC,NFE_DATAVENC,NFE_VALOR) VALUES (?, ?, ?, ?, ?)");
            $insereFatura->bindParam(1,$fabricanteID);
            $insereFatura->bindParam(2,$_numeronota);
            $insereFatura->bindParam(3,$_parcela);
            $insereFatura->bindParam(4,$_dVenc);
            $insereFatura->bindParam(5,$_valor);
         //   $insereFatura->execute();
        }

        /*
         * Cadastro de Produtos
         * */
        foreach ($xml->NFe->infNFe->det as $name) {
 
            $_codigo = $name->{'prod'}->{'cProd'};
            $_ean = trim($name->{'prod'}->{'cEAN'});
            $_descricao = $name->{'prod'}->{'xProd'};
            $_ncm = $name->{'prod'}->{'NCM'};
            $_cfop = $name->{'prod'}->{'CFOP'};
            $_cfop == "5401" || $_cfop == "6101" ? $_class = "500" : $_class = "102";
            $_un = $name->{'prod'}->{'uCom'};
            $_qtd = $name->{'prod'}->{'qCom'};
            $_custoNf = floatval($name->{'prod'}->{'vUnCom'});
            $_ipi	= floatval($name->{'imposto'}->{'IPI'}->{'IPITrib'}->{'pIPI'});
            $_ipivl	= floatval($name->{'imposto'}->{'IPI'}->{'IPITrib'}->{'vIPI'});
            $_valorst =  floatval($name->{'imposto'}->{'ICMS'}->{'ICMS10'}->{'vICMSST'});
            $_valorst =  $_valorst + floatval($name->{'imposto'}->{'ICMS'}->{'ICMS51'}->{'vICMSST'});
            $_valorst =  $_valorst + floatval($name->{'imposto'}->{'ICMS'}->{'ICMS70'}->{'vICMSST'});
            $_valorst =  $_valorst + floatval($name->{'imposto'}->{'ICMS'}->{'ICMSSN201'}->{'vICMSST'});
            $_descontoUni = floatval($name->{'prod'}->{'vDesc'});
            $_modelo = "-";
            $_nlote =  0;
            $_qlote =  0;
            $_dFab =  0;
            $_dVal =  0;

            $_origemicms  = floatval($name->{'imposto'}->{'ICMS'}->{'ICMS00'}->{'orig'});
            $_tipoicms  = floatval($name->{'imposto'}->{'ICMS'}->{'ICMS00'}->{'pICMS'});
            $_vlricms  = floatval($name->{'imposto'}->{'ICMS'}->{'ICMS00'}->{'vICMS'});

            if($_tipoicms == ""){
                $_origemicms  = 0;
                $_tipoicms  = 0;
                $_vlricms  = 0;
            }

            if(($name->{'prod'}->{'rastro'}->{'nLote'}) != "") {
			
			 $_nlote =  ($name->{'prod'}->{'rastro'}->{'nLote'});
			 $_qlote =  ($name->{'prod'}->{'rastro'}->{'qLote'});
			 $_dFab =  ($name->{'prod'}->{'rastro'}->{'dFab'});
			 $_dVal =  ($name->{'prod'}->{'rastro'}->{'dVal'});
            }
           

             if(($name->{'prod'}->{'med'}->{'cProdANVISA'}) != "") {

             $_modelo =  "ANVISA ".($name->{'prod'}->{'med'}->{'cProdANVISA'});
             $_modelo =  $_modelo." PMC:".($name->{'prod'}->{'med'}->{'vPMC'});
            
            }
            
             $_icms   = floatval($name->{'imposto'}->{'ICMS'}->{'ICMS00'}->{'pICMS'});
			
            $ximposto = ($_ipivl+$_valorst)/$_qtd;
            $_custo = $_custoNf + $ximposto;
            $_descontoUni == 0 ?: $_custo = $_custo - ($_descontoUni/$_qtd);
            $_total = $_custo*$_qtd;
            $porcFrete = ($_custoNf*$_qtd)*100/$_totalnota;
            $freteitem = round($porcFrete)/100*$_frete;
            $freteitem = $freteitem/$_qtd;
            $_custo = $_custo + $freteitem;
            $_total = $_custo*$_qtd;
            $_produto++;

            $consultaItem = $pdo->query("SELECT CODIGO_FORNECEDOR FROM ".$_SESSION['BASE'].".itemestoque  WHERE Codigo_Barra = '$_ean' AND Codigo_Barra <> ''");
            $retornoItem = $consultaItem->fetch();

            if ($retornoItem != false) {
                $_df_produto = $retornoItem['CODIGO_FORNECEDOR'];
           $SQL = "INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_CFOP,NFE_IPI,NFE_DESCRICAO,NFE_ITEM,
           NF_CUSTO_ORIG,NF_pICMS,NF_vICMS,NF_orirgemICMS,NFE_IDBASE) VALUES ('$fabricanteID',
            '$_numeronota', '$_df_produto', '$_qtd', '$_custo', '$_total', '$_cfop', '$_ipi', '$_descricao', 
            '$_produto', '$_custoNf', '$_tipoicms', '$_vlricms', '$_origemicms','$NFE_IDBASE')";

                $insereProduto = $pdo->prepare($SQL);
               		
                $insereProduto->execute();
               
                $updateEstoque = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET PRECO_CUSTO = ?, Cod_Class_Fiscal = ? WHERE CODIGO_FORNECEDOR = ?");
                $updateEstoque->bindParam(1,$_custo);
                $updateEstoque->bindParam(2,$_ncm);
                $updateEstoque->bindParam(3,$_df_produto);
               // $updateEstoque->execute();
            }
            else {
                $consultaItem = $pdo->query("SELECT * FROM  ".$_SESSION['BASE'].".itemestoquefornecedor LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = codigo_item WHERE itemestoquefornecedor.Codigo_fornecedor = '$_codigo' AND itemestoquefornecedor.codigo_fabricante = '$fabricanteID'");
                $retornoItem = $consultaItem->fetch();

                if ($retornoItem != false) {
                   
                    $_df_produto = $retornoItem['codigo_item'];
                    $insereProduto = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_CFOP,NFE_IPI,NFE_DESCRICAO,NFE_ITEM,NF_CUSTO_ORIG,NF_pICMS,NF_vICMS,NF_orirgemICMS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)");
                    $insereProduto->bindParam(1,$fabricanteID);
                    $insereProduto->bindParam(2,$_numeronota);
                    $insereProduto->bindParam(3,$_df_produto);
                    $insereProduto->bindParam(4,$_qtd);
                    $insereProduto->bindParam(5,$_custo);
                    $insereProduto->bindParam(6,$_total);
                    $insereProduto->bindParam(7,$_cfop);
                    $insereProduto->bindParam(8,$_ipi);
                    $insereProduto->bindParam(9,$_descricao);
                    $insereProduto->bindParam(10,$_produto);
                    $insereProduto->bindParam(11,$_custoNf);
                    $insereProduto->bindParam(12,$_tipoicms);
                    $insereProduto->bindParam(13,$_vlricms);
                    $insereProduto->bindParam(14,$_origemicms);	
                    $insereProduto->execute();
					

                   /* $updateEstoque = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET PRECO_CUSTO = ?, Cod_Class_Fiscal = ?,
					item_lote_B = item_lote_A,
					item_validade_B = item_validade_A,
					item_qtde_B = item_qtde_A,
					item_qtde_A = ?,
					item_validade_A = ?,
					item_lote_A = ?
					
					WHERE CODIGO_FORNECEDOR = ?");
                    $updateEstoque->bindParam(1,$_custo);
                    $updateEstoque->bindParam(2,$_ncm);
					  $updateEstoque->bindParam(3,$_qlote);
					    $updateEstoque->bindParam(4,$_dVal);
						  $updateEstoque->bindParam(5,$_nlote);
                    $updateEstoque->bindParam(6,$_df_produto);
					
                    $updateEstoque->execute();
                    */
                }
                else {
                  
                    $consultaParametro = $pdo->query("SELECT Ult_Cod_Peca FROM ".$_SESSION['BASE'].".parametro");
                    $retornoParametro = $consultaParametro->fetch();
                    $idPeca = intval($retornoParametro["Ult_Cod_Peca"]);
                    $idPecaAtt = intval($retornoParametro["Ult_Cod_Peca"]) + 1;
                    $parametro = "-1";

                    $updateParametro = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".parametro SET Ult_Cod_Peca = :id");
                    $updateParametro->bindParam(":id", $idPecaAtt);
                    $updateParametro->execute();

                    if (empty($_ean) || strlen($_ean) != 13) {
                        if(strlen($_codigo) == 13) {
                            $_ean = $_codigo;
                        }
                        else {
                            $_ean = $idPeca;
                        }
                    }

                    $insereItem = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoque(CODIGO_FORNECEDOR, DESCRICAO, UNIDADE_MEDIDA, PRECO_CUSTO, COD_FABRICANTE, Cod_Class_Fiscal, Codigo_Barra, Descricao_Reduzida,
                    SIT_TRIBUTARIA,	item_qtde_A,item_validade_A ,item_lote_A,PERC_ICMS,MODELO_APLICADO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
                    $insereItem->bindParam(1, $idPeca);
                    $insereItem->bindParam(2, $_descricao);
                    $insereItem->bindParam(3, $_un);
                    $insereItem->bindParam(4, $_custo);
                    $insereItem->bindParam(5, $fabricanteID);
                    $insereItem->bindParam(6, $_ncm);
                    $insereItem->bindParam(7, $_ean);
                    $insereItem->bindParam(8, $_descricao);
                    $insereItem->bindParam(9, $_class);
					$insereItem->bindParam(10, $_qlote);
					$insereItem->bindParam(11, $_dVal);
					$insereItem->bindParam(12, $_nlote);
                    $insereItem->bindParam(13, $_icms);
                    $insereItem->bindParam(14, $_modelo);
              
                    $insereItem->execute();
                   
                    $data = "2023-03-31";
                    $insereItemFornec = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquefornecedor (codigo_fabricante, codigo_item, Codigo_fornecedor, valor_ult_compra, data_ult_compra) VALUES (?, ?, ?, ?, ?)");
                    $insereItemFornec->bindParam(1, $fabricanteID);
                    $insereItemFornec->bindParam(2, $idPeca);
                    $insereItemFornec->bindParam(3, $_codigo);
                    $insereItemFornec->bindParam(4, $_custo);
                    $insereItemFornec->bindParam(5, $data);
                    $insereItemFornec->execute();
              
                    $consultaItem = $pdo->query("SELECT * FROM  ".$_SESSION['BASE'].".itemestoquefornecedor LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = codigo_item WHERE itemestoquefornecedor.Codigo_fornecedor = '$_codigo' AND itemestoquefornecedor.codigo_fabricante = '$fabricanteID'");
                    $retornoItem = $consultaItem->fetch();

                    $_df_produto = $retornoItem['codigo_item'];

                    $insereProduto = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_CFOP,NFE_IPI,NFE_DESCRICAO,NFE_ITEM,
					NF_CUSTO_ORIG,NF_pICMS,NF_vICMS,NF_orirgemICMS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insereProduto->bindParam(1,$fabricanteID);
                    $insereProduto->bindParam(2,$_numeronota);
                    $insereProduto->bindParam(3,$_df_produto);
                    $insereProduto->bindParam(4,$_qtd);
                    $insereProduto->bindParam(5,$_custo);
                    $insereProduto->bindParam(6,$_total);
                    $insereProduto->bindParam(7,$_cfop);
                    $insereProduto->bindParam(8,$_ipi);
                    $insereProduto->bindParam(9,$_descricao);
                    $insereProduto->bindParam(10,$_produto);
                    $insereProduto->bindParam(11,$_custoNf);
                    $insereProduto->bindParam(12,$_tipoicms);
                    $insereProduto->bindParam(13,$_vlricms);
                    $insereProduto->bindParam(14,$_origemicms);		
                    $insereProduto->execute();

                    $consultaAlmox = $pdo->query("SELECT Codigo_Almox FROM ".$_SESSION['BASE'].".almoxarifado ORDER BY Descricao");
                    $retornoAlmox = $consultaAlmox->fetchAll();
                    $quantidade = $_qtd;

                    foreach ($retornoAlmox as $row) {
                        $insereItemAlmox = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquealmox (Codigo_Item, Codigo_Almox, Qtde_Disponivel) VALUES (?, ?, ?)");
                        $insereItemAlmox->bindParam(1, $idPeca);
                        $insereItemAlmox->bindParam(2, $row["Codigo_Almox"]);
                        $insereItemAlmox->bindParam(3, $quantidade);
                        $insereItemAlmox->execute();
                    }
                }
            }
        }
  
        ?>
     
                        <h2>Nota Cadastrada! <?= "$xmlFile";;?></h2>
 
        <?php
    }
    else {
        ?>
   
                        <h2><?=("Nota Fiscal já cadastrada ! $xmlFile")?></h2>
                 
        <?php
    }
	
	}

} catch (PDOException $e) {
   
   
}