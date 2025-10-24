<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

use Functions\Validador;
if (isset($_parametros)) {
    $_parametros = Validador::sanitizeArrayRecursive($_parametros);
}
?>
<style>
    .color-red{
        font-size:18px;
        color:#F05050;
    }
    .color-red:hover{
        color:#a73939;
    }
</style>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Condições de Pagamento</h4>
                <p class="text-muted page-title-alt">Cadastre os tipos de pagamentos aceitos.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button id="addToTable" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir">Incluir <i class="fa fa-plus"></i></button>
<button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="listagem">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incluir -->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Incluir Condição de Pagamento</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="condicao-nome" class="control-label">Nome:</label>
                                <input type="text" class="form-control" name="condicao-nome" id="condicao-nome" value="">
                            </div>
                        </div>
                        <!---
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-parcelas" class="control-label">Qt. Parcelas:</label>
                                <input type="number" class="form-control" name="condicao-parcelas" id="condicao-parcelas" placeholder="Parcelas, ex: 24" value="">
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-juros" class="control-label">Taxa de Juros:</label>
                                <input type="number" class="form-control" name="condicao-juros" id="condicao-juros" placeholder="Juros ex: 10% ou 1.5%" value="">
                            </div>
                        </div>-->
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-liquida" class="control-label">Liquida:</label>
                                <select name="condicao-liquida" id="condicao-liquida" class="form-control">
                                    <option value="S" selected>Sim</option>
                                    <option value="N">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-nfce" class="control-label">Código Pgto NFCe:</label>
                                <select name="condicao-nfce" id="condicao-nfce" class="form-control">
                                    <option value="01" selected>Dinheiro</option>
                                    <option value="17">Pix</option>
                                    <option value="02">Cheque</option>
                                    <option value="03">Cartão de Crédito</option>
                                    <option value="04">Cartão de Débito</option>
                                    <option value="05">Cartão da Loja</option>
                                    <option value="10">Vale Alimentação</option>
                                    <option value="11">Vale Refeição</option>
                                    <option value="12">Vale Presente</option>
                                    <option value="13">Vale Combustível</option>
                                    <option value="15">Boleto Bancário</option>
                                    <option value="16">Depósito Bancário</option>
                                    <option value="99">Outros</option>
                                    <option value="99">Sem Pagamento</option>                                    
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-prazo" class="control-label">Prazo Normal (Dias)</label>
                                <input type="number" class="form-control" name="condicao-prazo" id="condicao-prazo" placeholder="0" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="condicao-max-parcela" class="control-label">Qtde Máx. Parcela</label>
                                <input type="number" class="form-control" id="condicao-max-parcela" name="condicao-max-parcela" value="1" min="1" max="12" placeholder="Digite a quantidade máxima de parcelas">
                        </div>
</div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
    <label for="condicao-livro" class="control-label">Conta Corrente/Caixa</label>
    <select name="condicao-livro" id="condicao-livro" class="form-control">
        <option value="" selected>Selecione</option>
        <?php
        // Puxando do banco
        $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".livro_caixa_numero ORDER BY Descricao");
        $result = $consulta->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            // Se quiser já marcar algum selecionado
            $selected = ($row["Livro_Numero"] == $retorno["Num_Livro"]) ? "selected" : "";
            echo "<option value='{$row['Livro_Numero']}' $selected>{$row['Descricao']}</option>";
        }
        ?>
    </select>
