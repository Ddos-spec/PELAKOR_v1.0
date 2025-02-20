<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

// Fungsi untuk mendapatkan harga paket
function getHargaPaket($jenis, $idAgen) {
    global $connect;
    $jenisEscaped = mysqli_real_escape_string($connect, $jenis);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$jenisEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

// Fungsi untuk mendapatkan harga per item
function getPerItemPrice($item, $idAgen) {
    global $connect;
    $itemEscaped = mysqli_real_escape_string($connect, $item);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$itemEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

// Fungsi untuk menghitung total per item
function getTotalPerItem($itemType, $idAgen) {
    $total = 0;
    $items = explode(', ', $itemType);
    foreach ($items as $it) {
        if (trim($it) === "") continue;
        if (preg_match('/([^(]+)\((\d+)\)/', $it, $matches)) {
            $item = strtolower(trim($matches[1]));
            $qty = (int)$matches[2];
            $price = getPerItemPrice($item, $idAgen);
            $total += $price * $qty;
        }
    }
    return $total;
}

// Fungsi untuk menghitung total harga
function calculateTotalHarga($transaksi) {
    if (empty($transaksi["berat"])) {
        return 0;
    }
    $paket = getHargaPaket($transaksi["jenis"], $transaksi["id_agen"]) * $transaksi["berat"];
    $totalPerItem = getTotalPerItem($transaksi["item_type"] ?? '', $transaksi["id_agen"]);
    return $paket + $totalPerItem;
}

// Tentukan tipe login dan ambil data transaksi yang sudah selesai (payment_status = 'Paid')
if (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) {
    $login = "Admin";
    $query = mysqli_query($connect, "SELECT t.*, c.total_item, c.berat, c.jenis, c.item_type, c.tgl_mulai, c.tgl_selesai 
        FROM transaksi t 
        JOIN cucian c ON t.id_cucian = c.id_cucian 
        WHERE t.payment_status = 'Paid'");
} elseif (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) {
    $login = "Agen";
    $idAgen = intval($_SESSION["agen"]);
    $query = mysqli_query($connect, "SELECT t.*, c.total_item, c.berat, c.jenis, c.item_type, c.tgl_mulai, c.tgl_selesai 
        FROM transaksi t 
        JOIN cucian c ON t.id_cucian = c.id_cucian 
        WHERE t.id_agen = $idAgen AND t.payment_status = 'Paid'");
} else {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Laporan - <?= htmlspecialchars($login) ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h3 class="text-center my-4">Laporan Transaksi</h3>
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Dari</span>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                        <span class="input-group-text">Sampai</span>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">Harga Min</span>
                        <input type="number" class="form-control" id="min_price" name="min_price" placeholder="0">
                        <span class="input-group-text">Harga Max</span>
                        <input type="number" class="form-control" id="max_price" name="max_price" placeholder="0">
                        <button class="btn btn-primary" type="submit">Filter Harga</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success" onclick="window.print()">Cetak Laporan</button>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <?php if ($login != "Pelanggan"): ?>
                        <th>Agen</th>
                    <?php endif; ?>
                    <?php if ($login != "Agen"): ?>
                        <th>Pelanggan</th>
                    <?php endif; ?>
                    <th>Total Item</th>
                    <th>Berat</th>
                    <th>Jenis</th>
                    <th>Harga Paket</th>
                    <th>Total Per Item</th>
                    <th>Total Bayar</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Selesai</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transaksi = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaksi["kode_transaksi"]) ?></td>
                        <?php if ($login != "Pelanggan"):
                            $agenQuery = mysqli_query($connect, "SELECT nama_laundry FROM agen WHERE id_agen = " . intval($transaksi["id_agen"]));
                            $agenRow = mysqli_fetch_assoc($agenQuery);
                        ?>
                            <td><?= htmlspecialchars($agenRow["nama_laundry"] ?? '') ?></td>
                        <?php endif; ?>
                        <?php if ($login != "Agen"):
                            $pelQuery = mysqli_query($connect, "SELECT nama FROM pelanggan WHERE id_pelanggan = " . intval($transaksi["id_pelanggan"]));
                            $pelRow = mysqli_fetch_assoc($pelQuery);
                        ?>
                            <td><?= htmlspecialchars($pelRow["nama"] ?? '') ?></td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($transaksi["total_item"]) ?></td>
                        <td><?= htmlspecialchars($transaksi["berat"] ?? '-') ?></td>
                        <td><?= htmlspecialchars($transaksi["jenis"]) ?></td>
                        <td><?= "Rp " . number_format(getHargaPaket($transaksi["jenis"], $transaksi["id_agen"]), 0, ',', '.') ?></td>
                        <td><?= "Rp " . number_format(getTotalPerItem($transaksi["item_type"] ?? '', $transaksi["id_agen"]), 0, ',', '.') ?></td>
                        <td><?= "Rp " . number_format(calculateTotalHarga($transaksi), 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($transaksi["tgl_mulai"]) ?></td>
                        <td><?= htmlspecialchars($transaksi["tgl_selesai"]) ?></td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailModal" data-item="<?= htmlspecialchars($transaksi["item_type"]) ?>">Detail</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Detail Item -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="itemDetailList"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var itemType = button.getAttribute('data-item');
            var itemList = itemType.split(', ');
            var itemDetailList = document.getElementById('itemDetailList');
            itemDetailList.innerHTML = '';
            itemList.forEach(function (item) {
                var li = document.createElement('li');
                li.textContent = item;
                itemDetailList.appendChild(li);
            });
        });
    });
    </script>
</body>
</html>