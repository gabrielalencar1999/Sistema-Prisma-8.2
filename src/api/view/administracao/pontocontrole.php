<?php
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();

function addDayIntoDate($date,$days) {
    $thisyear = substr ( $date, 0, 4 );
    $thismonth = substr ( $date, 4, 2 );
    $thisday =  substr ( $date, 6, 2 );
    $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );
    return strftime("%Y%m%d", $nextdate);
}


?>
<!DOCTYPE html>
<html>
<?php require_once('header.php');


?>



<body>
    <?php

    require_once('navigatorbar.php');
    if ($data_ini == "") {
        $data_ini = date('Y-m-d');
    }

    if ($data_fim == "") {
        $data_fim = date('Y-m-d');
    }

  
    ?>

    <div class="wrapper">
        <div class="container" style="width:97% ">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">Registro Ponto </h4>
                    <p class="text-muted page-title-alt">Controle Registro Ponto</p>
                </div>
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">

                        <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa  fa-sliders"></i></span>Filtros</button>
                     
                      
                        
                        <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">
                 
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
                            <input type="hidden" id="_opcrel" name="_opcrel" value="1">
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
                                    <label for="field-1" class="control-label">Situação</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">                                        
                                        <select name="ativo" id="ativo"  class="form-control">
                                            <option value="Sim">Ativo</option>
                                            <option value="Nao">Desativado</option>       
                                            <option value="0">Todos</option>                                  
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">  
                                    <div class="col-md-2">
                                        <label for="field-1" class="control-label">Nome</label>
                                    </div>
                                         <div class="col-md-8">
                        
                                                    <div class="form-group">                                                      
                                                        <input type="text" class="form-control"  name="_namefunc"  id="_namefunc" >                                                   
                                                    </div>
                                                </div>
                                  </div>                          
                            </div>             
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light" onclick="_lista()">Filtrar</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

        <div id="custom-modal-editar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Folha Ponto</h4>
                    </div>
                    <div class="modal-body">
                        <form name="form32" id="form32" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="_idtec" name="_idtec" value="">
                        <input type="hidden" id="_idsel" name="_idsel" value="">
                        <input type="hidden" id="_mA" name="_mA" value="">
                        <input type="hidden" id="_mB" name="_mB" value="">
                        <input type="hidden" id="_mC" name="_mC" value="">
                        <input type="hidden" id="_mD" name="_mD" value="">
                       
                            <div id="result-ponto">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- print -->
        <div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="_printviewer">
                        Gerando impressão
                    </div>
                </div>
            </div>
        </div>

 


        <form id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform" value="">
            <input type="hidden" id="_chaveid" name="_chaveid" value="">    
            
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
    
       <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
       <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
      

        <script src="assets/js/printThis.js"></script>

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
            $(document).ready(function() {
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

            
    


            function _print() {

                var $_keyid = "REGPONTO_0001";                
                var dados = $("#form32 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                     dados: dados,
                     acao: 4
                }, function(result) {
                   
                    $('#_printviewer').html(result);
                    $('#_printviewer').printThis();
                });

                

            }




            function _editar(_ref) {
                  $('#_idtec').val(_ref);
                    var $_keyid = "REGPONTO_0001";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 2
                    }, function(result) {
                        $('#result-ponto').html(result);
                    });

             }

             function _lista() {
                    var $_keyid = "REGPONTO_0001";
                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 1
                    }, function(result) {                    
                        $("#resultado").html(result);                        
                       $('#datatable-responsive').DataTable();
                    });
             }

             function _listaMes() {
                    var $_keyid = "REGPONTO_0001";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 3
                    }, function(result) {                                         
                        $("#_ret").html(result);                        
                        $('#datatable-responsive').DataTable();
                    });
             }

             function _editarPonto(_dvid) {
                     $('#_idsel').val(_dvid);
                    var $_keyid = "REGPONTO_0001";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 5
                    }, function(result) {     
                                                     ;
                        $("#"+_dvid).html(result);                                                
                    });
             }

             function _salvarPonto(_dvid) {
                     $('#_idsel').val("_linha"+_dvid);
                     var A  = '#mEntrada'+_dvid;
                     var B  = '#mSaida'+_dvid;
                     var C  = '#tEntrada'+_dvid;
                     var D  = '#tSaida'+_dvid;
                     $('#_mA').val($(A).val());
                     $('#_mB').val($(B).val());
                     $('#_mC').val($(C).val());
                     $('#_mD').val($(D).val());
                    var $_keyid = "REGPONTO_0001";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 55
                    }, function(result) {     
                                             ;
                        $("#_linha"+_dvid).html(result);                                                
                    });
             }

             

       
            
            function _carregando (_idmodal){
                    $(_idmodal).html('' +
                            '<div class="bg-icon pull-request">' +
                            '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                            '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                            '</div>');

                }

                _lista();

         
        </script>



</body>

</html>