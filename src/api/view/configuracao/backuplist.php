<?php 
use Functions\Validador; 

?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php require_once('navigatorbar.php')?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">Backup</h4>
                <p class="text-muted page-title-alt">Lista de Backup.</p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">        
                             <?php
                        // Buscar registros
                     // pega o código do cliente logado
                  //  $codigoCli = Validador::sanitizaValor($_SESSION['CODIGOCLI']);
                    $codigoCli = ($_SESSION['CODIGOCLI']);
                    if($codigoCli == 9000) {  ?><span id="resulte">
                     <button id="bk" type="button" class="btn btn-danger waves-effect waves-light" onclick="_bkp()">Gerar</button>     
                     </span>
                     <?php } ?>
                    <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-box table-responsive" id="listagem">
                    <div class="bg-icon pull-request text-center">
                        <?php
                        // Buscar registros
                     // pega o código do cliente logado
                  //  $codigoCli = Validador::sanitizaValor($_SESSION['CODIGOCLI']);
                 
                    if($codigoCli == 9000) {
                        $sql = "SELECT bk_id, bk_data, bk_descricao, bk_caminho,bk_login  
                                                FROM aquivo_backup                             
                                                ORDER BY bk_data DESC";

                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([$codigoCli]);

                    }else{
                        $sql = "SELECT bk_id, bk_data, bk_descricao, bk_caminho,bk_login  
                                                FROM aquivo_backup 
                                                WHERE bk_login = ?  
                                                ORDER BY bk_data DESC";

                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([$codigoCli]);
                    }
                  
                    $backups = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <div style="padding:20px;">
                            <h2>Lista de Backup</h2>
                            <table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse;">
                                <thead style="background:#f0f0f0;">
                                    <tr>
                                       <th><div style="text-align: center">Cod.Acesso</div> </th>
                                        <th><div style="text-align: center">Data Geração</div> </th>
                                        <th><div style="text-align: center">Descrição</div></th>
                                        <th><div style="text-align: center">Download</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backups as $bk): ?>
                                        <tr>
                                             <td><?= htmlspecialchars($bk['bk_login']) ?></td>
                                            <td><?= date("d/m/Y H:i", strtotime($bk['bk_data'])) ?></td>
                                            <td><?= htmlspecialchars($bk['bk_descricao']) ?></td>
                                            <td>
                                                <a href="<?= htmlspecialchars($bk['bk_caminho']) ?>" 
                                                download
                                                style="color:blue; text-decoration:underline;">
                                                Baixar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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

        function _bkp() {
                var $_keyid = "bk_00002";            
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                 $("#resulte").html("executando ..");     
                           $.post("page_return.php", {_keyform:$_keyid,dados:dados},
                    function(result){    
                        $("#resulte").html(result);                     
                });
    }





</script>

</body>
</html>