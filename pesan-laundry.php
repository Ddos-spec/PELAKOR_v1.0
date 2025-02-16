<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

cekPelanggan();

// Get agent and customer data
$idAgen = $_GET["id"];
$idPelanggan = $_SESSION["pelanggan"];

$query = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$idAgen'");
$agen = mysqli_fetch_assoc($query);

$query = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
$pelanggan = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'headtags.html' ?>
    <title>Pemesanan Laundry</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'header.php' ?>

    <div class="form-container">
        <h4>Pesan Laundry</h4>
        
        <form method="post">
            <div class="input-field">
                <p>Tipe Layanan:</p>
                <label>
                    <input name="tipe_layanan" type="radio" value="kiloan" checked />
                    <span>Kiloan</span>
                </label>
                <label>
                    <input name="tipe_layanan" type="radio" value="satuan" />
                    <span>Satuan</span>
                </label>
            </div>

            <div class="input-field">
                <p>Jenis Layanan:</p>
                <select name="jenis" class="browser-default">
                    <option value="cuci">Cuci</option>
                    <option value="setrika">Setrika</option>
                    <option value="komplit">Komplit</option>
                </select>
            </div>

            <div class="input-field">
                <textarea id="alamat" name="alamat" class="materialize-textarea" required></textarea>
                <label for="alamat">Alamat</label>
            </div>

            <div class="input-field">
                <textarea id="catatan" name="catatan" class="materialize-textarea"></textarea>
                <label for="catatan">Catatan (opsional)</label>
            </div>

            <button type="submit" class="btn waves-effect waves-light">
                Pesan Sekarang
            </button>
        </form>
    </div>

    <?php include 'footer.php' ?>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipe_layanan = $_POST['tipe_layanan'];
    $jenis = $_POST['jenis'];
    $alamat = $_POST['alamat'];
    $catatan = $_POST['catatan'];
    $tgl = date("Y-m-d H:i:s");

    $query = "INSERT INTO cucian 
              (id_agen, id_pelanggan, tgl_mulai, jenis, alamat, catatan, status_cucian, tipe_layanan) 
              VALUES (?, ?, ?, ?, ?, ?, 'Penjemputan', ?)";
    
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "iisssss", $idAgen, $idPelanggan, $tgl, $jenis, $alamat, $catatan, $tipe_layanan);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Pesanan berhasil dibuat');
            window.location.href = 'status.php';
        </script>";
    } else {
        echo "<script>alert('Gagal membuat pesanan');</script>";
    }
}
?>
