<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;


$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data      = $ano . "-" . $mes . "-" . $dia ;
$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];; //codigo login

$usuariologado =  $_SESSION["APELIDO"]; //nome
$dtaberturaSelAgenda = "";
		

$dtaberturaSelAgenda = $_parametros['dtaberturaSelAgenda'];

function mensagem($_mensagem, $_campo, $valor_campo )
{
	$_texto =    str_replace($_campo, $valor_campo, $_mensagem); //[NOME]

	return $_texto;
}



if($_acao == 1 ) {   
	?>
	
<table id="tabx" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
<thead>
<tr>
	<th class="text-left"  style="width:50px ;">Ação</th>	
	<th class="text-center">Nº O.S</th>    
	<th class="text-center">Tipo Gar.</th>    
	<th class="text-center" style="width:170px ;">Dt Encerramento O.S</th>
	<th class="text-center" style="width:350px ;">Cliente </th>
	<th class="text-center" >Telefone</th>	
	<th class="text-center">Tratativa</th>
	<th class="text-center">Enviar</th>
	<th class="text-center">Dt Envio</th>
	<th class="text-center">Status</th>
</tr>
</thead>
<tbody>

<?php

if($_parametros['tipogar'] != "") {
	$filtro = " AND nps_garantia = '".$_parametros['tipogar'] ."'";
}
if($_parametros['tratativa'] != "") {
	$filtro = $filtro." AND nps_idtratativa = '".$_parametros['tratativa'] ."'";
}

if($_parametros['tiponps'] != "") {
	$filtro = $filtro." AND tipo_npsid = '".$_parametros['tiponps'] ."'";
}


			$sql = "Select nps_id,nps_dtconclusaoos,date_format(nps_dtenvio,'%d/%m/%Y') as dtenvio, nps_dtinclusao, nps_OS,g_descricao,Nome_Consumidor,tipo_npsdesc,trat_descricao,nps_dtenvio,DDD,FONE_RESIDENCIAL,FONE_CELULAR,
			trat_cor,tipo_npscor,g_sigla,g_cor,nps_idtratativa
			from ".$_SESSION['BASE'].".nps 
			LEFT JOIN ".$_SESSION['BASE'].".situacao_garantia ON nps_garantia = g_id
			LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR = nps_idcliente
			LEFT JOIN ".$_SESSION['BASE'].".tratativa ON nps_idtratativa = trat_id
			LEFT JOIN ".$_SESSION['BASE'].".tipo_nps ON tipo_npsid = nps_idtiponps
			where nps_dtconclusaoos BETWEEN '".$_parametros['_dataIni']."' AND  '".$_parametros['_dataFim']."'  $filtro  "; 
			$stm = $pdo->query("$sql");
			$ret = $stm->fetchAll(PDO::FETCH_OBJ);
 		   
			if ( $stm->rowCount() > 0 ){
			
					foreach ($ret as $row) { 
						$tipoger = $row->g_sigla;
						$COR = $row->g_cor;
						if($tipoger == "") {
							$tipoger = "FG";
							$COR = "primary";
						}
			
				?>
				<tr  id='L<?=$row->nps_id;?>'>	<td class="text-left" >

					
					<button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-atend" onclick="_det(<?=$row->nps_id;?>)"> <i class="fa  fa-pencil"></i></button>
					</td>
					
					<td class="text-center"><?=$row->nps_OS;?></td>
					<td class="text-center"><span  class="badge badge-xs bg-<?=$COR;?>" > <?=$tipoger;?></span></td>					
					<td class="text-center "><?=date('d/m/Y',  strtotime($row->nps_dtinclusao))?></td>
					<td class="text-center"><?=$row->Nome_Consumidor;?></td>
					<td class="text-center"><?=$row->DDD;?>-<?=$row->FONE_RESIDENCIAL;?> <?=$row->FONE_CELULAR;?></td>
					
					<td class="text-center">
						<span class="label label-table label-<?=$row->trat_cor?>"><?=$row->trat_descricao?></span>
					</td>
					<td class="text-center ">
						<?php 
						if($row->nps_dtenvio == "" or $row->nps_dtenvio == "0000-00-00") {  ?>
						<div id="<?=$row->nps_id;?>"><button type="button" class="btn btn-success  btn-sm waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarwhats(<?=$row->nps_id;?>)" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-whatsapp"></i></span>ENVIAR</button></div>
						
						<?php }else{
					
							if($row->nps_idtratativa != "3") { ?>
								<div id="<?=$row->nps_id;?>"><button type="button" class="btn btn-success  btn-sm waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarwhats(<?=$row->nps_id;?>)" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-whatsapp"></i></span>ENVIAR</button></div>

							<?php }
							} ?>
							</td>
							<td class="text-center">
							<?=$row->dtenvio;?>
					</td>
					
					<td class="text-center">
						<span class="label label-table label-<?=$row->tipo_npscor?>"><?=$row->tipo_npsdesc?></span>
					</td>			
				</tr>
				<?php 
			} 
		}?>

</tbody>
</table>
<?php
exit();
}

