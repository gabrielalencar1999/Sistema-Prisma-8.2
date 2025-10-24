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

if ($acao["acao"] == 0) {
    ?>
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="imagem-carregando">
                <div class="bg-icon pull-request">
                    <i class="md-5x md-highlight-remove"></i>
                    <h2>Selecione uma impressora!</h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
/*
 * Gera Toledo Prix - Cód B. 4
 * */
else if ($acao["acao"] == 1) {
    try {
        $consulta = $pdo->query("SELECT id FROM ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetch();

        $cliente = $retorno["id"];

        if (is_dir("./docs/$cliente/download")) {
           if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/downloadTXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }
        else {
            mkdir("./docs/$cliente/download", 0760, true);
            if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/download/TXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }

        $statement = $pdo->query("SELECT Descricao_Reduzida,Codigo_Barra,UNIDADE_MEDIDA,Tab_Preco_5 ,Dias_Validade FROM ".$_SESSION['BASE'].".itemestoque WHERE UNIDADE_MEDIDA = 'Kg' OR UNIDADE_MEDIDA = 'UB' OR UNIDADE_MEDIDA = 'KG' OR UNIDADE_MEDIDA = 'kg' AND Tab_Preco_5 > 0");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {
            $sequencia =  $sequencia +1;

			 $descricao = $row["Descricao_Reduzida"];	
			 $descricao = substr($descricao,0,25);
			 $descricao = str_pad($descricao, 25, " ", STR_PAD_RIGHT);					

		    $codigo =  substr($row["Codigo_Barra"],0,4);  //codigo barras
            $codigo = str_pad($codigo, 6, "0", STR_PAD_LEFT);		
			// $tipo = '24'; //modelo etiqueta;
		    $validade = $row["Dias_Validade"];
			$validade = str_pad($validade, 3, "0", STR_PAD_LEFT);
		 

			$vlr = number_format($row["Tab_Preco_5"],2,',','.');	
			$vlr  = str_replace(".", "", $vlr); 
			$vlr = str_replace(",", "", $vlr);  
			
			$vlr = str_pad($vlr, 6, "0", STR_PAD_LEFT);



			 if($row["UNIDADE_MEDIDA"] == "UB") { 
			  $etiqueta  = '001';  // Kg   PCS(Kg)
			 }else { 
			  $etiqueta  = '000';  // Kg   PCS(Kg)
			} 	
			 $departamento = '01';
			

  			$detalhe = $detalhe.$departamento.$etiqueta.$codigo.$vlr.$validade.$descricao.$descrico2."\n";
        }
        fwrite($arquivo, $detalhe);  
        fclose($arquivo);
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Arquivo Gerado!</h2>
                        <p>Clique abaixo para baixa-lo.</p>
                        <a href="./docs/<?=$cliente?>/download/TXITENS.TXT" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>
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
/*
 * Gera Toledo Prix - Cód B. 6
 * */
else if ($acao["acao"] == 2) {
    try {
        $consulta = $pdo->query("SELECT id FROM ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetch();

        $cliente = $retorno["id"];

        if (is_dir("./docs/$cliente/download")) {
           if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/downloadTXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }
        else {
            mkdir("./docs/$cliente/download", 0760, true);
            if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/download/TXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }

        $statement = $pdo->query("SELECT Descricao_Reduzida,Codigo_Barra,UNIDADE_MEDIDA,Tab_Preco_5 ,Dias_Validade FROM ".$_SESSION['BASE'].".itemestoque WHERE UNIDADE_MEDIDA = 'Kg' OR UNIDADE_MEDIDA = 'UB' OR UNIDADE_MEDIDA = 'KG' OR UNIDADE_MEDIDA = 'kg' AND Tab_Preco_5 > 0");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {
            $sequencia =  $sequencia +1;

            $descricao = $row["Descricao_Reduzida"];	
            $descricao = substr($descricao,0,25);
            $descricao = str_pad($descricao, 25, " ", STR_PAD_RIGHT);					

            $codigo =  substr($row["Codigo_Barra"],0,6);  //codigo barras
            $codigo = str_pad($codigo, 6, "0", STR_PAD_LEFT);		
            // $tipo = '24'; //modelo etiqueta;
            $validade = $row["Dias_Validade"];

            $validade = str_pad($validade, 3, "0", STR_PAD_LEFT);


            $vlr = number_format($row["Tab_Preco_5"],2,',','.');	
            $vlr  = str_replace(".", "", $vlr); 
            $vlr = str_replace(",", "", $vlr);  

            $vlr = str_pad($vlr, 6, "0", STR_PAD_LEFT);

            if($row["UNIDADE_MEDIDA"] == "UB") { 
                $etiqueta  = '001';  // Kg   PCS(Kg)
            }else { 
                $etiqueta  = '000';  // Kg   PCS(Kg)
            } 
            if($_SESSION['LOGADO'] != "7157") { 
                $departamento = '01';
            }else{
                $departamento = '04';
            }
            //$extra = str_pad("", 50, " ", STR_PAD_LEFT);
            $detalhe = $detalhe.$departamento.$etiqueta.$codigo.$vlr.$validade.$descricao.$descrico2."\n";
        }
        fwrite($arquivo, $detalhe);  
        fclose($arquivo);
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Arquivo Gerado!</h2>
                        <p>Clique abaixo para baixa-lo.</p>
                        <a href="./docs/<?=$cliente?>/download/TXITENS.TXT" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>
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
/**
 * Gera Ramuza
 */
else if ($acao["acao"] == 3) {
    try {
        $consulta = $pdo->query("SELECT id FROM ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetch();

        $cliente = $retorno["id"];

        if (is_dir("./docs/$cliente/download")) {
           if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/downloadTXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }
        else {
            mkdir("./docs/$cliente/download", 0760, true);
            if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/download/TXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }

        $statement = $pdo->query("SELECT Descricao_Reduzida,Codigo_Barra,UNIDADE_MEDIDA,Tab_Preco_5 ,Dias_Validade FROM ".$_SESSION['BASE'].".itemestoque WHERE UNIDADE_MEDIDA = 'Kg' OR UNIDADE_MEDIDA = 'UB' OR UNIDADE_MEDIDA = 'KG' OR UNIDADE_MEDIDA = 'kg' AND Tab_Preco_5 > 0");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {
            $sequencia =  $sequencia +1;

            $descricao = $row["Descricao_Reduzida"];	
            // $descricao = substr($descricao,0,25);
            // $descricao = str_pad($descricao, 25, " ", STR_PAD_RIGHT);					
            $codigofornecedor = str_pad($row['codigo_fornecedor'], 4, "0", STR_PAD_LEFT);
            $codigo =  substr($row["Codigo_Barra"],0,6);  //codigo barras
            $codigo = str_pad($codigo, 7, "0", STR_PAD_LEFT);		
            // $tipo = '24'; //modelo etiqueta;
            $validade = $row["Dias_Validade"];
            $validade = str_pad($validade, 3, "0", STR_PAD_LEFT);

            $vlr = number_format($row["Tab_Preco_5"],2,',','.');	
            $vlr  = str_replace(".", "", $vlr); 
            $vlr = str_replace(",", "", $vlr);  
            $vlr = str_pad($vlr, 6, "0", STR_PAD_LEFT);

            if($row["UNIDADE_MEDIDA"] == "UB") { 
                $etiqueta  = '001';  // Kg   PCS(Kg)
            }else { 
                $etiqueta  = '000';  // Kg   PCS(Kg)
            } 	
            $departamento = '01';

            //$extra = str_pad("", 50, " ", STR_PAD_LEFT);
            $detalhe = $detalhe.$codigofornecedor."A".$codigo.$vlr.$validade."000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000##".$descricao."#### \r\n";

        }
        fwrite($arquivo, $detalhe);  
        fclose($arquivo);
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Arquivo Gerado!</h2>
                        <p>Clique abaixo para baixa-lo.</p>
                        <a href="./docs/<?=$cliente?>/download/TXITENS.TXT" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>
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
/**
 * Gera Micheletti - 1
 */
else if ($acao["acao"] == 4) {
    try {
        $consulta = $pdo->query("SELECT id FROM ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetch();

        $cliente = $retorno["id"];

        if (is_dir("./docs/$cliente/download")) {
           if (file_exists("./docs/$cliente/download/CSVITENS.CSV")) {
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
           else {
                unlink("./docs/$cliente/downloadCSVITENS.CSV");
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
        }
        else {
            mkdir("./docs/$cliente/download", 0760, true);
            if (file_exists("./docs/$cliente/download/CSVITENS.CSV")) {
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
           else {
                unlink("./docs/$cliente/download/CSVITENS.CSV");
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
        }

        $statement = $pdo->query("SELECT Descricao_Reduzida,Codigo_Barra,UNIDADE_MEDIDA,Tab_Preco_5 ,Dias_Validade FROM ".$_SESSION['BASE'].".itemestoque WHERE UNIDADE_MEDIDA = 'Kg' OR UNIDADE_MEDIDA = 'UB' OR UNIDADE_MEDIDA = 'KG' OR UNIDADE_MEDIDA = 'kg' AND Tab_Preco_5 > 0");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {
            $sequencia =  $sequencia +1;
            $descricao = $row["Descricao_Reduzida"];						
            $plu = ltrim($row["Codigo_Barra"], "0");     //codig cco plu
            $codigo =  ltrim($row["Codigo_Barra"], "0");  //codigo barras
            $tipo = '24'; //modelo etiqueta;
            $validade = $row["Dias_Validade"];
            $vlr = number_format($row["Tab_Preco_5"],2,',','.');	
            $vlr  = str_replace(".", "", $vlr); 
            $vlr = str_replace(",", "", $vlr);  

            if($row["UNIDADE_MEDIDA"] == "UB") { 
                $unidade  = "A";  // Kg   PCS(Kg)
            }
            else { 
                $unidade  = "4";  // Kg   PCS(Kg)
            } 	

            $departamento = '2';

            $detalhe = $detalhe.$codigo.",".$descricao.",".$plu.",".$codigo.",".$tipo.",".$vlr.",".$unidade.",".$departamento.", ,".$validade.",000,30,0, 0,000,5,1,0,0,0,0,0\n";	
        }
        fwrite($arquivo, $detalhe);  
        fclose($arquivo);
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Arquivo Gerado!</h2>
                        <p>Clique abaixo para baixa-lo.</p>
                        <a href="./docs/<?=$cliente?>/download/CSVITENS.CSV" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>
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
/**
 * Gera Micheletti - 2
 */
else if ($acao["acao"] == 5) {
    try {
        $consulta = $pdo->query("SELECT id FROM ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetch();

        $cliente = $retorno["id"];

        if (is_dir("./docs/$cliente/download")) {
           if (file_exists("./docs/$cliente/download/CSVITENS.CSV")) {
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
           else {
                unlink("./docs/$cliente/downloadCSVITENS.CSV");
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
        }
        else {
            mkdir("./docs/$cliente/download", 0760, true);
            if (file_exists("./docs/$cliente/download/CSVITENS.CSV")) {
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
           else {
                unlink("./docs/$cliente/download/CSVITENS.CSV");
                $arquivo = fopen("./docs/$cliente/download/CSVITENS.CSV", "a");
           }
        }

        $statement = $pdo->query("SELECT Descricao_Reduzida,Codigo_Barra,UNIDADE_MEDIDA,Tab_Preco_5 ,Dias_Validade FROM ".$_SESSION['BASE'].".itemestoque WHERE UNIDADE_MEDIDA = 'Kg' OR UNIDADE_MEDIDA = 'UB' OR UNIDADE_MEDIDA = 'KG' OR UNIDADE_MEDIDA = 'kg' AND Tab_Preco_5 > 0");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {
            $sequencia =  $sequencia +1;
            $descricao = $row["Descricao_Reduzida"];						
            $plu = ltrim($row["Codigo_Barra"], "0");     //codig cco plu
            $codigo =  ltrim($row["Codigo_Barra"], "0");  //codigo barras
            $tipo = '24'; //modelo etiqueta;
            $validade = $row["Dias_Validade"];
            $vlr = number_format($row["Tab_Preco_4"],2,',','.');	
            $vlr  = str_replace(".", "", $vlr); 
            $vlr = str_replace(",", "", $vlr);  

            if($row["UNIDADE_MEDIDA"] == "UB") { 
                $unidade  = "A";  // Kg   PCS(Kg)
            }
            else { 
                $unidade  = "4";  // Kg   PCS(Kg)
            } 	
            $departamento = '2';

            $detalhe = $detalhe.$codigo.",".$descricao.",".$plu.",".$codigo.",".$tipo.",".$vlr.",".$unidade.",".$departamento.", ,".$validade.",000,30,0, 0,000,5,1,0,0,0,0,0\n";	
        }
        fwrite($arquivo, $detalhe);  
        fclose($arquivo);
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Arquivo Gerado!</h2>
                        <p>Clique abaixo para baixa-lo.</p>
                        <a href="./docs/<?=$cliente?>/download/CSVITENS.CSV" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>
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
/**
 * Gera Filizola
 */
else if ($acao["acao"] == 6) {
    try {
        $consulta = $pdo->query("SELECT id FROM ".$_SESSION['BASE'].".parametro");
        $retorno = $consulta->fetch();

        $cliente = $retorno["id"];

        if (is_dir("./docs/$cliente/download")) {
           if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/downloadTXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }
        else {
            mkdir("./docs/$cliente/download", 0760, true);
            if (file_exists("./docs/$cliente/download/TXITENS.TXT")) {
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
           else {
                unlink("./docs/$cliente/download/TXITENS.TXT");
                $arquivo = fopen("./docs/$cliente/download/TXITENS.TXT", "w");
           }
        }

        $statement = $pdo->query("SELECT Descricao_Reduzida,Codigo_Barra,UNIDADE_MEDIDA,Tab_Preco_5 ,Dias_Validade FROM ".$_SESSION['BASE'].".itemestoque WHERE UNIDADE_MEDIDA = 'Kg' OR UNIDADE_MEDIDA = 'UB' OR UNIDADE_MEDIDA = 'KG' OR UNIDADE_MEDIDA = 'kg' AND Tab_Preco_5 > 0");
        $retorno = $statement->fetchAll();

        foreach ($retorno as $row) {
            $sequencia =  $sequencia +1;

            $descricao = $row["Descricao_Reduzida"];	
            $descricao = substr($descricao,0,22);
            $descricao = str_pad($descricao, 22, " ", STR_PAD_RIGHT);					

            $codigo =  substr($row["Codigo_Barra"],0,5);  //codigo barras
            $codigo = str_pad($codigo, 6, "0", STR_PAD_LEFT);		
            // $tipo = '24'; //modelo etiqueta;
            $validade = $row["Dias_Validade"];
            $validade = str_pad($validade, 3, "0", STR_PAD_LEFT);

            $vlr = number_format($row["Tab_Preco_5"],2,',','.');	
            $vlr  = str_replace(".", "", $vlr); 
            $vlr = str_replace(",", "", $vlr);  
            $vlr = str_pad($vlr, 7, "0", STR_PAD_LEFT);

            if($row["UNIDADE_MEDIDA"] == "UB") { 
                $etiqueta  = '001';  // Kg   PCS(Kg)
                $x = "U";
            }
            else { 
                $etiqueta  = '000';  // Kg   PCS(Kg)
                $x = "P";
            } 	
            $departamento = '01';
            $detalhe = $detalhe.$codigo.$x.$descricao.$vlr.$validade.$descrico2."\n";
        }
        fwrite($arquivo, $detalhe);  
        fclose($arquivo);
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Arquivo Gerado!</h2>
                        <p>Clique abaixo para baixa-lo.</p>
                        <a href="./docs/<?=$cliente?>/download/TXITENS.TXT" download class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;">Download</a>
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