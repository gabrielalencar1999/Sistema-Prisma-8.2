<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


date_default_timezone_set('America/Sao_Paulo');

use Database\MySQL;

$pdo = MySQL::acessabd();


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];; //codigo login


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data= $ano . "-" . $mes . "-" . $dia . " " . $hora;
$_MOB = 0;

if(trim($_parametros['_idos']) != "") {
	$_NUMOS = $_parametros['_idos'];
	$_MOB = 1;
}else{
	$_NUMOS = $_parametros['_osmed'];
}


if($_acao == 1 ) {   //gravar medição

//print_r($_parametros);
//deletar 

$_sql = "DELETE FROM ". $_SESSION['BASE'] .".chamada_medicao WHERE chmed_numeroos = '".$_NUMOS."'";

$stm = $pdo->prepare("$_sql");                   
$stm->execute();	


foreach ($_parametros as $chave => $valor) {
   // echo "Chave: $chave,  Valor: {$_parametros[$chave]}";
	$_id = explode("_",$chave);
	$_valor = trim($_parametros[$chave]);
	//echo $_id[1]."-".$_valor."<Br>";

	//inserir
	try{	
		if($_valor != "" and $_id[1] > 0)  {
			$stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".chamada_medicao (
				chmed_datahora,
				chmed_idmed,
				chmed_numeroos,
				chmed_resposta,			
				chmed_usuario) 
					VALUES (
					?,
					?,
					?,					
					?,				
					?
					); ");		
				$stm->bindParam(1, $data);	
				$stm->bindParam(2, $_id[1]);				
				$stm->bindParam(3, $_NUMOS);								
				$stm->bindParam(4, $_valor);		
				$stm->bindParam(5, $usuario);					
				$stm->execute();			
			}
		
		}
		catch (\Exception $fault){
			$response = $fault;
			echo '<div class="alert alert-warning alert-dismissable " style="text-align: center;margin-top:5px"> OPS !!! algo deu errado avise o suporte</div>';
		
		}
		
	
		}
		echo '<div class="alert alert-success alert-dismissable " style="text-align: center;;margin-top:5px">Dados Atualizados</div>';
	
		
}

if($_acao == 99 ) {   //MEDIÇÃO

	if($_parametros["indaparelho"] > 0) { ?>
		<button type="button"   class="btn btn-warning waves-effect waves-light btn-block" onclick="_medicao()" data-toggle="modal" data-target="#custom-modal-medicao" ><i class="fa fa-area-chart "></i></button>
		<?php
	}else{	
		if($_MOB == 0) {		
		?>
		 <i class="glyphicon glyphicon-ban-circle fa-2x" > </i>		
		<?php
		}
	}
		
}

if($_acao == 9 ) {   //MEDIÇÃO

	$sql = "SELECT chmed_idmed,chmed_resposta FROM ". $_SESSION['BASE'] .".chamada_medicao WHERE chmed_numeroos = '".$_NUMOS."'";		
    $consultabk = $pdo->query($sql);	
	$retornobk = $consultabk->fetchAll();

    $totalbk = $consultabk->rowCount();

	$consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".medicao
		   where med_tipoaparelho = '".$_parametros["indaparelho"]."'  ");
		  $retorno = $consulta->fetchAll();
		  if($consulta->rowCount() == 0) {
			 ?>
				<div class="alert alert-success " style="margin-top: 5px;" >Não existe <strong>medições</strong> para Modelo do Produto </div>
			<?php 
			 }else{		
				echo '	<div class="row" >	';  
					foreach ($retorno as $row) {	
						
						
						//ver tipo de campo
						if($row['med_tipocampo'] == '3') { 
								$tipo = "select";
						}else{
							if ($row['med_tipocampo'] == '1') { 
								$tipo = "text";
							}else{
								$tipo = "number";
							}
						}

						$_opcao = explode(';',$row['med_opcao']);


						if($totalbk > 0 ) {
						
							foreach ($retornobk as $chave) {
								
								$_id = $chave['chmed_idmed'];							   

								if($row['med_id'] == $_id  ){
									$_valor = trim($chave['chmed_resposta']);
									
								}

							}

						}
						
					
						?>
					
									
											<div class="col-md-6" >
													<label class="control-label" ><?=$row["med_descricao"];?></label>
												
														<?php if($tipo == 'select') { ?>
															<select name="med_<?=$row["med_id"];?>" id="med_<?=$row["med_id"];?>" class="form-control  input-sm">
															<?php 
															foreach ($_opcao as $_op) {
																?>
																<option value="<?=$_op;?>" <?php if ($_op == "$_valor") { ?>selected="selected" <?php } ?>><?=$_op;?></option>
																<?php
																}
																?>
																
															</select>
														<?php }else { ?>
															<input name="med_<?=$row["med_id"];?>" type="<?=$tipo;?>"  id="med_<?=$row["med_id"];?>" value="<?=$_valor;?>"  class="form-control input-sm" />
														<?php } ?>
											</div>
										
											
										
						
						
						<?php
							$_valor = "";
					}
					?></div>
					<div class="row"  id="retorno_medicao">

					</div>
					<div class="row" style="text-align: center; margin:10px">
						<div class="btn-group pull-center">
													 <button type="button" class="btn btn-inverse waves-effect waves-light " data-dismiss="modal" ><span class="btn-label"><i class="fa fa-times"></i></span>Fechar</button>
               										 <button id="salvar" type="button" class="btn btn-success waves-effect waves-light mb-auto m-l-5" onclick="_gravarMed()"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>    
														</div>
											</div>
											</div>
					<?php
				}

}

