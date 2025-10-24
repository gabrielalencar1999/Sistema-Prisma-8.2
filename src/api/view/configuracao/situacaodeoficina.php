<?php include("../../api/config/iconexao.php");
?>
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
                                    <h4 class="page-title m-t-15">Situação de Oficina</h4>
                                    <p class="text-muted page-title-alt">Cadastro oficina</p>
                                </div>
                                <div class="btn-group pull-right m-t-20">
                                    <div class="m-b-30">   
                                        <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir"><span class="btn-label"><i class="fa fa-plus"></i></span>Incluir</button>
                                        <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div> 
                                <div class="row">            
                                    <div class="col-md-12" >
                                        <div class="card-box table-responsive" id="grupo-lista">
                            <div style="text-align:center; padding: 40px;">
                                <img src="/assets/img/loading.gif" alt="Carregando..." />
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
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
                <h4 class="modal-title">Adicionar Situação</h4>
            </div>
            <div class="modal-body">
                <form id="form-inclui">
                    <input type="hidden" id="id-incluir" name="id-incluir" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input type="text" class="form-control" id="addDescricao">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ordem de Visualização</label>
                                <input type="number" class="form-control" id="addOrdemVisualizacao" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cor do Fundo</label>
                                <input type="color" class="form-control" id="addCorPersonalizada" value="#000000">
                                <button type="button" class="btn btn-outline-secondary btn-block" onclick="document.getElementById('addCorPersonalizada').value = '#000000';">
                                    <i class="fa fa-eraser"></i> 
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cor da Fonte</label>
                                <input type="color" class="form-control" id="addCorFonte" value="#ffffff">
                                <button type="button" class="btn btn-outline-secondary btn-block" onclick="document.getElementById('addCorFonte').value = '#ffffff';">
                                    <i class="fa fa-eraser"></i> Limpar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_incluir()">Salvar</button>
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
            <div class="modal-body">
                <form id="form-altera">
    <input type="hidden" id="id-altera" name="id-altera">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input type="text" class="form-control" id="editDescricao">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ordem de Visualização</label>
                                <input type="number" class="form-control" id="editOrdemVisualizacao" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ativo</label>
                                <select class="form-control" id="editSitelxAtivo">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cor do Fundo</label>
                                <input type="color" class="form-control" id="editCorPersonalizada" value="#ffffff">
                                <button type="button" class="btn btn-outline-secondary btn-block" onclick="document.getElementById('editCorPersonalizada').value = '#ffffff';">
                                    <i class="fa fa-eraser"></i> Limpar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cor da Fonte</label>
                                <input type="color" class="form-control" id="editCorFonte" value="#000000">
                                <button type="button" class="btn btn-outline-secondary btn-block" onclick="document.getElementById('editCorFonte').value = '#000000';">
                                    <i class="fa fa-eraser"></i> Limpar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_alterar()">Salvar</button>
            </div>
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
            </div><form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-equi" id="form-equi">
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


