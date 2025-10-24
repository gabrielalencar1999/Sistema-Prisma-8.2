<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php
require_once('navigatorbar.php');
use Database\MySQL;
$pdo = MySQL::acessabd();
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">NFe e NFCe</h4>
                <p class="text-muted page-title-alt">Emissão Nota Fiscal Eletrônica</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-mdf" onclick="_newmanifesto();"><i class="fa  fa-truck"></i> MDFe</button>    
                    <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span> Filtros Avançado</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="_incluir()" ><span class="btn-label"><i class="fa fa-plus"></i></span> Nova NFe</button>      
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card-box table-responsive" id="listagem"></div>
        </div>
    </div>
</div>

<!-- Modal Filtro -->
<div id="custom-modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Filtros </h4>
            </div>
            <div class="modal-body">
                
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro" >
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="nf-inicial">Período de </label>
                                <input type="date" class="form-control" name="nf-inicial" id="nf-inicial" value="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="nf-final">Até </label>
                                <input type="date" class="form-control" name="nf-final" id="nf-final" value="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                    
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nf-modelo">Modelo</label>
                                <select class="form-control" name="nf-modelo" id="nf-modelo">                               
                                    <option value="55" selected>55-NF-e</option>   
                                    <option value="58" >58-MDF-e</option>   
                                    <option value="0" >65-NFC-e</option>   
                                    <option value="90" >90-NFs-e</option>             
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">                        
                                    <label for="field-1" class="control-label">Nº Nota</label>                           
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="_numeronf" name="_numeronf">
                                    </div>                               
                        </div>
                        
                    </div>
                  
                    <div class="row ">                         
                             <div class="form-group col-md-3 col-xs-3" >
                                <label >Localizar por</label>
                                  <select class="form-control" name="nf-tipo" id="nf-tipo"> 
                                     <option value="0" >-</option>   
                                     <option value="1" >Cód. Peça/Produto</option>  
                                     <option value="3" >CPF/CNPJ</option>    
                                     <option value="2" >Observação</option>   
                                </select>
                             </div> 
                             <div class="form-group col-md-9 col-xs-9"  >
                                <label >Descrição</label>
                           
                                        <input type="text" class="form-control" id="_desc" name="_desc">
                                        
                             </div>                    
                        </div>
                          <?php  
                                                         $sql = "Select empresa_id,empresa_nome from " . $_SESSION['BASE'] . ".empresa";
                                                                $consulta = $pdo->query($sql);
                                                                $retornoEmp = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                                if($consulta->rowCount() > 1) {

                                                             
                                                                ?>
                           <div class="row ">
                            <div class="form-group col-md-6 col-xs-6"  >
                                <label >Empresa :</label>
                                <Select  class="form-control" id="_empresa"  name="_empresa" >
                                                        <option value="">Todos</option>
                                                        <?php                                                        
                                                               
                                                                foreach ($retornoEmp as $rowEmp) {
                                                              ?>
                                                              <option value="<?=$rowEmp->empresa_id;?>"><?=$rowEmp->empresa_nome;?></option>        
                                                               <?php
                                                              } ?>
                                                          
                                </Select>
                            </div> 
                                              
                        </div>
                        <?php  } ?>
                    <div class="row">
                        <div class="col-md-6" id="lotemensal">  
                             <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarxmlmensal()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar Lote Xml Autorizadas</button>
                        </div>
                        <div class="col-md-6">  
                            <button type="button"   class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="imprimirmensal()" id="_bt000445"  ><span class="btn-label btn-label"> <i class="fa   fa-print"></i></span>Imprimir Relatório </button>
                            <button type="button"  style="margin-top: 5px"; class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="imprimirmensalsintetico()" id="_bt000445"  ><span class="btn-label btn-label"> <i class="fa   fa-print"></i></span>Rel.Sintético Geral </button>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6" id="lotemensalcancelada">  
                             <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarxmlmensalcancelada()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar Lote Xml Cancelada</span></button>
                        </div>
                        <div class="col-md-6" id="lotesintegra">                           
                             <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarsintegra()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar Sintegra</button>                    
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                  
          
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_lista()" data-dismiss="modal">Buscar</button>
            </div>
        </div>
    </div>
