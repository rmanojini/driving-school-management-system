<?php
include 'includes/connection.php';

echo "<h1>Applying Username Changes...</h1>";

function addColumnIfNotExists($con, $table, $column, $definition) {
    $check = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if(mysqli_num_rows($check) == 0){
        $alter = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if(mysqli_query($con, $alter)){
            echo "<p style='color:green'>✔ Column `$column` added to `$table`.</p>";
        } else {
            echo "<p style='color:red'>✘ Error adding `$column` to `$table`: " . mysqli_error($con) . "</p>";
        }
    } else {
        echo "<p style='color:blue'>ℹ Column `$column` already exists in `$table`.</p>";
    }
}

// 1. Add columns
addColumnIfNotExists($con, 'onlineapplication', 'username', "VARCHAR(50) AFTER name");
addColumnIfNotExists($con, 'registration', 'username', "VARCHAR(50) AFTER name");

// 2. Backfill Data
echo "<h2>Backfilling Data</h2>";

$updates = [
    "UPDATE onlineapplication SET username = nic WHERE (username IS NULL OR username = '') AND nic IS NOT NULL",
    "UPDATE registration SET username = nic WHERE (username IS NULL OR username = '') AND nic IS NOT NULL"
];

foreach($updates as $sql) {
    if(mysqli_query($con, $sql)){
        echo "<p style='color:green'>✔ Backfill executed: " . htmlspecialchars($sql) . "</p>";
    } else {
        echo "<p style='color:red'>✘ Error backfilling: " . mysqli_error($con) . "</p>";
    }
}

// 3. Add Unique Constraint (Attempt)
echo "<h2>Adding Constraints</h2>";
$tables = ['onlineapplication', 'registration'];
foreach($tables as $table) {
    // Check if index exists first to avoid error
    $idx_check = mysqli_query($con, "SHOW INDEX FROM `$table` WHERE Key_name = 'username'");
    if(mysqli_num_rows($idx_check) == 0) {
        $sql = "ALTER TABLE `$table` ADD UNIQUE (username)";
        if(mysqli_query($con, $sql)){
            echo "<p style='color:green'>✔ Unique constraint added to `$table`.username</p>";
        } else {
            echo "<p style='color:orange'>⚠ Could not add unique constraint to `$table`: " . mysqli_error($con) . " (Check for duplicates)</p>";
        }
    } else {
         echo "<p style='color:blue'>ℹ Unique constraint already exists on `$table`.username</p>";
    }
}

echo "<h3>Update Complete.</h3>";
?>
