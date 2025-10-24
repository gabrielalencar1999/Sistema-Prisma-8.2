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
use DOMDocument;

class NFeManifesto {

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
         $empresa = 9079;
         //  $stm = $this->pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa_cadastro e LEFT JOIN empresa_dados d ON e.id = d.id LEFT JOIN minhaos_cep.cidade c ON e.uf = c.estado WHERE e.id = '$empresa'");
          $sql = "SELECT * FROM ".$_SESSION['BASE'].".empresa as e WHERE e.empresa_id = '$empresa'";          
         
          $stm = $this->pdo->query("SELECT * FROM bd_novo.empresa as e WHERE e.empresa_id = '9079'");
          $empresa = $stm->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao consultar dados: " . $e->getMessage());
        }
      
    /*
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

         $config = [
                "atualizacao" => date('Y-m-d H:i:s'),
                "tpAmb" => 1, // 1=produção, 2=homologação
                "razaosocial" =>  $empresa->empresa_razaosocial,
                "siglaUF" => $empresa->empresa_uf,
                "cnpj" => $empresa->empresa_cnpj,
                "schemes" => "PL_MDFe_300b",
                "versao" => "3.00b",
                "tokenIBPT" => "",
                "CSC" => "",
                "CSCid" => ""
            ];
           */

            $config = [
                "atualizacao" => date('Y-m-d H:i:s'),
                "tpAmb" => 2,
                "razaosocial" => 'FÁBRICA DE SOFTWARE MATRIZ',
                "cnpj" => '06157250000116',
                "ie" => '',
                "siglaUF" => 'PR',
                "versao" => '3.00'
            ];
  
        $configJson = json_encode($config);
        
        $tools = new Tools($configJson, Certificate::readPfx(base64_decode($empresa->arquivo_certificado_base64), $empresa->senha_certificado));
        $this->tools->model(58); // ✅ INDICA O MODELO CORRETO
           print_r($tools );  
          exit();
        $soapCurl = new SoapCurl();
        $tools->loadSoapClass($soapCurl);
        $tools->soap->httpVersion('1.1');
        //$tools->model($modelo);       
      
        $this->config = $configJson;
        $this->empresa = $empresa;      
        $this->tools = $tools;    
          print_r( $this->tools);  
          exit();
       /*
        /*
        $configJson = json_encode($config);
            
            // Inicializar Tools para MDF-e
            $this->tools = new Tools($configJson, Certificate::readPfx(
                base64_decode($this->empresa->arquivo_certificado_base64), 
                $this->empresa->senha_certificado
            ));
            
            // Configurar SOAP
            $soapCurl = new SoapCurl();
            $this->tools->loadSoapClass($soapCurl);
            $this->tools->soap->httpVersion('1.1');
            
            $this->config = $configJson; 
            */

    }
