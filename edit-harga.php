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
        
        <!-- Tab Navigation -->
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s6"><a class="active" href="#hargaKiloan">Harga Kiloan</a></li>
                    <li class="tab col s6"><a href="#hargaSatuan">Harga Satuan</a></li>
                </ul>
            </div>
        </div>

        <!-- Harga Kiloan -->
        <div id="hargaKiloan">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Harga Layanan Kiloan</span>
                    <form action="" method="post">
                        <div class="input-field">
                            <input type="number" id="cuci" name="cuci" value="<?= $cuci['harga'] ?>">
                            <label for="cuci">Cuci (Rp/Kg)</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="setrika" name="setrika" value="<?= $setrika['harga'] ?>">
                            <label for="setrika">Setrika (Rp/Kg)</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="komplit" name="komplit" value="<?= $komplit['harga'] ?>">
                            <label for="komplit">Cuci + Setrika (Rp/Kg)</label>
                        </div>
                        <div class="center">
                            <button class="btn-large blue darken-2" type="submit" name="simpanKiloan">
                                Simpan Harga Kiloan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Harga Satuan -->
        <div id="hargaSatuan">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Harga Layanan Satuan</span>
                    <form action="" method="post" id="formHargaSatuan">
                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn blue" onclick="tambahItemSatuan()">
                                    <i class="material-icons left">add</i>Tambah Item
                                </button>
                            </div>
                        </div>
                        <div id="listItemSatuan">
                            <?php
                            $querySatuan = mysqli_query($connect, "SELECT * FROM harga_satuan WHERE id_agen = '$idAgen'");
                            while($item = mysqli_fetch_assoc($querySatuan)):
                            ?>
                            <div class="row item-satuan">
                                <div class="col s5">
                                    <div class="input-field">
                                        <input type="text" name="nama_item[]" value="<?= $item['nama_item'] ?>" required>
                                        <label>Nama Item</label>
                                    </div>
                                </div>
                                <div class="col s5">
                                    <div class="input-field">
                                        <input type="number" name="harga_satuan[]" value="<?= $item['harga'] ?>" required>
                                        <label>Harga (Rp)</label>
                                    </div>
                                </div>
                                <div class="col s2">
                                    <button type="button" class="btn-floating red" onclick="hapusItem(this)">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="center">
                            <button class="btn-large blue darken-2" type="submit" name="simpanSatuan">
                                Simpan Harga Satuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php" ?>
    <!-- end footer -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabs = document.querySelectorAll('.tabs');
            M.Tabs.init(tabs);
        });

        function tambahItemSatuan() {
            var template = document.querySelector('.item-satuan').cloneNode(true);
            template.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            document.getElementById('listItemSatuan').appendChild(template);
            M.updateTextFields();
        }

        function hapusItem(btn) {
            var items = document.querySelectorAll('.item-satuan');
            if(items.length > 1) {
                btn.closest('.item-satuan').remove();
            } else {
                M.toast({html: 'Minimal harus ada 1 item!'});
            }
        }
    </script>

</body>
</html>

<?php

// fungsi mengubah harga
function ubahHarga($data){
    global $connect, $idAgen;

    $hargaCuci = htmlspecialchars($data["cuci"]);
    $hargaSetrika = htmlspecialchars($data["setrika"]);
    $hargaKomplit = htmlspecialchars($data["komplit"]);

    validasiHarga($hargaCuci);
    validasiHarga($hargaSetrika);
    validasiHarga($hargaKomplit);

    $query1 = "UPDATE harga SET
        harga = $hargaCuci
        WHERE jenis = 'cuci' AND id_agen = $idAgen
    ";
    $query2 = "UPDATE harga SET
        harga = $hargaSetrika
        WHERE jenis = 'setrika' AND id_agen = $idAgen
    ";
    $query3 = "UPDATE harga SET
        harga = $hargaKomplit
        WHERE jenis = 'komplit' AND id_agen = $idAgen
    ";

    mysqli_query($connect,$query1);
    $hasil1 = mysqli_affected_rows($connect);
    mysqli_query($connect,$query2);
    $hasil2 = mysqli_affected_rows($connect);
    mysqli_query($connect,$query3);
    $hasil3 = mysqli_affected_rows($connect);

    return $hasil1+$hasil2+$hasil3;
}


// jika user menekan tombol simpan harga
if (isset($_POST["simpanKiloan"])) {
    if (ubahHarga($_POST) > 0) {
        echo "<script>Swal.fire('Berhasil','Harga kiloan berhasil diupdate','success');</script>";
    }
}

if (isset($_POST["simpanSatuan"])) {
    $nama_items = $_POST["nama_item"];
    $harga_satuans = $_POST["harga_satuan"];
    
    mysqli_query($connect, "DELETE FROM harga_satuan WHERE id_agen = '$idAgen'");
    
    foreach($nama_items as $i => $nama) {
        $harga = $harga_satuans[$i];
        mysqli_query($connect, "INSERT INTO harga_satuan (id_agen, nama_item, harga) 
                              VALUES ('$idAgen', '$nama', '$harga')");
    }
    
    echo "<script>Swal.fire('Berhasil','Harga satuan berhasil diupdate','success');</script>";
}

?>