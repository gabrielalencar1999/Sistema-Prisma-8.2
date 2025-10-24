<?php 
//session_start();

date_default_timezone_set('America/Sao_Paulo');

       //VERIFICAR AUTENTICACAO	
    // $_callbackUrl =  "https://webhook.site/e9067162-dd76-4290-acd7-8e8c86062eeb";
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
                       // echo "atualiza BEAR $_usernameEvo,$_apikeyEvo <bR> ";

                           // $_TOKENBEAR =   atualizaBear($_usernameEvo,$_apikeyEvo);
                            $retorno =  atualizaBear($_usernameEvo,$_apikeyEvo);
             
                            if ($retorno != "") {
                                    exit();
                              }else{
                                $_TOKENBEAR = $retorno;
                              }
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
     //  echo "$_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split,$_terminal";
        
     
     
     $_retorno = criatransacao($_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split,$_terminal);
             //  print_r($_retorno);
               $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);
            
               if($_retstatus == 3){
                   exit();
               }
                   if($_retstatus == 2){
                    
                  //  atualizaBear($_usernameEvo,$_apikeyEvo);
                  $consulta = $pdo->query("SELECT merchantId,payusername,paykeyprisma,paydttoken,paytoken FROM " . $_SESSION['BASE'] . ".empresa_dados 
                  WHERE id = '".$_SESSION['BASE_ID']."' ");
                    $retorno = $consulta->fetch(\PDO::FETCH_OBJ);
                    $_usernameEvo = $retorno->payusername;
                    $_apikeyEvo = $retorno->paykeyprisma;
                    $_apidataEvo= $retorno->paydttoken;
                    $_merchantId = $retorno->merchantId;        
                    $_TOKENBEAR = $retorno->paytoken;
                    $_retorno = criatransacao($_merchantId,$_value,$_installments,$_clientName,$_PEDIDO,$_CAIXA,$_paymentBrand,$_TOKENBEAR, $_callbackUrl,$_split,$_terminal);
                  
                    $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);
                    if($_retstatus == 1){
                        $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                                    WHERE statusTrans_cod = '$_retorno' ");
                            $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);
                            $_MOTIVO = $retorno->statusTrans_motivo;
                            if($_MOTIVO == ""){
                                $_MOTIVO =  $_retorno;
                            }
                            
                            ?>
                                <div class="row">
                                    <div class="col-sm-12" align="center">
                                    <i class="fa fa-5x  fa-ban"></i>
                                                            
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
                          exit();
                    }
                  } else{
                  
                    if($_retstatus == 1){
                    
                        $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                                    WHERE statusTrans_cod = '$_retorno' ");
                            $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);

                            $_MOTIVO = $retorno->statusTrans_motivo;
                            if($_MOTIVO == ""){
                                $_MOTIVO =  $_retorno;
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
                            exit();
                           
                    }

                  }   
    
            
                }    catch (PDOException $e) {      
                    echo $e->getMessage();
            }
    
        }

        if($_ACAOCARTAO == 9 ){	 //cancelar transacao

            $_idpedido = $_parametros["id_pedido"];
            $_idcaixa =  $_parametros["id_caixa"];         


                    $_SQL = "Select * from " . $_SESSION['BASE'] . ".linkPay           
                    where  pay_idempresa = '".$_SESSION['BASE_ID']."' 
                    AND  pay_pedidoRef = '".$_idpedido."' 
                    AND pay_pedidoRefCaixa = '".$_idcaixa."' 
                    AND pay_tipo = 'e'
                    AND pay_status = 1";
                    $consultaLinha = $pdo->query($_SQL);

                    $retornoLinha = $consultaLinha->fetchAll();
                    foreach ($retornoLinha as $row_a) {  
                    $_pay_uuid = $row_a['pay_uuid'];
                        $_retorno = cancelartransacao($_pay_uuid,$_TOKENBEAR);

                        
                            
                            // $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);

                    
                                if($_retorno == 'OK_MSG'){
                                            $_sql = "UPDATE " . $_SESSION['BASE'] . ".linkPay SET pay_tipo = 'x' WHERE pay_idempresa = '".$_SESSION['BASE_ID']."' and pay_uuid = '$_pay_uuid'";	                  
                                        
                                        $stm = $pdo->prepare($_sql);	
                                        $stm->execute();
                                        
                                    }else{
                                            $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                                            WHERE statusTrans_cod = '$_retorno' ");
                                            $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);
                                            $_MOTIVO = $retorno->statusTrans_motivo;
                                            if($_MOTIVO == ""){
                                                $_MOTIVO =  $_retorno;
                                            }

                                                echo "OPS!!!  <br>".$_MOTIVO;
                                                $_sql = "UPDATE " . $_SESSION['BASE'] . ".linkPay SET pay_tipo = 'x' WHERE pay_idempresa = '".$_SESSION['BASE_ID']."' and pay_uuid = '$_pay_uuid'";	                  
                                        
                                            $stm = $pdo->prepare($_sql);	
                                            $stm->execute();
                                            
                                                ?>
                                            
                                        <?php
                                        
                                    }
                            

                            }
    }

