<?php
session_start();
if(!isset($_SESSION['student_email'])){
    header("Location: student_login.php");
    exit();
}
include 'includes/connection.php';

// Get logged in student ID
$email = $_SESSION['student_email'];
$sql_student = "SELECT `index` FROM registration WHERE email = ?";
$stmt = mysqli_prepare($con, $sql_student);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res_student = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($res_student);
$student_id = $student['index'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Schedule - Driving School</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f4f7f6; min-height: 100vh; }
        
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px 0; color: white; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 24px; }
        .back-btn { background: rgba(255,255,255,0.2); color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .back-btn:hover { background: rgba(255,255,255,0.3); }

        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #555; font-weight: 600; }
        tr:hover { background-color: #f9f9f9; }
        
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-completed { background: #d4edda; color: #155724; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }

        .empty-state { text-align: center; padding: 40px; color: #888; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; color: #ddd; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-alt"></i> My Schedule</h1>
            <a href="student_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Dashboard</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h3>Upcoming & Past Sessions</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM schedules WHERE student_id = ? ORDER BY scheduled_datetime DESC";
                    $stmt_sched = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt_sched, "i", $student_id);
                    mysqli_stmt_execute($stmt_sched);
                    $result = mysqli_stmt_get_result($stmt_sched);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $status = strtolower($row['status']);
                            $badgeClass = 'badge-' . $status;
                            
                            // Formating type
                            $type = ucwords(str_replace('_', ' ', $row['schedule_type']));
                            
                            echo "<tr>";
                            echo "<td><strong>{$type}</strong></td>";
                            echo "<td>" . date('M d, Y h:i A', strtotime($row['scheduled_datetime'])) . "</td>";
                            echo "<td><span class='badge {$badgeClass}'>" . ucfirst($status) . "</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='empty-state'><i class='fas fa-calendar-times'></i><br>No schedules found yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
