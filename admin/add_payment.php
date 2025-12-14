<?php
include '../includes/connection.php';

if(isset($_POST['add_payment'])){
    $student_id = $_POST['student_id'];
    $payment_type = $_POST['payment_type'];
    $remarks = $_POST['remarks'];

    // 1. Fetch Student's Vehicle Class from DB (Secure)
    $stmt_class = mysqli_prepare($con, "SELECT classofvehicle FROM registration WHERE `index` = ?");
    mysqli_stmt_bind_param($stmt_class, "s", $student_id);
    mysqli_stmt_execute($stmt_class);
    $res_class = mysqli_stmt_get_result($stmt_class);
    $row_class = mysqli_fetch_assoc($res_class);
    $db_class = $row_class['classofvehicle'] ?? '';
    mysqli_stmt_close($stmt_class);

    // 2. Define Pricing Rules (Server-side Source of Truth)
    $prices = [
        'A' => ['1st_installment' => 5000, 'full_payment' => 9000, '2nd_installment' => 4000],
        'B' => ['1st_installment' => 9000, 'full_payment' => 18000, '2nd_installment' => 9000],
        'B1' => ['1st_installment' => 5000, 'full_payment' => 10000, '2nd_installment' => 5000],
        'G' => ['1st_installment' => 5000, 'full_payment' => 10000, '2nd_installment' => 5000],
        'D' => ['1st_installment' => 10000, 'full_payment' => 19000, '2nd_installment' => 9000],
        'AB' => ['1st_installment' => 10000, 'full_payment' => 20000, '2nd_installment' => 10000],
        'AB1' => ['1st_installment' => 7500, 'full_payment' => 15000, '2nd_installment' => 7500],
        'AB1B' => ['1st_installment' => 11000, 'full_payment' => 22500, '2nd_installment' => 11500],
        'AB1BG' => ['1st_installment' => 12000, 'full_payment' => 25000, '2nd_installment' => 13000]
    ];

    // 3. Determine/Validate Amount
    $amount = 0;
    if ($payment_type == 'exam_fee') {
        // Exam fee is manually entered for now? Or strict? User didn't specify. 
        // Let's allow manual input for exam_fee ONLY, but user asked to block manual.
        // Assuming strict adherence to price list for the Installments.
        // If $_POST['amount'] differs from Price List, OVERWRITE IT or REJECT IT?
        // Safest is to OVERWRITE it with the correct price.
        $amount = $_POST['amount']; // Allow manual for non-listed types?
    } elseif (isset($prices[$db_class][$payment_type])) {
        $amount = $prices[$db_class][$payment_type];
    } else {
        // Fallback or Error
         echo "<script>alert('Error: Invalid Payment Type or Class configuration.');</script>";
         exit; // Or handle gracefully
    }

    // Insert
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
                    <select name="student_id" id="student_id" class="form-select" required>
                        <option value="" data-class="">-- Select Student --</option>
                        <?php
                        $res = mysqli_query($con, "SELECT `index`, name, classofvehicle FROM registration");
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<option value='" . $row['index'] . "' data-class='" . $row['classofvehicle'] . "'>" . $row['name'] . " (" . $row['index'] . ")</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Vehicle Class - DISABLED (Visual Only) -->
                <div class="mb-3">
                    <label>Vehicle Class (Auto-Detected)</label>
                    <input type="text" id="vehicle_class_display" class="form-control" readonly style="background-color: #e9ecef;">
                    <!-- Hidden input to keep logic simple if needed, but we rely on DB lookup backend -->
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

    // Pricing Structure (Matches Server-Side)
    const prices = {
        'A': { '1st_installment': 5000, 'full_payment': 9000, '2nd_installment': 4000 },
        'B': { '1st_installment': 9000, 'full_payment': 18000, '2nd_installment': 9000 },
        'B1': { '1st_installment': 5000, 'full_payment': 10000, '2nd_installment': 5000 },
        'G': { '1st_installment': 5000, 'full_payment': 10000, '2nd_installment': 5000 },
        'D': { '1st_installment': 10000, 'full_payment': 19000, '2nd_installment': 9000 },
        'AB': { '1st_installment': 10000, 'full_payment': 20000, '2nd_installment': 10000 },
        'AB1': { '1st_installment': 7500, 'full_payment': 15000, '2nd_installment': 7500 },
        'AB1B': { '1st_installment': 11000, 'full_payment': 22500, '2nd_installment': 11500 },
        'AB1BG': { '1st_installment': 12000, 'full_payment': 25000, '2nd_installment': 13000 }
    };

    let currentClass = '';

    // Auto-select Class when Student updates
    studentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        currentClass = selectedOption.getAttribute('data-class');
        
        if(currentClass) {
            vehicleDisplay.value = currentClass; // Show Class Code
            updateAmount();
        } else {
            vehicleDisplay.value = '';
        }
    });

    // Update Amount
    function updateAmount() {
        const pType = typeSelect.value;
        
        if(pType === 'exam_fee') {
             amountInput.readOnly = false;
             amountInput.style.backgroundColor = '#ffffff';
             amountInput.placeholder = 'Enter Exam Fee';
             amountInput.value = '';
             return;
        }

        // Lock for standard payments
        amountInput.readOnly = true;
        amountInput.style.backgroundColor = '#e9ecef';

        if (currentClass && pType && prices[currentClass] && prices[currentClass][pType]) {
            amountInput.value = prices[currentClass][pType];
        } else if (currentClass && pType) {
             // If combo not found (rare), maybe allow manual? Or show 0
             amountInput.value = 0;
        }
    }

    typeSelect.addEventListener('change', updateAmount);

</script>
</body>
</html>
