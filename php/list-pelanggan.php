<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAdmin();

// Konfigurasi pagination
$jumlahDataPerHalaman = 5;
$query = mysqli_query($connect, "SELECT * FROM pelanggan");
$jumlahData = mysqli_num_rows($query);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Menentukan halaman aktif
$halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;

// Data awal
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Fungsi memasukkan data di db ke array
$pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC LIMIT $awalData, $jumlahDataPerHalaman");

// Ketika tombol cari ditekan
if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM pelanggan WHERE 
        nama LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_pelanggan DESC
        LIMIT $awalData, $jumlahDataPerHalaman";
    $pelanggan = mysqli_query($connect, $query);

    $jumlahData = mysqli_num_rows($pelanggan);
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>List Pelanggan</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h3 class="uk-heading-line uk-text-center"><span>List Pelanggan</span></h3>
    <br>
    <form class="uk-form-inline uk-text-center" action="" method="post">
        <div class="uk-margin">
            <input class="uk-input" type="text" name="keyword" placeholder="Keyword">
            <button class="uk-button uk-button-primary" type="submit" name="cari"><i class="material-icons">search</i></button>
        </div>
    </form>
    <div class="uk-container">
        <ul class="uk-pagination uk-flex-center">
            <?php if ($halamanAktif > 1) : ?>
            <li><a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
            <li class="<?= $i == $halamanAktif ? 'uk-active' : '' ?>"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <?php if ($halamanAktif < $jumlahHalaman) : ?>
            <li><a href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a></li>
            <?php endif; ?>
        </ul>
        <table class="uk-table uk-table-divider uk-table-hover uk-table-responsive">
            <thead>
                <tr>
                    <th>ID Pelanggan</th>
                    <th>Nama</th>
                    <th>No Telp</th>
                    <th>Email</th>
                    <th>Kota</th>
                    <th>Alamat Lengkap</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pelanggan as $dataPelanggan) : ?>
                <tr>
                    <td><?= $dataPelanggan["id_pelanggan"] ?></td>
                    <td><?= $dataPelanggan["nama"] ?></td>
                    <td><?= $dataPelanggan["telp"] ?></td>
                    <td><?= $dataPelanggan["email"] ?></td>
                    <td><?= $dataPelanggan["kota"] ?></td>
                    <td><?= $dataPelanggan["alamat"] ?></td>
                    <td>
                        <a class="uk-button uk-button-danger" href="list-pelanggan.php?hapus=<?= $dataPelanggan['id_pelanggan'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ?')">
                            <i class="material-icons">delete</i>
                        </a>
                        <a class="uk-button uk-button-warning" href="reset-password.php?user_id=<?= $dataPelanggan['id_pelanggan'] ?>">
                            <i class="material-icons">lock_reset</i>
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php include "footer.php"; ?>
    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php

if (isset($_GET["hapus"])) {
    $idPelanggan = $_GET["hapus"];

    $query = mysqli_query($connect, "DELETE FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");

    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Data Pelanggan Berhasil Di Hapus', '', 'success').then(function() {
                    window.location = 'list-pelanggan.php';
                });
            </script>
        ";
    }
}

?>