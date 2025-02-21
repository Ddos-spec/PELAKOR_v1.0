<?php
session_start();
include 'connect-db.php';

// Konfigurasi pagination (server-side fallback)
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect, "SELECT a.*, COALESCE(AVG(NULLIF(t.rating, 0)), 0) as rating 
                                FROM agen a 
                                LEFT JOIN transaksi t ON a.id_agen = t.id_agen 
                                GROUP BY a.id_agen");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Query default (server-side)
$agen = mysqli_query($connect, "SELECT a.*, COALESCE(AVG(NULLIF(t.rating, 0)), 0) as rating 
                               FROM agen a 
                               LEFT JOIN transaksi t ON a.id_agen = t.id_agen 
                               GROUP BY a.id_agen 
                               ORDER BY a.nama_laundry ASC 
                               LIMIT $awalData, $jumlahDataPerHalaman");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laundryku</title>
    <?php include 'headtags.html'; ?>
    <style>
        .agent-card {
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .agent-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .agent-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 16px;
        }
        .card-action {
            padding: 16px;
            border-top: 1px solid #eee;
        }
        .rating-stars {
            color: #ffd700;
            font-size: 20px;
        }
        .pagination {
            margin: 2rem 0;
        }
        /* Sembunyikan pesan "no results" secara default */
        #noResults {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <br>
        <h1 class="header center orange-text">
            <img src="img/banner.png" width="70%" alt="Laundryku Banner">
        </h1>
        <div class="row center">
            <h5 class="header col s12 light">"Solusi Laundry Praktis Tanpa Keluar Rumah"</h5>
        </div>

        <!-- (Bagian menu, dll) -->

        <!-- Search Bar -->
        <div class="row">
            <div class="input-field col s12 m6 offset-m3">
                <i class="material-icons prefix">search</i>
                <input type="text" id="keyword" placeholder="Cari berdasarkan nama laundry atau kota..." class="validate" autocomplete="off">
                <label for="keyword">Pencarian</label>
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="row grey-text center">
            <div class="col s12">
                <h5>Tidak ada hasil yang ditemukan</h5>
            </div>
        </div>

        <!-- Agent List Container -->
        <div class="row" id="agentContainer">
            <!-- Data awal (fallback server-side) -->
            <?php foreach($agen as $dataAgen): ?>
                <div class="col s12 m4">
                    <div class="card agent-card">
                        <div class="card-image">
                            <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>">
                                <img src="img/agen/<?= $dataAgen['foto'] ?>" alt="<?= $dataAgen["nama_laundry"] ?>" class="agent-image">
                            </a>
                        </div>
                        <div class="card-content">
                            <span class="card-title"><?= $dataAgen["nama_laundry"] ?></span>
                            <p>
                                <i class="material-icons tiny">location_on</i> <?= $dataAgen["alamat"] ?>, <?= $dataAgen["kota"] ?>
                            </p>
                            <p>
                                <i class="material-icons tiny">phone</i> <?= $dataAgen["telp"] ?>
                            </p>
                        </div>
                        <div class="card-action">
                            <div class="rating-stars">
                                <?= str_repeat('★', round($dataAgen['rating'])) . str_repeat('☆', 5 - round($dataAgen['rating'])) ?>
                            </div>
                            <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>">Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination (Jika ingin dihilangkan, hapus baris di bawah) -->
        <div class="row center">
            <ul class="pagination">
                <!-- ScriptAjax.js akan menimpa ini jika user mulai mencari -->
                <?php if($halamanAktif > 1) : ?>
                    <li class="waves-effect">
                        <a href="?page=<?= $halamanAktif - 1 ?>"><i class="material-icons">chevron_left</i></a>
                    </li>
                <?php endif; ?>

                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <li class="waves-effect <?= $i == $halamanAktif ? 'active blue' : '' ?>">
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if($halamanAktif < $jumlahHalaman) : ?>
                    <li class="waves-effect">
                        <a href="?page=<?= $halamanAktif + 1 ?>"><i class="material-icons">chevron_right</i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <script src="materialize/js/materialize.min.js"></script>
    <script src="js/script.js"></script>
    <!-- Pastikan file scriptAjax.js disertakan setelah elemen HTML -->
    <script src="js/scriptAjax.js"></script>
</body>
</html>
