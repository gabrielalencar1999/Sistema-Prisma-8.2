<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function verificarstatus($_status,$_usernameEvo,$_apikeyEvo) {
    $_ret = 0;

    if($_status == "REVOKED_TOKEN" or $_status == "INVALID_TOKEN" or $_status == "EXPIRED_TOKEN" ){
        atualizaBear($_usernameEvo,$_apikeyEvo);
        $_ret = 2;
    }else{
       
     $_ret = 0;
        
    }

   
   return $_ret;
}

function atualizaBear($_usernameEvo,$_apikeyEvo, $_linkURL) {
    $pdo = MySQL::acessabd();
    $curl = curl_init();

    $_linkURL = $_linkURL.'/remote/token';
    curl_setopt_array($curl, array(
    CURLOPT_URL =>  $_linkURL ,
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


    $_sql = "UPDATE " . $_SESSION['BASE'] . ".empresa_dados SET paytoken = '$_TOKENBEAR',paydttoken = CURRENT_DATE() 
    WHERE id = '".$_SESSION['BASE_ID']."'";	                  

    $stm = $pdo->prepare($_sql);	
    $stm->execute();	

            return $_TOKENBEAR;
}



try {

    $stm = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".parametro");
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    $_linkURL = $result[0]['p_requestfinpet'];     


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
                //  echo "atualiza BEAR $_usernameEvo,$_apikeyEvo <bR> ";

                    $_TOKENBEAR =   atualizaBear($_usernameEvo,$_apikeyEvo, $_linkURL);

            }

             

        }    catch (PDOException $e) {      
            echo $e->getMessage();
       }



/*
 * Lista beneficiario finpet
 * */
