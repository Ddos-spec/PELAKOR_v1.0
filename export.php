<?php
session_start();
include 'connect-db.php';
include 'functions/laporan.php';

// Cek akses
if(!isset($_SESSION['login-admin']) && !isset($_SESSION['login-agen'])) {
    header('Location: login.php');
    exit;
}

$type = $_GET['type'];
$idAgen = isset($_SESSION['agen']) ? $_SESSION['agen'] : null;

$data = generateLaporan($idAgen);

if($type == 'pdf') {
    exportPDF($data);
} else {
    exportExcel($data);
}
