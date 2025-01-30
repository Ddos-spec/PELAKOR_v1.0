<?php
session_start();
include 'connect-db.php';

// Configuration for pagination
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect,"SELECT * FROM agen");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Determine active page
$halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;

// Initial data
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Fetch data from the database
$agen = mysqli_query($connect,"SELECT * FROM agen LIMIT $awalData, $jumlahDataPerHalaman");

// Search functionality
if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM agen WHERE 
        kota LIKE '%$keyword%' OR
        nama_laundry LIKE '%$keyword%'
        LIMIT $awalData, $jumlahDataPerHalaman";
    $agen = mysqli_query($connect,$query);
    $jumlahData = mysqli_num_rows($agen);
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;
}

// Sorting functionality
if (isset($_POST["submitSorting"])) {
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
        <h1 class="header center orange-text"><img src="img/baner.png" width=45% alt=""></h1>
        <div class="row center">
            <h5 class="header col s12 light">Solusi Laundry Praktis Tanpa Keluar Rumah</h5>
        </div>

        <!-- Menu -->
        <div class="row center">
            <div id="body">
                <?php if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) : ?>
                    <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="pelanggan.php">Profil Saya</a>
                        <?php 
                        $idPelanggan = $_SESSION['pelanggan'];
                        $cek = mysqli_query($connect,"SELECT * FROM cucian WHERE id_pelanggan = $idPelanggan AND status_cucian != 'Selesai'");
                        $status = mysqli_num_rows($cek) > 0 ? "Status Cucian<i class='material-icons right'>notifications_active</i>" : "Status Cucian";
                        ?>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                    </div>
                <?php elseif (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) : ?>
                    <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                        <?php
                        $idAgen = $_SESSION['agen'];
                        $cek = mysqli_query($connect,"SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'");
                        $status = mysqli_num_rows($cek) > 0 ? "Status Cucian<i class='material-icons right'>notifications_active</i>" : "Status Cucian";
                        ?>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="agen.php">Profil Saya</a>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                    </div>
                <?php else : ?>
                    <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                        <a href="registrasi.php" id="download-button" class="btn-large waves-effect waves-light blue darken-3">Daftar Sekarang</a>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <br>
    </div>

    <!-- Searching -->
    <form class="col s12 center" action="" method="post" style="margin-bottom: 20px;">
        <div class="input-field inline">
            <input type="text" size=40 name="keyword" placeholder="Kota / Kabupaten" id="keyword" autofocus autocomplete="off">
        </div>
    </form>

    <div id="container">
        <!-- Pagination -->
        <div id="search">
            <ul class="pagination center">
                <?php if($halamanAktif > 1) : ?>
                    <li class="disabled-effect blue darken-1">
                        <a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a>
                    </li>
                <?php endif; ?>
                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <li class="<?= $i == $halamanAktif ? 'active grey' : 'waves-effect blue darken-1' ?>">
                        <a href="?page=<?= $i; ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <?php if($halamanAktif < $jumlahHalaman) : ?>
                    <li class="waves-effect blue darken-1">
                        <a class="page-link" href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- List Agen -->
        <div class="container">
            <div class="section">
                <div class="row card">
                    <?php foreach ($agen as $dataAgen) : ?>
                        <div class="col s12 m4">
                            <div class="icon-block center">
                                <h2 class="center light-blue-text"><a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>"><img src="img/agen/<?= $dataAgen['foto'] ?>" class="circle responsive-img" width=60% /></a></h2>
                                <h5 class="center"><a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>"><?= $dataAgen["nama_laundry"] ?></a></h5>
                                <?php
                                    $temp = $dataAgen["id_agen"];
                                    $queryStar = mysqli_query($connect,"SELECT * FROM transaksi WHERE id_agen = '$temp'");
                                    $totalStar = 0;
                                    $i = 0;
                                    while ($star = mysqli_fetch_assoc($queryStar)){
                                        if ($star["rating"] != 0){
                                            $totalStar += $star["rating"];
                                            $i++;
                                            $fixStar = ceil($totalStar / $i);
                                        }
                                    }
                                    if ($totalStar == 0) {
                                ?>
                                    <center><fieldset class="bintang"><span class="starImg star-0"></span></fieldset></center>
                                <?php } else { ?>
                                    <center><fieldset class="bintang"><span class="starImg star-<?= $fixStar ?>"></span></fieldset></center>
                                <?php } ?>
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

    <!-- Footer -->
    <?php include "footer.php" ?>
    <!-- End Footer -->

</body>
<script src="js/script.js"></script>
<script src="js/scriptAjax.js"></script>
</html>
