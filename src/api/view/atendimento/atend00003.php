<?php require_once('validarlogin.php') ?>

<!DOCTYPE html>
<html>
   <?php require_once('header.php') ?>

    <body>

    <?php require_once('navigatorbar.php') ?>
     


       

        <div class="wrapper">
            <div class="container">


                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">
                            <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Opções <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                            <ul class="dropdown-menu drop-menu-right" role="menu">
                                <li><a href="#">Filtrar por</a></li>
                                <li><a href="#">Pesquisar Pets</a></li>
                                <li><a href="#">Pesquisar Clientes</a></li>
                                <li><a href="#">Animais Internados</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Dashboard</a></li>
                            </ul>
                        </div>

                        <h4 class="page-title">Análise</h4>
                        <ol class="breadcrumb">
                            <li>
                                <a href="#">Menu</a>
                            </li>
                          
                            <li class="active">
                                Resumo Gestão / Financeiro
                            </li>
                        </ol>
                    </div>
                </div>

          
                <div class="row">
                <div class="col-lg-3 col-sm-6">
                        <div class="widget-panel widget-style-2 bg-white">

                            <i class="md md-store-mall-directory text-info"></i>
                            <div class="text-muted m-t-5">Faturamento Mês</div>
                            <h2 class="m-0 text-dark counter font-600">20.000,00</h2>
                            <h5 class="m-0 text-dark counter font-600">Anterior 15.000,00</h5>  
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-panel widget-style-2 bg-white">
                            <i class="md md-add-shopping-cart text-pink"></i>
                            <div class="text-muted m-t-5">Receitas/Despesas  </div>
                            <h3 class="m-0 text-dark counter font-600">9.000,00</h3>
                            <h4 class="m-0 text-dark counter font-600">-(2.900,00)</h4>                           
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-panel widget-style-2 bg-white">
                            <i class="md md-attach-money text-primary"></i>   
                            <div class="text-muted m-t-5">Resultado Mês</div>                        
                            <h2 class="m-0 text-dark counter font-600">6.100,00</h2>
                            <h5 class="m-0 text-dark counter font-600">Caixa: 85.000,00</h5> 
                           
                        </div>
                    </div>
                   
                  
                    
                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-panel widget-style-2 bg-white">
                            <i class="md md-account-child text-custom"></i>
                            <div class="text-muted m-t-5">Total Atendimento/Vendas</div>
                            <h2 class="m-0 text-dark counter font-600">300</h2>
                            <h5 class="m-0 text-dark counter font-600">Anterior 150</h5>  
                            
                          
                        </div>
                    </div>
                </div>

               
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            
                            <div class="row">
                                 <div class="col-lg-4">
                                        <div class="card-box">
                                            <h4 class="m-t-0 m-b-20 "><b>Ultimas Transações</b></h4>

                                            <div class="nicescroll mx-box">
                                                <ul class="list-unstyled transaction-list m-r-5">
                                                    <li>
                                                        <i class="ti-download text-success"></i>
                                                        <span class="tran-text">Advertising</span>
                                                        <span class="pull-right text-success tran-price">+$230</span>
                                                        <span class="pull-right text-muted">07/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-upload text-danger"></i>
                                                        <span class="tran-text">Support licence</span>
                                                        <span class="pull-right text-danger tran-price">-$965</span>
                                                        <span class="pull-right text-muted">07/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-download text-success"></i>
                                                        <span class="tran-text">Extended licence</span>
                                                        <span class="pull-right text-success tran-price">+$830</span>
                                                        <span class="pull-right text-muted">07/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-download text-success"></i>
                                                        <span class="tran-text">Advertising</span>
                                                        <span class="pull-right text-success tran-price">+$230</span>
                                                        <span class="pull-right text-muted">05/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-upload text-danger"></i>
                                                        <span class="tran-text">New plugins added</span>
                                                        <span class="pull-right text-danger tran-price">-$452</span>
                                                        <span class="pull-right text-muted">05/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-download text-success"></i>
                                                        <span class="tran-text">Google Inc.</span>
                                                        <span class="pull-right text-success tran-price">+$230</span>
                                                        <span class="pull-right text-muted">04/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-upload text-danger"></i>
                                                        <span class="tran-text">Facebook Ad</span>
                                                        <span class="pull-right text-danger tran-price">-$364</span>
                                                        <span class="pull-right text-muted">03/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-download text-success"></i>
                                                        <span class="tran-text">New sale</span>
                                                        <span class="pull-right text-success tran-price">+$230</span>
                                                        <span class="pull-right text-muted">03/09/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-download text-success"></i>
                                                        <span class="tran-text">Advertising</span>
                                                        <span class="pull-right text-success tran-price">+$230</span>
                                                        <span class="pull-right text-muted">29/08/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>

                                                    <li>
                                                        <i class="ti-upload text-danger"></i>
                                                        <span class="tran-text">Support licence</span>
                                                        <span class="pull-right text-danger tran-price">-$854</span>
                                                        <span class="pull-right text-muted">27/08/2015</span>
                                                        <span class="clearfix"></span>
                                                    </li>


                                                </ul>
                                            </div>
                                        </div>

                                    </div> <!-- end col -->

                 
                                 <div class="col-md-4">
                                 <div class="card-box">
                                            <h4 class="m-t-0 m-b-20 "><b>Tipo Pagamento</b></h4>
                                    <p class="font-600">Dinheiro <span class="text-primary pull-right">80%</span></p>
                                    <div class="progress m-b-30">
                                      <div class="progress-bar progress-bar-primary progress-animated wow animated" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                      </div><!-- /.progress-bar .progress-bar-danger -->
                                    </div><!-- /.progress .no-rounded -->

                                    <p class="font-600">Débito <span class="text-pink pull-right">50%</span></p>
                                    <div class="progress m-b-30">
                                      <div class="progress-bar progress-bar-pink progress-animated wow animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                                      </div><!-- /.progress-bar .progress-bar-pink -->
                                    </div><!-- /.progress .no-rounded -->

                                    <p class="font-600">Crédito <span class="text-info pull-right">70%</span></p>
                                    <div class="progress m-b-30">
                                      <div class="progress-bar progress-bar-info progress-animated wow animated" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                      </div><!-- /.progress-bar .progress-bar-info -->
                                    </div><!-- /.progress .no-rounded -->

                                    <p class="font-600">Boleto <span class="text-warning pull-right">65%</span></p>
                                    <div class="progress m-b-30">
                                      <div class="progress-bar progress-bar-warning progress-animated wow animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                      </div><!-- /.progress-bar .progress-bar-warning -->
                                    </div><!-- /.progress .no-rounded -->

                                    <p class="font-600">Pix <span class="text-success pull-right">40%</span></p>
                                    <div class="progress m-b-30">
                                      <div class="progress-bar progress-bar-success progress-animated wow animated" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                      </div><!-- /.progress-bar .progress-bar-success -->
                                    </div><!-- /.progress .no-rounded -->
                                    </div>

                                </div>
                             


                            </div>

                            <!-- end row -->

                        </div>

                    </div>



                </div>
                <!-- end row -->

            </div> <!-- end container -->
        </div>
        <form c id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform"  value="">
        </form>
        <!-- end wrapper -->



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- Jquery ui js -->
        <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>

        <!-- App core js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

  
        <script>
            $(document).ready(function () {

                  $(_menu).click(function(){                      
                                               var $_keyid =   "_Am00001"; 
                                            $('#_keyform').val($_keyid);     
                                            $("#form1").submit();                 
                                         });

                    

                                     $(_back00000).click(function(){                                
                                               var $_keyid =   "_Am00001"; 
                                            $('#_keyform').val($_keyid);     
                                            $("#form1").submit();                 
                                         });
                                    
                    
                                      $(_menuadmin).click(function(){       
                                   
                                           var $_keyid =   "_Na00001"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                                     
                                      
                                      $(_menufin).click(function(){       
                                      
                                           var $_keyid =   "_Nf00002"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                    
                                      $(_menuvend).click(function(){       
                                      
                                           var $_keyid =   "_Nv00003"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                    
                                      $(_menucli).click(function(){       
                                      
                                           var $_keyid =   "_Nc00004"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                    
                                      $(_menuconf).click(function(){       
                                      
                                           var $_keyid =   "_Nc00005"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });

                $("#upcoming, #inprogress, #completed").sortable({
                    connectWith: ".taskList",
                    placeholder: 'task-placeholder',
                    forcePlaceholderSize: true,
                    update: function (event, ui) {

                        var todo = $("#todo").sortable("toArray");
                        var inprogress = $("#inprogress").sortable("toArray");
                        var completed = $("#completed").sortable("toArray");
                    }
                }).disableSelection();

            });
        </script>


    </body>
</html>