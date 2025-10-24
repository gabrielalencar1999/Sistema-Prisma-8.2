<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

		    $sql = "Select UF from " . $_SESSION['BASE'] . ".parametro ";
			$stm = $pdo->prepare("$sql");
			$stm->execute();
			if ($stm->rowCount() > 0) {
				while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
				{
						$_UF = $linha->UF;
				}
			}
			if($_UF == 'MT' OR $_UF == 'MS' OR $_UF == 'RO' OR $_UF == 'RR' OR $_UF == 'AM' ){
				date_default_timezone_set('America/Manaus');
			}else{
				date_default_timezone_set('America/Sao_Paulo');
			}
				


use Database\MySQL;

$pdo = MySQL::acessabd();


function calcularHorasTotais($entradaManha, $saidaManha, $entradaTarde, $saidaTarde) {
    // Converter horários para objetos DateTime
    $entradaManha = new DateTime($entradaManha);
    $saidaManha = new DateTime($saidaManha);
    $entradaTarde = new DateTime($entradaTarde);
    $saidaTarde = new DateTime($saidaTarde);

    // Calcular intervalos
    $intervaloManha = $entradaManha->diff($saidaManha);
    $intervaloTarde = $entradaTarde->diff($saidaTarde);

    // Somar os intervalos
    $horasTotais = $intervaloManha->h + $intervaloTarde->h; // Soma das horas
    $minutosTotais = $intervaloManha->i + $intervaloTarde->i; // Soma dos minutos

    // Ajustar minutos para horas
    if ($minutosTotais >= 60) {
        $horasTotais += intdiv($minutosTotais, 60);
        $minutosTotais = $minutosTotais % 60;
    }

    // Retornar resultado formatado
    return sprintf("%02d:%02d", $horasTotais, $minutosTotais);
}
// Função para calcular horário
function dif_horario($horario1, $horario2)
{
	$horario1 = strtotime("1/1/1980 $horario1");
	$horario2 = strtotime("1/1/1980 $horario2");

	if ($horario2 < $horario1) {
		$horario2 = $horario2 + 86400;
	}

	return ($horario2 - $horario1) / 3600;
}

function dia($dia)
{
	
	switch ($dia) {
		case "0":
			echo "Domingo";
			break;
		case "1":
			echo "Segunda";
			break;
		case "2":
			echo "Terça";
			break;
		case "3":
			echo "Quarta";
			break;
		case "4":
			echo "Quinta";
			break;
		case "5":
			echo "Sexta";
			break;
		case "6":
			echo "Sábado";
			break;
	}
}


function soma_horas($hora1, $hora2)
{

	if($hora1 != "" and $hora2 != "") {
	$times = array(

		$hora1, //aqui vai o valor da sua tabela

		$hora2, //aqui vai o valor da sua tabela

	);

	$seconds = 0;

	foreach ($times as $time) {	
		
		list($g, $i, $s) = explode(':', $time);

		$seconds += $g * 3600;

		$seconds += $i * 60;

		$seconds += $s;
	
	}

	$hours = floor($seconds / 3600);

	$seconds -= $hours * 3600;

	$minutes = floor($seconds / 60);

	$seconds -= $minutes * 60;



	if (strlen($minutes) == 1) {

		$minutes = "0" . $minutes;
	}

	if (strlen($seconds) == 1) {

		$seconds = "0" . $seconds;
	}

	return "{$hours}:{$minutes}";
}else { 
	return "00:00";
}
}


function m2h($mins) {
	// Se os minutos estiverem negativos
	if ($mins < 0)
		$min = abs($mins);
	else
		$min = $mins;

	// Arredonda a hora
	$h = floor($min / 60);
	$m = ($min - ($h * 60)) / 100;
	$horas = $h + $m;

	// Matemática da quinta série
	// Detalhe: Aqui também pode se usar o abs()
	if ($mins < 0)
		$horas *= -1;

	// Separa a hora dos minutos
	$sep = explode('.', $horas);
	$h = $sep[0];
	if (empty($sep[1]))
		$sep[1] = 00;

	$m = $sep[1];

	// Aqui um pequeno artifício pra colocar um zero no final
	if (strlen($m) < 2)
		$m = $m . 0;

	return sprintf('%02d:%02d', $h, $m);
} 

$_acao = $_POST["acao"];
//$dataini = $_parametros['_dataIni'];
// $datafim = $_parametros['_dataFim']; 
//$datafim = $dataini;
//$dtpesquisa = ""; $nextdate = "";

//$ordem = $_parametros['ordem'];
$mEntradax = "";
$mSaidax =  "";
$tEntradax = "";
$tSaidax = "";
$motivo = "";
$mSaida = "";
$tEntrada = "";
$tSaida = "";
$INTsaida = "";
$INTEntrada = "";
$dia_semana--;
$lat_a = ""; $lat_b = "";  $lat_c = "";$lat_d  = "";

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
// $data_atual      = $dia."/".$mes."/".$ano; 
$data_atual = $ano . "-" . $mes . "-" . $dia;;
//  $data_atual2      = "01/".$mes."/".$ano; 
$data     = $ano . "-" . $mes . "-" . $dia;
$hora = date("H:i:s");
$datahora      = $hora;
$datahoracpt = base64_encode($data." ".$hora);
/*
if ($dataini == "") {
	$date = date("Ymd");

	$ano = substr($nextdate, 0, 4);
	$mes = substr($nextdate, 4, 2);
	$dia =  substr($nextdate, 6, 2);
	$data_prevista      = $ano . "-" . $mes . "-" . $dia;

	$dataini =   $data_prevista;
	$datafim =   $data_prevista;
}*/

//$situacao =   $_parametros['situacao'];




if ($_acao == 1) { //lista
?>
	<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
		<thead>
			<tr>
				<th></th>
				<th>Nome </th>
				<th>Apelido</th>
				<th>Dt Última Atualização </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sql = "Select usuario_CODIGOUSUARIO,usuario_NOME,usuario_APELIDO,DATE_FORMAT(usuario_ultatualizacaoponto,'%d/%m/%Y - %T ') as data from " . $_SESSION['BASE'] . ".usuario  where usuario_ATIVO = 'Sim' order by usuario_NOME";

			$stm = $pdo->prepare("$sql");
			$stm->execute();
			if ($stm->rowCount() > 0) {
				while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
				{
			?>
					<tr>
						<td class="actions text-center" style="width: 20px ;">
							<button class="btn btn-icon waves-effect waves-light btn-warning btn-sm" data-toggle="modal" data-target="#custom-modal-editar" onclick="_editar('<?= $linha->usuario_CODIGOUSUARIO ?>')"><i class="fa  fa-clock-o  fa-lg "></i> </button>
						</td>
						<td><?= $linha->usuario_NOME; ?></td>
						<td><?= $linha->usuario_APELIDO; ?></td>
						<td><?= $linha->data; ?></td>

					</tr>
			<?php
				}
			}

			?>
		</tbody>
	</table>

<?php
	exit();
}

if ($_acao == 2) { //carregar folha ponto

	$id = $_parametros['_idtec'];
	if ($mes == "") {

		$mes = date('m');

		$ano = date('Y');
	}
	$sql = "Select usuario_CODIGOUSUARIO,usuario_NOME from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '$id'";

	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$_nome = $linha->usuario_NOME;
		}
	}

