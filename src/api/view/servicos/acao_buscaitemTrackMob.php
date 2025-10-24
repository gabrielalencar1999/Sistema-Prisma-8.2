<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

if (isset($_COOKIE['Cookiemob_base']) and $_SESSION['BASE'] != "") {
    $_SESSION['BASE'] = $_COOKIE["Cookiemob_base"];
    $_SESSION['tecnico'] =$_COOKIE["Cookiemob_tec"];
}


$_acao = $_POST["acao"];

$_descricao = ($_parametros["busca-produto"]);
$_filtro = $_parametros["filtrarbusca"];
if($_filtro == "") { 
	$_filtro =  $_parametros["filtrarbuscaservico"];
}

$_descricao = str_replace("*","%",$_descricao);

$usuarioss = $_SESSION['tecnico'];

if($_filtro == "codigobarra") { $filtrar = "CODIGO_FABRICANTE = '$_descricao' OR CODIGO_FABRICANTE = '$_descricao'";}
if($_filtro == "codigo") { $filtrar = "Codigo_Item = '$_descricao'";}
if($_filtro == "descricao") { $filtrar = "DESCRICAO like '%$_descricao%'";}
if($_filtro == "modelo") { $filtrar = "Nome_Modelo = '%$_descricao%'";}
if($_filtro == "endereco") { $filtrar = "itemestoque.ENDERECO1 = '$_descricao' OR itemestoque.ENDERECO2 = '$_descricao'";}
	
function SomarData($data, $dias, $meses, $ano)
{
   /*www.brunogross.com*/
   //passe a data no formato dd/mm/yyyy 
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
     $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

date_default_timezone_set('America/Sao_Paulo');


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;




$consulta = "Select * from parametro ";
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
		$num_rows = mysqli_num_rows($executa);
		   if($num_rows!=0)
			{
				while($rst = mysqli_fetch_array($executa))	{
        $_vizCodInterno = $rst["empresa_vizCodInt"];
        if( $_vizCodInterno == 1) {
          $_codviewer = "CODIGO_FABRICANTE";
        }else{
          $_codviewer = "CODIGO_FORNECEDOR";
        }
	}
}


if($_acao == 1) {
	
	
	?>
 <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                            <thead>
                                <tr>
								    
                                    <th>Cod.</th>
									<th>Cod Barra/Fornec.</th>
									<th>Descrição</th>                                                                       
                                    <th>Valor</th>
                                    <th>Estoque</th>
                                    <th>End 1</th>
                                    <th>End 2</th>
									<th style="text-align:center">Ação</th>
                                </tr>
                            </thead>
                            
                            <tbody id="tbody_item">
<?php
	$sql="Select sum(itemestoquealmox.Qtde_Disponivel) as tot_item,DESCRICAO,CODIGO_FORNECEDOR,CODIGO_FORNECEDOR,Tab_Preco_5,
	CODIGO_FABRICANTE,Codigo_Barra,Codigo_Item,Nome_Modelo,itemestoque.ENDERECO1 as ender1,itemestoque.ENDERECO2 as ender2
	 from itemestoque
	inner join itemestoquealmox  ON  Codigo_Item = CODIGO_FORNECEDOR WHERE  $filtrar group by DESCRICAO,CODIGO_FORNECEDOR,CODIGO_FORNECEDOR,Tab_Preco_5,
	CODIGO_FABRICANTE,Codigo_Barra,Codigo_Item,Nome_Modelo,itemestoque.ENDERECO1,itemestoque.ENDERECO2 limit 500" ;   

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
		   ?>
		    <tr>
		   <td><?=$_codigoint;?></td>
		   <td><?=$_fornecedor;?></td>
		   <td><?=$_descricao;?></td>                                    
		  
		   <td style="text-align:center ;"><?=$_preco;?></td>
		   <td style="text-align:center ;"><?=$QTDE;?></td>
		   <td style="text-align:center ;"><?=$_end1;?></td>
		   <td style="text-align:center ;"><?=$_end1;?></td>
		   <td style="text-align:center ;"><a href="javascript:void(0);" class="on-default" onclick="_idprodutosel('<?=$_codigoint;?>')"><i class="fa fa-check fa-1x"></i></a></td>

		   
	   </tr>
	   <?php		  
	   }
	   ?>	
							</tbody>
 </table>  
	   								
	   <?php
}

