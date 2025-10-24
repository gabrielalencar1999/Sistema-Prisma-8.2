<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");


use Database\MySQL;

$pdo = MySQL::acessabd();
date_default_timezone_set('America/Sao_Paulo');


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
                    <h4 class="page-title m-t-15">Painel Oficina Diário</h4>
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
                        <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fecharDiario()"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado" style="padding:0px">
                            <?php
                            $_parametros = array();
                            $_LINK = "_ATa000122";
                            // require_once('../../api/view/atendimento/trackmoblist.php');
                          require_once('../../api/view/atendimento/trackmoblistGerencialOficina.php'); ?>
                        </div>
                    </div>
                </div>
         


        </div> <!-- end container -->
    </div>


    <!-- Footer -->
    <?php if ($_SESSION["nivel"] != 1) { 

     ?>
    <div  style=" bottom: 0;border-top: 1px solid rgba(0, 0, 0, 0.1);
    position: fixed;
    width: 100%;
    text-align: center;background: url(assets/images/agsquare.png);">
<div class="col-sm-12">
<div class="col-xs-12" style="text-align:center ;">
<div  id="resultadoTotal">

<table width="100%" > <?php
        $_parametros = array();
     require_once('../../api/view/atendimento/trackmobTotalOficina.php');
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
    <div id="custom-modal-os" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
            <div class="modal-dialog ">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Carregando Dados</h4>
                    </div>
                    <div class="modal-body">
                            <div id="result-os" class="result">                             
                            </div>                      
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
        <input type="hidden" id="_sitoficina" name="_sitoficina" value=""> 
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

        
        function _fecharDiario() {
            
                var $_keyid = "S00022";
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

            var $_keyid = "_ATa00011";
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
        function _000010($_idref) {
                _carregando('#result-os');
              
                $('#custom-modal-os').modal('show');

                var $_keyid = "S00001";

                $('#_chaveid').val($_idref);
                $('#_keyform').val($_keyid);
               
                $("#form1").submit();  


            };





        function _listOStec() {

            $('#_idref').val($('#tecnico_e').val());
            $('#_dataref').val($('#_dataIni').val());

            _carregando('#result_detalheOS');
            _carregando('#result_listaOS');
            var $_keyid = "_ATa00011";
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
            var $_keyid = "_ATa00025";
            $('#_keyform').val($_keyid);
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
     
            _carregando('#resultado');

         
            _REFRESH();   


         

         
        };





        function _REFRESH() {
            $('#_dataref').val($('#_dataIni').val());
            var $_keyid = "_ATa00023";
            $('#_keyform').val($_keyid);
            $('#form1').submit();
        }

        function _listaOS(_idsituacao) {
            $('#_dataref').val($('#_dataIni').val());
                $('#_sitoficina').val(_idsituacao);
                _carregando('#result-oslista');
              
              $('#custom-modal-oslista').modal('show');               
                var $_keyid = "S00023";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 8
                    }, function(result) {
                        $("#result-oslista").html(result);
                    });
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
                $.post("timetrack.php", {
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