?>

	<div class="form-group">
		<div class="row">
			<div class="col-md-5" style="margin-top: 25px;">
				<h4 class="text-uppercase font-600"><i class="ti-user "></i> <?= $_nome; ?></h4>
			</div>
			<div class="col-md-3">
				<label for="field-1" class="control-label">Mês</label>
				<select name="mes" id="mes" class="form-control" onchange="_listaMes();">
					<option value="01" <?php if ($mes == "01") { ?>selected="selected" <?php } ?>>JANEIRO</option>
					<option value="02" <?php if ($mes == "02") { ?>selected="selected" <?php } ?>>FEVEREIRO</option>
					<option value="03" <?php if ($mes == "03") { ?>selected="selected" <?php } ?>>MARÇO</option>
					<option value="04" <?php if ($mes == "04") { ?>selected="selected" <?php } ?>>ABRIL</option>
					<option value="05" <?php if ($mes == "05") { ?>selected="selected" <?php } ?>>MAIO</option>
					<option value="06" <?php if ($mes == "06") { ?>selected="selected" <?php } ?>>JUNHO</option>
					<option value="07" <?php if ($mes == "07") { ?>selected="selected" <?php } ?>>JULHO</option>
					<option value="08" <?php if ($mes == "08") { ?>selected="selected" <?php } ?>>AGOSTO</option>
					<option value="09" <?php if ($mes == "09") { ?>selected="selected" <?php } ?>>SETEMBRO</option>
					<option value="10" <?php if ($mes == "10") { ?>selected="selected" <?php } ?>>OUTUBRO</option>
					<option value="11" <?php if ($mes == "11") { ?>selected="selected" <?php } ?>>NOVEMBRO</option>
					<option value="12" <?php if ($mes == "12") { ?>selected="selected" <?php } ?>>DEZEMBRO</option>
				</select>
			</div>
			<div class="col-md-2">
				<label for="field-1" class="control-label">Ano</label>

				<select name="ano" id="ano" class="form-control" onchange="_listaMes();">
					<option value="2022">2022</option>
					<option value="2023" <?php if ($ano == "2023") { ?>selected="selected" <?php } ?>>2023</option>
					<option value="2024" <?php if ($ano == "2024") { ?>selected="selected" <?php } ?>>2024</option>
					<option value="2025" <?php if ($ano == "2025") { ?>selected="selected" <?php } ?>>2025</option>
				</select>
			</div>
			<div class="col-md-2" style="margin-top: 25px;">
				<button class="btn btn-inverse waves-effect waves-light" onclick="_print()"><span class="btn-label btn-label"> <i class="fa fa-print"></i></span>Imprimir</button>
			</div>
		</div>
	</div>
		<div class="row">
					<div id="_ret">
					<div class="card-box table-responsive">
						<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
							<thead>
								<tr>

									<th>Alterar</th>
									<th>SEMANA</th>
									<th>DIA</th>
									<th><strong>ENTRADA</strong></th>
									<th><strong>SAIDA</strong></th>
									<th><strong>ENTRADA</strong></th>
									<th><strong>SAIDA</strong></th>
									<th>MOTIVO</th>
									<th>TOTAL HORAS</th>
								</tr>
								</tr>
							</thead>
							<tbody>

								<?php

								$dia = date("1");
								$mes = date('m');
								if ($mes == "01") {
									$diap = 31;
								} //janeiro tem 31 dias
								// Fevereiro tem 28 (ou 29, nos anos bissextos)						 
								if ($mes == "02") {
									$diap = 29;
								} //janeiro tem 31 dias						 
								if ($mes == "03") {
									$diap = 31;
								}  //Março tem 31						 
								if ($mes == "04") {
									$diap = 30;
								}  // Abril tem 30						 
								if ($mes == "05") {
									$diap = 31;
								}  //Maio tem 31						 
								if ($mes == "06") {
									$diap = 30;
								}  // Junho tem 30						 
								if ($mes == "07") {
									$diap = 31;
								}  // Julho tem 31						 
								if ($mes == "08") {
									$diap = 31;
								}  // Agosto tem 31						 
								if ($mes == "09") {
									$diap = 30;
								}  // Setembro tem 30						 
								if ($mes == "10") {
									$diap = 31;
								}  // Outubro tem 31						 
								if ($mes == "11") {
									$diap = 30;
								}  // Novembro tem 30						 
								if ($mes == "12") {
									$diap = 31;
								}  // Dezembro tem 31


								while ($diap >= $dia) {

									$dia_calendario = date("d", mktime(0, 0, 0, $mes, $dia, $ano));
									$dataconsultar = "$ano" . "-" . "$mes" . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
									$_idponto = $dia_calendario;
									$sql = "Select reg_id,Motivo,Ind_Ger,A_GPS,B_GPS,C_GPS,D_GPS,
									Hora_E_Manha as MENTRADA,date_format(Hora_E_Manha,'%H:%i') as manha_entrada,
																		Hora_S_Manha as MSAIDA,date_format(Hora_S_Manha,'%H:%i') as manha_saida,
																		Hora_E_Tarde as TENTRADA,date_format(Hora_E_Tarde,'%H:%i') as tarde_entrada,
																		Hora_S_Tarde as TSAIDA,date_format(Hora_S_Tarde,'%H:%i') as tarde_saida,
																		DATE_FORMAT(Hora_E_Manha,'%H:%i') as MENTRADAx,DATE_FORMAT(Hora_S_Manha,'%H:%i') as MSAIDAx,DATE_FORMAT(Hora_E_Tarde,'%H:%i') as TENTRADAx,
																		DATE_FORMAT(Hora_S_Tarde,'%H:%i') as TSAIDAx,  SUBTIME(DATE_FORMAT(Hora_S_Manha,'%H:%i'),DATE_FORMAT(Hora_E_Manha,'%H:%i')) as hora1,
																		SUBTIME(DATE_FORMAT(`Hora_S_Tarde`,'%H:%i'),DATE_FORMAT(`Hora_E_Tarde`,'%H:%i')) as hora2,
																		DATE_FORMAT(hora_intervaloBfim,'%H:%i') as INTENTRADA,DATE_FORMAT(hora_intervaloBini,'%H:%i') as INTSAIDA 
																		from " . $_SESSION['BASE'] . ".registroponto where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";

									$stm = $pdo->prepare("$sql");
									$stm->execute();
									if ($stm->rowCount() > 0) {
										while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
										{

											

											$motivo = $linha->Motivo;

											$mEntradax = $linha->MENTRADAx;

											$mSaidax = $linha->MSAIDAx;

											$tEntradax = $linha->TENTRADAx;

											$tSaidax = $linha->TSAIDAx;

											$mEntrada = $linha->manha_entrada;

											$mSaida = $linha->manha_saida;

											$tEntrada = $linha->tarde_entrada;

											$tSaida = $linha->tarde_saida;

											$hora1 =  dif_horario($mEntrada, $mSaida);

											//	$hora2 = mysql_result($executaR,$Linhapro,"hora2");
											$hora2 =  dif_horario($tEntrada, $tSaida);

											$totalHoras = calcularHorasTotais($mEntrada, $mSaida, $tEntrada, $tSaida);

											$ger = $linha->Ind_Ger;
											if($linha->A_GPS != "") {
											
													$gps = explode('|',$linha->A_GPS);
													$lat_a = 	$gps[0];
													$long_a = $gps[1];
											}
											if($linha->B_GPS != "") {
													$gps = explode('|',$linha->B_GPS);
													$lat_b = 	$gps[0];
													$long_b = $gps[1];
											}

											if($linha->C_GPS != "") {
												$gps = explode('|',$linha->C_GPS);
												$lat_c = 	$gps[0];
												$long_c = $gps[1];
											}

											if($linha->D_GPS != "") {
												$gps = explode('|',$linha->D_GPS);
												$lat_d = 	$gps[0];
												$long_d = $gps[1];
											}									
										
										}
									}

								?>
									<tr id="_linha<?=$_idponto; ?>">
										<td class="actions text-center">
											<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_editarPonto('_linha<?=$_idponto; ?>')"><i class="fa fa-pencil"></i></a>
										<td height="23"><?= $dia_s_calendario = dia(date("w", mktime(0, 0, 0, $mes, $dia, $ano)));  ?></td>
										<td height="23" style="text-align: center;"><?PHP echo $dia_s_calendario . "  " . $dia_calendario;?></td>
										<td>
										<div style="text-align: center;">
											<?=$mEntradax; 
											if($lat_a != "") {?>
												<a href="https://www.waze.com/location?ll=<?=$lat_a;?>,<?=$long_a;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
											<?php  } ?>
										</div>
									</td>
									<td>
										<div style="text-align: center;">
											<?= $mSaidax; 
											if($lat_b != "") {?>
												<a href="https://www.waze.com/location?ll=<?=$lat_b;?>,<?=$long_b;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
											<?php  } ?>
										</div>
									</td>
									<td>
										<div style="text-align: center;">
											<?= $tEntradax;
											if($lat_c != "") {?>
												<a href="https://www.waze.com/location?ll=<?=$lat_c;?>,<?=$long_c;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
											<?php  } ?>
										</div>
									</td>

									<td>
										<div style="text-align: center;">
											<?= $tSaidax; 
											if($lat_d != "") {?>
												<a href="https://www.waze.com/location?ll=<?=$lat_d;?>,<?=$long_d;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
											<?php  } ?>
										</div>
									</td>
										<td><?= $motivo; ?></td>
										<td style="text-align: center;"><?php
										echo $totalHoras;
										if($hora1 != "" and $hora2 != "") {		
											if($horag != ""){
												$horag = soma_horas("$horag", "$totalHoras");
											}else {
												$horag =$totalHoras;
											}
										}
											

/*
																		$t =  soma_horas("$hora1", "$hora2");
																		echo $t;
																		$M = substr($t, -2);

																		$mi = $mi + $M;
																		//echo $mi;
																		if($hora1 != "" and $hora2 != "") {																		
																			$horag = $horag + soma_horas("$hora1", "$hora2");
																		}
*/
																		$hora1 = "";
																		$t  = "";
																		$M = 0;
																		$hora2 = ""
																		?></td>
										<?php
										$totalHoras = "";
										$mEntradax = "";
										$mSaidax =  "";
										$tEntradax = "";
										$tSaidax = "";
										$motivo = "";
										$mSaida = "";
										$tEntrada = "";
										$tSaida = "";
										$INTsaida = "";
										$INTEntrada = "";
										$dia_semana--;
										$lat_a = ""; $lat_b = "";  $lat_c = "";$lat_d  = "";
										$dia = $dia + 1;
										$cont++;
										?>

									</tr>
								<?php

								} //fim while dias

								?>
								 <tr >
				<td  colspan="7" ></td>

				<td  ><div  style="text-align: center;" ><strong>TOTAL HORAS:</strong></div></td>

				<td ><div style="text-align: center;"><strong><?=$horag;?></strong>  </div></td>
				</tr> 
							</tbody>
						</table>
						</div>
					</div>
			</div>
	<?php
	exit();
}

