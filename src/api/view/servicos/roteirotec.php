<?php
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();




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
        <div class="container">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">Roteiro </h4>
                    <p class="text-muted page-title-alt">Roteiro Atendimento</p>
                </div>
                <form name="form2" id="form2" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div class="btn-group pull-right m-t-5">
                        <div class="m-b-5">
                        <input type="date" style="display:none ;"  name="_dataIni"  id="_dataIni" value="<?=$data_ini;?>" onchange="_datalist()">
                            <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </form>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                            <?php
                          //  $_parametros = array();
                           require_once('../../api/view/servicos/roteirolistatec.php'); ?>
                 
                        </div>
                    </div>
                </div>

            </div> <!-- end container -->
        </div>


        <!-- acompanhamento -->

        <div id="custom-modal-acompanhamento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Acompanhamento</h4>
                    </div>
                    <div class="modal-body">
                        <form name="form3" id="form3" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="roteiro" name="roteiro" value="1">
                        <input type="hidden" id="chamada" name="chamada" value="">
                            <div id="result-acompanhamento" class="result">
                                
                            </div>
                        </form>
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

                $(_00003).click(function() {

                    var $_keyid = "S00008";

                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);

                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados
                    }, function(result) {

                        $("#resultado").html(result);
                        $('#modalfiltro').modal('hide');

                        $('#datatable-responsive').DataTable();

                    });

                });

            });

            function _datalist() {
                var $_keyid = "S00019";

                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados
                }, function(result) {
                  

                    $("#resultado").html(result);                   

                    $('#datatable-responsive').DataTable();

                });

            }


            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

            function _buscaAcompanhamento(_ref) {
                $('#chamada').val(_ref);

                var $_keyid = "S00010";

                var dados = $("#form3 :input").serializeArray();
                dados = JSON.stringify(dados);

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 10 
                }, function(result) {

                    $("#result-acompanhamento").html(result);                
                    

                });

            }

            function  _acompanhamentoincluir(){       
                var $_keyid =   "S00010";    
                var dados = $("#form3 :input").serializeArray();
                dados = JSON.stringify(dados);    
               
                _carregando('#result-acopanhamento');
                
                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 11}, function(result){
                  
                    $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 10 
                }, function(result) {

                    $("#result-acompanhamento").html(result);                
                    

                });                                                                                  
                });             
        }


            function _print() {

                var $_keyid = "S00013";
                var dados = $("#form2 :input").serializeArray();

                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados
                }, function(result) {
                    $('#_printviewer').html(result);
                    $('#_printviewer').printThis();
                });

            }


            function _000010($_idref) {
                _carregando('#result-os');
              
                $('#custom-modal-os').modal('show');

                var $_keyid = "S00001";

                $('#_chaveid').val($_idref);
                $('#_keyform').val($_keyid);
               
                $("#form1").submit();  


            };
            
            function _carregando (_idmodal){
                    $(_idmodal).html('' +
                            '<div class="bg-icon pull-request">' +
                            '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                            '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                            '</div>');

                }
            $('#datatable-responsive').DataTable();
        </script>



</body>

</html>