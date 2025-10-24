<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;


$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];; //codigo login

$usuariologado =  $_SESSION["APELIDO"]; //nome

		

$dtaberturaSelAgenda = $_parametros['dtaberturaSelAgenda'];

if($_acao == 1 ) {   //tranferir roteiro

	$assessorDE =    $_parametros['tecnicoDE'];
	$assessorPARA =    $_parametros['tecnicoPARA'];

	if($assessorDE ==  $assessorPARA ) { 
		
		$_msg = "Assessor não pode ser igual  para transferência!!!";
	
	?>
	<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
		<?=$_msg;?>
	</div>
<?php
	exit();

}

	if($assessorDE == 0 OR $assessorPARA == 0) { 
		
			$_msg = "Selecione corretamente Assessores !!!";
		
		?>
		<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
			<?=$_msg;?>
		</div>
	<?php
		exit();

	}

		//verificar data selecionada
		$data_inicial = date("Y")."-".date("m")."-".date("d");
		$data_final = $_parametros['dtaberturaSelAgenda'] ;
		$diferenca = strtotime($data_final) - strtotime($data_inicial);
		$dias = floor($diferenca / (60 * 60 * 24));
	
		if($dias < 0) {
			?>
			<div  style="text-align: center ;">
						<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
								<h4 ><span ><?=$dias;?>Data agendamento não pode ser inferior a hoje !!! <strong>  </h4>							
					</div> 
			</div>
		<?php

		exit();
		}
	  
		$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM  ". $_SESSION['BASE'] .".usuario  where  usuario_CODIGOUSUARIO = '$assessorPARA' ");
		$stm = $pdo->prepare("$query");                   
		$stm->execute();	
		$result = $stm->fetch(PDO::FETCH_OBJ);

		$_nomePARA		= $result->usuario_APELIDO;
		

	$sql = "Select CODIGO_CHAMADA,SituacaoOS_Elx,CODIGO_CONSUMIDOR,A.usuario_APELIDO  as tecnico FROM ". $_SESSION['BASE'] .".trackOrdem 
			left join ". $_SESSION['BASE'] .".chamada on CODIGO_CHAMADA = trackO_chamada	
			left join ". $_SESSION['BASE'] .".usuario as A on trackO_tecnico = usuario_CODIGOUSUARIO			
			where trackO_data = '".$_parametros['dtaberturaSelAgenda']."'  and  ISNULL(DATA_ENCERRAMENTO) AND  Cod_Tecnico_Execucao = '$assessorDE'   or 
			trackO_data = '".$_parametros['dtaberturaSelAgenda']."'  and  DATA_ENCERRAMENTO = '0000-00-00' and Cod_Tecnico_Execucao = '$assessorDE'";
		
	$stm = $pdo->prepare("$sql");                   
	$stm->execute();	            

	if ( $stm->rowCount() > 0 ){  
		$result = $stm->fetchAll(PDO::FETCH_OBJ);
				foreach ($result as $row) {
					$_nomeDE = $row->tecnico;
					$_sit = $row->SituacaoOS_Elx;
					$_dtprevisto = explode("-",$_parametros['dtaberturaSelAgenda']);
					$_dtprevisto = $_dtprevisto[2]."/".$_dtprevisto["1"]."/".$_dtprevisto["0"];

					$update = "UPDATE  ". $_SESSION['BASE'] .".trackOrdem  SET trackO_tecnico = '".$assessorPARA."', trackO_ordem = 0,trackO_ordemtemp = 0,
							  trackO_periodo = 0,trackO_periodotemp = 0, trackO_salvar = 0 , trackO_situacaoEncerrado = 0 WHERE  datahora_trackini = '0-0-0' 
							  and trackO_tecnico = '".$assessorDE."' and trackO_data = '".$dtaberturaSelAgenda."' and trackO_chamada = '".$row->CODIGO_CHAMADA."' ";   
					$stm = $pdo->prepare("$update");                   
					$stm->execute();	
					
					$consultaMov = "UPDATE ". $_SESSION['BASE'] .".chamada SET Cod_Tecnico_Execucao = '$assessorPARA',
									DATA_ATEND_PREVISTO  = '".$_parametros['dtaberturaSelAgenda']."'									
									WHERE CODIGO_CHAMADA = '".$row->CODIGO_CHAMADA."'  ";							
									$stm = $pdo->prepare("$consultaMov");                   
									$stm->execute();			
					
					$Rdescricao = "<strong>ACOMPANHAMENTO AUTOMÁTICO:TRANSFERÊNCIA ROTEIRO</strong> - DATA DE ATENDIMENTO ATUALIZADA PARA DIA (<strong>$_dtprevisto</strong>) COM TÉCNICO (<strong>$_nomePARA</strong>)";
					$consulta = "insert into ". $_SESSION['BASE'] .".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
							(CURRENT_DATE(),'$data','".$row->CODIGO_CHAMADA."','$usuario','$usuariologado','$row->CODIGO_CONSUMIDOR','$Rdescricao','$_sit' )";
							$stm = $pdo->prepare("$consulta");                   
							$stm->execute();	
					$retatualizado = 1;

				if($retatualizado == 1) {
					//insert ALTERACAO acompanhamento					?>
					
					<div  style="text-align: center ;">
					<div class="alert alert-success alert-dismissable " style="margin-top: 5px;">	
								
										<h4 ><span >Atualizado  de <?=$_nomeDE;?> para <?=$_nomePARA;?>!!! <strong>  </h4>								
				</div> 
		</div>
					<?php
				}
			}
						
		
	
	}else{
		?>
			
				<div  style="text-align: center ;">
						<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
									<h4 ><span >Nenhuma O.S a transferir  <?=$_nomeDE;?> !!! <strong>  </h4>								
					</div> 
				</div>
			<?php
	}


}

