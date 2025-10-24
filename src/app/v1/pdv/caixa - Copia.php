<?php 
	session_start();
	date_default_timezone_set('America/Sao_Paulo');
	
	require_once('../../../api/config/config.inc.php');
	require '../../../api/vendor/autoload.php';
	include("../../../api/config/iconexao.php");   

	use Database\MySQL;

	$pdo = MySQL::acessabd();	

	date_default_timezone_set('America/Sao_Paulo');

	$dia       = date('d');
	$mes       = date('m');
	$ano       = date('Y');
	$hora = date("H:i:s");

	$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
		
		//busca logo
		/*
		$sql = "Select arquivo_logo_base64 from " . $_SESSION['BASE'] . ".empresa_dados where id = '".$_SESSION['BASE_ID']."'";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){
			$img_logo = $value['arquivo_logo_base64'];
		}
*/

		$_SESSION['company'] = "PRISMA GESTÃO";
	
	 	$style='<link href="css/styleCaixaBlue.css" rel="stylesheet">';
		$logo = '<div style="margin-top:8px; font-weight:bold; color:#414850; font-size:21px; Noto Sans, Helvetica Neue, Helvetica, Arial, sans-serif"><img src="img/logo_sm_lg1dark.png" style="width:150" ></div>';
	 	$logoCliente = "background:
		 url(data:image/png;base64,".$img_logo.")
		 no-repeat;";
		 $_SESSION['corPadrao'] = "#00A8E6";
	 


	// $chaveAcesso = $_SESSION["chave_loja"];
	
	// $sql = "select * from consumidor where CODIGO_CONSUMIDOR = ?";
	// $stm = $pdo->prepare($sql);
	// $stm->bindParam(1,$chaveAcesso, \PDO::PARAM_STR);	
	// $stm->execute();
	// if($stm->rowCount() > 0){
	// 		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){
	// 			$_SESSION['BASE'] = $value['consumidor_base'];			
	// 		}
	// }else{

	if (empty($_SESSION['BASE'])) {
		?>
		<script>
		alert("Efetue Login novamente !!! Tempo expirado. ");
		location.href="/app";
		</script>
		<?php
	}
	$_idfrefGO = $_GET['refid'];
	$_idfref = base64_decode($_idfrefGO);	
	$_idfref = explode('-',$_idfref);

	/*VERIFICA SE CONEXAO COM CAIXA ESTÁ ATIVA====================================================================================================================================
	============================================================================================================================================================================
	=============================================================================================================================================================================*/
	if($_SESSION['id_caixa'] == ""){
		/*
		//busca caixa livre
		//DESCONECTA AUTOMATICAMENTE CAIXA DEPOIS DE 2HORAS DE INATIVIDADE
		$sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero where Ind_Sel = '0' or TIMESTAMPDIFF(SECOND, data_atualizacao_p, NOW()) >= 7200 and Ind_Sel = '-1' limit 1";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		if($stm->rowCount() > 0){
			foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){
				$_SESSION['descricao_caixa'] = $value['Descricao'];
				$_SESSION['id_caixa'] = $value["Livro_Numero"];
			}
			//registra como caixa em uso
			$sql="update ".$_SESSION['BASE'].".livro_caixa_numero set Ind_Sel = '-1', data_atualizacao_v = NOW() , data_atualizacao_p = NOW() where Livro_Numero = '".$value["Livro_Numero"]."'";
			$stm = $pdo->prepare($sql);
			$stm->execute();

	
			//gera código caixa
			$_SESSION['hash_caixa'] = md5(date('dmYHis'));

			//lanca valor inicial no caixa padrao
			$sql="insert into ".$_SESSION['BASE'].".livro_caixa (
				Livro_Numero,
				Livro_caixa_valor_entrada,
				Livro_caixa_data_lancamento,
				Livro_caixa_data_hora_lancamento,
				Livro_caixa_usuariio_alterado,
				Livro_caixa_Cod_Pagamento,
				Livro_hash,
				Livro_caixa_usuario_lancamento,
				Livro_caixa_motivo,
				Livro_caixa_historico
			) values (
				'".$_SESSION['id_caixa']."',
				'$credito_caixa',
				NOW(),
				NOW(),
				'".$_SESSION['NOME']."',
				'4',
				'".$_SESSION['hash_caixa']."',
				'".$_SESSION['IDUSER']."',
				'1',
				'Valor Caixa Inicial'
			)";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			
		}else{
			//força usuário a desconectar um caixa e se conectar nele
			include("seleciona_caixa.php");
			exit();
		}
	}else{
		//atualiza hora caixa

		$sql="select Livro_Numero from ".$_SESSION['BASE'].".livro_caixa where Livro_hash = '".$_SESSION['hash_caixa']."' limit 1";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){

			$sql="update ".$_SESSION['BASE'].".livro_caixa_numero set data_atualizacao_p = NOW() where Livro_Numero = '".$value["Livro_Numero"]."'";
			$stm = $pdo->prepare($sql);
			$stm->execute();
		}
		*/
	}	


	/*====================================================================================================================================
	============================================================================================================================================================================
	=============================================================================================================================================================================*/
	if($_idfref[1] == "") {
		$origem = 1;
	} else{
		$origem = 0;
	}

	if( $_idfref[1] == ""){	 //$_SESSION['numberPedido'] == "" 

		//busca novo numero do pedido
		$sql = "Select parametro_CODIGO_LOGIN, Num_Pedido_Venda, livro_padrao from ".$_SESSION['BASE'].".parametro ";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){
			$idPedido = $value['Num_Pedido_Venda'];
			$idPedidoX = $idPedido + 1;
		}	
				

		//Atualiza numero pedido
		$sql = "Update ".$_SESSION['BASE'].".parametro set Num_Pedido_Venda = '$idPedidoX'";
		$stm = $pdo->prepare($sql);
		$stm->execute();		

		//nova venda iniciada
		$_SESSION['numberPedido'] = $idPedido;		
		$_numeropedido = $idPedido;	
		//cria pedido
		 $sql = "Insert into ".$_SESSION['BASE'].".saidaestoque (
			NUMERO,
			CODIGO_CLIENTE,			
			COD_TIPO_SAIDA,
			DATA_CADASTRO,
			DATA_HORA,
			Cod_Situacao,
			COD_Vendedor,
			CLIENTE,
			num_livro,
			origem
		 )values (
			'$idPedido',
			'1',
			'1',			
			CURRENT_DATE(),
			'$datahora',
			'1',
			'".$_SESSION["tecnico"]."',
			'CONSUMIDOR',
			'1',
			'$origem'
		 )";		
		 $stm = $pdo->prepare($sql);
		 $stm->execute();

	}else{
		if($_SESSION['numberPedido'] != ""  and  $_idfref[1] == "") {
			
			$_numeropedido = $_SESSION['numberPedido'];

			$sql="select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_SESSION['numberPedido']."' and COD_Vendedor = '".$_SESSION["tecnico"]."'";
			$stm = $pdo->prepare($sql);
			$stm->execute();
			
			if($stm->rowCount() == 0){
				//busca novo numero do pedido
				$sql = "Select parametro_CODIGO_LOGIN, Num_Pedido_Venda, livro_padrao from ".$_SESSION['BASE'].".parametro ";
				$stm = $pdo->prepare($sql);
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){
					$idPedido = $value['Num_Pedido_Venda'];
					$idPedidoX = $idPedido + 1;
				}	
					//Atualiza numero pedido
					$sql = "Update ".$_SESSION['BASE'].".parametro set Num_Pedido_Venda = '$idPedidoX'";
					$stm = $pdo->prepare($sql);
					$stm->execute();		

					//nova venda iniciada
					$_SESSION['numberPedido'] = $idPedido;		
							//cria pedido
					$sql = "Insert into ".$_SESSION['BASE'].".saidaestoque (
						NUMERO,
						CODIGO_CLIENTE,			
						COD_TIPO_SAIDA,
						DATA_CADASTRO,
						Cod_Situacao,
						COD_Vendedor,
						CLIENTE,
						num_livro,
						origem
					)values (
						'$idPedido',
						'1',
						'1',			
						CURRENT_DATE(),
						'1',
						'".$_SESSION["tecnico"]."',
						'CONSUMIDOR',
						'1',
						'$origem'
					)";
					
					$stm = $pdo->prepare($sql);
					$stm->execute();

					$_numeropedido = $idPedido;
					

			}
		}else{
			if($_idfref[1] != "") {
				$sql="select * from ".$_SESSION['BASE'].".saidaestoque as A
				LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda  as B ON A.Cod_Situacao = B.Cod_Situacao
				 where NUMERO = '".$_idfref[1]."'";
				$_numeropedido = $_idfref[1];
				$_SESSION['numberPedido'] = $_numeropedido;
			}else{
				$sql="select * from ".$_SESSION['BASE'].".saidaestoque where NUMERO = '".$_SESSION['numberPedido']."'";
				$_numeropedido = $_SESSION['numberPedido'];
			}
		
			$stm = $pdo->prepare($sql);
			$stm->execute();
			while($linha = $stm->fetch(PDO::FETCH_OBJ)){
				$cpfNota = $linha->cpfcnpj;
				$situacaoPedido = $linha->Cod_Situacao;
				$sistuacaoDesc= $linha->Descricao;
				$SAIDA_NFE = $linha->SAIDA_NFE;
				//buscar nf 
			}

		}
	
	}
	

