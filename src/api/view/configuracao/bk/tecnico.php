<?php include("../../api/config/iconexao.php");
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >

<?php require_once('navigatorbar.php');

use Database\MySQL;

$pdo = MySQL::acessabd();
$id = $_chaveid;

$consulta = "SELECT *,DATE_FORMAT(data1_contrato_de, '%Y-%m-%d') AS data1_contrato_ded
,DATE_FORMAT(data1_contrato_ate, '%Y-%m-%d') AS data1_contrato_ated
,DATE_FORMAT(data2_contrato_de, '%Y-%m-%d') AS data2_contrato_ded
,DATE_FORMAT(data2_contrato_ate, '%Y-%m-%d') AS data2_contrato_ated
,DATE_FORMAT(dtadv1, '%Y-%m-%d') AS dtadv1_ded
,DATE_FORMAT(dtadv2, '%Y-%m-%d') AS dtadv2_ded
,DATE_FORMAT(dtadv3, '%Y-%m-%d') AS dtadv3_ded
,DATE_FORMAT(data3_contrato_de, '%Y-%m-%d') AS data3_contrato_ded
,DATE_FORMAT(data3_contrato_ate, '%Y-%m-%d') AS data3_contrato_ated
,DATE_FORMAT(datanascimento, '%Y-%m-%d') AS dtnascimento
 FROM " .  $_SESSION['BASE'] . ".usuario 
 WHERE usuario_CODIGOUSUARIO = '$id'  
  ORDER BY usuario_NOME";

$statement = $pdo->query($consulta);
$rst = $statement->fetch();

$usuario_base64 = $rst["usuario_img64"];

