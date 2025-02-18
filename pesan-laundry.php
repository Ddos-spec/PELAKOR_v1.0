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
            <form action="" method="post">
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
                    <div class="input-field">
                        <h5>Pilih Jenis Pakaian:</h5>
                        <div class="item-selection">
                            <label>
                                <input type="checkbox" class="item-checkbox" name="items[baju]" value="baju" onchange="toggleQuantity('baju')">
                                <span>Baju</span>
                                <input type="number" name="quantities[baju]" id="quantity-baju" class="item-quantity" min="0" value="0" disabled onchange="calculatePrice()">
                            </label>
                            <label>
                                <input type="checkbox" class="item-checkbox" name="items[celana]" value="celana" onchange="toggleQuantity('celana')">
                                <span>Celana</span>
                                <input type="number" name="quantities[celana]" id="quantity-celana" class="item-quantity" min="0" value="0" disabled onchange="calculatePrice()">
                            </label>
                            <label>
                                <input type="checkbox" class="item-checkbox" name="items[jaket]" value="jaket" onchange="toggleQuantity('jaket')">
                                <span>Jaket</span>
                                <input type="number" name="quantities[jaket]" id="quantity-jaket" class="item-quantity" min="0" value="0" disabled onchange="calculatePrice()">
                            </label>
                            <label>
                                <input type="checkbox" class="item-checkbox" name="items[karpet]" value="karpet" onchange="toggleQuantity('karpet')">
                                <span>Karpet</span>
                                <input type="number" name="quantities[karpet]" id="quantity-karpet" class="item-quantity" min="0" value="0" disabled onchange="calculatePrice()">
                            </label>
                            <label>
                                <input type="checkbox" class="item-checkbox" name="items[pakaian_khusus]" value="pakaian_khusus" onchange="toggleQuantity('pakaian_khusus')">
                                <span>Pakaian Khusus</span>
                                <input type="number" name="quantities[pakaian_khusus]" id="quantity-pakaian_khusus" class="item-quantity" min="0" value="0" disabled onchange="calculatePrice()">
                            </label>
                        </div>
                    </div>
                    <div class="input-field">
                        <ul>
                            <li><label for="jenis">Jenis Paket</label></li>
                            <li>
                            <?php if ($jenis == NULL) : ?>
                                <label><input id="jenis" name="jenis" value="cuci" type="radio" onchange="calculatePrice()"/><span>Cuci</span> </label>
                                <label><input id="jenis" name="jenis" value="setrika" type="radio" onchange="calculatePrice()"/><span>Setrika</span> </label>
                                <label><input id="jenis" name="jenis" value="komplit" type="radio" onchange="calculatePrice()"/><span>Komplit</span></label><li>
                            <?php elseif ($jenis == "cuci") : ?>
                                <label><input id="jenis" name="jenis" value="cuci" type="radio" checked onchange="calculatePrice()"/><span>Cuci</span> </label>
                                <label><input id="jenis" name="jenis" value="setrika" type="radio" onchange="calculatePrice()"/><span>Setrika</span> </label>
                                <label><input id="jenis" name="jenis" value="komplit" type="radio" onchange="calculatePrice()"/><span>Komplit</span></label><li>
                            <?php elseif ($jenis == "setrika") : ?>
                                <label><input id="jenis" name="jenis" value="cuci" type="radio" onchange="calculatePrice()"/><span>Cuci</span> </label>
                                <label><input id="jenis" name="jenis" value="setrika" type="radio" checked onchange="calculatePrice()"/><span>Setrika</span> </label>
                                <label><input id="jenis" name="jenis" value="komplit" type="radio" onchange="calculatePrice()"/><span>Komplit</span></label><li>
                            <?php elseif ($jenis == "komplit") : ?>
                                <label><input id="jenis" name="jenis" value="cuci" type="radio" onchange="calculatePrice()"/><span>Cuci</span> </label>
                                <label><input id="jenis" name="jenis" value="setrika" type="radio" onchange="calculatePrice()"/><span>Setrika</span> </label>
                                <label><input id="jenis" name="jenis" value="komplit" type="radio" checked onchange="calculatePrice()"/><span>Komplit</span></label><li>
                            <?php else : ?>
                                <label><input id="jenis" name="jenis" value="cuci" type="radio"/><span>Cuci</span> </label>
                                <label><input id="jenis" name="jenis" value="setrika" type="radio"/><span>Setrika</span> </label>
                                <label><input id="jenis" name="jenis" value="komplit" type="radio"/><span>Komplit</span></label><li>
                            <?php endif; ?>

                        </ul>
                    </div>
                    <div class="input-field">
                        <label for="catatan">Catatan</label>
                        <textarea class="materialize-textarea" name="catatan" id="catatan" cols="30" rows="10" placeholder="Tulis catatan untuk agen"></textarea>
                    </div>
                    <div class="input-field">
                        <h4>Perkiraan Harga: <span id="pricePreview">Rp 0</span></h4>
                    </div>
                    <div class="input-field center">
                        <button class="btn-large blue darken-2" type="submit" name="pesan">Buat Pesanan</button>
                    </div>
                    <script>
                        const itemPrices = {
                            baju: { cuci: 5000, setrika: 3000, komplit: 7000 },
                            celana: { cuci: 4000, setrika: 2500, komplit: 6000 },
                            jaket: { cuci: 6000, setrika: 4000, komplit: 9000 },
                            karpet: { cuci: 8000, setrika: 5000, komplit: 10000 },
                            pakaian_khusus: { cuci: 10000, setrika: 6000, komplit: 12000 }
                        };

                        function toggleQuantity(itemType) {
                            const checkbox = document.querySelector(`input[name="items[${itemType}]"]`);
                            const quantityInput = document.getElementById(`quantity-${itemType}`);
                            quantityInput.disabled = !checkbox.checked;
                            if (!checkbox.checked) {
                                quantityInput.value = 0;
                            }
                            calculatePrice();
                        }

                        function calculatePrice() {
                            const serviceType = document.querySelector('input[name="jenis"]:checked').value;
                            let totalPrice = 0;
                            
                            ['baju', 'celana', 'jaket', 'karpet', 'pakaian_khusus'].forEach(item => {
                                const quantity = parseInt(document.getElementById(`quantity-${item}`).value) || 0;
                                if (quantity > 0) {
                                    totalPrice += quantity * itemPrices[item][serviceType];
                                }
                            });

                            document.getElementById('pricePreview').innerText = `Rp ${totalPrice.toLocaleString()}`;
                        }

                        // Initialize price calculation on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            calculatePrice();
                        });
                    </script>
                </div>
            </form>
        </div>
    </div>
    <!-- end info pemesanan -->

    <!-- end body -->

    <!-- footer -->
    <?php include 'footer.php' ?>
    <!-- end footer -->
    
