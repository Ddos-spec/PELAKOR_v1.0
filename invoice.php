<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

// Ensure only agents can access
if (!isset($_SESSION["login-agen"])) {
    header("Location: login.php");
    exit();
}

// Get transaction ID from URL
if (!isset($_GET['id'])) {
    header("Location: transaksi.php");
    exit();
}

$transactionId = intval($_GET['id']);
$agentId = intval($_SESSION["agen"]);

// Get transaction details
$query = mysqli_query($connect, 
    "SELECT t.*, c.total_item, c.berat, c.jenis, c.item_type, c.tgl_mulai, c.tgl_selesai,
            a.nama_laundry AS nama_agen, p.nama AS nama_pelanggan
     FROM transaksi t
     JOIN cucian c ON t.id_cucian = c.id_cucian
     JOIN agen a ON t.id_agen = a.id_agen
     JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
     WHERE t.kode_transaksi = $transactionId AND t.id_agen = $agentId");

if (mysqli_num_rows($query) === 0) {
    header("Location: transaksi.php");
    exit();
}

$transaction = mysqli_fetch_assoc($query);

ob_start();
?>
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .invoice { width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; }
    .header { text-align: center; margin-bottom: 30px; }
    .details { margin-bottom: 20px; }
    .details table { width: 100%; border-collapse: collapse; }
    .details td, .details th { padding: 8px; border: 1px solid #ddd; }
    .total { text-align: right; margin-top: 20px; }
</style>
<page>
    <div class="invoice">
        <div class="header">
            <h2>Invoice Laundry</h2>
            <p>No. Invoice: <?= htmlspecialchars($transaction["kode_transaksi"]) ?></p>
        </div>
        
        <div class="details">
            <table>
                <tr>
                    <th>Nama Agen</th>
                    <td><?= htmlspecialchars($transaction["nama_agen"]) ?></td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td><?= htmlspecialchars($transaction["nama_pelanggan"]) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Pesan</th>
                    <td><?= htmlspecialchars($transaction["tgl_mulai"]) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Selesai</th>
                    <td><?= htmlspecialchars($transaction["tgl_selesai"]) ?></td>
                </tr>
                <tr>
                    <th>Jenis Layanan</th>
                    <td><?= htmlspecialchars($transaction["jenis"]) ?></td>
                </tr>
                <tr>
                    <th>Berat</th>
                    <td><?= htmlspecialchars($transaction["berat"]) ?> kg</td>
                </tr>
                <tr>
                    <th>Total Item</th>
                    <td><?= htmlspecialchars($transaction["total_item"]) ?></td>
                </tr>
            </table>
        </div>

        <div class="total">
            <h3>Total Bayar: Rp <?= number_format(calculateTotalHarga($transaction), 0, ',', '.') ?></h3>
        </div>
    </div>
</page>
<?php
$content = ob_get_clean();

require_once 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

try {
    $html2pdf = new Html2Pdf('P', 'A4', 'en');
    $html2pdf->writeHTML($content);
    $html2pdf->output('invoice.pdf');
} catch (Html2PdfException $e) {
    echo $e;
    exit;
}
?>