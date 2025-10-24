<?php 

session_start();

	
	//$_SESSION['chave_loja'] = '1020';
	//$_SESSION['numberPedido'] = '1251';
	
	// $chaveAcesso = $_SESSION['chave_loja'];

	use Database\MySQL;

	$pdo = MySQL::acessabd();
	
	//print_r($_parametros);	$_numeromdf  = $_parametros['_numeromdf'];



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



/*
$sql = ("SELECT NUMERO, CODIGO_CLIENTE, DATE_FORMAT( saidaestoque.DATA_CADASTRO, '%d/%m/%Y' ) AS dtCADASTRO,
DATE_FORMAT( saidaestoque.DATA_HORA, '%H:%i' ) AS dtHORA, saidaestoque.Cod_Situacao, Descricao, usuario_NOME, COD_Vendedor, Valor_Entrada, Tipo_Pagamento_Entrada, COND_PAGTO, Vl_Pedido, DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS dt1, DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS dt2, DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS dt3, DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS dt4, DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS dt5, DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS dt6, Valor_Venc1, Valor_Venc2, Valor_Venc3, Valor_Venc4, Valor_Venc5, Valor_Venc6, Nome_Consumidor,COMPLEMENTO,NOME_RECADO, CGC_CPF,OBSERVACAO,consumidor.CIDADE,BAIRRO,Nome_Rua,CEP,UF,INSCR_ESTADUAL,Num_Rua,Fax,FONE_RESIDENCIAL,FONE_CELULAR,DDD,FONE_COMERCIAL,CGC_CPF,Tipo_Pagamento,VL_Pedido,Nome_Fantasia,Valor_Troco,xml_retorno ,VL_DESCONTO, DATE_FORMAT( DATA_ENTREGA, '%d/%m/%Y' ) AS dataentrega,  Valor_Frete,VL_DESCONTO_porc,
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
			$valorfrete = $rstPedido['Valor_Frete'];
			$desconto = $rstPedido['VL_DESCONTO'] + $rstPedido['VL_DESCONTO_porc'] ;
			$obs = $rstPedido["OBSERVACAO"];
			$datac = $rstPedido["dtCADASTRO"];
			$dataHORA = $rstPedido["dtHORA"];
			$id_numero = $rstPedido["NUMERO"];
			$empresa_id = $rstPedido["SAIDA_EMPRESA"];
		
				*/
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
		
			$sql = ("SELECT * FROM ".$_SESSION['BASE'].".NFE_MANIFESTO WHERE mdf_docfiscal = '$_numeromdf'");
			$stm = $pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $rst){

				$serie = $rst['VL_Pedido'];
				$numero = $rst['Valor_Frete'];
				$data = $rst['VL_DESCONTO']; 
				$qtdcte = $rst['VL_DESCONTO']; 
				$qtdnfe = $rst['VL_DESCONTO'];
				$pesototal = $rst['VL_DESCONTO'];
				$vlmercadoria = $rst['VL_DESCONTO'];
				$protocolo = $rst['VL_DESCONTO'];
				$chave = $rst['VL_DESCONTO'];
				$qrcode = "https://dfe-portal.svrs.rs.gov.br/mdfe/qrCode?chMDFe=$chave&tpAmb=2";
				$placa = $rst['VL_DESCONTO'];
				$cpfcondutor = $rst['VL_DESCONTO'];
				$nomecondutor = $rst['VL_DESCONTO'];
				$seguradora= $rst['VL_DESCONTO'];
				$numeroapolice = $rst['VL_DESCONTO'];
				$cidadecarregamento = $rst['VL_DESCONTO'];
				$cidadescarregamento = $rst['VL_DESCONTO'];
			}

			
		

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional/ EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>



<title>Manifesto Eletrônico</title>


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

<table width="893" border="0" cellspacing="0" cellpadding="00">
  <tr>
    <td height="136" colspan="2">logo</td>
    <td height="136" colspan="4"><?=$razao;?><br>
	<?=$endereco?> ,<?=$nmro;?> <?=$Complemento_Endereco;?> <?=$bairro;?>
	<?=$cidade;?>- <?=$estado;?> CEP:<?=$cep;?> 
