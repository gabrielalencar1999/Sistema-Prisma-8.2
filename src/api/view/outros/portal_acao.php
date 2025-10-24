<?php
require_once('../api/config/config.inc.php');
require '../api/vendor/autoload.php';

use Database\MySQL;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Functions\Validador;    

if (isset($_parametros)) {
    $_parametros = Validador::sanitizeArrayRecursive($_parametros, [
          '_codlink'  => 'base64'
    ]);
}

$pdo = MySQL::acessabd();


//include('libs/phpqrcode/qrlib.php'); 

$_acao = $_POST["acao"];

//verifica totem
$_totem  = substr($_parametros['_codlink'],-2);

$_cod = base64_decode($_parametros['_codlink']);
$_login = substr($_cod,0,4);

//buscar base
$consulta = $pdo->query("SELECT consumidor_base  from info.consumidor WHERE CODIGO_CONSUMIDOR = '$_login' limit 1");
$retorno = $consulta->fetchAll();
foreach ($retorno as $row) {
   $_BASE =  $row["consumidor_base"];                                                              
}


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


function mensagem($_mensagem, $_campo, $valor_campo )
{
	$_texto =    str_replace($_campo, $valor_campo, $_mensagem); //[NOME]

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


$recaptchaSecretKey = '6LeiLcUrAAAAAKDA1B2BnJOYaAW5SiqoqhQomHqv';

// Função para validar o reCAPTCHA v2
function validarRecaptcha($token, $secretKey) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);
    
    // Debug - mostrar resposta completa
    if (!$result['success']) {
        echo 'Debug reCAPTCHA v2: ' . json_encode($result);
        return false;
    }
    
    // Para reCAPTCHA v2, só verificamos se success = true
    return $result['success'];
}

if ($_acao == 0) {  
       ?>
   
       <head>
              <script src="https://www.google.com/recaptcha/api.js" async defer></script>
       </head>
                      <div class="container" style="margin-top: 10px; font-weight: 700px;" >
                                        <div class="row">
                                                <div class="col-lg-12"  >    
                                                                <blockquote style="border-left: 20px solid #2fc8e7;">
                                                                            <p >
                                                                            <h3>SEJA BEM-VINDO(A)</h3>
                                                                            </p>
                                                                            <footer>
                                                                            Este acesso é para <strong>consultas</strong> e <strong>solicitações serviços</strong></cite>
                                                                            </footer>
                                                                        </blockquote>
                                                </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                                <div class="col-xs-12 col-lg-12"  >    
                                                
                                                                        <div class="radio radio-info radio-inline">
                                                                            <input type="radio" id="_PD001" onclick="PD001()" value="option1" name="radioInline" checked="">
                                                                            <label for="inlineRadio1"> Pessoa Física</label>
                                                                        </div>
                                                                        <div class="radio radio-warning  radio-inline">
                                                                            <input type="radio" id="_PD002"  onclick="PD002()" value="option2" name="radioInline">
                                                                            <label for="inlineRadio2"> Pessoa Jurídica </label>
                                                                        </div>
                                                </div>
                                                
                                        </div>
                    </div>   
                    <div class="container" >  
                                        <div class="row">                
                                                <div class="col-xs-12" style="margin-top: 10px;">
                                                    <label>Por favor, informe seu CPF ou Telefone * </label>
                                                </div>
                                        </div>
                                        <div class="row">              
                                                             
                                                        <div class="col-md-4 col-xs-12">
                                                            <div class="input-group" id="_tpdoc">
                                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                                <input type="text" id="prisma_doc" name="prisma_doc" class="form-control"  maxlength="14" autocomplete="off" onKeyUp="mascaraTexto(event,'1')"  placeholder="CPF" >                                                              
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-xs-12 text-center" ><h3><strong>OU</strong> </h3>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12"> 
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                <input type="text" id="_fonecelular" name="_fonecelular"  maxlength="10" class="form-control" autocomplete="off" placeholder="TELEFONE" onKeyUp="mascaraTexto(event,'2')">
                                                            </div>                          
                                                        </div>
                                                 
                                        </div>
                                      
                                      
                                        <?php if($_totem != "TX") { ?> 
                                        <div class="row">                                    
                                          <div class="col-md-5 col-xs-12">
                                                
                                                        <label>informe o E-mail* </label>
                                                            <div class="input-group" >
                                                          
                                                                <span class="input-group-addon"><i class="fa  fa-envelope-o"></i></span>
                                                                <input type="email" id="prisma_email" name="prisma_email" class="form-control"    placeholder="exemplo@exemplo.com.br" >                                                              
                                                            </div>
                                                      
                                                
                                                
                                             
                                    
                                                
                            
                                                 <div class="col-md-7 col-xs-12">
                                                        <div style="margin-top: 25px; display: flex; align-items: center; gap: 30px;">
                                                            <script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>
                                                            <div id="recaptcha-checkbox" class="g-recaptcha" data-sitekey="6LeiLcUrAAAAAByOUiCzWerZK2mRYgQw_vern15i" data-callback="recaptchaCallback" data-theme="light" style="flex-shrink: 0;"></div>
                                                            <script>
                                                            function recaptchaCallback() {
                                                                console.log('reCAPTCHA validado com sucesso');
                                                            }
                                                            </script>
                                                            <button type="button" id="P0001" name="P0001" onclick="_P0001('223')" class="btn btn-success waves-effect waves-light" style="flex-shrink: 0;">
                                                                <span class="btn-label"><i class="fa fa-check"></i>
                                                                </span>Continuar
                                                            </button>
                                                        </div>
                                                 </div> 
                                        </div>       
                                        </div>
                                        <div id="retmsg"> </div>
                                        <?php }else {
                                          ?>
                                          <div class="row">                                    
                                                  <div class="col-xs-12 col-md-5"  >   
                                                        <div class="btn-group pull-right" style="margin-top: 25px;">
                                                            <button type="button" id="P0001" name="P0001" onclick="_P0001('22')" class="btn btn-success waves-effect waves-light">
                                                            <span class="btn-label"><i class="fa fa-check"></i>
                                                             </span>Continuar</button>

                                                        </div>

                                                 </div>
                                       
                                           </div> 
                                           <div id="retmsg"> </div>
                                        <?php } ?>                         
                                    
                    </div>
                    <?php
       exit();
}

if ($_acao == 1) {   //VALIDA PRIMEIRA PAGINA
       if ($_parametros['radioInline'] == "option1") { //CNPJ
?>
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" id="prisma_doc" name="prisma_doc" class="form-control" maxlength="14" onKeyUp="mascaraTexto(event,'1')" placeholder="CPF">
       <?php
       } else {
       ?>
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" id="prisma_doc" name="prisma_doc" class="form-control"  maxlength="18" onKeyUp="mascaraTexto(event,'4')"placeholder="CNPJ">
       <?php
       }
       exit();
}

if ($_acao == 101) {   //produto em garantia
       if ($_parametros['radioInline'] == "2") { //?>
                
                             <?php if($_totem != "TX") { ?>
                                   <div class="col-xs-12 col-lg-3"  >    
                                   Data de emissão da Nota Fiscal <input type="date" id="prisma_dtnf" name="prisma_dtnf" class="form-control" placeholder="DATA NF">
                                   </div>
       <?php
                             }
       } else {
       
       }
       exit();
}




if ($_acao == 22) {   //valida dados primeira tela
       try {
              $cpfcnpj = $_parametros["prisma_doc"];
              $msg = "";
             // print_r($_parametros);
              if($_parametros["prisma_doc"] == "" and  $_parametros["_fonecelular"] == "" ){
                     $msg = $msg." Informe um CPF/CNPJ ou Telefone";
                    
              }else{
                     if ($cpfcnpj != ""){
                            $_tipodoc = $_parametros["radioInline"];
                            //valida tipo                             
                                   $cpfcnpj = preg_replace('/[^0-9]/', '', (string) $_parametros["prisma_doc"]);                                   
                                   if($_tipodoc =="option1") //cpf
                                   {
                                          $ret  = validaCPF($cpfcnpj);
                                          if($ret == false){                            
                                                 $msg = $msg." CPF INVALIDO, Verifique !!! ";
                                          }
                                   } else {
                                          $ret  = validar_cnpj($cpfcnpj);
                                          if($ret == false){                                          
                                                 $msg = $msg." CNPJ INVALIDO, Verifique !!! ";                                                 
                                          }
                                   } 
                            
                     }
                     else{
                            //telefone                       
                            if(strlen($_parametros["_fonecelular"]) < 12 ){                                                                       
                                   $msg = $msg." INFORME TELEFONE CORRETAMENTE !!! ";                                                                                    
                            }

                    
                     }
                     if($_totem != "TX" ){
                        
                       
                            if (filter_var($_parametros["prisma_email"], FILTER_VALIDATE_EMAIL)  )  {
                                   //    echo "Email address '$email_b' is considered valid.\n";
                                   } else {
                                       $msg = $msg." Informe um Email válido !!! ";       
                                   }       
                     }

              }

           if($msg != "") { ?>
              <div class="alert alert-danger alert-dismissable" style="margin:5px ;"><?=$msg;?></div>
              <hr />
              <?php  exit();} 

       } catch (PDOException $e) {
              ?>
                  <div>                     
                              <h2>* <?= "Erro: " . $e->getMessage() ?></h2>
                              <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                       
                  </div>
          <?php
              }

      

}

