<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;


$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

function diasDatas($data_inicial,$data_final) {
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24)); 
    return $dias;
}


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];; //codigo login

$usuariologado =  $_SESSION["APELIDO"]; //nome

		

$dtaberturaSelAgenda = $_parametros['dtaberturaSelAgenda'];
if($_acao == 0 ) { 
	echo '<span class="badge badge-purple m-l-0"> Análise </span>';

}
if($_acao == 1 ) { 
	echo '<span class="badge badge-success m-l-0"> Pedidos </span>';
}



if($_acao == 2 ) {   //LISTATAGEM ANALISE
	
	$_npedido= $_parametros['_npedido'];
	$_codpeca= $_parametros['_codpeca'];
	$dataini= $_parametros['_dataIni'];
	$datafim = $_parametros['_dataFim'];

	$_dataAT = $_parametros['_dataAT'];
	$_StatusItem = $_parametros['_StatusItem'];
	$_StatusPedido = $_parametros['_StatusPedido'];

	$dia       = date('d'); 
	$mes       = date('m'); 
	$ano       = date('Y'); 



	if($datafim == "" ) {
			$dataini = "2000-01-01" ;   
			$datafim = $ano."-".$mes."-".$dia ;   		
	}

if(trim($_codpeca) != "") { 
	$_filtro = " AND pi_codfabricante = '$_codpeca'";
}

if(trim($_StatusPedido) != "") { 
	$_filtro = $_filtro ." AND ped_sit = '$_StatusPedido'";
}

if(trim($_StatusItem) != "") { 
	$_filtro = $_filtro ." AND pi_situacao = '$_StatusItem'";
}

if(trim($_dataAT) != "") { 
	$_filtro = $_filtro ." AND pi_dtatualizado >= '$_dataAT 00:00:00' and pi_dtatualizado <= '$_dataAT 23:59:59'   ";
}



$_SQL = "SELECT * FROM ". $_SESSION['BASE'] .".pedidoitens 
LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia  ON g_id  = pi_tipgarantia	
LEFT JOIN  ". $_SESSION['BASE'] .".tipopedido  ON pi_tipodocumento	  = Cod_Tipo	
WHERE pi_situacao = '1' and  pi_data BETWEEN 	'$dataini' and  ' $datafim' $_filtro ORDER BY pi_id DESC";


	$statement = $pdo->query("$_SQL");
    $retorno = $statement->fetchAll();
    ?>
	<div style="margin-bottom: 5px;">
		<button class="btn btn-success waves-effect waves-light btn-xs" data-toggle="modal" data-target="#modalincluir" onclick="_listaAdd()"> <i class="fa fa-plus-square-o"></i> Adicionar</button>
		<button type="button" class="btn btn-default waves-effect waves-light  btn-xs" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa  fa-sliders"></i></span>Filtros</button>
		
	</div>
    <table id="datatable-fixed-col"  class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th  data-radio="true"><!--<input name="btSelectItem" id="btSelectItem"  type="checkbox" onclick="_selitem()">--></th>
                <th class="text-center">Código</th>
                <th class="text-center">Descrição</th>
                <th class="text-center">Qtde Solic.</th>
				<th class="text-center">Estoque</th>
				<th class="text-center" >Tipo</th>
				<th class="text-center">Nº Doc.</th>
				<th>O.S Fabr.</th>
				<th>Produto</th>
				<th>Modelo Com.</th>
				<th>Tensão</th>
				<th class="text-center">Obs</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst) {
			$_estoque = 0;
			$_garantia  = $rst["g_sigla"];
			if($_garantia == "") {
				$_garantia = "FG";
				$_garantiaCOR = 'primary';
			}else{
				$_garantiaCOR  = $rst["g_cor"];
			}
			
			$i++;

			if($rst["pi_selped"] == "1"){
				$_chk = 'checked="checked"';
			}else{
				$_chk = "";
			}//pi_tipodocumento
			
        ?>
		
            <tr class="gradeX">
				<td class="text-center"><input  name="bt_item<?=$i;?>" id="bt_item<?=$i;?>"   <?=$_chk;?> value="<?=$rst["pi_id"]?>" type="checkbox" onclick="_SelPedido('bt_item<?=$i;?>')"></td>
                <td class="text-center"><?=($rst["pi_codfabricante"])?></td>
                <td class="text-center"><?=($rst["pi_descitem"])?></td>
                <td class="text-center"><?=($rst["pi_qtde"])?></td>
				<td><?=($_estoque)?></td>
				<td class="text-center"><span class="label label-<?=($rst["TPCor"])?> " style="font-size:12px; "><?=($rst["TPDescricao"])?></span></td>
				<td class="text-center"><?=($rst["pi_documento"])?> <span class="badge badge-xs badge-<?=$_garantiaCOR;?>"><?=($_garantia)?></td>
				<td><?=($rst["pi_svo"])?></td>
				<td><?=($rst["pi_produto"])?></td>
				<td><?=($rst["pi_modelo"])?></td>
				<td ><?=($rst["pi_tensao"])?></td>
				<td><?=($rst["pi_observacao"])?></td>				         
                <td class="actions text-center">
                  
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst["pi_id"];?>)"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table> 
	<button class="btn btn-success waves-effect waves-light " onclick="_GerarPedido()"> <i class="fa fa-plus-square-o"></i> Gerar Pedido </button>
	<?php

}

if($_acao == 3 ) {   //LISTAGEM PEDIDOS
	?>
	<div style="margin-bottom: 5px;">
		
		<button type="button" class="btn btn-default waves-effect waves-light  btn-xs" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa  fa-sliders"></i></span>Filtros</button>
		<button class="btn btn-inverse waves-effect waves-light  btn-xs" onclick="_print()"><i class="fa fa-print"></i></button>
	
	</div>
	<?php

}

if($_acao == 4 ) {   //LISTAGEM ADICIONADO

	$_sql = "SELECT pi_id,pi_idpedido,pi_codfabricante,pi_qtde,pi_documento,pi_descitem,pi_vlrped
	FROM " . $_SESSION['BASE'] . ".pedidoitens                          
	LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON pi_coditem = Codigo_Fornecedor
	WHERE pi_selped = '0'" ;

			$consulta = $pdo->query("$_sql");
			$retorno = $consulta->fetchAll();

			?>
			
			<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
				<thead>
					<tr>
						<th class="text-center" >Código</th>
						<th class="text-center " style="max-width: 100px ; " >Descrição</th>											
						<th class="text-center" >Qtde</th>
						<th class="text-center">OS</th>
						<th class="text-center " >Valor</th>
						<th class="text-center" >Total</th>
						<th class='text-center'></th>
					</tr>
				</thead>
			<tbody>
			<?php
			foreach ($retorno as $row) {
			
			$_CAMPO = "CODIGO_FABRICANTE";
		
			?>
			<tr class="gradeX">
			<td class="text-center"><?=$row["pi_codfabricante"]; ?></td>
			<td class="text-center"><?=$row["pi_descitem"];?></td>
			<td class="text-center"><?=$row["pi_qtde"] ?></td>
			<td class="text-center"><?= $row["pi_documento"] ?></td>
			<td class="text-center"><?= number_format($row["pi_vlrped"], 2, ',', '.') ?></td>
			<td class="text-center"><?= number_format($row["pi_vlrped"]*$row["pi_qtde"], 2, ',', '.') ?></td>

			<td class="actions text-center">
			<?php if ($row["pi_idpedido"] == "0" ) { ?>
				<a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row['pi_id'] ?>)"><i class="fa fa-trash-o"></i></a>
			<?php  } ?>
			</td>
			</tr>
			<?php
			}
			?>
			</tbody>
			</table>


<?PHP 
			}	
			
