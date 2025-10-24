<?php

namespace Functions;

use Database\MySQL;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

use PDO;
use PDOException;
use Exception;
use stdClass;

class NFeService {

    /**
     * @var string
     */
    private $config;
    /**
     * @var object
     */
    private $pdo;
    /**
     * @var object
     */
    private $empresa;
    /**
     * @var object
     */
    private $tools;

    public function __construct(Int $empresa, Int $modelo)
    {
        $this->pdo = MySQL::acessabd();
    
        try {
            if($empresa == "") {
                $empresa = 1;
            }
          //  $stm = $this->pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa_cadastro e LEFT JOIN empresa_dados d ON e.id = d.id LEFT JOIN minhaos_cep.cidade c ON e.uf = c.estado WHERE e.id = '$empresa'");
          $stm = $this->pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa as e
                                    WHERE e.empresa_id = '$empresa'");
            $empresa = $stm->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao consultar dados: " . $e->getMessage());
        }

        $config = [
            "atualizacao" => $empresa->updated_at,
            "tpAmb" => 1,
            "razaosocial" => $empresa->empresa_razaosocial,
            "siglaUF" => $empresa->empresa_uf,
            "cnpj" => $empresa->empresa_cnpj,
            "schemes" => "PL_009_V4",
            "versao" => "4.00",
            "CSC" => $empresa->csc_nfce_producao,
            "CSCid" => $empresa->id_token_nfce_producao
        ];

        //alterado PL_008i2 para PL_009_V4
        //  "schemes" => "PL_009_V4",

        $configJson = json_encode($config);
        //$tools->soap->httpVersion('1.1');
        $tools = new Tools($configJson, Certificate::readPfx(base64_decode($empresa->arquivo_certificado_base64), $empresa->senha_certificado));
        $soapCurl = new SoapCurl();
        $tools->loadSoapClass($soapCurl);
        $tools->soap->httpVersion('1.1');
        $tools->model($modelo);       
    
       

        $this->config = $configJson;
        $this->empresa = $empresa;
      
        $this->tools = $tools;


    }
//***************************************************************************************************************************************************************** */
    public function gerarNFCeOS($numeroNFCe,$numPedido,$_CPFCNPJ,$descontopecas,$_IDCLIENTE,$_OBS)
    {
        /**
         * Consulta informações no banco
         */
     
        try {
            if($_IDCLIENTE > 0) {
                //busca dados consumidor
                $stm = $this->pdo->query("SELECT Nome_Consumidor,CIDADE,BAIRRO,Nome_Rua,CEP,UF,Num_Rua	 FROM ".$_SESSION['BASE'].".consumidor WHERE CODIGO_CONSUMIDOR = '$_IDCLIENTE'  ");
                $consumidor = $stm->fetch(PDO::FETCH_OBJ);
                        $c_nomecliente = trim($consumidor->Nome_Consumidor);
                        $c_endereco = trim($consumidor->Nome_Rua);
                        $c_numrua= trim($consumidor->Num_Rua);
                        $c_bairro = trim($consumidor->BAIRRO);
                        $c_cidade = trim($consumidor->CIDADE);      
                        $c_cep = trim($consumidor->CEP);       
                        $c_uf = trim($consumidor->UF);    
                        
                        //buscar codigo municipio 
                        $_s = "Select cod_cidade,cod_uf  from minhaos_cep.cidade  where cidade  = '".$c_cidade."' and estado  = '". $c_uf."'";
                        $stm = $this->pdo->query("$_s");         
                        $codm = $stm->fetch(PDO::FETCH_OBJ);
                        $codigo_municipio =  $codm->cod_cidade;
                            

            }
         
         
            $stm = $this->pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as pecas	 FROM ".$_SESSION['BASE'].".chamadapeca WHERE Numero_OS = '$numPedido' and TIPO_LANCAMENTO = 0 ");
            $pedido = $stm->fetch(PDO::FETCH_OBJ);
            $totalPedido =  $pedido->pecas-$descontopecas;
            $pedido->cpfcnpj = $_CPFCNPJ;
            $totalFrete =  0;
            $totalDesconto =  $descontopecas;
    
          
            $stm = $this->pdo->query("SELECT saida.Codigo_Peca_OS as CODIGO_ITEM, saida.Qtde_peca AS QUANTIDADE, saida.Minha_Descricao as DESCRICAO_ITEM, saida.Valor_Peca as Valor_unitario_desc, item.Codigo_Barra, item.Cod_Class_Fiscal, item.UNIDADE_MEDIDA, item.SIT_TRIBUTARIA, imp.impostonacional, CFOPD  
            FROM ".$_SESSION['BASE'].".chamadapeca saida LEFT JOIN ".$_SESSION['BASE'].".itemestoque item ON saida.Codigo_Peca_OS = item.CODIGO_FORNECEDOR LEFT JOIN minhaos_cep.impostost imp ON item.Cod_Class_Fiscal = imp.codigoncm 
            WHERE saida.Numero_OS = '$numPedido' and saida.TIPO_LANCAMENTO = '0'  ");
            $items = $stm->fetchAll(PDO::FETCH_OBJ);
            $stm->execute();
          

            $xdesconto_vlr = $totalDesconto/ $stm->rowCount() ;
          //  $xdesconto_vlr = $totalDesconto;
            $stm = $this->pdo->query("SELECT  Tipo FROM ".$_SESSION['BASE'].".pagamentos LEFT JOIN ".$_SESSION['BASE'].".tiporecebimpgto ON pgto_tipopagamento = id WHERE pgto_documento = '$numPedido'  limit 1");
            $pagamentos = $stm->fetchAll(PDO::FETCH_OBJ);

          
            foreach ($pagamentos as $pagamento) {
              $tipopgto =   $pagamento->Tipo;
            }

            	
			/*$update = $this->pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".saidaestoque 
					SET SAIDA_NFE = '".$this->empresa->serie_nfce_producao."'  WHERE NUMERO= '$numPedido' AND num_livro = '$caixa'");
			$update->execute();
            */
              //buscar cfop 
              $_modBC = "0";              
              $_pICMS = "18"; 
              $sql = "SELECT NAT_CODIGO,NAT_DESCRICAO,NAT_TIPO,NAT_modBC,NAT_pICMS,NAT_pPis,NAT_pCofins,NAT_PIS,NAT_COFINS FROM ".$_SESSION['BASE'].".cfop where NAT_CODIGO = '5102' limit 1";        
              $statement =$this->pdo->query("$sql");
              $retorno = $statement->fetchAll();
           
              foreach ($retorno as $row) {         
              
                $_modBC = $row['NAT_modBC'];              
                $_pICMS = $row['NAT_pICMS']; 
                $_cstPIS = $row['NAT_PIS']; 
                $_cstCofins = $row['NAT_COFINS']; 
                $_pPis = $row['NAT_pPis']; 
                $_pCofins = $row['NAT_pCofins']; 
  
              }

        } catch (Exception $e) {
            throw new Exception("Erro ao consultar dados: " . $e->getMessage());
        }

        /**
         * Gera XML
         */
        try {
            date_default_timezone_set('America/Sao_Paulo');

            $nfe = new Make;
    
            $std = new stdClass;
            $std->versao = '4.00';
            $std->pk_nItem = null;
            $nfe->taginfNFe($std);
    
            $std = new stdClass;
            $std->cUF = $this->empresa->empresa_ddd;
            $std->cNF = strlen($numPedido) < 8 ? str_pad($numPedido , (8 - strlen($numPedido)) , '0' , STR_PAD_LEFT) : $numPedido;
            $std->natOp = 'VENDA CONSUMIDOR';
            $std->mod = 65;
            $std->serie = $this->empresa->serie_nfce_producao;
            if($numeroNFCe > 0) {
                $std->nNF = $numeroNFCe;
              
            }else{
                $std->nNF = $this->empresa->proximo_numero_nfce_producao;
              
            }
           
            $std->dhEmi = date("Y-m-d\TH:i:sP");
            $std->dhSaiEnt = date("Y-m-d\TH:i:sP");
            $std->tpNF = 1;
            $std->idDest = 1;
            $std->tpImp = 4;
            $std->tpEmis = 1;
            $std->cDV = 4;
            $std->cMunFG = $this->empresa->empresa_codmunicipio;
            $std->tpAmb = 1;
            $std->finNFe = 1;
            $std->indFinal = 1;
            $std->indPres = 1;
            $std->indIntermed = null;
            $std->procEmi = 0;
           // $std->verProc = '1';
            $std->verProc = '4.9.27';
            $std->dhCont = null;
            $std->xJust = null;
            $nfe->tagide($std);
    
            $std = new stdClass;
            $std->xNome = $this->empresa->empresa_razaosocial;
            $std->xFant = $this->empresa->empresa_nome;
            $std->IE =  $this->empresa->empresa_inscricao;
            if($this->empresa->empresa_tipo == '0') {
                $this->empresa->empresa_tipo = '1';
            }
            $std->CRT = $this->empresa->empresa_tipo;
            $std->CNPJ = $this->empresa->empresa_cnpj;
            $nfe->tagemit($std);
    
            $std = new stdClass;
            $std->xLgr = $this->empresa->empresa_endereco;
            $std->nro = $this->empresa->empresa_numero;
            $std->xCpl = $this->empresa->empresa_complemento;
            $std->xBairro = $this->empresa->empresa_bairro;
            $std->cMun = $this->empresa->empresa_codmunicipio;
            $std->xMun = $this->empresa->empresa_cidade;
            $std->UF = $this->empresa->empresa_uf;
            $std->CEP = $this->empresa->CEP;
            $std->cPais = '1058';
            $std->xPais = 'Brasil';
            $std->fone = $this->empresa->empresa_telefone;
            $nfe->tagenderEmit($std);
    
            if (!empty($pedido->cpfcnpj)) {
                   //DADOS CONSUMIDOR ---------------------------------------------------------------------------------------
                $std = new stdClass;
                $std->xNome = trim($c_nomecliente);
               // $std->indIEDest = trim($pedido->nfed_tipocontribuinte);
                $std->indIEDest = 9;
                
                $c_cpfcnpj = preg_replace('/[^0-9]/', '',  $_CPFCNPJ);
                strlen($c_cpfcnpj) > 11 ? $std->CNPJ =   $c_cpfcnpj : $std->CPF =  $c_cpfcnpj;
              
           
                $nfe->tagdest($std);
             
           
                $std = new stdClass;
                $std->xLgr = trim($c_endereco);
                $std-> nro = trim($c_numrua);
                $std->xBairro = trim($c_bairro);
                $std->cMun = trim($codigo_municipio);
                $std->xMun = trim($c_cidade);
                $std->UF = trim($c_uf);
                $std->CEP =preg_replace('/[^0-9]/', '',trim($c_cep)); 
                $std->cPais = '1058';
               
                         
                  $nfe->tagenderDest($std); 
               

                /*
                $std = new stdClass;
                if($_NOME != ""){
                    $std->xNome = $_NOME;
                }
               
                $std->indIEDest = 9;
                $pedido->cpfcnpj = preg_replace('/[^0-9]/', '', $pedido->cpfcnpj);
                strlen($pedido->cpfcnpj) > 11 ? $std->CNPJ = $pedido->cpfcnpj : $std->CPF = $pedido->cpfcnpj;

                    //dados gravado cliente
        
                $nfe->tagdest($std);*/
            }

    
    
            $i = 1;
            $totalItems = 0;
            $totalTrib = 0;      
            $basevaloritemG = 0;
            $totalFrete = 0;
            $totalOutros = 0;
            $totalbaseIcms = 0;
            $totalIcms = 0;

            foreach ($items as $item) {

                $sqlCEST = "SELECT tabnct_cest FROM bd_prisma.tab_ncmcest  WHERE tabnc_ncm = '". $item->Cod_Class_Fiscal."' AND tabnct_cest <> '' LIMIT 1";
                $consultaCEST = $this->pdo->query("$sqlCEST");
                $resultCEST = $consultaCEST->fetch();
                $CEST = $resultCEST["tabnct_cest"];

                $CFOP  = $item->CFOPD;
                if($CFOP == ""){
                    $CFOP = '5102';
                }
                if($CFOP == "5102" or $CFOP == '5405'){
                    //não faz nada
                }else{
                    $CFOP = '5102'; //diferente
                }

                if($CEST == "") {
                    $CFOP = '5102'; 
                }
    
                $std = new stdClass;
                $std->item = $i;
                $std->cProd = $item->CODIGO_ITEM;
                $std->cEAN = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->xProd = $item->DESCRICAO_ITEM;
                $std->NCM = $item->Cod_Class_Fiscal;
                $std->CFOP =  $CFOP ;
                $std->uCom = $item->UNIDADE_MEDIDA;
                $std->qCom = number_format($item->QUANTIDADE, 4, '.', '');
                $std->vUnCom = $item->Valor_unitario_desc;
                $std->vProd = ($item->Valor_unitario_desc*$item->QUANTIDADE);
                $basevaloritem = ($item->Valor_unitario_desc*$item->QUANTIDADE);
                $std->cEANTrib = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->uTrib = $item->UNIDADE_MEDIDA;
                $std->qTrib = number_format($item->QUANTIDADE, 4, '.', '');
                $std->vUnTrib = $item->Valor_unitario_desc;
                if ($xdesconto_vlr >= 0.01) {
                    $std->vDesc = $xdesconto_vlr;
                   
                }
                
                $std->indTot = 1;
                $nfe->tagprod($std);

                
                if($CEST != "") {
                    $std = new stdClass();
                    $std->item = $i; 
                    $std->CEST = $CEST ;
                    $nfe->tagCEST($std);
               
                }
              
    
                $std = new stdClass();
                $std->item = $i;
                $std->vTotTrib = number_format(($item->QUANTIDADE * ($item->impostonacional / 100 * $item->Valor_unitario_desc)), 2, '.', '');
                $_vt = number_format(($item->QUANTIDADE * ($item->impostonacional / 100 * $item->Valor_unitario_desc)), 2, '.', '');
                $nfe->tagimposto($std);
    
               //simples nacional
               if($this->empresa->empresa_tipo == '1' or $this->empresa->empresa_tipo == '0'){
                if($CFOP == '5405') {
                    $item->SIT_TRIBUTARIA = '500';
                }else{
                    $item->SIT_TRIBUTARIA = '102';
                }    
                
            }

             
                if($item->SIT_TRIBUTARIA > 100 ){             
                    $std = new stdClass();
                    $std->item = $i;
                    $std->orig = 0;
                    $std->CSOSN = $item->SIT_TRIBUTARIA;
                    $nfe->tagICMSSN($std);
                }else{

                //mod 65
                $std = new stdClass();
                $std->item = $i;
                $std->orig = 0;
                
                $std->CST = $item->SIT_TRIBUTARIA;
                if( $std->CST == 0)  { $std->CST = "00";}
                  //$CST_CFOP
                  if(  $item->SIT_TRIBUTARIA == '00' )  {
                    $std->modBC = $_modBC;
                    $std->vBC = $basevaloritem;
                    $std->pICMS = $_pICMS;
                  }else{
                 
                  }
               
                $std->vICMS = number_format(($basevaloritem*($_pICMS/100)), 2, '.', '');
                $totalbaseIcms = $totalbaseIcms + $basevaloritem;
                $totalIcms = $totalIcms + number_format(($basevaloritem*($_pICMS/100)), 2, '.', '');
                
                $nfe->tagICMS($std);

                if($this->empresa->empresa_tipo == '1' or $this->empresa->empresa_tipo == '0'){
                    //não faz nada simples nacional
                 }else{     
                    
            
                            $std = new stdClass();
                            $std->item = $i;
                            $std->CST = $_cstPIS ;//'07';
                            if($_pPis > 0 and $_cstPIS  == '01') {              
                                $std->vBC = $item->vlrunitario_nfeitens*$item->quantidade;//'07';
                                $std->pPIS =$_pPis ;//'07';
                                $std->vPIS =$_pPis * ($item->vlrunitario_nfeitens*$item->quantidade)/100;//'07';
                                }
                            $nfe->tagPIS($std);
            
            
                            $std = new stdClass();
                            $std->item = $i;
                            $std->CST = $_cstCofins;//'04';
                            if($_pCofins >  0 and $_cstCofins == '01') {   
                                $std->vBC = $item->vlrunitario_nfeitens*$item->quantidade;//'04';
                                $std->pCOFINS = $_pCofins;//'04';
                                $std->vCOFINS = $_pCofins * ($item->vlrunitario_nfeitens*$item->quantidade)/100;//'04';
                            }
                            $nfe->tagCOFINS($std);
                }

                }
                
    
                $totalItems = $totalItems + ($item->QUANTIDADE * $item->Valor_unitario_desc);
                $totalTrib = $totalTrib + ($_vt);
                $i++;
            }
          
    
            $std = new stdClass;
            $std->vBC = number_format($totalbaseIcms, 2, '.', ''); 
            $std->vICMS = number_format($totalIcms, 2, '.', ''); 
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
            $std->vDesc = $totalDesconto;
            $std->vII = 0;
            $std->vIPI = 0;
            $std->vIPIDevol = 0;
            $std->vPIS = 0;
            $std->vCOFINS = 0;
            $std->vOutro = null;
            $std->vNF = number_format($totalItems-$totalDesconto, 2, '.', '');
            $std->vTotTrib = number_format($totalTrib, 2, '.', '');
            $nfe->tagicmstot($std);

            $std = new stdClass;
            $std->modFrete = 9;
            $nfe->tagtransp($std);

            $std = new stdClass;
            if($pedido->Valor_Troco > 0) {
                $std->vTroco = $pedido->Valor_Troco;
            }


            
            $nfe->tagpag($std);

           // foreach ($pagamentos as $pagamento) {
                $std = new stdClass;
                $std->tPag = strval($tipopgto);
             
                $std->vPag = $totalPedido;
                if($pagamento->Tipo == "03" or $pagamento->Tipo == "04"  or $pagamento->Tipo == "17" ){
                    $std->tpIntegra = 2;
                    if($pagamento->Tipo == "03" ){
                        $std->indPag = 1; //= Pagamento a Prazo
                    }else{                       
                        $std->indPag = 0; //pagamento a vista
                    }                  
                }              
                $nfe->tagdetpag($std);
          //  }

    
            // 
            // $std->infAdFisco = '';
            if(trim($_OBS) != "") {
                $std = new stdClass;
                $std->infCpl = $_OBS;
                $nfe->taginfadic($std);
            }
            // 
            // 
          if( $this->empresa->empresa_uf == "PR" OR $this->empresa->empresa_uf == "MS"){
            $std = new stdClass();
            $std->CNPJ = '11493284000111';
            $std->xContato = 'Sistema Prisma';
            $std->email = 'contato@sistemaprisma.com.br';
            $std->fone = '4131544325';
            $std->CSRT = 'c2ymfa80CP9GHGiwlBBVVU5yd0Y=';
            $std->idCSRT = '01';
            $nfe->taginfRespTec($std);
        }

           if( $this->empresa->empresa_uf == "CE"){
                $std = new stdClass();
                $std->CNPJ = '46142393000179';
                $std->xContato = 'Sistema Prisma - Dvet';
                $std->email = 'contato@sistemaprisma.com.br';
                $std->fone = '41991458007';
            //  $std->CSRT = 'c2ymfa80CP9GHGiwlBBVVU5yd0Y=';
            //  $std->idCSRT = '01';
                $nfe->taginfRespTec($std);
            }

            $nfe->monta();
           
         
            return $nfe->getXML();

        } catch (\Exception $e) {
         
           /* throw new Exception(
                "Erro ao gerar XML: " . $e->getMessage(),
                "Mensagem: ". print_r($nfe->getErrors()));
			*/
            print "<pre>";
                print_r($nfe->getErrors());
            print "</pre>";
        
           return $e->getMessage();
        }  
    }

    public function gerarNFCe(Int $numPedido, Int $caixa)
    {
        /**
         * Consulta informações no banco
         */
        try {
            $stm = $this->pdo->query("SELECT CODIGO_CLIENTE,cpfcnpj,VL_Pedido, Valor_Troco,Valor_Frete,VL_DESCONTO,SAIDA_NFE FROM ".$_SESSION['BASE'].".saidaestoque WHERE NUMERO = '$numPedido' AND num_livro = '$caixa'");
            $pedido = $stm->fetch(PDO::FETCH_OBJ);
            $totalPedido =  $pedido->VL_Pedido;
            $totalFrete =  $pedido->Valor_Frete;
            $totalDesconto =  $pedido->VL_DESCONTO;
            $CODIGO_CLIENTE =  $pedido->CODIGO_CLIENTE;
            $SAIDA_NFE =  $pedido->SAIDA_NFE;
            $_NOME  = "";
            if($CODIGO_CLIENTE > 1) {
                $stmConsumidor = $this->pdo->query("SELECT Nome_Consumidor,CGC_CPF FROM ".$_SESSION['BASE'].".consumidor WHERE CODIGO_CONSUMIDOR = '$CODIGO_CLIENTE' ");
                $retc = $stmConsumidor->fetch(PDO::FETCH_OBJ);
                $_NOME  = trim($retc->Nome_Consumidor);
                if(trim($retc->CGC_CPF) != "" and $pedido->cpfcnpj == "" ){
                    $pedido->cpfcnpj = $retc->CGC_CPF;
                }
            }
          
    
            $stm = $this->pdo->query("SELECT saida.CODIGO_ITEM, saida.QUANTIDADE, saida.DESCRICAO_ITEM, saida.Valor_unitario_desc, item.Codigo_Barra, item.Cod_Class_Fiscal, item.UNIDADE_MEDIDA, item.SIT_TRIBUTARIA, imp.impostonacional,
            id_pis,id_cofins,CFOPD,
              (saida.Valor_unitario_desc * saida.QUANTIDADE) AS vProd,           
                ROUND(
                    (saida.Valor_unitario_desc * saida.QUANTIDADE) / {$totalPedido} * {$totalDesconto}, 
                    2
                ) AS vDesc 
            FROM ".$_SESSION['BASE'].".saidaestoqueitem saida 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoque item ON saida.CODIGO_ITEM = item.CODIGO_FORNECEDOR LEFT JOIN minhaos_cep.impostost imp ON item.Cod_Class_Fiscal = imp.codigoncm            
            WHERE saida.NUMERO = '$numPedido' AND saida.num_livro = '$caixa' ");
            $items = $stm->fetchAll(PDO::FETCH_OBJ);
            $stm->execute();

            // Ajuste de centavos no último item
            $somaDescontos = array_sum(array_column($items, 'vDesc'));
            $diferenca = round($totalDesconto - $somaDescontos, 2);

            if ($diferenca != 0) {
                $items[count($items)-1]->vDesc += $diferenca;
            }


            //alterado desconto proporcional
           // $xdesconto_vlr = $totalDesconto/ $stm->rowCount() ;

            $stm = $this->pdo->query("SELECT spgto_valor, spgto_entrada, Tipo FROM ".$_SESSION['BASE'].".saidaestoquepgto LEFT JOIN ".$_SESSION['BASE'].".tiporecebimpgto ON spgto_tipopgto = id WHERE spgto_parcela > 0 and  spgto_numpedido = '$numPedido' AND spgto_numlivro = '$caixa' group by spgto_valor,spgto_entrada,Tipo");
            $pagamentos = $stm->fetchAll(PDO::FETCH_OBJ);

            	
			/*$update = $this->pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".saidaestoque 
					SET SAIDA_NFE = '".$this->empresa->serie_nfce_producao."'  WHERE NUMERO= '$numPedido' AND num_livro = '$caixa'");
			$update->execute();
            */

          

            //buscar cfop 
            $_modBC = "0";              
            $_pICMS = "18"; 
            $sql = "SELECT NAT_CODIGO,NAT_DESCRICAO,NAT_TIPO,NAT_modBC,NAT_pICMS,NAT_pPis,NAT_pCofins,NAT_PIS,NAT_COFINS FROM ".$_SESSION['BASE'].".cfop where NAT_CODIGO = '5102' limit 1";        
          
            $statement =$this->pdo->query("$sql");
            $retorno = $statement->fetchAll();
         
            foreach ($retorno as $row) {  
                $_modBC = $row['NAT_modBC'];              
                $_pICMS = $row['NAT_pICMS']; 
                $_cstPIS = $row['NAT_PIS']; 
                $_cstCofins = $row['NAT_COFINS']; 
                $_pPis = $row['NAT_pPis']; 
                $_pCofins = $row['NAT_pCofins']; 
            }

          

        } catch (Exception $e) {
            throw new Exception("Erro ao consultar dados: " . $e->getMessage());
        }

        /**
         * Gera XML
         */
        try {
            date_default_timezone_set('America/Sao_Paulo');

            $nfe = new Make;
    
            $std = new stdClass;
            $std->versao = '4.00';
            $std->pk_nItem = null;
            $nfe->taginfNFe($std);
    
            $std = new stdClass;
            $std->cUF = $this->empresa->empresa_ddd;
            $std->cNF = strlen($numPedido) < 8 ? str_pad($numPedido , (8 - strlen($numPedido)) , '0' , STR_PAD_LEFT) : $numPedido;
            $std->natOp = 'VENDA CONSUMIDOR';
            $std->mod = 65;
            $std->serie = $this->empresa->serie_nfce_producao;
           // $std->nNF = $this->empresa->proximo_numero_nfce_producao;
            $std->nNF =  $SAIDA_NFE;          
            $std->dhEmi = date("Y-m-d\TH:i:sP");
            $std->dhSaiEnt = date("Y-m-d\TH:i:sP");
            $std->tpNF = 1;
            $std->idDest = 1;
            $std->tpImp = 4;
            $std->tpEmis = 1;
            $std->cDV = 4;
            $std->cMunFG = $this->empresa->empresa_codmunicipio;
            $std->tpAmb = 1;
            $std->finNFe = 1;
            $std->indFinal = 1;
            $std->indPres = 1;
            $std->indIntermed = null;
            $std->procEmi = 0;
           // $std->verProc = '1';
            $std->verProc = '4.9.27';
            $std->dhCont = null;
            $std->xJust = null;
            $nfe->tagide($std);
    
            $std = new stdClass;
            $std->xNome = $this->empresa->empresa_razaosocial;
            $std->xFant = $this->empresa->empresa_nome;
            $std->IE =  $this->empresa->empresa_inscricao;
            if($this->empresa->empresa_tipo == '0') {
                $this->empresa->empresa_tipo = '1';
            }
            $std->CRT = $this->empresa->empresa_tipo;
            $std->CNPJ = $this->empresa->empresa_cnpj;
            $nfe->tagemit($std);
    
            $std = new stdClass;
            $std->xLgr = $this->empresa->empresa_endereco;
            $std->nro = $this->empresa->empresa_numero;
            $std->xCpl = $this->empresa->empresa_complemento;
            $std->xBairro = $this->empresa->empresa_bairro;
            $std->cMun = $this->empresa->empresa_codmunicipio;
            $std->xMun = $this->empresa->empresa_cidade;
            $std->UF = $this->empresa->empresa_uf;
            $std->CEP = $this->empresa->CEP;
            $std->cPais = '1058';
            $std->xPais = 'Brasil';
            $std->fone = $this->empresa->empresa_telefone;
            $nfe->tagenderEmit($std);
    
            if (!empty($pedido->cpfcnpj)) {
                $std = new stdClass;
                if($_NOME != ""){
                    $std->xNome = $_NOME;
                }
                $std->indIEDest = 9;
                $pedido->cpfcnpj = preg_replace('/[^0-9]/', '', $pedido->cpfcnpj);
                strlen($pedido->cpfcnpj) > 11 ? $std->CNPJ = $pedido->cpfcnpj : $std->CPF = $pedido->cpfcnpj;
                $nfe->tagdest($std);
            }

            $_cnpjcpfContador = preg_replace('/[^0-9]/', '',  $this->empresa->empresa_cnpjcpfContador);

            if(strlen($_cnpjcpfContador) >= 11){    
                $std = new stdClass;           
                strlen($_cnpjcpfContador) > 11 ? $std->CNPJ =   $_cnpjcpfContador : $std->CPF =  $_cnpjcpfContador;
                $nfe->tagautXML($std);
            }   

    
            $i = 1;
            $totalItems = 0;
          
            $totalTrib = 0;
            $totalbaseIcms =0;
            $totalIPI = 0;
            $totalIcms = 0;
            

            foreach ($items as $item) {

                $sqlCEST = "SELECT tabnct_cest FROM bd_prisma.tab_ncmcest  WHERE tabnc_ncm = '". $item->Cod_Class_Fiscal."' AND tabnct_cest <> '' LIMIT 1";
                $consultaCEST = $this->pdo->query("$sqlCEST");
                $resultCEST = $consultaCEST->fetch();
                $CEST = $resultCEST["tabnct_cest"];

                $CFOP  = $item->CFOPD;
                if($CFOP == ""){
                    $CFOP = '5102';
                }
                if($CFOP == "5102" or $CFOP == '5405'){
                    //não faz nada
                }else{
                    $CFOP = '5102'; //diferente
                }

                if($CEST == "") {
                    $CFOP = '5102'; 
                }
    
                $std = new stdClass;
                $std->item = $i;
                $std->cProd = $item->CODIGO_ITEM;
                $std->cEAN = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->xProd = $item->DESCRICAO_ITEM;
                $std->NCM = $item->Cod_Class_Fiscal;
                $std->CFOP =  $CFOP ;
                $std->uCom = $item->UNIDADE_MEDIDA;
                $std->qCom = number_format($item->QUANTIDADE, 4, '.', '');
                $std->vUnCom = $item->Valor_unitario_desc;
                $std->vProd = ($item->Valor_unitario_desc*$item->QUANTIDADE);
                $basevaloritem = ($item->Valor_unitario_desc*$item->QUANTIDADE);
              // $std->vProd = $item->Valor_unitario_desc;
                $std->cEANTrib = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->uTrib = $item->UNIDADE_MEDIDA;
                $std->qTrib = number_format($item->QUANTIDADE, 4, '.', '');
                $std->vUnTrib = $item->Valor_unitario_desc;
              /*  if ($xdesconto_vlr >= 0.01) {
                    $std->vDesc = $xdesconto_vlr;
                }
                    */
                
                if ($item->vDesc >= 0.01) {
                    $std->vDesc = $item->vDesc;
                }
                $std->indTot = 1;
                $nfe->tagprod($std);

                if($CEST != "") {
                    $std = new stdClass();
                    $std->item = $i; 
                    $std->CEST = $CEST ;
                    $nfe->tagCEST($std);
               
                }
              
             
    
                $std = new stdClass();
                $std->item = $i;
                $std->vTotTrib = number_format(($item->QUANTIDADE * ($item->impostonacional / 100 * $item->Valor_unitario_desc)), 2, '.', '');
                $_vt = number_format(($item->QUANTIDADE * ($item->impostonacional / 100 * $item->Valor_unitario_desc)), 2, '.', '');
                $nfe->tagimposto($std);
    
               
                //simples nacional
                if($this->empresa->empresa_tipo == '1' or $this->empresa->empresa_tipo == '0'){
                    if($CFOP == '5405') {
                        $item->SIT_TRIBUTARIA = '500';
                    }else{
                        $item->SIT_TRIBUTARIA = '102';
                    }
        
                    
                }
            
                if($item->SIT_TRIBUTARIA > 100 ){             
                    $std = new stdClass();
                    $std->item = $i;
                    $std->orig = 0;
                    $std->CSOSN = $item->SIT_TRIBUTARIA;
                    $nfe->tagICMSSN($std);
               
               
                }else{

                   

           //mod 65
                $std = new stdClass();
                $std->item = $i;
              //  $std->orig = 0;
                
                $std->CST = $item->SIT_TRIBUTARIA;
                
                if( $std->CST == 0)  { $std->CST = "00";}
                if( $std->orig == '')  {  $std->orig = 0;}

                  //$CST_CFOP
                  if($CFOP == '5405' and  $CEST != "" and  $item->SIT_TRIBUTARIA  == '60') {
                      
                    $std->item = $i;
                  //  $std->orig = 0;
                    $std->CSOSN = '60';    
                //    $totalbaseIcms = $totalbaseIcms + $basevaloritem;

                  }else {       
                    $std->CST = "00";
                    $std->CSOSN = '00';           
                    $std->modBC = $_modBC;
                    $std->vBC = $basevaloritem;
                    $std->pICMS = $_pICMS;
                    $std->vICMS = number_format(($basevaloritem*($_pICMS/100)), 2, '.', '');
                    $totalbaseIcms = $totalbaseIcms + $basevaloritem;
                    $totalIcms = $totalIcms + number_format(($basevaloritem*($_pICMS/100)), 2, '.', '');

                  }                 
               
                         $nfe->tagICMS($std);

                         if($this->empresa->empresa_tipo == '1' or $this->empresa->empresa_tipo == '0'){
                            //não faz nada simples nacional
                         }else{     
                            
                    
                                    $std = new stdClass();
                                    $std->item = $i;
                                    $std->CST = $_cstPIS ;//'07';
                                    if($_pPis > 0 and $_cstPIS  == '01') {              
                                        $std->vBC = $item->vlrunitario_nfeitens*$item->quantidade;//'07';
                                        $std->pPIS =$_pPis ;//'07';
                                        $std->vPIS =$_pPis * ($item->vlrunitario_nfeitens*$item->quantidade)/100;//'07';
                                        }
                                    $nfe->tagPIS($std);
                    
                    
                                    $std = new stdClass();
                                    $std->item = $i;
                                    $std->CST = $_cstCofins;//'04';
                                    if($_pCofins >  0 and $_cstCofins == '01') {   
                                        $std->vBC = $item->vlrunitario_nfeitens*$item->quantidade;//'04';
                                        $std->pCOFINS = $_pCofins;//'04';
                                        $std->vCOFINS = $_pCofins * ($item->vlrunitario_nfeitens*$item->quantidade)/100;//'04';
                                    }
                                    $nfe->tagCOFINS($std);
                        }

                         
                  }
                
    
                $totalItems = $totalItems + ($item->QUANTIDADE * $item->Valor_unitario_desc);
                $totalTrib = $totalTrib + ($_vt);
                $i++;
            }
          
        
            $std = new stdClass;
            $std->vBC = number_format($totalbaseIcms, 2, '.', ''); 
            $std->vICMS = number_format($totalIcms, 2, '.', ''); 
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
            $std->vDesc = $totalDesconto;
            $std->vII = 0;
            $std->vIPI = 0;
            $std->vIPIDevol = 0;
            $std->vPIS = 0;
            $std->vCOFINS = 0;
            $std->vOutro = null;
            $std->vNF = number_format($totalItems-$totalDesconto, 2, '.', '');
            $std->vTotTrib = number_format($totalTrib, 2, '.', '');
            $nfe->tagicmstot($std);

            $std = new stdClass;
            $std->modFrete = 9;
            $nfe->tagtransp($std);

            $std = new stdClass;
            if($pedido->Valor_Troco > 0) {
                $std->vTroco = $pedido->Valor_Troco;
            }


            
            $nfe->tagpag($std);

            foreach ($pagamentos as $pagamento) {
                $std = new stdClass;
                $std->tPag = strval($pagamento->Tipo);
             
                $std->vPag = $pagamento->spgto_valor-$totalFrete;
                if($pagamento->Tipo == "03" or $pagamento->Tipo == "04" or $pagamento->Tipo == "17" ){
                    $std->tpIntegra = 2;
                    if($pagamento->Tipo == "03" ){
                        $std->indPag = 1; //= Pagamento a Prazo
                    }else{                       
                        $std->indPag = 0; //pagamento a vista
                    }                  
                }              
                $nfe->tagdetpag($std);
            }

    
            // $std = new stdClass;
            // $std->infAdFisco = '';
            // $std->infCpl = '';
            // $nfe->taginfadic($std);
            if( $this->empresa->empresa_uf == "PR" or $this->empresa->empresa_uf == "MS"){
            $std = new stdClass();
            $std->CNPJ = '11493284000111';
            $std->xContato = 'Sistema Prisma';
            $std->email = 'contato@sistemaprisma.com.br';
            $std->fone = '4131544325';
            $std->CSRT = 'c2ymfa80CP9GHGiwlBBVVU5yd0Y=';
            $std->idCSRT = '01';
            $nfe->taginfRespTec($std);
        }

          if( $this->empresa->empresa_uf == "CE"){
                $std = new stdClass();
                $std->CNPJ = '46142393000179';
                $std->xContato = 'Sistema Prisma - Dvet';
                $std->email = 'contato@sistemaprisma.com.br';
                $std->fone = '41991458007';
            //  $std->CSRT = 'c2ymfa80CP9GHGiwlBBVVU5yd0Y=';
            //  $std->idCSRT = '01';
                $nfe->taginfRespTec($std);
            }
            
            $nfe->monta();
           


         
            return $nfe->getXML();

        } catch (\Exception $e) {
         
           /* throw new Exception(
                "Erro ao gerar XML: " . $e->getMessage(),
                "Mensagem: ". print_r($nfe->getErrors()));
			*/
            print "<pre>";
                print_r($nfe->getErrors());
            print "</pre>";
        
           return $e->getMessage();
        }  
    }

    public function gerarNFe(Int $numReg)
    {
        /**
         * Consulta informações no banco
         */
        
        try {

           $sql = "SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  
                    FROM  " . $_SESSION['BASE'] . ".parametro  ";
            $query = $this->pdo->query("$sql");
            $retornoItem = $query->fetch();

            $_vizCodInterno = $retornoItem['empresa_vizCodInt'];

            if($_vizCodInterno == 1) { 
                $_COD = "CODIGO_FABRICANTE";   
            }else{
                $_COD = "codigoproduto_nfeitens";   
            }

            $_s = "SELECT nfed_cfopID,nfed_numeronf,nfed_cfop,nfed_cfopdesc,nfed_tipodocumento,nfed_operacao,nfed_finalizade,nfed_tipocontribuinte,            
            nfed_tipocontribuinte,
            nfed_codpgto,nfed_informacaoAdicionais,nfed_informacaoTributos,nfed_chavedev1,
            nfed_modalidade, nfed_tranportadora,nfed_valor,nfed_qtde,nfed_qtdevolume,nfed_especie,nfed_marca,nfed_numerovolume,nfed_bruto,nfed_liquido,
            nfed_dNome,nfed_dEdereco,nfed_dBairro,nfed_dCidade,nfed_dUF,nfed_dTelefone,nfed_dCEP,nfed_cpfcnpj,nfed_email,nfed_dnumrua,nfed_ie
            FROM ".$_SESSION['BASE'].".NFE_DADOS            
            WHERE nfed_id = '$numReg'";
            $stm = $this->pdo->query("$_s");
           
         
            $pedido = $stm->fetch(PDO::FETCH_OBJ);
       
            $nfed_numeronf  = $pedido->nfed_numeronf;
            $nfed_cfopID  = $pedido->nfed_cfopID;
            $nfed_cfop  = $pedido->nfed_cfop;
            $cfopdesc = $pedido->nfed_cfopdesc;
            $nfed_tipodocumento = $pedido->nfed_tipodocumento;
            $finalidade =  $pedido->nfed_finalizade;
            $destinooperacao =  $pedido->nfed_operacao;
            $informacaoAdicionais = $pedido->nfed_informacaoAdicionais;
            $informacaoTributos = $pedido->nfed_informacaoTributos;
            $pgto =  $pedido->nfed_codpgto;
            $chavedevolucao =  str_replace(' ',"",$pedido->nfed_chavedev1);
            $chavedevolucao = preg_replace('/[^0-9]/', '',  $chavedevolucao );
            $nfed_modalidade = $pedido->nfed_modalidade;
            $nfed_tranportadora = $pedido->nfed_tranportadora;
            $nfed_valor = $pedido->nfed_valor;
            $nfed_qtde = $pedido->nfed_qtde;
            $nfed_qtdevolume = $pedido->nfed_qtdevolume;
            $nfed_especie = $pedido->nfed_especie;
            $nfed_marca = $pedido->nfed_marca;
            $nfed_numerovolume = $pedido->nfed_numerovolume;
            $nfed_bruto = $pedido->nfed_bruto;
            $nfed_liquido = $pedido->nfed_liquido;
            $totalDesconto = 0;
            $xdesconto_vlr = 0; 

             //dados gravado cliente
            $c_nomecliente = $pedido->nfed_dNome;
            $c_cpfcnpj = $pedido->nfed_cpfcnpj;
            $c_inscricao = $pedido->nfed_ie;
            $c_endereco = $pedido->nfed_dEdereco;
            $c_numrua= $pedido->nfed_dnumrua;
            $c_bairro = $pedido->nfed_dBairro;
            $c_cidade = $pedido->nfed_dCidade;      
            $c_cep = $pedido->nfed_dCEP;       
            $c_uf = $pedido->nfed_dUF;
            $c_telefone = $pedido->nfed_dTelefone;
            $c_email = trim($pedido->nfed_email);
          
               //TELEFONE DDD FONE_RESIDENCIAL FONE_CELULAR FONE_COMERCIAL
               /*
               if($pedido->FONE_CELULAR != "") {
                $telefone = str_replace(".","",$pedido->DDD.$pedido->FONE_CELULAR);
                $telefone = str_replace("-","",$pedido->DDD.$pedido->FONE_CELULAR);
            }elseif($pedido->FONE_RESIDENCIAL != "") {
                $telefone = str_replace(".","",$pedido->DDD.$pedido->FONE_RESIDENCIAL);
                $telefone = str_replace("-","",$pedido->DDD.$pedido->FONE_RESIDENCIAL);
            }elseif($pedido->FONE_COMERCIAL != "") {
                $telefone = str_replace(".","",$pedido->DDD.$pedido->FONE_COMERCIAL);
                $telefone = str_replace("-","",$pedido->DDD.$pedido->FONE_COMERCIAL);
            }
            */
       
            $sql = "SELECT NAT_CODIGO,NAT_DESCRICAO,NAT_TIPO,NAT_CST FROM ".$_SESSION['BASE'].".cfop where ID = '" . $nfed_cfopID . "' limit 1";        
            $statement =$this->pdo->query("$sql");
            $retorno = $statement->fetchAll();
         
            foreach ($retorno as $row) {         
            
                $nfed_tributado = $row['NAT_TIPO']; // vTotTrib tipo igual 0 tributado  1 nao tributado 
                $CST_CFOP = $row['NAT_CST'];
            }

            if($nfed_tranportadora > 0) {
                $sql = "SELECT CNPJ,INSCR_ESTADUAL,RAZAO_SOCIAL,ENDERECO,CIDADE,UF FROM ".$_SESSION['BASE'].".fabricante where CODIGO_FABRICANTE = '" . $nfed_tranportadora . "' limit 1";        
                $statement =$this->pdo->query("$sql");
                $retorno = $statement->fetchAll();             
                foreach ($retorno as $row) {      
                    $nfed_nometransporador = $row['RAZAO_SOCIAL']; // 
                    $nfed_cnpjtransporador = $row['CNPJ']; // 
                    $nfed_ietransporador = $row['INSCR_ESTADUAL']; //                     
                    $nfed_enderecotransporador = $row['ENDERECO']; // 
                    $nfed_cidadetransporador = $row['CIDADE']; // 
                    $nfed_uftransporador = $row['UF']; // 
                }
                

            }
           
            $_si = "SELECT infAdProd,nfe_itensvlrOutros,nfe_itensfrete,id_nfedados,$_COD as CODIGOPROODUTO,Codigo_Barra,descricao_nfeitens,item_nmc,unidade_nfeitens,item_nmc,quantidade,
            vlrunitario_nfeitens,nfe_itensvlrDesconto,impostonacional,cfop_nfeitens,situacaotributario_nfeitens,
            origemimposto_nfeitens,pisCST_nfeitens,cofins_nfeitens,modBC_nfeitens,vBC_nfeitens,pICMS_nfeitens,vICMS_nfeitens,modBCST_nfeitens,vBCST_nfeitens,pICMSST_nfeitens,vICMSST_nfeitens,
            nfe_itensvlrIPI,nfe_itensIPI,mva_nfeitens,item_cest,nfe_itensPimpostoDevol,nfe_itensvlrimpostoDevol,NAT_pPis,NAT_pCofins,fcpST_nfeitens,vlrfcpST_nfeitens      
            FROM ".$_SESSION['BASE'].".NFE_ITENS 
            LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON  codigoproduto_nfeitens = CODIGO_FORNECEDOR
            LEFT JOIN ".$_SESSION['BASE'].".NFE_DADOS ON  id_nfedados = nfed_id
             LEFT JOIN ".$_SESSION['BASE'].".cfop ON  nfed_cfopid = ID
            LEFT JOIN minhaos_cep.impostost imp ON item_nmc = imp.codigoncm
             WHERE id_nfedados = '$numReg' ORDER BY id_nfeitens ASC ";
         
            $stm = $this->pdo->query("$_si");
            $items = $stm->fetchAll(PDO::FETCH_OBJ);
            $totalintens = $stm->rowCount() ;

            $_si = "SELECT Nfat_idnf,sum(Nfat_valor) as total  FROM ".$_SESSION['BASE'].".NFE_FATURA  WHERE Nfat_idnf = '$numReg' group by  Nfat_idnf";             
            $stm = $this->pdo->query("$_si");
            $faturas = $stm->fetchAll(PDO::FETCH_OBJ);
            $totalfatura = $stm->rowCount() ;

            $_si = "SELECT *  FROM ".$_SESSION['BASE'].".NFE_FATURA  WHERE Nfat_idnf = '$numReg' ";             
            $stm = $this->pdo->query("$_si");
            $duplicatas = $stm->fetchAll(PDO::FETCH_OBJ);
            ;
          
            //buscar codigo municipio 
            $_s = "Select cod_cidade,cod_uf  from minhaos_cep.cidade  where cidade  = '".$c_cidade."' and estado  = '". $c_uf."'";
            $stm = $this->pdo->query("$_s");         
            $codm = $stm->fetch(PDO::FETCH_OBJ);
            $codigo_municipio =  $codm->cod_cidade;

            //codigo cond pgto
            if($pgto != "") {          
                $_s = "Select Tipo, nome FROM ".$_SESSION['BASE'].".tiporecebimpgto  WHERE id = '".$pgto."' ";
                $stm = $this->pdo->query("$_s");         
                $codm = $stm->fetch(PDO::FETCH_OBJ);
                $nfed_codpgto =  $codm->Tipo;
                $nfed_codpgtonome =  $codm->nome;
            }

        $errosmsg = "";

        //para emitir NF
      

        if(trim($nfed_cfop) == "" or  strlen($nfed_cfop) < 4) { 
            $errosmsg = $errosmsg."- Informe Natureza da Operação  <br>";
        }
        $c_cpfcnpj = preg_replace('/[^0-9]/', '',  $c_cpfcnpj);

        if(trim($c_inscricao) == "" and  strlen($c_cpfcnpj) > 11 and  $pedido->nfed_tipocontribuinte == 1) { 
            $errosmsg = $errosmsg."- Informe a inscrição estadual <br>";
        }

        if($finalidade == 4 and ($chavedevolucao) == "" or $nfed_cfop == "5929" and ($chavedevolucao) == "") {  //devolução
            //processa nfeRef e coloca as tags na tag ide
            $errosmsg = $errosmsg." - Informe Nº da chave da nota de referência na aba OUTROS<br>";
           
        } 

        if($totalintens == 0) {
            $errosmsg = $errosmsg." - Informe as Peças ou Produto <br>";
        }else{
            $contador = 0;
            foreach ($items as $item) {
                $contador = $contador+1;
                 if(trim($item->item_nmc) == "" OR  strlen( $item->item_nmc) != 8   ) { 
                    $errosmsg = $errosmsg."- Item ($contador): Informe NCM correto <br>";
                }
               
                if(trim($item->unidade_nfeitens) == "" and  $item->cfop_nfeitens < 0 ) { 
                    $errosmsg = $errosmsg."- Item ($contador): Informe UNIDADE correta <br>";
                }
               

            }
        }
       
       // if(strlen(preg_replace('/[^0-9]/', '',  $c_cpfcnpj)) > 11 and $pedido->nfed_tipocontribuinte == 9 ){ 
        //   $errosmsg = $errosmsg."- Para Cnpj $c_cpfcnpj informe o Tipo Contribuinte(<strong> Contribuinte ICMS ou  Isento de ICMS</strong>)<br>";
     //   }

        if(strlen(preg_replace('/[^0-9]/', '',  $c_inscricao)) >= 1 and strlen(preg_replace('/[^0-9]/', '',  $c_cpfcnpj)) > 11 and $pedido->nfed_tipocontribuinte == 2){ 
            $errosmsg = $errosmsg."- Foi informado a I.E selecione Tipo Contribuinte(<strong>Contribuinte ICMS</strong>)<br>";
         }

         //verificar se existe produtos para 5949 e 6949 com cfop de vendas

        if($nfed_cfop == '5949' or $nfed_cfop == '6949')   {          
                $_selcfop = "SELECT id_nfedados FROM ".$_SESSION['BASE'].".NFE_ITENS   WHERE id_nfedados = '$numReg' and cfop_nfeitens = '5102' or  id_nfedados = '$numReg' and cfop_nfeitens = '5405' or  id_nfedados = '$numReg' and cfop_nfeitens = '6102' or  id_nfedados = '$numReg' and cfop_nfeitens = '6405' limit 1";               
                $stm = $this->pdo->query("$_selcfop");          
                if($stm->rowCount() > 0) {
                    $errosmsg = $errosmsg."- Existe Produtos/Peças lançado com CFOP de Venda <br>";
                };
           
            
           

        }

        
            

            if($errosmsg != "") {
                ?>
           
                    
                        <div >
                           
                           
                              
                               
                                <div class="alert alert-danger "
                                <h4><strong><?=$errosmsg;?></strong></h4>
                        </div>
                                <button class="btn btn-white waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                       
                  
                </div><?php
                exit();
            }
          

        } catch (Exception $e) {
           // throw new Exception("Erro ao consultar dados: " . $e->getMessage());
           print "<pre>";
           print_r( $e->getMessage());
          print "</pre>";
         
        }

        /**
         * Gera XML
         */
 
        try {
            date_default_timezone_set('America/Sao_Paulo');

            $nfe = new Make;
    
            $std = new stdClass;
            $std->versao = '4.00';
            $std->pk_nItem = null;
            $nfe->taginfNFe($std);
    
            $std = new stdClass;
            $std->cUF = $this->empresa->empresa_ddd;
            $std->cNF = strlen($numReg) < 8 ? str_pad($numReg , (8 - strlen($numReg)) , '0' , STR_PAD_LEFT) : $numReg;
            $std->natOp = $cfopdesc;
            $std->mod = 55;
            $std->serie = $this->empresa->serie_nfe_producao;
            if($nfed_numeronf == 0) {
                $std->nNF = $this->empresa->empresa_nf;
            }else{
                $std->nNF = $nfed_numeronf;
            }
           
            $std->dhEmi = date("Y-m-d\TH:i:sP");
            $std->dhSaiEnt = date("Y-m-d\TH:i:sP");
            $std->tpNF = $nfed_tipodocumento;
            $std->idDest = $destinooperacao;
            $std->tpImp = 1;
            $std->tpEmis = 1;
            $std->cDV = 4;
            $std->cMunFG = $this->empresa->empresa_codmunicipio;
            $std->tpAmb = 1;
            $std->finNFe =  $finalidade;
            $std->indFinal = 1;
            $std->indPres = 1;
            $std->indIntermed = null;
            $std->procEmi = 0;
           // $std->verProc = '1';
            $std->verProc = '4.9.27';
            $std->dhCont = null;
            $std->xJust = null;
            $nfe->tagide($std);

            if($finalidade == 2 or $finalidade == 4 or $nfed_cfop == "5929") {  //devolução
                //processa nfeRef e coloca as tags na tag ide
                $std = new stdClass;
                $std->refNFe =  trim($chavedevolucao);
                $nfe->tagrefNFe($std);
               
                }
                
            $std = new stdClass;
            $std->xNome = trim($this->empresa->empresa_razaosocial);
            $std->xFant = trim($this->empresa->empresa_nome);
            $std->IE =  trim($this->empresa->empresa_inscricao);
            if($this->empresa->empresa_tipo == '0') {
                $this->empresa->empresa_tipo = '1';
            }
            $std->CRT = trim($this->empresa->empresa_tipo);
            $std->CNPJ = trim($this->empresa->empresa_cnpj);
            $nfe->tagemit($std);
    
            $std = new stdClass;
            $std->xLgr = $this->empresa->empresa_endereco;
            $std->nro = $this->empresa->empresa_numero;
            $std->xCpl = $this->empresa->empresa_complemento;
            $std->xBairro = $this->empresa->empresa_bairro;
            $std->cMun = $this->empresa->empresa_codmunicipio;
            $std->xMun = $this->empresa->empresa_cidade;
            $std->UF = $this->empresa->empresa_uf;
            $std->CEP = $this->empresa->CEP;
            $std->cPais = '1058';
            $std->xPais = 'Brasil';
            $std->fone = $this->empresa->empresa_telefone;
            
            $nfe->tagenderEmit($std);
    
         

                $std = new stdClass;
                $std->xNome = trim($c_nomecliente);
                $std->indIEDest = trim($pedido->nfed_tipocontribuinte);
                if(trim($c_inscricao) != "" and  trim($c_inscricao) != "ISENTO") { 
                    $std->IE = preg_replace('/[^0-9]/', '',trim($c_inscricao));
                }
                $c_cpfcnpj = preg_replace('/[^0-9]/', '',  $c_cpfcnpj);
                strlen($c_cpfcnpj) > 11 ? $std->CNPJ =   $c_cpfcnpj : $std->CPF =  $c_cpfcnpj;
              
              //  $std->idEstrangeiro = '';
                if ($c_email != "") {
                    $std->email = $c_email;                 
            
                }

                $nfe->tagdest($std);
             
                $_cnpjcpfContador = preg_replace('/[^0-9]/', '',  $this->empresa->empresa_cnpjcpfContador);

                if(strlen($_cnpjcpfContador) >= 11){    
                    $std = new stdClass;           
                    strlen($_cnpjcpfContador) > 11 ? $std->CNPJ =   $_cnpjcpfContador : $std->CPF =  $_cnpjcpfContador;
                    $nfe->tagautXML($std);
                }   

                $std = new stdClass;
                $std->xLgr = trim($c_endereco);
                $std-> nro = trim($c_numrua);
                $std->xBairro = trim($c_bairro);
                $std->cMun = trim($codigo_municipio);
                $std->xMun = trim($c_cidade);
                $std->UF = trim($c_uf);
                $std->CEP =preg_replace('/[^0-9]/', '',trim($c_cep)); 
                $std->cPais = '1058';
                if($c_telefone != "") {
                    $c_telefone =  str_replace(".","",trim($c_telefone));
                    $c_telefone =  str_replace("-","",$c_telefone);
                    $c_telefone =  str_replace("(","",$c_telefone);
                    $c_telefone =  str_replace(")","",$c_telefone);
                    $std->fone =  preg_replace('/[^0-9]/', '',trim($c_telefone)); 
                }
            
              
                  $nfe->tagenderDest($std); 
          //  }
    
            $i = 1;
            $totalItems = 0;
            $totalTrib = 0;
            $totalFrete = 0;
            $totalOutros = 0;
            $totalbaseIcms = 0;
            $totalIcms = 0;
            $totalFCP = 0;
               $totalFCPST = 0;
            $totalbaseIcmsST =0;
            $totalIPI = 0;
            $totalIcmsST = 0;
            $totalDevol  = 0;
           
            //NFE
        
            foreach ($items as $item) {
    
                $std = new stdClass;
                $std->item = $i;
                $std->cProd = $item->CODIGOPROODUTO;
                $std->cEAN = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->xProd = $item->descricao_nfeitens;
                $std->NCM = $item->item_nmc;
               // $std->cBenef = 'PR840008';
             

                if($item->cfop_nfeitens != "0" ) { 
                     $std->CFOP = $item->cfop_nfeitens;
                }else{
                    $std->CFOP = $nfed_cfop; 
                }
               
                $std->uCom = $item->unidade_nfeitens;
                $std->qCom = number_format($item->quantidade, 4, '.', '');
                $std->vUnCom = $item->vlrunitario_nfeitens;
                $std->vProd = ($item->vlrunitario_nfeitens*$item->quantidade);
              // $std->vProd = $item->Valor_unitario_desc;
                $std->cEANTrib = strlen($item->Codigo_Barra) < 13 ? "SEM GTIN" : $item->Codigo_Barra;
                $std->uTrib = $item->unidade_nfeitens;
                $std->qTrib = number_format($item->quantidade, 4, '.', '');
                $std->vUnTrib = $item->vlrunitario_nfeitens;
              
                if ($item->nfe_itensvlrDesconto >= 0.01) {
                    $std->vDesc = $item->nfe_itensvlrDesconto;
                    $totalDesconto = $totalDesconto+$item->nfe_itensvlrDesconto;
            
                }
              
                if ($item->nfe_itensfrete >= 0.01) {
                    $std->vFrete = $item->nfe_itensfrete;
                    $totalFrete = $totalFrete + $item->nfe_itensfrete;
                }

                if ($item->nfe_itensvlrOutros >= 0.01) {
                    $std->vOutro = $item->nfe_itensvlrOutros;
                    $totalOutros = $totalOutros + $item->nfe_itensvlrOutros;
                }

                
             
                $std->indTot = 1;
                $nfe->tagprod($std);

                if($item->item_cest!= "") {
                    $std = new stdClass();
                    $std->item = $i; 
                    $std->CEST = $item->item_cest;
                    $nfe->tagCEST($std);
               
                }

            
             
                
             
    
    
              //  $std = new stdClass();
             //   $std->item = $i;
           //  if($nfed_tributado == 0) {
             //   $std->vTotTrib = number_format(($item->quantidade * ($item->impostonacional / 100 * $item->vlrunitario_nfeitens)), 2, '.', '');
              //  $_vt = number_format(($item->quantidade * ($item->impostonacional / 100 * $item->vlrunitario_nfeitens)), 2, '.', '');
           //  }
               
                //buscar 
              //  echo "xx4xx".$item->vlrunitario_nfeitens."x".$item->quantidade."emp".$this->empresa->empresa_tipo ;
              if($this->empresa->empresa_tipo == '1') {
            //  if( intval($item->situacaotributario_nfeitens) > 100  and $item->situacaotributario_nfeitens != "00") {
               
                $std = new stdClass();
                $std->item = $i; 
          
                       
                $std->orig = $item->origemimposto_nfeitens;
                $std->CSOSN = $item->situacaotributario_nfeitens;
                if(strlen($item->situacaotributario_nfeitens)>= 4){
                    $std->CSOSN = substr($item->situacaotributario_nfeitens,-3);
                    $std->orig = substr($item->situacaotributario_nfeitens,0,1);
                }else{
                    $std->CSOSN = $item->situacaotributario_nfeitens;  
                }
               
                
                if($item->situacaotributario_nfeitens == 101)  {
                    $std->pCredSN = 0;
                    $std->vCredICMSSN = 0;
                }

            
              
                if(  $item->vBC_nfeitens > 0){
             
                    if($item->situacaotributario_nfeitens == 500)  {
                        $std->modBC = 0;
                        $std->vBC = 0;
                        $std->pICMS =0;
                        $std->vICMS = 0;
                        $totalbaseIcms = 0;
                        $totalIcms = 0;
                   
                    }else{
                     
                        $std->modBC = $item->modBC_nfeitens;
                        $std->vBC = $item->vBC_nfeitens;
                        $std->pICMS = $item->pICMS_nfeitens;
                        $std->vICMS = $item->vICMS_nfeitens;
                        $totalbaseIcms = $totalbaseIcms + $item->vBC_nfeitens;
                        $totalIcms = $totalIcms + $item->vICMS_nfeitens;

                    
                        
              }

              
              if($item->vICMSST_nfeitens > 0){
                if($item->modBCST_nfeitens > 0){
                    $std->modBCST = $item->modBCST_nfeitens;
                }else{
                    $std->modBCST = 4;
                }
                if($item->mva_nfeitens > 0){
                    $std->pMVAST = $item->mva_nfeitens;
                }
                $std->vBCST = $item->vBCST_nfeitens;
                $std->pICMSST = $item->pICMSST_nfeitens;
                $std->vICMSST = $item->vICMSST_nfeitens;  

                $totalbaseIcmsST = $totalbaseIcmsST + $item->vBCST_nfeitens;  
               
           
                $totalIcmsST = $totalIcmsST + $item->vICMSST_nfeitens;  
                                 
             }  

            
                 //pFCPST
                 if($item->vlrfcpST_nfeitens > 0){       //fcpST_nfeitens,vlrfcpST_nfeitens            
                    $std->vBCFCPST = $item->vBCST_nfeitens;
                    $std->pFCPST = $item->fcpST_nfeitens;
                    $std->vFCPST = $item->vlrfcpST_nfeitens;  
                  $std->pCredSN =  $item->fcpST_nfeitens;
                   $std->vCredICMSSN = $item->vlrfcpST_nfeitens;
                

                    $totalFCPST = $totalFCPST + $item->vlrfcpST_nfeitens;                
                 }   

                 
                   
                 }   
                $nfe->tagICMSSN($std);
             

                if(  $item->nfe_itensvlrIPI > 0){
                   
                    $std->item = $i; //item da NFe
                    $std->clEnq = null;
                    $std->CNPJProd = null;
                    $std->cSelo = null;
                    $std->qSelo = null;
                    $std->cEnq = '999';
                    $std->CST = '99';
                    $std->vIPI = $item->nfe_itensvlrIPI;
                    $totalIPI = $totalIPI +  $item->nfe_itensvlrIPI;
                    $std->vBC = $item->vBC_nfeitens;
                    $std->pIPI =  $item->nfe_itensIPI;
                    $std->qUnid = null;
                    $std->vUnid = null;
                    $nfe->tagIPI($std);

                }

              
/*
                if ($item->nfe_itensvlrimpostoDevol >= 0.01) {
                                
                    $std->pDevol = $item->nfe_itensPimpostoDevol;
                    $std->vIPIDevol = $item->nfe_itensvlrimpostoDevol;                    
                    $totalDevol  = $totalDevol  + $item->nfe_itensvlrimpostoDevol;

                    $std->item = $i; //item da NFe
                    $std->clEnq = null;
                    $std->CNPJProd = null;
                    $std->cSelo = null;
                    $std->qSelo = null;
                    $std->cEnq = '999';
                    $std->CST = '99';
                    $std->vIPI = $item->nfe_itensvlrIPI;
                    $totalIPI = $totalIPI +  $item->nfe_itensvlrIPI;
                    $std->vBC = $item->vBC_nfeitens;
                    $std->pIPI =  $item->nfe_itensIPI;
                    $std->qUnid = null;
                    $std->vUnid = null;
                    $nfe->tagIPI($std);
                   
                }
                */

                $std = new stdClass();
                $std->item = $i;
                $std->CST = $item->pisCST_nfeitens;//'07';
                if($item->pisCST_nfeitens != "07") {              
                 $std->vBC = 0;//'07';
                 $std->pPIS =0;//'07';
                 $std->vPIS =0;//'07';
                 }
                $nfe->tagPIS($std);

                
                $std = new stdClass();
                $std->item = $i;
                $std->CST = $item->cofins_nfeitens;//'04';
                if($item->cofins_nfeitens != "04") {   
                    $std->vBC = 0;//'04';
                    $std->pCOFINS = 0;//'04';
                    $std->vCOFINS = 0;//'04';
                }
                     $nfe->tagCOFINS($std);

              }else{
              //REGIME NORMAL
                $std = new stdClass();
                $std->item = $i;
                $std->orig = $item->origemimposto_nfeitens;

                $std->CST = $item->situacaotributario_nfeitens ;

                if(strlen($std->CST )>= 3){
                    $std->CST  = substr($item->situacaotributario_nfeitens,-2);
                    $std->orig = substr($item->situacaotributario_nfeitens,0,1);
                }
               
               // echo $std->CST;
               // echo "xx";
            
                if( $std->CST == 0)  { $std->CST = "00";}
                //$CST_CFOP
                if( $item->situacaotributario_nfeiten == '41' and $CST_CFOP == '40')  { $std->CST = "50";}
                if( $item->situacaotributario_nfeiten > '0' ) {
                    $std->modBC = $item->modBC_nfeitens;
                }
               

                //pICMS
         
                if($item->vICMS_nfeitens > 0){  
                    $std->modBC = $item->modBC_nfeitens;
                    $std->vBC = $item->vBC_nfeitens;
                    $std->pICMS = $item->pICMS_nfeitens;
                    $std->vICMS = $item->vICMS_nfeitens;
                    $totalbaseIcms = $totalbaseIcms + $item->vBC_nfeitens;
                    $totalIcms = $totalIcms + $item->vICMS_nfeitens;
                }else{
                    $std->modBC = $item->modBC_nfeitens;
                    $std->vBC = $item->vBC_nfeitens;
                    $std->pICMS = $item->pICMS_nfeitens;
                    $std->vICMS = $item->vICMS_nfeitens;
                    ;

                }
               
                if($item->vICMSST_nfeitens > 0){
                    if($item->modBCST_nfeitens > 0){
                        $std->modBCST = $item->modBCST_nfeitens;
                    }else{
                        $std->modBCST = 4;
                    }
                    if($item->mva_nfeitens > 0){
                        $std->pMVAST = $item->mva_nfeitens;
                    }
                   
                    $std->vBCST = $item->vBCST_nfeitens;
                    $std->pICMSST = $item->pICMSST_nfeitens;
                    $std->vICMSST = $item->vICMSST_nfeitens;  

                    $totalbaseIcmsST = $totalbaseIcmsST + $item->vBCST_nfeitens;  
                   
                    $totalIcmsST = $totalIcmsST + $item->vICMSST_nfeitens;  
                                     
                 }  
                 //pFCPST
                 if($item->vlrfcpST_nfeitens > 0){       //fcpST_nfeitens,vlrfcpST_nfeitens             
                   // $std->pFCPST = $item->fcpST_nfeitens;
                   $std->vBCFCPST = $item->vBCST_nfeitens;
                    $std->vFCPST = $item->vlrfcpST_nfeitens;  
                    $totalFCPST = $totalFCPST + $item->vlrfcpST_nfeitens;                
                 }   

                
                 //vBCSTRet
                 if($item->vlrfcpSTret_nfeitens > 0){                   
                    $std->vBCSTRet = $item->baseIcmsSTret_nfeitens;
                    $std->pST = $item->aliqIcmsSTret_nfeitens;
                       //vICMSSubstituto
                    if($item->vlrIcmsSubstituto_nfeitens > 0){                  
                        $std->vICMSSubstituto = $item->vlrIcmsSubstituto_nfeitens;  
                    }
                    $std->vICMSSTRet = $item->vlrfcpSTret_nfeitens;                   
                 }  
                 
                $nfe->tagICMS($std);

                $totalIPI = 0;

                if(  $item->nfe_itensvlrIPI > 0){
                    $std->item = 1; //item da NFe
                    $std->clEnq = null;
                    $std->CNPJProd = null;
                    $std->cSelo = null;
                    $std->qSelo = null;
                    $std->cEnq = '999';
                    $std->CST = '99';
                    $std->vIPI = $item->nfe_itensvlrIPI;
                    $totalIPI = $totalIPI +  $item->nfe_itensvlrIPI;
                    $std->vBC = $item->vBC_nfeitens;
                    $std->pIPI =  $item->nfe_itensIPI;
                    $std->qUnid = null;
                    $std->vUnid = null;
                    $nfe->tagIPI($std);

                }else{
                    $std->item = 1; //item da NFe
                    $std->clEnq = null;
                    $std->CNPJProd = null;
                    $std->cSelo = null;
                    $std->qSelo = null;
                    $std->cEnq = '999';
                    $std->CST = '53';                
                    $std->qUnid = null;
                    $std->vUnid = null;
                    $nfe->tagIPI($std);


                }

                

                $std = new stdClass();
                $std->item = $i;
                $std->CST = $item->pisCST_nfeitens;//'07';
                if($item->NAT_pPis > 0 and $item->pisCST_nfeitens == '01'  ) {              
                    $std->vBC = $item->vlrunitario_nfeitens*$item->quantidade;//'07';
                    $std->pPIS =$item->NAT_pPis;//'07';
                    $std->vPIS =$item->NAT_pPis * ($item->vlrunitario_nfeitens*$item->quantidade)/100;//'07';
                    }
                    if($item->pisCST_nfeitens == '99'  ) {              
                        $std->vBC = 0;//'07';
                        $std->pPIS =0;//'07';
                        $std->vPIS =0;//'07';
                        }
                $nfe->tagPIS($std);


              
                $std = new stdClass();
                $std->item = $i;
                $std->CST = $item->cofins_nfeitens;//'04';
                if($item->NAT_pCofins >  0 and $item->cofins_nfeitens == '01') {   
                    $std->vBC = $item->vlrunitario_nfeitens*$item->quantidade;//'04';
                    $std->pCOFINS = $item->NAT_pCofins;//'04';
                    $std->vCOFINS = $item->NAT_pCofins * ($item->vlrunitario_nfeitens*$item->quantidade)/100;//'04';
                }
                if($item->cofins_nfeitens == '99') {   
                    $std->vBC = 0;//'04';
                    $std->pCOFINS = 0;//'04';
                    $std->vCOFINS = 0;//'04';
                }
                $nfe->tagCOFINS($std);

              }
              

              if ($item->nfe_itensvlrimpostoDevol >= 0.01) {
                $std = new stdClass();    
                $std->item = $i;     
                $std->pDevol = $item->nfe_itensPimpostoDevol;
                $std->vIPIDevol = $item->nfe_itensvlrimpostoDevol;                    
                $totalDevol  = $totalDevol  + $item->nfe_itensvlrimpostoDevol;

                $nfe->tagimpostoDevol($std);
               
            }

              if($nfed_tributado == 0) {
                $std->vTotTrib = number_format(($item->quantidade * ($item->impostonacional / 100 * $item->vlrunitario_nfeitens)), 2, '.', '');
                $_vt = number_format(($item->quantidade * ($item->impostonacional / 100 * $item->vlrunitario_nfeitens)), 2, '.', '');
             }
              $nfe->tagimposto($std);

              if($item->infAdProd != ""){
                $std = new stdClass();
                $std->item =  $i; //item da NFe
                $std->infAdProd = $item->infAdProd;               
                $nfe->taginfAdProd($std);
              }
            

             
    
                $totalItems = $totalItems + ($item->quantidade * $item->vlrunitario_nfeitens);
                $totalTrib = $totalTrib + ($_vt);
                $i++;
            }

          
            $std = new stdClass;
            $std->vBC =  number_format($totalbaseIcms, 2, '.', ''); 
            $std->vICMS =   number_format($totalIcms, 2, '.', ''); 
            $std->vICMSDeson = 0;
            $std->vFCPUFDest = 0;
            $std->vICMSUFDest = 0;
            $std->vICMSUFRemet = 0;
            $std->vFCP = number_format($totalFCP, 2, '.', ''); ; // 
            $std->vBCST = number_format($totalbaseIcmsST, 2, '.', ''); 
            $std->vST = number_format($totalIcmsST, 2, '.', ''); 
            $std->vFCPST =  number_format($totalFCPST, 2, '.', ''); ;
            $std->vFCPSTRet = 0;
            $std->vProd = number_format($totalItems, 2, '.', '');
            $std->vFrete = $totalFrete;
            $std->vSeg = null;
            $std->vDesc = $totalDesconto;
            $std->vII = 0;
            $std->vIPI = number_format($totalIPI, 2, '.', ''); 
            $std->vIPIDevol = $totalDevol;
            $std->vPIS = 0;
            $std->vCOFINS = 0;
            $std->vOutro = $totalOutros;
            $std->vNF = number_format($totalItems+$totalFrete+$totalOutros-$totalDesconto+$totalIPI+$totalIcmsST+$totalDevol+$totalFCPST, 2, '.', '');
            if($nfed_tributado == 0) {
            $std->vTotTrib = number_format($totalTrib, 2, '.', '');
            }
            $nfe->tagicmstot($std);

            $std = new stdClass;
            $std->modFrete = $nfed_modalidade;          
            $nfe->tagtransp($std);
            if($nfed_tranportadora > 0) {
                $std = new stdClass;
                $nfed_cnpjtransporador = preg_replace('/[^0-9]/', '',trim($nfed_cnpjtransporador));
                $std->CNPJ = $nfed_cnpjtransporador;
                $nfed_ietransporador = preg_replace('/[^0-9]/', '',trim($nfed_ietransporador));
                $std->IE = $nfed_ietransporador;     
                $std->xNome = trim($nfed_nometransporador);   
                $std->xEnder = trim($nfed_enderecotransporador);   
                $std->xMun = trim($nfed_cidadetransporador);   
                if( $nfed_uftransporador != "") {
                    $std->UF = $nfed_uftransporador; 
                }
               
                $nfe->tagtransporta($std);
                
            }
            if($nfed_qtdevolume > 0 or $nfed_bruto > 0) {
                $std = new stdClass;
                $std->qVol = trim($nfed_qtdevolume);      
                $std->nVol = trim($nfed_numerovolume);                 
                $std->esp = trim($nfed_especie);  
                $std->marca = trim($nfed_marca); 
                $std->pesoL =  number_format(trim($nfed_liquido), 2, '.', '');   
                $std->pesoB = number_format(trim($nfed_bruto), 2, '.', '');                
               
                $nfe->tagvol($std);
            }

           //dados da fatura
            if($totalfatura > 0) {
                //nfe faturas
                foreach ($faturas as $fatrow) {
                    $std = new stdClass;
                    $std->nFat =   str_pad($fatrow->Nfat_idnf, 6, 0, STR_PAD_LEFT);
                    $std->vOrig = $fatrow->total;
                    $std->vDesc = 0;
                    $std->vLiq = $fatrow->total;
                    $nfe->tagfat($std);
                }

            
                foreach ($duplicatas as $dup) {                 
                    $std = new stdClass;
                    $std->nDup =    $dup->Nfat_numerofat;; //Código da Duplicata
                    $std->dVenc = $dup->Nfat_vencimento;  //Vencimento               
                    $std->vDup = number_format(trim($dup->Nfat_valor), 2, '.', '');    ;// Valor                
                    $nfe->tagdup($std);
                }

            }

            
            if($finalidade != 4) {
                $std = new stdClass;
                $std->vTroco = $pedido->Valor_Troco;
                $nfe->tagpag($std);

            //    foreach ($pagamentos as $pagamento) {
                    $std = new stdClass;
                    $std->tPag = strval($nfed_codpgto);
                    if(strval($nfed_codpgto) == "99"){                       
                        $std->xPag = trim($nfed_codpgtonome);
                    }
                    if(strval($nfed_codpgto) != "90"){        
                    $std->vPag = number_format($totalItems+$totalFrete-$totalDesconto+$totalOutros, 2, '.', '');
                    }else{
                     
                        $std->vPag = number_format( 0, 2, '.', '');
                       
                    }
                    $nfe->tagdetpag($std);
            //   }
            }else{
                $std->vTroco = $pedido->Valor_Troco;
                $nfe->tagpag($std);

            //    foreach ($pagamentos as $pagamento) {
                    $std = new stdClass;
                    $std->tPag = strval('90');
                    $std->vPag = number_format(0, 2, '.', '');
                    $nfe->tagdetpag($std);
            }
        
 
             $std = new stdClass;
             $std->infAdFisco = '';
             $std->infCpl = "$informacaoAdicionais.$informacaoTributos";
             $nfe->taginfadic($std);

          if( $this->empresa->empresa_uf == "MS"){
            $std = new stdClass();
            $std->CNPJ = '11493284000111';
            $std->xContato = 'Sistema Prisma';
            $std->email = 'contato@sistemaprisma.com.br';
            $std->fone = '41991458007';
            $std->CSRT = 'c2ymfa80CP9GHGiwlBBVVU5yd0Y=';
            $std->idCSRT = '01';
            $nfe->taginfRespTec($std);
        }

        
        if( $this->empresa->empresa_uf == "PR" ){
            $std = new stdClass();
            $std->CNPJ = '11493284000111';
            $std->xContato = 'Sistema Prisma';
            $std->email = 'contato@sistemaprisma.com.br';
            $std->fone = '41991458007';        
            $nfe->taginfRespTec($std);
        }

      
            $nfe->monta();
           
         
            return $nfe->getXML();

        } catch (\Exception $e) {
         
           /* throw new Exception(
                "Erro ao gerar XML: " . $e->getMessage(),
                "Mensagem: ". print_r($nfe->getErrors()));
			*/
            print "<pre>";
                print_r($nfe->getErrors());
            print "</pre>";
        
           return $e->getMessage();
        }  
    }

    /**
     * Assina nota fiscal
     * @param type $xml
     * @return type
     */
    public function assinaNFe($xml)
    {
        return $this->tools->signNFe($xml);
    }

    public function transmitir($xml)
    {
        $response = $this->tools->sefazEnviaLote([$xml], 1, 1);

        $st = new Standardize();
        $stResponse = $st->toStd($response);
    
        if ($stResponse->cStat != 104) {
          
            throw new Exception("[$stResponse->cStat] $stResponse->xMotivo");
        }

        //return $stResponse->protNFe->infProt->chNFe;
        return $response;
        
    }

    public function transmitirNFE($xml)
    {
        $response = $this->tools->sefazEnviaLote([$xml], 1, 1);

      //  $st = new Standardize();
      //  $stResponse = $st->toStd($response);
      /*
        if ($stResponse->cStat != 104) {
          
            throw new Exception("[$stResponse->cStat] $stResponse->xMotivo");
        }
*/
        //return $stResponse->protNFe->infProt->chNFe;
        return $response;
        
    }

     public function transmitirNFE_assincrono ($xml)
    {
        $response = $this->tools->sefazEnviaLote([$xml], 1,0);
        return $response;
        
    }

    public function CancelarNF($chave,$xJust,$nProt )
    {
     
        try {
            $ret = $this->tools->sefazCancela($chave, $xJust, $nProt);
            return $ret;
        } catch (Exception $e) {
            throw new Exception('Erro ao cancelar NF: ' . $e->getMessage());
        }
    }
    public function InutilizarNF($nSerie,$nIni, $nFin,$xJust )
    {
     
        try {
            $ret = $this->tools->sefazInutiliza($nSerie, $nIni, $nFin, $xJust);
            return $ret;
        } catch (Exception $e) {
            throw new Exception('Erro ao inutilizar NF: ' . $e->getMessage());
        }
    }

    public function cartaNF($chave, $xJust, $nSeqEvento)
    {
     
        try {
            $ret = $this->tools->sefazCCe($chave, $xJust, $nSeqEvento);
        
            return $ret;
        } catch (Exception $e) {
            throw new Exception('Erro ao gerar carta NF: ' . $e->getMessage());
        }
    }
    

    

    public function consultaRecibo(String $recibo)
    {
        try {
            $protocolo = $this->tools->sefazConsultaRecibo($recibo);
            return $protocolo;
        } catch (Exception $e) {
            throw new Exception('Erro ao obter protocolo: ' . $e->getMessage());
        }
    }

    public function consultaChave(String $chave)
    {
        try {
            $protocolo = $this->tools->sefazConsultaChave($chave);
            return $protocolo;
        } catch (Exception $e) {
            throw new Exception('Erro ao obter protocolo: ' . $e->getMessage());
        }
    }

    public function autorizaXml($xml, $protocolo)
    {
        try {
            $xmlProtocolado = Complements::toAuthorize($xml, $protocolo);
           // header('Content-type: text/xml; charset=UTF-8');
        } catch (Exception $e) {
            throw new Exception('Erro ao protocolar XML: ' . $e->getMessage());
        }

        return $xmlProtocolado;
    }

    public function emitirNFCe(Int $numPedido, Int $caixa)
    {
        try {
            date_default_timezone_set('America/Sao_Paulo');

            //Gera e assina XML
            $xml = $this->gerarNFCe($numPedido, $caixa);
            $signedXML = $this->assinaNFe($xml);

            //Grava XML no banco e incrementa número de NF
            $consulta = $this->pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numPedido' AND nfed_numlivro = '$caixa'");
            $xml = $consulta->fetch(PDO::FETCH_OBJ);

            if (!$xml) {
                $dataNFC = date('Y-m-d H:m:s');

                $insert = $this->pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_pedido, nfed_numlivro, nfed_xml, nfed_hora) VALUES(?, ?, ?, ?)");
                $insert->bindParam(1, $numPedido);
                $insert->bindParam(2, $caixa);
                $insert->bindParam(3, $signedXML);
                $insert->bindParam(4, $dataNFC);
                $insert->execute();
    
                $update = $this->pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET proximo_numero_nfe_producao = proximo_numero_nfe_producao + 1 WHERE id = ?");
                $update->bindParam(1, $this->empresa->id);
                $update->execute();

            } else {
                $dataNFC = date('Y-m-d H:m:s');

                $update = $this->pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?, nfed_hora = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
                $update->bindParam(1, $signedXML);
                $update->bindParam(2, $dataNFC);
                $update->bindParam(3, $numPedido);
                $update->bindParam(4, $caixa);
                $update->execute();
            }

            $consulta = $this->pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numPedido' AND nfed_numlivro = '$caixa'");
            $xml = $consulta->fetch(PDO::FETCH_OBJ);

            //Transmite XML 65
           

            $recibo = $this->transmitir($xml->nfed_xml);

            //Grava recibo
            $update = $this->pdo->prepare("UPDATE SET ".$_SESSION['BASE'].".NFE_DADOS nfed_recibo = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
            $update->bindParam(1, $recibo);
            $update->bindParam(2, $numPedido);
            $update->bindParam(3, $caixa);
            $update->execute();

            $consulta = $this->pdo->query("SELECT nfed_recibo FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numPedido' AND nfed_numlivro = '$caixa'");
            $recibo = $consulta->fetch(PDO::FETCH_OBJ);

            //Obtem protocolo e gera XML protocolado
            $protocolo = $this->consultaRecibo($recibo);

            $verificaProtocolo = new Standardize();
            $verificaProtocolo = $verificaProtocolo->toStd($protocolo);

            if ($verificaProtocolo->cStat != '104') {
                $update = $this->pdo->prepare("UPDATE SET ".$_SESSION['BASE'].".NFE_DADOS nfed_motivo = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
                $update->bindParam(1, $verificaProtocolo->protNFe->infProt->xMotivo);
                $update->bindParam(2, $numPedido);
                $update->bindParam(3, $caixa);
                $update->execute();
            } else {
                $xmlProtocolado = $this->autorizaXml($xml->nfed_xml, $protocolo);
                $dataProtocolo = date('Y-m-d H:m:s');

                $update = $this->pdo->prepare("UPDATE SET ".$_SESSION['BASE'].".NFE_DADOS nfed_dataautorizacao = ?, nfed_xml_protocolado = ?, nfed_motivo = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
                $update->bindParam(1, $dataProtocolo);
                $update->bindParam(2, $xmlProtocolado);
                $update->bindParam(3, $verificaProtocolo->protNFe->infProt->xMotivo);
                $update->bindParam(4, $numPedido);
                $update->bindParam(5, $caixa);
                $update->execute();
            }

        } catch (\Exception $e) {
            echo $e;
        }
    }
}