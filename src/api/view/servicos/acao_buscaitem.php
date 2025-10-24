<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  
if($_SESSION['BASE'] == "") { 
	echo "SEU LOGIN EXPIROU !!!";
	exit();
}

use Functions\APIecommerce;
use Database\MySQL;
$pdo = MySQL::acessabd();
use Functions\Acesso;

date_default_timezone_set('America/Sao_Paulo');




$_acao = $_POST["acao"];


$_descricao = ($_parametros["busca-produto"]);
$_filtro = $_parametros["filtrarbusca"];
if($_filtro == "") { 
	$_filtro =  $_parametros["filtrarbuscaservico"];
}

$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
}

$query = ("SELECT Ind_Gera_Treinamento from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {      
	$Ind_Gera_Treinamento =   $rst["Ind_Gera_Treinamento"];
}

		
					
						

$_descricao = str_replace("*","%",$_descricao);

$usuarioss = $_SESSION['tecnico'];

if($_filtro == "codigobarra") { $filtrar = "Codigo_Barra = '$_descricao' ";}
if($_filtro == "Codigo_Barra") { $filtrar = "Codigo_Barra = '$_descricao' ";}
if($_filtro == "sku") { $filtrar = "Codigo_Referencia_Fornec = '".str_pad(trim($_descricao), 18, '0', STR_PAD_LEFT)."' ";}
if($_filtro == "CODIGO_FABRICANTE") { $filtrar = "CODIGO_FABRICANTE = '$_descricao' ";}
if($_filtro == "codigo") { $filtrar = "Codigo_Item = '$_descricao'";}
if($_filtro == "descricao") { $filtrar = "DESCRICAO like '%$_descricao%'";}
if($_filtro == "modelo") { $filtrar = "Nome_Modelo = '%$_descricao%'";}
if($_filtro == "endereco") { $filtrar = "itemestoque.ENDERECO1 = '$_descricao' OR itemestoque.ENDERECO2 = '$_descricao'";}
	
function SomarData($data, $dias, $meses, $ano)
{
 // Passe a data no formato dd/mm/yyyy
 $data = explode("/", $data);
   
 // Verificando se a data está no formato correto
 if (count($data) != 3) {
	 return "Formato de data inválido";
 }

 // Criando a nova data usando mktime
 $novaData = mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano);
 
 // Formatando a data de volta para dd/mm/yyyy
 $newData = date("d/m/Y", $novaData);
 
 return $newData;
}


if($_acao == 1) {
	//<table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
	
	?>

 <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered">
                            <thead>
                                <tr>
									<th>Código</th>								
									<th>Descrição</th>                                                                       
                                    <th>Valor</th>
                                    <th>Estoque</th>
                                    <th>End</th>                                    
									<th style="text-align:center">Ação</th>
                                </tr>
                            </thead>
                            
                            <tbody id="tbody_item">
<?php
	$sql="Select sum(itemestoquealmox.Qtde_Disponivel) as tot_item,DESCRICAO,CODIGO_FORNECEDOR,CODIGO_FORNECEDOR,Tab_Preco_5,
	CODIGO_FABRICANTE,Codigo_Barra,Codigo_Item,Nome_Modelo,itemestoque.ENDERECO1 as ender1,itemestoque.ENDERECO2 as ender2,itemestoque.ENDERECO3 as ender3
	 from itemestoque
	inner join itemestoquealmox  ON  Codigo_Item = CODIGO_FORNECEDOR WHERE  $filtrar group by DESCRICAO,CODIGO_FORNECEDOR,CODIGO_FORNECEDOR,Tab_Preco_5,
	CODIGO_FABRICANTE,Codigo_Barra,Codigo_Item,Nome_Modelo,itemestoque.ENDERECO1,itemestoque.ENDERECO2,itemestoque.ENDERECO3  limit 500" ;   

	$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
	   while($pegar = mysqli_fetch_array($resultado)){
		   $QTDE = $pegar['tot_item'];
		   $_descricao= $pegar['DESCRICAO'];
		   $_codigoint= $pegar['CODIGO_FORNECEDOR'];
		   $_barra = $pegar['Codigo_Barra'];
		   $_fornecedor = $pegar['CODIGO_FABRICANTE'];
		   $_preco = number_format($pegar['Tab_Preco_5'],2,',','.');
		   $_modelo= $pegar['Nome_Modelo'];
		   $_end1= $pegar['ender1'];
		   $_end2= $pegar['ender2'];
		   $_end3= $pegar['ender3'];
		   ?>
		    <tr>
		
		   <td><?=$_fornecedor;?></td>
		   <td><?=$_descricao;?></td>                                    
		  
		   <td style="text-align:center ;"><?=$_preco;?></td>
		   <td style="text-align:center ;"><?=$QTDE;?></td>
		   <td style="text-align:center ;"><?=$_end1;?>/<?=$_end2;?>/<?=$_end3;?></td>
		  
		   <td style="text-align:center ;"><a href="javascript:void(0);" class="on-default" onclick="_idprodutosel('<?=$_codigoint;?>')"><i class="fa fa-check fa-1x"></i></a></td>

		   
	   </tr>
	   <?php		  
	   }
	   ?>	
							</tbody>
 </table>  
	   								
	   <?php
	   exit();
}


if($_acao == 111) {
	//<table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
	$_tipo = $_POST['tipo'];
	$os = $_parametros['chamada'];
	if($_tipo == "" or $_tipo == 0) { //peças e produtos	
	?>

				<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                    <thead>
                                                        <tr>                                                       
                                                            <th>Código</th>
															<th>Cod.Fornecedor</th>
                                                            <th>Descrição</th>  
															<th class="text-center">Qtde </th>                                                         
                                                            <th class="text-center">Valor</th>                                                      
                                                            <th class="text-center">Total</th>
															<th class="text-center">Almoxarifado</th>
															<th class="text-center">Est </th>
															<th class="text-center">End</th>
                                                            
                                                            <th class="text-center">Situação</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
														,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc,sitpeca 
														from " . $_SESSION['BASE'] . ".chamadapeca 
                                                        left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
                                                        left join " . $_SESSION['BASE'] . ".almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' order by Minha_Descricao ASC limit 100";

														$consulta = $pdo->query($sql);	
														$executa = $consulta->fetchAll();;
														//$totalConsumidor = $consultaT->rowCount();
													
														foreach ($executa as $row) {					
															

                                                            /*
                                                                <td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
                                                                <td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
                                                                <td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
                                                                <td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
                                                                <td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
                                                            */
																	$sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
																	where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
																	$realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
																	while($rowalmox = mysqli_fetch_array($realmox)){
																		$_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
																							}
																			?>
																			<tr class="gradeX">
																				<td><?=$row["Codigo_Peca_OS"]?></td>
																				<td><?=$row["CODIGO_FABRICANTE"]?></td>			
																				<td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
																				<td class="text-center"><?= $row["Qtde_peca"] ?></td>
																				<td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
																				<td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>    
																				<td class="text-center"><?=$row["Descricao"]?></td>
																				<td class="text-center"><?=$_qtdematriz;?></td>
																				<td class="text-center"><?=$row["ENDERECO1"]?>/<?=$row["ENDERECO2"]?>/<?=$row["ENDERECO3"]?></td>
																				
																				<td class="text-center <?=$row['sitpeca_cor'];?>"><i class="fa <?=$row['sitpeca_icon'];?> fa-1x" ></i> <?=$row['sitpeca_desc'];?></td>
																				<td class="text-center">
																					<a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
																					<?php 
																					if($_situacao < 90 and $row['sitpeca'] != '5') { ?>
																					<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
																					<?php } ?> 
																				</td>
																			</tr>
																		
																			<?php
																	} ?>
                                                    </tbody>
                                                </table> 
				<?php } else { //mao de obra servicos ?>
					<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
												<thead>
													<tr>                                                       
														<th>Código</th>
														<th>Descrição</th>														
														<th class="text-center">Valor</th>                                                      
														<th class="text-center">Total</th>
														<th class="text-center">Técnico</th>													
														<th class="text-center">Ação</th>
													</tr>
												</thead>
												<?php 
													$sql="Select Seq_item,Codigo_Peca_OS,Minha_Descricao,peca_mo,Qtde_peca,usuario_APELIDO,
													peca_mo
													 from  " . $_SESSION['BASE'] . ".chamadapeca 
													left join  " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
													left join  " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
													left join  " . $_SESSION['BASE'] . ".usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
													where TIPO_LANCAMENTO = 1 and	Numero_OS  = '$os' order by Minha_Descricao ASC";
												
														$consulta = $pdo->query($sql);	
														$executa = $consulta->fetchAll();
														//$totalConsumidor = $consultaT->rowCount();
													
														foreach ($executa as $row) {	
														

												?>
												<tr class="gradeX">
													<td><?=$row["Codigo_Peca_OS"]?></td>
													<td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
												
													<td class="text-center"><?=number_format($row["peca_mo"],2,',','.')?></td>
													<td class="text-center"><?=number_format($row["Qtde_peca"]*$row["peca_mo"],2,',','.')?></td>    
													<td class="text-center"><?=$row["usuario_APELIDO"]?></td>
													
													<td class="text-center">
														
														<?php 
														if($_situacao < 90) { ?>
														<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','2')"><i class="fa fa-trash-o fa-2x"></i></a>
														<?php } ?> 
													</td>
												</tr>
											
												<?php
											} ?>
												</tbody>
											</table>
				<?php }
			
														
	   
	   exit();
}

