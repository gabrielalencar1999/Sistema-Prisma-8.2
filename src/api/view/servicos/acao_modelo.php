<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;

$pdo = MySQL::acessabd();


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];; //codigo login

if($_acao == 1 ) {   //modelo
	$descricao = ($_parametros["modelo"]);
if( $descricao <> '') {   //modelo
	$consulta = "Select MODELO,DESCRICAO, fabricante.nome as fornecedor
	from aparelho 
	left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = aparelho.CODIGO_FABRICANTE	
	WHERE MODELO like '%$descricao%' and MODELO <> '' order by MODELO,NOME,DESCRICAO";
$executa = mysqli_query($mysqli,$consulta)  or die(mysqli_error($mysqli));
$reg = mysqli_num_rows($executa);
if($reg == 0) {
	?>
	<div class="row" style="border:  1px solid #CCC ; font-size:12px; background: #eaf8ff;   width:550px; min-height:25px">
	
	<div class="col-sm-12">
	Nenhum modelo encontrado
	   </div>
	   
   
	   
   </div>        
<?php 
}else{


while ($rst = mysqli_fetch_array($executa)) {
?>
     <div class="row" style="border:  1px solid #CCC ; font-size:12px; background: #eaf8ff;   width:550px; min-height:25px">
	 
	 <div class="col-sm-2">
	 <span style="cursor:pointer ;"  onClick="modelo_carrega('<?=$rst['MODELO'];?>','<?=$rst['DESCRICAO'];?>','<?=$rst['fornecedor'];?>')"><?=$rst['MODELO'];?></span>
		</div>
		<div class="col-sm-6">
		<?=$rst['DESCRICAO'];?>
		</div>
		<div class="col-sm-3">
			<?=$rst['fornecedor'];?>
		</div>
	
		
	</div>        
<?php 
}
}  
}else{
	?>
	<div class="row" style="border:  1px solid #CCC ; font-size:12px; background: #eaf8ff;   width:550px; min-height:25px">
	
	<div class="col-sm-12">
	Informe modelo pesquisa
	   </div>
	   
   
	   
   </div>        
<?php 
}
}


