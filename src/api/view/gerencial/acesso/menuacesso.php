<?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body>

<?php 
require_once('navigatorbar.php');
use Database\MySQL;
$pdo = MySQL::acessabd();

?>
     
        <section class="bg-img-2" id="home" >
        <div class="container "  >

        <div class="wrapper">

       
        <form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
           
                <!-- Page-Title -->
            <div >
                <div class="row" >
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">                           
                        </div>
                        <h4 class="page-title"><span style="color:#fff">Olá, <?=$_SESSION['NOME'];?>!</span></h4>
                        <p class="text-muted page-title-alt"><span style="color:#fff">Você está na área Gerencial!</span></p>
                    </div>
                </div>
              
               <div class="card-box" >
                <div class="row button-list"  >
                    <div class="col-md-6 col-lg-3"> 
                        <a href="javascript:void(0)" id="_G000001">
                        <div class="widget-bg-color-icon card-box fadeInDown animated">
                        <div class="bg-icon pull-left">                          
                           <img src="../app/assets/images/small/img1.jpg" class="thumb-md">                     
                        </div>
                            <div class="text-left">
                            <h3 class="text-dark"><b > Treinamentos</b></h3>
                                <p class="text-muted">Manuais e Tutoriais sobre o sistema</b></p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                    <a href="javascript:void(0)" id="_G000002">
                        <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon pull-left">                          
                           <img src="../app/assets/images/small/img4.jpg" class="thumb-md">                    
                        </div>
                            <div class="text-left">
                                <h3 class="text-dark"><b>Tarifas</b></h3>
                                <p class="text-muted">Tarifas do sistema</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <a href="javascript:void(0)" id="_G000003">
                            <div class="widget-bg-color-icon card-box">
                            <div class="bg-icon pull-left">                          
                            <img src="../app/assets/images/small/img3.jpg" class="thumb-md">                    
                            </div>
                                <div class="text-left">
                                    <h3 class="text-dark"><b>Depósitos</b></h3>
                                    <p class="text-muted">saldos,depósitos e comprovantes</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>

                    
                    <div class="col-md-6 col-lg-3">

                    </div>
              
                </div>

            </div>
    
        </div>
        <input type="hidden" id="_keyform" name="_keyform"  value="">
        </form>
  
         
    </div>
		<div class="container m-t-40" style="position:absolute; bottom:85px; color:#FFF;">
            <p class="text-center"><b><?php echo $_SESSION['BASEG']."|".$_SESSION['EMPRESAG']."|".$_SESSION['NOME']?></b></p>
        </div>
    </section>



        <!-- jQuery  -->
        <script src="../app/assets/js/jquery.min.js"></script>
        <script src="../app/assets/js/bootstrap.min.js"></script>
        <script src="../app/assets/js/detect.js"></script>
        <script src="../app/assets/js/fastclick.js"></script>
		<script src="../app/assets/js/routesGerencial.js"></script>
        <script src="../app/assets/js/jquery.slimscroll.js"></script>
        <script src="../app/assets/js/jquery.blockUI.js"></script>
        <script src="../app/assets/js/waves.js"></script>
        <script src="../app/assets/js/wow.min.js"></script>
        <script src="../app/assets/js/jquery.nicescroll.js"></script>
        <script src="../app/assets/js/jquery.scrollTo.min.js"></script>
        <script src="../app/assets/plugins/peity/jquery.peity.min.js"></script>

        <!-- jQuery  -->
        <script src="../app/assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="../app/assets/plugins/counterup/jquery.counterup.min.js"></script>
        <script src="../app/assets/plugins/raphael/raphael-min.js"></script>
        <script src="../app/assets/plugins/jquery-knob/jquery.knob.js"></script>
        <script src="../app/assets/js/jquery.core.js"></script>
        <script src="../app/assets/js/jquery.app.js"></script>

        <!-- Modal-Effect -->
        <script src="../app/assets/plugins/custombox/js/custombox.min.js"></script>
        <script src="../app/assets/plugins/custombox/js/legacy.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                
                $(_G000001).click(function(){       
                    var $_keyid =   "_G001"; 
                    $('#_keyform').val($_keyid);     
                    $("#form1").submit();                 
                });
                $(_G000003).click(function(){       
                    var $_keyid =   "_G1000"; 
                    $('#_keyform').val($_keyid);     
                    $("#form1").submit();                 
                });

            });
        </script>
    </body>
</html>