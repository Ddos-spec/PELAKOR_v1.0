<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Cek akses agen
cekAgen();
$idAgen = $_SESSION["agen"];

$id = $_GET['id'];

// Validasi kepemilikan harga satuan
$check = mysqli_query($connect, "SELECT id_agen FROM harga_satuan WHERE id_harga_satuan = $id");
$data = mysqli_fetch_assoc($check);

if($data['id_agen'] != $idAgen) {
    echo "<script>
        alert('Akses ditolak');
        window.location = 'edit-harga.php';
    </script>";
    exit;
}

mysqli_query($connect, "DELETE FROM harga_satuan WHERE id_harga_satuan = $id");

if(mysqli_affected_rows($connect) > 0) {
    echo "<script>
        window.location = 'edit-harga.php';
    </script>";
}