CNPJ: <?=$cnpj;?> <br> IE: <?=$inscricao;?>
TEL.: <?=$telefone;?>
<?=$email;?> </td>
    <td height="136">&nbsp;</td>
    <td width="166">QRCODE</td>
  </tr>
  <tr>
    <td height="19" colspan="8"><strong>DAMDFE - Documento Auxiliar de Manifesto Eletrônico de Documento Fiscais</strong></td>
  </tr>
  <tr>
    <td colspan="8"><table width="759" border="0" cellspacing="0" cellpadding="00">
      <tr>
        <td width="96">MODELO</td>
        <td width="60">SÉRIE</td>
        <td width="76">NÚMERO</td>
        <td width="97">FOLHA</td>
        <td width="213">DATA E HORA DE EMISSÃO</td>
        <td width="75">UF CARGA</td>
        <td width="142">UF DESCARGA</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="5"><table width="530" border="0" cellspacing="0" cellpadding="00">
      <tr>
        <td colspan="4" align="center"><strong>MODAL RODOVIÁRIO DE CARGA</strong></td>
      </tr>
      <tr>
        <td>QTDE CT-e</td>
        <td>QTDE NF-e</td>
        <td>PESO TOTAL (Kg)</td>
        <td>VALOR DA MERCADORIA R$</td>
      </tr>
      <tr>
        <td width="87">&nbsp;</td>
        <td width="86">&nbsp;</td>
        <td width="136">&nbsp;</td>
        <td width="221">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">PROTOCOLO DE AUTORIZAÇÃO DE USO</td>
      </tr>
      <tr>
        <td colspan="4">98988989889898 25/03/2020 23:54</td>
        </tr>
    </table></td>
    <td colspan="3"><table width="352" border="0" cellspacing="0" cellpadding="00">
      <tr>
        <td width="352">Controle do Fisco</td>
      </tr>
      <tr>
        <td>||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="8"><table width="874" border="0" cellspacing="0" cellpadding="00">
      <tr>
        <td colspan="3"><strong>Veículo</strong></td>
        <td colspan="3"><strong>Condutor</strong></td>
        </tr>
      <tr>
        <td width="192">Placa</td>
        <td colspan="2">RNTRC</td>
        <td width="142">CPF</td>
        <td colspan="2">Nome</td>
      </tr>
      <tr>
        <td height="19" colspan="6">___________________________________________________________________________________________________________</td>
        </tr>
      <tr>
        <td height="56">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><strong>Vale Pedágio</strong></td>
        <td colspan="3"><strong>Responsável pelo Seguro - Emitente</strong></td>
        </tr>
      <tr>
        <td><strong>Responsável CNPJ</strong></td>
        <td width="157"><strong>Fornecedor CNPJ</strong></td>
        <td width="124"><strong>N.Comprovante</strong></td>
        <td><strong>Nome da Seguradora</strong></td>
        <td width="117">&nbsp;</td>
        <td width="142"><strong>Número da Apólice</strong></td>
      </tr>
      <tr>
        <td height="19" colspan="6">___________________________________________________________________________________________________________</td>
        </tr>
      <tr>
        <td height="69">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="8" align="center">   Tp Doc. CNPJ/CPF Emitente Série/Nro. Documento</td>
  </tr>
  <tr>
    <td colspan="8" align="center"><strong>RELAÇÃO DOS DOCUMENTOS FISCAIS ELETRÔNICOS</strong></td>
  </tr>
  <tr>
    <td colspan="8"><table width="906" border="0" cellspacing="0" cellpadding="00">
      <tr>
        <td width="78">Tp. Doc.</td>
        <td width="179">CNPJ/CPF Emitente</td>
        <td width="221">Série/Nro. Documento</td>
        <td width="114" height="19">Tp. Doc.</td>
        <td width="163">CNPJ/CPF Emitente</td>
        <td width="151">Série/Nro. Documento</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="82">NF-e</td>
    <td colspan="3">9999999999999999999999999999999999</td>
    <td colspan="2">NF-e</td>
    <td colspan="2">9999999999999999999999999999999999</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="53">&nbsp;</td>
    <td width="77">&nbsp;</td>
    <td width="264">&nbsp;</td>
    <td width="54">&nbsp;</td>
    <td width="123">&nbsp;</td>
    <td width="106">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="8">Observação </td>
  </tr>
  <tr>
    <td colspan="8">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
>

