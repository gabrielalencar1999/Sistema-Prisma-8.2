<?php 
	session_start();

//	require_once('../../../api/config/config.inc.php');
//	require '../../../api/vendor/autoload.php';
//	include("../../../api/config/iconexao.php");   

	require_once('../../api/config/config.inc.php');
	require '../../api/vendor/autoload.php';
	include("../../api/config/iconexao.php");   

	include('libs/phpqrcode/qrlib.php'); 
	
	// $chaveAcesso = $_SESSION['chave_loja'];

	use Database\MySQL;

	$pdo = MySQL::acessabd();

	$livro = "1";

	$OS = $_parametros["osfinan"];
	$NFE = $_parametros["DOCnfe"];
	$NFEid = $_parametros["id-nota"];
	
	$sql = "Select * from ".$_SESSION['BASE'].".parametro ";
	$stm = $pdo->prepare($sql);
	$stm->execute();
	if ($stm->rowCount() > 0 ){
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rst){	
			$_vizCodInterno = $rst["empresa_vizCodInt"];			
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

			if($estado == "PR") {
				$_linkwebservice = "http://www.fazenda.pr.gov.br/nfce/consulta";
			}
			if($estado == "SP") {
				$_linkwebservice = "https://www.nfce.fazenda.sp.gov.br/NFCeConsultaPublica";
			}
			if($estado == "RS") {
				$_linkwebservice = "https://www.sefaz.rs.gov.br/nfce/consulta";
			}
		
		}
	}			




			
