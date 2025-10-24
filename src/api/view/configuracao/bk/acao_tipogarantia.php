<?php
session_start();
use Database\MySQL;

header('Content-Type: application/json');

// Conexão PDO
$pdo = MySQL::acessabd();
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados']);
    exit;
}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Pega os parâmetros
$acao = $_POST['acao'] ?? null;
$id   = $_POST['id'] ?? null;

// Decodifica os dados enviados pelo JS
$dados = json_decode($_POST['dados'] ?? '{}', true);

// Variáveis para situação de garantia
$descricao = $dados['g_descricao'] ?? '';
$sigla     = $dados['g_sigla'] ?? '';
$prazo     = $dados['g_prazoatend'] ?? '';
$cor       = $dados['g_cor'] ?? '';

// Variáveis para tipo de equipamento
$tipo_desc = $dados['tipo_desc'] ?? '';

try {

    switch ($acao) {

        // =================== Situação de Garantia =================== //

        // Buscar situação
        case '1':
            if (!$id) throw new Exception('ID não informado');
            $stmt = $pdo->prepare("SELECT * FROM {$_SESSION['BASE']}.situacao_garantia WHERE g_id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'dados' => $stmt->fetch(PDO::FETCH_ASSOC)]);
            break;

        // Inserir situação
        case '2':
            $stmt = $pdo->prepare("INSERT INTO {$_SESSION['BASE']}.situacao_garantia 
                (g_descricao, g_sigla, g_prazoatend, g_cor)
                VALUES (:descricao, :sigla, :prazo, :cor)");
            $stmt->execute([
                ':descricao' => $descricao,
                ':sigla'     => $sigla,
                ':prazo'     => $prazo,
                ':cor'       => $cor
            ]);
            echo json_encode(['success' => true, 'message' => 'Situação incluída']);
            break;

        // Atualizar situação
       case '3': // atualizar situação
    $dados = json_decode($_POST['dados'] ?? '{}', true);
    $descricao = $dados['g_descricao'] ?? '';
    $sigla     = $dados['g_sigla'] ?? '';
    $prazo     = $dados['g_prazoatend'] ?? '';
    $cor       = $dados['g_cor'] ?? '';
    $id        = $dados['id'] ?? null; // <- pega do hidden

    if (!$id) throw new Exception('ID não informado');

    $stmt = $pdo->prepare("UPDATE {$_SESSION['BASE']}.situacao_garantia 
        SET g_descricao=:descricao, g_sigla=:sigla, g_prazoatend=:prazo, g_cor=:cor
        WHERE g_id=:id");
    $stmt->execute([
        ':descricao' => $descricao,
        ':sigla'     => $sigla,
        ':prazo'     => $prazo,
        ':cor'       => $cor,
        ':id'        => $id
    ]);
    echo json_encode(['success' => true, 'message' => 'Situação atualizada']);
    break;


        // Excluir situação
        case '4':
            if (!$id) throw new Exception('ID não informado');
            $stmt = $pdo->prepare("DELETE FROM {$_SESSION['BASE']}.situacao_garantia WHERE g_id=:id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Situação excluída']);
            break;

        // =================== Tipo de Equipamento =================== //

        // Buscar tipo
        case '5':
            if (!$id) throw new Exception('ID não informado');
            $stmt = $pdo->prepare("SELECT * FROM {$_SESSION['BASE']}.tipo_equipamento WHERE tipo_id=:id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'dados' => $stmt->fetch(PDO::FETCH_ASSOC)]);
            break;

        // Inserir tipo
        case '6':
            $stmt = $pdo->prepare("INSERT INTO {$_SESSION['BASE']}.tipo_equipamento (tipo_desc) VALUES (:descricao)");
            $stmt->execute([':descricao' => $tipo_desc]);
            echo json_encode(['success' => true, 'message' => 'Tipo incluído']);
            break;

        // Atualizar tipo
       case '7': // atualizar tipo
    $dados = json_decode($_POST['dados'] ?? '{}', true);
    $descricao = $dados['tipo_desc'] ?? '';
    $id = $dados['id'] ?? null; // <- pega do hidden

    if (!$id) throw new Exception('ID não informado');

    $stmt = $pdo->prepare("UPDATE {$_SESSION['BASE']}.tipo_equipamento SET tipo_desc=:descricao WHERE tipo_id=:id");
    $stmt->execute([
        ':descricao' => $descricao,
        ':id' => $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Tipo atualizado']);
    break;


        // Excluir tipo
        case '8':
            if (!$id) throw new Exception('ID não informado');
            $stmt = $pdo->prepare("DELETE FROM {$_SESSION['BASE']}.tipo_equipamento WHERE tipo_id=:id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Tipo excluído']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
