<?php //include("../../api/config/iconexao.php");   
include("../../api/config/iconexao2.php");   
?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php
require_once('navigatorbar.php');
use Database\MySQL;
$pdo = MySQL::acessabd();


$_nf = $_POST["id-nota"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');

$data      = $ano . "-" . $mes . "-" . $dia;
$data_atual      = $dia . "/" . $mes . "/" . $ano;
$hora = date("H:i:s");
$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;


function mascara($_texto, $_tipo)
{
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	$_texto =    str_replace("NULL", "", $_texto);

	if ($_tipo == "telefone" and $_texto != "") {
	
		if (strlen($_texto) > 10) {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 5) . "-" . substr($_texto, 7, 4);
		} else {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 4) . "-" . substr($_texto, 6, 4);
		}
	}

	return $_texto;
}


    $SQL = "SELECT  nfed_cancelada,nfed_codpgto,nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente, DATE_FORMAT(nfed_data,'%d/%m/%Y') AS DT,
    nfed_totalnota,nfed_frete, nfed_empresa,nfed_finalizade,nfed_operacao,nfed_tipocontribuinte,nfed_tranportadora,nfed_modalidade,
    nfed_operacao,nfed_finalizade,nfed_tipodocumento,
    nfed_qtde,nfed_qtdevolume,nfed_especie,nfed_marca,	nfed_numerovolume,nfed_bruto,nfed_liquido,
    nfed_textofatura,nfed_informacaoAdicionais,nfed_motivo,nfed_chavedev1,nfed_chave,nfed_protocolo,nfed_serie,nfed_cfop,nfed_url,nfed_arquivo,    
    nfed_cfopid,nfed_basecalculo,nfed_abatimentoIptu,nfed_valorISS,nfed_aliquotaISS,nfed_valorISSretido,nfed_totaldesconto 
    FROM ".$_SESSION['BASE'].".NFE_DADOS   
    WHERE nfed_id = '$_nf' and nfed_modelo = '58'";

        $statement = $pdo->query("$SQL");
        $retornoNF = $statement->fetch();

        $documentoREF = $retornoNF["nfed_tipodocumento"];
   
        $id=  $retornoNF["nfed_id"];
        $NUMERONF = $retornoNF["nfed_numeronf"];
        
       
        $empresa_serie  = $retornoNF["nfed_serie"];

           $nfed_EMPRESA = $retornoNF["nfed_empresa"];
            $nfed_MODELO    = $retornoNF["nfed_modelo"];
        
        $nfe_chave = $retornoNF["nfed_chave"];
        $nfe_protocolo = $retornoNF["nfed_protocolo"];
        $nfe_url = $retornoNF["nfed_url"];
        $nfe_arquivo = $retornoNF["nfed_arquivo"];

 
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">MDFe Nº <?=$NUMERONF;?> 
                <?php  if($empresa_serie != "0" and $NUMERONF > 0) { echo '<span class="badge badge-inverse">Série '.$empresa_serie.'</span>';} ?>
                 </h4>
                <p class="text-muted page-title-alt">Manifesto </p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">  
                    <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
          <form action="javascript:void(0)" id="form-nota" name="form-nota" method="post">
          <input type="hidden" id="vlrmanualprod" name="vlrmanualprod" value="0"> 
          <input type="hidden" id="idmanualprod" name="idmanualprod" value=""> 
            <div class="panel panel-color panel-custom">
                <div class="card-box table-responsive">
                    <div class="panel-body">   
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados</a>
                            </li>
                        
                             
                                   
                            <li class="">
                                <a href="#navpills-61" data-toggle="tab" aria-expanded="false" onclick="_Outros();">Outros</a>
                            </li>
                         
                        </ul>                   
                            <div class="tab-content br-n pn">
                                <!-- Dados da NF -->
                                <div id="navpills-11" class="tab-pane active">                                  
                                      
                                        <div class="card-box" >
                                              <div class="row">
                                                 <div class="form-group col-xs-12" >                  
                                                <label class="control-label " for="nf-fornecedornome">Status:</label> Emitido
                                                </div>
                                               
                                            </div>
                                            <div class="row">
                                                 <div class="form-group col-xs-6" >                  
                                                <label class="control-label " for="nf-fornecedornome">Chave:</label><?=$retornoNF["nfed_chave"]?>                        
                                                </div>
                                                <div class="form-group col-xs-6" >                  
                                                    <label class="control-label " for="nf-fornecedornome">Protocolo:</label>       <?=$retornoNF["nfed_protocolo"]?>                                    
                                                </div>
                                            </div>
                                         
                                        </div>
                                               <div class="form-group col-md-12" id="divretbutton">
                                                    <div class="row">
                                                 <div class="form-group col-xs-12" style="text-align:center;">
                                                    <a href="<?=$nfe_arquivo;?>" target="_blank"><button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  ><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir MDF-e</button></a>
                                                     <a href="baixar.php?id=<?=$nfe_chave;?>" target="_blank"><button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00044" ><span class="btn-label btn-label"> <i class="fa   fa-download"></i></span>Download Xml</button></a>                                                                                            
                                                </div>
                                               
                                                
                                            </div>
                                        
           </div>

                                </div>
                               
                              <!-- Outros -->
                                <div id="navpills-61" class="tab-pane">
                                    <div class="row" id="resumo-outros">
                                        <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                <label class="control-label " >Justificativa do Cancelamento</label>
                                                <input id="nf-motivo" name="nf-motivo" type="text" class="form-control" value="<?=$retornoNF["nfed_motEcarta"]?>"> 
                                         </div>
                                       
                                         <div class="form-group col-md-12"  style="padding-left: 10px;">
                                         <?php if($cancelada == 1) { 
                                                //NÃO VISUALIZA
                                         }else{
                                           // if($retornoNF["nfed_protocolo"] !='') { 
                                            ?>
                                            <button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00carta"   onclick="_EncerrarMDFE();"><span class="btn-label btn-label"> <i class="fa fa-wpforms"></i></span>Encerrar</button>
                                            <button type="button"  class="btn btn-danger  waves-effect waves-light" aria-expanded="false" id="_bt00cancel"  onclick="_CancelarMDFE();"><span class="btn-label btn-label"> <i class="fa fa-ban"></i></span>Cancelar </button>
                                            <?php
                                            //}
                                         }
                                         ?>
                                         
                                         </div>
                                         
                                         
                                    </div>
                                      <div id="result-cancelarnf">
                                                </div>
                                    
                               </div>
                                    
                               </div>
                            
                              
                    </div>
                </div>
            </div>
        
            </form>
        </div>
    </div>