if($_acao == "2"  ) {   //envio mensagem  whats_situacao = 999

	$sql = "Select  *	from ".$_SESSION['BASE'].".nps  where nps_id = '".$_parametros['_idnps']."'   ";  
	$stm = $pdo->prepare("$sql");            
	$stm->execute();	
	
	if ( $stm->rowCount() > 0 ){
		while($row = $stm->fetch(PDO::FETCH_OBJ)){
			$_idcli = $row->nps_idcliente;
		}
	}

	$query = ("SELECT tokenwats,serviceId,urlwats,NOME_FANTASIA,TELEFONE  from  parametro  ");
	$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($result)) {

		$tokenwats = 'Authorization: Bearer '.$rst["tokenwats"];
		$serviceId =  $rst["serviceId"] ;
		$urlwats = $rst["urlwats"];
		$_nome_empresa = $rst["NOME_FANTASIA"];
		$_telefone_empresa = $rst["TELEFONE"];
	}

	 //buscar telefone wats cadastro consumidor
	 $_telefone = "";
	 $consulta = "Select DDD,id_celularwats,id_celular2wats,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR from consumidor where  CODIGO_CONSUMIDOR = '".$_idcli."' ";

	 $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	 while ($rst = mysqli_fetch_array($executa)) {             
		 if($_telefone == "" and $rst['id_celularwats'] == 1){
			 $_telefone = $rst["DDD"].$rst["FONE_CELULAR"];
		 }elseif($rst['id_celular2wats'] == 1){
			 $_telefone = $rst["DDD"].$rst["FONE_COMERCIAL"];
		 }

	  }

	        
	  if( $_telefone != "") { 
        $_telefone = str_replace(".", "",  $_telefone);
        $_telefone = str_replace(" ", "",  $_telefone);
        $_telefone = str_replace("-", "",  $_telefone);
        $_telefone = "55".$_telefone;
   
		$sql = "Select *		
			from ".$_SESSION['BASE'].".msg_whats 				
			where  whats_nps = '1'  limit 1  "; 
			$stm = $pdo->prepare("$sql");            
			$stm->execute();	
		
			
			if ( $stm->rowCount() > 0 ){
				while($row = $stm->fetch(PDO::FETCH_OBJ)){
					$_msg = $row->whats_mensagem;
					$dontOpenTicket = $row->dontOpenTicket;
					
				}
			}

		if($dontOpenTicket == 0) {
			$dontOpenTicket = "false";
		}else{
			$dontOpenTicket = "true";
		}

        $_fields = "number=$_telefone&text=".rawurlencode($_msg)."&serviceId=".$serviceId."&dontOpenTicket=$dontOpenTicket&departmentId=6a1895c4-3383-4152-957f-9cf1c98357ac";
   

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $urlwats,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 15,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => ''.$_fields.'',
          CURLOPT_HTTPHEADER => array(
           ''.$tokenwats.'',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);        
        curl_close($curl);     
  
        $obj = json_decode($response);
   
    
        if(  $obj->sent == false){
            $descricao_alte = "Falha no envio ";
        }else{
           $descricao_alte = "Enviado";
		   $stm = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".nps 
		   SET nps_dtenvio = ? ,
		   nps_hrenvio = ? ,
		   nps_usuarioenvio = ? 
		   WHERE nps_id = '".$_parametros['_idnps']."' ");
			$stm->bindParam(1, $data);                      
			$stm->bindParam(2, $datahora);				
			$stm->bindParam(3, $_SESSION['tecnico']);								
					
			$stm->execute();			

        }      

        echo "$descricao_alte ";
     
    }else{
       
       		 $descricao_alte = "Telefone incorreto";
       		 echo "$descricao_alte";
       
	}

