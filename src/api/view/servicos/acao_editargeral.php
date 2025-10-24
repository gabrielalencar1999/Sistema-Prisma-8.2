<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();


$_acao = $_POST["acao"];



if($_acao == 50 ) {   //editar parcela financeiro da O.S


		$id = str_replace('_linha','',$_parametros['idselfinan']);	

		$sql="Select *  from " . $_SESSION['BASE'] . ".pagamentos 
										left join " . $_SESSION['BASE'] . ".tiporecebimpgto on id = pgto_tipopagamento  
										where 	pgto_id = '$id' " ;     
										
										$stm = $pdo->prepare("$sql");
										$stm->execute();
									
									
										while ($rst = $stm->fetch()) 
											{
												?>												
													<td><?=$rst["pgto_parcela"];?></td>
													<td><input type="date" id="_venc<?=$id;?>"  name="_venc<?=$id;?>"  value="<?=$rst["pgto_vencimento"];?>"></td>                                    
													<td><?=number_format(($rst["pgto_valor"]),2,',','.'); $tot = $tot +$rst["pgto_valor"]; ?></td>
													<td><?=$rst["nome"];?></td> 
													<td><?php 
														if($indpgto != '-1') {
															$libera = 1; ?>
															<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_salvarfinan('_linha<?=$rst["pgto_id"];?>')"><i class="fa fa-floppy-o fa-1x"></i></a>
															<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluirpgto('<?=$rst["pgto_id"];?>','<?=$rst["nome"]?>')"><i class="fa fa-trash-o fa-1x"></i></a>
													<?php }
														else { 
															echo "Confirmado";
													}
													?> </td>                                         
											
													<?php

											}
}

if($_acao == 51 ) {   //salvar parcela financeiro da O.S


		$id = str_replace('_linha','',$_parametros['idselfinan']);	
		$venc = '_venc'.$id;
		$venc = str_replace('_venc','',$_parametros[$venc]);	

		$sql="UPDATE " . $_SESSION['BASE'] . ".pagamentos  SET pgto_vencimento = '".$venc."' WHERE pgto_id = '$id' LIMIT 1";			
		$stm = $pdo->prepare("$sql");
		$stm->execute();
		
		$sql="Select *, DATE_FORMAT(pgto_vencimento,'%d/%m/%Y') AS vencimento from " . $_SESSION['BASE'] . ".pagamentos 
										left join " . $_SESSION['BASE'] . ".tiporecebimpgto on id = pgto_tipopagamento  
										where 	pgto_id = '$id' " ;     
										
										$stm = $pdo->prepare("$sql");
										$stm->execute();
									
									
										while ($rst = $stm->fetch()) 
											{
												?>												
													<td><?=$rst["pgto_parcela"];?></td>
													<td><?=$rst["vencimento"];?></td>                                    
													<td><?=number_format(($rst["pgto_valor"]),2,',','.'); $tot = $tot +$rst["pgto_valor"]; ?></td>
													<td><?=$rst["nome"];?></td> 
													<td><?php 
														if($indpgto != '-1') {
															$libera = 1; ?>
															<a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_editarfinan('_linha<?=$rst["pgto_id"];?>')"><i class="fa fa-pencil  fa-1x"></i></a>
															<a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluirpgto('<?=$rst["pgto_id"];?>','<?=$rst["nome"]?>')"><i class="fa fa-trash-o fa-1x"></i></a>
													<?php }
														else { 
															echo "Confirmado";
													}
													?> </td>                                         
											
													<?php

											}
}


?> 