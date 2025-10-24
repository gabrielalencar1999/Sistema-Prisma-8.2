<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body>
<?php require_once('navigatorbar.php')?>
<style>
.box-categoria{
	-webkit-box-shadow: 0px 0px 19px 13px rgba(186,186,186,0.52);
	-moz-box-shadow: 0px 0px 19px 13px rgba(186,186,186,0.52);
	box-shadow: 0px 0px 19px 13px rgba(186,186,186,0.22); width:280px; height:400px; float:left; margin-left:10px; margin-bottom:10px; background-color:#FFF; padding:10px; text-align:center;
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
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Produtos</h4>
                <p class="text-muted page-title-alt">Cadastre e altere as Produtos</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_incluir('')"><span class="btn-label"><i class="fa fa-plus"></i></span>Incluir</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive" id="linha-listagem" style="background-color:#EBEFF2; padding:0px; border:0px;">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="custom-modal-alterar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="divAlt">
            <div class="modal-body  text-center">
                <div class="bg-icon pull-request" >
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

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "produto_00002";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $("#custom-modal-alterar").html(result);
        });
    }

    function _incluir() {
		 var $_keyid = "produto_00002";
		 $.post("page_return.php", {_keyform:$_keyid, acao:1},
            function(result){
                $("#divAlt").html(result);
          });
    }

    function _lista() {
        var $_keyid = "produto_00002";
        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#linha-listagem").html(result);
                $('#datatable-responsive').DataTable();
        });
    }

	
	function alt(id){
		//var nomeIcon = "#iconn"+id;
		var nomealt = "#alte"+id;
		
		//$(nomeIcon).css("display","none");
		$(nomealt).css("display","");
	}
	function off(id){
		//var nomeIcon = "#iconn"+id;
		var nomealt = "#alte"+id;
		
		//$(nomeIcon).css("display","");
		$(nomealt).css("display","none");
	}
	
	function alterarCategoria(id){
		 var $_keyid = "produto_00002";
		 $.post("page_return.php", {_keyform:$_keyid, _var:id, acao:3},
            function(result){
                $("#divAlt").html(result);
          });
	}

	function _addSub(id){
		 var $_keyid = "produto_00002";
		 $.post("page_return.php", {_keyform:$_keyid, _var:id, acao:4},
            function(result){
                $("#divAlt").html(result);
          });
	}

	function alterarSubCategoria(id){
		 var $_keyid = "produto_00002";
		 $.post("page_return.php", {_keyform:$_keyid, _var:id, acao:5},
            function(result){
                $("#divAlt").html(result);
          });
	}
	function excluirSubCategoria(id){
		 var $_keyid = "produto_00002";
		 $.post("page_return.php", {_keyform:$_keyid, _var:id, acao:6},
            function(result){
                $("#divAlt").html(result);
          });
	}
	
	function gerarSub(){
		
		var $_keyid =   "_Fl00008";
		var dados = $("#form3 :input").serializeArray();
		dados = JSON.stringify(dados);			
		$.post("page_return.php", {_keyform:$_keyid, dados:dados}, function(result){		
			if(result == 1){ }else{
				_lista();
				$("#divAlt").html(
				'	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p> Subcategoria incluída com <strong>Sucesso !!!</strong></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/></div></div></div>'
				);	
			}
		});
	}	

	function altSub(){
		
		var $_keyid =   "_C000034";
		var dados = $("#form3 :input").serializeArray();
		dados = JSON.stringify(dados);			
		$.post("page_return.php", {_keyform:$_keyid, dados:dados, acao:"alteraSubcategoria"}, function(result){		
			if(result == 1){ }else{
				_lista();
				$("#divAlt").html(
				'	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p> Subcategoria alterada com <strong>Sucesso !!!</strong></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/></div></div></div>'
				);	
			}
		});
	}

	function excluirSubcat(){
		
		var $_keyid =   "_C000034";
		var dados = $("#form3 :input").serializeArray();
		dados = JSON.stringify(dados);			
		$.post("page_return.php", {_keyform:$_keyid, dados:dados, acao:"excluirSubcategoria"}, function(result){		
			if(result == 1){ }else{
				_lista();
				$("#divAlt").html(
				'	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"></h4></div><div class="modal-body"><div class="col-sm-12" align="center"><p> Subcategoria deletada com <strong>Sucesso !!!</strong></p></div><div class="row"><div class="col-sm-12" align="center"><img src="assets/images/small/img_cadastro.jpg" alt="image" class="img-responsive " width="200"/></div></div></div>'
				);	
			}
		});
	}	

</script>

</body>
</html>