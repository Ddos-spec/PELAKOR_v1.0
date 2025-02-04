<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// validasi login
cekAdmin();

//konfirgurasi pagination
$jumlahDataPerHalaman = 5;
$stmt = $connect->prepare("SELECT * FROM agen");
$stmt->execute();
$result = $stmt->get_result();
$jumlahData = mysqli_num_rows($result);
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
$agen = mysqli_query($connect,"SELECT * FROM agen ORDER BY id_agen DESC LIMIT $awalData, $jumlahDataPerHalaman");

//ketika tombol cari ditekan
if ( isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM agen WHERE 
        nama_laundry LIKE '%$keyword%' OR
        nama_pemilik LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_agen DESC
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Data Agen</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <h3 class="uk-heading-line uk-text-center"><span>List Agen</span></h3>
    <br>

    <!-- searching -->
    <form class="uk-form-inline uk-text-center" action="" method="post">
        <div class="uk-margin">
            <input class="uk-input" type="text" size=40 name="keyword" placeholder="Keyword">
            <button class="uk-button uk-button-primary" type="submit" name="cari"><i class="material-icons">search</i></button>
        </div>
    </form>
    <!-- end searching -->

    <div class="uk-container">
        <!-- pagination -->
        <ul class="uk-pagination uk-flex-center">
        <?php if( $halamanAktif > 1 ) : ?>
            <li>
                <a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a>
            </li>
        <?php endif; ?>
        <?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
            <?php if( $i == $halamanAktif ) : ?>
                <li class="uk-active"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
            <?php else : ?>
                <li><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if( $halamanAktif < $jumlahHalaman ) : ?>
            <li>
                <a href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a>
            </li>
        <?php endif; ?>
        </ul>
        <!-- end pagination -->

        <!-- data agen -->
        <table class="uk-table uk-table-divider uk-table-hover">