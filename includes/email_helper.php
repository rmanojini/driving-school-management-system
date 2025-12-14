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

function send_result_notification($student_email, $student_name, $exam_type, $marks, $status) {
    $to = $student_email;
    $subject = "Exam Result Notification - Alex Driving School";
    
    // Determine color based on status
    $color = ($status == 'pass') ? '#2ecc71' : (($status == 'fail') ? '#e74c3c' : '#f39c12');
    $status_text = strtoupper($status);

    $message = "
    <html>
    <head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .result-box { background-color: #f9f9f9; padding: 20px; border-radius: 5px; text-align: center; }
        .status { font-size: 24px; font-weight: bold; color: $color; margin-top: 10px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
    </head>
    <body>
    <div class='container'>
        <div class='header'>
            <h2>Exam Results Published</h2>
        </div>
        <p>Dear <strong>$student_name</strong>,</p>
        <p>Your result for the driving exam has been released. Here are the details:</p>
        
        <div class='result-box'>
            <h3>" . ucfirst($exam_type) . " Exam</h3>
            <p>Marks Obtained: <strong>$marks / 40</strong></p>
            <div class='status'>$status_text</div>
        </div>

        <p>Please log in to your student portal for more details or contact the administration office.</p>
        
        <div class='footer'>
            &copy; " . date("Y") . " Alex Driving School. All rights reserved.
        </div>
    </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Alex Driving School <admin@alexdrivingschool.com>' . "\r\n";
    
    @mail($to, $subject, $message, $headers);
}
?>
