<?php
// session_start();
// if($_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }
include '../includes/connection.php';
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
        <h2 style="color: var(--karuneelam-navy);">Student Payments</h2>
        <div>
            <a href="add_payment.php" class="btn btn-primary">+ Add New Payment</a>
            <a href="../dashboard.html" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, r.name FROM payments p JOIN registration r ON p.student_id = r.index ORDER BY p.payment_date DESC";
                    $result = mysqli_query($con, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>" . $row['payment_id'] . "</td>";
                            echo "<td>" . $row['name'] . " (" . $row['student_id'] . ")</td>";
                            echo "<td>Rs. " . number_format($row['amount'], 2) . "</td>";
                            echo "<td>" . ucwords(str_replace('_', ' ', $row['payment_type'])) . "</td>";
                            echo "<td>" . date('Y-m-d H:i', strtotime($row['payment_date'])) . "</td>";
                            echo "<td>" . $row['remarks'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No payment records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
