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
     <!-- Circliful css -->
     <link href="assets/plugins/jquery-circliful/css/jquery.circliful.css" rel="stylesheet" type="text/css" />


<body>


    <?php

    require_once('navigatorbar.php');
    if ($data_ini == "") {
        $data_ini = date('Y-m-d');
    }

    if ($data_fim == "") {
        $data_fim = date('Y-m-d');
    }

   /* if($dataini == "" ) {
        $date = date("Ymd");
       $nextdate = addDayIntoDate($date,1);    // Adiciona 15 dias
            $ano = substr ( $nextdate, 0, 4 );
            $mes = substr ( $nextdate, 4, 2 );
            $dia =  substr ( $nextdate, 6, 2 ); 
            $data_prevista      = $ano."-".$mes."-".$dia;
          
            $data_ini =   $data_prevista ;
            $data_fim =   $data_prevista ;
     }
     */
    ?>

    <div class="wrapper">
        <div class="container" style="width:97% ">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">NPS </h4>
                    <p class="text-muted page-title-alt">Pesquisa de Satisfação</p>
                </div>
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                        <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalresumo" onclick="_resumo()"> <i class="fa  fa-database"></i></button>
                        <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa  fa-sliders"></i></span>Filtros</button>
                     
                        <button class="btn btn-inverse waves-effect waves-light" onclick="_print()"><span class="btn-label btn-label"> <i class="fa fa-print"></i></span>Imprimir</button>
                        
                        <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">
                           <div class="alert alert-warning text-center">
                                 Selecione os filtros para lista 
                                 
                    </div>
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
                                    <label for="field-1" class="control-label">Tipo</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <?php
                                        $querySit = ("SELECT * FROM tipo_nps ");
                                        $resultSit = mysqli_query($mysqli, $querySit)  or die(mysqli_error($mysqli));
                                        $TotalRegSit = mysqli_num_rows($resultSit);
                                        ?>
                                        <select name="tiponps" id="tiponps"  class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                            while ($resultado = mysqli_fetch_array($resultSit)) {
                                                $codigo = $resultado["tipo_npsid"];
                                                $descricao = $resultado["tipo_npsdesc"];
                                            ?>
                                                <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Situação</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <?php
                                        $querySitMob = ("SELECT * FROM tratativa ");
                                        $resultSitMob = mysqli_query($mysqli, $querySitMob)  or die(mysqli_error($mysqli));
                                        $TotalRegSitMob = mysqli_num_rows($resultSitMob);
                                        ?>
                                        <select name="tratativa" id="tratativa"  class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                            while ($resultado = mysqli_fetch_array($resultSitMob)) {
                                                $codigoSit = $resultado["trat_id"];
                                                $descricaoSit = $resultado["trat_descricao"];
                                            ?>
                                                <option value="<?php echo "$codigoSit"; ?>"> <?php echo "$descricaoSit"; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Garantia</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <?php
                                        $querySit = ("SELECT * FROM situacao_garantia ");
                                        $resultSit = mysqli_query($mysqli, $querySit)  or die(mysqli_error($mysqli));
                                        $TotalRegSit = mysqli_num_rows($resultSit);
                                        ?>
                                        <select name="tipogar" id="tipogar"  class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                            while ($resultado = mysqli_fetch_array($resultSit)) {
                                                $codigo = $resultado["g_id"];
                                                $descricao = $resultado["g_descricao"];
                                            ?>
                                                <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
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
            </div><!-- /.modal -->
        </form>
        
       

        <form id="form9" name="form9" action="javascript:void(0)">
            <div id="modalresumo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Resumo</h4>
                        </div>
                        <div class="modal-body" id="retresumo">
                        </div>
                     
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                          
                        </div>
                    </div>
                </div>
                </div>
            </div><!-- /.modal -->
        </form>

        <form id="form19" name="form19" action="javascript:void(0)">
            <div id="custom-modal-atend" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Ação/Detalhamento</h4>
                        </div>
                            <div class="modal-body" id="retdet">
                       
                             </div>    
                     </div>
                        
                    </div>
            </div>
        </form>

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
            <input type="hidden" id="_idnps" name="_idnps" value="">
            <input type="hidden" id="_idacaosel" name="_idacaosel" value="">
            
    
            
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

          <!-- Notification js -->
  <script src="assets/plugins/notifyjs/js/notify.js"></script>
  <script src="assets/plugins/notifications/notify-metro.js"></script>  

        <!--FooTable Example
  <script src="assets/pages/jquery.footable.js"></script>
  
<script src="assets/plugins/footable/js/footable.all.min.js"></script>
-->
     <!-- EASY PIE CHART JS -->
     <script src="assets/plugins/jquery.easy-pie-chart/dist/easypiechart.min.js"></script>
        <script src="assets/plugins/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="assets/pages/easy-pie-chart.init.js"></script>

        <script src="assets/plugins/jquery-knob/jquery.knob.js"></script>


        <script src="assets/plugins/jquery-circliful/js/jquery.circliful.min.js"></script>
      
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

         
            function _lista() {

                var $_keyid = "NPS_0001";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                }, function(result) {
                    $('#resultado').html(result);
                    
                    $('#modalfiltro').modal('hide');
                });

                }

            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }


            function _det(_idacao) {
                $("#_idnps").val(_idacao);
                $("#_idacaosel").val("");
                var $_keyid = "NPS_0001";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 3
                }, function(result) {
                    $('#retdet').html(result);
                    
                   
                });

                }
            
                function _dret() {
                    var l = "L"+$("#_idnps").val();
                var $_keyid = "NPS_0001";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                if($("#_idacaosel").val("") == "") {
                    $.Notification.notify('danger', 'right Top','AVISO!', 'Pontuação não selecionada');
                }else{
               
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 5
                }, function(result) {
                    if(result == 2) {
                        $.Notification.notify('warning', 'right Top','Atualizado!', 'Sem Resposta');

                    }else{
                        $.Notification.notify('success', 'right Top','Sucesso!', "Atualizado Nota com sucesso !!!");

                    }
                  
                    $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,
                            acao: 555
                                }, function(result) {
                                 
                                    $('#'+l).html(result);
                                });
                                
                     
                   $('#custom-modal-atend').modal('hide');
                });
            }

                }
                

                  function _drets() {
                    var l = "L"+$("#_idnps").val();
                
                var $_keyid = "NPS_0001";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 55
                }, function(result) {
                  
                        $.Notification.notify('warning', 'right Top','Atualizado!', 'Sem Resposta');

                     $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,
                            acao: 555
                                }, function(result) {
                                 
                                    $('#'+l).html(result);
                                });
                                
                        $('#custom-modal-atend').modal('hide');
                        });

                }

            
                
                function _resumo() {                
                    var $_keyid = "NPS_0001";
                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);                
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 6
                    }, function(result) {
                        $('#retresumo').html(result);    
                        $(".knob").knob();
                $('.circliful-chart').circliful();                
                    });

                }


            function _print() {

                var $_keyid = "NPS_0001";
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

            function _gerarcsv(_acao) {

                var $_keyid = "NPS_0001";
                var dados = $("#form2 :input").serializeArray();
                _carregando('#result-arquivo');
          
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,  acao: _acao
                }, function(result) {
                    $('#result-arquivo').html(result);
                 
                
                });

                }

                
                function _enviarwhats(_idacao) {

               
                    $("#_idnps").val(_idacao);

                    var $_keyid = "NPS_0001";
                    var dados = $("#form1 :input").serializeArray();
                    _carregandoenvio('#'+_idacao);

                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,  acao: 2
                    }, function(result) {
                        $('#'+_idacao).html(result);
                    

                    });

                    }

                    function _selnps(_idacao) {
                        
               
                        $("#_idacaosel").val(_idacao);

                        var $_keyid = "NPS_0001";
                        var dados = $("#form1 :input").serializeArray();
                        _carregandoenvio('#retselnps');

                        dados = JSON.stringify(dados);
                        $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,  acao: 4
                        }, function(result) {
                          
                            $('#retselnps').html(result);
                        });

                        }

                    


         
            function _carregandoenvio (_idmodal){
                    $(_idmodal).html(                        
                            '<button type="button" class="btn btn-white btn-sm waves-effect waves-light"  ><i class="fa fa-spin fa-spinner"></i></i> Enviando...</button>   ');

                }

                function _carregandoA(_idmodal) {

$(_idmodal).html('' +
    '<div class="bg-icon pull-request" >' +
    '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
    '<h4 class="text-center">Aguarde, atualizando dados...</h4>' +
    '</div>');

}

        </script>



</body>

</html>