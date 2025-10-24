<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['arquivo-foto'])) {
    $arquivo_temp = $_FILES['arquivo-foto']['tmp_name'];  // CAMINHO TEMPORÁRIO
    $arquivo_name = $_FILES['arquivo-foto']['name'];      // NOME DO ARQUIVO
    $arquivo_size = $_FILES['arquivo-foto']['size'];      // TAMANHO DO ARQUIVO
    $arquivo_type = $_FILES['arquivo-foto']['type'];      // TIPO DO ARQUIVO

        // Definir limites para as dimensões e tamanho do arquivo
   
    $max_width = 320;  // Largura máxima em pixels
    $max_height = 320; // Altura máxima em pixels
    $max_size = 2 * 320 * 320; // Tamanho máximo do arquivo em bytes (2MB)

    // Verificar se o arquivo é uma imagem JPEG
    if ($arquivo_type == 'image/jpeg' || $arquivo_type == 'image/jpg') {
        // Obter as dimensões da imagem
        list($width, $height) = getimagesize($arquivo_temp);

        // Verificar se as dimensões e tamanho do arquivo são válidos
        if ($width <= $max_width && $height <= $max_height && $file_size <= $max_size) {
            // A imagem é válida; processar o upload ou salvar o arquivo
        
        } else {
            echo "A imagem não atende aos requisitos de tamanho.";
            exit();
        }
    } else {
        echo "O arquivo não é uma imagem JPEG válida.";
        exit();
    }

    // Verifica se o arquivo foi enviado sem erros
    if (is_uploaded_file($arquivo_temp)) {
        // Lê o conteúdo do arquivo
        $arquivo_conteudo = file_get_contents($arquivo_temp);

        // Converte o conteúdo do arquivo para base64
        $arquivo_base64 = base64_encode($arquivo_conteudo);

        // Exibe a string base64 (pode ser usado em uma tag <img> ou salvo em um banco de dados)
        echo "data:$arquivo_type;base64,$arquivo_base64";
    } else {
        echo "Erro ao enviar o arquivo.";
    }
} else {
    echo "Nenhum arquivo foi enviado.";
}
?>