if ($_acao == 5) {   //busca peça para lançamento
				$_campo = "";
			
				$consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
									" . $_SESSION['BASE'] . ".itemestoquealmox.Qtde_Disponivel AS tot_item 
									from " . $_SESSION['BASE'] . ".itemestoque 
									LEFT JOIN " . $_SESSION['BASE'] . ".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
									WHERE CODIGO_FABRICANTE = '" . $_parametros['codbarra-mov'] . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '1'  ";
				$consulta = $pdo->query("$consulta_produto");
				$retorno = $consulta->fetchAll();
				if ($consulta->rowCount() > 0) {
					print_r(json_encode($retorno));
				}else{
					//VERIFICAR BUSCA POR CODIGO DE BARRAS E SKU
					$_refsku  = str_pad(trim($_parametros['codbarra-mov']), 18, '0', STR_PAD_LEFT);
					$consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
						itemestoquealmox.Qtde_Disponivel AS tot_item 
						from " . $_SESSION['BASE'] . ".itemestoque 
						LEFT JOIN " . $_SESSION['BASE'] . ".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
						WHERE Codigo_Barra = '" . $_parametros['codbarra-mov'] . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '1' OR
						Codigo_Referencia_Fornec = '" . $_refsku . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '1'   ";
						$consulta = $pdo->query("$consulta_produto");
						$retorno = $consulta->fetchAll();
						if ($consulta->rowCount() > 0) {
							print_r(json_encode($retorno));
						}else{
							$consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
												'0' AS tot_item 
												from " . $_SESSION['BASE'] . ".itemestoque 
												LEFT JOIN " . $_SESSION['BASE'] . ".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
												WHERE CODIGO_FORNECEDOR = '" . $_parametros['codbarra-mov'] . "' 
												AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '1'  ";
							$consulta = $pdo->query("$consulta_produto");
							$retorno = $consulta->fetchAll();
								if ($consulta->rowCount() > 0) {
										print_r(json_encode($retorno));
								}
						}
				}
			
}

if ($_acao == 6) { // adicionar novo
	$_tipodocumento  = 1; //manual / os / venda	
	$_pi_situacao = 1;


	if(trim($_parametros["_codpesq"]) == ""){
		echo '<div class="alert alert-danger " style="margin-top: 0px;" >
                    <h5>Código informado inválido !!</h5>
			  </div>';

	}else{
		$pi_tipgarantia = 0;

	$statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".pedidoitens (pi_data,pi_datahora,pi_user,pi_coditem,pi_qtde,pi_documento,pi_tipodocumento,pi_tipgarantia,pi_marca,pi_produto,pi_modelo,pi_tensao,pi_situacao,pi_descitem,pi_codfabricante,pi_vlrped) VALUES(CURRENT_DATE(),'$data',?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		//pi_data,pi_datahora,pi_user,pi_coditem,pi_qtde,pi_documento,pi_tipodocumento,pi_tipgarantia,pi_marca,pi_produto,pi_modelo,pi_tensao,pi_situacao
		$statement->bindParam(1, $usuario);		
		$statement->bindParam(2, $_parametros["_codpesq"]);	
		$statement->bindParam(3, $_parametros["qnt-mov"]);
		$statement->bindParam(4, $_parametros["OS"]);
		$statement->bindParam(5, $_tipodocumento);
		$statement->bindParam(6, $_pi_tipgarantia);
		$statement->bindParam(7, $_parametros["_marca"]);
		$statement->bindParam(8, $_parametros["_produto"]);
		$statement->bindParam(9, $_parametros["_modelo"]);
		$statement->bindParam(10, $_parametros["_tensao"]);	
		$statement->bindParam(11, $_pi_situacao);	
		$statement->bindParam(12, $_parametros["_desc"]);	
		$statement->bindParam(13, $_parametros["codbarra-mov"]);	
		$statement->bindParam(14, $_parametros["_vlr"]);	
		$statement->execute();
	}

}

if ($_acao == 7) { // excluir novo
	$_sql = "SELECT pi_id,piSeq_itemOS
	FROM " . $_SESSION['BASE'] . ".pedidoitens                          
	WHERE pi_id = '".$_parametros["_idsel"]."'" ;
		$consulta = $pdo->query("$_sql");
		$retorno = $consulta->fetchAll();
			foreach ($retorno as $row) {
				$_idpeca = $row['piSeq_itemOS'];
			}

	$statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".pedidoitens WHERE pi_id = '".$_parametros["_idsel"]."'");
	$statement->execute();

	//ATUALIZAR ITEM CHAMADA PECA
	$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca SET 	Pedido_Fabricante = 'N',sitpeca = '1' WHERE  Seq_item  = '".$_idpeca."'	");
	$statement->execute();

}
if ($_acao == 8) { // buscar OS
	?>
		<div class="modal-body">
			<div class="row">
			
				<div class="col-sm-4 " >    
					<input type="number" id="busca-OS" name="busca-OS" class="form-control input-sm" placeholder="Número da O.S">
				</div>
				<div class="col-sm-2" >
					<button type="button" class="btn waves-effect waves-light btn-warning input-sm" onclick="_buscaOS()"><i class="fa  fa-search"></i></button>
					<button type="button" class="btn waves-effect waves-light btn-inverse input-sm" onclick="_listaAdd()"><i class="fa fa-times"></i></button>
				</div>  
				<div class="col-sm-2"  id="_retOS">
					-
				</div> 

				
			</div>
		<div class="row" id="retorno-pecasos" >
			<table id="datatable-responsive-produtos-buscaos" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%" style="margin-top:10px;">
				<thead>
					<tr>
						<th>Codigo </th>
						<th >Descrição</th>   
						<th style="width: 20px; text-align: center;">Reservar</th>                      
						<th>Qtde</th> 
						<th style="width: 10px; text-align: center;">Ação</th>	
						<th>-</th>   			
					</tr>
				</thead>                                                            
				<tbody id="tbody_itemos">    
																
				</tbody>
			</table>
		</div>

		</div>

	<?php

}

if ($_acao == 9) { // buscar OS

	
	if($_parametros['busca-OS'] == "") { 
		$msg = "Informe número da O.S";

	}


	if($msg != "") {  ?>

					<tr class="gradeX">
													
													<td colspan="5"><div class="alert alert-danger alert-dismissable">                      
						<?=$msg ;?>
						</div></td>			
													

	<?php exit(); }



	
													$sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,Pedido_Fabricante
													from " . $_SESSION['BASE'] . ".chamadapeca 
													left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 																									
													where TIPO_LANCAMENTO = 0 and	Numero_OS  = '".$_parametros['busca-OS']."'  and Numero_OS > '0'  order by  Minha_Descricao ASC";
													$consulta = $pdo->query("$sql");
													$retorno = $consulta->fetchAll();
													foreach ($retorno as $row) {
														$_pf = $row['Pedido_Fabricante']; // R N A
														?>
														<tr class="gradeX">										
															<td><?=$row["CODIGO_FABRICANTE"]?></td>			
															<td><?=$row["Minha_Descricao"]?></td>                                                  
															<td style="width: 20px; text-align: center;">
															<?php if($_pf == 'N' ){ ?>
																		<a href="javascript:void(0);" class="on-default edit-row" title="Reservar" onclick="_separarOS('<?=$_parametros['busca-OS'];?>|<?=$row["Seq_item"];?>')" style="margin:0px;"><i class="fa  fa-unlink fa-2x"></i></a>
															<?php }else{ 
																	if($_pf == 'R' ){ 
																		echo '<span class="badge  badge-warning">R</span>';
																	}
															} ?>
																
														</td>                                                  
															
															<td class="text-center"><?= $row["Qtde_peca"] ?></td>	
															<td class="text-center">
															<?php if($_pf == 'N' ){ ?>
																<a href="javascript:void(0);" class="on-default edit-row" title="Adicionar Direto" onclick="_incluirOS('<?=$_parametros['busca-OS'];?>|<?=$row["Seq_item"];?>')" style="margin-right: 0px;"><i class="fa   fa-plus-square fa-2x"></i></a>
																<?php }else{ 
																	if($_pf == 'A' ){ 
																		echo '<i class="fa fa-hourglass-2"></i>';
																	}
															} 
															if($_pf == 'R' ){  ?>
																	<a href="#" class="on-default remove-row"  onclick="_separarExluirOS('<?=$_parametros['busca-OS'];?>|<?=$row["Seq_item"];?>')"><i class="fa fa-trash-o"></i></a>
															
															<?php }?>	


															</td>
															<td><?php if($_pf == 'S' ){
																	echo '<i class="fa  fa-truck"></i>';
															}?></td>	
														</tr>
													
														<?php
													} ?>
												

<?php 

exit();
}

