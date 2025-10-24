<?php
// Define o cabeçalho para permitir requisições de qualquer origem (CORS)
header('Access-Control-Allow-Origin: *');
// Define o cabeçalho para permitir os métodos POST
header('Access-Control-Allow-Methods: POST');
// Define o cabeçalho para permitir conteúdo JSON
header('Content-Type: application/json; charset=UTF-8');

$base = '9029_maqservice';
$origem = 3;

require_once('../../../../api/config/config.inc.php');
require '../../../../api/vendor/autoload.php';

use Database\MySQL;

$pdo = MySQL::acessabd();	

date_default_timezone_set('America/Sao_Paulo');

	$dia       = date('d');
	$mes       = date('m');
	$ano       = date('Y');
	$hora = date("H:i:s");
	$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

        // Lê os dados da entrada de fluxo padrão (php://input) 
    $jsonData = file_get_contents('php://input');
  //  $jsonData = 'data={"retorno":{"pedidos":[{"pedido":{"desconto":"0,00","observacoes":"tste","observacaointerna":"","data":"2024-10-23","numero":"15","numeroOrdemCompra":"","vendedor":"","valorfrete":"0.00","outrasdespesas":"0.00","totalprodutos":"26.98","totalvenda":"26.98","situacao":"Atendido","dataSaida":"2024-10-23","cliente":{"id":"17028897596","nome":"Consumidor Final","cnpj":null,"ie":null,"indIEDest":"9","rg":"","endereco":null,"numero":"","complemento":"","cidade":null,"bairro":null,"cep":null,"uf":null,"email":null,"celular":null,"fone":null},"transporte":{"tipo_frete":"R","qtde_volumes":"0","peso_bruto":"0.000"},"itens":[{"item":{"codigo":"10","descricao":"PRODUTO TESTE","quantidade":"0.9000","valorunidade":"29.9800000000","precocusto":null,"descontoItem":"0.00","un":"UN","pesoBruto":"0.00000","largura":"0","altura":"0","profundidade":"0","descricaoDetalhada":"","unidadeMedida":"cm","gtin":""}}],"parcelas":[{"parcela":{"idLancamento":"21403382959","valor":"26.98","dataVencimento":"2024-10-24","obs":"","destino":"3","forma_pagamento":{"id":"5846334","descricao":"Dinheiro","codigoFiscal":"1"}}}]}}]}}';

  // Remover o prefixo 'data=' para obter um JSON válido
    $jsonData = str_replace('data=', '', $jsonData);
    // Decodifica os dados JSON em um array associativo
    $data = json_decode($jsonData, true);

    // Verifica se a decodificação foi bem-sucedida
    if ($data !== null) {
        // Converte o array associativo de volta para uma string JSON formatada
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    }
// Exemplo de JSON recebido
//$jsonData = '{"retorno":{"pedidos":[{"pedido":{"desconto":"0,00","observacoes":"","observacaointerna":"","data":"2024-06-19","numero":"5","numeroOrdemCompra":"","vendedor":"","valorfrete":"0.00","outrasdespesas":"0.00","totalprodutos":"59.96","totalvenda":"59.96","situacao":"Atendido","dataSaida":"2024-06-19","cliente":{"id":"16803018546","nome":"Robson Sales","cnpj":"","ie":"","indIEDest":"9","rg":"","endereco":"Rua Francisco Derosso","numero":"3451","complemento":"Sob. 6","cidade":"Curitiba","bairro":"xaxim","cep":"81830190","uf":"PR","email":"robsonlopessales@gmail.com","celular":"","fone":""},"transporte":{"tipo_frete":"R","qtde_volumes":"0","peso_bruto":"0.000"},"itens":[{"item":{"codigo":"10","descricao":"PRODUTO TESTE","quantidade":"2.0000","valorunidade":"29.9800000000","precocusto":null,"descontoItem":"0.00","un":"UN","pesoBruto":"0.00000","largura":"0","altura":"0","profundidade":"0","descricaoDetalhada":"","unidadeMedida":"cm","gtin":""}}],"parcelas":[{"parcela":{"idLancamento":"20541125985","valor":"59.96","dataVencimento":"2024-06-20 00:00:00","obs":"","destino":"3","forma_pagamento":{"id":"5846334","descricao":"Dinheiro","codigoFiscal":"1"}}}]}}]}}';

