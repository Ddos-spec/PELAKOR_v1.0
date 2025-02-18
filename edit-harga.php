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
</head>
<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- end header -->

    <!-- body -->
    <div class="container">
        <h3 class="header light center">Data Harga</h3>
        <form action="" method="post">
            <div class="input field">
                <label for="cuci">Cuci</label>
                <input type="text" id="cuci" name="cuci" value="<?= $cuci['harga'] ?>">
            </div>
            <div class="input field">
                <label for="setrika">Setrika</label>
                <input type="text" id="setrika" name="setrika" value="<?= $setrika['harga'] ?>">
            </div>
            <div class="input field">
                <label for="komplit">Cuci + Setrika</label><input type="text" id="komplit" name="komplit" value="<?= $komplit['harga'] ?>">
            </div>
            <div class="input field center">
                <button class="btn-large blue darken-2" type="submit" name="simpan">Simpan Data</button>
            </div>
        </form>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php" ?>
    <!-- end footer -->

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

    // Validate prices
    if (!validasiHarga($hargaCuci) || !validasiHarga($hargaSetrika) || !validasiHarga($hargaKomplit)) {
        return -1;
    }

    // Start transaction
    mysqli_begin_transaction($connect);

    try {
        // Prepare update queries
        $queries = [
            "UPDATE harga SET harga = $hargaCuci WHERE jenis = 'cuci' AND id_agen = $idAgen",
            "UPDATE harga SET harga = $hargaSetrika WHERE jenis = 'setrika' AND id_agen = $idAgen",
            "UPDATE harga SET harga = $hargaKomplit WHERE jenis = 'komplit' AND id_agen = $idAgen"
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