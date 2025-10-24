<?php

		session_start();
		
		require_once('../../../api/config/config.inc.php');
		require FILE_BASE_API . '/vendor/autoload.php';

		USE Database\MySQL;
		$pdo = MySQL::acessabd();
		
		//desconecta caixa ativo
		$sql="update ".$_SESSION['BASE'].".livro_caixa_numero set Ind_Sel = '0' where Livro_Numero = '".$_SESSION['id_caixa']."'";
		$stm = $pdo->prepare($sql);
		$stm->execute();

		session_destroy();

		?>
		<script>
				location.href="http://gestorpet.com.br/app/";
		</script>

