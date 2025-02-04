<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAdmin();

//konfirgurasi pagination
$jumlahDataPerHalaman = 5;
$query = mysqli_query($connect,"SELECT * FROM pelanggan");
$jumlahData = mysqli_num_rows($query);
//ceil() = pembulatan ke atas
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

//menentukan halaman aktif
//$halamanAktif = ( isset($_GET["page"]) ) ? $_GET["page"] : 1; = versi simple
if ( isset($_GET["page"])){
    $halamanAktif = $_GET["page"];
}else{
    $halamanAktif = 1;
}

//data awal
$awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;

//fungsi memasukkan data di db ke array
$pelanggan = mysqli_query($connect,"SELECT * FROM pelanggan ORDER BY id_pelanggan DESC LIMIT $awalData, $jumlahDataPerHalaman");

//ketika tombol cari ditekan
if ( isset($_POST["cari"])) {
    $keyword = htmlspecialchars($_POST["keyword"]);
    $query = "SELECT * FROM pelanggan WHERE 
        nama LIKE '%$keyword%' OR
        kota LIKE '%$keyword%' OR
        email LIKE '%$keyword%' OR
        alamat LIKE '%$keyword%'
        ORDER BY id_pelanggan DESC
        LIMIT $awalData, $jumlahDataPerHalaman
    ";
    $pelanggan = mysqli_query($connect,$query);

    //konfirgurasi pagination
    $jumlahDataPerHalaman = 3;
    $jumlahData = mysqli_num_rows($pelanggan);
    //ceil() = pembulatan ke atas
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

    //menentukan halaman aktif
    //$halamanAktif = ( isset($_GET["page"]) ) ? $_GET["page"] : 1; = versi simple
    if ( isset($_GET["page"])){
        $halamanAktif = $_GET["page"];
    }else{
        $halamanAktif = 1;
    }
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

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <h3 class="uk-heading-line uk-text-center"><span>List Pelanggan</span></h3>
    <br>

    <!-- searching -->
    <form class="uk-form-inline uk-text-center" action="" method="post">
        <div class="uk-margin">
            <input class="uk-input" type="text" size=40 name="keyword" placeholder="Keyword">
            <button class="uk-button uk-button-primary" type="submit" name="cari"><i class="material-icons">search</i></button>
        </div>
    </form>
    <!-- end searching -->

    <div class="uk-container">
        <!-- pagination -->
        <ul class="uk-pagination uk-flex-center">
        <?php if( $halamanAktif > 1 ) : ?>
            <li>
                <a href="?page=<?= $halamanAktif - 1; ?>"><i class="material-icons">chevron_left</i></a>
            </li>
        <?php endif; ?>
        <?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
            <?php if( $i == $halamanAktif ) : ?>
                <li class="uk-active"><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
            <?php else : ?>
                <li><a href="?page=<?= $i; ?>"><?= $i ?></a></li>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if( $halamanAktif < $jumlahHalaman ) : ?>
            <li>
                <a href="?page=<?= $halamanAktif + 1; ?>"><i class="material-icons">chevron_right</i></a>
            </li>
        <?php endif; ?>
        </ul>
        <!-- end pagination -->

        <table class="uk-table uk-table-divider uk-table-hover">
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

    <!-- footer -->
    <?php include "footer.php"; ?>
    <!-- end footer -->

    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php

if (isset($_GET["hapus"])){
    // ambil id pelangan
    $idPelanggan = $_GET["hapus"];

    // hapus data
    $query = mysqli_query($connect, "DELETE FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");

    // langsung arahin ke halaman sebelumnya
    // buat alert setelah semua halaman tampil
    if ( mysqli_affected_rows($connect) > 0 ){
        echo "
            <script>
                Swal.fire('Data Pelanggan Berhasil Di Hapus','','success').then(function(){
                    window.location = 'list-pelanggan.php';
                });
            </script>
        ";
    }
}

?>