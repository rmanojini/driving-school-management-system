<?php
$con = mysqli_connect("localhost", "root", "", "dms");
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

$result = mysqli_query($con, "SHOW COLUMNS FROM onlineapplication");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>
