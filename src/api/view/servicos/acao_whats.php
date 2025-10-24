<?php

require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

if($_SESSION['BASE'] == "") { 
	echo "Seu login expirou. Efetue o login novamente !!!";
	
}

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
$data2      = $ano . "-" . $mes . "-" . $dia;


function RemoveSpecialChar($str)
{

	// Using str_replace() function 
	// to replace the word 
	$res = str_replace(array(
		'\'', '"',
		',', ';', '<', '>', '-', '(', ')', ' '
	), ' ', $str);
	$res = str_replace(" ", "", $res);
	// Returning the result 
	return $res;
}

function mascara($_texto, $_tipo)
{
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	$_texto =    str_replace("NULL", "", $_texto);

	if ($_tipo == "telefone" and $_texto != "") {
	
		if (strlen($_texto) > 10) {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 5) . "-" . substr($_texto, 7, 4);
		} else {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 4) . "-" . substr($_texto, 6, 4);
		}
		
	}

	return $_texto;
}

function mensagem($_mensagem, $_campo, $valor_campo )
{
	$_texto =    str_replace($_campo, $valor_campo, $_mensagem); //[NOME]

	return $_texto;
}

function mensagemArray( $_campo,$NUMEROOS, $CHAMADA,
$NOME, 
$ENDERECO,
$COMPLEMENTO,
$BAIRRO,						
$CPFCNPJ,
$CIDADE,
$UF,
$DDD,
$EMAIL,
$FONES,
$FONECELULAR1,
$FONECELULAR2,
$FONEFIXO,
$PRODUTO,
$DTATENDIMENTO,
$NOMEATENDENTE,
$NOMETECNICO,
$DEFEITORECLAMADO,
$DEFEITOCOSTATADO,
$SERVICOEXECUTADO,
$OBSERVACAO,
$MODELO,						
$SERIE,
$MARCA,
$HORARIOATENDIMENTO,						
$VLRSERVICOS,
$VLRPECAS,
$TOTAL,
$TOTALDESCONTO,
$EMPRESANOME,
$EMPRESATELEFONE,
$desc_peca,
$orcamento,
$nfselink)
{


	
	switch ($_campo) {		
		case 'CHAMADA':
			$_texto =    str_replace($_campo, $CHAMADA,$_campo); 	
			break;
		case 'NUMEROOS':
			$_texto =    str_replace($_campo, $NUMEROOS,$_campo); 	
			break;
		case 'NOME':
			$_texto =    str_replace($_campo, $NOME,$_campo);	
			break;
		case 'ENDERECO':
			$_texto =    str_replace($_campo, $ENDERECO,$_campo);	
			break;
		case 'COMPLEMENTO':
			$_texto =    str_replace($_campo, $COMPLEMENTO,$_campo);	
			break;
		case 'BAIRRO':
			$_texto =    str_replace($_campo, $BAIRRO,$_campo);	
			break;
		case 'ENDERECO':
			$_texto =    str_replace($_campo, $ENDERECO,$_campo);	
			break;
		case 'CPFCNPJ':
			$_texto =    str_replace($_campo, $CPFCNPJ,$_campo);	
			break;
		case 'CIDADE':
			$_texto =    str_replace($_campo, $CIDADE,$_campo);	
			break;
		case 'UF':
			$_texto =    str_replace($_campo, $UF,$_campo);	
			break;
		case 'DDD':
			$_texto =    str_replace($_campo, $DDD,$_campo);	
			break;
		case 'EMAIL':
			$_texto =    str_replace($_campo, $EMAIL,$_campo);	
			break;
		case 'FONES':
			$_texto =    str_replace($_campo, $FONES,$_campo);	
			break;
		case 'FONECELULAR1':
			$_texto =    str_replace($_campo, $FONECELULAR1,$_campo);	
			break;
		case 'FONECELULAR2':
			$_texto =    str_replace($_campo, $FONECELULAR2,$_campo);	
			break;
		case 'FONEFIXO':
			$_texto =    str_replace($_campo, $FONEFIXO,$_campo);	
			break;
		case 'PRODUTO':
			$_texto =    str_replace($_campo, $PRODUTO,$_campo);	
			break;
		case 'DTATENDIMENTO':
			$_texto =    str_replace($_campo, $DTATENDIMENTO,$_campo);	
			break;
		case 'DEFEITORECLAMADO':
			$_texto =    str_replace($_campo, $DEFEITORECLAMADO,$_campo);	
			break;
		case 'NOMETECNICO':
			$_texto =    str_replace($_campo, $NOMETECNICO,$_campo);	
			break;
		case 'NOMEATENDENTE':
				$_texto =    str_replace($_campo, $NOMEATENDENTE,$_campo);	
				break;
			
		case 'DEFEITOCOSTATADO':
			$_texto =    str_replace($_campo, $DEFEITOCOSTATADO,$_campo);	
			break;
		case 'SERVICOEXECUTADO':
			$_texto =    str_replace($_campo, $SERVICOEXECUTADO,$_campo);	
			break;
		case 'OBSERVACAO':
			$_texto =    str_replace($_campo, $OBSERVACAO,$_campo);	
			break;
		case 'MODELO':
			$_texto =    str_replace($_campo, $MODELO,$_campo);	
			break;
		case 'SERIE':
			$_texto =    str_replace($_campo, $SERIE,$_campo);	
			break;
		case 'MARCA':
			$_texto =    str_replace($_campo, $MARCA,$_campo);	
			break;
			case 'HORARIOATENDIMENTO':
				$_texto =    str_replace($_campo, $HORARIOATENDIMENTO,$_campo);	
				break;
			case 'VLRSERVICOS':
				$_texto =    str_replace($_campo, number_format($VLRSERVICOS,2,',','.'),$_campo);	
				break;
			case 'VLRPECAS':
				$_texto =    str_replace($_campo, number_format($VLRPECAS,2,',','.'),$_campo);	
				break;
			case 'TOTAL':
				$$_texto =    str_replace($_campo, number_format($TOTAL,2,',','.'),$_campo);	
				break;
			case 'TOTALDESCONTO':
				$_texto =    str_replace($_campo, number_format($TOTALDESCONTO,2,',','.'),$_campo);		
				break;
			case 'EMPRESANOME':
				$_texto =    str_replace($_campo, $EMPRESANOME,$_campo);	
				break;
			case 'EMPRESATELEFONE':
				$_texto =    str_replace($_campo, $EMPRESATELEFONE,$_campo);	
				break;
			case 'desc_peca':
				$_texto =    str_replace($_campo, $desc_peca,$_campo);	
				break;
			case 'orcamento':
				$_texto =    str_replace($_campo, $orcamento,$_campo);	
				break;
			case 'LINKNFSE':				
				$_texto =    str_replace($_campo, $nfselink,$_campo);	
				break;
	}
	
	return $_texto;
}

