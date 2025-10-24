<?php 
	session_start();

	require_once('../../api/config/config.inc.php');	
	require '../../../api/vendor/autoload.php';
	include('libs/phpqrcode/qrlib.php'); 
	
	// $chaveAcesso = $_SESSION['chave_loja'];

	use Database\MySQL;

	$pdo = MySQL::acessabd();
	
	$livro = "1";
	//$pedido = $_SESSION['numberPedido'];

	$pedido = explode('-', $_parametros['id-altera']);

	$pedido=  $pedido['1'];
	$livro = $pedido['2'];

		
	$sql = "Select arquivo_logo_base64 from bd_gestorpet.empresa_dados where id = '".$_SESSION['BASE_ID']."'";
	$stm = $pdo->prepare($sql);
	$stm->execute();

	if ($stm->rowCount() > 0 ){
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rst){	
		
			$img_logo = $rst['arquivo_logo_base64'];
		
		}
	}		

	$sql = "Select * from ".$_SESSION['BASE'].".parametro ";
	$stm = $pdo->prepare($sql);
	$stm->execute();
	if ($stm->rowCount() > 0 ){
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rst){	
		
			$id_parametro = $rst["id"];
			$endereco = $rst["ENDERECO"];
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
/*
$sql = ("SELECT NUMERO, CODIGO_CLIENTE, DATE_FORMAT( saidaestoque.DATA_CADASTRO, '%d/%m/%Y' ) AS dtCADASTRO,
 saidaestoque.Cod_Situacao, Descricao, usuario_NOME, COD_Vendedor, Valor_Entrada, Tipo_Pagamento_Entrada,
  COND_PAGTO, Vl_Pedido, DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS dt1, DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS dt2,
   DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS dt3, DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS dt4,
    DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS dt5, DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS dt6,
	 Valor_Venc1, Valor_Venc2, Valor_Venc3, Valor_Venc4, Valor_Venc5, Valor_Venc6,
	  Nome_Consumidor,COMPLEMENTO,NOME_RECADO, CGC_CPF,OBSERVACAO,consumidor.CIDADE
	  ,BAIRRO,Nome_Rua,CEP,UF,INSCR_ESTADUAL,Num_Rua,Fax,FONE_RESIDENCIAL,FONE_CELULAR,DDD,
	  FONE_COMERCIAL,CGC_CPF,Tipo_Pagamento,VL_Pedido,Nome_Fantasia,Valor_Troco,xml_retorno
,VL_DESCONTO, DATE_FORMAT( DATA_ENTREGA, '%d/%m/%Y' ) AS dataentrega, obs_pedido
FROM ".$_SESSION['BASE'].".saidaestoque

LEFT JOIN bd_gestorpet.usuario ON usuario_CODIGOUSUARIO = COD_Vendedor

LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao

LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR = CODIGO_CLIENTE

WHERE NUMERO = '$pedido' and num_livro = '$livro' ");


*/
$sql = ("SELECT Valor_Frete FROM ".$_SESSION['BASE'].".saidaestoque
WHERE NUMERO = '$pedido' ");

$stm = $pdo->prepare($sql);
$stm->execute();
$TotalRegPedido = $stm->rowCount();
$valorfrete = 0;

	foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rstPedido){
			
			$valorfrete = $rstPedido['Valor_Frete'];		

	}
$sql = ("SELECT nfed_xml_protocolado FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$pedido'   ");

$stm = $pdo->prepare($sql);
$stm->execute();
$TotalRegPedido = $stm->rowCount();

	foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rstPedido){

		//	$desconto = $rstPedido['VL_DESCONTO'];
		//	$obs = $rstPedido["obs_pedido"];
		//	$datac = $rstPedido["dtCADASTRO"];
		//	$id_numero = $rstPedido["NUMERO"];
			

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional/ EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>



<title>CUPOM FISCAL</title>


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

	$arquivo = $rstPedido['nfed_xml_protocolado'];
	$xml = simplexml_load_string($arquivo, null, LIBXML_NOCDATA);
	
?>


<table  width="350" border="0">
	<tr>
		<td align="center">
			<img src="data:image/png;base64, <?=$img_logo?>" width="100px"/>
		</td>
	</tr>	
	<tr>
		<td class="center"> <?=$xml->NFe->infNFe->emit->xFant;?></td>
	</tr>
	<tr>
		<td class="center">CNPJ <?=$xml->NFe->infNFe->emit->CNPJ;?> <?=$xml->NFe->infNFe->emit->xNome;?> <br><?=$xml->NFe->infNFe->emit->enderEmit->xLgr;?>, <?=$xml->NFe->infNFe->emit->enderEmit->nro;?>, <?=$Complemento_Endereco;?> <?=$xml->NFe->infNFe->emit->enderEmit->xBairro;?> <?=$xml->NFe->infNFe->emit->enderEmit->xMun;?> <?=$xml->NFe->infNFe->emit->enderEmit->UF;?> <?=$xml->NFe->infNFe->emit->enderEmit->CEP;?> FONE <?=$telefone;?> I.E. <?=$xml->NFe->infNFe->emit->IE;?> <br><span style="font-size:7px; margin-top:-5px;">DOCUMENTO AUXILIAR DA NOTA FISCAL DE CONSUMIDOR ELETRONICA</span></td>
	</tr>
	<tr>
		<td class="center" style="padding-left:5px;">
			<table width="300">
			<tr>
					<th style="font-size:12px;">Cod</th>
					<th style="font-size:12px;">Descrição</th>
					<th style="font-size:12px;">Un Med</th>
					<th style="font-size:12px;">Qtd</th>					
					<th style="font-size:12px;">Vlr Un</th>
					<th style="font-size:12px;">Vlr Total</th>
				</tr>
				
				<?php 	$valorPago = str_replace(".",",",$xml->NFe->infNFe->pag->detPag->vPag);
						$totalgeral = str_replace(".",",",$xml->NFe->infNFe->total->ICMSTot->vNF);
						
						if($xml->NFe->infNFe->dest->CNPJ != ""){
							$docc = "CNPJ";
							$cpfcnpj = $xml->NFe->infNFe->dest->CNPJ;
						}else{
							$docc = "CPF";
							$cpfcnpj = $xml->NFe->infNFe->dest->CPF;
						}
								
						foreach($xml->NFe->infNFe->det as $name ){ 

							$qt = intval($name->{'prod'}->{'qCom'});
							$_CODPRODUTO = $name->{'prod'}->{'cProd'};
							
							if($_vizCodInterno == 1 ){ //codigo fabricante

								
								$sql = "select impostonacional,CODIGO_FABRICANTE as COD from ".$_SESSION['BASE'].".itemestoque
								left join minhaos_cep.impostost ON 	codigoncm = Cod_Class_Fiscal
								where CODIGO_FORNECEDOR = ? and Ind_Prod <> '2' limit 1";							
								$stm = $pdo->prepare($sql);	
								$stm->bindParam(1,$name->{'prod'}->{'cProd'}, \PDO::PARAM_STR);							
								$stm->execute();
								
							}else{
								$sql = "select impostonacional,Codigo_Barra as COD from ".$_SESSION['BASE'].".itemestoque
								left join minhaos_cep.impostost ON 	codigoncm = Cod_Class_Fiscal
								where CODIGO_FORNECEDOR = ? and Ind_Prod <> '2' limit 1";							
								$stm = $pdo->prepare($sql);	
								$stm->bindParam(1,$name->{'prod'}->{'cProd'}, \PDO::PARAM_STR);							
								$stm->execute();

							}
								
						
						
							$IMPOSTO = 0;
							if($stm->rowCount() > 0){
								while($linha = $stm->fetch(PDO::FETCH_OBJ)){					
									
									$IMPOSTO = $linha->impostonacional;		
									$_CODPRODUTO = $linha->COD;							

									$IMPOSTOVLR = $IMPOSTOVLR + (($name->{'prod'}->{'vProd'}*$qt)*$IMPOSTO/100 );
									
								}
							}
						
					//
					//	$valorUnidade = str_replace(".",",",$name->{'prod'}->{'vUnCom'});
						//$valorProduto = str_replace(".",",",$name->{'prod'}->{'vProd'});
					
						$valorProduto = number_format(floatval($name->{'prod'}->{'vProd'}), 2, ',', '.'); 
						$valorProdutoF = number_format($name->{'prod'}->{'vProd'}*$qt, 2, ',', '.'); 
						$unidade = $name->{'prod'}->{'uCom'};
						?>
						<tr> 
							<td colspan="1" style="font-size:12px;"><?=$_CODPRODUTO;?></td>
							
							<td colspan="5"style="font-size:10px"><?=$name->{'prod'}->{'xProd'};?></td>
							
						</tr>
						<tr> 
							<td style="font-size:12px;"></td>
							
							<td style="font-size:10px"></td>
							<td><div align="center" style="font-size:12px;"><?=$unidade;?></div></td>							
							<td><div align="center" style="font-size:12px;"><?=$qt;?> X</div></td>
							<td><div align="center" style="font-size:12px;"><?=$valorProduto;?></div></td>
							<td><div align="right" style="font-size:12px;"><?=$valorProdutoF;?></div></td>
						</tr>
				<?php 	} ?>
						<tr>
							<td colspan="5" style="text-align:left; font-size:10px;"><b>QTD. TOTAL DE ITENS</b></td>
							<td style="font-size:12px; text-align:right;"><?=$qtde;?></td>
						</tr>
							<?php
								foreach($xml->NFe->infNFe->total as $name){
									$descontoItens = floatval($name->{'ICMSTot'}->{'vDesc'});
								}
							?>
						<tr>
							<td colspan="5" style="text-align:left; font-size:10px;"><b>VALOR TOTAL R$</b></td>
							<td style="font-size:12px; text-align:right;"><?=number_format($totalgeral+$descontoItens,2,',','.');?></td>
						</tr>
						<?php if($descontoItens > 0){  ?>

						
						<tr>
							<td colspan="5" style="text-align:left; font-size:10px;">Desconto R$</td>
							
							<td  style="font-size:12px;"><?php  echo(number_format($descontoItens,2,',','.')); ?></td>
						</tr>
						<?php }  if($valorfrete > 0) { ?>
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
							<td colspan="5" style="text-align:left; font-size:11px;">FORMA PAGAMENTO</td>
							<td colspan="2" style="text-align:right; font-size:12px;">Valor Pago</td>
						</tr>
						<tr>
							<td colspan="5" style="text-align:left; font-size:10px;"><?=$rstPedido["COND_PAGTO"];?></td>
							
							<?php
								foreach($xml->NFe->infNFe->pag as $name){ 
									
									$valorPgg = $valorPgg +  $name->{'detPag'}->{'vPag'};
									$troco = $troco +  $name->{'detPag'}->{'vTroco'};
									
									$i = $i + 1;
								}
							?>
							<td colspan="2" style="font-size:12px;text-align:right"><?=number_format($valorPgg,2,',','.');?></td>
						</tr><tr>
							<td colspan="5" style="text-align:left; font-size:10px;">Troco R$</td>
							<td style="font-size:12px;text-align:right"><?=number_format($troco,2,',','.');?></td>
						</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="center"><b>Consulte pela Chave de Acesso em</b></td>
	</tr>
	<tr>
		<td class="center">http://www.fazenda.pr.gov.br/nfce/consulta</td>
	</tr>
	<tr>
		<td class="center" style="font-size:11px;"><?=$xml->protNFe->infProt->chNFe;?></td>
	</tr>
	<tr>
		<td class="center"><b>CONSUMIDOR <?=$docc;?>: <?=$cpfcnpj;?></b></td>
	</tr>
	<tr>
		<td class="center">CONSUMIDOR PADRAO</td>
	</tr>
	<tr>
		<td class="center" style="font-size:11px;">NFC-E nº <?=str_pad($xml->NFe->infNFe->ide->nNF, 6 , '0' , STR_PAD_LEFT);?> Série <?=str_pad($xml->NFe->infNFe->ide->serie, 3 , '0' , STR_PAD_LEFT);?> <?=$xml->protNFe->infProt->dhRecbto;?></td>
	</tr>
	<tr>
		<td class="center" style="font-size:11px;">Protocolo de Autorizacão: <?=$xml->protNFe->infProt->nProt;?></td>
	</tr>
	<tr>
		<td class="center">Data de Autorização: <?=$datac;?></td>
	</tr>
	<tr>
		<td class="center">
			<?php 
				//QR CODE
				$tempDir = "qrcodes/";
				$codeContents = $xml->NFe->infNFeSupl->qrCode;
				
				
				$fileName = $_SESSION['BASE_ID'].'.png';
				
				$pngAbsoluteFilePath = $tempDir.$fileName;
				$urlRelativeFilePath = $tempDir.$fileName;
				

				QRcode::png($codeContents, $pngAbsoluteFilePath); 
				
				?>
				<img src="app/<?=$urlRelativeFilePath;?>" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">
				<?php
			?>
		</td>
	</tr>
	<tr>
		<td class="center">OBRIGADO PELA SUA PREFERÊNCIA, VOLTE SEMPRE!</td>
	</tr>
	
	<tr>
		<td class="center"><?=$xml->NFe->infNFe->infAdic->infCpl;?></td>
	</tr>
	<tr>
		<td style="padding-right: 10px; text-align:right; font-size:12px; font-family:Arial, Helvetica, sans-serif;"><b>www.sistemaprisma.com.br</b></td>
	</tr>

</table>
			<?php }
			 exit();?>