if ($_acao == 3) { //carregar folha ponto mes


	$id = $_parametros['_idtec'];


	$mes = $_parametros['mes'];

	$ano = $_parametros['ano'];


	$sql = "Select usuario_CODIGOUSUARIO,usuario_NOME from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '$id'";

	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$_nome = $linha->usuario_NOME;
		}
	}

	?>
<div class="card-box table-responsive">
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>

					<th>Alterar</th>
					<th>SEMANA</th>
					<th>DIA</th>
					<th><strong>ENTRADA</strong></th>
					<th><strong>SAIDA</strong></th>
					<th><strong>ENTRADA</strong></th>
					<th><strong>SAIDA</strong></th>
					<th>MOTIVO</th>
					<th>TOTAL HORAS</th>
				</tr>
				</tr>
			</thead>
			<tbody>

				<?php

				$dia = date("1");

				if ($mes == "01") {
					$diap = 31;
				} //janeiro tem 31 dias
				// Fevereiro tem 28 (ou 29, nos anos bissextos)						 
				if ($mes == "02") {
					$diap = 29;
				} //janeiro tem 31 dias						 
				if ($mes == "03") {
					$diap = 31;
				}  //Março tem 31						 
				if ($mes == "04") {
					$diap = 30;
				}  // Abril tem 30						 
				if ($mes == "05") {
					$diap = 31;
				}  //Maio tem 31						 
				if ($mes == "06") {
					$diap = 30;
				}  // Junho tem 30						 
				if ($mes == "07") {
					$diap = 31;
				}  // Julho tem 31						 
				if ($mes == "08") {
					$diap = 31;
				}  // Agosto tem 31						 
				if ($mes == "09") {
					$diap = 30;
				}  // Setembro tem 30						 
				if ($mes == "10") {
					$diap = 31;
				}  // Outubro tem 31						 
				if ($mes == "11") {
					$diap = 30;
				}  // Novembro tem 30						 
				if ($mes == "12") {
					$diap = 31;
				}  // Dezembro tem 31


				while ($diap >= $dia) {

					$dia_calendario = date("d", mktime(0, 0, 0, $mes, $dia, $ano));
					$dataconsultar = "$ano" . "-" . "$mes" . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
				
					$_idponto = $dia_calendario;
					$sql = "Select reg_id,Motivo,Ind_Ger,A_GPS,B_GPS,C_GPS,D_GPS,
						Hora_E_Manha as MENTRADA,date_format(Hora_E_Manha,'%H:%i') as manha_entrada,
															Hora_S_Manha as MSAIDA,date_format(Hora_S_Manha,'%H:%i') as manha_saida,
															Hora_E_Tarde as TENTRADA,date_format(Hora_E_Tarde,'%H:%i') as tarde_entrada,
															Hora_S_Tarde as TSAIDA,date_format(Hora_S_Tarde,'%H:%i') as tarde_saida,
															DATE_FORMAT(Hora_E_Manha,'%H:%i') as MENTRADAx,DATE_FORMAT(Hora_S_Manha,'%H:%i') as MSAIDAx,DATE_FORMAT(Hora_E_Tarde,'%H:%i') as TENTRADAx,
															DATE_FORMAT(Hora_S_Tarde,'%H:%i') as TSAIDAx,  SUBTIME(DATE_FORMAT(Hora_S_Manha,'%H:%i'),DATE_FORMAT(Hora_E_Manha,'%H:%i')) as hora1,
															SUBTIME(DATE_FORMAT(`Hora_S_Tarde`,'%H:%i'),DATE_FORMAT(`Hora_E_Tarde`,'%H:%i')) as hora2,
															DATE_FORMAT(hora_intervaloBfim,'%H:%i') as INTENTRADA,DATE_FORMAT(hora_intervaloBini,'%H:%i') as INTSAIDA 
															from " . $_SESSION['BASE'] . ".registroponto where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";

						$stm = $pdo->prepare("$sql");
						$stm->execute();
						if ($stm->rowCount() > 0) {
							while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
							{

							

							$motivo = $linha->Motivo;

							$mEntradax = $linha->MENTRADAx;

							$mSaidax = $linha->MSAIDAx;

							$tEntradax = $linha->TENTRADAx;

							$tSaidax = $linha->TSAIDAx;

							$mEntrada = $linha->manha_entrada;

							$mSaida = $linha->manha_saida;

							$tEntrada = $linha->tarde_entrada;

							$tSaida = $linha->tarde_saida;

							$hora1 =  dif_horario($mEntrada, $mSaida);

							//	$hora2 = mysql_result($executaR,$Linhapro,"hora2");
							$hora2 =  dif_horario($tEntrada, $tSaida);

						$totalHoras = calcularHorasTotais($mEntrada, $mSaida, $tEntrada, $tSaida);

							$ger = $linha->Ind_Ger;

							$gps = explode('|',$linha->A_GPS);
							$lat_a = 	$gps[0];
							$long_a = $gps[1];

							$gps = explode('|',$linha->B_GPS);
							$lat_b = 	$gps[0];
							$long_b = $gps[1];

							$gps = explode('|',$linha->C_GPS);
							$lat_c = 	$gps[0];
							$long_c = $gps[1];

							$gps = explode('|',$linha->D_GPS);
							$lat_d = 	$gps[0];
							$long_d = $gps[1];
						}
					}

				?>
					<tr id="_linha<?=$_idponto; ?>">
						<td class="actions text-center">
							<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_editarPonto('_linha<?=$_idponto;?>')"><i class="fa fa-pencil"></i></a>
						<td height="23"><?= $dia_s_calendario = dia(date("w", mktime(0, 0, 0, $mes, $dia, $ano)));  ?></td>
						<td height="23" style="text-align: center;"><?PHP echo $dia_s_calendario . "  " . $dia_calendario ; ?></td>
						<td>
							<div style="text-align: center;">
								<?= $mEntradax; 
								if($lat_a != "") {?>
									<a href="https://www.waze.com/location?ll=<?=$lat_a;?>,<?=$long_a;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
								<?php  } ?>
							</div>
						</td>
						<td>
							<div style="text-align: center;">
								<?= $mSaidax; 
								if($lat_b != "") {?>
									<a href="https://www.waze.com/location?ll=<?=$lat_b;?>,<?=$long_b;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
								<?php  } ?>
							</div>
						</td>
						<td>
							<div style="text-align: center;">
								<?= $tEntradax;
								if($lat_c != "") {?>
									<a href="https://www.waze.com/location?ll=<?=$lat_c;?>,<?=$long_c;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
								<?php  } ?>
							</div>
						</td>

						<td>
							<div style="text-align: center;">
								<?= $tSaidax; 
								if($lat_d != "") {?>
									<a href="https://www.waze.com/location?ll=<?=$lat_d;?>,<?=$long_d;?>" target="_blank"><i class="fa  fa-street-view text-success fa-1x"></i></a>
								<?php  } ?>
							</div>
						</td>
						<td><?= $motivo; ?></td>
						<td style="text-align: center;"><?php
							echo $totalHoras;
							if($hora1 != "" and $hora2 != "") {		
								if($horag != ""){
									$horag = soma_horas("$horag", "$totalHoras");
								}else {
									$horag =$totalHoras;
								}
							}
								
								$totalHoras = "";
/*

														$t =  soma_horas("$hora1", "$hora2");
														echo $t;
														$M = substr($t, -2);

														$mi = $mi + $M;
														//echo $mi;

														if($hora1 != "" and $hora2 != "") {																		
															$horag = $horag + soma_horas("$hora1", "$hora2");
														}
*/

														$hora1 = "";
														$t  = "";
														$M = 0;
														$hora2 = ""
														?></td>
						<?php
						$totalHoras = "";
						$mEntradax = "";
						$mSaidax =  "";
						$tEntradax = "";
						$tSaidax = "";
						$motivo = "";
						$mSaida = "";
						$tEntrada = "";
						$tSaida = "";
						$INTsaida = "";
						$INTEntrada = "";
						$dia_semana--;

						$lat_a = ""; $lat_b = "";  $lat_c = "";$lat_d  = "";
						$dia = $dia + 1;
						$cont++;
						?>

					</tr>
				<?php

				} //fim while dias

				?>
				
				 <tr >
				<td  colspan="7" ></td>

				<td  ><div  style="text-align: center;" ><strong>TOTAL HORAS:</strong></div></td>

				<td ><div style="text-align: center;"><strong><?=$horag;?></strong>  </div></td>
				</tr> 
			</tbody>
		</table>
		
