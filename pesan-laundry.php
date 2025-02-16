<?php
// Start session and handle all processing before any output
session_start();

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'laundry_error.log');

// Database connection
require 'connect-db.php';

// Functions
require 'functions/functions.php';

// Check database connection
if (!isset($connect) || !$connect) {
    error_log("Database connection not established");
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]));
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Processing POST request");
    
    // Validate session
    cekPelanggan();
    
    // Get customer ID
    $idPelanggan = $_SESSION["pelanggan"];
    
    // Process order based on type
    if (isset($_POST["pesanKiloan"])) {
        $result = processKiloanOrder($connect, $idPelanggan);
        
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        } else {
            if($result['status'] === 'success') {
                echo "<script>
                    Swal.fire({
                        title: 'Pesanan Berhasil!',
                        text: 'Menunggu konfirmasi agen',
                        icon: 'success'
                    }).then(() => window.location = 'status.php');
                </script>";
            } else {
                echo "<script>
                    Swal.fire('Error!','".$result['message']."','error');
                </script>";
            }
        }
    } elseif (isset($_POST["pesanSatuan"])) {
        $result = processSatuanOrder($connect, $idPelanggan);
        
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        } else {
            if($result['status'] === 'success') {
                echo "<script>
                    Swal.fire({
                        title: 'Pesanan Berhasil!',
                        text: 'Menunggu konfirmasi agen',
                        icon: 'success'
                    }).then(() => window.location = 'status.php');
                </script>";
            } else {
                echo "<script>
                    Swal.fire('Error!','".$result['message']."','error');
                </script>";
            }
        }
    }
}

// Get agent and customer data for display
$idAgen = $_GET["id"] ?? null;
if (!$idAgen) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid agent ID']));
}

$query = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$idAgen'");
$agen = mysqli_fetch_assoc($query);

$idPelanggan = $_SESSION["pelanggan"];
$query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
$pelanggan = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html' ?>
    <title>Pemesanan Laundry</title>
    <style>
        .card-panel-item {
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            background-color: #f5f5f5;
        }
        .total-box {
            padding: 15px;
            margin-top: 20px;
            background-color: #e3f2fd;
            border-radius: 4px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <?php include 'header.php' ?>

    <div class="container">
        <!-- Profile and Customer Data -->
        <div class="row">
            <!-- Profile -->
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image">
                        <img src="img/logo.png" style="max-height: 200px; object-fit: contain;">
                    </div>
                    <div class="card-content">
                        <span class="card-title center"><?= $agen["nama_laundry"] ?></span>
                        <div class="center">
                            <?php
                                $temp = $agen["id_agen"];
                                $queryStar = mysqli_query($connect,"SELECT * FROM transaksi WHERE id_agen = '$temp'");
                                $totalStar = 0;
                                $i = 0;
                                while ($star = mysqli_fetch_assoc($queryStar)){
                                    $totalStar += $star["rating"];
                                    $i++;
                                    $fixStar = ceil($totalStar / $i);
                                }
                            ?>
                            <fieldset class="bintang">
                                <span class="starImg star-<?= ($totalStar == 0) ? '0' : $fixStar ?>"></span>
                            </fieldset>
                        </div>
                        <p><i class="material-icons tiny">location_on</i> <?= $agen["alamat"] . ", " . $agen["kota"] ?></p>
                        <p><i class="material-icons tiny">phone</i> <?= $agen["telp"] ?></p>
                    </div>
                </div>
            </div>

            <!-- Customer Data -->
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Data Penerima</span>
                        <div class="input-field">
                            <input id="nama_penerima" type="text" disabled value="<?= $pelanggan['nama'] ?>">
                            <label for="nama_penerima">Nama Penerima</label>
                        </div>
                        <div class="input-field">
                            <input id="telp_penerima" type="text disabled value="<?= $pelanggan['telp'] ?>">
                            <label for="telp_penerima">No Telp</label>
                        </div>
                        <div class="input-field">
                            <textarea id="alamat_penerima" class="materialize-textarea" name="alamat"><?= $pelanggan['alamat'] . ", " . $pelanggan['kota'] ?></textarea>
                            <label for="alamat_penerima">Alamat</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Forms -->
        <div class="card">
            <div class="card-content">
                <span class="card-title center">Pilih Jenis Layanan</span>
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s6"><a class="active" href="#kiloan">Kiloan</a></li>
                            <li class="tab col s6"><a href="#satuan">Satuan</a></li>
                        </ul>
                    </div>

                    <!-- Form Kiloan -->
                    <div id="kiloan" class="col s12">
                        <form action="" method="post" id="formKiloan">
                            <input type="hidden" name="tipe_layanan" value="kiloan">
                            <input type="hidden" name="alamat" id="alamat_kiloan">
                            <div class="row">
                                <div class="col s12">
                                    <p>Jenis Layanan</p>
                                    <?php
                                    $queryHarga = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen'");
                                    while($harga = mysqli_fetch_assoc($queryHarga)) {
                                        echo '
                                        <p>
                                            <label>
                                                <input name="jenis" type="radio" value="'.$harga['jenis'].'" '.($harga['jenis'] == 'cuci' ? 'checked' : '').'/>
                                                <span>'.$harga['jenis'].' (Rp '.number_format($harga['harga'], 0, ',', '.').')/Kg</span>
                                            </label>
                                        </p>';
                                    }
                                    ?>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <textarea id="estimasi_item" class="materialize-textarea" name="estimasi_item" placeholder="Contoh: 3 baju, 2 celana, 4 kaos"></textarea>
                                        <label for="estimasi_item">Estimasi Item (Opsional</label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <textarea id="catatan_kiloan" class="materialize-textarea" name="catatan"></textarea>
                                        <label for="catatan_kiloan">Catatan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="center">
                                <button class="btn-large waves-effect waves-light blue darken-2" type="submit" name="pesanKiloan">
                                    Pesan Sekarang
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Satuan -->
                    <div id="satuan" class="col s12">
                        <form action="" method="post" id="formSatuan">
                            <input type="hidden" name="tipe_layanan" value="satuan">
                            <input type="hidden" name="alamat" id="alamat_satuan">
                            <div class="row">
                                <div class="col s12">
                                    <p>Jenis Layanan</p>
                                    <p>
                                        <label>
                                            <input name="jenis" type="radio" value="cuci" checked onchange="updateDaftarItem(this.value)"/>
                                            <span>Cuci</span>
                                        </label>
                                        <label>
                                            <input name="jenis" type="radio" value="setrika" onchange="updateDaftarItem(this.value)"/>
                                            <span>Setrika</span>
                                        </label>
                                        <label>
                                            <input name="jenis" type="radio" value="komplit" onchange="updateDaftarItem(this.value)"/>
                                            <span>Komplit</span>
                                        </label>
                                    </p>
                                </div>
                            </div>

                            <div id="daftarItem">
