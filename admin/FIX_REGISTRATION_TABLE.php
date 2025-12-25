<?php
include '../includes/connection.php';

echo "<h1>🔧 Fix Registration Table - Add Missing Columns</h1>";
echo "<p>Adding missing columns to registration table for admin approval...</p><hr>";

// Define all required columns for registration table
$required_columns = [
    'name' => "VARCHAR(255) NOT NULL",
    'dob' => "DATE NULL",
    'age' => "INT(3) NULL",
    'nic' => "VARCHAR(20) NULL",
    'username' => "VARCHAR(50) NULL",
    'gender' => "VARCHAR(10) NULL",
    'address' => "TEXT NULL",
    'phone_number' => "VARCHAR(15) NULL",
    'email' => "VARCHAR(100) NULL",
    'password' => "VARCHAR(255) NULL",
    'status' => "VARCHAR(20) DEFAULT 'approved'",
    'reg_date' => "DATE NULL",
    'classofvehicle' => "VARCHAR(50) NULL",
    'medical_number' => "VARCHAR(100) NULL",
    'medical_date' => "DATE NULL",
    'doc_nic' => "VARCHAR(255) DEFAULT NULL",
    'doc_address' => "VARCHAR(255) DEFAULT NULL"
];

echo "<h2>Checking registration table...</h2>";

// Get existing columns
$result = mysqli_query($con, "SHOW COLUMNS FROM `registration`");
$existing_columns = [];
while($row = mysqli_fetch_assoc($result)){
    $existing_columns[] = $row['Field'];
}

echo "<p>Existing columns: " . implode(', ', $existing_columns) . "</p>";

// Find missing columns
$missing = [];
foreach($required_columns as $col => $def){
    if(!in_array($col, $existing_columns)){
        $missing[] = $col;
    }
}

if(count($missing) > 0){
    echo "<p style='color:red; font-weight:bold;'>Missing columns: " . implode(', ', $missing) . "</p>";
    echo "<h3>Adding missing columns...</h3>";
    
    foreach($missing as $col){
        $sql = "ALTER TABLE `registration` ADD COLUMN `$col` {$required_columns[$col]}";
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green;'>✔ Added: $col</p>";
        } else {
            echo "<p style='color:red;'>✘ Failed to add $col: " . mysqli_error($con) . "</p>";
        }
    }
    
    // Backfill username if needed
    mysqli_query($con, "UPDATE `registration` SET `username` = `nic` WHERE `username` IS NULL OR `username` = ''");
    
} else {
    echo "<p style='color:green; font-weight:bold;'>✔ All required columns exist!</p>";
}

echo "<br><hr><h2>Final Column List (registration table):</h2>";
$result = mysqli_query($con, "SHOW COLUMNS FROM `registration`");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>#</th><th>Column</th><th>Type</th><th>Status</th></tr>";
$i = 1;
while($row = mysqli_fetch_assoc($result)){
    $status = in_array($row['Field'], array_keys($required_columns)) ? '✔ Required' : 'Extra';
    $color = in_array($row['Field'], array_keys($required_columns)) ? 'lightgreen' : 'lightyellow';
    echo "<tr style='background-color:$color;'><td>$i</td><td><strong>{$row['Field']}</strong></td><td>{$row['Type']}</td><td>$status</td></tr>";
    $i++;
}
echo "</table>";

echo "<br><hr>";
echo "<h2 style='color:green;'>✅ Registration Table Fix Complete!</h2>";
echo "<p><a href='../diagnose_login.php' style='font-size:20px; padding:10px 20px; background:orange; color:white; text-decoration:none; border-radius:5px;'>➜ Go Back & Auto-Approve Students</a></p>";
?>