if ($acao["acao"] == 0) {

  //  $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);

  $_TOKENBEAR = atualizaBear($_usernameEvo,$_apikeyEvo,$_linkURL);

    $_linkURL =   $_linkURL."/remote/merchants/".$_merchantId."/recipients";

            
                   //tudo certo
                   $consulta = $pdo->query("SELECT paytoken FROM " . $_SESSION['BASE'] . ".empresa_dados 
                   WHERE id = '".$_SESSION['BASE_ID']."' ");
                   $ret = $consulta->fetch(\PDO::FETCH_OBJ);
                   $_TOKENBEAR = $ret->paytoken;
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                        CURLOPT_URL => $_linkURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'bearer:'.$_TOKENBEAR.'',
                            'Content-Type: application/json'
                        ),
                        ));

                        $response = curl_exec($curl);
                        $decode = json_decode( $response, TRUE );
                      

                        curl_close($curl);

                        $_STATUS = ($decode->success);
                        $_MENSAGEM = $obj->error;
 
                         if( $_STATUS == true) {
                             $_STATUS = 1;
                         }else{
                             
                             $_STATUS = 0;
                             
                         }
 
                         $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);
 
                         if($_retstatus == 2 or $_retstatus == 0){
                   
                     } else{
                             if($_retstatus == 1){
                                 $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                                             WHERE statusTrans_cod = '$_retorno' ");
                                     $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);
 
                                     echo "OPS!!! <br>".$retorno->statusTrans_motivo;
                             }
                  
                  } 
   
  

    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
        <th class="text-center"></th>
            <th class="text-center">Código</th>
            <th>Descrição</th>   
            <th>Documento</th>            
        </tr>
        </thead>
        <tbody>
        <?php
      foreach ( $decode as $valor){
        $_cod = $valor["code"];
        $_doc = $valor["document"];
        $_name = $valor["name"];

        //verificar se existe cadastro
        $consultaLinha = $pdo->query("Select usuario_CODIGOUSUARIO
        from " . $_SESSION['BASE'] . ".usuario  
        where  usuario_cpf = '".$_doc."' ");
        $retornoLinha = $consultaLinha->fetchAll();
    
        if($consultaLinha->rowCount() > 0 ) { 
            foreach ($retornoLinha as $row) {          
                $_idcolaborador = $row["usuario_CODIGOUSUARIO"] ;
            
            }
        }else{
                //cadastro novo colaborador
                $_SQL = "INSERT INTO " . $_SESSION['BASE'] . ".usuario
                  (usuario_cpf,usuario_APELIDO,usuario_NOME,usuario_colaborador,usuario_PERFIL,cod_beneficiario,
                  usuario_perfil2,usuario_lancaCC,usuario_comissionado,usuario_ATIVO )
                VALUES (?,?,?,'1','8',?,'8','-1','-1','-1')"; 
            
                    $_insert = $pdo->prepare($_SQL);
                    $_insert->bindParam(1, $_doc);
                    $_insert->bindParam(2, $_name);
                    $_insert->bindParam(3, $_name);
                    $_insert->bindParam(4, $_cod);
                    $_insert->execute();
                    
                    $consultaLinha = $pdo->query("Select usuario_CODIGOUSUARIO
                                                    from " . $_SESSION['BASE'] . ".usuario  
                                                    where  usuario_cpf = '".$_doc."' ");
                    $retornoLinha = $consultaLinha->fetchAll();	
                    foreach ($retornoLinha as $row) {          
                        $_idcolaborador = $row["usuario_CODIGOUSUARIO"] ;
                        
                    }

                    $sql2="insert into " . $_SESSION['BASE'] . ".colaborador 
                    (colaborador_usuario,colaborador_empresa,colaborador_aceite,colaborador_status
                    ) values ('$_idcolaborador','".$_SESSION['BASE_ID']."','0','-1')";
                    $stm2 = $pdo->prepare($sql2);
                    $stm2->execute();
                    
            } 
            ?>
            <tr >
            <td><?=++$_i;?></td>
                <td><?=$_cod?></td>
                <td><?=$_name?></td>
                <td><?=$_doc?></td>
               
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}


/*
 * Lista terminal finpet
 * */
if ($acao["acao"] == 1) {


    $_TOKENBEAR = atualizaBear($_usernameEvo,$_apikeyEvo,$_linkURL);
 
    $_linkURL =   $_linkURL."/remote/merchants/".$_merchantId."/terminals";

        
                   //tudo certo
               
                //   echo "<br>".$_linkURL."<br>".$_TOKENBEAR."xx"; ;
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                        CURLOPT_URL => $_linkURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'bearer:'.$_TOKENBEAR.'',
                            'Content-Type: application/json'
                        ),
                        ));

                        $response = curl_exec($curl);
                    //  print_r( $response);
                        $decode = json_decode( $response, TRUE );
                       curl_close($curl);
                      
                       $_STATUS = ($decode->success);
                       $_MENSAGEM = $obj->error;

                        if( $_STATUS == true) {
                            $_STATUS = 1;
                        }else{
                            
                            $_STATUS = 0;
                            
                        }

                        $_retstatus =  verificarstatus($_retorno,$_usernameEvo,$_apikeyEvo);

                        if($_retstatus == 2 or $_retstatus == 0){
                  
                    } else{
                            if($_retstatus == 1){
                                $consulta_erro = $pdo->query("SELECT statusTrans_motivo FROM " . $_SESSION['BASE'] . ".statusTrans 
                                            WHERE statusTrans_cod = '$_retorno' ");
                                    $retorno = $consulta_erro->fetch(\PDO::FETCH_OBJ);

                                    echo "OPS!!! <br>".$retorno->statusTrans_motivo;
                            }
                  }
   
  

    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            
            <th class="text-center">Código</th>
            <th>Descrição</th>   
            <th>Status</th>             
        </tr>
        </thead>
        <tbody>
        <?php
      foreach ( $decode as $valor){
        $_name = $valor["computerName"];
        $_terminalid = $valor["terminalId"];
        $_status = $valor["terminalStatus"];
     
       
            ?>
            <tr >
            <td><?=$_terminalid?></td>
             <td><?=$_name?></td>       
             <td><?=$_status?></td>         
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}