</div>





<!-- Modal INUTLIZAR -->
<div id="custom-modal-inutilizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog ">
    <div class="modal-content " id="result-inutilizar" style="text-align: center;">
           Aguarde Enviando Informação
            </div>
            </div>
    </div>
</div>
<!-- Modal VALIDA -->
<div id="custom-modal-resumo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" >
                <div class="modal-content text-center">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" data-toggle="modal"  aria-hidden="true">×</button>
                 
                </div>
                    <div class="modal-body" id="modal-resumo">
                        aguarde ....
                    </div>
                </div>
        </div>        
</div>

<!-- Modal  -->
<div id="custom-modal-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" >
                <div class="modal-content text-center">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" data-toggle="modal"  aria-hidden="true">×</button>
                 
                </div>
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="formemail" id="formemail">
                <input type="hidden" id="idnfemail" name="idnfemail" value=""> 
                    <div class="modal-body" id="ret_email">
                       aguarde...
                    </div>
                </form>
                </div>
        </div>        
</div>

<!-- Modal Excluir Produto -->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir o produto? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluir();">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;"></div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="id-nota" name="id-nota" value="<?=$_nf;?>">   
 
    <input type="hidden" id="documentoREF" name="documentoREF" value="<?=$documentoREF;?>">
  
    <input type="hidden" id="id-exclusao" name="id-exclusao">
    <input type="hidden" id="id-empresa" name="id-empresa" value="<?=$nfed_EMPRESA?>">   
    <input type="hidden" id="xchave" name="xchave" value="<?=$retornoNF["nfed_chave"]?>">   
    <input type="hidden" id="xJust" name="xJust" value="">   
    <input type="hidden" id="xEvento" name="xEvento" value="">  
    <input type="hidden" id="xnProt" name="xnProt" value="<?=$retornoNF["nfed_protocolo"]?>"> 
    <input type="hidden" id="xnSerie" name="xnSerie" value="<?=$retornoNF["nfed_serie"]?>">  
    <input type="hidden" id="xnNF" name="xnNF" value="<?=$retornoNF["nfed_numeronf"]?>">  
    <input type="hidden" id="xmodelo" name="xmodelo" value="<?=$nfed_MODELO?>">  

 
    
  
   
</form>


<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form6" id="form6">
    <div id="custom-width-cli" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: none;">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cadastro Cliente 
                        <input type="hidden" id="_idcliente" name="_idcliente" value="<?=$_cliente; ?>"></h4>
                </div>
                <div id="_newclinew">
                </div>
                <div id="_newclinewAiso">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>

<!-- Modal Fatura -->
<div id="custom-modal-fatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left" id="modal-fatura"></div>
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
<script src="assets/js/jquery.realmask.js"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- Counter Up  -->
<script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
<script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<script src="assets/js/printThis.js"></script>

<script type="text/javascript">


    function _fechar() {
        var $_keyid = "NTFCE";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

     

          function fecharModal(){
            $('#custom-modal-resumo').modal('hide');

           
          }

          function fecharModalC(){
        
            $('#custom-modal-cancelar').modal('hide');
          }

       
      function _imprimirnf(_link) {
      //  var $_keyid = "_NTFCECLIENTE_00010";
      //  var dados = $("#form-nota :input").serializeArray();
      //  dados = JSON.stringify(dados);
        document.getElementById('form1').action = _link;    
        $('#form1').attr('target', '_blank');
        $("#form1").submit();
        document.getElementById('form1').action = '';
        document.getElementById('form1').target=""
       


    }

    



    function _CancelarMDFE() { 
      
        $('#xJust').val($('#nf-motivo').val());
        
        var $_keyid = "_NTFCECLIENTE_00100";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
         
         aguardeListagem('#result-cancelarnf');
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 16}, function(result){                   
                $('#result-cancelarnf').html(result);                   
            });   
             

    }
    function _EncerrarMDFE() { 
  
        var $_keyid = "_NTFCECLIENTE_00100";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
            
         aguardeListagem('#result-cancelarnf');
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 17}, function(result){      
           
                $('#result-cancelarnf').html(result);                 
            });   
    }
           


    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="50" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="50" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
    function aguardeEmail(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="50" alt="imagem de carregamento, aguarde.">' +
                '<h3 class="text-center">Aguarde, estamos preparando envio</h3>'+
            '</div>');
    }
</script>

</body>
</html>