if ($_acao == 10) { // adicionar itemOS
	$_tipodocumento  = 2; //manual / os / venda	
	$_pi_situacao = 1;
	$_descricao = explode("|",$_parametros["_codpesquisaOS"]);

	if(trim($_parametros["_codpesquisaOS"]) == ""){
		echo '<code>Ops !!! Não foi possível Adicionar !!</code>';

	}else{

	

		$sql="Select marca,Modelo,descricao,VOLTAGEM,DATA_CHAMADA,CODIGO_CONSUMIDOR,NUM_ORDEM_SERVICO,GARANTIA
		FROM " . $_SESSION['BASE'] . ".chamada 																							
		where  CODIGO_CHAMADA  = '".$_descricao[0]."'  LIMIT 1";
		$consulta = $pdo->query("$sql");
		$retorno = $consulta->fetchAll();
		foreach ($retorno as $row) {
			$produto = $row["descricao"];
			$modelo = $row["Modelo"];
			$marca = $row["marca"];
			$tensao = $row["VOLTAGEM"];
			$_dtos = $row["DATA_CHAMADA"];
			$_icliente =  $row["CODIGO_CONSUMIDOR"];
			$_svo = $row["NUM_ORDEM_SERVICO"];//pi_svo
			$pi_tipgarantia =  $row["GARANTIA"];
		}

		

		$sql="Select Numero_OS,CODIGO_FABRICANTE,CODIGO_FORNECEDOR,Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,PRECO_CUSTO,nPedido
		from " . $_SESSION['BASE'] . ".chamadapeca 
		left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 																									
		where  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."' ";
	
		$consulta = $pdo->query("$sql");
		$retorno = $consulta->fetchAll();
		foreach ($retorno as $row) {
			
			$statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".pedidoitens (pi_data,pi_datahora,pi_user,pi_coditem,pi_qtde,pi_documento,pi_tipodocumento,pi_tipgarantia,pi_marca,pi_produto,pi_modelo,pi_tensao,pi_situacao,pi_descitem,pi_codfabricante,pi_vlrped,pi_dtOS,piSeq_itemOS,pi_idcliente,pi_svo) VALUES(CURRENT_DATE(),'$data ',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");			
			$statement->bindParam(1, $usuario);		
			$statement->bindParam(2, $row["CODIGO_FORNECEDOR"]);	
			$statement->bindParam(3, $row["Qtde_peca"]);
			$statement->bindParam(4, $row["Numero_OS"]);
			$statement->bindParam(5, $_tipodocumento);
			$statement->bindParam(6, $pi_tipgarantia);
			$statement->bindParam(7, $marca);
			$statement->bindParam(8, $produto);
			$statement->bindParam(9, $modelo);
			$statement->bindParam(10, $tensao);	
			$statement->bindParam(11, $_pi_situacao);	
			$statement->bindParam(12, $row["Minha_Descricao"]);	
			$statement->bindParam(13, $row["CODIGO_FABRICANTE"]);	
			$statement->bindParam(14, $row["PRECO_CUSTO"]);	
			$statement->bindParam(15, $_dtos);
			$statement->bindParam(16, $_descricao[1]);
			$statement->bindParam(17, $_icliente);
			$statement->bindParam(18, $_svo);
			
			
			
			$statement->execute();

		
			$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca SET 	Pedido_Fabricante = 'A' WHERE  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."'");
			$statement->execute();

		}
	}
	exit();

}

if ($_acao == 101) { // RESERVAR itemOS
	$_tipodocumento  = 2; //manual / os / venda	
	$_pi_situacao = 1;
	$_descricao = explode("|",$_parametros["_codpesquisaOS"]);
	
	$sql="Select Numero_OS,CODIGO_FABRICANTE,CODIGO_FORNECEDOR,Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,PRECO_CUSTO,nPedido
	from " . $_SESSION['BASE'] . ".chamadapeca 
	left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 																									
	where  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."' limit 1 ";

	$consulta = $pdo->query("$sql");
	$retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {
			
			$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca
			 SET 	Pedido_Fabricante = 'R',sitpeca = '4', reserva_solicitacao = 1, reserva = '".$row["Qtde_peca"]."' 
			 WHERE  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."'");
			$statement->execute();

		

			 $sqlu="Update  " . $_SESSION['BASE'] . ".itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica + ".$row["Qtde_peca"]." 
						 where CODIGO_FORNECEDOR  = '" . $row["CODIGO_FORNECEDOR"] . "'	" ;	
						 $statement = $pdo->prepare("$sqlu");	
						 $statement->execute();
	}
	exit();

}


if ($_acao == 102) { // EXCLUIR RESERVAR itemOS
	$_tipodocumento  = 2; //manual / os / venda	
	$_pi_situacao = 1;
	$_descricao = explode("|",$_parametros["_codpesquisaOS"]);
	
	$sql="Select Numero_OS,CODIGO_FABRICANTE,CODIGO_FORNECEDOR,Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,PRECO_CUSTO,nPedido
	from " . $_SESSION['BASE'] . ".chamadapeca 
	left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 																									
	where  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."' limit 1 ";

	$consulta = $pdo->query("$sql");
	$retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {
			
			$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca
			 SET 	Pedido_Fabricante = 'N',sitpeca = '1', reserva_solicitacao = 0, reserva = '0' 
			 WHERE  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."'");
			$statement->execute();

		

			 $sqlu="Update  " . $_SESSION['BASE'] . ".itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica - ".$row["Qtde_peca"]." 
						 where CODIGO_FORNECEDOR  = '" . $row["CODIGO_FORNECEDOR"] . "'	" ;	
						 $statement = $pdo->prepare("$sqlu");	
						 $statement->execute();
	}
	exit();

}

if ($_acao == 11) { // sel pedido pedido
	
	$_ret = explode("|",$_parametros['_selchaveitem']);
	if($_ret[1] == "0") {
		$_cheked = 1;
	}else{
		$_cheked = 0;
	}
	
	$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".pedidoitens SET  pi_selped = '".$_cheked."'	 WHERE pi_id = '".$_ret[0]."' ");
	$statement->execute();

	exit();

}

