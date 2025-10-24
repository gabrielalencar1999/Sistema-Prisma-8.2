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
	use Functions\APIecommerce;
	use Functions\Acesso;

	$_retviewerCE = Acesso::customizacao('34'); //remover caracteres especiais

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

	function remove($_texto) {
		$_texto =    str_replace(")", "", $_texto);
		$_texto =    str_replace("(", "", $_texto);
		$_texto =    str_replace("/", "", $_texto);
		$_texto =    str_replace(".", "", $_texto);
		$_texto =    str_replace(",", "", $_texto);
		$_texto =    str_replace("-", "", $_texto);
		return $_texto;
	} 

	

		function limparTexto(string $string, bool $permitirEspacos = false): string
					{
						// Mapa manual de substituição de caracteres acentuados
						$mapa = [
							'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
							'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
							'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
							'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
							'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
							'ç' => 'c',

							'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
							'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
							'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
							'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
							'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
							'Ç' => 'C',
						];

					 // Substitui acentos e ç/Ç
						$string = strtr($string, $mapa);

						// Remove tudo que não for letra, número, espaço ou underline
						$string = preg_replace('/[^a-zA-Z0-9 _]/u', '', $string);

						return $string;
					}

//$_SESSION['id_caixa'] = 1;
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

$dataATUAL      = $ano . "-" . $mes . "-" . $dia ;

	$acao = $_POST['acao'];

	
	
	    $empresa  = $_POST['empresa'];
		if($empresa  > '1'){
			//$empresa = '1';
		}else{
			$empresa = '1';
		}
		
		$_idfrefGO = $_POST['ref'];
		$_idfref = base64_decode($_idfrefGO);



	$_idfref = explode('-',$_idfref);
	if($_idfref[1] != "") { 
		$_numeropedido = $_idfref[1];
		
	}else{
		$_numeropedido = $_numeropedido;
	
		
	}
	$numero_pedido = $_numeropedido;
	/*
	if($_POST['ref'] != ""){
		$_numeropedido = $_POST['ref'];	
		$_Numeropedido = $_numeropedido;
		$_idpedido = $_numeropedido;
	}
*/



$_idpedido = $_numeropedido;
$OBSERVACAO =  $_POST['obs'];
$almox =  $_POST['almox'];

