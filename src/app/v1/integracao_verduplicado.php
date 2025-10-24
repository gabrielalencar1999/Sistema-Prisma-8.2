<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");


$servidor = 'prisma-rds-master.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
$user_conect = 'admin';
$senha = '';
$banco_conect = 'bd_plustec';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
/*
$sql = 'SELECT CGC_CPF, COUNT(*) AS num_duplicados
FROM consumidor
GROUP BY CGC_CPF
HAVING COUNT(*) > 1;';
*/
$conteudo = "000.268.159-58;2
000.330.068-49;2
000.541.308-75;2
000.578.548-07;2
000.582.198-38;3
001.233.188-07;2
001.790.205-36;2
002.696.795-29;2
002.995.376-68;2
003.026.478-21;2
003.082.288-20;2
003.262.646-04;2
003.369.949-66;2
003.483.038-31;2
003.581.078-57;2
003677828-12;2
004.209.468-29;2
004.454.878-87;2
004.679.448-49;2
005.241.318-72;2
005.405.839-28;2
005.730.709-11;3
005185574-72;2
00527615/0001-70;2
006.081.458-66;2";


	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {
       //NCM,DESCRIÇÃO,DESCRIÇÃO CONCATENADA ,DATA INÍCIO,DATA FIM,EXEÇÃO,ATO LEGAL,NÚMERO,ANO 
       //0  ,1          2                           3            4   5       6         7    8
		$var = trim($linha[$i]);
		//$linhasNCM = explode(":", $var);	
		$linhas = explode(";", $var);	
	
        $DOCUMENTO =  str_replace(".","",$linhas[0]); //$value['Codigo']; 

		echo $DOCUMENTO."<br>";

		$sql = "SELECT CODIGO_CONSUMIDOR FROM consumidor WHERE CGC_CPF = '$DOCUMENTO'";
		echo $sql;
			$mov = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
			while($rowmov = mysqli_fetch_array($mov))									 
				{
                $_id = $rowmov['CODIGO_CONSUMIDOR'];

				echo "- $_id <Br>";
				//buscar dados telefone

				//buscar O.S

				//atualizar OS

				//inativar


		
				}


	}




		
	