if ($_acao == 2 ){ //buscar dados produtos POR CODIGO INTERNO
	$_descricao = ($_parametros["_keyidpesquisa"]);


   
						if($_vizCodInterno == 1){ 
							$consulta_produto = "Select CODIGO_FORNECEDOR as codint,CODIGO_FABRICANTE as CODIGO_FORNECEDOR ,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
							from itemestoque 
							WHERE    CODIGO_FABRICANTE = '".$_descricao."' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO <> '900'";
						 }else{
							/*
							$consulta_produto = "Select CODIGO_FORNECEDOR as codint,CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
							from itemestoque 
							WHERE CODIGO_FORNECEDOR= '".$_descricao."' and Codigo <> '' and CODIGO_FORNECEDOR <> '' AND GRU_GRUPO <> '900' OR 
						   CODIGO_FABRICANTE = '".$_descricao."' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO <> '900'";
						   */
						  $consulta_produto = "Select CODIGO_FORNECEDOR as codint,CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
						  from itemestoque 
						  WHERE CODIGO_FORNECEDOR= '".$_descricao."' and Codigo <> '' and CODIGO_FORNECEDOR <> '' AND GRU_GRUPO <> '900' ";
							
						}			
    $resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
	if(mysqli_num_rows($resultado)> 0){
		print_r(json_encode(mysqli_fetch_array($resultado)));
	}else{
		$consulta_produto = "Select CODIGO_FORNECEDOR as codint,CODIGO_FABRICANTE as CODIGO_FORNECEDOR ,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
							from itemestoque 
							WHERE CODIGO_FORNECEDOR= '".$_descricao."' and Codigo <> '' and CODIGO_FORNECEDOR <> '' AND GRU_GRUPO <> '900' ";
							$resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
		print_r(json_encode(mysqli_fetch_array($resultado)));
	}
		
 
   
}


if ($_acao == 3 ){ //incluir item chamada
		//inserir registro	
		
		$codigo = $_parametros['_codpesqInt'];
		$os = $_parametros['chamada'];
	    $descricao = $_parametros['_desc'];
	    $qtde = $_parametros['_qtde'];
		if($qtde == "" or $qtde == 0) {
			$qtde = 1;
		}
	   	$valor = str_replace(".", "", $_parametros['_vlr']);
		$valor = str_replace(",", ".", $valor);		
		$almoxarifado = $_parametros['_almox'];
		$valor_mo = 0;

		$total = $valor * $qtde;

/*
		$sql="Select * from chamadapeca 
			left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
			left join situacaopeca ON sitpeca = sitpeca_id
			left join usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
			where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' and Codigo_Peca_OS = '$codigo' ";
			*/
			$sql="Select Codigo_Peca_OS from " . $_SESSION['BASE'] . ".chamadapeca 			
			where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' and Codigo_Peca_OS = '$codigo' limit 1 ";
			$consulta = $pdo->query($sql);	
			$executa = $consulta->fetchAll();
			if($consulta->rowCount() > 0){
				$msg = "Peça $descricao já incluída !!! ";
			}

		if($codigo == "") { 
			$msg = "Informe código corretamente";

		}else { 
			  $sql="Select CODIGO_FORNECEDOR,PRECO_CUSTO,GRU_GRUPO,	Tab_Preco_5	 from itemestoque where CODIGO_FORNECEDOR = '$codigo' ";          
			  $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
				  while($pegar = mysqli_fetch_array($resultado)){
					 $custo = $pegar["PRECO_CUSTO"];
					 $valorunit = $pegar["Tab_Preco_5"];
					 $grupo = $pegar['GRU_GRUPO'];
					 $_CODPESQUIDADO =  $pegar['CODIGO_FORNECEDOR'];
				}
		if($_CODPESQUIDADO == "" )  {
			$msg = "Código informado não foi localizado";

		}else{
			if($almoxarifado == "" )  {
				$msg = "Almoxarifado não selecionado!!! Verifique se o assessor foi selecionado corretamente  ou se cadastro no cadastro assessor foi adicionado Almoxarifado";
			}
		}
	  
			


		}
		if($msg != "") {  ?>
<div class="row"> 
                            <div class="alert alert-danger alert-dismissable">                      
                            <?=$msg ;?>
                            </div>
                        </div>

		<?php }else{
				
				$consulta = "INSERT INTO chamadapeca (Numero_OS,Codigo_Almox,Codigo_Peca_OS,Valor_Peca,Qtde_peca,Minha_Descricao,
				chamada_custo,peca_tecnico,peca_mo,Data_entrada,user_entrada) 	values 
				('$os','$almoxarifado','$codigo','$valor','$qtde','$descricao','$custo','0','$valor_mo', current_date(),'". $_SESSION['tecnico']."' )";
			   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));   
		}

	
	?>
												<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                    <thead>
                                                        <tr>                                                       
                                                            <th>Código</th>
															<th>Cod.Fornecedor</th>
                                                            <th>Descrição</th>  
															<th class="text-center">Qtde </th>                                                         
                                                            <th class="text-center">Valor</th>                                                      
                                                            <th class="text-center">Total</th>
															<th class="text-center">Almoxarifado</th>
															<th class="text-center">Est </th>
															<th class="text-center">End</th>
                                                            
                                                            <th class="text-center">Situação</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
														,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc,sitpeca 
														from " . $_SESSION['BASE'] . ".chamadapeca 
                                                        left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
                                                        left join " . $_SESSION['BASE'] . ".almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' order by Minha_Descricao ASC limit 100";
                                                    
                                                        $consulta = $pdo->query($sql);	
														$executa = $consulta->fetchAll();
														foreach ($executa as $row) {                                                          

                                                            /*
                                                                <td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
                                                                <td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
                                                                <td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
                                                                <td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
                                                                <td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
                                                            */
															$sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
															where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
													 $realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
													while($rowalmox = mysqli_fetch_array($realmox)){
														$_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
													}
                                                    ?>
                                                    <tr class="gradeX">
                                                        <td><?=$row["Codigo_Peca_OS"]?></td>
														<td><?=$row["CODIGO_FABRICANTE"]?></td>			
                                                        <td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
														<td class="text-center"><?= $row["Qtde_peca"] ?></td>
                                                        <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                        <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>    
                                                        <td class="text-center"><?=$row["Descricao"]?></td>
														<td class="text-center"><?=$_qtdematriz;?></td>
														<td class="text-center"><?=$row["ENDERECO1"]?>/<?=$row["ENDERECO2"]?>/<?=$row["ENDERECO3"]?></td>
                                                        
                                                        <td class="text-center <?=$row['sitpeca_cor'];?>"><i class="fa <?=$row['sitpeca_icon'];?> fa-1x" ></i> <?=$row['sitpeca_desc'];?></td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                            <?php 
                                                            if($_situacao < 90 and $row['sitpeca'] != '5') { ?>
                                                            <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                            <?php } ?> 
                                                        </td>
                                                    </tr>
                                                
                                                    <?php
                                                } ?>
                                                    </tbody>
                                                </table>

<?php 

	}

	
if ($_acao == 33 ){ //pesquisar item chamada para requisicao

	
	if($_parametros['busca-OS'] == "") { 
		$msg = "Informe número da O.S";

	}


	if($msg != "") {  ?>

					<tr class="gradeX">
													
													<td colspan="5"><div class="alert alert-danger alert-dismissable">                      
						<?=$msg ;?>
						</div></td>			
													

	<?php exit(); }



	
													$sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca
													from  " . $_SESSION['BASE'] . ".chamadapeca 
													left join  " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 																									
													where TIPO_LANCAMENTO = 0 and	Numero_OS  = '".$_parametros['busca-OS']."'  and Numero_OS > '0' order by Minha_Descricao ASC";
												
													$consulta = $pdo->query($sql);	
													$executa = $consulta->fetchAll();;
													foreach ($executa as $row) {

														$sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
														where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
															$realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
															while($rowalmox = mysqli_fetch_array($realmox)){
																$_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
															}
												?>
												<tr class="gradeX">
													
													<td><?=$row["CODIGO_FABRICANTE"]?></td>			
													<td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
													<td class="text-center"><?= $row["Qtde_peca"] ?></td>													
													<td class="text-center"><?=$_qtdematriz;?></td>
													
													<td class="text-center">
	
														<a href="javascript:void(0);" class="on-default edit-row" title="Adicionar Direto" onclick="_incluirOS('<?=$_parametros['busca-OS'];?>|<?=$row["Seq_item"];?>')" style="margin-right: 10px;"><i class="fa   fa-plus-square fa-2x"></i></a>
													</td>
												</tr>
											
												<?php
											} ?>
												

<?php 

exit();
}

	

	if ($_acao == 4 ){ //exclui item chamada
		
		$codigo = $_parametros['_idexpeca'];
		$os = $_parametros['chamada'];
	
		$sql="SELECT Codigo_Peca_OS,Qtde_peca,Codigo_Almox,Ind_Baixa_Estoque,reserva FROM chamadapeca WHERE Numero_OS = '$os' AND Seq_item = '$codigo'";
		$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
			while($rstQ = mysqli_fetch_array($resultado)){
				$qtde = $rstQ["Qtde_peca"];
				$almoxarifado =  $rstQ["Codigo_Almox"];
				$Ind_Baixa_Estoque =  $rstQ["Ind_Baixa_Estoque"];
				$codigopeca = $rstQ["Codigo_Peca_OS"];
				$reservado =  $rstQ["reserva"];
				
			}
			   
		if($Ind_Baixa_Estoque == 1) {
					//ATUALIZAR ESTOQUE
					$sql="SELECT Qtde_Disponivel FROM itemestoquealmox  where Codigo_Item  = '$codigopeca' and Codigo_Almox = '$almoxarifado'" ;	
					$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
						while($rstQ = mysqli_fetch_array($resultado)){
							$qtde_atual = $rstQ["Qtde_Disponivel"]+$qtde;				
						}
							$sqlu="Update itemestoquealmox  set Qtde_Disponivel = '$qtde_atual' where Codigo_Item  = '$codigopeca' and Codigo_Almox = '$almoxarifado'" ;   				  
							$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));
						
							if($Ind_Gera_Treinamento == 1) {									
								$retapp = APIecommerce::bling_saldoEstoque($codigopeca,0,0,$qtde, "E","Exclusão por OS $os");	
							 }
					
							$consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
											Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,
											Codigo_Chamada )
											values ( '$codigopeca',
											'$qtde','$almoxarifado','S','O','$os','0','0','','".$usuarioss."','Exclusão por OS ','$qtde_atual','$data2','$os')";
											$executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));
					
		}
		if($reservado > 0){

					$sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica - ".$qtde." 
					where CODIGO_FORNECEDOR  = '" . $codigopeca . "'	" ;	                           
					$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	
					
					$sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = 0
							where CODIGO_FORNECEDOR  = '" . $codigopeca . "'	and Qtde_Reserva_Tecnica < 0" ;	                           
					$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	
		}
					$consulta = "DELETE FROM chamadapeca WHERE Numero_OS = '$os' AND Seq_item = '$codigo' limit 1";
					$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
	
	
	?>
												<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                    <thead>
                                                        <tr>                                                       
                                                            <th>Código</th>
															<th>Cod.Fornecedor</th>
                                                            <th>Descrição</th>
                                                            <th class="text-center">Qtde</th>
                                                            <th class="text-center">Valor</th>                                                      
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Almoxarifado</th>
                                                            <th class="text-center">Est</th>
															<th class="text-center">End</th>
                                                         
                                                            <th class="text-center">Situação</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
														,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc ,sitpeca														
														from " . $_SESSION['BASE'] . ".chamadapeca 
                                                        left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
                                                        left join " . $_SESSION['BASE'] . ".almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and 	Numero_OS  = '$os' order by Seq_item ASC limit 100 ";
                                                    
														$consulta = $pdo->query($sql);	
														$executa = $consulta->fetchAll();;
                                                        foreach ($executa as $row) {
                                                            

                                                            /*
                                                                <td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
                                                                <td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
                                                                <td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
                                                                <td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
                                                                <td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
                                                            */
															$sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
															where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
													 $realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
													while($rowalmox = mysqli_fetch_array($realmox)){
														$_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
													}
                                                    ?>
                                                    <tr class="gradeX">
                                                        <td><?=$row["Codigo_Peca_OS"]?></td>
														<td><?=$row["CODIGO_FABRICANTE"]?></td>													
                                                        <td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
                                                        <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                        <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                        <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>    
                                                        <td class="text-center"><?=$row["Descricao"]?></td>
														<td class="text-center"><?=$_qtdematriz;?></td>
														<td class="text-center"><?=$row["ENDERECO1"]?></td>
                                                        <td class="text-center"><?=$row["ENDERECO2"]?></td>
                                                        <td class="text-center <?=$row['sitpeca_cor'];?>"><i class="fa <?=$row['sitpeca_icon'];?> fa-1x" ></i> <?=$row['sitpeca_desc'];?></td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                            <?php 
                                                            if($_situacao < 90 and $row['sitpeca'] != '5') { ?>
                                                           <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                            <?php } ?> 
                                                        </td>
                                                    </tr>
                                                
                                                    <?php
                                                } ?>
                                                    </tbody>
                                                </table>

