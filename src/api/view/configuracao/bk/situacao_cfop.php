<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<style>
.box-categoria{
	-webkit-box-shadow: 0px 0px 19px 13px rgba(186,186,186,0.52);
	-moz-box-shadow: 0px 0px 19px 13px rgba(186,186,186,0.52);
	box-shadow: 0px 0px 19px 13px rgba(186,186,186,0.22);
    width:400px;  min-height:500px ;  float:left; margin-left:10px; margin-bottom:10px; background-color:#FFF; padding:5px; text-align:center;
	border-radius:12px;
}
.addSub{
	color:#00a8e6;
	cursor:pointer;
}
.addSub:hover{
	text-decoration:underline;
}

.altCat{
	position:relative;
	top:-5px;
	left:225px;
	padding:2px;
	color:#f05050;
	font-size:20px;
	width:37px;
	float:left;
	cursor:pointer;
	padding:5px;
	border-radius:100px;
	background-color:#FFF;
}
.altCat:hover{
	color:#FFF;
	background-color:#f05050;
}

.selCor{
	 width:40px; 
	 height:40px;
}
.styleIconX{
	font-size:28px;
	color:#00a8e6;
}

</style>
<body >
<?php require_once('navigatorbar.php')?>


<!-- DAQUI PRA BAIXO  -->
  




