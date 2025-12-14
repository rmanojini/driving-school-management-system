<?php
include 'includes/connection.php';

if ($con) {
    echo "<h3>Database Connected Successfully!</h3>";

    // 1. Create Admin Table
    $sql_create = "CREATE TABLE IF NOT EXISTS `admin` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL
    )";

    if (mysqli_query($con, $sql_create)) {
        echo "<p>✅ Admin table check/creation: <b>Success</b></p>";
    } else {
        echo "<p>❌ Admin table error: " . mysqli_error($con) . "</p>";
    }

    // 2. Insert Default Admin
    // Using simple INSERT IGNORE to avoid duplicate errors
    $sql_insert = "INSERT IGNORE INTO `admin` (`username`, `password`) VALUES ('admin', 'admin123')";

    if (mysqli_query($con, $sql_insert)) {
        echo "<p>✅ Default Admin User (admin / admin123): <b>Ensured</b></p>";
    } else {
        echo "<p>❌ User insert error: " . mysqli_error($con) . "</p>";
    }

    echo "<hr><h3>🎉 Setup Complete!</h3>";
    echo "<p>You can now <a href='login.php'>Login as Admin</a>.</p>";
    
} else {
    echo "<h3>❌ Database Connection Failed. check includes/connection.php</h3>";
}
?>
