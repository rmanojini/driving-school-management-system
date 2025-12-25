<?php
include 'includes/connection.php';

echo "<h1>🔍 Student Login Diagnostic Tool</h1>";
echo "<p>Checking why login is not working...</p><hr>";

// Check if there are any students in onlineapplication
echo "<h2>1. Students in onlineapplication (Pending Approval)</h2>";
$result = mysqli_query($con, "SELECT name, nic, username, email, status FROM onlineapplication ORDER BY name LIMIT 10");
if(mysqli_num_rows($result) > 0){
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Name</th><th>NIC</th><th>Username</th><th>Email</th><th>Status</th></tr>";
    while($row = mysqli_fetch_assoc($result)){
        $status_color = ($row['status'] == 'pending') ? 'orange' : 'green';
        echo "<tr><td>{$row['name']}</td><td>{$row['nic']}</td><td><strong>{$row['username']}</strong></td><td>{$row['email']}</td><td style='color:$status_color;'>{$row['status']}</td></tr>";
    }
    echo "</table>";
    echo "<p style='color:orange; font-weight:bold;'>⚠ These students are PENDING approval. They CANNOT login yet!</p>";
} else {
    echo "<p style='color:red;'>No students found in onlineapplication table.</p>";
}

echo "<br><hr>";

// Check if there are any approved students in registration
echo "<h2>2. Students in registration (Approved - Can Login)</h2>";
$result = mysqli_query($con, "SELECT name, nic, username, email, status FROM registration ORDER BY name LIMIT 10");
if(mysqli_num_rows($result) > 0){
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Name</th><th>NIC</th><th>Username</th><th>Email</th><th>Status</th></tr>";
    while($row = mysqli_fetch_assoc($result)){
        $status_color = ($row['status'] == 'approved') ? 'green' : 'red';
        echo "<tr><td>{$row['name']}</td><td>{$row['nic']}</td><td><strong>{$row['username']}</strong></td><td>{$row['email']}</td><td style='color:$status_color;'>{$row['status']}</td></tr>";
    }
    echo "</table>";
    echo "<p style='color:green; font-weight:bold;'>✔ These students CAN login!</p>";
} else {
    echo "<p style='color:red;'>No approved students found in registration table.</p>";
}

echo "<br><hr>";

// Solution
echo "<h2>💡 Solution</h2>";
echo "<div style='background:lightyellow; padding:15px; border-left:5px solid orange;'>";
echo "<h3>Why Login is Not Working:</h3>";
echo "<ol>";
echo "<li><strong>Registration Flow:</strong> When students register, data goes to <code>onlineapplication</code> table with status='pending'</li>";
echo "<li><strong>Admin Approval:</strong> Admin must approve the student, which moves data to <code>registration</code> table</li>";
echo "<li><strong>Login:</strong> Login checks <code>registration</code> table only (approved students)</li>";
echo "</ol>";

echo "<h3>How to Fix:</h3>";
echo "<p><strong>Option 1: Admin Approval (Recommended)</strong></p>";
echo "<ul>";
echo "<li>Go to: <a href='admin/pending_approvals.php' style='font-weight:bold; color:blue;'>Admin Pending Approvals</a></li>";
echo "<li>Approve the student</li>";
echo "<li>Then student can login</li>";
echo "</ul>";

echo "<p><strong>Option 2: Auto-Approve for Testing</strong></p>";
echo "<form method='POST'>";
echo "<button type='submit' name='auto_approve_all' style='padding:10px 20px; background:orange; color:white; border:none; cursor:pointer; font-size:16px;'>⚡ AUTO-APPROVE ALL PENDING STUDENTS (Testing Only)</button>";
echo "</form>";
echo "</div>";

// Auto-approve functionality
if(isset($_POST['auto_approve_all'])){
    echo "<br><h3>Auto-Approving Students...</h3>";
    
    // Get all pending students
    $result = mysqli_query($con, "SELECT * FROM onlineapplication WHERE status='pending' OR status IS NULL");
    $count = 0;
    
    while($student = mysqli_fetch_assoc($result)){
        // Check if already exists in registration
        $check = mysqli_query($con, "SELECT * FROM registration WHERE nic='{$student['nic']}'");
        
        if(mysqli_num_rows($check) == 0){
            // Insert into registration
            $sql = "INSERT INTO registration (name, dob, age, nic, username, gender, address, phone_number, email, password, status, reg_date, classofvehicle) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved', ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssssss", 
                $student['name'], 
                $student['dob'], 
                $student['age'], 
                $student['nic'], 
                $student['username'], 
                $student['gender'], 
                $student['address'], 
                $student['phone_number'], 
                $student['email'], 
                $student['password'], 
                $student['reg_date'], 
                $student['classofvehicle']
            );
            
            if(mysqli_stmt_execute($stmt)){
                echo "<p style='color:green;'>✔ Approved: {$student['name']} (Username: {$student['username']})</p>";
                $count++;
            }
        }
    }
    
    echo "<p style='color:green; font-weight:bold;'>✅ Total approved: $count students</p>";
    echo "<p><a href='student_login.php' style='font-size:18px; padding:10px 20px; background:green; color:white; text-decoration:none;'>➜ Try Login Now</a></p>";
    echo "<meta http-equiv='refresh' content='3'>";
}

echo "<br><a href='student_login.php'>Go to Student Login</a> | ";
echo "<a href='onlineapplication.php'>Register New Student</a>";
?>
