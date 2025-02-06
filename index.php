<?php
session_start();
include 'connect-db.php';

// Konfigurasi pagination
$jumlahDataPerHalaman = 3;
$halamanAktif = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$awalData = ($halamanAktif - 1) * $jumlahDataPerHalaman;

// Inisialisasi variabel untuk query dasar dan query hitung total data
$baseQuery = "SELECT * FROM agen";
$countQuery = "SELECT COUNT(*) as total FROM agen";
$additionalWhere = ""; // Untuk kondisi search

// Jika tombol cari ditekan, ubah query
if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $additionalWhere = " WHERE kota LIKE '%$keyword%' OR nama_laundry LIKE '%$keyword%'";
    $baseQuery .= $additionalWhere;
    $countQuery .= $additionalWhere;
}

// Jika tombol sorting ditekan, gunakan query sorting (menggunakan join dengan tabel harga)
if (isset($_POST["submitSorting"])) {
    $sorting = $_POST["sorting"];
    // Contoh: sorting berdasarkan harga ascending (hanya tipe 'komplit')
    $baseQuery = "SELECT agen.* FROM agen 
                  JOIN harga ON agen.id_agen = harga.id_agen 
                  WHERE harga.jenis = 'komplit' 
                  ORDER BY harga.harga ASC";
    $countQuery = "SELECT COUNT(*) as total FROM agen 
                   JOIN harga ON agen.id_agen = harga.id_agen 
                   WHERE harga.jenis = 'komplit'";
}

// Hitung total data untuk pagination
$resultCount = mysqli_query($connect, $countQuery);
$rowCount = mysqli_fetch_assoc($resultCount);
$jumlahData = $rowCount['total'];
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Tambahkan LIMIT ke query utama
// Hanya tambahkan LIMIT jika belum menggunakan query sorting dengan join yang mungkin sudah mengandung LIMIT logika tersendiri
$baseQuery .= " LIMIT $awalData, $jumlahDataPerHalaman";

// Jalankan query untuk mendapatkan data agen
$agen = mysqli_query($connect, $baseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laundryku</title>
    <?php include 'headtags.html'; ?>
    <style>
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border-radius: 15px;
            overflow: hidden;
            height: 400px;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card .card-image img {
            height: 200px;
            object-fit: cover;
        }
        .card .card-content {
            height: 150px;
        }
        .card .card-action {
            height: 50px;
        }
        .card .card-action a {
            color: #ff9800;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <br>
        <h1 class="header center orange-text">
            <img src="img/banner.png" width="70%" alt="">
        </h1>
        <div class="row center">
            <h5 class="header col s12 light">Solusi Laundry Praktis Tanpa Keluar Rumah</h5>
        </div>

        <!-- Menu Navigasi Berdasarkan Session -->
        <div class="row center">
            <div id="body">
                <?php if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) : ?>
                    <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="pelanggan.php">Profil Saya</a>
                        <?php 
                        $idPelanggan = $_SESSION['pelanggan'];
                        $cek = mysqli_query($connect, "SELECT * FROM cucian WHERE id_pelanggan = $idPelanggan AND status_cucian != 'Selesai'");
                        $status = mysqli_num_rows($cek) > 0 ? "Status Cucian<i class='material-icons right'>notifications_active</i>" : "Status Cucian";
                        $cek2 = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_pelanggan = $idPelanggan AND (rating = 0 OR komentar = '')");
                        $transaksi = mysqli_num_rows($cek2) > 0 ? "Riwayat Transaksi<i class='material-icons right'>notifications_active</i>" : "Riwayat Transaksi";
                        ?>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php"><?= $transaksi ?></a>
                    </div>
                <?php elseif (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) : ?>
                    <div class="hero__btn" data-animation="fadeInRight" data-delay="1s">
                        <?php
                        $idAgen = $_SESSION['agen'];
                        $cek = mysqli_query($connect, "SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'");
                        $status = mysqli_num_rows($cek) > 0 ? "Status Cucian<i class='material-icons right'>notifications_active</i>" : "Status Cucian";
                        ?>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="agen.php">Profil Saya</a>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                        <a id="download-button" class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                    </div>
                <?php elseif (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) : ?>
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
                <?php endif; ?>
            </div>
        </div>
        <br>
    </div>

    <!-- Form Pencarian -->
    <form class="col s12 center" action="" method="post">
        <div class="input-field inline">
            <input type="text" size="40" name="keyword" placeholder="Kota / Kabupaten" id="keyword" autofocus autocomplete="off">
            <button type="submit" class="btn waves-effect blue darken-2" id="cariData" name="cari">
                <i class="material-icons">search</i>
            </button>
        </div>
    </form>

    <!-- (Opsional) Form Sorting, kalau Bos mau aktifkan sorting, hapus komentar di bawah -->
    <!--
    <div class="row">
        <div class="col s4 offset-s4">
            <form action="" method="post">
                <label for="sorting">Sorting</label>
                <select class="browser-default" name="sorting" id="sorting">
                    <option disabled selected>Pilih Sorting</option>
                    <option value="hargaDown">Harga Terendah</option>
                </select>
                <div class="center">
                    <button class="btn blue darken-2" type="submit" name="submitSorting">
                        <i class="material-icons">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    -->

    <!-- Tampilan List Agen -->
    <div id="container">
        <!-- Pagination -->
        <div id="search">
            <ul class="pagination center">
                <?php if ($halamanAktif > 1) : ?>
                    <li class="disabled-effect blue darken-1">
                        <a href="?page=<?= $halamanAktif - 1; ?>">
                            <i class="material-icons">chevron_left</i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <?php if ($i == $halamanAktif) : ?>
                        <li class="active grey">
                            <a href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php else : ?>
                        <li class="waves-effect blue darken-1">
                            <a href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($halamanAktif < $jumlahHalaman) : ?>
                    <li class="waves-effect blue darken-1">
                        <a href="?page=<?= $halamanAktif + 1; ?>">
                            <i class="material-icons">chevron_right</i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- End Pagination -->

        <!-- List Agen -->
        <div class="container">
            <div class="section">
                <div class="row">
                    <?php while ($dataAgen = mysqli_fetch_assoc($agen)) : ?>
                        <div class="col s12 m4">
                            <div class="card">
                                <div class="card-image">
                                    <img src="img/agen/<?= $dataAgen['foto'] ?>" alt="Foto Agen">
                                </div>
                                <div class="card-content">
                                    <?php
                                    // Hitung rating untuk masing-masing agen
                                    $temp = $dataAgen["id_agen"];
                                    $queryStar = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$temp'");
                                    $totalStar = 0;
                                    $i = 0;
                                    while ($star = mysqli_fetch_assoc($queryStar)) {
                                        if ($star["rating"] != 0) {
                                            $totalStar += $star["rating"];
                                            $i++;
                                        }
                                    }
                                    $fixStar = $i > 0 ? ceil($totalStar / $i) : 0;
                                    ?>
                                    <div class="center">
                                        <fieldset class="bintang">
                                            <span class="starImg star-<?= $fixStar ?>"></span>
                                        </fieldset>
                                    </div>
                                    <p>
                                        Alamat: <?= $dataAgen["alamat"] . ", " . $dataAgen["kota"] ?><br>
                                        Telp: <?= $dataAgen["telp"] ?>
                                    </p>
                                </div>
                                <div class="card-action">
                                    <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>">
                                        <?= $dataAgen["nama_laundry"] ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <br><br>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Materialize JS & Script Files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/scriptAjax.js"></script>
</body>
</html>
