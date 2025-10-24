<?php

use Database\MySQL;

$pdo = MySQL::acessabd();
use Functions\Validador;

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


/*
 * Chama modal altera
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".almoxarifado WHERE Codigo_Almox = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Almoxarifado</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="almoxarifado-descricao" class="control-label">Descrição:</label>
                                    <input type="text" class="form-control" name="almoxarifado-descricao" id="almoxarifado-descricao" value="<?=$retorno["Descricao"]?>">
                                    <input type="hidden" name="almoxarifado-id" id="almoxarifado-id" value="<?=$retorno["Codigo_Almox"]?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="almoxarifado-descricao" class="control-label">Ativo:</label>
                                    <select name="ativo-almox" id="ativo-almox" class="form-control">
                                                <option value="1"<?=$retorno["almox_ativo"] == "1" ? "selected" : ""?>>Sim</option>
                                                <option value="0"<?=$retorno["almox_ativo"] == "0" ? "selected" : ""?>>Não</option>
                                            </select>                                   
                                </div>
                            </div>
                        </div>
                 
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label">Usuário:</label>                                    
                                    <select name="user-almox" id="user-almox" class="form-control">
                                             <option value="0"> - </option>
                                            <?php
                                            //VERIFICAR SE EXISTE DEFINIÇÃO ALMOXARIFADO PARA USUARIO 
                                            $sqluser = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO FROM ".$_SESSION['BASE'].".usuario 
                                            WHERE usuario_ATIVO  = 'Sim' ORDER BY usuario_APELIDO asc";                                            
                                            $stmuser = $pdo->prepare($sqluser);
                                            $stmuser->execute();
                                            if($stmuser->rowCount() > 0) {
                                              
															foreach($stmuser->fetchAll(PDO::FETCH_ASSOC) as $value){
																$codigoSit = $value["usuario_CODIGOUSUARIO"];
																$descricaoSit = $value["usuario_APELIDO"];
															?><option value="<?php echo "$codigoSit"; ?>" > <?php echo "$descricaoSit"; ?></option>
															<?php
															}
                                                        	}	?>                   
                                    </select>                                   
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                   
                                    <button type="button" style="margin-top: 25px;" class="btn btn-purple waves-effect waves-light"  onclick="_adduser()">Adicionar Usuário</button>                                
                                </div>
                            </div>
                        </div>
                          
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                  Usuários Vinculados para Venda/Requisição                            
                                </div>
                                <div class="form-group" id='listuser'>
                                    <?php 
                                //VERIFICAR SE EXISTE DEFINIÇÃO ALMOXARIFADO PARA USUARIO 
									$sqluser = "Select au_id,usuario_APELIDO FROM ".$_SESSION['BASE'].".almoxarifado_user 
									LEFT JOIN  ".$_SESSION['BASE'].".almoxarifado ON Codigo_Almox = au_almoxid
                                    LEFT JOIN ".$_SESSION['BASE'].".usuario ON au_user = usuario_CODIGOUSUARIO
									WHERE au_almoxid  = '".$retorno["Codigo_Almox"]."' ";									
									$stmuser = $pdo->prepare($sqluser);
									$stmuser->execute();
									if($stmuser->rowCount() > 0) {  
                                      
                                      }  ?>
                                      <div>
                                        <table class="table table-striped m-0">                                          
                                            <tbody><?php
                                            foreach($stmuser->fetchAll(PDO::FETCH_ASSOC) as $value){
                                                    $codigoSit = $value["au_id"];
                                                    $descricaoSit = $value["usuario_APELIDO"];
                                                    ?>
                                                    <tr>                                                 
                                                    <td><?=$descricaoSit;?></td>
                                                    <td><a href="#" class="on-default remove-row"  onclick="_idexcluiruser(<?=$codigoSit;?>)"><i class="fa fa-trash-o"></i></a></td>
                                                   
                                                </tr>
                                        <?php  } ?>
                                                
                                              
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_altera()">Salvar</button>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}
/*
 * Cadastra Almoxarifado
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["almoxarifado-descricao"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a descrição do almoxarifado!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $ativo = 1;
            //$_parametros["almoxarifado-descricao"] = Validador::sanitizaValor($_parametros["almoxarifado-descricao"],'');
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".almoxarifado (Descricao,almox_ativo) VALUES(?,?)");
            $statement->bindParam(1, Validador::sanitizaValor($_parametros["almoxarifado-descricao"],''));
          //  $statement->bindParam(1, $_parametros["almoxarifado-descricao"]);
            $statement->bindParam(2, $ativo);           
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Almoxarifado Cadastrado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Lista Almoxarifados
 * */
