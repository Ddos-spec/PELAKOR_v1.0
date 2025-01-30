<?php

$username = "root";
$passwordDB = "";
$server = "localhost";
$db_name = "laundryku";

$connect = mysqli_connect($server, $username, $passwordDB, $db_name);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}


?>