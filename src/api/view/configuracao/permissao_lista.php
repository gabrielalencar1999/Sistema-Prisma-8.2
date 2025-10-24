<?php include("../../api/config/iconexao.php");
$id = $_chaveid;

//PEGA NOME DO USUARIO
$stm = $pdo->prepare("select usuario_NOME from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '".$id."'");
$stm->execute();
foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
	$nome = $rst->usuario_NOME;
}

//BUSCA PERMISSÕES PARA USUARIO
$stm = $pdo->prepare("select * from " . $_SESSION['BASE'] . ".telas_acesso where tela_user = '".$id."'");
$stm->execute();
if($stm->rowCount() > 0){
	foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {

		//ULTIMO ID SEQUENCIAL 158


		//PERMISSOES PRINCIPAIS=============================================================================================================
		if($rst->tela_descricao == "1"){ $per001 = $rst->tela_descricao; }//CLIENTE
		if($rst->tela_descricao == "2"){ $per002 = $rst->tela_descricao; }//AGENDA
		if($rst->tela_descricao == "3"){ $per003 = $rst->tela_descricao; }///MENU PRINCIPAL - SERVIÇOS
		if($rst->tela_descricao == "4"){ $per004 = $rst->tela_descricao; }//Vendas / PDV
		if($rst->tela_descricao == "5"){ $per005 = $rst->tela_descricao; }//ROTEIRO
		if($rst->tela_descricao == "6"){ $per006 = $rst->tela_descricao; }//REQUISICAO
		if($rst->tela_descricao == "231"){ $per231 = $rst->tela_descricao; }//REQUISICAO PRISMAMOBTEC

		if($rst->tela_descricao == "7"){ $per007 = $rst->tela_descricao; }//ATENDIMENTO CAMPO
		if($rst->tela_descricao == "8"){ $per008 = $rst->tela_descricao; }//SERVICOS ok
		if($rst->tela_descricao == "9"){ $per009 = $rst->tela_descricao; }//ESTOQUE
		if($rst->tela_descricao == "10"){ $per010 = $rst->tela_descricao; }//CONFIGURACOES
		if($rst->tela_descricao == "11"){ $per011 = $rst->tela_descricao; }//MEU SALDO
		if($rst->tela_descricao == "12"){ $per012 = $rst->tela_descricao; }//MINHA CONTA
        if($rst->tela_descricao == "13"){ $per013 = $rst->tela_descricao; }//CAIXA PDV
		if($rst->tela_descricao == "14"){ $per014 = $rst->tela_descricao; }//VENDAS
	
		if($rst->tela_descricao == "15"){ $per015 = $rst->tela_descricao; }//ADMISTRATIVO
		if($rst->tela_descricao == "16"){ $per016 = $rst->tela_descricao; }//FINANCEIRO  menu

		if($rst->tela_descricao == "17"){ $per017 = $rst->tela_descricao; }//ULTIMAS ATIVIDADES  MENU
		if($rst->tela_descricao == "18"){ $per018 = $rst->tela_descricao; }//RESUMO MENU


		
		//PERMISSOES SECUNDARIAS //CLIENTES=================================================================================================
		if($rst->tela_descricao == "100"){ $per100 = $rst->tela_descricao; }//CONSULTA VENDAS
		if($rst->tela_descricao == "101"){ $per101 = $rst->tela_descricao; }//PLANOS
		if($rst->tela_descricao == "102"){ $per102 = $rst->tela_descricao; }//ANIVERSARIOS
		if($rst->tela_descricao == "103"){ $per103 = $rst->tela_descricao; }//RESUMO DE VENDAS
		if($rst->tela_descricao == "104"){ $per104 = $rst->tela_descricao; }//RELATORIOS

		if($rst->tela_descricao == "160"){ $per160 = $rst->tela_descricao; }//EXPORTAR RELATORIO CLIENTES
		
		
		//PERMISSOES SECUNDARIAS //FINANCEIRO===============================================================================================
		if($rst->tela_descricao == "146"){ $per146 = $rst->tela_descricao; }//FINANCEIRO
		if($rst->tela_descricao == "106"){ $per106 = $rst->tela_descricao; }//EXTRATO FINANCEIRO
		if($rst->tela_descricao == "107"){ $per107 = $rst->tela_descricao; }//NOTAS FISCAIS
		if($rst->tela_descricao == "108"){ $per108 = $rst->tela_descricao; }//RECEBIVEIS
		if($rst->tela_descricao == "109"){ $per109 = $rst->tela_descricao; }//RELATORIOS FINANCEIRO
		if($rst->tela_descricao == "142"){ $per142 = $rst->tela_descricao; }//FECHAMENTO FINANCEIRO
		if($rst->tela_descricao == "105"){ $per105 = $rst->tela_descricao; }//RESUMO CAIXA
		
		
		
		//PERMISSOES SECUNDARIAS //ADMINISTRATIVO===========================================================================================
		if($rst->tela_descricao == "110"){ $per110 = $rst->tela_descricao; }//NOTAS DE ENTRADA
		if($rst->tela_descricao == "111"){ $per111 = $rst->tela_descricao; }//GERACAO DE ARQUIVOS
		if($rst->tela_descricao == "112"){ $per112 = $rst->tela_descricao; }//FORNECEDORES
		if($rst->tela_descricao == "230"){ $per230 = $rst->tela_descricao; }// modelo comercial
		if($rst->tela_descricao == "232"){ $per232 = $rst->tela_descricao; }//registro ponto
		if($rst->tela_descricao == "233"){ $per233 = $rst->tela_descricao; }//gestão compras
		//PERMISSOES SECUNDARIAS //SERVICOS=================================================================================================
		if($rst->tela_descricao == "113"){ $per113 = $rst->tela_descricao; }//SERVICOS
		if($rst->tela_descricao == "114"){ $per114 = $rst->tela_descricao; }//RELATORIOS
		if($rst->tela_descricao == "152"){ $per152 = $rst->tela_descricao; }//importar O.S
		
		if($rst->tela_descricao == "220"){ $per220 = $rst->tela_descricao; }//AGENDA PREVENTIVO
		if($rst->tela_descricao == "221"){ $per221 = $rst->tela_descricao; }//PAINEL DA OFICINA
		if($rst->tela_descricao == "222"){ $per222 = $rst->tela_descricao; }//ALTERAR SITUAÇÃO OFICINA PAINEL DA OFICINA
		if($rst->tela_descricao == "223"){ $per223 = $rst->tela_descricao; }//Data Encerramento O.S
		if($rst->tela_descricao == "224"){ $per224 = $rst->tela_descricao; }// Modelo Produto O.S
		if($rst->tela_descricao == "225"){ $per225 = $rst->tela_descricao; }// ABA RESUMO NA os
		if($rst->tela_descricao == "226"){ $per226 = $rst->tela_descricao; }// LIBERA ALMOX O.S
		if($rst->tela_descricao == "227"){ $per227 = $rst->tela_descricao; }// LIBERA CPF DUPLICADO
		if($rst->tela_descricao == "228"){ $per228 = $rst->tela_descricao; }// BLOQUER REATIVAR OS
		if($rst->tela_descricao == "229"){ $per229 = $rst->tela_descricao; }// modelo comercial
		if($rst->tela_descricao == "240"){ $per240 = $rst->tela_descricao; }// libera inativar acompanhamento
		if($rst->tela_descricao == "241"){ $per241 = $rst->tela_descricao; }// liberar visualização log salvamenteo
		if($rst->tela_descricao == "242"){ $per242 = $rst->tela_descricao; }// Consulta O.S

		if($rst->tela_descricao == "157"){ $per157 = $rst->tela_descricao; }//PAGAMENTOS PRISMA MOB
		if($rst->tela_descricao == "161"){ $per161 = $rst->tela_descricao; }//bloqueia RESUMO O.S
	
		
 		//PERMISSOES SECUNDARIAS //VENDAS =================================================================================================
		if($rst->tela_descricao == "115"){ $per115 = $rst->tela_descricao; }//libera Almoxarifado
		if($rst->tela_descricao == "250"){ $per250 = $rst->tela_descricao; }//libera Almoxarifado
		if($rst->tela_descricao == "251"){ $per251 = $rst->tela_descricao; }//relatorios vendas
		if($rst->tela_descricao == "252"){ $per252 = $rst->tela_descricao; }//esconder valores
		if($rst->tela_descricao == "253"){ $per253 = $rst->tela_descricao; }//alterar vendedor venda finalizada	

		//PERMISSOES SECUNDARIAS //ESTOQUE==================================================================================================
		if($rst->tela_descricao == "116"){ $per116 = $rst->tela_descricao; }//PRODUTOS
		if($rst->tela_descricao == "117"){ $per117 = $rst->tela_descricao; }//REQUISICAO
		if($rst->tela_descricao == "118"){ $per118 = $rst->tela_descricao; }//MOVIMENTACAO
		if($rst->tela_descricao == "119"){ $per119 = $rst->tela_descricao; }//INVENTARIO
		if($rst->tela_descricao == "120"){ $per120 = $rst->tela_descricao; }//ARQUIVO DE BALANCA
		if($rst->tela_descricao == "121"){ $per121 = $rst->tela_descricao; }//CURVA ABC
		if($rst->tela_descricao == "122"){ $per122 = $rst->tela_descricao; }//ESTIQUETA
		if($rst->tela_descricao == "123"){ $per123 = $rst->tela_descricao; }//RELATORIOS

		if($rst->tela_descricao == "219"){ $per219 = $rst->tela_descricao; }//SUPERVISOR INVENTÁRIO
		if($rst->tela_descricao == "401"){ $per401 = $rst->tela_descricao; }//Inclui/Altera Estoque
		if($rst->tela_descricao == "402"){ $per402 = $rst->tela_descricao; }//Visualiza Detalhe
		
		if($rst->tela_descricao == "158"){ $per158 = $rst->tela_descricao; }//GERA REQUISICAO DENTRO DA O.S
		
		
		//PERMISSOES SECUNDARIAS //CONFIGURACOES=============================================================================================
		if($rst->tela_descricao == "124"){ $per124 = $rst->tela_descricao; }//DADOS CADASTRAIS
		if($rst->tela_descricao == "125"){ $per125 = $rst->tela_descricao; }//FUNCIONARIOS E LOGINS
		if($rst->tela_descricao == "126"){ $per126 = $rst->tela_descricao; }//GRUPOS
		if($rst->tela_descricao == "127"){ $per127 = $rst->tela_descricao; }//CATEGORIAS
		if($rst->tela_descricao == "128"){ $per128 = $rst->tela_descricao; }//ESPECIES
		if($rst->tela_descricao == "129"){ $per129 = $rst->tela_descricao; }//ENDERCOS
		if($rst->tela_descricao == "130"){ $per130 = $rst->tela_descricao; }//TIPO DE CLIENTE
		if($rst->tela_descricao == "131"){ $per131 = $rst->tela_descricao; }//TIPO DE FORNECEDOR
		if($rst->tela_descricao == "132"){ $per132 = $rst->tela_descricao; }//ALMOXARIFADO
		if($rst->tela_descricao == "133"){ $per133 = $rst->tela_descricao; }//LINHAS
		if($rst->tela_descricao == "134"){ $per134 = $rst->tela_descricao; }//CONDICOES DE PAGAMENTOS
		if($rst->tela_descricao == "135"){ $per135 = $rst->tela_descricao; }//LIVRO CAIXA
		if($rst->tela_descricao == "136"){ $per136 = $rst->tela_descricao; }//GRUPO DE RECEITAS E DESPESAS
		if($rst->tela_descricao == "137"){ $per137 = $rst->tela_descricao; }//CONTAS DE RECEITAS E DESPESAS
		if($rst->tela_descricao == "138"){ $per138 = $rst->tela_descricao; }//PROJETO, CENTRO DE CUSTO
		if($rst->tela_descricao == "139"){ $per139 = $rst->tela_descricao; }//EXTRA A
		if($rst->tela_descricao == "140"){ $per140 = $rst->tela_descricao; }//EXTRA B
		if($rst->tela_descricao == "141"){ $per141 = $rst->tela_descricao; }//ZERA ESTOQUE
		if($rst->tela_descricao == "143"){ $per143 = $rst->tela_descricao; }//PREVENTIVO
		if($rst->tela_descricao == "144"){ $per144 = $rst->tela_descricao; }//WHATS
		if($rst->tela_descricao == "145"){ $per145 = $rst->tela_descricao; }//TERMINAL
		if($rst->tela_descricao == "148"){ $per148 = $rst->tela_descricao; }//REGIÃO
		if($rst->tela_descricao == "149"){ $per149 = $rst->tela_descricao; }//AVISOS
		if($rst->tela_descricao == "150"){ $per150 = $rst->tela_descricao; }//ESTOQUE COMPARTILHADO
		if($rst->tela_descricao == "151"){ $per151 = $rst->tela_descricao; }//LOG ACESSO

		if($rst->tela_descricao == "153"){ $per153 = $rst->tela_descricao; }//SITUACAO ELX
		if($rst->tela_descricao == "154"){ $per154 = $rst->tela_descricao; }//SITUACAO OFICINA
		if($rst->tela_descricao == "155"){ $per155 = $rst->tela_descricao; }//CFOP
		if($rst->tela_descricao == "156"){ $per156 = $rst->tela_descricao; }//CUSTOMIZACAO
		if($rst->tela_descricao == "162"){ $per162 = $rst->tela_descricao; }//BACKUP
		if($rst->tela_descricao == "163"){ $per163 = $rst->tela_descricao; }//BLOQUERAR ACESSO ALTERACAO CLIENTE
		if($rst->tela_descricao == "164"){ $per164 = $rst->tela_descricao; }//BLOQUEIA NOVO MODELO/APARELHO O.S
		if($rst->tela_descricao == "999"){ $per999 = $rst->tela_descricao; }//PEFIL FINANCEIRO




	}
}
?>
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-permissao" id="form-permissao">
							<input type="hidden" id="id" name="id" value="<?=$id;?>">						
							<div class="row">
								<!----PERMISSOES MENU-------------------->
							<div class="col-sm-4">
								<h3><i class="ti-desktop text-custom"></i> <b>Menu</b></h3>
								
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Clientes</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per001','1')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per001 != ""){ echo'checked'; } ?> />
												<input type="hidden" id="per001" name="per001" value="<?=$per001;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Área onde é realizada o atendimento do cliente: Vendas e Ordem Serviço...</p>
								   <br>
									   
									   <div class="row">
													<div class="col-sm-6">														
													</div>
													<div class="col-sm-6" align="right">
														<a href="javascript:void(0)" onclick="expandir('avanCliente')"><i class="ti-angle-double-down"></i> Avançado</a>
													</div>
										</div>
										<div class="row" style="display:none; padding:10px;" id="avanCliente">
														<div class="col-xs-12">
															<div class="row">
																<div class="col-sm-6">
																Exportar Relatório Clientes														
																</div>
																<div class="col-sm-6" align="right">
																<a onclick="alt('per160','160')">
																		<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per160 != ""){ echo'checked'; }?> />
																		<input type="hidden" id="per160" name="per160" value="<?=$per160;?>">
																	</a>
																</div>
															</div>
														</div>
										</div>
                                   
								</div>

								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Agenda</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per002','2')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per002 != ""){ echo'checked'; } ?> />
												<input type="hidden" id="per002" name="per002" value="<?=$per002;?>">
											</a>									
										</div>
									
									</div>
									<br>
									   <p>Gestão Agenda</p>
									   <div class="row">
													<div class="col-sm-6">														
													</div>
													<div class="col-sm-6" align="right">
														<a href="javascript:void(0)" onclick="expandir('avanAgenda')"><i class="ti-angle-double-down"></i> Avançado</a>
													</div>
										</div>
										<div class="row" style="display:none; padding:10px;" id="avanAgenda">
														<div class="col-xs-12">
															<div class="row">
																<div class="col-sm-6">
																	Agenda Preventivo														
																</div>
																<div class="col-sm-6" align="right">
																<a onclick="alt('per220','220')">
																		<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per220 != ""){ echo'checked'; }?> />
																		<input type="hidden" id="per220" name="per220" value="<?=$per220;?>">
																	</a>
																</div>
															</div>
														</div>
										</div>
								</div>	
								


								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Serviços</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per003','3')">
												<input id="fin1" type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per003 != ""){ echo'checked'; } ?> />
												<input type="hidden" id="per003" name="per003" value="<?=$per003;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Lista Ordens Serviços</p>
								   <br>
								   <b></b>
                                   
								</div>

								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Vendas / PDV</h4>
										</div>
										<div class="col-xs-4" align="right">
											
											<a onclick="alt('per013','13')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per013!= ""){ echo'checked'; } ?> />
												<input type="hidden" id="per013" name="per013" value="<?=$per013;?>">
											</a>								
										</div>
									</div>
								   <br>
								   <p>Ponto de venda. Venda de produtos</p>
								   <br>
								   <b></b>
                                   
								</div>
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Roteiro</h4>
										</div>
										<div class="col-xs-4" align="right">
											
											<a onclick="alt('per005','5')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per005!= ""){ echo'checked'; } ?> />
												<input type="hidden" id="per005" name="per005" value="<?=$per005;?>">
											</a>								
										</div>
									</div>
								   <br>
								   <p>Lista Roteiro Diário</p>
								   <br>
								   <b></b>
                                   
								</div>

								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Requisição</h4>
										</div>
										<div class="col-xs-4" align="right">											
											<a onclick="alt('per006','6')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per006!= ""){ echo'checked'; } ?> />
												<input type="hidden" id="per006" name="per006" value="<?=$per006;?>">
											</a>								
										</div>
									</div>
								   <br>
								   <p>Lançamento e Movimentação de estoque </p>
								   <br>
								   <div class="row">
													<div class="col-sm-6">														
													</div>
													<div class="col-sm-6" align="right">
														<a href="javascript:void(0)" onclick="expandir('avanReq')"><i class="ti-angle-double-down"></i> Avançado</a>
													</div>
										</div>
										<div class="row" style="display:none; padding:10px;" id="avanReq">
														<div class="col-xs-12">
															<div class="row">
																<div class="col-sm-6">
																	Libera requisição PrismaMob Técnico														
																</div>
																<div class="col-sm-6" align="right">
																<a onclick="alt('per231','231')">
																		<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per231 != ""){ echo'checked'; }?> />
																		<input type="hidden" id="per231" name="per231" value="<?=$per231;?>">
																	</a>
																</div>
															</div>
																<div class="row">
																	<hr>
																	<div class="col-sm-6">
																		Gerar requisição a partir O.S
																	</div>
																	<div class="col-sm-6" align="right">
																	<a onclick="alt('per158','158')">
																			<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per158 != ""){ echo'checked'; }?> />
																			<input type="hidden" id="per158" name="per158" value="<?=$per158;?>">
																		</a>
																	</div>
															</div>
														</div>
										</div>
                                   
								</div>
							</div>
							<!----PERMISSOES MEU NEGÓCIO-------------------->
							<div class="col-sm-4">
								<h3><i class="md md-dashboard text-custom"></i> <b>Meu Negócio</b></h3>
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Atendimento Campo</h4>											
										</div>

												<div class="col-xs-4" align="right">
											<a onclick="alt('per007','7')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per007 != ""){ echo'checked'; } ?> />
												<input type="hidden" id="per007" name="per007" value="<?=$per007;?>">
											</a>
										</div>
											<div class="col-sm-6">
											
										</div>
										<div class="col-sm-6" align="right">
														<a href="javascript:void(0)" onclick="expandir('avanAtend')"><i class="ti-angle-double-down"></i> Avançado</a>
													</div>
								
									</div>
								   <br>
								   	<div class="row" style="display:none; padding:10px;" id="avanAtend">
														<div class="col-xs-12">
															<div class="row">
																<div class="col-sm-6">
																	Pagamento PrismaMob														
																</div>
																<div class="col-sm-6" align="right">
																<a onclick="alt('per157','157')">
																		<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per157 != ""){ echo'checked'; }?> />
																		<input type="hidden" id="per157" name="per157" value="<?=$per157;?>">
																	</a>
																</div>
															</div>
														</div>
										</div>
								
								
								</div><!---------------------------------------------------------------------------------------------------------->		
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Financeiro</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per016','16')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per016 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per016" name="per016" value="<?=$per016;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Acesso financeiro, resumo caixa, extrato financeiro, notas fiscais, recebiveis e relatórios</p>
								   <br>
								   <div class="row">
										<div class="col-sm-6">
											
										</div>
										<div class="col-sm-6" align="right">
											<a href="javascript:void(0)" onclick="expandir('avanFinanceiro')"><i class="ti-angle-double-down"></i> Avançado</a>
										</div>
								   </div>
										
									<div class="row" style="display:none; padding:10px;" id="avanFinanceiro">
										<div class="col-xs-12">
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Financeiro
												</div>
												<div class="col-xs-6" align="right">
													<!-----MESMO INPUT DO MENU/FINANCEIRO--------------->
													<a onclick="alt('per146','146')">
														<input  type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per146 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per146" name="per146" value="<?=$per146;?>">
													</a>
												</div>
											</div>	
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Fechamento Financeiro
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per142','142')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per142 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per142" name="per142" value="<?=$per142;?>">
													</a>
												</div>
											</div>
											<!--
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Conta Corrente
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per106','106')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per106 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per106" name="per106" value="<?=$per106;?>">
													</a>
												</div>
											</div>
											-->

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Resumo Caixa
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per105','105')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per105 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per105" name="per105" value="<?=$per105;?>">
													</a>
												</div>
											</div>
											
											
											<!--
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Recebiveis
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per108','108')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per108 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per108" name="per108" value="<?=$per108;?>">
													</a>
												</div>
											</div>	
