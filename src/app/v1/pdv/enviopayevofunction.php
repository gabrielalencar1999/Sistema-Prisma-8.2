<?php 
//session_start();

     require_once('../../../api/config/config.inc.php');
     require FILE_BASE_API . '/vendor/autoload.php';
    
     
     use Database\MySQL;
     $pdo = MySQL::acessabd();

     date_default_timezone_set('America/Sao_Paulo');
      
       // echo "ACAO   $_ACAOCARTAO  |";
       $consulta_ped = $pdo->query("Select p_requestfinpet  from " . $_SESSION['BASE'] . ".parametro ");
       $retPedido = $consulta_ped->fetch();
       $_SESSION['URLEVO'] = $retPedido["p_requestfinpet"];	
      
        function verificarstatus($_status,$_usernameEvo,$_apikeyEvo) {
            $_ret = 0;
          
            if($_status == "REVOKED_TOKEN" or $_status == "EXPIRED_TOKEN"  or $_status == "INVALID_TOKEN"){
               $retorno =  atualizaBear($_usernameEvo,$_apikeyEvo);
            
               if ($retorno != "") {
                $_ret = 3;
               }else{
                $_ret = 2;
               }
               
            }else{
          
                if($_status != "REMOTE_TRANSACTION_SUCCESS" ){
                    $_ret = 1;
                }else{
                    $_ret = 0;
                }
                   
                    
                
            }

           
           return $_ret;
        }

        function atualizaBear($_usernameEvo,$_apikeyEvo) {
            $pdo = MySQL::acessabd();
            $curl = curl_init();
            $_url = $_SESSION['URLEVO']."/remote/token";
            curl_setopt_array($curl, array(
            CURLOPT_URL =>  $_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "auth": {
                    "username": '.$_usernameEvo.',
                    "apiKey": '.$_apikeyEvo.'
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            $obj = json_decode($response);
            
           
          
          
            if( $obj->Bearer != '') {               
                $_TOKENBEAR = $obj->Bearer;
                $_sql = "UPDATE " . $_SESSION['BASE'] . ".empresa_dados SET paytoken = '$_TOKENBEAR',paydttoken = CURRENT_DATE() WHERE id = '".$_SESSION['BASE_ID']."'";	                  
         
                $stm = $pdo->prepare($_sql);	
                $stm->execute();

                return  '';
            }else{
             
                $_MENSAGEM = $obj->error;
               
                $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                WHERE statusTrans_cod = '$_MENSAGEM' ");
                $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);

                $_MOTIVO = $retorno->statusTrans_motivo;
                if($_MOTIVO == ""){
                    $_MOTIVO =  $_MENSAGEM;
                }
                ?>
                    <div class="row">
                        <div class="col-sm-12" align="center">
                        <i class="fa fa-5x fa-ban"></i>
                                                
                        </div>
                        </div>  
                        <div class="col-sm-12" align="center">			
                    <p><?php echo "OPS!!! <br>".$_MOTIVO; ?> </p>
                        <p></p>
                    </div>
                
                
                    <div style="padding: 17px;" align="center">
                        <button type="button" class="btn btn-default waves-effect"  onclick="_fecharFinalizar()" >Fechar</button>
                        
                    </div>
                <?php
                 return  $_MENSAGEM;
          
            }
           
           
     

         	

                   
        }

        function cancelartransacao($_pay_uuid,$_TOKENBEAR) {
            $curl = curl_init();
            $_url = $_SESSION['URLEVO']."/remote/$_pay_uuid";
            curl_setopt_array($curl, array(
            CURLOPT_URL =>  $_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                'bearer:'.$_TOKENBEAR.'',
                'Content-Type: application/json'
              ),
            ));

            $response = curl_exec($curl);
            $obj = json_decode($response);
            $_MENSAGEM = $obj->error;
//print_r($obj);
            curl_close($curl);
           
       
            return $_MENSAGEM;


        }



        function criatransacao($_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split,$_terminal) { 
            $pdo = MySQL::acessabd();
            $curl = curl_init();
            $count = count($_split);
            $_SLIPTDESC = "";
            if($count > 0) {
            $_SLIPT = ',"splits": ['; 
                for ($i = 0; $i < $count; $i++) {
                    $_code = $_split["$i"]["code"];
                    $_vl = $_split["$i"]["valor"];
                    $_SLIPTDESC = $_SLIPTDESC.";$_code:$_vl;";
                    $_SLIPT = $_SLIPT.
                         '{
                             "code": '.$_code.',
                             "value": "'.$_vl.'",
                             "chargeFees": true                  
                          }';
                       
                        if(($i+1) < $count ){
                            $_SLIPT = $_SLIPT. ',';
                        
                           
                        
                    }
                  
                }
                $_SLIPT = $_SLIPT. ']';
                }

                if($_terminal != ""){
                    $_terminalname = 'terminalId": "'.$_terminal.'",';    
                }
            $_envioText = "$_merchantId;$_value;$_installments;$_clientName; $_terminalname;$_paymentBrand; $_SLIPTDESC";
            $_url = $_SESSION['URLEVO']."/remote/transaction";
         //   echo    $_envioText;
            curl_setopt_array($curl, array(
               CURLOPT_URL => $_url,
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => 'POST',
               CURLOPT_POSTFIELDS =>'{
                   "transaction":{
                       "merchantId": "'.$_merchantId.'",
                       "value": "'.$_value.'",
                       "installments": "'.$_installments.'",
                       "clientName": "'.$_clientName.'", 
                       '. $_terminalname.'                        
                       "paymentBrand": "'.$_paymentBrand.'",
                       "callbackUrl": "'.$_callbackUrl.'"
                       '. $_SLIPT.'
                   }
               }',
               CURLOPT_HTTPHEADER => array(
                   'bearer:'.$_TOKENBEAR.'',
                   'Content-Type: application/json'
                 ),
             ));

           $response = curl_exec($curl);

           $obj = json_decode($response);


           
           $_STATUS = ($obj->success);
          
           if( $_STATUS == 'true') {
               $_STATUS = 1;
               $_e = 'e';
           }else{
               $_STATUS = 0;
               $_e = 'x';
           }
          
           $_MENSAGEM = $obj->error;
       

           $_IDTRANSACTION = $obj->transactionId;                                        

           curl_close($curl);

           $_sql = "INSERT INTO " . $_SESSION['BASE'] . ".linkPay ( 
                    pay_idempresa,pay_data,pay_hora,pay_uuid,pay_status,pay_textoEnvio,pay_textoRetorno,
                    pay_pedidoRef,pay_pedidoRefCaixa,pay_tipo,	pay_mensagem,
                    pay_valor,pay_parcelas) VALUES(
                    '".$_SESSION['BASE_ID']."',CURRENT_DATE(),NOW(),'$_IDTRANSACTION','$_STATUS','$_envioText','$response ',
                    '$_PEDIDO','$_CAIXA','$_e','$_MENSAGEM',
                    '$_value','$_installments')";	
                             
           $stm = $pdo->prepare($_sql);	
           $stm->execute();	

           return $_MENSAGEM;

        }

        


        