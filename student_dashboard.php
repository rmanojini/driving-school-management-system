<?php
session_start();
if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}

// Fetch student details from database
include 'includes/connection.php';
$email = $_SESSION['student_email'];
$sql = "SELECT * FROM registration WHERE email = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?php echo htmlspecialchars($student['name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        
        .dashboard-header { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.2); }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .header-content h1 { color: white; font-size: 28px; }
        .logout-btn { background: rgba(255,255,255,0.2); color: white; padding: 10px 25px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .logout-btn:hover { background: rgba(255,255,255,0.3); }
        
        .dashboard-container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        .welcome-card { background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .welcome-card h2 { color: #667eea; margin-bottom: 10px; }
        .welcome-card p { color: #666; font-size: 16px; }
        .username-badge { display: inline-block; background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; margin-top: 10px; }
        
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .info-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .info-card h3 { color: #667eea; margin-bottom: 20px; font-size: 18px; display: flex; align-items: center; gap: 10px; }
        .info-card h3 i { font-size: 24px; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #888; font-weight: 600; font-size: 14px; }
        .info-value { color: #333; font-weight: 500; text-align: right; }
        
        .status-badge { padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        
        .action-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .action-card { background: white; border-radius: 15px; padding: 25px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: 0.3s; cursor: pointer; text-decoration: none; color: inherit; display: block; }
        .action-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .action-card i { font-size: 48px; color: #667eea; margin-bottom: 15px; }
        .action-card h4 { color: #333; margin-bottom: 10px; }
        .action-card p { color: #888; font-size: 14px; }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-graduation-cap"></i> Student Portal</h1>
            <a href="includes/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <div class="dashboard-container">
        
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2>Welcome back, <?php echo htmlspecialchars($student['name']); ?>! 👋</h2>
            <p>You are successfully enrolled in Alex Driving School. Track your progress and manage your learning journey.</p>
            <span class="username-badge"><i class="fas fa-user"></i> <?php echo htmlspecialchars($student['username']); ?></span>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            
            <!-- Personal Information -->
            <div class="info-card">
                <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIC Number</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['nic']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value"><?php echo $student['dob'] ? date('d M Y', strtotime($student['dob'])) : 'N/A'; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age</span>
                    <span class="info-value"><?php echo $student['age'] ?? 'N/A'; ?> years</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['gender']); ?></span>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="info-card">
                <h3><i class="fas fa-address-book"></i> Contact Information</h3>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['phone_number']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address</span>
                    <span class="info-value" style="text-align: right; max-width: 200px;"><?php echo htmlspecialchars($student['address']); ?></span>
                </div>
            </div>

            <!-- Course Details -->
            <div class="info-card">
                <h3><i class="fas fa-car"></i> Course Details</h3>
                <div class="info-row">
                    <span class="info-label">Vehicle Class</span>
                    <span class="info-value"><strong><?php echo htmlspecialchars($student['classofvehicle']); ?></strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Registration Date</span>
                    <span class="info-value"><?php echo $student['reg_date'] ? date('d M Y', strtotime($student['reg_date'])) : 'N/A'; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge status-<?php echo $student['status']; ?>">
                            <?php echo strtoupper($student['status']); ?>
                        </span>
                    </span>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="info-card">
                <h3><i class="fas fa-heartbeat"></i> Medical Information</h3>
                <div class="info-row">
                    <span class="info-label">Certificate Number</span>
                    <span class="info-value"><?php echo htmlspecialchars($student['medical_number'] ?? 'Not Provided'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Certificate Date</span>
                    <span class="info-value"><?php echo $student['medical_date'] ? date('d M Y', strtotime($student['medical_date'])) : 'Not Provided'; ?></span>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <h3 style="color: white; margin-bottom: 20px; font-size: 24px;"><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="action-grid">
            <a href="student_schedule.php" class="action-card">
                <i class="fas fa-calendar-alt"></i>
                <h4>My Schedule</h4>
                <p>View upcoming lessons</p>
            </a>
            <a href="student_payments.php" class="action-card">
                <i class="fas fa-credit-card"></i>
                <h4>Payments</h4>
                <p>Check payment history</p>
            </a>
            <a href="student_results.php" class="action-card">
                <i class="fas fa-poll-h"></i>
                <h4>Exam Results</h4>
                <p>View your scores</p>
            </a>
            <a href="change_password.php" class="action-card">
                <i class="fas fa-key"></i>
                <h4>Change Password</h4>
                <p>Update your password</p>
            </a>
        </div>

    </div>

</body>
</html>
