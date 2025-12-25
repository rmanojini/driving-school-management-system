<?php
session_start();
if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}
include 'includes/connection.php';

$email = $_SESSION['student_email'];
$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Verify current password
    $sql = "SELECT password FROM registration WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);

    // FIXED: Use password_verify since passwords are hashed
    if($row && password_verify($current, $row['password'])){
        if($new === $confirm){
            // FIXED: Hash the new password before storing
            $hashed_password = password_hash($new, PASSWORD_DEFAULT);
            
            $update_sql = "UPDATE registration SET password = ? WHERE email = ?";
            $upk_stmt = mysqli_prepare($con, $update_sql);
            mysqli_stmt_bind_param($upk_stmt, "ss", $hashed_password, $email);
            if(mysqli_stmt_execute($upk_stmt)){
                echo "<script>alert('Password updated successfully! Please login with your new password.'); window.location.href='includes/logout.php';</script>";
            } else {
                $msg = "Error updating password.";
            }
        } else {
            $msg = "New passwords do not match.";
        }
    } else {
        $msg = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Driving School</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f4f7f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        
        .card { background: white; border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .card h2 { text-align: center; color: #333; margin-bottom: 20px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #666; font-size: 14px; margin-bottom: 8px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: 0.3s; }
        .form-group input:focus { border-color: #667eea; outline: none; }
        
        .btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn:hover { opacity: 0.9; }
        
        .cancel-link { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; font-size: 14px; }
        .error { color: #dc3545; text-align: center; margin-bottom: 15px; font-size: 14px; background: #ffe6e6; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Change Password</h2>
        
        <?php if($msg): ?>
            <div class="error"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Update Password</button>
            <a href="student_dashboard.php" class="cancel-link">Cancel</a>
        </form>
    </div>

</body>
</html>
