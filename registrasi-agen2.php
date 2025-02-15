<?php

session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekAgen();

$idAgen = $_SESSION["agen"];

// ambil data agen
$query = "SELECT * FROM agen WHERE id_agen = '$idAgen'";
$result = mysqli_query($connect, $query);
$agen = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "headtags.html"; ?>
    <title>Registrasi Agen Lanjutan</title>
</head>
<body>
    <?php include 'header.php' ?>

    <div class="row">
        <!-- term -->
        <div class="col s4 offset-s1">
            <div class="card">
                <div class="col center" style="margin:20px">
                    <img src="img/banner.png" alt="laundryku" width=100%/><br><br>
                    <span class="card-title black-text">Syarat dan Ketentuan :</span>
                </div>
                <div class="card-content">
                    <p>1.	Memiliki lokasi usaha laundry yang strategis dan teridentifikasi oleh google map</p>
                    <p>2.	Agen memiliki nama usaha serta logo perusahaan agar dapat diposting di website laundryKU</p>
                    <p>3.	Mampu memberikan layanan Laundry dengan kualitas prima dan harga yang bersaing</p>
                    <p>4.	Memiliki driver yang bersedia untuk melakukan penjemputan dan pengantaran terhadap laundry pelanggan</p>
                    <p>5.	Harga dari jenis laundry ditentukan berdasarkan berat per kilo (kg) ditambah dengan biaya ongkos kirim</p>
                    <p>6.	Bersedia untuk memberikan informasi kepada pelanggan mengenai harga Laundry Kiloan</p>
                    <p>7.	Bersedia untuk menerapkan sistem poin kepada pelanggan</p>
                    <p>8.	Bersedia memberikan kompensasi untuk setiap kemungkinan terjadinya seperti kehilangan pakaian atau kerusakan pakaian pada saat proses Laundry dilakukan</p>
                    <p>9.	Agen tidak diperkenankan untuk melakukan kerjasama dengan pihak Laundry lainnya</p>
                    <p>10.	Sebagai kompensasi atas kerjasama adalah sistem bagi hasil sebesar 5%, yang diperhitungkan dari setiap 7 hari</p>
                    <p>11.	Status agen secara otomatis dicabut apabila melanggar kesepakatan yang telah ditetapkan dalam surat perjanjian kerjasama ataupun agen ingin mengundurkan diri</p>
                </div>
                <div class="card-action">
                    <a href="term.php">Baca Selengkapnya</a>
                </div>
            </div>  
        </div>
        <!-- end term -->

        <!-- Updated price section -->
        <div class="col s6 offset-s1">
            <div class="card">
                <div class="card-content">
                    <span class="card-title center">Pengaturan Harga Awal</span>
                    
                    <ul class="tabs">
                        <li class="tab col s6"><a class="active" href="#setupKiloan">Harga Kiloan</a></li>
                        <li class="tab col s6"><a href="#setupSatuan">Harga Satuan</a></li>
                    </ul>

                    <div id="setupKiloan">
                        <form action="" method="post">
                            <div class="input-field">
                                <input type="number" name="cuci" required>
                                <label>Cuci (Rp/Kg)</label>
                            </div>
                            <div class="input-field">
                                <input type="number" name="setrika" required>
                                <label>Setrika (Rp/Kg)</label>
                            </div>
                            <div class="input-field">
                                <input type="number" name="komplit" required>
                                <label>Cuci + Setrika (Rp/Kg)</label>
                            </div>
                            <div class="center">
                                <button class="btn-large blue darken-2" type="submit" name="setupKiloan">
                                    Simpan & Lanjutkan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="setupSatuan">
                        <form action="" method="post" id="formSetupSatuan">
                            <div class="row">
                                <div class="col s12">
                                    <button type="button" class="btn blue" onclick="tambahItemSatuan()">
                                        <i class="material-icons left">add</i>Tambah Item
                                    </button>
                                </div>
                            </div>
                            <div id="listItemSatuan">
                                <div class="row item-satuan">
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
                                        <button type="button" class="btn-floating red" onclick="hapusItem(this)">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="center">
                                <button class="btn-large blue darken-2" type="submit" name="setupSatuan">
                                    Simpan & Lanjutkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

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

function dataHarga($data){
    global $connect, $idAgen;

    $cuci = htmlspecialchars($data["cuci"]);
    $setrika = htmlspecialchars($data["setrika"]);
    $komplit = htmlspecialchars($data["komplit"]);

    validasiHarga($cuci);
    validasiHarga($setrika);
    validasiHarga($komplit);

    $query2 = "INSERT INTO harga VALUES(
        '',
        'cuci',
        '$idAgen',
        '$cuci'
    )";
    $query3 = "INSERT INTO harga VALUES(
        '',
        'setrika',
        '$idAgen',
        '$setrika'
    )";
    $query4 = "INSERT INTO harga VALUES(
        '',
        'komplit',
        '$idAgen',
        '$komplit'
    )";

    $result2 = mysqli_query($connect, $query2);
    $result3 = mysqli_query($connect, $query3);
    $result4 = mysqli_query($connect, $query4);

    return mysqli_affected_rows($connect);
}

if ( isset($_POST["submit"]) ){
    

    if ( dataHarga($_POST) > 0 ){
        echo "
            <script>
                Swal.fire('Pendaftaran Berhasil','Data Harga Berhasil Ditambahkan','success').then(function(){
                    window.location = 'index.php';
                });
            </script>
        ";
    }else {
        echo "
            <script>
                alert('Data Gagal Ditambahkan !');
            </script>
        ";
        echo mysqli_error($connect);
    }
}

if(isset($_POST["setupKiloan"])) {
    if(dataHarga($_POST) > 0) {
        echo "<script>Swal.fire('Berhasil','Data harga kiloan berhasil disimpan','success')
            .then(() => window.location='index.php');</script>";
    }
}

if(isset($_POST["setupSatuan"])) {
    $nama_items = $_POST["nama_item"];
    $harga_satuans = $_POST["harga_satuan"];
    
    $success = true;
    foreach($nama_items as $i => $nama) {
        $harga = $harga_satuans[$i];
        $result = mysqli_query($connect, "INSERT INTO harga_satuan (id_agen, nama_item, harga) 
                                        VALUES ('$idAgen', '$nama', '$harga')");
        if(!$result) {
            $success = false;
            break;
        }
    }
    
    if($success) {
        echo "<script>Swal.fire('Berhasil','Data harga satuan berhasil disimpan','success')
            .then(() => window.location='index.php');</script>";
    }
}
?>