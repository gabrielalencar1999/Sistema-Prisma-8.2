<?php 

session_start();

	
	//$_SESSION['chave_loja'] = '1020';
	//$_SESSION['numberPedido'] = '1251';
	
	// $chaveAcesso = $_SESSION['chave_loja'];

	use Database\MySQL;

	$pdo = MySQL::acessabd();

	use Functions\Acesso;
	//$livro = "1";
	//$pedido = $_SESSION['numberPedido'];
	/*
	$_idfrefGO = $_parametros["_ref"];
	
	$_idfref = base64_decode($_idfrefGO);

	$_idfref = explode('-',$_idfref);
	if($_idfref[1] != "") { 
		$pedido = $_idfref[1];
		$numero_pedido= $_idfref[1];
	}else{
		$pedido = $_SESSION['numberPedido'];
	
	}
*/
	$numero_pedido  = $_parametros['id_pedido'];
	$pedido = $_parametros['id_pedido'];

	$TITULOCUPOM = 'CUPOM VENDA';
	$_retviewerTitulo= Acesso::customizacao('9');
	if($_retviewerTitulo == 1) { 
		$TITULOCUPOM = "CUPOM NÃO FISCAL";
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
		
		
		}
	}

	
	$cnpj = substr($cnpj,0,2).".".substr($cnpj,2,3).".".substr($cnpj,5,3)."/".substr($cnpj,8,4)."-".substr($cnpj,12,2);


	if($mensagemCupom == ""){
		$mensagemCupom = "OBRIGADO PELA SUA PREFERÊNCIA, VOLTE SEMPRE!";
	}

