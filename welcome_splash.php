<?php
include 'includes/connection.php';

// Count Pending Applications
$sql = "SELECT COUNT(*) as count FROM onlineapplication";
$result = mysqli_query($con, $sql);
$count = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
        }
        .welcome-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .welcome-header h1 { font-size: 2.5rem; color: #0f3460; margin-bottom: 0.5rem; }
        .welcome-header p { font-size: 1.1rem; color: #666; }
        
        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 25px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #0f3460;
        }
        .card h3 { margin: 10px 0; font-size: 1.2rem; }
        .card .count { font-size: 2.5rem; font-weight: 700; color: #e94560; }
        
        .alert-card {
            background: linear-gradient(135deg, #e94560 0%, #d6304d 100%);
            color: white;
        }
        .alert-card .card-icon { color: white; }
        .alert-card .count { color: white; }
    </style>
</head>
<body>
    <div class="welcome-header">
        <h1>Welcome to Alex Driving School</h1>
        <p>Overview of current status</p>
    </div>

    <div class="dashboard-cards">
        
        <!-- Pending Approvals Alert -->
        <?php if($count > 0): ?>
        <a href="admin/pending_approvals.php" class="card alert-card">
            <div class="card-icon"><i class="fas fa-bell"></i></div>
            <h3>Pending Applications</h3>
            <div class="count"><?php echo $count; ?></div>
            <p>Action Required</p>
        </a>
        <?php else: ?>
        <a href="admin/pending_approvals.php" class="card">
            <div class="card-icon"><i class="fas fa-check-circle"></i></div>
            <h3>Pending Applications</h3>
            <div class="count">0</div>
            <p>All caught up!</p>
        </a>
        <?php endif; ?>

        <!-- Quick Links (Static for now, can be made dynamic) -->
        <a href="admin/student_details.php" class="card">
            <div class="card-icon"><i class="fas fa-users"></i></div>
            <h3>Total Students</h3>
            <div class="count">-</div> <!-- Placeholder, query needed for real count -->
        </a>

        <a href="registration.php" class="card">
            <div class="card-icon"><i class="fas fa-user-plus"></i></div>
            <h3>New Registration</h3>
            <p>Register a walk-in student</p>
        </a>

    </div>
</body>
</html>
