<?php 
	session_start();
	date_default_timezone_set('America/Sao_Paulo');
	
	require_once('../../../api/config/config.inc.php');
	require '../../../api/vendor/autoload.php';
	include("../../../api/config/iconexao.php");   
	
	use Functions\NFeService;
	use Database\MySQL;
	use NFePHP\NFe\Common\Standardize;
	use Functions\Atividade;

	$pdo = MySQL::acessabd();
	date_default_timezone_set('America/Sao_Paulo');

	function SomarData($data, $dias, $meses, $ano)
{
   //passe a data no formato dd/mm/yyyy 
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
     $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

$_SESSION['id_caixa'] = 1;
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

	$acao = $_POST['acao'];

	$empresa = '1';
	

	$_idfrefGO = $_POST['ref'];
	$_idfref = base64_decode($_idfrefGO);

	$_idfref = explode('-',$_idfref);
	if($_idfref[1] != "") { 
		$_numeropedido = $_idfref[1];
		$numero_pedido= $_idfref[1];
	}else{
		$_numeropedido = $_SESSION['numberPedido'];
		$numero_pedido= $_SESSION['numberPedido'];
	}
	/*
	if($_POST['ref'] != ""){
		$_numeropedido = $_POST['ref'];	
		$_SESSION['numberPedido'] = $_numeropedido;
		$_idpedido = $_numeropedido;
	}
*/
$_idpedido = $_numeropedido;
	

	$_idcaixa = 1;
	$usuario = $_SESSION['tecnico'];

	//empresa_vizCodInt codigo visualização interno
		$query = ("SELECT empresa_vizCodInt,empresa_validaestoque from  " . $_SESSION['BASE'] . ".parametro  ");
		$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
		while ($rst = mysqli_fetch_array($result)) {
			$_vizCodInterno = $rst["empresa_vizCodInt"];	
			$_validaestoque = $rst["empresa_validaestoque"];		
		}

	$consultaLinha = $pdo->query("Select CODIGO_CLIENTE,Cod_Situacao from ". $_SESSION['BASE'] .".saidaestoque
	where  NUMERO = '".$_idpedido."' AND num_livro = '".$_idcaixa."'");
	$retornoLinha = $consultaLinha->fetchAll();
	foreach ($retornoLinha as $row_a) {        
		$_idcliente = $row_a['CODIGO_CLIENTE'];
		$_situacaoPedido = $row_a['Cod_Situacao'];
	}


	if($acao == 5){
		//cancelar operacao cartao
		$_ACAOCARTAO = 9; //EXCLUI TRANSAÇÃO REMOTA
		// $_pay_uuid = $variable;
		 require_once('enviopayevofunction.php'); 	
	     require_once('enviopayevo.php'); 
		 if($_retstatus == 0){
			  ?>
			<div class="row">
								<div class="col-sm-12" align="center">
								<i class="fa fa-5x fa-check-circle-o"></i>
														
								</div>
								</div>  
								<div class="col-sm-12" align="center">			
								<p><strong>Operação Remota </strong>Cancelada !!!</p>
								<p></p>
							</div>					
						
							<div style="padding: 17px;" align="center">
								<button type="button" class="btn btn-default waves-effect"  onclick="finalizaVenda()" >Fechar</button>
								
							</div>
		<?php
	 
		 }

		exit();
	}
	
	if($acao == "lerCodigoBarra" or $acao == "ProdutoNaoCadastrado" ){
		$cod = $_POST["cod"];
		$qtde = $_POST["qtde"];
		if($qtde == ""){
			$qtde = 1;
		}
		$qtde = str_replace(".", "", $qtde);
		$qtde = str_replace(",", ".",$qtde);


		//$_SESSION['i'] = $_SESSION['i'] + 1;		
			//busca valor do pedido
			$sql2="SELECT ITEM FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE NUMERO = '$_numeropedido' order by ITEM DESC limit 1 ";
			$stm2 = $pdo->prepare($sql2);
			$stm2->execute();
			foreach ($stm2->fetchAll() as $rst) { 
				$ITEM = $rst['ITEM'];
			}

			$ITEM = $ITEM + 1;

			//verifica se nao é produto nao cadastrado
			if($acao == 'ProdutoNaoCadastrado'){
				
				$valorProduto = $_POST['valorProduto'];
				$descricaoProduto = "DIVERSO";
				$totalProduto = $valorProduto*$qtde;

				

				//insere produto no pedido
				$sql2="insert into ".$_SESSION['BASE'].".saidaestoqueitem (NUMERO, ITEM, CODIGO_ITEM, QUANTIDADE, Valor_unitario_desc, VALOR_UNITARIO, VALOR_TOTAL, VALOR_UNIT_DESC, QUANTIDADE_ATEND, SALDO_ATEND,DATA_COMPRA, QTDE_BAIXA, DESCRICAO_ITEM, Ind_Aut, Ind_Estok , Valor_Custo , 	num_livro, HORA_COMPRA , Cod_Atendente, tabela_preco) values('".$_numeropedido."','".$ITEM."','$cod','$qtde','$valorProduto','$valorProduto','$totalProduto','$valorProduto','$qtde','$qtde',CURRENT_DATE,'$qtde' , '$descricaoProduto','1', '-1' , '$valorCusto','1','$datahora', '".$_SESSION['login']."','Tab_Preco_5')";
				$stm2 = $pdo->prepare($sql2);
				$stm2->execute();	

				//busca valor do pedido
				$sql2="SELECT VALOR_TOTAL FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE NUMERO = '$_numeropedido'";
				$stm2 = $pdo->prepare($sql2);
				$stm2->execute();
				foreach ($stm2->fetchAll() as $rst) { 
					$valorPedido = $rst['VALOR_TOTAL'];
				}

				//$valor_pedido_atualizado = $valorPedido+$totalProduto;
				$valor_pedido_atualizado = $valorPedido;

				//atualiza valor da venda
				$sql2="UPDATE  ".$_SESSION['BASE'].".saidaestoque set VL_Pedido = '$valor_pedido_atualizado' where NUMERO = '$_numeropedido'";
				$stm2 = $pdo->prepare($sql2);
				$stm2->execute();			

			}else{
		
				//PROCURA ITEM PARA INSERIR NA TABELA
				if($_vizCodInterno == 1 ){ //codigo fabricante
					$sql = "select * from ".$_SESSION['BASE'].".itemestoque where CODIGO_FABRICANTE = ? and Ind_Prod <> '2' limit 1";				
					$stm = $pdo->prepare($sql);	
					$stm->bindParam(1,$cod, \PDO::PARAM_STR);
					$stm->execute();
					
				}else{
					$sql = "select * from ".$_SESSION['BASE'].".itemestoque where Codigo_Barra = ? and Ind_Prod <> '2' or
					CODIGO_FORNECEDOR = ? and Ind_Prod <> '2' limit 1";
					$stm = $pdo->prepare($sql);	
					$stm->bindParam(1,$cod, \PDO::PARAM_STR);
					$stm->bindParam(2,$cod, \PDO::PARAM_STR);
					$stm->execute();
				}
				
		
			
				if($stm->rowCount() > 0){
					while($linha = $stm->fetch(PDO::FETCH_OBJ)){					
						
						$codigoProduto = $linha->CODIGO_FORNECEDOR;

						//validar estoque
						if($_validaestoque == 1) {
							$_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
							WHERE Codigo_Item  = '" . $codigoProduto . "' AND  Codigo_Almox = '1'";         
							$stm2 = $pdo->prepare($_sql);
							$stm2->execute();
							foreach ($stm2->fetchAll() as $rstest) { 
								$Qtde_Disponivel = $rstest['Qtde_Disponivel'];
							}
							if($qtde > $Qtde_Disponivel) { 
								$msg = "Estoque insulficiente, qtde disponível $Qtde_Disponivel";
							}
						}
						
						if($msg == "" ){
						
							$valorProduto = $linha->Tab_Preco_5;
							$descricaoProduto = $linha->DESCRICAO;
							$totalProduto = $valorProduto*$qtde;
							
							//insere produto no pedido
							$sql2="insert into ".$_SESSION['BASE'].".saidaestoqueitem (NUMERO, ITEM, CODIGO_ITEM, QUANTIDADE, Valor_unitario_desc, VALOR_UNITARIO, VALOR_TOTAL, VALOR_UNIT_DESC, QUANTIDADE_ATEND, SALDO_ATEND,DATA_COMPRA, QTDE_BAIXA, DESCRICAO_ITEM, Ind_Aut, Ind_Estok , Valor_Custo , 	num_livro, HORA_COMPRA , Cod_Atendente, tabela_preco) values('".$_numeropedido."','".$ITEM."','$codigoProduto','$qtde','$valorProduto','$valorProduto','$totalProduto','$valorProduto','$qtde','$qtde',CURRENT_DATE,'$qtde' , '$descricaoProduto','1', '-1' , '$valorCusto','1','$datahora', '".$_SESSION['login']."','Tab_Preco_5')";
							$sql3 = $sql2;
							$stm2 = $pdo->prepare($sql2);
							$stm2->execute();
							
							//busca valor do pedido
							$sql2="SELECT VALOR_TOTAL FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE NUMERO = '$_numeropedido'";
							$stm2 = $pdo->prepare($sql2);
							$stm2->execute();
							foreach ($stm2->fetchAll() as $rst) { 
								$valorPedido = $rst['VALOR_TOTAL'];
							}

							//$valor_pedido_atualizado = $valorPedido+$totalProduto;
							$valor_pedido_atualizado = $valorPedido;

							//atualiza valor da venda
							$sql2="UPDATE  ".$_SESSION['BASE'].".saidaestoque set VL_Pedido = '$valor_pedido_atualizado' where NUMERO = '$_numeropedido'";
							$stm2 = $pdo->prepare($sql2);
							$stm2->execute();
						}
						
						//$_SESSION['i'] = 0;
					}
				}
			}
			if($msg != "") { 	?>
				<div class="row alert alert-info alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							<?=$msg;?>
						</div>
				
				
			
			<?php 

			}
	
	?>
	
		<table class="table">
			<tr>
				<th class="lin">#</th>
				<th class="lin">DESCRIÇÃO</th>
				<th class="lin">QTDE</th>
				<th class="lin" style="min-width: 90px ;">VALOR UN.</th>
				<th class="lin" style="min-width: 90px ;">VALOR</th>
			</tr>
			<?php
				//busca valor desconto total da venda
				$sql = "select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."'";
				$stm = $pdo->prepare($sql);
				$stm->execute();
				while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 
					$descontoVenda = $rst->VL_DESCONTO_porc;
				}


				$sql = "select * from ".$_SESSION['BASE'].".saidaestoqueitem where NUMERO = '".$_numeropedido."'";
				$stm = $pdo->prepare($sql);
				$stm->execute();
				if($stm->rowCount() > 0){
					while($linha = $stm->fetch(PDO::FETCH_OBJ)){
						
					$totalValor = $totalValor + $linha->VALOR_TOTAL;
					if($linha->Valor_unitario_desc != $linha->VALOR_UNIT_DESC){
						$hideDesconto = "SIM";
					}else{
						$hideDesconto = "";
					}
				
			?>
					<tr>
						<td><b><?=$linha->ITEM?></b></td>
						<td><b><?=$linha->DESCRICAO_ITEM?></b></td>
						<td><b><?=$linha->QUANTIDADE?></b></td>
						<td><b><?="R$ ".number_format($linha->Valor_unitario_desc,2,',','.')?></b></td>
						<td><b><?="R$ ".number_format($linha->VALOR_TOTAL,2,',','.')?></b></td>
						<td class="delclass" style="padding:0px; padding-top:3px;"><span style="font-size:16px; color:red; font-weight:bold; cursor:pointer;" onclick="deletItem('<?=$linha->ITEM;?>')">X</span></td>
						<td class="desclass" style="padding:0px; padding-top:3px;"><?php if($hideDesconto == ""){ ?><span style="font-size:16px; color:orange; font-weight:bold; cursor:pointer;" onclick="descontoItem('<?=$linha->ITEM;?>')"><i class="fa fa-plus"></i></span><?php } ?></td>
					</tr>
				<?php } }
				$totalValor = $totalValor-$descontoVenda;
				?>
		</table>
		<input type="hidden"  id="totalVenda" name="TotalVenda" value="<?=number_format($totalValor,2,',','.');?>">
		<input type="hidden"  id="trocoC" name="trocoC" value="">
	<?php } 
	
	if($acao == 'pesquisarItem'){
		if($_vizCodInterno == 1) {
			$_campopesquisa = "CODIGO_FABRICANTE";
		}else{
			$_campopesquisa = "Codigo_Barra";
		}
		?>
			<table class="table">
				<tr>
					<th></th>
					<th>Código </th>
					<th>Descrição</th>
					<th>Valor</th>
					
				</tr>
		<?php
		
		$descricao = "%".$_POST['descricao']."%";
		
		if($_POST['descricao'] != ""){
		
		$sql = "select * from ".$_SESSION['BASE'].".itemestoque where $_campopesquisa <> '' and Ind_Prod <> 2 and  DESCRICAO  like ? limit 100";
	
		$stm = $pdo->prepare($sql);	
		$stm->bindParam(1,$descricao, \PDO::PARAM_STR);
		$stm->execute();
		if($stm->rowCount() > 0){
			while($linha = $stm->fetch(PDO::FETCH_OBJ)){
				?>
				<tr>				    
					<td style="text-align:center;"><button class="btn btn-default" onclick="sel('<?=$linha->DESCRICAO;?>','<?=$linha->$_campopesquisa;?>','<?=$linha->Tab_Preco_5;?>','<?=number_format($linha->Tab_Preco_5,2,',','.');?>')"><i class="fa fa-check"></i></button></td>
					<td><?=$linha->$_campopesquisa;?></td>
					<td><?=$linha->DESCRICAO;?></td>
					<td>R$ <?=number_format($linha->Tab_Preco_5,2,',','.');?></td>					
				</tr>	
				<?php
			}
		}else{
			?>
				<tr>
					<td colspan="4" style="text-align:center;">Nenhum registro encontrado</td>
				</tr>
			
			<?php
		}
		}else{
			?>
				<tr>
					<td colspan="4" style="text-align:center;">Digite alguma coisa para procurar</td>
				</tr>
			
			<?php
		}
		?></table><?php
	}
	
	//INCLUI CPF NA NOTA
	if($acao == "cpfNota"){
		
		$cpfNota = preg_replace('/[^0-9]/', '', $_POST['cpfcpnjnota']);
		
		$sql = "update ".$_SESSION['BASE'].".saidaestoque set cpfcnpj = ? where NUMERO = '".$_numeropedido."'";
		$stm = $pdo->prepare($sql);	
		$stm->bindParam(1,$cpfNota, \PDO::PARAM_STR);
		$stm->execute();
		
	}
	//ABRE MODAL PARA FINALIZAR PAGAMENTO
	if($acao == "finalizaPagamento"){

			//verifica se a venda está aberta
			$sql="select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."' and Cod_Situacao <> '99' and Cod_Situacao <> '93'";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
			if($stm->rowCount() > 0){

				$sql="DELETE FROM ".$_SESSION['BASE'].".saidaestoquepgto where spgto_numpedido = '".$_numeropedido."' ";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
		
				$sql="select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."' ";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				$result = $stm->fetch(PDO::FETCH_OBJ);

				$cpfCnpj = $result->cpfcnpj;
				
				$desconto_item = $result->VL_DESCONTO;
				$desconto_venda = $result->VL_DESCONTO_porc;
				$id_consumidorAtendimento = $result->CODIGO_CLIENTE;
				
				$sql="select * from ".$_SESSION['BASE'].".saidaestoqueitem where NUMERO = '".$_numeropedido."' ";
				$stm = $pdo->prepare($sql);	
				$stm->execute();

				while($result = $stm->fetch(PDO::FETCH_OBJ)){
				
					$total = $total + $result->VALOR_UNITARIO*$result->QUANTIDADE;
					
				}
				//$totalComDesconto = 0 ;
				$totalComDesconto = $total - $desconto_item - $desconto_venda;
				$cpf = $cpfCnpj;

				//verifica se consumidor j� tem cadastroConsumidor
				$sql="select *,DATE_FORMAT(data_nascimento , '%d/%m/%Y') as nascimento from ".$_SESSION['BASE'].".consumidor 
				where CODIGO_CONSUMIDOR = '$id_consumidorAtendimento'";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				if($stm->rowCount() > 0){
					
					$cad = "disabled";
					while($result = $stm->fetch(PDO::FETCH_OBJ)){
					
						$nome = $result->Nome_Consumidor;
						$fantasia = $result->Nome_Fantasia;
						$cidade = $result->CIDADE;
						$bairro = $result->BAIRRO;
						$endereco = $result->Nome_Rua;
						$telefone = $result->FONE_RESIDENCIAL;
						$celular = $result->FONE_CELULAR;
						$numero = $result->Num_Rua;
						$cep = $result->CEP;
						$site = $result->comentarios;
						$complemento = $result->COMPLEMENTO;
						$nascimento = $result->nascimento;
						$cpfcnpj = $result->CGC_CPF;

						

					}
				}
		
				?>
					<input type="hidden" id="totalForma" name="totalForma" value="1">			
			
					<div class="row" style="padding:15px;">
						<!--FORMAS DE PAGAMENTOS-->
						<div class="col-sm-10 style1" id="divFormaPagamento">							
							<div class="row">
								<div class="col-sm-4">
									<label>FORMA DE PAGAMENTO </label>
									<select type="text" class="form-control" name="selpag1" id="selpag1" onchange="selTipo('1',this.value)">
										<?php										
											$sql = "select * from ".$_SESSION['BASE'].".tiporecebimpgto where id <> '99'";											
											$stm = $pdo->prepare($sql);	
											$stm->execute();
											while($result = $stm->fetch(PDO::FETCH_OBJ)){
												if($qtdeParcela == ""){
													$qtdeParcela = $result->QT_Parcelas;
													$parcelas = $linha->qt_parcelas;
												}
												?><option value="<?=$result->id;?>" <?php if ($result->id == 4){ echo'selected';} ?>><?=$result->nome;?></option><?php
											}
											$parcelas = 1;
										?>
										
									</select>
								</div>
								<div class="col-sm-4" id="parc1">
									<label>PARCELAS</label>
									<select type="text" class="form-control" name="parpag1" id="parpag1" >
									<?php							
										$i = 0;
										while($i< $parcelas ){											$i++;
										
											?><option value="<?=$i?>"><?=$i;?></option><?php											
										}
										
									?>
									
									</select>
									
									
								</div>
								<div class="col-sm-4">
									<label>VALOR PAGO R$</label>
									<input type="text" class="form-control" name="vlrPag1" id="vlrPag1" onKeyPress="return(moeda(this,'.',',',event));" onkeyup="soma();" value="<?=number_format(0,2,',','.');?>" onblur="calc_parcela('1',this.value)">
								</div>
							</div>
							<div id="divmaispagto1">
								<hr>
								<h6 style="text-align:center;"><a href="#" onclick="add('1')">+ ADICIONAR MAIS UMA FORMA DE PAGAMENTO</a></h6>
							</div>
							
						</div>
						<div id="cepbusca" style=""></div>
						<div class="col-sm-2 " >
							<div class="row">
								
								<div class="col-sm-12">
									<label>Frete</label>
									<input type="text" class="form-control" id="frete" name="frete" placeholder="0,00" onKeyPress="return(moeda(this,'.',',',event));" onkeyup="soma();" />
								</div>
								
							</div>
							
						</div>
						<div class="col-sm-8 style1 hid" id="divBuscaConsumidor">
							<div class="row">
								<div class="col-sm-3">
									<label>Tipo</label>
									<select class="form-control" name="tipoBB" id="tipoBB">
										<option value="0">CPF/CNPJ</option>
										<option value="1">NOME</option>
									</select>
								</div>
								
								<div class="col-sm-6">
									<label>Descrição</label>
									<input type="text" class="form-control" id="bbconsumidor" name="bbconsumidor" placeholder="digite aqui para buscar consumidor"/>
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-primary btn-block" style="margin-top:22px;" onclick="buscaCliente()"><i class="fa fa-search"></i> BUSCAR</button>
								</div>
							</div>
							<br><br>
							<div class="row">
								<div class="col-sm-12" id="respBuscaConsumidor">
									<table class="table">
										<tr>
											<th>Ação</th>
											<th>Nome</th>
											<th>CPF/CNPJ</th>
										</tr>
										<tr>
											<td colspan="3" align="center">NENHUM CLIENTE ENCONTRADO...</td>
										</tr>

									</table>
								</div>
							</div>
						</div>
						
					</div>
					
					<div class="row" style="padding:15px; padding-top:5px; padding-right:30px;">
						<div class="col-sm-12" style="">
							<div class="row">
								<div class="col-sm-2" style="padding:5px;">
									<div style="padding:10px; border:1px solid #BABABA; border-radius:4px; text-align:center;">
										<label>TOTAL</label>
										<p class="rr2"><b>R$ <?=number_format($total,2,',','.');?></b></p>
									</div>
								</div>
								<div class="col-sm-2" style="padding:5px;">
									<div style="padding:10px; border:1px solid #BABABA; border-radius:4px; text-align:center;">
										<label>DESC. ITENS</label>
										<p class="rr2"><b>R$ <?=number_format($desconto_item,2,',','.');?></b></p>
									</div>	
								</div>
								<div class="col-sm-2" style="padding:5px;">
									<div style="padding:10px; border:1px solid #BABABA; border-radius:4px; text-align:center;">
										<label>DESC. VENDA</label>
										<p class="rr2 dle" onclick="removeDesconto()"><b id="descVenda">R$ <?=number_format($desconto_venda,2,',','.');?></b></p>
									</div>
								</div>
								<div class="col-sm-2" style="padding:5px;">
									<div style="padding:10px; border:1px solid #BABABA; border-radius:4px; text-align:center;">
										<label>VALOR A PAGAR</label>
										<p class="rr"><b>R$ <?=number_format($totalComDesconto,2,',','.');?></b></p>
									</div>
								</div>
								<div class="col-sm-2" style="padding:5px;">
									<div id="fp" style="padding:10px;  border-radius:4px; text-align:center; background-color:#5CB85C; color:#FFF;">
										<label>FALTA PAGAR</label>
										<p class="rr"><b id="pago">R$ 0,00</b></p>
									</div>
								</div>
								<div class="col-sm-2" style="padding:5px;">
									<div style="padding:10px; border:1px solid #bababa; border-radius:4px; text-align:center; background-color:#bababa;">
										<label>TROCO</label>
										<p class="rr"><b id="troco">R$ 0,00</b></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="padding-right:15px;">
						
						<div class="col-sm-4"><button type="button" class="btn btn-primary btn-block" onclick="fim('1')"> FINALIZAR PEDIDO</div>
						<div class="col-sm-4"><button type="button" class="btn btn-success btn-block" onclick="fim('2')"> PAGAMENTO COM NFC-e</div>
					</div>
				
				<?php
		
			}else{
				//se a venda nao estiver mais em aberto entra aqui
				?>
					<div class="row">
						<div class="col-sm-12" align="center">			
							<p><strong>Desculpe, a venda não está com situação em aberto!</p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12" align="center">
								<img src="../assets/images/small/Questions-pana.png" alt="image" class="img-responsive " width="300"/>                                                    
						</div>
					</div>                            		
					<div class="row">
						<div class="col-sm-6" style="text-align: center;">
							<button type="button" class="btn btn-primary btn-block" onclick="fecharModal()">Fechar</button>
						</div>
						<div class="col-sm-6" style="text-align: center;">
							<button type="button" class="btn btn-default btn-block"  onclick="NovaVenda()" >Nova venda</button>
						</div>						
					</div> 		
				<?php				

			}
	}
	
	if($acao == "maisFormapagamento"){
		
		$i = $_POST['q'] + 1;
		?>	
		<hr>
		<div class="row">
			<div class="col-sm-4">
				<label>FORMA DE PAGAMENTO</label>
				<select type="text" class="form-control" name="selpag<?=$i;?>" id="selpag<?=$i;?>" onchange="selTipo('<?=$i;?>',this.value)">
					<?php
					
						$sql = "select * from ".$_SESSION['BASE'].".tiporecebimpgto where id <> '99'";
						$stm = $pdo->prepare($sql);	
						$stm->execute();
						while($result = $stm->fetch(PDO::FETCH_OBJ)){
							
							if($qtdeParcela == ""){
								$qtdeParcela = $result->QT_Parcelas;
							}
							
							?><option value="<?=$result->id;?>"><?=$result->nome;?></option><?php
						}
					
					?>
				</select>
			</div>
			<div class="col-sm-4" id="parc<?=$i;?>">
				<label>PARCELAS</label>
				<select type="text" class="form-control" name="parpag<?=$i;?>" id="parpag<?=$i;?>">
					<?php
						$qnt = 1;
						while($qnt <= $qtdeParcela){
							?><option value="<?=$qnt;?>"><?=$qnt;?></option><?php
							$qnt++;
						}
					?>
				</select>
			</div>
			<div class="col-sm-4">
				<label>VALOR PAGO R$</label>
				<input type="text" class="form-control" name="vlrPag<?=$i;?>" id="vlrPag<?=$i;?>" onKeyPress="return(moeda(this,'.',',',event));" onkeyup="soma();" onblur="calc_parcela('<?=$i;?>',this.value)">
			</div>	
		</div>
		<div id="divmaispagto<?=$i;?>">
			<h6 style="text-align:center;"><a href="#" onclick="add('<?=$i?>')">+ ADICIONAR MAIS UMA FORMA DE PAGAMENTO</a></h6>
		</div>
		<?php
	}
	
	if($acao == "parcelamento"){
		
		$i = $_POST['q'];
		$forma_pagamento = $_POST['formpagmento'];
		$valorPago = $_POST['valorPago'];	
		$valorPago = str_replace(".", "", $valorPago);
		$valorPago = str_replace(",", ".", $valorPago);	
		if($valorPago == ""){
			$valorPago = 0;
		}

		$parcela_selecionada = $_POST['parcela_selecionada'];
		if($parcela_selecionada == ""){
			$parcela_selecionada = 1;
		}
		
		?>
		
		<label>PARCELAS</label>
		<select type="text" class="form-control" name="parpag<?=$i;?>" id="parpag<?=$i;?>">
		<?php
			$sql="select * from ".$_SESSION['BASE'].".condicao_parcelamento where cp_condPag = '$forma_pagamento'";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
			if($stm->rowCount() > 0){
				while($result = $stm->fetch(PDO::FETCH_OBJ)){
					$valor_parcela = $valorPago/$result->cp_parcela;
					if($result->cp_txCliente > 0){
						//$tipoTX = " C/J";
					}else{
						//$tipoTX = " S/J";
					}
					?><option value="<?=$result->cp_parcela;?>" <?php if($parcela_selecionada == $result->cp_parcela){echo 'selected';}?>><?=$result->cp_parcela;?> x de <?=number_format($valor_parcela,2,',','.').$tipoTX;?> </option><?php				
				}
			}else{
				?><option value="1">1 x de <?=number_format($valorPago,2,',','.');?></option><?php	
			}

		?>
		</select>
		<?php		
	}
	
