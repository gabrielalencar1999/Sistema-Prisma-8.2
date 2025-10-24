<?php

require_once('../../api/config/config.inc.php');
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



// $id = $_POST['id'];

$data_Agenda = $_parametros["dtagenda"];
$descricao = ($_parametros["agendadescricao"]);
$_idref = $_parametros["_idref"];

/*
	   $dia = substr("$data_Agenda",0,2); 

	   $mes = substr("$data_Agenda",3,2); 

	   $ano = substr("$data_Agenda",6,4); 

	   $data_agenda = $ano."-".$mes."-".$dia; 
*/
$documento = $_parametros["chamada"];

$situacao = $_parametros["sitagenda"];

// $situacao2 = $_POST["situacao2"];

$prioridade = $_parametros['prioridade'];

$referencia = $_parametros['ref'];

$nome = $_parametros['nomecliente'];

// $contato = $_POST['contato'];

//  $telefone = $_POST['telefone'];

//  $assunto = $_POST['assunto'];

$cliente = $_parametros['codigo']; //idcliente

//$usuario =  $tecnico;

$usuario = $_SESSION['tecnico'];; //codigo login

//$usuariologado =  $_SESSION["login"]; //nome
$usuariologado =  $_SESSION["APELIDO"]; //nome
$consulta = "Select ult_osimport,	NOME_FANTASIA,TELEFONE from parametro";
$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($executa)) {	
	$_ult_osimport = $rst["ult_osimport"];
	$_nome_empresa = $rst["NOME_FANTASIA"];
	$_telefone_empresa = $rst["TELEFONE"];
}

if ($_acao == 10) {


	//acompanhamento
	//$documento = $_parametros["osagenda"];
	$roteiro = $_parametros['roteiro']; // chamado da tela roteiro
?>

	<input type="hidden" id="osagenda" name="osagenda" value="<?= $documento; ?>"><?php
																				//if($_acao == 0) {  

	$consultaMov = "SELECT *,DATE_FORMAT(ac_hora,'%d/%m/%Y %H:%i') as dt
	FROM acompanhamento	
	LEFT JOIN situacaoos_elx ON ac_sitos = COD_SITUACAO_OS
	WHERE ac_OS = '$documento' OR ac_cliente  = '$cliente' AND ac_indagenda = '1'  AND ac_inativado = '0' ORDER BY ac_id DESC";

	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	?>

	<input name="dtagenda" type="hidden" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
	

	<div class="card-box" style="height: 300px;  overflow-y: scroll;">
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="text-center" style="width:200px ;">Dt Posicionamento</th>
					<th class="text-left">Descrição</th>
					<th class="text-left">Situação OS</th>
					<th class="text-center">Usuário</th>
					<th class="text-center">Ref</th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = mysqli_fetch_array($resultado)) {
					$_stylecor = "";
					if($row["cor_sitcodigo"] != "") {
						$_stylecor = 'style="color:'.$row["cor_sitcodigofonte"].'; background-color: '.$row["cor_sitcodigo"].'"';
					}
					
					if($row["ac_inativado"] == 1) {
						$_Totalinativado =  $_Totalinativado + 1;
					}
					
					if($row["ac_inativado"] == 0) {
				?>
					<tr class="gradeX">
						<td class="text-center"><?= $row["dt"]; ?></td>
						<td class="text-left" style="min-width: 300px ;"><?=nl2br($row["ac_descricao"]); ?></td>
						<td class="text-center"> <span class="label label-table label-<?= $row['cor_sit'] ?>" <?=$_stylecor;?>><?= $row['DESCRICAO'] ?></span></td>
						<td class="text-center"><?= $row['ac_usuarionome']; ?></td>
						<td class="text-center"><?= $row['ac_OS']; ?></td>
						<td class="text-center"><a href="#"  class="table-action-btn" title="INATIVAR" onclick="_inativar('<?= $row['ac_id']; ?>')"><i class="md md-not-interested"></i></a></td>
					</tr>
				<?php }  }
				//verificar historico 
				if($documento < $_ult_osimport){
					$consultaMov = "SELECT *,DATE_FORMAT(ac_hora,'%d/%m/%Y %H:%i') as dt
					FROM acompanhamento_hist	
					LEFT JOIN situacaoos_elx ON ac_sitos = COD_SITUACAO_OS
					WHERE ac_OS = '$documento' ORDER BY ac_id DESC";
					$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
					 while ($row = mysqli_fetch_array($resultado)) {
						?>
							<tr class="gradeX">
								<td class="text-center"><?= $row["dt"]; ?></td>
								<td class="text-left" style="min-width: 300px ;"><?= $row["ac_descricao"]; ?></td>
								<td class="text-center"> <span class="label label-table label-<?= $row['cor_sit'] ?>" <?=$_stylecor;?>><?= $row['DESCRICAO'] ?></span></td>
								<td class="text-center"><?= $row['ac_usuarionome']; ?></td>
								<td class="text-center"><?= $row['ac_OS']; ?></td>
								<td class="text-center"></td>
							</tr>
						<?php } 
				}
				
				?>
			</tbody>
		</table>
	</div>
	<?php if($_Totalinativado > 0 ) {  ?>
	<div style="text-align: right";><span style="cursor: pointer;" onclick="_inativarAcomp(3)"> Existe <span class="badge badge-danger"><?=$_Totalinativado;?></span> Reg. Inativado</span></div>
	<?php } ?>
	
<?php
	exit();
}


