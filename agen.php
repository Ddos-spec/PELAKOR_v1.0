<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// harus agen yg kesini
cekAgen();

// ambil data agen
$idAgen = $_SESSION["agen"];
$stmt = $connect->prepare("SELECT * FROM agen WHERE id_agen = ?");
$stmt->bind_param("i", $idAgen);
$stmt->execute();
$result = $stmt->get_result();
$agen = mysqli_fetch_assoc($result);
$result = mysqli_query($connect, $query);
if (!$result) {
    echo "<script>Swal.fire('Error', 'Failed to retrieve data', 'error');</script>";
    exit;
}
$agen = mysqli_fetch_assoc($result);
$idAgen = $agen["id_agen"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html'; ?>
    <title>Profil Agen</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- data agen -->
    <div class="row">
        <div class="col s6 offset-s3">
            <h3 class="header light center">Data Agen</h3>
            <br><br>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="center">
                    <img src="img/agen/<?= $agen['foto'] ?>" class="circle" width="150" height="150" alt="">
                </div>
                <div class="file-field input-field">
                    <div class="btn blue darken-2">
                        <span>Foto Profil</span>
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload foto profil">
                    </div>
                </div>
                <div class="input-field">
                    <ul>
                        <li>
                            <label for="namaLaundry">Nama Laundry</label>
                            <input type="text" id="namaLaundry" name="namaLaundry" value="<?= $agen['nama_laundry']?>">
                        </li>
                        <li>
                            <label for="namaPemilik">Nama Pemilik</label>
                            <input type="text" id="namaPemilik" name="namaPemilik" value="<?= $agen['nama_pemilik']?>">
                        </li>
                        <li>
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" value="<?= $agen['email']?>">
                        </li>
                        <li>
                            <label for="telp">No Telp</label>
                            <input type="text" id="telp" name="telp" value="<?= $agen['telp']?>">
                        </li>
                        <li>
                            <label for="plat">Plat Driver</label>
                            <input type="text" id="plat" name="platDriver" value="<?= $agen['plat_driver']?>">
                        </li>
                        <li>
                            <label for="kota">Kota / Kabupaten</label>
                            <input type="text" name="kota" value="<?= $agen['kota']?>">
                        </li>
                        <li>
                            <label for="alamat">Alamat</label>
                            <textarea class="materialize-textarea" name="alamat"><?= $agen['alamat']?></textarea>
                        </li>
                        <li>
                            <
