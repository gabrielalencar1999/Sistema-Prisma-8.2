<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Etiqueta</h4>
                <p class="text-muted page-title-alt">Gere as etiquetas dos seus produtos para cada impressora.</p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <form action="javascript:void(0)" name="form-inclui" id="form-inclui" method="post">
                        <div class="row">
                       
                            <div class="form-group col-md-2 col-xs-12">
                                <label for="valor-etq">Código</label>
                              <div class="input-group">
                                                <span class="input-group-btn">
                                                 <button type="button" data-toggle="modal" data-target="#custom-modal-buscar" class="btn waves-effect waves-light btn-primary input-sm" style="padding-top:5px;"><i class="fa fa-search"></i></button>
                                                </span>
                                                <input type="text" tabindex="6"  name="codbarra-etq" id="codbarra-etq" class="form-control  input-sm"  onblur="_buscadados(this.value)" placeholder="Peça/Produto">
                                                <input type="hidden"  name="cod-forn" id="cod-forn" class="form-control  input-sm" value="">
                                            </div>
                            </div>
                            <div class="form-group col-md-3 col-xs-12">
                                <label for="produto-etq">Descrição:</label>
                                <input type="text" name="produto-etq" id="produto-etq" class="form-control  input-sm" value="" >
                                <input type="hidden" name="valida-etq" id="valida-etq">
                            </div>
                             <div class="form-group col-md-2 col-xs-6">
                                <label for="valor-etq">Marca/Fabricante:</label>
                                <input type="text" name="Marca-etq" id="Marca-etq" class="form-control  input-sm">
                            </div>
                            <div class="form-group col-md-2 col-xs-6">
                                <label for="valor-etq">Endereço:</label>
                                <input type="text" name="endereco-etq" id="endereco-etq" class="form-control  input-sm">
                            </div>
                            <div class="form-group col-md-2 col-xs-6">
                                <label for="valor-etq">Valor Venda:</label>
                                <input type="text" name="valor-etq" id="valor-etq" class="form-control  input-sm">
                            </div>
                            <div class="form-group col-md-1 col-xs-6">
                                <label for="qnt-etq">Quantidade:</label>
                                <div class="input-group">
                                    <input type="text" name="qnt-etq" id="qnt-etq" class="form-control  input-sm">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-success waves-effect waves-light input-sm" onclick="_incluir()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row card-box" id="listagem"></div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="impressora-etq">Impressora:</label>
                                <select name="impressora-etq" id="impressora-etq" class="form-control">
                                    <option value="0">Selecione</option>
                                    <option value="1">Elgin</option>
                                    <option value="2">Argox</option>
                                    <option value="3">Bematech</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipo-etq" id="label-tipo">Tipo:</label>
                                <div class="input-group">
                                 
                                    <select name="tipo-etq" id="tipo-etq-elgin"  class="form-control">
                                        <option value="4">30x50mm 2 Colunas</option>                                      
                                    </select>
                                  
                                    <div class="input-group-btn">
                                        <button id="voltar" type="button" class="btn btn-default waves-effect waves-light m-l-5" onclick="_fechar()"><span class="btn-label"><i class="fa fa-times"></i></span>Fechar</button>
                                        <button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5"  data-toggle="modal" data-target="#custom-modal-result" onclick="_alterar()"><span class="btn-label"><i class="fa fa-check"></i></span>Gerar Arquivo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar-->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="custom-modal-buscar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                        <div class="modal-dialog modal-lg text-left">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Pesquisar Peças e Produtos</h4>
                                </div>
                                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form3" id="form3">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-2 " >
                                                            <select name="filtrarbusca" id="filtrarbusca" class="form-control input-sm">
                                                                    <option value="CODIGO_FABRICANTE">Cód.Fabricante</option>
                                                                    <option value="codigobarra">Cód.Barra</option>
                                                                    <option value="codigo">Cód. Interno</option>
                                                                    <option value="Codigo_Referencia_Fornec">Cod.Sku</option>
                                                                    <option value="descricao" selected="">Descrição</option>   
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-8 " >    
                                                            <input type="text" id="busca-produto" name="busca-produto" class="form-control input-sm" placeholder="Descrição, Cód. Fabricante / Barras / SKU ">
                                                        </div>
                                                        <div class="col-sm-1 " >
                                                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_prodservicos(1)"><i class="fa fa-search"></i></button>
                                                        </div>                       
                                                    </div>
                                                    <div class="row" id="retorno-produto" >
                                                        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive  " cellspacing="0" width="100%" style="margin-top:10px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Descrição</th>                                    
                                                                    <th>Código</th>
                                                                    <th>Valor</th>
                                                                    <th>Estoque</th>
                                                                    <th>End</th>
                                                                    
                                                                </tr>
                                                            </thead>                                                            
                                                            <tbody id="tbody_item">                                                        
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                </form>
                                <div class="modal-footer">
                                
                                    <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" >Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Modal Buscar OS-->
