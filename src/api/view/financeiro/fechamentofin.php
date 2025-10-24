<?php
  use Database\MySQL;

  $pdo = MySQL::acessabd();

?><!DOCTYPE html>
<html>
<?php require_once('header.php') ;
?>
     


    <body >

    <?php
  
    
    require_once('navigatorbar.php');

          if($data_ini == ""){
                $data_ini = date('Y-m-d');
          }
        
          if($data_fim == ""){
            $data_fim = date('Y-m-d');
        }  
    ?>

    <div class="wrapper">
            <div class="container">


                <!-- Page-Title -->
                <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">Fechamento Financeiro</h4>
                            <p class="text-muted page-title-alt">Vendas/Atendimentos </p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">
                            
                           
                                <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                              
                        </div>
                        </div>
             
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">
                        <?php
                                $_parametros = array();
                                require_once('../../api/view/financeiro/fechamentofinan_list.php'); ?>
                        </div>
                    </div>
                </div>

            </div> <!-- end container -->
        </div>
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
                                                     <label for="field-1" class="control-label">Período de </label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="date" class="form-control" name="_dataIni"  id="_dataIni" value="<?=$data_ini;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                 
                                                    <label for="field-1" class="control-label">Até </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                      
                                                        <input type="date" class="form-control"  name="_dataFim"  id="_dataFim" value="<?=$data_fim;?>">                                                   
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Nº Controle</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_pedido"  name="_pedido">
                                                    </div>
                                                </div>                                              
                                            </div>                                          
                                           
                                           
                                        </div>
                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form>
    <form id="form4" name="form4"  method="post" action="javascript:void(0)">
                 <div id="custom-width-ok" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: none;">
                       <div class="modal-dialog ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Confirmação</h4>
                                        </div>
                                    <div  id="_result">
                                      
                                    </div>                                  
                              </div><!-- /.modal-content -->
                         </div><!-- /.modal-dialog -->
                     </div><!-- /.modal -->
                
    </form>  
         <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value=""> 
                            <input type="hidden" id="id_pedido" name="id_pedido"  value=""> 
                            <input type="hidden" id="id_caixa" name="id_caixa"  value="">  
                                                
          </form>
      

       

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
<script src="assets/js/routes.js"></script>

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

<!--datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
      <!--   <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>-->
     <!--    <script src="assets/plugins/datatables/jszip.min.js"></script>-->
    <!--     <script src="assets/plugins/datatables/pdfmake.min.js"></script>-->
     <!--    <script src="assets/plugins/datatables/vfs_fonts.js"></script>-->
     <!--    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>-->
       <!--  <script src="assets/plugins/datatables/buttons.print.min.js"></script>-->
        
       <!-- <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>-->
       <!--  <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>-->
       <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
       <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
       <!--   <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>-->
       <!--   <script src="assets/plugins/datatables/dataTables.colVis.js"></script>-->
       <!--   <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>-->

    

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

  <!--FooTable Example
  <script src="assets/pages/jquery.footable.js"></script>
  
<script src="assets/plugins/footable/js/footable.all.min.js"></script>
-->
  <!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>


        <script type="text/javascript">
            $(document).ready(function () {

                  
            $(_00003).click(function(){
                      
                                var $_keyid =   "_Fl00019";    
												
								var dados = $("#form2 :input").serializeArray();
								dados = JSON.stringify(dados);		
                             
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:3}, function(result){									

                                if(result == 1){ 

                                }else{			
                                   					
                                    $("#resultado").html(result);                                  
                                    $('#modalfiltro').modal('hide');
                                   
                                }
                                $('#datatable-responsive').DataTable();


                                });

                    });


                                 
                                 
            });
            </script> 
            <script>   

              function  _000007(_idped,_idcaixa){
                // alert("cadastro  ");           
                             $('#id_pedido').val(_idped);
                             $('#id_caixa').val(_idcaixa);        
                            var $_keyid =   "_Fl00019";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);          
                        
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:1}, function(result){									
                  
                                $("#_result").html(result);    
                                                 
                                $('#custom-width-ok').modal('show');                        

                            });  
                                                   

                   };

                function  _000008(){
                            
                               
                            var $_keyid =   "_Fl00019";    
                            var dados = $("#form4 :input").serializeArray();
                            dados = JSON.stringify(dados);          
                        
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:2}, function(result){									                 
                            $("#_result").html(result);   

                            });  
                                                   

                   };

                   function  _000010($_idref){
                       // $('#custom-width-modal').modal('hide');                     
                    //$('#custom2-width-modal').modal('show');

                  
                    var $_keyid =   "_Vc00010";    
                    
                    $('#_chaveid').val($_idref);
                    $('#_keyform').val($_keyid);
                    $("#form1").submit();                 
                                                

                   };
         
               $('#datatable-responsive').DataTable();
        
      


          
</script>    



    </body>
</html>