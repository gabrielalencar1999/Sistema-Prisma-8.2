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

/*
 * Chama modal altera
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".msg_whats WHERE whats_id = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Nova Mensagem</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Título:</label>
                                    <input type="text" class="form-control" name="whats_titulo" id="whats_titulo" value="<?=$retorno["whats_titulo"]?>">
                                    <input type="hidden" name="whats-id" id="whats-id" value="<?=$retorno["whats_id"]?>">
                                </div>
                            </div>
                          
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label  class="control-label">Ativo:</label>
                                    <select name="ativo-whats" id="ativo-whats" class="form-control">
                                                <option value="1"<?=$retorno["whats_ativo"] == "1" ? "selected" : ""?>>Sim</option>
                                                <option value="0"<?=$retorno["whats_ativo"] == "0" ? "selected" : ""?>>Não</option>
                                            </select>                                   
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label  class="control-label">Open Ticket:</label>
                                    <select name="openticket-whats" id="openticket-whats" class="form-control">
                                                <option value="1"<?=$retorno["dontOpenTicket"] == "1" ? "selected" : ""?>>Sim</option>
                                                <option value="0"<?=$retorno["dontOpenTicket"] == "0" ? "selected" : ""?>>Não</option>
                                            </select>                                   
                                </div>
                            </div>
                                <div class="col-md-2">
                                <div class="form-group">
                                    <label  class="control-label">Id Template:</label>
                                    <input type="hidden" class="form-control" name="whats-qtde" id="whats-qtde" value="<?=$retorno["whats_maxenvio"]?>">   
                                    <input type="text" class="form-control" name="whats-template" id="whats-template" value="<?=$retorno["msg_template"]?>">                         
                                </div>
                            </div>
                        </div>
                   
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label f class="control-label">Mensagem:</label>
                                    <textarea  name="textowats" id="textowats"  class="form-control" rows="16"><?=$retorno["whats_mensagem"]?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6"  style="margin-left: 10px;">
                                        <h5 class="m-t-20"><b>TAGS: </b>   
                                        </h5>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-md-3" style="margin-left: 10px;">                                                  
                                
                                <code>[NOME]</code>
                                <code>[ENDERECO]</code>
                                <code>[COMPLEMENTO]</code>
                                <code>[BAIRRO]</code>					
                                <code>[CPFCNPJ]</code>
                                <code>[CIDADE]</code>
                                <code>[UF]</code>
                                <code>[DDD]</code>
                                <code>[EMAIL]</code>
                                <code>[FONES]</code>
                                <code>[FONECELULAR1]</code>
                                <code>[FONECELULAR2]</code>
                                <code>[FONEFIXO]</code>
                               
                                </div>
                                <div class="col-md-3">
                                    <code>[NUMEROOS]</code>
                                    <code>[PRODUTO]</code>
                                    <code>[DTATENDIMENTO]</code>
                                    <code>[NOMEATENDENTE]</code>
                                    <code>[NOMETECNICO]</code>
                                    <code>[DEFEITORECLAMADO]</code>
                                    <code>[DEFEITOCOSTATADO]</code>
                                    <code>[SERVICOEXECUTADO]</code>
                                    <code>[OBSERVACAO]</code>
                                    <code>[MODELO]</code>			
                                    <code>[SERIE]</code>
                                    <code>[MARCA]</code>
                                    <code>[HORARIOATENDIMENTO]</code>
                                    <code>[VLRSERVICOS]</code>
                                    <code>[VLRPECAS]</code>
                                    <code>[TOTAL]</code>
                                    <code>[TOTALDESCONTO]</code>
                                 <code>[DESCRICAOPECAS]</code>     
                                    <code>[DETALHAMENTO_ORCAMENTO]</code>       
                                    <code>[LINKNFSE]</code>  
                            
                                </div>
                                <div class="col-md-3">                               
                                    <code>[EMPRESANOME]</code>
                                    <code>[EMPRESATELEFONE]</code>
                                                    
                                </div>
                            </div>
                            </div>
                        </div>
                        <?php /*
                     
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label  class="control-label">Anexar Arquivo:<code>Imagem,Video,Documento</code></label>
                                    <input type="file" class="filestyle" name="arquivo-anexo" id="arquivo-anexo" accept="text/xml,x-png,image/gif,image/jpeg,application/pdf,application/vnd.ms-excel" data-placeholder="Sem arquivos">
                                </div>
                            </div>
                            <div class="col-sm-1" style="padding-top: 25px;">
                                <button type="button" class="btn btn-info waves-effect waves-light" onclick="_newAnexoEnvio()">Anexar</button>
                            </div>
                        </div>
                       */?>
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
 * Cadastra nova mensagem
 * */
else if ($acao["acao"] == 1) {
   
    if ( empty($_parametros["whats_titulo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                       
                        <h2>Preencha campos título!</h2>
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
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".msg_whats (whats_titulo,whats_mensagem,whats_ativo,whats_maxenvio,dontOpenTicket,msg_template) VALUES(?,?,?,?,?,?)");
            $statement->bindParam(1, $_parametros["whats_titulo"]);
            $statement->bindParam(2, $_parametros["textowats"]);
            $statement->bindParam(3, $ativo);     
            $statement->bindParam(4, $_parametros["whats-qtde"]);
            $statement->bindParam(5, $_parametros["openticket-whats"]);    
            $statement->bindParam(6, $_parametros["whats-template"]);    
                
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Mensagem Cadastrada!</h2>
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
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".msg_whats ORDER BY whats_titulo");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Título</th>
                <th>Ativo</th>
                <th>Open Ticket</th>
                <th>Template</th>
                
                <th class="text-right">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst) {
        ?>
            <tr class="gradeX">
                <td><?=($rst["whats_titulo"])?></td>
                <td class="text-center">
                    <span class="label label-table label-<?=$rst["whats_ativo"] == "1" ? "success" : "inverse" ?>"><?=$rst["whats_ativo"] == "1" ? "Sim" : "Não"?></span>
                </td>
                <td class="text-center">
                    <span class="label label-table label-<?=$rst["dontOpenTicket"] == "1" ? "success" : "inverse" ?>"><?=$rst["dontOpenTicket"] == "1" ? "Sim" : "Não"?></span>
                </td>
                <td><?=($rst["msg_template"])?></td>
               
                
                <td class="actions text-right">
                    <a href="#" class="on-default edit-row" style="margin: 10px;" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$rst["whats_id"]?>)"><i class="fa fa-pencil "></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst["whats_id"];?>)"><i class="fa fa-trash-o "></i></a>
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
 * Atualiza 
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["whats_titulo"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Preencha campos título!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".msg_whats SET whats_mensagem = ?, whats_maxenvio = ? , whats_ativo = ? ,  whats_titulo = ?, dontOpenTicket = ? , msg_template = ? WHERE whats_id = ?");
            $statement->bindParam(1, $_parametros["textowats"]);
            $statement->bindParam(2, $_parametros["whats-qtde"]);
            $statement->bindParam(3, $_parametros["ativo-whats"]);
            $statement->bindParam(4, $_parametros["whats_titulo"]);
            $statement->bindParam(5, $_parametros["openticket-whats"]);
            $statement->bindParam(6, $_parametros["whats-template"]);          
            $statement->bindParam(7, $_parametros["whats-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Mensagem Atualizada!</h2>
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
 * Exclui 
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".msg_whats WHERE whats_id = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Mensagem Excluída!</h2>
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