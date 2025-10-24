<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    if (!empty($valor)) {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("'", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
    }
    return $valor;
}

function ajustaData($data) {
    if (!empty($data)) {
        $data = trim($data);
        $data = str_replace("/", "-", $data);
        $data = date('Y-m-d', strtotime($data));
    }
    return $data;
}

$_parametros["rg-usuario"] = LimpaVariavel($_parametros["rg-usuario"]);
$_parametros["cpf-usuario"] = LimpaVariavel($_parametros["cpf-usuario"]);
$_parametros["nascimento-usuario"] = ajustaData($_parametros["nascimento-usuario"]);
$_parametros["cep-usuario"] = LimpaVariavel($_parametros["cep-usuario"]);
$_parametros["fixo-usuario"] = LimpaVariavel($_parametros["fixo-usuario"]);
$_parametros["celular-usuario"] = LimpaVariavel($_parametros["celular-usuario"]);
$_parametros["senha-usuario"] == "" ?: $_parametros["senha-usuario"] = md5($_parametros["senha-usuario"]);
$_parametros["ctpsserie"] = LimpaVariavel($_parametros["ctpsserie"]);
$_parametros["ctpsnum"] = LimpaVariavel($_parametros["ctpsnum"]);
$_parametros["ctpspis"] = LimpaVariavel($_parametros["ctpspis"]);
$_parametros["salario-usuario"] = LimpaVariavel($_parametros["salario-usuario"]);

$_parametros["dtadmissao-usuario"] = ajustaData($_parametros["dtadmissao-usuario"]);
$_parametros["dtdemissao-usuario"] = ajustaData($_parametros["dtdemissao-usuario"]);
$_parametros["banco-usuario"] = LimpaVariavel($_parametros["banco-usuario"]);
$_parametros["agencia-usuario"] = LimpaVariavel($_parametros["agencia-usuario"]);
$_parametros["conta-usuario"] = LimpaVariavel($_parametros["conta-usuario"]);
$_parametros["dtini1-usuario"] = ajustaData($_parametros["dtini1-usuario"]);
$_parametros["dtini2-usuario"] = ajustaData($_parametros["dtini2-usuario"]);
$_parametros["dtini3-usuario"] = ajustaData($_parametros["dtini3-usuario"]);
$_parametros["dtfim1-usuario"] = ajustaData($_parametros["dtfim1-usuario"]);
$_parametros["dtfim2-usuario"] = ajustaData($_parametros["dtfim2-usuario"]);
$_parametros["dtfim3-usuario"] = ajustaData($_parametros["dtfim3-usuario"]);
$_parametros["dtadv1-usuario"] = ajustaData($_parametros["dtadv1-usuario"]);
$_parametros["dtadv2-usuario"] = ajustaData($_parametros["dtadv2-usuario"]);
$_parametros["dtadv3-usuario"] = ajustaData($_parametros["dtadv3-usuario"]);
//$vlrcomissao= str_replace(",", ".",  $_parametros["comissaotecnico"]);
$_parametros["comissao-usuario"] = str_replace(",", ".",  $_parametros["comissao-usuario"]);
//$_parametros["comissao-usuario"] = LimpaVariavel($_parametros["comissao-usuario"]);
$senha_ponto = $_parametros["senha-usuario"];
$almoxarifado = $_parametros["almoxarifado"];
$tecnico = $_parametros["tecnico"];
$tipocontrato = $_parametros["tipocontrato"];


/*
 * Cria Usuário
 * */