if ($_acao == 223) {   //token
       // Validação do reCAPTCHA
       $dados = json_decode($_POST['dados'], true);
       
       // Extrai o token do reCAPTCHA dos dados
       $recaptchaToken = '';
       foreach ($dados as $campo) {
           if ($campo['name'] === 'g-recaptcha-response') {
               $recaptchaToken = $campo['value'];
               break;
           }
       }
       
       // Valida o reCAPTCHA
       if (empty($recaptchaToken)) {
           echo 'Erro: Token do reCAPTCHA não encontrado.';
           exit;
       }
       
       $recaptchaResult = validarRecaptcha($recaptchaToken, $recaptchaSecretKey);
       
       if (!$recaptchaResult) {
           echo 'Erro: Validação do reCAPTCHA falhou. Token: ' . substr($recaptchaToken, 0, 10) . '...';
           exit;
       }
       
       if($_totem != "TX" )
              
              {

              date_default_timezone_set('America/Sao_Paulo');
       
              $dia       = date('d');
              $mes       = date('m');
              $ano       = date('Y');
       
              $data_atual      = $dia . "/" . $mes . "/" . $ano;
              $data_atualb  = $ano . "-" . $mes . "-" . $dia ;
             
              //Create an instance; passing `true` enables exceptions
              $CHAVE =  rand( 10000 , 99999 );
              try{			
                  
                     $stm = $pdo->prepare("INSERT INTO info.tokenos (
                            tkemail,
                            tkdate	,                                   
                            tktoken) 
                                   VALUES (?,
                                   ?,					
                                   ?)");
                            $stm->bindParam(1, $_parametros["prisma_email"]);			
                            $stm->bindParam(2, $data_atualb);	
                            $stm->bindParam(3, $CHAVE);				
                           	
                            $stm->execute();			
                            
                            
                                   }
                                   catch (\Exception $fault){
                                                 $response = $fault;
                                   }
                     echo($CHAVE);
                                   

                            $mail = new PHPMailer(true);
                            try {
                            //Server settings
                            //  $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.titan.email';                     //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'contato@sistemaprisma.com.br';                     //SMTP username
                            $mail->Password   = 'ttitts01!@';                               //SMTP password
                            $mail->SMTPSecure = 'ENCRYPTION_STARTTLS';            //Enable implicit TLS encryption
                            // $mail->SMTPSecure = 'tls'; 
                            $mail->Port       = 587;    
                                                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            $mail->SMTPOptions = array(
                                   'ssl' => array(
                                   'verify_peer' => false,
                                   'verify_peer_name' => false,
                                   'allow_self_signed' => true
                                   )
                            );
                            

                     //Recipients
                     $mail->setFrom('contato@sistemaprisma.com.br', 'Atendimento Sistema Prisma -Nao responder '); //quem esta enviando titulo
                     $mail->addAddress($_parametros["prisma_email"], "Seu código de acesso é $CHAVE");     //Add a recipient
                     
                     //Content
                     $mail->isHTML(true);                                  //Set email format to HTML
                     $mail->Subject = utf8_decode('Seu código de acesso é '.$CHAVE); // assunto !!!
                     $mail->Body    = utf8_decode('<h3>Seu código de acesso é <b> '.$CHAVE.' </b></h3> <br>Olá!
                     Copie o código acima, retorne à página de login do Sistema Prisma para inseri-lo e confirmar seu acesso.<br>');
                     $mail->AltBody = utf8_decode('Seu código de acesso é : '.$CHAVE.'. Copie o código acima, retorne à página de login do Sistema Prisma para inseri-lo e confirmar seu acesso');

                     $mail->send();
                     
                                   } catch (Exception $e) {
                                   echo "OPS $CHAVE !!! algo deu errado no envio e-mail, fale direto com atendente    xxxxxxxx";
                                   }
                                   ?>

              <div class="container">
                            <!-- Page-Title -->
                            <div class="row">
                                   <div class="col-sm-12">
                                          <h4 class="page-title">CHAVE ACESSO </h4>
                                          <ol class="breadcrumb">
                                                 <li class="active">Informe a chave de acesso</li>
                                          </ol>
                                   </div>
                            </div>
                     
                            <div class="card-box">
                                   <div class="panel-body">
                                          <div class="row">
                                                 <div class="col-sm-12 col-xs-12">
                                                   Agora é só informar o código recebido em: <?= $_parametros["prisma_email"];?>
                                                 </div>
                                                 <input type="hidden" class="form-control" name="radioInline" id="radioInline" value="<?= $_parametros["radioInline"]; ?>">        
                                                 <input type="hidden" class="form-control" name="prisma_doc" id="prisma_doc" value="<?= $_parametros["prisma_doc"]; ?>">        
                                                 <input type="hidden" class="form-control" name="_fonecelular" id="_fonecelular" value="<?= $_parametros["_fonecelular"]; ?>">        
                                                 <input type="hidden" class="form-control" name="prisma_email" id="prisma_email" value="<?=$_parametros["prisma_email"]; ?>">        
                                                       
                                          </div>
                                          <div class="row">
                                                   <div class="col-sm-3 col-xs-12" style="margin: 10px 0px 10px 0px">                                                   
                                                    <input autocomplete="new-password" type="text" id="prisma_chave" name="prisma_chave" class="form-control input-lg "  placeholder="Código" >                                                              
                                                 </div>
                                          </div>
                                          <div id="retmsg"> </div>
                                          <div class="row">
                                                 <div class="col-sm-12">
                                                               <button type="button" id="P0001" name="P0001" onclick="_PT001()" class="btn btn-success waves-effect waves-light">
                                                               <span class="btn-label"><i class="fa fa-check"></i>
                                                               
                                                               </span>Continuar</button>
                                                 </div>
                                          </div>
                                   </div>
                            </div>
              </div>
              <?php
      }

}


if ($_acao == 2231) {   //VALIDA TOKEN
        
       $sql = "SELECT tktoken FROM info.tokenos WHERE tkemail = :email ORDER BY tkid DESC LIMIT 1";

       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(':email', $_parametros['prisma_email']);
       $stmt->execute();
       
       $retorno = $stmt->fetchall(PDO::FETCH_ASSOC); //alterado para fetchall

       $_bdtktoken = "";
              foreach ($retorno as $row) {
                
                   $_bdtktoken = $row['tktoken'];
                  }
         
                  if($_bdtktoken != trim($_parametros["prisma_chave"])){
              ?>
            
             <div class="alert alert-danger alert-dismissable" style="margin:5px ;">CHAVE <?= $_bdtktoken; $_parametros["prisma_chave"];?> INVÁLIDA</div>
              <hr />
             <?php
                  }
      

}


if ($_acao == 2) {   //cadastro

     
       //verficar se existe cadastro

       //VAR CNPJ E CPF
       $_tipodoc = $_parametros["radioInline"];
	$cpfcnpj  = remove($_parametros["prisma_doc"] );
	$cpfcnpj = preg_replace('/[^0-9]/', '', (string) $cpfcnpj);
	
	if($cpfcnpj  != ""){

       	if($_tipodoc =="option1") //cpf
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

       if($_parametros["prisma_doc"] != ""){
              $filtro = " AND CGC_CPF =  '".$cpfcnpj."'";
       }
       if($_parametros["_fonecelular"] != ""){
              $FONE = remove($_parametros["_fonecelular"]);
              $DDD   = substr($FONE,0,2);
              $FONE= substr($FONE,2,10);
              $filtro = $filtro." AND  DDD_RES = '". $DDD."' AND  FONE_RESIDENCIAL =  '".$FONE."' ";
              $filtro = $filtro." OR  DDD = '". $DDD."' AND  FONE_CELULAR =  '".$FONE."'  ";
              $filtro = $filtro." OR DDD_COM = '". $DDD."' AND  FONE_COMERCIAL =  '".$FONE."'  ";
       }

       $sql = "Select  * from $_BASE.consumidor  where  CGC_CPF <> '' $filtro  limit 1	";    
    
           $stm = $pdo->prepare("$sql");                   
       $stm->execute();
       $totalreg = $stm->rowCount() ;  
       $DDD = "";
       $DDD_COM ="";
       $DDD_RES = "";
 
       if($totalreg > 0) {
              $_retorno =  $stm->fetch(\PDO::FETCH_OBJ);  
              if($_retorno->{'DDD'} != ""){
                     $DDD = "(".$_retorno->{'DDD'}.")";
              }
              if($_retorno->{'DDD_COM'} != ""){
                     $DDD_COM = "(".$_retorno->{'DDD_COM'}.")";
              }
              if($_retorno->{'DDD_RES'} != ""){
                     $DDD_RES = "(".$_retorno->{'DDD_RES'}.")";
              }
             
             
             
       }
       ?>

       <div class="container">
              <!-- Page-Title -->
              <div class="row">
                     <div class="col-sm-12">
                            <h4 class="page-title">DADOS CADASTRAIS</h4>
                            <ol class="breadcrumb">
                                   <li class="active">Confirme abaixo os Dados Cadastrais</li>
                            </ol>
                     </div>
              </div>
            
              <div class="card-box">
                     <div class="panel-body">
                            <div class="row">
                                   <div class="col-sm-6">
                                          <label class="control-label">Nome Completo</label>
                                          <input type="hidden" class="form-control" name="_tipopessoa" id="_tipopessoa" value="<?=htmlspecialchars($_retorno->{'Tipo_Pessoa'}); ?>">
                                          <input type="hidden" class="form-control" name="TIPO_CLIENTE" id="TIPO_CLIENTE" value="<?=htmlspecialchars($_parametros["radioInline"]); ?>">                                          
                                          <input type="hidden" class="form-control" name="_idcliente_sel" id="_idcliente_sel" value="<?=htmlspecialchars($_retorno->{'CODIGO_CONSUMIDOR'}); ?>">
                                          <input type="hidden" class="form-control" name="id_celularwats" id="id_celularwats" value="<?= $id_celularwats; ?>">
                                          <input type="hidden" class="form-control" name="id_celular2wats" id="id_celular2wats" value="<?=htmlspecialchars($_retorno->{'id_celular2wats'}); ?>">
                                          <input type="hidden" class="form-control" name="id_celularsms" id="id_celularsms" value="<?=htmlspecialchars($_retorno->{'id_celularsms'}); ?>">
                                          <input type="hidden" class="form-control" name="id_celular2sms" id="id_celular2sms" value="<?=htmlspecialchars($_retorno->{'id_celular2sms'}); ?>">
                                          <input type="text" class="form-control" name="_nome" id="_nome" value="<?=htmlspecialchars($_retorno->{'Nome_Consumidor'}); ?>">
                                   </div>
                                   <div class="col-sm-3">
                                          <label class="control-label">CPF / CNPJ</label>
                                          <input type="text" class="form-control" name="prisma_doc" id="prisma_doc" value="<?=htmlspecialchars($cpfcnpj);?>" autocomplete="off" maxlength="18" onKeyUp="mascaraTexto(event,'1')" onblur="validarCPF()">
                                   </div>
                                   <div class="col-sm-3">
                                          <label class="control-label">RG / I.E</label>
                                          <input type="text" class="form-control" name="_rgie" id="_rgie" value="<?=htmlspecialchars($_retorno->{'INSCR_ESTADUAL'}); ?>" maxlength="20">
                                   </div>
                            </div>
                            <?php if ($_retorno->{'Tipo_Pessoa'} == 2) {
                                   $_display = "display:";
                            } else {
                                   $_display = "display:none";
                            } ?>

                            <div class="row" id='c_fantasia' style="<?= $_display; ?>">
                                   <div class="col-sm-9">
                                          <label class="control-label">Nome Fantasia</label>
                                          <input type="text" class="form-control" name="_nomefantasia" id="_nomefantasia" value="<?= $_retorno->{'Nome_Fantasia'}; ?>">
                                   </div>
                                   <div class="row" id='c_municipal' style="<?= $_display; ?>">
                                          <div class="col-sm-3">
                                                 <label class="control-label">Inscr.Municipal</label>
                                                 <input type="text" class="form-control" name="_ie_municipal" id="_ie_municipal" value="<?= $_retorno->{'INSCR_MUNICIPAL'}; ?>">
                                          </div>
                                   </div>
                            </div>

                            <div class="row" id="retcpf">
                            </div>
                            <div class="row">
                                   <div class="col-xs-6">
                                          <label class="control-label">Data Nascimento</label>
                                          <input type="date" class="form-control" name="_dtnacimento" id="_dtnacimento" value="<?= $_retorno->{'data_nascimento'}; ?>">
                                   </div>
                                   <div class="col-xs-6">
                                          <label class="control-label">Email</label>
                                          <input type="text" class="form-control" name="_email" id="_email" value="<?= $_retorno->{'EMail'}; ?>">
                                   </div>
                            </div>
                            <div class="row">
                                   <div class="col-md-3 col-xs-12">
                                          <label class="control-label">Telefone Celular 1</label>
                                          <span id="spanfone1" name="spanfone1" class="badge " style="cursor:pointer ;<?php if ($id_celularwats == 0) {
                                                                                                                              echo 'background-color:#79898f';
                                                                                                                       } else {
                                                                                                                              echo 'background-color:#81c868';
                                                                                                                       } ?>" onblur="validarTefone()" onclick="_atwats('id_celularwats','1','spanfone1')"><i class="fa  fa-whatsapp fa-2"></i></span>
                                          <span id="spanfone2" class="badge " style="cursor:pointer; <?php if ($_retorno->{'id_celularsms'} == 0) {
                                                                                                                echo 'background-color:#79898f';
                                                                                                         } else {
                                                                                                                echo 'background-color:#337ab7';
                                                                                                         } ?>" onblur="validarTefone()" onclick="_atwats('id_celularsms','2','spanfone2')"><i class="fa  fa-envelope fa-2"></i></span>
                                          <input type="text" class="form-control" name="_fonecelular" id="_fonecelular" value="<?=$DDD;?><?= $_retorno->{'FONE_CELULAR'}; ?>" maxlength="14" onblur="validarTefone()" onKeyUp="mascaraTexto(event,'2')" placeholder="(00)00000-0000">
                                   </div>
                                   <div class="col-md-3  col-xs-12">
                                          <label class="control-label">Celular 2</label>
                                          <span id="spanfone3" class="badge " style="cursor:pointer ;<?php if ($_retorno->{'id_celular2wats'} == 0) {
                                                                                                                echo 'background-color:#79898f';
                                                                                                         } else {
                                                                                                                echo 'background-color:#81c868"';
                                                                                                         } ?>" onblur="validarTefone()" onclick="_atwats('id_celular2wats','1','spanfone3')"><i class="fa  fa-whatsapp fa-2"></i></span>
                                          <span id="spanfone4" class="badge " style="cursor:pointer ;<?php if ($_retorno->{'id_celular2sms'} == 0) {
                                                                                                                echo 'background-color:#79898f';
                                                                                                         } else {
                                                                                                                echo 'background-color:#337ab7"';
                                                                                                         } ?>" onblur="validarTefone()" onclick="_atwats('id_celular2sms','2','spanfone4')"><i class="fa  fa-envelope fa-2"></i></span>
                                          <input type="text" class="form-control" name="_fonecelular2" id="_fonecelular2" value="<?=$DDD_COM; ?><?= $_retorno->{'FONE_COMERCIAL'}; ?>" maxlength="14" onblur="validarTefone()" onKeyUp="mascaraTexto(event,'2')" placeholder="(00)00000-0000">
                                   </div>
                                   <div class="col-md-6  col-xs-12">
                                          <label class="control-label">Telefone Fixo</label>
                                          <input type="text" class="form-control" name="_fonefixo" id="_fonefixo" value="<?=$DDD_RES ?><?= $_retorno->{'FONE_RESIDENCIAL'}; ?>" maxlength="14" onblur="validarTefone()" onKeyUp="mascaraTexto(event,'3')" placeholder="(00)00000-0000">
                                   </div>
                            </div>


                            <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                                               <label >CEP</label>                                                              
                                                               <input type="text" class="form-control" name="_cep"  id="_cep" value="<?=str_replace(".", "",$_retorno->{'CEP'});?>" maxlength="10" onKeyUp="mascaraTexto(event,'5')" onblur="_buscacep()" pattern="[0-9]{5}-[0-9]{3}" placeholder="00000-000">  
                                                               
                                                        </div> 
                                   <div class="col-sm-6 col-xs-10">
                                          <label>Endereço</label>
                                          <input type="text" class="form-control" name="_endereco" id="_endereco" value="<?=htmlspecialchars($_retorno->{'Nome_Rua'}); ?>">
                                   </div>
                                   <div class="col-sm-3 col-xs-2">
                                          <label>Nº</label>
                                          <input type="text" class="form-control" name="_numendereco" id="_numendereco" value="<?=htmlspecialchars($_retorno->{'Num_Rua'}); ?>">
                                   </div>
                            </div>
                            <div class="row">
                                   <div class="col-sm-6">
                                          <label>Bairro</label>
                                          <input type="text" class="form-control" name="_bairro" id="_bairro" value="<?=htmlspecialchars($_retorno->{'BAIRRO'}); ?>">
                                   </div>
                                   <div class="col-sm-6">
                                          <label>Complemento</label>
                                          <input type="text" class="form-control" name="_complemento" id="_complemento" value="<?=htmlspecialchars($_retorno->{'COMPLEMENTO'}); ?>">
                                   </div>
                            </div>

                            <div class="row">
                                   <div class="col-sm-4">
                                          <label>Cidade</label>
                                          <input type="text" class="form-control" name="_cidade" id="_cidade" value="<?=htmlspecialchars($_retorno->{'CIDADE'}); ?>">
                                   </div>
                                   <div class="col-sm-2">
                                          <label>UF</label>
                                          <input type="text" class="form-control" name="_estado" id="_estado" value="<?=htmlspecialchars($_retorno->{'UF'}); ?>">
                                   </div>
                                   <div class="col-sm-6">
                                          <label>Proximidade</label>
                                          <input type="text" class="form-control" name="_proximidade" id="_proximidade" value="<?=htmlspecialchars($_retorno->{'LOCAL_REFERENCIA'}); ?>">
                                   </div>
                            </div>
                            <div id="retmsg"> </div>



                     </div>

              </div>
                      <div class="row">
                                   <div class="col-sm-12 text-center">
                                          <?php if($_retorno->{'CODIGO_CONSUMIDOR'} != "" ) { 
                                                 ?>
                                                 <button type="button" class="btn btn-success waves-effect" id="_P0003" onclick="P00031()"><i class="fa  fa-save"></i> Continuar</button>
                                                 <?php
                                               }   else{
                                                 ?>
                                                 <button type="button" class="btn btn-success waves-effect" id="_P0003" onclick="P0003()"><i class="fa  fa-save"></i> Continuar</button>
                                                 <?php

                                                 }
                                         
                                          ?>
                                   
                                  
                                          <button type="button" class="btn btn-inverse waves-effect" id="_PBK00" onclick="PBK100()"><i class="fa  fa-arrow-left"></i> Voltar</button>
                                   </div>
                            </div>
                

       </div>