if($_acao == 2 ) {   //descricao produto
	$descricao = ($_parametros["descricaoproduto"]);
	if( $descricao <> '') {   
		$consulta = "Select MODELO,DESCRICAO, fabricante.nome as fornecedor
		from aparelho 
		left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = aparelho.CODIGO_FABRICANTE	
		WHERE DESCRICAO like '%$descricao%' and DESCRICAO <> '' order by DESCRICAO,MODELO,NOME";
		
	$executa = mysqli_query($mysqli,$consulta)  or die(mysqli_error($mysqli));
	$reg = mysqli_num_rows($executa);
	if($reg == 0) {
		?>
		<div class="row" style="border:  1px solid #CCC ; font-size:12px; background: #eaf8ff;   width:550px; min-height:25px">
		
		<div class="col-sm-12">
		Nenhum produto encontrado
		   </div>
		   
	   
		   
	   </div>        
	<?php 
	}else{
	
	
	while ($rst = mysqli_fetch_array($executa)) {
	?>
		 <div class="row" style="border:  1px solid #CCC ; font-size:12px; background: #eaf8ff;   width:550px; min-height:25px">
		 
		 <div class="col-sm-2">
		 <span style="cursor:pointer ;"  onClick="descricao_carrega('<?=$rst['MODELO'];?>','<?=$rst['DESCRICAO'];?>','<?=$rst['fornecedor'];?>')"><?=$rst['MODELO'];?></span>
			</div>
			<div class="col-sm-6">
				<?=$rst['DESCRICAO'];?>
			</div>
			<div class="col-sm-3">
				<?=$rst['fornecedor'];?>
			</div>			
		</div>        
	<?php 
	}
	}  
	}else{
		?>
		<div class="row" style="border:  1px solid #CCC ; font-size:12px; background: #eaf8ff;   width:550px; min-height:25px">
		
		<div class="col-sm-12">
				Informe descricao pesquisa
		   </div>
	   </div>        
	<?php 
	}
	}

	if($_acao == 3 ) {   //PESQUISAR MODAL DE PRODUTO
	 $descricao = ($_parametros["busca-aparelho"]);
	 if(strlen($descricao) <= 2) { ?>
		<th ><div class="col-sm-10">
			<div class="alert alert-warning text-center" style="margin:15px ;">
				Digite mais 3 letras para pesquisar
			</div>
		</th>
	<?php 
	exit();
}
	if( $descricao <> ''  ) {   
		$consulta = "Select MODELO,DESCRICAO, fabricante.nome as fornecedor,mes_preventivo,aparelho_codProduto
		from aparelho 
		left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = aparelho.CODIGO_FABRICANTE	
		WHERE DESCRICAO like '%$descricao%' and DESCRICAO <> '' OR 
		nome like '%$descricao%' AND nome <> '' OR 
		MODELO like '%$descricao%' AND MODELO <> '' 
		order by DESCRICAO,MODELO,NOME";
		
	$executa = mysqli_query($mysqli,$consulta)  or die(mysqli_error($mysqli));
	$reg = mysqli_num_rows($executa);
	if($reg == 0) {
		?><th ><div class="col-sm-10">
		
	
		<?php  if($_SESSION['per164'] == '164'){ ?>
			<div class="alert alert-danger text-center" style="margin:15px ;">
				Você não tem permissão para cadastrar novos produtos
			</div>
			<?php } else { ?>
					<div class="alert alert-warning text-center" style="margin:15px ;">
				Nenhum produto encontrado<br>Informe abaixo dados para novo cadastro
			</div>
		<div class="row " style="margin-left:15px ;">
			<div class="col-sm-10">
                <label >Descrição Produto</label>
                 <input type="text" name="newprod" id="newprod" value="" class="form-control input-sm">
            </div>
		</div>
		<div class="row " style="margin-left:15px ;">
			<div class="col-sm-6">
                <label >Marca</label>
               
				 <select name="newmarca" id="newmarca" class="form-control input-sm">
                                                
                                                <?php
												
                                                $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante where for_Tipo = 1 ORDER BY NOME");
                                                $retorno = $consulta->fetchAll();
                                                foreach ($retorno as $row) {
                                                    ?><option value="<?=$row["CODIGO_FABRICANTE"]?>"><?=$row["NOME"]?></option><?php
                                                }
												
                                                ?>
                                            </select>
            </div>
			<div class="col-sm-4">
                <label >Modelo Comercial</label>
                 <input type="text" name="newmod" id="newmod" value="" class="form-control input-sm">
            </div>
		</div>
		<div class="row " style="margin:15px ;">
			<div class="col-sm-10">
			<button type="button" class="btn btn-success waves-effect" data-dismiss="modal" onclick="_aparelhoSalvar()">Incluir Produto</button>
            </div>
		</div>
	<?php } ?>
			
		</div>
	</div></th>
		<?php  
	}else{ 
		while ($rst = mysqli_fetch_array($executa)) {
			?>
			<tr>
			<th><?=$rst['DESCRICAO'];?></th>
			<th><?=$rst['fornecedor'];?></th>
			<th><?=$rst['MODELO'];?></th>
			<th><a href="javascript:void(0);" class="on-default" data-dismiss="modal" onclick="_aparelhoSEL('<?=$rst['DESCRICAO'];?>','<?=$rst['fornecedor'];?>','<?=$rst['MODELO'];?>','<?=$rst['mes_preventivo'];?>','<?=$rst['aparelho_codProduto'];?>','<?=$rst['aparelho_codProduto'];?>')"><i class="fa fa-check fa-1x"></i></a></th>
			</tr>
			<?php }
		
		
	}
	}
}

if($_acao == 5 ) {   //INCLUIR  MODAL DE PRODUTO

	$consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante where CODIGO_FABRICANTE = '".$_parametros["newmarca"]."' ");
	$retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {
		$_nomefabricante = $row["NOME"];
	}

	$consulta = "insert into aparelho (DESCRICAO,CODIGO_FABRICANTE,MODELO,aparelho_codigologin)
	 values (
	'".$_parametros["newprod"]."','".$_parametros["newmarca"]."','".$_parametros["newmod"]."','$usuario')";	
	
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	
	echo $_parametros["newprod"].";".$_nomefabricante.";".$_parametros["newmod"];

}


