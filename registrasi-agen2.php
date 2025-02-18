<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAgen();

$idAgen = $_SESSION["agen"];

// ambil data agen
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

    <!-- header -->
    <?php include 'header.php' ?>
    <!-- end header -->

    <div class="row">

        <!-- term -->
        <div class="col s4 offset-s1">
            <div class="card">
                <div class="col center" style="margin:20px">
                    <img src="img/banner.png" alt="laundryku" width=100%/><br><br>
                    <span class="card-title black-text">Syarat dan Ketentuan :</span>
                </div>
                <div class="card-content">
                    <p>1.	Memiliki lokasi usaha laundry yang strategis dan teridentifikasi oleh google map</p>
                    <p>2.	Agen memiliki nama usaha serta logo perusahaan agar dapat diposting di website laundryKU</p>
                    <p>3.	Mampu memberikan layanan Laundry dengan kualitas prima dan harga yang bersaing</p>
                    <p>4.	Memiliki driver yang bersedia untuk melakukan penjemputan dan pengantaran terhadap laundry pelanggan</p>
                    <p>5.	Harga dari jenis laundry ditentukan berdasarkan berat per kilo (kg) ditambah dengan biaya ongkos kirim</p>
                    <p>6.	Bersedia untuk memberikan informasi kepada pelanggan mengenai harga Laundry Kiloan</p>
                    <p>7.	Bersedia untuk menerapkan sistem poin kepada pelanggan</p>
                    <p>8.	Bersedia memberikan kompensasi untuk setiap kemungkinan terjadinya seperti kehilangan pakaian atau kerusakan pakaian pada saat proses Laundry dilakukan</p>
                    <p>9.	Agen tidak diperkenankan untuk melakukan kerjasama dengan pihak Laundry lainnya</p>
                    <p>10.	Sebagai kompensasi atas kerjasama adalah sistem bagi hasil sebesar 5%, yang diperhitungkan dari setiap 7 hari</p>
                    <p>11.	Status agen secara otomatis dicabut apabila melanggar kesepakatan yang telah ditetapkan dalam surat perjanjian kerjasama ataupun agen ingin mengundurkan diri</p>
                </div>
                <div class="card-action">
                    <a href="term.php">Baca Selengkapnya</a>
                </div>
            </div>  
        </div>
        <!-- end term -->

    
        <!-- harga -->
        <div class="col s4 offset-s1">
            <h3 class="header light center">Data Harga</h3>
            <form action="" method="post">
                <div class="input-field inline">
                    <ul>
                        <li>
                            <label for="cuci">Cuci (Rp.)</label>
                            <input type="text" size=50 name="cuci" value="0">
                        </li>
                        <li>
                            <label for="setrika">Setrika (Rp.)</label>
                            <input type="text" size=50 name="setrika" value="0">
                        </li>
                        <li>
                            <label for="komplit">Cuci + Setrika (Rp.)</label>
                            <input type="text" size=50 name="komplit" value="0">
                        </li>
                        <li>
                            <div class="center">
                                <button class="btn-large blue darken-2" type="submit" name="submit">Simpan Harga</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
        <!-- end harga -->
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>
    <!-- end footer -->

</body>
</html>

<?php

function dataHarga($data) {
    global $connect, $idAgen;

    // Validate and sanitize input
    $cuci = filter_var($data["cuci"], FILTER_SANITIZE_NUMBER_INT);
    $setrika = filter_var($data["setrika"], FILTER_SANITIZE_NUMBER_INT);
    $komplit = filter_var($data["komplit"], FILTER_SANITIZE_NUMBER_INT);

    // Set default minimum prices if values are 0 or invalid
    $minPrice = 1000; // Minimum price in IDR
    $cuci = ($cuci < $minPrice) ? $minPrice : $cuci;
    $setrika = ($setrika < $minPrice) ? $minPrice : $setrika;
    $komplit = ($komplit < $minPrice) ? $minPrice : $komplit;

    // Validate prices
    if (!validasiHarga($cuci) || !validasiHarga($setrika) || !validasiHarga($komplit)) {
        return -1;
    }

    // Start transaction
    mysqli_begin_transaction($connect);

    try {
        // Prepare insert queries with prepared statements
        $queries = [
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('cuci', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('setrika', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('komplit', ?, ?)"
        ];

        $successCount = 0;
        foreach ($queries as $query) {
            $stmt = mysqli_prepare($connect, $query);
            if ($stmt) {
                // Bind the appropriate price based on query type
                $price = match($query) {
                    str_contains($query, 'cuci') => $cuci,
                    str_contains($query, 'setrika') => $setrika,
                    str_contains($query, 'komplit') => $komplit,
                    default => 0
                };
                
                mysqli_stmt_bind_param($stmt, "ii", $idAgen, $price);
                if (mysqli_stmt_execute($stmt)) {
                    $successCount++;
                } else {
                    throw new Exception(mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception(mysqli_error($connect));
            }
        }

        // Commit transaction
        mysqli_commit($connect);
        return $successCount;

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($connect);
        error_log("Error in dataHarga: " . $e->getMessage());
        return -1;
    }
}

if (isset($_POST["submit"])) {
    $result = dataHarga($_POST);
    
    if ($result > 0) {
        echo "
            <script>
                Swal.fire({
                    title: 'Pendaftaran Berhasil',
                    text: 'Data harga berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>
        ";
    } else {
        $errorMsg = ($result == -1) ? "Terjadi kesalahan saat menyimpan data harga" : "Data harga tidak valid";
        
        echo "
            <script>
                Swal.fire({
                    title: 'Gagal',
                    text: '$errorMsg',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>
        ";
        
        if($result == -1) {
            error_log("Database error: " . mysqli_error($connect));
        }
    }
}

?>