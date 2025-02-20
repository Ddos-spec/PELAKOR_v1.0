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

// Build filter conditions
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
    body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; }
    .header { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    table, th, td { border: 1px solid #000; }
    th, td { padding: 5px; text-align: left; }
    th { background-color: #eee; }
    /* Modal styling untuk tampilan web */
    #detailModal {
        position: fixed;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -30%);
        border: 1px solid #000;
        background: #fff;
        padding: 15px;
        z-index: 9999;
        display: none;
        width: 300px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.5);
    }
    #detailModal h4 { margin-top: 0; }
    #modalClose {
        float: right;
        cursor: pointer;
        font-weight: bold;
    }
</style>
<page>
    <div class="header">
        <h2>Laporan Transaksi</h2>
        <?php 
        if (isset($start_date) && isset($end_date)) {
            echo "<p>Periode: " . htmlspecialchars($start_date) . " s/d " . htmlspecialchars($end_date) . "</p>";
        }
        ?>
    </div>
    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <?php if ($login != "Pelanggan"): ?>
                    <th>Agen</th>
                <?php endif; ?>
                <?php if ($login != "Agen"): ?>
                    <th>Pelanggan</th>
                <?php endif; ?>
                <th>Total Item</th>
                <th>Berat</th>
                <th>Jenis</th>
                <th>Harga Paket</th>
                <th>Total Per Item</th>
                <th>Total Bayar</th>
                <th>Tanggal Pesan</th>
                <th>Tanggal Selesai</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
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
            <tr>
                <td><?= htmlspecialchars($transaksi["kode_transaksi"]) ?></td>
                <?php if ($login != "Pelanggan"): ?>
                    <td><?= htmlspecialchars($agentName) ?></td>
                <?php endif; ?>
                <?php if ($login != "Agen"): ?>
                    <td><?= htmlspecialchars($pelName) ?></td>
                <?php endif; ?>
                <td><?= htmlspecialchars($transaksi["total_item"]) ?></td>
                <td><?= htmlspecialchars($transaksi["berat"]) ?></td>
                <td><?= htmlspecialchars($transaksi["jenis"]) ?></td>
                <td>Rp <?= number_format(getHargaPaket($transaksi["jenis"], $transaksi["id_agen"]), 0, ',', '.') ?></td>
                <td>Rp <?= number_format(getTotalPerItem($transaksi["item_type"] ?? '', $transaksi["id_agen"]), 0, ',', '.') ?></td>
                <td>Rp <?= number_format(calculateTotalHarga($transaksi), 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($transaksi["tgl_mulai"]) ?></td>
                <td><?= htmlspecialchars($transaksi["tgl_selesai"]) ?></td>
                <td>
                    <button type="button" class="detail-btn" data-items="<?= htmlspecialchars($transaksi["item_type"]) ?>" style="padding:3px 6px; font-size:9pt;">Detail</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal Minimalis untuk Detail Item (hanya untuk tampilan web) -->
    <div id="detailModal">
        <span id="modalClose">&times;</span>
        <h4>Item Detail</h4>
        <div id="modalContent" style="margin-top:10px;"></div>
    </div>
</page>
<script>
// Skrip modal untuk tampilan web (tidak aktif di PDF)
document.addEventListener('DOMContentLoaded', function(){
    var buttons = document.getElementsByClassName('detail-btn');
    for(var i=0; i<buttons.length; i++){
         buttons[i].addEventListener('click', function(){
              var items = this.getAttribute('data-items');
              // Ubah koma menjadi baris baru
              var content = items.replace(/, /g, "<br>");
              document.getElementById('modalContent').innerHTML = content;
              document.getElementById('detailModal').style.display = 'block';
         });
    }
    document.getElementById('modalClose').addEventListener('click', function(){
         document.getElementById('detailModal').style.display = 'none';
    });
});
</script>
<?php
$content = ob_get_clean();

require_once 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

try {
    $html2pdf = new Html2Pdf('P', 'A4', 'en');
    $html2pdf->writeHTML($content);
    // Output PDF dan force download
    $html2pdf->output('laporan.pdf', 'D');
} catch (Exception $e) {
    echo $e;
    exit;
}
?>
