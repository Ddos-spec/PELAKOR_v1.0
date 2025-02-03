okay before you give me a reason why you seem to have trouble editing the appearance<?php

session_start();
include 'connect-db.php';


//konfirgurasi pagination
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
//ceil() = pembulatan ke atas
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

//menentukan halaman aktif
if ( isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
}else{
    $halamanAktif = 1;
}

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Fetch data from the database
$agen = mysqli_query($connect, "SELECT * FROM agen LIMIT ?, ?");
$agen->bind_param("ii", $awalData, $jumlahDataPerHalaman);
$agen->execute();
$result = $agen->get_result();


//ketika tombol cari ditekan
if ( isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);

    $query = "SELECT * FROM agen WHERE 
        kota LIKE '%$keyword%' OR
        nama_laundry LIKE '%$keyword%'
        LIMIT $awalData, $jumlahDataPerHalaman
    ";

    $agen = mysqli_query($connect,$query);

    //konfirgurasi pagination
    $jumlahDataPerHalaman = 3;
    $jumlahData = mysqli_num_rows($agen);
    //ceil() = pembulatan ke atas
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

    //menentukan halaman aktif
    if ( isset($_GET["page"])){
        $halamanAktif = $_GET["page"];
    }else{
        $halamanAktif = 1;
    }
}

if (isset($_POST["submitSorting"])){
    $sorting = $_POST["sorting"];

    $agen = mysqli_query($connect, "SELECT * FROM agen JOIN harga ON agen.id_agen = harga.id_agen WHERE harga.jenis = 'komplit' ORDER BY harga.harga ASC LIMIT $awalData, $jumlahDataPerHalaman");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Laundryku</title>
    <?php include 'headtags.html' ?>
</head>
<body>

    <?php include 'header.php'; ?>

        <div class="container">
            <br>
            <h1 class="header center orange-text"><img src="img/banner.png" width=60% alt=""></h1>
            <div class="row center">
                <h5 class="header col s12 light">Solusi Laundry Praktis Tanpa Keluar Rumah</h5>
            </div>

            <!-- menu -->
            <div class="row center">
                <div id="body">
                    <?php if ( isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"]) ) : ?>
                        <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="pelanggan.php">Profil Saya</a>
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

                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php"><?= $transaksi ?></a>
                        </div>
                    <?php elseif ( isset($_SESSION["login-agen"]) && isset($_SESSION["agen"]) ) : ?>
                        <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                        <?php
                        $idAgen = $_SESSION['agen'];
                        $cek = mysqli_query($connect,"SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'");
                        if (mysqli_num_rows($cek) > 0){
                            $status = "Status Cucian<i class='material-icons right'>notifications_active</i>";
                        }else {
                            $status = "Status Cucian";
                        }
                        ?>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="agen.php">Profil Saya</a>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                        </div>
                    <?php elseif ( isset($_SESSION["login-admin"]) && isset($_SESSION["admin"]) ) : ?>
                        <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="admin.php">Profil Saya</a>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php">Status Cucian</a>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                            <br><br>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="list-agen.php">Data Agen</a>
                            <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="list-pelanggan.php">Data Pelanggan</a>
                        </div>
                    <?php else : ?>
                        <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                            <a href="registrasi.php" id="download-button" class="btn-large waves-effect waves-light blue darken-3">Daftar Sekarang</a>
                        </div>
                    <?php endif ?>
                </div>
            <!-- end menu -->
            </div>
            <br>
        </div>


    <!-- searching -->
    <form class="col s12 center" action="" method="post">
        <div class="input-field inline">
            <input type="text" size=40 name="keyword" placeholder="Kota / Kabupaten" id="keyword" autofocus autocomplete="off">
        </div>
    </form>
    <!-- end searching -->

    <div id="container">
        <!-- pagination -->
        <div id="search">
            <ul class="pagination center">
            <?php if( $halamanAktif > 1 ) : ?>
                <li class="disabled-effect blue darken-1">
                    <a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a>
                </li>
            <?php endif; ?>
            <?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
                <?php if( $i == $halamanAktif ) : ?>
                    <li class="active grey"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                <?php else : ?>
                    <li class="waves-effect blue darken-1"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if( $halamanAktif < $jumlahHalaman ) : ?>
                <li class="waves-effect blue darken-1">
                    <a class="page-link" href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a>
                </li>
            <?php endif; ?>
            </ul>
        </div>
        <!-- pagination -->

        <!-- list agen -->
        <div class="container">
            <div class="section">
                <div class="row card">
                    <?php foreach ( $agen as $dataAgen) : ?>
                        <div class="col s12 m4">
                            <div class="icon-block center">
                                <h2 class="center light-blue-text">
                                    <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>">
                                        <img src="img/agen/<?= $dataAgen['foto'] ?>" class="circle" width="150" height="150" />
                                    </a>
                                </h2>
                                <h5 class="center">
                                    <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>"><?= $dataAgen["nama_laundry"] ?></a>
                                </h5>
                                <p class="light">
                                    Alamat : <?= $dataAgen["alamat"] . ", " . $dataAgen["kota"]  ?>
                                    <br/>Telp : <?= $dataAgen["telp"] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <br><br>
        </div>
    </div>

    <!-- footer -->
    <?php include "footer.php" ?>
    <!-- end footer -->

</body>
    <script src="js/script.js"></script>
    <script src="js/scriptAjax.js"></script>
</html>
