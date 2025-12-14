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
                
                <div class="mb-3">
                    <label>Vehicle Class</label>
                    <select class="form-select" id="vehicle_class" name="classofvehicle" required>
                        <option value="" disabled selected>Select a vehicle class</option>
                        <option value="A">A - Motor Cycles (Motorbikes)</option>
                        <option value="B">B - Motor Cars / Van</option>
                        <option value="B1">B1 - Motor Tricycles (Three Wheelers)</option>
                        <option value="G">G - Land Vehicles (Tractors / Agricultural)</option>
                        <option value="D">D - Heavy Motor Vehicles (Lorry / Bus)</option>
                        <option value="AB1">AB1 - Combo: A (Motor Cycles) & B1 (Tricycles)</option>
                        <option value="AB1B">AB1B - Combo: A, B1, and B (Motor Cars)</option>
                        <option value="AB1BG">AB1BG - Combo: A, B1, B, and G</option>
                        <option value="AB">AB - Combo: A (Motor Cycles) & B (Motor Cars)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-select" required>
                        <option value="" disabled selected>Select Type</option>
                        <option value="1st_installment">1st Installment</option>
                        <option value="full_payment">Full Payment</option>
                        <option value="2nd_installment">2nd Installment / Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Amount (Rs)</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
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
    const vehicleSelect = document.getElementById('vehicle_class');
    const typeSelect = document.getElementById('payment_type');
    const amountInput = document.getElementById('amount');

    // Pricing Structure
    const prices = {
        'A': { '1st_installment': 5000, 'full_payment': 9000 },
        'B': { '1st_installment': 9000, 'full_payment': 18000 },
        'B1': { '1st_installment': 5000, 'full_payment': 10000 },
        'G': { '1st_installment': 5000, 'full_payment': 10000 },
        'D': { '1st_installment': 10000, 'full_payment': 19000 },
        'AB': { '1st_installment': 10000, 'full_payment': 20000 },
        'AB1': { '1st_installment': 7500, 'full_payment': 15000 },
        'AB1B': { '1st_installment': 11000, 'full_payment': 22500 },
        'AB1BG': { '1st_installment': 12000, 'full_payment': 25000 }
    };

    // Auto-select Class when Student updates
    studentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const studentClass = selectedOption.getAttribute('data-class');
        
        if(studentClass) {
            vehicleSelect.value = studentClass;
            updateAmount(); // Trigger amount update if Type is already selected
        }
    });

    // Update Amount when Class or Type changes
    function updateAmount() {
        const vClass = vehicleSelect.value;
        const pType = typeSelect.value;

        if (vClass && pType && prices[vClass] && prices[vClass][pType]) {
            amountInput.value = prices[vClass][pType];
        } else {
            // Don't clear if it's '2nd_installment' or manual entry, just leave as is or clear if new selection is invalid
            if (pType !== '2nd_installment') {
                 // optionally clear or leave blank
            }
        }
    }

    vehicleSelect.addEventListener('change', updateAmount);
    typeSelect.addEventListener('change', updateAmount);

</script>
</body>
</html>
