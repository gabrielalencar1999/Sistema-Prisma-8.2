<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
//include("../../api/config/iconexao.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Database\MySQL;
$pdo = MySQL::acessabd();



    $contultaNF = $pdo->query("SELECT nfed_numeronf,nfed_empresa,nfed_email, nfed_dNome,nfed_chave,nfed_xml_protocolado  FROM ".$_SESSION['BASE'].". NFE_DADOS WHERE nfed_id =  '".$_parametros['idnfemail']."'");
    $retornoNF = $contultaNF->fetch(PDO::FETCH_ASSOC);
    $nfed_empresa = $retornoNF["nfed_empresa"];
    $nfed_chave = $retornoNF["nfed_chave"];
    $_emailaddAddress = explode(",",$retornoNF["nfed_email"]);
    $_emailaddAddress_1 = $_emailaddAddress[0];
    $_emaileaddAddress_2 = $_emailaddAddress[1];
    $nomecliente =  $retornoNF["nfed_dNome"];
    $_xmlprotocolado = $retornoNF["nfed_xml_protocolado"];
   

    //buscar dados tab empresa
    $empresaNF = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] . ".empresa where empresa_id = '".$nfed_empresa."' ");
    $ret = $empresaNF->fetch(PDO::FETCH_ASSOC);

    $Host       =  $ret["empresa_smtp"]; //'smtp.titan.email';                    
    $SMTPAuth   =  $ret["empresa_SMTPAuth"];//true;                                   
    $Username   =  $ret["empresa_Username"];//'contato@sistemaprisma.com.br';                  
    $Password   =  $ret["empresa_Password"];//'ttitts01';
    $SMTPSecure  =  $ret["empresa_SMTPSecure"];  // PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $Port   =  $ret["empresa_Port"];//587;  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $_nomeEnvio  =  $ret["empresa_contato"];
    $_emailenvio = $ret["empresa_email"];
    $_nomeFantasia =  utf8_decode($ret["empresa_nome"]);

    $_emailenvioCC  = $ret["empresa_emailCC"];

    if($Host == "") {
        ?>
        <div class="bg-icon pull-request">
            <i class="md-5x md-highlight-remove"></i>
            <h3>Ainda não configurado dados p/ envio email</h3>
        </div>
        <?php
      
        exit();
    }




//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
try {
    //Server settings
   //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPDebug =0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = "$Host";                     //Set the SMTP server to send through
    $mail->SMTPAuth   = $SMTPAuth;                                   //Enable SMTP authentication
    $mail->Username   = "$Username";                     //SMTP username
    $mail->Password   = "$Password" ;   
    if($SMTPSecure == 'ssl'){
     
        $mail->SMTPSecure = 'ssl'; 
    }else{
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    }                         //SMTP password
   
   // $mail->SMTPSecure = 'tls'; 
    $mail->Port       = "$Port";                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

   
    //Recipients
    $mail->setFrom($_emailenvio, $_nomeEnvio); //quem esta enviando titulo
    $mail->addAddress($_emailaddAddress_1, $nomecliente);     //Add a recipient
    if($_emailaddAddress_2 != "") {
        $mail->addAddress($_emaileaddAddress_2);               //Name is optional
    }   
    //  $mail->addReplyTo('info@example.com', 'Information');
    if($_emailenvioCC != "") {
        $mail->addCC($_emailenvioCC);
    } 
  //  $mail->addBCC('bcc@example.com');

    //Attachments
  //  $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

   //verificar se existe os arquivos

    if (file_exists("docs/".$nfed_chave.".xml")) {
        //echo "xml existe";
    } else {
        echo "Visualizar impressão da NF antes de enviar email";
        exit();
    }

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "$_nomeFantasia - NF-e $nfed_chave"; // assunto !!!
    $mail->Body    =  utf8_decode('Você está recebendo em anexo o arquivo XML referente a uma Nota Fiscal Eletrônica.
    <br><b>Esse é um e-mail automático. Não é necessário respondê-lo.!</b>');
    $mail->AltBody = utf8_decode('Você está recebendo em anexo o arquivo XML referente a uma Nota Fiscal Eletrônica. Esse é um e-mail automático. Não é necessário respondê-lo');

      //Attachments
    $xmlnome = $nfed_chave.".xml";
   

    $mail->addStringAttachment($_xmlprotocolado,  "$nfed_chave.xml" );
    //$mail->addAttachment($file);         //Add attachments
    $mail->addAttachment("docs/NFE".$nfed_chave.".pdf");         //Add attachments
    
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    $mail->send();
    echo '  <img src="assets/images/small/email-enviado.png" alt="image" class="img-responsive center-block" width="200"/>
              <strong>NF-e Enviada com Sucesso !!! </strong>   ';
} catch (Exception $e) {
    ?>
    <div class="bg-icon pull-request"><?="Ops, algo deu errado no envio mensagem: {$mail->ErrorInfo}";?></div>
    <?php
}
    exit();