if($almox == "") { $almox = 1;};
$_statusVenda =  $_POST['_status'];


	//$_idcaixa = 1;
	$_idcaixa =  $_SESSION['id_caixa'] ;
	if($_idcaixa == "") {
		$_idcaixa = 1;
	}
	$usuario = $_SESSION['tecnico'];

	//empresa_vizCodInt codigo visualização interno
		$query = ("SELECT empresa_vizCodInt,empresa_validaestoque from  " . $_SESSION['BASE'] . ".parametro  ");
		$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
		while ($rst = mysqli_fetch_array($result)) {
			$_vizCodInterno = $rst["empresa_vizCodInt"];	
			$_validaestoque = $rst["empresa_validaestoque"];		
		}

	$consultaLinha = $pdo->query("Select CODIGO_CLIENTE,Cod_Situacao from ". $_SESSION['BASE'] .".saidaestoque
	where  NUMERO = '".$_idpedido."' ");
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


	
if($acao == "selcx" ){

		if($_POST['idsel']!= "") {
			$_SESSION['id_caixa'] = $_POST['idsel'];
			$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET num_livro = '".$_SESSION['id_caixa']."' WHERE NUMERO = '" . $_idpedido . "' ";               
				$stm = $pdo->prepare("$_SQL");	
				$stm->execute();

				$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoqueitem SET num_livro = '".$_SESSION['id_caixa']."' WHERE NUMERO = '" . $_idpedido . "' ";               
				$stm = $pdo->prepare("$_SQL");	
				$stm->execute();

						$sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero where Livro_Numero = '".$_SESSION['id_caixa']."'  ";
						$stmcx = $pdo->prepare($sql);
						$stmcx->execute();
						foreach($stmcx->fetchAll(PDO::FETCH_ASSOC) as $value){
							?>
							<span class="label label-table label-primary" style="font-size: 20px;"  onclick="_cxviewer()"><?=$value['Descricao'];?></span>
							<?php 

						}
			
		}
	

}

	if($acao == "validaestoque" ){
		$_SELBX =  $_POST['bx'];

		
	
		if($_validaestoque == 1 and $_SELBX == 1) {
				$sql = "select * from ".$_SESSION['BASE'].".saidaestoqueitem where NUMERO = '".$_numeropedido."'";
				$stm = $pdo->prepare($sql);
				$stm->execute();
				if($stm->rowCount() > 0){
					while($linha = $stm->fetch(PDO::FETCH_OBJ)){			
					$codigoProduto =  $linha->CODIGO_ITEM;
					//$almox  =  $linha->Cod_Almox;					
					$qtde  =  $linha->QUANTIDADE;
				//validar estoque				
					$_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
					WHERE Codigo_Item  = '" . $codigoProduto . "' AND  Codigo_Almox = '$almox'";  
				   
					$stm2 = $pdo->prepare($_sql);
					$stm2->execute();
					foreach ($stm2->fetchAll() as $rstest) { 
						$Qtde_Disponivel = $rstest['Qtde_Disponivel'];
					}
					if($qtde > $Qtde_Disponivel) { 
						echo "sem estoque";
					}
				
				
			
				} 
			}
	
	

		}//fim validaestoque
			

	}
	if($acao == "validaestoqueViewer" ){
		$_SELBX =  $_POST['bx'];

		if($_validaestoque == 1 and $_SELBX == 1) {
	
						?>
	
		<table class="table">
			<tr>
				<th class="lin">#</th>
				<th class="lin">CODIGO</th>
				<th class="lin">DESCRIÇÃO</th>
				<th class="lin">QTDE</th>
				<th class="lin" style="min-width: 90px ;">VALOR UN.</th>
				<th class="lin" style="min-width: 90px ;">TOTAL</th>
			</tr>
			<?php
				//busca valor desconto total da venda
				$sql = "select VL_DESCONTO_porc,Valor_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."'";
				
				$stm = $pdo->prepare($sql);
				$stm->execute();
				while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 
					$descontoVenda = $rst->VL_DESCONTO_porc;
					$entrada = $rst->Valor_Entrada;
				}


				$sql = "select ITEM,CODIGO_FABRICANTE,DESCRICAO_ITEM,QUANTIDADE,VALOR_UNIT_DESC,VALOR_TOTAL,Valor_unitario_desc,CODIGO_ITEM
									 FROM ".$_SESSION['BASE'].".saidaestoqueitem 
									 LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = CODIGO_ITEM
									 where NUMERO = '".$_numeropedido."'";
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

					$codigoProduto =  $linha->CODIGO_ITEM;
					//$almox  =  $linha->Cod_Almox;
					
					$qtde  =  $linha->QUANTIDADE;

					
				
			?>
					<tr>
						<td><b><?=$linha->ITEM?></b></td>
						<td><b><?=$linha->CODIGO_FABRICANTE;?></b></td>
						<td><b><?=$linha->DESCRICAO_ITEM?></b></td>
						<td><b><?=$linha->QUANTIDADE?></b></td>
						<td style="text-align: center;"><b><?="R$ ".number_format($linha->Valor_unitario_desc,2,',','.')?></b></td>
						<td style="text-align: center;"><b><?="R$ ".number_format($linha->VALOR_TOTAL,2,',','.')?></b></td>
						<td class="delclass" style="padding:0px; padding-top:3px;"><span style="font-size:16px; color:red; font-weight:bold; cursor:pointer;" onclick="deletItem('<?=$linha->ITEM;?>')">X</span></td>
						<td class="desclass" style="padding:0px; padding-top:3px;"><?php if($hideDescontoxx == ""){ ?><span style="font-size:16px; color:orange; font-weight:bold; cursor:pointer;" onclick="descontoItem('<?=$linha->ITEM;?>')"><i class="fa fa-plus"></i></span><?php } ?></td>
					</tr>
				<?php 
				//validar estoque
				
					$_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
					WHERE Codigo_Item  = '" . $codigoProduto . "' AND  Codigo_Almox = '$almox'";         
					$stm2 = $pdo->prepare($_sql);
					$stm2->execute();
					foreach ($stm2->fetchAll() as $rstest) { 
						$Qtde_Disponivel = $rstest['Qtde_Disponivel'];
					}
					if($qtde > $Qtde_Disponivel) { 
						?>
					<tr>
						<td colspan="5" style="background-color:#ef9c9c"><?php echo "<strong>Estoque insulficiente, qtde disponível $Qtde_Disponivel</strong>";?> </td>
					</tr>
						<?php
					}
				
				
			
			} }
				$totalValor = $totalValor-$descontoVenda;
				?>
		</table>
		<input type="hidden"  id="totalVenda" name="TotalVenda" value="<?=number_format($totalValor,2,',','.');?>">
		<input type="hidden"  id="trocoC" name="trocoC" value="">
	<?php 
		//busca valor do pedido
		$sql2="SELECT SUM(VALOR_TOTAL) AS T FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE NUMERO = '$_numeropedido'";
		$stm2 = $pdo->prepare($sql2);
		$stm2->execute();
		foreach ($stm2->fetchAll() as $rst) { 
			$valorPedido = $rst['T'];
		}

		//$valor_pedido_atualizado = $valorPedido+$totalProduto;
		$valor_pedido_atualizado = $valorPedido;

		}//fim validaestoque
			

	}

	if($acao == "lerCodigoBarra" or $acao == "ProdutoNaoCadastrado" ){
		$cod = $_POST["cod"];
		$qtde = $_POST["qtde"];
		$almox = $_POST["almox"];
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
				$sql2="insert into ".$_SESSION['BASE'].".saidaestoqueitem (NUMERO, ITEM, CODIGO_ITEM, QUANTIDADE, Valor_unitario_desc, VALOR_UNITARIO, VALOR_TOTAL, VALOR_UNIT_DESC, QUANTIDADE_ATEND, SALDO_ATEND,DATA_COMPRA, QTDE_BAIXA, DESCRICAO_ITEM, Ind_Aut, Ind_Estok , Valor_Custo , 	num_livro, HORA_COMPRA , Cod_Atendente, tabela_preco,Cod_Almox,vlr_vendaorigem) values('".$_numeropedido."','".$ITEM."','$cod','$qtde','$valorProduto','$valorProduto','$totalProduto','$valorProduto','$qtde','$qtde',CURRENT_DATE,'$qtde' , '$descricaoProduto','1', '-1' , '$valorCusto','$_idcaixa','$datahora', '".$_SESSION['login']."','Tab_Preco_5','$almox','$valorProduto')";
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
				}
				if($stm->rowCount() == 0){
					$_refsku  = str_pad(trim($cod), 18, '0', STR_PAD_LEFT);
					$sql = "select * from ".$_SESSION['BASE'].".itemestoque where Codigo_Referencia_Fornec = ? and Ind_Prod <> '2'  limit 1";
					$stm = $pdo->prepare($sql);	
					$stm->bindParam(1,$_refsku, \PDO::PARAM_STR);					
					$stm->execute();
					
				}
				if($stm->rowCount() == 0){
					
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
						$NCM =  $linha->Cod_Class_Fiscal ?? '';

                                                    if ($NCM === '00000000' || strlen($NCM) !== 8) {
                                                        $stmt = $pdo->prepare("
                                                            SELECT tabncm_ncm 
                                                            FROM bd_prisma.tab_ncmproduto  
                                                            WHERE tabncm_codfabricante = ? 
                                                            LIMIT 1
                                                        ");
                                                        $stmt->execute([$linha->CODIGO_FABRICANTE ?? null]);

                                                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $NCM = $row['tabncm_ncm'];
                                                            //update
                                                              $stmt = $pdo->prepare(" UPDATE ". $_SESSION['BASE'] .".itemestoque SET Cod_Class_Fiscal = ?  WHERE CODIGO_FORNECEDOR = ?    LIMIT 1");
                                                               $stmt->execute([$NCM, $codigoProduto ?? null]); 
                                                        }
                                                    }
						
						if($msg == "" ){
						
							$valorCusto = $linha->PRECO_CUSTO;
							$valorProduto = $linha->Tab_Preco_5;
							$descricaoProduto = $linha->DESCRICAO;
							$totalProduto = $valorProduto*$qtde;

							if($_retviewerCE == 1){
								$descricaoProduto = limparTexto($linha->DESCRICAO);
							}
							
							//insere produto no pedido
							$sql2="insert into ".$_SESSION['BASE'].".saidaestoqueitem (NUMERO, ITEM, CODIGO_ITEM, QUANTIDADE, Valor_unitario_desc, VALOR_UNITARIO, VALOR_TOTAL, VALOR_UNIT_DESC, QUANTIDADE_ATEND, SALDO_ATEND,DATA_COMPRA, QTDE_BAIXA, DESCRICAO_ITEM, Ind_Aut, Ind_Estok , Valor_Custo , 	num_livro, HORA_COMPRA , Cod_Atendente, tabela_preco,Cod_Almox,vlr_vendaorigem) values('".$_numeropedido."','".$ITEM."','$codigoProduto','$qtde','$valorProduto','$valorProduto','$totalProduto','$valorProduto','$qtde','$qtde',CURRENT_DATE,'$qtde' , '$descricaoProduto','1', '-1' , '$valorCusto','$_idcaixa','$datahora', '".$_SESSION['login']."','Tab_Preco_5','$almox','$valorProduto')";
							$sql3 = $sql2;
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
				<th class="lin">CODIGO</th>
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


				$sql = "select ITEM,CODIGO_FABRICANTE,DESCRICAO_ITEM,QUANTIDADE,VALOR_UNIT_DESC,VALOR_TOTAL,Valor_unitario_desc
				FROM ".$_SESSION['BASE'].".saidaestoqueitem 
				LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = CODIGO_ITEM
				where NUMERO = '".$_numeropedido."'";
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
						<td><b><?=$linha->CODIGO_FABRICANTE;?></b></td>
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
		//busca valor do pedido
		$sql2="SELECT SUM(VALOR_TOTAL) AS T FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE NUMERO = '$_numeropedido'";
		$stm2 = $pdo->prepare($sql2);
		$stm2->execute();
		foreach ($stm2->fetchAll() as $rst) { 
			$valorPedido = $rst['T'];
		}

		//$valor_pedido_atualizado = $valorPedido+$totalProduto;
		$valor_pedido_atualizado = $valorPedido;

		//atualiza valor da venda
		$sql2="UPDATE  ".$_SESSION['BASE'].".saidaestoque set VL_Pedido = '$valor_pedido_atualizado' where NUMERO = '$_numeropedido'";
		$stm2 = $pdo->prepare($sql2);
		$stm2->execute();	
} 
	
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

				if($OBSERVACAO != "" or $_statusVenda > 0){
					$sql = "update ".$_SESSION['BASE'].".saidaestoque set se_status = '$_statusVenda', OBSERVACAO = ? where NUMERO = '".$_numeropedido."'";
					$stm = $pdo->prepare($sql);	
					$stm->bindParam(1,$OBSERVACAO, \PDO::PARAM_STR);
					$stm->execute();
				}
			

				$sql="DELETE FROM ".$_SESSION['BASE'].".saidaestoquepgto where spgto_numpedido = '".$_numeropedido."' ";
				$stm = $pdo->prepare($sql);	
				$stm->execute();
		
				$sql="select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."' ";			
				$stm = $pdo->prepare($sql);	
				$stm->execute();
				$result = $stm->fetch(PDO::FETCH_OBJ);

				$cpfCnpj = $result->cpfcnpj;
				$entrada= $result->Valor_Entrada;
				$desconto_item = $result->VL_DESCONTO;
				$desconto_venda = $result->VL_DESCONTO_porc;
				$id_consumidorAtendimento = $result->CODIGO_CLIENTE;
				
				$sql="select * from ".$_SESSION['BASE'].".saidaestoqueitem where NUMERO = '".$_numeropedido."' ";
				$stm = $pdo->prepare($sql);	
				$stm->execute();

				while($result = $stm->fetch(PDO::FETCH_OBJ)){
				
					$total = $total + $result->Valor_unitario_desc*$result->QUANTIDADE;
					
				}
				
				//$totalComDesconto = 0 ;
				$totalComDesconto = $total - $desconto_item - $desconto_venda - $entrada;
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
									<input type="text" class="form-control" name="vlrPag1" id="vlrPag1" onKeyPress="return(moeda(this,'.',',',event));" onkeyup="soma();" value="<?=number_format($total-$entrada,2,',','.');?>" onblur="calc_parcela('1',this.value)">
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
									<label>Empresa</label>
									<?php
									$sqlEmp = "Select * from " . $_SESSION['BASE'] . ".empresa ";
									$consultaEmp = $pdo->query($sqlEmp);                                                    
									if ($consultaEmp->rowCount() > 1){
										$retornoEmp = $consultaEmp->fetchAll(\PDO::FETCH_OBJ);
									?>
														 <select name="empresa" id="empresa" class="form-control input-sm">
                                                            <?php
                                                              foreach ($retornoEmp as $rowEmp) {
                                                            ?>
                                                            <option value="<?=$rowEmp->empresa_id;?>" <?php if ($rowEmp->empresa_id ==  $empresaOS) { ?>selected="selected" <?php } ?> ><?=$rowEmp->empresa_nome;?></option>        
                                                             <?php
                                                            } ?>
                                                            </select>
									<?php } else { ?>
										<input type="hidden" class="form-control" id="empresa" name="empresa" value="1"/>
									<?php 
									
										}	
										?>					
									
								</div>
								
							</div>
							<div class="row">
								
								<div class="col-sm-12">
									<label>Frete</label>
									<input type="text" class="form-control" id="frete" name="frete" placeholder="0,00" onKeyPress="return(moeda(this,'.',',',event));" onkeyup="soma();" />
								</div>
								
							</div>
							<div class="row">
								
								<div class="col-sm-12">
									<label>Desconto</label>
									<input type="text" class="form-control" id="descontogeral" name="descontogeral" placeholder="0,00" onKeyPress="return(moeda(this,'.',',',event));" onkeyup="soma();"  />
								</div>
								
							</div>
						
							<div class="row">
								
								<div class="col-sm-12">
									<label>Entrada</label>
									<input type="text" class="form-control" id="entradageral" name="entradageral"   disabled value="<?=number_format($entrada,2,',','.');?>"  />
								</div>
								
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
			
			$numero_pedido = $_numeropedido;

					
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

	if($acao == "alteraConsumidor"){
		$nomecli = $_POST['nomecli'];	
		$codigocli = $_POST['codigocli'];	
				$up = "update ".$_SESSION['BASE'].".saidaestoque  set CLIENTE = '$nomecli', CODIGO_CLIENTE = '$codigocli' where NUMERO = '$numero_pedido' ";
			
				$exe = $pdo->prepare($up);	
				$exe->execute();	
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
		$numero_pedido = $_numeropedido;
		
		$valor_pagoS = $_POST['valorPago'];
		$frete = $_POST['frete'];
		$_valordesconto = $_POST['descontogeral'];
		$_valorentrada = $_POST['entrada'];
		//	$_valorentrada = str_replace(".", "", $_valorentrada);
		//	$_valorentrada = str_replace(",", ".", $_valorentrada);
		$_valorpgto = $valor_pagoS ;
		$spgto_valorInfo = $_valorpgto;
		$forma_pago = $_POST['selpag'];
	
		$parcela_pago = $_POST['parpag'];

		//condições de pagamentos
		$pagamentos = $_POST['pagamentos'];
		$pagamentos = substr($pagamentos, 0, -1);
		$cond_pagamento = explode("|",$pagamentos);
	
		
		$valorTotal = $_POST['valorApagar']+$_valorentrada ;
		
		$troco = $_POST['troco'];
		$data = date('Y-m-d');
		$livro = $_SESSION['id_caixa'];
		$_idcaixa = $_SESSION['id_caixa'];
		if($_idcaixa == "") {
			$_idcaixa = 1;
		}
		$produto = '0';
	
		$tipo = $_POST['tipo'];
		
		
		if($troco > 0){
			$_valorpgto = $valor_pagoS - $troco;
		}else{
			$_valorpgto = $valor_pagoS;
		}

		
		try {
			
			//entrada

			if($_valorentrada > 0) { 
				$sql = "select Tipo_Pagamento_Entrada,Valor_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$numero_pedido."'";
				
				$stm = $pdo->prepare($sql);
				$stm->execute();
				while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 
					$formaPagamento = $rst->Tipo_Pagamento_Entrada;
				}
						$Linha = 1;
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
								spgto_total_parcela,
								spgto_entrada
							) VALUES(
								?,
								?,
								?,
								CURRENT_DATE(),
								CURRENT_DATE(),
								?,
								?,								
								?,
								1,
								'1'
							)";								
							$statement = $pdo->prepare($_sql); 					
							$statement->bindParam(1, $numero_pedido);
							$statement->bindParam(2, $livro);
							$statement->bindParam(3, $formaPagamento);							        
							$statement->bindParam(4, $_valorentrada);    
							$statement->bindParam(5, $Linha);
							$statement->bindParam(6, $_valorentrada);							  
														
							$statement->execute();
				
			}
			

			//buscar total pago
			$_SQL = "SELECT sum(spgto_valor) as total FROM ".$_SESSION['BASE'].".saidaestoquepgto
			WHERE spgto_numpedido = '" . $numero_pedido . "'  ";
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
			
			if(($_totalpgto+$_valorpgto) > ( $_valorpedido+$frete- $_valordesconto)) {					
				$_valorpgto = ($_valorpedido- $_valordesconto-$_totalpgto);
				
			}

		//	if($frete > 0) { 
				//buscar total pago
				$_SQL = "UPDATE  ".$_SESSION['BASE'].".saidaestoque SET Valor_Frete = '$frete',Valor_Troco = '$troco',VL_DESCONTO = '$_valordesconto',
				SAIDA_EMPRESA = '$empresa'
				WHERE NUMERO = '" . $numero_pedido . "' AND num_livro = '" . $livro . "' ";
				$stm = $pdo->prepare($_SQL);	
				$stm->execute();
		//	}

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
				$valor_porParcela = $valorPagamento/$parcelaPagamento;

		//	$valor_porParcela = $_valorpgto/$parcelaPagamento;
		

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
							
							
						if($valorPagamento > 0)		 {

							$valor_porParcelaC = $valor_porParcela;
							if($troco > 0 and $formaPagamento = 4){
								$valor_porParcelaC = $valor_porParcela - $troco;
							};

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
							$statement->bindParam(7, $valor_porParcelaC);
							$statement->bindParam(8, $troco);  
							$statement->bindParam(9, $parcelaPagamento);   									
							$statement->execute();
						}
							
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


	if($acao == "EmitirNFce"){
		try {
			date_default_timezone_set('America/Sao_Paulo');

			$stm = $pdo->query("SELECT SAIDA_NFE,VL_Pedido,SAIDA_EMPRESA FROM ".$_SESSION['BASE'].".saidaestoque WHERE NUMERO = '$numero_pedido' ");
			$pedido = $stm->fetch(PDO::FETCH_OBJ);				
			$SAIDA_NFE =  $pedido->SAIDA_NFE;
			$VLPEDIDO =  $pedido->VL_Pedido;
			$idemp    = $pedido->SAIDA_EMPRESA;

			if($idemp == 0) {
				$idemp = 1;
			}
		
			// Instância NFeService
			$nfe = new NFeService($idemp, 65);
			$numero_pedido= $_idpedido;
				if($_idcaixa == "") {
					$_idcaixa = 1;
				}
			$livro = $_idcaixa;
			
			$consulta = $pdo->query("SELECT proximo_numero_nfce_producao,serie_nfce_producao FROM ".$_SESSION['BASE'].".empresa as e	WHERE e.empresa_id = '$empresa'");
			$ret = $consulta->fetch(PDO::FETCH_OBJ);				
			$serie =  $ret->serie_nfce_producao;
			
						
			//Gera e assina XML
			
			if($SAIDA_NFE == '0' or $SAIDA_NFE == "") {			

				//caso ainda não tenha numero nf
				$consulta = $pdo->query("SELECT proximo_numero_nfce_producao,serie_nfce_producao FROM ".$_SESSION['BASE'].".empresa as e	WHERE e.empresa_id = '$empresa'");
				$ret = $consulta->fetch(PDO::FETCH_OBJ);				
				$serie =  $ret->serie_nfce_producao;
				$numeroNFCe = $ret->proximo_numero_nfce_producao;

				$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".saidaestoque 	SET SAIDA_NFE = '".$numeroNFCe."'  WHERE NUMERO= '$numero_pedido' ");
				$update->execute();
					
				$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET proximo_numero_nfce_producao = proximo_numero_nfce_producao + 1 WHERE empresa_id = ?");
				$update->bindParam(1, $empresa);
				$update->execute();
		}else{
			$numeroNFCe = $SAIDA_NFE;
		}

		$xml = $nfe->gerarNFCe($_idpedido, $_idcaixa);
	
		$signedXML = $nfe->assinaNFe($xml);
	
				//Grava XML no banco e incrementa número de NF
				$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
				$xml = $consulta->fetch(PDO::FETCH_OBJ);

				if (!$xml) {
					$dataNFC = date('Y-m-d H:m:s');		
					$dataNFCe = date('Y-m-d');					
					$insert = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_pedido, nfed_numlivro, nfed_xml, nfed_hora,nfed_numeronf,nfed_empresa,nfed_serie,nfed_data,nfed_totalnota) VALUES(?, ?, ?, ?,?,?,?,?,?)");
					$insert->bindParam(1, $numero_pedido);
					$insert->bindParam(2, $livro);
					$insert->bindParam(3, $signedXML);
					$insert->bindParam(4, $dataNFC);
					$insert->bindParam(5, $numeroNFCe);
					$insert->bindParam(6, $empresa);
					$insert->bindParam(7, $serie);
					$insert->bindParam(8, $dataNFCe);		
					$insert->bindParam(9, $VLPEDIDO);				
					$insert->execute();		
				
					
				} else {
					$dataNFC = date('Y-m-d H:m:s');

					$update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?, nfed_hora = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
					$update->bindParam(1, $signedXML);
					$update->bindParam(2, $dataNFC);
					$update->bindParam(3, $idemp);
					$update->bindParam(4, $livro);
					$update->execute();
				}

			

				$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
				$xml = $consulta->fetch(PDO::FETCH_OBJ);


				//Transmite XML 65
		
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
					?>
					<button type="button" class="btn btn-primary "  style="text-align: center;" onclick="_ImprimirVendaNF()" >Imprimir NFCe</button>
					<?php
					
				}
			

			} catch (\Exception $e) {
				//echo $e;
				
				?>
				<div class="row">
									<div class="col-sm-12 default" align="center">			
										<p><strong> Ops Ocorreu Erro</strong>, Envio Receita !!!<?php echo $x;?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 default" align="center">			
										<?php 
										
										echo $e->getmessage();
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" align="center">			
									<button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
									</div>
								</div>
								
						<?php
				exit();
			}

		//fim geracao nfce apos pedido

	}

	if($acao == "fimPagamento"){

		$tipo = $_POST['tipo'];
		$_idpedido = $_numeropedido;
		$livro = $_SESSION['id_caixa'];
		$_idcaixa = $_SESSION['id_caixa'];
			if($_idcaixa == "") {
				$_idcaixa = 1;
			}
		//$idemp = '1';//$_SESSION['BASE_ID']';
		$idemp = $empresa;
		$atendente = $_POST['atendente'];


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
				
				$consulta = $pdo->query("SELECT proximo_numero_nfce_producao,serie_nfce_producao FROM ".$_SESSION['BASE'].".empresa as e	WHERE e.empresa_id = '$empresa'");
				$ret = $consulta->fetch(PDO::FETCH_OBJ);				
				$serie =  $ret->serie_nfce_producao;
				
							
				//Gera e assina XML
				$stm = $pdo->query("SELECT SAIDA_NFE,VL_Pedido FROM ".$_SESSION['BASE'].".saidaestoque WHERE NUMERO = '$numero_pedido' AND num_livro = '$livro'");
				$pedido = $stm->fetch(PDO::FETCH_OBJ);				
				$SAIDA_NFE =  $pedido->SAIDA_NFE;
				$VLPEDIDO =  $pedido->VL_Pedido;
				if($SAIDA_NFE == '0' or $SAIDA_NFE == "") {			

					//caso ainda não tenha numero nf
					$consulta = $pdo->query("SELECT proximo_numero_nfce_producao,serie_nfce_producao FROM ".$_SESSION['BASE'].".empresa as e	WHERE e.empresa_id = '$empresa'");
					$ret = $consulta->fetch(PDO::FETCH_OBJ);				
					$serie =  $ret->serie_nfce_producao;
					$numeroNFCe = $ret->proximo_numero_nfce_producao;

					$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".saidaestoque 	SET SAIDA_NFE = '".$numeroNFCe."'  WHERE NUMERO= '$numero_pedido' AND num_livro = '$livro'");
					$update->execute();
						
					$update = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".empresa SET proximo_numero_nfce_producao = proximo_numero_nfce_producao + 1 WHERE empresa_id = ?");
					$update->bindParam(1, $empresa);
					$update->execute();
			}else{
				$numeroNFCe = $SAIDA_NFE;
			}
			
			$xml = $nfe->gerarNFCe($_idpedido, $_idcaixa);
		
			$signedXML = $nfe->assinaNFe($xml);
	
				//Grava XML no banco e incrementa número de NF
				$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
				$xml = $consulta->fetch(PDO::FETCH_OBJ);

				if (!$xml) {
					$dataNFC = date('Y-m-d H:m:s');		
					$dataNFCe = date('Y-m-d');					
					$insert = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_pedido, nfed_numlivro, nfed_xml, nfed_hora,nfed_numeronf,nfed_empresa,nfed_serie,nfed_data,nfed_totalnota) VALUES(?, ?, ?, ?,?,?,?,?,?)");
					$insert->bindParam(1, $numero_pedido);
					$insert->bindParam(2, $livro);
					$insert->bindParam(3, $signedXML);
					$insert->bindParam(4, $dataNFC);
					$insert->bindParam(5, $numeroNFCe);
					$insert->bindParam(6, $empresa);
					$insert->bindParam(7, $serie);
					$insert->bindParam(8, $dataNFCe);		
					$insert->bindParam(9, $VLPEDIDO);				
					$insert->execute();		
				
					
				} else {
					$dataNFC = date('Y-m-d H:m:s');

					$update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".NFE_DADOS SET nfed_xml = ?, nfed_hora = ? WHERE nfed_pedido = ? AND nfed_numlivro = ?");
					$update->bindParam(1, $signedXML);
					$update->bindParam(2, $dataNFC);
					$update->bindParam(3, $idemp);
					$update->bindParam(4, $livro);
					$update->execute();
				}

			

				$consulta = $pdo->query("SELECT nfed_xml FROM ".$_SESSION['BASE'].".NFE_DADOS WHERE nfed_pedido = '$numero_pedido' AND nfed_numlivro = '$livro'");
				$xml = $consulta->fetch(PDO::FETCH_OBJ);

				//Transmite XML 65

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
				
				?>
				<div class="row">
									<div class="col-sm-12" align="center">			
										<p><strong> Ops Ocorreu Erro</strong>, Envio Receita !!!<?php echo $x;?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" align="center">			
										<?php 
										
										echo $e->getmessage();
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" align="center">			
									<button type="button"  onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">Fechar</span></button>
									</div>
								</div>
								
						<?php
				exit();
			}
		}

		//exit();
	
		//atualiza saida estoque
		$data_pg = date('Y-m-d');
		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '3', se_status = '1', Data_Financeiro = '$data_pg'	WHERE NUMERO = '" . $_idpedido . "' ";               
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

		
		$i = 0;
		$_idcolaborador = "";
		$_Nomecolaborador = "";
		$total_servico = "";
		$vencimento = "";
		$data_atual = "";

		//==============================================================================================================================================================
		//comissao atendimento		

		
			$consultaLinha = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".saidaestoquepgto where spgto_parcela > 0 and spgto_numpedido = '".$_idpedido."'");	
			$retornoLinha = $consultaLinha->fetchAll();
			foreach ($retornoLinha as $row_a) {
	
				//informações da forma de pagamento
				$_TIPOPGTO = $row_a["spgto_tipopgto"];		
				//$vencimento = $row_a["spgto_venc"];
				$total_parcela = $row_a['spgto_total_parcela'];
				//$parcela_x = $parcela_x + $row_a['spgto_parcela'];
				$valor_parcela_x = $valor_parcela_x + $row_a['spgto_valorInfo'];
			}
		
