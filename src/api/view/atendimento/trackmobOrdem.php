<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   

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
    
    
if($_POST['idtecsession'] != ''){
    $idtecsession = $_POST['idtecsession'];
}else{
    $idtecsession = base64_encode($_SESSION["tecnico"].";".$_SESSION['CODIGOCLI'].";".$_SESSION["nivel"]);
}

    ?>
       

    <div class="wrapper" style=" z-index: 2;background: url(assets/images/agsquare.png);">
            <div style="margin:5px 30px 30px 30px;">
                <!-- Page-Title -->
                <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">Ordenação de Atendimento</h4>                           
                        </div>
                        <div class="btn-group pull-right m-t-10">
                            <div class="m-b-5">   
                         
                                <input type="date"  name="_dataIni" readonly id="_dataIni" value="<?=$data_ini;?>" > <?php //onchange="_datalist() ; ?>
                                <button class="btn btn-warning waves-effect waves-light" onclick="_datalist()"> <i class="fa fa-refresh"></i></button>
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                             </div>
                        </div>
                </div>
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">
                        <?php
                              $_parametros = array();
                              require_once('../../api/view/atendimento/trackmoblistOrdem.php'); ?>
                        </div>
                    </div>
                </div>

            </div> <!-- end container -->
        </div>
   
    </div>
             

                    <!-- atendimento -->
                    
  
                     <div id="custom-modal-atendimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog modal-lg ">
                        <form name="form4" id="form4" action="javascript:void(0)" method="post" enctype="multipart/form-data" >
                          
                        </form>
                        </div>
                    </div>
                    
                    <div id="custom-modal-tec" name="custom-modal-tec" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog  ">
                            <form name="form5" id="form5" action="javascript:void(0)" method="post" enctype="multipart/form-data" >
                          x
                            </form>
                        </div>
                    </div>

                    <div id="modalSequenciaOrdem" name="modalSequenciaOrdem" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                         <div class="modal-dialog ">            
                                    <div class="modal-content text-center">        
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                <h4 class="modal-title">Informe Número Sequencia</h4>
                                            </div>
                                            <div class="modal-body" id="result-osconsulta">                                            
                                                    <div class="row">                                                        
                                                        <div class="col-sm-12">                                                                   
                                                            <input name="numeroSequencia" type="text" id="numeroSequencia" value="" class="form-control ">                                                                                                    
                                                            <input name="numeroSequencia_ant" type="hidden" id="numeroSequencia_ant" value="" class="form-control ">                                                                                                    
                                                        </div>                                                             
                                                    </div>
                                                    <div class="row">                                                        
                                                        <div class="col-sm-12" style="margin-top: 10px ;" id="altseq"> 
                                                            <button class="btn btn-default btn-block waves-effect waves-light" onclick="_ordemOSalterarSalvar()"> Alterar</button>
                                                        </div>                                                             
                                                    </div>                                            
                                            </div>
                                    </div>            
                            </div>
                    </div>

                    <div id="modalSalvarOrdem" name="modalSalvarOrdem" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                         <div class="modal-dialog modal-sm">            
                                    <div class="modal-content text-center">                                                       
                                            <div class="modal-body" id="result_ordem" >                                                                                                
                                                                                 
                                            </div>
                                    </div>            
                            </div>
                    </div>


                       <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            <input type="hidden" id="_idref" name="_idref"  value="">
                            <input type="hidden" id="_tec" name="_tec"  value="">    
                            <input type="hidden" id="_dataref" name="_dataref"  value="">   
                            <input type="hidden" id="_cor" name="_cor"  value="">  
                            <input type="hidden" id="_periodo" name="_periodo"  value=""> 
                            <input type="hidden" id="_numeroSequencia" name="_numeroSequencia"  value=""> 
                            <input type="hidden" id="_numeroSequencia_ant" name="_numeroSequencia_ant"  value=""> 
                            <input type="hidden"  name="idtecsession" id="idtecsession" value="<?=$idtecsession;?>">
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

                $(formOS).submit(function(){ //pesquisa os
                     
                     var $_keyid =   "S00001";                     
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#formOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                           $('#_chaveid').val($('#numOS').val());   
                           $("#form1").submit();  
             
                  });

                 });
                });


                     
            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
               
                $('#form1').submit();
            }
            </script> 
            <script>   

            function _ativa(_id){
                $('li.active').removeClass('active'); // to remove the current active tab
                $(_id).addClass('active'); // add active class to the clicked tab
            }

         
            function  _000007($_idref){
                // alert("cadastro  ");
                
                $('#_idref').val($_idref);
                $('#_dataref').val($('#_dataIni').val());
                
                             var $_keyid =   "_ATa00011";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);        
                            _carregando('#form4');
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:0}, function(result){								                         		                                                    
                             
                                $("#form4").html(result);                                                                                  
                            });                           

            };

            function  _000077($_idref){                            
                $('#_idref').val($_idref);
                $('#_dataref').val($('#_dataIni').val());
                
                             var $_keyid =   "_ATa00014";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);        
                            _carregando('#form5');
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:0}, function(result){                                                           
                                $("#form5").html(result);                      
                            });                           

            };

            function  _listOStec(){                    
                    $('#_idref').val($('#tecnico_e').val());
                    $('#_dataref').val($('#_dataIni').val());
                
                    _carregando('#result_detalheOS');
                    _carregando('#result_listaOS');
                                var $_keyid =   "_ATa00011";    
                                var dados = $("#form1 :input").serializeArray();
                                dados = JSON.stringify(dados);                       
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:1}, function(result){	
                                                                                                                                                                            
                                $("#form4").html(result);               
                                                                                                                                            
                                });                           

                };

          
            function _carregando (_idmodal){
                    $(_idmodal).html('' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                    '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                    '</div>');
            }  

            function _ordemOS($_chamada,$_cor,$_idperiodo,$_tec){
               
                $('#_idref').val($_chamada);
                $('#_cor').val($_cor);
                $('#_periodo').val($_idperiodo);
                $('#_tec').val($_tec);
                $('#_dataref').val($('#_dataIni').val());

                var $_divos = "_id" + $_chamada; 
                     var $_keyid = "_ATa00011";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);        
                            _carregando($_divos);
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:2}, function(result){  
                                           		                                                    ;
                              $('#'+$_divos).html(result);      
                              _datalist2();                                                                                                     
                            }); 
            }

           function _ordemOSalterar($_chamada,$_tec,$_seqant){
               
               $('#_idref').val($_chamada);
               $('#_tec').val($_tec);
               $('#_dataref').val($('#_dataIni').val());
               $('#numeroSequencia').val();
               $('#numeroSequencia_ant').val($_seqant);
           
           }

            function _ordemOSalterarSalvar(){               
                    $('#_numeroSequencia').val($('#numeroSequencia').val())         
                    $('#_numeroSequencia_ant').val($('#numeroSequencia_ant').val())                    
                    var $_keyid = "_ATa00011";    
                    var dados = $("#form1 :input").serializeArray();
                        dados = JSON.stringify(dados);        
                          
                    $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:3}, function(result){                       		                                                    
                        _datalist2();                                                                                                  ;
                        // $('#altseq').html(result);   
                        
                        $('#modalSequenciaOrdem').modal('hide');
                         $('#numeroSequencia_ant').val('');
                          }); 
           }

           function _salvarordem(){
                                     
                    var $_keyid = "_ATa00011";    
                    var dados = $("#form1 :input").serializeArray();
                        dados = JSON.stringify(dados);        
                          
                    $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:11}, function(result){   
                                //enviar wats
                                $('#result_ordem').html('<i class="fa fa-spin fa-spinner"></i> Processando aguarde...');
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:13}, function(result){   
                                  
                                 _fechar();                                
                                }); 
                          }); 
           }

           
           function _validarordem(){
            $('#_dataref').val($('#_dataIni').val());
                                     var $_keyid = "_ATa00011";    
                                     var dados = $("#form1 :input").serializeArray();
                                         dados = JSON.stringify(dados);        
                                           
                                     $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:14}, function(result){   
                                                 //enviar wats
                                                 $('#result_ordem').html(result);
                                              
                                           }); 
                            }
                 

           
           function  _datalist(){
                
                $('#_dataref').val($('#_dataIni').val());           
                
                             var $_keyid =   "_ATa00016";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);        
                            _carregando('#resultado');
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){								                         		                                                    
                                $("#resultado").html(result);                                                                                  
                            }); 

                                               

                };

                function  _datalist2(){
                
                $('#_dataref').val($('#_dataIni').val());           
                
                             var $_keyid =   "_ATa00018";    
                            var dados = $("#form1 :input").serializeArray();
                            dados = JSON.stringify(dados);        
                            _carregando('#resultado');
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){								                         		                                                    
                                $("#resultado").html(result);                                                                                  
                            }); 

                                               

                };
            
            
         
            $('#datatable-responsive').DataTable();
             
      


          
</script>    



    </body>
</html>