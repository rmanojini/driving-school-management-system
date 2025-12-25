<?php
try {
    $con = mysqli_connect("localhost", "root", "", "dms");
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