if($acao == "cadastroConsumidor"){
			
			$numero_pedido = $_SESSION['numberPedido'];

					
			$nome = $_POST['nome'];
			$cpfCnpj = $_POST['cpfCnpj'];
			$nascimento = $_POST['nascimento'];
			$email = $_POST['email'];
			$telefone = $_POST['telefone'];
			$site = $_POST['site'];
			$cep = $_POST['cep'];
			$endereco = $_POST['endereco'];
			$bairro = $_POST['bairro'];
			$numero = $_POST['numero'];
			$cidade = $_POST['cidade'];
			$uf = $_POST['uf'];
			$complemento = $_POST['complemento'];
			$celular = $_POST['celular'];
			$fantasia = $_POST['fantasia'];
			
			$sql = "select CODIGO_CONSUMIDOR from ".$_SESSION['BASE'].".consumidor  order by CODIGO_CONSUMIDOR DESC LIMIT 1";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
			while($result = $stm->fetch(PDO::FETCH_OBJ)){
				$codigo = $result->CODIGO_CONSUMIDOR + 1;
			}
			
			$cpfCnpj = str_replace(".","",$cpfCnpj);
			$cpfCnpj = str_replace("-","",$cpfCnpj);
			$cpfCnpj = str_replace("/","",$cpfCnpj);
			$cpfCnpj = str_replace(" ","",$cpfCnpj);
			
			$telefone = str_replace("(","",$telefone);
			$telefone = str_replace(")","",$telefone);
			$telefone = str_replace(" ","",$telefone);
			$telefone = str_replace("-","",$telefone);

			$celular = str_replace("(","",$celular);
			$celular = str_replace(")","",$celular);
			$celular = str_replace(" ","",$celular);
			$celular = str_replace("-","",$celular);
			
			$explode = explode("/",$nascimento);
			$nascimento = $explode[2].'-'.$explode[1].'-'.$explode[0];
			
			$sql = "select CODIGO_CONSUMIDOR from ".$_SESSION['BASE'].".consumidor where CGC_CPF = '$cpfCnpj'";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
			if($stm->rowCount() == 0){

				$sql="insert into ".$_SESSION['BASE'].".consumidor (CODIGO_CONSUMIDOR,Nome_Consumidor,CIDADE,BAIRRO,Nome_Rua,CEP,UF,FONE_RESIDENCIAL,FONE_CELULAR,Nome_Fantasia,COMPLEMENTO,Data_Cadastro,Num_Rua,EMail,CGC_CPF,data_nascimento,comentarios) values('$codigo','$nome','$cidade','$bairro','$endereco','$cep','$uf','$telefone','$celular','$fantasia','$complemento','$datahora','$numero','$email','$cpfCnpj','$nascimento','$site')";
				$stm = $pdo->prepare($sql);	
				$stm->execute();

				$up = "update ".$_SESSION['BASE'].".saidaestoque  set cpfcnpj = '$cpfCnpj' , CLIENTE = '$nome', CODIGO_CLIENTE = '$codigo' where NUMERO = '$numero_pedido' ";
				$exe = $pdo->prepare($up);	
				$exe->execute();			
			}else{
				echo 'CONSUMIDOR JÁ CADASTRADO!';
				exit();
			}
			

			
	}

	if($acao == "verPagamentocartao"){
		/*
		//verificar se já existe conexao aberto
		$_SQL = "Select date_format(pay_hora,'%d/%m/%Y %H:%i') as hora,pay_valor,pay_parcelas,pay_uuid
		from " . $_SESSION['BASE'] . ".linkPay           
				where  pay_idempresa = '".$_SESSION['BASE_ID']."' 
				AND  pay_pedidoRef = '".$_numeropedido."' 
				AND pay_pedidoRefCaixa = '".$_idcaixa."' 
				AND pay_tipo = 'e'
				AND pay_status = 1";
			
				
		  $consultaLinha = $pdo->query($_SQL);

		if($consultaLinha->rowCount()  > 0 ) { //visualiza transações
		  ?>
		  <div class="row">
			<div class="col-sm-12" align="center">
				<!-- <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive " width="200"/> -->
									
			</div>
		  </div>  
		  <div class="col-sm-12" align="center">			
			<p><strong>AGUARDANDO FINALIZAÇÃO CARTÃO </strong></p>
			<p>Clique em continuar após conclusão operação terminal</p>
			  <p><img src="../assets/images/sp-loading.gif" alt="image" class="img-responsive " />  </p>
		</div>                    
 
		<div style="padding: 17px;" align="center">
			
			<button id="voltar" type="button" class="btn btn-default waves-effect waves-light m-l-5" onclick="fimCartao('<?=$tipo;?>')"><span class="btn-label"><i class="fa fa-arrow-right"></i></span>Continuar</button>
		  
		</div>
   
		<?php
	  
				  foreach ($consultaLinha as $row_a) { 
			   
					$_idpay = $row_a['pay_uuid'];
					  ?>
					  
						<div class="row" ">
						<div class="col-sm-12" align="Left">                                 
						Pendente
						</div>                               
						</div>
						<div class="row" ">
						<div class="col-sm-12" align="Left" style="background-color: #c7c7c7;;">                                 
						</div>                               
						</div>
					  <div class="row" stay>
						<div class="col-sm-4" align="center">Data:
							<?=$row_a["hora"];?>
						</div>
						<div class="col-sm-2" align="center">Parc:
							  <?=$row_a["pay_parcelas"];?>
						</div>
						<div class="col-sm-4" align="center">Valor:
							R$ <?=number_format($row_a["pay_valor"],2,',','.')?>
						</div>                                 
						<div class="col-sm-2" align="center">
						 -
						</div>
					  </div>
				<?php 
				  }
				  ?>
				  <div class="row" >                              
						<div class="col-sm-12" align="center"><span style="cursor: pointer; color:red" onclick="_CancelarOp()">Cancelar </span>
						</div>
						</div>
			  <?php 
		}
				 exit();
			*/ 

	}

