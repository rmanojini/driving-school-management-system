<?php
include 'includes/connection.php';

echo "<h1>Database Column Checker</h1>";
echo "<p>Checking if 'username' column exists in tables...</p><hr>";

// Check onlineapplication table
echo "<h2>Table: onlineapplication</h2>";
$result = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication`");
$found_in_online = false;

if($result){
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>#</th><th>Column Name</th><th>Type</th></tr>";
    $i = 1;
    while($row = mysqli_fetch_assoc($result)){
        $highlight = ($row['Field'] == 'username') ? 'background-color:lightgreen; font-weight:bold;' : '';
        if($row['Field'] == 'username') $found_in_online = true;
        echo "<tr style='$highlight'><td>$i</td><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
        $i++;
    }
    echo "</table>";
    
    if($found_in_online){
        echo "<p style='color:green; font-weight:bold;'>✔ 'username' column EXISTS in onlineapplication</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>✘ 'username' column MISSING in onlineapplication</p>";
        echo "<p><strong>FIX:</strong> Run this SQL:</p>";
        echo "<pre>ALTER TABLE `onlineapplication` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`;</pre>";
    }
} else {
    echo "<p style='color:red;'>ERROR: " . mysqli_error($con) . "</p>";
}

echo "<br><hr>";

// Check registration table
echo "<h2>Table: registration</h2>";
$result = mysqli_query($con, "SHOW COLUMNS FROM `registration`");
$found_in_reg = false;

if($result){
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>#</th><th>Column Name</th><th>Type</th></tr>";
    $i = 1;
    while($row = mysqli_fetch_assoc($result)){
        $highlight = ($row['Field'] == 'username') ? 'background-color:lightgreen; font-weight:bold;' : '';
        if($row['Field'] == 'username') $found_in_reg = true;
        echo "<tr style='$highlight'><td>$i</td><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
        $i++;
    }
    echo "</table>";
    
    if($found_in_reg){
        echo "<p style='color:green; font-weight:bold;'>✔ 'username' column EXISTS in registration</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>✘ 'username' column MISSING in registration</p>";
        echo "<p><strong>FIX:</strong> Run this SQL:</p>";
        echo "<pre>ALTER TABLE `registration` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`;</pre>";
    }
} else {
    echo "<p style='color:red;'>ERROR: " . mysqli_error($con) . "</p>";
}

echo "<br><hr>";

// Auto-fix option
if(!$found_in_online || !$found_in_reg){
    echo "<h2 style='color:red;'>⚠ AUTO-FIX AVAILABLE</h2>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='autofix' style='padding:15px 30px; font-size:18px; background:red; color:white; border:none; cursor:pointer;'>🔧 CLICK HERE TO AUTO-FIX NOW</button>";
    echo "</form>";
}

if(isset($_POST['autofix'])){
    echo "<h2>Running Auto-Fix...</h2>";
    
    if(!$found_in_online){
        $sql = "ALTER TABLE `onlineapplication` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`";
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green;'>✔ Added username to onlineapplication</p>";
            mysqli_query($con, "UPDATE `onlineapplication` SET `username` = `nic` WHERE `username` IS NULL");
        } else {
            echo "<p style='color:red;'>✘ Failed: " . mysqli_error($con) . "</p>";
        }
    }
    
    if(!$found_in_reg){
        $sql = "ALTER TABLE `registration` ADD COLUMN `username` VARCHAR(50) NULL AFTER `email`";
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green;'>✔ Added username to registration</p>";
            mysqli_query($con, "UPDATE `registration` SET `username` = `nic` WHERE `username` IS NULL");
        } else {
            echo "<p style='color:red;'>✘ Failed: " . mysqli_error($con) . "</p>";
        }
    }
    
    echo "<p><strong>Refresh this page to verify!</strong></p>";
    echo "<meta http-equiv='refresh' content='2'>";
}

echo "<br><a href='onlineapplication.php'>Go to Registration Form</a>";
?>