if ($acao["acao"] == 1) {
    if (empty($_parametros["nome-usuario"]) || empty($_parametros["login-usuario"]) || empty($_parametros["apelido-usuario"]) || empty($_parametros["senha-usuario"]) ) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe nome, login, apelido e senha do usuário!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        if($pdo->query("SELECT COUNT(*) FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_LOGIN = '".$_parametros["login-usuario"]."'")->fetchColumn() > 0) {
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <i class="md-5x md-highlight-remove"></i>
                            <h2>Login já cadastrado! Por gentileza utilize outro login.</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            exit();
        }
        /*
        if (empty($_parametros["cpf-usuario"]) and $_parametros["cpf-usuario"]!= "") 
            {

            }
 
        else if ($pdo->query("SELECT COUNT(*) FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_cpf = '".$_parametros["cpf-usuario"]."'")->fetchColumn() > 0){
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <i class="md-5x md-highlight-remove"></i>
                            <h2>CPF já cadastrado! Por gentileza verifique a lista de usuários.</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
          */
     //   else {
            try {

                $arquivo_base64 = $_parametros["usuario_base64"];

                $sql = "INSERT INTO " . $_SESSION['BASE'] . ".usuario (usuario_NOME, usuario_APELIDO, usuario_CARGO, usuario_LOGIN, usuario_SENHA, usuario_ATIVO, usuario_HORARIO, usuario_MANHAENTRADA, usuario_MANHASAIDA, usuario_TARDEENTRADA, usuario_TARDESAIDA, usuario_sabadoe, usuario_sabados, usuario_acessoexterno, usuario_comissaotecnico, usuario_perfil2, usuario_cnh, usuario_datacnh, tipo_cnh, usuario_senhaponto, usuario_empresa, usuario_base, usuario_endereco, usuario_bairro, usuario_cidade, usuario_cep, usuario_uf, usuario_telefone, usuario_celular, usuario_sexo, usuario_funcao, usuario_escolaridade, usuario_cpf, usuario_rg, usuario_estadocivil, usuario_numero_carteira_trabalho, usuario_serie_carteira_trabalho, usuario_dataamissao, usuario_datademissao, usuario_salario, usuario_nomemae, usuario_nomepai, Ind_auto_desc_venda, data1_contrato_de, data1_contrato_ate, data2_contrato_de, data2_contrato_ate, data3_contrato_de, data3_contrato_ate, datanascimento, usuario_endereconumero, dtadv1, dtadv2, dtadv3, pis, nomebanco, agencia, conta,cod_beneficiario,usuario_avatar,usuario_background,usuario_PERFIL,usuario_almox,usuario_tecnico,usuario_email,chavepix,nomeresponsavel,numerodocresponsavel,	foneresponsavel,usuario_img64,p100)value (
                    '".$_parametros['nome-usuario']."',
                    '".$_parametros['apelido-usuario']."',
                    '".$_parametros['funcao-usuario']."',
                    '".$_parametros['login-usuario']."',
                    '".$_parametros['senha-usuario']."',
                    '".$_parametros['ativo-usuario']."',
                    '".$_parametros['hdias-usuario']."',
                    '".$_parametros['entradam-usuario']."',
                    '".$_parametros['saidam-usuario']."',
                    '".$_parametros['entradat-usuario']."',
                    '".$_parametros['saidat-usuario']."',
                    '".$_parametros['entradas-usuario']."',
                    '".$_parametros['saidas-usuario']."',
                    '".$_parametros['acessoexterno-usuario']."',
                    '".$_parametros['comissao-usuario']."',
                    '".$_parametros['perfil-usuario']."',
                    '".$_parametros['cnh-usuario']."',
                    '".$_parametros['dtcnh-usuario']."',
                    '".$_parametros['tcnh-usuario']."',
                    '".$_parametros['senhaponto-usuario']."',
                    '".$_parametros['empresa-usuario']."',
                    '".$_SESSION['BASE_ID']."',
                    '".$_parametros['endereco-usuario']."',
                    '".$_parametros['bairro-usuario']."',
                    '".$_parametros['cidade-usuario']."',
                    '".$_parametros['cep-usuario']."',
                    '".$_parametros['estado-usuario']."',
                    '".$_parametros['fixo-usuario']."',
                    '".$_parametros['celular-usuario']."',
                    '".$_parametros['sexo-usuario']."',
                    '".$_parametros['funcao-usuario']."',
                    '".$_parametros['escolaridade-usuario']."',
                    '".$_parametros['cpf-usuario']."',
                    '".$_parametros['rg-usuario']."',
                    '".$_parametros['estadocivil-usuario']."',
                    '".$_parametros['ctpsnum']."',
                    '".$_parametros['ctpsserie']."',
                    '".$_parametros['dtadmissao-usuario']."',
                    '".$_parametros['dtdemissao-usuario']."',
                    '".$_parametros['salario-usuario']."',
                    '".$_parametros['mae-usuario']."',
                    '".$_parametros['pai-usuario']."',
                    '".$_parametros['desconto-usuario']."',
                    '".$_parametros['dtini1-usuario']."',
                    '".$_parametros['dtfim1-usuario']."',
                    '".$_parametros['dtini2-usuario']."',
                    '".$_parametros['dtfim2-usuario']."',
                    '".$_parametros['dtini3-usuario']."',
                    '".$_parametros['dtfim3-usuario']."',
                    '".$_parametros['nascimento-usuario']."',
                    '".$_parametros['endereconum-usuario']."',
                    '".$_parametros['dtadv1-usuario']."',
                    '".$_parametros['dtadv2-usuario']."',
                    '".$_parametros['dtadv3-usuario']."',
                    '".$_parametros['ctpspis']."',
                    '".$_parametros['banco-usuario']."',
                    '".$_parametros['agencia-usuario']."',
                    '".$_parametros['conta-usuario']."',
                    '".$_parametros['usuario_lancaCC']."',
                    '".$_parametros['usuario_avatar']."',
                    '".$_parametros['usuario_background']."',
                    '".$_parametros['perfil-usuario']."',	
                    '".$_parametros['almoxarifado']."',
                    '".$_parametros['tecnico']."',
                    '".$_parametros['email-usuario']."',
                    '".$_parametros['chavepix']."',
                    '".$_parametros['nomeresponsavel']."',
                    '".$_parametros['numerodocresponsavel']."',
                    '".$_parametros['foneresponsavel']."',
                    '".$arquivo_base64."',
                    '".$tipocontrato."'   )";               
                        $statement = $pdo->prepare("$sql");             	
                        $statement->execute();
                        $_iduser = $pdo->lastInsertId();
                   
                    if($arquivo_base64 != ""){ 
  
                        // Exemplo de string base64 (substitua esta string pela sua)
                        $base64_string = $arquivo_base64;
                
                        // Remove o prefixo 'data:image/jpeg;base64,' se existir
                        if (strpos($base64_string, 'base64,') !== false) {
                            $base64_string = explode('base64,', $base64_string)[1];
                        }
                
                        // Decodifica a string base64
                        $image_data = base64_decode($base64_string);
                
                        // Especifica o caminho e o nome do arquivo para salvar
                        $file_path = "assets/images/usersnew/".$_SESSION["CODIGOCLI"]."_".$_iduser.".jpg";
                
                        // Salva o conteúdo binário em um arquivo
                        if (file_put_contents($file_path, $image_data)) {
                           $x =  "Arquivo salvo com sucesso em $file_path";
                        } else {
                            $x =  "Falha ao salvar o arquivo.";
                        }
                    }
              
                ?>
                <div class="modal-dialog text-center">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="bg-icon pull-request">
                            
                          
                                <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                                <i class="fa fa-5x fa-check-circle-o"></i>
                                <h2>Usuário Cadastrado! </h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } catch (PDOException $e) {
                ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h2><?="Erro: " . $e->getMessage()?></h2>
                        </div>
                    </div>
                </div>
                <?php
            }
     //   }
        
    }
}
/*
 * Lista Usuários
 * */
