<?php
session_start();
require_once('api/config/config.inc.php');
require 'api/vendor/autoload.php';
//include("../../api/config/iconexao.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



use Database\MySQL;
$pdo = MySQL::acessabd();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$_empresa = $_parametros["empresa"];
$_nome = $_parametros["nome"];
$_emailcontato = $_parametros["email"];
$_wpp = $_parametros["wpp"];
$_cit = $_parametros["cit"];
$_cnpj = $_parametros["cnpj"];
$_empresa = $_parametros["empresa"];

			$mail = new PHPMailer(true);
			try {
				//Server settings
				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'smtp.titan.email';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = 'contato@sistemaprisma.com.br';                     //SMTP username
				$mail->Password   = 'ttitts01';                               //SMTP password
			$mail->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
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
				$mail->setFrom('contato@sistemaprisma.com.br', '[Prisma] - CONTATO SITE'); //quem esta enviando titulo
				$mail->addAddress('contato@sistemaprisma.com.br', 'Site');     //Add a recipient

				//Content
				$mail->isHTML(true);                                  //Set email format to HTML
				$mail->Subject = ('PRISMA - CONTATO SITE'); // assunto !!!
				$mail->Body    = ('<b>'.$_empresa.'</b><br><b>'.$_nome.'</b><br><b>'.$_emailcontato.'</b><br><b>'.$_wpp.'</b><br>'.$_cit.'</b><b>'.$_cnpj.'</b>');
				$mail->AltBody = ('<b>'.$_empresa.'</b><b>'.$_nome.'</b><b>'.$_emailcontato.'</b><b>'.$_wpp.'</b>'.$_cit.'</b>');

				$mail->send();
				echo '  <img src="images/email-enviado.png" alt="image" class="img-responsive center-block" width="200"/>
				<strong>Mensagem Enviada com Sucesso !!! </strong>   ';

			} catch (Exception $e) {
				echo "Ops !!! não conseguimos enviar email, entre em contato via whatZapp";
			}
				exit();
