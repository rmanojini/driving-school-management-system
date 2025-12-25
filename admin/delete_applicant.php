<?php
include '../includes/connection.php';

if(isset($_GET['id'])){
    $app_id = $_GET['id'];
    
    // Delete query
    $sql = "DELETE FROM onlineapplication WHERE app_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    
    if($stmt){
        mysqli_stmt_bind_param($stmt, "i", $app_id);
        
        if(mysqli_stmt_execute($stmt)){
            echo "<script>alert('Application Deleted Successfully'); window.location.href='pending_approvals.php';</script>";
        } else {
            echo "<script>alert('Error Deleting Application: " . mysqli_error($con) . "'); window.location.href='pending_approvals.php';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database Error'); window.location.href='pending_approvals.php';</script>";
    }
} else {
    header("Location: pending_approvals.php");
}
?>
