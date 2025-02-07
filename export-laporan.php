<?php
// Pastikan tidak ada output sebelum tag pembuka PHP
error_reporting(0);
ini_set('display_errors', 0);

session_start();
include 'connect-db.php';
include 'functions/functions.php';

// Cek apakah admin sudah login
cekAdmin();

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Mulai output buffering
ob_start();

// Ambil parameter filter tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date   = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Siapkan query untuk mengambil data transaksi
$query = "SELECT * FROM transaksi";
if ($start_date && $end_date) {
    $query .= " WHERE tgl_mulai BETWEEN '$start_date' AND '$end_date'";
}

$result = mysqli_query($connect, $query);

if ($result) {
    // Debugging: Log the output of the query to a temporary file
    $logFile = 'query_output.log';
    file_put_contents($logFile, "Query: $query\n", FILE_APPEND);
    while ($row = mysqli_fetch_assoc($result)) {
        file_put_contents($logFile, json_encode($row) . "\n", FILE_APPEND);
    }
} else {
    file_put_contents('query_output.log', "Query Error: " . mysqli_error($connect) . "\n", FILE_APPEND);
}

// Cek format output yang diinginkan (pdf atau excel)
$format = isset($_GET['format']) ? $_GET['format'] : '';

// --- Fungsi-Fungsi Pembantu ---

// Fungsi untuk mendapatkan data transaksi dalam bentuk HTML tabel (digunakan untuk PDF)
function getTransaksiTable($result) {
    $table = '<table border="1" cellpadding="10" class="responsive-table centered">
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Agen</th>
                    <th>Pelanggan</th>
                    <th>Total Item</th>
                    <th>Berat</th>
                    <th>Jenis</th>
                    <th>Total Bayar</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Selesai</th>
                </tr>';
    while ($transaksi = mysqli_fetch_assoc($result)) {
        $table .= '<tr>
                    <td>' . htmlspecialchars($transaksi["kode_transaksi"]) . '</td>
                    <td>' . htmlspecialchars(getAgenName($transaksi["id_agen"])) . '</td>
                    <td>' . htmlspecialchars(getPelangganName($transaksi["id_pelanggan"])) . '</td>
                    <td>' . htmlspecialchars(getCucianTotalItem($transaksi["id_cucian"])) . '</td>
                    <td>' . htmlspecialchars(getCucianBerat($transaksi["id_cucian"])) . '</td>
                    <td>' . htmlspecialchars(getCucianJenis($transaksi["id_cucian"])) . '</td>
                    <td>' . htmlspecialchars($transaksi["total_bayar"]) . '</td>
                    <td>' . htmlspecialchars($transaksi["tgl_mulai"]) . '</td>
                    <td>' . htmlspecialchars($transaksi["tgl_selesai"]) . '</td>
                </tr>';
    }
    $table .= '</table>';
    return $table;
}

// Fungsi untuk mendapatkan nama agen
function getAgenName($id_agen) {
    global $connect;
    $agenQuery = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$id_agen'");
    $agen = mysqli_fetch_assoc($agenQuery);
    return isset($agen["nama_laundry"]) ? $agen["nama_laundry"] : '';
}

// Fungsi untuk mendapatkan nama pelanggan
function getPelangganName($id_pelanggan) {
    global $connect;
    $pelangganQuery = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
    $pelanggan = mysqli_fetch_assoc($pelangganQuery);
    return isset($pelanggan["nama"]) ? $pelanggan["nama"] : '';
}

// Fungsi untuk mendapatkan total item cucian
function getCucianTotalItem($id_cucian) {
    global $connect;
    $cucianQuery = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$id_cucian'");
    $cucian = mysqli_fetch_assoc($cucianQuery);
    return isset($cucian["total_item"]) ? $cucian["total_item"] : '';
}

// Fungsi untuk mendapatkan berat cucian
function getCucianBerat($id_cucian) {
    global $connect;
    $cucianQuery = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$id_cucian'");
    $cucian = mysqli_fetch_assoc($cucianQuery);
    return isset($cucian["berat"]) ? $cucian["berat"] : '';
}

// Fungsi untuk mendapatkan jenis cucian
function getCucianJenis($id_cucian) {
    global $connect;
    $cucianQuery = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$id_cucian'");
    $cucian = mysqli_fetch_assoc($cucianQuery);
    return isset($cucian["jenis"]) ? $cucian["jenis"] : '';
}

// --- Proses Export Berdasarkan Format ---
if ($format == 'pdf') {
    // Karena pointer result bisa bergeser, jalankan ulang query untuk PDF
    $resultPdf = mysqli_query($connect, $query);
    $html = '<h3>Laporan Keuangan</h3>
             <p>Periode: ' . htmlspecialchars($start_date) . ' s/d ' . htmlspecialchars($end_date) . '</p>
             ' . getTransaksiTable($resultPdf);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    if (ob_get_length()) {
        ob_end_clean();
    }
    $dompdf->render();
    $dompdf->stream("laporan_keuangan.pdf", array("Attachment" => 1));
    exit();

} elseif ($format == 'excel') {
    // Jalankan ulang query untuk Excel agar data lengkap
    $resultExcel = mysqli_query($connect, $query);
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Laporan Keuangan');
    $sheet->setCellValue('A1', 'Kode Transaksi');
    $sheet->setCellValue('B1', 'Agen');
    $sheet->setCellValue('C1', 'Pelanggan');
    $sheet->setCellValue('D1', 'Total Item');
    $sheet->setCellValue('E1', 'Berat');
    $sheet->setCellValue('F1', 'Jenis');
    $sheet->setCellValue('G1', 'Total Bayar');
    $sheet->setCellValue('H1', 'Tanggal Pesan');
    $sheet->setCellValue('I1', 'Tanggal Selesai');

    $row = 2;
    while ($transaksi = mysqli_fetch_assoc($resultExcel)) {
        $sheet->setCellValue('A' . $row, $transaksi["kode_transaksi"]);
        $sheet->setCellValue('B' . $row, getAgenName($transaksi["id_agen"]));
        $sheet->setCellValue('C' . $row, getPelangganName($transaksi["id_pelanggan"]));
        $sheet->setCellValue('D' . $row, getCucianTotalItem($transaksi["id_cucian"]));
        $sheet->setCellValue('E' . $row, getCucianBerat($transaksi["id_cucian"]));
        $sheet->setCellValue('F' . $row, getCucianJenis($transaksi["id_cucian"]));
        $sheet->setCellValue('G' . $row, (float)$transaksi["total_bayar"] ?: 0); // Default to 0 if empty
        $sheet->setCellValue('H' . $row, $transaksi["tgl_mulai"] ?: ''); // Default to empty string if null
        $sheet->setCellValue('I' . $row, $transaksi["tgl_selesai"] ?: ''); // Default to empty string if null
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    if (ob_get_length()) {
        ob_clean();
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_keuangan.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}
