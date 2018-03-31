<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
include 'PHPMailer.php';
include 'Exception.php';
include 'SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//require 'vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
	$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'monitoramentoceratfo@gmail.com';                 // SMTP username
    $mail->Password = 'Fabino13#';                           // SMTP password
    //$mail->SMTPSecure = 'tsl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('monitoramentoceratfo@gmail.com', 'Mailer');
    $mail->addAddress('monitoramentoceratfo@gmail.com', 'Joe User');     // Add a recipient
   /* $mail->addAddress('fabianolisboa@gmail.com');               // Name is optional
    $mail->addReplyTo('fabianolisboa@gmail.com', 'Information');
    $mail->addCC('fabianolisboa@gmail.com');
    $mail->addBCC('fabianolisboa@gmail.com');*/

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}