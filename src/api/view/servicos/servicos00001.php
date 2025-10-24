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
            <div class="container" style="width:97% ">
                <!-- Page-Title -->
                <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">Ordem de Serviço</h4>
                            <p class="text-muted page-title-alt">Gestão Ordem Serviço</p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">                               
                                <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                                <button class="btn btn-inverse waves-effect waves-light" onclick="_print()"><span class="btn-label btn-label"> <i class="fa fa-print"></i></span>Imprimir</button>
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                        </div>
                        </div>             
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">
                        <?php
                              $_parametros = array();
                              require_once('../../api/view/servicos/servicos00001_list.php'); ?>
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
                                                        <input type="text" class="form-control input-sm" id="_os"  name="_os">
                                                    </div>
                                                </div>   
                                                <div class="col-md-1">
                                                     <label for="field-1" class="control-label">Tipo</label>
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
                                                               <option value="<?=$idgarantia;?>" ><?=$descgarantia;?></option>
                                                           <?php
                                                       }
                                                       ?>
                                                   </select>
                                                    </div>
                                                    </div>                                                
                                            </div>
                                           
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Assessor</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="tecnico_e" id="tecnico_e" class="form-control input-sm" >
                                                            <option value=""> </option> 
                                                                    <?php
                                                                        $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' ORDER BY usuario_APELIDO ");
                                                                        $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
                                                                        $TotalReg = mysqli_num_rows($result);
                                                                        $codigoTec = $rst["Cod_Tecnico_Execucao"];

                                                                     while($resultado = mysqli_fetch_array($result))
                                                                            {
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
                                                <div class="col-md-1">
                                                     <label for="field-1" class="control-label">Atend</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="atendente" id="atendente" class="form-control input-sm" >
                                                            <option value=""> </option> 
                                                                    <?php
                                                                        $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico <> '1' and usuario_ATIVO = 'Sim' ORDER BY usuario_APELIDO ");
                                                                        $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
                                                                       
                                                                       

                                                                     while($resultado = mysqli_fetch_array($result))
                                                                            {
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
                                                                                            
                                            </div>
                                            <div class="row">
                                <div class="col-md-2">
                                    <label for="field-1" class="control-label">Usuário Preventivo</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="preventivo" id="preventivo" class="form-control input-sm">
                                            <option value=""> </option>
                                            <?php
                                            $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico <> '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
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
                                <div class="col-md-1">
                                    <label for="field-1" class="control-label">Marca</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                         <select name="marca" id="marca" class="form-control input-sm">
                                             <option value=""></option>
                                                <?php
												
                                                $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante where for_Tipo = 1 ORDER BY NOME");
                                                $retorno = $consulta->fetchAll();
                                                foreach ($retorno as $row) {
                                                    ?><option value="<?=$row["NOME"]?>"><?=$row["NOME"]?></option><?php
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
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                    <?php
                                                        $querySit = ("SELECT * FROM situacaoos_elx order by DESCRICAO");
                                                        $resultSit = mysqli_query($mysqli,$querySit)  or die(mysqli_error($mysqli));
                                                        $TotalRegSit = mysqli_num_rows($resultSit);
                                                        ?>
                                                        <select name="situacao" id="situacao"  onchange="sit(this.value)" class="form-control input-sm">                                                            
                                                            <option value="">Todos</option> 
                                                             <option value="997">*Ativos*</strong></option>          
                                                             <option value="998">*Finalizados*</option>          
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
                                                
                                                <div class="col-md-1">
                                                        <label for="field-1" class="control-label">Dias Limite</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                             <input type="number" class="form-control"  name="limiteencerramento"  id="limiteencerramento" value="" placeholder="Inicio">                                                             
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                             <input type="number" class="form-control"  name="limiteencerramentofim"  id="limiteencerramentofim" value="" placeholder="Fim">                                                             
                                                        </div>
                                                    </div>
                                            </div>
                                           
                                        </div>
                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form>


                            <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            
                          
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
                                var $_keyid =   "S00003";    												
								var dados = $("#form2 :input").serializeArray();
								dados = JSON.stringify(dados);		
                             
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									
                             	                                 					
                                    $("#resultado").html(result);                                  
                                    $('#modalfiltro').modal('hide');                                                                
                                    $('#datatable-responsive').DataTable();


                                });

                    });


         
            });

            function _print() {
                var $_keyid = "S00913";
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
         

               $('#datatable-responsive').DataTable();
            


          
</script>    



    </body>
</html>