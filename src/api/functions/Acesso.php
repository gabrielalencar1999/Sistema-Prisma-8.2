<?php

namespace Functions;
use Database\MySQL;
use PDOStatement;

class Acesso {
   

    public static function autenticacao(array $params){

        if (!empty($params['_userlogin'])) {
            try {
                $pdo = MySQL::acessabd();
            
                $stm = $pdo->prepare("SELECT usuario_CODIGOUSUARIO, usuario_NOME, usuario_LOGIN, usuario_SENHA, usuario_empresa, usuario_base, usuario_colaborador, base, nome_fantasia, usuario_NOME, usuario_acessoGerencial,usuario_APELIDO, usuario_avatar, usuario_background,
                domingo_ini, domingo_fim, segunda_ini, segunda_fim, terca_ini, terca_fim, quarta_ini, quarta_fim, quinta_ini, quinta_fim, sexta_ini, sexta_fim, sabado_ini, sabado_fim,usuario_acessoexterno
                FROM usuario 
                LEFT JOIN " . $_SESSION['BASE'] . ".empresa_cadastro ON id = usuario_base WHERE usuario_LOGIN = ?");
                $stm->bindParam(1, $params['_userlogin'], \PDO::PARAM_STR);
                $stm->execute();
                $return = $stm->fetch(\PDO::FETCH_OBJ);

                $stm = $pdo->query("CALL " . $_SESSION['BASE'] . ".consulta_saldo('$return->usuario_base')");
                $response = $stm->fetch(\PDO::FETCH_OBJ);
    
                if ($return != NULL) {
                    if (password_verify($params['_pass'], $return->usuario_SENHA)) {
                        if (floatval($response->saldo) > -200) {

                            
                            
                            //busca horario
                            $horario = Acesso::acessoHorario($params['_userlogin']);
                            $explode = explode("|",$horario);
                            $hora_1 = $explode['0'];
                            $hora_2 = $explode['1'];
                            $mensagemHora = $explode['4'];


                            if(strtotime("now") >= strtotime($hora_1) and strtotime("now") <= strtotime($hora_2) or $return->usuario_acessoexterno != "Nao"){
                                $response = array(
                                    'type' => 'success',
                                    'message' => 'Realizando login...'
                                ) + (array) $return;
                            }else{
                                $response = array(
                                    'type' => 'info',
                                    'message' => "Você não possuí permissão para acessar fora de horário! $mensagemHora"
                                );
                            }
                        }
                        else {
                            $response = array(
                                'type' => 'info',
                                'message' => 'Não há saldo suficiente! <a href="#" data-toggle="modal" id="envio-comprovante" data-target="#custom-modal-comprovante">Clique aqui</a> para enviar o comprovante de pagamento.'
                            );
                        }
                    }
                    else {
                        $response = array(
                            'type' => 'info',
                            'message' => 'E-mail ou senha inválidos!'
                        );
                    }
                }
                else {
                    $response = array(
                        'type' => 'info',
                        'message' => 'Usuário não cadastrado!'
                    );
                }
    
            } catch (\PDOException $e) {
                $response = array(
                    'type' => 'error',
                    'message' => 'Erro ao consultar informações!'
                );
            }
        }

        return (Object) $response;
	}   
    
    public static function acessoHorario($idUser){

        try {

            $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
            $data = date("Y-m-d");
            $horaAgora = date("H:i:s");
            $diasemana_numero = date('w', strtotime($data));          
            $pdo = MySQL::acessabd();

            $stm = $pdo->prepare("SELECT domingo_ini, domingo_fim, segunda_ini, segunda_fim, terca_ini, terca_fim, quarta_ini, quarta_fim, quinta_ini, quinta_fim, sexta_ini, sexta_fim, sabado_ini, sabado_fim, usuario_acessoexterno
            FROM " . $_SESSION['BASE'] . ".usuario 
            LEFT JOIN " . $_SESSION['BASE'] . ".empresa_cadastro ON id = usuario_base WHERE usuario_LOGIN = ?");
            $stm->bindParam(1, $idUser, \PDO::PARAM_STR);
            $stm->execute();
            $return = $stm->fetch(\PDO::FETCH_OBJ);


            //verifica se ta dentro do horário permitido
            if($diasemana[$diasemana_numero] == "Domingo"){
                $hora_1 = $return->domingo_ini;
                $hora_2 = $return->domingo_fim;
                $n = "no";
            }
            if($diasemana[$diasemana_numero] == "Segunda"){
                $hora_1 = $return->segunda_ini;
                $hora_2 = $return->segunda_fim;
                $n = "na";
            }
            if($diasemana[$diasemana_numero] == "Terça"){
                $hora_1 = $return->terca_ini;
                $hora_2 = $return->terca_fim;
                $n = "na";
            }
            if($diasemana[$diasemana_numero] == "Quarta"){
                $hora_1 = $return->quarta_ini;
                $hora_2 = $return->quarta_fim;
                $n = "na";
            }
            if($diasemana[$diasemana_numero] == "Quinta"){
                $hora_1 = $return->quinta_ini;
                $hora_2 = $return->quinta_fim;
                $n = "na";
            }
            if($diasemana[$diasemana_numero] == "Sexta"){
                $hora_1 = $return->sexta_ini;
                $hora_2 = $return->sexta_fim;
                $n = "na";
            }
            if($diasemana[$diasemana_numero] == "Sabado"){
                $hora_1 = $return->sabado_ini;
                $hora_2 = $return->sabado_fim;
                $n = "no";
            }
            
            $acessoExterno = $return->usuario_acessoexterno;

            if($hora_1  == "00:00:00"){
                $mensagemHora = "<br>Não está permitido acessar $n <b>$diasemana[$diasemana_numero]</b>.";
            }else{
                $mensagemHora = "<br>Horário permitido <b>$diasemana[$diasemana_numero]: $hora_1 às $hora_2</b>.";
            }
           
            $retorno = $hora_1."|".$hora_2."|".$diasemana[$diasemana_numero]."|".$n."|".$mensagemHora."|".$acessoExterno;
            return $retorno;
        } catch (\PDOException $e) {
            $response = array(
                'type' => 'error',
                'message' => 'Erro ao consultar informações!'
            );
        }

    }  

    public static function pendenciafinanceira(){
        //  session_start();
  
          try {
             
              $pdo = MySQL::acessabd();
      
              //BUSCA PERMISSÕES PARA USUARIOecho 
                      $stm = $pdo->prepare("SELECT date_format(pg_dtvencimento,'%d/%m/%Y') as vencimento,pg_valor FROM info.pagamento 
                        WHERE pg_idcliente = '".$_SESSION['CODIGOCLI']."'  and pg_valorpago = '0' AND pg_dtvencimento <= DATE_SUB(CURDATE(), INTERVAL 4 DAY)");
              $stm->execute();
            
              return $stm->rowCount();         
  
              } catch (\PDOException $e) {
                  $response = array(
                      'type' => 'error',
                      'message' => 'Erro ao consultar informações!'
                  );
              }
          }

    public static function notificacao(){
      //  session_start();

        try {
           
            $pdo = MySQL::acessabd();
    
            //BUSCA PERMISSÕES PARA USUARIOecho 
                    $stm = $pdo->prepare("SELECT COUNT(not_setor) AS registros FROM ".$_SESSION['BASE'].".notificacao WHERE not_setor = '".$_SESSION["perfil"]."' and not_lido = 0");
            $stm->execute();
            $retorno = $stm->fetchAll();
            $reg = 0;
            foreach ($retorno as $row) {
                $reg =$row["registros"];
                }
            return $reg ;         

            } catch (\PDOException $e) {
                $response = array(
                    'type' => 'error',
                    'message' => 'Erro ao consultar informações!'
                );
            }
        }

        public static function notificacaolist(){
            try {
            //    session_start();
                $pdo = MySQL::acessabd();
                 $sql = "SELECT not_id,not_mensagem FROM ".$_SESSION['BASE'].".notificacao WHERE not_setor = '".$_SESSION["perfil"]."' and not_lido = 0";
                //BUSCA PERMISSÕES PARA USUARIOecho 
                $stm = $pdo->prepare("$sql");
                $stm->execute();
                
                $response = $stm->fetchAll(\PDO::FETCH_OBJ);
                return  $response ;
 
                } catch (\PDOException $e) {
                    $response = array(
                        'type' => 'error',
                        'message' => 'Erro ao consultar informações!'
                    );
                }
            }

            public static function notificacaofull(array $params){

                try {
                    session_start();
                    $pdo = MySQL::acessabd();

                    if($params['_dataIni'] == ""){
                        $params['_dataIni'] = date('Y-m-d');
             
                    }
                    if($params['_dataFim'] == ""){
                        $params['_dataFim'] = date('Y-m-d');
                    }
              
                     $sql = "SELECT usuario_LOGIN,not_id,not_mensagem,not_usuario, date_format(not_hora,'%d/%m/%Y %T') as data
                      FROM ".$_SESSION['BASE'].".notificacao
                      LEFT JOIN ".$_SESSION['BASE'].".usuario ON usuario_CODIGOUSUARIO = not_usuario
                       WHERE not_setor = '".$_SESSION["perfil"]."' 
                    ";
                    //BUSCA PERMISSÕES PARA USUARIOecho  not_data BETWEEN '".$params['_dataIni']."' AND '".$params['_dataFim']."  ' 
                 
                    $stm = $pdo->prepare("$sql");
                    $stm->execute();
                    
                    $response = $stm->fetchAll(\PDO::FETCH_OBJ);
                    return  $response ;
     
                    } catch (\PDOException $e) {
                        $response = array(
                            'type' => 'error',
                            'message' => 'Erro ao consultar informações!'
                        );
                    }
                }

                public static function notificacaoUpdate(array $params){

                    try {
                        session_start();
                        $pdo = MySQL::acessabd();
    
              
                  
                         $sql = "UPDATE ".$_SESSION['BASE'].".notificacao 
                         SET not_lido  = 1, not_datalido = CURRENT_DATE(), not_usuariolido = '".$_SESSION['tecnico']."'                          
                           WHERE not_setor = '".$_SESSION["perfil"]."' 
                        ";
                        //BUSCA PERMISSÕES PARA USUARIOecho  not_data BETWEEN '".$params['_dataIni']."' AND '".$params['_dataFim']."  ' 
                     
                        $stm = $pdo->prepare("$sql");
                        $stm->execute();
                        
                        $response = $stm->fetchAll(\PDO::FETCH_OBJ);
                        return  $response ;
         
                        } catch (\PDOException $e) {
                            $response = array(
                                'type' => 'error',
                                'message' => 'Erro ao consultar informações!'
                            );
                        }
                    }
                    public static function avisoUpdate(){

                        try {
                            session_start();
                            $pdo = MySQL::acessabd();      
                                       
                             $sql = "UPDATE info.pagamento 
                             SET  pg_dtaviso = CURRENT_DATE()                       
                               WHERE pg_idcliente = '".$_SESSION['CODIGOCLI']."' ";                          
                         
                            $stm = $pdo->prepare("$sql");
                            $stm->execute();
                          
             
                            } catch (\PDOException $e) {
                                $response = array(
                                    'type' => 'error',
                                    'message' => 'Erro ao atualizar informações!'
                                );
                            }
                        }
                        public static function avisoUpdateUser(){

                            try {
                                session_start();
                                $pdo = MySQL::acessabd();      
                                           
                                 $sql = "UPDATE ".$_SESSION['BASE'].".usuario SET usuario_aviso = CURRENT_DATE()
                                   WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' ";   
                                $stm = $pdo->prepare("$sql");
                                $stm->execute();
                              
                                $_SESSION['_DTUSERAVISO'] = date('Y-m-d');
                 
                                } catch (\PDOException $e) {
                                    $response = array(
                                        'type' => 'error',
                                        'message' => 'Erro ao atualizar informações!'
                                    );
                                }
                            }

                        public static function logAcessoTela(int $tela,$ip)
                        {
                            try {
                                session_start();
                                $pdo = MySQL::acessabd();  
                                date_default_timezone_set('America/Sao_Paulo');    
                                           
                                $sql = "INSERT INTO info.logtela(logtela_tela,logtela_login,logtela_coduser,logtela_data,logtela_hora,logtela_ip) VALUES('".$tela."','".$_SESSION['CODIGOCLI']."','".$_SESSION['tecnico']."',CURRENT_DATE(),NOW(),'$ip')";           
                                $stm = $pdo->prepare("$sql");
                                $stm-> execute();	
                             
                            } catch (\PDOException $th) {
                                $response = array(
                                    'type' => 'error',
                                    'message' => 'Erro ao atualizar informações!'
                                );
                            }
                        }

                        public static function resumoLog($_acao,$_status,$_documento)
                        {
                            try {
                                session_start();
                                $pdo = MySQL::acessabd();  
                                date_default_timezone_set('America/Sao_Paulo');   
                                /* 
                                   if($_acao == 'i'){
                                    $sql = "UPDATE info.logtela(logtela_tela,logtela_login,logtela_coduser,logtela_data,logtela_hora,logtela_ip) VALUES('".$tela."','".$_SESSION['CODIGOCLI']."','".$_SESSION['tecnico']."',CURRENT_DATE(),NOW(),'$ip')";           
                                    
                                   }else{
                                    $sql = "INSERT INTO info.logtela(logtela_tela,logtela_login,logtela_coduser,logtela_data,logtela_hora,logtela_ip) VALUES('".$tela."','".$_SESSION['CODIGOCLI']."','".$_SESSION['tecnico']."',CURRENT_DATE(),NOW(),'$ip')";           
                                   }        
                                
                                $stm = $pdo->prepare("$sql");
                                $stm-> execute();	
                             */
                            } catch (\PDOException $th) {
                                $response = array(
                                    'type' => 'error',
                                    'message' => 'Erro ao atualizar informações!'
                                );
                            }
                        }


                        public static function customizacao(int $_idref){ // customizacoes sistema
                            //  session_start();
                      
                              try {
                                 
                                  $pdo = MySQL::acessabd();
                          
                                  //BUSCA PERMISSÕES PARA USUARIOecho 
                                          $stm = $pdo->prepare("SELECT cust_valor FROM ".$_SESSION['BASE'].".customizacao WHERE cust_id = '".$_idref."'");
                                            $stm->execute();
                                            $retorno = $stm->fetchAll();
                                            $reg = 0;
                                            foreach ($retorno as $row) {
                                                $reg =$row["cust_valor"];
                                                }
                                  return $reg ;         
                      
                                  } catch (\PDOException $e) {
                                      $response = array(
                                          'type' => 'error',
                                          'message' => 'Erro ao consultar informações!'
                                      );
                                  }
                              }

                              public static function customizacaoUsuario($operacao,$_vartipo){ // customizacoes usuario 
                                //  session_start();
                          
                                  try {
                                     
                                      $pdo = MySQL::acessabd();
                                      $_NEWVALOR = 0;  $reg = 0;
                                   
                                      if($operacao == "C") {//consulta        
                                        $reg = "SELECT $_vartipo FROM ".$_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' limit 1";                              
                                              $stm = $pdo->prepare("SELECT $_vartipo FROM ".$_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' limit 1");
                                                $stm->execute();
                                                $retorno = $stm->fetchAll();
                                               
                                                foreach ($retorno as $row) {
                                              $reg =$row["$_vartipo"];
                                                 
                                                    }
                                   
                                        }else{ //update
                                          //  $reg = "SELECT $_vartipo FROM ".$_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' limit 1";                              
                                            $stm = $pdo->prepare("SELECT $_vartipo FROM ".$_SESSION['BASE'].".usuario WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' limit 1");
                                              $stm->execute();
                                              $retorno = $stm->fetchAll();
                                             
                                              foreach ($retorno as $row) {
                                                $_valor = $row["$_vartipo"];                                               
                                                  }
                                                    if($_valor == 0) {
                                                        $_NEWVALOR = 1;
                                                    }else{
                                                        $_NEWVALOR = 0;
                                                    }
                                              //  $reg =  "UPDATE  ".$_SESSION['BASE'].".usuario SET $_vartipo = '$_NEWVALOR' WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."'"  ; 
                                                    $stm = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".usuario SET $_vartipo = '$_NEWVALOR' WHERE usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."'");
                                                    $stm->execute();

                                                } 
                                      return $reg ; 

                          
                                      } catch (\PDOException $e) {
                                          $response = array(
                                              'type' => 'error',
                                              'message' => 'Erro ao consultar informações!'
                                          );
                                      }
                                  }

    public static function permissao($idUser){

            try {
                session_start();
                $pdo = MySQL::acessabd();
                
                $empresa_id = $_SESSION['BASE_ID'];

                //BUSCA PERMISSÕES PARA USUARIOecho 
            
                $stm = $pdo->prepare("select * from " . $_SESSION['BASE'] . ".telas_acesso where tela_user = '".$idUser."' ");
                $stm->execute();
                if($stm->rowCount() > 0){

                    unset($_SESSION['per999']); //usuario somente para financeiro

                    unset($_SESSION['per001']);
                    unset($_SESSION['per002']);
                    unset($_SESSION['per003']);
                    unset($_SESSION['per004']);
                    unset($_SESSION['per005']);
                    unset($_SESSION['per006']);
                    unset($_SESSION['per007']);
                    unset($_SESSION['per008']);
                    unset($_SESSION['per009']);
                    unset($_SESSION['per010']);
                    unset($_SESSION['per011']);
                    unset($_SESSION['per012']);
                    unset($_SESSION['per013']);
                    unset($_SESSION['per014']);

                    unset($_SESSION['per100']);
                    unset($_SESSION['per101']);
                    unset($_SESSION['per102']);
                    unset($_SESSION['per103']);
                    unset($_SESSION['per104']);
                    unset($_SESSION['per105']);
                    unset($_SESSION['per106']);
                    unset($_SESSION['per107']);
                    unset($_SESSION['per108']);
                    unset($_SESSION['per109']);
                    unset($_SESSION['per110']);
                    unset($_SESSION['per111']);
                    unset($_SESSION['per112']);
                    unset($_SESSION['per113']);
                    unset($_SESSION['per114']);
                    unset($_SESSION['per115']);
                    unset($_SESSION['per116']);
                    unset($_SESSION['per117']);
                    unset($_SESSION['per118']);
                    unset($_SESSION['per119']);
                    unset($_SESSION['per120']);
                    unset($_SESSION['per121']);
                    unset($_SESSION['per122']);
                    unset($_SESSION['per123']);
                    unset($_SESSION['per124']);
                    unset($_SESSION['per125']);
                    unset($_SESSION['per126']);
                    unset($_SESSION['per127']);
                    unset($_SESSION['per128']);
                    unset($_SESSION['per129']);
                    unset($_SESSION['per130']);
                    unset($_SESSION['per131']);
                    unset($_SESSION['per132']);
                    unset($_SESSION['per133']);
                    unset($_SESSION['per134']);
                    unset($_SESSION['per135']);
                    unset($_SESSION['per136']);
                    unset($_SESSION['per137']);
                    unset($_SESSION['per138']);
                    unset($_SESSION['per138']);
                    unset($_SESSION['per139']);
                    unset($_SESSION['per140']);
                    unset($_SESSION['per141']);
                    unset($_SESSION['per142']);
                    unset($_SESSION['per143']);
                    unset($_SESSION['per144']);
                    unset($_SESSION['per145']);
                    unset($_SESSION['per146']);
                    unset($_SESSION['per148']);
                    unset($_SESSION['per149']);
                    unset($_SESSION['per151']);
                    unset($_SESSION['per152']);
                    unset($_SESSION['per153']);
                    unset($_SESSION['per154']);
                    unset($_SESSION['per155']);
                    

                    $array = array();
                    foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
                        echo $rst->tela_descricao ;

                        //PERMISSOES PRINCIPAIS=============================================================================================================
                        if($rst->tela_descricao == "999"){ $_SESSION['per999'] = $rst->tela_descricao; }//USUARIO PERFIL FINANCEIRO
                        
                        if($rst->tela_descricao == "1"){ $_SESSION['per001'] = $rst->tela_descricao; }//AGENDA
                        if($rst->tela_descricao == "2"){ $_SESSION['per002'] = $rst->tela_descricao; }//GESTAO DE CLIENTE
                        if($rst->tela_descricao == "3"){ $_SESSION['per003'] = $rst->tela_descricao; }//FINANCEIRO E FINANCEIRO SECUNDARIO
                        if($rst->tela_descricao == "4"){ $_SESSION['per004'] = $rst->tela_descricao; }//ANALISE
                        if($rst->tela_descricao == "5"){ $_SESSION['per005'] = $rst->tela_descricao; }//CLIENTES
                        if($rst->tela_descricao == "6"){ $_SESSION['per006'] = $rst->tela_descricao; }//FINANCEIRO 2
                        if($rst->tela_descricao == "7"){ $_SESSION['per007'] = $rst->tela_descricao; }//ADMINISTRATIVO
                        if($rst->tela_descricao == "8"){ $_SESSION['per008'] = $rst->tela_descricao; }//SERVICOS
                        if($rst->tela_descricao == "9"){ $_SESSION['per009'] = $rst->tela_descricao; }//ESTOQUE
                        if($rst->tela_descricao == "10"){ $_SESSION['per010'] = $rst->tela_descricao; }//CONFIGURACOES
                        if($rst->tela_descricao == "11"){ $_SESSION['per011'] = $rst->tela_descricao; }//MEU SALDO
                        if($rst->tela_descricao == "12"){ $_SESSION['per012'] = $rst->tela_descricao; }//MINHA CONTA
                        if($rst->tela_descricao == "13"){ $_SESSION['per013'] = $rst->tela_descricao; }//CAIXA PDV
                        if($rst->tela_descricao == "14"){ $_SESSION['per014'] = $rst->tela_descricao; }//VENDAS
                        
                        
                        //PERMISSOES SECUNDARIAS //CLIENTES=================================================================================================
                        if($rst->tela_descricao == "100"){ $_SESSION['per100'] = $rst->tela_descricao; }//CLIENTES
                        if($rst->tela_descricao == "101"){ $_SESSION['per101'] = $rst->tela_descricao; }//PLANOS
                        if($rst->tela_descricao == "102"){ $_SESSION['per102'] = $rst->tela_descricao; }//ANIVERSARIOS
                        if($rst->tela_descricao == "103"){ $_SESSION['per103'] = $rst->tela_descricao; }//RESUMO DE VENDAS
                        if($rst->tela_descricao == "104"){ $_SESSION['per104'] = $rst->tela_descricao; }//RELATORIOS

                     
                        
                        //PERMISSOES SECUNDARIAS //FINANCEIRO===============================================================================================
                        if($rst->tela_descricao == "105"){ $_SESSION['per105'] = $rst->tela_descricao; }//RESUMO CAIXA
                        if($rst->tela_descricao == "106"){ $_SESSION['per106'] = $rst->tela_descricao; }//EXTRATO FINANCEIRO
                        if($rst->tela_descricao == "107"){ $_SESSION['per107'] = $rst->tela_descricao; }//NOTAS FISCAIS
                        if($rst->tela_descricao == "108"){ $_SESSION['per108'] = $rst->tela_descricao; }//RECEBIVEIS
                        if($rst->tela_descricao == "109"){ $_SESSION['per109'] = $rst->tela_descricao; }//RELATORIOS
                        if($rst->tela_descricao == "142"){ $_SESSION['per142'] = $rst->tela_descricao; }//FECHAMENTO FINANCEIRO
                        if($rst->tela_descricao == "146"){ $_SESSION['per146'] = $rst->tela_descricao; }//TERMINAL
                        
                        //PERMISSOES SECUNDARIAS //ADMINISTRATIVO===========================================================================================
                        if($rst->tela_descricao == "110"){ $_SESSION['per110'] = $rst->tela_descricao; }//NOTAS DE ENTRADA
                        if($rst->tela_descricao == "111"){ $_SESSION['per111'] = $rst->tela_descricao; }//GERACAO DE ARQUIVOS
                        if($rst->tela_descricao == "112"){ $_SESSION['per112'] = $rst->tela_descricao; }//FORNECEDORES
                        
                        //PERMISSOES SECUNDARIAS //SERVICOS=================================================================================================
                        if($rst->tela_descricao == "113"){ $_SESSION['per113'] = $rst->tela_descricao; }//MAPA SEMANAL
                        if($rst->tela_descricao == "114"){ $_SESSION['per114'] = $rst->tela_descricao; }//RELATORIOS
                        if($rst->tela_descricao == "115"){ $_SESSION['per115'] = $rst->tela_descricao; }//COMISSOES
                        if($rst->tela_descricao == "152"){ $_SESSION['per152'] = $rst->tela_descricao; }//IMPORTAÇÃO CSV OS
                       
                      
                        //PERMISSOES SECUNDARIAS //ESTOQUE==================================================================================================
                        if($rst->tela_descricao == "116"){ $_SESSION['per116'] = $rst->tela_descricao; }//PRODUTOS
                        if($rst->tela_descricao == "117"){ $_SESSION['per117'] = $rst->tela_descricao; }//REQUISICAO
                        if($rst->tela_descricao == "118"){ $_SESSION['per118'] = $rst->tela_descricao; }//MOVIMENTACAO
                        if($rst->tela_descricao == "119"){ $_SESSION['per119'] = $rst->tela_descricao; }//INVENTARIO
                        if($rst->tela_descricao == "120"){ $_SESSION['per120'] = $rst->tela_descricao; }//ARQUIVO DE BALANCA
                        if($rst->tela_descricao == "121"){ $_SESSION['per121'] = $rst->tela_descricao; }//CURVA ABC
                        if($rst->tela_descricao == "122"){ $_SESSION['per122'] = $rst->tela_descricao; }//ESTIQUETA
                        if($rst->tela_descricao == "123"){ $_SESSION['per123'] = $rst->tela_descricao; }//RELATORIOS
                        
                 
                        //PERMISSOES SECUNDARIAS //CONFIGURACOES=============================================================================================
                        if($rst->tela_descricao == "124"){ $_SESSION['per124'] = $rst->tela_descricao; }//DADOS CADASTRAIS
                        if($rst->tela_descricao == "125"){ $_SESSION['per125'] = $rst->tela_descricao; }//FUNCIONARIOS E LOGINS
                        if($rst->tela_descricao == "126"){ $_SESSION['per126'] = $rst->tela_descricao; }//GRUPOS
                        if($rst->tela_descricao == "127"){ $_SESSION['per127'] = $rst->tela_descricao; }//CATEGORIAS
                        if($rst->tela_descricao == "128"){ $_SESSION['per128'] = $rst->tela_descricao; }//ESPECIES
                        if($rst->tela_descricao == "129"){ $_SESSION['per129'] = $rst->tela_descricao; }//AVALIACAO
                        if($rst->tela_descricao == "130"){ $_SESSION['per130'] = $rst->tela_descricao; }//TIPO DE CLIENTE
                        if($rst->tela_descricao == "131"){ $_SESSION['per131'] = $rst->tela_descricao; }//TIPO DE FORNECEDOR
                        if($rst->tela_descricao == "132"){ $_SESSION['per132'] = $rst->tela_descricao; }//ALMOXARIFADO
                        if($rst->tela_descricao == "133"){ $_SESSION['per133'] = $rst->tela_descricao; }//LINHAS
                        if($rst->tela_descricao == "134"){ $_SESSION['per134'] = $rst->tela_descricao; }//CONDICOES DE PAGAMENTOS
                        if($rst->tela_descricao == "135"){ $_SESSION['per135'] = $rst->tela_descricao; }//LIVRO CAIXA
                        if($rst->tela_descricao == "136"){ $_SESSION['per136'] = $rst->tela_descricao; }//GRUPO DE RECEITAS E DESPESAS
                        if($rst->tela_descricao == "137"){ $_SESSION['per137'] = $rst->tela_descricao; }//CONTAS DE RECEITAS E DESPESAS
                        if($rst->tela_descricao == "138"){ $_SESSION['per138'] = $rst->tela_descricao; }//PROJETO, CENTRO DE CUSTO
                        if($rst->tela_descricao == "139"){ $_SESSION['per139'] = $rst->tela_descricao; }//EXTRA A
                        if($rst->tela_descricao == "140"){ $_SESSION['per140'] = $rst->tela_descricao; }//EXTRA B
                        if($rst->tela_descricao == "141"){ $_SESSION['per141'] = $rst->tela_descricao; }//ZERA ESTOQUE
                        if($rst->tela_descricao == "143"){ $_SESSION['per143'] = $rst->tela_descricao; }//COLABORADORES
                        if($rst->tela_descricao == "144"){ $_SESSION['per144'] = $rst->tela_descricao; }//BENEFICIÁRIO
                        if($rst->tela_descricao == "145"){ $_SESSION['per145'] = $rst->tela_descricao; }//TERMINAL
                        if($rst->tela_descricao == "148"){ $_SESSION['per148'] = $rst->tela_descricao; }//REGIAO
                        if($rst->tela_descricao == "149"){ $_SESSION['per149'] = $rst->tela_descricao; }//AVISOS
                        if($rst->tela_descricao == "150"){ $_SESSION['per150'] = $rst->tela_descricao; }//estoque compartilhado
                        
                        if($rst->tela_descricao == "151"){ $_SESSION['per151'] = $rst->tela_descricao; }//log acesso
                        if($rst->tela_descricao == "153"){ $_SESSION['per153'] = $rst->tela_descricao; }//situaçãos O.S
                        if($rst->tela_descricao == "154"){ $_SESSION['per154'] = $rst->tela_descricao; }//situacao oficina
                        if($rst->tela_descricao == "155"){ $_SESSION['per155'] = $rst->tela_descricao; }//cfop
                        if($rst->tela_descricao == "156"){ $_SESSION['per156'] = $rst->tela_descricao; }//tipo garantia / tipo oficina
                        if($rst->tela_descricao == "157"){ $_SESSION['per157'] = $rst->tela_descricao; }//backup
                       
                    }
                }

                $response = array(
                    'type' => 'OK',
                    'message' => 'Permissoes verificadas com sucesso!'
                );
                
            } catch (\PDOException $e) {
                $response = array(
                    'type' => 'error',
                    'message' => 'Erro ao consultar informações!'
                );
            }
        return (Object) $response;
	}



	public static function rotas($_keychave){

		$_ret  = "";
		//view/acesso -------------------------------------------------------------------------------
		/*
		if($_keychave =='_Ar00001' ){
			$_ret  = "../../api/view/acesso/recoverpw00001.php" ;//_Ar00001
		}*/
		switch ($_keychave) {
//*** MENU
			case ($_keychave == '_Am00001'):	
				$_ret  = "../../api/view/acesso/menuacesso.php";//_Am00001 menu
			break;	
			case ($_keychave == '_Am00002'):	
				$_ret  = "../../api/view/gerencial/acesso/menuacesso.php";//_Am00001 menu
			break;
//*** ACESSO
			case ($_keychave == '_Ar00001'):
					$_ret  = "../../api/view/acesso/recoverpw00001.php";//_Ar00001
					break;
			case ($_keychave == '_As00002'):	
					$_ret  = "../../api/view/acesso/cadastro.php";//_Ar00002
					break;
            case ($_keychave == '_As00002A'):	
                  $_ret  = "../../api/view/acesso/cadastro.php";// _As00002A LINK CADASTRO SITE
                    break;   
			case ($_keychave == '_As00004'):	
			  	   $_ret  = "index.php" ;//_Ar00004
						break;
            case ($_keychave == 'ACEXTR'):	
                $_ret  = "../../api/view/acesso/acaoExtrato.php";//_Ar00004
                    break;

            case ($_keychave == '_As00005'):	
                $_ret  = "../../api/view/acesso/acaoNotificacao.php";//_
                 break; 
            case ($_keychave == '_As00006'):	
                $_ret  = "../../api/view/acesso/acaofecharaviso.php";//_
                break;  
            case ($_keychave == '_As00007'):	
                $_ret  = "../../api/view/acesso/acaofecharavisoUser.php";//_
                break;  
                
                              
//*** ATENDIMENTO
           
			case ($_keychave == '_ATa00001'):	
				$_ret  = "../../api/view/atendimento/agend00001.php";//__ATa00001 agenda
				break;
            case ($_keychave == 'ACAGND'):	
                $_ret  = "../../api/view/atendimento/acaoAgenda.php";//__ATa00001 agenda
                break;
			case ($_keychave == '_ATa00002'):	
				$_ret  = "../../api/view/atendimento/atend00002.php" ;//__ATa00002 atendimento
				break;            
            case ($_keychave == '_ATa00003'):	
                $_ret  = "../../api/view/atendimento/atend00003.php" ;//__ATa00003 Análise
                 break;
            case ($_keychave == '_ATa00004'):	
                 	$_ret  = "../../api/view/atendimento/agenda.php" ;//__ATa00001 agenda list
                 break;
                 
            case ($_keychave == '_ATa00005'):	
                    $_ret  = "../../api/view/atendimento/acaoAgend.php" ;// acao agenda __ATa00001
                 break;       
            case ($_keychave == '_ATa00006'):	
                    $_ret  = "../../api/view/atendimento/trackmob.php" ;// trackmob
                 break;     
            case ($_keychave == '_ATa00007'):	
                  $_ret  = "../../api/view/atendimento/trackmoblist.php" ;// trackmob
               break;              
            case ($_keychave == '_ATa00008'):	
                $_ret  = "../../api/view/atendimento/consumidor_cadastro.php" ;// consumidor new edit
            break;   
            case ($_keychave == '_ATa00009'):	
                $_ret  = "../../api/view/atendimento/acaocliente.php" ;// consumidor new edit
             break;              
             case ($_keychave == '_ATa00010'):	
                $_ret  = "../../api/view/atendimento/acaoAgendLista.php" ;// lista agendamento
             break;             
             case ($_keychave == '_ATa00011'):	
                $_ret  = "../../api/view/atendimento/acaoTrackMob.php" ;// trackmob
             break;    
             case ($_keychave == '_ATa00012'):	
                $_ret  = "../../api/view/atendimento/trackmoblist.php" ;// trackmob
             break;  
             case ($_keychave == '_ATa000122'):	
                $_ret  = "../../api/view/atendimento/trackmoblistGerencial.php" ;// trackmob
             break;  
            case ($_keychave == '_ATa100122'):	
                $_ret  = "../../api/view/atendimento/trackmoblistOficina.php" ;// trackmob
             break;  
             
             case ($_keychave == '_ATa00013'):	
                $_ret  = "../../api/view/atendimento/trackmobTotal.php" ;// trackmob
             break; 
             case ($_keychave == '_ATa00014'):	
                $_ret  = "../../api/view/atendimento/trackmobTec.php" ;// trackmob
             break; 
             case ($_keychave == '_ATa00015'):	
                $_ret  = "../../api/view/atendimento/trackmobOrdem.php" ;// trackmob
             break;
             case ($_keychave == '_ATa00016'):	
                $_ret  = "../../api/view/atendimento/trackmoblistOrdem.php" ;// trackmob
             break;

             case ($_keychave == '_ATa00017'):	
                $_ret  = "../../api/view/atendimento/acaoTrackMobConsulta.php" ;// trackmob tecnico OS
             break; 
             
             case ($_keychave == '_ATa00018'):	
                $_ret  = "../../api/view/atendimento/trackmoblistOrdem2.php" ;// trackmob
             break;              
           
             case ($_keychave == '_ATa00018'):	
                $_ret  = "../../api/view/atendimento/trackmoblistOrdem2.php" ;// trackmob
             break;             
                 
             case ($_keychave == '_ATa00020'):	
                $_ret  = "../../api/view/atendimento/trackmobOficina.php" ;// trackmob para oficna
             break; 
             
             case ($_keychave == '_ATa00021'):	
                $_ret  = "../../api/view/atendimento/trackmobTecOficina.php" ;// trackmob para oficna
             break; 
             case ($_keychave == '_ATa00022'):	
                $_ret  = "../../api/view/atendimento/acaoTrackMobOficina.php" ;// trackmob oficina
             break;  
             case ($_keychave == '_ATa00023'):	
                $_ret  = "../../api/view/atendimento/tracklistGerencialOficina.php" ;// PAINEL OFCINA POR TECNICO
             break; 
             case ($_keychave == '_ATa00024'):	
                $_ret  = "../../api/view/atendimento/trackmoblistGerencialOficina.php" ;// LISTA PAINEL OFCINA POR TECNICO
                
            case ($_keychave == '_ATa00025'):	
                $_ret  = "../../api/view/atendimento/trackmobTotalOficina.php" ;//TOTAL PAINEL OFCINA POR TECNICO
            break;     

//*** ESTOQUE
            case ($_keychave == 'PRDLT'):
                $_ret  = "../../api/view/almoxarifado/produtoLista.php";
                break;
            case ($_keychave == 'PRDLTtec'):
                $_ret  = "../../api/view/almoxarifado/produtoListatec.php";
                break;    
            case ($_keychave == 'PRD'):
                $_ret  = "../../api/view/almoxarifado/produto.php";
                break;          
            case ($_keychave == 'ACPRDLT'):
                $_ret  = "../../api/view/almoxarifado/acaoProdutoLista.php";
                break;
            case ($_keychave == 'ACPRDLTtec'):
                $_ret  = "../../api/view/almoxarifado/acaoProdutoListatec.php";
                break;
            case ($_keychave == 'ACPRD'):
                $_ret  = "../../api/view/almoxarifado/acaoProduto.php";
                break;
            case ($_keychave == 'RQEST'):
                $_ret  = "../../api/view/almoxarifado/requisicaoEstoque.php";
                break;
            case ($_keychave == 'RQESTtec'):
                    $_ret  = "../../api/view/almoxarifado/requisicaoEstoquetec.php";
                    break;    
            case ($_keychave == 'RE0001'):
                $_ret  = "../../api/view/almoxarifado/requisicao.php";
                break;
            case ($_keychave == 'RE0001tec'):
                    $_ret  = "../../api/view/almoxarifado/requisicaotec.php";
                    break;    
            case ($_keychave == 'RE0001_LISTA'):
                $_ret  = "../../api/view/almoxarifado/requisicao_list.php";
                break;
            case ($_keychave == 'RE0001_LISTAtec'):
                    $_ret  = "../../api/view/almoxarifado/requisicao_listtec.php";
                    break;    
            case ($_keychave == 'RE0002'):
               $_ret  = "../../api/view/almoxarifado/acaoRequisicao.php";
               break;    

            case ($_keychave == 'RE0003'):
               $_ret  = "../../api/view/almoxarifado/acaoRequisicaoReserva.php";
               break;   
            case ($_keychave == 'ACRQEST'):
                $_ret  = "../../api/view/almoxarifado/acaoRequisicaoEstoque.php";
                break;
            case ($_keychave == 'ACRQESTtec'):
                    $_ret  = "../../api/view/almoxarifado/acaoRequisicaoEstoquetec.php";
                    break;    
            case ($_keychave == 'ACRQESTRPT'):
                $_ret  = "../../api/view/almoxarifado/rptRequisicaoMatr.php";
                break;    
            case ($_keychave == 'MVEST'):
                $_ret  = "../../api/view/almoxarifado/movEstoque.php";
                break;
            case ($_keychave == 'ACMVEST'):
                $_ret  = "../../api/view/almoxarifado/acaoMovEstoque.php";
                break;
            case ($_keychave == 'INVT'):
                $_ret  = "../../api/view/almoxarifado/inventario.php";
                break;
            case ($_keychave == 'ACINVT'):
                $_ret  = "../../api/view/almoxarifado/acaoInventario.php";
                break;
            case ($_keychave == 'ARQBL'):
                $_ret  = "../../api/view/almoxarifado/arquivosBalanca.php";
                break;
            case ($_keychave == 'ACARQBL'):
                $_ret  = "../../api/view/almoxarifado/acaoArquivosBalanca.php";
                break;
            case ($_keychave == 'CVABC'):
                $_ret  = "../../api/view/almoxarifado/curvaAbc.php";
                break;
            case ($_keychave == 'ACCVABC'):
                $_ret  = "../../api/view/almoxarifado/acaoCurvaAbc.php";
                break;
            case ($_keychave == 'ETQT'):
                $_ret  = "../../api/view/almoxarifado/etiqueta.php";
                break;
            case ($_keychave == 'ACETQT'):
                $_ret  = "../../api/view/almoxarifado/acaoEtiqueta.php";
                break;
            case ($_keychave == 'RLTS'):
                $_ret  = "../../api/view/almoxarifado/relatorios.php";
                break;
            case ($_keychave == 'ACRLTS'):
                $_ret  = "../../api/view/almoxarifado/acaoRelatorios.php"; 
                break;
         
//*** FINANCEIRO
			case ($_keychave == '_Fl00004'):	
				$_ret  = "../../api/view/financeiro/finan00001.php" ;//_Fl00004 financeiro
				break;
			case ($_keychave == '_Fl00005'):	
				$_ret  = "../../api/view/financeiro/finan00001_list.php" ;//_Fl00005 financeiro filtra pesquisa
				break;
                     case ($_keychave == '_Fl00006'):	
                $_ret  = "../../api/view/financeiro/finan00002_list.php" ;//_Fl00006 financeiro filtra pesquisa
                break;	
            case ($_keychave == '_Fl00066'):	
                $_ret  = "../../api/view/financeiro/ck_finan00002_list.php" ;//_Fl00066 financeiro insert checkbox
                break;	
			case ($_keychave == '_Fl00007'):	
				$_ret  = "../../api/view/financeiro/finanCategoria.php" ;//_Fl00008 financeiro busca subcategoria
				break;	
			case ($_keychave == '_Fl00008'):	
				$_ret  = "../../api/view/financeiro/finanSubcategoria.php" ;//_Fl00008 financeiro busca subcategoria
				break;	
			case ($_keychave == 'ADREGFIN1'):	
				$_ret  = "../../api/view/financeiro/acaoFinanceiro.php" ;//ADREGFIN1 - insere registro no financeiro via classe
				break;	
			case ($_keychave == 'ALREGFIN1'):	
				$_ret  = "../../api/view/financeiro/alteraFinanceiro.php" ;//ADREGFIN1 - ALTERA REGISTRO FINANCEIRO VIA CLASSE
				break;	
			case ($_keychave == 'ALT1FINAN'):	
				$_ret  = "../../api/view/financeiro/modalAlterar.php" ;//ALT1FINAN - ABRE MODAL PARA ALTERAR REGISTRO FINANCEIRO
				break;	
			case ($_keychave == '_Fl00009'):	
				$_ret  = "../../api/view/financeiro/tipoQuem.php" ;//ALT1FINAN - ABRE MODAL PARA ALTERAR REGISTRO FINANCEIRO
				break;		
			case ($_keychave == '_Fl00010'):	
				$_ret  = "../../api/view/financeiro/finanSubcategoria2.php" ;//_Fl00008 pesquisa subcategoria financeiro
				break;
			case ($_keychave == '_Fl00011'):	
				$_ret  = "../../api/view/financeiro/finanCategoria2.php" ;//_Fl00008 pesquisa categoria por tipo receita
				break;
            case ($_keychave == '_Fl00012'):	
                $_ret  = "../../api/view/financeiro/finanResumoCx.php" ;//_Fl00012 tela resumo caixa 
                break;	    
            case ($_keychave == '_Fl00013'):	
                    $_ret  = "../../api/view/financeiro/finanResumoCx00001_list.php" ;//_Fl00013  tela resumo caixa  List
                 break; 
           case ($_keychave == '_Fl00014'):	
                    $_ret  = "../../api/view/financeiro/finanRecebiveis.php" ;//_Fl00014 tela recebiveis cartoes
                 break;          	                 
           case ($_keychave == '_Fl00015'):	
            $_ret  = "../../api/view/financeiro/finanRecebiveis_acao.php" ;//_Fl00014  tela recebiveis cartoes  List
                break;                 
            case ($_keychave == '_Fl00016'):	
                   $_ret  = "../../api/view/financeiro/acaorecebivel.php" ;//_Fl00016  recebiveis cartao
                   break; 
            case ($_keychave == '_Fl00017'):	
                   $_ret  = "../../api/view/financeiro/fechamentofin.php" ;//_Fl00017 Fechamento Financeiro
            break;            
            case ($_keychave == '_Fl00018'):	
                $_ret  = "../../api/view/financeiro/fechamentofinan_list.php" ;//_Fl00017 Fechamento Financeiro
            break;
            case ($_keychave == '_Fl00019'):	
                $_ret  = "../../api/view/financeiro/acaofechamentofinan.php" ;//_Fl00017 Fechamento Financeiro
             break;

             case ($_keychave == '_Fl00020'):	
                $_ret  = "../../api/view/financeiro/relatorios.php" ;//_Fl00020 Relatorios Financeiro
             break;

             case ($_keychave == '_Fl00021'):	
                $_ret  = "../../api/view/financeiro/rptrelatorios.php" ;//_Fl00020 impressao Relatorios Financeiro
             break;

            case ($_keychave == 'f_00002'):	
                $_ret  = "../../api/view/financeiro/finanExtrato.php" ;//f_00002 tela extrato comissao serviços e vendas - menu financeiro
                break;	    
            case ($_keychave == 'f_00003'):	
                $_ret  = "../../api/view/financeiro/finanExtrato00001_list.php" ;//f_00002 tela extrato comissao serviços e vendas - menu financeiro
                break;             
          
            case ($_keychave == 'ACNTFCE'):	
                $_ret  = "../../api/view/financeiro/acaoNotaFiscal.php";
                break; 

            case ($_keychave == 'NTFCEPR'):	
                    $_ret  = "../../api/view/financeiro/viewer_notaFiscal.php";
                break;     
 //*** CLIENTES
            case ($_keychave == 'cli_00001'):	
                $_ret  = "../api/view/atendimento/OSconsultaMob.php" ;
                break;	          
 
            case ($_keychave == 'cli_00011'):	
                $_ret  = "../../api/view/atendimento/prontuario.php" ;
                break;	
            
            case ($_keychave == 'rel_00001'):	
                $_ret  = "../../api/view/atendimento/acaoRelatorios.php" ;
                break;	    
                
           

//*** SERVIÇOS e atndimento

            case ($_keychave == '1'):	
                $_ret  = "../../api/view/servicos/rptordemservicoService.php";//__relatorio OS
                break;
            case ($_keychave == '2'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoMatr.php";//__relatorio OS
                break;    
            case ($_keychave == '3'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoAtivo.php";//__relatorio OS
                    break;    
            case ($_keychave == '4'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoLaudo.php";//__relatorio OS
                    break;
            case ($_keychave == '5'):	
                $_ret  = "../../api/view/servicos/rptordemservicoMatr.php";
                break;
            case ($_keychave == '6'):	
                $_ret  = "../../api/view/servicos/rptordemservicoMatr.php";
                break;    
            case ($_keychave == '7'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoMatr.php";
                    break; 
            case ($_keychave == '8'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoRecibo.php";
                    break;
            case ($_keychave == '9'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoOrcamento.php";
                   break;
            case ($_keychave == '10'):	
                   $_ret  = "../../api/view/servicos/rptordemservicoMatr2.php";
                  break;
            case ($_keychave == '11'):	
                  $_ret  = "../../api/view/servicos/rptordemservicoServiceA.php";
            case ($_keychave == '12'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoMatr3.php";
                    break;
            case ($_keychave == '14'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoServiceB.php";
                    break;
            case ($_keychave == '15'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoProtEntrega.php";
                    break;
            case ($_keychave == '16'):	
                   $_ret  = "../../api/view/servicos/rptordemservicoTermoR.php";
                    break;
            case ($_keychave == '51'):	
                   $_ret  = "../../api/view/servicos/rptTermicoOS.php";
                   break;
            case ($_keychave == '52'):	
                 $_ret  = "../../api/view/servicos/rptordemservicoServiceC.php";
                break;
            case ($_keychave == '53'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoServiceD.php";
                     break;
            case ($_keychave == '54'):	
                     $_ret  = "../../api/view/servicos/rptordemservicoServiceD.php";
                     break;              
            case ($_keychave == '55'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoServiceD.php";
                     break;   
            case ($_keychave == '56'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoServiceE.php";
                     break; 
            case ($_keychave == '57'):	
                    $_ret  = "../../api/view/servicos/rptordemservicoServiceF.php";
                    break;                            
                                  
                
            case ($_keychave == 'rptOS'):	
                        $_ret  = "../../api/view/servicos/rptFicha.php";
                         break;
            case ($_keychave == 'rptOSarquivo'):	
                        $_ret  = "../../api/view/servicos/rptArquivo.php";
                        break;
            case ($_keychave == '999'):	
                   $_ret  = "../../api/view/servicos/rpt_tabela.php";
                    break;
            case ($_keychave == 'SRVC'):	
                   $_ret  = "../../api/view/servicos/servicos.php" ;
                   break;	
            case ($_keychave == 'ACSRVC'):	
                  $_ret  = "../../api/view/servicos/acaoServicos.php";
                  break;
            case ($_keychave == 'S00001'):	
                    $_ret  = "../../api/view/servicos/frmordemservicoEncerramentoF.php";
                    break;
            case ($_keychave == 'S00002'):	
                   $_ret  = "../../api/view/servicos/servicos00001.php";
                   break;
            case ($_keychave == 'S00003'):	
                  $_ret  = "../../api/view/servicos/servicos00001_list.php";
                   break; 
            case ($_keychave == 'S00004'):	
                   $_ret  = "../../api/view/servicos/servicos00002_list.php";
                   break;  
            case ($_keychave == 'S00005'):	
                   $_ret  = "../../api/view/servicos/cliente.php";
                   break;
            case ($_keychave == 'S00006'):	
                  $_ret  = "../../api/view/servicos/clientelista.php";
                  break;  
            case ($_keychave == 'S00007'):	
                   $_ret  = "../../api/view/servicos/roteiro.php";
                        break;
            case ($_keychave == 'S00008'):	
                        $_ret  = "../../api/view/servicos/roteirolista.php";
                        break; 
            case ($_keychave == 'S00008a'):	
                        $_ret  = "../../api/view/servicos/roteirolistaOficina.php";
                            break;             
            case ($_keychave == 'S00009'):	
                        $_ret  = "../../api/view/servicos/acao_buscaitem.php";
                        break;  
            case ($_keychave == 'S00099'):	
                        $_ret  = "../../api/view/servicos/acao_buscaitemTrackMob.php";
                        break;            
            case ($_keychave == 'S00010'):	
                        $_ret  = "../../api/view/servicos/acao_acompanhamento.php";
                         break;  
            case ($_keychave == 'S00011'):	
                        $_ret  = "../../api/view/servicos/acao_os.php";
                        break;
            case ($_keychave == 'S00012'):	
                        $_ret  = "../../api/view/servicos/acao_modelo.php";
                        break; 
            case ($_keychave == 'S00013'):	
                        $_ret  = "../../api/view/servicos/rptRoteiro.php";
                        break; 
            case ($_keychave == 'S00013a'):	
                            $_ret  = "../../api/view/servicos/rptRoteiroOficina.php";
                        break;   
            case ($_keychave == 'S00913'):	
                        $_ret  = "../../api/view/servicos/rptConsulta.php";
                        break;              
            case ($_keychave == 'S00014'):	
                        $_ret  = "../../api/view/servicos/acao_almox.php";
                        break;
            case ($_keychave == 'S00015'):	
                        $_ret  = "../../api/view/servicos/servicos00001tec.php";
                        break;
            case ($_keychave == 'S00016'):	
                        $_ret  = "../../api/view/servicos/servicos00001_listTec.php";
                        break; 
            case ($_keychave == 'S00017'):	
                        $_ret  = "../../api/view/servicos/roteirotec.php";
                        break; 
           case ($_keychave == 'S00018'):	
                        $_ret  = "../../api/view/servicos/acao_tecnicosel.php";
                        break; 
            case ($_keychave == 'S00019'):	
                        $_ret  = "../../api/view/servicos/roteirolistatec.php";
                         break; 
            case ($_keychave == 'S00020'):	
                        $_ret  = "../../api/view/servicos/mapa.php";
                        break;                                                                                               
            case ($_keychave == 'S00021'):	
                        $_ret  = "../../api/view/servicos/mapalista.php";
                        break;
            case ($_keychave == 'S00022'):	
                        $_ret  = "../../api/view/servicos/paineloficina.php";
                        break;                                                                                               
            case ($_keychave == 'S00023'):	
                        $_ret  = "../../api/view/servicos/paineloficinalista.php";
                        break;
            case ($_keychave == 'S00024'):	
                        $_ret  = "../../api/view/servicos/acao_whats.php";
                        break;      
            case ($_keychave == 'S00025'):	
                        $_ret  = "../../api/view/acesso/acao_log.php";
                        break;              
            case ($_keychave == 'S00026'):	
                        $_ret  = "../../api/view/servicos/acao_gerarnfce.php";
                        break; 
            case ($_keychave == 'S00027'):	
                        $_ret  = "../../api/view/servicos/acao_gerarcsv.php";
                       break;   
            case ($_keychave == 'S00027a'):	
                       $_ret  = "../../api/view/servicos/acao_gerarcsvoficina.php";
                       break;              
            case ($_keychave == 'S00028'):	
                      $_ret  = "../../api/view/servicos/acao_roteiro.php";
                      break;  
            case ($_keychave == 'S00029'):	
                     $_ret  = "../../api/view/servicos/acao_medicao.php"; // garantia extendida
                     break; 
            case ($_keychave == 'S00030'):	
                     $_ret  = "../../api/view/servicos/acao_relempresa.php";
                      break; 
            case ($_keychave == 'S00031'):	
                     $_ret  = "../../api/view/servicos/acao_sos_fabricante.php";
                      break; 
            case ($_keychave == 'S00109'):	
                   $_ret  = "../../api/view/servicos/acao_editargeral.php";
                   break;                      
            case ($_keychave == 'RLserv'):
                        $_ret  = "../../api/view/servicos/relatorios.php";
                      break;   
            case ($_keychave == 'SERVICORL'):
                      $_ret  =
                       "../../api/view/servicos/acaoRelatorios.php";
                      break; 
            case ($_keychave == 'OSIMPORT'):
                $_ret  = "../../api/view/servicos/importOS.php"; 
                break;
            case ($_keychave == 'OSIMPORT001'):
                $_ret  = "../../api/view/servicos/acaoimportOS.php"; 
                break;    
    
                                                                    
//*** VENDAS
            
            case ($_keychave == '_PDV00001'):	
                $_ret  = "cpmNaoFiscal.php" ;// cupom nao fiscal
                break;
            case ($_keychave == '_PDV00002'):	
                $_ret  = "cpmFiscal.php" ;// cupom  fiscal
                break;
            case ($_keychave == '_PDV00022'):	                
                $_ret  = "../../api/view/servicos/cpmFiscalOS.php";//_nfce os financeiro
                break;     
                 
            case ($_keychave == '_PDV000022'):	
                 $_ret  = "../../api/view/financeiro/cpmFiscal.php" ;// cupom  fiscal
                 break;
            case ($_keychave == '_PDV00003'):	
                 $_ret  = "acaoCaixa.php" ;// cupom nao fiscal
                 break;
            case ($_keychave == '_PDV00004'):	
                    $_ret  = "rptcompleto.php" ;// pedido completo
                break;     
            case ($_keychave == '_PDV00005'):	
                    $_ret  = "rptorcamento.php" ;// orçamento
                    break;    
           case ($_keychave == '_PDV00006'):	
                  $_ret  = "rptpedido.php" ;// pedido
                  break;  
			case ($_keychave == '_Vl00003'):	
				$_ret  = "../../api/view/vendas/vendas00001.php" ;//_Vl00003 vendas                       
				break;		
			case ($_keychave == '_Vc00004'):	
				$_ret  = "../../api/view/vendas/vendas00001_list.php" ;//_Vc00004 Consultar Venda lista
				break;	
			case ($_keychave == '_Vc00005'):	
					$_ret  = "../../api/view/vendas/vendas00002_list.php" ;//_Vc00004 Consultar cliente lista
					break;									
			case ($_keychave == '_Vc00006'):	
					$_ret  = "../../api/view/vendas/vendas00003_endereco.php" ;//_Vc00006 cadastro endereco
					break;	
		
			case ($_keychave == '_Vc00007'):	
					$_ret  = "../../api/view/vendas/vendas00004_cadastro.php" ;//_Vc00007 cadastro dados iniciais
					break;	
					
            case ($_keychave == '_Vc00009'):	
                    $_ret  = "../../api/view/vendas/vendas00005_gravar.php" ;//_Vc00009 finalizar cadastro
                    break;	
                        
            case ($_keychave == '_Vc00010'):	
                    $_ret  = "../../api/view/vendas/vendas000010_pedido.php" ;//_Vc00010 pedido
                    break;
            
            case ($_keychave == '_Vc00011'):	
                    $_ret  = "../../api/view/vendas/vendas000010_pedidolist.php" ;//_Vc00010 pedido
                    break;
                    
            case ($_keychave == '_Vc00012'):	
                    $_ret  = "../../api/view/vendas/vendas_produto.php" ;//_Vc00012 pedido - produtos
                    break;        
           
            case ($_keychave == '_Vc00013'):	
                $_ret  = "../../api/view/vendas/vendas_acaoproduto.php" ;//_Vc00013 pedido - produto busca
                    break;	
                
            case ($_keychave == '_Vc00014'):	
                    $_ret  = "../../api/view/vendas/vendas_resumopgto.php" ;//_Vc00014 pgto pedido (vendas_acaopgto.php)- alterado para resumo
                     break;		

            case ($_keychave == '_Vc00015'):	
                    $_ret  = "../../api/view/vendas/vendas_servicos.php" ;//_Vc00015 pedido servicos
                    break;
                    
            case ($_keychave == '_Vc00016'):	
                    $_ret  = "../../api/view/vendas/vendas_acaoservico.php" ;//_Vc00016 pedido - acao servicos
                    break;		        
          
            case ($_keychave == '_Vc00017'):	
                   $_ret  = "../../api/view/vendas/vendas_ProdutoServico.php" ;//_Vc00017 pedido - _busca Produto e Servicos
                    break;		        
            
            case ($_keychave == '_Vc00018'):	
                  $_ret  = "../../api/view/vendas/vendas_acaofinalizar.php" ;//_Vc00018 finalizar pedido
                   break; 
                   
            case ($_keychave == '_Vc00019'):	
                  $_ret  = "../../api/view/vendas/vendas_acaoavaliacao.php" ;//_Vc00019 pedido - avalicao
                  break;                  
            case ($_keychave == '_Vc00020'):	
                    $_ret  = "../../api/view/vendas/vendas_pets.php" ;//_Vc00020 pedido - pets
                    break;
            case ($_keychave == '_Vc00021'):
                    $_ret  = "../../api/view/vendas/relatoriosVendas.php";
                    break; 
           case ($_keychave == '_Vc00022'):
                    $_ret  = "../../api/view/vendas/rptrelatorios.php";
                    break; 
            case ($_keychave == '_Nc00004'):	
                $_ret  = "../../api/view/vendas/cliente.php" ;//
            break;
            case ($_keychave == 'cliente_00001'):	
                $_ret  = "../../api/view/vendas/acaocliente.php" ;//
            break;
            case ($_keychave == 'cliente_00002'):	
                $_ret  = "../../api/view/vendas/cadastrocliente.php" ;//
            break;
            case ($_keychave == 'plano_00001'):	
                $_ret  = "../../api/view/vendas/plano.php" ;//
            break;
            case ($_keychave == 'plano_00002'):	
                $_ret  = "../../api/view/vendas/planoAcao.php" ;//
            break;
            case ($_keychave == 'plano_00003'):	
                $_ret  = "../../api/view/vendas/planoCadastro.php" ;//
            break;                
                                 
//*** NAVEGACAO
			case ($_keychave == '_Na00001'):	
				$_ret  = "../../api/view/navegacao/adminstrativo.php" ;//
				break;
			case ($_keychave == '_Nf00002'):	
					$_ret  = "../../api/view/navegacao/financeiro.php" ;//
				break;		
			case ($_keychave == '_Nv00003'):	
					$_ret  = "../../api/view/navegacao/venda.php" ;//
				break;
			case ($_keychave == '_Nc00005'):	
					$_ret  = "../../api/view/navegacao/configuracao.php" ;//
				break;
			case ($_keychave == '_Na00006'):	
				$_ret  = "../../api/view/navegacao/estoque.php" ;//
				break;	
			case ($_keychave == '_Na00007'):	
				$_ret  = "../../api/view/navegacao/servicos.php" ;//
				break;				
			case ($_keychave == '_off00001'):	
					$_ret  = "../../api/view/navegacao/logout.php" ;//
				break;                
//*** ADMINISTRACAO
			case ($_keychave == 'NFENTLT'):
                $_ret  = "../../api/view/administracao/notaEntradaLista.php" ;//
				break;
            case ($_keychave == 'ACNFENTLT'):
                $_ret  = "../../api/view/administracao/acaoNotaEntradaLista.php" ;//
                break;
            case ($_keychave == 'NFENT'):
                $_ret  = "../../api/view/administracao/notaEntrada.php" ;//
                break;
            case ($_keychave == 'ACNFENT'):
                $_ret  = "../../api/view/administracao/acaoNotaEntrada.php" ;//
                break;
            case ($_keychave == 'ACNFENTPR'):
                $_ret  = "../../api/view/administracao/acaoNotaProduto.php" ;//
                break;
            case ($_keychave == 'CAFORNCLT'):
                $_ret  = "../../api/view/administracao/cadastroFornecedorLista.php" ;//
                break;
            case ($_keychave == 'CAFORNC'):
                $_ret  = "../../api/view/administracao/cadastroFornecedor.php" ;//
                break;
            case ($_keychave == 'ACCAFORNC'):
                $_ret  = "../../api/view/administracao/acaoCadastroFornec.php" ;//
                break;
            case ($_keychave == 'NTFCE'):	
                $_ret  = "../../api/view/administracao/nfeLista.php";
                break; 
            case ($_keychave == 'NTFCELT'):	
                $_ret  = "../../api/view/administracao/acaoNotaNfeLista.php";
                 break;               
            case ($_keychave == 'NTFCECLIENTE'):	
                    $_ret  = "../../api/view/administracao/cliente.php";
                    break;   
            case ($_keychave == '_NTFCECLIENTE_00006'):	
                   $_ret  = "../../api/view/administracao/clientelista.php";
                        break;          
            case ($_keychave == '_NTFCECLIENTE_00008'):	
                    $_ret  = "../../api/view/administracao/consumidor_cadastro.php" ;// 
                    break; 
            case ($_keychave == '_NTFCECLIENTE_00009'):	
                    $_ret  = "../../api/view/administracao/nfe.php" ;// NFE                    
                    break;
            case ($_keychave == '_NTFCECLIENTE_00099'):	
                    $_ret  = "../../api/view/administracao/nfse.php" ;// NFSE                    
                    break;
            case ($_keychave == '_NTFCECLIENTE_00010'):	
                    $_ret  = "../../api/view/administracao/acaoNfe.php" ;// 
                    break;   
            case ($_keychave == '_NTFCECLIENTE_00100'):	
                    $_ret  = "../../api/view/administracao/acaoNFeManifesto.php" ;// 
                    break;  
            case ($_keychave == '_NTFCECLIENTE_00101'):	
                    $_ret  = "../../api/view/administracao/nfmdfe.php" ;// NFMDFe                    
                    break; 
            case ($_keychave == '_NTFCECLIENTE_00090'):	
                        $_ret  = "../../api/view/administracao/acaoNfse.php" ;// 
                        break;   
            case ($_keychave == '_NTFCECLIENTE_00011'):	
                    $_ret  = "pdv/rptmanifesto.php" ;// 
                    break; 
            case ($_keychave == '_email001'):	
                    $_ret  = "emailNF.php" ;// 
                    break;  
            case ($_keychave == 'NPS'):	
                      $_ret  = "../../api/view/administracao/nps.php" ;// pesquisa satisfação
                    break; 
                    
            case ($_keychave == 'NPS_0001'):	
                   $_ret  = "../../api/view/administracao/acao_nps.php" ;// pesquisa satisfação
                   break;  
                   
           case ($_keychave == 'REGPONTO'):	
                  $_ret  = "../../api/view/administracao/pontocontrole.php" ;// registro ponto
                   break;          
           case ($_keychave == 'REGPONTO_0001'):	
                   $_ret  = "../../api/view/administracao/pontocontrole_acao.php" ;// acao registro ponto
                   break; 
           case ($_keychave == 'REGPONTO_0002'):	
                    $_ret  = "../../api/view/administracao/pontocontrole_acao.php" ;// acao registro ponto
                    break; 
            case ($_keychave == 'PEDIDO_0001'):	
                   $_ret  = "../../api/view/administracao/pedido.php" ;// pedido
                    break;
            case ($_keychave == 'PEDIDO_0002'):	
                   $_ret  = "../../api/view/administracao/acao_pedido.php" ;// acao pedido
                   break;                

         /*CONFIGURAÇÃO*/
            case ($_keychave == 'cadastro_00001'):
                $_ret  = "../../api/view/configuracao/cadastroEmpresa.php" ;//
                break;
            case ($_keychave == 'CDCLB'):
                $_ret  = "../../api/view/configuracao/cadastroColaborador.php" ;//
                break;
            case ($_keychave == 'ACCDT'):
                $_ret = "../../api/view/configuracao/acaocadastro.php";//
                break;
            case ($_keychave == 'tecnicoLista_00001'):
                $_ret = "../../api/view/configuracao/tecnicoLista.php";//
                break;
            case ($_keychave == 'tecnico_00001'):
                $_ret = "../../api/view/configuracao/tecnico.php";//
                break;
            case ($_keychave == 'acaoTecnico_00001'):
                $_ret = "../../api/view/configuracao/acaotecnico.php";//
                break;
            case ($_keychave == 'grupos_00001'):
                $_ret  = "../../api/view/configuracao/grupo.php" ;//
                break;
            case ($_keychave == 'acaogrupo_0001'):
                $_ret  = "../../api/view/configuracao/acaogrupo.php" ;//
                break;
            case ($_keychave == 'linhas_00001'):
                $_ret  = "../../api/view/configuracao/linha.php" ;//
                break;
			case ($_keychave == 'categoria_00001'):
                $_ret  = "../../api/view/configuracao/categoriaFinanceiro.php" ;//
                break;
			case ($_keychave == 'categoria_00002'):
                $_ret  = "../../api/view/configuracao/acaoCategoria.php" ;//
                break;
            case ($_keychave == 'acaolinha_0001'):
                $_ret  = "../../api/view/configuracao/acaolinha.php" ;//
                break;
            case ($_keychave == 'almoxarifado_00001'):
                $_ret  = "../../api/view/configuracao/almoxarifado.php" ;//
                break;
            case ($_keychave == 'acaoalmoxarifado_0001'):
                $_ret  = "../../api/view/configuracao/acaoalmoxarifado.php" ;//
                break;
            case ($_keychave == 'tipAviso_00001'):
                $_ret  = "../../api/view/configuracao/tipoAviso.php" ;//
                break;
            case ($_keychave == 'acao_tipAviso_0001'):
                $_ret  = "../../api/view/configuracao/acaoaviso.php" ;//
                break;
           case ($_keychave == 'tipfornecedor_00001'):
                $_ret  = "../../api/view/configuracao/tipfornecedor.php" ;//
                break;
            case ($_keychave == 'acaotipofornecedor_0001'):
                $_ret  = "../../api/view/configuracao/acaotipofornecedor.php" ;//
                break;
            case ($_keychave == 'tipocliente_00001'):
                $_ret  = "../../api/view/configuracao/tipocliente.php" ;//
                break;
            case ($_keychave == 'acaotipocliente_0001'):
                $_ret  = "../../api/view/configuracao/acaotipocliente.php" ;//
                break;
            case ($_keychave == 'condicaopagamento_0001'):
                $_ret  = "../../api/view/configuracao/condicoespagamento.php" ;//
                break;
            case ($_keychave == 'acaocondicaopgto_0001'):
                $_ret  = "../../api/view/configuracao/acaocondicaopgto.php" ;//
                break;
            case ($_keychave == 'livrocaixa_0001'):
                $_ret  = "../../api/view/configuracao/livrocaixa.php" ;//
                break;
            case ($_keychave == 'acaolivrocaixa_0001'):
                $_ret  = "../../api/view/configuracao/acaolivro.php" ;//
                break;
            case ($_keychave == 'grupodespesa_0001'):
                $_ret  = "../../api/view/configuracao/grupodespesa.php" ;//
                break;
            case ($_keychave == 'acaogrupodespesa_0001'):
                $_ret  = "../../api/view/configuracao/acaogrupodespesa.php" ;//
                break;
            case ($_keychave == 'despesas_0001'):
                $_ret  = "../../api/view/configuracao/despesas.php" ;//
                break;
            case ($_keychave == 'acaodespesa_0001'):
                $_ret  = "../../api/view/configuracao/acaodespesas.php" ;//
                break;
            case ($_keychave == 'PRCEC'):
                $_ret  = "../../api/view/configuracao/projeto.php" ;//
                break;
            case ($_keychave == 'ACPRCEC'):
                $_ret  = "../../api/view/configuracao/acaoprojeto.php" ;//
                break;
            case ($_keychave == 'ZRES'):
                $_ret  = "../../api/view/configuracao/zeraestoque.php" ;//
                break;
            case ($_keychave == 'ACZRES'):
                $_ret  = "../../api/view/configuracao/acaozeraestoque.php" ;//
                break;
            case ($_keychave == 'EXTA'):
                $_ret  = "../../api/view/configuracao/extraA.php" ;//
                break;
            case ($_keychave == 'ACEXTA'):
                $_ret  = "../../api/view/configuracao/acaoExtraA.php" ;//
                break;
            case ($_keychave == 'EXTB'):
                $_ret  = "../../api/view/configuracao/extraB.php" ;//
                break;
            case ($_keychave == 'ACEXTB'):
                $_ret  = "../../api/view/configuracao/acaoExtraB.php" ;//
                break;
			case ($_keychave == '_C000034'):	
				$_ret  = "../../api/view/configuracao/acaoCategoria.php" ;//_Fl00008 altera subcategoria em configuracao/categoriaFinanceiro.php
			break;	
			case ($_keychave == 'especie_00001'):	
				$_ret  = "../../api/view/configuracao/especie.php" ;//_Fl00008 altera subcategoria em configuracao/categoriaFinanceiro.php
			break;	
			case ($_keychave == 'especie_00002'):	
				$_ret  = "../../api/view/configuracao/acaoEspecie.php" ;//_Fl00008 altera subcategoria em configuracao/categoriaFinanceiro.php
			break;		
			case ($_keychave == 'especie_00003'):	
				$_ret  = "../../api/view/configuracao/acaoEspecie.php" ;// CRIA NOVA ESPECIE
			break;	
			case ($_keychave == 'especie_00005'):	
				$_ret  = "../../api/view/configuracao/acaoEspecie.php" ;// ALTERA ESPECIE
			break;	
			case ($_keychave == 'especie_00006'):	
				$_ret  = "../../api/view/configuracao/acaoEspecie.php" ;// ALTERA RACA
			break;	
			case ($_keychave == 'avaliacao_00001'):	
				$_ret  = "../../api/view/configuracao/avaliacao.php" ;// ALTERA RACA
			break;           
			case ($_keychave == 'avaliacao_00002'):	
				$_ret  = "../../api/view/configuracao/acaoAvaliacao.php" ;// ALTERA RACA
			break;
            case ($_keychave == 'endereco_00001'):	
				$_ret  = "../../api/view/configuracao/endereco.php" ;// ENDEREÇOS ESTOQUE
			break;	
            case ($_keychave == 'endereco_00002'):	
				$_ret  = "../../api/view/configuracao/acaoendereco.php" ;// ENDEREÇOS ESTOQUE
			break;
            case ($_keychave == 'permissao_00001'):
                $_ret = "../../api/view/configuracao/permissao.php";//
            break;		
            case ($_keychave == 'permissao_00002'):
                $_ret = "../../api/view/configuracao/acaoPermissao.php";//
            break;	
            case ($_keychave == 'colaboradores_00001'):
                $_ret = "../../api/view/configuracao/colaboradores.php";//
            break;	
            case ($_keychave == 'colaboradores_00002'):
                $_ret = "../../api/view/configuracao/acao_colaboradores.php";//
            break;
            case ($_keychave == 'colaboradores_00003'):
                $_ret = "../../api/view/configuracao/dados_colaborador.php";//
            break;
            case ($_keychave == 'receita_00001'):
                $_ret = "../../api/view/configuracao/receita.php";//
            break;
            case ($_keychave == 'receita_00002'):
                $_ret = "../../api/view/configuracao/acaoReceita.php";//
            break;
            case ($_keychave == 'whats_00001'):
                $_ret = "../../api/view/configuracao/mensagemwhats.php";//
            break;
            case ($_keychave == 'whats_00002'):
                $_ret = "../../api/view/configuracao/acaowhats.php";//
            break;
            case ($_keychave == 'regiao_00001'):
                $_ret  = "../../api/view/configuracao/regiao.php" ;//
                break;
            case ($_keychave == 'acaoregiao_0001'):
                $_ret  = "../../api/view/configuracao/acaoregiao.php" ;//
                break;
            case ($_keychave == 'modelo_00001'):
                $_ret = "../../api/view/configuracao/modelosaparelho.php";//
                break;	
            case ($_keychave == 'modelo_00002'):
                $_ret = "../../api/view/configuracao/acao_modelosaparelho.php";//
                break;
            case ($_keychave == 'produto_00001'):
                    $_ret = "../../api/view/configuracao/produtoaparelho.php";//
                    break;
            case ($_keychave == 'produto_00002'):
                    $_ret = "../../api/view/configuracao/acaoProduto.php";//
                    break;
            case ($_keychave == 'logaceso_00001'):
                    $_ret = "../../api/view/configuracao/logacesso.php";//
                  break;
            case ($_keychave == 'logaceso_00002'):
                   $_ret = "../../api/view/configuracao/acao_logacesso.php";//
                 break;
            case ($_keychave == 'estcomp_00001'):
                    $_ret = "../../api/view/configuracao/EstoqueCompartilhado.php";//
                    break;
            case ($_keychave == 'estcomp_00002'):
                   $_ret = "../../api/view/configuracao/acaoEstoqueCompartilhado.php";//
                   break; 
            
            case ($_keychave == 'sit00001'):
                    $_ret = "../../api/view/configuracao/situacao_atendimento.php";//
                    break;
            case ($_keychave == 'sit00002'):
                   $_ret = "../../api/view/configuracao/acao_situacaoatendimento.php";//
                   break;  
            
            case ($_keychave == 'sitOf00001'):
                    $_ret = "../../api/view/configuracao/situacaodeoficina.php";//
                    break;
            case ($_keychave == 'sitOf00002'):
                   $_ret = "../../api/view/configuracao/acao_situacaodeoficina.php";//
                   break;  
                   
            case ($_keychave == 'cfop00001'):
                    $_ret = "../../api/view/configuracao/situacao_cfop.php";//
                    break;
            case ($_keychave == 'cfop00002'):
                   $_ret = "../../api/view/configuracao/acao_cfop.php";//
                   break;   
                   
            case ($_keychave == 'cust00001'):
                    $_ret = "../../api/view/configuracao/customizacao.php";//
                    break;
            case ($_keychave == 'cust00002'):
                   $_ret = "../../api/view/configuracao/acao_customizacao.php";//
                   break; 

            case ($_keychave == 'tpgar00001'):
                    $_ret = "../../api/view/configuracao/tipogarantia.php";//
                    break;
            case ($_keychave == 'tpgar00002'):
                   $_ret = "../../api/view/configuracao/acao_tipogarantia.php";//
                   break; 
            case ($_keychave == 'bk_00001'):
                   $_ret = "../../api/view/configuracao/backuplist.php";//
                   break; 

            case ($_keychave == 'bk_00002'):
                   $_ret = "../../api/view/outros/rotinasgeral.php";//
                   break; 
 
                   
                

/*CONFIGURAÇÃO PAG 3*/             
            case ($_keychave == 'benf_00001'):
                $_ret  = "../../api/view/configuracao/finpetbeneficiarios.php" ;// beneficiarios finpet
            break;
            case ($_keychave == 'pgto_00001'):
                $_ret  = "../../api/view/configuracao/finpetcartoes.php" ;// bandeiras finpet
            break;  
             
            case ($_keychave == 'terminal_00001'):
                $_ret  = "../../api/view/configuracao/finpetterminal.php" ;// terminal finpet
            break;
            case ($_keychave == 'finpet_action'):
                $_ret  = "../../api/view/configuracao/acaofinpet.php" ;// terminal finpet
            break;
            
/*OUTROS*/
			case ($_keychave == '_Am00003'):	
				$_ret  = "../../api/view/outros/treinamentos.php" ;// lista treinamento
			break;	
            case ($_keychave == '_Am00004'):	
				$_ret  = "../../api/view/outros/treinamentosacao.php" ;// lista treinamento
			break;	
			case ($_keychave == '_T001'):	
				$_ret  = "../../api/view/outros/lista_treinamentos.php" ;// lista treinamento
			break;	
			case ($_keychave == '_T002'):	
				$_ret  = "../../api/view/outros/TreinamentoView.php" ;// lista treinamento
			break;	
            case ($_keychave == 'email_000001'):	
				$_ret  = "email_site.php" ;// EMAIL =====!
			break;
         
            case ($_keychave == 'email_000002'):	
				$_ret  = "../api/view/outros/email.php" ;// EMAIL RECUPERAR SENHA=====!
			break;
            case ($_keychave == 'doc_000001'):	
				$_ret  = "../../api/view/outros/linkanexo.php" ;// link baixar anexo e documento
			break;

            case ($_keychave == 'qr00001'):
                $_ret  = "../../api/view/outros/link_qrcode.php";
                break;       
        
          //PORTAL CLIENTE
          case ($_keychave == 'P00001'):	
            $_ret  = "../api/view/outros/portal_acao.php" ;// 
            break;
       
          

/*GERENCIAL*/
			case ($_keychave == '_G001'):	
				$_ret  = "../../api/view/gerencial/manual/manual.php" ;// ENTRA NA PAGE MANUAL
			break;	
			case ($_keychave == '_G002'):	
				$_ret  = "../../api/view/gerencial/manual/manualTopico.php" ;// ABRE TELA PARA CRIAR NOVO TOPICO MANUAL
			break;	
			case ($_keychave == '_G003'):	
				$_ret  = "documentos/Manual/manual_acao.php" ;// ABRE TELA PARA CRIAR NOVO TOPICO MANUAL
			break;
            case ($_keychave == '_G1000'):	
				$_ret  = "../../api/view/gerencial/saldo/saldo.php" ;// ABRE TELA DE COMPROVANTE DA EMPRESA
			break;
            case ($_keychave == '_G1001'):	
				$_ret  = "../../api/view/gerencial/saldo/acaoSaldo.php" ;// FUNCOES GERAIS DA TELA SALDO
			break;            
//*** site
            case ($_keychave == '_S00001A'):	
                $_ret  = "../api/view/outros/simulacao_site.php" ;// ABRE TELA PARA CRIAR NOVO TOPICO MANUAL
            break;
            case ($_keychave == '_S00002A'):	
                $_ret  = "../api/view/outros/verifica_codigo.php" ;// ABRE TELA PARA CRIAR NOVO TOPICO MANUAL
            break;


//** resumo */   
            case ($_keychave == 'R0001'):	
                $_ret  = "../../api/view/outros/dashboardger.php" ;// dashboard gerencial
            break;  
            case ($_keychave == 'R00002'):	
                $_ret  = "../../api/view/outros/resumoRoteiro.php" ;// RESUMO DO ROTEIRO
            break;          
		}
		return $_ret;
	}
}