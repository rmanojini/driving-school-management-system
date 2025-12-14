<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register - Bootstrap Version</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="regiapp.css">
</head>

<script>
    function calculateAge() {
        // 1. உள்ளீட்டுக் களங்களின் மதிப்புகளைப் பெறவும்
        const dobInput = document.getElementById('dob').value;
        const ageInput = document.getElementById('age');

        if (dobInput) {
            // உள்ளிடப்பட்ட DOB-ஐ Date பொருளாக மாற்றுகிறது
            const birthDate = new Date(dobInput);
            
            // இன்றைய தேதியைப் பெறுகிறது
            const today = new Date();
            
            // 2. வயதைக் கணக்கிட ஆரம்பிக்கவும் (ஆண்டுகள் அடிப்படையில்)
            let age = today.getFullYear() - birthDate.getFullYear();
            
            // 3. துல்லியமான சரிசெய்தல் (மாதம் மற்றும் நாள் அடிப்படையில்)
            const monthDifference = today.getMonth() - birthDate.getMonth();
            
            // பிறந்தநாள் இன்னும் வரவில்லை எனில் (மாதம் அல்லது நாள் அடிப்படையில்), வயதில் இருந்து 1-ஐக் கழிக்கிறது
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            // 4. கணக்கிடப்பட்ட வயதை 'age' உள்ளீட்டுக் களத்தில் நிரப்புகிறது
            if (age >= 0) {
                ageInput.value = age;
            } else {
                // எதிர்காலத் தேதியை உள்ளீடு செய்தால் (பிழைச் சரிபார்ப்பு)
                ageInput.value = ''; 
                alert("பிறந்த தேதி எதிர்காலத் தேதியாக இருக்கக் கூடாது.");
            }
            
        } else {
            ageInput.value = ''; // DOB காலியாக இருந்தால் வயதையும் நீக்கவும்
        }
    }
    
</script>

<body>
<div class="container my-5">
    <div class="row shadow bg-white rounded-4 overflow-hidden">

        <div class="col-lg-4 left-panel">
            <img src="1.png" class="img-fluid rounded mb-3" />

            <div class="step-box">
                <div class="step-number">1</div>
                <p class="m-0 small">Register with us, enter your details and click on **Register Button**</p>
            </div>

            <img src="boy.jpg" class="img-fluid rounded mb-3" />

            <div class="step-box">
                <div class="step-number bg-danger">2</div>
                <p class="m-0 small">Select **7 day trial** or Pay for **Enterprise Package**</p>
            </div>
        </div>

        <div class="col-lg-8 p-5">
            <h2 class="fw-bold text-navy mb-4">📝 New User Registration</h2> 

            <form action="registration.php" method="POST" >
                <div class="row g-3">
                   
                    <div class="col-12">
                        <label for="index_no" class="form-label">Index Number</label>
                        <input type="text" class="form-control" id="index_no" name="index" placeholder="Enter Index No." required>
                    </div>

                    <div class="col-12">
                        <label for="student_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="student_name" name="name" placeholder="Enter full name" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" onchange="calculateAge()" required >
                    </div>

                    <div class="col-md-6">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" placeholder="Enter age" onchange="calculateAge()" readonly required>
                    </div>

                    <div class="col-12">
                         <label for="nic" class="form-label">National ID Card (NIC)</label>
                        <input type="text" class="form-control" id="nic" name="nic" placeholder="Enter NIC number" required>
                    </div>

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
                        <input type="tel" class="form-control" id="phone1" name="phone_number" pattern="[0-9]{10}" placeholder="07XXXXXXXX" required>
                    </div>

                    <div class="col-md-6">
                         <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>
                    
                    <div class="col-md-6">
                         <label for="reg_date" class="form-label">Registered Date</label>
                        <input type="date" class="form-control" id="reg_date" name="reg_date" value="2025-12-08" required>
                    </div>

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
                            <option value="AB1BG">AB1BG - Combo: A, B1, B, and G (All listed Light/Medium Classes)</option>
                            <option value="AB">AB - Combo: A (Motor Cycles) & B (Motor Cars)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="medical_number" class="form-label">Medical Certificate Number</label>
                        <input type="text" class="form-control" id="medical_number" name="medical_number" placeholder="Enter Certificate No." required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="medical_date" class="form-label">Medical Date</label>
                        <input type="date" class="form-control" id="medical_date" name="medical_date" required>
                    </div>

                    <div class="col-12">
                        <label for="photo_upload" class="form-label">Passport Size Photo Upload</label>
                        <input type="file" class="form-control form-control-file" id="photo_upload" name="photo" accept="image/*" required>
                    </div>


                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-2">
                        <button type="button" class="btn btn-solid-navy w-100 py-2">Cancel</button>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button type="submit" name="registration" class="btn btn-solid-navy w-100 py-2">Register</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php

    include 'connection.php';

    if(!$con)
    {
        echo "Server Not Connected......Check it";
        exec();
    }

    if(isset($_POST['registration'])){
    
    $index=$_POST['index'];
    $name=$_POST['name'];
    $dob=$_POST['dob'];
    $age=$_POST['age'];
    $nic=$_POST['nic'];
    $gender=$_POST['gender'];
     $address=$_POST['address'];
    $phone_number=$_POST['phone_number'];
    $email=$_POST['email'];
    $reg_date=$_POST['reg_date'];
    $classofvehicle=$_POST['classofvehicle'];
    $medical_number=$_POST['medical_number'];
    $medical_date=$_POST['medical_date'];
    $photo=$_POST['photo'];



    $sql="insert into registration(`index`,name,dob,age,nic,gender,address,phone_number,email,reg_date,classofvehicle,medical_number,medical_date,photo)
values($index,'$name','$dob','$age','$nic','$gender','$address','$phone_number','$email','$reg_date','$classofvehicle','$medical_number','$medical_date','$photo')";

$res=mysqli_query($con,$sql);

    if ($res) {
        echo "<p class='alert alert-success mt-4'><b>Data Successfully saved......</b></p>";
        
    }

    else {
        echo "<p class='alert alert-danger mt-4'><b>Data not saved......</b></p>";
        
    }

    mysqli_close($con);
}

?>