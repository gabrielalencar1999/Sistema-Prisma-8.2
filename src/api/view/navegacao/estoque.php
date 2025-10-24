 <?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body style="background-color: #cccccc; background-image: linear-gradient(#3e3d3d, #bfbfbf,  #d3d6e5)">
 <?php require_once('navigatorbar.php'); ?>
 <section  id="home" >
 <div class="container "  >
	<div class="wrapper">
		<form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
            <div class="row" >
                <div class="col-sm-12">
                    <div class="btn-group pull-right m-t-15">
                    </div>
                    <h4 class="page-title"><span style="color:#fff">Solução prática para seu negócio</span></h4>
                    <p class="text-muted page-title-alt"><span style="color:#fff">Tenha ótimo dia de Trabalho!</span></p>
                </div>
            </div>
            <div class="card-box" id="bbody" style=" background:rgba(255, 255, 255, 0.9)">
                <div class="row button-list"  >
                    <div class="col-md-6 col-lg-3">
                        <a href="javascript:void(0)" id="nf_entrada" onclick="_geral('PRDLT','116')">
                            <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per116'] == ""){ echo 'disabled'; } ?>">
                                <div class="bg-icon pull-left">
                                    <i class="ti-bag"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="text-dark"><b > Produtos</b></h4>
                                    <p class="text-muted">Inclusão, Alteração e exclusão</p>
                                </div>
                            </div>
                        </a>
                    </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('RQEST','117')">				
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per117'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                  <i class="ti-layers-alt"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Requisição</b></h4>
                                   <p class="text-muted">Saídas,Entradas e transfências</p>
                               </div>
                           </div>
                       </a>
                   </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('MVEST','118')">				
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per118'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                  <i class="glyphicon glyphicon-transfer"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Movimentação</b></h4>
                                   <p class="text-muted">Movimentação de produtos</p>
                               </div>
                           </div>
                       </a>
                   </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('INVT','119')">
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per119'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                 <i class="ti-agenda"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Inventário </b></h4>
                                   <p class="text-muted">Atualizar Saldo Inicial<br><br></p>
                               </div>
                           </div>
                       </a>
                   </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('ARQBL','120')">
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per120'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                 <i class="ti-write"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Arquivo de Balança</b></h4>
                                   <p class="text-muted">TXT p/ carga na balança.<br><br></p>
                               </div>
                           </div>
                       </a>
                   </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('CVABC','121')">
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per121'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                 <i class="ti-vector"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Curva ABC</b></h4>
                                   <p class="text-muted">Consulta Qtde e Valor.<br><br></p>
                               </div>
                           </div>
                       </a>
                   </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('ETQT','122')">
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per122'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                 <i class="ti-ticket"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Etiqueta</b></h4>
                                   <p class="text-muted">Gera etiqueta de produtos para impressoras.</p>
                               </div>
                           </div>
                       </a>
                   </div>
                   <div class="col-md-6 col-lg-3">
                       <a href="javascript:void(0)" onclick="_geral('RLTS','123')">
                           <div class="widget-bg-color-icon card-box <?php if($_SESSION['per123'] == ""){ echo 'disabled'; } ?>">
                               <div class="bg-icon pull-left">                         
                                 <i class="md-my-library-books"></i>                   
                               </div>
                               <div class="text-left">
                                   <h4 class="text-dark"><b>Relatórios</b></h4>
                                   <p class="text-muted">Consulte, gere e imprima relatórios de estoque.</p>
                               </div>
                           </div>
                       </a>
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
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
</section>

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

 
</script>
</body>
<html>				