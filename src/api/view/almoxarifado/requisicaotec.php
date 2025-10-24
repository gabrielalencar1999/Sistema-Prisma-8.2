<?php
  use Database\MySQL;
  use Functions\Vendas;
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

$_linkreq = "RQESTtec";

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
            <div class="container">


                <!-- Page-Title -->
                <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">Requisição</h4>
                            <p class="text-muted page-title-alt">Consulta de Requisicao</p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">
                               
                                <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                                <?php if($_SESSION['per231'] == '231') { 
                                   $_linkreq = "RQEST";
                                   ?>
                                    <button  class="btn btn-success  waves-effect waves-light"  aria-expanded="false" id="_100002" ><span class="btn-label btn-label"> <i class="fa  fa-user-plus"></i></span>Nova</button> 
                                    <?php } ?>
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                             </div>
                        </div>
             
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                        <?php
                             
                                require_once('../../api/view/almoxarifado/requisicao_listtec.php');
                             
                               ?>
                        </div>
                    </div>
                </div>

            </div> <!-- end container -->
        </div>
        
  
    
                       <form id="form2" name="form2">                      
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
                                                     <label for="field-1" class="control-label">Nº Controle</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_pedido"  name="_pedido">
                                                     
                                                    </div>
                                                </div>                                              
                                            </div>
                                         
                                         
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Status</label>
                                                </div>   
                                                <div class="col-md-7">
                                                    <div class="form-group">                                                       
                                                        <Select  class="form-control" id="_situacao"  name="_situacao" >
                                                        <option value="">Todos</option>
                                                        <?php                                                             
                                                            $consulta = $pdo->query("SELECT *
                                                                        FROM " . $_SESSION['BASE'] . ".situacaorequisicao 
                                                                        ORDER BY sitreq_descricao");
                                                            $result = $consulta->fetchAll();
                                                                foreach ($result as $row) {
                                                                    ?><option value="<?=$row["sitreq_id"];?>"><?=$row["sitreq_descricao"];?></option><?php
                                                                }
                                                             
                                                        ?>  
                                                        </Select>
                                                    </div>
                                                </div>                                              
                                            </div>
                                           
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                            <button type="button" id="00003" onclick="_00003()" class="btn btn-info waves-effect waves-light">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form>
                            <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            
                          
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

<script type="text/javascript">
            $(document).ready(function () {
                $('#_chaveid').val("");   
                   $(_100002).click(function(){                      
                                var $_keyid =   "RQEST";  
							    $('#_keyform').val($_keyid);
                                $('#form1').submit();
                    });                                
                                 
            });

            function _00003() {   
                
                var $_keyid = "RE0001_LISTAtec";
                var dados = $("#form2 :input").serializeArray();
                dados = JSON.stringify(dados);
            
                aguardeListagem('#resultado');
              
                $.post("page_return.php", {_keyform:$_keyid, dados},
                    function(result){
                  
                        $("#resultado").html(result);
                        $('#datatable-responsive').DataTable();
                    });   
                    
                    
         }


            function _000010(_idreq) {   
             
                    var $_keyid =   "<?=$_linkreq;?>"; 
                    $('#_keyform').val($_keyid);  
                    var permissao = "6";                  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error','top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                        }else{
                            $('#_chaveid').val(_idreq);   
                            $("#form1").submit();  
                        }								  
					});	             
                }

            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

            
    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
            </script> 
            <script>   

               $('#datatable-responsive').DataTable();
           
      


          
</script>    



    </body>
</html>