</body>
</html>

<?php

if (isset($_POST["pesan"])){
    $alamat = htmlspecialchars($_POST["alamat"]);
    $jenis = htmlspecialchars($_POST["jenis"]);
    $catatan = htmlspecialchars($_POST["catatan"]);
    $tgl = htmlspecialchars(date("Y-m-d H:i:s"));
    
    // Process items and quantities
    $items = $_POST['items'] ?? [];
    $quantities = $_POST['quantities'] ?? [];
    $totalItems = 0;
    $itemDetails = [];
    
    foreach ($items as $item => $value) {
        $quantity = (int)($quantities[$item] ?? 0);
        if ($quantity > 0) {
            $totalItems += $quantity;
            $itemDetails[] = ucfirst($item) . ' (' . $quantity . ')';
        }
    }
    
    $itemType = implode(', ', $itemDetails);

    $query = mysqli_query($connect, "INSERT INTO cucian (id_agen, id_pelanggan, tgl_mulai, jenis, item_type, total_item, alamat, catatan, status_cucian) VALUES ($idAgen, $idPelanggan, '$tgl', '$jenis', '$itemType', $totalItems, '$alamat', '$catatan', 'Penjemputan')");

    if (mysqli_affected_rows($connect) > 0){
        echo "
            <script>
                Swal.fire('Pesanan Berhasil Dibuat','Silahkan Pergi Ke Halaman Status Cucian','success').then(function(){
                    window.location = 'status.php';
                });
            </script>
        ";
    }else {
        echo mysqli_error($connect);
    }
}



?>