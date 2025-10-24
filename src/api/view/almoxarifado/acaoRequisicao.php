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

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data_atual      = $ano . "-" . $mes . "-" . $dia. " ".$hora;


$_acao = $_parametros["acaoRel"];

$usuario = $_SESSION['tecnico'];

$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
}
/*
 * RELATORIO SIMPLIFICADO
 * */

 

$dia       = date('d'); 
$mes       = date('m'); 
$ano       = date('Y'); 

$data_atual = $ano."-".$mes."-".$dia; ;

$_requisicao = $_parametros['_pedido'];
$_almox = $_parametros['_vendedor'];
$situacao = $_parametros['_situacao'];
$dataini = $_parametros['_dataIni'];
$datafim = $_parametros['_dataFim'];
$_tipomov = $_parametros['_tipomov'];

    if($datafim == "" ) {
        $dataini = $data_atual;
        $datafim = $data_atual;
        //   $datafimP = $data; 
    }

    if($_tipomov  != "" ) { 
        $filTipo= " and req_tipomov = '$_tipomov'  ";
   
  }

    if($_requisicao != "" ) { 
    $vend = " and req_numero = '$_requisicao'  ";
    $vendREQ = " OR req_numero = '$_requisicao' $filTipo  ";
   }

   if($situacao != "" ) { 
    $situacao = " and req_status = '$situacao'  ";
   }

   if($_almox != "" ) { 
    $filamox = " and req_almoxarifado = '$_almox'  ";
    $filamoxB = "OR req_data BETWEEN '$dataini' and  '$datafim' $situacao  and req_almoxarifadoPara = '$_almox' $filTipo ";
   }



