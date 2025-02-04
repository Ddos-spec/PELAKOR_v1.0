<?php 

// session
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekBelumLogin();

// sesuaikan dengan jenis login
if(isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])){

    $idAdmin = $_SESSION["admin"];
    $login = "Admin";

}else if(isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])){

    $idAgen = $_SESSION["agen"];
    $login = "Agen";

}else if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])){

    $idPelanggan = $_SESSION["pelanggan"];
    $login = "Pelanggan";

}else {
    echo "
        <script>
            document.location.href = 'login.php';
        </script>
    ";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Transasksi - <?= $login ?></title>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="row">
        <h3 class="header col s12 light center">Riwayat Transaksi Cucian</h3>
        <br>
        <?php if ($login == "Admin") : $query = mysqli_query($connect, "SELECT * FROM transaksi"); ?>
        <div class="col s10 offset-s1">
            <table border=1 cellpadding=10 class="responsive-table centered">
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
                    <td style="font-weight:bold;">Rating</td>
                    <td style="font-weight:bold;">Komentar</td>
                </tr>
                <?php while ($transaksi = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?php echo $kodeTransaksi = $transaksi["kode_transaksi"] ?></td>
                    <td>
                        <?php
                            $temp = $transaksi["id_agen"];
                            $agen = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$temp'");
                            $agen = mysqli_fetch_assoc($agen);
                            echo $agen["nama_laundry"];
                        ?>
                    </td>
                    <td>
                        <?php
                            $temp = $transaksi["id_pelanggan"];
                            $pelanggan = mysqli_query($connect,"SELECT * FROM pelanggan WHERE id_pelanggan = '$temp'");
                            $pelanggan = mysqli_fetch_assoc($pelanggan);
                            echo $pelanggan["nama"];
                        ?>
                    </td>
                    <td>
                        <?php
                            $idCucian = $transaksi["id_cucian"];
                            $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
                            $cucian = mysqli_fetch_assoc($cucian);
                            echo $cucian["total_item"];
                        ?>
                    </td>
                    <td><?= $cucian["berat"] ?></td>
                    <td><?= $cucian["jenis"] ?></td>
                    <td><?= $transaksi["total_bayar"] ?></td>
                    <td><?= $transaksi["tgl_mulai"] ?></td>
                    <td><?= $transaksi["tgl_selesai"] ?></td>
                    <td>
                        <?php
                            $star = mysqli_query($connect,"SELECT * FROM transaksi WHERE kode_transaksi = $kodeTransaksi");
                            $star = mysqli_fetch_assoc($star);
                            $star = ceil($star["rating"]);
                        ?>
                        <fieldset class="bintang"><span class="starImg star-<?= $star ?>"></span></fieldset>
                    </td>
                    <td><?= $transaksi["komentar"] ?></td>
                    <td>
                        <?php if ($transaksi["status"] == "Complete") : ?>
                            <button type="button" id="finishButton" onclick="generateInvoice('<?= $kodeTransaksi ?>')">Finish</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php elseif ($login == "Agen") : $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$idAgen'"); ?>
        <div class="col s10 offset-s1">
        <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold">Kode Transaksi</td>
                    <td style="font-weight:bold">Pelanggan</td>
                    <td style="font-weight:bold">Total Item</td>
                    <td style="font-weight:bold">Berat</td>
                    <td style="font-weight:bold">Jenis</td>
                    <td style="font-weight:bold">Total Bayar</td>
                    <td style="font-weight:bold">Tanggal Pesan</td>
                    <td style="font-weight:bold">Tanggal Selesai</td>
                    <td style="font-weight:bold">Rating</td>
                    <td style="font-weight:bold">Komentar</td>
                </tr>
                <?php while ($transaksi = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?php echo $kodeTransaksi = $transaksi["kode_transaksi"] ?></td>
                    <td>
                        <?php
                            $temp = $transaksi["id_pelanggan"];
                            $pelanggan = mysqli_query($connect,"SELECT * FROM pelanggan WHERE id_pelanggan = '$temp'");
                            $pelanggan = mysqli_fetch_assoc($pelanggan);
                            echo $pelanggan["nama"];
                        ?>
                    </td>
                    <td>
                        <?php
                            $idCucian =
