<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAgen();

$idAgen = $_SESSION["agen"];

// Ambil data agen
$query = "SELECT * FROM agen WHERE id_agen = '$idAgen'";
$result = mysqli_query($connect, $query);
$agen = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include "headtags.html"; ?>
  <title>Registrasi Agen Lanjutan</title>
</head>
<body>
  <!-- Header and Footer removed as per request -->
    <div class="row">
        <!-- Syarat dan Ketentuan -->
        <div class="col s4 offset-s1">
            <div class="card">
                <div class="col center" style="margin:20px">
                    <img src="img/banner.png" alt="laundryku" width="100%"/><br><br>
                    <span class="card-title black-text">Syarat dan Ketentuan :</span>
                </div>
                <div class="card-content">
                    <p>1. Memiliki lokasi usaha laundry yang strategis dan teridentifikasi oleh google map</p>
                    <p>2. Agen memiliki nama usaha serta logo perusahaan agar dapat diposting di website laundryKU</p>
                    <p>3. Mampu memberikan layanan Laundry dengan kualitas prima dan harga yang bersaing</p>
                    <p>4. Memiliki driver yang bersedia untuk melakukan penjemputan dan pengantaran terhadap laundry pelanggan</p>
                    <p>5. Harga dari jenis laundry ditentukan berdasarkan berat per kilo (kg) ditambah dengan biaya ongkos kirim</p>
                    <p>6. Bersedia untuk memberikan informasi kepada pelanggan mengenai harga Laundry Kiloan</p>
                    <p>7. Bersedia untuk menerapkan sistem poin kepada pelanggan</p>
                    <p>8. Bersedia memberikan kompensasi untuk setiap kemungkinan terjadinya seperti kehilangan atau kerusakan pakaian</p>
                    <p>9. Agen tidak diperkenankan untuk melakukan kerjasama dengan pihak laundry lain</p>
                    <p>10. Sistem bagi hasil sebesar 5% setiap 7 hari</p>
                    <p>11. Status agen dicabut apabila melanggar kesepakatan</p>
                </div>
                <div class="card-action">
                    <a href="term.php">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        <!-- Peringatan Verifikasi -->
        <div class="col s4 offset-s1">
            <div class="card yellow lighten-2">
                <div class="card-content center">
                    <h4>Mohon Tunggu Verifikasi Admin</h4>
                    <p>Pendaftaran Anda sedang diproses. Silakan tunggu verifikasi dari admin.</p>
                </div>
            </div>
            <!-- Tombol untuk kembali ke Beranda -->
            <div class="center" style="margin-top:20px;">
                <a class="btn-large waves-effect waves-light blue darken-2" href="index.php">
                    <i class="material-icons left">home</i>Ke Beranda
                </a>
            </div>
        </div>
    </div>
  <!-- Footer removed as per request -->
</body>
</html>