</div>
	<?php

	exit();
}

if ($_acao == 4) { //print folha ponto
	$id = $_parametros['_idtec'];
	$mes = date($_parametros["mes"]);
	$ano = date($_parametros["ano"]);
	$dia = date("1");
	if ($mes == "") {
		$mes = date('m');
		$ano = date('Y');
	}



	$dia_semana = date('w');

	$sql = "Select usuario_CODIGOUSUARIO,usuario_NOME,pis,usuario_numero_carteira_trabalho,usuario_serie_carteira_trabalho,usuario_cpf from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '$id'";

	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$nome = $linha->usuario_NOME;
			$CARTEIRA = $linha->usuario_numero_carteira_trabalho;
			$SERIE =  $linha->usuario_serie_carteira_trabalho;
			$PIS =  $linha->pis;
			$CPF  =  $linha->usuario_cpf;
		}
	}

	$sql = "Select * from " . $_SESSION['BASE'] . ".parametro ";
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($rst = $stm->fetch()) {

			$endereco = $rst["ENDERECO"];

			$bairro = $rst["BAIRRO"];

			$cep = $rst["Cep"];

			$cidade = $rst["CIDADE"];

			$estado = $rst["UF"];

			$email = $rst["CGC"];

			$inscricao = $rst["INSC_ESTADUAL"];

			$cnpj = $rst["CGC"];

			$telefone = "(" . $rst["DDD"] . ") " . $rst["TELEFONE"];

			$email = $rst["EMAIL"];

			$site = $rst["site"];

			$fantasia = $rst["NOME_FANTASIA"];
		}
	}

	?>

		<style type="text/css">
			table.bordasimples {
				border-collapse: collapse;
				font-size: 11px;
			}

			table.bordasimples tr td {
				border: 1px solid #000000;
			}

			.style8 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;
			}

			.style5 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}

			.style10 {
				font-family: Arial, Helvetica, sans-serif
			}

			.style11 {
				font-size: 12px
			}
		</style>

<table width="679" border="0" class="bordasimples">
  <tr>
    <td colspan="4"><div align="center" class="style11">FOLHA PONTO <strong> REF.:
      <?=$mes."/".$ano;?></strong>
    </div></td>
    </tr>
  <tr>
    <td width="106"><span class="style8">Empresa:</span></td>
    <td colspan="2" ><strong><strong>
      <?=$fantasia;?>
    </strong></strong></td>
    <td >CNPJ:<span class="style5">
      <?=$cnpj;?>
    </span></td>
    </tr>
  <tr>
    <td><span class="style8">Endere&ccedil;o:</span></td>
    <td colspan="3"><?=$endereco;?>
      - <span class="style5">
        <?=$bairro;?>
        </span> - <span class="style5">
          <?=$cidade;?>
          -
          <?=$estado;?>
        </span></td>
  </tr>
  <tr>
    <td><span class="style8">Nome Funcionario:</span></td>
    <td width="225" colspan="2"><?=$nome;?></td>
    <td><div align="left"><span class="style8">CPF:</span>
      <?=$CPF;?>
    </div></td>
  </tr>
  <tr>
    <td><span class="style8">Cartreia Profissional:</span></td>
    <td><?=$CARTEIRA;?></td>
    <td><span class="style8">SÉRIE:</span>
      <?=$SERIE;?></td>
    <td><span class="style8">PIS: </span><?=$PIS;?></td>
  </tr>
		<div class="card-box table-responsive">
		<table width="680" border="0" class="bordasimples" style="margin_left:10px">
			<thead>
				<tr>
					<th>SEMANA</th>
					<th>DIA</th>
					<th><strong>ENTRADA</strong></th>
					<th><strong>SAIDA</strong></th>
					<th><strong>ENTRADA</strong></th>
					<th><strong>SAIDA</strong></th>
					<th>MOTIVO</th>
					<th>H.DIÁRIA</th>
					<!-- <th>ATRASOS</th>
					<th>H.EXTRA</th>
					<th>INT.1</th>
					<th>INT.2</th> -->
				</tr>
				</tr>
			</thead>
			<tbody>

				<?php

				$dia = date("1");
				//$mes = date('m');
				if ($mes == "01") {
					$diap = 31;
				} //janeiro tem 31 dias
				// Fevereiro tem 28 (ou 29, nos anos bissextos)						 
				if ($mes == "02") {
					$diap = 29;
				} //janeiro tem 31 dias						 
				if ($mes == "03") {
					$diap = 31;
				}  //Março tem 31						 
				if ($mes == "04") {
					$diap = 30;
				}  // Abril tem 30						 
				if ($mes == "05") {
					$diap = 31;
				}  //Maio tem 31						 
				if ($mes == "06") {
					$diap = 30;
				}  // Junho tem 30						 
				if ($mes == "07") {
					$diap = 31;
				}  // Julho tem 31						 
				if ($mes == "08") {
					$diap = 31;
				}  // Agosto tem 31						 
				if ($mes == "09") {
					$diap = 30;
				}  // Setembro tem 30						 
				if ($mes == "10") {
					$diap = 31;
				}  // Outubro tem 31						 
				if ($mes == "11") {
					$diap = 30;
				}  // Novembro tem 30						 
				if ($mes == "12") {
					$diap = 31;
				}  // Dezembro tem 31


				while ($diap >= $dia) {

					$dia_calendario = date("d", mktime(0, 0, 0, $mes, $dia, $ano));
					$dataconsultar = "$ano" . "-" . "$mes" . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
				
					$sql = "Select *,
											Hora_E_Manha as MENTRADA,date_format(Hora_E_Manha,'%H:%i') as manha_entrada,
											Hora_S_Manha as MSAIDA,date_format(Hora_S_Manha,'%H:%i') as manha_saida,
											Hora_E_Tarde as TENTRADA,date_format(Hora_E_Tarde,'%H:%i') as tarde_entrada,
											Hora_S_Tarde as TSAIDA,date_format(Hora_S_Tarde,'%H:%i') as tarde_saida,
											DATE_FORMAT(Hora_E_Manha,'%H:%i') as MENTRADAx,DATE_FORMAT(Hora_S_Manha,'%H:%i') as MSAIDAx,DATE_FORMAT(Hora_E_Tarde,'%H:%i') as TENTRADAx,
											DATE_FORMAT(Hora_S_Tarde,'%H:%i') as TSAIDAx,  SUBTIME(DATE_FORMAT(Hora_S_Manha,'%H:%i'),DATE_FORMAT(Hora_E_Manha,'%H:%i')) as hora1,
											 SUBTIME(DATE_FORMAT(`Hora_S_Tarde`,'%H:%i'),DATE_FORMAT(`Hora_E_Tarde`,'%H:%i')) as hora2,
											 DATE_FORMAT(hora_intervaloBfim,'%H:%i') as INTENTRADA,DATE_FORMAT(hora_intervaloBini,'%H:%i') as INTSAIDA 
											 from " . $_SESSION['BASE'] . ".registroponto where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";

					$stm = $pdo->prepare("$sql");
					$stm->execute();
					if ($stm->rowCount() > 0) {
						while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
						{
							
							$motivo = $linha->Motivo;

							$mEntradax = $linha->MENTRADAx;

							$mSaidax = $linha->MSAIDAx;

							$tEntradax = $linha->TENTRADAx;

							$tSaidax = $linha->TSAIDAx;

							$mEntrada = $linha->manha_entrada;

							$mSaida = $linha->manha_saida;

							$tEntrada = $linha->tarde_entrada;

							$tSaida = $linha->tarde_saida;

								$hora1 =  dif_horario($mEntrada, $mSaida);

								//$hora2 = mysql_result($executaR,$Linhapro,"hora2");
					$hora2 =  dif_horario($tEntrada, $tSaida);

						$totalHoras = calcularHorasTotais($mEntrada, $mSaida, $tEntrada, $tSaida);

							$ger = $linha->Ind_Ger;
						}
					}

				?>
					<tr>

						<td height="23"><?= $dia_s_calendario = dia(date("w", mktime(0, 0, 0, $mes, $dia, $ano)));  ?></td>
						<td height="23" style="text-align: center;"><?PHP echo $dia_s_calendario . "  " . $dia_calendario; ?></td>
						<td>
							<div style="text-align: center;">
								<?= $mEntradax; ?> 
							</div>
						</td>
						<td>
							<div style="text-align: center;">
								<?= $mSaidax; ?>
							</div>
						</td>
						<td>
							<div style="text-align: center;">
								<?= $tEntradax; ?>
							</div>
						</td>

						<td>
							<div style="text-align: center;">
								<?= $tSaidax; ?>
							</div>
						</td>
						<td><?= $motivo; ?></td>
						<td style="text-align: center;"><?php
						echo $totalHoras;

						

/*
														$t =  soma_horas("$hora1", "$hora2");
														echo $t;
														$M = substr($t, -2);

														$mi = $mi + $M;
														//echo $mi;

													

*/
if($hora1 != "" and $hora2 != "") {		
	if($horag != ""){
		$horag = soma_horas("$horag", "$totalHoras");
	}else {
		$horag =$totalHoras;
	}
	
}
														$hora1 = "";
														$t  = "";
														$M = 0;
														$hora2 = ""
														?></td>
						<?php
						$totalHoras = "";
						$mEntradax = "";
						$mSaidax =  "";
						$tEntradax = "";
						$tSaidax = "";
						$motivo = "";
						$mSaida = "";
						$tEntrada = "";
						$tSaida = "";
						$INTsaida = "";
						$INTEntrada = "";
						$dia_semana--;
						$dia = $dia + 1;
						$cont++;
						?>

					</tr>
				<?php


				} //fim while dias

				
				?>
				 <tr >
				<td  colspan="6" ></td>

				<td  ><div  style="text-align: center;" ><strong>TOTAL HORAS:</strong></div></td>

				<td ><div style="text-align: center;"><strong><?=$horag;?></strong>  </div></td>
				</tr> 

			</tbody>
		</table>
		</div>
	<?php
	
	exit();
}

