<?php
include '../includes/connection.php';

if(!isset($_GET['id'])){
    echo "<script>alert('Invalid Request!'); window.location.href='student_details.php';</script>";
    exit();
}

$student_id = $_GET['id'];

// Fetch Student Details
$student_query = "SELECT * FROM registration WHERE `index` = '$student_id'";
$student_result = mysqli_query($con, $student_query);
$student = mysqli_fetch_assoc($student_result);

if(!$student){
    echo "<script>alert('Student Not Found!'); window.location.href='student_details.php';</script>";
    exit();
}

// Fetch Payments
$payment_query = "SELECT * FROM payments WHERE student_id = '$student_id' ORDER BY payment_date DESC";
$payments = mysqli_query($con, $payment_query);

// Fetch Results
$result_query = "SELECT * FROM results WHERE student_id = '$student_id' ORDER BY exam_date DESC";
$results = mysqli_query($con, $result_query);
// Handle Password Reset
if(isset($_POST['reset_password'])){
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $update_pass_sql = "UPDATE registration SET password = ? WHERE `index` = ?";
    $stmt_pass = mysqli_prepare($con, $update_pass_sql);
    mysqli_stmt_bind_param($stmt_pass, "ss", $hashed_password, $student_id);
    
    if(mysqli_stmt_execute($stmt_pass)){
        echo "<script>alert('Password Reset Successfully!'); window.location.href='view_student.php?id=$student_id';</script>";
    } else {
        echo "<script>alert('Error Updating Password: " . mysqli_error($con) . "');</script>";
    }
    mysqli_stmt_close($stmt_pass);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile - <?php echo $student['name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-header { background: linear-gradient(135deg, #0d6efd, #0dcaf0); color: white; padding: 30px 20px; border-radius: 15px 15px 0 0; }
        .profile-img { width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; object-fit: cover; }
        .section-title { border-left: 5px solid #0d6efd; padding-left: 10px; margin-bottom: 20px; font-weight: bold; color: #333; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between mb-3">
        <a href="student_details.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
        <a href="../welcome_splash.php" class="btn btn-outline-primary"><i class="fas fa-home"></i> Home</a>
    </div>

    <div class="card shadow border-0">
        <!-- Header -->
        <div class="profile-header text-center">
             <!-- Placeholder image if no photo uploaded -->
             <?php 
                $photoPath = !empty($student['photo']) ? "../uploads/" . $student['photo'] : "https://via.placeholder.com/150"; 
             ?>
            <img src="<?php echo $photoPath; ?>" alt="Student Photo" class="profile-img shadow">
            <h2 class="mt-3 fw-bold"><?php echo $student['name']; ?></h2>
            <p class="mb-0"><i class="fas fa-id-card"></i> <?php echo $student['nic']; ?> | <i class="fas fa-phone"></i> <?php echo $student['phone_number']; ?></p>
            <p class="mb-0"><i class="fas fa-id-card"></i> <?php echo $student['nic']; ?> | <i class="fas fa-phone"></i> <?php echo $student['phone_number']; ?></p>
            <span class="badge bg-light text-primary mt-2 fs-6 mb-2"><?php echo strtoupper($student['status']); ?></span>
            <br>
            <button type="button" class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                <i class="fas fa-key"></i> Reset Password
            </button>
        </div>

        <!-- Password Reset Modal -->
        <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reset Password for <?php echo $student['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <p class="text-danger small">Warning: This will overwrite the student's current password immediately.</p>
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="text" name="new_password" class="form-control" placeholder="Enter new password" required minlength="4">
                                <button class="btn btn-outline-secondary" type="button" onclick="generatePassword()">Generate</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="reset_password" class="btn btn-warning">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function generatePassword() {
                const chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                const length = 8;
                let password = "";
                for (let i = 0; i < length; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.querySelector('input[name="new_password"]').value = password;
            }
        </script>

        <div class="card-body p-4">
            
            <!-- Personal Information -->
            <h4 class="section-title">Personal Information</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-4"><strong>Full Name:</strong> <br> <?php echo $student['name']; ?></div>
                <div class="col-md-4"><strong>Date of Birth:</strong> <br> <?php echo $student['dob']; ?></div>
                <div class="col-md-4"><strong>Gender:</strong> <br> <?php echo ucfirst($student['gender']); ?></div>
                <div class="col-md-6"><strong>Address:</strong> <br> <?php echo $student['address']; ?></div>
                <div class="col-md-6"><strong>Email:</strong> <br> <?php echo $student['email']; ?></div>
                <div class="col-md-4"><strong>Vehicle Class:</strong> <br> <span class="badge bg-info"><?php echo $student['classofvehicle']; ?></span></div>
                <div class="col-md-4"><strong>Registration Date:</strong> <br> <?php echo $student['reg_date']; ?></div>
            </div>

            <hr>

            <!-- Payment History -->
            <h4 class="section-title text-success">Payment History</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount (Rs.)</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($payments) > 0){
                            while($pay = mysqli_fetch_assoc($payments)){
                                echo "<tr>";
                                echo "<td>" . $pay['payment_date'] . "</td>";
                                echo "<td>" . ucwords(str_replace('_', ' ', $pay['payment_type'])) . "</td>";
                                echo "<td>" . number_format($pay['amount'], 2) . "</td>";
                                echo "<td>" . $pay['remarks'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'>No payment records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <!-- Exam Results -->
            <h4 class="section-title text-danger">Exam Results</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-danger">
                        <tr>
                            <th>Date</th>
                            <th>Exam Type</th>
                            <th>Marks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($results) > 0){
                            while($res = mysqli_fetch_assoc($results)){
                                $badge = $res['result_status'] == 'pass' ? 'bg-success' : 'bg-danger';
                                echo "<tr>";
                                echo "<td>" . $res['exam_date'] . "</td>";
                                echo "<td>" . ucfirst($res['exam_type']) . "</td>";
                                echo "<td>" . $res['marks'] . "</td>";
                                echo "<td><span class='badge $badge'>" . ucfirst($res['result_status']) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted'>No exam results found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
