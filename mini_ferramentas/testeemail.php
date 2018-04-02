<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
include_once 'conecta.php';
include 'PHPMailer.php';
include 'Exception.php';
include 'SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//require 'vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
$mail->CharSet = 'UTF-8';
try {
    //Server settings
    $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
    $mail->CharSet = 'UTF-8';
	//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = EMAILFROM;//'monitoramentoceratfo@gmail.com';                 // SMTP username
    $mail->Password = EMAILSENHA;//'Fabino13#';                           // SMTP password
    //$mail->SMTPSecure = 'tsl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom(EMAILFROM, 'Email Automático - Verificação de Sincronia');
    $mail->addAddress(EMAILFOR, 'Equipe TI');     // Add a recipient
   /* $mail->addAddress('');               // Name is optional
    $mail->addReplyTo('', 'Information');
    $mail->addCC('');
    $mail->addBCC('');*/

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $html = "<!DOCTYPE html>            
            <html style='box-sizing: inherit;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;overflow-x: hidden;font-family: Verdana,sans-serif;font-size: 15px;line-height: 1.5;'>
              <head style='box-sizing: inherit;'>
                <meta charset='UTF-8' style='box-sizing: inherit;'>                     
               
              </head>
              <body style='box-sizing: inherit;margin: 0;font-family: Verdana,sans-serif;font-size: 15px;line-height: 1.5;'>
                <div style='box-sizing: inherit;letter-spacing: 4px;padding: 8px 16px!important;box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2),0 4px 20px 0 rgba(0,0,0,0.19);font-size: 10px!important;text-align: center!important;margin: 16px!important;color: #fff!important;background-color: #3f51b5!important;'>
            		<b style='box-sizing: inherit;'>RADAR CARTÕES - Painel de verificação de Sincronia BD</b>
            	</div>
                <div style='box-sizing: inherit;font-size: 10px!important;text-align: center!important;margin-bottom: 16px!important;margin-left: 16px!important;margin-right: 16px!important;'>
            		<b style='box-sizing: inherit;'>Banco de Dados - DB_ATF</b>
            		<br style='box-sizing: inherit;'><br style='box-sizing: inherit;'><b style='box-sizing: inherit;'>Data:</b> 02/04/2018
            	    <br style='box-sizing: inherit;'><br style='box-sizing: inherit;'><b style='box-sizing: inherit;'>Obs:</b> Tabelas com tempo de sincronia superior ao parâmetro previsto em contrato.
            	</div>
                    
            	<div  style='padding-bottom: 16px !important;box-sizing: inherit;box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2),0 4px 20px 0 rgba(0,0,0,0.19);font-size: 10px!important;text-align: center!important;border: 1px solid #ccc!important;margin-bottom: 16px!important;margin-left: 16px!important;margin-right: 16px!important;padding: 8px 16px!important;'>
                <table style='box-sizing: inherit;border-collapse: collapse;border-spacing: 0;width: 100%;display: table;font-size: 10px!important;'>
                  	<thead style='box-sizing: inherit;'>
                        <tr  style='box-sizing: inherit;color: #fff!important;background-color: #3f51b5!important;'>
                        	<td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;padding-left: 16px;'><b style='box-sizing: inherit;'>Grupo de Sincronia</b></td>
                            <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'><b style='box-sizing: inherit;'>ID</b></td>
                            <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'><b style='box-sizing: inherit;'>Tabela</b></td>
                            <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'><b style='box-sizing: inherit;'>Dt. Verificação</b></td>
                            <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'><b style='box-sizing: inherit;'>Dt. Ult. Sincronia</b></td>
                            <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'><b style='box-sizing: inherit;'>Dif. Minutos</b></td>
                            <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'><b style='box-sizing: inherit;'>Ult. CallID</b></td>
                       </tr>
                    </thead>
                    <tbody style='box-sizing: inherit;'>
                      <tr style='box-sizing: inherit;'>
						  <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;padding-left: 16px;'>2018-01-04 00:04:39</td>
						  <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'>5</td>
						  <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'>tb_log_categorizacao</td>
						  <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'>2018-01-04 00:07:47</td>
						  <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'>2018-02-28 23:59:32</td>
						  <td bgcolor='#f2c4c4' style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'>85</td>
						  <td style='box-sizing: inherit;padding: 8px 8px;display: table-cell;text-align: left;vertical-align: top;'>KAj:73oQeh1ha4H6</td>
					  </tr>
					</tbody>
                </table>
              </div>
              </body>
            </html>";
    
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Sincronia do Banco de Dados BD_ATF';
    $mail->Body    = $html;//'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Mensagem enviada';
} catch (Exception $e) {
    echo 'Mensagem não pode ser enviada. Erro: ', $mail->ErrorInfo;
}