<?php
include '../includes/connection.php';

    if(isset($_POST['add_result'])){
    $student_id = $_POST['student_id'];
    $exam_type = $_POST['exam_type'];
    $exam_date = $_POST['exam_date'];
    $marks = $_POST['marks'];
    $result_status = $_POST['result_status'];

    // Check for Duplicates
    $check_sql = "SELECT * FROM results WHERE student_id = ? AND exam_type = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $student_id, $exam_type);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if(mysqli_stmt_num_rows($check_stmt) > 0){
        echo "<script>alert('Error: Result already exists for this student and exam type!'); window.location.href='results.php';</script>";
        mysqli_stmt_close($check_stmt);
    } elseif($marks > 40){
         echo "<script>alert('Error: Marks cannot be greater than 40!');</script>";
         mysqli_stmt_close($check_stmt);
    } else {
        mysqli_stmt_close($check_stmt);
        $sql = "INSERT INTO results (student_id, exam_type, exam_date, marks, result_status) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sssis", $student_id, $exam_type, $exam_date, $marks, $result_status);
    
        if(mysqli_stmt_execute($stmt)){
            // Fetch Student Email & Name for Notification
            $stmt_student = mysqli_prepare($con, "SELECT name, email FROM registration WHERE `index` = ?");
            mysqli_stmt_bind_param($stmt_student, "s", $student_id);
            mysqli_stmt_execute($stmt_student);
            $res_student = mysqli_stmt_get_result($stmt_student);
            
            if($row_student = mysqli_fetch_assoc($res_student)){
                $student_email = $row_student['email'];
                $student_name = $row_student['name'];
                
                // Send Email
                include '../includes/email_helper.php';
                send_result_notification($student_email, $student_name, $exam_type, $marks, $result_status);
                
                echo "<script>alert('Result Added Successfully! Email notification sent to $student_name.'); window.location.href='results.php';</script>";
            } else {
                echo "<script>alert('Result Added, but could not fetch student email.'); window.location.href='results.php';</script>";
            }
            mysqli_stmt_close($stmt_student);

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
