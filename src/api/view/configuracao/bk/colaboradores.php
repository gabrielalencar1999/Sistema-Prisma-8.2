<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php')?>
<style>
.circle-image { 
  border-radius: 50%; 
  overflow: hidden; 
  width: 120px; 
  height: 120px; 
  margin-left:25%;
} 
.circle-image img { 
  width: 100%; 
  height: 100%; 
  margin-top:11px;
  
}
.abc{
    background-color:#fff;
    color:#333;
    font-size:14px;
    padding:6px;
    padding-right:1px;
    padding-left:1px;
    border:1px solid #eee;
    border-radius:4px;
    width:30px;
    text-align:center;
    margin-right:3px;
    float:left;
    cursor:pointer;
}
.abc:hover{
    background-color:#00A8E6;
    color:#FFF;
}
.abc-active{
    background-color:#417BFF;
    color:#FFF;
}
.bbox1{
    padding:10px;
    text-align:center;
}
.lal{
    position:absolute;
    right:18px;
    top:21px;
}
.lal2{
    font-size:12px;
    position:absolute;
    left:18px;
    top:21px;
}
.imag{
    border:1px solid #e3e3e3;
    border-radius:8px;
    padding-top:15px;
}
.tam{
    width:100%;
    height:140px; 
}
.categoria_title{
    margin-top:5px;
    font-size:16px;
    font-weight:bold;
    color:#444;
}

</style>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-6">
                    <h4 class="page-title m-t-15">Colaboradores</h4>
                    <p class="text-muted page-title-alt">Colaboradores/Beneficiários/Terceiros</p>
                </div>
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                        <button id="addToTable" class="btn btn-success waves-effect waves-light" onclick="_incluir()">Incluir <i class="fa fa-plus"></i></button>
                        <button id="addToTable" class="btn btn-default waves-effect waves-light" onclick="_fechar()">Fechar <i class="fa fa-remove"></i></button>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-xs-12" style="padding-bottom:15px; display:none;" id="abc">
                    <div class="abc abc-active" id="abc_a" onclick="alf('a')">A</div>
                    <div class="abc" id="abc_b" onclick="alf('b')">B</div>
                    <div class="abc" id="abc_c" onclick="alf('c')">C</div>
                    <div class="abc" id="abc_d" onclick="alf('d')">D</div>
                    <div class="abc" id="abc_e" onclick="alf('e')">E</div>
                    <div class="abc" id="abc_f" onclick="alf('f')">F</div>
                    <div class="abc" id="abc_g" onclick="alf('g')">G</div>
                    <div class="abc" id="abc_h" onclick="alf('h')">H</div>
                    <div class="abc" id="abc_i" onclick="alf('i')">I</div>
                    <div class="abc" id="abc_j" onclick="alf('j')">J</div>
                    <div class="abc" id="abc_k" onclick="alf('k')">K</div>
                    <div class="abc" id="abc_l" onclick="alf('l')">L</div>
                    <div class="abc" id="abc_m" onclick="alf('m')">M</div>
                    <div class="abc" id="abc_n" onclick="alf('n')">N</div>
                    <div class="abc" id="abc_o" onclick="alf('o')">O</div>
                    <div class="abc" id="abc_p" onclick="alf('p')">P</div>
                    <div class="abc" id="abc_q" onclick="alf('q')">Q</div>
                    <div class="abc" id="abc_r" onclick="alf('r')">R</div>
                    <div class="abc" id="abc_s" onclick="alf('s')">S</div>
                    <div class="abc" id="abc_t" onclick="alf('t')">T</div>
                    <div class="abc" id="abc_u" onclick="alf('u')">U</div>
                    <div class="abc" id="abc_v" onclick="alf('v')">V</div>
                    <div class="abc" id="abc_w" onclick="alf('w')">W</div>
                    <div class="abc" id="abc_x" onclick="alf('x')">X</div>
                    <div class="abc" id="abc_y" onclick="alf('y')">Y</div>
                    <div class="abc" id="abc_z" onclick="alf('z')">Z</div>
                    <div style="width:10%; float:left;">
                        <input type="text" class="form-control" placeholder="Nome Colaborador" style="height:35px;" id="nomeColaborador" name="nomeColaborador">
                    </div>
                    <button type="button" class="btn btn-default" style="float:left; margin-left:5px;" onclick="busca('','2')">Buscar</button>

                    <div style="width:8%; float:right; text-align:right; cursor:pointer;" id="vertudo" onclick="exe('0')">
                        <i class="fa fa-eye"></i> Ver Todos
                    </div>
                    <div style="width:8%; float:right; text-align:right; cursor:pointer; display:none" id="escondertudo" onclick="exe('1')">
                        <i class="fa fa-eye-slash"></i> esconder Todos
                    </div>                   
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box table-responsive" id="tecnico-listagem" style="min-height: 535px;">
                        <div class="bg-icon pull-request text-center">
                            <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                            <h2>Aguarde, carregando dados...</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="resultado-modal">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                    <h2>Aguarde, carregando dados...</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Historico -->
<div id="custom-modal-historico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <img src="assets/images/loading.gif" class="img-responsive center-block" width="120" alt="imagem de carregamento, aguarde.">
                    <h5>Aguarde, carregando dados...</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
    <input type="hidden" id="_chaveid" name="_chaveid"  value="">
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
        var $_keyid = "_Nc00005";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }


    function _incluir(){
        var $_keyid = "colaboradores_00002";
        $.post("page_return.php", {_keyform:$_keyid, acao:1},
            function(result){
                $("#custom-modal-historico").modal('show').html(result);
        });
        
    }

    function _lista() {
        var $_keyid = "colaboradores_00002";
        $.post("page_return.php", {_keyform:$_keyid, acao: 2},
            function(result){
                $("#tecnico-listagem").html(result);
                $('#datatable-responsive').DataTable();
                $("#abc").css("display","");
        });
    }

    function _alterar(id){
        var $_keyid = "colaboradores_00003";
        $('#_keyform').val($_keyid);
        $('#_chaveid').val(id);
        $('#form1').submit();

    }
    function fechar2(){
        $("#custom-modal-result").modal('hide');
        _lista();
    }
    
</script>

</body>
</html>