<?php 

	}

	
if ($_acao == 8 ){ //listar item chamada
	
	$os = $_parametros['chamada'];

	
	?>
												<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                    <thead>
                                                        <tr>                                                       
                                                            <th>Código</th>
															<th>Cod.Fornecedor</th>
                                                            <th>Descrição</th>
                                                            <th class="text-center">Qtde</th>
                                                            <th class="text-center">Valor</th>                                                      
                                                            <th class="text-center">Total</th>
                                                            <th class="text-center">Almoxarifado</th>														
                                                            <th class="text-center">Estoque</th>
															<th class="text-center">Qtde</th>
															<th class="text-center">End</th>
                                                         
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
														,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc 	,sitpeca 													
														from " . $_SESSION['BASE'] . ".chamadapeca 
                                                        left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
                                                        left join " . $_SESSION['BASE'] . ".almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and 	Numero_OS  = '$os' order by Seq_item ASC limit 100";
                                                    
                                                        $consulta = $pdo->query($sql);	
														$executa = $consulta->fetchAll();;
														foreach ($executa as $row) {	

                                                            /*
                                                                <td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
                                                                <td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
                                                                <td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
                                                                <td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
                                                                <td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
                                                            */
															$sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
															where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
															$realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
															while($rowalmox = mysqli_fetch_array($realmox)){
																$_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
															}
                                                    ?>
                                                    <tr class="gradeX">
                                                        <td><?=$row["Codigo_Peca_OS"]?></td>
														<td><?=$row["CODIGO_FABRICANTE"]?></td>													
                                                        <td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
                                                        <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                        <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                        <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>    
                                                        <td class="text-center"><?=$row["Descricao"]?></td>
                                                        <td class="text-center <?=$row['sitpeca_cor'];?>"><i class="fa <?=$row['sitpeca_icon'];?> fa-1x" ></i> <?=$row['sitpeca_desc'];?></td>
                                                        <td class="text-center"><?=$_qtdematriz;?></td>
														<td class="text-center"><?=$row["ENDERECO1"]?>/<?=$row["ENDERECO2"]?>/<?=$row["ENDERECO3"]?></td>                                                       
														<td class="text-center">
                                                            <a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                            <?php 
                                                            if($_situacao < 90 and $row['sitpeca'] != '5') { ?>
                                                           <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                            <?php } ?> 
                                                        </td>
                                                    </tr>
                                                
                                                    <?php
                                                } ?>
                                                    </tbody>
                                                </table>

<?php 

	}

	
if ($_acao == 9 ){ //detalhe peca

	$_retreserva = Acesso::customizacao('30'); //altera opção alerta reservar para reservado para transferencia peças
	$idpeca = $_parametros['_keyidpesquisa'];
	$os = $_parametros['chamada'];

	$sql="Select CODIGO_FORNECEDOR,Codigo_Barra,CODIGO_SIMILAR,DESCRICAO,MODELO_APLICADO,ENDERECO1,ENDERECO2,
	Num_Requisicao,Pedido_Fabricante,
	date_format(Data_entrada, '%d/%m/%Y') as dtentrada,
	date_format(Data_baixa, '%d/%m/%Y') as dtbaixa,
	A.usuario_APELIDO as userA,
	B.usuario_APELIDO as userB,
	reserva,reserva_solicitacao,Qtde_peca,CODIGO_FABRICANTE,
	nPedido
	 from " . $_SESSION['BASE'] . ".chamadapeca 
	left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 	
	left join " . $_SESSION['BASE'] . ".usuario as A on user_entrada = A.usuario_CODIGOUSUARIO
	left join " . $_SESSION['BASE'] . ".usuario as B on user_baixa = B.usuario_CODIGOUSUARIO
	where Seq_item = '$idpeca' and 	Numero_OS  = '$os'  ";

	$consulta = $pdo->query($sql);	
	$executa = $consulta->fetchAll();
	foreach ($executa as $row) {	
		$reservado = $row['reserva'] ;
		$reserva_solicitacao = $row['reserva_solicitacao'] ;
		$_qtsolicitada = $row['Qtde_peca'] ;
		$_codpeca = $row['CODIGO_FORNECEDOR'];
		$_codpecafab = $row['CODIGO_FABRICANTE'];

		if($row['nPedido']!= "") {
			$sql="Select ped_numero, DATE_FORMAT(ped_data,'%d/%m/%Y') as dtpedido FROM pedido where ped_id = '".$row['nPedido']."'";
			$resultadoPed=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
			while($rowPed = mysqli_fetch_array($resultadoPed)){
				$nPedido = $rowPed["ped_numero"] ;
				$nPedidodata = $rowPed["dtpedido"] ;
			}

		}
	
	?>
	<table class="table table-bordered m-0">
                                          
                                            <tbody>
                                                <tr style="text-align:left ;">
													<td>Código Interno</td>
                                                    <td><?=$row['CODIGO_FORNECEDOR'];?> </td>
													<td>Codigo Barra</td>
                                                    <td><?=$row['Codigo_Barra'];?> </td>
												
                                                </tr>
												<tr style="text-align:left ;">
													<td>Código </td>
                                                    <td><?=$row['CODIGO_FABRICANTE'];?> </td>												
													<td>Codigo Substituto</td>
                                                    <td><?=$row['CODIGO_SIMILAR'];?> </td>
                                                </tr>
												<tr style="text-align:left ;">
													<td >Descrição</td>
                                                    <td  colspan="2"><?=$row['DESCRICAO'];?></td>
                                                </tr>
												
												<tr style="text-align:left ;">
													<td  >Modelo Aplicado</td>
                                                    <td  colspan="2"><?=$row['MODELO_APLICADO'];?></td>
                                                </tr>
												<tr style="text-align:left ;">
													<td   >Endereço A</td>
                                                    <td><?=$row['ENDERECO1'];?></td>
													<td   >Endereço B</td>
                                                    <td><?=$row['ENDERECO2'];?></td>
                                                </tr>
											
												<tr style="text-align:left ;">
													<td   >Usuário Inclusão</td>
                                                    <td><?=$row['userA'];?></td>
													<td   >Dt inclusão OS</td>
                                                    <td><?=$row['dtentrada'];?></td>
                                                </tr>
											
												<tr style="text-align:left ;">
													<td  >Usuário Baixa</td>
                                                    <td><?=$row['userB'];?></td>
													<td   >Dt Baixa Estoque da OS</td>
													<td><?=$row['dtbaixa'];?></td>
                                                </tr>
												
												<tr style="text-align:left ;">
													<td  >Requisição</td>
                                                    <td><?=$row['Num_Requisicao'];?></td>
													<td   >Nº Pedido</td>
                                                    <td><strong><?=$nPedido." - ".$nPedidodata?></strong></td>
                                                </tr>
											
                                               
                                            </tbody>
                                        </table>



<?php } 


if($_retreserva > 0 and $reservado == 0) {
	//reserva com transferencia ?>
		<div id="pcreserva" style="margin-top: 10px;">
							<button type="button"  class="btn   btn-warning btn-md waves-effect" tabindex="2" 
							style="display: inline-block;" onclick="_reservartransf('<?=$idpeca ;?>','<?=$os;?>','<?=$_codpecafab;?>','<?=$_qtsolicitada;?>','<?=$_SESSION['tecnico'];?>','<?=$_codpeca;?>')" >Reservar</button>	
						</div>
	<?php
}else{
	if($reservado > 0) { 
					?>
						
						<div class="alert-danger" style="margin: 10px ;"><p><stron>Reservado</strong></p> </div>
						<p>
								<button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
							</p>
					<?php 
					}else{

						if($reserva_solicitacao == 1) { 
							echo "<strong> * Solicitado Reserva, aguardando liberação *</strong> "; }else{ ?>
						<div id="pcreserva" style="margin-top: 10px;">
							<button type="button"  class="btn   btn-warning btn-md waves-effect" tabindex="2" 
							style="display: inline-block;" onclick="_reserva('<?=$idpeca ;?>','<?=$os;?>','<?=$_codpecafab;?>','<?=$_qtsolicitada;?>','<?=$_SESSION['tecnico'];?>','<?=$_codpeca;?>')" >Solicitar Reserva</button>	
						</div>
				 <?php	}} 
}
				
					

					
					
					
	}

