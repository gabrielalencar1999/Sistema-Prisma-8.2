 <?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body style="background-color: #cccccc; background-image: linear-gradient(#3e3d3d, #bfbfbf, #8c8c8c)">
 <?php require_once('navigatorbar.php'); ?>
 <section  id="home" >
 <div class="container "  >
	<div class="wrapper">
		<form class="form-horizontal m-t-20" id="form1" name="form1" method="post" style="padding-bottom: 140px; "action="">
			<div>
				<div class="row" >
					<div class="col-sm-12">
						<div class="btn-group pull-right m-t-15">                           
						</div>
						<h4 class="page-title"><span style="color:#ffffff">Solução prática para seu negócio</span></h4>
						<p class="text-muted page-title-alt"><span style="color:#fff">Tenha ótimo dia de Trabalho!</span></p>
					</div>
				</div>  

				<div class="card-box" id="bbody" style=" background:rgba(255, 255, 255, 0.9)">	
					<div class="row button-list"  >		
					<div class="col-md-6 col-lg-3">
						<a href="javascript:void(0)" onclick="_00013()">
							<div class="widget-bg-color-icon card-box <?php if($_SESSION['per013'] == ""){ echo 'disabled'; } ?>">
							<div class="bg-icon pull-left">                          
							<i class="icon-basket-loaded fa-2x"></i>                  
							</div>
								<div class="text-left">
									<h4 class="text-dark"><b>PDV/Vendas</b></h4>
									<p class="text-muted">Caixa de Venda</p>
								</div>
								<div class="clearfix"></div>
							</div>
							</a>
						</div>			
						<div class="col-md-6 col-lg-3">
						<a href="javascript:void(0)" onclick="_geral('_Vl00003','100')">
							<div class="widget-bg-color-icon card-box <?php if($_SESSION['per100'] == ""){ echo 'disabled'; } ?>">
							<div class="bg-icon pull-left">                          
							<i class="ti-shopping-cart-full fa-2x"></i>                  
							</div>
								<div class="text-left">
									<h4 class="text-dark"><b>Relação Vendas</b></h4>
									<p class="text-muted">Consulta por período</p>
								</div>
								<div class="clearfix"></div>
							</div>
							</a>
						</div>
						
						<div class="col-md-6 col-lg-3">
						<a href="javascript:void(0)" onclick="_geral('_Vc00021','251')"> <?php //104 ?>
							<div class="widget-bg-color-icon card-box <?php if($_SESSION['per251'] == ""){ echo 'disabled'; } ?>">
							<div class="bg-icon pull-left">                          
							<i class="ti-stats-up fa-2x"></i>                  
							</div>
								<div class="text-left">
									<h4 class="text-dark"><b >Relatorios</b></h4>
									<p class="text-muted">Gerador relatorios</p>
								</div>
								<div class="clearfix"></div>
							</div>
						</a>
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
		
		<script>
			function _geral(_keyid,permissao) {
				$('#_keyform').val(_keyid);

				$.post("verPermissao.php", {permissao:permissao}, function(result){
					if(result != ""){
						$.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
					}else{
						$("#form1").submit();  
					}								  
				});
    		 }

			function _00013(){
                      window.open("/app/v1/pdv/caixa.php", "_blank" );              
                };
			 
		</script>
</section>
</body>
<html>					
	