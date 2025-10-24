<?php 

require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;
use Functions\Acesso;

$pdo = MySQL::acessabd();
$codigoos = $_parametros["chamada"];
date_default_timezone_set('America/Sao_Paulo');

$_retvieweVlr = Acesso::customizacao('15'); //esconde valores 
$_retvieweDev = Acesso::customizacao('16'); //esconde coluan devolucao

$elx = $_POST['acao'];

$pedido = $_GET["pedido"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$horaatual = date("H:i:s");

$data_atual      = $dia . "/" . $mes . "/" . $ano. " ".$horaatual;



  $consulta = "Select * from parametro ";
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
		$num_rows = mysqli_num_rows($executa);
		
		   if($num_rows!=0)
			{
			
				while($rst = mysqli_fetch_array($executa))	{
		
          $_validaestoque = $rst["empresa_validaestoque"];
          $_vizCodInterno = $rst["empresa_vizCodInt"];
     
				$numrua = $rst["NumRua"];
				$endereco = $rst["ENDERECO"];
							    
				$bairro = $rst["BAIRRO"];
                $cep = $rst["Cep"];
				$cidade = $rst["CIDADE"];
				$estado = $rst["UF"];
				$EMAIL = $rst["EMAIL"];
				$inscricao = $rst["INSC_ESTADUAL"];
				$cnpj = $rst["CGC"];
				$telefone = $rst["TELEFONE"];
				$email = $rst["EMAIL"];
				$site = $rst["site"];
				$fantasia = $rst["NOME_FANTASIA"];
				   

				}}
        $consulta = "Select arquivo_logo_base64 from empresa  limit 1 ";       
        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
     
          while($rst = mysqli_fetch_array($executa))	{
              $logo64 = $rst["arquivo_logo_base64"];             
         
          }
		





?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Prisma-Requisição</title>


<style type="text/css">
.style45 {font-family: Arial, Helvetica, sans-serif; font-size: 12px;   }
.style46 {font-family: Arial, Helvetica, sans-serif; font-size: 14px;   }
 table.bordasimples {border-collapse: collapse;  }

	table.bordasimples tr td {
	  border-left: 0px ;
      border-right: 0px ;
	  border-bottom:1px dashed #000000;
  
  }


/*
    .linha {
      border-collapse: collapse;
      border-top: 0px ;
      border-left: 0px ;
      border-right: 0px ;
      border-bottom: 1px dashed #000000;
   
  font-family: "Calibri";    */
     
  .linha {  border-bottom: 1px solid #CCC };
     

  

</style>
<body>


     <?php


           $consulta = $pdo->query("SELECT A.Descricao as descA,B.Descricao as descB ,C.Descricao as tipo 
           FROM ".$_SESSION['BASE'].".movtorequisicao_historico
           left join ".$_SESSION['BASE'].".almoxarifado as A ON  Almox_Origem = A.Codigo_Almox
           left join ".$_SESSION['BASE'].".almoxarifado as B ON  Almox_Destino = B.Codigo_Almox
           left join ".$_SESSION['BASE'].".tabmovtoestoque as C on Tipo_Mov = Tipo_Movto_Estoque
            where Num_Movto = '".$_parametros['id-busca']."'");
           $retorno = $consulta->fetch();
        
           $tipomov   = $retorno['tipo'];
           $deA = $retorno['descA'];
           $paraB = $retorno['descB'];
          if( $paraB != "") {
            $paraB = " para $paraB";
          }
   
         $consulta = $pdo->query("SELECT 
         req_status,sitreq_descricao 
         FROM " . $_SESSION['BASE'] . ".requisicao 
         left join " . $_SESSION['BASE'] . ".situacaorequisicao on req_status= `sitreq_id`                              
         where req_numero = '".$_parametros['id-busca']."'");
         $retornoReq = $consulta->fetch();
         $status = $retornoReq['req_status'];
         $statusdesc = $retornoReq['sitreq_descricao'];

         $_ordem = $_parametros['_keyidordem'];  // $_ORDEM = "ORDER BY mov_id ASC";
       
         switch ($_ordem) {
          case 0:
            $_ORDEM = "ORDER BY hora_finalizada, Descricao_Item ASC";
              break;
          case 9:
            $_ORDEM = "ORDER BY hora_finalizada,CODIGO_FABRICANTE ASC";
              break;
          case 1:
            $_ORDEM = "ORDER BY hora_finalizada,Descricao_Item ASC";
              break;
          case 2:
             $_ORDEM = "ORDER BY hora_finalizada,ind_Devolvido ASC";
              break; 
          case 4:
                $_ORDEM = "ORDER BY hora_finalizada,Qtde ASC";
                  break;
          case 3:
             $_ORDEM = "ORDER BY hora_finalizada,ENDERECO1,ENDERECO2,ENDERECO3,ENDERECO_COMP ASC";
               break;  
          case 5:
             $_ORDEM = "ORDER BY hora_finalizada,Codigo_Chamada ASC";
               break;                  
            
      }

         $_sql = "SELECT  Descricao_Item,Qtde,motivo,mov_id,
         CODIGO_FABRICANTE,Codigo_Item,Tab_Preco_5,
         Qtde_Entrega,Qtde_Devolvido,Qtde_Trocado,
         ENDERECO1,ENDERECO2,ENDERECO3,ENDERECO_COMP,Codigo_Chamada,
         date_format(hora_finalizada,'%d/%m/%Y %T') as dt_atualizada
          FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico 
         LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque on Codigo_Item = codigo_fornecedor 
         WHERE Num_Movto = '" . $_parametros['id-busca'] . "'  $_ORDEM";      
      
         $consultaMov = $pdo->query("$_sql");
         $retornoMov = $consultaMov->fetchAll();
        
 
         $_CAMPO = "Codigo_Item";
         if($_vizCodInterno == 1){ 
                $_CAMPO = "CODIGO_FABRICANTE";
         }
 
      
  
		  ?> 
        <table width="900" border="0" >
       
        <tr class="style46" >
                <td colspan="4">     
                   <?php if($logo64 != "") {?>
                      <img src="data:image/png;base64, <?=$logo64?>" width="100px"/>
                      <?php
                    }else{ ?>
                        <img src="../logos/<?=$logo;?>" alt=""/>
                    <?php } ?></td>
          <td width="36%" colspan="4" style="text-align:left;font-size:16px"><strong>Requisição N° 
          <?= $_parametros['id-busca'] ?>
          </strong><br>
                Data: <?= date('d/m/Y') ?>
                Status: <?= $statusdesc;?>  <br></td>                
        </tr>

        <tr >
                
                <td colspan="4"><?=$tipomov;?> : <?=$deA;?> <?=$paraB;?></td>
                <td colspan="4" style="text-align:left"><?=$_SESSION['login'];?></td>
                
        </tr>
        <tr>
    <td height="8" colspan="8"><div class="linha"></div></td>
  </tr>
        </table>
    

        <?php 
        if($status == 1 or $status == 3){ ?>
          <table width="900" border="0"  >
              <tr class="style45" >
                <td><strong>Código </strong></td>
                <td><strong>Descrição</strong></td>
                <?php if($_retvieweVlr != '1'){ ?>
                  <td><div align="center"><strong>Valor</strong></div></td>
                <?php } ?>
                
                <td><div align="center"><strong>Qtde</strong></div></td>
                <td><div align="center"><strong>End.</strong></div></td>
                <td><div align="center"><strong>Troca</strong></div></td>
                <?php if($_retvieweDev != '1'){ ?>
                <td><div align="center"><strong>Devolução</strong></div></td>
                <?php  } ?>
                <td><div align="center"><strong>O.S</strong></div></td>
        </tr>
            <?php
          
		 
          foreach ($retornoMov as $rst) {
            $ender = "";
        
          
           

                if($rst["ENDERECO1"] != ""){
                  $ender = $rst["ENDERECO1"];
                  if(substr($rst["ENDERECO1"],0,1) == "R"){
                      if($rst["ENDERECO2"] != ""){
                          $ender =   $ender."/".$rst["ENDERECO2"];
                          if($rst["ENDERECO3"] != ""){
                              $ender =   $ender."/".$rst["ENDERECO3"];
                          }
                      }
                  }else{
                      if($rst["ENDERECO2"] != ""){
                          $ender =   $ender."/".$rst["ENDERECO2"].$rst["ENDERECO3"];                         
                      }
                   }
               }
              $ender = $ender." ".$rst["ENDERECO_COMP"];
           
?>
                <tr  class="style45">
                <td width="9%"><div align="left">
                  <?=$rst["$_CAMPO"];?>
                </div></td>
           
                <td width="37%">
                  <div align="left">
                    <?=($rst["Descricao_Item"]);?>
                  </div></td>
                  <?php if($_retvieweVlr != '1'){ ?>
                    <td width="7%"><div align="center">                 
                         <?=number_format($rst["Tab_Preco_5"],2,',','.');?>
                     </div></td>
                    <?php }
                     ?>

                  
              <td width="4%"><div align="center">
                  <?=$rst["Qtde"];?>
                </div></td>   
                <td width="6%">
                  <div align="center">
                  <?=$ender;?>
                  </div></td>
                    
                <td width="7%"><div align="center"> ______</div></td>
                <?php if($_retvieweDev != '1'){ ?>
                <td width="7%"><div align="center"> ______</div></td>
                <?php  } ?>
                <td width="12%">
                  <div align="center">
                    <?=($rst["Codigo_Chamada"]);?>
                  </div></td>
                   
              </tr>
              <tr>
              <td height="8" colspan="10"><div class="linha"></div></td>
              </tr>
             
              <?php } ?>
        <?php

        } else {//fim status 1
       
          ?>
            <table width="900" border="0"  >
              <tr  class="style45">
                <td><strong>Código</strong></td>
                <td><strong>Descrição</strong></td>
                <?php 
                   if($_retvieweVlr != '1'){ ?>
                          <td><div align="center"><strong>Valor</strong></div></td>
                        <?php }  ?>
                <td><div align="center"><strong>Qtde</strong></div></td>
                <td><div align="center"><strong>End.</strong></div></td>
                <td><div align="center"><strong>Entregue</strong></div></td>
                <td><div align="center"><strong>Troca</strong></div></td>
                <?php if($_retvieweDev != '1'){ ?>
                <td><div align="center"><strong>Devol.</strong></div></td>
                <?php } ?>
                <td><div align="center"><strong>O.S</strong></div></td>
             </tr>
            <?php
          
		 
          foreach ($retornoMov as $rst) {
              if($hora != "" ) {
                if( $hora == '00/00/0000 00:00:00') { ?>
              
                <table width="100%" border="0" >  
                  
                       <td  colspan="4" valign="bottom"><span class="style41">Data Impressão:<?=$data_atual;?></span></td>
                       <td  colspan="4" style="text-align: right;"><span class="style41"> Peças não movimentadas no estoque </span></td>    </tr>
                      
                  </table>
                  <table width="900" border="0"  >
                      <tr  class="style45">
                        <td><strong>Código</strong></td>
                        <td><strong>Descrição</strong></td>
                       <?php 
                         if($_retvieweVlr != '1'){ ?>
                          <td><div align="center"><strong>Valor</strong></div></td>
                        <?php }  ?>

                        <td><div align="center"><strong>Qtde</strong></div></td>
                        <td><div align="center"><strong>End.</strong></div></td>
                        <td><div align="center"><strong>Entregue</strong></div></td>
                        <td><div align="center"><strong>Troca</strong></div></td>
                        <?php if($_retvieweDev != '1'){ ?>
                          <td><div align="center"><strong>Devol.</strong></div></td>
                          <?php } ?>
                        <td><div align="center"><strong>O.S</strong></div></td>
                    </tr><?php 
                 }else{
                if($hora != $rst["dt_atualizada"]  ) {
                  echo $_string ;
                  $_string  = "";
                   ?>
                    <table width="100%" border="0" >  
                    <td  colspan="4" valign="bottom"><span class="style41">Data Impressão:<?=$data_atual;?></span></td>
                    <td  colspan="4" style="text-align: right;"><span class="style41"> <?=$horaatualizada;?> </span></td>    </tr>
                        <tr  style="height: 60px;">
                          <td height="21" colspan="4" valign="bottom"><span class="style41">__________________________</span></td>
                          <td width="61%" colspan="4" valign="bottom">__________________________</td>
                        </tr>
                        <tr >
                          <td colspan="4" valign="top"><span class="style41">Visto Solicitante</span></td>
                          <td colspan="4" valign="top">Visto Almoxarifado</td>                
                      </tr>
                  </table>
                  <table width="900" border="0"  >
                      <tr  class="style45">
                        <td><strong>Código</strong></td>
                        <td><strong>Descrição</strong></td>
                        <?php if($_retvieweVlr != '1'){ ?>
                          <td><div align="center"><strong>Valor</strong></div></td>
                        <?php  } ?>
                        <td><div align="center"><strong>Qtde</strong></div></td>
                        <td><div align="center"><strong>End.</strong></div></td>
                        <td><div align="center"><strong>Entregue</strong></div></td>
                        <td><div align="center"><strong>Troca</strong></div></td>
                        <?php if($_retvieweDev != '1'){ ?>
                          <td><div align="center"><strong>Devol.</strong></div></td>
                          <?php } ?>
                        <td><div align="center"><strong>O.S</strong></div></td>
                    </tr>
                <?php 
              }}}

            $ender = "";
         
            $hora = $rst["dt_atualizada"];
            $horaatualizada = "Data/Hora Movimento:".$rst["dt_atualizada"];
            if($status == 4 ){ 
              $horaatualizada = "";
            }
            if($rst["ENDERECO1"] != ""){
              $ender = $rst["ENDERECO1"];
              if(substr($rst["ENDERECO1"],0,1) == "R"){
                  if($rst["ENDERECO2"] != ""){
                      $ender =   $ender."/".$rst["ENDERECO2"];
                      if($rst["ENDERECO3"] != ""){
                          $ender =   $ender."/".$rst["ENDERECO3"];
                      }
                  }
              }else{
                  if($rst["ENDERECO2"] != ""){
                      $ender =   $ender."/".$rst["ENDERECO2"].$rst["ENDERECO3"];                         
                  }
               }
           }
          $ender = $ender." ".$rst["ENDERECO_COMP"];
              
?>
         <tr class="style45" >
                <td width="9%"><div align="left">
                  <?=$rst["$_CAMPO"];?>
                </div></td>           
                <td width="37%">
                  <div align="left">
                    <?=($rst["Descricao_Item"]);?>
                  </div></td>
                  <?php if($_retvieweVlr != '1'){  ?>
                    <td width="7%"><div align="center">                 
                      <?=number_format($rst["Tab_Preco_5"],2,',','.');?>
                      </div></td>  
                    <?php } ?>
               
              <td width="4%"><div align="center">
                  <?=$rst["Qtde"];?>
                </div></td>   
                <td width="6%">
                  <div align="center">
                  <?=$ender;?>
                  </div></td>
                    
                <td width="7%"><div align="center">  <?=$rst["Qtde_Entrega"];?></div></td>
                <td width="7%"><div align="center">  <?=$rst["Qtde_Trocado"];?></div></td>
                <?php if($_retvieweDev != '1'){ ?>
                <td width="12%"><div align="center">  <?=$rst["Qtde_Devolvido"];?></div></td>
                <?php } ?>
                <td width="11%">
                  <div align="center">
                    <?=($rst["Codigo_Chamada"]);?>
                  </div></td>
                  
        </tr><tr>
              <td height="8" colspan="10"><div class="linha"></div></td>
              </tr>
              
              <?php } 
          
        } //fim status 2
        
        ?>
       
          
</table>


  <table width="100%" border="0" > 
      <tr >
        <td  colspan="4" valign="bottom"><span class="style41">Data Impressão:<?=$data_atual;?></span></td>
        <td  colspan="4" style="text-align: right;"><span class="style41"> <?=$horaatualizada;?> </span></td>    </tr>
      <tr  style="height: 60px;">
        <td height="21" colspan="4" valign="bottom"><span class="style41">__________________________</span></td>
        <td width="61%" colspan="4" valign="bottom">__________________________</td>
      </tr>
      <tr >
        <td colspan="4" valign="top"><span class="style41">Visto Solicitante</span></td>
        <td colspan="4" valign="top">Visto Almoxarifado</td>                
    </tr>
</table>

</body>
