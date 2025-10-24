<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 


$codigoos = $_parametros["chamada"];

$elx = $_POST['acao'];

$pedido = $_GET["pedido"];

$pedido = $_GET["pedido"];



if($codigoos == "") { 

$codigoos = $_GET["codigoos"];

}

$queryPedido = ("Select *,a.usuario_LOGIN as atendente, b.usuario_LOGIN as tecnico,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB,

consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,

situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(DATA_CHAMADA, '%d/%m/%Y') as data1 ,date_format(Data_Nota, '%d/%m/%Y') as datanf,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3 from chamada 

left JOIN usuario as a ON  a.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE

left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao

left JOIN situacaoos_elx  ON COD_SITUACAO_OS = SituacaoOS_Elx

left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR

left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
LEFT JOIN situacao_garantia ON GARANTIA = g_id

WHERE CODIGO_CHAMADA = '$codigoos' ");



$resultPedido = mysqli_query($mysqli,$queryPedido) or die(mysqli_error($mysqli));

$TotalRegPedido = mysqli_num_rows ($resultPedido);

	while($rstPedido = mysqli_fetch_array($resultPedido))						

			{

			

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Prisma - Ordem de Serviço</title>







<style type="text/css">

<!--

table.bordasimples {border-collapse: collapse;}



table.bordasimples tr td {border:1px solid #000000;}

body {

	margin-top: 0px;

}

.style30 {font-family: "Courier New", Courier, monospace; font-size: 16px; }

.style31 {font-family: "Courier New", Courier, monospace; font-weight: bold; }

.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }

.style38 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }

.style39 {font-family: Arial, Helvetica, sans-serif}

.style41 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; font-style: italic; }

.style43 {font-size: 16px}

.style44 {font-size: 8px}

.style45 {font-family: Arial, Helvetica, sans-serif; font-size: 8px; }

.style46 {font-size: 13px}

.style47 {font-size: 12px}

.style48 {font-size: 24px;font-weight: bold;}

.style49 {font-family: "Courier New", Courier, monospace; font-size: 14px; }

.style50 {font-family: "Courier New", Courier, monospace; font-weight: bold; font-size: 14px; }

.style51 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }

.style52 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }

-->

</style>

<body>



<table   width="698" border="0">

  <tr>

    <td width="278" ><div align="left" class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px"><img src="../img/logopequena.jpg"/></span></div></td>

    <td width="450"><div align="right" class="style31 style39" style="margin-right:20px"><span class="style50">TELEFONE: <strong>Fone: (41) 3076 - 8880 e 3076 - 8886</strong><strong>atendimento@beautytech.com.br</strong><br />
          <strong>Rua Alferes Poli, 555 - Rebou&ccedil;as - Curitiba - Pr</strong></span></div></td>

  </tr>

  <tr>

    <td height="21" colspan="2"><div align="center" class="style30 style44">

      <div align="left"><span class="style45">__________________________________________________________________________________________________________________________________________________________________________________</span></div>

    </div></td>

  </tr>

  <tr>

    <td height="48" colspan="2"><table width="732" border="0" align="left">

      <tr>

        <td width="155" class="style38">Ordem Servi&ccedil;o N:</td>

        <td width="87" class="style37">

          <span class="style48"><?=$rstPedido["CODIGO_CHAMADA"];?></span>         </td>

        <td width="154" class="style38"><div align="right">Atendente:</div></td>

        <td class="style37" ><div align="right">

          <div align="left">

            <?=$rstPedido["atendente"];?>

          </div>

        </div>          <div align="right"></div></td>

        <td width="174" class="style37 style46" ><div align="center">

         <strong>

          <?php  if ($rstPedido["GARANTIA"] == 1) { echo "Fora Garantia"; } 
      if($rstPedido["GARANTIA"] == 2) { echo "Garantia de Fabrica";}
      if ($rstPedido["GARANTIA"] == 3) { echo "Garantia de Serviços";}
      if ($rstPedido["GARANTIA"] == 4) { echo "Garantia Estendida";}  ?></strong>

        </div></td>

      </tr>

      <tr>

        <td height="20" class="style38">Data Abertura:</td>

        <td class="style37"><?=$rstPedido["data1"];?>

          <div align="left"></div></td>

        <td class="style37"><div align="right"><span class="style38">Data Encerramento: </span></div></td>

        <td colspan="2" class="style37"><span class="style38">

        

        </span>  <?=$rstPedido["data3"];?></td>

        </tr>

    </table></td>

  </tr>

  <tr>

    <td height="12" colspan="2"><div align="center" class="style45">_______________________________________________________________________________________________________________________________________________________________________________</div></td>

  </tr>

  <tr>

    <td colspan="2"><table width="731" border="0">

      <tr>

        <td width="101" class="style38">Cliente:</td>

        <td width="364" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>

        <td width="73" class="style37"><strong><span class="style38">Telefone:</span></strong></td>

        <td width="169" class="style37"><?=$rstPedido["FONE_RESIDENCIAL"];?>

