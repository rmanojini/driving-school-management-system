<?php
include '../includes/connection.php';

if(isset($_POST['add_schedule'])){
    $student_id = $_POST['student_id'];
    $schedule_type = $_POST['schedule_type'];
    $scheduled_datetime = $_POST['scheduled_datetime'];
    $status = 'scheduled';

    $sql = "INSERT INTO schedules (student_id, schedule_type, scheduled_datetime, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $student_id, $schedule_type, $scheduled_datetime, $status);

    if(mysqli_stmt_execute($stmt)){
        echo "<script>alert('Schedule Created Successfully!'); window.location.href='schedule.php';</script>";
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
    <title>Add Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Schedule Lesson / Test</h4>
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
                    <label>Type</label>
                    <select name="schedule_type" class="form-select" required>
                        <option value="lesson">Driving Lesson</option>
                        <option value="theory_test">Theory Test</option>
                        <option value="practical_test">Practical Test</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Date & Time</label>
                    <input type="datetime-local" name="scheduled_datetime" class="form-control" required>
                </div>
                
                <button type="submit" name="add_schedule" class="btn btn-success w-100">Create Schedule</button>
                <a href="schedule.php" class="btn btn-link w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
