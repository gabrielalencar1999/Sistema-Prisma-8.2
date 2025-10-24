<?php include("../../api/config/iconexao.php")?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php')?>
<div class="wrapper">
    <div class="container" >
   
   
                    <div class="row">
                        <div class="col-xs-6">
                            <h4 class="page-title m-t-15">Região</h4>
                            <p class="text-muted page-title-alt">Região Atendimento.</p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">
                                <button class="btn btn-warning waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-tranferir"> <i class="fa fa-random"></i> Tranferir Região</button>
                                <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir"><span class="btn-label"><i class="fa fa-plus"></i></span>Incluir</button>
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">            
                        <div class="col-md-6" >
                        <form role="form" action="javascript:void(0)" method="post" id="formpeca" name="formpeca" style="margin-bottom: 5px;">                        
                        <input type="hidden" id="idselreg" name="idselreg"  value="">
                                        <div class="form-group contact-search m-b-0">
                                            <input type="text" id="descfull"  name="descfull" class="form-control product-search"placeholder="Região, Bairro ou Nome do Técnico.">
                                            <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                                        </div> <!-- form-group -->
                                    </form>
                        </div>
                    
                    </div>
     
    <div class="row">
            <div class="col-md-9 col-xs-10">          
              
                        <div class="card-box table-responsive" id="grupo-lista" style="height:600px;  overflow-x: auto;"  >
                            <div class="bg-icon pull-request text-center">
                                <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                                <h2>Aguarde, carregando dados...</h2>
                            </div>
                        </div>
                
            </div>
            <div class="col-md-3 col-xs-2">
                <div class="card-box" id="grupo-listasel" >
                  
                          
                </div>
            </div>
    </div>
</div>

<!-- Modal Inclui-->
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-inclui" id="form-inclui">
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Região</h4>
            </div>
            <div class="modal-body">
               
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="grupo-descricao" class="control-label">Descrição:</label>
                                <input type="text" class="form-control" name="regiao-descricao" id="regiao-descricao" placeholder="Informe a descrição do Bairro ou Região" value="">
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="grupo-descricao" class="control-label">Composição</label>
                                <input type="text" class="form-control" name="regiao-composicao" id="regiao-composicao"  value="">
                            </div>
                        </div>
                    </div>
                            <div class="row">
                                <div class="col-md-12">
                                 <label>Assessor Técnico Externo</label>
                                                            <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' ORDER BY usuario_APELIDO");
                                                    $statement = $pdo->query($query);
                                                    $retorno = $statement->fetchall();
                                                   
                                                    ?>
                                                    <select name="regiao-tecnico" id="regiao-tecnico"   class="form-control " >
                                                        <option value=""> </option>
                                                        <?php
                                                         foreach ($retorno as $resultado) {
                                                            $descricao = $resultado["usuario_APELIDO"];
                                                            $codigo = $resultado["usuario_CODIGOUSUARIO"];

                                                            if ($codigo == $codigoTec) {
                                                                
                                                               
                                                        ?>
                                                                <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                                                                <?php } else {

                                                              ?>
                                                                    </option>
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                        <?php

                                                                }                                                         
                                                        }

                                                        ?>
                                                    </select>
                                                    </div>
                            </div>
                           
                        </div>
                        <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_incluir()">Salvar</button>
            </div>
            </div>
        
        </div>
    </div>

</form>

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
                    <h2>Deseja realmente excluir a região? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_excluir();">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<form  id="form5" name="form1" method="post" action="javascript:void(0)">
