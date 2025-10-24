<?php 
include("../../api/config/conexaobase.php");


function LimpaVariavel($_texto)
{
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	$_texto =    str_replace("NULL", "", $_texto);
  return $_texto;
}

function LimpaVariavelDoc($valor){
    $valor = trim($valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


$servidor = 'naws.com';
$user_conect = 'admin';
$senha = '';
$banco_conect = '9029_maqservice';
$_SESSION['BASE'] = $banco_conect;
$_SESSION['CODIGOCLI'] = '9000';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690     
   

     $_sql = "select  NUMERO,Tipo_Pagamento_Entrada,Valor_Entrada from ".$_SESSION['BASE'].".saidaestoque where Valor_Entrada > 0";  
    
     $consultaMovRequisicao = "$_sql";
     $mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
     while($row = mysqli_fetch_array($mov))									 
         {
         $_venda =  $row['NUMERO'];
          
          $_sql = "select  financeiro_historico from ".$_SESSION['BASE'].".financeiro  where  Documento = '$_venda' and financeiro_historico like '%REF ENTRADA PED%'";                    
          $mov2 = mysqli_query($mysqli, $_sql) or die(mysqli_error($mysqli));
          $_count = mysqli_num_rows($mov2);
if($_count > 0){
         
          while($row2 = mysqli_fetch_array($mov2))									 
              {
              //  echo $row2['NUMERO']."<br>";
              }
            }else{
              echo $_venda ."<br>";

            }
         }

  

exit();
	
			