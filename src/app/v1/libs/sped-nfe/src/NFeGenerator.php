<?php

namespace NFePHP\NFe;

require_once "../../../api/config/config.inc.php";
require  FILE_BASE_API."/autoload/autoload.inc.php";

//use NFePHP\NFe\Make;
use Database\MySQL;
use PDO;
use PDOException;
use stdClass;

class NFeGenerator {

    /**
     * @var static
     */
    protected $std;
    /**
     * @var object
     */
    protected $nfe;
    /**
     * @var array
     */
    protected $config;

    public function xml65Generate(String $numPedido, String $caixa): String
    {
        try {
            date_default_timezone_set('America/Sao_Paulo');

            $nfe = new Make;
            $pdo = MySQL::acessabd();
    
            $stm = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa");
            $empresa = $stm->fetch(PDO::FETCH_OBJ);
    
            $stm = $pdo->query("SELECT cpfcnpj FROM ".$_SESSION['BASE'].".saidaestoque WHERE NUMERO = '$numPedido' AND num_livro = '$caixa'");
            $pedido = $stm->fetch(PDO::FETCH_OBJ);
    
            $stm = $pdo->query("SELECT saida.CODIGO_ITEM, saida.QUANTIDADE, saida.DESCRICAO_ITEM, saida.Valor_unitario_desc, item.Codigo_Barra, item.Cod_Class_Fiscal, item.UNIDADE_MEDIDA, imp.impostonacional 
                FROM ".$_SESSION['BASE'].".saidaestoqueitem saida LEFT JOIN ".$_SESSION['BASE'].".itemestoque item ON saida.CODIGO_ITEM = item.CODIGO_FORNECEDOR LEFT JOIN minhaos_cep.impostost imp ON item.Cod_Class_Fiscal = imp.codigoncm 
                WHERE saida.NUMERO = '$numPedido' AND saida.num_livro = '$caixa'");
            $items = $stm->fetchAll(PDO::FETCH_OBJ);

            $stm = $pdo->query("SELECT spgto_valor, Tipo FROM ".$_SESSION['BASE'].".saidaestoquepgto LEFT JOIN ".$_SESSION['BASE'].".tiporecebimpgto ON id = Tipo WHERE spgto_numpedido = '$numPedido'");
            $pagamentos = $stm->fetchAll(PDO::FETCH_OBJ);
    
            $std = new stdClass;
            $std->versao = '4.00';
            $std->pk_nItem = null;
            $nfe->taginfNFe($std);
    
            $std = new stdClass;
            $std->cUF = 35;
            $std->cNF = strlen($numPedido) < 8 ? str_pad($numPedido , (8 - strlen($numPedido)) , '0' , STR_PAD_LEFT) : $numPedido;
            $std->natOp = 'VENDA CONSUMIDOR';
            $std->mod = 65;
            $std->serie = $empresa->empresa_serie;
            $std->nNF = $empresa->empresa_nf;
            $std->dhEmi = date("Y-m-d\TH:i:sP");
            $std->dhSaiEnt = date("Y-m-d\TH:i:sP");
            $std->tpNF = 1;
            $std->idDest = 1;
            $std->tpImp = 4;
            $std->tpEmis = 1;
            $std->cDV = 4;
            $std->cMunFG = $empresa->empresa_codmunicipio;
            $std->tpAmb = 1;
            $std->finNFe = 1;
            $std->indFinal = 1;
            $std->indPres = 1;
            $std->indIntermed = null;
            $std->procEmi = 0;
            $std->verProc = '1';
            $std->dhCont = null;
            $std->xJust = null;
            $nfe->tagide($std);
    
            $std = new stdClass;
            $std->xNome = $empresa->empresa_razaosocial;
            $std->xFant = $empresa->empresa_nome;
            $std->IE =  $empresa->empresa_inscricao;
            $std->CRT = $empresa->empresa_tipo;
            $std->CNPJ = $empresa->empresa_cnpj;
            $nfe->tagemit($std);
    
            $std = new stdClass;
            $std->xLgr = $empresa->empresa_endereco;
            $std->nro = $empresa->empresa_numero;
            $std->xCpl = $empresa->empresa_complemento;
            $std->xBairro = $empresa->empresa_bairro;
            $std->cMun = $empresa->empresa_codmunicipio;
            $std->xMun = $empresa->empresa_cidade;
            $std->UF = $empresa->empresa_uf;
            $std->CEP = $empresa->CEP;
            $std->cPais = '1058';
            $std->xPais = 'Brasil';
            $std->fone = $empresa->empresa_telefone;
            $nfe->tagenderEmit($std);
    
            if (!empty($pedido->cpfcnpj)) {
                $std = new stdClass;
                $std->indIEDest = 9;
                $pedido->cpfcnpj = preg_replace('/[^0-9]/', '', $pedido->cpfcnpj);
                strlen($pedido->cpfcnpj) > 11 ? $std->CNPJ = $pedido->cpfcnpj : $std->CPF = $pedido->cpfcnpj;
        
                $nfe->tagdest($std);
            }
    
            $i = 1;
            $totalItems = 0;
            $totalTrib = 0;
            foreach ($items as $item) {
    
                $std = new stdClass;
                $std->item = $i;
                $std->cProd = $item->CODIGO_ITEM;
                $std->cEAN = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->xProd = $item->DESCRICAO_ITEM;
                $std->NCM = $item->Cod_Class_Fiscal;
                $std->CFOP = 5102;
                $std->uCom = $item->UNIDADE_MEDIDA;
                $std->qCom = number_format($item->QUANTIDADE, 4, '.', '');
                $std->vUnCom = $item->Valor_unitario_desc;
                $std->vProd = $item->Valor_unitario_desc;
                $std->cEANTrib = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->uTrib = $item->UNIDADE_MEDIDA;
                $std->qTrib = number_format($item->QUANTIDADE, 4, '.', '');
                $std->vUnTrib = $item->Valor_unitario_desc;
                $std->indTot = 1;
                $nfe->tagprod($std);
    
                $std = new stdClass();
                $std->item = $i;
                $std->vTotTrib = number_format(($item->QUANTIDADE * ($item->impostonacional / 100 * $item->Valor_unitario_desc)), 2, '.', '');
                $nfe->tagimposto($std);
    
                $std = new stdClass();
                $std->item = $i;
                $std->orig = 0;
                $std->CSOSN = $empresa->empresa_tipo != 3 ? '102' : '400';
                $nfe->tagICMSSN($std);
    
                $totalItems = $totalItems + ($item->QUANTIDADE * $item->Valor_unitario_desc);
                $totalTrib = $totalTrib + ($item->QUANTIDADE * ($item->impostonacional / 100 * $item->Valor_unitario_desc));
                $i++;
            }
    
            $std = new stdClass;
            $std->vBC = 0;
            $std->vICMS = 0;
            $std->vICMSDeson = 0;
            $std->vFCPUFDest = 0;
            $std->vICMSUFDest = 0;
            $std->vICMSUFRemet = 0;
            $std->vFCP = 0;
            $std->vBCST = 0;
            $std->vFCPST = 0;
            $std->vFCPSTRet = 0;
            $std->vProd = number_format($totalItems, 2, '.', '');
            $std->vFrete = 0;
            $std->vSeg = null;
            $std->vDesc = null;
            $std->vII = 0;
            $std->vIPI = 0;
            $std->vIPIDevol = 0;
            $std->vPIS = 0;
            $std->vCOFINS = 0;
            $std->vOutro = null;
            $std->vNF = number_format($totalItems, 2, '.', '');
            $std->vTotTrib = number_format($totalTrib, 2, '.', '');
            $nfe->tagicmstot($std);

            $std = new stdClass;
            $std->modFrete = 9;
            $nfe->tagtransp($std);

            $std = new stdClass;
            $std->vTroco = 0;
            $nfe->tagpag($std);

            foreach ($pagamentos as $pagamento) {
                $std = new stdClass;
                $std->tPag = "$pagamento->Tipo";
                $std->vPag = number_format($pagamento->spgto_valor, 2, '.', '');
                $nfe->tagdetpag($std);
            }

            
    
            $std = new stdClass;
            $std->infAdFisco = '';
            $std->infCpl = '';
            $nfe->taginfadic($std);

            $std = new stdClass();
            $std->CNPJ = '11493284000111';
            $std->xContato = 'Sistema Prisma';
            $std->email = 'contato@sistemaprisma.com.br';
            $std->fone = '4131544325';
            $std->CSRT = 'c2ymfa80CP9GHGiwlBBVVU5yd0Y=';
            $std->idCSRT = '01';
            $nfe->taginfRespTec($std);
    
            $nfe->monta();

            return $nfe->getXML();

        } catch (\Exception $e) {

            return $e->getMessage();

        }  
    }

    public function signXML(String $xml)
    {
        $pdo = MySQL::acessabd();

        $stm = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa");
        $empresa = $stm->fetch(PDO::FETCH_OBJ);

        $config = [
            "atualizacao" => "2021-08-02 06:01:21",
            "tpAmb" => 1,
            "razaosocial" => $empresa->empresa_razaosocial,
            "siglaUF" => $empresa->empresa_uf,
            "cnpj" => $empresa->empresa_cnpj,
            "schemes" => "PL_008i2",
            "versao" => "4.00",
            "tokenIBPT" => "AAAAAAA",
            "CSC" => $empresa->empresa_csc,
            "CSCid" => $empresa->empresa_cscid
        ];

        $config = json_encode($config);

        
    }
}