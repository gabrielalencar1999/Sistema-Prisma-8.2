<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
use Database\MySQL;
use Functions\Validador;
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
        $sql = "SELECT COUNT(*) FROM " . $_SESSION['BASE'] . ".usuario WHERE usuario_LOGIN = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $_parametros["login-usuario"]);
        $stmt->execute();
        $count = (int) $stmt->fetchColumn();
        if($count > 0) {
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
                        $nome_usuario = $_parametros['nome-usuario'];
                        $apelido_usuario = $_parametros['apelido-usuario'];
                        $funcao_usuario = $_parametros['funcao-usuario'];
                        $login_usuario = $_parametros['login-usuario'];
                        $senha_usuario = $_parametros['senha-usuario'];
                        $ativo_usuario = $_parametros['ativo-usuario'];
                        $entradam_usuario = $_parametros['entradam-usuario'];
                        $saidam_usuario = $_parametros['saidam-usuario'];
                        $entradat_usuario = $_parametros['entradat-usuario'];
                        $saidat_usuario = $_parametros['saidat-usuario'];
                        $entradas_usuario = $_parametros['entradas-usuario'];
                        $saidas_usuario = $_parametros['saidas-usuario'];
                        $acessoexterno_usuario = $_parametros['acessoexterno-usuario'];
                        $comissao_usuario = $_parametros['comissao-usuario'];
                        $perfil_usuario = $_parametros['perfil-usuario'];
                        $cnh_usuario = $_parametros['cnh-usuario'];
                        $dtcnh_usuario = $_parametros['dtcnh-usuario'];
                        $tcnh_usuario = $_parametros['tcnh-usuario'];
                        $empresa_usuario = $_parametros['empresa-usuario'];
                        $endereco_usuario = $_parametros['endereco-usuario'];
                        $bairro_usuario = $_parametros['bairro-usuario'];
                        $cidade_usuario = $_parametros['cidade-usuario'];
                        $cep_usuario = $_parametros['cep-usuario'];
                        $estado_usuario = $_parametros['estado-usuario'];
                        $fixo_usuario = $_parametros['fixo-usuario'];
                        $celular_usuario = $_parametros['celular-usuario'];
                        $sexo_usuario = $_parametros['sexo-usuario'];
                        $funcao_usuario = $_parametros['funcao-usuario'];
                        $escolaridade_usuario = $_parametros['escolaridade-usuario'];
                        $cpf_usuario = $_parametros['cpf-usuario'];
                        $rg_usuario = $_parametros['rg-usuario'];
                        $estadocivil_usuario = $_parametros['estadocivil-usuario'];
                        $ctpsnum = $_parametros['ctpsnum'];
                        $ctpsserie = $_parametros['ctpsserie'];
                        $dtadmissao_usuario = $_parametros['dtadmissao-usuario'];
                        $dtdemissao_usuario = $_parametros['dtdemissao-usuario'];
                        $salario_usuario = $_parametros['salario-usuario'];
                        $mae_usuario = $_parametros['mae-usuario'];
                        $pai_usuario = $_parametros['pai-usuario'];
                        $desconto_usuario = $_parametros['desconto-usuario'];
                        $dtini1_usuario = $_parametros['dtini1-usuario'];
                        $dtfim1_usuario = $_parametros['dtfim1-usuario'];
                        $dtini2_usuario = $_parametros['dtini2-usuario'];
                        $dtfim2_usuario = $_parametros['dtfim2-usuario'];
                        $dtini3_usuario = $_parametros['dtini3-usuario'];
                        $dtfim3_usuario = $_parametros['dtfim3-usuario'];
                        $nascimento_usuario = $_parametros['nascimento-usuario'];
                        $endereconum_usuario = $_parametros['endereconum-usuario'];
                        $dtadv1_usuario = $_parametros['dtadv1-usuario'];
                        $dtadv2_usuario = $_parametros['dtadv2-usuario'];
                        $dtadv3_usuario = $_parametros['dtadv3-usuario'];
                        $ctpspis = $_parametros['ctpspis'];
                        $banco_usuario = $_parametros['banco-usuario'];
                        $agencia_usuario = $_parametros['agencia-usuario'];
                        $conta_usuario = $_parametros['conta-usuario'];
                        $usuario_lancaCC = $_parametros['usuario_lancaCC'];
                        $usuario_avatar = $_parametros['usuario_avatar'];
                        $usuario_background = $_parametros['usuario_background'];
                        $perfil_usuario = $_parametros['perfil-usuario'];
                        $almoxarifado = $_parametros['almoxarifado'];
                        $tecnico = $_parametros['tecnico'];
                        $email_usuario = $_parametros['email-usuario'];
                        $chavepix = $_parametros['chavepix'];
                        $nomeresponsavel = $_parametros['nomeresponsavel'];
                        $numerodocresponsavel = $_parametros['numerodocresponsavel'];
                        $foneresponsavel = $_parametros['foneresponsavel'];
                        $arquivo_base64 = $_parametros['usuario_img64'];
                        $p100 = $_parametros['p100'];
                        $usuario_emaicorp = $_parametros['usuario_emaicorp'];
                        $usuario_fonecomercial = $_parametros['usuario_fonecomercial'];
                        $usuario_nomerecado = $_parametros['usuario_nomerecado'];

                     
                        if($p100 == "") {
                            $p100 = "N";    
                        }
                           if($funcao_usuario == "" ) {
                               $funcao_usuario = "-";    
                            }

    
                         $sql = "INSERT INTO " . $_SESSION['BASE'] . ".usuario (
                            usuario_NOME, usuario_APELIDO, usuario_CARGO, usuario_LOGIN, usuario_SENHA, usuario_ATIVO,
                            usuario_HORARIO, usuario_MANHAENTRADA, usuario_MANHASAIDA, usuario_TARDEENTRADA, usuario_TARDESAIDA,
                            usuario_sabadoe, usuario_sabados, usuario_acessoexterno, usuario_comissaotecnico, usuario_perfil2,
                            usuario_cnh, usuario_datacnh, tipo_cnh, usuario_empresa, usuario_base,
                            usuario_endereco, usuario_bairro, usuario_cidade, usuario_cep, usuario_uf, usuario_telefone,
                            usuario_celular, usuario_sexo, usuario_funcao, usuario_escolaridade, usuario_cpf, usuario_rg,
                            usuario_estadocivil, usuario_numero_carteira_trabalho, usuario_serie_carteira_trabalho,
                            usuario_dataamissao, usuario_datademissao, usuario_salario, usuario_nomemae, usuario_nomepai,
                            Ind_auto_desc_venda, data1_contrato_de, data1_contrato_ate, data2_contrato_de, data2_contrato_ate,
                            data3_contrato_de, data3_contrato_ate, datanascimento, usuario_endereconumero, dtadv1, dtadv2, dtadv3,
                            pis, nomebanco, agencia, conta, cod_beneficiario, usuario_avatar, usuario_background, usuario_PERFIL,
                            usuario_almox, usuario_tecnico, usuario_email, chavepix, nomeresponsavel, numerodocresponsavel,
                            foneresponsavel, usuario_img64, p100, usuario_emaicorp, usuario_fonecomercial, usuario_nomerecado) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                        $statement = $pdo->prepare($sql);

                        $statement->bindParam(1, $nome_usuario);
                        $statement->bindParam(2, $apelido_usuario);
                        $statement->bindParam(3, $funcao_usuario);
                        $statement->bindParam(4, $login_usuario);
                        $statement->bindParam(5, $senha_usuario);
                        $statement->bindParam(6, $ativo_usuario);
                        $statement->bindParam(7, $entradam_usuario);
                        $statement->bindParam(8, $saidam_usuario);
                        $statement->bindParam(9, $entradat_usuario);
                        $statement->bindParam(10, $saidat_usuario);
                        $statement->bindParam(11, $entradas_usuario);
                        $statement->bindParam(12, $saidas_usuario);
                        $statement->bindParam(13, $acessoexterno_usuario);
                        $statement->bindParam(14, $comissao_usuario);
                        $statement->bindParam(15, $perfil_usuario);
                        $statement->bindParam(16, $cnh_usuario);
                        $statement->bindParam(17, $dtcnh_usuario);
                        $statement->bindParam(18, $tcnh_usuario);
                        $statement->bindParam(19, $senhaponto_usuario);
                        $statement->bindParam(20, $empresa_usuario);
                        $statement->bindParam(21, $_SESSION['BASE_ID']);
                        $statement->bindParam(22, $endereco_usuario);
                        $statement->bindParam(23, $bairro_usuario);
                        $statement->bindParam(24, $cidade_usuario);
                        $statement->bindParam(25, $cep_usuario);
                        $statement->bindParam(26, $estado_usuario);
                        $statement->bindParam(27, $fixo_usuario);
                        $statement->bindParam(28, $celular_usuario);
                        $statement->bindParam(29, $sexo_usuario);
                        $statement->bindParam(30, $funcao_usuario2);
                        $statement->bindParam(31, $escolaridade_usuario);
                        $statement->bindParam(32, $cpf_usuario);
                        $statement->bindParam(33, $rg_usuario);
                        $statement->bindParam(34, $estadocivil_usuario);
                        $statement->bindParam(35, $ctpsnum);
                        $statement->bindParam(36, $ctpsserie);
                        $statement->bindParam(37, $dtadmissao_usuario);
                        $statement->bindParam(38, $dtdemissao_usuario);
                        $statement->bindParam(39, $salario_usuario);
                        $statement->bindParam(40, $mae_usuario);
                        $statement->bindParam(41, $pai_usuario);
                        $statement->bindParam(42, $desconto_usuario);
                        $statement->bindParam(43, $dtini1_usuario);
                        $statement->bindParam(44, $dtfim1_usuario);
                        $statement->bindParam(45, $dtini2_usuario);
                        $statement->bindParam(46, $dtfim2_usuario);
                        $statement->bindParam(47, $dtini3_usuario);
                        $statement->bindParam(48, $dtfim3_usuario);
                        $statement->bindParam(49, $nascimento_usuario);
                        $statement->bindParam(50, $endereconum_usuario);
                        $statement->bindParam(51, $dtadv1_usuario);
                        $statement->bindParam(52, $dtadv2_usuario);
                        $statement->bindParam(53, $dtadv3_usuario);
                        $statement->bindParam(54, $ctpspis);
                        $statement->bindParam(55, $banco_usuario);
                        $statement->bindParam(56, $agencia_usuario);
                        $statement->bindParam(57, $conta_usuario);
                        $statement->bindParam(58, $usuario_lancaCC);
                        $statement->bindParam(59, $usuario_avatar);
                        $statement->bindParam(60, $usuario_background);
                        $statement->bindParam(61, $perfil_usuario);
                        $statement->bindParam(62, $almoxarifado);
                        $statement->bindParam(63, $tecnico);
                        $statement->bindParam(64, $email_usuario);
                        $statement->bindParam(65, $chavepix);
                        $statement->bindParam(66, $nomeresponsavel);
                        $statement->bindParam(67, $numerodocresponsavel);
                        $statement->bindParam(68, $foneresponsavel);
                        $statement->bindParam(69, $arquivo_base64);
                        $statement->bindParam(70, $p100);
                        $statement->bindParam(71, $usuario_emaicorp);
                        $statement->bindParam(72, $usuario_fonecomercial);
                        $statement->bindparam(73, $usuario_nomerecado);

                        $statement->execute();

                        $_iduser = $pdo->lastInsertId();
                   

                $tipo_sanguineo = $_parametros['tiposanguineo-usuario'];
                $tipo_alergia = $_parametros['alergia-usuario'];
                $medicamento_controlado = $_parametros['usoMedicamento-usuario'];
                $medicamento_dor = $_parametros['medicamento-usuario'];
                $problema_saude = $_parametros['problemasSaude-usuario']; 
                $ctt_emergencia_nome = $_parametros['nomecontatoemergencia-usuario'];
                $ctt_emergencia_telefone = $_parametros['numerocontatoemergencia-usuario'];
                $fs_fumante = isset($_parametros['fumante-usuario']) && $_parametros['fumante-usuario'] !== ""? $_parametros['fumante-usuario'] : 0;
                $fs_convenio = $_parametros['convenio-usuario'];
                $fs_usomedicamento       = isset($_parametros['usoMedicamentos']) && $_parametros['usoMedicamentos'] !== "" ? (int)$_parametros['usoMedicamentos'] : null;
                $fs_acompmedicamento = isset($_POST['acompanhamentoMedico']) && $_POST['acompanhamentoMedico'] !== "" ? $_POST['acompanhamentoMedico'] : 0;
                $fs_examemedicamento = isset($_POST['examesPeriodicos']) && $_POST['examesPeriodicos'] !== "" ? $_POST['examesPeriodicos'] : 0;
                
                
                       $statement = $pdo->prepare("
                        INSERT INTO " . $_SESSION['BASE'] . ".ficha_saude (
            fs_CodUsuario,
            tipo_sanguineo,
            tipo_alergia,
            medicamento_controlado,
            medicamento_dor,
            problema_saude,
            ctt_emergencia_nome,
            ctt_emergencia_telefone,
            fs_fumante,
            fs_convenio,
            fs_usomedicamento,
            fs_acompmedicamento,
            fs_examemedicamento) VALUES 
                        ('". $_iduser."',
            '".$tipo_sanguineo."',
            '".$tipo_alergia."',
            '".$medicamento_controlado."',
            '".$medicamento_dor."',
            '".$problema_saude."',
            '".$ctt_emergencia_nome."',
            '".$ctt_emergencia_telefone."',
            '".$fs_fumante."',
            '".$fs_convenio."',
            '".$fs_usomedicamento."',
            '".$fs_acompmedicamento."',
            '".$fs_examemedicamento."');");
                        $statement->execute();
                  
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
         
                    
            $nome_usuario = $_parametros['nome-usuario']?? null; 
            $apelido_usuario = $_parametros['apelido-usuario']?? null;
            $funcao_usuario = $_parametros['funcao-usuario']?? null;
            $login_usuario = $_parametros['login-usuario']?? null;
            $senha_usuario = $_parametros['senha-usuario']?? null;
            $ativo_usuario = $_parametros['ativo-usuario']?? null;
            $entradam_usuario = $_parametros['entradam-usuario']?? null;
            $saidam_usuario = $_parametros['saidam-usuario']?? null;
            $entradat_usuario = $_parametros['entradat-usuario']?? null;
            $saidat_usuario = $_parametros['saidat-usuario']?? null;
            $entradas_usuario = $_parametros['entradas-usuario']?? null;
            $saidas_usuario = $_parametros['saidas-usuario']?? null;
            $acessoexterno_usuario = $_parametros['acessoexterno-usuario']?? null;
            $comissao_usuario = $_parametros['comissao-usuario']?? null;
            $perfil_usuario = $_parametros['perfil-usuario']?? null;
            $cnh_usuario = $_parametros['cnh-usuario']?? null;
            $dtcnh_usuario = $_parametros['dtcnh-usuario']?? null;
            $tcnh_usuario = $_parametros['tcnh-usuario']?? null;
            $empresa_usuario = $_parametros['empresa-usuario']?? null;
            $endereco_usuario = $_parametros['endereco-usuario']?? null;
            $bairro_usuario = $_parametros['bairro-usuario']?? null;
            $cidade_usuario = $_parametros['cidade-usuario']?? null;
            $cep_usuario = $_parametros['cep-usuario']?? null;
            $estado_usuario = $_parametros['estado-usuario']?? null;
            $fixo_usuario = $_parametros['fixo-usuario']?? null;
            $celular_usuario = $_parametros['celular-usuario']?? null;
            $sexo_usuario = $_parametros['sexo-usuario']?? null;
            $funcao_usuario = $_parametros['funcao-usuario']?? null;
            $escolaridade_usuario = $_parametros['escolaridade-usuario']?? null;
            $cpf_usuario = $_parametros['cpf-usuario']?? null;
            $rg_usuario = $_parametros['rg-usuario']?? null;
            $estadocivil_usuario = $_parametros['estadocivil-usuario']?? null;
            $ctpsnum = $_parametros['ctpsnum']?? null;
            $ctpsserie = $_parametros['ctpsserie']?? null;
            $dtadmissao_usuario = $_parametros['dtadmissao-usuario']?? null;
            $dtdemissao_usuario = $_parametros['dtdemissao-usuario']?? null;
            $salario_usuario = $_parametros['salario-usuario']?? null;
            $mae_usuario = $_parametros['mae-usuario']?? null;
            $pai_usuario = $_parametros['pai-usuario']?? null;
            $desconto_usuario = $_parametros['desconto-usuario']?? null;
            $dtini1_usuario = $_parametros['dtini1-usuario']?? null;
            $dtfim1_usuario = $_parametros['dtfim1-usuario']?? null;
            $dtini2_usuario = $_parametros['dtini2-usuario']?? null;
            $dtfim2_usuario = $_parametros['dtfim2-usuario']?? null;
            $dtini3_usuario = $_parametros['dtini3-usuario']?? null;
            $dtfim3_usuario = $_parametros['dtfim3-usuario']?? null;
            $nascimento_usuario = $_parametros['nascimento-usuario']?? null;
            $endereconum_usuario = $_parametros['endereconum-usuario']?? null;
            $dtadv1_usuario = $_parametros['dtadv1-usuario']?? null;
            $dtadv2_usuario = $_parametros['dtadv2-usuario']?? null;
            $dtadv3_usuario = $_parametros['dtadv3-usuario']?? null;
            $ctpspis = $_parametros['ctpspis']?? null;
            $banco_usuario = $_parametros['banco-usuario']?? null;
            $agencia_usuario = $_parametros['agencia-usuario']?? null;
            $conta_usuario = $_parametros['conta-usuario']?? null;
            $usuario_lancaCC = $_parametros['usuario_lancaCC']?? null;
            $usuario_avatar = $_parametros['usuario_avatar']?? null;
            $usuario_background = $_parametros['usuario_background']?? null;
            $cod_beneficiario = $_parametros['cod_beneficiario']?? null;
            $almoxarifado = $_parametros['almoxarifado']?? null;
            $tecnico = $_parametros['tecnico']?? null;
            $email_usuario = $_parametros['email-usuario']?? null;
            $chavepix = $_parametros['chavepix']?? null;
            $nomeresponsavel = $_parametros['nomeresponsavel']?? null;
            $numerodocresponsavel = $_parametros['numerodocresponsavel']?? null;
            $foneresponsavel = $_parametros['foneresponsavel']?? null;
            $id_usuario = $_parametros['id-usuario']?? null;
            $arquivo_base64 = $_parametros['usuario_img64'] ?? null;
            $tipocontrato   = $_parametros['p100'] ?? null;
            $usuario_emaicorp = $_parametros['usuario_emaicorp'] ?? null;
            $usuario_fonecomercial = $_parametros['usuario_fonecomercial'];
            $usuario_nomerecado = $_parametros['usuario_nomerecado'];

            $sql = "UPDATE " . $_SESSION['BASE'] . ".usuario SET usuario_NOME = ?, usuario_APELIDO = ?, usuario_CARGO = ?, usuario_LOGIN = ?, usuario_SENHA = ?, usuario_ATIVO = ?, usuario_HORARIO = ?, usuario_MANHAENTRADA = ?, usuario_MANHASAIDA = ?, usuario_TARDEENTRADA = ?, usuario_TARDESAIDA = ?, usuario_sabadoe = ?, usuario_sabados = ?, usuario_acessoexterno = ?, usuario_comissaotecnico = ?, usuario_perfil2 = ?, usuario_cnh = ?, usuario_datacnh = ?, tipo_cnh = ?, usuario_senhaponto = ?, usuario_empresa = ?, usuario_endereco = ?, usuario_bairro = ?, usuario_cidade = ?, usuario_cep = ?, usuario_uf = ?, usuario_telefone = ?, usuario_celular = ?, usuario_sexo = ?, usuario_funcao = ?, usuario_escolaridade = ?, usuario_cpf = ?, usuario_rg = ?, usuario_estadocivil = ?, usuario_numero_carteira_trabalho = ?, usuario_serie_carteira_trabalho = ?, usuario_dataamissao = ?, usuario_datademissao = ?, usuario_salario = ?, usuario_nomemae = ?, usuario_nomepai = ?, Ind_auto_desc_venda = ?, data1_contrato_de = ?, data1_contrato_ate = ?, data2_contrato_de = ?, data2_contrato_ate = ?, data3_contrato_de = ?, data3_contrato_ate = ?, datanascimento = ?, usuario_endereconumero = ?, dtadv1 = ?, dtadv2 = ?, dtadv3 = ?, pis = ?, nomebanco = ?, agencia = ?, conta = ?, usuario_lancaCC = ?, usuario_avatar = ?, usuario_background = ?, cod_beneficiario = ?, usuario_almox = ?, usuario_tecnico = ?, usuario_email = ?, chavepix = ?, nomeresponsavel = ?, numerodocresponsavel = ?, foneresponsavel = ?, usuario_img64 = ?, p100 = ?, usuario_emaicorp = ? ,usuario_nomerecado = ? WHERE usuario_CODIGOUSUARIO = ?";
           
            $statement = $pdo->prepare($sql);                  
             
            
            $statement->bindParam(1, $nome_usuario);
            $statement->bindParam(2, $apelido_usuario);
            $statement->bindParam(3, $funcao_usuario);
            $statement->bindParam(4, $login_usuario);
            $statement->bindParam(5, $senha_usuario);
            $statement->bindParam(6, $ativo_usuario);
            $statement->bindParam(7, $hdias_usuario);
            $statement->bindParam(8, $entradam_usuario);
            $statement->bindParam(9, $saidam_usuario);
            $statement->bindParam(10, $entradat_usuario);
            $statement->bindParam(11, $saidat_usuario);
            $statement->bindParam(12, $entradas_usuario);
            $statement->bindParam(13, $saidas_usuario);
            $statement->bindParam(14, $acessoexterno_usuario);
            $statement->bindParam(15, $comissao_usuario);
            $statement->bindParam(16, $perfil_usuario);
            $statement->bindParam(17, $cnh_usuario);
            $statement->bindParam(18, $dtcnh_usuario);
            $statement->bindParam(19, $tcnh_usuario);
            $statement->bindParam(20, $senhaponto_usuario);
            $statement->bindParam(21, $empresa_usuario);
            $statement->bindParam(22, $endereco_usuario);
            $statement->bindParam(23, $bairro_usuario);
            $statement->bindParam(24, $cidade_usuario);
            $statement->bindParam(25, $cep_usuario);
            $statement->bindParam(26, $estado_usuario);
            $statement->bindParam(27, $fixo_usuario);
            $statement->bindParam(28, $celular_usuario);
            $statement->bindParam(29, $sexo_usuario);
            $statement->bindParam(30, $funcao_usuario);
            $statement->bindParam(31, $escolaridade_usuario);
            $statement->bindParam(32, $cpf_usuario);
            $statement->bindParam(33, $rg_usuario);
            $statement->bindParam(34, $estadocivil_usuario);
            $statement->bindParam(35, $ctpsnum);
            $statement->bindParam(36, $ctpsserie);
            $statement->bindParam(37, $dtadmissao_usuario);
            $statement->bindParam(38, $dtdemissao_usuario);
            $statement->bindParam(39, $salario_usuario);
            $statement->bindParam(40, $mae_usuario);
            $statement->bindParam(41, $pai_usuario);
            $statement->bindParam(42, $desconto_usuario);
            $statement->bindParam(43, $dtini1_usuario);
            $statement->bindParam(44, $dtfim1_usuario);
            $statement->bindParam(45, $dtini2_usuario);
            $statement->bindParam(46, $dtfim2_usuario);
            $statement->bindParam(47, $dtini3_usuario);
            $statement->bindParam(48, $dtfim3_usuario);
            $statement->bindParam(49, $nascimento_usuario);
            $statement->bindParam(50, $endereconum_usuario);
            $statement->bindParam(51, $dtadv1_usuario);
            $statement->bindParam(52, $dtadv2_usuario);
            $statement->bindParam(53, $dtadv3_usuario);
            $statement->bindParam(54, $ctpspis);
            $statement->bindParam(55, $banco_usuario);
            $statement->bindParam(56, $agencia_usuario);
            $statement->bindParam(57, $conta_usuario);
            $statement->bindParam(58, $usuario_lancaCC);
            $statement->bindParam(59, $usuario_avatar);
            $statement->bindParam(60, $usuario_background);
            $statement->bindParam(61, $cod_beneficiario);
            $statement->bindParam(62, $almoxarifado);
            $statement->bindParam(63, $tecnico);
            $statement->bindParam(64, $email_usuario);
            $statement->bindParam(65, $chavepix);
            $statement->bindParam(66, $nomeresponsavel);
            $statement->bindParam(67, $numerodocresponsavel);
            $statement->bindParam(68, $foneresponsavel);
            $statement->bindParam(69, $arquivo_base64);
            $statement->bindParam(70, $usuario_emaicorp);
            $statement->bindParam(71, $usuario_fonecomercial);
            $statement->bindparam(72, $usuario_nomerecado);
            $statement->bindParam(73, $id_usuario);
            $statement->execute();

            $sql = "SELECT COUNT(*) as total FROM " . $_SESSION['BASE'] . ".ficha_saude WHERE fs_CodUsuario = '".$_parametros['id-usuario']."'";
            $statement = $pdo->query($sql);
            $result = $statement->fetch();
            $total = $result['total'];
            
            if ($total == 0) {
                   
                           $tipo_sanguineo        = $_parametros['tiposanguineo-usuario'];
    $tipo_alergia          = $_parametros['alergia-usuario'];
    $fs_usomedicamento       = isset($_parametros['usoMedicamentos']) && $_parametros['usoMedicamentos'] !== "" ? (int)$_parametros['usoMedicamentos'] : null;
    $medicamento_controlado = $_parametros['usoMedicamento-usuario'];
    $medicamento_dor       = $_parametros['medicamento-usuario'];
    $problema_saude        = $_parametros['problemasSaude-usuario'];
    $ctt_emergencia_nome   = $_parametros['nomecontatoemergencia-usuario'];
    $ctt_emergencia_telefone = $_parametros['numerocontatoemergencia-usuario'];
    $fs_fumante = isset($_parametros['fumante-usuario']) && $_parametros['fumante-usuario'] !== ""? $_parametros['fumante-usuario'] : 0;
    $fs_convenio           = $_parametros['convenio-usuario'];
    $fs_acompmedicamento   = isset($_parametros['acompanhamentoMedico']) && $_parametros['acompanhamentoMedico'] !== "" ? $_parametros['acompanhamentoMedico'] : 0;
    $fs_examemedicamento   = isset($_parametros['examesPeriodicos']) && $_parametros['examesPeriodicos'] !== "" ? $_parametros['examesPeriodicos'] : 0;
    $id_usuario            = $_parametros['id-usuario'];

                        


        $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".ficha_saude (
                fs_CodUsuario,
                tipo_sanguineo,
                tipo_alergia,
                medicamento_controlado,
                medicamento_dor,
                problema_saude,
                ctt_emergencia_nome,
                ctt_emergencia_telefone,
                fs_fumante,
                fs_convenio,
                fs_usomedicamento,
                fs_acompmedicamento,
                fs_examemedicamento
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
       $statement->bindParam(1, $id_usuario);
       $statement->bindParam(2, $tipo_sanguineo);
    $statement->bindParam(3, $tipo_alergia);
    $statement->bindParam(4, $medicamento_controlado); 
    $statement->bindParam(5, $medicamento_dor);
    $statement->bindParam(6, $problema_saude);
    $statement->bindParam(7, $ctt_emergencia_nome);
    $statement->bindParam(8, $ctt_emergencia_telefone);
    $statement->bindParam(9, $fs_fumante);
    $statement->bindParam(10, $fs_convenio);
    $statement->bindParam(11, $fs_usomedicamento);
    $statement->bindParam(12, $fs_acompmedicamento);
    $statement->bindParam(13, $fs_examemedicamento);
  
                                
 
                            $statement->execute();
                        }
                        else {
                            $tipo_sanguineo          = $_parametros['tiposanguineo-usuario'];
    $tipo_alergia            = $_parametros['alergia-usuario'];
   $medicamento_controlado       = $_parametros['usoMedicamento-usuario'];
    $medicamento_dor         = $_parametros['medicamento-usuario'];
    $problema_saude          = $_parametros['problemasSaude-usuario'];
    $ctt_emergencia_nome     = $_parametros['nomecontatoemergencia-usuario'];
    $ctt_emergencia_telefone = $_parametros['numerocontatoemergencia-usuario'];
    $fs_fumante = isset($_parametros['fumante-usuario']) && $_parametros['fumante-usuario'] !== ""? $_parametros['fumante-usuario'] : 0;
    $fs_convenio             = $_parametros['convenio-usuario'];
    $fs_acompmedicamento     = $_parametros['acompanhamentoMedico'];
    $fs_examemedicamento     = $_parametros['examesPeriodicos'];
    $fs_usomedicamento       = isset($_parametros['usoMedicamentos']) && $_parametros['usoMedicamentos'] !== "" ? (int)$_parametros['usoMedicamentos'] : null;
    $id_usuario              = $_parametros['id-usuario'];
        
               

    $statement = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".ficha_saude SET 
                tipo_sanguineo = ?,
                tipo_alergia = ?,
                medicamento_controlado = ?,
                medicamento_dor = ?,
                problema_saude = ?,
                ctt_emergencia_nome = ?,
                ctt_emergencia_telefone = ?,
                fs_fumante = ?,
                fs_convenio = ?,
                fs_usomedicamento = ?,
                fs_acompmedicamento = ?,
                fs_examemedicamento = ?
                WHERE fs_CodUsuario = ?");
    $statement->bindParam(1, $tipo_sanguineo);
    $statement->bindParam(2, $tipo_alergia);
    $statement->bindParam(3, $medicamento_controlado);
    $statement->bindParam(4, $medicamento_dor);
    $statement->bindParam(5, $problema_saude);
    $statement->bindParam(6, $ctt_emergencia_nome);
    $statement->bindParam(7, $ctt_emergencia_telefone);
    $statement->bindParam(8, $fs_fumante);
    $statement->bindParam(9, $fs_convenio);
    $statement->bindParam(10, $fs_usomedicamento);
    $statement->bindParam(11, $fs_acompmedicamento);
    $statement->bindParam(12, $fs_examemedicamento);
    $statement->bindParam(13, $id_usuario);

    $statement = $pdo->prepare($sql);
                        $statement->execute();
}
        
                       
     

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
          

$nome_usuario = $_parametros['nome-usuario'] ?? null;
$apelido_usuario = $_parametros['apelido-usuario'] ?? null;
$funcao_usuario = $_parametros['funcao-usuario'] ?? null;
$funcao_usuario2 = $_parametros['funcao-usuario2'] ?? null;
$login_usuario = $_parametros['login-usuario'] ?? null;
$ativo_usuario = $_parametros['ativo-usuario'] ?? null;

$entradam_usuario = $_parametros['entradam-usuario'] ?? null;
$saidam_usuario = $_parametros['saidam-usuario'] ?? null;
$entradat_usuario = $_parametros['entradat-usuario'] ?? null;
$saidat_usuario = $_parametros['saidat-usuario'] ?? null;
$entradas_usuario = $_parametros['entradas-usuario'] ?? null;
$saidas_usuario = $_parametros['saidas-usuario'] ?? null;
$acessoexterno_usuario = $_parametros['acessoexterno-usuario'] ?? null;
$comissao_usuario = $_parametros['comissao-usuario'] ?? null;
$perfil_usuario = $_parametros['perfil-usuario'] ?? null;
$cnh_usuario = $_parametros['cnh-usuario'] ?? null;
$dtcnh_usuario = $_parametros['dtcnh-usuario'] ?? null;
$tcnh_usuario = $_parametros['tcnh-usuario'] ?? null;
$empresa_usuario = $_parametros['empresa-usuario'] ?? null;
$endereco_usuario = $_parametros['endereco-usuario'] ?? null;
$bairro_usuario = $_parametros['bairro-usuario'] ?? null;
$cidade_usuario = $_parametros['cidade-usuario'] ?? null;
$cep_usuario = $_parametros['cep-usuario'] ?? null;
$estado_usuario = $_parametros['estado-usuario'] ?? null;
$fixo_usuario = $_parametros['fixo-usuario'] ?? null;
$celular_usuario = $_parametros['celular-usuario'] ?? null;
$sexo_usuario = $_parametros['sexo-usuario'] ?? null;
$escolaridade_usuario = $_parametros['escolaridade-usuario'] ?? null;
$cpf_usuario = $_parametros['cpf-usuario'] ?? null;
$rg_usuario = $_parametros['rg-usuario'] ?? null;
$estadocivil_usuario = $_parametros['estadocivil-usuario'] ?? null;
$ctpsnum = $_parametros['ctpsnum'] ?? null;
$ctpsserie = $_parametros['ctpsserie'] ?? null;
$dtadmissao_usuario = $_parametros['dtadmissao-usuario'] ?? null;
$dtdemissao_usuario = $_parametros['dtdemissao-usuario'] ?? null;
$salario_usuario = $_parametros['salario-usuario'] ?? null;
$mae_usuario = $_parametros['mae-usuario'] ?? null;
$pai_usuario = $_parametros['pai-usuario'] ?? null;
$desconto_usuario = $_parametros['desconto-usuario'] ?? null;
$dtini1_usuario = $_parametros['dtini1-usuario'] ?? null;
$dtfim1_usuario = $_parametros['dtfim1-usuario'] ?? null;
$dtini2_usuario = $_parametros['dtini2-usuario'] ?? null;
$dtfim2_usuario = $_parametros['dtfim2-usuario'] ?? null;
$dtini3_usuario = $_parametros['dtini3-usuario'] ?? null;
$dtfim3_usuario = $_parametros['dtfim3-usuario'] ?? null;
$nascimento_usuario = $_parametros['nascimento-usuario'] ?? null;
$endereconum_usuario = $_parametros['endereconum-usuario'] ?? null;
$dtadv1_usuario = $_parametros['dtadv1-usuario'] ?? null;
$dtadv2_usuario = $_parametros['dtadv2-usuario'] ?? null;
$dtadv3_usuario = $_parametros['dtadv3-usuario'] ?? null;
$ctpspis = $_parametros['ctpspis'] ?? null;
$banco_usuario = $_parametros['banco-usuario'] ?? null;
$agencia_usuario = $_parametros['agencia-usuario'] ?? null;
$conta_usuario = $_parametros['conta-usuario'] ?? null;
$usuario_lancaCC = $_parametros['usuario_lancaCC'] ?? null;
$usuario_avatar = $_parametros['usuario_avatar'] ?? null;
$usuario_background = $_parametros['usuario_background'] ?? null;
$cod_beneficiario = $_parametros['cod_beneficiario'] ?? null;
$almoxarifado = $_parametros['almoxarifado'] ?? null;
$tecnico = $_parametros['tecnico'] ?? null;
$email_usuario = $_parametros['email-usuario'] ?? null;
$chavepix = $_parametros['chavepix'] ?? null;
$nomeresponsavel = $_parametros['nomeresponsavel'] ?? null;
$numerodocresponsavel = $_parametros['numerodocresponsavel'] ?? null;
$foneresponsavel = $_parametros['foneresponsavel'] ?? null;
$id_usuario = $_parametros['id-usuario'] ?? null;
$arquivo_base64 = $_parametros['usuario_img64'] ?? null;
$p100           = $_parametros['p100'] ?? null;
$usuario_emaicorp = $_parametros['usuario_emaicorp'] ?? null;
$usuario_fonecomercial = $_parametros['usuario_fonecomercial'] ?? null;
$usuario_nomerecado = $_parametros['usuario_nomerecado']?? null;


            $sql = "UPDATE " . $_SESSION['BASE'] . ".usuario SET usuario_NOME = ?, usuario_APELIDO = ?, usuario_CARGO = ?, usuario_LOGIN = ?, usuario_ATIVO = ?, usuario_HORARIO = ?, usuario_MANHAENTRADA = ?, usuario_MANHASAIDA = ?, usuario_TARDEENTRADA = ?, usuario_TARDESAIDA = ?, usuario_sabadoe = ?, usuario_sabados = ?, usuario_acessoexterno = ?, usuario_comissaotecnico = ?, usuario_perfil2 = ?, usuario_cnh = ?, usuario_datacnh = ?, tipo_cnh = ?, usuario_senha = ?, usuario_empresa = ?, usuario_endereco = ?, usuario_bairro = ?, usuario_cidade = ?, usuario_cep = ?, usuario_uf = ?, usuario_telefone = ?, usuario_celular = ?, usuario_sexo = ?, usuario_funcao = ?, usuario_escolaridade = ?, usuario_cpf = ?, usuario_rg = ?, usuario_estadocivil = ?, usuario_numero_carteira_trabalho = ?, usuario_serie_carteira_trabalho = ?, usuario_dataamissao = ?, usuario_datademissao = ?, usuario_salario = ?, usuario_nomemae = ?, usuario_nomepai = ?, Ind_auto_desc_venda = ?, data1_contrato_de = ?, data1_contrato_ate = ?, data2_contrato_de = ?, data2_contrato_ate = ?, data3_contrato_de = ?, data3_contrato_ate = ?, datanascimento = ?, usuario_endereconumero = ?, dtadv1 = ?, dtadv2 = ?, dtadv3 = ?, pis = ?, nomebanco = ?, agencia = ?, conta = ?, usuario_lancaCC = ?, usuario_avatar = ?, usuario_background = ?, cod_beneficiario = ?, usuario_almox = ?, usuario_tecnico = ?, usuario_email = ?, chavepix = ?, nomeresponsavel = ?, numerodocresponsavel = ?, foneresponsavel = ?, usuario_img64 = ?, p100 = ?, usuario_emaicorp = ?, usuario_fonecomercial = ?, usuario_nomerecado = ? WHERE usuario_CODIGOUSUARIO = ?";
            
            $statement = $pdo->prepare($sql);
            
            $statement->bindParam(1, $nome_usuario);    
            $statement->bindParam(2, $apelido_usuario);
            $statement->bindParam(3, $funcao_usuario2);
            $statement->bindParam(4, $login_usuario);
            $statement->bindParam(5, $ativo_usuario);
            $statement->bindParam(6, $hdias_usuario);
            $statement->bindParam(7, $entradam_usuario);
            $statement->bindParam(8, $saidam_usuario);
            $statement->bindParam(9, $entradat_usuario);
            $statement->bindParam(10, $saidat_usuario);
            $statement->bindParam(11, $entradas_usuario);
            $statement->bindParam(12, $saidas_usuario);
            $statement->bindParam(13, $acessoexterno_usuario);
            $statement->bindParam(14, $comissao_usuario);
            $statement->bindParam(15, $perfil_usuario);
            $statement->bindParam(16, $cnh_usuario);
            $statement->bindParam(17, $dtcnh_usuario);
            $statement->bindParam(18, $tcnh_usuario);
            $statement->bindParam(19, $senhaponto_usuario);
            $statement->bindParam(20, $empresa_usuario);
            $statement->bindParam(21, $endereco_usuario);
            $statement->bindParam(22, $bairro_usuario);
            $statement->bindParam(23, $cidade_usuario);
            $statement->bindParam(24, $cep_usuario);
            $statement->bindParam(25, $estado_usuario);
            $statement->bindParam(26, $fixo_usuario);
            $statement->bindParam(27, $celular_usuario);
            $statement->bindParam(28, $sexo_usuario);
            $statement->bindParam(29, $funcao_usuario);
            $statement->bindParam(30, $escolaridade_usuario);
            $statement->bindParam(31, $cpf_usuario);
            $statement->bindParam(32, $rg_usuario);
            $statement->bindParam(33, $estadocivil_usuario);
            $statement->bindParam(34, $ctpsnum);
            $statement->bindParam(35, $ctpsserie);
            $statement->bindParam(36, $dtadmissao_usuario);
            $statement->bindParam(37, $dtdemissao_usuario);
            $statement->bindParam(38, $salario_usuario);
            $statement->bindParam(39, $mae_usuario);
            $statement->bindParam(40, $pai_usuario);
            $statement->bindParam(41, $desconto_usuario);
            $statement->bindParam(42, $dtini1_usuario);
            $statement->bindParam(43, $dtfim1_usuario);
            $statement->bindParam(44, $dtini2_usuario);
            $statement->bindParam(45, $dtfim2_usuario);
            $statement->bindParam(46, $dtini3_usuario);
            $statement->bindParam(47, $dtfim3_usuario);
            $statement->bindParam(48, $nascimento_usuario);
            $statement->bindParam(49, $endereconum_usuario);
            $statement->bindParam(50, $dtadv1_usuario);
            $statement->bindParam(51, $dtadv2_usuario);
            $statement->bindParam(52, $dtadv3_usuario);
            $statement->bindParam(53, $ctpspis);
            $statement->bindParam(54, $banco_usuario);
            $statement->bindParam(55, $agencia_usuario);
            $statement->bindParam(56, $conta_usuario);
            $statement->bindParam(57, $usuario_lancaCC);
            $statement->bindParam(58, $usuario_avatar);
            $statement->bindParam(59, $usuario_background);
            $statement->bindParam(60, $cod_beneficiario);
            $statement->bindParam(61, $almoxarifado);
            $statement->bindParam(62, $tecnico);
            $statement->bindParam(63, $email_usuario);
            $statement->bindParam(64, $chavepix);
            $statement->bindParam(65, $nomeresponsavel);
            $statement->bindParam(66, $numerodocresponsavel);
            $statement->bindParam(67, $foneresponsavel);
            $statement->bindParam(68, $arquivo_base64);           
            $statement->bindParam(69, $p100);
            $statement->bindParam(70, $usuario_emaicorp);
            $statement->bindParam(71, $usuario_fonecomercial);
            $statement->bindParam(72, $usuario_nomerecado);
            $statement->bindParam(73, $id_usuario);
            $statement->execute();
            
                      
 
            

        $sql = "SELECT COUNT(*) as total FROM " . $_SESSION['BASE'] . ".ficha_saude WHERE fs_CodUsuario = '".$_parametros['id-usuario']."'";
            $statement = $pdo->query($sql);
            $result = $statement->fetch();
            $total = $result['total'];
            
            if ($total == 0) {
                  
                                                
                             $tipo_sanguineo        = $_parametros['tiposanguineo-usuario'];
                            $tipo_alergia          = $_parametros['alergia-usuario'];
                            $fs_usomedicamento       = isset($_parametros['usoMedicamentos']) && $_parametros['usoMedicamentos'] !== "" ? (int)$_parametros['usoMedicamentos'] : null;
                             $medicamento_controlado = $_parametros['usoMedicamento-usuario'];
                            $medicamento_dor       = $_parametros['medicamento-usuario'];
                            $problema_saude        = $_parametros['problemasSaude-usuario'];
                            $ctt_emergencia_nome   = $_parametros['nomecontatoemergencia-usuario'];
                            $ctt_emergencia_telefone = $_parametros['numerocontatoemergencia-usuario'];
                            $fs_fumante = isset($_parametros['fumante-usuario']) && $_parametros['fumante-usuario'] !== ""? $_parametros['fumante-usuario'] : 0;
                            $fs_convenio           = $_parametros['convenio-usuario'];
                            $fs_acompmedicamento   = isset($_parametros['acompanhamentoMedico']) && $_parametros['acompanhamentoMedico'] !== "" ? $_parametros['acompanhamentoMedico'] : 0;
                            $fs_examemedicamento   = isset($_parametros['examesPeriodicos']) && $_parametros['examesPeriodicos'] !== "" ? $_parametros['examesPeriodicos'] : 0;
                            $id_usuario            = $_parametros['id-usuario'];


                           $sql = "INSERT INTO " . $_SESSION['BASE'] . ".ficha_saude (
                fs_CodUsuario,
                tipo_sanguineo,
                tipo_alergia,
                medicamento_controlado,
                medicamento_dor,
                problema_saude,
                ctt_emergencia_nome,
                ctt_emergencia_telefone,
                fs_fumante,
                fs_convenio,
                fs_usomedicamento,
                fs_acompmedicamento,
                fs_examemedicamento
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


                            $statement = $pdo->prepare($sql);


                            $statement = $pdo->prepare($sql);
    $statement->bindParam(1, $id_usuario);
    $statement->bindParam(2, $tipo_sanguineo);
    $statement->bindParam(3, $tipo_alergia);
    $statement->bindParam(4, $medicamento_controlado); 
    $statement->bindParam(5, $medicamento_dor);
    $statement->bindParam(6, $problema_saude);
    $statement->bindParam(7, $ctt_emergencia_nome);
    $statement->bindParam(8, $ctt_emergencia_telefone);
    $statement->bindParam(9, $fs_fumante);
    $statement->bindParam(10, $fs_convenio);
    $statement->bindParam(11, $fs_acompmedicamento);
    $statement->bindParam(12, $fs_examemedicamento);
    $statement->bindParam(13, $fs_usomedicamento);


                            $statement->execute();
                        }
                        else {
    $tipo_sanguineo          = $_parametros['tiposanguineo-usuario'];
    $tipo_alergia            = $_parametros['alergia-usuario'];
    $medicamento_controlado       = $_parametros['usoMedicamento-usuario'];
    $medicamento_dor         = $_parametros['medicamento-usuario'];
    $problema_saude          = $_parametros['problemasSaude-usuario'];
    $ctt_emergencia_nome     = $_parametros['nomecontatoemergencia-usuario'];
    $ctt_emergencia_telefone = $_parametros['numerocontatoemergencia-usuario'];
    $fs_fumante = isset($_parametros['fumante-usuario']) && $_parametros['fumante-usuario'] !== ""? $_parametros['fumante-usuario'] : 0;

    $fs_convenio             = $_parametros['convenio-usuario'];
    $fs_acompmedicamento     = $_parametros['acompanhamentoMedico'];
    $fs_examemedicamento     = $_parametros['examesPeriodicos'];
    $fs_usomedicamento       = isset($_parametros['usoMedicamentos']) && $_parametros['usoMedicamentos'] !== "" ? (int)$_parametros['usoMedicamentos'] : null;
    $id_usuario              = $_parametros['id-usuario'];

    $sql = "UPDATE " . $_SESSION['BASE'] . ".ficha_saude SET 
                tipo_sanguineo = ?,
                tipo_alergia = ?,
                medicamento_controlado = ?,
                medicamento_dor = ?,
                problema_saude = ?,
                ctt_emergencia_nome = ?,
                ctt_emergencia_telefone = ?,
                fs_fumante = ?,
                fs_convenio = ?,
                fs_usomedicamento = ?,
                fs_acompmedicamento = ?,
                fs_examemedicamento = ?
            WHERE fs_CodUsuario = ?";

                                $statement = $pdo->prepare($sql);
    $statement->bindParam(1, $tipo_sanguineo);
    $statement->bindParam(2, $tipo_alergia);
    $statement->bindParam(3, $medicamento_controlado);
    $statement->bindParam(4, $medicamento_dor);
    $statement->bindParam(5, $problema_saude);
    $statement->bindParam(6, $ctt_emergencia_nome);
    $statement->bindParam(7, $ctt_emergencia_telefone);
    $statement->bindParam(8, $fs_fumante);
    $statement->bindParam(9, $fs_convenio);
    $statement->bindParam(10, $fs_usomedicamento);
    $statement->bindParam(11, $fs_acompmedicamento);
    $statement->bindParam(12, $fs_examemedicamento);
    $statement->bindParam(13, $id_usuario);

                                $statement->execute();
                                }

                             

                   
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
                            <h2>Cadastro atualizado! </h2>
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
          $statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".ficha_saude WHERE fs_CodUsuario = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();

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