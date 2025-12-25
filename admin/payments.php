<?php
// session_start();
// if($_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }
include '../includes/connection.php';

// Define Pricing Rules (Same as in add_payment.php) - centralized source of truth ideally
$prices = [
    'A' => 9000,
    'B' => 18000,
    'B1' => 10000,
    'G' => 10000,
    'D' => 19000,
    'AB' => 20000,
    'AB1' => 15000,
    'AB1B' => 22500,
    'AB1BG' => 25000
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body style="background-color: #f4f7f6;">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--karuneelam-navy);">Student Payment Summary</h2>
        <div>
            <a href="add_payment.php" class="btn btn-primary">+ Add New Payment</a>
            <a href="../welcome_splash.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Payment Status Overview</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Total Fee</th>
                        <th>1st Pay</th>
                        <th>2nd Pay</th>
                        <th>Total Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 1. Get all students and their class
                    $sql_students = "SELECT `index` as id, name, classofvehicle FROM registration ORDER BY `index` DESC";
                    $res_students = mysqli_query($con, $sql_students);

                    if(mysqli_num_rows($res_students) > 0){
                        while($student = mysqli_fetch_assoc($res_students)){
                            $student_id = $student['id'];
                            $class = $student['classofvehicle'];
                            $total_fee = isset($prices[$class]) ? $prices[$class] : 0;

                            // 2. Get payments for this student
                            $sql_pay = "SELECT * FROM payments WHERE student_id = '$student_id'";
                            $res_pay = mysqli_query($con, $sql_pay);

                            $paid_1st = 0;
                            $paid_2nd = 0;
                            $paid_full = 0;
                            $total_paid = 0;
                            $other_paid = 0; // Exam fees, etc.

                            while($pay = mysqli_fetch_assoc($res_pay)){
                                if($pay['payment_type'] == '1st_installment') {
                                    $paid_1st += $pay['amount'];
                                } elseif($pay['payment_type'] == '2nd_installment') {
                                    $paid_2nd += $pay['amount'];
                                } elseif($pay['payment_type'] == 'full_payment') {
                                    $paid_full += $pay['amount'];
                                } else {
                                    $other_paid += $pay['amount'];
                                }
                            }

                            // Logic: If 'full_payment' exists, it covers everything. 
                            // Otherwise sum 1st + 2nd.
                            // However, we want to show column-wise.
                            // If paid_full > 0, we can display it in Total Paid or split visual if preferred?
                            // Let's just sum them up for Total Paid.
                            
                            $total_paid = $paid_1st + $paid_2nd + $paid_full; 
                            // Note: We exclude 'exam_fee' from the Course Fee Balance calculation usually?
                            // User asked: "balance evvalavu pay pannanum enkirathaiyum total amount yum kaadramaathiri"
                            // Presumably refers to Course Fee.
                            
                            $balance = $total_fee - $total_paid;
                            if($balance < 0) $balance = 0; // Should not happen ideally

                            // Status
                            $status = ($total_paid >= $total_fee && $total_fee > 0) ? '<span class="badge bg-success">Completed</span>' : '<span class="badge bg-warning text-dark">Pending</span>';
                            if($total_fee == 0) $status = '<span class="badge bg-secondary">N/A</span>';

                            echo "<tr>";
                            echo "<td>{$student['id']}</td>";
                            echo "<td>{$student['name']}</td>";
                            echo "<td>{$class}</td>";
                            echo "<td>Rs. " . number_format($total_fee) . "</td>";
                            
                            // 1st Pay Column
                            if($paid_full > 0) {
                                echo "<td><span class='badge bg-success'>Full Paid</span></td>";
                                echo "<td>-</td>";
                            } else {
                                echo "<td>" . ($paid_1st > 0 ? number_format($paid_1st) : '-') . "</td>";
                                echo "<td>" . ($paid_2nd > 0 ? number_format($paid_2nd) : '-') . "</td>";
                            }

                            echo "<td>Rs. " . number_format($total_paid) . "</td>";
                            echo "<td class='fw-bold text-danger'>" . ($balance > 0 ? "Rs. " . number_format($balance) : "Rs. 0") . "</td>";
                            echo "<td>{$status}</td>";
                            
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No students found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Transactions Section (Optional, keeping for history log) -->
    <div class="card shadow-sm">
        <div class="card-header">
            Recent Transactions Log
        </div>
        <div class="card-body">
            <table class="table table-sm table-hover text-muted">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_log = "SELECT p.*, r.name FROM payments p JOIN registration r ON p.student_id = r.index ORDER BY p.payment_date DESC LIMIT 10";
                    $res_log = mysqli_query($con, $sql_log);
                    while($log = mysqli_fetch_assoc($res_log)){
                        echo "<tr>";
                        echo "<td>" . date('Y-m-d', strtotime($log['payment_date'])) . "</td>";
                        echo "<td>{$log['name']}</td>";
                        echo "<td>{$log['payment_type']}</td>";
                        echo "<td>" . number_format($log['amount']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