?>
<html>
	<head>
		<title>PRISMA | PDV</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="icon" href="img/caixaIcon2.png" type="image/png">
		<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../assets/css/icons.css" rel="stylesheet">
		<meta http-equiv="cache-control" content="no-cache" />
		<?=$style;?>
		<style>
			.dle{
				cursor:pointer;
			}
			.dle:hover{
				color:red;
			}
			/* Labels */
			.label {
			font-weight: 600;
			letter-spacing: 0.05em;
			padding: .3em .6em .3em;
			}
			.label-default {
			background-color: #00a8e6;
			}
			.label-primary {
			background-color: #5d9cec;
			}
			.label-success {
			background-color: #81c868;
			}
			.label-info {
			background-color: #34d3eb;
			}
			.label-warning {
			background-color: #ffbd4a;
			}
			.label-danger {
			background-color: #f05050;
			}
			.label-purple {
			background-color: #7266ba;
			}
			.label-pink {
			background-color: #fb6d9d;
			}
			.label-inverse {
			background-color: #4c5667;
			}

		</style>
	</head>
	<body>
	
	<form  id="form1" name="form1" method="post" action="">
		<input type="hidden" id="_keyform" name="_keyform"  value="">
		<input type="hidden" id="_chaveid" name="_chaveid"  value="">
		<input type="hidden" id="_ref" name="_ref"  value="<?=$_idfrefGO;?>">
		
		<input type="hidden" id="id_pedido" name="id_pedido"  value="<?=$_numeropedido;?>">
		<input type="hidden" id="id_caixa" name="id_caixa"  value="<?=$_caixa;?>">
		<input type="hidden" id="tipofim" name="tipofim"  value="1">						
	</form>
		<div class="container-fluid">
		
			<div class="row">
				<div class="col-md-3">
					<!--<div class="full" id="btn-fullscreen" ><i class="icon-size-fullscreen"></i></div>-->
					<?=$logo;?>
				</div>
				<div class="col-md-6 relogio">
					<div id="relogio"></div>
				</div>
				<div class="col-md-3 statusCaixa">
					<div id="situacaoCaixa" style="color:#FFF; font-weight:300;"><span class="stat green">&nbsp;</span> IDENTIFICAÇÃO: <b style="color:#333;"><?=$_SESSION['descricao_caixa'];?></b><br>N° VENDA: <b style="color:#333;"><?=$_numeropedido;?></b></div>
					<!---INPUT DE SITUAÇÃO DO CAIXA---->
					<input type="hidden" id="sitCx" name="sitCx" value="1" style="color:#000;">
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-5" style="height:87%">
					<div class="col-sm-12 fundo1">
						<div style="<?=$logoCliente;?> background-size:cover; height:90%; width:90%; position:absolute; opacity:0.7; top:35;"></div>
						<div id="listaItem">
							<table class="table">
								<tr>
									<th class="lin">#</th>
									<th class="lin">DESCRIÇÃO</th>
									<th class="lin">QTDE</th>
									<th class="lin" style="min-width: 100px ;">VALOR UN.</th>
									<th class="lin" style="min-width: 100px ;" >VALOR</th>
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
										if($linha->VALOR_UNITARIO != $linha->VALOR_UNIT_DESC){
											$hideDesconto = "SIM";
										}else{
											$hideDesconto = "";
										}
								?>
										<tr>
											<td><b><?=$linha->ITEM;?></b></td>
											<td><b><?=$linha->DESCRICAO_ITEM;?></b></td>
											<td><b><?=$linha->QUANTIDADE;?></b></td>
											<td><b><?="R$ ".number_format($linha->VALOR_UNIT_DESC,2,',','.');?></b></td>
											<td><b><?="R$ ".number_format($linha->VALOR_TOTAL,2,',','.');?></b></td>
											<td class="delclass" style="padding:0px; padding-top:3px;"><span style="font-size:16px; color:red; font-weight:bold; cursor:pointer;" onclick="deletItem('<?=$linha->ITEM;?>')">X</span></td>
											<td class="desclass" style="padding:0px; padding-top:3px;"><?php if($hideDescontox == ""){ ?><span style="font-size:16px; color:orange; font-weight:bold; cursor:pointer;" onclick="descontoItem('<?=$linha->ITEM;?>')"><i class="fa fa-plus"></i></span> <?php } ?></td>
										</tr>
									<?php } }
									$totalValor = $totalValor-$descontoVenda;
									?>
							</table>
							<input type="hidden"  id="totalVenda" name="TotalVenda" value="<?=number_format($totalValor,2,',','.');?>">
							<input type="hidden"  id="trocoC" name="trocoC" value="">
						</div>
					</div>
					<div class="col-sm-12 top valorT">TOTAL: <span id="tt" style="color:#2FFF00;">R$ <?=number_format($totalValor,2,',','.');?></span></div>			
				</div>
				
				<div class="space" style="height:30px;">&nbsp;</div>
				
				
				<div class="col-md-7" style="height:87%">
					<div class="row o2">
						<div class="col-sm-2 default">
							<b>CÓDIGO </b>
						</div>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="codigoBarras" name="codigoBarras" placeholder="PRESSIONE ENTER PARA LER O CÓDIGO ">
						</div>
						<div class="col-sm-3">
							<button class="btn btn-primary btn-block" id="procurar" name="procurar" onclick="search()"> Buscar </button>
						</div>
					</div>
					<div class="row o3 top">
						<div class="col-sm-2 default" style="text-align:right;">
							<b> QUANTIDADE: </b>
						</div>
						<div class="col-sm-1" style="padding:1px;">
							<input type="text" id="qtde" name="qtde" placeholder="0" class="form-control">
						</div>
						<div class="col-sm-2 default" style="text-align:right;">
							<b>DESCRIÇÃO: </b>
						</div>
						<div class="col-sm-7 default" style="text-align:center; border-bottom:1px solid #C1C1C1;">
							<span id="descdesc" style="opacity:0.6;">PESQUISE ALGUM ITEM PARA LISTAR AQUI</span>
							<input type="hidden" value="" id="itemPesquisado" name="itemPesquisado">
						</div>
					</div>
					<div class="row o4">
						<div class="col-sm-6 ">
							<b>VALOR UNITÁRIO:<span id="valorUnit" class="yellow"> R$ 0,00</span></b>
						</div>
						<div class="col-sm-6 " style="text-align:right;">
							<b>VALOR TOTAL:<span id="valorTot" class="yellow"> R$ 0,00</span></b>
						</div>				
					</div>
					<div class="space" style="height:30px;">&nbsp;</div>
					<?php 
					if($situacaoPedido == '3' or $situacaoPedido == '9') { ?>
					<div class="row fundo">
							<div class="col-xs-12   top">								
								<span style="color:#00A8E6;">Pedido: <b style="color:#FFF"><?=$sistuacaoDesc;?></b> 
							</div>
							<?php if($situacaoPedido == '3') { ?>
								<div class="col-xs-6  top">		
									<?php 
									if($SAIDA_NFE == 0){ ?>
										<button type="button" class="btn btn-warning btn-block" style="padding:12px;"  onclick="_ImprimirVenda('<?=$_numeropedido;?>')" >Imprimir Cupom</button>
									<?php }else{ ?>
										<button type="button" class="btn btn-warning btn-block" style="padding:12px;"  onclick="_ImprimirVendaNF('<?=$_numeropedido;?>')" >Imprimir NFCe</button>
									<?php }
									?>
								</div>
								<div class="col-xs-4 top">
								<button class="btn btn-warning btn-block" id="total" onclick="finalizaSalva(<?=$_numeropedido ;?>)" style="padding:12px;"><b> Opções</button></b></button><br>								
								
							</div>
							<?php }
							?>
							
							
					</div>
					<?php }else{
					?>
					
					<div class="row ">
						<div class="col-sm-11 fundo top">
							<div class="col-xs-3"><button class="btn btn-white btn-block" onclick="NovaVenda()" id="iniciar"><b>NOVA VENDA</button></b></div>														
							<div class="col-xs-3"><button class="btn btn-white btn-block" onclick="reabrirVenda('','','')"  data-toggle="modal" data-target="#modal-reabrirVenda"><b>REABRIR VENDA<BR></button></b></button></div>
							<div class="col-xs-3"><button class="btn btn-white btn-block" id="desconto" onclick="descotinho()"><b>DESCONTO/ALT ITEM</button></b></button></div>
							<div class="col-xs-3"><button class="btn btn-white btn-block" id="total" onclick="cpf_nota()"><b>CPF/CNPJ NA NOTA</button></b></button></div>
							
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" id="cancelaItem" onclick="cancelaItem()"><b>CANCELA ITEM</button></b></button></div>
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" id="cancelaVenda" onclick="cancelaCompra()"><b>CANCELA VENDA</button></b></button></div>
							<div class="col-xs-3 top"><button class="btn btn-white btn-block"id="imprime" onclick="reimpressao()"><b>REIMPRIME CUPOM</button></b></button></div>
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" onclick="att_valorCaixa()"><b>AT/CAIXA</b></button></button></div>
<!--
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" id="consulta" onclick="search();"><b>CONSULTA<BR>(P)</button></b></button></div>
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" id="comanda"><b>COMANDA<BR></button></b></button></div>
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" id="gerencial"><b>GERENCIAL<BR></button></b></button></div>
							<div class="col-xs-3 top"><button class="btn btn-white btn-block" id="troca"><b>TROCA<BR></button></b></button></div>
										-->
							<div class="col-xs-8 top">
								<button class="btn btn-success btn-block" id="total" onclick="finalizaVenda()" style="padding:12px;"><b> PAGAR</button></b></button><br>
								
							</div>
							<div class="col-xs-4 top">
								<button class="btn btn-warning btn-block" id="total" onclick="finalizaSalva(<?=$_numeropedido ;?>)" style="padding:12px;"><b> SALVAR e OPÇÕES</button></b></button><br>								
								
							</div>
							<div class="col-xs-12 top">								
								<span style="color:#00A8E6;">Atalhos: <b style="color:#FFF">(ENTER)</b> Cod. barras/Add item <b style="color:#FFF"></b> </span>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
				
				<div class="barraLateral" style="top:40%; color:yellow; display:none"><i class="fa fa-share"></i></div>
				<div class="barraLateral" style="top:49%; opacity:0.7; display:none"><i class="fa fa-table"></i></div>
				<!--<div class="barraLateral" style="top:58%; opacity:0.7;" onclick="configuracao()"><i class="fa fa-gear"></i></div>-->
				<div class="barraLateral" style="top:40%; opacity:0.7;" onclick="fechar_pdv()"><i class="fa fa-minus"></i></div>
				<!--<a href="logout.php"><div class="barraLateral" style="top:47%; opacity:0.7;"><i class="fa fa-power-off"></i></div></a>-->
				
				
				
				
		<input type="hidden" value="<?=$_SESSION['NOME'];?>" name="nameUser" id="nameUser">
		<input type="hidden" value="<?=$_numeropedido;?>" name="NumeroPedido" id="NumeroPedido">
		<input type="hidden" value="<?=$cpfNota;?>" name="cpfNota" id="cpfNota">
		
	
		<!-- MODAL -->
		<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-body">
			  <button type="button" class="close" onclick="fecharModal()"><span aria-hidden="true">&times;</span></button>
			  <br>
			  <br>
				<div class="row">
					<div class="col-sm-12">
						<input type="text" class="form-control" id="pesq" name="pesq" placeholder="Procurar item por descrição" onkeyup="buscaItem(this.value)">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-12" id="retPesq">
						<table class="table">
							<tr>
								<th></th>
								<th>Descrição</th>
								<th>Valor</th>
								<th>Cód. Interno</th>
								<th>Cód de Barras</th>
							</tr>
							<tr>
								<td colspan="5" style="text-align:center;">Digite alguma coisa para procurar</td>
							</tr>
						</table>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>

		<!-- MODAL -->
		<div class="modal fade bs-example-modal-lg" id="finalizarVenda" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-xs-8"><h5>PAGAMENTO</h5></div>
					<div class="col-xs-4" id="fechar_pgto"><button type="button" class="close" onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">×</span></button></div>
					
				</div>
			  <div class="modal-body" id="divPag">
				
			  </div>
			</div>
		  </div>
		</div>

		
		<!-- MODAL SALVAR -->
		<div class="modal fade bs-example-modal-lg" id="finalizarSalvar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-xs-8"><h5>PEDIDO</h5></div>
					<div class="col-xs-4" ><button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button></div>
					
				</div>
			  <div class="modal-body" id="divSalvar">
				
			  </div>
			</div>
		  </div>
		</div>
		
		
		<!-- REABRIR VENDA -->
		<div class="modal fade bs-example-modal-lg" id="modal-reabrirVenda" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-xs-8"><h5>REABRIR VENDA</h5></div>
					<div class="col-xs-4"><button type="button" class="close" onclick="fecharModal()" style="margin-top:4px;"><span aria-hidden="true">×</span></button></div>
					
				</div>
			  <div class="modal-body" id="div-ReabrirVenda">
				
			  </div>
			</div>
		  </div>
		</div>


		<div id="modal-balanca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content text-center">
					<div class="modal-body">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4><i class="fa fa-exclamation"></i> Balança não está conectada ou configurada.</h4>
					</div>
				</div>
			</div>
		</div>
	
		<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content text-center">
				<div class="modal-body" id="_printviewer">
					Gerando impressão
				</div>
			</div>
		</div>
	</div>

		<!-- MODAL CONFIGURACAO-->
		<div class="modal fade" id="modal_config" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" onclick="fecharModal()"><span aria-hidden="true">&times;</span></button>
					<h5>CONFIGURAÇÕES</h5>
				</div>
			  <div class="modal-body" id="divConf">
				
			  </div>
			</div>
		  </div>
		</div>	
		
	</body>
</html>
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.min.js"></script>
<script src="bootbox/bootbox.min.js"></script>
 <script src="bootbox/bootbox.locales.min.js"></script>
 <script src="../../assets/js/ajax.js"></script>
 <script src="../../assets/js/printThis.js"></script>

 <!-- App core js -->
<script src="../../assets/js/jquery.core.js"></script>
<script src="../../assets/js/jquery.app.js"></script>

<!---FULLSCREEN-->
<div id="ascrail2000" class="nicescroll-rails" style="width: 6px; z-index: 99; cursor: default; position: fixed; top: 60px; left: 1576px; height: 409px; display: block; opacity: 0;"><div style="position: relative; top: 0px; float: right; width: 6px; height: 297px; background-color: rgb(152, 166, 173); border: rgb(152, 166, 173); background-clip: padding-box; border-radius: 5px;"></div></div><div id="ascrail2000-hr" class="nicescroll-rails" style="height: 6px; z-index: 99; top: 463px; left: 1342px; position: fixed; cursor: default; display: none; width: 234px; opacity: 0;"><div style="position: relative; top: 0px; height: 6px; width: 240px; background-color: rgb(152, 166, 173); border: rgb(152, 166, 173); background-clip: padding-box; border-radius: 5px;"></div></div>
<script src="../../assets/js/jquery.app.js"></script>
<!---FULLSCREEN FIM-->
<script>

	//funcao de hora e usuario logado!
	var myVar = setInterval(myTimer ,1000);
    function myTimer() {
		
		var data = new Date(),
        dia  = data.getDate().toString().padStart(2, '0'),
        mes  = (data.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro come?a com zero.
        ano  = data.getFullYear();
		var dataHoje = dia+"/"+mes+"/"+ano;
		
		
		var nome = $("#nameUser").val();
        var d = new Date(), displayDate;
       if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
          displayDate = d.toLocaleTimeString('pt-BR');
       } else {
          displayDate = d.toLocaleTimeString('pt-BR', {timeZone: 'America/Sao_Paulo'});
       }
          document.getElementById("relogio").innerHTML = dataHoje+" "+displayDate+" | "+nome;
    }



		
		document.querySelector('body').addEventListener('keydown', function(event) {
 
 			var key = event.keyCode;
			 
			 	//status do caixaalert_FinalizarVenda
				var sitVenda = $("#sitCx").val();
				
				var cod = $("#codigoBarras").val();
				var qtde = $("#qtde").val();

				
			
				
				//ATALHO PARA CODIGO DE BARRA
				if(key == '13' && sitVenda == '1' && cod == ""){	
					$("#codigoBarras").focus();
				}
				//ATALHO PROCURAR ITEM
				if(key == '66' && sitVenda == '1'){
					search();
					var apaga = 1;
				}
				//ATALHO PARA QUANTIDADE
				if(key == '81' && sitVenda == '1'){
					//$("#qtde").focus();
					//var apaga = 1;
				}
				//ATALHO PARA ABRIR MODAL DE PAGAMENTO
				if(key == '80' && sitVenda == '1'){
				//	finalizaVenda();
				}
				//ATALHO PARA PAGAR COM NFC-E
				if(key == '80' && sitVenda == '3'){
					//fim('2');	
				}
				//ATALHO PARA FINALIZAR PEDIDO SEM CUPOM FISCAL
				if(key == '70' && sitVenda == '3'){
					//fim('1');	
				}
				//ESC - CANCELA TODA OPERACAO
				if(key == '27'){
					//$("#sitCx").val('1');
				}

				//FAZ INCLUSAO DE PRODUTO NAO CADASTRADO
				if(key == '13' && cod != "" && sitVenda == '5'){
					
					add_produto();
				}
				//FAZ A INCLUSAO DO ITEM NA COMPRA
				if(key == '13' && cod != "" && sitVenda == '1'){	
					if(cod == '1'){
						//produto nao cadastrado

						dialog = bootbox.dialog({
							message: '<h4>Produto não cadastrado, informe o valor:</h4><hr><div class="row"><div class="col-sm-2"></div><div class="col-sm-4"><input type="tel" class="form-control" id="nao_cadastrado" name="nao_cadastrado" placeholder="0,00" onKeyPress="return(moeda(this,'+"',',',',event"+'));"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="add_produto()" ><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
						});

						setTimeout(function(){ $("#nao_cadastrado").focus(); },800);

						$("#sitCx").val('5');


					}else{
						
								$.post('acaoCaixa.php',{cod:cod , qtde:qtde , acao:'lerCodigoBarra',ref: $("#_ref").val()}, function(resp){
								
									var retorno = $.trim(resp);
									$('#listaItem').html(retorno);	
									$("#codigoBarras").val("");
									$("#qtde").val("");
									
									$("#descdesc").html("PESQUISE ALGUM ITEM PARA LISTAR AQUI");
									$("#descdesc").css("opacity","0.6");
									$("#valorUnit").html("R$ 0,00");
									$("#valorTot").html("R$ 0,00");
									
									var TotalVenda = $("#totalVenda").val();
									$("#tt").html('R$ '+ TotalVenda);
									
									
									//PERGUNTA APOS PRIMEIRO ITEM SE QUER CPF NA NOTA
									var cpfNota = $("#cpfNota").val();
									if(cpfNota == ""){	
										cpf_nota();
									}
								});
							}		
				}


				if(apaga == 1){
					setTimeout(function(){ apagar(); }, 10);
				}
		});

		function TABEnter(tabA,_id){
			
			var oEvent = (oEvent)? oEvent : event;
			var oTarget =(oEvent.target)? oEvent.target : oEvent.srcElement;
			if(oEvent.keyCode==13){
					if(tabA == "1" && oEvent.keyCode==13){
						verificaSenha();
					}  
					if(tabA == "2" && oEvent.keyCode==13){
						verificaSenhaDesconto(_id);
					} 
					if(tabA == "3" && oEvent.keyCode==13){
					
						$('#novoValor').focus();
					}      
				
					

	   
            
		}
		}

	function qtde(){
		$("#qtde").focus();
	}
	function finalizaVenda(){
			 
		$("#sitCx").val('3');

		 $("#finalizarVenda").modal();

		 $.post('acaoCaixa.php',{acao:'finalizaPagamento',ref:<?= $_SESSION['numberPedido'];?>}, function(resp){
			var retorno = $.trim(resp);
			$('#divPag').html(retorno);	
		});
		
	}

	function finalizaSalva(_ped){
		
		 $("#finalizarSalvar").modal();		 
		
		 $.post('acaoCaixa.php',{acao:'finalizaSalva',ref: _ped}, function(resp){
			var retorno = $.trim(resp);
			$('#divSalvar').html(retorno);	
		});
		
	}

	
	function _btSalvar(){	
		$.post('acaoCaixa.php',{acao:'btSalvar',ref: '<?= $_SESSION['numberPedido'];?>'}, function(resp){
		   var retorno = $.trim(resp);
		   $('#retacao').html(retorno);	
	   });	   
   }

   function _btFechar(){	
		$("#finalizarSalvar").modal('hide');	
   }



   function _btImprimirCompleto(){	
		$.post('acaoCaixa.php',{acao:'btImprimirCompleto',ref: $("#_ref").val()}, function(resp){
		   var retorno = $.trim(resp);
		 //  $('#retacao').html(retorno);	
	   });	  
	   var $_keyid = "_PDV00004";
								$('#_keyform').val($_keyid);
								var dados = $("#form1 :input").serializeArray();
								dados = JSON.stringify(dados);
								$('#_printviewer').html("");
								//verificar cartao
					
					
								$.post("page_return.php", {_keyform: $_keyid,dados:dados},
									function (result){																		
										$('#_printviewer').html(result);
										$('#_printviewer').printThis(); 
									}
									);
																			
							
   }

   function _btOrcamento(_ped){	
		$.post('acaoCaixa.php',{acao:'btOrcamento',ref:_ped}, function(resp){
		   var retorno = $.trim(resp);
		  $('#retacao').html(retorno);	
	   });	  
	   var $_keyid = "_PDV00005";
								$('#_keyform').val($_keyid);
								var dados = $("#form1 :input").serializeArray();
								dados = JSON.stringify(dados);
								$('#_printviewer').html("");
								//verificar cartao
					
					
								$.post("page_return.php", {_keyform: $_keyid,dados:dados},
									function (result){																		
										$('#_printviewer').html(result);
										$('#_printviewer').printThis(); 
									}
									);
																			
							 
   }

   function _btNFe(){	

		$.post('acaoCaixa.php',{acao:'btNFe',ref: $("#_ref").val()}, function(resp){
		   var retorno = $.trim(resp);
		   $('#retacao').html(retorno);	
	   });	   
   }

	
	
	function apagar(){
		var qtde = $("#qtde").val();
		if(qtde == 'q' || qtde == 'Q' ){
			$("#qtde").val("");
		}
		var cod = $("#codigoBarras").val();
		if(cod == 'q' || qtde == 'Q'){
			$("#codigoBarras").val("");
		}

	}
	function fechar_pdv(){
		window.close();		
	}
	function Encerra_pdv(){
		$.post('acaoCaixa.php',{acao:'desconecta_venda'}, function(resp){
			window.close();
		});
		
	}
	function search(){
		
		 $("#myModal").modal();
		 setTimeout(function(){ $("#pesq").focus(); }, 800);
		 
		 //status do caixa
		$("#sitCx").val('2');		 
		 
	}
	function buscaItem(descricao){
		$.post('acaoCaixa.php',{descricao:descricao , acao:'pesquisarItem',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			$('#retPesq').html(retorno);	
		});
	}
	function reabrirVenda(tipo){
		if(tipo == "1"){
			var dataIni = $("#dataIniV").val();
			var dataFim = $("#dataFimV").val();
			var descricao = $("#descricaoV").val();
		}else{
			var dataIni = "";
			var dataFim = "";
			var descricao = "";

		}
		$.post('acaoCaixa.php',{dataIni:dataIni , dataFim:dataFim , acao:'ReabrirVenda' , descricao:descricao}, function(resp){
			var retorno = $.trim(resp);
			$('#div-ReabrirVenda').html(retorno);	
		});
	}
	function sel(desc, codbarra , valor , vlrMask){
		$("#pesq").val("");
		buscaItem('');
		$("#myModal").modal('hide');
		
		var qtde = $("#qtde").val();
		if(qtde == "" || qtde == 0){		
			qtde = 1;
			$("#qtde").val(qtde);
		}
		
		var valortotal = parseFloat(valor)*parseFloat(qtde);
		var valor = parseFloat(valor);
		var f = valortotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
		var f2 = valor.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
		
		$("#valorUnit").html(f2);
		$("#valorTot").html(f);
		$("#codigoBarras").val(codbarra);
		
		$("#descdesc").html(desc);
		$("#descdesc").css("opacity","1");
		
		 //status do caixa
		$("#sitCx").val('1');
		
	}
	function add_produto(){
		var cod = $("#codigoBarras").val();
		var valorProduto = $("#nao_cadastrado").val();
		valorProduto = parseFloat(valorProduto.replace('.','').replace(',','.'));
		var qtde = $("#qtde").val();
		
		if(valorProduto != "" && valorProduto > 0){
			$.post('acaoCaixa.php',{valorProduto:valorProduto , acao:'ProdutoNaoCadastrado', qtde:qtde , cod:cod}, function(resp){
				var retorno = $.trim(resp);
				$('#listaItem').html(retorno);	
				$("#codigoBarras").val("");
				$("#qtde").val("");
				
				$("#descdesc").html("PESQUISE ALGUM ITEM PARA LISTAR AQUI");
				$("#descdesc").css("opacity","0.6");
				$("#valorUnit").html("R$ 0,00");
				$("#valorTot").html("R$ 0,00");
				
				var TotalVenda = $("#totalVenda").val();
				$("#tt").html('R$ '+ TotalVenda);

				bootbox.hideAll();
							
				
				//PERGUNTA APOS PRIMEIRO ITEM SE QUER CPF NA NOTA
				var cpfNota = $("#cpfNota").val();
				if(cpfNota == ""){	
					cpf_nota();
				}

				//status do caixa
				$("#sitCx").val('1');
			});
		}else{
			$("#msgS").html("Informe um valor valido!");
		}

	}
	function cpf_nota(){
		
		$("#cpfNota").val("-");
		bootbox.confirm('CPF NA NOTA?<br><input type="text" class="form-control" name="informeCpf" id="informeCpf" onkeypress="maskCpfCnpj('+"'informeCpf'"+')" onKeyup="nm()">', function(result){ 
			var resposta = "22"+result;
			if(resposta == "22true"){
				var informeCpf = $("#informeCpf").val();
				if(informeCpf == ""){
					cpf_nota();
				}else{
					$("#cpfNota").val(informeCpf);
					upCpf();
				}
			}else{
				upCpf();
			}
		});
		setTimeout(function(){ $("#informeCpf").focus(); }, 800);
	}
	function upCpf(){
		var cpfcpnjnota = $("#cpfNota").val();
		$.post('acaoCaixa.php',{cpfcpnjnota:cpfcpnjnota , acao:'cpfNota',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
		});
	}
	
	function fecharModal(){
	
		$("#myModal").modal('hide');
		$("#finalizarVenda").modal('hide');
		$("#modal_config").modal('hide');
		$("#modal-reabrirVenda").modal('hide');
		 //status do caixa
		$("#sitCx").val('1');
	}
	function fecharModal2(){
	
		bootbox.hideAll();
		//status do caixa
		$("#sitCx").val('1');
	}
	//ADICIONA MAIS UM METODO DE PAGAMENTO - FINALIZA PAGAMENTO
	function add(i){
		var divRetor = "#divmaispagto" + i;
		
		var cont = $("#totalForma").val();
		var conta = parseInt(cont) + parseInt(1);
		$("#totalForma").val(conta);
		
		$.post('acaoCaixa.php',{q:i , acao:'maisFormapagamento',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			$(divRetor).html(retorno);	
		});
	}
	
	function selTipo(i,valor){
		var div = "#parc"+i;
		var vlr = "#vlrPag"+i;
		var valorPago = $(vlr).val();
		$.post('acaoCaixa.php',{q:i , formpagmento:valor , acao:'parcelamento',ref: $("#_ref").val(), valorPago:valorPago}, function(resp){
			var retorno = $.trim(resp);
			$(div).html(retorno);	
		});
	}
	function calc_parcela(i,valorPago){
		var div = "#parc"+i;

		
		var par = "#parpag"+i;
		var parcela_selecionada = $(par).val();

		var div2 = "#selpag"+i;
		var cond_pagto = $(div2).val();

		$.post('acaoCaixa.php',{q:i ,formpagmento:cond_pagto , acao:'parcelamento',ref: $("#_ref").val(), valorPago:valorPago, parcela_selecionada:parcela_selecionada}, function(resp){
			var retorno = $.trim(resp);
			$(div).html(retorno);	
		});

	}
	
	function incluirVend(){
		$("#divFormaPagamento").addClass("hid");
		$("#divDadosConsumidor").removeClass("hid");
		$("#divBuscaConsumidor").addClass("hid");
		
		$("#inc").addClass("hid");
		$("#retForm").removeClass("hid");

		$("#inc2").removeClass("hid");
		$("#retForm2").addClass("hid");

		$("#sitCx").val('4');
	}	
	function retomarVend(){
		$("#divFormaPagamento").removeClass("hid");
		$("#divDadosConsumidor").addClass("hid");
		$("#divBuscaConsumidor").addClass("hid");
		
		$("#inc").removeClass("hid");
		$("#retForm").addClass("hid");

		$("#inc2").removeClass("hid");
		$("#retForm2").addClass("hid");

		$("#sitCx").val('3');
	}

	function buscaConsumidor(){
		$("#divFormaPagamento").addClass("hid");	
		$("#divDadosConsumidor").addClass("hid");
		$("#divBuscaConsumidor").removeClass("hid");

		$("#inc").removeClass("hid");
		$("#retForm").addClass("hid");
		
		$("#inc2").addClass("hid");
		$("#retForm2").removeClass("hid");

		$("#sitCx").val('4');
	}

	function buscaCliente(){
		var bbconsumidor = $("#bbconsumidor").val();
		var tipoBB = $("#tipoBB").val();

		$.post('acaoCaixa.php',{bbconsumidor:bbconsumidor, tipoBB:tipoBB , acao:'buscaCliente',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			$("#respBuscaConsumidor").html(retorno);
		});
	}
	function selCliente(idCliente){

		$.post('acaoCaixa.php',{idCliente:idCliente, acao:'insereConsumidor',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			var rep = retorno.split("|");
			var nome = rep[0];
			var cpfcnpj = rep[1];

			$("#dsNome").html(nome);
			$("#dsCpf").html(cpfcnpj);
			retomarVend();
			block();
		});
	}
	function block(){
		$("#inc2").attr("disabled","disabled");
		$("#inc").attr("disabled","disabled");
	}
	function salvarConsumidor(){
		var nome = $("#nomeCompleto").val();
		var cpfCnpj = $("#cpf_cnpj").val();
		var nascimento = $("#nascimento").val();
		var email = $("#email").val();
		var telefone = $("#telefone").val();
		var site = $("#site").val();
		var cep = $("#cep").val();
		var endereco = $("#endereco").val();
		var bairro = $("#bairro").val();
		var cidade = $("#cidade").val();
		var uf = $("#estado").val();
		var complemento = $("#complemento").val();
		var numero = $("#numeroCasa").val();
		var celular = $("#celular").val();
		var fantasia = $("#fantasia").val();

		if(nome == ""){
			bootbox.alert("Necessário preencher o campo Nome Completo/Raz?o Social!");
		}else{
			if(cpfCnpj == ""){
				bootbox.alert("Necessário preencher o campo cpf ou cnpj!");
			}else{
				if(nascimento == ""){
					bootbox.alert("Necessário preencher o campo Data Nascimento!");
				}else{
					if(email == ""){
						bootbox.alert("Necessário preencher o campo E-mail!");
					}else{
						if(telefone == ""){
							bootbox.alert("Necessário preencher o campo Telefone!");
						}else{
							if(cep == ""){
								bootbox.alert("Necessário preencher o campo CEP!");
							}else{
								if(endereco == ""){
									bootbox.alert("Necessário preencher o campo Endere?o!");
								}else{
									if(bairro == ""){
										bootbox.alert("Necessário preencher o campo Bairro!");
									}else{
										if(cidade == ""){
											bootbox.alert("Necessário preencher o campo Cidade!");
										}else{
											if(uf == ""){
												bootbox.alert("Necessário preencher o campo UF(Estado)!");
											}else{											
												if(numero == ""){
													bootbox.alert("Necessário preencher o campo Número!");
												}else{												
													if(celular == ""){
														bootbox.alert("Necessário preencher o campo Celular!");
													}else{
														//TUDO CERTO!
														$.post('acaoCaixa.php',{acao:'cadastroConsumidor', nome:nome, cpfCnpj:cpfCnpj, nascimento:nascimento, email:email, telefone:telefone, cep:cep, endereco:endereco, bairro:bairro, cidade:cidade, uf:uf, numero:numero, complemento:complemento, site:site , celular:celular , fantasia:fantasia,ref: $("#_ref").val()}, function(resp){
															var retorno = $.trim(resp);
															if(retorno == ""){
																$("#btnIncluir").addClass("hid");	
																$("#btnSalvo").removeClass("hid");	
																
																retomarVend();
															}else{
																var dialog = bootbox.dialog({
																	message: 'Esse Cpf ou Cnpj já foi cadastrado anteriormente no sistema, incluímos ele na venda em questão! ;)'
																});
																
																$("#btnIncluir").addClass("hid");	
																$("#btnSalvo").removeClass("hid");	
																
																retomarVend();
															}
														});
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
	}
	function aguarde() {
		$('#divPag').html('' +
						            '<div class="bg-icon pull-request">' +
                							'<img src="../assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                							'<h2 class="text-center">Aguarde, processando dados...</h2>'+
            								'</div>');
    }

	
	function _FinalizarVendaCartao (tipo){
		dialog.modal('hide');
		dialog.find('.bootbox-body').html('<i class="fa fa-spin fa-cog"></i> Em processamento, aguarde...');
						$.post('acaoCaixa.php',{valorPago:valorPago, selpag:selpag , parpag:parpag, valorApagar:valorApagar, troco:troco, tipo:tipo, acao:'fimPagamento',ref: $("#_ref").val()}, function(resp){
							var retorno = $.trim(resp);
							dialog.find('.bootbox-body').html(retorno);    ;
						});
	}

	function fimCartao(tipo){
			tipo = $("#tipofim").val();

							//divPag
							aguarde(tipo);
							
											$.post('acaoCaixa.php',{ tipo:tipo,acao:'verPagamentocartao',ref: $("#_ref").val()}, function(resp){
											
												if(resp == ''){  

													//tudo ok
												
													// finaliza venda
													//GERA REGISTRO NO CONTAS A RECEBER
													//dialog.find('.bootbox-body').html('<i class="fa fa-spin fa-cog"></i> Em processamento, aguarde...');
												//	$.post('acaoCaixa.php',{valorPago:valorPago, selpag:selpag , parpag:parpag, valorApagar:valorApagar, troco:troco, tipo:tipo, acao:'fimPagamento',ref: $("#_ref").val()}, function(resp){
													$.post('acaoCaixa.php',{acao:'fimPagamento',ref: $("#_ref").val()}, function(resp){
														var retorno = $.trim(resp);
													//	dialog.find('.bootbox-body').html(retorno);    ;
														//dialog.modal('hide');
														$('#divPag').html(resp);
														if(tipo == "1"){
															//NOTA NAO FISCAL
														
															/*
															setTimeout(function(){
																window.open('cpmNaoFiscal.php');
																setTimeout(function(){
																	NovaVenda();
																}, 1000);
															}, 1500);
															*/
														

																/*
														
																var $_keyid = "_PDV00001";
																$('#_keyform').val($_keyid);
																var dados = $("#form1 :input").serializeArray();
																dados = JSON.stringify(dados);
																$('#_printviewer').html("");
																//verificar cartao


																$.post("page_return.php", {_keyform: $_keyid,dados:dados},
																	function (result){																		
																		
																		//$("#finalizarVenda").modal('hide');
																		$('#_printviewer').html(result);
																		
																			}
																);
														
														*/
															
														}
														if(tipo == "2"){
															//NOTA FISCAL
														//	dialog.modal('hide');
														//	bootbox.dialog({
														//		message: '<p class="text-center text-danger mb-0"><i class="fa fa-times"></i> Emissão NFCe não liberada, contate suporte  !</p>',
														//	});
															/*
															dialog.find('.bootbox-body').html('<i class="fa fa-spin fa-cog"></i> Imprimindo Cupom fiscal do consumidor...');
															setTimeout(function(){
																window.open('cpmFiscal.php');
																setTimeout(function(){
																	NovaVenda();
																}, 1000);
															}, 1500);
															*/
															
														}
													});

												}else{
													$('#divPag').html(resp);

												}
											});	
						
				
				

	}
	function alert(tipo){
		var mensagem = "";
		if(tipo == 'pgto_parcial'){vfrete
			var mensagem = "Ao finalizar ativar o pagamento parcial, você poderá finalizar a venda com valor inferior ao total, mas a venda não será finalizada!";
		}
		bootbox.dialog({
			message: '<p class="text-center mb-0"><i class="md-info-outline"></i> '+mensagem+'</p>',
		});
	}
	function fim(tipo){
		/*
		var dialog = bootbox.dialog({
			message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Finalizando venda, aguarde...</p>',
			closeButton: false
		});
		*/
		

		$("#tipofim").val(tipo);
		var totalForma = $("#totalForma").val();
		var valorTotal = $("#totalVenda").val();
		var vfrete = $("#frete").val();
		var troco = $("#trocoC").val();
		var atendente = $("#selVendedor").val();
		if(vfrete == ""){
			vfrete = 0;
		}else{
			vfrete = parseFloat(vfrete.replace('.','').replace(',','.'));
		}
		
		
		var valorApagar = parseFloat(valorTotal.replace('.','').replace(',','.'));
		valorApagar = valorApagar + vfrete;
		
		//SOMA OS PAGAMENTOS
	
		var i = 1;
		var valorPago = 0;
		for (i = 1; i <= totalForma; i++) {
			var nomediv = "#vlrPag" + i;
			
			var f = $(nomediv).val(); 
			if(f != ""){
				valorPago += parseFloat(f.replace('.','').replace(',','.'));
			}
		}	
		
		//VALIDA SE ? O VALOR TOTAL ? MENOR Q O VALOR PAGO
		if(valorApagar <=  valorPago && valorPago > 0){
		
				var i = 1;
				var valorPago = 0;
				var pagamentos = 0;
				var dinheiro = "";
				var liberaTroco = "";

				for (i = 1; i <= totalForma; i++) {
					
					var div_selpag = "#selpag" + i;
					var selpag = $(div_selpag).val(); 
					
					var div_parpag = "#parpag" + i;
					var parpag = $(div_parpag).val(); 
					
					var nomediv = "#vlrPag" + i;
					var f = $(nomediv).val();

					valorPago = parseFloat(f.replace('.','').replace(',','.'));

					//verica se tem troco
					if(troco > 0){
						//verifica se tem dinheiro para troco
						if(selpag == 4){
							dinheiro = "SIM";
							//verifica se valor do troco não é maior que o informado em dinheiro
							if(valorPago > troco){
								
								liberaTroco = "SIM";
							}
						}
					}else{
						dinheiro = "SIM";
						liberaTroco = "SIM";
					}
					

					//pega condições de pagamentos para loop php
					pagamentos = pagamentos + selpag+","+parpag+","+valorPago+"|";
					
				}
				
				
				if(dinheiro == "SIM" && liberaTroco == "SIM"){

				
					//divPag
					aguarde();				
			
					$.post('acaoCaixa.php',{frete:vfrete,valorPago:valorPago, selpag:selpag , parpag:parpag, valorApagar:valorApagar, troco:troco, tipo:tipo, acao:'verPagamento',ref: $("#_ref").val(),pagamentos:pagamentos}, function(resp){
											
						if(resp == ''){  
							// finaliza venda
							//GERA REGISTRO NO CONTAS A RECEBER
							//dialog.find('.bootbox-body').html('<i class="fa fa-spin fa-cog"></i> Em processamento, aguarde...');
							$.post('acaoCaixa.php',{frete:vfrete,valorPago:valorPago, selpag:selpag , parpag:parpag, valorApagar:valorApagar, troco:troco, tipo:tipo, atendente:atendente, acao:'fimPagamento',ref: $("#_ref").val()}, function(resp){
								var retorno = $.trim(resp);
															
								$('#divPag').html(resp);
								$('#fechar_pgto').html("");
								//	dialog.find('.bootbox-body').html(retorno);    ;
								//dialog.modal('hide');

								if(tipo == "1"){

									var $_keyid = "_PDV00001";
									$('#_keyform').val($_keyid);
									var dados = $("#form1 :input").serializeArray();
									dados = JSON.stringify(dados);
									$('#_printviewer').html("");
									//verificar cartao


									$.post("page_return.php", {_keyform: $_keyid,dados:dados},function (result){     
										$('#_printviewer').html(result);				
									});
															
															
																
								}
								if(tipo == "2"){														
									var $_keyid = "_PDV00002";
									$('#_keyform').val($_keyid);
									var dados = $("#form1 :input").serializeArray();
									dados = JSON.stringify(dados);
									$('#_printviewer').html("");
									//verificar cartao
									

									$.post("page_return.php", {_keyform: $_keyid,dados:dados},function (result){     
										$('#_printviewer').html(result);
																	
									});

																
								}
							});
						}else{
							$('#divPag').html(resp);
						}
					});	
				}else{
					
					if(dinheiro == ""){
						var mensagem = "Não pode haver troco se não houver pagamento em dinheiro";
					}else{
						if(liberaTroco == ""){
							var mensagem = "Valor do troco não pode ser maior que o dinheiro informado";
						}else{
							var mensagem = "Oops! Aconteceu algum erro com o troco!";
						}
					}
					
					bootbox.dialog({
						message: '<p class="text-center mb-0"><i class="fa fa-times"></i> '+mensagem+'</p>',
					});
				
				}

			
		}else{
			
		//	dialog.modal('hide');
			bootbox.dialog({
				message: '<p class="text-center mb-0"><i class="fa fa-times"></i> Valor insuficiente!</p>',
			});
		}
		/*
		
		*/
	}

	
	function _fecharFinalizar(){
		$.post('acaoCaixa.php',{acao:'finalizaPagamento',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			$('#divPag').html(retorno);	
		});
	}


	
	function _ImprimirVenda(_idref){
							
											
		var $_keyid = "_PDV00001";
			$('#_keyform').val($_keyid);
			var dados = $("#form1 :input").serializeArray();
			dados = JSON.stringify(dados);
			$('#_printviewer').html("");
			//verificar cartao


			$.post("page_return.php", {_keyform: $_keyid,dados:dados},
				function (result){																		
					$('#_printviewer').html(result);
					$('#_printviewer').printThis();
				}
				);
														

	}

	function _ImprimirVendaNF(_idref){
							
		
						var $_keyid = "_PDV00002";
								$('#_keyform').val($_keyid);
								var dados = $("#form1 :input").serializeArray();
								dados = JSON.stringify(dados);
								$('#_printviewer').html("");
								//verificar cartao
					
					
								$.post("page_return.php", {_keyform: $_keyid,dados:dados},
									function (result){																		
										$('#_printviewer').html(result);
										$('#_printviewer').printThis();
									}
									);
																			
							
	}

	function NovaVenda(){
		$.post('acaoCaixa.php',{acao:'NovaVenda'}, function(resp){
			 //location.reload(); 
			 window.open('caixa.php', '_self');
			});
	}
	function cancelaItem(){
		dialog = bootbox.dialog({
			message: '<h4>Informe a senha gerencial para cancelar item:</h4><hr><div class="row"><div class="col-sm-2"></div><div class="col-sm-4"><input type="password" class="form-control" id="snGerencial" name="snGerencial" onKeyDown="TABEnter(1,0)"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="verificaSenha()"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
		
	}
	function att_valorCaixa(){

		$.post('acaoCaixa.php',{acao:'tela_attCx'}, function(resp){
			var retorno = $.trim(resp);
			$("#atualizaValorCX").html(retorno);
		});
		dialog = bootbox.dialog({
			message: '<div id="atualizaValorCX"></div>',
		});
		$("#sitCx").val('5');
	}
	function att_valor(){
		var senha = $("#snGerencial").val();
		var n_valor = $("#valor_caixa").val();
		var tipo = $("#tipo_lancamentoCX").val();
		var motivo = $("#tipo_motivo").val();
		var obs = $("#obs_cx").val();

		//alert(valor);
		$.post('acaoCaixa.php',{acao:'senhaDesconto'}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
			
			if(senha != ''){
				if(senha == senhaCorreta){
					$.post('acaoCaixa.php',{acao:'att_valor_caixa', tipo:tipo, n_valor:n_valor , motivo:motivo , obs:obs}, function(resp){
						var retorno = $.trim(resp);
						$("#atualizaValorCX").html(retorno);
					});	
				}else{
					$("#msgS").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS").html("A Senha não pode ser vazia!");
			}
		});
		
	}
	function deletItem(id){
		$.post('acaoCaixa.php',{id:id , acao:'delItemCompra',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			$.post('acaoCaixa.php',{acao:'descontoItematualiza',  id:id,ref: $("#_ref").val()}, function(resp){
								var retorno = $.trim(resp);
			$('#listaItem').html(retorno);	
								//alert(retorno);
								//location.reload();
								var TotalVenda = $("#totalVenda").val();
									$("#tt").html('R$ '+ TotalVenda);
								});
		});
	}
	function verificaSenha(){
		var senha = $("#snGerencial").val();
		$.post('acaoCaixa.php',{acao:'senhaCancela',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
		
			if(senha != ''){
				if(senha == senhaCorreta){
					$("#msgS").html("&nbsp;");
					$(".delclass").css("opacity","1");
					dialog.modal('hide');
				}else{
					$("#msgS").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS").html("A Senha não pode ser vazia!");
			}
		});
	}
	function cancelaCompra(){
		dialog = bootbox.dialog({
			message: '<h4>Informe a senha gerencial para cancelar Compra:</h4><hr><div class="row"><div class="col-sm-2"></div><div class="col-sm-4"><input type="password" class="form-control" id="snGerencial2" name="snGerencial2" ></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="verificaSenha2()"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS2" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
		
	}


	
	function _CancelarOp() {           
		
		aguarde();
		  var $_keyid = "_PDV00003";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
    
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
         function(result){            
             $('#divPag').html(result);
         });     
		  
	 }
	function verificaSenha2(){
		var senha = $("#snGerencial2").val();
		$.post('acaoCaixa.php',{acao:'senhaCancela',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
		
			if(senha != ''){
				if(senha == senhaCorreta){
					$("#msgS2").html("&nbsp;");
					$.post('acaoCaixa.php',{acao:'cancelaCompra',ref: $("#_ref").val()}, function(resp){
						var retorno = $.trim(resp);
						$('#listaItem').html(retorno);	
								//alert(retorno);
								//location.reload();
								var TotalVenda = $("#totalVenda").val();
									$("#tt").html('R$ '+ TotalVenda);
					});
					
				}else{
					$("#msgS2").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS2").html("A Senha não pode ser vazia!");
			}
		});
	}
	function descotinho(){
	
		$(".desclass").css("opacity","1");
	}

	function descontoItem(id){

		var dialog = dialog = bootbox.dialog({
			message: '<h4>Informe a senha gerencial e o novo valor do item:</h4><hr><div class="row"><div class="col-sm-3"><input type="password" class="form-control" id="snGerencial2" name="snGerencial2" placeholder="Senha" onKeyDown="TABEnter(3,0)"></div><div class="col-sm-3"><input type="text" class="form-control" id="novoValor" name="novoValor" placeholder="Novo valor" onKeyDown="TABEnter(2,'+"'"+id+"'"+')"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="verificaSenhaDesconto('+"'"+id+"'"+')"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS3" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
	}

	function verificaSenhaDesconto(id){
		var senha = $("#snGerencial2").val();
		var valor = $("#novoValor").val();
	
		//alert(valor);
		$.post('acaoCaixa.php',{acao:'senhaDesconto'}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
			
			if(senha != ''){
				if(senha == senhaCorreta){

					$.post('acaoCaixa.php',{acao:'valorItem', id:id,ref: $("#_ref").val()}, function(resp){
					var valorItem = $.trim(resp);
					valorItem = parseFloat(valorItem);
					//valor = parseFloat(valor);
					
					//	if(valor <= valorItem || id == 1){
							$("#msgS3").html("&nbsp;");
						
								$.post('acaoCaixa.php',{acao:'descontoItem', novoValor:valor , id:id,ref: $("#_ref").val()}, function(resp){
								var retorno = $.trim(resp);
							
								//location.reload();
								$.post('acaoCaixa.php',{acao:'descontoItematualiza', novoValor:valor , id:id,ref: $("#_ref").val()}, function(resp){
								var retorno = $.trim(resp);
									$('#listaItem').html(retorno);	
								//alert(retorno);
								//location.reload();
								var TotalVenda = $("#totalVenda").val();
									$("#tt").html('R$ '+ TotalVenda);
								
									bootbox.hideAll();
							
							});
							
							});			
					//	}else{
						//	$("#msgS3").html("Valor não pode ser maior que o valor do produto!"+valorItem);
						//}
					});	
				}else{
					$("#msgS3").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS3").html("A Senha não pode ser vazia!");
			}
		});

	}
	function descontoVenda(){
	
		var dialog = dialog = bootbox.dialog({
			message: '<h4>Informe a senha gerencial e o novo valor para desconto da venda:</h4><hr><div class="row"><div class="col-sm-3"><input type="password" class="form-control" id="snGerencial3" name="snGerencial3" placeholder="Senha"></div><div class="col-sm-2"><select class="form-control" id="tipoDesconto" name="tipoDesconto"><option value="1">%</option><option value="2">R$</option></select></div><div class="col-sm-4"><input type="text" class="form-control" id="novoValor2" name="novoValor2" placeholder="Novo Valor/Porcentagem" onKeyPress="return(moeda(this'+",'.',',',event"+'));"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="verificaSenhaDesconto2()"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS4" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
	}


	function verificaSenhaDesconto2(){
		var senha = $("#snGerencial3").val();
		var valor = $("#novoValor2").val();
		var tipoDesconto = $("#tipoDesconto").val();
		$.post('acaoCaixa.php',{acao:'senhaDesconto'}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
		
			if(senha != ''){
				if(senha == senhaCorreta){
					$("#msgS4").html("&nbsp;");
					$.post('acaoCaixa.php',{acao:'descontoVenda', novoValor:valor,ref: $("#_ref").val(),tipoDesconto:tipoDesconto}, function(resp){
						var retorno = $.trim(resp);
						$('#listaItem').html(retorno);	
								//alert(retorno);
								//location.reload();
								var TotalVenda = $("#totalVenda").val();
									$("#tt").html('R$ '+ TotalVenda);
					});			
				}else{
					$("#msgS4").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS4").html("A Senha não pode ser vazia!");
			}
		});
	}

	function removeDesconto(){
		var dialog = dialog = bootbox.dialog({
			message: '<h4>Informe a senha gerencial para cancelar o desconto da venda:</h4><hr><div class="row"><div class="col-sm-3"><input type="password" class="form-control" id="snGerencial3" name="snGerencial3" placeholder="Senha"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="verificaSenhaDesconto3()"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS4" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
	}
	function verificaSenhaDesconto3(){
		var senha = $("#snGerencial3").val();
		$.post('acaoCaixa.php',{acao:'senhaDesconto'}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
		
			if(senha != ''){
				if(senha == senhaCorreta){
					$("#msgS4").html("&nbsp;");
					$.post('acaoCaixa.php',{acao:'cancelaDesconto'}, function(resp){
						var retorno = $.trim(resp);
						$('#listaItem').html(retorno);	
								//alert(retorno);
								//location.reload();
								var TotalVenda = $("#totalVenda").val();
									$("#tt").html('R$ '+ TotalVenda);
					});			
				}else{
					$("#msgS4").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS4").html("A Senha não pode ser vazia!");
			}
		});
	}	
	
	function reimpressao(){
		var dialog = dialog = bootbox.dialog({
			message: 'Ao continuar você perderá conexão com a venda atual, e iniciará uma nova!<br><h4>Informe a senha gerencial e o numero da venda:</h4><hr><div class="row"><div class="col-sm-3"><input type="password" class="form-control" id="snGerencial4" name="snGerencial4" placeholder="Senha"></div><div class="col-sm-3"><input type="text" class="form-control" id="numVend" name="numVend" placeholder="Numero Venda"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="reimprimeCupom()"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS5" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
	}
	function reimprimeCupom(){
		var senha = $("#snGerencial4").val();
		var numeroVenda = $("#numVend").val();
		$.post('acaoCaixa.php',{acao:'senhaDesconto'}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
		
			if(senha != ''){
				if(senha == senhaCorreta){
					if(numeroVenda != ''){
					$("#msgS5").html("&nbsp;");
					$.post('acaoCaixa.php',{acao:'reimpressaoCupom', numeroVenda:numeroVenda,ref: $("#_ref").val()}, function(resp){
						var retorno = $.trim(resp);
						if(retorno == ""){
							window.open('cpmNaoFiscal.php?ref=<?=$_idfrefGO;?>');
							setTimeout(function(){
								NovaVenda();
							}, 1000);
						}else{
							$("#msgS5").html(retorno);
						}
					});	
					}else{
						$("#msgS5").html("Numero Venda não pode ser vazio!");
					}
				}else{
					$("#msgS5").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS5").html("A Senha não pode ser vazia!");
			}
		});
	}
	
	
	function configuracao(){
		dialog = bootbox.dialog({
			message: '<h4>Informe a senha gerencial para acessar as configurações:</h4><hr><div class="row"><div class="col-sm-2"></div><div class="col-sm-4"><input type="password" class="form-control" id="snGerencial5" name="snGerencial5"></div><div class="col-sm-3"><button type="button" class="btn btn-primary btn-block" onclick="configuracao_2()"><i class="fa fa-check"></i>&nbsp;</button></div></div><div id="msgS" style="color:red; width:68%; text-align:center;">&nbsp;</div>',
		});
		
	}	
	function configuracao_2(){
		var senha = $("#snGerencial5").val();
		$.post('acaoCaixa.php',{acao:'senhaCancela',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			var senhaCorreta = retorno;
		
			if(senha != ''){
				if(senha == senhaCorreta){
					dialog.modal('hide');
					configuracao_3();
					
				}else{
					$("#msgS").html("Senha incorreta! Tente novamente.");
				}
			}else{
				$("#msgS").html("A Senha não pode ser vazia!");
			}
		});
	}
	
	function configuracao_3(){
		 $("#sitCx").val('3');
		 $("#modal_config").modal();
		 
		 $.post('acaoCaixa.php',{acao:'configCaixa',ref: $("#_ref").val()}, function(resp){
			var retorno = $.trim(resp);
			$('#divConf').html(retorno);	
		});	
	}
	/*-------------------------------MASCARAS----------------------------------------*/
		function nm(){
			
		var cpf = $("#cpf_cnpj").val();
		if(cpf != ""){
			$("#dsCpf").html(cpf);
		}else{
			$("#dsCpf").html("Nenhum");
		}
		
		var nome = $("#nomeCompleto").val();
		if(nome != ""){
			$("#dsNome").html(nome);
		}else{
			$("#dsNome").html("Nenhum");
		}
		/*
		var email = $("#email").val();
		if(email != ""){
			$("#dsEmail").html(email);
		}else{
			$("#dsEmail").html("Nenhum");
		}
		
		
				var celular = $("#celular").val();
		if(celular != ""){
			$("#dsCelular").html(celular);
		}else{
			$("#dsCelular").html("(00) 0 0000-000");
		}
		*/
	}
	function mascaraData(val) {
	  var pass = val.value;
	  var expr = /[0123456789]/;

	  for (i = 0; i < pass.length; i++) {
		// charAt -> retorna o caractere posicionado no ?ndice especificado
		var lchar = val.value.charAt(i);
		var nchar = val.value.charAt(i + 1);

		if (i == 0) {
		  // search -> retorna um valor inteiro, indicando a posi??o do inicio da primeira
		  // ocorr?ncia de expReg dentro de instStr. Se nenhuma ocorrencia for encontrada o m?todo retornara -1
		  // instStr.search(expReg);
		  if ((lchar.search(expr) != 0) || (lchar > 3)) {
			val.value = "";
		  }

		} else if (i == 1) {

		  if (lchar.search(expr) != 0) {
			// substring(indice1,indice2)
			// indice1, indice2 -> será usado para delimitar a string
			var tst1 = val.value.substring(0, (i));
			val.value = tst1;
			continue;
		  }

		  if ((nchar != '/') && (nchar != '')) {
			var tst1 = val.value.substring(0, (i) + 1);

			if (nchar.search(expr) != 0)
			  var tst2 = val.value.substring(i + 2, pass.length);
			else
			  var tst2 = val.value.substring(i + 1, pass.length);

			val.value = tst1 + '/' + tst2;
		  }

		} else if (i == 4) {

		  if (lchar.search(expr) != 0) {
			var tst1 = val.value.substring(0, (i));
			val.value = tst1;
			continue;
		  }

		  if ((nchar != '/') && (nchar != '')) {
			var tst1 = val.value.substring(0, (i) + 1);

			if (nchar.search(expr) != 0)
			  var tst2 = val.value.substring(i + 2, pass.length);
			else
			  var tst2 = val.value.substring(i + 1, pass.length);

			val.value = tst1 + '/' + tst2;
		  }
		}

		if (i >= 6) {
		  if (lchar.search(expr) != 0) {
			var tst1 = val.value.substring(0, (i));
			val.value = tst1;
		  }
		}
	  }

	  if (pass.length > 10)
		val.value = val.value.substring(0, 10);
	  return true;
	}

	
		
	//FORMATA MOEDA FINALIZA PAGAMENTO
	function moeda(a, e, r, t) {
		let n = ""
		  , h = j = 0
		  , u = tamanho2 = 0
		  , l = ajd2 = ""
		  , o = window.Event ? t.which : t.keyCode;
		if (13 == o || 8 == o)
			return !0;
		if (n = String.fromCharCode(o),
		-1 == "0123456789".indexOf(n))
			return !1;
		for (u = a.value.length,
		h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
			;
		for (l = ""; h < u; h++)
			-1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
		if (l += n,
		0 == (u = l.length) && (a.value = ""),
		1 == u && (a.value = "0" + r + "0" + l),
		2 == u && (a.value = "0" + r + l),
		u > 2) {
			for (ajd2 = "",
			j = 0,
			h = u - 3; h >= 0; h--)
				3 == j && (ajd2 += e,
				j = 0),
				ajd2 += l.charAt(h),
				j++;
			for (a.value = "",
			tamanho2 = ajd2.length,
			h = tamanho2 - 1; h >= 0; h--)
				a.value += ajd2.charAt(h);
			a.value += r + l.substr(u - 2, u)
		}
		return !1
	}
	
	//SOMA VALOR A PAGAR  - TELA FINALIZA PAGAMENTO
	function soma() { 
		
		var totalForma = $("#totalForma").val();
		$("#fp").css("background-color","#00A8E6");
		
		var i = 0;
		var valorTotal = 0;
		var valorFrete = 0;
		var x = $("#totalVenda").val();
		var frete = $("#frete").val();
		if(frete == ""){
			frete = 0;
		}else{
			valorFrete = parseFloat(frete.replace('.','').replace(',','.'));
		}
		
		for (i = 1; i <= totalForma; i++) {
			var nomediv = "#vlrPag" + i;
			var f = $(nomediv).val(); 
			if(f != ""){
				valorTotal += parseFloat(f.replace('.','').replace(',','.'));
			}
		}	
	
	
	  totalVenda = parseFloat(x.replace('.','').replace(',','.'));

	  
	  var troco = valorTotal - (totalVenda+valorFrete);
	  if(troco < 0){
		  troco = 0;
	  }
	  $("#trocoC").val(troco);
	  var totalTroco = troco.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
	  $("#troco").html(totalTroco);
	
	  var faltaPagar2 = (valorFrete+totalVenda) - valorTotal;
	  
	  if(faltaPagar2 <= 0){
		faltaPagar2 = 0;
		
		$("#fp").css("background-color","#5CB85C");
	  }
	  var totalResult = faltaPagar2.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
	  $("#pago").html(totalResult);
	} 
	
	//APENAS NUMEROS
	
	function Onlynumbers(e)
	{
		var tecla=new Number();
		if(window.event) {
			tecla = e.keyCode;
		}
		else if(e.which) {
			tecla = e.which;
		}
		else {
			return true;
		}
		if((tecla >= "97") && (tecla <= "122")){
			return false;
		}
	}
	
	
	function mascaraTexto(evento, tipo){
	var mascara = ''
  

	 if (tipo == 6) { 
		mascara = "99/99/9999";
		//if (document.form1.datavencimento.value.length==10) {
		// document.form1.numeroparcela.focus();
		//}
	  }
	  
	 if (tipo == 4) { 
	var    mascara = "99.999-999";
	if (document.getElementById('cep').length==10) {
	 document.getElementById('endereco_numero').focus();
}

}
	  
 
  if (tipo ==  31) { 
	mascara = "99.999-99";
	
	
 } 
   
    var campo, valor, i, tam, caracter;  
    if (document.all) // Internet Explorer  
       campo = evento.srcElement;  
    else // Nestcape, Mozzila  
        campo= evento.target;  
	    valor = campo.value;  
	    tam = valor.length;  
	    for(i=0;i<mascara.length;i++){  
	       caracter = mascara.charAt(i);  
       if(caracter!="9")   
          if(i<tam & caracter!=valor.charAt(i))  
             campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);  
	    }  
 } 
	
		function tel(input){
		
		var div = '#'+input;
		var valor = $(div).val();
		
		var valor = valor.replace(/[^\d]+/g,'');
		var tamanho = valor.length;

		
		if(tamanho > 10){
			
			p1 = valor.substr(0,2);
			p2 = valor.substr(2,1);
			p3 = valor.substr(3,4);
			p4 = valor.substr(7,4);
			
			if(p1 != ""){
				if(p2 != ""){					
					if(p3 != ""){
						if(p4 != ""){
							$(div).val('('+p1+')' + ' ' + p2 + ' ' + p3 + '-' + p4);	
						}else{
							$(div).val('('+p1+')' + ' ' + p2 + ' ' + p3);
						}	
					}else{
						$(div).val('('+p1+')' + ' ' + p2);
					}
				}						
			}else{
				$(div).val("");
			}	
		
		}else{
			p1 = valor.substr(0,2);
			p2 = valor.substr(2,4);
			p3 = valor.substr(6,4);	
			
			if(p1 != ""){
				if(p2 != ""){					
					if(p3 != ""){
						$(div).val('('+p1+')' + ' ' + p2 + '-' + p3);	
					}else{
						$(div).val('('+p1+')' + ' ' + p2);
					}
				}					
			}else{
				$(div).val("");
			}		
		}			
	}	
	function maskCpfCnpj(name){
		var div = "#" + name;
		var valor = $(div).val();
		
		var valor = valor.replace(/[^\d]+/g,'');
		var tamanho = valor.length;
		
		if(tamanho > 10){
			p1 = valor.substr(0,2);
			p2 = valor.substr(2,3);
			p3 = valor.substr(5,3);
			p4 = valor.substr(8,4);
			p5 = valor.substr(12,2);
			
			$(div).val(p1 + '.' + p2 + '.' + p3 + '/' + p4 + '-' + p5);
			
		}else{
			p1 = valor.substr(0,3);
			p2 = valor.substr(3,3);
			p3 = valor.substr(6,3);
			p4 = valor.substr(9,2);
			
			if(p1 != ""){
				if(p2 != ""){					
					if(p3 != ""){
						if(p4 != ""){
							$(div).val(p1 + '.' + p2 + '.' + p3 + '-' + p4);
						}else{
							$(div).val(p1 + '.' + p2 + '.' + p3);
						}	
					}else{
						$(div).val(p1 + '.' + p2);
					}
				}	
			}else{
				$(div).val("");
			}
		}
	}

	/*$("#qtde").keyup(function(){
		var v = this.value,
			integer = v.split(',')[0];


		v = v.replace(/\D/, "");
		v = v.replace(/^[0]+/, "");

		if(v.length <= 3 || !integer)
		{
			if(v.length === 1) v = '0,00' + v;
			if(v.length === 2) v = '0,0' + v;
			if(v.length === 3) v = '0,' + v;
		} else {
			v = v.replace(/^(\d{1,})(\d{3})$/, "$1,$2");
		}

		this.value = v;
	});*/

	
</script>