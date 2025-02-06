<?php

session_start();
include 'connect-db.php';


//konfirgurasi pagination
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
//ceil() = pembulatan ke atas
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

//menentukan halaman aktif
//$halamanAktif = ( isset($_GET["page"]) ) ? $_GET["page"] : 1; = versi simple
if ( isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
}else{
    $halamanAktif = 1;
}

//data awal
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;

//fungsi memasukkan data di db ke array
$agen = mysqli_query($connect,"SELECT * FROM agen LIMIT $awalData, $jumlahDataPerHalaman");



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
    //$halamanAktif = ( isset($_GET["page"]) ) ? $_GET["page"] : 1; = versi simple
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
    <style>
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 15px; /* Membulatkan sudut card */
        overflow: hidden; /* Agar gambar tidak keluar dari batas card */
    }
    .card:hover {
        transform: scale(1.05); /* Efek membesar saat hover */
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }
    .card-img-top {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
</style>

</head>
<body>

    <?php include 'header.php'; ?>

        <div class="container">
            <br>
            <h1 class="header center orange-text"><img src="img/banner.png" width=70% alt=""></h1>
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
            <!-- <a href="#search"><button type="submit" class="btn waves-effect blue darken-2" id="cariData" name="cari"><i class="material-icons">search</i></button></a> -->
        </div>
    </form>
    <!-- end searching -->

    <div id="container">
        <!-- pagination -->
        <div id="search">
            <ul class="pagination center">
            <?php if( $halamanAktif > 1 ) : ?>
                <li class="disabled-effect blue darken-1">
                    <!-- halaman pertama -->
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


        <!-- sorting -->
        <!-- <div class="row">
            <div class="col s4 offset-s4">
                <form action="" method="post">
                    <label for="sorting">Sorting</label>
                    <select class="browser-default" name="sorting" id="sorting">
                        <option disabled>Sorting</option>
                        <option value="hargaDown">Harga Terendah</option>
                    </select>
                    <div class="center"><button class="btn blue darken-2" type="submit" name="submitSorting"><i class="material-icons">send</i></button></div>
                </form>
            </div>
        </div> -->
        <!-- end sorting -->

        <!-- list agen -->
    
        <div class="container">
    <div class="row">
        <?php foreach ($agen as $dataAgen) : ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm">
                    <img src="img/agen/<?= $dataAgen['foto'] ?>" class="card-img-top" alt="<?= $dataAgen['nama_laundry'] ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"> <?= $dataAgen['nama_laundry'] ?> </h5>
                        <p class="card-text">Alamat: <?= $dataAgen['alamat'] . ', ' . $dataAgen['kota'] ?></p>
                        <p class="card-text">Telp: <?= $dataAgen['telp'] ?></p>
                        <div>
                            <?php
                                $temp = $dataAgen['id_agen'];
                                $queryStar = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$temp'");
                                $totalStar = 0;
                                $i = 0;
                                while ($star = mysqli_fetch_assoc($queryStar)) {
                                    if ($star['rating'] != 0) {
                                        $totalStar += $star['rating'];
                                        $i++;
                                    }
                                }
                                $fixStar = ($i > 0) ? ceil($totalStar / $i) : 0;
                            ?>
                            <span class="starImg star-<?= $fixStar ?>"></span>
                        </div>
                    </div>
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
