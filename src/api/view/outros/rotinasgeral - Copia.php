<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

                

use phpseclib3\Net\SFTP;
use Database\MySQL;
$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

//************* PROCESSA O ARQUIVO A CADA 10 MINUTOS */



//rotinas diarias

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");


$_datafim      = $ano . "-" . $mes . "-" . $dia;
$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

$codigoElectroux = 1; //tabela marcas 


/*
function sftpUpload($sftpHost, $sftpPort, $sftpUser, $sftpPass, $localFilePath, $remoteFilePath) {
    // Inicializa a conexão SFTP
    $sftp = new SFTP($sftpHost, $sftpPort);
  
    // Faz login no servidor SFTP
    if (!$sftp->login($sftpUser, $sftpPass)) {
        die("Não foi possível conectar ao servidor SFTP ou realizar login.");
    }

    // Faz upload do arquivo 
    if ($sftp->put($remoteFilePath, file_get_contents($localFilePath))) {
        echo "Arquivo enviado com sucesso para $remoteFilePath no servidor SFTP.";
    } else {
        echo "Falha ao enviar o arquivo $localFilePath.";
    }
 
}



*/


//$sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log)  VALUES ('$data_hora ','1','verificando bk csv $horaAtual')";         
//$stmi = $pdo->prepare($sql);
//$stmi->execute();	

                //script backup local
$now   = new DateTime('now');
$start = (clone $now)->setTime(23, 0, 0);
$end   = (clone $now)->setTime(23, 20, 59);