//servi?os*****************************************************************************


if($_acao == 11 ) {
	
	?>
	<table id="datatable-responsive-servico" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
							   <thead>
								   <tr>
									   
									   <th>Codigo </th>
									   <th>Descrição</th>                                    
									   
									   <th>Valor</th>
									   <th style="text-align:center">Ações</th>
								   </tr>
							   </thead>
							   
							   <tbody id="tbody_item">
   <?php
	   $sql="Select DESCRICAO,CODIGO_FORNECEDOR,CODIGO_FORNECEDOR,Tab_Preco_5
		from itemestoque
	    WHERE  $filtrar AND GRU_GRUPO = '900' 
	     limit 500" ;   

	   $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  while($pegar = mysqli_fetch_array($resultado)){
			
			  $_descricao= $pegar['DESCRICAO'];
			  $_codigoint= $pegar['CODIGO_FORNECEDOR'];			
			
			  $_preco = number_format($pegar['Tab_Preco_5'],2,',','.');
			
			  ?>
			   <tr>
			  <td><?=$_codigoint;?></td>
			  <td><?=$_descricao;?></td>                                    
			
			  <td style="text-align:center ;"><?=$_preco;?></td>
			
			  <td style="text-align:center ;"><a href="javascript:void(0);" class="on-default" onclick="_idservicosel('<?=$_codigoint;?>')"><i class="fa fa-check fa-1x"></i></a></td>
   
			  
		  </tr>
		  <?php		  
		  }
		  ?>	
							   </tbody>
	</table>  
										  
		  <?php
   }


if ($_acao == 222 ){ //buscar dados produtos POR CODIGO INTERNO
	$_descricao = ($_parametros["_keyidpesquisa"]);
    $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
                         from itemestoque 
                         WHERE CODIGO_FORNECEDOR= '".$_descricao."' and Codigo <> '' AND GRU_GRUPO = '900' OR 
						       CODIGO_FABRICANTE = '".$_descricao."' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO = '900'";
							 
    $resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
    print_r(json_encode(mysqli_fetch_array($resultado)));
   
}

if ($_acao == 333 ){ //buscar dados produtos requisição OS
	$_descricao = explode("|",$_parametros["_codpesquisaOS"]);

  
	$sql="Select Numero_OS,CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,Valor_Peca
	from chamadapeca 
	left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 																									
	where  Seq_item  = '".$_descricao[1]."'	AND Numero_OS  = '".$_descricao[0]."'  LIMIT 1";

					 
    $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    print_r(json_encode(mysqli_fetch_array($resultado)));
	
   
}

if ($_acao == 13 ){ 
	//inserir registro	
	
	$codigo = $_parametros['_codpesqS'];
	$os = $_parametros['chamada'];
	$descricao = $_parametros['_descS'];
	$qtde = $_parametros['_qtdeS'];
	if($qtde == "" or $qtde == 0) {
		$qtde = 1;
	}
	$valor = str_replace(".", "", $_parametros['_vlrS']);
	$valor_mo = str_replace(",", ".", $valor);		
	$tecnico = $_parametros['_almoxS'];
	
	if($descricao == "") { 
		$msg = "Informe a descrição serviço";
	}else { 
		  
		$consulta = "INSERT INTO chamadapeca (Numero_OS,Codigo_Almox,Codigo_Peca_OS,Valor_Peca,Qtde_peca,Minha_Descricao,
					chamada_custo,peca_tecnico,peca_mo,Data_entrada,	TIPO_LANCAMENTO,user_entrada) 	values 
					('$os','0','$codigo','$valorunit','$qtde','$descricao','$custo','$tecnico','$valor_mo', current_date(),'1','". $_SESSION['tecnico']."' )";
				   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));   

	}

	if($msg != "") {  ?>
		<div class="row"> 
									<div class="alert alert-danger alert-dismissable">                      
									<?=$msg ;?>
									</div>
								</div>		
				<?php }


?>
											<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
												<thead>
													<tr>                                                       
														<th>Código</th>
														<th>Descrição</th>														
														<th class="text-center">Valor</th>                                                      
														<th class="text-center">Total</th>
														<th class="text-center">Técnico</th>													
														<th class="text-center">Ação</th>
													</tr>
												</thead>
												<?php 
													$sql="Select Seq_item,Codigo_Peca_OS,Minha_Descricao,peca_mo,Qtde_peca,usuario_APELIDO,
													peca_mo
													 from " . $_SESSION['BASE'] . ".chamadapeca 
													left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
													left join " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
													left join " . $_SESSION['BASE'] . ".usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
													where TIPO_LANCAMENTO = 1 and	Numero_OS  = '$os' order by Minha_Descricao ASC";
												
													$consulta = $pdo->query($sql);	
													$executa = $consulta->fetchAll();
														foreach ($executa as $row) {

														/*
															<td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
															<td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
															<td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
															<td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
															<td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
														*/
												?>
												<tr class="gradeX">
													<td><?=$row["Codigo_Peca_OS"]?></td>
													<td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
												
													<td class="text-center"><?=number_format($row["peca_mo"],2,',','.')?></td>
													<td class="text-center"><?=number_format($row["Qtde_peca"]*$row["peca_mo"],2,',','.')?></td>    
													<td class="text-center"><?=$row["usuario_APELIDO"]?></td>
													
													<td class="text-center">
														
														<?php 
														if($_situacao < 90) { ?>
														<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','2')"><i class="fa fa-trash-o fa-2x"></i></a>
														<?php } ?> 
													</td>
												</tr>
											
												<?php
											} ?>
												</tbody>
											</table>

<?php 

}


if ($_acao == 44 ){ //exclui item chamada
				
	
	$codigo = $_parametros['_idexpeca'];
	$os = $_parametros['chamada'];


				$consulta = "DELETE FROM chamadapeca WHERE Numero_OS = '$os' AND Seq_item = '$codigo'";
				$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));


?>
											<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
												<thead>
													<tr>                                                       
														<th>Código</th>
														<th>Descrição</th>
														<th class="text-center">Qtde</th>
														<th class="text-center">Valor</th>                                                      
														<th class="text-center">Total</th>
														<th class="text-center">Técnico</th>
													
														<th class="text-center">Ação</th>
													</tr>
												</thead>
												<?php 
													$sql="Select Codigo_Peca_OS,Minha_Descricao,Qtde_peca,peca_mo,usuario_APELIDO,Seq_item
													 from " . $_SESSION['BASE'] . ".chamadapeca 
													left join " . $_SESSION['BASE'] . ".itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
													left join " . $_SESSION['BASE'] . ".situacaopeca ON sitpeca = sitpeca_id
													left join " . $_SESSION['BASE'] . ".usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
													where TIPO_LANCAMENTO = 1 and 	Numero_OS  = '$os' order by Seq_item ASC ";
												
													$consulta = $pdo->query($sql);	
													$executa = $consulta->fetchAll();
														foreach ($executa as $row) {	
														

														/*
															<td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
															<td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
															<td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
															<td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
															<td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
														*/
												?>
												<tr class="gradeX">
													<td><?=$row["Codigo_Peca_OS"]?></td>
													<td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
													<td class="text-center"><?=$row["Qtde_peca"]?></td>
													<td class="text-center"><?=number_format($row["peca_mo"],2,',','.')?></td>
													<td class="text-center"><?=number_format($row["Qtde_peca"]*$row["peca_mo"],2,',','.')?></td>    
													<td class="text-center"><?=$row["usuario_APELIDO"]?></td>
													
													<td class="text-center">
													
														<?php 
														if($_situacao < 90) { ?> 
													   <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','2')"><i class="fa fa-trash-o fa-2x"></i></a>
														<?php } ?> 
													</td>
												</tr>
											
												<?php
											} ?>
												</tbody>
											</table>

<?php 

}

//resumo

