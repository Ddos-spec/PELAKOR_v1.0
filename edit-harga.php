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
        .item-satuan {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .btn-hapus {
            margin-top: 25px;
        }
    </style>
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

        <!-- Harga Satuan -->
        <div id="hargaSatuan">
            <div class="row">
                <div class="col s12">
                    <button type="button" class="btn blue" onclick="tambahItemSatuan()">
                        <i class="material-icons left">add</i>Tambah Item
                    </button>
                </div>
            </div>
            <form action="" method="post" id="formHargaSatuan">
                <div id="listItemSatuan">
                    <!-- Template awal -->
                    <div class="row item-satuan" id="template-item" style="display: none;">
                        <div class="col s5">
                            <div class="input-field">
                                <input type="text" name="nama_item[]" required>
                                <label>Nama Item</label>
                            </div>
                        </div>
                        <div class="col s5">
                            <div class="input-field">
                                <input type="number" name="harga_satuan[]" required>
                                <label>Harga (Rp)</label>
                            </div>
                        </div>
                        <div class="col s2">
                            <button type="button" class="btn-floating red btn-hapus" onclick="hapusItem(this)">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                    </div>

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
                            <button type="button" class="btn-floating red btn-hapus" onclick="hapusItem(this)">
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
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php" ?>
    <!-- end footer -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var tabs = document.querySelectorAll('.tabs');
        M.Tabs.init(tabs);
        
        if(document.querySelectorAll('.item-satuan:not(#template-item)').length === 0) {
            tambahItemSatuan();
        }
        
        // Add form validation
        document.getElementById('formHargaSatuan').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('.item-satuan:not(#template-item)');
            let isValid = true;
            
            items.forEach((item, index) => {
                const nama = item.querySelector('[name="nama_item[]"]').value;
                const harga = item.querySelector('[name="harga_satuan[]"]').value;
                
                if(!nama || !harga) {
                    isValid = false;
                    M.toast({html: `Item ke-${index + 1} tidak lengkap`});
                }
            });
            
            if(!isValid) {
                e.preventDefault();
            }
        });
    });

    function tambahItemSatuan() {
        console.log('Menambah item baru');
        var template = document.getElementById('template-item');
        if (!template) {
            console.error('Template item tidak ditemukan');
            return;
        }
        
        var newItem = template.cloneNode(true);
        newItem.style.display = 'block';
        newItem.removeAttribute('id');
        
        console.log('Mereset nilai input');
        newItem.querySelectorAll('input').forEach(input => {
            input.value = '';
        });
        
        document.getElementById('listItemSatuan').appendChild(newItem);
        M.updateTextFields();
        console.log('Item baru ditambahkan');
    }

    function hapusItem(btn) {
        var items = document.querySelectorAll('.item-satuan:not(#template-item)');
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

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    // 1. Validate form data existence
    if(!isset($_POST["nama_item"]) || !isset($_POST["harga_satuan"])) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Data tidak lengkap',
                icon: 'error'
            });
        </script>";
        exit;
    }

    $nama_items = $_POST["nama_item"];
    $harga_satuans = $_POST["harga_satuan"];
    
    mysqli_begin_transaction($connect);
    
    try {
        error_log("Menghapus data lama untuk agen: " . $idAgen);
        
        mysqli_query($connect, "DELETE FROM harga_satuan WHERE id_agen = '$idAgen'");
        
        foreach($nama_items as $i => $nama) {
            $nama = htmlspecialchars($nama);
            $harga = htmlspecialchars($harga_satuans[$i]);
            
            // Detailed validation
            if(empty($nama)) throw new Exception("Nama item ke-".($i+1)." kosong");
            if(empty($harga)) throw new Exception("Harga item ke-".($i+1)." kosong");
            if(!is_numeric($harga)) throw new Exception("Harga harus berupa angka");
            if($harga <= 0) throw new Exception("Harga harus lebih dari 0");
            
            $query = "INSERT INTO harga_satuan (id_agen, nama_item, harga) 
                     VALUES ('$idAgen', '$nama', '$harga')";
            
            error_log("Executing query: " . $query);
            
            if(!mysqli_query($connect, $query)) {
                throw new Exception(mysqli_error($connect));
            }
        }
        
        mysqli_commit($connect);
        
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data berhasil disimpan',
                icon: 'success'
            }).then(() => {
                window.location.reload();
            });
        </script>";
        
    } catch(Exception $e) {
        mysqli_rollback($connect);
        
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: '".$e->getMessage()."',
                icon: 'error'
            });
        </script>";
        
        error_log("Error in simpanSatuan: " . $e->getMessage());
    }
}

?>