<!-- Modal Excluir-->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir o <b>produto</b>?</h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" id="btn-excluir" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_excluir()">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<!-- Modal Relatório -->
<div id="custom-modal-relatorio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando"></div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
    <input type="hidden" id="id-busca" name="id-busca" value="">
    <input type="hidden" id="id-exclusao" name="id-exclusao" value="">
      <input type="hidden" name="_codpesq" id="_codpesq" value="">
       <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">
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

<!--datatables-->
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
    window.onload = function () {
        _lista();
    }

    function _fechar() {
        var $_keyid = "_Na00006";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "ACETQT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
               
                result = jQuery.parseJSON( result );
             
                $("#cod-forn").val(result.codigointerno);
                $("#produto-etq").val(result.produto);
                $('#valor-etq').val(result.promocao);
                $('#endereco-etq').val(result.ENDERECO_COMPLETO);
              //  $('#Marca-etq').val(result.marca);
                $('#valor-etq').val(result.vlrvenda);
               
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

    function _incluir() {
        var $_keyid = "ACETQT";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
                _lista();
            });
    }

    function _lista() {
        var $_keyid = "ACETQT";
        aguardeListagem('#listagem');

        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#listagem").html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _alterar() {
        var $_keyid = "ACETQT";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
            });
    }

    function _excluir() {
        var $_keyid = "ACETQT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").modal('show').html(result);
                _lista();
            });
    }

    function _idexcluir(id) {
        $('#id-exclusao').val(id);
    }

    function _relatorio() {
        var $_keyid = "ACETQT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){
                $("#custom-modal-relatorio").modal('show').html(result);
            });

        $("#custom-modal-relatorio").on('hide.bs.modal', function (event) {
            location.reload();
        });
    }

    function imprimeModal(id) {
        $(id).printThis();
    }

    function _tipoImpressora(id) {
        if (id == 0) {
            $('#tipo-etq').show();
            $('#tipo-etq-elgin').hide();
            $('#tipo-etq-argox').hide();
            $('#label-tipo').attr('for', 'tipo-etq');
        }
        else if (id == 1) {
            $('#tipo-etq-elgin').show();
            $('#tipo-etq-argox').hide();
            $('#tipo-etq').hide();
            $('#label-tipo').attr('for', 'tipo-etq-elgin');
        }
        else if (id == 2) {
            $('#tipo-etq-argox').show();
            $('#tipo-etq-elgin').hide();
            $('#tipo-etq').hide();
            $('#label-tipo').attr('for', 'tipo-etq-argox');
        }
    }

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
            '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

     function _prodservicos(_id) {
      
    var $_keyid =   "S00009";   
   
    var dados = $("#form3 :input").serializeArray();
    dados = JSON.stringify(dados);
  
    aguardeListagem('#datatable-responsive-produtos-busca');
      
            $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 1}, function(result){	
          
                $('#retorno-produto').html(result); 
                $('#datatable-responsive-produtos-busca').DataTable(
                    {"pageLength": 25, "bFilter": false, "dom": 'rtip', "info": false,
                      "language": {
                            "paginate": {
                            "previous": " < ",
                            "next": " >>"
                            }
                    }
                    } 
                    );                                                                                       
             });
    }

        function _idprodutosel(_id) {
        $('#_codpesq').val(_id);
        $('#custom-modal-buscar').modal('hide');
        _buscaProdutoCodPesq(_id);
        }

        
        function _buscaProdutoCodPesq(id) {    
        
        $('#_keyidpesquisa').val(id);         
        var $_keyid =   "S00009";    
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);                              
        $.post("page_return.php", {_keyform:$_keyid,dados:dados,acao: 2}, function(result){					                          		                                                    ;
            var ret = JSON.parse(result);                    
                    $("#codbarra-etq").val(ret.CODIGO_FORNECEDOR);
                  $("#codbarra-etq").focus();
                                                                                            
        });  
    }

</script>
</body>
</html>