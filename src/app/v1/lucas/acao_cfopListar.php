<?php 

define("MAIN_DB_HOST", getenv('DB_HOST'));
define("MAIN_DB_USER", getenv('DB_USER'));
define("MAIN_DB_PASS", getenv('DB_PASSWORD'));
define('MAIN_DB_PORT', getenv('DB_PORT'));
define('MAIN_DB_SCHEMA', getenv('DB_NAME'));

$servidor = MAIN_DB_HOST;
$user_conect = MAIN_DB_USER;
$senha = MAIN_DB_PASS;
$banco_conect = "bd_novo";

$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}

// Pega os filtros do GET, usando os nomes que você tem no HTML (adaptar se precisar)
$descricao  = isset($_GET['descricao']) ? $_GET['descricao'] : '';
$cfop       = isset($_GET['cfop']) ? $_GET['cfop'] : '';
$tipoDoc    = isset($_GET['tipoDoc']) ? $_GET['tipoDoc'] : '';
$finalidade = isset($_GET['finalidade']) ? $_GET['finalidade'] : '';
$dataInicial= isset($_GET['dataInicial']) ? $_GET['dataInicial'] : '';
$dataFinal  = isset($_GET['dataFinal']) ? $_GET['dataFinal'] : '';

// Monta a query base e arrays para prepared statement
$sql = "SELECT * FROM cfop WHERE 1=1 ";
$params = [];
$types = ""; // Tipos dos parâmetros (s - string, i - int, etc)

// Monta condições conforme os filtros
if ($descricao !== '') {
    $sql .= " AND NAT_DESCRICAO LIKE ? ";
    $params[] = "%$descricao%";
    $types .= "s";
}

if ($cfop !== '') {
    $sql .= " AND NAT_CODIGO = ? ";
    $params[] = $cfop;
    $types .= "s";  // ou "i" se for inteiro
}

if ($tipoDoc !== '') {
    $sql .= " AND NAT_TIPODOCUMENTO = ? ";
    $params[] = $tipoDoc;
    $types .= "s";
}

if ($finalidade !== '') {
    $sql .= " AND NAT_FINALIDADE = ? ";
    $params[] = $finalidade;
    $types .= "s";
}

// Se sua tabela tiver uma coluna de data, coloque o filtro (troque NAT_DATA pelo nome correto)
if ($dataInicial !== '') {
    $sql .= " AND NAT_DATA >= ? ";
    $params[] = $dataInicial;
    $types .= "s"; // data como string
}
if ($dataFinal !== '') {
    $sql .= " AND NAT_DATA <= ? ";
    $params[] = $dataFinal;
    $types .= "s";
}

// Preparar e executar
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

if ($params) {
    // Bind dos parâmetros dinamicamente
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["NAT_DESCRICAO"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["NAT_CODIGO"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["NAT_TIPODOCUMENTO"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["NAT_FINALIDADE"]) . "</td>";
        echo "</tr>";
    }
} else {
    echo '<tr><td colspan="4" style="text-align:center;">Nenhum registro encontrado.</td></tr>';
}

$stmt->close();
$mysqli->close();
?>
