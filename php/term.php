<?php

session_start();
include 'connect-db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/uikit/dist/css/uikit.min.css" />
    <title>Term and Condition</title>
</head>
<body>
    
    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="uk-container">
        <div class="uk-section">
            <div class="uk-card uk-card-default uk-card-body">
                <div class="uk-text-center">
                    <img src="img/banner.png" alt="laundryku" width="100%"><br><br>
                    <h3 class="uk-card-title">Syarat dan Ketentuan :</h3>
                </div>
                <div class="uk-card-body">
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
                <div class="uk-card-footer uk-text-center">
                    <a href="index.php" class="uk-button uk-button-primary">HOME - LAUNDRYKU</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include 'footer.php' ?>
    <!-- end footer -->

    <script src="../node_modules/uikit/dist/js/uikit.min.js"></script>
    <script src="../node_modules/uikit/dist/js/uikit-icons.min.js"></script>
</body>
</html>