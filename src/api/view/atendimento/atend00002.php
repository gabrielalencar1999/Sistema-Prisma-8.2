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

                        <h4 class="page-title">Atendimentos</h4>
                        <ol class="breadcrumb">
                            <li>
                                <a href="#">Menu</a>
                            </li>
                          
                            <li class="active">
                                Atendimento
                            </li>
                        </ol>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-box">
                            <a href="#" class="pull-right btn btn-default btn-sm waves-effect waves-light">Adicionar Novo </a>
                            <h4 class="text-dark header-title m-t-0">PRÓXIMOS</h4>
                            <p class="text-muted m-b-30 font-13">
                                Clientes aguardando atendimento na fila
                            </p>

                            <ul class="sortable-list taskList list-unstyled" id="upcoming">
                                <li class="task-warning" id="task1">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    <strong> Hamister(Stark) </strong>- Vacina
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-1.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Petey Cruiser</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-success" id="task2">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                   <strong>Dog(Thor)</strong> - Banho e Tosa
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-2.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Anna Sthesia</span></a> </p>
                                    </div>
                                </li>
                                <li id="task3">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                   xxx xxx 
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-3.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Gail Forcewind</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-info" id="task4">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-4.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Maya Didas</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-danger" id="task5">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-5.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Rick O'Shea</span></a> </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">
                            <h4 class="text-dark header-title m-t-0">EM ANDAMENTO</h4>
                            <p class="text-muted m-b-30 font-13">
                            Clientes que estão em  atendimentos 
                            </p>

                            <ul class="sortable-list taskList list-unstyled" id="inprogress">
                                <li id="task9">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    If you are going to use a passage of Lorem Ipsum..
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-3.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Gail Forcewind</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-info" id="task10">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-4.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Maya Didas</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-danger" id="task11">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-5.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Rick O'Shea</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-warning" id="task7">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-1.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Petey Cruiser</span></a> </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">
                            <h4 class="text-dark header-title m-t-0">FINALIZADO</h4>
                            <p class="text-muted m-b-30 font-13">
                                Atendimentos Concluídos
                            </p>

                            <ul class="sortable-list taskList list-unstyled" id="completed">
                                <li class="task-warning" id="task14">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-1.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Petey Cruiser</span></a> </p>
                                    </div>
                                </li>
                                <li class="task-success" id="task15">
                                    <div class="checkbox checkbox-custom checkbox-single pull-right">
                                        <input type="checkbox" aria-label="Single checkbox Two">
                                        <label></label>
                                    </div>
                                    Many desktop publishing packages and web page editors now use Lorem.
                                    <div class="m-t-20">
                                        <p class="pull-right m-b-0"><i class="fa fa-clock-o"></i> <span title="15/06/2016 12:56">15/06/2016</span></p>
                                        <p class="m-b-0"><a href="" class="text-muted"><img src="assets/images/users/avatar-2.jpg" alt="task-user" class="thumb-sm img-circle m-r-10"> <span class="font-bold">Anna Sthesia</span></a> </p>
                                    </div>
                                </li>
                             
                            </ul>
                        </div>
                    </div>

                </div>

               

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