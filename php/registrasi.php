<?php
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
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <style>
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            width: 45%;
            margin: 1%;
            display: inline-block;
            vertical-align: top;
            padding: 20px;
            border-radius: 15px;
            min-height: 250px;
            text-align: center;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card-pelanggan {
            background-color: #FFD700 !important; /* Warna kuning */
            color: #000; /* Warna teks hitam */
        }
        .card-agen {
            background-color: #1E90FF !important; /* Warna biru */
            color: #fff; /* Warna teks putih */
        }
        .uk-button {
            background-color: #fff; /* Warna tombol putih */
            color: #000 !important; /* Teks hitam */
            font-weight: bold;
            padding: 8px 16px; /* Ukuran tombol lebih kecil */
            font-size: 14px; /* Ukuran font lebih kecil */
            border-radius: 8px; /* Sudut membulat */
            border: 2px solid #fff; /* Garis batas */
            transition: background-color 0.3s, transform 0.3s;
        }
        .uk-button:hover {
            background-color: rgba(255, 255, 255, 0.9); /* Warna putih saat hover */
            transform: scale(1.05); /* Efek zoom */
        }
        .uk-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1%; /* Jarak antar kartu */
            margin-bottom: 50px; /* Jarak dari footer */
        }
        .uk-card-title {
            margin-bottom: 15px;
        }
        .uk-card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
    </style>
    <title>Registrasi</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h3 class="uk-heading-line uk-text-center"><span>Halaman Registrasi</span></h3>
    <br>
    <div class="uk-container">
        <div class="uk-card uk-card-default uk-card-body card card-pelanggan">
            <h3 class="uk-card-title"><span uk-icon="icon: user"></span> Registrasi Pelanggan</h3>
            <p>Pelanggan adalah orang yang menggunakan layanan laundry kami.</p>
            <a class="uk-button uk-button-large" href="registrasi-pelanggan.php">Registrasi Pelanggan</a>
        </div>
        <div class="uk-card uk-card-default uk-card-body card card-agen">
            <h3 class="uk-card-title"><span uk-icon="icon: user"></span> Registrasi Agen</h3>
            <p>Agen adalah orang yang membantu kami untuk memperluas jangkauan layanan.</p>
            <a class="uk-button uk-button-large" href="registrasi-agen.php">Registrasi Agen</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>
