<?php

namespace Database;

class MySQL {
	/**
	 * @var \PDO
	 */
	private static $conexao = array();
	
	/**
	 * Cria uma conexão MySQL (implementa o pattern Singleton)
	 * @return \PDO
	 * @throws \PDOException
	 */
	 public static function acessabd(){
		
		if(MAIN_DB_HOST == ""){
		define('MAIN_DB_HOST', getenv('DB_HOST'));
		define('MAIN_DB_PORT', getenv('DB_PORT'));
		define('MAIN_DB_SCHEMA', getenv('DB_NAME'));
		define('MAIN_DB_USER', getenv('DB_USER'));
		define('MAIN_DB_PASS', getenv('DB_PASSWORD'));
	}
	
	 	preg_match('/\w+$/', __METHOD__, $matches);
	 	$_indice = current($matches);
	 	if ( ! isset(self::$conexao[$_indice]) ){
			self::$conexao[$_indice] = new \PDO(
				'mysql:host='.MAIN_DB_HOST.';port='.MAIN_DB_PORT.';dbname='.MAIN_DB_SCHEMA,
				MAIN_DB_USER,
				MAIN_DB_PASS,
				array(
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
					\PDO::ATTR_TIMEOUT => 10
				)
			);
		}

	/*
			self::$conexao[$_indice] = new \PDO(
				'mysql:host=prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com;port=3306;dbname='."bd_tecfast",
				"admin",
				"",
				array(
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
					\PDO::ATTR_TIMEOUT => 10
				)
			);
		*/
		
		return self::$conexao[$_indice];
	}
}

?>
