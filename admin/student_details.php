<?php
include '../includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #0f3460;" class="fw-bold"><i class="fas fa-users"></i> Registered Students</h2>
        <a href="../welcome_splash.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-white" style="background-color: #0f3460;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Phone</th>
                            <th>Vehicle Class</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM registration ORDER BY `index` DESC";
                        $result = mysqli_query($con, $sql);

                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $status_badge = $row['status'] == 'approved' ? 'bg-success' : 'bg-warning text-dark';
                                echo "<tr>";
                                echo "<td>" . $row['index'] . "</td>";
                                echo "<td class='fw-bold'>" . $row['name'] . "</td>";
                                echo "<td>" . $row['nic'] . "</td>";
                                echo "<td>" . $row['phone_number'] . "</td>";
                                echo "<td>" . $row['classofvehicle'] . "</td>";
                                echo "<td><span class='badge $status_badge'>" . ucfirst($row['status']) . "</span></td>";
                                echo "<td>
                                        <a href='view_student.php?id=" . $row['index'] . "' class='btn btn-sm text-white' style='background-color: #0f3460;'>
                                            <i class='fas fa-eye'></i> View Profile
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted py-4'>No students found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
