<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");
use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data_atual      = $ano . "-" . $mes . "-" . $dia;
$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

function remove($_texto)
{
	$_texto =    str_replace(")", "", $_texto);
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	return $_texto;
}


function mascara($_texto, $_tipo)
{
	$_texto =    str_replace(")", "", $_texto);
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);

	if ($_tipo == "telefone") {
		if (strlen($_texto) > 10) {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 5) . "-" . substr($_texto, 7, 4);
		} else {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 4) . "-" . substr($_texto, 6, 4);
		}
	}

	return $_texto;
}

$_acao = $_POST["acao"];

if ($acao == 1) {

	//DADOS CLIENTES
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["cpfcnpj"];

	//se informou cpf/cnpj, busca para ver se nao está duplicado

	if ($cpfcnpj != "") {
		//verifica se cpf/cnpj é existente
		$sql = "select * from " . $_SESSION['BASE'] . ".consumidor where CGC_CPF = ? AND Tipo_Pessoa <> '2'";
		$stm = $pdo->prepare($sql);
		$stm->bindParam(1, $cpfcnpj, \PDO::PARAM_STR);
		$stm->execute();
		if ($stm->rowCount() > 0) {
?>
			<div class="modal-dialog text-center">
				<div class="modal-content">
					<div class="modal-body">
						<div class="bg-icon pull-request">
							<img src="assets/images/small/security.png" alt="image" class="img-responsive center-block" width="400" />
							<i class="fa fa-5x  fa-times-circle-o"></i>
							<h2>CPF/CNPJ já existente!</h2>
							<button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
		<?php
			exit();
		}
	} else {
		if ($nome == "") {
		?>
			<div class="modal-dialog text-center">
				<div class="modal-content">
					<div class="modal-body">
						<div class="bg-icon pull-request">

							<i class="fa fa-5x  fa-times-circle-o"></i>
							<h2>Nome não pode estar em branco</h2>
							<button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
		<?php
			exit();
		}
	}
	echo 1;
}

