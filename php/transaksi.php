<?php 

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekBelumLogin();

// Sesuaikan dengan jenis login
$login = "";
$idUser = null;
if (isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])) {
    $login = "Admin";
    $idUser = $_SESSION["admin"];
} else if (isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])) {
    $login = "Agen";
    $idUser = $_SESSION["agen"];
} else if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])) {
    $login = "Pelanggan";
    $idUser = $_SESSION["pelanggan"];
} else {
    echo "<script>document.location.href = 'login.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Transaksi - <?= htmlspecialchars($login) ?></title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="uk-container uk-margin-top">
        <h3 class="uk-heading-line uk-text-center"><span>Riwayat Transaksi Cucian</span></h3>
        <br>
        <div class="uk-overflow-auto">
            <table class="uk-table uk-table-divider uk-table-hover uk-table-responsive">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Agen</th>
                        <th>Pelanggan</th>
                        <th>Total Item</th>
                        <th>Berat</th>
                        <th>Jenis</th>
                        <th>Total Bayar</th>
                        <th>Tanggal Pesan</th>
                        <th>Tanggal Selesai</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $query = null;
                    if ($login == "Admin") {
                        $query = mysqli_query($connect, "SELECT * FROM transaksi");
                    } else if ($login == "Agen") {
                        $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$idUser'");
                    } else if ($login == "Pelanggan") {
                        $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_pelanggan = $idUser");
                    }

                    while ($transaksi = mysqli_fetch_assoc($query)) :
                        $kodeTransaksi = $transaksi["kode_transaksi"];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($kodeTransaksi) ?></td>
                        <td>
                            <?php
                                $temp = $transaksi["id_agen"];
                                $agen = mysqli_fetch_assoc(mysqli_query($connect, "SELECT nama_laundry FROM agen WHERE id_agen = '$temp'")) ?? ['nama_laundry' => 'Data tidak ditemukan'];
                                echo htmlspecialchars($agen["nama_laundry"]);
                            ?>
                        </td>
                        <td>
                            <?php
                                $temp = $transaksi["id_pelanggan"];
                                $pelanggan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT nama FROM pelanggan WHERE id_pelanggan = '$temp'")) ?? ['nama' => 'Data tidak ditemukan'];
                                echo htmlspecialchars($pelanggan["nama"]);
                            ?>
                        </td>
                        <td>
                            <?php
                                $idCucian = $transaksi["id_cucian"];
                                $cucian = mysqli_fetch_assoc(mysqli_query($connect, "SELECT total_item, berat, jenis FROM cucian WHERE id_cucian = $idCucian")) ?? ['total_item' => 'N/A', 'berat' => 'N/A', 'jenis' => 'N/A'];
                                echo htmlspecialchars($cucian["total_item"]);
                            ?>
                        </td>
                        <td><?= htmlspecialchars($cucian["berat"]) ?></td>
                        <td><?= htmlspecialchars($cucian["jenis"]) ?></td>
                        <td><?= htmlspecialchars($transaksi["total_bayar"]) ?></td>
                        <td><?= htmlspecialchars($transaksi["tgl_mulai"]) ?></td>
                        <td><?= htmlspecialchars($transaksi["tgl_selesai"]) ?></td>
                        <td>
                            <?php
                                $star = ceil($transaksi["rating"] ?? 0);
                                echo "<fieldset class='bintang'><span class='starImg star-$star'></span></fieldset>";
                            ?>
                        </td>
                        <td><?= htmlspecialchars($transaksi["komentar"]) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php

if (isset($_POST["simpanRating"])) {
    $rating = $_POST["rating"];
    $kodeTransaksiRating = $_POST["kodeTransaksi"];
    $stmt = $connect->prepare("UPDATE transaksi SET rating = ? WHERE kode_transaksi = ?");
    $stmt->bind_param("is", $rating, $kodeTransaksiRating);
    $stmt->execute();
    echo "
        <script>
            Swal.fire('Penilaian Berhasil', 'Rating Berhasil Ditambahkan', 'success').then(function() {
                window.location = 'transaksi.php';
            });
        </script>
    ";
}

if (isset($_POST["kirimKomentar"])) {
    $komentar = htmlspecialchars($_POST["komentar"]);
    $kodeTransaksiRating = $_POST["kodeTransaksi"];
    $stmt = $connect->prepare("UPDATE transaksi SET komentar = ? WHERE kode_transaksi = ?");
    $stmt->bind_param("si", $komentar, $kodeTransaksiRating);
    $stmt->execute();
    echo "
        <script>
            Swal.fire('Penilaian Berhasil', 'Feedback Berhasil Ditambahkan', 'success').then(function() {
                window.location = 'transaksi.php';
            });
        </script>
    ";
}

?>