<?php
include 'includes/connection.php';

echo "<h1>Fixing Missing 'status' Column...</h1>";

function addColumnIfNotExists($con, $table, $column, $definition) {
    echo "Checking `$table` for `$column`...<br>";
    $check = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if(mysqli_num_rows($check) == 0){
        echo "Column missing. Adding...<br>";
        $alter = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if(mysqli_query($con, $alter)){
            echo "<p style='color:green; font-weight:bold;'>✔ SUCCESS: `$column` added to `$table`.</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>✘ ERROR: Could not add `$column`. MySQL said: " . mysqli_error($con) . "</p>";
        }
    } else {
        echo "<p style='color:blue;'>ℹ Column `$column` already exists.</p>";
    }
}

// Fix 'onlineapplication' table
addColumnIfNotExists($con, 'onlineapplication', 'status', "VARCHAR(20) DEFAULT 'pending'");

// Fix 'registration' table - just in case
addColumnIfNotExists($con, 'registration', 'status', "VARCHAR(20) DEFAULT 'approved'");


echo "<br><hr>";
echo "<h3>Verification (onlineapplication):</h3>";
$result = mysqli_query($con, "DESCRIBE `onlineapplication`");
echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
while($row = mysqli_fetch_assoc($result)){
    $color = ($row['Field'] == 'status') ? 'lightgreen' : 'white';
    echo "<tr style='background-color:$color'><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
}
echo "</table>";

echo "<br><br><a href='onlineapplication.php' style='font-size:20px; font-weight:bold;'>Try Registering Again</a>";
?>
