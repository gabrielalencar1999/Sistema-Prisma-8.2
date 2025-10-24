<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
  <!-- Plugins css-->
 
       
      
        <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
       
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();


?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Importar O.S com numero Fabricante Garantia</h4>
                <p class="text-muted page-title-alt">Selecione o arquivo CSV</p>
            </div>
                <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">                               
                              
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                        </div>
                        </div> 
        </div>
    

        <div class="row">
            <div class="panel panel-color panel-custom">
                   <div class="card-box">
                   <div class="row" >
                            <div class="col-sm-6"> 
             
                    <form action="javascript:void(0)" id="form2" name="form2" method="POST" enctype="multipart/form-data"> 
                        Colunas para CSV 
                            <h4 > <code>svo</code> 
                            <code>assessor</code> 
                            <code>nome</code> 
                            <code>produto</code> 
                            <code>marca</code> 
                            <code>modelocomercial</code> 
                            <code>cpf</code> 
                            <code>telefone</code> 
                            <code>cep</code> 
                            <code>rua</code> 
                            <code>numero</code><code> complemento </code> </h4> 
                           
             
                           <div class="row">
                            <div class="col-sm-6">       
                                    <div class="form-group">
                                                <label class="control-label">Selecione o CSV:</label>
                                        <input type="file" class="filestyle btn btn-default" name="csv" id="csv" accept="text/csv" data-placeholder="Sem arquivos">
                                      
                                    </div>
                            </div>
                                <div class="col-sm-3" style="margin-top: 25px;">   
                                    <button type="button" name="btnEnviar" id="btnEnviar" class="btn btn-info waves-effect waves-light"  onclick="_enviar();">Importar</button>   
                                </div>
                           </div>
                <div class="form-group" id="ret"></div>
                            <h5 class="text-danger">**Importante que arquivo esteja formato csv UTF-8**</h5>
                                            
                        </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <form  id="form1" name="form1" method="post" action="">
                                <input type="hidden" id="_keyform" name="_keyform"  value="">
                                <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                                <input type="hidden" id="_idcli" name="_idcli"  value="">
                                <input type="hidden" id="_idossel" name="_idossel"  value="">
                                <input type="hidden" id="_dadosequi" name="_dadosequi"  value="">
                                
                                
                               
                            </form>
<!-- Modal Relatório -->
<div id="custom-modal-relatorio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                x
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

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!-- Datatables -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

        
        <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
    
          <!-- Notification js -->
  <script src="assets/plugins/notifyjs/js/notify.js"></script>
  <script src="assets/plugins/notifications/notify-metro.js"></script>  
<script type="text/javascript">

    $(document).ready(function () {

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
        var $_keyid = "_Na00007";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _enviar() {
       // var $_keyid = "OSIMPORT001";
        const botao = document.getElementById('btnEnviar');
        botao.disabled = true; //  Bloqueia o botão
        var form_data = new FormData(document.getElementById("form2"));
        aguarde(ret);
        $.ajax({
            url: 'acaoimportOS.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
               
               $("#ret").html(data);
               botao.disabled = false; //  Desbloqueia o botão
            }
        });
    }


  


    function aguarde(id) {
        $(id).html('' +       
                '<div class="text-center">' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
             
                    '</div>' +
                '</div>' 
          );
    }


</script>
</body>
</html>