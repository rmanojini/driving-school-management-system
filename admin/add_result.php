<?php
include '../includes/connection.php';

    if(isset($_POST['add_result'])){
    $student_id = $_POST['student_id'];
    $exam_type = $_POST['exam_type'];
    $exam_date = $_POST['exam_date'];
    $marks = $_POST['marks'];
    $result_status = $_POST['result_status'];

    if($marks > 40){
         echo "<script>alert('Error: Marks cannot be greater than 40!');</script>";
    } else {
        $sql = "INSERT INTO results (student_id, exam_type, exam_date, marks, result_status) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sssis", $student_id, $exam_type, $exam_date, $marks, $result_status);
    
        if(mysqli_stmt_execute($stmt)){
            echo "<script>alert('Result Added Successfully!'); window.location.href='results.php';</script>";
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
    <title>Add Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Record Exam Result</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Select Student</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">-- Select Student --</option>
                        <?php
                        $res = mysqli_query($con, "SELECT `index`, name FROM registration");
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<option value='" . $row['index'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Exam Type</label>
                    <select name="exam_type" class="form-select" required>
                        <option value="theory">Theory Test</option>
                        <option value="practical">Practical Test</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Exam Date</label>
                    <input type="date" name="exam_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Marks (Max 40)</label>
                    <input type="number" name="marks" id="marks" class="form-control" placeholder="1-40" max="40" min="0">
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="result_status" id="result_status" class="form-select" required>
                        <option value="pass">Pass</option>
                        <option value="fail">Fail</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                
                <button type="submit" name="add_result" class="btn btn-success w-100">Save Result</button>
                <a href="results.php" class="btn btn-link w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
    const marksInput = document.getElementById('marks');
    const statusSelect = document.getElementById('result_status');

    marksInput.addEventListener('input', function() {
        let marks = parseInt(this.value);
        
        if (marks > 40) {
            alert("Maximum marks allowed is 40");
            this.value = 40;
            marks = 40;
        }

        if (!isNaN(marks)) {
            if (marks < 30) {
                statusSelect.value = 'fail';
            } else {
                statusSelect.value = 'pass';
            }
        }
    });
</script>
        </div>
    </div>
</div>
</body>
</html>
