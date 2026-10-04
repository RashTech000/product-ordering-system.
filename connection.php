<?php
$username = "root";
$hostname = "localhost";
$password = "";
$database = "shop_db";

$connection = mysqli_connect($hostname, $username, $password, $database)
    or die("fail to connect");

mysqli_report(MYSQLI_REPORT_OFF);
?>
