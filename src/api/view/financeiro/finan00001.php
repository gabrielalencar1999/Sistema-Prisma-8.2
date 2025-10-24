<!DOCTYPE html>
<html>
	<?php require_once('header.php') ; 
		date_default_timezone_set('America/Sao_Paulo');
	?>
	        <!-- DataTables -->
        <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>

     
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>
		<style>
			@media only screen and (max-width: 934px) {
				#spaceDesktop{
					display:none;
				}
				#desktop{
					display:none;
				}
				#smartphone{
					
					padding:10px;
				    text-align:center;
				}
			}
			@media only screen and (min-width: 935px) {
				#smartphone{
					display:none;
					
				}
			}
			.box {
				padding:15px;
				padding-bottom:10px;
				min-width: 120px;
				height: 120px;
				cursor:pointer;
			}
			.scrolling-wrapper {
				text-align:center;
				display: flex;
				flex-wrap: nowrap;
				overflow-x: scroll;
				
				-webkit-box-shadow: inset 0px -22px 34px 10px rgba(255,255,255,1);
				-moz-box-shadow: inset 0px -22px 34px 10px rgba(255,255,255,1);
				box-shadow: inset 0px -22px 34px 10px rgba(255,255,255,1);
			}
			
			.categoria_title{
				font-size:12px;
				line-height:15px;
				color:#666;
			
			}
			.catCor{
				 width:30px; 
				 height:30px;
				 margin:5px; float:left;
				 cursor:pointer;
				 border-radius:50%;
			}
			.styleIcon{
				margin:5px;
				color:#666;
				cursor:pointer;
				font-size:20px;
			}
			.styleIcon2{
				font-size:28px;
			}
			.boxC{
				margin-top:15px;
				border:1px solid #D9D9D9;
				border-radius:4px;
				padding:15px;
			}
			.boxD{
				margin-top:15px;
				border:1px solid #D9D9D9;
				border-radius:4px;
				padding:15px;
			}
			.nc{
				cursor:pointer;
			}
			
			.selCor{
				 width:40px; 
				 height:40px;
			}
			
			.option{
				cursor:pointer;
			}
			.marg{
				margin-top:10px;
			}
		</style>
		</head>
    <body id="body">
    <?php require_once('navigatorbar.php');
		
		if($data_ini == ""){
			$data_ini = date('Y-m-d');
		}
		if($data_fim == ""){
			$data_fim = date('Y-m-d');
		}
		
		
		
		//$_SESSION['BASE'] = "bd_G000001";
	?>

        <div class="wrapper" style="padding-top:56px;">
			<div class="row">
				<div class="col-sm-2" id="spaceDesktop"></div>
				<div class="col-sm-8 styleTotal" id="desktop">
					<div class="row">
						<div class="col-sm-3">	
							<div class="bar-widget">
								<div class="table-box">
									<div class="table-detail">
										<div class="iconbox">
											<i class="md md-attach-money" style="color:#81C868; font-size:32px;"></i>
										</div>
									</div>
									<div class="table-detail">
										<h4 class="m-t-0 m-b-5"><b>Receitas</b></h4>
										<p class="m-b-0 m-t-0 text-success" style="font-size:18px;"><span id="resReceita">R$ 0,00</span></p>
									</div>
								</div>
							</div>      
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-3">
							<div class="bar-widget">
								<div class="table-box">
									<div class="table-detail">
										<div class="iconbox">
											<i class="ti-receipt" style="color:#F05050; font-size:32px;"></i>
										</div>
									</div>
									<div class="table-detail">
										<h4 class="m-t-0 m-b-5"><b>Despesas</b></h4>
										<p class="m-b-0 m-t-0 text-danger" style="font-size:18px;"><span id="resDespesa">R$ 0,00</span></p>
									</div>
								</div>
							</div>         
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-3">
							<div class="bar-widget">
								<div class="table-box">
									<div class="table-detail">
										<div class="iconbox">
											<i class="ion-android-sort" style="color:#00a8e6; font-size:32px; "></i>
										</div>
									</div>
									<div class="table-detail">
										<h4 class="m-t-0 m-b-5"><b>Balanço</b></h4>
										<p class="m-b-0 m-t-0 text-custom" style="font-size:18px;"><span id="resBalanco">R$ 0,00</span></p>
									</div>
								</div>
							</div>   						
						</div>
					</div>
				</div>
				<!---SMARTPHONE--->
				<div class="col-sm-12 styleTotal" id="smartphone">
					<div class="row">
						<div class="col-xs-4">	
							<i class="md md-attach-money" style="color:#81C868; font-size:32px;"></i>
							<div class="table-detail">
								<h5 class="m-t-0 m-b-5">Receitas</h5>
								<p class="m-b-0 m-t-0 text-success" style="font-size:18px;"><span id="resReceita1">R$ 0,00</span></p>
							</div>   
						</div>
						<div class="col-xs-4" style="margin-top:10px;">
							<i class="ti-receipt" style="color:#F05050; font-size:32px;"></i>
							<br>
							<div class="table-detail">
								<h5 class="m-t-0 m-b-5">Despesas</h5>
								
								<p class="m-b-0 m-t-0 text-danger" style="font-size:18px;"><span id="resDespesa1">R$ 0,00</span></p>
							</div>
						</div>
						<div class="col-xs-4" style="margin-top:10px;">
							<i class="ion-android-sort" style="color:#00a8e6; font-size:32px; "></i>
							<br>
							<div class="table-detail">
								<h5 class="m-t-0 m-b-5">Balanço</h5>
								<p class="m-b-0 m-t-0 text-custom" style="font-size:18px;"><span id="resBalanco1">R$ 0,00</span></p>
							</div>
						</div>
					</div>
				</div>			
			</div>

            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">
						 <span style="margin:5px">
							<button type="button" class="btn btn-default waves-effect waves-light"  aria-expanded="false" id="_000002" data-toggle="modal" data-target="#modalfiltro">Filtros<span class="m-l-5"><i class="fa  fa-cog"></i></span></button>
						</span>
						 <span style="margin:5px"><button type="button" class="btn btn-success  waves-effect waves-light"  aria-expanded="false" data-toggle="modal" data-target="#modalLancamento" onclick="openModal('')">Novo Lançamento<span class="m-l-5"><i class="fa  fa-plus"></i></span></button></span>
						</div>
                        <ol class="breadcrumb">
							<li><h4 class="page-title">Financeiro</h4></li>
                        </ol>
                    </div>
                </div>




                <div class="row">
                    <div class="col-sm-12" id="resposta">
						<?php
							
							$_parametros = array();	
							require_once('../../api/view/financeiro/finan00001_list.php'); 
							$_parametros = "";
							
							
						?>
                    </div>
                </div>
                <!-- end row -->
 <!----------------MODAL FILTROS------------------------------------------------------------------------------>                     
				         <form id="form2" name="form2">

                            <div id="modalfiltro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Filtros</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="field-1" class="control-label">Filtrar por</label>
													<Select  class="form-control" id="tipoData" name="tipoData">
														<option value="financeiro_emissao">Dt Lançamento</option>
														<option value="financeiro_vencimento">Dt Vencimento</option>
														<option value="financeiro_dataFim">Dt Pago</option>
													</Select>
                                                </div>   
                                                <div class="col-md-4">
													<label for="field-1" class="control-label">Período de </label>
                                                    <div class="form-group">                                                       
                                                        <input type="date" class="form-control" name="dataIni"  id="dataIni" value="<?=$data_ini;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
													<label for="field-1" class="control-label">Até </label>
                                                    <div class="form-group">                                                      
                                                        <input type="date" class="form-control"  name="dataFim"  id="dataFim" value="<?=$data_fim;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
													<label for="field-1" class="control-label">Ordenar por </label>
                                                    <Select  class="form-control" id="ordenar" name="ordenar">
														<option value="DESC">Descrescente</option>
														<option value="ASC">Crescente</option>														
													</Select>
                                                </div>
                                            </div>
											<br>
											<div class="row">
												<div class="col-sm-3">
													<label class="control-label">Tipo Lançamento</label>
													<select class="form-control" name="financeiro_tipo" id="financeiro_tipo" onchange="selTipo2(this.value)">
														<option value="">Ambos</option>
														<option value="0">Receita</option>
														<option value="1">Despesa</option>
													</select>
												</div> 
												<div class="col-md-9">
													<label for="field-1" class="control-label">Buscar&nbsp;por</label>
													<div class="form-group">                                                       
														<input type="text" class="form-control" name="Descricao" id="Descricao" placeholder="Descrição ou N° Documento">
													</div>
												</div>  												
											</div>
											<div class="row">  
												<div class="col-md-12">
													<label class="control-label">Filtrar por Categoria e Subcategoria</label>  
												</div>
											</div>
											
											<div class="scrolling-wrapper" id="divCategoria2" >  	
												<?php require_once('../../api/view/financeiro/finanCategoria2.php'); ?>
											</div>
											<div class="row">  
												<div class="col-sm-12" id="divSubcategoria2"  style="display:none;"></div>
											</div>
											<br>
											<div class="row">  
												<div class="col-md-9">
													<a class="option" onclick="maisOpcoes('2')">Mais Opções <i class="ti-angle-down"></i></a>    
												</div>   
											</div>
											<!--===============================================================================================================-->
                                            <div class="row boxF2" style="display:none;">	
											<br>
                                            
												<div class="col-md-3">
													<label class="control-label">Por...</label>
													<select class="form-control" name="financeiro_tipoQuem2" id="financeiro_tipoQuem2"  onchange="seltipoQuem2(this.value,'F')">
														<option value=""></option>
														<option value="1">Consumidor</option>
														<option value="2">Fornecedor</option>
														<option value="3">Usuário</option>
													</select>
												</div>	
												<div class="col-md-9" id="tipoQm2">
													<label class="control-label">Nome</label> 
													<input type="text" class="form-control" id="financeiro_codigoCliente2" name="financeiro_codigoCliente2" placeholder="selecione..." disabled>
												</div>
												<div class="col-md-4">
													<label class="control-label">Tipo pagamento</label>
													<?php require_once('../../api/view/financeiro/tipoPagamento_filtro.php'); ?>
												</div>
												<div class="col-md-4">
													<label class="control-label">Situação Pgto</label>
													<select class="form-control" name="sit_financeiro" id="sit_financeiro">
														<option value="">Todos</option>
														<option value="1">Em Aberto</option>
														<option value="2">Pago</option>
													</select>
												</div>
                                            </div>
											<br>
											<!--===============================================================================================================-->
											<input type="hidden" id="pesquisaCategoria" name="pesquisaCategoria" value="">
											<input type="hidden" id="pesquisaSubCategoria" name="pesquisaSubCategoria" value="">
											<input type="hidden" id="canceladas" name="canceladas" value="0">
											<div class="modal-footer">
												<div class="col-md-3">
													<div class="col-md-2">	
														<div class="checkbox checkbox-custom checkbox-circle">
															<input id="checkCancelado" name="checkCancelado" type="checkbox" onchange="checkCancel()">
															<label for="checkCancelado">Canceladas</label>
														</div>
													</div>
												</div>										
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div><!-- /.modal -->
                        </form>
				
