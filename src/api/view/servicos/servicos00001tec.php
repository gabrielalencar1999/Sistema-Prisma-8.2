<?php
include("../../api/config/iconexao.php");   

  use Database\MySQL; 
  $pdo = MySQL::acessabd();


  if(count($_parametros) == 0) {
    $_parametros = array(
        '_bd' =>$_SESSION['BASE']    
    );
}else{
    $_bd = array(
        '_bd' =>$_SESSION['BASE']    
    );
  
    $_parametros =  array_merge($_parametros, $_bd);
  
};

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
                            <h4 class="page-title m-t-15">OS</h4>
                            <p class="text-muted page-title-alt">Consulta Ordem Serviço</p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">                               
                                <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                        </div>
                        </div>
             
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                        <?php
                                $_parametros = array();
                              require_once('../../api/view/servicos/servicos00001_listTec.php'); ?>
                        </div>
                    </div>
                </div>

            </div> <!-- end container -->
        </div>
        




    
                       <form id="form2" name="form2" action="javascript:void(0)">                      
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
                                                     <label for="field-1" class="control-label">Nº OS</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_os"  name="_os">
                                                    </div>
                                                </div>                                              
                                            </div>
                                         
                                                        <input type="hidden" name="tecnico_e" id="tecnico_e" value="<?=$_SESSION["tecnico"];?>" >
                                                            
                                         
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Situação</label>
                                                </div>   
                                                <div class="col-md-7">
                                                    <div class="form-group">                                                       
                                                    <?php
                                                        $querySit = ("SELECT * FROM situacaoos_elx order by DESCRICAO");
                                                        $resultSit = mysqli_query($mysqli,$querySit)  or die(mysqli_error($mysqli));
                                                        $TotalRegSit = mysqli_num_rows($resultSit);
                                                        ?>
                                                        <select name="situacao" id="situacao"  onchange="sit(this.value)" class="form-control input-sm">                                                            
                                                            <option value="">Todos</option>         
                                                            <?php
                                                                while($resultado = mysqli_fetch_array($resultSit))
                                                                {
                                                                            $codigoSit = $resultado["COD_SITUACAO_OS"];
                                                                            $descricaoSit = $resultado["DESCRICAO"];
                                                                    ?>
                                                                        <option value="<?php echo "$codigoSit"; ?>" <?php if ($codigoSit ==  $situacaoA) { ?>selected="selected" <?php } ?>> <?php echo "$descricaoSit"; ?></option>
                                                                    <?php                                                                       
                                                                }
                                                            ?>
                                                        </select>
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


                            <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            <input type="hidden" id="_idref" name="_idref"  value="">
                            
                            
                          
                             </form>

        <div id="custom-modal-atendimento" name="custom-modal-atendimento" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog modal-lg">
                            
                            <form name="form4" id="form4" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data" >
                          x
                            </form>
                        </div>
                    </div>
      

       

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




        <script type="text/javascript">
            $(document).ready(function () {
                $(formOS).submit(function(){ //pesquisa os
                var $_keyid =   "S00001";                     
                $('#_keyform').val($_keyid);   
                if($('#oksalva').val() == 0 ) { 
                    $('#custom-modal-fechar').modal('show');
                }else{
                    var dados = $("#formOS :input").serializeArray();
                    dados = JSON.stringify(dados);		
                                
                    $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                    $('#_chaveid').val($('#numOS').val());   
                    $("#form1").submit();  
                    
                        });
                }  
                });
                  
                    $(_00003).click(function(){                      
                                var $_keyid =   "S00016";    												
								var dados = $("#form2 :input").serializeArray();
								dados = JSON.stringify(dados);		
                             
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									
                             	                                 					
                                    $("#resultado").html(result);                                  
                                    $('#modalfiltro').modal('hide');                                                                
                                    $('#datatable-responsive').DataTable();


                                });

                    });


         
            });

            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }
           
            function  _000010($_idref){

                    var $_keyid =   "S00001";    
                    
                    $('#_chaveid').val($_idref);
                    $('#_keyform').val($_keyid);
                    $("#form1").submit();                 
                                                

                   };

            function  _000007($_idref){
            
                $('#_idref').val($_idref);
                $('#_dataref').val($('#_dataIni').val());
                
                             var $_keyid =   "_ATa00017";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);        
                            _carregando('#form4');
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:0}, function(result){								                         		                                                    
                             
                                $("#form4").html(result);                                                                                  
                            });                           

            };

            

            function _carregando (_idmodal){
                    $(_idmodal).html('' +
                    '<div class="bg-icon pull-request" >' +
                    '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde."></div>');

                }  

               $('#datatable-responsive').DataTable();
            


          
</script>    



    </body>
</html>