if($_acao == 6  or $_acao == 7 ) {   //FICHA DA os
	//print_r($_parametros); 

	//$_parametros["codigo"];

	
	$consulta = $pdo->query("SELECT IND_IMPRESSO
    FROM ". $_SESSION['BASE'] .".chamada where 	CODIGO_CHAMADA = '".$_parametros["_os"]."' LIMIT 1 ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {		
	$impressa = $row['IND_IMPRESSO'];
   }
	
   $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".situacao_garantia where g_id = '".$_parametros["garantia"]."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {
	   $DESTIPOGARANTIA = $row["g_descricao"];
   }
 
   $consulta = $pdo->query("SELECT usuario_APELIDO FROM ". $_SESSION['BASE'] .".usuario where usuario_CODIGOUSUARIO = '".$_parametros["tecnico_e"]."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {
	   $nometecnico = $row["usuario_APELIDO"];
   }
   $consulta = $pdo->query("SELECT usuario_APELIDO FROM ". $_SESSION['BASE'] .".usuario where usuario_CODIGOUSUARIO = '".$_parametros["tecnico_e2"]."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {
	   $nometecnicooficina = $row["usuario_APELIDO"];
   }

 //  if($_parametros['situacao'] == "Encerrado"){
		//$sit = $_parametros["situacao_original"];
		$sit = $_parametros["situacao"];
		//
 //  }else{
		//$sit = $_parametros["situacao"];
  // }

   $consulta = $pdo->query("SELECT DESCRICAO,cor_sit,cor_sitcodigo,cor_sitcodigofonte FROM ". $_SESSION['BASE'] .".situacaoos_elx where COD_SITUACAO_OS = '".$sit."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {
	   $situacao = $row["DESCRICAO"];
	   $cor = $row["cor_sit"];
	   if($row["cor_sitcodigo"] != "") {
			$_stylecor = 'style="color:'.$rst["cor_sitcodigofonte"].'; background-color: '.$rst["cor_sitcodigo"].'"';
		}
   }

  
	//  $situacaooficina = $row["sitmobOF_descricao"];
	//   $coroficina = $row["sitmobOF_cortable"];
   


   $valor = trim($_parametros["_vlrdescontopeca"]);
   $vlrdescontopeca = str_replace(",", ".", $valor);

   $valor = trim($_parametros["_vlrdesconto"]);
   $vlrdesconto = str_replace(",", ".", $valor);

   $valor = trim($_parametros["_vlrtaxa"]);
   $vlrtaxa = str_replace(",", ".", $valor);

   $consulta = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as pecas
    FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '".$_parametros["_os"]."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {		
		$vlrtotal = $vlrtotal + $row['pecas'];
		$vlrpeca = $row['pecas'];
   }

   $consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
   FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 1 and	Numero_OS = '".$_parametros["_os"]."' and Codigo_Peca_OS <> 2 ");
   $retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {		
		$vlrmaoobra = $row['maoobra'];
		$vlrtotal = $vlrtotal + $row['maoobra'];
	}

	$consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
	FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 1 and	Numero_OS = '".$_parametros["_os"]."' and Codigo_Peca_OS = 2 ");
	$retorno = $consulta->fetchAll();
	 foreach ($retorno as $row) {		
		 $vlrtaxa =  $vlrtaxa + $row['maoobra'];
		
	 }

   $vlrtotal  = $vlrtotal  + $vlrtaxa - $vlrdesconto - $vlrdescontopeca;
   //$vlrtotal = number_format($vlrtotal,2,',','.');

$urgente = $_parametros['urgente'];

if($impressa == 'N'){  $impressa  = "-"; } else { $impressa = '<span class="label label-inverse" style="font-size:12px">SIM</span>';} 
if($urgente == 0){ //$urgente = "Não"; 
		$urgente = '<span class="label label-info" style="font-size:12px">Não</span>';
	}else { //$urgente = "Sim";
		$urgente ='<span class="label label-danger" style="font-size:14px">Sim</span>';}
