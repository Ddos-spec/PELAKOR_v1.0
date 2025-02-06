<?php

// mulai session
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekLogin();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Registrasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        .card {
            border-radius: 15px; /* Membuat card menjadi rounded */
            height: 300px; /* Menyamakan ukuran card */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-pelanggan {
            background-color: #2196F3; /* Warna biru untuk pelanggan */
            color: white;
        }
        .card-agen {
            background-color: #FFEB3B; /* Warna kuning untuk agen */
            color: black;
        }
        .btn-white {
            background-color: white;
            color: black;
            font-weight: bold; /* Pertebal tulisan */
            transition: background-color 0.3s;
        }
        .card-pelanggan .btn-white:hover {
            background-color: #2196F3; /* Warna biru untuk hover pelanggan */
            color: white;
        }
        .card-agen .btn-white:hover {
            background-color: #FFEB3B; /* Warna kuning untuk hover agen */
            color: black;
        }
        .card-icon {
            font-size: 70px; /* Perbesar ukuran ikon */
            margin-bottom: 10px;
        }
    </style>
    <!-- Tambahkan link ke ikon Materialize -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <h3 class="header light center">Halaman Registrasi</h3>
    <br>

    <!-- body -->
    <div class="container center">
        <div class="row">
            <div class="col s12 m6">
                <div class="card card-pelanggan">
                    <div class="card-content">
                        <i class="material-icons card-icon">person</i>
                        <span class="card-title">Registrasi Sebagai Pelanggan</span>
                        <p>Daftar sebagai pelanggan untuk menikmati layanan laundry kami.</p>
                    </div>
                    <div class="card-action">
                        <a href="registrasi-pelanggan.php" class="btn btn-large waves-effect waves-light btn-white">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card card-agen">
                    <div class="card-content">
                        <i class="material-icons card-icon">person</i>
                        <span class="card-title">Registrasi Sebagai Agen</span>
                        <p>Daftar sebagai agen untuk menyediakan layanan laundry.</p>
                    </div>
                    <div class="card-action">
                        <a href="registrasi-agen.php" class="btn btn-large waves-effect waves-light btn-white">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- body -->

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>