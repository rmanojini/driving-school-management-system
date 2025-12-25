<?php
include 'includes/connection.php';

echo "<h1>Adding All Missing Columns to onlineapplication Table</h1>";
echo "<p>Note: Skipping 'app_id' as requested (thavrntha = except)</p>";

function addColumnIfNotExists($con, $table, $column, $definition) {
    $check = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if(mysqli_num_rows($check) == 0){
        $alter = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if(mysqli_query($con, $alter)){
            echo "<p style='color:green;'>✔ Added: `$column`</p>";
            return true;
        } else {
            echo "<p style='color:red;'>✘ Failed to add `$column`: " . mysqli_error($con) . "</p>";
            return false;
        }
    } else {
        echo "<p style='color:blue;'>ℹ Already exists: `$column`</p>";
        return true;
    }
}

// Add all columns that might be missing (based on create_online_app_table.sql)
$columns = [
    'username' => "VARCHAR(50) NULL AFTER name",
    'dob' => "DATE NULL",
    'age' => "INT(3) NULL",
    'gender' => "VARCHAR(10) NULL",
    'address' => "TEXT NULL",
    'phone_number' => "VARCHAR(15) NULL",
    'email' => "VARCHAR(100) NULL",
    'password' => "VARCHAR(255) NULL",
    'classofvehicle' => "VARCHAR(50) NULL",
    'status' => "VARCHAR(20) DEFAULT 'pending'",
    'reg_date' => "DATE NULL",
    'doc_nic' => "VARCHAR(255) DEFAULT NULL",
    'doc_address' => "VARCHAR(255) DEFAULT NULL"
];

echo "<h3>Processing columns...</h3>";
foreach($columns as $col => $def) {
    addColumnIfNotExists($con, 'onlineapplication', $col, $def);
}

// Backfill username if needed
echo "<br><h3>Backfilling Data...</h3>";
$backfill = "UPDATE onlineapplication SET username = nic WHERE username IS NULL OR username = ''";
if(mysqli_query($con, $backfill)) {
    echo "<p style='color:green;'>✔ Backfilled usernames (set to NIC)</p>";
} else {
    echo "<p style='color:orange;'>⚠ Backfill skipped or failed: " . mysqli_error($con) . "</p>";
}

echo "<br><hr><h3>Final Table Structure:</h3>";
$result = mysqli_query($con, "DESCRIBE `onlineapplication`");
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
while($row = mysqli_fetch_assoc($result)){
    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Default']}</td></tr>";
}
echo "</table>";

echo "<br><br><a href='onlineapplication.php' style='font-size:18px; font-weight:bold; color:blue;'>✓ Go to Registration Form</a>";
echo " | ";
echo "<a href='student_login.php' style='font-size:18px; font-weight:bold; color:green;'>✓ Go to Login</a>";
?>