/

<?=$rstPedido["FONE_CELULAR"];?>

/

<?=$rstPedido["FONE_COMERCIAL"];?>

<?php if($rstPedido["RAMAL"] != "") { echo $rstPedido["RAMAL"]; } ?></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="2"><div align="center" class="style37">

      <div align="center" class="style45">__________________________________________________________________________________________________________________________________________________________________________________</div>

    </div></td>

  </tr>

  <tr>

    <td colspan="2"><table width="735" border="0" cellpadding="0">

      <tr>

        <td width="141" class="style52">Aparelho:</td>

        <td width="115" class="style51"><?=$rstPedido["descA"];?>        </td>

        <td width="55" class="style52">Modelo:</td>

        <td width="116" class="style51"><?=$rstPedido["Modelo"];?></td>

        <td width="64" class="style51"><div align="right"><strong>N&ordm; NF:</strong></div></td>

        <td width="230" class="style51"><?=$rstPedido["Nota_Fiscal"];?>

            <span class="style52">Revenda: </span>

            <?=$rstPedido["Revendedor"];?></td>

      </tr>

      <tr>

        <td class="style52">Marca:</td>

        <td class="style51"><?=$rstPedido["marca"];?></td>

        <td class="style52">S&eacute;rie:</td>

        <td class="style51"><?=$rstPedido["serie"];?>

          &nbsp;&nbsp;</td>

        <td class="style52"><div align="right">Data NF:</div></td>

        <td class="style51"><?=$rstPedido["datanf"];?>

            <span class="style52">CNPJ:

              <?=$rstPedido["cnpj"];?>

          </span></td>

      </tr>

      <tr>

        <td class="style52">Defeito Reclamado:</td>

        <td colspan="5" class="style51" valign="top"><?=$rstPedido["DEFEITO_RECLAMADO"];?>        </td>

      </tr>

      <tr>

        <td class="style52">Condi&ccedil;&otilde;es Produto:</td>

        <td colspan="5" class="style51"><?=$rstPedido["Estado_Aparelho"];?></td>

      </tr>

      <tr>

        <td class="style52">Servi&ccedil;o Executado</td>

        <td colspan="5" class="style51"><span class="style37">

          <?=$rstPedido["SERVICO_EXECUTADO"];?>

        </span></td>

      </tr>

      <tr>

        <td class="style52">Observa&ccedil;&otilde;es:</td>

        <td colspan="5" class="style51"><?=$rstPedido["OBSERVACAO_atendimento"];?>        </td>

      </tr>

      <tr>

        <td class="style52">Acess&oacute;rios</td>

        <td colspan="5" class="style51"><?=$rstPedido["Acessorios"];?></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td height="18" colspan="2"><div align="center" class="style37">

      <div align="center" class="style45">__________________________________________________________________________________________________________________________________________________________________________________</div>

    </div></td>

  </tr>

  <tr>

    <td height="18" colspan="2" valign="top" class="style37"><table width="691" border="0">

      <tr>

        <td width="154" class="style38">Valor Pe&ccedil;a:</td>

        <td width="156" class="style38">R$

          <?=number_format($rstPedido["VALOR_PECA"],2,',','.');?>        </td>

        <td width="95" class="style38">Valor Taxa:</td>

        <td width="268" class="style38">R$

          <?=number_format($rstPedido["TAXA"],2,',','.');?>

          -

          <?=$rstPedido["DESCRICAO_TAXA"];?>        </td>

      </tr>

      <tr>

        <td class="style38">Valor Servi&ccedil;o:</td>

        <td class="style38">R$

          <?=number_format($rstPedido["VALOR_SERVICO"],2,',','.');?>        </td>

        <td class="style38">Desconto:</td>

        <td class="style38">R$

          <?=number_format($rstPedido["DESC_SERVICO"],2,',','.');?>        </td>

      </tr>

      <tr>

        <td class="style38">Valor Total:</td>

        <td class="style38">R$

          <?=number_format(($rstPedido["VALOR_SERVICO"]+$rstPedido["TAXA"]+$rstPedido["VALOR_PECA"]-$rstPedido["DESC_SERVICO"]),2,',','.');?>        </td>

        <td class="style38">&nbsp;</td>

        <td class="style38">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td class="style37">&nbsp;</td>

    <td class="style37">&nbsp;</td>

  </tr>

  <tr>

    <td class="style37">________________________</td>

    <td class="style37">________________________</td>

  </tr>

  <tr>

    <td class="style30"><span class="style37">

      <?=$rstPedido["Nome_Consumidor"];?>

    </span></td>

    <td class="style41">Beauty Tech</td>

  </tr>

  <tr>

    <td colspan="2" class="style30 style39 style47"><br /></td>

  </tr>