if($_acao == 6 ){
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	<h4 class="modal-title">Resumo</h4>
</div>
<?php  } ?>
<div class="modal-body">

	<div class="row">
		<div class="col-md-4 col-lg-3">
			<div class="profile-detail card-box" style="background: url(assets/images/agsquare.png);">
				<div>                                 

					<ul class="list-inline status-list m-t-5">
						<li>
						<h3 class="text-primary m-b-5">O.S <?=$_parametros["_os"];?></h3>
						<p class=" font-13"><strong> <?=$DESTIPOGARANTIA;?></strong></p>
						   
						
						</li>                                      
					</ul>                               

					<hr>
					<h4 class="text-uppercase font-600"><?=$_parametros["descricaoproduto"];?></h4>
					
					<div class="text-left;">
					<p class=" font-13"><strong>MODELO : <?=$_parametros["modelo"];?></strong></p>
						<p class=" font-13"><strong>SERIE : <?=$_parametros["serie"];?></strong></p>
						<p class=" font-13"><strong>PNC : <?=$_parametros["pnc"];?></strong></p>
						<?php if($_parametros["osfabricante"] != "") { ?>
							<p class=" font-13 text-danger"><strong>O.S Fabricante :  <?=$_parametros["osfabricante"];?></strong></p>
						<?php } ?>	
						
					</div>

				</div>

			</div>

			<div class="card-box" style="padding: 0px;background: url(assets/images/agsquare.png);">
			<div style="text-align: center;"> <h4 class="text-uppercase font-600">Total R$ <?=number_format($vlrtotal,2,',','.');?></h4></div>
		   
				<table class="table table-striped table-bordered">
					<thead>
						<tr>                                           
							<th>Descrição</th>
							<th>Vlr R$</th>													  
						</tr>
					</thead>
					<tbody>				
						<tr>                                          
							<td>Vlr Peças </td>
							<td style="text-align: right;"><span class="text-custom"><?=number_format($vlrpeca,2,',','.');?></span></td>                                           
						</tr>
						<?php if($vlrdescontopeca > 0) { ?>						
						<tr>                                          
							<td>Desc.Peças</td>
							<td style="text-align: right;"><span class="text-danger">- <?=$_parametros["_vlrdescontopeca"];?></span></td>                                          
						</tr>
						<?php } ?>
						<tr>                                          
							<td>Vlr Serviços </td>
							<td style="text-align: right;"><span class="text-custom"><?=number_format($vlrmaoobra,2,',','.');?></span></td>                                          
						</tr>
						<?php if($vlrdesconto > 0) { ?>	
						<tr>                                          
							<td>Desc.Serviços</td>
							<td style="text-align: right;"><span class="text-danger">-<?=$_parametros["_vlrdesconto"];?></span></td>                                          
						</tr>
						<?php } ?>
						<tr>                                          
							<td>Taxa </td>
							<td style="text-align: right;"><span class="text-custom"><?=number_format($vlrtaxa,2,',','.');?></span></td>                                          
						</tr>
					</tbody>
				</table>
		   
			</div>
		</div>
		
	

		<div class="col-lg-9 col-md-8">
  
			<div class="card-box" style="background: url(assets/images/agsquare.png);">
				<span class="badge badge-<?=$cor;?> m-l-0" style="font-size: 16px;<?=$_stylecor ;?>" ><?=$situacao;?></span>
				
					<div class="comment" style="margin-left:0px;">  
					<div class="comment-text" >
						<div class="row" style="background-color: #e3e3e3;">
							<div class="col-md-12 col-lg-12" >
									<div style="height:30px;">
									
									
										<strong> Consumidor:</strong><?=$_parametros['nomecliente'];?> 
										
									</span>
									</div>                                 
									</div>  	
						
						
							</div>
					</div>  
					<div class="comment-text" >
						<div class="row">
							<div class="col-md-6 col-lg-6">                                                                 
							
									<div style="height:30px;">
									<i class="fa	 fa-smile-o fa-2x" alt="" ></i>
									
										<strong>  Atendente:</strong>
										<span style="color:#00a8e6"><?=$_parametros['atendentex'];?> 
									</span>
									</div>                                 
									</div>  	
						
									<div class="col-md-6 col-lg-6"> 								
									<div style="height:30px;">
										<i class="ion-settings fa-2x" alt="" ></i>								
											<strong>Assessor Ext:</strong>
											<span style="color:#00a8e6"><?=$nometecnico;?> 
											</span>
											<?php if($nometecnicooficina != '' ) 	 { ?>									
																								
																								<strong style="padding-left: 5px;">Tec.Oficina:</strong>
																								<span style="color:#00a8e6"><?=$nometecnicooficina;?> 
																								</span>
																					
																						<?php } ?> 
									</div>   
								                           
								
							</div>
							</div>
					</div>

					<?php if(trim($_parametros["pedidofabricante"]) != "") { ?>
					<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
						<div class="row">
							<div class="col-md-2 col-lg-2" style="text-align: center;">
							<span class="label label-purple" style="font-size:12px"><i class=" ti-package"></i> Pedido: <?=$_parametros["pedidofabricante"];?></span> </span>
							</div>
						</div>
					</div>
					<?php } ?>
					
						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
						<div class="row">
							<div class="col-md-2 col-lg-2" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtabertura"]); ?>
												<div class="comment-header">
													<strong > Data Abertura</strong>
												</div>
												<span style="background-color:#eff9fd">
													<?=$dtabertura[2]."/".$dtabertura[1]."/".$dtabertura[0]?>
												</span>
							</div>
							<div class="col-md-3 col-lg-3" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtprevista"]); ?>
												<div class="comment-header">
													<strong > Data Atendimento</strong>
												</div>
												<span style="background-color:#eff9fd">
													<?=$dtabertura[2]."/".$dtabertura[1]."/".$dtabertura[0]?>
													</span>
							</div>
							<div class="col-md-3 col-lg-3" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtencerramento"]); ?>
												<div class="comment-header">
													<strong > Data Conclusão</strong>
												</div>
												<span style="background-color:#eff9fd">
												<?=$dtabertura[2]."/".$dtabertura[1]."/".$dtabertura[0]?>
												</span>
							</div>
							<div class="col-md-2 col-lg-2" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtencerramento"]); ?>
												<div class="comment-header">
													<strong >Urgência</strong>
												</div>
												
												<?=$urgente?>
												
							</div>
							<div class="col-md-1 col-lg-1" style="text-align: center;">							
												<div class="comment-header">
													<strong >Impressa</strong>
												</div>												
												<?=$impressa?>												
							</div>
						</div>
								
						</div>
						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
										<div class="comment-header">
											<strong >DEFEITO RECLAMADO</strong>
										</div>
										<?=$_parametros["sintomas"];?>
						</div>
						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF">
										<div class="comment-header">
											<strong >DEFEITO CONSTATADO</strong>
										</div>
										<?=$_parametros["defeitoconstatado"];?>
						</div>

						 <div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
										<div class="comment-header">
											<strong >SERVIÇO EXECUTADO</strong>
										</div>
										<?=$_parametros["servicoexecutado"];?>
						</div>

						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
										<div class="comment-header">
											<strong >OBSERVAÇÃO</strong>
										</div>
										<?=$_parametros["observacao"];?>
						</div>
						
									
									<!--
									<div class="comment-text" style="color:#36404a;margin-top:5px">
										<div class="comment-header">
											<strong >FOTOS</strong>
										</div>
									  

										<div class="m-t-15">
											<a href="">
												<img src="assets/images/small/img1.jpg" class="thumb-md">
											</a>
											<a href="">
												<img src="assets/images/small/img2.jpg" class="thumb-md">
											</a>
											<a href="">
												<img src="assets/images/small/img3.jpg" class="thumb-md">
											</a>
										</div>
									</div>
									-->

					</div>
				</div>
		   
			</div>
			<div class="col-lg-9 col-md-8">
  
			
			<table class="table table-striped table-bordered" style="font-size: 11px;">
					<thead>
						<tr>        
						<th>Código</th>
						
						<th>Descrição</th>
							<th>Qtde</th>
							<th>Vlr R$</th>		
							<th>Total</th>												  
						</tr>
					</thead>
					<tbody>				
						
				<?php 
				if(trim($_parametros["_os"]) != "") {
				
					$consulta = $pdo->query("SELECT CODIGO_FABRICANTE,Minha_Descricao,Qtde_peca,Valor_Peca,peca_mo FROM ". $_SESSION['BASE'] .".chamadapeca
					left join ". $_SESSION['BASE'] .".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR      where Numero_OS = '".$_parametros["_os"]."'
					 ORDER BY TIPO_LANCAMENTO,Seq_item ASC");
					$retorno = $consulta->fetchAll();
					foreach ($retorno as $row) {		
						?>
						<tr>                                          
							<td><?= $row["CODIGO_FABRICANTE"] ?></td>
							<td><?= $row["Minha_Descricao"] ?></td>
							<td class="text-center"><?= $row["Qtde_peca"] ?></td>
							<td class="text-center"><?= number_format($row["Valor_Peca"], 2, ',', '.') ?></td>
                           <td class="text-center"><?= number_format($row["Qtde_peca"] * ($row["Valor_Peca"]+ $row["peca_mo"]), 2, ',', '.') ?></td>
							                                  
						</tr>
						<?php
					}
					}
				?>
				</tbody>
				</table>
		   
			</div>
		
		</div>
		</div>
  

<?php
}