if ($_acao == 12) { // gerar pedido
	$_sql = "SELECT pi_codfabricante,pi_descitem,sum(pi_qtde) as QTDE,pi_documento FROM ". $_SESSION['BASE'] .".pedidoitens 	
	WHERE pi_situacao = '1' and 	pi_selped = '1' GROUP BY pi_codfabricante,pi_descitem,pi_documento ORDER BY pi_id ASC";	
	$consulta = $pdo->query("$_sql ");
	$retorno = $consulta->fetchAll();
	
    ?>
	 						 <div class="row">                            
                               
                                <div class="col-md-3">
									<label for="field-1" class="control-label">Nº Pedido</label>
										<div class="form-group">
											<input type="text" class="form-control input-sm" id="_nPedido" name="_nPedido">
										</div>
                                </div> 
								<div class="col-md-3">
										<label for="field-1" class="control-label">Data Pedido</label>
                                    <div class="form-group">
                                        <input type="date" class="form-control input-sm" id="_dtpedido" name="_dtpedido">
                                    </div>
                                </div>   
								<div class="col-md-6">
									<div class="form-group m-r-10">
										<label for="nf-fornecedor">Fornecedor: </label>
										<select class="form-control input-sm"   name="_fornPedido" id="_fornPedido">
										<?php
										$statement2 = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante  where for_Tipo = 1 ORDER BY nome");
										$retorno2 = $statement2->fetchAll();
										?>
											<option value="">Selecione</option>
										<?php
										foreach ($retorno2 as $row2) {
										?>
											<option value="<?=$row2["CODIGO_FABRICANTE"]?>"><?=$row2["NOME"]?></option>
										<?php
										}
										?>
										</select>
									</div>
                                </div>  
								
                            </div>  

							
							<div class="row">  
                                <div class="col-md-12">
										<label for="field-1" class="control-label">Obs. Pedido</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" id="_obsPedido" name="_obsPedido">
                                    </div>
                                </div>                               
                            </div>  
							<?php
							if ($consulta->rowCount() == 0) {
									echo '<div class="alert alert-warning" style="margin-top: 0px;" >
									<h5>Não existe item selecionado !!</h5>
							</div>';
								}else{ ?>
							<div class="row">                            
                                <div class="col-md-12">    
										<table id="datatable-fixed-col"  class="table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
												<tr>
													
													<th><div style="text-align: center;">Código</div></th>
													<th>Descrição</th>
													<th><div style="text-align: center;">Qtde</div> </th>
													<th><div style="text-align: center;">Nº Documento</div></th>
													
												</tr>
											</thead>
											<tbody>
											<?php
											foreach ($retorno as $rst) {
												
												$i++;

												
												
											?>
											
												<tr class="gradeX">				
													<td><div style="text-align: center;"><?=($rst["pi_codfabricante"])?></div></td>
													<td><?=($rst["pi_descitem"])?></td>
													<td><div style="text-align: center;"><?=($rst["QTDE"])?></div></td>				
													<td><div style="text-align: center;"><?=($rst["pi_documento"])?></div></td>			
												</tr>
											<?php
											}
											?>
											</tbody>
										</table> 
								</div>
								</div>
								<div class="modal-footer" id="_retconf" style="text-align: center;">
                             <button class="btn btn-success waves-effect waves-light " type="button" onclick="_ConfGerarPedido()"> <i class="fa fa-plus-square-o"></i> Confirmar Pedido </button>
                             <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                            
                        
                        </div>
<?php
}
	exit();

}

if ($_acao == 13) { // gerar pedido
	$_valida = 0;

	if(trim($_parametros['_nPedido']) == ""){
		echo '<div class="alert alert-danger" style="margin-top: 0px;" >
					<h5>Informe o número do pedido !!</h5>
			  </div>';
			  $_valida = 1;
	}
	if(trim($_parametros['_dtpedido']) == ""){
		echo '<div class="alert alert-danger" style="margin-top: 0px;" >
					<h5>Informe o data do pedido gerado !!</h5>
			  </div>';
			  $_valida = 1;
	}
	if(trim($_parametros['_fornPedido']) == ""){
		echo '<div class="alert alert-danger" style="margin-top: 0px;" >
					<h5>Selecione o Fornecedor !!</h5>
			  </div>';
			  $_valida = 1;
	}

	


	if($_valida == 0) {
		//atualiza dados
		$SIT = 1;
		//pedido
		$_sql = "INSERT INTO " . $_SESSION['BASE'] . ".pedido (ped_numero,ped_data,ped_fabricante,ped_sit,ped_obs) VALUE (?,?,?,?,?)";
		$stm = $pdo->prepare($_sql);
		$stm->bindParam(1, $_parametros['_nPedido'], \PDO::PARAM_STR);
		$stm->bindParam(2, $_parametros['_dtpedido'], \PDO::PARAM_STR);
		$stm->bindParam(3,  $_parametros['_fornPedido'], \PDO::PARAM_STR);
		$stm->bindParam(4, $SIT, \PDO::PARAM_STR);	
		$stm->bindParam(5, $_parametros['_obsPedido'], \PDO::PARAM_STR);		
		$stm->execute();
		$id = $pdo->lastInsertId();

		$_dtpedido = explode("-",$_parametros['_dtpedido']);
		$_dtpedido = $_dtpedido[2]."/".$_dtpedido[1]."/".$_dtpedido[0];

		$_sql = "SELECT pi_id,pi_documento,pi_idcliente,pi_tipodocumento,piSeq_itemOS FROM ". $_SESSION['BASE'] .".pedidoitens 	WHERE pi_situacao = '1' and 	pi_selped = '1' ";	
		$consulta = $pdo->query("$_sql");
		$retorno = $consulta->fetchAll();
			foreach ($retorno as $rst) {
				$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".pedidoitens SET  pi_situacao = '2', pi_idpedido = '$id'  WHERE pi_id = '".$rst['pi_id']."' ");
				$statement->execute();	
				if($rst['pi_tipodocumento'] == 2){
					$_ref = "<strong>nº:".$_parametros['_nPedido']." </strong>- Gerado em <strong>".$_dtpedido."</strong>";
					$_ref2 = "nº:".$_parametros['_nPedido']." - Gerado em ".$_dtpedido." ";
					
					$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamada SET SituacaoOS_Elx = '2', Num_Pedido = '$_ref2', Data_Pedido = CURRENT_DATE() WHERE CODIGO_CHAMADA = '".$rst['pi_documento']."' ");
					$statement->execute();	
					//ATUALIZAR ITEM CHAMADA PECA
					$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca SET 	Pedido_Fabricante = 'S',sitpeca = '5',nPedido = '".$id."' WHERE  Seq_item  = '".$rst['piSeq_itemOS']."'	AND Numero_OS  = '".$rst['pi_documento']."'");
					$statement->execute();

					$descricao_alteof = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Pedido ".$_ref ."</strong>";
					
						$consulta = "insert into  " . $_SESSION['BASE'] . ".acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao) values (
							CURRENT_DATE(),'$data','".$rst['pi_documento']."' ,'$usuario', '". $_SESSION["login"]."','".$rst['pi_idcliente']."','$descricao_alteof' )";       
						$statement = $pdo->prepare($consulta);
						$statement->execute();
					
				}
																							
			}

			echo '<div class="alert alert-success" style="margin-top: 0px;" >
					<h5>Pedido Confirmado  !!</h5>
	 			 </div>';
			?>
		
			<button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
		<?php

												

	}else{ ?>
		<button class="btn btn-success waves-effect waves-light " type="button" onclick="_ConfGerarPedido()"> <i class="fa fa-plus-square-o"></i> Confirmar Pedido </button>
		<button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
		<?php
	}

}


//PEDIDO