</table>



<br />

<table   width="676" border="0">

  <tr>

    <td width="287" ><div align="left" class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px"><img src="../img/logopequena.jpg"/></span></div></td>

    <td width="441"><div align="right" class="style31 style39" style="margin-right:20px"><span class="style50">TELEFONE: <strong>Fone: (41) 3076 - 8880 e 3076 - 8886</strong><strong>atendimento@beautytech.com.br</strong><br />
          <strong>Rua Alferes Poli, 555 - Rebou&ccedil;as - Curitiba - Pr</strong></span></div></td>

  </tr>

  <tr>

    <td height="18" colspan="2"><div align="center" class="style45">__________________________________________________________________________________________________________________________________________________________________________________</div></td>

  </tr>

  <tr>

    <td height="48" colspan="2"><table width="732" border="0" align="left">

      <tr>

        <td width="140" class="style38">Ordem Servi&ccedil;o N:</td>

        <td width="68" class="style37"><span class="style48">

          <?=$rstPedido["CODIGO_CHAMADA"];?>

        </span></td>

        <td width="184" class="style38"><div align="right">Atendente:</div></td>

        <td class="style37" ><div align="right">

            <div align="left">

              <?=$rstPedido["atendente"];?>

            </div>

        </div>          <div align="right"></div></td>

        <td width="164" class="style37 style46" ><div align="center"><strong>

          <?php if($rstPedido["GARANTIA"] == 0) { echo strtoupper("Fora Garantia");}

            if($rstPedido["GARANTIA"] == 1) { echo strtoupper("Garantia de Fabrica");}

         if($rstPedido["GARANTIA"] == 2) { echo strtoupper("Garantia de Servi&ccedil;o");}  ?></strong>