//bloco para garantia estendida da O.s


if($_acao == 2 ) {   //PESQUISAR GARANTIA ESTENDIDA

	$sql = "SELECT * FROM ". $_SESSION['BASE'] .".chamada_garext WHERE cge_os = '".$_NUMOS."' limit 1";	
	$consulta = $pdo->query($sql);
	if($consulta->rowCount() == 0) {	
		  
			 ?>
			 <h4 class="modal-title">Garantia Estendida</h4>
				<div class="alert alert-success " style="margin-top: 5px; text-align:center" >Não existe <strong>Garantia  Estendida</strong> Informada <br>Preencha valores abaixo </div>
				<div class="card-box" style="background: url(assets/images/agsquare.png);">
					<div class="row" >						
						<div class="col-sm-2"  style="text-align: center;">                    
							<label>12 meses</label> 
							<input style="text-align: right;" type="text" class="form-control input-sm" name="_vlr12" id="_vlr12" placeholder="R$ 0,00" >
						</div>
						<div class="col-sm-2"  style="text-align: center;">                    
							<label >24 meses</label> 
							<input style="text-align: right;" type="text" class="form-control input-sm" name="_vlr24" id="_vlr24" placeholder="R$ 0,00" >
						</div>
						<div class="col-sm-2"  style="text-align: center;">                    
							<label >36 meses</label> 
							<input style="text-align: right;" type="text" class="form-control input-sm" name="_vlr36" id="_vlr36" placeholder="R$ 0,00" >
						</div>
						<div class="col-sm-3"  style="text-align: center;">                    
							<label >Opção Selecionada</label> 
								<select name="opc_garantia" id="opc_garantia" class="form-control input-sm" >
                                    <option value="0" selected="selected" >--</option>
                                    <option value="12">12</option>
									<option value="24">24</option>
									<option value="36" >36</option>
									<option value="-1">Não tem Interesse</option>
                                </select>
						</div>
						<div class="col-sm-2"  style="text-align: center;">                    
							
						</div>
					</div>
				</div>
				
				</div>
			<?php 
			 }else{		
				$retorno = $consulta->fetchAll();
			
					foreach ($retorno as $row) {	
						
						?>
						<h4 class="modal-title">Garantia Estendida</h4>
						<div class="card-box" style="background: url(assets/images/agsquare.png);">
							<div class="row" >						
								<div class="col-sm-2"  style="text-align: center;">                    
									<label>12 meses</label> 
									<input style="text-align: right;" type="text" class="form-control input-sm" name="_vlr12" id="_vlr12" placeholder="R$ 0,00"  value="<?= number_format($row["cge_vlr12"], 2, ',', '.') ?>">
								</div>
								<div class="col-sm-2"  style="text-align: center;">                    
									<label >24 meses</label> 
									<input style="text-align: right;" type="text" class="form-control input-sm" name="_vlr24" id="_vlr24" placeholder="R$ 0,00"  value="<?= number_format($row["cge_vlr24"], 2, ',', '.') ?>">
								</div>
								<div class="col-sm-2"  style="text-align: center;">                    
									<label >36 meses</label> 
									<input style="text-align: right;" type="text" class="form-control input-sm" name="_vlr36" id="_vlr36" placeholder="R$ 0,00"   value="<?= number_format($row["cge_vlr36"], 2, ',', '.') ?>">
								</div>
								<div class="col-sm-3"  style="text-align: center;">                    
									<label >Opção Selecionada</label> 
										<select name="opc_garantia" id="opc_garantia" class="form-control input-sm" >
											<option value="0" <?php if ($row['cge_periodosel'] == 0) { ?>selected="selected" <?php } ?>>--</option>
											<option value="12" <?php if ($row['cge_periodosel'] == 12) { ?>selected="selected" <?php } ?>>12</option>
											<option value="24" <?php if ($row['cge_periodosel'] == 24) { ?>selected="selected" <?php } ?>>24</option>
											<option value="36" <?php if ($row['cge_periodosel'] == 36) { ?>selected="selected" <?php } ?>>36</option>
											<option value="-1" <?php if ($row['cge_periodosel'] == '-1') { ?>selected="selected" <?php } ?>>Não tem Interesse</option>
										</select>
								</div>
								<div class="col-sm-2"  style="text-align: center;">
									<?php if($row["cge_valrcontratado"] > 0){ ?>								
										<label >Valor</label> <br>
										R$ <?= number_format($row["cge_valrcontratado"], 2, ',', '.') ?>
									<?php } ?>	
								</div>
								<div class="col-sm-1"  style="text-align: center;"><br>
								<button type="button" class="btn btn-danger btn-sm   waves-effect waves-light " onclick="_excluirgarext()" ><i class="fa fa-trash"></i></button>
								</div>
							</div>
						</div>
						
						</div>
					<?php 
					}
					?></div>
					
					<?php
				}
				?>
					<div class="row"  id="retorno_medicao">
					</div>
					<div class="row" style="text-align: center; margin:10px">
						<div class="btn-group pull-center">
								<button type="button" class="btn btn-inverse waves-effect waves-light " data-dismiss="modal" ><span class="btn-label"><i class="fa fa-times"></i></span>Fechar</button>
               					<button id="salvar" type="button" class="btn btn-success waves-effect waves-light mb-auto m-l-5" onclick="_gravargarext()"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>    
						</div>
					</div>
				<?php
				exit();

}

