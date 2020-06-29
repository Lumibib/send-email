<?php
header('Access-Control-Allow-Origin: *');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function receive(){
	$to = Flight::request()->data['to'];
	$from = Flight::request()->data['from'];
	$from_name = Flight::request()->data['from_name'];
	$cc = Flight::request()->data['cc'];
	$bcc = Flight::request()->data['bcc'];
	$template = Flight::request()->data['template'];
	$data = Flight::request()->data['data'];
	$subject = Flight::request()->data['subject'];
	$message = Flight::request()->data['message'];
	$template = Flight::request()->data['template'];
	$remote_template = Flight::request()->data['remote_template'];
	$lang = Flight::request()->data['lang'];
	$data = Flight::request()->data['data'];
	$transport = Flight::request()->data['transport'] ? Flight::request()->data['transport'] : 'mailer';

	$mail = new PHPMailer(true);
	try {
		/* Server settings */
		if ($transport == 'smtp') {
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->SMTPDebug = 2;
			$mail->isSMTP();
			$mail->Host       = $_ENV['SMTP_HOST'];
			$mail->SMTPAuth   = true;
			$mail->Username   = $_ENV['SMTP_USERNAME'];
			$mail->Password   = $_ENV['SMTP_PASSWORD'];
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port       = $_ENV['SMTP_PASSWORD'];
		}
		
		/* Recipients */
		$mail->setFrom($from, $from_name);
		$mail->addReplyTo($from, $from_name);
		$mail->addAddress($to);
		if (isset($cc)) {$mail->addCC($cc);}
		if (isset($bcc)) {$mail->addBCC($bcc);}

		/* Attachements */
		/*
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		*/

		/* Data */
		if (isset($data)) {
			/* http://lisalu.com.au/javascript-json-stringify-and-php-json_encode-json_decode */
			$templateData = json_decode(stripslashes($data), true);
		} else {
			$templateData = [];
		}
		
		/* Template, Message */
		if (isset($template)) {
			$locale = isset($lang) ? '-'.$lang : 'en';
			$loader = new \Twig\Loader\FilesystemLoader('templates/');
			$templateFile = $template.$locale.'.html';
			$templateFileTxt = $template.$locale.'.txt';
			$twig = new \Twig\Environment($loader);
			$outputMessage = $twig->render($templateFile, $templateData);
			$outputMessageTxt = $twig->render($templateFileTxt, $templateData);
		} else if (isset($remote_template)) {
			$templateFileRemote = mb_convert_encoding(file_get_contents($remote_template), "HTML-ENTITIES", "UTF-8" );
			$templateFile = 'template.html';
			$loader = new \Twig\Loader\ArrayLoader([
				$templateFile => $templateFileRemote,
			]);
			$twig = new \Twig\Environment($loader);
			$outputMessage = $twig->render($templateFile, $templateData);
		} else if (isset($message)) {
			$templateString = $message;
			$outputMessage = $templateString;
		}

		/* Content */
		$mail->CharSet = 'UTF-8';
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $outputMessage;
		if (isset($outputMessageTxt)) {
			$mail->AltBody = $outputMessageTxt;
		}

		$mail->send();

		echo Flight::json(array('sent' => true, 'error' => false, 'to' => $to, 'transport' => $transport));

	} catch (Exception $e) {
		echo Flight::json(array('sent' => false, 'error' => true, 'message' => $mail->ErrorInfo, 'transport' => $transport, 'request_data' => Flight::request() ));
	}
}

Flight::route('POST /', 'receive');

Flight::start();

?>