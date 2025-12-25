<?php
include '../includes/connection.php';

// Check ID
if (!isset($_GET['id'])) {
    header("Location: pending_approvals.php");
    exit();
}

$app_id = $_GET['id'];
$msg = "";
$msg_type = "";

// Handle Form Submission (Update Details)
if (isset($_POST['update_details'])) {
    $medical_number = $_POST['medical_number'];
    $medical_date = $_POST['medical_date'];
    $learner_permit_no = $_POST['learner_permit_no'];
    
    // Update onlineapplication table with missing details
    $update_sql = "UPDATE onlineapplication SET medical_number=?, medical_date=?, learner_permit_no=? WHERE app_id=?";
    $stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($stmt, "sssi", $medical_number, $medical_date, $learner_permit_no, $app_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Application details updated successfully!";
        $msg_type = "success";
    } else {
        $msg = "Error updating details: " . mysqli_error($con);
        $msg_type = "danger";
    }
    mysqli_stmt_close($stmt);
}

// Fetch Application Data
$sql = "SELECT * FROM onlineapplication WHERE app_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $app_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$app = mysqli_fetch_assoc($result);

if (!$app) {
    echo "Application not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Application - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
    <style>
        .form-label { font-weight: 500; color: #0f3460; }
        .card-header { background-color: #0f3460; color: white; }
    </style>
</head>
<body style="background-color: #f4f7f6;">

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Review Online Application</h2>
        <a href="pending_approvals.php" class="btn btn-secondary">Back to List</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Applicant Details: <?php echo htmlspecialchars($app['name']); ?></h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <!-- Personal Info (Read Only) -->
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['name']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIC</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['nic']); ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['dob']); ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Age</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['age']); ?>" readonly>
                    </div>
                     <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['gender']); ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" readonly><?php echo htmlspecialchars($app['address']); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vehicle Class</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['classofvehicle']); ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['phone_number']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($app['email']); ?>" readonly>
                    </div>

                    <!-- Documents (Read Only links) -->
                    <div class="col-12">
                        <hr>
                        <h5>Submitted Documents</h5>
                    </div>
                    <div class="col-md-6">
                        <p><strong>NIC Copy:</strong> <a href="../uploads/<?php echo htmlspecialchars($app['doc_nic']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">View Document</a></p>
                    </div>
                    <div class="col-md-6">
                         <p><strong>Address Proof:</strong> <a href="../uploads/<?php echo htmlspecialchars($app['doc_address']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">View Document</a></p>
                    </div>

                    <!-- Admin Sections (Editable) -->
                    <div class="col-12">
                        <hr>
                        <h5 class="text-danger">Admin Requirement for Approval</h5>
                        <p class="text-muted small">Please enter the Medical Certificate and Learner Permit details before approving.</p>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Medical Certificate No <span class="text-danger">*</span></label>
                        <input type="text" name="medical_number" class="form-control" value="<?php echo htmlspecialchars($app['medical_number'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Medical Date</label>
                        <input type="date" name="medical_date" class="form-control" value="<?php echo htmlspecialchars($app['medical_date'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Learner Permit No</label>
                        <input type="text" name="learner_permit_no" class="form-control" value="<?php echo htmlspecialchars($app['learner_permit_no'] ?? ''); ?>">
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2 justify-content-end">
                    <button type="submit" name="update_details" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Details
                    </button>
                    
                    <?php if(!empty($app['medical_number'])): ?>
                        <a href="approve_user.php?id=<?php echo $app_id; ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this student?');">
                            <i class="fas fa-check-circle"></i> Approve Student
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled title="Please Save Details (Medical No) first">
                            Approve Student
                        </button>
                    <?php endif; ?>
                    
                    <a href="reject_user.php?id=<?php echo $app_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this application?');">
                        <i class="fas fa-times-circle"></i> Reject
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
