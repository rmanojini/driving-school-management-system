<?php
include 'includes/connection.php';

echo "<h1>🎯 ULTIMATE DATABASE FIX - Both Tables</h1>";
echo "<p>Adding ALL missing columns to BOTH onlineapplication AND registration tables...</p><hr>";

// All required columns
$all_columns = [
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
    'status' => "VARCHAR(20) DEFAULT 'pending'",
    'reg_date' => "DATE NULL",
    'classofvehicle' => "VARCHAR(50) NULL",
    'medical_number' => "VARCHAR(100) NULL",
    'medical_date' => "DATE NULL",
    'doc_nic' => "VARCHAR(255) DEFAULT NULL",
    'doc_address' => "VARCHAR(255) DEFAULT NULL"
];

function fixTable($con, $tableName, $columns) {
    echo "<h2>📋 Fixing Table: $tableName</h2>";
    
    // Get existing columns
    $result = mysqli_query($con, "SHOW COLUMNS FROM `$tableName`");
    $existing = [];
    while($row = mysqli_fetch_assoc($result)){
        $existing[] = $row['Field'];
    }
    
    // Find and add missing columns
    $added = 0;
    foreach($columns as $col => $def){
        if(!in_array($col, $existing)){
            $sql = "ALTER TABLE `$tableName` ADD COLUMN `$col` $def";
            if(mysqli_query($con, $sql)){
                echo "<p style='color:green;'>✔ Added: $col</p>";
                $added++;
            } else {
                echo "<p style='color:red;'>✘ Failed: $col - " . mysqli_error($con) . "</p>";
            }
        }
    }
    
    if($added == 0){
        echo "<p style='color:blue;'>ℹ All columns already exist</p>";
    } else {
        echo "<p style='color:green; font-weight:bold;'>✅ Added $added columns</p>";
    }
    
    // Backfill username
    mysqli_query($con, "UPDATE `$tableName` SET `username` = `nic` WHERE `username` IS NULL OR `username` = ''");
    
    return $added;
}

// Fix both tables
$total1 = fixTable($con, 'onlineapplication', $all_columns);
echo "<br>";
$total2 = fixTable($con, 'registration', $all_columns);

echo "<br><hr>";
echo "<h2 style='color:green;'>✅ COMPLETE! Total columns added: " . ($total1 + $total2) . "</h2>";

echo "<h3>Next Steps:</h3>";
echo "<ol style='font-size:16px;'>";
echo "<li>✅ Database is now ready</li>";
echo "<li>📝 <a href='onlineapplication.php' style='font-weight:bold;'>Register a new student</a></li>";
echo "<li>👨‍💼 <a href='diagnose_login.php' style='font-weight:bold;'>Auto-approve students</a></li>";
echo "<li>🔐 <a href='student_login.php' style='font-weight:bold;'>Login as student</a></li>";
echo "</ol>";

echo "<br><hr><h3>Table Structures:</h3>";

// Show both tables
foreach(['onlineapplication', 'registration'] as $table){
    echo "<h4>$table:</h4>";
    $result = mysqli_query($con, "SHOW COLUMNS FROM `$table`");
    echo "<table border='1' cellpadding='3' style='font-size:12px;'>";
    echo "<tr><th>#</th><th>Column</th><th>Type</th></tr>";
    $i = 1;
    while($row = mysqli_fetch_assoc($result)){
        $highlight = in_array($row['Field'], ['username', 'status', 'doc_nic']) ? 'background:lightgreen;' : '';
        echo "<tr style='$highlight'><td>$i</td><td><strong>{$row['Field']}</strong></td><td>{$row['Type']}</td></tr>";
        $i++;
    }
    echo "</table><br>";
}
?>
