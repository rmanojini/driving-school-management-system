<?php
session_start();
if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}
include 'includes/connection.php';

// Prices Array
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

// Get student records
$email = $_SESSION['student_email'];
$sql_student = "SELECT `index`, classofvehicle FROM registration WHERE email = ?";
$stmt = mysqli_prepare($con, $sql_student);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res_student = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($res_student);

$student_id = $student['index'];
$my_class = $student['classofvehicle'];
$total_fee = isset($prices[$my_class]) ? $prices[$my_class] : 0;

// Fetch Payments
$paid_amount = 0;
$sql_pay = "SELECT * FROM payments WHERE student_id = ? ORDER BY payment_date DESC";
$stmt_pay = mysqli_prepare($con, $sql_pay);
mysqli_stmt_bind_param($stmt_pay, "i", $student_id);
mysqli_stmt_execute($stmt_pay);
$result_pay = mysqli_stmt_get_result($stmt_pay);

$payment_history = [];
while($row = mysqli_fetch_assoc($result_pay)){
    $paid_amount += $row['amount'];
    $payment_history[] = $row;
}
$balance = $total_fee - $paid_amount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Payments - Driving School</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f4f7f6; min-height: 100vh; }
        
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px 0; color: white; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 24px; }
        .back-btn { background: rgba(255,255,255,0.2); color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .back-btn:hover { background: rgba(255,255,255,0.3); }

        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .summary-card { background: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .summary-label { display: block; color: #888; font-size: 14px; margin-bottom: 5px; }
        .summary-value { display: block; font-size: 24px; font-weight: 700; color: #333; }
        .text-green { color: #28a745; }
        .text-red { color: #dc3545; }

        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        h3 { margin-bottom: 20px; color: #444; }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #555; }
        
        .empty-state { text-align: center; padding: 40px; color: #888; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-file-invoice-dollar"></i> My Payments</h1>
            <a href="student_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Dashboard</a>
        </div>
    </div>

    <div class="container">
        
        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <span class="summary-label">Course Fee (<?php echo $my_class; ?>)</span>
                <span class="summary-value">Rs. <?php echo number_format($total_fee); ?></span>
            </div>
            <div class="summary-card">
                <span class="summary-label">Total Paid</span>
                <span class="summary-value text-green">Rs. <?php echo number_format($paid_amount); ?></span>
            </div>
            <div class="summary-card">
                <span class="summary-label">Balance Due</span>
                <span class="summary-value text-red">Rs. <?php echo number_format($balance > 0 ? $balance : 0); ?></span>
            </div>
        </div>

        <!-- History Table -->
        <div class="card">
            <h3>Payment History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($payment_history) > 0): ?>
                        <?php foreach($payment_history as $pay): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($pay['payment_date'])); ?></td>
                            <td><?php echo ucwords(str_replace('_', ' ', $pay['payment_type'])); ?></td>
                            <td><strong>Rs. <?php echo number_format($pay['amount']); ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="empty-state">No payments recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