if($OS != ""){
	$sql = ("SELECT nfed_empresa,nfed_xml_protocolado,nfed_informacaoAdicionais FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_numeronf = '$NFE' AND  nfed_chamada = '$OS'  ");
}else{
	$sql = ("SELECT nfed_empresa,nfed_xml_protocolado,nfed_informacaoAdicionais FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_id = '$NFEid' ");
}
	
	$stm = $pdo->prepare($sql);
	$stm->execute();
	$TotalRegPedido = $stm->rowCount();

	foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rstPedido){

		$empresa_id = $rstPedido['nfed_empresa'];
		$_obs = trim($rstPedido['nfed_informacaoAdicionais']);
		$sqlEmp = "Select arquivo_logo_base64 from " . $_SESSION['BASE'] . ".empresa where empresa_id = '$empresa_id'";
		$stmEmp = $pdo->prepare($sqlEmp);
		$stmEmp->execute();
	
		if ($stmEmp->rowCount() > 0 ){
			foreach($stmEmp->fetchAll(PDO::FETCH_ASSOC) as $rstEmp){				
				$img_logo = $rstEmp['arquivo_logo_base64'];				
			}
		}
	

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
	
	$cnpj = $xml->NFe->infNFe->emit->CNPJ;
	$cnpj = substr($cnpj,0,2).".".substr($cnpj,2,3).".".substr($cnpj,5,3)."/".substr($cnpj,8,4)."-".substr($cnpj,12,2);

	$dtrecibo = $xml->protNFe->infProt->dhRecbto;
	$dtrecibo =substr($dtrecibo,8,2)."/".substr($dtrecibo,5,2)."/". substr($dtrecibo,0,4)." ".substr($dtrecibo,11,5);
?>


<table  width="350" border="0" >
	<tr>
		<td align="center">
			<img src="data:image/png;base64, <?=$img_logo?>" width="100px"/>
		</td>
	</tr>	
	<tr>
		<td class="center"> <?=$xml->NFe->infNFe->emit->xFant;?></td>
	</tr>
	<tr>
		<td class="center">CNPJ <?=$cnpj?></td>
	</tr>
	<tr>
		<td class="center"><?=$xml->NFe->infNFe->emit->xNome;?> <br><?=$xml->NFe->infNFe->emit->enderEmit->xLgr;?>, <?=$xml->NFe->infNFe->emit->enderEmit->nro;?>, <?=$Complemento_Endereco;?> <?=$xml->NFe->infNFe->emit->enderEmit->xBairro;?> <?=$xml->NFe->infNFe->emit->enderEmit->xMun;?> <?=$xml->NFe->infNFe->emit->enderEmit->UF;?> <?=$xml->NFe->infNFe->emit->enderEmit->CEP;?> FONE <?=$telefone;?> I.E. <?=$xml->NFe->infNFe->emit->IE;?> <br><span style="font-size:7px; margin-top:-5px;">DOCUMENTO AUXILIAR DA NOTA FISCAL DE CONSUMIDOR ELETRONICA</span></td>
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
				
				<?php 	
				 //	$valorPago = str_replace(".",",",$xml->NFe->infNFe->pag->detPag->vPag);
						//$totalgeral = str_replace(".",",",$xml->NFe->infNFe->total->ICMSTot->vNF);
						$valorPago =  floatval($xml->NFe->infNFe->pag->detPag->vPag);
						$totalgeral = floatval($xml->NFe->infNFe->total->ICMSTot->vNF);
						$spgto_valor = floatval($xml->NFe->infNFe->pag->detPag->vPag);
						$tipopgto = $xml->NFe->infNFe->pag->detPag->tPag;
				
						
						if($xml->NFe->infNFe->dest->CNPJ != ""){
							$docc = "CNPJ";
							$cpfcnpj = $xml->NFe->infNFe->dest->CNPJ;
						}else{
							$docc = "CPF";
							$cpfcnpj = $xml->NFe->infNFe->dest->CPF;
						}

						$xNome = $xml->NFe->infNFe->dest->xNome;
						$xEndereco = trim($xml->NFe->infNFe->dest->enderDest->xLgr." ".$xml->NFe->infNFe->dest->enderDest->nro);
						$xCEP = $xml->NFe->infNFe->dest->enderDest->CEP;
						$xMun = $xml->NFe->infNFe->dest->enderDest->xMun;
						$xUF = $xml->NFe->infNFe->dest->enderDest->UF;
						
						if($xNome == ""){
							$xNome = "CONSUMIDOR PADRAO";
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
									$IMPOSTOVLR = $IMPOSTOVLR + (($name->{'prod'}->{'vUnTrib'}*$qt)*$IMPOSTO/100 );									
								}
							}
						
					//
					//	$valorUnidade = str_replace(".",",",$name->{'prod'}->{'vUnCom'});
						//$valorProduto = str_replace(".",",",$name->{'prod'}->{'vProd'});
					
						$valorProduto = number_format(floatval($name->{'prod'}->{'vUnTrib'}), 2, ',', '.'); 
						$valorProdutoF = number_format(floatval($name->{'prod'}->{'vProd'}), 2, ',', '.');  //*$qt
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
							<td colspan="5" style="text-align:left; font-size:10px;"><b>QTD. TOTAL DE ITENS </b></td>
							<td style="font-size:12px; text-align:right;"><?=$qtde;?></td>
						</tr>
							<?php
								foreach($xml->NFe->infNFe->total as $name){
									$descontoItens = floatval($name->{'ICMSTot'}->{'vDesc'});
								}
							?>
						<tr>
							<td colspan="5" style="text-align:left; font-size:10px;"><b>VALOR TOTAL R$</b></td>
							<td style="font-size:12px; text-align:right;"><?=number_format(($totalgeral),2,',','.');?></td>
						</tr>
						<?php if($descontoItens > 0){  ?>

						
						<tr>
							<td colspan="5" style="text-align:left; font-size:10px;">Desconto R$</td>
							
							<td  style="font-size:12px;"><?php  echo(number_format($descontoItens,2,',','.')); ?></td>
						</tr>
						<?php }
						 if($valorfrete > 0) { ?>
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
							<td colspan="2" style="text-align:right; font-size:12px;">Valor Pago </td>
							</tr>
									<?php 
										$sql="select nome from ".$_SESSION['BASE'].".tiporecebimpgto WHERE Tipo = '$tipopgto'";
										$stm = $pdo->prepare($sql);
										$stm->execute();
										foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $pagamento ){
											//$troco = $troco + $pagamento['spgto_troco'];
											$nome_pgto = $pagamento["nome"];
										}
											?>
												<tr>
													<td colspan="5" style="text-align:left; font-size:10px;"><?=$nome_pgto;?></td>									
													<td style="font-size:12px; text-align:right;"><?=number_format($spgto_valor,2,',','.');?></td>
												</tr>
											<?php
										
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
		<td class="center"><b>Consulte pela Chave de Acesso em</b></td>
	</tr>
	<tr>
		<td class="center"><?=$_linkwebservice;?></td>
	</tr>
	<tr>
		<td class="center" style="font-size:11px;"><?=$xml->protNFe->infProt->chNFe;?></td>
	</tr>
	<tr>
		<td class="center"><b>CONSUMIDOR <?=$docc;?>: <?=$cpfcnpj;?></b></td>
	</tr>
	<tr>
		<td class="center"><?=$xNome;?></td>
	</tr>
	<?php if($xEndereco != "") { ?>
		<tr>
			<td class="center"><?=$xEndereco;?> </td>
		</tr>
		<tr>
			<td class="center"><?=$xCEP;?> <?=$xMun;?>-<?=$xUF;?></td>
		</tr>
	<?php } ?>
	<tr>
		<td class="center" style="font-size:11px;">NFC-E nº <?=str_pad($xml->NFe->infNFe->ide->nNF, 6 , '0' , STR_PAD_LEFT);?> Série <?=str_pad($xml->NFe->infNFe->ide->serie, 3 , '0' , STR_PAD_LEFT);?> <?=$dtrecibo?></td>
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
			// echo getcwd().'<br>';
				//QR CODE
				$tempDir = "qrcodes/";
			//	$tempDir = "../qrcodes/";
				$codeContents = $xml->NFe->infNFeSupl->qrCode;
				
				$fileName = $id_parametro.'.png';
				
				$pngAbsoluteFilePath = $tempDir.$fileName;
				$urlRelativeFilePath = "/app/v1/qrcodes/".$fileName;

				QRcode::png($codeContents, $pngAbsoluteFilePath); 
				
				echo '<img src="'.$urlRelativeFilePath.'" /> ';
			?>
		</td>
	</tr>
	<?php if($obs != "") {	?>			
					<tr>
						<td class="center"><?=$obs;?></td>
					</tr>				
				<?php } ?>
				
	<tr>
		<td class="center"  style="font-size:11px;">Tributos Totais Incidentes(Lei Federal 12.741/2012) R$ <?=number_format(($IMPOSTOVLR), 2, ',', '.'); ?></td>
	</tr>
	
	<tr>
		<td class="center">OBRIGADO PELA SUA PREFERÊNCIA, VOLTE SEMPRE!</td>
	</tr>
	
	<tr>
		<td class="center"><?=$xml->NFe->infNFe->infAdic->infCpl;?></td>
	</tr>
	<tr>
		<td style="padding-right: 20px; text-align:right; font-size:11px; font-family:Arial, Helvetica, sans-serif;">www.sistemaprisma.com.br</td>
	</tr>

</table>
			<?php } exit();?>