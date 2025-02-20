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
    // Jika belum ada berat, kembalikan 0 atau handle sesuai kebutuhan
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
                                    <button class="btn red">Invoice</button>
                                </td>
                            <?php else: ?>
                                <?php if (empty($transaksi["rating"]) || empty($transaksi["komentar"])): ?>
                                    <td colspan="2">
                                        <form method="post" action="">
                                            <input type="hidden" name="kode_transaksi" value="<?= $transaksi['kode_transaksi'] ?>">
                                            <select name="rating" class="browser-default" required>
                                                <option value="" disabled selected>Pilih Rating</option>
                                                <option value="1">⭐</option>
                                                <option value="2">⭐⭐</option>
                                                <option value="3">⭐⭐⭐</option>
                                                <option value="4">⭐⭐⭐⭐</option>
                                                <option value="5">⭐⭐⭐⭐⭐</option>
                                            </select>
                                            <textarea name="komentar" placeholder="Tulis ulasan Anda (maksimal 100 karakter)..." maxlength="100" oninput="countChars(this)" required></textarea>
                                            <small id="charCount">0/100 karakter</small>
                                            <script>
                                                function countChars(textarea) {
                                                    const charCount = textarea.value.length;
                                                    document.getElementById('charCount').textContent = `${charCount}/100 karakter`;
                                                    if (charCount > 100) {
                                                        textarea.value = textarea.value.substring(0, 100);
                                                    }
                                                }
                                            </script>
                                            <button type="submit" class="btn blue" name="submit_rating">Kirim Ulasan</button>
                                        </form>
                                    </td>
                                <?php else: ?>
                                    <td><?= htmlspecialchars($transaksi["rating"]) ?></td>
                                    <td><?= htmlspecialchars($transaksi["komentar"]) ?></td>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
// Handle rating submission
if (isset($_POST['submit_rating'])) {
    $kode_transaksi = $_POST['kode_transaksi'];
    $rating = intval($_POST['rating']);
    $komentar = mysqli_real_escape_string($connect, $_POST['komentar']);
    
    $update_query = "UPDATE transaksi SET rating = $rating, komentar = '$komentar' 
                    WHERE kode_transaksi = $kode_transaksi";
    
    if (mysqli_query($connect, $update_query)) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Ulasan Berhasil Dikirim!',
                text: 'Terima kasih atas ulasan Anda',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        </script>";
    } else {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengirim Ulasan',
                text: 'Silakan coba lagi',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}

include 'footer.php'; 
?>
</body>
</html>
