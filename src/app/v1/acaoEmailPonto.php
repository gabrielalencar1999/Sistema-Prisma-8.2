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

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$msgregistroponto = $_POST["msgregistroponto"];

	$sql = "Select NOME_FANTASIA from " . $_SESSION['BASE'] . ".parametro LIMIT 1 ";	
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$parametro = $linha->NOME_FANTASIA;
		}
	}

	$id = $_SESSION["tecnico"];


	$mes = date('m');

	$ano  = date('Y');

	$sql = "Select usuario_LOGIN,usuario_NOME,usuario_email from " . $_SESSION['BASE'] . ".usuario where usuario_CODIGOUSUARIO = '$id'";
	$stm = $pdo->prepare("$sql");
	$stm->execute();
	if ($stm->rowCount() > 0) {
		while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
		{
			$nomecompleto = $linha->usuario_NOME;
			$_login = $linha->usuario_LOGIN;
			$_email = $linha->usuario_email;
		}
	}
			//Create an instance; passing `true` enables exceptions
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
				$mail->setFrom('contato@sistemaprisma.com.br', '[Prisma] Registro Ponto'); //quem esta enviando titulo
				$mail->addAddress($_email, $_login);     //Add a recipient

				//Content
				$mail->isHTML(true);                                  //Set email format to HTML
				$mail->Subject = utf8_decode('não responder - '.$parametro.' -  REGISTRO PONTO'); // assunto !!!
				$mail->Body    = utf8_decode('<b>'.$nomecompleto.'</b> - Registro Ponto Efetuado <br> '.$parametro."<Br>$msgregistroponto");
				$mail->AltBody = utf8_decode('<b>'.$nomecompleto.'</b> - Registro Ponto Efetuado <br> '.$parametro."<Br>$msgregistroponto");

				$mail->send();
				
			} catch (Exception $e) {
				//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
				exit();
