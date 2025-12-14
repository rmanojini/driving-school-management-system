<?php
include '../includes/connection.php';

if(isset($_POST['add_payment'])){
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_type = $_POST['payment_type'];
    $remarks = $_POST['remarks'];

    $sql = "INSERT INTO payments (student_id, amount, payment_type, remarks) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sdss", $student_id, $amount, $payment_type, $remarks);

    if(mysqli_stmt_execute($stmt)){
        echo "<script>alert('Payment Added Successfully!'); window.location.href='payments.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Record New Payment</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Select Student</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">-- Select Student --</option>
                        <?php
                        // Only show students who are approved? Or all? Let's show all.
                        $res = mysqli_query($con, "SELECT `index`, name FROM registration");
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<option value='" . $row['index'] . "'>" . $row['name'] . " (" . $row['index'] . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Amount (Rs)</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label>Payment Type</label>
                    <select name="payment_type" class="form-select" required>
                        <option value="1st_installment">1st Installment</option>
                        <option value="2nd_installment">2nd Installment</option>
                        <option value="full_payment">Full Payment</option>
                        <option value="exam_fee">Exam Fee</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control"></textarea>
                </div>
                <button type="submit" name="add_payment" class="btn btn-success w-100">Save Payment</button>
                <a href="payments.php" class="btn btn-link w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
