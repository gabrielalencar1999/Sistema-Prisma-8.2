<?php 


include("../../api/config/iconexao.php"); 
use Database\MySQL;
$pdo = MySQL::acessabd();


if($_sittela == "") {
    $_sittela = 0;
   
}else{
    $acao = 1;


}



$_sittela = strip_tags(trim($_POST['_sittela']));

$_userAgenda = strip_tags(trim($_POST['_userAgenda']));
$_idAgenda = strip_tags(trim($_POST['_idAgenda']));
$_idConsumidor = strip_tags(trim($_POST['_idConsumidor']));

//-filtro botao fase agenda
    $_colaborador = strip_tags(trim($_POST['_filcol']));
    $_situacaofiltro =  strip_tags(trim($_POST['_filsit']));
    $_dtini = strip_tags(trim($_POST['_fildtini']));
    $_dtfim = strip_tags(trim($_POST['_fildtfim']));

$filtro = "";
$_cssbtfiltro = '';
$_cssbtncompartilhado = 'none';


if($_dtini == "" ) {
    $_AgendaINI = 1;
  //  $_dtini = date('Y-m-d');
  //  $_dtfim = date('Y-m-d');
}
if($_dtini != "" ) {
       $filtro2 = $filtro2." AND inicio >= '$_dtini 00:00' AND termino <= '$_dtfim 23:59'  ";       

}
/*
$datax = explode("-",$_dtini);
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];

$datax = explode("-",$_dtfim);
$data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];
*/
if($_colaborador != "" ) {
    $_FIL = explode(';',$_colaborador);
    if($_FIL[0] != ""){
        $filtro2 = $filtro2." AND id_userAgenda = '$_FIL[0] ' ";       
    }
    if($_FIL[1] != ""){
        $filtro2 = $filtro2." AND id_agenda = '$_FIL[1] ' ";       
    }
    
}

if($_situacaofiltro != "" ) {
    
    $filtro2 = $filtro2." AND ev_status = '$_situacaofiltro' ";       
}
/*
$sql = "SELECT id_evento, titulo, descricao, inicio, termino, cor, fk_id_destinatario, fk_id_remetente, status ,sit_cor
    FROM ".$_SESSION['BASE'].".eventos as e
	LEFT JOIN ".$_SESSION['BASE'].".convites as c ON e.id_evento = c.fk_id_evento
    LEFT JOIN  ".$_SESSION['BASE'].".situacao_agenda ON  ev_status = sit_agendaID
    "; //Where fk_id_usuario = $id_user   WHERE id_emp = ".$_SESSION['BASE_ID']." $filtro  $_filAgenda
   
	$req = $pdo->prepare($sql);
	$req->execute();
	$events = $req->fetchAll();
*/

   
?>

