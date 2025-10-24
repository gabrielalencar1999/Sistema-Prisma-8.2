<?php
namespace Functions;

use stdClass;
use Database\MySQL;

class Estoque {

    public static function gravarAlteracaoPrecoCadastro(array $params){
	
		$response = "";

		date_default_timezone_set('America/Sao_Paulo');

        try{
			$pdo = MySQL::acessabd();	

			$dataAtual = date('Y-m-d');
			$data = date("Y-m-d-His");

			$dia       = date('d');
			$mes       = date('m');
			$ano       = date('Y');
			$hora = date("H:i:s");

			$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

			$codigoItem = $params['codigoitem'];
			$tipo =  $params['tipo'];;//cadastro
			$login = $params['login']; // exemplo
			$loginnome = $params['loginnome'];
			$vlrcustoatual = $params['vlrcustoatual'];
			$vendaAtual = $params['vlrvendaatual'];
			$vendaAnterior = $params['vendaAnterior'];
			$custoAnterior = $params['custoAnterior'];

			$vlrcustoTab1 = $params['vlrcustoTab1'];       
			$vlrcustoTab2 = $params['vlrcustoTab2'];
			$vlrcustoTab3 = $params['vlrcustoTab3']; 
			$vlrcustoTab4 = $params['vlrcustoTab4'];
			$vlrcustoAnTab1 = $params['vlrcustoAnTab1'];
			$vlrcustoAnTab2 = $params['vlrcustoAnTab2'];
			$vlrcustoAnTab3 = $params['vlrcustoAnTab3'];
			$vlrcustoAnTab4 = $params['vlrcustoAnTab4'];
								
		
						
				// Verifica se já existe um registro hoje com o mesmo login
				$sql = "
					SELECT ipc_id 
					FROM {$_SESSION['BASE']}.itemestoque_preco 
					WHERE DATE(ipc_data) = CURDATE() AND ipc_login = ? and ipc_codigoitem =  ? AND ipc_tipo = ? 
					AND ipc_vlrcustoanterior = '$custoAnterior' AND ipc_vlrvendaanterior = '$vendaAnterior'
					AND  ipc_vlrcustoatual = '$vlrcustoatual' AND ipc_vlrvendaatual = '$vendaAtual'
					LIMIT 1
				";
				
				$stm = $pdo->prepare($sql);
				$stm->bindParam(1, $login, \PDO::PARAM_INT);
				$stm->bindParam(2, $codigoItem);
				$stm->bindParam(3, $tipo, \PDO::PARAM_INT);
				$stm->execute();

				if ($stm->rowCount() > 0) {
					// UPDATE
					$registro = $stm->fetch(\PDO::FETCH_OBJ);
		
					$sqlUpdate = "
						UPDATE {$_SESSION['BASE']}.itemestoque_preco
						SET 
							ipc_datahora = NOW(),
							ipc_codigoitem = ?,
							ipc_tipo = ?,
							ipc_vlrcustoatual = ?,
							ipc_vlrcustoanterior = ?,
							ipc_vlrvendaatual = ?,
							ipc_vlrvendaanterior = ?,
							ipc_vlrcustoTab1 = ?,
							ipc_vlrcustoAnTab1 = ?,
							ipc_vlrcustoTab2 = ?,
							ipc_vlrcustoAnTab2 = ?,
							ipc_vlrcustoTab3 = ?,
							ipc_vlrcustoAnTab3 = ?,
							ipc_vlrcustoTab4 = ?,
							ipc_vlrcustoAnTab4 = ?
						WHERE ipc_id = ?
					";
					$stm = $pdo->prepare($sqlUpdate);
					$stm->execute([
						$codigoItem,
						$tipo,
						$vlrcustoatual,
						$custoAnterior,
						$vendaAtual,
						$vendaAnterior,
						$vlrcustoTab1,
						$vlrcustoAnTab1,
						$vlrcustoTab2,
						$vlrcustoAnTab2,
						$vlrcustoTab3,
						$vlrcustoAnTab3,
						$vlrcustoTab4,
						$vlrcustoAnTab4,
						$registro->ipc_id
					]);
		
				} else {
					// INSERT
					$sqlInsert = "
						INSERT INTO {$_SESSION['BASE']}.itemestoque_preco (
							ipc_data,
							ipc_id,
							ipc_datahora,
							ipc_login,
							ipc_loginuser,
							ipc_codigoitem,
							ipc_tipo,
							ipc_vlrcustoatual,
							ipc_vlrcustoanterior,
							ipc_vlrvendaatual,
							ipc_vlrvendaanterior,
							ipc_vlrcustoTab1,
							ipc_vlrcustoAnTab1,
							ipc_vlrcustoTab2,
							ipc_vlrcustoAnTab2,
							ipc_vlrcustoTab3,
							ipc_vlrcustoAnTab3,
							ipc_vlrcustoTab4,
							ipc_vlrcustoAnTab4
						) VALUES (
							'$dataAtual', NULL, '$data_hora', ?, ?, ?,?, ?, ?, ?, ?,
							?, ?, ?, ?, ?, ?, ?, ?
						)
					";
					$stm = $pdo->prepare($sqlInsert);
					$stm->execute([
						$login,
						$loginnome,
						$codigoItem,
						$tipo,
						$vlrcustoatual,
						$custoAnterior,
						$vendaAtual,
						$vendaAnterior,
						$vlrcustoTab1,
						$vlrcustoAnTab1,
						$vlrcustoTab2,
						$vlrcustoAnTab2,
						$vlrcustoTab3,
						$vlrcustoAnTab3,
						$vlrcustoTab4,
						$vlrcustoAnTab4
					]);
				}

        }
        catch (\Exception $fault){
            $response = $fault;
        }
        return $response;
    }  

    
}