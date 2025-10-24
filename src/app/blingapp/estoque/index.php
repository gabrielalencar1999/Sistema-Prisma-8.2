<?php
// Define o cabeçalho para permitir requisições de qualquer origem (CORS)
header('Access-Control-Allow-Origin: *');
// Define o cabeçalho para permitir os métodos POST
header('Access-Control-Allow-Methods: POST');
// Define o cabeçalho para permitir conteúdo JSON
header('Content-Type: application/json; charset=UTF-8');

// Verifica se o método de requisição é POST
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lê os dados da entrada de fluxo padrão (php://input) 
    $jsonData = file_get_contents('php://input');

    $file = 'geral.txt';
    $handle = fopen($file, 'a');
    fwrite($handle, "\n" . $jsonData . "\n");
    // Fecha o arquivo
    fclose($handle);

    // Decodifica os dados JSON em um array associativo
    $data = json_decode($jsonData, true);

    // Verifica se a decodificação foi bem-sucedida
    if ($data !== null) {
        // Converte o array associativo de volta para uma string JSON formatada
        $jsonFormattedData = json_encode($data, JSON_PRETTY_PRINT);

        // Define o caminho do arquivo onde os dados serão gravados
        $file = 'geral.txt';

        // Abre o arquivo para escrita, criando-o se não existir
        $handle = fopen($file, 'a');
        
        // Verifica se o arquivo foi aberto com sucesso
        if ($handle) {
            // Adiciona uma nova linha no arquivo antes de gravar os novos dados
            fwrite($handle, "\n" . $jsonFormattedData . "\n");

            // Fecha o arquivo
            fclose($handle);

            // Envia uma resposta de sucesso
           // echo json_encode(['status' => 'success', 'message' => 'Dados gravados com sucesso!']);
        } else {
            // Envia uma resposta de erro caso o arquivo não possa ser aberto
           // echo json_encode(['status' => 'error', 'message' => 'Não foi possível abrir o arquivo para escrita.']);
        }
    } else {
        // Envia uma resposta de erro caso a decodificação do JSON falhe
      //  echo json_encode(['status' => 'error', 'message' => 'Dados JSON inválidos.']);
    }
//} else {
    // Envia uma resposta de erro caso o método de requisição não seja POST
  //  echo
   json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
//}


/*
// JSON string
$json_data = '{"retorno":{"pedidos":[{"pedido":{"desconto":"0,00","observacoes":"","observacaointerna":"","data":"2024-06-19","numero":"5","numeroOrdemCompra":"","vendedor":"","valorfrete":"0.00","outrasdespesas":"0.00","totalprodutos":"59.96","totalvenda":"59.96","situacao":"Atendido","dataSaida":"2024-06-19","cliente":{"id":"16803018546","nome":"Robson Sales","cnpj":"","ie":"","indIEDest":"9","rg":"","endereco":"Rua Francisco Derosso","numero":"3451","complemento":"Sob. 6","cidade":"Curitiba","bairro":"xaxim","cep":"81830190","uf":"PR","email":"robsonlopessales@gmail.com","celular":"","fone":""},"transporte":{"tipo_frete":"R","qtde_volumes":"0","peso_bruto":"0.000"},"itens":[{"item":{"codigo":"10","descricao":"PRODUTO TESTE","quantidade":"2.0000","valorunidade":"29.9800000000","precocusto":null,"descontoItem":"0.00","un":"UN","pesoBruto":"0.00000","largura":"0","altura":"0","profundidade":"0","descricaoDetalhada":"","unidadeMedida":"cm","gtin":""}}],"parcelas":[{"parcela":{"idLancamento":"20541125985","valor":"59.96","dataVencimento":"2024-06-20 00:00:00","obs":"","destino":"3","forma_pagamento":{"id":"5846334","descricao":"Dinheiro","codigoFiscal":"1"}}}]}}]}}';

// Decodificando o JSON para um array PHP
$data = json_decode($json_data, true);

// Acessando a parte "itens"
$itens = $data['retorno']['pedidos'][0]['pedido']['itens'];

// Percorrendo todos os itens com foreach
foreach ($itens as $item) {
  $_codigofabricante =  $item['item']['codigo'];
  $_qtde =  $item['item']['quantidade'];
  
}
  */
