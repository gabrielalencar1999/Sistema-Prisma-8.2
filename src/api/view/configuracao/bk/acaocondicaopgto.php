<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    if (!empty($valor)) {
        $valor = trim($valor);
        $valor = str_replace(",", ".", $valor);
        $valor = str_replace("'", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
    }
    return $valor;
}



/*
 * Chama modal altera
 * */
if ($acao["acao"] == 0) {
    try {
        $id_cond = $_parametros["id-altera"];

        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tiporecebimpgto LEFT JOIN ". $_SESSION['BASE'] .".bancos ON cod_Portador = BCO_ID LEFT JOIN ". $_SESSION['BASE'] .".livro_caixa_numero ON  Num_Livro = Livro_Numero WHERE id = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="modal-dialog text-left modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Altera Condição de Pagamento</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="condicao-nome" class="control-label">Nome:</label>
                                    <input type="text" class="form-control" name="condicao-nome" id="condicao-nome" value="<?=$retorno["nome"]?>">
                                    <input type="hidden" name="condicao-id" id="condicao-id" value="<?=$_parametros["id-altera"]?>">
                                </div>
                            </div>
                        </div>
                            <!---
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-parcelas" class="control-label">Qt. Parcelas:</label>
                                    <input type="text" class="form-control" name="condicao-parcelas" id="condicao-parcelas" value="<?=$retorno["QT_Parcelas"]?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-prazo" class="control-label">Prazo:</label>
                                    <input type="text" class="form-control" name="condicao-prazo" id="condicao-prazo" value="<?=$retorno["prz"]?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-juros" class="control-label">Taxa de Juros:</label>
                                    <input type="text" class="form-control" name="condicao-juros" id="condicao-juros" value="<?=$retorno["tx_juro"]?>">
                                </div>
                            </div>-->
                            <!---
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-livro" class="control-label">Livro Caixa:</label>
                                    <select name="condicao-livro" id="condicao-livro" class="form-control">
                                        <option value="0" selected>Nenhum</option>
                                        <?php
                                        $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".livro_caixa_numero order by Descricao");
                                        $result = $consulta->fetchAll();

                                        foreach ($result as $row)
                                        {
                                            ?><option value="<?=$row["Livro_Numero"]?>"<?=$row["Livro_Numero"] != $retorno["Num_Livro"] ?: "selected"?>><?=$row["Descricao"]?></option><?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>-->                            
                        <div class="row">
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-liquida" class="control-label">Liquida Venda:</label>
                                    <select name="condicao-liquida" id="condicao-liquida" class="form-control">
                                        <option value="S" <?=$retorno["Ind_liquida"] == "S" ? "selected":""?>>Sim</option>
                                        <option value="N" <?=$retorno["Ind_liquida"] == "N" ? "selected":""?>>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-nfce" class="control-label">Código Pgto NFCe:</label>
                                    <select name="condicao-nfce" id="condicao-nfce" class="form-control">
                                        <option value="01" <?=$retorno["Tipo"] == "01" ? "selected":""?>>Dinheiro</option>
                                        <option value="02" <?=$retorno["Tipo"] == "02" ? "selected":""?>>Cheque</option>
                                        <option value="03" <?=$retorno["Tipo"] == "03" ? "selected":""?>>Cartão de Crédito</option>
                                        <option value="04" <?=$retorno["Tipo"] == "04" ? "selected":""?>>Cartão de Débito</option>
                                        <option value="05" <?=$retorno["Tipo"] == "05" ? "selected":""?>>Cartão da Loja</option>
                                        <option value="10" <?=$retorno["Tipo"] == "10" ? "selected":""?>>Vale Alimentação</option>
                                        <option value="11" <?=$retorno["Tipo"] == "11" ? "selected":""?>>Vale Refeição</option>
                                        <option value="12" <?=$retorno["Tipo"] == "12" ? "selected":""?>>Vale Presente</option>
                                        <option value="13" <?=$retorno["Tipo"] == "13" ? "selected":""?>>Vale Combustível</option>
                                        <option value="99" <?=$retorno["Tipo"] == "99" ? "selected":""?>>Outros</option>
                                    </select>
                                </div>
                            </div>
                            
                              
                        
                             <!---
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-resumo" class="control-label">Separar Resumo cx:</label>
                                    <select name="condicao-resumo" id="condicao-resumo" class="form-control">
                                        <option value="1" <?=$retorno["ind_troca"] == "1" ? "selected":""?>>Sim</option>
                                        <option value="0" <?=$retorno["ind_troca"] == "0" ? "selected":""?>>Não</option>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="condicao-site" class="control-label">Exibir no Site:</label>
                                    <select name="condicao-site" id="condicao-site" class="form-control">
                                        <option value="0" <?=$retorno["recebTipo_site"] == "0" ? "selected":""?>>Sim</option>
                                        <option value="-1" <?=$retorno["recebTipo_site"] == "0" ?:"selected"?>>Não</option>
                                    </select>
                                </div>
                            </div>--->    
                     
    
                         <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Prazo Normal (Dias)</label>
            <input type="number" class="form-control" name="condicao-prazo-normal" value="<?=$retorno["prz"]?>">
        </div>
    </div>
    <div class="col-md-4">
    <div class="form-group">
        <label for="condicao-parcelas" class="control-label">Qtde Máx. Parcela</label>
        <select name="condicao-parcelas" id="condicao-parcelas" class="form-control">
            <?php
            // Defina o máximo de parcelas que você quer permitir
            $maxParcelas = 12;

            // Puxa o valor atual do banco
            $qtdeAtual = $retorno['QT_Parcelas'];

            for ($i = 1; $i <= $maxParcelas; $i++) {
                $selected = ($i == $qtdeAtual) ? "selected" : "";
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>
    </div>
</div>

    
    <div class="col-md-4">
        <div class="form-group">
            <label>Conta Corrente/Caixa</label>
            <select class="form-control" name="condicao-livro">
                <option value="">Selecione</option>
                <?php foreach($result as $row): ?>
                    <option value="<?=$row['Livro_Numero']?>" <?=$row['Livro_Numero']==$retorno["Num_Livro"]?"selected":""?>><?=$row['Descricao']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

                    
  
                        <!--
                        <div class="row" >             
                            <div class="col-sm-4"  style="padding:15px; border:1px solid #618a99; border-radius:8px 0px 0px 8px;">
                                <div class="col-md-12" style="border-bottom:1px solid #618a99; text-align:center;">Parcelas</div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condicao-parcelas" class="control-label" style="font-size:12px;">Inicial</label>
                                        <input type="number" class="form-control" name="condicao_parcelas_ini" id="condicao_parcelas_ini" value="1" placeholder="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condicao-parcelas" class="control-label" style="font-size:12px;">Final</label>
                                        <input type="number" class="form-control" name="condicao_parcelas_fim" id="condicao_parcelas_fim" value="12" placeholder="12">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-8" style="padding:15px; padding-top:36px; border:1px solid #618a99; border-radius:0px 8px 8px 0px; background-color:#618a99; color:#FFF;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="condicao-juros" class="control-label" style="font-size:12px;">Tx Normal (%)</label>
                                        <input type="tel" class="form-control" name="tx_normal" id="tx_normal" value="" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="condicao-prazo" class="control-label" style="font-size:12px;">Tx Antecipação</label>
                                        <input type="tel" class="form-control" name="tx_antecipacao" id="tx_antecipacao" value=""  onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="condicao-prazo" class="control-label" style="font-size:12px;">Tx Cliente</label>
                                        <input type="tel" class="form-control" name="tx_cliente" id="tx_cliente" value=""  onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="condicao-prazo" class="control-label" style="font-size:12px;">Tipo Taxa</label>
                                        <select class="form-control" id="tipo_tx" name="tipo_tx">
                                            <option value="0">Sobre o valor</option>
                                            <option value="1">Ao mês</option>
                                        </select>
                                    </div>
                                </div>                                                                                                            
                            </div>
                       
                        <div class="row">
                        <div class="col-md-12">
                                <div class="form-group" style="padding-top:7px;" align="center">
                                    <button class="btn btn-default waves-effect waves-light" onclick="geparcela('<?=$id_cond;?>')"><span class="btn-label btn-label"> <i class="fa fa-bars"></i></span>Gerar Parcelas</button>
                                    <button class="btn btn-danger waves-effect waves-light" onclick="clear_parcela('<?=$id_cond;?>','')"><span class="btn-label btn-label"> <i class="fa fa-times"></i></span>Limpar Parcelas</button>
                                </div>
                            </div>    
                        </div>
                      </div>
                                    -->
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
 * Inclui Condição
 * */
else if ($acao["acao"] == 1) {
    
    if (empty($_parametros["condicao-nome"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o nome da Condição!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {


         $sql = "INSERT INTO ". $_SESSION['BASE'] .".tiporecebimpgto 
        (nome, Tipo, prz, cod_Portador, Ind_liquida, Num_Livro, QT_Parcelas) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stm = $pdo->prepare($sql);	
$stm->bindParam(1, $_parametros["condicao-nome"]);
$stm->bindParam(2, $_parametros["condicao-nfce"]);
$stm->bindParam(3, $_parametros["condicao-prazo"]);
$stm->bindParam(4, $_parametros["condicao-portador"]);
$stm->bindParam(5, $_parametros["condicao-liquida"]);
$stm->bindParam(6, $_parametros["condicao-livro"]);
$stm->bindParam(7, $_parametros["condicao-max-parcela"]);

$stm->execute();



// Valor selecionado na combobox
$tipoSelecionado = $_parametros["condicao-nfce"]; // ex: "01"

// Pega o ID do registro recém-inserido
$idCondPag = $pdo->lastInsertId();

// Agora insere direto na condicao_parcelamento usando o ID recém-criado
$parcelas = $_parametros["condicao-max-parcela"] ?? 1;

$stmtParcela = $pdo->prepare("
    INSERT INTO " . $_SESSION['BASE'] . ".condicao_parcelamento 
        (cp_condPag, cp_parcela)
    VALUES (:condPag, :parcela)
");

for ($i = 1; $i <= $parcelas; $i++) {
    $stmtParcela->execute([
        ':condPag' => $idCondPag,
        ':parcela' => $i
    ]);
}








            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Condição Cadastrada!</h2>
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
 * Lista Condições
 * */
else if ($acao["acao"] == 2) {
    $consulta = $pdo->query("
    SELECT t.*, b.BCO_NOME, l.Descricao AS Livro_Descricao, n.tpnfce_descricao
    FROM ". $_SESSION['BASE'] .".tiporecebimpgto t
    LEFT JOIN ". $_SESSION['BASE'] .".bancos b ON t.cod_Portador = b.BCO_ID
    LEFT JOIN ". $_SESSION['BASE'] .".livro_caixa_numero l ON t.Num_Livro = l.Livro_Numero
    LEFT JOIN bd_prisma.tipo_pgtoNfce n ON t.Tipo = n.tpnfce_id
    ORDER BY t.nome
");

    $retorno = $consulta->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                
                <th class="text-center">Descrição</th>
                <th class="text-center">Qt. Parcelas</th>
                <th class="text-center">Prazo Normal</th>
                
                <th class="text-center">Liquida</th>
                <th class="text-center">Tipo Nfce</th>
                <th class="text-center">Ações</th>
                                       
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $rst)
        {

          
            if($totalParcela == 0){
                $totalParcela = 1;

            }

            if($rst["ind_troca"] == 0){
                $contabilizaCX = "Não";
            }else{
                $contabilizaCX = "Sim";
            }

        ?>
            <tr class="gradeX">
                
                <td class="text-center"><?=$rst["nome"];?></td>
                <td class="text-center"><?=$rst["QT_Parcelas"];?></td>
                <td class="text-center"><?=$rst["BCO_NOME"];?></td>
                <td class="text-center"><?=$rst["Ind_liquida"];?></td>
                <td><?=$rst["tpnfce_descricao"];?></td>

                
                           <td class="actions text-center">
    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados(<?=$rst['id']?>)">
        <i class="fa fa-pencil" style="font-size:18px; margin-right:10px;"></i>
    </a>
    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$rst['id'];?>)">
        <i class="fa fa-trash-o color-red"></i>
    </a>
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
 * Atualiza Condição
 * */
else if ($acao["acao"] == 3) {
    if (empty($_parametros["condicao-nome"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o nome da Condição!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
          $statement = $pdo->prepare("UPDATE ".$_SESSION["BASE"].".tiporecebimpgto 
SET nome = ?, Tipo = ?, QT_Parcelas = ?, prz = ?, cod_Portador = ?, 
tx_juro = ?, Ind_liquida = ?, Num_Livro = ?, ind_troca = ?, 
recebTipo_site = ?, receb_integral= ?, prz_antecipacao = ?  
WHERE id = ?");

 





            $statement->bindParam(1, $_parametros["condicao-nome"]);
            $statement->bindParam(2, $_parametros["condicao-nfce"]);
            $statement->bindParam(3, $_parametros["condicao-parcelas"]);
            $statement->bindParam(4, $_parametros["condicao-prazo-normal"]);
            $statement->bindParam(5, $_parametros["condicao-portador"]);
            $statement->bindParam(6, $_parametros["condicao-juros"]);
            $statement->bindParam(7, $_parametros["condicao-liquida"]);
            $statement->bindParam(8, $_parametros["condicao-livro"]);
            $statement->bindParam(9, $_parametros["condicao-resumo"]);
            $statement->bindParam(10, $_parametros["condicao-site"]);
            $statement->bindParam(11, $_parametros["tp_recibimento"]);
            $statement->bindParam(12, $_parametros["condicao-prazo-antecipado"]);
            $statement->bindParam(13, $_parametros["condicao-id"]);
            

           $statement->execute();
// ID do registro sendo editado
$idCondPag = $_parametros["condicao-id"];

// Número de parcelas atualizado
$parcelas = $_parametros["condicao-parcelas"] ?? 1;

// Apaga as parcelas antigas
$pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".condicao_parcelamento WHERE cp_condPag = :id")
    ->execute([':id' => $idCondPag]);

// Recria as parcelas de 1 até N
$stmtParcela = $pdo->prepare("
    INSERT INTO " . $_SESSION['BASE'] . ".condicao_parcelamento 
        (cp_condPag, cp_parcela)
    VALUES (:condPag, :parcela)
");

for ($i = 1; $i <= $parcelas; $i++) {
    $stmtParcela->execute([
        ':condPag' => $idCondPag,
        ':parcela' => $i
    ]);
}



           ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Condição Atualizada!</h2>
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
 * Excluí Condição
 * */
else if ($acao["acao"] == 4) {
    try {
        // ID da condição
        $id = $_parametros["id-exclusao"];

        // 1º - Apagar registros relacionados na condicao_parcelamento
        $stmtDel = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".condicao_parcelamento WHERE cp_condPag = :id");
        $stmtDel->bindParam(':id', $id);
        $stmtDel->execute();

        // 2º - Apagar o registro principal na tiporecebimpgto
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".tiporecebimpgto WHERE id = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();

        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Condição Excluída!</h2>
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
