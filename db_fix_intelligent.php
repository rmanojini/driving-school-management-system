<?php
include 'includes/connection.php';

echo "<h1>Database Fixer Tool</h1>";

// 1. Check/Create Table
$sql = "CREATE TABLE IF NOT EXISTS `onlineapplication` (
    `app_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `nic` VARCHAR(20) NOT NULL UNIQUE
)";
if(mysqli_query($con, $sql)){
    echo "<p style='color:green'>✔ Table `onlineapplication` checked/created.</p>";
} else {
    echo "<p style='color:red'>✘ Error checking table: " . mysqli_error($con) . "</p>";
}

// 2. Define Columns to Check/Add
$columns = [
    'password' => "VARCHAR(255) NOT NULL DEFAULT ''",
    'status' => "VARCHAR(20) DEFAULT 'pending'",
    'classofvehicle' => "VARCHAR(50) NOT NULL DEFAULT ''",
    'doc_nic' => "VARCHAR(255) DEFAULT NULL",
    'doc_address' => "VARCHAR(255) DEFAULT NULL",
    'dob' => "DATE NULL",
    'age' => "INT(3) NULL",
    'gender' => "VARCHAR(10) NULL",
    'address' => "TEXT NULL",
    'phone_number' => "VARCHAR(15) NULL",
    'email' => "VARCHAR(100) NULL",
    'reg_date' => "DATE NULL"
];

foreach ($columns as $column => $definition) {
    // Check if column exists
    $check = mysqli_query($con, "SHOW COLUMNS FROM `onlineapplication` LIKE '$column'");
    if(mysqli_num_rows($check) == 0){
        // Column missing, ADD IT
        $alter = "ALTER TABLE `onlineapplication` ADD COLUMN `$column` $definition";
        if(mysqli_query($con, $alter)){
            echo "<p style='color:green'>✔ Column `$column` added successfully.</p>";
        } else {
            echo "<p style='color:red'>✘ Error adding `$column`: " . mysqli_error($con) . "</p>";
        }
    } else {
        echo "<p style='color:blue'>ℹ Column `$column` already exists. Skipped.</p>";
    }
}

    }
}

echo "<h3>Current Table Structure:</h3><pre>";
$result = mysqli_query($con, "DESCRIBE `onlineapplication`");
while($row = mysqli_fetch_assoc($result)){
    print_r($row);
}
echo "</pre>";

echo "<h3>Database Fix Complete. Please check the 'Field' names above. One of them is your Primary Key (likely 'id' or 'index').</h3>";
echo "<a href='index.html'>Go to Home</a>";
?>
