<?php
include 'connect-db.php';

header('Content-Type: application/json');

if (!isset($_GET['id_agen'])) {
    error_log("ID Agen tidak ditemukan dalam request");
    echo json_encode(['error' => 'ID Agen tidak ditemukan']);
    exit;
}

$id_agen = mysqli_real_escape_string($connect, $_GET['id_agen']);
error_log("Mencoba mengambil data untuk id_agen: " . $id_agen);

$query = mysqli_query($connect, "SELECT * FROM harga_satuan WHERE id_agen = '$id_agen'");

if (!$query) {
    error_log("Error query: " . mysqli_error($connect));
    echo json_encode(['error' => mysqli_error($connect)]);
    exit;
}

$items = [];
while($row = mysqli_fetch_assoc($query)) {
    $items[] = $row;
}

error_log("Data yang ditemukan: " . json_encode($items));
echo json_encode($items);