<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");


use Database\MySQL;

$pdo = MySQL::acessabd();
date_default_timezone_set('America/Sao_Paulo');


    $idtecsession = $_POST['idtecsession'];


?>
<!DOCTYPE html>
<html>
<?php require_once('header.php');

?>


<body>
    <!-- onLoad="relogio()"-->

    <?php


    require_once('navigatorbar.php');

    if (empty($_SESSION['BASE'])) {
        echo ("Efetue Login novamente !!! Tempo expirado. ");
        exit();
    }


    if (isset($_POST['_dataref'])) {
        $_dtini = $_POST['_dataref'];
    }

    if ($_dtini == "") {
        $_dtini = date('Y-m-d');
    }

    /*   if($data_fim == ""){
            $data_fim = date('Y-m-d');
        }  
        */
    ?>
    <!-- <div class="row hidden-xs" style="position:fixed; z-index: 100; bottom:0px;background-color: #ebeff2">
                    
                        <div style="margin-left:30px ;" >
                         <ul class="list-inline status-list m-t-20" id="resultadoTotal">
                            
                         <?php
                            $_parametros = array();
                            //  require_once('../../api/view/atendimento/trackmobTotal.php'); 
                            ?>
                          </ul>
                       
                 </div>
        </div>
    -->
    <div class="wrapper" style=" z-index: 2;background: url(assets/images/agsquare.png);" id="_principal" style="display:''">
        <div style="margin:5px 20px 20px 20px;">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">Prisma Oficina</h4>
                </div>
                <div class="btn-group pull-right m-t-10">
                    <div class="m-b-5">
                        <!-- <button class="btn btn-success waves-effect waves-light"  onclick="_resumo()" data-toggle="modal" data-target="#modalresumo"><span class="btn-label btn-label"><i class="fa fa-list-alt"></i></span>Resumo</button>                   -->
                        <?php if ($_SESSION["nivel"] == 1) { //1 perfil tecnico  
                            $hide = " ";
                        } else {
                            $hide = "";
                        } ?>
                        <span id="spanRelogio"></span>
                        <button class="btn btn-warning waves-effect waves-light" onclick="_REFRESH()"> <i class="fa fa-refresh"></i></button>
                        <input type="date" style="display:<?= $hide; ?> ;" name="_dataIni" id="_dataIni" value="<?= $_dtini; ?>" onchange="_datalist()">
                        <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>           
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                            <?php
                            $_parametros = array();
                            $_LINK = "_ATa100122";
                            // require_once('../../api/view/atendimento/trackmoblist.php');
                            require_once('../../api/view/atendimento/trackmoblistOficina.php'); ?>
                        </div>
                    </div>
                </div>
        


        </div> <!-- end container -->
    </div>


    <!-- Footer -->
    <?php if ($_SESSION["nivel"] != 1) { 
        /* ?>
        <footer class="footer text-center hidden-xs" style="padding:0px">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12" style="text-align:center ;">
                        <table width="100%" id="resultadoTotal"> <?php
                                                                    $_parametros = array();
                                                                    require_once('../../api/view/atendimento/trackmobTotal.php');
                                                                    ?>
                        </table>
                    </div>

                </div>
            </div>
            </footer>
    <?php*/ 
     ?>
    <div  style=" bottom: 0;border-top: 1px solid rgba(0, 0, 0, 0.1);
    position: fixed;
    width: 100%;
    text-align: center;background: url(assets/images/agsquare.png);">
<div class="col-sm-12">
<div class="col-xs-12" style="text-align:center ;">
<div  id="resultadototal">

<table width="100%" id="resultadoTotal"> <?php
        $_parametros = array();
        require_once('../../api/view/atendimento/trackmobTotal.php');
                                                                    ?>
                        </table>
</div>
</div>
</div>
</div>
<?php
    } ?>


    <!-- atendimento -->
    <div id="_submenu" name="submenu" style="display: none;">
        <div class="col-sm-12">

            <form name="form5" id="form5" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
            
                ... carregando
            </form>
        </div>
    </div>



    <div id="custom-modal-atendimento" name="custom-modal-atendimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">

            <form name="form4" id="form4" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                x
            </form>
        </div>
    </div>

    <div id="custom-modal-tec" name="custom-modal-tec" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="test-modal">

            <form name="form55" id="form55" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                x
            </form>
        </div>
    </div>

    <!-- excluir -->
    <div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-3x  md-info-outline"></i>
                    </div>
                    <h3><span id="textexclui">Deseja realmente excluir ?</span> </h3>
                    <p>
                        <button class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <span id="textexcluibt">
                            <button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluir();">Excluir</button>
                        </span>
                    </p>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- motivos -->
    <div id="custom-modal-motivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="mySmallModalLabel">Selecione o Motivo</h4>
                    </div>
                    <div id="_motivodetalhe">

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- detalhes peca -->
    <div id="custom-modal-detpeca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="mySmallModalLabel">Detalhes Peças</h4>
                    </div>
                    <div id="_pecadetalhe">

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- fto -->
    <div id="custom-modal-foto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div id="foto_detalhe">
                        <table class="table m-0">
                            <tbody>
                                <tr>
                                    <td align="center">
                                        <img src="<?= $_img; ?>" alt="image" class="img-responsive">
                                    </td>
                                </tr>
                                <tr>
                                    <td> <button class="cancel btn   btn-default btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button> </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right ;"> <button type="button" class="confirm btn bt-sn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluir();"><i class="fa  fa-trash"></i></button> </td>
                                </tr>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>
                        <span id="textexcluibt">
                        </span>
                    </p>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="custom-modal-aparelho" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog  text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Selecionar Produto da OS</h4>
                </div>
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="formAp" id="formAp">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <input type="text" id="busca-aparelho" name="busca-aparelho" class="form-control " placeholder="Descrição, Marca, Modelo" onkeyup="_aparelhoBusca()">
                            </div>
                        </div>


                        <div class="row" id="retorno-aparelho">
                            <div class="col-sm-12 " style="height: 350px;  overflow-x: auto;" style="margin-top:20px ;">
                                <table id="datatable-fixed-col" class="table table-striped table-bordered ">
                                    <tbody id="pesquisaaparelho">

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="custom-modal-oslista" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
            <div class="modal-dialog ">
                <div class="modal-content " id="result-oslista">
                   
                </div>
            </div>
        </div>

    <form id="form1" name="form1" method="post" action="">
        <input type="hidden" id="_keyform" name="_keyform" value="">
        <input type="hidden" id="_chaveid" name="_chaveid" value="">
        <input type="hidden" id="_idref" name="_idref" value="">      
        <input type="hidden" id="_lat" name="_lat" value="">
        <input type="hidden" id="_long" name="_long" value="">
        <input type="hidden" id="_datarefid" name="_datarefid" value="">
        <input type="hidden" id="_dataref" name="_dataref" value="">
        <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">
        <input type="hidden" name="_idfotoV" id="_idfotoV" value="" />
        <input type="hidden" name="motivooutrosAT" id="motivooutrosAT" value="" />
        <input type="hidden" name="_sittrackmob" id="_sittrackmob" value="" />
        
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
    <script src="assets/js/jquery.mask.min.js"></script>
    <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
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

    <!-- Notification js -->
    <script src="assets/plugins/notifyjs/js/notify.js"></script>
    <script src="assets/plugins/notifications/notify-metro.js"></script>

    <!--FooTable Example
  <script src="assets/pages/jquery.footable.js"></script>
  