if ($_acao == 2 ){ //buscar dados produtos POR CODIGO INTERNO
	$_descricao = utf8_decode($_parametros["_keyidpesquisa"]);
    $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
                         from itemestoque 
                         WHERE CODIGO_FORNECEDOR= '".$_descricao."' and Codigo <> '' AND GRU_GRUPO <> '900' OR 
						CODIGO_FABRICANTE = '".$_descricao."' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO <> '900'";
				
    $resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
   print_r(json_encode(mysqli_fetch_array($resultado)));
   
}


if ($_acao == 3 ){ //incluir item chamada
		//inserir registro	
		
		$codigo = $_parametros['_codpesq'];
		$os = $_parametros['_idos'];
	    $descricao = $_parametros['_desc'];
	    $qtde = $_parametros['_qtde'];
	   	$valor = str_replace(".", "", $_parametros['_vlr']);
		$valor = str_replace(",", ".", $valor);		
		$almoxarifado = $_parametros['_almox'];
		$valor_mo = 0;

		$total = $valor * $qtde;

		if($descricao == "Código Invalido") { 

		}else { 

			//buscar almox do tecnico
			
			$sql="Select usuario_almox		
			from usuario where usuario_CODIGOUSUARIO = '".$_SESSION['tecnico']."' ";          
			  $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
				  while($pegar = mysqli_fetch_array($resultado)){
					 $almoxarifado = $pegar["usuario_almox"];
					
				}
if($almoxarifado == '' or $almoxarifado == '0') {
	?>
				<div class="row"> 
                            <div class="alert alert-danger alert-dismissable">                      
                          Não foi selecionado Almoxarifado para seu usuário
                            </div>
                        </div>
	<?php
}elseif(trim($descricao) == ""){
	?>
				<div class="row"> 
               
						 <div class="alert alert-danger alert-dismissable">                      
						 Informe código válido !!! </div>
                            </div>
                       
	<?php

}else{

	//verificar se ja existe lançamento
	$sql="Select Numero_OS from chamadapeca	
	where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' and Codigo_Peca_OS = '$codigo' ";
	$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
	
	if(mysqli_num_rows($resultado) > 0){
		$msg = "Peça $descricao já incluída !!! ";
		?>
				<div class="row"> 
               
						 <div class="alert alert-danger alert-dismissable">                      
						<?=$msg ;?> </div>
                            </div>
                       
	<?php

	}else{


			  $sql="Select CODIGO_FORNECEDOR,PRECO_CUSTO,GRU_GRUPO,	Tab_Preco_5	 from itemestoque where CODIGO_FORNECEDOR = '$codigo' ";          
			  $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
				  while($pegar = mysqli_fetch_array($resultado)){
					 $custo = $pegar["PRECO_CUSTO"];
					 $valorunit = $pegar["Tab_Preco_5"];
					 $grupo = $pegar['GRU_GRUPO'];
				}

	  			$consulta = "INSERT INTO chamadapeca (Numero_OS,Codigo_Almox,Codigo_Peca_OS,Valor_Peca,Qtde_peca,Minha_Descricao,
						chamada_custo,peca_tecnico,peca_mo,Data_entrada,user_entrada) 	values 
						('$os','$almoxarifado','$codigo','$valor','$qtde','$descricao','$custo','0','$valor_mo', current_date(),'". $_SESSION['tecnico']."' )";
					   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));   

	}

		}
	}
	
	?>
												<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                    <thead>
                                                        <tr>                                                       
                                                            <th>Código</th>
															<th>Cod.Fabricante</th>
                                                            <th style="width:100px ;">Descrição</th>
                                                            <th class="text-center">Qtde</th>
                                                            <th class="text-center">Valor</th>                                                      
                                                            <th class="text-center">Total</th>														
                                                            <th class="text-center">Estoque</th>															
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select 
														CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
                                                ,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc 
														from chamadapeca 
                                                        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join situacaopeca ON sitpeca = sitpeca_id
                                                        left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' order by Seq_item ASC LIMIT 100";                                                    
                                                        $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                        while($row = mysqli_fetch_array($resultado)){
                
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
														<td class="text-center"><?=$_qtdematriz;?></td>														
                                                       
                                                        <td class="text-center">
															<a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                            <?php 
                                                            if($_situacao < 90) { ?>
                                                            <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                            <?php } ?> 
                                                        </td>
                                                    </tr>
                                                
                                                    <?php
                                                } ?>
                                                    </tbody>
                                                </table>

