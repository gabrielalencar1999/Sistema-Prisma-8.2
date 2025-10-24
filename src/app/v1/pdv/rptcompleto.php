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
        $nmro = $rst["NumRua"];
        $Complemento_Endereco = $rst["Complemento_Endereco"];

				$bairro = $rst["BAIRRO"];

		        $cep = $rst["Cep"];

				$cidade = $rst["CIDADE"];

				$estado = $rst["UF"];

				$email = $rst["CGC"];

				$inscricao = $rst["INSC_ESTADUAL"];

				$cnpj = $rst["CGC"];

				$telefone = $rst["TELEFONE"];

				$email = $rst["EMAIL"];

				$site = $rst["site"];

				$fantasia = $rst["NOME_FANTASIA"];
				
      	$MENSAGEM = $rst["Msg_E"];

				$_2via = $rst['imprime_segviaVenda'];
		
				}}

        	//entrada
	$_valorentrada = 0;
	$sql = "select Valor_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO =  '$pedido' and Valor_Entrada > 0";	
  $resultE= mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  while($rstentrada = mysqli_fetch_array($resultE))		
  {
    $_valorentrada = $rstentrada['Valor_Entrada'];
  }

				
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
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;

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

    <td colspan="2" class="style5"><?=$endereco;?> Nº <?=$nmro;?> <?=$Complemento_Endereco;?>

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

    <td height="44" colspan="2" class="style5"><table width="705" border="0" align="center" cellpadding="0">

      <tr>

        <td width="94" class="style32" ><strong>Pedido N:</strong></td>

        <td width="133" ><?=$rstPedido["NUMERO"];?></td>

        <td colspan="2" class="style32"><span ><strong>Dt Emiss&atilde;o</strong>:</span>
    <?=$rstPedido["dtCADASTRO"];?><div align="right"></div></td>

        <td width="166" ><div align="left">
          <span class="style32"><strong>Vendedor:</strong>
          <?=$rstPedido["usuario_NOME"];?>
          </span></div></td>
        <td width="121" ><?=$rstPedido["Descricao"];?></td>
        </tr>

    

      <tr>
        
        <td class="style32"><strong>Observa&ccedil;&atilde;o:</strong></td>
        
        <td colspan="5" class="style32"><strong>
          <?=$obs;?>
        </strong>
          <?=$rstPedido["OBSERVACAO"];?>
        </td>
      </tr>

    </table></td>
  </tr>

  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>
  <tr>

    <td colspan="2" class="style5"><table width="713" cellpadding="0" order="0">

      <tr>

        <td width="68" class="style32"><strong>Cliente:</strong></td>

        <td colspan="3" class="style32">
          
          <strong><?php echo $rstPedido["Nome_Consumidor"];?></strong> <?php if($rstPedido["Nome_Fantasia"] != ""){ echo "( ".$rstPedido["Nome_Fantasia"]." )"; }?></td>
        <td><span class="style32"><strong>CNPJ/CPF:</strong></span></td>
        <td class="style32"><?=$rstPedido["CGC_CPF"];?></td>
        </tr>

      <tr>

        <td class="style32"><strong>Telefone:</strong></td>

        <td colspan="3" class="style32">(<?=$rstPedido["DDD"];?>) <?=$rstPedido["FONE_RESIDENCIAL"];?> / <?=$rstPedido["FONE_CELULAR"];?> / <?=$rstPedido["FONE_COMERCIAL"];?></td>

        <td width="59"><strong><span class="style32"><strong>Inscr.Est:</strong></span></strong></td>

        <td width="134" class="style32"><?=$rstPedido["INSCR_ESTADUAL"];?></td>
      </tr>

      <tr>

        <td class="style32"><strong>Endere&ccedil;o:</strong></td>

        <td colspan="5" class="style32"><?=$rstPedido["Nome_Rua"];?>-<?=$rstPedido["Num_Rua"];?>-<?=$rstPedido["COMPLEMENTO"];?></td>
        </tr>

      <tr>

        <td class="style32"><strong>Bairro:</strong></td>

        <td width="266" class="style32"><?=$rstPedido["BAIRRO"];?></td>
        <td width="42" class="style32"><span class="style32"><strong>Cidade:</strong></span></td>
        <td width="128" class="style32"><?=$rstPedido["CIDADE"];?>
          <span class="style32"><strong>UF: </strong>
          <?=$rstPedido["UF"];?>
         </span></td>

        <td class="style5" align="right"><strong>CEP:</strong></td>

        <td class="style32"><?=$rstPedido["CEP"];?></td>
      </tr>

    </table></td>
  </tr>

  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>

  <tr>

    <td height="69" colspan="2" class="style5">

