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
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	return $_texto;
}

function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}

function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;

	// Verifica se todos os digitos são iguais
	if (preg_match('/(\d)\1{13}/', $cnpj))
		return false;	

	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
		return false;

	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}




$_acao = $_POST["acao"];

if ($acao == 1) {

	//DADOS CLIENTES
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["cpfcnpj"];
	$idcliente = $_parametros["_idcliente_sel"];
	//se informou cpf/cnpj, busca para ver se nao está duplicado

	if ($cpfcnpj != "") {
		//verifica se cpf/cnpj é existente
		$sql = "select * from " . $_SESSION['BASE'] . ".consumidor where CGC_CPF = ? and  CODIGO_CONSUMIDOR <> '$idcliente'";
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
							<h2>CPF/CNPJ já existente! </h2>
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

if ($acao == 22) {
	$cpfcnpj = $_parametros["_cpfcnpj"];
	$tipo = $_parametros['_tipopessoa'];
	$idcliente = $_parametros["_idcliente_sel"];
	//VAR CNPJ E CPF
	if($cpfcnpj != ""){
	$cpfcnpj = preg_replace('/[^0-9]/', '', (string) $_parametros["_cpfcnpj"]);
	
	if($tipo =="1") //cpf
	{
		$ret  = validaCPF($cpfcnpj);
		if($ret == false){

			echo "CPF INVALIDO, Verifique !!! ";
		
				exit();
			
		

		}
		$TIPODESC = "CPF";
		$cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
		substr($cpfcnpj, 3, 3) . '.' .
		substr($cpfcnpj, 6, 3) . '-' .
		substr($cpfcnpj, 9, 2);
	} else {
		$ret  = validar_cnpj($cpfcnpj);
		if($ret == false){
		
						echo "CNPJ INVALIDO, Verifique !!! ";
					
					
						
							exit();
					
			
		}
		$TIPODESC = "CNPJ";
		$cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                substr($cpfcnpj, 2, 3) . '.' .
                                substr($cpfcnpj, 5, 3) . '/' .
                                substr($cpfcnpj, 8, 4) . '-' .
                                substr($cpfcnpj, -2);

	} 
	if($_SESSION['per227'] == '') { 

	
				$sql = "Select  CODIGO_CONSUMIDOR,CGC_CPF from consumidor  where  CGC_CPF <> '' and CGC_CPF =  '".$cpfcnpj."' and CODIGO_CONSUMIDOR <> '$idcliente'  AND Tipo_Pessoa <> '2'  limit 1	";
				$exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
				if(mysqli_num_rows( $exUltOs) > 0 ) {
					echo "$TIPODESC JÁ EXISTENTE, Verifique !!! ";
					exit();
				}
			}
	}
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
	$cep =    str_replace(".", "", $cep);
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
 
	$_ie_municipal = $_parametros["_ie_municipal"];

	if($tecnico_e == "") {
		$tecnico_e = 0;
	}

	$_sitcliente = $_parametros["_sitcliente"];
	
	if($_sitcliente ==  ""){
		$_sitcliente = 0;
	}else{
		$_sitcliente = $_parametros["_sitcliente"];
	}

	//VAR CNPJ E CPF
	
	$cpfcnpj  = remove($cpfcnpj );
	$cpfcnpj = preg_replace('/[^0-9]/', '', (string) $cpfcnpj);
	
	if($cpfcnpj  != ""){
	if($tipo =="1") //cpf
	{
		

		$cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
		substr($cpfcnpj, 3, 3) . '.' .
		substr($cpfcnpj, 6, 3) . '-' .
		substr($cpfcnpj, 9, 2);
	} else {
		

		$cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                substr($cpfcnpj, 2, 3) . '.' .
                                substr($cpfcnpj, 5, 3) . '/' .
                                substr($cpfcnpj, 8, 4) . '-' .
                                substr($cpfcnpj, -2);

	} 

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
		EMail,
		DDD_RES,
		DDD_COM,
		INSCR_MUNICIPAL
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
		$stm->bindParam(28, $dddFixo, \PDO::PARAM_STR);
		$stm->bindParam(29, $dddCelular2, \PDO::PARAM_STR);
		$stm->bindParam(30, $_ie_municipal, \PDO::PARAM_STR);
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
		Ind_Bloqueio_Atendim = ?,		
		DDD_RES = ?,		
		DDD_COM = ?,
		INSCR_MUNICIPAL = ?
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
		$stm->bindParam(29, $dddFixo, \PDO::PARAM_STR);
		$stm->bindParam(30, $dddCelular2, \PDO::PARAM_STR);
		$stm->bindParam(31, $_ie_municipal, \PDO::PARAM_STR);		
		$stm->bindParam(32, $_parametros["_idcliente_sel"], \PDO::PARAM_STR);
	
		
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

	$sql = "Select codigo_equipamento,DATE_FORMAT(cEqui_dtUltAtualizacao,'%d/%m/%Y') AS dtult,DESCRICAO,
	tag as PNC,tipo_equipamento,numero_serie,MODELO,ap_linhaIMG 
	from " . $_SESSION['BASE'] . ".consumidor_equipamento 	
	LEFT JOIN " . $_SESSION['BASE'] . ".aparelho ON  CODIGO_APARELHO = id_produto
	LEFT JOIN " . $_SESSION['BASE'] . ".aparelho_produto ON  ap_prodId = aparelho_codProduto	
	LEFT JOIN " . $_SESSION['BASE'] . ".aparelho_linha ON  ap_linhaId = ap_prodLinha	
	where consumidor_equipamento.Codigo_consumidor = '" . $_parametros['_idcliente'] . "' 
    order by  DESCRICAO";

	$consulta = $pdo->query($sql);	
	$retorno = $consulta->fetchAll(); ?>
					<div id="divProdutoCli" >
                        <div class="card-box text-left" >
							<a href="#" class="pull-right btn btn-info btn-sm waves-effect waves-light" onclick="_newproduto()">Adicionar Equipamento</a>
                            <h4 class="text-dark header-title m-t-0">Produtos do Cliente</h4>
					<?php 
				
	if ($consulta->rowCount() > 0) {
	?>
			
							
                                <table class="table table-actions-bar m-b-0 ">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Descrição</th>
                                            <th>Ult Atualização</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
									<tbody>
											<?php

											foreach ($retorno as $row) {
												if ($row['DESCRICAO'] != "") {
												
														
											?>
																		<tr>
																			<td><button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_newOSAcaoSel('<?=$equi;?>','ListEqui')"> <i class="fa  fa-plus-square"></i> </button></td>
																			<td><img src="assets/images/products/<?=$row['ap_linhaIMG'];?>" class="thumb-sm pull-left m-r-10" alt=""> 
																			<?= $row['DESCRICAO'] ?><br>
																			<b>Modelo: </b> <span class="text-muted"><?= $row['MODELO'] ?></span>
																			<b>Série: </b> <span class="text-muted"><?= $row['numero_serie'] ?></span>
																			<b> PNC: </b> <span class="text-muted"><?= $row['PNC'] ?></span></td>
																			<td><span><?= $row['dtult'] ?></span></td>
																			<td>
																				<a href="#" onclick="_aparelhoEditar('<?=$row['codigo_equipamento'];?>')" class="table-action-btn"><i class="md md-edit"></i></a>
																			
																			</td>
																		</tr>
																		
												
											<?php }
											} ?>
			 					</tbody>
                                </table>
			    			
			</div>
		</div><?php
			}else {
				echo "Nenhum Produto informado";
			}
		}

		if ($acao == 32) { // adicionar equipamento cliente
			?>
		<div class="card-box text-left" >
			<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="formAp" id="formAp">
			<input type="hidden" id="_idclienteAP" name="_idclienteAP"  value="">
                <div >
                    <div class="row">             
                        <div class="col-sm-12 ">
							<div class="input-group">
                               <span class="input-group-btn">
                                    <button type="button" onclick="_aparelhoBusca()" class="btn waves-effect waves-light btn-primary " ><i class="fa fa-search"></i></button>
                                </span>
                                <input type="text" id="busca-aparelho" name="busca-aparelho" class="form-control " placeholder="Descrição, Marca, Modelo" onkeyup="_aparelhoBusca()">
                            </div> 
                        </div>                       
                    </div>  
										             
                    <div class="row" id="retorno-aparelho" >
                        <div class="col-sm-12 "  id="pesquisaaparelho" style="padding: 10px;">
                          
                           
                             <?php //buscar aparelho do cliente
							 /*
                                $sqlAPcli = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,VOLTAGEM,PNC
                                from " . $_SESSION['BASE'] . ".chamada 	
                                left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
                                where chamada.CODIGO_CONSUMIDOR = '" . $idcliente . "' 
                                group by CODIGO_FABRICANTE,descricao,marca, serie,PNC";
                                $consultaAPcli = $pdo->query($sqlAPcli);
                                $retornoAPcli = $consultaAPcli->fetchAll();                               
                                foreach ($retornoAPcli as $rowAPcli) {
                                    if ($rowAPcli['descricao'] != "") {
                                        $marcaAP = $rowAPcli['marca'];
                                        $descricaoprodutoAP = $rowAPcli['descricao'];
                                        $modeloAP = $rowAPcli['Modelo'];
                                        $SerieAP = $rowAPcli['serie'];
                                        $pncAP= $rowAPcli['PNC'];
                                        $voltagemAP= $rowAPcli['VOLTAGEM'];
                                      
                                        $_APTROCA = $marcaAP.";".$descricaoprodutoAP.";".$modeloAP.";".$SerieAP.";".$pncAP.";".$voltagemAP;
                                      
                                        
                                    ?>
                                    <tr>
                                    <td>
                                        <button class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_idOStrocaAparelho('<?=$_APTROCA;?>')"> <i class="fa  fa-exchange"></i> </button>
                                    </td>
                                    <th><?=$rowAPcli['descricao'] ?></th>
                                    <th><?=$rowAPcli['marca'] ?></th>
                                    <th><?=$rstAPcli['MODELO'];?></th>
                                    
                                    </tr>
                                    <?php
                                }
                            }
							*/

                             ?>

                            </table>
                        </div>
                        </div>
                        
                        
                   

                </div>
            </form> 
		</div>
			<?php
		
		
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

		if ($acao == 444) { // consulta novamente dados cliente para nf
			$sql = "Select Nome_Consumidor,CGC_CPF,INSCR_ESTADUAL,EMail,CEP,Nome_Rua,Num_Rua,BAIRRO,CIDADE,UF from " . $_SESSION['BASE'] . ".consumidor 
			        where CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "'";
			   $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    			print_r(json_encode(mysqli_fetch_array($resultado)));
			
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
			<td width="11%"><strong>Nome:</strong></td>
                                                        <td width="58%"><span>
                                                                <?= $rst["Nome_Consumidor"]; ?>
                                                                <span>

                                                                </span></span></td>
                                                        <td colspan="2"> <strong>Email:</strong><span> <?= $rst["EMail"]; ?>
                                                                <span>

                                                                </span></span></td>
                                                        <td><button type="button" class="btn btn-warning waves-effect waves-light btn-xs" onclick="_consAlt()"><i class="fa fa-user fa-2x "></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="20"><strong>Endere&ccedil;o:</strong></td>
                                                        <td colspan="2"><span> <?= $rst["Nome_Rua"]; ?> &nbsp;<?= $rst["Num_Rua"]; if($rst['COMPLEMENTO'] != '') { echo "<strong>   Compl:</strong>".$rst['COMPLEMENTO'];} ?></span> <strong>  Bairro:</strong><span> <?= $rst["BAIRRO"]; ?></span> 
                                                        <span><strong>  CEP:</strong><?=$rst['CEP']; ?></span> 
                                                        <strong>  Cidade: </strong>                                                     
                                                        <span><?= $rst["cid"]; ?></span>
                                                        <strong> UF:</strong>
                                                            <span> <?= $rst["estado"]; ?></span>
                                                        </td>
                                                        <td width="5%" rowspan="2"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Telefone:</strong></td>
                                                        <td ><span><?= mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone'); ?>
                                                               
                                                                <?= mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone'); ?>
                                                               
                                                                <?= mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone'); ?>
                                                            </span>                                                            
                                                                    <input type="hidden" name="id" id="id" value="1" />
                                                                </td>
                                                                <td colspan="2"> </td>
                                                        <td><button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_000011('<?=$idcliente;?>')"> <i class="fa  fa-plus-square"></i> </button>                  </td>
                                                    </tr><?php
				}
			}
		}



if ($acao == 5) { // Lista equipamento  OS

			$sql = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,CODIGO_CHAMADA,PNC
					from " . $_SESSION['BASE'] . ".chamada 
						left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
					where chamada.CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "' 
   			 group by CODIGO_FABRICANTE,descricao,marca, serie,PNC";
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
								<p class="text-dark m-b-5"><b>Modelo: </b> <span class="text-muted"><?= $row['modelo'] ?></span></p>
								<p class="text-dark m-b-5"><b>Série: </b> <span class="text-muted"><?= $row['serie'] ?></span><b> PNC: </b> <span class="text-muted"><?= $row['PNC'] ?></span></p>
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

	$sql = "Select descricao,Modelo,serie,marca,PNC
			from " . $_SESSION['BASE'] . ".chamada 	left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
			where chamada.CODIGO_CONSUMIDOR = '" . $_parametros['_idcliente'] . "' 
		    group by descricao,marca, serie,Modelo,PNC";
	
	$consulta = $pdo->query($sql);
	$retorno = $consulta->fetchAll();

			?>
		
			<h5 class="modal-title">Equipamentos Cliente</h5>
		
<div id="dvlista">
	<?php
	if ($consulta->rowCount() > 0) {
		foreach ($retorno as $row) {
			if ($row['descricao'] != "") {
				//$equi = $row['descricao'].";".$row['marca'].";".$row['Modelo'].";".$row['serie'];
				$equi = $row['descricao'].";".$row['marca'].";".$row['Modelo'].";".$row['serie'].";;".$row['Nota_Fiscal'].";".$row['Data_Nota'].";".$row['VOLTAGEM'].";".$row['Revendedor'].";".$row['cnpj'].";".$row['PNC'];
				
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
						<p class="text-dark m-b-5"><b>Série: </b> <span class="text-muted"><?= $row['serie'] ?></span> <b> PNC: </b> <span class="text-muted"><?= $row['PNC'] ?></span></p>

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


	$sql = "Select CODIGO_CHAMADA,marca,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
	date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
	CODIGO_FABRICANTE,chamada.descricao,Modelo, serie
    ,g_sigla,situacaoos_elx,g_cor, situacaoos_elx.DESCRICAO as descsit 
    FROM chamada 
    LEFT JOIN situacao_garantia ON GARANTIA = g_id
    LEFT JOIN situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
    WHERE  chamada.CODIGO_CONSUMIDOR = '".$_parametros['_idcliente']."'  
	group by CODIGO_CHAMADA,CODIGO_FABRICANTE,descricao,Modelo, serie,DATA_ATEND_PREVISTO,marca";
	
    $exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
   			?>

                                 
                              <h5 class="modal-title">Lista de OS </h5>
                              
                                <div class="modal-body" style="overflow: auto;">
                                
                                    <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                   
                                                    <th>Descrição</th>                                                                                                                                                        
                                                    <th>N.OS</th>  
													<th>Dt abertura</th>           
                                                    <th>Agendo p/</th>    
                                                    <th>Dt encerramento</th>                                    
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
														<td style="text-align:center ;"><?= $rult['data1']; ?></td>
                                                          <td style="text-align:center ;"><?= $rult['DATA_ATEND_PREVISTO']; ?></td>
                                                       <td style="text-align:center ;"><?= $rult['data3']; ?></td>
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
if ($acao == 100) { // validar telefone
	$celular_completo = $_parametros["_fonecelular"];	
	$celular = remove($celular_completo);
	$celular= substr($celular, 2, 10);

	$celular_completo2 = $_parametros["_fonecelular2"];	
	$celular2 = remove($celular_completo2);
	$celular2= substr($celular2, 2, 10);


	$fixo_completo = $_parametros["_fonefixo"];	
	$fixo = remove($fixo_completo);
	$fixo= substr($fixo, 2, 10);

	
	$sql = "Select CODIGO_CONSUMIDOR,FONE_CELULAR,FONE_CELULAR,FONE_RESIDENCIAL	from consumidor  where  
			FONE_COMERCIAL = '$celular' and FONE_COMERCIAL <> ''and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."' OR FONE_CELULAR =  '".$celular."' and FONE_CELULAR <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."' OR  FONE_RESIDENCIAL = '$celular' and FONE_RESIDENCIAL <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."'
		 OR FONE_COMERCIAL = '$celular2' and FONE_COMERCIAL <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."' OR FONE_CELULAR =  '".$celular2."' and FONE_CELULAR <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."' OR  FONE_RESIDENCIAL = '$celular2'  and FONE_RESIDENCIAL <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."'
		 OR FONE_COMERCIAL = '$fixo' and FONE_COMERCIAL <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."' OR FONE_CELULAR =  '".$fixo."' and FONE_CELULAR <> '' and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."' OR  FONE_RESIDENCIAL = '$fixo' and FONE_RESIDENCIAL <> ''  and CODIGO_CONSUMIDOR <> '".$_parametros['_idcliente']."'
			  limit 1	";

    $exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
	if(mysqli_num_rows( $exUltOs) > 0 ) {
		while ($rult = mysqli_fetch_array($exUltOs)) {
				$_id = $rult['CODIGO_CONSUMIDOR'];
		}

	}
	if($_id != "") {
   			?>
		 <div class="alert alert-danger alert-dismissable" style="margin:5px ;"> 
		 Já Existe telefone com este documento ! <strong> <span style="cursor:pointer ;"  onclick="_consAltExiste('<?=$_id;?>')">consultar dados</span></strong> </div>
                                                  </div>
							<?php 
								}
								if($m != "") { 
									?>
							  <div class="alert alert-danger alert-dismissable" style="margin:5px ;"> 
							  <strong> <?=$m;?>  </strong> </div>
																	   </div>
												 <?php
								}
}

			
if ($acao == 10) { // validar cpf cnpj
	$tipo = $_parametros['_tipopessoa'];
	$cpfcnpj = $_parametros["_cpfcnpj"];
	//VAR CNPJ E CPF
	$idcliente = $_parametros["_idcliente_sel"];
	if($cpfcnpj  != ""){

	
	$cpfcnpj = preg_replace('/[^0-9]/', '', (string) $_parametros["_cpfcnpj"]);
	
	if($tipo =="1") //cpf
	{
		$ret  = validaCPF($cpfcnpj);
		if($ret == false){

			$m =  "CPF INVALIDO, Verifique !!! ";
					
		

		}

		$cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
		substr($cpfcnpj, 3, 3) . '.' .
		substr($cpfcnpj, 6, 3) . '-' .
		substr($cpfcnpj, 9, 2);
	} else {
		$ret  = validar_cnpj($cpfcnpj);
		if($ret == false){
		
						$m =  "CNPJ INVALIDO, Verifique !!! ";
					
			
			
		}

		$cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                substr($cpfcnpj, 2, 3) . '.' .
                                substr($cpfcnpj, 5, 3) . '/' .
                                substr($cpfcnpj, 8, 4) . '-' .
                                substr($cpfcnpj, -2);

	} 
}

	$sql = "Select  CODIGO_CONSUMIDOR,CGC_CPF from consumidor  where  CGC_CPF <> '' and CGC_CPF =  '".$cpfcnpj."'  and  CODIGO_CONSUMIDOR <> '$idcliente'   limit 1	";
    $exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
	if(mysqli_num_rows( $exUltOs) > 0 ) {
		while ($rult = mysqli_fetch_array($exUltOs)) {
				$_id = $rult['CODIGO_CONSUMIDOR'];
		}

	}
	if($_id != "" and $tipo =="1") {
   			?>
		 <div class="alert alert-danger alert-dismissable" style="margin:5px ;"> 
		 Já Existe cadastro com este documento ! <strong> <span style="cursor:pointer ;"  onclick="_consAltExiste('<?=$_id;?>')">consultar dados</span></strong> </div>
                                                  </div>
							<?php
								}
								if($m != "") {
									?>
							  <div class="alert alert-danger alert-dismissable" style="margin:5px ;"> 
							  <strong> <?=$m;?>  </strong> </div>
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