<!DOCTYPE html>
<html>
   <?php require_once('header.php') ?>


    <body>

    <?php require_once('navigatorbar.php')     
    ?>
      <!-- DataTables -->
      <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
   
        <div class="wrapper">
            <div class="container">

               <!-- Page-Title -->
               <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-10">Agenda<br>
                       
                    <?=$xxx;?></h4>
                           
                        </div>
                        <div class="btn-group pull-right m-t-10">
                            <div class="m-b-5">  
                           
                                    <button class="btn btn-warning waves-effect waves-light"  onclick="_pFil()"> <i class="fa fa-refresh" ></i></button>
                                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-atendimento" onclick="agendanew()"> <i class="fa  fa-plus-square"></i> </button>
                                    <!--<button class="btn btn-success waves-effect waves-light"  onclick="_trackmobA()"> <i class="fa fa-taxi" ></i></button>-->
                                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalresumo" onclick="_resumo()"> <i class="fa  fa-database"></i></button>
                                    <span id="_btnfiltro" style="display:<?=$_cssbtfiltro;?>">                
                                     <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                                  
                                     </span>   
                               
                        </div>
                        </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-md-2">
                            <div class="card-box">
                         
                            <input type="hidden" id="_id_ref" name="_id_ref">
                            <div class="table-responsive" id="_retuser">
                            
                            <?php
                             //  print_r($_parametros);xx
                               require_once('../../api/view/atendimento/acaoAgend.php'); ?>
                            </div>
                        </div>
                            </div> <!-- end col-->
                            <div class="col-md-10">
                                <div class="card-box" id="_painel">
                                    
                                    <div class="row"  id="_task">
                                            <table id="datatable-buttons" class="table table-striped table-bordered"  cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th class="text-left">Ação</th>
                                                    <th class="text-left">Assunto</th>
                                                    <th class="text-center">Dt Cadastro</th>
                                                    <th class="text-center">Dt Posicionamento</th>
                                                    <th class="text-center">Prioridade</th>
                                                    <th class="text-center">Refer.</th>
                                                    <th class="text-center" style="width:150px ;">Cliente</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Usuário</th>
                                                </tr>
                                                </thead>
                                                <tbody>                                               
                                                <?php
                                                 require_once('../../api/view/atendimento/acaoAgendListaINI.php'); ?>     
                                                </tbody>
                                            </table>
                                               <?php
                                             
                                              // require_once('../../api/view/atendimento/acaoAgendLista.php'); ?>                                                                                         

                                            </div>

                                            <div id="calendar" name="calendar" style="display:none;"></div>
                                </div>
                            </div> <!-- end col -->
                        </div>  <!-- end row -->
                        
                        <!-- BEGIN MODAL -->
                                            
                        <!-- Modal Adicionar Evento -->
                        <?php //include ('../../api/view/atendimento/evento/modal/modalAdd.php'); ?>
			
			
                        <!-- Modal Editar/Mostrar/Deletar Evento -->
                        <?php //include ('../../api/view/atendimento/evento/modal/modalEdit.php'); ?>
                        <!-- END MODAL -->
                    </div>
                    <!-- end col-12 -->
                </div> <!-- end row -->

                <!-- Modal agenda lista -->
                <div id="custom-modal-agenda" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                                <div class="modal-dialog">
                                        <div class="modal-content">                                        
                                            <form action="javascript:void(0)" id="form-agenda" name="form-agenda" method="post">
                                                <div id="resAgend">                                                
                                                <input type="hidden" id="_idag" name="_idag"  value="">                                                
                                                </div> 
                                            </form>
                                        
                                        </div>
                                    </div>
                </div>
               
                <form id="form1" name="form1" method="post" action="">                 
                    <input type="hidden" id="_keyform" name="_keyform"  value="">
                    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                    <input type="hidden" id="_idref" name="_idref"  value="">
                    <input type="hidden" id="_idcli" name="_idcli"  value="">                    
                    <input type="hidden" id="_dadosequi" name="_dadosequi"  value=""> 
                                      
                 
                </form> 
                
                <form id="formfiltro" name="formfiltro">  
                <input type="hidden" id="_idagenda" name="_idagenda"  value="">                    
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
                                                        <input type="date" class="form-control" name="_dataIni"  id="_dataIni" value="<?=$_dtini;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                 
                                                    <label for="field-1" class="control-label">Até </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                      
                                                        <input type="date" class="form-control"  name="_dataFim"  id="_dataFim" value="<?=$_dtfim;?>">                                                   
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Nº OS</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_os" name="_os">
                                                    </div>
                                                </div>                                              
                                            </div>    
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Nome Cliente</label>
                                                </div>   
                                                <div class="col-md-7">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_nomecliente" name="_nomecliente">
                                                    </div>
                                                </div>                                              
                                            </div>                                           
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Agenda</label>
                                                </div>   
                                                <div class="col-md-7">
                                                    <div class="form-group">   
                                                                 <select class="form-control" name="_agendafiltro" id="_agendafiltro">
                                                                 <option value="">Todos</option>                                                 
                                                    <?php 
                                                                $_sql = "SELECT * FROM ".$_SESSION['BASE'].".agendatab        
                                                                WHERE  ag_ativo = '1'  ";  
                                                                                    
                                                                    $consulta = $pdo->query($_sql);                                        
                                                                
                                                                    $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                                    
                                                                 foreach ($retornoUsuario as $row){ ?>
                                                                        <option value="<?=$row->ag_id?>"<?=$row->ag_id == $_AgendaINI ? "selected" : ""?>><?=$row->ag_nome?></option>
                                                                    <?php } 
                                                    /*
                                                                $_sql = "SELECT usuario_CODIGOUSUARIO,usuario_APELIDO,usuario_background,usuario_avatar
                                                                FROM ".$_SESSION['BASE'].".usuario 
                                                                ";  
                                                                                        
                                                                    $consulta = $pdo->query($_sql);                                        
                                                                
                                                                    $retornoUsuario = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                                    
                                                                    foreach ($retornoUsuario as $row){ ?>
                                                                        <option value="<?=$row->usuario_CODIGOUSUARIO?>;"<?=$row->usuario_CODIGOUSUARIO == $_userAgenda ? "selected" : ""?>><?=$row->usuario_APELIDO?></option>
                                                                    <?php }
                                                                    */
                                                                    ?>
                                                                </select>
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
                                                                $consulta = $pdo->query("SELECT sit_agendaID,sit_agendaDescricao
                                                                            FROM ". $_SESSION['BASE'] .".situacao_agenda  where sit_visualiza = '1'
                                                                            order by sit_agendaDescricao");
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["sit_agendaID"];?>"><?=($row["sit_agendaDescricao"]);?></option><?php
                                                                    }
                                                            ?>  
                                                        </Select>
                                                    </div>
                                                </div>                                              
                                            </div>
                                           
                                        </div>
                                        <div class="modal-footer">
                                        
                                         
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                            <button type="button"  class="btn btn-info waves-effect waves-light" onclick="_pFil()">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form> 

                        <div id="modalresumo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Resumo</h4>
                                        </div>
                                        <div class="modal-body" id="_RETresumo">
                                            <div class="row" >
                                                <div class="col-md-6">
                                                     Sem Resultados
                                                </div>
                                            </div>   
                                                                         
                                         
                                         
                                           
                                        </div>
                                        <div class="modal-footer">
                                        
                                         
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->


            </div> <!-- end container -->
        </div>
        <form  id="form2" name="form2" method="post" action="">
            <input type="hidden" id="_idevento" name="_idevento"  value="">
            
           
            
            
            
        </form>
        <!-- end wrapper -->
 <!-- atendimento -->
                    
  
            <div id="custom-modal-atendimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog modal-lg">
                        <form name="form3" id="form3" action="javascript:void(0)" method="post" enctype="multipart/form-data" >
                        <input type="hidden" id="_refstatus" name="_refstatus"  value="">
                                    <div id="result-acopanhamento" class="result">                 
                                    
                                    </div>
                               </form>                         
                        </div>
                    </div>


        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/routes.js"></script>
        
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        

        <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>

        <!-- BEGIN PAGE SCRIPTS
        <script src="assets/plugins/moment/moment.js"></script>
        <script src='assets/plugins/fullcalendar/js/fullcalendar.min.js'></script>
        <script src="assets/pages/jquery.fullcalendar.js"></script>
         -->
       <!-- FullCalendar -->
       
		<script src='../js/moment.min.js'></script>
		<script src='../js/fullcalendar.min.js'></script>
		<script src='../locale/pt-br.js'></script>
 

