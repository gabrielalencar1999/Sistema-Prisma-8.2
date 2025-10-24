<?php

# ------------------------------------------------
# AMBIENTE
# ------------------------------------------------
/*
const SYSTEM = '';

# ------------------------------------------------
# CAMINHOS
# ------------------------------------------------

define('FILE_BASE_PATH', '');
define('FILE_BASE_PUB', FILE_BASE_PATH);
define('FILE_BASE_API', FILE_BASE_PATH . '/api');
define('FILE_BASE_SYS', FILE_BASE_PUB . '/' . SYSTEM);
*/
# ------------------------------------------------
# BANCO DE DADOS
# ------------------------------------------------

define("MAIN_DB_HOST", getenv('DB_HOST'));
define("MAIN_DB_USER", getenv('DB_USER'));;
define("MAIN_DB_PASS", getenv('DB_PASSWORD'));
define('MAIN_DB_PORT', getenv('DB_PORT'));
define('MAIN_DB_SCHEMA', getenv('DB_NAME'));