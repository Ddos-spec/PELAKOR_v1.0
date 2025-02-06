<?php
require __DIR__ . '/vendor/autoload.php';
include 'connect-db.php';

use Dompdf\Dompdf;

$kodeTransaksi = $_GET['kode_transaksi'];
$query = mysqli_query($connect, "SELECT * FROM transaksi WHERE kode_transaksi = '$kodeTransaksi'");
$transaksi = mysqli_fetch_assoc($query);

$idPelanggan = $transaksi['id_pelanggan'];
$pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$idPelanggan'");
$pelanggan = mysqli_fetch_assoc($pelanggan);

$idCucian = $transaksi['id_cucian'];
$cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$idCucian'");
$cucian = mysqli_fetch_assoc($cucian);

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            font-size: 28px;
            color: #444;
            margin: 0;
        }
        .invoice-header hr {
            border: 0;
            border-top: 2px solid #ddd;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #555;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .highlight {
            background-color: #eafaf1;
            font-weight: bold;
            color: #228B22;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <hr>
    </div>
    <table>
        <tr>
            <th>Kode Transaksi</th>
            <td><?= $transaksi['kode_transaksi'] ?></td>
        </tr>
        <tr>
            <th>Nama Pelanggan</th>
            <td><?= $pelanggan['nama'] ?></td>
        </tr>
        <tr>
            <th>Total Item</th>
            <td><?= $cucian['total_item'] ?></td>
        </tr>
        <tr>
            <th>Berat</th>
            <td><?= $cucian['berat'] ?> Kg</td>
        </tr>
        <tr>
            <th>Jenis</th>
            <td><?= $cucian['jenis'] ?></td>
        </tr>
        <tr class="highlight">
            <th>Total Bayar</th>
            <td>Rp. <?= $transaksi['total_bayar'] ?></td>
        </tr>
        <tr>
            <th>Tanggal Pesan</th>
            <td><?= $transaksi['tgl_mulai'] ?></td>
        </tr>
        <tr>
            <th>Tanggal Selesai</th>
            <td><?= $transaksi['tgl_selesai'] ?></td>
        </tr>
    </table>
    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami.</p>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($content);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('invoice.pdf');