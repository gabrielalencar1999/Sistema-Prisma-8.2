<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;
use Functions\Acesso;
$pdo = MySQL::acessabd();

function LimpaVariavel($valor)
{
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

date_default_timezone_set('America/Sao_Paulo');

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data_atual      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

$data = $ano."-".$mes."-".$dia; 

$usuario = $_SESSION['tecnico'];
//  $('#_idexpeca').val(_idpeca + ';' + _os + ';' + _idcodpeca + ';' + _qtde + ';' + _usuario + ';' + codfornecedor);
$iddados = $_parametros['_idexpeca']; //
$_var = explode(";",$iddados);

$_parametros["_codpesq"] = $_var[2];
$_parametros["qnt-mov"]= $_var[3];
$_parametros["OS"]= $_var[1];

$almoxarifadomatriz = 1;
 $_tipomov = "T"; 
 $motivo = "reservado";

$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
}
/*
 * RELATORIO SIMPLIFICADO
 * */

 $_retreserva = Acesso::customizacao('30'); //busca almoxarifado para reserva


;



$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
}

if ($_POST['acao'] == 1) {
  
        //validar estoque
            if($_validaestoque == 1) {

                        $_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
                        WHERE Codigo_Item = '" . $_parametros["_codpesq"] . "' and Codigo_Almox = '1'";
                
                    $consulta = $pdo->query("$_sql");
                    $retorno = $consulta->fetch();
                    foreach ($retorno as $row) {
                            if( $_parametros["qnt-mov"] <= $retorno['Qtde_Disponivel'] and $retorno['Qtde_Disponivel'] != "") { 

                            }else {
                                ?>
                               
                                            <h3>Quantidade informado superior estoque disponivel  (Qtde Est:<?=$retorno['Qtde_Disponivel'];?>) </h3>
                                           
                        <?php
                        exit();
                            }
                        }

            }
                //verificar se existe alguma requisicao aberta no dia do tecnico para reserva
               $sql = "SELECT req_numero  FROM ".$_SESSION['BASE'].".requisicao  where req_criacao =  '$usuario' and req_data  = '$data' AND req_status = '4'";    
              // echo  $sql;
             //  exit();          
               $consulta = $pdo->query("$sql");
               $retorno = $consulta->fetch();
               $reqnumber = $retorno['req_numero'];

                if($reqnumber == "") {              
                
                            $consulta = $pdo->query("SELECT Num_Requisicao FROM ".$_SESSION['BASE'].".parametro");
                            $retorno = $consulta->fetch();
                            $reqnumber = $retorno['Num_Requisicao'];

                            $_SQL = "UPDATE   ".$_SESSION['BASE'].".parametro
                            SET Num_Requisicao = '".($reqnumber+1)."' ";
                            $stm = $pdo->prepare($_SQL);	            
                            $stm->execute();

                            $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".requisicao
                            (req_data,req_almoxarifado,req_almoxarifadoPara,req_status,req_numero,req_datahora,req_criacao,req_tipomov) VALUES(?, ?, ?, 2, ?, ?,?,?)");
                            $statement->bindParam(1, $data_atual);
                            $statement->bindParam(2, $almoxarifadomatriz);
                            $statement->bindParam(3, $_retreserva);
                            $statement->bindParam(4, $reqnumber);
                            $statement->bindParam(5, $data_atual);
                            $statement->bindParam(6, $usuario);      
                            $statement->bindParam(7, $_tipomov);           
                            $statement->execute();
                 }


            $consulta = $pdo->query("SELECT DESCRICAO, PRECO_CUSTO,CODIGO_FORNECEDOR FROM " . $_SESSION['BASE'] . ".itemestoque WHERE codigo_fabricante = '" . $_parametros['_codpesq'] . "' LIMIT 1");
            $retorno = $consulta->fetch();
            
            $codigo = $retorno["CODIGO_FORNECEDOR"];

           //   if ($_parametros["tipo-mov"] == "T") {
                $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".movtorequisicao_historico (Num_Movto, Codigo_Item, Almox_Origem, Almox_Destino, Tipo_Mov, Data_mov, Qtde, Valor_Item, Usuario_Mov, motivo, Descricao_Item, Codigo_Chamada) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?)");
                $statement->bindParam(1, $reqnumber);
                $statement->bindParam(2, $_parametros["_codpesq"]);
                $statement->bindParam(3, $almoxarifadomatriz); //almox origem
                $statement->bindParam(4, $_retreserva); //almox destino 
                $statement->bindParam(5,  $_tipomov); //$_parametros["tipo-mov"]
                $statement->bindParam(6, $data_atual); //$_parametros["data-mov"]
                $statement->bindParam(7, $_parametros["qnt-mov"]);
                $statement->bindParam(8, $retorno["PRECO_CUSTO"]);
                $statement->bindParam(9, $_SESSION["NOME"]);
                $statement->bindParam(10, $motivo);
                $statement->bindParam(11, $retorno["DESCRICAO"]);               
                $statement->bindParam(12, $_parametros["OS"]);
                $statement->execute();

                //MOVImenta estoque 
                
                $para = $_retreserva;
                $de = '1'; //matriz
                $tipo = $_tipomov;
                $requisicao = $reqnumber;
               $motivod = $motivo;
               $projeto = "";
               $os =  $_parametros["OS"];
               $qtde = $_parametros["qnt-mov"];
                
          

                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] - $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $de);
                $updateItemAlmox->execute();

                if($Ind_Gera_Treinamento == 1 and $de == '1') {		
                    					
                 //   $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "S","Requisição $requisicao");	    
                }

                $Codigo_Movimento = 'S';
                $Inventario = '0';
                if($projeto == ""){
                    $projeto = 0;
                }

                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $de);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual);
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();

                $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                if (!$retornoItemAlmox) {
                    $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                    $insertItemAlmox->bindParam(1, $codigo);
                    $insertItemAlmox->bindParam(2, $para);
                    $insertItemAlmox->execute();
                }

                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();

   

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] + $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $para);
                $updateItemAlmox->execute();

                if($Ind_Gera_Treinamento == 1 and $para == '1') {	
                   						
                 //   $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "E","Requisição $requisicao");	    
                }
                

                $Codigo_Movimento = 'E';
                $Inventario = '0';
                if($projeto == ""){
                    $projeto = 0;
                }
                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $para);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual);
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();

              
               //atualiza chamada
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".chamadapeca SET Num_Requisicao = ? , reserva = 1 where Numero_OS = '$os' and Codigo_Peca_OS = '$codigo'");             
                $update->bindParam(1, $requisicao);
                $update->execute();


                //ATUaliza todos com indentrega
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".movtorequisicao_historico SET Qtde_Entrega = Qtde where Num_Movto = ? and ind_Entrega = 0");             
                $update->bindParam(1, $requisicao);
                $update->execute();

                $ind_Entrega = 1;
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".movtorequisicao_historico SET ind_Entrega = ? where Num_Movto = ?");
                $update->bindParam(1, $ind_Entrega);
                $update->bindParam(2, $requisicao);
                $update->execute();

                      
                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".movtorequisicao_historico  set ind_baixado = '1', hora_finalizada = '$data_atual' WHERE Num_Movto  = ? ");
                $updateItemAlmox->bindParam(1,$requisicao);   
                $updateItemAlmox->execute();

                $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_status = '2', req_tipomov = '$tipo', req_titulo = ? WHERE  req_numero = ?");
                $updateParametro->bindParam(1, $tituloreq);
                $updateParametro->bindParam(2, $requisicao);            
                $updateParametro->execute();

        


        echo "$reqnumber";
     exit();

}
//monta requisição a partir das peças O.S que ainda não tem requisição
if ($_POST['acao'] == 2) {
      //MOVImenta estoque 
              echo "modulo em andamento";
              exit();  
                $almoxarifadomatriz = 1;
                $_tipomov = "T"; 
                $motivo = "gerado p/ O.S";
                $de = '1'; //matriz
                $para  = 0;  // almoxarifado tecnico 
                $tipo = $_tipomov;
                $requisicao = $reqnumber;
                $motivod = $motivo;
                $projeto = "";
                $os =  $_parametros["chamada"];
                                

                //verificar se existe alguma peça a gerar requisição
               $sql = "SELECT peca_tecnico  FROM ".$_SESSION['BASE'].".chamadapeca  where Numero_OS =  '".$os."' and Num_Requisicao  <= '0' group by peca_tecnico";                     
            //   echo  $sql;
               $consulta = $pdo->query($sql);
                    // Percorrer os resultados com foreach
                    foreach ($consulta as $linha) {
                        $para = 0;
                       $tecnico = $linha['peca_tecnico'];
                       //busca almoxarifado base 
                        $sqltec = "SELECT Codigo_Almox  FROM ".$_SESSION['BASE'].".almoxarifado 
                        LEFT JOIN  ".$_SESSION['BASE'].".usuario  ON Codigo_Almox = usuario_almox
                        where usuario_CODIGOUSUARIO =  '".$tecnico."' ";       
                               
                        $consultatec = $pdo->query($sqltec);
                                // Percorrer os resultados com foreach
                                foreach ($consultatec as $linhatec) {
                                    $para = $linha['Codigo_Almox'];                              
                                }
                                

                                   if($para > 0) {  //gera requisição              
                
                                        $consulta = $pdo->query("SELECT Num_Requisicao FROM ".$_SESSION['BASE'].".parametro");
                                        $retorno = $consulta->fetch();
                                        $reqnumber = $retorno['Num_Requisicao'];

                                        $_SQL = "UPDATE   ".$_SESSION['BASE'].".parametro
                                        SET Num_Requisicao = '".($reqnumber+1)."' ";
                                        $stm = $pdo->prepare($_SQL);	            
                                        $stm->execute();

                                        $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".requisicao
                                        (req_data,req_almoxarifado,req_almoxarifadoPara,req_status,req_numero,req_datahora,req_criacao,req_tipomov) VALUES(?, ?, ?, 2, ?, ?,?,?)");
                                        $statement->bindParam(1, $data_atual);
                                        $statement->bindParam(2, $almoxarifadomatriz);
                                        $statement->bindParam(3, $para);
                                        $statement->bindParam(4, $reqnumber);
                                        $statement->bindParam(5, $data_atual);
                                        $statement->bindParam(6, $usuario);      
                                        $statement->bindParam(7, $_tipomov);           
                                        $statement->execute();

                                        //gera itens da requisição
                                        $sqlch = "SELECT Codigo_Peca_OS,Qtde_peca FROM ".$_SESSION['BASE'].".chamadapeca  where Numero_OS =  '".$os."' and Num_Requisicao  <= '0' ";                     
                                        $consultach = $pdo->query($sqlch);

                                                // Percorrer os resultados com foreach
                                                foreach ($consultach as $linhach) {
                                                    $consulta = $pdo->query("SELECT DESCRICAO, PRECO_CUSTO,CODIGO_FORNECEDOR FROM " . $_SESSION['BASE'] . ".itemestoque WHERE codigo_fabricante = '" . $_parametros['_codpesq'] . "' LIMIT 1");
                                                    $retorno = $consulta->fetch();
                                                                
                                                    $codigo = $retorno["CODIGO_FORNECEDOR"];

                                                    //   if ($_parametros["tipo-mov"] == "T") {
                                                            $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".movtorequisicao_historico (Num_Movto, Codigo_Item, Almox_Origem, Almox_Destino, Tipo_Mov, Data_mov, Qtde, Valor_Item, Usuario_Mov, motivo, Descricao_Item, Codigo_Chamada) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?)");
                                                            $statement->bindParam(1, $reqnumber);
                                                            $statement->bindParam(2, $linhach["Codigo_Peca_OS"]);
                                                            $statement->bindParam(3, $almoxarifadomatriz); //almox origem
                                                            $statement->bindParam(4, $para); //almox destino 
                                                            $statement->bindParam(5,  $_tipomov); //$_parametros["tipo-mov"]
                                                            $statement->bindParam(6, $data_atual); //$_parametros["data-mov"]
                                                            $statement->bindParam(7, $linhach["Qtde_peca-mov"]);
                                                            $statement->bindParam(8, $retorno["PRECO_CUSTO"]);
                                                            $statement->bindParam(9, $_SESSION["NOME"]);
                                                            $statement->bindParam(10, $motivo);
                                                            $statement->bindParam(11, $retorno["DESCRICAO"]);               
                                                            $statement->bindParam(12, $_parametros["OS"]);
                                                            $statement->execute();

                                                                                                                  
                                                           } 

                                                        
                                                        //atualiza chamada
                                                            $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".chamadapeca SET Num_Requisicao = ?  where Numero_OS = '$os' and Codigo_Peca_OS = '$codigo'");             
                                                            $update->bindParam(1, $requisicao);
                                                            $update->execute();


                                                            $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_status = '3', req_tipomov = '$tipo', req_titulo = 'Gerado por O.S' WHERE  req_numero = ?");
                                                            $updateParametro->bindParam(1, $tituloreq);
                                                            $updateParametro->bindParam(2, $requisicao);            
                                                            $updateParametro->execute();
                                                

                                   } //gera itens requisica


                    }
                                    

             

           
        


        echo "$reqnumber";
     exit();
}