$sql = "INSERT into $base.logintegracao (logI_identidade,logI_datahora,logI_texto) VALUES('1','$datahora',?)";
$stm = $pdo->prepare($sql);
$stm->bindParam(1, $jsonData);
$stm->execute();	
// Decodifica o JSON em um array associativo
$data = json_decode($jsonData, true);

// Acessa as variáveis específicas
$desconto = $data['retorno']['pedidos'][0]['pedido']['desconto'];
$observacoes = $data['retorno']['pedidos'][0]['pedido']['observacoes'];
$dataPedido = $data['retorno']['pedidos'][0]['pedido']['data'];
$numeroPedido = $data['retorno']['pedidos'][0]['pedido']['numero'];
$totalProdutos = $data['retorno']['pedidos'][0]['pedido']['totalprodutos'];
$totalVenda = $data['retorno']['pedidos'][0]['pedido']['totalvenda'];
$situacao = $data['retorno']['pedidos'][0]['pedido']['situacao'];
$valorfrete = $data['retorno']['pedidos'][0]['pedido']['valorfrete'];


// Informações do cliente
$clienteNome = $data['retorno']['pedidos'][0]['pedido']['cliente']['nome'];
$clienteEndereco = $data['retorno']['pedidos'][0]['pedido']['cliente']['endereco'];
$clienteCidade = $data['retorno']['pedidos'][0]['pedido']['cliente']['cidade'];

//gerar pedido venda
                    //busca novo numero do pedido
                    $sql = "Select parametro_CODIGO_LOGIN, Num_Pedido_Venda, livro_padrao from $base.parametro ";
                    $stm = $pdo->prepare($sql);
                    $stm->execute();
                    foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){
                        $idPedido = $value['Num_Pedido_Venda'];
                        $idPedidoX = $idPedido + 1;
                    }	
                            

                    //Atualiza numero pedido
                    $sql = "Update $base.parametro set Num_Pedido_Venda = '$idPedidoX'";
                    $stm = $pdo->prepare($sql);
                    $stm->execute();		

                    //nova venda iniciada
                    //	$_SESSION['numberPedido'] = $idPedido;		
                    $_numeropedido = $idPedido;	
                    //cria pedido
                    $sql = "Insert into  $base.saidaestoque (
                        NUMERO,
                        CODIGO_CLIENTE,			
                        COD_TIPO_SAIDA,
                        DATA_CADASTRO,
                        DATA_HORA,
                        Cod_Situacao,
                        COD_Vendedor,
                        CLIENTE,
                        num_livro,
                        origem,
                        VL_Pedido
                    )values (
                        '$idPedido',
                        '1',
                        '1',			
                        '$dataPedido',
                        '$datahora',
                        '3',
                        '999',
                        '$clienteNome',
                        '1',
                        '$origem',
                        '$totalVenda'
                    )";	
                 
                    $stm = $pdo->prepare($sql);
                    $stm->execute();

