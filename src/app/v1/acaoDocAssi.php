<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;

date_default_timezone_set('America/Sao_Paulo');

$pdo = MySQL::acessabd();

// parametros basicos
$acao = isset($_POST['acao']) ? (int)$_POST['acao'] : 0;
$imgBase64 = isset($_POST['imgBase64']) ? $_POST['imgBase64'] : '';
$_OS = isset($_POST['_idos']) ? $_POST['_idos'] : '';

if (empty($_OS)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'ERROR', 'message' => 'OS nao informada']);
    exit;
}

if (empty($_SESSION['BASE']) || empty($_SESSION['CODIGOCLI'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'ERROR', 'message' => 'Sessao invalida']);
    exit;
}

$clienteId = $_SESSION['CODIGOCLI'];
$baseDir = "../docs/{$clienteId}/";
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true);
}

// lista as assinaturas com atualização pra exclusão da imagem 
if ($acao === 2) {
    try {
        $sql = "SELECT * FROM " . $_SESSION['BASE'] . ".foto WHERE arquivo_OS = :os AND arquivo_assi IS NOT NULL AND arquivo_assi <> '' ORDER BY arquivo_id DESC";
        $stm = $pdo->prepare($sql);
        $stm->execute([':os' => $_OS]);
        $html = '';
        while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
            $src = '';
            if (!empty($row['arquivo_assi'])) {
                $src = $row['arquivo_assi'];
            } elseif (!empty($row['arquivo_imagem'])) {
                $src = $row['arquivo_imagem'];
            }
            if ($src !== '') {
                $idref = isset($row['arquivo_id']) ? $row['arquivo_id'] : '';
                $osref = isset($row['arquivo_OS']) ? $row['arquivo_OS'] : '';
                $html .= '<img src="' . $src . '" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfoto(\'' . $osref . '\',\'' . $idref . '\')">';
            }
        }
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
        exit;
    }
}






if ($acao === 1) {
    header('Content-Type: application/json');



    // valida o CPF inline usando a função do Validador.php (não deu para puxar a função do Validador.php no arquivo acaoDocAssi.php)
    function validaCPF($cpf) {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
    
    // lidar com o CPF se fornecido
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    
    // sanitiza aqui também para garantir que não tem caracteres especiais e nem tags HTML
    $cpf = preg_replace('/\D/', '', $cpf); // remove tudo que não é dígito
    $nome = strip_tags($nome); // remove tags HTML
    $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); // escapa caracteres especiais
    $nome = trim($nome); // remove espaços extras
    
    // valida o tamanho do nome
    if (strlen($nome) > 255) {
        $nome = substr($nome, 0, 255);
    }
    
    if (!empty($cpf) && !validaCPF($cpf)) {
        http_response_code(422);
        echo json_encode(['status' => 'ERROR', 'message' => 'CPF inválido']);
        exit;
    }
    
    if ($imgBase64 === '') {
        http_response_code(400);
        echo json_encode(['status' => 'ERROR', 'message' => 'Imagem nao informada']);
        exit;
    }

    // limpar prefixo dataURL e decodificar (assumindo PNG enviado pelo front)
    if (strpos($imgBase64, 'data:image/') === 0) {
        $imgBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $imgBase64);
    }
    $bin = base64_decode($imgBase64);
    if ($bin === false) {
        http_response_code(400);
        echo json_encode(['status' => 'ERROR', 'message' => 'Imagem invalida']);
        exit;
    }

    // salvar arquivo
    $agora = date('Y-m-d H:i:s');
    $ext = 'png';
    $nomeAssi = $clienteId . '_' . date('Y-m-d-His') . '_assi.' . $ext;
    $caminhoAssi = $baseDir . $nomeAssi;
    if (file_put_contents($caminhoAssi, $bin) === false) {
        http_response_code(500);
        echo json_encode(['status' => 'ERROR', 'message' => 'Falha ao salvar arquivo']);
        exit;
    }
    $sizeAssi = @filesize($caminhoAssi) ?: 0;
    $tipoAssi = $ext;

    try {
        // sempre inserir nova row para cada assinatura enviada
        $sqlIns = "INSERT INTO " . $_SESSION['BASE'] . ".foto
            (arquivo_data, arquivo_OS, arquivo_imagem, arquivo_tipo, arquivo_size, arquivo_nome,
             arquivo_assi, arquivo_tipoAssi, arquivo_sizeAssi, arquivo_nomeAssi, cpf_assi, nome_assi)
            VALUES (:data, :os, :img, :tipo, :size, :nome, :assi, :tipoAssi, :sizeAssi, :nomeAssi, :cpf, :nomeCli)";
        $stmIns = $pdo->prepare($sqlIns);
        $stmIns->execute([
            ':data' => $agora,
            ':os' => $_OS,
            ':img' => '',
            ':tipo' => '',
            ':size' => null,
            ':nome' => null,
            ':assi' => $caminhoAssi,
            ':tipoAssi' => $tipoAssi,
            ':sizeAssi' => $sizeAssi,
            ':nomeAssi' => $nomeAssi,
            ':cpf' => $cpf,
            ':nomeCli' => $nome,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
        exit;
    }
    // Após salvar com sucesso, segue para listagem e retorno de HTML (abaixo)
}

try {
    $sql = "SELECT * FROM " . $_SESSION['BASE'] . ".foto WHERE arquivo_OS = :os AND arquivo_assi IS NOT NULL AND arquivo_assi <> '' ORDER BY arquivo_id DESC";
    $stm = $pdo->prepare($sql);
    $stm->execute([':os' => $_OS]);
    $html = '';
    while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
        $src = '';
        if (!empty($row['arquivo_assi'])) {
            $src = $row['arquivo_assi'];
        } elseif (!empty($row['arquivo_imagem'])) {
            $src = $row['arquivo_imagem'];
        }
        if ($src !== '') {
            $idref = isset($row['arquivo_id']) ? $row['arquivo_id'] : '';
            $osref = isset($row['arquivo_OS']) ? $row['arquivo_OS'] : '';
            $html .= '<img src="' . $src . '" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfoto(\'' . $osref . '\',\'' . $idref . '\')">';
        }
    }
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    exit;
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
    exit;
}

?>