if($acao == "verPagamento"){

		$usuario = $_SESSION["login"];
		$numero_pedido = $_SESSION['numberPedido'];
		
		$valor_pagoS = $_POST['valorPago'];
		$frete = $_POST['frete'];
		$_valorpgto = $valor_pagoS ;
		$spgto_valorInfo = $_valorpgto;
		$forma_pago = $_POST['selpag'];
	
		$parcela_pago = $_POST['parpag'];

		//condições de pagamentos
		$pagamentos = $_POST['pagamentos'];
		$pagamentos = substr($pagamentos, 0, -1);
		$cond_pagamento = explode("|",$pagamentos);
	
		
		$valorTotal = $_POST['valorApagar'];
		$troco = $_POST['troco'];
		$data = date('Y-m-d');
		$livro = $_SESSION['id_caixa'];
		$_idcaixa = $_SESSION['id_caixa'];
		$produto = '0';
	
		$tipo = $_POST['tipo'];
		
		
		if($troco > 0){
			$_valorpgto = $valor_pagoS - $troco;
		}else{
			$_valorpgto = $valor_pagoS;
		}

		
		try {
			
			//
			

			//buscar total pago
			$_SQL = "SELECT sum(spgto_valor) as total FROM ".$_SESSION['BASE'].".saidaestoquepgto
			WHERE spgto_numpedido = '" . $numero_pedido . "' AND spgto_numlivro = '" . $livro . "' ";
			$stm = $pdo->prepare($_SQL);	
			$stm->execute();		
			while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
				$_totalpgto = $linha->total;				
			}

			$_valorpedido = str_replace(".", "", $valorTotal);
			$_valorpedido = str_replace(",", ".", $_valorpedido);

			//$frete = str_replace(".", "", $frete);
		//	$frete = str_replace(",", ".", $frete);

			$_valorpedido = $_valorpedido  ;
			
			$_valordesconto = str_replace(".", "", 0);
			$_valordesconto = str_replace(",", ".", $_valordesconto);

			if(($_totalpgto+$_valorpgto) > ( $_valorpedido+$frete- $_valordesconto)) {					
				$_valorpgto = ($_valorpedido- $_valordesconto-$_totalpgto);
			}

			if($frete > 0) { 
				//buscar total pago
				$_SQL = "UPDATE  ".$_SESSION['BASE'].".saidaestoque SET Valor_Frete = '$frete'
				WHERE NUMERO = '" . $numero_pedido . "' AND num_livro = '" . $livro . "' ";
				$stm = $pdo->prepare($_SQL);	
				$stm->execute();
			}

			//LOOP das condições de pagamentos-----------------------------------------------------------------------------------
			$i = 0;		
			while($i < count($cond_pagamento)){
				
				$explode = explode(",",$cond_pagamento[$i]);
				

				$formaPagamento = $explode[0];
				$parcelaPagamento = $explode[1];
				$valorPagamento = $explode[2];


				//buscar vencimento
				$_SQL = "SELECT qt_parcelas,prz FROM ".$_SESSION['BASE'].".tiporecebimpgto 
				where id = '$formaPagamento' ";           
				$stm = $pdo->prepare($_SQL);	
				$stm->execute();	
				if ($stm->rowCount() > 0 ){		
					while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
					{
							$dias = $linha->prz;
							$parcelas = $linha->qt_parcelas;							
							//$recebe_integral = $linha->receb_integral;				
					}                
				}
/*
				//busca quantidade de parcelas
				$sql="SELECT * FROM ".$_SESSION['BASE'].".condicao_parcelamento where cp_condPag = '$formaPagamento' ";
				$stm = $pdo->prepare($sql);	
				$stm->execute();	
				if ($stm->rowCount() > 0 ){
					
					$parcelas = $stm->rowCount();
					
				}else{
					$parcelas = 1;
				}
*/
			//	$valor_porParcela = $valorPagamento/$parcelaPagamento;

			$valor_porParcela = $_valorpgto/$parcelaPagamento;

				//verifica se quantidade de parcela esta dentro da regra da condição de pgto						
				if($parcelaPagamento > $parcelas) { 
					$_errorlog = $_errorlog."-Parcelamento não pode ser superior <b>$parcelas</b>! \n";
					echo($_errorlog);
					exit();
				}else{

		
						//loop do parcelamento----------------------
						$Linha = 0;
						while($Linha < $parcelaPagamento) {

							$Linha ++; 
							
							
							if($data_atual == ""){
								$data_atual = date('d/m/Y');
							}							
							$data12 = SomarData($data_atual, $dias, 0, 0); 
							$dia = substr("$data12",0,2); 
							$mes = substr("$data12",3,2); 
							$ano = substr("$data12",6,4); 
							$data_atual = "$dia/$mes/$ano";

							$_vencimento = "$ano-$mes-$dia";


							//echo("forma pagamento: $formaPagamento <br>");
							//echo("parcela: $Linha <br>");
							//echo("valor Parcela: $valor_porParcela <br>");
							//echo("valor total: $valorPagamento <br>");
							//echo'<hr>';
							
							

							//cria condicao de pagamento
							$_sql = "INSERT INTO ".$_SESSION['BASE'].".saidaestoquepgto(
								spgto_numpedido,
								spgto_numlivro,
								spgto_tipopgto,
								spgto_data,
								spgto_venc,
								spgto_valor,
								spgto_parcela,
								spgto_valorInfo,
								spgto_troco,
								spgto_total_parcela
							) VALUES(
								?,
								?,
								?,
								CURRENT_DATE(),
								?,
								?,
								?,
								?,
								?,
								?
							)";								
							$statement = $pdo->prepare($_sql); 					
							$statement->bindParam(1, $numero_pedido);
							$statement->bindParam(2, $livro);
							$statement->bindParam(3, $formaPagamento);
							$statement->bindParam(4, $_vencimento);           
							$statement->bindParam(5, $valorPagamento);    
							$statement->bindParam(6, $Linha);
							$statement->bindParam(7, $valor_porParcela);
							$statement->bindParam(8, $troco);  
							$statement->bindParam(9, $parcelaPagamento);   									
							$statement->execute();

							
						}	
				}



				$i = $i + 1;
			}
			//FIM DO LOOP das condições de pagamentos-----------------------------------------------------------------------------------
		
		} catch (PDOException $e) {      
				echo $e->getMessage();
		}



		//verifica se consumidor tem cadastro
		$sql="select *,DATE_FORMAT(data_nascimento , '%d/%m/%Y') as nascimento from ".$_SESSION['BASE'].".consumidor where CGC_CPF = '$cpfCnpj' and  CGC_CPF <> '' or CGC_CPF = '$cpf' and  CGC_CPF <> '' ";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		if($stm->rowCount() > 0){
			while($result = $stm->fetch(PDO::FETCH_OBJ)){
				$_nomecliente = $result->Nome_Consumidor;
			}
		}else{
				$_nomecliente = "Consumidor";
		}
		$_operacaoevo = "";
