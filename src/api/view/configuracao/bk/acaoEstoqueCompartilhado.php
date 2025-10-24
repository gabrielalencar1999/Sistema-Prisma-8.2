<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


$dia       = date('d'); 
$mes       = date('m'); 
$ano       = date('Y'); 
$horaAgora = date("H:i:s");

$data_atual = $ano."-".$mes."-".$dia." ".$horaAgora ;


if ($acao["acao"] == 2) {
    $consultaEmp= $pdo->query("SELECT idref FROM info.consumidor WHERE CODIGO_CONSUMIDOR =  '".$_SESSION['CODIGOCLI']."'");
    $retornoEmp = $consultaEmp->fetch();
    $idsolicitante =  $retornoEmp['idref'];
    
    $filtro = " AND Nome_Consumidor LIKE '%".$_parametros['fornecedor-Nome_Consumidor']."%'";

    $SQL = "SELECT idref,UF,CIDADE,Nome_Consumidor,FONE_CELULAR,Contato_Tecnico 
     FROM info.consumidor        
     WHERE ".$_SESSION['CODIGOCLI']." <> CODIGO_CONSUMIDOR and ind_situacao = '1' $filtro ORDER BY Nome_Consumidor";

    $statement = $pdo->query("$SQL");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Estado</th>
            <th>Cidade</th>
            <th>Nome Fantasia</th>
            
            <th class="text-center">Telefones</th>
            <th class="text-center">Contato</th>
            <th class="text-center">Compartilhado</th>
            <th class="text-center">Ação </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            $sequencial++;

            //BUSCAR STATUS 
            $SQL = "SELECT eve_status,eve_loginautorizador,eve_loginsolicitante	 FROM bd_prisma.estoquebvinculoemp 
            WHERE eve_loginsolicitante =  '".$idsolicitante ."' and eve_loginautorizador = '".$row['idref']."'";  
              
            $consultaEmp= $pdo->query($SQL);
            $retornoEmp = $consultaEmp->fetch();
            if($consultaEmp->rowCount()== 0){
                $SQL = "SELECT eve_status,eve_loginautorizador	 FROM bd_prisma.estoquebvinculoemp 
                WHERE eve_loginautorizador=  '".$idsolicitante ."' and eve_loginsolicitante  = '".$row['idref']."'";  
                 
                $consultaEmp= $pdo->query($SQL);
                $retornoEmp = $consultaEmp->fetch();
            }

            $idSTATUS =  $retornoEmp['eve_status'];

            
                      ?>
            <tr class="gradeX" id="dv<?=$sequencial;?>">
                  <td><?=$row["UF"]?></td>
                 <td><?=$row["CIDADE"]?></td>
                <td><?=empty($row["Nome_Consumidor"]) ? "Não informado" : $row["Nome_Consumidor"]?></td>
               
                <td class="text-center"><?=empty($row["FONE_CELULAR"]) ? "Não informado" : $row["FONE_CELULAR"]?></td>
                <td class="text-center"><?=empty($row["Contato_Tecnico"]) ? "Não informado" : $row["Contato_Tecnico"]?></td>
              
               
                    <?php if($idSTATUS == 0) { ?>
                        <td class="actions text-center">
                         <span class="label label-table label-inverse">Não</span>
                        </td>
                        <td class="actions text-center">
                        <a href="#" class="on-default edit-row" onclick="_compartilhar(<?=$row['idref']?>,'dv<?=$sequencial;?>')"> <span class="label label-table label-info">Compartilhar e  Solicitar Autorização</span></a>                    
                        </td>
                    <?php }else{
                         if($idSTATUS == 1) { ?>
                                <td class="actions text-center">
                                     <span class="label label-table label-warning">Aguardando Aceite</span>
                                </td>

                         <?php }else{ ?>

                            <td class="actions text-center">
                                     <span class="label label-table label-success">Compartilhado</span>
                                </td>

                         <?php } ?>
                        
                       
                        <td class="actions text-center">
                            <?php if($idSTATUS == 1 and  $retornoEmp['eve_loginautorizador'] == $idsolicitante ) {?> <a href="#" class="on-default edit-row" onclick="_compartilharaceita(<?=$row['idref']?>,'dv<?=$sequencial;?>')"> <span class="label label-table label-success">Aceitar</span></a>  <?php } ?>
                        <a href="#" class="on-default edit-row" onclick="_cancelarcompartilhar('<?=$row['idref']?>','<?=$sequencial;?>')"> <span class="label label-table label-danger">Cancelar</span></a> 
                        </td>
                    <?php } ?>
                    
              
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
/*
 * Atualiza Linha
 * */
else if ($acao["acao"] == 3) {
   
        $_idempresasolicitada = $_parametros["id-altera"];
       
        try {
           
            $consultaEmp= $pdo->query("SELECT idref FROM info.consumidor WHERE CODIGO_CONSUMIDOR =  '".$_SESSION['CODIGOCLI']."'");
            $retornoEmp = $consultaEmp->fetch();
            $idsolicitante =  $retornoEmp['idref'];          
            
            $statement = $pdo->prepare("INSERT INTO bd_prisma.estoquebvinculoemp 
            (eve_datahora,eve_loginsolicitante,eve_loginautorizador	,eve_status) VALUES
            ('$data_atual',?,?,1)");
            $statement->bindParam(1, $idsolicitante);
            $statement->bindParam(2, $_idempresasolicitada);
            $statement->execute();
          
            $SQL = "SELECT idref, UF,CIDADE,Nome_Consumidor,FONE_CELULAR,Contato_Tecnico  FROM info.consumidor 
             WHERE idref = '$_idempresasolicitada' LIMIT 1";
       
            $statement = $pdo->query("$SQL");
            $retorno = $statement->fetchAll();

                foreach ($retorno as $row) {
                    $sequencial++;
                    ?>
                   
                        <td><?=$row["UF"]?></td>
                        <td><?=$row["CIDADE"]?></td>
                        <td><?=empty($row["Nome_Consumidor"]) ? "Não informado" : $row["Nome_Consumidor"]?></td>
                    
                        <td class="text-center"><?=empty($row["FONE_CELULAR"]) ? "Não informado" : $row["FONE_CELULAR"]?></td>
                        <td class="text-center"><?=empty($row["Contato_Tecnico"]) ? "Não informado" : $row["Contato_Tecnico"]?></td>
                        <td class="actions text-center">
                        <span class="label label-table label-warning">Aguardando Aceite</span>
                        </td>
                        <td class="actions text-center">
                            <a href="#" class="on-default edit-row" onclick="_cancelarcompartilhar('<?=$row['idref']?>','<?=$sequencial;?>')"> <span class="label label-table label-danger">Cancelar</span></a> 
                        </td>
                    
                    <?php
                }
            
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    
}
else if ($acao["acao"] == 5) {
   
    $_idempresasolicitada = $_parametros["id-altera"];
   
    try {
       
        $consultaEmp= $pdo->query("SELECT idref FROM info.consumidor WHERE CODIGO_CONSUMIDOR =  '".$_SESSION['CODIGOCLI']."'");
        $retornoEmp = $consultaEmp->fetch();
        $idsolicitante =  $retornoEmp['idref'];

        $statement = $pdo->prepare("UPDATE bd_prisma.estoquebvinculoemp  SET eve_status = '2'  
        WHERE         eve_loginsolicitante  = ? AND eve_loginautorizador = ? ");
        $statement->bindParam(1, $_idempresasolicitada);
        $statement->bindParam(2, $idsolicitante);
        $statement->execute();
      
        
        $statement = $pdo->prepare("INSERT INTO bd_prisma.estoquebvinculoemp 
        (eve_datahora,eve_loginsolicitante,eve_loginautorizador	,eve_status) VALUES
        ('$data_atual',?,?,2)");
        $statement->bindParam(1, $idsolicitante);
        $statement->bindParam(2,  $_idempresasolicitada);
        $statement->execute();
      
        $SQL = "SELECT idref, UF,CIDADE,Nome_Consumidor,FONE_CELULAR,Contato_Tecnico  FROM info.consumidor 
         WHERE idref = '$_idempresasolicitada' LIMIT 1";
   
        $statement = $pdo->query("$SQL");
        $retorno = $statement->fetchAll();

            foreach ($retorno as $row) {
                $sequencial++;
                ?>
               
                    <td><?=$row["UF"]?></td>
                    <td><?=$row["CIDADE"]?></td>
                    <td><?=empty($row["Nome_Consumidor"]) ? "Não informado" : $row["Nome_Consumidor"]?></td>
                
                    <td class="text-center"><?=empty($row["FONE_CELULAR"]) ? "Não informado" : $row["FONE_CELULAR"]?></td>
                    <td class="text-center"><?=empty($row["Contato_Tecnico"]) ? "Não informado" : $row["Contato_Tecnico"]?></td>
                    <td class="actions text-center">
                    <span class="label label-table label-success">Compartilhado</span>
                    </td>
                    <td class="actions text-center">
                        <a href="#" class="on-default edit-row" onclick="_cancelarcompartilhar('<?=$row['idref']?>','<?=$sequencial;?>')"> <span class="label label-table label-danger">Cancelar</span></a> 
                    </td>
                
                <?php
            }
        
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }

}
/*
 *cancelar Linha
 * */
else if ($acao["acao"] == 4) {
    $_idempresasolicitada = $_parametros["id-altera"];
       
    try {
       
        $consultaEmp= $pdo->query("SELECT idref FROM info.consumidor WHERE CODIGO_CONSUMIDOR =  '".$_SESSION['CODIGOCLI']."'");
        $retornoEmp = $consultaEmp->fetch();
        $idsolicitante =  $retornoEmp['idref'];
      
        
        $statement = $pdo->prepare("DELETE FROM bd_prisma.estoquebvinculoemp  WHERE eve_loginsolicitante = ? AND eve_loginautorizador = ?");
        $statement->bindParam(1, $idsolicitante);
        $statement->bindParam(2, $_idempresasolicitada);
        $statement->execute();

        $statement = $pdo->prepare("DELETE FROM bd_prisma.estoquebvinculoemp  WHERE eve_loginsolicitante = ? AND eve_loginautorizador = ?");
        $statement->bindParam(1, $_idempresasolicitada);
        $statement->bindParam(2, $idsolicitante);
        $statement->execute();
      
      
        
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}