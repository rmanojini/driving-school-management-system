<?php
include '../includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body style="background-color: #f4f7f6;">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--karuneelam-navy);">Student Exam Results</h2>
        <div>
            <a href="add_result.php" class="btn btn-primary">+ Add Result</a>
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
                        <th>Exam Type</th>
                        <th>Date</th>
                        <th>Marks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT res.*, r.name FROM results res JOIN registration r ON res.student_id = r.index ORDER BY res.exam_date DESC";
                    $result = mysqli_query($con, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $badge = $row['result_status'] == 'pass' ? 'success' : ($row['result_status'] == 'fail' ? 'danger' : 'warning');
                            echo "<tr>";
                            echo "<td>" . $row['result_id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . ucfirst($row['exam_type']) . "</td>";
                            echo "<td>" . $row['exam_date'] . "</td>";
                            echo "<td>" . $row['marks'] . "</td>";
                            echo "<td><span class='badge bg-$badge'>" . ucfirst($row['result_status']) . "</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No results recorded.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