if ($acao == 2) {

	//DADOS CLIENTES
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["_cpfcnpj"];
	$rgie = $_parametros["_rgie"];
	$tipo = $_parametros['_tipopessoa'];
	$tipocliente = $_parametros['_tipopcliente'];
	$nascimento = $_parametros["_dtnacimento"];
	if ($nascimento == "") {
		$nascimento = "0000-00-00";
	}

	//ENDERECO
	$cep = $_parametros["_cep"];
	$endereco = $_parametros["_endereco"];
	$cidade = $_parametros["_cidade"];
	$bairro = $_parametros["_bairro"];
	$uf = $_parametros["_estado"];
	$numerocasa = $_parametros["_numendereco"];
	$complemento = $_parametros["_complemento"];
	$_comentario  = $_parametros["_obs"];
	$proximidade  = $_parametros["_proximidade"];

		
	$_codregiao = $_parametros["_codregiao"];
	$tecnico_e = $_parametros["tecnico_e"];

	//contato
	$email = $_parametros["_email"];

	$celular_completo = $_parametros["_fonecelular"];
	$dddCelular = substr($celular_completo, 1, 2);
	$celular = remove($celular_completo);
	$celular= substr($celular, 2, 10);

	$celular_completo2 = $_parametros["_fonecelular2"];
	$dddCelular2 = substr($celular_completo2, 1, 2);
	$celular2 = remove($celular_completo2);
	$celular2= substr($celular2, 2, 10);


	$fixo_completo = $_parametros["_fonefixo"];
	$dddFixo = substr($fixo_completo, 1, 2);
	$fixo = remove($fixo_completo);
	$fixo= substr($fixo, 2, 10);

	$id_celularwats  = $_parametros["id_celularwats"];
	$id_celular2wats = $_parametros["id_celular2wats"];

	$id_celularsms = $_parametros["id_celularsms"];
	$id_celular2sms = $_parametros["id_celular2sms"];

	if($tecnico_e == "") {
		$tecnico_e = 0;
	}

	$_sitcliente = $_parametros["_sitcliente"];
	
	if($_sitcliente ==  ""){
		$_sitcliente = 0;
	}else{
		$_sitcliente = $_parametros["_sitcliente"];
	}

	
	if ($_parametros["_idcliente_sel"] == "") {
		//--------------------------- INCLUIR --------------------------------------------------------------------//

		//insere cadastro consumidor
		$sql = "insert into " . $_SESSION['BASE'] . ".consumidor (
		Nome_Consumidor,
		CIDADE,
		BAIRRO,
		Nome_Rua,
		CEP,
		UF,
		COMPLEMENTO,
		Data_Cadastro,
		Tipo_Pessoa,
		Num_Rua,
		data_nascimento,		
		CGC_CPF,
		TIPO_CLIENTE,
		INSCR_ESTADUAL,
		Nome_Fantasia,
		Cod_Regiao,
		CODIGO_TECNICO,
		FONE_RESIDENCIAL,
		FONE_CELULAR,
		DDD,
		comentarios,
		LOCAL_REFERENCIA,
		id_celularwats,
		id_celular2wats,
		id_celularsms,
		id_celular2sms,
		FONE_COMERCIAL,
		EMail
	) values(
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		CURRENT_DATE,
		?,
		?,
		?,		
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?
	)";
		$stm = $pdo->prepare($sql);
		$stm->bindParam(1, $nome, \PDO::PARAM_STR);
		$stm->bindParam(2, $cidade, \PDO::PARAM_STR);
		$stm->bindParam(3, $bairro, \PDO::PARAM_STR);
		$stm->bindParam(4, $endereco, \PDO::PARAM_STR);
		$stm->bindParam(5, $cep, \PDO::PARAM_STR);
		$stm->bindParam(6, $uf, \PDO::PARAM_STR);
		$stm->bindParam(7, $complemento, \PDO::PARAM_STR);
		$stm->bindParam(8, $tipo, \PDO::PARAM_STR);
		$stm->bindParam(9, $numerocasa, \PDO::PARAM_STR);
		$stm->bindParam(10, $nascimento, \PDO::PARAM_STR);
		$stm->bindParam(11, $cpfcnpj, \PDO::PARAM_STR);
		$stm->bindParam(12, $tipocliente, \PDO::PARAM_STR);
		$stm->bindParam(13, $rgie, \PDO::PARAM_STR);
		$stm->bindParam(14, $nomefantasia, \PDO::PARAM_STR);
		$stm->bindParam(15, $_codregiao, \PDO::PARAM_STR);
		$stm->bindParam(16, $tecnico_e, \PDO::PARAM_STR);
		$stm->bindParam(17, $fixo, \PDO::PARAM_STR);
		$stm->bindParam(18, $celular, \PDO::PARAM_STR);
		$stm->bindParam(19, $dddCelular, \PDO::PARAM_STR);
		$stm->bindParam(20, $_comentario, \PDO::PARAM_STR);
		$stm->bindParam(21, $proximidade, \PDO::PARAM_STR);
		$stm->bindParam(22, $id_celularwats, \PDO::PARAM_STR);
		$stm->bindParam(23, $id_celular2wats, \PDO::PARAM_STR);
		$stm->bindParam(24, $id_celularsms, \PDO::PARAM_STR);
		$stm->bindParam(25, $id_celular2sms, \PDO::PARAM_STR);
		$stm->bindParam(26, $celular2, \PDO::PARAM_STR);
		$stm->bindParam(27, $email, \PDO::PARAM_STR);
		$stm->execute();
		$id = $pdo->lastInsertId();

		try{			
			$_tipoAtividade = 100;
			$_documentoAtividade = 0;
			$_assuntoAtividade = "Novo Cliente";
			$_descricaoAtividade = "$nome - $bairro ";
			$stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".atividades (
				at_id,
				at_datahora,
				at_iduser,
				at_userlogin,
				at_tipo,
				at_icliente,				
				at_documento,				
				at_assunto,
				at_descricao) 
					VALUES (NULL,
					?,
					?,					
					?,
					?,
					?,
					?,
					?, 
					?); ");
				$stm->bindParam(1, $data_hora);			
				$stm->bindParam(2, $_SESSION['tecnico']);	
				$stm->bindParam(3, $_SESSION["APELIDO"]);		
				$stm->bindParam(4, $_tipoAtividade);	
				$stm->bindParam(5, $id);				
				$stm->bindParam(6, $_documentoAtividade);					
				$stm->bindParam(7, $_assuntoAtividade);	
				$stm->bindParam(8, $_descricaoAtividade);		
				$stm->execute();			
				

        }
        catch (\Exception $fault){
			$response = $fault;
        }

		?>
		<div class="modal-dialog text-center">

			<div class="modal-body">
				<div class="bg-icon pull-request">

					<i class="fa fa-5x fa-check-circle-o"></i>
					<h2>Cliente Cadastrado!</h2>
					<?php if ($stm->rowCount() > 0) { ?>
						<button type="button" class="btn btn-success waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="_newOS('<?= $id; ?>')">Gerar Nova OS</button>
					<?php 	}
					?>
					<button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>

				</div>
			</div>
		</div>
	<?php
		exit();
	} else {
		//--------------------------- ALTERAR  --------------------------------------------------------------------//
		$sql = "UPDATE " . $_SESSION['BASE'] . ".consumidor  SET
		Nome_Consumidor = ?,
		CIDADE = ?,
		BAIRRO = ?,
		Nome_Rua = ?,
		CEP = ?,
		UF = ?,
		COMPLEMENTO = ?,		
		Tipo_Pessoa = ?,
		Num_Rua = ?,
		data_nascimento = ?,		
		CGC_CPF = ?,
		TIPO_CLIENTE = ?,
		INSCR_ESTADUAL = ?,
		Nome_Fantasia = ?,
		Cod_Regiao = ?,
		CODIGO_TECNICO = ?,
		FONE_RESIDENCIAL = ?,
		FONE_CELULAR = ?,
		DDD = ?,
		comentarios = ?,
		EMail = ?,
		LOCAL_REFERENCIA = ?,
		id_celularwats = ?,
		id_celular2wats = ?,
		id_celularsms = ?,
		id_celular2sms = ?,
		FONE_COMERCIAL = ?,
		Ind_Bloqueio_Atendim = ?
		WHERE CODIGO_CONSUMIDOR = ?
	
	";
		$stm = $pdo->prepare($sql);
		$stm->bindParam(1, $nome, \PDO::PARAM_STR);
		$stm->bindParam(2, $cidade, \PDO::PARAM_STR);
		$stm->bindParam(3, $bairro, \PDO::PARAM_STR);
		$stm->bindParam(4, $endereco, \PDO::PARAM_STR);
		$stm->bindParam(5, $cep, \PDO::PARAM_STR);
		$stm->bindParam(6, $uf, \PDO::PARAM_STR);
		$stm->bindParam(7, $complemento, \PDO::PARAM_STR);
		$stm->bindParam(8, $tipo, \PDO::PARAM_STR);
		$stm->bindParam(9, $numerocasa, \PDO::PARAM_STR);
		$stm->bindParam(10, $nascimento, \PDO::PARAM_STR);
		$stm->bindParam(11, $cpfcnpj, \PDO::PARAM_STR);
		$stm->bindParam(12, $tipocliente, \PDO::PARAM_STR);
		$stm->bindParam(13, $rgie, \PDO::PARAM_STR);
		$stm->bindParam(14, $nomefantasia, \PDO::PARAM_STR);
		$stm->bindParam(15, $_codregiao, \PDO::PARAM_STR);
		$stm->bindParam(16, $tecnico_e, \PDO::PARAM_STR);
		$stm->bindParam(17, $fixo, \PDO::PARAM_STR);
		$stm->bindParam(18, $celular, \PDO::PARAM_STR);
		$stm->bindParam(19, $dddCelular, \PDO::PARAM_STR);
		$stm->bindParam(20, $_comentario, \PDO::PARAM_STR);
		$stm->bindParam(21, $email, \PDO::PARAM_STR);
		$stm->bindParam(22, $proximidade, \PDO::PARAM_STR);	
		$stm->bindParam(23, $id_celularwats, \PDO::PARAM_STR);	
		$stm->bindParam(24, $id_celular2wats, \PDO::PARAM_STR);	
		$stm->bindParam(25, $id_celularsms, \PDO::PARAM_STR);	
		$stm->bindParam(26, $id_celular2sms, \PDO::PARAM_STR);	
		$stm->bindParam(27, $celular2, \PDO::PARAM_STR);
		$stm->bindParam(28, $_sitcliente, \PDO::PARAM_STR);
		$stm->bindParam(29, $_parametros["_idcliente_sel"], \PDO::PARAM_STR);
	
		
		$stm->execute();

	?>
		<div class="modal-dialog text-center">

			<div class="modal-body">
				<div class="bg-icon pull-request">

					<i class="fa fa-5x fa-check-circle-o"></i>
					<h2>Cliente Alterado com sucesso!</h2>
					<button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
				</div>

			</div>
		</div>
	<?php
		exit();
	}
}

