<?php 

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekPelanggan();

$idPelanggan = $_SESSION["pelanggan"];
$data = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
$data = mysqli_fetch_assoc($data);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Data Pengguna - <?= $data["nama"] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.7.3/dist/css/uikit.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.7.3/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.7.3/dist/js/uikit-icons.min.js"></script>
    <style>
        .center-text { text-align: center; }
        .profile-pic { display: block; margin: 0 auto; border-radius: 50%; width: 150px; height: 150px; }
        .container { max-width: 600px; margin: 20px auto; }
        .button-group { display: flex; justify-content: center; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="uk-container uk-margin-large-top">
        <h3 class="uk-heading-line uk-text-center"><span>DATA PENGGUNA</span></h3>
        <form action="" method="post" enctype="multipart/form-data" class="uk-form-stacked uk-card uk-card-default uk-card-body uk-padding">
            <div class="uk-text-center">
                <img src="img/pelanggan/<?= $data['foto'] ?>" class="profile-pic uk-box-shadow-medium" alt="Foto Profil">
            </div>
            <div class="uk-margin">
                <div uk-form-custom="target: true">
                    <input type="file" name="foto" id="foto">
                    <button class="uk-button uk-button-secondary" type="button" tabindex="-1">Pilih Foto</button>
                    <input class="uk-input uk-form-width-medium" type="text" placeholder="Tidak ada file terpilih" disabled>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="nama">Nama</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" id="nama" name="nama" value="<?= $data['nama'] ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="email">Email</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" id="email" name="email" value="<?= $data['email'] ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="telp">No Telp</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" id="telp" name="telp" value="<?= $data['telp'] ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="kota">Kota / Kabupaten</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" id="kota" name="kota" value="<?= $data['kota'] ?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="alamat">Alamat</label>
                <div class="uk-form-controls">
                    <textarea id="alamat" name="alamat" class="uk-textarea"><?= $data['alamat'] ?></textarea>
                </div>
            </div>
            <div class="button-group">
                <button class="uk-button uk-button-primary uk-box-shadow-hover-large" type="submit" name="ubah-data">Simpan Data</button>
                <a class="uk-button uk-button-secondary uk-box-shadow-hover-large" href="ganti-kata-sandi.php">Ganti Kata Sandi</a>
                <a class="uk-button uk-button-danger uk-box-shadow-hover-large" href="logout.php">Logout</a>
            </div>
        </form>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>


<?php

function uploadFoto(){
    //data foto
    $ukuranFile = $_FILES["foto"]["size"];
    $temp = $_FILES["foto"]["tmp_name"];
    $namaFile = $_FILES["foto"]["name"];
    $error = $_FILES["foto"]["error"];

    if ($namaFile == NULL){
        return NULL;
        exit;
    }

    //cek apakah file adalah gambar
    $ekstensiGambarValid = ['jpg','jpeg','png'];
    // explode = memecah string menjadi array (dg pemisah delimiter)
    $ekstensiGambar = explode('.',$namaFile);
    //mengambil ekstensi gambar yg paling belakang dg strltolower (mengecilkan semua huruf)
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    //CEK $ekstensiGambar ada di array $ekstensiGambarValid
    if ( !in_array($ekstensiGambar,$ekstensiGambarValid) ){
        echo "
            <script>
                Swal.fire('Upload Gagal','Masukan Ekstensi Gambar Yang Valid','warning');
            </script>
        ";
        return false;
    }

    //CEK ukuran file
    if ( $ukuranFile > 3000000 ) {
        echo "
            <script>
                Swal.fire('Upload Gagal','Ukuran File Gambar Terlalu Besar','warning');
            </script>
        ";
        return false;
    }

    //LOLOS CEK BROOO
    //generate nama baru random
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($temp,'img/pelanggan/'.$namaFileBaru);

    return $namaFileBaru;
}

if ( isset($_POST["ubah-data"]) ){

    // mengambil pelanggan
    $nama = htmlspecialchars($_POST["nama"]);
    $email = htmlspecialchars($_POST["email"]);
    $telp = htmlspecialchars($_POST["telp"]);
    $kota = htmlspecialchars($_POST["kota"]);
    $alamat = htmlspecialchars($_POST["alamat"]);
    $foto = uploadFoto();

    if ($foto == NULL){
        $foto = $data["foto"];
    }

    //var_dump($foto);die;

    // validasi
    validasiNama($nama);
    validasiEmail($email);
    validasiTelp($telp);
    validasiNama($kota);
    
    $query = "UPDATE pelanggan SET
        nama = '$nama',
        email = '$email',
        telp = '$telp',
        kota = '$kota',
        alamat = '$alamat',
        foto = '$foto'
        WHERE id_pelanggan = $idPelanggan
    ";

    mysqli_query($connect,$query);

    $hasil = mysqli_affected_rows($connect);

    if ( $hasil > 0 ){

        // panggis isi db
        $pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan");
        $pelanggan = mysqli_fetch_assoc($pelanggan);

        // mengganti session
        $_SESSION["pelanggan"] = $pelanggan["id_pelanggan"];
        echo "
            <script>
                Swal.fire('Data Berhasil Di Update','','success').then(function(){
                    window.location = 'pelanggan.php';
                });
            </script>
        ";
    }else{
        echo "
            <script>
                Swal.fire('Upload Gagal','','error');
            </script>
        ";
    }
}

?>