<div id="custom-modal-tranferir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-3x   md-forward"></i>
                    </div>
                    <h4>Seleção região para Transferir </h4>
                    <p>
                    <div class="row" >
                        <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Transferir DE </strong><select name="tecnicoDE" id="tecnicoDE" class="form-control input-sm">
                                                <option value="0">Selecione </option>
                                                <?php
                                                $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
                                                $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                $TotalReg = mysqli_num_rows($result);
                                                $codigoTec = $rst["Cod_Tecnico_Execucao"];

                                                while ($resultado = mysqli_fetch_array($result)) {
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
                                <div class="col-md-6">
                                            <div class="form-group">
                                            <strong> PARA</strong>  <select name="tecnicoPARA" id="tecnicoPARA" class="form-control input-sm">
                                                    <option value="0">Selecione </option>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    $TotalReg = mysqli_num_rows($result);
                                                    $codigoTec = $rst["Cod_Tecnico_Execucao"];
                                                    while ($resultado = mysqli_fetch_array($result)) {
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
                   <div class="row" >
                       
                       <div class="col-md-12">
                        ou
                       </div>
                   </div>
                   <div class="row" >
                       
                                <div class="col-md-12">
                                            <div class="form-group">
                                               TRANSFERIR REGIÃO SELECIONADA
                                            <strong> PARA</strong>  <select name="tecnicoPARAselecao" id="tecnicoPARAselecao" class="form-control input-sm">
                                                    <option value="0">Selecione </option>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' order by usuario_APELIDO ");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    $TotalReg = mysqli_num_rows($result);
                                                    $codigoTec = $rst["Cod_Tecnico_Execucao"];
                                                    while ($resultado = mysqli_fetch_array($result)) {
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
                   <div class="row" id="result_transferir">
                   </div>
                  
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-warning waves-effect waves-light"  onclick="_transferir()"> <i class="fa fa-random"></i> Tranferir Região</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

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

    function _buscadados(id) {
        $('#id-altera').val(id);
        var $_keyid = "acaoregiao_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
           
                $("#custom-modal-alterar").html(result);
        });
    }

    function _incluir() {
        var $_keyid = "acaoregiao_0001";
        var dados = $("#form-inclui :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
                $("#form-inclui :input").val("")
                _lista();
        });
    }
    $(formpeca).submit(function(){ //pesquisa os      
        _lista();
 });

    function _lista() {
        
        var $_keyid = "acaoregiao_0001";
        var dados = $("#formpeca :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 2},
            function(result){
              
                $("#grupo-lista").html(result);
                $('#datatable-responsive').DataTable(
                    {paging: false}
                    );
                    _listaTemp();
        });
    }

    function _listaTemp() {
        
     
        var $_keyid = "acaoregiao_0001";
        var dados = $("#formpeca :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#grupo-listasel');
        $.post("page_return.php", {_keyform:$_keyid, dados:dados, acao: 22},
            function(result){              
                $("#grupo-listasel").html(result);               
        });
    }


    
    function _newSel(_idsel) {
        $('#idselreg').val(_idsel);
     
        var $_keyid = "acaoregiao_0001";
        var dados = $("#formpeca :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#grupo-listasel');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 222},
            function(result){      
              
                _listaTemp();
        });
    }

    function _idexcluirTemp(_idsel) {
        $('#idselreg').val(_idsel);
        var $_keyid = "acaoregiao_0001";
        var dados = $("#formpeca :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#grupo-listasel');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2222},
            function(result){               
                _listaTemp();
        });
    }

    function _excluirTemp() {
       
        var $_keyid = "acaoregiao_0001";
        var dados = $("#formpeca :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#grupo-listasel');
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2220},
            function(result){               
                _listaTemp();
        });
    }
    

    function _altera() {
        var $_keyid = "acaoregiao_0001";
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
        var $_keyid = "acaoregiao_0001";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
       
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _lista();
        });
    }
    
    function _transferir() {
        var $_keyid = "acaoregiao_0001";
        var dados = $("#form5 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $("#result_transferir").html(result);
                _lista();
        });
    }

    function _carregandoA(_idmodal) {

        $(_idmodal).html('' +
            '<div class="bg-icon pull-request" >' +
            '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
         
            '</div>');

        }
    
</script>

</body>
</html>