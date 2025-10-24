<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

if ($acao == 1) {
?>
<div class="modal-dialog text-center">
		<div class="modal-content">
			<div class="modal-header" style="border-bottom:0px; padding-bottom:2px;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="bg-icon pull-request">
				
					<h5 align="left">Digite o e-mail do colaborador</h5>
					<form action="javascript:void(0)" method="post" id="form_email" name="form_email">
						<div class="col-sm-8">
							<input type="email" name="emailColaborador" id="emailColaborador"  class="form-control" required>
						</div>
						<div class="col-sm-4">
							<button type="submit" class="btn btn-success btn-block">Enviar</button>
						</div>
					</form>
					<script>
						$('#form_email').submit(function (e){							
							$("#custom-modal-result").modal('show');
							$("#custom-modal-historico").modal('hide');
							$("#resultado-modal").html('<i class="fa fa-spinner fa-spin fa-2x"></i><br>Aguarde, enviando e-mail...');

							e.preventDefault();
							var $_keyid = "email_000001";
							var dados = $(this).serializeArray();
							dados = JSON.stringify(dados);
							$.post("page_return.php", {_keyform:$_keyid,dados:dados, acao:"email-colaborador"},
								function(result){
									$("#resultado-modal").html(result);
							});	

						});

					</script>
					<br><br>
				</div>
			</div>
		</div>
</div>
<?php
	exit();

}
if ($acao == 2) {

	
	$letra = $_POST['letra'];
	$alfabeto = array();

	if($_POST['tipo'] != ""){
		$filtro = "
			and usuario_NOME like '%".$_POST['nomeColaborador']."%' 	and usuario_perfil2 = '8'
			or colaborador_empresa = '".$_SESSION['BASE_ID']."' 
			and usuario_perfil2 = '8' 
			and usuario_LOGIN like '%".$_POST['nomeColaborador']."%'";
	}else{
		if($letra == ""){
			$filtro = "
				and usuario_NOME like 'a%' 	and usuario_perfil2 = '8'
				or colaborador_empresa = '".$_SESSION['BASE_ID']."' 
				and usuario_perfil2 = '8'
				and usuario_LOGIN like 'a%'";
            $letra = "a";
		}else{
			if($letra != "tudo"){
				$filtro = "
					and usuario_NOME like '$letra%' 	and usuario_perfil2 = '8'
					or colaborador_empresa = '".$_SESSION['BASE_ID']."' 
					and usuario_perfil2 = '8'
					and usuario_LOGIN like '$letra%'";
			}
		}
	}
	$masculino = array("0007.png", "0015.png", "0010.png", "0005.png", "0044.png", "0031.png","0020.png","0014.png");
	$feminino = array("0003.png", "0013.png", "0008.png", "0004.png", "0030.png", "0026.png","0025.png","0043.png","0036.png");
	$aleatório = array("0039.png","0044.png","0057.png");
	$cor = array("#FF7C0A", "#F5CE2D", "#5D9CEC", "#CA295D", "#F05050", "#FB6D9D","#2B2B2B","#A45BB9","#0067e6","#00B976");



    $sql="SELECT * FROM " . $_SESSION['BASE'] . ".usuario 
	inner join " . $_SESSION['BASE'] . ".colaborador on usuario_CODIGOUSUARIO=colaborador_usuario
	 WHERE 	 usuario_perfil2 = '8'and colaborador_empresa = '".$_SESSION['BASE_ID']."' $filtro order by usuario_NOME ASC";

    $statement = $pdo->query($sql);
    $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($retorno as $row) {


			if($row->usuario_avatar == ""){		
				if($row->usuario_sexo == 0){

					$rand = array_rand($masculino,2);
					$usuario_avatar = $masculino[$rand[0]];
				}
				if($row->usuario_sexo == 1){
					
					$rand = array_rand($feminino,2);
					$usuario_avatar = $feminino[$rand[0]];
				}
				if($row->usuario_sexo == 2){
					$rand = array_rand($masculino,8);
					$usuario_avatar = $masculino[$rand[0]];
					
				}				
			}else{
				$usuario_avatar = $row->usuario_avatar;
			}

			if($row->usuario_background == ""){

				$rand = array_rand($cor,2);
				$usuario_background = $cor[$rand[0]];

			}else{
				$usuario_background = $row->usuario_background;
			}

			//verifica status do aceite
			$sql2="select * from " . $_SESSION['BASE'] . ".colaborador where colaborador_aceite = '0'";
			$stm2 = $pdo->query($sql);
   			$retorno2 = $stm2->fetchAll(\PDO::FETCH_OBJ);
			   if($retorno2[0]->colaborador_aceite == 0){
					$status = "Enviado";
					$corSit = "#a7a7a7;";
			   }else{
					$status = "Ok";
					$corSit = "#81C868;";
			   }

			   if($retorno2[0]->colaborador_status == 0){
						$status_colaborador = "Inativo";
						$corSit_colaborador = "#4C5667;";
				}else{
						$status_colaborador = "Ativo";
						$corSit_colaborador = "#5D9CEC;";
				}

			//verifica se colaborador tem nome
			if($row->usuario_NOME != ""){
				$nomeColaborador = $row->usuario_NOME;
			}else{
				$nomeColaborador = '<span style="font-size:11px;">'.$row->usuario_LOGIN.'</span>';
			}

			$alfabeto[] = $nomeColaborador."|".$row->usuario_CODIGOUSUARIO."|".$usuario_avatar."|".$usuario_background."|".$status."|".$corSit."|".$status_colaborador."|".$corSit_colaborador;


			
			
		}
		if($statement->rowCount() == 0){
			if($_POST['nomeColaborador'] != ""){
				$letra = $_POST['nomeColaborador'];
			}
			if($letra == "tudo"){
				$letra = "";
			}
			$mensagem = '<h4>Desculpe não encontramos nenhum colaborador com <b>"'.$letra.'"</b></h4>';
			
		}
		?>
		
<!------A--------------------------------------------------------------------------------------------------------------------------------------->
		<div id="respond"><?php
            echo($mensagem);
			foreach($alfabeto as $valor) {
				$explode = explode("|",$valor);  ?>

            	<div class="col-sm-2 bbox1">
					<span class="label label-table lal2" style="background-color:<?=$explode[5];?>"><?=$explode[4];?></span>
					<span class="label label-table lal" style="background-color:<?=$explode[7];?>"><?=$explode[6];?></span>		
					<div class="imag">
						<div class="tam">
							<div class="circle-image" style="background-color:<?=$explode[3];?>;">
								<img src="assets/images/avatar/<?=$explode[2];?>" width="100px">
							</div>		
						</div>			
						<p class="categoria_title"><?=$explode[0];?></p>
					
						<div class="row" style="padding:15px;">
							<div class="col-sm-12">                             
								<button type="button" class="btn btn-success btn-block" onclick="_alterar('<?=$explode[1];?>')">Permissões</button>
							</div>
						</div>
					</div>
				</div> <?php
			} ?>
		</div>
		<script>
			function alf(letra){
				
				$("#abc_a").removeClass('abc-active');
				$("#abc_b").removeClass('abc-active');
				$("#abc_c").removeClass('abc-active');
				$("#abc_d").removeClass('abc-active');
				$("#abc_e").removeClass('abc-active');
				$("#abc_f").removeClass('abc-active');
				$("#abc_g").removeClass('abc-active');
				$("#abc_h").removeClass('abc-active');
				$("#abc_i").removeClass('abc-active');
				$("#abc_j").removeClass('abc-active');
				$("#abc_k").removeClass('abc-active');
				$("#abc_l").removeClass('abc-active');
				$("#abc_m").removeClass('abc-active');
				$("#abc_n").removeClass('abc-active');
				$("#abc_o").removeClass('abc-active');
				$("#abc_p").removeClass('abc-active');
				$("#abc_q").removeClass('abc-active');
				$("#abc_r").removeClass('abc-active');
				$("#abc_s").removeClass('abc-active');
				$("#abc_t").removeClass('abc-active');
				$("#abc_u").removeClass('abc-active');
				$("#abc_v").removeClass('abc-active');
				$("#abc_w").removeClass('abc-active');
				$("#abc_x").removeClass('abc-active');
				$("#abc_y").removeClass('abc-active');
				$("#abc_z").removeClass('abc-active');


				
				if(letra == 'a'){
					$("#abc_a").addClass('abc-active');
				}
				if(letra == 'b'){
					$("#abc_b").addClass('abc-active');
				}
				if(letra == 'c'){
					$("#abc_c").addClass('abc-active');
				}
				if(letra == 'd'){
					$("#abc_d").addClass('abc-active');
				}
				if(letra == 'e'){
					$("#abc_e").addClass('abc-active');
				}
				if(letra == 'f'){
					$("#abc_f").addClass('abc-active');
				}
				if(letra == 'g'){
					$("#abc_g").addClass('abc-active');
				}
				if(letra == 'h'){
					$("#abc_h").addClass('abc-active');
				}
				if(letra == 'i'){
					$("#abc_i").addClass('abc-active');
				}
				if(letra == 'j'){
					$("#abc_j").addClass('abc-active');
				}
				if(letra == 'k'){
					$("#abc_k").addClass('abc-active');
				}
				if(letra == 'l'){
					$("#abc_l").addClass('abc-active');
				}
				if(letra == 'm'){
					$("#abc_m").addClass('abc-active');
				}
				if(letra == 'n'){
					$("#abc_n").addClass('abc-active');
				}
				if(letra == 'o'){
					$("#abc_o").addClass('abc-active');
				}
				if(letra == 'p'){
					$("#abc_p").addClass('abc-active');
				}
				if(letra == 'q'){
					$("#abc_q").addClass('abc-active');
				}
				if(letra == 'r'){
					$("#abc_r").addClass('abc-active');
				}
				if(letra == 's'){
					$("#abc_s").addClass('abc-active');
				}
				if(letra == 't'){
					$("#abc_t").addClass('abc-active');
				}
				if(letra == 'u'){
					$("#abc_u").addClass('abc-active');
				}
				if(letra == 'v'){
					$("#abc_v").addClass('abc-active');
				}
				if(letra == 'w'){
					$("#abc_w").addClass('abc-active');
				}
				if(letra == 'x'){
					$("#abc_x").addClass('abc-active');
				}
				if(letra == 'y'){
					$("#abc_y").addClass('abc-active');
				}
				if(letra == 'z'){
					$("#abc_z").addClass('abc-active');
				}
				busca(letra,"");



			}

			function exe(tipo){

				$("#nomeConsumidor").val('');

				if(tipo == '0'){
					$("#vertudo").css("display","none");
					$("#escondertudo").css("display","");
					busca("tudo","");
				}
				if(tipo == '1'){
					$("#vertudo").css("display","");
					$("#escondertudo").css("display","none");
					alf("a");
				}
			}

			function busca(letra,tipo){
				var $_keyid = "colaboradores_00002";

				if(tipo == "2"){
					var nomeColaborador = $("#nomeColaborador").val();
					alert(nomeColaborador);
					if(nomeColaborador == ""){
						alf("a");
					}else{
						$.post("page_return.php", {_keyform:$_keyid, acao:"2", letra:letra, tipo:tipo , nomeColaborador:nomeColaborador}, function(result){	
							alert(result);
							if(result == 1){ }else{
								$("#respond").html(result);
							}
						});					
					}

				}else{
					$.post("page_return.php", {_keyform:$_keyid, acao:"2", letra:letra, tipo:tipo , nomeColaborador:nomeColaborador}, function(result){	
						if(result == 1){ }else{
							$("#respond").html(result);
						}
					});	
				}

				
			

			}
		</script>

<?php }
if($acao == 3){
	$id = $_POST['user'];

	//altera status do colaborador
	$sql="update " . $_SESSION['BASE'] . ".colaborador set colaborador_status = '$variable' where colaborador_usuario = '$id' and colaborador_empresa = '".$_SESSION['BASE_ID']."'";
	$stm = $pdo->prepare($sql);
	$stm->execute();

	?>
		<div class="modal-dialog text-center">
			<div class="modal-content">
				<div class="modal-body">
					<div class="bg-icon pull-request">
						<img src="assets/images/small/img_0004.jpg" alt="image" class="img-responsive center-block" width="200"/>
						<h2>Status alterado com sucesso!</h2>
						<button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
					</div>
				</div>
			</div>
		</div>
	<?php
}
