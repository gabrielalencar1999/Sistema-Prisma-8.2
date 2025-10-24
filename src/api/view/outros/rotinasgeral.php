<?php 

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
// Impede execução pelo navegador


//use phpseclib3\Net\SFTP;
use Database\MySQL;
$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

//************* PROCESSA O ARQUIVO A CADA 10 MINUTOS */

ini_set('max_execution_time', 0); // tempo ilimitado
set_time_limit(0);
//rotinas diarias

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");


$_datafim      = $ano . "-" . $mes . "-" . $dia;
$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

$codigoElectroux = 1; //tabela marcas 

//teste



//--------------------------------------------

                //script backup local
$now   = new DateTime('now');
$start = (clone $now)->setTime(8, 0, 0);
$end   = (clone $now)->setTime(23, 50, 59);

echo "ROTINA >".($now)->format('d/m/Y H:i:s')."\n";
  $_log = "ROTINA GERAL BK > VERIFICANDO";


     $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                VALUES ('$data_hora ','1','$_log')";         
                            $stmi = $pdo->prepare($sql);
                            $stmi->execute();	

if ($now >= $start && $now <= $end) {


   echo "- " . $now->format('H:i:s');
    // Rotina de backup
    // Verificar se já existe backup hoje
    $sqlCheck = "SELECT COUNT(*) FROM bd_prisma.aquivo_backup WHERE DATE(bk_data) = :hoje";
   // echo  $sqlCheck ;
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([':hoje' => $_datafim]);
    $count = $stmtCheck->fetchColumn();

    if ($count >55) {
          $_log = "já foi exeutado";
             $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                VALUES ('$data_hora ','1','$_log')";         
                            $stmi = $pdo->prepare($sql);
                            $stmi->execute();
            echo "já foi exeutado";
            exit();
    }

          // Diretório de destino
          
                $backupDir =  "../../docs/backups/";
              if (!is_dir($backupDir)) {
                   mkdir($backupDir, 0777, true);
               }   
                  
              // $backupDir =  "arquivos";

                        // Buscar todos os backups registrados
                        $stmt = $pdo->query("SELECT bk_id, bk_caminho FROM bd_prisma.aquivo_backup WHERE DATE(bk_data) < '$_datafim'");
                        $backups = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($backups as $bk) {
                            $arquivo = $bk['bk_caminho'];
                            // Apagar arquivo físico se existir
                            if (file_exists($arquivo)) {
                                unlink($arquivo);
                            }
                        }

                        // Limpar registros da tabela
                        $pdo->exec("DELETE FROM bd_prisma.aquivo_backup WHERE DATE(bk_data) < '$_datafim'");        
                // Buscar consumidores ativos and CODIGO_CONSUMIDOR = '7201'
                $sql = "SELECT CODIGO_CONSUMIDOR, consumidor_base 
                        FROM info.consumidor 
                        WHERE ind_situacao = '1' and Data_Alteracao < '$_datafim' 
                        OR ind_situacao = '1' and Data_Alteracao IS NULL  
                        or ind_situacao = '1' and Data_Alteracao < '$_datafim'  
                        OR ind_situacao = '1' and Data_Alteracao IS NULL  
                        ORDER BY CODIGO_CONSUMIDOR DESC ";
                $stmt = $pdo->query($sql);
                 $consumidores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $_log = "-LISTANDO BASES";
            
                $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                                VALUES ('$data_hora ','1','$_log')";         
                                            $stmi = $pdo->prepare($sql);
                                            $stmi->execute();
                                            	
                // Validar schemas existentes
                $bancosExistentes = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
                $schemasValidos = [];
                foreach ($consumidores as $cons) {
                    if (in_array($cons['consumidor_base'], $bancosExistentes, true)) {
                        $schemasValidos[] = $cons;
                    } else {
                       // error_log("Schema inválido ignorado: {$cons['consumidor_base']}");
                          $_log = "Schema inválido ignorado: {$cons['consumidor_base']}";
            
                        $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                                    VALUES ('$data_hora ','1','$_log')";         
                                                $stmi = $pdo->prepare($sql);
                                                $stmi->execute();
                        }
                }

                //VERIFICA SE 

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
                    $zipFile = "$backupDir{$nomeArquivoBase}.zip";

                    // Criar arquivo CSV temporário
                    $tempDir = "$backupDir{$nomeArquivoBase}";
                    mkdir($tempDir, 0700, true);

                    $_log = $codigoCli." - GERANDO BACKUP";
            
                   $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                                VALUES ('$data_hora ','1','$_log')";         
                                            $stmi = $pdo->prepare($sql);
                                            $stmi->execute();

                 $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

                 // Tabelas fixas a ignorar
                $excluir = ['acompanhamento_hist', 'chamadapeca_arquivo', 'chamada_arquivo'];

                    foreach ($tables as $table) {
                       // Ignorar tabelas que começam com "log" ou estão na lista de exclusão
                        if (stripos($table, 'log') === 0 || in_array($table, $excluir, true)) {
                            continue;
                        }
                        //  $_log = $codigoCli."-".$table;
             try {
                  
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
                          } catch (Exception $e) {
                        // Grava o erro no log
                        $_erro = $codigoCli . " - ERRO na tabela $table: " . $e->getMessage();
                        $sql = "INSERT INTO info.logsistema (ls_datahora, ls_tipo, ls_log) 
                                VALUES (:datahora, 2, :log)";
                        $stmi = $pdo->prepare($sql);
                        $stmi->execute([
                            ":datahora" => $data_hora,
                            ":log"      => $_erro
                        ]);

                        // Opcional: continua para a próxima tabela sem parar o backup
                        continue;
                    }
                    }
                  

                       $_log = $codigoCli." - GERANDO ZIP";
            
                   $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                                VALUES ('$data_hora ','1','$_log')";         
                                            $stmi = $pdo->prepare($sql);
                                            $stmi->execute();

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

                         $_log = $codigoCli." - LIMPANDO ARQUIVO TEMPORARIO";
            
                   $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                                VALUES ('$data_hora ','1','$_log')";         
                                            $stmi = $pdo->prepare($sql);
                                            $stmi->execute();


                    // Limpar arquivos temporários
                    $it = new RecursiveDirectoryIterator($tempDir, RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach($files as $file) {
                        $file->isDir() ? rmdir($file) : unlink($file);
                    }
                    rmdir($tempDir);

                           $_log = $codigoCli." - ATUALIZANDO TABELAS ARQUIVO TEMPORARIO";
            
                   $sql = "INSERT INTO info.logsistema (ls_datahora,ls_tipo,ls_log) 
                                                VALUES ('$data_hora ','1','$_log')";         
                                            $stmi = $pdo->prepare($sql);
                                            $stmi->execute();

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

                    $sqlUpdate= "UPDATE info.consumidor  SET Data_Alteracao = :data WHERE CODIGO_CONSUMIDOR = :login";
                    $stmtIns = $pdo->prepare($sqlUpdate);
                    $stmtIns->execute([
                        ":login"     => $codigoCli,
                        ":data"      => $data                       
                    ]);
                    

                    //echo "✅ Backup CSV do schema <b>$banco</b> registrado para consumidor $codigoCli<br>";
                }
           }


?>



		
	