exit();
}
if($_acao == "3"  ) {   //detalhamento

	$sql = "Select nps_id,nps_OS,Nome_Consumidor		
			from ".$_SESSION['BASE'].".nps 		
			LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR = nps_idcliente		
			where  nps_id = '".$_parametros['_idnps']."'   ";     
	
			$stm = $pdo->prepare("$sql");            
			$stm->execute();	
		
			
			if ( $stm->rowCount() > 0 ){
				while($row = $stm->fetch(PDO::FETCH_OBJ)){
					$_os = $row->nps_OS;
					$_nome = $row->Nome_Consumidor;
				}
			}
	?>

<div class="card-box">
  <div class="row">
  	 <div class="col-md-2 col-xs-2"  style="text-align: left;"> <strong>O.S <?=$_os;?></strong>
	 </div>
	 <div class="col-md-10 col-xs-10"  style="text-align: left;"> <strong> <?=$_nome;?></strong>
	 </div>
	 
  </div>

  <div class="row">
	 <div class="col-md-10 col-xs-12"  >
				<div class="card-box">
					<div class="row">
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(10)">
							10<span class="mini-stat-icon bg-success"><i class=" ti-face-smile text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(9)">
							9<span class="mini-stat-icon bg-success"><i class=" ti-face-smile text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(8)">
							8<span class="mini-stat-icon bg-warning"><i class=" ti-face-smile text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(7)">
							7<span class="mini-stat-icon bg-warning"><i class=" ti-face-smile text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(6)">
							6<span class="mini-stat-icon bg-danger"><i class="  ti-face-sad text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(5)">
							5<span class="mini-stat-icon bg-danger"><i class="  ti-face-sad text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(4)">
							4<span class="mini-stat-icon bg-danger"><i class="  ti-face-sad text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(3)">
							3<span class="mini-stat-icon bg-danger"><i class="  ti-face-sad text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;margin-right:10px;cursor:pointer" onclick="_selnps(2)">
							2<span class="mini-stat-icon bg-danger"><i class="  ti-face-sad text-white"></i></span>
						</div>
						<div class="col-md-1 col-xs-2"  style="text-align: center;cursor:pointer" onclick="_selnps(1)">
							1<span class="mini-stat-icon bg-danger"><i class="  ti-face-sad text-white"></i></span>
						</div>
						
					</div>
				</div>
	 </div>
	 <div class="col-md-2 col-xs-12"  >
			<div class="card-box">
			<div class="row">
					<div class="col-md-12 col-xs-12"  style="min-height: 80px; text-align:center"  id="retselnps" >				
					
							<label class="control-label" for="nf-chave">SELEÇÃO (-)</label>				
								<span class="mini-stat-icon bg-inverse" ><i class=" text-white"></i></span>
						
						</div>
			</div>
			</div>
	 </div>

					<div class="row">
									<div class="col-md-2 col-xs-12">
                                         <button type="button" style="margin:5px ;" class="btn btn-danger btn-whatsapp waves-effect waves-light btn-block" onclick=" _drets()"> Sem Retorno
                                  </div>
                                <div class="col-md-10 col-xs-12">
                                         <button type="button" style="margin:5px ;" class="btn btn-success btn-whatsapp waves-effect waves-light btn-block" onclick=" _dret()"> Confirmar Resposta
                                  </div>
                           
                      </div>

<?php 
exit();

}