if ($_acao == 50 ){ //resumo chamada

	$os = $_parametros['chamada'];
	$tx= $_parametros['_vlrtaxa'];
	$desconto= $_parametros['_vlrdesconto'];
	$descontopeca= $_parametros['_vlrdescontopeca'];

	$vlrpeca = number_format(0,2,',','.');
	$vlrmaoobra = number_format(0,2,',','.');

	$vlrdescontopeca = str_replace(",", ".",  $descontopeca);
	$vlrdescontopeca = str_replace(",", ".",  $vlrdescontopeca);

	$vlrdesconto = str_replace(",", ".",  $desconto);
	$vlrdesconto = str_replace(",", ".",  $vlrdesconto);

	$vlrtaxa = str_replace(",", ".", $tx );
	$vlrtaxa = str_replace(",", ".",  $vlrtaxa);

													$sql="Select sum(peca_mo*Qtde_peca) as maoobra,	COUNT(Qtde_peca) as qtde																									  
														  from " . $_SESSION['BASE'] . ".chamadapeca 													
														  where TIPO_LANCAMENTO = 1 and	Numero_OS  = '$os' and Codigo_Peca_OS <> 2";												
														  $consulta = $pdo->query($sql);	
														  $executa = $consulta->fetchAll();
														  foreach ($executa as $row) {
															$qtmaoobra = $row['qtde'];
															$vlrmaoobra = number_format($row['maoobra'],2,',','.');
															$vlrtotal = $vlrtotal + $row['maoobra'];
													}
													//taxa 
													$sql="Select sum(peca_mo*Qtde_peca) as maoobra,	COUNT(Qtde_peca) as qtde																									  
													from " . $_SESSION['BASE'] . ".chamadapeca 													
													where TIPO_LANCAMENTO = 1 and	Numero_OS  = '$os' and Codigo_Peca_OS = 2";												
													$consulta = $pdo->query($sql);	
													$executa = $consulta->fetchAll();													
													foreach ($executa as $row) {
															
														$vlrtaxa =$vlrtaxa + $row['maoobra'];
													
													}

													$sql="Select
													sum(Valor_Peca*Qtde_peca) as pecas,
													COUNT(Qtde_peca) as qtde																										  
													from " . $_SESSION['BASE'] . ".chamadapeca 											  
													where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' ";
												
													$consulta = $pdo->query($sql);	
													$executa = $consulta->fetchAll();
													foreach ($executa as $row) {	
															$qtpecas = $row['qtde'];
															$vlrtotal = $vlrtotal + $row['pecas'];
															$vlrpeca = number_format($row['pecas'],2,',','.');
													}
													$vlrtotal  = $vlrtotal  + $vlrtaxa - $vlrdesconto - $vlrdescontopeca;

													$vlrtotal = number_format($vlrtotal,2,',','.');


												
													?>
  													<div class="row" style="height: 50px;   margin:5px">
                                                            <div class="col-sm-12" >
                                                            <h4 class="page-title m-t-15" style="color:#009fda; margin:10px" > Resumo Atendimento  </h4>                              
                                                            </div>                                                        
                                                        </div>
                                                        <div class="row" style=" background-color:#00a8e61f;  margin:10px">
                                                            <div class="col-sm-8" >
                                                                <strong>Descrição</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <strong>Valor</strong>
                                                            </div>
                                                        
                                                        </div>
                                                        <div class="row" style="margin:10px">
                                                            <div class="col-sm-8">
                                                            Total Peças<br>
                                                            Qtde <?=$qtpecas;?>
                                                            </div>
                                                            <div class="col-sm-4" style="text-align:right ;">
                                                            <br>
                                                            R$<strong> <?=$vlrpeca;?></strong>
                                                            </div>                           
                                                        </div>
                                                        
                                                        <div class="row" style="height: 5px;"> 
                                                            <div class="col-sm-11" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;   height:1px;   margin-left:20px">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin:10px">
                                                            <div class="col-sm-8">
                                                            Total Serviços <br>
                                                            Qtde <?= $qtmaoobra;?>
                                                            </div>
                                                            <div class="col-sm-4" style="text-align:right ;">
                                                            <br>
                                                            R$<strong> <?=$vlrmaoobra;?></strong>
                                                            </div>                           
                                                        </div>
														<div class="row" style="height: 5px;"> 
                                                            <div class="col-sm-11" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;   height:1px;   margin-left:20px">
                                                            </div>
                                                        </div>
														<div class="row" style="margin:10px">
                                                            <div class="col-sm-8">
                                                            Total Taxa 
                                                            
                                                            </div>
                                                            <div class="col-sm-4" style="text-align:right ;">
                                                       
                                                            R$<strong> <?=number_format($vlrtaxa,2,',','.');?></strong>
                                                            </div>                           
                                                        </div>
														<div class="row" style="margin:10px">
                                                            <div class="col-sm-8">
                                                            Desconto Peça (-)
                                                            
                                                            </div>
                                                            <div class="col-sm-4" style="text-align:right ;">
                                                           
                                                            R$<strong> -<?=number_format($vlrdescontopeca,2,',','.');;?></strong>
                                                            </div>                           
                                                        </div>
														<div class="row" style="margin:10px">
                                                            <div class="col-sm-8">
                                                            Desconto Serviço (-)
                                                            
                                                            </div>
                                                            <div class="col-sm-4" style="text-align:right ;">
                                                           
                                                            R$<strong> -<?=number_format($vlrdesconto,2,',','.');?></strong>
                                                            </div>                           
                                                        </div>
                                                        
                                                        <div class="row" style="height:3px;"> 
                                                            <div class="col-sm-11" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;  height:1px;   margin-left:20px">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="  margin:10px">
                                                            <div class="col-sm-8" >
                                                                <strong>TOTAL</strong>
                                                            </div>
                                                            <div class="col-sm-4" style="text-align:right ;">
                                                                <strong>R$ <?=$vlrtotal;?></strong>
                                                            </div>
                                                        
                                                        </div>
													<?php
													exit();

}


//pagamentos *****************************************************************************


if ($_acao == 21 or $_acao == 211 ){ //pagamentos incluir 211-prismmob

	
		$os = $_parametros['osfinan'];
		
	    $cliente = $_parametros['DOCidcliente'];
	
	    $condicao_pgto = $_parametros['condicao_pgto'];
		$vlr = str_replace(".", "", $_parametros['_vlrF']);
		$valor = str_replace(",", ".", $vlr);
	
		$parcela = $_parametros['_parcelaF'];

		$dia       = date('d'); 
		$mes       = date('m'); 
		$ano       = date('Y'); 
		$data_dia = $ano."-".$mes."-".$dia; 
		$data_atual = $dia."/".$mes."/".$ano; 

		//verificar se ja foi confirmado financeiro
		/*
		$sql="Select SituacaoOS_Elx,Ind_At_CR,sitelx_bloqueia,CODIGO_CONSUMIDOR																							  
		from chamada
		LEFT JOIN situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx											
		where CODIGO_CHAMADA  = '$os' ";		
										
		$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		while($row = mysqli_fetch_array($resultado)){			
				$sitbloqueia = $row['sitelx_bloqueia'] ; //1 bloqueado
				$indcr = $row['Ind_At_CR'];
				$cliente = $row['CODIGO_CONSUMIDOR'];


				if($indcr == '-1') { ?>
					<div class="row">        
								<div class="col-sm-8 ">
								<div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
													x
													</button>
													<span style="color:#6b1010"><?=nl2br("O.S já Confirmada Financeiro"); ?></span>
												</div>
								</div>
						</div>
				<?php
			
				}
		}
		*/
		$dias = 0;
		$sql="SELECT qt_parcelas,prz FROM tiporecebimpgto where id = '$condicao_pgto' " ; 
	  
		$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		 while($rst = mysqli_fetch_array($resultado)){
		 	$dias = $rst["prz"];
		 	$parcelas = $rst["qt_parcelas"];	  
		 }

		 $valor = $valor / $parcela;

		 if($parcela >  $parcelas ){
			 
			 ?>
			   <div class="row">        
                <div class="col-sm-8 ">
                <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                       x
                                    </button>
                                    <span style="color:#6b1010"><?=nl2br("Parcela não pode ser superior $parcelas"); ?></span>
                                </div>
                </div>
        </div>
			 <?php
			 $parcela = 0;		 
		 }

		
$i = 0;
	if($indcr != '-1') { 
		while($i < $parcela) {
				$i++; 
				
				$data12 = SomarData($data_atual, $dias, 0, 0); 
			
				$dia = substr("$data12",0,2); 
				$mes = substr("$data12",3,2); 
				$ano = substr("$data12",6,4); 
				$datavenc = $ano."-".$mes."-".$dia;
				$data_atual = "$dia/$mes/$ano";

				$consulta = "INSERT INTO pagamentos (pgto_tipo,pgto_documento,pgto_parcela,pgto_vencimento,pgto_valor,pgto_tipopagamento,pgto_usuario,pgto_cliente) values 
				('1','$os', '$i','$datavenc','$valor','$condicao_pgto','".$_SESSION['login']."','$cliente')";
				$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));   


		}
	}
	
	if ($_acao == 21){
			$_acao = 20;
	}else{
		$_acao = 201;	
	}

}


if ($_acao == 22 or $_acao == 222 ){ //pagamentos excluir 222 prismamob
	//*exclui registro   
	$os = $_parametros['osfinan'];
	$idexclusao = $_parametros['_idexpgto'];
	$consulta = "Delete from pagamentos where  pgto_id  = '$idexclusao' and pgto_documento 	 = '$os' ";
	$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));	
	if ($_acao == 22  ){ 
		$_acao = 20;
	}else{
		$_acao = 200;
	}
}