-->
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Relatórios
												</div>
												<div class="col-xs-6" align="right">
												
													<a onclick="alt('per109','109')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per109 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per109" name="per109" value="<?=$per109;?>">
													</a>
													
												</div>
											</div>
										</div>
									</div>
								</div><!---------------------------------------------------------------------------------------------------------->	
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Administrativo</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per015','15')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per015 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per015" name="per015" value="<?=$per015;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Acesso a Notas de Entrada, geração de arquivos NF-e e XML, e fornecedores...</p>
								   <br>
								   <div class="row">
										<div class="col-sm-6">
											<b></b>
										</div>
										<div class="col-sm-6" align="right">
											<a href="javascript:void(0)" onclick="expandir('avanAdministrativo')"><i class="ti-angle-double-down"></i> Avançado</a>
										</div>
								   </div>
										
									<div class="row" style="display:none; padding:10px;" id="avanAdministrativo">
										<div class="col-xs-12">
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Notas de Entrada
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per110','110')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per110 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per110" name="per110" value="<?=$per110;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Notas Fiscais
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per107','107')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per107 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per107" name="per107" value="<?=$per107;?>">
													</a>
												</div>
											</div><!--
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Geração de Arquivos
												</div>
												<div class="col-xs-6" align="right">
													
													<a onclick="alt('per111','111')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per111 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per111" name="per111" value="<?=$per111;?>">
													
													EM BREVE
												</div>
											</div>
											</a>-->
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Fornecedores
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per112','112')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per112 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per112" name="per112" value="<?=$per112;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													NPS - Pesquisa Satisfação
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per230','230')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per230 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per230" name="per230" value="<?=$per230;?>">
													</a>													
												</div>
												
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Registro Ponto 
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per232','232')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per232 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per232" name="per232" value="<?=$per232;?>">
													</a>													
												</div>
												
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Gestão Pedidos e Compras
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per233','233')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per233 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per233" name="per233" value="<?=$per233;?>">
													</a>													
												</div>
												
											</div>
										</div>
									</div>
								</div>
								<!---------------------------------------------------------------------------------------------------------->	
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Serviços</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per008','8')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per008 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per008" name="per008" value="<?=$per008;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Acesso Consultas e Relatórios Atendimento O.S...</p>
								   <br>
								   <div class="row">
										<div class="col-sm-6">
											<b></b>
										</div>
										<div class="col-sm-6" align="right">
											<a href="javascript:void(0)" onclick="expandir('avanServicos')"><i class="ti-angle-double-down"></i> Avançado</a>
										</div>
								   </div>
										
									<div class="row" style="display:none; padding:10px;" id="avanServicos">
										<div class="col-xs-12">		
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Mapa Atendimento
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per113','113')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per113 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per113" name="per113" value="<?=$per113;?>">
													</a>													
												</div>
											</div>	
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Painel Oficina
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per221','221')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per221 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per221" name="per221" value="<?=$per221;?>">
													</a>													
												</div>
											</div>	
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Relatórios
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per114','114')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per114 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per114" name="per114" value="<?=$per114;?>">
													</a>													
												</div>
												<br>
								   					<p>Permissão Relatório Múltiplos</p>
											</div>	
												<div class="row">
												<hr>
												<div class="col-sm-6">
													Importar O.S
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per152','152')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per152 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per152" name="per152" value="<?=$per152;?>">
													</a>													
												</div>
												<br>
								   					<p>Permissão Incluir O.S por CSV</p>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Situação O.S Oficina
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per222','222')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per222 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per222" name="per222" value="<?=$per222;?>">
													</a>													
												</div>
												<br>
								   					<p>Permissão alterar situação Oficina na Consulta O.S...</p>
											</div>	
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Data Encerramento O.S
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per223','223')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per223 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per223" name="per223" value="<?=$per223;?>">
													</a>													
												</div>
												<br>
								   					<p>Permissão alterar Data  Encerramento O.S...</p>
											</div>		
											
										
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Libera Almoxarifados O.S
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per226','226')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per226 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per226" name="per226" value="<?=$per226;?>">
													</a>													
												</div>
												
											</div>	
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Libera CPF duplicado
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per227','227')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per227 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per227" name="per227" value="<?=$per227;?>">
													</a>													
												</div>
												
											</div>																				
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Ordem de Serviço
												</div>
												<div class="col-xs-12" align="right">
													
													
													Resumo - Ativa em abas Detalhes
													<a onclick="alt('per225','225')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per225 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per225" name="per225" value="<?=$per225;?>">
													</a>	<br>
													Permite Edição Modelo Comercial
													<a onclick="alt('per229','229')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per229 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per229" name="per229" value="<?=$per229;?>">
													</a><br>
													Permite Inativar Reg. Acompanhamento
													<a onclick="alt('per240','240')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per240 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per240" name="per240" value="<?=$per240;?>">
													</a><br>
													Permite visualização LOG salvamento
													<a onclick="alt('per241','241')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per241 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per241" name="per241" value="<?=$per241;?>">
													</a><br><br>
													<p>
													<code style="color:red">"Bloquear"</code> Consulta O.S/Editar
													<a onclick="alt('per242','242')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per242 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per242" name="per242" value="<?=$per242;?>">
													</a>	<br>
													
													<code style="color:red">"Bloquear"</code> Reativar O.S
													<a onclick="alt('per228','228')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per228 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per228" name="per228" value="<?=$per228;?>">
													</a><br>
													
													<code style="color:red">"Bloquear"</code> Resumo da O.S
													<a onclick="alt('per161','161')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per161 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per161" name="per161" value="<?=$per161;?>">
													</a></p>
													<code style="color:red">"Bloquear"</code>Alteração Dados Cliente
													<a onclick="alt('per163','163')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per163 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per163" name="per163" value="<?=$per163;?>">
													</a></p>
													<code style="color:red">"Bloquear"</code>Cadastro Modelo/Aparelho O.S
													<a onclick="alt('per164','164')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per164 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per164" name="per164" value="<?=$per164;?>">
													</a></p>
													
												</div>
												
												
												
											</div>	
											
										
												
											
											
										</div>
									</div>
								</div>
								<!---------------------------------------------------------------------------------------------------------->	
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Vendas</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per014','14')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per014 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per014" name="per014" value="<?=$per014;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Acesso cadastro de serviços, relatórios e comissões...</p>
								   <br>
								   <div class="row">
										<div class="col-sm-6">
											<b></b>
										</div>
										<div class="col-sm-6" align="right">
											<a href="javascript:void(0)" onclick="expandir('avanVendas')"><i class="ti-angle-double-down"></i> Avançado</a>
										</div>
								   </div>

								   <div class="row" style="display:none; padding:10px;" id="avanVendas">
									
										<div class="col-xs-12">	
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Resumo Vendas
												</div>
												<div class="col-xs-6" align="right">
													
													<a onclick="alt('per100','100')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per100 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per100" name="per100" value="<?=$per100;?>">
													</a>
													Consulta por período

												</div>
											</div>

							
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Libera Almoxarifados 
												</div>
												<div class="col-xs-6" align="right">
												
													<a onclick="alt('per115','115')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per115 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per115" name="per115" value="<?=$per115;?>">
													</a>
													
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Libera Baixa Estoque
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per250','250')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per250 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per250" name="per250" value="<?=$per250;?>">
													</a>													
												</div>
												<p>Ativa seleção Baixa Estoque Sim/Não</p>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Relatórios
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per251','251')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per251 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per251" name="per251" value="<?=$per251;?>">
													</a>													
												</div>
												<br>
								   					<p>Permissão Relatório Múltiplos</p>
											</div>	
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Bloquear visualização valores 
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per252','252')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per252 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per252" name="per252" value="<?=$per252;?>">
													</a>													
												</div>
												
											</div>	
											<div class="row">
												<hr>
												<div class="col-sm-6">
												Alterar Vendedor na Venda Finalizada
												</div>
												
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per253','253')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per253 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per253" name="per253" value="<?=$per253;?>">
													</a>													
												</div>
												
											</div>		
										</div>
									</div>
								</div>
								<!---------------------------------------------------------------------------------------------------------->		
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Estoque</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per009','9')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per009 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per009" name="per009" value="<?=$per009;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Acesso ao Estoque, requisição, movimentação, inventários, arquivos de balança, curva ABC, Etiqueta e Relatórios...</p>
								   <br>
								   <div class="row">
										<div class="col-sm-6">
											<b></b>
										</div>
										<div class="col-sm-6" align="right">
											<a href="javascript:void(0)" onclick="expandir('avanEstoque')"><i class="ti-angle-double-down"></i> Avançado</a>
										</div>
								   </div>
										
									<div class="row" style="display:none; padding:10px;" id="avanEstoque">
										<div class="col-xs-12">
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Produtos
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per116','116')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per116 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per116" name="per116" value="<?=$per116;?>">
													</a>
												</div>
												<div class="col-xs-9" align="right">Inclui/Altera
													<a onclick="alt('per401','401')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per401 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per401" name="per401" value="<?=$per401;?>">
													</a>
													<p>
													Visual.Detalhe
													<a onclick="alt('per402','402')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per402 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per402" name="per402" value="<?=$per402;?>">
													</a>
													</p>
												</div>
												
												
											</div>
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Requisição
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per117','117')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per117 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per117" name="per117" value="<?=$per117;?>">
													</a>
												</div>
											</div>
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Movimentação
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per118','118')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per118 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per118" name="per118" value="<?=$per118;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Inventário
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per119','119')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per119 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per119" name="per119" value="<?=$per119;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Supervisor do Inventário
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per219','219')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per219 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per219" name="per219" value="<?=$per219;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Arquivo de balança
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per120','120')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per120 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per120" name="per120" value="<?=$per120;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Curva ABC
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per121','121')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per121 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per121" name="per121" value="<?=$per121;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Etiqueta
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per122','122')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per122 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per122" name="per122" value="<?=$per122;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Relatórios
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per123','123')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per123 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per123" name="per123" value="<?=$per123;?>">
													</a>
												</div>
											</div>
										</div>
									</div>
								</div><!---------------------------------------------------------------------------------------------------------->		
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Configurações</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per010','10')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per010 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per010" name="per010" value="<?=$per010;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Acesso as Configurações do sistema, como: Dados cadastrais, Funcionários e logins, Grupos, Tipo de Cliente, Tipo de fornecedor e muito mais...</p>
								   <br>
								   <div class="row">
										<div class="col-sm-6">
											<b></b>
										</div>
										<div class="col-sm-6" align="right">
											<a href="javascript:void(0)" onclick="expandir('avanConfiguracoes')"><i class="ti-angle-double-down"></i> Avançado</a>
										</div>
								   </div>
										
									<div class="row" style="display:none; padding:10px;" id="avanConfiguracoes">
										<div class="col-xs-12">
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Dados Cadastrais
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per124','124')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per124 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per124" name="per124" value="<?=$per124;?>">
													</a>
												</div>
											</div>
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Funcionários e Logins
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per125','125')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per125 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per125" name="per125" value="<?=$per125;?>">
													</a>
												</div>
											</div>
											
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Grupos
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per126','126')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per126 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per126" name="per126" value="<?=$per126;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Categorias
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per127','127')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per127 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per127" name="per127" value="<?=$per127;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Endereços
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per129','129')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per129!= ""){ echo'checked'; }?> />
														<input type="hidden" id="per129" name="per129" value="<?=$per129;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													 Modelo Produto
												</div>
												<div class="col-xs-6" align="right">													
													<a onclick="alt('per224','224')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per224 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per224" name="per224" value="<?=$per224;?>">
													</a>													
												</div>
												<br>
								   					<p> Aparelhos e Mod.Produto p/ O.S</p>
											</div>	

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Preventivo Equipamentos
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per143','143')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per143 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per143" name="per143" value="<?=$per143;?>">
													</a>													
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Tipo de Fornecedor
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per131','131')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per131 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per131" name="per131" value="<?=$per131;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Almoxarifado
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per132','132')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per132 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per132" name="per132" value="<?=$per132;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Linhas
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per133','133')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per133 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per133" name="per133" value="<?=$per133;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Condições de Pagamentos
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per134','134')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per134 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per134" name="per134" value="<?=$per134;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Livro Caixa
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per135','135')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per135 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per135" name="per135" value="<?=$per135;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Grupo de Receitas e Despesas
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per136','136')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per136 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per136" name="per136" value="<?=$per136;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Contas de Receitas e Despesas
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per137','137')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per137 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per137" name="per137" value="<?=$per137;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Projeto, Centro de Custo
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per138','138')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per138 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per138" name="per138" value="<?=$per138;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Extra A
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per139','139')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per139 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per139" name="per139" value="<?=$per139;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Extra B
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per140','140')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per140 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per140" name="per140" value="<?=$per140;?>">
													</a>
												</div>
											</div>

											<div class="row">
												<hr>
												<div class="col-sm-6">
													Zera Estoque
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per141','141')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per141 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per141" name="per141" value="<?=$per141;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Mensagens Whatsapp
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per144','144')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per144 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per144" name="per144" value="<?=$per144;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Região
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per148','148')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per148 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per148" name="per148" value="<?=$per148;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Avisos
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per149','149')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per149 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per149" name="per149" value="<?=$per149;?>">
													</a>
												</div>
											</div>
											<!--
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Terminais
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per145','145')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per145 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per145" name="per145" value="<?=$per145;?>">
													</a>
												</div>
											</div>
											-->
												<div class="row">
												<hr>
												<div class="col-sm-6">
													Situação Atendimento
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per153','153')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per153 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per153" name="per153" value="<?=$per153;?>">
													</a>
												</div>
											</div>
												<div class="row">
												<hr>
												<div class="col-sm-6">
													Situação Oficina
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per154','154')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per154 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per154" name="per154" value="<?=$per154;?>">
													</a>
												</div>
											</div>
												<div class="row">
												<hr>
												<div class="col-sm-6">
													CFOP - Situação Tributária
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per155','155')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per155 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per155" name="per155" value="<?=$per155;?>">
													</a>
												</div>
											</div>
										
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Tipo de Cliente
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per130','130')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per130 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per130" name="per130" value="<?=$per130;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Customização
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per156','156')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per156 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per156" name="per156" value="<?=$per156;?>">
													</a>
												</div>
											</div>
											<div class="row">
												<hr>
												<div class="col-sm-6">
													Log Acesso
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per151','151')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per151 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per151" name="per151" value="<?=$per151;?>">
													</a>
												</div>
											</div>
												<div class="row">
												<hr>
												<div class="col-sm-6">
													Backup Dowload
												</div>
												<div class="col-xs-6" align="right">
													<a onclick="alt('per162','162')">
														<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="small" <?php if($per162 != ""){ echo'checked'; }?> />
														<input type="hidden" id="per162" name="per162" value="<?=$per162;?>">
													</a>
												</div>
											</div>
										</div>
									</div>
								</div><!---------------------------------------------------------------------------------------------------------->				
							</div>
							<!----PERMISSOES MEU NEGÓCIO------------------->
							<div class="col-sm-4">						
								<h3><i class="fa fa-bullseye text-custom"></i><b> Extras</b></h3>
								
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Menu - Resumo Hoje</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per018','18')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per018 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per018" name="per018" value="<?=$per018;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Novos Clientes,Nova O.S e Tipo Atendimento</p>
								   <br>
								   <b></b>
                                   
								</div>
								
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Menu - Últimas Atividades</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per017','17')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per017 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per017" name="per017" value="<?=$per017;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Visualização Atividades</p>
								   <br>
								   <b></b>                                   
								</div>

								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#00a8e6; text-decoration:underline">Minha Conta</h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per012','12')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per012 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per012" name="per012" value="<?=$per012;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Mensalidade e Tarifas ..</p>
								   <br>
								   <b></b>
                                   
								</div>
								<!--
								<div class="boxDiv">
									<div class="row">
										<div class="col-xs-8">
											<h4 style="color:#F05050; text-decoration:underline"><b>Perfil Financeiro</b></h4>
										</div>
										<div class="col-xs-4" align="right">
											<a onclick="alt('per999','999')">
												<input type="checkbox" data-plugin="switchery" data-color="#00a8e6" data-secondary-color="#98a6ad" data-size="" <?php if($per999 != ""){ echo'checked'; }?> />
												<input type="hidden" id="per999" name="per999" value="<?=$per999;?>">
											</a>
										</div>
									</div>
								   <br>
								   <p>Somente pagamentos e notas fiscais</p>
								   <br>
								   <b></b>
                                   
								</div>
-->
							</div>
						</div>
						<div class="row" style="margin-top:25px;">
							<div class="col-sm-12">
								<button class="btn btn-success waves-effect waves-light" type="submit">Salvar</button>
								<button class="btn btn-default waves-effect waves-light m-l-5" id="voltar" type="button">Voltar</button>						
							</div>
						</div>
						</form>