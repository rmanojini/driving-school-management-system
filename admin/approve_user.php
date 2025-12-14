<?php
include '../includes/connection.php';

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
        $gender = $applicant['gender'];
        $address = $applicant['address'];
        $phone_number = $applicant['phone_number'];
        $email = $applicant['email'];
        $password = $applicant['password'];
        $classofvehicle = $applicant['classofvehicle'];
        $reg_date = $applicant['reg_date'];
        $doc_nic = $applicant['doc_nic'];
        $doc_address = $applicant['doc_address'];
        $status = 'approved';
        
        // 2. Insert into registration (Main Table)
        $insert_sql = "INSERT INTO registration (name, dob, age, nic, gender, address, phone_number, email, password, status, reg_date, classofvehicle, doc_nic, doc_address) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_insert = mysqli_prepare($con, $insert_sql);
        mysqli_stmt_bind_param($stmt_insert, "ssssssssssssss", $name, $dob, $age, $nic, $gender, $address, $phone_number, $email, $password, $status, $reg_date, $classofvehicle, $doc_nic, $doc_address);
        
        if(mysqli_stmt_execute($stmt_insert)){
            // 3. Delete from onlineapplication
            $delete_sql = "DELETE FROM onlineapplication WHERE app_id = ?";
            $stmt_delete = mysqli_prepare($con, $delete_sql);
            mysqli_stmt_bind_param($stmt_delete, "i", $app_id);
            mysqli_stmt_execute($stmt_delete);
            
            echo "<script>alert('Student Approved & Moved to Main Database Successfully!'); window.location.href='pending_approvals.php';</script>";
        } else {
             echo "Error Inserting into Main DB: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt_insert);
        
    } else {
        echo "Error: Application not found.";
    }
    mysqli_stmt_close($stmt_fetch);
}
?>
