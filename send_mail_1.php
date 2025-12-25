<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'rajaretnammanojini69@gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rajaretnammanojini69@gmail.com';
    $mail->Password   = 'wbdajalwbjnxccen'; // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
    $mail->setFrom('rajaretnammanojini69@gmail.com', 'XAMPP Mail Test');

    // Receiver
    $mail->addAddress('rmanojini19@gmail.com');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Working';
    $mail->Body    = 'PHPMailer is working correctly in <b>XAMPP (Windows)</b>';

    $mail->send();
    echo "✅ Email sent successfully";
} catch (Exception $e) {
    echo "❌ Mailer Error: {$mail->ErrorInfo}";
}
