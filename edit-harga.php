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
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s6"><a href="#harga-kiloan" class="active">Harga Kiloan</a></li>
                    <li class="tab col s6"><a href="#harga-satuan">Harga Satuan</a></li>
                </ul>
            </div>

            <div id="harga-kiloan" class="col s12">
                <form action="" method="post" onsubmit="return validateHargaForm()">
                    <div class="input field">
                        <label for="cuci">Cuci</label>
                        <input type="text" id="cuci" name="cuci" value="<?= $cuci['harga'] ?>">
                    </div>
                    <div class="input field">
                        <label for="setrika">Setrika</label>
                        <input type="text" id="setrika" name="setrika" value="<?= $setrika['harga'] ?>">
                    </div>
                    <div class="input field">
                        <label for="komplit">Cuci + Setrika</label><input type="text" id="komplit" name="komplit" value="<?= $komplit['harga'] ?>">
                    </div>
                    <div class="input field center">
                        <button class="btn-large blue darken-2" type="submit" name="simpan">Simpan Data</button>
                    </div>
                    <div class="preview-section">
                        <?php include 'components/harga-preview.php'; ?>
                    </div>
                </form>
            </div>

            <div id="harga-satuan" class="col s12">
                <form action="" method="post" id="form-satuan">
                    <div class="input-field">
                        <input type="text" name="nama_item" placeholder="Nama Item">
                        <input type="number" name="harga_satuan" placeholder="Harga">
                        <button class="btn blue darken-2" type="submit" name="tambah_harga_satuan">Tambah</button>
                    </div>
                </form>
                <table class="responsive-table centered">
                    <tr>
                        <th>Nama Item</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                    <!-- PHP untuk menampilkan daftar harga satuan -->
                    <?php
                    $harga_satuan = mysqli_query($connect, "SELECT * FROM harga_satuan WHERE id_agen = $idAgen");
                    while($item = mysqli_fetch_assoc($harga_satuan)):
                    ?>
                    <tr>
                        <td><?= $item["nama_item"] ?></td>
                        <td>Rp <?= number_format($item["harga"]) ?></td>
                        <td>
                            <button class="btn red" onclick="hapusHargaSatuan(<?= $item['id_harga_satuan'] ?>)">
                                <i class="material-icons">delete</i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
    <!-- end body -->

    <!-- footer -->
    <?php include "footer.php" ?>
    <!-- end footer -->

    <script>
    function hapusHargaSatuan(id) {
        Swal.fire({
            title: 'Hapus harga satuan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = `hapus-harga-satuan.php?id=${id}`;
            }
        });
    }

    function validateHargaForm() {
        const hargaCuci = parseInt($('#cuci').val());
        const hargaSetrika = parseInt($('#setrika').val());
        const hargaKomplit = parseInt($('#komplit').val());
        
        if(isNaN(hargaCuci) || isNaN(hargaSetrika) || isNaN(hargaKomplit)) {
            Swal.fire('Error', 'Harga harus berupa angka', 'error');
            return false;
        }
        return true;
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
if (isset($_POST["simpan"])) {

    if ( ubahHarga($_POST) > 0)   {
        echo "
            <script>
                Swal.fire('Data Berhasil Di Update','','success').then(function() {
                    window.location = 'edit-harga.php';
                });
            </script>
        ";
    }else {
        echo "
            <script>
                Swal.fire('Data Gagal Di Update','','error');
            </script>
        ";
        mysqli_error($connect);
    }

}

// jika user menekan tombol tambah harga satuan
if(isset($_POST["tambah_harga_satuan"])) { 
    $nama_item = htmlspecialchars($_POST["nama_item"]);
    $harga = htmlspecialchars($_POST["harga_satuan"]);
    
    validasiHargaSatuan($harga);
    
    mysqli_query($connect, "INSERT INTO harga_satuan (id_agen, nama_item, harga) 
        VALUES ($idAgen, '$nama_item', $harga)");
        
    if(mysqli_affected_rows($connect) > 0) {
        echo "<script>
            Swal.fire('Berhasil','Harga satuan berhasil ditambahkan','success')
            .then(() => location.reload());
        </script>";
    }
}

?>