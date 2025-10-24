<?php 
session_start();

$servidor = 'm';
$user_conect = 'admin';
$senha = '';
$banco_conect = 'bd_morumbi';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690


$consultaMovRequisicao = "SELECT * FROM bd_morumbi.usuario where usuario_ATIVO = 'Sim'  and usuario_CODIGOUSUARIO > 10 and usuario_CODIGOUSUARIO = '249' and usuario_CODIGOUSUARIO = '249'";
$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
while($rowmov = mysqli_fetch_array($mov))									 
    {
    
      $_perfil = $rowmov['usuario_PERFIL'];
      $_CODIGO = trim($rowmov['usuario_CODIGOUSUARIO']);

      if($_perfil == 9) { // tecnico//
        $sql = "INSERT INTO bd_morumbi.telas_acesso (tela_descricao,tela_user) SELECT tela_descricao,'$_CODIGO' FROM bd_morumbi.telas_acesso where tela_user = '249'";
        echo $sql;
        $mov = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        
      }else {
        $sql = "INSERT INTO bd_morumbi.telas_acesso (tela_descricao,tela_user) SELECT tela_descricao,'$_CODIGO' FROM bd_morumbi.telas_acesso where tela_user = '293'  ";
        echo $sql;
        $mov = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

      }
    }

	
				
?>	



		
	


