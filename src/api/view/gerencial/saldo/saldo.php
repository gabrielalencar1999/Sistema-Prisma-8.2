<?php
    require_once('validarlogin.php');
    $data1 = date('Y-m-d');
    $data2 = date('Y-m-d');


?>

<!DOCTYPE html>
<html>
   <?php require_once('header.php'); ?>


    <body>

    <?php require_once('navigatorbar.php');  ?>
     
    

        <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-6">
                      

                        <h4 class="page-title">Depósito</h4>
                        <ol class="breadcrumb">
                            <li><a href="javascript:void(0)" id="_back00000">Menu</a></li>                         
                            <li class="active">Depósito</li>
                        </ol>
                    </div>

					<div class="col-sm-6" align="right">
					 <button class="btn btn-primary waves-effect waves-light" aria-expanded="true" id="_filtros"><span class="btn-label btn-label">  <i class="fa fa-filter"></i></span> Filtros</button>
                    </div>
                    
                </div>
		
                <div class="row"  style="background-color:#FFF; padding:30px;" id="result-div">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Extrato -->
        <div id="modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-overflow">
                <div class="modal-content">
                    <div id="extrato-modal">
                        <div class="modal-header">             
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h5>Filtros</h5>
                        
                        <div class="modal-body">
                            <form  id="form-filtro" name="form-filtro" method="post" action="">
                                <div class="col-sm-12">
                                    <label>Data Inicio</label>
                                    <input type="date" class="form-control input-sm" id="data1" name="data1" value="<?=$data1;?>">
                                </div>
                                <div class="col-sm-12">
                                    <label>Data Fim</label>
                                    <input type="date" class="form-control input-sm" id="data2" name="data2" value="<?=$data1;?>">
                                </div>
                                <div class="col-sm-12">
                                    <label>ID EMPRESA</label>
                                    <input type="text" class="form-control input-sm" id="idEmpresa" name="idEmpresa">
                                </div>
                                <div class="col-sm-12">
                                    <label>Situacao</label>
                                    <select class="form-control input-sm" id="situacao" name="situacao">
                                        <option value="">todos</option>
                                        <option value="0">Pendente</option>
                                        <option value="1">Liberado</option>
                                        <option value="2">Negado</option>
                                    </select>
                                </div>
                                <div class="col-sm-12" style="margin-top:15px;">
                                    <button type="submit" class="btn btn-default btn-sm">PESQUISAR</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <form  id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform"  value="">
            <input type="hidden" id="idA" name="idA"  value="">
            <input type="hidden" id="acao" name="acao"  value="">
        </form>
        <!-- end wrapper -->



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
            window.onload = function () {
                _lista();
            }

            function _lista() {
                var $_keyid = "_G1001";
                $.post("page_return.php", {_keyform:$_keyid, acao: 1},
                    function(result){
                        $("#result-div").html(result);
                });
            }
            
            $(_filtros).click(function(){
                $('#modal-filtro').modal('show');
            });


            $("#form-filtro").submit(function (e) {
                e.preventDefault();
                var $_keyid =  "_G1001"; 
                var dados = $("#form-filtro :input").serializeArray();
                 dados = JSON.stringify(dados);

                $.post("page_return.php", {_keyform:$_keyid, acao:"1" , dados:dados}, function(result){	
                    if(result == 1){ }else{
                        $("#result-div").html(result);
                    }
                });
                $('#modal-filtro').modal('hide');		
            });

            function alteraSit(valor,idEmpresa){
                var $_keyid =  "_G1001"; 
                $.post("page_return.php", {_keyform:$_keyid, acao:"2", status:valor , idEmpresa:idEmpresa}, function(result){	
                    if(result == 1){ }else{
                        alert(result);
                    }
                });	
            }
		</script>
    </body>
</html>