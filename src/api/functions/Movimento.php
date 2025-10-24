<?php

namespace Functions;

use Database\MySQL;

class Movimento
{
    private $pdo;
    private $id;
    private $usuario;

    public function __construct()
    {
        $this->pdo = MySQL::acessabd();
        $this->id = $_SESSION['BASE_ID'];
        $this->usuario = $_SESSION['IDUSER'];
    }

 
    public function consultaExtrato(String $dataInicio, String $dataFim): array
    {
        try {
            session_start();
            $sql = "SELECT *,date_format(pg_dtvencimento,'%d/%m/%Y') as dtvenc, date_format(pg_dtpgto,'%d/%m/%Y') as dtpgto FROM info.pagamento WHERE pg_idcliente = '".$_SESSION['CODIGOCLI']."' AND pg_dtvencimento BETWEEN '$dataInicio' AND '$dataFim'  ORDER BY pg_dtvencimento DESC";
           
            $stm = $this->pdo->query(" $sql");
            $response = $stm->fetchAll(\PDO::FETCH_OBJ);
            return $response;
        } catch (\PDOException $th) {
            throw "Error: ".$th->getMessage();
        }
    }

    public function consultaAberto(): array
    {
        try {
            session_start();
            $sql = "SELECT date_format(pg_dtvencimento,'%d/%m/%Y') as vencimento,pg_valor FROM info.pagamento WHERE pg_idcliente = '".$_SESSION['CODIGOCLI']."' AND  pg_valorpago = 0 Limit 1";           
            $stm = $this->pdo->query(" $sql");
            $response = $stm->fetchAll(\PDO::FETCH_OBJ);
            return $response;
        } catch (\PDOException $th) {
            throw "Error: ".$th->getMessage();
        }
    }

    public static function whats_enviopadraodigisac($id_msg ,$id_cliente,$fone_envio, $_textoenvio , $refdoc , $base, $empresa){
	
        try{			
			$pdo = MySQL::acessabd();			
			date_default_timezone_set('America/Sao_Paulo');   

			$dontOpenTicket = "false";
			$empresa_userenvio = "";
			$departmentId = "";
			$serviceId = "0";
			$urlwats = "";

			$sql = "Select whats_ativo from ".$_SESSION['BASE'].".msg_whats where  whats_id = '$id_msg' limit 1  "; 
			$stm = $pdo->prepare("$sql");            
			$stm->execute();	

			$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				foreach($response as $row){	                        
					$id_ATIVO = $row->whats_ativo; // 1 ATIVO									   
				}
			
			if($id_ATIVO == 1) { 			


					$stm = $pdo->prepare("SELECT empresa_urlwats,empresa_departmentId,empresa_serviceId,empresa_tokenwats,empresa_userenvio FROM $base.empresa WHERE empresa_id = ?");
					$stm->bindParam(1, $empresa, \PDO::PARAM_STR);
					$stm->execute();           
				
					if ( $stm->rowCount() > 0 ){
						$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
						foreach($response as $row){					
							$departmentId =  $row->empresa_departmentId;
							$serviceId =   $row->empresa_serviceId; 
							$urlwats =  trim($row->empresa_urlwats); //endpoint 
							$tokenwats = trim($row->empresa_tokenwats); //bear   
							$empresa_userenvio =    $row->empresa_userenvio; //campo para trocar nome bot ennvio mensagem    
							if($empresa_userenvio != "")  {
									$newparametro = '&origin='.$empresa_userenvio;
							}
						}
					}

					if( $urlwats == ""){
						$_retmensagem = '<b style="color:#f05050;">Mensagem WhatsApp não configurado!</b> ';
						return $_retmensagem;	
						exit();
					}

					if($departmentId != "") {
						$_fields = "number=55$fone_envio&text=".rawurlencode($_textoenvio)."&serviceId=".$serviceId."&dontOpenTicket=".$dontOpenTicket."&departmentId=".$departmentId.$newparametro;
					}else{
						$_fields = "number=55$fone_envio&text=".rawurlencode($_textoenvio)."&serviceId=".$serviceId."&dontOpenTicket=".$dontOpenTicket.$newparametro;
					}
						
				

					if($urlwats != "" and $tokenwats != "") {
						$tokenwats = 'Authorization: Bearer '.$tokenwats;
			
					$curl = curl_init();
			
					curl_setopt_array($curl, array(
					CURLOPT_URL => $urlwats,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 15,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => ''.$_fields.'',
					CURLOPT_HTTPHEADER => array(
						''.$tokenwats.'',
						'Content-Type: application/x-www-form-urlencoded'
					),
					));
				
					$response = curl_exec($curl);  
					
					curl_close($curl);		  
			
					$obj = json_decode($response);		
				
					if(  $obj->sent == false){
						$_retmensagem = '<b style="color:#f05050;">Falha envio</b> ';
					}else{
						$_retmensagem = '<b style="color:#00A8E6;">Enviado</b> ';
					
					}  
		/*
					$stm = $pdo->prepare("INSERT INTO ".$base.".logmensagem(
						log_data,
						log_datahora,
						log_documento,
						log_idcliente,
						log_texto,
						log_ret,
						log_send,
						log_sequencia
						) 
						VALUES (
							CURRENT_DATE(),
							NOW(), 
							?,
							?,
							?, 
							?,
							?,
							?
						); ");
						$stm->bindParam(1, $refdoc);			
						$stm->bindParam(2, $id_cliente);	
						$stm->bindParam(3, $_textoenvio);
						$stm->bindParam(4, $response);
						$stm->bindParam(5, $obj->sent);
					
						$stm->bindParam(6, $id_msg);		
						$stm->execute();	 
						*/               
						
					}	

				}
				

        }
        catch (\Exception $fault){
			$response = $fault;
        }
       return $_retmensagem;		
	 
	}

	
	