if($_acao == 22 ) {   //gravar GARANTIA ESTENDIDA

		$dtconclusao = '0-0-0';
		$_idcliente = $_parametros['_idclimed'];
		$VRL12 = str_replace(",", ".",  $_parametros['_vlr12']);
   		$VRL12 = str_replace(",", ".",  $VRL12);
		$VRL24 = str_replace(",", ".",  $_parametros['_vlr24']);
   		$VRL24 = str_replace(",", ".",  $VRL24);
		$VRL36 = str_replace(",", ".",  $_parametros['_vlr36']);
   		$VRL36 = str_replace(",", ".",  $VRL36);
		$opc_garantia = $_parametros['opc_garantia'];
		$cge_valrcontratado  = 0;
		if($opc_garantia== 12) {
			$cge_valrcontratado  = $VRL12;
			$dtconclusao = $data;
			$usuariocc = $usuario;
			$_meses = 12;
			$sql_acompanhamento = "insert into   " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
				CURRENT_DATE(),'$data','".$_NUMOS."' ,'".$usuario."', '". $_SESSION["APELIDO"]."','".$_idcliente."',
				'<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Atualizado Garantia Estendida  O.S<strong> $_NUMOS </strong>  Opção $_meses  Valor R$ $cge_valrcontratado ','0' )";
				
			
		}elseif($opc_garantia== 24){
			$cge_valrcontratado  = $VRL24;
			$dtconclusao = $data;
			$usuariocc = $usuario;
			$_meses = 12;
			$sql_acompanhamento = "insert into   " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
				CURRENT_DATE(),'$data','".$_NUMOS."' ,'".$usuario."', '". $_SESSION["APELIDO"]."','".$_idcliente."',
				'<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Atualizado Garantia Estendida  O.S<strong> $_NUMOS </strong>  Opção $_meses  Valor R$ $cge_valrcontratado ','0' )";
				
			
		}elseif($opc_garantia== 36){
			$cge_valrcontratado  = $VRL36;
			$dtconclusao = $data;
			$usuariocc = $usuario;
			$_meses = 12;
			$sql_acompanhamento = "insert into   " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
				CURRENT_DATE(),'$data','".$_NUMOS."' ,'".$usuario."', '". $_SESSION["APELIDO"]."','".$_idcliente."',
				'<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Atualizado Garantia Estendida  O.S<strong> $_NUMOS </strong>  Opção $_meses  Valor R$ $cge_valrcontratado ','0' )";
				
			
		}elseif($opc_garantia== '-1'){
				$cge_valrcontratado  = 0;	
				$dtconclusao = $data;
				$usuariocc = $usuario;
				$_meses = "Não tem Interesse";
				$sql_acompanhamento = "insert into   " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
					CURRENT_DATE(),'$data','".$_NUMOS."' ,'".$usuario."', '". $_SESSION["APELIDO"]."','".$_idcliente."',
					'<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Atualizado Garantia Estendida  O.S<strong> $_NUMOS </strong>  Opção $_meses  ','0' )";
			
		}

		if($sql_acompanhamento !="") {

			$stm = $pdo->prepare($sql_acompanhamento);
			$stm->execute();
		}


		$sql = "SELECT * FROM ". $_SESSION['BASE'] .".chamada_garext WHERE cge_os = '".$_NUMOS."' limit 1";		
		$consulta = $pdo->query($sql);
		if($consulta->rowCount() == 0) {
				
			//inserir
			try{	
				
					$stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".chamada_garext (
						cge_dtinclusao,
						cge_cliente,
						cge_os,
						cge_vlr12,			
						cge_vlr24,
						cge_vlr36,
						cge_atendinclusao,
						cge_periodosel,
						cge_valrcontratado,
						cge_atendfinalizacao,
						cge_dtconclusao
						) 
							VALUES (
							?,
							?,
							?,					
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?
							); ");		
						$stm->bindParam(1, $data);	
						$stm->bindParam(2, $_idcliente);				
						$stm->bindParam(3, $_NUMOS);								
						$stm->bindParam(4, $VRL12);		
						$stm->bindParam(5, $VRL24);
						$stm->bindParam(6, $VRL36);	
						$stm->bindParam(7, $usuario);	
						$stm->bindParam(8, $opc_garantia);	
						$stm->bindParam(9, $cge_valrcontratado);	
						$stm->bindParam(10, $usuariocc);	
						$stm->bindParam(11, $dtconclusao);			
						$stm->execute();			
						echo '<div class="alert alert-info alert-dismissable " style="text-align: center;margin-top:5px"> Garantia Estendida <strong>'.$_meses.'</strong> Adicionada !</div>';
				
				}
				catch (\Exception $fault){
					$response = $fault;
					echo $response;
					echo '<div class="alert alert-warning alert-dismissable " style="text-align: center;margin-top:5px"> OPS !!! algo deu errado avise o suporte</div>';
				
				}

			 }else{	
				
				try{	
					//update
					$retorno = $consulta->fetchAll();
					foreach ($retorno as $row) {	
						
						
					}
					$stm = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamada_garext SET
					cge_vlr12 = ?,			
					cge_vlr24 = ?,
					cge_vlr36 = ?,					
					cge_periodosel = ?,
					cge_valrcontratado = ?,
					cge_atendfinalizacao = ?,
					cge_dtconclusao = ? 
					WHERE cge_os = ?");							
					
					$stm->bindParam(1, $VRL12);		
					$stm->bindParam(2, $VRL24);
					$stm->bindParam(3, $VRL36);	
					$stm->bindParam(4, $opc_garantia);	
					$stm->bindParam(5, $cge_valrcontratado);	
					$stm->bindParam(6, $usuariocc);	
					$stm->bindParam(7, $dtconclusao);			
					$stm->bindParam(8,  $_NUMOS);			
					 
					$stm->execute();			
					echo '<div class="alert alert-warning alert-dismissable " style="text-align: center;margin-top:5px"> Garantia Estendida <strong>'.$_meses.'</strong> Atualizada !</div>';
					
					$consulta = "insert into   " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
						CURRENT_DATE(),'$data','".$_NUMOS."' ,'".$usuario."', '". $_SESSION["APELIDO"]."','".$_idcliente."',
						'<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Atualizado Garantia Estendida  O.S<strong> $_NUMOS </strong>  Opção $opc_garantia Valor R$ $cge_valrcontratado ','0' )";
					
					$stm = $pdo->prepare($consulta);
					$stm->execute();
			
				}
				catch (\Exception $fault){
					$response = $fault;
					
					echo '<div class="alert alert-warning alert-dismissable " style="text-align: center;margin-top:5px"> OPS !!! algo deu errado avise o suporte</div>';
				
				}
			
				
					?></div>
					
					<?php
				}
			

}

if($_acao == 23 ) {   //deletar GARANTIA ESTENDIDA

			try{	
			
				$stm = $pdo->prepare("DELETE FROM   " . $_SESSION['BASE'] . ".chamada_garext  WHERE cge_os = ? LIMIT 1");
				$stm->bindParam(1, $_NUMOS);											
				$stm->execute();			
				echo '<div class="alert alert-danger alert-dismissable " style="text-align: center;margin-top:5px"> Garantia Estendida Excluída !</div>';
			
				$consulta = "insert into   " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
					CURRENT_DATE(),'$data','".$_NUMOS."' ,'".$usuario."', '". $_SESSION["APELIDO"]."','".$_idcliente."',
					'<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Excluído Garantia Estendida  O.S<strong> $_NUMOS </strong> ','0' )";
				$stm = $pdo->prepare($consulta);
				$stm->execute();	
			
		
			}
			catch (\Exception $fault){
				$response = $fault;
				
				echo '<div class="alert alert-warning alert-dismissable " style="text-align: center;margin-top:5px"> OPS !!! algo deu errado avise o suporte</div>';
			
			}
		
		

}
?> 