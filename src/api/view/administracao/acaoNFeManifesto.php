<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   



use Database\MySQL;
use Functions\NFeFocus;


//use NFePHP\NFe\Extras\Danfe;



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


$nfed_empresa = $_parametros["id-empresa"];
if($nfed_empresa == "") {
    $nfed_empresa = 1;
}


$_idref = $_parametros['id-nota'];
if($_idref == "") {     
    $_idref = $_parametros['numeromdfe'];
}

$query = $pdo->query("SELECT empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC ,Ult_NFS FROM  " . $_SESSION['BASE'] . ".parametro  ");
$retornoItem = $query->fetch();


if ( $_POST["acao"] == '10') { 
  
    if($retornoItem['Ult_NFS'] < 99) {
        $retornoItem['Ult_NFS'] = 100;
             echo  $retornoItem['Ult_NFS'];
             $update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".parametro SET Ult_NFS = 101   ");  
    }else{
    
         echo  $retornoItem['Ult_NFS'];
          $update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".parametro SET Ult_NFS = Ult_NFS + 1   ");  
    }
   
    $update->execute();
    exit();
}
    


if ($acao == '30') { 


            try{
                 $nfed_empresa = $_parametros["nf-empresa"];
           
                $modelo = '58'; //modelo 58 MDF-e
                //verificar se ja existe
                if($_idref != "" && $_idref != "0" ) {
                  //  echo "Verificando MDF-e:".$_idref;
                    // 1. Usa PREPARE para a segurança (permite o placeholder ?)
                        $query = $pdo->prepare("SELECT  nfed_modelo,nfed_tipodocumento  FROM " . $_SESSION['BASE'] . ".NFE_DADOS  WHERE nfed_tipodocumento = ? AND nfed_modelo = '58'");
                        // 2. Faz o BIND da variável
                        $query->bindParam(1, $_idref);                         
                        // 3. EXECUTA
                        $query->execute(); 
                        
                        $retorno = $query->fetch();
                    if($retorno['nfed_tipodocumento'] != "" ) {
                        //    MDF-e já existe na base de dados
                       
                          
                    }else{
                        //continua
                        //inserir MDF-e
                      //  echo "MDF-e:".$_idref;
                        $INSERT = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".NFE_DADOS (nfed_empresa,nfed_modelo,nfed_tipodocumento,nfed_data) VALUES (?,?,?,?)");
                        $INSERT->bindParam(1, $nfed_empresa);
                        $INSERT->bindParam(2, $modelo); //modelo 58 MDF-e
                        $INSERT->bindParam(3, $_idref); //numero do mdfe
                        $INSERT->bindParam(4, date('Y-m-d'));                      
                        $INSERT->execute();
                    }
                }
             
        
                 
                    // Buscar dados da empresa do banco (exemplo)
                    // Prepara a query com placeholder
                    $sql = "SELECT 
                                empresa_nome,
                                empresa_razaosocial,
                                empresa_endereco,
                                empresa_numero,
                                empresa_complemento,
                                empresa_bairro,
                                empresa_codmunicipio,
                                empresa_cidade,
                                empresa_telefone,
                                empresa_cnpj,
                                empresa_inscricao,
                                empresa_email,
                                CEP
                            FROM " . $_SESSION['BASE'] . ".empresa 
                            WHERE empresa_id = :empresa_id";
                    // Prepara a consulta
                    $stmt = $pdo->prepare($sql);

                    // Faz o bind do parâmetro (seguro contra SQL Injection)
                    $stmt->bindValue(':empresa_id', $nfed_empresa, PDO::PARAM_INT);

                    // Executa
                    $stmt->execute();

                    // Busca os dados
                    $retornoEmp = $stmt->fetch(PDO::FETCH_ASSOC);
                        // $empresa = buscarEmpresaDoBanco($empresa_id);
                      // print_r($_parametros);

                        // OU usar dados do POST diretamente
                    
                            // ========================================
                            // 7. MUNICÍPIO DE DESCARGA
                            // ========================================
                            if (!empty($_parametros['municipios_descarga_codigo']) && !empty($_parametros['municipios_descarga_nome'])) {
                                $notas_fiscais = [];
                                
                                // Buscar chaves de NF-e
                                if (!empty($_parametros['chaveacesso'])) {
                                    // Se vier como string única
                                    if (is_string($_parametros['chaveacesso'])) {
                                        $chaves = explode(',', $_parametros['chaveacesso']);
                                        foreach ($chaves as $chave) {
                                            $chave = trim($chave);
                                            if (strlen($chave) == 44) {
                                                $notas_fiscais[] = ["chave_nfe" => $chave];
                                                $mdfe_data_array['quantidade_total_nfe']++;
                                            }
                                        }
                                    }
                                    // Se vier como array
                                    elseif (is_array($_parametros['chaveacesso'])) {
                                        foreach ($_parametros['chaveacesso'] as $chave) {
                                            $chave = trim($chave);
                                            if (strlen($chave) == 44) {
                                                $notas_fiscais[] = ["chave_nfe" => $chave];
                                                $mdfe_data_array['quantidade_total_nfe']++;
                                            }
                                        }
                                    }
                                }
                            }

                         
                            

                             //buscar codigo municipio 
                            $_s = "Select cod_cidade,cod_uf  from minhaos_cep.cidade  where cidade  = '".$_parametros['municipio_carregamento_nome']."' and estado  = '". $_parametros['uf_inicio']."'";
                            $stm = $pdo->query("$_s");         
                            $codm = $stm->fetch(PDO::FETCH_OBJ);
                               $_municipiocarregamento =  $codm->cod_cidade;

                                 $_s = "Select cod_cidade,cod_uf  from minhaos_cep.cidade  where cidade  = '".$_parametros['municipios_descarga_nome']."' and estado  = '". $_parametros['uf_fim']."'";
                            $stm = $pdo->query("$_s");         
                            $codm = $stm->fetch(PDO::FETCH_OBJ);
                              $_municipiodescarga =  $codm->cod_cidade;
   

                        $mdfe_data_array = [
                             "emitente" =>  $_parametros['emitente_id'] ?? "2",                                                          
                            "modal_rodoviario" => [
                                "codigo_veiculo" =>  $_parametros['veiculo_codigo'] ?? "1",
                                "placa_veiculo" => strtoupper( $_parametros['veiculo_placa'] ?? ""),
                                "tara_veiculo" => (int)( $_parametros['tara'] ?? 1106),
                                "capacidade_kg_veiculo" => (int)( $_parametros['capacidade_kg'] ?? 650),
                                "capacidade_m3_veiculo" => (int)( $_parametros['capacidade_m3'] ?? 1),
                                "tipo_rodado_veiculo" =>  $_parametros['veiculo_tipo_rodado'] ?? "05",
                                "tipo_carroceria_veiculo" =>  $_parametros['veiculo_tipo_carroceria'] ?? "00",
                                "uf_licenciamento_veiculo" =>   $retornoEmp['empresa_uf'] ?? 'MT',                                
                                "condutores" => [
                                    [
                                    
                                       "cpf" =>  preg_replace('/\D/', '', $_parametros['motorista_cpf'] ?? ""),
                                       "nome" => $_parametros['motorista_nome']
                                        ]
                                ]
                            ],
                            "uf_inicio" =>  $_parametros['uf_inicio'] ?? "MT",
                            "uf_fim" =>  $_parametros['uf_fim'] ?? "MT", 
                                 // Dados do emitente
                            "nome_emitente" =>  $retornoEmp['empresa_razaosocial'] ?? "",
                            "nome_fantasia_emitente" =>  $retornoEmp['empresa_nome'] ?? "",
                            "logradouro_emitente" =>  $retornoEmp['empresa_endereco'] ?? "",
                            "numero_emitente" =>  $retornoEmp['empresa_numero'] ?? "",
                            "complemento_emitente" =>  $retornoEmp['empresa_complemento'] ?? "",
                            "bairro_emitente" =>  $retornoEmp['empresa_bairro'] ?? "",
                            "codigo_municipio_emitente" =>  $retornoEmp['empresa_codmunicipio'] ?? "",
                            "municipio_emitente" =>  $retornoEmp['empresa_cidade'] ?? "",
                            "cep_emitente" => preg_replace('/\D/', '',  $retornoEmp['CEP'] ?? ''),
                            "uf_emitente" =>  $retornoEmp['empresa_uf'] ?? "MT",
                            "telefone_emitente" => preg_replace('/\D/', '',  $retornoEmp['empresa_telefone'] ?? ''),
                            "cnpj_emitente" => preg_replace('/\D/', '',  $retornoEmp['empresa_cnpj'] ?? ''),
                            "inscricao_estadual_emitente" => preg_replace('/\D/', '',  $retornoEmp['empresa_inscricao'] ?? ''),
                            "email_emitente" =>  $retornoEmp['empresa_email'] ?? "",                     
                                                      
                            "municipios_carregamento" => [
                                [
                                    "codigo" => $_municipiocarregamento ?? "-",
                                    "nome" => $_parametros['municipio_carregamento_nome'] ?? "-"
                                ]
                            ],
                            "municipios_descarregamento" => [
                                [
                                    "codigo" => $_municipiodescarga ?? "-",
                                    "nome" => $_parametros['municipios_descarga_nome'] ?? "-",                                                                      
                                    "notas_fiscais" => [
                                          [
                                                "chave_nfe"=>  preg_replace('/\D/', '',  $_parametros['chaveacesso'] ?? '') 
                                          ]
                                    ]
                                    
                                ]
                            ],
                            
                            "quantidade_total_cte" => 0,
                            "quantidade_total_nfe" => $mdfe_data_array['quantidade_total_nfe'],
                            "valor_total_carga" => (float)( $_parametros['valor_total_carga']),//0.00,
                            "peso_bruto" =>(float)( $_parametros['peso_bruto']) ,//0.00,
                            "codigo_unidade_medida_peso_bruto" => "01",
                            "cnpj_autorizado" => preg_replace('/\D/', '',  $retornoEmp['empresa_cnpj'] ?? ''),
                            "informacao_complementar" =>  $_parametros['observacoes'] ?? ""
                        ];
                        
                      
                        
                        // Calcular totais
                        $mdfe_data_array['valor_total_carga'] = (float)( $_parametros['valor_total_carga'] ?? 1.00);
                        $mdfe_data_array['peso_bruto'] = (float)( $_parametros['peso_bruto'] ?? 1.00);
                        
                    
                        // Exibir JSON gerado
                      //  header('Content-Type: application/json');
                     


                // 2. Converte o array para a string JSON
                //$mdfe_json_string = json_encode($mdfe_data_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                // Exibe o JSON formatado
                //echo $mdfe_json_string;
                //exit();
                 
                    $statement = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET   
                    nfed_cancelada = ?  ,
                    nfed_xml = ?
                    WHERE nfed_tipodocumento = ? and
                    nfed_modelo = '58'");     
                    $_status = 9;//"9 processando 10 emitida 11 cancelada"  ;                               
                    $statement->bindParam(1, $_status);
                    $statement->bindParam(2, json_encode($mdfe_data_array));
                    $statement->bindParam(3, $_idref);               
                    $statement->execute();
        
                           
                $NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd                
            
                $response = $NFeFocus->gerarMDFe($_idref,$mdfe_data_array);
          


                if($response != ""){
                    //salva log
                    $msg = "";
                    $texto = $response->body->mensagem;
                    $codigo_focus = $response->http_code;
                    if( $codigo_focus >201 ){ //erro 401 
                            // Exemplo de resposta
              
                        $mensagem = $response->body->mensagem;
              
                        $msg = "ERR. COD($codigo_focus) " . $mensagem;
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
                        Atualize daqui alguns instantes na opção filtrar 
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





if ( $_POST["acao"]  == 7) {
   
    /*
 * consulta status nf
 * */

 //BUSCAR EMPRESA
  $nfed_empresa = $_parametros["id-empresa"];
$NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd   

$response = $NFeFocus->ConsultarMDFE($_parametros['id-ref']);

if($response->status == 'autorizado') {

    $dtemissao = $response->data_emissao;

        $curl = curl_init();
//  CURLOPT_URL => 'https://api.focusnfe.com.br'.$response->caminho_xml_nota_fiscal,
    curl_setopt_array($curl, array(
    CURLOPT_URL =>$response->caminho_xml,
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
    nfed_serie = ?,  
    nfed_cancelada = ?, 
    nfed_chave = ?,
    nfed_hora = ?,
    nfed_arquivo = ?,
    nfed_xml_protocolado = ?,
    nfed_dataautorizacao = ?,
    nfed_recibo = ?,
    nfed_url = ?,
    nfed_numeronf = ?
    WHERE nfed_tipodocumento = ?";
       
    $statement = $pdo->prepare("$_sql");
    $status = 0;

  
    $statement->bindParam(1, $response->serie);
    $statement->bindParam(2, $status );//
    $statement->bindParam(3, $response->chave);   
    $statement->bindParam(4, $response->data_emissao);
    $statement->bindParam(5, $response->caminho_damdfe);
    $statement->bindParam(6, $responsexml);//
    $statement->bindParam(7, $dtemissao);
    $statement->bindParam(8, json_encode($response));     
    $statement->bindParam(9, $response->caminho_damdfe);    
    $statement->bindParam(10, $response->numero);         
    $statement->bindParam(11, $_parametros['id-ref']);
    $statement->execute();



   
   
    ?>  <span class="label label-table label-success">Emitida</span>
   
          
    <?php 
    exit(); 
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
  
  <span class="label label-table label-primary">Aguardando Aut.</span><?=$texto;?>
          
    <?php exit(); 
    }
}


//CANCELAMENTO mdfe
if ( $_POST["acao"]  == 16) {
    try{
        date_default_timezone_set('America/Sao_Paulo');   

        $_idref = $_parametros['documentoREF'];
        $xJust =   trim($_parametros['xJust']);

        if(  $xJust == "") { ?>
        
        <div class="alert alert-danger" role="alert" id="result-cancelarnf">
          		
                    <p><strong> Informe o  Motivo do cancelmento  </strong> !!!</p>
                                      
        </div>
                         
                    <?php
                    exit();

            }


                //BUSCAR EMPRESA
                $nfed_empresa = $_parametros["id-empresa"];
                $NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd   

 
                $response = $NFeFocus->CancelamentoMdfe($_idref,$xJust);

          
       print_r($response);

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
                    
                        //update
                        $_sql = "UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET 
                        nfed_cancelada = ?
                        WHERE nfed_tipodocumento = ?";       
                        $statement = $pdo->prepare("$_sql");
                        $status = 9;
                    
                    
                        $statement->bindParam(1, $status );//
                        
                        $statement->bindParam(11, $_idref);
                        $statement->execute();

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

//ENCERRAMENTO

if ( $_POST["acao"]  == 17) {

 //BUSCAR EMPRESA
 $nfed_empresa = $_parametros["id-empresa"];
 $NFeFocus = new NFeFocus($nfed_empresa, 'prd');//prd   

  $mdfe_data_array = [
                       "data" =>  date('Y-m-d'),
                       "sigla_uf" =>  "PR",
                       "nome_municipio" =>  'Curitiba'
                           
  ];



  $response = $NFeFocus->EncerramentoMDFe($_idref,$mdfe_data_array);

       try{

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

                if($response->status == 'encerrada' ) {
                    $xMotivo = " Encerramento Efetuado";
                    
                        //update
                        $_sql = "UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET 
                        nfed_cancelada = ?
                        WHERE nfed_tipodocumento = ?";       
                        $statement = $pdo->prepare("$_sql");
                        $status = 8;
                    
                    
                        $statement->bindParam(1, $status );//
                        
                        $statement->bindParam(11, $_idref);
                        $statement->execute();

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
       $xcancelada = 2;
     
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