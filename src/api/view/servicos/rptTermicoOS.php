<?php 

session_start();

	

	use Database\MySQL;

	$pdo = MySQL::acessabd();
	
	$livro = "1";
	
	$codigoos = $_parametros["chamada"];

	//LOG

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
      
  $_tipoAtividade = 210;
  $_documentoAtividade = $_os;
  $_assuntoAtividade = "Impressão O.S Termico 80mm";
  $_SQLLOG = "INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
      l_tipo, l_datahora, l_doc,  l_usuario,  l_desc) 
          VALUES (
           '$_tipoAtividade','$datahora','$codigoos','".$_SESSION["APELIDO"]."','$_assuntoAtividade')";
		   $stm = $pdo->prepare($_SQLLOG);
		   $stm->execute();



//FIM LOG

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

			$msgoficina = $rst['Msg_Oficina'];
		
		
		}
	}

	
	$cnpj = substr($cnpj,0,2).".".substr($cnpj,2,3).".".substr($cnpj,5,3)."/".substr($cnpj,8,4)."-".substr($cnpj,12,2);


	if($mensagemCupom == ""){
		$mensagemCupom = "OBRIGADO PELA SUA PREFERÊNCIA, VOLTE SEMPRE!";
	}

$sql = ("Select *,a.usuario_LOGIN as atendente, b.usuario_APELIDO as tecnico,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%H:%i') as horaA,date_format(Hora_Marcada_Ate,'%H:%i') as horaB,
consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(Data_Nota, '%d/%m/%Y') as datanf,  date_format(DATA_CHAMADA, '%d/%m/%Y') as data1 
from  " . $_SESSION['BASE'] . ".chamada 
left JOIN  " . $_SESSION['BASE'] . ".usuario as a ON  a.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
left JOIN  " . $_SESSION['BASE'] . ".usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
left JOIN  " . $_SESSION['BASE'] . ".situacaoos_elx  ON COD_SITUACAO_OS = SituacaoOS_Elx
left JOIN  " . $_SESSION['BASE'] . ".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
left JOIN  " . $_SESSION['BASE'] . ".fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
left JOIN  " . $_SESSION['BASE'] . ".cor_sga ON Cod_Cor = ID_COR
left JOIN  " . $_SESSION['BASE'] . ".tipo_gas ON g_cod = tipoGAS
LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id
WHERE CODIGO_CHAMADA = '$codigoos' ");

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



<title>ORDEM DE SERVIÇO</title>


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
	font-family: Arial;
	font-size: 1git3px;
	font-weight: 600px;
	
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
	</style>

<body>
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
					<td class="center"> <?=$razao;?><br>CNPJ <?=$cnpj;?> <br><?=$endereco?>, <?=$nmro;?>, <?=$Complemento_Endereco;?> <?=$bairro;?> <?=$cidade;?> <?=$estado;?> <?=$cep;?> FONE <?=$telefone;?> I.E. <?=$inscricao;?> <br>
					<span >
					<hr><h3>ORDEM DE SERVI&Ccedil;O  <?=$rstPedido["CODIGO_CHAMADA"];?></h3>
					<?=$rstPedido["g_descricao"];?>
					<hr></span></td>
				</tr>






<tr>

  <td     class="style32 ">
  <strong>
  ATENDENTE:
</strong>
	<?=$rstPedido["atendente"];?>
	
  </td>
</tr>

<tr>

  <td      class="style32 ">
  	<strong>DATA ABERTURA:</strong><?=$rstPedido["data1"];?>
	<br>
	<strong>DATA PREVIS&Atilde;O:</strong><?=$rstPedido["data2"];?>
	<hr>
  </td>
</tr>
<tr>
  <td      class="style32 ">
  <strong>CLIENTE:</strong>
  	  <?=$rstPedido["Nome_Consumidor"];?>
	  </td>
</tr>
<tr>
  <td      class="style32 " >
  <strong>TELEFONE:</strong>
	<?=$rstPedido["FONE_RESIDENCIAL"];?>/<?=$rstPedido["FONE_CELULAR"];?>/<?=$rstPedido["FONE_COMERCIAL"];?><? if($rstPedido["RAMAL"] != "") { echo $rstPedido["RAMAL"]; } ?>
 </td>
</tr>
<tr>
  <td      class="style32 " >
  <strong>ENDERE&Ccedil;O:</strong>
		<?=$rstPedido["Nome_Rua"];?> -
		<?=$rstPedido["Num_Rua"]; 
		 if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
	</td>
</tr>
<tr>
  <td      class="style32 " > 
  <strong>BAIRRO:</strong>
		<?=$rstPedido["bairros"];?>
	 </td>
</tr>

<tr>
  <td      class="style32 " > 
  	<strong>CEP:</strong>
		<?=$rstPedido["ceps"];?>	
		<strong>CIDADE:</strong>
		<?=$rstPedido["cidades"];?><strong>	UF:</strong>

	<?=$rstPedido["estado"];?>

	</td>

</tr>

<tr>
  <td     class="style32 " >
  <strong>CONTATO: </strong>
	<?=$rstPedido["rec"];?> 
   </td>
</tr>
<tr>
  <td      class="style32 ">
  <strong>LOCAL REFERENCIA:</strong>
	<?=$rstPedido["LOCAL_REFERENCIA"]; ?> <hr> </td>	
</tr>
<tr>
  <td   class="style32 " >
	  <strong>APARELHO:</strong><?=$rstPedido["descA"];?>
     </td>
</tr>
<tr>

  <td   class="style32 " >
  <strong>MARCA:</strong>
	<?=$rstPedido["marca"];?>      </td>

</tr>
<tr>
  <td   class="style32 " >
  <strong>DEFEITO RECLAMADO:</strong>
	<?=$rstPedido["DEFEITO_RECLAMADO"];?>      </td>
</tr>
<tr>
  <td  class="style32 "  >
  <strong>MODELO:</strong>
	<?=$rstPedido["Modelo"];?>     
 </td>

</tr>

<tr>

  <td   class="style32 " >
  <strong>SERIE/IMEI:</strong>
	<?=$rstPedido["serie"];?>
</td>
</tr>
<tr>
  <td   class="style32 " ><strong>CONDI&Ccedil;&Otilde;ES PRODUTO:</strong>

	<?=$rstPedido["Estado_Aparelho"];?>      </td>

</tr>
<?php if($rstPedido["SERVICO_EXECUTADO"] != "") {  ?>
<tr>

  <td  class="style32 "  ><strong>SERVIÇO EXECUTADO:</strong>

	<?=$rstPedido["SERVICO_EXECUTADO"];?>      </td>

</tr>
<?php } ?>

<tr>

  <td  class="style32 "  ><strong>OBSERVA&Ccedil;&Atilde;O/SENHA:</strong>

	<?=$rstPedido["OBSERVACAO_atendimento"];?>      </td>

</tr>

<tr>

  <td   class="style32 " ><strong>ACESSORIOS:</strong>

	<?=$rstPedido["Acessorios"];?>      </td>

</tr>
  <tr>
	  <td   class="style32 " ><strong>VALOR TOTAL:</strong>
		  <?=number_format($rstPedido['VALOR_SERVICO'] + $rstPedido['VALOR_PECA'] + $rstPedido['TAXA'] - $rstPedido['DESC_SERVICO'],2,',','.');?>
		  <hr>
	  </td>
  </tr>



 <tr>
  <td style="text-align: justify;"><?=$msgoficina;?>
		  <br>
		  <br>
		  <span style="font-size:16px;">Estou ciente das observações acima:</span>
	 
	  <hr>
  </td>
</tr> 



<tr>

  <td       ><div align="center" class="style75 style81 style82">

	<?=$rstPedido["Nome_Consumidor"];?>

  </div></td>

</tr>
<tr>
  <td height="102"   class="center"     >&nbsp;</td>
</tr>
<tr>
  <td class="center"><hr>
    <h3>EM ANÁLISE  O.S <?=$rstPedido["CODIGO_CHAMADA"];?></h3>
    </h3>
					<?=$rstPedido["g_descricao"];?>
					<hr>
    </td>
</tr>
<tr>
  <td       ><span class="style32 "><strong>DATA ABERTURA:</strong>
      <?=$rstPedido["data1"];?>
  </span></td>
</tr>
<tr>
  <td   class="style32 " >
	  <strong>APARELHO:</strong><?=$rstPedido["descA"];?>
     </td>
</tr>
<tr>

  <td   class="style32 " >
  <strong>MARCA:</strong>
	<?=$rstPedido["marca"];?>      </td>

</tr>
<tr>
  <td   class="style32 " >
  <strong>DEFEITO RECLAMADO:</strong>
	<?=$rstPedido["DEFEITO_RECLAMADO"];?>      </td>
</tr>
<tr>
  <td  class="style32 "  >
  <strong>MODELO:</strong>
	<?=$rstPedido["Modelo"];?>     
 </td>

</tr>

<tr>

  <td   class="style32 " >
  <strong>SERIE/IMEI:</strong>
	<?=$rstPedido["serie"];?>
</td>
</tr>
<tr>
  <td       ><hr /></td>
</tr>
<tr>
  <td       ><span class="style32 "><strong>1) Feito Pedido de Peça </strong></span></td>
</tr>
<tr>
  <td       ><span class="style32 "><strong>Nº ___________ em _____/ _____ / _____</strong></span></td>
</tr>
<tr>
  <td       ><span class="style32 "><strong>2) Avisado consumidor sobre peça faltante e prazo </strong></span></td>
</tr>
<tr>
  <td       >(dscx)<span class="style32 "><strong> _____/ _____ / _____ </strong></span></td>
</tr>
<tr>
  <td       ><strong>OBS: </strong></span></td>
</tr>
<tr>
  <td       >&nbsp;</td>
</tr>
<tr>
  <td       >&nbsp;</td>
</tr>
<tr>
  <td       >&nbsp;</td>
</tr>


</table>
<?php  } exit();?>