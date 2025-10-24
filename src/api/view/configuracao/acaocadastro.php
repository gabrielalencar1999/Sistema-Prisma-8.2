<?php

use Database\MySQL;
use Functions\Configuracoes;
use Functions\Validador;

$acao = isset($_POST['acao']) ? $_POST['acao'] : null;
$_parametros = isset($_POST['dados']) ? json_decode($_POST['dados'], true) : [];

// Se for array de objetos com name/value, transforma em array associativo
if (isset($_parametros[0]) && isset($_parametros[0]['name']) && isset($_parametros[0]['value'])) {
    $tmp = [];
    foreach ($_parametros as $item) {
        $tmp[$item['name']] = $item['value'];
    }
    $_parametros = $tmp;
}

if (isset($_parametros)) {
    $_parametros = Validador::sanitizeArrayRecursive($_parametros);
}

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

// Evita notices
$certificado = isset($_FILES['arquivo-certificado']) ? $_FILES['arquivo-certificado'] : '';
$certificadoSenha = isset($_POST['senha_certificado']) ? $_POST['senha_certificado'] : '';
$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : '';

// certificado
if($certificado != ""){
    $certificado = !empty($certificado) ? base64_encode(file_get_contents($certificado["tmp_name"])) : '';
    $certificado = Validador::sanitizaValor($certificado, '');

    $statement = $pdo->prepare("UPDATE ".$_SESSION['BASE'] .".empresa SET
    arquivo_certificado_base64 = ?
    WHERE empresa_id = ?");
    $statement->bindParam(1, $certificado);
    $statement->bindParam(2, $empresa);
    $statement->execute();
}

// SENHA certificado
if($certificadoSenha != ""){
    $certificadoSenha = Validador::sanitizaValor($certificadoSenha, '');
    $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".empresa SET
    senha_certificado = ?
    WHERE empresa_id = ?");
    $statement->bindParam(1, $certificadoSenha);
    $statement->bindParam(2, $empresa);
    $statement->execute();
}


if ($acao == 2) {
    $sql = "UPDATE ".$_SESSION['BASE'].".parametro SET
        RAZAO_SOCIAL = :razao_social,
        NOME_FANTASIA = :nome_fantasia,
        CGC = :cnpj,
        Cep = :cep,
        ENDERECO = :endereco,
        NumRua = :numero,
        Complemento_Endereco = :complemento,
        BAIRRO = :bairro,
        CIDADE = :cidade,
        UF = :uf,
        DDD = :ddd,
        TELEFONE = :telefone,
        EMAIL = :email,
        INSC_ESTADUAL = :insc_estadual
        WHERE id = 9000";
    $statement = $pdo->prepare($sql);

    $razao_social = $_parametros['razao'] ?? '';
    $nome_fantasia = $_parametros['fantasia'] ?? '';
    $cnpj = isset($_parametros['cnpj']) ? str_replace(['.', '-', '/'], '', $_parametros['cnpj']) : '';
    $cep = isset($_parametros['cep']) ? str_replace('-', '', $_parametros['cep']) : '';
    $endereco = $_parametros['rua'] ?? '';
    $numero = $_parametros['numero'] ?? '';
    $complemento = $_parametros['complemento'] ?? '';
    $bairro = $_parametros['bairro'] ?? '';
    $cidade = $_parametros['cidade'] ?? '';
    $uf = $_parametros['uf'] ?? '';
    $ddd = isset($_parametros['telefone']) ? substr(preg_replace('/[^0-9]/', '', $_parametros['telefone']), 0, 2) : '';
    $telefone = isset($_parametros['telefone']) ? substr(preg_replace('/[^0-9]/', '', $_parametros['telefone']), 2) : '';
    $email = $_parametros['email'] ?? '';
    $insc_estadual = $_parametros['inscricao-estadual'] ?? '';

    $statement->bindParam(':razao_social', $razao_social);
    $statement->bindParam(':nome_fantasia', $nome_fantasia);
    $statement->bindParam(':cnpj', $cnpj);
    $statement->bindParam(':cep', $cep);
    $statement->bindParam(':endereco', $endereco);
    $statement->bindParam(':numero', $numero);
    $statement->bindParam(':complemento', $complemento);
    $statement->bindParam(':bairro', $bairro);
    $statement->bindParam(':cidade', $cidade);
    $statement->bindParam(':uf', $uf);
    $statement->bindParam(':ddd', $ddd);
    $statement->bindParam(':telefone', $telefone);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':insc_estadual', $insc_estadual);

    $statement->execute();


}
