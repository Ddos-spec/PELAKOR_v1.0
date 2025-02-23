<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

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

$transactions = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html'; ?>
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
                            <th>Detail</th>
                        <?php endif; ?>
                        <th>Rating</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaksi): ?>
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
                            <td>
                                <?php 
                                try {
                                    $packagePrice = getHargaPaket($transaksi["jenis"], $transaksi["id_agen"], $connect);
                                    if ($packagePrice <= 0) {
                                        throw new Exception("Invalid package price");
                                    }
                                    echo "Rp " . number_format($packagePrice, 0, ',', '.');
                                } catch (Exception $e) {
                                    error_log("Error getting package price: " . $e->getMessage());
                                    echo "Rp 0";
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                try {
                                    $itemTotal = getTotalPerItem($transaksi["item_type"] ?? '', $transaksi["id_agen"], $connect);
                                    if ($itemTotal <= 0) {
                                        throw new Exception("Invalid item total");
                                    }
                                    echo "Rp " . number_format($itemTotal, 0, ',', '.');
                                } catch (Exception $e) {
                                    error_log("Error calculating item total: " . $e->getMessage());
                                    echo "Rp 0";
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                try {
                                    $totalPrice = calculateTotalHarga($transaksi, $connect);
                                    if ($totalPrice <= 0) {
                                        throw new Exception("Invalid total price");
                                    }
                                    echo "Rp " . number_format($totalPrice, 0, ',', '.');
                                } catch (Exception $e) {
                                    error_log("Error calculating total price: " . $e->getMessage());
                                    echo "Rp 0";
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($transaksi["tgl_mulai"]) ?></td>
                            <td><?= htmlspecialchars($transaksi["tgl_selesai"]) ?></td>
                            <?php if ($login === "Agen"): ?>
                                <td>
                                    <a href="invoice.php?id=<?= $transaksi['kode_transaksi'] ?>" class="btn red" target="_blank">Invoice</a>
                                </td>
                                <td>
                                    <button class="btn modal-trigger" data-target="modal-<?= htmlspecialchars($transaksi['id_cucian']) ?>">Detail</button>
                                </td>
                            <?php endif; ?>
                            <!-- Form review rating & komentar yang disatukan -->
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
                                    <fieldset class="bintang">
                                        <span class="starImg star-<?= htmlspecialchars($transaksi["rating"]) ?>"></span>
                                    </fieldset>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($transaksi["komentar"]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($login === "Agen"): ?>
    <!-- Modal Detail Order untuk Agen -->
    <?php foreach ($transactions as $transaksi): ?>
        <div id="modal-<?= htmlspecialchars($transaksi['id_cucian']) ?>" class="modal">
          <div class="modal-content">
            <h4>Detail Order #<?= htmlspecialchars($transaksi['id_cucian']) ?></h4>
            <table class="striped">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Quantity</th>
                  <th>Harga per Item</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $items = explode(', ', $transaksi['item_type']);
                foreach($items as $item) {
                    if(trim($item) == "") continue;
                    if(preg_match('/([^(]+)\((\d+)\)/', $item, $matches)) {
                        $itemName = trim($matches[1]);
                        $quantity = (int)$matches[2];
                        try {
                            $price = getPerItemPrice(strtolower($itemName), $transaksi['id_agen'], $connect);
                            if ($price <= 0) {
                                throw new Exception("Invalid price for item: $itemName");
                            }
                            $total = $price * $quantity;
                        } catch (Exception $e) {
                            error_log("Error in modal price calculation: " . $e->getMessage());
                            $price = 0;
                            $total = 0;
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($itemName) ?></td>
                            <td><?= $quantity ?></td>
                            <td>Rp <?= number_format($price, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <a href="#!" class="modal-close btn">Close</a>
          </div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <script>
        // Inisialisasi modal Materialize
        document.addEventListener('DOMContentLoaded', function() {
          var modals = document.querySelectorAll('.modal');
          M.Modal.init(modals);
        });
    </script>
    <?php
    // Penanganan pengiriman review
    if (isset($_POST["submitReview"])) {
        $kodeTransaksiRating = $_POST["kodeTransaksi"];
        
        // Cek apakah transaksi sudah direview sebelumnya
        $checkStmt = mysqli_prepare($connect, "SELECT rating FROM transaksi WHERE kode_transaksi = ?");
        mysqli_stmt_bind_param($checkStmt, "i", $kodeTransaksiRating);
        mysqli_stmt_execute($checkStmt);
        $resultCheck = mysqli_stmt_get_result($checkStmt);
        $dataReview = mysqli_fetch_assoc($resultCheck);
        mysqli_stmt_close($checkStmt);
        
        if ($dataReview && $dataReview["rating"] != 0) {
            echo "<script>
                    Swal.fire('Perhatian','Transaksi ini sudah direview','warning');
                  </script>";
        } else {
            $rating = $_POST["rating"];
            $komentar = htmlspecialchars($_POST["komentar"]);
            
            $updateReview = mysqli_prepare($connect, "UPDATE transaksi SET rating = ?, komentar = ? WHERE kode_transaksi = ?");
            mysqli_stmt_bind_param($updateReview, "isi", $rating, $komentar, $kodeTransaksiRating);
            
            if (mysqli_stmt_execute($updateReview)) {
                echo "<script>
                        Swal.fire('Penilaian Berhasil','Ulasan berhasil ditambahkan','success').then(function() {
                            window.location = 'transaksi.php';
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire('Error','Gagal menambahkan ulasan','error');
                      </script>";
            }
            mysqli_stmt_close($updateReview);
        }
    }
    
    include 'footer.php'; 
    ?>
</body>
</html>
