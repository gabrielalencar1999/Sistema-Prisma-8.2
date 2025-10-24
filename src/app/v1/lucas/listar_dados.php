<?php
include 'conexao.php';

$sql = "SELECT nat_codigo, descricao, cfop, tipo_documento, finalidade FROM ordens_servico";
$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        var_dump($row);
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cfop']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tipo_documento']) . "</td>";
        echo "<td>" . htmlspecialchars($row['finalidade']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' class='empty-message'>Nenhum registro encontrado.</td></tr>";
}
?>