</div>
 </div>

                        
                       
                        
                        <!---
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-livro" class="control-label">Livro Caixa:</label>
                                <select name="condicao-livro" id="condicao-livro" class="form-control">
                                    <option value="0" selected>Nenhum</option>
                                    <?php
                                    $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".livro_caixa_numero order by Descricao");
                                    $result = $consulta->fetchAll();

                                    foreach ($result as $row)
                                    {
                                        ?><option value="<?=$row["Livro_Numero"]?>"><?=$row["Descricao"]?></option><?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-resumo" class="control-label">Separar Resumo cx:</label>
                                <select name="condicao-resumo" id="condicao-resumo" class="form-control">
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="condicao-site" class="control-label">Exibir no Site:</label>
                                <select name="condicao-site" id="condicao-site" class="form-control">
                                    <option value="0" selected>Sim</option>
                                    <option value="-1">Não</option>
                                </select>
                            </div>
                        </div>-->
                        <div class="col-md-12" style="font-size:10px; color:red;">* Para incluir a quantidade de parcela e juros, primeiro gere o arquivo e depois clique em alterar</div>
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

<!-- Modal Alterar -->
<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;"
     data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">

            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div><!-- /.modal -->


<!-- Modal Excluir -->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir a condição? </h2>
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
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
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

<!--datatables-->
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
        var $_keyid = "_Nc00005";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "acaocondicaopgto_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
            });
    }

    function _incluir() {
        var $_keyid = "acaocondicaopgto_0001";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                $("#form-inclui :input").val("");
                _lista();
            });
    }

    function _lista() {
        var $_keyid = "acaocondicaopgto_0001";
        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#listagem").html(result);
                $('#datatable-responsive').DataTable();
            });
    }

    function _altera() {
        var $_keyid = "acaocondicaopgto_0001";
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
        var $_keyid = "acaocondicaopgto_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
            });
    }

    function refresh(id_parcela_cond) {
        var $_keyid = "acaocondicaopgto_0001";
        $.post("page_return.php", {_keyform:$_keyid, acao: 5 , id_parcela_cond:id_parcela_cond},
            function(result){
                $("#lista_parcelamento").html(result);
                _lista();
            });
    }

    function geparcela(id_parcela_cond){

        var $_keyid = "acaocondicaopgto_0001";
        var parcela_ini = $("#condicao_parcelas_ini").val();
        var parcela_fim = $("#condicao_parcelas_fim").val();

        var tx_normal = $("#tx_normal").val();
        var taxa_ant = $("#tx_antecipacao").val();
        var tx_cliente = $("#tx_cliente").val();
        var tipo_tx = $("#tipo_tx").val();
        

        var parc1 = parseInt(parcela_ini);
        var parc2 = parseInt(parcela_fim);
        if(parc1 <= parc2 && parcela_ini != "" && parcela_ini != ""){
            $("#lista_parcelamento").html('carregando...');
            $.post("page_return.php", {_keyform:$_keyid, acao: 6, parcela_ini:parcela_ini , parcela_fim:parcela_fim, tx_normal:tx_normal , taxa_ant:taxa_ant, tx_cliente:tx_cliente, id_parcela_cond:id_parcela_cond, tipo_tx:tipo_tx},function(result){
               //alert(result);
               setTimeout(function(){ refresh(id_parcela_cond); },1000);
               
            });
        }else{
            alert('parcelas invalida!');
        }


    }
    
    function clear_parcela(id_parcela_cond,id_parcela){

        var $_keyid = "acaocondicaopgto_0001";
        $("#lista_parcelamento").html('carregando...');
        $.post("page_return.php", {_keyform:$_keyid, acao: 7 , id_parcela_cond:id_parcela_cond, id_parcela:id_parcela},function(result){
            setTimeout(function(){ refresh(id_parcela_cond); },1000);
        });

    }


    //FORMATA MOEDA FINALIZA PAGAMENTO
	function moeda(a, e, r, t) {
		let n = ""
		  , h = j = 0
		  , u = tamanho2 = 0
		  , l = ajd2 = ""
		  , o = window.Event ? t.which : t.keyCode;
		if (13 == o || 8 == o)
			return !0;
		if (n = String.fromCharCode(o),
		-1 == "0123456789".indexOf(n))
			return !1;
		for (u = a.value.length,
		h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
			;
		for (l = ""; h < u; h++)
			-1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
		if (l += n,
		0 == (u = l.length) && (a.value = ""),
		1 == u && (a.value = "0" + r + "0" + l),
		2 == u && (a.value = "0" + r + l),
		u > 2) {
			for (ajd2 = "",
			j = 0,
			h = u - 3; h >= 0; h--)
				3 == j && (ajd2 += e,
				j = 0),
				ajd2 += l.charAt(h),
				j++;
			for (a.value = "",
			tamanho2 = ajd2.length,
			h = tamanho2 - 1; h >= 0; h--)
				a.value += ajd2.charAt(h);
			a.value += r + l.substr(u - 2, u)
		}
		return !1
	}

</script>

</body>
</html>