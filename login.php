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

        <form action="/handle-login" method="POST" class="login-form">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Please Enter Your Username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Please Enter Your Password">
            </div>

            <button type="submit" name="submit" class="login-btn" value="Login">
                Enter
            </button>
        </form>

        <?php
        if(isset($_POST['submit'])){
            include 'includes/connection.php';

            $un=$_POST['un'];
            $pw=$_POST['pw'];

            $sql="select * from admin where username='$un' and password='$pw'";

            $res=mysqli_query($con,$sql);

            if (mysqli_num_rows($res)==0) {

                echo "<p class='alert alert-danger text-center mt-3'><b>Sorry, user name or pass word wrong....Try again</b></p>";

            }
            else{

                $_SESSION['xusername']=$un;

                echo "<script>window.location.href='dashboard.html';</script>";

            }
        }

     ?>

        <div class="links-section">
         </div>

    </div>
</body>
</html>