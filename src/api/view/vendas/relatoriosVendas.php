<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

$consulta = $pdo->query("SELECT extra_A_label, extra_B_label FROM ".$_SESSION['BASE'].".parametro");
$parametro = $consulta->fetch();
$extra_a = $parametro['extra_A_label'];
$extra_b = $parametro['extra_B_label'];
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Relatórios</h4>
                <p class="text-muted page-title-alt">Selecione o tipo de relatório para consulta.</p>
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
                    <form action="javascript:void(0)" name="form-inclui" id="form-inclui" method="post">
                        <div class="row">
                            <div class="form-group col-md-7 col-xs-12">
                                <label for="relatorio-tipo">Relatório:</label>
                                <select name="relatorio-tipo" id="relatorio-tipo" class="form-control" onchange="verificaRelatorio()">
                                    <option value="0">Selecione</option>
                                    <option value="1">Vendas - Detalhado por produto</option>  
                                    <option value="2">Vendas - Detalhado por vendedor</option>  
                                    <option value="4">Vendas - Comissão % vendedor</option>         
                                    <option value="3">Extrato</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="row">
                      
                                 <div class="col-md-1 col-xs-12" id="input-datapor" style="display: none;">
                                            <label for="relatorio-estoque">Pesquisar por:</label>
                                                    <div class="form-group">                                                       
                                                        <Select  class="form-control" id="_ppor"  name="_ppor" >                                                                                                              
                                                        <option value="P">Data Pedido</option>
                                                        <option value="F">Data Pgto</option>
                                                        </Select>
                                                    </div>
                                  </div>  
                                  <div class="col-md-2 col-xs-12" id="input-ordernarpor" style="display: none;">
                                            <label for="relatorio-estoqueordem">Classificar por:</label>
                                                    <div class="form-group">                                                       
                                                        <Select  class="form-control" id="_ordempor"  name="_ordempor" >    
                                                        <option value="n">Número Pedido</option>  
                                                        <option value="az">Valor A a Z</option>                                                                                                     
                                                        <option value="za">Valor Z a B</option>                                                                                                     
                                                        <option value="P">Data Pedido</option>
                                                        <option value="F">Data Pgto</option>
                                                        </Select>
                                                    </div>
                                  </div>     
                                  <div class="form-group col-md-2 col-xs-6" id="input-dataini" style="display: none;">
                                    <label for="relatorio-dataini">Período de:</label>
                                    <input type="date" name="relatorio-dataini" id="relatorio-dataini" class="form-control" value="<?=date("Y-m-d")?>">
                                  </div>
                                 <div class="form-group col-md-2 col-xs-6" id="input-datafim" style="display: none;">
                                    <label for="relatorio-datafim">Até:</label>
                                    <input type="date" name="relatorio-datafim" id="relatorio-datafim" class="form-control" value="<?=date("Y-m-d")?>">
                                 </div>                    
                        </div>
                        
                      
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-12" id="input-situacao" style="display: none;">
                                <label for="relatorio-estoque">Situação :</label>
                                <Select  class="form-control" id="relatorio_situacao"  name="relatorio_situacao" >
                                                        <option value="">Todos</option>
                                                         <option value="99">Atendido Totalmente e Pedido</option>
                                                        <?php  
                                                                $consulta = $pdo->query("SELECT Cod_Situacao,Descricao
                                                                            FROM ". $_SESSION['BASE'] .".situacaopedidovenda 
                                                                            order by Descricao");
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["Cod_Situacao"];?>"><?=($row["Descricao"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                            </div> 
                               <div class="form-group col-md-3 col-xs-12" id="input-caixa" style="display: none;">
                                <label for="relatorio-estoque">Caixa :</label>
                                <Select  class="form-control" id="relatorio_caixa"  name="relatorio_caixa" >
                                                        <option value="">Todos</option>
                                                      
                                                        <?php  
                                                                $sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero where ind_caixa = '1' ";
                                                        $stmcx = $pdo->prepare($sql);
                                                        $stmcx->execute();
                                                        $totalcaixaativo = $stmcx->rowCount();
                                                        if($totalcaixaativo > 1){
                                                                  	foreach($stmcx->fetchAll(PDO::FETCH_ASSOC) as $value){
                                                                        ?><option value="<?=$value["Livro_Numero"];?>"><?=($value["Descricao"]);?></option><?php
                                                                    }
                                                                }
                                                                  
                                                            ?>  
                                </Select>
                            </div>
                            <div class="form-group col-md-3 col-xs-12" id="input-vendedor" style="display: none;">
                                <label for="relatorio_vendedor">Vendedor :</label>
                                <Select  class="form-control" id="relatorio_vendedor"  name="relatorio_vendedor" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico <> '1' ORDER BY usuario_APELIDO ";
                                                                $consulta = $pdo->query($sql);
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["usuario_CODIGOUSUARIO"];?>"><?=($row["usuario_APELIDO"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                            </div>  
                                                  
                        </div>
                        <div class="row">
                      
                      <div class="col-md-3 col-xs-12" id="input-filtrarpor" style="display: none;">
                                 <label for="relatorio-estoque">Filtrar:</label>
                                         <div class="form-group">                                                       
                                             <Select  class="form-control" id="_filtrarpor"  name="_filtrarpor"  onchange="verificaCancelado(this.value)">                                                                                                              
                                             <option value=""></option>
                                             <option value="1">Pedido Cancelado</option>
                                             </Select>
                                         </div>
                       </div>  
                      
                       <div class="form-group col-md-2 col-xs-6" id="input-datainicancel" style="display: none;">
                         <label for="relatorio-dataini">Período Cancelado de:</label>
                         <input type="date" name="relatorio-datainicancel" id="relatorio-datainicancel" class="form-control" value="<?=date("Y-m-d")?>">
                     </div>
                 <div class="form-group col-md-2 col-xs-6" id="input-datafimcancel" style="display: none;">
                     <label for="relatorio-datafim">Até:</label>
                     <input type="date" name="relatorio-datafimcancel" id="relatorio-datafimcancel" class="form-control" value="<?=date("Y-m-d")?>">
                 </div>                    
             </div>
                       
                        
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-12" id="input-empresa" style="display: none;">
                                <label >Empresa :</label>
                                <Select  class="form-control" id="relatorio_empresa"  name="relatorio_empresa" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "Select empresa_id,empresa_nome from " . $_SESSION['BASE'] . ".empresa";
                                                                $consulta = $pdo->query($sql);
                                                                $retornoEmp = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                            
                                                                foreach ($retornoEmp as $rowEmp) {
                                                              ?>
                                                              <option value="<?=$rowEmp->empresa_id;?>"><?=$rowEmp->empresa_nome;?></option>        
                                                               <?php
                                                              } ?>
                                                          
                                </Select>
                            </div>                      
                        </div>
                   
                        <?php /*
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-6" id="input-empresa" style="display: none;">
                                <label>Empresa:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa order by empresa_nome");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-empresa" id="relatorio-empresa" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['empresa_id']?>"><?=$row['empresa_nome']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                      
                        </div>
                        */?>
                        <div class="row">
                             <div class="form-group col-md-3 col-xs-6 m-t-26">
                                <input type="hidden" name="relatorio-tabela" id="relatorio-tabela">
                                <button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5" onclick="imprime()"><span class="btn-label"><i class="fa fa-print"></i></span>Imprimir</button>
                              </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Relatório -->
<div id="custom-modal-relatorio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Imprime -->
<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="tablea-impressa"></div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="id-busca" name="id-busca" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">
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
<script src="assets/js/printThis.js"></script>


<script type="text/javascript">

    function _fechar() {
        var $_keyid = "_Nv00003";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACRLTS";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                result = jQuery.parseJSON( result );
                
                $("#produto-etq").val(result.produto);
                $('#valor-etq').val(result.promocao);

            });
        
        setTimeout(() => {
            if ($("#produto-etq").val() == "Produto não encontrado") {
            $('#valida-etq').val('0');
            }
            else {
                $('#valida-etq').val('1');
            }
        }, 900);
    }

   

    function imprime() {        
        var $_keyid = "_Vc00022";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#tablea-impressa").html(result);
                $("#tablea-impressa").printThis();
            });
       
    }

    function verificaCancelado(idvalor) {
       
        var relatorio = $('#relatorio-tipo').val();
        $('#input-datainicancel').hide();
        $('#input-datafimcancel').hide();    
        switch (relatorio) {
            case "1":
              
                break;
           
            case "2":
                if(idvalor != ""){
                    $('#input-datainicancel').show();
                    $('#input-datafimcancel').show();     
                }
                            
              
               
                break;
            case "3":
             
               
                break;
            default:
                break;
        }

    }

    function verificaRelatorio() {
        var relatorio = $('#relatorio-tipo').val();
     
        
        $('#input-datapor').hide();
        $('#input-filtrarpor').hide();
       
        $('#input-ordernarpor').hide();        
        $('#input-dataini').hide();
        $('#input-datafim').hide();
        $('#input-datainicancel').hide();
        $('#input-datafimcancel').hide();            
        $('#input-situacao').hide(); 
         $('#input-caixa').hide(); 
        $('#input-vendedor').hide(); 
        $('#input-empresa').hide(); 
        switch (relatorio) {
            case "1":
                $('#input-datapor').show();
                $('#input-ordernarpor').show(); 
                $('#input-dataini').show();
                $('#input-datafim').show();             
                $('#input-situacao').show(); 
                $('#input-filtrarpor').show();
                  $('#input-caixa').show();
                break;
           
            case "2":
                $('#input-datapor').show();
                $('#input-filtrarpor').show();
                $('#input-ordernarpor').show(); 
                $('#input-dataini').show();
                $('#input-datafim').show(); 
                           
                $('#input-situacao').show(); 
                $('#input-vendedor').show(); 
                $('#input-empresa').show(); 
               
                break;
            case "3":
                $('#input-datapor').show();
                $('#input-ordernarpor').show(); 
                $('#input-dataini').show();
                $('#input-datafim').show(); 
                $('#input-situacao').show(); 
                $('#input-vendedor').show();          
                        $('#input-filtrarpor').show(); 
                $('#input-empresa').show(); 
               
                break;
            case "4":
                $('#input-datapor').show();
                $('#input-filtrarpor').show();
                $('#input-ordernarpor').show(); 
                $('#input-dataini').show();
                $('#input-datafim').show(); 
                           
                $('#input-situacao').show(); 
                $('#input-vendedor').show(); 
                $('#input-empresa').show(); 
               
                break;
            default:
                break;
        }
    }

    function aguarde(id) {
        $(id).html('' +
            '<div class="modal-dialog">' +
                '<div class="modal-content text-center">' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                    '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
                    '</div>' +
                '</div>' +
            '</div>');
    }
</script>
</body>
</html>