if ($now >= $start && $now <= $end) {


   echo " " . $now->format('H:i:s');
    // Rotina de backup
    // Verificar se já existe backup hoje
    $sqlCheck = "SELECT COUNT(*) FROM bd_prisma.aquivo_backup WHERE DATE(bk_data) = :hoje";
   // echo  $sqlCheck ;
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([':hoje' => $_datafim]);
    $count = $stmtCheck->fetchColumn();

    if ($count >0 ) {
    echo "já foi exeutado";
            exit();
    }

          // Diretório de destino
                $backupDir =  "arquivos";
              //  if (!is_dir($backupDir)) {
                //    mkdir($backupDir, 0777, true);
             //   }   

                        // Buscar todos os backups registrados
                        $stmt = $pdo->query("SELECT bk_id, bk_caminho FROM bd_prisma.aquivo_backup");
                        $backups = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($backups as $bk) {
                            $arquivo = $bk['bk_caminho'];
                            // Apagar arquivo físico se existir
                            if (file_exists($arquivo)) {
                                unlink($arquivo);
                            }
                        }

                        // Limpar registros da tabela
                        $pdo->exec("DELETE FROM bd_prisma.aquivo_backup");        
                // Buscar consumidores ativos
                $sql = "SELECT CODIGO_CONSUMIDOR, consumidor_base 
                        FROM info.consumidor 
                        WHERE ind_situacao = 1 ";
                $stmt = $pdo->query($sql);
                $consumidores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Validar schemas existentes
                $bancosExistentes = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
                $schemasValidos = [];
                foreach ($consumidores as $cons) {
                    if (in_array($cons['consumidor_base'], $bancosExistentes, true)) {
                        $schemasValidos[] = $cons;
                    } else {
                        error_log("Schema inválido ignorado: {$cons['consumidor_base']}");
                    }
                }

                foreach ($schemasValidos as $cons) {
                    $codigoCli = (int)$cons['CODIGO_CONSUMIDOR'];
                    $banco     = $cons['consumidor_base'];
                    
                    $data = date("Y-m-d H:i:s");
                  
                    // Alterar schema ativo
                   // $pdo->exec("USE `$banco`");
                   $pdo->exec("USE `" . str_replace("`","``",$banco) . "`");

                    // Nome do arquivo CSV + hash + data
                    $chave = substr(md5(uniqid(mt_rand(), true)), 0, 8);
                    $nomeArquivoBase = "cli{$codigoCli}_{$chave}_" . date("Ymd_His");
                    $zipFile = "$backupDir/{$nomeArquivoBase}.zip";

                    // Criar arquivo CSV temporário
                    $tempDir = "$backupDir/{$nomeArquivoBase}";
                    mkdir($tempDir, 0700, true);

                 $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

                 // Tabelas fixas a ignorar
                $excluir = ['acompanhamento_hist', 'chamadapeca_arquivo', 'chamada_arquivo'];

                    foreach ($tables as $table) {
                       // Ignorar tabelas que começam com "log" ou estão na lista de exclusão
                        if (stripos($table, 'log') === 0 || in_array($table, $excluir, true)) {
                            continue;
                        }
                        $fp = fopen("$tempDir/{$table}.csv", 'w');
                    // 🔹 Pega os nomes das colunas
                        $stmtCols = $pdo->query("SHOW COLUMNS FROM `$table`");
                        $columns = $stmtCols->fetchAll(PDO::FETCH_COLUMN);
                        fputcsv($fp, $columns); // Cabeçalho no CSV

                        $stmtTable = $pdo->query("SELECT * FROM `$table`");
                        while ($row = $stmtTable->fetch(PDO::FETCH_ASSOC)) {
                            fputcsv($fp, $row);
                        }

                        fclose($fp);
                    }

                    // Compactar em ZIP com senha
                    $zipSenha = "prisma".$codigoCli;
                    $zip = new ZipArchive();
                    if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
                        $zip->setPassword($zipSenha);

                        $files = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($tempDir),
                            RecursiveIteratorIterator::LEAVES_ONLY
                        );

                        foreach ($files as $name => $file) {
                            if (!$file->isDir()) {
                                $filePath = $file->getRealPath();
                                $relativePath = substr($filePath, strlen($tempDir) + 1);
                                $zip->addFile($filePath, $relativePath);
                                  $zip->setPassword($zipSenha);
                                 $zip->setEncryptionName($relativePath, ZipArchive::EM_AES_256);
                            }
                        }

                        $zip->close();
                    }

                    // Limpar arquivos temporários
                    $it = new RecursiveDirectoryIterator($tempDir, RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach($files as $file) {
                        $file->isDir() ? rmdir($file) : unlink($file);
                    }
                    rmdir($tempDir);

                    // Registrar backup no banco central
                    $sqlInsert = "INSERT INTO bd_prisma.aquivo_backup (bk_login, bk_data, bk_descricao, bk_caminho)
                                VALUES (:login, :data, :descricao, :caminho)";
                    $stmtIns = $pdo->prepare($sqlInsert);
                    $stmtIns->execute([
                        ":login"     => $codigoCli,
                        ":data"      => $data,
                        ":descricao" => "Backup em CSV dos dados",
                        ":caminho"   => $zipFile
                    ]);

                    //echo "✅ Backup CSV do schema <b>$banco</b> registrado para consumidor $codigoCli<br>";
                }
           }

