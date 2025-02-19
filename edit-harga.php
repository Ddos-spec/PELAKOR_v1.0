<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// cek apakah sudah login sebagai agen
cekAgen();

// mengambil id agen di session
$idAgen = $_SESSION["agen"];

// mengambil data harga pada db
$cuci = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'cuci'");
$cuci = mysqli_fetch_assoc($cuci);
$setrika = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'setrika'");
$setrika = mysqli_fetch_assoc($setrika);
$komplit = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'komplit'");
$komplit = mysqli_fetch_assoc($komplit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Ubah Data Harga</title>
    <style>
        .price-input {
            font-size: 1.2rem;
            padding: 1rem;
            margin-top: 1rem;
        }
        .price-card {
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
        }
        .price-card .input-field {
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="container">
        <div class="card price-card">
            <div class="card-content">
                <h3 class="header light center">Ubah Data Harga</h3>
                
                <form action="" method="post">
                    <!-- Package Prices -->
                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="cuci" name="cuci" class="validate price-input" 
                               value="<?= $cuci['harga'] ?>" required min="1000">
                        <label for="cuci">Harga Cuci (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix">iron</i>
                        <input type="number" id="setrika" name="setrika" class="validate price-input" 
                               value="<?= $setrika['harga'] ?>" required min="1000">
                        <label for="setrika">Harga Setrika (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="komplit" name="komplit" class="validate price-input" 
                               value="<?= $komplit['harga'] ?>" required min="1000">
                        <label for="komplit">Harga Cuci + Setrika (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <!-- Per-Item Prices -->
                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="harga_baju" name="harga_baju" class="validate price-input" 
                               value="<?= $cuci['harga_baju'] ?? 0 ?>" required min="1000">
                        <label for="harga_baju">Harga Baju (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="harga_celana" name="harga_celana" class="validate price-input" 
                               value="<?= $cuci['harga_celana'] ?? 0 ?>" required min="1000">
                        <label for="harga_celana">Harga Celana (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="harga_jaket" name="harga_jaket" class="validate price-input" 
                               value="<?= $cuci['harga_jaket'] ?? 0 ?>" required min="1000">
                        <label for="harga_jaket">Harga Jaket (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="harga_karpet" name="harga_karpet" class="validate price-input" 
                               value="<?= $cuci['harga_karpet'] ?? 0 ?>" required min="1000">
                        <label for="harga_karpet">Harga Karpet (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix">local_laundry_service</i>
                        <input type="number" id="harga_pakaian_khusus" name="harga_pakaian_khusus" class="validate price-input" 
                               value="<?= $cuci['harga_pakaian_khusus'] ?? 0 ?>" required min="1000">
                        <label for="harga_pakaian_khusus">Harga Pakaian Khusus (Rp)</label>
                        <span class="helper-text" data-error="Harga minimal Rp 1000"></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="center">
                        <button class="btn-large waves-effect waves-light blue darken-2" type="submit" name="simpan">
                            <i class="material-icons left">save</i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php" ?>
    <!-- end footer -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Materialize components
            M.updateTextFields();
            
            // Add input validation
            const inputs = document.querySelectorAll('.price-input');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value < 1000) {
                        this.classList.add('invalid');
                    } else {
                        this.classList.remove('invalid');
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php

// fungsi mengubah harga
function ubahHarga($data) {
    global $connect, $idAgen;

    // Validate and sanitize input
    $hargaCuci = filter_var($data["cuci"], FILTER_SANITIZE_NUMBER_INT);
    $hargaSetrika = filter_var($data["setrika"], FILTER_SANITIZE_NUMBER_INT);
    $hargaKomplit = filter_var($data["komplit"], FILTER_SANITIZE_NUMBER_INT);
    $hargaBaju = filter_var($data["harga_baju"], FILTER_SANITIZE_NUMBER_INT);
    $hargaCelana = filter_var($data["harga_celana"], FILTER_SANITIZE_NUMBER_INT);
    $hargaJaket = filter_var($data["harga_jaket"], FILTER_SANITIZE_NUMBER_INT);
    $hargaKarpet = filter_var($data["harga_karpet"], FILTER_SANITIZE_NUMBER_INT);
    $hargaPakaianKhusus = filter_var($data["harga_pakaian_khusus"], FILTER_SANITIZE_NUMBER_INT);

    // Set default minimum prices if values are 0 or invalid
    $minPrice = 1000;
    $hargaCuci = ($hargaCuci < $minPrice) ? $minPrice : $hargaCuci;
    $hargaSetrika = ($hargaSetrika < $minPrice) ? $minPrice : $hargaSetrika;
    $hargaKomplit = ($hargaKomplit < $minPrice) ? $minPrice : $hargaKomplit;
    $hargaBaju = ($hargaBaju < $minPrice) ? $minPrice : $hargaBaju;
    $hargaCelana = ($hargaCelana < $minPrice) ? $minPrice : $hargaCelana;
    $hargaJaket = ($hargaJaket < $minPrice) ? $minPrice : $hargaJaket;
    $hargaKarpet = ($hargaKarpet < $minPrice) ? $minPrice : $hargaKarpet;
    $hargaPakaianKhusus = ($hargaPakaianKhusus < $minPrice) ? $minPrice : $hargaPakaianKhusus;

    // Validate prices
    if (!validasiHarga($hargaCuci) || !validasiHarga($hargaSetrika) || !validasiHarga($hargaKomplit) ||
        !validasiHarga($hargaBaju) || !validasiHarga($hargaCelana) || !validasiHarga($hargaJaket) ||
        !validasiHarga($hargaKarpet) || !validasiHarga($hargaPakaianKhusus)) {
        return -1;
    }

    // Start transaction
    mysqli_begin_transaction($connect);

    try {
        // Prepare update queries
        $queries = [
            "UPDATE harga SET harga = $hargaCuci WHERE jenis = 'cuci' AND id_agen = $idAgen",
            "UPDATE harga SET harga = $hargaSetrika WHERE jenis = 'setrika' AND id_agen = $idAgen",
            "UPDATE harga SET harga = $hargaKomplit WHERE jenis = 'komplit' AND id_agen = $idAgen",
            "UPDATE harga SET harga_baju = $hargaBaju WHERE id_agen = $idAgen",
            "UPDATE harga SET harga_celana = $hargaCelana WHERE id_agen = $idAgen",
            "UPDATE harga SET harga_jaket = $hargaJaket WHERE id_agen = $idAgen",
            "UPDATE harga SET harga_karpet = $hargaKarpet WHERE id_agen = $idAgen",
            "UPDATE harga SET harga_pakaian_khusus = $hargaPakaianKhusus WHERE id_agen = $idAgen"
        ];

        $successCount = 0;
        foreach ($queries as $query) {
            if (mysqli_query($connect, $query)) {
                $successCount++;
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
        error_log("Error in ubahHarga: " . $e->getMessage());
        return -1;
    }
}

// jika user menekan tombol simpan harga
if (isset($_POST["simpan"])) {
    $result = ubahHarga($_POST);
    
    if ($result > 0) {
        echo "
            <script>
                Swal.fire({
                    title: 'Success',
                    text: 'Prices updated successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'edit-harga.php';
                });
            </script>
        ";
    } else {
        $errorMsg = ($result == -1) ? "Invalid price values entered" : "Failed to update prices. Please try again.";
        
        echo "
            <script>
                Swal.fire({
                    title: 'Error',
                    text: '$errorMsg',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>
        ";
        
        if ($result == -1) {
            error_log("Database error: " . mysqli_error($connect));
        }
    }
}

?>