if($_acao == 8 ) {   //FICHA DA os menu principal
	//print_r();  //numOS_ficha 


	//$_parametros["codigo"];
	//bucar dados da OS
	$sql = "Select 
	g_descricao,situacaoos_elx.DESCRICAO  as descB, cor_sit,IND_URGENTE,chamada.descricao as descA,
	Nome_Consumidor,Modelo,Serie,PNC,NUM_ORDEM_SERVICO,
	DATA_CHAMADA,DATA_ATEND_PREVISTO,DATA_ENCERRAMENTO,Defeito_Constatado,SERVICO_EXECUTADO,
	chamada.DEFEITO_RECLAMADO as def,	
	sitmob_cor,sitmob_descricao, situacaoos_elx.DESCRICAO as descsit ,
	at.usuario_APELIDO AS NOMEATENDENTE, t.usuario_APELIDO AS NOMETECNICO, O.usuario_APELIDO AS NOMETECNICOOFICINA,
	DESC_PECA,DESC_SERVICO,TAXA,sitmobOF_descricao,sitmobOF_cortable,IND_IMPRESSO,cor_sitcodigo,cor_sitcodigofonte,Num_Pedido,OBSERVACAO_atendimento
	from  ". $_SESSION['BASE'] .".chamada 
	left JOIN  ". $_SESSION['BASE'] .".usuario AS at ON at.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
	left JOIN  ". $_SESSION['BASE'] .".usuario AS t ON t.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
	left JOIN  ". $_SESSION['BASE'] .".usuario AS O ON O.usuario_CODIGOUSUARIO = COD_TEC_OFICINA
	left JOIN ". $_SESSION['BASE'] .". situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
	LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia ON GARANTIA = g_id               
	left JOIN  ". $_SESSION['BASE'] .".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
	left JOIN  ". $_SESSION['BASE'] .".fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
	left join  ". $_SESSION['BASE'] .".situacao_trackmob ON sitmob_id = SIT_TRACKMOB  
	left join  ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA 	
	WHERE CODIGO_CHAMADA = '".$_parametros['numOS_ficha']."'";

	$consulta=$pdo->query("$sql");
	$RETORNO = $consulta->fetchAll();
	
	foreach ($RETORNO as $row) {
		$DESTIPOGARANTIA = $row["g_descricao"];
		$nometecnico = $row["NOMETECNICO"];
		$nometecnicooficina = $row["NOMETECNICOOFICINA"];
		$_parametros['atendentex']= $row["NOMEATENDENTE"];
		$sit = $row["situacao"];
		$situacao = $row["descB"];
		$situacaooficina  = $row["sitmobOF_descricao"];
	
	
		$cor = $row["cor_sit"];
		$coroficina = $row["sitmobOF_cortable"];
		
		$urgente = $row['IND_URGENTE'];
		$impressa = $row['IND_IMPRESSO'];
		
		$_parametros["descricaoproduto"] = $row['descricao'];
		$_parametros['nomecliente'] = $row["Nome_Consumidor"];

		$_parametros["modelo"]= $row["Modelo"];
		$_parametros["serie"]= $row["Serie"];
		$_parametros["pnc"] = $row["PNC"];
		$_parametros["osfabricante"]= $row["NUM_ORDEM_SERVICO"];
	
	
		$_parametros["dtabertura"]= $row["DATA_CHAMADA"];
		$_parametros["dtprevista"]= $row["DATA_ATEND_PREVISTO"];
		$_parametros["dtencerramento"]= $row["DATA_ENCERRAMENTO"];
		$_parametros["sintomas"]= $row["def"];
		$_parametros["defeitoconstatado"]= $row["Defeito_Constatado"];
		$_parametros["servicoexecutado"]= $row["SERVICO_EXECUTADO"];
		$_parametros["observacao"] = trim($row["OBSERVACAO_atendimento"]);

		$vlrdesconto= $row["DESC_SERVICO"];
		$vlrdescontopeca= $row["DESC_PECA"];

		$vlrtaxa = $row["TAXA"];
		$PEDIDO = $row["Num_Pedido"];

		if($row["cor_sitcodigo"] != "") {
			$_stylecor = 'style="color:'.$rst["cor_sitcodigofonte"].'; background-color: '.$rst["cor_sitcodigo"].'"';
		}
	}
 


   $consulta = $pdo->query("SELECT sum(Valor_Peca*Qtde_peca) as pecas
    FROM ". $_SESSION['BASE'] .".chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '".$_parametros['numOS_ficha']."' ");
   $retorno = $consulta->fetchAll();
   foreach ($retorno as $row) {		
		$vlrtotal = $vlrtotal + $row['pecas'];
		$vlrpeca = $row['pecas'];
   }

   $consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
   FROM ". $_SESSION['BASE'] .".chamadapeca where Codigo_Peca_OS <> 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '".$_parametros['numOS_ficha']."' ");
   $retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {		
		$vlrmaoobra = $row['maoobra'];
		$vlrtotal = $vlrtotal + $row['maoobra'];
	}


	$consulta = $pdo->query("SELECT sum(peca_mo*Qtde_peca) as maoobra
   FROM ". $_SESSION['BASE'] .".chamadapeca where Codigo_Peca_OS = 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '".$_parametros['numOS_ficha']."' ");
   $retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {		
		$vlrtaxa = $vlrtaxa+$row['maoobra'];
	
	}

   $vlrtotal  = $vlrtotal  + $vlrtaxa - $vlrdesconto - $vlrdescontopeca;
   //$vlrtotal = number_format($vlrtotal,2,',','.');


if($urgente == 0){$urgente = "Não";  $corurgente = "#000";}else { $urgente = "Sim"; $corurgente = "red";}

if($impressa == 'N'){  $impressa  = "-"; } else { $impressa = '<span class="label label-inverse" style="font-size:12px">SIM</span>';} 
if($urgente == 0){ //$urgente = "Não"; 
		$urgente = '<span class="label label-info" style="font-size:12px">Não</span>';
	}else { //$urgente = "Sim";
		$urgente ='<span class="label label-danger" style="font-size:14px">Sim</span>';}
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	<h4 class="modal-title">Resumo</h4>
</div>

<div class="modal-body">

	<div class="row">
		<div class="col-md-4 col-lg-3">
			<div class="profile-detail card-box" style="background: url(assets/images/agsquare.png);">
				<div>                                 

					<ul class="list-inline status-list m-t-5">
						<li>
						<h3 class="text-primary m-b-5" style="cursor:pointer" onclick="_resumoconsultaOS('<?=$_parametros["numOS_ficha"];?>')">O.S <?=$_parametros["numOS_ficha"];?></h3>
						<p class=" font-13"><strong> <?=$DESTIPOGARANTIA;?></strong></p>
						
						
						
						</li>                                      
					</ul>                              

					<hr>
					
					<h4 class="text-uppercase font-600"><?=$_parametros["descricaoproduto"];?></h4>
					
					<div class="text-left;">
					<p class=" font-13"><strong>MODELO : <?=$_parametros["modelo"];?></strong></p>
						<p class=" font-13"><strong>SERIE : <?=$_parametros["serie"];?></strong></p>
						<p class=" font-13"><strong>PNC : <?=$_parametros["pnc"];?></strong></p>
						<?php if($_parametros["osfabricante"] != "") { ?>
							<p class=" font-13 text-danger"><strong>O.S Fabricante :  <?=$_parametros["osfabricante"];?></strong></p>
						<?php } ?>	
						
					</div>

				</div>

			</div>

			<div class="card-box" style="padding: 0px;background: url(assets/images/agsquare.png);">
			<div style="text-align: center;"> <h4 class="text-uppercase font-600">Total R$ <?=number_format($vlrtotal,2,',','.');?></h4></div>
		   
				<table class="table table-striped table-bordered">
					<thead>
						<tr>                                           
							<th>Descrição</th>
							<th>Vlr R$</th>													  
						</tr>
					</thead>
					<tbody>				
						<tr>                                          
							<td>Vlr Peças </td>
							<td style="text-align: right;"><span class="text-custom"><?=number_format($vlrpeca,2,',','.');?></span></td>                                           
						</tr>
						<?php if($vlrdescontopeca > 0) { ?>						
						<tr>                                          
							<td>Desc.Peças</td>
							<td style="text-align: right;"><span class="text-danger">- <?=number_format($vlrdescontopeca,2,',','.');?></span></td>                                          
						</tr>
						<?php } ?>
						<tr>                                          
							<td>Vlr Serviços </td>
							<td style="text-align: right;"><span class="text-custom"><?=number_format($vlrmaoobra,2,',','.');?></span></td>                                          
						</tr>
						<?php if($vlrdesconto > 0) { ?>	
						<tr>                                          
							<td>Desc.Serviços</td>
							<td style="text-align: right;"><span class="text-danger">-<?=number_format($vlrdesconto,2,',','.');?></span></td>                                          
						</tr>
						<?php } ?>
						<tr>                                          
							<td>Taxa </td>
							<td style="text-align: right;"><span class="text-custom"><?=number_format($vlrtaxa,2,',','.');?></span></td>                                          
						</tr>
					</tbody>
				</table>
		   
			</div>
		</div>


		<div class="col-lg-9 col-md-8">
  
			<div class="card-box" style="background: url(assets/images/agsquare.png);">
				<span class="badge badge-<?=$cor;?> m-l-0" style="font-size: 16px;<?=$_stylecor ;?>" ><?=$situacao;?></span>					
				<span style="padding-left:20px;cursor:unset;font-size: 12px;cursor:pointer;background-color:<?=$coroficina;?>;border:<?=$coroficina;?>;color:#FFFFFF" class="btn  btn-rounded waves-effect waves-light"><i class="icon-wrench"></i> <?=$situacaooficina;?></span>
				
				
					<div class="comment" style="margin-left:0px;">  
					<div class="comment-text" >
						<div class="row" style="background-color: #e3e3e3;">
							<div class="col-md-12 col-lg-12" >
									<div style="height:30px;">
									
									
										<strong> Consumidor:</strong><?=$_parametros['nomecliente'];?> 
										
									</span>
									</div>                                 
									</div>  	
						
						
							</div>
					</div>  
					<div class="comment-text" >
						<div class="row">
							<div class="col-md-6 col-lg-6">                                                                 
							
									<div style="height:30px;">
									<i class="fa	 fa-smile-o fa-2x" alt="" ></i>
									
										<strong>  Atendente:</strong>
										<span style="color:#00a8e6"><?=$_parametros['atendentex'];?> 
									</span>
									</div>                                 
									</div>  	
						
							<div class="col-md-6 col-lg-6"> 								
									<div style="height:30px;">
										<i class="ion-settings fa-2x" alt="" ></i>								
											<strong>Assessor Ext:</strong>
											<span style="color:#00a8e6"><?=$nometecnico;?> 
											</span>
									</div>   
									<?php if($nometecnicooficina != '' ) 	 { ?>									
									<div style="height:30px;">																
											<strong style="padding-left: 35px;">Tec.Oficina:</strong>
											<span style="color:#00a8e6"><?=$nometecnicooficina;?> 
											</span>
									</div>  
									<?php } ?>                            
								
							</div>
							</div>
					</div>
					<?php if(trim($PEDIDO) != "") { ?>
					<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
						<div class="row">
							<div class="col-md-2 col-lg-2" style="text-align: center;">
							<span class="label label-purple" style="font-size:12px"><i class=" ti-package"></i> Pedido: <?=$PEDIDO;?></span> </span>
							</div>
						</div>
					</div>
					<?php } ?>
				
						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
						<div class="row">
							<div class="col-md-2 col-lg-2" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtabertura"]); ?>
												<div class="comment-header">
													<strong > Data Abertura</strong>
												</div>
												<span style="background-color:#eff9fd">
													<?=$dtabertura[2]."/".$dtabertura[1]."/".$dtabertura[0]?>
												</span>
							</div>
							<div class="col-md-3 col-lg-3" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtprevista"]); ?>
												<div class="comment-header">
													<strong > Data Atendimento</strong>
												</div>
												<span style="background-color:#eff9fd">
													<?=$dtabertura[2]."/".$dtabertura[1]."/".$dtabertura[0]?>
													</span>
							</div>
							<div class="col-md-3 col-lg-3" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtencerramento"]); ?>
												<div class="comment-header">
													<strong > Data Conclusão</strong>
												</div>
												<span style="background-color:#eff9fd">
												<?=$dtabertura[2]."/".$dtabertura[1]."/".$dtabertura[0]?>
												</span>
							</div>
							<div class="col-md-2 col-lg-2" style="text-align: center;">
								<?php 
									$dtabertura = explode("-",$_parametros["dtencerramento"]); ?>
												<div class="comment-header">
													<strong >Urgência</strong>
												</div>
												<span style="color:<?=$corurgente;?>">
												<?=$urgente?>
												</span>
							</div>
							<div class="col-md-1 col-lg-1" style="text-align: center;">							
												<div class="comment-header">
													<strong >Impressa</strong>
												</div>												
												<?=$impressa?>												
							</div>
						</div>
								
						</div>
						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
										<div class="comment-header">
											<strong >DEFEITO RECLAMADO</strong>
										</div>
										<?=$_parametros["sintomas"];?>
						</div>
						<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF">
										<div class="comment-header">
											<strong >DEFEITO CONSTATADO</strong>
										</div>
										<?=$_parametros["defeitoconstatado"];?>
						</div>

						 <div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
										<div class="comment-header">
											<strong >SERVIÇO EXECUTADO</strong>
										</div>
										<?=$_parametros["servicoexecutado"];?>
						</div>
						<?php if($_parametros["observacao"] != ""){ ?>
							<div class="comment-text" style="color:#36404a;margin-top:5px;background-color:#FFF"">
											<div class="comment-header">
												<strong >OBSERVAÇÃO</strong>
											</div>
											<?=$_parametros["observacao"];?>
							</div>
							<?php } ?>
									
								

					</div>
				</div>
		   
			</div>
			<div class="col-lg-9 col-md-8">
  
			
  <table class="table table-striped table-bordered" style="font-size: 11px;">
		  <thead>
			  <tr>        
			  <th>Código</th>
			  
			  <th>Descrição</th>
				  <th>Qtde</th>
				  <th>Vlr R$</th>		
				  <th>Total</th>												  
			  </tr>
		  </thead>
		  <tbody>				
			  
	  <?php 
	  if(trim($_parametros["numOS_ficha"]) != "") {
		  $consulta = $pdo->query("SELECT CODIGO_FABRICANTE,Minha_Descricao,Qtde_peca,Valor_Peca,peca_mo FROM ". $_SESSION['BASE'] .".chamadapeca
		  left join ". $_SESSION['BASE'] .".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR      where Numero_OS = '".$_parametros["numOS_ficha"]."'
		   ORDER BY TIPO_LANCAMENTO,Seq_item ASC");
		  $retorno = $consulta->fetchAll();
		  foreach ($retorno as $row) {		
			  ?>
			  <tr>                                          
				  <td><?= $row["CODIGO_FABRICANTE"] ?></td>
				  <td><?= $row["Minha_Descricao"] ?></td>
				  <td class="text-center"><?= $row["Qtde_peca"] ?></td>
				  <td class="text-center"><?= number_format($row["Valor_Peca"], 2, ',', '.') ?></td>
				 <td class="text-center"><?= number_format($row["Qtde_peca"] * ($row["Valor_Peca"]+ $row["peca_mo"]), 2, ',', '.') ?></td>
													
			  </tr>
			  <?php
		  }
		  }
	  ?>
	  </tbody>
	  </table>
 
  </div>
		</div>
		</div>
  

<?php
}

if($_acao == 9 ) {   //PESQUISAR total parcelas financeiro
	?>
 <label>Parcela</label>
  <select name="_parcelaF" id="_parcelaF" class="form-control input-sm" >
	<?php 
	$query = ("SELECT QT_Parcelas  FROM ". $_SESSION['BASE'] .".tiporecebimpgto where id =  '".$_parametros['_tipopgto']."'");

	$consulta = $pdo->query("$query");
	$retorno = $consulta->fetchAll();
if($consulta->rowCount() > 0) {
	foreach ($retorno as $row) {			
		$qtde = $row["QT_Parcelas"];
	}
	
	if($qtde <= 0 ) {
		?>
			 <option value="1">1</option>
	<?php

	}else {
		$iContador = 0;
		while ($iContador < $qtde) {           
			$iContador = $iContador + 1;
		?>
	 <option value="<?=$iContador;?>"><?=$iContador;?></option>
	<?php
		}

	}
}else{
	?>
	 <option value="1">1</option>
	<?php

} ?>
 </select>
<?php 

	

	
}

?> 