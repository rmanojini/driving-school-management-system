<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Alex Driving School</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    
</head>
<body>
    <div class="login-container">
        <h2>Login Portal</h2>
        <p class="subtitle"><i class="fas fa-car"></i> Welcome to Alex Driving School</p>

        <form action="" method="POST" class="login-form">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Please Enter Your Username" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Please Enter Your Password" autocomplete="new-password">
            </div>

            <button type="submit" name="submit" class="login-btn" value="Login">
                Enter
            </button>
        </form>

        <?php
        if(isset($_POST['submit'])){
            include 'includes/connection.php';

            $un=$_POST['username'];
            $pw=$_POST['password'];

            $sql="select * from admin where username='$un' and password='$pw'";

            $res=mysqli_query($con,$sql);

            if (mysqli_num_rows($res)==0) {

                echo "<p class='alert alert-danger text-center mt-3'><b>Sorry, user name or pass word wrong....Try again</b></p>";

            }
            else{
                $row = mysqli_fetch_assoc($res);
                // Try to get a name, otherwise fallback to username
                $admin_name = isset($row['name']) ? $row['name'] : $un;
                $_SESSION['admin_name'] = $admin_name;
                $_SESSION['xusername'] = $un;

                echo "<script>window.location.href='dashboard.php';</script>";

            }
        }

     ?>

        <div class="links-section">
         </div>

    </div>
</body>
</html>