<?php
       exit();
}

 
 if ($_acao == 33) {     //validar E SALVAR CONSUMIDOR

       try {
              //DADOS CLIENTES
          //    print_r( $_parametros);
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["prisma_doc"];
	$rgie = $_parametros["_rgie"];
	$tipo = $_parametros['_tipopessoa'];
	$tipocliente = $_parametros['TIPO_CLIENTE'];
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

       $tecnico_e = 0;

           
              $msg = "";
              
             
                     if ($cpfcnpj != ""){
                          
                            //valida tipo                             
                                   $cpfcnpj = preg_replace('/[^0-9]/', '', (string) $cpfcnpj);                                   
                                   if($tipocliente =="option1") //cpf
                                   {
                                          $ret  = validaCPF($cpfcnpj);
                                          if($ret == false){                            
                                                 $msg = $msg." CPF INVALIDO, Verifique !!! ";
                                          }   
                                          $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
                                          substr($cpfcnpj, 3, 3) . '.' .
                                          substr($cpfcnpj, 6, 3) . '-' .
                                          substr($cpfcnpj, 9, 2);                         
                                        
                                   } else {
                                          $ret  = validar_cnpj($cpfcnpj);
                                          if($ret == false){                                          
                                                 $msg = $msg." CNPJ INVALIDO, Verifique !!! ";                                                 
                                          }
                                          $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                          substr($cpfcnpj, 2, 3) . '.' .
                                          substr($cpfcnpj, 5, 3) . '/' .
                                          substr($cpfcnpj, 8, 4) . '-' .
                                          substr($cpfcnpj, -2);
                            
                     } 
                                    
              }

                     if (trim($cep) == ""){
                            $msg = $msg."- CEP<br>"; 
                     }
                     if (trim($endereco) == ""){
                            $msg = $msg."- ENDEREÇO<br>"; 
                     }
                     if (trim($numerocasa) == ""){
                            $msg = $msg."- NÚMERO DO ENDEREÇO<br>"; 
                     }
                     if (trim($celular_completo) == "" AND trim($celular_completo2) == "" AND trim($fixo_completo) == "" ){
                            $msg = $msg."- INFORME UM TELEFONE<br>"; 
                     }
                     if (trim($msg) != ""){
                            $msg = " Verifique os campos:<br>".$msg; 
                     }
                   

                   
                        
           

           if($msg != "") { ?>
              <div class="alert alert-danger alert-dismissable" style="margin:5px ;"><?=$msg;?></div>
              <hr />
              <?php } 

       } catch (PDOException $e) {
              ?>
                  <div>                     
                              <h2>xxx <?= "Erro: " . $e->getMessage() ?></h2>
                              <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                       
                  </div>
          <?php
              }


 }

 