<!-- Modal Filtro -->
<div id="custom-modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Filtros</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                <div class="row m-b-10">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label >Linha</label>               
                                         <select name="modelo-linha" id="modelo-linha" class="form-control"  onchange="mod_produto()">    
                                        
                                                 <option value="">Todos</option>
                                                                       
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_linha where ap_linhaAtivo = 1 ORDER BY ap_linhaDescricao");
                                              $retorno = $consulta->fetchAll();
                                              foreach ($retorno as $row) {
                                                  ?><option value="<?=$row["ap_linhaId"]?>"><?=$row["ap_linhaDescricao"]?></option><?php
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Produto</label>               
                                            <select name="modelo-produto" id="modelo-produto" class="form-control">   
                                            <option value="">Todos</option>                                           
                                                <?php                                              
                                                $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_produto where ap_prodAtivo = 1 ORDER BY ap_prodd");
                                                $retorno = $consulta->fetchAll();
                                                foreach ($retorno as $row) {
                                                    ?><option value="<?=$row["ap_prodId"]?>"><?=$row["ap_prodd"]?></option><?php
                                                }                                              
                                                ?>                                          
                                            </select>
                                        </div>
                                </div>
                        </div>
                  
                    <div class="row m-b-10">
                        <div class="col-md-6">
                            <div class="form-group m-r-10">
                                <label for="nf-fornecedor">Fornecedor: </label>
                                <select class="form-control" name="nf-fornecedor" id="nf-fornecedor">
                                <?php
                                $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante where for_Tipo = 1 ORDER BY NOME");
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
                        </div>
                        <div class="col-md-6">                        
                                    <label for="field-1" class="control-label">Modelo</label>                           
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="descricao" name="descricao">
                                    </div>                               
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
        var $_keyid = "sitOf00002"; // Chave do formulário para identificar a operação
        
        // Coleta todos os dados do formulário de inclusão
        var dados = {
            sitmobOF_descricao: $('#addDescricao').val(), // Descrição da situação
            sitmobOF_ativo: $('#addSitelxAtivo').val(), // Indica se está ativo (0 ou 1)
            sitmobOF_ordemvis: $('#addOrdemVisualizacao').val() // Ordem de visualização
        };
        
        // Exibe mensagem de carregamento
        aguardeListagem('#grupo-lista');
        
        // Faz requisição AJAX para incluir a nova situação
        $.post("acao_situacaodeoficina.php", {
            dados: JSON.stringify(dados), // Dados do formulário em formato JSON
            acao: 1 // Código da ação (1 = inclusão)
        },
            function(result){
                var response = JSON.parse(result);
                if(response.status === 'success') {
                    _lista(); // Atualiza a lista de situações
                    $('#custom-modal-incluir').modal('hide'); // Fecha o modal de inclusão
                } else {
                    alert('Erro ao incluir: ' + response.message);
                }
            }
        );
    }

    // evento do botão de salvar do modal inclusão 
    $(document).on('click', '#custom-modal-incluir .modal-footer .btn-success', function(e) {
        e.preventDefault();
        _incluir();
    });

    // abre o modal de edição 
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');

        _buscadados(id);
    });

    function _fechar() {
                var $_keyid = "_Nc00005";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

    function _produto() {
                var $_keyid = "produto_00002";
            
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("acao_situacaodeoficina.php", {
                    dados: dados,
                    acao: 2
                },
                    function(result){      
                   
                        $("#resultequi").html(result);
                     
                });
    }

    function _produtoADD() {
               var $_keyid = "produto_00002";
               var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("acao_situacaodeoficina.php", {
                    dados: dados,
                    acao: 1
                },
                    function(result){ 
                        $("#resultequi").html(result);
                     
                });
    }

    function _produtoNew() {
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("acao_situacaodeoficina.php", {
                    dados: dados,
                    acao: 3
                },
                    function(result){ 
                        $("#resultequi").html(result);           
                });
    }

    function alterarSub(id) {
                $('#id-alteraproduto').val(id);             
                var $_keyid = "sitOf00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("acao_situacaodeoficina.php", {
                    dados: dados,
                    acao: 4
                },
                    function(result){ 
                        $("#resultequi").html(result);           
                });
    }

    
    $(document).ready(function() {
        $('.btn-editar').click(function() {
            var data = $(this).data('id');
            var dados = JSON.parse(atob(data));
            $('#alterarDescricao').val(dados.DESCRICAO);
            $('#custom-modal-alterar').modal('show');
        });
    });

    function excluirSub(id) { 
                 $('#id-alteraproduto').val(id);             
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("acao_situacaodeoficina.php", {
                    dados: dados,
                    acao: 5
                },
                    function(result){ 
                        $("#resultequi").html(result);           
                });
    }


    function _produtoSave() {
                           
                var $_keyid = "produto_00002"; 
                var dados = $("#form-equi :input").serializeArray();
                dados = JSON.stringify(dados);
                aguardeListagem('#resultequi');
                $.post("acao_situacaodeoficina.php", {
                    dados: dados,
                    acao: 6
                },
                    function(result){ 
                    
                        _produto();        
                });
    }

    

    

    function _buscadados(id) {
        // Decodifica os dados da linha
        var dadosLinha = JSON.parse(atob(id));
        
        // Preenche o modal com os dados
        $('#id-altera').val(dadosLinha.sitmobOF_id);
        $('#editDescricao').val(dadosLinha.sitmobOF_descricao);
        $('#editSitelxAtivo').val(dadosLinha.sitmobOF_ativo);
        $('#editSituacaoOficina').val(dadosLinha.sitmobOF_id);
        $('#editCorPersonalizada').val(dadosLinha.sitmobOF_cortable || '#000000');
        $('#editCorFonte').val(dadosLinha.sitmobOF_cortfont || '#ffffff');
        
        // Mostra o modal
        $('#custom-modal-alterar').modal('show');
    }

    // Função que carrega produtos no modal de filtro
    function mod_produto() {
        // Define a chave do formulário
        var $_keyid = "sitOf00002";
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
        var $_keyid = "sitOf00002";
        
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

        // Cria um objeto com todos os dados do formulário de inclusão
        var dados = {
            sitmobOF_descricao: $('#addDescricao').val(),
            sitmobOF_ativo: parseInt($('#addSitelxAtivo').val()) || 0,
            sitmobOF_ordemvis: parseInt($('#addOrdemVisualizacao').val()) || 0,
            sitmobOF_id: $('#addSituacaoOficina').val(),
            sitmobOF_cortable: $('#addCorPersonalizada').val(),
            sitmobOF_corfont: $('#addCorFonte').val()
        };

        // Verifica se a descrição está vazia
        if (!dados.sitmobOF_descricao) {
            alert('A descrição não pode estar vazia!');
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
                _keyform: "sitOf00002",
                dados: JSON.stringify(dados),
                acao: 1
            },
            success: function(result) {
                // Remove a flag de loading e libera botões
                $('#custom-modal-incluir .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-incluir .modal-footer .btn-default').prop('disabled', false);
                incluindo = false;
                
                try {
                    // Tenta converter o resultado para JSON
                    var response = JSON.parse(result);
                    if (response.status === 'success') {
                        // Limpa o formulário
                        $('#form-inclui')[0].reset();
                        // Fecha o modal
                        $('#custom-modal-incluir').modal('hide');
                        // Atualiza a lista
                        _lista();
                    } else {
                        alert('Erro ao incluir: ' + response.message);
                    }
                } catch (e) {
                    // Se não for JSON, tratar como string simples
                    if (result === 'ok') {
                        // Limpa o formulário
                        $('#form-inclui')[0].reset();
                        // Fecha o modal
                        $('#custom-modal-incluir').modal('hide');
                        // Atualiza a lista
                        _lista();
                    } else {
                        alert('Erro ao incluir: ' + result);
                    }
                }
            },
            // Tratamento de erro da requisição AJAX
            error: function(xhr, status, error) {
                // Libera os botões que foram bloqueados durante o processamento
                $('#custom-modal-incluir .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-incluir .modal-footer .btn-default').prop('disabled', false);
                // Reseta a flag de inclusão
                incluindo = false;
                // Exibe mensagem de erro para o usuário
                alert('Erro ao comunicar com o servidor: ' + error);
            }
        });
    }

    // Adiciona evento de clique para o botão salvar
    $(document).ready(function() {
        // Remove qualquer evento de clique existente
        $('#custom-modal-incluir .modal-footer .btn-success').off('click');
        
        // Adiciona novo evento de clique
        $('#custom-modal-incluir .modal-footer .btn-success').on('click', function(e) {
            e.preventDefault(); // Previne o comportamento padrão do botão
            e.stopPropagation(); // Previne propagação do evento
            _incluir(); // Chama a função de inclusão
            return false; // Previne qualquer outro comportamento
        });
    });



    // Função que carrega a lista de situações
    function _lista() {
        // Define a chave do formulário
        var $_keyid = "sitOf00002";
        // Serializa os dados do formulário
        var dados = $("#form1 :input").serializeArray();
        // Converte os dados para JSON
        dados = JSON.stringify(dados);
        // Exibe mensagem de carregamento
        aguardeListagem('#grupo-lista');
        // Faz requisição AJAX para carregar a lista
        $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 0},
            function(result){
                // Exibe o resultado na tabela
                $("#grupo-lista").html(result);
                // Inicializa a tabela responsiva do DataTable
                $('#datatable-responsive').DataTable();
                
                // Adiciona o filtro de pesquisa na tabela
                $('#demo-foo-search').on('keyup', function() {
                    // Pega o valor do campo de busca e converte para minúsculo
                    var termo = $(this).val().toLowerCase();
                    
                    // Primeiro esconde todas as linhas da tabela
                    $('#demo-foo-filtering tbody tr').hide();
                    
                    // Filtra e mostra apenas as linhas que contêm o termo buscado
                    $('#demo-foo-filtering tbody tr').filter(function() {
                        // Pega o texto da primeira coluna (Descrição) e compara com o termo
                        return $(this).find('td:first').text().toLowerCase().indexOf(termo) > -1;
                    }).show();
                });
        });
    }

    // Função que processa o salvamento dos dados alterados
    function _alterar() {
        // Cria um objeto com todos os dados do formulário de edição
        var dados = {
            sitmobOF_id: $('#id-altera').val(), // ID do registro que está sendo editado
            sitmobOF_descricao: $('#editDescricao').val(), // Nova descrição
            sitmobOF_ativo: parseInt($('#editSitelxAtivo').val()) || 0, // Valor do campo ativo (converte para número)
            sitmobOF_ordemvis: parseInt($('#editOrdemVisualizacao').val()) || 0, // Ordem de visualização
            sitmobOF_cortable: $('#editCorPersonalizada').val(), // Nova cor do botão
            sitmobOF_cortfont: $('#editCorFonte').val() // Nova cor da fonte
            
        }
        
        if (!dados.sitmobOF_id) {
            console.log(dados);
            alert('ID da situação não encontrado!');
            return;
        }

        // Adiciona uma flag de loading
        $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', true);
        $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', true);

        // Faz requisição AJAX para editar a situação
    $.ajax({
            url: "page_return.php", // URL do servidor
            type: "POST", // Método HTTP
            data: {
                _keyform: "sitOf00002", // Chave do formulário
                dados: JSON.stringify(dados), // Dados do formulário em formato JSON
                acao: 2 // Código da ação (2 = edição)
            },
            // Se a requisição for bem sucedida
            success: function(result) {
                // Libera os botões que foram bloqueados
                $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', false);
                
                // Se a edição foi bem sucedida
                if(result === 'ok') {
                    _lista(); // Atualiza a lista de situações
                    $('#custom-modal-alterar').modal('hide'); // Fecha o modal de edição
                } else {
                    // Se houve erro, exibe mensagem
                    alert('Erro ao alterar: ' + result);
                }
            },
            // Se houver erro na comunicação com o servidor
            error: function() {
                // Libera os botões que foram bloqueados
                $('#custom-modal-alterar .modal-footer .btn-success').prop('disabled', false);
                $('#custom-modal-alterar .modal-footer .btn-default').prop('disabled', false);
                // Exibe mensagem de erro
                alert('Erro ao comunicar com o servidor');
            }
        });
    }




    // função excluir
    function _idexcluir(id){ //quando o botão é clicado, ele captura o id do botão
        $('#id-exclusao').val(id); // #id-exclusao recebe o valor do (id) do botão clicado
        $('#custom-modal-excluir').modal('show'); // $(#custom-modal-excluir) é o id do modal que vai ser aberto .modal('show') mostra o modal na tela com bootstrap
    }

    
    function _abrirModalAlterar(dados) {
        // Decodificar e converter os dados
        const dadosJSON = atob(dados);
        dados = JSON.parse(dadosJSON);
        $('#id-altera').val(dados.sitmobOF_id);

        $('#editDescricao').val(dados.sitmobOF_descricao);
        $('#editSitelxAtivo').val(dados.sitmobOF_ativo);
        $('#editOrdemVisualizacao').val(dados.sitmobOF_ordemvis || 0);
        $('#editSituacaoOficina').val(dados.sitmobOF_id);
        $('#editCorPersonalizada').val(dados.sitmobOF_cortable || '#000000');
        $('#editCorFonte').val(dados.sitmobOF_cortfont || '#ffffff');
        $('#custom-modal-alterar').modal('show');
    }
   


    function excluirSub() { // referencia a função onclick do botão excluir do modal
        var $_keyid = "sitOf00002";  // referencia ao arquivo acesso que passa a rota para o arquivo page_return.php
        $('#_keyform').val($_keyid);
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

       $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 3},
            function(result) 
            {
                
                if(result.trim() === 'ok') {
                    $("#custom-modal-excluir").modal('hide');
                    _lista(); // Atualiza a lista
                } else {
                    alert('Erro: ' + result);
                }
            }
        );
    }

        
    function alterarSub(id) { 
        var $_keyid = "sitOf00002";
        $('#_keyform').val($_keyid);
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);


       $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 2},
            function(result) {
                if(result.trim() === 'ok') {
                    $("#custom-modal-excluir").modal('hide');
                    _lista(); // Atualiza a lista
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