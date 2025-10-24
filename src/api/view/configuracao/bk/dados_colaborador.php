<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >

<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();
$id = $_chaveid;
//echo("<br><br><br><Br><br><br>xxxxxx $id");

//busca informações caso for alterar
$sql="select * from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '$id'";
$stm = $pdo->prepare($sql);
$stm->execute();
foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
   
    $nome = $rst->usuario_NOME;
    $email = $rst->usuario_LOGIN;
    
}
//busca status colaborador
$sql="select * from " . $_SESSION['BASE'] . ".colaborador where colaborador_usuario = '$id' and colaborador_empresa = '".$_SESSION['BASE_ID']."'";
$stm = $pdo->prepare($sql);
$stm->execute();
foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
   
    $status = $rst->colaborador_status;
    
}
?>
<style>
.boxDiv{
    border:1px solid #ecebeb;
    border-radius:8px;
    padding:20px;
}    
.circle-image { 
  border-radius: 50%; 
  overflow: hidden; 
  width: 100px; 
  height: 100px; 
  margin-left:24%;
} 
.circle-image img { 
  display: block;
  margin-left: auto;
  margin-right: auto;
  margin-top:8px;
  
}

.circle-image2 { 
  overflow: hidden; 
  width: 100%; 
  height: 100px; 
}
.circle-image2 img { 
  display: block;
  margin-left: auto;
  margin-right: auto;
 
}
.margintop{
    margin-top:3%;
}
.bbox1{
    padding:10px;
    text-align:center;
    color:#FFF;
}
.lal{
    position:absolute;
    left:18px;
    top:21px;
}
.lal2{
    font-size:12px;
    position:absolute;
    right:18px;
    top:21px;
}
.imag{
    border:1px solid #e3e3e3;
    border-radius:8px;
    padding-top:5px;
}
.tam{
    width:100%;
    height:120px; 
}
.categoria_title{
    margin-top:5px;
    font-size:16px;
    font-weight:bold;
    color:#FFF;
}

.clicker{
    color:#FFF;
    cursor:pointer;
    font-size:18px;
}
.clicker:hover{
    color:#333;
}

</style>
<div class="wrapper">
    <div class="container">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-xs-12">
                <h4 class="page-title m-t-15">Dados Colaborador</h4>
                <p class="text-muted page-title-alt"></p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <div class="panel-body">
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-21" data-toggle="tab" aria-expanded="true">Dados Colaborador</a>
                            </li>  
                            <li class="">
                                <a href="#navpills-51" data-toggle="tab" aria-expanded="false">Permissões</a>
                            </li>                          						                        

                        </ul>                      
                            <div class="tab-content br-n pn">		
                            <div id="navpills-21" class="tab-pane active">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="nome"> Nome<strong class="text-danger"></strong>:</label>
                                            <input type="text" name="nome" id="nome" class="form-control" value="<?=$nome;?>" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="email">E-mail<strong class="text-danger"></strong></label>
                                            <input type="text" name="email" class="form-control" id="email" value="<?=$email;?>" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="nascimento">Situação<strong class="text-danger"></strong></label>
                                            <select class="form-control" id="status" name="status" onchange="alteraSit(this.value,'<?=$id;?>')">
                                                <option value="-1" <?php if($status == "-1"){ echo 'selected';} ?>>Ativo</option>
                                                <option value="0" <?php if($status == "0"){ echo 'selected';} ?>>Inativo</option>
                                            </select>
                                        </div>                                                                                                                 
                                    </div>
                                </div>
                                <!------------------Endereco----------------------------------------------------------------------------------------------------------------------->
                                <div id="navpills-51" class="tab-pane">
                                    <?php include('permissao_lista.php');?>
                                </div>                           
                                <!---------------acao----------------------------------------------------------------------------------------------------------------------------------------->                    
                                
                            </div>
                        
                    </div>
                </div>
                <div class="panel-footer">
                    <p class="text-danger">
                        
                    </p>
                </div>
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

    $('#form-permissao').submit(function (e){
        var $_keyid =  "permissao_00002";
        var dados = $(this).serializeArray();
        dados = JSON.stringify(dados);
		$("#modalResposta").modal('show');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados},
            function(result){
                $("#modalResposta").html(result);
        });
    });	


    $('#voltar').click(function() {
        var $_keyid = "colaboradores_00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    });

    function _fechar() {
        var $_keyid = "colaboradores_00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    };

    function alteraSit(valor,user){
        var $_keyid =  "colaboradores_00002";
        $("#modalResposta").modal('show');
        $.post("page_return.php", {_keyform:$_keyid, user:user, _var:valor, acao:3},
            function(result){
                $("#modalResposta").html(result);
        });
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
    function expandir(id){
		var name = "#"+id;	
		$(name).toggle( 1000, function() {});
	}
</script>
</body>
</html>