if ($_acao == 100) {

	//acompanhamento
	$documento = $_parametros["_idos"];

?>

	<input type="hidden" id="osagenda" name="osagenda" value="<?= $documento; ?>">
	<?php
	//if($_acao == 0) {  

	$consultaMov = "SELECT *,DATE_FORMAT(ac_hora,'%d/%m/%Y %H:%i') as dt
	FROM acompanhamento	
	LEFT JOIN situacaoos_elx ON ac_sitos = COD_SITUACAO_OS
	WHERE ac_OS = '$documento' ORDER BY ac_id DESC";
	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	?>
	<div class="card-box">
		<div class="row">
			<div class="col-sm-12">

				<input name="dtagenda" type="hidden" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
				<label>Descrição</label>
				<textarea name="agendadescricao" rows="2" id="agendadescricao" class="form-control input-sm" ></textarea>
			</div>
			<div class="col-sm-1">
				<div style="margin-top: 5px ; text-align:center">
					<button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda"   name="btagenda" onclick="_acompanhamentoincluir()">
						Incluir
					</button>
				</div>
			</div>

		</div>
		<?php  /* if ($roteiro != "") {
		?>
			<div class="row">
				<div class="col-sm-11">
					<input name="dtagenda" type="hidden" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
					<label>Alterar Assessor para </label>
					<?php
					$queryT = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox  FROM usuario  where usuario_tecnico = '1'");
					$resultT = mysqli_query($mysqli, $queryT)  or die(mysqli_error($mysqli));
				
					?>
					<select name="tecnico_troca" id="tecnico_troca" class="form-control ">
						<option value=""> </option>
						<?php
						while ($re = mysqli_fetch_array($resultT)) {

							$descricao = $re["usuario_NOME"];
							$codigo = $re["usuario_CODIGOUSUARIO"];

						?>
									<option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
						<?php						
						
						}

						?>
					</select>
				</div>



			</div>
		<?php
		} */ ?>
	</div>

	</div>

	<div class="card-box" style="height: 300px;  overflow-y: scroll;">
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="font-size: 12px ;">
			<thead>
				<tr>
					<th class="text-center" >Dt Posicionamento</th>
					<th class="text-left">Descrição</th>
					
				
				</tr>
			</thead>
			<tbody>
				<?php while ($row = mysqli_fetch_array($resultado)) {
				?>
					<tr class="gradeX">
						<td class="text-center"><?= $row["dt"]; ?></td>
						<td class="text-left" ><?= $row["ac_descricao"]; ?></td>
						
					
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php
	exit();
}


if ($_acao == 12) {
	//agendamento previsto	?>
	<input type="hidden" id="ctchamado" name="ctchamado" value="<?= $_parametros['chamada']; ?>">
	
	<?php
	//if($_acao == 0) {  

	$consultaAP = "Select Cod_Tecnico_Execucao,DATA_ATEND_PREVISTO,Obs_Atend_Externo from chamada where 
	CODIGO_CHAMADA = '" . $_parametros['chamada'] . "' ";	
	$executaAP = mysqli_query($mysqli, $consultaAP) or die(mysqli_error($mysqli));
	while ($rstAP = mysqli_fetch_array($executaAP)) {
		$Cod_Tecnico_Execucao = $rstAP['Cod_Tecnico_Execucao'];
		$DT = $rstAP['DATA_ATEND_PREVISTO'];
		$obsroteiro = trim($rstAP['Obs_Atend_Externo']);
	}
	?>
	<div id="_listatendimentoprevistoS">
		<div class="card-box">
			<div class="row">
				<div class="col-sm-4">
					<label>Data Atendimento </label>
					<input type="hidden" id="ctat" name="ctat" value="<?=$DT;?>">
					<input type="hidden" id="obsroteiro" name="obsroteiro" value="<?=$obsroteiro;?>">
					<input name="dtaberturaSel" type="date" value="<?= $DT; ?>" id="dtaberturaSel" size="10" onchange="_listOStec()" class="form-control " />
				</div>
				<div class="col-sm-8">
					<label>Assessor</label>
					<?php
					$queryT = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM usuario  where usuario_tecnico = '1'   and usuario_ATIVO = 'Sim' or usuario_CODIGOUSUARIO = '$Cod_Tecnico_Execucao'  ORDER BY usuario_APELIDO ");
					$resultT = mysqli_query($mysqli, $queryT)  or die(mysqli_error($mysqli));
					?>
					<select name="tecnico_troca" id="tecnico_troca" class="form-control" onchange="_listOStec()">
						<option value=""> </option>
						<?php
						while ($re = mysqli_fetch_array($resultT)) {

							$descricao = $re["usuario_APELIDO"];
							$codigo = $re["usuario_CODIGOUSUARIO"];

						?>
							<option value="<?php echo "$codigo"; ?>" <?php if ($Cod_Tecnico_Execucao == $codigo) { ?>selected="selected" <?php } ?>><?php echo "$descricao"; ?></option>
						<?php

						}

						?>
					</select>
				</div>

			</div>
			<div class="row">
				<div class="col-sm-12">

					<input name="dtagenda" type="hidden" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
					<label>Observação Roteiro</label>
					<textarea name="agendadescricao" rows="2" id="agendadescricao" class="form-control input-sm"><?=$obsroteiro;?></textarea>
				</div>

			</div>
			<div class="row">

				<div class="col-sm-1">
					<div style="margin-top: 5px ; text-align:center">
						<button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendamentoSalvar()">
							Salvar
						</button>
					</div>
				</div>
			
			</div>
		</div>
	

	<div class="card-box" style="height: 300px;  overflow-y: scroll;" id="_listatendimentoprevisto">
		<?php
		$sql = "Select CODIGO_CHAMADA,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,Nome_Consumidor,BAIRRO,Obs_Atend_Externo
		    from chamada      
			left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
			where  DATA_ATEND_PREVISTO = '" . $DT . "' 
			and Cod_Tecnico_Execucao = '" .  $Cod_Tecnico_Execucao . "' 
			and Cod_Tecnico_Execucao <> ''  ";

		$ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
		$TotalReg = mysqli_num_rows($ex);
		?>Total Atendimento : <strong><?= $TotalReg; ?></strong>
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="text-center">OS</th>
					<th class="text-left " style="width:200px ;">Cliente</th>
					<th class="text-left" style="width:150px ;">Bairro</th>
					<th class="text-left" style="width:200px ;">Observação</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ($rtoslist = mysqli_fetch_array($ex)) {
					$i++;
				?>
					<tr class="gradeX">
						<td class="text-center"><?= $rtoslist["CODIGO_CHAMADA"]; ?></td>
						<td class="text-left" ><?= $rtoslist["Nome_Consumidor"]; ?></td>
						<td class="text-left" ><?= $rtoslist['BAIRRO']; ?></td>
						<td class="text-left" ><?=$rtoslist['Obs_Atend_Externo']; ?></td>
						
					</tr>
				<?php }

				?>
			</tbody>
		</table>
		</div>
		<div class="row">

				<div class="col-sm-8">
					
				</div>
				
				
				<div class="col-sm-2">
					<div style="margin-top: 5px ; text-align:center">
						<button type="button" class="btn btn-danger  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendamentoCancelar()">
							Anular agendamento
						</button>
					</div>
				</div>
			</div>

	</div>
	<?php
	exit();
}

if ($_acao == 13) {
	$sql = "Select CODIGO_CHAMADA,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,Nome_Consumidor,BAIRRO,Obs_Atend_Externo
		    from chamada      
			left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
			where  DATA_ATEND_PREVISTO = '" . $_parametros['dtaberturaSel'] . "' 
			and Cod_Tecnico_Execucao = '" .  $_parametros['tecnico_troca'] . "' and Cod_Tecnico_Execucao <> ''  ";

	$ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
	$TotalReg = mysqli_num_rows($ex);
	?>Total Atendimento : <strong><?= $TotalReg; ?></strong>
	<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
		<thead>
			<tr>
			<th class="text-center">OS</th>
					<th class="text-left " style="width:200px ;">Cliente</th>
					<th class="text-left" style="width:150px ;">Bairro</th>
					<th class="text-left" style="width:200px ;">Observação</th>
			</tr>
		</thead>
		<tbody>

			<?php

			while ($rtoslist = mysqli_fetch_array($ex)) {
				$i++;
			?>
				<tr class="gradeX">
					<td class="text-center"><?= $rtoslist["CODIGO_CHAMADA"]; ?></td>
					<td class="text-left" ><?= $rtoslist["Nome_Consumidor"]; ?></td>
					<td class="text-center"><?= $rtoslist['BAIRRO']; ?></td>
					<td class="text-left" ><?= $rtoslist['Obs_Atend_Externo']; ?></td>
				</tr>
			<?php }

			?>
		</tbody>
	</table>

<?php
	exit();
}


if ($_acao == 14) {

	//verificar data selecionada
	$data_inicial = date("Y")."-".date("m")."-".date("d");
	$data_final = $_parametros['dtaberturaSel'] ;
	$diferenca = strtotime($data_final) - strtotime($data_inicial);
	$dias = floor($diferenca / (60 * 60 * 24));

	if($dias < 0) {
		$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao,Obs_Atend_Externo,DATA_ATEND_PREVISTO
		FROM chamada	
		WHERE CODIGO_CHAMADA = '".$_parametros['ctchamado']."' Limit 1";
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		while ($row = mysqli_fetch_array($resultado)) {
			$_sit = $row['SituacaoOS_Elx'];
			$_tec = $row['Cod_Tecnico_Execucao'];
			$obsroteiro = trim($row['Obs_Atend_Externo']);	
			$DTPREVISTO = $row['DATA_ATEND_PREVISTO'];	
		}

		if($_parametros['agendadescricao'] != "") {
			$obsroteiro = trim($_parametros['agendadescricao']);
		}

		$consultaMov = "UPDATE chamada SET 
						Obs_Atend_Externo = '".$obsroteiro."'
						WHERE CODIGO_CHAMADA ='".$_parametros['ctchamado']."'  ";
					
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		?>
		<div  style="text-align: center ;">
		<p><i class="md-2x   md-warning text-warning"></i></p>
		
							<h4 ><span >Data agendamento não pode ser inferior  a hoje !!! <strong>  </h4>
							<p>
								<button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" onclick="agendaprevista()">Voltar</button>
							</p>
					<div > 
	<?php

	exit();

	}


	//verificar tecnico selecionado 
	if($_parametros['tecnico_troca'] == ""){
		$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao,Obs_Atend_Externo
		FROM chamada	
		WHERE CODIGO_CHAMADA = '".$_parametros['ctchamado']."' Limit 1";
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		while ($row = mysqli_fetch_array($resultado)) {
			$_sit = $row['SituacaoOS_Elx'];
			$_tec = $row['Cod_Tecnico_Execucao'];
			$obsroteiro = trim($row['Obs_Atend_Externo']);		
		}

		if($_parametros['agendadescricao'] != "") {
			$obsroteiro = trim($_parametros['agendadescricao']);
		}

		$consultaMov = "UPDATE chamada SET 
						Obs_Atend_Externo = '".$obsroteiro."'
						WHERE CODIGO_CHAMADA ='".$_parametros['ctchamado']."'  ";
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		?>
		<div  style="text-align: center ;">
		<p><i class="md-2x   md-warning text-warning"></i></p>
							<h4 ><span >Selecione o Assessor !!! <strong>  </h4>
							<p>
								<button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" onclick="agendaprevista()">Voltar</button>
							</p>
					<div > 
	<?php
	exit();
	}
	$_dtprevisto = explode("-",$_parametros['dtaberturaSel']);
	$_dtprevisto = $_dtprevisto[2]."/".$_dtprevisto["1"]."/".$_dtprevisto["0"];
	$_tecnicotroca = $_parametros['tecnico_troca']; // chamado da 
		$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao,Obs_Atend_Externo,DATA_ATEND_PREVISTO,GARANTIA,
						chamada.CODIGO_CONSUMIDOR,FONE_RESIDENCIAL,FONE_CELULAR,FONE_COMERCIAL
						FROM chamada	
						left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
						WHERE CODIGO_CHAMADA = '".$_parametros['ctchamado']."' Limit 1";
	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	while ($row = mysqli_fetch_array($resultado)) {
		$_sit = $row['SituacaoOS_Elx'];
		$_tec = $row['Cod_Tecnico_Execucao'];
		$obsroteiro = trim($row['Obs_Atend_Externo']);
		$DTPREVISTO = $row['DATA_ATEND_PREVISTO'];	
		$garantia	 = $row['GARANTIA'];	
		$cliente = $row['CODIGO_CONSUMIDOR'];	

		$FONE_RESIDENCIAL = RemoveSpecialChar($row["FONE_RESIDENCIAL"]);
		$FONE_CELULAR = RemoveSpecialChar($row["FONE_CELULAR"]);
		$FONE_COMERCIAL = RemoveSpecialChar($row["FONE_COMERCIAL"]);

		$fonex = $FONE_RESIDENCIAL.$FONE_CELULAR.$FONE_COMERCIAL;

	}

	if($_parametros['agendadescricao'] != "" or $_parametros['agendadescricao'] != "" and $_parametros['agendadescricao'] != "$obsroteiro" ) {
		$obsroteiro = trim($_parametros['agendadescricao']);
	}

	if($_dtprevisto != $_parametros['dtprevistaViewer']){		
		$_trackstatus = ",SIT_TRACKMOB = 0,SIT_TRACKPERIODO = '0'";	
		$up = "UPDATE trackOrdem SET  trackO_ordem = '0',trackO_periodo = 0
               WHERE trackO_chamada = '" .$_parametros['ctchamado'] . "'  and trackO_data = '".$DTPREVISTO."'
			   and  trackO_ordem = '0' ";
		 	   mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));	
	}

	if ( $_tec != "$_tecnicotroca" and  $_tecnicotroca != "") {
		$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM usuario  
		where usuario_CODIGOUSUARIO = '$_tecnicotroca'");
		$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
		while ($resultado = mysqli_fetch_array($result)) {
			$_nomeacessor = $resultado["usuario_APELIDO"];
		}

		$Rdescricao = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - DATA DE ATENDIMENTO ATUALIZADA PARA DIA (<strong>$_dtprevisto</strong>) COM TÉCNICO (<strong>$_nomeacessor</strong>) $obsroteiro";
		$consultaMov = "UPDATE chamada SET Cod_Tecnico_Execucao = '$_tecnicotroca',
						Obs_Atend_Externo = '".$obsroteiro."',
						DATA_ATEND_PREVISTO  = '".$_parametros['dtaberturaSel']."'
						$_trackstatus
						WHERE CODIGO_CHAMADA = '".$_parametros['ctchamado']."'  ";
						$xx =  	$consultaMov ;
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
				(CURRENT_DATE(),'$data','".$_parametros['ctchamado']."','$usuario','$usuariologado','$cliente','$Rdescricao','$_sit' )";
				$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));	
		
	}else{
		$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM usuario  
		where usuario_CODIGOUSUARIO = '$_tecnicotroca'");
		$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
		while ($resultado = mysqli_fetch_array($result)) {
			$_nomeacessor = $resultado["usuario_APELIDO"];
		}

		$consultaMov = "UPDATE chamada SET 
		Obs_Atend_Externo = '".$obsroteiro."',
		DATA_ATEND_PREVISTO  = '".$_parametros['dtaberturaSel']."'
		$_trackstatus
		WHERE CODIGO_CHAMADA ='".$_parametros['ctchamado']."'  ";
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	
		$xx =  	$_parametros['dtaberturaSel']." !=  ".$_parametros['ctat'];
		if($_parametros['dtaberturaSel'] !=  $_parametros['ctat']){
			$a = 1;
		    $Rdescricao = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - DATA DE ATENDIMENTO ATUALIZADA PARA DIA (<strong>$_dtprevisto</strong>)-TÉCNICO (<strong>$_nomeacessor</strong>) $obsroteiro";
			$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
				(CURRENT_DATE(),'$data','".$_parametros['ctchamado']."','$usuario','$usuariologado','$cliente','$Rdescricao','$_sit' )";
				$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));	
		}else{
		
			if($Rdescricao == "" and $_parametros['agendadescricao'] != "" and $_parametros['obsroteiro'] != "$obsroteiro" ) {

				$Rdescricao = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - DATA DE ATENDIMENTO (<strong>$_dtprevisto</strong>) - TÉCNICO (<strong>$_nomeacessor</strong>) $obsroteiro";
				$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
					(CURRENT_DATE(),'$data','".$_parametros['ctchamado']."','$usuario','$usuariologado','$cliente','$Rdescricao','$_sit' )";
					$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));	
			}

		}

		
	

		//	if($obsroteiro !=  $_parametros['obsroteiro'] or $a == 1){	
				//if($obsroteiro !=  $_parametros['obsroteiro']){
				//	$descricao = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ATUALIZADO OBS:$obsroteiro";
			//	}
				
			//}
		
	}


		?>
			<div  style="text-align: center ;">
			<p><i class="md-2x   md-check text-success"></i></p>
			
								<h4 ><span >Atualizado com sucesso !!!  <strong>  </h4>
								<p>
									<button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
								</p>
						<div > 
		<?php

		//verificar prisma mob
			$sql = "Select trackO_ordem 
			from trackOrdem            
			where trackO_data = '" . $_parametros['dtaberturaSel'] . "' 
			and trackO_tecnico = '" . $_parametros['tecnico_troca'] . "'
			and trackO_chamada = '" . $_parametros['ctchamado'] . "' 
			and datahora_trackfim = '0000-00-00 00:00:00'
			and trackO_cancelado = 0";
		//	echo $sql;
			$ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
			$_count = mysqli_num_rows($ex);
			if ($_count == 0) {
				$delete = "DELETE FROM  trackOrdem WHERE
				trackO_chamada = '".$_parametros['ctchamado']."' AND
				trackO_ordem = 0";
				
          		 mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));

				$insert = "INSERT INTO trackOrdem (trackO_data,trackO_chamada,trackO_ordem,trackO_tecnico,trackO_periodo,trackO_garantia,trackO_idcli) 
                VALUES ('" . $_parametros['dtaberturaSel'] . "','" . $_parametros['ctchamado'] . "','0','" . $_parametros['tecnico_troca'] . "','0','$garantia','$cliente')";
          		 mysqli_query($mysqli, $insert) or die(mysqli_error($mysqli));

