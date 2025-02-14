<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idAgen = $_POST['id_agen'];
    $jenisPakaian = htmlspecialchars($_POST['jenis_pakaian']);
    $paket = htmlspecialchars($_POST['paket']);
    $catatan = htmlspecialchars($_POST['catatan']);

    // Validate inputs
    if (empty($jenisPakaian) || empty($paket)) {
        echo "
            <script>
                alert('Pilih jenis pakaian dan paket.');
                window.history.back();
            </script>
        ";
        exit;
    }

    // Simpan transaksi ke database
    $query = "INSERT INTO transaksi_kiloan (id_agen, jenis_pakaian, paket, catatan) VALUES ('$idAgen', '$jenisPakaian', '$paket', '$catatan')";
    mysqli_query($connect, $query);

    echo "
        <script>
            alert('Transaksi berhasil dikirim');
            window.location.href = 'agen.php';
        </script>
    ";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Kiloan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Laundry Kiloan</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_agen" value="<?= $_GET['id'] ?>">
            <div class="mb-3">
                <label for="jenis_pakaian" class="form-label">Jenis Pakaian</label>
                <select id="jenis_pakaian" name="jenis_pakaian" class="form-select">
                    <option value="pakaian_harian">Pakaian Harian</option>
                    <option value="pakaian_berat">Pakaian Berat (Jaket, Selimut, dll.)</option>
                    <option value="campuran">Campuran</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="paket" class="form-label">Pilih Paket</label>
                <select id="paket" name="paket" class="form-select">
                    <option value="biasa">cuci</option>
                    <option value="express">gosok</option>
                    <option value="super_express">komplit</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea id="catatan" name="catatan" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
</body>
</html>