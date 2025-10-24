<?php
include("../../api/config/iconexao.php");

use Database\MySQL;
$pdo = MySQL::acessabd();
use Functions\Acesso;


$_retviewerRoteiro = Acesso::customizacao('1');


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

    if($dataini == "" ) {
        $date = date("Ymd");
       $nextdate = addDayIntoDate($date,1);    // Adiciona 15 dias
            $ano = substr ( $nextdate, 0, 4 );
            $mes = substr ( $nextdate, 4, 2 );
            $dia =  substr ( $nextdate, 6, 2 ); 
            $data_prevista      = $ano."-".$mes."-".$dia;
          
            $data_ini =   $data_prevista ;
            $data_fim =   $data_prevista ;
     }
    ?>

    <div class="wrapper">
        <div class="container" style="width:97% ">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-xs-4">
                    <h4 class="page-title m-t-15">Roteiro </h4>
                    <p class="text-muted page-title-alt">Roteiro Atendimento</p>
                </div>
                
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                       <button  type="button" id="opResumo" class="btn btn-white waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resumo" onclick="_Resumo()"> <i class="fa  fa-database"></i></button>
                        <button type="button" id="opAvancado"  class="btn btn-white waves-effect waves-light" data-toggle="tooltip"  data-placement="top" title="Opções Avançada"> <i class="fa  fa-gear"></i></button>
                        <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa  fa-sliders"></i></span>Filtros</button>
                     
                        <button class="btn btn-inverse waves-effect waves-light" onclick="_print()"><span class="btn-label btn-label"> <i class="fa fa-print"></i></span>Imprimir</button>
                        
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
                                 Selecione os filtros para listar roteiro.
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
                                
                        
                                <div class="col-md-12">
                                     <ul class="nav nav-pills m-b-30">
                                                <li class="active">
                                                    <a href="#navpills-11" onclick="_tabon(1)"data-toggle="tab" aria-expanded="true">Atend.Externo</a>
                                                </li>
                                                <li class="">
                                                    <a href="#navpills-21" onclick="_tabon(2)" data-toggle="tab" aria-expanded="false">Atend.Oficina</a>
                                                </li>
                                            
                                       </ul>
                                </div>
                        </div>
                          
                      
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Data </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="date" class="form-control input-sm" name="_dataIni" id="_dataIni" value="<?= $data_ini; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Nº OS</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" id="_os" name="_os">
                                    </div>
                                </div>
                               
                            </div>
                           
                            <div class="row">
                                                 
                                                 <div class="col-md-2">
                                                      <label for="field-1" class="control-label">Tipo Atend.</label>
                                                 </div>   
                                                 <div class="col-md-4">
                                                     <div class="form-group">                                                       
                                                     <select name="garantia" id="garantia"   class="form-control input-sm">
                                                        <option value="">Todos</option> 
                                                        <?php
                                                         $queryTipoAtend = ("SELECT * FROM situacao_garantia ORDER BY g_descricao");
                                                         $restipoAtend = mysqli_query($mysqli, $queryTipoAtend)  or die(mysqli_error($mysqli));
                                                        
                                                        while ($resultadoTipoAtend = mysqli_fetch_array($restipoAtend)) {
                                                            $idgarantia = $resultadoTipoAtend["g_id"];
                                                            $descgarantia = $resultadoTipoAtend["g_descricao"];
                                                            ?>
                                                                <option value="<?=$idgarantia;?>"><?=$descgarantia;?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                     </div>
                                                 </div>  
                                                 <div class="col-md-2">
                                                        <label for="field-1" class="control-label">Situação O.S</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                            $querySit = ("SELECT * FROM situacaoos_elx order by DESCRICAO");
                                                            $resultSit = mysqli_query($mysqli, $querySit)  or die(mysqli_error($mysqli));
                                                            $TotalRegSit = mysqli_num_rows($resultSit);
                                                            ?>
                                                            <select name="situacao" id="situacao"  class="form-control input-sm">
                                                                <option value="">Todos</option>
                                                                <?php
                                                                while ($resultado = mysqli_fetch_array($resultSit)) {
                                                                    $codigoSit = $resultado["COD_SITUACAO_OS"];
                                                                    $descricaoSit = $resultado["DESCRICAO"];
                                                                ?>
                                                                    <option value="<?php echo "$codigoSit"; ?>"> <?php echo "$descricaoSit"; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                                                         
                              </div>
                         
                           
                         
                            
                           
                                    <div class="tab-content br-n pn">
                                                <div id="navpills-11" class="tab-pane active">
                                            
                                                                <div class="row">
                                                                        <div class="col-md-2">
                                                                            <label for="field-1" class="control-label">Sit. MOB</label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <?php
                                                                                $querySitMob = ("SELECT * FROM situacao_trackmob order by sitmob_descricao");
                                                                                $resultSitMob = mysqli_query($mysqli, $querySitMob)  or die(mysqli_error($mysqli));
                                                                                $TotalRegSitMob = mysqli_num_rows($resultSitMob);
                                                                                ?>
                                                                                <select name="situacaomob" id="situacaomob"  class="form-control input-sm">
                                                                                    <option value="">Todos</option>
                                                                                    <?php
                                                                                    while ($resultado = mysqli_fetch_array($resultSitMob)) {
                                                                                        $codigoSit = $resultado["sitmob_id"];
                                                                                        $descricaoSit = $resultado["sitmob_descricao"];
                                                                                    ?>
                                                                                        <option value="<?php echo "$codigoSit"; ?>"> <?php echo "$descricaoSit"; ?></option>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                                <label for="field-1" class="control-label">Assessor</label>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <select name="tecnico_e" id="tecnico_e" class="form-control input-sm">
                                                                                        <option value=""> </option>
                                                                                        <?php
                                                                                        $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
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
                                                <div id="navpills-21" class="tab-pane">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        
                                                        <div class="col-md-2">
                                                                <label for="field-1" class="control-label">Tec.Oficina</label>
                                                            </div>
                                                            <div class="col-md-10">
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
                                                </div>
                                        
                                            </div>
                        


                            </div>
                            <div class="row">
                                    <div class="col-md-2">
                                        <label for="field-1" class="control-label">Arquivo</label>
                                    </div>
                                    <div class="col-md-6" id="result-arquivo">
                                        <button class="btn btn-white waves-effect waves-light"  onclick="_gerarcsv(1)"> <i class="fa  fa-file-excel-o"></i> Gerar Roteiro</button>
                                    </div>
                            </div>

                     
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light">Filtrar</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

        <form id="form9" name="form9" action="javascript:void(0)">
            <div id="modalavancado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Opções Avançada</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Data </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="date" class="form-control input-sm" name="dtaberturaSelAgenda" id="dtaberturaSelAgenda" value="<?=$data_ini; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                               
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Nº OS</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" id="_ostecnico" name="_ostecnico">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                            <div class="form-group">
                                                <select name="tecnicoOS" id="tecnicoOS" class="form-control input-sm">
                                                    <option value="0"> </option>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
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

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button class="btn btn-default waves-effect waves-light"  onclick="_agendarOS()"> <i class="fa   fa fa-calendar-check-o"></i> Agendar</button>
                                    </div>
                                </div>
                                
                               
                            </div>
                           
                       
                            <div class="row">                          
                                    <div class="col-md-12" id="_retagendar">
                                       
                                    </div>                           
                         </div>
                           
                         
                         <div class="row">
                             <div class="col-md-2">
                                <label for="field-1" class="control-label">Transferir:</label>
                             </div>
                             <div class="col-md-5">
                                            <div class="form-group">
                                              <strong> DE </strong><select name="tecnicoDE" id="tecnicoDE" class="form-control input-sm">
                                                    <option value="0">Selecione </option>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
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
                                <div class="col-md-5">
                                            <div class="form-group">
                                            <strong> PARA</strong>  <select name="tecnicoPARA" id="tecnicoPARA" class="form-control input-sm">
                                                    <option value="0">Selecione </option>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
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
                          
                         <div class="row">
                                <div class="col-md-2">
                                     
                                    </div>
                                    <div class="col-md-6" id="result-arquivo">
                                        <button class="btn btn-warning waves-effect waves-light"  onclick="_transferir()"> <i class="fa fa-random"></i> Transferir Roteiro</button>
                                    </div>
                                </div>
                         </div>
                         <div class="row">                          
                                    <div class="col-md-12" id="_rettransferir">
                                       
                                    </div>                           
                         </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                          
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

        <form id="formResumo" name="formResumo" action="javascript:void(0)">
            <div id="modalresumo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Resumo</h4>
                        </div>
                        <div class="modal-body" id="_ResumoPrevisto">
                            ...
                           
                         </div>
                         <div class="row">                          
                                    <div class="col-md-12" id="_rettransferir">
                                       
                                    </div>                           
                         </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                          
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

        <div id="custom-modal-agendaprevisto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Agendamento Previsto</h4>
                    </div>
                    <div class="modal-body">
                        <form name="form32" id="form32" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                            <div id="result-agendaprevisto">

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

                 $(opAvancado).click(function() {
                    $('#modalavancado').modal('show');

                });


                 $(opResumo).click(function() {
                    $('#modalresumo').modal('show');
                 });

           
                $(_00003).click(function() {

                    var $_keyid = "S00008";
                    if( $("#_opcrel").val() == 2) {
                        $_keyid = "S00008a";

                    }

                    var dados = $("#form2 :input").serializeArray();
                    dados = JSON.stringify(dados);

                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados
                    }, function(result) {

                        $("#resultado").html(result);
                        $('#modalfiltro').modal('hide');

                        $('#datatable-responsive').DataTable( {
                                                            "columnDefs": [ {
                                                            "targets": 'no-sort',
                                                            "orderable": false,
                                                        } ]
                                                    });

                    });

                });
            });


            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }
            
            function _Resumo() {
               
                var $_keyid = "R00002";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 2
                }, function(result) {
                    $("#_ResumoPrevisto").html(result);                
                });

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

            
        function agendaprevista(_idref,_idreftec) {

            $('#chamada').val(_idref);         
            
            var $_keyid = "S00010";
            var dados = $("#form3 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 12
            }, function(result) {
                $('#result-agendaprevisto').html(result);
            });
        }

    
    
 


            function agenda() {

                var $_keyid = "S00010";
                var dados = $("#form3 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 0
                }, function(result) {
                    $('#result-agenda').html(result);
                });
            }

            
                    function _agendamentoSalvar() {

                    var $_keyid = "S00010";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    var dt = $("#dtaberturaSel").val().split("-");
                    dt = dt[2] + "/" + dt[1] + "/" + dt[0];
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 14
                    }, function(result) {
                        $("#dtprevistaViewer").val(dt);
                        $("#dtprevista").val($("#dtaberturaSel").val());
                        $("#tecnico_e").val($("#tecnico_troca").val());

                        $('#_listatendimentoprevistoS').html(result);

                    });

                    }

                    function _agendamentoCancelar() {

                    var $_keyid = "S00010";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);

                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 15
                    }, function(result) {
                        $("#dtprevistaViewer").val();
                        $("#dtprevista").val('00/00/0000');
                        $('#_listatendimentoprevistoS').html(result);

                    });

                    }

               function _listOStec() {

                    var $_keyid = "S00010";
                    var dados = $("#form32 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 13
                    }, function(result) {
                        $('#_listatendimentoprevisto').html(result);
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


            function _000010($_idref) {
                _carregando('#result-os');
              
                $('#custom-modal-os').modal('show');

                var $_keyid = "S00001";

                $('#_chaveid').val($_idref);
                $('#_keyform').val($_keyid);
               
                $("#form1").submit();  


            };

            function _agendarOS() {
                    var $_keyid = "S00028";
                    var dados = $("#form9 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 2
                    }, function(result) {
                        $('#_retagendar').html(result);
                    });

             }

            
            function _transferir() {

                var $_keyid = "S00028";
                var dados = $("#form9 :input").serializeArray();
                dados = JSON.stringify(dados);
                
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                }, function(result) {               
                    $('#_rettransferir').html(result);

                });

                }

                
                function _tabon(_vid) {
                    $("#_opcrel").val(_vid);

                }
            
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
        </script>



</body>

</html>