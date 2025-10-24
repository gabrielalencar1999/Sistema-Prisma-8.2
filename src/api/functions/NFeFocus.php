<?php

namespace Functions;

use Database\MySQL;
use Functions\Web_request;

use PDO;
use PDOException;
use Exception;
use stdClass;

class NFeFocus {

    /**
     * @var string
     */
    private $config;
    /**
     * @var object
     */
    private $pdo;
    /**
     * @var string
     */
    private $ambiente;
    /**
     * @var string
     */
    private $url_stg;
    /**
     * @var string
     */
    private $url_prd;
    /**
     * @var object
     */
    private $empresa;
    /**
     * @var object
     */
    private $webRequest;

    public function __construct(Int $empresa, String $ambiente)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $this->pdo = MySQL::acessabd();
    
        try {
            $stm = $this->pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa  WHERE empresa_id = '$empresa'");
            $empresa = $stm->fetch(PDO::FETCH_OBJ);
          
        } catch (PDOException $e) {
            throw new Exception("Erro ao consultar dados: " . $e->getMessage());
        }

        $this->ambiente = $ambiente;
        $this->url_prd = "https://api.focusnfe.com.br/v2/";
      // $this->url_stg = "https://homologacao.focusnfe.com.br/v2/";
    // $this->url_prd = "https://homologacao.focusnfe.com.br/v2/";
        $this->url_stg = "https://homologacao.focusnfe.com.br/";
        $this->empresa = $empresa;      
        $this->webRequest = new WebRequest;