//echo $insert;
	
				   //$codigocodificado 
				   $query = ("SELECT sigla  from  parametro  ");
					$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
					while ($rst = mysqli_fetch_array($result)) {
						$sigla = $rst["sigla"];       
					}
    
				 
				   $codigocodificado = str_shuffle("$sigla".($_SESSION['CODIGOCLI']+(date('d').date('m'))+$_parametros['ctchamado']));
				   $insert = "INSERT INTO bd_prisma.os (login,os,cliente,data,codigo,tecnico,telefone) 
				   VALUES ('" .$_SESSION['CODIGOCLI'] . "','" . $_parametros['ctchamado'] . "','$cliente','" . $data . "','$codigocodificado','".$_parametros['tecnico_troca']."','$fonex')";
					  mysqli_query($mysqli, $insert) or die(mysqli_error($mysqli));
			}
	exit();
}


if ($_acao == 15) {

	//cancelar agendamento
	
		$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao,Obs_Atend_Externo,DATA_ATEND_PREVISTO,GARANTIA,
						CODIGO_CONSUMIDOR
						FROM chamada	
						WHERE CODIGO_CHAMADA = '".$_parametros['ctchamado']."' Limit 1";
	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	while ($row = mysqli_fetch_array($resultado)) {
		$_sit = $row['SituacaoOS_Elx'];
		$_tec = $row['Cod_Tecnico_Execucao'];
		$DTPREVISTO = $row['DATA_ATEND_PREVISTO'];	
		$cliente = $row['CODIGO_CONSUMIDOR'];	
	}



		$up = "UPDATE trackOrdem SET  trackO_cancelado = '1',trackO_situacaoEncerrado = '10'
               WHERE trackO_chamada = '" .$_parametros['ctchamado'] . "'  and trackO_data = '".$DTPREVISTO."'
			   ";
		
		 	   mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));	

				$delete = "DELETE FROM  trackOrdem WHERE
				trackO_chamada = '".$_parametros['ctchamado']."' AND
				trackO_ordem = 0";				
          		 mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));

				$_trackstatus = ",SIT_TRACKMOB = 0,SIT_TRACKPERIODO = '0'";	

		    $descricao = $descricao . "<strong> Anulado Atendimento</strong>";
			$consultaMov = "UPDATE chamada SET 							
							DATA_ATEND_PREVISTO  = '0000-00-00'
							$_trackstatus
							WHERE CODIGO_CHAMADA ='".$_parametros['ctchamado']."'  ";
			$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		

	$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
	(CURRENT_DATE(),'$data','".$_parametros['ctchamado']."','$usuario','$usuariologado','$cliente','$descricao','$_sit' )";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));	
		?>
			<div  style="text-align: center ;">
			<p><i class="md-2x   md-check text-success"></i></p>
			
								<h4 ><span >Anulado agendamento !!! <strong>  </h4>
								<p>
									<button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
								</p>
						<div > 
		<?php
		
	exit();
}





if ($_acao == 11) {
	//$documento = $_parametros["osagenda"];
	$descricao = str_replace("'",' ',$_parametros['agendadescricaoOS']);
	
	$roteiro = $_parametros['roteiro']; // chamado da tela roteiro
	$_tecnicotroca = $_parametros['tecnico_troca']; // chamado da tela roteiro
	$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao
	FROM chamada	
	WHERE CODIGO_CHAMADA = '$documento' Limit 1";
	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	while ($row = mysqli_fetch_array($resultado)) {
		$_sit = $row['SituacaoOS_Elx'];
		$_tec = $row['Cod_Tecnico_Execucao'];		
	}

	if ($roteiro != "" and $_tec != "$_tecnicotroca" and  $_tecnicotroca != "") {
		$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM usuario  
		where usuario_CODIGOUSUARIO = '$_tecnicotroca'");
		$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
		while ($resultado = mysqli_fetch_array($result)) {
			$_nomeacessor = $resultado["usuario_APELIDO"];
		}
		$descricao = $descricao . "<strong>Assessor Atualizado OS para $_nomeacessor</strong>";
		$consultaMov = "UPDATE chamada SET Cod_Tecnico_Execucao = '$_tecnicotroca' WHERE CODIGO_CHAMADA = '$documento'  ";
		
		$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	}

	if($descricao != ""){
	$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
	(CURRENT_DATE(),'$data','$documento','$usuario','$usuariologado','$cliente','$descricao','$_sit' )";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
}else{
	$_msg = "Informe descrição acompanhamento";
}
	$consultaMov = "SELECT *,DATE_FORMAT(ac_hora,'%d/%m/%Y %H:%i') as dt
	FROM acompanhamento	
	LEFT JOIN situacaoos_elx ON ac_sitos = COD_SITUACAO_OS
	WHERE ac_OS = '$documento' AND ac_inativado = '0' ORDER BY ac_id DESC";

	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));

