<?php
include '../includes/connection.php';

// 1. Define Pricing Rules (Server-side Source of Truth) - Moved to top
$prices = [
    'A' => ['1st_installment' => 4500, 'full_payment' => 9000, '2nd_installment' => 4500],
    'B' => ['1st_installment' => 9000, 'full_payment' => 18000, '2nd_installment' => 9000],
    'B1' => ['1st_installment' => 5000, 'full_payment' => 10000, '2nd_installment' => 5000],
    'G' => ['1st_installment' => 5000, 'full_payment' => 10000, '2nd_installment' => 5000],
    'D' => ['1st_installment' => 9500, 'full_payment' => 19000, '2nd_installment' => 9500],
    'AB' => ['1st_installment' => 10000, 'full_payment' => 20000, '2nd_installment' => 10000],
    'AB1' => ['1st_installment' => 7500, 'full_payment' => 15000, '2nd_installment' => 7500],
    'AB1B' => ['1st_installment' => 11250, 'full_payment' => 22500, '2nd_installment' => 11250],
    'AB1BG' => ['1st_installment' => 12500, 'full_payment' => 25000, '2nd_installment' => 12500]
];

if(isset($_POST['add_payment'])){
    $student_id = $_POST['student_id'];
    $payment_type = $_POST['payment_type'];
    $remarks = $_POST['remarks'];

    // Fetch Student's Vehicle Class from DB
    $stmt_class = mysqli_prepare($con, "SELECT classofvehicle FROM registration WHERE `index` = ?");
    mysqli_stmt_bind_param($stmt_class, "s", $student_id);
    mysqli_stmt_execute($stmt_class);
    $res_class = mysqli_stmt_get_result($stmt_class);
    $row_class = mysqli_fetch_assoc($res_class);
    $db_class = $row_class['classofvehicle'] ?? '';
    mysqli_stmt_close($stmt_class);

    // SERVER-SIDE VALIDATION: Check if already fully paid
    if ($payment_type != 'exam_fee') {
        $check_total = isset($prices[$db_class]['full_payment']) ? $prices[$db_class]['full_payment'] : 0;
        
        $q_paid = "SELECT SUM(amount) as total FROM payments WHERE student_id = '$student_id' AND payment_type != 'exam_fee'";
        $r_paid = mysqli_query($con, $q_paid);
        $d_paid = mysqli_fetch_assoc($r_paid);
        $already_paid = $d_paid['total'] ?? 0;

        if ($already_paid >= $check_total) {
            echo "<script>alert('Error: This student has already paid the full course fee.'); window.location.href='add_payment.php';</script>";
            exit;
        }
    }

    // Determine Amount
    $amount = 0;
    if ($payment_type == 'exam_fee') {
        $amount = $_POST['amount']; 
    } elseif (isset($prices[$db_class][$payment_type])) {
        $amount = $prices[$db_class][$payment_type];
    } else {
         echo "<script>alert('Error: Invalid Payment Type or Class configuration.');</script>";
         exit; 
    }

    // Insert Logic: Handle Full Payment Split
    if ($payment_type == 'full_payment') {
        // Split Amount
        $half_amount = $amount / 2;
        
        // 1st Installment
        $sql1 = "INSERT INTO payments (student_id, amount, payment_type, remarks) VALUES (?, ?, '1st_installment', ?)";
        $stmt1 = mysqli_prepare($con, $sql1);
        $remarks1 = $remarks . " (Split from Full Payment)";
        mysqli_stmt_bind_param($stmt1, "sds", $student_id, $half_amount, $remarks1);
        $res1 = mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);

        // 2nd Installment
        $sql2 = "INSERT INTO payments (student_id, amount, payment_type, remarks) VALUES (?, ?, '2nd_installment', ?)";
        $stmt2 = mysqli_prepare($con, $sql2);
        $remarks2 = $remarks . " (Split from Full Payment)";
        mysqli_stmt_bind_param($stmt2, "sds", $student_id, $half_amount, $remarks2);
        $res2 = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        if($res1 && $res2){
             echo "<script>alert('Full Payment Recorded Successfully! (Split into 2 Installments)'); window.location.href='payments.php';</script>";
        } else {
             echo "<script>alert('Error recording split payments: " . mysqli_error($con) . "');</script>";
        }

    } else {
        // Normal Single Payment
        $sql = "INSERT INTO payments (student_id, amount, payment_type, remarks) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sdss", $student_id, $amount, $payment_type, $remarks);

        if(mysqli_stmt_execute($stmt)){
            echo "<script>alert('Payment Added Successfully! Amount: Rs " . $amount . "'); window.location.href='payments.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    }
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
        <div class="card-header text-white" style="background-color: navy;">
            <h4>Record New Payment</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Select Student</label>
                    <select name="student_id" id="student_id" class="form-select" required>
                        <option value="" data-class="">-- Select Student --</option>
                        <?php
                        // Fetch Students
                        $res = mysqli_query($con, "SELECT `index`, name, classofvehicle FROM registration");
                        
                        // Load all payments logic to filter
                        // Optimization: Fetch all payments sum grouped by student first
                        $pay_map = [];
                        $pay_q = mysqli_query($con, "SELECT student_id, SUM(amount) as paid FROM payments WHERE payment_type != 'exam_fee' GROUP BY student_id");
                        while($p = mysqli_fetch_assoc($pay_q)){
                            $pay_map[$p['student_id']] = $p['paid'];
                        }

                        while($row = mysqli_fetch_assoc($res)){
                            $sid = $row['index'];
                            $sclass = $row['classofvehicle'];
                            $total_fee = isset($prices[$sclass]['full_payment']) ? $prices[$sclass]['full_payment'] : 0;
                            $paid_so_far = isset($pay_map[$sid]) ? $pay_map[$sid] : 0;

                            // FILTER: Only show if balance remains
                            if($paid_so_far < $total_fee) {
                                echo "<option value='" . $sid . "' data-class='" . $sclass . "'>" . $row['name'] . " (" . $sid . ")</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label>Vehicle Class (Auto-Detected)</label>
                    <input type="text" id="vehicle_class_display" class="form-control" readonly style="background-color: #e9ecef;">
                </div>

                <div class="mb-3">
                    <label>Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-select" required>
                        <option value="" disabled selected>Select Type</option>
                        <option value="1st_installment">1st Installment</option>
                        <option value="full_payment">Full Payment</option>
                        <option value="2nd_installment">2nd Installment (Balance)</option>
                         <option value="exam_fee">Exam Fee (Manual)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Amount (Rs) - <small class="text-muted">Auto-Calculated</small></label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" readonly style="background-color: #e9ecef;" required>
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

<script>
    const studentSelect = document.getElementById('student_id');
    const vehicleDisplay = document.getElementById('vehicle_class_display');
    const typeSelect = document.getElementById('payment_type');
    const amountInput = document.getElementById('amount');

    // Pricing Structure (Passed from PHP to JS essentially, but let's keep the hardcoded JS object for interactivity speed, ensuring it matches PHP)
    const prices = {
        'A': { '1st_installment': 4500, 'full_payment': 9000, '2nd_installment': 4500 },
        'B': { '1st_installment': 9000, 'full_payment': 18000, '2nd_installment': 9000 },
        'B1': { '1st_installment': 5000, 'full_payment': 10000, '2nd_installment': 5000 },
        'G': { '1st_installment': 5000, 'full_payment': 10000, '2nd_installment': 5000 },
        'D': { '1st_installment': 9500, 'full_payment': 19000, '2nd_installment': 9500 },
        'AB': { '1st_installment': 10000, 'full_payment': 20000, '2nd_installment': 10000 },
        'AB1': { '1st_installment': 7500, 'full_payment': 15000, '2nd_installment': 7500 },
        'AB1B': { '1st_installment': 11250, 'full_payment': 22500, '2nd_installment': 11250 },
        'AB1BG': { '1st_installment': 12500, 'full_payment': 25000, '2nd_installment': 12500 }
    };

    let currentClass = '';

    studentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        currentClass = selectedOption.getAttribute('data-class');
        
        if(currentClass) {
            vehicleDisplay.value = currentClass; 
            updateAmount();
        } else {
            vehicleDisplay.value = '';
        }
    });

    function updateAmount() {
        const pType = typeSelect.value;
        
        if(pType === 'exam_fee') {
             amountInput.readOnly = false;
             amountInput.style.backgroundColor = '#ffffff';
             amountInput.placeholder = 'Enter Exam Fee';
             amountInput.value = '';
             return;
        }

        amountInput.readOnly = true;
        amountInput.style.backgroundColor = '#e9ecef';

        if (currentClass && pType && prices[currentClass] && prices[currentClass][pType]) {
            amountInput.value = prices[currentClass][pType];
        } else if (currentClass && pType) {
             amountInput.value = 0;
        }
    }

    typeSelect.addEventListener('change', updateAmount);

</script>
</body>
</html>