$_regparcela = 0;
		//==============================================================================================================================================================
		//loop da forma de pagamentos===================================================================================================================================
		$i = 1;
		$consultaLinha = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".saidaestoquepgto where  spgto_parcela > 0 and spgto_numpedido = '".$_idpedido."'");	
		$retornoLinha = $consultaLinha->fetchAll();
		foreach ($retornoLinha as $row_a) {

			//informações da forma de pagamento
			$_TIPOPGTO = $row_a["spgto_tipopgto"];		
			$vencimento = $row_a["spgto_venc"];
			$total_parcela = $row_a['spgto_total_parcela'];
			$parcela = $row_a['spgto_parcela'];
			$valor_parcela = $row_a['spgto_valorInfo'];
            $_tipoentrada = $row_a['spgto_entrada'];


			//VERIFICAR ENTRADA NO FINANCEIRO
		if(  $_tipoentrada == 1) {

	
			$sql = "select CODIGO_CLIENTE,Valor_Entrada,Tipo_Pagamento_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_idpedido."' and Ind_Financeiro = 0 limit 1";
				
			$stm = $pdo->prepare($sql);
			$stm->execute();
			while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 					
				$entradaPedido = $rst->Valor_Entrada;
				$_TIPOPGTOEntrada=  $rst->Tipo_Pagamento_Entrada;
				$_idcliente = $rst->CODIGO_CLIENTE;					
			}
		}

	
	if($entradaPedido > 0 and $_xentrada == "") {

	
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
						financeiro_valorDesconto,
						financeiro_totalduplicata,
						financeiro_identificador,
						Documento,
						financeiro_referente,
						financeiro_usucom
					) VALUES (
						'1',
						'1',
						'$_idcliente',
						'".$_SESSION['NOME']."',
						'$_idpedido',
						'REF ENTRADA PED. $_idpedido ',
						CURRENT_DATE(),
						CURRENT_DATE(),
						CURRENT_DATE(),
						'$entradaPedido',
						'0',
						'1',
						'0',
						'1',
						'2',
						'1',
						'$_TIPOPGTOEntrada',
						'$datahora',
						'0',
						'1',
						'$entradaPedido',
						CURRENT_DATE(),
						'Entrada Pedido',
						'0',
						'$entradaPedido',
						'2',
						'$_idpedido',
						'1',
						'".$usuario."'
					)";          
					$stm = $pdo->prepare($_SQL);	
					$stm->execute();
					$_xentrada = "(Entrada Registrada)";
				//	$Ind_Financeiro = ",Ind_Financeiro = 1";
						$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Ind_Financeiro = 1
					WHERE NUMERO = '" . $_idpedido . "' ";               
					$stm = $pdo->prepare("$_SQL");	
					$stm->execute();	

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
						Livro_Num_Docto,
						Livro_caixa_historico,
						Livro_caixa_motivo
					) VALUES (
						'$_idcaixa',
						'$entradaPedido',
						'$datahora',
						'$datahora',
						'".$_SESSION['IDUSER']."',
						'".$_SESSION['NOME']."',
						'$_TIPOPGTO',
						'".$_SESSION['hash_caixa']."',
						'$_idpedido',
						'VENDA ENTRADA $_idpedido',
						'5'
					)";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
				}
			
			

			//busca dados da condição de pagamento
			$sql="SELECT * FROM ". $_SESSION['BASE'] .".tiporecebimpgto where id = '$_TIPOPGTO'";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			while($rst = $stm->fetch(PDO::FETCH_OBJ)){
				$QT_Parcelas = $rst->QT_Parcelas;
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
			
if($QT_Parcelas> 1){


					$sql="SELECT *,SUM(spgto_valorInfo) as total FROM ". $_SESSION['BASE'] .".saidaestoquepgto 
					LEFT JOIN ".$_SESSION['BASE'] .".tiporecebimpgto  on spgto_tipopgto = id 
					where spgto_parcela > 0 and spgto_numpedido = '".$_idpedido."' and spgto_entrada <> '1'  GROUP BY spgto_tipopgto";
					$stm = $pdo->prepare($sql);	
				
					$stm->execute();
					while($rst = $stm->fetch(PDO::FETCH_OBJ)){

						$total_pagto = $rst->total;	//busca total por tipo de pagamento
				
						//$data_atual = "";

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
						$_regparcela++; 
						
						$obs = "parcelado: p.$_regparcela";
						
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
							financeiro_valorDesconto,
							financeiro_totalduplicata,
							Documento,financeiro_referente,
							financeiro_usucom
						) VALUES (
							'$_regparcela',
							'$QT_Parcelas',
							'$_idcliente',
							'".$_SESSION['NOME']."',
							'$_idpedido',
							'REF $_idpedido ',
							CURRENT_DATE(),
							'$vencimento',
							'$vencimento',
							'$valor_parcela',
							'0',
							'1',
							'0',
							'1',
							'2',
							'$_idcaixa',
							'$_TIPOPGTO',
							'$datahora',
							'$NSU',
							'1',
							'$valor_pagoF',
							'$data_pagoF',
							'$obs',
							'$desconto_total',
							'$total_pagto',
							'$_idpedido','1',
							'".$usuario."'
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
								Livro_Num_Docto,
								Livro_caixa_historico,
								Livro_caixa_motivo
							) VALUES (
								'$_idcaixa',
								'$valor_pagoF',
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
				}else{

					//parcela unica

					$sql="SELECT *,SUM(spgto_valorInfo) as total FROM ". $_SESSION['BASE'] .".saidaestoquepgto 
					LEFT JOIN ".$_SESSION['BASE'] .".tiporecebimpgto  on spgto_tipopgto = id 
					where spgto_parcela > 0 and spgto_numpedido = '".$_idpedido."' and spgto_entrada <> '1'  GROUP BY spgto_tipopgto";
					$stm = $pdo->prepare($sql);	
				
					$stm->execute();
					while($rst = $stm->fetch(PDO::FETCH_OBJ)){

						$total_pagto = $rst->total;	//busca total por tipo de pagamento

					}
						//verifica se liquida venda
						if($liquida_condPag == 'S'){
							$valor_pagoF = $valor_parcela;
							$data_pagoF = date('Y-m-d');
						}else{
							$valor_pagoF = "";
							$data_pagoF  = "";
						}
						
					if($_tipoentrada == 0) {

					
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
							financeiro_valorDesconto,
							financeiro_totalduplicata,
							Documento,
							financeiro_referente,
							financeiro_usucom
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
							'$valor_parcela',
							'0',
							'1',
							'0',
							'1',
							'2',
							'$_idcaixa',
							'$_TIPOPGTO',
							'$datahora',
							'$NSU',
							'1',
							'$valor_pagoF',
							'$data_pagoF',
							'$obs',
							'$desconto_total',
							'$total_pagto',
							'$_idpedido','1',
							'".$usuario."'
						)";          
						$stm = $pdo->prepare($_SQL);	
						$stm->execute();
						$contabiliza_caixa = 1;
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
								Livro_Num_Docto,
								Livro_caixa_historico,
								Livro_caixa_motivo
							) VALUES (
								'$_idcaixa',
								'$valor_pagoF',
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
					}
						

					
				}
		//loop da forma de pagamentos===================================================================================================================================
				} 
		//		$i ++;
		//		$obs = "";			
		//	}
	
		


		
		//FIM loop da forma de pagamentos===============================================================================================================

       // $_almox = 1;
		$_SELBX =  $_POST['bx']; 
		
		if($_SELBX == 1){
            	//atualiza estoque
                $consultaLinha = $pdo->query("Select CODIGO_ITEM,QUANTIDADE,VALOR_UNIT_DESC,VALOR_TOTAL,Cod_Almox,Valor_Custo,vlr_vendaorigem
				 from ". $_SESSION['BASE'] .".saidaestoqueitem 
                                              where  NUMERO = '".$_idpedido."' AND num_livro = '".$_idcaixa."' ");
								
                $retornoLinha = $consultaLinha->fetchAll();
                foreach ($retornoLinha as $row_a) {        
                    
                    $iditem = $row_a["CODIGO_ITEM"];
					//$_almox= $row_a["Cod_Almox"];
					if($almox == 0){  $almox = 1;}
                    $qtde = $row_a["QUANTIDADE"];  
                    $valor = $row_a["VALOR_UNIT_DESC"]; 
                    $total = $row_a["VALOR_TOTAL"];
					$_totalprodutos = $_totalprodutos +  $row_a["VALOR_TOTAL"];
					$vlrcusto = $row_a["Valor_Custo"];
					$vlrvenda =  $row_a["vlr_vendaorigem"];
               

                        $consultaEST = $pdo->query("Select Qtde_Disponivel from ". $_SESSION['BASE'] .".itemestoquealmox 
                                                    where  Codigo_Item = '".$iditem."' AND Codigo_Almox = '".$almox."'");
                        $retornoEST = $consultaEST->fetchAll();
                            foreach ($retornoEST as $rowEST) {     

                                $qtde_atual = $rowEST["Qtde_Disponivel"] - $qtde ;	
								$codigoFornecedor = $rowEST["codref_fabricante"] ;                            
                            } 

                          $_SQL = "Update ". $_SESSION['BASE'] .".itemestoquealmox  set Qtde_Disponivel = '$qtde_atual' 
                               where Codigo_Item  = '$iditem' and Codigo_Almox = '$almox' ";
                            $stm = $pdo->prepare($_SQL);	
                            $stm->execute();
							
							$_SQL = "Update ". $_SESSION['BASE'] .".itemestoque set DATA_ULT_SAIDA = '$datahora' ,VALOR_ULT_SAIDA = '".$row_a["VALOR_UNIT_DESC"]."'
							where codigo_fornecedor  = '$iditem'  limit 1 ";
							$stm = $pdo->prepare($_SQL);	
							$stm->execute();	

							

                        $_SQL = " INSERT INTO ". $_SESSION['BASE'] .".itemestoquemovto
                                (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento,
                                Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,
                                Motivo,Saldo_Atual,Data_Movimento ) 
                                values 
                                ( '$iditem',
                                    '$qtde',
                                    '$almox',
                                    's',
                                    'v',
                                    '$_idpedido','$valor','0','$total','$usuario','Saida por Venda','$qtde_atual','$datahora') ";
                        $stm = $pdo->prepare($_SQL);	
                        $stm->execute();	

						$consultaPar = $pdo->query("SELECT Ind_Gera_Treinamento FROM ".$_SESSION['BASE'].".parametro");
						$retPar = $consultaPar->fetch(PDO::FETCH_OBJ);				
						$Ind_Gera_Treinamento =  $retPar->Ind_Gera_Treinamento;
						
						if($Ind_Gera_Treinamento == 1) {									
									$retapp = APIecommerce::bling_saldoEstoque($iditem,$vlrcusto,$valor,$qtde, "S","Venda $_idpedido");	
						}

                
                    }
				}
					
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
		
	} //fim pgto


	if($acao == "NovaVenda"){
		$_numeropedido = "";
		$_SESSION['i'] = 0;
	}
	if($acao == "delItemCompra"){
		
		$id = $_POST['id'];

		//busca valor do item
		$sql="SELECT * FROM ".$_SESSION['BASE'].".saidaestoqueitem where ITEM = '$id' and NUMERO = '".$_numeropedido."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			$valorItem = $result->VALOR_TOTAL;
			$valorDesconto = $result->DESCONTO;
		}
		$valorTotal_item = $valorItem;

		//busca valor da venda
		$sql="SELECT * FROM ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		while($result = $stm->fetch(PDO::FETCH_OBJ)){
			$valorVenda = $result->VL_Pedido;
			$valorVenda_desconto = $result->VL_DESCONTO;
		}

		$novoValor = $valorVenda-$valorTotal_item;
		$novoValor_Desconto = $valorVenda_desconto-$valorDesconto;

		//ATUALIZA VALOR DA VENDA
		$sql="UPDATE ".$_SESSION['BASE'].".saidaestoque set VL_Pedido = '$novoValor', VL_DESCONTO = '$novoValor_Desconto' where NUMERO = '".$_Numeropedido."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		
		$sql="delete from ".$_SESSION['BASE'].".saidaestoqueitem where ITEM = '$id' and NUMERO = '".$_numeropedido."'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
	}
	if($acao == "cancelaCompra"){
		
		//$numero_pedido = $_Numeropedido;

		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET DATA_CANCELAMENTO = '$datahora',USER_CANCELAMENTO = '$usuario'
		WHERE NUMERO = '" . $_idpedido . "' and Cod_Situacao = '3' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	

		$sql="update ".$_SESSION['BASE'].".saidaestoque set  DATA_CANCELAMENTO = '$datahora',USER_CANCELAMENTO = '$usuario',Cod_SituacaoAnterior = Cod_Situacao, Cod_Situacao = '9' where NUMERO = '$_numeropedido'";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
					
		
		$_SESSION['i'] = 0;

		$consultaLinha = $pdo->query("Select SAIDA_NFE,Valor_Entrada,DATE_FORMAT(DATA_CADASTRO,'%Y-%m-%d') AS DT
		from ". $_SESSION['BASE'] .".saidaestoque 
									 where  NUMERO = '".$_numeropedido."'  ");
					   
	   $retornoLinha = $consultaLinha->fetchAll();
	   foreach ($retornoLinha as $row_a) { 
		$_numeroNF = $row_a['SAIDA_NFE'];
		$entrada = $row_a['Valor_Entrada'];
		$DATA_CADASTRO = $row_a['dt'];
	   }

		
		if($entrada > 0   ) { 
			$_SQL = "Update ". $_SESSION['BASE'] .".financeiro  set financeiro_situacaoID = '1' 
			where Documento  = '$_idpedido' and financeiro_identificador = '2' and financeiro_emissao = CURRENT_DATE() ";
				$stm = $pdo->prepare($_SQL);	
				$stm->execute();	
		   }

		   $_SQL = "Update ". $_SESSION['BASE'] .".financeiro  set financeiro_situacaoID = '1' 
		   where financeiro_referente = '1' and Documento  = '$_idpedido' and financeiro_identificador = '0' and financeiro_emissao = CURRENT_DATE()";
		   $stm = $pdo->prepare($_SQL);	
		   $stm->execute();	

		   $_SQL = "DELETE FROM ". $_SESSION['BASE'] .".livro_caixa  
		   where  Livro_Num_Docto  = '$_idpedido' AND Livro_caixa_data_lancamento >= '$dataATUAL 00:00:00'";
		   $stm = $pdo->prepare($_SQL);	
		   $stm->execute();	



		   $_numeropedido = "";
		  
	}

	
	if($acao == "descontoItematualiza"){
		//$_numero_pedido = $_Numeropedido;
	
		$id = $_POST['id'];
		?>
		<table class="table">
			<tr>
				<th class="lin">#</th>
				<th class="lin">CODIGO</th>
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


				
				$sql = "select ITEM,CODIGO_FABRICANTE,DESCRICAO_ITEM,QUANTIDADE,VALOR_UNIT_DESC,VALOR_TOTAL,Valor_unitario_desc
									 FROM ".$_SESSION['BASE'].".saidaestoqueitem 
									 LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = CODIGO_ITEM
									 where NUMERO = '".$_numeropedido."'";
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
					$valor_pedido_atualizado = $valor_pedido_atualizado  + $linha->VALOR_TOTAL;
			?>
					<tr>
						<td><b><?=$linha->ITEM?></b></td>
						<td><b><?=$linha->CODIGO_FABRICANTE;?></b></td>
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


	//atualiza valor da venda
	$sql2="UPDATE  ".$_SESSION['BASE'].".saidaestoque set VL_Pedido = '$valor_pedido_atualizado' where NUMERO = '$_numeropedido'";
	$stm2 = $pdo->prepare($sql2);
	$stm2->execute();

	}

	if($acao == "descontoItem"){
		
	
		//$numero_pedido = $_Numeropedido;
		$novoValor = $_POST['novoValor'];		
		$id = $_POST['id'];
		
		$novoValor = str_replace(".","",$novoValor);
		$novoValor = str_replace(",",".",$novoValor);
		
		
		if($novoValor != "" ){ //and $novoValor > '0'
		
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
				/*
				if($novoValor > $valorOriginal) {
				
			
				
				*/
				/*** $valorOriginal = $novoValor; 

				}else{
					$ValorDescontado = $valorOriginal - $novoValor;
					$ValorDescontado = $ValorDescontado*$quantidade;
					
					if($ValorDescontado < '0'){
						$ValorDescontado = 0;
						$novoValor = $valorOriginal;
					}
				}
*/
				$ValorDescontado = 0;
				$valorOriginal = $novoValor;
		
			
				
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
		
		//$numero_pedido = $_Numeropedido;
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

	//$numero_pedido = $_Numeropedido;

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
					//$_Numeropedido = $numero_pedido;
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

	if($acao == "valorEntradaRet"){
		$_idpedido = $_numeropedido;
		$sql = "select Valor_Entrada,Cod_Situacao from ".$_SESSION['BASE'].".saidaestoque where NUMERO =  '$_numeropedido' and Valor_Entrada > 0  ";
		$stm = $pdo->prepare($sql);	
		$stm->execute();
		
		if($stm->rowCount() > 0) {

			while($result = $stm->fetch(PDO::FETCH_OBJ)){		
				$_valorentrada = number_format($result->Valor_Entrada,2,',','.');
				$Cod_Situacao = $result->Cod_Situacao;
			}
			if($Cod_Situacao >= '2'  ){
				?>
				<h4>Entrada Pedido:</h4><hr>
				<div class="row">
				<div class="col-sm-12">
				Valor da Entrada registrado de R$ <?=$_valorentrada;?>
				</div>			
			<?php
			
			}else { ?>

				<h4>Entrada Pedido:</h4><hr>
				<div class="row">
					<div class="col-sm-3">
						<label>VALOR ENTRADA</label>
						<input type="text" class="form-control" id="entradaValor" name="entradaValor" placeholder="0,00" value="<?=$_valorentrada;?>" >
					</div>				
							<div class="col-sm-4">
									<label>FORMA DE PAGAMENTO</label>
									<select type="text" class="form-control" name="selpagEntrada" id="selpagEntrada<?=$i;?>" >
										<?php
										
											$sql = "select * from ".$_SESSION['BASE'].".tiporecebimpgto where id <> '99'";
											$stm = $pdo->prepare($sql);	
											$stm->execute();
											while($result = $stm->fetch(PDO::FETCH_OBJ)){											
												?><option value="<?=$result->id;?>"><?=$result->nome;?></option><?php
											}
										
										?>
									</select>
								</div>
								<div class="col-sm-3" style="margin-top: 25px;"><button type="button" class="btn btn-primary btn-block" onclick="verificaValorEntrada()"><i class="fa fa-check"></i>&nbsp;</button></div></div>
				</div>
	
			<?php }
		

	
		}else {		
	
	?>
		<h4>Entrada Pedido:</h4><hr>
		<div class="row">
			<div class="col-sm-3">
				<label>VALOR ENTRADA</label>
				<input type="text" class="form-control" id="entradaValor" name="entradaValor" placeholder="0,00" >
			</div>				
					<div class="col-sm-4">
							<label>FORMA DE PAGAMENTO</label>
							<select type="text" class="form-control" name="selpagEntrada" id="selpagEntrada<?=$i;?>" >
								<?php
								
									$sql = "select * from ".$_SESSION['BASE'].".tiporecebimpgto where id <> '99'";
									$stm = $pdo->prepare($sql);	
									$stm->execute();
									while($result = $stm->fetch(PDO::FETCH_OBJ)){											
										?><option value="<?=$result->id;?>"><?=$result->nome;?></option><?php
									}
								
								?>
							</select>
						</div>
						<div class="col-sm-3" style="margin-top: 25px;"><button type="button" class="btn btn-primary btn-block" onclick="verificaValorEntrada()"><i class="fa fa-check"></i>&nbsp;</button></div></div>
		</div>
	<?php
	
}
	exit();
		}

	if($acao == "valorEntrada"){
		//entrada
		//$_idpedido = $_POST['ref'];
		$_idpedido = $_numeropedido;
		$idPgto = $_POST['idPgto'];
		$_idcliente = $_POST['idcliente'];
		$novoLancamento = $_POST['entradaValor'];
		$novoLancamento = str_replace(".","",$novoLancamento);
		$novoLancamento = str_replace(",",".",$novoLancamento);

		//
		$sql = "select Num_Livro from ".$_SESSION['BASE'].".tiporecebimpgto where id = '$idPgto '";
									$stm = $pdo->prepare($sql);	
									$stm->execute();
									while($result = $stm->fetch(PDO::FETCH_OBJ)){											
										$_idcaixa = $result->Num_Livro;?></option><?php
									}

		$update = "update ".$_SESSION['BASE'].".saidaestoque set Valor_Entrada = '$novoLancamento',dtentrada = CURRENT_DATE(), Tipo_Pagamento_Entrada = '$idPgto' where NUMERO = '$_idpedido'";
		$stm = $pdo->prepare($update);	
		$stm->execute();

					

							exit();

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
						$sql="select * from ".$_SESSION['BASE'].".tiposaida order by COD_TIPO_SAIDA DESC";
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
	
		?>
		<div class="row">
				<div class="col-md-2" >
					<select name="ordem"  id="ordem" class="form-control">
						<option value="Nome_Consumidor"  <?php if ($ordemd == 'Nome_Consumidor') { ?> selected="selected" <?php } ?>>Nome</option>
						<option value="CGC_CPF"  <?php if ($ordemd == 'CGC_CPF') { ?> selected="selected" <?php } ?>>CPF/CNPJ</option>
						<option value="Nome_Rua"  <?php if ($ordemd == 'Nome_Rua') { ?> selected="selected" <?php } ?>>Endere&ccedil;o</option>
						<option value="FONE_RESIDENCIAL"  <?php if ($ordemd == 'FONE_RESIDENCIAL') { ?> selected="selected" <?php } ?>>Telefone</option>						
						<option value="EMail"  <?php if ($ordemd == 'Email') { ?> selected="selected" <?php } ?>>Email</option>        
					
					</select>      
				</div>
				<div class="col-md-7">           
						<input type="text" id="_desccli" name="_desccli" class="form-control" value="<?=$descricao;?>";  onkeypress='mascaraMutuario(this,cpfCnpj)'" placeholder="Nome, CPF/CNPJ, Endereço, Telefone, Email">           
				</div>
				<div class="col-md-1">           
					    <button type="button"  class="btn btn-primary waves-effect waves-light" aria-expanded="false" onclick="localizarCliente()"><span class="btn-label btn-label"> </span><i class="fa fa-search"></i> &nbsp;</button>  						 
				</div>
				<div class="col-md-1">  
					<!-- <button type="button"  class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="_cons" onclick="_novocli()" data-toggle="modal" data-target="#custom-width-cli"><span class="btn-label btn-label"> </span>Incluir</button> --> 
				</div>
		</div>
		<div class="row" style="padding-top: 5px;">
			<div class="col-md-12" >
			<div class="card-box table-responsive" id="_resultclinew">       
					<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  
						<thead>
							<tr>
								<th>Ação </th>       
								<th>Nome</th>  
								<th>Endereço</th>          
								<th>Telefone</th>
								<th>CNPJ/CPF</th>   
							</tr>
						</thead>
						<tbody>
							
								
						

						</tbody>       
						</table>
			</div>
			</div>
		</div>
		<?php


	}
	if($acao == "localizarCliente"){
		$ordem = $_POST['ordem'];
		$descricao = $_POST['descricao'];
		
		?>
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  
						<thead>
							<tr>
								<th>Ação </th>       
								<th>Nome</th>  
								<th>Endereço</th>          
								<th>Telefone</th>
								<th>CNPJ/CPF</th>   
							</tr>
						</thead>
						<tbody>
							<?php
							     if( $ordem == "")  {
									$ordem = "Nome_Consumidor";
								 }

								 if ($ordem == 'Nome_Rua'  or $ordem == 'Nome_Consumidor') { 
										$sql = "Select * from ".$_SESSION['BASE'].".consumidor 
										where  $ordem like '%$descricao%' $ordem1 order by Nome_Consumidor limit 100  ";   
									}else{

										if ($ordem == 'CGC_CPF'){

											$cpfcnpj  = remove($descricao);
												$cpfcnpj = preg_replace('/[^0-9]/', '', (string) $cpfcnpj);
												
												if(strlen($cpfcnpj)==11) //cpf
												{
													

													$cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
													substr($cpfcnpj, 3, 3) . '.' .
													substr($cpfcnpj, 6, 3) . '-' .
													substr($cpfcnpj, 9, 2);
												} else {
													

													$cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
																			substr($cpfcnpj, 2, 3) . '.' .
																			substr($cpfcnpj, 5, 3) . '/' .
																			substr($cpfcnpj, 8, 4) . '-' .
																			substr($cpfcnpj, -2);

												} 
												$descricao = $cpfcnpj;

										}
										$sql = "Select CODIGO_CONSUMIDOR,Nome_Consumidor from ".$_SESSION['BASE'].".consumidor 
										where  $ordem = '$descricao' $ordem1 order by Nome_Consumidor ASC limit 100  ";   

									}   

								$stm = $pdo->prepare($sql);	
								$stm->execute();
								while($result = $stm->fetch(PDO::FETCH_OBJ)){
									
								
										$_idcliente =  $result->CODIGO_CONSUMIDOR;;
										$_desccliente =  $result->Nome_Consumidor;
								?>
									<tr>
										<td class="text-center" style="width:100px">                  
										<button class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="selCliente('<?=$_idcliente;?>','<?=$_desccliente;?>')"> <i class="fa  fa-check"></i> </button>                  
										
										</td>
										<td><?=$result->Nome_Consumidor;?></td>                       
										<td><?=$result->Nome_Rua." ".$result->Num_Rua;?> </td>
										<td> <?=$result->DDD;?>-<?=$result->FONE_RESIDENCIAL?> <?=$result->FONE_CELULAR;?></td>
										<td><?=$result->CGC_CPF?></td> 
									</tr>
								
								
								<?php
								
									} ?>
								
						

						</tbody>       
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
			$sql2="UPDATE ".$_SESSION['BASE'].".saidaestoque set CLIENTE = '$nome ', cpfcnpj = '$cpfcnpj' , CODIGO_CLIENTE = '$idCliente'  WHERE NUMERO = '".$_numeropedido."'";
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
							<th>..</th>
							<th>Cpf/Cnpj</th>
							<th>Situação</th>
						</tr>
							<?php 
								$sql="SELECT *,DATE_FORMAT(DATA_CADASTRO , '%d/%m/%Y') as data 
								FROM ".$_SESSION['BASE'].".saidaestoque								
								LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda ON situacaopedidovenda.Cod_Situacao = saidaestoque.Cod_Situacao
								WHERE DATA_CADASTRO between '$dataIni' and '$dataFim' and saidaestoque.Cod_Situacao <> '93' and saidaestoque.Cod_Situacao <> '99' and NUMERO <> '".$_numeropedido."' $fil_descricao";
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

	if($acao == "finalizaSalva"){ 

		

		if($OBSERVACAO != ""){
			$sql = "update ".$_SESSION['BASE'].".saidaestoque set se_status = '$_statusVenda', OBSERVACAO = ? where NUMERO = '".$_numeropedido."'";
			$stm = $pdo->prepare($sql);	
			$stm->bindParam(1,$OBSERVACAO, \PDO::PARAM_STR);
			$stm->execute();
		}?>
					<div class="card-box">  
                                            <div class="row">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-8">
                                                     <label for="field-1" class="control-label">Selecione opção:</label>                                                     
													 <button type="button" class="btn btn-block btn-md btn-warning waves-effect waves-light" style="margin:10px"  onclick="_btImprimirCompleto();"><i class="fa  fa-print"></i> Gerar e Imprimir Pedido Compl.</button>
                                                     <button type="button" class="btn btn-block btn-md waves-effect waves-light btn-warning" style="margin:10px" <?php if($_situacaoPedido == 3) { echo 'disabled'; } ?> onclick="_btOrcamento('<?=$_idpedido;?>')"><i class="fa   fa-shopping-cart"></i> Gerar e Imprimir Orçamento</button>
													 <button type="button" class="btn btn-block btn-md waves-effect waves-light btn-info" style="margin:10px" <?php if($_situacaoPedido == 3) { echo 'disabled'; } ?> onclick="_btPedido('<?=$_idpedido;?>')"><i class="fa   fa-shopping-cart"></i> Gerar e Imprimir Pedido</button>
													  <?php if($_situacaoPedido == 3) {
																$sql="select SAIDA_NFE from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_numeropedido."'";															
																$stm = $pdo->prepare($sql);
																$stm->execute();
																while($linha = $stm->fetch(PDO::FETCH_OBJ)){																
																	$SAIDA_NFE = $linha->SAIDA_NFE;
																}
															if($SAIDA_NFE == 0){ 
															}else{	
															?>

																	<button type="button" class="btn btn-block btn--md btn-inverse waves-effect waves-light" style="margin:10px" onclick="gerarNFCE();"><i class="fa   fa-check-square-o"></i> Re-Enviar NFC-e </button> 
															
																<?php }  ?>
														<button type="button" class="btn btn-block btn-md waves-effect waves-light btn-danger" style="margin:10px" onclick="_btCancelar()" ><i class="fa fa-close"></i> Cancelar Venda</button>
														<button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" style="margin:10px;background-color:#BABABA" onclick="_btFechar();"><i class="fa   fa-check-square-o"></i> Fechar </button> 
														<?php  } else {  ?>
															<p>
																<button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" style="margin:10px;background-color:#BABABA" onclick="_btSalvar();"><i class="fa   fa-check-square-o"></i> Salvar Fechar </button> 
																</p>
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

	if($acao == "confGerarNFCE"){ 		

	?>
					<div class="card-box">  
                                           
											<div class="row" id="retacao">
											<div class="col-md-12 default" style="text-align: center;">
													<b>Deseja Realmente emitir a NFC-e ?</b>
                                                </div>
												<div class="col-md-12" style="padding-bottom: 40px;text-align: center">   
														<button type="button" class="btn btn-success " onclick="_EmitirNFce()"> Emitir NFC-e</button>                                                 
                                                </div>    
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

	if($acao == "btSalvararametros"){ 	
		
		//atualiza vendedor
		if($_POST['vend'] > 0) {

	
		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET COD_Vendedor = '".$_POST['vend']."' 
		WHERE NUMERO = '" . $_idpedido . "' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	
	
		?>		
			<i class="fa fa-floppy-o fa-2x" style="cursor: pointer;" onclick="_salvarparametros();" ></i>
				 - Atualizado com sucesso !!! 				
							
			     	
	<?php
		}
	}
	

	if($acao == "btCancelar"){ 	
		$dia       = date('d');
		$mes       = date('m');
		$ano       = date('Y');
		$hora = date("H:i:s");

		$datahora      = $ano . "-" . $mes . "-" . $dia ;
		//atualiza saida estoque
		$consultaLinha = $pdo->query("Select SAIDA_NFE,Valor_Entrada,DATE_FORMAT(DATA_CADASTRO,'%Y-%m-%d') AS DT
		from ". $_SESSION['BASE'] .".saidaestoque 
									 where  NUMERO = '".$_idpedido."'  ");
					   
	   $retornoLinha = $consultaLinha->fetchAll();
	   foreach ($retornoLinha as $row_a) { 
		$_numeroNF = $row_a['SAIDA_NFE'];
		$entrada = $row_a['Valor_Entrada'];
		$DATA_CADASTRO = $row_a['dt'];
	   }
	   
	   $_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET DATA_CANCELAMENTO = '$datahora',USER_CANCELAMENTO = '$usuario',Cod_SituacaoAnterior = Cod_Situacao, Cod_Situacao = '9'
	   WHERE NUMERO = '" . $_idpedido . "' ";               
	   $stm = $pdo->prepare("$_SQL");	
	   $stm->execute();	

		
		//atualiza estoque
		$consultaLinha = $pdo->query("Select CODIGO_ITEM,QUANTIDADE,VALOR_UNIT_DESC,VALOR_TOTAL,Cod_Almox
		from ". $_SESSION['BASE'] .".saidaestoqueitem 
									 where  NUMERO = '".$_idpedido."'  ");
					   
	   $retornoLinha = $consultaLinha->fetchAll();
	   foreach ($retornoLinha as $row_a) {        
		   
		   $iditem = $row_a["CODIGO_ITEM"];
		 //  $_almox= $row_a["Cod_Almox"];
		   if($almox == 0){  $almox = 1;}
		   $qtde = $row_a["QUANTIDADE"];  
		   $valor = $row_a["VALOR_UNIT_DESC"]; 
		   $total = $row_a["VALOR_TOTAL"];
		   $_totalprodutos = $_totalprodutos +  $row_a["VALOR_TOTAL"];

	  

			   $consultaEST = $pdo->query("Select Qtde_Disponivel from ". $_SESSION['BASE'] .".itemestoquealmox 
										   where  Codigo_Item = '".$iditem."' AND Codigo_Almox = '".$almox."'");
			   $retornoEST = $consultaEST->fetchAll();
				   foreach ($retornoEST as $rowEST) {     

					   $qtde_atual = $rowEST["Qtde_Disponivel"] + $qtde ;	
				   
				   } 

				 $_SQL = "Update ". $_SESSION['BASE'] .".itemestoquealmox  set Qtde_Disponivel = '$qtde_atual' 
					  where Codigo_Item  = '$iditem' and Codigo_Almox = '$almox' ";
				   $stm = $pdo->prepare($_SQL);	
				   $stm->execute();	

				   $consultaPar = $pdo->query("SELECT Ind_Gera_Treinamento FROM ".$_SESSION['BASE'].".parametro");
						$retPar = $consultaPar->fetch(PDO::FETCH_OBJ);				
						$Ind_Gera_Treinamento =  $retPar->Ind_Gera_Treinamento;
						
						if($Ind_Gera_Treinamento == 1) {									
									$retapp = APIecommerce::bling_saldoEstoque($iditem,0,0,$qtde, "E","Venda Cancelada $_idpedido");	
						}

			   $_SQL = " INSERT INTO ". $_SESSION['BASE'] .".itemestoquemovto
					   (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento,
					   Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,
					   Motivo,Saldo_Atual,Data_Movimento ) 
					   values 
					   ( '$iditem',
						   '$qtde',
						   '$almox',
						   'e',
						   'v',
						   '$_idpedido','$valor','0','$total','$usuario','Venda Cancelada','$qtde_atual','$datahora') ";
			   $stm = $pdo->prepare($_SQL);	
			   $stm->execute();	

			  

	   
		   }

		

		   if($entrada > 0  ) { 
			$_SQL = "Update ". $_SESSION['BASE'] .".financeiro  set financeiro_situacaoID = '1' 
			where Documento  = '$_idpedido' and financeiro_identificador = '2' and  financeiro_emissao = CURRENT_DATE() ";
				$stm = $pdo->prepare($_SQL);	
				$stm->execute();	

		   }
		   
			$_SQL = "Update ". $_SESSION['BASE'] .".financeiro  set financeiro_situacaoID = '1' 
			where financeiro_referente = '1' and Documento  = '$_idpedido' and financeiro_identificador = '0' and financeiro_emissao = CURRENT_DATE()";
			$stm = $pdo->prepare($_SQL);	
			$stm->execute();	

			$_SQL = "DELETE FROM ". $_SESSION['BASE'] .".livro_caixa  
			where  Livro_Num_Docto  = '$_idpedido' AND Livro_caixa_data_lancamento >= '$dataATUAL 00:00:00'";
			$stm = $pdo->prepare($_SQL);	
			$stm->execute();	
		 
		

		//verificar cancelamento nf 
		if($_numeroNF > 0) {
			try{
				date_default_timezone_set('America/Sao_Paulo');      
		
				
		
				
			
				$consultaLinha = $pdo->query("Select nfed_chave,nfed_protocolo,nfed_empresa
				from ". $_SESSION['BASE'] .".NFE_DADOS 
				where  nfed_pedido = '".$_idpedido."' and nfed_numeronf = '".$_numeroNF."' and  nfed_modelo <> '55' ");							   
				$retornoLinha = $consultaLinha->fetchAll();
				foreach ($retornoLinha as $row_nf) { 
					$chave =  $row_nf['nfed_chave'];
			  		$xJust =   trim("PEDIDO DE VENDA CANCELADO");
					$nProt =   $row_nf['nfed_protocolo'];
					$idemp = $row_nf['nfed_empresa'];
				}
			if($idemp < 1){	
						$idemp = 1;
			}
				
				// Instância NFeService
				$nfe = new NFeService($idemp, 65);
			
				
				$livro = 0;
			 
				$retcancelamento = $nfe->CancelarNF($chave, $xJust, $nProt);
			 
			//   echo "----------------------";
				$st = new Standardize();
				$stResponse = $st->toStd($retcancelamento);
				$cStat = $stResponse->retEvento->infEvento->cStat;
				$xMotivo = $stResponse->retEvento->infEvento->xMotivo;
				$xcancelada = 0;
			 //   echo "-- $cStat------ $xMotivo-------------";
				if( $cStat != "135") {
					$xcancelada = 0;
					?>
				
				<div class="alert alert-warning alert-dismissable"> 
									<p><strong>Nota não pode ser Cancelada</strong> </p>	
									<p><?=$chave;?>
									<p><?php echo $xMotivo ;?></p>
								</div>
								                           
						
										 
									<?php
		
				}else{
					$xcancelada = 1;
					?>
				
				<div class="alert alert-success alert-dismissable"> 		
									<p><strong> Executado</strong> !!!<?php echo $xMotivo ;?></p>
								</div>
								                             
								
										
									<?php
				}
				
			   $update = $pdo->prepare("UPDATE  ".$_SESSION['BASE'].".NFE_DADOS SET
			   nfed_cancelada = ?,
			   nfed_motEcarta = ?, 
			   nfed_xmlcancelado = ?
			   WHERE nfed_cancelada = '0' and  nfed_pedido = ? ");
			   $update->bindParam(1, $xcancelada);
			   $update->bindParam(2, $xJust);
			   $update->bindParam(3, $retcancelamento);     					
			   $update->bindParam(4, $_idpedido);
			  
			   $update->execute();
			} catch (\Exception $e) {
			 echo $e;
			}

		}


		//verificar cancelmaento pedido

		?>		
			<div class="alert alert-success alert-dismissable"> 
				Venda <?=$_idpedido;?> Cancelada  com sucesso !!!  
				
							
			</div>        	
	<?php
	}


	if($acao == "btImprimirCompleto"){ 	
		//atualiza saida estoque
		
		
		$sql = "select CODIGO_CLIENTE,Valor_Entrada,Tipo_Pagamento_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_idpedido."' and Ind_Financeiro = 0 limit 1";
				
				$stm = $pdo->prepare($sql);
				$stm->execute();
				while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 					
					$entrada = $rst->Valor_Entrada;
					$_TIPOPGTO =  $rst->Tipo_Pagamento_Entrada;
					$_idcliente = $rst->CODIGO_CLIENTE;					
				}


		if($entrada > 0) {

		
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
							financeiro_valorDesconto,
							financeiro_totalduplicata,
							financeiro_identificador,
							Documento,
							financeiro_referente,
							financeiro_usucom
						) VALUES (
							'1',
							'1',
							'$_idcliente',
							'".$_SESSION['NOME']."',
							'$_idpedido',
							'REF ENTRADA PED. $_idpedido ',
							CURRENT_DATE(),
							CURRENT_DATE(),
							CURRENT_DATE(),
							'$entrada',
							'0',
							'1',
							'0',
							'1',
							'2',
							'1',
							'$_TIPOPGTO',
							'$datahora',
							'0',
							'1',
							'$entrada',
							CURRENT_DATE(),
							'Entrada Pedido',
							'0',
							'$entrada',
							'2',
							'$_idpedido',
							'1',
							'".$usuario."'
						)";          
						$stm = $pdo->prepare($_SQL);	
						$stm->execute();
						$_x = "(Entrada Registrada)";
						$Ind_Financeiro = ",Ind_Financeiro = 1";

						
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
						Livro_Num_Docto,
						Livro_caixa_historico,
						Livro_caixa_motivo
					) VALUES (
						'$_idcaixa',
						'$entrada',
						'$datahora',
						'$datahora',
						'".$_SESSION['IDUSER']."',
						'".$_SESSION['NOME']."',
						'$_TIPOPGTO',
						'".$_SESSION['hash_caixa']."',
						'$_idpedido',
						'VENDA ENTRADA $_idpedido',
						'5'
					)";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
					}

				

					$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '2' $Ind_Financeiro 
					WHERE NUMERO = '" . $_idpedido . "' ";               
					$stm = $pdo->prepare("$_SQL");	
					$stm->execute();	
	
		?>		
			Gerando Pedido e atualizando dados <?=$_x ;?>
	<?php
	}

	if($acao == "btOrcamento"){ 	
		//atualiza saida estoque
		$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '4' 
		WHERE NUMERO = '" . $_idpedido . "' ";               
		$stm = $pdo->prepare("$_SQL");	
		$stm->execute();	
	
		?>		
			Gerando Orçamento 
	<?php
	}
	if($acao == "btPedido"){ 	
		//atualiza saida estoque
	
		$sql = "select CODIGO_CLIENTE,Valor_Entrada,Tipo_Pagamento_Entrada from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_idpedido."' and Ind_Financeiro = 0 limit 1";
				
				$stm = $pdo->prepare($sql);
				$stm->execute();
				while($rst = $stm->fetch(PDO::FETCH_OBJ)){ 					
					$entrada = $rst->Valor_Entrada;
					$_TIPOPGTO =  $rst->Tipo_Pagamento_Entrada;
					$_idcliente = $rst->CODIGO_CLIENTE;					
				}


		if($entrada > 0) {

		
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
							financeiro_valorDesconto,
							financeiro_totalduplicata,
							financeiro_identificador,
							Documento,
							financeiro_referente,
							financeiro_usucom
						) VALUES (
							'1',
							'1',
							'$_idcliente',
							'".$_SESSION['NOME']."',
							'$_idpedido',
							'REF ENTRADA PED. $_idpedido ',
							CURRENT_DATE(),
							CURRENT_DATE(),
							CURRENT_DATE(),
							'$entrada',
							'0',
							'1',
							'0',
							'1',
							'2',
							'1',
							'$_TIPOPGTO',
							'$datahora',
							'0',
							'1',
							'$entrada',
							CURRENT_DATE(),
							'Entrada Pedido',
							'0',
							'$entrada',
							'2'	,
							'$_idpedido',
							'1',
							'".$usuario."'
						)";          
						$stm = $pdo->prepare($_SQL);	
						$stm->execute();
						$_x = "(Entrada Registrada)";
						$Ind_Financeiro = ",Ind_Financeiro = 1";

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
						Livro_Num_Docto,
						Livro_caixa_historico,
						Livro_caixa_motivo
					) VALUES (
						'$_idcaixa',
						'$entrada',
						'$datahora',
						'$datahora',
						'".$_SESSION['IDUSER']."',
						'".$_SESSION['NOME']."',
						'$_TIPOPGTO',
						'".$_SESSION['hash_caixa']."',
						'$_idpedido',
						'VENDA ENTRADA $_idpedido',
						'5'
					)";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
						
					}

					$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Cod_Situacao = '2' $Ind_Financeiro 
					WHERE NUMERO = '" . $_idpedido . "' ";               
					$stm = $pdo->prepare("$_SQL");	
					$stm->execute();	

					$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque SET Ind_Financeiro = 1
					WHERE NUMERO = '" . $_idpedido . "' ";               
					$stm = $pdo->prepare("$_SQL");	
					$stm->execute();	

					
	
		?>		
			Gerando Pedido e atualizando dados <?=$_x ;?>
	<?php
	}




	if($acao == "desconecta_venda"){
		$_numeropedido = "";
	}
	?>