?>

	<input type="hidden" id="osagenda" name="osagenda" value="<?= $documento; ?>"><?php
																				//if($_acao == 0) {  



																				?>
	
		<?php if($_msg != "") { ?>
			<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
							<?=$_msg;?>
						</div>
		<?php }  ?>
		
	

	<div class="card-box" style="height: 300px;  overflow-y: scroll;">
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>

					<th class="text-center" style="width:200px ;">Dt Posicionamento</th>
					<th class="text-left">Descrição</th>
					<th class="text-left">Situação OS</th>
					<th class="text-center">Usuário</th>
					<th class="text-center">Ref</th>

				</tr>
			</thead>
			<tbody>
				<?php while ($row = mysqli_fetch_array($resultado)) {
					$_stylecor = "";
						if($row["cor_sitcodigo"] != "") {
							$_stylecor = 'style="color:'.$row["cor_sitcodigofonte"].'; background-color: '.$row["cor_sitcodigo"].'"';
						}
				?>
					<tr class="gradeX">
						<td class="text-center"><?= $row["dt"]; ?></td>
						<td class="text-left" style="min-width: 300px ;"><?=nl2br($row["ac_descricao"]); ?></td>
						<td class="text-center"> <span class="label label-table label-<?= $row['cor_sit'] ?>" <?=$_stylecor;?> ><?= $row['DESCRICAO'] ?></span></td>
						<td class="text-center"><?= $row['ac_usuarionome']; ?></td>
						<td class="text-center"><?= $row['ac_OS']; ?></td>

					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php
	exit();
}


if ($_acao == 111) {
	
	$documento = $_parametros["_idos"];
	$cliente  = $_parametros["_idcliente"];
	$descricao  = $_parametros["agendadescricao"];


	$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao
	FROM chamada	
	WHERE CODIGO_CHAMADA = '$documento' Limit 1";
	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	while ($row = mysqli_fetch_array($resultado)) {
		$_sit = $row['SituacaoOS_Elx'];		
		
	}

	if($descricao != ""){
	
	$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
	(CURRENT_DATE(),'$data','$documento','$usuario','$usuariologado','$cliente','$descricao','$_sit' )";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	}else{
		$_msg = "Informe descrição acompanhamento";
	}
	$consultaMov = "SELECT *,DATE_FORMAT(ac_hora,'%d/%m/%Y %H:%i') as dt
	FROM acompanhamento	
	LEFT JOIN situacaoos_elx ON ac_sitos = COD_SITUACAO_OS
	WHERE ac_OS = '$documento'  AND ac_inativado = '0'ORDER BY ac_id DESC";

	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));

?>

	<input type="hidden" id="osagenda" name="osagenda" value="<?= $documento; ?>"><?php
																				//if($_acao == 0) {  



																				?>
	<div class="card-box">
	<?php if($_msg != "") { ?>
			<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
							<?=$_msg;?>
						</div>
		<?php }  ?>
		<div class="row">
			<div class="col-sm-12">
				<input name="dtagenda" type="hidden" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
				<label>Descrição</label>
				<textarea name="agendadescricao" rows="2" id="agendadescricao" class="form-control input-sm"></textarea>
			</div>
			<div class="col-sm-1">
				<div style="margin-top: 5px ; text-align:center">
					<button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_acompanhamentoincluir()">
						Incluir
					</button>
				</div>
			</div>


		</div>
	</div>

	<div class="card-box" style="height: 300px;  overflow-y: scroll;">
		<table id="datatable-responsive"  class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="font-size: 12px ;">
			<thead>
				<tr>

					<th class="text-center" >Dt Posicionamento</th>
					<th class="text-left">Descrição</th>
				

				</tr>
			</thead>
			<tbody>
				<?php while ($row = mysqli_fetch_array($resultado)) {
				?>
					<tr class="gradeX">
						<td class="text-center"><?= $row["dt"]; ?></td>
						<td class="text-left" ><?= $row["ac_descricao"]; ?></td>
					

					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php
	exit();
}

