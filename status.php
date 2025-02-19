<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekBelumLogin();


// sesuaikan dengan jenis login
if(isset($_SESSION["login-admin"]) && isset($_SESSION["admin"])){

    $login = "Admin";
    $idAdmin = $_SESSION["admin"];

}else if(isset($_SESSION["login-agen"]) && isset($_SESSION["agen"])){

    $idAgen = $_SESSION["agen"];
    $login = "Agen";

}else if (isset($_SESSION["login-pelanggan"]) && isset($_SESSION["pelanggan"])){

    $idPelanggan = $_SESSION["pelanggan"];
    $login = "Pelanggan";

}else {
    echo "
        <script>
            window.location = 'login.php';
        </script>
    ";
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html" ?>
    <title>Status Cucian - <?= $login ?></title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div id="body">
        <h3 class="header col s10 light center">Status Cucian</h3>
        <br>
        <?php if ($login == "Admin") : $query = mysqli_query($connect, "SELECT * FROM cucian WHERE status_cucian != 'Selesai'"); ?>
        <div class="col s10 offset-s1">
            <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold;">ID Cucian</td>
                    <td style="font-weight:bold;">Nama Agen</td>
                    <td style="font-weight:bold;">Pelanggan</td>
                    <td style="font-weight:bold;">Total Item</td>
                    <td style="font-weight:bold;">Berat (kg)</td>
                    <td style="font-weight:bold;">Jenis</td>
                    <td style="font-weight:bold;">Harga Paket</td>
                    <td style="font-weight:bold;">Harga Per Item</td>
                    <td style="font-weight:bold;">Total Harga</td>
                    <td style="font-weight:bold;">Tanggal Dibuat</td>
                    <td style="font-weight:bold;">Status</td>
                </tr>
                <?php while ($cucian = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td>
                        <?php
                            echo $idCucian = $cucian['id_cucian'];
                        ?>
                    </td>
                    <td>
                        <?php
                            $data = mysqli_query($connect, "SELECT agen.nama_laundry FROM cucian INNER JOIN agen ON agen.id_agen = cucian.id_agen WHERE id_cucian = $idCucian");
                            $data = mysqli_fetch_assoc($data);
                            echo $data["nama_laundry"];
                        ?>
                    </td>
                    <td>
                        <?php
                            $data = mysqli_query($connect, "SELECT pelanggan.nama FROM cucian INNER JOIN pelanggan ON pelanggan.id_pelanggan = cucian.id_pelanggan WHERE id_cucian = $idCucian");
                            $data = mysqli_fetch_assoc($data);
                            echo $data["nama"];
                        ?>
                    </td>
                    <td><?= $cucian["total_item"] ?></td>
                    <td><?= $cucian["berat"] ?></td>
                    <td><?= $cucian["jenis"] ?></td>
                    <td>
                        <?php
                            $hargaPaket = getHargaPaket($cucian['jenis'], $cucian['id_agen']);
                            echo "Rp " . number_format($hargaPaket, 0, ',', '.');
                        ?>
                    </td>
                    <td>
                        <?php
                            $hargaPerItem = getHargaPerItem($cucian['jenis'], $cucian['id_agen']);
                            echo "Rp " . number_format($hargaPerItem, 0, ',', '.');
                        ?>
                    </td>
                    <td>
                        <?php
                            $totalHarga = calculateTotalHarga($cucian);
                            echo "Rp " . number_format($totalHarga, 0, ',', '.');
                        ?>
                    </td>
                    <td><?= $cucian["tgl_mulai"] ?></td>
                    <td><?= $cucian["status_cucian"] ?></td>
                    <td>
                        <button class="btn modal-trigger" data-target="modal-<?= $cucian['id_cucian'] ?>">
                            View Details
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php elseif ($login == "Agen") : $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_agen = $idAgen AND status_cucian != 'Selesai'"); ?>
        <div class="col s10 offset-s1">
            <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold;">ID Cucian</td>
                    <td style="font-weight:bold;">Pelanggan</td>
                    <td style="font-weight:bold;">Total Item</td>
                    <td style="font-weight:bold;">Berat (kg)</td>
                    <td style="font-weight:bold;">Jenis</td>
                    <td style="font-weight:bold;">Tanggal Dibuat</td>
                    <td style="font-weight:bold;">Status</td>
                    <td style="font-weight:bold;">Aksi</td>
                </tr>
                <?php while ($cucian = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td>
                        <?php
                            echo $idCucian = $cucian['id_cucian'];
                        ?>
                    </td>
                    <td>
                        <?php
                            $data = mysqli_query($connect, "SELECT pelanggan.nama FROM cucian INNER JOIN pelanggan ON pelanggan.id_pelanggan = cucian.id_pelanggan WHERE id_cucian = $idCucian");
                            $data = mysqli_fetch_assoc($data);
                            echo $data["nama"];
                        ?>
                    </td>
                    <td><?= $cucian["total_item"] ?></td>
                    <td>
                        <?php if ($cucian["berat"] == NULL) : ?>
                            <form action="" method="post">
                                <input type="hidden" name="id_cucian" value="<?= $idCucian ?>">
                                <div class="input-field">
                                    <input type="text" size=1 name="berat">
                                    <div class="center"><button class="btn blue darken-2" type="submit" name="simpanBerat"><i class="material-icons">send</i></button></div>
                                </div>
                            </form>
                        <?php else : echo $cucian["berat"]; endif;?>
                    </td>
                    <td><?= $cucian["jenis"] ?></td>
                    <td><?= $cucian["tgl_mulai"] ?></td>
                    <td><?= $cucian["status_cucian"] ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="id_cucian" value="<?= $idCucian ?>">
                            <select class="browser-default" name="status_cucian" id="status_cucian">
                                <option disabled selected>Status :</option>
                                <option value="Penjemputan">Penjemputan</option>
                                <option value="Sedang di Cuci">Sedang di Cuci</option>
                                <option value="Sedang Di Jemur">Sedang Di Jemur</option>
                                <option value="Sedang di Setrika">Sedang di Setrika</option>
                                <option value="Pengantaran">Pengantaran</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                                
                            <div class="center">
                                <button class="btn blue darken-2" type="submit" name="simpanStatus"><i class="material-icons">send</i></button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php elseif ($login == "Pelanggan") : $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_pelanggan = $idPelanggan AND status_cucian != 'Selesai'"); ?>
        <div class="col s10 offset-s1">
            <table border=1 cellpadding=10 class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold">ID Cucian</td>
                    <td style="font-weight:bold">Agen</td>
                    <td style="font-weight:bold">Total Item</td>
                    <td style="font-weight:bold">Berat (kg)</td>
                    <td style="font-weight:bold">Jenis</td>
                    <td style="font-weight:bold">Tanggal Dibuat</td>
                    <td style="font-weight:bold">Status</td>
                </tr>
                <?php while ($cucian = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td>
                        <?php
                            echo $idCucian = $cucian['id_cucian'];
                        ?>
                    </td>
                    <td>
                        <?php
                            $data = mysqli_query($connect, "SELECT agen.nama_laundry FROM cucian INNER JOIN agen ON agen.id_agen = cucian.id_agen WHERE id_cucian = $idCucian");
                            $data = mysqli_fetch_assoc($data);
                            echo $data["nama_laundry"];
                        ?>
                    </td>
                    <td><?= $cucian["total_item"] ?></td>
                    <td><?= $cucian["berat"] ?></td>
                    <td><?= $cucian["jenis"] ?></td>
                    <td><?= $cucian["tgl_mulai"] ?></td>
                    <td>
                        <span class="status-badge <?= str_replace(' ', '-', strtolower($cucian['status_cucian'])) ?>">
                            <i class="material-icons">
                                <?php 
                                    switch($cucian['status_cucian']) {
                                        case 'Penjemputan': echo 'directions_car'; break;
                                        case 'Sedang di Cuci': echo 'local_laundry_service'; break;
                                        case 'Sedang Di Jemur': echo 'wb_sunny'; break;
                                        case 'Sedang di Setrika': echo 'iron'; break;
                                        case 'Pengantaran': echo 'local_shipping'; break;
                                        case 'Selesai': echo 'check_circle'; break;
                                        default: echo 'info'; break;
                                    }
                                ?>
                            </i>
                            <?= $cucian["status_cucian"] ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php include "footer.php"; ?>
    
    <style>
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            color: white;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .penjemputan { 
            background-color: #2196F3;
            background: linear-gradient(45deg, #2196F3, #64B5F6);
        }
        .sedang-di-cuci { 
            background-color: #FFC107;
            background: linear-gradient(45deg, #FFC107, #FFD54F);
        }
        .sedang-di-jemur { 
            background-color: #4CAF50;
            background: linear-gradient(45deg, #4CAF50, #81C784);
        }
        .sedang-di-setrika { 
            background-color: #9C27B0;
            background: linear-gradient(45deg, #9C27B0, #BA68C8);
        }
        .pengantaran { 
            background-color: #FF5722;
            background: linear-gradient(45deg, #FF5722, #FF8A65);
        }
        .selesai { 
            background-color: #607D8B;
            background: linear-gradient(45deg, #607D8B, #90A4AE);
        }
        
        .status-badge i {
            font-size: 1.2em;
        }
    </style>
    
    <!-- Modals -->
    <?php while($cucian = mysqli_fetch_assoc($query)): ?>
    <div id="modal-<?= $cucian['id_cucian'] ?>" class="modal">
        <div class="modal-content">
            <h4>Order Details #<?= $cucian['id_cucian'] ?></h4>
            <table class="striped">
                <thead>
                    <tr>
                        <th>Item Type</th>
                        <th>Quantity</th>
                        <th>Price per Item</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $items = explode(', ', $cucian['item_type']);
                    foreach($items as $item):
                        list($itemName, $quantity) = explode(' (', $item);
                        $quantity = str_replace(')', '', $quantity);
                        $price = getItemPrice($itemName, $cucian['jenis']);
                    ?>
                    <tr>
                        <td><?= $itemName ?></td>
                        <td><?= $quantity ?></td>
                        <td>Rp <?= number_format($price, 0, ',', '.') ?></td>
                    <td>
                        <?php
                        // Get the latest price from database
                        $query = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$serviceType'");
                        $latestPrice = mysqli_fetch_assoc($query);
                        $price = $latestPrice['harga'] ?? $price; // Use latest price if available
                        ?>
                        Rp <?= number_format($price * $quantity, 0, ',', '.') ?>
                    </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close btn">Close</a>
        </div>
    </div>
    <?php endwhile; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems);
        });
    </script>
</body>
</html>

<?php

function getHargaPaket($jenis, $idAgen) {
    global $connect;
    
    $query = mysqli_query($connect, "SELECT harga_paket FROM harga WHERE id_agen = $idAgen AND jenis = '$jenis'");
    $harga = mysqli_fetch_assoc($query);
    
    return $harga['harga_paket'] ?? 0;
}

function getHargaPerItem($jenis, $idAgen) {
    global $connect;
    
    $query = mysqli_query($connect, "SELECT harga_per_item FROM harga WHERE id_agen = $idAgen AND jenis = '$jenis'");
    $harga = mysqli_fetch_assoc($query);
    
    return $harga['harga_per_item'] ?? 0;
}

function calculateTotalHarga($cucian) {
    global $connect;
    
    $hargaPaket = getHargaPaket($cucian['jenis'], $cucian['id_agen']);
    $hargaPerItem = getHargaPerItem($cucian['jenis'], $cucian['id_agen']);
    
    // Calculate total based on package price + (number of items * per item price)
    return $hargaPaket + ($cucian['total_item'] * $hargaPerItem);
}

function getItemPrice($item, $serviceType) {
    global $connect;
    
    // Get the agen ID from the cucian
    $cucianId = $_GET['id'] ?? 0;
    $query = mysqli_query($connect, "SELECT id_agen FROM cucian WHERE id_cucian = $cucianId");
    $cucian = mysqli_fetch_assoc($query);
    $idAgen = $cucian['id_agen'] ?? 0;
    
    // Get the price from database
    $query = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = $idAgen AND jenis = '$serviceType'");
    if (!$query) {
        error_log("Database error: " . mysqli_error($connect));
        return 0;
    }
    
    $price = mysqli_fetch_assoc($query);
    if (!$price) {
        error_log("No price found for service type: $serviceType");
        return 0;
    }
    
    return $price['harga'];
}

// STATUS CUCIAN
if ( isset($_POST["simpanStatus"]) ){

    // ambil data method post
    $statusCucian = $_POST["status_cucian"];
    $idCucian = $_POST["id_cucian"];

    // cari data
    $query = mysqli_query($connect, "SELECT * FROM cucian INNER JOIN harga ON harga.jenis = cucian.jenis WHERE id_cucian = $idCucian");
    $cucian = mysqli_fetch_assoc($query);
    $status = "Selesai";
    // kalau status selesai
    if ( $statusCucian == $status){

        // isi data di tabel transaksi
        $tglMulai = $cucian["tgl_mulai"];
        $tglSelesai = date("Y-m-d H:i:s");
        $totalBayar = $cucian["berat"] * $cucian["harga"];
        $idCucian = $cucian["id_cucian"];
        $idPelanggan = $cucian["id_pelanggan"];
        // masukkan ke tabel transaksi
        mysqli_query($connect,"INSERT INTO transaksi (id_cucian, id_agen, id_pelanggan, tgl_mulai, tgl_selesai, total_bayar, rating) VALUES ($idCucian, $idAgen, $idPelanggan, '$tglMulai', '$tglSelesai', $totalBayar, 0)");
        if (mysqli_affected_rows($connect) == 0){
            echo mysqli_error($connect);
        }
    }

    mysqli_query($connect, "UPDATE cucian SET status_cucian = '$statusCucian' WHERE id_cucian = '$idCucian'");
    if (mysqli_affected_rows($connect) > 0){
        echo "
            <script>
                Swal.fire('Status Berhasil Di Ubah','','success').then(function() {
                    window.location = 'status.php';
                });
            </script>   
        ";
    }

    
}

// total berat
if (isset($_POST["simpanBerat"])){

    $berat = htmlspecialchars($_POST["berat"]);
    $idCucian = $_POST["id_cucian"];

    // validasi 
    validasiBerat($berat);

    mysqli_query($connect, "UPDATE cucian SET berat = $berat WHERE id_cucian = $idCucian");

    if (mysqli_affected_rows($connect) > 0){
        echo "
            <script>
                Swal.fire('Data Berhasil Di Ubah','','success').then(function() {
                    window.location = 'status.php';
                });
            </script>
        ";
    }

    

}

?>