<?php 

session_start();

	
	//$_SESSION['chave_loja'] = '1020';
	//$_SESSION['numberPedido'] = '1251';
	
	// $chaveAcesso = $_SESSION['chave_loja'];
date_default_timezone_set('America/Sao_Paulo');
 


	use Database\MySQL;

	$pdo = MySQL::acessabd();
	$dia       = date('d');
	$mes       = date('m');
	$ano       = date('Y');
	$hora = date("H:i");
	$datadia = $dia."/".$mes."/" .$ano." as ". $hora;
		
	$livro = "1";
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


	$sql = "Select * from ".$_SESSION['BASE'].".parametro ";
	$stm = $pdo->prepare($sql);
	$stm->execute();

	if ($stm->rowCount() > 0 ){
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rst){	
			$_vizCodInterno = $rst["empresa_vizCodInt"];		
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
			$razao = $rst["RAZAO_SOCIAL"];
			$casa = $rst["NumRua"];		
			$imprime_dois = $rst["imprime_dois"];

			
		
		}
	}

	if($mensagemCupom == ""){
		$mensagemCupom = "OBRIGADO PELA SUA PREFERÊNCIA, VOLTE SEMPRE!";
	}

	//entrada
	$_valorentrada = 0;
	$sql = "select Valor_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO =  '$pedido' and Valor_Entrada > 0";	
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	
	if($stm->rowCount() > 0) {
		while($result = $stm->fetch(PDO::FETCH_OBJ)){		
			$_valorentrada = $result->Valor_Entrada;		
		}
	}