<table width="706" border="0">

        <tr class="style32"> 

          <td width="89" class="style55"><div align="left"><span class="style32">Codigo</span></div></td>

          <td width="44" class="style56"><div align="center"><span class="style32">Qtde</span></div></td>

          <td width="45" class="style57"><div align="center"><span class="style32">Unid.</span></div></td>

          <td width="291" class="style58"><div align="left"><span class="style32">Descri&ccedil;&atilde;o</span></div></td>

          <td width="122" class="style59"><div align="right"><span class="style32">Vlr Unit</span></div></td>

          <td width="89" class="style60"><div align="right"><span class="style32">Total</span></div></td>
          <td width="47" class="style60"><div align="center"><span class="style32">Ende.</span></div></td>
        </tr>

        <?php $consulta = "Select ind_troca,CODIGO_ITEM ,QUANTIDADE,DESCRICAO_ITEM,VALOR_UNIT_DESC,VALOR_TOTAL,UNIDADE_MEDIDA,CODIGO_FABRICANTE 
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

            <span class="style32">
            <?=$rst["CODIGO_FABRICANTE"];?>          
            </span></td>

          <td><div align="center">

              <span class="style32">
              <?=$rst["QUANTIDADE"]; ?>  
              <?php $qtde = $qtde + $rst["QUANTIDADE"]; ?>

            </span></div></td>

              <td><div align="center">

                <span class="style32">
                <?=$rst["UNIDADE_MEDIDA"];?>

          </span></div></td>

          <td>

            <span class="style32">
            <?=$rst["DESCRICAO_ITEM"];?>          
            </span></td>

          <td><div align="right">

              <span class="style32">
              <?=number_format($rst["VALOR_UNIT_DESC"],2,',','.');?>

            </span></div></td>

          <td><div align="right"> 

              <span class="style32">
              <?=number_format($rst["VALOR_TOTAL"],2,',','.');

                      $totalgeral = $totalgeral + $rst["VALOR_TOTAL"];?>

            </span></div></td>
          <td><div align="center"><span class="style32"> <?php
           if($row["ENDERECO1"] != ""){
                $ender = $row["ENDERECO1"];
                if(substr($row["ENDERECO1"],0,1) == "R"){
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
			echo  $ender;?>
          </span></div></td>
        </tr>

        <?php }?>

        <tr> 

          <td height="21">

<div align="center"><span class="style32">Total Qtde</span></div></td>

          <td> 

            <div align="center"><span class="style32"><?php echo "$qtde";?></span></div></td>

          <td>&nbsp;</td>

          <td><div align="right"></div></td>

          <td><div align="right"><span class="style32"><strong>Sub Total:</strong></span></div></td>
              <td><div align="right">

                <span class="style32">
                <?=number_format($totalgeral,2,',','.');?>

            </span></div></td>
              <td>&nbsp;</td>
        </tr>
      
        <?php if($_valorentrada > 0 ) { ?>
          <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right">
            <div align="right"><span class="style32"><strong>Entrada:</strong></span></div>
          </div></td>
           <td><div align="right">
              <span class="style32">
              <?=number_format($_valorentrada,2,',','.');?>
          </span></div></td>
           <td>&nbsp;</td>
        </tr>
        <?php } 
        if($desconto > 0 ) { ?>
          <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right">
            <div align="right"><span class="style32"><strong>Desconto:</strong></span></div>
          </div></td>
           <td><div align="right">
              <span class="style32">
              <?=number_format($desconto,2,',','.');?>
          </span></div></td>
           <td>&nbsp;</td>
        </tr>
        <?php } ?>
       
        <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right"></div></td>
          <td><div align="right"><span class="style32"><strong>Total:</strong></span></div></td>
              <td><div align="right">
                <span class="style32">
                <?=number_format(($totalgeral-$desconto-$_valorentrada),2,',','.');?>
          </span></div></td>
              <td>&nbsp;</td>
        </tr>
    </table></td>
  </tr>

  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>


  <tr>

 

    <td colspan="2" class="style32"><?=$MENSAGEM;?><br /><br /><br />

Ass.Cliente____________________   Data: _ _ _/_ _ _/_ _ _ _ </td>
  </tr>

 
</table>


<?php if( $_2via == 1) { 
    $totalgeral = 0;
    $qtde = 0;
  
  ?>

<table   width="701" border="0">
  <tr>
    <td width="477" class="style5" ><div align="left" style="margin-left:5px"><strong><span style="margin-right:20px"><strong> <img src="data:image/png;base64, <?=$img_logo?>" width="100px"/> </strong></span><strong> <br />
    </strong></strong></div></td>
    <td width="214" class="style5" ><div align="right"><span style="margin-left:5px; font-weight: bold;">TELEFONE:
      <?=$telefone;?>
      <br />
      <?=$site;?>
      <br />
      <?=$email;?>
    </span></div></td>
  </tr>
  <tr>
    <td colspan="2" class="style5"><?=$endereco;?> Nº <?=$nmro;?> <?=$Complemento_Endereco;?>
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
    <td height="44" colspan="2" class="style5"><table width="705" border="0" align="center" cellpadding="0">
      <tr>
        <td width="94" class="style32" ><strong>Pedido N:</strong></td>
        <td width="133" ><?=$rstPedido["NUMERO"];?></td>
        <td colspan="2" class="style32"><span ><strong>Dt Emiss&atilde;o</strong>:</span>
          <?=$rstPedido["dtCADASTRO"];?>
          <div align="right"></div></td>
        <td width="166" ><div align="left"> <span class="style32"><strong>Vendedor: </strong>
          <?=$rstPedido["usuario_NOME"];?>
       </span></div></td>
        <td width="121" ><?=$rstPedido["Descricao"];?></td>
      </tr>
      <tr>
        <td class="style32"><strong>Observa&ccedil;&atilde;o:</strong></td>
        <td colspan="5" class="style32"><strong>
          <?=$obs;?>
          </strong>
          <?=$rstPedido["OBSERVACAO"];?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>
  <tr>
    <td colspan="2" class="style5"><table width="713" cellpadding="0" order="0">
      <tr>
        <td width="68" class="style32"><strong>Cliente:</strong></td>
        <td colspan="3" class="style32"><strong><?php echo $rstPedido["Nome_Consumidor"];?></strong>
          <?php if($rstPedido["Nome_Fantasia"] != ""){ echo "( ".$rstPedido["Nome_Fantasia"]." )"; }?></td>
        <td><span class="style32"><strong>CNPJ/CPF:</strong></span></td>
        <td class="style32"><?=$rstPedido["CGC_CPF"];?></td>
      </tr>
      <tr>
        <td class="style32"><strong>Telefone:</strong></td>
        <td colspan="3" class="style32">(
          <?=$rstPedido["DDD"];?>
          )
          <?=$rstPedido["FONE_RESIDENCIAL"];?>
          /
          <?=$rstPedido["FONE_CELULAR"];?>
          /
          <?=$rstPedido["FONE_COMERCIAL"];?></td>
        <td width="59"><strong><span class="style32"><strong>Inscr.Est:</strong></span></strong></td>
        <td width="134" class="style32"><?=$rstPedido["INSCR_ESTADUAL"];?></td>
      </tr>
      <tr>
        <td class="style32"><strong>Endere&ccedil;o:</strong></td>
        <td colspan="5" class="style32"><?=$rstPedido["Nome_Rua"];?>
          -
          <?=$rstPedido["Num_Rua"];?>
          -
          <?=$rstPedido["COMPLEMENTO"];?></td>
      </tr>
      <tr>
        <td class="style32"><strong>Bairro:</strong></td>
        <td width="266" class="style32"><?=$rstPedido["BAIRRO"];?></td>
        <td width="42" class="style32"><strong>Cidade:</strong></td>
        <td width="128" class="style32"><?=$rstPedido["CIDADE"];?>
          <strong>UF: </strong>
          <?=$rstPedido["UF"];?></td>
        <td class="style5" align="right"><strong>CEP:</strong></td>
        <td class="style32"><?=$rstPedido["CEP"];?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>
  <tr>
    <td height="69" colspan="2" class="style5"><table width="706" border="0">
      <tr class="style32">
        <td width="89" class="style55"><div align="left">Codigo</div></td>
        <td width="44" class="style56"><div align="center">Qtde</div></td>
        <td width="45" class="style57"><div align="center">Unid.</div></td>
        <td width="291" class="style58"><div align="left">Descri&ccedil;&atilde;o</div></td>
        <td width="122" class="style59"><div align="right">Vlr Unit</div></td>
        <td width="89" class="style60"><div align="right">Total</div></td>
        <td width="47" class="style60"><div align="center">Ende.</div></td>
      </tr>
      <?php $consulta = "Select ind_troca,CODIGO_ITEM ,QUANTIDADE,DESCRICAO_ITEM,VALOR_UNIT_DESC,VALOR_TOTAL,UNIDADE_MEDIDA,CODIGO_FABRICANTE 
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
        <td><span class="style32">
          <?=$rst["CODIGO_FABRICANTE"];?>
        </span></td>
        <td><div align="center"> <span class="style32">
          <?=$rst["QUANTIDADE"]; ?>
          <?php $qtde = $qtde + $rst["QUANTIDADE"]; ?>
        </span></div></td>
        <td><div align="center"> <span class="style32">
          <?=$rst["UNIDADE_MEDIDA"];?>
        </span></div></td>
        <td><span class="style32">
          <?=$rst["DESCRICAO_ITEM"];?>
        </span></td>
        <td><div align="right"> <span class="style32">
          <?=number_format($rst["VALOR_UNIT_DESC"],2,',','.');?>
        </span></div></td>
        <td><div align="right"> <span class="style32">
          <?=number_format($rst["VALOR_TOTAL"],2,',','.');

                      $totalgeral = $totalgeral + $rst["VALOR_TOTAL"];?>
        </span></div></td>
        <td><div align="center"><span class="style32">
          <?php
           if($row["ENDERECO1"] != ""){
                $ender = $row["ENDERECO1"];
                if(substr($row["ENDERECO1"],0,1) == "R"){
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
			echo  $ender;?>
        </span></div></td>
      </tr>
      <?php }?>
      <tr>
        <td height="21"><div align="center"><span class="style32">Total Qtde</span></div></td>
        <td><div align="center"><span class="style32"><?php echo "$qtde";?></span></div></td>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td><div align="right"><span class="style32"><strong>Sub Total:</strong></span></div></td>
        <td><div align="right"> <span class="style32">
          <?=number_format($totalgeral,2,',','.');?>
        </span></div></td>
        <td>&nbsp;</td>
      </tr>
      <?php if($_valorentrada > 0 ) { ?>
          <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right">
            <div align="right"><span class="style32"><strong>Entrada:</strong></span></div>
          </div></td>
           <td><div align="right">
              <span class="style32">
              <?=number_format($_valorentrada,2,',','.');?>
          </span></div></td>
           <td>&nbsp;</td>
        </tr>
        <?php } 
        if($desconto > 0 ) { ?>
          <tr>
          <td height="21">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><div align="right">
            <div align="right"><span class="style32"><strong>Desconto:</strong></span></div>
          </div></td>
           <td><div align="right">
              <span class="style32">
              <?=number_format($desconto,2,',','.');?>
          </span></div></td>
           <td>&nbsp;</td>
        </tr>
        <?php } ?>
      <tr>
        <td height="21">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td><div align="right"><span class="style32"><strong>Total:</strong></span></div></td>
        <td><div align="right"> <span class="style32">
          <?=number_format(($totalgeral-$desconto-$_valorentrada),2,',','.');?>
        </span></div></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="3" colspan="2" class="style5 style61">------------------------------------------------------------------------------------</td>
  </tr>
  <tr>
    <td colspan="2" class="style32"><?=$MENSAGEM;?>
      <br />
      <br />
      <br />
      Ass.Cliente____________________   Data: _ _ _/_ _ _/_ _ _ _ </td>
  </tr>
</table>
<p>&nbsp;</p>
<p class="style5"><br>
</p>
</body>



<?php

            }
}

?>

