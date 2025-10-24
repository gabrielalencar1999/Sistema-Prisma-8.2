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
                <h4 class="page-title m-t-15">Log Acesso</h4>
                <p class="text-muted page-title-alt">Registro de login </p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">   
                    <button  type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span> Filtros</button> 

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
                <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Período de </label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="date" class="form-control" name="_dataIni"  id="_dataIni" value="<?=$data_ini;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                 
                                                    <label for="field-1" class="control-label">Até </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                      
                                                        <input type="date" class="form-control"  name="_dataFim"  id="_dataFim" value="<?=$data_fim;?>">                                                   
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                        <label for="field-1" class="control-label">Usuário </label>
                                                    </div>  
                                                <div class="col-md-9">
                                                        <div class="form-group">
                                                            <select name="atendente" id="atendente" class="form-control input-sm" >
                                                                <option value=""> </option> 
                                                                        <?php
                                                                            $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where  usuario_ATIVO = 'Sim' ORDER BY usuario_APELIDO ");
                                                                            $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
                                                                        
                                                                        

                                                                        while($resultado = mysqli_fetch_array($result))
                                                                                {
                                                                                $descricao = $resultado["usuario_APELIDO"];
                                                                                $codigo = $resultado["usuario_CODIGOUSUARIO"];
                                                                                ?>   
                                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>                                                                          
                                                            </Select>
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

 

    function _lista() {
        
        var $_keyid = "logaceso_00002";
        var dados = $("#form-filtro :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#grupo-lista');
        $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 2},
            function(result){
              
                $("#grupo-lista").html(result);
                $('#datatable-responsive').DataTable();
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