if($_acao == 22 ) {   //LISTATAGEM PEDIDO

	
	$_npedido= $_parametros['_npedido'];
	$_codpeca= $_parametros['_codpeca'];
	$dataini= $_parametros['_dataIni'];
	$datafim = $_parametros['_dataFim'];

	$_dataAT = $_parametros['_dataAT'];
	$_StatusItem = $_parametros['_StatusItem'];
	$_StatusPedido = $_parametros['_StatusPedido'];

	$dia       = date('d'); 
	$mes       = date('m'); 
	$ano       = date('Y'); 



	if($datafim == "" ) {
		$dataini = "2000-01-01" ;   
		$datafim = $ano."-".$mes."-".$dia ;   	
	}

	if(trim($_codpeca) != "") { 
		$_filtro = " AND pi_codfabricante = '$_codpeca'";
	}

	if(trim($_npedido) != "") { 
		$_filtro = $_filtro." AND ped_numero = '$_npedido'  OR  ped_numero = '$_npedido' ";
	}




if(trim($_StatusPedido) != "") { 
	$_filtro = $_filtro ." AND ped_sit = '$_StatusPedido'";
}

if(trim($_StatusItem) == "") { 
	$_filtro = $_filtro ." AND pi_situacao = '2'";

}else{
	if(trim($_StatusItem) == "0") { 
	}else{
		$_filtro = $_filtro ." AND pi_situacao = '$_StatusItem'";
	}
	
}



if(trim($_dataAT) != "") { 
	$_filtro = $_filtro ." AND pi_dtatualizado >= '$_dataAT 00:00:00' and pi_dtatualizado <= '$_dataAT 23:59:59'   ";
}

	$_SQL = "SELECT ped_id,ped_numero,ped_obs,NOME,TPCor,TPDescricao , DATE_FORMAT(ped_data , '%d/%m/%Y') AS pi_date, DATE_FORMAT( ped_dtultat , '%d/%m/%Y') AS pi_dateAT,
	pi_id,pi_codfabricante,pi_descitem , DATE_FORMAT( pi_dtOS , '%d/%m/%Y') AS  dtOS,pi_documento,pi_qtde,
	pi_tensao, pi_modelo,pi_tensao, DATEDIFF(current_date(), ped_data) AS dias_de_intervalo,sp_cor,sp_desc,spi_cor,spi_desc,pi_produto,pi_svo,g_sigla,g_cor
	FROM ". $_SESSION['BASE'] .".pedido
	LEFT JOIN  ". $_SESSION['BASE'] .".pedidoitens  ON pi_idpedido  = ped_id	
	LEFT JOIN  ". $_SESSION['BASE'] .".fabricante  ON ped_fabricante  = CODIGO_FABRICANTE	
	LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia  ON g_id  = pi_tipgarantia	
	LEFT JOIN  ". $_SESSION['BASE'] .".tipopedido  ON pi_tipodocumento	  = Cod_Tipo	
	LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPed  ON sp_id	  = ped_sit
	LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPedItem  ON spi_id	  = pi_situacao
	WHERE  ped_data BETWEEN 	'$dataini' and  ' $datafim' $_filtro
	 ORDER BY ped_data";
	 

	$statement = $pdo->query("$_SQL");
    $retorno = $statement->fetchAll();
    ?>
	<div style="margin-bottom: 5px;">
		
		<button type="button" class="btn btn-default waves-effect waves-light  btn-xs" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa  fa-sliders"></i></span>Filtros</button>
		<button class="btn btn-inverse waves-effect waves-light  btn-xs" onclick="_printFiltro()"><i class="fa fa-print"></i></button>
		<button class="btn btn-inverse waves-effect waves-light  btn-xs" onclick="_printOS()"><i class="fa fa-print"></i> O.S Liberada</button>
	</div>
    <table id="datatable-fixed-col"  class="table  table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
			<th class="text-center">Status Ped</th>		
			    <th class="text-center">Pedido</th>
                <th class="text-center">Dt. Pedido</th>
                <th class="text-center">Fornecedor</th>
                <th class="text-center">Dt.Ult.Atualização</th>				
				<th class="text-center" >Dias Pedido</th>
						
				<th class="text-center">Tipo</th>	
				<th class="text-center">Documento</th>	
				<th class="text-center">O.S Fabr.</th>	
				<th class="text-center">Aberta em </th>	
				
				<th class="text-center">Qtde</th>
				<th colspan="2" class="text-center" style="min-width: 300px;">Peça/Produto</th>	
				<th class="text-center">Sit.Item</th>	
				<th colspan="2" class="text-center">Dados O.S</th>			
				
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst) {
			$_estoque = 0;
			$_garantia  = $rst["g_sigla"];
			if($_garantia == "") {
				$_garantia = "FG";
				$_garantiaCOR = 'primary';
			}else{
				$_garantiaCOR  = $rst["g_cor"];
			}
			
			$i++;

			if($rst["pi_selped"] == "1"){
				$_chk = 'checked="checked"';
			}else{
				$_chk = "";
			}//pi_tipodocumento
			
        ?>
		
            <tr class="gradeX">
			<td class="text-center"><span class="label label-<?=($rst["sp_cor"])?> " style="font-size:12px; "><?=($rst["sp_desc"])?></span></td>
				<td class="text-center"><?=($rst["ped_numero"])?></td>		
                <td class="text-center"><?=($rst["pi_date"])?></td>
                <td class="text-center"><?=($rst["NOME"])?></td>
                <td class="text-center"><?=($rst["pi_dateAT"])?></td>			
				<td class="text-center"><?=($rst["dias_de_intervalo"])?></td>
						
				<td class="text-center"><span class="label label-<?=($rst["TPCor"])?> " style="font-size:12px; "><?=($rst["TPDescricao"])?></span></td>
				<td class="text-center"><?=($rst["pi_documento"])?> <span class="badge badge-xs badge-<?=$_garantiaCOR;?>"><?=($_garantia)?></td>	
				<td  class="text-center"><?=($rst["pi_svo"])?></td>
				<td class="text-center"><?=($rst["dtOS"])?></td>	
				<td class="text-center"><?=($rst["pi_qtde"])?></td>		
				<td class="text-center"><?=($rst["pi_codfabricante"])?></td>
				<td ><?=($rst["pi_descitem"])?></td>				
				<td class="text-center"><span class="label label-<?=($rst["spi_cor"])?> " style="font-size:12px; "><?=($rst["spi_desc"])?></span></td>				
				<td colspan="2" ><?=($rst["pi_produto"])?> <?=($rst["pi_modelo"])?> <?=($rst["pi_tensao"])?></td>
			
				<td><a href="#" class="on-default edit-row" data-toggle="modal" data-target="#modalpedido" onclick="detPedido(<?=$rst["pi_id"]?>)"><i class="fa fa-pencil"></i></a></td>	
				
				</tr>
        <?php
        }
        ?>
        </tbody>
    </table> 

	<?php

}

