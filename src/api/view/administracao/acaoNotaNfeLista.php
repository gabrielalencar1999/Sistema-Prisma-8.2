<?php

use Database\MySQL;

$pdo = MySQL::acessabd();
date_default_timezone_set('America/Sao_Paulo');
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$data_atual = $dia."/".$mes."/".$ano;
/*
 * Função para limpar variáveis, caso necessário
 * */
function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}
function LimpaVariavelDoc($valor){
    $valor = trim($valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function CFOP($CFOP){
    if("6"  == substr($CFOP,0,1) or "2"  == substr($CFOP,0,1)){
        /*
6401 = 2403 CSOSN 500 OU 2407 CSOSN 900
6101 = 2102 CSOSN 102 ou 2556 CSOSN 900

6949 = 2949 CSOSN 900

6102 = 2102 CSOSN 102 ou 2556 CSOSN 900

6403 = 2403 CSOSN 500 ou 2407 CSOSN 900

5102 = 1556 CSOSN 900 ou 1102 CSOSN 102

5403 = 1403 CSOSN 500 ou 1407 CSOSN 900

5401 = 1403 CSOSN 500 ou 1407 CSOSN 900

5949 = 1949 CSOSN 900
        */
         
            switch ($CFOP) {
                case "6401":
                    $valor = '2403';
                    break;
                case "6101":
                    $valor = '2102';
                    break;
                case "6949":
                    $valor = '2949';
                    break;
                case "6102":
                    $valor = '2102';
                    break;
                 case "6403":
                    $valor = '2403';
                    break;  
                    case "6353":
                        $valor = '2353';
                        break;        
                  case "5353":
                       $valor = '1353';
                          break;  
                  case "5405":
                        $valor = '1403';
                       break; 
                                

            }  
        
         if($valor == "") {
            
            if(substr($CFOP,0,1) == '5'){
                $_ix = "1";
            }else{
                $_ix = "2";
            }
                $valor  = $_ix.substr($CFOP,-3);        
            }
                     
        }else{
            $valor  = "1".substr($CFOP,-3);//1102; 
        }
    return $valor;
}

/*
 * Inclui Notas
 * */
$acao = $_POST["acao"];
if ($acao == 1) {

   $modelo = $_parametros['nf-modelo'];
   if($modelo == "") { 
    $modelo = 55;
   }else{
    $modelo = $_parametros['nf-modelo'];
   }

    if($_parametros['_dataIni'] == '') {
        $_parametros['_dataIni'] = date('Y-m-d');
        $_parametros['_dataFim'] = date('Y-m-d');
    } 

    if($_parametros['_numeronf'] != '') {
            $fil = "and  nfed_numeronf = '".$_parametros['_numeronf']."'OR nfed_modelo = '$modelo' and nfed_numeronf = '".$_parametros['_numeronf']."'";
    }
    
    $consultaNF = $pdo->query("SELECT nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente
                               FROM ".$_SESSION['BASE'].".NFE_DADOS
                               WHERE nfed_modelo = '$modelo' and 	nfed_data BETWEEN '".$_parametros['_dataIni']."' AND '".$_parametros['_dataFim']."' 
                               $fil");
    $retornoNF = $consultaNF->fetch();


    if (!$retornoNF) {
        /*
        date_default_timezone_set('America/Sao_Paulo');
        $data_entrada = date("Y-m-d H:i:s", time());
        try {
            $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_data,nfed_cliente) VALUES (?, ?)");
            $statement->bindParam(1, $data_entrada);
            $statement->bindParam(2, $_parametros["nf-fornec"]);
            $statement->bindParam(3, $data_entrada);
            $statement->execute();
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Nota Cadastrada!</h2>
                            <button class="btn btn-success waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_alterar(<?=$_parametros["nf-num"]?>, <?=$_parametros["nf-fornec"]?>)">Ir para Cadastro</button>
                            <input type="hidden" id="retorno-nota" name="retorno-nota" value="true">
                        </div>
                    </div>
                </div>
            </div>
            <?php
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
    else {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Nota Fiscal já cadastrada!</h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
          */
    }
    exit();
  
}
/*
 * Lista Notas
 * 
 * */
else if ($acao == 2) {

    try {
        $modelo =  $_parametros['nf-modelo'];
        if($modelo == "" ) {   
            $modelo = '55';
        }
      
        if( $modelo == "55") {       

         $modeloDesc = "55-NF-e";  
        }elseif($modelo == "90"){
            $modeloDesc = "NFS-e";
          
        }else{
         $modeloDesc = "65-NFC-e";
        }

        $_parametros["_desc"] = trim( $_parametros["_desc"]);

        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 
        
        if($_parametros['_empresa'] != '') {
            $filemp = " AND nfed_empresa = '".$_parametros['_empresa']."'";
        }
        
        if($_parametros["_desc"] != "" && $_parametros["nf-tipo"] == 2 ) { 
            $filemp .= " AND nfed_informacaoAdicionais LIKE '%". $_parametros["_desc"] ."%' ";   
        }

        if($_parametros["_desc"] != "" && $_parametros["nf-tipo"] == 3 ) { 
            $cpfcnpj = preg_replace('/[^0-9]/', '', (string)  $_parametros["_desc"]);
                
                if(strlen($cpfcnpj) <= 11) //cpf
                {
                    $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
                    substr($cpfcnpj, 3, 3) . '.' .
                    substr($cpfcnpj, 6, 3) . '-' .
                    substr($cpfcnpj, 9, 2);
                } else {
                    $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                    substr($cpfcnpj, 2, 3) . '.' .
                    substr($cpfcnpj, 5, 3) . '/' .
                    substr($cpfcnpj, 8, 4) . '-' .
                    substr($cpfcnpj, -2);
                } 
            $filemp .= " AND nfed_cpfcnpj = '".  $cpfcnpj ."' ";   
        }

        
        if($_parametros['_numeronf'] != '') {
            $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_modelo = '$modelo' and nfed_numeronf = '".$_parametros['_numeronf']."'";
            
        }

if($_parametros["_desc"] != "" && $_parametros["nf-tipo"] == 1 ) {             
   
                $statement = $pdo->query("SELECT nfed_tipodocumento,nfed_empresa,nfed_pedido,nfed_chamada,nfed_url,nfed_cancelada,nfed_modelo,nfed_id,nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente, DATE_FORMAT(nfed_dataautorizacao,'%d/%m/%Y') AS DT,
                DATE_FORMAT(nfed_data,'%d/%m/%Y') AS DTcad,nfed_totalnota,Nome_Consumidor,nfed_dNome
                FROM ".$_SESSION['BASE'].".NFE_ITENS
                LEFT JOIN ".$_SESSION['BASE'].".NFE_DADOS ON nfed_id = id_nfedados
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON codigoproduto_nfeitens = CODIGO_FORNECEDOR
                LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
                WHERE CODIGO_FABRICANTE LIKE '%". $_parametros["_desc"] ."%' AND nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil 
                OR CODIGO_FABRICANTE LIKE '%". $_parametros["_desc"] ."%' AND nfed_modelo = '$modelo' and nfed_data >= '".$_parametros['nf-inicial']." 00:00' AND nfed_data <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil  LIMIT 500");
                $retorno = $statement->fetchAll();


                  $sqlCount = "
                        SELECT COUNT(*) AS total FROM ".$_SESSION['BASE'].".NFE_ITENS
                                LEFT JOIN ".$_SESSION['BASE'].".NFE_DADOS ON nfed_id = id_nfedados
                                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON codigoproduto_nfeitens = CODIGO_FORNECEDOR
                                LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
                                WHERE CODIGO_FABRICANTE LIKE '%". $_parametros["_desc"] ."%' AND nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil 
                                OR CODIGO_FABRICANTE LIKE '%". $_parametros["_desc"] ."%' AND nfed_modelo = '$modelo' and nfed_data >= '".$_parametros['nf-inicial']." 00:00' AND nfed_data <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil  
                        ";

                        $stmtCount = $pdo->prepare($sqlCount);
                        $stmtCount->execute();
                        $total = $stmtCount->fetchColumn();
        } else {


                $statement = $pdo->query("SELECT nfed_tipodocumento,nfed_empresa,nfed_pedido,nfed_chamada,nfed_url,nfed_cancelada,nfed_modelo,nfed_id,nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente, DATE_FORMAT(nfed_dataautorizacao,'%d/%m/%Y') AS DT,
                DATE_FORMAT(nfed_data,'%d/%m/%Y') AS DTcad,nfed_totalnota,Nome_Consumidor
                FROM ".$_SESSION['BASE'].".NFE_DADOS
                LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
                WHERE nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil
                OR nfed_modelo = '$modelo' and nfed_data >= '".$_parametros['nf-inicial']." 00:00' AND nfed_data <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil  LIMIT 500");
                $retorno = $statement->fetchAll();

                      $sqlCount = " SELECT COUNT(*) AS total
                       FROM ".$_SESSION['BASE'].".NFE_DADOS
                LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
                WHERE nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil
                OR nfed_modelo = '$modelo' and nfed_data >= '".$_parametros['nf-inicial']." 00:00' AND nfed_data <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil  LIMIT 500                        ";

                        $stmtCount = $pdo->prepare($sqlCount);
                        $stmtCount->execute();
                        $total = $stmtCount->fetchColumn();

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
    
// ---------- 2️⃣  Emitir aviso se necessário ----------
if ($total > 500) {
    echo "<div style='color: orange; font-weight: bold;'>
            Aviso: Foram encontrados {$total} registros. 
            Apenas os 500 primeiros serão exibidos. 
            Considere refinar seus filtros.
          </div>";
} 
    ?>
    
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
            <th style="text-align: center;">N° NF</th>
            <th>Cliente</th>   
            <th>Data Cadastro</th>       
            <th>Data Autorização</th>
            <th style="text-align: center;">Valor NF</th>
            <th style="text-align: center;">N° Pedido</th>
            <th style="text-align: center;">N° O.S</th>
            <th class="text-center">Situação</th>
            <th class="text-center">Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalFF = 0.0;
        foreach ($retorno as $row) {
            $_BTN = "";
           if($row["nfed_cancelada"] == 1) { 
             $_labelstatus = 'danger'; $status = 'Cancelada'; 
             $_ex = 1;
            }elseif($row["nfed_cancelada"] == 3) { 
                $_labelstatus = 'inverse'; $status = 'Negado'; 
                $_ex = 1;
           }elseif($row["nfed_cancelada"] == 2) { 
            $_labelstatus = 'warning'; $status = 'Inutiizada'; 
            $_ex = 1;
            }elseif($row["nfed_cancelada"] == 9) { 
                $_labelstatus = 'primary'; $status = 'Processando Aut.'; 
                if($modelo == '90'){
                    $_BTN = ' <a href="javascript:void(0);" class="on-default remove-row"  onclick="_atualizarNfse('.$row["nfed_id"].','."'ret".$row["nfed_id"]."','".$row["nfed_empresa"]."'".')"><i class="fa fa-refresh"></i></a>';
                }else{
                    //58 mdfe
                 $_BTN = ' <a href="javascript:void(0);" class="on-default remove-row"  onclick="_atualizarMDFE('.$row["nfed_tipodocumento"].','."'ret".$row["nfed_id"]."','".$row["nfed_empresa"]."'".')"><i class="fa fa-refresh"></i></a>';
                }
                $_ex = 1;
            }elseif($row["nfed_url"] != "" or $row['DT'] != '00/00/0000'){
                $_labelstatus = 'success'; $status = 'Emitida'; 
                $_ex = 1;
            }else{
                $_labelstatus = 'inverse'; $status = '-'; 
                $_ex = 0;
            }

            ?>
            <tr class="gradeX">
                <td style="text-align: center;"><?=$row["nfed_numeronf"]?></td>
                <td><?=$row["Nome_Consumidor"];?></td>    
                <td><?=$row["DTcad"]?></td>           
                <td><?=$row["DT"]?></td>
                <td style="text-align: center;">R$ <?=number_format($row["nfed_totalnota"], 2, ',', '.')?></td>
                <td style="text-align: center;"><?=$row["nfed_pedido"];?></td> 
                <td style="text-align: center;"><?=$row["nfed_chamada"];?></td>       
                <td class="text-center" id=ret<?=$row["nfed_id"];?>>
                    <span class="label label-table label-<?=$_labelstatus;?>"><?=$status;?></span>
                </td>
                <td class="actions text-center">
                    <a href="javascript:void(0);" style="padding-right: 10px;" class="on-default edit-row" onclick="_alterar('<?=$row["nfed_id"];?>','<?=$row["nfed_modelo"];?>')"><i class="fa fa-pencil"></i></a>
                    <?php 
                  if($_ex == 0) { ?>
                        <a href="javascript:void(0);" class="on-default remove-row"  onclick="_idexcluir(<?=$row["nfed_id"]?>)"><i class="fa fa-trash-o"></i></a>
                    <?php }
                    echo $_BTN;
                    ;?>
            
                </td>
            </tr>
            <?php
            $totalFF += $row["nfed_totalnota"];
        }
        ?>
        </tbody>
    </table>
    <div class="alert alert-info">
        Total <strong>R$<?=number_format($totalFF, 2, ',', '.')?></strong>
    </div>
    
    <?php
}
/*
 * Excluir Notas
 * */
else if ($acao == 4) {
    $consultaNF = $pdo->query("SELECT NFE_Conferido FROM ".$_SESSION['BASE'].".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
    $retornoNF = $consultaNF->fetch();

    if ($retornoNF["NFE_Conferido"] == "-1") {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Nota Fiscal já lançada!</h2>
                    </div>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    else {
        try {
            $consultaNF = $pdo->query("SELECT NFE_ALMOX FROM ". $_SESSION['BASE'] .".nota_ent_base WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
            $resultNF = $consultaNF->fetch();

            $consultaProd = $pdo->query("SELECT NFE_CHAVE, NFE_NRO, NFE_FORNEC, NFE_CODIGO, NFE_QTDADE, NFE_VLRUNI, NFE_TOTALITEM, NFE_ESTOK FROM ". $_SESSION['BASE'] .".nota_ent_item WHERE NFE_NRO = '".$_parametros["id-nota"]."' AND NFE_FORNEC = '".$_parametros["id-fornecedor"]."'");
            $resultProd = $consultaProd->fetchAll();

            foreach ($resultProd as $row) {
                if (intval($row["NFE_ESTOK"]) == 0) {
                    $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_CHAVE = ?");
                    $statement->bindParam(1, $row["NFE_CHAVE"]);
                    $statement->execute();
                }
                else {
                    date_default_timezone_set('America/Sao_Paulo');
                    $codigoMov = "S";
                    $tipMov = "E";
                    $numDocumento = $row["NFE_NRO"]."-".$row["NFE_FORNEC"];
                    $row["NFE_VLRUNI"] = number_format($row["NFE_VLRUNI"], 2, ".", "");
                    $inventario = "0";
                    $row["NFE_TOTALITEM"] = number_format($row["NFE_TOTALITEM"], 2, ".", "");
                    $motivo = "Exclusao da NF";
                    $dataMov = date("Y-m-d H:i:s", time());

                    $consultaQuantidade = $pdo->query("SELECT Qtde_Disponivel FROM ". $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '".$row["NFE_CODIGO"]."' AND Codigo_Almox = '".$resultNF["NFE_ALMOX"]."'");
                    $retorno = $consultaQuantidade->fetch();;
                    $quantidadeAtual = intval($retorno["Qtde_Disponivel"]) - intval($row["NFE_QTDADE"]);

                    $updateEstoque = $pdo->prepare("UPDATE ". $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                    $updateEstoque->bindParam(1, $quantidadeAtual);
                    $updateEstoque->bindParam(2, $row["NFE_CODIGO"]);
                    $updateEstoque->bindParam(3, $resultNF["NFE_ALMOX"]);
                    $updateEstoque->execute();

                    $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_item WHERE NFE_CHAVE = ?");
                    $statement->bindParam(1, $row["NFE_CHAVE"]);
                    $statement->execute();

                    $insertMov = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde, Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertMov->bindParam(1, $row["NFE_CODIGO"]);
                    $insertMov->bindParam(2, $row["NFE_QTDADE"]);
                    $insertMov->bindParam(3, $resultNF["NFE_ALMOX"]);
                    $insertMov->bindParam(4, $codigoMov);
                    $insertMov->bindParam(5, $tipMov);
                    $insertMov->bindParam(6, $numDocumento);
                    $insertMov->bindParam(7, $row["NFE_VLRUNI"]);
                    $insertMov->bindParam(8, $inventario);
                    $insertMov->bindParam(9, $row["NFE_TOTALITEM"]);
                    $insertMov->bindParam(10, $_SESSION["IDUSER"]);
                    $insertMov->bindParam(11,$motivo);
                    $insertMov->bindParam(12, $quantidadeAtual);
                    $insertMov->bindParam(13,$dataMov);
                    $insertMov->execute();
                }
            }

            $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_base WHERE NFE_NRO = ? AND NFE_FORNEC = ?");
            $statement->bindParam(1, $_parametros["id-nota"]);
            $statement->bindParam(2, $_parametros["id-fornecedor"]);
            $statement->execute();

            $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".nota_ent_pgto WHERE NFE_NRO = ? AND NFE_FORNEC = ? AND NFE_LANCADO = '0'");
            $statement->bindParam(1, $_parametros["id-nota"]);
            $statement->bindParam(2, $_parametros["id-fornecedor"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Nota Excluída!</h2>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
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
}else if ($acao == 5) { //gerar lote mensal
 
    try {

        $modelo = $_parametros['nf-modelo'];
        if($modelo == "" or $modelo == "55") {       

         $modeloDesc = "55-NF-e";      
         $tiponf = "NFe" ;
        }else{
         $modeloDesc = "65-NFC-e";
         $tiponf = "NFce" ;
        }

        
        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 
        
        if($_parametros['_empresa'] != '') {
            $filemp = " AND nfed_empresa = '".$_parametros['_empresa']."'";
        }
        
    if($_parametros['_numeronf'] != '') {
        $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
    }

    $cliente = $_SESSION['BASE_ID'];

    $mesInicial = explode("-", $_parametros['nf-inicial'] );   

    $Ames= $mesInicial[1];
    $Aano  = $mesInicial[0];
    $nomearquivo = $Ames."_".$Aano;
  
 
    $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $_sql = "SELECT nfed_xml_protocolado,nfed_chave
        FROM ".$_SESSION['BASE'].".NFE_DADOS
        LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
        WHERE nfed_chave <> '' AND  nfed_cancelada = 0 AND             
        nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil";
        $statement = $pdo->query("$_sql");
        $retorno = $statement->fetchAll();
    

        foreach ($retorno as $row) {
            $Idchave = $row['nfed_chave'];
            $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/NFe".$Idchave.".xml";
            $_xml =$row['nfed_xml_protocolado'];
      

            $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
            fwrite($fp,trim($_xml));
            fclose($fp); 
                        }
                        ?>
                    <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarxmlmensal()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar Lote Xml Autorizada</button>
                        <?php

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

    
// Instancia a Classe Zip
$diretorio = $dir."/";
$zip = new ZipArchive();
// Cria o Arquivo Zip, caso não consiga exibe mensagem de erro e finaliza script
if($zip->open($diretorio.$tiponf.'_'.$nomearquivo.'.zip', ZIPARCHIVE::CREATE) == TRUE)
{
// Insere os arquivos que devem conter no arquivo zip

$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if(substr($filename,-3) == "xml"){
    $zip->addFile($diretorio.$filename,$filename);
}}

//echo 'Arquivo criado com sucesso.';
}
else
{
exit('O Arquivo não pode ser criado.');
}

    // Fecha arquivo Zip aberto
    $zip->close();

    $arquivo = $tiponf."_".$nomearquivo.'.zip';
    if( file_exists($diretorio.$arquivo)){ 
    ?><a href="<?=$diretorio.$arquivo;?>" target="_blank"><?=$arquivo;?></a>
<?php
    }else{
        echo "Sem registros nesse periodo";
    }

}
else if ($acao == 55) { //gerar lote mensal cancelada
 
    try {

        $modelo = $_parametros['nf-modelo'];
        if($modelo == "" or $modelo == "55") {       

         $modeloDesc = "55-NF-e";      
         $tiponf = "NFe" ;
        }else{
         $modeloDesc = "65-NFC-e";
         $tiponf = "NFce" ;
        }

        
        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 
        
        if($_parametros['_empresa'] != '') {
            $filemp = " AND nfed_empresa = '".$_parametros['_empresa']."'";
        }

    if($_parametros['_numeronf'] != '') {
        $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
    }

    $cliente = $_SESSION['BASE_ID'];

    $mesInicial = explode("-", $_parametros['nf-inicial'] );   

    $Ames= $mesInicial[1];
    $Aano  = $mesInicial[0];
    $nomearquivo = $Ames."_".$Aano;
  
 
    $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
        
        if($_SESSION['CODIGOCLI'] != "") {
            foreach ( $ri as $file ) {
                $file->isDir() ?  rmdir($file) : unlink($file);
            }
          }

        $_sql = "SELECT nfed_xml_protocolado,nfed_chave
        FROM ".$_SESSION['BASE'].".NFE_DADOS
        LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
        WHERE nfed_chave <> '' AND  nfed_cancelada = '1' AND             
        nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil";
        $statement = $pdo->query("$_sql");
        $retorno = $statement->fetchAll();
    

        foreach ($retorno as $row) {
            $Idchave = $row['nfed_chave'];
            $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/NFe".$Idchave."_Canc.xml";
            $_xml =$row['nfed_xml_protocolado'];
      

            $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
            fwrite($fp,trim($_xml));
            fclose($fp); 
                        }
                        ?>
                    <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarxmlmensalcancelada()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar Lote Xml Cancelada</button>
                        <?php

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

    
// Instancia a Classe Zip
$diretorio = $dir."/";
$zip = new ZipArchive();
// Cria o Arquivo Zip, caso não consiga exibe mensagem de erro e finaliza script
if($zip->open($diretorio.$tiponf.'_'.$nomearquivo.'_Canc.zip', ZIPARCHIVE::CREATE) == TRUE)
{
// Insere os arquivos que devem conter no arquivo zip

$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if(substr($filename,-3) == "xml"){
    $zip->addFile($diretorio.$filename,$filename);
}}

//echo 'Arquivo criado com sucesso.';
}
else
{
exit('O Arquivo não pode ser criado.');
}

    // Fecha arquivo Zip aberto
    $zip->close();

    $arquivo = $tiponf."_".$nomearquivo.'_Canc.zip';
    if( file_exists($diretorio.$arquivo)){ 
    ?><a href="<?=$diretorio.$arquivo;?>" target="_blank"><?=$arquivo;?></a>
<?php
    }else{
        echo "Sem registros nesse periodo";
    }

}
else if ($acao == 6) { //gerar SINTEGRA
 
    try {

    
           
         $tiponf = "SINTEGRA" ;
     
 
        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 
        
        
    if($_parametros['_numeronf'] != '') {
        $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
    }

    $cliente = $_SESSION['BASE_ID'];
    $mesInicial = explode("-", $_parametros['nf-final'] );   

    $AdiaFinal= $mesInicial[2];
    $AmesFinal= $mesInicial[1];
    $AanoFinal  = $mesInicial[0];

    $mesInicial = explode("-", $_parametros['nf-inicial'] );   

    $Adia= $mesInicial[2];
    $Ames= $mesInicial[1];
    $Aano  = $mesInicial[0];
    $nomearquivo = $Ames."_".$Aano;
  
 
    $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
    $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/SINTEGRA".$Idchave.".txt";
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        
            //REGISTRO 10
            //10195117160001813674185290013 FUNCIONAL ASSISTENCIA TECNICA EM ELJuiz de Fora                  MG32321565972023020120230228331
            $_sql = "SELECT empresa_contatosSintegra,empresa_cnpj,empresa_inscricao,empresa_razaosocial,empresa_cidade,empresa_telefone,empresa_uf,
                   empresa_bairro,empresa_endereco,empresa_numero,empresa_complemento,CEP
                FROM ".$_SESSION['BASE'].".empresa limit 1";  
            $statement = $pdo->query("$_sql");
            $retorno = $statement->fetchAll();  
    
            foreach ($retorno as $row) {
              
                $CNPJ  = str_pad($row['empresa_cnpj'], 14 , ' ' , STR_PAD_RIGHT); 
                $INSC_ESTADUAL  = str_pad($row['empresa_inscricao'], 14 , ' ' , STR_PAD_RIGHT); 
                $RAZAO_SOCIAL_CONTRIBUINTE = str_pad(substr($row["empresa_razaosocial"],0,35), 35 , ' ' , STR_PAD_RIGHT);
                
                $MUNICIPIO = str_pad($row['empresa_cidade'], 30 , ' ' , STR_PAD_RIGHT);
                $UF =  str_pad($row['empresa_uf'],2 , ' ' , STR_PAD_RIGHT);
                $UF_EMPRESA = $UF;
                $FAX = str_pad($row['empresa_telefone'], 10 , '0' , STR_PAD_RIGHT);
                $DATA_INICIAL = $Aano.$Ames.$Adia;
                $DATA_FINAL = $AanoFinal.$AmesFinal.$AdiaFinal;
               // $COD_IDENTIFICACAO = "8";
                $COD_CONVENIO = "3";
                $COD_NATUREZA = "3";
                $COD_FINALIDADE = "1";
          
                $TIPO = "10";    
                $linha = "10".$CNPJ.$INSC_ESTADUAL.$RAZAO_SOCIAL_CONTRIBUINTE.$MUNICIPIO.$UF.$FAX.$DATA_INICIAL.$DATA_FINAL.$COD_IDENTIFICACAO.$COD_CONVENIO.$COD_NATUREZA.$COD_FINALIDADE."\r\n";
           
            //REGISTRO 11
       
            $TIPO = "11";
            $ENDERECO =  str_pad($row['empresa_endereco'], 34 , ' ' , STR_PAD_RIGHT); 
            $NUMERO_ENDER  = str_pad($row['empresa_numero'], 5 , '0' , STR_PAD_RIGHT); 
            $COMPLEMENTO =  str_pad($row['empresa_complemento'], 22 , ' ' , STR_PAD_RIGHT); 
            $BAIRRO = str_pad($row['empresa_bairro'], 15 , ' ' , STR_PAD_RIGHT); 
            $CEP  = str_pad($row['CEP'], 8 , ' ' , STR_PAD_RIGHT); 
            $NOME_CONTATO = str_pad($row['empresa_contatosSintegra'], 28 , ' ' , STR_PAD_RIGHT); 
            $TELEFONE =  str_pad($row['empresa_telefone'], 12 , '0' , STR_PAD_RIGHT); 
       
            $linha = $linha."11".$ENDERECO.$NUMERO_ENDER.$COMPLEMENTO.$BAIRRO.$CEP.$NOME_CONTATO.$TELEFONE."\r\n";
            }
       
           //REGISTRO 50 - COMPRA //NFE_CFOPEntr <> NFE_CFOP
          
           $_sql = "SELECT nota_ent_item.NFE_CFOPEntr as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE, date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
           SUM(NF_CUSTO_ORIG*NFE_QTDADE) AS NFE_TOTALNFITEM,SUM(NF_IPI_vIPI+NF_vICMSST+NF_FRETE) AS NFE_TOTALIMPOSTO,
            NFE_TOTALNF,NFE_BASEICM,NFE_TOTALICM
           FROM ".$_SESSION['BASE'].".nota_ent_base
           LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
           LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC
           LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao    
           WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
           and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353'  
           OR nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
           and nota_ent_item.NFE_CFOP IS NULL and cfop.NAT_CODIGO <> 2353 and cfop.NAT_CODIGO <> '1353' 
           group by nota_ent_item.NFE_CFOP,NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";
         
           $statement = $pdo->query("$_sql");
           $retorno = $statement->fetchAll();

           foreach ($retorno as $row) {
            $TIPO = "50";
            //  $CODIGO_PRODUTO = $row['NFE_CODIGO'];
              $CFOP = trim($row['NFE_CFOP']);
              if($CFOP == "0" or $CFOP == "") {
                $CFOP = $row['NAT_CODIGO'];
              }
           
            //** */  $CFOP = CFOP($CFOP);

              $totalnf = $row["NFE_TOTALNFITEM"]+$row["NFE_TOTALIMPOSTO"];
                //$totalnf = $row["NFE_TOTALNF"];
                                    
                if($totalnf == 0 OR $totalnf  == ""){
                    $totalnf = $row["NFE_TOTALNF"];
                }
            
  
        $INSCR_ESTADUAL_A = $row['INSCR_ESTADUAL'];
       
       if(trim($INSCR_ESTADUAL_A) == ""){
            $INSCR_ESTADUAL_A = "ISENTO";
        }

        //telefonia
        $tipo_modelo  = 55;
        if($CFOP >= '1300' and  $CFOP <= '1306'){
          $tipo_modelo = '22';
        }

          //TELECOMUNICAAO
          if($CFOP == '1303' and  $CFOP <= '1303'){
            $tipo_modelo = '21';
          }

        //tranportaDORA
        if($CFOP == '1353' OR  $CFOP == '2353'){
          $tipo_modelo = '57';
        }

        //ENERGIA
        if($CFOP == '1253' ){
          $tipo_modelo = '06';
        }


        $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ']), 14 , '0' , STR_PAD_LEFT);
        $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
        $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
        $UF = str_pad($row['UF'] , 2, ' ' , STR_PAD_RIGHT);
        $MODELO_NFE = str_pad($tipo_modelo , 2, '0' , STR_PAD_RIGHT);
        $SERIE = Str_pad($row['NFE_SERIE']  , 3 , ' ' , STR_PAD_RIGHT);
        $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
        $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
        $EMITENTE = "T";
        $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($totalnf, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
     
      //28/06  $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
          $BASE_CALC_ICMS = str_pad(0  , 13 , '0' , STR_PAD_LEFT);
      //28/06  $VALOR_ICMS = str_pad(str_replace(".","",number_format($row["NFE_TOTALICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
          $VALOR_ICMS = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
          $VALOR_ISENTO = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
          $VALOR_OUTRO = str_pad(str_replace(".","",number_format($totalnf, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
     
          $ALIQUOTA_ICMS = str_pad("0"  ,4 , '0' , STR_PAD_LEFT);
          $SITUACAO_NFE = str_pad("N" , 1 , ' ' , STR_PAD_LEFT);   

          $linha = $linha."50".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTO.$VALOR_OUTRO.$ALIQUOTA_ICMS.$SITUACAO_NFE."\r\n";
      
          $reg =  $reg + 1;             
          }
      
          //REGISTRO 50 - VENDAS 
            $_sql = "SELECT  date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao,
            0 as NFE_BASEICM, 0 as NFE_TOTALICM,
            nfed_numeronf,nfed_cfop,nfed_totalnota,nfed_cpfcnpj,nfed_ie,nfed_dUF
            FROM ".$_SESSION['BASE'].".NFE_DADOS      
            WHERE nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0 
            and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
            AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' ";
    
           $consultaMovRequisicao = "$_sql";
           $statement = $pdo->query("$_sql");
           $retorno = $statement->fetchAll();

           foreach ($retorno as $row) {
            $TIPO = "50";
            //  $CODIGO_PRODUTO = $row['NFE_CODIGO'];
              $CFOP = $row['nfed_cfop'];            
       
              $INSCR_ESTADUAL_A = $row['nfed_ie'];
            
             if(trim($INSCR_ESTADUAL_A) == ""){
                  $INSCR_ESTADUAL_A = "ISENTO";
              }
     
               //telefonia
             $tipo_modelo  = 55;
              //telefonia
          
              if($CFOP >= '1300' and  $CFOP <= '1306'){
                $tipo_modelo = '22';
              }
                //TELECOMUNICAAO
             if($CFOP == '1303' and  $CFOP <= '1303'){
               $tipo_modelo = '21';
             }
      
              //tranportaDORA
              if($CFOP == '1353' OR  $CFOP == '2353'){
                $tipo_modelo = '57';
              }
      
              //ENERGIA
              if($CFOP == '1253' ){
                $tipo_modelo = '06';
              }
      
              $CNPJ_A  = str_pad(LimpaVariavelDoc($row['nfed_cpfcnpj']), 14 , '0' , STR_PAD_LEFT);
              $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
              $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
              $UF = str_pad($row['nfed_dUF'], 2, ' ' , STR_PAD_RIGHT);
              $MODELO_NFE = str_pad($tipo_modelo , 2, '0' , STR_PAD_RIGHT);
              $SERIE = Str_pad("2"  , 3 , ' ' , STR_PAD_RIGHT);
              $NUMERO_NFE = Str_pad(substr($row['nfed_numeronf'],-6)  , 6 , '0' , STR_PAD_LEFT);
              $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
              $EMITENTE = "P";
              $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($row["nfed_totalnota"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
           
             //28/06 $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
             //28/06 $VALOR_ICMS = str_pad(str_replace(".","",number_format($row["NFE_TOTALICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
              $BASE_CALC_ICMS = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
              $VALOR_ICMS = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
              $VALOR_ISENTO = str_pad("0"  , 13 , '0' , STR_PAD_LEFT);
              $VALOR_OUTRO = str_pad(str_replace(".","",number_format($row["nfed_totalnota"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
              $ALIQUOTA_ICMS = str_pad("0"  ,4 , '0' , STR_PAD_LEFT);
              $SITUACAO_NFE = str_pad("N" , 1 , ' ' , STR_PAD_RIGHT);
             
      
              $linha = $linha."50".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTO.$VALOR_OUTRO.$ALIQUOTA_ICMS.$SITUACAO_NFE."\r\n";
      
            
              $reg =  $reg + 1;
           }

           if( $reg > 0) { 
            $regfinal =  $regfinal + $reg;
            $TOTAL_TIPO = str_pad("50" , 2 , '0' , STR_PAD_RIGHT);
            $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
            $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
            $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
          }
       $reg  = 0;
    
    
   //REGISTRO 53
   $_sql = "SELECT nota_ent_item.NFE_CFOPEntr as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_TOTALIPI, date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
   SUM(NF_CUSTO_ORIG*NFE_QTDADE) AS NFE_TOTALNFITEM, NFE_TOTALNF,NFE_BASEICM,NFE_TOTALICM,SUM(NFE_TOTALFRETE) AS VALOR_DESPESA,
   SUM(NF_vICMSST) AS VALOR_ST, SUM(NF_vBCST) AS VALOR_BASEST
   FROM ".$_SESSION['BASE'].".nota_ent_base
   LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = NFE_FORNEC
   LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
   LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao
   WHERE nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
   and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353' 
  AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
  AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307' 
  AND nota_ent_item.NFE_CFOP <> '1308'                       
   group by nota_ent_item.NFE_CFOP,NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";
            $consultaMovRequisicao = "$_sql";
              $statement = $pdo->query("$consultaMovRequisicao");
              $retorno = $statement->fetchAll();
      
              foreach ($retorno as $row) {
            //  $CFOP = $row['NAT_CODIGO'];
                        $CFOP = $row['NFE_CFOP'];
                        if($CFOP == "0" or $CFOP == "") {
                        $CFOP = $row['NAT_CODIGO'];
                    }
                    
                  //** */  $CFOP = CFOP($CFOP);
                    // $totalnf = $row["NFE_TOTALNFITEM"];
                    $totalnf = $row["NFE_TOTALNF"];
                    if($totalnf == 0 OR $totalnf  == ""){
                        $totalnf = $row["NFE_TOTALNF"];
                    }

                    $TIPO = "53";
                    $INSCR_ESTADUAL_A = $row['INSCR_ESTADUAL'];
                if(trim($INSCR_ESTADUAL_A) == ""){
                        $INSCR_ESTADUAL_A = "ISENTO";
                    }

                    //telefonia
                    $tipo_modelo  = 55;
                //   if($CFOP >= '1300' and  $CFOP <= '1306'){
                    //    $tipo_modelo = '22';
                    //  }
                    
                    $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
                   $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
                
                    $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
                    $UF = str_pad($row['UF'] , 2, ' ' , STR_PAD_RIGHT);
                    $MODELO_NFE = str_pad($tipo_modelo , 2, '0' , STR_PAD_RIGHT);
                    $SERIE = Str_pad($row['NFE_SERIE']  , 3 , ' ' , STR_PAD_RIGHT);
                    $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
                    $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
                    $EMITENTE = "T";

                      
                    $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["VALOR_BASEST"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
                    $VALOR_ICMS_RETIRO = str_pad(str_replace(".","",number_format($row["VALOR_ST"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
                    $VALOR_DESPESA = str_pad(str_replace(".","",number_format($row["VALOR_DESPESA"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
                    $SITUACAO_NFE = Str_pad("N"  , 1 , '0' , STR_PAD_LEFT);
                // $CODIGO_ANTECIPACAO = Str_pad($CODIGO_ANTECIPACAO  , 1 , '0' , STR_PAD_RIGHT);
                    $BRANCOS29 = str_pad(" "  , 30, ' ' , STR_PAD_LEFT);   
                    

                    $linha = $linha."53".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$BASE_CALC_ICMS.$VALOR_ICMS_RETIRO.$VALOR_DESPESA.$SITUACAO_NFE.$BRANCOS29."\r\n";
                    $reg =  $reg + 1;
       }
        //REGISTRO 53 vendas
        $_sql = "SELECT nfed_id, date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao,
        0 as NFE_BASEICM, 0 as NFE_TOTALICM,
        nfed_numeronf,nfed_cfop,nfed_totalnota,nfed_cpfcnpj,nfed_ie,nfed_dUF
        FROM ".$_SESSION['BASE'].".NFE_DADOS      
        WHERE  nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0 
        and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
        AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";
        $consultaMovRequisicao = "$_sql";

       $statement = $pdo->query("$consultaMovRequisicao");
       $retorno = $statement->fetchAll();

       foreach ($retorno as $row) {

  
            $_sqlITEM = "SELECT SUM(nfe_itensvlrOutros) AS VALOROUTROS
            FROM ".$_SESSION['BASE'].".NFE_ITENS      
            WHERE id_nfedados = '".$row['nfed_id']."'";
            $statement2 = $pdo->query("$_sqlITEM");
            $retornoOutros = $statement2->fetchAll();
            $VALOR_DESPESA = 0;
            foreach ($retornoOutros as $rowOutros) {
                $VALOR_DESPESA  = $rowITEM ["VALOROUTROS"];
            }
     
             

                $TIPO = "53"; //vendas

                $INSCR_ESTADUAL_A = $row['nfed_ie'];
                if(trim($INSCR_ESTADUAL_A) == ""){
                    $INSCR_ESTADUAL_A = "ISENTO";
                }

            
                $CNPJ_A  = str_pad(LimpaVariavelDoc($row['nfed_cpfcnpj']), 14 , '0' , STR_PAD_LEFT);
                $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc( $INSCR_ESTADUAL_A) , 14 , ' ' , STR_PAD_RIGHT);
                $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
                $UF = str_pad($row['nfed_dUF'] , 2, ' ' , STR_PAD_RIGHT);
                $MODELO_NFE = str_pad('55' , 2, '0' , STR_PAD_RIGHT);
                $SERIE = Str_pad(2  , 3 , ' ' , STR_PAD_RIGHT);
                $NUMERO_NFE = Str_pad(substr($row['nfed_numeronf'],-6)  , 6 , '0' , STR_PAD_LEFT);
                $CFOP = Str_pad($row['nfed_cfop'] , 4 , '0' , STR_PAD_RIGHT);
                $EMITENTE = "P";
                $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format($row["NFE_BASEICM"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);              
                $VALOR_ICMS_RETIRO = Str_pad(0 , 13 , '0' , STR_PAD_LEFT);
                
                $VALOR_DESPESA = str_pad(str_replace(".","",number_format($VALOR_DESPESA, 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);              
                $SITUACAO_NFE = Str_pad("N"  , 1 , '0' , STR_PAD_LEFT);
            // $CODIGO_ANTECIPACAO = Str_pad($CODIGO_ANTECIPACAO  , 1 , '0' , STR_PAD_RIGHT);
                $BRANCOS29 = str_pad(" "  , 30, ' ' , STR_PAD_LEFT);   
                

                $linha = $linha."53".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$NUMERO_NFE.$CFOP.$EMITENTE.$BASE_CALC_ICMS.$VALOR_ICMS_RETIRO.$VALOR_DESPESA.$SITUACAO_NFE.$BRANCOS29."\r\n";
                $reg =  $reg + 1;
                       }
                       if( $reg > 0) { 
                         $regfinal =  $regfinal + $reg;
                         $TOTAL_TIPO = str_pad("53" , 2 , '0' , STR_PAD_RIGHT);
                         $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
                         $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
                         $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
                       }
                       $reg  = 0;
   //registro 54
   $_sql = "SELECT CNPJ,UF,NF_SKU,NFE_SERIE,nota_ent_base.NFE_NRO as NUMERONF,nota_ent_item.NFE_CFOPEntr as NFE_CFOP,cfop.NAT_CODIGO as NAT_CODIGO ,NFE_ITEM,NFE_CODIGO,
   NF_ICMS_ST,
    NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,NF_IPI_vIPI,NF_CUSTO_ORIG,
    NF_vICMSST AS VALOR_ST, NF_vBCST AS VALOR_BASEST,nota_ent_item.NF_FRETE AS FRETE
    FROM ".$_SESSION['BASE'].".nota_ent_base
    LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = NFE_FORNEC
    LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
    LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao
    WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
    and nota_ent_item.NFE_CFOP <> 2353 and nota_ent_item.NFE_CFOP <> '1353' 
    AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
    AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307' 
    AND nota_ent_item.NFE_CFOP <> '1308' 
   
";
      
            $statement = $pdo->query("$_sql");
            $retorno = $statement->fetchAll();

            foreach ($retorno as $row) {
                            
                    $CODIGO_PRODUTO = $row['NFE_CODIGO'];
                    //REGISTRO 54
                        
                    $CODIGO_PRODUTO = $row['NFE_CODIGO'];
                    //  $CFOP = $row['NAT_CODIGO'];
                    $CFOP = trim($row['NFE_CFOP']);
                    if($CFOP == "0" or $CFOP == "") {
                    $CFOP = $row['NAT_CODIGO'];
                    }

                 
                
                  //** */  $CFOP = CFOP($CFOP);
                $qtde = explode(".",$row['NFE_QTDADE']);
                $NUMERO_ITEMv =$row["NFE_ITEM"];
                if($NUMERO_ITEMv == 0 ) {
                    $NUMERO_ITEM  = $NUMERO_ITEM + 1;
                }else{
                    $NUMERO_ITEM =$row["NFE_ITEM"];
                }

                    $TIPO = "54";
                
                    $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
                //  $CNPJ  = str_pad($CNPJ  , 14 , '0' , STR_PAD_RIGHT);
                    $MODELO = 55;

                        //telefonia
                        $tipo_modelo  = 55;
                    //  if($CFOP >= '1300' and  $CFOP <= '1306'){
                    //    $tipo_modelo = '22';
                    //  }

                    $MODELO_NFE = str_pad($tipo_modelo , 2 , '0' , STR_PAD_RIGHT);
                    $SERIE_NFE = str_pad($row['NFE_SERIE'], 3 , ' ' , STR_PAD_RIGHT);
                    $NUMERO_NFE = str_pad(substr($row['NUMERONF'],-6) , 6 , '0' , STR_PAD_LEFT);
                    $CFOP = str_pad($CFOP, 4 , '0' , STR_PAD_RIGHT);
                    $CST_SIT_TRIBUTARIA = Str_pad(substr($row['NF_ICMS_ST'],-3)  , 3 , '0' , STR_PAD_RIGHT);
                    $NUMERO_ITEM = Str_pad($NUMERO_ITEM  , 3 , '0' , STR_PAD_LEFT);
                    $CODIGO_PRODUTO = Str_pad($CODIGO_PRODUTO, 14 , ' ' , STR_PAD_RIGHT);
                    $QTDE_PRODUTO = Str_pad($qtde[0] , 8 , '0' , STR_PAD_LEFT).Str_pad($qtde[1] , 3 , '0' , STR_PAD_RIGHT);
                     // $VALOR_PRODUTO = str_pad(str_replace(".","",)  , 12 , '0' , STR_PAD_LEFT);
                    // $VALOR_PRODUTO = number_format(($row["NF_CUSTO_ORIG"]*$row['NFE_QTDADE'])+$row["NF_IPI_vIPI"], 2, '.', ''); atlerado 30/11
                    $VALOR_PRODUTO = number_format(($row["NF_CUSTO_ORIG"]*$row['NFE_QTDADE'])+$row["FRETE"]+$row["NF_IPI_vIPI"]+$row["VALOR_ST"], 2, '.', '');
                    $VALOR_PRODUTO =str_replace(".","",$VALOR_PRODUTO); 
                    $VALOR_PRODUTO  = str_pad( $VALOR_PRODUTO, 12 , '0' , STR_PAD_LEFT);
                  
                    $VALOR_DESCONTO =str_replace(".","",$row["NF_FRETE"]); 
                    $VALOR_DESCONTO = Str_pad($VALOR_DESCONTO , 12 , '0' , STR_PAD_LEFT); //FRETE
                    //Aproveitando o assunto, gostaríamos de solicitar, que a configuração do Sintegra seja realizada de forma os registros de entradas e saídas não contenham os dados de Base de Calculo de ICMS, Alíquota de ICMS e Valor de ICMS. Nem no Registro 50,  if(trim($INSCR_ESTADUAL_A) == ""){, 54                   
                    
                    // $BASE_ICMS  = str_pad(str_replace(".","",number_format($row["VALOR_BASEST"], 2, '.', ''))  , 12 , '0' , STR_PAD_LEFT);
                    // $BASE_ICMS_SUBSTITUICAO = str_pad(str_replace(".","",number_format($row["VALOR_ST"], 2, '.', ''))  , 12 , '0' , STR_PAD_LEFT);
                    $BASE_ICMS  = str_pad(0 , 12 , '0' , STR_PAD_LEFT);
                    $BASE_ICMS_SUBSTITUICAO = str_pad(0 , 12 , '0' , STR_PAD_LEFT);
                    $VALOR_IPI = Str_pad(0 , 12 , '0' , STR_PAD_LEFT);
                    $ALIQUOTA_ICMS = Str_pad(0  , 4 , '0' , STR_PAD_LEFT);
                    
                    $linha =  $linha."54".$CNPJ_A.$MODELO_NFE.$SERIE_NFE.$NUMERO_NFE.$CFOP.$CST_SIT_TRIBUTARIA.$NUMERO_ITEM.$CODIGO_PRODUTO.$QTDE_PRODUTO.$VALOR_PRODUTO.$VALOR_DESCONTO.$BASE_ICMS.$BASE_ICMS_SUBSTITUICAO.$VALOR_IPI.$ALIQUOTA_ICMS."\r\n";
                    $reg =  $reg + 1;
         }

           //registro 54 vendas
            $_sql = "SELECT nfed_id, date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao,
            0 as NFE_BASEICM, 0 as NFE_TOTALICM,
            nfed_numeronf,nfed_cfop,nfed_totalnota,
            quantidade,situacaotributario_nfeitens,codigoproduto_nfeitens,vlrunitario_nfeitens,nfed_cpfcnpj,nfed_ie,nfed_dUF
            FROM ".$_SESSION['BASE'].".NFE_DADOS   
            LEFT JOIN ".$_SESSION['BASE'].".NFE_ITENS ON id_nfedados  = nfed_id   
            WHERE  nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0
            and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
            AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' 
            ORDER BY nfed_id asc";
          
            $statement = $pdo->query("$_sql");
            $retorno = $statement->fetchAll();

            foreach ($retorno as $row) {
                if($row['nfed_numeronf'] != $_nf){
                    $NUMERO_ITEM =1;
                }else{
                    $NUMERO_ITEM  = $NUMERO_ITEM + 1;
                }
        
                $CFOP = $row['nfed_cfop'];

                    $qtde = explode(".",$row['quantidade']);
            
                    $TIPO = "54";

                    $CNPJ_A  = str_pad(LimpaVariavelDoc($row['nfed_cpfcnpj'])  , 14 , '0' , STR_PAD_LEFT);
                    //  $CNPJ  = str_pad($CNPJ  , 14 , '0' , STR_PAD_RIGHT);
                    $MODELO = 55;
                        //telefonia
                    $tipo_modelo  = 55;
                //   if($CFOP >= '1300' and  $CFOP <= '1306'){
                //    $tipo_modelo = '22';
                //  }
                    $MODELO_NFE = str_pad($tipo_modelo , 2 , '0' , STR_PAD_RIGHT);
                    $SERIE_NFE = str_pad(2, 3 , ' ' , STR_PAD_RIGHT);
                    $NUMERO_NFE = str_pad(substr($row['nfed_numeronf'],-6) , 6 , '0' , STR_PAD_LEFT);
                    $CFOP = str_pad($CFOP, 4 , '0' , STR_PAD_RIGHT);
                    $CST_SIT_TRIBUTARIA = Str_pad(substr($row['situacaotributario_nfeitens'],0,-3) , 3 , '0' , STR_PAD_RIGHT);
                    $NUMERO_ITEM = Str_pad($NUMERO_ITEM  , 3 , '0' , STR_PAD_LEFT);
                    $CODIGO_PRODUTO = Str_pad($row['codigoproduto_nfeitens'], 14 , ' ' , STR_PAD_RIGHT);
                    $QTDE_PRODUTO = Str_pad($qtde[0] , 8 , '0' , STR_PAD_LEFT).Str_pad($qtde[1] , 3 , '0' , STR_PAD_RIGHT);
                    // $VALOR_PRODUTO = str_pad(str_replace(".","",)  , 12 , '0' , STR_PAD_LEFT);
                    $VALOR_PRODUTO =str_replace(".","",number_format($row["vlrunitario_nfeitens"]*$row['quantidade'], 2, '.', '')); 
                    $VALOR_PRODUTO  = str_pad( $VALOR_PRODUTO, 12 , '0' , STR_PAD_LEFT);
                    $VALOR_DESCONTO = Str_pad(0  , 12 , '0' , STR_PAD_LEFT);
                    //Aproveitando o assunto, gostaríamos de solicitar, que a configuração do Sintegra seja realizada de forma os registros de entradas e saídas não contenham os dados de Base de Calculo de ICMS, Alíquota de ICMS e Valor de ICMS. Nem no Registro 50, 70, 54
                    $BASE_ICMS = Str_pad(0  , 12 , '0' , STR_PAD_LEFT);
                    $BASE_ICMS_SUBSTITUICAO = Str_pad(0  , 12 , '0' , STR_PAD_LEFT);
                    $VALOR_IPI = Str_pad(0 , 12 , '0' , STR_PAD_LEFT);
                    $ALIQUOTA_ICMS = Str_pad(0  , 4 , '0' , STR_PAD_LEFT);

                    $linha =  $linha."54".$CNPJ_A.$MODELO_NFE.$SERIE_NFE.$NUMERO_NFE.$CFOP.$CST_SIT_TRIBUTARIA.$NUMERO_ITEM.$CODIGO_PRODUTO.$QTDE_PRODUTO.$VALOR_PRODUTO.$VALOR_DESCONTO.$BASE_ICMS.$BASE_ICMS_SUBSTITUICAO.$VALOR_IPI.$ALIQUOTA_ICMS."\r\n";
                    $reg =  $reg + 1;

                    $_nf = $row['nfed_numeronf'];
               }
            if( $reg > 0) { 
            $regfinal =  $regfinal + $reg;
            $TOTAL_TIPO = str_pad("54" , 2 , '0' , STR_PAD_RIGHT);
            $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
            $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
            $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
            }

            $reg  = 0;

  //REGISTRO 60R
  //Resumo Mensal (60R): Registro de mercadoria/produto ou serviço processado em EC

  /*   $TIPO = "60";
     $SUBTIPO  = "R";
     $MES_ANO = str_pad($MES_ANO , 6 , '0' , STR_PAD_RIGHT);
     $CODIGO_PRODUTO = Str_pad($CODIGO_PRODUTO  , 14 , ' ' , STR_PAD_RIGHT);
     $CODIGO_SERVICO = Str_pad($CODIGO_SERVICO  , 14 , ' ' , STR_PAD_RIGHT);
     $QTDE_PRODUTO = Str_pad($QTDE_PRODUTO  , 11 , '0' , STR_PAD_RIGHT);
     $VALOR_PRODUTO = Str_pad($VALOR_PRODUTO  , 16 , '0' , STR_PAD_RIGHT);
     $VALOR_PRODUTO_BRUTO = Str_pad($VALOR_PRODUTO_BRUTO  , 16 , '0' , STR_PAD_RIGHT);
     $VALOR_PRODUTO_ACUMULADO = Str_pad($VALOR_PRODUTO_ACUMULADO  , 16 , '0' , STR_PAD_RIGHT);
     $BASE_ICMS = Str_pad($VALOR_ICMS  , 16 , '0' , STR_PAD_RIGHT);
     $SITUACAO_TRIBUTARIA_ALIQ = Str_pad($SITUACAO_TRIBUTARIA_ALIQ  , 4 , '0' , STR_PAD_RIGHT);
     $SITUACAO_TRIBUTARIA_ALIQ_PROD = Str_pad($SITUACAO_TRIBUTARIA_ALIQ_PROD  , 4 , '0' , STR_PAD_RIGHT);
     $BRANCOS = Str_pad($BRANCOS  , 53 , ' ' , STR_PAD_RIGHT);


     $linha = "60R".$MES_ANO.$CODIGO_PRODUTO.$CODIGO_SERVICO.$QTDE_PRODUTO.$VALOR_PRODUTO.$VALOR_PRODUTO_BRUTO.$VALOR_PRODUTO_ACUMULADO.$BASE_ICMS.$SITUACAO_TRIBUTARIA_ALIQ.$SITUACAO_TRIBUTARIA_ALIQ_PROD.$BRANCOS."\r\n";
     */

//REGISTRO 61
//REGISTRO 61 - Documentos fiscais não emitidos por equipamento emissor de cupom fiscal

$_sql = "Select nfed_serie,nfed_totalnota,nfed_basecalculo,nfed_numeronf,
date_format(nfed_dataautorizacao,'%Y%m%d') as dtemissao from ".$_SESSION['BASE'].".NFE_DADOS
    where nfed_numeronf > 0 and nfed_cancelada = 0 and nfed_chave <> '' and nfed_modelo <> '55' and nfed_modelo <> '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' and nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";                          
    $consultaMovRequisicao = "$_sql";
        $statement = $pdo->query("$consultaMovRequisicao");
        $retorno = $statement->fetchAll();
        foreach ($retorno as $row) {
            $TIPO = "61";
            $BRANCOS = Str_pad(''  , 14 , ' ' , STR_PAD_RIGHT);
            $BRANCOS1 = Str_pad(''  , 14 , ' ' , STR_PAD_RIGHT);
            $DATA_EMISSAO = str_pad($row['dtemissao'], 8, '0' , STR_PAD_RIGHT);
            $MODELO = Str_pad('65'  , 2 , '0' , STR_PAD_RIGHT);
            $SERIE = Str_pad("2" , 3 , ' ' , STR_PAD_RIGHT);
            $SUB_SERIE = Str_pad('' , 2 , ' ' , STR_PAD_RIGHT);            
            $NUMERO_INICIAL = Str_pad($row['nfed_numeronf'] ,6 , '0' , STR_PAD_LEFT);
            $NUMERO_FINAL = Str_pad($row['nfed_numeronf'] ,6 , '0' , STR_PAD_LEFT);
            $VALOR_TOTAL = str_pad(str_replace(".","",number_format($row["nfed_totalnota"], 2, '.', ''))  , 13 , '0' , STR_PAD_LEFT);
            $BASE_CALC_ICMS = str_pad(str_replace(".","",number_format(0, 2))  , 13 , '0' , STR_PAD_LEFT);
            $VALOR_ICMS =  str_pad(str_replace(".","",number_format(0, 2))  , 12 , '0' , STR_PAD_LEFT);
            $VALOR_ISENTA =  str_pad(str_replace(".","",number_format(0, 2))  , 13 , '0' , STR_PAD_LEFT);
            $VALOR_OUTRA = str_pad(str_replace(".","",number_format(0, 2))  , 13 , '0' , STR_PAD_LEFT);
            $ALIQUOTA_ICMS = Str_pad(0  , 4 , '0' , STR_PAD_LEFT);
            $BRANCOS3 = Str_pad(''  , 1 , ' ' , STR_PAD_RIGHT);
       
            $linha = $linha."61".$BRANCOS.$BRANCOS1.$DATA_EMISSAO.$MODELO.$SERIE.$SUB_SERIE.$NUMERO_INICIAL.$NUMERO_FINAL.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTA.$VALOR_OUTRA.$ALIQUOTA_ICMS.$BRANCOS3."\r\n";
            $reg =  $reg + 1;
        }
       
       
    

        //REGISTRO 61R
        $_sql = "Select CODIGO_ITEM,QUANTIDADE,Valor_unitario_desc,
        date_format(nfed_dataautorizacao,'%m%Y') as dtemissao from ".$_SESSION['BASE'].".NFE_DADOS
        left join ".$_SESSION['BASE'].".saidaestoqueitem on NUMERO = nfed_pedido
        where nfed_numeronf > 0  and nfed_cancelada = 0 and nfed_chave <> '' and nfed_modelo <> '55' and nfed_modelo <> '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' and nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";                          
        $consultaMovRequisicao = "$_sql";
        $statement = $pdo->query("$consultaMovRequisicao");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {

            $TIPO = "61R";
            $MES_ANO = Str_pad($row["dtemissao"]  , 6 , ' 0' , STR_PAD_RIGHT);
            $CODIGO_PRODUTO = Str_pad($row["CODIGO_ITEM"] , 14 , ' ' , STR_PAD_RIGHT);
            $QTDE_PRODUTO = str_pad($row["QUANTIDADE"]  , 13, '0' , STR_PAD_RIGHT);
            $VALOR_BRUTO_PRODUTO = str_pad(str_replace(".","",number_format($row['Valor_unitario_desc'], 2, '.', ''))  , 16 , '0' , STR_PAD_LEFT);
            $BASE_CALC_ICMS = Str_pad(0 , 16 , '0' , STR_PAD_LEFT);
            $ALIQUOTA_ICMS = Str_pad(0 , 4 , '0' , STR_PAD_LEFT);
            $BRANCOS = Str_pad(''  ,54 , ' ' , STR_PAD_RIGHT);
       
            if($row["CODIGO_ITEM"]== 1) { 
               $diverso = "1";
            }
            if(trim($CODIGO_PRODUTO)!= ""){

           
            $linha = $linha."61R".$MES_ANO.$CODIGO_PRODUTO.$QTDE_PRODUTO.$VALOR_BRUTO_PRODUTO.$BASE_CALC_ICMS.$ALIQUOTA_ICMS.$BRANCOS."\r\n";
        
            $reg =  $reg + 1;
        }
        }
        if( $reg > 0) { 
           $regfinal =  $regfinal + $reg;
         $TOTAL_TIPO = str_pad("61" , 2 , '0' , STR_PAD_RIGHT);
         $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
         $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
         $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
       }
       $reg  = 0;

   //REGISTRO 70 transporte
$_sql = "SELECT cfop.NAT_CODIGO as NAT_CODIGO,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,
date_format(NFE_DATAENTR,'%Y%m%d') as dtemissao,
NFE_TOTALNF,NFE_BASEICM,NFE_TOTALICM
FROM ".$_SESSION['BASE'].".nota_ent_base
LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC
LEFT JOIN ".$_SESSION['BASE'].".cfop ON ID  = NFe_Cod_Nat_Operacao    
WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
and cfop.NAT_CODIGO  = '2353'  or
nota_ent_base.NFE_NRO > '0' and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' and
cfop.NAT_CODIGO ='1353'
group by NAT_CODIGO,NFE_NATOPER,nota_ent_base.NFE_NRO,CNPJ,INSCR_ESTADUAL, UF, NFE_SERIE,NFE_DATAENTR";
$consultaMovRequisicao = "$_sql";
$statement = $pdo->query("$consultaMovRequisicao");
$retorno = $statement->fetchAll();

while($row = mysqli_fetch_array($mov))									 
    {
        $TIPO = "70";
        //  $CODIGO_PRODUTO = $row['NFE_CODIGO'];
          $CFOP = $row['NAT_CODIGO'];
             
      
        // $CNPJ_A  = str_pad(LimpaVariavelDoc($CNPJ), 14 , '0' , STR_PAD_LEFT);
          $CNPJ_A  = str_pad(LimpaVariavelDoc($row['CNPJ'])  , 14 , '0' , STR_PAD_LEFT);
         // $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($INSC_ESTADUAL) , 14 , ' ' , STR_PAD_LEFT);
          $INSCR_ESTADUAL_A = str_pad(LimpaVariavelDoc($row['INSCR_ESTADUAL']) , 14 , ' ' , STR_PAD_RIGHT);
          $DATA_EMISSAO = str_pad($row['dtemissao']  , 8 , ' ' , STR_PAD_RIGHT);
          $UF = str_pad($UF_EMPRESA , 2, ' ' , STR_PAD_RIGHT);
          $MODELO_NFE = str_pad('08' , 2, '0' , STR_PAD_RIGHT);
          $SERIE = Str_pad("U"  ,1 , ' ' , STR_PAD_RIGHT);
          $subSERIE = "  ";
         // $NUMERO_NFE = Str_pad(substr($row['NFE_NRO'],-6)  , 6 , '0' , STR_PAD_LEFT);
         $NUMERO_NFE = substr(Str_pad( $row['NFE_NRO'] , 6 , '0' , STR_PAD_LEFT),-6) ;
          $CFOP = Str_pad($CFOP , 4 , '0' , STR_PAD_RIGHT);
        
          $VALOR_TOTAL =  str_pad(str_replace(".","",number_format($row["NFE_TOTALNF"], 2, '.', ''))  , 14 , '0' , STR_PAD_LEFT);
       
        
          $BASE_CALC_ICMS = str_pad("0"  , 14 , '0' , STR_PAD_LEFT);
          $VALOR_ICMS = str_pad("0"  , 13, '0' , STR_PAD_LEFT);
          $VALOR_ISENTO = str_pad("0"  , 14 , '0' , STR_PAD_LEFT);
          $VALOR_OUTRO = str_pad(str_replace(".","",number_format($row["NFE_TOTALNF"], 2, '.', ''))  , 14 , '0' , STR_PAD_LEFT);
          $ALIQUOTA_ICMS = str_pad("0"  ,4 , '0' , STR_PAD_LEFT);
          $SITUACAO_NFE = str_pad("N" , 1 , ' ' , STR_PAD_RIGHT);
          $MODALIDADEX = "1"; 
        
          $linha = $linha."70".$CNPJ_A.$INSCR_ESTADUAL_A.$DATA_EMISSAO.$UF.$MODELO_NFE.$SERIE.$subSERIE.$NUMERO_NFE.$CFOP.$VALOR_TOTAL.$BASE_CALC_ICMS.$VALOR_ICMS.$VALOR_ISENTO.$VALOR_OUTRO.$MODALIDADEX.$SITUACAO_NFE."\r\n";
      
        
          $reg =  $reg + 1;
    
   
}


if( $reg > 0) { 
$regfinal =  $regfinal + $reg;
$TOTAL_TIPO = str_pad("70" , 2 , '0' , STR_PAD_RIGHT);
$TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
$_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
$_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
}
$reg  = 0;
//REGISTRO 75

        $delete = "DELETE FROM ".$_SESSION['BASE'].".temp_sintegraitem ";
        $statement = $pdo->prepare("$delete");
        $statement->execute();
    
        $_sql = "INSERT INTO ".$_SESSION['BASE'].".temp_sintegraitem (CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA)
        SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA
        FROM ".$_SESSION['BASE'].".nota_ent_base
        LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
        left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = NFE_CODIGO
        WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' 
        AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
        AND nota_ent_item.NFE_CFOP <> '1300' AND nota_ent_item.NFE_CFOP <> '1301' AND nota_ent_item.NFE_CFOP <> '1302' AND nota_ent_item.NFE_CFOP <> '1303' 
        AND nota_ent_item.NFE_CFOP <> '1304' AND nota_ent_item.NFE_CFOP <> '1305' AND nota_ent_item.NFE_CFOP <> '1306' AND nota_ent_item.NFE_CFOP <> '1307'
        AND nota_ent_item.NFE_CFOP <> '1308'    AND nota_ent_item.NFE_CFOP <> '' 
        OR nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' 
        AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59'  
        group by CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA";
        $statement = $pdo->prepare("$_sql");
        $statement->execute();


        $_sql = "INSERT INTO ".$_SESSION['BASE'].".temp_sintegraitem (CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA) 
        SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA
        FROM ".$_SESSION['BASE'].".NFE_DADOS   
        LEFT JOIN ".$_SESSION['BASE'].".NFE_ITENS ON id_nfedados  = nfed_id   
        left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = codigoproduto_nfeitens
        WHERE  nfed_modelo = '55' and  nfed_cancelada = 0 AND nfed_xml_protocolado <> ''  and nfed_cancelada = 0
        and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' 
        AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' 
        ORDER BY nfed_id asc";
        $statement = $pdo->prepare("$_sql");
        $statement->execute();

      
        $_sql = "INSERT INTO ".$_SESSION['BASE'].".temp_sintegraitem (CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA) 
        Select CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA from ".$_SESSION['BASE'].".NFE_DADOS
        left join ".$_SESSION['BASE'].".saidaestoqueitem on NUMERO = nfed_pedido
        left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = CODIGO_ITEM
        where nfed_numeronf > 0  and nfed_cancelada = 0 and nfed_chave <> '' and nfed_modelo <> '55' and nfed_modelo <> '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' and nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'";
        $statement = $pdo->prepare("$_sql");
        $statement->execute();

        $_sql = "SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA
        FROM ".$_SESSION['BASE'].".temp_sintegraitem  
        group by CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA";
        $consultaMovRequisicao = "$_sql";

        $statement = $pdo->query("$consultaMovRequisicao");
        $retorno = $statement->fetchAll();

     foreach ($retorno as $row) {
        $TIPO = "75";
        $DATAINICIAL = $Aano.$Ames.$Adia;
        $DATAFINAL = $AanoFinal.$AmesFinal.$AdiaFinal;
        $CODIGO_PRODUTO = str_pad($row['CODIGO_FORNECEDOR'] , 14 , ' ' , STR_PAD_RIGHT);
        $NCM = str_pad($row['Cod_Class_Fiscal']   , 8 , ' ' , STR_PAD_RIGHT);
        $DESCRICAO = str_pad(substr(trim($row['DESCRICAO']),0,53)  , 53, ' ' , STR_PAD_RIGHT);
        $UN = str_pad($row['UNIDADE_MEDIDA']  , 6 , ' ' , STR_PAD_RIGHT);
        $ALIQUOTA_IPI = str_pad('0'  , 5 , '0' , STR_PAD_LEFT);
        $ALIQUOTA_ICMS = str_pad('0'  , 4 , '0' , STR_PAD_LEFT);
        $ALIQUOTA_REDUCAO_ICMS = str_pad('0'  , 5 , '0' , STR_PAD_LEFT);
        $BASE_CALC_ICMS_REDUCAO = str_pad('0'  , 13 , '0' , STR_PAD_LEFT);
       
        if($row['DESCRICAO'] != "") {
      
        $linha =  $linha."75".$DATAINICIAL.$DATA_FINAL.$CODIGO_PRODUTO.$NCM.$DESCRICAO.$UN.$ALIQUOTA_IPI.$ALIQUOTA_ICMS.$ALIQUOTA_REDUCAO_ICMS.$BASE_CALC_ICMS_REDUCAO."\r\n";
  
        $reg =  $reg + 1;
    }
              }
              if( $reg > 0) { 
               if( $diverso != "") {
                   $CODIGO_PRODUTO = str_pad("1" , 14 , ' ' , STR_PAD_RIGHT);
                   $linha =  $linha."75".$DATAINICIAL.$DATA_FINAL.$CODIGO_PRODUTO.$NCM.$DESCRICAO.$UN.$ALIQUOTA_IPI.$ALIQUOTA_ICMS.$ALIQUOTA_REDUCAO_ICMS.$BASE_CALC_ICMS_REDUCAO."\r\n";
                   $reg =  $reg + 1;
       
               }
                $regfinal =  $regfinal + $reg;
                $TOTAL_TIPO = str_pad("75" , 2 , '0' , STR_PAD_RIGHT);
                $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
       
               $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
                $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
              }
              $reg  = 0;
       //88
        $_sql = "SELECT CODIGO_FORNECEDOR,Cod_Class_Fiscal,DESCRICAO,UNIDADE_MEDIDA,Codigo_Barra
        FROM ".$_SESSION['BASE'].".nota_ent_base
        LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
        left join ".$_SESSION['BASE'].".itemestoque on CODIGO_FORNECEDOR = NFE_CODIGO
        WHERE  nota_ent_base.NFE_NRO > '0' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' ";
        $statement = $pdo->query("$_sql");
        $retorno = $statement->fetchAll();


     foreach ($retorno as $row) {
        $TIPO = "88";
        $subtipop = "EAN";
        $VERSAO = "13";
        $CODIGO_PRODUTO = str_pad($row['CODIGO_FORNECEDOR'] , 14 , ' ' , STR_PAD_RIGHT);
       
        $DESCRICAO = str_pad(substr($row['DESCRICAO'],0,53)  , 53, ' ' , STR_PAD_RIGHT);
        $UN = str_pad($row['UNIDADE_MEDIDA']  , 6 , ' ' , STR_PAD_RIGHT);
        $CODIGO_barra = str_pad($row['Codigo_Barra'] , 14 , ' ' , STR_PAD_RIGHT);
        $BRANCOS = Str_pad(''  ,32 , ' ' , STR_PAD_RIGHT);
       
        $linha =  $linha."88".$subtipop.$VERSAO.$CODIGO_PRODUTO.$DESCRICAO.$UN.$CODIGO_barra.$BRANCOS."\r\n";
       
       
       $reg =  $reg + 1;
       }
       if( $reg > 0) { 
        $regfinal =  $regfinal + $reg;
       $TOTAL_TIPO = str_pad("88" , 2 , '0' , STR_PAD_RIGHT);
       $TOTAL_REGISTRO = str_pad($reg  , 8 , '0' , STR_PAD_LEFT);
       $_totalizador = $_totalizador.$TOTAL_TIPO.$TOTAL_REGISTRO;
       $_totalizadorGeral = $_totalizadorGeral + $TOTAL_REGISTRO;
       }
       $reg  = 0;
       
      
//REGISTRO 90

     $TIPO = "90";
     $CNPJ  = str_pad($CNPJ  , 14 , '0' , STR_PAD_RIGHT);
     $INSCR_ESTADUAL = str_pad($INSC_ESTADUAL  , 14 , ' ' , STR_PAD_RIGHT);
     $TOTAL_TIPO = str_pad($TOTAL_TIPO  , 2 , '0' , STR_PAD_RIGHT);
     
  
     $NUMERO_REG_90 = str_pad('2'  , 1 , '0' , STR_PAD_RIGHT);
     $X = 95-(strlen($_totalizador));          
     $branco  = str_pad(' '  ,  $X , ' ' , STR_PAD_RIGHT);
     $branco2  = str_pad(' '  , 98-(strlen($regfinal)) , ' ' , STR_PAD_RIGHT);
     $linha = $linha."90".$CNPJ.$INSCR_ESTADUAL.$_totalizador.$branco.$NUMERO_REG_90."\r\n";
     
     $_totalizadorGeral = $_totalizadorGeral + 4;

     

     $branco2  = str_pad(' '  ,85 , ' ' , STR_PAD_RIGHT);
  
     $_totalizadorGeral = str_pad($_totalizadorGeral  , 8 , '0' , STR_PAD_LEFT);
     $linha = $linha."90".$CNPJ.$INSCR_ESTADUAL."99".$_totalizadorGeral.$branco2.$NUMERO_REG_90."\r\n";

     $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
     fwrite($fp,$linha);
     fclose($fp); 
                        ?>
                    <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarsintegra()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar Sintegra</button>
                        <?php

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


    
// Instancia a Classe Zip
$diretorio = $dir."/";
$zip = new ZipArchive();
// Cria o Arquivo Zip, caso não consiga exibe mensagem de erro e finaliza script
if($zip->open($diretorio.$tiponf.'_'.$nomearquivo.'.zip', ZIPARCHIVE::CREATE) == TRUE)
{
// Insere os arquivos que devem conter no arquivo zip

$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if(substr($filename,-3) == "txt"){
    $zip->addFile($diretorio.$filename,$filename);
}}

//echo 'Arquivo criado com sucesso.';
}
else
{
exit('O Arquivo não pode ser criado.');
}

    // Fecha arquivo Zip aberto
    $zip->close();

    $arquivo = $tiponf."_".$nomearquivo.'.zip';
    if( file_exists($diretorio.$arquivo)){ 
    ?><a href="<?=$diretorio.$arquivo;?>" target="_blank"><?=$arquivo;?></a>
<?php
    }else{
        echo "Sem registros nesse periodo";
    }


}


else if ($acao == 7) { //gerar COM Situação tributaria
 
    try {
        $porfornecedor = $_parametros['rel-fornecedor'];
        $filtrarDT = $_parametros['rel-dt'];
        
         if($_parametros['_numeronf'] != '') {
            $filnumero = " AND nota_ent_item.NFE_NRO = '".$_parametros['_numeronf']."' OR nota_ent_item.NFE_NRO = '".$_parametros['_numeronf']."'";
            $filnumero2 = " AND NFE_NRO = '".$_parametros['_numeronf']."' OR NFE_NRO = '".$_parametros['_numeronf']."'";
        }
        $tiponf = "REL_ComSituacaoTributaria" ;
        
        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 
        
        


    $cliente = $_SESSION['BASE_ID'];

    $mesInicial = explode("-", $_parametros['nf-inicial'] );   

    $Ames= $mesInicial[1];
    $Aano  = $mesInicial[0];
    $nomearquivo = $Ames."_".$Aano;
  
 
    $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
    $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/Rel_comSubstituicaoTributaria_".$nomearquivo.".csv";
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }


        //LISTA PADRAO SEM FORNECEDOR

       if($porfornecedor == 0) { 
/*
        $_sql = "SELECT NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
        FROM ".$_SESSION['BASE'].".nota_ent_base
        LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
        LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
        LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal       
        LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC    
        WHERE  NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' ";
  */
    $_sql = "SELECT nota_ent_item.NFE_NRO,fabricante.NOME,nota_ent_item.NFE_CFOP,NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,fabricante.UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
    FROM ".$_SESSION['BASE'].".nota_ent_base
    LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
    LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
    LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal   
    LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE   = nota_ent_base.NFE_FORNEC       
    LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC    
    WHERE   NFE_IDG = ''  AND nota_ent_item.NFE_CFOP <> '6949' and  nota_ent_item.NFE_CFOP <> '6910' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero ";

        $statement = $pdo->query("$_sql");
       
        $retorno = $statement->fetchAll();

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
       // $_itemlinha = "Codigo;Descrição;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
        $_itemlinha = "Fornecedor;Numero NF;Codigo;Descrição;cfop;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
        //UF
          //  $_xml =$row['nfed_xml_protocolado'];
          $NUMERO_NFE = $row['NFE_NRO'];
          $VALORNF = number_format($row2['NFE_TOTALNF'], 2, ',', '.');
          $FORNECEDOR = $row['NOME'];
           // $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi
           $vlsemipi = $row['NF_CUSTO_ORIG']; // VALOR ipi
            $vlTotalsemipi = $vlsemipi*$row['NFE_QTDADE'] ;  //35,20
            //$vlicmsOrigem = $row['NF_vICMS'];
            $vlicmsOrigem =  $vlTotalsemipi*$icms/100;

            $picms = $row["NF_pICMS"];
            if( $picms == 0){
                if($row['NFE_CFOP'] == 5102){
                $picms = 18;
                }else{
               // $picms = 12;
                }
            }
           // 
            $mva = $row['ncmmva_mva'];
           if($picms < 18){
          
          
            //  $vlicmsOrigem = $vlTotalsemipi*(12/100); // VALOR DO ICMS NO PR 4,22
              $vlcomipi = ($row['NFE_TOTALITEM']); // VALOR ipi 36,12
          
              $vlmva= ($vlcomipi*($row['ncmmva_mva']/100))  ; // VALOR ICMS ST 13,558
              $BASESTR =$vlcomipi+ $vlmva+$vlicmsOrigem;
              $vlricmsDestino =  $BASESTR*(18/100);   //9,69    
              $vlicmsDiferenca  = $vlricmsDestino-$vlicmsOrigem;
              if($picms <= 4){
                  $vlicmsDiferenca = 0;
                }
          
          }else{
          $vlcomipi = 0;
          $vlrbase  = 0;
          $vlicmsOrigem = 0;
          $BASESTR = 0;
          $vlicmsDestino  = 0;
          $vlicmsDiferenca= 0;
          }
          
          
          
          //  $_itemlinha = $row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".$row['NFE_IPI'].";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
          $_itemlinha = $FORNECEDOR.";".$NUMERO_NFE.";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
              fwrite($fp,$_itemlinha."\r\n");
           
                        }
                        fclose($fp);           
     } else { 
        //gera com fornecedor
        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "Fornecedor;Codigo;Descrição;cfop;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
        fwrite($fp,$_itemlinha."\r\n");
        
        $_sql2 = "SELECT NFE_NRO,NFE_FORNEC,NFE_ID,NFE_TOTALNF,NOME
        FROM ".$_SESSION['BASE'].".nota_ent_base    
        LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE   = NFE_FORNEC             
        WHERE  ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero2 
        GROUP BY NFE_NRO,NFE_FORNEC,NFE_ID,NFE_TOTALNF,NOME";
        $statement2 = $pdo->query("$_sql2");
        $retorno2 = $statement2->fetchAll();
        foreach ($retorno2 as $row2) {
            $NUMERO_NFE = $row2['NFE_NRO'];
            $VALORNF = number_format($row2['NFE_TOTALNF'], 2, ',', '.');
            $FORNECEDOR = $row2['NOME'];
            $IDNFE = $row2['NFE_ID'] ;

        

        $_sql = "SELECT nota_ent_item.NFE_NRO,fabricante.NOME,nota_ent_item.NFE_CFOP,NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,fabricante.UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
        FROM ".$_SESSION['BASE'].".nota_ent_base
        INNER JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
        LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
        LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal  
        LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE   = nota_ent_base.NFE_FORNEC  
        LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC            
        WHERE  NFE_IDG = ''  AND  NFE_IDBASE = '$IDNFE' and nota_ent_item.NFE_CFOP <> '6949' and  nota_ent_item.NFE_CFOP <> '6910' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero";
        $statement = $pdo->query("$_sql");
        if($statement->rowCount() > 0){
            $_itemlinha = "$NUMERO_NFE - $FORNECEDOR";
            fwrite($fp,$_itemlinha."\r\n");
            $_itemlinha = "$VALORNF";
            fwrite($fp,$_itemlinha."\r\n");

       
        $retorno = $statement->fetchAll();       
        foreach ($retorno as $row) {            
           // $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi            
           $vlsemipi = $row['NF_CUSTO_ORIG']; // VALOR ipi
           if(  $vlsemipi == 0) {
            $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi
           }
            $vlTotalsemipi = $vlsemipi*$row['NFE_QTDADE'] ;  //35,20
            //$vlicmsOrigem = $row['NF_vICMS'];
            $vlicmsOrigem =  $vlTotalsemipi*$icms/100;
            $picms = $row["NF_pICMS"];
            if( $picms == 0){
                if($row['NFE_CFOP'] == 5102){
                $picms = 18;
                }else{
               // $picms = 12;
                }
            }
           // $picms = $row["NF_pICMS"];
            $mva = $row['ncmmva_mva'];
           if($picms < 18){
          
          
            //  $vlicmsOrigem = $vlTotalsemipi*(12/100); // VALOR DO ICMS NO PR 4,22
              $vlcomipi = ($row['NFE_TOTALITEM']); // VALOR ipi 36,12
          
              $vlmva= ($vlcomipi*($row['ncmmva_mva']/100))  ; // VALOR ICMS ST 13,558
              $BASESTR =$vlcomipi+ $vlmva+$vlicmsOrigem;
              $vlricmsDestino =  $BASESTR*(18/100);   //9,69    
              $vlicmsDiferenca  = $vlricmsDestino-$vlicmsOrigem;
              if($picms <= 4){
                  $vlicmsDiferenca = 0;
                }
          
          }else{
          $vlcomipi = 0;
          $vlrbase  = 0;
          $vlicmsOrigem = 0;
          $BASESTR = 0;
          $vlicmsDestino  = 0;
          $vlicmsDiferenca= 0;
          }
          
          
          
         
         // $_itemlinha = $FORNECEDOR.";".$NUMERO_NFE.";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
           //   fwrite($fp,$_itemlinha."\r\n");
              
      
         $_itemlinha = ";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
         
            fwrite($fp,$_itemlinha."\r\n");
        }
           
                        }
                        $_itemlinha = "";
                        fwrite($fp,$_itemlinha."\r\n");      

     }   
    
     fclose($fp);     
    }  
                        ?>
                    <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarcomBase()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar C/ Substituição Tributária</button>
                        <?php

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


    

    $arquivo = $tiponf."_".$nomearquivo.'.csv';
   
    if( file_exists($arquivo_caminho)){ 
    ?><a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
<?php
    }else{
        echo "Sem registros nesse periodo";
    }

}

else if ($acao == 8) { //gerar SEM Situação tributaria
 
    try {

        $tiponf = "REL_SemSituacaoTributaria" ;
        $filtrarDT = $_parametros['rel-dt'];
        
        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 
        
        
        if($_parametros['_numeronf'] != '') {
            $filnumero = " AND nota_ent_item.NFE_NRO = '".$_parametros['_numeronf']."' OR nota_ent_item.NFE_NRO = '".$_parametros['_numeronf']."'";
        }

    $cliente = $_SESSION['BASE_ID'];

    $mesInicial = explode("-", $_parametros['nf-inicial'] );   

    $Ames= $mesInicial[1];
    $Aano  = $mesInicial[0];
    $nomearquivo = $Ames."_".$Aano;
  
 
    $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
    $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/Rel_semSubstituicaoTributaria_".$nomearquivo.".csv";
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $_sql = "SELECT NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM
        FROM ".$_SESSION['BASE'].".nota_ent_base
        LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
        WHERE NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and ".$filtrarDT." >= '".$_parametros['nf-inicial']." 00:00' AND ".$filtrarDT." <= '".$_parametros['nf-final']." 23:59:59'  $filnumero ";
        $statement = $pdo->query("$_sql");
        $retorno = $statement->fetchAll();

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "Descrição;Qtd;V Unit;V Total;IPI;BC STR;V STR";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
        
          //  $_xml =$row['nfed_xml_protocolado'];
      
        //  $_itemlinha = $row['NFE_DESCRICAO'].";".$row['NFE_QTDADE'].";".number_format($row['NFE_VLRUNI'], 2, ',', '.').";".number_format($row['NFE_TOTALITEM'], 2, ',', '.').";".$row['NFE_IPI'].";".number_format(0, 2, ',', '.').";".number_format($row['0'], 2, ',', '.');
          $_itemlinha = $row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".$row['NFE_IPI'].";".$row['picms'].";".$mva.";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
            fwrite($fp,$_itemlinha."\r\n");
        
           
                        }
                        fclose($fp);           
                  
                        ?>
                    <button type="button"  class="btn btn-white  waves-effect waves-light" aria-expanded="false" onclick="gerarsemBase()" id="_bt000446" ><span class="btn-label btn-label"> <i class="fa    fa-cog"></i></span>Gerar s/ Substituição Tributária</button>
                        <?php

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


    

    $arquivo = $tiponf."_".$nomearquivo.'.csv';
   
    if( file_exists($arquivo_caminho)){ 
    ?><a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
<?php
    }else{
        echo "Sem registros nesse periodo";
    }

  }  else if ($acao == 9) { //relatorio mensal
        //gerar lote mensal
 
    try {

        $modelo = $_parametros['nf-modelo'];
        if($modelo == "" or $modelo == "55") {     
            $modeloDesc = "55-NF-e";      
            $tiponf = "NFe" ;
        }elseif($modelo == "90"){
            $modeloDesc = "NFS-e";
            $tiponf = "NFSe" ;
        
        }else{
         $modeloDesc = "65-NFC-e";
         $tiponf = "NFce" ;
        }
        
        if($_parametros['nf-inicial'] == '') {
            $_parametros['nf-inicial'] = date('Y-m-d');
            $_parametros['nf-final'] = date('Y-m-d');
        } 

        if($_parametros['_empresa'] != '') {
         $filemp = " AND nfed_empresa = '".$_parametros['_empresa']."'";
        }
        
    if($_parametros['_numeronf'] != '') {
        $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
    }

    $cliente = $_SESSION['BASE_ID'];

    $mesInicial = explode("-", $_parametros['nf-inicial'] );   

    $Ames= $mesInicial[1];
    $Aano  = $mesInicial[0];
    $nomearquivo = $Ames."_".$Aano;

    $_datainiT = explode("-", $_parametros['nf-inicial'] );  
    $_datafimT = explode("-", $_parametros['nf-final'] );  

$_datainiT = $_datainiT[2]."/".$_datainiT[1]."/".$_datainiT[0];
$_datafimT = $_datafimT[2]."/".$_datafimT[1]."/".$_datafimT[0];
  
 
    $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        
    if(is_dir($dir))
        {
            //echo "A Pasta Existe";
        }
        else
        {
            //echo "A Pasta não Existe";
            //mkdir(dirname(__FILE__).$dir, 0777, true);
            mkdir($dir."/", 0777, true);
            
        }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        if($modelo == 90 ) {
            $_sql = "SELECT nfed_chave,nfed_numeronf,nfed_serie,DATE_FORMAT(nfed_dataautorizacao,'%d/%m/%Y') as dt,nfed_totalnota,CGC_CPF,Nome_Consumidor 
            FROM ".$_SESSION['BASE'].".NFE_DADOS
            LEFT JOIN  ".$_SESSION['BASE'].".consumidor on CODIGO_CONSUMIDOR  = nfed_cliente
            WHERE  nfed_cancelada = 0 AND nfed_modelo = '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00:00'
            AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59'
            AND nfed_cancelada =  '0' and nfed_protocolo > 0  $fil ";
        
        }else{
            $_sql = "SELECT nfed_xml_protocolado,nfed_chave
            FROM ".$_SESSION['BASE'].".NFE_DADOS    
            WHERE nfed_chave <> '' AND  nfed_cancelada = 0 AND             
            nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil";
        }
     
        $statement = $pdo->query("$_sql");
        $retorno = $statement->fetchAll();

        //NOTA DE SERVICOS=====================================================================================
        if($modelo == 90){   
              //GERAR RELATORIO AUTORIZADOS
        ?>
        <style type="text/css">
            table.bordasimples {border-collapse: collapse;}
            table.bordasimples tr td {border:1px solid #000000; font-size: 12px;    }
            .linha {border-bottom: 1px solid #CCC};
        </style>
        <?php
          
            if($_parametros['_empresa'] != '') {

            $sqlEmp = "Select empresa_nome from " . $_SESSION['BASE'] . ".empresa WHERE empresa_id = '".$_parametros['_empresa']."' ";
            $statement = $pdo->query("$sqlEmp");
            $retornoP = $statement->fetchAll();
        
            foreach ($retornoP as $rstP) {
                $fantasia = $rstP["empresa_nome"];
            }
            }else{
                $_sql = "Select NOME_FANTASIA from ".$_SESSION['BASE'].".parametro";
                $statement = $pdo->query("$_sql");
                $retornoP = $statement->fetchAll();
            
                foreach ($retornoP as $rstP) {
                    $fantasia = $rstP["NOME_FANTASIA"];
                }
    

            }

            ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relatorio Mensal  (<?=$modeloDesc;?>)</td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" ></td>
        </tr>
        <tr>
            <td colspan="2"  >Emitidas</td>
        </tr>
        </table>
        <table   width="100%" border="0"  class="bordasimples">
        <tr>
        <?php
       
       
        echo "<td>Numero da Nota</td>";
        echo "<td>Serie</td>";
     
        echo "<td>Data Emissao</td>";
        echo "<td>Total Nota</td>";
                
        echo "<td>CPF/CNPJ Destinatario</td>";
        echo "<td>Nome Destinatario</td>";
        
        echo "</tr>";
        
     
           
            foreach ($retorno as $row) {
                $Idchave = $row['nfed_chave'];   
                $total    = $row['nfed_totalnota'];            

                //  $total = strval($xml->NFe->infNFe->total->ICMSTot->vNF);
                //  $totalg = $totalg + $total ;
                $contador++; 
                echo "<tr>";
        
                echo "<td>".$row['nfed_numeronf'].'</td>';
                echo '<td style="text-align: center;">'.$row['nfed_serie'].'</td>';
              
                echo "<td>".$row['dt'].'</td>';
                echo '<td style="text-align: center;">'.number_format($total,2,',','.').'</td>';
              
            
                echo "<td>".$row['CGC_CPF'].'</td>';
                echo "<td>".$row['Nome_Consumidor'].'</td>';
                
                        echo "</tr>";
                        $totalg= $totalg+$total;
                    
                    }
                    
      
                        echo "<tr>";
                        echo "<td>Reg:$contador</td>";
                      
                        echo "<td></td>";
                    echo "<td>Vl.Total</td>";
                    echo '<td style="text-align: center;">'.number_format($totalg,2,',','.')."</td>";
                 
                    echo "<td></td>";
                    echo "<td></td>";
                  
                 
                    echo "</table>";

                    ?>
                    <table   width="100%" border="0">      
                        <tr>
                            <td colspan="2" class="linha"  ></td>
                        </tr>
                        <tr>
                            <td colspan="2"  >Canceladas</td>
                        </tr>
                        </table>
                
                        <table   width="100%" border="0"  class="bordasimples">
                            <?php
                    $_sql = "SELECT nfed_numeronf,nfed_chave,nfed_serie,DATE_FORMAT(nfed_dataautorizacao,'%d/%m/%Y') AS dt
                    FROM ".$_SESSION['BASE'].".NFE_DADOS   
                    WHERE nfed_protocolo <> '' AND  nfed_cancelada = 1 AND             
                    nfed_modelo = '90' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00:00' 
                    AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil";
                    $statement = $pdo->query("$_sql");
                    $retorno = $statement->fetchAll();
                
                    echo "<tr>";
                     echo "<td>Numero da Nota</td>";   
                     echo "<td>Série</td>"; 
                     echo "<td>Data Emissao</td>";
                
                    echo "</tr>";
                
                    foreach ($retorno as $row) {
                        $numeronf = $row['nfed_numeronf'];
                        $DTnf = $row['dt'];
                        $Idchave = $row['nfed_chave'];
                        echo "<tr>";
                        echo "<td>$numeronf</td>";
                        echo '<td style="text-align: center;">'. $row['nfed_serie'].'</td>';
                         
                        echo "<td> $DTnf </td>";
                        
                        echo "</tr>";
                    }
            exit();
        }


        //FIM NOTAS DE SERVIÇOS-+-------------------------------------------------------------------------------------------------------
    

        foreach ($retorno as $row) {
            $Idchave = $row['nfed_chave'];
            $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/NFe".$Idchave.".xml";
            $_xml =$row['nfed_xml_protocolado'];      

            $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
            fwrite($fp,trim($_xml));
            fclose($fp);
            
          }
        //GERAR RELATORIO AUTORIZADOS
        ?>
        <style type="text/css">
            table.bordasimples {border-collapse: collapse;}
            table.bordasimples tr td {border:1px solid #000000; font-size: 12px;    }
            .linha {border-bottom: 1px solid #CCC};
        </style>
        <?php
          
            if($_parametros['_empresa'] != '') {

            $sqlEmp = "Select empresa_nome from " . $_SESSION['BASE'] . ".empresa WHERE empresa_id = '".$_parametros['_empresa']."' ";
            $statement = $pdo->query("$sqlEmp");
            $retorno = $statement->fetchAll();
        
            foreach ($retorno as $rst) {
                $fantasia = $rst["empresa_nome"];
            }
            }else{
                $_sql = "Select NOME_FANTASIA from ".$_SESSION['BASE'].".parametro";
                $statement = $pdo->query("$_sql");
                $retorno = $statement->fetchAll();
            
                foreach ($retorno as $rst) {
                    $fantasia = $rst["NOME_FANTASIA"];
                }
    

            }
            ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relatorio Mensal  (<?=$modeloDesc;?>)</td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" ></td>
        </tr>
        <tr>
            <td colspan="2"  >Emitidas</td>
        </tr>
        </table>
        <table   width="100%" border="0"  class="bordasimples">
        <tr>
        <?php
       
       
        echo "<td>Numero da Nota</td>";
        echo "<td>Serie</td>";
        echo "<td>Mod</td>";
        echo "<td>Natureza Operacao</td>";
        echo "<td>Data Emissao</td>";
        echo "<td>Total Nota</td>";
        echo "<td>Total Produtos</td>";
        echo "<td>Total Frete</td>";
        echo "<td>CFOP de produtos</td>";
        echo "<td>Chave</td>";
        echo "<td>Protocolo</td>";
        echo "<td>Cnpj Emitente</td>";
       
        echo "<td>CPF/CNPJ Destinatario</td>";
        
        echo "</tr>";
        {
     
        foreach(glob('arquivos/'.$_SESSION['CODIGOCLI'].'/*.xml') as $xmlFile){
            $ok = "";
            
            $ok =  strripos($xmlFile,"sta");
             
            if($ok == ""){ 
            $xml = simplexml_load_file($xmlFile);
         
      if($xml->NFe->infNFe->ide->nNF != "") {

     
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('nfe', $namespaces['']);

                
                $detElements = $xml->xpath('//nfe:det');
                    $cfops = "";
                    $CFOP= "";
                        
                    foreach ($detElements as $det) {
                        $prod = $det->prod;
                        $cProd = (string) $prod->cProd;
                        $xProd = (string) $prod->xProd;
                        $vProd = (string) $prod->vProd;
                        $cfop = (string) $prod->CFOP;
                        $cfops = $cfops.$cfop."/";
                    
                    }

                    // Remover a barra inicial e final, se existirem
                    $cfops = trim($cfops, '/');

                    // Transformar a string em um array
                    $array = explode('/', $cfops);

                    // Contar as ocorrências de cada valor
                    $countValues = array_count_values($array);

                    // Filtrar e agrupar os valores "6949" e "5102"
                    //$groupedValues = array_intersect_key($countValues, array_flip(['6949', '5102']));

                    // Exibir os resultados
                    foreach ($countValues as $value => $count) {
                        //if($value != $CFOPANT) { 
                            $CFOP = $CFOP.$value."/";
                    //  }
                    //  $CFOPANT = $value;
                    }
                    $CFOP = rtrim($CFOP, '/');
                }
                        $total = strval($xml->NFe->infNFe->total->ICMSTot->vNF);
                        $totalg = $totalg + $total ;
                    $totalproduto =  strval($xml->NFe->infNFe->total->ICMSTot->vProd); 
                    $totalfrete  = strval($xml->NFe->infNFe->total->ICMSTot->vFrete);

                        $totalprodutog = $totalprodutog + strval($xml->NFe->infNFe->total->ICMSTot->vProd); 
                        $totalfreteg = $totalfreteg + strval($xml->NFe->infNFe->total->ICMSTot->vFrete);

            $dtemissao = explode("-",substr($xml->NFe->infNFe->ide->dhEmi,0,10));
            $dtemissao  = $dtemissao[2]."/".$dtemissao[1]."/".$dtemissao[0] ;
          //  $total = strval($xml->NFe->infNFe->total->ICMSTot->vNF);
          //  $totalg = $totalg + $total ;
            $contador++; 
            echo "<tr>";
    
            echo "<td>".$xml->NFe->infNFe->ide->nNF.'</td>';
            echo '<td style="text-align: center;">'.$xml->NFe->infNFe->ide->serie.'</td>';
            echo '<td style="text-align: center;">'.$xml->NFe->infNFe->ide->mod.'</td>';
            echo '<td style="text-align: left;">'.$xml->NFe->infNFe->ide->natOp.'</td>';
            echo "<td>".$dtemissao.'</td>';
            echo '<td style="text-align: center;">'.number_format($total,2,',','.').'</td>';
            echo '<td style="text-align: center;">'.number_format($totalproduto,2,',','.').'</td>';
		    echo '<td style="text-align: center;">'.number_format($totalfrete,2,',','.').'</td>';
		    echo '<td style="text-align: center;">'.$CFOP.'</td>';
            echo "<td>".$xml->protNFe->infProt->chNFe.'</td>';
            echo "<td>".$xml->protNFe->infProt->nProt.'</td>';
            echo "<td>".$xml->NFe->infNFe->emit->CNPJ.'</td>';
         
            echo "<td>".$xml->NFe->infNFe->dest->CPF.'</td>';
            
                    echo "</tr>";
        }
        }
        echo "<tr>";
         echo "<td>Reg:$contador</td>";
         echo "<td></td>";
          echo "<td></td>";echo "<td></td>";
        echo "<td>Vl.Total</td>";
        echo '<td style="text-align: center;">'.number_format($totalg,2,',','.')."</td>";
        echo '<td style="text-align: center;">'.number_format($totalprodutog,2,',','.')."</td>";
	    echo '<td style="text-align: center;">'.number_format($totalfreteg,2,',','.')."</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";
        echo "</table>";
        }

        ?>
    <table   width="100%" border="0">      
        <tr>
            <td colspan="2" class="linha"  ></td>
        </tr>
        <tr>
            <td colspan="2"  >Canceladas</td>
        </tr>
        </table>

        <table   width="100%" border="0"  class="bordasimples">
            <?php
    $_sql = "SELECT nfed_numeronf,nfed_chave,nfed_serie,DATE_FORMAT(nfed_dataautorizacao,'%d/%m/%Y') AS dt
    FROM ".$_SESSION['BASE'].".NFE_DADOS   
    WHERE nfed_chave <> '' AND  nfed_cancelada = 1 AND             
    nfed_modelo = '$modelo' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil";
    $statement = $pdo->query("$_sql");
    $retorno = $statement->fetchAll();

    echo "<tr>";
     echo "<td>Numero da Nota</td>";   
     echo "<td>Série</td>"; 
     echo "<td>Data Emissao</td>";
    echo "<td>Chave</td>";
    echo "</tr>";

    foreach ($retorno as $row) {
        $numeronf = $row['nfed_numeronf'];
        $DTnf = $row['dt'];
        $Idchave = $row['nfed_chave'];
        echo "<tr>";
        echo "<td>$numeronf</td>";
        echo '<td style="text-align: center;">'. $row['nfed_serie'].'</td>';
         
        echo "<td> $DTnf </td>";
        echo "<td>$Idchave</td>";
        echo "</tr>";
    }
          echo "</table>";
       

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


      //  echo "Sem registros nesse periodo";
  
  }  else if ($acao == 10) { //relatorio sintetico
    //gerar lote mensal

try {

    $modelo = $_parametros['nf-modelo'];
    
    
    if($_parametros['nf-inicial'] == '') {
        $_parametros['nf-inicial'] = date('Y-m-d');
        $_parametros['nf-final'] = date('Y-m-d');
    } 

    if($_parametros['_empresa'] != '') {
     $filemp = " AND nfed_empresa = '".$_parametros['_empresa']."'";
    }
    
if($_parametros['_numeronf'] != '') {
    $fil = " AND nfed_numeronf = '".$_parametros['_numeronf']."' OR nfed_numeronf = '".$_parametros['_numeronf']."'";
}

$cliente = $_SESSION['BASE_ID'];

$mesInicial = explode("-", $_parametros['nf-inicial'] );   

$Ames= $mesInicial[1];
$Aano  = $mesInicial[0];
$nomearquivo = $Ames."_".$Aano;

$_datainiT = explode("-", $_parametros['nf-inicial'] );  
$_datafimT = explode("-", $_parametros['nf-final'] );  

$_datainiT = $_datainiT[2]."/".$_datainiT[1]."/".$_datainiT[0];
$_datafimT = $_datafimT[2]."/".$_datafimT[1]."/".$_datafimT[0];


$dir = "arquivos/".$_SESSION['CODIGOCLI'];

    
if(is_dir($dir))
    {
        //echo "A Pasta Existe";
    }
    else
    {
        //echo "A Pasta não Existe";
        //mkdir(dirname(__FILE__).$dir, 0777, true);
        mkdir($dir."/", 0777, true);
        
    }

            
    $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

    foreach ( $ri as $file ) {
    $file->isDir() ?  rmdir($file) : unlink($file);
    }

 
     


    //FIM NOTAS DE SERVIÇOS-+-------------------------------------------------------------------------------------------------------



    ?>
    <style type="text/css">
        table.bordasimples {border-collapse: collapse;}
        table.bordasimples tr td {border:1px solid #000000; font-size: 12px;    }
        .linha {border-bottom: 1px solid #CCC};
    </style>
    <?php
      
        if($_parametros['_empresa'] != '') {

        $sqlEmp = "Select empresa_nome from " . $_SESSION['BASE'] . ".empresa WHERE empresa_id = '".$_parametros['_empresa']."' ";
        $statement = $pdo->query("$sqlEmp");
        $retorno = $statement->fetchAll();
    
        foreach ($retorno as $rst) {
            $fantasia = $rst["empresa_nome"];
        }
        }else{
            $_sql = "Select NOME_FANTASIA from ".$_SESSION['BASE'].".parametro";
            $statement = $pdo->query("$_sql");
            $retorno = $statement->fetchAll();
        
            foreach ($retorno as $rst) {
                $fantasia = $rst["NOME_FANTASIA"];
            }


        }
        ?>
    <table   width="100%" border="0">
    <tr>
        <td width="374" class="style34" ><strong><span class="style31" >
        <?=$fantasia;?></strong>
        </span> -  Resumo de NFe e NFCe por CFOP (<?=$modeloDesc;?>)</td>
        <td width="172" class="style34" >Data:<span class="titulo">
        <?=$data_atual ;?>
        </span></td>
    </tr>
    <tr>
        <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="linha" ></td>
    </tr>
    <tr>
        <td colspan="2"  >Emitidas</td>
    </tr>
    </table>
    <table   width="100%" border="0"  class="bordasimples">
    
    <tr>
    <?php
   
   
    echo "<td>CFOP</td>";
    echo "<td>Natureza Operação</td>";
    echo "<td>Mod</td>";
    echo "<td>R$ Total NF</td>";
    echo "<td>Base ICMS</td>";
    echo "<td>Valor ICMS</td>";
    echo "<td>R$ IPI/Outras Despesas</td>";
   
    
    echo "</tr>";
    $_sql = "SELECT sum(nfed_totalnota) as total  FROM ".$_SESSION['BASE'].".NFE_DADOS  
    WHERE nfed_chave <> '' AND  nfed_cancelada = 0 AND             
    nfed_modelo <> '90' and nfed_modelo <> '55' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil    ";


    $statement = $pdo->query("$_sql");
    $retornonf = $statement->fetchAll();

   
    foreach ($retornonf as $row) {
        $MODELO = '65';
        $CFOP = '5102';
        $DESC_CFOP = 'VENDA CONSUMIDOR';
        $TOTALNF = $row['total'];
        $TOTALBASE = 0;
        $TOTALVLRBASE = 0;
        $VALORIPI = 0;

        $TOTALNF_T = $TOTALNF_T + $TOTALNF;
        $TOTALBASE_T = $TOTALBASE_T + $TOTALBASE;
        $TOTALVLRBASE_T = $TOTALVLRBASE_T + $TOTALVLRBASE;
        $VALORIPI_T = $VALORIPI_T + $VALORIPI;
  
        
        $contador++; 
        echo "<tr>";

        echo "<td>". $CFOP.'</td>';
        echo '<td style="text-align: center;">'.$DESC_CFOP.'</td>';
        echo '<td style="text-align: center;">'. $MODELO.'</td>';        
        echo '<td style="text-align: center;">'.number_format( $TOTALNF,2,',','.').'</td>';
        echo '<td style="text-align: center;">'.number_format( $TOTALBASE ,2,',','.').'</td>';
        echo '<td style="text-align: center;">'.number_format($TOTALVLRBASE,2,',','.').'</td>';
        echo '<td style="text-align: center;">'.number_format($VALORIPI,2,',','.').'</td>';
        
                echo "</tr>";
    }


    $_sql = "SELECT nfed_modelo,cfop_nfeitens,NAT_DESCRICAO,SUM(vlrtotal_nfeitens+nfe_itensvlrOutros) AS vlrtotal_nfeitens,SUM(vBC_nfeitens) AS vBC_nfeitens,
    SUM(vICMS_nfeitens) AS vICMS_nfeitens, SUM(nfe_itensvlrOutros) AS nfe_itensvlrOutros  FROM ".$_SESSION['BASE'].".NFE_DADOS    
        INNER JOIN  ".$_SESSION['BASE'].".NFE_ITENS ON nfed_id = id_nfedados
        LEFT JOIN ".$_SESSION['BASE'].".cfop ON nfed_cfopid  = ID
        WHERE nfed_chave <> '' AND  nfed_cancelada = 0 AND             
        nfed_modelo = '55' and nfed_dataautorizacao >= '".$_parametros['nf-inicial']." 00:00' AND nfed_dataautorizacao <= '".$_parametros['nf-final']." 23:59:59' $filemp $fil
        GROUP BY  nfed_modelo,cfop_nfeitens";

 
    $statement = $pdo->query("$_sql");
    $retornonf = $statement->fetchAll();

   
    foreach ($retornonf as $row) {
        $MODELO = $row['nfed_modelo'];
        $CFOP = $row['cfop_nfeitens'];
        $DESC_CFOP = $row['NAT_DESCRICAO'];
        $TOTALNF = $row['vlrtotal_nfeitens'];
        $TOTALBASE = $row['vBC_nfeitens'];
        $TOTALVLRBASE = $row['vICMS_nfeitens'];
        $VALORIPI = $row['nfe_itensvlrOutros'];

        $TOTALNF_T = $TOTALNF_T + $TOTALNF;
        $TOTALBASE_T = $TOTALBASE_T + $TOTALBASE;
        $TOTALVLRBASE_T = $TOTALVLRBASE_T + $TOTALVLRBASE;
        $VALORIPI_T = $VALORIPI_T + $VALORIPI;
  
        
        $contador++; 
        echo "<tr>";

        echo "<td>". $CFOP.'</td>';
        echo '<td style="text-align: center;">'.$DESC_CFOP.'</td>';
        echo '<td style="text-align: center;">'. $MODELO.'</td>';
        
        echo '<td style="text-align: center;">'.number_format( $TOTALNF,2,',','.').'</td>';
        echo '<td style="text-align: center;">'.number_format( $TOTALVLRBASE ,2,',','.').'</td>';
        echo '<td style="text-align: center;">'.number_format($TOTALVLRBASE,2,',','.').'</td>';
        echo '<td style="text-align: center;">'.number_format($VALORIPI,2,',','.').'</td>';
        
                echo "</tr>";
    }
   
    echo "<tr>";

    echo "<td></td>";
    echo '<td style="text-align: center;"></td>';
    echo '<td style="text-align: center;"></td>';
    
    echo '<td style="text-align: center;">'.number_format( $TOTALNF_T,2,',','.').'</td>';
    echo '<td style="text-align: center;">'.number_format( $TOTALVLRBASE_T ,2,',','.').'</td>';
    echo '<td style="text-align: center;">'.number_format($TOTALVLRBASE_T,2,',','.').'</td>';
    echo '<td style="text-align: center;">'.number_format($VALORIPI_T,2,',','.').'</td>';
    
            echo "</tr>";
    echo "</table>";
   


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


  //  echo "Sem registros nesse periodo";

}