if ($_acao == 2) {   //wats




	$consulta = "Select *,chamada.descricao as descA,Nome_Rua,consumidor.BAIRRO,consumidor.CIDADE as cid, consumidor.UF as estado,chamada.DEFEITO_RECLAMADO as def,
	situacaoos_elx.DESCRICAO  as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB, HORARIO_ATENDIMENTO,
	
	DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,
	
	DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS Data_Vencimento1,
	
	DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS Data_Vencimento2,
	
	DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS Data_Vencimento3,
	
	DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS Data_Vencimento4,
	
	DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS Data_Vencimento5,
	
	DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS Data_Vencimento6,
	
	chamada.CODIGO_TECNICO as TEC from chamada 
	
	left JOIN usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
	
	left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
	
	left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
	
	left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
	
	WHERE CODIGO_CHAMADA = '$documento'";

	$executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

	$TotalReg = mysqli_num_rows($executa);

	while ($rst = mysqli_fetch_array($executa)) {
		$nome = $rst["Nome_Consumidor"];
		$endereco = $rst["Nome_Rua"];
		$nrua = $rst["Num_Rua"];
		$_complemento = $rst["COMPLEMENTO"];
		$_cpfcnpj = $rst["CGC_CPF"];
		$cidade = $rst["cid"];
		$bairro  = $rst["BAIRRO"];
		$uf = $rst["estado"];
		$ddd = $rst["DDD"];
		$email = $rst["EMail"];
		
		$FONE_RESIDENCIAL = RemoveSpecialChar($rst["FONE_RESIDENCIAL"]);
		$FONE_CELULAR = RemoveSpecialChar($rst["FONE_CELULAR"]);
		$FONE_COMERCIAL = RemoveSpecialChar($rst["FONE_COMERCIAL"]);

		
		if($rst["FONE_RESIDENCIAL"] != "") {
			$fone = $fone." ".mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');
		}
		if($rst["FONE_CELULAR"] != "") {
			$fone = $fone." ".mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
		}
		if($rst["FONE_COMERCIAL"] != "") {
			$fone = $fone." ".mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');
		}
	
	

		//$fone = mascara($rst["DDD"].$rst["FONE_RESIDENCIAL"], 'telefone') . " " . mascara($rst["DDD"].$rst["FONE_COMERCIAL"], 'telefone') . " " . mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');
		$nomeRecado = $rst["NOME_RECADO"];
		$tecnico_cliente = $rst["Cod_Tecnico_Execucao"];
		$_nomeproduto = $rst["descA"];
		$_dtatend = $rst["data2"];
		$_nomeatend =  $rst["usuario_APELIDO"];
		$_def = $rst["DEFEITO_RECLAMADO"];

		$_dataref = $rst["DATA_ATEND_PREVISTO"];
	}

		//verificar se existe link
		$queryOS = ("SELECT codigo  from bd_prisma.os 
		WHERE os = '$documento' and login = '".$_SESSION['CODIGOCLI']."' and 
		tecnico = '".$tecnico_cliente."' and   data = '".$_dataref."'");
		$resultOS = mysqli_query($mysqli, $queryOS)  or die(mysqli_error($mysqli));
	
		while ($rstOS = mysqli_fetch_array($resultOS)) {
			$codigocodificado = $rstOS["codigo"];  
		}
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
							<div class="col-sm-12">
								<a href="https://wa.me/55<?=$ddd.$FONE_RESIDENCIAL; ?> " target="_blank"><?=mascara($ddd.$FONE_RESIDENCIAL, 'telefone') ; ?> </a>

							</div>
						</div>
					<?php } ?>

				</div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="margin-left:20px">
					<?php
					if ($FONE_CELULAR != "") {  ?>
						<div class="row">
							<div class="col-sm-12">
								<a href="https://wa.me/55<?=$ddd.$FONE_CELULAR; ?> " target="_blank"><?=mascara($ddd.$FONE_CELULAR, 'telefone') ; ?> </a>

							</div>
						</div>
					<?php } ?>


				</div>
			</div>
			<?php
			if ($FONE_COMERCIAL != "") {  ?>
				<div class="row">
					<div class="col-sm-12" style="margin-left:20px">
						<a href="https://wa.me/55<?=$ddd.$FONE_COMERCIAL; ?> " target="_blank"><?=mascara($ddd.$FONE_COMERCIAL, 'telefone') ; ?> </a>

					</div>
				</div>
			<?php } ?>

			<div class="row">
				<div class="col-sm-10">
					<button type="button" style="margin:5px ;" class="btn btn-default btn-whatsapp waves-effect waves-light btn-block" onclick=" _copy()"> Copiar
				</div>
			</div>
			<div class="row">
				<div class="col-sm-10">
					<button type="button" style="margin:5px ;" class="btn btn-success btn-whatsapp waves-effect waves-light btn-block" onclick=" _salvarwats2()"> Enviar Whatsapp
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
					
				</div>
			</div>
		</div>
		
		<div class="col-sm-9">
			<textarea type="hidden" name="textowats" id="textowats" style="height: 454px; width: 638px;"><?php
																											echo ("
*$_nome_empresa* 
*Contato: $_telefone_empresa*

Olá! Gostaríamos de lhe dar as boas-vindas em nosso canal digital do WhatsApp Business, através desse canal será possível tirar dúvidas sobre o atendimento, pedido de peças em andamento, orçamentos, status da ordem de serviço, etc...

Peço a gentileza de *CONFIRMAR os dados de sua ordem de serviço, importante para que o técnico tenha êxito na visita agendada:*

*ORDEM DE SERVIÇO:* $documento
*NOME COMPLETO:* $nome
*ENDEREÇO:* $endereco $nrua $_complemento, $bairro $cidade-$uf
*TELEFONE(S):* $fone
*CPF/CNPJ:* $_cpfcnpj
*E-MAIL:* $email
*MODELO DO PRODUTO:* $_nomeproduto 
*DESCRIÇÃO DO ATENDIMENTO:* $_def

*DATA DO ATENDIMENTO:* $_dtatend 

Aguardamos a sua *CONFIRMAÇÃO* e permanecemos a disposição.

Atenciosamente,
$_nomeatend");

?>
</textarea>

		</div>

	</div>	
<?php


	exit();
}


if ($_acao == 3) {   //SOS



	$consulta = "Select *,chamada.descricao as descA,Nome_Rua,consumidor.BAIRRO,consumidor.CIDADE as cid, consumidor.UF as estado,chamada.DEFEITO_RECLAMADO as def,
	situacaoos_elx.DESCRICAO  as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB, HORARIO_ATENDIMENTO,
	
	DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,
	
	DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS Data_Vencimento1,
	
	DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS Data_Vencimento2,
	
	DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS Data_Vencimento3,
	
	DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS Data_Vencimento4,
	
	DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS Data_Vencimento5,
	
	DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS Data_Vencimento6 from chamada 
	
	left JOIN usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
	
	left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
	
	left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
	
	left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
	
	WHERE CODIGO_CHAMADA = '$documento'";


	$executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

	$TotalReg = mysqli_num_rows($executa);

	while ($rst = mysqli_fetch_array($executa)) {
		$nome = $rst["Nome_Consumidor"];
		$endereco = $rst["Nome_Rua"];
		$nrua = $rst["Num_Rua"];
		$_complemento = $rst["COMPLEMENTO"];
		$_cpfcnpj = $rst["CGC_CPF"];
		$cidade = $rst["CIDADE"];
		$uf = $rst["UF"];
		$ddd = $rst["DDD"];
		$email = $rst["EMail"];
		$fone = $rst["FONE_RESIDENCIAL "] . "/" . $rst["FONE_COMERCIALR"] . "/" . $rst["FONE_CELULAR"];

		$FONE_RESIDENCIAL = $rst["FONE_RESIDENCIAL"];
		$FONE_CELULAR = $rst["FONE_CELULAR"];
		$FONE_COMERCIAL = $rst["FONE_COMERCIAL"];
		$nomeRecado = $rst["NOME_RECADO"];
		$tecnico_cliente = $rst["CODIGO_TECNICO"];
		$_nomeproduto = $rst["descA"];
		$_dtatend = $rst["data2"];
		$_nomeatend =  $rst["usuario_APELIDO"];
	}
?>
	<div class="row">
		<div class="col-sm-3">
			<div class="row">
				<div class="col-sm-12">
					<label>sos</label>
				</div>
			</div>

		<?php


		exit();
	}


	if ($_acao == 1) {



		//incluir
		if ($situacao == 2) {
			$dataEncerramentoA = ",Agenda_Encerrado";
			$dataEncerramentoB = ",'$data2'";
		}

		$referencia = 2;

		$tipoagenda = $_parametros['IDagenda'];
		if($tipoagenda == ""){
			$tipoagenda  = 1;
		}
		//$prioridade = 1;
		$consulta = "insert into agenda (Agenda_Documento,Agenda_Cadastro,Agenda_DataAgenda,Agenda_Usuario,
	Agenda_CodUsuario,Prioridade,Agenda_Cliente,Agenda_NomeCliente,
	Agenda_Situacao,Agenda_Referencia,Agenda_descricao,Agenda_Telefone,Agenda_Contato,sit_idtabagenda $dataEncerramentoA
	) values (
	'$documento',CURRENT_DATE(),'$data_Agenda','$tecnico','$usuario','$prioridade',
	'$cliente','$nome','$situacao','$referencia','$descricao','$telefone','$contato','$tipoagenda' $dataEncerramentoB
	 )";


		$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));

	
	$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao
	FROM chamada	
	WHERE CODIGO_CHAMADA = '$documento' Limit 1";
	$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
	while ($row = mysqli_fetch_array($resultado)) {
		$_sit = $row['SituacaoOS_Elx'];
		$_tec = $row['Cod_Tecnico_Execucao'];
	}

		$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
		(CURRENT_DATE(),'$data','".$documento."','$usuario','$usuariologado','$cliente','<strong>*Agenda*</strong> $descricao','$_sit' )";

		$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	}

	//EDITAR AGENDA
	if ($_acao == 5) {
		//if($_acao == 0) {  
			if($_idref != "") {
					$consultaMov = "SELECT sit_idtabagenda,ag_nome,Agenda_descricao,Agenda_Documento,Agenda_Situacao,agenda_solucao,
									Nome_Consumidor,EMail,Nome_Rua,Num_Rua,BAIRRO,CIDADE,UF,FONE_RESIDENCIAL,FONE_COMERCIAL,NOME_RECADO,Agenda_Cliente
									FROM agenda	
									left join agendatab ON  sit_idtabagenda = ag_id
									left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR = Agenda_Cliente
									WHERE   Agenda_ID = '" . $_idref . "'";

					$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
					while ($row = mysqli_fetch_array($resultado)) {
						$documento = $row['Agenda_Documento'];
						$desc = $row['Agenda_descricao'];
						$sit  = $row['Agenda_Situacao'];
						$solucao  = $row['agenda_solucao'];
						$idagenda  = $row['sit_idtabagenda'];
						$agendaNome = $row['ag_nome'];
						$Nome_Consumidor = $row['Nome_Consumidor'];
						$EMail = $row['EMail'];
						$Nome_Rua = $row['Nome_Rua'];
						$Num_Rua = $row['Num_Rua'];
						$BAIRRO = $row['BAIRRO'];
						$CIDADE = $row['CIDADE'];
						$estado = $row['UF'];
						$FONE_RESIDENCIAL = $row['FONE_RESIDENCIAL'];
						$FONE_COMERCIAL = $row['FONE_COMERCIAL'];
						$NOME_RECADO = $row['NOME_RECADO'];
						$idcliente = $row['Agenda_Cliente'];
					}
		}else{
			$idagenda = $_parametros["_agendafiltro"];
		}

		?>
			<input type="hidden" id="osagenda" name="osagenda" value="<?= $documento; ?>">
			<input type="hidden" id="agendaref" name="agendaref" value="<?= $_idref; ?>">
			<input type="hidden" id="IDagenda" name="IDagenda" value="<?= $idagenda; ?>">
			<input type="hidden" id="IDcliente" name="IDcliente" value="<?= $idcliente; ?>">

			<div class="modal-content ">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title"><?= $agendaNome; ?> - OSº <strong><?= $documento; ?></strong></h4>
				</div>
				<div class="modal-body">
					<div class="col-lg12">
						<ul class="nav nav-pills m-b-10">
							<li class="active">
								<a href="#home" data-toggle="tab" aria-expanded="false">
									<span class="visible-xs"><i class="fa fa-home"></i></span>
									<span class="hidden-xs">Agendamento</span>
								</a>
							</li>
							<li class="">
								<a href="#dados" data-toggle="tab" aria-expanded="false">
									<span class="visible-xs"><i class="fa fa-home"></i></span>
									<span class="hidden-xs">Consumidor/Equipamento</span>
								</a>
							</li>
							<li class="">
								<a href="#messages" data-toggle="tab" aria-expanded="false">
									<span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
									<span class="hidden-xs">Histórico</span>
								</a>
							</li>

						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="home">
								<div class="card-box" id="result_agendamento">
								
									<div class="row">
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label>Dt Posicionamento</label>
													<input name="dtagenda" type="date" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label> Status</label>
													<select name="sitagenda" id="sitagenda" class="form-control input-sm">

														<?php
														$consulta = $pdo->query("SELECT sit_agendaID,sit_agendaDescricao
																									FROM " . $_SESSION['BASE'] . ".situacao_agenda  where sit_idagenda = '$idagenda' and sit_visualiza = '1'
																									order by sit_agendaDescricao");
														$result = $consulta->fetchAll();
														foreach ($result as $row) {
														?><option value="<?= $row["sit_agendaID"]; ?>"><?= ($row["sit_agendaDescricao"]); ?></option><?php
																																											}
																																															?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label> Prioridade</label>
													<select name="prioridade" id="prioridade" class="form-control input-sm">
														<option value="1">Normal</option>
														<option value="2">Média</option>
														<option value="3">Alta</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-1">
										</div>
										<div class="col-sm-8">
											<div class="row">
												<label>Descrição</label>
												<textarea name="agendadescricao" rows="3" id="agendadescricao" class="form-control input-sm"></textarea>
											</div>
											<div class="row" style="margin-top: 5px ; text-align:right">
												<button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendaincluir()">
													Novo Agendamento
												</button>
											</div>
										</div>

									</div>
									<span id="_retornoinclusao">
									</span>
									<hr>
					<?php if($_idref != "") { ?>
									<div class="row">
										<div class="col-sm-11">
											<div class="row">
												
												<div class="col-sm-11"><label>Assunto:</label><?= $desc; ?></div>
											</div>
											<div class="row">
												
												<div class="col-sm-11">
												<label>Anotação: </label> 
													<?php
													if ($sit == 2) { ?>
														<?= $solucao; ?>
														<input type="hidden" name="agendasolucao" id="agendasolucao" class="form-control input-sm">

													<?php } else { ?>
														<input type="text" name="agendasolucao" id="agendasolucao" class="form-control input-sm">
													<?php }
													?>
												</div>

											</div>
											<?php
											if ($sit == 2 or $sit == 3) {
												//finalizadox
												if($sit == 2){
													$_msg = "Encerrado";
													$cor = "success";
												}
										
												if($sit == 3){
													$_msg = "Excluído";
													$cor = "danger";
												}
												?>
												<div class="row" style="margin-top: 5px ; margin-left:10px ; text-align:center" id="val_agendamento">
													<div class="alert alert-<?=$cor;?> alert-dismissable " style="margin-top: 5px;">
														<?=$_msg;?> !!!
													</div>
								
												</div>
											<?php
											
											} else {  ?>
											<div class="row" id="val_agendamento">
												
												<div class="col-sm-2">
													<div style="margin-top: 5px  ; text-align:center" >
														<button type="button" class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendafim(2)">
															Encerrar
														</button>													
													</div>	
												</div>
												<div class="col-sm-2">
													<div style="margin-top: 5px  ; text-align:center" >
													<button  type="button" class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendafim(4)">
														Pendente
													</button>
													</div>
												</div>
													<div class="col-sm-8">
													<div style="margin-top: 5px  ; text-align:center" >
														<button  type="button" class="btn btn-white waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendafim(3)">
															Excluir
														</button>
													</div>
													</div>
													
												</div>
											<?php } ?>
										</div>
									</div>
									<?php  } ?>
								</div>
								
							</div> <!-- home -->

							<div class="tab-pane" id="dados">
								<div class="card-box">
									<div class="row">
										<table width="100%" border="0">
											<tr>
												<td width="11%"><strong>Nome:</strong></td>
												<td width="58%"><span>
														<?= $Nome_Consumidor; ?>
														<span>
												
														</span></span></td>
												<td colspan="2"><strong>Email:</strong><span> <?= $EMail; ?>
													</span></td>
											</tr>
											<tr>
												<td height="20"><strong>Endere&ccedil;o:</strong></td>
												<td colspan="2"><span> <?= $Nome_Rua; ?> &nbsp;<?= $Num_Rua; ?></span> Bairro:<span> <?= $BAIRRO; ?></span> Cidade:
													<span><?= $cidade; ?></span>
													<strong>UF:</strong>
													<span> <?= $estado; ?></span>
												</td>
												<td width="5%" rowspan="2"></td>
											</tr>
											<tr>
												<td><strong>Telefone:</strong></td>
												<td colspan="2"><span><?= mascara($ddd.$rst["FONE_RESIDENCIAL"], 'telefone'); ?>
														/
														<?= mascara($ddd.$FONE_CELULAR, 'telefone'); ?>
														/
														<?= mascara($ddd.$FONE_COMERCIAL, 'telefone'); ?>
													</span>&nbsp;&nbsp; | &nbsp;&nbsp;<strong> Contato:</strong><span> <?= $NOME_RECADO; ?>
													</span></td>
											</tr>

										</table>
									</div>
								</div>
								<?php 
										$consulta = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,Revendedor,Data_Nota,Nota_Fiscal,VOLTAGEM,cnpj,PNC
										from " . $_SESSION['BASE'] . ".chamada 	
										left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
										where chamada.CODIGO_CONSUMIDOR = '" . $idcliente. "' 
										group by CODIGO_FABRICANTE,descricao,marca, serie";
										$executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));
										while ($rst = mysqli_fetch_array($executa)) {
												$_produto = $rst['descricao'];
												$_modelo = $rst['Modelo'];
												$_marca = $rst['marca'];
												$_serie = $rst['serie'];

										if($_produto != ""){	
											//$equi = $rst['descricao'].";".$rst['marca'].";".$rst['Modelo'].";".$rst['serie'].";$usuario;".$rst['Nota_Fiscal'].";".$rst['Data_Nota'].";".$rst['VOLTAGEM'].";".$rst['Revendedor'].";".$rst['cnpj'];
											$equi = $rst['descricao'].";".$rst['marca'].";".$rst['Modelo'].";".$rst['serie'].";;".$rst['Nota_Fiscal'].";".$rst['Data_Nota'].";".$rst['VOLTAGEM'].";".$rst['Revendedor'].";".$rst['cnpj'].";".$rst['PNC'];
													?>
												<div>
													<div class="card-box m-b-10" style="padding:0px">
														<div class="table-box opport-box">
															<div class="table-detail checkbx-detail" style="margin-left:20px ;">
																<div style="margin-left:10px ;">
																	<button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_newOSAcaoSel('<?=$equi;?>','<?=$idcliente;?>')"> <i class="fa  fa-plus-square"></i> </button>
																</div>
															</div>														
															<div class="table-detail">
																<div class="member-info"  style="margin-left:10px ;">
																	<h4 class="m-t-0 text-custom"><b><?=$_produto;?> </b></h4>																	
																	<p class="text-dark m-b-5"><b>Modelo: </b> <span class="text-muted"><?=$_modelo;?></span></p>
																	<p class="text-dark m-b-5"><b>Marca: </b> <span class="text-muted"><?=$_marca;?></span>Série:  <span class="text-muted"><?=$_serie; ?></span></p>																	
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php
												}
											}

										?>
							</div>

							<div class="tab-pane" id="messages">
								<span id="resultAcompanhamento" name="resultAcompanhamento">


									<?php
									if ($idagenda == "") {
										$idagenda = '1';
									};

									$consultaMov = "SELECT Agenda_ID, Agenda_Documento, Agenda_Cadastro, Agenda_DataAgenda, Agenda_Encerrado, 
													Agenda_Usuario, Agenda_CodUsuario, Prioridade, Agenda_Situacao,
													Agenda_descricao, agenda_usuarioEncerramento, usuario_LOGIN ,Agenda_Referencia,
													sit_cor,sit_agendaDescricao ,agenda_solucao
													FROM agenda	INNER JOIN situacao_agenda ON Agenda_Situacao = sit_agendaID
													
													LEFT JOIN usuario ON Agenda_CodUsuario = usuario_CODIGOUSUARIO 
													WHERE   Agenda_Documento = '$documento' and sit_idtabagenda = '$idagenda'
													ORDER BY Agenda_DataAgenda DESC";

									$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
									?>
									<div class=" table-responsive">
										<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0">
											<thead>
												<tr>
													<th class="text-left">Assunto</th>
													<th class="text-left">Solução</th>
													<th class="text-center">Status</th>
													<th class="text-center">Prioridade</th>
													<th class="text-center">Dt Posicionamento</th>
													<th class="text-center">Refer.</th>
													<th class="text-center">Dt Cadastro</th>
													<th class="text-center">Usuário</th>

												</tr>
											</thead>
											<tbody>
												<?php while ($row = mysqli_fetch_array($resultado)) {
													if ($row['Prioridade'] == 1) {
														$style = 'info';
														$descricao = 'Normal';
													} else if ($row['Prioridade'] == 2) {
														$style = 'warning';
														$descricao = 'Média';
													} else {
														$style = 'danger';
														$descricao = 'Alta';
													}

												?>

													<tr>
														<td class="text-left">
															<div style="width: 200px ;"><?= $row["Agenda_descricao"]; ?></div>
														</td>
														<td class="text-left">
															<div style="width: 400px ;"><?= $row["agenda_solucao"]; ?></div>
														</td>
														<td class="text-center">
															<span class="label label-table label-<?= $row['sit_cor'] ?>"><?= $row['sit_agendaDescricao'] ?></span>
														</td>
														<td class="text-center">
															<span class="label label-table label-<?= $style ?>"><?= $descricao ?></span>
														</td>
														<td class="text-center"><?= date('d/m/Y',  strtotime($row['Agenda_DataAgenda'])) ?></td>
														<td class="text-center"><?= $row['Agenda_Documento']; ?></td>
														<td class="text-center"><?= date('d/m/Y',  strtotime($row['Agenda_Cadastro'])) ?></td>
														<td class="text-center"><?= $row['usuario_LOGIN']; ?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</span>
							</div>

						</div> <!-- tab-content -->
					</div>


				</div> <!-- modal-content -->

				<?php
				exit();
			}


			//EDITAR FINALIZAR AGENDA
			if ($_acao == 6) {
/*
				if ($_parametros['agendasolucao'] == "") { ?>
					<div class="row" style="margin-top: 5px ; text-align:center" id="val_agendamento">
						<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
							Informe a solução
						</div>
						<button class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendafim()">
							Finalizar
						</button>
					</div>
				<?php
				}
				exit();
				*/
			}

			if ($_acao == 7) {
				
				$up = "UPDATE agenda SET 
				Agenda_Encerrado  = CURRENT_DATE(),
				agenda_usuarioEncerramento  = '" . $usuario . "',
				agenda_solucao  = '" . $_parametros['agendasolucao'] . "',
				Agenda_Situacao = '" . $_parametros['_refstatus'] . "'
				WHERE  Agenda_ID = '" . $_parametros['agendaref'] . "'";

				$executa = mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

				if($_parametros['_refstatus'] == 2){
					$_msg = "Encerrado";
					$cor = "success";
				}
				if($_parametros['_refstatus'] == 4){
					$_msg = "Pendente";
					$cor = "warning";
				}
				if($_parametros['_refstatus'] == 3){
					$_msg = "Excluído";
					$cor = "danger";
				}
				
				exit();
			}

			if ($_acao == 88) {
				
				$up = "UPDATE agenda SET 
				Agenda_Encerrado  = CURRENT_DATE(),
				agenda_usuarioEncerramento  = '" . $usuario . "',
				agenda_solucao  = '" . $_parametros['agendasolucao'] . "',
				Agenda_Situacao = '2'
				WHERE  Agenda_ID = '" . $_parametros['agendaref'] . "'";
				$executa = mysqli_query($mysqli, $up) or die(mysqli_error($mysqli)); //atualiza para encerrado  e abre novo registro

				$documento = $_parametros["osagenda"];
				$idagenda = $_parametros["IDagenda"];
				$IDcliente = $_parametros['IDcliente'];
				//incluir
				if ($situacao == 2) {
					$dataEncerramentoA = ",Agenda_Encerrado";
					$dataEncerramentoB = ",'$data2'";
				}

				$referencia = 2;
				//$prioridade = 1;
				$consulta = "insert into agenda (Agenda_Documento,Agenda_Cadastro,Agenda_DataAgenda,Agenda_Usuario,
				Agenda_CodUsuario,Prioridade,Agenda_Cliente,Agenda_NomeCliente,
				Agenda_Situacao,Agenda_Referencia,Agenda_descricao,Agenda_Telefone,Agenda_Contato,sit_idtabagenda $dataEncerramentoA
				) values (
				'$documento',CURRENT_DATE(),'$data_Agenda','$tecnico','$usuario','$prioridade',
				'$IDcliente','$nome','$situacao','$referencia','$descricao','$telefone','$contato','$idagenda' $dataEncerramentoB
				)";
				$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));


				$consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao
					FROM chamada	
					WHERE CODIGO_CHAMADA = '$documento' Limit 1";
					$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
					while ($row = mysqli_fetch_array($resultado)) {
						$_sit = $row['SituacaoOS_Elx'];		
						$consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos,ac_indagenda) values
						(CURRENT_DATE(),'$data','$documento','$usuario','$usuariologado','$IDcliente','<strong>".'<span class="badge badge-default m-l-0">'."AGENDA</span></strong> - $descricao','$_sit','1' )";
						$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
								
					}
	
	
				
							
				exit();
			}

		


			if ($_acao == 8) {

				$documento = $_parametros["osagenda"];
				$idagenda = $_parametros["IDagenda"];
				$IDcliente = $_parametros['IDcliente'];
				//incluir
				if ($situacao == 2) {
					$dataEncerramentoA = ",Agenda_Encerrado";
					$dataEncerramentoB = ",'$data2'";
				}

				$referencia = 2;
				//$prioridade = 1;
				$consulta = "insert into agenda (Agenda_Documento,Agenda_Cadastro,Agenda_DataAgenda,Agenda_Usuario,
					Agenda_CodUsuario,Prioridade,Agenda_Cliente,Agenda_NomeCliente,
					Agenda_Situacao,Agenda_Referencia,Agenda_descricao,Agenda_Telefone,Agenda_Contato,sit_idtabagenda $dataEncerramentoA
					) values (
					'$documento',CURRENT_DATE(),'$data_Agenda','$tecnico','$usuario','$prioridade',
					'$IDcliente','$nome','$situacao','$referencia','$descricao','$telefone','$contato','$idagenda' $dataEncerramentoB
					)";

				$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));

		


			?>

				<span id="resultAcompanhamento" name="resultAcompanhamento">


					<?php
					if ($idagenda == "") {
						$idagenda = '1';
					};

					$consultaMov = "SELECT Agenda_ID, Agenda_Documento, Agenda_Cadastro, Agenda_DataAgenda, Agenda_Encerrado, 
													Agenda_Usuario, Agenda_CodUsuario, Prioridade, Agenda_Situacao,
													Agenda_descricao, agenda_usuarioEncerramento, usuario_LOGIN ,Agenda_Referencia,
													sit_cor,sit_agendaDescricao ,agenda_solucao
													FROM agenda	INNER JOIN situacao_agenda ON Agenda_Situacao = sit_agendaID
													
													LEFT JOIN usuario ON Agenda_CodUsuario = usuario_CODIGOUSUARIO 
													WHERE   Agenda_Documento = '$documento' and sit_idtabagenda = '$idagenda'
													ORDER BY Agenda_DataAgenda DESC";

					$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
					?>
					<div class=" table-responsive">
						<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0">
							<thead>
								<tr>
									<th class="text-left">Assunto</th>
									<th class="text-left">Solução</th>
									<th class="text-center">Status</th>
									<th class="text-center">Prioridade</th>
									<th class="text-center">Dt Posicionamento</th>
									<th class="text-center">Refer.</th>
									<th class="text-center">Dt Cadastro</th>
									<th class="text-center">Usuário</th>

								</tr>
							</thead>
							<tbody>
								<?php while ($row = mysqli_fetch_array($resultado)) {
									if ($row['Prioridade'] == 1) {
										$style = 'info';
										$descricao = 'Normal';
									} else if ($row['Prioridade'] == 2) {
										$style = 'warning';
										$descricao = 'Média';
									} else {
										$style = 'danger';
										$descricao = 'Alta';
									}

								?>

									<tr>
										<td class="text-left">
											<div style="width: 200px ;"><?= $row["Agenda_descricao"]; ?></div>
										</td>
										<td class="text-left">
											<div style="width: 400px ;"><?= $row["agenda_solucao"]; ?></div>
										</td>
										<td class="text-center">
											<span class="label label-table label-<?= $row['sit_cor'] ?>"><?= $row['sit_agendaDescricao'] ?></span>
										</td>
										<td class="text-center">
											<span class="label label-table label-<?= $style ?>"><?= $descricao ?></span>
										</td>
										<td class="text-center"><?= date('d/m/Y',  strtotime($row['Agenda_DataAgenda'])) ?></td>
										<td class="text-center"><?= $row['Agenda_Documento']; ?></td>
										<td class="text-center"><?= date('d/m/Y',  strtotime($row['Agenda_Cadastro'])) ?></td>
										<td class="text-center"><?= $row['usuario_LOGIN']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</span>

			<?php
				exit();
			}

			if ($_acao == 16) { //visualização OS prismaMob
		
					$consultaMov = "Select trackO_id, DATE_FORMAT(trackO_data,'%d/%m/%Y') as data, DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
					DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
					DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
					DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim,
					DATE_FORMAT(DATA_CHAMADA,'%d/%m/%Y')  as dataabertura,					
					sitmob_cortable,trackO_chamada,usuario_APELIDO,sitmob_descricao,trackO_periodo,
					sitmob_cortfont,trackO_ordem
					from trackOrdem        
					left join situacao_trackmob ON sitmob_id = trackO_situacaoEncerrado      
					LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico 
					 INNER JOIN chamada ON CODIGO_CHAMADA = trackO_chamada  
					where trackO_idcli = '".trim($_parametros['codigo'])."' 				       
					order by trackO_id DESC";

					$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
					
					?>  <div class="row">
                    <div class="col-lg-12">
                        <div class="panel-group" id="accordion-test-2">
							<?php 
								foreach ($resultado as $row) {
									$ci++;
									$DADOS = "";
									$PECAS = "";
									$SERVICOS = "";
														$_cor = $row['sitmob_cortable'];
														$_corfonte =$row['sitmob_cortfont'];
														$g_sigla =  $row['g_sigla'];
														$g_cor = $row['g_cor']; 
														$_regordem= $row['trackO_ordem']; 
														$_datahora_trackini = $row['horaini'];
														$_datahora_trackfim = $row['horafim'];
														$_hora_trackmob = $row['dif'];

														if ( $row['trackO_periodo'] == 2) {
															$periodo = "MANHÃ";
														
														} else {
															$periodo = "TARDE";
														
														}
														$periodo = "(".$_regordem.")".$periodo;								

												$tempoatendimento = "$_datahora_trackini as $_datahora_trackfim, $_hora_trackmob de duração";

												$consultaH = "Select trackh_dados,trackh_pecas,trackh_servicos
												from trackHist 
												where trackh_idmob = '".trim($row['trackO_id'])."' 				       
												";							
												$reT = mysqli_query($mysqli, $consultaH) or die(mysqli_error($mysqli));
												foreach ($reT as $rowH) {
													$DADOS =  nl2br($rowH['trackh_dados']);
													$PECAS =  nl2br($rowH['trackh_pecas']);
													$SERVICOS =  nl2br($rowH['trackh_servicos']);
												}
												?>
<div class="panel panel-default" style="border-radius: 6px; overflow: hidden; margin-bottom: 8px;">
  <div class="panel-heading" style="background-color: #f7f7f7; padding: 10px 15px;">
    <h4 class="panel-title" style="margin: 0; font-size: 15px; font-weight: 500;">
      <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?= $ci; ?>" 
         aria-expanded="false" class="collapsed d-flex align-items-center" 
         style="text-decoration: none; color: #000000ff;">
         
        <!-- Badge principal -->
       <a style="cursor: pointer; text-decoration: none;" 
   onclick="_0000101('<?= $row['trackO_chamada']; ?>')">
  <span class="badge badge-success mr-2" 
        style="font-size: 13px; min-width: 35px; text-align: center;">
    <?= $row["trackO_chamada"]; ?>
  </span>
</a>

        <!-- Status -->
        <span class="label mr-2" 
              style="color:<?= $_corfonte; ?>; background-color:<?= $_cor; ?>; 
                     font-size: 12px; padding: 4px 8px; border-radius: 4px;  min-width: 140px;
             display: inline-block;">
          <?= $row['sitmob_descricao']; ?>
        </span>
| &nbsp;
        <!-- Informações -->
        <span class="text-inverse" style="font-size: 14px;">
          <strong>Dt Abertura OS:</strong> <?= $row["dataabertura"]; ?> &nbsp; | &nbsp;
          <strong>Dt Atend:</strong> <?= $row["data"]; ?> &nbsp; | &nbsp;
          <strong>Técnico:</strong> <?= $row["usuario_APELIDO"]; ?>
        </span>

       
      </a>
    </h4>
  </div>

  <div id="collapse<?= $ci; ?>" class="panel-collapse collapse">
    <div class="panel-body" style="background-color: #fafafa; border-top: 1px solid #eee; padding: 12px 15px;">
      <p style="margin-bottom: 8px; font-size: 13px; color: #555;">
        <span><?= $periodo ?> <?= $tempoatendimento; ?></span>
      </p>

      <?= $DADOS; ?>
      <?= $PECAS; ?>
      <?= $SERVICOS; ?>
    </div>
  </div>
</div> <?php	} 	?>
                            
                        </div>
                    </div>
				
				<?php

				exit();
			}

			if ($_acao == 17) { //RESUMO AGENDA
				try { ?>
       
					<?php   
					$agenda = $_parametros['_agendafiltro']; 
				
					 $consultaAgenda = $pdo->query("Select count(sit_idtabagenda) as total, ag_nome 
					 from ".$_SESSION['BASE'].".agenda
					 inner join  ".$_SESSION['BASE'].".agendatab ON ag_id = sit_idtabagenda				 
					  GROUP BY ag_nome");
					  
					$retornoAgenda = $consultaAgenda->fetchAll();  
				   
						 
				   ?>
					<table  class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
					<thead>
						<tr>
							<th class="text-center">Agendas</th>
							<th class="text-center">Total Registros</th>
					   </tr>
					   </thead>
					<tbody>
					   <?php
							  foreach ($retornoAgenda as $row) {
								   $t = $t + $row["total"];
					
						 ?>
					   
						<tr>
						 <td  class="text-center"><?=$row["ag_nome"];?> </td>
						 <td  class="text-center" > <?=$row["total"];?>  </td>
					   </tr>
					   
					   <?php
								   
				   }
				   
				   ?>
				   
				  
				   </tbody>
					 </table>
					 <div class="text-right"> Qtde Total: <strong><?=$t;?> </strong> </div>
					
					 <?php   
									
					 $consulta = $pdo->query("Select count(Agenda_Situacao) as reg, sit_agendaDescricao 
					 from ".$_SESSION['BASE'].".agenda
					 inner join  ".$_SESSION['BASE'].".situacao_agenda ON sit_agendaID = Agenda_Situacao				 
					 GROUP BY sit_agendaDescricao");
					  
					$retorno = $consulta->fetchAll();  
				   
						 
				   ?>
					<table  class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
					<thead>
						<tr>
							<th class="text-center">Status</th>
							<th class="text-center">Total Registros</th>
					   </tr>
					   </thead>
					<tbody>
					   <?php
							  foreach ($retorno as $row) {							
						 ?>					   
						<tr>
						 <td  class="text-center"><?=$row["sit_agendaDescricao"];?> </td>
						 <td  class="text-center" > <?=$row["reg"];?>  </td>
					   </tr>					   
					   <?php
								   
				   }
				   
				   ?>
				   
				  
				   </tbody>
					 </table>
					 
					
				
					
				   <?php 
				} catch (PDOException $e) {
					?>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body" id="imagem-carregando">
								<h2><?="Erro: " . $e->getMessage()?></h2>
							</div>
						</div>
					</div>
					<?php
				}
				exit();

			}



			$idagenda = 1;

			$consultaMov = "SELECT sit_idtabagenda,ag_nome,Agenda_descricao,Agenda_Documento,Agenda_Situacao,agenda_solucao,
	Agenda_Cliente
	FROM agenda	
	left join agendatab ON  sit_idtabagenda = ag_id	
	WHERE   Agenda_ID = '" . $_idref . "'";

			$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
			while ($row = mysqli_fetch_array($resultado)) {
				$documento = $row['Agenda_Documento'];
				$desc = $row['Agenda_descricao'];
				$sit  = $row['Agenda_Situacao'];
				$solucao  = $row['agenda_solucao'];
				$idagenda  = $row['sit_idtabagenda'];
				$agendaNome = $row['ag_nome'];
			}
			?>
			<input type="hidden" id="osagenda" name="osagenda" value="<?= $documento; ?>">
			<input type="hidden" id="agendaref" name="agendaref" value="<?= $_idref; ?>">
	
			<input type="hidden" id="IDcliente" name="IDcliente" value="<?= $idcliente; ?>">

			<div class="modal-content ">
				<div class="col-lg12">
					<ul class="nav nav-pills m-b-5">
						<li class="active">
							<a href="#home" data-toggle="tab" aria-expanded="false">
								<span class="visible-xs"><i class="fa fa-home"></i></span>
								<span class="hidden-xs">Agendamento</span>
							</a>
						</li>

						<li class="">
							<a href="#messages" data-toggle="tab" aria-expanded="false">
								<span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
								<span class="hidden-xs">Histórico</span>
							</a>
						</li>

					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="home">

							<div class="card-box">
								<div class="row">
									<div class="col-sm-3">
										<div class="row">
											<div class="col-sm-12">
												<label>Dt Posicionamento</label>
												<input name="dtagenda" type="date" id="dtagenda" class="form-control input-sm" value="<?= $data2; ?>" maxlength="10" />
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label>Tipo Agenda</label>
												<select name="IDagenda" id="IDagenda" class="form-control input-sm">
													<option value="2">Preventivo</option>
													<option value="1">Diário</option>
													
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label> Status</label>
												<select name="sitagenda" id="sitagenda" class="form-control input-sm">

													<?php
													$consulta = $pdo->query("SELECT sit_agendaID,sit_agendaDescricao
																									FROM " . $_SESSION['BASE'] . ".situacao_agenda  where sit_idagenda = '$idagenda' and sit_visualiza = '1'
																									order by sit_agendaDescricao");
													$result = $consulta->fetchAll();
													foreach ($result as $row) {
													?><option value="<?= $row["sit_agendaID"]; ?>"><?= ($row["sit_agendaDescricao"]); ?></option><?php
																																														}
																																															?>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label> Prioridade</label>
												<select name="prioridade" id="prioridade" class="form-control input-sm">
													<option value="1">Normal</option>
													<option value="2">Média</option>
													<option value="3">Alta</option>
												</select>
											</div>
										</div>
									
									</div>
									
									<div class="col-sm-1">
									</div>
									<div class="col-sm-8">
										
										<div class="row">
                                                    <label>Data Preventivo</label>
                                                    <select name="preventivocalc" id="preventivocalc" onchange="calculardtprevista()" class="form-control input-sm" style="width: 120px;">
                                                    <option value="0"> </option>
                                                    <?php
                                                        $sqlEmp = "Select * from " . $_SESSION['BASE'] . ".preventivo_mes ";                                                        
                                                        $consultaEmp = $pdo->query($sqlEmp);                                                    
                                                        if ($consultaEmp->rowCount() > 1){
                                                            $retornoEmp = $consultaEmp->fetchAll(\PDO::FETCH_OBJ);

                                                                foreach ($retornoEmp as $rowEmp) {
                                                                            if($rowEmp->mesprev_mes > 360) { 
                                                                                $_prev = intval($rowEmp->mesprev_mes/365);
                                                                                $_prevtext = "Anos";
																				$_dias = 365;
                                                                            }else{
                                                                                $_prev = $rowEmp->mesprev_mes;
                                                                                if($_prev == 1 ) {
                                                                                    $_prevtext = "Mês";
                                                                                }else{
                                                                                    $_prevtext = "Meses";
                                                                                }
																				$_dias = 30;
                                                                               
                                                                            }
                                                                ?>
                                                                    <option value="<?=$_prev*$_dias;?>" <?php if ($_prev == $_preventivo) { ?>selected="selected" <?php } ?>><?=$_prev;?> <?=$_prevtext;?> </option>
                                                                  
                                                                <?php  }
                                                                } ?>
                                                                 </select>
                                                </div>
												
										<div class="row">
											<label>Descrição</label>
											<textarea name="agendadescricao" rows="6" id="agendadescricao" class="form-control input-sm">Entrar contato para agendamento do preventivo</textarea>
										</div>
										<div class="row" style="margin-top: 5px ; text-align:right">
											<button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda" onclick="_agendaincluir()">
												Incluir
											</button>
										</div>
									</div>

								</div>
								<span id="_retornoinclusao">
								</span>
							</div>
						</div> <!-- home -->



						<div class="tab-pane" id="messages">
							<span id="resultAcompanhamento" name="resultAcompanhamento">


								<?php
								if ($idagenda == "") {
									$idagenda = '1';
								};

								$consultaMov = "SELECT Agenda_ID, Agenda_Documento, Agenda_Cadastro, Agenda_DataAgenda, Agenda_Encerrado, 
													Agenda_Usuario, Agenda_CodUsuario, Prioridade, Agenda_Situacao,
													Agenda_descricao, agenda_usuarioEncerramento, usuario_LOGIN ,Agenda_Referencia,
													sit_cor,sit_agendaDescricao ,agenda_solucao
													FROM agenda	INNER JOIN situacao_agenda ON Agenda_Situacao = sit_agendaID
													
													LEFT JOIN usuario ON Agenda_CodUsuario = usuario_CODIGOUSUARIO 
													WHERE   Agenda_Documento = '$documento' and sit_idtabagenda = '$idagenda'
													ORDER BY Agenda_DataAgenda DESC";

								$resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
								?>
								<div class=" table-responsive">
									<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0">
										<thead>
											<tr>
												<th class="text-left">Assunto</th>
												<th class="text-left">Solução</th>
												<th class="text-center">Status</th>
												<th class="text-center">Prioridade</th>
												<th class="text-center">Dt Posicionamento</th>
												<th class="text-center">Refer.</th>
												<th class="text-center">Dt Cadastro</th>
												<th class="text-center">Usuário</th>

											</tr>
										</thead>
										<tbody>
											<?php while ($row = mysqli_fetch_array($resultado)) {
												if ($row['Prioridade'] == 1) {
													$style = 'info';
													$descricao = 'Normal';
												} else if ($row['Prioridade'] == 2) {
													$style = 'warning';
													$descricao = 'Média';
												} else {
													$style = 'danger';
													$descricao = 'Alta';
												}

											?>

												<tr>
													<td class="text-left">
														<div style="width: 200px ;"><?= $row["Agenda_descricao"]; ?></div>
													</td>
													<td class="text-left">
														<div style="width: 400px ;"><?= $row["agenda_solucao"]; ?></div>
													</td>
													<td class="text-center">
														<span class="label label-table label-<?= $row['sit_cor'] ?>"><?= $row['sit_agendaDescricao'] ?></span>
													</td>
													<td class="text-center">
														<span class="label label-table label-<?= $style ?>"><?= $descricao ?></span>
													</td>
													<td class="text-center"><?= date('d/m/Y',  strtotime($row['Agenda_DataAgenda'])) ?></td>
													<td class="text-center"><?= $row['Agenda_Documento']; ?></td>
													<td class="text-center"><?= date('d/m/Y',  strtotime($row['Agenda_Cadastro'])) ?></td>
													<td class="text-center"><?= $row['usuario_LOGIN']; ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</span>
						</div>

					</div> <!-- tab-content -->



				</div> <!-- modal-content -->

				<?php
				exit();




				?>

				