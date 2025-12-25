<?php
include '../includes/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if(isset($_GET['id'])){
    $app_id = $_GET['id'];
    
    // 1. Fetch from onlineapplication
    $fetch_sql = "SELECT * FROM onlineapplication WHERE app_id = ?";
    $stmt_fetch = mysqli_prepare($con, $fetch_sql);
    mysqli_stmt_bind_param($stmt_fetch, "i", $app_id);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    
    if($applicant = mysqli_fetch_assoc($result_fetch)){
        // Data found, prepare for TRANSFER
        $name = $applicant['name'];
        $dob = $applicant['dob'];
        $age = $applicant['age'];
        $nic = $applicant['nic'];
        $username = $applicant['username'] ?? $nic; // Use username from form, or default to NIC
        $gender = $applicant['gender'];
        $address = $applicant['address'];
        $phone_number = $applicant['phone_number'];
        $email = $applicant['email'];
        $password = $applicant['password'];
        $classofvehicle = $applicant['classofvehicle'];
        $reg_date = $applicant['reg_date'];
        $doc_nic = $applicant['doc_nic'];
        $doc_address = $applicant['doc_address'];
        $medical_number = $applicant['medical_number'];
        $medical_date = $applicant['medical_date'];
        $learner_permit_no = $applicant['learner_permit_no'];
        $status = 'approved';

        // VALIDATION: Strict validation removed as per user request (allow approval without medical details)
        // if(empty($medical_number)){ ... }
        
        // Check if NIC already exists in registration table
        $check_sql = "SELECT nic FROM registration WHERE nic = ?";
        $check_stmt = mysqli_prepare($con, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $nic);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if(mysqli_stmt_num_rows($check_stmt) > 0){
            // Student already approved
            echo "<script>alert('This student is already approved! NIC: $nic already exists in the system.'); window.location.href='pending_approvals.php';</script>";
            mysqli_stmt_close($check_stmt);
        } else {
            mysqli_stmt_close($check_stmt);
            
            // 2. Insert into registration (Main Table) - Added medical & learner permit info
            $insert_sql = "INSERT INTO registration (name, dob, age, nic, username, gender, address, phone_number, email, password, status, reg_date, classofvehicle, doc_nic, doc_address, medical_number, medical_date, learner_permit_no) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_insert = mysqli_prepare($con, $insert_sql);
            mysqli_stmt_bind_param($stmt_insert, "ssssssssssssssssss", $name, $dob, $age, $nic, $username, $gender, $address, $phone_number, $email, $password, $status, $reg_date, $classofvehicle, $doc_nic, $doc_address, $medical_number, $medical_date, $learner_permit_no);
            
            if(mysqli_stmt_execute($stmt_insert)){
                // 3. Delete from onlineapplication (As requested: remove from there)
                $delete_sql = "DELETE FROM onlineapplication WHERE app_id = ?";
                $stmt_delete = mysqli_prepare($con, $delete_sql);
                mysqli_stmt_bind_param($stmt_delete, "i", $app_id);
                mysqli_stmt_execute($stmt_delete);

                // --- SEND EMAIL NOTIFICATION ---
                $mail = new PHPMailer(true);
                try {
                    // SMTP configuration (from send_mail.php)
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

                    // Sender
                    $mail->setFrom(getenv('SMTP_USER'), 'Driving School Admin');
                    
                    // Receiver
                    $mail->addAddress($email, $name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Driving School Application Approved';
                    $mail->Body    = "
                        <h3>Congratulations, $name!</h3>
                        <p>Your application for the Driving School has been <b>APPROVED</b>.</p>
                        <p>You can now log in to the student portal using your credentials.</p>
                        <p><b>Username:</b> $username<br>
                        <b>Password:</b> (The one you registered with)</p>
                        <br>
                        <p>Regards,<br>Driving School Management</p>
                    ";

                    $mail->send();
                    $mail_msg = "Approval Email Sent Successfully!";
                } catch (Exception $e) {
                    $mail_msg = "However, Email sending failed: " . $mail->ErrorInfo;
                }
                // -------------------------------
                
                echo "<script>alert('Student Approved & Moved to Main Database Successfully! $mail_msg'); window.location.href='pending_approvals.php';</script>";
            } else {
                 echo "Error Inserting into Main DB: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt_insert);
        }
        
    } else {
        echo "Error: Application not found.";
    }
    mysqli_stmt_close($stmt_fetch);
}
?>