<?php 

	}

	

	if ($_acao == 4 ){ //exclui item chamada
		
		$codigo = $_parametros['_idexpeca'];
		$os = $_parametros['_idos'];
	
		$sql="SELECT Codigo_Peca_OS,Qtde_peca,Codigo_Almox,Ind_Baixa_Estoque FROM chamadapeca WHERE Numero_OS = '$os' AND Seq_item = '$codigo'";
		$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
			while($rstQ = mysqli_fetch_array($resultado)){
				$qtde = $rstQ["Qtde_peca"];
				$almoxarifado =  $rstQ["Codigo_Almox"];
				$Ind_Baixa_Estoque =  $rstQ["Ind_Baixa_Estoque"];
				$codigopeca = $rstQ["Codigo_Peca_OS"];
				
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
						
					
							$consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
											Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,
											Codigo_Chamada )
											values ( '$codigopeca',
											'$qtde','$almoxarifado','S','O','$os','0','0','','".$usuarioss."','Exclusão por OS ','$qtde_atual',now(),'$os')";
											$executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));
					
		}
					$consulta = "DELETE FROM chamadapeca WHERE Numero_OS = '$os' AND Seq_item = '$codigo'";
					$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
	
	
	?>
												<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                    <thead>
                                                        <tr>                                                       
															<th>Código</th>
															<th>Cod.Fabricante</th>
                                                            <th style="width:100px ;">Descrição</th>
                                                            <th class="text-center">Qtde</th>
                                                            <th class="text-center">Valor</th>                                                      
                                                            <th class="text-center">Total</th>														
                                                            <th class="text-center">Estoque</th>															
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
														,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc  from chamadapeca 
                                                        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join situacaopeca ON sitpeca = sitpeca_id
                                                        left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and 	Numero_OS  = '$os' order by Seq_item ASC ";
                                                    
                                                        $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                        while($row = mysqli_fetch_array($resultado)){
                                                            

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
														<td class="text-center"><?=$_qtdematriz;?></td>														
                                                       
                                                        <td class="text-center">
														<a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                            <?php 
                                                            if($_situacao < 90) { ?>
                                                            <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
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
	
	$os = $_parametros['_idos'];

	
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
															<th class="text-center">End 1</th>
                                                            <th class="text-center">End 2</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                        $sql="Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
														,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc  from chamadapeca 
                                                        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                        left join situacaopeca ON sitpeca = sitpeca_id
                                                        left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                        where TIPO_LANCAMENTO = 0 and 	Numero_OS  = '$os' order by Seq_item ASC ";
                                                    
                                                        $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                        while($row = mysqli_fetch_array($resultado)){
                                                            

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
														<td class="text-center"><?=$row["ENDERECO1"]?></td>
                                                        <td class="text-center"><?=$row["ENDERECO2"]?></td>
														<td class="text-center">
                                                            <a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                            <?php 
                                                            if($_situacao < 90) { ?>
                                                           <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
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


	$idpeca = $_parametros['_idexpeca'];
	$os = $_parametros['_idos'];

	$sql="Select *,
	date_format(Data_entrada, '%d/%m/%Y') as dtentrada,
	date_format(Data_baixa, '%d/%m/%Y') as dtbaixa,
	A.usuario_APELIDO as userA,
	B.usuario_APELIDO as userB
	 from chamadapeca 
	left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 	
	left join usuario as A on user_entrada = A.usuario_CODIGOUSUARIO
	left join usuario as B on user_baixa = B.usuario_CODIGOUSUARIO
	where Seq_item = '$idpeca' and 	Numero_OS  = '$os'  ";

	$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
	while($row = mysqli_fetch_array($resultado)){
		$reservado = $row['reserva'] ;
		$reserva_solicitacao = $row['reserva_solicitacao'] ;
		
		//buscar estoque total 
		$sq = "Select * from itemestoquealmox		
		where Codigo_Item = '".$row['CODIGO_FORNECEDOR']."' and  Codigo_Almox = 1 ";
		$res=mysqli_query($mysqli,$sq) or die(mysqli_error($mysqli));
		while($linha = mysqli_fetch_array($res)){
			$est = $linha ['Qtde_Disponivel'];
		}

		$_cod = $row[$_codviewer];
		$_qtsolicitada = $row['Qtde_peca'];

		$_codpeca  = $row['CODIGO_FORNECEDOR'];
		$_codpecafab = $row['CODIGO_FABRICANTE'];
	
	?>
	<table class="table table-bordered m-0">
                                          
                                            <tbody>
                                                <tr style="text-align:left ;">
													<td>Código Interno</td>
                                                    <td><?=$row['CODIGO_FORNECEDOR'];?> </td>													
                                                </tr>
												<tr style="text-align:left ;">
													<td>Cód.Fabricante</td>
                                                    <td><?=$row['CODIGO_FABRICANTE'];?> </td>																									
                                                </tr>
												<tr style="text-align:left ;">																								
													<td>Cod.Substituto</td>
                                                    <td><?=$row['CODIGO_SIMILAR'];?> </td>
                                                </tr>
												<tr style="text-align:left ;">
													<td >Descrição</td>
                                                    <td  ><?=$row['DESCRICAO'];?></td>
                                                </tr>
												
												<tr style="text-align:left ;">
													<td  >Modelo Aplicado</td>
                                                    <td  ><?=$row['MODELO_APLICADO'];?></td>
                                                </tr>
											
												<tr style="text-align:left ;">
													<td >Est Reservado</td>
                                                    <td> 
														<?php
														 $estR = $est; 
														$consulta = "Select reserva,Numero_OS,DATE_FORMAT(Data_entrada,'%d/%m/%Y') as dt	
														from chamadapeca 
													    where Codigo_Peca_OS = '".$row['CODIGO_FORNECEDOR']."'  and reserva > 0  ";
													
														$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
														$num_rows = mysqli_num_rows($executa);
															if($num_rows!=0)
															{
															
																while($st = mysqli_fetch_array($executa))						
																{
																	?>
																<div class="row" >                                                        
																	<div class="col-sm-8 col-xs-8" > OS <?=$rst["Numero_OS"];?>
																	</div>
																	<div class="col-sm-4 col-xs-4" > <?=$rst["reserva"];?>
																	</div>
																</div>
																
																<?php $estR = $estR -$rst["reserva"]; } 
															}else{
																echo " - ";
															} ?>
													</td>
												
                                                </tr>
												<tr style="text-align:left ;">
													<td >Estoque </td>
                                                    <td>Disponivel <strong>(<?=$estR;?>)</strong>  Total:(<?=$est;?>)</td>
													
                                                </tr>
												<tr style="text-align:center ;">
													<td colspan="2" >
													 <?php if($reservado > 0) { ?> 											
														<div class="alert-danger" style="margin: 10px ;"><p><stron>Reservado</strong></p> </div>
														<?php }else { 
															if($reserva_solicitacao == 1) { 
																echo "<strong> * Solicitado Reserva, aguardando liberação *</strong> "; }else{ ?>
															<div id="pcreserva">
																<button type="button"  class="btn   btn-warning btn-md waves-effect" tabindex="2" 
																style="display: inline-block;" onclick="_reserva('<?=$idpeca ;?>','<?=$os;?>','<?=$_codpecafab;?>','<?=$_qtsolicitada;?>','<?=$_SESSION['tecnico'];?>','<?=$_codpeca;?>')" >Solicitar Reserva</button>	
															</div>
													 <?php	}} ?>
														
													</td>
                                                   </tr>
												
											
                                               
                                            </tbody>
                                        </table>
<?php }  
					
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
	$_descricao = utf8_decode($_parametros["_keyidpesquisa"]);
    $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
                         from itemestoque 
                         WHERE CODIGO_FORNECEDOR= '".$_descricao."' and Codigo <> '' AND GRU_GRUPO = '900' OR 
						       CODIGO_FABRICANTE = '".$_descricao."' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO = '900'";
							 
    $resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
    print_r(json_encode(mysqli_fetch_array($resultado)));
   
}

if ($_acao == 13 ){ 
	//inserir registro	
	
	$codigo = $_parametros['_codpesqS'];
	$os = $_parametros['_idos'];
	$descricao = $_parametros['_descS'];
	$qtde = 1;
	$valor = str_replace(".", "", $_parametros['_vlrS']);
	$valor_mo = str_replace(",", ".", $valor);		
	$tecnico = $_SESSION['tecnico'];

	if($descricao == "Código Invalido") { 

	}else { 
		
  
		$consulta = "INSERT INTO chamadapeca (Numero_OS,Codigo_Almox,Codigo_Peca_OS,Valor_Peca,Qtde_peca,Minha_Descricao,
					chamada_custo,peca_tecnico,peca_mo,Data_entrada,	TIPO_LANCAMENTO,user_entrada) 	values 
					('$os','0','$codigo','$valorunit','$qtde','$descricao','$custo','$tecnico','$valor_mo', current_date(),'1','". $_SESSION['tecnico']."' )";
				   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));   


	}


?>
											<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
												<thead>
													<tr>                                                       
														<th>Código</th>
														<th>Descrição</th>
														<th class="text-center">Qtde</th>
														<th class="text-center">Valor</th>                                                      
														<th class="text-center">Total</th>																											
														<th class="text-center">Ação</th>
													</tr>
												</thead>
												<?php 
													$sql="Select Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,peca_mo,usuario_APELIDO from chamadapeca 
													left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
													left join situacaopeca ON sitpeca = sitpeca_id
													left join usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
													where TIPO_LANCAMENTO = 1 and	Numero_OS  = '$os' order by Seq_item ASC";
												
													$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
													while($row = mysqli_fetch_array($resultado)){
														

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
													<td class="text-center">
														
														<?php 
														if($_situacao < 90) { ?>
														<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','2')"><i class="fa fa-trash-o fa-2x"></i></a>
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
	$os = $_parametros['_idos'];


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
													$sql="Select Seq_item,Codigo_Peca_OS,Minha_Descricao,Qtde_peca,peca_mo,usuario_APELIDO from chamadapeca 
													left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
													left join situacaopeca ON sitpeca = sitpeca_id
													left join usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
													where TIPO_LANCAMENTO = 1 and 	Numero_OS  = '$os' order by Seq_item ASC ";
												
													$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
													while($row = mysqli_fetch_array($resultado)){
														

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
													   <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','2')"><i class="fa fa-trash-o fa-2x"></i></a>
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
	

	$os = $_parametros['_idos'];
	$tx= $_parametros['_vlrtaxa'];
	$_opcao = $_parametros['opcaotaxa']; //soma ou desconto
	//$desconto= $_parametros['_vlrdesconto'];

	$vlrpeca = number_format(0,2,',','.');
	$vlrmaoobra = number_format(0,2,',','.');

	$desconto = 0;

if($_opcao == "op2") { 
	$desconto = $tx;
	$tx = 0;
}

	$vlrdesconto = str_replace(",", ".",  $desconto);
	$vlrdesconto = str_replace(",", ".",  $vlrdesconto);

	$vlrtaxa = str_replace(",", ".", $tx );
	$vlrtaxa = str_replace(",", ".",  $vlrtaxa);

													$sql="Select sum(peca_mo*Qtde_peca) as maoobra,	COUNT(Qtde_peca) as qtde																									  
														  from chamadapeca 													
														  where TIPO_LANCAMENTO = 1 and	Numero_OS  = '$os' ";												
													$resultado=mysqli_query($mysqli,$sql) ;
													while($row = mysqli_fetch_array($resultado)){
															$qtmaoobra = $row['qtde'];
															$vlrmaoobra = number_format($row['maoobra'],2,',','.');
															$vlrtotal = $vlrtotal + $row['maoobra'];
													}

													$sql="Select
													sum(Valor_Peca*Qtde_peca) as pecas,
													COUNT(Qtde_peca) as qtde																										  
													from chamadapeca 											  
													where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' ";
												
													$resultado=mysqli_query($mysqli,$sql) ;
													while($row = mysqli_fetch_array($resultado)){
															$qtpecas = $row['qtde'];
															$vlrtotal = $vlrtotal + $row['pecas'];
															$vlrpeca = number_format($row['pecas'],2,',','.');
													}
													$vlrtotal  = $vlrtotal  + $vlrtaxa - $vlrdesconto;

													$vlrtotal = number_format($vlrtotal,2,',','.');


												
													?>
													 <div class="row" style=" background-color:#00a8e61f; ">
                                                            <div class="col-sm-5 col-xs-5">
                                                                <strong>Descrição</strong>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-3" style="text-align:center ;">
                                                                <strong>Qtde</strong>
                                                            </div>
                                                            <div class="col-sm-4 col-xs-4" style="text-align:center ;">
                                                                <strong>Valor R$</strong>
                                                            </div>                                                        
                                                        </div>
                                                        <div class="row" >
                                                            <div class="col-sm-5  col-xs-5">                                                            
                                                               Total Itens
                                                            </div>
                                                            <div class="col-sm-3  col-xs-3" style="text-align:center ;">                                                            
																<?=$qtpecas;?>
                                                            </div>
                                                            <div class="col-sm-4  col-xs-4" style="text-align:center ;">                                                            
																R$<strong> <?=$vlrpeca;?></strong>
                                                             </div>                           
                                                        </div>
                                                                                                             
														<div class="row" style="height: 5px;"> 
                                                            <div class="col-sm-12" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;   height:1px;  ">
                                                            </div>
                                                        </div>

                                                        <div class="row" >
                                                            <div class="col-sm-5  col-xs-5">                                                            
                                                               Total Serviços
                                                            </div>
                                                            <div class="col-sm-3  col-xs-3" style="text-align:center ;">                                                            
																<?= $qtmaoobra;?>
                                                            </div>
                                                            <div class="col-sm-4  col-xs-4" style="text-align:center ;">                                                            
																R$<strong> <?=$vlrmaoobra;?></strong>
                                                             </div>                           
                                                        </div>
                                                                                                             
														
                                                  
													<?php 	if($vlrtaxa > 0) { ?>
														<div class="row" style="height: 5px;"> 
                                                            <div class="col-sm-12" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;   height:1px;   ">
                                                            </div>
                                                        </div>
													
                                                        <div class="row" >
                                                            <div class="col-sm-5  col-xs-5">                                                            
                                                               Taxa
                                                            </div>
                                                            <div class="col-sm-3  col-xs-3" style="text-align:center ;">                                                            
                                                               
                                                            </div>
                                                            <div class="col-sm-4  col-xs-4" style="text-align:center ;">                                                            
																R$<strong> <?=number_format($vlrtaxa,2,',','.');?></strong>
                                                             </div>                           
                                                        </div>
													<?php }
													
													if($vlrdesconto > 0) { ?>
							<div class="row" style="height: 5px;"> 
                                                            <div class="col-sm-12" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;   height:1px;   ">
                                                            </div>
                                                        </div>
													
                                                        <div class="row" >
                                                            <div class="col-sm-5  col-xs-5">                                                            
                                                               Desconto
                                                            </div>
                                                            <div class="col-sm-3  col-xs-3" style="text-align:center ;">                                                            
                                                               
                                                            </div>
                                                            <div class="col-sm-4  col-xs-4" style="text-align:center ;">                                                            
																R$ -<strong> <?=number_format($vlrdesconto,2,',','.');?></strong>
                                                             </div>                           
                                                        </div>
													<?php }
													?>
                                                        
                                                        <div class="row" style="height:3px;"> 
                                                            <div class="col-sm-12" style=" border:none; border-top:1px dotted #dbdbdb; color:#dbdbdb;  background-color:#dbdbdb;  height:1px;   ">
                                                            </div>
                                                        </div>
                                                        <div class="row" >
                                                            <div class="col-sm-8 col-xs-8">
                                                                <strong>TOTAL</strong>
                                                            </div>
                                                            <div class="col-sm-4 col-xs-4" style="text-align:center ;">
																<strong>R$ <?=$vlrtotal;?></strong>
                                                            </div>
                                                        
                                                        </div>  
														
														<?php  if($_SESSION['per157'] == '157') {  //permisso para incluir pagamentos?>
																<div class="row"  >
																	<div class="col-sm-12 col-xs-12">                                                                    
																
																		<button type="button" class="btn btn-tumblr  btn-block waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-pagamento"   onclick="_financeiro()">
																			<span class="btn-label"><i class="ion-card "></i> </span>PAGAMENTOS
																		</button>
																	</div>
																</div> 
														<?php  } ?>
													<?php
													exit();

}


if ($_acao == 61 ){  //salvar reserva
	echo "Incluído Reserva";
	$idpeca = $_parametros['_idexpeca'];
	$_var = explode(";",$idpeca);
	//print_r($idpeca = $_parametros['_idexpeca']);

	$sql="UPDATE  chamadapeca SET reserva_solicitacao = 1, reserva = '".$_var[3]."' 
				  WHERE Seq_item  =  '".$_var[0]."'";
			  	  $resultado=mysqli_query($mysqli,$sql) ;
				  $_text = "Reservado: OS ".$_var[1]."- cod.".$_var[2]." Qtd:".$_var[3];
				  $assunto = "Reservado";					 

				  $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica + ".$_var[3]." 
						 where CODIGO_FORNECEDOR  = '" . $_var[5] . "'	" ;		
				 
				   $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));			  

	$sql="INSERT INTO `notificacao` (`not_id`, `not_data`, `not_hora`, `not_setor`, `not_usuario`, `not_mensagem`, `not_lido`, `not_datalido`)
				  VALUES (NULL, CURRENT_DATE(), '$datahora', '6', '".$_var[4]."', '$_text ', '0', '0-0-0');";					
				  $resultado=mysqli_query($mysqli,$sql) ;
	
	$sql="INSERT INTO `notificacao` (`not_id`, `not_data`, `not_hora`, `not_setor`, `not_usuario`, `not_mensagem`, `not_lido`, `not_datalido`)
				  VALUES (NULL, CURRENT_DATE(), '$datahora', '5', '".$_var[4]."', '$_text ', '0', '0-0-0');";					
				  $resultado=mysqli_query($mysqli,$sql) ;				  
}


  ?> 