if($_acao == 2 ) {   //UPDATE roteiro

	$_ostecnico = trim($_parametros['_ostecnico']);
	$tecnicoOS = $_parametros['tecnicoOS'];

	if(trim($_ostecnico) ==  "" ) { 
		
		$_msg = "Informe Nº de O.S !!!";
	
		?>
		<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
			<?=$_msg;?>
		</div>
	<?php
		exit();

	}
		

	if($tecnicoOS == 0 ) { 
		
			$_msg = "Selecione corretamente Assessor !!!";
		
		?>
		<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
			<?=$_msg;?>
		</div>
	<?php
		exit();

	}

		//verificar data selecionada
		$data_inicial = date("Y")."-".date("m")."-".date("d");
		$data_final = $_parametros['dtaberturaSelAgenda'] ;
		$diferenca = strtotime($data_final) - strtotime($data_inicial);
		$dias = floor($diferenca / (60 * 60 * 24));
	
		if($dias < 0) {
			?>
		
			<div  style="text-align: center ;">
					<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
								<h4 ><span >Data agendamento não pode ser inferior a hoje !!! <strong>  </h4>								
				</div> 
			</div>
		<?php

		exit();
		}
	  
		
		
		$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM  ". $_SESSION['BASE'] .".usuario  where  usuario_CODIGOUSUARIO = '$tecnicoOS' ");
		$stm = $pdo->prepare("$query");                   
		$stm->execute();	
		$result = $stm->fetch(PDO::FETCH_OBJ);

		$_nome		= $result->usuario_APELIDO;

		$sql = "Select CODIGO_CHAMADA,SituacaoOS_Elx,CODIGO_CONSUMIDOR,A.usuario_APELIDO  as tecnico,Cod_Tecnico_Execucao FROM ". $_SESSION['BASE'] .".trackOrdem 
		left join ". $_SESSION['BASE'] .".chamada on CODIGO_CHAMADA = trackO_chamada	
		left join ". $_SESSION['BASE'] .".usuario as A on trackO_tecnico = usuario_CODIGOUSUARIO			
		where trackO_data = '".$_parametros['dtaberturaSelAgenda']."'  and  ISNULL(DATA_ENCERRAMENTO)   and CODIGO_CHAMADA = '".$_ostecnico."' or 
		trackO_data = '".$_parametros['dtaberturaSelAgenda']."'  and  DATA_ENCERRAMENTO = '0000-00-00'and CODIGO_CHAMADA = '".$_ostecnico."'";	
		$stm = $pdo->prepare("$sql");                   
		$stm->execute();	            


		$result = $stm->fetchAll(PDO::FETCH_OBJ);
			foreach ($result as $row) {
				$_nomeDE = $row->tecnico;
				$_sit = $row->SituacaoOS_Elx;
				$_dtprevisto = explode("-",$_parametros['dtaberturaSelAgenda']);
				$_dtprevisto = $_dtprevisto[2]."/".$_dtprevisto["1"]."/".$_dtprevisto["0"];
				$_idcliente = $row->CODIGO_CONSUMIDOR;
				$_tec = $row->Cod_Tecnico_Execucao;
			}
			if($_idcliente == "") {
				?>
			
				<div  style="text-align: center ;">
						<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
									<h4 ><span >O.S não Encontrada !!! <strong>  </h4>								
					</div> 
				</div>
			<?php
	
			exit();
			}

			if($_tec == $tecnicoOS) {
				?>
			
				<div  style="text-align: center ;">
						<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
									<h4 ><span >O.S já esta com assessor  <?=$_nomeDE;?> !!! <strong>  </h4>								
					</div> 
				</div>
			<?php
	
			exit();
			}
		

				$update = "UPDATE  ". $_SESSION['BASE'] .".trackOrdem  SET trackO_tecnico = '".$tecnicoOS."', trackO_ordem = 0,trackO_ordemtemp = 0,
							  trackO_periodo = 0,trackO_periodotemp = 0, trackO_salvar = 0 , trackO_situacaoEncerrado = 0 WHERE  datahora_trackini = '0-0-0'  and trackO_data = '".$dtaberturaSelAgenda."' and trackO_chamada = '".$_ostecnico."' ";   			
				$retatualizado = 1;
				$stm = $pdo->prepare("$update");                   
				$stm->execute();	

						
				$consultaMov = "UPDATE ". $_SESSION['BASE'] .".chamada SET Cod_Tecnico_Execucao = '$tecnicoOS',
				DATA_ATEND_PREVISTO  = '".$_parametros['dtaberturaSelAgenda']."'									
				WHERE CODIGO_CHAMADA = '".$_ostecnico."'  ";							
				$stm = $pdo->prepare("$consultaMov");                   
				$stm->execute();			

				$Rdescricao = "<strong>ACOMPANHAMENTO AUTOMÁTICO:TRANSFERÊNCIA ROTEIRO</strong> - DATA DE ATENDIMENTO ATUALIZADA PARA DIA (<strong>$_dtprevisto</strong>) COM TÉCNICO (<strong>$_nome</strong>)";
				$consulta = "insert into ". $_SESSION['BASE'] .".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
						(CURRENT_DATE(),'$data','".$_ostecnico."','$usuario','$usuariologado','$_idcliente','$Rdescricao','$_sit' )";
						$stm = $pdo->prepare("$consulta");                   
						$stm->execute();	

			//	if($retatualizado == 1) {
					//insert ALTERACAO acompanhamento
					?>
				
					<div  style="text-align: center ;">
					<div class="alert alert-success alert-dismissable " style="margin-top: 5px;">
								
								
					<h4 ><span >Atualizado <?=$_ostecnico;?> para <?=$_nome;?> !!! <strong>  </h4>								
				</div> 
			</div>
					<?php
			//	}
}



?> 