if ($acao == 3) { // lista equipamento ja do cliente

	$sql = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,
	Revendedor,Data_Nota,Nota_Fiscal,VOLTAGEM,cnpj
	from " . $_SESSION['BASE'] . ".chamada 	left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
	where chamada.CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "' 
    group by CODIGO_FABRICANTE,descricao,marca, serie";
	$consulta = $pdo->query($sql);	
	$retorno = $consulta->fetchAll();
	if ($consulta->rowCount() > 0) {
	?>
		<div class="row">
			<div class="panel panel-default panel-border">
				<div class="panel-heading">
					<h3 class="panel-title">Equipamentos Cliente</h3>
				</div>
			</div>
			<?php

			foreach ($retorno as $row) {
				if ($row['descricao'] != "") {
				
						$equi = $row['descricao'].";".$row['marca'].";".$row['Modelo'].";".$row['serie'].";;".$row['Nota_Fiscal'].";".$row['Data_Nota'].";".$row['VOLTAGEM'].";".$row['Revendedor'].";".$row['cnpj'];
			?>
					<div>

						<div class="card-box m-b-10" style="padding:0px">
							<div class="table-box opport-box">
								<div class="table-detail checkbx-detail" style="margin-left:20px ;">
									<div style="margin-left:20px ;">
										<button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_newOSAcaoSel('<?=$equi;?>','ListEqui')"> <i class="fa  fa-plus-square"></i> </button>
									</div>
								</div>
							
								<div class="table-detail">
									<div class="member-info">
										<h4 class="m-t-0 text-custom"><b><?= $row['descricao'] ?> </b></h4>
										<p class="text-dark m-b-5"><b>Modelo: </b> <span class="text-muted"><?= $row['Modelo'] ?></span></p>
										<p class="text-dark m-b-5"><b>Marca: </b> <span class="text-muted"><?= $row['marca'] ?></span></p>
										<p class="text-dark m-b-0"><b>Série: </b> <span class="text-muted"><?= $row['serie'] ?></span></p>
									</div>
								</div>
								<div class="table-detail checkbx-detail">
									<div class="checkbox checkbox-primary m-r-15">
										<input id="checkbox1" type="checkbox">
										<label for="checkbox1"></label>
										<h6><span class="text-muted">Inativar</span></h6>
									</div>

								</div>


							</div>
						</div>
					</div>
			<?php }
			} ?>
		</div><?php
			}
		}

		if ($acao == 33) { // consulta novamente dados cliente da os
			$sql = "Select Ind_Bloqueio_Atendim from " . $_SESSION['BASE'] . ".consumidor 
			        where CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "' and Ind_Bloqueio_Atendim <> 0";
			$consulta = $pdo->query($sql);
			$retorno = $consulta->fetchAll();
			if ($consulta->rowCount() > 0) {
				foreach ($retorno as $rst) {
					if($rst["Ind_Bloqueio_Atendim"] == 1) {
						$msg = "Cliente Bloqueado";
					}
					if($rst["Ind_Bloqueio_Atendim"] == 2) {
						$msg = "Cliente Inativo";
					}
					?>
					
  										<div class="bg-icon pull-request">
                                            <i class="md-3x  md-info-outline text-danger"></i>
                                                </div>
                                                <h3 ><span ><?=$msg;?></span> </h3>
                                                <p>
                                                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Voltar</button>
                                                    
                                                </p>
					<?php

				}
			}

		}
		if ($acao == 333) { // consulta novamente dados cliente da os
		
					?>
					
				
                                            <div class="bg-icon pull-request">
                                            <i class="md-3x   md-loupe text-success"></i>
                                                </div>
                                                <h3 ><span >Deseja Gerar nova O.S ?</span> </h3>
												<span  id="btnewOS">
                                                <p>
                                                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
													
                                                    	<button  type="button" class="confirm btn   btn-default btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_newOSAcao()">Nova O.S</button>
													
                                                </p>
												</span>
                                             
					<?php

				

		}

		if ($acao == 4) { // consulta novamente dados cliente da os
			$sql = "Select * from " . $_SESSION['BASE'] . ".consumidor 
			        where CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "'";
			$consulta = $pdo->query($sql);
			$retorno = $consulta->fetchAll();
			if ($consulta->rowCount() > 0) {
				foreach ($retorno as $rst) {
				?>
			<tr>
				<td width="11%">Nome:</td>
				<td width="58%"><span>
						<?= $rst["Nome_Consumidor"]; ?>
						<span>
							<input name="nomecliente" type="hidden" id="nomecliente" value=" <?= $rst["Nome_Consumidor"]; ?>" size="5" />
							<input name="codigo" type="hidden" id="codigo" value=" <?= $rst["CODIGO_CONSUMIDOR"]; ?>" size="4" />
							<input name="oksalva" type="hidden" id="oksalva" value="<?= $oksalva; ?>" />
						</span></span></td>
				<td colspan="2"> Email:<span> <?= $rst["EMail"]; ?>
						<span>

						</span></span></td>
				<td><button type="button" class="btn btn-warning waves-effect waves-light btn-xs" onclick="_consAlt()"><i class="fa fa-user fa-2x "></i></button>
				</td>
			</tr>
			<tr>
				<td height="20">Endere&ccedil;o:</td>
				<td colspan="2"><span> <?= $rst["Nome_Rua"]; ?> &nbsp;<?= $rst["Num_Rua"]; ?></span> Bairro:<span> <?= $rst["BAIRRO"]; ?></span> Cidade:
					<span><?= $rst["cid"]; ?></span>
					UF:
					<span> <?= $rst["estado"]; ?></span>
				</td>
				<td width="5%" rowspan="2"></td>
			</tr>
			<tr>
                                                        <td>Telefone:</td>
                                                        <td ><span><?= mascara($rst["FONE_RESIDENCIAL"], 'telefone'); ?>
                                                                /
                                                                <?= mascara($rst["FONE_CELULAR"], 'telefone'); ?>
                                                                /
                                                                <?= mascara($rst["FONE_COMERCIAL"], 'telefone'); ?>
                                                            </span>&nbsp;&nbsp; | &nbsp;&nbsp; Contato:<span> <?= $rst["NOME_RECADO"]; ?>
                                                                <span>
                                                                    <input type="hidden" name="id" id="id" value="1" />
                                                                </span></span></td>
                                                                <td colspan="2"> </td>
                                                        <td><button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_000011('<?=$_parametros['_idcliente'];?>')"> <i class="fa  fa-plus-square"></i> </button>                  </td>
                                                    </tr><?php
				}
			}
		}


