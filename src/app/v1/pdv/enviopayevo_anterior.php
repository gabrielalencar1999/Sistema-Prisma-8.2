<?php 
//session_start();
   //  require_once('../../../api/config/config.inc.php');
    // require FILE_BASE_API . '/vendor/autoload.php';
     
     use Database\MySQL;
        $pdo = MySQL::acessabd();
      
       // echo "ACAO   $_ACAOCARTAO  |";
       $consulta_ped = $pdo->query("Select p_requestfinpet  from " . $_SESSION['BASE'] . ".parametro ");
       $retPedido = $consulta_ped->fetch();
       $_SESSION['URLEVO'] = $retPedido["p_requestfinpet"];	
      
        function verificarstatus($_status,$_usernameEvo,$_apikeyEvo) {
            $_ret = 0;
          
            if($_status == "REVOKED_TOKEN" or $_status == "INVALID_TOKEN" or $_status == "EXPIRED_TOKEN"){
                atualizaBear($_usernameEvo,$_apikeyEvo);
                $_ret = 2;
            }else{
               
                    $_ret = 0;
                
            }

           
           return $_ret;
        }

        function atualizaBear($_usernameEvo,$_apikeyEvo) {
            $pdo = MySQL::acessabd();
            $curl = curl_init();
            $_url = $_SESSION['URLEVO']."/remote/token";

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
            $_TOKENBEAR = $obj->Bearer;
       //  print_r($obj->Bearer);

            $_sql = "UPDATE " . $_SESSION['BASE'] . ".empresa_dados SET paytoken = '$_TOKENBEAR',paydttoken = CURRENT_DATE() WHERE id = '".$_SESSION['BASE_ID']."'";	                  
         
            $stm = $pdo->prepare($_sql);	
            $stm->execute();	

                    return $_TOKENBEAR;
        }

        function cancelartransacao($_pay_uuid,$_TOKENBEAR) {
            $curl = curl_init();
            $_url = $_SESSION['URLEVO']."/remote/$_pay_uuid";
            curl_setopt_array($curl, array(
            CURLOPT_URL => $_url,
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

            curl_close($curl);
           
         //   print_r($response);

            return $_MENSAGEM;


        }
        function criatransacao($_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split) { 
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
            $_envioText = "$_merchantId;$_value;$_installments;$_clientName;$_PEDIDO;$_CAIXA;$_paymentBrand; $_SLIPTDESC";
            $_url = $_SESSION['URLEVO']."/remote/transaction";
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

         //  print_r($obj);
           
           $_STATUS = ($obj->success);
           if( $_STATUS == true) {
               $_STATUS = 1;
           }else{
               $_STATUS = 0;
           }

           $_MENSAGEM = $obj->error;
       

           $_IDTRANSACTION = $obj->transactionId;                                        

           curl_close($curl);

           $_sql = "INSERT INTO " . $_SESSION['BASE'] . ".linkPay ( 
                    pay_idempresa,pay_data,pay_hora,pay_uuid,pay_status,pay_textoEnvio,pay_textoRetorno,
                    pay_pedidoRef,pay_pedidoRefCaixa,pay_tipo,	pay_mensagem,
                    pay_valor,pay_parcelas) VALUES(
                    '".$_SESSION['BASE_ID']."',CURRENT_DATE(),NOW(),'$_IDTRANSACTION','$_STATUS','$_envioText','$response ',
                    '$_PEDIDO','$_CAIXA','e','$_MENSAGEM',
                    '$_value','$_installments')";	
                             
           $stm = $pdo->prepare($_sql);	
           $stm->execute();	

           return $_MENSAGEM;

        }

        

       //VERIFICAR AUTENTICACAO	
    //   $_callbackUrl =  "https://webhook.site/c3f329ae-6db3-4b35-b209-c6bf25c1e0b2";
      $_callbackUrl =  "https://dvet.com.br/app/pay/";
      
       try {
            $consulta = $pdo->query("SELECT merchantId,payusername,paykeyprisma,paydttoken,paytoken FROM " . $_SESSION['BASE'] . ".empresa_dados 
                                    WHERE id = '".$_SESSION['BASE_ID']."' ");
            $retorno = $consulta->fetch(\PDO::FETCH_OBJ);

            $_usernameEvo = $retorno->payusername;

            $_apikeyEvo = $retorno->paykeyprisma;

            $_apidataEvo= $retorno->paydttoken;

            $_merchantId = $retorno->merchantId;        

            $_TOKENBEAR = $retorno->paytoken;
       
                    if($_apidataEvo != (date('Y')."-".date('m')."-".date('d'))) { 
                    
                            //atualiza BEAR autenticação
                         //   echo "atualiza BEAR $_usernameEvo,$_apikeyEvo <bR> ";

                            $_TOKENBEAR =   atualizaBear($_usernameEvo,$_apikeyEvo);

                    }

                }    catch (PDOException $e) {      
                    echo $e->getMessage();
               }           
              
       
       

        if($_ACAOCARTAO == 1 ){	 //CRIAR TRANSACA
            try{
               
/*
                $_CAIXA = '1';
                $_PEDIDO = '99';
               // "terminalId":"AA006003",
               $_value = '11.02';
               $_installments = 1;  //parcelas
               $_clientName = "$_PEDIDO-$_CAIXA ROBSON PRISMA";
            //   $_terminalId"
               $_paymentBrand = "VISA_CREDITO";


    $_split = array();
    $_arr =  array("code" => "9YTDOO","valor" => "1.00");
    array_push($_split,$_arr);
    $_arr =  array("code" => "E75UXK","valor" => "1.00");   
    array_push($_split,$_arr); 
        */
      //  echo "ENVIAR <bR>";
       // print_r($_split);
               $_retorno = criatransacao($_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split);
            //   echo "RETORNO $_retorno";  

               $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);

             //  echo "STATUS $_retstatus ";  
                   if($_retstatus == 2){
                  //  atualizaBear($_usernameEvo,$_apikeyEvo);
                    $_retorno = criatransacao($_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split);
                  
                  } else{
                  
                    if($_retstatus == 1){
                        $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                                    WHERE statusTrans_cod = '$_retorno' ");
                            $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);

                            echo "OPS!!! <br>".$retorno->statusTrans_motivo;
                    }

                  }   
    
            
                }    catch (PDOException $e) {      
                    echo $e->getMessage();
            }
    
        }

        if($_ACAOCARTAO == 9 ){	 //cancelar transacao

            $_retorno = cancelartransacao($_pay_uuid,$_TOKENBEAR);

          //  echo "RETORNO $_retorno ";
           
            $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);

         //   echo "STATUS $_retstatus ";  
             if($_retstatus == 0){
                        $_sql = "UPDATE " . $_SESSION['BASE'] . ".linkPay SET pay_tipo = 'x' WHERE pay_idempresa = '".$_SESSION['BASE_ID']."' and pay_uuid = '$_pay_uuid'";	                  
                      
                        $stm = $pdo->prepare($_sql);	
                        $stm->execute();
                      
                   }else{
                        $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                          WHERE statusTrans_cod = '$_retorno' ");
                          $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);

                            echo "OPS!!! <br>".$retorno->statusTrans_motivo;
                   }
       	

        }
