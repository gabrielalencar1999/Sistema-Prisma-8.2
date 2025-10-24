<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();
use Functions\Estoque;

$cliente = $_SESSION['BASE_ID'];
$loginuser = $_SESSION['tecnico'];
$loginNOME = $_SESSION['APELIDO'];

$arquivo_temp	=	$_FILES["nota-xml"]["tmp_name"];	//CAMINHO TEMPORÁRIO
$arquivo_name	=	$_FILES["nota-xml"]["name"];		//NOME DO ARQUIVO
$arquivo_size	=	$_FILES["nota-xml"]["size"];		//TAMANHO DO ARQUIVO
$arquivo_type	=	$_FILES["nota-xml"]["type"];		//TIPO DO ARQUIVO

$mes = date("m");

if (is_dir("./docs/$cliente/import/$mes/")) {
    if (!copy("$arquivo_temp", "./docs/$cliente/import/$mes/$arquivo_name")) {
        $errors= error_get_last();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h1><?="COPY ERROR: ".$errors['type']?></h1>
                        <p><?=$errors['message'];?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        exit();
    }
}
else {
    if (!mkdir("./docs/$cliente/import/$mes", 0764, true)) {
        $errors= error_get_last();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h1><?="CREATE ERROR: ".$errors['type']?></h1>
                        <p><?=$errors['message'];?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        exit();
    }
    else if (!copy("$arquivo_temp", "./docs/$cliente/import/$mes/$arquivo_name")) {
        $errors= error_get_last();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h1><?="COPY ERROR: ".$errors['type']?></h1>
                        <p><?=$errors['message'];?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        exit();
    }
}

/*
No lançamento de entrada 
Cfop saída 6401 - cfop entrada 2403

Na emissão da venda :
CFOP 5405 dentro do estado - CSOSN CSOSN 500 
CFOP 6403 fora do estado PJ contribuinte - CSOSN 500
CFOP 6108 fora do estado não contribuinte - CSOSN 102

------------------------
No lançamento de entrada 
Cfop saída 6101 - cfop entrada 2102

Na emissão da venda :
CFOP 5102 dentro do estado - CSOSN 102
CFOP 6102 fora do estado PJ contribuinte - CSOSN 102
CFOP 6108 fora do estado não contribuinte - CSOSN 102
------------------------
No lançamento de entrada 
Cfop saída 5405 - cfop entrada 1403

Na emissão da venda:
CFOP 5405 dentro do estado - CSOSN 500 
CFOP 6403 fora do estado PJ contribuinte  - CSOSN 500
CFOP 6108 fora do estado não contribuinte - CSOSN 102
-------------------------
No lançamento de entrada 
Cfop saída 5102 - cfop entrada 1102

Na emissão da venda:
CFOP 5102 dentro do estado - CSOSN 102 
CFOP 6102 fora do estado PJ contribuinte - CSOSN 102 
CFOP 6108 fora do estado não contribuinte - CSOSN 10

*/
function obterCSOSN($tipo, $cfop, $empresa) { 
    // Mapeamento dos CFOPs para CSOSNs  $empresa = 1 simples nacional 
   
    switch ($tipo) {
        case 'entrada':
            if( substr($cfop,0,1) == 6 ) {
                $cfopN = "2".substr($cfop,1,3);
            }else{
                $cfopN = "1".substr($cfop,1,3);
            }
            
            switch ($cfop) {
                case 6105:
                    return 2403;
                case 6401:
                    return 2403;
                case 6403:
                        return 2403;
                case 6108:
                        return 2403;
                case 6108:
                       return 2403;        
                case 6101:
                    return 2102;
                 case 6949:
                   return 2949;    

               case 5949:
                    return 1949; 
                case 5405:
                    return 1403;
                case 5102:
                    return 1102;
                default:
                return $cfopN; //'CFOP de venda não reconhecido';
            }
            break;
         
        case ('venda' &&   $empresa == 1) :
            switch ($cfop) {
                case 6105:
                    return 102;
                case 5405:
                    return 500;               
                case 6403:
                    return 500;
                case 6108:
                    return 102;
                case 5102:
                    return 102;
                case 6102:
                    return 102;                    
                default:
                    return 102; //'CFOP de venda não reconhecido';
            }
            break;
            case ('venda' &&   $empresa != 1) :
                switch ($cfop) {
                    case 6105:
                        return 0; 
                    case 5405:
                        return 60;               
                    case 6403:
                        return 60;
                    case 6108:
                        return 00;
                    case 5102:
                        return 0;
                    case 6102:
                        return 0;
                        
                    default:
                        return 0; //'CFOP de venda não reconhecido';
                }
                break;
           case 'vendacfop':
                switch ($cfop) { 
                    case 6105:
                        return 5102;                       
                    case 5405:
                        return 5405;                
                    case 6403:
                        return 5405;
                    case 6108:
                        return 5102;
                    case 5102:
                        return 5102;
                    case 6102:
                        return 5102;
                    default:
                        return 9999; //'CFOP de venda não reconhecido';
                }
                break;
        default:
            return 'Tipo não reconhecido';
    }
}

$caminho = "./docs/$cliente/import/$mes";
$data = date("Y-m-d");
$data_hora = date("Y-m-d H:m:s");
$arquivo_caminho = "./docs/$cliente/import/$mes/$arquivo_name";

