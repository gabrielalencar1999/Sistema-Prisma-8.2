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
 * Incluir Produto
 * */
if ($acao["acao"] == 1) {
    if (empty($_parametros["valida-etq"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe um produto válido!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else if (empty($_parametros["qnt-etq"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a quantidade do produto!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            
            $ender = !empty($_parametros["endereco-etq"]) ? $_parametros["endereco-etq"] : '';
            $modApl = !empty($_parametros["Marca-etq"]) ? $_parametros["Marca-etq"] : '';

            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".tabetiq_peca (CODIGO_FORNECEDOR, DESCRICAO, Qtde, Valor_Venda,ender,Mod_Apl,codref_fabricante) VALUES(?, ?, ?, ?,?,?,?)");
            $statement->bindParam(1, $_parametros["cod-forn"]);
            $statement->bindParam(2, $_parametros["produto-etq"]);
            $statement->bindParam(3, $_parametros["qnt-etq"]);
            $statement->bindParam(4, $_parametros["valor-etq"]);
            $statement->bindParam(5, $ender);
            $statement->bindParam(6, $modApl);
            $statement->bindParam(7, $_parametros["codbarra-etq"]);
            
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Produto incluído!</h2>
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
                    <div class="modal-body" id="imagem-carregando">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/**
 * Lista Produtos Etiqueta
 */
else if ($acao["acao"] == 2) {
    $consulta= $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".tabetiq_peca");
    $retorno = $consulta->fetchAll();
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr> 
            <th class="text-center">Código</th>
            <th class="text-center">Descrição</th>
            <th class="text-center">Endereço</th>
             <th class="text-center">Marca/Fabricante</th>
            <th class="text-center">Valor de Venda</th>
            <th class="text-center">Quantidade</th>
            <th class='text-center'>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td class="text-center"><?=$row["codref_fabricante"]?></td>
                <td class="text-center"><?=$row["DESCRICAO"];?></td>
                <td class="text-center"><?=$row["ender"]?></td>
                <td class="text-center"><?=$row["Mod_Apl"]?></td>
                <td class="text-center"><?=number_format($row["Valor_Venda"], '2', ',', '.')?></td>
                <td class="text-center"><?=$row["Qtde"]?></td>
                <td class="actions text-center">
                    <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row['ID']?>)"><i class="fa fa-trash-o"></i></a>
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
 * Atualiza Movimento
 * */
else if ($acao["acao"] == 3) {
    /**
     * Valida impressora preenchida
     */
    if (empty($_parametros["impressora-etq"])) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                            <h2>Informe a Impressora!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    /**
     * Lança etiqueta
     */
    else {
        try {
            $consulta = $pdo->query("SELECT id, NOME_FANTASIA FROM ".$_SESSION['BASE'].".parametro");
            $retorno = $consulta->fetch();

            $cliente = $retorno["id"];
            $fantasia = $retorno["NOME_FANTASIA"];

            $consulta = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".tabetiq_peca ORDER BY DESCRICAO");
            $retorno = $consulta->fetchAll();

            if (is_dir("./docs/$cliente/download")) {
                if (file_exists("./docs/$cliente/download/ETIQUETA.TXT")) {
                    $arquivo = fopen("./docs/$cliente/download/ETIQUETA.TXT", "w");
                }
                else {
                    unlink("./docs/$cliente/downloadETIQUETA.TXT");
                    $arquivo = fopen("./docs/$cliente/download/ETIQUETA.TXT", "w");
                }
            }
            else {
                mkdir("./docs/$cliente/download", 0760, true);
                if (file_exists("./docs/$cliente/download/ETIQUETA.TXT")) {
                    $arquivo = fopen("./docs/$cliente/download/ETIQUETA.TXT", "w");
                }
                else {
                    unlink("./docs/$cliente/download/ETIQUETA.TXT");
                    $arquivo = fopen("./docs/$cliente/download/ETIQUETA.TXT", "w");
                }
            }

             if ($_parametros['impressora-etq'] == 1) {

             }elseif ($_parametros['impressora-etq'] == 2) {

             }elseif ($_parametros['impressora-etq'] == 3) { // Bematech e Zebra  liguagem 

                $offset = 0;
                    foreach ($retorno as $row) {
                     //   $desc = $row['DESCRICAO'];
                     //   $codigo= $row['CODIGO_FABRICANTE'];
                      //  $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        // exemplo de dados variáveis
                        $empresa = $fantasia;//"HELEMBRAS - Assistência Técnica";
                        $codigo1 = $row['CODIGO_FABRICANTE'];//"41002757 64800637";                     
                        $descricao = $row['DESCRICAO'];//"PLACA POTENCIA 183140 D150174";
                        $codigo2 =  trim($row['CODIGO_FORNECEDOR']);//"64800637";
                        $marca = trim($row['Mod_Apl']);//"ELECTROLUX";
                        $ender = trim($row['ender']);//"X5";
                        $num =  trim($row['CODIGO_FORNECEDOR']);//"216";
                    // gera o número de etiquetas de acordo com a quantidade
                        for ($i = 1; $i <= $qtde; $i += 2) {
                
                            // inicia nova linha de etiqueta (duas colunas)
                          //  $detalhe .= "N\n";
                          //  $detalhe .= "q890\n";      // 2 colunas = 480 dots (~60mm)
                            //$detalhe .= "Q400,24\n";   // altura 50mm (~400 dots)

                           // etiqueta 1 (coluna esquerda)
                            $detalhe .= "N\n"; // limpa buffer
                            $detalhe .= "A20,10,0,3,1,1,N,\"$empresa\"\n";
                            $detalhe .= "A20,35,0,4,1,1,N,\"$codigo1\"\n";
                            $detalhe .= "A20,60,0,4,1,1,N,\"$descricao\"\n";
                            $detalhe .= "A20,85,0,4,1,1,N,\"$codigo1\"\n";
                            $detalhe .= "A20,110,0,3,1,1,N,\"$marca\"\n";
                            $detalhe .= "A200,110,0,3,1,1,N,\"$ender\"\n";
                            $detalhe .= "B20,140,0,1,2,2,60,B,\"$codigo2\"\n";
                            $detalhe .= "A20,210,0,3,1,1,N,\"$num\"\n";
                            // borda (x1, y1, largura, altura, espessura)
                            $detalhe .= "X10,5,480,240,2\n";

                            // === segunda coluna ===
                            // só imprime se ainda houver mais uma etiqueta a imprimir
                            if ($i + 1 <= $qtde) {
                               // etiqueta 2 (coluna direita - deslocada 500 dots ≈ 50mm)
                                $offset = 500;
                                $detalhe .= "A" . ($offset + 20) . ",10,0,3,1,1,N,\"$empresa\"\n";
                                $detalhe .= "A" . ($offset + 20) . ",35,0,4,1,1,N,\"$codigo1\"\n";
                                $detalhe .= "A" . ($offset + 20) . ",60,0,4,1,1,N,\"$descricao\"\n";
                                $detalhe .= "A" . ($offset + 20) . ",85,0,4,1,1,N,\"$codigo1\"\n";
                                $detalhe .= "A" . ($offset + 20) . ",110,0,3,1,1,N,\"$marca\"\n";
                                $detalhe .= "A" . ($offset + 200) . ",110,0,3,1,1,N,\"$ender\"\n";
                                $detalhe .= "B" . ($offset + 20) . ",140,0,1,2,2,60,B,\"$codigo2\"\n";
                                $detalhe .= "A" . ($offset + 20) . ",210,0,3,1,1,N,\"$num\"\n";
                                $detalhe .= "X" . ($offset + 10) . ",5,480,240,2\n";
                             }

                            $detalhe .= "P1\n"; // imprime a linha (1 ou 2 etiquetas)
                        }
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);

             }
                

            /**
             * Verifique impressora
             */
            /*
            if ($_parametros['impressora-etq'] == 1) {
            
                if ($_parametros['tipo-etq-elgin'] == 1) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 2 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A130,20,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A130,90,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B130,125,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A150,190,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A130,233,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A130,270,0,4,1,1,N,".'"'.$vlr.'"'."\n";
                            $detalhe = $detalhe."A130,370,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B130,400,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A130,465,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A160,500,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A160,530,0,4,1,1,N,".'"'.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A460,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A460,90,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B470,125,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A470,190,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A480,233,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A460,270,0,4,1,1,N,".'"'.$vlr.'"'."\n";
                            $detalhe = $detalhe."A460,370,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B470,400,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A470,465,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A480,500,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A460,530,0,4,1,1,N,".'"'.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++;
                    }

                    if(substr($detalhe, -3) == "P1") { 
                        $detalhe = $detalhe."P1\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);                        
                }
                else if ($_parametros['tipo-etq-elgin'] == 2) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A090,15,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A020,90,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,125,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A80,190,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A020,233,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A020,310,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B020,330,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A20,370,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A120,370,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A360,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A306,90,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B346,125,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A366,190,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A306,233,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A306,310,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B306,330,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A306,370,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A430,370,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A630,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A580,90,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B620,125,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A640,190,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A580,233,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."A580,310,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B580,330,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A580,370,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A670,370,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 3) {
                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        $detalhe = $detalhe."N\n";
                        $detalhe = $detalhe."q890\n";
                        $detalhe = $detalhe."A220,19,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                        $detalhe = $detalhe."A150,38,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                        $detalhe = $detalhe."B490,20,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                        $detalhe = $detalhe."A500,60,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                        $detalhe = $detalhe."A150,59,0,4,1,1,N,".'"'.$vlr.'"'."\n";
                        $detalhe = $detalhe."P1\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 4) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A090,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A030,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A030,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A120,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A360,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A300,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B360,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A300,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A380,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A630,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A600,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B620,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A600,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A690,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }
                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 5) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A020,10,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,32,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A80,92,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A060,115,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A306,10,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B350,32,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A360,92,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A350,115,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A590,10,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B620,32,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A640,92,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A620,115,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 6) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A090,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A030,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A030,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A120,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A360,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A300,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B360,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A300,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A380,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A630,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A570,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B620,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A570,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A650,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 7) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 2 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A090,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A030,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A030,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A120,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A490,10,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                            $detalhe = $detalhe."A450,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B460,50,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A460,85,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A610,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 8) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A020,40,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,70,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A80,135,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A060,155,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A306,40,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B350,70,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A360,135,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A350,155,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A590,40,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B620,70,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A640,135,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A620,155,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 9) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A130,10,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B150,30,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A150,62,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A150,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A380,10,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B410,30,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A410,62,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A410,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A620,10,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B640,30,0,1,2,2,30,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A640,62,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A640,85,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 10) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');
                        $promocao = $row['promocao'];

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."N\n";
                            $detalhe = $detalhe."q890\n";
                            $detalhe = $detalhe."A020,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B060,60,0,1,2,2,50,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A65,115,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A060,138,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."A306,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B350,60,0,1,2,2,50,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A350,115,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A350,138,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                        }
                        else {
                            $detalhe = $detalhe."A590,30,0,2,1,1,N,".'"'.$descricao.'"'."\n";
                            $detalhe = $detalhe."B620,60,0,1,2,2,50,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A620,115,0,2,1,1,N,".'"'.$codigo.'"'."\n";
                            $detalhe = $detalhe."A620,138,0,4,1,1,N,".'"R$ '.$vlr.'"'."\n";
                            $detalhe = $detalhe."P1\n";
                        }
                        $contador++; 
                    }

                    if(substr($detalhe, -3) != "P1\n") { 
                        $detalhe = $detalhe."P1\n";
                    }

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-elgin'] == 11) {
                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        $detalhe = $detalhe."N\n";
                        $detalhe = $detalhe."q890\n";
                        $detalhe = $detalhe."A110,15,0,2,1,1,N,".'"'.$fantasia.'"'."\n";
                        $detalhe = $detalhe."A090,50,0,2,2,2,N,".'"'.$descricao.'"'."\n";
                        $detalhe = $detalhe."B140,90,0,1,2,2,60,N,".'"'.$codigo.'"'."\n";
                        $detalhe = $detalhe."A160,170,0,2,1,2,N,".'"'.$codigo.'"'."\n";
                        $detalhe = $detalhe."A480,90,0,4,2,4,N,".'"R$ '.$vlr.'"'."\n";
                        $detalhe = $detalhe."P1\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
            }
            else if ($_parametros['impressora-etq'] == 2) {
                if ($_parametros['tipo-etq-argox'] == 1) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 2 == 1) {
                            $detalhe = $detalhe."L\n";
                            $detalhe = $detalhe."m\n";
                            $detalhe = $detalhe."e\n";
                            $detalhe = $detalhe."PC\n";
                            $detalhe = $detalhe."D11\n";
                            $detalhe = $detalhe."H14\n";
                            $detalhe = $detalhe."z\n";
                            $detalhe = $detalhe."121100002400080".$descricao."\n";
                            $detalhe = $detalhe."131100001900100R$ ".$vlr." \n";
                            $detalhe = $detalhe."1C0005000500080".$codigo."\n";
                        }
                        else {
                            $detalhe = $detalhe."121100002400580".$descricao."\n";
                            $detalhe = $detalhe."131100001500600R$ ".$vlr."\n";
                            $detalhe = $detalhe."1C0005000500600".$codigo."\n";
                            $detalhe = $detalhe."Q0001\n";
                            $detalhe = $detalhe."E\n"; 
                        }
                        $contador++;
                    }

                    if (substr($detalhe, -2) != "E\n") {
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-argox'] == 2) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 2 == 1) {
                            $detalhe = $detalhe."L\n";
                            $detalhe = $detalhe."m\n";
                            $detalhe = $detalhe."e\n";
                            $detalhe = $detalhe."PC\n";
                            $detalhe = $detalhe."D11\n";
                            $detalhe = $detalhe."H14\n";
                            $detalhe = $detalhe."z\n";
                            $detalhe = $detalhe."121100002400080".$descricao."\n";
                            $detalhe = $detalhe."131100001900100R$ ".$vlr." \n";
                            $detalhe = $detalhe."1C0005000500080".$codigo."\n";
                        }
                        else {
                            $detalhe = $detalhe."121100002400580".$descricao."\n";
                            $detalhe = $detalhe."131100001500600R$ ".$vlr."\n";
                            $detalhe = $detalhe."1C0005000500600".$codigo."\n";
                            $detalhe = $detalhe."Q0001\n";
                            $detalhe = $detalhe."E\n"; 
                        }
                        $contador++;
                    }

                    if (substr($detalhe, -2) != "E\n") {
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-argox'] == 3) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        $detalhe = $detalhe."L\n";
                        $detalhe = $detalhe."m\n";
                        $detalhe = $detalhe."e\n";
                        $detalhe = $detalhe."PC\n";
                        $detalhe = $detalhe."D11\n";
                        $detalhe = $detalhe."H14\n";
                        $detalhe = $detalhe."z\n";
                        $detalhe = $detalhe."121100001000030".$descricao."\n";  
                        $detalhe = $detalhe."121100000600180R$".$vlr."\n";   
                        $detalhe = $detalhe."1D0005000120030".$codigo."\n"; 
                        $detalhe = $detalhe."121100001000360".$descricao."\n";  
                        $detalhe = $detalhe."121100000600520R$".$vlr."\n";   
                        $detalhe = $detalhe."1D0005000120360".$codigo."\n"; 
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";   
                    }

                    $detalhe = $detalhe."L\n";
                    $detalhe = $detalhe."m\n";
                    $detalhe = $detalhe."e\n";
                    $detalhe = $detalhe."PC\n";
                    $detalhe = $detalhe."D11\n";
                    $detalhe = $detalhe."H14\n";
                    $detalhe = $detalhe."z\n";
                    $detalhe = $detalhe."121100001000030"."   "."\n";
                    $detalhe = $detalhe."Q0001\n";
                    $detalhe = $detalhe."E\n";

                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-argox'] == 4) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."L\n";
                            $detalhe = $detalhe."m\n";
                            $detalhe = $detalhe."e\n";
                            $detalhe = $detalhe."PC\n";
                            $detalhe = $detalhe."D11\n";
                            $detalhe = $detalhe."H14\n";
                            $detalhe = $detalhe."z\n";
                            $detalhe = $detalhe."121100001700030".$descricao."\n";
                            $detalhe = $detalhe."131100001200030".$vlr."\n";
                            $detalhe = $detalhe."1D0005000500045".$codigo."\n";
                            $detalhe = $detalhe."111100000050050".$fantasia."\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."121100001700410".$descricao."\n";
                            $detalhe = $detalhe."131100001200410".$vlr."\n";
                            $detalhe = $detalhe."1D0005000500430".$codigo."\n";
                            $detalhe = $detalhe."111100000050420".$fantasia."\n";
                        }
                        else {
                            $detalhe = $detalhe."121100001700750".$descricao."\n";
                            $detalhe = $detalhe."131100001200750".$vlr."\n";
                            $detalhe = $detalhe."1D0005000500780".$codigo."\n";
                            $detalhe = $detalhe."111100000050790".$fantasia."\n";
                            $detalhe = $detalhe."Q0001\n";
                            $detalhe = $detalhe."E\n";
                        }
                        $contador++;
                    }

                    if (substr($detalhe, -2) != "E\n") {
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-argox'] == 5) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 2 == 1) {
                            $detalhe = $detalhe."L\n";
                            $detalhe = $detalhe."m\n";
                            $detalhe = $detalhe."e\n";
                            $detalhe = $detalhe."PC\n";
                            $detalhe = $detalhe."D11\n";
                            $detalhe = $detalhe."H14\n";
                            $detalhe = $detalhe."z\n";
                            $detalhe = $detalhe."121100002400080".$descricao."\n";
                            $detalhe = $detalhe."131100001900100R$ ".$vlr." \n";
                            $detalhe = $detalhe."1C0005000500080".$codigo."\n";
                            $detalhe = $detalhe."121100000150110".$fantasia."\n";
                        }
                        else {
                            $detalhe = $detalhe."121100002400540".$descricao."\n";
                            $detalhe = $detalhe."131100001900580R$ ".$vlr." \n";
                            $detalhe = $detalhe."1C0005000500600".$codigo."\n";
                            $detalhe = $detalhe."121100000150630".$fantasia."\n";
                            $detalhe = $detalhe."Q0001\n";
                            $detalhe = $detalhe."E\n";
                        }
                        $contador++;
                    }

                    if (substr($detalhe, -2) != "E\n") {
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-argox'] == 6) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 3 == 1) {
                            $detalhe = $detalhe."L\n";
                            $detalhe = $detalhe."m\n";
                            $detalhe = $detalhe."e\n";
                            $detalhe = $detalhe."PC\n";
                            $detalhe = $detalhe."D11\n";
                            $detalhe = $detalhe."H14\n";
                            $detalhe = $detalhe."z\n";
                            $detalhe = $detalhe."321100001701010$descricao\n";
                            $detalhe = $detalhe."3C0005002901010$codigo\n";	
                            $detalhe = $detalhe."321100003351010$descricao\n";
                            $detalhe = $detalhe."331100003601010R$ $vlr\n";
                            $detalhe = $detalhe."3C0005004801010$codigo\n";
                        }
                        else if ($contador % 3 == 2) {
                            $detalhe = $detalhe."321100001700680$descricao\n";
                            $detalhe = $detalhe."3C0005002900680$codigo\n";
                            $detalhe = $detalhe."321100003350680$descricao\n";
                            $detalhe = $detalhe."331100003700680R$ $vlr\n";
                            $detalhe = $detalhe."3C0005004800680$codigo\n";
                        }
                        else {
                            $detalhe = $detalhe."321100001700290$descricao\n";
                            $detalhe = $detalhe."3C0005002900290$codigo\n";
                            $detalhe = $detalhe."321100003350290$descricao\n";
                            $detalhe = $detalhe."331100003700290R$ $vlr\n";
                            $detalhe = $detalhe."3C0005004800290$codigo\n";
                            $detalhe = $detalhe."Q0001\n";
                            $detalhe = $detalhe."E\n";
                        }
                        $contador++;
                    }
                    
                    if (substr($detalhe, -2) != "E\n") {
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";
                    }
                    
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
                else if ($_parametros['tipo-etq-argox'] == 7) {
                    $contador = 1;

                    foreach ($retorno as $row) {
                        $descricao = $row['DESCRICAO'];
                        $codigo = trim($row['CODIGO_FORNECEDOR']);
                        $qtde = $row['Qtde'];
                        $vlr = number_format($row["Valor_Venda"], 2, ',', '.');

                        if ($contador % 2 == 1) {
                            $detalhe = $detalhe."L\n";
                            $detalhe = $detalhe."m\n";
                            $detalhe = $detalhe."e\n";
                            $detalhe = $detalhe."PC\n";
                            $detalhe = $detalhe."D11\n";
                            $detalhe = $detalhe."H14\n";
                            $detalhe = $detalhe."z\n";
                            $detalhe = $detalhe."121100004300030"."EM CASO DE TROCA FAVOR"."\n";
                            $detalhe = $detalhe."121100004000030"."PRESERVAR ESTA ETIQUETA"."\n";
                            $detalhe = $detalhe."121100003700030"."FIXADA NA PECA A TROCA"."\n";
                            $detalhe = $detalhe."121100003400030"."DEVE SER EFETUADA NA LOJA"."\n";
                            $detalhe = $detalhe."121100003100030"."ONDE O PRODUTO FOI ADQUIRIDO"."\n";
                            $detalhe = $detalhe."121100001900030".$descricao."\n";                     
                            $detalhe = $detalhe."1D0005001100045".$codigo."\n";
                            $detalhe = $detalhe."121100000800030REF:".$codigo."\n";
                            $detalhe = $detalhe."131100000200090R$ ".$vlr."\n";
                        }
                        else {
                            $detalhe = $detalhe."121100004300530"."EM CASO DE TROCA FAVOR"."\n";
                            $detalhe = $detalhe."121100004000530"."PRESERVAR ESTA ETIQUETA"."\n";
                            $detalhe = $detalhe."121100003700530"."FIXADA NA PEÇA A TROCA"."\n";
                            $detalhe = $detalhe."121100003400530"."DEVE SER EFETUADA NA LOJA"."\n";
                            $detalhe = $detalhe."121100003100530"."ONDE O PRODUTO FOI ADQUIRIDO"."\n";
                            $detalhe = $detalhe."121100001900530".$descricao."\n";                     
                            $detalhe = $detalhe."1D0005001100530".$codigo."\n";
                            $detalhe = $detalhe."121100000800530REF:".$codigo."\n";
                            $detalhe = $detalhe."131100000200590R$ ".$vlr."\n";
                            $detalhe = $detalhe."Q0001\n";
                            $detalhe = $detalhe."E\n"; 
                        }
                        $contador++;
                    }

                    if (substr($detalhe, -2) != "E\n") {
                        $detalhe = $detalhe."Q0001\n";
                        $detalhe = $detalhe."E\n";
                    }
                    fwrite($arquivo, $detalhe);    
                    fclose($arquivo);
                }
            }
            */
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">                           
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Arquivo Gerado!</h2>
                            <p>Clique abaixo para baixa-lo.</p>
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            <a href="./docs/<?=$cliente?>/download/ETIQUETA.TXT" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>                            
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
                        <button class="btn btn-inverse waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/**
 * Excluir Movimento
 */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".tabetiq_peca WHERE ID = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Produto Excluído!</h2> 
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
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Busca Produto
 */
else if ($acao["acao"] == 5) {
    //die("<h1>".$_parametros['id-altera']."</h1>");
        $sql = "SELECT DESCRICAO, promocao, Codigo_Barra, CODIGO_FORNECEDOR, Codigo_Referencia_Fornec, CODIGO_FABRICANTE,CONCAT(
                COALESCE(ENDERECO1, ''), 
                ' ', 
                COALESCE(ENDERECO2, ''), 
                ' ', 
                COALESCE(ENDERECO3, '')
            ) AS ENDERECO_COMPLETO
                FROM {$_SESSION['BASE']}.itemestoque                 
                WHERE CODIGO_FABRICANTE = :codigo_fabricante";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':codigo_fabricante' => $_parametros['id-altera']]);
        $retorno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($retorno == NULL) {
        //buscar codigo de barras        
        $retorno = $consulta->fetch();
         $sql = "SELECT DESCRICAO, promocao, Codigo_Barra, CODIGO_FORNECEDOR, Codigo_Referencia_Fornec, CODIGO_FABRICANTE,CONCAT(
                COALESCE(ENDERECO1, ''), 
                ' ', 
                COALESCE(ENDERECO2, ''), 
                ' ', 
                COALESCE(ENDERECO3, '')
            ) AS ENDERECO_COMPLETO
                FROM {$_SESSION['BASE']}.itemestoque 
                WHERE Codigo_Barra = :Codigo_Barra";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':Codigo_Barra' => $_parametros['id-altera']]);
        $retorno = $stmt->fetch(PDO::FETCH_ASSOC);
    }   

    if ($retorno == NULL) {
        //buscar por codigo fornecedor        
        $retorno = $consulta->fetch();
         $retorno = $consulta->fetch();
         $sql = "SELECT DESCRICAO, promocao, Codigo_Barra, CODIGO_FORNECEDOR, Codigo_Referencia_Fornec, CODIGO_FABRICANTE,CONCAT(
                COALESCE(ENDERECO1, ''), 
                ' ', 
                COALESCE(ENDERECO2, ''), 
                ' ', 
                COALESCE(ENDERECO3, '')
            ) AS ENDERECO_COMPLETO
                FROM {$_SESSION['BASE']}.itemestoque 
                WHERE CODIGO_FORNECEDOR = :CODIGO_FORNECEDOR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':CODIGO_FORNECEDOR' => $_parametros['id-altera']]);
        $retorno = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    if ($retorno == NULL) {
        //buscar pelo codigo sku 
        $sql = "SELECT DESCRICAO, promocao, Codigo_Barra, CODIGO_FORNECEDOR, Codigo_Referencia_Fornec, CODIGO_FABRICANTE,CONCAT(
                COALESCE(ENDERECO1, ''), 
                ' ', 
                COALESCE(ENDERECO2, ''), 
                ' ', 
                COALESCE(ENDERECO3, '')
            ) AS ENDERECO_COMPLETO
                FROM {$_SESSION['BASE']}.itemestoque 
                WHERE Codigo_Referencia_Fornec = :Codigo_Referencia_Fornec";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':Codigo_Referencia_Fornec' => $_parametros['id-altera']]);
        $retorno = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    if ($retorno == NULL) {
        $retorno =  array(
            'produto' => 'Produto não encontrado', 
            'promocao' => '0'
        );

        echo json_encode($retorno);
    }
    else {
        $retorno['promocao'] = empty($retorno['promocao']) ? "0" : number_format($retorno['promocao'], 2, ',', '.');
        $retorno = array(
            'produto' => $retorno["DESCRICAO"], 
            'Codigo_Barra' => $retorno["Codigo_Barra"], 
            'codigointerno' => $retorno["CODIGO_FORNECEDOR"], 
            'Codigo_Referencia_Fornec' => $retorno["Codigo_Referencia_Fornec"], 
             'ENDERECO_COMPLETO' => $retorno["ENDERECO_COMPLETO"], 
            
            'promocao' => $retorno['promocao']
        );

        echo json_encode($retorno);
    }
}
/**
 * Gera Relatório
 */
else if ($acao["acao"] == 6) {
    try {
        $consultaParametro = $pdo->query("SELECT NOME_FANTASIA, DDD, TELEFONE FROM ".$_SESSION['BASE'].".parametro");
        $retornoParametro = $consultaParametro->fetch();

        $telefone = "(".$retornoParametro["DDD"].") ".$retornoParametro["TELEFONE"];
        $fantasia = $retornoParametro["NOME_FANTASIA"];
        
        $consultaMov = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".movtorequisicao_historico LEFT JOIN ".$_SESSION['BASE'].".itemestoque on Codigo_Item = codigo_fornecedor WHERE Num_Movto = '".$_parametros['id-busca']."'");
        $retornoMov = $consultaMov->fetch();
        
        ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                        <thead>
                            <th colspan="2"><?=$fantasia?></th>
                            <th colspan="2">Telefone: <?=$telefone?></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">Requisição N° <?=$_parametros['num-mov']?></td>
                                <td colspan="2">Data: <?=date('d/m/Y')?></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                        </tbody>
                        <thead>
                            <th><strong>Código de Barras:</strong></th>
                            <th><strong>Qtde:</strong></th>
                            <th><strong>Descrição:</strong></th>
                            <th><strong>Motivo:</strong></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$retornoMov["Codigo_Barra"]?></td>
                                <td><?=$retornoMov["Qtde"]?></td>
                                <td><?=$retornoMov["Descricao_Item"]?></td>
                                <td><?=$retornoMov["motivo"]?></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><strong>Ass. Almoxarifado:</strong></td>
                                <td colspan="2"><strong>Ass. Produção:</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal('#tabela-relatorio')">Imprimir</button>
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
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
