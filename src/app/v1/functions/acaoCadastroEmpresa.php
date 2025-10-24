<?php
session_start();
include("../../../api/config/iconexao.php");
require '../../../api/vendor/autoload.php';

use Database\MySQL;
//use Functions\Movimento;
//use Functions\NFeFocus;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

empty($_POST['senha']) ?: $_POST['senha'] = md5($_POST['senha']);
$_POST['juro'] = LimpaVariavel($_POST['juro']);
$_POST['multa'] = LimpaVariavel($_POST['multa']);
$_POST['habilita-nfe'] = empty($_POST['habilita-nfe']) ? false : true;
$_POST['habilita-nfce'] = empty($_POST['habilita-nfce']) ? false : true;
$_POST['habilita-nfse'] = empty($_POST['habilita-nfse']) ? false : true;
$_POST['cnpj'] = preg_replace('/[^0-9]/', '', $_POST['cnpj']);
$_POST['inscricao-municipal'] = preg_replace('/[^0-9]/', '', $_POST['inscricao-municipal']);
$_POST['inscricao-estadual'] = preg_replace('/[^0-9]/', '', $_POST['inscricao-estadual']);
$_FILES['arquivo-certificado'] = !empty($_FILES['arquivo-certificado']) ? base64_encode(file_get_contents($_FILES['arquivo-certificado']["tmp_name"])) : '';
$_FILES['arquivo-logo'] = !empty($_FILES['arquivo-logo']) ? base64_encode(file_get_contents($_FILES['arquivo-logo']["tmp_name"])) : '';
$situacao = '4';
$habilita_nfse = TRUE;



/*
 * Altera Cadastro
 * */


