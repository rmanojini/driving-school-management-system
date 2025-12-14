<?php
// Note: In a real app, you must check if the logged-in user is an ADMIN.
// session_start();
// if($_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit(); }

include '../includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Approvals - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body style="background-color: #f4f7f6;">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--karuneelam-navy);">Pending Student Approvals</h2>
        <a href="../dashboard.html" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Index</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>NIC</th>
                        <th>Vehicle Class</th>
                        <th>Date Applied</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM onlineapplication"; 
                    $result = mysqli_query($con, $sql);

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            // Determine Primary Key (app_id or id or index)
                            $id = $row['app_id'] ?? $row['id'] ?? $row['index']; 
                            
                            echo "<tr>";
                            echo "<td>" . $id . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['nic'] . "</td>";
                            echo "<td>" . $row['classofvehicle'] . "</td>";
                            echo "<td>" . $row['reg_date'] . "</td>";
                            echo "<td>
                                    <a href='approve_user.php?id=" . $id . "' class='btn btn-success btn-sm'>Approve</a>
                                    <a href='reject_user.php?id=" . $id . "' class='btn btn-danger btn-sm'>Reject</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No pending approvals found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
