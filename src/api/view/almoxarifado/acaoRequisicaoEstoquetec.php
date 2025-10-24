<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;

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

$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];

$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
}
/*
 * Cadastra Movimento
 * */
if ($_acao == 1) {
  
   
}
/**
 * Lista Movimento
 */
else if ($_acao == 2) {

    $consulta = $pdo->query("SELECT req_status,req_tipomov FROM " . $_SESSION['BASE'] . ".requisicao
    where req_numero = '" . $_parametros['id-busca'] . "'");
    $retorno = $consulta->fetch();
    $status = $retorno['req_status'];
    $tipomov = $retorno['req_tipomov'];

    $consulta = $pdo->query("SELECT 
                            Descricao_Item,Qtde,motivo,mov_id,
                            CODIGO_FABRICANTE,Codigo_Item,Qtde_Entrega,Qtde_Devolvido,Codigo_Chamada,
                            ind_Devolvido,ENDERECO1,ENDERECO2,ENDERECO3,ENDERECO_COMP,Tab_Preco_5
                            FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico 
                         
                            LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = Codigo_Fornecedor
                            WHERE Num_Movto = '" . $_parametros['id-busca'] . "'");
    $retorno = $consulta->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr><th class="text-center">Descrição</th>
                <th class="text-center">Código</th>                
                 <th class="text-center">Qtde</th>
                <th class="text-center">Endereço</th>
                <th class="text-center">OS</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Total</th>
                <th class="text-center">Motivo</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($retorno as $row) {
                $_CAMPO = "Codigo_Item";
                 if($_vizCodInterno == 1){ 
                        $_CAMPO = "CODIGO_FABRICANTE";
                 }
                 if($row["ENDERECO1"] != ""){
                    $ender = $row["ENDERECO1"];
                    if($row["ENDERECO1"] == "RH"){
                        if($row["ENDERECO2"] != ""){
                            $ender =   $ender."/".$row["ENDERECO2"];
                            if($row["ENDERECO3"] != ""){
                                $ender =   $ender."/".$row["ENDERECO3"];
                            }
                        }
                    }else{
                        if($row["ENDERECO2"] != ""){
                            $ender =   $ender."/".$row["ENDERECO2"].$row["ENDERECO3"];                         
                        }
                     }
                 }
                $ender = $ender." ".$row["ENDERECO_COMP"];
            ?>
                <tr>
                   
                    <td class="text-center"><?= (strlen($row["Descricao_Item"]) > 39 ? substr($row["Descricao_Item"], 0, 37) . "..." : $row["Descricao_Item"]) ?></td>             
                    <td class="text-center"><?=$row[$_CAMPO]; ?></td>
                    <td class="text-center"><?=$row["Qtde"] ?></td>
                    <td class="text-center"><?=$ender?></td>
                    <td class="text-center">
                    <?php                  
                       $consulta_os = $pdo->query("SELECT  *
                                                FROM " . $_SESSION['BASE'] . ".movtorequisicaoOS                     
                                                WHERE mro_req = '" . $_parametros['id-busca'] . "' 
                                                and mro_peca = '" . $row['Codigo_Item'] . "'");
                        $retorno_os = $consulta_os->fetchAll();
                        if ($consulta_os->rowCount() > 0) {
                        foreach ($retorno_os as $ret) {
                        
                                echo $ret['mro_OS']."<br>" ;
                        }
                    }else{ ?>
                            <?= $row["Codigo_Chamada"] ?>
                    <?php }
                    ?></td>
                    <td class="text-center"><?= number_format($row["Tab_Preco_5"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["Tab_Preco_5"]*$row["Qtde"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= $row["motivo"] ?></td>
                </tr>
            <?php
             $_total = $_total + $row["Tab_Preco_5"]*$row["Qtde"];
            }
            ?>
        </tbody>
    </table>
    <strong>Total R$ <?= number_format($_total, 2, ',', '.') ;?></strong>
    <?php
    
}
else if ($_acao == 100) {
   try {
       $_CAMPO = "Codigo_Item";
       if($_vizCodInterno == 1){ 
              $_CAMPO = "CODIGO_FABRICANTE";
       }
  
       
  
       $consulta = $pdo->query("SELECT 
                               Descricao_Item,Qtde,motivo,mov_id,
                               CODIGO_FABRICANTE,Codigo_Item,Num_Movto,
                               Qtde_Entrega,Qtde_Devolvido,Almox_Destino,
                               ENDERECO1,ENDERECO2,ENDERECO3,ind_Entrega,Codigo_Chamada,Tab_Preco_5
                               FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                               LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = Codigo_Fornecedor
                               where Num_Movto = '".$_parametros['id-busca']."'
                               order by ENDERECO1,ENDERECO2,ENDERECO3");
                               $retorno = $consulta->fetchAll();
                             
      
       $consulta = $pdo->query("SELECT 
                               req_status
                               FROM " . $_SESSION['BASE'] . ".requisicao                               
                               where req_numero = '".$retorno['Num_Movto']."'");
       $retornoReq = $consulta->fetch();
       $status = $retornoReq['req_status'];
     
       foreach ($retorno as $row) {

           $q = $row['Qtde_Devolvido'];
           if($row['ind_Entrega'] == 1){
               $qe = $row['Qtde_Entrega'];
           }else{
               $qe = $row['Qtde'];
           }
       


           if($row['ind_Entrega'] == "1"){
               $entrega = "1";
               $cor = "success";
               $corInverso = ""; 
               $corInversoI = "success"; 
               
           }else{
               $entrega = "0";
               $cor = "white";
               $corInverso = "white";
               $corInversoI = "white"; 
             
           }
        
       ?> 
       <div class="row card-box m-b-10" style="padding: 5px;"">
                

                
                            <div class="col-sm-12 col-xs-12">                           
                             
                                   <div class="member-info">
                                   <p class="text-dark m-b-5">Código:<b><?=$row[$_CAMPO];?> </b> O.S:<b><?=$row['Codigo_Chamada'];?> </b>  </p>                               
                                       <div  style="display: inline;"><b><?=$row['Descricao_Item'];?></b></div> 
                                       <div  style="display: inline;"><b><?=$row['Descricao_Item'];?></b></div> 
                                       <div style="text-align:right ;">
                                            <span style="font-size:14px; color:red;font-weight: 800; " >R$ :<b> <strong> <?= number_format($row["Tab_Preco_5"], 2, ',', '.') ?></strong></b></span>
                                        </div>
                                       <div  style="display: inline;">
                                           <p class="text-dark m-b-5"><span style="font-size:14px; font-weight: 800; " >QTDE :<b><input  id="qte<?=$row['mov_id'];?>" type="numeric"   style="width:30px; font-weight: 800; " value="<?=$row['Qtde'];?>" disabled></b></span> 
                                           DEVOLVIDA:<b><input   type="numeric"   style="width:30px ;font-size:14px;" value="<?=$q;?>" disabled></b>
                                           ENTREGUE:<b><input   type="numeric"   style="width:30px ;font-size:14px;" value="<?=$qE;?>" disabled></b></p>
                                       </div> 
                                   </div>
                               </div>
                           

                       </div>
                    
           
                       <?php } ?>
       
            
       <?php

   } catch (PDOException $e) {
   ?>
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-body" id="imagem-carregando">
                   <h2><?= "Erro: " . $e->getMessage() ?></h2>
                   <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
               </div>
           </div>
       </div>
   <?php
   }
}

