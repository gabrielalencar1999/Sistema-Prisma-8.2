<?php include("../../api/config/iconexao.php");
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >

<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();

$id = $_chaveid;

//PEGA NOME DO USUARIO
$stm = $pdo->prepare("select usuario_NOME from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '".$id."'");
$stm->execute();
foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
	$nome = $rst->usuario_NOME;
}

?>
<link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
<style>
	.boxDiv{
		border:1px solid #ecebeb;
		border-radius:8px;
		padding:20px;
	}
	h3{
		margin-top:25px;
	}
</style>
<div class="wrapper">
    <div class="container">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-xs-12">
                <h4 class="page-title m-t-15">Permissões de <b style="color:#00a8e6; text-decoration:underline"><?=$nome;?> [<?=$id;?>]</b></h4>
                <p class="text-muted page-title-alt">Configure para cada usuário os acesso aos setores do sistema.</p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <div class="panel-body">
						<?php include('permissao_lista.php');?>
                    </div>
                </div>
				<!--
                <div class="panel-footer">
                    <p class="text-danger">
                        Campos marcados com * são de preenchimento obrigatório.
                    </p>
                </div>-->
            </div>
        </div>
        <!-- end container -->
    </div>
</div>

<!-- Modal Retorno -->
<div id="modalResposta" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="result">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, processando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
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

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>
<script src="assets/plugins/switchery/js/switchery.min.js"></script>


<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>

<script type="text/javascript">
	
    $('#voltar').click(function() {
        var $_keyid = "tecnicoLista_00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    });

	function expandir(id){
		var name = "#"+id;	
		$(name).toggle( 1000, function() {});
	}
	
	function alt(div,valor){
		var nome = "#"+div;
		var inpt = $(nome).val();
		if(inpt == ""){
			$(nome).val(valor);	
		}else{
			$(nome).val("");		
		}

	}
	
    $('#form-permissao').submit(function (e){
        var $_keyid =   "permissao_00002";
        var dados = $(this).serializeArray();
        dados = JSON.stringify(dados);
		$("#modalResposta").modal('show');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                $("#modalResposta").html(result);
        });
    });	
	
    function _fechar() {
        var $_keyid = "tecnicoLista_00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    };	
</script>
</body>
</html>