if ($_acao == 331) {   //verficar se existe produto e OS
       try {
              //DADOS CLIENTES
          //    print_r( $_parametros);
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["prisma_doc"];
	$rgie = $_parametros["_rgie"];
	$tipo = $_parametros['_tipopessoa'];
	$tipocliente = $_parametros['TIPO_CLIENTE'];
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

       $tecnico_e = 0;

           
              $msg = "";
              
             
                     if ($cpfcnpj != ""){
                          
                            //valida tipo                             
                                   $cpfcnpj = preg_replace('/[^0-9]/', '', (string) $cpfcnpj);                                   
                                   if($tipocliente =="option1") //cpf
                                   {
                                          $ret  = validaCPF($cpfcnpj);
                                          if($ret == false){                            
                                                 $msg = $msg." CPF INVALIDO, Verifique !!! ";
                                          }   
                                          $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
                                          substr($cpfcnpj, 3, 3) . '.' .
                                          substr($cpfcnpj, 6, 3) . '-' .
                                          substr($cpfcnpj, 9, 2);                         
                                        
                                   } else {
                                          $ret  = validar_cnpj($cpfcnpj);
                                          if($ret == false){                                          
                                                 $msg = $msg." CNPJ INVALIDO, Verifique !!! ";                                                 
                                          }
                                          $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                          substr($cpfcnpj, 2, 3) . '.' .
                                          substr($cpfcnpj, 5, 3) . '/' .
                                          substr($cpfcnpj, 8, 4) . '-' .
                                          substr($cpfcnpj, -2);
                            
                     } 
                                    
              }

                     if (trim($cep) == ""){
                            $msg = $msg."- CEP<br>"; 
                     }
                     if (trim($endereco) == ""){
                            $msg = $msg."- ENDEREÇO<br>"; 
                     }
                     if (trim($numerocasa) == ""){
                            $msg = $msg."- NÚMERO DO ENDEREÇO<br>"; 
                     }
                     if (trim($celular_completo) == "" AND trim($celular_completo2) == "" AND trim($fixo_completo) == "" ){
                            $msg = $msg."- INFORME UM TELEFONE<br>"; 
                     }
                     if (trim($msg) != ""){
                            $msg = " Verifique os campos:<br>".$msg; 
                     }
                   

                   
                        
                          
            

           if($msg != "") { ?>
              <div class="alert alert-danger alert-dismissable" style="margin:5px ;"><?=$msg;?></div>
              <hr />
              <?php } 

       } catch (PDOException $e) {
              ?>
                  <div>                     
                              <h2>xxx <?= "Erro: " . $e->getMessage() ?></h2>
                              <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                       
                  </div>
          <?php
              }
       }

       
