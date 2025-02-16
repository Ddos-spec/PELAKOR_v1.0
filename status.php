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
                    <td><?= $cucian["tgl_mulai"] ?></td>
                    <td><?= $cucian["status_cucian"] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php 
        elseif ($login == "Agen") : 
            // Debug logging
            error_log("ID Agen: " . $idAgen);
            
            // Query untuk pesanan baru
            $queryStr = "SELECT c.*, p.nama as nama_pelanggan 
                      FROM cucian c 
                      JOIN pelanggan p ON c.id_pelanggan = p.id_pelanggan 
                      WHERE c.id_agen = $idAgen 
                      AND c.status_cucian = 'Penjemputan'
                      ORDER BY c.tgl_mulai DESC";
            error_log("Query Baru: " . $queryStr);
            
            $queryBaru = mysqli_query($connect, $queryStr);
            if (!$queryBaru) {
                error_log("Error in queryBaru: " . mysqli_error($connect));
            }

            // Query untuk pesanan proses
            $queryProses = mysqli_query($connect, "SELECT c.*, p.nama as nama_pelanggan 
                        FROM cucian c 
                        JOIN pelanggan p ON c.id_pelanggan = p.id_pelanggan 
                        WHERE c.id_agen = $idAgen 
                        AND c.status_cucian NOT IN ('Penjemputan', 'Selesai')
                        ORDER BY c.tgl_mulai DESC");
        ?>

        <div class="col s10 offset-s1">
            <!-- Tabs Navigation -->
            <div class="card">
                <div class="card-content">
                    <ul class="tabs">
                        <li class="tab col s6"><a class="active" href="#pesananBaru">Pesanan Baru</a></li>
                        <li class="tab col s6"><a href="#pesananProses">Sedang Diproses</a></li>
                    </ul>
                </div>
            </div>

            <!-- Tab Pesanan Baru -->
            <div id="pesananBaru">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Pesanan Baru</span>
                        <table class="responsive-table highlight">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Waktu</th>
                                    <th>Pelanggan</th>
                                    <th>Tipe</th>
                                    <th>Detail</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($pesanan = mysqli_fetch_assoc($queryBaru)) : ?>
                                <tr>
                                    <td><?= $pesanan['id_cucian'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($pesanan['tgl_mulai'])) ?></td>
                                    <td><?= $pesanan['nama_pelanggan'] ?></td>
                                    <td><?= ucfirst($pesanan['tipe_layanan']) ?></td>
                                    <td>
                                        <?php if($pesanan['tipe_layanan'] == 'kiloan'): ?>
                                            <a class="waves-effect waves-light btn modal-trigger" href="#modalBerat<?= $pesanan['id_cucian'] ?>">
                                                <i class="material-icons">scale</i>
                                            </a>
                                        <?php else: ?>
                                            <a class="waves-effect waves-light btn modal-trigger" href="#modalDetailSatuan<?= $pesanan['id_cucian'] ?>">
                                                <i class="material-icons">list</i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="" method="post" class="status-form">
                                            <input type="hidden" name="id_cucian" value="<?= $pesanan['id_cucian'] ?>">
                                            <button class="btn green" type="submit" name="terimaOrder">
                                                <i class="material-icons">check</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Pesanan Proses -->
            <div id="pesananProses">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Sedang Diproses</span>
                        <?php while($pesanan = mysqli_fetch_assoc($queryProses)) : ?>
                            <div class="timeline-item card-panel">
                                <div class="row">
                                    <div class="col s12 m4">
                                        <p><b>ID:</b> <?= $pesanan['id_cucian'] ?></p>
                                        <p><b>Pelanggan:</b> <?= $pesanan['nama_pelanggan'] ?></p>
                                        <p><b>Tipe:</b> <?= ucfirst($pesanan['tipe_layanan']) ?></p>
                                    </div>
                                    <div class="col s12 m8">
                                        <div class="status-timeline">
                                            <?php 
                                            $statusList = ['Penjemputan', 'Sedang di Cuci', 'Sedang Di Jemur', 'Sedang di Setrika', 'Pengantaran'];
                                            $currentStatus = array_search($pesanan['status_cucian'], $statusList);
                                            foreach($statusList as $index => $status): 
                                            ?>
                                            <div class="timeline-step <?= ($index <= $currentStatus) ? 'active' : '' ?>">
                                                <i class="material-icons"><?= getStatusIcon($status) ?></i>
                                                <span><?= $status ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="center-align" style="margin-top: 20px;">
                                            <form action="" method="post" class="status-form">
                                                <input type="hidden" name="id_cucian" value="<?= $pesanan['id_cucian'] ?>">
                                                <select name="status_cucian" class="browser-default" style="width: auto; display: inline-block; margin-right: 10px;">
                                                    <?php foreach($statusList as $status): ?>
                                                        <option value="<?= $status ?>" <?= ($status == $pesanan['status_cucian']) ? 'selected' : '' ?>><?= $status ?></option>
                                                    <?php endforeach; ?>
                                                    <option value="Selesai">Selesai</option>
                                                </select>
                                                <button class="btn" type="submit" name="updateStatus">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <form action="" method="post" class="status-form">
                                            <input type="hidden" name="id_cucian" value="<?= $pesanan['id_cucian'] ?>">
                                            <div class="input-field">
                                                <select name="status_cucian" required>
                                                    <option value="Sedang di Cuci">Sedang di Cuci</option>
                                                    <option value="Sedang di Jemur">Sedang di Jemur</option>
                                                    <option value="Sedang di Setrika">Sedang di Setrika</option>
                                                    <option value="Pengantaran">Pengantaran</option>
                                                    <option value="Selesai">Selesai</option>
                                                </select>
                                                <label>Update Status</label>
                                            </div>
                                            <div class="input-field">
                                                <textarea name="catatan_status" class="materialize-textarea"></textarea>
                                                <label>Catatan Status</label>
                                            </div>
                                            <button class="btn waves-effect waves-light" type="submit" name="updateStatus">
                                                Update
                                                <i class="material-icons right">send</i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <?php 
        mysqli_data_seek($queryBaru, 0);
        while($pesanan = mysqli_fetch_assoc($queryBaru)) : 
            if($pesanan['tipe_layanan'] == 'kiloan'): 
        ?>
            <!-- Modal Berat -->
            <div id="modalBerat<?= $pesanan['id_cucian'] ?>" class="modal">
                <div class="modal-content">
                    <h4>Konfirmasi Berat Cucian #<?= $pesanan['id_cucian'] ?></h4>
                    <div class="row">
                        <div class="col s12">
                            <p><b>Pelanggan:</b> <?= $pesanan['nama_pelanggan'] ?></p>
                            <p><b>Jenis:</b> <?= ucfirst($pesanan['jenis']) ?></p>
                            <?php if(!empty($pesanan['estimasi_item'])): ?>
                            <p><b>Estimasi Item:</b> <?= $pesanan['estimasi_item'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <form action="" method="post" id="formBerat<?= $pesanan['id_cucian'] ?>">
                        <input type="hidden" name="id_cucian" value="<?= $pesanan['id_cucian'] ?>">
                        <input type="hidden" name="jenis" value="<?= $pesanan['jenis'] ?>">
                        <?php
                        $queryHarga = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = '$idAgen' AND jenis = '{$pesanan['jenis']}'");
                        $harga = mysqli_fetch_assoc($queryHarga)['harga'];
                        ?>
                        <input type="hidden" id="hargaPerKg<?= $pesanan['id_cucian'] ?>" value="<?= $harga ?>">
                        
                        <div class="input-field">
                            <input type="number" step="0.1" id="beratAktual<?= $pesanan['id_cucian'] ?>" 
                                   name="berat" required min="0.1"
                                   onchange="updateHarga(<?= $pesanan['id_cucian'] ?>)">
                            <label>Berat Aktual (kg)</label>
                        </div>
                        <div class="total-harga">
                            <h5>Total: <span id="totalHarga<?= $pesanan['id_cucian'] ?>">Rp 0</span></h5>
                        </div>
                        <div class="input-field">
                            <textarea name="catatan_berat" class="materialize-textarea" placeholder="Catatan tambahan..."></textarea>
                            <label>Catatan</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn waves-effect waves-light red modal-close">Batal</button>
                    <button class="btn waves-effect waves-light green" 
                            onclick="document.getElementById('formBerat<?= $pesanan['id_cucian'] ?>').submit()">
                        Konfirmasi <i class="material-icons right">check</i>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Modal Detail Satuan -->
            <div id="modalDetailSatuan<?= $pesanan['id_cucian'] ?>" class="modal">
                <div class="modal-content">
                    <h4>Detail Pesanan Satuan #<?= $pesanan['id_cucian'] ?></h4>
                    <?php
                    $queryDetail = "SELECT d.*, h.nama_item, h.harga 
                                   FROM detail_cucian d 
                                   JOIN harga_satuan h ON d.id_harga_satuan = h.id_harga_satuan 
                                   WHERE d.id_cucian = {$pesanan['id_cucian']}";
                    error_log("Detail Query: " . $queryDetail);
                    
                    $detailResult = mysqli_query($connect, $queryDetail);
                    if (!$detailResult) {
                        error_log("Error in detail query: " . mysqli_error($connect));
                    }
                    ?>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            while($detail = mysqli_fetch_assoc($detailResult)): 
                                $subtotal = $detail['jumlah'] * $detail['harga'];
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= $detail['nama_item'] ?></td>
                                <td><?= $detail['jumlah'] ?></td>
                                <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="input-field">
                        <textarea name="catatan_terima" class="materialize-textarea" 
                                 form="formTerima<?= $pesanan['id_cucian'] ?>" 
                                 placeholder="Catatan penerimaan..."></textarea>
                        <label>Catatan</label>
                    </div>
                    <form id="formTerima<?= $pesanan['id_cucian'] ?>" action="" method="post">
                        <input type="hidden" name="id_cucian" value="<?= $pesanan['id_cucian'] ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn waves-effect waves-light green" 
                            onclick="document.getElementById('formTerima<?= $pesanan['id_cucian'] ?>').submit()">
                        Terima Pesanan <i class="material-icons right">check</i>
                    </button>
                </div>
            </div>
        <?php 
            endif;
        endwhile; 
        ?>

        <style>
        /* Add the CSS for timeline here */
        .status-timeline {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .timeline-step {
            text-align: center;
            position: relative;
            flex: 1;
        }

        /* ...rest of the CSS... */
        .preloader-wrapper {
            display: none;
        }
        
        .loading .preloader-wrapper {
            display: inline-block;
        }

        .btn-loading {
            position: relative;
        }

        .btn-loading .preloader-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -12px;
            margin-left: -12px;
        }

        .btn-loading span {
            visibility: hidden;
        }

        .timeline-step.done {
            color: #4CAF50;
        }

        .timeline-step.active {
            color: #2196F3;
        }

        .timeline-note {
            margin-top: 10px;
            font-size: 0.9em;
            color: #666;
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabs = document.querySelectorAll('.tabs');
            M.Tabs.init(tabs);

            var modals = document.querySelectorAll('.modal');
            M.Modal.init(modals);

            // Initialize all selects
            var selects = document.querySelectorAll('select');
            M.FormSelect.init(selects);

            // Check for new orders periodically
            checkNewOrders();
            setInterval(checkNewOrders, 30000); // Check every 30 seconds
        });

        function updateHarga(idCucian) {
            const berat = document.getElementById('beratAktual' + idCucian).value;
            const harga = document.getElementById('hargaPerKg' + idCucian).value;
            const total = berat * harga;
            document.getElementById('totalHarga' + idCucian).textContent = 
                'Rp ' + total.toLocaleString();
        }

        function showLoading(button) {
            button.classList.add('btn-loading');
            button.disabled = true;
        }

        function hideLoading(button) {
            button.classList.remove('btn-loading');
            button.disabled = false;
        }

        function checkNewOrders() {
            fetch('check_new_orders.php?id_agen=<?= $idAgen ?>')
                .then(response => response.json())
                .then(data => {
                    if(data.new_orders > 0) {
                        M.toast({
                            html: `Ada ${data.new_orders} pesanan baru!`,
                            classes: 'rounded'
                        });
                    }
                });
        }

        // Add loading state to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                if(button) showLoading(button);
            });
        });
        </script>

        <?php
        // Helper function for status icons
        function getStatusIcon($status) {
            switch($status) {
                case 'Penjemputan': return 'local_shipping';
                case 'Sedang di Cuci': return 'local_laundry_service';
                case 'Sedang Di Jemur': return 'wb_sunny';
                case 'Sedang di Setrika': return 'iron';
                case 'Pengantaran': return 'local_shipping';
                default: return 'check_circle';
            }
        }
        ?>
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
                    <td><?= $cucian["status_cucian"] ?></td>
                    
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>