$sql = ("SELECT NUMERO, CODIGO_CLIENTE, DATE_FORMAT( saidaestoque.DATA_CADASTRO, '%d/%m/%Y' ) AS dtCADASTRO,
DATE_FORMAT( saidaestoque.DATA_HORA, '%H:%i' ) AS dtHORA, saidaestoque.Cod_Situacao, Descricao, usuario_NOME, COD_Vendedor, Valor_Entrada, Tipo_Pagamento_Entrada, COND_PAGTO, Vl_Pedido, DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS dt1, DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS dt2, DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS dt3, DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS dt4, DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS dt5, DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS dt6, Valor_Venc1, Valor_Venc2, Valor_Venc3, Valor_Venc4, Valor_Venc5, Valor_Venc6, Nome_Consumidor,COMPLEMENTO,NOME_RECADO, CGC_CPF,OBSERVACAO,consumidor.CIDADE,BAIRRO,Nome_Rua,CEP,UF,INSCR_ESTADUAL,Num_Rua,Fax,FONE_RESIDENCIAL,FONE_CELULAR,DDD,FONE_COMERCIAL,CGC_CPF,Tipo_Pagamento,VL_Pedido,Nome_Fantasia,Valor_Troco,xml_retorno ,VL_DESCONTO, DATE_FORMAT( DATA_ENTREGA, '%d/%m/%Y' ) AS dataentrega,  Valor_Frete,VL_DESCONTO_porc,
OBSERVACAO,SAIDA_EMPRESA
FROM ".$_SESSION['BASE'].".saidaestoque

LEFT JOIN " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = COD_Vendedor

LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao

LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR = CODIGO_CLIENTE

WHERE NUMERO = '$pedido'  ");

$stm = $pdo->prepare($sql);
$stm->execute();
$TotalRegPedido = $stm->rowCount();

	foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rstPedido){

			$valorPedido = $rstPedido['VL_Pedido'];
			$valorfrete = $rstPedido['Valor_Frete'];
			$desconto = $rstPedido['VL_DESCONTO'] + $rstPedido['VL_DESCONTO_porc'] ;
			$obs = $rstPedido["OBSERVACAO"];
			$datac = $rstPedido["dtCADASTRO"];
			$dataHORA = $rstPedido["dtHORA"];
			$id_numero = $rstPedido["NUMERO"];
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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional/ EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>



<title>CUPOM DE VENDA/ATENDIMENTO</title>


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

	$arquivo = $rstPedido['xml_retorno'];
	$xml = simplexml_load_string($arquivo, null, LIBXML_NOCDATA);
	
	?>

			<table  width="350" border="0">
				<tr>
					<td align="center">
						<img src="data:image/png;base64, <?=$img_logo?>" width="200px"/>
					</td>
				</tr>
				<tr>
					<td class="center"> <?=$xml->NFe->infNFe->emit->xFant;?></td>
				</tr>
				<tr>
					<td class="center"> <?=$razao;?><br>CNPJ <?=$cnpj;?> <br><?=$endereco?>, <?=$nmro;?>, <?=$Complemento_Endereco;?> <?=$bairro;?> <?=$cidade;?> <?=$estado;?> <?=$cep;?> FONE <?=$telefone;?> I.E. <?=$inscricao;?> <br><span style="font-size:14px; font-weight:bold;"><br><hr><?=$TITULOCUPOM;?> Nº <?=$pedido;?><hr></span></td>
				</tr>
				<tr>
					<td class="center" style="padding-left:5px;">
						<table width="300">
							<tr>
								<th style="font-size:12px;">Cod</th>
								<th style="font-size:12px;">Qtd</th>
								<th style="font-size:12px;">Descriçao</th>
								<th style="font-size:12px;">Unidade</th>
								<th style="font-size:12px;">Vlr Un</th>
								<th style="font-size:12px;">Vlr Total</th>
							</tr>
							
							<?php 	
							if($_vizCodInterno == 1 ){ //codigo fabricante
								$REF = "CODIGO_FABRICANTE";
							}else{
								$REF = "CODIGO_FORNECEDOR";
							}


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
										<td colspan="1" style="font-size:12px;"><?=$_CODPRODUTO;?></td>						
										<td  colspan="5" style="font-size:10px"><?=substr($name['DESCRICAO_ITEM'],0,46);?></td>
										
									</tr>
									<tr> 
									<td style="font-size:12px;"></td>							
										<td style="font-size:10px"></td>
										<td ><div align="center" style="font-size:12px;"><?=$rst["UNIDADE_MEDIDA"];?></div></td>
										<td><div align="center" style="font-size:12px;"><?=$name['QUANTIDADE'];?> X <?php $qtde = $qtde + $name['QUANTIDADE']; ?></div></td>																
										<td><div align="right" style="font-size:12px;"><?=$valorUnidade;?></div></td>
										<td><div align="right" style="font-size:12px;"><?=$valorProduto;?></div></td>
									</tr>
							<?php 	} ?>
									<tr>
										<td colspan="6" style="text-align:left; font-size:10px;"><hr></td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:left; font-size:10px;"><b>QTD. TOTAL DE ITENS</b></td>
										<td style="font-size:12px; text-align:right;"><?=$qtde;?></td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:left; font-size:10px;"><b>VALOR TOTAL R$</b></td>
										<td style="font-size:12px; text-align:right;"><?=number_format($totalgeral,2,',','.');?></td>
									</tr>
									<?php if($desconto > 0) { ?>
									<tr>
										<td colspan="5" style="text-align:left; font-size:10px;">Desconto R$</td>
										<td style="font-size:12px; text-align:right;"><?=number_format($desconto,2,',','.');?></td>
									</tr>
									<?php } if($valorfrete > 0) { ?>
										<tr>
										<td colspan="5" style="text-align:left; font-size:10px;">Frete R$</td>
										<td style="font-size:12px; text-align:right;"><?=number_format(($valorfrete),2,',','.');?></td>
									</tr>
										
									<?php } ?>
									<tr>
										<td colspan="5" style="text-align:left; font-size:10px;"><b>VALOR A PAGAR R$</b></td>
										<td style="font-size:12px; text-align:right;"><?=number_format(($totalgeral+$valorfrete-$desconto),2,',','.');?></td>
									</tr>
									
									
									
									<tr>
										<td colspan="4" style="text-align:left; font-size:11px;">FORMA PAGAMENTO</td>
										<td colspan="2" style="text-align:right; font-size:12px;">Valor Pago</td>
									</tr>
									<?php 
										$sql="select * from ".$_SESSION['BASE'].".saidaestoquepgto left join ".$_SESSION['BASE'].".tiporecebimpgto on id=spgto_tipopgto where spgto_numpedido = '$pedido'";
										$stm = $pdo->prepare($sql);
										$stm->execute();
										foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $pagamento ){
											$troco = $troco + $pagamento['spgto_troco'];
											
											?>
												<tr>
													<td colspan="5" style="text-align:left; font-size:10px;"><?=$pagamento["nome"];?></td>	

													<td style="font-size:12px; text-align:right;"><?=number_format($pagamento['spgto_valorInfo']+$pagamento['spgto_troco'],2,',','.');?></td>
												</tr>
											<?php
										}
										if($troco > 0) { 
									
									?>
									<tr>
										<td colspan="5" style="text-align:left; font-size:10px;">Troco R$</td>
										<td style="font-size:12px; text-align:right;"><?=number_format($troco,2,',','.');?></td>
									</tr>
									<?php } ?>
									
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
					<td class="center"><?=$mensagemCupom;?></td>
				</tr>
				
				

				<tr>
					<td style="padding-right: 15px; text-align:right; font-size:12px; font-family:Arial, Helvetica, sans-serif;"><b>Sistema Prisma</b></td>
				</tr>	
				<tr>
					<td style="padding-right: 10px; text-align:right; font-size:12px; font-family:Arial, Helvetica, sans-serif;"><b><br><br><br></b></td>
				</tr>

			</table>
		<?php

?>


			<?php } exit();?>