$sql = ("SELECT CGC_CPF,Nome_Consumidor,DDD_RES,DDD_COM,DDD,FONE_RESIDENCIAL,FONE_CELULAR,FONE_COMERCIAL,NUMERO, CODIGO_CLIENTE, DATE_FORMAT( saidaestoque.DATA_CADASTRO, '%d/%m/%Y' ) AS dtCADASTRO, saidaestoque.Cod_Situacao, Descricao, usuario_NOME, COD_Vendedor, Valor_Entrada, Tipo_Pagamento_Entrada, COND_PAGTO, Vl_Pedido, DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS dt1, DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS dt2, DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS dt3, DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS dt4, DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS dt5, DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS dt6, Valor_Venc1, Valor_Venc2, Valor_Venc3, Valor_Venc4, Valor_Venc5, Valor_Venc6, Nome_Consumidor,COMPLEMENTO,NOME_RECADO, CGC_CPF,OBSERVACAO,consumidor.CIDADE,BAIRRO,Nome_Rua,CEP,UF,INSCR_ESTADUAL,Num_Rua,Fax,FONE_RESIDENCIAL,FONE_CELULAR,DDD,FONE_COMERCIAL,CGC_CPF,Tipo_Pagamento,VL_Pedido,Nome_Fantasia,Valor_Troco,xml_retorno ,VL_DESCONTO, DATE_FORMAT( DATA_ENTREGA, '%d/%m/%Y' ) AS dataentrega,  VL_DESCONTO_porc,
OBSERVACAO,SAIDA_EMPRESA
FROM ".$_SESSION['BASE'].".saidaestoque

LEFT JOIN " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = COD_Vendedor

LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao

LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR = CODIGO_CLIENTE

WHERE NUMERO = '$pedido' and num_livro = '$livro' ");

$stm = $pdo->prepare($sql);
$stm->execute();
$TotalRegPedido = $stm->rowCount();

	foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rstPedido){

			$valorPedido = $rstPedido['VL_Pedido'];
			$desconto = $rstPedido['VL_DESCONTO'] + $rstPedido['VL_DESCONTO_porc'] ;
			$obs = $rstPedido["OBSERVACAO"];
			$datac = $rstPedido["dtCADASTRO"];
			$id_numero = $rstPedido["NUMERO"];

			$nomeconsumidor = $rstPedido["Nome_Consumidor"];
		
			if($rstPedido["FONE_RESIDENCIAL"] != "") {
				$_telefonecli .= "(".$rstPedido["DDD_RES"].")".$rstPedido["FONE_RESIDENCIAL"];
			  }
			  if($rstPedido["FONE_CELULAR"] != "") {
				$_telefonecli .= "(".$rstPedido["DDD"].")".$rstPedido["FONE_CELULAR"];
			  }
			  if($rstPedido["FONE_COMERCIAL"] != "") {
				$_telefonecli .= "(".$rstPedido["DDD_COM"].")".$rstPedido["FONE_COMERCIAL"];
			  }
			  $telefoneconsumidor  =  $_telefonecli;
			$cpfconsumidor = $rstPedido["CGC_CPF"];

			$empresa_id = $rstPedido["SAIDA_EMPRESA"];
			if($empresa_id == 0){
				$empresa_id = 1;	
			}
			
			$sqlEmp = "Select arquivo_logo_base64 from " . $_SESSION['BASE'] . ".empresa where empresa_id = '$empresa_id'";
			$stmEmp = $pdo->prepare($sqlEmp);
			$stmEmp->execute();
		
			if ($stmEmp->rowCount() > 0 ){
				foreach($stmEmp->fetchAll(PDO::FETCH_ASSOC) as $rstEmp){	
				
					$img_logo = $rstEmp['arquivo_logo_base64'];
				
				}
			}

			if($_vizCodInterno == 1 ){ //codigo fabricante
				$REF = "CODIGO_FABRICANTE";
			}else{
				$REF = "CODIGO_FORNECEDOR";
			}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional/ EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>



<title>PEDIDO DE VENDA</title>


	<style type="text/css">

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
	
	
	.center{
		font-family:Arial, Helvetica, sans-serif;
		text-align:center;
		padding-right:10px;
		font-size:14px;
	}
	</style>

<body>
<?php 

	//$arquivo = $rstPedido['xml_retorno'];
	//$xml = simplexml_load_string($arquivo, null, LIBXML_NOCDATA);
	

if($imprime_dois == 3) {  //SERVFAST resumido
		?>

<table  width="350" border="0">
	
	<tr>
		<td > <?=$fantasia?></td>
	</tr>
	<tr>
		<td > NUMERO:<?=$pedido?></td>
	</tr>


	<tr>
		<td  style="padding-left:5px;">
			<table width="300">
				<tr>
					<th align="left" >QTDE - COD -  DESCRICAO</th>					
				</tr>
				<tr>
							<td colspan="5" style="text-align:left; ">- - - - - - - - - - - - - - - - - - -</td>

						</tr>
				<?php 	
		

						$sql = "select $REF AS codprod, Valor_unitario_desc,QUANTIDADE,VALOR_UNITARIO,DESCRICAO_ITEM,UNIDADE_MEDIDA
						 FROM ".$_SESSION['BASE'].".saidaestoqueitem 
						 LEFT JOIN ".$_SESSION['BASE'].".itemestoque on  CODIGO_FORNECEDOR = CODIGO_ITEM
						 where NUMERO = '$pedido'";			
						$stm = $pdo->prepare($sql);
						$stm->execute();
						foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $name ){
						
						$_CODPRODUTO = $name['codprod'];
						$valorProduto = $name['VALOR_UNITARIO']*$name['QUANTIDADE'];
						$totalgeral = $totalgeral + $valorProduto;
						
					//	$valorUnidade = str_replace(".",",",$name['VALOR_UNITARIO']);
					//	$valorProduto = str_replace(".",",",$valorProduto);

					    $valorUnidade = number_format($name['VALOR_UNITARIO'],2,',','.');
						$valorProduto = number_format($valorProduto,2,',','.');
						
						 
				?>
					
						<tr> 
												
						
							
							<td><?=$name['QUANTIDADE'];?>-<?=$_CODPRODUTO;?>-<?=substr($name['DESCRICAO_ITEM'],0,46);?> </td>																
							
						</tr>
				<?php 	} ?>
				<tr>
							<td colspan="5" style="text-align:left; ">- - - - - - - - - - - - - - - - - - -</td>

						</tr>
						<tr>
							<td colspan="5" style="text-align:left; "><b>VALOR TOTAL R$ <?=number_format($totalgeral,2,',','.');?></b></td>
							
						</tr>

						
						<?php if($_valorentrada > 0) { ?>
						<tr>
							<td colspan="5" style="text-align:left; ">Entrada R$ <?=number_format($_valorentrada,2,',','.');?></td>
						
						</tr>						
						<?php } if($desconto > 0) { ?>
						<tr>
							<td colspan="5" style="text-align:left; ">Desconto R$ <?=number_format($desconto,2,',','.');?></td>
						
						</tr>
						<?php } if($valorfrete > 0) { ?>
							<tr>
							<td colspan="5" style="text-align:left; ">Frete R$ <?=number_format(($valorfrete),2,',','.');?></td>
						
						</tr>
							
						 <?php } ?>
						 <tr>
							<td colspan="5" style="text-align:left; ">- - - - - - - - - - - - - - - - - - -</td>

						</tr>
						<tr>
							<td colspan="5" style="text-align:left; "><b>VALOR A PAGAR R$ <?=number_format(($totalgeral+$valorfrete-$desconto-$_valorentrada),2,',','.');?></b></td>

						</tr>
					
						
			</table>
		</td>
	</tr>

	<tr>
		<td class="center"><hr>Data de venda: <?=$datac." ".$dataHORA;?><br><br></td>
	</tr>
	<?php if($obs != "") {	?>			
					<tr>
						<td class="center"><?=$obs;?></td>
					</tr>				
				<?php } ?>
	<tr>
		<td colspan="5" style="text-align:left; ">&nbsp;</td>						
    </tr>
	<tr>
		<td colspan="5" style="text-align:left; ">&nbsp;</td>						
    </tr>
	<tr>
		<td colspan="5" style="text-align:left; ">&nbsp;</td>						
    </tr>
	<tr>
		<td colspan="5" style="text-align:left; ">&nbsp;</td>						
    </tr>
	<tr>
		<td colspan="5" style="text-align:left; ">&nbsp;</td>						
    </tr>
	<tr>
		<td colspan="5" style="text-align:left; ">&nbsp;</td>						
    </tr>
	<tr>
		<td colspan="5" style="text-align:left; ">-</td>						
    </tr>



</table>

		<?php


	}else{
		?>

<table  width="357" border="0">
	<tr>
		<td width="351" align="center">
			<img src="data:image/png;base64, <?=$img_logo?>" width="200px"/>
		</td>
	</tr>
	<tr>
		<td class="center"> <?=$xml->NFe->infNFe->emit->xFant;?></td>
	</tr>
	<tr>
		<td class="center">CNPJ <?=$cnpj;?> <?=$razao;?> <br><?=$endereco?> Nº <?=$nmro;?> <?=$Complemento_Endereco;?> <?=$bairro;?> <?=$cidade;?> <?=$estado;?> <?=$cep;?> FONE <?=$telefone;?> I.E. <?=$inscricao;?> <br><span style="font-size:14px; font-weight:bold;"><br><hr>** PEDIDO <?=$pedido?> **<hr></span></td>
	</tr>
	<tr>
		<td class="center" style="padding-left:5px;">
			<table width="100%">
				<tr>
					<th width="69" style="font-size:12px;">Cod</th>
					<th width="30" style="font-size:12px;">Qtd</th>
					<th width="123"style="font-size:12px;">Descriçao</th>
					
					<th width="42"style="font-size:12px;">Vlr Un</th>
					<th width="45"style="font-size:12px;">Vlr Total</th>
				</tr>
				
				<?php 	
					
					$sql = "select $REF AS codprod, Valor_unitario_desc,QUANTIDADE,VALOR_UNITARIO,DESCRICAO_ITEM,UNIDADE_MEDIDA
					FROM ".$_SESSION['BASE'].".saidaestoqueitem 
					LEFT JOIN ".$_SESSION['BASE'].".itemestoque on  CODIGO_FORNECEDOR = CODIGO_ITEM
					where NUMERO = '$pedido'";			
				   $stm = $pdo->prepare($sql);
				   $stm->execute();
				   foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $name ){
						
						
						$valorProduto = $name['Valor_unitario_desc']*$name['QUANTIDADE'];
						$totalgeral = $totalgeral + $valorProduto;
						
					//	$valorUnidade = str_replace(".",",",$name['VALOR_UNITARIO']);
					//	$valorProduto = str_replace(".",",",$valorProduto);

					    $valorUnidade = number_format($name['VALOR_UNITARIO'],2,',','.');
						$valorProduto = number_format($valorProduto,2,',','.');
						
						 
				?>
						<tr> 
							<td style="font-size:12px;"><?=	$name['codprod'];?></td>
							<td><div align="center" style="font-size:12px;"><?=$name['QUANTIDADE'];?>  <?php $qtde = $qtde + $name['QUANTIDADE']; ?></div></td>
							<td style="font-size:10px"><?=$name['DESCRICAO_ITEM'];?></td>
													
							<td><div align="right" style="font-size:12px;"><?=$valorUnidade;?></div></td>
							<td><div align="right" style="font-size:12px;"><?=$valorProduto;?></div></td>
						</tr>
				<?php 	} ?>
						<tr>
							<td colspan="6" style="text-align:left; font-size:10px;"><hr></td>
						</tr>
						<tr>
							<td colspan="4" style="text-align:left; font-size:10px;"><b>QTD. TOTAL DE ITENS</b></td>
							<td width="45" style="font-size:12px; text-align:right;"><?=$qtde;?></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:left; font-size:10px;"><b>VALOR TOTAL </b></td>
							<td colspan="2" style="font-size:12px; text-align:right;">R$ <?=number_format($totalgeral,2,',','.');?></td>
						</tr>
						<tr>
							<td colspan="5" style="font-size:12px; text-align:right;"><hr></td>
						
						</tr>						
						
						<?php if($_valorentrada > 0) { ?>
						<tr>
							<td colspan="5" style="font-size:12px; text-align:right;">Entrada R$ <?=number_format($_valorentrada,2,',','.');?></td>
						
						</tr>						
						<?php } if($desconto > 0) { ?>
						<tr>
							<td colspan="5"style="font-size:12px; text-align:right;">Desconto R$ <?=number_format($desconto,2,',','.');?></td>
						
						</tr>
						<?php } if($valorfrete > 0) { ?>
							<tr>
							<td colspan="5" style="font-size:12px; text-align:right;">Frete R$ <?=number_format(($valorfrete),2,',','.');?></td>
						
						</tr>
							
						 <?php } ?>
						
						<tr>
							<td colspan="5" style="font-size:12px; text-align:right;"><b>VALOR A PAGAR R$ <?=number_format(($totalgeral+$valorfrete-$desconto-$_valorentrada),2,',','.');?></b></td>

						</tr>
						
					
						<?php
						 ?>
			</table>
		</td>
	</tr>

	<tr>
		<td class="center"><hr>Data do pedido: <?=$datac;?><br><br></td>
	</tr>
	</tr>
		
					<tr>
						<td class="center">Nome:<?=$nomeconsumidor;?></td>
					</tr>	
					<tr>
						<td class="center">Telefone.:<?=$telefoneconsumidor;?></td>
					</tr>	
					<tr>
						<td class="center">CPF/CNPJ:<?=$cpfconsumidor;?></td>
					</tr>			
			
	<tr>
	<?php if($obs != "") {	?>			
					<tr>
						<td class="center"><?=$obs;?></td>
					</tr>				
				<?php } ?>
	<tr>
		<td class="center"><?=$mensagemCupom;?></td>
	</tr>
	
	

	<tr>
	  <td style="padding-right: 15px; text-align:right; font-size:12px; font-family:Arial, Helvetica, sans-serif;">&nbsp;</td>
  </tr>
	<tr>
		<td style="padding-right: 15px; text-align:right; font-size:12px; font-family:Arial, Helvetica, sans-serif;"><hr>
		 <?=$datadia;?> -  www.sistemaprisma.com.br</td>
	</tr>	
	<tr>
		<td style="padding-right: 10px; text-align:right; font-size:12px; font-family:Arial, Helvetica, sans-serif;"><b><br><br><br></b></td>
	</tr>

</table>
			<?php }} exit();?>