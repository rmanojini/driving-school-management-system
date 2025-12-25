<?php
include 'includes/connection.php';

echo "<h1>Fixing Registration Table...</h1>";

// 1. Check if column exists
$check = mysqli_query($con, "SHOW COLUMNS FROM `registration` LIKE 'username'");
if(mysqli_num_rows($check) == 0){
    echo "<p>Column 'username' missing. Attempting to add...</p>";
    
    // 2. Add Column
    $sql = "ALTER TABLE `registration` ADD COLUMN `username` VARCHAR(50) NULL AFTER `name`";
    if(mysqli_query($con, $sql)){
        echo "<p style='color:green; font-weight:bold;'>✔ SUCCESS: 'username' column added to 'registration' table.</p>";
        
        // 3. Backfill with NIC
        $update = "UPDATE `registration` SET `username` = `nic` WHERE `username` IS NULL";
        if(mysqli_query($con, $update)){
             echo "<p style='color:green;'>✔ Data Backfilled: Existing users set to use NIC as username.</p>";
        } else {
             echo "<p style='color:orange;'>⚠ Warning: Could not backfill data: " . mysqli_error($con) . "</p>";
        }
        
    } else {
        echo "<p style='color:red; font-weight:bold;'>✘ CRITICAL ERROR: Could not add column. MySQL said: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color:blue;'>ℹ Column 'username' already exists in 'registration' table.</p>";
}

echo "<br><hr>";
echo "<h3>Verification:</h3>";
$result = mysqli_query($con, "DESCRIBE `registration`");
echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
while($row = mysqli_fetch_assoc($result)){
    $color = ($row['Field'] == 'username') ? 'lightgreen' : 'white';
    echo "<tr style='background-color:$color'><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
}
echo "</table>";

echo "<br><br><a href='onlineapplication.php' style='font-size:20px; font-weight:bold;'>Go back and try again</a>";
?>
