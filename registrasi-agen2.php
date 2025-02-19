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

// Check for existing prices for the current agent
$checkPricesQuery = "SELECT * FROM harga WHERE id_agen = '$idAgen'";
$checkPricesResult = mysqli_query($connect, $checkPricesQuery);
$existingPrices = mysqli_fetch_all($checkPricesResult, MYSQLI_ASSOC);

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
                <div class="card-action">
                    <a href="term.php">Baca Selengkapnya</a>
                </div>
            </div>  
        </div>
        <!-- end term -->

        <!-- harga -->
        <div class="col s4 offset-s1">
            <div class="card price-card">
                <div class="card-content">
                    <h3 class="header light center">Data Harga</h3>
                    <form action="" method="post">
                        <!-- Package Prices -->
                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="cuci" name="cuci" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="cuci">Harga Cuci (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">iron</i>
                            <input type="number" id="setrika" name="setrika" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="setrika">Harga Setrika (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="komplit" name="komplit" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="komplit">Harga Cuci + Setrika (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <!-- Per-Item Prices -->
                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="harga_baju" name="harga_baju" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="harga_baju">Harga Baju (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="harga_celana" name="harga_celana" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="harga_celana">Harga Celana (Rp)</label>
                            <span class="helperText" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="harga_jaket" name="harga_jaket" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="harga_jaket">Harga Jaket (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="harga_karpet" name="harga_karpet" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="harga_karpet">Harga Karpet (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">local_laundry_service</i>
                            <input type="number" id="harga_pakaian_khusus" name="harga_pakaian_khusus" class="validate price-input" 
                                   value="0" required min="1000">
                            <label for="harga_pakaian_khusus">Harga Pakaian Khusus (Rp)</label>
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                        </div>

                        <!-- Submit Button -->
                        <div class="center">
                            <button class="btn-large waves-effect waves-light blue darken-2" type="submit" name="submit">
                                <i class="material-icons left">save</i>Simpan Harga
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
    $harga_baju = filter_var($data["harga_baju"], FILTER_SANITIZE_NUMBER_INT);
    $harga_celana = filter_var($data["harga_celana"], FILTER_SANITIZE_NUMBER_INT);
    $harga_jaket = filter_var($data["harga_jaket"], FILTER_SANITIZE_NUMBER_INT);
    $harga_karpet = filter_var($data["harga_karpet"], FILTER_SANITIZE_NUMBER_INT);
    $harga_pakaian_khusus = filter_var($data["harga_pakaian_khusus"], FILTER_SANITIZE_NUMBER_INT);
    
    // Set minimum price
    $minPrice = 1000;
    if ($cuci < $minPrice || $setrika < $minPrice || $komplit < $minPrice ||
        $harga_baju < $minPrice || $harga_celana < $minPrice || $harga_jaket < $minPrice ||
        $harga_karpet < $minPrice || $harga_pakaian_khusus < $minPrice) {
        return [
            'status' => false,
            'message' => "Harga minimum adalah Rp. " . number_format($minPrice)
        ];
    }

    // Check if prices already exist for this agent
    $check = mysqli_query($connect, "SELECT COUNT(*) as count FROM harga WHERE id_agen = $idAgen");
    $exists = mysqli_fetch_assoc($check)['count'] > 0;
    
    mysqli_begin_transaction($connect);
    
    try {
        if ($exists) {
            // Update existing prices
            $stmt = mysqli_prepare($connect, 
                "UPDATE harga SET harga = ?, harga_baju = ?, harga_celana = ?, harga_jaket = ?, harga_karpet = ?, harga_pakaian_khusus = ? WHERE id_agen = ?"
            );
        } else {
            // Insert new prices
            $stmt = mysqli_prepare($connect, 
                "INSERT INTO harga (harga, harga_baju, harga_celana, harga_jaket, harga_karpet, harga_pakaian_khusus, id_agen) VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
        }
        
        if (!$stmt) {
            throw new Exception(mysqli_error($connect));
        }

        // Bind parameters
        if ($exists) {
            mysqli_stmt_bind_param($stmt, "iiiiiii", $cuci, $harga_baju, $harga_celana, $harga_jaket, $harga_karpet, $harga_pakaian_khusus, $idAgen);
        } else {
            mysqli_stmt_bind_param($stmt, "iiiiiii", $cuci, $harga_baju, $harga_celana, $harga_jaket, $harga_karpet, $harga_pakaian_khusus, $idAgen);
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_commit($connect);
            return ['status' => true];
        } else {
            throw new Exception("Gagal menyimpan harga");
        }

    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Error in dataHarga: " . $e->getMessage());
        return [
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}

if (isset($_POST["submit"])) {
    $result = dataHarga($_POST);
    
    if ($result['status']) {
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
        echo "
            <script>
                Swal.fire({
                    title: 'Gagal',
                    text: '".$result['message']."',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>
        ";
        
        error_log("Database error: " . mysqli_error($connect));
    }
}