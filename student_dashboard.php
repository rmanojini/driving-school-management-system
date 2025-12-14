<?php
session_start();
if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($_SESSION['student_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">
    <style>
        body { background-color: #f4f7f6; color: #333; }
        .dashboard-header { background: var(--karuneelam-navy); color: #fff; padding: 20px 0; }
        .dashboard-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .welcome-box { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .logout-btn { background: var(--alex-secondary); color: #fff; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Student <span>Portal</span></h1>
            <a href="includes/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="welcome-box">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h2>
            <p>You are officially enrolled. This dashboard will eventually track your lessons and payments.</p>
        </div>

        <!-- Placeholder for future features -->
        <div class="feature-grid">
            <div class="feature-item" style="background:#fff; color:#333; animation:none; opacity:1; transform:none; border: 1px solid #eee;">
                <h4><i class="fas fa-book"></i> My Lessons</h4>
                <p>View upcoming driving schedules.</p>
            </div>
            <div class="feature-item" style="background:#fff; color:#333; animation:none; opacity:1; transform:none; border: 1px solid #eee;">
                <h4><i class="fas fa-file-invoice"></i> Payments</h4>
                <p>Check payment history.</p>
            </div>
        </div>
    </div>

</body>
</html>
