<?php
include 'includes/connection.php';

echo "<h1>🔧 Complete Database Fix - All Missing Columns</h1>";
echo "<p>Adding ALL missing columns to onlineapplication table...</p><hr>";

// Define all required columns based on the INSERT statement in onlineapplication.php
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
    'reg_date' => "DATE NULL",
    'classofvehicle' => "VARCHAR(50) NULL",
    'doc_nic' => "VARCHAR(255) DEFAULT NULL",
    'doc_address' => "VARCHAR(255) DEFAULT NULL"
];

echo "<h2>Checking onlineapplication table...</h2>";

// Get existing columns
$result = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication`");
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
        $sql = "ALTER TABLE `onlineapplication` ADD COLUMN `$col` {$required_columns[$col]}";
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green;'>✔ Added: $col</p>";
        } else {
            echo "<p style='color:red;'>✘ Failed to add $col: " . mysqli_error($con) . "</p>";
        }
    }
    
    // Backfill username if needed
    mysqli_query($con, "UPDATE `onlineapplication` SET `username` = `nic` WHERE `username` IS NULL OR `username` = ''");
    
} else {
    echo "<p style='color:green; font-weight:bold;'>✔ All required columns exist!</p>";
}

echo "<br><hr><h2>Final Column List:</h2>";
$result = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication`");
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
echo "<h2 style='color:green;'>✅ Database Fix Complete!</h2>";
echo "<p><a href='onlineapplication.php' style='font-size:20px; padding:10px 20px; background:green; color:white; text-decoration:none; border-radius:5px;'>➜ Go to Registration Form</a></p>";
?>