// Informações do item
// Acessa o array de itens
$itens = $data['retorno']['pedidos'][0]['pedido']['itens'];
$ITEM = 0;
$_almox = 1;
// Faz o loop pelos itens
foreach ($itens as $item) {
    $codigo = $item['item']['codigo'];
    $descricaoProduto = $item['item']['descricao'];
    $qtde = $item['item']['quantidade'];
    $valorProduto = $item['item']['valorunidade'];
    $unidadeMedida = $item['item']['unidadeMedida'];
    $totalProduto = $valorProduto  * $qtde;
    $precocusto =  $item['item']['precocusto'];
    
    // Exemplo de exibição das informações
    /*
    echo "Código: $codigo\n";
    echo "Descrição: $descricao\n";
    echo "Quantidade: $quantidade\n";
    echo "Valor Unidade: $valorUnidade\n";
    echo "Unidade de Medida: $unidadeMedida\n";
    echo "--------------------------\n";
    */
    $ITEM = $ITEM + 1;

    
    $consultaLinha = $pdo->query("Select Codigo_Item,codref_fabricante,Qtde_Disponivel    from  $base.itemestoquealmox  where  codref_fabricante = '".$codigo ."' AND Codigo_Almox = '1' LIMIT 1 ");                   
    $retornoLinha = $consultaLinha->fetchAll();
    foreach ($retornoLinha as $row_a) {   
        $cod = $row_a['Codigo_Item'];
        $iditem  = $cod;
        $qtde_atual = $row_a["Qtde_Disponivel"] - $qtde ;	
        $codigoFornecedor = $row_a["codref_fabricante"] ;
        $valorCusto =$precocusto;
    }

   
	//insere produto no pedido
	$sql2="insert into $base.saidaestoqueitem (NUMERO, ITEM, CODIGO_ITEM, QUANTIDADE, Valor_unitario_desc, VALOR_UNITARIO, VALOR_TOTAL, VALOR_UNIT_DESC, QUANTIDADE_ATEND, SALDO_ATEND,DATA_COMPRA, QTDE_BAIXA, DESCRICAO_ITEM, Ind_Aut, Ind_Estok , Valor_Custo , 	num_livro, HORA_COMPRA , Cod_Atendente, tabela_preco,Cod_Almox,vlr_vendaorigem) 
    values('".$_numeropedido."','".$ITEM."','$cod','$qtde','$valorProduto','$valorProduto','$totalProduto','$valorProduto','$qtde','$qtde',CURRENT_DATE,'$qtde' , '$descricaoProduto','1', '-1' , '$valorCusto','1','$datahora', '$base','Tab_Preco_5','1','$valorProduto')";
	$stm2 = $pdo->prepare($sql2);
	$stm2->execute();	

   
        $_SQL = "Update $base.itemestoquealmox  set Qtde_Disponivel = '$qtde_atual' 
        where Codigo_Item  = '$iditem' and Codigo_Almox = '$_almox' ";
        $stm = $pdo->prepare($_SQL);	
        $stm->execute();	

        $_SQL = " INSERT INTO $base.itemestoquemovto
        (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento,
        Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,
        Motivo,Saldo_Atual,Data_Movimento ) 
        values 
        ( '$iditem',
        '$qtde',
        '$_almox',
        's',
        'w',
        '$_idpedido','$valorProduto','0','$total','999','Saida por Venda','$qtde_atual','$datahora') ";
        $stm = $pdo->prepare($_SQL);	
        $stm->execute();
    
}

