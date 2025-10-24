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
                            <div class="form-group col-md-6 col-xs-6">
                                <label for="relatorio-tipo">Relatório:</label>
                                <select name="relatorio-tipo" id="relatorio-tipo" class="form-control" onchange="verificaRelatorio()">
                                    <option value="0">Selecione</option>
                                    <option value="5">Resumo Geral - Atendente</option>  
                                    <option value="6">Relatorio Atendimentos</option>  
                                    <option value="7">Resumo Geral - Peças e Serviços </option>  
                                    <option value="3">Comissão Geral %</option>     
                                    <?php  /*-*      
                                                                  
                                    <option value="2">Comissão por Meta</option>    
                                    <option value="1">Peças não Movimentas no Período</option>                              
                                    <option value="4">Curva ABC Aparelhos</option>                                   
                                    */ ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2 col-xs-6" id="input-dataini" style="display: none;">
                                <label for="relatorio-dataini">Período de:</label>
                                <input type="date" name="relatorio-dataini" id="relatorio-dataini" class="form-control" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="form-group col-md-2 col-xs-6" id="input-datafim" style="display: none;">
                                <label for="relatorio-datafim">Até:</label>
                                <input type="date" name="relatorio-datafim" id="relatorio-datafim" class="form-control" value="<?=date("Y-m-d")?>">
                            </div>
                            <div class="form-group col-md-2 col-xs-6" id="input-dtpesquisa" style="display: none;">
                                <label> Data p/ Pesquisa</label>
                                <select name="relatorio-dtpesquisa" id="relatorio-dtpesquisa" class="form-control">
                                       <option value="2" selected>Data Abertura</option>
                                       <option value="1">Data Encerramento</option>                                    
                                     

                                </select>
                            </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-3 col-xs-3" id="input-situacaoMulti" style="display: none;">
                                <label >Situação:</label>
                              
                                <Select  id="relatorio_situacaoMulti"  name="relatorio_situacaoMulti[]"  class="selectpicker" multiple data-style="btn-white" >
                                                      
                                                        <?php  
                                                                $consulta = $pdo->query("SELECT COD_SITUACAO_OS,DESCRICAO
                                                                            FROM ". $_SESSION['BASE'] .".situacaoos_elx 
                                                                            order by DESCRICAO");
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["COD_SITUACAO_OS"];?>"><?=($row["DESCRICAO"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                            </div>
                            <div class="form-group col-md-3 col-xs-3" id="input-situacao" style="display: none;">
                                <label >Situação :</label>
                              
                                <Select  id="relatorio_situacao"  name="relatorio_situacao"   class="form-control" >
                                                <option value="">Todos</option>
                                                        <?php  
                                                                $consulta = $pdo->query("SELECT COD_SITUACAO_OS,DESCRICAO
                                                                            FROM ". $_SESSION['BASE'] .".situacaoos_elx 
                                                                            order by DESCRICAO");
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["COD_SITUACAO_OS"];?>"><?=($row["DESCRICAO"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                            </div>
                            <div class="form-group col-md-3 col-xs-3" id="input-modelo" style="display: none;">
                                <label >Modelo Comercial :</label>
                                <Select  class="form-control" id="relatorio_modelo"  name="relatorio_modelo" >
                                                        <option value="0">Não</option>
                                                        <option value="1">Sim</option>                                                  
                                </Select>
                            </div>                       
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-6" id="input-pecasprodutos" style="display: none;">
                                <label for="relatorio-pecasprodutos">Filtrar Peças/Produtos/Serviços</label>
                                <select name="relatorio-pecasprodutos" id="relatorio-pecasprodutos" class="form-control">
                                       <option value="">Todos</option>  
                                       <option value="0">Peças e Produtos</option>                                    
                                       <option value="1">Serviços</option>
                                       <option value="2">Taxas</option>
                                </select>
                            </div>
                      
                           
                        </div>
                        <div class="row"  id="input-vendedor" style="display: none;">
                            <div class="form-group col-md-3 col-xs-12">
                                <label >Assessor Técnico :</label>
                                <Select  class="form-control" id="relatorio_vendedor"  name="relatorio_vendedor" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico =  '1' and usuario_ATIVO = 'Sim'   ORDER BY usuario_APELIDO ";
                                                                $consulta = $pdo->query($sql);
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["usuario_CODIGOUSUARIO"];?>"><?=($row["usuario_APELIDO"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                                
                            </div>   
                            <div class="form-group col-md-3 col-xs-12" >
                                <label>Almoxarifado O.S:</label>
                                <Select  class="form-control" id="relatorio_vendedoralmox"  name="relatorio_vendedoralmox" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT Codigo_Almox,Descricao   FROM ". $_SESSION['BASE'] .".almoxarifado  where almox_ativo = '1'   ORDER BY Descricao ";
                                                                $consulta = $pdo->query($sql);
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["Codigo_Almox"];?>"><?=($row["Descricao"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                            </div>  
                                           
                        </div>
                      
                        <div class="row" id="input-vendedortec" style="display: none;">
                            <div class="form-group col-md-3 col-xs-12" >
                                <label >Técnico Oficina O.S:</label>
                                <Select  class="form-control" id="relatorio_vendedortec"  name="relatorio_vendedortec" >
                                <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where usuario_perfil2 = '9' and usuario_ATIVO = 'Sim'  ORDER BY usuario_APELIDO ";
                                                                $consulta = $pdo->query($sql);
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["usuario_CODIGOUSUARIO"];?>"><?=($row["usuario_APELIDO"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                                
                            </div>   
                            <div class="form-group col-md-3 col-xs-12">
                                <label >Técnico de Serviços:</label>
                                <Select  class="form-control" id="relatorio_vendedortecS"  name="relatorio_vendedortecS" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico =  '1' and usuario_ATIVO = 'Sim'  or usuario_perfil2 = '9' and usuario_ATIVO = 'Sim'  ORDER BY usuario_APELIDO ";
                                                                $consulta = $pdo->query($sql);
                                                                $result = $consulta->fetchAll();
                                                                    foreach ($result as $row) {
                                                                        ?><option value="<?=$row["usuario_CODIGOUSUARIO"];?>"><?=($row["usuario_APELIDO"]);?></option><?php
                                                                    }
                                                            ?>  
                                </Select>
                            </div>                     
                        </div>
                        <div class="row"  id="input-comissao" style="display: none;">
                        <div class="form-group col-md-2 col-xs-12" >
                                    <label>Filtrar Valor</label>
                                    <Select  class="form-control" id="relatorio_fat"  name="relatorio_fat" >
                                    <option value="">Todos</option>
                                    <option value="1">Faturado</option>
                                    </Select>
                             </div>  
                            <div class="form-group col-md-1 col-xs-12" >
                                    <label> Comissão Peça %:</label>
                                    <input type="text" name="vlrcomissaoPeca" id="vlrcomissaoPeca" class="form-control"  value="" placeholder="00,00">
                             </div>  
                             <div class="form-group col-md-1 col-xs-12" >
                                    <label>Comissão Taxa %:</label>
                                    <input type="text" name="vlrcomissaoTaxa" id="vlrcomissaoTaxa" class="form-control"  value="" placeholder="00,00">
                             </div>  
                             <div class="form-group col-md-2 col-xs-12" >
                                    <label>Comissão Serviço %:</label>
                                    <input type="text" name="vlrcomissaoServico" id="vlrcomissaoServico" class="form-control"  value="" placeholder="00,00">
                             </div>  
                        </div>




















                        <div class="row">
                            <div class="form-group col-md-4 col-xs-6" id="input-atendente" style="display: none;">
                                <label for="relatorio_atendente">Atendente :</label>
                                <Select  class="form-control" id="relatorio_atendente"  name="relatorio_atendente" >
                                                        <option value="">Todos</option>
                                                        <?php  
                                                         $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico <> '1' and  usuario_perfil2 <> '9' and usuario_ATIVO = 'Sim'  ORDER BY usuario_APELIDO ";
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
                            <div class="form-group col-md-4 col-xs-6" id="input-produtos" style="display: none;"> 
                                <label >Filtrar por descrição produto <code>Ex: *refrigerador*purificador*</code> </label>   
                                          <textarea name="descprodutos"  id="descprodutos" rows="2" type="text" class="form-control"  > </textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 col-xs-6" id="input-lista" style="display: none;"> 
                                <label for="relatorio-lista">Lista:</label>
                                <?php
                                $consulta = $pdo->query("SELECT GRUPO_PECAS FROM ".$_SESSION['BASE'].".itemestoque WHERE GRUPO_PECAS <> '' GROUP BY GRUPO_PECAS ORDER BY GRUPO_PECAS");
                                $retorno = $consulta->fetchAll();
                                ?>
                                <select name="relatorio-lista" id="relatorio-lista" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno as $row): ?>
                                        <option value="<?=$row['GRUPO_PECAS']?>"><?=$row['GRUPO_PECAS']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>   
                            <div class="form-group col-md-2 col-xs-6" id="input-detalhado" style="display: none;"> 
                                <label >Custo e Margens:</label>  
                                <input type="hidden" name="relatorio-detalhado" id="relatorio-detalhado" class="form-control">
                                <?php /*                            
                                <select name="relatorio-detalhado" id="relatorio-detalhado" class="form-control">
                                    <option value="1">Não</option>
                                    <option value="2">Sim</option>
                                  
                                </select>
                                */?>
                                <select name="relatorio-custos" id="relatorio-custos" class="form-control">
                                    <option value="1">Não</option>
                                    <option value="2">Sim</option>
                                  
                                </select>
                            </div>                         
                            <div class="form-group col-md-2 col-xs-6" id="input-csv" style="display: none;"> 
                                <label >Tipo:</label>                              
                                <select name="relatorio-arquivo" id="relatorio-arquivo" class="form-control">
                                    <option value="1">Visualizar</option>
                                    <option value="2">Gerar CSV-Excel</option>
                                  
                                </select>
                            </div>
                        
                            <div class="form-group col-md-3 col-xs-6" id="input-dias" style="display: none;">
                                <label for="relatorio-dias">Dias para Vencer:</label>
                                <input type="number" name="relatorio-dias" id="relatorio-dias" class="form-control">
                            </div>
                            <div class="form-group col-md-3 col-xs-6 m-t-26">
                                <input type="hidden" name="relatorio-tabela" id="relatorio-tabela">
                                <button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5" onclick="imprimeModal()"><span class="btn-label"><i class="fa fa-check"></i></span>Gerar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



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

<!-- Modal Imprime -->
<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
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


        
        <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
    
<script type="text/javascript">

    function _fechar() {
        var $_keyid = "_Na00006";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

  


    function imprimeModal() {
        $('#relatorio-tabela').val(0);
       
        var $_keyid = "SERVICORL";
        // Capturar valores do select múltiplo corretamente
        var situacoesSelecionadas = $('#relatorio_situacaoMulti').val(); // Retorna um array

        var dados = $("#form-inclui").serializeArray();
     
        // Adicionar ao array de dados serializados
        dados.push({ name: "relatorio_situacaoMulti", value: situacoesSelecionadas });

        dados = JSON.stringify(dados);
        $("#tablea-impressa").html(''); 
        aguarde("#custom-modal-relatorio");
        $("#custom-modal-relatorio").modal('show');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                var relatorioTipo = $('#relatorio-tipo').val();  
                if($('#relatorio-arquivo').val() == 1 && relatorioTipo == 3  || $('#relatorio-arquivo').val() == 1 || relatorioTipo == 5){
                      $("#tablea-impressa").html(result);  
                       $("#tablea-impressa").printThis();
                       $("#custom-modal-relatorio").modal('hide');
                }else{     
                    $("#custom-modal-relatorio").html(result); 
                      $("#custom-modal-relatorio").modal('show');
                }
                
            });
       // $("#tablea-impressa").printThis();
    }

    function verificaRelatorio() {
        var relatorio = $('#relatorio-tipo').val();      
        
        $('#input-situacao').hide();
        $('#input-situacaoMulti').hide();
       
        $('#input-pecasprodutos').hide();
        $('#input-obra').hide();      
        $('#input-linha').hide();
        $('#input-vendedor').hide();
        $('#input-vendedortec').hide();
        $('#input-atendente').hide();        
        $('#input-produtos').hide();
        $('#input-dataini').hide();
        $('#input-datafim').hide();
        $('#input-lista').hide();
        $('#input-dtpesquisa').hide();
        $('#input-csv').hide();
        $('#input-modelo').hide();
        $('#input-detalhado').hide();
        $('#input-comissao').hide();
        
        switch (relatorio) {
            case "1":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacao').show();
              
                $('#input-dtpesquisa').show();
                break;
            case "2":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacao').show();
               
                $('#input-obra').show();      
                $('#input-pecasprodutos').show();              
                $('#input-vendedor').show();              
                $('#input-dataini').show();
                $('#input-datafim').show();    
                $('#input-dtpesquisa').show();            
                break;
            case "3":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacao').show();
              
                $('#input-obra').show();      
                $('#input-pecasprodutos').show();              
                 $('#input-vendedor').show();
               
                $('#input-vendedortec').show();
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-dtpesquisa').show();   
                break;
            case "4":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacao').show();
            
                $('#input-obra').show();      
                $('#input-pecasprodutos').show();              
                $('#input-vendedor').show();
                $('#input-vendedortec').show();
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-dtpesquisa').show();   
                break;
            case "5":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacao').show();                
                $('#input-atendente').show();
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-dtpesquisa').show();   
                break;
            case "6":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacao').show();             
                $('#input-csv').show();
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-dtpesquisa').show();                  
                $('#input-produtos').show();
                 $('#input-atendente').show();
                break;

            case "7":
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-situacaoMulti').show();
                
                $('#input-obra').show();      
               // $('#input-pecasprodutos').show(); 
               $('#input-comissao').show();            
               $('#input-vendedor').show();
               $('#input-vendedortec').show();
                $('#input-dataini').show();
                $('#input-datafim').show();
                $('#input-dtpesquisa').show();   
                $('#input-modelo').show();  
                $('#input-csv').show();
                $('#input-detalhado').show();
                
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
                    '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
                    '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
                    '</div>' +
                '</div>' +
            '</div>');
    }

    $('#relatorio_situacaoMulti').selectpicker({
         noneSelectedText: 'Todos'
});
</script>
</body>
</html>