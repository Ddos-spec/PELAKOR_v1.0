<?php

session_start();
include 'connect-db.php';

//konfirgurasi pagination
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

if ( isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
}else{
    $halamanAktif = 1;
}

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$stmt = $connect->prepare("SELECT * FROM agen LIMIT ?, ?");
$stmt->bind_param("ii", $awalData, $jumlahDataPerHalaman);
$stmt->execute();
$result = $stmt->get_result();

if ( isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM agen WHERE 
        kota LIKE '%$keyword%' OR
        nama_laundry LIKE '%$keyword%'
        LIMIT $awalData, $jumlahDataPerHalaman
    ";
    $result = mysqli_query($connect,$query);
    $jumlahDataPerHalaman = 3;
    $jumlahData = mysqli_num_rows($result);
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    if ( isset($_GET["page"])){
        $halamanAktif = $_GET["page"];
    }else{
        $halamanAktif = 1;
    }
}

if (isset($_POST["submitSorting"])){
    $sorting = $_POST["sorting"];
    $result = mysqli_query($connect, "SELECT * FROM agen JOIN harga ON agen.id_agen = harga.id_agen WHERE harga.jenis = 'komplit' ORDER BY harga.harga ASC LIMIT $awalData, $jumlahDataPerHalaman");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laundryku</title>
    <?php include 'headtags.html' ?>
    <style>
        body {
            background-color: #e0f7fa;
        }
        .uk-card {
            border-radius: 10px;
            overflow: hidden;
        }
        .uk-card img {
            object-fit: cover;
            width: 100%;
            height: 200px;
        }
        .uk-card:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        }
        .uk-pagination .uk-active span {
            background-color: #1e87f0;
            color: white;
        }
    </style>
</head>
<body class="uk-background-default uk-text-muted">

    <?php include 'header.php'; ?>

    <div class="uk-container">
        <br>
        <h1 class="uk-text-center uk-text-primary"><img src="img/banner.png" width=60% alt=""></h1>
        <div class="uk-text-center">
            <h5 class="uk-text-large">Solusi Laundry Praktis Tanpa Keluar Rumah</h5>
        </div>

        <div class="uk-text-center">
            <div id="body">
                <?php if ( isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"]) ) : ?>
                    <div class="uk-button-group" data-animation="fadeInRight" data-delay="1s">
                        <a id="download-button" class="uk-button uk-button-primary" href="pelanggan.php">Profil Saya</a>
                        <?php 
                        $idPelanggan = $_SESSION['pelanggan'];
                        $cek = mysqli_query($connect,"SELECT * FROM cucian WHERE id_pelanggan = $idPelanggan AND status_cucian != 'Selesai'");
                        if (mysqli_num_rows($cek) > 0){
                            $status = "Status Cucian<i class='material-icons right'>notifications_active</i>";
                        }else {
                            $status = "Status Cucian";
                        }

                        $cek = mysqli_query($connect,"SELECT * FROM transaksi WHERE id_pelanggan = $idPelanggan AND rating = 0 OR komentar = ''");
                        if (mysqli_num_rows($cek) > 0){
                            $transaksi = "Riwayat Transaksi<i class='material-icons right'>notifications_active</i>";
                        }else {
                            $transaksi = "Riwayat Transaksi";
                        }
                        ?>
                        <a id="download-button" class="uk-button uk-button-primary" href="status.php"><?= $status ?></a>
                        <a id="download-button" class="uk-button uk-button-primary" href="transaksi.php"><?= $transaksi ?></a>
                    </div>
                <?php elseif ( isset($_SESSION["login-agen"]) && isset($_SESSION["agen"]) ) : ?>
                    <div class="uk-button-group" data-animation="fadeInRight" data-delay="1s">
                    <?php
                    $idAgen = $_SESSION['agen'];
                    $cek = mysqli_query($connect,"SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'");
                    if (mysqli_num_rows($cek) > 0){
                        $status = "Status Cucian<i class='material-icons right'>notifications_active</i>";
                    }else {
                        $status = "Status Cucian";
                    }
                    ?>
                        <a id="download-button" class="uk-button uk-button-primary" href="agen.php">Profil Saya</a>
                        <a id="download-button" class="uk-button uk-button-primary" href="status.php"><?= $status ?></a>
                        <a id="download-button" class="uk-button uk-button-primary" href="transaksi.php">Riwayat Transaksi</a>
                    </div>
                <?php elseif ( isset($_SESSION["login-admin"]) && isset($_SESSION["admin"]) ) : ?>
                    <div class="uk-button-group" data-animation="fadeInRight" data-delay="1s">
                        <a id="download-button" class="uk-button uk-button-primary" href="admin.php">Profil Saya</a>
                        <a id="download-button" class="uk-button uk-button-primary" href="status.php">Status Cucian</a>
                        <a id="download-button" class="uk-button uk-button-primary" href="transaksi.php">Riwayat Transaksi</a>
                        <br><br>
                        <a id="download-button" class="uk-button uk-button-primary" href="list-agen.php">Data Agen</a>
                        <a id="download-button" class="uk-button uk-button-primary" href="list-pelanggan.php">Data Pelanggan</a>
                    </div>
                <?php else : ?>
                    <div class="uk-button-group" data-animation="fadeInRight" data-delay="1s">
                        <a href="registrasi.php" id="download-button" class="uk-button uk-button-primary">Daftar Sekarang</a>
                    </div>
                <?php endif ?>
            </div>
            <br>
        </div>

    <form class="uk-form-inline" action="" method="post">
        <div class="uk-margin">
            <input type="text" size=40 name="keyword" placeholder="Kota / Kabupaten" id="keyword" autofocus autocomplete="off" class="uk-input">
        </div>
    </form>

    <div id="container">
        <div id="search">
            <ul class="uk-pagination uk-flex-center">
            <?php if( $halamanAktif > 1 ) : ?>
                <li class="uk-disabled">
                    <a href="?page=<?= $halamanAktif - 1; ?>"><i class="uk-icon" uk-icon="chevron-left"></i></a>
                </li>
            <?php endif; ?>
            <?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
                <?php if( $i == $halamanAktif ) : ?>
                    <li class="uk-active"><span><?= $i ?></span></li>
                <?php else : ?>
                    <li><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if( $halamanAktif < $jumlahHalaman ) : ?>
                <li>
                    <a href="?page=<?= $halamanAktif + 1; ?>"><i class="uk-icon" uk-icon="chevron-right"></i></a>
                </li>
            <?php endif; ?>
            </ul>
        </div>

        <div class="uk-container">
            <div class="uk-section">
                <div class="uk-grid uk-child-width-1-3@m" uk-grid>
                    <?php while ($dataAgen = mysqli_fetch_assoc($result)) : ?>
                        <div>
                            <div class="uk-card uk-card-default uk-card-hover">
                                <div class="uk-card-media-top">
                                    <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>">
                                        <img src="img/agen/<?= $dataAgen['foto'] ?>" />
                                    </a>
                                </div>
                                <div class="uk-card-body">
                                    <h5 class="uk-text-center uk-text-bold">
                                        <a class="uk-link-heading" href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>"><?= $dataAgen["nama_laundry"] ?></a>
                                    </h5>
                                    <p class="uk-text-muted">
                                        Alamat : <?= $dataAgen["alamat"] . ", " . $dataAgen["kota"]  ?>
                                        <br/>Telp : <?= $dataAgen["telp"] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <br><br>
        </div>
    </div>

    <?php include "footer.php" ?>
</body>
    <script src="js/script.js"></script>
    <script src="js/scriptAjax.js"></script>
</html>