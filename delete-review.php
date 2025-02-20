<?php
session_start();
include 'connect-db.php';

// Only allow if logged in as agent
if (!isset($_SESSION['agen'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: login.php");
        exit;
    }
    
    // Validate and sanitize input
    if (!isset($_POST['id_transaksi']) || !is_numeric($_POST['id_transaksi'])) {
        header("Location: login.php");
        exit;
    }
    
    $id_transaksi = intval($_POST['id_transaksi']);
    
    // Verify that this transaction belongs to the agent using prepared statement
    $query = "SELECT id_agen FROM transaksi WHERE id_transaksi = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $transaksi = mysqli_fetch_assoc($result);
    
    if ($transaksi && $transaksi['id_agen'] == $_SESSION['agen']) {
        // Delete the review using prepared statement
        $deleteQuery = "UPDATE transaksi SET komentar = NULL, rating = 0 WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($connect, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
        mysqli_stmt_execute($stmt);
        
        // Redirect back to agent profile
        header("Location: detail-agen.php?id=" . $_SESSION['agen']);
        exit;
    } else {
        // Unauthorized attempt
        header("Location: login.php");
        exit;
    }
} else {
    // Invalid request
    header("Location: login.php");
    exit;
}
?>