<script src="assets/plugins/footable/js/footable.all.min.js"></script>
-->
    <!-- Via Cep -->
    <script src="assets/js/jquery.viacep.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $(formOS).submit(function() { //pesquisa os

                var $_keyid = "S00001";
                $('#_keyform').val($_keyid);

                var dados = $("#formOS :input").serializeArray();
                dados = JSON.stringify(dados);

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados
                }, function(result) {
                    $('#_chaveid').val($('#numOS').val());
                    $("#form1").submit();

                });

            });
        });

        $('#_vlrtaxa').mask('#.##0,00', {
            reverse: true
        });

        $('#_vlrS').mask('#.##0,00', {
            reverse: true
        });

        $('#_vlr').mask('#.##0,00', {
            reverse: true
        });

        $("#revcnpj").mask("99.999.999/9999-99");

        function _fechar() {
            var $_keyid = "_Am00001";
            $('#_keyform').val($_keyid);

            $('#form1').submit();
        }
    </script>
    <script>
        function _ativa(_id) {
            $('li.active').removeClass('active'); // to remove the current active tab
            $(_id).addClass('active'); // add active class to the clicked tab
        }


        function _000007($_idref, $_datarefid) {
            // alert("cadastro  ");
            clearInterval(myTimer);
            clearInterval(myTimer2);
            $('#_idref').val($_idref);
            $('#_datarefid').val($_datarefid);
            $('#_dataref').val($('#_dataIni').val());

            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#form4');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 0
            }, function(result) {

                $("#form4").html(result);
            });

        };

        function _000077($_idref, $_datarefid) {
            clearInterval(myTimer);
            clearInterval(myTimer2);

            $('#_idref').val($_idref);
            $('#_datarefid').val($_datarefid);
            $('#_dataref').val($('#_dataIni').val());


            var $_keyid = "_ATa00021";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            document.getElementById("_submenu").style.display = "";
            document.getElementById("_principal").style.display = "none";
            _carregando2('#form5');

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 0
            }, function(result) {

                $("#form5").html(result);
                /*
                               var altura = window.screen.height;
                                altura = altura - (altura*0.48);
                                $("#conteudo").css({
                                height:altura+"px"                                
                                }); 
                                */

                $(":file").filestyle({
                    input: false,
                    buttonText: "Foto ou Imagem",
                    iconName: "glyphicon glyphicon glyphicon-camera",
                    size: "lg",
                    badge: false
                });


            });

        };

        function _listaOSResumo(_idsituacao) {
            $('#_dataref').val($('#_dataIni').val());
                $('#_sittrackmob').val(_idsituacao);
                _carregando('#result-oslista');
              
              $('#custom-modal-oslista').modal('show');               
                var $_keyid = "S00023";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 9
                    }, function(result) {
                        $("#result-oslista").html(result);
                    });
            }

            function _listaOSResumoRR(_idsituacao) {
                $('#_dataref').val($('#_dataIni').val());
                $('#_sittrackmob').val(_idsituacao);
                _carregando('#result-oslista');
              
              $('#custom-modal-oslista').modal('show');               
                var $_keyid = "S00023";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 10
                    }, function(result) {
                        $("#result-oslista").html(result);
                    });
            }

        function _listOStec() {

            $('#_idref').val($('#tecnico_e').val());
            $('#_dataref').val($('#_dataIni').val());

            _carregando('#result_detalheOS');
            _carregando('#result_listaOS');
            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 1
            }, function(result) {

                $("#form4").html(result);
            });
        };

        function _datalist() {
           
            $('#_dataref').val($('#_dataIni').val());
            var $_keyid = "<?= $_LINK; ?>";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#resultado');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados
            }, function(result) {               
                $("#resultado").html(result);
            });

            var $_keyid = "_ATa00013";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#resultadoTotal');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados
            }, function(result) {

                $("#resultadoTotal").html(result);
            });

        };


        function _rotaAtendimento($_idref) {

            $('#_idref').val($_idref);

            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            _carregando('#iniciar-2');

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 12
            }, function(result) {
                $("#iniciar-2").html(result);
            });

        }


        function _iniciarAtendimento($_idref, $_datarefid) {
            getLocation($_idref, $_datarefid);
        }

        function _iniciarAtendimentonow() {


            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            _carregando('#_retverand');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 444
            }, function(result) {
                if(result != "") {
                   
                    $("#_retverand").html(result);
                   
                }else{

                    _carregando('#form5');   
                            $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,
                            acao: 4
                        }, function(result) {

                            _000077($('#_idref').val(), $('#_datarefid').val());
                            /* 
                                            var $_keyid = "_ATa00020";
                                                    $('#_keyform').val($_keyid);
                                                    $('#form1').submit();
                                            var $_keyid =   "_ATa00014";                                                     
                                        
                                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao:0}, function(result){                                                         
                                            $("#form5").html(result);  
                                            var altura = window.screen.height;
                                            altura = altura - (altura*0.48);
                                            $("#conteudo").css({
                                            height:altura+"px"                                
                                            });                               
                                                                        
                                            });                                
                                            */
                        });
              }

            });

     
        };

        function _salvarTecOS() {

            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#atualizaOS');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 5
            }, function(result) {

                $("#atualizaOS").html(result);
                //  $('#custom-modal-tec').modal('hide');                               
                document.getElementById("_submenu").style.display = "none";
                document.getElementById("_principal").style.display = "";
                relogio();
            });

        }

        function _salvarTecOS_continuar() {

            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            // _carregando('#atualizaOS');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 5
            }, function(result) {
                
                $('#_alerta').html(result);

            });

        }

        function _fecharTecOS() {
            document.getElementById("_submenu").style.display = "none";
            document.getElementById("_principal").style.display = "";
            relogio();

        }

        function _aparelhoBusca() {
            var $_keyid = "S00012";
            var dados = $("#formAp :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#pesquisaaparelho');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 3
            }, function(result) {
                $('#pesquisaaparelho').html(result);
            });
        }



        function _aparelhoSEL(aparelho, marca, modelo,preventivo,cod_produto,id_prodaparelho) {
            //$('#marca').val(marca);
            $('#descricaoproduto').val(aparelho);
            $('#modelo').val(modelo);
            $('#indaparelho').val(id_prodaparelho);           
            // $('#preventivo').val(preventivo);
            $('#custom-modal-aparelho').modal('hide');
            _btmedicao();    
        }

        function _btmedicao(){
                
                var $_keyid = "S00029";
                var dados = $("#form5 :input").serializeArray();
                dados = JSON.stringify(dados);              
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 99
                }, function(result) {               
                    $('#ret_med').html(result);            
                    _medicao();
                });
            
            }

            function _medicao(){
        
                var $_keyid = "S00029";
                var dados = $("#form5 :input").serializeArray();
                dados = JSON.stringify(dados);              
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 9
                }, function(result) {               
                    $('#result-medicao').html(result);                  
                });
            
            }

            function _gravarMed(){
            
                var $_keyid = "S00029";
                var dados = $("#formMed :input").serializeArray();
                dados = JSON.stringify(dados);              
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                }, function(result) {               
                    $('#retorno_medicao').html(result);
                });
            
            
            }

        function _aparelhoADD() {
            var $_keyid = "S00012";
            var dados = $("#formAp :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#pesquisaaparelho');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 4
            }, function(result) {
                $('#pesquisaaparelho').html(result);
            });
        }

        function _aparelhoSalvar() {
            var $_keyid = "S00012";
            var dados = $("#formAp :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#pesquisaaparelho');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 5
            }, function(result) {

                res = result.split(";");

                $('#descricaoproduto').val(res[0]);
                $('#marca').val(res[1]);
                $('#modelo').val(res[2]);
                $('#custom-modal-aparelho').modal('hide');
                $('#pesquisaaparelho').html("");
            });
        }

        function resize_image(input, width, height) {
            if (input.files[0].type.match(/image.*/)) {

                var canvas = $("#resizer")[0].getContext('2d');
                canvas.canvas.width = width;
                canvas.canvas.height = height;
                // If image's aspect ratio is less than canvas's we fit on height
                // and place the image centrally along width

                var img = new Image;
                img.src = URL.createObjectURL(input.files[0]);

                img.onload = function() {
                    var imageAspectRatio = img.width / img.height;
                    var canvasAspectRatio = canvas.canvas.width / canvas.canvas.height;
                    var renderableHeight, renderableWidth, xStart, yStart;

                    if (imageAspectRatio < canvasAspectRatio) {
                        renderableHeight = canvas.canvas.height;
                        renderableWidth = img.width * (renderableHeight / img.height);
                        xStart = (canvas.canvas.width - renderableWidth) / 2;
                        yStart = 0;
                    }

                    // If image's aspect ratio is greater than canvas's we fit on width
                    // and place the image centrally along height
                    else if (imageAspectRatio > canvasAspectRatio) {
                        renderableWidth = canvas.canvas.width
                        renderableHeight = img.height * (renderableWidth / img.width);
                        xStart = 0;
                        yStart = (canvas.canvas.height - renderableHeight) / 2;
                    }

                    // Happy path - keep aspect ratio
                    else {
                        renderableHeight = canvas.canvas.height;
                        renderableWidth = canvas.canvas.width;
                        xStart = 0;
                        yStart = 0;
                    }
                    canvas.drawImage(img, xStart, yStart, renderableWidth, renderableHeight);

                    //   canvas.drawImage(img, 0, 0, width, height);

                }



            } else {

                $('#fotosdetalhe').html("Algo deu errado carregar imagem");
            }
        }

        function send_image() {
            var image_base64 = $("#resizer")[0].toDataURL();

            if (image_base64 != $('#textbase').val()) {
                $('#textbase').val(image_base64);

                var OSn = $('#_idos').val();
                _carregando('#fotosdetalhe');
                $.post(
                    "acaoDoc.php", {
                        imgBase64: image_base64,
                        acao: 5,
                        _idos: OSn
                    },
                    function(response) {
                        $('#fotosdetalhe').html(response);

                        var canvas = document.getElementById('resizer');
                        var context = canvas.getContext('2d');

                        clearCanvas(context, canvas);

                    }
                );
            }
        }

        function clearCanvas(context, canvas) {
            context.beginPath();


        }

        function clearCanvasNew() {
            $('#textbase').val('');
            var canvas = document.getElementById('resizer');
            var context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
            var w = canvas.width;
            canvas.width = 1;
            canvas.width = w;
            context.beginPath();

        }



        function _carregarfoto(_idos, idref) {
            $('#_idos').val(_idos);
            $('#_idfoto').val(idref);

            var $_keyid = "_ATa00022";
            $('#custom-modal-foto').modal('show');
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#foto_detalhe');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 6
            }, function(result) {

                $("#foto_detalhe").html(result);

            });
        }

        function _carregarfotoViewer(_idos, idref) {

            $('#_idfotoV').val(idref);

            var $_keyid = "_ATa00022";
            $('#custom-modal-foto').modal('show');
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#foto_detalhe');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 66
            }, function(result) {

                $("#foto_detalhe").html(result);

            });
        }

        function _carregarfotoViewerConsulta(_idos, idref) {

            $('#_idfotoV').val(idref);

            var $_keyid = "_ATa00022";
            //$('#custom-modal-foto').modal('show');
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#foto_detalhenew');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 67
            }, function(result) {
                $("#foto_detalhenew").html(result);
            });
        }



        function _excluirfoto(idref) {

            $('#_idfoto').val(idref);

            var $_keyid = "_ATa00022";
            $('#custom-modal-foto').modal('show');
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#fotosdetalhe');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 7
            }, function(result) {

                $("#fotosdetalhe").html(result);
                $('#custom-modal-foto').modal('hide');

            });
        }

        function _excluirfotoV(idref) {

            $('#_idfotoV').val(idref);

            var $_keyid = "_ATa00022";
            $('#custom-modal-foto').modal('show');
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#fotosdetalhe');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 77
            }, function(result) {

                $("#fotosdetalhe").html(result);
                $('#custom-modal-foto').modal('hide');

            });
        }




        //pecas
        function _idprodutobusca(_id) {
            _buscaProdutoCod(_id);
        }

        function _buscaProdutoCod(id) {
            $('#_keyidpesquisa').val(id);
            var $_keyid = "S00099";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 2
            }, function(result) {
                ;
                var ret = JSON.parse(result);
                $("#_codpesq").val(ret.CODIGO_FORNECEDOR);
                $('#_desc').val(ret.DESCRICAO);
                $('#_vlr').val(ret.Tab_Preco_5);
            });
        }

        function _adicionaProduto(tipo) {

            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);;
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 3
            }, function(result) {

                $('#listagem-produtos').html(result);
                $('#_codpesq').val("");
                $('#_desc').val("");
                $('#_qtde').val("");
                $('#_vlr').val("");
            });
        }

        function _adicionaServico(tipo) {

            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 13
            }, function(result) {
                $('#listagem-servicos').html(result);
                $('#_vlrS').val("");
            });
        }

        function _descservico() {
            $("#_descS").val($("#_codpesqS option:selected").text());
        }


        function _idexcluir(_id, _desc, tipo) {

            $('#_idexpeca').val(_id);
            if (tipo == 1) {
                $('#textexclui').html('Deseja realmente excluir produto "' + _desc + '" ?');
                $('#textexcluibt').html('<button type="button"  class="confirm btn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirProduto();">Excluir</button>');
            } else {
                $('#textexclui').html('Deseja realmente excluir Serviços "' + _desc + '" ?');
                $('#textexcluibt').html('<button  type="button"  class="confirm btn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirServico();">Excluir</button>');
            }

            $('#custom-modal-excluir').modal('show');

        }

        function _iddetalhes(_id) {

            $('#_idexpeca').val(_id);
            _carregando('#_pecadetalhe');
            $('#custom-modal-detpeca').modal('show');

            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 9
            }, function(result) {

                $('#_pecadetalhe').html(result);

            });
        }

        function _reserva(_idpeca, _os, _idcodpeca, _qtde, _usuario,codfornecedor) {

            $('#_idexpeca').val(_idpeca + ';' + _os + ';' + _idcodpeca + ';' + _qtde + ';' + _usuario + ';' + codfornecedor);
            _carregando('#pcreserva');


            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 61
            }, function(result) {

                $('#pcreserva').html(result);

            });
        }

        function _excluirProduto() {

            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);

            _carregando('#listagem-produtos');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 4
            }, function(result) {
                $('#listagem-produtos').html(result);
            });
            $('#custom-modal-excluir').modal('hide');

        }

        function _excluirServico() {

            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#listagem-produtos');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 44
            }, function(result) {
                $('#listagem-servicos').html(result);
            });
            $('#custom-modal-excluir').modal('hide');

        }


        function _listaResumo() {
            _carregando('#divFormaPagamento');

            var $_keyid = "S00099";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 50
            }, function(result) {

                $('#divFormaPagamento').html(result);
            });
        }

        function acompanhamento() {

            var $_keyid = "S00010";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 100
            }, function(result) {

                $('#result-acopanhamento').html(result);
            });
        }

        function _acompanhamentoincluir() {
            var $_keyid = "S00010";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);

            _carregando('#result-acopanhamento');

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 111
            }, function(result) {
                $('#result-acopanhamento').html(result);
            });
        }

        

        function _cancelarmotivo() {
            
            $('#motivooutrosAT').val($('#motivooutros').val());
            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 16
            }, function(result) {
                $('#_cancelasemat').html(result);
                $('#motivooutrosAT').val('');
            });

        }

        function _motivo(_idmot,_idsit) {
       

            $('#_motivoselecionado').val(_idsit);
            $('#_idalt').val(_idmot);
            $('#_idstatustrack').val(_idmot);
            //  _carregando('#_motivodetalhe');
            //  $('#custom-modal-motivo').modal('show');  
            _carregando('#finalizar-2');

            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 8
            }, function(result) {

                //        $('#_motivodetalhe').html(result);   
                $('#finalizar-2').html(result);

            });
        }

        function _motivoselecionar(_idmot, _idtextmot) {
            document.getElementById("_txtoutros").style.display = "none";
            $('#_idalt').val(_idmot);

            $('#_motivoselecionado').val(_idtextmot);

            if ($('#_motivoselecionado').val() == 'Outros') {

                document.getElementById("_txtoutros").style.display = "";
            }

        }


        function _cancelarAtend() {
            document.getElementById("_msgcancela").style.display = "";
        }


        function _cancelarid(_idref) {
            $('#_datarefid').val(_idref);
            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 15
            }, function(result) {
                $('#_msgcancela').html(result);

            });
        }



        function _validamotivo(_idmot) {

            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 99
            }, function(result) {
                if (result == "") {

                    $('#finalizar-2').removeClass('active'); // to remove the current active tab
                    _motivofoto(_idmot);
                    $('#foto-2').addClass('active'); // add active class to the clicked tab



                } else {

                    $.Notification.notify('error', 'top right', 'Aviso!', result);

                }

            });

        }


        function _motivofoto(_idmot) {
            $('#_idstatustrack').val(_idmot);
            clearCanvasNew();

            _carregando('#_motivofinalizar');
            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 10
            }, function(result) {
                $('#_motivofinalizar').html(result);
            });

        }

        function _motivofinalizar(_idmot) {
            $('#_idstatustrack').val(_idmot);


            _carregando('#finalizar-2');
            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 10
            }, function(result) {
                //   $('#_motivofinalizar').html(result);     

                $('#finalizar-2').html(result);

            });

        }


        function _lim() {

            $('#_idalt').val("");


        }

        function _selFinalizar(_id) {


            $('#_idstatustrack').val(_id);
            _motivoconfirmar();

        }




        function _motivoconfirmar() {

            _carregando('#_motivodetalhe');

            var $_keyid = "_ATa00022";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 9
            }, function(result) {

                if (result == "") {
                    $('#_motivodetalhe').html(result);

                    $('#custom-modal-motivo').modal('hide');
                    $('#custom-modal-tec').modal('hide');
                    var $_keyid = "_ATa00020";
                    $('#_keyform').val($_keyid);
                    $('#form1').submit();
                } else {
                    $.Notification.notify('error', 'top right', 'Aviso!', result);

                }

            });
        }


        function _REFRESH() {
            $('#_dataref').val($('#_dataIni').val());
            var $_keyid = "_ATa00020";
            $('#_keyform').val($_keyid);
            $('#form1').submit();
        }



        function _carregando(_idmodal) {
            $(_idmodal).html('' +
                '<div class="bg-icon pull-request" >' +
                '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde."></div>');

        }

        function _carregando2(_idmodal) {
            $(_idmodal).html('' +
                '<div class="bg-icon pull-request"  style="margin-top:100px">' +
                '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde."></div>');

        }


        $('#datatable-responsive').DataTable();



        var min, seg, myTimer;
        min = 5;
        seg = 1

        function relogio() {


            if ((min > 0) || (seg > 0)) {
                if (seg == 0) {
                    seg = 59;
                    min = min - 1
                } else {
                    seg = seg - 1;
                }
                if (min.toString().length == 1) {
                    min = "0" + min;
                }
                if (seg.toString().length == 1) {
                    seg = "0" + seg;
                }
                document.getElementById('spanRelogio').innerHTML = min + ":" + seg;
                myTimer = setTimeout('relogio()', 1000);
            } else {
                document.getElementById('spanRelogio').innerHTML = "00:00";
                _REFRESH();
            }
        }



        var myTimer2;



        function relogioAtendimento(_idatendimento) {
            var myTimer2 = setInterval(function() {
                $.post("timetrack2.php", {
                        _id: _idatendimento
                    },
                    function(result) {

                        $('#tempocontador').html(result);

                    });
            }, 59000);

        }

        function _buscadescricao() {

            var $_keyid = "S00012";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            document.getElementById("descricao_busca").style.display = "";

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 2
            }, function(result) {

                $('#descricao_busca').html(result);
            });


        }

        function _000010($_idref) {
            var $_keyid = "S00001";
            $('#_chaveid').val($_idref);
            $('#_keyform').val($_keyid);
            $("#form1").submit();
        };

        function descricao_carrega(_mod, produto, marca) {
            $('#descricaoproduto').val(produto);
            $('#modelo').val(_mod);
            document.getElementById("descricao_busca").style.display = "none";
        }

        function _sairdescricao() {
            document.getElementById("descricao_busca").style.display = "none";
        }

        function getLocation(_id, $_datarefid){
            $('#_idref').val(_id);
            $('#_datarefid').val($_datarefid);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("O seu navegador não suporta Geolocalização");
                _iniciarAtendimentonow();
            }

        }


        function showPosition(position) {
            $('#_lat').val(position.coords.latitude);
            $('#_long').val(position.coords.longitude);

            _iniciarAtendimentonow();

        }

        function _atualizarGps(_id) {
            $('#_datarefid').val(_id);
            if (navigator.geolocation) {
                //_carregando('#form5');
                _carregando('#_gpalerta');
                navigator.geolocation.getCurrentPosition(showPosition2);
            } else {
                alert("O seu navegador não suporta Geolocalização");

            }

        }

        function showPosition2(position) {

            $('#_lat').val(position.coords.latitude);
            $('#_long').val(position.coords.longitude);

            var $_keyid = "_ATa00022";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 44
            }, function(result) {

                $('#_gpalerta').html(result);

            });


        }

        function soNumeros(evt) {
            /*Retorna máscara com R$*/
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
            //var regex = /^[0-9.,]+$/;
            var regex = /^[0-9.]+$/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }


        relogio();
    </script>



</body>

</html>