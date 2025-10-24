<?php
include('../conecta.php');
$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/pedidos/vendas?pagina=1&limite=100&dataInicial=2024-01-01&dataFinal=2024-03-29',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => '',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Bearer '.$access_token
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);
    var_dump($response);
curl_close($curl);

foreach($resultado->data as $linhas){
    echo $linhas->id;
}
?>