<?php


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
    $catatan_berat = htmlspecialchars($_POST["catatan_berat"]);
    
    // validasi 
    validasiBerat($berat);

    mysqli_query($connect, "UPDATE cucian 
                          SET berat = $berat,
                              catatan_berat = '$catatan_berat',
                              status_cucian = 'Sedang di Cuci'
                          WHERE id_cucian = $idCucian");

    if (mysqli_affected_rows($connect) > 0){
        echo "
            <script>
                Swal.fire('Berat Berhasil Dikonfirmasi','Pesanan akan diproses','success').then(function() {
                    window.location = 'status.php';
                });
            </script>
        ";
    }

    

}

// Add new functions for handling status changes
if (isset($_POST["terimaOrder"])) {
    $idCucian = $_POST["id_cucian"];
    $catatan = htmlspecialchars($_POST["catatan_terima"] ?? '');
    
    mysqli_query($connect, "UPDATE cucian SET 
                          status_cucian = 'Sedang di Cuci',
                          catatan_proses = CONCAT(IFNULL(catatan_proses, ''), '\n[" . date('Y-m-d H:i:s') . "] Pesanan diterima: $catatan')
                          WHERE id_cucian = '$idCucian'");
                          
    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Pesanan Diterima','Pesanan akan diproses','success').then(function() {
                    window.location = 'status.php';
                });
            </script>
        ";
    }
}