if ($_acao == 5) { //editar folha ponto
	//print_r($_parametros);

	$id = $_parametros['_idtec'];
	$seldia = substr($_parametros['_idsel'],-2);
	$selmes =   $_parametros['mes'];
	$selano =   $_parametros['ano'];

	$dataconsultar = $selano."-" .$selmes."-".$seldia;

	$_idponto = $seldia;
	$sql = "Select  reg_id,Motivo,Ind_Ger,A_GPS,B_GPS,C_GPS,D_GPS,
	Hora_E_Manha,Hora_S_Manha,Hora_E_Tarde,Hora_S_Tarde,
						Hora_E_Manha as MENTRADA,date_format(Hora_E_Manha,'%H:%i') as manha_entrada,
						Hora_S_Manha as MSAIDA,date_format(Hora_S_Manha,'%H:%i') as manha_saida,
						Hora_E_Tarde as TENTRADA,date_format(Hora_E_Tarde,'%H:%i') as tarde_entrada,
						Hora_S_Tarde as TSAIDA,date_format(Hora_S_Tarde,'%H:%i') as tarde_saida,
						DATE_FORMAT(Hora_E_Manha,'%H:%i') as MENTRADAx,DATE_FORMAT(Hora_S_Manha,'%H:%i') as MSAIDAx,DATE_FORMAT(Hora_E_Tarde,'%H:%i') as TENTRADAx,
						DATE_FORMAT(Hora_S_Tarde,'%H:%i') as TSAIDAx,  SUBTIME(DATE_FORMAT(Hora_S_Manha,'%H:%i'),DATE_FORMAT(Hora_E_Manha,'%H:%i')) as hora1,
						SUBTIME(DATE_FORMAT(`Hora_S_Tarde`,'%H:%i'),DATE_FORMAT(`Hora_E_Tarde`,'%H:%i')) as hora2,
						DATE_FORMAT(hora_intervaloBfim,'%H:%i') as INTENTRADA,DATE_FORMAT(hora_intervaloBini,'%H:%i') as INTSAIDA 
						from " . $_SESSION['BASE'] . ".registroponto						
						where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";
  
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			

			$motivo = $linha->Motivo;

			$mEntradax = $linha->MENTRADAx;

			$mSaidax = $linha->MSAIDAx;

			$tEntradax = $linha->TENTRADAx;

			$tSaidax = $linha->TSAIDAx;

			$mEntrada = $linha->manha_entrada;

			$mSaida = $linha->manha_saida;

			$tEntrada = $linha->tarde_entrada;

			$tSaida = $linha->tarde_saida;

			$hora1 =  dif_horario($mEntrada, $mSaida);

			//	$hora2 = mysql_result($executaR,$Linhapro,"hora2");
			$hora2 =  dif_horario($tEntrada, $tSaida);

			$ger = $linha->Ind_Ger;
		}
	}

	?>
		
		
			<td class="actions text-center">
				<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_salvarPonto('<?= $_idponto; ?>')"><i class="fa  fa-floppy-o"></i></a>
			<td height="23"><?= $dia_s_calendario = dia(date("w", mktime(0, 0, 0, $mes, $dia, $ano)));  ?></td>
			<td height="23" style="text-align: center;"><?PHP echo $seldia . ""; ?></td>
			<td>
				<div style="text-align: center;">					
					<input name="mEntrada<?=$_idponto;?>" type="time" id="mEntrada<?=$_idponto;?>" class="form-control input-sm" value="<?=$mEntradax;?>"  maxlength="10"/>
				</div>
			</td>
			<td>
				<div style="text-align: center;">
				
					<input name="mSaida<?=$_idponto;?>" type="time" id="mSaida<?=$_idponto;?>" class="form-control input-sm" value="<?=$mSaidax;?>"  maxlength="10"/>
				</div>
			</td>
			<td>
				<div style="text-align: center;">
					
					<input name="tEntrada<?=$_idponto;?>" type="time" id="tEntrada<?=$_idponto;?>" class="form-control input-sm" value="<?=$tEntradax;?>"  maxlength="10"/>
				</div>
			</td>

			<td>
				<div style="text-align: center;">
				
					<input name="tSaida<?=$_idponto;?>" type="time" id="tSaida<?=$_idponto;?>" class="form-control input-sm" value="<?=$tSaidax;?>"  maxlength="10"/>
				</div>
			</td>
			<td><input name="motivo<?=$_idponto;?>" type="text" id="motivo<?=$_idponto;?>" class="form-control input-sm" value="<?=$motivo;?>"  style="width: 120px;" maxlength="250"/></td>
			<td style="text-align: center;">-</td>
			

		
	<?php


}


