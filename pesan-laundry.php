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
</head>
<body>

    <!-- header -->
    <?php include 'header.php' ?>
    <!-- end header -->

    <!-- body -->

    <!-- info laundry -->
    <div class="row">
        <div class="col s2 offset-s4">
            <img src="img/logo.png" width="70%" />
            <a id="download-button" class="btn waves-effect waves-light red darken-3" href="pesan-laundry.php?id=<?= $idAgen ?>">PESAN LAUNDRY</a>
        </div>
        <div class="col s6">
            <h3><?= $agen["nama_laundry"] ?></h3>
            <ul>
                <li>
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
                            
                        if ( $totalStar == 0 ) {
                    ?>
                        <fieldset class="bintang"><span class="starImg star-0"></span></fieldset>
                    <?php }else { ?>
                        <fieldset class="bintang"><span class="starImg star-<?= $fixStar ?>"></span></fieldset>
                    <?php } ?>
                </li>
                <li>Alamat : <?= $agen["alamat"] . ", " . $agen["kota"] ?></li>
                <li>No. HP : <?= $agen["telp"] ?></li>
            </ul>
        </div>
    </div>
    <!-- end info laundry -->
    
    <!-- info pemesanan -->
    <div class="row">
        <div class="col s10 offset-s1">
            <form action="" method="post" id="formPesanan" onsubmit="return validateForm()">
                <div class="col s5">
                    <h3 class="header light center">Data Diri</h3>
                    <br>
                    <div class="input-field">
                        <label for="nama">Nama Penerima</label>
                        <input id="nama" type="text" disabled value="<?= $pelanggan['nama'] ?>">
                    </div>
                    <div class="input-field">
                        <label for="telp">No Telp</label>
                        <input id="telp" type="text" disabled value="<?= $pelanggan['telp'] ?>">
                    </div>
                    <div class="input-field">
                        <label for="alamat">Alamat</label>
                        <textarea class="materialize-textarea" name="alamat" id="alamat" cols="30" rows="10"><?= $pelanggan['alamat'] . ", " . $pelanggan['kota'] ?></textarea>
                    </div>
                </div>
                <div class="col s5 offset-s1">
                    <h3 class="header light center">Info Paket Laundry</h3>
                    <br>
                    <!-- Tab untuk pilih tipe layanan -->
                    <div class="input-field">
                        <select class="browser-default" name="tipe_layanan" id="tipe_layanan" onchange="showForm()">
                            <option value="" disabled selected>Pilih Tipe Layanan</option>
                            <option value="kiloan">Kiloan</option>
                            <option value="satuan">Satuan</option>
                        </select>
                    </div>

                    <!-- Form Kiloan -->
                    <div id="form-kiloan" style="display:none;">
                        <div class="input-field">
                            <label for="total">Estimasi Jumlah Item</label>
                            <input type="text" name="estimasi_item">
                        </div>
                        <div class="input-field">
                            <ul>
                                <li><label>Jenis Paket</label></li>
                                <li>
                                    <label><input name="jenis" value="cuci" type="radio"/><span>Cuci</span></label>
                                    <label><input name="jenis" value="setrika" type="radio"/><span>Setrika</span></label>
                                    <label><input name="jenis" value="komplit" type="radio"/><span>Komplit</span></label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Form Satuan -->
                    <div id="form-satuan" style="display:none;">
                        <?php
                        $items = mysqli_query($connect, "SELECT * FROM harga_satuan WHERE id_agen = $idAgen");
                        while($item = mysqli_fetch_assoc($items)):
                        ?>
                        <div class="input-field">
                            <label for="item_<?= $item['id_harga_satuan'] ?>"><?= $item['nama_item'] ?> (Rp <?= number_format($item['harga']) ?>)</label>
                            <input type="number" min="0" name="items[<?= $item['id_harga_satuan'] ?>]" id="item_<?= $item['id_harga_satuan'] ?>" value="0">
                        </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="input-field">
                        <label for="catatan">Catatan</label>
                        <textarea class="materialize-textarea" name="catatan" id="catatan"></textarea>
                    </div>
                    <div class="input-field center">
                        <button class="btn-large blue darken-2" type="submit" name="pesan">Buat Pesanan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showForm() {
        var tipe = document.getElementById('tipe_layanan').value;
        if(tipe == 'kiloan') {
            document.getElementById('form-kiloan').style.display = 'block';
            document.getElementById('form-satuan').style.display = 'none';
        } else {
            document.getElementById('form-kiloan').style.display = 'none';
            document.getElementById('form-satuan').style.display = 'block';
        }
    }
    </script>
    <!-- end info pemesanan -->

    <!-- end body -->

    <!-- footer -->
    <?php include 'footer.php' ?>
    <!-- end footer -->
    
</body>
</html>

<?php

if (isset($_POST["pesan"])) {
    include 'functions/validasi-pesanan.php';
    
    $errors = validasiPesananBaru($_POST);
    
    if(empty($errors)) {
        $alamat = htmlspecialchars($_POST["alamat"]);
        $tipe_layanan = $_POST["tipe_layanan"];
        $catatan = htmlspecialchars($_POST["catatan"]);
        $tgl = date("Y-m-d H:i:s");
        
        mysqli_begin_transaction($connect);
        try {
            if($tipe_layanan == 'kiloan') {
                $jenis = htmlspecialchars($_POST["jenis"]);
                $estimasi_item = htmlspecialchars($_POST["estimasi_item"]);
                
                mysqli_query($connect, "INSERT INTO cucian (id_agen, id_pelanggan, tgl_mulai, jenis, tipe_layanan, estimasi_item, alamat, catatan, status_cucian) 
                    VALUES ($idAgen, $idPelanggan, '$tgl', '$jenis', 'kiloan', '$estimasi_item', '$alamat', '$catatan', 'Penjemputan')");
                    
            } else {
                // Insert cucian satuan
                mysqli_query($connect, "INSERT INTO cucian (id_agen, id_pelanggan, tgl_mulai, tipe_layanan, alamat, catatan, status_cucian) 
                    VALUES ($idAgen, $idPelanggan, '$tgl', 'satuan', '$alamat', '$catatan', 'Penjemputan')");
                
                $id_cucian = mysqli_insert_id($connect);
                
                // Insert detail items
                foreach($_POST['items'] as $id_harga_satuan => $jumlah) {
                    if($jumlah > 0) {
                        $harga = mysqli_query($connect, "SELECT harga FROM harga_satuan WHERE id_harga_satuan = $id_harga_satuan");
                        $harga = mysqli_fetch_assoc($harga)['harga'];
                        $subtotal = $jumlah * $harga;
                        
                        mysqli_query($connect, "INSERT INTO detail_cucian (id_cucian, id_harga_satuan, jumlah, subtotal)
                            VALUES ($id_cucian, $id_harga_satuan, $jumlah, $subtotal)");
                    }
                }
            }
            
            mysqli_commit($connect);
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan Berhasil Dibuat',
                    text: 'Silahkan cek status cucian Anda',
                    showConfirmButton: true
                }).then(() => {
                    window.location = 'status.php';
                });
            </script>";
        } catch(Exception $e) {
            mysqli_rollback($connect);
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memproses pesanan'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '" . implode('<br>', $errors) . "'
            });
        </script>";
    }
}

?>