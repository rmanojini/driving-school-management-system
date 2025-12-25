<?php
include '../includes/connection.php';

// Fetch Statistics
$total_students = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM registration"))['c'];
$pending_students = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM registration WHERE status='pending'"))['c'];
$total_income = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(amount) as s FROM payments"))['s'];
$passed_exams = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM results WHERE result_status='pass'"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports & Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
    <style>
        .report-card { border: none; border-radius: 10px; color: white; padding: 20px; text-align: center; }
        .bg-money { background: linear-gradient(45deg, #11998e, #38ef7d); }
        .bg-users { background: linear-gradient(45deg, #3a7bd5, #00d2ff); }
        .bg-pending { background: linear-gradient(45deg, #ff9966, #ff5e62); }
        .bg-pass { background: linear-gradient(45deg, #F2994A, #F2C94C); }
    </style>
</head>
<body style="background-color: #f4f7f6;">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--karuneelam-navy);">System Reports</h2>
        <a href="../welcome_splash.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="report-card bg-users">
                <h3><?php echo $total_students; ?></h3>
                <p>Total Registered Students</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-card bg-pending">
                <h3><?php echo $pending_students; ?></h3>
                <p>Pending Approvals</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-card bg-money">
                <h3>Rs. <?php echo number_format($total_income, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-card bg-pass">
                <h3><?php echo $passed_exams; ?></h3>
                <p>Total Exams Passed</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5>Recent New Registrations</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php
                        $res = mysqli_query($con, "SELECT name, reg_date FROM registration ORDER BY reg_date DESC LIMIT 5");
                        while($r = mysqli_fetch_assoc($res)){
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                            echo $r['name'];
                            echo "<span class='badge bg-light text-dark'>" . $r['reg_date'] . "</span>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5>Recent Payments</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                         <?php
                        $res = mysqli_query($con, "SELECT r.name, p.amount FROM payments p JOIN registration r ON p.student_id = r.index ORDER BY p.payment_date DESC LIMIT 5");
                        while($r = mysqli_fetch_assoc($res)){
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                            echo $r['name'];
                            echo "<span class='badge bg-success'>Rs. " . $r['amount'] . "</span>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