if ($_acao == 55) { //salvar folha ponto

	$id = $_parametros['_idtec'];
	$seldia = substr($_parametros['_idsel'],-2);
	$selmes =   $_parametros['mes'];
	$selano =   $_parametros['ano'];
	$dataconsultar = $selano."-" .$selmes."-".$seldia;
	$_idponto = $seldia;
	
	$mentrada = $_parametros['_mA'] != "" ? $_parametros['_mA'] : '00:00:00';
	$msaida = $_parametros['_mB'] != "" ? $_parametros['_mB'] : '00:00:00';
	$tentrada = $_parametros['_mC'] != "" ? $_parametros['_mC'] : '00:00:00';
	$tsaida = $_parametros['_mD'] != "" ? $_parametros['_mD'] : '00:00:00';



	$mentrada = $dataconsultar." ".$mentrada;
	$msaida = $dataconsultar." ".$msaida;
	$tentrada = $dataconsultar." ".$tentrada;
	$tsaida = $dataconsultar." ".$tsaida;
	$motivo = $_parametros['motivo'.$seldia];

	$sql = "Select  reg_id  	from " . $_SESSION['BASE'] . ".registroponto						
	where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		$sql = "UPDATE  " . $_SESSION['BASE'] . ".registroponto	SET
		Hora_E_Manha = '$mentrada', Hora_S_Manha = '$msaida', Hora_E_Tarde = '$tentrada',Hora_S_Tarde = '$tsaida',Motivo = '$motivo'																
				where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";					
		$stm = $pdo->prepare("$sql");
		$stm->execute();

	}else{
		$sql = "INSERT INTO  " . $_SESSION['BASE'] . ".registroponto	(Data_Movto,CODIGO_TECNICO,
		Hora_E_Manha,Hora_S_Manha, Hora_E_Tarde ,Hora_S_Tarde,Motivo)
				VALUES ('$dataconsultar','$id','$mentrada','$msaida','$tentrada','$tsaida','$motivo')";					
		$stm = $pdo->prepare("$sql");
		$stm->execute();
			
	}
	

	$sql = "Select  reg_id,Motivo,Ind_Ger,A_GPS,B_GPS,C_GPS,D_GPS,
	Hora_E_Manha,Hora_S_Manha,Hora_E_Tarde,Hora_S_Tarde,
						Hora_E_Manha as MENTRADA,date_format(Hora_E_Manha,'%H:%i') as manha_entrada,
						Hora_S_Manha as MSAIDA,date_format(Hora_S_Manha,'%H:%i') as manha_saida,
						Hora_E_Tarde as TENTRADA,date_format(Hora_E_Tarde,'%H:%i') as tarde_entrada,
						Hora_S_Tarde as TSAIDA,date_format(Hora_S_Tarde,'%H:%i') as tarde_saida,
						DATE_FORMAT(Hora_E_Manha,'%H:%i') as MENTRADAx,DATE_FORMAT(Hora_S_Manha,'%H:%i') as MSAIDAx,DATE_FORMAT(Hora_E_Tarde,'%H:%i') as TENTRADAx,
						DATE_FORMAT(Hora_S_Tarde,'%H:%i') as TSAIDAx,  SUBTIME(DATE_FORMAT(Hora_S_Manha,'%H:%i'),DATE_FORMAT(Hora_E_Manha,'%H:%i')) as hora1,
						SUBTIME(DATE_FORMAT(`Hora_S_Tarde`,'%H:%i'),DATE_FORMAT(`Hora_E_Tarde`,'%H:%i')) as hora2,
						DATE_FORMAT(hora_intervaloBfim,'%H:%i') as INTENTRADA,DATE_FORMAT(hora_intervaloBini,'%H:%i') as INTSAIDA 
						from " . $_SESSION['BASE'] . ".registroponto						
						where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";
    
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			

			$motivo = $linha->Motivo;

			$mEntradax = $linha->MENTRADAx;

			$mSaidax = $linha->MSAIDAx;

			$tEntradax = $linha->TENTRADAx;

			$tSaidax = $linha->TSAIDAx;

			$mEntrada = $linha->manha_entrada;

			$mSaida = $linha->manha_saida;

			$tEntrada = $linha->tarde_entrada;

			$tSaida = $linha->tarde_saida;

			$hora1 =  dif_horario($mEntrada, $mSaida);

			//	$hora2 = mysql_result($executaR,$Linhapro,"hora2");
			$hora2 =  dif_horario($tEntrada, $tSaida);

			$ger = $linha->Ind_Ger;
		}
	}


	?>
		
			<td class="actions text-center">
				<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_editarPonto('_linha<?=$_idponto;?>')"><i class="fa  fa-pencil"></i></a>
			<td height="23"><?= $dia_s_calendario = dia(date("w", mktime(0, 0, 0, $mes, $dia, $ano)));  ?></td>
			<td height="23" style="text-align: center;"><?PHP echo $seldia . ""; ?></td>
			<td>
				<div style="text-align: center;">					
				<?=$mEntradax;?> 
				</div>
			</td>
			<td>
				<div style="text-align: center;">
				
					<?=$mSaidax;?>
				</div>
			</td>
			<td>
				<div style="text-align: center;">
					
					<?=$tEntradax;?>
				</div>
			</td>

			<td>
				<div style="text-align: center;">
				
					<?=$tSaidax;?>
				</div>
			</td>
			<td><?= $motivo; ?></td>
			<td style="text-align: center;">
			<?php


				$t =  soma_horas("$hora1", "$hora2");
				echo $t;
				$M = substr($t, -2);

				$mi = $mi + $M;
				//echo $mi;

				if($hora1 != "" and $hora2 != "") {																		
					$horag = $horag + soma_horas("$hora1", "$hora2");
				}


				$hora1 = "";
				$t  = "";
				$M = 0;
				$hora2 = ""
				?></td>
			  
		
	<?php


}

