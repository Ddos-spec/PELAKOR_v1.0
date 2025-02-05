<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Validasi login
cekAdmin();

// Konfigurasi pagination
$jumlahDataPerHalaman = 5;
$stmt = $connect->prepare("SELECT * FROM agen");
$stmt->execute();
$result = $stmt->get_result();
$jumlahData = mysqli_num_rows($result);
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Menentukan halaman aktif
$halamanAktif = isset($_GET["page"]) ? $_GET["page"] : 1;

// Data awal
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Fungsi memasukkan data di db ke array
$agen = mysqli_query($connect, "SELECT * FROM agen ORDER BY id_agen DESC LIMIT $awalData, $jumlahDataPerHalaman");

// Ketika tombol cari ditekan
if (isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM agen WHERE 
        nama_laundry LIKE '%$keyword%' OR
        nama_pemilik LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_agen DESC
        LIMIT $awalData, $jumlahDataPerHalaman";
    $agen = mysqli_query($connect, $query);

    $jumlahData = mysqli_num_rows($agen);
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
    <title>Data Agen</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h3 class="uk-heading-line uk-text-center"><span>List Agen</span></h3>
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
                    <th>ID Agen</th>
                    <th>Nama Laundry</th>
                    <th>Nama Pemilik</th>
                    <th>No Telp</th>
                    <th>Email</th>
                    <th>Plat Driver</th>
                    <th>Kota</th>
                    <th>AlamatLengkap</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agen as $dataAgen) : ?>
                <tr>
                    <td><?= $dataAgen["id_agen"] ?></td>
                    <td><?= $dataAgen["nama_laundry"] ?></td>
                    <td><?= $dataAgen["nama_pemilik"] ?></td>
                    <td><?= $dataAgen["telp"] ?></td>
                    <td><?= $dataAgen["email"] ?></td>
                    <td><?= $dataAgen["plat_driver"] ?></td>
                    <td><?= $dataAgen["kota"] ?></td>
                    <td><?= $dataAgen["alamat"] ?></td>
                    <td>
                        <a class="uk-button uk-button-danger" href="list-agen.php?hapus=<?= $dataAgen['id_agen'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ?')">
                            <i class="material-icons">delete</i>
                        </a>
                        <a class="uk-button uk-button-warning" href="reset-password.php?user_id=<?= $dataAgen['id_agen'] ?>">
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
    $idAgen = $_GET["hapus"];

    $query = mysqli_query($connect, "DELETE FROM agen WHERE id_agen = '$idAgen'");

    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Data Agen Berhasil Di Hapus', '', 'success').then(function() {
                    window.location = 'list-agen.php';
                });
            </script>
        ";
    }
}

?>