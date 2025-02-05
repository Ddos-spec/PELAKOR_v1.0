<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// harus agen yg kesini
cekAgen();

// ambil data agen
$idAgen = $_SESSION["agen"];
$stmt = $connect->prepare("SELECT * FROM agen WHERE id_agen = ?");
$stmt->bind_param("i", $idAgen);
$stmt->execute();
$result = $stmt->get_result();
$agen = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Profil Agen</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- data agen -->
    <div class="uk-container">
        <div class="uk-text-center">
            <a href="logout.php" class="uk-button uk-button-danger">Logout</a>
        </div>
        <h3 class="uk-heading-line uk-text-center"><span>Data Agen</span></h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="uk-text-center">
                <img src="img/agen/<?= $agen['foto'] ?>" class="uk-border-circle" width="150" height="150" alt="">
            </div>
            <div class="uk-margin">
                <div uk-form-custom="target: true">
                    <input type="file" name="foto" id="foto">
                    <input class="uk-input uk-form-width-medium" type="text" placeholder="Upload foto profil" disabled>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="namaLaundry">Nama Laundry</label>
                <input class="uk-input" type="text" id="namaLaundry" name="namaLaundry" value="<?= $agen['nama_laundry']?>">
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="namaPemilik">Nama Pemilik</label>
                <input class="uk-input" type="text" id="namaPemilik" name="namaPemilik" value="<?= $agen['nama_pemilik']?>">
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="email">Email</label>
                <input class="uk-input" type="text" id="email" name="email" value="<?= $agen['email']?>">
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="telp">No Telp</label>
                <input class="uk-input" type="text" id="telp" name="telp" value="<?= $agen['telp']?>">
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="plat">Plat Driver</label>
                <input class="uk-input" type="text" id="plat" name="platDriver" value="<?= $agen['plat_driver']?>">
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="kota">Kota / Kabupaten</label>
                <input class="uk-input" type="text" name="kota" value="<?= $agen['kota']?>">
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="alamat">Alamat</label>
                <textarea class="uk-textarea" name="alamat"><?= $agen['alamat']?></textarea>
            </div>
            <div class="uk-text-center">
                <button class="uk-button uk-button-primary" type="submit" name="submit">Simpan</button>
            </div>
        </form>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>
    <!-- end footer -->

    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>