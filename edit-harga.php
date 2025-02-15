<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAgen();

$idAgen = $_SESSION["agen"];

// Improved data fetching with default values
$cuci = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'cuci'");
$cuci = mysqli_fetch_assoc($cuci);
$hargaCuci = isset($cuci['harga']) ? $cuci['harga'] : 0;

$setrika = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'setrika'");
$setrika = mysqli_fetch_assoc($setrika);
$hargaSetrika = isset($setrika['harga']) ? $setrika['harga'] : 0;

$komplit = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen' AND jenis = 'komplit'");
$komplit = mysqli_fetch_assoc($komplit);
$hargaKomplit = isset($komplit['harga']) ? $komplit['harga'] : 0;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all requests
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST Data: " . print_r($_POST, true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Raw POST data: " . file_get_contents('php://input'));
    error_log("POST array: " . print_r($_POST, true));
}

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
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Harga Layanan Kiloan</span>
                    <form action="" method="post">
                        <div class="input-field">
                            <input type="number" id="cuci" name="cuci" value="<?= $hargaCuci ?>">
                            <label for="cuci">Cuci (Rp/Kg)</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="setrika" name="setrika" value="<?= $hargaSetrika ?>">
                            <label for="setrika">Setrika (Rp/Kg)</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="komplit" name="komplit" value="<?= $hargaKomplit ?>">
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
            <div class="row">
                <div class="col s12">
                    <button type="button" class="btn blue" onclick="tambahItemSatuan()">
                        <i class="material-icons left">add</i>Tambah Item
                    </button>
                </div>
            </div>
            <form action="" method="post" id="formHargaSatuan" novalidate>
                <input type="hidden" name="form_type" value="harga_satuan">
                <div id="listItemSatuan">
                    <!-- Template awal -->
                    <div class="row item-satuan" id="template-item" style="display: none;">
                        <div class="col s5">
                            <div class="input-field">
                                <input type="text" 
                                       id="nama_item_template" 
                                       name="nama_item[]"
                                       class="validate">
                                <label for="nama_item_template">Nama Item</label>
                            </div>
                        </div>
                        <div class="col s5">
                            <div class="input-field">
                                <input type="number" 
                                       id="harga_satuan_template" 
                                       name="harga_satuan[]"
                                       class="validate">
                                <label for="harga_satuan_template">Harga (Rp)</label>
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
                    $counter = 0;
                    while($item = mysqli_fetch_assoc($querySatuan)):
                        $counter++;
                    ?>
                    <div class="row item-satuan">
                        <div class="col s5">
                            <div class="input-field">
                                <input type="text" 
                                       id="nama_item_<?= $counter ?>" 
                                       name="nama_item[]" 
                                       value="<?= $item['nama_item'] ?>" 
                                       required>
                                <label for="nama_item_<?= $counter ?>">Nama Item</label>
                            </div>
                        </div>
                        <div class="col s5">
                            <div class="input-field">
                                <input type="number" 
                                       id="harga_satuan_<?= $counter ?>" 
                                       name="harga_satuan[]" 
                                       value="<?= $item['harga'] ?>" 
                                       required>
                                <label for="harga_satuan_<?= $counter ?>">Harga (Rp)</label>
                            </div>
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
        // Initialize all Materialize components
        M.AutoInit();
        
        // Add first item if empty
        if(document.querySelectorAll('.item-satuan:not(#template-item)').length === 0) {
            tambahItemSatuan();
        }

        var tabs = document.querySelectorAll('.tabs');
        var tabsInstance = M.Tabs.init(tabs, {
            onShow: function(tab) {
                // Save active tab to localStorage
                localStorage.setItem('activeTab', tab.id);
            }
        });
        
        // Restore active tab after refresh
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            var instance = M.Tabs.getInstance(tabs[0]);
            instance.select(activeTab);
        }

        // Improved form validation
        document.getElementById('formHargaSatuan').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const items = document.querySelectorAll('.item-satuan:not(#template-item)');
            let isValid = true;
            let formData = new FormData(this);
            
            // Ensure form_type and simpanSatuan are sent
            formData.append('simpanSatuan', '1');
            
            items.forEach((item, index) => {
                const nama = item.querySelector('[name="nama_item[]"]');
                const harga = item.querySelector('[name="harga_satuan[]"]');
                
                if(!nama.value.trim()) {
                    isValid = false;
                    M.toast({html: `Nama item ke-${index + 1} tidak boleh kosong`});
                }
                
                if(!harga.value || parseFloat(harga.value) <= 0) {
                    isValid = false;
                    M.toast({html: `Harga item ke-${index + 1} harus lebih dari 0`});
                }
            });
            
            if(isValid) {
                // Submit form using AJAX
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(html => {
                    if(html.includes('Berhasil')) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data tersimpan',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = window.location.href + '#hargaSatuan';
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', 'Gagal menyimpan data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Terjadi kesalahan', 'error');
                });
            }
        });
    });

    function tambahItemSatuan() {
        var template = document.getElementById('template-item');
        if (!template) {
            console.error('Template item tidak ditemukan');
            return;
        }
        
        var newItem = template.cloneNode(true);
        newItem.style.display = 'block';
        newItem.removeAttribute('id');
        
        // Generate unique IDs
        var timestamp = new Date().getTime();
        var namaInput = newItem.querySelector('input[name="nama_item[]"]');
        var hargaInput = newItem.querySelector('input[name="harga_satuan[]"]');
        
        // Remove tabindex from cloned items
        namaInput.removeAttribute('tabindex');
        hargaInput.removeAttribute('tabindex');
        
        // Set new IDs
        namaInput.id = 'nama_item_' + timestamp;
        hargaInput.id = 'harga_satuan_' + timestamp;
        
        // Update labels
        newItem.querySelector('label[for="nama_item_template"]').setAttribute('for', 'nama_item_' + timestamp);
        newItem.querySelector('label[for="harga_satuan_template"]').setAttribute('for', 'harga_satuan_' + timestamp);
        
        // Reset values
        namaInput.value = '';
        hargaInput.value = '';
        
        document.getElementById('listItemSatuan').appendChild(newItem);
        
        // Initialize Materialize select
        M.updateTextFields();
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
    // Check if data exists
    $check = mysqli_query($connect, "SELECT COUNT(*) as count FROM harga WHERE id_agen = '$idAgen'");
    $row = mysqli_fetch_assoc($check);
    
    if($row['count'] == 0) {
        // Insert new data
        $hargaCuci = htmlspecialchars($_POST["cuci"]);
        $hargaSetrika = htmlspecialchars($_POST["setrika"]);
        $hargaKomplit = htmlspecialchars($_POST["komplit"]);
        
        validasiHarga($hargaCuci);
        validasiHarga($hargaSetrika);
        validasiHarga($hargaKomplit);
        
        $queries = [
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('cuci', '$idAgen', '$hargaCuci')",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('setrika', '$idAgen', '$hargaSetrika')",
            "INSERT INTO harga (jenis, id_agen, harga) VALUES ('komplit', '$idAgen', '$hargaKomplit')"
        ];
        
        $success = true;
        foreach($queries as $query) {
            if(!mysqli_query($connect, $query)) {
                $success = false;
                break;
            }
        }
        
        if($success) {
            echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Harga berhasil disimpan',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            </script>";
        }
    } else {
        // Update existing data
        if (ubahHarga($_POST) > 0) {
            echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Harga berhasil diupdate',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            </script>";
        }
    }
}

