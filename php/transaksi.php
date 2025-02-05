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
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Transasksi - <?= $login ?></title>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="uk-container">
        <h3 class="uk-heading-line uk-text-center"><span>Riwayat Transaksi Cucian</span></h3>
        <br>
        <?php if ($login == "Admin") : $query = mysqli_query($connect, "SELECT * FROM transaksi"); ?>
        <div>
            <table class="uk-table uk-table-divider uk-table-hover">
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
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($login == "Agen") : $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$idAgen'"); ?>
        <div>
            <table class="uk-table uk-table-divider uk-table-hover">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
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
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($login == "Pelanggan") : $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_pelanggan = $idPelanggan"); ?>
        <div>
            <table class="uk-table uk-table-divider uk-table-hover">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Agen</th>
                        <th>Total Item</th>
                        <th>Berat</th>
                        <th>Jenis</th>
                        <th>Total Bayar</th>
                        <th>Tanggal Pesan</th>
                        <th>Tanggal Selesai</th>
                        <th>Rating</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
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
                            <?php if ( $transaksi["rating"] == 0 ) : ?>
                                <form action="" method="post">
                                    <input type="hidden" value="<?= $kodeTransaksi ?>" name="kodeTransaksi">
                                    <select class="uk-select" name="rating">
                                        <option disabled>Rating</option>
                                        <option value="2">1</option>
                                        <option value="4">2</option>
                                        <option value="6">3</option>
                                        <option value="8">4</option>
                                        <option value="10">5</option>
                                    </select>
                                    <div class="uk-text-center"><button class="uk-button uk-button-primary" type="submit" name="simpanRating"><i class="material-icons">send</i></button></div>
                                </form>
                            <?php else : ?>
                                <?php
                                    $star = mysqli_query($connect,"SELECT * FROM transaksi WHERE kode_transaksi = $kodeTransaksi");
                                    $star = mysqli_fetch_assoc($star);
                                    $star = ceil($star["rating"]);
                                ?>
                                <fieldset class="bintang"><span class="starImg star-<?= $star ?>"></span></fieldset>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ( $transaksi["komentar"] == "" ) : ?>
                                <form action="" method="post">
                                    <input type="hidden" value="<?= $kodeTransaksi ?>" name="kodeTransaksi">
                                    <textarea name="komentar" class="uk-textarea" rows="5" placeholder="Masukkan Komentar"></textarea>
                                    <div class="uk-text-center"><button class="uk-button uk-button-primary" type="submit" name="kirimKomentar"><i class="material-icons">send</i></button></div>
                                </form>
                            <?php else : ?>
                            <?= $transaksi["komentar"] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>

<?php

// jika pelanggan rating
if ( isset($_POST["simpanRating"]) ) {
    $rating = $_POST["rating"];
    $kodeTransaksiRating = $_POST["kodeTransaksi"];
    $stmt = $connect->prepare("UPDATE transaksi SET rating = ? WHERE kode_transaksi = ?");
    $stmt->bind_param("is", $rating, $kodeTransaksiRating);
    $stmt->execute();
    echo "
        <script>
            Swal.fire('Penilaian Berhasil','Rating Berhasil Di Tambahkan','success').then(function() {
                window.location = 'transaksi.php';
            });
        </script>
    ";
}

if ( isset($_POST["kirimKomentar"])) {
    $komentar = htmlspecialchars($_POST["komentar"]);
    $kodeTransaksiRating = $_POST["kodeTransaksi"];
    $stmt = $connect->prepare("UPDATE transaksi SET komentar = ? WHERE kode_transaksi = ?");
    $stmt->bind_param("si", $komentar, $kodeTransaksiRating);
    $stmt->execute();
    echo "
        <script>
            Swal.fire('Penilaian Berhasil','Feedback Berhasil Di Tambahkan','success').then(function() {
                window.location = 'transaksi.php';
            });
        </script>
    ";
}

?>