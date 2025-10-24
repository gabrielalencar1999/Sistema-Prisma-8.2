<?php

use Database\MySQL;
use Functions\NFeFocus;
use Functions\NFeService;
use NFePHP\NFe\Common\Standardize;
use Functions\Atividade;


use Functions\Movimento;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

/*
 * Função para limpar variáveis, caso necessário
 * */
function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

/*
 * Modal Pedidos
 * */

$pedido = explode('-', $_parametros['id-altera']);
$cpfcnpj = $_parametros['cpf'];

if($_POST["acao"]== "monta_info"){

    //busca dados
    $sql="select * from bd_gestorpet.notas where nf_controle = '".$_POST['id']."' and nf_empresaid = '".$_SESSION['BASE_ID']."'limit 1";
    //echo($sql);
    $stm = $pdo->prepare($sql);	
    $stm->execute();
    while($row = $stm->fetch(PDO::FETCH_OBJ)){;
        echo $row->nf_empresaid.'-'.$row->nf_controle.'-'.$row->nf_livro;
    }    
    exit();
    
}
//emissao nfce
if ($acao["acao"] == 9) {  
    try {
        date_default_timezone_set('America/Sao_Paulo');
    
        // Instância NFeService
   
       $nfe = new NFeService($_SESSION['BASE_ID'], 65);
        $numero_pedido=  $pedido['1'];
        $livro = $pedido['2'];
        //Gera e assina XML
        if($cpfcnpj != "") {
            $_sql = "UPDATE    ".$_SESSION['BASE'].".saidaestoque SET 
            cpfcnpj = '$cpfcnpj'                                      
            WHERE NUMERO = '$numero_pedido'";
            $statement = $pdo->prepare("$_sql"); 
            $statement->execute();
        }
    $xml = $nfe->gerarNFCe($numero_pedido, $livro);
    

    $signedXML = $nfe->assinaNFe($xml);


        //Grava XML no banco e incrementa número de NF
        $consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido'");
        $xml = $consulta->fetch(PDO::FETCH_OBJ);

        if (!$xml) {
            $dataNFC = date('Y-m-d H:m:s');
         
            $insert = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_pedido, nfed_numlivro, nfed_xml, nfed_hora) VALUES(?, ?, ?, ?)");
            $insert->bindParam(1, $numero_pedido);
            $insert->bindParam(2, $livro);
            $insert->bindParam(3, $signedXML);
            $insert->bindParam(4, $dataNFC);
            $insert->execute();

            /*$update = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET proximo_numero_nfce_producao = proximo_numero_nfce_producao + 1 WHERE id = ?");
            $update->bindParam(1, $empresa);
            $update->execute();
            */
        } else {
            $dataNFC = date('Y-m-d H:m:s');

            $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?, nfed_hora = ? WHERE nfed_pedido = ?");
            $update->bindParam(1, $signedXML);
            $update->bindParam(2, $dataNFC);
            $update->bindParam(3, $numero_pedido);
            $update->execute();
        }

        $consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido'");
        $xml = $consulta->fetch(PDO::FETCH_OBJ);

        //Transmite XML
               $recibo = $nfe->transmitir($xml->nfed_xml);
        
            $st = new Standardize();
              $stResponse = $st->toStd($recibo);
        
    //	$protocolo = $nfe->consultaChave($recibo);
        //Grava 
        $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET nfed_recibo = ? , nfed_chave = ? WHERE nfed_pedido = ?");
        $update->bindParam(1, $recibo);
        $update->bindParam(2, $_CHAVE);
        $update->bindParam(3, $numero_pedido);
        $update->execute();

//				$consulta = $pdo->query("SELECT nfed_recibo FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
//				$recibo = $consulta->fetch(PDO::FETCH_OBJ);

        //Obtem protocolo e gera XML protocolado
        //$protocolo = $nfe->consultaRecibo($recibo);
    

        $verificaProtocolo = new Standardize();
        //$verificaProtocolo = $verificaProtocolo->toStd($protocolo);
        $verificaProtocolo = $verificaProtocolo->toStd($recibo);

        $_retmotivo = $verificaProtocolo->protNFe->infProt->xMotivo;
        $_retprotocolo = $verificaProtocolo->protNFe->infProt->nProt;

        if ($verificaProtocolo->cStat != '104') {
            $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET  nfed_motivo = ? WHERE nfed_pedido = ?");
            $update->bindParam(1, $verificaProtocolo->protNFe->infProt->xMotivo);
            $update->bindParam(2, $numero_pedido);
            $update->execute();
        } else {
            $dataProtocolo = date('Y-m-d H:m:s');

            $xmlProtocolado = $nfe->autorizaXml($xml->nfed_xml,$recibo);
        
            $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
            nfed_dataautorizacao = ?, 
            nfed_xml_protocolado = ?, 
            nfed_motivo = ?,											
            nfed_protocolo =  ?
            WHERE nfed_pedido = ?");
            $update->bindParam(1, $dataProtocolo);
            $update->bindParam(2, $xmlProtocolado);
            $update->bindParam(3, $_retmotivo);				
            $update->bindParam(4, $_retprotocolo);									
            $update->bindParam(5, $numero_pedido);
            $update->execute();

            $update = $pdo->prepare("UPDATE  bd_gestorpet.notas SET nfce_chave = ? 
            WHERE nf_empresaid  = '".$pedido['0']."' and nf_controle  = '".$pedido['1']."'");
            $update->bindParam(1, $_CHAVE);        
            $update->execute();
            
        echo "Chave: $_CHAVE";
        ?>
        <button type="button" class="btn btn-success waves-effect waves-light" onclick="_imprimeNotaNfce('<?=$retornoPedido->nf_empresaid.'-'.$pedidoNum.'-'.$caixa.'-0'?>')"><span class="btn-label"><i class="fa fa-print"></i></span> Imprimir NFCe</button>
    <?php

        }


    } catch (\Exception $e) {
        //echo $e;
        echo $e->getmessage();
    }

exit();
}


