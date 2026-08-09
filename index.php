<?php
session_start();
include 'connect-db.php';

// Konfigurasi pagination server-side
$jumlahDataPerHalaman = 3;
$query = mysqli_query($connect, "SELECT a.*, COALESCE(AVG(NULLIF(t.rating, 0)), 0) as rating 
                                FROM agen a 
                                LEFT JOIN transaksi t ON a.id_agen = t.id_agen 
                                WHERE a.status = 'approved' 
                                GROUP BY a.id_agen");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Query data agen untuk tampilan awal (server-side)
$agen = mysqli_query($connect, "SELECT a.*, COALESCE(AVG(NULLIF(t.rating, 0)), 0) as rating 
                               FROM agen a 
                               LEFT JOIN transaksi t ON a.id_agen = t.id_agen 
                               WHERE a.status = 'approved' 
                               GROUP BY a.id_agen 
                               ORDER BY a.nama_laundry ASC 
                               LIMIT $awalData, $jumlahDataPerHalaman");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundryku — Laundry Praktis dari Rumah</title>
    <?php include 'headtags.html'; ?>
    <style>
        :root {
            --brand: #1565c0;
            --brand-dark: #0d47a1;
            --brand-soft: #e8f1ff;
            --ink: #152033;
            --muted: #6b778c;
            --surface: #ffffff;
            --line: #e8edf5;
            --page: #f5f8fc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background:
                radial-gradient(circle at 10% 10%, rgba(21, 101, 192, .08), transparent 26rem),
                var(--page);
            color: var(--ink);
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .page-shell {
            padding: 28px 0 56px;
        }

        .laundry-hero {
            position: relative;
            overflow: hidden;
            margin: 8px 0 34px;
            padding: clamp(32px, 6vw, 72px);
            border-radius: 30px;
            background: linear-gradient(135deg, #0a387a 0%, #1565c0 55%, #29a3ef 100%);
            box-shadow: 0 28px 70px rgba(13, 71, 161, .22);
            color: #fff;
        }

        .laundry-hero::before,
        .laundry-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .laundry-hero::before {
            width: 360px;
            height: 360px;
            right: -120px;
            top: -140px;
            background: rgba(255,255,255,.12);
        }

        .laundry-hero::after {
            width: 220px;
            height: 220px;
            right: 18%;
            bottom: -150px;
            background: rgba(101, 214, 255, .18);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 760px;
        }

        .hero-logo {
            display: block;
            width: min(350px, 72vw);
            max-height: 105px;
            object-fit: contain;
            object-position: left center;
            margin-bottom: 18px;
            filter: drop-shadow(0 8px 22px rgba(0,0,0,.12));
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 8px 12px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(8px);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .hero-title {
            margin: 0;
            max-width: 720px;
            font-size: clamp(2rem, 5vw, 4.1rem);
            font-weight: 800;
            line-height: 1.02;
            letter-spacing: -.04em;
        }

        .hero-copy {
            max-width: 650px;
            margin: 20px 0 0;
            color: rgba(255,255,255,.86);
            font-size: clamp(1rem, 1.6vw, 1.18rem);
            line-height: 1.7;
        }

        .hero-points {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 26px;
        }

        .hero-point {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            color: #fff;
            font-size: .9rem;
            font-weight: 600;
        }

        .hero-point .material-icons { font-size: 18px; }

        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: -58px auto 36px;
            position: relative;
            z-index: 5;
            width: min(920px, calc(100% - 32px));
            padding: 18px;
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 22px;
            background: rgba(255,255,255,.94);
            box-shadow: 0 18px 50px rgba(27, 54, 91, .14);
            backdrop-filter: blur(12px);
        }

        .quick-actions .btn-large {
            height: 48px;
            line-height: 48px;
            border-radius: 12px;
            box-shadow: none;
            text-transform: none;
            font-weight: 700;
            letter-spacing: 0;
        }

        .quick-actions .btn-large:hover {
            box-shadow: 0 10px 24px rgba(21, 101, 192, .22);
            transform: translateY(-1px);
        }

        .discover-section {
            margin-top: 26px;
        }

        .section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: clamp(1.65rem, 3vw, 2.35rem);
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .section-heading p {
            max-width: 520px;
            margin: 6px 0 0;
            color: var(--muted);
            line-height: 1.65;
        }

        .search-panel {
            margin-bottom: 26px;
            padding: 10px 22px 1px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 8px 30px rgba(23, 43, 77, .06);
        }

        .search-panel .input-field { margin-bottom: 8px; }
        .search-panel .material-icons.prefix { color: var(--brand); }
        .search-panel input[type=text]:focus:not([readonly]) { border-bottom: 1px solid var(--brand); box-shadow: 0 1px 0 0 var(--brand); }
        .search-panel input[type=text]:focus:not([readonly]) + label { color: var(--brand); }

        .agent-card {
            height: 100%;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: var(--surface);
            box-shadow: 0 10px 30px rgba(23, 43, 77, .07);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .agent-card:hover {
            transform: translateY(-7px);
            border-color: #cbdcf3;
            box-shadow: 0 22px 48px rgba(23, 43, 77, .14);
        }

        .agent-card .card-image { overflow: hidden; }

        .agent-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .agent-card:hover .agent-image { transform: scale(1.045); }

        .card-content { padding: 20px !important; }

        .agent-card .card-title {
            color: var(--ink);
            font-size: 1.28rem !important;
            font-weight: 800 !important;
            letter-spacing: -.02em;
        }

        .agent-card .card-content p {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 10px;
            color: var(--muted);
            line-height: 1.55;
        }

        .agent-card .card-content .material-icons { color: var(--brand); }

        .card-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px !important;
            border-top: 1px solid var(--line) !important;
            background: #fbfdff;
        }

        .rating-stars {
            color: #ffb300;
            font-size: 19px;
            letter-spacing: 1px;
        }

        .agent-card .card-action a {
            margin: 0 !important;
            padding: 8px 13px;
            border-radius: 9px;
            background: var(--brand-soft);
            color: var(--brand-dark) !important;
            font-size: .84rem;
            font-weight: 800;
            text-transform: none !important;
        }

        #agentContainer {
            display: flex;
            flex-wrap: wrap;
        }

        #agentContainer > .col { margin-bottom: 22px; }

        #noResults {
            display: none;
            padding: 22px;
            border: 1px dashed #ccd8e8;
            border-radius: 16px;
            background: #fff;
        }

        .pagination-wrap {
            margin-top: 10px;
            padding-top: 8px;
        }

        .pagination li {
            margin: 0 3px;
            border-radius: 9px;
        }

        .pagination li.active { background: var(--brand) !important; }
        .pagination li a { border-radius: 9px; }

        @media (max-width: 700px) {
            .page-shell { padding-top: 12px; }
            .laundry-hero { margin-top: 0; padding: 32px 24px 78px; border-radius: 0 0 28px 28px; }
            .hero-logo { width: min(260px, 70vw); }
            .quick-actions { margin-top: -55px; width: calc(100% - 24px); padding: 12px; border-radius: 18px; }
            .quick-actions .btn-large { width: 100%; }
            .section-heading { display: block; }
            .search-panel { padding-inline: 12px; }
            .agent-image { height: 210px; }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="page-shell">
        <div class="container">
            <section class="laundry-hero">
                <div class="hero-content">
                    <span class="hero-kicker"><i class="material-icons tiny">local_laundry_service</i> Laundry on demand</span>
                    <img src="img/banner.png" class="hero-logo" alt="Laundryku">
                    <h1 class="hero-title">Laundry beres tanpa harus keluar rumah.</h1>
                    <p class="hero-copy">Temukan agen laundry, lakukan pemesanan, dan pantau status cucian dari satu tempat. Lebih praktis untuk rutinitas yang tidak punya waktu menunggu.</p>
                    <div class="hero-points">
                        <span class="hero-point"><i class="material-icons">verified</i> Agen terverifikasi</span>
                        <span class="hero-point"><i class="material-icons">location_on</i> Cari berdasarkan lokasi</span>
                        <span class="hero-point"><i class="material-icons">track_changes</i> Pantau status cucian</span>
                    </div>
                </div>
            </section>

            <div class="quick-actions">
                <?php if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) : ?>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="pelanggan.php">Profil Saya</a>
                    <?php 
                    $idPelanggan = $_SESSION['pelanggan'];
                    $cek = mysqli_query($connect, "SELECT * FROM cucian WHERE id_pelanggan = $idPelanggan AND status_cucian != 'Selesai'");
                    $status = mysqli_num_rows($cek) > 0 ? "Status Cucian<i class='material-icons right'>notifications_active</i>" : "Status Cucian";

                    $cek = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_pelanggan = $idPelanggan AND rating = 0 OR komentar = ''");
                    $transaksi = mysqli_num_rows($cek) > 0 ? "Riwayat Transaksi<i class='material-icons right'>notifications_active</i>" : "Riwayat Transaksi";
                    ?>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php"><?= $transaksi ?></a>
                <?php elseif (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) : ?>
                    <?php
                    $idAgen = $_SESSION['agen'];
                    $cek = mysqli_query($connect, "SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'");
                    $status = mysqli_num_rows($cek) > 0 ? "Status Cucian<i class='material-icons right'>notifications_active</i>" : "Status Cucian";
                    ?>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="agen.php">Profil Saya</a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="status.php"><?= $status ?></a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                <?php elseif (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) : ?>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="admin.php">Profil Saya</a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="status.php">Status Cucian</a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="transaksi.php">Riwayat Transaksi</a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="list-agen.php">Data Agen</a>
                    <a class="btn-large waves-effect waves-light blue darken-3" href="list-pelanggan.php">Data Pelanggan</a>
                <?php else : ?>
                    <a href="registrasi.php" class="btn-large waves-effect waves-light blue darken-3">Daftar & Mulai Sekarang</a>
                    <a href="login.php" class="btn-large waves-effect waves-light white blue-text text-darken-3">Masuk</a>
                <?php endif ?>
            </div>

            <section class="discover-section">
                <div class="section-heading">
                    <div>
                        <h2>Temukan laundry yang tepat</h2>
                        <p>Cari agen berdasarkan nama laundry atau kota, lalu cek detail layanan dan rating sebelum memesan.</p>
                    </div>
                </div>

                <div class="search-panel">
                    <div class="row" style="margin-bottom:0;">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" id="keyword" placeholder="Contoh: Laundry Bintaro atau Tangerang..." class="validate" autocomplete="off">
                            <label for="keyword">Cari laundry atau kota</label>
                        </div>
                    </div>
                </div>

                <div id="noResults" class="row grey-text center">
                    <div class="col s12">
                        <i class="material-icons medium">search_off</i>
                        <h5>Tidak ada hasil yang ditemukan</h5>
                        <p>Coba gunakan nama laundry atau kota yang berbeda.</p>
                    </div>
                </div>

                <div class="row" id="agentContainer">
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
                                        <i class="material-icons tiny">location_on</i>
                                        <span><?= $dataAgen["alamat"] ?>, <?= $dataAgen["kota"] ?></span>
                                    </p>
                                    <p>
                                        <i class="material-icons tiny">phone</i>
                                        <span><?= $dataAgen["telp"] ?></span>
                                    </p>
                                </div>
                                <div class="card-action">
                                    <div class="rating-stars" aria-label="Rating laundry">
                                        <?php
                                        $rating = round($dataAgen['rating']);
                                        $rating = max(0, min(5, $rating));
                                        echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                                        ?>
                                    </div>
                                    <a href="detail-agen.php?id=<?= $dataAgen['id_agen'] ?>">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="row center pagination-wrap">
                    <ul class="pagination">
                        <?php if($halamanAktif > 1) : ?>
                            <li class="waves-effect">
                                <a href="#!" data-page="<?= $halamanAktif - 1 ?>"><i class="material-icons">chevron_left</i></a>
                            </li>
                        <?php endif; ?>

                        <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                            <li class="waves-effect <?= $i == $halamanAktif ? 'active blue' : '' ?>">
                                <a href="#!" data-page="<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if($halamanAktif < $jumlahHalaman) : ?>
                            <li class="waves-effect">
                                <a href="#!" data-page="<?= $halamanAktif + 1 ?>"><i class="material-icons">chevron_right</i></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="materialize/js/materialize.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/scriptAjax.js"></script>
</body>
</html>
