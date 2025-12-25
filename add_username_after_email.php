<?php
include 'includes/connection.php';

echo "<h1>Adding Username Column After Email</h1>";

// Check if username column exists
$check = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication` LIKE 'username'");

if(mysqli_num_rows($check) == 0){
    // Column doesn't exist, add it after email
    echo "<p>Adding 'username' column after 'email'...</p>";
    $sql = "ALTER TABLE `onlineapplication` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`";
    
    if(mysqli_query($con, $sql)){
        echo "<p style='color:green; font-weight:bold;'>✔ SUCCESS: 'username' column added after 'email'.</p>";
        
        // Backfill with NIC
        $update = "UPDATE `onlineapplication` SET `username` = `nic` WHERE `username` IS NULL";
        if(mysqli_query($con, $update)){
             echo "<p style='color:green;'>✔ Backfilled: Existing records set username = nic</p>";
        }
    } else {
        echo "<p style='color:red; font-weight:bold;'>✘ ERROR: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p style='color:blue;'>ℹ Column 'username' already exists.</p>";
    
    // Check if it's in the right position
    $result = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication`");
    $prev_field = '';
    $found_after_email = false;
    
    while($row = mysqli_fetch_assoc($result)){
        if($row['Field'] == 'username' && $prev_field == 'email'){
            $found_after_email = true;
            echo "<p style='color:green;'>✔ Username is already positioned after email.</p>";
            break;
        }
        $prev_field = $row['Field'];
    }
    
    if(!$found_after_email){
        echo "<p style='color:orange;'>⚠ Username exists but NOT after email. Repositioning...</p>";
        // Drop and re-add in correct position
        mysqli_query($con, "ALTER TABLE `onlineapplication` DROP COLUMN `username`");
        $sql = "ALTER TABLE `onlineapplication` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`";
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green;'>✔ Repositioned username after email.</p>";
            // Backfill again
            mysqli_query($con, "UPDATE `onlineapplication` SET `username` = `nic` WHERE `username` IS NULL");
        }
    }
}

echo "<br><hr><h3>Current Column Order:</h3>";
$result = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication`");
echo "<ol>";
while($row = mysqli_fetch_assoc($result)){
    $highlight = ($row['Field'] == 'username' || $row['Field'] == 'email') ? 'background-color:yellow;' : '';
    echo "<li style='$highlight'><strong>{$row['Field']}</strong> ({$row['Type']})</li>";
}
echo "</ol>";

echo "<br><a href='onlineapplication.php' style='font-size:18px; font-weight:bold;'>✓ Test Registration</a>";
?>