if($_acao == "4"  ) {   //detalhamentosel
	
	$_idsel = $_parametros['_idacaosel'];
	if($_idsel == 10 or $_idsel == 9) { 
		$_cor = 'bg-success';
		$_icone = 'ti-face-smile';
	}

	if($_idsel == 8 or $_idsel == 7) { 
		$_cor = 'bg-warning';
		$_icone = 'ti-face-smile';
	}

	if($_idsel <=6) { 
		$_cor = 'bg-danger';
		$_icone = 'ti-face-sad';
	}

	?>
	<label class="control-label" for="nf-chave">SELEÇÃO (<?=$_idsel;?>)</label>
	<span class="mini-stat-icon <?=$_cor;?>"><i class="  <?=$_icone;?> text-white"></i></span>
	<?php 

}

if($_acao == "5"  ) {   //update resposta
	$_idsel = $_parametros['_idacaosel'];

	if($_idsel == 10 or $_idsel == 9) {
		$status = 1; //promotor
	}
	if($_idsel == 8 or $_idsel == 7) {
		$status = 2; //neutro
	}
	if($_idsel <= 6 ) {
		$status = 3; //detratator
	}

	if($_idsel== "") {

		$stm = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".nps 
	SET nps_nota = '0' ,
	nps_idtiponps = '0',
	nps_dtresposta = ? ,
	nps_usuarioresposta  = ?,
	nps_idtratativa = '2'
	WHERE nps_id = '".$_parametros['_idnps']."' ");
	                  
	 $stm->bindParam(1, $datahora);				
	 $stm->bindParam(2, $_SESSION['tecnico']);	
							
			 
	 $stm->execute();		
	 echo "2"; //Sem Resposta 
	}else{
		$stm = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".nps 
	SET nps_nota = ? ,
	nps_dtresposta = ? ,
	nps_usuarioresposta = ? ,
	nps_idtiponps = ?,
	nps_idtratativa = '3'
	WHERE nps_id = '".$_parametros['_idnps']."' ");
	 $stm->bindParam(1, $_parametros['_idacaosel']);                      
	 $stm->bindParam(2, $datahora);				
	 $stm->bindParam(3, $_SESSION['tecnico']);	
	 $stm->bindParam(4, $status);								
			 
	 $stm->execute();		
	 echo "1"; //Confirmado nota Resposta

	}

	

}


if($_acao == "55"  ) {   //update resposta
	$_idsel = $_parametros['_idacaosel'];

		$stm = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".nps 
	SET nps_nota = '0' ,
	nps_idtiponps = '0',
	nps_dtresposta = ? ,
	nps_usuarioresposta  = ?,
	nps_idtratativa = '2'
	WHERE nps_id = '".$_parametros['_idnps']."' ");
	                  
	 $stm->bindParam(1, $datahora);				
	 $stm->bindParam(2, $_SESSION['tecnico']);	
							
			 
	 $stm->execute();		
	 echo "2"; //Sem Resposta 
	

	

	

}


