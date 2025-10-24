<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   


use Functions\NFeService;
use Database\MySQL;
use Functions\NFeFocus;
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

$nfed_empresa = $_parametros["id-empresa"];

$_idref = $_parametros['nf-id'];
if($_idref == "") {     
    $_idref = $_parametros['id-nota'];
}

$query = $pdo->query("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC  
FROM  " . $_SESSION['BASE'] . ".parametro  ");
$retornoItem = $query->fetch();

$_vizCodInterno = $retornoItem['empresa_vizCodInt'];
if ($_POST["acao"] == 11) { 
    try{
        
        $_cliente =  $retornoNF["nfed_cliente"];
        $id=  $retornoNF["nfed_id"];
  
        $NUMERONF = $retornoNF["nfed_numeronf"];
        $tipnf = $retornoNF["nfed_tipodocumento"];
        $modalidade = $retornoNF["nfed_modalidade"];
        $finalidade = $retornoNF["nfed_finalizade"];
        $operacao =  $retornoNF["nfed_operacao"];
        $tipocontribuinte = $retornoNF["nfed_tipocontribuinte"];
        $_txtadd = $retornoNF['nfed_informacaoAdicionais'];
        $codpgto = $retornoNF["nfed_codpgto"];        
        $cancelada = $retornoNF["nfed_cancelada"];
        //dados gravado cliente
        $c_nomecliente = $retornoNF["nfed_dNome"];
        $c_cpfcnpj = $retornoNF["nfed_cpfcnpj"];
        $c_ie = $retornoNF["nfed_ie"];
        $c_endereco = $retornoNF["nfed_dEdereco"];
        $c_numrua= $retornoNF["nfed_dnumrua"];
        $c_bairro = $retornoNF["nfed_dBairro"];
        $c_cidade = $retornoNF["nfed_dCidade"];      
        $c_cep = $retornoNF["nfed_dCEP"];       
        $c_uf = $retornoNF["nfed_dUF"];
        $c_telefone = $retornoNF["nfed_dTelefone"];
        $c_email = $retornoNF["nfed_email"];
        $empresa_serie  = $retornoNF["nfed_serie"];
        $nfed_totalnota  = $retornoNF["nfed_totalnota"];
        $nfed_basecalculo  = $retornoNF["nfed_basecalculo"];
        $nfed_valorISS  = $retornoNF["nfed_valorISS"];
        $nfed_aliquotaISS  = $retornoNF["nfed_aliquotaISS"];
        $nfed_abatimentoIptu  = $retornoNF["nfed_abatimentoIptu"];

        $nfed_valorISSretido  = $retornoNF["nfed_valorISSretido"]; 


        $_vlbase = LimpaVariavel($_parametros['nota-base']);  
        $_aliquota= LimpaVariavel($_parametros['nota-aliquota']); 
        $_vliss= LimpaVariavel($_parametros['nota-iss']);   
        $valor_iss_retido =  LimpaVariavel($_parametros['nota-issretido']);   
        $iss_retido = 'false';
     
        $_vliptu= LimpaVariavel($_parametros['nota-abatimento-iptu']);   
        $_vldeducoes= LimpaVariavel($_parametros['nota-deducoes']);   
        
        $_vlservico = LimpaVariavel($_parametros['nota-total']);    
              $idempresa=  $_parametros["nf-empresa"];
        if($idempresa <= 0){
            $idempresa = 1;
        }
        
        $_sql = "UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET   
        nfed_totalnota = ?,
        nfed_informacaoAdicionais = ?,
        nfed_basecalculo= ?,nfed_abatimentoIptu= ?,nfed_valorISS= ?,nfed_aliquotaISS = ?,nfed_valorISSretido = ? ,  nfed_totaldesconto = ?,
        nfed_empresa = ?
        WHERE nfed_id = ? and  nfed_modelo = '90'";
        $statement = $pdo->prepare("$_sql");     
      
        $_status = 9;//"9 processando 10 emitida 11 cancelada"  ;
        //$ativit = new atividade();
        
                   
        $statement->bindParam(1, $_vlservico);// 
        $statement->bindParam(2, $_parametros['nf-informacaoAdicionais']);
        $statement->bindParam(3, $_vlbase);
        $statement->bindParam(4, $_vliptu);
        $statement->bindParam(5,  $_vliss);
        $statement->bindParam(6, $_aliquota);
        $statement->bindParam(7, $valor_iss_retido);
        $statement->bindParam(8, $_vldeducoes);
        $statement->bindParam(9, $idempresa);
        $statement->bindParam(10, $_idref);
        $statement->execute();
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
}


if ($_POST["acao"] == 1) { 
            try{
            
     
                //$empresa-$cliente-$_baseid-$pedido-$caixa
          

                $SQL = "SELECT nfed_cancelada,nfed_codpgto,nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente, DATE_FORMAT(nfed_data,'%d/%m/%Y') AS DT,nfed_totalnota,nfed_frete,
                nfed_empresa,nfed_finalizade,nfed_operacao,nfed_tipocontribuinte,nfed_tranportadora,nfed_modalidade,
                nfed_operacao,nfed_finalizade,nfed_tipodocumento,
                nfed_qtde,nfed_qtdevolume,nfed_especie,nfed_marca,	nfed_numerovolume,nfed_bruto,nfed_liquido,
                nfed_textofatura,nfed_informacaoAdicionais,nfed_motivo,nfed_chavedev1,nfed_chave,nfed_protocolo,nfed_serie,nfed_cfop,
                nfed_dNome,nfed_dEdereco,nfed_dBairro,nfed_dCidade,nfed_dUF,nfed_dTelefone,nfed_dCEP,nfed_cpfcnpj,nfed_email,nfed_dnumrua,nfed_email,nfed_ie,
                nfed_cfopid,nfed_basecalculo,nfed_abatimentoIptu,nfed_valorISS,nfed_aliquotaISS,nfed_valorISSretido FROM ".$_SESSION['BASE'].".NFE_DADOS
                LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
                WHERE nfed_id = '$_idref' and nfed_modelo = '90'";
               
                    $statement = $pdo->query("$SQL");
                    $retornoNF = $statement->fetch();
                    $nfed_MODELO  = $retornoNF["nfed_modelo"];
                
                  
                    $nfed_empresa  = $retornoNF["nfed_empresa"];
                    if( $nfed_empresa == 0) { 
                        $nfed_empresa = 1;
                    }
                   
                    $_cliente =  $retornoNF["nfed_cliente"];
                    $id=  $retornoNF["nfed_id"];
                    $NUMERONF = $retornoNF["nfed_numeronf"];
                    $tipnf = $retornoNF["nfed_tipodocumento"];
                    $modalidade = $retornoNF["nfed_modalidade"];
                    $finalidade = $retornoNF["nfed_finalizade"];
                    $operacao =  $retornoNF["nfed_operacao"];
                    $tipocontribuinte = $retornoNF["nfed_tipocontribuinte"];
                    $_txtadd = $retornoNF['nfed_informacaoAdicionais'];
                    $codpgto = $retornoNF["nfed_codpgto"];        
                    $cancelada = $retornoNF["nfed_cancelada"];
                    //dados gravado cliente
                    $c_nomecliente = $retornoNF["nfed_dNome"];
                    $c_cpfcnpj = $retornoNF["nfed_cpfcnpj"];
                    $c_ie = $retornoNF["nfed_ie"];
                    $c_endereco = $retornoNF["nfed_dEdereco"];
                    $c_numrua= $retornoNF["nfed_dnumrua"];
                    $c_bairro = $retornoNF["nfed_dBairro"];
                    $c_cidade = $retornoNF["nfed_dCidade"];      
                    $c_cep = $retornoNF["nfed_dCEP"];       
                    $c_uf = $retornoNF["nfed_dUF"];
                    $c_telefone = $retornoNF["nfed_dTelefone"];
                    $c_email = $retornoNF["nfed_email"];
                    $empresa_serie  = $retornoNF["nfed_serie"];
                    $nfed_totalnota  = $retornoNF["nfed_totalnota"];
                    $nfed_basecalculo  = $retornoNF["nfed_basecalculo"];
                    $nfed_valorISS  = $retornoNF["nfed_valorISS"];
                    $nfed_aliquotaISS  = $retornoNF["nfed_aliquotaISS"];
                    $nfed_abatimentoIptu  = $retornoNF["nfed_abatimentoIptu"];

                    $nfed_valorISSretido  = $retornoNF["nfed_valorISSretido"]; 
           
              

                //buscar dados empresa
                $ret = $pdo->query("SELECT empresa_cnpj,empresa_inscricao,empresa_inscricaomunicipal,empresa_codmunicipio,habilita_nfse,empresa_codcnae,empresa_tributacao,empresa_regime_tributario,empresa_numero_aedf,        
                empresa_codigo_tributario_municipio,empresa_regime_especial_tributacao,empresa_codcnae,empresa_NaturezaOperacao	  FROM ".$_SESSION['BASE'].".empresa 
                where empresa_id = '$nfed_empresa' limit 1  ");
                $retornoEmpresa = $ret->fetch(\PDO::FETCH_OBJ);

                //comentario para teste


                //buscar dados cliente
                $_nomeconsumidor =  $_parametros['nomecliente'];  

                $CodigoCnae =  $retornoEmpresa->empresa_codcnae;  

                //novo item
                      

                //verificar se esta liberado
                if($retornoEmpresa->habilita_nfse == 0){
                        $_errorlog = $_errorlog.$xx."- NFSe não liberada para emissão! ($nfed_empresa)\n";
                }

                //verificar descrição
                if($_parametros['nf-informacaoAdicionais'] == ""){
                    $_errorlog = $_errorlog.$xx."- Preencha corretamente descrição serviço!\n";
                }

                //verificar itemserviço
                if($_parametros['item-servico'] <= 0){
                    $_errorlog = $_errorlog.$xx."- Selecione Item Serviço!\n";
                }

             
                        
                if($_errorlog != ""){
                    ?>
                    <div id="ok11" class="row" style="text-align: center;" class="alert alert-danger alert-dismissable" id="retnf">                                                 
                        <span style="color:#6b1010"><?=nl2br($_errorlog) ?></span>
                    </div>      
                    <div class="row pull-right">
               
                        <?php
                        exit();
                }    

                $consultaItem = $pdo->query("SELECT * FROM bd_prisma.item_lista_servico WHERE is_id = '".$_parametros['item-servico']."'");
                $servicosItem = $consultaItem->fetchAll(\PDO::FETCH_OBJ);
                foreach($servicosItem as $rowItem){
                    $itemservico = preg_replace('/[^0-9]/', '',  $rowItem->is_Subitem);
                } 
              

                $_tipopessoa = 'cpf';
                $_cnpjcpfContador = preg_replace('/[^0-9]/', '',  $_parametros['NF-CGC_CPF']);

                if(strlen($_cnpjcpfContador) > 11){    
                    $_tipopessoa = 'cnpj';
                }   

                $InscricaoMunicipal = preg_replace('/[^0-9]/', '',  $_parametros['NF-ie']);
           

              /* $cpfcnpj = str_replace(".","",$retornoCliente->CGC_CPF);
                $cpfcnpj = str_replace("-","",$retornoCliente->CGC_CPF);
                $cpfcnpj = str_replace("/","",$retornoCliente->CGC_CPF);
*/

                $cep = LimpaVariavel($_parametros['NF-CEP']);
                $cep = str_replace("-","",$cep);
                $cpfcnpj = $_cnpjcpfContador;

                $consulta = $pdo->query("SELECT cod_cidade FROM minhaos_cep.cidade 
                WHERE estado = '".$_parametros['NF-estado']."' AND cidade = '".$_parametros['NF-Cidade']."'");
                $codigo_municipio = ($consulta->fetch(\PDO::FETCH_OBJ))->cod_cidade;

                $prestador = array(
                    "cnpj" => $retornoEmpresa->empresa_cnpj,
                    "inscricao_municipal" => $retornoEmpresa->empresa_inscricaomunicipal,
                    "codigo_municipio" => $retornoEmpresa->empresa_codmunicipio
                );

                if($InscricaoMunicipal != ""){
                    if($_parametros['NF-EMAIL'] != "") {
                        $tomador = array(
                            "$_tipopessoa" => $cpfcnpj,
                            "razao_social" => $_nomeconsumidor,
                            "email" => $_parametros['NF-EMAIL'],
                            "inscricao_municipal" => $InscricaoMunicipal,
                            "endereco" => array(
                                "logradouro" => $_parametros['NF-endereco'],
                                "numero" =>$_parametros['NF-numrua'],
                                "bairro" => $_parametros['NF-bairro'],
                                "complemento" => 'null',                   
                                "codigo_municipio" => "$codigo_municipio",          
                                "uf" => $_parametros['NF-estado'],
                                "cep" => $cep)
                            );
                    }else{
                        $tomador = array(
                            "$_tipopessoa" => $cpfcnpj,
                            "razao_social" => $_nomeconsumidor,
                            "inscricao_municipal" => $InscricaoMunicipal,
                            "endereco" => array(
                                "logradouro" => $_parametros['NF-endereco'],
                                "numero" =>$_parametros['NF-numrua'],
                                "bairro" => $_parametros['NF-bairro'],
                                "complemento" => 'null',                   
                                "codigo_municipio" => "$codigo_municipio",          
                                "uf" => $_parametros['NF-estado'],
                                "cep" => $cep)
                            );
                    }

                }else{

                    //sem inscr.municpal
                    if($_parametros['NF-EMAIL'] != "") {
                        $tomador = array(
                            "$_tipopessoa" => $cpfcnpj,
                            "razao_social" => $_nomeconsumidor,
                            "email" => $_parametros['NF-EMAIL'],
                            "endereco" => array(
                                "logradouro" => $_parametros['NF-endereco'],
                                "numero" =>$_parametros['NF-numrua'],
                                "bairro" => $_parametros['NF-bairro'],
                                "complemento" => 'null',                   
                                "codigo_municipio" => "$codigo_municipio",          
                                "uf" => $_parametros['NF-estado'],
                                "cep" => $cep)
                            );
                    }else{
                        $tomador = array(
                            "$_tipopessoa" => $cpfcnpj,
                            "razao_social" => $_nomeconsumidor,                          
                            "endereco" => array(
                                "logradouro" => $_parametros['NF-endereco'],
                                "numero" =>$_parametros['NF-numrua'],
                                "bairro" => $_parametros['NF-bairro'],
                                "complemento" => 'null',                   
                                "codigo_municipio" => "$codigo_municipio",          
                                "uf" => $_parametros['NF-estado'],
                                "cep" => $cep)
                            );
                    }

                }

               

                $_vlbase = LimpaVariavel($_parametros['nota-base']);  
                $_aliquota= LimpaVariavel($_parametros['nota-aliquota']); 
                $_vliss= LimpaVariavel($_parametros['nota-iss']);   
                $valor_iss_retido =  LimpaVariavel($_parametros['nota-issretido']);   
                $iss_retido = 'false';
             
                $_vliptu= LimpaVariavel($_parametros['nota-abatimento-iptu']);   
                $_vldeducoes= LimpaVariavel($_parametros['nota-deducoes']);   
                
                $_vlservico = LimpaVariavel($_parametros['nota-total']);   
                
                if($retornoEmpresa->empresa_codigo_tributario_municipio == "") {
                    
                        if($valor_iss_retido > 0) {
                            $iss_retido = 'true';
                            $servico = array(         
                                "discriminacao" => $_parametros['nf-informacaoAdicionais'],
                                "iss_retido" => $iss_retido,
                                "valor_iss_retido" => $valor_iss_retido,
                                "valor_iss" => $_vliss,
                                "base_calculo" => $_vlbase,
                                "aliquota" => $_aliquota,           
                                "item_lista_servico" => $itemservico,   
                                'valor_servicos' => $_vlservico,
                                'valor_deducoes'  => $_vldeducoes                     
                            );
                        }else{
                            $servico = array(         
                                "discriminacao" => $_parametros['nf-informacaoAdicionais'],
                                "iss_retido" => $iss_retido,
                                "valor_iss" => $_vliss,
                                "base_calculo" => $_vlbase,
                                "aliquota" => $_aliquota,           
                                "item_lista_servico" => $itemservico,                                                           
                                'valor_servicos' => $_vlservico,
                                'valor_deducoes'  => $_vldeducoes                     
                            );

                        }
                    //$retornoEmpresa->empresa_codigo_tributario_municipio == ""

                       // Adiciona o código CNAE se não estiver vazio
                       if (!empty($CodigoCnae)) {
                        $servico["codigo_cnae"] = $CodigoCnae;
                       }
                    
                }else{

               

                if($valor_iss_retido > 0) {
                    $iss_retido = 'true';
                    $servico = array(         
                        "discriminacao" => $_parametros['nf-informacaoAdicionais'],
                        "iss_retido" => $iss_retido,
                        "valor_iss_retido" => $valor_iss_retido,
                        "valor_iss" => $_vliss,
                        "base_calculo" => $_vlbase,
                        "aliquota" => $_aliquota,           
                        "item_lista_servico" => $itemservico,                     
                        "codigo_tributario_municipio" => $retornoEmpresa->empresa_codigo_tributario_municipio,                         
                        'valor_servicos' => $_vlservico,
                        'valor_deducoes'  => $_vldeducoes                     
                    );
                
                }else{
                    $servico = array(         
                        "discriminacao" => $_parametros['nf-informacaoAdicionais'],
                        "iss_retido" => $iss_retido,
                        "valor_iss" => $_vliss,
                        "base_calculo" => $_vlbase,
                        "aliquota" => $_aliquota,           
                        "item_lista_servico" => $itemservico,                     
                        "codigo_tributario_municipio" => $retornoEmpresa->empresa_codigo_tributario_municipio,                         
                        'valor_servicos' => $_vlservico,
                        'valor_deducoes'  => $_vldeducoes                     
                    );


                }

                   // Adiciona o código CNAE se não estiver vazio
                   if (!empty($CodigoCnae)) {
                    $servico["codigo_cnae"] = $CodigoCnae;
                 }

            }
              
                if($retornoEmpresa->empresa_regime_tributario == 1) {
                    $_simples = "true";
                }else{
                    $_simples = "false";
                    if($retornoEmpresa->empresa_regime_tributario == 4) {
                        $retornoEmpresa->empresa_regime_tributario = 1;
                    }
                }

                $empresa_NaturezaOperacao = $retornoEmpresa->empresa_regime_tributario;

                if($retornoEmpresa->empresa_NaturezaOperacao != "") {
                   $empresa_NaturezaOperacao  = $retornoEmpresa->empresa_NaturezaOperacao;
                }
                

            
                if($retornoEmpresa->empresa_regime_especial_tributacao != "") {
                    $nfse =  array (
                        "data_emissao" => date("Y-m-d\TH:i:sP"),
                        "natureza_operacao" => $empresa_NaturezaOperacao ,
                        "optante_simples_nacional" => $_simples,
                        "regime_especial_tributacao" => $retornoEmpresa->empresa_regime_especial_tributacao);               
                            
                        $nfse += array('prestador' =>$prestador);
                        $nfse += array('tomador' =>$tomador);
                        $nfse += array('servico' =>$servico);

                }else{
                    $nfse =  array (
                        "data_emissao" => date("Y-m-d\TH:i:sP"),
                        "natureza_operacao" => $empresa_NaturezaOperacao,
                        "optante_simples_nacional" => $_simples);
                    
                            
                        $nfse += array('prestador' =>$prestador);
                        $nfse += array('tomador' =>$tomador);
                        $nfse += array('servico' =>$servico);
                }
            


                $_sql = "UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET     
                nfed_cancelada = ?, nfed_totalnota = ?,
                nfed_informacaoAdicionais = ?,
                nfed_basecalculo= ?,nfed_abatimentoIptu= ?,nfed_valorISS= ?,nfed_aliquotaISS = ?,
                nfed_xml = ?
                WHERE nfed_id = ? and
                nfed_modelo = '90'";
                $statement = $pdo->prepare("$_sql");     
                $_status = 9;//"9 processando 10 emitida 11 cancelada"  ;
                //$ativit = new atividade();
                
                $statement->bindParam(1, $_status);//                 
                $statement->bindParam(2, $_vlservico);// 
                $statement->bindParam(3, $_parametros['nf-informacaoAdicionais']);
                $statement->bindParam(4, $_vlbase);
                $statement->bindParam(5, $_vliptu);
                $statement->bindParam(6,  $_vliss);
                $statement->bindParam(7, $_aliquota);
                $statement->bindParam(8, json_encode($nfse));
                $statement->bindParam(9, $_idref);
               
                $statement->execute();
      
               $NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd                
          
               $response = $NFeFocus->gerarNFSe($_idref,$nfse);
          

              //  print_r($response);

                if($response != ""){
                    //salva log
                    $msg = "";
                    $texto = $response->body->mensagem;
                    $codigo_focus = $response->http_code;
                    if( $codigo_focus == 401){ //erro 401 
                        $msg = "ERR. COD($codigo_focus)";
                    }
                 //   $ativit = new atividade();
                 //   $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);
                }

                if($msg != "") {                    ?>
            
                    <div class="alert alert-danger" role="alert"> 
                        <strong><i class="fa  fa-exclamation-triangle " style="color:#000000"></i>   <span class="text-muted" style="color:#000000">(Ops!! Algo deu errado)</span><br></strong>
                        <?=$msg;?>
                    </div>
                    
                <?php
                exit();
                }
          
              
                if($response == true) {
              

              
                $_sql = "UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET     
                nfed_recibo = ?
                WHERE nfed_id = ? and
                nfed_modelo = '90'";
                $statement = $pdo->prepare("$_sql");   
                $statement->bindParam(1, json_encode($response));  
                $statement->bindParam(2, $_idref);
                $statement->execute();
                
                //    $_obs = "Emissão NFSe-$_ref";
                
                    ?>
            <div id="retnf">
                    <div class="alert alert-warning" role="alert"> 
                        <strong><i class="fa fa-spin fa-gear " style="color:#000000"></i>   <span class="text-muted" style="color:#000000">(Processando Autorização  )</span><br></strong>
                        Você pode aguardar ou pesquisar daqui alguns instantes
                    </div>
                    </div>  
                <?php }


                exit();
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

if ($_POST["acao"] == 6) {

 //BUSCAR EMPRESA
 $NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd   

 
 //$_ref = $_idref['3'].str_pad($_idref['4'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
 $response = $NFeFocus->ConsultarNFSe($_idref);
 
 if($response->status == 'autorizado') {
 
     $dtemissao = $response->data_emissao;
 
     $curl = curl_init();
 
     curl_setopt_array($curl, array(
     CURLOPT_URL => 'https://api.focusnfe.com.br'.$response->caminho_xml_nota_fiscal,
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'GET',
     ));
 
 $responsexml = curl_exec($curl);
 
 
 curl_close($curl);
 
     //update
     $_sql = "UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET 
     nfed_protocolo = ?,
     nfed_serie = ?,
     nfed_cancelada = ?,
     nfed_numeronf = ?,
     nfed_chave = ?,
     nfed_hora = ?,
     nfed_arquivo = ?,
     nfed_xml_protocolado = ?,
     nfed_dataautorizacao = ?,
     nfed_recibo = ?,
     nfed_url = ?
     WHERE nfed_id = ?";
        
     $statement = $pdo->prepare("$_sql");
     $status = 0;
 
     $statement->bindParam(1, $response->numero_rps);
     $statement->bindParam(2, $response->serie_rps);
     $statement->bindParam(3, $status );//
     $statement->bindParam(4, $response->numero);
     $statement->bindParam(5, $response->codigo_verificacao);
     $statement->bindParam(6, $response->data_emissao);
     $statement->bindParam(7, $response->url_danfse);
     $statement->bindParam(8, $responsexml);//
     $statement->bindParam(9, $dtemissao);
     $statement->bindParam(10, json_encode($response));       
     $statement->bindParam(11, $response->url);
     $statement->bindParam(12, $_idref);
     $statement->execute();
  
    ?><div id="ok10" class="alert alert-success" role="alert"> 
       <a href="<?=$response->url;?>" target="_blank"> <button class="btn btn-success waves-effect waves-light" ><span class="btn-label"><i class="fa fa-print"></i></span> Imprimir NFSe</button></a>           
              
        </div>
   
          
    <?php exit(); 
    }else{
        if($response != ""){
            //salva log
            $msg = $_idref;
            $texto = $response->body->mensagem;
            $codigo_focus = $response->http_code;
         //   $ativit = new atividade();
          //  $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);

            // 404 - nota não encontrada
            // 422 - erro
            // 400 - empresa nao habilitada para emissão na focus
      //  print_r($response);
       //update
     $_sql = "UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET 
          nfed_recibo = ?
     WHERE nfed_id = ?";
        
     $statement = $pdo->prepare("$_sql");
     $status = 0;
 
     $statement->bindParam(1, json_encode($response));   
     $statement->bindParam(2, $_idref);
     $statement->execute();
        }
        ?>
  
        <div class="alert alert-warning" role="alert"> 
        <strong>
            <i class="fa fa fa-spin fa-spinner " style="color:#000000">   </i>   <span class="text-muted" style="color:#000000">( Aguardando Autorização id:<?=$_idref;?>  )</span><br>
            </strong> Você pode aguardar ou pesquisar daqui alguns instantes     
            
        </div>
          
    <?php exit(); 
    }
 
        
      
       
}


if ($_POST["acao"] == 7) {
   
    /*
 * consulta status nf
 * */

 //BUSCAR EMPRESA
$NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd   

//$response = $NFeFocus->consultaItemServico('4106902');
//print_r($response);
//exit();
//print_r( $NFeFocus);
//exit();

//$_ref = $_idref['3'].str_pad($_idref['4'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
$response = $NFeFocus->ConsultarNFSe($_idref);

if($response->status == 'autorizado') {

    $dtemissao = $response->data_emissao;

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.focusnfe.com.br'.$response->caminho_xml_nota_fiscal,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));

$responsexml = curl_exec($curl);


curl_close($curl);

    //update
    $_sql = "UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET 
    nfed_protocolo = ?,
    nfed_serie = ?,
    nfed_cancelada = ?,
    nfed_numeronf = ?,
    nfed_chave = ?,
    nfed_hora = ?,
    nfed_arquivo = ?,
    nfed_xml_protocolado = ?,
    nfed_dataautorizacao = ?,
    nfed_recibo = ?,
    nfed_url = ?
    WHERE nfed_id = ?";
       
    $statement = $pdo->prepare("$_sql");
    $status = 0;

    $statement->bindParam(1, $response->numero_rps);
    $statement->bindParam(2, $response->serie_rps);
    $statement->bindParam(3, $status );//
    $statement->bindParam(4, $response->numero);
    $statement->bindParam(5, $response->codigo_verificacao);
    $statement->bindParam(6, $response->data_emissao);
    $statement->bindParam(7, $response->url_danfse);
    $statement->bindParam(8, $responsexml);//
    $statement->bindParam(9, $dtemissao);
    $statement->bindParam(10, json_encode($response));   
    $statement->bindParam(11, $response->url);
    $statement->bindParam(12, $_idref);
    $statement->execute();
   
    ?>  <span class="label label-table label-success">Emitida</span>
   
          
    <?php exit(); 
    }else{
        if($response != ""){
            //salva log
            $msg = $_idref;
            $texto = $response->body->mensagem;
            $codigo_focus = $response->http_code;
         //   $ativit = new atividade();
          //  $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);

            // 404 - nota não encontrada
            // 422 - erro
            // 400 - empresa nao habilitada para emissão na focus
           
        }
        ?>
  
  <span class="label label-table label-primary">Aguardando Aut.</span>
          
    <?php exit(); 
    }
}


//CANCELAMENTO nfe
if ($_POST["acao"] == 16) {
    try{
        date_default_timezone_set('America/Sao_Paulo');   

        $numero_pedido = $_parametros['id-nota'];
        
        $livro = 0;
     
        $xJust =   trim($_parametros['xJust']);

        if(  $xJust == "") { ?>
        
        <div class="alert alert-danger" role="alert" id="result-cancelarnf">
          		
                    <p><strong> Informe o  Motivo do cancelmento  </strong> !!!</p>
                                      
        </div>
                         
                    <?php
                    exit();

}


      
    /*
 * consulta status nf
                * */
               
           
                //BUSCAR EMPRESA
                $NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd   

                //$response = $NFeFocus->consultaItemServico('4106902');
                //print_r($response);
                //exit();
                //print_r( $NFeFocus);
                //exit();

                //$_ref = $_idref['3'].str_pad($_idref['4'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
           
                $response = $NFeFocus->CancelamentoNFSe($_idref,$xJust);
             //  print_r( $response);

                if ($response->http_code == '200' || $response->http_code == '201') {
                    $response = $response->body;
                
                } else {
                    ?><div  class="alert alert-warning" role="alert" style="text-align: center;"> 
                    <span class="text-muted" style="color:#000000; ">       
                    <?php print_r($response->body->mensagem );?>
                    </span>
                </div>


                <?php 
                }

                if($response->status == 'cancelado' ) {
                    $xMotivo = " Cancelamento Efetuado";
                }

        
            ?>
        
                    <div class="row">
                        <div class="col-sm-12" align="center">			
                            <p><strong> <?php echo $xMotivo ;?></p>
                        </div>
                        </div>                               
                      
                                
                            <?php
        
    
       $texto = $response->body->mensagem;
       $codigo_focus = $response->http_code;
       $_retorno = $codigo_focus ."-".$texto ;
       $xcancelada = 1;
     
        $_sql = "UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
        nfed_cancelada = ?,
        nfed_motEcarta = ?, 
        nfed_xmlcancelado = ?
        WHERE nfed_cancelada = '0' and  nfed_id = ? ";    
        $statement = $pdo->prepare("$_sql");   
        $statement->bindParam(1, $xcancelada);
        $statement->bindParam(2, $xJust);
        $statement->bindParam(3, $_retorno);     					
        $statement->bindParam(4, $_idref);
        $statement->execute();
     
       

    } catch (\Exception $e) {
        echo $e->getMessage();
    }


}