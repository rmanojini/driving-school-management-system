<?php
include_once __DIR__ . '/env_loader.php';
loadEnv(__DIR__ . '/../.env');

try {
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'dms';

    $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception $e) {
    // Catch connection errors like "target machine actively refused it"
    die("Server Not Connected......Check if MySQL is running. <br>Error details: " . $e->getMessage());
}

if(!$con)
    {
     echo "Server Not Connected......Check it";
     exit();
    }
?>