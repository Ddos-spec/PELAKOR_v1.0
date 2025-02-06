<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// cek apakah admin sudah login
cekAdmin();

// ambil data transaksi
$query = mysqli_query($connect, "SELECT * FROM transaksi");
$totalPendapatan = 0;
$jumlahTransaksi = mysqli_num_rows($query);

while ($transaksi = mysqli_fetch_assoc($query)) {
    $totalPendapatan += $transaksi['total_bayar'];
}

$rataRataPendapatan = $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html'; ?>
    <title>Laporan Keuangan</title>
    <style>
        .card {
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .card-content h4 {
            font-size: 20px;
            margin: 0;
        }
        .filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h3 class="header col s12 light center">Laporan Keuangan</h3>
        <form class="filter-form" method="GET" action="laporan-keuangan.php">
            <div class="row">
                <div class="input-field col s6">
                    <input type="date" name="start_date" id="start_date">
                    <label for="start_date">Tanggal Mulai</label>
                </div>
                <div class="input-field col s6">
                    <input type="date" name="end_date" id="end_date">
                    <label for="end_date">Tanggal Selesai</label>
                </div>
            </div>
            <div class="center">
                <button class="btn blue darken-2" type="submit" name="filter"><i class="material-icons">filter_list</i> Filter</button>
            </div>
        </form>
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Total Pendapatan</span>
                        <h4>Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Jumlah Transaksi</span>
                        <h4><?= $jumlahTransaksi ?></h4>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Rata-rata Pendapatan per Transaksi</span>
                        <h4>Rp. <?= number_format($rataRataPendapatan, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="center">
            <a class="btn green darken-2" href="export-laporan.php?format=pdf"><i class="material-icons">picture_as_pdf</i> Export PDF</a>
            <a class="btn orange darken-2" href="export-laporan.php?format=excel"><i class="material-icons">grid_on</i> Export Excel</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>