if ($_acao == 6) { //registrar ponto 
	//Verificar codigo acesso

	$codigo_p  =  strip_tags(trim($_parametros["codigo_p"]));
	$senha =  strip_tags(trim($_parametros["password"]));
	$login =  strip_tags(trim($_parametros["login"]));
	$_lat =  strip_tags(trim($_parametros["_lat"]));
	$_long =  strip_tags(trim($_parametros["_long"]));
	$_GPS = $_lat."|".$_long;
	  //buscar dados da base informatica
	  $consulta = "Select consumidor_base,Nome_Fantasia from info.consumidor where CODIGO_CONSUMIDOR = '$codigo_p'"; 
	  $stm = $pdo->prepare("$consulta");
	  $stm->execute();
	  if ($stm->rowCount() > 0) {
		  while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		  {
			$codusuario =  $linha->rst_sel['usuario_CODIGOUSUARIO'];
			$_SESSION['CODIGOCLI']  = $codigo_p;
			$_SESSION['BASE'] =  $linha->consumidor_base;
			$_SESSION['fantasia'] =  $linha->Nome_Fantasia;
		  }
	  }

	  $msg = "";
	  if (trim($login == "")) {
		$message =  '<b style="color:#E77171">Usuário não pode ser vazio!</b>';
	 } else if (trim($senha == "")) {
		$message =  '<b style="color:#E77171">Senha não pode ser vazio!</b>';
	 } else {//1
		$senha = md5($senha);
	 }
	 

	  $sql_sel = "SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_avatar,usuario_background FROM ".$_SESSION['BASE'].".usuario WHERE usuario_LOGIN = '$login' and usuario_SENHA = '$senha' and usuario_ATIVO = 'Sim' ";	
			
	  $stm = $pdo->prepare("$sql_sel");
	  $stm->execute();
	  if ($stm->rowCount() > 0) {
		  while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		  {
			$_SESSION["tecnico"] =  $linha->usuario_CODIGOUSUARIO;
			$nome =  $linha->usuario_APELIDO;
			$avatar =  $linha->usuario_avatar;
		  }
	  }else{ 
		$_msg = "OPS!... <br>Login não encontrado";
		?>
			<form class="form-horizontal m-t-20" id="form2" name="form2" method="post" action="">
                       
					   <input type="hidden" name="dthoracpt" id="dthoracpt" value="<?=$datahoracpt;?>"/> 
					   <input type="hidden" id="_keyform" name="_keyform"  value="">
					   <div class="card-box text-center">
							<img src="v1/assets/images/small/opsnaoencontrado.jpg" alt="image" class="img-responsive center-block" width="200"/>
							<h3><?=$_msg;?></h3>
								
							</div>
			</form>
		<?php

		exit();
	  }

	


	$sql = "Select NOME_FANTASIA from " . $_SESSION['BASE'] . ".parametro LIMIT 1 ";	
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$parametro = $linha->NOME_FANTASIA;
		}
	}

	$lSQL = "Select Data_Movto,Hora_E_Manha,Hora_S_Manha,Hora_E_Tarde,Hora_S_Tarde,hora_intervaloAini,hora_intervaloAfim,hora_intervaloBini,hora_intervaloBfim 
	from " . $_SESSION['BASE'] . ".registroponto
  where CODIGO_TECNICO = '" .$_SESSION["tecnico"]. "' and Data_Movto = '" .$data."'";

  $stm = $pdo->prepare("$lSQL");
  $stm->execute();
  if ($stm->rowCount() > 0) {
	  while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
	  {
		if ($linha->Hora_E_Manha != "") {  $mentrada = $linha->Hora_E_Manha; };
		if ($linha->Hora_S_Manha != "") {  $msaida = $linha->Hora_S_Manha; };
		if ($linha->Hora_E_Tarde != "") {  $tentrada = $linha->Hora_E_Tarde; };
		if ($linha->Hora_S_Tarde != "") {  $tsaida = $linha->Hora_S_Tarde; };
	
	}
}
	
	?>
	  <form class="form-horizontal m-t-20" id="form2" name="form2" method="post" action="">
                       
                        <input type="hidden" name="dthoracpt" id="dthoracpt" value="<?=$datahoracpt;?>"/> 
                        <input type="hidden" id="_keyform" name="_keyform"  value="">
						<input type="hidden" id="_gps" name="_gps" value="<?=$_GPS;?>">
                      
		<div class="row">
			<div class="col-sm-2 col-xs-4 text-center">
				<div class="contact-card">
					<?php if($avatar != "") {  ?>
						<img class="img-circle" src="v1/assets/images/avatar/<?=$avatar;?>" alt="">
					 <?php }else{ ?>
						<img class="img-circle" src="v1/assets/images/avatar/0000.png" alt="">
					<?php } ?>
				
				</div>
			</div>
			<div class="col-sm-10 col-xs-8 text-left">
				<div class="member-info">
					
					<p class="text-muted"></p>
					<h4 class="m-t-0 m-b-5 header-title"><b><?=$nome;?></b></h4>
					<p class="text-dark"><i class="md md-business m-r-10"></i><small><?=$_SESSION['fantasia'];?></small></p>
				</div>
			</div>
		</div>
		<?php
			if($tsaida <> "$dtpesquisa 00:00:00" And $tsaida <> "" and $tsaida <> "0000-00-00 00:00:00" ) {  
				?>
					<div class="card-box text-center"  >
					<img src="v1/assets/images/small/Checklist-pana.png" alt="image" class="img-responsive center-block" width="200"/>
					<h1>Registro Ponto Concluído</h1>
						
					</div>
				
					<?php 
			}else {  ?>

			
		<div class="row">
			<div class="col-sm-12 col-xs-12 text-center" id="retconf">

				<button type="button" class="btn btn-block btn-lg btn-info waves-effect waves-light"  onclick="_regPontopoint()"> <i class="ti-time fa-2x" style="color: #505458;"></i>
					<h1>bater ponto</h1>
					<h1 id="rethora"><?=$datahora ;?></h1>
				
				</button>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-6 col-xs-6 text-center" style="margin-top:10px ;">
				<button type="button" class="btn btn-block btn-lg  waves-effect waves-light" onclick="_regPontoAtualiza()">
					<h4>sincronizar hora</h4>
				</button>
			</div>
			<div class="col-sm-6 col-xs-6 text-center" style="margin-top:10px ;">
				<button type="button" class="btn btn-block btn-lg  waves-effect waves-light" onclick="_regviz()">
					<h4>ponto batidos</h4>
				</button>
			</div>
		</div>
	  </form>
	<?php

}

if ($_acao == 7) { //sicronizar ponto 
	echo $datahora ;

}


if ($_acao == 8) { //registrar ponto ]

	$msgregistroponto = "";	
	$_GPS =  strip_tags(trim($_parametros["_gps"]));
	$_GPSLINK = explode("|",$_GPS);


	$dthoracpt = strip_tags(trim(base64_decode($_parametros["dthoracpt"])));
	$dtpesquisa = substr($dthoracpt,0,10);
	$dtreg = explode('-',$dtpesquisa ) ;
	$msg = "";
	$mentrada = "";
	$msaida = "";
	$tentrada = "";
	$tsaida = "";
	$m_inicio = "";
	$m_fim  = "";
	$t_inicio = "";
	$t_fim  = "";

	if($msg != ""){ ?>
			<button type="button" class="btn btn-block btn-lg btn-info waves-effect waves-light" onclick="_regPontopoint()"> <i class="ti-time fa-2x" style="color: #505458;" ></i>
					<h1>bater ponto</h1>
					<h1 id="rethora"><?=$datahora ;?></h1>				
				</button>
				<div class="alert alert-danger alert-dismissable"><?=$msg;?></div>
	<?php } else { 

		//tudo certo gravar registro

		$lSQL = "Select Data_Movto,Hora_E_Manha,Hora_S_Manha,Hora_E_Tarde,Hora_S_Tarde,hora_intervaloAini,hora_intervaloAfim,hora_intervaloBini,hora_intervaloBfim 
		from " . $_SESSION['BASE'] . ".registroponto
	  where CODIGO_TECNICO = '" .$_SESSION["tecnico"]. "' and Data_Movto = '" .$dtpesquisa."'";
	
	  $stm = $pdo->prepare("$lSQL");
	  $stm->execute();
	  if ($stm->rowCount() > 0) {
		  while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		  {
			if ($linha->Hora_E_Manha != "") {  $mentrada = $linha->Hora_E_Manha; };
			if ($linha->Hora_S_Manha != "") {  $msaida = $linha->Hora_S_Manha; };
			if ($linha->Hora_E_Tarde != "") {  $tentrada = $linha->Hora_E_Tarde; };
			if ($linha->Hora_S_Tarde != "") {  $tsaida = $linha->Hora_S_Tarde; };

			if($mentrada == '0000-00-00 00:00:00') { $mentrada = "";}
			if($msaida == '0000-00-00 00:00:00') { $tsaida = "";}
			if($tentrada == '0000-00-00 00:00:00') { $tentrada = "";}
			if($tsaida == '0000-00-00 00:00:00') { $tsaida = "";}
		
		}
	}

			if($tsaida <> "$dtpesquisa 00:00:00" And $tsaida <> "" ) { 

				$_msgconf = ("Já efetuado todos registros");
				$icone = 'Checklist-pana.png';
			}else{
				
				try{	
					
					if ($mentrada == "" Or $mentrada == "$dtpesquisa 00:00:00"  ) { 
						$lSQL = "INSERT into " . $_SESSION['BASE'] . ".registroponto( CODIGO_TECNICO ,Data_Movto, Hora_E_Manha,A_GPS) values ( '" .$_SESSION["tecnico"]. "','" . $data . "','" . $dthoracpt . "','".$_GPS." ')";
						$_msgconf = "Entrada Registrada";
						$icone = 'confperfeito.jpg';
						

					}elseif ($msaida == "" Or $msaida == "$dtpesquisa 00:00:00"   ) {
						$lSQL = "UPDATE " . $_SESSION['BASE'] . ".registroponto SET Hora_S_Manha =  '". $dthoracpt."' ,B_GPS = '".$_GPS."' where CODIGO_TECNICO = '"  .$_SESSION["tecnico"]. "' and Data_Movto = '". $data . "' ";
						$_msgconf = "Saída Registrada";
						
						$icone = 'confok.jpg';

					}elseif ($tentrada == "" Or $tentrada == "$dtpesquisa 00:00:00"  ) {
						$lSQL = "UPDATE  " . $_SESSION['BASE'] . ".registroponto SET Hora_E_Tarde =  '". $dthoracpt."' ,C_GPS = '".$_GPS."' where CODIGO_TECNICO = '"  .$_SESSION["tecnico"]. "' and Data_Movto = '". $data . "' ";
						$_msgconf = "Entrada Registrada";
						$icone = 'confperfeito.jpg';

					}elseif ($tsaida == "" Or $tsaida == "$dtpesquisa 00:00:00"  ) {
						$lSQL = "UPDATE  " . $_SESSION['BASE'] . ".registroponto SET Hora_S_Tarde =  '". $dthoracpt."' ,D_GPS = '".$_GPS."' where CODIGO_TECNICO = '"  .$_SESSION["tecnico"]. "' and Data_Movto = '". $data . "' ";
						$_msgconf = "Saída Registrada";
						$icone = 'confok.jpg';
						
						
						
					

					}
               

					$stm = $pdo->prepare("$lSQL");								
				    $stm->execute();	

					$msgregistroponto = 'Registro efetuado com sucesso as '.$dtreg[2]."/".$dtreg[1]."/".$dtreg[0].' de '.$dtpesquisa.' | GPS: ='.$_GPSLINK[0].' ,'.$_GPSLINK[1].'';
			
					?>
					<div class="card-box">
					<img src="v1/assets/images/small/<?=$icone;?>" alt="image" class="img-responsive center-block" width="200"/>
					<h1><?=$_msgconf;?></h1>
						
						<input type="hidden" id="msgregistroponto" name="msgregistroponto" value="<?=$msgregistroponto;?>">
					</div>
				
					<?php 


					$lSQL = "UPDATE  " . $_SESSION['BASE'] . ".usuario SET usuario_ultatualizacaoponto =  '". $dthoracpt."'  where usuario_CODIGOUSUARIO = '"  .$_SESSION["tecnico"]. "'  ";
						
					$stm = $pdo->prepare("$lSQL");								
				    $stm->execute();	
				  
			
				}
				catch (\Exception $fault){
					$response = $fault;
				
				}
			}
		 
		
		
	}

}


