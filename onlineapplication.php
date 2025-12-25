<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register - Bootstrap Version</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="assets/css/regiapp.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
    /* Inline styles from original if any, or rely on regiapp.css */
</style>
</head>
<script>
    function calculateAge() {
        const dobInput = document.getElementById('dob').value;
        const ageInput = document.getElementById('age');

        if (dobInput) {
            const birthDate = new Date(dobInput);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();
            
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
           if (age >= 18) {
                ageInput.value = age;
            } else {
                ageInput.value = age;
                alert("Sorry, you must be 18 years or older to register.");
                // Optionally clear DOB or Age to force correction
                // document.getElementById('dob').value = '';
                // ageInput.value = '';
            }
        } else {
            ageInput.value = '';
        }
    }
    
    // Toggle Password Visibility
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>
<body>
<div class="container my-5">
    <div class="row shadow bg-white rounded-4 overflow-hidden">

        <div class="col-lg-4 left-panel">
            <img src="assets/images/1.png" class="img-fluid rounded mb-3" />

            <div class="step-box">
                <div class="step-number">1</div>
                <p class="m-0 small">Register with us, enter your details and click on **Register Button**</p>
            </div>

            <img src="assets/images/boy.jpg" class="img-fluid rounded mb-3" />

            <div class="step-box">
                <div class="step-number bg-danger">2</div>
                <p class="m-0 small">Select **7 day trial** or Pay for **Enterprise Package**</p>
            </div>
        </div>

        <div class="col-lg-8 p-5">
            <h2 class="fw-bold text-navy mb-4">📝 New User Registration</h2> 

            <form action="onlineapplication.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                   
                    <div class="col-12">
                        <label for="student_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="student_name" name="name" placeholder="Enter full name" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required onchange="calculateAge()">
                    </div>

                    <div class="col-md-6">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" placeholder="Enter age" onchange="calculateAge()" readonly required>
                    </div>

                    <div class="col-12">
                         <label for="nic" class="form-label">National ID Card (NIC)</label>
                        <input type="text" class="form-control" id="nic" name="nic" placeholder="Enter NIC number" required>
                    </div>

                    
                    
                    <!-- Added Password Field for Login -->
                    <!-- Added Password Field for Login with Toggle -->
                   
                    <div class="col-12">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" required>
                            <label class="form-check-label" for="genderMale">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" required>
                            <label class="form-check-label" for="genderFemale">Female</label>
                        </div>
                    </div>

                    <div class="col-12">
                         <label for="address" class="form-label">Full Residential Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter full address" required></textarea>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="phone1" class="form-label">Mobile Number (10 Digits)</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" pattern="[0-9]{10}" placeholder="07XXXXXXXX" required>
                    </div>

                    <div class="col-md-6">
                         <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>

                    <div class="col-md-6">
                         <label for="username" class="form-label">Username (For Login)</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Create a unique username" required>
                    </div>


                    <div class="col-md-6">
                        <label for="password" class="form-label">Create Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" minlength="4" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Registered Date removed (handled by backend) -->

                    <div class="col-md-6">
                        <label for="vehicle_class" class="form-label">Class Of Vehicle</label>
                        <select class="form-select" id="vehicle_class" name="classofvehicle" required>
                            <option value="" disabled selected>Select a vehicle class</option>
                            <option value="A">A - Motor Cycles (Motorbikes)</option>
                            <option value="B">B - Motor Cars / Van</option>
                            <option value="B1">B1 - Motor Tricycles (Three Wheelers)</option>
                            <option value="G">G - Land Vehicles (Tractors / Agricultural)</option>
                            <option value="D">D - Heavy Motor Vehicles (Lorry / Bus)</option>
                            <option value="AB1">AB1 - Combo: A (Motor Cycles) & B1 (Tricycles)</option>
                            <option value="AB1B">AB1B - Combo: A, B1, and B (Motor Cars)</option>
                            <option value="AB1BG">AB1BG - Combo: A, B1, B, and G</option>
                            <option value="AB">AB - Combo: A (Motor Cycles) & B (Motor Cars)</option>
                        </select>
                    </div>
                    
                    <!-- Document Uploads Removed as per request -->
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-2">
                        <a href="index.html" class="btn btn-solid-navy w-100 py-2">Cancel</a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button type="submit" class="btn btn-solid-navy w-100 py-2" name="apply">Register</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>

<?php
    include 'includes/connection.php';
    include 'includes/email_helper.php';

    if(isset($_POST['apply'])){

    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $nic = $_POST['nic'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    
    // BACKEND Validation: Age must be 18+
    if($age < 18){
         echo "<script>alert('Registration Failed: You must be 18 years or older to register.'); window.location.href='index.html';</script>";
         exit();
    }
    
    // Option 1: User enters username manually, allow duplicate NIC but unique username
    $username = $_POST['username']; 
    
    $raw_password = $_POST['password']; // Capture password input
    if(empty($raw_password) && !empty($nic)){
         $raw_password = $nic; // Fallback to NIC if empty (though required in form)
    }
    
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
    $reg_date = date('Y-m-d'); // Auto-set date
    $classofvehicle = $_POST['classofvehicle'];
    $status = 'pending';

    // File Uploads removed - setting to NULL/Empty
    $doc_nic = NULL;
    $doc_address = NULL;

    // Check for Duplicate USERNAME only (allow same NIC with different username)
    $check_sql = "SELECT username FROM registration WHERE username = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if(mysqli_stmt_num_rows($check_stmt) > 0){
        echo "<script>alert('Error: This Username already exists! Please choose a different username.');</script>";
        mysqli_stmt_close($check_stmt);
    } else {
        mysqli_stmt_close($check_stmt);

        // SQL INSERT INTO onlineapplication.
        // Columns: name, dob, age, nic, username, gender, address, phone_number, email, password, reg_date, classofvehicle, doc_nic, doc_address (14 cols)
        // REMOVED 'status' column per user request
        $sql="INSERT INTO onlineapplication (name, dob, age, nic, username, gender, address, phone_number, email, password, reg_date, classofvehicle, doc_nic, doc_address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($con, $sql);
        if($stmt) {
            // Correct bind_param: 14 's' chars for 14 variables (Removed 1 's' for status)
            mysqli_stmt_bind_param($stmt, "ssssssssssssss", $name, $dob, $age, $nic, $username, $gender, $address, $phone_number, $email, $password, $reg_date, $classofvehicle, $doc_nic, $doc_address);
            
            if (mysqli_stmt_execute($stmt)) {
                 // Send Email
                 send_admin_notification($name, $email, $phone_number);
                 echo "<script>alert('Data Successfully saved! Please wait for approval. Use your NIC as Username for login.'); window.location.href='onlineapplication.php';</script>";
            } else {
                 echo "<script>alert('Data not saved: " . mysqli_error($con) . "');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Database Error: " . mysqli_error($con) . "');</script>";
        }
    }
    } // End Duplicate Check Else
 // End Isset
?>