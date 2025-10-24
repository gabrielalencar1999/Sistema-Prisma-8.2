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

$json = $_POST['dados'];

$acao  = $_POST['acao'];



$data = json_decode($json, true);

foreach ($data as $item) {
    ${$item['name']} = $item['value'];
}



if($acao == 0) { //lista dados para select



// Pega os filtros do GET, usando os nomes que você tem no HTML (adaptar se precisar)
$descricao  = isset($descricaoPesquisa) ? $descricaoPesquisa : '';

// Monta condições conforme os filtros
if ($descricao != '') {
   $filtro = " WHERE NAT_DESCRICAO LIKE '%".$descricao."%'";

}

$sql = "SELECT * FROM cfop  $filtro ";
echo $sql;

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $mysqli->error);
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

}


if($acao == 1){ //faz insert

    print_r($_POST['dados']);

    echo "INCLUIR REGISTRO INSERT MONTAR SQL";

    $nometeste = $nome;

    echo $nometeste;

}
?>
