<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

// 🔍 Enable debug (VERY IMPORTANT)
$mail->SMTPDebug = 2; 
$mail->Debugoutput = 'html';

try {
    $mail->isSMTP();

    // Force IPv4 (Windows fix)
    $mail->Host = gethostbyname('smtp.gmail.com');

    $mail->SMTPAuth = true;
    $mail->Username = 'rajaretnammanojini69@gmail.com';
    $mail->Password = 'wbdajalwbjnxccen'; // 16-char APP PASSWORD

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // SSL fix for Windows
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->setFrom('rajaretnammanojini69@gmail.com', 'XAMPP Test');
    $mail->addAddress('rmanojini19@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'SMTP Debug Test';
    $mail->Body = 'If you see this, PHPMailer is working ✅';

    $mail->send();
    echo "<br><b>✅ Email sent successfully</b>";

} catch (Exception $e) {
    echo "<br><b>❌ Mailer Error:</b> " . $mail->ErrorInfo;
}
