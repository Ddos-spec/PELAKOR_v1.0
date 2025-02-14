<?php

//session 
session_start();
include 'connect-db.php';

// mengambil id agen dg method get
$idAgen = $_GET["id"];

// ambil data agen
$query = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$idAgen'");
$agen = mysqli_fetch_assoc($query);

// ambil data layanan
$queryLayanan = mysqli_query($connect, "SELECT * FROM layanan WHERE id_agen = '$idAgen'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title><?= $agen["nama_laundry"] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->
    <br><br>
    <!-- data agen -->
    <div class="row text-center">
        <div class="col-md-4 offset-md-4">
            <img src="img/agen/<?= $agen['foto'] ?>" class="rounded-circle img-fluid" width="70%" />
            <a id="download-button" class="btn btn-danger mt-3" href="pesan-laundry.php?id=<?= $idAgen ?>">PESAN LAUNDRY</a>
        </div>
    </div>
    <div class="row text-center mt-3">
        <h3><?= $agen["nama_laundry"] ?></h3>
        <p>Alamat: <?= $agen["alamat"] . ", " . $agen["kota"] ?></p>
        <p>No. HP: <?= $agen["telp"] ?></p>
    </div>

    <!-- Pilihan jenis layanan -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Layanan Kiloan</h5>
                        <p>Laundry berdasarkan berat pakaian dalam kilogram.</p>
                        <a href="kiloan.php?id=<?= $idAgen ?>" class="btn btn-primary mt-3">MASUK</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Layanan Satuan</h5>
                        <p>Laundry berdasarkan jumlah pakaian satuan.</p>
                        <a href="satuan.php?id=<?= $idAgen ?>" class="btn btn-primary mt-3">MASUK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>
