<?php
include 'includes/connection.php';
$result = mysqli_query($con, "SHOW COLUMNS FROM admin");
while($row = mysqli_fetch_array($result)){
    echo $row['Field'] . "\n";
}
?>