if ($acao["acao"] == 0) {  

   
if($pedido['3']!=""){
    $_filid = "s.nf_id  = '".$pedido['3']."' and";   
}
$consultaPedido = $pdo->query("SELECT s.nf_controle, s.nf_data, s.nf_livro,s.nf_empresaid,
sum(s.nf_vlproduto) AS TOTAL_PRODUTOS ,
sum(s.nf_vlrservico) AS TOTAL_SERVICOS ,
sum(s.nf_total) AS TOTAL_PEDIDO,
s.nfce_chave,s.nfse_status
FROM  bd_gestorpet.notas s 
WHERE $_filid s.nf_empresaid  = '".$pedido['0']."' and s.nf_controle  = '".$pedido['1']."'  
GROUP BY s.nf_controle, s.nf_data,s.nf_empresaid,s.nfce_chave 
");
    $retornoPedido = $consultaPedido->fetch(\PDO::FETCH_OBJ);

    if($consultaPedido->rowCount() > 0) {
        $pedidoNum = $retornoPedido->nf_controle;
        $caixa =$retornoPedido->nf_livro;


        //ve se tem NFC-e gerada
        $sql="SELECT * FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$pedidoNum'";
        $stm = $pdo->prepare($sql);    
		$stm->execute();

        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-left">
                        Pedido N° - <?=$pedidoNum;?>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="ajax_load" id="dados-nf">
                        <div class="ajax_load_box">
                            <div class="ajax_load_box_circle"></div>
                            <div class="ajax_load_box_title">Aguarde, carrengando...</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <ul>
                                <li>N° de Controle: <?=$pedidoNum?></li>
                                <li>Data de Entrada: <?=date('d/m/Y', strtotime($retornoPedido->nf_data))?></li>
                                <?php if($retornoPedido->TOTAL_SERVICOS): ?>
                                    <li>Valor Serviços: R$<?=number_format($retornoPedido->TOTAL_SERVICOS, 2, ',', '.')?></li>
                                <?php endif ?>
                                <?php if($retornoPedido->TOTAL_PRODUTOS): ?>
                                    <li>Valor Produtos: R$<?=number_format($retornoPedido->TOTAL_PRODUTOS, 2, ',', '.')?></li>
                                <?php endif ?>
                                <li>Total Pedido: R$<?=number_format($retornoPedido->TOTAL_PEDIDO, 2, ',', '.')?></li>
                            </ul>
                        </div>
                        <div class="col-xs-5 text-center">
                         
                            <?php if($retornoPedido->TOTAL_SERVICOS AND $retornoPedido->nfse_status == "pendente"):?>
                                <div class="m-t-10">
                                    <button class="btn btn-success waves-effect waves-light" onclick="_dadosnf('<?=$retornoPedido->nf_empresaid.'-'.$pedidoNum.'-'.$caixa.'-0'?>')"><span class="btn-label"><i class="fa fa-gears"></i></span> Gerar NFSe Integral</button>
                                </div>
                            <?php endif ?>
                            <?php if($retornoPedido->TOTAL_PRODUTOS > 0){
                                if($stm->rowCount() > 0) { //emitida
                                    ?>
                                    <button type="button" class="btn btn-warning waves-effect waves-light" onclick="_imprimeNotaNfce('<?=$retornoPedido->nf_empresaid.'-'.$pedidoNum.'-'.$caixa.'-0'?>')"><span class="btn-label"><i class="fa fa-print"></i></span> Imprimir NFCe</button>
                                    <?php
                                }else{ ?>

                                <div class="m-t-10">
                                    <button class="btn btn-info waves-effect waves-light" onclick="_dadosnfce('<?=$retornoPedido->nf_empresaid.'-'.$pedidoNum.'-'.$caixa.'-0'?>')"><span class="btn-label"><i class="fa fa-gears"></i></span> Gerar NFCe Integral</button>
                                </div>
                                <?php
                                }
                             } ?>
                        </div>
                    </div>
                    <?php   
                 
                       /* $consulta = $pdo->query("SELECT IFNULL(e.id, (SELECT e.id FROM bd_gestorpet.empresa_cadastro e 
                        WHERE e.id = '".$_SESSION['BASE_ID']."')) AS ID, IFNULL(e.nome_fantasia, (SELECT e.nome_fantasia 
                        FROM bd_gestorpet.empresa_cadastro e 
                        WHERE e.id = '".$_SESSION['BASE_ID']."')) AS EMPRESA, s.NUMERO, s.num_livro, sum(cc_valor) AS TOTAL_PEDIDO 
                        FROM ".$_SESSION['BASE'].".saidaestoqueitem s 
                        LEFT JOIN bd_gestorpet.usuario u ON s.Cod_Colaborador = u.usuario_CODIGOUSUARIO 
                        LEFT join bd_gestorpet.contacorrente  ON cc_documento = s.NUMERO
                        LEFT JOIN bd_gestorpet.empresa_cadastro e ON cc_usuario = e.id 
                        WHERE s.NUMERO = '$pedidoNum' AND s.num_livro = '$caixa' AND s.SE_IND_PROD = '2' GROUP BY EMPRESA");
                      */
            
                        $consulta = $pdo->query("SELECT  s.nf_id,nome_fantasia,s.nf_empresaemissao,
                         s.nf_controle, s.nf_livro, s.nf_data, 
                        s.nf_nomeconsumidor	,s.nfse_status,s.nfse_url,s.nfse_ref,s.nfse_numero,
                        sum(s.nf_total) AS TOTAL_PEDIDO ,  sum(s.nf_valornf) AS TOTAL_EMITIDO,
                        sum(s.nf_vlrservico) AS TOTAL_SERVICOS ,  sum(s.nf_vlproduto) AS TOTAL_PRODUTOS
                      FROM bd_gestorpet.notas as s   
                            LEFT JOIN bd_gestorpet.usuario u ON s.nf_usuarioid = u.usuario_CODIGOUSUARIO 
                            LEFT JOIN bd_gestorpet.empresa_cadastro e ON s.nf_empresaemissao = e.id           
                            WHERE $_filid s.nf_empresaid = '".$retornoPedido->nf_empresaid."'
                            AND s.nf_controle = '".$retornoPedido->nf_controle."'
                            GROUP BY s.nf_empresaemissao,nome_fantasia, s.nf_controle, s.nf_livro, s.nf_data, 
                            s.nf_nomeconsumidor,s.nfse_status,s.nfse_url,s.nfse_ref,s.nfse_numero");
                             $retorno = $consulta->fetchAll(\PDO::FETCH_OBJ);
                   
                    ?>
                    <?php if($retorno): ?>
                        <div class="row">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">NF</th>
                                        <th class="text-center">Empresa</th>
                                        <th class="text-center">Total Serviços</th>
                                        <th class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($retorno as $row): ?>
                                    <tr class="gradeX">
                                    <?php 
                                            $_status = ""; 
                                            if($row->nfse_status == "pendente" or $row->nfse_status == "processando" or $row->nfse_status== ""){
                                            //verificar se foi solicitado emissao
                                            if($row->nfse_status== "pendente" and $row->nfse_ref != "" and $row->TOTAL_SERVICOS > 0 
                                            OR $row->nfse_status== "processando" and $row->nfse_ref != "" and $row->TOTAL_SERVICOS > 0 ){
                                                $_idref = explode("-",$_parametros['idref']);

                                                /*
                                             * consulta status nf
                                             * */
                                          
                                            $NFeFocus = new NFeFocus($row->nf_empresaemissao, 'prd');//prd
                                            $response = $NFeFocus->ConsultarNFSe( $row->nfse_ref );
                                      
                                            if($response->status == 'autorizado') {
                                                //update
                                                $_sql = "UPDATE bd_gestorpet.notas SET 
                                                nfse_cnpj_prestador = ?,
                                                nfse_ref= ?,
                                                nfse_numero_rps = ?,
                                                nfse_serie_rps = ?,
                                                nfse_status = ?,
                                                nfse_numero = ?,
                                                nfse_codigo_verificacao = ?,
                                                nfse_data_emissao = ?,
                                                nfse_url = ?,
                                                nfse_caminho_xml_nota_fiscal = ?,
                                                nf_dtautorizado = ?                                              
                                                WHERE nfse_ref = ?   ";
                                                $statement = $pdo->prepare("$_sql");            
                                            
                                               
                                                $statement->bindParam(1, $response->cnpj_prestador);
                                                $statement->bindParam(2, $response->ref);
                                                $statement->bindParam(3, $response->numero_rps);
                                                $statement->bindParam(4, $response->serie_rps);
                                                $statement->bindParam(5, $response->status);//
                                                $statement->bindParam(6, $response->numero);
                                                $statement->bindParam(7, $response->codigo_verificacao);
                                                $statement->bindParam(8, $response->data_emissao);
                                                $statement->bindParam(9, $response->url);
                                                $statement->bindParam(10, $response->caminho_xml_nota_fiscal);//
                                                $statement->bindParam(11, $response->data_emissao);                                              
                                                $statement->bindParam(12, $row->nfse_ref );                                              
                                                $statement->execute();
                                                $_url =  $response->url;
                                                $_status = '<span class="label label-success" >Autorizado</span>';
                                              
                                                }else{
                                                    $_status = '<span class="label label-success" >'.$response->status.'</span>';
                                               
                                                }
                                            }else{ 
                                                if($row->TOTAL_SERVICOS > 0) { 
                                               $_status = '<button class="btn btn-success waves-effect waves-light" onclick="_dadosnf('."'".$row->nf_empresaemissao.'-'.$row->nf_controle.'-'.$row->nf_livro.'-0-'.$row->nf_id."'".')"><span class="btn-label"><i class="fa fa-gears"></i></span> Gerar NFSe</button>';
                                                }
                                            }
                                                
                                            }else{
                                                if($row->nfse_status== "cancelado"){
                                                    $_status ='<span class="label label-danger" >Cancelado</span>';                                          
                                                }else{
                                                    if($row->TOTAL_SERVICOS > 0) { 
                                                    $_status ='<button class="btn btn-danger waves-effect waves-light" onclick="_cancelarnf('."'".$row->nf_empresaemissao.'-'.$row->nf_controle.'-'.$row->nf_livro.'-0-'.$row->nf_id."'".')"><span class="btn-label"><i class="fa fa-gears"></i></span> Cancelar NFSe</button>';
                                               }
                                            }
                                                } 

                                                ?>

                                       <td class="text-center"><a href="<?=$row->nfse_url;?>" target="_blank"> <?=$row->nfse_numero?></a></td>
                                        <td class="text-center"><?=$row->nome_fantasia?></td>
                                        <td class="text-center">R$ <?=number_format($row->TOTAL_SERVICOS, 2, ',', '.')?></td>
                                        <td class="text-center"><?= $_status;?>                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif ?>
                    <div class="row"  id="statusnf"> </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        $x =  $_parametros['id-altera'];
        
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Pedido não encontrado.<?php print_r($_parametros);?> <?=$_x;?></h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/*
 * Emissão de NFSe
 * */
elseif ($acao["acao"] == 1) {
 
    $_idref = explode("-",$_parametros['idref']);
    //$empresa-$cliente-$_baseid-$pedido-$caixa
    $_ref = $_idref['3'].str_pad($_idref['4'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
    $baseid  = $_idref['0'];
  

    //BUSCAR DADOS NF
    $consulta = $pdo->query("SELECT *
    FROM bd_gestorpet.empresa_cadastro WHERE             
    id= '".$_idref['2']."'  ");     
    $retornoNotas = $consulta->fetchAll(\PDO::FETCH_OBJ);

    $_base=$retornoNotas[0]->base;

    //montar array

    //buscar dados empresa
    $consulta = $pdo->query("SELECT c.*, d.item_lista_servico, d.aliquota_nota,
    d.codigo_cnae, d.tributacao, c.regime_tributario, d.ativo_nfse,
     d.numero_aedf, d.codigo_tributario_municipio 
    FROM bd_gestorpet.empresa_cadastro c 
    LEFT JOIN bd_gestorpet.empresa_dados d ON c.id = d.id
     WHERE c.id = '".$_idref['0']."'");
    $retornoEmpresa = $consulta->fetch(\PDO::FETCH_OBJ);
    //buscar dados cliente
    $_nomeconsumidor =  $_parametros['nomeconsumidor'];  

    $consulta = $pdo->query("SELECT * FROM ".$_base.".consumidor WHERE CODIGO_CONSUMIDOR = '".$_idref['1']."'");

    $retornoCliente = $consulta->fetch(\PDO::FETCH_OBJ);

    //verificar se esta liberado
    if($retornoEmpresa->ativo_nfse == 0){
             $_errorlog = $_errorlog.$xx."- NFSe não liberada para emissão!\n";
    }

     //verificar descrição
     if($_parametros['servico'] == ""){
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
            <button class="btn btn-success waves-effect waves-light" onclick="_emiteNota()"><span class="btn-label"><i class="fa fa-print"></i></span> Emitir NFSe</button>
       </div>
            <?php
            exit();
       }    

    if($retornoCliente->Tipo_Pessoa == 1){
        $_tipopessoa = 'cpf';
    }else{
        $_tipopessoa = 'cnpj';
    }

    $consulta = $pdo->query("SELECT cod_cidade FROM minhaos_cep.cidade 
    WHERE estado = '".$retornoCliente->UF."' AND cidade = '".$retornoCliente->CIDADE."'");
    $codigo_municipio = ($consulta->fetch(\PDO::FETCH_OBJ))->cod_cidade;

    $prestador = array(
        "cnpj" => $retornoEmpresa->cnpj,
        "inscricao_municipal" => $retornoEmpresa->inscricao_municipal,
        "codigo_municipio" => $retornoEmpresa->codigo_municipio
    );
    $tomador = array(
        "$_tipopessoa" => $retornoCliente->CGC_CPF,
        "razao_social" => $_nomeconsumidor,
        "email" => $retornoCliente->EMail,
        "endereco" => array(
            "logradouro" => $retornoCliente->Nome_Rua,
            "numero" => $retornoCliente->Num_Rua,
            "bairro" => $retornoCliente->BAIRRO,
            "complemento" => $retornoCliente->complemento,                       
            "codigo_municipio" => "$codigo_municipio",          
            "uf" => $retornoCliente->UF,
            "cep" => $retornoCliente->CEP)
        );

        $_vlservico = $_parametros['nota-base'];         
       // "aliquota":3,
        if( $retornoEmpresa->numero_aedf != "") { 
        
            $servico = array(         
                "discriminacao" => $_parametros['servico'],
                "iss_retido" => "false",
                "valor_iss" => $_parametros['nota-iss'],
                "base_calculo" => $_parametros['nota-base'],
                "aliquota" => $_parametros['nota-aliquota'],           
                "item_lista_servico" => $_parametros['item-servico'],   
                "numero_aedf" => $retornoEmpresa->numero_aedf,
                "codigo_tributario_municipio" => $retornoEmpresa->codigo_tributario_municipio,
                "codigo_cnae" => $retornoEmpresa->codigo_cnae, 
                'valor_servicos' => $_vlservico 
            );
        }else{
            $servico = array(         
                "discriminacao" => $_parametros['servico'],
                "iss_retido" => "false",
                "valor_iss" => $_parametros['nota-iss'],
                "base_calculo" => $_parametros['nota-base'],
                "aliquota" => $_parametros['nota-aliquota'],           
                "item_lista_servico" => $_parametros['item-servico'],              
                "codigo_cnae" => $retornoEmpresa->codigo_cnae, 
                'valor_servicos' => $_vlservico 
            );

        }


        if($retornoEmpresa->regime_tributario == 1) {
            $_simples = "true";
        }else{
            $_simples = "false";
        }
        if($retornoEmpresa->NaturezaOperacaoEspecial != "") {
            $_NaturezaOperacao = $retornoEmpresa->NaturezaOperacaoEspecial;
        }else{
            $_NaturezaOperacao = $retornoEmpresa->regime_tributario;
        }

        

        $nfse =  array (
            "data_emissao" => date("Y-m-d\TH:i:sP"),
            "natureza_operacao" => $_NaturezaOperacao,
            "optante_simples_nacional" => $_simples);
                              
              $nfse += array('prestador' =>$prestador);
              $nfse += array('tomador' =>$tomador);
              $nfse += array('servico' =>$servico);


$_sql = "UPDATE bd_gestorpet.notas SET     
nfse_status = ?, nfse_ref = ?, nf_valornf = ?
WHERE nf_empresaid = ? and
nf_empresaemissao = ? and
nf_controle = ?";
$statement = $pdo->prepare("$_sql");     
$_status = "processando"  ;
$ativit = new atividade();
$log = $ativit->log_insert(json_encode($nfse),'0',$_ref,$_SESSION['BASE']);
$statement->bindParam(1, $_status);//   
$statement->bindParam(2, $_ref);//  
$statement->bindParam(3, $_vlservico);// 
$statement->bindParam(4, $_idref['2']);
$statement->bindParam(5, $_idref['0']);
$statement->bindParam(6, $_idref['3']);
$statement->execute();


    $NFeFocus = new NFeFocus($_idref['0'], 'prd');//prd   
    $response = $NFeFocus->gerarNFSe($_ref,$nfse);
print_r($response);
    if($response != ""){
        //salva log
        $msg = $_idref['3'];
        $texto = $response->body->mensagem;
        $codigo_focus = $response->http_code;
        $ativit = new atividade();
        $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);
    }
  
    if($response == true) {
      //  print_r($response);
      //gerar debito
        //cobra acesso diario
        $movimento = new Movimento;
        $_obs = "Emissão NFSe-$_ref";
        $movimento->cadastraMovimento(8, $_obs);
        ?>
      
            <div class="alert alert-warning" role="alert"> 
                <strong><i class="fa fa-spin fa-gear " style="color:#000000"></i>   <span class="text-muted" style="color:#000000">(Processando Autorização  )</span><br></strong>
                Você pode aguardar ou pesquisar daqui alguns instantes
            </div>
              
        <?php }
    

exit();

 } elseif ($acao["acao"] == 7) {
    $_idref = explode("-",$_parametros['pedido-dados']);

    /*
 * cancelamento status nf
 * */

$NFeFocus = new NFeFocus($_idref['0'], 'prd');//prd
$_ref = $_idref['1'].str_pad($_idref['2'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
$response = $NFeFocus->CancelamentoNFSe($_ref);


if ($response->http_code == '200' || $response->http_code == '201') {
    $response = $response->body;
   
} else {
    ?><div  class="alert alert-warning" role="alert" style="text-align: center;"> 
       <span class="text-muted" style="color:#000000; ">       
    <?php print_r($response->body->mensagem );?>
       </span>
</div>


<?php exit(); 
}
    if($response->status == 'cancelado' ) {
        
        if($response != ""){
            //salva log
            $msg = $_idref['3'];
            $texto = $response->body->mensagem;
            $codigo_focus = $response->http_code;
            $ativit = new atividade();
            $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);
        }

        //update
        $_sql = "UPDATE bd_gestorpet.notas SET     
        nfse_status = 'cancelado',nf_dtcancelado = now()    
        WHERE nfse_ref = ?  and nfse_status <> 'cancelado'        ";
        $statement = $pdo->prepare("$_sql");              
        $statement->bindParam(1, $_ref);     
        $statement->execute();
    
        ?><div  class="alert alert-danger" role="alert"> 
        Cancelamento Efetuado
             
            </div>
    
            
        <?php exit(); 
    }

}elseif ($acao["acao"] == 6) {
    $_idref = explode("-",$_parametros['idref']);
   

    /*
 * consulta status nf
 * */
$NFeFocus = new NFeFocus($_idref['0'], 'prd');//prd

//$response = $NFeFocus->consultaItemServico('4106902');
//print_r($response);
//exit();
//print_r( $NFeFocus);
//exit();

$_ref = $_idref['3'].str_pad($_idref['4'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
$response = $NFeFocus->ConsultarNFSe($_ref);

if($response->status == 'autorizado') {

    if($response != ""){
        //salva log
        $msg = $_idref['3'];
        $texto = $response->body->mensagem;
        $codigo_focus = $response->http_code;
        $ativit = new atividade();
        $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);
    }

    //update
    $_sql = "UPDATE bd_gestorpet.notas SET 
    nfse_cnpj_prestador = ?,
    nfse_ref= ?,
    nfse_numero_rps = ?,
    nfse_serie_rps = ?,
    nfse_status = ?,
    nfse_numero = ?,
    nfse_codigo_verificacao = ?,
    nfse_data_emissao = ?,
    nfse_url = ?,
    nfse_caminho_xml_nota_fiscal = ?,
    nf_dtautorizado = ?   
    WHERE nf_empresaid = ? and
    nf_empresaemissao = ? and
    nf_controle = ?";
    $statement = $pdo->prepare("$_sql");
    $statement->bindParam(1, $response->cnpj_prestador);
    $statement->bindParam(2, $response->ref);
    $statement->bindParam(3, $response->numero_rps);
    $statement->bindParam(4, $response->serie_rps);
    $statement->bindParam(5, $response->status);//
    $statement->bindParam(6, $response->numero);
    $statement->bindParam(7, $response->codigo_verificacao);
    $statement->bindParam(8, $response->data_emissao);
    $statement->bindParam(9, $response->url);
    $statement->bindParam(10, $response->caminho_xml_nota_fiscal);//
    $statement->bindParam(11, $response->data_emissao);
    $statement->bindParam(12, $_idref['2']);
    $statement->bindParam(13, $_idref['0']);
    $statement->bindParam(14, $_idref['3']);
    $statement->execute();
  
    ?><div id="ok10" class="alert alert-success" role="alert"> 
       <a href="<?=$response->url;?>" target="_blank"> <button class="btn btn-success waves-effect waves-light" ><span class="btn-label"><i class="fa fa-print"></i></span> Imprimir NFSe</button></a>           
              
        </div>
   
          
    <?php exit(); 
    }else{
        if($response != ""){
            //salva log
            $msg = $_idref['3'];
            $texto = $response->body->mensagem;
            $codigo_focus = $response->http_code;
            $ativit = new atividade();
            $log = $ativit->log_insert($texto,$codigo_focus,$msg,$_SESSION['BASE']);
        }
        ?>
  
        <div class="alert alert-warning" role="alert"> 
        <strong>
            <i class="fa fa fa-spin fa-spinner " style="color:#000000">   </i>   <span class="text-muted" style="color:#000000">( Aguardando Autorização <?=$_ref;?> )</span><br>
            </strong> Você pode aguardar ou pesquisar daqui alguns instantes     
            
        </div>
          
    <?php exit(); 
    }



?>
      
<div class="alert alert-warning" role="alert"> 
     <strong>
    <i class="fa fa-spin fa-spinner  " style="color:#000000">   </i>   <span class="text-muted" style="color:#000000">(Processando Autorização-1  )</span><br>
    </strong> Você pode aguardar ou pesquisar daqui alguns instantes
</div>
  
<?php 
}elseif ($acao["acao"] == 7) {
    $_idref = explode("-",$_parametros['idref']);
   
//VERIFICAR Notas pedentes
    /*
 * consulta status nf
 * */
$NFeFocus = new NFeFocus($_idref['0'], 'prd');//prd

//$response = $NFeFocus->consultaItemServico('4106902');
//print_r($response);
//exit();
//print_r( $NFeFocus);
//exit();

$_ref = $_idref['3'].str_pad($_idref['4'] , 2 , '0' , STR_PAD_LEFT).str_pad($_idref['0'] , 5 , '0' , STR_PAD_LEFT);
$response = $NFeFocus->ConsultarNFSe($_ref);
if($response->status == 'autorizado') {
    //update
    $_sql = "UPDATE bd_gestorpet.notas SET 
    nfse_cnpj_prestador = ?,
    nfse_ref= ?,
    nfse_numero_rps = ?,
    nfse_serie_rps = ?,
    nfse_status = ?,
    nfse_numero = ?,
    nfse_codigo_verificacao = ?,
    nfse_data_emissao = ?,
    nfse_url = ?,
    nfse_caminho_xml_nota_fiscal = ?,
    nf_dtautorizado = ?   
    WHERE nf_empresaid = ? and
    nf_empresaemissao = ? and
    nf_controle = ?";
    $statement = $pdo->prepare("$_sql");            

   
    $statement->bindParam(1, $response->cnpj_prestador);
    $statement->bindParam(2, $response->ref);
    $statement->bindParam(3, $response->numero_rps);
    $statement->bindParam(4, $response->serie_rps);
    $statement->bindParam(5, $response->status);//
    $statement->bindParam(6, $response->numero);
    $statement->bindParam(7, $response->codigo_verificacao);
    $statement->bindParam(8, $response->data_emissao);
    $statement->bindParam(9, $response->url);
    $statement->bindParam(10, $response->caminho_xml_nota_fiscal);//
    $statement->bindParam(11, $response->data_emissao);    
    $statement->bindParam(12, $_idref['2']);
    $statement->bindParam(13, $_idref['0']);
    $statement->bindParam(14, $_idref['3']);
    $statement->execute();
  
    ?><div id="ok10" class="alert alert-success" role="alert"> 
       <a href="<?=$response->url;?>" target="_blank"> <button class="btn btn-success waves-effect waves-light" ><span class="btn-label"><i class="fa fa-print"></i></span> Imprimir NFSe</button></a>           
              
        </div>
   
          
    <?php exit(); 
    }else{
        ?>
  
        <div class="alert alert-warning" role="alert"> 
        <strong>
            <i class="fa fa fa-spin fa-spinner " style="color:#000000">   </i>   <span class="text-muted" style="color:#000000">( Aguardando Autorização <?=$_ref;?> )</span><br>
            </strong> Você pode aguardar ou pesquisar daqui alguns instantes     
            
        </div>
          
    <?php exit(); 
    }


}elseif ($acao["acao"] == 2) {
    /*
 * Lista Pedidos
 * */
    !empty($_parametros["pedido-num"]) ? $pedidoNum = "AND s.nf_controle = '". $_parametros["pedido-num"] ."' " : $pedidoNum = '';
    if($_parametros["pedido-situacao"] == "pendente"){
        $situacao = " AND s.nfse_status = 'pendente' ";
    }elseif($_parametros["pedido-situacao"] == "autorizado"){
        $situacao = " AND s.nfse_status = 'autorizado' ";
    }elseif($_parametros["pedido-situacao"] == "processando"){
        $situacao = " AND s.nfse_status = 'processando' ";
    }
    elseif($_parametros["pedido-situacao"] == "cancelado"){
        $situacao = " AND s.nfse_status = 'cancelado' ";
    }
    !empty($_parametros["pedido-colaborador"]) ? $colaborador = " AND s.nf_usuarioid = '".$_parametros["pedido-colaborador"]."' " : $colaborador = '';
    !empty($_parametros["pedido-empresa"]) ? $empresa = " AND e.id = '".$_parametros["pedido-empresa"]."' " : $empresa = '';

    $detalhado = $_parametros["nf-detalhado"];
 
  
    try {
         if($detalhado !=  1) { 
          
            $_GROUP = "GROUP BY s.nf_empresaid, s.nf_controle, s.nf_livro";
         }else{
            $_filid = "s.nf_id ,";   
            $_GROUP = "group by  $_filid nf_empresaemissao,s.nf_controle, s.nf_livro,
            s.nfse_status,s.nfse_ref,s.nf_empresaemissao";
            $_fantasia = "nome_fantasia,s.nfse_status,";   
          
         }

       // echo $_parametros['pedido-empresa']." == ".$_SESSION['BASE_ID']."& empty($colaborador)";
       /* if($_parametros['pedido-empresa'] == $_SESSION['BASE_ID'] && empty($colaborador)) {
            $statement = $pdo->query("SELECT s.nf_controle, s.nf_livro, s.nf_data, s.nf_nomeconsumidor	,
             sum(s.nf_total) AS TOTAL_PEDIDO,s.nfse_status 
            FROM bd_gestorpet.notas as s            
            WHERE s.nf_data BETWEEN '".$_parametros['pedido-inicial']."' AND '".$_parametros['pedido-final']."'
             AND s.Cod_Colaborador = '0' $pedidoNum $situacao GROUP BY s.NUMERO,nfse_status ORDER BY s.NUMERO");
            $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
        } else {
            */
            //verificar empresas participantes
            $consultaLinha = $pdo->query("Select colaborador_empresa
            from bd_gestorpet.colaborador  
            where  colaborador_usuario = '".$_SESSION['IDUSER']."' ");
                $retornoLinha = $consultaLinha->fetchAll();
                $_FILTRO = "";
                if($consultaLinha->rowCount() > 0) {
                        foreach ($retornoLinha as $row) {          
                            $_IDEMPRESAS =   $row['colaborador_empresa'];
                     
                            if($_FILTRO == ""){
                                $_FILTRO = "s.nf_empresaid = '".$_IDEMPRESAS."' AND
                                s.nf_data BETWEEN '".$_parametros['pedido-inicial']."' AND '".$_parametros['pedido-final']."'             
                                $pedidoNum $situacao $colaborador $empresa";
                            }else{
                                $_FILTRO = "OR s.nf_empresaid = '".$_IDEMPRESAS."' AND
                                s.nf_data BETWEEN '".$_parametros['pedido-inicial']."' AND '".$_parametros['pedido-final']."'             
                                $pedidoNum $situacao $colaborador $empresa";
                            }
                        }
                    }else{
                        $_FILTRO = "s.nf_empresaid = '".$_SESSION['BASE_ID']."' AND
                        s.nf_data BETWEEN '".$_parametros['pedido-inicial']."' AND '".$_parametros['pedido-final']."'             
                        $pedidoNum $situacao $colaborador $empresa";
                        }

          

            $sql = "SELECT  $_filid $_fantasia s.nf_empresaid,
            s.nf_controle, s.nf_livro, s.nf_data, s.nf_nomeconsumidor	,
             sum(s.nf_total) AS TOTAL_PEDIDO ,  sum(s.nf_valornf) AS TOTAL_EMITIDO,
             sum(s.nf_vlrservico) AS TOTAL_SERVICOS ,  sum(s.nf_vlproduto) AS TOTAL_PRODUTOS,
             s.nfse_ref,s.nf_empresaemissao
            FROM bd_gestorpet.notas as s   
            LEFT JOIN bd_gestorpet.usuario u ON s.nf_usuarioid = u.usuario_CODIGOUSUARIO  
            LEFT JOIN bd_gestorpet.empresa_cadastro e ON s.nf_empresaemissao = e.id
            LEFT JOIN ".$_SESSION['BASE'].".NFE_DADOS y ON s.nf_controle = y.nfed_pedido       
            WHERE 
            $_FILTRO $_GROUP  ORDER BY s.nf_hora";
            $stm = $pdo->prepare("$sql");    
			$stm->execute();           
            $retorno = $stm->fetchAll(\PDO::FETCH_OBJ);
       // }
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
    ?>
    <div class="ajax_load" id="lista-pedidos">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <div class="ajax_load_box_title">Aguarde, carrengando...</div>
        </div>
    </div>
    <?php if($detalhado !=  1) { 
    ?>
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center">Status</th>   
                <th class="text-center">Nº Controle</th>    
                <th class="text-center">Data</th>
                <th class="text-center">Consumidor</th>               
                <th class="text-center">Vlr Total</th>  
                <th class="text-center">Vlr Emitido</th>                
            </tr>
        </thead>
        <tbody>
        <?php 
       
        foreach ($retorno as $row): 
            $_status = ""; 
            $_statusret = "";
           $NFSTATUS = $pdo->query("SELECT  nfse_status,nfse_ref,nf_empresaemissao,nfse_status
           FROM bd_gestorpet.notas 
           WHERE nf_empresaid = '".$row->nf_empresaid."'       
           AND nf_controle = '".$row->nf_controle."'                  
           GROUP BY nfse_status,nfse_ref,nf_empresaemissao,nfse_status");  
           $retnf = $NFSTATUS->fetchAll(\PDO::FETCH_OBJ);
           foreach ($retnf as $rownf):
            if($rownf->nfse_status == "autorizado"){
                $_cor = "label-success";
                $_statusret = "autorizado";
            }elseif($rownf->nfse_status == "pendente"){
                $_cor = "label-default";
                $_statusret = "pendente";
                //verificar se existe notas para consulta
                                //verificar se foi solicitado emissao
                                if( $rownf->nfse_ref != "" and $rownf->nfse_status == "pendente"){                                 

                                /*
                                * consulta status nf
                                * */
                          
                               $NFeFocus = new NFeFocus($rownf->nf_empresaemissao, 'prd');//prd
                               $response = $NFeFocus->ConsultarNFSe( $rownf->nfse_ref );
                        
                                if($response->status == 'autorizado') {
                                    //update
                                  
                                    $_sql = "UPDATE bd_gestorpet.notas SET 
                                    nfse_cnpj_prestador = ?,
                                    nfse_ref= ?,
                                    nfse_numero_rps = ?,
                                    nfse_serie_rps = ?,
                                    nfse_status = ?,
                                    nfse_numero = ?,
                                    nfse_codigo_verificacao = ?,
                                    nfse_data_emissao = ?,
                                    nfse_url = ?,
                                    nfse_caminho_xml_nota_fiscal = ?,
                                    nf_dtautorizado = ?                                              
                                    WHERE nfse_ref = ?   ";
                                    $statement = $pdo->prepare("$_sql");            
                                
                                
                                    $statement->bindParam(1, $response->cnpj_prestador);
                                    $statement->bindParam(2, $response->ref);
                                    $statement->bindParam(3, $response->numero_rps);
                                    $statement->bindParam(4, $response->serie_rps);
                                    $statement->bindParam(5, $response->status);//
                                    $statement->bindParam(6, $response->numero);
                                    $statement->bindParam(7, $response->codigo_verificacao);
                                    $statement->bindParam(8, $response->data_emissao);
                                    $statement->bindParam(9, $response->url);
                                    $statement->bindParam(10, $response->caminho_xml_nota_fiscal);//
                                    $statement->bindParam(11, $response->data_emissao);                                              
                                    $statement->bindParam(12, $rownf->nfse_ref );                                              
                                    $statement->execute();
                                    
                                    $_url =  $response->url;
                                
                                    $_statusret = 'autorizado';
                                    $_cor = "label-success";
                                    }else{
                                        $_statusret = $response->status;
                                
                                    }
                                }

                //-------------------------------------
             
               }elseif($rownf->nfse_status == "negado" or $rownf->nfse_status  == "cancelado"){
                $_cor = "label-danger";
                $_statusret  = $rownf->nfse_status;
               }else{
                $_cor = "label-warning";
                $_statusret  = $rownf->nfse_status;
               }
          
            $_status =  $_status.'<span class="label '. $_cor.'">'.$_statusret .'</span>';
           endforeach
       
            ?>
            <tr class="gradeX"> 
                <td class="text-center"><?=$_status;?></td>     
                <td class="text-center"><a href="#" onclick="_buscadados('<?=$row->nf_empresaid;?>-<?=$row->nf_controle;?>-<?=$row->nf_livro;?>')"><?=$row->nf_controle?></a></td>
                <td class="text-center"><?=date('d/m/Y', strtotime($row->nf_data))?></td>
                <td class="text-center"><?=empty($row->nf_nomeconsumidor) ? 'Não Informado' : $row->nf_nomeconsumidor?></td>
                <td class="text-center">R$ <?=number_format($row->TOTAL_PEDIDO, 2, ',', '.')?></td>
                <td class="text-center">R$ <?=number_format($row->TOTAL_EMITIDO, 2, ',', '.')?></td>                
        <?php
        $_totalnf = $_totalnf + $row->TOTAL_PEDIDO;
        endforeach ?>
        </tbody>
    </table>
    <div class="alert alert-info">
        
        Total Geral<strong>R$ <?=number_format($_totalnf, 2, ',', '.')?></strong>
    </div>
    <?php
    }else{ ?>
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center">Status</th>
                <th class="text-center">Nº Controle</th>    
                <th class="text-center">Data</th>
                <th class="text-center">Consumidor</th>
                <th class="text-center">Empresa</th>                    
                <th class="text-center">Vlr Produtos</th>
                <th class="text-center">Vlr Serviços</th>
                <th class="text-center">Vlr Total</th>
                <th class="text-center">Vlr Emitido</th>       
            </tr>
        </thead>
        <tbody>
        <?php foreach ($retorno as $row):
             $_status = ""; 
             $_statusret = "";
              //verificar se existe notas para consulta
                                //verificar se foi solicitado emissao
                                if( $row->nfse_ref != "" and $row->nfse_status == "pendente") {                                

                                    /*
                                    * consulta status nf
                                    * */
                              
                                   $NFeFocus = new NFeFocus($row->nf_empresaemissao, 'prd');//prd
                                   $response = $NFeFocus->ConsultarNFSe( $row->nfse_ref );
                            
                                    if($response->status == 'autorizado') {
                                        //update
                                      
                                        $_sql = "UPDATE bd_gestorpet.notas SET 
                                        nfse_cnpj_prestador = ?,
                                        nfse_ref= ?,
                                        nfse_numero_rps = ?,
                                        nfse_serie_rps = ?,
                                        nfse_status = ?,
                                        nfse_numero = ?,
                                        nfse_codigo_verificacao = ?,
                                        nfse_data_emissao = ?,
                                        nfse_url = ?,
                                        nfse_caminho_xml_nota_fiscal = ?,
                                        nf_dtautorizado = ?                                              
                                        WHERE nfse_ref = ?   ";
                                        $statement = $pdo->prepare("$_sql");            
                                    
                                    
                                        $statement->bindParam(1, $response->cnpj_prestador);
                                        $statement->bindParam(2, $response->ref);
                                        $statement->bindParam(3, $response->numero_rps);
                                        $statement->bindParam(4, $response->serie_rps);
                                        $statement->bindParam(5, $response->status);//
                                        $statement->bindParam(6, $response->numero);
                                        $statement->bindParam(7, $response->codigo_verificacao);
                                        $statement->bindParam(8, $response->data_emissao);
                                        $statement->bindParam(9, $response->url);
                                        $statement->bindParam(10, $response->caminho_xml_nota_fiscal);//
                                        $statement->bindParam(11, $response->data_emissao);                                              
                                        $statement->bindParam(12, $row->nfse_ref );                                              
                                        $statement->execute();
                                        
                                        $_url =  $response->url;
                                    
                                        $_statusret = 'autorizado';
                                        $_cor = "label-success";
                                        }else{
                                            $_statusret = $response->status;
                                    
                                        }
                                    }
    
                    //-------------------------------------
             
             ?>
            <tr class="gradeX">
               <?php 
             if($row->nfse_status == "autorizado" or $_statusret == 'autorizado'){
                $_cor = "label-success";
                $_statusret = 'autorizado';
            }elseif($row->nfse_status == "pendente"){
                $_cor = "label-default";
                $_statusret = 'pendente';
               }elseif($row->nfse_status == "negado" or $row->nfse_status == "cancelado"){
                $_cor = "label-danger";
                $_statusret = 'cancelado';
               }else{
                $_cor = "label-warning";
                $_statusret =$row->nfse_status;
               }
               $_status =  $_status.'<span class="label '. $_cor.'">'.$_statusret.'</span>';
               ?>
         
                <td class="text-center"><?=$x;?> <?=$_status;?></td>
                <td class="text-center"><a href="#" onclick="_buscadados('<?=$row->nf_empresaid;?>-<?=$row->nf_controle;?>-<?=$row->nf_livro;?>-<?=$row->nf_id ;?>')"><?=$row->nf_controle?></a></td>
                <td class="text-center"><?=date('d/m/Y', strtotime($row->nf_data))?></td>
                <td class="text-center"><?=empty($row->nf_nomeconsumidor) ? 'Não Informado' : $row->nf_nomeconsumidor?></td>
                <td class="text-center"><?=empty($row->nome_fantasia) ? 'Não Informado' : $row->nome_fantasia?></td>
                <td class="text-center">R$ <?=number_format($row->TOTAL_PRODUTOS, 2, ',', '.')?></td>
                <td class="text-center">R$ <?=number_format($row->TOTAL_SERVICOS, 2, ',', '.')?></td>
                <td class="text-center">R$ <?=number_format($row->TOTAL_PEDIDO, 2, ',', '.')?></td>
                <td class="text-center">R$ <?=number_format($row->TOTAL_EMITIDO, 2, ',', '.')?></td>  
                
              
            </tr>
        <?php  $_totalnf = $_totalnf + $row->TOTAL_PEDIDO;
        endforeach ?>
        </tbody>
    </table>
    <div class="alert alert-info">
        Total <strong>R$ <?=number_format($_totalnf, 2, ',', '.')?></strong>
    </div>
    <?php }
}

/**
 * Exibe NFCe a gerar
 */
elseif ($acao['acao'] == 4) { 
    $dados = explode('-', $_parametros['pedido-dados']);
    $empresa = $dados[0];
    $pedidoNum = $dados[1];
    $caixa = $dados[2];
    $integral = $dados[3];
    $_idnf = $dados[4];


    $consulta = $pdo->query("SELECT SUM(nf_vlrservico) AS totalServ,SUM(nf_vlproduto) AS totalProduto,nf_idconsumidor,base,nf_usuarioid
    FROM bd_gestorpet.notas       
    LEFT JOIN bd_gestorpet.empresa_cadastro  ON nf_empresaid = id
    WHERE             
    nf_empresaid= '$empresa'  AND
    nf_controle= '$pedidoNum'");     
    $retornoNotas = $consulta->fetchAll(\PDO::FETCH_OBJ);
    
    $_base=$retornoNotas[0]->base;
    $totalPedido=$retornoNotas[0]->totalServ;
    $totalProduto=$retornoNotas[0]->totalProduto;
    ?>
        <div class="modal-dialog modal-dialog-overflow ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">
                        <?=$xx;?>     Dados da NFCe
                        </h4>
                    </div>
                  
                        <div class="row" style="margin:3px;">
                        
                         
                            <div class="col-xs-6"><p>  <strong>N° Controle:</strong> <?=$pedidoNum?></p>                               
                                    <?php if($totalProduto): ?>
                                        <li>Total Produtos: R$<?=number_format($totalProduto, 2, ',', '.')?></li>
                                    <?php endif ?>
                                    </div>   
                            
                            </div>
                        
                        <div class="row" >
                        <div class="panel panel-border panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">CPF / CNPJ:</h3>
                                </div>
                        
                          <div class="col-xs-7">
                                    
                                       <p> <input type="text" name="cpf-cnpj" id="cpf-cnpj" class="form-control" value="">
                                       </p>
                                    </div>
                                    </div>
                        <?php 
                                                $consulta = $pdo->query("SELECT * 
                                                FROM ".$_base.".saidaestoqueitem s                                               
                                                WHERE s.NUMERO = '$pedidoNum'
                                                AND s.SE_IND_PROD <> '2' and SE_NUMNFSE = 0");
                        $retorno = $consulta->fetchAll(\PDO::FETCH_OBJ);
                        ?>
                        <div class="row">
                          <div class="col-xs-7"> </div>
                          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Produto</th>
                                        <th class="text-center">Qtde</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($retorno as $row): ?>
                                    <tr class="gradeX">
                                        <td class="text-center"><?=$row->DESCRICAO_ITEM?></td>
                                        <td class="text-center"><?=$row->QUANTIDADE?></td>
                                        <td class="text-center">R$ <?=number_format($row->VALOR_TOTAL, 2, ',', '.')?></td>
                                        
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>  
                        <div class="row"  >
                          <div class="col-xs-12" style="text-align: right;"  id="result-nfce" >
                          <button type="button" class="btn btn-success waves-effect waves-light" onclick="_emiteNotaNfce()"><span class="btn-label"><i class="fa fa-print"></i></span> Emitir NFCe</button>
                          </div>
                        </div>  
                    
                </div>  
         </div>   
    <?php

}
/**
 * Exibe NFse a gerar
 */
elseif ($acao['acao'] == 5) {
    $dados = explode('-', $_parametros['pedido-dados']);
    if(count($dados) > 2) {
        $empresa = $dados[0];
        $pedido = $dados[1];
        $caixa = $dados[2];
        $integral = $dados[3];
        $_idnf = $dados[4];
        // die($integral);

      

      if($integral == 0) {
              
   
            //buscar valores emissao
            $consulta = $pdo->query("SELECT SUM(nf_vlrservico) AS totalServ,nf_idconsumidor,base,nf_usuarioid,
            nf_empresaid,nf_nomeconsumidor
            FROM bd_gestorpet.notas       
            LEFT JOIN bd_gestorpet.empresa_cadastro  ON nf_empresaid = id
            WHERE             
            nf_empresaemissao= '$empresa'  AND
            nf_controle= '$pedido'");     
            $retornoNotas = $consulta->fetchAll(\PDO::FETCH_OBJ);
            
            $_baseid=$retornoNotas[0]->nf_empresaid;
            $_base=$retornoNotas[0]->base;
            $totalPedido=$retornoNotas[0]->totalServ;
            $nomeconsumidor=$retornoNotas[0]->nf_nomeconsumidor; 

       $consulta = $pdo->query("SELECT DESCRICAO, COD_CLIENTE, sum(cc_valor) AS VALOR_TOTAL
            FROM ".$_base.".saidaestoqueitem s 
            INNER JOIN ".$_base.".itemestoque i ON s.CODIGO_ITEM = i.CODIGO_FORNECEDOR 
            LEFT JOIN bd_gestorpet.usuario u ON s.Cod_Colaborador = u.usuario_CODIGOUSUARIO 
            LEFT JOIN bd_gestorpet.empresa_cadastro e ON u.usuario_empresa = e.id
            LEFT join bd_gestorpet.contacorrente as t ON t.cc_documento = s.NUMERO
            WHERE s.NUMERO = '$pedido'
            AND s.Ind_Aut = '1' AND t.cc_numnfse = '0' and  s.SE_IND_PROD = '2'
            ");//and t.cc_usuario = 0
            $retornoPedido = $consulta->fetchAll(\PDO::FETCH_OBJ);
     

        } else {
           //buscar valores emissao
              //buscar valores emissao
              $consulta = $pdo->query("SELECT SUM(nf_vlrservico) AS totalServ,nf_idconsumidor,base,
              nf_usuarioid,nf_empresaid,nf_nomeconsumidor
              FROM bd_gestorpet.notas       
              LEFT JOIN bd_gestorpet.empresa_cadastro  ON nf_empresaid = id
              WHERE             
              nf_empresaid= '$empresa'  AND
              nf_controle= '$pedido'");     
              $retornoNotas = $consulta->fetchAll(\PDO::FETCH_OBJ);

              $_baseid=$retornoNotas[0]->nf_empresaid;
              $_base=$retornoNotas[0]->base;
              $totalPedido=$retornoNotas[0]->totalServ;
              $nomeconsumidor=$retornoNotas[0]->nf_nomeconsumidor; 

            $consulta = $pdo->query("SELECT DESCRICAO, COD_CLIENTE, VALOR_TOTAL
             FROM ".$_base.".saidaestoqueitem s 
             INNER JOIN ".$_base.".itemestoque i ON s.CODIGO_ITEM = i.CODIGO_FORNECEDOR 
             WHERE s.NUMERO = '$pedido' 
             AND s.Ind_Aut = '1' AND s.SE_NUMNFSE = '0' AND s.SE_IND_PROD = '2'");
            $retornoPedido = $consulta->fetchAll(\PDO::FETCH_OBJ);
            
           
        }
        //busca cpfnaNota
        $sql = "select cpfcnpj from ".$_base.".saidaestoque where NUMERO = '$pedido'";
        
        $stm = $pdo->prepare($sql);	
        $stm->execute();
        $rstCPF = $stm->fetchAll(\PDO::FETCH_OBJ);
        $cpfcnpj=$rstCPF[0]->cpfcnpj; 


        $consulta = $pdo->query("SELECT c.*, d.item_lista_servico, d.aliquota_nota 
        FROM bd_gestorpet.empresa_cadastro c 
        LEFT JOIN bd_gestorpet.empresa_dados d ON c.id = d.id WHERE c.id = '$empresa'");
        $retornoEmpresa = $consulta->fetch(\PDO::FETCH_OBJ);

        $consulta = $pdo->query("SELECT * FROM bd_gestorpet.item_lista_servico ORDER BY id");
        $servicos = $consulta->fetchAll(\PDO::FETCH_OBJ);
      

        $cliente = intval($retornoPedido[0]->COD_CLIENTE); 

        if($cpfcnpj == ""){
          $cpfcnpj = $retornoCliente->CGC_CPF;
        }
    
       
        if(!empty($cliente)) {
           $consulta = $pdo->query("SELECT * FROM ".$_base.".consumidor WHERE CODIGO_CONSUMIDOR = '$cliente'");
            $retornoCliente = $consulta->fetch(\PDO::FETCH_OBJ);

            ?>
            <div class="modal-dialog modal-dialog-overflow modal-exlg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-left">
                        <?=$xx;?>      Dados da NFSe
                        </h4>
                    </div>
                    <form action="ACNTFCE" method="post" enctype="multipart/form-data" name="form-valores" id="form-valores">
                        <div class="modal-body modal-body-overflow" id="imagem-carregando">
                            <?php $idref = "$empresa-$cliente-$_baseid-$pedido-$caixa";?>
                        <input type="hidden" name="idref" id="idref" class="form-control" value="<?=$idref;?>">
                            <div class="panel panel-border panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Prestador</h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="col-md-6">
                                        <li>
                                            <b>Razão Social:</b> <?=$retornoEmpresa->razao_social?>
                                        </li>
                                        <li>
                                            <b>Endereço:</b> <?=$retornoEmpresa->logradouro.", ".$retornoEmpresa->numero." | Bairro: ".$retornoEmpresa->bairro." | CEP: ".$retornoEmpresa->cep?>
                                        </li>
                                        <li>
                                            <b>Email:</b> <?=$retornoEmpresa->email?>
                                        </li>
                                    </ul>
                                    <ul class="col-md-3">
                                        <li>
                                            <b>CNPJ:</b> <?=$retornoEmpresa->cnpj?>
                                        </li>
                                        <li>
                                            <b>Município:</b> <?=$retornoEmpresa->municipio?>
                                        </li>
                                        <li>
                                            <b>Telefone:</b> <?=$retornoEmpresa->telefone?>
                                        </li>
                                    </ul>
                                    <ul class="col-md-3">
                                        <li>
                                            <b>Inscrição Municipal:</b> <?=$retornoEmpresa->inscricao_municipal?>
                                        </li>
                                        <li>
                                            <b>UF:</b> <?=$retornoEmpresa->uf?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel panel-border panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Tomador</h3>
                                    <input type="hidden" name="nomeconsumidor" id="nomeconsumidor" value="<?=$nomeconsumidor;?>">
                                </div>
                                <div class="panel-body">
                                    <ul class="col-md-6">
                                        <li>
                                            <b>Nome / Razão Social:</b> <?=$nomeconsumidor; //$retornoCliente->Nome_Consumidor 
                                            ?>
                                        </li>
                                        <li>
                                            <b>Endereço:</b> <?=$retornoCliente->Nome_Rua.", ".$retornoCliente->Num_Rua." | Bairro: ".$retornoCliente->BAIRRO." | CEP: ".$retornoCliente->CEP?>
                                        </li>
                                    </ul>
                                    <ul class="col-md-3">
                                        <li>
                                            <b>CPF / CNPJ:</b> <?=$cpfcnpj;?>
                                        </li>
                                        <li>
                                            <b>Município:</b> <?=$retornoCliente->CIDADE?>
                                        </li>
                                    </ul>
                                    <ul class="col-md-3">
                                        <li>
                                            <b>Email: <?=$retornoCliente->EMail?></b>
                                        </li>
                                        <li>
                                            <b>UF:</b> <?=$retornoCliente->UF?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel panel-border panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Dados Complementares</h3>
                                </div>
                                <div class="panel-body">
                                
                                        <div class="form-group col-md-10">
                                        <?php $descricao = 'Nota fiscal referente a serviços prestados '; ?>
                                        
                                              <label for="item-servico">Referente a prestação de <?=count($retornoPedido) > 1 ? 'serviços' : 'serviço'?>: </label>
                                              <?php 
                                                foreach($retornoPedido as  $value){                                               
                                                    $descricao = $descricao . $value->DESCRICAO;
                                                   // $totalPedido = $totalPedido + $value->VALOR_TOTAL;
                                                } 
                                             
                                             ?><br>
                                              <textarea  name="servico"  rows="3" cols="100" style="width: 989px; height: 80px;"><?=$descricao;?></textarea>
                                            </div>
                                          
                                    
                                            <div class="col-md-10">
                                                <label for="item-servico">Item Serviço:</label>
                                                <select name="item-servico" id="item-servico" class="form-control" required>
                                                    <option value="">Selecione</option>
                                                    <?php foreach($servicos as $row): ?>
                                                    <option value="<?=$row->id?>" <?=$retornoEmpresa->item_lista_servico == $row->id ? 'selected' : ''?>><?=$row->descricao?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                      
                                   
                                </div>
                            </div>
                            <div class="panel panel-border panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Valores da Nota</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group col-md-4">
                                        <label for="nota-total">Valor Total:</label>
                                        <input type="number" name="nota-total" id="nota-total" class="form-control" value="<?=number_format($totalPedido, 2, '.', '')?>">
                                        <input type="hidden" name="acao-form" id="acao-form" value="1">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nota-deducoes">Valor Deduções:</label>
                                        <input type="number" name="nota-deducoes" id="nota-deducoes" class="form-control" value="0.00">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nota-base">Base de Calculo:</label>
                                        <input type="number" name="nota-base" id="nota-base" class="form-control" value="<?=number_format($totalPedido, 2, '.', '')?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nota-aliquota">Aliquota:</label>
                                        <input type="number" name="nota-aliquota" id="nota-aliquota" class="form-control" value="<?=$retornoEmpresa->aliquota_nota?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nota-iss">Valor ISS:</label>
                                        <input type="number" name="nota-iss" id="nota-iss" class="form-control" value="<?=number_format((floatval($totalPedido) * floatval($retornoEmpresa->aliquota_nota / 100)), 2, '.', '')?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nota-abatimento-iptu">Crédito p/ abatimento IPTU:</label>
                                        <input type="number" name="nota-abatimento-iptu" id="nota-abatimento-iptu" class="form-control" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                   
                    <div class="modal-footer"  id="retnf">
                         <div  class="row" style="text-align: center;" >                           
                         </div>
                        <div class="row pull-right">
                            <button class="btn btn-success waves-effect waves-light" onclick="_emiteNota()"><span class="btn-label"><i class="fa fa-print"></i></span> Emitir NFSe</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="md-5x md-highlight-remove"></i>
                            <?=$xx;?>     -<h2>Nota sem consumidor!</h2>
                        </div>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        }

    } else {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Dados não encontrados.</h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}