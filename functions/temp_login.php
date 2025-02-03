<?php
session_start();

// Simulate Admin login
$_SESSION["login-admin"] = true;
$_SESSION["admin"] = 1; // Assuming admin ID is 1

// Redirect to transaksi.php
header("Location: ../transaksi.php");
exit();
?>
