<?php
function send_admin_notification($student_name, $student_email) {
    // Basic email configuration
    // IMPORTANT: For this to work on XAMPP/Localhost, you need to configure sendmail in php.ini
    // or use PHPMailer with an SMTP server (Gmail, Outlook, etc.)
    
    $to = "admin@alexdrivingschool.com"; // Replace with your admin email
    $subject = "New Student Registration: " . $student_name;
    
    $message = "
    <html>
    <head>
    <title>New Registration</title>
    </head>
    <body>
    <p>A new student has registered and is pending approval.</p>
    <table>
    <tr>
    <th>Name</th>
    <td>$student_name</td>
    </tr>
    <tr>
    <th>Email</th>
    <td>$student_email</td>
    </tr>
    </table>
    <p><a href='http://localhost/drivingSchool/admin/pending_approvals.php'>Click here to view pending approvals</a></p>
    </body>
    </html>
    ";
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // More headers
    $headers .= 'From: <system@alexdrivingschool.com>' . "\r\n";
    
    // Attempt to send
    // Suppressing errors (@) because on unconfigured localhost this will vomit errors
    @mail($to, $subject, $message, $headers);
}
?>
