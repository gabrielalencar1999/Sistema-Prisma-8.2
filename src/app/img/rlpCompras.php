<?php include("../conexao.php");
$id = $_GET["id"];

 $consulta = "Select * from parametro ";
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
		$num_rows = mysqli_num_rows($executa);
		
		   if($num_rows!=0)
			{
			
				while($rst = mysqli_fetch_array($executa))	{
				$endereco = $rst["ENDERECO"];
				$bairro = $rst["BAIRRO"];
                $cep = $rst["Cep"];
				$cidade = $rst["CIDADE"];
				$estado = $rst["UF"];
				$email = $rst["CGC"];
				$inscricao = $rst["INSC_ESTADUAL"];
				$cnpj = $rst["CGC"];
				$telefone = "(".$rst["DDD"].") ".$rst["TELEFONE"];
				$email = $rst["EMAIL"];
				$site = $rst["site"];
				$fantasia = $rst["NOME_FANTASIA"];
				$razao = $rst["RAZAO_SOCIAL"];

				}}

$query = ("Select Previsao_Entrega,PED_NRO,PED_CONDPGTO,TipoFrete, Data_Alteradouser,PED_FABRICANTE,DATE_FORMAT(PED_DATAEMISSAO, '%d/%m/%Y') AS data, NOME,CGC  from pedido_base 
left join fabricante on   	CODIGO_FABRICANTE = PED_FABRICANTE where PED_NRO = '$id'");
$result = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));
	while($rst = mysqli_fetch_array($result))						
			{
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prisma-Sistema Integrado Gestão - Pedido de Compras</title>



<style type="text/css">
<!--
table.bordasimples {border-collapse: collapse;}

table.bordasimples tr td {border:1px solid #000000;}
body {
	margin-top: 0px;
}
.style30 {font-family: "Courier New", Courier, monospace; font-size: 14px; }
.style31 {font-family: "Courier New", Courier, monospace; font-weight: bold; }
.style32 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; }
.style34 {font-size: 24px}
.style35 {font-size: 14px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; }
.style36 {
	font-size: 12px;
	color: #FF0000;
}
-->
</style>
<body>

<table   width="638" border="0">
  <tr>
    <td width="396" ><div align="left" class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px"><img src="../img/logo2.jpg"  /></span></div></td>
    <td width="334"><div align="right" class="style32" style="margin-right:20px"><span class="style34">N&ordm; <?=$id;?></span><br/>
    DATA:<?=$rst["data"];?></div></td>
  </tr>
  <tr>
    <td height="21" colspan="2"><span class="style30">_______________________________________________________________________________________________</span></td>
  </tr>
  <tr>
    <td height="21" colspan="2"><div align="center" class="style32">ORDEM DE COMPRA</div></td>
  </tr>
  <tr>
    <td height="21" colspan="2"><div align="center" class="style30">_______________________________________________________________________________________________</div></td>
  </tr>
  <tr class="bordasimples">
    <td height="188" colspan="2"><table width="699" border="0" align="center">
      <tr>
        <td class="style35"><?=$razao;?></td>
      </tr>
      <tr>
        <td class="style35">TEL / FAX: <?=$telefone;?> (RAMAL FAX 209)</td>
      </tr>
      <tr>
        <td class="style35"> <?=$endereco;?> - <?=$bairro;?></td>
      </tr>
      <tr>
        <td class="style35">CEP: <?=$cep;?> - <?=$cidade;?> - <?=$estado;?></td>
      </tr>
      <tr>
        <td class="style35">CNPJ: <?=$cnpj;?></td>
      </tr>
      <tr>
        <td class="style35">INSCRI&Ccedil;&Atilde;O ESTADUAL: <?=$inscricao;?></td>
      </tr>
      <tr>
        <td class="style35">&nbsp;</td>
      </tr>
      <tr>
        <td class="style35"><div align="center">AUTORIZAMOS A AQUISI&Ccedil;&Atilde;O ABAIXO DISCRIMINADO</div></td>
      </tr>
    </table></td>
  </tr>
 
  
  
  <tr>
    <td height="74" colspan="2" valign="top"><table width="769" border="0" class="bordasimples">
      <tr>
        <td width="42" class="style35">Itens</td>
        <td width="48" class="style35"><div align="center">Qtde</div></td>
        <td width="361" class="style35"><div align="left"> DESCRI&Ccedil;&Atilde;O DOS PRODUTOS/SERVI&Ccedil;OS</div></td>
        <td width="37" class="style35"><div align="center">%IPI</div></td>
        <td width="87" class="style35"><div align="center">VALOR UN.</div></td>
        <td width="71" class="style35"><div align="right">VL C/ IPI</div></td>
        <td width="93" class="style35"><div align="right">VALOR TOT.</div></td>
      </tr>
      <?php $consultaProd = "Select PED_DESCRICAO,PED_VALUNIT,PERC_IPI,PED_VALUNIT,PERC_IPI,PED_QTDADE,PED_CODIGO from pedido_item
	  left join itemestoque on codigo_fornecedor = PED_CODIGO  where PED_NRO = '$id'";
          $executaProd = mysqli_query($mysqli,$consultaProd) or die(mysqli_error($mysqli));
		  $item = 0;
		   while($rstProd = mysqli_fetch_array($executaProd))						
			{
			$item++;
			?>
      <tr>
        <td class="style35"><?=$item;?></td>
        <td class="style35"><div align="center">
          <?=$rstProd["PED_QTDADE"];?>
        </div></td>
        <td class="style35"><?=$rstProd["PED_DESCRICAO"];?></td>
        <td class="style35"><div align="center"><?=$rstProd ["PERC_IPI"];?></div></td>
        <td class="style35"><div align="right">
          <?=number_format($rstProd["PED_VALUNIT"],2,',','.');?>
        </div></td>
        <td class="style35"><div align="right"><?=number_format($rstProd["PED_VALUNIT"],2,',','.');?></div></td>
        <td class="style35"><div align="right"> <?=number_format($rstProd["PED_VALUNIT"]*$rstProd["PED_QTDADE"],2,',','.');
       
       $totalgeral = $totalgeral + ($rstProd["PED_VALUNIT"]*$rstProd["PED_QTDADE"]);?></div></td>
      </tr>
     <?php }?>
      <tr>
        <td colspan="6" class="style35"><div align="right"><strong>TOTAL GERAL:</strong></div></td>
        <td class="style35"><div align="right"><?=number_format($totalgeral,2,',','.');?></div></td>
      </tr>
    </table></td>
  </tr>

  <tr>
    <td colspan="2" class="style30"><table width="764" border="0">
      <tr>
        <td colspan="6" class="style35">PRAZO DE ENTREGA:<?=$rst["Previsao_Entrega"];?> </td>
        </tr>
      <tr>
        <td colspan="2" class="style35">PRAZO/ CONDI&Ccedil;&Otilde;ES  DE PAGAMENTO:<?=$rst["PED_CONDPGTO"];?></td>
        <td colspan="4" class="style35">FRETE:<?=$rst["TipoFrete"];?></td>
        </tr>
      <tr>
        <td colspan="6" class="style35">EMPRESA:<?=$rst["NOME"];?> </td>
        </tr>
      <tr>
        <td width="272" class="style35">CNPJ:<?=$rst["CGC"];?></td>
        <td colspan="5" class="style35">CASO O FORNECEDOR SEJA SUPER SIMPLES, FATURAR EM NOME DE </td>
        </tr>
      <tr>
        <td class="style35">&nbsp;</td>
        <td colspan="5" class="style35">JOS&Eacute; CARLOS M. DA SILVA ME</td>
        </tr>
      <tr>
        <td class="style35">&nbsp;</td>
        <td colspan="5" class="style35">CNPJ: 01305421/0001-93 INSCRI&Ccedil;&Atilde;O ESTADUAL: 90408789-01</td>
        </tr>
      <tr>
        <td class="style35">&nbsp;</td>
        <td colspan="5" class="style35">RUA FRANCISCO NUNES, 812 - REBOU&Ccedil;AS CEP: 80.215-000</td>
        </tr>
      <tr>
        <td class="style35">&nbsp;</td>
        <td width="147" class="style35">&nbsp;</td>
        <td width="82" class="style35">&nbsp;</td>
        <td width="128" class="style35">&nbsp;</td>
        <td width="7" class="style35">&nbsp;</td>
        <td width="102" class="style35">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" class="style35">FEITO POR:<?=$rst["Data_Alteradouser"];?></td>
        </tr>
      <tr>
        <td height="30" colspan="2" class="style35">VENDEDOR/REPRESENTANTE: <?=$rst[""];?></td>
        <td colspan="4" class="style35">Assinatura:_____________________________</td>
        </tr>
      <tr>
        <td class="style35">&nbsp;</td>
        <td class="style35">&nbsp;</td>
        <td colspan="4" class="style35 style36">FAVOR ASSINAR ESTA O.C. E PASSAR POR E-MAIL OU FAX</td>
        </tr>
      <tr>
        <td height="41" class="style35">AUTORIZADO POR:</td>
        <td class="style35">&nbsp;</td>
        <td colspan="4" class="style35">Assinatura:_____________________________</td>
        </tr>
    </table></td>
  </tr>
</table>

<p><br>
</p>
</body>

<?php
}
?>