if (isset($_POST["simpanSatuan"])) {
    error_log("Received POST data for harga satuan: " . print_r($_POST, true));
    
    if(!isset($_POST["nama_item"]) || !isset($_POST["harga_satuan"])) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit;
    }
    
    $nama_items = $_POST["nama_item"];
    $harga_satuans = $_POST["harga_satuan"];
    
    mysqli_begin_transaction($connect);
    
    try {
        mysqli_query($connect, "DELETE FROM harga_satuan WHERE id_agen = '$idAgen'");
        
        $inserted = false;
        foreach($nama_items as $i => $nama) {
            if(empty(trim($nama))) continue;
            
            $nama = mysqli_real_escape_string($connect, $nama);
            $harga = (int)$harga_satuans[$i];
            
            if($harga <= 0) continue;
            
            $query = "INSERT INTO harga_satuan (id_agen, nama_item, harga) 
                     VALUES ('$idAgen', '$nama', '$harga')";
            
            if(mysqli_query($connect, $query)) {
                $inserted = true;
            } else {
                throw new Exception(mysqli_error($connect));
            }
        }
        
        if(!$inserted) {
            throw new Exception("Tidak ada data valid untuk disimpan");
        }
        
        mysqli_commit($connect);
        echo "<script>Swal.fire('Berhasil!', 'Data tersimpan', 'success');</script>";
        
    } catch(Exception $e) {
        mysqli_rollback($connect);
        error_log("Error saving harga satuan: " . $e->getMessage());
        echo "<script>Swal.fire('Error!', '" . addslashes($e->getMessage()) . "', 'error');</script>";
    }
    exit;
}

?>