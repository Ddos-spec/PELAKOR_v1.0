<?php
require __DIR__ . '/vendor/autoload.php';
include 'connect-db.php';

use Spipu\Html2Pdf\Html2Pdf;

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
    <tr>
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

<?php
$content = ob_get_clean();

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($content);
$html2pdf->output('invoice.pdf');