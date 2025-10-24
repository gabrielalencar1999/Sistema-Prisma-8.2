<?php
  use Database\MySQL;

  $pdo = MySQL::acessabd();

?><!DOCTYPE html>
<html>
<?php require_once('header.php') ;
date_default_timezone_set('America/Sao_Paulo');


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
  <style>
    .borda-table{
        border: 1px solid #bbb;
    }
    .font12{
        font-size:12px;
    }
    .blue{
        color:#0a9add;
    }
    .red{
        color:#F05050;
    }
  </style>
    <div class="wrapper">
            <div class="container">


                <!-- Page-Title -->
                <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">Resumo Caixa</h4>
                            <p class="text-muted page-title-alt">Extrato Fechamento</p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">
                            
                                <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                                <!-- <button  class="btn btn-inverse waves-effect waves-light"  aria-expanded="false" id="_relextrato"><span class="btn-label btn-label">  <i class="fa fa fa-print"></i></span>Imprimir</button> -->

                        </div>
                        </div>
             
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                        <?php
                                $_parametros = array();
                                require_once('../../api/view/financeiro/finanResumoCx00001_list.php'); ?>
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
                                        <label for="field-1" class="control-label">Visualização</label>
                                </div>   
                                <div class="col-md-7">
                                    <div class="form-group">                                                    
                                        <Select  class="form-control" id="view"  name="view" > 
                                        <option value="">Total</option>    
                                        <option value="0">Por Caixa</option>                                                  
                                        </Select>
                                    </div>
                                </div>                                              
                            </div>
                            <input type="hidden" id="_keycaixa" name="_keycaixa"  value="">
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

                        
        </div><!-- /.modal -->
        <div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="_printviewer">
                        Gerando impressão
                    </div>
                </div>
            </div>
        </div>

        
        <form  id="form1" name="form1" method="post" action="">
        <input type="hidden" id="_keyform" name="_keyform"  value="">
        <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            
                          
        </form>
      

       

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
<script src="assets/js/printThis.js"></script>
  <!--FooTable Example
  <script src="assets/pages/jquery.footable.js"></script>
  
<script src="assets/plugins/footable/js/footable.all.min.js"></script>
-->
  <!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>


        <script type="text/javascript">
            $(document).ready(function () {                
                  
                    $(_00003).click(function(){
                      
                                var $_keyid =   "_Fl00013";    
												
								var dados = $("#form2 :input").serializeArray();
								dados = JSON.stringify(dados);		
                             
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0}, function(result){					
                             		
                                    $("#resultado").html(result);                                  
                                    $('#modalfiltro').modal('hide');
                                   
                            
                                $('#datatable-responsive').DataTable();


                                });

                    });



                    

                                 
                                 
             });
            </script> 
            <script>   

                    function aguardeListagem(id) {
                            $(id).html('' +
                                '<div class="bg-icon pull-request">' +
                                    '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                                    '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
                                '</div>');
                        }
         
      function _idprint(_idcx,tipoResumo) {      
        $('#_keycaixa').val(_idcx);     
        
        var $_keyid = "_Fl00013";
       
        var dados = $("#form2 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1, tipoResumo:tipoResumo},
            function(result){
               
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();
             // $('#custom-modal-venda').modal('hide');   
                    
            });
            
           // $('#custom-modal-imprime').modal('hide');   
    }
         
         

               $('#datatable-responsive').DataTable();
           
</script>    



    </body>
</html>