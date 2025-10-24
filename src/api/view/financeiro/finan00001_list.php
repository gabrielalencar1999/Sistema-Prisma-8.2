<?php 
	use Database\MySQL;
	$pdo = MySQL::acessabd();

use Functions\Financeiro; 
	//$_SESSION['BASE'] = "bd_G000001";
//	echo(count($_parametrosFinanceiro));
	if(count($_parametros) == 0) {

		$_parametros = array(
			'_bd' =>$_SESSION['BASE'],
			'tipoData' =>'financeiro_emissao',
			'dataIni' =>$data_ini,
			'dataFim' =>$data_fim
			 
		);
	}else{

		$_bd = array(
			'_bd' =>$_SESSION['BASE']    
		);
		
		$_parametros =  array_merge($_parametros, $_bd);
		
	  
	};
	
	//print_r($_parametros);

	$_retorno = Financeiro::consultarFinanceiro($_parametros);
//	print_r($_retorno);

	
?>
<div class="card-box table-responsive" >
	<h4 class="m-t-0 header-title"><b>Lançamentos</b></h4>
	<br>
	<table id="datatable-buttons" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th></th>
				<th>Data</th>
				<th>Vencimento</th>
				<th>Tipo</th>
				<th>Descrição</th>
				<th>Valor Parcela</th>
				<th>Valor Pago</th>
				<th>Tipo Pgto</th>
				<th>N° Doc.</th>			
				<th>Forn/Cliente/Usuário</th>		
				<th>Categoria/sub...</th>
				
				
			</tr>
		</thead>
		<tbody>
		<?php
			foreach($_retorno as $value){
				$dataL = explode("/",$value->data);
				if($value->financeiro_tipo == 0){
					//receita
					$clss = "text-success";
					$sin = "";
					$valor = $value->financeiro_valor;
					$receita = $receita + $value->financeiro_valor;
					$valorRecebido = $valorRecebido + $value->financeiro_valorFim;

				}else{
					$clss = "text-danger";
					$sin = "-";
					$valor = $value->financeiro_valor;
					$despesa = $despesa + $value->financeiro_valor;
					$valorPago = $valorPago + $value->financeiro_valorFim;
				}
				
				
				
				if($cMes != $dataL[1]){
					if($dataL[1] == "01"){ $cMes = "01"; $descMes = "Janeiro/".$dataL[2]; }
					if($dataL[1] == "02"){ $cMes = "02"; $descMes = "Feveiro/".$dataL[2]; }
					if($dataL[1] == "03"){ $cMes = "03"; $descMes = "Março/".$dataL[2]; }
					if($dataL[1] == "04"){ $cMes = "04"; $descMes = "Abril/".$dataL[2]; }
					if($dataL[1] == "05"){ $cMes = "05"; $descMes = "Maio/".$dataL[2]; }
					if($dataL[1] == "06"){ $cMes = "06"; $descMes = "Junho/".$dataL[2]; }
					if($dataL[1] == "07"){ $cMes = "07"; $descMes = "Julho/".$dataL[2]; }
					if($dataL[1] == "08"){ $cMes = "08"; $descMes = "Agosto/".$dataL[2]; }
					if($dataL[1] == "09"){ $cMes = "09"; $descMes = "Setembro/".$dataL[2]; }
					if($dataL[1] == "10"){ $cMes = "10"; $descMes = "Outubro/".$dataL[2]; }
					if($dataL[1] == "11"){ $cMes = "11"; $descMes = "Novembro/".$dataL[2]; }
					if($dataL[1] == "12"){ $cMes = "12"; $descMes = "Dezembro/".$dataL[2]; }				
					
				}
				
				
				
				?>
				<tr>
					<td>
						<button type="text" class="btn btn-warning btn-sm" onclick="openModal('<?=$value->financeiro_id;?>')" data-toggle="modal" data-target="#modalLancamento"><i class="fa fa-pencil"></i></button>
					</td>
					<td><?php echo $value->data;?></td>
					<td><?php echo $value->vencimento;?></td>
					<td class="<?=$clss;?>"><?php if($value->financeiro_tipo == 0){ echo 'Receita';}else{ echo 'Despesa';}?></td>
					<td><?=$value->financeiro_historico;?></td>
					<td>
						<p class="<?=$clss;?>">
							<?=$sin;?> <?=number_format($valor,2,',','.');?>
							<?php if($value->financeiro_totalParcela > 1){ ?><span  style="color:#666"><br>&nbsp;&nbsp;<?php echo $value->financeiro_parcela." de ".$value->financeiro_totalParcela."</span>";  } ?>
						</p>
					</td>
					<td>
						<p class="<?=$clss;?>">
							<?=$sin;?> <?=number_format($value->financeiro_valorFim,2,',','.');?>
						</p>
					</td>
					<td><?=$value->tipoPagto;?></td>
					<td>
						<?php if($value->financeiro_grupo == 1 and $value->financeiro_grupo == 1 and $value->financeiro_documento != ""){
							$cod_cliente = $value->financeiro_codigoCliente;
							if($cod_cliente == ""){
								$cod_cliente = 1;
							}
							$atendimento = $cod_cliente.";".$value->financeiro_documento.";0";
							?><a style="cursor: pointer;" onclick="_000010('<?=$atendimento;?>')"><?=$value->financeiro_documento;?></a></a><?php
						}else{
							echo $value->financeiro_documento;
						}
						?>
						
					</td>			
					<td><?php if($value->financeiro_nome != ""){
							echo $value->financeiro_nome; 
						}else{
							echo($value->financeiro_nome);
							if($value->financeiro_fornecedor != 0){
								$sqx = "select * from ".$_SESSION['BASE'].".fabricante where CODIGO_FABRICANTE = '".$value->financeiro_fornecedor."'";
								$exec = $pdo->prepare("$sqx");    
								$exec->execute();
								foreach ($exec->fetchAll() as $result) {
									echo $result['NOME']; 
								}
							} 
						}?>
						</td>
					<td>
						<?php
						if($value->financeiro_grupo != 0){
							$sel = "select * from ".$_SESSION['BASE'].".categoria where id_categoria = '".$value->financeiro_grupo."'";
							$exe = $pdo->prepare("$sel");    
							$exe->execute();
							foreach ($exe->fetchAll() as $rst) {
								$categoria2 = $rst['descricao_categoria'];
								$corCategoria = $rst['cor_categoria'];
								$iconCategoria = $rst['icon_categoria'];
							}

							$sel = "select * from ".$_SESSION['BASE'].".subcategoria where id_subcategoria = '".$value->financeiro_subgrupo."'";
							$exe = $pdo->prepare("$sel");    
							$exe->execute();
							foreach ($exe->fetchAll() as $rst) {
								$subcategoria2 = $rst['descricao_subcategoria'];
							}
						
						
						
						?>	
						<div class="bar-widget" style="width:40px; float:left;">		
							<div class="iconbox" style="margin-right:0px; background-color:<?=$corCategoria;?>; width:30px !important; height:30px !important;">
								<i class="<?=$iconCategoria;?>" style=" line-height:30px !important; font-size:13px;"></i>
							</div>
						</div>
						<b><?=$categoria2;?></b><br>&nbsp;<?=$subcategoria2;?>
						<?php $subcategoria2 = "";} ?>
					</td>

				</tr>
				<?php				
			}
		?>
		</tbody>
	</table>
			<input type="hidden" value="<?=number_format($receita,2,',','.');?>" id="vlrTotalReceita">
			<input type="hidden" value="<?=number_format($despesa,2,',','.');?>" id="vlrTotalDespesa">
			<input type="hidden" value="<?=number_format($receita-$despesa,2,',','.');?>" id="vlrTotalBalanco">
			

		<script>
			setTimeout(function(){ 
				calculaTotal(); 
			}, 800);
			
			
		</script>
