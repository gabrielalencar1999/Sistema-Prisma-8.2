<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
header("Content-type: text/html; charset=utf-8");
require_once('../api/config/config.inc.php');
require '../api/vendor/autoload.php';

include("../api/config/conexaobase.php");
use Database\MySQL;
$pdo = MySQL::acessabd();

//Verificar codigo acesso
$codigo_p  =  strip_tags(trim($_POST["codigo_p"]));
$codigo_p =  htmlspecialchars($codigo_p, ENT_QUOTES, 'UTF-8');



$login_p  =  strip_tags(trim($_POST["login"]));
$login_p =  htmlspecialchars($login_p, ENT_QUOTES, 'UTF-8');


$_password  =  strip_tags(trim($_POST["password"]));
$_password =  htmlspecialchars($_password, ENT_QUOTES, 'UTF-8');
//$v1 = $_POST['v1'];
$submetido = "";

if(intval($codigo_p) < 8000 and $codigo_p != "7201") {   //$codigo_p != "7201" and
     //ecaminha para uol

     ?>
     <!DOCTYPE html>
    <html lang="en">
    
    <body >
    <form id="form1" name="form1" method="post" action="https://www.cliente.sistemaprisma.com.br/cliente.sistemaprisma.com.br/public_html/prismaloja/"   target="_self">		
       <input type="hidden"  name="codigo_p" id="codigo_p" value="<?=$codigo_p;?>";>
       
     
     </form>
    </body>
    </html>     
     <script>
        document.form1.submit();
     </script>
     <?php
      }else{
        // $codigo_p = $_POST['codigo_p'];
                
         $horabd = date ("H:i:s");
         //buscar dados da base informatica
         $consulta = "Select consumidor_base,Nome_Fantasia from info.consumidor where CODIGO_CONSUMIDOR = '$codigo_p'";        
         $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
         $row = mysqli_num_rows($executa);
         while($rstP = mysqli_fetch_array($executa))	{	
                  $_SESSION['CODIGOCLI'] = $codigo_p;
                  $_SESSION['BASE'] = $rstP['consumidor_base'];
                  $_SESSION['fantasia'] = $rstP['Nome_Fantasia'];
         }

     

                        $explode = explode(" ",$_SESSION['fantasia']);
                        $nomeFantasia = $explode[0].'<br>'.$explode[1].'<br>'.$explode[2];

                      
                        if(isset($_POST['submetido'])){
                            $submetido = $_POST['submetido'];
                            // Faça algo com $nome
                        } else {
                            // A chave 'nome' não foi definida, faça algo para lidar com isso
                        }
                        $msg = "";
                       
                           
                        if(isset($login_p)){
                            $login = $login_p;
                            // Faça algo com $nome
                        } else {
                            // A chave 'nome' não foi definida, faça algo para lidar com isso
                        }
                     
                        if(isset($_password)){
                            $senha = $_password;
                            // Faça algo com $nome
                        } else {
                            // A chave 'nome' não foi definida, faça algo para lidar com isso
                        }

                        $logado = $codigo_p;

                        $_SESSION['LOGADO'] = $logado;
                 
                        $chave = $_POST['chave'];

                        $dia = date('d'); 
                        $mes = date('m'); 
                        $ano = date('Y');

                        $databd = $dia."/".$mes."/".$ano;
                        $horabd = date ("H:i:s");
                        $horacc = date ("Hi");
                        $data = mktime(substr("$horabd",0,2),substr("$horabd",3,2),substr("$horabd",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
         ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Prisma Gestão ">

   
<link rel="shortcut icon" href="assets/images/iconN.png">   
    <title>Prisma Service - Login</title>
    <!-- Load div style -->
    <link href="assets/css/load-ajax.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/message.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/ladda-buttons/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="assets/js/modernizr.min.js"></script>
</head>
<body>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
        <div class="wrapper-page">
        
        
        <div></div>
        <div class=" card-box">
         
        
            <div class="panel-heading"> 
            <div class="text-center">  <img src="assets/images/prisma.png" style="width: 70%;"> </div>
                
            </div> 
            <div class="panel-body">
                <form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
                        <input type="hidden" name="submetido" id="submetido" value="1"/>  
                        <input type="hidden" name="codigo_p" id="codigo_p"  size="15" value="<?=$codigo_p;?>"/> 
                        <input type="hidden" id="_keyform" name="_keyform"  value="">
                        <input type="hidden" id="_lat" name="_lat" value="">
                        <input type="hidden" id="_long" name="_long" value="">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="login" id="login" placeholder="Usuário" value="<?=$_userlogin;?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password" id="password" placeholder="Senha" value="<?=$_usersenha;?>" required>
                        </div>
                    </div>
           
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="ladda-button btn btn-default btn-block waves-effect waves-light" data-style="expand-left">
                                ENTRAR
                            </button>
                        </div>
                    </div>
                    
                </form>
            </div>
             <?php
             if($row == 0){ $message =  '<b style="color:#E77171">Código de acesso incorreto,<br> por favor digite novamente!</b>';} 
             if($submetido == '1'){
               if (trim($login == "")) {
                  $message =  '<b style="color:#E77171">Usuário não pode ser vazio!</b>';
               } else if (trim($senha == "")) {
                  $message =  '<b style="color:#E77171">Senha não pode ser vazio!</b>';
               } else {//1
                  $senha = md5($senha);

         

                // $sql_sel = "SELECT * FROM ".$_SESSION['BASE'].".usuario WHERE usuario_LOGIN = '$login' and usuario_SENHA = '$senha' and usuario_ATIVO = 'Sim' ";							
              //    $rs_sel = mysqli_query($mysqli,$sql_sel);
                  
                    $stmt = $pdo->prepare("SELECT * FROM {$_SESSION['BASE']}.usuario
                    WHERE usuario_LOGIN = :login AND usuario_SENHA = :senha AND usuario_ATIVO = 'Sim'"); 
                  
                    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
                     $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
                    $stmt->execute();
                  //  $result = $stmt->get_result();
                    $numrows = $stmt->rowCount();
                 
                   
                    if ($numrows >= 1) {
                  
                  //Testas as condicoes
                //  $numrows = mysqli_num_rows($rs_sel);
                //  $rst_sel = mysqli_fetch_array($rs_sel); 
               //   $_SESSION["login"] = $login; 
                //  if ($numrows!=0){		//2

                    //INSERT VALIDACAO PRISMMOB 
                  //  $rst_sel =  $result->fetch_assoc();
                     $rst_sel = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if( $mEntrada != "") {               

                     $mEntrada = $rst_sel['usuario_MANHAENTRADA'];
                     $A = mktime(substr("$mEntrada",0,2),substr("$mEntrada",3,2),substr("$mEntrada",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
                     $mSaida = $rst_sel['usuario_MANHASAIDA'];
                     $B = mktime(substr("$mSaida",0,2),substr("$mSaida",3,2),substr("$mSaida",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
                     $tEntrada = $rst_sel['usuario_TARDEENTRADA'];
                     $C = mktime(substr("$tEntrada",0,2),substr("$tEntrada",3,2),substr("$tEntrada",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
                     $tsaida = $rst_sel['usuario_TARDESAIDA'];
                     $D = mktime(substr("$tsaida",0,2),substr("$tsaida",3,2),substr("$tsaida",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
                     $sabadoEntrada = $rst_sel['usuario_sabadoe'];
                     $E = mktime(substr("$sabadoEntrada",0,2),substr("$sabadoEntrada",3,2),substr("$sabadoEntrada",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
                     $sabadoSaida = $rst_sel['usuario_sabados'];
                     $F = mktime(substr("$sabadoSaida",0,2),substr("$sabadoSaida",3,2),substr("$sabadoSaida",6,2),substr("$databd",0,2),substr("$databd",3,2),substr("$databd",6,4));
                    }
                     $forahorario = $rst_sel['usuario_acessoexterno'];
                     $_SESSION["tecnico"]  = $rst_sel['usuario_CODIGOUSUARIO'];
                     $_SESSION['APELIDO'] = $rst_sel['usuario_APELIDO'];
                     $_SESSION['_DTUSERAVISO'] = $rst_sel['usuario_aviso'];
                     $idUser =  $rst_sel['usuario_CODIGOUSUARIO'];
                     $_SESSION["login"] = $rst_sel['usuario_LOGIN'];
                     $_SESSION["nivel"] = $rst_sel['usuario_tecnico'];; // 1 tecnico
                     if ($rst_sel['usuario_tecnico'] == 1) { 
                        $sql_i = "INSERT bd_prisma.aut (aut_codloja,aut_login,aut_data) VALUE ('$codigo_p','$idUser',CURRENT_DATE() ) ";							
                        $rs_i = mysqli_query($mysqli,$sql_i);
                     }
                    
                     $_SESSION["perfil"] =$rst_sel['usuario_perfil2'];
                     $_SESSION["log"] ='S';		
                     $_SESSION["empresa"] =$rst_sel['usuario_empresa'];

                     $idtecsession = base64_decode($_SESSION["tecnico"].";".$_SESSION['CODIGOCLI'].";".$_SESSION["nivel"]);

                     if($rst_sel['usuario_img64'] == "") {
                        $_SESSION["imglogin"] = 'avatar-1.jpg'; 
                    }else{
                        $_SESSION["imglogin"] = $_SESSION["CODIGOCLI"]."_". $_SESSION["tecnico"].".jpg";  
                    }
                 

                //BUSCA PERMISSÕES PARA USUARIO
                $stm = $pdo->prepare("select * from ".$_SESSION['BASE'].".telas_acesso where tela_user = '".$idUser."' and tela_idEmpresa = '$empresa_id'");
                $stm->execute();
                if($stm->rowCount() > 0){

                        unset($_SESSION['per999']); //usuario somente para financeiro

                        unset($_SESSION['per001']);
                        unset($_SESSION['per002']);
                        unset($_SESSION['per003']);
                        unset($_SESSION['per004']);
                        unset($_SESSION['per005']);
                        unset($_SESSION['per006']);
                        unset($_SESSION['per007']);
                        unset($_SESSION['per008']);
                        unset($_SESSION['per009']);
                        unset($_SESSION['per010']);
                        unset($_SESSION['per011']);
                        unset($_SESSION['per012']);
                        unset($_SESSION['per013']);
                        unset($_SESSION['per014']);
                        unset($_SESSION['per015']);     
                        unset($_SESSION['per016']); 
                        unset($_SESSION['per017']);  
                        unset($_SESSION['per018']);                   

                        unset($_SESSION['per100']);
                        unset($_SESSION['per101']);
                        unset($_SESSION['per102']);
                        unset($_SESSION['per103']);
                        unset($_SESSION['per104']);
                        unset($_SESSION['per105']);
                        unset($_SESSION['per106']);
                        unset($_SESSION['per107']);
                        unset($_SESSION['per108']);
                        unset($_SESSION['per109']);
                        unset($_SESSION['per110']);
                        unset($_SESSION['per111']);
                        unset($_SESSION['per112']);
                        unset($_SESSION['per113']);
                        unset($_SESSION['per114']);
                        unset($_SESSION['per115']);
                        unset($_SESSION['per116']);
                        unset($_SESSION['per117']);
                        unset($_SESSION['per118']);
                        unset($_SESSION['per119']);
                        unset($_SESSION['per120']);
                        unset($_SESSION['per121']);
                        unset($_SESSION['per122']);
                        unset($_SESSION['per123']);
                        unset($_SESSION['per124']);
                        unset($_SESSION['per125']);
                        unset($_SESSION['per126']);
                        unset($_SESSION['per127']);
                        unset($_SESSION['per128']);
                        unset($_SESSION['per129']);
                        unset($_SESSION['per130']);
                        unset($_SESSION['per131']);
                        unset($_SESSION['per132']);
                        unset($_SESSION['per133']);
                        unset($_SESSION['per134']);
                        unset($_SESSION['per135']);
                        unset($_SESSION['per136']);
                        unset($_SESSION['per137']);
                        unset($_SESSION['per138']);
                        unset($_SESSION['per138']);
                        unset($_SESSION['per139']);
                        unset($_SESSION['per140']);
                        unset($_SESSION['per141']);
                        unset($_SESSION['per142']);
                        unset($_SESSION['per143']);
                        unset($_SESSION['per144']);
                        unset($_SESSION['per145']);
                        unset($_SESSION['per146']);
                        unset($_SESSION['per148']);
                        unset($_SESSION['per149']);
                        unset($_SESSION['per150']);
                        unset($_SESSION['per151']);
                        unset($_SESSION['per160']);                           
                        unset($_SESSION['per219']);
                        unset($_SESSION['per220']);
                        unset($_SESSION['per221']);
                        unset($_SESSION['per222']);
                        unset($_SESSION['per223']);
                        unset($_SESSION['per224']);
                        unset($_SESSION['per225']);
                        unset($_SESSION['per226']);
                        unset($_SESSION['per227']);
                        unset($_SESSION['per228']);
                        unset($_SESSION['per229']);
                        unset($_SESSION['per230']);
                        unset($_SESSION['per231']);
                        unset($_SESSION['per232']);
                        unset($_SESSION['per233']);
                        unset($_SESSION['per240']);
                        unset($_SESSION['per241']);
                        unset($_SESSION['per242']);

                        unset($_SESSION['per250']);
                        unset($_SESSION['per251']);
                        unset($_SESSION['per252']);
                        unset($_SESSION['per253']);

                        unset($_SESSION['per306']);

                        unset($_SESSION['per401']);
                        unset($_SESSION['per402']);

                         unset($_SESSION['per152']);
                         unset($_SESSION['per153']);
                         unset($_SESSION['per154']);

                         unset($_SESSION['per156']);
                         unset($_SESSION['per157']);
                         unset($_SESSION['per158']);
                       

                $array = array();
                foreach ($stm->fetchAll(\PDO::FETCH_OBJ) as $rst) {
                    //PERMISSOES PRINCIPAIS=============================================================================================================
                    if($rst->tela_descricao == "999"){ $_SESSION['per999'] = $rst->tela_descricao; }//USUARIO PERFIL FINANCEIRO
                    
                    if($rst->tela_descricao == "1"){ $_SESSION['per001'] = $rst->tela_descricao; }//AGENDA
                    if($rst->tela_descricao == "2"){ $_SESSION['per002'] = $rst->tela_descricao; }//GESTAO DE CLIENTE
                    if($rst->tela_descricao == "3"){ $_SESSION['per003'] = $rst->tela_descricao; }//MENU PRINCIPAL - SERVIÇOS
                    if($rst->tela_descricao == "4"){ $_SESSION['per004'] = $rst->tela_descricao; }//ANALISE
                    if($rst->tela_descricao == "5"){ $_SESSION['per005'] = $rst->tela_descricao; }//CLIENTES
                    if($rst->tela_descricao == "6"){ $_SESSION['per006'] = $rst->tela_descricao; }//REQUISICAO
                    if($rst->tela_descricao == "231"){ $_SESSION['per231'] = $rst->tela_descricao; }//REQUISICAO PRISMAMOB TECNICO 
                    //if($rst->tela_descricao == "306"){ $_SESSION['per306'] = $rst->tela_descricao; }//FINANCEIRO 2
                    if($rst->tela_descricao == "7"){ $_SESSION['per007'] = $rst->tela_descricao; }//ADMINISTRATIVO
                    if($rst->tela_descricao == "8"){ $_SESSION['per008'] = $rst->tela_descricao; }//SERVICOS
                    if($rst->tela_descricao == "9"){ $_SESSION['per009'] = $rst->tela_descricao; }//ESTOQUE
                    if($rst->tela_descricao == "10"){ $_SESSION['per010'] = $rst->tela_descricao; }//CONFIGURACOES
                    if($rst->tela_descricao == "11"){ $_SESSION['per011'] = $rst->tela_descricao; }//MEU SALDO
                    if($rst->tela_descricao == "12"){ $_SESSION['per012'] = $rst->tela_descricao; }//MINHA CONTA
                    if($rst->tela_descricao == "13"){ $_SESSION['per013'] = $rst->tela_descricao; }//CAIXA PDV
                    if($rst->tela_descricao == "14"){ $_SESSION['per014'] = $rst->tela_descricao; }//VENDAS
                    if($rst->tela_descricao == "15"){ $_SESSION['per015'] = $rst->tela_descricao; }//ADMINSTRATIVO
                    if($rst->tela_descricao == "16"){ $_SESSION['per016'] = $rst->tela_descricao; }//FINANCEIRO - menu

                    if($rst->tela_descricao == "17"){ $_SESSION['per017'] = $rst->tela_descricao; }//QTDE OS MENU
                    if($rst->tela_descricao == "18"){ $_SESSION['per018'] = $rst->tela_descricao; }//ULTIMAS ATIVIDADES
                                        
                    //PERMISSOES SECUNDARIAS //CLIENTES=================================================================================================
                    if($rst->tela_descricao == "100"){ $_SESSION['per100'] = $rst->tela_descricao; }//CLIENTES
                    if($rst->tela_descricao == "101"){ $_SESSION['per101'] = $rst->tela_descricao; }//PLANOS
                    if($rst->tela_descricao == "102"){ $_SESSION['per102'] = $rst->tela_descricao; }//ANIVERSARIOS
                    if($rst->tela_descricao == "103"){ $_SESSION['per103'] = $rst->tela_descricao; }//RESUMO DE VENDAS
                    if($rst->tela_descricao == "104"){ $_SESSION['per104'] = $rst->tela_descricao; }//RELATORIOS
           
                    if($rst->tela_descricao == "160"){ $_SESSION['per160'] = $rst->tela_descricao; }//EXPORTAR RELATORIO CLIENTES
                  
                    
                    //PERMISSOES SECUNDARIAS //FINANCEIRO===============================================================================================
                  
                    if($rst->tela_descricao == "146"){ $_SESSION['per146'] = $rst->tela_descricao; }//FINANCEIRO
                    if($rst->tela_descricao == "105"){ $_SESSION['per105'] = $rst->tela_descricao; }//RESUMO CAIXA
                    if($rst->tela_descricao == "106"){ $_SESSION['per106'] = $rst->tela_descricao; }//EXTRATO FINANCEIRO
                    if($rst->tela_descricao == "107"){ $_SESSION['per107'] = $rst->tela_descricao; }//NOTAS FISCAIS
                    if($rst->tela_descricao == "108"){ $_SESSION['per108'] = $rst->tela_descricao; }//RECEBIVEIS
                    if($rst->tela_descricao == "109"){ $_SESSION['per109'] = $rst->tela_descricao; }//RELATORIOS
                    if($rst->tela_descricao == "142"){ $_SESSION['per142'] = $rst->tela_descricao; }//FECHAMENTO FINANCEIRO
                    
                    //PERMISSOES SECUNDARIAS //ADMINISTRATIVO===========================================================================================
                    if($rst->tela_descricao == "110"){ $_SESSION['per110'] = $rst->tela_descricao; }//NOTAS DE ENTRADA
                    if($rst->tela_descricao == "111"){ $_SESSION['per111'] = $rst->tela_descricao; }//GERACAO DE ARQUIVOS
                    if($rst->tela_descricao == "112"){ $_SESSION['per112'] = $rst->tela_descricao; }//FORNECEDORES
                    if($rst->tela_descricao == "230"){ $_SESSION['per230'] = $rst->tela_descricao; } //NPS
                    if($rst->tela_descricao == "232"){ $_SESSION['per232'] = $rst->tela_descricao; }//REGISTRO PONTO
                    if($rst->tela_descricao == "233"){ $_SESSION['per233'] = $rst->tela_descricao; }//GESTÃO PEDIDOS
                    
                    //PERMISSOES SECUNDARIAS //SERVICOS=================================================================================================
                    if($rst->tela_descricao == "113"){ $_SESSION['per113'] = $rst->tela_descricao; }//SERVICOS ATENDIMENTO
                    if($rst->tela_descricao == "114"){ $_SESSION['per114'] = $rst->tela_descricao; }//RELATORIOS
                     if($rst->tela_descricao == "152"){ $_SESSION['per152'] = $rst->tela_descricao; }//IMPORTAR O.S
                    if($rst->tela_descricao == "226"){ $_SESSION['per226'] = $rst->tela_descricao; }// LIBERA ALMOX O.S

                    if($rst->tela_descricao == "220"){ $_SESSION['per220'] = $rst->tela_descricao; }//ACESSO LISTA PREVENTIVO
                    if($rst->tela_descricao == "221"){ $_SESSION['per221'] = $rst->tela_descricao; }//PAINEL OFICINA
                    if($rst->tela_descricao == "222"){ $per222 = $rst->tela_descricao; }//ALTERAR SITUAÇÃO OFICINA PAINEL DA OFICINA
                    if($rst->tela_descricao == "223"){ $_SESSION['per223'] = $rst->tela_descricao; }//Data Encerramento O.S
                    if($rst->tela_descricao == "224"){ $_SESSION['per224'] = $rst->tela_descricao; }// Modelo Produto O.S
                    if($rst->tela_descricao == "225"){ $_SESSION['per225'] = $rst->tela_descricao; }// ABA RESUMO NA os
              
                    if($rst->tela_descricao == "227"){ $_SESSION['per227'] = $rst->tela_descricao; }// LIBERACPF DUPLICADO
                    if($rst->tela_descricao == "228"){ $_SESSION['per228'] = $rst->tela_descricao; }// bloquear reativar O.S
                    if($rst->tela_descricao == "229"){ $_SESSION['per229'] = $rst->tela_descricao; } //libera modelo comercial
                    
                    if($rst->tela_descricao == "240"){  $_SESSION['per240'] = $rst->tela_descricao; }// libera inativar acompanhamento
                    if($rst->tela_descricao == "241"){  $_SESSION['per241'] = $rst->tela_descricao; }// liberar visualização log salvamenteo
                    if($rst->tela_descricao == "242"){  $_SESSION['per242'] = $rst->tela_descricao; }// BLOQUERAR ACESSO A O.S

                      if($rst->tela_descricao == "157"){ $_SESSION['per157'] = $rst->tela_descricao; }//HABILITA PGTO PRISMAMOB
                    //PERMISSOES SECUNDARIAS //VENDAS =================================================================================================
                    
                    if($rst->tela_descricao == "115"){ $_SESSION['per115'] = $rst->tela_descricao; }//LIBERA ALMOXARIFADO
                    if($rst->tela_descricao == "250"){ $_SESSION['per250'] = $rst->tela_descricao; }// LIBERA VENDAS 
                    if($rst->tela_descricao == "251"){ $_SESSION['per251'] = $rst->tela_descricao; }// LIBERA RELATORIO
                    if($rst->tela_descricao == "252"){ $_SESSION['per252'] = $rst->tela_descricao; }// VISUALIZAÇÃO VALORES
                    if($rst->tela_descricao == "253"){ $_SESSION['per253'] = $rst->tela_descricao; }// ALTERA VENDEDOR VENDA FINALIZADA
                    //PERMISSOES SECUNDARIAS //ESTOQUE==================================================================================================
                    if($rst->tela_descricao == "116"){ $_SESSION['per116'] = $rst->tela_descricao; }//PRODUTOS
                    if($rst->tela_descricao == "117"){ $_SESSION['per117'] = $rst->tela_descricao; }//REQUISICAO
                    if($rst->tela_descricao == "118"){ $_SESSION['per118'] = $rst->tela_descricao; }//MOVIMENTACAO
                    if($rst->tela_descricao == "119"){ $_SESSION['per119'] = $rst->tela_descricao; }//INVENTARIO
                    if($rst->tela_descricao == "120"){ $_SESSION['per120'] = $rst->tela_descricao; }//ARQUIVO DE BALANCA
                    if($rst->tela_descricao == "121"){ $_SESSION['per121'] = $rst->tela_descricao; }//CURVA ABC
                    if($rst->tela_descricao == "122"){ $_SESSION['per122'] = $rst->tela_descricao; }//ESTIQUETA
                    if($rst->tela_descricao == "123"){ $_SESSION['per123'] = $rst->tela_descricao; }//RELATORIOS

                    if($rst->tela_descricao == "219"){ $_SESSION['per219'] = $rst->tela_descricao; }//SUPERVISOR   INVENTARIO
                    if($rst->tela_descricao == "401"){ $_SESSION['per401'] = $rst->tela_descricao; }//Inclui/Altera Estoque
                    if($rst->tela_descricao == "402"){ $_SESSION['per402'] = $rst->tela_descricao; }//Visualiza Detalhe

                     if($rst->tela_descricao == "158"){ $_SESSION['per158'] = $rst->tela_descricao; }//Gerar requisição a partir O.S
                    
                    //PERMISSOES SECUNDARIAS //CONFIGURACOES=============================================================================================
                    if($rst->tela_descricao == "124"){ $_SESSION['per124'] = $rst->tela_descricao; }//DADOS CADASTRAIS
                    if($rst->tela_descricao == "125"){ $_SESSION['per125'] = $rst->tela_descricao; }//FUNCIONARIOS E LOGINS
                    if($rst->tela_descricao == "126"){ $_SESSION['per126'] = $rst->tela_descricao; }//GRUPOS
                    if($rst->tela_descricao == "127"){ $_SESSION['per127'] = $rst->tela_descricao; }//CATEGORIAS
                    if($rst->tela_descricao == "128"){ $_SESSION['per128'] = $rst->tela_descricao; }//ESPECIES
                    if($rst->tela_descricao == "129"){ $_SESSION['per129'] = $rst->tela_descricao; }//enderecos
                    if($rst->tela_descricao == "130"){ $_SESSION['per130'] = $rst->tela_descricao; }//TIPO DE CLIENTE
                    if($rst->tela_descricao == "131"){ $_SESSION['per131'] = $rst->tela_descricao; }//TIPO DE FORNECEDOR
                    if($rst->tela_descricao == "132"){ $_SESSION['per132'] = $rst->tela_descricao; }//ALMOXARIFADO
                    if($rst->tela_descricao == "133"){ $_SESSION['per133'] = $rst->tela_descricao; }//LINHAS
                    if($rst->tela_descricao == "134"){ $_SESSION['per134'] = $rst->tela_descricao; }//CONDICOES DE PAGAMENTOS
                    if($rst->tela_descricao == "135"){ $_SESSION['per135'] = $rst->tela_descricao; }//LIVRO CAIXA
                    if($rst->tela_descricao == "136"){ $_SESSION['per136'] = $rst->tela_descricao; }//GRUPO DE RECEITAS E DESPESAS
                    if($rst->tela_descricao == "137"){ $_SESSION['per137'] = $rst->tela_descricao; }//CONTAS DE RECEITAS E DESPESAS
                    if($rst->tela_descricao == "138"){ $_SESSION['per138'] = $rst->tela_descricao; }//PROJETO, CENTRO DE CUSTO
                    if($rst->tela_descricao == "139"){ $_SESSION['per139'] = $rst->tela_descricao; }//EXTRA A
                    if($rst->tela_descricao == "140"){ $_SESSION['per140'] = $rst->tela_descricao; }//EXTRA B
                    if($rst->tela_descricao == "141"){ $_SESSION['per141'] = $rst->tela_descricao; }//ZERA ESTOQUE
                    if($rst->tela_descricao == "143"){ $_SESSION['per143'] = $rst->tela_descricao; }//COLABORADORES
                    if($rst->tela_descricao == "144"){ $_SESSION['per144'] = $rst->tela_descricao; }//BENEFICIÁRIO
                    if($rst->tela_descricao == "145"){ $_SESSION['per145'] = $rst->tela_descricao; }//TERMINAL
                    if($rst->tela_descricao == "148"){ $_SESSION['per148'] = $rst->tela_descricao; }//REGIAO
                    if($rst->tela_descricao == "149"){ $_SESSION['per149'] = $rst->tela_descricao; }//AVISOS
                    if($rst->tela_descricao == "150"){ $_SESSION['per150'] = $rst->tela_descricao; }//estoque compartilhado
                    if($rst->tela_descricao == "151"){ $_SESSION['per151'] = $rst->tela_descricao; }//log acesso
                    if($rst->tela_descricao == "153"){ $_SESSION['per153'] = $rst->tela_descricao; }//situaçãos O.S
                    if($rst->tela_descricao == "154"){ $_SESSION['per154'] = $rst->tela_descricao; }//situacao oficina
                    if($rst->tela_descricao == "155"){ $_SESSION['per155'] = $rst->tela_descricao; }//cfop notas
                    if($rst->tela_descricao == "156"){ $_SESSION['per156'] = $rst->tela_descricao; }//customizacao
                }
            }
            $v1 = "on";
                     if ($forahorario == "Sim") { 	
                   
                           if($v1 == "on") {
                              ?>
                              <script>
                                 location.href = "v1/";
                              </script>
                            <?php
                           }else{
                              ?>
                              <script>
                                 location.href = "prismaloja/menu.php";
                              </script>
                            <?php
                           }
                     	
                       
                     } else {
                        $status = 0;
                        if($data > $A and $data < $B) { $status = 1; } 
                        if($data > $C and $data < $D) { $status = 1; } 
                        if($data > $E and $data < $F) { $status = 1; } 

                        if($status == 0 ) { 
                           $message =  "Acesso nao permitido!!!,Informar Horario de acesso ";
                        } else { 
                         
                      
                           if($v1 == "on") {
                              ?>
                              <script>
                                 location.href = "v1/menu.php";
                              </script>
                            <?php
                           }else{
                              ?>
                              <script>
                                 location.href = "prismaloja/menu.php";
                              </script>
                            <?php
                           }
                                                           
                               }

                        }

                    		

                  } else {	
                     $message =  '<b style="color:#E77171">Usuário ou Senha incorreta, verifique!</b>';
                  }
               }
            }	
             
             
             
             ?>
            <div class="panel-footer" style="display: <?=!empty($message) ? 'block' : 'none'?>;">
                <?php if(!empty($message)): ?>
                    <p class="text-danger text-center m-t-5">
                        <?=$message?>
                    </p>
                <?php endif ?>
            </div> 
        </div>    
        <div style="background-image: url(prismaloja/img/bannerservice.jpg); background-size: contain; background-repeat: no-repeat; height: 60%;""></div> 
        <div class="row">
            <div class="form-group m-t-20 m-b-0">
                        
                        <div class="form-group m-b-0 text-center">
                            <div class="col-sm-12">
                            <button type="button" class="btn btn-twitter waves-effect waves-light" data-toggle="modal" data-target="#modalponto" onclick="_regPonto()">
                                           <span class="btn-label"><i class="fa  fa-clock-o"></i>
                                           </span>REG.PONTO</button>

                            </div>
                        </div>
            </div>  
        </div>                        
        <div class="row">
            <div class="col-sm-12 text-center">
                <p>
                   Código de Acesso <span class="text-primary m-l-5"><b> <?=$codigo_p;?></b></span>
                </p>
            </div>
        </div>
    </div>
    <?php } ?>
    <div id="modalponto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Registro ponto</h4>
                                        </div>
                                        <div class="modal-body" id="_rethora">
                                      
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
    <script>
        var resizefunc = [];

        function _regPonto() {
                   
                    var $_keyid = "REGPONTO_0002";
                      $('#_keyform').val($_keyid);   
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    _carregando('#_rethora');
                    if (navigator.geolocation) {                 
                       navigator.geolocation.getCurrentPosition(showPosition2);
                  } else {
                         alert("O seu navegador não suporta Geolocalização");

                }
              
             }

             function _regPontoAtualiza() {                  
                   var $_keyid = "REGPONTO_0002";
                     $('#_keyform').val($_keyid);   

                   var dados = $("#form1 :input").serializeArray();
                   dados = JSON.stringify(dados);
                
                   $.post("v1/page_return.php", {
                       _keyform: $_keyid,
                       dados: dados,
                       acao: 7
                   }, function(result) {                           
                       $("#rethora").html(result);                                                
                   });
            }

            function _regPontopoint() {                  
                   var $_keyid = "REGPONTO_0002";
                     $('#_keyform').val($_keyid);   

                   var dados = $("#form2 :input").serializeArray();
                   dados = JSON.stringify(dados);
                   _carregando('#retconf');
                   $.post("v1/page_return.php", {
                       _keyform: $_keyid,
                       dados: dados,
                       acao: 8
                   }, function(result) {                           
                       $("#retconf").html(result);       
                
                            //ENVIAR EMAIL
                            var form_data = new FormData(document.getElementById("form2"));
                          
                            $.ajax({
                                url: 'v1/acaoEmailPonto.php',
                                dataType: 'text',
                                async: true,
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                type: 'post',
                                success: function(retorno) {
                                         
                                }
                            });
                        });
         
            }

            function _regviz() {                  
                   var $_keyid = "REGPONTO_0002";
                     $('#_keyform').val($_keyid);   

                   var dados = $("#form2 :input").serializeArray();
                   dados = JSON.stringify(dados);
                   _carregando('#_rethora');
                   $.post("v1/page_return.php", {
                       _keyform: $_keyid,
                       dados: dados,
                       acao: 9
                   }, function(result) {                           
                       $("#_rethora").html(result);                                                
                   });
            }

            function _carregando(_idmodal) {
                        $(_idmodal).html('' +
                            '<div class="bg-icon pull-request" >' +
                            '<img src="v1/assets/images/preloader.gif"  class="img-responsive center-block"  alt=" aguarde">' +
                            '<h4 class="text-center">carregando dados...</h4>' +
                            '</div>');
                        }
          
      
        function showPosition2(position) {
            var $_keyid = "REGPONTO_0002";
                      $('#_keyform').val($_keyid);  
                      var dados = $("#form1 :input").serializeArray();
                       dados = JSON.stringify(dados); 
                      $('#_lat').val(position.coords.latitude);
                      $('#_long').val(position.coords.longitude);

                 $.post("v1/page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 6
                    }, function(result) {                           
                      $("#_rethora").html(result);                    
                 
             
                    });

            }

            

    </script>

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
    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>

    <!-- Modal-Effect -->
    <script src="assets/plugins/custombox/js/custombox.min.js"></script>
    <script src="assets/plugins/custombox/js/legacy.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

    <!-- ladda js -->
    <script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>
    <script src="assets/js/form-signin.js"></script>  
</body>
</html>



