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
    die("Erro na conexão: " . $mysqli->connect_error);
}



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
    die("Erro na conexão: " . $mysqli->connect_error);
}
 
<?php 

// Definições do banco
define("MAIN_DB_HOST", getenv('DB_HOST'));
define("MAIN_DB_USER", getenv('DB_USER'));
define("MAIN_DB_PASS", getenv('DB_PASSWORD'));
define('MAIN_DB_PORT', getenv('DB_PORT'));
define('MAIN_DB_SCHEMA', getenv('DB_NAME'));

$servidor = MAIN_DB_HOST;
$user_conect = MAIN_DB_USER;
$senha = MAIN_DB_PASS;
$banco_conect = "bd_novo";

// Conexão com o banco
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);

// Verifica erro de conexão
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

?>