</div>
		<?php
			//A RECEBER
			$aReceber = $receita-$valorRecebido;
			if($aReceber < 0){
				$aReceber = 0;
			}
		
		?>	
<div class="card-box table-responsive" >
	<h4 class="m-t-0 header-title"><b>TOTAIS</b></h4>
	<br>
	<div class="row" style="font-size:16px;">
		<div class="col-sm-3" >
			<label class="text-success">Receitas:</label>
			R$ <?=number_format($receita,2,',','.');?>
			<hr>
		</div>
		<div class="col-sm-3">
			<label class="text-danger">Despesas:</label>
			R$ <?=number_format($despesa,2,',','.');?>
			<hr>
		</div>
		<div class="col-sm-3">
			<label>A Receber:</label>
			R$ <?=number_format($aReceber,2,',','.');?>
			<hr>
		</div>		
		<div class="col-sm-3">
			<label>A Pagar:</label>
			R$ <?=number_format($despesa-$valorPago,2,',','.');?>
			<hr>
		</div>
		<div class="col-sm-3">
			<label>Pago:</label>
			R$ <?=number_format($valorPago,2,',','.');?>
			<hr>
		</div>
		<div class="col-sm-3">
			<label>Recebido:</label>
			R$ <?=number_format($valorRecebido,2,',','.');?>
			<hr>
		</div>
		<div class="col-sm-3">
			<label class="text-custom">Balanço:</label>
			R$ <?=number_format($receita-$despesa,2,',','.');?>
			<hr>
		</div>		
	</div>
</div>