        $this->token_producao = $this->empresa->token_producao;
       // $this->token_homologacao = $this->empresa->token_homologacao;
        

    }
    
    public function cadastraEmpresa()
    {
      /*  $cadastro = new stdClass;
        $cadastro->nome = $this->empresa->razao_social;
        $cadastro->nome_fantasia = $this->empresa->nome_fantasia;
        $cadastro->cnpj = $this->empresa->cnpj;
        $cadastro->bairro = $this->empresa->bairro;
        $cadastro->cep = $this->empresa->cep;
        $cadastro->complemento = $this->empresa->complemento;
        $cadastro->email = $this->empresa->email;
        $cadastro->inscricao_estadual = $this->empresa->inscricao_estadual;
        $cadastro->inscricao_municipal = $this->empresa->inscricao_municipal;
        $cadastro->logradouro = $this->empresa->logradouro;
        $cadastro->numero = $this->empresa->numero;
        $cadastro->regime_tributario = $this->empresa->regime_tributario;
        $cadastro->telefone = $this->empresa->telefone;
        $cadastro->municipio = $this->empresa->municipio;
        $cadastro->uf = $this->empresa->uf;
        $cadastro->enviar_email_destinatario = $this->empresa->enviar_email_destinatario;
        $cadastro->discrimina_impostos = $this->empresa->discrimina_impostos;
        $cadastro->cpf_cnpj_contabilidade = $this->empresa->cpf_cnpj_contabilidade;
        $cadastro->habilita_nfe = $this->empresa->habilita_nfe;
        $cadastro->habilita_nfce = $this->empresa->habilita_nfce;
        $cadastro->habilita_nfse = $this->empresa->habilita_nfse;	
        $cadastro->csc_nfce_producao = $this->empresa->csc_nfce_producao;
        $cadastro->id_token_nfce_producao = $this->empresa->id_token_nfce_producao;
        $cadastro->csc_nfce_homologacao = $this->empresa->csc_nfce_homologacao;
        $cadastro->id_token_nfce_homologacao = $this->empresa->id_token_nfce_homologacao;
        $cadastro->proximo_numero_nfe_producao	= $this->empresa->proximo_numero_nfe_producao;
        $cadastro->proximo_numero_nfe_homologacao = $this->empresa->proximo_numero_nfe_homologacao;
        $cadastro->serie_nfe_producao = $this->empresa->serie_nfe_producao;
        $cadastro->serie_nfe_homologacao = $this->empresa->serie_nfe_homologacao;
        $cadastro->proximo_numero_nfse_producao = $this->empresa->proximo_numero_nfse_producao;
        $cadastro->proximo_numero_nfse_homologacao	= $this->empresa->proximo_numero_nfse_homologacao;
        $cadastro->serie_nfse_producao = $this->empresa->serie_nfse_producao;
        $cadastro->serie_nfse_homologacao = $this->empresa->serie_nfse_homologacao;
        $cadastro->arquivo_certificado_base64 = $this->empresa->arquivo_certificado_base64;
        $cadastro->senha_certificado = $this->empresa->senha_certificado;
        $cadastro->arquivo_logo_base64 = $this->empresa->arquivo_logo_base64;

        $config['url'] = $this->ambiente == 'prd' ? $this->url_prd."empresas" : $this->url_stg."empresa";
        $config['token'] = getenv('TOKEN');
        $config['post_fields'] = (array) $cadastro;

        $request = new WebRequest;
        $request->initialize($config);
        $response = $request->post();

        if ($response->http_code == '200' || $response->http_code == '201') {
          /*
            $stm = $this->pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET api_id = ?, token_producao = ?, token_homologacao = ? WHERE id = ?");
            $stm->bindParam(1, $response->id);
            $stm->bindParam(2, $response->token_producao);
            $stm->bindParam(3, $response->token_homologacao);
            $stm->bindParam(4, $this->empresa->id);
            $stm->execute();
           / /

            return true;
          
        } else {
            return $response;
        }
        */
    }

    public function atualizaEmpresa(){
    /*    $cadastro = new stdClass;
        $cadastro->nome = $this->empresa->razao_social;
        $cadastro->nome_fantasia = $this->empresa->nome_fantasia;
        $cadastro->cnpj = $this->empresa->cnpj;
        $cadastro->bairro = $this->empresa->bairro;
        $cadastro->cep = $this->empresa->cep;
        $cadastro->complemento = $this->empresa->complemento;
        $cadastro->email = $this->empresa->email;
        $cadastro->inscricao_estadual = $this->empresa->inscricao_estadual;
        $cadastro->inscricao_municipal = $this->empresa->inscricao_municipal;
        $cadastro->logradouro = $this->empresa->logradouro;
        $cadastro->numero = $this->empresa->numero;
        $cadastro->regime_tributario = $this->empresa->regime_tributario;
        $cadastro->telefone = $this->empresa->telefone;
        $cadastro->municipio = $this->empresa->municipio;
        $cadastro->uf = $this->empresa->uf;
        $cadastro->enviar_email_destinatario = $this->empresa->enviar_email_destinatario;
        $cadastro->discrimina_impostos = $this->empresa->discrimina_impostos;
        $cadastro->cpf_cnpj_contabilidade = $this->empresa->cpf_cnpj_contabilidade;
        $cadastro->habilita_nfe = $this->empresa->habilita_nfe;
        $cadastro->habilita_nfce = $this->empresa->habilita_nfce;
        $cadastro->habilita_nfse = $this->empresa->habilita_nfse;	
        $cadastro->csc_nfce_producao = $this->empresa->csc_nfce_producao;
        $cadastro->id_token_nfce_producao = $this->empresa->id_token_nfce_producao;
        $cadastro->csc_nfce_homologacao = $this->empresa->csc_nfce_homologacao;
        $cadastro->id_token_nfce_homologacao = $this->empresa->id_token_nfce_homologacao;
        $cadastro->proximo_numero_nfe_producao	= $this->empresa->proximo_numero_nfe_producao;
        $cadastro->proximo_numero_nfe_homologacao = $this->empresa->proximo_numero_nfe_homologacao;
        $cadastro->serie_nfe_producao = $this->empresa->serie_nfe_producao;
        $cadastro->serie_nfe_homologacao = $this->empresa->serie_nfe_homologacao;
        $cadastro->proximo_numero_nfse_producao = $this->empresa->proximo_numero_nfse_producao;
        $cadastro->proximo_numero_nfse_homologacao	= $this->empresa->proximo_numero_nfse_homologacao;
        $cadastro->serie_nfse_producao = $this->empresa->serie_nfse_producao;
        $cadastro->serie_nfse_homologacao = $this->empresa->serie_nfse_homologacao;
        $cadastro->arquivo_certificado_base64 = $this->empresa->arquivo_certificado_base64;
        $cadastro->senha_certificado = $this->empresa->senha_certificado;
        $cadastro->arquivo_logo_base64 = $this->empresa->arquivo_logo_base64;

    
      
        

        $config['url'] = $this->ambiente == 'prd' ? $this->url_prd."empresas/".$this->empresa->api_id : $this->url_stg."empresas/".$this->empresa->api_id;
        $config['token'] = getenv('TOKEN');
        $config['post_fields'] = (array) $cadastro;

        $request = new WebRequest;
        $request->initialize($config);
        $response = $request->put();
        //print_r($response);

        if ($response->http_code == '200' || $response->http_code == '201') {
            return true;
        } else {
            return $response;
        } 
        */
    }

    public function consultaItemServico($codigoMunicipio = NULL)
    {
        $codigoMunicipio == NULL ? $codigoMunicipio = $this->empresa->codigo_municipio : '';
        $config['url'] = $this->url_prd."municipios/$codigoMunicipio/itens_lista_servico";
        $config['token'] = getenv('TOKEN');

        $request = $this->webRequest;
        $request->initialize($config);
        $response = $request->get();

        if ($response->http_code == '200' || $response->http_code == '201') {
            $response = $response->body;
            return $response;
        } else {
            return false;
        }
    }

