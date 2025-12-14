<?php
include '../includes/connection.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE registration SET status = 'approved' WHERE `index` = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    
    if(mysqli_stmt_execute($stmt)){
        echo "<script>alert('Student Approved Successfully!'); window.location.href='pending_approvals.php';</script>";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
    mysqli_stmt_close($stmt);
}
?>
