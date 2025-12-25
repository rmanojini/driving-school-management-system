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
            include 'includes/rate_limiter.php'; // Include rate limiter

            $ip = $_SERVER['REMOTE_ADDR'];
            $limit = 5; // 5 attempts allowed
            $window = 900; // 15 minutes

            // 1. Check Rate Limit
            if(is_rate_limited($con, $ip, 'student_login', $limit, $window)){
                echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Too many login attempts. Please try again in 15 minutes.</p>";
            } else {
                $username = mysqli_real_escape_string($con, $_POST['username']);
                $password = $_POST['password'];

                $stmt = mysqli_prepare($con, "SELECT * FROM registration WHERE username = ?");
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                $login_success = false;

                if($row = mysqli_fetch_assoc($result)){
                    if(password_verify($password, $row['password'])){
                        if($row['status'] == 'approved'){
                            $login_success = true;
                            $_SESSION['student_email'] = $row['email'];
                            $_SESSION['student_username'] = $row['username'];
                            $_SESSION['student_name'] = $row['name'];
                            header("Location: student_dashboard.php");
                            exit();
                        } elseif($row['status'] == 'pending'){
                            echo "<p class='alert alert-warning' style='color: orange; font-weight: bold;'>Your account is pending approval by the admin.</p>";
                        } else {
                            echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Your account has been rejected. Please contact support.</p>";
                        }
                    } else {
                        // Password Incorrect
                         echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Invalid Password.</p>";
                    }
                } else {
                    // Username Not Found
                    echo "<p class='alert alert-danger' style='color: red; font-weight: bold;'>Username not found.</p>";
                }
                mysqli_stmt_close($stmt);

                // 2. Log failed attempt if not successful
                if(!$login_success){
                    log_rate_limit($con, $ip, 'student_login');
                }
            }
        }
        ?>

        <form action="" method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password" autocomplete="new-password">
            </div>

            <button type="submit" name="login" class="login-btn">Login</button>
            <a href="auth_choice.html" class="back-link">Return to Selection</a>
        </form>
    </div>
</body>
</html>
