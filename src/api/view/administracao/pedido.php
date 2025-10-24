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
      //  $data_ini = date('Y-m-d');
        $data_ininf = date('Y-m-d');
    }

    if ($data_fim == "") {
        $data_fim = date('Y-m-d');
    }

    if($dataini == "" ) {
       // $data_ini = date('Y-m-01');
    }
      
    ?>

    <div class="wrapper">
        <div class="container" style="width:97% ">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">Gestão Pedidos <span id="_sel"></span></h4>
                    <p class="text-muted page-title-alt">Controle de Pedidos</p>
                </div>
                
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                       <button class="btn btn-white  waves-effect waves-light" data-toggle="modal" data-target="#modalvalida" onclick="_validanf()"><i class="ti-zip"></i> </span>NF Val.</button>
                       <button class="btn btn-purple  waves-effect waves-light" onclick="_analise()"><i class="fa fa-hourglass-2"></i> </span>Análise</button>
                       <button class="btn btn-success waves-effect waves-light" onclick="_pedido()"><i class="fa fa-wpforms"></i> </span>Pedidos</button>
                        
                     
                
                        
                        <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                            <?php
                          //  $_parametros = array();
                          //  require_once('../../api/view/servicos/roteirolista.php'); ?>
                           <div class="alert alert-warning text-center">
                                 Selecione opções <strong>ANÁLISE</strong> OU <strong>PEDIDOS</strong>
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
                                        <label for="field-1" class="control-label">Nº Pedido</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-sm" id="_npedido" name="_npedido">
                                        </div>
                                    </div>    
                                    <div class="col-md-2">
                                        <label for="field-1" class="control-label">Código</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-sm" id="_codpeca" name="_codpeca">
                                        </div>
                                    </div>                              
                                </div>
                            
                                            <div class="row">
                                            <div class="col-md-2">
                                                    <div class="form-group">                                                 
                                                    <label for="field-1" class="control-label">Status Pedido</label>
                                                    </div>
                                                </div>
                                            <div class="col-md-4">
                                                    <div class="form-group">                                                      
                                                    <select class="form-control input-sm"   name="_StatusPedido" id="_StatusPedido">
                                                            <?php
                                                            $statement2 = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".situacaoPed  ORDER BY sp_desc");
                                                            $retorno2 = $statement2->fetchAll();
                                                            ?>
                                                                <option value="">Selecione</option>
                                                            <?php
                                                            foreach ($retorno2 as $row2) {
                                                            ?>
                                                                <option value="<?=$row2["sp_id"]?>"><?=$row2["sp_desc"]?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                            </select>                       
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">                                                 
                                                    <label for="field-1" class="control-label">Status Item</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                      
                                                    <select class="form-control input-sm"   name="_StatusItem" id="_StatusItem">
                                                            <?php
                                                            $statement2 = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".situacaoPedItem  ORDER BY spi_desc");
                                                            $retorno2 = $statement2->fetchAll();
                                                            ?>
                                                                <option value="">Selecione</option>
                                                                
                                                            <?php
                                                            foreach ($retorno2 as $row2) {
                                                            ?>
                                                                <option value="<?=$row2["spi_id"]?>"><?=$row2["spi_desc"]?></option>
                                                            <?php
                                                            }
                                                            
                                                            ?>
                                                             <option value="0">Todos</option>
                                                            </select>                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                     <label for="field-1" class="control-label">Dt Atualização Pedido </label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="date" class="form-control" name="_dataAT"  id="_dataAT" value="">
                                                    </div>
                                                </div>
                                              
                                               
                                            </div>
                                      
                           
                            </div>
                          

                     
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light" data-dismiss="modal">Filtrar</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

      

        <form id="form9" name="form9" action="javascript:void(0)">
            <input type="hidden" name="_codpesq" id="_codpesq" value="">
            <input type="hidden" name="_marca" id="_marca" value="">
            <input type="hidden" name="_produto" id="_produto" value="">
            <input type="hidden" name="_modelo" id="_modelo" value="">
            <input type="hidden" name="_tensao" id="_tensao" value="">     
            <input type="hidden" name="_vlr" id="_vlr" value="">   
            <input type="hidden" name="_idsel" id="_idsel" value=""> 
            <input type="hidden" name="_codpesquisaOS" id="_codpesquisaOS" value=""> 
            
                    
            <div id="modalincluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_analise()">×</button>
                            <h4 class="modal-title">Incluir </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                          <div class="col-sm-3 col-xs-6">
                                            <label>Código</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                    <button type="button" data-toggle="modal" data-target="#custom-modal-buscar" class="btn waves-effect waves-light btn-primary input-sm" style="padding-top:5px;"><i class="fa fa-search"></i></button>
                                                    </span>
                                                    <input type="text" tabindex="6"  name="codbarra-mov" id="codbarra-mov" class="form-control  input-sm" onblur="_idprodutobusca(this.value)" onKeyDown="TABEnter('','qnt-mov')" value="" placeholder="Peça/Produto">
                                                </div>                                           
                                            </div> 
                                            <div class="col-sm-3 col-xs-6" >
                                                <label>Descrição</label>
                                                <input tabindex="7"  type="text" class="form-control input-sm" name="_desc" id="_desc" onKeyDown="TABEnter('','qnt-mov')" value="" readonly>                                                                              
                                            </div> 
                                            <div class="col-sm-1 col-xs-3">        
                                                <label>Qtde</label>
                                                <input tabindex="8"  type="text" class="form-control input-sm" name="qnt-mov" id="qnt-mov" onKeyDown="TABEnter('','OS')" value="">        
                                            </div>
                                            <div class="form-group col-xs-1 col-md-1">
                                                <label for="os">OS</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                        <button type="button" onclick="_carregarOS()" class="btn waves-effect waves-light btn-white input-sm" style="padding-top:5px;"><i class="fa  fa-binoculars"></i></button>
                                                        </span>
                                                      
                                                    </div>                                           
                                                </div>
                                                <div class="form-group col-xs-3 col-md-2">
                                                <label for="os">Nº Documento</label>
                                                                                                      
                                                        <input type="text" tabindex="9" name="OS" id="OS" class="form-control  input-sm"    onKeyDown="TABEnter('','cadastrar')" >
                                                                                      
                                                </div>
                                            <div class="col-sm-2 col-xs-4" style="margin-top: 25px;">       
                                                <button tabindex="10" id="cadastrar" type="button" class="btn btn-success waves-effect waves-light mb-auto" onclick="_incluir()"><i class="fa fa-plus"></i></button>                                                                                             
                                            </div> 
                            </div>
                           
                         <div class="row">    
                                    <div class="col-md-12" id="_retaviso">                        
                                    </div>
                                    <div class="col-md-12" id="_ret">                                       
                                    </div>                           
                         </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" onclick="_analise()">Fechar</button>
                            
                          
                        </div>
                    </div>
                </div>
                </div>
            </div><!-- /.modal -->
        </form>

        <form id="form8" name="form8" action="javascript:void(0)">
            <input type="hidden" name="_codpedidoitem" id="_codpedidoitem" value="">
           
            
                    
            <div id="modalpedido" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                           
                        </div>
                        <div class="modal-body" id="_detpedido">
                            
                     
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                          
                        </div>
                    </div>
                </div>
                </div>
            </div><!-- /.modal -->
        </form>

    
        <form id="formNF" name="formNF" action="javascript:void(0)">                    
            <div id="modalvalida" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >×</button>
                            <h4 class="modal-title">Validador de Notas de Entrada  </h4>
                        </div>
                        <div class="modal-body">
                                 <div class="row">
                                             
                                                <div class="col-md-4">
                                                <label for="field-1" class="control-label"> Data Entrada NF </label>
                                                    <div class="form-group">                                                                                                               
                                                        <input type="date" class="form-control" name="nf-inicial"  id="nf-inicial" value="<?=$data_ininf;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4" style="margin-top:25px;" id="resultnfval">
                                                  <button type="button" id="_00003" class="btn btn-warning waves-effect waves-light" onclick="_valnf()"><i class="fa  fa-refresh"></i> </span> Verificar</button>
                                                </div>
                                               
                                            </div>
                  
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                          
                        </div>
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Carregando OS</h4>
                    </div>
                    <div class="modal-body">
                      
                        
                      
                            <div id="result-os" class="result">
                             
                            </div>
                      
                    </div>
                </div>
            </div>
        </div>

    
 


        <form id="form1" name="form1" method="post" action="">
            <input type="hidden" id="_keyform" name="_keyform" value="">
            <input type="hidden" id="_chaveid" name="_chaveid" value="">
            <input type="hidden" id="_selchaveitem" name="_selchaveitem" value="">
            <input type="hidden" id="_selbox" name="_selbox" value="">

            <div id="modalPed" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Gerar Pedido</h4>
                            <input type="hidden" id="_opcrel" name="_opcrel" value="1">
                        </div>
                        <div class="modal-body">                                               
                          
                                <div id="_retpeditem">                                 
                                </div>                          
                           
                     
                       
                    </div>
                </div>
            </div><!-- /.modal -->
            </div>
            
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
                 
                    var $_keyid = "PEDIDO_0002";                   
                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    if( $("#_opcrel").val() == 0) {
                        $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,
                            acao: 2
                        }, function(result) {
                            $("#resultado").html(result);                
                        });

                    }else{
                        $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,
                            acao: 22
                        }, function(result) {
                            $("#resultado").html(result);                
                        });

                    }
                 

                });
            });


            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }
            
            function _analise() {
                $("#_opcrel").val(0);
                var $_keyid = "PEDIDO_0002";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 0
                }, function(result) {
                    $("#_sel").html(result);                
                });

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 2
                }, function(result) {
                    $("#resultado").html(result);    
                           
                });

            }

            function _pedido() {
                $("#_opcrel").val(1);
              
               var $_keyid = "PEDIDO_0002";
               var dados = $("#form1 :input").serializeArray();
               dados = JSON.stringify(dados);
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 1
               }, function(result) {
                   $("#_sel").html(result);                
               });

               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 22
               }, function(result) {
                   $("#resultado").html(result);                
               });

           }

           function _SelPedido(_idref) {
            
            
            if($('#'+_idref).is(':checked')){
                $_tipo = 0;
            }else{
                $_tipo = 1;
            };
        

             $("#_selchaveitem").val($("#"+_idref).val()+"|"+  $_tipo) ;
            
                    var $_keyid = "PEDIDO_0002";
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);
                   
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 11
                    }, function(result) {
                         
                    });
     
                }

          function _GerarPedido() {
                 
            $('#modalPed').modal('show');
               var $_keyid = "PEDIDO_0002";
               var dados = $("#form1 :input").serializeArray();
               dados = JSON.stringify(dados);
               _carregando('#_retpeditem');
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 12
               }, function(result) {
                   $("#_retpeditem").html(result);                
               });

           }

           function _ConfGerarPedido() {
                 
                 $('#modalPed').modal('show');
                    var $_keyid = "PEDIDO_0002";
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    _carregando('#_retconf');
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 13
                    }, function(result) {
                        $("#_retconf").html(result);     
                        _analise();           
                    });
     
                }
     
       
                function _printFiltro() {
                    var $_keyid = "PEDIDO_0002";
              
                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    _carregando('#_printviewer');
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 98
                    }, function(result) {
                        $('#_printviewer').html(result);
                        $('#_printviewer').printThis();
                       
                        
                    });

           

                }

                function _printOS() {
                    var $_keyid = "PEDIDO_0002";
              
                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    _carregando('#_printviewer');
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 97
                    }, function(result) {
                        $('#_printviewer').html(result);
                        $('#_printviewer').printThis();
                       
                        
                    });

           

                }

        
            function _print() {

                var $_keyid = "S00013";
                if( $("#_opcrel").val() == 2) {
                        $_keyid = "S00013a";

                    }
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

            function _valnf() {

                    var $_keyid = "PEDIDO_0002";
              
                    var dados = $("#formNF :input").serializeArray();
                    dados = JSON.stringify(dados);
                    _carregando('#resultnfval');
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 99
                    }, function(result) {
                        $('#_printviewer').html(result);
                        $('#_printviewer').printThis();
                        $('#resultnfval').html('<button type="button" id="_00003" class="btn btn-white waves-effect waves-light" onclick="_valnfrec()"><i class="fa  fa-checked"></i> </span> Nova Verificação</button>');
                       
                    });

                    }

                    
            function _valnfrec() {
                $('#resultnfval').html('<button type="button" id="_00003" class="btn btn-warning waves-effect waves-light" onclick="_valnf()"><i class="fa  fa-refresh"></i> </span> Verificar</button>');
            }

            

            function _gerarcsv(_acao) {

                var $_keyid = "S00027";
                if( $("#_opcrel").val() == 2) {
                        $_keyid = "S00027a";

                    }

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

                function _lista() {
               
               var $_keyid = "PEDIDO_0002";
               var dados = $("#form1 :input").serializeArray();
               dados = JSON.stringify(dados);
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 11
               }, function(result) {
                   $("#_ret").html(result);                
               });
             }
                
            function _listaAdd() {
                  
                    var $_keyid = "PEDIDO_0002";
                    var dados = $("#form9 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 4
                    }, function(result) {
                        $("#_ret").html(result);                
                    });
           }

           function _idprodutobusca(_id) {    
                if(_id != "")      {
                    _buscaProdutoCod(_id);
                }           
            }

            function _buscaProdutoCod(id) {
                var $_keyid = "PEDIDO_0002";
                var dados = $("#form9 :input").serializeArray();
                dados = JSON.stringify(dados);
             //   _carregando();

                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
                    function(result){                   
                       var ret = JSON.parse(result);    
                     
                                $("#_codpesq").val(ret[0].CODIGO_FORNECEDOR);
                                $('#_desc').val(ret[0].DESCRICAO);
                                $('#_vlr').val(ret[0].Tab_Preco_5);                       
                               // document.getElementById("_qtdealmox").innerHTML = "Qtd: "+ret.tot_item;
                    }); 
                }

               
    function _incluir() {
        var $_keyid = "PEDIDO_0002";
        var codpesq_A =  $("#_codpesq").val();
        var qntmov_A =  $('#qnt-mov').val();
        var desc_A =  $('#_desc').val();
        var vlr_A =  $('#_vlr').val();
        var OS_Aa  = $('#OS').val();

         
                    var dados = $("#form9 :input").serializeArray();
                    dados = JSON.stringify(dados);                           
                    $("#_retaviso").html('');     
                    _carregando('#_ret');

                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
                        function(result){                            
                            if(result.trim() == "") {
                                $("#_codpesq").val("");
                                $('#codbarra-mov').val("");                                                          
                                $('#qnt-mov').val("");
                                $('#_desc').val("");
                                $('#_vlr').val("0");
                                $('#OS').val("");    
                                $("#codbarra-mov").focus();                         
                                _listaAdd();
                            }
                            else{  //aviso                             
                                $("#_retaviso").html(result);     
                                _listaAdd();
                            }                            
                            
                        });
            

    }

    function _idexcluir(_id) {
        var $_keyid = "PEDIDO_0002";
        $("#_idsel").val(_id);
        var dados = $("#form9 :input").serializeArray();        
        dados = JSON.stringify(dados);                       
                 
         _carregando('#_ret');

                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
                        function(result){ 
                          _analise();
                          _listaAdd(); 
                        });
    }

    function _carregarOS() {
               
               var $_keyid = "PEDIDO_0002";
               var dados = $("#form9 :input").serializeArray();
               dados = JSON.stringify(dados);
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 8
               }, function(result) {
                   $("#_ret").html(result);                
               });
      }

    function _buscaOS() {      
      var $_keyid =   "PEDIDO_0002";        
      var dados = $("#form9 :input").serializeArray();
      dados = JSON.stringify(dados);    
      _carregando('#_retaviso');      
              $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 9}, function(result){	 
                  $("#_retaviso").html('');                      
                  $('#tbody_itemos').html(result);                                                                                       
               });
      }

    function _incluirOS(_idref) {   
        $('#_codpesquisaOS').val(_idref);
      
       var $_keyid = "PEDIDO_0002";
               var dados = $("#form9 :input").serializeArray();
               dados = JSON.stringify(dados);
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 10
               }, function(result) {
                _buscaOS();
               });

    }

    function _separarOS(_idref) {   
        $('#_codpesquisaOS').val(_idref);
      
       var $_keyid = "PEDIDO_0002";
               var dados = $("#form9 :input").serializeArray();
               dados = JSON.stringify(dados);
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 101
               }, function(result) {
                _buscaOS();
               });

    }
    function _separarExluirOS(_idref) {   
        $('#_codpesquisaOS').val(_idref);
      
       var $_keyid = "PEDIDO_0002";
               var dados = $("#form9 :input").serializeArray();
               dados = JSON.stringify(dados);
               $.post("page_return.php", {
                   _keyform: $_keyid,
                   dados: dados,
                   acao: 102
               }, function(result) {
                _buscaOS();
               });

    }

    


    
    function detPedido(_idref) {     
        $('#_codpedidoitem').val(_idref); 
        var $_keyid =   "PEDIDO_0002";        
        var dados = $("#form8 :input").serializeArray();
        dados = JSON.stringify(dados);    
        _carregando('#_detpedido');      
                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 23}, function(result){	
               
                    $("#_detpedido").html(result);                                                       
                });
      }

      function _salvaritem() {     
     
        var $_keyid =   "PEDIDO_0002";        
        var dados = $("#form8 ").serializeArray();
        dados = JSON.stringify(dados);    
                 _carregando('#_detpedido');      
                $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 24}, function(result){	
               
                    $("#_detpedido").html(result);     
                    _pedido();                                                  
                });
      }

      function _selitem() {     
        // Obtém todos os elementos de input do tipo checkbox
              var checkboxes = document.querySelectorAll('input[type="checkbox"]');

        
       if($('#_selbox').val() == "") { 
          // Itera sobre os checkboxes e define-os como marcados
          $('#_selbox').val('true');
          checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });

       }else{
          // Itera sobre os checkboxes e define-os como marcados
          $('#_selbox').val('');
          checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

       } 
     
    
          
        }

      

    function _carregando (_idmodal){
                    $(_idmodal).html('' +
                            '<div class="bg-icon pull-request">' +
                            '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                            '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                            '</div>');
    }

       

                function TABEnter(oEvent,tabA){
   
                var oEvent = (oEvent)? oEvent : event;
                var oTarget =(oEvent.target)? oEvent.target : oEvent.srcElement;
                if(oEvent.keyCode==13){
                if(oTarget.type=="text" && oEvent.keyCode==13){
                    $('#'+tabA).focus();
                }                              
                    
                if (oTarget.type=="radio" && oEvent.keyCode==13) {
                    $('#'+tabA).focus();
                }
                
                    
                }
                }


            $('#datatable-responsive').DataTable( {
                    "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
});
        </script>



</body>

</html>