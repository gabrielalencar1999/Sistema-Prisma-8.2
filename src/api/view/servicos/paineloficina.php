<?php
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();


?>
<!DOCTYPE html>
<html>
<?php require_once('header.php');

function addDayIntoDate($date,$days) {
    $thisyear = substr ( $date, 0, 4 );
    $thismonth = substr ( $date, 4, 2 );
    $thisday =  substr ( $date, 6, 2 );
    $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );
    return strftime("%Y%m%d", $nextdate);
}


?>
<style>

.dot {
    height: 25px;
    width: 25px;  
    border-radius: 50%;
    display: inline-block;
  }
  </style>


<body>
    <?php

    require_once('navigatorbar.php');
    if ($data_ini == "") {
        $data_ini = date('Y-m-d');
    }

    if ($data_fim == "") {
    
        $date = date("Ymd");
        $nextdate = addDayIntoDate($date,5);    // Adiciona 15 dias
            $ano = substr ( $nextdate, 0, 4 );
            $mes = substr ( $nextdate, 4, 2 );
            $dia =  substr ( $nextdate, 6, 2 ); 
            $data_prevista      = $ano."-".$mes."-".$dia;
            
            $data_fim = $data_prevista ;;
    }

    $query = "SELECT CODIGO_CHAMADA, g_sigla,sitmobOF_descricao,DATE_FORMAT(DATA_ENTOFICINA,'%d/%m/%Y') as dtoficina,DATE_FORMAT(DATA_ATEND_PREVISTO,'%d/%m/%Y') as dtatend
    FROM ". $_SESSION['BASE'] .".chamada_urgente  
    INNER JOIN  " . $_SESSION['BASE'] . ".chamada ON chmu_OS = CODIGO_CHAMADA        
    LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id        
    INNER JOIN ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA            
    where  CODIGO_SITUACAO <> 6  and SIT_OFICINA > 0 and sitmobOF_urgente = 1 ";  
   
    $consulta = $pdo->query("$query");    
    $_totalaviso =  $consulta->rowCount();

    //buscar 
  
    ?>

    <div class="wrapper">
        <div class="container" style="width:97% ">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">Painel Oficina  
                        <?php  if($_totalaviso > 0) {
                            echo '<span class="badge badge-danger m-l-0" style="cursor:pointer" onclick="_filtraUrg()"> ('.$_totalaviso.') urgentes <i class="icon-bell"></i></span> </h4>';
                        }
                        ?>
                </div>
                <div class="btn-group pull-right m-t-5">
                    <div class="m-b-5">
                       
                       
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                     <button class="btn btn-warning" onclick="_pdiario()"><span class="btn-label btn-label" > <i class="ti ti-bookmark-alt"></i></span>Painel Diário</button>
                         <!--
                        <button class="btn btn-inverse waves-effect waves-light" onclick="_print()"><span class="btn-label btn-label"> <i class="fa fa-print"></i></span>Imprimir</button>
                        --> <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
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
        <div  style=" bottom: 0;border-top: 1px solid rgba(0, 0, 0, 0.1);
                            position: fixed;
                            width: 100%;
                            text-align: center;background: url(assets/images/agsquare.png);">
                    <div class="col-sm-12">
                       
                        <div  id="resultadototal">
                        </div>
                        
                    </div>
                </div>

        <form id="form2" name="form2" action="javascript:void(0)">
        <input type="hidden" id="_dtfiltra" name="_dtfiltra" value=""> 
        <input type="hidden" id="_sitoficina" name="_sitoficina" value=""> 
        
            <div id="modalfiltro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Filtros</h4>
                        </div>
                        <div class="modal-body">
                          
                        
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="field-1" class="control-label">Pesquisar por</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <select name="_tipodata" id="_tipodata" class="form-control input-sm">
                                            <option value="1">Data Oficina</option>
                                            <option value="2">Data Atendimento</option>
                                            <option value="3">Data Abertura</option>
                                        </Select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="field-1" class="control-label">Tec.Oficina</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                    <select name="tecnico_of" id="tecnico_of" class="form-control input-sm">
                                                                        <option value=""> </option>
                                                                        <?php
                                                                        $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_perfil2 = '9' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
                                                                        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                                        $TotalReg = mysqli_num_rows($result);
                                                                        $codigoTec = $rst["Cod_Tecnico_Execucao"];

                                                                        while ($resultado = mysqli_fetch_array($result)) {
                                                                            $descricao = $resultado["usuario_APELIDO"];
                                                                            $codigo = $resultado["usuario_CODIGOUSUARIO"];
                                                                        ?>
                                                                            <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </Select>
                                    </div>
                                </div>
                            </div>
                           
                           

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button"  class="btn btn-info waves-effect waves-light" onclick = _list() >Filtrar</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content " id="result-oslista">
                   
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


            });


            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

    
            function _list() {
                var $_keyid = "S00023";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 1
                    }, function(result) {
                        $("#resultado").html(result);
                        $.post("page_return.php", {
                                _keyform: $_keyid,
                                dados: dados,
                                acao: 7
                            }, function(result) {
                                $("#resultadototal").html(result);
                                

                            });
                        $('#modalfiltro').modal('hide');
                    });

            }

            
            function _pdiario() {
                var $_keyid = "_ATa00023";
                           
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#form1 :input").serializeArray();
                         dados = JSON.stringify(dados);		
                       
                           $("#form1").submit();  

            }


            

            function _filtra(_dt) {
                _carregando('#result-os');
                
              $('#custom-modal-os').modal('show');
                 $('#_dtfiltra').val(_dt);
              
                var $_keyid = "S00023";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 1
                    }, function(result) {
                        $("#resultado").html(result);
                        $.post("page_return.php", {
                                _keyform: $_keyid,
                                dados: dados,
                                acao: 7
                            }, function(result) {
                                $("#resultadototal").html(result);                              
                            });
                        $('#custom-modal-os').modal('hide');
                    });

            }

            function _filtraUrg() {
                _carregando('#result-oslista');
                
              $('#custom-modal-oslista').modal('show');                               
                var $_keyid = "S00023";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 12
                    }, function(result) {
                        $("#result-oslista").html(result);
                      
                    });

            }

            function _listaOS(_idsituacao) {
                $('#_sitoficina').val(_idsituacao);
                _carregando('#result-oslista');
              
              $('#custom-modal-oslista').modal('show');               
                var $_keyid = "S00023";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 8
                    }, function(result) {
                        $("#result-oslista").html(result);
                    });
            }

            
            function _listaOSRR(_idsituacao) {
                $('#_sitoficina').val(_idsituacao);
                _carregando('#result-oslista');
              
              $('#custom-modal-oslista').modal('show');               
                var $_keyid = "S00023";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 11
                    }, function(result) {
                        $("#result-oslista").html(result);
                    });
            }
 

               function _listOStec(_data,_idtec) {

                    var $_keyid = "S00023";
                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);
                   
                    $('#custom-modal-os').modal('show');
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dt: _data,
                        idtec: _idtec,
                        acao: 2
                    }, function(result) {
                        $('#result-os').html(result);
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

            function _000100($_idref) {
                _carregando('#result-oslista');
              
              

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
            $('#datatable-responsive').DataTable( {
        "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
    } ]
});

_list();
        </script>



</body>

</html>