<?php require_once('validarlogin.php') ?>

<!DOCTYPE html>
<html>
   <?php require_once('header.php') ?>


    <body>

    <?php require_once('navigatorbar.php');
	use Database\MySQL;
	$pdo = MySQL::acessabd();	?>
     

		<style>
			.circleBase{
				padding: 5px;
				border-radius: 50%;
				text-align: center;
				color: #FFF;
				width: 52px;
				background-color: #F5CE2D;
			}
			.panel-default{
				border:1px solid #333;
				padding:30px !important;
			}
			.panel-default2{
				border:1px solid #333;
				padding:30px !important;
				border-style: dotted;
				opacity:0.6;
				cursor:pointer;
			}
			.panel-default2:hover{
				opacity:1;
			}
			.remove_destq{
				position:absolute;
				top:5px;
				right:10px;
				font-size:24px;
				color:#F05050;
				cursor:pointer;
			}
			.remove_destq:hover{
				color:#cf4545;
			}
		</style>
        <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-6">
                      

                        <h4 class="page-title">Treinamentos</h4>
                        <ol class="breadcrumb">
                            <li><a href="javascript:void(0)" id="_back00000">Menu</a></li>                         
                            <li class="active">Treinamentos</li>
                        </ol>
                    </div>
					<div class="col-sm-6" align="right">
					 <button  class="btn btn-success  waves-effect waves-light"  aria-expanded="false" id="_NovoTopico" ><span class="btn-label btn-label">  <i class="fa fa-plus"></i></span>Novo Tópico</button>
                    </div>
				
                </div>
		
                <div class="row"  style="background-color:#FFF; padding:30px;">
                    <div class="col-lg-12">
						<?php
							$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where destaque_sv <> '0'";
							$stm = $pdo->prepare("$sql");
							$stm->execute();
							if($stm->rowCount() > 0){
						?>	
							<h4>Em Destaque</h4>
							<div class="row">
								<!--------------------------------------------------------------------------------------------------------------------->
								<?php 
									$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where destaque_sv = '1'";
									$stm = $pdo->prepare("$sql");
									$stm->execute();
									if($stm->rowCount() > 0){
										while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
											$titulo = $linha->titulo_sv;
											$subtitulo = $linha->subtitulo_sv;
											$cor = $linha->cor_sv;
											$icon = $linha->icon_sv;
											$id_destaque = $linha->id_sv;
											
										}
									}
									
									
										?>
										<div class="col-sm-3">
											<div class="panel panel-default" style="padding:5px"> 								
											<div class="row" >
												<div class="col-sm-3">				
													<span class="fa-stack fa-lg">
														<div class="circleBase" style="background-color:<?=$cor;?>;"><i class="<?=$icon;?>"></i></div>
													</span>
												</div>
												<div class="col-sm-8">
													 <h5 class="m-t-0 m-b-5"><b><?=$titulo;?></b></h5>
													  <h5 class="text-muted m-b-0 m-t-0"><?=$subtitulo;?></h5>
												</div>	 
											</div>
											</div>
											<a class="remove_destq" onclick="remover_destaq('<?=$id_destaque;?>')" ><i class="fa fa-times-circle-o"></i></a>
										</div>
										<?php }else{ ?>

											<div class="col-sm-3">
												<div class="panel panel-default2" style="padding:5px" data-toggle="modal" data-target="#custom-modal"> 								
												<div class="row" >
													<div class="col-sm-3">				
														<span class="fa-stack fa-lg">
															<div class="circleBase" style="background-color:grey; color:#FFF;"><i class="fa fa-plus"></i></div>
														</span>
													</div>
													<div class="col-sm-8">		
														<h5 class="m-t-0 m-b-5"><b>CLIQUE PARA DESTACAR</b></h5>
														<h5 class="text-muted m-b-0 m-t-0"></h5>
													</div>	 
												</div>
												</div>
											</div>

											<?php } ?>
								<!--------------------------------------------------------------------------------------------------------------------->
								<?php 
									$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where destaque_sv = '2'";
									$stm = $pdo->prepare("$sql");
									$stm->execute();
									if($stm->rowCount() > 0){
										while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
											$titulo = $linha->titulo_sv;
											$subtitulo = $linha->subtitulo_sv;
											$cor = $linha->cor_sv;
											$icon = $linha->icon_sv;
											
										}
										?>
										<div class="col-sm-3">
											<div class="panel panel-default" style="padding:5px"> 								
											<div class="row" >
												<div class="col-sm-3">				
													<span class="fa-stack fa-lg">
														<div class="circleBase" style="background-color:<?=$cor;?>;"><i class="<?=$icon;?>"></i></div>
													</span>
												</div>
												<div class="col-sm-8">		
													 <h5 class="m-t-0 m-b-5"><b><?=$titulo;?></b></h5>
													  <h5 class="text-muted m-b-0 m-t-0"><?=$subtitulo;?></h5>
												</div>	 
											</div>
											</div>
										</div>
								<?php }else{ ?>

										<div class="col-sm-3">
											<div class="panel panel-default2" style="padding:5px" onclick="sel_destaq('2')"> 								
											<div class="row" >
												<div class="col-sm-3">				
													<span class="fa-stack fa-lg">
														<div class="circleBase" style="background-color:grey; color:#FFF;"><i class="fa fa-plus"></i></div>
													</span>
												</div>
												<div class="col-sm-8">		
													 <h5 class="m-t-0 m-b-5"><b>CLIQUE PARA DESTACAR</b></h5>
													  <h5 class="text-muted m-b-0 m-t-0"></h5>
												</div>	 
											</div>
											</div>
										</div>

								<?php } ?>
								<!--------------------------------------------------------------------------------------------------------------------->		
								<?php 
									$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where destaque_sv = '3'";
									$stm = $pdo->prepare("$sql");
									$stm->execute();
									if($stm->rowCount() > 0){
										while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
											$titulo = $linha->titulo_sv;
											$subtitulo = $linha->subtitulo_sv;
											$cor = $linha->cor_sv;
											$icon = $linha->icon_sv;
											
										}
										?>
										<div class="col-sm-3">
											<div class="panel panel-default" style="padding:5px"> 								
											<div class="row" >
												<div class="col-sm-3">				
													<span class="fa-stack fa-lg">
														<div class="circleBase" style="background-color:<?=$cor;?>;"><i class="<?=$icon;?>"></i></div>
													</span>
												</div>
												<div class="col-sm-8">		
													 <h5 class="m-t-0 m-b-5"><b><?=$titulo;?></b></h5>
													  <h5 class="text-muted m-b-0 m-t-0"><?=$subtitulo;?></h5>
												</div>	 
											</div>
											</div>
										</div>
										<?php }else{ ?>

											<div class="col-sm-3">
												<div class="panel panel-default2" style="padding:5px" onclick="sel_destaq('3')"> 								
												<div class="row" >
													<div class="col-sm-3">				
														<span class="fa-stack fa-lg">
															<div class="circleBase" style="background-color:grey; color:#FFF;"><i class="fa fa-plus"></i></div>
														</span>
													</div>
													<div class="col-sm-8">		
														<h5 class="m-t-0 m-b-5"><b>CLIQUE PARA DESTACAR</b></h5>
														<h5 class="text-muted m-b-0 m-t-0"></h5>
													</div>	 
												</div>
												</div>
											</div>

											<?php } ?>
								<!--------------------------------------------------------------------------------------------------------------------->		
								<?php 
									$sql="select * from " . $_SESSION['BASE'] . ".salavirtual where destaque_sv = '4'";
									$stm = $pdo->prepare("$sql");
									$stm->execute();
									if($stm->rowCount() > 0){
										while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
											$titulo = $linha->titulo_sv;
											$subtitulo = $linha->subtitulo_sv;
											$cor = $linha->cor_sv;
											$icon = $linha->icon_sv;
											
										}
										?>
										<div class="col-sm-3">
											<div class="panel panel-default" style="padding:5px"> 								
											<div class="row" >
												<div class="col-sm-3">				
													<span class="fa-stack fa-lg">
														<div class="circleBase" style="background-color:<?=$cor;?>;"><i class="<?=$icon;?>"></i></div>
													</span>
												</div>
												<div class="col-sm-8">		
													 <h5 class="m-t-0 m-b-5"><b><?=$titulo;?></b></h5>
													  <h5 class="text-muted m-b-0 m-t-0"><?=$subtitulo;?></h5>
												</div>	 
											</div>
											</div>
										</div>
										<?php }else{ ?>
											<div class="col-sm-3">
												<div class="panel panel-default2" style="padding:5px" onclick="sel_destaq('4')"> 								
												<div class="row" >
													<div class="col-sm-3">				
														<span class="fa-stack fa-lg">
															<div class="circleBase" style="background-color:grey; color:#FFF;"><i class="fa fa-plus"></i></div>
														</span>
													</div>
													<div class="col-sm-8">		
														<h5 class="m-t-0 m-b-5"><b>CLIQUE PARA DESTACAR</b></h5>
														<h5 class="text-muted m-b-0 m-t-0"></h5>
													</div>	 
												</div>
												</div>
											</div>

										<?php } ?>
								<!--------------------------------------------------------------------------------------------------------------------->
							</div>
						<div class="row">
							<div class="col-sm-2">
									<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal" disabled><i class="fa fa-file-video-o"></i> Vídeos</button>
							</div>
						</div>
			<br>
			<div class="row">
				<div class="col-sm-4">
					<table class="table table-bordered table-condesed table-stripped">
						<tr>
							<th>Operacional</th>
							<th colspan="2"></th>
							<th>Status</th>
						</tr>
						<tr>
						<?php
							$sql="select id_sv,titulo_sv,ativo_sv from " . $_SESSION['BASE'] . ".salavirtual where tipo_sv = '0'";
							$stm = $pdo->prepare("$sql");
							$stm->execute();
							while ($linha = $stm->fetch(PDO::FETCH_OBJ)){?>
							<tr>
								<td><?=$linha->id_sv;?> - <?=$linha->titulo_sv;?></td>
								<td><button type="button" class="btn btn-danger btn-sm" onclick="delet('<?=$linha->id_sv;?>')"><i class="fa fa-times"></i></button></td>
								<td><button type="button" class="btn btn-warning btn-sm" onclick="alterar('<?=$linha->id_sv;?>')"><i class="fa fa-pencil"></i></button></td>
								<td>
									<select class="form-control input-sm" id="status" name="status" onchange="sta(this.value,'<?=$linha->id_sv;?>')">
										<option value="0" <?php if($linha->ativo_sv == '0'){ echo'selected="selected"';}?>>Inativo</option>
										<option value="-1"<?php if($linha->ativo_sv == '-1'){ echo'selected="selected"';}?>>Ativo</option>
									</select>
								</td>
							</tr>
								<?php 
							}
						?>
					</table>
				</div>
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
					<table class="table table-bordered table-condesed table-stripped">
						<tr>
							<th>Gerencial</th>
							<th colspan="2"></th>
							<th>Status</th>
						</tr>
						<tr>
						<?php
							$sql="select id_sv,titulo_sv,ativo_sv from " . $_SESSION['BASE'] . ".salavirtual where tipo_sv = '1'";
							$stm = $pdo->prepare("$sql");
							$stm->execute();
							while ($linha = $stm->fetch(PDO::FETCH_OBJ)){?>
							<tr>
								<td><?=$linha->id_sv;?> - <?=$linha->titulo_sv;?></td>
								<td><button type="button" class="btn btn-danger btn-sm" onclick="delet('<?=$linha->id_sv;?>')"><i class="fa fa-times"></i></button></td>
								<td><button type="button" class="btn btn-warning btn-sm" onclick="alterar('<?=$linha->id_sv;?>')"><i class="fa fa-pencil"></i></button></td>
								<td>
									<select class="form-control input-sm" id="status" name="status" onchange="sta(this.value,'<?=$linha->id_sv;?>')">
										<option value="0" <?php if($linha->ativo_sv == '0'){ echo'selected="selected"';}?>>Inativo</option>
										<option value="-1"<?php if($linha->ativo_sv == '-1'){ echo'selected="selected"';}?>>Ativo</option>
									</select>
								</td>
							</tr>
								<?php
							}
						?>
					</table>
				</div>
			</div>

				
                    </div>
                    <!-- end col-12 -->
                </div> <!-- end row -->
            </div> <!-- end container -->
        </div>
        <form  id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform"  value="">
            <input type="hidden" id="idA" name="idA"  value="">
            <input type="hidden" id="acao" name="acao"  value="">
        </form>
        <!-- end wrapper -->

		<div id="custom-modal" class="modal-demo">
            <button type="button" class="close" onclick="Custombox.close();">
                <span>×</span><span class="sr-only">Close</span>
            </button>
            <h4 class="custom-modal-title">Modal title</h4>
            <div class="custom-modal-text">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </div>
        </div>



        <!-- jQuery  -->
        <script src="../app/assets/js/jquery.min.js"></script>
        <script src="../app/assets/js/bootstrap.min.js"></script>
        <script src="../app/assets/js/detect.js"></script>
        <script src="../app/assets/js/fastclick.js"></script>
        <script src="../app/assets/js/jquery.slimscroll.js"></script>
        <script src="../app/assets/js/jquery.blockUI.js"></script>
        <script src="../app/assets/js/waves.js"></script>
        <script src="../app/assets/js/wow.min.js"></script>
        <script src="../app/assets/js/jquery.nicescroll.js"></script>
        <script src="../app/assets/js/jquery.scrollTo.min.js"></script>
		<script src="../app/assets/js/routesGerencial.js"></script>

        <script src="../app/assets/plugins/jquery-ui/jquery-ui.min.js"></script>

        <!-- BEGIN PAGE SCRIPTS -->
        <script src="../app/assets/plugins/moment/moment.js"></script>
        <script src='../app/assets/plugins/fullcalendar/js/fullcalendar.min.js'></script>
        <script src="../app/assets/pages/jquery.fullcalendar.js"></script>

        <!-- App core js -->
        <script src="../app/assets/js/jquery.core.js"></script>
		<script src="../app/assets/js/jquery.app.js"></script>
		
      <!-- ladda js -->
		<script src="../app/assets/plugins/ladda-buttons/js/spin.min.js"></script>
		<script src="../app/assets/plugins/ladda-buttons/js/ladda.min.js"></script>
		<script src="../app/assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>

		<script>
			$(_NovoTopico).click(function(){       
				var $_keyid =   "_G002"; 
				$('#_keyform').val($_keyid);     
				$('#acao').val("");  
				$('#idA').val("");				
				$("#form1").submit();                 
			});	

			function alterar(id){      
				var $_keyid =   "_G002"; 
				$('#_keyform').val($_keyid);     
				$('#idA').val(id);
				$('#acao').val("");				
				$("#form1").submit();                 
			}		
				function delet(id){
				var result = confirm("Deseja deletar o ID["+id+"]?");
				if (result) {
					
					var $_keyid = "_G003"; 					
					$.post("page_return.php", {_keyform:$_keyid, variable:id, acao:"deletar"}, function(resultado){		
						location.reload();
					});
				}
			}	
			function sta(valor,id){
				var $_keyid = "_G003";
				var variable = id +"|"+valor;
				
				$.post("page_return.php", {_keyform:$_keyid, variable:variable, acao:"statusAtivo"}, function(resultado){	
					alert("Status Alterado com sucesso!");
					alert(resultado);
				});				
			
			}	
			function sel_destaq(sequencia){
				var $_keyid = "_G003";

				$.post("page_return.php", {_keyform:$_keyid, id:id, acao:"removeDestaque"}, function(resultado){	
					Location.reload();
				});
			}		
			function remover_destaq(id){
				var $_keyid = "_G003";

				$.post("page_return.php", {_keyform:$_keyid, id:id, acao:"removeDestaque"}, function(resultado){	
					Location.reload();
				});	
			}
		</script>
    </body>
</html>