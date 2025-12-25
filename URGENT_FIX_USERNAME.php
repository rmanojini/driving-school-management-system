<?php
include 'includes/connection.php';

echo "<h1 style='color:red;'>🔧 URGENT FIX: Adding Username Column</h1>";
echo "<p><strong>Error:</strong> Unknown column 'username' in 'field list'</p>";
echo "<hr>";

function addUsernameColumn($con, $table) {
    echo "<h3>Fixing table: <code>$table</code></h3>";
    
    // Check if username exists
    $check = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE 'username'");
    
    if(mysqli_num_rows($check) == 0){
        echo "<p style='color:orange;'>⚠ Column 'username' is MISSING. Adding now...</p>";
        
        // Add username column after email
        $sql = "ALTER TABLE `$table` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`";
        
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green; font-weight:bold;'>✔ SUCCESS: 'username' column added!</p>";
            
            // Backfill with NIC
            $update = "UPDATE `$table` SET `username` = `nic` WHERE `username` IS NULL OR `username` = ''";
            if(mysqli_query($con, $update)){
                echo "<p style='color:green;'>✔ Backfilled existing records (username = nic)</p>";
            }
            
            return true;
        } else {
            echo "<p style='color:red; font-weight:bold;'>✘ FAILED: " . mysqli_error($con) . "</p>";
            return false;
        }
    } else {
        echo "<p style='color:blue;'>✔ Column 'username' already exists.</p>";
        return true;
    }
}

// Fix both tables
$success1 = addUsernameColumn($con, 'onlineapplication');
echo "<br>";
$success2 = addUsernameColumn($con, 'registration');

echo "<br><hr>";

if($success1 && $success2){
    echo "<h2 style='color:green;'>✅ ALL FIXED! Database is ready.</h2>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ol>";
    echo "<li>Go to <a href='onlineapplication.php' style='font-size:18px; font-weight:bold;'>Registration Form</a></li>";
    echo "<li>Fill the form and create a username</li>";
    echo "<li>Login using <a href='student_login.php' style='font-size:18px; font-weight:bold;'>Student Login</a></li>";
    echo "</ol>";
} else {
    echo "<h2 style='color:red;'>❌ Some errors occurred. Please check above.</h2>";
}

echo "<br><hr><h3>Current Table Structures:</h3>";

echo "<h4>onlineapplication table:</h4>";
$result = mysqli_query($con, "DESCRIBE `onlineapplication`");
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
while($row = mysqli_fetch_assoc($result)){
    $highlight = ($row['Field'] == 'username') ? 'background-color:lightgreen;' : '';
    echo "<tr style='$highlight'><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
}
echo "</table><br>";

echo "<h4>registration table:</h4>";
$result = mysqli_query($con, "DESCRIBE `registration`");
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
while($row = mysqli_fetch_assoc($result)){
    $highlight = ($row['Field'] == 'username') ? 'background-color:lightgreen;' : '';
    echo "<tr style='$highlight'><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
}
echo "</table>";
?>
