<?php

namespace Functions;

use stdClass;
use Database\MySQL;

class Vendas {

  public static function consultarvendasfinanceiro(array $params){
	
		//$response = new \stdClass;
		$response = "";
	//	$quiz_ret = array();

        try{

            if($params['_dataIni'] == ""){
		      		$params['_dataIni'] = date('Y-m-d');
	      		}
			      if($params['_dataFim'] == ""){
				      $params['_dataFim'] = date('Y-m-d');
            }
            
            if($params['_pedido'] != ""){
              $fil = "and NUMERO = '".$params['_pedido']."' 	";
            }

            if($params['_situacao'] != ""){
              $fil .= "and saidaestoque.Cod_Situacao = '".$params['_situacao']."' 	";
            }
                  
            if($params['_vendedor'] != ""){
              $fil .= "and COD_Vendedor = '".$params['_vendedor']."' 	";
            }  

         
		
            $pdo = MySQL::acessabd();	
            
            $SQL = "SELECT *,
                    DATE_FORMAT(saidaestoque.DATA_CADASTRO,'%d/%m/%Y') AS DTCADASTRO                   
                    FROM  ".$params['_bd'].".saidaestoque 
                    LEFT  JOIN ".$params['_bd'].".consumidor ON consumidor.CODIGO_CONSUMIDOR =  saidaestoque.CODIGO_CLIENTE
                    LEFT  JOIN ".$params['_bd'].".pets ON pets.pets_id =  saidaestoque.CODIGO_PET
                    LEFT JOIN " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = COD_Vendedor
                    LEFT JOIN ".$params['_bd'].".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao                 
                    WHERE  saidaestoque.Cod_Situacao = '93' AND saidaestoque.DATA_CADASTRO BETWEEN '".$params['_dataIni']."' AND '".$params['_dataFim']."  ' 
                    $fil order by  NUMERO DESC
                   ";			
               
					$stm = $pdo->prepare("$SQL");                   
             
                    //$stm->bindParam(1,$id_buscar, \PDO::PARAM_INT);	
                    $stm->execute();	
                 
			        if ( $stm->rowCount() > 0 ){
                        $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
					}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }   
  
    public static function consultarvendas(array $params){
	
		//$response = new \stdClass;
		$response = "";
	//	$quiz_ret = array();

        try{
            if($params['_dataIni'] == ""){
		      		$params['_dataIni'] = date('Y-m-d');
           
	      		}
			      if($params['_dataFim'] == ""){
				      $params['_dataFim'] = date('Y-m-d');
            }
            
			if($params['_pedido'] != ""){
				$fil = "and NUMERO = '".$params['_pedido']."' 	";
        $filor = "OR NUMERO = '".$params['_pedido']."' 	";
      }
      if($params['_nf'] != ""){
				$fil = $fil."and SAIDA_NFE = '".$params['_nf']."' 	";
        $filor =  $filor."OR SAIDA_NFE = '".$params['_nf']."' 	";
      }

      if($params['_situacao'] != ""){
				$fil .= "and saidaestoque.Cod_Situacao = '".$params['_situacao']."' 	";
      }else{
        $fil .= "and saidaestoque.Cod_Situacao <> '99' 	";
      }

      if($params['_status'] != ""){
				$fil .= "and saidaestoque.se_status = '".$params['_status']."' 	";
      }
      
            
      if($params['_vendedor'] != ""){
        $fil .= "and COD_Vendedor = '".$params['_vendedor']."' 	";
       }  

       if($params['_nomeclivend'] != ""){
         $fil .= "and CLIENTE like '%".$params['_nomeclivend']."%'";
       }  

       if($params['_idcliente'] != ""){
         $fil .= "and CODIGO_CLIENTE = '".$params['_idcliente']."'";
      }  

      

       if($params['_ppor']  == 'F' ) { 
        $dtpesquisa = "saidaestoque.Data_Financeiro";
       }else {
        $dtpesquisa = "saidaestoque.DATA_CADASTRO";
       
       }
       

     		
            $pdo = MySQL::acessabd();	
            
            $SQL = "SELECT *,
                    DATE_FORMAT(saidaestoque.DATA_CADASTRO,'%d/%m/%Y') AS DTCADASTRO ,
                    DATE_FORMAT(saidaestoque.Data_Financeiro,'%d/%m/%Y') AS DTPGTO                                     
                    FROM  " . $_SESSION['BASE'] . ".saidaestoque 
                    LEFT  JOIN ".$params['_bd'].".consumidor ON consumidor.CODIGO_CONSUMIDOR =  saidaestoque.CODIGO_CLIENTE
                    LEFT JOIN " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = COD_Vendedor
                    LEFT JOIN " . $_SESSION['BASE'] . ".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao    
                    LEFT JOIN " . $_SESSION['BASE'] . ".statusvenda ON stavenda_id = saidaestoque.se_status                 
                    WHERE $dtpesquisa BETWEEN '".$params['_dataIni']."' AND '".$params['_dataFim']."  ' 
                    $fil $filor order by NUMERO DESC
                   ";
             
               
					$stm = $pdo->prepare("$SQL");                   
             
                    //$stm->bindParam(1,$id_buscar, \PDO::PARAM_INT);	
                    $stm->execute();	
                 
			        if ( $stm->rowCount() > 0 ){
                        $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
					}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }    

    public static function consultarvendasCliente(array $params){
	
      $response = "";   
  
          try{       
      
           $fil = " CODIGO_CLIENTE = '".$params['_idcliente']."'";
           
              $pdo = MySQL::acessabd();	
              
              $SQL = "SELECT *,
                      DATE_FORMAT(saidaestoque.DATA_CADASTRO,'%d/%m/%Y') AS DTCADASTRO ,
                      DATE_FORMAT(saidaestoque.Data_Financeiro,'%d/%m/%Y') AS DTPGTO                                     
                      FROM  " . $_SESSION['BASE'] . ".saidaestoque 
                      LEFT  JOIN ".$params['_bd'].".consumidor ON consumidor.CODIGO_CONSUMIDOR =  saidaestoque.CODIGO_CLIENTE
                      LEFT JOIN " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = COD_Vendedor
                      LEFT JOIN " . $_SESSION['BASE'] . ".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao    
                      LEFT JOIN " . $_SESSION['BASE'] . ".statusvenda ON stavenda_id = saidaestoque.se_status                 
                      WHERE  $fil order by NUMERO DESC
                     ";
                   
               
                 
            $stm = $pdo->prepare("$SQL");                   
               
                      //$stm->bindParam(1,$id_buscar, \PDO::PARAM_INT);	
                      $stm->execute();	
                   
                if ( $stm->rowCount() > 0 ){
                          $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
            }
  
          }
          catch (\Exception $fault){
                          $response = $fault;
          }
          return $response;
      }    
  


    public static function consultarclientes(array $params){
	
		//$response = new \stdClass;
		$response = "";
    //	$quiz_ret = array();
   // print_r( $params);

        try{           
		
            $pdo = MySQL::acessabd();	

            if($params['_desc'] == ""){
              $SQL = "SELECT *               
              FROM  ".$params['_bd'].".consumidor	 
              WHERE Nome_Consumidor like '%Consumidor%'";		
            }else{
              $SQL = "SELECT *               
              FROM  ".$params['_bd'].".consumidor	 
              WHERE Nome_Consumidor like '%".$params['_desc']."%' OR
              Nome_Rua like '%".$params['_desc']."%' OR 
              FONE_CELULAR like '%".$params['_desc']."%' ";		
            }

         
         	
					$stm = $pdo->prepare("$SQL");                   
              //   echo  $SQL;
                    //$stm->bindParam(1,$id_buscar, \PDO::PARAM_INT);	
                    $stm->execute();	
                 
			        if ( $stm->rowCount() > 0 ){
                        $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
					}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }   

    
    public static function carregarCliente(array $params){
	
		$response = "";
        if($params['_idcliente']!= "" ){
            try{           
          
                $pdo = MySQL::acessabd();	            
                $SQL = "SELECT *               
                        FROM  ".$params['_bd'].".consumidor	 
                        WHERE CODIGO_CONSUMIDOR = '".$params['_idcliente']."' ";			
                $stm = $pdo->prepare("$SQL");                   
                $stm->execute();	
                    
              if ( $stm->rowCount() > 0 ){
                            $response =  $stm->fetch(\PDO::FETCH_OBJ);
              }

            }
            catch (\Exception $fault){
                            $response = $fault;
            }
            return $response;

          }
    }   
    
    
    public static function IncluirClientes(array $params){
	
		//$response = new \stdClass;
		$response = "";
    //	$quiz_ret = array();
   //  print_r($params);
   
        try{  
              
         

          $_RetCelular = explode(")",$params['_fonecelular']);
         
          $_celular = str_replace("-", "",$_RetCelular[1]); 
          $_dddcelular = str_replace("(", "", $_RetCelular[0]); 
      

          $_RetFixo = explode(")",$params['_fonefixo']);
          $_fixo = str_replace("-", "",$_RetFixo[1]); 
          $_dddfixo = str_replace("(", "", $_RetFixo[0]); 
       
          
           $pdo = MySQL::acessabd();	
            
            $SQL = "INSERT INTO  ".$params['_bd'].".consumidor  (Nome_Consumidor,
                                Nome_Fantasia,                                
                                Tipo_Pessoa,
                                CGC_CPF,
                                INSCR_ESTADUAL,
                                con_sexo,
                                FONE_CELULAR,
                                FONE_COMERCIAL,                               
                                EMail,
                                data_nascimento,
                                TIPO_CLIENTE,
                                DDD_CELULAR,
                                DDD_FIXO,
                                Data_Cadastro
                                ) VALUES (
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,                                
                                ?,
                                ?,
                                ?,
                                ?,
                                CURRENT_DATE()
                                 ) ";
                                   
                 
                                 $stm = $pdo->prepare("$SQL");    
                                 $stm->bindParam(1,$params['_nome'], \PDO::PARAM_STR );  // Nome_Consumidor, 
                                 $stm->bindParam(2,$params['_nomefantasia'], \PDO::PARAM_STR ); //Nome_Fantasia,                                 
                                 $stm->bindParam(3,$params['_tipopessoa'], \PDO::PARAM_INT);//Tipo_Pessoa, 
                                 $stm->bindParam(4,$params['_cpfcnpj'], \PDO::PARAM_STR );//CPF_CNPJ,  
                                 $stm->bindParam(5,$params['_inscricao'], \PDO::PARAM_STR );//INSCR_ESTADUAL, 
                                 $stm->bindParam(6,$params['_sexo'], \PDO::PARAM_INT);//con_sexo,  
                                 $stm->bindParam(7,$_celular, \PDO::PARAM_STR );//FONE_CELULAR,  
                                 $stm->bindParam(8,$_fixo, \PDO::PARAM_STR ); //FONE_COMERCIAL,                                
                                 $stm->bindParam(9,$params['_email'], \PDO::PARAM_STR ); //EMail ,
                                 $stm->bindParam(10,$params['_dtnacimento'], \PDO::PARAM_STR );//Dt_Nascimento,  
                                 $stm->bindParam(11,$params['_tipoconsumidor'], \PDO::PARAM_STR );//TIPO_CLIENTE,  
                                 $stm->bindParam(12,$_dddcelular, \PDO::PARAM_STR );//TIPO_CLIENTE,  
                                 $stm->bindParam(13,$_dddfixo, \PDO::PARAM_STR );//TIPO_CLIENTE,  
                    $stm->execute();	
                    $id = $pdo->lastInsertId();
                 
			        if ( $stm->rowCount() > 0 ){
                        $response =  $id;
					}

        }
        catch (\Exception $fault){
                        $response = $fault;
        }
        return $response;
    }
    public static function AtualizarclientesDados(array $params){
	
      //$response = new \stdClass;
      $response = "";
      //	$quiz_ret = array();
    //print_r($params); 
  
          try{           
      
              $pdo = MySQL::acessabd();	

              $_RetCelular = explode(")",$params['_fonecelular']);
         
              $_celular = str_replace("-", "",$_RetCelular[1]); 
              $_dddcelular = str_replace("(", "", $_RetCelular[0]); 
          
    
              $_RetFixo = explode(")",$params['_fonefixo']);
              $_fixo = str_replace("-", "",$_RetFixo[1]); 
              $_dddfixo = str_replace("(", "", $_RetFixo[0]);
              
              $SQL = "UPDATE ".$params['_bd'].".consumidor SET
                                Nome_Consumidor = ?,
                                Nome_Fantasia= ?,                                
                                Tipo_Pessoa= ?,
                                CGC_CPF= ?,                               
                                con_sexo= ?,
                                FONE_CELULAR= ?,
                                FONE_COMERCIAL= ?,                               
                                EMail= ?,
                                data_nascimento= ?,
                                TIPO_CLIENTE= ?,
                                DDD_CELULAR= ?,
                                DDD_FIXO= ?
                            
                                 WHERE CODIGO_CONSUMIDOR = '".$params['_idcliente']."' ";                              
                   
                                   $stm = $pdo->prepare("$SQL");  
                                   $stm->bindParam(1,$params['_nome'], \PDO::PARAM_STR );
                                   $stm->bindParam(2,$params['_nomefantasia'], \PDO::PARAM_STR );
                                   $stm->bindParam(3,$params['_tipopessoa'], \PDO::PARAM_STR );
                                   $stm->bindParam(4,$params['_cpfcnpj'], \PDO::PARAM_STR );                                 
                                   $stm->bindParam(5,$params['_sexo'], \PDO::PARAM_STR );
                                   $stm->bindParam(6,$_celular, \PDO::PARAM_STR );
                                   $stm->bindParam(7,$_fixo, \PDO::PARAM_STR );
                                   $stm->bindParam(8,$params['_email'], \PDO::PARAM_STR );
                                   $stm->bindParam(9,$params['_dtnacimento'], \PDO::PARAM_STR );
                                   $stm->bindParam(10,$params['_tipopcliente'], \PDO::PARAM_STR );
                                   $stm->bindParam(11,$_dddcelular, \PDO::PARAM_STR );
                                   $stm->bindParam(12,$_dddfixo , \PDO::PARAM_STR );
                                
            
                      $stm->execute();	
                   
                if ( $stm->rowCount() > 0 ){
                          $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
            }
  
          }
          catch (\Exception $fault){
                          $response = $fault;
          }
          return $response;
      }


    public static function AtualizarclientesEndereco(array $params){
	
      //$response = new \stdClass;
      $response = "";
      //	$quiz_ret = array();
    //print_r($params);
 
  
          try{           
      
              $pdo = MySQL::acessabd();	

              $_cep = str_replace("-", "",$params['_cep']);
              $_cep = str_replace(".", "",$_cep);  
              
              $SQL = "UPDATE ".$params['_bd'].".consumidor SET
                                  Nome_Rua = ?, 
                                  Num_Rua = ?,
                                  CIDADE = ?,
                                  BAIRRO = ?,
                                  UF = ?,
                                  CEP = ?,
                                  COMPLEMENTO = ?
                                  WHERE CODIGO_CONSUMIDOR = '".$params['_idcliente']."' ";                              
                   
                                   $stm = $pdo->prepare("$SQL");    
                                   $stm->bindParam(1,$params['_endereco'], \PDO::PARAM_STR );  // Nome_Rua, 
                                   $stm->bindParam(2,$params['_numendereco'], \PDO::PARAM_STR ); //Num_Rua,  
                                   $stm->bindParam(3,$params['_cidade'], \PDO::PARAM_STR );//CIDADE,  
                                   $stm->bindParam(4,$params['_bairro'], \PDO::PARAM_STR );//BAIRRO,  
                                   $stm->bindParam(5,$params['_estado'], \PDO::PARAM_STR );//UF,  
                                   $stm->bindParam(6,$_cep, \PDO::PARAM_STR );//CEP,  
                                   $stm->bindParam(7,$params['_complemento'], \PDO::PARAM_STR );//COMPLEMENTO,             
                                   $stm->execute();	
                   
                if ( $stm->rowCount() > 0 ){
                          $response =  $stm->fetchAll(\PDO::FETCH_OBJ);
            }
  
          }
          catch (\Exception $fault){
                          $response = $fault;
          }
          return $response;
      }

      public static function print_nfe(array $params){
	
        //$response = new \stdClass;
        $response = "";
      //	$quiz_ret = array();
    
            try{
/*
              $pdo = MySQL::acessabd();	            
              $SQL = "SELECT *               
                      FROM  " . $_SESSION['BASE'] . "consumidor	 
                      WHERE CODIGO_CONSUMIDOR = '".$params['_idcliente']."' ";			
              $stm = $pdo->prepare("$SQL");                   
              $stm->execute();	
                  
            if ( $stm->rowCount() > 0 ){
                          $response =  $stm->fetch(\PDO::FETCH_OBJ);
            }
*/
            }catch (\Exception $fault){
                $response = $fault;
              }
            return $response;
      }
    
}