else if ($acao["acao"] == 2) {

    if($_parametros['ativo'] != "" and $_parametros['ativo'] != "0") {
        $_filtro = " WHERE usuario_ATIVO = '".$_parametros['ativo']."'";
        if(trim($_parametros['_nome']) != "") {
            $_filtro = $_filtro." AND usuario_NOME LIKE '%".$_parametros['_nome']."%'";
        }
     }else{
        if($_parametros['ativo'] == "") {
            $_filtro = " WHERE usuario_ATIVO = 'Sim'";
        }
     }

   

    $statement = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".usuario u $_filtro
     order by usuario_NOME ASC");
    $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($retorno as $row) {
            $usuario_base64 = $row->usuario_img64;
            

          if($usuario_base64 == ""){ 
			if($row->usuario_background == ""){
				$usuario_background = "#00a8e6";
			}else{
				$usuario_background = $row->usuario_background;
			}
			if($row->usuario_avatar == ""){
				$usuario_avatar = "0017.png";
			}else{
				$usuario_avatar = $row->usuario_avatar;
			}
        }
            
            $consulta_perfil = $pdo->query("SELECT permissao_descricao FROM ". $_SESSION['BASE'] .".permissao 
            where permissao_id = '".$row->usuario_perfil2."'");
            while($value = $consulta_perfil->fetch(PDO::FETCH_OBJ)){
                $perfil = $value->permissao_descricao;
            }
			
        ?>
            <div class="col-sm-3" style=" padding:10px; text-align:center;">
				<span style="position:absolute; right:18px; top:21px;" class="label label-table label-<?=$row->usuario_ATIVO == "Sim" ? "success" : "inverse" ?>">
                    <?php if($row->usuario_ATIVO == "Sim" ){ echo "Ativo";
                     }elseif($row->usuario_ATIVO == "Nao") { echo "Desativado";
                    }else{ echo "Bloqueado";} ?></span>
				<div style="border:1px solid #e3e3e3; border-radius:8px; padding-top:15px;">
					<div style="width:100%; height:140px;">
							<div class="circle-image" style="background-color:<?=$usuario_background;?>;">
                            <?php if($usuario_base64 == "") {?>
                                <img src="assets/images/avatar/<?=$usuario_avatar;?>" width="100px">
                            <?php }else{ ?>
                                <img style="margin-top:0px" src="<?=$usuario_base64;?>" width="100px"/>  
                            <?php } ?>
								
							</div>		
					</div>			
					<p class="categoria_title" style=" margin-top:9px;"><b style="font-size:14px;"><?php //=$row->usuario_CODIGOUSUARIO;?> <?=$row->usuario_NOME;?></b><br><br>Perfil:<span style="text-decoration:underline;"><?=$perfil;?></span></p>
					
					<div class="row" style="padding:20px;">
						<div class="col-xs-4">
							<button type="button" class="btn btn-default btn-block" onclick="_alterar(<?=$row->usuario_CODIGOUSUARIO?>)">Alterar</button>
						</div>
						<div class="col-xs-4">
							<button type="button" class="btn btn-success btn-block" onclick="_permissoes(<?=$row->usuario_CODIGOUSUARIO?>)">Permissões</button>
						</div>
						<div class="col-xs-4">
							<button type="button" class="btn btn-inverse btn-block" onclick="_idexcluir(<?=$row->usuario_CODIGOUSUARIO?>)" data-toggle="modal" data-target="#custom-modal-excluir">Excluir</button>
						</div>
					</div>
				</div>
			</div>

        <?php
        }
}
/*
 * Atualiza Usuário
 * */
else if ($acao["acao"] == 3) {
    $arquivo_base64 = $_parametros["usuario_base64"];
   
    if($arquivo_base64 != ""){ 
  
        // Exemplo de string base64 (substitua esta string pela sua)
        $base64_string = $arquivo_base64;

        // Remove o prefixo 'data:image/jpeg;base64,' se existir
        if (strpos($base64_string, 'base64,') !== false) {
            $base64_string = explode('base64,', $base64_string)[1];
        }

        // Decodifica a string base64
        $image_data = base64_decode($base64_string);

        // Especifica o caminho e o nome do arquivo para salvar
        $file_path = "assets/images/usersnew/".$_SESSION["CODIGOCLI"]."_".$_parametros['id-usuario'].".jpg";
        unlink($file_path);
        // Salva o conteúdo binário em um arquivo
        if (file_put_contents($file_path, $image_data)) {
           $x =  "Arquivo salvo com sucesso em $file_path";
        } else {
            $x =  "Falha ao salvar o arquivo.";
        }
    }else{
        $file_path = "assets/images/usersnew/".$_SESSION["CODIGOCLI"]."_".$_parametros['id-usuario'].".jpg";
        unlink($file_path);
    }


    if (empty($_parametros["nome-usuario"]) || empty($_parametros["apelido-usuario"]) || empty($_parametros["login-usuario"]) ) {
        ?>
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe nome, login e apelido do usuário! </h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else if (!empty($_parametros["senha-usuario"])) {
        try {
            
       

            $statement = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".usuario SET usuario_NOME = ?, usuario_APELIDO = ?, usuario_CARGO = ?, usuario_LOGIN = ?, usuario_SENHA = ?, usuario_ATIVO = ?, usuario_HORARIO = ?, usuario_MANHAENTRADA = ?, usuario_MANHASAIDA = ?, usuario_TARDEENTRADA = ?, usuario_TARDESAIDA = ?, usuario_sabadoe = ?, usuario_sabados = ?, usuario_acessoexterno = ?, usuario_comissaotecnico = ?, usuario_perfil2 = ?, usuario_cnh = ?, usuario_datacnh = ?, tipo_cnh = ?, usuario_senhaponto = ?, usuario_empresa = ?, usuario_endereco = ?, usuario_bairro = ?, usuario_cidade = ?, usuario_cep = ?, usuario_uf = ?, usuario_telefone = ?, usuario_celular = ?, usuario_sexo = ?, usuario_funcao = ?, usuario_escolaridade = ?, usuario_cpf = ?, usuario_rg = ?, usuario_estadocivil = ?, usuario_numero_carteira_trabalho = ?, usuario_serie_carteira_trabalho = ?, usuario_dataamissao = ?, usuario_datademissao = ?, usuario_salario = ?, usuario_nomemae = ?, usuario_nomepai = ?, Ind_auto_desc_venda = ?, data1_contrato_de = ?, data1_contrato_ate = ?, data2_contrato_de = ?, data2_contrato_ate = ?, data3_contrato_de = ?, data3_contrato_ate = ?, datanascimento = ?, usuario_endereconumero = ?, dtadv1 = ?, dtadv2 = ?, dtadv3 = ?, pis = ?, nomebanco = ?, agencia = ?, conta = ? , usuario_lancaCC = ? , usuario_avatar = ? , usuario_background = ?, cod_beneficiario = ?, usuario_almox = ?, usuario_tecnico = ?, usuario_email = ?, chavepix = ?, nomeresponsavel = ?, numerodocresponsavel = ?,foneresponsavel = ?, usuario_img64 = ? , p100 = ? WHERE usuario_CODIGOUSUARIO = ?");
            $statement->bindParam(1, $_parametros['nome-usuario']);
            $statement->bindParam(2, $_parametros['apelido-usuario']);
            $statement->bindParam(3, $_parametros['funcao-usuario']);
            $statement->bindParam(4, $_parametros['login-usuario']);
            $statement->bindParam(5, $_parametros['senha-usuario']);
            $statement->bindParam(6, $_parametros['ativo-usuario']);
            $statement->bindParam(7, $_parametros['hdias-usuario']);
            $statement->bindParam(8, $_parametros['entradam-usuario']);
            $statement->bindParam(9, $_parametros['saidam-usuario']);
            $statement->bindParam(10, $_parametros['entradat-usuario']);
            $statement->bindParam(11, $_parametros['saidat-usuario']);
            $statement->bindParam(12, $_parametros['entradas-usuario']);
            $statement->bindParam(13, $_parametros['saidas-usuario']);
            $statement->bindParam(14, $_parametros['acessoexterno-usuario']);
            $statement->bindParam(15, $_parametros['comissao-usuario']);
            $statement->bindParam(16, $_parametros['perfil-usuario']);
            $statement->bindParam(17, $_parametros['cnh-usuario']);
            $statement->bindParam(18, $_parametros['dtcnh-usuario']);
            $statement->bindParam(19, $_parametros['tcnh-usuario']);
            $statement->bindParam(20, $_parametros['senhaponto-usuario']);
            $statement->bindParam(21, $_parametros['empresa-usuario']);
            $statement->bindParam(22, $_parametros['endereco-usuario']);
            $statement->bindParam(23, $_parametros['bairro-usuario']);
            $statement->bindParam(24, $_parametros['cidade-usuario']);
            $statement->bindParam(25, $_parametros['cep-usuario']);
            $statement->bindParam(26, $_parametros['estado-usuario']);
            $statement->bindParam(27, $_parametros['fixo-usuario']);
            $statement->bindParam(28, $_parametros['celular-usuario']);
            $statement->bindParam(29, $_parametros['sexo-usuario']);
            $statement->bindParam(30, $_parametros['funcao-usuario']);
            $statement->bindParam(31, $_parametros['escolaridade-usuario']);
            $statement->bindParam(32, $_parametros['cpf-usuario']);
            $statement->bindParam(33, $_parametros['rg-usuario']);
            $statement->bindParam(34, $_parametros['estadocivil-usuario']);
            $statement->bindParam(35, $_parametros['ctpsnum']);
            $statement->bindParam(36, $_parametros['ctpsserie']);
            $statement->bindParam(37, $_parametros['dtadmissao-usuario']);
            $statement->bindParam(38, $_parametros['dtdemissao-usuario']);
            $statement->bindParam(39, $_parametros['salario-usuario']);
            $statement->bindParam(40, $_parametros['mae-usuario']);
            $statement->bindParam(41, $_parametros['pai-usuario']);
            $statement->bindParam(42, $_parametros['desconto-usuario']);
            $statement->bindParam(43, $_parametros['dtini1-usuario']);
            $statement->bindParam(44, $_parametros['dtfim1-usuario']);
            $statement->bindParam(45, $_parametros['dtini2-usuario']);
            $statement->bindParam(46, $_parametros['dtfim2-usuario']);
            $statement->bindParam(47, $_parametros['dtini3-usuario']);
            $statement->bindParam(48, $_parametros['dtfim3-usuario']);
            $statement->bindParam(49, $_parametros['nascimento-usuario']);
            $statement->bindParam(50, $_parametros['endereconum-usuario']);
            $statement->bindParam(51, $_parametros['dtadv1-usuario']);
            $statement->bindParam(52, $_parametros['dtadv2-usuario']);
            $statement->bindParam(53, $_parametros['dtadv3-usuario']);
            $statement->bindParam(54, $_parametros['ctpspis']);
            $statement->bindParam(55, $_parametros['banco-usuario']);
            $statement->bindParam(56, $_parametros['agencia-usuario']);
            $statement->bindParam(57, $_parametros['conta-usuario']);
            $statement->bindParam(58, $_parametros['usuario_lancaCC']);			
            $statement->bindParam(59, $_parametros['usuario_avatar']);
            $statement->bindParam(60, $_parametros['usuario_background']);
            $statement->bindParam(61, $_parametros['cod_beneficiario']);
            $statement->bindParam(62, $_parametros['almoxarifado']);
            $statement->bindParam(63, $_parametros['tecnico']);  
            $statement->bindParam(64, $_parametros['email-usuario']);  
            $statement->bindParam(65, $_parametros['chavepix']); 
            $statement->bindParam(66, $_parametros['nomeresponsavel']); 
            $statement->bindParam(67, $_parametros['numerodocresponsavel']); 
            $statement->bindParam(68, $_parametros['foneresponsavel']); 
            $statement->bindParam(69, $arquivo_base64);  
            $statement->bindParam(70, $tipocontrato);          
            $statement->bindParam(71, $_parametros['id-usuario']);	
                       
           	
            $statement->execute();

			if($_SESSION['IDUSER'] == $_parametros['id-usuario']){
				$_SESSION['avatar']  = "/app/assets/images/avatar/". $_parametros['usuario_avatar'];
				$_SESSION['background-avatar']  = $_parametros['usuario_background'];			
			}
            ?>
            <div class="modal-dialog text-center">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cadastro atualizado!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        try {
         
           
            
            $sql = "UPDATE " . $_SESSION['BASE'] . ".usuario SET usuario_NOME = ?, usuario_APELIDO = ?, usuario_CARGO = ?, usuario_LOGIN = ?, usuario_ATIVO = ?, usuario_HORARIO = ?, usuario_MANHAENTRADA = ?, usuario_MANHASAIDA = ?, usuario_TARDEENTRADA = ?, usuario_TARDESAIDA = ?, usuario_sabadoe = ?, usuario_sabados = ?, usuario_acessoexterno = ?, usuario_comissaotecnico = ?, usuario_perfil2 = ?, usuario_cnh = ?, usuario_datacnh = ?, tipo_cnh = ?, usuario_senhaponto = ?, usuario_empresa = ?, usuario_endereco = ?, usuario_bairro = ?, usuario_cidade = ?, usuario_cep = ?, usuario_uf = ?, usuario_telefone = ?, usuario_celular = ?, usuario_sexo = ?, usuario_funcao = ?, usuario_escolaridade = ?, usuario_cpf = ?, usuario_rg = ?, usuario_estadocivil = ?, usuario_numero_carteira_trabalho = ?, usuario_serie_carteira_trabalho = ?, usuario_dataamissao = ?, usuario_datademissao = ?, usuario_salario = ?, usuario_nomemae = ?, usuario_nomepai = ?, Ind_auto_desc_venda = ?, data1_contrato_de = ?, data1_contrato_ate = ?, data2_contrato_de = ?, data2_contrato_ate = ?, data3_contrato_de = ?, data3_contrato_ate = ?, datanascimento = ?, usuario_endereconumero = ?, dtadv1 = ?, dtadv2 = ?, dtadv3 = ?, pis = ?, nomebanco = ?, agencia = ?, conta = ?, usuario_lancaCC = ?  , usuario_avatar = ? , usuario_background = ?, cod_beneficiario = ?, usuario_almox = ?, usuario_tecnico = ? , usuario_email = ?, chavepix = ?, nomeresponsavel = ?, numerodocresponsavel = ?,foneresponsavel = ?,usuario_img64 = ?, p100 = ? WHERE usuario_CODIGOUSUARIO = ?";
	        $statement = $pdo->prepare($sql);
          
            $statement->bindParam(1, $_parametros['nome-usuario']);
            $statement->bindParam(2, $_parametros['apelido-usuario']);
            $statement->bindParam(3, $_parametros['funcao-usuario']);
            $statement->bindParam(4, $_parametros['login-usuario']);
            $statement->bindParam(5, $_parametros['ativo-usuario']);
            $statement->bindParam(6, $_parametros['hdias-usuario']);
            $statement->bindParam(7, $_parametros['entradam-usuario']);
            $statement->bindParam(8, $_parametros['saidam-usuario']);
            $statement->bindParam(9, $_parametros['entradat-usuario']);
            $statement->bindParam(10, $_parametros['saidat-usuario']);
            $statement->bindParam(11, $_parametros['entradas-usuario']);
            $statement->bindParam(12, $_parametros['saidas-usuario']);
            $statement->bindParam(13, $_parametros['acessoexterno-usuario']);
            $statement->bindParam(14, $_parametros['comissao-usuario']);
            $statement->bindParam(15, $_parametros['perfil-usuario']);
            $statement->bindParam(16, $_parametros['cnh-usuario']);
            $statement->bindParam(17, $_parametros['dtcnh-usuario']);
            $statement->bindParam(18, $_parametros['tcnh-usuario']);
            $statement->bindParam(19, $_parametros['senhaponto-usuario']);
            $statement->bindParam(20, $_parametros['empresa-usuario']);
            $statement->bindParam(21, $_parametros['endereco-usuario']);
            $statement->bindParam(22, $_parametros['bairro-usuario']);
            $statement->bindParam(23, $_parametros['cidade-usuario']);
            $statement->bindParam(24, $_parametros['cep-usuario']);
            $statement->bindParam(25, $_parametros['estado-usuario']);
            $statement->bindParam(26, $_parametros['fixo-usuario']);
            $statement->bindParam(27, $_parametros['celular-usuario']);
            $statement->bindParam(28, $_parametros['sexo-usuario']);
            $statement->bindParam(29, $_parametros['funcao-usuario']);
            $statement->bindParam(30, $_parametros['escolaridade-usuario']);
            $statement->bindParam(31, $_parametros['cpf-usuario']);
            $statement->bindParam(32, $_parametros['rg-usuario']);
            $statement->bindParam(33, $_parametros['estadocivil-usuario']);
            $statement->bindParam(34, $_parametros['ctpsnum']);
            $statement->bindParam(35, $_parametros['ctpsserie']);
            $statement->bindParam(36, $_parametros['dtadmissao-usuario']);
            $statement->bindParam(37, $_parametros['dtdemissao-usuario']);
            $statement->bindParam(38, $_parametros['salario-usuario']);
            $statement->bindParam(39, $_parametros['mae-usuario']);
            $statement->bindParam(40, $_parametros['pai-usuario']);
            $statement->bindParam(41, $_parametros['desconto-usuario']);
            $statement->bindParam(42, $_parametros['dtini1-usuario']);
            $statement->bindParam(43, $_parametros['dtfim1-usuario']);
            $statement->bindParam(44, $_parametros['dtini2-usuario']);
            $statement->bindParam(45, $_parametros['dtfim2-usuario']);
            $statement->bindParam(46, $_parametros['dtini3-usuario']);
            $statement->bindParam(47, $_parametros['dtfim3-usuario']);
            $statement->bindParam(48, $_parametros['nascimento-usuario']);
            $statement->bindParam(49, $_parametros['endereconum-usuario']);
            $statement->bindParam(50, $_parametros['dtadv1-usuario']);
            $statement->bindParam(51, $_parametros['dtadv2-usuario']);
            $statement->bindParam(52, $_parametros['dtadv3-usuario']);
            $statement->bindParam(53, $_parametros['ctpspis']);
            $statement->bindParam(54, $_parametros['banco-usuario']);
            $statement->bindParam(55, $_parametros['agencia-usuario']);
            $statement->bindParam(56, $_parametros['conta-usuario']);
            $statement->bindParam(57, $_parametros['usuario_lancaCC']);			
            $statement->bindParam(58, $_parametros['usuario_avatar']);
            $statement->bindParam(59, $_parametros['usuario_background']);
            $statement->bindParam(60, $_parametros['cod_beneficiario']);         
            $statement->bindParam(61, $_parametros['almoxarifado']);
            $statement->bindParam(62, $_parametros['tecnico']);      
            $statement->bindParam(63, $_parametros['email-usuario']);  
            $statement->bindParam(64, $_parametros['chavepix']); 
            $statement->bindParam(65, $_parametros['nomeresponsavel']); 
            $statement->bindParam(66, $_parametros['numerodocresponsavel']); 
            $statement->bindParam(67, $_parametros['foneresponsavel']); 
            $statement->bindParam(68, $arquivo_base64);  
            $statement->bindParam(69, $tipocontrato);                
            $statement->bindParam(70, $_parametros['id-usuario']);
            $statement->execute();

			if($_SESSION['IDUSER'] == $_parametros['id-usuario']){
				$_SESSION['avatar']  = "/app/assets/images/avatar/". $_parametros['usuario_avatar'];
				$_SESSION['background-avatar'] = $_parametros['usuario_background'];			
			}		
            ?>
            <div class="modal-dialog text-center">	
                <div class="modal-content">		
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cadastro atualizado!  </h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/*
 * Deleta Usuário
 * */
else if ($acao["acao"] == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_CODIGOUSUARIO = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Usuário Excluído!</h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}else if ($acao["acao"] == 5) { //imagem base64
    try {
        $statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_CODIGOUSUARIO = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Usuário Excluído!</h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}