<?php

$username = "root";
$passwordDB = "";
$server = "localhost";
$db_name = "laundryku";

$connect = mysqli_connect($server, $username, $passwordDB, $db_name);


$connect = mysqli_connect("localhost", "root", "", "laundryku");

if (!$connect) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Connection failed: " . mysqli_connect_error());
}
?>