<?php
session_start();
include 'includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Alex Driving School</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <style>
        .login-btn { width: 100%; }
        .back-link { display: block; margin-top: 15px; color: var(--glow-blue-secondary); text-decoration: none; font-size: 0.9em; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Portal</h2>
        <p class="subtitle">Access your learning journey</p>

        <?php
        if(isset($_POST['login'])){
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $password = $_POST['password'];

            // Using prepared statement for security
            $stmt = mysqli_prepare($con, "SELECT * FROM registration WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if($row = mysqli_fetch_assoc($result)){
                // Verify password
                if(password_verify($password, $row['password'])){
                    // Check status
                    if($row['status'] == 'approved'){
                        $_SESSION['student_email'] = $row['email'];
                        $_SESSION['student_name'] = $row['name'];
                        header("Location: student_dashboard.php");
                        exit();
                    } elseif($row['status'] == 'pending'){
                        echo "<p class='alert alert-warning' style='color: orange; font-weight: bold;'>Your account is pending approval by the admin.</p>";
                    } else {
                        echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Your account has been rejected. Please contact support.</p>";
                    }
                } else {
                    echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Invalid Password.</p>";
                }
            } else {
                echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Email not found.</p>";
            }
            mysqli_stmt_close($stmt);
        }
        ?>

        <form action="" method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="text" id="email" name="email" required placeholder="Enter your registered email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" name="login" class="login-btn">Login</button>
            <a href="auth_choice.html" class="back-link">Return to Selection</a>
        </form>
    </div>
</body>
</html>
