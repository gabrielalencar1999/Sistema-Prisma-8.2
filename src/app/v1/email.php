<?php
exit();
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
//include("../../api/config/iconexao.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Database\MySQL;
$pdo = MySQL::acessabd();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
try {
/*
// -<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'acrefrigeracao.com.br';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;
    $mail->Username   = 'nfe@acrefrigeracao.com.br';                     //SMTP username
    $mail->Password   = 'Celiavaz*56';                               //SMTP password
   //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  // $mail->SMTPAutoTLS = false; // Desabilita o uso de STARTTLS
  $mail->SMTPSecure = 'ssl';
    $mail->Port       = "465";  
                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                              
   $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => true,
            'allow_self_signed' => true
        )
       
    );
*/

//gmail
/*
     //Server settings
     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
     $mail->isSMTP();                                            //Send using SMTP
     $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
     $mail->SMTPAuth = true;
     $mail->Username   = 'autorizadalitoralsantos@gmail.com';                     //SMTP username
     $mail->Password   = 'djnk scmp keak cviw';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
   // $mail->SMTPAutoTLS = false; // Desabilita o uso de STARTTLS
     $mail->SMTPSecure = 'ssl';
     $mail->Port       = "587";  
                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                          
    $mail->SMTPOptions = array(
         'ssl' => array(
             'verify_peer' => false,
             'verify_peer_name' => true,
             'allow_self_signed' => true
         )
        
     );
  

    //Recipients
    $mail->setFrom('nfe@acrefrigeracao.com.br', 'TESTE'); //quem esta enviando titulo
    $mail->addAddress('robsonlopessales@gmail.com', 'Robson User');     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
   // $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');

    //Attachments
  //  $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = '2ASSUNTO NF TESTE CONFIG. PRISMA'; // assunto !!!
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
*/
 //Server settings
 /*
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'email-ssl.com.br';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;
    $mail->Username   = 'nf1@remoservice.com.br';                     //SMTP username
    $mail->Password   = 'Remo2024@#';                               //SMTP password
//   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
   //$mail->SMTPAutoTLS = false; // Desabilita o uso de STARTTLS
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = "465";  
                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                          
   $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => true,
            'allow_self_signed' => true
        )
       
    );
  */
    //Recipients
    $mail->setFrom('nf1@remoservice.com.br', 'TESTE PRISMA'); //quem esta enviando titulo
    $mail->addAddress('contato@sistemaprisma.com.br', 'Robson User');     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
   // $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');

    //Attachments
  //  $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'ASSUNTO NF TESTE CONFIG. PRISMA'; // assunto !!!
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
    exit();

