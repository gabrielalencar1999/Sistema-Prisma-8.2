<?php
include("../../api/config/iconexao.php");  

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

// Processamento do CSV
if (isset($_POST['importar'])) {
    if (isset($_FILES['csv']) && $_FILES['csv']['error'] === 0) {
        $arquivo = fopen($_FILES['csv']['tmp_name'], 'r');

        if ($arquivo !== false) {
            while (($linha = fgetcsv($arquivo, 1000, ";")) !== false) {
                list($svo, $assessor, $nome, $modelo, $cpf, $telefone, $cep, $numero, $complemento) = $linha;

                // Limpar CPF e telefone (remove pontos, traços, espaços, etc.)
                $cpf = preg_replace('/\D/', '', $cpf);
                $telefone = preg_replace('/\D/', '', $telefone);

                //  Consultar na tabela chamada (svo -> osfabricante)
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM " . $_SESSION['BASE'] . ".chamada WHERE osfabricante = ? LIMIT 1");
                $stmt->execute([$svo]);
                $qtdeChamada = $stmt->fetchColumn();
                $registrosChamada += $qtdeChamada;

                //  Verificar se consumidor já existe (por CPF ou qualquer telefone)
                $stmt = $pdo->prepare("
                    SELECT id 
                    FROM " . $_SESSION['BASE'] . ".consumidor 
                    WHERE cpfcnpj = :cpf 
                       OR telefonecelular = :tel 
                       OR telefoneresidencial = :tel 
                       OR telefonecomercial = :tel
                       LIMIT 1;
                ");
                $stmt->execute([
                    ':cpf' => $cpf,
                    ':tel' => $telefone
                ]);

                if ($stmt->rowCount() == 0) {
                    //  Inserir consumidor
                    $stmtInsert = $pdo->prepare("
                        INSERT INTO " . $_SESSION['BASE'] . ".consumidor 
                            (cpfcnpj, nome, telefonecelular, cep, numero, complemento)
                        VALUES 
                            (:cpf, :nome, :tel, :cep, :numero, :complemento)
                    ");
                    $stmtInsert->execute([
                        ':cpf' => $cpf,
                        ':nome' => $nome,
                        ':tel' => $telefone,
                        ':cep' => $cep,
                        ':numero' => $numero,
                        ':complemento' => $complemento
                    ]);

                    $consumidoresIncluidos++;
                }
            }

            fclose($arquivo);

            echo "<h3>Importação Concluída!</h3>";
            echo "<p>Consumidores incluídos: <b>$consumidoresIncluidos</b></p>";
            echo "<p>Registros encontrados na tabela 'chamada': <b>$registrosChamada</b></p>";
        } else {
            echo "Erro ao abrir o arquivo.";
        }
    } else {
        echo "Erro no upload do arquivo.";
    }
}
?>