if($_acao == 6 ) {    //RESUMO


if($_parametros['tipogar'] != "") {
	$filtro = " AND nps_garantia = '".$_parametros['tipogar'] ."'";
}
if($_parametros['tratativa'] != "") {
	$filtro = $filtro." AND nps_idtratativa = '".$_parametros['tratativa'] ."'";
}

if($_parametros['tiponps'] != "") {
	$filtro = $filtro." AND tipo_npsid = '".$_parametros['tiponps'] ."'";
}


			$sql = "Select count(nps_id) as t
			from ".$_SESSION['BASE'].".nps 			
			where nps_nota > 0 and nps_dtconclusaoos BETWEEN '".$_parametros['_dataIni']."' AND  '".$_parametros['_dataFim']."'  $filtro  "; 
			$stm = $pdo->query("$sql");
		
			while($row = $stm->fetch(PDO::FETCH_OBJ)){
				$totreg  = $row->t;
			}

			$sql = "Select count(nps_id) as tt
			from ".$_SESSION['BASE'].".nps 			
			where nps_nota > 0 and  nps_idtiponps = '1' AND nps_dtconclusaoos BETWEEN '".$_parametros['_dataIni']."' AND  '".$_parametros['_dataFim']."'  $filtro  "; 
			$stm = $pdo->query("$sql");
			while($row = $stm->fetch(PDO::FETCH_OBJ)){
				$totregpromotores  = $row->tt;
			}

			
			$sql = "Select count(nps_id) as tt
			from ".$_SESSION['BASE'].".nps 			
			where nps_nota > 0 and  nps_idtiponps = '2' AND nps_dtconclusaoos BETWEEN '".$_parametros['_dataIni']."' AND  '".$_parametros['_dataFim']."'  $filtro  "; 
			$stm = $pdo->query("$sql");
			while($row = $stm->fetch(PDO::FETCH_OBJ)){
				$totregneutro  = $row->tt;
			}
		
			
			

			$sql = "Select count(nps_id) as ttt
			from ".$_SESSION['BASE'].".nps 			
			where nps_nota > 0 and  nps_idtiponps = '3' AND nps_dtconclusaoos BETWEEN '".$_parametros['_dataIni']."' AND  '".$_parametros['_dataFim']."'  $filtro  "; 
			$stm = $pdo->query("$sql");
			while($row = $stm->fetch(PDO::FETCH_OBJ)){
				$totregdetratores  = $row->ttt;
			}

			

			$neutro = $totreg - ($promotores+$detratores );
			//Número de promotores - Número de detratores
						//Número de respostas
			$nps = ($totregpromotores-$totregdetratores)/$totreg*100;

			$nps = substr($nps,0,2);

			$promotores = ($totregpromotores/$totreg)*100;
			$neutro = ($$totregneutro/$totreg)*100;
			$detratores = ($totregdetratores/$totreg)*100;
			
			$promotores = number_format($promotores, 2, ',', '.');
			$neutro = number_format($neutro, 2, ',', '.');
			$detratores = number_format($detratores, 2, ',', '.');

		?>

<div class="row">
                                <div class="col-md-3 col-xs-12">
                                    <div class="card-box">
                                        <div class="circliful-chart m-b-30" data-dimension="180" data-text="<?=$nps;?>%" data-info="NPS" data-width="20" data-fontsize="24" data-percent="<?=$nps;?>" data-fgcolor="#FF7C0A" data-bgcolor="#ebeff2" data-fill="#f4f8fb"></div>
                                    </div>

                                </div>
                               
                                <div class="col-md-3 col-xs-12">
                                <div class="card-box">
                                     <p class="font-600">Promotor <span class="text-success pull-right"><?=$promotores;?>%</span></p>
                                        <div class="progress m-b-30">
                                        <div class="progress-bar progress-bar-success progress-animated wow animated" role="progressbar" aria-valuenow="<?=$promotores;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$promotores;?>%">
                                        </div><!-- /.progress-bar .progress-bar-success -->
                                        </div><!-- /.progress .no-rounded -->

                                    <p class="font-600">Neutro <span class="text-primary pull-right"><?=$neutro;?>%</span></p>
                                        <div class="progress m-b-30">
                                        <div class="progress-bar progress-bar-primary progress-animated wow animated" role="progressbar" aria-valuenow="<?=$neutro;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$neutro;?>%">
                                        </div><!-- /.progress-bar .progress-bar-danger -->
                                        </div><!-- /.progress .no-rounded -->

                                        <p class="font-600">Detrator <span class="text-danger pull-right"><?=$detratores;?>%</span></p>
                                        <div class="progress m-b-30">
                                        <div class="progress-bar progress-bar-danger progress-animated wow animated" role="progressbar" aria-valuenow="<?=$detratores;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$detratores;?>%">
                                        </div><!-- /.progress-bar .progress-bar-pink -->
                                        </div><!-- /.progress .no-rounded -->

                                </div>
                                       
                                </div>
                                 <div class="col-md-6 col-xs-12">
									<div class="card-box">
									<table class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
												<thead>
													<tr>
														<th class="text-center">Nota</th>
														<th class="text-center">QTDE</th>
												</tr>
												</thead>
												<tbody>	
													<?php 
													$sql = "Select nps_nota,count(nps_nota) as t
													from ".$_SESSION['BASE'].".nps 			
													where nps_nota > 0  AND nps_dtconclusaoos BETWEEN '".$_parametros['_dataIni']."' AND  '".$_parametros['_dataFim']."'  $filtro  
													group by nps_nota  ORDER BY nps_nota DESC"; 
												
													$stm = $pdo->query("$sql");
													while($row = $stm->fetch(PDO::FETCH_OBJ)){
														$tot  = $row->t;
														$nota  = $row->nps_nota;
														?>	<tr>
															<td class="text-center"><?=$nota;?> </td>
															<td class="text-center"><?=$tot;?></td>                                                
													</tr>
														<?php
													}
													?>
												
													
												
																		
											</tbody>
												</table>
								
									</div>
                                 
                                </div>
                            </div>
<?php
exit();
}

