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
        <!-- Tambah filter di bagian atas tabel -->
        <div class="row">
            <div class="col s12">
                <div class="input-field inline">
                    <select id="filter_tipe" onchange="filterTipe(this.value)">
                        <option value="all">Semua Tipe</option>
                        <option value="kiloan">Kiloan</option>
                        <option value="satuan">Satuan</option>
                    </select>
                    <label>Filter Tipe Layanan</label>
                </div>
                <div class="right-align">
                    <a class="btn blue" onclick="exportData('pdf')">
                        <i class="material-icons left">picture_as_pdf</i>Export PDF
                    </a>
                    <a class="btn green" onclick="exportData('excel')">
                        <i class="material-icons left">grid_on</i>Export Excel
                    </a>
                </div>
            </div>
        </div>
        <?php if ($login == "Admin") : ?>
        <!-- Modifikasi query untuk paginasi -->
        <?php 
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $query = mysqli_query($connect, "SELECT c.*, dc.*, hs.*, h.harga as harga_kiloan 
            FROM cucian c 
            LEFT JOIN detail_cucian dc ON c.id_cucian = dc.id_cucian
            LEFT JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan
            LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
            LIMIT $limit OFFSET $offset");
        ?>
        <div class="col s10 offset-s1">
            <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold;">Kode Transaksi</td>
                    <td style="font-weight:bold;">Tipe Layanan</td>
                    <td style="font-weight:bold;">Detail Item/Kategori</td>
                    <td style="font-weight:bold;">Total Harga</td>
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
                            if($cucian["tipe_layanan"] == "kiloan") {
                                echo $cucian["estimasi_item"] . " item";
                            } else {
                                $detail = mysqli_query($connect, "SELECT hs.nama_item, dc.jumlah 
                                    FROM detail_cucian dc 
                                    JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan 
                                    WHERE dc.id_cucian = $idCucian");
                                while($item = mysqli_fetch_assoc($detail)) {
                                    echo $item["nama_item"] . " (" . $item["jumlah"] . ")<br>";
                                }
                            }
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
            </table>
        </div>
        <!-- Tambah pagination -->
        <div class="center">
            <ul class="pagination">
                <?php
                $total = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM cucian"));
                $pages = ceil($total / $limit);
                for($i = 1; $i <= $pages; $i++):
                ?>
                <li class="<?= $i==$page?'active':'' ?>">
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </div>
        <?php elseif ($login == "Agen") : $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_agen = '$idAgen'"); ?>
        <div class="col s10 offset-s1">
        <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold">Kode Transaksi</td>
                    <td style="font-weight:bold">Tipe Layanan</td>
                    <td style="font-weight:bold">Detail Item/Kategori</td>
                    <td style="font-weight:bold">Total Harga</td>
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
                            $idCucian = $transaksi["id_cucian"];
                            $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
                            $cucian = mysqli_fetch_assoc($cucian);
                            if($cucian["tipe_layanan"] == "kiloan") {
                                echo $cucian["estimasi_item"] . " item";
                            } else {
                                $detail = mysqli_query($connect, "SELECT hs.nama_item, dc.jumlah 
                                    FROM detail_cucian dc 
                                    JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan 
                                    WHERE dc.id_cucian = $idCucian");
                                while($item = mysqli_fetch_assoc($detail)) {
                                    echo $item["nama_item"] . " (" . $item["jumlah"] . ")<br>";
                                }
                            }
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
            </table>
        </div>
        <?php elseif ($login == "Pelanggan") : $query = mysqli_query($connect, "SELECT * FROM transaksi WHERE id_pelanggan = $idPelanggan"); ?>
        <div class="container">
            <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold">Kode Transaksi</td>
                    <td style="font-weight:bold">Tipe Layanan</td>
                    <td style="font-weight:bold">Detail Item/Kategori</td>
                    <td style="font-weight:bold">Total Harga</td>
                    <td style="font-weight:bold">Agen</td>
                    <td style="font-weight:bold">Total Item</td>
                    <td style="font-weight:bold">Berat</td>
                    <td style="font-weight:bold">Jenis</td>
                    <td style="font-weight:bold">Total Bayar</td>
                    <td style="font-weight:bold">Tanggal Pesan</td>
                    <td style="font-weight:bold">Tanggal Selesai</td>
                    <td style="font-weight:bold">Rating</td>
                    <td style="font-weight:bold">Feedback</td>
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
                            $idCucian = $transaksi["id_cucian"];
                            $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
                            $cucian = mysqli_fetch_assoc($cucian);
                            if($cucian["tipe_layanan"] == "kiloan") {
                                echo $cucian["estimasi_item"] . " item";
                            } else {
                                $detail = mysqli_query($connect, "SELECT hs.nama_item, dc.jumlah 
                                    FROM detail_cucian dc 
                                    JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan 
                                    WHERE dc.id_cucian = $idCucian");
                                while($item = mysqli_fetch_assoc($detail)) {
                                    echo $item["nama_item"] . " (" . $item["jumlah"] . ")<br>";
                                }
                            }
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
                                <select class="browser-default" name="rating" id="">
                                    <option disabled>Rating</option>
                                    <option value="2">1</option>
                                    <option value="4">2</option>
                                    <option value="6">3</option>
                                    <option value="8">4</option>
                                    <option value="10">5</option>
                                </select>
                                <div class="center"><button class="btn blue darken-2" type="submit" name="simpanRating"><i class="material-icons">send</i></button></div>
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
                                <textarea name="komentar" class="materialize-textarea" id="" cols="30" rows="10" placeholder="Masukkan Komentar"></textarea>
                                <div class="center"><button class="btn blue darken-2" type="submit" name="kirimKomentar"><i class="material-icons">send</i></button></div>
                            </form>
                        <?php else : ?>
                        <?= $transaksi["komentar"] ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>

<?php


// jika pelanggan rating
if ( isset($_POST["simpanRating"]) ){

    $rating = $_POST["rating"];
    $kodeTransaksiRating = $_POST["kodeTransaksi"];

    mysqli_query($connect, "UPDATE transaksi SET rating = $rating WHERE kode_transaksi = $kodeTransaksiRating");
    echo "
        <script>
            Swal.fire('Penilaian Berhasil','Rating Berhasil Di Tambahkan','success').then(function() {
                window.location = 'transaksi.php';
            });
        </script>
    ";
}

if ( isset($_POST["kirimKomentar"])){

    $komentar = htmlspecialchars($_POST["komentar"]);
    $kodeTransaksiRating = $_POST["kodeTransaksi"];

    mysqli_query($connect, "UPDATE transaksi SET komentar = '$komentar' WHERE kode_transaksi = $kodeTransaksiRating");
    echo "
        <script>
            Swal.fire('Penilaian Berhasil','Feedback Berhasil Di Tambahkan','success').then(function() {
                window.location = 'transaksi.php';
            });
        </script>
    ";
}

?>