if($_acao == 23 ) {   //DETALHE PEDIDO

$_SQL = "SELECT ped_id,ped_numero,ped_obs,NOME,TPCor,TPDescricao , DATE_FORMAT(ped_data , '%d/%m/%Y') AS pi_date, DATE_FORMAT( ped_dtultat , '%d/%m/%Y') AS pi_dateAT,
	pi_id,pi_codfabricante,pi_descitem , DATE_FORMAT( pi_dtOS , '%d/%m/%Y') AS  dtOS,pi_documento,pi_qtde,
	pi_tensao, pi_modelo,pi_tensao, DATEDIFF(current_date(), ped_data) AS dias_de_intervalo,sp_cor,sp_desc,spi_cor,spi_desc,pi_produto,
	pi_situacao,pi_observacao,pi_dtatualizado,DATE_FORMAT( pi_dtatualizado , '%d/%m/%Y %T') AS  dtatualizado,piSeq_itemOS
	FROM ". $_SESSION['BASE'] .".pedido
	LEFT JOIN  ". $_SESSION['BASE'] .".pedidoitens  ON pi_idpedido  = ped_id	
	LEFT JOIN  ". $_SESSION['BASE'] .".fabricante  ON ped_fabricante  = CODIGO_FABRICANTE	
	LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia  ON g_id  = pi_tipgarantia	
	LEFT JOIN  ". $_SESSION['BASE'] .".tipopedido  ON pi_tipodocumento	  = Cod_Tipo	
	LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPed  ON sp_id	  = ped_sit
	LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPedItem  ON spi_id	  = pi_situacao
	WHERE pi_id = '".$_parametros['_codpedidoitem']."' limit 1";	

	$statement = $pdo->query("$_SQL");
    $retorno = $statement->fetchAll();
	foreach ($retorno as $rst) {
	?>
	   <div class="card-box">
			<div class="row">    
				<div class="col-md-4"><strong>Pedido:</strong><?=($rst["ped_numero"])?>  </div>
				<input type="hidden" name="_codpedido" id="_codpedido" value="<?=($rst["ped_id"])?>">
				
				<input type="hidden" name="_piSeq_itemOS" id="_piSeq_itemOS" value="<?=($rst["piSeq_itemOS"])?>">
				<div class="col-md-4"> <strong>Data Pedido:</strong> <?=($rst["pi_date"])?> </div>
				<div class="col-md-4"> <strong>Documento:</strong> <?=($rst["pi_documento"])?> </div>
			</div>	
			<div class="row">    
				<div class="col-md-8"><strong>Obs.Pedido:</strong><?=($rst["ped_obs"])?>  </div>
				
			</div>	
			<div class="row">    
				<div class="col-md-8"><strong>Descrição:</strong>  <?=($rst["pi_codfabricante"])?> - <?=($rst["pi_descitem"])?></div>
				<div class="col-md-4"><strong>Qtde:</strong><?=($rst["pi_qtde"])?>  </div>
			</div>	                      
			<div class="row">   
				<div class="col-md-8"><strong>Produto:</strong>  <?=($rst["pi_produto"])?> </div> 
				<div class="col-md-4"><strong>Modelo:</strong><?=($rst["pi_modelo"])?>  <strong>Tensão:</strong> <?=($rst["pi_tensao"])?> </div>
			</div>
			<div class="row">    
				<div class="col-md-12">
					<div class="form-group"><label>Anotação Item</label>
							<textarea name="anotacaoitem" rows="2" id="anotacaoitem" class="form-control input-sm"><?=($rst["pi_observacao"])?></textarea><br>
							<?php 
							if($row['pi_dtatualizado'] != "0000-00-00 00:00:00" ) { 
								echo "<code>Ultima Atualização item:".$rst['dtatualizado']."</code>";
							}
							?>
                    </div>
				 </div>
				
			</div>	
			<div class="row">    
				<div class="col-md-4">
					<div class="form-group"><label>Situação</label>
							<select class="form-control "   name="_sititem" id="_sititem">
										<?php
										$statement2 = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".situacaoPedItem where spi_id <> 1 ORDER BY spi_id");
										$retorno2 = $statement2->fetchAll();
										?>
											<option value="">Selecione</option>
										<?php
										foreach ($retorno2 as $row2) {
										?>
											<option value="<?=$row2["spi_id"]?>"  <?php if($row2["spi_id"] == $rst['pi_situacao']) { echo 'selected="selected"'; }?>><?=$row2["spi_desc"]?></option>
										<?php
										}
										?>
										</select>
						</div>
				</div>
				<div class="col-md-4" style="margin-top: 25px;">
						<button class="btn btn-success waves-effect waves-light" onclick="_salvaritem()"><i class="fa  fa-floppy-o"></i> </span> Salvar</button>
				</div>
				
		</div>
	                 
    
    
	
	<?php
	}

}


if($_acao == 24 ) {   //salvar item atlerado

	$sql = "UPDATE  " . $_SESSION['BASE'] . ".pedidoitens SET 	pi_situacao = '".$_parametros['_sititem']."', pi_observacao = '".$_parametros['anotacaoitem']."',pi_dtatualizado = '$data'
	WHERE  pi_id  = '".$_parametros['_codpedidoitem']."'	";	
	$statement = $pdo->prepare("$sql");
	$statement->execute();

	$sql = "UPDATE  " . $_SESSION['BASE'] . ".pedido SET 	ped_dtultat = '$data'
	WHERE  ped_id  = '".$_parametros['_codpedido']."'	";	
	$statement = $pdo->prepare("$sql");
	$statement->execute();

	if($_parametros['_sititem'] == 4) { //cancelado
		$mensagem = "Item Cancelado" ;
			//ATUALIZAR ITEM CHAMADA PECA
			$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca SET 	Pedido_Fabricante = 'N',sitpeca = '1' WHERE  Seq_item  = '".$_parametros['_piSeq_itemOS']."'");
			$statement->execute();
	
		?>
  		<div class="bg-icon pull-request" style="text-align: center;">
                      <i class="md-3x   md-thumb-up text-danger"></i>
					  <h3 ><?=$mensagem;?> !!! </h3>
                  </div>
                  
	<?php
	} else{
		$mensagem = "Atualizado com sucesso ";
			//ATUALIZAR ITEM CHAMADA PECA
			$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".chamadapeca SET 	sitpeca = '6' WHERE  Seq_item  = '".$_parametros['_piSeq_itemOS']."'");
			$statement->execute();
		?>
  		<div class="bg-icon pull-request" style="text-align: center;">
                      <i class="md-3x   md-thumb-up text-success"></i>
					  <h3 ><?=$mensagem;?> !!! </h3>
                  </div>
                  
	<?php
	}

	$_status =0 ; /*
	
	1 Pedido
	2 Ped.Parcial
	3 Concluído
	4 cancelado

	1 Análise
	2 Pendente
	3 Entregue
	4 Cancelado

	*/
	//verificar status de todos itens
	$_sql = "SELECT pi_situacao,pi_idpedido
	FROM " . $_SESSION['BASE'] . ".pedidoitens                          
	WHERE pi_idpedido = '".$_parametros["_codpedido"]."' and pi_situacao <> 1 ORDER BY  pi_situacao " ;
		$consulta = $pdo->query("$_sql");
		$retorno = $consulta->fetchAll();
			foreach ($retorno as $row) {
				$_idpedido = $row['pi_idpedido'];
				$_situacaoItem = $row['pi_situacao'];
							
				if($_status == 0  )  {
					$_status ='1';
					if($_situacaoItem == 2){
						$_status ='1';
					}elseif($_situacaoItem == 3){
						$_status ='3';
					}else{
						$_status ='4';
					}
					
				}else{
				
						if($_situacaoItem == 2 )  {
							$_status ='1';
						}elseif($_situacaoItem == 3 and  $_status == 1 or $_situacaoItem == 4 and  $_status == 1)  {
							$_status ='2';

						}else{
							if($_situacaoItem == 3 and  $_status == 1 or $_situacaoItem == 4 and  $_status == 1)  {
								$_status ='2';
							}else{
								$_status =$_situacaoItem;
							}
						}
					
					

				}
				
				
				
			}
			$statement = $pdo->prepare("UPDATE  " . $_SESSION['BASE'] . ".pedido SET 	ped_sit = '$_status' WHERE  ped_id  = '".$_idpedido."'");
		$statement->execute();

	

	
}


