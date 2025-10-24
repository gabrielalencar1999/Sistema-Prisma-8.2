<?php session_start();

//	require_once('../../../api/config/config.inc.php');
//	require '../../../api/vendor/autoload.php';
//	include("../../../api/config/iconexao.php");   

	require_once('../../../api/config/config.inc.php');
	require '../../../api/vendor/autoload.php';
	include("../../../api/config/iconexao.php");   

	include('../libs/phpqrcode/qrlib.php'); 
	
	// $chaveAcesso = $_SESSION['chave_loja'];

	use Database\MySQL;

	$pdo = MySQL::acessabd();
	

$pedido = $_GET["pedido"];
$livro = $_GET['livro'];
$idcliente = $_GET['idcliente'];

if($livro == "") { 
	$livro = $_GET['caixa'];
}

if($idcliente != "") { 
	$idcliente = "AND CODIGO_CLIENTE = '$idcliente'";
}

$livro = 1;

	//$pedido = $_SESSION['numberPedido'];
	$_idfrefGO = $_parametros["_ref"];
	
	$_idfref = base64_decode($_idfrefGO);

	$_idfref = explode('-',$_idfref);
	if($_idfref[1] != "") { 
		$pedido = $_idfref[1];
		$numero_pedido= $_idfref[1];
	}else{
		$pedido = $_SESSION['numberPedido'];
	
	}

	$empresa_id = 1;
	$sql = "Select arquivo_logo_base64 from " . $_SESSION['BASE'] . ".empresa where empresa_id = '$empresa_id'";
	$stm = $pdo->prepare($sql);
	$stm->execute();

	if ($stm->rowCount() > 0 ){
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rst){	
		
			$img_logo = $rst['arquivo_logo_base64'];
		
		}
	}


  $consulta = "Select * from parametro ";

$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 

		$num_rows = mysqli_num_rows($executa);

		

		   if($num_rows!=0)

			{

			

				while($rst = mysqli_fetch_array($executa))	{
				
				$id_parametro = $rst["id"];
				
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
				
				



				}}

				