if($rst["usuario_background"] == ""){
	$usuario_background = "#00a8e6";
}else{
	$usuario_background = $rst["usuario_background"];
}
if($rst["usuario_avatar"] == ""){
	$usuario_avatar = "0017.png";
}else{
	$usuario_avatar = $rst["usuario_avatar"];
}
?>
<style>
.circle-image { 
  border-radius: 50%; 
  overflow: hidden; 
  width: 180px; 
  height: 180px; 
  margin-left:24%;

} 
.circle-image img { 
  width: 100%; 
  height: 100%; 
  margin-top:11px;
  
}
</style>
<div class="wrapper">
    <div class="container">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-xs-12">
                <h4 class="page-title m-t-15">Cadastro de Funcionário</h4>
                <p class="text-muted page-title-alt">Cadastre seus funcionários, informe dados cadastrais e parametros de acesso.</p>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-color panel-custom">
                <div class="card-box">
                    <div class="panel-body">
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-21" data-toggle="tab" aria-expanded="true">Dados de Acesso</a>
                            </li>                            						
                            <li class="">
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="false">Dados Cadastrais</a>
                            </li>
                            <li class="">
                                <a href="#navpills-31" data-toggle="tab" aria-expanded="false">Dados Contratuais</a>
                            </li>
                            <li class="">
                                <a href="#navpills-41" data-toggle="tab" aria-expanded="false">Avatar</a>
                            </li>                           

                        </ul>
                        <?php $_nomeform = empty($id) ? "form-inclui" : "form-altera"?>
                        <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="<?=empty($id) ? "form-inclui" : "form-altera"?>" id="<?=empty($id) ? "form-inclui" : "form-altera"?>">
                            <div class="tab-content br-n pn">		
                            <div id="navpills-21" class="tab-pane active">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="login">Login <strong class="text-danger">*</strong>:</label>
                                            <input type="text" name="login-usuario" class="form-control" id="login" value="<?=$rst["usuario_LOGIN"]?>" >
                                            
                                            
                                        </div>
                    
                                        <div class="form-group col-md-4">
                                            <label for="senha">Senha<strong class="text-danger">*</strong>:</label>
                                            <input type="password" name="senha-usuario" class="form-control" id="senha" value="" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="ativo"><b class="text-danger">Usuário Ativo:</b></label>
                                            <select name="ativo-usuario" id="ativo" class="form-control">
                                                <option value="Sim"<?=$rst["usuario_ATIVO"] == "Sim" ? "selected" : ""?>>Sim</option>
                                                <option value="Nao"<?=$rst["usuario_ATIVO"] == "Nao" ? "selected" : ""?>>Não</option>
                                                <option value="bloc"<?=$rst["usuario_ATIVO"] == "bloc" ? "selected" : ""?>>Bloqueado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="acessoexterno">Apelido<strong class="text-danger">*</strong></label>
                                            <input type="text" name="apelido-usuario" class="form-control" id="apelido-usuario" value="<?=$rst["usuario_APELIDO"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="acessoexterno">Acesso fora de horário</label>
                                            <select name="acessoexterno-usuario" id="acessoexterno" class="form-control">
                                                <option value="Sim" <?=$rst["usuario_acessoexterno"] == "Sim" ? " selected" : ""?>>Sim</option>
                                                <option value="Nao" <?=$rst["usuario_acessoexterno"] == "Nao" ? " selected" : ""?>>Não</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="desconto">Libera Desconto:</label>
                                            <select name="desconto-usuario" id="desconto" class="form-control">
                                                <option value="-1"<?=$rst["Ind_auto_desc_venda"] == "-1" ? " selected" : ""?>>Sim</option>
                                                <option value="0"<?=$rst["Ind_auto_desc_venda"] == "-1" ?: " selected"?>>Não</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="perfil">Perfil:</label>
                                            <select name="perfil-usuario" id="perfil" class="form-control">
                                            <?php
                                            $consulta_perfil = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".permissao ORDER BY permissao_descricao");
                                            $result_perfil = $consulta_perfil->fetchAll();

                                            foreach ($result_perfil as $row)
                                            {
                                                ?>
                                                <option value="<?=$row["permissao_id"]?>"<?=$row["permissao_id"] == $rst["usuario_perfil2"] ? " selected" : ""?>><?=utf8_encode($row["permissao_descricao"])?></option>
                                                <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Empresa:</label>
                                            <select name="empresa-usuario" id="empresa" class="form-control">
                                                <option value="0">TODAS</option>
                                                <?php
                                                $consulta_empresa = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".empresa ORDER BY empresa_nome");
                                                $result_empresa = $consulta_empresa->fetchAll();

                                                foreach ($result_empresa as $row)
                                                {
                                                    ?>
                                                    <option value="<?=$row["empresa_id"]?>"<?=$row["empresa_id"] == $rst["usuario_empresa"] ? " selected" : ""?>><?=utf8_encode($row["empresa_nome"])?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <?php 
                                                $almox = $rst["usuario_almox"]; 
                                                if($almox == 0 ) { $almox = 1;}
                                            ?>
                                            <label for="">Almoxarifado</label>
                                            <select name="almoxarifado" id="almoxarifado" class="form-control">
                                                <option value="">NENHUM</option>
                                            <?php
                                                $consulta_almox = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".almoxarifado ORDER BY Descricao");
                                                $result_almox = $consulta_almox->fetchAll();

                                                foreach ($result_almox as $row)
                                                {
                                                    ?>
                                                    <option value="<?=$row["Codigo_Almox"]?>"<?=$row["Codigo_Almox"] == $rst["usuario_almox"] ? " selected" : ""?>><?=$row["Descricao"];?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                          
       
                                        </div>
                                        <div class="form-group col-md-4">
                                            <?php 
                                                $tecnico = $rst["usuario_tecnico"];                                                 
                                            ?>
                                            <label for="">Assessor/Técnico</label>
                                            <select name="tecnico" id="tecnico" class="form-control">
                                            <?php if($tecnico == "1") { ?>
                                                <option value="1" selected="selected" >Sim</option>
                                                <option value="0" >Não</option>
                                                <?php } else { ?> 
                                                <option value="1" >Sim</option>
                                                <option value="0" selected="selected" >Não</option>
                                                <?php } ?> 
                                            </select>
                                            
                                          
       
                                        </div>
                                        <!-----
                                        <div class="form-group col-md-6">
                                            <label for="senhaponto">Senha do Ponto:</label>
                                            <input type="password" name="senhaponto-usuario" class="form-control" id="senhaponto" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="hdias">Horas por Dia:</label>
                                            <input type="number" name="hdias-usuario" id="hdias" class="form-control" value="<?=$rst["usuario_HORARIO"]?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="manhae">Manhã Entrada:</label>
                                            <input type="time" name="entradam-usuario" class="form-control" id="manhae" value="<?=$rst["usuario_MANHAENTRADA"]?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="tardee">Tarde Entrada:</label>
                                            <input type="time" name="entradat-usuario" class="form-control" id="tardee" value="<?=$rst["usuario_TARDEENTRADA"]?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="sabadoe">Sábado Entrada:</label>
                                            <input type="time" name="entradas-usuario" class="form-control" id="sabadoe" value="<?=$rst["usuario_sabadoe"]?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="manhas">Manhã Saída:</label>
                                            <input type="time" name="saidam-usuario" class="form-control" id="manhas" value="<?=$rst["usuario_MANHASAIDA"]?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="tardes">Tarde Saída:</label>
                                            <input type="time" name="saidat-usuario" class="form-control" id="tardes" value="<?=$rst["usuario_TARDESAIDA"]?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="sabados">Sábado Saída:</label>
                                            <input type="time" name="saidas-usuario" class="form-control" id="sabados" value="<?=$rst["usuario_sabados"]?>">
                                        </div>--->
                                    </div>
                                </div>                                						
                                <div id="navpills-11" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-xs-12">
                                            <label for="nome">Nome Completo<strong class="text-danger">*</strong>:</label>
                                            <input type="hidden" name="id-usuario" value="<?=($id) ?: "" ?>">
                                            <input type="text" name="nome-usuario" class="form-control" id="nome" value="<?=$rst["usuario_NOME"];?>" >
                                        </div>
                                        <div class="form-group col-md-2  col-xs-6">
                                            <label for="nascimt">Data de Nascimento:</label>
                                            <input type="date" name="nascimento-usuario" class="form-control" id="nascimt" value="<?=$rst["dtnascimento"]?>">
                                        </div>
                                        <div class="form-group col-md-2  col-xs-6" >
                                            <label for="rg">RG:</label>
                                            <input type="text" name="rg-usuario" class="form-control" id="rg" value="<?=$rst["usuario_rg"];?>">
                                        </div>
                                        <div class="form-group col-md-2  col-xs-6">
                                            <label for="cpf">CPF:</label>
                                            <input type="text" name="cpf-usuario" class="form-control" id="cpf" value="<?=$rst["usuario_cpf"];?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3 col-xs-6">
                                            <label for="cnh-usuario">Número CNH:</label>
                                            <input type="text" name="cnh-usuario" class="form-control" id="cnh-usuario" value="<?=$rst["usuario_cnh"];?>">
                                        </div>
                                        <div class="form-group col-md-3 col-xs-6">
                                            <label for="dtcnh-usuario">Validade CNH:</label>
                                            <input type="date" name="dtcnh-usuario" class="form-control" id="dtcnh-usuario" value="<?=$rst["usuario_datacnh"];?>">
                                        </div>
                                        <div class="form-group col-md-3 col-xs-6">
                                            <label for="tcnh-usuario">Tipo CNH:</label>
                                            <select name="tcnh-usuario" class="form-control" id="tcnh-usuario">
                                                <option value="N"<?=$rst["tipo_cnh"] == "N" ? " selected" : ""?>>Nenhuma</option>
                                                <option value="A"<?=$rst["tipo_cnh"] == "A" ? " selected" : ""?>>A</option>
                                                <option value="AB"<?=$rst["tipo_cnh"] == "AB" ? " selected" : ""?>>AB</option>
                                                <option value="B"<?=$rst["tipo_cnh"] == "B" ? " selected" : ""?>>B</option>
                                                <option value="C"<?=$rst["tipo_cnh"] == "C" ? " selected" : ""?>>C</option>
                                                <option value="D"<?=$rst["tipo_cnh"] == "E" ? " selected" : ""?>>D</option>
                                                <option value="E"<?=$rst["tipo_cnh"] == "D" ? " selected" : ""?>>E</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 col-xs-6">
                                            <label for="tsexo">Sexo:</label>
                                            <select name="sexo-usuario"  id="tsexo" class="form-control">
                                                <option value=""<?=$rst["usuario_sexo"] == "" ? " selected" : ""?>>Selecione</option>
                                                <option value="M"<?=$rst["usuario_sexo"] == "M" ? " selected" : ""?>>Masculino</option>
                                                <option value="F"<?=$rst["usuario_sexo"] == "F" ? " selected" : ""?>>Feminino</option>
                                            </select>
                                        </div>
                                        </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="testadocivil">Estado Civil:</label>
                                            <select name="estadocivil-usuario"  id="testadocivil" class="form-control">
                                                <option value=""<?=$rst["usuario_estadocivil"] == "" ? " selected" : ""?>>Selecione</option>
                                                <option value="Solteiro"<?=$rst["usuario_estadocivil"] == "Solteiro" ? " selected" : ""?>>Solteiro</option>
                                                <option value="Casado"<?=$rst["usuario_estadocivil"] == "Casado" ? " selected" : ""?>>Casado</option>
                                                <option value="Viuvo"<?=$rst["usuario_estadocivil"] == "Viuvo" ? " selected" : ""?>>Viuvo</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="tescolaridade">Escolaridade:</label>
                                            <select name="escolaridade-usuario"  id="tescolaridade" class="form-control">
                                                <option value=""<?=$rst["usuario_escolaridade"] == "" ? " selected" : ""?>>Selecione</option>
                                                <option value="Ensino fundamental"<?=$rst["usuario_escolaridade"] == "fundamental" ? " selected" : ""?>>Ensino Fundamental</option>
                                                <option value="Ensino Medio Completo"<?=$rst["usuario_escolaridade"] == "Ensino Medio Completo" ? " selected" : ""?>>Ensino Médido Completo</option>
                                                <option value="Ensino Medio Incompleto"<?=$rst["usuario_escolaridade"] == "Ensino Medio Incompleto" ? " selected" : ""?>>Ensino Médido Incompleto</option>
                                                <option value="Superior Completo"<?=$rst["usuario_escolaridade"] == "Superior Completo" ? " selected" : ""?>>Superior Completo</option>
                                                <option value="Superior Incompleto"<?=$rst["usuario_escolaridade"] == "Superior Incompleto" ? " selected" : ""?>>Superior Incompleto</option>
                                                <option value="Graduacao"<?=$rst["usuario_escolaridade"] == "Graduacao" ? " selected" : ""?>>Pós Graduado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="nomemae">Nome da Mãe:</label>
                                            <input name="mae-usuario" type="text" class="form-control" id="nomemae" value="<?=$rst["usuario_nomemae"]?>" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="nomepai">Nome do Pai:</label>
                                            <input name="pai-usuario" type="text" class="form-control" id="nomepai" value="<?=$rst["usuario_nomepai"]?>" />
                                        </div>
                                        </div>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="cep">CEP:</label>
                                            <input type="text" name="cep-usuario"  class="form-control" id="cep" value="<?=$rst["usuario_cep"];?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="endereco">Endereço:</label>
                                            <input type="text" name="endereco-usuario"  class="form-control" id="rua" value="<?=$rst["usuario_endereco"];?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="endereconum">N°:</label>
                                            <input type="text" name="endereconum-usuario"  class="form-control" id="endereconum" value="<?=$rst["usuario_endereconumero"];?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="bairro">Bairro:</label>
                                            <input type="text" name="bairro-usuario"  class="form-control" id="bairro" value="<?=$rst["usuario_bairro"];?>">
                                        </div>
                                        </div>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="cidade">Cidade:</label>
                                            <input type="text" name="cidade-usuario"  class="form-control" id="cidade" value="<?=$rst["usuario_cidade"];?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="estado">Estado:</label>
                                            <select name="estado-usuario" id="uf" class="form-control">
                                                <option value=""<?=$rst["usuario_uf"] == "" ? " selected" : ""?>>Selecione</option>
                                            <?php
                                            $consultaUF = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".estado ORDER BY nome_estado");
                                            $resultUF = $consultaUF->fetchAll();

                                            foreach ($resultUF as $row)
                                            {
                                                ?>
                                                <option value="<?=$row["estado_sigla"]?>"<?=$row["estado_sigla"] == $rst["usuario_uf"] ? " selected" : ""?>><?=($row["nome_estado"])?></option>
                                                <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="telfix">Telefone Fixo:</label>
                                            <input type="text" name="fixo-usuario"  class="form-control" id="telfix" value="<?=$rst["usuario_telefone"];?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="telcel">Telefone Celular:</label>
                                            <input type="text" name="celular-usuario"  class="form-control" id="telcel" value="<?=$rst["usuario_celular"];?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="telcel"><b class="text-danger">Email:</b>: <code>* importante p/ registro ponto</code></label>
                                            <input type="text" name="email-usuario"  class="form-control" id="email-usuario" value="<?=$rst["usuario_email"];?>">
                                        </div>
                                    </div>
                                </div>
                                <div id="navpills-31" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="tipocontrato">Tipo Contratação:</label>
                                            <select name="tipocontrato"  id="tipocontrato" class="form-control">
                                                <option value=""<?=$rst["C"] == "" ? " selected" : ""?>>Selecione</option>
                                                <option value="1"<?=$rst["p100"] == "1" ? " selected" : ""?>>Jovem Aprendiz</option>
                                                <option value="2"<?=$rst["p100"] == "2" ? " selected" : ""?>>Estagiário</option>
                                                <option value="3"<?=$rst["p100"] == "3" ? " selected" : ""?>>CLT</option>
                                                <option value="4"<?=$rst["p100"] == "4" ? " selected" : ""?>>MEI</option>
                                                <option value="5"<?=$rst["p100"] == "5" ? " selected" : ""?>>TERCEIRIZADO</option>
                                            
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="ctpsserie">CTPS Série:</label>
                                            <input id="ctpsserie" name="ctpsserie" type="text" class="form-control" value="<?=$rst["usuario_serie_carteira_trabalho"]?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="ctpsnum">CTPS Número:</label>
                                            <input id="ctpsnum" name="ctpsnum" type="text" class="form-control" value="<?=$rst["usuario_numero_carteira_trabalho"]?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="ctpspis">PIS:</label>
                                            <input id="ctpspis" name="ctpspis" type="text" class="form-control" value="<?=$rst["pis"]?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="funcao">Função:</label>
                                            <input id="funcao" name="funcao-usuario" type="text" class="form-control" value="<?=$rst["usuario_funcao"]?>">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="salario">Salário:</label>
                                            <input id="salario" name="salario-usuario" type="text" class="form-control" value="<?=$rst["usuario_salario"]?>">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="comissaotecnico">Comissão Geral (%):</label>
                                            <input type="tel" name="comissao-usuario" class="form-control" id="comissao-usuario" value="<?=number_format($rst["usuario_comissaotecnico"],2,',','.');?>" onKeyPress="return(moeda(this,'.',',',event));" placeholder="0,00">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="usuario_lancaCC">Comissão Fin. C/C</label>
                                            <select name="usuario_lancaCC" id="	usuario_lancaCC" class="form-control">
												<option value="0" <?php if($rst["usuario_lancaCC"] == 0){ echo 'selected="selected"';} ?>>Não</option>
												<option value="-1" <?php if($rst["usuario_lancaCC"] == -1){ echo 'selected="selected"';} ?>>Sim</option>
											</select>
                                        </div>									
                                        <div class="form-group col-md-2">
                                            <label for="dtadmissao">Data de Admissão:</label>
                                            <input id="dtadmissao" name="dtadmissao-usuario" type="date" class="form-control" value="<?=$rst["usuario_dataamissao"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtdemissao">Data de Demissão:</label>
                                            <input id="dtdemissao" name="dtdemissao-usuario" type="date" class="form-control" value="<?=$rst["usuario_datademissao"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="nomebanco">Banco:</label>
                                            <input id="nomebanco" name="banco-usuario" type="text" class="form-control" value="<?=$rst["nomebanco"]?>">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="agencia">Agência:</label>
                                            <input id="agencia" name="agencia-usuario" type="text" class="form-control" value="<?=$rst["agencia"]?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="conta">Conta:</label>
                                            <input id="conta" name="conta-usuario" type="text" class="form-control" value="<?=$rst["conta"]?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="conta">Chave Pix:</label>
                                            <input id="chavepix" name="chavepix" type="text" class="form-control" value="<?=$rst["chavepix"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtini1">Início 1° Contrato:</label>
                                            <input id="dtini1" name="dtini1-usuario" type="date" class="form-control" value="<?=$rst["data1_contrato_ded"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtini2">Início 2° Contrato:</label>
                                            <input id="dtini2" name="dtini2-usuario" type="date" class="form-control" value="<?=$rst["data2_contrato_ded"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtini3">Início 3° Contrato:</label>
                                            <input id="dtini3" name="dtini3-usuario" type="date" class="form-control" value="<?=$rst["data3_contrato_ded"]?>">
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="dtfim1">Responsável Legal:</label>
                                            <input id="nomeresponsavel" name="nomeresponsavel" type="text" class="form-control" value="<?=$rst["nomeresponsavel"]?>">
                                        </div>
                                      


                                        <div class="form-group col-md-2">
                                            <label for="dtfim1">Fim 1° Contrato:</label>
                                            <input id="dtfim1" name="dtfim1-usuario" type="date" class="form-control" value="<?=$rst["data1_contrato_ated"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtfim2">Fim 2° Contrato:</label>
                                            <input id="dtfim2" name="dtfim2-usuario" type="date" class="form-control" value="<?=$rst["data2_contrato_ated"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtfim3">Fim 3° Contrato:</label>
                                            <input id="dtfim3" name="dtfim3-usuario" type="date" class="form-control" value="<?=$rst["data3_contrato_ated"]?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="dtfim2">N° Documento Responsável:</label>
                                            <input id="numerodocresponsavel" name="numerodocresponsavel" type="text" class="form-control" value="<?=$rst["numerodocresponsavel"]?>">
                                        </div>
                                       
                                        <div class="form-group col-md-2">
                                            <label for="dtadv1">Data 1° Advertência:</label>
                                            <input id="dtadv1" name="dtadv1-usuario" type="date" class="form-control" value="<?=$rst["dtadv1_ded"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtadv2">Data 2° Advertência:</label>
                                            <input id="dtadv2" name="dtadv2-usuario" type="date" class="form-control" value="<?=$rst["dtadv2_ded"]?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="dtadv3">Data 3° Advertência:</label>
                                            <input id="dtadv3" name="dtadv3-usuario" type="date" class="form-control" value="<?=$rst["dtadv3_ded"]?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="dtfim3">Telefone p/ Contato Responsável:</label>
                                            <input id="foneresponsavel" name="foneresponsavel" type="text" class="form-control" value="<?=$rst["foneresponsavel"]?>">
                                        </div>
                                        
                                    </div>
                                </div>
                                <div id="navpills-41" class="tab-pane">
									
									<div class="row">
										<div class="col-sm-3">
											<div style="width:100%; height:140px;">
												<div class="circle-image" style="background-color:<?=$usuario_background;?>;" id="divAvatar">
                                                <?php if($usuario_base64 == ""){      ?>
													<img src="assets/images/avatar/<?=$usuario_avatar;?>" width="100px">
                                                    <?php } else { ?>
                                                        <img style="margin-top:0px" src="<?=$usuario_base64;?>" width="100px"/>  
                                                    <?php }?>
												</div>		
											</div>												
										</div>
										<div class="col-sm-3">
										<?php 
											$sql="select * from " . $_SESSION['BASE'] . ".categoria_cor";
											$stm= $pdo->query($sql);
                                            while($value = $stm->fetch(PDO::FETCH_OBJ)){
												?>
												<div style="width:30px; height:30px; margin:5px; float:left; cursor:pointer; border-radius:50%; background-color:<?=$value->codigo_cor;?>" onclick="cor('<?=$value->codigo_cor;?>')"></div>
												<?php
											}
											
										
										?>
										</div>
										<div class="col-sm-6">
											<div style="overflow-y:scroll; height:200px; ">
											<?php
                                                                              
												$pasta = "assets/images/avatar/"; 
												$arquivos = glob("$pasta{*.png}", GLOB_BRACE);
												foreach($arquivos as $idx => $img){
													$explode = explode("/",$img);
												   echo '<img src="'.$img.'"  width="100px" onclick="img('."'$img'".', '."'".$explode[3]."'".')">';
												}
                                                                                      
                                                ?>
                                              
                                       
											</div>
										</div>
										<div class="form-group col-md-2">
                                            <label for="arquivo-logo">Arquivo:</label>
                                            <input type="file" name="arquivo-foto" id="arquivo-foto" class="filestyle">
                                            <span style="color:red; font-size:10px;">imagens no fomato PNG/JPG tamanho máximo 250x250</span><br>
                                                                                      
                                        </div>
                                        <div class="form-group col-md-1" style="margin-top: 27px;">
                                       
                                              <button type="button" class="btn btn-info waves-effect waves-light m-l-5" id="previsualizar" name="previsualizar">Visualizar</button>
                                        </div>
									</div>
									<input type="hidden" value="<?=$rst["usuario_avatar"];?>" id="usuario_avatar" name="usuario_avatar">
									<input type="hidden" value="<?=$rst["usuario_background"];?>" id="usuario_background" name="usuario_background">
                                    <input type="hidden" value="<?=$usuario_base64;?>" id="usuario_base64" name="usuario_base64">
                                 
									<br>
									<br>
									<br>
									<hr>
								</div>                               
                                <!--
                                <div id="navpills-41" class="tab-pane">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="panel panel-color panel-custom">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title text-center"><b>Acesso Rápido</b></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="p01" class="control-label col-md-4">Pesquisar Produtos</label>
                                                            <div class="col-md-5">
                                                                <select name="p01" id="p01" class="form-control">
                                                                    <option value="0"<?=$rst["p01"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p01"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p01" class="control-label col-md-4">Pesquisar Clientes</label>
                                                            <div class="col-md-5">
                                                                <select name="p02" id="p02" class="form-control">
                                                                    <option value="0"<?=$rst["p02"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p02"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p03" class="control-label col-md-4">Contas a Pagar</label>
                                                            <div class="col-md-5">
                                                                <select name="p03" id="p03" class="form-control">
                                                                    <option value="0"<?=$rst["p03"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p03"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p04" class="control-label col-md-4">Debitos Clientes</label>
                                                            <div class="col-md-5">
                                                                <select name="p04" id="p04" class="form-control">
                                                                    <option value="0"<?=$rst["p04"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p04"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p05" class="control-label col-md-4">Consultar Vendas</label>
                                                            <div class="col-md-5">
                                                                <select name="p05" id="p05" class="form-control">
                                                                    <option value="0"<?=$rst["p05"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p05"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p06" class="control-label col-md-4">Fornecedores</label>
                                                            <div class="col-md-5">
                                                                <select name="p06" id="p06" class="form-control">
                                                                    <option value="0"<?=$rst["p06"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p06"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p07" class="control-label col-md-4">Resumo de Vendas por Grupo</label>
                                                            <div class="col-md-5">
                                                                <select name="p07" id="p07" class="form-control">
                                                                    <option value="0"<?=$rst["p07"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p07"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p08" class="control-label col-md-4">Caixas</label>
                                                            <div class="col-md-5">
                                                                <select name="p08" id="p08" class="form-control">
                                                                    <option value="0"<?=$rst["p08"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p08"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p09" class="control-label col-md-4">Entradas de Notas</label>
                                                            <div class="col-md-5">
                                                                <select name="p09" id="p09" class="form-control">
                                                                    <option value="0"<?=$rst["p09"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p09"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p10" class="control-label col-md-4">Resumo Financeiro</label>
                                                            <div class="col-md-5">
                                                                <select name="p10" id="p10" class="form-control">
                                                                    <option value="0"<?=$rst["p10"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p10"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="panel panel-color panel-custom">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title text-center"><b>Menu Vendas</b></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="p11" class="control-label col-md-4">Clientes e Pedidos</label>
                                                            <div class="col-md-5">
                                                                <select name="p11" id="p11" class="form-control">
                                                                    <option value="0"<?=$rst["p11"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p11"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p12" class="control-label col-md-4">Consulta Pedidos</label>
                                                            <div class="col-md-5">
                                                                <select name="p12" id="p12" class="form-control">
                                                                    <option value="0"<?=$rst["p12"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p12"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p13" class="control-label col-md-4">Consulta Meus Pedidos</label>
                                                            <div class="col-md-5">
                                                                <select name="p13" id="p13" class="form-control">
                                                                    <option value="0"<?=$rst["p13"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p13"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p12" class="control-label col-md-4">Resumo Vendas por Grupo</label>
                                                            <div class="col-md-5">
                                                                <select name="p14" id="p14" class="form-control">
                                                                    <option value="0"<?=$rst["p14"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p14"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p15" class="control-label col-md-4">Resumo Vendas por Linha</label>
                                                            <div class="col-md-5">
                                                                <select name="p15" id="p15" class="form-control">
                                                                    <option value="0"<?=$rst["p15"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p15"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p47" class="control-label col-md-4">Resumo Vendas por Vendedor</label>
                                                            <div class="col-md-5">
                                                                <select name="p47" id="p47" class="form-control">
                                                                    <option value="0"<?=$rst["p47"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p47"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p16" class="control-label col-md-4">Resumo T. de Vendas por Vendedor</label>
                                                            <div class="col-md-5">
                                                                <select name="p16" id="p16" class="form-control">
                                                                    <option value="0"<?=$rst["p16"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p16"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="panel panel-color panel-custom">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title text-center"><b>Menu Estoque</b></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="p17" class="control-label col-md-4">Produtos</label>
                                                            <div class="col-md-5">
                                                                <select name="p17" id="p17" class="form-control">
                                                                    <option value="0"<?=$rst["p17"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p17"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p18" class="control-label col-md-4">Requisição de Estoque</label>
                                                            <div class="col-md-5">
                                                                <select name="p18" id="p18" class="form-control">
                                                                    <option value="0"<?=$rst["p18"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p18"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p19" class="control-label col-md-4">Etiqueta</label>
                                                            <div class="col-md-5">
                                                                <select name="p19" id="p19" class="form-control">
                                                                    <option value="0"<?=$rst["p19"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p19"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p20" class="control-label col-md-4">Movimentação de Produtos</label>
                                                            <div class="col-md-5">
                                                                <select name="p20" id="p20" class="form-control">
                                                                    <option value="0"<?=$rst["p20"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p20"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p21" class="control-label col-md-4">Inventário</label>
                                                            <div class="col-md-5">
                                                                <select name="p21" id="p21" class="form-control">
                                                                    <option value="0"<?=$rst["p21"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p21"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p22" class="control-label col-md-4">Arquivo de Balança</label>
                                                            <div class="col-md-5">
                                                                <select name="p22" id="p22" class="form-control">
                                                                    <option value="0"<?=$rst["p22"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p22"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p23" class="control-label col-md-4">Curva ABC</label>
                                                            <div class="col-md-5">
                                                                <select name="p23" id="p23" class="form-control">
                                                                    <option value="0"<?=$rst["p23"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p23"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p25" class="control-label col-md-4">Relatórios</label>
                                                            <div class="col-md-5">
                                                                <select name="p25" id="p25" class="form-control">
                                                                    <option value="0"<?=$rst["p25"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p25"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="panel panel-color panel-custom">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title text-center"><b>Menu Financeiro</b></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="p08" class="control-label col-md-4">Caixas</label>
                                                            <div class="col-md-5">
                                                                <select name="p26" id="p26" class="form-control">
                                                                    <option value="0"<?=$rst["p26"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p26"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p27" class="control-label col-md-4">Debitos de Clientes</label>
                                                            <div class="col-md-5">
                                                                <select name="p27" id="p27" class="form-control">
                                                                    <option value="0"<?=$rst["p27"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p27"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p28" class="control-label col-md-4">Contas a Pagar</label>
                                                            <div class="col-md-5">
                                                                <select name="p28" id="p28" class="form-control">
                                                                    <option value="0"<?=$rst["p28"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p28"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p29" class="control-label col-md-4">Contas a Receber</label>
                                                            <div class="col-md-5">
                                                                <select name="p29" id="p29" class="form-control">
                                                                    <option value="0"<?=$rst["p29"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p29"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p30" class="control-label col-md-4">Resumo Financeiro</label>
                                                            <div class="col-md-5">
                                                                <select name="p30" id="p30" class="form-control">
                                                                    <option value="0"<?=$rst["p30"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p30"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p31" class="control-label col-md-4">Relatorios Contas a Pagar e Receber</label>
                                                            <div class="col-md-5">
                                                                <select name="p31" id="p31" class="form-control">
                                                                    <option value="0"<?=$rst["p31"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p31"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="panel panel-color panel-custom">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title text-center"><b>Menu Administrativo</b></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="p32" class="control-label col-md-4">Notas de Entrada</label>
                                                            <div class="col-md-5">
                                                                <select name="p32" id="p32" class="form-control">
                                                                    <option value="0"<?=$rst["p32"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p32"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p107" class="control-label col-md-4">Liberar Nota já Conferida</label>
                                                            <div class="col-md-5">
                                                                <select name="nf_liberaconferida" id="p107" class="form-control">
                                                                    <option value="0"<?=$rst["nf_liberaconferida"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["nf_liberaconferida"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p33" class="control-label col-md-4">Nota Fiscal Eletrônica</label>
                                                            <div class="col-md-5">
                                                                <select name="p33" id="p33" class="form-control">
                                                                    <option value="0"<?=$rst["p33"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p33"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p34" class="control-label col-md-4">Compras</label>
                                                            <div class="col-md-5">
                                                                <select name="p34" id="p34" class="form-control">
                                                                    <option value="0"<?=$rst["p34"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p34"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p35" class="control-label col-md-4">Produtos para Compra</label>
                                                            <div class="col-md-5">
                                                                <select name="p35" id="p35" class="form-control">
                                                                    <option value="0"<?=$rst["p35"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p35"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p36" class="control-label col-md-4">Controle de Frota</label>
                                                            <div class="col-md-5">
                                                                <select name="p36" id="p36" class="form-control">
                                                                    <option value="0"<?=$rst["p36"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p36"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="pxx" class="control-label col-md-4">Comissão</label>
                                                            <div class="col-md-5">
                                                                <select name="p40" id="p40" class="form-control">
                                                                    <option value="0"<?=$rst["p40"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p40"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p51" class="control-label col-md-4">Controle de Ponto</label>
                                                            <div class="col-md-5">
                                                                <select name="p51" id="p51" class="form-control">
                                                                    <option value="0"<?=$rst["p51"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p51"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="panel panel-color panel-custom">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title text-center"><b>Menu Gerencial</b></h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="p37" class="control-label col-md-4">Dados Cadastral</label>
                                                            <div class="col-md-5">
                                                                <select name="p37" id="p37" class="form-control">
                                                                    <option value="0"<?=$rst["p37"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p37"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p38" class="control-label col-md-4">Funcionários e Login</label>
                                                            <div class="col-md-5">
                                                                <select name="p38" id="p38" class="form-control">
                                                                    <option value="0"<?=$rst["p38"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p38"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="p39" class="control-label col-md-4">Parâmetros</label>
                                                            <div class="col-md-5">
                                                                <select name="p39" id="p39" class="form-control">
                                                                    <option value="0"<?=$rst["p39"] == 0 ? " selected" : ""?>>Liberado</option>
                                                                    <option value="-1"<?=$rst["p39"] == -1 ? " selected" : ""?>>Bloqueado</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                                <div class="form-group text-left">
                                    <button type="submit" class="btn btn-success waves-effect waves-light" id="alteraCadastro"  data-toggle="modal" data-target="#custom-modal-result">Salvar</button>
                                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" id="voltar" type="button">Voltar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-footer">
                    <p class="text-danger">
                        Campos marcados com * são de preenchimento obrigatório.
                    </p>
                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="result">
                    <div class="bg-icon pull-request">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform"  value="">
</form>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/routes.js"></script>

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>

<script type="text/javascript">

    $('#voltar').click(function() {
        var $_keyid = "tecnicoLista_00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    });

    $('#previsualizar').click(function() {
       
        var $_keyid = "_Vc00019";              
            var form_data = new FormData(document.getElementById("<?=$_nomeform;?>"));
            
            
                    $.ajax({
                        url: 'acaoDocIMG.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function(retorno) {
                           
                            let start = 0;
                            let end = 22;
                            let str = retorno.substring(start, end);
                            $("#divAvatar").html('<img style="margin-top:0px" src="'+retorno+'" width="200px">');  
                            if( str == 'data:image/jpeg;base64'){
                                $('#usuario_base64').val(retorno);
                            }else{
                                alert(retorno);
                             
                            }
                           
                                        
                        }
                    });
         
           
          
    });
    

    $('#form-inclui').submit(function (e){
        e.preventDefault();

        var $_keyid = "acaoTecnico_00001";
        var dados = $(this).serializeArray();
        dados = JSON.stringify(dados);
             
        _carregandoA('#custom-modal-result');    
        
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                $("#custom-modal-result").html(result);
        });
    });

    $('#form-altera').submit(function (e){
        var $_keyid =   "acaoTecnico_00001";
        var dados = $(this).serializeArray();
        dados = JSON.stringify(dados);      
      
       _carregandoA('#custom-modal-result');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){       
               
                $("#custom-modal-result").html(result);
        });
    });

    function _fechar() {
        var $_keyid = "tecnicoLista_00001";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    };
	
		
	function img(imagem,nome){
		$("#divAvatar").html('<img src="'+imagem+'" width="100px">');
		$("#usuario_avatar").val(nome);
        $('#usuario_base64').val("");
	}
	
	function cor(codigo){
		$("#usuario_background").val(codigo);
		$("#divAvatar").css('backgroundColor',codigo);
	}

    
    function _carregandoA(_idmodal) {

    $(_idmodal).html('' +
        '<div class="bg-icon pull-request" >' +
        '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
        '<h4 class="text-center">Aguarde, atualizando dados...</h4>' +
        '</div>');

    }

    
    //FORMATA MOEDA FINALIZA PAGAMENTO
	function moeda(a, e, r, t) {
		let n = ""
		  , h = j = 0
		  , u = tamanho2 = 0
		  , l = ajd2 = ""
		  , o = window.Event ? t.which : t.keyCode;
		if (13 == o || 8 == o)
			return !0;
		if (n = String.fromCharCode(o),
		-1 == "0123456789".indexOf(n))
			return !1;
		for (u = a.value.length,
		h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
			;
		for (l = ""; h < u; h++)
			-1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
		if (l += n,
		0 == (u = l.length) && (a.value = ""),
		1 == u && (a.value = "0" + r + "0" + l),
		2 == u && (a.value = "0" + r + l),
		u > 2) {
			for (ajd2 = "",
			j = 0,
			h = u - 3; h >= 0; h--)
				3 == j && (ajd2 += e,
				j = 0),
				ajd2 += l.charAt(h),
				j++;
			for (a.value = "",
			tamanho2 = ajd2.length,
			h = tamanho2 - 1; h >= 0; h--)
				a.value += ajd2.charAt(h);
			a.value += r + l.substr(u - 2, u)
		}
		return !1
	}
</script>
</body>
</html>