if ($_acao == 9) { //carregar folha ponto mes


	$id = $_SESSION["tecnico"];


	$mes = date('m');

	$ano  = date('Y');

	$sql = "Select usuario_CODIGOUSUARIO,usuario_NOME from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '$id'";
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$_nome = $linha->usuario_NOME;
		}
	}

	?>
 <div class="table-responsive">
		<table class="table  table-striped table-bordered  m-b-0">
			<thead>
				<tr>

				

					<th>DIA</th>
					<th><strong>ENTR.</strong></th>
					<th><strong>SAÍDA</strong></th>
					<th><strong>ENTRA.</strong></th>
					<th><strong>SAÍDA</strong></th>					
					<th>TOTAL HORAS</th>
					<th>MOTIVO</th>
				</tr>
				</tr>
			</thead>
			<tbody>

				<?php

				$dia = date("1");

				if ($mes == "01") {
					$diap = 31;
				} //janeiro tem 31 dias
				// Fevereiro tem 28 (ou 29, nos anos bissextos)						 
				if ($mes == "02") {
					$diap = 29;
				} //janeiro tem 31 dias						 
				if ($mes == "03") {
					$diap = 31;
				}  //Março tem 31						 
				if ($mes == "04") {
					$diap = 30;
				}  // Abril tem 30						 
				if ($mes == "05") {
					$diap = 31;
				}  //Maio tem 31						 
				if ($mes == "06") {
					$diap = 30;
				}  // Junho tem 30						 
				if ($mes == "07") {
					$diap = 31;
				}  // Julho tem 31						 
				if ($mes == "08") {
					$diap = 31;
				}  // Agosto tem 31						 
				if ($mes == "09") {
					$diap = 30;
				}  // Setembro tem 30						 
				if ($mes == "10") {
					$diap = 31;
				}  // Outubro tem 31						 
				if ($mes == "11") {
					$diap = 30;
				}  // Novembro tem 30						 
				if ($mes == "12") {
					$diap = 31;
				}  // Dezembro tem 31


				while ($diap >= $dia) {

					$dia_calendario = date("d", mktime(0, 0, 0, $mes, $dia, $ano));
					$dataconsultar = "$ano" . "-" . "$mes" . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
					$dia = $dia + 1;
					$sql = "Select *,
											Hora_E_Manha as MENTRADA,date_format(Hora_E_Manha,'%H:%i') as manha_entrada,
											Hora_S_Manha as MSAIDA,date_format(Hora_S_Manha,'%H:%i') as manha_saida,
											Hora_E_Tarde as TENTRADA,date_format(Hora_E_Tarde,'%H:%i') as tarde_entrada,
											Hora_S_Tarde as TSAIDA,date_format(Hora_S_Tarde,'%H:%i') as tarde_saida,
											DATE_FORMAT(Hora_E_Manha,'%H:%i') as MENTRADAx,DATE_FORMAT(Hora_S_Manha,'%H:%i') as MSAIDAx,DATE_FORMAT(Hora_E_Tarde,'%H:%i') as TENTRADAx,
											DATE_FORMAT(Hora_S_Tarde,'%H:%i') as TSAIDAx,  SUBTIME(DATE_FORMAT(Hora_S_Manha,'%H:%i'),DATE_FORMAT(Hora_E_Manha,'%H:%i')) as hora1,
											 SUBTIME(DATE_FORMAT(`Hora_S_Tarde`,'%H:%i'),DATE_FORMAT(`Hora_E_Tarde`,'%H:%i')) as hora2,
											 DATE_FORMAT(hora_intervaloBfim,'%H:%i') as INTENTRADA,DATE_FORMAT(hora_intervaloBini,'%H:%i') as INTSAIDA 
											 from " . $_SESSION['BASE'] . ".registroponto where CODIGO_TECNICO = '$id' and Data_Movto = '$dataconsultar'";

					$stm = $pdo->prepare("$sql");
					$stm->execute();
					if ($stm->rowCount() > 0) {
						while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
						{

						
							$motivo = $linha->Motivo;

							$mEntradax = $linha->MENTRADAx;

							$mSaidax = $linha->MSAIDAx;

							$tEntradax = $linha->TENTRADAx;

							$tSaidax = $linha->TSAIDAx;

							$mEntrada = $linha->manha_entrada;

							$mSaida = $linha->manha_saida;

							$tEntrada = $linha->tarde_entrada;

							$tSaida = $linha->tarde_saida;

							$hora1 =  dif_horario($mEntrada, $mSaida);

							//	$hora2 = mysql_result($executaR,$Linhapro,"hora2");
							$hora2 =  dif_horario($tEntrada, $tSaida);

							$ger = $linha->Ind_Ger;
						}
					}

				?>
					<tr id="_linha">
						
						
						<td height="23" style="text-align: center;"><?PHP echo $dia_s_calendario . "  " . $dia_calendario . "<br>"; ?></td>
						<td>
							<div style="text-align: center;">
								<?= $mEntradax; ?>
							</div>
						</td>
						<td>
							<div style="text-align: center;">
								<?= $mSaidax; ?>
							</div>
						</td>
						<td>
							<div style="text-align: center;">
								<?= $tEntradax; ?>
							</div>
						</td>

						<td>
							<div style="text-align: center;">
								<?= $tSaidax; ?>
							</div>
						</td>
						
						<td style="text-align: center;"><?php


														$t =  soma_horas("$hora1", "$hora2");
														echo $t;
														$M = substr($t, -2);

														$mi = $mi + $M;
														//echo $mi;

														
														if($hora1 != "" and $hora2 != "") {																		
															$horag = $horag + soma_horas("$hora1", "$hora2");
														}

														$hora1 = "";
														$t  = "";
														$M = 0;
														$hora2 = ""
														?></td>
														<td><?= $motivo; ?></td>
						<?php
						$mEntradax = "";
						$mSaidax =  "";
						$tEntradax = "";
						$tSaidax = "";
						$motivo = "";
						$mSaida = "";
						$tEntrada = "";
						$tSaida = "";
						$INTsaida = "";
						$INTEntrada = "";
						$dia_semana--;
						$cont++;
						?>

					</tr>
				<?php

				} //fim while dias

				?>
			</tbody>
		</table>
 </div>
	<?php
	exit();
}


