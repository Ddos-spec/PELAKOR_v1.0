<?php
session_start();
require_once 'connect-db.php';
require_once 'functions/functions.php';

// Pastikan hanya Admin atau Agen yang dapat mengakses
if (!isset($_SESSION["login-admin"]) && !isset($_SESSION["login-agen"])) {
    header("Location: login.php");
    exit();
}

$login = isset($_SESSION["login-admin"]) ? "Admin" : "Agen";

// Membangun kondisi query berdasarkan filter
$conditions = "t.payment_status = 'Paid'";
if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
    $conditions .= " AND c.tgl_mulai >= '$start_date'";
}
if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);
    $conditions .= " AND c.tgl_mulai <= '$end_date'";
}
if ($login == "Admin" && isset($_GET['agent_id']) && !empty($_GET['agent_id'])) {
    $agent_id = intval($_GET['agent_id']);
    $conditions .= " AND t.id_agen = $agent_id";
}
if ($login == "Agen") {
    $idAgen = intval($_SESSION["agen"]);
    $conditions .= " AND t.id_agen = $idAgen";
}

$queryStr = "SELECT t.*, c.total_item, c.berat, c.jenis, c.item_type, c.tgl_mulai, c.tgl_selesai 
    FROM transaksi t 
    JOIN cucian c ON t.id_cucian = c.id_cucian 
    WHERE $conditions
    ORDER BY c.tgl_mulai DESC";
$query = mysqli_query($connect, $queryStr);

ob_start();
?>
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .card { border: 1px solid #000; margin-bottom: 10px; padding: 10px; }
    .card h5 { margin: 0 0 5px 0; }
    .card p { margin: 2px 0; }
    .header { text-align: center; margin-bottom: 20px; }
</style>
<page>
    <div class="header">
        <h2>Laporan Transaksi</h2>
        <?php if (isset($start_date) && isset($end_date)) {
            echo "<p>Periode: " . htmlspecialchars($start_date) . " s/d " . htmlspecialchars($end_date) . "</p>";
        } ?>
    </div>
    <?php while ($transaksi = mysqli_fetch_assoc($query)): 
        $agentName = '';
        if ($login != "Pelanggan") {
            $agenQuery = mysqli_query($connect, "SELECT nama_laundry FROM agen WHERE id_agen = " . intval($transaksi["id_agen"]));
            $agenRow = mysqli_fetch_assoc($agenQuery);
            $agentName = $agenRow["nama_laundry"] ?? '';
        }
        $pelName = '';
        if ($login != "Agen") {
            $pelQuery = mysqli_query($connect, "SELECT nama FROM pelanggan WHERE id_pelanggan = " . intval($transaksi["id_pelanggan"]));
            $pelRow = mysqli_fetch_assoc($pelQuery);
            $pelName = $pelRow["nama"] ?? '';
        }
    ?>
    <div class="card">
        <h5>Kode Transaksi: <?= htmlspecialchars($transaksi["kode_transaksi"]) ?></h5>
        <?php if ($login != "Pelanggan"): ?>
            <p><strong>Agen:</strong> <?= htmlspecialchars($agentName) ?></p>
        <?php endif; ?>
        <?php if ($login != "Agen"): ?>
            <p><strong>Pelanggan:</strong> <?= htmlspecialchars($pelName) ?></p>
        <?php endif; ?>
        <p><strong>Total Item:</strong> <?= htmlspecialchars($transaksi["total_item"]) ?></p>
        <p><strong>Berat:</strong> <?= htmlspecialchars($transaksi["berat"]) ?></p>
        <p><strong>Jenis:</strong> <?= htmlspecialchars($transaksi["jenis"]) ?></p>
        <p><strong>Harga Paket:</strong> Rp <?= number_format(getHargaPaket($transaksi["jenis"], $transaksi["id_agen"]), 0, ',', '.') ?></p>
        <p><strong>Total Per Item:</strong> Rp <?= number_format(getTotalPerItem($transaksi["item_type"] ?? '', $transaksi["id_agen"]), 0, ',', '.') ?></p>
        <p><strong>Total Bayar:</strong> Rp <?= number_format(calculateTotalHarga($transaksi), 0, ',', '.') ?></p>
        <p><strong>Tanggal Pesan:</strong> <?= htmlspecialchars($transaksi["tgl_mulai"]) ?></p>
        <p><strong>Tanggal Selesai:</strong> <?= htmlspecialchars($transaksi["tgl_selesai"]) ?></p>
    </div>
    <?php endwhile; ?>
</page>
<?php
$content = ob_get_clean();

require_once 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

try {
    $html2pdf = new Html2Pdf('P', 'A4', 'en');
    $html2pdf->writeHTML($content);
    $html2pdf->output('laporan.pdf');
} catch (Html2PdfException $e) {
    echo $e;
    exit;
}
?>
