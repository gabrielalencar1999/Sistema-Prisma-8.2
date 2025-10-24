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
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <form action="javascript:void(0)" name="form-inclui" id="form-inclui" method="post">
                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <label for="relatorio-tipo">Relatório:</label>
                                <select name="relatorio-tipo" id="relatorio-tipo" class="form-control" onchange="verificaRelatorio()">
                                    <option value="0">Selecione</option>
                                    <option value="1">DRE- Sintético</option>     
                                    <option value="2">Relação Títulos</option> 
                                    <option value="3">Extrato Movimentação</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2 col-xs-3" id="input-dataini" style="display: none;">
                                <label for="relatorio-dataini">Período de:</label>
                                <input type="date" name="relatorio-dataini" id="relatorio-dataini" class="form-control" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="form-group col-md-2 col-xs-3" id="input-datafim" style="display: none;">
                                <label for="relatorio-datafim">Até:</label>
                                <input type="date" name="relatorio-datafim" id="relatorio-datafim" class="form-control" value="<?=date("Y-m-d")?>">
                            </div>
                        
                            <div class="form-group col-md-2 col-xs-6" id="input-filtro" style="display: none;">
                                <label for="relatorio-estoque">Filtrar por:</label>
                                <select name="relatorio-datafiltro" id="relatorio-datafiltro" class="form-control">                                                                    
                                    <option value="1">Vencimento</option>
                                    <option value="2">Pagamento</option>
                                    <option value="3">Emissão</option>
                                </select>
                            </div>
                        </div>
                       
                     
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-6" id="input-grupodespesa" style="display: none;">
                                <label>Categoria:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".categoria  order by descricao_categoria");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-grupodespesa" id="relatorio-grupodespesa" class="form-control" onchange="_buscaSelect(this.value, '#input-subdespesa')">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['id_categoria']?>"><?=$row['descricao_categoria']?></option>
                                    <?php endforeach; ?>
                                </select>
                                </div>   
                                <div class="form-group col-md-3 col-xs-6" id="input-subdespesa" style="display: none;">
                                <label>SubCategoria </label>
                            
                                <select name="nf-contdespesa" id="nf-contdespesa" class="form-control">
                                    <option value="0">Todos</option>
                                   
                                </select>
                            </div>                      
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2 col-xs-6" id="input-situacao" style="display: none;">
                                <label for="relatorio-estoque">Situação Financeiro:</label>
                                <select name="relatorio-situacao" id="relatorio-situacao" class="form-control">
                                    <option value="0">Todas</option>                                    
                                    <option value="1">Aberto</option>
                                    <option value="2">Liquidado</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 col-xs-6" id="input-tipopgto" style="display: none;">
                                <label>Tipo Pgto :</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".tiporecebimpgto  order by nome");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-tipopgto" id="relatorio-tipopgto" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['id']?>"><?=$row['nome']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>   
                             <div class="form-group col-md-2 col-xs-12" id="input-vendedor" style="display: none;">
                                <label for="relatorio_vendedor">Login :</label>
                                <Select  class="form-control" id="relatorio_vendedor"  name="relatorio_vendedor" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where  usuario_ATIVO = 'Sim' ORDER BY usuario_APELIDO ";
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
                        <div class="form-group col-md-2 col-xs-6" id="input-tipofinanceiro" style="display: none;">
                                <label >Tipo Lançamento</label>
                                <select name="relatorio-tipofinanceiro" id="relatorio-tipofinanceiro" class="form-control">
                                    <option value="9">Ambos</option>                                    
                                    <option value="0">Receita</option>
                                    <option value="1">Despesa</option>
                                </select>
                            </div> 
                              <div class="form-group col-md-2 col-xs-6" id="input-conta" style="display: none;">
                                <label>Conta Bancária:</label>
                                <?php
                                $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".livro_caixa_numero order by Descricao");
                                $retorno = $consulta->fetchAll();
                                ?>
                                    <select name="relatorio-conta" id="relatorio-conta" class="form-control">
                                        <option value="0">Todos</option>
                                        <?php foreach ($retorno as $row): ?>
                                            <option value="<?=$row['Livro_Numero']?>"><?=$row['Descricao']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                            <div class="form-group col-md-2 col-xs-6" id="input-empresa" style="display: none;">
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
                            <div class="form-group col-md-2 col-xs-6" id="input-caixa" style="display: none;">
                                <label>Entrada/Saida Caixa:</label>                             
                                <select name="relatorio-caixa" id="relatorio-caixa" class="form-control">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>     
                                               
                        </div>
                        
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
    <input type="hidden" id="id-filtro" name="id-filtro" value="">
   
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
        var $_keyid = "_Na00006";
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

    function _buscaSelect(id, retorno) {
        $("#id-filtro").val(id);
    
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $(retorno).html(result);
            });
    }

   

    function imprime() {        
        var $_keyid = "_Fl00021";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#tablea-impressa").html(result);
                $("#tablea-impressa").printThis();
            });
        
    }

    function verificaRelatorio() {
        var relatorio = $('#relatorio-tipo').val();
     
        $('#input-dataini').hide();
        $('#input-datafim').hide();  
        $('#input-filtro').hide(); 
        $('#input-tipo').hide();  
        $('#input-situacao').hide(); 
        $('#input-grupodespesa').hide(); 
        $('#input-subdespesa').hide(); 
        $('#input-tipopgto').hide(); 
        $('#input-vendedor').hide(); 
        
        $('#input-empresa').hide(); 
         
        $('#input-datafiltro').hide(); 
        $('#input-tipofinanceiro').hide(); 
        $('#input-caixa').hide(); 

        $('#input-conta').hide(); 

        switch (relatorio) {
            case "1":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-datafiltro').show();
                $('#input-tipo').show();
                $('#input-filtro').show(); 
                $('#input-situacao').show(); 
                $('#input-tipopgto').show(); 
                $('#input-grupodespesa').show(); 
                $('#input-subdespesa').show(); 
                $('#input-empresa').show(); 
                 $('#input-conta').show(); 
                break;

            case "2":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-datafiltro').show();
                $('#input-tipo').show();
                $('#input-filtro').show(); 
                $('#input-situacao').show(); 
                $('#input-tipopgto').show(); 
                 $('#input-vendedor').show(); 
               // $('#input-grupodespesa').show(); 
               $('#input-tipofinanceiro').show(); 
              
                $('#input-empresa').show(); 
                  $('#input-conta').show(); 
                break;

            case "3":
                   $('#input-vendedor').show(); 
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-datafiltro').show();
                $('#input-tipo').show();
                $('#input-filtro').show(); 
                $('#input-situacao').show();                              
                $('#input-grupodespesa').show(); 
                $('#input-subdespesa').show(); 
                $('#input-empresa').show(); 
                $('#input-caixa').show(); 
                $('#input-conta').show(); 
                break;
           
            case "8":
               
                $('#input-dias').show();
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