try {
    $query = $pdo->query("SELECT empresa_tipo  FROM  " . $_SESSION['BASE'] . ".empresa limit 1  ");
    $retornoItem = $query->fetch();
    $_empresatipo  = $retornoItem['empresa_tipo'];

    $query = $pdo->query("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC,UF  
    FROM  " . $_SESSION['BASE'] . ".parametro  ");
    $retornoItem = $query->fetch();

    $_vizCodInterno = $retornoItem['empresa_vizCodInt'];
    $_UFEMPRESA = $retornoItem['UF'];
  

    $tipoarquivo = 1; 
    $insertArquivo = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".arquivos_cliente(arquivo_data, arquivo_entrada, arquivo_nomearquivo, arquivo_link, arquivo_tipo, arquivo_cliente) VALUES (?, ?, ?, ?, ?, ?)");
    $insertArquivo->bindParam(1, $data);
    $insertArquivo->bindParam(2, $data_hora);
    $insertArquivo->bindParam(3, $arquivo_name);
    $insertArquivo->bindParam(4, $caminho);
    $insertArquivo->bindParam(5, $arquivo_type);
    $insertArquivo->bindParam(6, $tipoarquivo);
    $insertArquivo->execute();

    $xml = simplexml_load_file($arquivo_caminho);
    $arquivoxml= file_get_contents($arquivo_caminho);

    $_numeronota = $xml->NFe->infNFe->ide->nNF;
    $_emissao =  substr($xml->NFe->infNFe->ide->dhEmi,0,10);
    $_totalnf = $xml->NFe->infNFe->total->ICMSTot->vNF;
    $_chave  = $xml->protNFe->infProt->chNFe;
    $_cnpj = $xml->NFe->infNFe->emit->CNPJ;
    $_ie = $xml->NFe->infNFe->emit->IEST;
    $_razao = $xml->NFe->infNFe->emit->xNome;
    $_fantasia = $xml->NFe->infNFe->emit->xFant;
    if($_fantasia == "" ) {
        $_fantasia   =   $_razao;
    }

    $_infoadd = $xml->NFe->infNFe->infAdic->infCpl;

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
    $consultaFabricante = $pdo->query("SELECT CODIGO_FABRICANTE,Fabricante_CODIGO_LOGIN FROM ".$_SESSION['BASE'].".fabricante WHERE CNPJ = '$_cnpj'");
    $retornoFabricante = $consultaFabricante->fetch();
  

    if ($retornoFabricante != false) {
        $fabricanteID = $retornoFabricante["CODIGO_FABRICANTE"];
        $fabricanteCodFabr = $retornoFabricante["Fabricante_CODIGO_LOGIN"];  //Fabricante_CODIGO_LOGIN == 1  PEGA CODIGO FABRICANTE PELA DESCRICAO
    }
    else {
        $insereFabricante = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".fabricante(NOME, RAZAO_SOCIAL, CNPJ, INSCR_ESTADUAL, TELEFONE, ENDERECO, BAIRRO, CIDADE, UF, CEP, for_Tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '9')");
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

        $insereNF = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_base (NFE_NRO, NFE_FORNEC, NFE_DATAENTR, NFE_CHAVE, NFE_TOTALNF, NFE_TOTALFRETE, NFE_TOTALICM, NFE_TOTALIPI, NFE_BASEICM, NFE_TOTALDESC, NFE_DATAEMIS,NFE_INFOADD) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
        $insereNF->bindParam(12,$_infoadd);        
        $insereNF->execute();

    $consultaNF = $pdo->query("SELECT NFE_ID FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_FORNEC = '$fabricanteID' AND NFE_NRO = '$_numeronota'");
    $retornoNF = $consultaNF->fetch();
        $_NFE_ID = intval($retornoNF["NFE_ID"]);

       
        $insereXml= $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_xml(nxml_data,nxml_chave,nxml_idnotabase,nxml_xml) VALUES (CURRENT_DATE(), ?, ?, ?)");
        $insereXml->bindParam(1, $_chave);
        $insereXml->bindParam(2, $_NFE_ID);        
        $insereXml->bindParam(3, $arquivoxml,PDO::PARAM_STR);   
        $insereXml->execute();
        

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
            $insereFatura->execute();
        }

        /*
         * Cadastro de Produtos
         * */
        foreach ($xml->NFe->infNFe->det as $name) {
            $NFE_CSTCSON = "";
            $_codigo = $name->{'prod'}->{'cProd'};
            $_NF_SKU =  ltrim($_codigo,'0');
            $_ean = trim($name->{'prod'}->{'cEAN'});
            $_descricao = $name->{'prod'}->{'xProd'};
            $_ncm = $name->{'prod'}->{'NCM'};
            $_CEST = $name->{'prod'}->{'CEST'};
            $_cfop = $name->{'prod'}->{'CFOP'};
          //  $_cfop == "5401" || $_cfop == "6101"  || $_cfop == "5405"   || $_cfop == "6405"? $_class = "500" : $_class = "102";
            $_class =  obterCSOSN('venda', $_cfop,$_empresatipo );
            $_vendacfop =  obterCSOSN('vendacfop', $_cfop,$_empresatipo );
            $_CFOPENTRADA =  obterCSOSN('entrada', $_cfop,$_empresatipo );
            $_un = $name->{'prod'}->{'uCom'};
            $_qtd = $name->{'prod'}->{'qCom'};
            $_custoNf = floatval($name->{'prod'}->{'vUnCom'});
            $_xPed = $name->{'prod'}->{'xPed'};
            $_ipi	= floatval($name->{'imposto'}->{'IPI'}->{'IPITrib'}->{'pIPI'});
            $_ipivl	= floatval($name->{'imposto'}->{'IPI'}->{'IPITrib'}->{'vIPI'});
            $_ipiCST	= floatval($name->{'imposto'}->{'IPI'}->{'IPITrib'}->{'CST'});
            $_ipiBASE	= floatval($name->{'imposto'}->{'IPI'}->{'IPITrib'}->{'vBC'});


            $_pCOFINS	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSAliq'}->{'pCOFINS'});
            $_vCOFINS	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSAliq'}->{'vCOFINS'});
            $_COFINSCST	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSAliq'}->{'CST'});
            $_COFINSBASE	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSAliq'}->{'vBC'});
            if($_COFINSCST == "") {
                $_pCOFINS	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSOutr'}->{'pCOFINS'});
                $_vCOFINS	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSOutr'}->{'vCOFINS'});
                $_COFINSCST	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSOutr'}->{'CST'});
                $_COFINSBASE	= floatval($name->{'imposto'}->{'COFINS'}->{'COFINSOutr'}->{'vBC'});
            }

            $_pPIS	= floatval($name->{'imposto'}->{'PIS'}->{'PISAliq'}->{'pPIS'});
            $_vPIS	= floatval($name->{'imposto'}->{'PIS'}->{'PISAliq'}->{'vPIS'});
            $_PISCST	= floatval($name->{'imposto'}->{'PIS'}->{'PISAliq'}->{'CST'});
            $_PISBASE	= floatval($name->{'imposto'}->{'PIS'}->{'PISAliq'}->{'vBC'});
            if( $_PISCST == ""){
                $_pPIS	= floatval($name->{'imposto'}->{'PIS'}->{'PISOutr'}->{'pPIS'});
                $_vPIS	= floatval($name->{'imposto'}->{'PIS'}->{'PISOutr'}->{'vPIS'});
                $_PISCST	= floatval($name->{'imposto'}->{'PIS'}->{'PISOutr'}->{'CST'});
                $_PISBASE	= floatval($name->{'imposto'}->{'PIS'}->{'PISOutr'}->{'vBC'});
            }

       
            $x =  $x."$_codigo($_cfop)>";
           
            $_descontoUni = floatval($name->{'prod'}->{'vDesc'});
            
           // $_custoNf = floatval($name->{'prod'}->{'vUnCom'});

            $NFE_CSTCSON =  "";
            if($name->{'imposto'}->{'ICMS'}->{'ICMSSN101'}->{'CSOSN'} != "") {
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN101'}->{'CSOSN'};
                    $ICMS = 'ICMSSN101';
            }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN102'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN102'}->{'CSOSN'};
                    $ICMS = 'ICMSSN102';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN103'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN103'}->{'CSOSN'};
                    $ICMS = 'ICMSSN103';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN201'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN201'}->{'CSOSN'};
                    $ICMS = 'ICMSSN201';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN201'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN201'}->{'CSOSN'};
                    $ICMS = 'ICMSSN201';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN202'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN202'}->{'CSOSN'};
                    $ICMS = 'ICMSSN202';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN203'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN203'}->{'CSOSN'};
                    $ICMS = 'ICMSSN203';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN300'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN300'}->{'CSOSN'};
                    $ICMS = 'ICMSSN300';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN400'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN400'}->{'CSOSN'};
                    $ICMS = 'ICMSSN400';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN500'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN500'}->{'CSOSN'};
                    $ICMS = 'ICMSSN500';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMSSN900'}->{'CSOSN'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMSSN900'}->{'CSOSN'};
                    $ICMS = 'ICMSSN900';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS00'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS00'}->{'CST'};
                    $ICMS = "ICMS00";

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS10'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS10'}->{'CST'};
                    $ICMS = 'ICMS10';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS20'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS20'}->{'CST'};
                    $ICMS = 'ICMS20';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS30'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS30'}->{'CST'};
                    $ICMS = 'ICMS30';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS40'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS40'}->{'CST'};
                    $ICMS = 'ICMS40';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS41'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS41'}->{'CST'};
                    $ICMS = 'ICMS41';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS50'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS50'}->{'CST'};
                    $ICMS = 'ICMS50';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS51'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS51'}->{'CST'};
                    $ICMS = 'ICMS51';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS60'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS60'}->{'CST'};
                    $ICMS = 'ICMS60';

                }elseif ($name->{'imposto'}->{'ICMS'}->{'ICMS90'}->{'CST'} != "" ){
                    //encontrou
                    $NFE_CSTCSON  = $name->{'imposto'}->{'ICMS'}->{'ICMS90'}->{'CST'};
                    $ICMS = 'ICMS90';

            }

          
            $_indST = 0;
            $_valorst =  floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vICMSST'});
          //  $_valorst =  $_valorst + floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vICMSST'});
            //$_valorst =  $_valorst + floatval($name->{'imposto'}->{'ICMS'}->{'ICMS70'}->{'vICMSST'});
            //$_valorst =  $_valorst + floatval($name->{'imposto'}->{'ICMS'}->{'ICMSSN201'}->{'vICMSST'});

            $_origemicms  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'orig'});
            $_modBC = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'modBC'});
            $_vBICMS  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vBC'});
            $_tipoicms  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'pICMS'});
            $_vlricms  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vICMS'});

            $_modBCST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'modBCST'}); 
            $_pMVAST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'pMVAST'});
            $_pRedBCST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'pRedBCST'});
            $_vBCST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vBCST'});
            $_pICMSST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'pICMSST'});
            $_vICMSST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vICMSST'});  
            
            $_vFCTCST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vBCFCPST'});
            $_pFCTSST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'pFCPST'});
            $_vFCTSST  = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vFCPST'}); 

            $situacaoTributaria = 0;
       
            $_vBCSTRet = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vBCSTRet'}); 
            $_pST = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'pST'});  
            $_vICMSSubstituto = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vICMSSubstituto'});  
            $_vICMSSTRet = floatval($name->{'imposto'}->{'ICMS'}->{$ICMS}->{'vICMSSTRet'});  

            if( $_valorst > 1 )
            {
                //IND_SUBTTRIBUTARIA
                $_indST = 1;
            }

          
          
            if($_tipoicms == ""){
                $_origemicms  = 0;
                $_tipoicms  = 0;
                $_vlricms  = 0;
            }
            if($_UFEMPRESA == "RJ" AND $_vFCTSST > 0.00) {
                $ximposto = ($_ipivl+$_valorst+$_vFCTSST)/$_qtd;
            }else{
                $ximposto = ($_ipivl+$_valorst)/$_qtd;
                $_vFCTCST = 0;  $_pFCTSST = 0;   $_vFCTSST = 0;
            }
           
            $x =  $x."$_custoNf + $ximposto ( ($_ipivl+$_valorst)/$_qtd)";
            $_custo = $_custoNf + $ximposto;
            $_descontoUni == 0 ?: $_custo = $_custo - ($_descontoUni/$_qtd);
            $_total = $_custo*$_qtd;
            $porcFrete = ($_custoNf*$_qtd)*100/$_totalnota;
            $freteitem = round($porcFrete)/100*$_frete;
            $freteitem = $freteitem/$_qtd;
            $_custo = $_custo + $freteitem;
            $_total = $_custo*$_qtd;
            $_produto++;

            $_custoI = 0;
            $gravacusto = 0;

             //VERIFICAR TABELA CFOP VENDA PARA ATUALIZAR CUSTO NA COMPRA
            $sql_cfop = "SELECT cfopvenda FROM bd_prisma.cfop_venda WHERE cfopvenda = '".$_cfop."'";
            $stmCfop = $pdo->prepare(" $sql_cfop ");            
            $stmCfop->execute();
            

            if($_vizCodInterno == 1) {

                $_verDescricaoProdutoELX= explode(" ",trim($_descricao));  
                $_verA = $_verDescricaoProdutoELX[0];
                $_verB = $_verDescricaoProdutoELX[1];
                if($fabricanteCodFabr == 1 or "$_verA" == "$_verB"){
                    $_codigoFabricante = explode(" ",$_descricao);               
                    $_codigoFabricante = $_codigoFabricante[0];

                }else{
                    $_codigoFabricante = $_codigo;
                }
              
                
                $consultaItem = $pdo->query("SELECT CODIGO_FORNECEDOR,Tab_Preco_5,Tab_Preco_4,Tab_Preco_3,Tab_Preco_2,Tab_Preco_1,PRECO_CUSTO FROM ".$_SESSION['BASE'].".itemestoque  WHERE CODIGO_FABRICANTE = '$_codigoFabricante' AND CODIGO_FABRICANTE <> ''");
                $retornoItem = $consultaItem->fetch();

                if ($retornoItem == false) {
                  
                    $consultaItem = $pdo->query("SELECT CODIGO_FORNECEDOR,Tab_Preco_5,Tab_Preco_4,Tab_Preco_3,Tab_Preco_2,Tab_Preco_1,PRECO_CUSTO FROM ".$_SESSION['BASE'].".itemestoque  WHERE Codigo_Referencia_Fornec = '$_codigo' AND Codigo_Referencia_Fornec <> ''");
                    $retornoItem = $consultaItem->fetch();
                }
            }
            

            if ($retornoItem == false) {
              
                $consultaItem = $pdo->query("SELECT CODIGO_FORNECEDOR,Tab_Preco_5,Tab_Preco_4,Tab_Preco_3,Tab_Preco_2,Tab_Preco_1,PRECO_CUSTO FROM ".$_SESSION['BASE'].".itemestoque  WHERE Codigo_Barra = '$_ean' AND Codigo_Barra <> '' AND Codigo_Barra <> 'SEM GTIN' ");
                $retornoItem = $consultaItem->fetch();
            }
            

            if ($retornoItem != false) {
                $_df_produto = $retornoItem['CODIGO_FORNECEDOR'];
                //, '$_custoNf', '$_tipoicms', '$_vlricms', '$_origemicms',
                $insereProduto = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_CFOP,NFE_IPI,NFE_DESCRICAO,NFE_ITEM, NFE_IDBASE,NF_SKU,
                NFE_CSTCSON, NF_CUSTO_ORIG,NF_pICMS,NF_vICMS,NF_orirgemICMS,
                NF_ICMS_modBC,NF_modBCST ,NF_pMVAST ,NF_pRedBCST ,NF_vBCST ,NF_pICMSST ,NF_vICMSST,vBCSTRet,pST,vICMSSubstituto,vICMSSTRet,
                NF_IPI_vIPI,NF_IPI_CST,NF_IPI_vBC,NF_ICMS_vBC,NF_CONFIS_pCONFIS,NF_CONFIS_vCONFIS,NF_CONFIS_CST, NF_CONFIS_vBC,NF_PIS_pPIS,NF_PIS_vPIS,NF_PIS_CST,NF_PIS_vBC,NF_xPed,NFE_CFOPEntr,NF_vFCPST,NF_pFCPST,NF_vIFCPST) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? , ?, ?, ? , ?, ?, ?,? , ?, ?, ? , ?, ?, ?, ? , ?,?,?,?,?,?, ?, ?, ?,?, ?, ?, ?,?,?, ?,?,?)");
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
                $insereProduto->bindParam(11,$_NFE_ID); 
                $insereProduto->bindParam(12,$_NF_SKU); 
                $insereProduto->bindParam(13,$NFE_CSTCSON);     
                $insereProduto->bindParam(14,$_custoNf);
                $insereProduto->bindParam(15,$_tipoicms);
                $insereProduto->bindParam(16,$_vlricms);
                $insereProduto->bindParam(17,$_origemicms);     
                
                $insereProduto->bindParam(18,$_modBC); 
                $insereProduto->bindParam(19,$_modBCST); 
                $insereProduto->bindParam(20,$_pMVAST); 
                $insereProduto->bindParam(21,$_pRedBCST); 
                $insereProduto->bindParam(22,$_vBCST); 
                $insereProduto->bindParam(23,$_pICMSST); 
                $insereProduto->bindParam(24,$_vICMSST); 

                $insereProduto->bindParam(25,$_vBCSTRet); 
                $insereProduto->bindParam(26,$_pST); 
                $insereProduto->bindParam(27,$_vICMSSubstituto); 
                $insereProduto->bindParam(28,$_vICMSSTRet); 
                $insereProduto->bindParam(29,$_ipivl); 
                $insereProduto->bindParam(30,$_ipiCST); 
                $insereProduto->bindParam(31,$_ipiBASE);
                $insereProduto->bindParam(32, $_vBICMS);

                $insereProduto->bindParam(33, $_pCOFINS);
                $insereProduto->bindParam(34, $_vCOFINS);
                $insereProduto->bindParam(35, $_COFINSCST);
                $insereProduto->bindParam(36, $_COFINSBASE);

                $insereProduto->bindParam(37, $_pPIS);
                $insereProduto->bindParam(38, $_vPIS);
                $insereProduto->bindParam(39, $_PISCST);
                $insereProduto->bindParam(40, $_PISBASE);
                $insereProduto->bindParam(41, $_xPed);
                
                $insereProduto->bindParam(42, $_CFOPENTRADA);
                $insereProduto->bindParam(43, $_vFCTCST);
                $insereProduto->bindParam(44, $_pFCTSST);
                $insereProduto->bindParam(45, $_vFCTSST);

                $insereProduto->execute();
                    //codigo CEST

                if($_CEST != "") { 

                    $consultaItem = $pdo->query("SELECT tabnc_ncm FROM  bd_prisma.tab_ncmcest WHERE tabnc_ncm = '$_ncm' AND tabnct_cest = '$_CEST' limit 1");
                    $retornoItem = $consultaItem->fetch();
                    if ($retornoItem != false) {
                        $updateEstoque = $pdo->prepare("INSERT INTO bd_prisma.tab_ncmcest (tabnc_atualizacao,tabnct_cest,tabnc_ncm,tabnc_aliquota) VALUE ( CURRENT_DATE(),?,?,?)");                    
                        $updateEstoque->bindParam(1,$_CEST);
                        $updateEstoque->bindParam(2,$_ncm);
                        $updateEstoque->bindParam(3,$_pICMSST);
                        $updateEstoque->execute();

                    }else{
                        $updateEstoque = $pdo->prepare("UPDATE bd_prisma.tab_ncmcest SET tabnc_atualizacao = CURRENT_DATE,tabnct_cest = ? WHERE 	tabnc_ncm  = ?");                    
                        $updateEstoque->bindParam(1,$_CEST);
                        $updateEstoque->bindParam(2,$_ncm);
                        $updateEstoque->execute();

                    }

               
                }
                if($_ean != "") {
                    $CODBARRA = ",Codigo_Barra = '$_ean'";                  
 
                 }
                 if($_CEST != "") {
                    $CODCEST = ",cest = '$_CEST' ";    
                }
                
                 

              //  if($_cfop == "5102" OR  $_cfop == "6102" OR  $_cfop == "3102" OR  $_cfop == "5405" OR $_cfop == "6405"  OR $_cfop == "6401"  OR $_cfop == "6401" OR $_cfop == "6101" OR $_cfop == "6403" OR $_cfop == "5403" OR $_cfop == "6105" OR $_cfop == "5105" ) { 
                if ($stmCfop->rowCount() > 0 ){
                    $updateEstoque = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET PRECO_CUSTO = ?, Cod_Class_Fiscal = ? , situacaoTributaria = '$situacaoTributaria' $CODCEST $CODBARRA WHERE CODIGO_FORNECEDOR = ?");
                    $updateEstoque->bindParam(1,$_custo);
                    $updateEstoque->bindParam(2,$_ncm);                  
                    $updateEstoque->bindParam(3,$_df_produto);
                    $updateEstoque->execute();
                    $_custoI = $_custo;
                    $gravacusto = 1;
                    $x = $x."($_cfop custo $_custo)";
                }else{
                    $updateEstoque = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET Cod_Class_Fiscal = ?,  situacaoTributaria = '$situacaoTributaria' $CODCEST $CODBARRA WHERE CODIGO_FORNECEDOR = ?");
                    
                    $updateEstoque->bindParam(1,$_ncm);              
                    $updateEstoque->bindParam(2,$_df_produto);
                    $updateEstoque->execute();
                    $x = $x."($_cfop)";
                }
               
              
                $consultaItem = $pdo->query("SELECT * FROM  ".$_SESSION['BASE'].".itemestoquefornecedor LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = codigo_item WHERE itemestoquefornecedor.Codigo_fornecedor = '$_codigo' AND itemestoquefornecedor.codigo_fabricante = '$fabricanteID'");
                $retornoItem = $consultaItem->fetch();

                if ($retornoItem == false) {
                    //if($_cfop == "5102" OR  $_cfop == "6102" OR  $_cfop == "3102" OR  $_cfop == "5405" OR $_cfop == "6405"  OR $_cfop == "6401"  OR $_cfop == "6401" OR $_cfop == "6101" OR $_cfop == "6403" OR $_cfop == "5403" OR $_cfop == "6105" OR $_cfop == "5105") { 
                     if ($stmCfop->rowCount() > 0 ){
                        $_custoI = $_custo;
                        $gravacusto = 1;
                        $insereItemFornec = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquefornecedor (codigo_fabricante, codigo_item, Codigo_fornecedor, valor_ult_compra, data_ult_compra) VALUES (?, ?, ?, ?, ?)");
                        $insereItemFornec->bindParam(1, $fabricanteID);
                        $insereItemFornec->bindParam(2, $_df_produto);
                        $insereItemFornec->bindParam(3, $_codigo);
                        $insereItemFornec->bindParam(4, $_custoI);
                        $insereItemFornec->bindParam(5, $data);
                        $insereItemFornec->execute();
                      
                    }else{ 
                        $_custoI = 0;
                        $insereItemFornec = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquefornecedor (codigo_fabricante, codigo_item, Codigo_fornecedor, valor_ult_compra, data_ult_compra) VALUES (?, ?, ?, ?, ?)");
                        $insereItemFornec->bindParam(1, $fabricanteID);
                        $insereItemFornec->bindParam(2, $_df_produto);
                        $insereItemFornec->bindParam(3, $_codigo);
                        $insereItemFornec->bindParam(4, $_custoI);
                        $insereItemFornec->bindParam(5, $data);
                        $insereItemFornec->execute();
                      

                    }
                  

                }
                if($gravacusto == 1 ) {

           if($_df_produto == ""){
            $_df_produto = 0;
           }
                    //historico
                    $_parametros2 = array(                
                       'tipo' =>'4',
                       'login' =>$loginuser,
                       'loginnome' => $loginNOME,
                       'codigoitem' =>$_df_produto,
                       'vlrcustoatual' =>$_custoI,
                       'vlrvendaatual' =>0,
                       'vendaAnterior' =>$retornoItem["Tab_Preco_5"],
                       'custoAnterior' =>$retornoItem["PRECO_CUSTO"],
                       'vlrcustoTab1'=>0,                 
                       'vlrcustoTab2'=>0, 
                       'vlrcustoTab3'=>0, 
                       'vlrcustoTab4'=>0, 
                       'vlrcustoAnTab1'=>$retornoItem["Tab_Preco_1"], 
                       'vlrcustoAnTab2'=>$retornoItem["Tab_Preco_2"], 
                       'vlrcustoAnTab3'=>$retornoItem["Tab_Preco_3"], 
                       'vlrcustoAnTab4'=>$retornoItem["Tab_Preco_4"] 
                   );
                 //  $_parametros2 =  array_merge($_parametros2);
                   $ret =  Estoque::gravarAlteracaoPrecoCadastro($_parametros2);
                 }
                 $x = $x."<br>".$_custoI.'primeirafase  ';
            }
            else {
                $x = $x."<br>".$_custoI.'segundafase  ';
                $consultaItem = $pdo->query("SELECT * FROM  ".$_SESSION['BASE'].".itemestoquefornecedor LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = codigo_item WHERE itemestoquefornecedor.Codigo_fornecedor = '$_codigo' AND itemestoquefornecedor.codigo_fabricante = '$fabricanteID'");
                $retornoItem = $consultaItem->fetch();

                if ($retornoItem != false) {
                    $_df_produto = $retornoItem['codigo_item'];                   
                    $insereProduto = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_CFOP,NFE_IPI,NFE_DESCRICAO,NFE_ITEM,NFE_IDBASE,NF_SKU,NFE_CSTCSON, NF_CUSTO_ORIG,NF_pICMS,NF_vICMS,NF_orirgemICMS,
                    NF_ICMS_modBC,NF_modBCST ,NF_pMVAST ,NF_pRedBCST ,NF_vBCST ,NF_pICMSST ,NF_vICMSST,vBCSTRet,pST,vICMSSubstituto,vICMSSTRet,
                    NF_IPI_vIPI,NF_IPI_CST,NF_IPI_vBC,NF_ICMS_vBC,NF_CONFIS_pCONFIS,NF_CONFIS_vCONFIS,NF_CONFIS_CST, NF_CONFIS_vBC,NF_PIS_pPIS,NF_PIS_vPIS,NF_PIS_CST,NF_PIS_vBC,NFE_CFOPEntr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?,?,?,?,?, ?, ?,? , ?, ?, ? , ?, ?, ?, ? , ?,?,?,?,?,?, ?, ?, ?,?, ?, ?, ?,? )");
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
                    $insereProduto->bindParam(11,$_NFE_ID);
                    $insereProduto->bindParam(12,$_NF_SKU); 
                    $insereProduto->bindParam(13,$NFE_CSTCSON); 
                    $insereProduto->bindParam(14,$_custoNf);
                    $insereProduto->bindParam(15,$_tipoicms);
                    $insereProduto->bindParam(16,$_vlricms);
                    $insereProduto->bindParam(17,$_origemicms);  
                    
                    $insereProduto->bindParam(18,$_modBC); 
                    $insereProduto->bindParam(19,$_modBCST); 
                    $insereProduto->bindParam(20,$_pMVAST); 
                    $insereProduto->bindParam(21,$_pRedBCST); 
                    $insereProduto->bindParam(22,$_vBCST); 
                    $insereProduto->bindParam(23,$_pICMSST); 
                    $insereProduto->bindParam(24,$_vICMSST);

                    $insereProduto->bindParam(25,$_vBCSTRet); 
                    $insereProduto->bindParam(26,$_pST); 
                    $insereProduto->bindParam(27,$_vICMSSubstituto); 
                    $insereProduto->bindParam(28,$_vICMSSTRet); 
                    $insereProduto->bindParam(29,$_ipivl); 
                    $insereProduto->bindParam(30,$_ipiCST); 
                    $insereProduto->bindParam(31,$_ipiBASE);
                    $insereProduto->bindParam(32, $_vBICMS);

                    $insereProduto->bindParam(33, $_pCOFINS);
                    $insereProduto->bindParam(34, $_vCOFINS);
                    $insereProduto->bindParam(35, $_COFINSCST);
                    $insereProduto->bindParam(36, $_COFINSBASE);

                    $insereProduto->bindParam(37, $_pPIS);
                    $insereProduto->bindParam(38, $_vPIS);
                    $insereProduto->bindParam(39, $_PISCST);
                    $insereProduto->bindParam(40, $_PISBASE);
                    $insereProduto->bindParam(41, $_CFOPENTRADA);
                    $insereProduto->execute();

                    if($_ean != "") {
                        $CODBARRA = ",Codigo_Barra = '$_ean'";                  
     
                     }
                    if($_CEST != "") {
                        $CODCEST = ",cest = '$_CEST' ";    
                    }
                    
                    $updateEstoque = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoque SET PRECO_CUSTO = ?, Cod_Class_Fiscal = ? , situacaoTributaria = '$situacaoTributaria' $CODCEST $CODBARRA  WHERE CODIGO_FORNECEDOR = ?");
                    $updateEstoque->bindParam(1,$_custoI);
                    $updateEstoque->bindParam(2,$_ncm);                 
                    $updateEstoque->bindParam(3,$_df_produto);
                    $updateEstoque->execute();

                    if($gravacusto == 1 ) {
                        $insereItemFornec = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".itemestoquefornecedor (codigo_fabricante= ?, codigo_item = ?, Codigo_fornecedor = ?, valor_ult_compra = ?, data_ult_compra = ?) ");
                        $insereItemFornec->bindParam(1, $fabricanteID);
                        $insereItemFornec->bindParam(2, $_df_produto);
                        $insereItemFornec->bindParam(3, $_codigo);
                        $insereItemFornec->bindParam(4, $_custoI);
                        $insereItemFornec->bindParam(5, $data);
                        $insereItemFornec->execute();
                    }
                  
             
             
                  

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

                   // if($_cfop == "5102" OR  $_cfop == "6102" OR  $_cfop == "3102" OR  $_cfop == "5405" OR $_cfop == "6405"  OR $_cfop == "6401"   OR $_cfop == "6401" OR $_cfop == "6101" OR $_cfop == "6403" OR $_cfop == "5403" OR $_cfop == "6105"  OR $_cfop == "5105") { 
                    if ($stmCfop->rowCount() > 0 ){
                        $_custoI = $_custo;
                        $gravacusto = 1;
                    }else{
                        $_custoI = 0;
                    }

                //    $X .= " TIPOEMPRESA:$_empresatipo |$_vendacfop | $_indST <bR>";
                        if( $_indST == 1)  {
                            $_vendacfop = '5405';
                            if( $_empresatipo != 1) {
                                $_class =  60;
                            }else{
                                $_class = 500;
                            }
                         
                        } else{
                            $_vendacfop = '5102';
                            if($_empresatipo != 1) {
                                $_class = 0;
                            }else{
                                $_class = 102;
                            }
                        }  

                        $_FILCOL = ", CFOPD,PERC_ICMS";
                        $_FILVAL = ",'".$_vendacfop."','".$_tipoicms."'";      
                      
                    //    $X .= "| $_class  <br> ";
                    
                       if($_CEST != "") { 
                         $_FILCOLCEST = ",cest";
                         $_FILVALCEST = ",'".$_CEST."'";     
                    }

                    $insereItem = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoque(CODIGO_FORNECEDOR, DESCRICAO, UNIDADE_MEDIDA, PRECO_CUSTO, COD_FABRICANTE, Cod_Class_Fiscal, Codigo_Barra, Descricao_Reduzida,ind_inclusao1,ind_inclusao2,ind_inclusao3,SIT_TRIBUTARIA,Codigo_Referencia_Fornec,CODIGO_FABRICANTE,IND_SUBTTRIBUTARIA,PERC_ICMSST,PERC_ICMSSTRET,VLR_SUBSTITUTO,MVA,origemnf,PERC_IPI  $_FILCOL  $_FILCOLCEST) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? $_FILVAL $_FILVALCEST)");
                    $insereItem->bindParam(1, $idPeca);
                    $insereItem->bindParam(2, $_descricao);
                    $insereItem->bindParam(3, $_un);
                    $insereItem->bindParam(4, $_custoI);
                    $insereItem->bindParam(5, $fabricanteID);
                    $insereItem->bindParam(6, $_ncm);
                    $insereItem->bindParam(7, $_ean);
                    $insereItem->bindParam(8, $_descricao);
                    $insereItem->bindParam(9, $parametro);
                    $insereItem->bindParam(10, $parametro);
                    $insereItem->bindParam(11, $parametro);
                    $insereItem->bindParam(12, $_class);
                    $insereItem->bindParam(13, $_codigo);
                    $insereItem->bindParam(14, $_codigoFabricante);    
                    $insereItem->bindParam(15, $_indST);  
                    $insereItem->bindParam(16, $_pICMSST);
                    $insereItem->bindParam(17, $_pRedBCST);
                    $insereItem->bindParam(18, $_vICMSSubstituto);
                    $insereItem->bindParam(19, $_pMVAST);
                    $insereItem->bindParam(20, $_origemicms);
                    $insereItem->bindParam(21, $_ipi);
                 // ($fabricanteID)-$idPeca-$_codigo-$_custoI."xx";
                 
                  
                    $insereItem->execute();
               
                    if($gravacusto == 1 ) {
                         $insereItemFornec = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquefornecedor (codigo_fabricante, codigo_item, Codigo_fornecedor, valor_ult_compra, data_ult_compra) VALUES (?, ?, ?, ?, ?)");
                        $insereItemFornec->bindParam(1, $fabricanteID);
                        $insereItemFornec->bindParam(2, $idPeca);
                        $insereItemFornec->bindParam(3, $_codigo);
                        $insereItemFornec->bindParam(4, $_custoI);
                        $insereItemFornec->bindParam(5, $data);
                      $insereItemFornec->execute();
                    }else{
                        $_custoZero =0;
                        $insereItemFornec = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquefornecedor (codigo_fabricante, codigo_item, Codigo_fornecedor, valor_ult_compra, data_ult_compra) VALUES (?, ?, ?, ?, ?)");
                        $insereItemFornec->bindParam(1, $fabricanteID);
                        $insereItemFornec->bindParam(2, $idPeca);
                        $insereItemFornec->bindParam(3, $_codigo);
                        $insereItemFornec->bindParam(4, $_custoZero);
                        $insereItemFornec->bindParam(5, $data);
                        $insereItemFornec->execute();
                    }

                  
                    $consultaItem = $pdo->query("SELECT * FROM  ".$_SESSION['BASE'].".itemestoquefornecedor LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON itemestoque.CODIGO_FORNECEDOR = codigo_item WHERE itemestoquefornecedor.Codigo_fornecedor = '$_codigo' AND itemestoquefornecedor.codigo_fabricante = '$fabricanteID'");
                    $retornoItem = $consultaItem->fetch();                    
                    $_df_produto = $retornoItem['codigo_item'];
                   

                    $insereProduto = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".nota_ent_item (NFE_FORNEC,NFE_NRO,NFE_CODIGO,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NFE_CFOP,NFE_IPI,NFE_DESCRICAO,NFE_ITEM,NFE_IDBASE,NF_SKU,NFE_CSTCSON, NF_CUSTO_ORIG,NF_pICMS,NF_vICMS,NF_orirgemICMS,
                    NF_ICMS_modBC,NF_modBCST ,NF_pMVAST ,NF_pRedBCST ,NF_vBCST ,NF_pICMSST ,NF_vICMSST,vBCSTRet,pST,vICMSSubstituto,vICMSSTRet,
                    NF_IPI_vIPI,NF_IPI_CST,NF_IPI_vBC,NF_ICMS_vBC,NF_CONFIS_pCONFIS,NF_CONFIS_vCONFIS,NF_CONFIS_CST, NF_CONFIS_vBC,NF_PIS_pPIS,NF_PIS_vPIS,NF_PIS_CST,NF_PIS_vBC,NFE_CFOPEntr) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?, ?, ? ,?, ?, ?, ?,? , ?, ?, ? , ?, ?, ?, ? , ?,?,?,?,?,?, ?, ?, ?,?, ?, ?, ?, ?)");
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
                    $insereProduto->bindParam(11,$_NFE_ID);
                    $insereProduto->bindParam(12,$_NF_SKU); 
                    $insereProduto->bindParam(13,$NFE_CSTCSON); 
                    $insereProduto->bindParam(14,$_custoNf);
                    $insereProduto->bindParam(15,$_tipoicms);
                    $insereProduto->bindParam(16,$_vlricms);
                    $insereProduto->bindParam(17,$_origemicms);    
                    
                    $insereProduto->bindParam(18,$_modBC); 
                    $insereProduto->bindParam(19,$_modBCST); 
                    $insereProduto->bindParam(20,$_pMVAST); 
                    $insereProduto->bindParam(21,$_pRedBCST); 
                    $insereProduto->bindParam(22,$_vBCST); 
                    $insereProduto->bindParam(23,$_pICMSST); 
                    $insereProduto->bindParam(24,$_vICMSST); 

                    $insereProduto->bindParam(25,$_vBCSTRet); 
                    $insereProduto->bindParam(26,$_pST); 
                    $insereProduto->bindParam(27,$_vICMSSubstituto); 
                    $insereProduto->bindParam(28,$_vICMSSTRet); 
                    $insereProduto->bindParam(29,$_ipivl); 
                    $insereProduto->bindParam(30,$_ipiCST); 
                    $insereProduto->bindParam(31,$_ipiBASE);
                    $insereProduto->bindParam(32, $_vBICMS);

                    $insereProduto->bindParam(33, $_pCOFINS);
                    $insereProduto->bindParam(34, $_vCOFINS);
                    $insereProduto->bindParam(35, $_COFINSCST);
                    $insereProduto->bindParam(36, $_COFINSBASE);

                    $insereProduto->bindParam(37, $_pPIS);
                    $insereProduto->bindParam(38, $_vPIS);
                    $insereProduto->bindParam(39, $_PISCST);
                    $insereProduto->bindParam(40, $_PISBASE);
                    $insereProduto->bindParam(41, $_CFOPENTRADA);

                    $insereProduto->execute();

            //inclui almoxarifado matriz
                        $insereItemAlmox = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".itemestoquealmox (Codigo_Item, Codigo_Almox) VALUES (?, 1)");
                        $insereItemAlmox->bindParam(1, $_df_produto);
                        $insereItemAlmox->execute();
                    
                }
                if($gravacusto == 1 ) {
                    if($_df_produto == ""){
                        $_df_produto = 0;
                       }
           
                    //historico
                    $_parametros2 = array(                
                       'tipo' =>'4',
                       'login' =>$loginuser,
                       'loginnome' => $loginNOME,
                       'codigoitem' =>$_df_produto,
                       'vlrcustoatual' =>$_custoI,
                       'vlrvendaatual' =>0,
                       'vendaAnterior' =>$retornoItem["Tab_Preco_5"],
                       'custoAnterior' =>$retornoItem["PRECO_CUSTO"],
                       'vlrcustoTab1'=>0,                 
                       'vlrcustoTab2'=>0, 
                       'vlrcustoTab3'=>0, 
                       'vlrcustoTab4'=>0, 
                       'vlrcustoAnTab1'=>$retornoItem["Tab_Preco_1"], 
                       'vlrcustoAnTab2'=>$retornoItem["Tab_Preco_2"], 
                       'vlrcustoAnTab3'=>$retornoItem["Tab_Preco_3"], 
                       'vlrcustoAnTab4'=>$retornoItem["Tab_Preco_4"] 
                   );
                  // $_parametros2 =  array_merge($_parametros2); insert
                   $ret =  Estoque::gravarAlteracaoPrecoCadastro($_parametros2);
                 }
            }

          
        }

        //update cfop
        $consultacfop = $pdo->query("SELECT ID FROM ".$_SESSION['BASE'].".cfop WHERE NAT_CODIGO = '$_cfop'");
        $retornocfop = $consultacfop->fetch();
        
        $consultaNF = $pdo->query("UPDATE  ".$_SESSION['BASE'].".nota_ent_base SET NFE_NATOPER = '".$retornocfop['ID']."'  WHERE NFE_ID = '".$_NFE_ID."'");
        $retornoNF = $consultaNF->fetch();
           
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Nota Cadastrada! </h2>
                        <button class="btn btn-success waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_alterar(<?=$_numeronota?>, <?=$fabricanteID?>)">Ir para Cadastro</button>
                        <input type="hidden" id="retorno-nota" name="retorno-nota" value="true">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2><?=("Nota Fiscal já cadastrada !")?></h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }

} catch (PDOException $e) {
    ?>
    <div class="modal-content text-center">
        <div class="modal-body" id="imagem-carregando">
            <div class="bg-icon pull-request">
                <i class="fa fa-5x fa-check-circle-o"></i>
                <h1>Erro: <?=$e->getMessage()?></h1>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
    <?php
}
