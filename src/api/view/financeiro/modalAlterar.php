	<?php 
		use Database\MySQL;
		use Functions\Financeiro; 
		$pdo = MySQL::acessabd();
		
		$id = $_POST['variable'];
		date_default_timezone_set('America/Sao_Paulo');


		function geraCodigoBarra($numero){
			$fino = 1;
			$largo = 3;
			$altura = 60;
			
			$barcodes[0] = '00110';
			$barcodes[1] = '10001';
			$barcodes[2] = '01001';
			$barcodes[3] = '11000';
			$barcodes[4] = '00101';
			$barcodes[5] = '10100';
			$barcodes[6] = '01100';
			$barcodes[7] = '00011';
			$barcodes[8] = '10010';
			$barcodes[9] = '01010';
			
			for($f1 = 9; $f1 >= 0; $f1--){
				for($f2 = 9; $f2 >= 0; $f2--){
					$f = ($f1*10)+$f2;
					$texto = '';
					for($i = 1; $i < 6; $i++){
						$texto .= substr($barcodes[$f1], ($i-1), 1).substr($barcodes[$f2] ,($i-1), 1);
					}
					$barcodes[$f] = $texto;
				}
			}
			
			echo '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
			echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
			echo '<img src="imagens/p.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
			echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
			
			echo '<img ';
			
			$texto = $numero;
			
			if((strlen($texto) % 2) <> 0){
				$texto = '0'.$texto;
			}
			
			while(strlen($texto) > 0){
				$i = round(substr($texto, 0, 2));
				$texto = substr($texto, strlen($texto)-(strlen($texto)-2), (strlen($texto)-2));
				
				if(isset($barcodes[$i])){
					$f = $barcodes[$i];
				}
				
				for($i = 1; $i < 11; $i+=2){
					if(substr($f, ($i-1), 1) == '0'){
						  $f1 = $fino ;
					  }else{
						  $f1 = $largo ;
					  }
					  
					  echo 'src="imagens/p.gif" width="'.$f1.'" height="'.$altura.'" border="0">';
					  echo '<img ';
					  
					  if(substr($f, $i, 1) == '0'){
						$f2 = $fino ;
					}else{
						$f2 = $largo ;
					}
					
					echo 'src="imagens/b.gif" width="'.$f2.'" height="'.$altura.'" border="0">';
					echo '<img ';
				}
			}
			echo 'src="imagens/p.gif" width="'.$largo.'" height="'.$altura.'" border="0" />';
			echo '<img src="imagens/b.gif" width="'.$fino.'" height="'.$altura.'" border="0" />';
			echo '<img src="imagens/p.gif" width="1" height="'.$altura.'" border="0" />';
		}


	if($id != ""){ 	
	
	//------------------ALTERAR REGISTRO FINANCEIRO-----------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------------------------------------	
		$sql="select *,DATE_FORMAT(financeiro_emissao , '%Y-%m-%d') as dateEmissao, DATE_FORMAT(financeiro_vencimento , '%Y-%m-%d') as dateVencimento ,DATE_FORMAT(financeiro_dataFim , '%Y-%m-%d') as dataPago from ".$_SESSION['BASE'].".financeiro where financeiro_id = '$id'";
		$stm = $pdo->prepare("$sql");    
        $stm->execute();
		foreach ($stm->fetchAll() as $value) {
			$descricao = $value['financeiro_historico'];
			$financeiro_empresa = $value['financeiro_empresa'];
			$financeiro_nome = $value['financeiro_nome'];
			$financeiro_tipo = $value['financeiro_tipo'];
			$financeiro_emissao= $value['dateEmissao'];
			$financeiro_vencimento = $value['dateVencimento'];
			$financeiro_totalParcela = $value['financeiro_totalParcela'];
			$financeiro_valor = $value['financeiro_valor'];
			$financeiro_valorJuros = $value['financeiro_valorJuros'];
			$financeiro_valorFim = $value['financeiro_valorFim'];
			$financeiro_tipoPagamento = $value['financeiro_tipoPagamento'];
			$financeiro_obs = $value['financeiro_obs'];
			$financeiro_documento = $value['financeiro_documento'];
			$financeiro_identificador = $value['financeiro_identificador'];
			$_documento = $value['Documento'];
			$financeiro_valorDesconto = $value['financeiro_valorDesconto'];
			$financeiro_tipoQuem = $value['financeiro_tipoQuem'];
			$financeiro_lancamentoCC = $value['financeiro_lancamentoCC'];
			$cclivro = $value['financeiro_caixa'];
			$financeiro_codigoCliente = $value['financeiro_codigoCliente'];
			$financeiro_descricaoBoleto = $value['financeiro_descricaoBoleto'];
			$financeiro_dataFim = $value['dataPago'];
			$situacao = $value['financeiro_situacaoID'];
			
			if($financeiro_tipo == 0){
				$descricaoQuem = "Receber de...";
			}else{
				$descricaoQuem = "Pagar o...";
			}
			
			//categoria--------------------------------------------
			$variable = $value['financeiro_grupo'];
			$financeiro_grupo = $value['financeiro_grupo'];
			$financeiro_subgrupo = $value['financeiro_subgrupo'];
			//-----------------------------------------------------
		}
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Alterar Lançamento [<b><?=$id;?></b>]</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-3">
				<label class="control-label">Tipo Lançamento* </label>
				<select class="form-control" name="financeiro_tipo" id="financeiro_tipo" onchange="selTipo(this.value)">
					<option value="0" <?php if($financeiro_tipo == 0){ echo'selected="selected"';}?>>Receita</option>
					<option value="1" <?php if($financeiro_tipo == 1){ echo'selected="selected"';}?>>Despesa</option>
				</select>
			</div> 
			<div class="col-md-6">
				<label class="control-label">Descrição*</label>    
				<input type="text" class="form-control" name="financeiro_historico" id="financeiro_historico" value="<?=$descricao;?>">
				
				<input type="hidden" class="form-control" name="idconsumidor" id="idconsumidor" value="<?=$financeiro_codigoCliente;?>">
			</div>  												
			<div class="col-md-3">
				<label class="control-label">N° Documento</label>    
				<input type="text" class="form-control" name="financeiro_documento" id="financeiro_documento" value="<?=$financeiro_documento;?>">
				<input type="hidden" class="form-control" name="Documento" id="Documento" value="<?=$_documento;?>">
				<input type="hidden" class="form-control" name="financeiro_identificador" id="financeiro_identificador" value="<?=$financeiro_identificador;?>">
				
			
			</div>   

		</div>  
		<br>
		<div class="row">  
			<div class="col-md-12">
				<label class="control-label">Categoria</label>  
			</div>
		</div>
		<div class="scrolling-wrapper" id="divCategoria">  	
			<?php require_once('../../api/view/financeiro/finanCategoria.php'); ?>
		</div>
		<div class="row">  
			<div class="col-md-12 boxC" style="display:none;">
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label">Nova Categoria</label>    
						<input class="form-control" name="newCategoria" id="newCategoria" placeholder="Descricao">
					</div>  
					<div class="col-sm-4">
						<label class="control-label">Tipo Categoria</label>    
						<select class="form-control" name="tipoCategoria" id="tipoCategoria">
							<option value="2">Ambos</option>
							<option value="0">Receita</option>
							<option value="1">Despesa</option>
						</select>
					</div>                                            
				</div> 
				<div class="row">
					<div class="col-sm-12"><br>
						<label class="control-label">Cor</label><br>
						<?php require_once('../../api/view/financeiro/finanCategoriaCor.php'); ?>
					</div>   
				</div>
				<div class="row">
					<div class="col-sm-12"><br>
						<label class="control-label">Icones</label><br>
						<?php require_once('../../api/view/financeiro/finanCategoriaIcon.php'); ?>
					</div>   
				</div>
				<div class="row">
					<div class="col-sm-12"><br>
						<button type="button" class="btn btn-default nc" onclick="NC()">Cancelar</button>
						<button type="button" id="_00005" class="btn btn-success">Criar</button>
					</div>   
				</div>
			</div>
			<div class="col-md-12 boxD" <?php if( $financeiro_grupo == "0"){ ?>style="display:none;" <?php } ?>>
				<?php require_once('../../api/view/financeiro/finanSubcategoria.php'); ?>
			</div>
		</div>
		<br>		
		<div class="row"> 	
			<div class="col-md-3">
				<label class="control-label" id="quem"><?=$descricaoQuem;?></label>
				
				 <input type="hidden" class="form-control" id="financeiro_lancamentoCC" name="financeiro_lancamentoCC" value="<?=$financeiro_lancamentoCC;?>">					
				<select class="form-control" name="financeiro_tipoQuem" id="financeiro_tipoQuem"  onchange="seltipoQuem(this.value)">
					<option value=""></option>					
					<option value="1" <?php if($financeiro_tipoQuem == 1){ echo 'selected="selected"';}?>>Consumidor</option>
					<option value="2" <?php if($financeiro_tipoQuem == 2){ echo 'selected="selected"';}?>>Fornecedor</option>
					<option value="3" <?php if($financeiro_tipoQuem == 3){ echo 'selected="selected"';}?>>Usuário</option>
				</select>
			</div>			
			<div class="col-md-9" id="tipoQm">
			<?php 
				if($financeiro_tipoQuem == ""){ ?>
					<label class="control-label">Nome</label>    
					<input type="text" class="form-control" id="" name="" placeholder="selecione para escolher" disabled>					
			<?php	
				}else{
					$_parametrosQuem = array(
						'_bd' =>$_SESSION['BASE']
					);
					
					$_parametrosQuem['tipo'] = $financeiro_tipoQuem;
					$_parametrosQuem['idconsumidor'] = $financeiro_codigoCliente;
					$_retornoTipoQm = Financeiro::buscaQuem($_parametrosQuem);
									
					?>
						<label class="control-label">Nome</label>    
						<select type="text" class="form-control" name="financeiro_codigoCliente" id="financeiro_codigoCliente" onchange="texto()">
						<option value="" ></option>
						<?php foreach($_retornoTipoQm as $valuee){ 

							if($financeiro_tipoQuem == 1){
								$idQuem = $valuee->CODIGO_CONSUMIDOR;
								$descricaoQuem = $valuee->Nome_Consumidor;
							}
							if($financeiro_tipoQuem == 2){
								$idQuem = $valuee->CODIGO_FABRICANTE;
								$descricaoQuem = $valuee->NOME." (".$valuee->CNPJ.")";
							}
							if($financeiro_tipoQuem == 3){
								$idQuem = $valuee->usuario_CODIGOUSUARIO;
								$descricaoQuem = $valuee->usuario_APELIDO;
							}
							
							if($idQuem == $financeiro_codigoCliente){ $usuarioAnterior = $idQuem; }
						?>
							<option <?php if($idQuem == $financeiro_codigoCliente){ echo'selected="selected"';}?> value="<?=$idQuem;?>" ><?=$descricaoQuem?></option>
						<?php } ?>
						<select>
					<?php
				}

			?>

			</div> 
		</div>										
		<br>
		<div class="row">  
			<div class="col-md-4">
				<label class="control-label">Data Vencimento*</label>
				<input type="date" class="form-control"  name="financeiro_vencimento"  id="financeiro_vencimento" value="<?=$financeiro_vencimento;?>">
			</div>
			<div class="col-md-4">
				<label class="control-label">Total de Parcelas*</label>                                                    
				<input type="text" class="form-control" name="financeiro_totalParcela" id="financeiro_totalParcela" placeholder="1 no máximo 99" value="<?=$financeiro_totalParcela;?>" disabled="disabled">
			</div>                                              
			<div class="col-md-4">
				<label class="control-label">Valor por Parcela</label>    
				<input type="tel" onKeyUp="formatarMoeda('financeiro_valor')" class="form-control" name="financeiro_valor" id="financeiro_valor" value="<?=number_format($financeiro_valor,2,',','.');?>">
			</div>                                              
		</div>
		<br>
		<div class="row">  
			<div class="col-md-4 marg">
				<label class="control-label">Tipo Pagamento</label>   
				<?php require_once('../../api/view/financeiro/tipoPagamento.php'); ?>											
			</div> 
			<div class="col-md-4 marg">
				<label class="control-label">Data Pago</label>    
				<input type="date" class="form-control"  name="financeiro_dataFim"  id="financeiro_dataFim" value="<?=$financeiro_dataFim;?>">
			</div>  											
			<div class="col-md-4 marg">
				<label class="control-label">Valor Pago</label>    
				<input type="tel" onKeyUp="formatarMoeda('financeiro_valorFim')" class="form-control" name="financeiro_valorFim" id="financeiro_valorFim" value="<?=number_format($financeiro_valorFim,2,',','.');?>">
			</div> 	
			
		</div>
	
		<div class="row">  
			<div class="col-md-8 marg">
				<label class="control-label">Conta Bancária Pagamento</label>  
					<?php $sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero ";
					
					$consulta = $pdo->query($sql);
					$retorno = $consulta->fetchAll() ?>

					<select type="text" class="form-control" name="financeiro_caixa" id="financeiro_caixa" >
						<option value="0"></option>
							<?php foreach ($retorno as $row){ ?>
					
							<option <?php if($cclivro == $row['Livro_Numero']){ echo'selected="selected"';}?> value="<?=$row['Livro_Numero'];?>" ><?=$row['Descricao']?></option><?php } ?>
							
						<select>										
			</div> 
		</div>

		<div class="row">  
			<div class="col-md-12">
				<a class="option" onclick="maisOpcoes('1')">Mais Opções <i class="ti-angle-down"></i></a>    
			</div>                                            
		</div>
		<div class="row">
			
			<div class="col-md-12 boxF" style="display:none;">
			<div class="col-md-4 marg">
				<label class="control-label">Data Emissão*</label>
				<input type="date" class="form-control"  name="financeiro_emissao"  id="financeiro_emissao" value="<?=$financeiro_emissao;?>">
			</div>	
				<div class="col-md-8 marg" >	
							
									<label>Empresa:</label>
									<?php
									$consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa order by empresa_nome");
									$retorno = $consulta->fetchAll();
									?>
									<select name="financeiro_empresa" id="financeiro_empresa" class="form-control">
										<option value="0">Todos</option>
										<?php foreach ($retorno as $row): ?>
											<option value="<?=$row['empresa_id']?>" <?php if($financeiro_empresa == $row['empresa_id']){ echo "selected"; } ?>><?=$row['empresa_nome']?></option>
										<?php endforeach; ?>
									</select>
												
					</div>
				<div class="col-md-12">
					<label class="control-label">Observação</label>    
					<textarea class="form-control" name="financeiro_obs" id="financeiro_obs"><?=$financeiro_obs;?></textarea>
				</div>                                            

				<div class="col-md-4 marg">
					<label class="control-label">Valor do Desconto</label>    
					<input type="tel" onKeyUp="formatarMoeda('financeiro_valorDesconto')" class="form-control" name="financeiro_valorDesconto" id="financeiro_valorDesconto" value="<?=number_format($financeiro_valorDesconto,2,',','.');?>">
				</div> 																												
				<div class="col-md-4 marg">
					<label class="control-label">Valor C/Juros</label>    
					<input type="tel" onKeyUp="formatarMoeda('financeiro_valorJuros')" class="form-control" name="financeiro_valorJuros" id="financeiro_valorJuros" value="<?=number_format($financeiro_valorJuros,2,',','.');?>">
				</div>  

				<div class="col-md-4 marg" style="padding-top:26px;">
					<?php if($situacao == 0){?>
						<button type="button" class="btn btn-danger" onclick="cancelar('<?=$id;?>')">Cancelar Lançamento</button>
					<?php }else{ /*<button type="button" class="btn btn-primary" onclick="desCancelar('<?=$id;?>')">Recuperar Lançamento</button> */?>
						
					<?php }?>
				</div> 
			
			



			</div>											
		</div>											
	</div>
	<input type="hidden" name="usuarioAnterior" id="usuarioAnterior" value="<?=$usuarioAnterior;?>">
	<input type="hidden" name="verificaCancelado" id="verificaCancelado" value="<?=$situacao;?>">
	<input type="hidden" name="financeiro_situacaoID" id="financeiro_situacaoID" value="<?=$situacao;?>">
	<input type="hidden" name="financeiro_nome" id="financeiro_nome" value="<?=$financeiro_nome;?>">
	<input type="hidden" name="financeiro_grupo" id="financeiro_grupo" value="<?=$financeiro_grupo;?>">
	<input type="hidden" name="financeiro_subgrupo" id="financeiro_subgrupo" value="<?=$financeiro_subgrupo;?>">
	<input type="hidden" name="financeiro_id" id="financeiro_id" value="<?=$id;?>">
	<div class="modal-footer">
		<button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
		<button type="button" id="alterar" class="btn btn-success waves-effect waves-light">Salvar</button>
	</div>
	<script>
		var search1 = $(alterar).click(function(){   		
				var $_keyid =   "ALREGFIN1";    			
				var dados = $("#form3 :input").serializeArray();
				dados = JSON.stringify(dados);
				
				$.post("page_return.php", {_keyform:$_keyid, dados:dados}, function(result){	
				
					var $_keyid =   "_Fl00005";    			
					var dados = $("#form2 :input").serializeArray();
					dados = JSON.stringify(dados);
					
					$.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){	
					
						if(result == 1){ }else{
													
							$("#resposta").html(result);						  
							handleDataTableButtons();
							//$('#modalLancamento').modal('hide');
							$("#divAlterar").html(
							'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p><h4> Lançamento alterado com <strong>Sucesso !!!</strong></h4></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/gif_0002.gif" alt="image" class="img-responsive" width="250"/></div></div></div>'
							);
						}
					});
					
				});			
		});		
	</script>
	<?php }else{
	//------------------INSERIR NOVO REGISTRO FINANCEIRO------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------------------------------------	
	
		$data = date('Y-m-d');
		?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Novo Lançamento</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-3">
					<label class="control-label">Tipo Lançamento* </label>
					<select class="form-control" name="financeiro_tipo" id="financeiro_tipo" onchange="selTipo(this.value)">
						<option value="0">Receita</option>
						<option value="1">Despesa</option>
					</select>
				</div> 
				<div class="col-md-6">
					<label class="control-label">Descrição*</label>    
					<input type="text" class="form-control" name="financeiro_historico" id="financeiro_historico">
				</div>  												
				<div class="col-md-3">
					<label class="control-label">N° Documento</label>    
					<input type="tel" class="form-control" name="financeiro_documento" id="financeiro_documento">
				</div>    

			</div>  
			<br>
			<div class="row">  
				<div class="col-md-12">
					<label class="control-label">Selecione uma Categoria</label>  
				</div>
			</div>
			<div class="scrolling-wrapper" id="divCategoria">  	
				<?php 
					$x = "1";
					require_once('../../api/view/financeiro/finanCategoria.php'); ?>
			</div>
			<div class="row">  
				<div class="col-md-12 boxC" style="display:none;">
					<div class="row">
						<div class="col-sm-8">
							<label class="control-label">Nova Categoria</label>    
							<input class="form-control" name="newCategoria" id="newCategoria" placeholder="Descricao">
						</div>  
						<div class="col-sm-4">
							<label class="control-label">Tipo Categoria</label>    
							<select class="form-control" name="tipoCategoria" id="tipoCategoria">
								<option value="2">Ambos</option>
								<option value="0">Receita</option>
								<option value="1">Despesa</option>
							</select>
						</div>                                            
					</div> 
					<div class="row">
						<div class="col-sm-12"><br>
							<label class="control-label">Cor</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaCor.php'); ?>
						</div>   
					</div>
					<div class="row">
						<div class="col-sm-12"><br>
							<label class="control-label">Icones</label><br>
							<?php require_once('../../api/view/financeiro/finanCategoriaIcon.php'); ?>
						</div>   
					</div>
					<div class="row">
						<div class="col-sm-12"><br>
							<button type="button" class="btn btn-default nc"  onclick="NC()">Cancelar</button>
							<button type="button" id="_00005" class="btn btn-success">Criar</button>
						</div>   
					</div>
				</div>
				<div class="col-md-12 boxD" style="display:none;">
					<?php require_once('../../api/view/financeiro/finanSubcategoria.php'); ?>
				</div>
			</div>	
			<br>
			<div class="row"> 	
			<div class="col-md-3">
				<label class="control-label" id="quem">Receber de...</label>
				<select class="form-control" name="financeiro_tipoQuem" id="financeiro_tipoQuem"  onchange="seltipoQuem(this.value)">
					<option value=""></option>
					<option value="1">Consumidor</option>
					<option value="2">Fornecedor</option>
					<option value="3">Usuário</option>
				</select>
			</div>			
				<div class="col-md-9" id="tipoQm">
					<label class="control-label">Nome</label> 
					<input type="text" class="form-control" id="" name="" placeholder="selecione..." disabled>
				</div> 
			</div>										
			<br>
			<div class="row">  
				<div class="col-md-4">
					<label class="control-label">Data Vencimento*</label>
					<input type="date" class="form-control"  name="financeiro_vencimento"  id="financeiro_vencimento" value="<?=$data;?>">
				</div>
				<div class="col-md-4">
					<label class="control-label">Total de Parcelas*</label>                                                    
					<input type="text" class="form-control" name="financeiro_totalParcela" id="financeiro_totalParcela" placeholder="1 no máximo 99" maxlength="2">
				</div>                                              
				<div class="col-md-4">
					<label class="control-label">Valor por Parcela</label>    
					<input type="tel" onKeyUp="formatarMoeda('financeiro_valor')" class="form-control" name="financeiro_valor" id="financeiro_valor">
				</div>                                              
			</div>
			<br>
			<div class="row">  
				<div class="col-md-4 marg">
					<label class="control-label">Tipo Pagamento</label>   
					<?php require_once('../../api/view/financeiro/tipoPagamento.php'); ?>											
				</div> 
				<div class="col-md-4 marg">
					<label class="control-label">Data Pago</label>    
					<input type="date" class="form-control"  name="financeiro_dataFim"  id="financeiro_dataFim">
				</div>  												
				<div class="col-md-4 marg">
					<label class="control-label">Valor Pago</label>    
					<input type="tel" onKeyUp="formatarMoeda('financeiro_valorFim')" class="form-control" name="financeiro_valorFim" id="financeiro_valorFim">
				</div> 	
				
			</div>

				<div class="row">  
			<div class="col-md-8 marg">
				<label class="control-label">Conta Bancária Pagamento</label>  
					<?php $sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero ";
					
					$consulta = $pdo->query($sql);
					$retorno = $consulta->fetchAll() ?>

					<select type="text" class="form-control" name="financeiro_caixa" id="financeiro_caixa" >
						<option value="0"></option>
							<?php foreach ($retorno as $row){ ?>
					
							<option <?php if($cclivro == $row['Livro_Numero']){ echo'selected="selected"';}?> value="<?=$row['Livro_Numero'];?>" ><?=$row['Descricao']?></option><?php } ?>
							
						<select>										
			</div> 
		</div>

			<div class="row">  
				<div class="col-md-12">
					<a class="option" onclick="maisOpcoes('1')">Mais Opções <i class="ti-angle-down"></i></a>    
				</div>                                            
			</div>
			<div class="row">
				<div class="col-md-12 boxF" style="display:none;"> 
				<div class="col-md-4 marg">
				<label class="control-label">Data Emissão*</label>
				<input type="date" class="form-control"  name="financeiro_emissao"  id="financeiro_emissao" value="<?=$data;?>">
			</div>	
				<div class="col-md-8 marg" >			
							
				<label>Empresa:</label>
				<?php
				$consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa order by empresa_nome");
				$retorno = $consulta->fetchAll();
				?>
				<select name="financeiro_empresa" id="financeiro_empresa" class="form-control">
					<option value="0">Todos</option>
					<?php foreach ($retorno as $row): ?>
						<option value="<?=$row['empresa_id']?>" <?php if($financeiro_empresa == $row['empresa_id']){ echo "selected"; } ?>><?=$row['empresa_nome']?></option>
					<?php endforeach; ?>
				</select>
							
</div>
					<div class="col-md-12">
						<label class="control-label">Observação</label>    
						<textarea class="form-control" name="financeiro_obs" id="financeiro_obs"></textarea>
					</div>                                            
					<br>
					<div class="col-md-4 marg">
						<label class="control-label">Valor do Desconto</label>    
						<input type="tel" onKeyUp="formatarMoeda('financeiro_valorDesconto')" class="form-control" name="financeiro_valorDesconto" id="financeiro_valorDesconto">
					</div> 																												
					<div class="col-md-4 marg">
						<label class="control-label">Valor C/Juros</label>    
						<input type="tel" onKeyUp="formatarMoeda('financeiro_valorJuros')" class="form-control" name="financeiro_valorJuros" id="financeiro_valorJuros">
					</div>  



				</div>											
			</div>											
		</div>
		<input type="hidden" name="financeiro_nome" id="financeiro_nome" value="">
		<input type="hidden" name="financeiro_grupo" id="financeiro_grupo" value="">
		<input type="hidden" name="financeiro_subgrupo" id="financeiro_subgrupo" value="">
		<div class="modal-footer">
			<button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
			<button type="button" id="incluir" class="btn btn-success waves-effect waves-light">Incluir</button>
		</div>	
		<script>	
			var search = $(incluir).click(function(){      
					var $_keyid =   "ADREGFIN1";    			
					var dados = $("#form3 :input").serializeArray();
					dados = JSON.stringify(dados);
					
					$.post("page_return.php", {_keyform:$_keyid, dados:dados}, function(result){	
								;
						var $_keyid =   "_Fl00005";    			
						var dados = $("#form2 :input").serializeArray();
						dados = JSON.stringify(dados);
						
						$.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){		
							if(result == 1){ }else{
												;
								$("#resposta").html(result);						  
								handleDataTableButtons();
								//$('#modalLancamento').modal('hide');
								$("#divAlterar").html(
								'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p><h4> Lançamento incluído com <strong>Sucesso !!!</strong></h4></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/gif_0001.gif" alt="image" class="img-responsive" width="200"/></div></div></div>'
								);								
							}
						});
					});			
			});				
		</script>
		
	
	<?php } ?>
	
	<script>
		var search2 = $(_00005).click(function(){       
					var $_keyid =   "_Fl00007";    			
					var dados = $("#form3 :input").serializeArray();
					dados = JSON.stringify(dados);
					
					var newCategoria = $("#newCategoria").val();
					var tipoCategoria = $("#tipoCategoria").val();
					var cor = $("#cor").val();
					var icon = $("#icon").val();
					
					if(newCategoria == ""){
						alert("Necessário preencher categoria");
					}else{
						if(tipoCategoria == ""){
							alert("Necessário selecionar Tipo Categoria");
						}else{
							if(cor == ""){
								alert("Necessário selecionar a cor");
							}else{
								if(icon == ""){
									alert("Necessário selecionar um icone");
								}else{
									$.post("page_return.php", {_keyform:$_keyid,dados:dados , acao:tipoCategoria}, function(result){		
										if(result == 1){ 
										
										}else{
											$("#divCategoria").html(result);
											$( ".boxC" ).toggle( "slow", function() {
												// Animation complete.
											});
											
											$("#newCategoria").val("");
											 $("#tipoCategoria").val("");
											 $("#cor").val("");
											 $("#icon").val("");
											$(".catCor").removeClass("selCor");
											$(".styleIcon").removeClass("styleIcon2");		

										}
									});
								}
							}
						}
					}
					/*					
					*/
					
			});
	function texto() {
		var option = $('#financeiro_codigoCliente').find(":selected").text();
		$("#financeiro_nome").val(option);
		
	}
	function gerar_codigoDeBarra(){
		var codigo = $("#financeiro_descricaoBoleto").val();
		var $_keyid =   "f_00004";   
		$.post("page_return.php", {_keyform:$_keyid,codigo:codigo , acao:"gerarCodigoBarras"}, function(result){
			alert();
			$("#resp_boleto").html(result);
		});
	}
	</script>
	