<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Natureza da Operação</h4>
                <p class="text-muted page-title-alt">Tela de gerenciamento-CFOP</p>
            </div>  
             <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">   
                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir"><span class="btn-label"><i class="fa fa-plus"></i></span>Incluir</button>
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        
    
         <form id="form-filtro" name="form-filtro" method="post" action="javascript:void(0)">
        <div class="row">            
            <div class="col-md-12" >
                <div class="card-box table-responsive" id="grupo-lista">
                    
              
                        </div>
                    </div>
                </div>
         </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Inclui-->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Adicionar Natureza da Operação</h4>
            </div>
            <div class="modal-body">
                <form id="form-inclui">
                    <input type="hidden" id="id-incluir" name="id-incluir" value="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CFOP <span style="color:red">*</span></label> 
                                <input type="text" class="form-control" id="addCFOP" placeholder="Ex: 5102">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Descrição <span style="color:red">*</span></label> 
                                <input type="text" class="form-control" id="addDescricao" placeholder="Digite uma descrição...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CST/CSOSN <span style="color:red">*</span></label>
                                <input type="text" class="form-control" id="addCSTCSOSN" placeholder="Ex: 102">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ICMS (%)</label>
                                <input type="text" class="form-control" id="addPorcentagem" placeholder="ICMS %">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>PIS (%)</label>
                                <input type="text" class="form-control" id="addPIS" placeholder="PIS %">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>COFINS (%)</label>
                                <input type="text" class="form-control" id="addCOFINS" placeholder="COFINS %">
                            </div>
                        </div>
                    </div>
                       
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PIS<span style="color:red">*</span></label>
                                <select class="form-control" id="addComboPIS">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        $sql = "SELECT pis_id, pis_desc FROM bd_prisma.tab_pis ORDER BY pis_desc";
                                        $stmt = $pdo->query($sql);
                                        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($dados as $item): ?>
                                            <option value="<?php echo htmlspecialchars($item['pis_id']); ?>">
                                                <?php echo htmlspecialchars($item['pis_desc']); ?>
                                            </option>
                                        <?php endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Erro ao carregar PIS</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>COFINS <span style="color:red">*</span></label>
                                <select class="form-control" id="addComboCOFINS">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        $sql = "SELECT cofins_id, cofins_desc FROM bd_prisma.tab_cofins ORDER BY cofins_desc";
                                        $stmt = $pdo->query($sql);
                                        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($dados as $item): ?>
                                            <option value="<?php echo htmlspecialchars($item['cofins_id']); ?>">
                                                <?php echo htmlspecialchars($item['cofins_desc']); ?>
                                            </option>
                                        <?php endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Erro ao carregar COFINS</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo Documento <span style="color:red">*</span></label>
                                <select class="form-control" id="addTipodoc">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        $sql = "SELECT natTipo_id, natTipo_descricao FROM bd_prisma.NF_TipoDocumento ORDER BY natTipo_descricao";
                                        $stmt = $pdo->query($sql);
                                        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($dados as $item): ?>
                                            <option value="<?php echo htmlspecialchars($item['natTipo_id']); ?>">
                                                <?php echo htmlspecialchars($item['natTipo_descricao']); ?>
                                            </option>
                                        <?php endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Erro ao carregar tipos de documento</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Finalidade <span style="color:red">*</span></label>
                                <select class="form-control" id="addFinalidade">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        $sql = "SELECT natFin_id, natFin_desc FROM bd_prisma.NF_Finalizade ORDER BY natFin_desc";
                                        $stmt = $pdo->query($sql);
                                        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($dados as $item): ?>
                                            <option value="<?php echo htmlspecialchars($item['natFin_id']); ?>">
                                                <?php echo htmlspecialchars($item['natFin_desc']); ?>
                                            </option>
                                        <?php endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Erro ao carregar finalidades</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Origem <span style="color:red">*</span></label>
                                <select class="form-control" id="addNatOrigem">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        $sql = "SELECT natORG_id, natORG_descricao FROM bd_prisma.NF_Origem ORDER BY natORG_descricao";
                                        $stmt = $pdo->query($sql);
                                        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($dados as $item): ?>
                                            <option value="<?php echo htmlspecialchars($item['natORG_id']); ?>">
                                                <?php echo htmlspecialchars($item['natORG_descricao']); ?>
                                            </option>
                                        <?php endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Erro ao carregar origens</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observação</label>
                                <select class="form-control" id="addTipo">
                                    <option value="1">Sem mensagem</option>
                                     <option value="0">Mensagem padrão simples nacional</option>                                    
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Operação <span style="color:red">*</span></label>
                                <select class="form-control" id="addOperacao">
                                    <option value="">Selecione...</option>
                                    <?php
                                    try {
                                        $sql = "SELECT natOp_id, natOp_descricao FROM bd_prisma.NF_Operacao ORDER BY natOp_descricao";
                                        $stmt = $pdo->query($sql);
                                        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($dados as $item): ?>
                                            <option value="<?php echo htmlspecialchars($item['natOp_id']); ?>">
                                                <?php echo htmlspecialchars($item['natOp_descricao']); ?>
                                            </option>
                                        <?php endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Erro ao carregar operações</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        </div>

                </form>
            </div>
            <div>
           
         </div > 
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar-->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Editar Situação</h4>
            </div>
            <div class="modal-body" id="modalalterar">
               
        </div>
    </div>
</div>
</div>

<!-- Modal Excluir-->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir esta situação? </h2>
                    <p>
                        <button type="button" class="btn btn-lg btn-white btn-md waves-effect" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-lg btn-danger btn-md waves-effect waves-light" onclick="excluirSub()">Excluir</button>
                                    <!--quando clicado, executa a função excluirSub() e manda lá pra baixo na função excluirSub() --> 
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="result">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-equi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Produtos</h4>
            </div>
            <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-equi" id="form-equi">
                     <input type="hidden" id="id-alteraproduto" name="id-alteraproduto" value="">
                    
                <div id="resultequi">
                        <div class="modal-body" >
                        
                            <div >
                            
                            </div>
                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-success waves-effect waves-light"  onclick="_produtoADD()"><span class="btn-label"><i class="fa fa-plus"></i></span>Adicionar Produto</button>                              
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>



<form  id="form1" name="form1" method="post" action=""> <!-- cria um formulario para passar como metodo post -->
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">  <!--passa o id-exclusão para o meu js la em baixo -->
    <input type="hidden" id="id-incluir" name="id-incluir" value="">
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

<!--Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>



<script src="assets/plugins/notifyjs/js/notify.js"></script>
<script src="assets/plugins/notifications/notify-metro.js"></script>



<script type="text/javascript">
    window.onload = function () {
      _lista();
    }

    // Adicionando evento de clique ao botão salvar do modal de inclusão
    // Evento de clique no botão salvar do modal de inclusão
    $(document).on('click', '#custom-modal-incluir .btn-success', function(e) {
        e.preventDefault(); // Previne o comportamento padrão do botão
        _incluir(); // Chama a função de inclusão
    });

    // Função que processa a inclusão de nova situação
    function _incluir() {
        // Se já está incluindo, não faz nada
        if ($('#custom-modal-incluir .btn-success').prop('disabled')) {
            return;
        }
        
        // Desabilita o botão para evitar múltiplos cliques
        $('#custom-modal-incluir .btn-success').prop('disabled', true);
        
        var $_keyid = "cfop00002"; // Chave do formulário para identificar a operação
        
        // Coleta todos os dados do formulário de inclusão
        // Converte os valores para float, substituindo vírgula por ponto
        var porcentagem = $('#addPorcentagem').val() ? ($('#addPorcentagem').val().replace(',', '.')) : null;
        var valorPIS = $('#addPIS').val() ? ($('#addPIS').val().replace(',', '.')) : null;
        var valorCOFINS = $('#addCOFINS').val() ?($('#addCOFINS').val().replace(',', '.')) : null;
        var pis = $('#addComboPIS').val();
        var cofins = $('#addComboCOFINS').val();
        
        var dados = {
            NAT_DESCRICAO: $('#addDescricao').val(),
            NAT_TIPODOCUMENTO: $('#addTipodoc').val(), // observação
            NAT_FINALIDADE: $('#addFinalidade').val(),


            NAT_PIS: $('#addComboPIS').val(),
            NAT_COFINS: $('#addComboCOFINS').val(),
    



            NAT_OPERACAO: $('#addOperacao').val(),
            NAT_CST: $('#addCSTCSOSN').val(),
            NAT_CODIGO: $('#addCodigo').val(),
            NAT_ORIGEM: $('#addNatOrigem').val(),
            NAT_TIPO: $('#addTipo').val(), // tipo documento
            
            NAT_pICMS: porcentagem !== null ? porcentagem.toString() : '',
            NAT_pPis: valorPIS !== null ? valorPIS.toString() : '',
            NAT_pCofins: valorCOFINS !== null ? valorCOFINS.toString() : ''
        };
        
        // Exibe mensagem de carregamento
        aguardeListagem('#grupo-lista');
        
        // Faz requisição AJAX para incluir a nova situação
        $.ajax({
            url: "page_return.php",
            type: "POST",
            data: {
                _keyform: $_keyid,
                dados: JSON.stringify(dados),
                acao: 1
            },
            // Remove dataType para aceitar qualquer tipo de resposta
            success: function(result) {
                $('#custom-modal-incluir .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-incluir .modal-footer .btn-default').prop('disabled', false);
                incluindo = false;

                let atualizou = false;
                try {
                    var response = typeof result === 'string' ? JSON.parse(result) : result;
                    if (response.status === 'success' || result.trim() === 'ok') {
                        atualizou = true;
                    }
                } catch (e) {
                    if (result.trim() === 'ok') {
                        atualizou = true;
                    }
                }
                if (atualizou) {
                    $('#form-inclui')[0].reset();
                    $('#custom-modal-incluir').modal('hide');
                    _lista();
                } else {
                    // Mesmo que não seja "ok", tenta atualizar a lista
                    $('#form-inclui')[0].reset();
                    $('#custom-modal-incluir').modal('hide');
                    _lista();
                    alert('Atenção: resposta inesperada do servidor. Verifique se o dado foi salvo.');
                }
            },
            error: function(xhr, status, error) {
                
                $('#custom-modal-incluir .btn-success').prop('disabled', false);
                
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    alert('Erro ao salvar: ' + (errorResponse.message || 'Erro desconhecido'));
                } catch (e) {
                     alert('Erro ao salvar. Por favor, tente novamente.');
                }
                
                to
                $('#grupo-lista').html('');
            }
        });
    }



    // abre o modal de edição 
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');

        _buscadados(id);
    });

    function _fechar() {
       
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

    function _produto() {
                var $_keyid = "produto_00002";
            
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
                    function(result){      
                   
                        $("#resultequi").html(result);
                     
                });
    }

    function _produtoADD() {
               var $_keyid = "produto_00002";
               var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                    function(result){ 
                        $("#resultequi").html(result);
                     
                });
    }

    function _produtoNew() {
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
                    function(result){ 
                        $("#resultequi").html(result);           
                });
    }

    function alterarSub(id) {
                $('#id-alteraproduto').val(id);             
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
                    function(result){ 
                        $("#resultequi").html(result);           
                });
    }

    
    function excluirSub(id) { 
                 $('#id-alteraproduto').val(id);             
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
                    function(result){ 
                        $("#resultequi").html(result);           
                });
    }


    function _produtoSave() {
                           
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
                    function(result){ 
                    
                        _produto();        
                });
    }

    

    

    function _buscadados(id) {
        try {
            // Decodifica os dados da linha
            var dadosLinha = JSON.parse(atob(id));
            
            // Debug no console
            console.log('Dados recebidos para edição:', dadosLinha);
            
            // Preenche o modal com os dados
            $('#id-altera').val(dadosLinha.ID || '');
            $('#editCFOP').val(dadosLinha.NAT_CODIGO || '');
            $('#editDescricao').val(dadosLinha.NAT_DESCRICAO || '');
            $('#editTipodoc').val(dadosLinha.NAT_TIPODOCUMENTO || '1');
            $('#editTipo').val(dadosLinha.NAT_TIPO || '1');
            $('#editFinalidade').val(dadosLinha.NAT_FINALIDADE || '1');
            $('#editOperacao').val(dadosLinha.NAT_OPERACAO || '1');
            $('#editCSTCSOSN').val(dadosLinha.NAT_CST || '');
            $('#editPorcentagem').val(dadosLinha.NAT_pICMS || '');
            $('#editComboPIS').val(dadosLinha.NAT_pPis || '');
            $('#editComboCOFINS').val(dadosLinha.NAT_pCofins || '');
            $('#editNatOrigem').val(dadosLinha.NAT_ORIGEM || '1');
            
            // Preenche os combos de PIS e COFINS
            if (dadosLinha.NAT_PIS) {
                console.log('Definindo PIS:', dadosLinha.NAT_PIS);
                $('#editComboPIS').val(dadosLinha.NAT_PIS);
            }
            
            if (dadosLinha.NAT_COFINS) {
                console.log('Definindo COFINS:', dadosLinha.NAT_COFINS);
                $('#editComboCOFINS').val(dadosLinha.NAT_COFINS);
            }
            
            // Força o refresh dos selects (importante para alguns plugins)
            $('#editComboPIS, #editComboCOFINS').trigger('change');
            
            // Mostra o modal
            $('#custom-modal-alterar').modal('show');
            
        } catch (error) {
            console.error('Erro ao carregar dados para edição:', error);
            alert('Erro ao carregar os dados para edição. Verifique o console para mais detalhes.');
        }
    }
    

    // Função que carrega produtos no modal de filtro
    function mod_produto() {
        // Define a chave do formulário
        var $_keyid = "modelo_00002";
        // Serializa os dados do formulário de filtro
        var dados = $("#form-filtro :input").serializeArray();
        // Converte os dados para JSON
        dados = JSON.stringify(dados);

        // Faz requisição AJAX para carregar os produtos
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){     
                // Exibe o resultado no modal de produtos
                $("#modelo-produto").html(result);
        });
    }
    // Função que carrega produtos no modal de edição/inclusão
    function mod_produto2(ref) {
        // Define a chave do formulário
        var $_keyid = "modelo_00002";
        
        // Verifica se é edição (A) ou inclusão
        if(ref == 'A') {
            // Para edição: carrega produtos do formulário de alteração
            var dados = $("#form-altera :input").serializeArray();
            dados = JSON.stringify(dados);

            // Faz requisição AJAX para carregar produtos no modal de edição
            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
                function(result){     
                    $("#modelo-produtoA").html(result);
            });

        }else {
            // Para inclusão: carrega produtos do formulário de inclusão
            var dados = $("#form-inclui :input").serializeArray();
            dados = JSON.stringify(dados);

            // Faz requisição AJAX para carregar produtos no modal de inclusão
            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
                function(result){     
                    $("#modelo-produtoI").html(result);
            });
        }
     
    
 }

    

    // Flag para controlar inclusão única
    var incluindo = false;

    // Função que processa a inclusão de nova situação
    function _incluir() {
        // Se já está incluindo, não faz nada
        if (incluindo) {
            return;
        }
        

        // Obtém o valor do campo de porcentagem e converte para float (substitui vírgula por ponto)
        var valorPorcentagem = $('#addPorcentagem').val();
        var valorFloat = valorPorcentagem ? parseFloat(valorPorcentagem.replace(',', '.')) : 0;
        
        // Cria um objeto com todos os dados do formulário de inclusão
        var dados = {
            NAT_CODIGO: $('#addCFOP').val(),
            NAT_DESCRICAO: $('#addDescricao').val(), 
            NAT_TIPODOCUMENTO: $('#addTipodoc').val(), // Tipo Documento
            NAT_TIPO: $('#addTipo').val(),  // Tipo
            NAT_PIS: $('#addComboPIS').val(), // PIS
            NAT_COFINS: $('#addComboCOFINS').val(), // COFINS
            NAT_FINALIDADE: $('#addFinalidade').val(), 
            NAT_OPERACAO: $('#addOperacao').val(), 
            NAT_CST: $('#addCSTCSOSN').val(),
            NAT_pICMS: $('#addPorcentagem').val(),
            NAT_ORIGEM: $('#addNatOrigem').val(), 
            NAT_pPis: $('#addPIS').val() || '0',  // Porcentagem PIS
            NAT_pCofins: $('#addCOFINS').val() || '0'  // Porcentagem COFINS
        };
     

     
        incluindo = true;
  
        if (!dados.NAT_DESCRICAO) {
            $.Notification.notify('error','top right','Acesso Negado!', 'Descrição não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_CODIGO) {
            $.Notification.notify('error','top right','Acesso Negado!', 'CFOP não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_TIPODOCUMENTO) {
            $.Notification.notify('error','top right','Acesso Negado!', 'Tipo Documento não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_TIPO) {
            $.Notification.notify('error','top right','Acesso Negado!', 'Observação não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_FINALIDADE) {
            $.Notification.notify('error','top right','Acesso Negado!', 'Finalidade não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_OPERACAO) {
            $.Notification.notify('error','top right','Acesso Negado!', 'Operação não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_CST) {
            $.Notification.notify('error','top right','Acesso Negado!', 'CST/CSOSN não pode estar vazia.');
            incluindo = false;
            return;
        }
        if (!dados.NAT_ORIGEM) {
            $.Notification.notify('error','top right','Acesso Negado!', 'Origem não pode estar vazia.');
            incluindo = false;
            return;
        }
       
      

        // Adiciona flag de loading e bloqueia botões
        $('#custom-modal-incluir .modal-footer .btn-success').prop('disabled', true);
        $('#custom-modal-incluir .modal-footer .btn-default').prop('disabled', true);
        incluindo = true;

        // Faz requisição AJAX para incluir nova situação
        $.ajax({
            url: "page_return.php",
            type: "POST",
            data: {
                _keyform: "cfop00002",
                dados: JSON.stringify(dados),
                acao: 1
            },
            success: function(result) {
                $('#custom-modal-incluir .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-incluir .modal-footer .btn-default').prop('disabled', false);
                incluindo = false;

                let atualizou = false;
                try {
                    var response = typeof result === 'string' ? JSON.parse(result) : result;
                    if (response.status === 'success' || result.trim() === 'ok') {
                        atualizou = true;
                    }
                } catch (e) {
                    if (result.trim() === 'ok') {
                        atualizou = true;
                    }
                }
                if (atualizou) {
                    $('#form-inclui')[0].reset();
                    $('#custom-modal-incluir').modal('hide');
                    _lista();
                } else {
                    $('#form-inclui')[0].reset();
                    $('#custom-modal-incluir').modal('hide');
                    _lista();
                }
            },
            error: function(xhr, status, error) {
                $('#custom-modal-incluir .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-incluir .modal-footer .btn-default').prop('disabled', false);
        
                incluindo = false;
        
                alert('Erro ao comunicar com o servidor: ' + error);
            }
        });
    }

    // Adiciona evento de clique para o botão salvar
    $(document).ready(function() {
     
        $('#custom-modal-incluir .modal-footer .btn-success').off('click');
        
     
        $('#custom-modal-incluir .modal-footer .btn-success').on('click', function(e) {
            e.preventDefault(); // Previne o comportamento padrão do botão
            e.stopPropagation(); // Previne propagação do evento
            _incluir(); 
            return false; 
        });
    });


 
    function _lista() {
      
        var $_keyid = "cfop00002";
     
        var dados = $("#form-filtro :input").serializeArray();
        // Converte os dados para JSON
        dados = JSON.stringify(dados);
   
        aguardeListagem('#grupo-lista');
      
        $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 0},
            function(result){
        
                $("#grupo-lista").html(result);
          
                $('#datatable-responsive').DataTable();
                
               $('#demo-foo-search').on('keyup', function() {
                    var termo = $(this).val().toLowerCase();
                    $('#demo-foo-filtering tbody tr').hide();
                    $('#demo-foo-filtering tbody tr').filter(function() {
                        var cfop = $(this).find('td').eq(0).text().toLowerCase();      // Número CFOP
                        var descricao = $(this).find('td').eq(1).text().toLowerCase(); // Descrição
                        return cfop.indexOf(termo) > -1 || descricao.indexOf(termo) > -1;
                    }).show();
                });
        });
    }

    // Função que processa o salvamento dos dados alterados
    function _alterar() {
        // Bloqueia os botões para evitar múltiplos cliques
        $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', true);
        $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', true);
        
        var porcentagem = $('#editPorcentagem').val() ? parseFloat($('#editPorcentagem').val().replace(',', '.')) : null;
        var valorPIS = $('#editPIS').val() ? parseFloat($('#editPIS').val().replace(',', '.')) : null;
        var valorCOFINS = $('#editCOFINS').val() ? parseFloat($('#editCOFINS').val().replace(',', '.')) : null;
        var pis = $('#editComboPIS').val();
        var cofins = $('#editComboCOFINS').val();
        
     
        var dados = {
            ID: $('#id-altera').val(),
            NAT_CODIGO: $('#editCFOP').val(),
            NAT_DESCRICAO: $('#editDescricao').val(),
            NAT_TIPODOCUMENTO: $('#editTipoDocumento').val(),
            NAT_TIPO: $('#editTipo').val(),
            NAT_PIS: $('#editComboPIS').val(),
            NAT_COFINS: $('#editComboCOFINS').val(),
            NAT_FINALIDADE: $('#editFinalidade').val(),
            NAT_OPERACAO: $('#editOperacao').val(),
            NAT_CST: $('#editCSTCSOSN').val(),
            NAT_pICMS: porcentagem !== null ? porcentagem.toString() : '',
            NAT_ORIGEM: $('#editNatOrigem').val(),
            NAT_pPis: valorPIS !== null ? valorPIS.toString() : '',
            NAT_pCofins: valorCOFINS !== null ? valorCOFINS.toString() : ''
        };
      
    
        
        if (!dados.NAT_DESCRICAO) {
            alert('A descrição não pode estar vazia!');
            $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', false);
            $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', false);
            return;
        }
        
        // Faz a requisição AJAX para atualizar os dados
        $.ajax({
            url: 'page_return.php',
            type: 'POST',
            data: {
                _keyform: 'cfop00002',
                dados: JSON.stringify(dados),
                acao: 2
            },
            success: function(response) {
               
                // Libera os botões novamente
                $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', false);
                
                try {
                    // Tenta fazer parse da resposta como JSON
                    var result = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if (result.status === 'success') {
                        // Fecha o modal e atualiza a lista
                        $('#custom-modal-alterar').modal('hide');
                        _lista();
                    } else {
                        // Mostra mensagem de erro se existir
                        if (result.message && result.message.trim() !== '') {
                            alert('Erro: ' + result.message);
                        } else {
                            alert('Ocorreu um erro ao processar a requisição.');
                        }
                    }
                } catch (e) {
                    console.error('Erro ao processar resposta:', e);
                    alert('Erro ao processar a resposta do servidor.');
                }
            },
            error: function(xhr, status, error) {
                // Libera os botões que foram bloqueados
                $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', false);
                
                // Exibe mensagem de erro detalhada
                var errorMsg = 'Erro ao comunicar com o servidor';
                if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        errorMsg = response.message || errorMsg;
                    } catch (e) {
                        errorMsg = xhr.responseText || errorMsg;
                    }
                }
                console.error('Erro na requisição:', status, error);
                alert(errorMsg);
            }
        });
    }




    // função excluir
    function _idexcluir(id){ //quando o botão é clicado, ele captura o id do botão
        $('#id-exclusao').val(id); // #id-exclusao recebe o valor do (id) do botão clicado
        $('#custom-modal-excluir').modal('show'); // $(#custom-modal-excluir) é o id do modal que vai ser aberto .modal('show') mostra o modal na tela com bootstrap
    }

    

    function _abrirModalAlterar($id) {
    var $_keyid = "cfop00002";
    // O campo #id-exclusao já está preenchido pelo _idexcluir(id)
    var id = $('#id-altera').val($id);
    var dados = $("#form1 :input").serializeArray();
    dados = JSON.stringify(dados);

    $.post("page_return.php", {_keyform: $_keyid, dados: dados, acao: 4},
        function(result) {
   
    $('#modalalterar').html(result);


    
        }
    );
}

  

    function excluirSub() {
    var $_keyid = "cfop00002";
    // O campo #id-exclusao já está preenchido pelo _idexcluir(id)
    var dados = $("#form1 :input").serializeArray();
    dados = JSON.stringify(dados);

    $.post("page_return.php", {_keyform: $_keyid, dados: dados, acao: 3},
        function(result) {
            // Tenta tratar como JSON, mas aceita 'ok' puro também
            let sucesso = false;
            try {
                var response = typeof result === 'string' ? JSON.parse(result) : result;
                if (response.status === 'success' || result.trim() === 'ok') {
                    sucesso = true;
                }
            } catch (e) {
                if (result.trim() === 'ok') {
                    sucesso = true;
                }
            }
            if (sucesso) {
                $("#custom-modal-excluir").modal('hide');
                _lista();
            } else {
                alert('Erro: ' + result);
            }
        }
    );
}
        
    function _excluir() {
  var id = $('#id-exclusao').val();
  excluirSub(id);
}

    

    





   function aguardeListagem(id) {
    $(id).html('' +
        '<div class="bg-icon pull-request">' +
        '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
        '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
        '</div>');
   } 
    
 
    
</script>

</body>
</html>