</div></td>

      </tr>

      <tr>

        <td height="20" class="style38">Data Abertura:</td>

        <td class="style37"><?=$rstPedido["data1"];?>

            <div align="left"></div></td>

        <td class="style37"><div align="right"><span class="style38">Data Encerramento: : </span></div></td>

        <td colspan="2" class="style37"><span class="style38"> </span>

            <?=$rstPedido["data3"];?></td>

        </tr>

      

    </table></td>

  </tr>

  <tr>

    <td height="18" colspan="2"><div align="center" class="style37">

      <div align="center" class="style45">__________________________________________________________________________________________________________________________________________________________________________________</div>

    </div></td>

  </tr>

  <tr>

    <td colspan="2"><table width="731" border="0">

      <tr>

        <td width="101" class="style38">Cliente:</td>

        <td width="364" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>

        <td width="73" class="style37"><strong><span class="style38">Telefone:</span></strong></td>

        <td width="169" class="style37"><?=$rstPedido["FONE_RESIDENCIAL"];?>

          /

          <?=$rstPedido["FONE_CELULAR"];?>

          /

          <?=$rstPedido["FONE_COMERCIAL"];?>

          <?php if($rstPedido["RAMAL"] != "") { echo $rstPedido["RAMAL"]; } ?></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td height="18" colspan="2"><div align="center" class="style37">

      <div align="center" class="style45">__________________________________________________________________________________________________________________________________________________________________________________</div>

    </div></td>

  </tr>

  <tr>

    <td colspan="2"><table width="735" border="0" cellpadding="0">

      <tr>

        <td width="141" class="style52">Aparelho:</td>

        <td width="115" class="style51"><?=$rstPedido["descA"];?>        </td>

        <td width="55" class="style52">Modelo:</td>

        <td width="116" class="style51"><?=$rstPedido["Modelo"];?></td>

        <td width="64" class="style51"><div align="right"><strong>N&ordm; NF:</strong></div></td>

        <td width="230" class="style51"><?=$rstPedido["Nota_Fiscal"];?>

            <span class="style52">Revenda: </span>

            <?=$rstPedido["Revendedor"];?></td>

      </tr>

      <tr>

        <td class="style52">Marca:</td>

        <td class="style51"><?=$rstPedido["marca"];?></td>

        <td class="style52">S&eacute;rie:</td>

        <td class="style51"><?=$rstPedido["serie"];?>

          &nbsp;&nbsp;</td>

        <td class="style52"><div align="right">Data NF:</div></td>

        <td class="style51"><?=$rstPedido["datanf"];?>

            <span class="style52">CNPJ:

              <?=$rstPedido["cnpj"];?>

          </span></td>

      </tr>

      <tr>

        <td class="style52">Defeito Reclamado:</td>

        <td colspan="5" class="style51" valign="top"><?=$rstPedido["DEFEITO_RECLAMADO"];?>        </td>

      </tr>

      <tr>

        <td class="style52">Condi&ccedil;&otilde;es Produto:</td>

        <td colspan="5" class="style51"><?=$rstPedido["Estado_Aparelho"];?></td>

      </tr>

      <tr>

        <td class="style52">Servi&ccedil;o Executado</td>

        <td colspan="5" class="style51"><span class="style37">

          <?=$rstPedido["SERVICO_EXECUTADO"];?>

        </span></td>

      </tr>

      <tr>

        <td class="style52">Observa&ccedil;&otilde;es:</td>

        <td colspan="5" class="style51"><?=$rstPedido["OBSERVACAO_atendimento"];?>        </td>

      </tr>

      <tr>

        <td class="style52">Acess&oacute;rios</td>

        <td colspan="5" class="style51"><?=$rstPedido["Acessorios"];?></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td height="18" colspan="2"><div align="center" class="style37">

      <div align="center" class="style45">__________________________________________________________________________________________________________________________________________________________________________________</div>

    </div></td>

  </tr>

  <tr>

    <td height="22" colspan="2" valign="top" class="style37"><table width="691" border="0">

      <tr>

        <td width="154" class="style38">Valor Pe&ccedil;a:</td>

        <td width="156" class="style38">R$

          <?=number_format($rstPedido["VALOR_PECA"],2,',','.');?>        </td>

        <td width="95" class="style38">Valor Taxa:</td>

        <td width="268" class="style38">R$

          <?=number_format($rstPedido["TAXA"],2,',','.');?>

          -

          <?=$rstPedido["DESCRICAO_TAXA"];?>        </td>

      </tr>

      <tr>

        <td class="style38">Valor Servi&ccedil;o:</td>

        <td class="style38">R$

          <?=number_format($rstPedido["VALOR_SERVICO"],2,',','.');?>        </td>

        <td class="style38">Desconto:</td>

        <td class="style38">R$

          <?=number_format($rstPedido["DESC_SERVICO"],2,',','.');?>        </td>

      </tr>

      <tr>

        <td class="style38">Valor Total:</td>

        <td class="style38">R$

          <?=number_format(($rstPedido["VALOR_SERVICO"]+$rstPedido["TAXA"]+$rstPedido["VALOR_PECA"]-$rstPedido["DESC_SERVICO"]),2,',','.');?>        </td>

        <td class="style38">&nbsp;</td>

        <td class="style38">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td class="style37">&nbsp;</td>

    <td class="style37">&nbsp;</td>

  </tr>

  <tr>

    <td class="style37">________________________</td>

    <td class="style37">________________________</td>

  </tr>

  <tr>

    <td class="style30"><span class="style37">

      <?=$rstPedido["Nome_Consumidor"];?>

    </span></td>

    <td class="style41">Beauty Tech</td>

  </tr>

  <tr>

    <td colspan="2" class="style30"><span class="style52">***** Produto n&atilde;o

    retirado c/ 45 (dias) desmanchado p/ doa&ccedil;&atilde;o ******</span></td>

  </tr>

  

  </tr>

</table>

</body>



<?php

}

?>