//fim backup local 
            /*
            //ROTINAS DA YOU DB-------------------------------------------------------------------------------------------------------------------
            // Configurações do servidor SFTP e arquivos
            $sftpHost = 'sftp.youdb.com.br:30087';
            $sftpPort = 22; // Porta padrão para SFTP
            $sftpUser = 'electrolux_rnsa_prisma';
            $sftpPass = '3lec@1758Fl0r1d@';


            $_log = "INICIO $data_hora \n ";

            //ROTINA DE GERACAO ARQUIVOS PARA ELECTROLUX A CADA 7 DIAS 

            //------------------------------------------------------------------------------------------------------------------------------
            $executa = $_GET['executa'];
            echo  $executa ;
            //verificar se é domingo 
            if (date('w') == 0 and  $_dataini == "" or  $executa == 1) { //

                $_log =  $_log."EXECUTADO $data_hora \n ";

            $_COD = $_GET['LOGIN'];

            if($_COD != "") {
                $filtro = "and  CODIGO_CONSUMIDOR = '$_COD'";
            }

            if($_COD != "") {
                $_dataini = '2024-01-01';
            }



                

                //verificar se esta no prazo para gerar aquivo 
                
                    $SQl = "SELECT lelx_data FROM bd_prisma.log_arquivoelx  LIMIT 1; ";      
                    $ret = $pdo->query($SQl);
                
                    if($ret->rowCount() > 0) {
                        foreach ($ret as $rowLog) { 
                        $_dataini = $rowLog['lelx_data'];
                        echo $_dataini;
                        $_log =  $_log."DATA ULTIMA EXECUÇÃO $_dataini \n ";
                        }
                            //grava registro para proximo processamento 
                            $stm = $pdo->prepare("UPDATE bd_prisma.log_arquivoelx SET  lelx_data = '$data_hora'; ");
                            $stm->execute();	
                            $_log =  $_log."DATA ATUALIZADA $data_hora \n ";
                    }
                
                if($_dataini ==  $_datafim ){
                    $_log =  $_log."DATA JÁ ATUALIZADA ATUALIZADA $data_hora \n ";
                    $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                        VALUES ('$data_hora ','1','$_log')";         
                    $stmi = $pdo->prepare($sql);
                    $stmi->execute();			
                        
                    exit();
                }

                $SQl = "SELECT CODIGO_CONSUMIDOR,CGC_CPF,consumidor_base FROM  info.consumidor WHERE autorizado = '2' $filtro";
                $consultaAut = $pdo->query($SQl);
                $retornoAutorizadas = $consultaAut->fetchAll();

                foreach ($retornoAutorizadas as $rowAut) { //buscar autorizadas com status 2

                    $CODIGOAUT = $rowAut['CODIGO_CONSUMIDOR'];
                    $CNPJ = $rowAut['CGC_CPF'];
                    $BASE = $rowAut['consumidor_base'];

            
                    $_log =  $_log."CODIGO AUT $CODIGOAUT \n ";
                    

                    $stm = $pdo->prepare("DELETE FROM " . $BASE . ".selloutElx  ");                                       
                    $stm->bindParam(1,$CODIGOAUT );	
                    $stm->execute();
                                
                                

                                        $nomearquivo = $CNPJ."_vendas_".$ano.$mes.$dia; 

                                        $dir = "arquivos/".$CODIGOAUT;
                                    
                                        $arquivo_caminho = "arquivos/".$CODIGOAUT."/".$nomearquivo.".csv";

                                        $_log =  $_log."$arquivo_caminho \n ";

                                        if(is_dir($dir))
                                            {
                                                //echo "A Pasta Existe";
                                            }
                                            else
                                            {            
                                                mkdir($dir."/", 0777, true);                
                                            }
                                    
                                                
                                        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                                        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
                                    
                                        foreach ( $ri as $file ) {
                                            $file->isDir() ?  rmdir($file) : unlink($file);
                                        }
                                
                                        //OS
                                        $sqOS = "SELECT CODIGO_FORNECEDOR,CODIGO_FABRICANTE,Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(Data_entrada, '%Y-%m-%d') AS mes_ano,DATE_FORMAT(DATA_ULT_SAIDA, '%Y-%m-%d') AS data_atualizacao 
                                        ,SUM(Qtde_peca) AS total_quantidade,  SUM(PRECO_CUSTO) AS total_valor 
                                        FROM   ".$BASE.".chamadapeca
                                        INNER JOIN ".$BASE.".itemestoque ON Codigo_Peca_OS = CODIGO_FORNECEDOR
                                        WHERE Codigo_Referencia_Fornec <>'000000000000000000' AND COD_FABRICANTE = '$codigoElectroux' and  Codigo_Referencia_Fornec <> '' AND Data_entrada BETWEEN '".$_dataini."' AND '".$_datafim."'
                                        GROUP BY Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(Data_entrada, '%Y-%m-%d') ORDER BY  DATE_FORMAT(Data_entrada, '%Y-%m-%d');";            
                                        //vendas
                                            $sq = "SELECT CODIGO_FORNECEDOR,CODIGO_FABRICANTE,Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(DATA_COMPRA, '%Y-%m-%d') AS mes_ano,DATE_FORMAT(DATA_ULT_SAIDA, '%Y-%m-%d') AS data_atualizacao 
                                            ,SUM(QUANTIDADE) AS total_quantidade,  SUM(PRECO_CUSTO) AS total_valor 
                                            FROM   ".$BASE.".saidaestoqueitem
                                            INNER JOIN ".$BASE.".itemestoque ON CODIGO_ITEM = CODIGO_FORNECEDOR
                                            WHERE Codigo_Referencia_Fornec <>'000000000000000000' AND COD_FABRICANTE = '$codigoElectroux' and  Codigo_Referencia_Fornec <> '' AND DATA_COMPRA BETWEEN '".$_dataini."' AND '".$_datafim."'
                                            GROUP BY Codigo_Referencia_Fornec,DESCRICAO, DATE_FORMAT(DATA_COMPRA, '%Y-%m-%d') ORDER BY  DATE_FORMAT(DATA_COMPRA, '%Y-%m-%d');";
                        
                                    try{	
                                        
                                        $consulta = $pdo->query($sqOS);                          
                                        $retorno = $consulta->fetchAll();

                                    foreach ($retorno as $row) {
                                        $cod410= $row["Codigo_Referencia_Fornec"];                           
                                    // $event_date= $_datafim; //$row["mes_ano"];
                                        $event_date= $row["mes_ano"];
                                        $description= $row["DESCRICAO"];
                                        $quantity= $row["total_quantidade"];
                                        $group= "";
                                        $netsales= 0;
                                        $product_line= "";
                                        $status= "active";
                                        $updated_at= $row["data_atualizacao"];
                                        $origem = "1"; //1 OS 2 vendas
                                        $gerado = "0";
                                        $codigo_novo = $row["CODIGO_FABRICANTE"];
                                        $codigo_fornecedor = $row["CODIGO_FORNECEDOR"];
                                    

                                        $sql = "INSERT INTO " . $BASE . ".selloutElx (
                                            cod410, event_date, description, quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado,codigo_novo,cod_interno) 
                                            VALUES ('$cod410','$event_date','$description','$quantity','$group','$netsales','$product_line','$status','$updated_at','$origem','$gerado','$codigo_novo','$codigo_fornecedor')";
                                        
                                        $stmi = $pdo->prepare($sql);
                                        $stmi->execute();			
                                            
                                        }

                                        //vendas
                                        $consulta = $pdo->query($sq);
                                        $retorno = $consulta->fetchAll();
                                    foreach ($retorno as $row) {
                                        $cod410= $row["Codigo_Referencia_Fornec"];
                                    // $event_date= $_datafim ;//$row["mes_ano"];
                                        $event_date= $row["mes_ano"];
                                        $description= $row["DESCRICAO"];
                                        $quantity= $row["total_quantidade"];
                                        $group= "";
                                        $netsales= 0;
                                        $product_line= "";
                                        $status= "active";
                                        $updated_at= $row["data_atualizacao"];
                                        $origem = "2"; //1 OS 2 vendas
                                        $gerado = "0";
                                        $codigo_novo = $row["CODIGO_FABRICANTE"];
                                        $codigo_fornecedor = $row["CODIGO_FORNECEDOR"];

                                        
                                        //echo "$cod410- $description<Br>";
                                    
                                        $sql = "INSERT INTO " . $BASE . ".selloutElx (
                                                    cod410, event_date, description, quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado,codigo_novo,cod_interno) 
                                                    VALUES ('$cod410','$event_date','$description','$quantity','$group','$netsales','$product_line','$status','$updated_at','$origem','$gerado','$codigo_novo','$codigo_fornecedor')";                                                                         
                                                    $stmi = $pdo->prepare($sql);
                                                    $stmi->execute();	 
                                            
                                        }
                                    }
                                    catch (\Exception $fault){
                                                            

                                    }

                                
                                        //gera todos registros acumulado
                                        $sq = "SELECT cod410,codigo_novo,Qtde_Disponivel,description, DATE_FORMAT(event_date, '%Y-%m-%d') AS mes_ano,DATE_FORMAT(updated_at, '%Y-%m-%d') AS data_atualizacao 
                                        ,SUM(quantity) AS total_quantidade,  SUM(sales) AS total_valor 
                                        FROM   ".$BASE.".selloutElx 
                                        INNER JOIN ".$BASE.".itemestoquealmox ON CODIGO_ITEM = cod_interno
                                        WHERE Codigo_Almox = 1 and gerado = '0' and cod410 <> '000000000000000000' group by cod410,mes_ano";
                                    
                                        try{	

                                        $consulta = $pdo->query($sq);
                                        $retorno = $consulta->fetchAll();

                                        if($consulta->rowCount() > 0) {

                                            $_log =  $_log."REGISTROS".$consulta->rowCount()." \n ";

                                            $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
                                            $_itemlinha = "cod410;codigo_novo;event_date;description;quantity;group;net sales;product_line;status;updated_at;current stock";
                                            fwrite($fp,$_itemlinha."\r\n");
                                            
                                            foreach ($retorno as $row) {
                                                $cod410= $row["cod410"];
                                                $codfabricante= $row["codigo_novo"];
                                                $cod410 = str_pad($cod410, 18, "0", STR_PAD_LEFT);
                                                $event_date= $row["mes_ano"];
                                                $description= $row["description"];
                                                $quantity= $row["total_quantidade"];
                                                $estoque= $row["Qtde_Disponivel"];
                                                $group= "";
                                                $netsales= 0;
                                                $product_line= "";
                                                $status= "";
                                                $updated_at= $row["data_atualizacao"];
                                                $_itemlinha = "$cod410;$codfabricante;$event_date;$description;$quantity;$group;$netsales;$product_line;$status;$updated_at;$estoque";
                                                fwrite($fp,$_itemlinha."\r\n");
                                            
                                            }

                                                    fclose($fp);   
                                                
                                                        $arquivo = $nomearquivo.'.csv';
                                                    
                                                        if( file_exists($arquivo_caminho)){ 
                                                        //faz ftp
                                                        }else{ 
                                                        }

                                            }
                                                    //EXECUTA FTP
                                            
                                            $localFilePath = $arquivo_caminho;//"caminho/para/arquivo/local.txt";  
                                            echo  $localFilePath."<br>";               
                                            $remoteFilePath = "in/".$CNPJ."/".$nomearquivo.".csv"; //"caminho/para/arquivo/remoto.txt";
                                        
                                        
                                            // Chama a função para fazer o upload
                                    sftpUpload($sftpHost, $sftpPort, $sftpUser, $sftpPass, $localFilePath, $remoteFilePath);
                                    
                                            //fim csv
                                            $_log =  $_log."FTP OK".$remoteFilePath." \n ";
                                        
                                        }
                                        catch (\Exception $fault){
                                            $_log =  $_log."FTP ERRO".$fault." \n ";

                                        }
                                        $stm = $pdo->prepare("DELETE FROM " . $BASE . ".selloutElx  ");                                       
                                    //  $stm->bindParam(1,$CODIGOAUT );	

                                    
                                    
            //$stm->execute();	

                            $_log =  $_log."FIM ---------------- \n ";  

                            $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                VALUES ('$data_hora ','1','$_log')";         
                            $stmi = $pdo->prepare($sql);
                            $stmi->execute();		
                            
                            $_log  = "";
                    
                            }

                    
                    


                

            }   
            */  

            //FIM ROTINA DE GERACAO ARQUIVOS PARA ELECTROLUX A CADA 7 DIAS 
            //-------------------------------------------------------------------------------------------------------------------------------------------------

?>



		
	