<!----------------MODAL NOVO LANCAMENTO E ALTERAR---------------------------------------------------------------------------->
						<form id="form3" name="form3">
                      
                            <div id="modalLancamento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog  ">
                                    <div class="modal-content" id="divAlterar">
										. . .
                                    </div>
                                </div>
                            </div>
						</form>
<!-- /.modal ---------------------------------------------------------------------------------------------------------------------------------------------------------->

                        
             

            
        </div>
        <form id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform"  value="">
			<input type="hidden" id="_chaveid" name="_chaveid"  value="">
        </form>
        <!-- end wrapper -->



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/routes.js"></script>
		
		<script src="assets/js/bootbox.all.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.colVis.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

        <script src="assets/pages/datatables.init.js?v=1"></script>

        <!-- App core js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <script type="text/javascript">

			$(document).ready(function () {

                $('#datatable').dataTable();
                $('#datatable-keytable').DataTable({keys: true});
                $('#datatable-responsive').DataTable();
                $('#datatable-colvid').DataTable({
                    "dom": 'C<"clear">lfrtip',
                    "colVis": {
                        "buttonText": "Change columns"
                    }
                });
                $('#datatable-scroller').DataTable({
                    ajax: "assets/plugins/datatables/json/scroller-demo.json",
                    deferRender: true,
                    scrollY: 380,
                    scrollCollapse: true,
                    scroller: true
                });
                var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
                var table = $('#datatable-fixed-col').DataTable({
                    scrollY: "300px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    }
                });
            });
            TableManageButtons.init();
					
			
			
			var search = $(_00003).click(function(){       
					var $_keyid =   "_Fl00005";    			
					var dados = $("#form2 :input").serializeArray();
					dados = JSON.stringify(dados);
					
					$.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){		
						if(result == 1){ }else{
													
							$("#resposta").html(result);						  
							handleDataTableButtons();
							$('#modalfiltro').modal('hide');
						}
					});
					
			});

			function  _000010($_idref){
					// $('#custom-width-modal').modal('hide');                     
				//$('#custom2-width-modal').modal('show');

				
				var $_keyid =   "_Vc00010";    
				
				$('#_chaveid').val($_idref);
				$('#_keyform').val($_keyid);
				$("#form1").submit();                 
											

			};
			
		

			function NC(){
			  $( ".boxC" ).toggle( "slow", function() {
				// Animation complete.
					$(".boxD").css("display","none");
			  });
			};
			
			function selc(cor,id){
				
				var div = "#cor" + id;
				$("#cor").val(cor);

				$(".catCor").removeClass("selCor");				
				$(div).addClass("selCor");

			}
			function seli(icon,id){
				
				var div = "#icon" + id;
				$("#icon").val(icon);

				$(".styleIcon").removeClass("styleIcon2");				
				$(div).addClass("styleIcon2");

			}
			
			function selCategoria(id){
				$(".boxD").css("display","");
				$("#financeiro_grupo").val(id);
				
				var $_keyid =   "_Fl00008";
				$.post("page_return.php", {_keyform:$_keyid , _var:id}, function(result){		
					if(result == 1){ }else{
						//alert(result);
						
						$(".boxD").html(result);							
						//
					}
				});
			}
			
			function selCategoria2(id){
				$( "#divCategoria2" ).toggle( "slow", function(){});
				
				$("#pesquisaCategoria").val(id);
				
				var $_keyid =   "_Fl00010";
				$.post("page_return.php", {_keyform:$_keyid , _var:id}, function(result){		
					if(result == 1){ }else{
						//alert(result);
						
						$("#divSubcategoria2").html(result);							
						$( "#divSubcategoria2" ).toggle( "slow", function(){});
					}
				});
			}
			function volt(){
				$( "#divSubcategoria2" ).toggle( "slow", function(){});
				$( "#divCategoria2" ).toggle( "slow", function(){});
				
				$("#pesquisaCategoria").val("");
				$("#pesquisaSubCategoria").val("");
			}
			function selecionarSubcategoria(id){
				$("#financeiro_subgrupo").val(id);	
			}
			function selecionarSubcategoria2(id){
				$("#pesquisaSubCategoria").val(id);	
			}
			
			function calculaTotal(){

				//============RECEITA=================================/
					var elemento1 = document.getElementById('vlrTotalReceita');
					var valor1 = elemento1.value;
					

					valor1 = valor1 + '';
					valor1 = parseInt(valor1.replace(/[\D]+/g, ''));
					valor1 = valor1 + '';
					valor1 = valor1.replace(/([0-9]{2})$/g, ",$1");
					

					if (valor1.length > 6) {
						valor1 = valor1.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
					}

					$("#resReceita").html("R$ "+valor1);
					$("#resReceita1").html("R$ "+valor1);
					if(valor1 == 'NaN' || valor1 == '0'){
						$("#resReceita").html("R$ 0,00");
						$("#resReceita1").html("R$ 0,00");
					};
				//==================================================/

				//============DESPESA=================================/
					var elemento2 = document.getElementById('vlrTotalDespesa');
					var valor2 = elemento2.value;				
					
					valor2 = valor2 + '';
					valor2 = parseInt(valor2.replace(/[\D]+/g, ''));
					valor2 = valor2 + '';
					valor2 = valor2.replace(/([0-9]{2})$/g, ",$1");
					

					if (valor2.length > 6) {
						valor2 = valor2.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
					}
					
					$("#resDespesa").html("R$ "+valor2);
					$("#resDespesa1").html("R$ "+valor2);
					if(valor2 == 'NaN' || valor2 == '0'){
						$("#resDespesa").html("R$ 0,00");
						$("#resDespesa1").html("R$ 0,00");
					};
				//==================================================/


				//============BALANCO=================================/
					var elemento3 = document.getElementById('vlrTotalBalanco');
					var valor3 = elemento3.value;
					var rs = "R$";
					if(parseInt(valor3) < 0){
						var rs = "- R$";
					}

					valor3 = valor3 + '';
					valor3 = parseInt(valor3.replace(/[\D]+/g, ''));
					valor3 = valor3 + '';
					valor3 = valor3.replace(/([0-9]{2})$/g, ",$1");
					

					if (valor3.length > 6) {
						valor3 = valor3.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
					}
					
					

					$("#resBalanco").html(rs+" "+valor3);
					$("#resBalanco1").html(rs+" "+valor3);
					if(valor3 == 'NaN' || valor3 == '0'){
						$("#resBalanco").html("R$ 0,00");
						$("#resBalanco1").html("R$ 0,00");
					};
				//==================================================/

			}
			
			function criarSub(){
				  $( "#cSub" ).toggle( "slow", function() {
					// Animation complete.
						//$(".boxD").css("display","none");
				  });

			}

			function maisOpcoes(t){
				if(t == 1){
				  $( ".boxF" ).toggle( "slow", function(){});
				}
				if(t == 2){
				  $( ".boxF2" ).toggle( "slow", function(){ });
				}
			}
			
			function selTipo(tipo){
					$(".boxD").css("display","none");
					if(tipo == 0){
						$("#quem").html("Receber de...");
					}else{
						$("#quem").html("Pagar o...");
					}
					$.post("page_return.php", {_keyform:"_Fl00007",acao:tipo}, function(result){	
						if(result == 1){ }else{												
							$("#divCategoria").html(result);						  
						}
					});				

			}
			function selTipo2(tipo){
				var div = $("#pesquisaCategoria").val();
				if(div != ""){
					volt();
				}
				
				$.post("page_return.php", {_keyform:"_Fl00011",acao:tipo}, function(result){	
					if(result == 1){ }else{												
						$("#divCategoria2").html(result);						  
					}
				});
					
			}

			function seltipoQuem(tipo){
				var _idconsumidor =   $("#idconsumidor").val();
				var _idOS =   $("#financeiro_documento").val();
				$.post("page_return.php", {_keyform:"_Fl00009",acao:tipo,idconsumidor:_idconsumidor,idOS:_idOS}, function(result){	
					if(result == 1){ }else{	
						$("#tipoQm").html(result);						  
					}
				});				
			}

			function seltipoQuem2(tipo,tipoPesq){
				$.post("page_return.php", {_keyform:"_Fl00009",acao:tipo , tipoPesq:tipoPesq}, function(result){	
					if(result == 1){ }else{	
						$("#tipoQm2").html(result);						  
					}
				});				
			}

			function gerarSub(){
				$(".boxD").css("display","");
				
				var $_keyid =   "_Fl00008";
				var dados = $("#form3 :input").serializeArray();
				dados = JSON.stringify(dados);			
				$.post("page_return.php", {_keyform:$_keyid, dados:dados}, function(result){	
					
					if(result == 1){ }else{
						$(".boxD").html(result);							
						//
					}
				});
			}

			
			function formatarMoeda(inputt) {
				var elemento = document.getElementById(inputt);
				var valor = elemento.value;
				

				valor = valor + '';
				valor = parseInt(valor.replace(/[\D]+/g, ''));
				valor = valor + '';
				valor = valor.replace(/([0-9]{2})$/g, ",$1");
				

				if (valor.length > 6) {
					valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
				}

				elemento.value = valor;
				if(valor == 'NaN') elemento.value = '';
				
			} 
			
			function openModal(id){
				var carrega = '<div class="bg-icon pull-request text-center"><img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde."><h2>Aguarde, carregando dados...</h2></div>';
				$("#divAlterar").html(carrega);	
				var $_keyid = "ALT1FINAN";
				
				$.post("page_return.php", {_keyform:$_keyid, variable:id}, function(result){		
						$("#divAlterar").html(result);	
					});	
			}
			
			function checkCancel(){
				var chklista = $('input[name="checkCancelado"]:checked').toArray().map(function(check) { 
					return $(check).val(); 
				});
				var chk01 = $('#checkCancelado').is(':checked');
				var sit = "x"+chk01;
				if(sit == "xtrue"){
					$("#canceladas").val("1");
				}else{
					$("#canceladas").val("0");
				}		
			}
			function cancelar(id){
				bootbox.confirm({
					message: "Deseja realmente cancelar o lançamento?",
					buttons: {
						confirm: {
							label: 'Sim',
							className: 'btn-success'
						},
						cancel: {
							label: 'Não',
							className: 'btn-danger'
						}
					},
					callback: function (result) {
						var resultado = "x"+result;
						if(resultado == "xtrue"){
							$("#financeiro_situacaoID").val("1");	
							$("#alterar").click();
						}	
					}
				});
			}

			function desCancelar(id){
				bootbox.confirm({
					message: "Deseja recuperar esse lançamento?",
					buttons: {
						confirm: {
							label: 'Sim',
							className: 'btn-success'
						},
						cancel: {
							label: 'Não',
							className: 'btn-danger'
						}
					},
					callback: function (result) {
						var resultado = "x"+result;
						if(resultado == "xtrue"){
							$("#financeiro_situacaoID").val("0");	
							$("#alterar").click();
						}									
					}
				});
			}
        </script>


    </body>
</html>