// Informações da parcela
/*
$parcelaValor = $data['retorno']['pedidos'][0]['pedido']['parcelas'][0]['parcela']['valor'];
$dataVencimento = $data['retorno']['pedidos'][0]['pedido']['parcelas'][0]['parcela']['dataVencimento'];
$formaPagamentoDescricao = $data['retorno']['pedidos'][0]['pedido']['parcelas'][0]['parcela']['forma_pagamento']['descricao'];
*/
// Acessa o array de parcelas
/*
$parcelas = $data['retorno']['pedidos'][0]['pedido']['parcelas'];

// Faz o loop pelas parcelas
foreach ($parcelas as $parcela) {
    $idLancamento = $parcela['parcela']['idLancamento'];
    $valor = $parcela['parcela']['valor'];
    $dataVencimento = $parcela['parcela']['dataVencimento'];
    $formaPagamento = $parcela['parcela']['forma_pagamento']['descricao'];

    $sql="SELECT *,SUM(spgto_valorInfo) as total FROM $base.tiporecebimpgto";	
    $stm = $pdo->prepare($sql);
    $stm->execute();
    while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 
        $formaPagamento = $rst->Tipo_Pagamento_Entrada;
    }
            $Linha = 1;
                //cria condicao de pagamento
                $_sql = "INSERT INTO ".$_SESSION['BASE'].".saidaestoquepgto(
                    spgto_numpedido,
                    spgto_numlivro,
                    spgto_tipopgto,
                    spgto_data,
                    spgto_venc,
                    spgto_valor,
                    spgto_parcela,
                    spgto_valorInfo,							
                    spgto_total_parcela,
                    spgto_entrada
                ) VALUES(
                    ?,
                    ?,
                    ?,
                    CURRENT_DATE(),
                    CURRENT_DATE(),
                    ?,
                    ?,								
                    ?,
                    1,
                    '1'
                )";								
                $statement = $pdo->prepare($_sql); 					
                $statement->bindParam(1, $numero_pedido);
                $statement->bindParam(2, $livro);
                $statement->bindParam(3, $formaPagamento);							        
                $statement->bindParam(4, $_valorentrada);    
                $statement->bindParam(5, $Linha);
                $statement->bindParam(6, $_valorentrada);							  
                                            
                $statement->execute();
   
}

//busca total por tipo de pagamento
$sql="SELECT *,SUM(spgto_valorInfo) as total FROM ". $_SESSION['BASE'] .".saidaestoquepgto 
LEFT JOIN ".$_SESSION['BASE'] .".tiporecebimpgto  on spgto_tipopgto = id 
where spgto_parcela > 0 and spgto_numpedido = '".$_idpedido."' and spgto_entrada <> '1'  GROUP BY spgto_tipopgto";
$stm = $pdo->prepare($sql);	

$stm->execute();
while($rst = $stm->fetch(PDO::FETCH_OBJ)){

    $total_pagto = $rst->total;
    $data_atual = "";

    //busca dados da condição de pagamento
    $sql="SELECT * FROM ". $_SESSION['BASE'] .".tiporecebimpgto where id = '$_TIPOPGTO'";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    while($rst = $stm->fetch(PDO::FETCH_OBJ)){
        $prazo_condPag = $rst->prz;
        $juros_condPag = $rst->tx_juro;
        $liquida_condPag = $rst->Ind_liquida;
        $recebeIntegral_condPag =0;
        $descricao_condPag = $rst->nome;
        $contabiliza_caixa = $rst->ind_troca;
    }
    $contabiliza_caixa = 1;
    //calcula novo vencimento
    if($data_atual == ""){
        $data_atual = date('d/m/Y');
    }							
    $data12 = SomarData($data_atual, $prazo_condPag, 0, 0); 
    $dia = substr("$data12",0,2); 
    $mes = substr("$data12",3,2); 
    $ano = substr("$data12",6,4); 
    $data_atual = "$dia/$mes/$ano";

    $vencimento = "$ano-$mes-$dia";

    //verifica se liquida venda
    if($liquida_condPag == 'S'){
        $valor_pagoF = $valor_parcela;
        $data_pagoF = date('Y-m-d');
    }else{
        $valor_pagoF = "";
        $data_pagoF  = "";
    }
    
    $obs = $obs."parcelado";
    
    //insere valor no financeiro
    $_SQL = "INSERT INTO ". $_SESSION['BASE'] .".financeiro (
        financeiro_parcela,
        financeiro_totalParcela,
        financeiro_codigoCliente,
        financeiro_nome,
        financeiro_documento,
        financeiro_historico,
        financeiro_emissao,
        financeiro_vencimento,
        financeiro_vencimentoOriginal,
        financeiro_valor,
        financeiro_situacaoID,
        INDENTIFICADOR,
        financeiro_tipo,
        financeiro_grupo,
        financeiro_subgrupo,
        financeiro_caixa,
        financeiro_tipoPagamento,
        financeiro_hora,
        financeiro_nsu,
        financeiro_tipoQuem,
        financeiro_valorFim,
        financeiro_dataFim,
        financeiro_obs,
        financeiro_valorDesconto,
        financeiro_totalduplicata,
        Documento
    ) VALUES (
        '1',
        '1',
        '$_idcliente',
        '".$_SESSION['NOME']."',
        '$_idpedido',
        'REF $_idpedido ',
        CURRENT_DATE(),
        '$vencimento',
        '$vencimento',
        '$valor_pagoF',
        '0',
        '1',
        '0',
        '1',
        '2',
        '$_idcaixa',
        '$_TIPOPGTO',
        '$datahora',
        '$NSU',
        '1',
        '$valor_pagoF',
        '$data_pagoF',
        '$obs',
        '$desconto_total',
        '$total_pagto',
        '$_idpedido'
    )";          
    $stm = $pdo->prepare($_SQL);	
    $stm->execute();

} //busca total por tipo de pagamento
 */

?>
