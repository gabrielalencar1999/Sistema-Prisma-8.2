<?php
	session_start();
	
	require_once('../../../api/config/config.inc.php');
	require FILE_BASE_API . '/vendor/autoload.php';

	use Database\MySQL;

	$pdo = MySQL::acessabd();
	


?>

    <head>
		<title>Dvet | PDV</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="icon" href="img/caixaIcon2.png" type="image/png">
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="../assets/css/icons.css" rel="stylesheet">
		<meta http-equiv="cache-control" content="no-cache" />
		<?=$style;?>
        <style>
            @font-face {
            font-family: roboto black;
            src: url('../fonts/roboto-black-webfont.woff');
            }
        </style>
	</head>
    <body>
        <div class="container-fluid" style="padding:6%;">
            <div class="col-sm-6">
                <h1 style="font-size:42px; font-family: roboto black; font-weight:bold; color:#407bff;">Oops! Todos os caixas estão ativos.</h1>
                <h4>Escolha um caixa abaixo para forçar a conexão a ele. Após selecionar, o outro usuário conectado ao caixa será desconectado automaticamente.</h4>
                <div class="row">
                    <div class="col-xs-12" style="background-color:#FFF; width:100%; min-height:500px; border-radius:12px; margin-top:2%">
                        <table class="table">
                            <tr>
                                <th></th>
                                <th>CAIXA (PDV)</th>
                                <th>USUÁRIO</th>
                                <th>ÚLTIMA OPERAÇÃO</th>
                            </tr>
                            <?php
                                $sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero";
                                $stm = $pdo->prepare($sql);
                                $stm->execute();
                                foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){

                                    //busca ultima transacao
                                    $sql2 = "select *,DATE_FORMAT(Livro_caixa_data_lancamento, '%d/%m/%Y ás %H:%i') as horario from ".$_SESSION['BASE'].".livro_caixa where Livro_Numero = '".$value['Livro_Numero']."' order by Livro_caixa_seq_lancamento DESC LIMIT 1";
                                    //echo($sql2);
                                    $stm2 = $pdo->prepare($sql2);
                                    $stm2->execute();
                                    foreach($stm2->fetchAll(PDO::FETCH_ASSOC) as $value2){                               
                                        $usuario = $value2['Livro_caixa_usuariio_alterado'];
                                        $horario = $value2['horario'];
                                    }
                                    ?>
                                        <tr>
                                            <td><input type="checkbox" class="form-control input-sm" id="check" name="check" onclick="escolher('<?=$value['Livro_Numero'];?>')"></td>
                                            <td><?=$value['Descricao'];?></td>
                                            <td><?=$usuario;?></td>
                                            <td><?=$horario;?></td>
                                        </tr>
                                    <?php
                                }
                                
                            ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12" align="center">
                        <button id="fechar" type="button" class="btn btn-primary" style="width:20%; margin-top:25px;" onclick="fechar()"> Cancelar</button>
                    </div>
                </div>
                <h4 align="center">Precisa de mais caixas disponíveis? <a data-toggle="modal" data-target="#myModal">Clique aqui para descobrir como criar mais.</a></h4>
            </div>
            <div class="col-sm-6" style="padding-left:5%">
                <img src="img/caixa-ocupado.png" width="80%;"  />
            </div>
        </div>
        	
		<!-- MODAL -->
		<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <br>
                <br>
                <?php 
                    $sql="select * from " . $_SESSION['BASE'] . ".salavirtual where id_sv = '5'";
                    $stm = $pdo->prepare($sql);
                    $stm->execute();
                    foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $value){                               
                        echo($value['texto_sv']);
                    }
                ?>
				
			  </div>
			</div>
		  </div>
		</div>
    </body>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        function escolher(id){
            $.post('acaoCaixa.php',{acao:'seleciona_caixa', id:id}, function(resp){
               location.reload();
            });
        }
        function fechar(){
            window.close();
        }
    </script>