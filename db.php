<?php
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "stoninopwdsysdb";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
?>