if($_acao == 555) {    //LINHA
	
	$sql = "Select nps_id,nps_dtconclusaoos,date_format(nps_dtenvio,'%d/%m/%Y') as dtenvio, nps_dtinclusao, nps_OS,g_descricao,Nome_Consumidor,tipo_npsdesc,trat_descricao,nps_dtenvio,DDD,FONE_RESIDENCIAL,FONE_CELULAR,
	trat_cor,tipo_npscor,g_sigla,g_cor,nps_idtratativa
	from ".$_SESSION['BASE'].".nps 
	LEFT JOIN ".$_SESSION['BASE'].".situacao_garantia ON nps_garantia = g_id
	LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR = nps_idcliente
	LEFT JOIN ".$_SESSION['BASE'].".tratativa ON nps_idtratativa = trat_id
	LEFT JOIN ".$_SESSION['BASE'].".tipo_nps ON tipo_npsid = nps_idtiponps
	where nps_id  = '".$_parametros['_idnps']."'"; 
	
	$stm = $pdo->query("$sql");
	$ret = $stm->fetchAll(PDO::FETCH_OBJ);
	
	if ( $stm->rowCount() > 0 ){
	
			foreach ($ret as $row) { 
				$tipoger = $row->g_sigla;
				$COR = $row->g_cor;
				if($tipoger == "") {
					$tipoger = "FG";
					$COR = "primary";
				}
	
		?>
	

	<td class="text-left" >
			<button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-atend" onclick="_det(<?=$row->nps_id;?>)"> <i class="fa  fa-pencil"></i></button>
	</td>
			<td class="text-center"><?=$row->nps_OS;?></td>
			<td class="text-center"><span  class="badge badge-xs bg-<?=$COR;?>" > <?=$tipoger;?></span></td>					
			<td class="text-center "><?=date('d/m/Y',  strtotime($row->nps_dtinclusao))?></td>
			<td class="text-center"><?=$row->Nome_Consumidor;?></td>
			<td class="text-center"><?=$row->DDD;?>-<?=$row->FONE_RESIDENCIAL;?> <?=$row->FONE_CELULAR;?></td>
			
			<td class="text-center">
				<span class="label label-table label-<?=$row->trat_cor?>"><?=$row->trat_descricao?></span>
			</td>
			<td class="text-center ">
				<?php 
				if($row->nps_dtenvio == "" or $row->nps_dtenvio == "0000-00-00") {  ?>
				<div id="<?=$row->nps_id;?>"><button type="button" class="btn btn-success  btn-sm waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarwhats(<?=$row->nps_id;?>)" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-whatsapp"></i></span>ENVIAR</button></div>
				
				<?php }else{
			
					if($row->nps_idtratativa != "3") { ?>
						<div id="<?=$row->nps_id;?>"><button type="button" class="btn btn-success  btn-sm waves-effect waves-light"  aria-expanded="false" id="_bt00045" onclick="_enviarwhats(<?=$row->nps_id;?>)" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-whatsapp"></i></span>ENVIAR</button></div>

					<?php }
					} ?>
					</td>
					<td class="text-center">
					<?=$row->dtenvio;?>
			</td>
			
			<td class="text-center">
				<span class="label label-table label-<?=$row->tipo_npscor?>"><?=$row->tipo_npsdesc?></span>
			</td>			
	
		<?php 
	} 
}
}

?> 