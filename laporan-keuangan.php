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
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h3 class="header col s12 light text-center">Laporan Keuangan</h3>
        <form class="filter-form mb-4" method="GET" action="laporan-keuangan.php">
            <div class="row">
                <div class="col s6">
                    <div class="input-field">
                        <input type="date" name="start_date" id="start_date" class="form-control">
                        <label for="start_date">Tanggal Mulai</label>
                    </div>
                </div>
                <div class="col s6">
                    <div class="input-field">
                        <input type="date" name="end_date" id="end_date" class="form-control">
                        <label for="end_date">Tanggal Selesai</label>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" type="submit" name="filter"><i class="material-icons">filter_list</i> Filter</button>
            </div>
        </form>
        <div class="row">
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Pendapatan</h5>
                        <h4>Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Transaksi</h5>
                        <h4><?= $jumlahTransaksi ?></h4>
                    </div>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Pendapatan per Transaksi</h5>
                        <h4>Rp. <?= number_format($rataRataPendapatan, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a class="btn btn-success" href="export-laporan.php?format=pdf"><i class="material-icons">picture_as_pdf</i> Export PDF</a>
            <a class="btn btn-warning" href="export-laporan.php?format=excel"><i class="material-icons">grid_on</i> Export Excel</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