if($_POST['acao'] == 1) {
    try {
        /*
        $consulta = $pdo->query("SELECT cod_cidade FROM minhaos_cep.cidade WHERE estado = '".$_POST['uf']."' AND cidade = '".$_POST['cidade']."'");
        $codigo_municipio = ($consulta->fetch(\PDO::FETCH_OBJ))->cod_cidade;
*/
        $logo = $_FILES['arquivo-logo'];
        $certificado = $_FILES['arquivo-certificado'];
        $certificadoSenha = $_POST['senha-certificado'];

        $aliquota = $_POST['aliquota'];
        $aliquota = str_replace(",","",$aliquota);
/*
     //$logo == "" or
        if( $certificado == "" ){
            //pega img e certificado atual
            $sql="select * from".$_SESSION['BASE'].".empresa_dados WHERE id = '".$_SESSION['BASE_ID']."'";
            $stm = $pdo->prepare($sql);	
		    $stm->execute();
            if($stm->rowCount() > 0){
                while($result = $stm->fetch(PDO::FETCH_OBJ)){
                    if($logo == ""){
                        $logo = $result->arquivo_logo_base64;
                    }
    
                    if($certificado == ""){
                        $certificado = $result->arquivo_certificado_base64;
                    }
                }
            }

        }
*/
        //atualiza cadastro
        $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_cadastro SET 
        razao_social = ?, 
        nome_fantasia = ?, 
        cnpj = ?, 
        regime_tributario = ?,
        inscricao_estadual = ?,
        inscricao_municipal = ?,
        email = ?, telefone = ?,
        logradouro = ?,
        numero = ?,
        complemento = ?,
        bairro = ?,
        cep = ?,
        municipio = ?, 
        uf = ?,
        codigo_municipio = ?,
        contato_nome = ?,
        contato_email = ?,
        contato_telefone = ?,
        dominio_site = ?, 
        usuario_site = ?,
        senha_site = ?,
        porta_site = ?,
        domingo_ini = ?,
        domingo_fim = ?,
        segunda_ini = ?,
        segunda_fim = ?,
        terca_ini = ?,
        terca_fim = ?,
        quarta_ini = ?,
        quarta_fim = ?,
        quinta_ini = ?,
        quinta_fim = ?,
        sexta_ini = ?,
        sexta_fim = ?,
        sabado_ini = ?,
        sabado_fim = ?,
        c_uf = ?
        WHERE id = '".$_SESSION['BASE_ID']."'");
        $statement->bindParam(1, $_POST['razao']);
        $statement->bindParam(2, $_POST['fantasia']);
        $statement->bindParam(3, $_POST['cnpj']);
        $statement->bindParam(4, $_POST['regime-tributario']);
        $statement->bindParam(5, $_POST['inscricao-estadual']);
        $statement->bindParam(6, $_POST['inscricao-municipal']);
        $statement->bindParam(7, $_POST['email']);
        $statement->bindParam(8, $_POST['telefone']);
        $statement->bindParam(9, $_POST['rua']);
        $statement->bindParam(10, $_POST['numero']);
        $statement->bindParam(11, $_POST['complemento']);
        $statement->bindParam(12, $_POST['bairro']);
        $statement->bindParam(13, $_POST['cep']);
        $statement->bindParam(14, $_POST['cidade']);
        $statement->bindParam(15, $_POST['uf']);
        $statement->bindParam(16, $codigo_municipio);
        $statement->bindParam(17, $_POST['contato-nome']);
        $statement->bindParam(18, $_POST['contato-email']);
        $statement->bindParam(19, $_POST['contato-telefone']);
        $statement->bindParam(20, $_POST['dominio-site']);
        $statement->bindParam(21, $_POST['usuario-site']);
        $statement->bindParam(22, $_POST['senha-site']);
        $statement->bindParam(23, $_POST['porta-site']);
        $statement->bindParam(24, $_POST['domingo_inicio']);
        $statement->bindParam(25, $_POST['domingo_fim']);
        $statement->bindParam(26, $_POST['segunda_inicio']);
        $statement->bindParam(27, $_POST['segunda_fim']);
        $statement->bindParam(28, $_POST['terca_inicio']);
        $statement->bindParam(29, $_POST['terca_fim']);
        $statement->bindParam(30, $_POST['quarta_inicio']);
        $statement->bindParam(31, $_POST['quarta_fim']);
        $statement->bindParam(32, $_POST['quinta_inicio']);
        $statement->bindParam(33, $_POST['quinta_fim']);
        $statement->bindParam(34, $_POST['sexta_inicio']);
        $statement->bindParam(35, $_POST['sexta_fim']);
        $statement->bindParam(36, $_POST['sabado_inicio']);
        $statement->bindParam(37, $_POST['sabado_fim']);    
        $statement->bindParam(38, $_POST['c_uf']);     
        $statement->execute();


        //parametros
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".parametro  SET
        RAZAO_SOCIAL = ?,
        NOME_FANTASIA = ?,
        ENDERECO = ?,
        NumRua = ?,
        BAIRRO = ?,
        Cep = ?,
        CIDADE = ?,
        UF = ?, 
        TELEFONE = ?,
        des_Comis_Fatur = ?,
        EMAIL = ?,
        Ult_Cod_Peca = ?,
        Num_Pedido_Venda = ?,
        codigopermissao = ?,
        codigopermissaocancelamento = ?,
        extra_A_label = ?,
        extra_B_label = ?, 
        Msg_A = ?,
        TEXTO_NF_ADICIONAL = ?,
        label_tab1 = ?,
        label_tab2 = ?,
        label_tab3 = ?,
        label_tab4 = ?,
        label_tab5 = ?,
        visualiza_tab1 = ?,
        visualiza_tab2 = ?,
        visualiza_tab3 = ?, 
        visualiza_tab4 = ?, 
        visualiza_tab5 = ?,
        banco = ?,
        agencia= ?,
        conta = ?,
        cedente = ?,
        sequencia = ?,
        carteira = ?,
        parametro_juro = ?,
        parametro_multa = ?");
        $statement->bindParam(1, $_POST['razao']);
        $statement->bindParam(2, $_POST['fantasia']);
        $statement->bindParam(3, $_POST['rua']);
        $statement->bindParam(4, $_POST['numero']);
        $statement->bindParam(5, $_POST['bairro']);
        $statement->bindParam(6, $_POST['cep']);
        $statement->bindParam(7, $_POST['cidade']);
        $statement->bindParam(8, $_POST['uf']);
        $statement->bindParam(9, $_POST['telefone']);
        $statement->bindParam(10, $_POST["taxa"]);
        $statement->bindParam(11, $_POST['email']);
        $statement->bindParam(12, $_POST['ano']);
        $statement->bindParam(13, $_POST["ultimaos"]);
        $statement->bindParam(14, $_POST["codigopermissao"]);
        $statement->bindParam(15, $_POST["codigopermissao2"]);
        $statement->bindParam(16, $_POST['extraA']);
        $statement->bindParam(17, $_POST['extraB']);
        $statement->bindParam(18, $_POST['msg']);
        $statement->bindParam(19, $_POST['msg2']);
        $statement->bindParam(20, $_POST['tab_1']);
        $statement->bindParam(21, $_POST['tab_2']);
        $statement->bindParam(22, $_POST['tab_3']);
        $statement->bindParam(23, $_POST['tab_4']);
        $statement->bindParam(24, $_POST['tab_5']);
        $statement->bindParam(25, $_POST['ind_tab1']);
        $statement->bindParam(26, $_POST['ind_tab2']);
        $statement->bindParam(27, $_POST['ind_tab3']);
        $statement->bindParam(28, $_POST['ind_tab4']);
        $statement->bindParam(29, $_POST['ind_tab5']);
        $statement->bindParam(30, $_POST['banco']);
        $statement->bindParam(31, $_POST["agencia"]);
        $statement->bindParam(32, $_POST["contacorrente"]);
        $statement->bindParam(33, $_POST['cedente']);
        $statement->bindParam(34, $_POST['sequencia']);
        $statement->bindParam(35, $_POST["carteira"]);
        $statement->bindParam(36, $_POST['juro']);
        $statement->bindParam(37, $_POST['multa']);
      //  $statement->execute();        


        //EMPRESA DADOS
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".empresa_dados SET
        item_lista_servico = ?,
        aliquota_nota = ?,
        cpf_cnpj_contabilidade = ?,
        habilita_nfe = ?,
        habilita_nfce = ?,
        habilita_nfse = ?,
        csc_nfce_producao = ?,
        id_token_nfce_producao = ?,
        csc_nfce_homologacao = ?, 
        id_token_nfce_homologacao = ?,
        proximo_numero_nfe_producao = ?,
        proximo_numero_nfe_homologacao = ?,
        serie_nfe_producao = ?,
        serie_nfe_homologacao = ?,
        proximo_numero_nfse_producao = ?,
        proximo_numero_nfse_homologacao = ?,
        serie_nfse_producao = ?,
        serie_nfse_homologacao = ?, 
        mensagem_cupom = ?,
        mensagem_adicional_nfe = ?,
        login_prefeitura = ?,
        senha_prefeitura  = ?,
        proximo_numero_nfce_producao  = ?,
        proximo_numero_nfce_homologacao  = ?,
        serie_nfce_producao  = ?,
        serie_nfce_homologacao = ?,
        codigo_cnae = ?
        WHERE id = '".$_SESSION['BASE_ID']."'"); 
        $statement->bindParam(1, $_POST['item-servico']);
        $statement->bindParam(2, $aliquota);
        $statement->bindParam(3, $_POST['cpf-cnpj-contabilidade']);
        $statement->bindParam(4, $_POST['habilita-nfe']);
        $statement->bindParam(5, $_POST['habilita-nfce']);
        $statement->bindParam(6, $_POST['habilita-nfse']);
        $statement->bindParam(7, $_POST['csc-nfce-prod']);
        $statement->bindParam(8, $_POST['token-nfce-prod']);
        $statement->bindParam(9, $_POST['csc-nfce-hom']);
        $statement->bindParam(10, $_POST['token-nfce-hom']);
        $statement->bindParam(11, $_POST['proxima-nfe-prod']);
        $statement->bindParam(12, $_POST['proxima-nfe-hom']);
        $statement->bindParam(13, $_POST['serie-nfe-prod']);
        $statement->bindParam(14, $_POST['serie-nfe-hom']);
        $statement->bindParam(15, $_POST['proxima-nfse-prod']);
        $statement->bindParam(16, $_POST['proxima-nfse-hom']);
        $statement->bindParam(17, $_POST['serie-nfse-prod']);
        $statement->bindParam(18, $_POST['serie-nfse-hom']);
        $statement->bindParam(19, $_POST['msg']);
        $statement->bindParam(20, $_POST['msg2']);
        $statement->bindParam(21, $_POST['usuario-nfse']);
        $statement->bindParam(22, $_POST['senha-nfse']);
        $statement->bindParam(23, $_POST['proxima-nfce-prod']);
        $statement->bindParam(24, $_POST['proxima-nfce-hom']);
        $statement->bindParam(25, $_POST['serie-nfce-prod']);
        $statement->bindParam(26, $_POST['serie-nfce-hom']);
        $statement->bindParam(27, $_POST['cnae']);
      //  $statement->execute();

        //certificado
        if($certificado != ""){

            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".empresa SET
            arquivo_certificado_base64 = ?     ");
            $statement->bindParam(1, $certificado);    
            $statement->execute();   
        }
        //SENHA certificado
        if($certificadoSenha != ""){
            $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET
            senha_certificado = ?
            WHERE id = '".$_SESSION['BASE_ID']."'");
            $statement->bindParam(1, $certificadoSenha);    
         //   $statement->execute();           
        }

        //LOGO EMPRESA
        if($logo != ""){
            $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET
            arquivo_logo_base64 = ?
            WHERE id = '".$_SESSION['BASE_ID']."'");
            $statement->bindParam(1, $logo);    
          //  $statement->execute();           
        }



        //echo('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
      //  $NFeFocus = new NFeFocus($_SESSION['BASE_ID'], 'prd');
      //  $response = $NFeFocus->atualizaEmpresa();

        if (is_object($response)) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2><?php
                      
                       echo $response->body->erros[0]->mensagem
                    ?></h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        } else {
           
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                        <h3>Cadastro Atualizado!</h3>                     
                            <img src="assets/images/small/img_0008.png" alt="image" class="img-responsive center-block"  width="300px"/>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?php echo $e; echo "OPS !!! dados inesperado, contate o suporte"?></h2>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Libera Cadastro Colaborador
 */
elseif ($_POST['acao'] == 2) {
    try {
        $statement = $pdo->prepare("UPDATE bd_gestorpet.usuario SET usuario_colaborador = TRUE WHERE usuario_CODIGOUSUARIO = :id");
        $statement->bindParam(':id', $_POST['usuario-colaborador']);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Cadastro Habilitado!</h2>
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
                <h2><?php echo $e; echo "OPS !!! dados inesperado, contate o suporte"?></h2>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Cadastra Colaborador
 */
elseif ($_POST['acao'] == 3) {
    try {
        $consulta = $pdo->query("SELECT id FROM bd_gestorpet.empresa_cadastro WHERE cnpj = '".$_POST['cnpj']."' OR razao_social = '".$_POST['razao']."'");
        $id = ($consulta->fetch(\PDO::FETCH_OBJ))->id;

        if (empty($id)) {
            $consulta = $pdo->query("SELECT cod_cidade FROM minhaos_cep.cidade WHERE estado = '".$_POST['uf']."' AND cidade = '".$_POST['cidade']."'");
            $codigo_municipio = ($consulta->fetch(\PDO::FETCH_OBJ))->cod_cidade;
            
            $statement = $pdo->prepare("INSERT INTO bd_gestorpet.empresa_cadastro (razao_social, nome_fantasia, cnpj, regime_tributario, inscricao_municipal, email, telefone, logradouro, numero, complemento, bairro, cep, municipio, 
            uf, codigo_municipio, contato_nome, contato_email, contato_telefone, situacao, grupo_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
            $statement->bindParam(1, $_POST['razao']);
            $statement->bindParam(2, $_POST['fantasia']);
            $statement->bindParam(3, $_POST['cnpj']);
            $statement->bindParam(4, $_POST['regime-tributario']);
            $statement->bindParam(5, $_POST['inscricao-municipal']);
            $statement->bindParam(6, $_POST['email']);
            $statement->bindParam(7, $_POST['telefone']);
            $statement->bindParam(8, $_POST['rua']);
            $statement->bindParam(9, $_POST['numero']);
            $statement->bindParam(10, $_POST['complemento']);
            $statement->bindParam(11, $_POST['bairro']);
            $statement->bindParam(12, $_POST['cep']);
            $statement->bindParam(13, $_POST['cidade']);
            $statement->bindParam(14, $_POST['uf']);
            $statement->bindParam(15, $codigo_municipio);
            $statement->bindParam(16, $_POST['contato-nome']);
            $statement->bindParam(17, $_POST['contato-email']);
            $statement->bindParam(18, $_POST['contato-telefone']);
            $statement->bindParam(19, $situacao);
            $statement->bindParam(20, $_SESSION['BASE_ID']);
            $statement->execute();
    
            $consulta = $pdo->query("SELECT id FROM bd_gestorpet.empresa_cadastro WHERE cnpj = '".$_POST['cnpj']."'");
            $id = ($consulta->fetch(\PDO::FETCH_OBJ))->id;
    
            if(!empty($_FILES['arquivo-certificado'] && !empty($_FILES['arquivo-logo']))) {
                $statement = $pdo->prepare("INSERT INTO bd_gestorpet.empresa_dados (id, item_lista_servico, aliquota_nota, cpf_cnpj_contabilidade, habilita_nfse, proximo_numero_nfse_producao, serie_nfse_producao, 
                login_prefeitura, senha_prefeitura, arquivo_certificado_base64, senha_certificado, arquivo_logo_base64) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
                $statement->bindParam(1, $id);
                $statement->bindParam(2, $_POST['item-servico']);
                $statement->bindParam(3, $_POST['aliquota']);
                $statement->bindParam(4, $_POST['cpf-cnpj-contabilidade']);
                $statement->bindParam(5, $habilita_nfse);
                $statement->bindParam(6, $_POST['proxima-nfse-prod']);
                $statement->bindParam(7, $_POST['serie-nfse-prod']);
                $statement->bindParam(8, $_POST['usuario-nfse']);
                $statement->bindParam(9, $_POST['senha-nfse']);
                $statement->bindParam(10, $_FILES['arquivo-certificado']);
                $statement->bindParam(11, $_POST['senha-certificado']);
                $statement->bindParam(12, $_FILES['arquivo-logo']);
                $statement->execute();
            }
            elseif (!empty($_FILES['arquivo-certificado'])) {
                $statement = $pdo->prepare("INSERT INTO bd_gestorpet.empresa_dados (id, item_lista_servico, aliquota_nota, cpf_cnpj_contabilidade, habilita_nfse, proximo_numero_nfse_producao, serie_nfse_producao, 
                login_prefeitura, senha_prefeitura, arquivo_certificado_base64, senha_certificado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
                $statement->bindParam(1, $id);
                $statement->bindParam(2, $_POST['item-servico']);
                $statement->bindParam(3, $_POST['aliquota']);
                $statement->bindParam(4, $_POST['cpf-cnpj-contabilidade']);
                $statement->bindParam(5, $habilita_nfse);
                $statement->bindParam(6, $_POST['proxima-nfse-prod']);
                $statement->bindParam(7, $_POST['serie-nfse-prod']);
                $statement->bindParam(8, $_POST['usuario-nfse']);
                $statement->bindParam(9, $_POST['senha-nfse']);
                $statement->bindParam(10, $_FILES['arquivo-certificado']);
                $statement->bindParam(11, $_POST['senha-certificado']);
                $statement->execute();
            }
            elseif (!empty($_FILES['arquivo-logo'])) {
                $statement = $pdo->prepare("INSERT INTO bd_gestorpet.empresa_dados (id, item_lista_servico, aliquota_nota, cpf_cnpj_contabilidade, habilita_nfse, proximo_numero_nfse_producao, serie_nfse_producao, 
                login_prefeitura, senha_prefeitura, arquivo_logo_base64) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
                $statement->bindParam(1, $id);
                $statement->bindParam(2, $_POST['item-servico']);
                $statement->bindParam(3, $_POST['aliquota']);
                $statement->bindParam(4, $_POST['cpf-cnpj-contabilidade']);
                $statement->bindParam(5, $habilita_nfse);
                $statement->bindParam(6, $_POST['proxima-nfse-prod']);
                $statement->bindParam(7, $_POST['serie-nfse-prod']);
                $statement->bindParam(8, $_POST['usuario-nfse']);
                $statement->bindParam(9, $_POST['senha-nfse']);
                $statement->bindParam(10, $_FILES['arquivo-logo']);
                $statement->execute();
            } else {
                $statement = $pdo->prepare("INSERT INTO bd_gestorpet.empresa_dados (id, item_lista_servico, aliquota_nota, cpf_cnpj_contabilidade, habilita_nfse, proximo_numero_nfse_producao, serie_nfse_producao, 
                login_prefeitura, senha_prefeitura) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
                $statement->bindParam(1, $id);
                $statement->bindParam(2, $_POST['item-servico']);
                $statement->bindParam(3, $_POST['aliquota']);
                $statement->bindParam(4, $_POST['cpf-cnpj-contabilidade']);
                $statement->bindParam(5, $habilita_nfse);
                $statement->bindParam(6, $_POST['proxima-nfse-prod']);
                $statement->bindParam(7, $_POST['serie-nfse-prod']);
                $statement->bindParam(8, $_POST['usuario-nfse']);
                $statement->bindParam(9, $_POST['senha-nfse']);
                $statement->execute();
            }
        }
/*
        $NFeFocus = new NFeFocus($id, 'prd');
        $response = $NFeFocus->cadastraEmpresa();
*/
        if (is_object($response)) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2><?php echo $response->body->erros[0]->mensagem ?></h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        } else {
            $statement = $pdo->prepare("UPDATE bd_gestorpet.usuario SET usuario_empresa = ?, usuario_colaborador = FALSE WHERE usuario_CODIGOUSUARIO = ?");
            $statement->bindParam(1, $id);
            $statement->bindParam(2, $_SESSION['IDUSER']);
            $statement->execute();
    
         //   $movimento = new Movimento;
          //  $movimento->cadastraMovimento(9, 'CADASTRO DE '.$_POST['fantasia'].'');
    
            $_SESSION['COLABORADOR_ID'] = $id;
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cadastro Realizado!</h2>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Altera Colaborador
 */
elseif ($_POST['acao'] == 4) {
    try {
        $consulta = $pdo->query("SELECT cod_cidade FROM minhaos_cep.cidade WHERE estado = '".$_POST['uf']."' AND cidade = '".$_POST['cidade']."'");
        $codigo_municipio = ($consulta->fetch(\PDO::FETCH_OBJ))->cod_cidade;
        
        $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_cadastro SET razao_social = ?, nome_fantasia = ?, cnpj = ?, regime_tributario = ?, inscricao_municipal = ?, email = ?, telefone = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cep = ?, municipio = ?, 
        uf = ?, codigo_municipio = ?, contato_nome = ?, contato_email = ?, contato_telefone = ? WHERE id = ?");
    
        $statement->bindParam(1, $_POST['razao']);
        $statement->bindParam(2, $_POST['fantasia']);
        $statement->bindParam(3, $_POST['cnpj']);
        $statement->bindParam(4, $_POST['regime-tributario']);
        $statement->bindParam(5, $_POST['inscricao-municipal']);
        $statement->bindParam(6, $_POST['email']);
        $statement->bindParam(7, $_POST['telefone']);
        $statement->bindParam(8, $_POST['rua']);
        $statement->bindParam(9, $_POST['numero']);
        $statement->bindParam(10, $_POST['complemento']);
        $statement->bindParam(11, $_POST['bairro']);
        $statement->bindParam(12, $_POST['cep']);
        $statement->bindParam(13, $_POST['cidade']);
        $statement->bindParam(14, $_POST['uf']);
        $statement->bindParam(15, $codigo_municipio);
        $statement->bindParam(16, $_POST['contato-nome']);
        $statement->bindParam(17, $_POST['contato-email']);
        $statement->bindParam(18, $_POST['contato-telefone']);
        $statement->bindParam(19, $_POST['empresa']);
        $statement->execute();

        if(!empty($_FILES['arquivo-certificado'] && !empty($_FILES['arquivo-logo']))) {
            $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET item_lista_servico = ?, aliquota_nota = ?, cpf_cnpj_contabilidade = ?, habilita_nfse = ?, proximo_numero_nfse_producao = ?, serie_nfse_producao = ?, 
            login_prefeitura = ?, senha_prefeitura = ?, arquivo_certificado_base64 = ?, senha_certificado = ?, arquivo_logo_base64 = ? WHERE id = ?");

            $statement->bindParam(1, $_POST['item-servico']);
            $statement->bindParam(2, $_POST['aliquota']);
            $statement->bindParam(3, $_POST['cpf-cnpj-contabilidade']);
            $statement->bindParam(4, $habilita_nfse);
            $statement->bindParam(5, $_POST['proxima-nfse-prod']);
            $statement->bindParam(6, $_POST['serie-nfse-prod']);
            $statement->bindParam(7, $_POST['usuario-nfse']);
            $statement->bindParam(8, $_POST['senha-nfse']);
            $statement->bindParam(9, $_FILES['arquivo-certificado']);
            $statement->bindParam(10, $_POST['senha-certificado']);
            $statement->bindParam(11, $_FILES['arquivo-logo']);
            $statement->bindParam(12, $_POST['empresa']);
            $statement->execute();
        }
        elseif (!empty($_FILES['arquivo-certificado'])) {
            $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET item_lista_servico = ?, aliquota_nota = ?, cpf_cnpj_contabilidade = ?, habilita_nfse = ?, proximo_numero_nfse_producao = ?, serie_nfse_producao = ?, 
            login_prefeitura = ?, senha_prefeitura = ?, arquivo_certificado_base64 = ?, senha_certificado = ? WHERE id = ?");
        
            $statement->bindParam(1, $_POST['item-servico']);
            $statement->bindParam(2, $_POST['aliquota']);
            $statement->bindParam(3, $_POST['cpf-cnpj-contabilidade']);
            $statement->bindParam(4, $habilita_nfse);
            $statement->bindParam(5, $_POST['proxima-nfse-prod']);
            $statement->bindParam(6, $_POST['serie-nfse-prod']);
            $statement->bindParam(7, $_POST['usuario-nfse']);
            $statement->bindParam(8, $_POST['senha-nfse']);
            $statement->bindParam(9, $_FILES['arquivo-certificado']);
            $statement->bindParam(10, $_POST['senha-certificado']);
            $statement->bindParam(11, $_POST['empresa']);
            $statement->execute();
        }
        elseif (!empty($_FILES['arquivo-logo'])) {
            $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET item_lista_servico = ?, aliquota_nota = ?, cpf_cnpj_contabilidade = ?, habilita_nfse = ?, proximo_numero_nfse_producao = ?, serie_nfse_producao = ?, 
            login_prefeitura = ?, senha_prefeitura = ?, arquivo_logo_base64 = ? WHERE id = ?");
            
            $statement->bindParam(1, $_POST['item-servico']);
            $statement->bindParam(2, $_POST['aliquota']);
            $statement->bindParam(3, $_POST['cpf-cnpj-contabilidade']);
            $statement->bindParam(4, $habilita_nfse);
            $statement->bindParam(5, $_POST['proxima-nfse-prod']);
            $statement->bindParam(6, $_POST['serie-nfse-prod']);
            $statement->bindParam(7, $_POST['usuario-nfse']);
            $statement->bindParam(8, $_POST['senha-nfse']);
            $statement->bindParam(9, $_FILES['arquivo-logo']);
            $statement->bindParam(10, $_POST['empresa']);
            $statement->execute();
        } else {
            $statement = $pdo->prepare("UPDATE bd_gestorpet.empresa_dados SET item_lista_servico = ?, aliquota_nota = ?, cpf_cnpj_contabilidade = ?, habilita_nfse = ?, proximo_numero_nfse_producao = ?, serie_nfse_producao = ?, login_prefeitura = ?, senha_prefeitura = ? WHERE id = ?");
        
            $statement->bindParam(1, $_POST['item-servico']);
            $statement->bindParam(2, $_POST['aliquota']);
            $statement->bindParam(3, $_POST['cpf-cnpj-contabilidade']);
            $statement->bindParam(4, $habilita_nfse);
            $statement->bindParam(5, $_POST['proxima-nfse-prod']);
            $statement->bindParam(6, $_POST['serie-nfse-prod']);
            $statement->bindParam(7, $_POST['usuario-nfse']);
            $statement->bindParam(8, $_POST['senha-nfse']);
            $statement->bindParam(9, $_POST['empresa']);
            $statement->execute();
        }

      //  $NFeFocus = new NFeFocus($_POST['empresa'], 'prd');
       // $response = $NFeFocus->atualizaEmpresa();

        if (is_object($response)) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2><?php echo $response->body->erros[0]->mensagem ?></h2>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Cadastro Atualizado!</h2>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}