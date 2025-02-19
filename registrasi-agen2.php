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
                            <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
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

    // Set default minimum prices if values are 0 or invalid
    $minPrice = 1000; // Minimum price in IDR
    $cuci = ($cuci < $minPrice) ? $minPrice : $cuci;
    $setrika = ($setrika < $minPrice) ? $minPrice : $setrika;
    $komplit = ($komplit < $minPrice) ? $minPrice : $komplit;
    $harga_baju = ($harga_baju < $minPrice) ? $minPrice : $harga_baju;
    $harga_celana = ($harga_celana < $minPrice) ? $minPrice : $harga_celana;
    $harga_jaket = ($harga_jaket < $minPrice) ? $minPrice : $harga_jaket;
    $harga_karpet = ($harga_karpet < $minPrice) ? $minPrice : $harga_karpet;
    $harga_pakaian_khusus = ($harga_pakaian_khusus < $minPrice) ? $minPrice : $harga_pakaian_khusus;

    // Validate prices
    if (!validasiHarga($cuci) || !validasiHarga($setrika) || !validasiHarga($komplit) ||
        !validasiHarga($harga_baju) || !validasiHarga($harga_celana) || !validasiHarga($harga_jaket) ||
        !validasiHarga($harga_karpet) || !validasiHarga($harga_pakaian_khusus)) {
        return -1;
    }

    // Start transaction
    mysqli_begin_transaction($connect);

    try {
        // Prepare insert queries with prepared statements
        $queries = [
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('cuci', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('setrika', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('komplit', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('baju', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('celana', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('jaket', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('karpet', ?, ?)",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('pakaian_khusus', ?, ?)"
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
                    str_contains($query, 'baju') => $harga_baju,
                    str_contains($query, 'celana') => $harga_celana,
                    str_contains($query, 'jaket') => $harga_jaket,
                    str_contains($query, 'karpet') => $harga_karpet,
                    str_contains($query, 'pakaian_khusus') => $harga_pakaian_khusus,
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