    public static function whats_oficialdigisac($id_msg ,$id_cliente,$fone_envio, $_textoenvio , $refdoc , $base, $empresa){
	
        try{			
			$pdo = MySQL::acessabd();			
			date_default_timezone_set('America/Sao_Paulo');   

			$dontOpenTicket = "false";

			$departmentId = "";
			$serviceId = "0";
			$urlwats = "";

			$sql = "Select whats_ativo from ".$_SESSION['BASE'].".msg_whats where  whats_id = '$id_msg' limit 1  "; 
			$stm = $pdo->prepare("$sql");            
			$stm->execute();	

			$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				foreach($response as $row){	                        
					$id_ATIVO = $row->whats_ativo; // 1 ATIVO									   
				}
			
			if($id_ATIVO == 1) { 			

			$stm = $pdo->prepare("SELECT empresa_urlwats,empresa_departmentId,empresa_serviceId,empresa_tokenwats FROM $base.empresa WHERE empresa_id = ?");
            $stm->bindParam(1, $empresa, \PDO::PARAM_STR);
            $stm->execute();           
		 
			if ( $stm->rowCount() > 0 ){
				$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
				foreach($response as $row){					
					$departmentId =  $row->empresa_departmentId;
					$serviceId =   $row->empresa_serviceId; 
					$urlwats =  trim($row->empresa_urlwats); //endpoint 
					$tokenwats = trim($row->empresa_tokenwats); //bear                  
				}
			}

			if( $urlwats == ""){
				$_retmensagem = '<b style="color:#f05050;">Mensagem WhatsApp não configurado!</b> ';
				return $_retmensagem;	
				exit();
			}

			if($departmentId != "") {
				$_fields = "number=55$fone_envio&text=".rawurlencode($_textoenvio)."&serviceId=".$serviceId."&dontOpenTicket=".$dontOpenTicket."&departmentId=".$departmentId;
			}else{
				$_fields = "number=55$fone_envio&text=".rawurlencode($_textoenvio)."&serviceId=".$serviceId."&dontOpenTicket=".$dontOpenTicket;
			}
				
			

			if($urlwats != "" and $tokenwats != "") {
				$tokenwats = 'Authorization: Bearer '.$tokenwats;
	 
			 $curl = curl_init();
	 
			 curl_setopt_array($curl, array(
			   CURLOPT_URL => $urlwats,
			   CURLOPT_RETURNTRANSFER => true,
			   CURLOPT_ENCODING => '',
			   CURLOPT_MAXREDIRS => 10,
			   CURLOPT_TIMEOUT => 15,
			   CURLOPT_FOLLOWLOCATION => true,
			   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			   CURLOPT_CUSTOMREQUEST => 'POST',
			   CURLOPT_POSTFIELDS => ''.$_fields.'',
			   CURLOPT_HTTPHEADER => array(
				''.$tokenwats.'',
				 'Content-Type: application/x-www-form-urlencoded'
			   ),
			 ));
		
			 $response = curl_exec($curl);  
			 curl_close($curl);		  
	   
			 $obj = json_decode($response);		
		   
			 if(  $obj->sent == false){
				 $_retmensagem = '<b style="color:#f05050;">Falha envio</b> ';
			 }else{
				$_retmensagem = '<b style="color:#00A8E6;">Enviado</b> ';
			
			 }  
/*
			$stm = $pdo->prepare("INSERT INTO ".$base.".logmensagem(
				log_data,
                log_datahora,
                log_documento,
                log_idcliente,
                log_texto,
                log_ret,
                log_send,
                log_sequencia
				) 
				VALUES (
					CURRENT_DATE(),
					NOW(), 
					?,
					?,
					?, 
					?,
					?,
					?
				); ");
				$stm->bindParam(1, $refdoc);			
				$stm->bindParam(2, $id_cliente);	
				$stm->bindParam(3, $_textoenvio);
				$stm->bindParam(4, $response);
				$stm->bindParam(5, $obj->sent);			
				$stm->bindParam(6, $id_msg);		
				$stm->execute();	                
                */
			}	
		}	

        }
        catch (\Exception $fault){
			$response = $fault;
        }
       return $_retmensagem;		
	 
	}

