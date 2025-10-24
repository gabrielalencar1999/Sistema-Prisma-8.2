<?php 

use Database\MySQL;

$pdo = MySQL::acessabd();
date_default_timezone_set('America/Sao_Paulo');

$_dtini = $_parametros["nf-inicial"];
$_dtfim =  $_parametros["nf-final"]; 


function SomarData($data, $dias, $meses, $ano)
{
   //passe a data no formato dd/mm/yyyy 
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
     $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

//print_r($_parametros);

$data_atual = date("d")."/".date("m")."/".date("Y"); 

if ($acao["acao"] == 0) {
	?>
	<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
	<thead>
	   <tr >
		   <th class='text-center'>Data Processamento</th>
		   <th class='text-center'>Qtde Linhas</th>	
		   <th class='text-center'>Valor Total Transação (R$)</th>	
		     
		  <th class='text-center'>Ação</th>
	   </tr>
	   </thead>
	   <tbody>
		   <?php
	  		 $consultaLinha = $pdo->query("Select
			    DATE_FORMAT(arquivo_data,'%d/%m/%Y') AS dt, 
				count(proc_id) as reg,
				sum(proc_vlr_parcela) as total
                                 from ". $_SESSION['BASE'] .".proc_recebiveis  
								 inner join ". $_SESSION['BASE'] .".arquivos_cliente  ON id = proc_id_arquivo
                                 WHERE arquivo_data between '$_dtini' AND '$_dtfim'");
			$retornoLinha = $consultaLinha->fetchAll();

			foreach ($retornoLinha as $row) {   				
			?>  
			<tr class="gradeX">
				<td class="text-center"><?=$row["dt"]?></td>
				<td class="text-center"><?=$row["reg"]?></td>
				<td class="text-center">R$ <?=number_format($row["total"],2,',','.')?></td>
				<td class="text-center">					
					<a href="javascript:void(0);" class="on-default remove-row" onclick="_idVisualizar('')"><i class="fa fa-list-alt fa-2x"></i></a>					
				</td>
			</tr>
			<?php
		 } ?>
			</tbody>
  </table>
		   <?php
}

//processar arquivo upload
if ($acao["acao"] == 1) {
	try {


	//arquivo_processado
	    $consultaLinha = $pdo->query("Select id,arquivo_link,arquivo_nomearquivo
					 from ". $_SESSION['BASE'] .".arquivos_cliente					 
					 WHERE arquivo_processado = 0");
		$ret = $consultaLinha->fetchAll();

		foreach ($ret as $row) { 
			$_idrel = $row['id'];
			
			$_caminho = $row['arquivo_link'];
			$_arquivo = $row['arquivo_nomearquivo'];
			$arquivo_caminho = $_caminho."/".$_arquivo;
			//echo "$arquivo_caminho";
			$fh  = fopen($arquivo_caminho, 'r');
				$conteudo=utf8_encode(fread($fh, filesize($arquivo_caminho) ));
			fclose($fh);
			
			$fh  = fopen($arquivo_caminho, 'w');
				fwrite($fh, $conteudo);
			fclose($fh);

			$handle  = fopen($arquivo_caminho, 'r');
		
			// Popular os dados
			$header = fgetcsv($handle, 1000, ";");
		
			while ($row = fgetcsv($handle, 1000, ";")) {
				$nota[] = array_combine($header, $row);
		
			}
							
				foreach ($nota as $value){
					$_dataTransacao = $value['Data da Transação'];
					$_numeroTransacao = $value['Número da transação'];
					$_vlrTransacao = $value['Valor da Transação (R$)'];   
					$_vlrParcelaTransacao = $value['Valor da Parcela (R$)'];
					$_parcelaTransacao = $value['Número de Parcelas'];
					$_vlr_receber = $value['Valor a Receber (R$)'];
					$_vlr_bruto_recebimento = $value['Valor Bruto de Recebimento (R$)'];
					$_nsu = $value['NSU'];
					$_tecnologia = $value['Tecnologia'];
					$_cartao = $value['Cartão'];
					$_tipo_plano = $value['Tipo do Plano'];
					$_nome_cliente = $value['Nome do Cliente'];
					$_cpfcnpj = $value['CPF/CNPJ'];
					$_telefone_cliente = $value['Telefone no Cliente'];
					$_beneficiario = $value['Beneficiário'];
					$_tipo_beneficiario = $value['Tipo do beneficiário'];
					$_documento_beneficiario = $value['Documento do Beneficiário'];
					$_vlr_receber_beneficiario = $value['Valor a receber por beneficiário (R$)'];
					$_data_prevista_recebimento = $value['Data Prevista do Recebimento'];
					$_data_recebida = $value['Data Recebida'];
					$_taxa_aplicada = $value['Taxa aplicada (%)'];
					$_valor_creditado = $value['Valor Creditado (R$)'];
					$_banco = $value['Banco'];
					$_agencia = $value['Agência'];
					$_conta = $value['Conta'];
					$_dadosbancario = $_banco." (AG:". $_agencia." CC:".$_conta.")";
					$_estabelecimento = $value['Estabelecimento que realizou a transação'];
					$_usuario = $value['Usuário que realizou a transação'];
					$_email_transacao = $value['Email do usuário que passou a transação'];
					$_categoria_transacao = $value['Categoria da Transação'];
					$_valor_retencao = $value['Valor de retenção (R$)'];
					$_Motivo_retencao = $value['Motivo da Retenção'];
					$_valorAntecipacao = $value['Valor de Antecipação (R$)'];
					
					$_valor_itens = $value['Valor dos itens (R$)'];
					$_valor_liquido_itens = $value['Valor líquido dos itens (R$)'];
					$_valor_itens_fornecedor = $value['Valor dos itens para o fornecedor (R$)'];
					$_valor_liquido_itens_fornecedor = $value['Valor líquido dos itens para o fornecedor (R$)'];
					$_ultimos_digito_cartao = $value['Últimos dígitos do cartão'];

					$_dtTransacao = explode("/",$_dataTransacao);
					$_dtTransacao = $_dtTransacao[2]."-".$_dtTransacao[1]."-".$_dtTransacao[0];

					$_pedido = '' ;
					$_numlivro = '' ;  
					$_inclui = 0;
					
					
					$_data_prevista_recebimento = explode("/",$_data_prevista_recebimento);
					$_data_prevista_recebimento = $_data_prevista_recebimento[2]."-".$_data_prevista_recebimento[1]."-".$_data_prevista_recebimento[0];

					$_dt_recebida = explode("/",$_data_recebida);
					$_dt_recebida = $_dt_recebida[2]."-".$_dt_recebida[1]."-".$_dt_recebida[0];

					$_taxa_aplicada = str_replace(".", "", $_taxa_aplicada);
					$_taxa_aplicada = str_replace(",", ".",$_taxa_aplicada);

					
					$_vlrTransacao = str_replace(".", "", $_vlrTransacao);
					$_vlrTransacao = str_replace(",", ".",$_vlrTransacao);

					$_vlrParcelaTransacao = str_replace(".", "", $_vlrParcelaTransacao);
					$_vlrParcelaTransacao = str_replace("-", "", $_vlrParcelaTransacao);
					$_vlrParcelaTransacao = str_replace(",", ".",$_vlrParcelaTransacao);

					$_vlr_receber = str_replace(".", "", $_vlr_receber);
					$_vlr_receber = str_replace(",", ".",$_vlr_receber);

					$_vlr_bruto_recebimento = str_replace(".", "", $_vlr_bruto_recebimento);
					$_vlr_bruto_recebimento = str_replace(",", ".",$_vlr_bruto_recebimento);


					$_valorAntecipacao = str_replace("-", "", $_valorAntecipacao);
					$_valorAntecipacao = str_replace(".", "", $_valorAntecipacao);
					$_valorAntecipacao = str_replace(",", ".",$_valorAntecipacao);
					
					$_vlr_receber_beneficiario = str_replace("-", "", $_vlr_receber_beneficiario);
					$_vlr_receber_beneficiario = str_replace(".", "", $_vlr_receber_beneficiario);
					$_vlr_receber_beneficiario = str_replace(",", ".",$_vlr_receber_beneficiario);

					$_valor_creditado = str_replace("-", "", $_valor_creditado);
					$_valor_creditado = str_replace(".", "", $_valor_creditado);
					$_valor_creditado = str_replace(",", ".",$_valor_creditado);

					

				if($_dtTransacao != "--") {
				
					//buscar transacao e numero 
					$consultaLinha = $pdo->query("Select spgto_numpedido,spgto_numlivro
					from ". $_SESSION['BASE'] .".saidaestoquepgto  
					where  spgto_nsu = '".$_nsu."' ");
					$retornoLinha = $consultaLinha->fetchAll();
					if($consultaLinha->rowCount() > 0) { 
						foreach ($retornoLinha as $row) {          
							$_pedido = $row["spgto_numpedido"] ;
							$_numlivro = $row["spgto_numlivro"] ;         
						}  
					}else{
						$consultaLinha = $pdo->query("Select *
						from bd_gestorpet.nsu_controle  
						where  nsu_number = '".$_nsu."' AND 
						nsu_empresa = '".$_SESSION['BASE_ID']."'
						");
						$retornoLinha = $consultaLinha->fetchAll(); 
						if($consultaLinha->rowCount() > 0) {         
							foreach ($retornoLinha as $row) {          
								$_pedido = $row["nsu_pedido"];
								$_numlivro = $row["nsu_caixa"];   
								$_empresaid = $row["nsu_empresa"];      
							}  
						}else{
							//não entrou 
							$_inclui = 1;
						
						}

					}
				//	echo "INCLUI $_inclui and $_vlr_receber  > 0 <br>";
					if($_inclui == 1 and $_vlr_receber  > 0){

						//gera informação venda

						//saidaestoque
						
							$consulta_ped = $pdo->query("Select parametro_CODIGO_LOGIN,	Num_Pedido_Venda,livro_padrao 
							from ".$_SESSION['BASE'].".parametro ");
							$retPedido = $consulta_ped->fetch();
							$_pedido = $retPedido["Num_Pedido_Venda"];		
							$caixa = $retPedido["livro_padrao"];					
							$idPedidoX = $_pedido + 1;
							$situacaodesc = "ABERTO";     
				   
							$stm = $pdo->query("Update ".$_SESSION['BASE'].".parametro set 	Num_Pedido_Venda = '$idPedidoX' ");
							$stm->execute();
							
							$SQL = "Insert into ".$_SESSION['BASE'].".saidaestoque
								   (NUMERO,CODIGO_CLIENTE,COD_TIPO_SAIDA,DATA_CADASTRO,Cod_Situacao,COD_Vendedor,CLIENTE,num_livro,CODIGO_PET,VL_Pedido) 
								   values ('$_pedido','1', '1','$_dtTransacao','93','0','".$_nome_cliente."','$caixa','1','$_vlrTransacao')    ";
								   $stm = $pdo->prepare("$SQL");  
								   $stm->execute();

						  //
						  $_empresaid = $_SESSION['BASE_ID'];
						  $_sql = "INSERT INTO bd_gestorpet.nsu_controle  (
							nsu_datahora,
							nsu_number,
							nsu_pedido,
							nsu_caixa,
							nsu_empresa) 
							VALUES (
							now(),
							?,
							?,
							?,
							?)";	
							
					
						
						
						  	 $statement = $pdo->prepare("$_sql");       
								$statement->bindParam(1, $_nsu);
								$statement->bindParam(2, $_pedido);
								$statement->bindParam(3, $caixa);
								$statement->bindParam(4, $_empresaid);
								$statement->execute();


						//saidaestoquepgto
						$_parcela = explode("/",$_parcelaTransacao);
						$_totalparcela = $_parcela[1];
						 //buscar tipo pagamento
						 $_SQL = "SELECT id,prz FROM ".$_SESSION['BASE'].".tiporecebimpgto
						  where tipo_label_rel  = '".$_cartao."' ";    
						   
						 $stm = $pdo->prepare($_SQL);	
						 $stm->execute();	
						 if ($stm->rowCount() > 0 ){		
							 while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
							 {
									 $_idpgto = $linha->id;	
									 $dias = $linha->prz;										
							 }                
						 }else{
							$_idpgto = 1;
						 }
			 				 
			 				 
						  
							$valor_parcela = $_vlrTransacao/$_parcela[1] ;
						
							$spgto_valorInfo = $valor_parcela;

							 $Linha = 0; 
		
							 while($Linha < $_parcela[1]   ) {  
			 
									 $Linha ++;                    
									if($Linha == 1){
										$data12	= explode("-",$_data_prevista_recebimento);
												 
										$dia = $data12[2]; 			 
										$mes = $data12[1]; 			 			 
										$ano = $data12[0]; 			 
   
										$data_atual = "$dia/$mes/$ano";			 
										$_vencimento = $_data_prevista_recebimento;		 

									}else{
										$data12 = SomarData($data_atual, $dias, 0, 0);			 
										$dia = substr("$data12",0,2); 			 
										$mes = substr("$data12",3,2); 			 
										$ano = substr("$data12",6,4); 
   
										$data_atual = "$dia/$mes/$ano";			 
										$_vencimento = "$ano-$mes-$dia";	
									}
								
									 
									 $_sql = "INSERT INTO ".$_SESSION['BASE'].".saidaestoquepgto
									 (  spgto_numpedido,
									 spgto_numlivro,
									 spgto_tipopgto,
									 spgto_data	,
									 spgto_venc,
									 spgto_valor,
									 spgto_parcela,
									 spgto_valorInfo,
									 spgto_nsu,
									 spgto_transactionNumber) VALUES(
									 ?,
									 ?,
									 ?,
									'$_dtTransacao',
									 ?,
									 ?,
									 ?,
									 ?,
									 ?,
									 ? )";
									
								 
									 $statement = $pdo->prepare("$_sql"); 
						 
									 $statement->bindParam(1, $_pedido);
									 $statement->bindParam(2, $caixa);
									 $statement->bindParam(3, $_idpgto);
									 $statement->bindParam(4, $_vencimento);           
									 $statement->bindParam(5, $valor_parcela);    
									 $statement->bindParam(6, $Linha);
									 $statement->bindParam(7, $spgto_valorInfo );  
									 $statement->bindParam(8, $_nsu);
									 $statement->bindParam(9, $_numeroTransacao);									 
									 $statement->execute();
								 
									}	//fim while parcelas
																			
									//conta corrente
					
										$consultaPgto = $pdo->query("Select * from ". $_SESSION['BASE'] .".saidaestoquepgto
										where  spgto_numpedido = '".$_pedido."' AND spgto_numlivro = '".$caixa."'");
										$regQtde = $consultaPgto->rowCount();
										$retornoPgto = $consultaPgto->fetchAll();
								
										$_totalserv = $_totalserv  /  $regQtde;  
										
										foreach ($retornoPgto as $row_a) {        

											$_idpgto = $row_a["spgto_tipopgto"];
											$_parcelaSgpto = $row_a["spgto_parcela"];
											$_vencimento = $row_a["spgto_venc"];
											$_totalserv = $row_a["spgto_valor"];

											//insert conta corrente
											$_SQL = "INSERT INTO bd_gestorpet.contacorrente (
											cc_data,
											cc_hora,
											cc_usuario,
											cc_documento,
											cc_livro,
											cc_tipomov,
											cc_valor,
											cc_tipopgto,	
											cc_venc,
											cc_nsuNumber,
											cc_empID,
											cc_vlrtransacao,
											cc_totalparc,	
											cc_parc,													
											cc_taxa,
											cc_vlrcreditado,
											cc_vlrtantecipado,
											cc_vlrtbrutoRecebido,
											cc_nome) values ( 
											'$_dtTransacao',
											NOW(),
											'0',
											'$_pedido',
											'$caixa',
											'1',
											'$_totalserv',
											'$_idpgto',	
											'$_vencimento',
											'$_nsu',
											'$_empresaid',
											'$_vlrTransacao',
											 '".$_totalparcela."',
											 '".$_parcelaSgpto."',
											 '$_taxa_aplicada',
											  '$_valor_creditado',
											 '$_valorAntecipacao',
											 '$_vlr_bruto_recebimento',
											 '$_nome_cliente')";                          
																
											$stm = $pdo->prepare($_SQL);	
											$stm->execute();	

										
											if($row_a["Ind_liquida"] == 'S') {
												$_sit = 2; // buscar situação tipo pgto - aberto ou liquidado
											}else{
												$_sit = 1; // buscar situação tipo pgto - aberto ou liquidado
											}
							
											
											//financeiro
												$_sit = 0; // buscar situação tipo pgto - aberto ou liquidado

												//verificar data recebida
											
												if($_dt_recebida != "" and $_dt_recebida != "--"){
													$dtrecebido = $_data_recebida;			
												}else{
													$dtrecebido = '';
												}
												

												$_SQL = "INSERT INTO ". $_SESSION['BASE'] .".financeiro  
														(financeiro_parcela,financeiro_totalParcela,financeiro_codigoCliente,financeiro_nome,
														financeiro_documento,financeiro_historico,financeiro_emissao,financeiro_vencimento,
														financeiro_vencimentoOriginal,financeiro_valor,
														financeiro_situacaoID,
														INDENTIFICADOR,
														financeiro_tipo,
														financeiro_grupo,
														financeiro_subgrupo,
														financeiro_caixa,financeiro_tipoPagamento,financeiro_hora,
														financeiro_nsu,
														financeiro_lancamentoCC,
														financeiro_dataFim) VALUES (
														'$_parcela','$regQtde','1','$_nome_cliente',
														'$_pedido','REF ATEND $_pedido','$_dtTransacao','$_vencimento',
														'$_vencimento','$_totalserv',
														'$_sit',
														'1',
														'0',
														'1',
														'1','$caixa','$_idpgto',now(),
														'$_nsu',
														'1',
														'$dtrecebido')";
													
												$stm = $pdo->prepare($_SQL);	
												$stm->execute();	
										}

										  //gerar informação para notas fiscais
										  $_totalprodutos = 0;
										  $consultaNF = $pdo->query("
										  Select usuario_empresa,cc_empID,sum(cc_valor) as total,cc_usuario,cc_documento,cc_livro
										  FROM bd_gestorpet.contacorrente
										  LEFT JOIN bd_gestorpet.usuario ON usuario_CODIGOUSUARIO  = 	cc_usuario
										  LEFT JOIN  bd_gestorpet.empresa_cadastro e ON cc_usuario = e.id
										  WHERE  cc_documento = '".$_pedido."' AND cc_livro = '".$caixa."' and cc_empID = '".$_SESSION['BASE_ID']."' 
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
						
													$_SQL = " INSERT INTO bd_gestorpet.notas
													(nf_empresaid,nf_empresaemissao,nf_usuarioid,nf_data,nf_hora,nf_controle,nf_livro,nf_idconsumidor,
													nf_nomeconsumidor,nf_vlrservico,nf_vlproduto,	nf_total,nf_situacao,nfse_status) 
													values 
													( '".$row['cc_empID']."',
													'".$emp."',
													'".$row['cc_usuario']."',
													'".$_dtTransacao."',
													NOW(),
													'".$row['cc_documento']."',
													'".$row['cc_livro']."',
													'1',
													'$_nome_cliente',
													'".$_valorservico."',
													'".$_totalprodutosG."',	
													'".$_totalnf."',	
													'0','pendente')";
													
													$stm = $pdo->prepare($_SQL);	
													$stm->execute();	
			
										//gravar emissão 
								 }
								
							   
								//fim notas fiscais
						
				

					} //fim inclui
					else{
						if($_tipo_beneficiario != 'Estabelecimento'){
							//$_documento_beneficiario
						}
					}
				
				

					$_sql = "Select proc_id
								from ". $_SESSION['BASE'] .".proc_recebiveis  
								where  proc_numero_transacao = '".$_numeroTransacao."' 
								AND proc_dt_transacao = '$_dtTransacao'               
								AND proc_parcela = '$_parcelaTransacao'
								AND proc_doc_beneficiario = '$_documento_beneficiario'							
								";
								
								$consultaLinha = $pdo->query($_sql);
								$retornoLinha = $consultaLinha->fetchAll();
					
								if($consultaLinha->rowCount() == 0) { 
											//gravar novo registro
										
											$_sql  = "INSERT INTO  ". $_SESSION['BASE'] .".proc_recebiveis 
														(proc_id_arquivo,
														proc_dt_transacao, 
														proc_numero_transacao, 
														proc_vlr_transacao,
														proc_vlr_parcela,
														proc_parcela,
														proc_vlr_receber,
														proc_vlr_bruto_recebido,
														proc_beneficiario,
														proc_doc_beneficiario,
														proc_vlr_receber_benificario,
														proc_dt_recebida,
														proc_vlr_creditado,
														proc_dados_bancario,
														proc_nsu) VALUES (?,
														?, 
														?, 
														?,
														?,
														?,
														?,
														?,
														?,
														?,
														?,
														?,
														?,
														?,
														?                        
														)";	
														/*
														$s = "INSERT INTO  ". $_SESSION['BASE'] .".proc_recebiveis 
														(proc_id_arquivo,
														proc_dt_transacao, 
														proc_numero_transacao, 
														proc_vlr_transacao,
														proc_vlr_parcela,
														proc_parcela,
														proc_vlr_receber,
														proc_vlr_bruto_recebido,
														proc_beneficiario,
														proc_doc_beneficiario,
														proc_vlr_receber_benificario,
														proc_dt_recebida,
														proc_vlr_creditado,
														proc_dados_bancario,
														proc_nsu) VALUES ('$_idrel',
														'$_dtTransacao', 
														'$_numeroTransacao', 
														'$_vlrTransacao',
														'$_vlrParcelaTransacao',
														'$_parcelaTransacao',
														'$_vlr_receber',
														'$_vlr_bruto_recebimento',
														'$_beneficiario',
														'$_documento_beneficiario',
														'$_vlr_receber_beneficiario',
														'$_dt_recebida',
														'$_valor_creditado',
														'$_dadosbancario',   
														$_nsu                     
														)";
														
														echo $s."<br><br>";
											*/
												$_insert = $pdo->prepare($_sql );
												$_insert->bindParam(1, $_idrel);
												$_insert->bindParam(2,$_dtTransacao);
												$_insert->bindParam(3, $_numeroTransacao);
												$_insert->bindParam(4,$_vlrTransacao);
												$_insert->bindParam(5,$_vlrParcelaTransacao);
												$_insert->bindParam(6,$_parcelaTransacao);
												$_insert->bindParam(7,$_vlr_receber);
												$_insert->bindParam(8,$_vlr_bruto_recebimento);
												$_insert->bindParam(9,$_beneficiario);
												$_insert->bindParam(10,$_documento_beneficiario);
												$_insert->bindParam(11,$_vlr_receber_beneficiario);
												$_insert->bindParam(12,$_dt_recebida);
												$_insert->bindParam(13,$_valor_creditado);
												$_insert->bindParam(14,$_dadosbancario);
												$_insert->bindParam(15,$_nsu);
											
												$_insert->execute();

												$_proc = 'ok';
												

												//buscar conta beneficiario
												$_idcolaborado = 0;												
												if( $_vlrParcelaTransacao <= 0 or $_vlrParcelaTransacao == "" and $_valor_creditado > 0){

													$consultaLinha = $pdo->query("Select usuario_CODIGOUSUARIO
													from bd_gestorpet.usuario  
													where  usuario_cpf = '".$_documento_beneficiario."' ");
													$retornoLinha = $consultaLinha->fetchAll();
												
													if($consultaLinha->rowCount() > 0 ) { 
														foreach ($retornoLinha as $row) {          
															$_idcolaborador = $row["usuario_CODIGOUSUARIO"] ;
														
														}
													}else{
															//cadastro novo colaborador
															$_SQL = "INSERT INTO bd_gestorpet.usuario
															  (usuario_cpf,usuario_APELIDO,usuario_NOME,usuario_colaborador,usuario_PERFIL )
															VALUES (?,?,?,'1','8')"; 
														
																$_insert = $pdo->prepare($_SQL);
																$_insert->bindParam(1, $_documento_beneficiario);
																$_insert->bindParam(2, $_beneficiario);
																$_insert->bindParam(3, $_beneficiario);
																$_insert->execute();
																
																$consultaLinha = $pdo->query("Select usuario_CODIGOUSUARIO
																								from bd_gestorpet.usuario  
																								where  usuario_cpf = '".$_documento_beneficiario."' ");
																$retornoLinha = $consultaLinha->fetchAll();	
																foreach ($retornoLinha as $row) {          
																	$_idcolaborador = $row["usuario_CODIGOUSUARIO"] ;
																	
																}

																$sql2="insert into bd_gestorpet.colaborador 
																(colaborador_usuario,colaborador_empresa,colaborador_aceite,colaborador_status
																) values ('$_idcolaborador','".$_empresaid."','0','-1')";
																$stm2 = $pdo->prepare($sql2);
																$stm2->execute();
																
														} 
														
													//faz insert conta corrente
													if($_idcolaborador > 0) {

														 //buscar tipo pagamento
															$_SQL = "SELECT id,prz FROM ".$_SESSION['BASE'].".tiporecebimpgto
															where tipo_label_rel  = '".$_cartao."' ";    
														
															$stm = $pdo->prepare($_SQL);	
															$stm->execute();	
															if ($stm->rowCount() > 0 ){		
																while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
																{
																
																		$_idpgto = $linha->id;	
																		$dias = $linha->prz;										
																}                
															}else{
															
																	$_idpgto = 1;
															}

														$_SQL = "INSERT INTO bd_gestorpet.	contacorrente (
															cc_data,
															cc_hora,
															cc_usuario,
															cc_documento,
															cc_livro,
															cc_tipomov,
															cc_valor,
															cc_tipopgto,	
															cc_venc,
															cc_origem,
															cc_transactionNumber,
															cc_nsuNumber,
															cc_empID,
															cc_vlrtransacao,
															cc_totalparc,	
															cc_parc,													
															cc_taxa,
															cc_vlrcreditado,
															cc_vlrtantecipado,
															cc_vlrtbrutoRecebido,
															cc_nome) values ( 
															'$_dtTransacao',
															NOW(),
															'$_idcolaborador',
															'$_pedido',
															'$_numlivro',
															'1',
															'-$_valor_creditado',
															'$_idpgto',	
															'$_data_prevista_recebimento',
															'2',
															'$_numeroTransacao',
															'$_nsu',
															'$_empresaid',
															'$_vlrTransacao',
															'".$_totalparcela."',
															 '".$_parcela[0]."',
															'$_taxa_aplicada',
															'$_valor_creditado',
															'$_valorAntecipacao',
															'$_vlr_bruto_recebimento',
															'$_nome_cliente')";                          
														// cc_origem credito Pedido 
												
															$stm = $pdo->prepare($_SQL);	
															$stm->execute();	
													}
												}
											

									}          
					
              
					//grava operacao
							

					//registro pgto
					} //valida data

				
				}  

		if($_proc == 'ok') {
			$_SQL = "UPDATE ". $_SESSION['BASE'] .".arquivos_cliente SET
			arquivo_processado = '1'
			WHERE id  = '$_idrel'	";                         
			
				$stm = $pdo->prepare($_SQL);	
				$stm->execute();	
		}
			
	
		}
		?>
    			 <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Retorno Processado</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_alterar(<?=$_numeronota?>, <?=$fabricanteID?>)">Fechar</button>
                       
		<?php


	}catch (PDOException $e) {

	}

}