$query = ("SELECT tokenwats,serviceId,urlwats,NOME_FANTASIA,TELEFONE  from  parametro  ");
$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {

 $tokenwats = 'Authorization: Bearer '.$rst["tokenwats"];
	$serviceId =  $rst["serviceId"] ;
	$urlwats = $rst["urlwats"];
	$EMPRESANOME = $rst["NOME_FANTASIA"];
	$EMPRESATELEFONE= $rst["TELEFONE"];
}

if ($_acao == 1) {   //carregar sele OS wats
		
	$consulta = $pdo->query("SELECT whats_id,whats_titulo FROM ". $_SESSION['BASE'] .".msg_whats  WHERE whats_tipo <> 3 AND whats_ativo = 1 BY whats_titulo ");
	$retorno = $consulta->fetchAll();
	?>
		<select name="lacre" id="lacre" class="form-control input-sm" onclick="_sairmodelo()">
	<?php
	foreach ($retorno as $row) {
		$_id = $row["whats_id"];
		$titulo = $row["whats_titulo"];
		?>		
		<option value="<?=$_id;?>" <?php if($sel == "1") { ?>selected="selected" <?php } ?> ><?=$titulo;?></option>
	
	</select>
	<?php }

}

if ($_acao == 2 or $_acao == 22 ) {   //carregar OS wats

	if( $_acao == 22 ) {
		$CHAMADA = $_parametros["chamadalog"];
		$_idwhats = $_parametros["whatsid"];
		$_fitro_mensagem =  " WHERE whats_id = '$_idwhats' ";
	}else{
		$_idwhats = 1;
		$_fitro_mensagem =  " WHERE  whats_tipo <> 3 AND whats_ativo = 1 ";	
		$CHAMADA = $_parametros["chamada"];
		}
	


	$consulta = "Select 
	Nome_Consumidor,Nome_Rua,Num_Rua,COMPLEMENTO,CGC_CPF,consumidor.CIDADE,BAIRRO,UF,DDD,EMail,DDD_COM,DDD_RES,
	FONE_RESIDENCIAL,FONE_CELULAR,FONE_COMERCIAL,DDD_RES,DDD,DDD_COM,consumidor.NOME_RECADO,
	Cod_Tecnico_Execucao,chamada.descricao as descA,DEFEITO_RECLAMADO,DATA_ATEND_PREVISTO,
	date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,
	date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,
	date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
	date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,
	date_format(Hora_Marcada,'%H:%i') as horaA,
	date_format(Hora_Marcada_Ate,'%H:%i') as horaB, HORARIO_ATENDIMENTO,
	chamada.CODIGO_TECNICO as TEC,TAXA,
	Modelo,serie,marca, Defeito_Constatado,SERVICO_EXECUTADO,DESC_SERVICO,DESC_PECA,OBSERVACAO_atendimento,
	at.usuario_APELIDO AS NOMEATENDENTE, t.usuario_APELIDO AS NOMETECNICO
	FROM  ". $_SESSION['BASE'] .".chamada 
	left JOIN  ". $_SESSION['BASE'] .".usuario AS at ON at.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
	left JOIN  ". $_SESSION['BASE'] .".usuario AS t ON t.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao	
	left JOIN  ". $_SESSION['BASE'] .".situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx	
	left JOIN  ". $_SESSION['BASE'] .".consumidor ON consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR		
	WHERE CODIGO_CHAMADA = '$CHAMADA'";

	$resultOS = $pdo->query($consulta); 
	$executa = $resultOS->fetchAll();


	foreach ($executa as $rst) {	
		$CPFCNPJ = $rst["CGC_CPF"];
		$NOME = $rst["Nome_Consumidor"];
		$ENDERECO = $rst["Nome_Rua"]." ".$rst["Num_Rua"]	;	
		$COMPLEMENTO = $rst["COMPLEMENTO"];
		
		$CIDADE = $rst["CIDADE"];
		$BAIRRO  = $rst["BAIRRO"];
		$UF = $rst["UF"];
		$DDD = $rst["DDD"];
		$DDD_RES = $rst["DDD_RES"];
		$DDD_COM = $rst["DDD_COM"];
		$EMAIL = $rst["EMail"];
		
		$FONE_RESIDENCIAL = RemoveSpecialChar($rst["FONE_RESIDENCIAL"]);
		$FONE_CELULAR = RemoveSpecialChar($rst["FONE_CELULAR"]);
		$FONE_COMERCIAL = RemoveSpecialChar($rst["FONE_COMERCIAL"]);

		$mensagem  = mensagem($mensagem,"[FONECELULAR1]",$FONECELULAR1);
						$mensagem  = mensagem($mensagem,"[FONECELULAR2]",$FONECELULAR2);
						$mensagem  = mensagem($mensagem,"[FONEFIXO]",$FONEFIXO);

		
		if($rst["FONE_RESIDENCIAL"] != "") {
			$FONE = $FONE." ".mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');			
			$FONEFIXO = mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');
		}
		if($rst["FONE_CELULAR"] != "") {
			$FONE = $FONE." ".mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
			$FONECELULAR1 = mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
		}
		if($rst["FONE_COMERCIAL"] != "") {
			$FONE = $FONE." ".mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');
			$FONECELULAR2 = mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');
		}
	
	
		if($rst["HORARIO_ATENDIMENTO"] == 1) { $_per =  "Comercial";}
		if($rst["HORARIO_ATENDIMENTO"] == 2) { $_per ="Manhã";}
		if($rst["HORARIO_ATENDIMENTO"] == 3) { $_per ="Tarde";}
		
	
		//$fone = mascara($rst["DDD"].$rst["FONE_RESIDENCIAL"], 'telefone') . " " . mascara($rst["DDD"].$rst["FONE_COMERCIAL"], 'telefone') . " " . mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
		
		$tecnico_cliente = $rst["Cod_Tecnico_Execucao"];
		$PRODUTO = $rst["descA"];
		$DTATENDIMENTO = $rst["data2"];
		$NOMEATENDENTE =  $rst["NOMEATENDENTE"];
		$NOMETECNICO =  $rst["NOMETECNICO"];
		$DEFEITORECLAMADO = $rst["DEFEITO_RECLAMADO"];
		$DEFEITOCOSTATADO = $rst["Defeito_Constatado"];
		$SERVICOEXECUTADO = $rst["SERVICO_EXECUTADO"];
		$OBSERVACAO = $rst["OBSERVACAO_atendimento"];
		$MODELO = $rst["Modelo"];
		$SERIE = $rst["serie"];
		$MARCA = $rst["marca"];
		$HORARIOATENDIMENTO = $_per." das ".$rst["horaA"]." as ".$rst["horaB"];
		$TOTALDESCONTO = $rst["DESC_PECA"]+$rst["DESC_SERVICO"];
		$vlrtaxa = $rst["TAXA"];
		

	}

	//CARREGAR DESCRIÇÃO PEÇAS
	$orcamento = $orcamento."----------------------------------------------------------------\n";
	$orcamento = $orcamento."Código Descrição Qtde Vlr Total\n";
	$orcamento = $orcamento."----------------------------------------------------------------\n";

	$consulta = $pdo->query("SELECT Minha_Descricao
	FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '".$CHAMADA."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {	
		if($desc_peca != ""){	$desc_peca = $desc_peca.",";
		}
		$desc_peca = $desc_peca.$row['Minha_Descricao'];
		$_aCod = substr($row['CODIGO_FABRICANTE'],0,16);
		$_aDesc =  ($row['Minha_Descricao']);
		$_aVlr = number_format($row['Valor_Peca'],2,',','.');
		$_aTotal = number_format(($row['Valor_Peca']*$row['Qtde_peca']),2,',','.');
		$_aVlr =  str_pad("R$".$_aVlr,12," ",STR_PAD_LEFT);
		$_aTotal =  str_pad("R$".$_aTotal,10," ",STR_PAD_LEFT);
		$_aQtde =  str_pad(" QT:".$row['Qtde_peca'],4," ",STR_PAD_LEFT);

		
		$_aTotalGeral =  $_aTotalGeral + ($row['Valor_Peca']*$row['Qtde_peca']);
		
		
	
		$orcamento = $orcamento.$_aCod."-".$_aDesc.$_aQtde.$_aVlr.$_aTotal."\n";		
		$orcamento = $orcamento."\n";
   }

	//buscar link da nfse 

	$consulta = $pdo->query("SELECT api_id	FROM ". $_SESSION['BASE'] .".empresa where api_id <> '' ");
	   $retorno = $consulta->fetchAll();
	   foreach ($retorno as $row) {		
			
			$consulta = $pdo->query("SELECT nfed_chamada,nfed_arquivo FROM ". $_SESSION['BASE'] .".NFE_DADOS WHERE nfed_chamada = '".$CHAMADA."' and nfed_arquivo <> '' ");
			$retorno = $consulta->fetchAll();
			foreach ($retorno as $row) {		
					$nfselink = $row['nfed_arquivo'];
			}
	   }

		$consulta = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as pecas
		FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '".$CHAMADA."' ");
	   $retorno = $consulta->fetchAll();
	   foreach ($retorno as $row) {		
			$vlrpeca = $row['pecas'];
	   }

	   $consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
		FROM ". $_SESSION['BASE'] .".chamadapeca where Codigo_Peca_OS <> 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '".$CHAMADA."' ");
		$retorno = $consulta->fetchAll();
		foreach ($retorno as $row) {		
			$vlrmaoobra = $row['maoobra'];
			$_totalmaoObra = $_totalmaoObra + $vlrmaoobra ;
			$_aTotalGeral =  $_aTotalGeral + ($_totalmaoObra );
			
		}
		$consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
		FROM ". $_SESSION['BASE'] .".chamadapeca where Codigo_Peca_OS = 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '".$CHAMADA."' ");
		$retorno = $consulta->fetchAll();
			foreach ($retorno as $row) {		
				$vlrtaxa = $vlrtaxa + $row['maoobra'];		
				$_aTotalGeral =  $_aTotalGeral + ($vlrtaxa );		
			}

			$_totaltaxa = 	$vlrtaxa ;
			if($_totaltaxa > 0 ) {
				$_totaltaxa = number_format($_totaltaxa,2,',','.');
				//$_totaltaxa = str_pad($_totaltaxa,102," ",STR_PAD_LEFT);
				
				$orcamento = $orcamento."----------------------------------------------------------------\n";
				$orcamento = $orcamento."TAXA: R$ $_totaltaxa\n";
				

			}
			
			if($_totalmaoObra > 0 ) {
				$_totalmaoObra = number_format($_totalmaoObra,2,',','.');
			//	$_totalmaoObra = str_pad($_totalmaoObra,86," ",STR_PAD_LEFT);
				
				$orcamento = $orcamento."----------------------------------------------------------------\n";
				$orcamento = $orcamento."MÃO DE OBRA: R$ $_totalmaoObra\n";
				

			}
				$_aTotalGeral = number_format($_aTotalGeral,2,',','.');
			//	$_aTotalGeral = str_pad($_aTotalGeral,100," ",STR_PAD_LEFT);
				
				$orcamento = $orcamento."----------------------------------------------------------------\n";
				$orcamento = $orcamento."TOTAL: R$ $_aTotalGeral\n";
				$orcamento = $orcamento."----------------------------------------------------------------\n";

		$VLRPECAS = $vlrpeca;
		$VLRSERVICOS = $vlrmaoobra;
		$VLRTAXA = $vlrtaxa;
		$TOTAL = $VLRPECAS+$VLRSERVICOS+$VLRTAXA-$TOTALDESCONTO;

/*
	if($_dataref != "0000-00-00"){

	
		//verificar se existe link
		$queryOS = "SELECT codigo  from bd_prisma.os 
		WHERE os = '$documento' and login = '".$_SESSION['CODIGOCLI']."' and 
		tecnico = '".$tecnico_cliente."' and   data = '".$_dataref."'";
		$resultOS = $pdo->query($queryOS); 
		$retorno = $resultOS->fetchAll();
		
		foreach ($retorno as $rstOS) {		
			$codigocodificado = $rstOS["codigo"];  
		}
	}
	*/
?>

	<div class="row">
		<div class="col-sm-3">
			<div class="row">
				<div class="col-sm-10">
					<label>Telefones</label>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="margin-left:20px">
					<?php
					if ($FONE_RESIDENCIAL != "") {  ?>
						<div class="row">
							<div class="col-sm-12"><a href="https://wa.me/55<?=$DDD_RES.$FONE_RESIDENCIAL; ?> " target="_blank"><?=mascara($DDD_RES.$FONE_RESIDENCIAL, 'telefone') ; ?> </a>
							</div>
						</div>
					<?php } ?>

				</div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="margin-left:20px">
					<?php	if ($FONE_CELULAR != "") {  ?>
						<div class="row"><div class="col-sm-12"><a href="https://wa.me/55<?=$DDD.$FONE_CELULAR; ?> " target="_blank"><?=mascara($DDD.$FONE_CELULAR, 'telefone') ; ?></a>							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
			if ($FONE_COMERCIAL != "") {  ?>
				<div class="row"><div class="col-sm-12" style="margin-left:20px"><a href="https://wa.me/55<?=$DDD_COM.$FONE_COMERCIAL; ?>" target="_blank"><?=mascara($DDD_COM.$FONE_COMERCIAL, 'telefone') ; ?></a>
					</div>
				</div>
			<?php } ?>

			<div class="row">
				<div class="col-sm-10">
					<button type="button" style="margin:5px ;" class="btn btn-default btn-whatsapp waves-effect waves-light btn-block" onclick=" _copy()"> Copiar
				</div>
			</div>
			
		<?php 
		if($codigocodificado != ""){
			$_link = "https://sistemaprisma.com.br/os/?ref=$codigocodificado";
			?>
		
			<div class="row">
				<div class="col-sm-10">
				<a href="<?=$_link;?>" target="_blank">LINK ACOMPANHAMENTO</a>				
				</div>
			</div>
		<?php } ?>	
			<div class="row">
				<div class="col-sm-10" id="_retidwats">
				<button type="button" style="margin:5px ;" class="btn btn-success btn-whatsapp waves-effect waves-light btn-block" onclick=" _salvarwats2()"> Enviar Whatsapp
				</div>
			</div>
		</div>
	
			<div class="col-sm-9" >	
			<div class="row" style="padding-right: 10px;">
				<div class="col-sm-12" style="margin-bottom: 10px;">
				<label>Mensagens</label>			
					<?php			
						$consulta = $pdo->query("SELECT whats_id,whats_titulo FROM ". $_SESSION['BASE'] .".msg_whats $_fitro_mensagem  GROUP BY whats_titulo ");
						$retorno = $consulta->fetchAll();
						?>
							<select name="mensagem_whats" id="mensagem_whats" class="form-control input-sm" onclick="_sel_msgwhats(this.value)">
							
						<?php
						foreach ($retorno as $row) {
							$_id = $row["whats_id"];
							$titulo = $row["whats_titulo"];
							?>		
							<option value="<?=$_id;?>"  <?php if($sel == "") { ?>selected="selected" <?php } ?>  >(<?=$_id;?>) <?=$titulo;?></option>				
						
						<?php $sel = $_id; }
						?>
						</select>
				</div>
				
			</div>	
			<div class="row">
				<div class="col-sm-12">
				<div  id="retWhats">
				<?php
				$stm = $pdo->prepare("SELECT empresa_envio FROM ".$_SESSION['BASE'].".empresa WHERE empresa_id = '1'");
				$stm->bindParam(1, $empresa, \PDO::PARAM_STR);
				$stm->execute(); 
				$response =  $stm->fetch(\PDO::FETCH_OBJ);             		
				$empresa_envio =  $response->empresa_envio;    

				$consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".msg_whats where whats_id = '$_idwhats'  ");
				$retorno = $consulta->fetchAll();
				foreach ($retorno as $row) {
						$mensagem = $row['whats_mensagem'];
						$NUMEROOS =$CHAMADA;
						$mensagem  = mensagem($mensagem,"[NUMEROOS]",$CHAMADA);
						$mensagem  = mensagem($mensagem,"[CHAMADA]",$CHAMADA);
						$mensagem  = mensagem($mensagem,"[NOME]",$NOME); 
						$mensagem  = mensagem($mensagem,"[ENDERECO]",$ENDERECO);
						$mensagem  = mensagem($mensagem,"[COMPLEMENTO]",$COMPLEMENTO);
						$mensagem  = mensagem($mensagem,"[BAIRRO]",$BAIRRO);						
						$mensagem  = mensagem($mensagem,"[CPFCNPJ]",$CPFCNPJ);
						$mensagem  = mensagem($mensagem,"[CIDADE]",$CIDADE);
						$mensagem  = mensagem($mensagem,"[UF]",$UF);
						$mensagem  = mensagem($mensagem,"[DDD]",$DDD);
						$mensagem  = mensagem($mensagem,"[EMAIL]",$EMAIL);
						$mensagem  = mensagem($mensagem,"[FONES]",$FONE);
						$mensagem  = mensagem($mensagem,"[FONECELULAR1]",$FONECELULAR1);
						$mensagem  = mensagem($mensagem,"[FONECELULAR2]",$FONECELULAR2);
						$mensagem  = mensagem($mensagem,"[FONEFIXO]",$FONEFIXO);
						$mensagem  = mensagem($mensagem,"[PRODUTO]",$PRODUTO);
						$mensagem  = mensagem($mensagem,"[DTATENDIMENTO]",$DTATENDIMENTO);
						$mensagem  = mensagem($mensagem,"[NOMEATENDENTE]",$NOMEATENDENTE);
						$mensagem  = mensagem($mensagem,"[NOMETECNICO]",$NOMETECNICO);
						$mensagem  = mensagem($mensagem,"[DEFEITORECLAMADO]",$DEFEITORECLAMADO);
						$mensagem  = mensagem($mensagem,"[DEFEITOCOSTATADO]",$DEFEITOCOSTATADO);
						$mensagem  = mensagem($mensagem,"[SERVICOEXECUTADO]",$SERVICOEXECUTADO);
						$mensagem  = mensagem($mensagem,"[OBSERVACAO]",$OBSERVACAO);
						$mensagem  = mensagem($mensagem,"[MODELO]",$MODELO);						
						$mensagem  = mensagem($mensagem,"[SERIE]",$SERIE);
						$mensagem  = mensagem($mensagem,"[MARCA]",$MARCA);
						$mensagem  = mensagem($mensagem,"[HORARIOATENDIMENTO]",$HORARIOATENDIMENTO);						
						$mensagem  = mensagem($mensagem,"[VLRSERVICOS]",number_format($VLRSERVICOS,2,',','.'));
						$mensagem  = mensagem($mensagem,"[VLRPECAS]",number_format($VLRPECAS,2,',','.'));
						$mensagem  = mensagem($mensagem,"[TOTAL]",number_format($TOTAL,2,',','.'));
						$mensagem  = mensagem($mensagem,"[TOTALDESCONTO]",number_format($TOTALDESCONTO,2,',','.'));
						$mensagem  = mensagem($mensagem,"[EMPRESANOME]",$EMPRESANOME);
						$mensagem  = mensagem($mensagem,"[EMPRESATELEFONE]",$EMPRESATELEFONE);
						$mensagem  = mensagem($mensagem,"[DESCRICAOPECAS]",$desc_peca);
						$mensagem  = mensagem($mensagem,"[DETALHAMENTO_ORCAMENTO]",$orcamento);	
						$mensagem  = mensagem($mensagem,"[LINKNFSE]",$nfselink);	
						// Array original
						$parameters = array();
							
					$mensagemArray = explode("[", $row['whats_mensagem']);
					// Use a função preg_match_all para encontrar todas as palavras dentro dos colchetes
					$mensagemArray = preg_match_all('/\[(.*?)\]/', $row['whats_mensagem'], $matches);
					// Se houver correspondências, $matches[1] conterá as palavras dentro dos colchetes
					if (!empty($matches[1])) {
						// Loop através das palavras dentro dos colchetes
						foreach ($matches[1] as $palavra) {							
							$_variaveis = "[".$palavra."];";
							// Adicionar novas variáveis		
									
							$novoValor = mensagemArray($palavra,$NUMEROOS,$CHAMADA,
							$NOME, 
							$ENDERECO,
							$COMPLEMENTO,
							$BAIRRO,						
							$CPFCNPJ,
							$CIDADE,
							$UF,
							$DDD,
							$EMAIL,
							$FONE,
							$FONECELULAR1,
							$FONECELULAR2,
							$FONEFIXO,
							$PRODUTO,
							$DTATENDIMENTO,
							$NOMEATENDENTE,
							$NOMETECNICO,
							$DEFEITORECLAMADO,
							$DEFEITOCOSTATADO,
							$SERVICOEXECUTADO,
							$OBSERVACAO,
							$MODELO,						
							$SERIE,
							$MARCA,
							$HORARIOATENDIMENTO,						
							$VLRSERVICOS,
							$VLRPECAS,
							$TOTAL,
							$TOTALDESCONTO,
							$EMPRESANOME,
							$EMPRESATELEFONE,
							$desc_peca,
							$orcamento,
						    $nfselink);	 //"valor_da_outra_variavel";
							if($novoValor != "") { 
								if($empresa_envio == 3){
									$parameters[$palavra] = $novoValor;
								}elseif($empresa_envio == 4  or $empresa_envio == 5){
									if($stringVariavel == ""){
										$stringVariavel = '"'.$novoValor;
									}else{
										$stringVariavel = $stringVariavel.','.$novoValor;
									}
									
								}
								
							}else{
								if($empresa_envio == 4 or $empresa_envio == 5){
									$stringVariavel = $stringVariavel.','."";
								}
							}
						}
					}
						
						
				}
				
				if($empresa_envio == 3){
					// Converta o array em JSON
					$json_parameters = json_encode($parameters);
					
				}elseif($empresa_envio == 4 or $empresa_envio == 5){
					$json_parameters = $stringVariavel.'"';
				}
					

		
			
				?>
				<textarea  name="textowats" id="textowats" style="height: 411px; width: 653px;" ><?=htmlspecialchars($mensagem);?></textarea>
				<textarea  name="textowatsparametros" id="textowatsparametros"  style="display:none ;"><?=($json_parameters);?></textarea>	
				</div>
				</div>
				
			</div>		
				
			</div>
	

		
	</div>	
	<div class="row">
			<div class="col-sm-3">
			</div>
				<div class="col-sm-7" >
					
				</div>
	</div>
<?php


	exit();
}