if ($_acao == 332) {   //verficar se existe ORDEM DE SERVIÇO     

       if(trim($_parametros['_idcliente_sel']) != "") {
 
             //DADOS CLIENTES
         //    print_r( $_parametros);
        $nome = $_parametros["_nome"];
        $nomefantasia = $_parametros["_nomefantasia"];
        $cpfcnpj = $_parametros["prisma_doc"];
        $rgie = $_parametros["_rgie"];
        $tipo = $_parametros['_tipopessoa'];
        $tipocliente = $_parametros['TIPO_CLIENTE'];
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
 
        $tecnico_e = 0;
 
            
               $msg = "";
               
                             //update
                                    //--------------------------- ALTERAR  --------------------------------------------------------------------//
                                    $sql = "UPDATE " .$_BASE.".consumidor  SET
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
                             
                                //    echo $_parametros["_idcliente_sel"].$dddCelular2.$celular2;
                                 
                                    try {
                                           $stm->execute();
                                    }
                                    catch (\Exception $fault){
                                                 $response = $fault;
                                    }
 ;
 
                                    echo $response ;
 
 
        $sql = "SELECT 
            chamada.descricao AS PRODUTO,
            Modelo,
            CODIGO_CHAMADA,
            DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y') AS data1,
            S.DESCRICAO AS descsit,
            cor_sit
        FROM " . $_BASE . ".chamada 
        LEFT JOIN " . $_BASE . ".consumidor ON chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
        LEFT JOIN " . $_BASE . ".situacaoos_elx AS S ON COD_SITUACAO_OS = SituacaoOS_Elx
        WHERE chamada.CODIGO_CONSUMIDOR = :id_cliente
        GROUP BY 
            CODIGO_CHAMADA, PRODUTO, marca, Modelo, S.DESCRICAO, cor_sit";

       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(':id_cliente', $_parametros['_idcliente_sel']);
       $stmt->execute();

       $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        $totalreg = $stmt->rowCount() ;       
     
        if ($totalreg > 0) {
             
               $retorno = $resultados; 
    
 ?>
 
        <div class="container">
               <!-- Page-Title -->        
                      <div class="panel-body">
                           
                             <div id="divProdutoCli">
                                    <div class="card-box text-left">
                                           <h4 class="text-dark header-title m-t-0">ATENDIMENTOS CADASTRADOS</h4>
                                           <input type="hidden" class="form-control" name="_idcliente_sel" id="_idcliente_sel" value="<?=htmlspecialchars($_parametros['_idcliente_sel']);?>">
               <?php foreach ($retorno as $row) {
                             
        ?>
 
               
               <div class="row">
                             
                                    <div class="col-sm-1 col-xs-12" style="margin-left: 10px;">                                   
                                          <h4>O.S <span class="dropcap text-primary" style="float: right;"><?= $row['CODIGO_CHAMADA'] ?></span></h5>
                                    </div>                                    
                                    <div class="col-sm-10 col-xs-12 "> 
                                           <div class="member-info">
                                                  <h4 class="m-t-0 text-custom"><b><span class="badge badge-<?=$row['cor_sit'];?> m-l-0"><?= $row['descsit'] ?></span> </b></h4>
                                                  <p class="text-dark m-b-5"><span class="text-inverse">Produto: </span><b><?= $row['PRODUTO'] ?></b></p>
                                                  <p class="text-dark m-b-5"><span class="text-inverse">Modelo: </span> <b><?= $row['Modelo'] ?></b></p>
                                                  <p class="text-dark m-b-5"><span class="text-inverse">Data Abertura: </span> <b><?= $row['data1'] ?></b></p> 
                                           </div>
                                    </div>                                    
                      </div>
               <?php 
               }
               
               ?>
                      </div>
                 </div>
                                        
 
 
                      </div>
                      </div>
 
               </div>
               <div class="row">
                      <div class="col-sm-12 text-center">
                      <button type="button" class="btn btn-success waves-effect" id="_P0004" onclick="P00033()"><i class="fa  fa-plus-square"></i> Agendar Novo Atendimento</button>
                             <button type="button" class="btn btn-inverse waves-effect" id="_PBK00" onclick="PBK00()"><i class="fa fa fa-times"></i> Fechar</button>
                      </div>
               </div>
 
            
 
        </div>
 
        <?php exit();
        }
        }        
 }

if ($_acao == 333) {   //verficar se existe produto e OS
     

      if(trim($_parametros['_idcliente_sel']) != "") {

            //DADOS CLIENTES
          //    print_r( $_parametros);
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["prisma_doc"];
	$rgie = $_parametros["_rgie"];
	$tipo = $_parametros['_tipopessoa'];
	$tipocliente = $_parametros['TIPO_CLIENTE'];
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

       $tecnico_e = 0;

           
              $msg = "";
              
                            //update
                            	//--------------------------- ALTERAR  --------------------------------------------------------------------//
                                   $sql = "UPDATE " .$_BASE.".consumidor  SET
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
                            
                               //    echo $_parametros["_idcliente_sel"].$dddCelular2.$celular2;
                                
                                   try {
                                          $stm->execute();
                                   }
                                   catch (\Exception $fault){
                                                $response = $fault;
                                   }
;

                                   echo $response ;



                                   $sql = "SELECT descricao, Modelo, serie, marca, PNC
                                   FROM " . $_BASE . ".chamada 
                                   LEFT JOIN " . $_BASE . ".consumidor ON chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
                                   WHERE chamada.CODIGO_CONSUMIDOR = :id_cliente
                                   GROUP BY descricao, marca, serie, Modelo, PNC";
                           
                               $stmt = $pdo->prepare($sql);
                               $stmt->bindParam(':id_cliente', $idCliente);
                               $stmt->execute();
                           
                               $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

       $totalreg = $consulta->rowCount() ;       
    
       if ($totalreg > 0) {
              $_msgproduexite = "selecione acima ou ";
              $retorno = $consulta->fetchAll(); 
   
?>

       <div class="container">
              <!-- Page-Title -->
            

       
                     <div class="panel-body">
                          
                            <div id="divProdutoCli">
                                   <div class="card-box text-left">
                                          <h4 class="text-dark header-title m-t-0">LISTA PRODUTOS CADASTRADOS</h4>
                                          <input type="hidden" class="form-control" name="_idcliente_sel" id="_idcliente_sel" value="<?=$_parametros['_idcliente_sel'];?>">
              <?php foreach ($retorno as $row) {
                                          if ($row['descricao'] != "") {
				//$equi = $row['descricao'].";".$row['marca'].";".$row['Modelo'].";".$row['serie'];
				$equi = $row['descricao'].";".$row['marca'].";".$row['Modelo'].";".$row['serie'].";;".$row['Nota_Fiscal'].";".$row['Data_Nota'].";".$row['VOLTAGEM'].";".$row['Revendedor'].";".$row['cnpj'].";".$row['PNC'];
				
	?>

		
              <div class="row">
                 
					
				
				
                                   <div class="col-sm-1 col-xs-12" style="margin-left: 10px;"> 
                                  
						<button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="P0044('<?=$equi;?>')"> <i class="fa fa-plus-square"></i> Selecionar </button>
					</div>
                                   
                                   <div class="col-sm-10 col-xs-12 "> 
                                          <div class="member-info">
                                                 <h4 class="m-t-0 text-custom"><b><?= $row['descricao'] ?> </b></h4>
                                                 <p class="text-dark m-b-5"><b>Modelo: </b> <span class="text-muted"><?= $row['Modelo'] ?></span></p>
                                                 <p class="text-dark m-b-5"><b>Série: </b> <span class="text-muted"><?= $row['serie'] ?></span> </span></p>

                                          </div>
                                   </div>
					
				
                           
			
			</div>
		<?php }
              }
		
		?>
              <div class="row" >
			
				<div  style="margin-left:20px ; margin-top:20px ">
									<p><strong>* Para NOVO ATENDIMENTO <?= $_msgproduexite;?>clique em Novo Produto</strong></p>
                                                               <button type="button" class="btn btn-success waves-effect" id="_P0004" onclick="P00033()"><i class="fa  fa-save"></i>  Novo Produto</button>
								</div>
                                                        

			
			</div>
                                   </div>
                            </div>
                                       


                     </div>
                     </div>

              </div>
              <div class="row">
                     <div class="col-sm-12 text-center">
                          
                            <button type="button" class="btn btn-inverse waves-effect" id="_PBK00" onclick="PBK00()"><i class="fa fa fa-times"></i>Cancelar</button>
                     </div>
              </div>

           

       </div>

       <?php exit();
       }
       }
       
}


