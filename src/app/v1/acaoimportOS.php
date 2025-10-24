<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

function LimpaVariavel($valor)
{
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

$atendente = $_SESSION['tecnico'];
$reg = 0; $registrosChamadaNew = 0;

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$_SESSION['pass'] = "";

$datahora     = $ano . "-" . $mes . "-" . $dia . " " . $hora;

// Processamento do CSV

    if (isset($_FILES['csv']) && $_FILES['csv']['error'] === 0) {
        $arquivo = fopen($_FILES['csv']['tmp_name'], 'r');

        if ($arquivo !== false) {
            while (($linha = fgetcsv($arquivo, 1000, ";")) !== false) {
                list($svo, $assessor, $nome,$produto,$marca, $modelocomercial, $cpf, $telefone, $cep, $rua, $numero, $complemento, $bairro, $cidade, $uf) = $linha;
                if($reg == 0) {
                        //titulo
                        $reg++;
                }else{
                        $reg++;
                
                // Limpar CPF e telefone (remove pontos, traços, espaços, etc.)
                $cpf = preg_replace('/\D/', '', $cpf);
                $telefone = preg_replace('/\D/', '', $telefone);
	             $telefoneDDD = substr($telefone, 0, 2);
                $telefone = substr($telefone, 2, 10);

                                        

                if(trim($svo) != "") {                
                    //  Consultar na tabela chamada (svo -> osfabricante)
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM " . $_SESSION['BASE'] . ".chamada WHERE NUM_ORDEM_SERVICO = ? LIMIT 1");
                    $stmt->execute([$svo]);
                    $qtdeChamada = $stmt->fetchColumn();
                    $registrosChamada += $qtdeChamada;

                    if($qtdeChamada > 0){
                        //não faz nada
                    }else{
                                            if($tipo =="1") //cpf
                                            {
                                         
                                                $TIPODESC = "CPF";
                                                $cpf = substr($cpf, 0, 3) . '.' .
                                                substr($cpf, 3, 3) . '.' .
                                                substr($cpf, 6, 3) . '-' .
                                                substr($cpf, 9, 2);
                                            } else {
                                                
                                           
                                                $TIPODESC = "CNPJ";
                                                $cpf = substr($cpf, 0, 2) . '.' .
                                                                        substr($cpf, 2, 3) . '.' .
                                                                        substr($cpf, 5, 3) . '/' .
                                                                        substr($cpf, 8, 4) . '-' .
                                                                        substr($cpf, -2);

                                            } 
                                       
                            
                                //  Verificar se consumidor já existe (por CPF ou qualquer telefone)
                                $stmt = $pdo->prepare("
                                    SELECT CODIGO_CONSUMIDOR
                                    FROM " . $_SESSION['BASE'] . ".consumidor 
                                    WHERE CGC_CPF = :cpf 
                                    OR FONE_CELULAR = :tel 
                                    OR FONE_RESIDENCIAL = :tel 
                                    OR FONE_COMERCIAL = :tel
                                    LIMIT 1;
                                ");
                                $stmt->execute([
                                    ':cpf' => $cpf,
                                    ':tel' => $telefone
                                ]);
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($result) {
                                    // Consumidor encontrado
                                    $idConsumidor = $result['CODIGO_CONSUMIDOR'];
                                } else {

                            
                                    if(trim($rua) == "") {
                                  
                                        $curl = curl_init();
                                        curl_setopt_array($curl, [
                                            CURLOPT_URL => "https://viacep.com.br/ws/{$cep}/json/",
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_TIMEOUT => 3,
                                        ]);

                                        $response = curl_exec($curl);
                                        curl_close($curl);

                                        $dados = json_decode($response, true);

                                        if (isset($dados['erro'])) {
                                           // echo "CEP não encontrado.";
                                        } else {
                                            $rua =  $dados['logradouro'];
                                            $bairro =  $dados['bairro'];
                                            $cidade = $dados['localidade'];
                                            $uf =  $dados['uf'];
                                        }  
                                    };


                                                                   //  Inserir consumidor
                                    $stmtInsert = $pdo->prepare("
                                        INSERT INTO " . $_SESSION['BASE'] . ".consumidor 
                                            (CGC_CPF, Nome_Consumidor, FONE_CELULAR,DDD, CEP,Nome_Rua, Num_Rua, COMPLEMENTO,Bairro, Cidade, UF,id_celularwats)
                                        VALUES 
                                            (:cpf, :nome, :tel, :ddd ,:cep, :rua, :numero, :complemento, :bairro , :cidade, :uf, :id_celularwats)
                                    ");
                                    $stmtInsert->execute([
                                        ':cpf' => $cpf,
                                        ':nome' => $nome,
                                        ':tel' => $telefone,
                                        ':ddd' => $telefoneDDD,
                                        ':cep' => $cep,
                                        ':rua' => $rua,
                                        ':numero' => $numero,
                                        ':complemento' => $complemento,
                                        ':bairro' => $bairro,
                                        ':cidade' => $cidade,
                                        ':uf' => $uf,
                                        ':id_celularwats' => "1"
                                    ]);
                                     $idConsumidor = $pdo->lastInsertId();

                                    $consumidoresIncluidos++;
                                }


                                //gera Ordem serviço
                                 // Buscar número da OS atual
                                        $sql = "SELECT parametro_ULTIMAOS FROM " . $_SESSION['BASE'] . ".parametro";
                                        $stmt = $pdo->query($sql);
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        if ($row) {
                                            $codigoos = $row['parametro_ULTIMAOS'];
                                            $numeroos1 = $codigoos + 1;
                                        } else {
                                            throw new Exception('Não foi possível obter o número da OS');
                                        }
                                        $codtecnico = 0;
                                        if(trim($assessor) != "") { 
                                                $sql = "SELECT usuario_CODIGOUSUARIO FROM " . $_SESSION['BASE'] . ".usuario where usuario_NOME = '$assessor'";
                                              
                                                    $stmt = $pdo->query($sql);
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($row) {
                                                        $codtecnico = $row['usuario_CODIGOUSUARIO'];                                                                                                          
                                                    } 
                                        }

                                        // Atualizar número da OS
                                        $sql = "UPDATE " . $_SESSION['BASE'] . ".parametro SET parametro_ULTIMAOS = :numeroos";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([':numeroos' => $numeroos1]);

                                        $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ABERTURA DA OS VIA CSV NESSA DATA";

                                        // Inserir na tabela chamada
                                        $sql = "INSERT INTO " . $_SESSION['BASE'] . ".chamada (
                                                    CODIGO_FABRICANTE, CODIGO_CONSUMIDOR, CODIGO_CHAMADA, DATA_CHAMADA, DATA_ATEND_PREVISTO,
                                                    CODIGO_APARELHO, marca, descricao, Modelo, Serie, Voltagem, Nota_Fiscal, Data_Nota,
                                                    CODIGO_SITUACAO, SituacaoOS_Elx, CODIGO_ATENDENTE,Cod_Tecnico_Execucao,NUM_ORDEM_SERVICO,GARANTIA
                                                ) VALUES (
                                                    :fabricante, :idcliente, :codigoos, CURRENT_DATE(), :dtatendimentoprevisto,
                                                    :aparelho, :marca, :descProduto, :modelo, :serie, :voltagem, :notafiscal, :datanf,
                                                    '1', '1', :atendente, :codtecnico, :NUM_ORDEM_SERVICO, :GARANTIA
                                                )";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([
                                            ':fabricante' => $fabricante,
                                            ':idcliente' => $idConsumidor,
                                            ':codigoos' => $numeroos1,
                                            ':dtatendimentoprevisto' => $_dtatendimentoprevisto,
                                            ':aparelho' => $aparelho,
                                            ':marca' => $marca,
                                            ':descProduto' => $produto,     
                                            ':modelo' => $modelocomercial,                                    
                                            ':serie' => $serie,
                                            ':voltagem' => $voltagem,
                                            ':notafiscal' => $notafiscal,
                                            ':datanf' => $datanf,
                                            ':atendente' => $atendente,
                                            ':codtecnico' => $codtecnico,
                                            ':NUM_ORDEM_SERVICO' => $svo,
                                            ':GARANTIA' => "2"
                                        ]);
                                                    //  ':modelo' => $modelo,
                                            $registrosChamadaNew++;

                                        // Inserir na tabela acompanhamento
                                        $sql = "INSERT INTO " . $_SESSION['BASE'] . ".acompanhamento (
                                                    ac_data, ac_hora, ac_OS, ac_usuarioid, ac_usuarionome, ac_cliente, ac_descricao, ac_sitos
                                                ) VALUES (
                                                    CURRENT_DATE(), :datahora, :codigoos, :atendente, :apelido, :idcliente, :descricao_alte, '1'
                                                )";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([
                                            ':datahora' => $datahora,
                                            ':codigoos' => $numeroos1,
                                            ':atendente' => $atendente,
                                            ':apelido' => $_SESSION['APELIDO'],
                                            ':idcliente' => $idConsumidor,
                                            ':descricao_alte' => $descricao_alte
                                        ]);

                                      //  echo "OS cadastrada com sucesso!";

                                   

                        try{			
                            $_tipoAtividade = 200;
                            $_documentoAtividade = $codigoos;
                            $_assuntoAtividade = "Nova O.S";
                            $_descricaoAtividade = "nº $codigoos  $marca";
                            $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".atividades (
                                at_id,
                                at_datahora,
                                at_iduser,
                                at_userlogin,
                                at_tipo,
                                at_icliente,				
                                at_documento,				
                                at_assunto,
                                at_descricao) 
                                    VALUES (NULL,
                                    ?,
                                    ?,					
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?, 
                                    ?); ");
                                $stm->bindParam(1, $datahora);			
                                $stm->bindParam(2, $_SESSION['tecnico']);	
                                $stm->bindParam(3, $_SESSION["APELIDO"]);		
                                $stm->bindParam(4, $_tipoAtividade);	
                                $stm->bindParam(5, $idcliente);				
                                $stm->bindParam(6, $_documentoAtividade);					
                                $stm->bindParam(7, $_assuntoAtividade);	
                                $stm->bindParam(8, $_descricaoAtividade);		
                                $stm->execute();
                                
                                $_tipoAtividade = 200;             			
                                $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
                                    l_tipo,
                                    l_datahora,
                                    l_doc,			
                                    l_usuario,								
                                    l_desc) 
                                        VALUES (
                                        ?,
                                        ?,
                                        ?,					
                                        ?,
                                        ?
                                        ); ");
                                    $stm->bindParam(1, $_tipoAtividade);
                                    $stm->bindParam(2, $datahora);	
                                    $stm->bindParam(3, $codigoos);				
                                    $stm->bindParam(4, $_SESSION["APELIDO"]);								
                                    $stm->bindParam(5, $_assuntoAtividade);						
                                    $stm->execute();


                                    $_tipoAtividade = 1;  
                                    $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".resumoOS (
                                        rsOS_tipresumo,
                                        rsOS_data,
                                        rsOS_chamada,			
                                        rsOS_cliente) 
                                            VALUES (
                                            ?,
                                            ?,
                                            ?,					
                                            ?
                                            ); ");
                                        $stm->bindParam(1, $_tipoAtividade);
                                        $stm->bindParam(2, $datahora);	
                                        $stm->bindParam(3, $codigoos);				
                                        $stm->bindParam(4, $idcliente);								
                                                    
                                        $stm->execute();
                    


                                    
                            

                        }
                        catch (\Exception $fault){
                            $response = $fault;
                        }

                              
                            }
                        }
                }
            }

            fclose($arquivo);

            echo "<h3>Importação Concluída!</h3>";
            echo "<p>Consumidores incluídos: <b>$consumidoresIncluidos</b></p>";
            echo "<p>Registros encontrados de OS: <b>$registrosChamada</b></p>";
             echo "<p>O.S incluídas: <b>$registrosChamadaNew</b></p>";
        } else {
            echo "Erro ao abrir o arquivo.";
        }
    } else {
        echo "Erro no upload do arquivo.";
    }

?>
