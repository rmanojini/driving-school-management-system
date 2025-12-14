<?php
include '../includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body style="background-color: #f4f7f6;">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--karuneelam-navy);">Training & Test Schedules</h2>
        <div>
            <a href="add_schedule.php" class="btn btn-primary">+ Add Schedule</a>
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
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT s.*, r.name FROM schedules s JOIN registration r ON s.student_id = r.index ORDER BY s.scheduled_datetime ASC";
                    $result = mysqli_query($con, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $badgeColor = $row['status'] == 'completed' ? 'success' : ($row['status'] == 'cancelled' ? 'danger' : 'warning');
                            echo "<tr>";
                            echo "<td>" . $row['schedule_id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . ucwords(str_replace('_', ' ', $row['schedule_type'])) . "</td>";
                            echo "<td>" . date('M d, Y h:i A', strtotime($row['scheduled_datetime'])) . "</td>";
                            echo "<td><span class='badge bg-$badgeColor'>" . ucfirst($row['status']) . "</span></td>";
                            echo "<td>
                                    <a href='mark_complete.php?type=schedule&id=" . $row['schedule_id'] . "' class='btn btn-sm btn-outline-success'>Complete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No schedules found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