if ($_acao == 3) {   //produto

       try {
              //DADOS CLIENTES
          //    print_r( $_parametros);
	$nome = $_parametros["_nome"];
	$nomefantasia = $_parametros["_nomefantasia"];
	$cpfcnpj = $_parametros["prisma_doc"];
	$rgie = $_parametros["_rgie"];
	$tipo = $_parametros['_tipopessoa'];
	$tipocliente = $_parametros['TIPO_CLIENTE'];
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

       $tecnico_e = 0;

           
              $msg = "";
              
             
                  
                   

                     if(trim($_parametros["_idcliente_sel"]) != "") {
                     

                     }else{
                            //insert
                            //--------------------------- INCLUIR --------------------------------------------------------------------//

                                   //insere cadastro consumidor
                                   $sql = "insert into " . $_BASE.".consumidor (
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
                                          $id_cliente = $pdo->lastInsertId();
       
                     try{			
                            $_tipoAtividade = 100;
                            $_documentoAtividade = 0;
                            $_assuntoAtividade = "Portal- Novo Cliente";
                            $_descricaoAtividade = "$nome - $bairro ";
                            $stm = $pdo->prepare("INSERT INTO " . $_BASE . ".atividades (
                                   at_id,
                                   at_datahora,                                   
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
                                   $stm->bindParam(2, $_tipoAtividade);	
                                   $stm->bindParam(3, $id);				
                                   $stm->bindParam(4, $_documentoAtividade);					
                                   $stm->bindParam(5, $_assuntoAtividade);	
                                   $stm->bindParam(6, $_descricaoAtividade);		
                                   $stm->execute();			
                                   
                                   
                                          }
                                          catch (\Exception $fault){
                                                        $response = $fault;
                                          }
       
                     }
                        
                


       } catch (PDOException $e) {
              ?>
                  <div>                     
                              <h2>xxx <?= "Erro: " . $e->getMessage() ?></h2>
                              <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                       
                  </div>
          <?php
              }

              if(trim($_parametros['_idcliente_sel']) == "") {
                      $_parametros['_idcliente_sel'] = $id_cliente;
              }
       
   
       ?>
       
              <div class="container">
                     <!-- Page-Title -->
                     <div class="row">
                            <div class="col-sm-12">
                                   <input type="hidden" class="form-control" name="_idcliente_sel" id="_idcliente_sel" value="<?=$_parametros['_idcliente_sel'];?>">
                                   <h4 class="page-title">PRODUTO</h4>
                                   <ol class="breadcrumb">
                                          <li class="active"> <strong>Identifique seu produto</strong></li>
                                   </ol>
                            </div>
                     </div>
       
              
                            <div class="panel-body">
                                
                                   <div id="divProdutoCli">
                                          <div class="card-box text-left">
                                               
                                                
                                                 <?php
       
                                                 if (0 > 0) {
                                                 } else {
                                                      
                                                        ?>
                                     <div class="row" id="retnovoproduto">
                                          <?php /*
                                                               <div class="col-xs-12 col-lg-12"   >    
                                                                       <p>o atendimento é para produto em <strong>Garantia</strong> ou <strong>Fora de Garantia</strong></p>
                                                                                    <div class="radio radio-info radio-inline">
                                                                                           <input type="radio" id="_PD001" onclick="PD101()" value="2" name="radioTipo">
                                                                                           <label for="inlineRadio1"> GARANTIA</label>
                                                                                    </div>
                                                                                    <div class="radio radio-warning  radio-inline">
                                                                                           <input type="radio" id="_PD002"  onclick="PD101()" value="1" name="radioTipo"  >
                                                                                           <label for="inlineRadio2"> FORA GARANTIA </label>
                                                                                    </div>
                                                                                    
                                                                 </div>
*/ ?>
                                                                 <div  id='rettipoos'></div>
                                                                             </div>
                                                                                   <div class="row ">
                                                                                   
                                                                                                                       
                                                                                                                       <div class="col-sm-6 col-xs-12">
                                                                                                                              <label >Marca / Fabricante </label>                                                                                                                
                                                                                                                              <select name="newmarca" id="newmarca" class="form-control ">
                                                                                                                                     <?php
                                                                                                                                      
                                                                                                                                     $consulta = $pdo->query("SELECT * FROM ". $_BASE .".fabricante where for_Tipo = 1 and Fabricante_CODIGO_LOGIN = 1 ORDER BY NOME");
                                                                                                                                     $retorno = $consulta->fetchAll();
                                                                                                                                     foreach ($retorno as $row) {
                                                                                                                                             ?><option value="<?php echo htmlspecialchars($row["CODIGO_FABRICANTE"]); ?>"><?php echo htmlspecialchars($row["NOME"]); ?></option><?php
                                                                                                                                            }
                                                                                                                                            
                                                                                                                                            ?>
                                                                                                                              </select>
                                                                                                                       </div>           
                                                                                                         </div>							
       
                                                                                                         <div class="row " >                                                                                                 
                                                                                                                <div class="col-sm-6 col-xs-12">
                                                                                                                       <label >Informe o modelo ou descrição do Produto</label>
                                                                                                                       <input type="text" id="busca-aparelho" name="busca-aparelho" autocomplete="off" class="form-control " autocomplete="off" placeholder="EX: PE11X OU PURIFICADOR" onkeyup="_aparelhoBusca()">
                                                                                                                </div>      
                                                                                                         </div>
                                                                                                         <div class="row " id="retorno-aparelho">
                                                                                                                  <div class="col-sm-6 col-xs-12" style="height: 350px;  overflow-x: auto;" id="pesquisaaparelho">
                                  
                               </div>
                                                        <?php
                                                 } ?>
       
       
                            </div>
                            </div>
       
                     </div>
                     <div class="row">
                            <div class="col-sm-12 text-center">
                                   <button type="button" class="btn btn-success waves-effect" id="_P0004" onclick="P0004()"><i class="fa  fa-save"></i> Continuar</button>
                                   <button type="button" class="btn btn-inverse waves-effect" id="_PBK00" onclick="PBK00()"><i class="fa fa fa-times"></i> Cancelar</button>
                            </div>
                     </div>
       
                  
       
              </div>
       
       <?php
              exit();
       }

if ($_acao == 4) {   //DESCRICAO DEFEITO
     
      // _r($_parametros);

       ?>
        <div class="container">
              <!-- Page-Title -->
              <div class="row">
                            <div class="col-sm-12">
                            <input type="hidden" class="form-control" name="_idcliente_sel" id="_idcliente_sel" value="<?=$_parametros['_idcliente_sel'];?>">
                                   <h4 class="page-title">DEFEITO</h4>
                                   <ol class="breadcrumb">
                                          <li class="active">informe o defeito do <strong>PRODUTO</strong></li>
                                   </ol>
                            </div>
                     </div>

              <div class="row">
                            <div class="col-sm-12 col-xs-12 text-left">
                                   <div class="form-group">
                                          <label >Descrição do Defeito / Motivo </label>   
                                          <textarea name="defeito" rows="2" type="text" class="form-control " > </textarea>
                                          
                                   </div>
                                   <div id="retmsg"> </div>
                                          <div class="row">
                                                 <div class="col-sm-12 text-center">
                                                        <button type="button" class="btn btn-success waves-effect" id="_P0005" onclick="P0006()"><i class="fa  fa-save"></i> Salvar e Gerar Atendimento</button>
                                                        <button type="button" class="btn btn-inverse waves-effect" id="_PBK00" onclick="PBK00()"><i class="fa fa fa-times"></i> Fechar</button>
                                                 </div>
                                          </div>
                                   </div>
              </div>
        </div>
      <?php
       exit();
}


if ($_acao == 66) {   //valida dados primeira tela
       try {
           
              $msg = "";
             // print_r($_parametros);
             
                     if (trim($_parametros["defeito"] == "")){
                            $msg = $msg." Informe o Defeito Produto ou Motivo do atendimento !!! ";
                     }
            

           if($msg != "") { ?>
              <div class="alert alert-danger alert-dismissable" style="margin:5px ;"><?=$msg;?></div>
              <hr />
              <?php } 

       } catch (PDOException $e) {
              ?>
                  <div>                     
                              <h2>xxx <?= "Erro: " . $e->getMessage() ?></h2>
                              <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                       
                  </div>
          <?php
              }

      

}
      

if ($_acao == 6) {   //PRE-ATENDIMENTO
       //gerar OS
       date_default_timezone_set('America/Sao_Paulo');
       
       $dia       = date('d');
       $mes       = date('m');
       $ano       = date('Y');

       $data_atual      = $dia . "/" . $mes . "/" . $ano;
       $data_atualb  = $ano . "-" . $mes . "-" . $dia ;
       $hora = date("H:i:s");

       $datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

       $_prodlink = explode(";",$_parametros['_prodlink']);
       $defeito = trim($_parametros["defeito"]);
       $indaparelho = trim($_parametros["_prodid"]);
     //  $tipogarantia = trim($_parametros["_tipatend"]);
     //  if($tipogarantia == ''){
              $tipogarantia = 1;
     //  }
       $dtnf  = trim($_parametros["_dtnf"]);
       if($indaparelho == 1) { // 1 selecionado
              $indaparelho = 0; 
       }else{
              $indaparelho = 1; 
       }

       $desc = $_prodlink[0];
       $marca = $_prodlink[1];    
       $modelo = $_prodlink[2];      
       $aparelho = $_prodlink[3];
       $serie = $_prodlink[4];
       $voltagem = $_prodlink[5];
       $notafiscal = $_prodlink[6];
       $datanf = $_prodlink[7];
       $revendedor = $_prodlink[8];
       $cnpj = $_prodlink[9];
       $PNC = $_prodlink[10];
      
       if($cnpj == "") { 
              $cnpj = " ";
       }
       if($PNC == "") { 
              $PNC = " ";
       }

       //buscar numero da OS
       $SQL = "Select parametro_ULTIMAOS,numero_sitPreAtend,
       tokenwats,serviceId,urlwats,NOME_FANTASIA,TELEFONE from ". $_BASE .".parametro";     
       $consulta = $pdo->query("$SQL");
       $retorno = $consulta->fetchAll();
       foreach ($retorno as $row) {
              $_sitelx = $row["numero_sitPreAtend"];
              $codigoos = $row["parametro_ULTIMAOS"];
              $numeroos1 = $codigoos + 1;
              $tokenwats = 'Authorization: Bearer '.$row["tokenwats"];
              $serviceId =  $row["serviceId"] ;
              $urlwats = $row["urlwats"];
              $EMPRESANOME = $row["NOME_FANTASIA"];
              $EMPRESATELEFONE = $row["TELEFONE"];
              }
              
       $SQL = "Update ". $_BASE .".parametro set parametro_ULTIMAOS = '$numeroos1' ";  
       $stm = $pdo->prepare("$SQL");     
       $stm->execute();              

       if($dtnf != "") {
              $var_dtnf  = ',Data_Nota';
              $var_dtnf_A  = ',?';
       }

       $INSERT = "INSERT INTO ". $_BASE .".chamada(CODIGO_CONSUMIDOR,CODIGO_CHAMADA,DATA_CHAMADA,
                  CODIGO_APARELHO,marca,descricao,Modelo,Serie,Voltagem,Nota_Fiscal,Data_Nota,CODIGO_SITUACAO,SituacaoOS_Elx,
                  Revendedor,cnpj,PNC,DEFEITO_RECLAMADO,Ind_Historico,GARANTIA $var_dtnf) 
                  values (? ,?,CURRENT_DATE() ,
                  ?,?,?,?, ? ,? ,?, ?,'0','3',
                  ?,?,?,?,?,? $var_dtnf_A)";
        $stm = $pdo->prepare("$INSERT"); 
        $stm->bindParam(1, $_parametros['_idcliente_sel']);		
        $stm->bindParam(2, $codigoos);	
        $stm->bindParam(3, $aparelho);	
        $stm->bindParam(4, $marca);	
        $stm->bindParam(5, $desc);
        $stm->bindParam(6, $modelo);
        $stm->bindParam(7, $serie);
        $stm->bindParam(8, $voltagem);
        $stm->bindParam(9, $notafiscal);
        $stm->bindParam(10, $datanf);
        $stm->bindParam(11, $revendedor);
        $stm->bindParam(12, $cnpj);
        $stm->bindParam(13, $PNC);
        $stm->bindParam(14, $defeito);
        $stm->bindParam(15, $indaparelho); //  1 verificar aparelho
        $stm->bindParam(16, $tipogarantia);
        if($dtnf != "") {
              $stm->bindParam(17, $var_dtnf ); //  data nf
        }
        $stm->execute();	       

        $sql = "INSERT INTO " . $_BASE . ".acompanhamento (ac_data, ac_hora, ac_OS, ac_usuarionome, ac_cliente, ac_descricao, ac_sitos) VALUES (CURDATE(), CURTIME(), :codigo_os, :login, :id_cliente, 'PRE-ATENDIMENTO', :sitelx)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigo_os', $codigoos);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':id_cliente', $idCliente);
    $stmt->bindParam(':sitelx', $siteLX);
    $stmt->execute();
	 
			
             $_tipoAtividade = 200;
             $_documentoAtividade = $codigoos;
             $_assuntoAtividade = "Nova O.S";
             $_descricaoAtividade = "nº $codigoos  $marca";
             $stm = $pdo->prepare("INSERT INTO ". $_BASE .".atividades (
                    at_id,
                    at_datahora,                 
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
                           ?); ");
                    $stm->bindParam(1, $datahora);
                    $stm->bindParam(2, $_tipoAtividade);	
                    $stm->bindParam(3, $_parametros['_idcliente_sel']);				
                    $stm->bindParam(4, $_documentoAtividade);					
                    $stm->bindParam(5, $_assuntoAtividade);	
                    $stm->bindParam(6, $_descricaoAtividade);		
                    $stm->execute();
     
       ?>
        <div class="container">
              <!-- Page-Title -->
              <div class="row">
                            <div class="col-sm-12">
                                   <h4 >PRE-ATENDIMENTO</h4>
                                  
                            </div>
                     </div>
              <div class="row">
                            <div class="col-sm-12 col-xs-12 text-center">
                                 
                                          <div class="row">
                                          <div class="bg-icon pull-request">
                                                 
                                                 <img src="img_004.jpg" alt="image" class="img-responsive center-block" width="200"/>
                                                 <h5>Concluído com sucesso! </h5>
                                                 <h2>Nº O.S <?=$codigoos;?> </h2>
                                                 <h4>Será enviado no WHATSAPP confirmação atendimento </h4>
                                                 <?php if($_totem == "TX") { ?>
                                                        <h4>Para agilizar o atendimento, informe o número da Ordem de serviço à recepcionista.</h4>
                                                 <?php }
                                                 
                                                 //enviar whats de integração ----------------------------------------------------
                                                   

                                                        try{		
                                                               $_assuntoAtividade = "Disparo Msg Whats - pre-cadastro";
                                                               $_tipoAtividade = 89;
                                                               $_documentoAtividade = $codigoos;
                                                               
                                                               
                                                               $stm = $pdo->prepare("INSERT INTO ". $_BASE .".logsistema (
                                                               l_tipo,
                                                               l_datahora,
                                                               l_doc,						
                                                               l_desc,
                                                               l_conferi) 
                                                                      VALUES (
                                                                      ?,
                                                                      ?,                                                                      				
                                                                      ?,
                                                                      ?,
                                                                      ?
                                                                      ); ");
                                                               $stm->bindParam(1, $_tipoAtividade);
                                                               $stm->bindParam(2, $data);	
                                                               $stm->bindParam(3, $_documentoAtividade);			
                                                               
                                                               $stm->bindParam(5, $_assuntoAtividade);		
                                                               $stm->bindParam(6, $_confericampos);					
                                                               $stm->execute();			
                                                        

                                                        }
                                                        catch (\Exception $fault){
                                                               $response = $fault;
                                                        }

                                                     
                                                       //REFAZER ANOTACAO DIA 10/09 -----------------------------------------
                                                       
                                                       
                                                       /*
                                                     
                                                               //buscar telefone wats cadastro consumidor
                                                               $_telefone = "";

                                                               $sql = "SELECT DDD, FONE_CELULAR FROM " . $_BASE . ".consumidor WHERE CODIGO_CONSUMIDOR = :id_cliente";

                                                               $stmt = $pdo->prepare($sql);
                                                               $stmt->bindParam(':id_cliente', $_parametros['_idcliente_sel']);
                                                               $stmt->execute();

                                                               $retorno = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                               foreach ($retorno as $row) {
                                                                      if($_telefone == "" ){
                                                                             $_telefone = $row["DDD"].$row["FONE_CELULAR"];
                                                                      }
                                                                      }
                                                            
                                                        if( $_telefone != "" and $tokenwats != "") { 
                                                               $_telefone = str_replace(".", "",  $_telefone);
                                                               $_telefone = str_replace(" ", "",  $_telefone);
                                                               $_telefone = str_replace("-", "",  $_telefone);
                                                               $_telefone = "55".$_telefone;
                                                               $documento = $_parametros['chamada'];
                                                        
                                                            

                                                               $sql = "Select whats_mensagem,dontOpenTicket		
                                                               from ". $_BASE .".msg_whats 				
                                                               where  whats_tipo = '3'  limit 1  "; 
                                                               $stm = $pdo->prepare("$sql");            
                                                               $stm->execute();
                                                                             
                                                                             if ( $stm->rowCount() > 0 ){
                                                                                    while($row = $stm->fetch(PDO::FETCH_OBJ)){
                                                                                    
                                                                                           $dontOpenTicket = $row->dontOpenTicket;
                                                                                           $_msg = $row->whats_mensagem;  
                                                                                           $mensagem = $_msg;
				
                                                                                                  $mensagem  = mensagem($mensagem,"[EMPRESANOME]",$EMPRESANOME);
                                                                                                  $mensagem  = mensagem($mensagem,"[EMPRESATELEFONE]",$EMPRESATELEFONE);
                                                                                                  	
                                                                                    }
                                                                             }


                                                               if($dontOpenTicket == 0) {
                                                                 $dontOpenTicket = "false";
                                                               }else{
                                                                $dontOpenTicket = "true";
                                                               }
                                                               
                                                               $_fields = "number=$_telefone&text=".rawurlencode($mensagem)."&serviceId=".$serviceId."&dontOpenTicket=$dontOpenTicket&departmentId=6a1895c4-3383-4152-957f-9cf1c98357ac";

                                                        //   if($wats == 0) {

                                                        
                                                               $curl = curl_init();

                                                               curl_setopt_array($curl, array(
                                                               CURLOPT_URL => $urlwats,
                                                               CURLOPT_RETURNTRANSFER => true,
                                                               CURLOPT_ENCODING => '',
                                                               CURLOPT_MAXREDIRS => 10,
                                                               CURLOPT_TIMEOUT => 15,
                                                               CURLOPT_FOLLOWLOCATION => true,
                                                               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                               CURLOPT_CUSTOMREQUEST => 'POST',
                                                               CURLOPT_POSTFIELDS => ''.$_fields.'',
                                                               CURLOPT_HTTPHEADER => array(
                                                               ''.$tokenwats.'',
                                                               'Content-Type: application/x-www-form-urlencoded'
                                                               ),
                                                               ));
                                                               
                                                               $response = curl_exec($curl);        
                                                               curl_close($curl);
                                                        

                                                               $obj = json_decode($response);

                                                        }

                                                     */

                                                        

                                                        ?>

                                                        <?php  
                                                        

                                                 //fim whats
                                                 
                                                 
                                                 ?>
                                                 <button type="button" class="btn btn-inverse waves-effect" id="_PBK00" onclick="PBK00()"> Fechar</button>
                                          </div>
                                          </div>
                                   </div>
              </div>
        </div>
      <?php
       exit();
}

