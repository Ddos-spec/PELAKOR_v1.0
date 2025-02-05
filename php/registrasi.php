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
            border-radius: 15px; /* Membuat sudut card menjadi rounded */
            min-height: 250px; /* Memperpanjang card ke bawah */
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card-pelanggan {
            background-color: #FFD700; /* Warna kuning */
            color: #000; /* Warna teks hitam */
        }
        .card-agen {
            background-color: #1E90FF; /* Warna biru */
            color: #fff; /* Warna teks putih */
        }
        .uk-button-pelanggan {
            background-color: #FFD700; /* Warna kuning */
            color: #fff !important; /* Warna teks putih */
            font-weight: bold; /* Ketebalan teks */
        }
        .uk-button-agen {
            background-color: #1E90FF; /* Warna biru */
            color: #fff !important; /* Warna teks putih */
            font-weight: bold; /* Ketebalan teks */
        }
    </style>
    <title>Registrasi</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h3 class="uk-heading-line uk-text-center"><span>Halaman Registrasi</span></h3>
    <br>
    <div class="uk-container uk-text-center">
        <div class="uk-card uk-card-default uk-card-body uk-margin card card-pelanggan">
            <h3 class="uk-card-title"><span uk-icon="icon: user"></span> Registrasi Pelanggan</h3>
            <p>Pelanggan adalah orang yang menggunakan layanan laundry kami.</p>
            <a id="download-button" class="uk-button uk-button-large uk-button-pelanggan" href="registrasi-pelanggan.php">Registrasi Pelanggan</a>
        </div>
        <div class="uk-card uk-card-primary uk-card-body uk-margin card card-agen">
            <h3 class="uk-card-title"><span uk-icon="icon: user"></span> Registrasi Agen</h3>
            <p>Agen adalah orang yang membantu kami untuk memperluas jangkauan layanan.</p>
            <a id="download-button" class="uk-button uk-button-large uk-button-agen" href="registrasi-agen.php">Registrasi Agen</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>