else if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".almoxarifado ORDER BY Descricao");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Ativo</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst) {
        ?>
            <tr class="gradeX">
                <td><?=($rst["Descricao"])?></td>
                <td class="text-center">
                    <span class="label label-table label-<?=$rst["almox_ativo"] == "1" ? "success" : "inverse" ?>"><?=$rst["almox_ativo"] == "1" ? "Sim" : "Não"?></span>
                </td>
                <td class="actions text-center">
                    <a href="#" class="on-default edit-row"  style="margin: 10px;" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$rst["Codigo_Almox"]?>)"><i class="fa fa-pencil"></i></a>
                    
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst["Codigo_Almox"];?>)"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
/*
 * Atualiza Almoxarifado
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["almoxarifado-descricao"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a descrição do almoxarifado!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".almoxarifado SET Descricao = ?, almox_ativo = ? WHERE Codigo_Almox = ?");
            $statement->bindParam(1, $_parametros["almoxarifado-descricao"]);
            $statement->bindParam(2, $_parametros["ativo-almox"]);
            $statement->bindParam(3, $_parametros["almoxarifado-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Almoxarifado Atualizado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Exclui Almoxarifado
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".almoxarifado WHERE Codigo_Almox = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Almoxarifado Excluído!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}


/*
 *  usuario vinculados ao  Almoxarifado 
 * */
else if ($acao["acao"] == 5) {
    try {
    
       //VERIFICAR SE EXISTE DEFINIÇÃO ALMOXARIFADO PARA USUARIO 

       //verificar se é para incluir ou excluir

       if($_parametros['almoxuserF'] != ""){
        
        $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".almoxarifado_user(au_almoxid,au_user) values ( :id,:user)");
        $statement->bindParam(':id', $_parametros["id-altera"]);
        $statement->bindParam(':user', $_parametros["almoxuserF"]);
        $statement->execute();
       }else{
      
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".almoxarifado_user WHERE au_id = :id");
        $statement->bindParam(':id', $_parametros["id-exclusaouser"]);
        $statement->execute();
       }

       $sqluser = "Select au_id,usuario_APELIDO FROM ".$_SESSION['BASE'].".almoxarifado_user 
       LEFT JOIN  ".$_SESSION['BASE'].".almoxarifado ON Codigo_Almox = au_almoxid
       LEFT JOIN ".$_SESSION['BASE'].".usuario ON au_user = usuario_CODIGOUSUARIO
       WHERE au_almoxid  = '".$_parametros["id-altera"]."' ";			
    					
       $stmuser = $pdo->prepare($sqluser);
       $stmuser->execute();
       if($stmuser->rowCount() > 0) {  
         
         }  ?>
         <div>
           <table class="table table-striped m-0">                                          
               <tbody><?php
               foreach($stmuser->fetchAll(PDO::FETCH_ASSOC) as $value){
                       $codigoSit = $value["au_id"];
                       $descricaoSit = $value["usuario_APELIDO"];
                       ?>
                       <tr>                                                 
                       <td><?=$descricaoSit;?></td>
                       <td><a href="#" class="on-default remove-row"  onclick="_idexcluiruser(<?=$codigoSit;?>)"><i class="fa fa-trash-o"></i></a></td>
                      
                   </tr>
           <?php  } ?>
                   
                 
               </tbody>
           </table>
       </div>
       <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}