if ($_acao == 20 ){ //pagamentos listar
	?>
	<div class="row">
        <div class="col-sm-9" style="margin: 20px;">
						<div class="row">
                            <div class="col-sm-3 "><label>Selecione Tipo Pgto</label>
                                <?php
                                $query = ("SELECT nome,id  FROM tiporecebimpgto order by nome ");
                                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                                $TotalReg = mysqli_num_rows($result);
                                ?>

                                <select name="condicao_pgto" id="condicao_pgto" class="form-control input-sm" onchange="_totalparcelafin(this.value)">
                                    <option value=""></option>
                                    <?php
                                    if ($condicao_parcelas == "") {
                                        $condicao_parcelas = 4;
                                    }
                                    while ($resultado = mysqli_fetch_array($result)) {										
                                        $descricao = $resultado["nome"];
                                        $codigo = $resultado["id"]; ?>
                                        <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                    <?php

                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2" id="_rettipopgto">
                                <label>Parcela</label>
                                <select name="_parcelaF" id="_parcelaF" class="form-control input-sm" >
                                    <option value="1">1</option>                                 
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Valor</label>
                                <input type="text" class="form-control input-sm" name="_vlrF" id="_vlrF" value="">
                            </div>

                            <div class="col-sm-1 ">
                                <button id="cadastrar" type="button" style="margin-top:25px ;" class="btn btn-success waves-effect waves-light mb-auto" onclick="_adicionapgto()"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
						<div class="col-sm-12">
																
										<?php

									   $os = $_parametros['osfinan'];
										$descricao = $_parametros['desc'];
										$qtde = $_parametros['qtde'];
										$valor = str_replace(".", "", $_parametros['vlr']);
										$valor = str_replace(",", ".", $valor);	

										$sql="Select SituacaoOS_Elx,Ind_At_CR,sitelx_bloqueia,DESC_PECA,DESC_SERVICO,TAXA																							  
										from chamada
										LEFT JOIN situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx											
										where CODIGO_CHAMADA  = '$os' ";		
																		
										$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
										while($row = mysqli_fetch_array($resultado)){
											
												$sitbloqueia = $row['sitelx_bloqueia'] ; //1 bloqueado
												$indcr = $row['Ind_At_CR'];
												$descontopecas = $row['DESC_PECA'];
												$descontomo = $row['DESC_SERVICO'];
												$ttaxa = $row['TAXA'];
												
										}
										
										$sql="Select sum(peca_mo*Qtde_peca) as maoobra,sum(Valor_Peca*Qtde_peca) as pecas																								  
										from  " . $_SESSION['BASE'] . ".chamadapeca 													
										where 	Numero_OS  = '$os' ";												
										$consulta = $pdo->query($sql);	
										$executa = $consulta->fetchAll();
										foreach ($executa as $row) {
											
												$apagar = $row['pecas'] + $row['maoobra'];
												$_pecas = $row['pecas'];
										}
										$apagar = $apagar - $descontomo+$ttaxa;
										$_pecas = $_pecas - $descontopecas;

									?>
	
							<table id="datatable-responsive-financeiro" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="margin-top:10px;">
								<thead>
									<tr>
										<th>Qtde </th>
										<th>Vencimento</th>                                    
										<th>Valor</th>
										<th>Tipo Pgto</th>     
										<th>Ação</th>                                  
									</tr>
								</thead>

								<tbody id="tbody_pgto">
								<?php
									$sql="Select *, DATE_FORMAT(pgto_vencimento,'%d/%m/%Y') AS vencimento from pagamentos 
									left join tiporecebimpgto on id = pgto_tipopagamento  
									where 	pgto_documento = '$os' " ;            
								
									$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
									while($rst = mysqli_fetch_array($resultado)){
										$indpgto = $rst["pgto_indfinanceiro"];
										?>
									<tr id="_linha<?=$rst["pgto_id"];?>">
										<td><?=$rst["pgto_parcela"];?></td>
										<td><?=$rst["vencimento"];?></td>                                    
										<td><?=number_format(($rst["pgto_valor"]),2,',','.'); $tot = $tot +$rst["pgto_valor"]; ?></td>
										<td><?=$rst["nome"];?></td> 
										<td><?php 
											if($indpgto != '-1') {
												$libera = 1; ?>
												<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_editarfinan('_linha<?=$rst["pgto_id"];?>')"><i class="fa fa-pencil fa-1x"></i></a>
												<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluirpgto('<?=$rst["pgto_id"];?>','<?=$rst["nome"]?>')"><i class="fa fa-trash-o fa-1x"></i></a>
										<?php }
											else { 
												echo "Confirmado";
										}
										?> </td>                                         
									</tr>
										<?php

									}
								?>

								</tbody>
								</table>
					<div class="col-sm-12 " >
					<strong>A Pagar :</strong> R$ <?=number_format($apagar,2,',','.');?>  
					</div> 
					<div class="col-sm-12 " >
					<strong>Total Pago :</strong> R$ <?=number_format($tot,2,',','.');?>
					</div> 
					
					<?php
					if($libera = '1' ) { ?>
					<input type="hidden" id="id-filtro" name="id-filtro">
					<div class="col-sm-4 " style="text-align: left ;" >
							<button id="cadastrar"  type="button" style="margin-top:25px ;" class="btn btn-warning waves-effect waves-light mb-auto" onclick="_confpgto()"><i class="fa fa-plus"></i> Confirmar Financeiro</button>
					</div>							
							<div class="form-group col-xs-4">
                                        <label class="control-label" for="nf-grupo">Categoria:</label>
                                            <?php
											$_catpadrao = 1; //categoria padrao despesas

                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".categoria WHERE ativo_categoria = 0 and tipo_categoria <> '2' ORDER BY descricao_categoria");
                                            $retornoGr = $statement->fetchAll();
                                            ?>
                                            <select name="nf-grupo" id="nf-grupo" class="form-control" onchange="_buscaSelectCategoria(this.value, '#contas-despesa')" >
                                               
                                                <?php
                                                foreach ($retornoGr as $row) {
                                                    ?>
                                                    <option value="<?=$row["id_categoria"]?>" <?=$row["id_categoria"] == $_catpadrao ? "selected" : ""?>><?=$row["descricao_categoria"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-4" id="contas-despesa">
                                            <label class="control-label" for="nf-contdespesa">Sub Categoria:</label>
                                            <?php
											$_subcatpadrao = 1; //subcategoria padrao despesas
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".subcategoria WHERE  ref_subcategoria = '".$_subcatpadrao."' ORDER BY descricao_subcategoria");
                                            $retornoCd = $statement->fetchAll();
											
                                            ?>
                                            <select name="nf-contdespesa" id="nf-contdespesa" class="form-control" >
                                              
                                                <?php
                                               
                                                    foreach ($retornoCd as $row) {
                                                        ?>
                                                          <option value="<?=$row["id_subcategoria"]?>" <?=$row["id_subcategoria"] == $_subcatpadrao ? "selected" : ""?>><?=($row["descricao_subcategoria"])?></option>
                                                        <?php
                                                    }
                                               
                                                ?>
                                            </select>
                                        </div>
					<?php } 
					?><div class="col-sm-12 " style="text-align: left ;" ><?php
					if($libera == ''){
						echo "O.S Já confirmado finaceiro";
					}
					
					?>
					
					</div> 
					
				</div>
				
	
		</div>
		
        <div class="col-sm-2" style="margin: 10px;">
		
						<div class="row" >
							<div class=" col-sm-12">
							
							<div class="card-box" style="display: flex; justify-content: space-between; align-items: center;padding:10px">
								<button type="button" class="btn btn-warning waves-effect waves-light" onclick="_editarrecibo()">
									<i class="fa fa-edit m-r-5"></i>
								</button>
								<button type="button" class="btn btn-inverse waves-effect waves-light"  onclick="_printrecibo()">
									<i class="fa fa-print m-r-5"></i>Recibo
								</button>
							</div>
						</div>						
						<div class="row" >
							<div class=" col-sm-12" style="text-align: center;">
								<strong>Total Peças</strong><br> R$ <?=number_format($_pecas ,2,',','.');?>
							</div>
						</div>
						<div class="row" >
							<div class="card-box">						
								<button type="button" class="btn btn-block btn-white waves-effect waves-light" onclick="_gerarNFCe(2)"><i class=" ti-bookmark"></i> Gerar NFC-e </button>
								<button type="button" class="btn btn-block btn-white waves-effect waves-light" onclick="_gerarNFe(4)"><i class=" ti-bookmark-alt"></i> Gerar NF-e</button>
								<button type="button" class="btn btn-block btn-white waves-effect waves-light" onclick="_gerarNFse(7)"><i class="  ti-receipt"></i> Gerar NFS-e</button>
							</div>
						</div>											

		</div>
	</div>
		<div class="col-sm-12" id="divnfce"> 
							<table id="datatable-responsive-financeiro" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="margin-top:10px;">
								<thead>
									<tr>
										<th>Nº NF </th>										    
										<th>Dt Emissão</th>                                    
										<th>Valor</th>
										<th>Modelo</th>     
										<th>Imprimir</th>                                  
										<th>Cancelar</th> 
									</tr>
								</thead>

								<tbody id="tbody_pgto">
								<?php
									$sql="Select nfed_id,nfed_numeronf,nfed_chave, DATE_FORMAT(nfed_dataautorizacao,'%d/%m/%Y') AS autorizado,nfed_totalnota,nfed_modelo,nfed_arquivo from NFE_DADOS 									
									where 	nfed_chamada = '$os' " ; // and nfed_chave <> ''
									$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
									$_tnf = mysqli_num_rows($resultado);
									while($rst = mysqli_fetch_array($resultado)){
										$idnf = $rst["nfed_numeronf"];
										$idnfref = $rst["nfed_id"];
										$modelo = $rst["nfed_modelo"];
										if($modelo == 55) {
											$modelodesc = "NF-e";
										}elseif($modelo == 90) {
											$modelodesc = "NFS-e";
											$nfe_arquivo= $rst["nfed_arquivo"];
										}else{
											$modelodesc = "NFC-e";
										}
										
										?>
									<tr>
										<td><?=$rst["nfed_numeronf"];?></td>
										<td><?=$rst["autorizado"];?></td>                                    
										<td><?=number_format(($rst["nfed_totalnota"]),2,',','.'); ?></td>
										<td><?=$modelodesc;?></td>    
										<td><?php 
										if($modelo != '90') { ?>
											<button type="button" class="btn btn-sm btn-inverse waves-effect waves-light" onclick="_ImprimirNF('<?=$modelo;?>','<?=$idnf;?>')"><i class="fa fa-print m-r-5"></i>Imprimir</button>
											<?php } else { ?>
												<a href="<?=$nfe_arquivo;?>" target="_blank"><button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  ><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFS-e</button></a>
											<?php } ?>
										</td> 
										<td><?php if($rst['nfed_chave'] != "" and $modelo != '90') { ?>
											<button type="button" class="btn btn-sm btn-danger waves-effect waves-light" onclick="_cancelarNFCe('<?=$idnfref;?>')"><i class="fa  fa-ban"></i></button>
										</td>                                         
										<?php } ?>		</tr>
										<?php

									}
								?>

								</tbody>
								</table>
		<?php if($_tnf == 0){
			?>
			<div class="alert alert-info  alert-dismissable" style="text-align:right">                      
				Sem Notas Geradas </div>
			</div>
		<?php } 

}


if ($_acao == 201 ){ //pagamentos listar tracckmob
	?>
	<div class="row">
        <div class="col-sm-12" style="margin: 20px;">
						<div class="row">
                            <div class="col-sm-3  col-xs-12"><label>Selecione Tipo Pgto</label>
                                <?php
                                $query = ("SELECT nome,id  FROM tiporecebimpgto order by nome ");
                                $result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
                                $TotalReg = mysqli_num_rows($result);
                                ?>

                                <select name="condicao_pgto" id="condicao_pgto" class="form-control input-sm" onchange="_totalparcelafin(this.value)">
                                    <option value=""></option>
                                    <?php
                                    if ($condicao_parcelas == "") {
                                        $condicao_parcelas = 4;
                                    }
                                    while ($resultado = mysqli_fetch_array($result)) {										
                                        $descricao = $resultado["nome"];
                                        $codigo = $resultado["id"]; ?>
                                        <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                    <?php

                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2 col-xs-4" id="_rettipopgto">
                                <label>Parcela</label>
                                <select name="_parcelaF" id="_parcelaF" class="form-control input-sm" >
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                </select>
                            </div>
                            <div class="col-sm-2  col-xs-6">
                                <label>Valor</label>
                                <input type="text" class="form-control input-sm" name="_vlrF" id="_vlrF" value="">
                            </div>

                            <div class="col-sm-1   col-xs-2">
                                <button id="cadastrar" type="button" style="margin-top:25px ;" class="btn btn-success waves-effect waves-light mb-auto" onclick="_adicionapgto()"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
						<div class="col-sm-12">
																
										<?php

									   $os = $_parametros['osfinan'];
										$descricao = $_parametros['desc'];
										$qtde = $_parametros['qtde'];
										$valor = str_replace(".", "", $_parametros['vlr']);
										$valor = str_replace(",", ".", $valor);	

										$sql="Select SituacaoOS_Elx,Ind_At_CR,sitelx_bloqueia,DESC_PECA,DESC_SERVICO,TAXA																							  
										from chamada
										LEFT JOIN situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx											
										where CODIGO_CHAMADA  = '$os' ";		
																		
										$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
										while($row = mysqli_fetch_array($resultado)){
											
												$sitbloqueia = $row['sitelx_bloqueia'] ; //1 bloqueado
												$indcr = $row['Ind_At_CR'];
												$descontopecas = $row['DESC_PECA'];
												$descontomo = $row['DESC_SERVICO'];
												$ttaxa = $row['TAXA'];
												
										}
										
										$sql="Select sum(peca_mo*Qtde_peca) as maoobra,sum(Valor_Peca*Qtde_peca) as pecas																								  
										from  " . $_SESSION['BASE'] . ".chamadapeca 													
										where 	Numero_OS  = '$os' ";												
										$consulta = $pdo->query($sql);	
										$executa = $consulta->fetchAll();
										foreach ($executa as $row) {
											
												$apagar = $row['pecas'] + $row['maoobra'];
												$_pecas = $row['pecas'];
										}
										$apagar = $apagar - $descontomo+$ttaxa;
										$_pecas = $_pecas - $descontopecas;

									?>
	
							<table id="datatable-responsive-financeiro" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="margin-top:10px;">
								<thead>
									<tr>
										<th>Qtde </th>
										<th>Vencimento</th>                                    
										<th>Valor</th>
										<th>Pgto</th>     
										<th>Ação</th>                                  
									</tr>
								</thead>

								<tbody id="tbody_pgto">
								<?php
									$sql="Select *, DATE_FORMAT(pgto_vencimento,'%d/%m/%Y') AS vencimento from pagamentos 
									left join tiporecebimpgto on id = pgto_tipopagamento  
									where 	pgto_documento = '$os' " ;            
								
									$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
									while($rst = mysqli_fetch_array($resultado)){
										$indpgto = $rst["pgto_indfinanceiro"];
										?>
									<tr id="_linha<?=$rst["pgto_id"];?>">
										<td><?=$rst["pgto_parcela"];?></td>
										<td><?=$rst["vencimento"];?></td>                                    
										<td><?=number_format(($rst["pgto_valor"]),2,',','.'); $tot = $tot +$rst["pgto_valor"]; ?></td>
										<td><?=$rst["nome"];?></td> 
										<td><?php 
											if($indpgto != '-1') {
												$libera = 1; ?>
												
												<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluirpgto('<?=$rst["pgto_id"];?>','<?=$rst["nome"]?>')"><i class="fa fa-trash-o fa-2x"></i></a>
										<?php }
											else { 
												echo "Confirmado";
											}
										?> </td>                                         
									</tr>
										<?php

											}
								?>

								</tbody>
								</table>
					<div class="col-sm-12 " >
					<strong>A Pagar :</strong> R$ <?=number_format($apagar,2,',','.');?>  
					</div> 
					<div class="col-sm-12 " >
					<strong>Total Pago :</strong> R$ <?=number_format($tot,2,',','.');?>
					</div> 
				
					
				</div>
				
	
		</div>
		<?php
			

}


if ($_acao == 23 ){ //pagamentos confirmar financeiro

	$os = $_parametros['osfinan'];
	$id = $os;
	    $descricao = $_parametros['desc'];
	    $qtde = $_parametros['qtde'];
	   	$valor = str_replace(".", "", $_parametros['vlr']);
		$valor = str_replace(",", ".", $valor);	

		$dia       = date('d'); 
		$mes       = date('m'); 	  
		$ano       = date('Y'); 	  
		$hora = date ("H:i:s");	  
		$data      = $ano."-".$mes."-".$dia; 
	    $data2      = $ano."-".$mes."-".$dia." ".$hora; 
		 
	
	  $sql="Select pgto_id,pgto_parcela,pgto_valor,pgto_vencimento,pgto_tipopagamento,CODIGO_CONSUMIDOR,Nome_Consumidor,
	   DATE_FORMAT(pgto_vencimento,'%d/%m/%Y') AS vencimento from pagamentos left join tiporecebimpgto on id = pgto_tipopagamento 
	   left join consumidor on pgto_cliente = CODIGO_CONSUMIDOR  where 	pgto_documento = '$id' 
	   and pgto_tipo = '1' and pgto_indfinanceiro  = '0'" ;    
	
				$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
				while($rstP = mysqli_fetch_array($resultado)){
				$parcelas = $rstP['pgto_parcela'];
				$vlr = $rstP['pgto_valor'];
				$venc = $rstP['pgto_vencimento'];
				$cliente = $rstP['CODIGO_CONSUMIDOR'];
				$nome = $rstP['Nome_Consumidor'];
							  $consulta = "Select * from tiporecebimpgto where id = '".$rstP['pgto_tipopagamento']."'";
							$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
							while($rst = mysqli_fetch_array($executa))						
							  {  
								//$parcelas = $rst["QT_Parcelas"];		
								$liquida = $rst["Ind_liquida"];
								$livro = $rst["Num_Livro"];
								$portador = $rst["cod_Portador"];
								$juro = $rst["tx_juro"];
								$Linha = 0;
								
								//$qtde_parcela = $parcelas ;
								}
			   
						  if( $liquida == "S"){ $datap = $data; $situacao = 2; $vX = $vlr ; }else { $datap = "";$vX = ""; $situacao = 1;}

	  $financeiro_registro = 0;
	  $financeiro_parcela  = $parcelas;
	  $financeiro_totalParcela = $parcelas; 
	  $financeiro_codigoCliente  = $cliente;
	  $financeiro_nome = $nome;
	  $financeiro_portador   = 0; // nao usa
	  $financeiro_documento  = "$id";
	  $financeiro_historico  = "Ref. OS $id";
	  $financeiro_emissao  = "$data";
	  $financeiro_vencimento = $rstP['pgto_vencimento'];
	  $financeiro_vencimentoOriginal = $rstP['pgto_vencimento'];
	  $financeiro_valor = $vlr;
	  $financeiro_dataFim  = $datap;
	  $financeiro_valorFim = $vX;
	  $financeiro_valorJuros = $valorJuro; 
	  $financeiro_valorDesconto  = 0;
	  $financeiro_impresso   = 0; // nao usa
	  $financeiro_dias   = 0; // nao usa
	  $financeiro_referente   ="0"; // nao usa
	  $Documento  = $rstP['pgto_id'];;// pagamento id da OS
	  $financeiro_situacaoID = "0";// nao usa
	  $financeiro_obs = "";// nao usa
	  $financeiro_carencia  = "";// nao usa
	  $financeiro_comissaoPaga  = "0";// nao usa
	  $financeiro_valorEmpresa   = 0; // nao usa
	  $financeiro_tipoPagamento   = $rstP['pgto_tipopagamento'];
	  $financeiro_codBanco = "";// nao usa
	  $financeiro_agenciaBanco = "";// nao usa
	  $financeiro_contaBanco = "";// nao usa
	  $financeiro_numTituloBanco   = 0; // nao usa
	  $financeiro_motivo = "";// nao usa
	  $financeiro_valorSaldo  = 0; // nao usa
	  $financeiro_caixa   = 0; // nao usa
	  $financeiro_identificador  = 0;
	  $financeiro_lcLivroUsuarioLancamento = "";// nao usa
	  $financeiro_usuarioLancamento  = $_SESSION["login"];
	  $financeiro_empresa  = $_parametros['osempresa']; 
	  if($financeiro_empresa == '') { $financeiro_empresa = 0; }

	  $financeiro_pgtoParcial  = 0; // nao usa
	  $financeiro_grupo  = $_parametros['nf-grupo'];
	  if($financeiro_grupo == '') { $financeiro_grupo = 1; }
	  //  $INDENTIFICADOR = $_parametros['nf-contdespesa'];
	  $_subcatpadrao = $_parametros['nf-contdespesa'];
	  if($_subcatpadrao == '') { $_subcatpadrao = 1; }
	  $INDENTIFICADOR = $_subcatpadrao;
	//  $INDENTIFICADOR 
	//  $financeiro_lancamentoCaixa 
	//  $financeiro_nossoNumero 
	//  $financeiro_remessa 
	//  $financeiro_dataRemessa 
	 // $financeiro_descricaoBoleto 
	  $financeiro_hora  = $data2;
	  $financeiro_tipo  = '0';
	  $financeiro_subgrupo  = 1;
	  $financeiro_tipoQuem = 1;
	 // $financeiro_lancamentoCC
	$SQL = "INSERT INTO financeiro
	  (	financeiro_registro,
		  financeiro_parcela,
		  financeiro_totalParcela,
		  financeiro_codigoCliente,
		  financeiro_nome,
		  financeiro_portador,
		  financeiro_documento,
		  financeiro_historico,
		  financeiro_emissao,
		  financeiro_vencimento,
		  financeiro_vencimentoOriginal,
		  financeiro_valor,
		  financeiro_dataFim,
		  financeiro_valorFim,
		  financeiro_valorJuros,
		  financeiro_valorDesconto,
		  financeiro_impresso,
		  financeiro_dias,
		  financeiro_referente,
		  Documento,
		  financeiro_situacaoID,
		  financeiro_obs,
		  financeiro_carencia,
		  financeiro_comissaoPaga,
		  financeiro_valorEmpresa,
		  financeiro_tipoPagamento,
		  financeiro_codBanco,
		  financeiro_agenciaBanco,
		  financeiro_contaBanco,
		  financeiro_numTituloBanco,
		  financeiro_motivo,
		  financeiro_valorSaldo,
		  financeiro_caixa,
		  financeiro_identificador,
		  financeiro_lcLivroUsuarioLancamento,
		  financeiro_usuarioLancamento,
		  financeiro_empresa,
		  financeiro_pgtoParcial,
		  financeiro_grupo,
		  INDENTIFICADOR,
		  financeiro_lancamentoCaixa,
		  financeiro_nossoNumero,
		  financeiro_remessa,
		  financeiro_dataRemessa,
		  financeiro_descricaoBoleto,
		  financeiro_hora,
		  financeiro_tipo,
		  financeiro_subgrupo,
		  financeiro_tipoQuem,
		  financeiro_lancamentoCC
	  )
	  values(
		  '$financeiro_registro',
		  '$financeiro_parcela',
		  '$financeiro_totalParcela',
		  '$financeiro_codigoCliente',
		  '$financeiro_nome',
		  '$financeiro_portador',
		  '$financeiro_documento',
		  '$financeiro_historico',
		  '$financeiro_emissao',
		  '$financeiro_vencimento',
		  '$financeiro_vencimentoOriginal',
		  '$financeiro_valor',
		  '$financeiro_dataFim',
		  '$financeiro_valorFim',
		  '$financeiro_valorJuros',
		  '$financeiro_valorDesconto',
		  '$financeiro_impresso',
		  '$financeiro_dias',
		  '$financeiro_referente',
		  '$Documento',
		  '$financeiro_situacaoID',
		  '$financeiro_obs',
		  '$financeiro_carencia',
		  '$financeiro_comissaoPaga',
		  '$financeiro_valorEmpresa',
		  '$financeiro_tipoPagamento',
		  '$financeiro_codBanco',
		  '$financeiro_agenciaBanco',
		  '$financeiro_contaBanco',
		  '$financeiro_numTituloBanco',
		  '$financeiro_motivo',
		  '$financeiro_valorSaldo',
		  '$financeiro_caixa',
		  '$financeiro_identificador',
		  '$financeiro_lcLivroUsuarioLancamento',
		  '$financeiro_usuarioLancamento',
		  '$financeiro_empresa',
		  '$financeiro_pgtoParcial',
		  '$financeiro_grupo',
		  '$INDENTIFICADOR',
		  '$financeiro_lancamentoCaixa',
		  '$financeiro_nossoNumero',
		  '$financeiro_remessa',
		  '$financeiro_dataRemessa',
		  '$financeiro_descricaoBoleto',
		  '$financeiro_hora',
		  '$financeiro_tipo',
		  '$_subcatpadrao',
		  '$financeiro_tipoQuem',
		  '$financeiro_lancamentoCC'
	  )
	  ";

	  $executa = mysqli_query($mysqli,$SQL) or die(mysqli_error($mysqli));
				  
	  
					   
					if($liquida == "S") {
	  
						 $consulta = "insert into livro_caixa(Livro_Numero,Livro_caixa_historico,Livro_caixa_valor_entrada,Livro_caixa_valor_saida,
	  
						Livro_caixa_data_lancamento,Livro_caixa_data_hora_lancamento,Livro_caixa_usuario_lancamento,Livro_conta,Livro_caixa_Cod_Pagamento,Livro_Num_Docto,	Livro_idfinanceiro,Livro_caixa_motivo) values (
	  
						 '$livro','Ref. OS $id','$vlr','','$data','$data2','".$_SESSION["login"]."','48','".$rstP['pgto_tipopagamento']."','$id','1','6')";
	  
						   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
	  
					  }

					   $consulta = "update pagamentos set pgto_indfinanceiro = '-1' where pgto_id = '".$rstP['pgto_id']."' ";
							$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
						 
						  $consulta = "update chamada set Ind_At_CR = '-1',DATA_FINANCEIRO = CURRENT_DATE() where CODIGO_CHAMADA = '".$id."' ";
							$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
	  
			   
				}


		
		$sql="Select sum(peca_mo*Qtde_peca) as maoobra,sum(Valor_Peca*Qtde_peca) as pecas																								  
		from  " . $_SESSION['BASE'] . ".chamadapeca 													
		where 	Numero_OS  = '$os' ";												
		$consulta = $pdo->query($sql);	
		$executa = $consulta->fetchAll();
		foreach ($executa as $row) {				
				$apagar = $row['pecas'] + $row['maoobra'];
		}

	?>
			<table id="datatable-responsive-financeiro" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Qtde </th>
					<th>Vencimento</th>                                    
					<th>Valor</th>
					<th>Tipo Pgto</th>     
					<th>Ação</th>                                  
				</tr>
			</thead>

			<tbody id="tbody_pgto">
			<?php
				$sql="Select *, DATE_FORMAT(pgto_vencimento,'%d/%m/%Y') AS vencimento from pagamentos 
				left join tiporecebimpgto on id = pgto_tipopagamento  
				 where 	pgto_documento = '$os' " ;            
			
				$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
				while($rst = mysqli_fetch_array($resultado)){
					?>
				<tr>
					<td><?=$rst["pgto_parcela"];?></td>
					<td><?=$rst["vencimento"];?></td>                                    
					<td><?=number_format(($rst["pgto_valor"]),2,',','.'); $tot = $tot +$rst["pgto_valor"]; ?></td>
					<td><?=$rst["nome"];?></td> 
					<td> </td>                                         
				</tr>
					<?php

				}
			?>

			</tbody>
			</table>
			<div class="col-sm-12 " >
			<strong>A Pagar :</strong> R$ <?=number_format($apagar,2,',','.');?>  
			</div> 
			<div class="col-sm-12 " >
			  <strong>Total Pago :</strong> R$ <?=number_format($tot,2,',','.');?>
			</div> 
			<button type="button" class="btn btn-inverse waves-effect waves-light" onclick="_printrecibo()"><i class="fa  fa  fa-print m-r-5"></i>Imprimir Recibo</button>
			<?php
			

}

if ($_acao == 24 ){ //editar recibo]
	$os = $_parametros['osfinan'];
	//verificar se existe algum recibo
	$sqlrec="Select rec_valor,	rec_descricao from  recibo 
	where rec_OS =  '".$os."' order by rec_id DESC limit 1"  ;
		$resrec=mysqli_query($mysqli,$sqlrec) or die(mysqli_error($mysqli));
		while($rowrec = mysqli_fetch_array($resrec)){
			$rec_valor = number_format($rowrec["rec_valor"],2,',','.');
			$rec_desc = $rowrec['rec_descricao'] ;
		}

		if($rec_desc == "") { 
			//busca da OS
			$sql="Select SUM(pgto_valor) AS TOTAL from pagamentos 
			left join tiporecebimpgto on id = pgto_tipopagamento  
			 where 	pgto_documento = '$os'";
			 $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
			 while($row = mysqli_fetch_array($resultado)){
			
				 $rec_desc  = "Referente ao serviços executados na Ordem de Serviço número  $os";
				 $rec_valor =  number_format($row["TOTAL"],2,',','.');
				 }

		}
	?>
									<div class="card-box">
										<div class="row">
											<div class="col-sm-2">
                                                    <label>Valor Recibo R$</label>
                                                    <input type="text" class="form-control input-sm" name="_vlrrecibo" id="_vlrrecibo"  value="<?=$rec_valor;?>">
                                                </div>
										   </div>
                                        <div class="row">
                                            <div class="col-sm-12">                                               
                                                <label>Descrição Recibo</label>
                                                <textarea name="descricaoRecibo" rows="2" id="descricaoRecibo" class="form-control input-sm"><?=$rec_desc;?></textarea>
                                            </div>
                                            <div class="col-sm-1">
                                                <div style="margin-top: 5px ; text-align:center">
                                                    <button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda" name="btagenda" onclick="_salvarrecibo()">
                                                        Salvar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>		
                                    </div>
								<?php

}


if ($_acao == 25 ){ //editar recibo]
	$data_hora = date('Y-m-d H:i:s'); // Formato: "2025-01-07 14:30:45"

	$os = $_parametros['osfinan'];
	$_vlrrecibo = $_parametros['_vlrrecibo'];
	$_vlrrecibo = str_replace(".", "", $_vlrrecibo);
	$_vlrrecibo = str_replace(",", ".", $_vlrrecibo);		
	$descricaoRecibo = $_parametros['descricaoRecibo'];
	

		$consulta = "INSERT INTO recibo (rec_dthora,rec_OS,rec_valor,rec_usuario,rec_descricao) 	values 
		('$data_hora','$os','$_vlrrecibo','". $_SESSION['tecnico']."' ,'$descricaoRecibo')";
	   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 

}
  ?> 