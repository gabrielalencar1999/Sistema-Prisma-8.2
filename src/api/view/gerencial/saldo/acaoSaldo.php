<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

if ($_POST['acao'] == 1) { 
    
    $data1 = $_parametros["data1"];
    $data2 = $_parametros["data2"];
    $idEmpresa = $_parametros["idEmpresa"];
    $status = $_parametros["situacao"];

    if($data1 == ""){
        $data1 = date('Y-m-d');
    }
    if($data2 == ""){
        $data2 = date('Y-m-d');
    }
    if($idEmpresa != ""){
        $fil_empresa = "and arquivo_cliente = '$idEmpresa'";
    }

    if($status != ""){
        $fil_status = "and arquivo_status = '$status'";
    }
?>

    <div class="col-lg-12">
        <table class="table">
            <tr>
                <th>Situação</th>
                <th>Comprovante</th>
                <th>Data</th>
                <th>Tipo</th>
                <th>Cliente</th>
            </tr>
        <?php


            //

            $sql="select *,date_format(arquivo_entrada , '%d/%m/%Y %H:%i:%s') as dataTime from " . $_SESSION['BASE'] . ".arquivos_cliente where arquivo_data between '$data1' and '$data2' $fil_empresa $fil_status ";
            $stm = $pdo->prepare($sql);
            $stm->execute();
            if($stm->rowCount() > 0){
                while ($linha = $stm->fetch(PDO::FETCH_OBJ)){
                    $idR = $linha->id;
                    $dataHora = $linha->dataTime;
                    $nomeArquivo = $linha->arquivo_nomearquivo;
                    $diretorio = "../app/".$linha->arquivo_link."/".$nomeArquivo ;
                    $tipo = $linha->arquivo_tipo;
                    $idCliente = $linha->arquivo_cliente;
                    $sit = $linha->arquivo_status;
                    if($linha->arquivo_status == 0){
                        $situacao = "PENDENTE";
                    }
                    if($linha->arquivo_status == 1){
                        $situacao = "LIBERADO";
                    }
                    if($linha->arquivo_status == 2){
                        $situacao = "NEGADO";
                    }


                    $sql2="select * from " . $_SESSION['BASE'] . ".empresa_cadastro where id = '$idCliente'";
                    $stm2 = $pdo->prepare($sql2);
                    $stm2->execute();
                        while ($linha2 = $stm2->fetch(PDO::FETCH_OBJ)){
                            $empresa = $linha2->nome_fantasia;
                        }
                    
                    ?>

                        <tr>
                            <td>
                                <select class="form-control input-sm" id="sit" name="sit" onchange="alteraSit(this.value,'<?=$idR;?>');">
                                    <option value="0" <?php if($sit == 0){ echo 'selected';}?>>Pendente</option>
                                    <option value="1" <?php if($sit == 1){ echo 'selected';}?>>Liberado</option>
                                    <option value="2" <?php if($sit == 2){ echo 'selected';}?>>Negado</option>
                                </select>
                            </td>
                            <td><a href="<?=$diretorio;?>" target="_blank"><i class="fa fa-file"></i> <?=$nomeArquivo;?></td>
                            <td><?=$dataHora;?></td>
                            <td><?=$tipo;?></td>
                            <td><?=$idCliente;?> - <?=$empresa;?></td>                                      
                        </tr>
                    
                    <?php
                }
            }else{
                ?>
                    <tr>
                        <td colspan="5"><h5>Nenhum registro encontrado</h5></td>
                    </tr>
                <?php
            }
        ?>
        </table>
    </div>

<?php }
if ($_POST['acao'] == 2) {
    $status = $_POST['status'];
    $idEmpresa = $_POST['idEmpresa'];

    $sql="update " . $_SESSION['BASE'] . ".arquivos_cliente set arquivo_status = '$status' where id = '$idEmpresa'";
    $stm = $pdo->prepare($sql);
    $stm->execute();

    echo("Alterado com sucesso!");
}


