<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idAgen = $_POST['id_agen'];
    $items = $_POST['items'];
    $jumlah = $_POST['jumlah'];
    $catatan = htmlspecialchars($_POST['catatan']);
    $totalHarga = 0;

    // Validate inputs
    if (empty($items)) {
        echo "
            <script>
                alert('Pilih setidaknya satu item.');
                window.history.back();
            </script>
        ";
        exit;
    }

    // Simpan transaksi ke database
    foreach ($items as $item) {
        $jumlahItem = $jumlah[$item];
        if ($jumlahItem <= 0) {
            echo "
                <script>
                    alert('Jumlah item harus lebih dari 0.');
                    window.history.back();
                </script>
            ";
            exit;
        }
        $hargaItem = $items[$item][2];
        $totalHarga += $hargaItem * $jumlahItem;
        $query = "INSERT INTO transaksi_satuan (id_agen, item, jumlah, harga, catatan) VALUES ('$idAgen', '$item', '$jumlahItem', '$hargaItem', '$catatan')";
        mysqli_query($connect, $query);
    }

    echo "
        <script>
            alert('Transaksi berhasil dikirim\\nTotal Harga: Rp " . number_format($totalHarga) . "');
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
    <title>Laundry Satuan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .item-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .item-container img {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Pilih Item Laundry Satuan</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_agen" value="<?= $_GET['id'] ?>">
            <div class="row">
                <?php
                $items = [
                    'baju' => ['img/baju.jpg', 'Baju', 5000],
                    'celana' => ['img/celana.jpg', 'Celana', 7000],
                    'jaket' => ['img/jaket.jpg', 'Jaket', 10000],
                    'bajukhusus' => ['img/bajukhusus.jpg', 'Baju Khusus', 15000],
                    'karpet' => ['img/karpet.jpg', 'Karpet', 20000],
                    'selimut' => ['img/selimut.jpg', 'Selimut', 25000]
                ];
                foreach ($items as $key => $item) {
                    $img = $item[0];
                    $name = $item[1];
                    echo "<div class='col-md-4'>
                            <label class='form-check-label item-container'>
                                <input type='checkbox' class='form-check-input item-checkbox' name='items[]' value='$key'>
                                <img src='$img' width='50'>
                                <span>$name</span>
                                <input type='number' name='jumlah[$key]' class='form-control d-none item-quantity' min='1' placeholder='Jumlah'>
                            </label>
                          </div>";
                }
                ?>
            </div>
            <div class="mb-3 mt-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea id="catatan" name="catatan" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
    <script>
        document.querySelectorAll(".item-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                let quantityInput = this.parentElement.querySelector(".item-quantity");
                quantityInput.classList.toggle("d-none", !this.checked);
            });
        });
    </script>
</body>
</html>