public function gerarMDFe($_ref, array  $mdfe) //, bool $integral
    {
      // $mdfe = json_encode($mdfe);//JSON_NUMERIC_CHECK
      // $mdfe = json_encode($mdfe, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
     //  print_r($mdfe);

        $config['url'] = $this->ambiente == 'prd' ? $this->url_prd."mdfe?ref=".$_ref : $this->url_stg."mdfe?ref=".$_ref;
        //$config['token'] = getenv('TOKEN');
        $config['token'] = $this->token_producao;

       
        //echo $this->url_prd."nfse?ref=".$this->empresa->api_id."aqui <br.";
         $config['post_fields'] = (array) $mdfe;

        $request = new WebRequest;
        $request->initialize($config);
        $response = $request->post();

       // if ($response->http_code == '200' || $response->http_code == '201') {
        if ($response->http_code == '200' || $response->http_code == '201' || $response->http_code == '202') {
            return true;        
        } else {
        
            return $response;
        }

    }

     public function ConsultarMDFE($referencia)
    {
        // $nfse = array();
        $config['url'] = $this->url_prd."mdfe/".$referencia;
     
        //$config['token'] = getenv('TOKEN');
        $config['token'] =$this->token_producao;
       // $config['post_fields'] = (array) $nfse;
        $request = $this->webRequest;
        $request->initialize($config);
        $response = $request->get();
     //  print_r($response);
        if ($response->http_code == '200' || $response->http_code == '201') {
            $response = $response->body;
            return $response;
        } else {
            return $response;
        }
    }

    public function gerarNFSe($_ref, array  $nfse) //, bool $integral
    {

       $nfse_json = json_encode($nfse);//JSON_NUMERIC_CHECK
     // print_r($nfse_json);
    //  exit();
   

        $config['url'] = $this->ambiente == 'prd' ? $this->url_prd."nfse?ref=".$_ref : $this->url_stg."nfse?ref=".$_ref;
        //$config['token'] = getenv('TOKEN');
        $config['token'] = $this->token_producao;
        //echo $this->url_prd."nfse?ref=".$this->empresa->api_id."aqui <br.";
         $config['post_fields'] = (array) $nfse;

        $request = new WebRequest;
        $request->initialize($config);
        $response = $request->post();

       // if ($response->http_code == '200' || $response->http_code == '201') {
        if ($response->http_code == '200' || $response->http_code == '201' || $response->http_code == '202') {
            return true;        
        } else {
            return $response;
        }

    }


    public function ConsultarNFSe($referencia)
    {
     
        $nfse = array();
        $config['url'] = $this->url_prd."nfse/".$referencia;
        
        //$config['token'] = getenv('TOKEN');
        $config['token'] =$this->token_producao;
        $config['post_fields'] = (array) $nfse;
        $request = $this->webRequest;
        $request->initialize($config);
        $response = $request->get();
      //  print_r($response);
        if ($response->http_code == '200' || $response->http_code == '201') {
            $response = $response->body;
            return $response;
        } else {
            return $response;
        }



    }

    public function CancelamentoNFSe($referencia,$motivo)
    {  
        $justificativa = array ("justificativa" => $motivo);
        $config['url'] = $this->url_prd."nfse/".$referencia;
        
        //$config['token'] = getenv('TOKEN');
        $config['token'] = $this->token_producao;
        $config['post_fields'] = (array) $justificativa;
        $request = $this->webRequest;
        $request->initialize($config);
        $response = $request->delete();
          
        return $response;
    }

   public function CancelamentoMdfe($referencia,$motivo)
   {
        $justificativa = array ("justificativa" => $motivo);     // precisa ser JSON
        $config['url'] = $this->url_prd."mdfe/".$referencia;     
        //$config['token'] = getenv('TOKEN');
        $config['token'] = $this->token_producao;
        $config['post_fields'] = (array) $justificativa;   
        $request = $this->webRequest;      
        $request->initialize($config);
        $response = $request->delete();
          
        return $response;
    }


    public function EncerramentoMDFe($_ref, array $mdfe) //, bool $integral
    {
    print_r($mdfe);
        $config['url'] = $this->url_prd."mdfe?ref=".$_ref."/encerrar";
        //$config['token'] = getenv('TOKEN');
        $config['token'] = $this->token_producao;
         $config['post_fields'] = (array) $mdfe;

        $request = new WebRequest;
        $request->initialize($config);
        $response = $request->post();

       // if ($response->http_code == '200' || $response->http_code == '201') {
        if ($response->http_code == '200' || $response->http_code == '201' || $response->http_code == '202') {
            return $response;     
        } else {        
            return $response;
        }

    }

    
}