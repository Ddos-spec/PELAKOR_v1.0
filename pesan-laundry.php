<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekPelanggan();

// ambil data agen
$idAgen = $_GET["id"];
$query = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$idAgen'");
$agen = mysqli_fetch_assoc($query);

if (isset($_GET["jenis"])){
    $jenis = $_GET["jenis"];
}else {
    $jenis = NULL;
}

// ambil data pelanggan
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
    <!-- header -->
    <?php include 'header.php' ?>
    <!-- end header -->

    <div class="container">
        <!-- Profil dan Data Diri -->
        <div class="row">
            <!-- Profil Agen -->
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

            <!-- Data Diri -->
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Data Penerima</span>
                        <div class="input-field">
                            <input id="nama" type="text" disabled value="<?= $pelanggan['nama'] ?>">
                            <label for="nama">Nama Penerima</label>
                        </div>
                        <div class="input-field">
                            <input id="telp" type="text" disabled value="<?= $pelanggan['telp'] ?>">
                            <label for="telp">No Telp</label>
                        </div>
                        <div class="input-field">
                            <textarea class="materialize-textarea" name="alamat" id="alamat"><?= $pelanggan['alamat'] . ", " . $pelanggan['kota'] ?></textarea>
                            <label for="alamat">Alamat</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pemesanan -->
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
                                        <textarea class="materialize-textarea" name="estimasi_item" id="estimasiItem" placeholder="Contoh: 3 baju, 2 celana, 4 kaos"></textarea>
                                        <label for="estimasiItem">Estimasi Item (Opsional)</label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <textarea class="materialize-textarea" name="catatan" id="catatanKiloan"></textarea>
                                        <label for="catatanKiloan">Catatan</label>
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
                                <textarea class="materialize-textarea" name="catatan" id="catatanSatuan"></textarea>
                                <label for="catatanSatuan">Catatan</label>
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

    <!-- footer -->
    <?php include 'footer.php' ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tabs
        var tabs = document.querySelectorAll('.tabs');
        M.Tabs.init(tabs);

        // Load initial items for satuan
        updateDaftarItem('cuci');
    });

    function updateDaftarItem(jenis) {
        fetch('get_harga_satuan.php?id_agen=<?= $idAgen ?>&jenis=' + jenis)
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(item => {
                    html += `
                    <div class="card-panel-item">
                        <div class="row">
                            <div class="col s7">
                                <p>${item.nama_item}</p>
                                <p class="grey-text">Rp ${parseInt(item.harga).toLocaleString()}</p>
                            </div>
                            <div class="col s5">
                                <div class="input-field">
                                    <input type="number" name="item[${item.id_harga_satuan}]" 
                                           value="0" min="0" 
                                           data-harga="${item.harga}"
                                           onchange="hitungTotal()">
                                    <label>Jumlah</label>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
                document.getElementById('daftarItem').innerHTML = html;
                M.updateTextFields();
                hitungTotal();
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
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $estimasi_item = htmlspecialchars($_POST["estimasi_item"]);
    $tgl = date("Y-m-d H:i:s");
    $tipe_layanan = "kiloan";

    $query = mysqli_query($connect, "INSERT INTO cucian (id_agen, id_pelanggan, tgl_mulai, jenis, estimasi_item, alamat, catatan, status_cucian, tipe_layanan) 
                                   VALUES ($idAgen, $idPelanggan, '$tgl', '$jenis', '$estimasi_item', '$alamat', '$catatan', 'Penjemputan', '$tipe_layanan')");

    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Pesanan Berhasil Dibuat','Silahkan Pergi Ke Halaman Status Cucian','success').then(function(){
                    window.location = 'status.php';
                });
            </script>
        ";
    } else {
        echo mysqli_error($connect);
    }
}

if (isset($_POST["pesanSatuan"])) {
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $tgl = date("Y-m-d H:i:s");
    $tipe_layanan = "satuan";
    
    // Insert main order
    $query = mysqli_query($connect, "INSERT INTO cucian (id_agen, id_pelanggan, tgl_mulai, jenis, alamat, catatan, status_cucian, tipe_layanan) 
                                   VALUES ($idAgen, $idPelanggan, '$tgl', '$jenis', '$alamat', '$catatan', 'Penjemputan', '$tipe_layanan')");

    if (mysqli_affected_rows($connect) > 0) {
        $cucian_id = mysqli_insert_id($connect);
        $total_items = 0;
        
        // Process each item
        foreach($_POST["item"] as $id_harga_satuan => $qty) {
            if ($qty > 0) {
                $total_items += $qty;
                // Get item price
                $q = mysqli_query($connect, "SELECT harga FROM harga_satuan WHERE id_harga_satuan = $id_harga_satuan");
                $harga = mysqli_fetch_assoc($q)['harga'];
                $subtotal = $qty * $harga;
                
                mysqli_query($connect, "INSERT INTO detail_cucian (id_cucian, id_harga_satuan, jumlah, subtotal) 
                                      VALUES ($cucian_id, $id_harga_satuan, $qty, $subtotal)");
            }
        }
        
        // Update total items
        mysqli_query($connect, "UPDATE cucian SET total_item = $total_items WHERE id_cucian = $cucian_id");

        echo "
            <script>
                Swal.fire('Pesanan Berhasil Dibuat','Silahkan Pergi Ke Halaman Status Cucian','success').then(function(){
                    window.location = 'status.php';
                });
            </script>
        ";
    } else {
        echo mysqli_error($connect);
    }
}
?>