if($_acao == 97 ) {   //os liberada

	?>
		<style type="text/css">

		.style5 {font-size: 16px; font-family: Arial, Helvetica, sans-serif;}
		.style6 {font-size: 16px}
		table.bordasimples {border-collapse: collapse;}
		table.bordasimples tr td {border:1px solid #000000;}
		.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
		-->
		</style>
	<?php


    try {
   

	
		$_npedido= $_parametros['_npedido'];
		$_codpeca= $_parametros['_codpeca'];
		$dataini= $_parametros['_dataIni'];
		$datafim = $_parametros['_dataFim'];
	
		$_dataAT = $_parametros['_dataAT'];
		$_StatusItem = $_parametros['_StatusItem'];
		$_StatusPedido = $_parametros['_StatusPedido'];
	
		$dia       = date('d'); 
		$mes       = date('m'); 
		$ano       = date('Y'); 
	
	
	
		if($datafim == "" ) {
			$dataini = "2000-01-01" ;   
			$datafim = $ano."-".$mes."-".$dia ;   	
		}
	
		if(trim($_codpeca) != "") { 
			$_filtro = " AND pi_codfabricante = '$_codpeca'";
		}
	
		if(trim($_npedido) != "") { 
			$_filtro = $_filtro." AND ped_numero = '$_npedido'  OR  ped_numero = '$_npedido' ";
		}
	
	
	
	
	if(trim($_StatusPedido) != "") { 
		$_filtro = $_filtro ." AND ped_sit = '$_StatusPedido'";
	}
	
	if(trim($_StatusItem) != "") { 
		$_filtro = $_filtro ." AND pi_situacao = '$_StatusItem'";
	}
	
	if(trim($_dataAT) != "") { 
		$_filtro = $_filtro ." AND pi_dtatualizado >= '$_dataAT 00:00:00' and pi_dtatualizado <= '$_dataAT 23:59:59'   ";
	}

	$dias = diasDatas($dataini,$datafim);

if($dias > 31) { 
	echo "<h2>PERÍODO NÃO PODE SER SUPERIOR A 30 DIAS</h2>";
	exit();
}

	$datainiT = explode("-",$dataini); $datainiT = $datainiT[2]."/".$datainiT[1]."/".$datainiT[0];
	$datafimT = explode("-",$datafim); $datafimT = $datafimT[2]."/".$datafimT[1]."/".$datafimT[0];


	$_SQL = "SELECT pi_documento FROM ". $_SESSION['BASE'] .".pedidoitens WHERE  pi_dtatualizado >= '$dataini 00:00:00' and pi_dtatualizado <=  '$datafim 23:59:59' AND pi_documento > 0 GROUP BY pi_documento";
		
		$statement = $pdo->query("$_SQL");
		$retorno = $statement->fetchAll();

	
            ?>
        
                    <div >
                    <table   width="100%" border="0">
                        <tr>
                            <td width="374" class="style34" ><strong><span class="style31" >                        
                            </span>  Relatorio O.S Liberadas entre <?=$datainiT;?> e <?=$datafimT;?>  </td>                           
                        </tr>
                     
                     
                        </table>
                      
                        <table border="0" class="bordasimples " width="100%" >
						<tr>
							<th>Número pedido</th> 
							<th>Data Pedido</th> 
							
							<th>Doc</th>
							<th>Qtde</th>
							<th>O.S Fabr.</th> 						
							<th>Código</th>
							<th>Descrição</th>      							
						
						
							<th>Anotação</th>     	  						
						</tr>
                          <?php
						  $situacao = "";  $situacaoUltima = ""; $_valida = 0;
						foreach ($retorno as $rstdoc) {
							$_valida = 0;
							$_SQLTotal = "SELECT pi_documento,pi_situacao FROM ". $_SESSION['BASE'] .".pedidoitens WHERE  pi_documento = '".$rstdoc['pi_documento']."'	 GROUP BY pi_situacao";													
							$statementT = $pdo->query("$_SQLTotal");
							$_totalreg = $statementT->rowCount();
							$retorno2 = $statementT->fetchAll();
							foreach ($retorno2 as $rst) {
								if($rst['pi_situacao'] == 3){
									$_valida = 1;
								}
							}

						
							if($_valida == 1 and  $_totalreg == 1){

							

							$_SQL2 = "SELECT ped_id,ped_numero,ped_obs,NOME,TPCor,TPDescricao , DATE_FORMAT(ped_data , '%d/%m/%Y') AS pi_date, DATE_FORMAT( ped_dtultat , '%d/%m/%Y') AS pi_dateAT,
							pi_id,pi_codfabricante,pi_descitem , DATE_FORMAT( pi_dtOS , '%d/%m/%Y') AS  dtOS,pi_documento,pi_qtde,
							pi_tensao, pi_modelo,pi_tensao, DATEDIFF(current_date(), ped_data) AS dias_de_intervalo,sp_cor,sp_desc,spi_cor,spi_desc,pi_produto,pi_svo,
							pi_situacao
							FROM ". $_SESSION['BASE'] .".pedidoitens 
							LEFT JOIN  ". $_SESSION['BASE'] .".pedido ON pi_idpedido  = ped_id	
							LEFT JOIN  ". $_SESSION['BASE'] .".fabricante  ON ped_fabricante  = CODIGO_FABRICANTE	
							LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia  ON g_id  = pi_tipgarantia	
							LEFT JOIN  ". $_SESSION['BASE'] .".tipopedido  ON pi_tipodocumento	  = Cod_Tipo	
							LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPed  ON sp_id	  = ped_sit
							LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPedItem  ON spi_id	  = pi_situacao
							WHERE  pi_documento = '".$rstdoc['pi_documento']."'						
							ORDER BY pi_situacao";
						//	echo $_SQL2;
							$statement2 = $pdo->query("$_SQL2");
							$retorno2 = $statement2->fetchAll();
							foreach ($retorno2 as $rst) {
								$situacao = $rst['pi_situacao'];
								$__numero = $rst["ped_numero"];
								$__data = $rst["pi_date"];
								$__documento = $rst["pi_documento"];
								$__svo = $rst["pi_svo"];
								$__qtde = $rst["pi_qtde"];
								$__codfabr = $rst["pi_codfabricante"];
								$__descitem = $rst["pi_descitem"];
								$__produto = $rst["pi_produto"];
								$__modelo = $rst["pi_modelo"];
								$__tensao = $rst["pi_tensao"];
								$__garantia = "";
								?>
						
								<td class="text-center"><?=($__numero)?></td>		
								<td class="text-center"><?=($__data)?></td>
								
								<td class="text-center"><?=($__documento)?> <?=($__garantia)?></td>	
								<td class="text-center"><?=($__qtde)?></td>		
								<td  class="text-center"><?=($__svo)?></td>
									
								
								<td class="text-center"><?=($__codfabr)?></td>
								<td ><?=($__descitem)?></td>				
										
								<td colspan="2" ><?=($__produto)?> <?=($__modelo)?> <?=($__tensao)?></td>
							
								</tr>
							<?php
                           
					
						}
					}
						
					}
			
										?>
                        
                             
                          
                        </table>
                    </div>
         
            <?php
			
      //  }
      
    } catch (PDOException $e) {
       
    }

}


if($_acao == 98 ) {   //filtro 

	?>
		<style type="text/css">

		.style5 {font-size: 16px; font-family: Arial, Helvetica, sans-serif;}
		.style6 {font-size: 16px}
		table.bordasimples {border-collapse: collapse;}
		table.bordasimples tr td {border:1px solid #000000;}
		.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
		-->
		</style>
	<?php


    try {
   

	
		$_npedido= $_parametros['_npedido'];
		$_codpeca= $_parametros['_codpeca'];
		$dataini= $_parametros['_dataIni'];
		$datafim = $_parametros['_dataFim'];
	
		$_dataAT = $_parametros['_dataAT'];
		$_StatusItem = $_parametros['_StatusItem'];
		$_StatusPedido = $_parametros['_StatusPedido'];
	
		$dia       = date('d'); 
		$mes       = date('m'); 
		$ano       = date('Y'); 
	
	
	
		if($datafim == "" ) {
			$dataini = "2000-01-01" ;   
			$datafim = $ano."-".$mes."-".$dia ;   	
		}
	
		if(trim($_codpeca) != "") { 
			$_filtro = " AND pi_codfabricante = '$_codpeca'";
		}
	
		if(trim($_npedido) != "") { 
			$_filtro = $_filtro." AND ped_numero = '$_npedido'  OR  ped_numero = '$_npedido' ";
		}
	
	
	
	
	if(trim($_StatusPedido) != "") { 
		$_filtro = $_filtro ." AND ped_sit = '$_StatusPedido'";
	}
	
	if(trim($_StatusItem) != "") { 
		$_filtro = $_filtro ." AND pi_situacao = '$_StatusItem'";
	}
	
	if(trim($_dataAT) != "") { 
		$_filtro = $_filtro ." AND pi_dtatualizado >= '$_dataAT 00:00:00' and pi_dtatualizado <= '$_dataAT 23:59:59'   ";
	}
	
		$_SQL = "SELECT ped_id,ped_numero,ped_obs,NOME,TPCor,TPDescricao , DATE_FORMAT(ped_data , '%d/%m/%Y') AS pi_date, DATE_FORMAT( ped_dtultat , '%d/%m/%Y') AS pi_dateAT,
		pi_id,pi_codfabricante,pi_descitem , DATE_FORMAT( pi_dtOS , '%d/%m/%Y') AS  dtOS,pi_documento,pi_qtde,
		pi_tensao, pi_modelo,pi_tensao, DATEDIFF(current_date(), ped_data) AS dias_de_intervalo,sp_cor,sp_desc,spi_cor,spi_desc,pi_produto,pi_svo
		FROM ". $_SESSION['BASE'] .".pedido
		LEFT JOIN  ". $_SESSION['BASE'] .".pedidoitens  ON pi_idpedido  = ped_id	
		LEFT JOIN  ". $_SESSION['BASE'] .".fabricante  ON ped_fabricante  = CODIGO_FABRICANTE	
		LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia  ON g_id  = pi_tipgarantia	
		LEFT JOIN  ". $_SESSION['BASE'] .".tipopedido  ON pi_tipodocumento	  = Cod_Tipo	
		LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPed  ON sp_id	  = ped_sit
		LEFT JOIN  ". $_SESSION['BASE'] .".situacaoPedItem  ON spi_id	  = pi_situacao
		WHERE  ped_data BETWEEN 	'$dataini' and  ' $datafim' $_filtro
		 ORDER BY ped_data";
		
		
		$statement = $pdo->query("$_SQL");
		$retorno = $statement->fetchAll();
            ?>
        
                    <div >
                    <table   width="100%" border="0">
                        <tr>
                            <td width="374" class="style34" ><strong><span class="style31" >                        
                            </span>  Relatorio Geral </td>                           
                        </tr>
                     
                     
                        </table>
                      
                        <table border="0" class="bordasimples " width="100%" >
						<tr>
							<th>Número pedido</th> 
							<th>Data Pedido</th> 
							<th>Fornecedor</th>
							<th>Tipo</th>
							<th>Doc</th>
							<th>Qtde</th>
							<th>O.S Fabr.</th> 
						
							<th>Código</th>
							<th>Descrição</th>          							
						
						
							<th>Anotação</th>     	  						
						</tr>
                          <?php
						foreach ($retorno as $rst) {
						?>
						
							<td class="text-center"><?=($rst["ped_numero"])?></td>		
							<td class="text-center"><?=($rst["pi_date"])?></td>
							<td class="text-center"><?=($rst["NOME"])?></td>									
							<td class="text-center"><?=($rst["TPDescricao"])?></td>
							<td class="text-center"><?=($rst["pi_documento"])?> <?=($_garantia)?></td>	
							<td  class="text-center"><?=($rst["pi_svo"])?></td>
								
							<td class="text-center"><?=($rst["pi_qtde"])?></td>		
							<td class="text-center"><?=($rst["pi_codfabricante"])?></td>
							<td ><?=($rst["pi_descitem"])?></td>				
									
							<td colspan="2" ><?=($rst["pi_produto"])?> <?=($rst["pi_modelo"])?> <?=($rst["pi_tensao"])?></td>
						
							</tr>
						<?php

					
						
					}
					?>
                        
                             
                          
                        </table>
                    </div>
         
            <?php
      //  }
      
    } catch (PDOException $e) {
       
    }

}

if($_acao == 99 ) {   //verificar entradas
	?>
		<style type="text/css">

		.style5 {font-size: 16px; font-family: Arial, Helvetica, sans-serif;}
		.style6 {font-size: 16px}
		table.bordasimples {border-collapse: collapse;}
		table.bordasimples tr td {border:1px solid #000000;}
		.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
		-->
		</style>
	<?php


    try {
   
	
	$data_atual = explode("-",$_parametros['nf-inicial']);
	$data_atual = $data_atual[2]."/".$data_atual[1]."/".$data_atual[0];

	$_sql = "SELECT NFE_ID,NFE_NRO,NOME,NFE_FORNEC,CODIGO_FABRICANTE,NFE_TOTALNF,NFE_Conferido,NFE_INFOADD,
	DATE_FORMAT(NFE_DATAENTR, '%d/%m/%Y') AS NF_ENTRADA FROM ". $_SESSION['BASE'] .".nota_ent_base 
	LEFT JOIN ". $_SESSION['BASE'] .".nota_xml ON nxml_idnotabase = NFE_ID
	LEFT JOIN ". $_SESSION['BASE'] .".fabricante ON CODIGO_FABRICANTE = NFE_FORNEC 
	WHERE nxml_data = '". $_parametros['nf-inicial'] ."'  ORDER BY NFE_DATAENTR, NFE_RAZSOC";

	$statement = $pdo->query("$_sql");
	 $retorno = $statement->fetchAll();

            ?>
        
                    <div >
                    <table   width="100%" border="0">
                        <tr>
                            <td width="374" class="style34" ><strong><span class="style31" >                        
                            </span>  Notas de Entrada( <?=$data_atual ;?>) </td>                           
                        </tr>
                     
                     
                        </table>
                      
                        <table border="0" class="bordasimples " width="100%" >
						<tr>
							<th>N° NF</th>
							<th>Fornecedor</th>          							
							<th colspan= "3" class="text-center">Obs</th>							
						</tr>
                          <?php
						foreach ($retorno as $row) {
					

						//buscar tabela pedido 
						$_sql = "SELECT NFE_CODIGO,NFE_DESCRICAO,CODIGO_FABRICANTE FROM ". $_SESSION['BASE'] .".nota_ent_item
						LEFT JOIN ". $_SESSION['BASE'] .".itemestoque ON NFE_CODIGO = CODIGO_FORNECEDOR
						WHERE NFE_IDBASE = '".$row['NFE_ID']."'";
					
						$NFITEM = $pdo->query("$_sql");
						if($NFITEM->rowCount() > 0) {
							?>
							<tr class="gradeX">
								<td><?=$row["NFE_NRO"]?></td>
								<td><?=($row["NOME"])?></td>						
								<td colspan= "5"><?=$row["NFE_INFOADD"]?></td>
							
							</tr>
							<?php
						}
						 $retNFITEM = $NFITEM->fetchAll(); ?>
						 <tr>
							<th>Código</th>
							<th>Descrição</th>     
							<th style="text-align: center;">O.S/Doc</th>      							
							<th style="text-align: center;">Número pedido</th> 
							<th style="text-align: center;">Data Pedido</th> 
							<th style="text-align: center;">O.S Fabr.</th> 
							<th>Anotação</th>     	  						
						</tr>
					 <?php
						 foreach ($retNFITEM as $rowITEM) {
							$_pedido = "";
							//buscar no pedido
							$_sql = "SELECT ped_numero,DATE_FORMAT(ped_data,'%d/%m/%Y') as dtped,ped_obs,pi_svo,pi_documento FROM ". $_SESSION['BASE'] .".pedido
							LEFT JOIN ". $_SESSION['BASE'] .".pedidoitens ON pi_idpedido = ped_id
							WHERE pi_codfabricante = '".$rowITEM['CODIGO_FABRICANTE']."' order by ped_data ASC";	
									
							$PEDIDO = $pdo->query("$_sql");
							$retPEDIDO = $PEDIDO->fetchAll();
							foreach ($retPEDIDO as $rowP) {
								$_pedido = $rowP['ped_numero']."<br>";
								$dtped = $rowP['dtped']."<br>";
								if($_obs == ''){
									$_obs = $rowP['ped_obs']."<br>";
								}
								
								$_obsitem = $rowP['pi_observacao']."<br>";
								?>
								<tr class="gradeX">
									<td><?=$rowITEM["CODIGO_FABRICANTE"];?></td>
									<td style="text-align: center;"><?=($rowITEM["NFE_DESCRICAO"])?></td>    
									<td style="text-align: center;" ><?=$rowP["pi_documento"];?></td>        
									<td style="text-align: center;"><?=$_pedido?></td>
									<td style="text-align: center;"><?=$dtped;?></td>
									<td style="text-align: center;"><?=$rowP["pi_svo"];?></td>
									<td ><?=$_obs;?></td>
								</tr>
						<?php
							}
							
					

						 }
						
					}
					?>
                        
                             
                          
                        </table>
                    </div>
         
            <?php
      //  }
      
    } catch (PDOException $e) {
       
    }

}




?> 