if ($acao == 5) { // Lista equipamento  OS

			$sql = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,CODIGO_CHAMADA
					from " . $_SESSION['BASE'] . ".chamada 	left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
					where chamada.CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "' 
   			 group by CODIGO_FABRICANTE,descricao,marca, serie";
			$consulta = $pdo->query($sql);
			$retorno = $consulta->fetchAll();
			if ($consulta->rowCount() > 0) {
					?>
		<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
										<h4 class="modal-title">Equipamentos Cliente</h4>
				</div>

			<?php

				foreach ($retorno as $row) {
					if ($row['descricao'] != "") {
			?>

				<div class="row">
					<div class="table-box opport-box">
						<div class="table-detail checkbx-detail" style="margin-left:20px ;">
							<div style="margin:20px ;">
								<button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_idOStrocasel('<?= $row['CODIGO_CHAMADA']; ?>')"> <i class="fa  fa-exchange"></i> </button>
							</div>
						</div>
						<div class="table-detail">
							<div class="member-info">
								<h4 class="m-t-0 text-custom"><b><?= $row['descricao'] ?> </b></h4>
								<p class="text-dark m-b-5"><b>Marca: </b> <span class="text-muted"><?= $row['marca'] ?></span></p>

							</div>
						</div>

					</div>
				<?php }
				?>

		</div>
		
<?php
				}
			}
		}


		if ($acao == 6) { // REGIAO POR CEP
		
		$cidade = $_parametros['cidade'];
		$bairro = $_parametros['_bairro'];
	
		$sql="Select Cod_Regiao,Descricao_Regiao,CODIGO_TECNICO 
				from " . $_SESSION['BASE'] . ".tabregiao 
				WHERE Descricao_Regiao = '$bairro'				" ;    
		
      	$consulta = $pdo->query($sql);
		$retorno = $consulta->fetchAll();
		
		if ($consulta->rowCount() > 0) {	
				foreach ($retorno as $row) {
					$_tec = $row['CODIGO_TECNICO'];
					$_codregiao = $row['Cod_Regiao'];
					echo $_codregiao.";".$_tec;
					}
			}
		}

		if ($acao == 7) { // REGIAO  BUSCAR TÉCNICO
		
			$regiaoid = $_parametros['_codregiao'];
			
		
			$sql="Select Cod_Regiao,Descricao_Regiao,CODIGO_TECNICO 
					from " . $_SESSION['BASE'] . ".tabregiao 
					WHERE Cod_Regiao = '$regiaoid'	" ;    
			
			  $consulta = $pdo->query($sql);
			$retorno = $consulta->fetchAll();
			
			if ($consulta->rowCount() > 0) {	
					foreach ($retorno as $row) {
						$_tec = $row['CODIGO_TECNICO'];
						$_codregiao = $row['Cod_Regiao'];
						echo $_codregiao.";".$_tec;
						}
				}
			}

			
