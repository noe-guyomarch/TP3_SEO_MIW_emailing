<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    // $mail->SMTPDebug    = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host         = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth     = true;                                   // Enable SMTP authentication
    $mail->Username     = '';                     // SMTP username
    $mail->Password     = '';                               // SMTP password
    $mail->SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port         = 465;                                    // TCP port to connect to
    $mail->CharSet      = "UTF-8";                                    // charset


    //Recipients
    $mail->setFrom('noguyomarch@gmail.com', 'noe');
    $mail->addAddress($_POST["email"], '');     // Add a recipient
    // $mail->addCC('newsletter@miw.ovh');

    // Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'test, dis moi si tu reçois';
    $mail->Body = file_get_contents('email/email_party.html');
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    //echo 'Message has been sent';
    header('Location: index.php?delivery=sent');
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