//***************************************************************************************************************************************************************** */
  /**
     * Gerar MDF-e completo
     */
    public function gerarMDFe($dados)
    {
        try {
            $mdfe = new Make();
            
            // 1. Informações básicas
            $this->adicionarInformacoesBasicas($mdfe, $dados);
            
            // 2. Identificação
            $this->adicionarIdentificacao($mdfe, $dados);
            
            // 3. Emitente
            $this->adicionarEmitente($mdfe, $dados);
            
            // 4. Modal rodoviário
            $this->adicionarModalRodoviario($mdfe, $dados);
            
            // 5. Percurso
            $this->adicionarPercurso($mdfe, $dados);
            
            // 6. Documentos vinculados
            $this->adicionarDocumentos($mdfe, $dados);
            
            // 7. Informações adicionais
            $this->adicionarInformacoesAdicionais($mdfe, $dados);
            
            // 8. Gerar XML
            $xml = $mdfe->getXML();
            
            if (!$xml) {
                throw new Exception("Erro ao gerar XML do MDF-e");
            }
            
            return [
                'sucesso' => true,
                'xml' => $xml,
                'dados' => $dados
            ];
            
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ];
        }
    }
    
    /**
     * Assinar MDF-e
     */
    public function assinarMDFe($xml)
    {
        try {
            $xmlAssinado = $this->tools->signMDFe($xml);
            
            if (!$xmlAssinado) {
                throw new Exception("Erro ao assinar XML");
            }
            
            return [
                'sucesso' => true,
                'xml_assinado' => $xmlAssinado
            ];
            
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Enviar MDF-e para SEFAZ
     */
    public function enviarMDFe($xmlAssinado, $lote = null)
    {
        try {
            $idLote = $lote ?: date('YmdHis') . sprintf('%06d', mt_rand(1, 999999));
            
            $resposta = $this->tools->sefazEnviaLote([$xmlAssinado], $idLote);
            
            return [
                'sucesso' => true,
                'resposta' => $resposta,
                'lote' => $idLote
            ];
            
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Processar resposta da SEFAZ
     */
    public function processarResposta($resposta)
    {
        try {
            if (!$resposta) {
                return [
                    'autorizado' => false,
                    'erro' => 'Resposta vazia da SEFAZ'
                ];
            }
            
            $stdResp = json_decode($resposta);
            
            if (!$stdResp || $stdResp->httpcode != 200) {
                return [
                    'autorizado' => false,
                    'erro' => 'Erro HTTP: ' . ($stdResp->httpcode ?? 'desconhecido')
                ];
            }
            
            $xmlResp = $stdResp->body ?? '';
            
            if (!$xmlResp) {
                return [
                    'autorizado' => false,
                    'erro' => 'Resposta XML vazia'
                ];
            }
            
            // Analisar XML de resposta
            $dom = new DOMDocument();
            $dom->loadXML($xmlResp);
            
            $cStats = $dom->getElementsByTagName('cStat');
            $xMotivs = $dom->getElementsByTagName('xMotiv');
            $protocolos = $dom->getElementsByTagName('nProt');
            
            $codigo = $cStats->length > 0 ? $cStats->item(0)->nodeValue : '';
            $motivo = $xMotivs->length > 0 ? $xMotivs->item(0)->nodeValue : '';
            $protocolo = $protocolos->length > 0 ? $protocolos->item(0)->nodeValue : '';
            
            $autorizado = ($codigo == '100');
            
            return [
                'autorizado' => $autorizado,
                'codigo' => $codigo,
                'motivo' => $motivo,
                'protocolo' => $protocolo,
                'xml_resposta' => $xmlResp
            ];
            
        } catch (Exception $e) {
            return [
                'autorizado' => false,
                'erro' => 'Erro ao processar resposta: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Consultar situação do MDF-e
     */
    public function consultarMDFe($chave)
    {
        try {
            $resposta = $this->tools->sefazConsultaChave($chave);
            
            return [
                'sucesso' => true,
                'resposta' => $resposta
            ];
            
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Salvar arquivo XML
     */
    public function salvarXML($xml, $nome = null, $pasta = 'xml/')
    {
        try {
            if (!is_dir($pasta)) {
                mkdir($pasta, 0755, true);
            }
            
            $nomeArquivo = $nome ?: 'mdfe_' . date('Y-m-d_H-i-s') . '.xml';
            $caminhoCompleto = $pasta . $nomeArquivo;
            
            file_put_contents($caminhoCompleto, $xml);
            
            return [
                'sucesso' => true,
                'arquivo' => $caminhoCompleto
            ];
            
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    // ========================================
    // MÉTODOS PRIVADOS PARA MONTAGEM DO XML  // EXEMPLO DE USO COMPATÍVEL PHP 7.x
    // ========================================
    
      private function adicionarInformacoesBasicas($mdfe, $dados)
    {
        $mdfe->taginfMDFe(
            $dados['numero'] ?? 1,
            $dados['codigo_uf'] ?? '35',
            $dados['uf_carregamento'] ?? 'SP',
            'MDFE'
        );
    }
    
   private function adicionarIdentificacao($mdfe, $dados)
    {
        // CORREÇÃO: Usar array associativo em vez de named arguments
        $parametros = [
            $dados['codigo_uf'] ?? '35',        // cUF
            $dados['ambiente'] ?? 2,            // tpAmb
            $dados['tipo_emitente'] ?? 1,       // tpEmit
            null,                               // tpTransp
            '58',                               // mod
            $dados['serie'] ?? 1,               // serie
            $dados['numero'] ?? 1,              // nMDF
            $dados['codigo_mdfe'] ?? str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT), // cMDF
            1,                                  // cDV
            $dados['modal'] ?? 1,               // modal
            $dados['data_emissao'] ?? date('Y-m-d\TH:i:sP'), // dhEmi
            1,                                  // tpEmis
            0,                                  // procEmi
            '1.0.0',                           // verProc
            $dados['uf_inicio'] ?? 'SP',        // UFIni
            $dados['uf_fim'] ?? 'RJ'            // UFFim
        ];
        
        call_user_func_array([$mdfe, 'tagide'], $parametros);
    }
    
   private function adicionarEmitente($mdfe, $dados)
    {
        $emitente = $dados['emitente'] ?? [];
        
        // Dados do emitente
        $mdfe->tagemit(
            $emitente['cnpj'] ?? '12345678000195',
            $emitente['ie'] ?? '123456789012',
            $emitente['xnome'] ?? 'TRANSPORTES EXEMPLO LTDA',
            $emitente['nome_fantasia'] ?? 'TRANSEX'
        );
        
        // Endereço do emitente
        $endereco = $emitente['endereco'] ?? [];
        
        $mdfe->tagenderEmit(
            $endereco['logradouro'] ?? 'Rua das Flores',
            $endereco['numero'] ?? '100',
            null, // xCpl
            $endereco['bairro'] ?? 'Centro',
            $endereco['codigo_municipio'] ?? '3550308',
            $endereco['municipio'] ?? 'São Paulo',
            $endereco['uf'] ?? 'SP',
            $endereco['cep'] ?? '01310100',
            null, // cPais
            null, // xPais
            $endereco['telefone'] ?? '1133334444'
        );
    }
    
   Private function adicionarModalRodoviario($mdfe, $dados)
    {
        // Modal rodoviário
        $mdfe->tagrodo();
        
        // Veículo trator
        $veiculo = $dados['veiculo'] ?? [];
        
        $mdfe->tagveicTracao(
            $veiculo['codigo_interno'] ?? '001',
            $veiculo['placa'] ?? 'ABC1234',
            $veiculo['renavam'] ?? '12345678901',
            $veiculo['tara'] ?? 6500,
            $veiculo['capacidade_kg'] ?? 15000,
            $veiculo['capacidade_m3'] ?? 45,
            $veiculo['tipo_rodado'] ?? '03',
            $veiculo['tipo_carroceria'] ?? '02',
            $veiculo['uf_veiculo'] ?? 'SP'
        );
        
        // Motoristas
        $motoristas = $dados['motoristas'] ?? [$dados['motorista'] ?? []];
        
        foreach ($motoristas as $motorista) {
            $mdfe->tagcondutor(
                $motorista['nome'] ?? 'João da Silva Santos',
                $motorista['cpf'] ?? '12345678901'
            );
        }
    }
    
    private function adicionarPercurso($mdfe, $dados)
    {
        // Município de carregamento
        $carregamento = $dados['municipio_carregamento'] ?? [];
        
        $mdfe->taginfMunCarrega(
            $carregamento['codigo'] ?? '3550308',
            $carregamento['nome'] ?? 'São Paulo'
        );
        
        // Estados do percurso (opcional)
        $percurso = $dados['percurso'] ?? [];
        foreach ($percurso as $uf) {
            $mdfe->taginfPercurso($uf);
        }
    }
    
    private function adicionarDocumentos($mdfe, $dados)
    {
        // Municípios de descarga
        $descargas = $dados['municipios_descarga'] ?? [];
        
        foreach ($descargas as $descarga) {
            $mdfe->taginfMunDescarga(
                $descarga['codigo'] ?? '3304557',
                $descarga['nome'] ?? 'Rio de Janeiro'
            );
            
            // Documentos para esta descarga
            $documentos = $descarga['documentos'] ?? [];
            
            foreach ($documentos as $doc) {
                if ($doc['tipo'] == 'NFe') {
                    $mdfe->taginfNFe($doc['chave']);
                } elseif ($doc['tipo'] == 'CTe') {
                    $mdfe->taginfCTe($doc['chave']);
                }
            }
        }
    }
    
    private function adicionarInformacoesAdicionais($mdfe, $dados)
    {
        $infAdic = $dados['informacoes_adicionais'] ?? [];
        
        if (!empty($infAdic['observacoes']) || !empty($infAdic['fisco'])) {
            $mdfe->taginfAdic(
                $infAdic['fisco'] ?? null,
                $infAdic['observacoes'] ?? null
            );
        }
    }
}

//    / ============================================
// EXEMPLO DE USO DA CLASSE
// ============================================

