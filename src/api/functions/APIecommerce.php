<?php
namespace Functions;

use Database\MySQL;
use PDOStatement;


class APIecommerce
{
  
    public static function bling_saldoEstoque(String $codigoFornecedor,$vlrcusto,$vlrvenda,$qtde, $operacao,$obs) 
    {
		//bling  B = balanço E=entrada S=saida
      try {
            session_start();
			date_default_timezone_set('America/Sao_Paulo');
			$pdo = MySQL::acessabd();
			//verifica se existe integracao
			$sql = "SELECT access_token FROM bd_prisma.token WHERE idlogin	 = '".$_SESSION['CODIGOCLI']."'";			
            $stm = $pdo->query($sql);
            $response = $stm->fetch(\PDO::FETCH_OBJ);
	
			if(count($response) > 0) {
				//verificar autenticação
					$sql = "SELECT access_token,refresh_token,TIMESTAMPDIFF(HOUR, datahora, NOW()) AS qtde_horas FROM bd_prisma.token
					 WHERE idlogin	 = '".$_SESSION['CODIGOCLI']."'";
					$stm = $pdo->query($sql);
					$response = $stm->fetch(\PDO::FETCH_OBJ);
					if(($response->qtde_horas) <= 6) {
							$_access_token = $response->access_token;
						//	echo $_access_token;
					}else{
					
						//atualiza token
					//	$client_id      = 'b9853dca759bbf19c1d291ba41085c377eb6db99';
					//	$client_secret  = 'c931c104d8bfe576ffb61e91dc82828139e4097f3e2a83aa7724381704d8';
				
					$sql = "SELECT client_id,client_secret FROM info.consumidor where  CODIGO_CONSUMIDOR = '".$_SESSION['CODIGOCLI']."'";
					$stmapp = $pdo->query($sql);
					$responseapp = $stmapp->fetch(\PDO::FETCH_OBJ);				
					$client_id      = $responseapp->client_id;;// 'b9853dca759bbf19c1d291ba41085c377eb6db99';
					$client_secret  = $responseapp->client_secret;//'c931c104d8bfe576ffb61e91dc82828139e4097f3e2a83aa7724381704d8';
					$basic  = $client_id.':'.$client_secret;

						$dados['grant_type']    = 'refresh_token';
						$dados['refresh_token'] = $response->refresh_token;
						$refresh_token =  $response->refresh_token;

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
							if($resultado->refresh_token <> ''){

								$_access_token = $resultado->access_token;

								$stm = $pdo->prepare("
											UPDATE token SET
											datahora = now(),
											refresh_token   = '".$resultado->refresh_token."',
											access_token    = '".$resultado->access_token."'
											WHERE refresh_token = '".$refresh_token."' 
											AND idlogin = '".$_SESSION['CODIGOCLI']."'");
								$stm->execute();	

							}
					}

					

					if($_access_token != "") {
						//buscar dados do produto
						$sql = "SELECT codigo_item,Codigo_fornecedor,iddeposito 
						FROM ". $_SESSION['BASE'] .".itemestoquefornecedor  AS I
						LEFT JOIN  ". $_SESSION['BASE'] .".fabricante  AS F ON F.codigo_fabricante = I.codigo_fabricante 
						WHERE codigo_item = '".$codigoFornecedor."' and Fabricante_CODIGO_LOGIN = '10' ";					
						$stmP = $pdo->query($sql);					
						$responseP = $stmP->fetch(\PDO::FETCH_OBJ);
						if( $stmP->rowcount()> 0) {
							$deposito= $responseP->iddeposito;
							$idcodreferencia= $responseP->Codigo_fornecedor;
						
											
					$tokenwats = 'Authorization: Bearer '.$_access_token;
									
						$_fields = '{
							"deposito": {
								"id": "'.$deposito .'"
							},
							"operacao":  "'.$operacao.'",
							"produto": {
								"id": "'.$idcodreferencia .'"
							},
							"quantidade": "'.$qtde.'",
							"preco": "'.$vlrvenda.'",
							"custo": "'.$vlrcusto.'",
							"observacoes": "'.$obs.'"
							}';
							
					

						$curl2 = curl_init();

						curl_setopt_array($curl2, array(
						CURLOPT_URL => 'https://bling.com.br/Api/v3/estoques',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS =>''.$_fields.'',
						CURLOPT_HTTPHEADER => array(
							'Content-Type: application/json',
							''.$tokenwats.''
						   ),
						));

						$response = curl_exec($curl2);

						curl_close($curl2);
						//echo $response;
						 // Remover o prefixo 'data=' para obter um JSON válido
   						 $jsonData = str_replace('data=', '', $response);
							$data = json_decode($jsonData, true);

							// Acessa as variáveis específicas
							if($data['error']['message'] != "") {		
								if($data['error']['type'] == 'invalid_token') {
									
									//NOVA TENTATIVA RENOVAR TOKEN
													
										//atualiza token
									//	$client_id      = 'b9853dca759bbf19c1d291ba41085c377eb6db99';
									//	$client_secret  = 'c931c104d8bfe576ffb61e91dc82828139e4097f3e2a83aa7724381704d8';
									$sql = "SELECT access_token,refresh_token FROM bd_prisma.token WHERE idlogin	 = '".$_SESSION['CODIGOCLI']."'";			
									$stm = $pdo->query($sql);
									$response = $stm->fetch(\PDO::FETCH_OBJ);
								
									$sql = "SELECT client_id,client_secret FROM info.consumidor where  CODIGO_CONSUMIDOR = '".$_SESSION['CODIGOCLI']."'";
									$stmapp = $pdo->query($sql);
									$responseapp = $stmapp->fetch(\PDO::FETCH_OBJ);				
									$client_id      = $responseapp->client_id;;// 'b9853dca759bbf19c1d291ba41085c377eb6db99';
									$client_secret  = $responseapp->client_secret;//'c931c104d8bfe576ffb61e91dc82828139e4097f3e2a83aa7724381704d8';
									$basic  = $client_id.':'.$client_secret;

										$dados['grant_type']    = 'refresh_token';
										$dados['refresh_token'] = $response->refresh_token;
										$refresh_token =  $response->refresh_token;

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
										
											if($resultado->refresh_token <> ''){

												$_access_token = $resultado->access_token;

												$stm = $pdo->prepare("
															UPDATE token SET
															datahora = now(),
															refresh_token   = '".$resultado->refresh_token."',
															access_token    = '".$resultado->access_token."'
															WHERE refresh_token = '".$refresh_token."' 
															AND idlogin = '".$_SESSION['CODIGOCLI']."'");
												$stm->execute();	

											}
													
									if($_access_token != "") {
										//buscar dados do produto
										$sql = "SELECT codigo_item,Codigo_fornecedor,iddeposito 
										FROM ". $_SESSION['BASE'] .".itemestoquefornecedor  AS I
										LEFT JOIN  ". $_SESSION['BASE'] .".fabricante  AS F ON F.codigo_fabricante = I.codigo_fabricante 
										WHERE codigo_item = '".$codigoFornecedor."' and Fabricante_CODIGO_LOGIN = '10' ";					
										$stmP = $pdo->query($sql);					
										$responseP = $stmP->fetch(\PDO::FETCH_OBJ);
										if( $stmP->rowcount()> 0) {
											$deposito= $responseP->iddeposito;
											$idcodreferencia= $responseP->Codigo_fornecedor;
										
															
									$tokenwats = 'Authorization: Bearer '.$_access_token;
													
										$_fields = '{
											"deposito": {
												"id": "'.$deposito .'"
											},
											"operacao":  "'.$operacao.'",
											"produto": {
												"id": "'.$idcodreferencia .'"
											},
											"quantidade": "'.$qtde.'",
											"preco": "'.$vlrvenda.'",
											"custo": "'.$vlrcusto.'",
											"observacoes": "'.$obs.'"
											}';
											
									
				
										$curl2 = curl_init();
				
										curl_setopt_array($curl2, array(
										CURLOPT_URL => 'https://bling.com.br/Api/v3/estoques',
										CURLOPT_RETURNTRANSFER => true,
										CURLOPT_ENCODING => '',
										CURLOPT_MAXREDIRS => 10,
										CURLOPT_TIMEOUT => 0,
										CURLOPT_FOLLOWLOCATION => true,
										CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
										CURLOPT_CUSTOMREQUEST => 'POST',
										CURLOPT_POSTFIELDS =>''.$_fields.'',
										CURLOPT_HTTPHEADER => array(
											'Content-Type: application/json',
											''.$tokenwats.''
										   ),
										));
				
										$response = curl_exec($curl2);
				
										curl_close($curl2);
										//echo $response;
										 // Remover o prefixo 'data=' para obter um JSON válido
											$jsonData = str_replace('data=', '', $response);
											$data = json_decode($jsonData, true);
				
											// Acessa as variáveis específicas
											if($data['error']['message'] != "") {		
												
												
												$erro = "<strong>BLING :</strong>".$data['error']['message'];
												$erro =  $erro."<br>".$data['error']['description'];
											
											?>
											<div class="alert alert-danger text-center"><?=$erro;?>
												</div>
											<?php 
										}
				
									}
				
				
							}


								}else{

								
								$erro = "<strong>BLING :</strong>".$data['error']['message'];
								$erro =  $erro."<br>".$data['error']['description'];
							}
							?>
							<div class="alert alert-danger text-center"><?=$erro;?>
								</div>
							<?php 
						}

					}


			}
		}
		return $response;
			
	} catch (\PDOException $e) {
		$response = array(
			'type' => 'error',
			'message' => 'Erro ao consultar informações Bling!'.$e
		);
	}

    }

	public function bling_baixaEstoque(String $dataInicio, String $dataFim): array
    {
		$pdo = MySQL::acessabd();
        try {
            session_start();
            $sql = "SELECT *,date_format(pg_dtvencimento,'%d/%m/%Y') as dtvenc, date_format(pg_dtpgto,'%d/%m/%Y') as dtpgto FROM info.pagamento WHERE pg_idcliente = '".$_SESSION['CODIGOCLI']."' AND pg_dtvencimento BETWEEN '$dataInicio' AND '$dataFim'  ORDER BY pg_dtvencimento DESC";
           
            $stm = $pdo->query(" $sql");
            $response = $stm->fetchAll(\PDO::FETCH_OBJ);
            return $response;
        } catch (\PDOException $th) {
            throw "Error: ".$th->getMessage();
        }
    }
	

   
}

