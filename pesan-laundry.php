<?php
// Add error logging at the start of the file
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'laundry_error.log');

session_start();
include 'connect-db.php';
include 'functions/functions.php';

error_log("Starting pesan-laundry.php execution");

cekPelanggan();

// ambil data agen
$idAgen = $_GET["id"];
$query = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$idAgen'");
$agen = mysqli_fetch_assoc($query);
error_log("Retrieved agent data for ID: " . $idAgen);

if (isset($_GET["jenis"])){
    $jenis = $_GET["jenis"];
}else {
    $jenis = NULL;
}

// ambil data pelanggan
$idPelanggan = $_SESSION["pelanggan"];
$query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
$pelanggan = mysqli_fetch_assoc($query);
error_log("Retrieved customer data for ID: " . $idPelanggan);
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
                            <input id="telp_penerima" type="text" disabled value="<?= $pelanggan['telp'] ?>">
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
                                        <label for="estimasi_item">Estimasi Item (Opsional)</label>
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
                                <!-- Items will be loaded here -->
                            </div>

                            <div class="total-box">
                                <div class="right" id="totalHarga">Total: Rp 0</div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="input-field">
                                <textarea id="catatan_satuan" class="materialize-textarea" name="catatan"></textarea>
                                <label for="catatan_satuan">Catatan</label>
                            </div>

                            <div class="center">
                                <button class="btn-large waves-effect waves-light blue darken-2" type="submit" name="pesanSatuan">
                                    Pesan Sekarang
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tabs
        var tabs = document.querySelectorAll('.tabs');
        M.Tabs.init(tabs);
        updateDaftarItem('cuci');

        // Sync address across forms
        var alamatPenerima = document.getElementById('alamat_penerima');
        var alamatKiloan = document.getElementById('alamat_kiloan');
        var alamatSatuan = document.getElementById('alamat_satuan');

        // Set initial values
        alamatKiloan.value = alamatPenerima.value;
        alamatSatuan.value = alamatPenerima.value;

        // Update when changed
        alamatPenerima.addEventListener('change', function() {
            alamatKiloan.value = this.value;
            alamatSatuan.value = this.value;
        });
        
        // Form validation
        document.getElementById('formSatuan').onsubmit = function(e) {
            let totalItems = 0;
            const inputs = document.querySelectorAll('#daftarItem input[type="number"]');
            inputs.forEach(input => totalItems += parseInt(input.value));
            
            if(totalItems === 0) {
                e.preventDefault();
                Swal.fire('Error', 'Minimal pesan 1 item', 'error');
            }
        };

        // Form submission handlers with validation
        document.getElementById('formKiloan').onsubmit = function(e) {
            e.preventDefault();
            
            if (!alamatPenerima.value.trim()) {
                Swal.fire('Error', 'Alamat pengiriman wajib diisi', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Konfirmasi Pesanan',
                html: `
                    <div class="left-align">
                        <p><b>Jenis Layanan:</b> ${document.querySelector('input[name="jenis"]:checked').value}</p>
                        <p><b>Estimasi Item:</b> ${document.getElementById('estimasi_item').value || '-'}</p>
                        <p><b>Catatan:</b> ${document.getElementById('catatan_kiloan').value || '-'}</p>
                        <p><b>Alamat:</b> ${alamatPenerima.value}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pesan Sekarang',
                cancelButtonText: 'Periksa Kembali',
                confirmButtonColor: '#2196F3',
                cancelButtonColor: '#ff5252',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        };

        document.getElementById('formSatuan').onsubmit = function(e) {
            e.preventDefault();
            
            if (!alamatPenerima.value.trim()) {
                Swal.fire('Error', 'Alamat pengiriman wajib diisi', 'error');
                return;
            }
            
            let totalItems = 0;
            const items = [];
            const inputs = document.querySelectorAll('#daftarItem input[type="number"]');
            
            inputs.forEach(input => {
                const qty = parseInt(input.value);
                totalItems += qty;
                if (qty > 0) {
                    const itemName = input.closest('.card-panel-item').querySelector('p').textContent;
                    items.push(`${qty}x ${itemName}`);
                }
            });
            
            if(totalItems === 0) {
                Swal.fire('Error', 'Minimal pesan 1 item', 'error');
                return;
            }
            
            Swal.fire({
                title: 'Konfirmasi Pesanan',
                html: `
                    <div class="left-align">
                        <p><b>Jenis Layanan:</b> ${document.querySelector('input[name="jenis"]:checked').value}</p>
                        <p><b>Item:</b></p>
                        <ul>
                            ${items.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                        <p><b>Total:</b> ${document.getElementById('totalHarga').textContent}</p>
                        <p><b>Catatan:</b> ${document.getElementById('catatan_satuan').value || '-'}</p>
                        <p><b>Alamat:</b> ${alamatPenerima.value}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pesan Sekarang',
                cancelButtonText: 'Periksa Kembali',
                confirmButtonColor: '#2196F3',
                cancelButtonColor: '#ff5252',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        };
    });

    function updateDaftarItem(jenis) {
        document.getElementById('daftarItem').innerHTML = 
            '<div class="progress"><div class="indeterminate"></div></div>';
            
        fetch('get_harga_satuan.php?id_agen=<?= $idAgen ?>')
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                
                if(!data || data.error) {
                    throw new Error(data.error || 'Data tidak valid');
                }
                
                if(data.length === 0) {
                    document.getElementById('daftarItem').innerHTML = 
                        '<div class="card-panel red lighten-4">'+
                        'Harga satuan belum diset oleh agen</div>';
                    return;
                }
                
                const default_items = {
                    'Baju': 'Pakaian Atas',
                    'Celana': 'Pakaian Bawah',
                    'Jaket/Sweater': 'Outerwear',
                    'Pakaian Khusus': 'Special Care',
                    'Selimut': 'Home Items',
                    'Karpet': 'Home Items'
                };
                
                let html = '';
                data.forEach(item => {
                    if(default_items.hasOwnProperty(item.nama_item)) {
                        let harga = parseInt(item.harga);
                        if(jenis === 'setrika') harga *= 0.8;
                        if(jenis === 'komplit') harga *= 1.5;
                        
                        html += `
                        <div class="card-panel-item">
                            <div class="row">
                                <div class="col s7">
                                    <p>${item.nama_item}</p>
                                    <p class="grey-text">(${default_items[item.nama_item]})</p>
                                    <p class="grey-text">Rp ${harga.toLocaleString()}</p>
                                </div>
                                <div class="col s5">
                                    <div class="input-field">
                                        <input type="number" name="item[${item.id_harga_satuan}]" 
                                               value="0" min="0" 
                                               data-harga="${harga}"
                                               onchange="hitungTotal()">
                                        <label>Jumlah</label>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                });
                document.getElementById('daftarItem').innerHTML = html;
                M.updateTextFields();
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('daftarItem').innerHTML = 
                    '<div class="card-panel red lighten-4">'+
                    'Gagal memuat data harga: ' + error.message + '</div>';
            });
    }

    function hitungTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('#daftarItem input[type="number"]');
        inputs.forEach(input => {
            total += input.value * parseInt(input.getAttribute('data-harga'));
        });
        document.getElementById('totalHarga').textContent = 'Total: Rp ' + total.toLocaleString();
    }
    </script>
</body>
</html>

<?php
if (isset($_POST["pesanKiloan"])) {
    error_log("Processing kiloan order. POST data: " . print_r($_POST, true));
    
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $estimasi_item = htmlspecialchars($_POST["estimasi_item"]);
    $tgl = date("Y-m-d"); 
    $tipe_layanan = "kiloan";

    mysqli_begin_transaction($connect);
    try {
        $query = "INSERT INTO cucian 
                  (id_agen, id_pelanggan, tgl_mulai, jenis, estimasi_item, alamat, catatan, status_cucian, tipe_layanan) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'Penjemputan', ?)";
        
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iissssss", $idAgen, $idPelanggan, $tgl, $jenis, $estimasi_item, $alamat, $catatan, $tipe_layanan);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error creating order: " . mysqli_error($connect));
        }

        $newOrderId = mysqli_insert_id($connect);
        error_log("Successfully created kiloan order with ID: " . $newOrderId);

        mysqli_commit($connect);
        echo "<script>
            console.log('Order created successfully, redirecting...');
            Swal.fire({
                title: 'Pesanan Berhasil!',
                text: 'Menunggu konfirmasi agen',
                icon: 'success'
            }).then(() => window.location = 'status.php');
        </script>";
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Error in kiloan order: " . $e->getMessage());
        echo "<script>Swal.fire('Error!','".$e->getMessage()."','error');</script>";
    }
}

if (isset($_POST["pesanSatuan"])) {
    error_log("Processing satuan order. POST data: " . print_r($_POST, true));
    
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $tgl = date("Y-m-d");
    $tipe_layanan = "satuan";
    
    mysqli_begin_transaction($connect);
    try {
        $query = "INSERT INTO cucian 
                  (id_agen, id_pelanggan, tgl_mulai, jenis, alamat, catatan, status_cucian, tipe_layanan) 
                  VALUES (?, ?, ?, ?, ?, ?, 'Penjemputan', ?)";
        
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iisssss", $idAgen, $idPelanggan, $tgl, $jenis, $alamat, $catatan, $tipe_layanan);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error creating order: " . mysqli_error($connect));
        }
        
        $cucian_id = mysqli_insert_id($connect);
        error_log("Created main order with ID: " . $cucian_id);
        
        $total_items = 0;
        
        foreach($_POST["item"] as $id_harga_satuan => $qty) {
            if ($qty > 0) {
                $total_items += $qty;
                
                // Get base price
                $qHarga = mysqli_prepare($connect, "SELECT harga FROM harga_satuan WHERE id_harga_satuan = ?");
                mysqli_stmt_bind_param($qHarga, "i", $id_harga_satuan);
                mysqli_stmt_execute($qHarga);
                $result = mysqli_stmt_get_result($qHarga);
                $baseHarga = mysqli_fetch_assoc($result)['harga'];
                
                // Calculate adjusted price
                $harga = $baseHarga;
                if($jenis === 'setrika') $harga *= 0.8;
                if($jenis === 'komplit') $harga *= 1.5;
                
                $subtotal = $qty * $harga;
                
                error_log("Adding item: Satuan ID $id_harga_satuan, Qty $qty, Price $harga");
                
                $stmt = mysqli_prepare($connect, "INSERT INTO detail_cucian 
                    (id_cucian, id_harga_satuan, jumlah, subtotal) 
                    VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iiid", $cucian_id, $id_harga_satuan, $qty, $subtotal);
                mysqli_stmt_execute($stmt);
            }
        }

        error_log("Updating total items to: " . $total_items);
        $stmt = mysqli_prepare($connect, "UPDATE cucian SET total_item = ? WHERE id_cucian = ?");
        mysqli_stmt_bind_param($stmt, "ii", $total_items, $cucian_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($connect);
        echo "<script>
            console.log('Order created successfully, redirecting...');
            Swal.fire({
                title: 'Pesanan Berhasil!',
                text: 'Menunggu konfirmasi agen',
                icon: 'success'
            }).then(() => window.location = 'status.php');
        </script>";
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Error in satuan order: " . $e->getMessage());
        echo "<script>Swal.fire('Error!','".$e->getMessage()."','error');</script>";
    }
}
?>