/*
		//verificar se tem alguma condição para pagamento
		$_SQL = "Select * from " . $_SESSION['BASE'] . ".linkPay           
		where  pay_idempresa = '".$_SESSION['BASE_ID']."' 
		AND  pay_pedidoRef = '".$numero_pedido."' 
		AND pay_pedidoRefCaixa = '".$_idcaixa."' 
		AND pay_tipo = 'r'
		AND pay_status = 1 order by pay_id DESC limit 1";
	    $consultaLinha = $pdo->query($_SQL);   
		$retornoLinha = $consultaLinha->fetchAll();		
		foreach ($retornoLinha as $row_a) {  
			$NSU = $row_a['pay_NSU'];
		  	//atualiza saida estoque
		  	$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoquepgto SET spgto_payment = '1' ,
				spgto_transactionNumber = '".$row_a['pay_transactionNumber']."',
				spgto_nsu = '".$NSU."'  
				WHERE spgto_numpedido = '". $numero_pedido . "' AND spgto_numlivro = '" . $_idcaixa . "' ";               
			 
		  	$stm = $pdo->prepare("$_SQL");	
		  	$stm->execute();     
		  	$_operacaoevo = "ok" ;
		}

		
			 //verificar se já existe conexao aberto
						  $_SQL = "Select date_format(pay_hora,'%d/%m/%Y %H:%i') as hora,pay_valor,pay_parcelas,pay_uuid
						  from " . $_SESSION['BASE'] . ".linkPay           
								  where  pay_idempresa = '".$_SESSION['BASE_ID']."' 
								  AND  pay_pedidoRef = '".$numero_pedido."' 
								  AND pay_pedidoRefCaixa = '".$_idcaixa."' 
								  AND pay_tipo = 'e'
								  AND pay_status = 1 order by pay_id DESC limit 1";
								  
							$consultaLinha = $pdo->query($_SQL);

						  if($consultaLinha->rowCount()  > 0  ) { //visualiza transações
							?>
							<div class="row">
							  <div class="col-sm-12" align="center">
								  <!-- <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive " width="200"/> -->
													  
							  </div>
							</div>  
							<div class="col-sm-12" align="center">			
							  <p><strong>AGUARDANDO FINALIZAÇÃO CARTÃO </strong></p>
							  <p>Clique em continuar após conclusão operação terminal</p>
								<p><img src="../assets/images/sp-loading.gif" alt="image" class="img-responsive " />  </p>
						  </div>                    
				   
						  <div style="padding: 17px;" align="center">
							  
							  <button id="voltar" type="button" class="btn btn-default waves-effect waves-light m-l-5" onclick="fimCartao('<?=$tipo;?>')"><span class="btn-label"><i class="fa fa-arrow-right"></i></span>Continuar</button>
							
						  </div>
					 
						  <?php
						
									foreach ($consultaLinha as $row_a) { 								 
									  $_idpay = $row_a['pay_uuid'];
										?>
										
										  <div class="row" ">
										  <div class="col-sm-12" align="Left">                                 
										  Pendente
										  </div>                               
										  </div>
										  <div class="row" ">
										  <div class="col-sm-12" align="Left" style="background-color: #c7c7c7;;">                                 
										  </div>                               
										  </div>
										<div class="row" stay>
										  <div class="col-sm-4" align="center">Data:
											  <?=$row_a["hora"];?>
										  </div>
										  <div class="col-sm-2" align="center">Parc:
												<?=$row_a["pay_parcelas"];?>
										  </div>
										  <div class="col-sm-4" align="center">Valor:
											  R$ <?=number_format($row_a["pay_valor"],2,',','.')?>
										  </div>                                 
										  <div class="col-sm-2" align="center">
										   -
										  </div>
										</div>
								  <?php 
									}
									?>
									<div class="row" >                              
										  <div class="col-sm-12" align="center"><span style="cursor: pointer; color:red" onclick="_CancelarOp()">Cancelar </span>
										  </div>
										  </div>
								<?php 
		
								   exit();
						 
			   } else {
				 //cria nova conexao
			
						  $_split = array();
				  
							//SERVICOS---COLABORADOR---------------------------------
							$_sql = "Select Cod_Colaborador,CODIGO_ITEM,VALOR_TOTAL,usuario_codeEvo 
							  from ". $_SESSION['BASE'] .".saidaestoqueitem
							left join " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = Cod_Colaborador
							  where  NUMERO = '".$_numeropedido."' AND num_livro = '".$_idcaixa."' and SE_IND_PROD = '2' 
							  and usuario_codeEvo <> ''
							  and Cod_Colaborador <> 0 GROUP BY Cod_Colaborador";
							
							$consultaLinha = $pdo->query($_sql);
							$retornoCol = $consultaLinha->fetchAll();
							foreach ($retornoCol as $row_COL) {        

								$_idcolaborador = $row_COL["Cod_Colaborador"];  
							  
								$_idCod_Item = $row_COL["CODIGO_ITEM"];  
							
								$_totalitem = $row_COL["VALOR_TOTAL"];
						
								$_usuario_codeEvo = $row_COL["usuario_codeEvo"];
							  
									//adiciona slit caso existe
									$_sql = "Select ieserv_comissao,ieserv_regra,ieserv_vlr
									from ". $_SESSION['BASE'] .".itemestservico                        
									where  ieserv_produto = '".$_idCod_Item."' 
									AND ieserv_user = '".$_idcolaborador."' and ieserv_comissao > '0' ";
								
									$sql_comissao = $pdo->query("$_sql");
							  
									$ret_comissao = $sql_comissao->fetchAll();
									foreach ($ret_comissao as $row_Comissao) {    
									  if($row_Comissao["ieserv_regra"] == "%"){
										$_vlrslit = number_format((($row_Comissao["ieserv_comissao"]/100)*$_totalitem) ,2);
									  }else{
										$_vlrslit = $row_Comissao['ieserv_vlr'];
									  }
									   
										$_arr =  array("code" => "$_usuario_codeEvo","valor" => "$_vlrslit");
										array_push($_split,$_arr);
					
									}

							
							}
							require_once('enviopayevofunction.php'); 

							//VERIFICAR SE EXISTE TRANSACAO EM CARTAO 
							  $_sql = "Select SUM(spgto_valor) AS spgto_valor, count(spgto_parcela) as spgto_parcela,bandeira_id
									  from ". $_SESSION['BASE'] .".saidaestoquepgto
									  INNER JOIN 	". $_SESSION['BASE'] .".tiporecebimpgto ON  spgto_tipopgto = ID             
									  where  spgto_numpedido = '".$_numeropedido."'
									  AND spgto_numlivro = '".$_idcaixa."' AND spgto_payment = 0 and bandeira_id <> ''
									  GROUP BY bandeira_id";
				  
							  $consultaLinha = $pdo->query("$_sql");
							  $retornoLinha = $consultaLinha->fetchAll();
							  $_regpgto =  $consultaLinha->rowCount();


							
								foreach ($retornoLinha as $row_a) {        
								  $_CAIXA = "$_idcaixa";
								  $_PEDIDO = "$_numeropedido";
								  // "terminalId":"AA006003",
								  $_value =    $row_a["spgto_valor"];
								  $_parc = $row_a["spgto_parcela"];  //parcelas
								  $_clientName = "$_PEDIDO-$_CAIXA $_nomecliente";
								//   $_terminalId"
								  $_paymentBrand =  $row_a["bandeira_id"];  

								  $_value =  number_format($_value ,2);
								  $_installments = $_parc;

								  $_ACAOCARTAO = 1; //CRIA CONEXAO
								  //verificar se tem terminal selecionado
								  $_terminal = $_parametros["id_terminal"];                    
							  
								  
								 include('enviopayevo.php'); 
							  

								}
							   if($_regpgto > 0){

						 
								$_SQL = "Select date_format(pay_hora,'%d/%m/%Y %H:%i') as hora,pay_valor,pay_parcelas,pay_uuid
								from " . $_SESSION['BASE'] . ".linkPay           
										where  pay_idempresa = '".$_SESSION['BASE_ID']."' 
										AND  pay_pedidoRef = '".$_numeropedido."' 
										AND pay_pedidoRefCaixa = '".$_idcaixa."' 
										AND pay_tipo = 'e'
										AND pay_status = 1";
									  
										$consultaLinha = $pdo->query($_SQL);
										if($consultaLinha->rowCount()  > 0 ) { //visualiza transações
										?>
										  <div class="row">
										<div class="col-sm-12" align="center">
											<!-- <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive " width="200"/> -->
																
										</div>
										</div>  
										<div class="col-sm-12" align="center">			
										  <p><strong>AGUARDANDO FINALIZAÇÃO CARTÃO </strong></p>
										  <p>Clique em continuar após conclusão operação terminal</p>
											<p><img src="../assets/images/sp-loading.gif" alt="image" class="img-responsive " />  </p>
									  </div>
								
								
									<div style="padding: 17px;" align="center">
										
										<button id="voltar" type="button" class="btn btn-default waves-effect waves-light m-l-5" onclick="fimCartao('<?=$tipo;?>')"><span class="btn-label"><i class="fa fa-arrow-right"></i></span>Continuar</button>
									  
									</div>
								  
										<?php
										}
										foreach ($consultaLinha as $row_a) { 
												$_idpay = $row_a['pay_uuid'];
																	?>
																	
																	<div class="row" ">
																	  <div class="col-sm-12" align="Left">                                 
																	  Pendente
																	  </div>                               
																	</div>
																	<div class="row" ">
																	  <div class="col-sm-12" align="Left" style="background-color: #c7c7c7;;">                                 
																	  </div>                               
																	</div>
																	<div class="row" stay>
																	  <div class="col-sm-4" align="center">Data:
																		<?=$row_a["hora"];?>
																	  </div>
																	  <div class="col-sm-2" align="center">Parc:
																		  <?=$row_a["pay_parcelas"];?>
																	  </div>
																	  <div class="col-sm-4" align="center">Valor:
																		R$ <?=number_format($row_a["pay_valor"],2,',','.')?>
																	  </div>
																	
																	  <div class="col-sm-2" align="center">
																		  -
																	  </div>
																	</div>
															  <?php 
											}
															  ?>
																  <div class="row" >                              
																		<div class="col-sm-12" align="center"><span style="cursor: pointer; color:red" onclick="_CancelarOp()">Cancelar  </span>
																		</div>
																		</div>
															  <?php 

					  
							  
							
								  exit();
								}
						  
					   } ///fim processamento cartao
				  
					   	*/  

	} //fim ver pagamento

	if($acao == "fimPagamento"){

		$tipo = $_POST['tipo'];
		$_idpedido = $_SESSION['numberPedido'];
		$livro = $_SESSION['id_caixa'];
		$_idcaixa = $_SESSION['id_caixa'];
		$idemp = '1';//$_SESSION['BASE_ID']';
		$atendente = $_POST['atendente'];

		//exit();
	
		//atualiza saida estoque
		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '3' 
		WHERE NUMERO = '" . $_idpedido . "' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	

		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoqueitem SET Ind_Aut = '1' 
		WHERE NUMERO = '" . $_idpedido . "'";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();

		//busca cliente da venda
		$sql="SELECT * FROM ". $_SESSION['BASE'] .".saidaestoque where NUMERO = '$_idpedido'";
		$stm = $pdo->prepare("$sql");	
		$stm->execute();
		while($resp = $stm->fetch(PDO::FETCH_OBJ)){
			$_idcliente = $resp->CODIGO_CLIENTE;
		}

		//lanca comissão do serviço para prestador (CASO HOUVER)====================================================================================================
		/*
		
		$sql="SELECT Cod_Colaborador,usuario_NOME FROM ". $_SESSION['BASE'] .".saidaestoqueitem
		LEFT JOIN  " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = Cod_Colaborador
		WHERE Cod_Colaborador <> 0 AND NUMERO = '".$_idpedido."' and SE_IND_PROD = '2' 
		GROUP BY Cod_Colaborador";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		if($stm->rowCount() > 0){
			while($rst = $stm->fetch(PDO::FETCH_OBJ)){

				$_idcolaborador = $rst->Cod_Colaborador;  
				$_Nomecolaborador = $rst->usuario_NOME; 

				//buscar SOMA serviços
				$sql2="SELECT * FROM ". $_SESSION['BASE'] .".saidaestoqueitem
				LEFT JOIN ". $_SESSION['BASE'] .".itemestservico ON ieserv_produto = CODIGO_ITEM
				WHERE NUMERO = '".$_idpedido."'and SE_IND_PROD = '2' 
				AND Cod_Colaborador = '$_idcolaborador' and ieserv_user = '$_idcolaborador'";
				$stm2 = $pdo->prepare($sql2);
				$stm2->execute();
				$retornoLinha = $stm2->fetchAll();
				$total_servico = 0;
				foreach ($retornoLinha as $row_a) {
										
					$porcentagem_comissao = $row_a["ieserv_comissao"];
					$valor_comissao = $row_a["ieserv_vlr"];
					$valor_servico = $row_a['VALOR_TOTAL'];

					//verifica se a regra é por porcentagem ou valor fixo
					if($row_a["ieserv_regra"] == "%"){

						$total_servico =  $total_servico + (($porcentagem_comissao/100)*$valor_servico); 

					}else{
						$total_servico = $total_servico + $row_a['ieserv_vlr'];                           
					}

				}
				
				//buscar vencimento comissao
				$_SQL = "SELECT qt_parcelas,prz FROM ".$_SESSION['BASE'].".tiporecebimpgto 
				where id = '99' ";           
				$stm2 = $pdo->prepare($_SQL);	
				$stm2->execute();		
				while ($linha = $stm2->fetch(PDO::FETCH_OBJ)){
						$dias = $linha->prz;
						$parcelas = $linha->qt_parcelas;	
						if($parcelas == 0){
							$parcelas = 1;
						}	
				} 
						
				$i = 0;
				$data_atual = "";
				$total_servico = $total_servico/$parcelas;

				

				//faz loop da comissão caso for parcelada em condicoes de pagamentos
				while($i < $parcelas){

					$i++;

					if($data_atual == ""){
						$data_atual = date('d/m/Y');
					}							
					$data12 = SomarData($data_atual, $dias, 0, 0); 
					$dia = substr("$data12",0,2); 
					$mes = substr("$data12",3,2); 
					$ano = substr("$data12",6,4);
					$data_atual = "$dia/$mes/$ano";

					$vencimento = "$ano-$mes-$dia";

					//LANCA COMISSAO DE SERVICO NO CONTA CORRENTE	
					$_SQL = "INSERT INTO " . $_SESSION['BASE'] . ".contacorrente (
						cc_data,
						cc_hora,
						cc_usuario,
						cc_documento,
						cc_livro,                                                                                                                                               
						cc_tipomov,
						cc_valor,
						cc_tipopgto,	
						cc_venc,
						cc_empID,
						cc_totalparc,
						cc_parc
					) values ( 
						CURRENT_DATE,
						NOW(),
						'$_idcolaborador',
						'$_idpedido',
						'$_idcaixa',
						'1',
						'$total_servico',
						'99',	
						'$vencimento',
						'$idemp',
						'$parcelas',
						'$i'
					)";                          
					$stm2 = $pdo->prepare($_SQL);	
					$stm2->execute();


					//insere valor no financeiro
					$_SQL = "INSERT INTO ". $_SESSION['BASE'] .".financeiro (
						financeiro_parcela,
						financeiro_totalParcela,
						financeiro_codigoCliente,
						financeiro_nome,
						financeiro_documento,
						financeiro_historico,
						financeiro_emissao,
						financeiro_vencimento,
						financeiro_vencimentoOriginal,
						financeiro_valor,
						financeiro_situacaoID,
						INDENTIFICADOR,
						financeiro_tipo,
						financeiro_grupo,
						financeiro_subgrupo,
						financeiro_tipoPagamento,
						financeiro_hora,
						financeiro_tipoQuem
					) VALUES (
						'$i',
						'$parcelas',
						'$_idcolaborador',
						'$_Nomecolaborador',
						'$_idpedido',
						'COMISSÃO REF. $_idpedido ',
						CURRENT_DATE(),
						'$vencimento',
						'$vencimento',
						'$total_servico',
						'0',
						'1',
						'1',
						'15',
						'99',
						'99',
						now(),
						'3'
					)";          
					$stm2 = $pdo->prepare($_SQL);	
					//$stm2->execute();


				}

				$total_servico_todosColaboradores = $total_servico_todosColaboradores+$total_servico ;
				$total_servico = "";
				$valor_comissao = "";
				

			}
		}
		*/
		//FIM lanca comissão do serviço para prestado (CASO HOUVER)=====================================================================================================	
		//==============================================================================================================================================================
		//MATA VARIAVEIS UTILIZADAS
		$i = 0;
		$_idcolaborador = "";
		$_Nomecolaborador = "";
		$total_servico = "";
		$vencimento = "";
		$data_atual = "";

		//==============================================================================================================================================================
		//comissao atendimento		

		
			$consultaLinha = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".saidaestoquepgto where  spgto_numpedido = '".$_idpedido."'");	
			$retornoLinha = $consultaLinha->fetchAll();
			foreach ($retornoLinha as $row_a) {
	
				//informações da forma de pagamento
				$_TIPOPGTO = $row_a["spgto_tipopgto"];		
				//$vencimento = $row_a["spgto_venc"];
				$total_parcela = $row_a['spgto_total_parcela'];
				//$parcela_x = $parcela_x + $row_a['spgto_parcela'];
				$valor_parcela_x = $valor_parcela_x + $row_a['spgto_valorInfo'];
			}
		

		//==============================================================================================================================================================
		//loop da forma de pagamentos===================================================================================================================================
		$i = 1;
		$consultaLinha = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".saidaestoquepgto where  spgto_numpedido = '".$_idpedido."'");	
		$retornoLinha = $consultaLinha->fetchAll();
		foreach ($retornoLinha as $row_a) {

			//informações da forma de pagamento
			$_TIPOPGTO = $row_a["spgto_tipopgto"];		
			$vencimento = $row_a["spgto_venc"];
			$total_parcela = $row_a['spgto_total_parcela'];
			$parcela = $row_a['spgto_parcela'];
			$valor_parcela = $row_a['spgto_valorInfo'];

			
			

			//busca dados da condição de pagamento
			$sql="SELECT * FROM ". $_SESSION['BASE'] .".tiporecebimpgto where id = '$_TIPOPGTO'";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			while($rst = $stm->fetch(PDO::FETCH_OBJ)){
				$prazo_condPag = $rst->prz;
				$juros_condPag = $rst->tx_juro;
				$liquida_condPag = $rst->Ind_liquida;
				//$recebeIntegral_condPag = $rst->receb_integral;
				$contabiliza_caixa = $rst->ind_troca;
			}

			//verifica se tem desconto
			$sql="SELECT * FROM ". $_SESSION['BASE'] .".saidaestoque where NUMERO = '$_idpedido'";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			while($rst = $stm->fetch(PDO::FETCH_OBJ)){
				$desconto_item = $rst->VL_DESCONTO;
				$VL_Pedido = $rst->VL_Pedido;
				if($desconto_item > 0){
					$obs = $obs."Desconto Item: ".number_format($desconto_item,2,',','.')."
					";
				}
				$desconto_venda = $rst->VL_DESCONTO_porc;
				if($desconto_venda > 0){
					$obs = $obs."Desconto Venda: ".number_format($desconto_venda,2,',','.')."
					";
				}

				$desconto_total = $desconto_item+$desconto_venda;
			}


			$valor_desconto_item = ((($valor_parcela/$VL_Pedido)*100)*$total_comissao/100);
			$valor_fim = $valor_parcela + $valor_desconto_item;


			//SE RECEBE O VALOR DO CARTAO DE CREDITO PARCELADO
			/*
			if($recebeIntegral_condPag == 0){
				*/
				//SE RECEBE O VALOR DO CARTAO DE CREDITO no VALOR INTEGRAL
				//!!!!!!!!!   SE FOR A ULTIMA PARCELA, INCLUI OS REGISTROS    !!!!!!!

					//busca total por tipo de pagamento
					$sql="SELECT *,SUM(spgto_valorInfo) as total FROM ". $_SESSION['BASE'] .".saidaestoquepgto 
					LEFT JOIN ".$_SESSION['BASE'] .".tiporecebimpgto  on spgto_tipopgto = id 
					where spgto_numpedido = '".$_idpedido."'  GROUP BY spgto_tipopgto";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
					while($rst = $stm->fetch(PDO::FETCH_OBJ)){

						$total_pagto = $rst->total;
						$data_atual = "";

						//busca dados da condição de pagamento
						$sql="SELECT * FROM ". $_SESSION['BASE'] .".tiporecebimpgto where id = '$_TIPOPGTO'";
						$stm = $pdo->prepare($sql);
						$stm->execute();
						while($rst = $stm->fetch(PDO::FETCH_OBJ)){
							$prazo_condPag = $rst->prz;
							$juros_condPag = $rst->tx_juro;
							$liquida_condPag = $rst->Ind_liquida;
							$recebeIntegral_condPag =0;
							$descricao_condPag = $rst->nome;
							$contabiliza_caixa = $rst->ind_troca;
						}
						$contabiliza_caixa = 1;
						//calcula novo vencimento
						if($data_atual == ""){
							$data_atual = date('d/m/Y');
						}							
						$data12 = SomarData($data_atual, $prazo_condPag, 0, 0); 
						$dia = substr("$data12",0,2); 
						$mes = substr("$data12",3,2); 
						$ano = substr("$data12",6,4); 
						$data_atual = "$dia/$mes/$ano";

						$vencimento = "$ano-$mes-$dia";

						//verifica se liquida venda
						if($liquida_condPag == 'S'){
							$valor_pagoF = $valor_parcela;
							$data_pagoF = date('Y-m-d');
						}else{
							$valor_pagoF = "";
							$data_pagoF  = "";
						}
						
						$obs = $obs."Única parcela de gerada de acordo com o tipo de pagamento $descricao_condPag configurado como tipo recibimento: Integral.
						";
						
						//insere valor no financeiro
						$_SQL = "INSERT INTO ". $_SESSION['BASE'] .".financeiro (
							financeiro_parcela,
							financeiro_totalParcela,
							financeiro_codigoCliente,
							financeiro_nome,
							financeiro_documento,
							financeiro_historico,
							financeiro_emissao,
							financeiro_vencimento,
							financeiro_vencimentoOriginal,
							financeiro_valor,
							financeiro_situacaoID,
							INDENTIFICADOR,
							financeiro_tipo,
							financeiro_grupo,
							financeiro_subgrupo,
							financeiro_caixa,
							financeiro_tipoPagamento,
							financeiro_hora,
							financeiro_nsu,
							financeiro_tipoQuem,
							financeiro_valorFim,
							financeiro_dataFim,
							financeiro_obs,
							financeiro_valorDesconto
						) VALUES (
							'1',
							'1',
							'$_idcliente',
							'".$_SESSION['NOME']."',
							'$_idpedido',
							'REF $_idpedido ',
							CURRENT_DATE(),
							'$vencimento',
							'$vencimento',
							'$total_pagto',
							'0',
							'1',
							'0',
							'1',
							'1',
							'$_idcaixa',
							'$_TIPOPGTO',
							'$datahora',
							'$NSU',
							'1',
							'$valor_pagoF',
							'$data_pagoF',
							'$obs',
							'$desconto_total'
						)";          
						$stm = $pdo->prepare($_SQL);	
						$stm->execute();
					
						if($contabiliza_caixa == '1'){
							//lanca valor no caixa
							$sql="INSERT INTO ". $_SESSION['BASE'] .".livro_caixa(
								Livro_Numero,
								Livro_caixa_valor_entrada,
								Livro_caixa_data_lancamento,
								Livro_caixa_data_hora_lancamento,
								Livro_caixa_usuario_lancamento,
								Livro_caixa_usuariio_alterado,
								Livro_caixa_Cod_Pagamento,
								Livro_hash,
								Livro_idfinanceiro,
								Livro_caixa_historico,
								Livro_caixa_motivo
							) VALUES (
								'$_idcaixa',
								'$total_pagto',
								'$datahora',
								'$datahora',
								'".$_SESSION['IDUSER']."',
								'".$_SESSION['NOME']."',
								'$_TIPOPGTO',
								'".$_SESSION['hash_caixa']."',
								'$_idpedido',
								'VENDA $_idpedido',
								'5'
							)";
							$stm = $pdo->prepare($sql);	
							$stm->execute();
						} //grava caixa

					} //busca total por tipo de pagamento

		//loop da forma de pagamentos===================================================================================================================================
				} 
		//		$i ++;
		//		$obs = "";			
		//	}
	
		


		
		//FIM loop da forma de pagamentos===============================================================================================================

        $_almox = 1;
               
            	//atualiza estoque
                $consultaLinha = $pdo->query("Select CODIGO_ITEM,QUANTIDADE,VALOR_UNIT_DESC,VALOR_TOTAL
				 from ". $_SESSION['BASE'] .".saidaestoqueitem 
                                              where  NUMERO = '".$_idpedido."' AND num_livro = '".$_idcaixa."' ");
								
                $retornoLinha = $consultaLinha->fetchAll();
                foreach ($retornoLinha as $row_a) {        
                    
                    $iditem = $row_a["CODIGO_ITEM"];
                    $qtde = $row_a["QUANTIDADE"];  
                    $valor = $row_a["VALOR_UNIT_DESC"]; 
                    $total = $row_a["VALOR_TOTAL"];
					$_totalprodutos = $_totalprodutos +  $row_a["VALOR_TOTAL"];

               

                        $consultaEST = $pdo->query("Select Qtde_Disponivel from ". $_SESSION['BASE'] .".itemestoquealmox 
                                                    where  Codigo_Item = '".$iditem."' AND Codigo_Almox = '".$_almox."'");
                        $retornoEST = $consultaEST->fetchAll();
                            foreach ($retornoEST as $rowEST) {     

                                $qtde_atual = $rowEST["Qtde_Disponivel"] - $qtde ;	
                            
                            } 

                          $_SQL = "Update ". $_SESSION['BASE'] .".itemestoquealmox  set Qtde_Disponivel = '$qtde_atual' 
                               where Codigo_Item  = '$iditem' and Codigo_Almox = '$_almox' ";
                            $stm = $pdo->prepare($_SQL);	
                            $stm->execute();	

                        $_SQL = " INSERT INTO ". $_SESSION['BASE'] .".itemestoquemovto
                                (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento,
                                Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,
                                Motivo,Saldo_Atual,Data_Movimento ) 
                                values 
                                ( '$iditem',
                                    '$qtde',
                                    '$_almox',
                                    's',
                                    'v',
                                    '$_idpedido','$valor','0','$total','$usuario','Saida por Venda','$qtde_atual','$datahora') ";
                        $stm = $pdo->prepare($_SQL);	
                        $stm->execute();	

                
                    }
					/*
					//busca total de produtos
						$consultaLinha = $pdo->query("Select sum(VALOR_TOTAL) as total 
						from ". $_SESSION['BASE'] .".saidaestoqueitem
						where  NUMERO = '".$_idpedido."' 
					");
						$retornoCol = $consultaLinha->fetchAll();
						$_regEmpresa = $consultaLinha->rowCount();
					
							foreach ($retornoCol as $row_COL) {  
							//   echo "<BR> TOTAL PRODUTOS ". $row_COL["total"];
								$_totalprodutos  =  $_totalprodutos +  $row_COL["total"];  
							}

                    //gerar informação para notas fiscais
                
                     $consultaNF = $pdo->query("
                              Select usuario_empresa,cc_empID,sum(cc_valor) as total,cc_usuario,cc_documento,cc_livro
                              FROM " . $_SESSION['BASE'] . ".contacorrente
                              LEFT JOIN " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO  = 	cc_usuario
                              LEFT JOIN  " . $_SESSION['BASE'] . ".empresa_cadastro e ON cc_usuario = e.id
                              WHERE  cc_documento = '".$_idpedido."' and cc_empID = '".$_SESSION['BASE_ID']."' 
                              GROUP BY usuario_empresa,cc_usuario,cc_documento,cc_livro");                     
                     $retornoNF= $consultaNF->fetchAll();
                  
                     foreach ($retornoNF as $row) {  
                      if($_SESSION['BASE_ID'] == $row['usuario_empresa'] or $row['usuario_empresa'] == "" or $row['usuario_empresa'] == "0"){
                        $_valorservico = $row['total']-$_totalprodutos;
                        $_totalprodutosG = $_totalprodutos;
                        $emp = $_SESSION['BASE_ID'];
                      }else{
                        $_valorservico = $row['total'];
                        $_totalprodutosG = 0;
                        $emp = $row['usuario_empresa'];
                      }

                      $_totalnf =  $_totalprodutosG+$_valorservico;

                            $_SQL = " INSERT INTO " . $_SESSION['BASE'] . ".notas
                            (nf_empresaid,nf_empresaemissao,nf_usuarioid,nf_data,nf_hora,nf_controle,nf_livro,nf_idconsumidor,
                            nf_nomeconsumidor,nf_vlrservico,nf_vlproduto,	nf_total,nf_situacao,nfse_status) 
                            values 
                            ( '".$row['cc_empID']."',
                              '".$emp."',
                              '".$row['cc_usuario']."',
                              CURRENT_DATE(),
                              NOW(),
                              '".$row['cc_documento']."',
                              '".$row['cc_livro']."',
                              '$_idcliente',
                              '$_nomecliente',
                              '".$_valorservico."',
                              '".$_totalprodutosG."',	
                              '".$_totalnf."',	
                              '0','pendente')";
                            
                            $stm = $pdo->prepare($_SQL);	
                            $stm->execute();	

                            //gravar emissão 
                     }
                   
                   
                    //fim notas fiscais

                    //atualizar evento agenda
                    $_SQL = "Update " . $_SESSION['BASE'] . ".eventos  set ev_sitagenda = '3' , ev_status = '6'
                    WHERE id_emp = '".$_SESSION['BASE_ID']."' and id_controle = '$_idpedido'";
                    $stm = $pdo->prepare($_SQL);	
                    $stm->execute();	

                    $_TOTALFINAL =number_format($_totalnf,2,',','.');
                    $_atividades = array(
                      '_bd' => $_SESSION['BASE'],	
                      'at_datahora' => date("Y-m-d H:i:s"),
                      'at_iduser' => $_SESSION['IDUSER'],	
                      'at_userlogin' => $_SESSION['USERAPELIDO'],	
                      'at_tipo'=> 9,	
                      'at_icliente'=> $id_consumidor,	
                      'at_idpet'=> $id_pet,		
                      'at_documento'=> $id_controle,			
                      'at_livro'=> '',		
                      'at_assunto'=> 'Pagamento efetuado',	
                      'at_descricao'=> "$_nomecliente R$ $_TOTALFINAL"	
                   );                              
                  
                   */
                //  Atividade::incluir($_atividades);
		?>

		<div class="row">
			<div class="col-sm-12" align="center">			
				<p><strong>* Venda Finalizada</strong> com sucesso !!!</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12" align="center">
					<img src="../assets/images/small/img_0008.png" alt="image" class="img-responsive " width="300"/>                                                    
			</div>
		</div>                            		
		<div class="row">
			<div class="col-sm-4" style="text-align: center;">
				<button type="button" class="btn btn-default btn-block"  onclick="NovaVenda()" >Nova venda</button>
			</div>
			<div class="col-sm-4" style="text-align: center;">
			<?php
			if($tipo == 2){	
				?>
					<button type="button" class="btn btn-primary btn-block"  onclick="_ImprimirVendaNF()" >Imprimir NFCe</button>
				<?php } else { ?>
					<button type="button" class="btn btn-primary btn-block"  onclick="_ImprimirVenda()" >Imprimir Cupom</button>
				<?php }
				?>
			</div>
			<div class="col-sm-4" style="text-align: center;">
				<button type="button" class="btn btn-danger btn-block"  onclick="Encerra_pdv()" >Fechar PDV</button>
			</div>
		</div> 
						
                
	    <?php
		/**
		 * Emissão de NFc
		 */
		if($tipo == 2){		
		
			try {
				date_default_timezone_set('America/Sao_Paulo');
			
				// Instância NFeService
				$nfe = new NFeService($idemp, 65);
				$numero_pedido= $_idpedido;
				$livro = $_idcaixa;
				//Gera e assina XML
			
			$xml = $nfe->gerarNFCe($_idpedido, $_idcaixa);
			
		
			$signedXML = $nfe->assinaNFe($xml);
	

				//Grava XML no banco e incrementa número de NF
				$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
				$xml = $consulta->fetch(PDO::FETCH_OBJ);

				if (!$xml) {
					$dataNFC = date('Y-m-d H:m:s');
					$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET proximo_numero_nfce_producao = proximo_numero_nfce_producao + 1 WHERE empresa_id = ?");
					$update->bindParam(1, $empresa);
					$update->execute();

				

					$consulta = $pdo->query("SELECT  proximo_numero_nfce_producao FROM ". $_SESSION['BASE'] . ".empresa  WHERE empresa_id = '$empresa'");
					$ret = $consulta->fetch(PDO::FETCH_OBJ);
					$numeroNFCe = $ret->proximo_numero_nfce_producao;

					$insert = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_pedido, nfed_numlivro, nfed_xml, nfed_hora,nfed_numeronf) VALUES(?, ?, ?, ?,?)");
					$insert->bindParam(1, $numero_pedido);
					$insert->bindParam(2, $livro);
					$insert->bindParam(3, $signedXML);
					$insert->bindParam(4, $dataNFC);
					$insert->bindParam(5, $numeroNFCe);
					$insert->execute();

				
					
				} else {
					$dataNFC = date('Y-m-d H:m:s');

					$update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?, nfed_hora = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
					$update->bindParam(1, $signedXML);
					$update->bindParam(2, $dataNFC);
					$update->bindParam(3, $numero_pedido);
					$update->bindParam(4, $livro);
					$update->execute();
				}

				$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".saidaestoque 
				SET SAIDA_NFE = '".$numeroNFCe."'  WHERE NUMERO= '$numero_pedido' AND num_livro = '$livro'");
				$update->execute();
				

				$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
				$xml = $consulta->fetch(PDO::FETCH_OBJ);

				//Transmite XML
			
					$recibo = $nfe->transmitir($xml->nfed_xml);
					
									
					$st = new Standardize();
      			    $stResponse = $st->toStd($recibo);
				
			//	$protocolo = $nfe->consultaChave($recibo);
				//Grava 
				$update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET nfed_recibo = ? , nfed_chave = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
				$update->bindParam(1, $recibo);
				$update->bindParam(2, $_CHAVE);
				$update->bindParam(3, $numero_pedido);
				$update->bindParam(4, $livro);
				$update->execute();

//				$consulta = $pdo->query("SELECT nfed_recibo FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
//				$recibo = $consulta->fetch(PDO::FETCH_OBJ);

				//Obtem protocolo e gera XML protocolado
				//$protocolo = $nfe->consultaRecibo($recibo);
			

				$verificaProtocolo = new Standardize();
				//$verificaProtocolo = $verificaProtocolo->toStd($protocolo);
				$verificaProtocolo = $verificaProtocolo->toStd($recibo);

				$_retmotivo = $verificaProtocolo->protNFe->infProt->xMotivo;
				$_retprotocolo = $verificaProtocolo->protNFe->infProt->nProt;
				$_CHAVE  = $verificaProtocolo->protNFe->infProt->chNFe;

				if ($verificaProtocolo->cStat != '104') {
					$update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET  nfed_motivo = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
					$update->bindParam(1, $verificaProtocolo->protNFe->infProt->xMotivo);
					$update->bindParam(2, $numero_pedido);
					$update->bindParam(3, $livro);
					$update->execute();
				} else {
				
					$dataProtocolo = date('Y-m-d H:m:s');
//echo "autoriza";
					$xmlProtocolado = $nfe->autorizaXml($xml->nfed_xml,$recibo);
			//	print_r($xmlProtocolado);
					$update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
					nfed_dataautorizacao = ?, 
					nfed_xml_protocolado = ?, 
					nfed_motivo = ?,											
					nfed_protocolo =  ?,
					nfed_chave = ?
					WHERE nfed_pedido = ? AND nfed_numlivro = ?");
					$update->bindParam(1, $dataProtocolo);
					$update->bindParam(2, $xmlProtocolado);
					$update->bindParam(3, $_retmotivo);				
					$update->bindParam(4, $_retprotocolo);	
					$update->bindParam(5, $_CHAVE);   								
					$update->bindParam(6, $numero_pedido);
					$update->bindParam(7, $livro);
					$update->execute();

					
				}

			} catch (\Exception $e) {
				//echo $e;
				echo $e->getmessage();
			}
		}
	} //fim pgto


	if($acao == "NovaVenda"){
		$_SESSION['numberPedido'] = "";
		$_SESSION['i'] = 0;
	}
	if($acao == "delItemCompra"){
		
		$id = $_POST['id'];

		//busca valor do item
		$sql="SELECT * FROM ".$_SESSION['BASE'].".saidaestoqueitem where ITEM = '$id' and NUMERO = '".$_SESSION['numberPedido']."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			$valorItem = $result->VALOR_TOTAL;
			$valorDesconto = $result->DESCONTO;
		}
		$valorTotal_item = $valorItem;

		//busca valor da venda
		$sql="SELECT * FROM ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_SESSION['numberPedido']."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			$valorVenda = $result->VL_Pedido;
			$valorVenda_desconto = $result->VL_DESCONTO;
		}

		$novoValor = $valorVenda-$valorTotal_item;
		$novoValor_Desconto = $valorVenda_desconto-$valorDesconto;

		//ATUALIZA VALOR DA VENDA
		$sql="UPDATE ".$_SESSION['BASE'].".saidaestoque set VL_Pedido = '$novoValor', VL_DESCONTO = '$novoValor_Desconto' where NUMERO = '".$_SESSION['numberPedido']."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		
		$sql="delete from ".$_SESSION['BASE'].".saidaestoqueitem where ITEM = '$id' and NUMERO = '".$_SESSION['numberPedido']."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
	}
	if($acao == "cancelaCompra"){
		
		$numero_pedido = $_SESSION['numberPedido'];

		$sql="update ".$_SESSION['BASE'].".saidaestoque set Cod_Situacao = '9' where NUMERO = '$numero_pedido'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
					
		$_SESSION['numberPedido'] = "";
		$_SESSION['i'] = 0;
	}

	
	if($acao == "descontoItematualiza"){
		$_numero_pedido = $_SESSION['numberPedido'];
	
		$id = $_POST['id'];
		?>
		<table class="table">
			<tr>
				<th class="lin">#</th>
				<th class="lin">DESCRIÇÃO</th>
				<th class="lin">QTDE</th>
				<th class="lin" style="min-width: 90px ;">VALOR UN.</th>
				<th class="lin" style="min-width: 90px ;">VALOR</th>
			</tr>
			<?php
				//busca valor desconto total da venda
				$sql = "select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."'";
				$stm = $pdo->prepare($sql);
				$stm->execute();
				while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 
					$descontoVenda = $rst->VL_DESCONTO_porc;
				}


				$sql = "select * from ".$_SESSION['BASE'].".saidaestoqueitem where NUMERO = '".$_numeropedido."'";
				$stm = $pdo->prepare($sql);
				$stm->execute();
				if($stm->rowCount() > 0){
					while($linha = $stm->fetch(PDO::FETCH_OBJ)){
						
					$totalValor = $totalValor + $linha->VALOR_TOTAL;
					if($linha->Valor_unitario_desc != $linha->VALOR_UNIT_DESC){
						$hideDesconto = "SIM";
					}else{
						$hideDesconto = "";
					}
			?>
					<tr>
						<td><b><?=$linha->ITEM?></b></td>
						<td><b><?=$linha->DESCRICAO_ITEM?></b></td>
						<td><b><?=$linha->QUANTIDADE?></b></td>
						<td><b><?="R$ ".number_format($linha->Valor_unitario_desc,2,',','.')?></b></td>
						<td><b><?="R$ ".number_format($linha->VALOR_TOTAL,2,',','.')?></b></td>
						<td class="delclass" style="padding:0px; padding-top:3px;"><span style="font-size:16px; color:red; font-weight:bold; cursor:pointer;" onclick="deletItem('<?=$linha->ITEM;?>')">X</span></td>
						<td class="desclass" style="padding:0px; padding-top:3px;"><?php if($hideDescontoxx == ""){ ?><span style="font-size:16px; color:orange; font-weight:bold; cursor:pointer;" onclick="descontoItem('<?=$linha->ITEM;?>')"><i class="fa fa-plus"></i></span><?php } ?></td>
					</tr>
				<?php } }
				$totalValor = $totalValor-$descontoVenda;
				?>
		</table>
		<input type="hidden"  id="totalVenda" name="TotalVenda" value="<?=number_format($totalValor,2,',','.');?>">
		<input type="hidden"  id="trocoC" name="trocoC" value="">
	<?php 

	}

	if($acao == "descontoItem"){
		
		$numero_pedido = $_SESSION['numberPedido'];
		$novoValor = $_POST['novoValor'];		
		$id = $_POST['id'];
		
		$novoValor = str_replace(".","",$novoValor);
		$novoValor = str_replace(",",".",$novoValor);
		
		
		if($novoValor != "" and $novoValor > '0'){
		
			$sql = "select * from  ".$_SESSION['BASE'].".saidaestoqueitem  
			where  NUMERO = '$numero_pedido' and ITEM = '$id'";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
			if($stm->rowCount() > 0){
				
				while($result = $stm->fetch(PDO::FETCH_OBJ)){
					$valorOriginal = $result->VALOR_UNITARIO;
					$quantidade = $result->QUANTIDADE;
					if($quantidade == '0'){
						$quantidade = 1;
					}
				}
				
				if($novoValor > $valorOriginal) {
					/*
					$sql="update ".$_SESSION['BASE'].".saidaestoqueitem set 
					VALOR_UNITARIO = '$novoValor', 
					Valor_unitario_desc = '$novoValor', 
					 VALOR_UNIT_DESC = '$novoValor', 
					 VALOR_TOTAL = '$valorTotal',
					  DESCONTO = '0', 
					  QUANTIDADE = '$quantidade' 
					  where NUMERO = '$numero_pedido' and ITEM = '$id'";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				
				*/
				$valorOriginal = $novoValor;

				}else{
					$ValorDescontado = $valorOriginal - $novoValor;
					$ValorDescontado = $ValorDescontado*$quantidade;
					
					if($ValorDescontado < '0'){
						$ValorDescontado = 0;
						$novoValor = $valorOriginal;
					}
				}
		
			
				
				$valorTotal = $novoValor*$quantidade;
				
			/*	$sql="update ".$_SESSION['BASE'].".saidaestoqueitem set VALOR_UNIT_DESC = '$novoValor', VALOR_TOTAL = '$valorTotal', DESCONTO = '$ValorDescontado', QUANTIDADE = '$quantidade' where NUMERO = '$numero_pedido' and ITEM = '$id'";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				
				*/
				$sql="update ".$_SESSION['BASE'].".saidaestoqueitem set 
					VALOR_UNITARIO = '$valorOriginal', 
					Valor_unitario_desc = '$novoValor', 
					 VALOR_UNIT_DESC = '$valorOriginal', 
					 VALOR_TOTAL = '$valorTotal',
					  DESCONTO = '0', 
					  QUANTIDADE = '$quantidade' 
					  where NUMERO = '$numero_pedido' and ITEM = '$id'";
					
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				$sql2="select VL_DESCONTO,VL_Pedido from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '$numero_pedido'";
				$stm2 = $pdo->prepare($sql2);	
				$stm2->execute();
				while($result2 = $stm2->fetch(PDO::FETCH_OBJ)){
					$descontoGeral = $result2->VL_DESCONTO;
					$descontoGeral = $descontoGeral  + $ValorDescontado;

					$novo_valor_total = $result2->VL_Pedido - $ValorDescontado;
					
					$sql="update ".$_SESSION['BASE'].".saidaestoque set VL_DESCONTO = '$descontoGeral', VL_Pedido = '$novo_valor_total' where NUMERO = '$numero_pedido'";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
					
				}
			
			}	
		
		}

	}

	if($acao == "descontoVenda"){
		
		$numero_pedido = $_SESSION['numberPedido'];
		$novoValor = $_POST['novoValor'];
		$tipoDesconto = $_POST['tipoDesconto'];
		
		
		$novoValor = str_replace(".","",$novoValor);
		$novoValor = str_replace(",",".",$novoValor);
		
		if($novoValor != "" and $novoValor > '0'){

			$sql = "select VL_Pedido,VL_DESCONTO,VL_DESCONTO_porc from  ".$_SESSION['BASE'].".saidaestoque  where  NUMERO = '$numero_pedido'";
			$stm = $pdo->prepare($sql);	
			$stm->execute();
				
			while($result = $stm->fetch(PDO::FETCH_OBJ)){
				$valorOriginal = $result->VL_Pedido;
				$valor_descontoItem = $result->VL_DESCONTO;
				$valor_descontoVenda = $result->VL_DESCONTO_porc;

				$total_valor_semDesconto = $valorOriginal+$valor_descontoVenda;
			}

			$total_valor = $valorOriginal;

			//se for tipo R$
			
			if($tipoDesconto == '2'){

				$valor_desconto_fim = $total_valor_semDesconto-$novoValor;

				$sql="update ".$_SESSION['BASE'].".saidaestoque set VL_DESCONTO_porc = '$valor_desconto_fim', VL_Pedido = '$novoValor' where NUMERO = '$numero_pedido'";
				$stm = $pdo->prepare($sql);	
				$stm->execute();

			}
			//se for tipo %
			if($tipoDesconto == '1'){


				
				$porcentagem_valor = ($total_valor/100)*$novoValor;

				$novo_total = $total_valor-$porcentagem_valor;
				
				
				$sql="update ".$_SESSION['BASE'].".saidaestoque set VL_DESCONTO_porc = '$porcentagem_valor', VL_Pedido = '$novo_total' where NUMERO = '$numero_pedido'";
				$stm = $pdo->prepare($sql);	
				$stm->execute();


			}
		
	
		
		}

	}	
	if($acao == "cancelaDesconto"){

	$numero_pedido = $_SESSION['numberPedido'];

		//soma valor inicial da venda
		$sql="SELECT * FROM  ".$_SESSION['BASE'].".saidaestoqueitem where NUMERO = '$numero_pedido'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			$total_itens = $result->Valor_unitario_desc*$result->QUANTIDADE;
			$novoValor = $novoValor + $total_itens;

		}

		$sql="update ".$_SESSION['BASE'].".saidaestoque set VL_DESCONTO_porc = '0', VL_Pedido = '$novoValor' where NUMERO = '$numero_pedido'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();

	}
	if($acao == "reimpressaoCupom"){

		$numero_pedido = $_POST['numeroVenda'];
		
		$sql = "select * from ".$_SESSION['BASE'].".saidaestoque  where  NUMERO = '$numero_pedido'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		if($stm->rowCount() > 0){
			while($result = $stm->fetch(PDO::FETCH_OBJ)){
				$situacao = $result->Cod_Situacao;
				if($situacao == "3" or $situacao == "93"){
					$_SESSION['numberPedido'] = $numero_pedido;
				}else{
					echo 'Essa venda ainda não foi finalizada!'. $situacao;
				}
			}
		}else{
			echo 'Número da venda incorreta!';
		}
	}

	
	if($acao == "senhaCancela"){
		$sql = "select * from ".$_SESSION['BASE'].".parametro";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			echo($result->codigopermissaocancelamento);
		}
	}

	if($acao == "senhaDesconto"){
		$sql = "select * from ".$_SESSION['BASE'].".parametro";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			echo($result->codigopermissao);
		}
	}
	
	if($acao == "configCaixa"){
		?>
		<div class="row">
			<div class="col-sm-8">
				<h4>Logomarca</h4>
				
			</div>
			<div class="col-sm-4"></div>
		</div>
		<?php
	}
	
	if($acao == "seleciona_caixa"){
		$id = $_POST['id'];

		$update = "update ".$_SESSION['BASE'].".livro_caixa_numero set Ind_Sel = '0' where Livro_Numero = '$id'";
		$stm = $pdo->prepare($update);	
		$stm->execute();

	}
	if($acao == "valorItem"){
		//verifica valor do item, para desconto
		$id = $_POST['id'];

		$sql="SELECT * FROM ".$_SESSION['BASE'].".saidaestoqueitem where ITEM = '$id'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			echo($result->VALOR_UNITARIO);
		}
	}
	if($acao == "att_valor_caixa"){

		$caixa = $_SESSION['id_caixa'];
		$hash = $_SESSION['hash_caixa'];
		$nome = $_SESSION['NOME'];
		$id_user = $_SESSION['IDUSER'];

		$tipo =  $_POST['tipo'];
		$obs = $_POST['obs'];
		$motivo = $_POST['motivo'];

		$novoLancamento = $_POST['n_valor'];
		$novoLancamento = str_replace(".","",$novoLancamento);
		$novoLancamento = str_replace(",",".",$novoLancamento);


		if($tipo == '1'){
			$valor_entrada = $novoLancamento;
		}else{
			$valor_saida = $novoLancamento;
		}

		//adiciona valor ao caixa
		$sql="INSERT INTO ".$_SESSION['BASE'].".livro_caixa (
			Livro_Numero,
			Livro_caixa_valor_entrada,
			Livro_caixa_valor_saida,
			Livro_caixa_data_lancamento,
			Livro_caixa_data_hora_lancamento,
			Livro_caixa_usuariio_alterado,
			Livro_caixa_Cod_Pagamento,
			Livro_hash,
			Livro_caixa_usuario_lancamento,
			Livro_caixa_motivo,
			Livro_caixa_historico
			) values (
				'$caixa',
				'$valor_entrada',
				'$valor_saida',
				'$datahora',
				'$datahora',
				'$nome',
				'4',
				'$hash',
				'$id_user',
				'$motivo',
				'$obs'
			)";
		$stm = $pdo->prepare($sql);	
		$stm->execute();

		?>
			<div class="row">
				<div class="col-sm-12" align="center">			
					<p><strong>* Valor Atualizado</strong> com sucesso !!!</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12" align="center">
						<img src="../assets/images/small/att_valor.png" alt="image" class="img-responsive " width="300"/>                                                    
				</div>
			</div>                            		
			<div class="row">
				<div class="col-sm-12" style="text-align: center;">
					<button type="button" class="btn btn-default" onclick="fecharModal2()" >Fechar</button>
				</div>
			</div>		
		<?php
		
	}
	if($acao == "tela_attCx"){
		?>
		<h4>Entrada/Retirada de valor no Caixa:</h4>
		<hr>
		<div class="row">
			<div class="col-sm-3">
				<label>Senha Gerencial</label>
				<input type="password" class="form-control" id="snGerencial" name="snGerencial" placeholder="••••">
			</div>
			<div class="col-sm-3">
				<label>Tipo Lançamento</label>
				<select class="form-control" id="tipo_lancamentoCX" name="tipo_lancamentoCX">
					<option value="1">Entrada</option>
					<option value="2">Saída</option>
				</select>
			</div>
			<div class="col-sm-3">
				<label>Valor</label>
				<input type="tel" class="form-control" id="valor_caixa" name="valor_caixa" placeholder="0,00" onKeyPress="return(moeda(this,'.',',',event));">
			</div>
			<div class="col-sm-3">
				<label>Motivo</label>
				<select class="form-control" id="tipo_motivo" name="tipo_motivo">
					<?php 
						$sql="select * from ".$_SESSION['BASE'].".tiposaida where COD_TIPO_SAIDA <> '1' ";
						$stm = $pdo->prepare($sql);	
						$stm->execute();
						while($result = $stm->fetch(PDO::FETCH_OBJ)){
							?><option value="<?=$result->COD_TIPO_SAIDA;?>"><?=$result->DESCRICAO;?></option><?php
						}
					?>

				</select>
			</div>
			<div class="col-sm-9" style="margin-top:10px;">
				<label>Observações</label>
				<input type="tel" class="form-control" id="obs_cx" name="obs_cx" placeholder="Observações">
			</div>
			<div class="col-sm-3" style="margin-top:31px;">
				<button type="button" class="btn btn-primary btn-block" onclick="att_valor()" style="padding:9px;"><i class="fa fa-check"></i></button>
			</div></div>
			<div id="msgS" style="color:red; width:68%; text-align:center;">&nbsp;</div>
		<?php
	}
	if($acao == "buscaCliente"){
		$tipoBB = $_POST['tipoBB'];
		$bbconsumidor = $_POST['bbconsumidor'];

		if($tipoBB == '0'){
			$bbconsumidor = str_replace(".","",$bbconsumidor);
			$bbconsumidor = str_replace("-","",$bbconsumidor);
			$bbconsumidor = str_replace("/","",$bbconsumidor);
			$filter = "where CGC_CPF = '$bbconsumidor'";
			
		}
		if($tipoBB == '1'){
			$filter = "where Nome_Consumidor like '%$bbconsumidor%'";
		}
		?>
		<table class="table">
			<tr>
				<th>Ação</th>
				<th>Nome</th>
				<th>CPF/CNPJ</th>
			</tr>
				<?php
				$sql="SELECT * FROM ".$_SESSION['BASE'].".consumidor $filter";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				if($stm->rowCount() > 0){
					while($result = $stm->fetch(PDO::FETCH_OBJ)){
						?>
						<tr>
							<td><i class="fa fa-check" style="color:blue; cursor:pointer;" onclick="selCliente('<?=$result->CODIGO_CONSUMIDOR;?>');"></i></td>
							<td><?=$result->Nome_Consumidor;?></td>
							<td><?=$result->CGC_CPF;?></td>
						</tr>
						<?php
					}
				}else{
					?>
					<tr>
						<td colspan="3" align="center">NENHUM CLIENTE ENCONTRADO...</td>
					</tr>
					<?php
				}
		?>
			</table>
		<?php


	}
	if($acao == "insereConsumidor"){

		$idCliente = $_POST['idCliente'];
		
		$sql="SELECT * FROM ".$_SESSION['BASE'].".consumidor WHERE CODIGO_CONSUMIDOR = '$idCliente'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){

			$nome = $result->Nome_Consumidor;
			$cpfcnpj = $result->CGC_CPF;
			//ATUALIZA ATENDIMENTO
			$sql2="UPDATE ".$_SESSION['BASE'].".saidaestoque set CLIENTE = '$nome ', cpfcnpj = '$cpfcnpj' , CODIGO_CLIENTE = '$idCliente'  WHERE NUMERO = '".$_SESSION['numberPedido']."'";
			$stm2 = $pdo->prepare($sql2);	
			$stm2->execute();

			echo $nome.'|'.$cpfcnpj;
		}


	}
	if($acao == "ReabrirVenda"){

		$dataIni = $_POST['dataIni'];
		$dataFim = $_POST['dataFim'];
		$descricao = $_POST['descricao'];

		if($dataIni == ""){
			$dataIni = date('Y-m-d');
		}
		if($dataFim == ""){
			$dataFim = date('Y-m-d');
		}
		if($descricao != ""){
			$fil_descricao = "and NUMERO = '$descricao'";
		}
		?>
			<div class="row">
				<div class="col-sm-3">
					<label>Periodo de</label>
					<input type="date" class="form-control" name="dataIniV" id="dataIniV" value="<?=$dataIni;?>" >
				</div>
				<div class="col-sm-3">
					<label>Até</label>
					<input type="date" class="form-control" name="dataFimV" id="dataFimV" value="<?=$dataFim;?>" >
				</div>
				<div class="col-sm-3">
					<label>Numero Controle</label>
					<input type="text" class="form-control" name="descricaoV" id="descricaoV" value="<?=$descricao;?>" >
				</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-primary btn-block" style="margin-top:23px" onclick="reabrirVenda('1')">BUSCAR</button>
				</div>
			</div>
			<div class="row" style="margin-top:20px;">
				<div class="col-sm-12">
					<table class="table">
						<tr>
							<th>Selecionar</th>
							<th>N° Venda</th>
							<th>Data</th>
							<th>Consumidor</th>
							<th>Pet</th>
							<th>Cpf/Cnpj</th>
							<th>Situação</th>
						</tr>
							<?php 
								$sql="SELECT *,DATE_FORMAT(DATA_CADASTRO , '%d/%m/%Y') as data 
								FROM ".$_SESSION['BASE'].".saidaestoque								
								LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao
								WHERE DATA_CADASTRO between '$dataIni' and '$dataFim' and saidaestoque.Cod_Situacao <> '93' and saidaestoque.Cod_Situacao <> '99' and NUMERO <> '".$_SESSION['numberPedido']."' $fil_descricao";
								$stm = $pdo->prepare($sql);	
								$stm->execute();
								if($stm->rowCount() > 0){
									while($result = $stm->fetch(PDO::FETCH_OBJ)){
										?>
											<tr>
												<td><button class="btn btn-default" onclick=""><i class="fa fa-check"></i></button></td>
												<td><?=$result->NUMERO;?></td>
												<td><?=$result->data;?></td>
												<td><?=$result->CLIENTE;?></td>
												<td><?=$result->CODIGO_PET;?></td>
												<td><?=$result->cpfcnpj;?></td>
												<td><span class="label label-table <?=$result->label_ped;?>"><?=$result->Descricao;?></span>
													<?php  if($result->origem == 1) { ?>
														<span class="label label-table label-warning">PDV</span>
													<?php } ?>
												</td>
											</tr>
										<?php
									}	
								}else{
									?>
										<tr>
											<td colspan="7" align="center">Nenhum resultado encontrado</td>
										</tr>
									<?php									
								}
					
							?>					
					</table>
				</div>
			</div>

		<?php




	}

	if($acao == "finalizaSalva"){ ?>
					<div class="card-box">  
                                            <div class="row">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-8">
                                                     <label for="field-1" class="control-label">Selecione opção:</label>                                                     
													 <button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light" style="margin:10px"  onclick="_btImprimirCompleto();"><i class="fa  fa-print"></i> Imprimir Pedido</button>
                                                     <button type="button" class="btn btn-block btn--md waves-effect waves-light btn-warning" style="margin:10px" <?php if($_situacaoPedido == 3) { echo 'disabled'; } ?> onclick="_btOrcamento('<?=$_idpedido;?>')"><i class="fa   fa-shopping-cart"></i> Gerar e Imprimir Orçamento</button>
													  <?php if($_situacaoPedido == 3) { ?>
														<button type="button" class="btn btn-block btn--md waves-effect waves-light btn-warning" style="margin:10px" onclick="_btNFe()" disabled><i class="fa   fa-file-text-o"></i> Gerar NF-e (mod 55)</button>
														<button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light" style="margin:10px" onclick="_btFechar();"><i class="fa   fa-check-square-o"></i> Fechar </button> 
														<?php  } else {  ?>
														<button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light" style="margin:10px" onclick="_btSalvar();"><i class="fa   fa-check-square-o"></i> Salvar Fechar </button> 

														<?php } ?>                                                    
                                                </div>  
                                                <div class="col-md-2">                                                    
                                                </div>                                                                                           
                                            </div>
											<div class="row" id="retacao">
													     
											</div>
               </div>
<?php
	}

	if($acao == "btSalvar"){ 	
		//atualiza saida estoque
	/*	$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '1' 
		WHERE NUMERO = '" . $_idpedido . "' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	
		*/
		?>		
			<div class="alert alert-success alert-dismissable"> 
				Salvo com sucesso !!! 
				<script>
					fechar_pdv();
				</script>
							
			</div>        	
	<?php
	}



	if($acao == "btImprimirCompleto"){ 	
		//atualiza saida estoque
		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '1' 
		WHERE NUMERO = '" . $_idpedido . "' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	
		?>		
			completo
	<?php
	}

	if($acao == "btOrcamento"){ 	
		//atualiza saida estoque
		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '4' 
		WHERE NUMERO = '" . $_idpedido . "' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	
		?>		
			Orçamento 
	<?php
	}




	if($acao == "desconecta_venda"){
		$_SESSION['numberPedido'] = "";
	}
	?>