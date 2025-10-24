<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');



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
      
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".avisos WHERE av_id = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Aviso</h4>                  
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label  class="control-label">Referência:</label>
                                    <input type="text" class="form-control" name="aviso_ref" id="aviso_ref" value="<?=$retorno["av_assunto"]?>">   
                                    <input type="hidden" class="form-control" name="aviso-id" id="aviso-id" value="<?=$retorno["av_id"]?>">  
                                                                
                                   
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label  class="control-label">Título:</label>
                                    <input type="text" class="form-control" name="aviso_titulo" id="aviso_titulo" value="<?=$retorno["av_titulo"]?>">
                                   
                                </div>
                            </div>
                        </div>  
                          
                       
                        <div class="row">
                            <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">  
                                                <label>Data Início</label>                                                     
                                                <input type="date" class="form-control" name="_dataIni"  id="_dataIni" value="<?=$retorno["av_dtinicio"];?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">  
                                                <label>Final</label>                                                     
                                                <input type="date" class="form-control" name="_dataFim"  id="_dataFim" value="<?=$retorno["av_dtfim"];?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="fotosdetalhe">
                                        <?php
                                            $_imagem = trim($retorno["av_imagem"]);
                                    ?>
                                        <img src="data:image/png;base64,<?=$_imagem;?>" class="img-responsive img-thumbnail" width="200" /> 
                                        <textarea  name="aviso-imagem" id="aviso-imagem"  class="form-control" style="display: none;"><?=$retorno["av_imagem"]?></textarea>
                                        </div>
                                    </div>
                                        
                                            
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label f class="control-label">Texto da Mensagem:</label>
                                    <textarea  name="textoaviso" id="textoaviso"  class="form-control" rows="8"><?=$retorno["av_texto"]?></textarea>
                                </div>
                            </div>
                        </div>   
                           
                     
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label  class="control-label">Anexar Arquivo:<code>Imagem (850px X 500px)</code></label>
                                    <input type="file" class="filestyle" name="arquivo-anexo" id="arquivo-anexo" accept="x-png,image/gif,image/jpeg,image/png" data-placeholder="Sem arquivos">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-white waves-effect" onclick="uploadImage()">Carregar Imagem</button>
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
 * Cadastra nova mensagem
 * */
else if ($acao["acao"] == 1) {
  
        try {

            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".avisos (av_assunto , av_titulo, av_texto, av_imagem , av_dtinicio ,av_dtfim,av_data  ) VALUES(?,?,?,?,?,?,current_date())");
            $statement->bindParam(1, $_parametros["aviso_ref"]);
            $statement->bindParam(2, $_parametros["aviso_titulo"]);
            $statement->bindParam(3, $_parametros["textoaviso"]);
            $statement->bindParam(4, $_parametros["aviso-imagem"]);        
            $statement->bindParam(5, $_parametros["_dataIni"]);   
            $statement->bindParam(6, $_parametros["_dataFim"]);    
            
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                           
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
/*
 * Lista
 * */
else if ($acao["acao"] == 2) {
    $statement = $pdo->query("SELECT av_id,av_assunto,av_titulo, DATE_FORMAT(av_dtinicio,'%d/%m/%Y') AS datainicial, DATE_FORMAT(av_dtfim,'%d/%m/%Y') AS datafinal  FROM ". $_SESSION['BASE'] .".avisos ORDER BY av_dtinicio DESC");
    $retorno = $statement->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Referência</th>
                <th>Título da Mensagem</th>
                <th>Data Início</th>
                <th>Data Final</th>
                <th class="text-right">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst) {
        ?>
            <tr class="gradeX">
                <td><?=($rst["av_assunto"])?></td>
                <td><?=($rst["av_titulo"])?></td>
                <td><?=($rst["datainicial"])?></td>
                <td><?=($rst["datafinal"])?></td>                
                <td class="actions text-right">
                    <a href="#" class="on-default edit-row" style="margin: 10px;" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$rst["av_id"]?>)"><i class="fa fa-pencil "></i></a>
                    <?php if($rst["av_id"] > 1) { ?>
                            <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst["av_id"];?>)"><i class="fa fa-trash-o "></i></a>
                    <?php } ?>                   
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
  
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".avisos SET av_assunto = ?, av_titulo = ?, av_texto = ?, av_imagem = ?, av_dtinicio = ?,av_dtfim = ? WHERE 	av_id = ?");
            $statement->bindParam(1, $_parametros["aviso_ref"]);
            $statement->bindParam(2, $_parametros["aviso_titulo"]);
            $statement->bindParam(3, $_parametros["textoaviso"]);
            $statement->bindParam(4, $_parametros["aviso-imagem"]);   
            $statement->bindParam(5, $_parametros["_dataIni"]);   
            $statement->bindParam(6, $_parametros["_dataFim"]);       
            $statement->bindParam(7, $_parametros["aviso-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                           
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Mensagem Atualizada! 
                            </h2>
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
 * Exclui 
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".avisos WHERE av_id = :id");
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

/*
 * qr-code
 * */
else if ($acao["acao"] == 5) {
    try {
        $_parametros["id-exclusao"] = "";
  
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