$queryPedido = ("SELECT NUMERO, CODIGO_CLIENTE, DATE_FORMAT( saidaestoque.DATA_CADASTRO, '%d/%m/%Y' ) AS dtCADASTRO, saidaestoque.Cod_Situacao, Descricao, usuario_NOME, COD_Vendedor, Valor_Entrada, Tipo_Pagamento_Entrada, COND_PAGTO, Vl_Pedido, DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS dt1, DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS dt2, DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS dt3, DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS dt4, DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS dt5, DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS dt6, Valor_Venc1, Valor_Venc2, Valor_Venc3, Valor_Venc4, Valor_Venc5, Valor_Venc6, Nome_Consumidor,COMPLEMENTO,NOME_RECADO, CGC_CPF,OBSERVACAO,consumidor.CIDADE,BAIRRO,Nome_Rua,CEP,UF,INSCR_ESTADUAL,Num_Rua,Fax,FONE_RESIDENCIAL,FONE_CELULAR,DDD,FONE_COMERCIAL,CGC_CPF,Tipo_Pagamento,VL_Pedido,Nome_Fantasia
,VL_DESCONTO, DATE_FORMAT( DATA_ENTREGA, '%d/%m/%Y' ) AS dataentrega, obs_pedido,saidaestoque.DATA_CADASTRO as dtcad
FROM saidaestoque

LEFT JOIN usuario ON usuario_CODIGOUSUARIO = COD_Vendedor

LEFT JOIN situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao

LEFT JOIN consumidor ON CODIGO_CONSUMIDOR = CODIGO_CLIENTE

WHERE NUMERO = '$pedido' and num_livro = '$livro'  ");

$resultPedido = mysqli_query($mysqli,$queryPedido) or die(mysqli_error($mysqli));

$TotalRegPedido = mysqli_num_rows ($resultPedido);

	while($rstPedido = mysqli_fetch_array($resultPedido))						

			{
			$desconto = $rstPedido['VL_DESCONTO'];

			$obs = $rstPedido["obs_pedido"];

			$DT = $rstPedido["dtcad"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional/ EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Prisma  - Pedido</title>







<style type="text/css">

<!--

.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

table.bordasimples {border-collapse: collapse;}



table.bordasimples tr td {border:1px solid #000000;}

body {

	margin-top: 0px;

}

.style29 {font-family: "Courier New", Courier, monospace}
.style32 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style55 {font-weight: bold}
.style56 {font-weight: bold}
.style57 {font-weight: bold}
.style58 {font-weight: bold}
.style59 {font-weight: bold}
.style60 {font-weight: bold}
.style61 {font-family: "Courier New", Courier, monospace; font-size: 14px; }

-->

</style>

<body>



<table   width="701" border="0">

  <tr>

    <td width="477" class="style5" ><div align="left" style="margin-left:5px"><strong><span style="margin-right:20px"><strong>
    <img src="data:image/png;base64, <?=$img_logo?>" width="100px"/>
	  
      </strong></span><span style="margin-left:5px"><strong>

  <br />
  

    </strong></span></strong></div>      </td>

    <td width="214" class="style5" ><div align="right"><span style="margin-left:5px; font-weight: bold;">TELEFONE: <?=$telefone;?><br /> <?=$site;?><br />  <?=$email;?></span></div></td>
  </tr>

  <tr>

    <td colspan="2" class="style5"><?=$endereco;?>

      &nbsp;&nbsp;Bairro:

      <?=$bairro;?>

      &nbsp;&nbsp;&nbsp;&nbsp; CEP:

      <?=$cep;?>

      &nbsp;&nbsp;

      <?=$cidade;?>

      -

      <?=$estado;?></td>
  </tr>

  <tr>

    <td class="style5">CNPJ:

      <?=$cnpj;?></td>

    <td class="style5"><div align="right">INSC.EST:

      <?=$inscricao;?>

    </div></td>
  </tr>
  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>
  <tr>

    <td height="108" colspan="2" class="style5"><table width="705" border="0" align="center" cellpadding="0">

      <tr>

        <td width="150" ><strong>Pedido N:</strong></td>

        <td width="94"><?=$rstPedido["NUMERO"];?></td>

        <td colspan="2"><strong>Data Entrega</strong>:
          <?=$rstPedido["dataentrega"];?>          <div align="right"></div></td>

        <td colspan="3" ><div align="right">
          <?=$rstPedido["Descricao"];?>
        </div></td>
        </tr>

      <tr>

        <td><strong>Data:</strong></td>

        <td colspan="2"><?=$rstPedido["dtCADASTRO"];?></td>

        <td colspan="4"><strong>
          <?php if($rstPedido["dt1"] != "00/00/0000") { echo "Vencimentos Pedido"; }?>
        </strong></td>
        </tr>

      <tr>

        <td><strong>Vendedor:</strong></td>

        <td colspan="2"><?=$rstPedido["usuario_NOME"];?></td>

        <td width="84"><div align="center"><?php if($rstPedido["dt1"] != "00/00/0000") { echo $rstPedido["dt1"]; }?></div></td>

        <td width="79"><div align="left"> <?php if($rstPedido["dt1"] != "00/00/0000") { echo "R$ ".number_format($rstPedido["Valor_Venc1"],2,',','.'); }?></div></td>

        <td width="88">&nbsp;</td>

        <td width="76">&nbsp;</td>
      </tr>

      <tr>

        <td><strong><strong>Condi&ccedil;&atilde;o de Pagto:</strong></strong></td>

        <td colspan="2"><?php $condicao = $rstPedido["Tipo_Pagamento"];

		$sql="SELECT nome FROM tiporecebimpgto where id = '$condicao' " ;            

        $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

	   while($rst = mysqli_fetch_array($resultado)){

	   $desc = $rst["nome"];

	   echo "$desc";

	

	   }?></td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>
      </tr>

      <tr>

        <td><strong>Observa&ccedil;&atilde;o:</strong></td>

        <td colspan="6"><strong><?=$obs;?></strong><br /><?=$rstPedido["OBSERVACAO"];?></td>
        </tr>

    </table></td>
  </tr>

  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>
  <tr>

    <td colspan="2" class="style5"><table width="713" cellpadding="0" order="0">

      <tr>

        <td width="142"><strong>Cliente:</strong></td>

        <td colspan="5">

          <strong><?php echo $rstPedido["Nome_Consumidor"];?></strong> <?php if($rstPedido["Nome_Consumidor"] != ""){ echo "( ".$rstPedido["Nome_Fantasia"]." )"; }?></td>
        </tr>

      <tr>

        <td><strong>Telefone:</strong></td>

        <td colspan="3">(<?=$rstPedido["DDD"];?>) <?=$rstPedido["FONE_RESIDENCIAL"];?> / <?=$rstPedido["FONE_CELULAR"];?> / <?=$rstPedido["FONE_COMERCIAL"];?></td>

        <td width="35"><strong></strong></td>

        <td width="134"><?=$rstPedido["Fax"];?></td>
      </tr>

      <tr>

        <td><strong>Endere&ccedil;o:</strong></td>

        <td colspan="5"><?=$rstPedido["Nome_Rua"];?>-<?=$rstPedido["Num_Rua"];?>-<?=$rstPedido["COMPLEMENTO"];?></td>
        </tr>

      <tr>

        <td><strong>Bairro:</strong></td>

        <td colspan="3"><?=$rstPedido["BAIRRO"];?></td>

        <td><strong>CEP:</strong></td>

        <td><?=$rstPedido["CEP"];?></td>
      </tr>

      <tr>

        <td><strong>Cidade:</strong></td>

        <td width="192"><?=$rstPedido["CIDADE"];?></td>

        <td width="63"><strong>UF:</strong></td>

        <td colspan="3"><?=$rstPedido["UF"];?></td>
        </tr>

      <tr>

        <td><strong>CNPJ/CPF:</strong></td>

        <td><?=$rstPedido["CGC_CPF"];?></td>

        <td><strong>Inscr.Est:</strong></td>

        <td colspan="3"><?=$rstPedido["INSCR_ESTADUAL"];?>- Contato:<?=$rstPedido["NOME_RECADO"];?> </td>
        </tr>

    </table></td>
  </tr>

  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>

  <tr>

    <td height="69" colspan="2" class="style5">

<table width="706" border="0">

        <tr> 

          <td width="89" class="style55"><div align="left">Codigo</div></td>

          <td width="44" class="style56"><div align="center">Qtde</div></td>

          <td width="45" class="style57"><div align="center">Unid.</div></td>

          <td width="291" class="style58"><div align="left">Descri&ccedil;&atilde;o</div></td>

          <td width="122" class="style59"><div align="right">Vlr Unit</div></td>

          <td width="89" class="style60"><div align="right">Total</div></td>
          <td width="47" class="style60"><div align="center">Troca</div></td>
        </tr>

        <?php $consulta = "Select ind_troca,CODIGO_ITEM ,QUANTIDADE,DESCRICAO_ITEM,VALOR_UNIT_DESC,VALOR_TOTAL,UNIDADE_MEDIDA 
		from saidaestoqueitem left join itemestoque on CODIGO_FORNECEDOR = CODIGO_ITEM 
		where NUMERO = '$pedido' and num_livro = '$livro'  order by saidaestoqueitem.CODIGO_ITEM";

          $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));

		   while($rst = mysqli_fetch_array($executa))						

			{
					  $_troca = "";
						if($rst['ind_troca'] == '-1' ) { 
						$_troca = "Troca";}

			?>

        <tr> 

          <td>

            <?=$rst["CODIGO_ITEM"];?>          </td>

          <td><div align="center">

              <?=$rst["QUANTIDADE"]; ?>  <?php $qtde = $qtde + $rst["QUANTIDADE"]; ?>

            </div></td>

              <td><div align="center">

                <?=$rst["UNIDADE_MEDIDA"];?>

          </div></td>

          <td>

            <?=$rst["DESCRICAO_ITEM"];?>          </td>

          <td><div align="right">

              <?=number_format($rst["VALOR_UNIT_DESC"],2,',','.');?>

            </div></td>

          <td><div align="right"> 

              <?=number_format($rst["VALOR_TOTAL"],2,',','.');

                      $totalgeral = $totalgeral + $rst["VALOR_TOTAL"];?>

            </div></td>
          <td><div align="center">
            <?=$_troca;?>
          </div></td>
        </tr>

        <?php }?>

        <tr> 

          <td height="21">

<div align="center">Total Qtde</div></td>

          <td> 

            <div align="center"><?php echo "$qtde";?></div></td>

          <td>&nbsp;</td>

          <td><div align="right"></div></td>

          <td><div align="right"><strong>Sub Total:</strong></div></td>
              <td><div align="right">

              <?=number_format($totalgeral,2,',','.');?>

            </div></td>
              <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right">
            <div align="right"><strong>Desconto:</strong></div>
          </div></td>
           <td><div align="right">
              <?=number_format($desconto,2,',','.');?>
          </div></td>
           <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right"></div></td>
          <td><div align="right"><strong>Total:</strong></div></td>
              <td><div align="right">
              <?=number_format(($totalgeral-$desconto),2,',','.');?>
          </div></td>
              <td>&nbsp;</td>
        </tr>
    </table></td>
  </tr>

  <tr>

    <td colspan="2" class="style5"><strong> 

      <?php if($rstPedido["VL_DESCONTO"] > 0 ) { ?>

      Desconto R$: 

      <?=number_format($rstPedido["VL_DESCONTO"],2,',','.'); } if($rstPedido["VL_DESCONTO_porc"] > 0) { ?>

      Desconto%: 

      <?=number_format($rstPedido["VL_DESCONTO_porc"],2,',','.'); }?>

      </strong></td>
  </tr>

  <tr>

    <td colspan="2" class="style5">&nbsp;</td></tr>

  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>


  <tr>

    <td height="40" colspan="2" valign="top" class="style32">[ ]A vista &nbsp;  &nbsp; &nbsp;[ ]Boleto&nbsp;  &nbsp; &nbsp; [ ]Cheque [ ]N&Atilde;O PAGO Motivo:</td>
  </tr>

  <tr>

 

    <td colspan="2" class="style32">&quot;Conferido pedido (produto em perfeito estado), me responsabilizo no ressarcimento por danos ou extravio.<br />

Ass.Transportador____________________  Ass. Recebimento._________________ Data: _ _ _/_ _ _/_ _ _ _ </td>
  </tr>

  <tr>

    <td height="39" colspan="2" class="style61">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </td>
  </tr>

  

  <tr>

    <td class="style29 style32"><strong>Pedido N:

      <?=$rstPedido["NUMERO"];?>

      <strong>Data:

      <?=$rstPedido["dtCADASTRO"];?>
          </strong></strong></td>

    <td class="style32"> [ ]A vista &nbsp;  &nbsp; &nbsp;[ ]Boleto&nbsp;  &nbsp; &nbsp; [ ]Cheque </td>
  </tr>

  <tr>

    <td class="style32"><strong>Valor Total Pedido:</strong>      <?=number_format($totalgeral-$desconto,2,',','.');?></td>

    <td class="style32">[ ]N&Atilde;O PAGO Motivo:</td>
  </tr>

  <tr>

    <td colspan="2" class="style32"><strong>Condi&ccedil;&atilde;o de Pagto:</strong>      <?php $condicao = $rstPedido["Tipo_Pagamento"];

		$sql="SELECT nome FROM tiporecebimpgto where id = '$condicao' " ;            

        $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

	   while($rst = mysqli_fetch_array($resultado)){

	   $desc = $rst["nome"];

	   echo "$desc";

	

	   }?> - 

      <?php if($rstPedido["dt1"] != "00/00/0000") { echo $rstPedido["dt1"]; }?>

    /

    <?php if($rstPedido["dt1"] != "00/00/0000") { echo "R$ ".number_format($rstPedido["Valor_Venc1"],2,',','.'); }?>    </td>
  </tr>

  <tr>

    <td colspan="2" class="style32"><strong>Cliente :

        <?=$rstPedido["Nome_Consumidor"];?>

    </strong></td>
  </tr>

  <tr>

    <td colspan="2" class="style32">&quot;Conferido pedido (produto em perfeito estado), me responsabilizo no ressarcimento por danos ou extravio.<br />

Ass.Transportador____________________  Ass. Recebimento._________________  Data: _ _ _/_ _ _/_ _ _ _</td>
  </tr>

  <tr>

    <td colspan="2" class="style32">&nbsp;</td>
  </tr>

  <tr>

    <td class="style32">&nbsp;</td>

    <td class="style32">&nbsp;</td>
  </tr>
</table>



<p class="style5"><br>
</p>
</body>



<?php

}

?>

