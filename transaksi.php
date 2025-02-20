<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

// Tentukan tipe login dan ambil data transaksi yang sudah selesai (payment_status = 'Paid')
// Lakukan JOIN dengan tabel cucian untuk mendapatkan data order yang lengkap.
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
} elseif (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) {
    $login = "Pelanggan";
    $idPelanggan = intval($_SESSION["pelanggan"]);
    $query = mysqli_query($connect, "SELECT t.*, c.total_item, c.berat, c.jenis, c.item_type, c.tgl_mulai, c.tgl_selesai 
        FROM transaksi t 
        JOIN cucian c ON t.id_cucian = c.id_cucian 
        WHERE t.id_pelanggan = $idPelanggan AND t.payment_status = 'Paid'");
} else {
    header("Location: login.php");
    exit();
}

// Fungsi tambahan untuk menghitung harga
function getHargaPaket($jenis, $idAgen) {
    global $connect;
    $jenisEscaped = mysqli_real_escape_string($connect, $jenis);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$jenisEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

function getPerItemPrice($item, $idAgen) {
    global $connect;
    $itemEscaped = mysqli_real_escape_string($connect, $item);
    $q = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$itemEscaped'");
    $row = mysqli_fetch_assoc($q);
    return $row['harga'] ?? 0;
}

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

function calculateTotalHarga($transaksi) {
    if (empty($transaksi["berat"])) {
        return 0;
    }
    $paket = getHargaPaket($transaksi["jenis"], $transaksi["id_agen"]) * $transaksi["berat"];
    $totalPerItem = getTotalPerItem($transaksi["item_type"] ?? '', $transaksi["id_agen"]);
    return $paket + $totalPerItem;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Transaksi - <?= htmlspecialchars($login) ?></title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="row">
        <h3 class="header col s12 light center">Riwayat Transaksi</h3>
        <div class="col s10 offset-s1">
            <?php if ($login === "Agen" || $login === "Admin"): ?>
                <div class="right-align" style="margin-bottom: 20px;">
                    <a href="laporan.php" class="btn blue">Laporan</a>
                </div>
            <?php endif; ?>
            <table border="1" cellpadding="10" class="responsive-table centered">
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
                        <?php if ($login === "Agen"): ?>
                            <th>Invoice</th>
                        <?php elseif ($login === "Admin"): ?>
                            <th>Rating</th>
                            <th>Komentar</th>
                        <?php else: ?>
                            <th>Rating</th>
                            <th>Komentar</th>
                        <?php endif; ?>
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
                            <?php if ($login === "Agen"): ?>
                                <td>
<a href="invoice.php?id=<?= $transaksi['kode_transaksi'] ?>" class="btn red" target="_blank">Invoice</a>
                                </td>
                            <?php elseif ($login === "Admin"): ?>
                                <td>
                                    <?php if ($transaksi["rating"] == 0): ?>
                                        <form action="" method="post" class="review-form">
                                            <input type="hidden" value="<?= $transaksi['kode_transaksi'] ?>" name="kodeTransaksi">
                                            <div class="input-field">
                                                <select class="browser-default" name="rating" required>
                                                    <option value="" disabled selected>Pilih Rating</option>
                                                    <option value="2">1</option>
                                                    <option value="4">2</option>
                                                    <option value="6">3</option>
                                                    <option value="8">4</option>
                                                    <option value="10">5</option>
                                                </select>
                                            </div>
                                            <div class="input-field">
                                                <textarea name="komentar" class="materialize-textarea" placeholder="Masukkan Komentar" required></textarea>
                                            </div>
                                            <div class="center">
                                                <button class="btn blue darken-2" type="submit" name="submitReview">
                                                    Kirim Ulasan
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <?php
                                            $star = mysqli_query($connect, "SELECT * FROM transaksi WHERE kode_transaksi = " . $transaksi['kode_transaksi']);
                                            $star = mysqli_fetch_assoc($star);
                                            $star = $star["rating"];
                                        ?>
                                        <fieldset class="bintang">
                                            <span class="starImg star-<?= $star ?>"></span>
                                        </fieldset>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($transaksi["komentar"] != ""): ?>
                                        <?= htmlspecialchars($transaksi["komentar"]) ?>
                                    <?php endif; ?>
                                </td>
                            <?php else: ?>
                                <td>
                                    <?php if ($transaksi["rating"] == 0): ?>
                                        <form action="" method="post" class="review-form">
                                            <input type="hidden" value="<?= $transaksi['kode_transaksi'] ?>" name="kodeTransaksi">
                                            <div class="input-field">
                                                <select class="browser-default" name="rating" required>
                                                    <option value="" disabled selected>Pilih Rating</option>
                                                    <option value="2">1</option>
                                                    <option value="4">2</option>
                                                    <option value="6">3</option>
                                                    <option value="8">4</option>
                                                    <option value="10">5</option>
                                                </select>
                                            </div>
                                            <div class="input-field">
                                                <textarea name="komentar" class="materialize-textarea" placeholder="Masukkan Komentar" required></textarea>
                                            </div>
                                            <div class="center">
                                                <button class="btn blue darken-2" type="submit" name="submitReview">
                                                    Kirim Ulasan
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <?php
                                            $star = mysqli_query($connect, "SELECT * FROM transaksi WHERE kode_transaksi = " . $transaksi['kode_transaksi']);
                                            $star = mysqli_fetch_assoc($star);
                                            $star = $star["rating"];
                                        ?>
                                        <fieldset class="bintang">
                                            <span class="starImg star-<?= $star ?>"></span>
                                        </fieldset>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($transaksi["komentar"] != ""): ?>
                                        <?= htmlspecialchars($transaksi["komentar"]) ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
// Handle review submission
if (isset($_POST["submitReview"])) {
    $rating = $_POST["rating"];
    $komentar = htmlspecialchars($_POST["komentar"]);
    $kodeTransaksiRating = $_POST["kodeTransaksi"];

    $updateReview = mysqli_prepare($connect, "UPDATE transaksi SET rating = ?, komentar = ? WHERE kode_transaksi = ?");
    mysqli_stmt_bind_param($updateReview, "isi", $rating, $komentar, $kodeTransaksiRating);
    
    if (mysqli_stmt_execute($updateReview)) {
        echo "
            <script>
                Swal.fire('Penilaian Berhasil','Ulasan Berhasil Di Tambahkan','success').then(function() {
                    window.location = 'transaksi.php';
                });
            </script>
        ";
    } else {
        echo "
            <script>
                Swal.fire('Error','Gagal menambahkan ulasan','error');
            </script>
        ";
    }
}

include 'footer.php'; 
?>
</body>
</html>
