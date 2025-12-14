<?php
// registration.php - Now functioning as Admin Approval/Completion Form
include 'includes/connection.php';
include 'includes/email_helper.php';

$student = null;
$search_nic = "";

// 1. NIC Search Logic
if(isset($_POST['search_nic_btn']) || isset($_GET['nic'])){
    $search_nic = isset($_GET['nic']) ? $_GET['nic'] : $_POST['search_nic'];
    $sql = "SELECT * FROM registration WHERE nic = ?";
    $stmt = mysqli_prepare($con, $sql);
    if($stmt){
        mysqli_stmt_bind_param($stmt, "s", $search_nic);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)){
            $student = $row;
        } else {
             echo "<script>alert('No student found with NIC: $search_nic');</script>";
        }
    }
}

// 2. Form Submission (Update/Approve)
if(isset($_POST['register_btn'])){
    // Get fields
    $index = $_POST['index']; // The ID of the record we are updating
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $nic = $_POST['nic'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $reg_date = $_POST['reg_date'];
    $classofvehicle = $_POST['classofvehicle'];
    
    // Admin Only Fields
    $medical_number = $_POST['medical_number'];
    $medical_date = $_POST['medical_date'];
    
    // Logic: If user exists, we UPDATE. If new (Admin typying manually?), we INSERT.
    // Given the workflow "Admin approves user", we assume UPDATE.
    
    if(!empty($index)) {
        // UPDATE existing student
        $status = 'approved';
        $sql = "UPDATE registration SET name=?, dob=?, age=?, nic=?, gender=?, address=?, phone_number=?, email=?, reg_date=?, classofvehicle=?, medical_number=?, medical_date=?, status='approved' WHERE `index`=?";
        
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssssi", $name, $dob, $age, $nic, $gender, $address, $phone_number, $email, $reg_date, $classofvehicle, $medical_number, $medical_date, $index);
        
        if(mysqli_stmt_execute($stmt)){
            echo "<script>alert('Student Approved & Updated Successfully!'); window.location.href='admin/pending_approvals.php';</script>";
        } else {
            echo "<script>alert('Error Updating: " . mysqli_error($con) . "');</script>";
        }
    } else {
    } else {
        // Fallback: Create NEW student (Walk-in Registration by Admin)
        $password = password_hash('1234', PASSWORD_DEFAULT); // Default password for offline users
        $status = 'approved';
        
        // Admin creates new student -> Status Approved immediately
        $sql = "INSERT INTO registration (name, dob, age, nic, gender, address, phone_number, email, password, status, reg_date, classofvehicle, medical_number, medical_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $name, $dob, $age, $nic, $gender, $address, $phone_number, $email, $password, $status, $reg_date, $classofvehicle, $medical_number, $medical_date);
        
        if(mysqli_stmt_execute($stmt)){
            echo "<script>alert('New Walk-in Student Registered & Approved Successfully!'); window.location.href='admin/student_details.php';</script>";
        } else {
            echo "<script>alert('Error Registering: " . mysqli_error($con) . "');</script>";
        }
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval - Alex Driving School</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/regiapp.css">
    
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
                ageInput.value = age >= 0 ? age : '';
            }
        }
    </script>
</head>
<body>
<div class="container my-5">
    
    <!-- SEARCH BOX -->
    <div class="card mb-4 shadow border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🔍 Admin Search (Find Student by NIC)</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="d-flex gap-2">
                <input type="text" name="search_nic" class="form-control" placeholder="Enter NIC Number" value="<?php echo $search_nic; ?>" required>
                <button type="submit" name="search_nic_btn" class="btn btn-dark">Search & Auto-fill</button>
            </form>
        </div>
    </div>

    <div class="row shadow bg-white rounded-4 overflow-hidden">
        <div class="col-lg-4 left-panel">
            <img src="assets/images/1.png" class="img-fluid rounded mb-3" />
            <div class="step-box">
                <div class="step-number">1</div>
                <p class="m-0 small">Verify student details.</p>
            </div>
            <img src="assets/images/boy.jpg" class="img-fluid rounded mb-3" />
            <div class="step-box">
                <div class="step-number bg-danger">2</div>
                <p class="m-0 small">Enter Medical Info & Approve.</p>
            </div>
        </div>

        <div class="col-lg-8 p-5">
            <h2 class="fw-bold text-navy mb-4">📝 Admin Completion & Approval</h2> 

            <form action="registration.php" method="POST" enctype="multipart/form-data">
                <!-- Hidden ID field -->
                <input type="hidden" name="index" value="<?php echo $student['index'] ?? ''; ?>">

                <div class="row g-3">
                   
                    <div class="col-12">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $student['name'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $student['dob'] ?? ''; ?>" required onchange="calculateAge()">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" value="<?php echo $student['age'] ?? ''; ?>" readonly required>
                    </div>

                    <div class="col-12">
                         <label class="form-label">National ID Card (NIC)</label>
                        <input type="text" class="form-control" name="nic" value="<?php echo $student['nic'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male" <?php if(($student['gender'] ?? '') == 'Male') echo 'checked'; ?> required>
                            <label class="form-check-label">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female" <?php if(($student['gender'] ?? '') == 'Female') echo 'checked'; ?> required>
                            <label class="form-check-label">Female</label>
                        </div>
                    </div>

                    <div class="col-12">
                         <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="3" required><?php echo $student['address'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Mobile Number</label>
                        <input type="tel" class="form-control" name="phone_number" value="<?php echo $student['phone_number'] ?? ''; ?>" pattern="[0-9]{10}" required>
                    </div>

                    <div class="col-md-6">
                         <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $student['email'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                         <label class="form-label">Registered Date</label>
                        <input type="date" class="form-control" name="reg_date" value="<?php echo $student['reg_date'] ?? date('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Class Of Vehicle</label>
                        <select class="form-select" name="classofvehicle" required>
                            <option value="<?php echo $student['classofvehicle'] ?? ''; ?>" selected><?php echo $student['classofvehicle'] ?? 'Select Class'; ?></option>
                            <option value="A">A - Motor Cycles</option>
                            <option value="B">B - Motor Cars / Van</option>
                            <option value="B1">B1 - Three Wheelers</option>
                             <option value="AB">AB - Combo</option>
                        </select>
                    </div>
                    
                    <div class="col-12"><hr class="my-4"></div>
                    <h5 class="text-primary mb-3">Admin Only Fields</h5>

                    <div class="col-12">
                        <label class="form-label">Medical Certificate Number</label>
                        <input type="text" class="form-control" name="medical_number" value="<?php echo $student['medical_number'] ?? ''; ?>" placeholder="Enter Medical No." required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Medical Certificate Date</label>
                        <input type="date" class="form-control" name="medical_date" value="<?php echo $student['medical_date'] ?? ''; ?>" required>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="p-3 bg-light border rounded">
                            <h6>Uploaded Documents (Preview)</h6>
                            <?php if(!empty($student['doc_nic'])): ?>
                                <a href="<?php echo $student['doc_nic']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">View NIC</a>
                            <?php else: ?>
                                <span class="text-muted small">No NIC Uploaded</span>
                            <?php endif; ?>
                            
                            <?php if(!empty($student['doc_address'])): ?>
                                <a href="<?php echo $student['doc_address']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">View Address Proof</a>
                            <?php else: ?>
                                <span class="text-muted small ms-2">No Address Proof</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-2">
                        <a href="admin/pending_approvals.php" class="btn btn-solid-navy w-100 py-2">Cancel</a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button type="submit" class="btn btn-success w-100 py-2" name="register_btn">Approve & Save</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>