if ($_acao == 3) {   //carregar mensagem O.S wats

	
	$_id = $_parametros['mensagem_whats'];

	$CHAMADA = $_parametros["chamada"];
	$consulta = "Select 
	Nome_Consumidor,Nome_Rua,Num_Rua,COMPLEMENTO,CGC_CPF,consumidor.CIDADE,BAIRRO,UF,DDD,EMail,
	FONE_RESIDENCIAL,FONE_CELULAR,FONE_COMERCIAL,DDD_RES,DDD,DDD_COM,consumidor.NOME_RECADO,
	Cod_Tecnico_Execucao,chamada.descricao as descA,DEFEITO_RECLAMADO,DATA_ATEND_PREVISTO,
	date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,
	date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,
	date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
	date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,
	date_format(Hora_Marcada,'%H:%i') as horaA,
	date_format(Hora_Marcada_Ate,'%H:%i') as horaB, HORARIO_ATENDIMENTO,
	chamada.CODIGO_TECNICO as TEC,TAXA,
	Modelo,serie,marca, Defeito_Constatado,SERVICO_EXECUTADO,DESC_SERVICO,DESC_PECA,OBSERVACAO_atendimento,
	at.usuario_APELIDO AS NOMEATENDENTE, t.usuario_APELIDO AS NOMETECNICO
	FROM  ". $_SESSION['BASE'] .".chamada 
	left JOIN  ". $_SESSION['BASE'] .".usuario AS at ON at.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
	left JOIN  ". $_SESSION['BASE'] .".usuario AS t ON t.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao	
	left JOIN  ". $_SESSION['BASE'] .".situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx	
	left JOIN  ". $_SESSION['BASE'] .".consumidor ON consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR		
	WHERE CODIGO_CHAMADA = '$CHAMADA'";

	$resultOS = $pdo->query($consulta); 
	$executa = $resultOS->fetchAll();


	foreach ($executa as $rst) {	
		$CPFCNPJ = $rst["CGC_CPF"];
		$NOME = $rst["Nome_Consumidor"];
		$ENDERECO = $rst["Nome_Rua"]." ".$rst["Num_Rua"]	;	
		$COMPLEMENTO = $rst["COMPLEMENTO"];
		
		$CIDADE = $rst["CIDADE"];
		$BAIRRO  = $rst["BAIRRO"];
		$UF = $rst["UF"];
		$DDD = $rst["DDD"];
		$EMAIL = $rst["EMail"];
		
		$FONE_RESIDENCIAL = RemoveSpecialChar($rst["FONE_RESIDENCIAL"]);
		$FONE_CELULAR = RemoveSpecialChar($rst["FONE_CELULAR"]);
		$FONE_COMERCIAL = RemoveSpecialChar($rst["FONE_COMERCIAL"]);

		$mensagem  = mensagem($mensagem,"[FONECELULAR1]",$FONECELULAR1);
						$mensagem  = mensagem($mensagem,"[FONECELULAR2]",$FONECELULAR2);
						$mensagem  = mensagem($mensagem,"[FONEFIXO]",$FONEFIXO);

		
		if($rst["FONE_RESIDENCIAL"] != "") {
			$FONE = $FONE." ".mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');			
			$FONEFIXO = mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');
		}
		if($rst["FONE_CELULAR"] != "") {
			$FONE = $FONE." ".mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
			$FONECELULAR1 = mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
		}
		if($rst["FONE_COMERCIAL"] != "") {
			$FONE = $FONE." ".mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');
			$FONECELULAR2 = mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');
		}

		if($rst["HORARIO_ATENDIMENTO"] == 1) { $_per =  "Comercial";}
		if($rst["HORARIO_ATENDIMENTO"] == 2) { $_per ="Manhã";}
		if($rst["HORARIO_ATENDIMENTO"] == 3) { $_per ="Tarde";}
		
	
		

		//$fone = mascara($rst["DDD"].$rst["FONE_RESIDENCIAL"], 'telefone') . " " . mascara($rst["DDD"].$rst["FONE_COMERCIAL"], 'telefone') . " " . mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
		
		$tecnico_cliente = $rst["Cod_Tecnico_Execucao"];
		$PRODUTO = $rst["descA"];
		$DTATENDIMENTO = $rst["data2"];
		$NOMEATENDENTE =  $rst["NOMEATENDENTE"];
		$NOMETECNICO =  $rst["NOMETECNICO"];
		$DEFEITORECLAMADO = $rst["DEFEITO_RECLAMADO"];
		$DEFEITOCOSTATADO = $rst["Defeito_Constatado"];
		$SERVICOEXECUTADO = $rst["SERVICO_EXECUTADO"];
		$OBSERVACAO = $rst["OBSERVACAO_atendimento"];
		$MODELO = $rst["Modelo"];
		$SERIE = $rst["serie"];
		$MARCA = $rst["marca"];		
		$HORARIOATENDIMENTO = $_per." das ".$rst["horaA"]." as ".$rst["horaB"];

		$TOTALDESCONTO = $rst["DESC_PECA"]+$rst["DESC_SERVICO"];
		$vlrtaxa = $rst["TAXA"];
		

	}

		//CARREGAR DESCRIÇÃO PEÇAS
	$orcamento = $orcamento."----------------------------------------------------------------\n";
	$orcamento = $orcamento."Código Descrição Qtde Vlr Total\n";
	$orcamento = $orcamento."----------------------------------------------------------------\n";
		$consulta = $pdo->query("SELECT Minha_Descricao, CODIGO_FABRICANTE,Valor_Peca,Qtde_peca
		FROM ". $_SESSION['BASE'] .".chamadapeca 
		LEFT JOIN ". $_SESSION['BASE'] .".itemestoque ON   Codigo_Peca_OS = CODIGO_FORNECEDOR
		where TIPO_LANCAMENTO = 0 and	Numero_OS = '".$CHAMADA."' ");
	   $retorno = $consulta->fetchAll();
	   foreach ($retorno as $row) {	
			if($desc_peca != ""){	$desc_peca = $desc_peca.",";
			}
				$desc_peca = $desc_peca.$row['Minha_Descricao'];
				$_aCod = substr($row['CODIGO_FABRICANTE'],0,16);
				$_aDesc =  ($row['Minha_Descricao']);
				$_aVlr = number_format($row['Valor_Peca'],2,',','.');
				$_aTotal = number_format(($row['Valor_Peca']*$row['Qtde_peca']),2,',','.');
				$_aVlr =  str_pad("R$".$_aVlr,12," ",STR_PAD_LEFT);
				$_aTotal =  str_pad("R$".$_aTotal,15," ",STR_PAD_LEFT);
				$_aQtde =  str_pad(" QT:".$row['Qtde_peca'],4," ",STR_PAD_LEFT);

				
				$_aTotalGeral =  $_aTotalGeral + ($row['Valor_Peca']*$row['Qtde_peca']);
				
				
				
				$orcamento = $orcamento.$_aCod."-".$_aDesc.$_aQtde.$_aVlr.$_aTotal."\n";		
				$orcamento = $orcamento."\n";
			
	   }

	  //buscar link da nfse 

	$consulta = $pdo->query("SELECT api_id	FROM ". $_SESSION['BASE'] .".empresa where api_id > '1' ");
	   $retorno = $consulta->fetchAll();
	   foreach ($retorno as $row) {				
			$consulta = $pdo->query("SELECT nfed_chamada,nfed_arquivo FROM ". $_SESSION['BASE'] .".NFE_DADOS WHERE nfed_chamada = '".$CHAMADA."' and nfed_arquivo <> '' ");
			$retorno = $consulta->fetchAll();
			foreach ($retorno as $row) {		
					$nfselink = $row['nfed_arquivo'];
			}
	   }
	  

	$consulta = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as pecas
		FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '".$CHAMADA."' ");
	   $retorno = $consulta->fetchAll();
	   foreach ($retorno as $row) {		
			$vlrpeca = $row['pecas'];
	   }

	   
	 $consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
	FROM ". $_SESSION['BASE'] .".chamadapeca where Codigo_Peca_OS <> 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '".$CHAMADA."' ");
	$retorno = $consulta->fetchAll();
		foreach ($retorno as $row) {		
			$vlrmaoobra = $row['maoobra'];
			$_totalmaoObra = $_totalmaoObra + $vlrmaoobra ;
			$_aTotalGeral =  $_aTotalGeral + ($_totalmaoObra );
		}
		$consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
		FROM ". $_SESSION['BASE'] .".chamadapeca where Codigo_Peca_OS = 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '".$CHAMADA."' ");
		$retorno = $consulta->fetchAll();
			foreach ($retorno as $row) {		
				$vlrtaxa = $vlrtaxa + $row['maoobra'];		
				$_aTotalGeral =  $_aTotalGeral + ($vlrtaxa );
			}
			$_totaltaxa = 	$vlrtaxa ;
			if($_totaltaxa > 0 ) {
				$_totaltaxa = number_format($_totaltaxa,2,',','.');
				//$_totaltaxa = str_pad($_totaltaxa,102," ",STR_PAD_LEFT);
				
				$orcamento = $orcamento."----------------------------------------------------------------\n";
				$orcamento = $orcamento."TAXA: R$ $_totaltaxa\n";
				

			}
			
			if($_totalmaoObra > 0 ) {
				$_totalmaoObra = number_format($_totalmaoObra,2,',','.');
			//	$_totalmaoObra = str_pad($_totalmaoObra,86," ",STR_PAD_LEFT);
				
				$orcamento = $orcamento."---------------------------------------------------------------- \n";
				$orcamento = $orcamento."MÃO DE OBRA: R$ $_totalmaoObra\n";
				

			}
				$_aTotalGeral = number_format($_aTotalGeral,2,',','.');
				//$_aTotalGeral = str_pad($_aTotalGeral,100," ",STR_PAD_LEFT);
				
				$orcamento = $orcamento."---------------------------------------------------------------- \n";
				$orcamento = $orcamento."TOTAL: R$ $_aTotalGeral\n";
				


		$VLRPECAS = $vlrpeca;
		$VLRSERVICOS = $vlrmaoobra;
		$VLRTAXA = $vlrtaxa;
		$TOTAL = $VLRPECAS+$VLRSERVICOS+$VLRTAXA-$TOTALDESCONTO;

		$stm = $pdo->prepare("SELECT empresa_envio FROM ".$_SESSION['BASE'].".empresa WHERE empresa_id = '1'");
		$stm->bindParam(1, $empresa, \PDO::PARAM_STR);
		$stm->execute(); 
		$response =  $stm->fetch(\PDO::FETCH_OBJ);             		
		$empresa_envio =  $response->empresa_envio;    

		$consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".msg_whats where whats_id = '".$_id."'  ");
		$retorno = $consulta->fetchAll();
		foreach ($retorno as $row) {
						$mensagem = $row['whats_mensagem'];
						$NUMEROOS =$CHAMADA;
	
						$mensagem  = mensagem($mensagem,"[NUMEROOS]",$CHAMADA);
						$mensagem  = mensagem($mensagem,"[CHAMADA]",$CHAMADA);
						$mensagem  = mensagem($mensagem,"[NOME]",$NOME);
						$mensagem  = mensagem($mensagem,"[ENDERECO]",$ENDERECO);
						$mensagem  = mensagem($mensagem,"[COMPLEMENTO]",$COMPLEMENTO);
						$mensagem  = mensagem($mensagem,"[BAIRRO]",$BAIRRO);						
						$mensagem  = mensagem($mensagem,"[CPFCNPJ]",$CPFCNPJ);
						$mensagem  = mensagem($mensagem,"[CIDADE]",$CIDADE);
						$mensagem  = mensagem($mensagem,"[UF]",$UF);
						$mensagem  = mensagem($mensagem,"[DDD]",$DDD);
						$mensagem  = mensagem($mensagem,"[EMAIL]",$EMAIL);
						$mensagem  = mensagem($mensagem,"[FONES]",$FONE);
						$mensagem  = mensagem($mensagem,"[FONECELULAR1]",$FONECELULAR1);
						$mensagem  = mensagem($mensagem,"[FONECELULAR2]",$FONECELULAR2);
						$mensagem  = mensagem($mensagem,"[FONEFIXO]",$FONEFIXO);
						$mensagem  = mensagem($mensagem,"[PRODUTO]",$PRODUTO);
						$mensagem  = mensagem($mensagem,"[DTATENDIMENTO]",$DTATENDIMENTO);
						$mensagem  = mensagem($mensagem,"[NOMEATENDENTE]",$NOMEATENDENTE);
						$mensagem  = mensagem($mensagem,"[NOMETECNICO]",$NOMETECNICO);
						$mensagem  = mensagem($mensagem,"[DEFEITORECLAMADO]",$DEFEITORECLAMADO);
						$mensagem  = mensagem($mensagem,"[DEFEITOCOSTATADO]",$DEFEITOCOSTATADO);
						$mensagem  = mensagem($mensagem,"[SERVICOEXECUTADO]",$SERVICOEXECUTADO);
						$mensagem  = mensagem($mensagem,"[OBSERVACAO]",$OBSERVACAO);
						$mensagem  = mensagem($mensagem,"[MODELO]",$MODELO);						
						$mensagem  = mensagem($mensagem,"[SERIE]",$SERIE);
						$mensagem  = mensagem($mensagem,"[MARCA]",$MARCA);
						$mensagem  = mensagem($mensagem,"[HORARIOATENDIMENTO]",$HORARIOATENDIMENTO);	
						$mensagem  = mensagem($mensagem,"[VLRSERVICOS]",number_format($VLRSERVICOS,2,',','.'));
						$mensagem  = mensagem($mensagem,"[VLRPECAS]",number_format($VLRPECAS,2,',','.'));
						$mensagem  = mensagem($mensagem,"[TOTAL]",number_format($TOTAL,2,',','.'));
						$mensagem  = mensagem($mensagem,"[TOTALDESCONTO]",number_format($TOTALDESCONTO,2,',','.'));
						$mensagem  = mensagem($mensagem,"[EMPRESANOME]",$EMPRESANOME);
						$mensagem  = mensagem($mensagem,"[EMPRESATELEFONE]",$EMPRESATELEFONE);
						$mensagem  = mensagem($mensagem,"[DESCRICAOPECAS]",$desc_peca);	
						$mensagem  = mensagem($mensagem,"[DETALHAMENTO_ORCAMENTO]",$orcamento);	
						$mensagem  = mensagem($mensagem,"[LINKNFSE]",$nfselink);	
						
											
						// Array original
						$parameters = array();
											
						$mensagemArray = explode("[", $row['whats_mensagem']);
						// Use a função preg_match_all para encontrar todas as palavras dentro dos colchetes
						$mensagemArray = preg_match_all('/\[(.*?)\]/', $row['whats_mensagem'], $matches);
						// Se houver correspondências, $matches[1] conterá as palavras dentro dos colchetes
						if (!empty($matches[1])) {
							// Loop através das palavras dentro dos colchetes
							foreach ($matches[1] as $palavra) {							
								$_variaveis = "[".$palavra."];";
								// Adicionar novas variáveis		
										
								$novoValor = mensagemArray($palavra,$NUMEROOS,$CHAMADA,
								$NOME, 
								$ENDERECO,
								$COMPLEMENTO,
								$BAIRRO,						
								$CPFCNPJ,
								$CIDADE,
								$UF,
								$DDD,
								$EMAIL,
								$FONE,
								$FONECELULAR1,
								$FONECELULAR2,
								$FONEFIXO,
								$PRODUTO,
								$DTATENDIMENTO,
								$NOMEATENDENTE,
								$NOMETECNICO,
								$DEFEITORECLAMADO,
								$DEFEITOCOSTATADO,
								$SERVICOEXECUTADO,
								$OBSERVACAO,
								$MODELO,						
								$SERIE,
								$MARCA,
								$HORARIOATENDIMENTO,						
								$VLRSERVICOS,
								$VLRPECAS,
								$TOTAL,
								$TOTALDESCONTO,
								$EMPRESANOME,
								$EMPRESATELEFONE,
								$desc_peca,
								$orcamento,
							    $nfselink);	 //"valor_da_outra_variavel";
								if($novoValor != "") { 
									
								if($empresa_envio == 3 ){
									$parameters[$palavra] = $novoValor;
								}elseif($empresa_envio == 4 or $empresa_envio == 5){
									/*
									if($stringVariavel == ""){
										$stringVariavel = '"'.$novoValor;
									}else{
										$stringVariavel = $stringVariavel.','.$novoValor;
									}
									*/
									if($empresa_envio == 4 ){
											if($stringVariavel == ""){
												$stringVariavel = '"'.$novoValor;
											}else{
												$stringVariavel = $stringVariavel.','.$novoValor;
											}
									}
									if( $empresa_envio == 5){
										if($stringVariavel == ""){
											$stringVariavel = '"'.$novoValor;
										}else{
											$stringVariavel = $stringVariavel.'","'.$novoValor;
										}
										// $stringVariavel = $stringVariavel.'","'.$novoValor;
										}
								}
								}
								else{
									if($empresa_envio == 4 ){
										
										$stringVariavel = $stringVariavel.','."-";
									}
									if( $empresa_envio == 5){
										
										$stringVariavel = $stringVariavel.'","'."-";
									}
								}
							}
						}
							
							
					}
					if($empresa_envio == 3 ){
						// Converta o array em JSON
						$json_parameters = json_encode($parameters);
						
					}elseif($empresa_envio == 4 or $empresa_envio == 5 ){
						$json_parameters = $stringVariavel.'"';
					}
					// style="display:none ;"
				
	?>
	<textarea type="hidden" name="textowats" id="textowats" style="height: 411px; width: 653px;" ><?=htmlspecialchars($mensagem);?></textarea>
	<textarea  name="textowatsparametros" id="textowatsparametros"  style="display:none ;" ><?=($json_parameters);?></textarea>	
	<?php 

}
				