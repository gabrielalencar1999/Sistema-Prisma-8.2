 <?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body style="background-color: #cccccc; background-image: linear-gradient(#3e3d3d, #bfbfbf, #8c8c8c)">
 <?php require_once('navigatorbar.php'); ?>
 <section id="home"  >
 <div class="container "  >
	<div class="wrapper">
		<form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="" >
			<div>
				<div class="row" >
					<div class="col-sm-12">
						<div class="btn-group pull-right m-t-15">                           
						</div>
						<h4 class="page-title"><span style="color:#fff">Solução prática para seu negócio</span></h4>
						<p class="text-muted page-title-alt"><span style="color:#fff">Tenha ótimo dia de Trabalho!</span></p>
					</div>
				</div>  
				<div class="card-box" id="bbody" style=" background:rgba(255, 255, 255, 0.9)">
					<div class="row "  >
						<div class="col-md-6 col-lg-3"> 
							<a href="javascript:void(0)" onclick="_geral('NFENTLT','110')" >
								<div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per110'] == ""){ echo 'disabled'; } ?>">
									<div class="bg-icon pull-left">                          
										<i class=" ti-import fa-2x"></i>                  
									</div>
									<div class="text-left">
										<h4 class="text-dark"><b > Notas de Entrada</b></h4>
										<p class="text-muted">NF, recibos e consultas</p>
									</div>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-lg-3">
                        <a href="javascript:void(0)" onclick="_geral('NTFCE','107')">
                            <div class="widget-bg-color-icon card-box <?php if($_SESSION['per107'] == ""){ echo 'disabled'; } ?>">
                                <div class="bg-icon pull-left">                          
                                <i class=" ti-zip fa-2x"></i>                  
                            </div>
                                <div class="text-left">
                                    <h4 class="text-dark"><b>Notas Fiscais</b></h4>
                                    <p class="text-muted">Emissão NFe</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                   		 </div>

                        <div class="col-md-6 col-lg-3">
                        <a href="javascript:void(0)" onclick="_geral('CAFORNCLT','112')">
                            <div class="widget-bg-color-icon card-box <?php if($_SESSION['per112'] == ""){ echo 'disabled'; } ?>">
                            <div class="bg-icon pull-left">
                               <i class="md md-assignment-ind md-3x"></i>
                            </div>
                                <div class="text-left">
                                    <h4 class="text-dark"><b>Fornecedores e Fabricantes </b></h4>
                                    <span class="text-muted">Cadastros<span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        </div>
						<div class="col-md-6 col-lg-3">
							<a href="javascript:void(0)" onclick="_geral('NPS','230')">
								<div class="widget-bg-color-icon card-box <?php if($_SESSION['per230'] == ""){ echo 'disabled'; } ?>">
									<div class="bg-icon pull-left">                        
										<i class="ti-medall-alt fa-2x"></i>                  
									</div>
									<div class="text-left">
										<h4 class="text-dark"><b >NPS</b></h4>
										<p class="text-muted">Pesquisa de satisfação</p>
									</div>
									<div class="clearfix"></div>
								</div>
							</a>
               			 </div>
					</div>
						<div class="row "  >
						<div class="col-md-6 col-lg-3"> 
							 <a href="javascript:void(0)" onclick="_geral('PEDIDO_0001','233')" >  
								<div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per233'] == ""){ echo 'disabled'; } ?>">
									<div class="bg-icon pull-left">                          
										<i class=" ti-clipboard fa-2x"></i>                  
									</div>
									<div class="text-left">
										<h4 class="text-dark"><b >Pedidos e Compras </b></h4>
										<p class="text-muted"> Gestão </p>
										
									</div>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
				
						<div class="col-md-6 col-lg-3"> 
							<a href="javascript:void(0)" onclick="_geral('REGPONTO','232')" >
								<div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per232'] == ""){ echo 'disabled'; } ?>">
									<div class="bg-icon pull-left">                          
										<i class="  ti-time fa-2x"></i>                  
									</div>
									<div class="text-left">
										<h4 class="text-dark"><b > Registro Ponto </b></h4>
										<p class="text-muted">Controle</p>
									</div>
									<div class="clearfix"></div>
								</div>
							</a>
						</div>
						
						
						

                 
					
					</div>
				</div>
			</div>
			<input type="hidden" id="_keyform" name="_keyform"  value="">
		</form> 
	</div>
</div>

 <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/routes.js"></script>
		<script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>

        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/plugins/peity/jquery.peity.min.js"></script>

        <!-- jQuery  -->
        <script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

        <script src="assets/plugins/raphael/raphael-min.js"></script>

        <script src="assets/plugins/jquery-knob/jquery.knob.js"></script>

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <!-- Notification js -->
        <script src="assets/plugins/notifyjs/js/notify.js"></script>
        <script src="assets/plugins/notifications/notify-metro.js"></script>    		
</section>
 <script>
     function _geral(_keyid,permissao) {
        $('#_keyform').val(_keyid);

		$.post("verPermissao.php", {permissao:permissao}, function(result){
			if(result != ""){
				$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa função.');
			}else{
				$("#form1").submit();  
			}								  
		});
     }
	 function _v2(_keyid,permissao) {
      
				$.Notification.notify('warning', 'top right','Em breve!', 'Desculpe, ainda não está disponivel essa função.');
									  
	
     }


	 
 </script>
</body>
<html>