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
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Modelos de Produtos</h4>
                <p class="text-muted page-title-alt">Aparelhos e Modelos de produto</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">   
                    <button  type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span> Filtros</button> 
                    <button type="button" class="btn btn-purple waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-equi" onclick="_produto()"><span class="btn-label"><i class="fa  fa-plug"></i></span>Produtos</button>                
                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir"><span class="btn-label"><i class="fa fa-plus"></i></span>Incluir</button>
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        
   
        <div class="row">            
            <div class="col-md-12" >
                <div class="card-box table-responsive" id="grupo-lista">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
          
            </div>
        </div>
    </div>
</div>

<!-- Modal Inclui-->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Modelo </h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label >Linha</label>               
                                         <select name="modelo-linhaI" id="modelo-linhaI" class="form-control" onchange="mod_produto2('I')">  
                                                                                        
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_linha where ap_linhaAtivo = 1 ORDER BY ap_linhaDescricao");
                                              $retorno = $consulta->fetchAll();
                                              foreach ($retorno as $row) {
                                             
                                                  ?><option value="<?=$row["ap_linhaId"]?>" <?php if($row["ap_linhaId"] == 2) { echo 'selected';}?> ><?=$row["ap_linhaDescricao"]?></option><?php
                                                 
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Produto</label>               
                                            <select name="modelo-produtoI" id="modelo-produtoI" class="form-control">                                              
                                            <option value="">Selecione</option>   
                                            <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_produto where ap_prodLinha = '2' ORDER BY ap_prodd");
                                              $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);
                                              foreach ($retorno as $row) {
                                                ?><option value="<?=$row->ap_prodId;?>"><?=$row->ap_prodd;?></option><?php
                                              }                                              
                                              ?>                          
                                            </select>
                                        </div>
                                </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                     <label >Marca / Fabricante</label>               
                                         <select name="modelo-marca" id="modelo-marca" class="form-control">                                              
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante where for_Tipo = 1 ORDER BY NOME");
                                              $retorno = $consulta->fetchAll();
                                              foreach ($retorno as $row) {
                                                  ?><option value="<?=$row["CODIGO_FABRICANTE"]?>"><?=$row["NOME"]?></option><?php
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="modelo-descricao" class="control-label">Descrição</label>
                                    <input type="text" class="form-control" name="modelo-descricao" id="modelo-descricao" placeholder="Descrição Produto e Aparelho" value="<?=$retorno["DESCRICAO"]?>">
                                    <input type="hidden" name="modelo-id" id="modelo-id" value="<?=$retorno["CODIGO_APARELHO"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="modelo-comercial" class="control-label">Modelo Comercial</label>
                                    <input type="text" class="form-control" name="modelo-comercial" id="modelo-comercial" placeholder="Modelo Comercial" value="<?=$retorno["DESCRICAO"]?>">
                                   
                                </div>
                            </div>
                        </div>
                        
                        </form> 
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluir()">Salvar</button>
            </div>
               
            </div>
          
        </div>
    </div>
</div>

<!-- Modal Alterar-->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
            <div id="result-exclui" class="result">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
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
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir a modelo? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_excluir();">Excluir</button>
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
                <button type="button" class="btn btn-success waves-effect waves-light" onch onclick="_lista()" data-dismiss="modal">Buscar</button>
            </div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
    <input type="hidden" id="id-altera" name="id-altera" value="">
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

    function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

    function _produto() {
                var $_keyid = "produto_00002";            
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
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

    
    function _excluirprod() {
                           
                           var $_keyid = "produto_00002"; 
                           var dados = $("#form-equi :input").serializeArray();
                           dados = JSON.stringify(dados);
                           aguardeListagem('#resultequi');
                           $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
                               function(result){                                
                                   _produto();        
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
        $('#id-altera').val(id);
        var $_keyid = "modelo_00002";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
           
                $("#custom-modal-alterar").html(result);
        });
    }

    function mod_produto() {
     
        var $_keyid = "modelo_00002";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){     
               
                $("#modelo-produto").html(result);
        });
    }
    function mod_produto2(ref) {
        var $_keyid = "modelo_00002";
        if(ref == 'A') {
            var dados = $("#form-altera :input").serializeArray();
              dados = JSON.stringify(dados);

            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
                function(result){     
                    
                    $("#modelo-produtoA").html(result);
            });

        }else {
            var dados = $("#form-inclui :input").serializeArray();
              dados = JSON.stringify(dados);

            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
                function(result){     
                    
                    $("#modelo-produtoI").html(result);
            });

        }
     
    
 }

    

    function _incluir() {
        var $_keyid = "modelo_00002";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                $("#form-inclui :input").val("")
                _lista();
        });
    }



    function _lista() {
        
        var $_keyid = "modelo_00002";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#grupo-lista');
        $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 2},
            function(result){
              
                $("#grupo-lista").html(result);
                $('#datatable-responsive').DataTable();
        });
    }

    function _altera() {
        var $_keyid = "modelo_00002";
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
        });
    }

    function _idexcluir(id) {
        $('#id-exclusao').val(id);
    }

    function _excluir() {
        var $_keyid = "modelo_00002";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
        });
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