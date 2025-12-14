<?php
include '../includes/connection.php';

if(isset($_GET['type']) && isset($_GET['id'])){
    $type = $_GET['type'];
    $id = $_GET['id'];

    if($type == 'schedule'){
        $sql = "UPDATE schedules SET status = 'completed' WHERE schedule_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            header("Location: schedule.php");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>