if ($_acao == 7) { //buscar tipo produto
       try {
           $idlinha =0;
           if($_parametros["modelo-linhaI"] != "" OR $_parametros["modelo-linhaA"] != "")  {
               if($_parametros["modelo-linhaI"] != ""  )  {
                   $idlinha = $_parametros["modelo-linhaI"];
               }else{
                   $idlinha = $_parametros["modelo-linhaA"];
               }
               
   
               ?>
               <option value="">Selecione</option> <?php
   
   
           }else {
               $idlinha = $_parametros["modelo-linha"];
               ?>
               <option value="">Todos</option>
           <?php
   
           }
         
        
         
           if($idlinha > 0) {
               $sql = "Select * from ". $_BASE .".aparelho_produto           
                   WHERE ap_prodAtivo = '1' and  ap_prodLinha = :id           
                   order by ap_prodd";                  
               $statement = $pdo->prepare($sql);
               $statement->bindParam(':id', $idlinha);
           }else{
               $sql = "Select * from ". $_BASE .".aparelho_produto where ap_prodAtivo = '1'
               order by ap_prodd";                  
                $statement = $pdo->prepare($sql);      
           }
           $statement->execute();
           if ( $statement->rowCount() > 0 ){        
               while ($row = $statement->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
             {   
               ?><option value="<?=$row->ap_prodId;?>"><?=$row->ap_prodd;?></option><?php
             }
           }
          
           
           
       } catch (PDOException $e) {
       }
   } 



   if ($_acao == 8) { //buscar tipo produto
   //PESQUISAR MODAL DE PRODUTO
	 $descricao = ($_parametros["busca-aparelho"]);
	 if(strlen($descricao) <= 2) { ?>
                            <th >
                                   <div class="alert alert-warning text-center" style="margin:15px ;">
                                          Digite mais 3 letras para pesquisar
                                   </div>
                            </th>
                     <?php 
                     exit();
              }
	if( $descricao <> ''  ) {   
		//$sql = "SELECT MODELO,DESCRICAO, fabricante.nome as fornecedor,mes_preventivo,aparelho_codProduto
		//FROM  ". $_BASE .".aparelho 
		//left JOIN  ". $_BASE .".fabricante on  fabricante.CODIGO_FABRICANTE  = aparelho.CODIGO_FABRICANTE	
		//WHERE aparelho.CODIGO_FABRICANTE = '".$_parametros["newmarca"]."' AND DESCRICAO like '%$descricao%' and DESCRICAO <> '' OR 
		//aparelho.CODIGO_FABRICANTE = '".$_parametros["newmarca"]."' AND nome like '%$descricao%' AND nome <> '' OR 
		//aparelho.CODIGO_FABRICANTE = '".$_parametros["newmarca"]."' AND MODELO like '%$descricao%' AND MODELO <> '' 
		//order by DESCRICAO,MODELO,NOME";        
                    
              //$statement = $pdo->prepare($sql);               
              //$statement->execute();

              $sql = "SELECT 
            MODELO, DESCRICAO, fabricante.nome as fornecedor, 
            mes_preventivo, aparelho_codProduto
           FROM " . $_BASE . ".aparelho 
           LEFT JOIN " . $_BASE . ".fabricante ON fabricante.CODIGO_FABRICANTE = aparelho.CODIGO_FABRICANTE 
           WHERE 
            aparelho.CODIGO_FABRICANTE = :marca";


       if (!empty($descricao)) {
              $sql .= " AND (
                            DESCRICAO LIKE :descricao 
                            OR fabricante.nome LIKE :descricao 
                            OR MODELO LIKE :descricao
                     )";
              $descricaoComLike = "%{$descricao}%";
       }

       $sql .= " ORDER BY DESCRICAO, MODELO, NOME";

       $stmt = $pdo->prepare($sql);


       $stmt->bindParam(':marca', $_parametros["newmarca"]);

       if (!empty($descricao)) {
              $stmt->bindParam(':descricao', $descricaoComLike);
       }

       $stmt->execute();
       $retornos = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $reg =  $stmt->rowCount();
                     
              if ( $stmt->rowCount() > 0 ){        
                 

                                   if($reg == 0) {
                                          ?><th >
                                                 <div class="alert alert-warning text-center" style="margin:15px ;">
                                                        Nenhum produto encontrado<br>Informe abaixo dados para novo cadastro
                                                 </div>
                                                   </th>
                                                        <?php  
                                                 }else{ 
                                                        ?>
                                               
                                                   <table id="datatable-fixed-col" class="table  table-bordered table-hover " >                            
                                                         <tbody >
                                                        <?php
                                                        while ($rst = $statement->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                                        {   
                                                               $ref = $rst->DESCRICAO.";".$rst->fornecedor.";".$rst->MODELO;
                                                               ?>
                                                               <tr>
                                                               <th  onclick="_aparelhoSEL('<?=$ref;?>')"><?=$rst->DESCRICAO;?></th>
                                                               <th  onclick="_aparelhoSEL('<?=$ref;?>')"><?=$rst->MODELO;?></th>
                                                               <th  onclick="_aparelhoSEL('<?=$ref;?>')"><?=$rst->fornecedor;?></th>
                                                              
                                                            
                                                               </tr>
                                                               <?php 
                                                               }
                                                               ?>
                                                                 </tbody>
                                                               </table>
                                                
                                                               <?php
                                                        
                                                        }
                                                 }else{
                                                        ?>
  <th >
                                   <div class="alert alert-info text-center" style="margin:15px ;">
                                   Não encontramos o modelo informado, mas fique tranquilo,<br> esse campo será preenchido pela atendente mais tarde.<br> Aperte em "Continuar" para concluir o cadastro!
                                   </div>
                            </th>
                                                        <?php
                                                 }
       }
  }

  

  if ($_acao == 9) { //modelo selecionado
       $produto = explode(";",$_parametros['_prodlink']);
       ?>
              <div class="card-box" style="margin-top: 5px;">
              <label > Produto Selecionado </label>
					<div class="member-info">
						<h4 class="m-t-0 text-custom"><b><?=$produto[0];?></b>(<span class="text-dark header-title m-t-0"><b><?=$produto[2];?></b></span>
						)</h4>
                                          
						
					</div>
                                   <div style="text-align: right;">
                                                    <button type="button" class="btn btn-white btn-sm waves-effect" id="_PBK00" onclick="proEx()"><i class="fa fa fa-times"></i> <span style="color:red">EXCLUIR PRODUTO</span></button>
                                   </div>
				</div>
       <?php

  }


?>
