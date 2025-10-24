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


$descricao = $_parametros['descricao'];
$_fabricante = $_parametros['nf-fornecedor'];
$_linha= $_parametros['modelo-linha'];
$_produto = $_parametros['modelo-produto'];
/*
 * Chama modal alterar
 * */
if ($acao["acao"] == 0) {
    try {
      
       
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho 
        left JOIN ". $_SESSION['BASE'] .".aparelho_produto on  ap_prodId  = aparelho_codProduto	
        left JOIN ". $_SESSION['BASE'] .".aparelho_linha on  ap_linhaId  = ap_prodLinha	
        WHERE CODIGO_APARELHO = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        $idlinha =  $retorno["ap_prodLinha"];
        $idproduto =  $retorno["aparelho_codProduto"];
        $_fab = $retorno["CODIGO_FABRICANTE"];
      
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Modelo</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label >Linha</label>               
                                         <select name="modelo-linhaA" id="modelo-linhaA" class="form-control" onchange="mod_produto2('A')">  
                                         <option value="">Selecione</option>                                           
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_linha where ap_linhaAtivo = 1 ORDER BY ap_linhaDescricao");
                                              $retornoP = $consulta->fetchAll();
                                              foreach ($retornoP as $row) {
                                             
                                                  ?><option value="<?=$row["ap_linhaId"]?>" <?php if($row["ap_linhaId"] == $idlinha) { echo 'selected';}?> ><?=$row["ap_linhaDescricao"]?></option><?php
                                                 
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Produto</label>               
                                            <select name="modelo-produtoA" id="modelo-produtoA" class="form-control">                                              
                                            <option value="">Selecione</option>   
                                            <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_produto where ap_prodLinha = '$idlinha' ORDER BY ap_prodd");
                                              $retornoP = $consulta->fetchAll(PDO::FETCH_OBJ);
                                              foreach ($retornoP as $row) {
                                                ?><option value="<?=$row->ap_prodId;?>" <?php if($row->ap_prodId == $idproduto) { echo 'selected';}?>><?=$row->ap_prodd;?></option><?php
                                              }                                              
                                              ?>                          
                                            </select>
                                        </div>
                                </div>
                        </div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                     <label >Marca / Fabricante</label>               
                                         <select name="modelo-marca" id="modelo-marca" class="form-control">    
                                         <option value="">Selecione</option>                                             
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".fabricante where for_Tipo = 1 ORDER BY NOME");
                                              $retornof = $consulta->fetchAll();
                                              foreach ($retornof as $rowf) {
                                                  ?><option value="<?=$rowf["CODIGO_FABRICANTE"];?>" <?php if($rowf["CODIGO_FABRICANTE"] == $_fab) { echo 'selected';}?>><?=$rowf["NOME"]?></option><?php
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="modelo-descricao" class="control-label">Descrição</label>
                                    <input type="text" class="form-control" name="modelo-descricao" id="modelo-descricao" placeholder="Descrição Produto e Aparelho" value="<?=$retorno["DESCRICAO"]?>">
                                    <input type="hidden" name="modelo-id" id="modelo-id" value="<?=$retorno["CODIGO_APARELHO"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="modelo-comercial" class="control-label">Modelo Comercial</label>
                                    <input type="text" class="form-control" name="modelo-comercial" id="modelo-comercial" placeholder="Modelo Comercial" value="<?=$retorno["MODELO"]?>">                                   
                                </div>
                            </div>
                        </div>
                        </div>
                        <div>
                       
                     
                   
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
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
 * Cria novo grupo
 * */
else if ($acao["acao"] == 1) {
    if (empty($_parametros["modelo-descricao"]) ) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["modelo-descricao"]) ): ?>
                            <h2>Informe a descrição!</h2>
                        <?php endif ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".aparelho (DESCRICAO,CODIGO_FABRICANTE,MODELO,aparelho_codProduto) VALUES(?,?,?,?)");
            $statement->bindParam(1, $_parametros["modelo-descricao"]);
            $statement->bindParam(2, $_parametros["modelo-marca"]);
            $statement->bindParam(3, $_parametros["modelo-comercial"]);
            $statement->bindParam(4, $_parametros["modelo-produtoI"]);        
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="100"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Modelo Cadastrado!</h2>
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
 * Lista grupos
 * */
else if ($acao["acao"] == 2) {

    if(trim($descricao) == "" and trim($_fabricante) == "" and trim($_linha) == ""  ){
        $descricao = "XX99xx99xx77";
    }
    if(trim($_fabricante) != "" ){
        $filfabricante = "AND A.CODIGO_FABRICANTE  = '$_fabricante'";

    }

    if(trim($_linha) != "" ){
        $fillinha = "AND ap_prodLinha  = '$_linha'";

    }
    if(trim($_produto) != "" ){
        $fillinha =  $fillinha." AND A.aparelho_codProduto  = '$_produto'";

    }
    

   $sql = "Select A.CODIGO_APARELHO,MODELO,DESCRICAO, F.nome as fornecedor,mes_preventivo,ap_prodd as produto, ap_linhaDescricao as linha,
   ap_prodVlG as vlg,ap_prodVlFG as vlfg,mes_preventivo
		from ". $_SESSION['BASE'] .".aparelho  as A
		left JOIN ". $_SESSION['BASE'] .".fabricante  as F on  F.CODIGO_FABRICANTE  = A.CODIGO_FABRICANTE	
        left JOIN ". $_SESSION['BASE'] .".aparelho_produto on  ap_prodId  = A.aparelho_codProduto	
        left JOIN ". $_SESSION['BASE'] .".aparelho_linha on  ap_linhaId  = ap_prodLinha	
		WHERE
		A.CODIGO_FABRICANTE = '$_fabricante'  AND MODELO like '%$descricao%' AND MODELO <> '' and  DESCRICAO like '%$descricao%' AND DESCRICAO <> '' $fillinha  OR 
		MODELO like '%$descricao%' AND MODELO <> ''  $filfabricante  $fillinha OR       
		DESCRICAO like '%$descricao%' AND DESCRICAO <> ''  $filfabricante  $fillinha
		order by DESCRICAO,MODELO,NOME";

    $statement = $pdo->query("$sql");
    $retorno = $statement->fetchAll();
    ?>
          
    
   
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                 <th class="text-center">Linha</th>
                <th class="text-center">Produto</th>
                <th>Descrição</th>
                <th class="text-center">Modelo</th>
                <th class="text-center">Marca</th>
                
                <th class="text-center">Mês P/ Preventivo</th>
                <th class="text-center">Ação</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td><?=($row["linha"]);?></td>
                <td><?=($row["produto"]);?></td>
                <td><?=($row["DESCRICAO"]);?></td>
                <td class="text-center"><?=$row["MODELO"];?></td>
                <td class="text-center"><?=$row["fornecedor"];?></td>
               
                <td class="text-center"><?=$row["mes_preventivo"];?></td>
                <td class="actions text-center">
                    <a href="#" style="margin-right: 10px;" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados('<?=$row["CODIGO_APARELHO"];?>')"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir('<?=$row["CODIGO_APARELHO"];?>')"><i class="fa fa-trash-o"></i></a>
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
 * Atualiza grupo
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["modelo-descricao"]) ) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["modelo-descricao"]) ): ?>
                       Informe a Descrição
                        <?php endif ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".aparelho SET DESCRICAO = ?, MODELO = ?, CODIGO_FABRICANTE = ? , aparelho_codProduto = ? WHERE CODIGO_APARELHO = ?");
            $statement->bindParam(1, $_parametros["modelo-descricao"]);
            $statement->bindParam(2, $_parametros["modelo-comercial"]);
            $statement->bindParam(3, $_parametros["modelo-marca"]);
            $statement->bindParam(4, $_parametros["modelo-produtoA"]);
            $statement->bindParam(5, $_parametros["modelo-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                          
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Registro Atualizado!</h2>
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
 * Exclui grupo
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".aparelho  WHERE CODIGO_APARELHO = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Registro Excluído! </h2>
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
} else if ($acao["acao"] == 5) { //buscar tipo produto
    try {
        $idlinha =0;
        if($_parametros["modelo-linhaI"] != "" OR $_parametros["modelo-linhaA"] != "")  {
            if($_parametros["modelo-linhaI"] != ""  )  {
                $idlinha = $_parametros["modelo-linhaI"];
            }else{
                $idlinha = $_parametros["modelo-linhaA"];
            }
            

            ?>
            <option value="">Selecione</option> <?php


        }else {
            $idlinha = $_parametros["modelo-linha"];
            ?>
            <option value="">Todos</option>
        <?php

        }
      
     
      
        if($idlinha > 0) {
            $sql = "Select * from ". $_SESSION['BASE'] .".aparelho_produto           
                WHERE ap_prodAtivo = '1' and  ap_prodLinha = :id           
                order by ap_prodd";                  
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':id', $idlinha);
        }else{
            $sql = "Select * from ". $_SESSION['BASE'] .".aparelho_produto where ap_prodAtivo = '1'
            order by ap_prodd";                  
             $statement = $pdo->prepare($sql);      
        }
        $statement->execute();
        if ( $statement->rowCount() > 0 ){        
            while ($row = $statement->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
          {   
            ?><option value="<?=$row->ap_prodId;?>"><?=$row->ap_prodd;?></option><?php
          }
        }
       
        
        
    } catch (PDOException $e) {
    }
} 