// Modify existing simpanStatus to include notes
if (isset($_POST["simpanStatus"])) {
    // ...existing code...
    $catatan = htmlspecialchars($_POST["catatan_status"] ?? '');
    
    mysqli_query($connect, "UPDATE cucian SET 
                          status_cucian = '$statusCucian',
                          catatan_proses = CONCAT(IFNULL(catatan_proses, ''), '\n[" . date('Y-m-d H:i:s') . "] $statusCucian: $catatan')
                          WHERE id_cucian = '$idCucian'");
    // ...rest of existing code...
}

if (isset($_POST["updateStatus"])) {
    $idCucian = $_POST["id_cucian"];
    $statusBaru = $_POST["status_cucian"];
    $catatan = htmlspecialchars($_POST["catatan_status"]);
    $timestamp = date("Y-m-d H:i:s");

    mysqli_begin_transaction($connect);
    try {
        // Update status
        mysqli_query($connect, "UPDATE cucian SET 
            status_cucian = '$statusBaru',
            catatan_proses = CONCAT(IFNULL(catatan_proses, ''), '\n[$timestamp] $statusBaru: $catatan')
            WHERE id_cucian = '$idCucian'");

        // Jika status Selesai, pindahkan ke transaksi
        if ($statusBaru === 'Selesai') {
            // ... kode untuk memindahkan ke transaksi ...
            echo "<script>window.location = 'transaksi.php';</script>";
        } else {
            echo "<script>window.location = 'status.php';</script>";
        }

        mysqli_commit($connect);
    } catch (Exception $e) {
        mysqli_rollback($connect);
        echo "<script>
            Swal.fire('Error!', '".$e->getMessage()."', 'error');
        </script>";
    }
}

// ...existing code...

// STATUS HANDLING
if (isset($_POST["simpanStatus"])) {
    try {
        $statusCucian = htmlspecialchars($_POST["status_cucian"]);
        $idCucian = (int)$_POST["id_cucian"];
        $catatan = htmlspecialchars($_POST["catatan"] ?? '');
        
        $result = handleStatusUpdate($connect, $idCucian, $statusCucian, $catatan, $idAgen);
        
        if (isset($result['redirect'])) {
            echo "<script>
                Swal.fire('Berhasil!', 'Status berhasil diupdate', 'success')
                .then(() => window.location = '{$result['redirect']}');
            </script>";
        } else {
            echo "<script>
                Swal.fire('Berhasil!', 'Status berhasil diupdate', 'success')
                .then(() => window.location.reload());
            </script>";
        }
    } catch (Exception $e) {
        echo "<script>
            Swal.fire('Error!', '". htmlspecialchars($e->getMessage()) ."', 'error');
        </script>";
    }
}

if (isset($_POST["terimaOrder"])) {
    try {
        $idCucian = (int)$_POST["id_cucian"];
        $catatan = htmlspecialchars($_POST["catatan_terima"] ?? '');
        
        if(handleAcceptOrder($connect, $idCucian, $catatan)) {
            echo "<script>
                Swal.fire('Pesanan Diterima', 'Pesanan akan diproses', 'success')
                .then(() => window.location = 'status.php');
            </script>";
        } else {
            throw new Exception("Gagal menerima pesanan");
        }
    } catch (Exception $e) {
        echo "<script>
            Swal.fire('Error!', '". htmlspecialchars($e->getMessage()) ."', 'error');
        </script>";
    }
}

if (isset($_POST["updateStatus"])) {
    try {
        $idCucian = (int)$_POST["id_cucian"];
        $statusBaru = htmlspecialchars($_POST["status_cucian"]);
        $catatan = htmlspecialchars($_POST["catatan_status"] ?? '');
        
        $result = handleStatusUpdate($connect, $idCucian, $statusBaru, $catatan, $idAgen);
        
        if (isset($result['redirect'])) {
            echo "<script>window.location = '{$result['redirect']}';</script>";
        } else if (isset($result['error'])) {
            throw new Exception($result['error']);
        } else {
            echo "<script>
                Swal.fire('Berhasil!', 'Status berhasil diupdate', 'success')
                .then(() => window.location = 'status.php');
            </script>";
        }
    } catch (Exception $e) {
        echo "<script>
            Swal.fire('Error!', '". htmlspecialchars($e->getMessage()) ."', 'error');
        </script>";
    }
}

if (isset($_POST["simpanBerat"])) {
    try {
        $berat = (float)$_POST["berat"];
        $idCucian = (int)$_POST["id_cucian"]; 
        $catatan_berat = htmlspecialchars($_POST["catatan_berat"]);
        
        if(handleWeightUpdate($connect, $berat, $idCucian, $catatan_berat)) {
            echo "<script>
                Swal.fire('Berat Berhasil Dikonfirmasi', 'Pesanan akan diproses', 'success')
                .then(() => window.location = 'status.php');
            </script>";
        }
    } catch (Exception $e) {
        echo "<script>
            Swal.fire('Error!', '". htmlspecialchars($e->getMessage()) ."', 'error');
        </script>";
    }
}

// ...existing code...
?>