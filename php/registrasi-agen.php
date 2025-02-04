<?php

// mulai session
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// kalau sudah login
cekLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Registrasi Agen</title>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <div class="uk-container">
        <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
            <!-- term -->
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <div class="uk-text-center">
                        <img src="img/banner.png" alt="laundryku" width="100%" /><br><br>
                        <h3 class="uk-card-title">Syarat dan Ketentuan :</h3>
                    </div>
                    <div>
                        <p>1. Memiliki lokasi usaha laundry yang strategis dan teridentifikasi oleh google map</p>
                        <p>2. Agen memiliki nama usaha serta logo perusahaan agar dapat diposting di website laundryKU</p>
                        <p>3. Mampu memberikan layanan Laundry dengan kualitas prima dan harga yang bersaing</p>
                        <p>4. Memiliki driver yang bersedia untuk melakukan penjemputan dan pengantaran terhadap laundry pelanggan</p>
                        <p>5. Harga dari jenis laundry ditentukan berdasarkan berat per kilo (kg) ditambah dengan biaya ongkos kirim</p>
                        <p>6. Bersedia untuk memberikan informasi kepada pelanggan mengenai harga Laundry Kiloan</p>
                        <p>7. Bersedia untuk menerapkan sistem poin kepada pelanggan</p>
                        <p>8. Bersedia memberikan kompensasi untuk setiap kemungkinan terjadinya seperti kehilangan pakaian atau kerusakan pakaian pada saat proses Laundry dilakukan</p>
                        <p>9. Agen tidak diperkenankan untuk melakukan kerjasama dengan pihak Laundry lainnya</p>
                        <p>10. Sebagai kompensasi atas kerjasama adalah sistem bagi hasil sebesar 5%, yang diperhitungkan dari setiap 7 hari</p>
                        <p>11. Status agen secara otomatis dicabut apabila melanggar kesepakatan yang telah ditetapkan dalam surat perjanjian kerjasama ataupun agen ingin mengundurkan diri</p>
                    </div>
                    <div class="uk-card-footer">
                        <a href="term.php" class="uk-button uk-button-text">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <!-- end term -->

            <!-- regis -->
            <div>
                <h3 class="uk-heading-line uk-text-center"><span>DAFTAR SEBAGAI AGEN</span></h3>
                <form action="" method="post">
                    <div class="uk-margin">
                        <label for="namaLaundry">Nama Laundry</label>
                        <input class="uk-input" type="text" size=50 id="namaLaundry" name="namaLaundry" placeholder="Nama Laundry">
                    </div>
                    <div class="uk-margin">
                        <label for="namaPemilik">Nama Pemilik</label>
                        <input class="uk-input" type="text" size=50 id="namaPemilik" name="namaPemilik" placeholder="Nama Pemilik">
                    </div>
                    <div class="uk-margin">
                        <label for="telp">No Telp</label>
                        <input class="uk-input" type="text" size=50 id="telp" name="telp" placeholder="No Telp">
                    </div>
                    <div class="uk-margin">
                        <label for="email">E-mail</label>
                        <input class="uk-input" type="text" size=50 id="email" name="email" placeholder="Email">
                    </div>
                    <div class="uk-margin">
                        <label for="plat">Plat Driver</label>
                        <input class="uk-input" type="text" size=50 id="plat" name="platDriver" placeholder="Plat Driver">
                    </div>
                    <div class="uk-margin">
                        <label for="kota">Kota / Kabupaten</label>
                        <input class="uk-input" type="text" size=50 id="kota" name="kota" placeholder="Kota / Kabupaten">
                    </div>
                    <div class="uk-margin">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea class="uk-textarea" id="alamat" name="alamat" placeholder="Alamat Lengkap"></textarea>
                    </div>
                    <div class="uk-margin">
                        <label for="password">Password</label>
                        <input class="uk-input" type="password" name="password" placeholder="Password">
                    </div>
                    <div class="uk-margin">
                        <label for="repassword">Re-type Password</label>
                        <input class="uk-input" type="password" id="repassword" name="password2" placeholder="Re-type Password">
                    </div>
                    <div class="uk-text-center">
                        <button class="uk-button uk-button-primary uk-button-large" type="submit" name="daftar">Daftar</button>
                    </div>
                </form>
            </div>
            <!-- end regis -->
        </div>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>
    <!-- end footer -->

    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>

<?php

if (isset($_POST["daftar"])) {
    $namaLaundry = htmlspecialchars($_POST["namaLaundry"]);
    $namaPemilik = htmlspecialchars($_POST["namaPemilik"]);
    $telp = htmlspecialchars($_POST["telp"]);
    $email = htmlspecialchars($_POST["email"]);
    $platDriver = htmlspecialchars($_POST["platDriver"]);
    $kota = htmlspecialchars($_POST["kota"]);
    $alamat = htmlspecialchars($_POST["alamat"]);
    $password = htmlspecialchars($_POST["password"]);
    $password2 = htmlspecialchars($_POST["password2"]);

    // Validasi input
    validasiNama($namaLaundry);
    validasiNama($namaPemilik);
    validasiTelp($telp);
    validasiEmail($email);
    validasiNama($kota);
    validasiNama($platDriver);

    // Cek password
    if ($password !== $password2) {
        echo "
            <script>
                Swal.fire('Pendaftaran Gagal','Password tidak sama','error');
            </script>
        ";
        exit;
    }

    // Hash password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data
    $stmt = $connect->prepare("INSERT INTO agen (nama_laundry, nama_pemilik, telp, email, plat_driver, kota, alamat, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $namaLaundry, $namaPemilik, $telp, $email, $platDriver, $kota, $alamat, $password);
    if ($stmt->execute()) {
        echo "
            <script>
                Swal.fire('Pendaftaran Berhasil','Akun Agen Berhasil Dibuat','success').then(function(){
                    window.location = 'index.php';
                });
            </script>
        ";
    } else {
        echo "
            <script>
                Swal.fire('Pendaftaran Gagal','Terjadi kesalahan, coba lagi nanti','error');
            </script>
        ";
    }
}

?>