	public static function whats_oficialomni($id_msg ,$id_cliente,$fone_envio, $refdoc , $base, $empresa, $parameters){
		
        try{			
			$pdo = MySQL::acessabd();			
			date_default_timezone_set('America/Sao_Paulo');   

			$dontOpenTicket = "false";

			$departmentId = "";
			$serviceId = "0";
			$urlwats = "";

			//TEMPLATE
			 $stm = $pdo->prepare("SELECT msg_template,whats_mensagem,whats_ativo FROM ".$_SESSION['BASE'].".msg_whats WHERE  whats_id = '".$id_msg."' limit 1 ");         	
        	 $stm->execute(); 
             $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
             $idTemplate =  $response->msg_template;  
			 $id_ATIVO =  $response->whats_ativo; 
			 $_textoenvio =  rawurlencode($response->whats_mensagem);   

			if($id_ATIVO == 1) { 
						$stm = $pdo->prepare("SELECT empresa_urlwats,empresa_departmentId,empresa_serviceId,empresa_tokenwats,empresa_fromtelefone FROM $base.empresa WHERE empresa_id = ?");
						$stm->bindParam(1, $empresa, \PDO::PARAM_STR);
						$stm->execute();           
					
						if ( $stm->rowCount() > 0 ){
							$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
							foreach($response as $row){					
								//$departmentId =  $row->empresa_departmentId;
							//	$serviceId =   $row->empresa_serviceId; 
								$urlwats =  trim($row->empresa_urlwats); //endpoint 
								$tokenwats = trim($row->empresa_tokenwats); //bear]
								$telefoneFom = trim($row->empresa_fromtelefone);                    
							}
						}

						if( $urlwats == ""){
							$_retmensagem = '<b style="color:#f05050;">Mensagem WhatsApp não configurado!</b> ';
							return $_retmensagem;	
							exit();
						}

						// Defina os parâmetros desejados em um array associativo
						/**$parameters = array(
							"nm_tec" => "TECNICO TESTE",
							"outro_parametro" => "valor_outro_parametro",
							// Adicione quantos parâmetros desejar
						);
					
						*/

									
						$_fields = '{"body": {"parameters":'.$parameters.',"text": "'.$_textoenvio.'","templateId": "'.$idTemplate .'"},"to": "'.$fone_envio.'","from": "'.$telefoneFom.'"}';
							
							
									// Decodificar o JSON original em um array associativo
						$array_original = json_decode($_fields, true);

						// Reformatar o JSON com a opção JSON_PRETTY_PRINT
						$json_formatado = json_encode($array_original, JSON_PRETTY_PRINT);

						if($urlwats != "" and $tokenwats != "") {
							$tokenwats = 'Authorization: Bearer '.$tokenwats;
				
						$curl = curl_init();
				
						curl_setopt_array($curl, array(
						CURLOPT_URL => $urlwats,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 15,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_POSTFIELDS =>''.$json_formatado.'',
						CURLOPT_HTTPHEADER => array(
							'Content-Type: application/json',
							''.$tokenwats.''
						),
						));
					
						$response = curl_exec($curl);  
						// print_r( $response);
						curl_close($curl);		  
				
						$obj = json_decode($response);		
						
						
						if(  $obj->status != 'PROCESSING' ){
							$_retmensagem = '<b style="color:#f05050;">Falha envio</b> ';
						}else{
							$_retmensagem = '<b style="color:#00A8E6;">Enviado</b> ';
						
						}  

						if($id_cliente == "") { $id_cliente == "0";}
						/*
						$stm = $pdo->prepare("INSERT INTO ".$base.".logmensagem(
							log_data,
							log_datahora,
							log_documento,
							log_idcliente,
							log_texto,
							log_ret,
							log_send,
							log_sequencia
							) 
							VALUES (
								CURRENT_DATE(),
								NOW(), 
								?,
								?,
								?, 
								?,
								?,
								?
							); ");
							$stm->bindParam(1, $refdoc);			
							$stm->bindParam(2, $id_cliente);	
							$stm->bindParam(3, $_textoenvio);
							$stm->bindParam(4, $response);
							$stm->bindParam(5, $obj->sent);				
							$stm->bindParam(6, $id_msg);		
							$stm->execute();	
							*/                
							
						}	
					}

        }
        catch (\Exception $fault){
			$response = $fault;
			//print_r($response);
        }
       return $_retmensagem;		
	 
	}

	
	public static function whats_oficialSonax($id_msg ,$id_cliente,$fone_envio, $refdoc , $base, $empresa, $parameters){
		
        try{			
			$pdo = MySQL::acessabd();			
			date_default_timezone_set('America/Sao_Paulo');   

			$dontOpenTicket = "false";

			$departmentId = "";
			$serviceId = "0";
			$urlwats = "";

			$fone_envio = str_replace(" ", "", $fone_envio);

			//TEMPLATE
			 $stm = $pdo->prepare("SELECT msg_template,whats_mensagem,whats_ativo FROM ".$_SESSION['BASE'].".msg_whats WHERE  whats_id = '".$id_msg."' limit 1 ");         	
        	 $stm->execute(); 
             $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
             $idTemplate =  $response->msg_template;  
			 $id_ATIVO =  $response->whats_ativo; 
			 $_textoenvio =  rawurlencode($response->whats_mensagem);   
			
			 if($id_ATIVO == 1) { 
						$stm = $pdo->prepare("SELECT empresa_urlwats,empresa_departmentId,empresa_serviceId,empresa_tokenwats,empresa_fromtelefone FROM $base.empresa WHERE empresa_id = ?");
						$stm->bindParam(1, $empresa, \PDO::PARAM_STR);
						$stm->execute();           
					
						if ( $stm->rowCount() > 0 ){
							$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
							foreach($response as $row){					
								//$departmentId =  $row->empresa_departmentId;
							//	$serviceId =   $row->empresa_serviceId; 
								$urlwats =  trim($row->empresa_urlwats); //endpoint 
								$tokenwats = trim($row->empresa_tokenwats); //bear]
								$telefoneFom = trim($row->empresa_fromtelefone);                    
							}
						}

						if( $urlwats == ""){
							$_retmensagem = '<b style="color:#f05050;">Mensagem WhatsApp não configurado!</b> ';
							return $_retmensagem;	
							exit();
						}

								
						$_fields = '{
							"message": "'.$idTemplate .'",
							"to": "55'.$fone_envio.'",
							"from": "'.$telefoneFom.'",
							"paramsTemplate": '.$parameters.'
							}';
							
									// Decodificar o JSON original em um array associativo
						$array_original = json_decode($_fields, true);

						// Reformatar o JSON com a opção JSON_PRETTY_PRINT
						$json_formatado = json_encode($array_original, JSON_PRETTY_PRINT);

						if($urlwats != "" and $tokenwats != "") {
							$tokenwats = 'Authorization: Bearer '.$tokenwats;
				
						$curl = curl_init();
				
						curl_setopt_array($curl, array(
						CURLOPT_URL => $urlwats,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 15,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_POSTFIELDS =>''.$json_formatado.'',
						CURLOPT_HTTPHEADER => array(
							'Content-Type: application/json',
							''.$tokenwats.''
						),
						));
					
						$response = curl_exec($curl);  
						// print_r( $response);
						curl_close($curl);		  
					
						$obj = json_decode($response);		
							
						
						if(  $obj->protocol->bot->active != 'true' ){
							$_retmensagem = '<b style="color:#f05050;">Falha envio</b> ';
						}else{
							$_retmensagem = '<b style="color:#00A8E6;">Enviado</b> ';
						
						}  
						/*
						$stm = $pdo->prepare("INSERT INTO ".$base.".logmensagem(
							log_data,
							log_datahora,
							log_documento,
							log_idcliente,
							log_texto,
							log_ret,
							log_send,
							log_sequencia
							) 
							VALUES (
								CURRENT_DATE(),
								NOW(), 
								?,
								?,
								?, 
								?,
								?,
								?
							); ");
							$stm->bindParam(1, $refdoc);			
							$stm->bindParam(2, $id_cliente);	
							$stm->bindParam(3, $_textoenvio);
							$stm->bindParam(4, $response);
							$stm->bindParam(5, $obj->sent);
						
							$stm->bindParam(6, $id_msg);		
							$stm->execute();	    
							*/            
							
						}	
					}

        }
        catch (\Exception $fault){
			$response = $fault;
        }
       return $_retmensagem;		
	 
	}

	
	public static function whats_oficialSuri($id_msg ,$id_cliente,$fone_envio, $refdoc , $base, $empresa, $parameters,$_nomeconsumidor){
		
        try{			
			$pdo = MySQL::acessabd();			
			date_default_timezone_set('America/Sao_Paulo');   

			$dontOpenTicket = "false";

			$departmentId = "";
			$serviceId = "0";
			$urlwats = "";

			$fone_envio = str_replace(" ", "", $fone_envio);

			//TEMPLATE
			 $stm = $pdo->prepare("SELECT msg_template,whats_mensagem,whats_ativo FROM ".$_SESSION['BASE'].".msg_whats WHERE  whats_id = '".$id_msg."' limit 1 ");         	
        	 $stm->execute(); 
             $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
             $idTemplate =  $response->msg_template;  
			 $id_ATIVO =  $response->whats_ativo; 
			 $_textoenvio =  rawurlencode($response->whats_mensagem);   
			
			 if($id_ATIVO == 1) { 
						$stm = $pdo->prepare("SELECT empresa_urlwats,empresa_departmentId,empresa_serviceId,empresa_tokenwats,empresa_fromtelefone FROM $base.empresa WHERE empresa_id = ?");
						$stm->bindParam(1, $empresa, \PDO::PARAM_STR);
						$stm->execute();           
					
						if ( $stm->rowCount() > 0 ){
							$response =  $stm->fetchAll(\PDO::FETCH_OBJ);
							foreach($response as $row){					
								//$departmentId =  $row->empresa_departmentId;
								$serviceId =   $row->empresa_serviceId; 
								$urlwats =  trim($row->empresa_urlwats); //endpoint 
								$tokenwats = trim($row->empresa_tokenwats); //bear]
								$telefoneFom = trim($row->empresa_fromtelefone);                    
							}
						}

						if( $urlwats == ""){
							$_retmensagem = '<b style="color:#f05050;">Mensagem WhatsApp não configurado!</b> ';
							return $_retmensagem;	
							exit();
						}

						
								
										$_fields = '{
													"user": {
														"name": "' . $_nomeconsumidor . '",
														"phone": "55' . $fone_envio . '",
														"email": null,
														"gender": 0,
														"channelId": "' . $serviceId . '",
														"channelType": 1,
														"defaultDepartmentId": null
													},
													"message": {
														"templateId": "' . $idTemplate . '",
														"BodyParameters": ['.$parameters.']
													}
												}';
					   			

				   
												
									// Decodificar o JSON original em um array associativo
						$array_original = json_decode($_fields, true);

						// Reformatar o JSON com a opção JSON_PRETTY_PRINT
						$json_formatado = json_encode($array_original, JSON_PRETTY_PRINT);

						if($urlwats != "" and $tokenwats != "") {
							$tokenwats = 'Authorization: Bearer '.$tokenwats;
				
						$curl = curl_init();
				
						curl_setopt_array($curl, array(
						CURLOPT_URL => $urlwats,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 15,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_POSTFIELDS =>''.$json_formatado.'',
						CURLOPT_HTTPHEADER => array(
							'Content-Type: application/json',
							''.$tokenwats.''
						),
						));
					
						$response = curl_exec($curl);  
						// print_r( $response);
						curl_close($curl);		  
					
						$obj = json_decode($response);		
							
						
						if(  $obj->success != 'true' ){
							$_retmensagem = '<b style="color:#f05050;">Falha envio</b>'.$obj->error;
						}else{
							$_retmensagem = '<b style="color:#00A8E6;">Enviado</b> ';
						
						}  
					      
							
						}	
					}

        }
        catch (\Exception $fault){
			$response = $fault;
        }
       return $_retmensagem;		
	 
	}
	

   
}