if ($_acao == 1) {
  
            ?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Prisma- Rel Requisição</title>
            <style type="text/css">
            .style45 {font-family: Arial, Helvetica, sans-serif; font-size: 12px;   }
            .style46 {font-family: Arial, Helvetica, sans-serif; font-size: 14px;   }
            table.bordasimples {border-collapse: collapse;  }
                table.bordasimples tr td {
                border-left: 0px ;
                border-right: 0px ;
                border-bottom:1px dashed #000000;            
            }

            .linha {  border-bottom: 1px solid #CCC };
            </style>
            <body>


                <?php

                    $sql="Select C.Descricao as tipomovdesc,req_numero,A.Descricao as descA,B.Descricao as descB,
                    date_format(req_data,'%d/%m/%Y') as dt,date_format(req_datahora,'%H:%i') as dthora,
                    sitreq_descricao,label_ped,req_titulo
                    from  " . $_SESSION['BASE'] . ".requisicao 
                    left join " . $_SESSION['BASE'] . ".situacaorequisicao  ON  sitreq_id = req_status
                    left join " . $_SESSION['BASE'] . ".almoxarifado as A ON  req_almoxarifado = A.Codigo_Almox
                    left join " . $_SESSION['BASE'] . ".almoxarifado as B ON  req_almoxarifadoPara = B.Codigo_Almox
                    left join " . $_SESSION['BASE'] . ".tabmovtoestoque as C ON  req_tipomov = Tipo_Movto_Estoque
                    WHERE req_data BETWEEN '$dataini' and  '$datafim' $situacao $vend  $filamox $filamoxB $vendREQ $filTipo "  ;                  
                    $consulta = $pdo->query("$sql");
                    $retorno = $consulta->fetchall();             
               
                    ?> 
                    <table width="900" border="0" >                
                    <tr class="style46" >
                            <td colspan="4">Relatório Simplificado</span></td>
                    <td width="36%" colspan="4" style="text-align:left;font-size:16px"><strong>
                    </strong>
                            Data: <?= date('d/m/Y') ?> </td>                
                    </tr>

                  
                    <tr>
                <td height="8" colspan="8"><div class="linha"></div></td>
            </tr>
                    </table>
                <table width="900" class="bordasimples" >                  
                <tr>
                    <th style="text-center">Nº Controle </th>    
                    <th style="text-center">Data </th>   
                    <th style="text-center">Tipo </th>          
                    <th style="text-center">De Almoxarifado </th>
                    <th style="text-center">Para Almoxarifado </th>
                    <th style="text-center">Status</th>
                    <th style="text-left">Tit.Ref</th>
                </tr>
                                    <?php
                    
                    
                    foreach ($retorno as $rowalmox) {
                        ?>
                        <tr>
                            <td  align="center"> <?=$rowalmox['req_numero']?></td> 
                            <td align="center"><?=$rowalmox['dt'];?></td>     
                            <td align="center"><?=$rowalmox['tipomovdesc'];?></td> 
                            <td  align="center"><?=$rowalmox['descA'];?></td>       
                            <td  align="center"><?=$rowalmox['descB'];?></td>                                     
                            <td  align="center"> <span class="label label-table <?=$rowalmox['label_ped'];?>"><?=$rowalmox['sitreq_descricao'];?></span>
                            <td ><?=$rowalmox['req_titulo'];?></td>  
                                </td>
                           <?php } ?>               
                        </tr>   
                    
            </table>
            </body>
            <?php
            exit();

}


if ($_acao == 2) {
  
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Prisma- Rel Requisição</title>
    <style type="text/css">
    .style45 {font-family: Arial, Helvetica, sans-serif; font-size: 12px;   }
    .style46 {font-family: Arial, Helvetica, sans-serif; font-size: 14px;   }
    table.bordasimples {border-collapse: collapse;  }
        table.bordasimples tr td {
        border-left: 0px ;
        border-right: 0px ;
        border-bottom:1px dashed #000000;            
    }

    .linha {  border-bottom: 1px solid #CCC };
    </style>
    <body>
        <?php
            $sql="Select C.Descricao as tipomovdesc,req_numero,A.Descricao as descA,B.Descricao as descB,
            date_format(req_data,'%d/%m/%Y') as dt,date_format(req_datahora,'%H:%i') as dthora,
            sitreq_descricao,label_ped,req_titulo
            from  " . $_SESSION['BASE'] . ".requisicao 
            left join " . $_SESSION['BASE'] . ".situacaorequisicao  ON  sitreq_id = req_status
            left join " . $_SESSION['BASE'] . ".almoxarifado as A ON  req_almoxarifado = A.Codigo_Almox
            left join " . $_SESSION['BASE'] . ".almoxarifado as B ON  req_almoxarifadoPara = B.Codigo_Almox
            left join " . $_SESSION['BASE'] . ".tabmovtoestoque as C ON  req_tipomov = Tipo_Movto_Estoque
            WHERE req_data BETWEEN '$dataini' and  '$datafim' $situacao $vend  $filamox $filamoxB $vendREQ $filTipo "  ;                  
            $consulta = $pdo->query("$sql");
            $retorno = $consulta->fetchall();             
       
            ?> 
            <table width="900" border="0" >                
            <tr class="style46" >
                    <td colspan="4" style="text-align:left;font-size:16px">Relatório Detalhado</span></td>
            <td width="36%" colspan="4" style="text-align:left;font-size:16px"><strong>
            </strong>
                    Data: <?= date('d/m/Y') ?> </td>                
            </tr>

          
            <tr>
        <td height="8" colspan="8"><div class="linha"></div></td>
    </tr>
            </table>
            <table width="900" class="bordasimples" >                       
        <tr bgcolor="#F0F0F0">
            <th style="text-center" >Nº Controle </th>  
            <th style="text-center" >Data </th>  
            <th style="text-center">Tipo </th>          
            <th style="text-center">De Almoxarifado </th>
            <th style="text-center">Para Almoxarifado </th>
            <th style="text-center">Status</th>
            <th style="text-left">Tit.Ref</th>
        </tr>
         <?php           
            
            foreach ($retorno as $rowalmox) {
                ?>
                <tr>
                    <th  align="center" > <?=$rowalmox['req_numero']?></th>    
                    <td align="center"><?=$rowalmox['dt'];?> </td>                      
                    <th align="center"><?=$rowalmox['tipomovdesc'];?></th> 
                    <th  align="center"><?=$rowalmox['descA'];?></th>       
                    <th  align="center"><?=$rowalmox['descB'];?></th>                                     
                    <th  align="center"> <span class="label label-table <?=$rowalmox['label_ped'];?>"><?=$rowalmox['sitreq_descricao'];?></span>
                    <th ><?=$rowalmox['req_titulo'];?></th>  
                </td>
                        <tr bgcolor="#F0F0F0">
                        <td align="right">item</td>
                        <td style="text-center">Código</td>
                        <td colspan="3"  style="text-center">Descrição</td>
                        <td align="center">Qtde</div></td>
                        <td style="text-center"><div align="center">O.S</div></td>
                       
                        </tr>
                   <?php 
                   $item = 0;
                    $_sql = "SELECT  Descricao_Item,Qtde,motivo,mov_id,
                    CODIGO_FABRICANTE,Codigo_Item,Tab_Preco_5,
                    Qtde_Entrega,Qtde_Devolvido,Qtde_Trocado,
                    ENDERECO1,ENDERECO2,ENDERECO3,ENDERECO_COMP,Codigo_Chamada
                    FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico 
                    LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque on Codigo_Item = codigo_fornecedor 
                    WHERE Num_Movto = '" . $rowalmox['req_numero'] . "'  ORDER BY Descricao_Item ASC";      
                    
                    $consultaMov = $pdo->query("$_sql");
                    $retornoMov = $consultaMov->fetchAll();                 
                 foreach ($retornoMov as $rst) {
                    $item++;
                    ?>
                      <tr>
                        <td align="right"><strong><?=$item;?>-</strong></td>
                        <td style="text-center"><div align="left"><?=$rst["CODIGO_FABRICANTE"];?></div></td>
                        <td colspan="3" style="text-center"><div align="left"><?=($rst["Descricao_Item"]);?></div></td>
                        <td style="text-center"><div align="center"><?=$rst["Qtde"];?></div></td>
                        <td style="text-left">  <div align="center"><?=($rst["Codigo_Chamada"]);?></div></th>
                        </tr>                        
                  <?php

                 } ?>
                <tr>
                            <th colspan="7" >&nbsp;</th>  
                            
                        </tr>
                 <?php
              
                } ?>     
               
            
    </table>
    </body>
    <?php

}