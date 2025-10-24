<?php
// As credenciais do cliente
$client_id = 'b9853dca759bbf19c1d291ba41085c377eb6db99';//'SEU_CLIENT_ID';
$client_id = '777819a1e55bfa40e6aef988198903d99eb4ccbc';
$client_secret = '367dd73553d2a026db617b7fcb54e0a77856bf77c9565c1e82ce606db75a';//'SEU_CLIENT_SECRET';

// Parâmetros a serem enviados no corpo da requisição
$grant_type = 'authorization_code';
$code = '306bfc7b6383a56d3747e75346b16c9ef7fb4749';

// URL do token endpoint
$url = 'https://www.bling.com.br/Api/v3/oauth/token?';

// Gerar o header de autenticação Basic
$auth_header = base64_encode("$client_id:$client_secret");


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.bling.com.br/Api/v3/oauth/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=authorization_code&code='.$code .'',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Basic '.$auth_header.''   
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
?>