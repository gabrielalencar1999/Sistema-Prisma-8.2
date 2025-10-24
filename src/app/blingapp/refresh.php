<?php
$basic  = $client_id.':'.$client_secret;

$dados['grant_type']    = 'refresh_token';
$dados['refresh_token'] = $refresh_token;

$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($dados),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic '.base64_encode($basic)
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);
   // echo 'refresh:<br>';
  //  var_dump($response);
curl_close($curl);
if($resultado->refresh_token <> ''){
    date_default_timezone_set('America/Sao_Paulo');
    $dia       = date('d');
    $mes       = date('m');
    $ano       = date('Y');
    $hora = date("H:i:s");

    $datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
    /*
    $query = "UPDATE token SET
            refresh_token   = '".$resultado->refresh_token."',
            access_token    = '".$resultado->access_token."'
    ";
    mysqli_query($conexao, $query);
    */
    $query = "UPDATE token SET
    datahora = '$datahora',
    refresh_token   = '".$resultado->refresh_token."',
    access_token    = '".$resultado->access_token."' ";
   
    mysqli_query($conexao, $query);
}
?>