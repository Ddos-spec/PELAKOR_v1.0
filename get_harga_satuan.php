<?php
include 'connect-db.php';

$id_agen = $_GET['id_agen'];
$jenis = $_GET['jenis'];

$query = mysqli_query($connect, "SELECT * FROM harga_satuan WHERE id_agen = '$id_agen' AND jenis = '$jenis'");
$items = array();

while($row = mysqli_fetch_assoc($query)) {
    $items[] = $row;
}

header('Content-Type: application/json');
echo json_encode($items);
?>