</div>

<div id="custom-modal-mdf" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Manifesto Transporte</h4>
            </div>
            <div class="modal-body">
                
                 <div class="form-container">
                       
                            <form action="javascript:void(0)" method="post" id="mdfeForm" name="mdfeForm">
                            <!-- Seção: Informações Gerais -->
                              <label class="control-label " for="nf-fornecedornome">Informações Gerais</label>
                              <div class="card-box" >
                                            <input type="hidden" id="numero" name="numero" value="1" class="form-control"  min="1">
                                            <input type="hidden" id="numeromdfe" name="numeromdfe" value="1" class="form-control" >  
                                            <div class="row">
                                                 
                                                    <div class="form-group col-xs-3">                                                    
                                                            <label >Empresa</label>
                                                            <?php
                                                            $nfed_empresa = 1;
                                                              $sql = "SELECT                                                                   
                                                                    empresa_uf,
                                                                    empresa_codmunicipio,
                                                                    empresa_cidade                                                                
                                                                FROM " . $_SESSION['BASE'] . ".empresa 
                                                                WHERE empresa_id = :empresa_id";
                                                        // Prepara a consulta
                                                        $stmt = $pdo->prepare($sql);
                                                        // Faz o bind do parâmetro (seguro contra SQL Injection)
                                                        $stmt->bindValue(':empresa_id', $nfed_empresa, PDO::PARAM_INT);
                                                        // Executa
                                                        $stmt->execute();

                                                        // Busca os dados
                                                        $retornoEmp = $stmt->fetch(PDO::FETCH_ASSOC);
                                                        $uf =   $retornoEmp['empresa_uf'] ?? '';
                                                        $cidade = $retornoEmp['empresa_cidade'] ?? '';

                                                                $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa ORDER BY empresa_nome");
                                                                $retornoEmp = $statement->fetchAll();
                                                                ?>
                                                                <select name="nf-empresa" id="nf-empresa" class="form-control" >                                                
                                                                    <?php
                                                                    foreach ($retornoEmp as $row) {
                                                                        ?>
                                                                        <option value="<?=$row["empresa_id"]?>" <?=$row["empresa_id"] == $nfed_empresa ? "selected" : ""?>><?=$row["empresa_nome"]?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                            </select>
                                                    </div>
                                                    <div class="form-group col-xs-2">                                                    
                                                            <label >Peso Bruto *</label>
                                                            <input type="number" id="peso_bruto" name="peso_bruto" value="0" 
                                                        class="form-control" required min="0"> 
                                                    </div>
                                                     <div class="form-group col-xs-2">                                                    
                                                            <label >Valor Total Carga *</label>
                                                             <input type="number" id="valor_total_carga" name="valor_total_carga" value="0" 
                                                        class="form-control" required min="0"> 
                                                    </div>
                                            </div>
                                            <div class="row">
                                                 <?php
                                                    $ufs = [
                                                        "AC" => "Acre",
                                                        "AL" => "Alagoas",
                                                        "AP" => "Amapá",
                                                        "AM" => "Amazonas",
                                                        "BA" => "Bahia",
                                                        "CE" => "Ceará",
                                                        "DF" => "Distrito Federal",
                                                        "ES" => "Espírito Santo",
                                                        "GO" => "Goiás",
                                                        "MA" => "Maranhão",
                                                        "MT" => "Mato Grosso",
                                                        "MS" => "Mato Grosso do Sul",
                                                        "MG" => "Minas Gerais",
                                                        "PA" => "Pará",
                                                        "PB" => "Paraíba",
                                                        "PR" => "Paraná",
                                                        "PE" => "Pernambuco",
                                                        "PI" => "Piauí",
                                                        "RJ" => "Rio de Janeiro",
                                                        "RN" => "Rio Grande do Norte",
                                                        "RS" => "Rio Grande do Sul",
                                                        "RO" => "Rondônia",
                                                        "RR" => "Roraima",
                                                        "SC" => "Santa Catarina",
                                                        "SP" => "São Paulo",
                                                        "SE" => "Sergipe",
                                                        "TO" => "Tocantins"
                                                    ];
                                                    ?>
                                                    <div class="form-group col-xs-2">                                                    
                                                            <label for="uf_inicio">UF Início *</label>
                                                            <select id="uf_inicio" name="uf_inicio" class="form-control" required>
                                                                <option value="" <?= ($uf == '' ? 'selected' : '') ?>>Selecione...</option>
                                                                <?php foreach ($ufs as $sigla => $nome): ?>
                                                                    <option value="<?= $sigla ?>" <?= ($uf == $sigla ? 'selected' : '') ?>>
                                                                        <?= $nome ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>                                                                                                                                                     
                                                    </div>
                                                      <div class="form-group col-xs-4">                                                    
                                                          <label for="uf_inicio">Município de Carregamento *</label>
                                                          
                                                          <input type="hidden" id="carregamento_codigo" name="municipio_carregamento_codigo" value="3550308" class="form-control" required maxlength="7" pattern="[0-9]{7}">  
                                                          <input type="text" id="municipio_carregamento_nome" name="municipio_carregamento_nome"  value="<?=$cidade;?>" class="form-control" required maxlength="60">
                                                    </div>
                                                       
                                                       <div class="form-group col-xs-2">   
                                                        <label for="uf_fim">UF Fim *</label>
                                                        <select id="uf_fim" name="uf_fim" class="form-control" required>
                                                             <option value="" <?= ($uf == '' ? 'selected' : '') ?>>Selecione...</option>
                                                                <?php foreach ($ufs as $sigla => $nome): ?>
                                                                    <option value="<?= $sigla ?>" <?= ($uf == $sigla ? 'selected' : '') ?>>
                                                                        <?= $nome ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                        </select>
                                                </div>
                                                 <div class="form-group col-xs-4">    
                                                                                               
                                                                <input type="hidden" name="municipios_descarga_codigo" value="3304557"   class="form-control" required maxlength="7" pattern="[0-9]{7}">
                                                                 <label>Nome do Município *</label>
                                                                 <input type="text" id="municipios_descarga_nome" name="municipios_descarga_nome" value="<?=$cidade;?>"  class="form-control" required maxlength="60">
                                             
                                                        </div>
                                            </div>
                              </div>    
         
                            <!-- Seção: Veículo -->
                              <label class="control-label " for="nf-fornecedornome">Dados veículo</label>
                              <div class="card-box" >
                                            <input type="hidden" id="numero" name="numero" value="1" class="form-control" required min="1">
                                            <input type="hidden" id="numero" name="numero" value="1" class="form-control" required min="1">    
                                            <div class="row">
                                                <div class="form-group col-xs-2">                                                    
                                                           <label for="veiculo_placa">Placa *</label>
                                                            <input type="text" id="veiculo_placa" name="veiculo_placa" value="ABC1234" 
                                                                class="form-control" required maxlength="7" 
                                                                pattern="[A-Z]{3}[0-9]{4}|[A-Z]{3}[0-9][A-Z][0-9]{2}">  
                                                </div>
                                           
                                         
                                                  <div class="form-group col-xs-3">    
                                                       <label for="veiculo_tara" >Peso do veículo vazio (Kg)</label>
                                                        <input type="number" id="veiculo_tara" name="veiculo[tara]" value="6500"                                                            class="form-control" required min="0"> 
                                                  </div>
                                                   <div class="form-group col-xs-2">   
                                                      <label for="veiculo_capacidade_kg">Capacidade (kg) *</label>
                                                    <input type="number" id="capacidade_kg" name="capacidade_kg" value="15000" 
                                                        class="form-control" required min="0"> 
                                                   </div>
                                                    <div class="form-group col-xs-2">   
                                                         <label for="veiculo_capacidade_m3">Capacidade (m³) *</label>
                                                        <input type="number" id="capacidade_m3" name="veiculo[capacidade_m3]" value="1" 
                                                            class="form-control" required min="0" step="0.01">
                                                    </div>
                                            </div>
                               
                            </div>

                            <!-- Seção: Motorista --> 
                         
                                <label class="control-label " for="nf-fornecedornome">Dados Motorista</label>
                              <div class="card-box" >
                                    <div class="row">
                                                        <div class="form-group col-xs-6">                                                    
                                                                <label for="nome_completo">Nome Completo *</label>
                                                                <input type="text" id="motorista_nome" name="motorista_nome" value="João da Silva Santos"    class="form-control" required maxlength="60">
                                                        </div>
                                                        <div class="form-group col-xs-2">                                                    
                                                                <label for="motorista_cpf">CPF *</label>
                                                                <input type="text" id="motorista_cpf" name="motorista_cpf" value="123.456.789-01"    class="form-control" required pattern="[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}">
                                                        </div>
                                    </div>
                            </div>
                    
                   
    <label class="control-label " for="nf-fornecedornome">Documentos Vinculados</label>
                              <div class="card-box" >
                                    <div class="row">
                                                        <div class="form-group col-xs-3">     
                                                            <label>Tipo de Documento *</label>
                                                                <select name="municipios_descarga[0][documentos][0][tipo]" class="form-control" required>
                                                                    <option value="NFe" selected>NF-e</option>
                                                                    <option value="CTe">CT-e</option>
                                                                </select>
                                                        </div>
                                                        <div class="form-group col-xs-7">     
                                                             <label>Chave de Acesso *</label>
                                                            <input type="text" name="chaveacesso"  class="form-control" required maxlength="44" pattern="[0-9]{44}">
                                                        </div>
                                                         <div class="form-group col-xs-2" style="margin-top: 25px;">     
                                                                
                                                         </div>
                                            
                                          
                                        
                                       
                                    </div>
                                </div>
                                
                             
                        

                            <!-- Seção: Informações Adicionais -->
                            <div class="form-section">
                                    <label > Informações Adicionais</label>
                           
                                </h2>
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea id="observacoes" name="observacoes" class="form-control" rows="4" 
                                            maxlength="5000">Transporte conforme MDF-e</textarea>
                                </div>
                            </div>

                            <!-- Ações -->

                              <!-- Mensagens de Status -->
                            <div id="result-manifesto" class="status-message"></div>
                               <div class="modal-footer">
                                  <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>  
                         
                                
                                <button type="button" id="submit-form" class="btn btn-success" onclick="_validarmanifesto()" >
                                    🚀 Gerar MDF-e
                                </button>
          
                                                   
                        </div>
                         

                           
                        </form>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div>

<!-- Modal Incluir Nota-->
<div id="custom-width-modal-incluir-nota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Incluir Nota Fiscal</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" id="form-inclui" name="form-inclui">
                    <div class="form-group col-md-4">
                        <label for="nf-num">N° da Nota</label>
                        <input type="number" class="form-control" id="nf-num" name="nf-num">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="nf-fornec">Fornecedor</label>
                        <select class="form-control" name="nf-fornec" id="nf-fornec">
                            <?php
                            $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante ORDER BY nome");
                            $retorno = $statement->fetchAll();
                            ?>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($retorno as $row) {
                                ?>
                                <option value="<?=$row["CODIGO_FABRICANTE"]?>"><?=$row["NOME"]?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

          
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluir()">Salvar</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
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

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="_chaveid" name="_chaveid">
    <input type="hidden" id="id-nota" name="id-nota">
    <input type="hidden" id="id-ref" name="id-ref">
    <input type="hidden" id="id-empresa" name="id-empresa">
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

<!-- Bootstrap -->
<script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>

<!--<script src="../../../api/view/administracao/acaoXML.php"></script>-->
<script src="assets/js/jquery.app.js"></script>

<script src="assets/js/printThis.js"></script>

<script type="text/javascript">
    window.onload = function () {
        _lista();
    }

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
        var $_keyid = "_Na00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id)
        var $_keyid = "";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
            });
    }

    function _incluir() {
        var $_keyid =   "NTFCECLIENTE";                     
            $('#_keyform').val($_keyid); 

             var dados = $("#formOS :input").serializeArray();
                 dados = JSON.stringify(dados);	
                                    
                       $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){							
                                $("#form1").submit();               
                          });    
        
    }

    function _incluiXML() {
        var form_data = new FormData(document.getElementById("form-xml"));
        aguarde();
        $.ajax({
            url: 'acaoXML.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                $("#custom-modal-result").modal('show').html(data);
            }
        });
    }

    function _lista() {
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem').html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _alterar(id,tiponf){
        if(tiponf == 90){
            var $_keyid = "_NTFCECLIENTE_00099";
        }else if(tiponf == 58) {
            var $_keyid = "_NTFCECLIENTE_00101";
        }
        else{
            var $_keyid = "_NTFCECLIENTE_00009";
        }
    
        $('#_keyform').val($_keyid);
        $('#id-nota').val(id);
     
        $('#form1').submit();

    }

    function _idexcluir(nota) {
     
        $('#id-nota').val(nota);
     
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 18},
            function(result){
            
                _lista();
            });
    }

    

    function _buscaSelect(id, retorno) {
        $("#id-filtro").val(id);
        var $_keyid = "";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $(retorno).html(result);
            });
    }

    function isXml(input)
    {
        var value = input.value;
        var res = value.substr(value.lastIndexOf('.')) == '.xml';
        if (!res) {
            input.value = "";
            $('#custom-width-modal-incluir-xml').modal('hide');
            $('#custom-modal-xml').modal('show');
        }
        return res;
    }

    function gerarxmlmensal(){
        
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#lotemensal');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
            
                $('#lotemensal').html(result);
               
            });
    }
    function gerarxmlmensalcancelada(){
        
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#lotemensalcancelada');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 55},
            function(result){
            
                $('#lotemensalcancelada').html(result);
               
            });
    }

   
    function gerarsintegra(){
        
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#lotesintegra');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){            
                $('#lotesintegra').html(result);               
            });
    }


    function baixarmensal(){

        
          document.getElementById('form-filtro').action = 'baixarlote.php'; 
      

           $("#form-filtro").submit(); 

          document.getElementById('form-filtro').action = 'Javascript:void(0)';
   

    }

    function imprimirmensal() {
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#_printviewer');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 9},
            function(result){               
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();     
                
            });
    }

    function imprimirmensalsintetico() {
        var $_keyid = "NTFCELT";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#_printviewer');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 10},
            function(result){               
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();     
                
            });
    }

    

    function imprimirmdf() {
        var $_keyid = "_NTFCECLIENTE_00011";
        var dados = $("#form-mdf :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#_printviewer');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){               
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();     
                
            });
    }


    function _atualizarNfse(_irfref,_dvret,_idEmp) {

        $('#id-empresa').val(_idEmp);
        $('#id-nota').val(_irfref);
        var $_keyid = "_NTFCECLIENTE_00090";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $('#'+_dvret).html('<img src="assets/images/loading.gif" class="img-responsive center-block" width="25" alt=" aguarde.">' +
            '<h6 class="text-center">Aguarde, processando dados...</h6>');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados, acao: 7
        }, function(result) {
       
            $('#'+_dvret).html(result);
        });


        };

        function _atualizarMDFE(_irfref,_dvret,_idEmp) {
                $('#id-empresa').val(_idEmp);
            // $('#id-nota').val(_irfref);
                $('#id-ref').val(_irfref);
                var $_keyid = "_NTFCECLIENTE_00100";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $('#'+_dvret).html('<img src="assets/images/loading.gif" class="img-responsive center-block" width="25" alt=" aguarde.">' +
                    '<h6 class="text-center">Aguarde, processando dados...</h6>');

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados, acao: 7
                }, function(result) {
                     alert(result)
                    $('#'+_dvret).html(result);
                });

        };
        

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

     function _newmanifesto() {   
           
        var $_keyid = "_NTFCECLIENTE_00100";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
     
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 10}, function(result){  
               
                  $('#numeromdfe').val(result);                             
              
            });  
           
             

    }

      function _validarmanifesto() {   
          
     
        var $_keyid = "_NTFCECLIENTE_00100";
        var dados = $("#mdfeForm :input").serializeArray();
         dados = JSON.stringify(dados);    
     aguardeListagem('#result-manifesto');
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 30}, function(result){  
             
                $('#result-manifesto').html(result);                   
              
            });  
           
             

    }
</script>

</body>
</html>

