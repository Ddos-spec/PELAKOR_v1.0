<?php
session_start();
include '../connect-db.php';
include '../functions/status-handler.php';

header('Content-Type: application/json');

if(!isset($_SESSION['login-agen']) && !isset($_SESSION['login-admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$idCucian = $_POST['id_cucian'];
$status = $_POST['status'];
$idAgen = isset($_SESSION['agen']) ? $_SESSION['agen'] : 0;

if(updateStatus($idCucian, $status, $idAgen)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mengubah status']);
}
