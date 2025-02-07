<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// cek apakah admin sudah login
cekAdmin();

// Cek apakah tombol filter sudah diklik
$filterApplied = isset($_GET['filter']);

$start_date = $filterApplied ? $_GET['start_date'] : '';
$end_date   = $filterApplied ? $_GET['end_date'] : '';

// Siapkan query dan variabel summary
$totalPendapatan   = 0;
$jumlahTransaksi   = 0;
$rataRataPendapatan = 0;
$result = false; // default

if ($filterApplied && $start_date && $end_date) {
    $query = "SELECT * FROM transaksi WHERE tgl_mulai BETWEEN '$start_date' AND '$end_date'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $jumlahTransaksi = mysqli_num_rows($result);

        // Menghitung total pendapatan dengan validasi
        while ($transaksi = mysqli_fetch_assoc($result)) {
            if (isset($transaksi['total_bayar']) && is_numeric($transaksi['total_bayar'])) {
                $totalPendapatan += $transaksi['total_bayar'];
            }
        }
        $rataRataPendapatan = $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;

        // Reset pointer result agar bisa dipakai lagi di tampilan tabel
        mysqli_data_seek($result, 0);
    } else {
        die("Query Error: " . mysqli_error($connect));
    }
}
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
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
                        <label for="start_date">Tanggal Mulai</label>
                    </div>
                </div>
                <div class="col s6">
                    <div class="input-field">
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
                        <label for="end_date">Tanggal Selesai</label>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" type="submit" name="filter">
                    <i class="material-icons">filter_list</i> Filter
                </button>
            </div>
        </form>

        <!-- Summary Cards -->
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

        <!-- Tombol Export -->
        <div class="text-center">
            <a class="btn btn-success" href="export-laporan.php?format=pdf"><i class="material-icons">picture_as_pdf</i> Export PDF</a>
            <a class="btn btn-warning" href="export-laporan.php?format=excel"><i class="material-icons">grid_on</i> Export Excel</a>
        </div>

        <!-- Tampilkan Tabel Hanya Jika Filter Sudah Diterapkan -->
        <?php if ($filterApplied && $start_date && $end_date && $result): ?>
        <div class="row">
            <div class="col s12">
                <table border="1" cellpadding="10" class="responsive-table centered">
                    <tr>
                        <td style="font-weight:bold;">Kode Transaksi</td>
                        <td style="font-weight:bold;">Agen</td>
                        <td style="font-weight:bold;">Pelanggan</td>
                        <td style="font-weight:bold;">Total Item</td>
                        <td style="font-weight:bold;">Berat</td>
                        <td style="font-weight:bold;">Jenis</td>
                        <td style="font-weight:bold;">Total Bayar</td>
                        <td style="font-weight:bold;">Tanggal Pesan</td>
                        <td style="font-weight:bold;">Tanggal Selesai</td>
                    </tr>
                    <?php while ($transaksi = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($transaksi["kode_transaksi"]) ?></td>
                        <td>
                            <?php
                                $temp = $transaksi["id_agen"];
                                $agenQ = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$temp'");
                                $agen = mysqli_fetch_assoc($agenQ);
                                echo htmlspecialchars($agen["nama_laundry"]);
                            ?>
                        </td>
                        <td>
                            <?php
                                $temp = $transaksi["id_pelanggan"];
                                $pelangganQ = mysqli_query($connect,"SELECT * FROM pelanggan WHERE id_pelanggan = '$temp'");
                                $pelanggan = mysqli_fetch_assoc($pelangganQ);
                                echo htmlspecialchars($pelanggan["nama"]);
                            ?>
                        </td>
                        <td>
                            <?php
                                $idCucian = $transaksi["id_cucian"];
                                $cucianQ = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
                                $cucian = mysqli_fetch_assoc($cucianQ);
                                echo htmlspecialchars($cucian["total_item"]);
                            ?>
                        </td>
                        <td><?= htmlspecialchars($cucian["berat"]) ?></td>
                        <td><?= htmlspecialchars($cucian["jenis"]) ?></td>
                        <td><?= number_format($transaksi["total_bayar"], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($transaksi["tgl_mulai"]) ?></td>
                        <td><?= htmlspecialchars($transaksi["tgl_selesai"]) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