<!--datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.colVis.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

        <script src="assets/pages/datatables.init.js?v=1"></script>

        <!-- App core js -->
        <script src="assets/js/jquery.core.js"></script>
		<script src="assets/js/jquery.app.js"></script>
		
      <!-- ladda js -->
	  <script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
      <script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
      <script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>

     <!-- Notification js -->
     <script src="assets/plugins/notifyjs/js/notify.js"></script>
     <script src="assets/plugins/notifications/notify-metro.js"></script>    
		<script>
            
             
            $(document).ready(function () {

                $('#datatable').dataTable();
                $('#datatable-keytable').DataTable({keys: true});
                $('#datatable-responsive').DataTable();
                $('#datatable-colvid').DataTable({
                    "dom": 'C<"clear">lfrtip',
                    "colVis": {
                        "buttonText": "Change columns"
                    }
                });
                $('#datatable-scroller').DataTable({
                    ajax: "assets/plugins/datatables/json/scroller-demo.json",
                    deferRender: true,
                    scrollY: 380,
                    scrollCollapse: true,
                    scroller: true
                });
                var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
                var table = $('#datatable-fixed-col').DataTable({
                    scrollY: "300px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    }
                });
                });
                TableManageButtons.init();

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

                 
                                    function _pDet(_ref) {
                                        $('#_agendafiltro').val(_ref);   
                                       
                                        var $_keyid =   "_ATa00010";    
                                        var dados = $("#formfiltro :input").serializeArray();
                                        dados = JSON.stringify(dados);    
                                        $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 1}, function(result){							                          		                                                    
                                          $('#_task').html(result);   
                                          handleDataTableButtons();                                                                                   
                                    });    
                                     
                                   };

                             

                                   function _pFil() {                                       
                                            
                                            var $_keyid =   "_ATa00010";    
                                            var dados = $("#formfiltro :input").serializeArray();
                                            dados = JSON.stringify(dados);    
                                            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 1}, function(result){							                          		                                                    
                                                $('#_task').html(result);    
                                                $('#modalfiltro').modal('hide');    
                                                handleDataTableButtons();                                                                            
                                        });    
                            
                                                                                                       
                                     };

                        

                                   function _REFRESH() {   
                                        var $_keyid = "_ATa00001";
                                        $('#_keyform').val($_keyid);
                                        $('#form1').submit();
                                   }
                                   

                                   function _atend($_idref) {   
                                                                   
                                       var $_keyid =   "_Vc00010";    
                                       $('#_keyform').val($_keyid);
                                       $('#_chaveid').val($_idref);                                       
                                       $("#form1").submit();    
                                   }

                                   
                                   function  _agendaincluir(){                                                                      
                                        var $_keyid =   "S00010";                                            
                                        var dados = $("#form3 :input").serializeArray();
                                        dados = JSON.stringify(dados);                              
                                        $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 88}, function(result){							                          		                                                    
                                            $('#resultAcompanhamento').html(result);   
                                          //  $('#_retornoinclusao').html(
                                         //       '<div class="alert alert-success alert-dismissable " style="margin:5px;text-align:center ;">Incluído registro</div>'
                                         //   );   
                                         $.Notification.notify('success', 'top right','', ' Incluído novo registro!!!');
                                             _pFil();
                                             $('#custom-modal-atendimento').modal('hide');            
                                           
                                        });  
                                     }

                                   function  agendaedit($_idref){
                                         $('#_idref').val($_idref);                                   
                                        var $_keyid =   "S00010";  
                                          
                                        var dados = $("#form1 :input").serializeArray();
                                        dados = JSON.stringify(dados);                              
                                        $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 5}, function(result){							                          		                                                    
                                            $('#result-acopanhamento').html(result);   
                                                                                                                               
                                        });  
                                     }
                                     
                                   function  agendanew(){
                                                                         
                                        var $_keyid =   "S00010";  
                                          
                                        var dados = $("#formfiltro :input").serializeArray();
                                        dados = JSON.stringify(dados);                              
                                        $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 5}, function(result){							                          		                                                    
                                            $('#result-acopanhamento').html(result);   
                                                                                                                               
                                        });  
                                     }

                                     function   _resumo(){
                                                                         
                                                                         var $_keyid =   "S00010";  
                                                                           
                                                                         var dados = $("#formfiltro :input").serializeArray();
                                                                         dados = JSON.stringify(dados);                              
                                                                         $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 17}, function(result){							                          		                                                    
                                                                             $('#_RETresumo').html(result);   
                                                                                                                                                                
                                                                         });  
                                      }
                                    
                                     
                                     function  _newOSAcaoSel($_dadosequipamento,$_idcli){                        
                                            var $_keyid =   "S00001";                                      
                                            $('#_dadosequi').val($_dadosequipamento) ;       
                                            $('#_idcli').val($_idcli) ;                                                                              
                                            $('#_keyform').val($_keyid);   
                       
                                            document.getElementById('form1').action = '';
                                           // $.post("page_return.php", {_keyform:$_keyid}, function(result){				
                                                            
                                            $("#form1").submit();                                  
                                          //  });    
                                                        
                                        }
         


                                     function _agendafim(ref) {
                                        var $_keyid =   "S00010";      
                                                
                                        $('#_refstatus').val(ref);                                
                                        var dados = $("#form3 :input").serializeArray();
                                        dados = JSON.stringify(dados);                              
                                     //   $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 6}, function(result){							                          		                                                    
                                         
                                         //   if(result == ""){                                                                         
                                              $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 7}, function(result){							                          		                                                    
                                             
                                             //   $('#val_agendamento').html(result);  
                                             $.Notification.notify('success', 'top right','', 'Atualizado com Sucesso !!!');
                                             _pFil();
                                             $('#custom-modal-atendimento').modal('hide');                                                   
                                            });

                                           
                                                                          
                                     }
                             
                            

                                  function _evadd(_idpet) {
                                       // alert("add");
                                       $('#_idConsumidor').val(_idpet); 
                                    //  alert("salvar editar");
                                    var $_keyid =   "_ATa00005";                                                                     
                                      var dados = $("#form1 :input").serializeArray();
                                      dados = JSON.stringify(dados);		
                                          $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 79}, function(result){									
                                           
                                          if(result == "" ) { 
                                          
                                            var $_keyid =   "_ATa00005";                                     
                                            var dados = $("#form1 :input").serializeArray();
                                            dados = JSON.stringify(dados);		
                                                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 13}, function(result){									
                                                $("#return_evento").html(result);   
                                            });  
                                               
                                          }else{
                                                $("#returnAG_aviso").html(result);   
                                            }
                                       });
                                    
                                      
                                   };

                                 

                                   function _evsalvar() {
                                     // alert("salvar");
                                     var $_keyid =   "_ATa00005"; 
                                                                     
                                      var dados = $("#form1 :input").serializeArray();
                                      dados = JSON.stringify(dados);		
                                          $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 78}, function(result){									
                                          
                                          if(result == "") {                                      
                                            var $_keyid =   "_ATa00005";                                     
                                            var dados = $("#form1 :input").serializeArray();
                                            dados = JSON.stringify(dados);		
                                                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7}, function(result){									
                                                $("#return_evento").html(result);   
                                            });
                                            }else{
                                                $("#return_aviso").html(result);   
                                            }
                                            });

                                   
                                      
                                      
                                   };

                                   function _editsalvar() {                                   
                                    //  alert("salvar editar");
                                    var $_keyid =   "_ATa00005";                                                                     
                                      var dados = $("#form-eventedit :input").serializeArray();
                                      dados = JSON.stringify(dados);		
                                          $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 77}, function(result){									
                                         
                                          if(result == "") { 
                                                var $_keyid =   "_ATa00005";                                     
                                                var dados = $("#form-eventedit :input").serializeArray();
                                                dados = JSON.stringify(dados);		
                                                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7}, function(result){									
                                                    $("#_eventoEditar").html(result);   
                                                });
                                      
                                          }else{
                                                $("#return_avisoEdit").html(result);   
                                            }
                                       });

                               
                                   };


                                   function _fecharAgenda() {
                                     // alert("recarregar");
                                      var $_keyid =   "_ATa00001"; 
                                     $('#_keyform').val($_keyid);     
                                     $("#form1").submit();    
                                   };

       
                                
                                       



                                  
                                   function _trackmobA() {                                                                          
                                            $_keyid =   "_ATa00006"; 
                                            $('#_keyform').val($_keyid); 
                                            var permissao = "1";              
                                            $.post("verPermissao.php", {permissao:permissao}, function(result){
                                                if(result != ""){
                                                    $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                                                }else{
                                                    $("#form1").submit();  
                                                }								  
                                            });     
                                    }

                                     function validaForm(erro) {
                                        if(erro.inicio.value>erro.termino.value){
                                           // alert('Data de Inicio deve ser menor ou igual a de termino.');
                                            return false;
                                        }else if(erro.inicio.value==erro.termino.value){
                                        //    alert('Defina um horario de inicio e termino.(24h)');
                                            return false;
                                        }

				                    }

                                    function mascaraTexto(evento, tipo){
                                        if (tipo == 3) { 
                                            mascara = "(99)99999-9999";
                                            document.getElementById('_fone').maxLength = 14;
                                        }
                                        var campo, valor, i, tam, caracter;  
                                        var campo, valor, i, tam, caracter;  
                                        if (document.all) // Internet Explorer  
                                        campo = evento.srcElement;  
                                        else // Nestcape, Mozzila  
                                            campo= evento.target;  
                                            valor = campo.value;  
                                            tam = valor.length;  
                                            for(i=0;i<mascara.length;i++){  
                                            caracter = mascara.charAt(i);  
                                        if(caracter!="9")   
                                            if(i<tam & caracter!=valor.charAt(i))  
                                                campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);  
                                            } 
                                    }
                              
                                    function modalShow() {
                                            $('#modalShow').modal('show');
                                        }
                                        //alert( document.getElementById('_sittela').value);
                                        if(  document.getElementById('_sittela').value == 0){
                                            document.getElementById('calendar').style.display = 'none';                                             
                                                  document.getElementById('_task').style.display = '';
                                        }else{
                                            document.getElementById('calendar').style.display = '';                                             
                                            document.getElementById('_task').style.display = 'none';
                                        }
        </script>  

    </body>
</html>