if ($acao == 8) { // Lista equipamento  OS

	$sql = "Select descricao,Modelo,serie,marca
			from " . $_SESSION['BASE'] . ".chamada 	left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
			where chamada.CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "' 
		    group by descricao,marca, serie,Modelo";
	
	$consulta = $pdo->query($sql);
	$retorno = $consulta->fetchAll();

			?>
		
			<h5 class="modal-title">Equipamentos Cliente</h5>
		
<div id="dvlista">
	<?php
	if ($consulta->rowCount() > 0) {
		foreach ($retorno as $row) {
			if ($row['descricao'] != "") {
				$equi = $row['descricao'].";".$row['marca'].";".$row['Modelo'].";".$row['serie'];
	?>

		<div class="row" >
			<div class="table-box opport-box" >
				<div class="table-detail checkbx-detail" style="margin-left:20px ;">
					<div style="margin:20px ;" >
						<button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_newOSAcaoSel('<?=$equi;?>','dvlista')"> <i class="fa fa-plus-square"></i> </button>
					</div>
				</div>
				<div class="table-detail checkbx-detail" style="margin-left:20px ;">
									<div >
										<?=$row['CODIGO_CHAMADA']; ?>
									</div>
								</div>
				<div class="table-detail">
					<div class="member-info">
						<h4 class="m-t-0 text-custom"><b><?= $row['descricao'] ?> </b></h4>
						<p class="text-dark m-b-5"><b>Modelo: </b> <span class="text-muted"><?= $row['Modelo'] ?></span></p>
						<p class="text-dark m-b-5"><b>Marca: </b> <span class="text-muted"><?= $row['marca'] ?></span></p>

					</div>
				</div>

			</div>
			</div>
		<?php }
		?>


<?php
		}
	}else{
		echo " Não existe equipamentos Registrados";
	}
	?></div>
	<?php

}


			
if ($acao == 9) { // Lista  OS


	$sql = "Select CODIGO_CHAMADA,marca,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,CODIGO_FABRICANTE,chamada.descricao,Modelo, serie
    ,g_sigla,situacaoos_elx,g_cor, situacaoos_elx.DESCRICAO as descsit 
    FROM chamada 
    LEFT JOIN situacao_garantia ON GARANTIA = g_id
    LEFT JOIN situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
    WHERE  chamada.CODIGO_CONSUMIDOR = '".$_parametros['_idcliente']."'  
	group by CODIGO_CHAMADA,CODIGO_FABRICANTE,descricao,Modelo, serie,DATA_ATEND_PREVISTO,marca";
	
    $exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
   			?>

                                 
                              <h5 class="modal-title">Lista de OS </h5>
                              
                                <div class="modal-body">
                                
                                    <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                   
                                                    <th>Descrição</th>                                                                                                                                                        
                                                    <th>N.OS</th>
                                                    <th>Dt Atend</th>                                   
                                                    <th>Situação</th>     
                                                    <th>Tipo Atend</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tbody_hist">
                                                <?php                                      
                                                while ($rult = mysqli_fetch_array($exUltOs)) {
                                                    ?>
                                                    <tr>                                                   
														<td style="text-align:center ;"><?=$rult['descricao'];?></td>                                                  
														<td style="text-align:center ;"><a style="cursor: pointer;" onclick="_0000101('<?=$rult['CODIGO_CHAMADA'];?>')"><?=$rult['CODIGO_CHAMADA'];?></a></td>
														<td style="text-align:center ;"><?=$rult['DATA_ATEND_PREVISTO'];?></td>
														<td style="text-align:center ;"><?=$rult['descsit'];?></td>
														<td style="text-align:center ;"><span class="badge  badge-<?=$rult['g_cor'];?>" ><?=$rult['g_sigla'];?></span></td>
                                                    </tr>
                                                    <?php
													
                                                }
                                        
                                            
                                                ?>
                                                    </tbody>
                                            </table>  
                                           
                                
                                
                               
                            </div>
							<?php
	
}


			
if ($acao == 10) { // validar cpf cnpj


	$sql = "Select  CODIGO_CONSUMIDOR,CGC_CPF from consumidor  where  CGC_CPF <> '' and CGC_CPF =  '".$_parametros["_cpfcnpj"]."'   limit 1	";
    $exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
	if(mysqli_num_rows( $exUltOs) > 0 ) {
		while ($rult = mysqli_fetch_array($exUltOs)) {
				$_id = $rult['CODIGO_CONSUMIDOR'];
		}

	}
	if($_id != "") {
   			?>
		 <div class="alert alert-danger alert-dismissable" style="margin:5px ;"> 
		 Já Existe cadastro com este documento ! <strong> <span style="cursor:pointer ;"  onclick="_consAltExiste('<?=$_id;?>')">consultar dados</span></strong> </div>
                                                  </div>
							<?php
								}
	
}

if ($acao == 11) { // carrega opções cliente ?>
	  <div class="card-box" >  
                                            <div class="row">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-8">
                                                     <label for="field-1" class="control-label">Selecione opção:</label>
                                                     
                                                     <button type="button" class="btn btn-block btn--md waves-effect waves-light btn-warning" onclick="_consAlt()" ><i class="fa  fa-user"></i> Dados Cadastrais</button>
                                                     <button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light"  onclick="_consOS();"><i class="fa fa-keyboard-o"></i> Lista OS</button>
                                                     <button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light" onclick="_consEquipamento();"><i class="fa fa-keyboard-o"></i> Lista Equipamentos</button>
                                                </div>  
                                                <div class="col-md-2">                                                    
                                                </div>                                                                                           
                                            </div>
                                            </div>             
<?php } 

?>