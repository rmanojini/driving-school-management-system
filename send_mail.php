<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include 'includes/env_loader.php';
loadEnv(__DIR__ . '/.env');

$mail = new PHPMailer(true);

// 🔍 Enable debug (VERY IMPORTANT)
$mail->SMTPDebug = 2; 
$mail->Debugoutput = 'html';

try {
    $mail->isSMTP();

    // Force IPv4 (Windows fix)
    $mail->Host = gethostbyname(getenv('SMTP_HOST'));

    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER');
    $mail->Password = getenv('SMTP_PASS'); // 16-char APP PASSWORD

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = getenv('SMTP_PORT');

    // SSL fix for Windows
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->setFrom(getenv('SMTP_USER'), 'XAMPP Test');
    $mail->addAddress('rmanojini19@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'SMTP Debug Test';
    $mail->Body = 'If you see this, PHPMailer is working ✅';

    $mail->send();
    echo "<br><b>✅ Email sent successfully</b>";

} catch (Exception $e) {
    echo "<br><b>❌ Mailer Error:</b> " . $mail->ErrorInfo;
}
