<?php
session_start();
include 'connect-db.php';
include 'functions/functions.php';

// cek apakah admin sudah login
cekAdmin();

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ambil data transaksi
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$query = "SELECT * FROM transaksi";
if ($start_date && $end_date) {
    $query .= " WHERE tgl_mulai BETWEEN '$start_date' AND '$end_date'";
}
$result = mysqli_query($connect, $query);

// cek format
$format = isset($_GET['format']) ? $_GET['format'] : '';

// fungsi untuk mendapatkan data transaksi dalam bentuk HTML tabel
function getTransaksiTable($result) {
    $table = '<table border="1" cellpadding="10" class="responsive-table centered">
                <tr>
                    <td style="font-weight:bold;">Kode Transaksi</td>
                    <td style="font-weight:bold;">Agen</td>
                    <td style="font-weight:bold;">Pelanggan</td>
                    <td style="font-weight:bold;">Total Item</td>
                    <td style="font-weight:bold;">Berat</td>
                    <td style="font-weight:bold;">Jenis</td>
                    <td style="font-weight:bold;">Total Bayar</td>
                    <td style="font-weight:bold;">Tanggal Pesan</td>
                    <td style="font-weight:bold;">Tanggal Selesai</td>
                </tr>';

    while ($transaksi = mysqli_fetch_assoc($result)) {
        $table .= '<tr>
                    <td>' . $transaksi["kode_transaksi"] . '</td>
                    <td>' . getAgenName($transaksi["id_agen"]) . '</td>
                    <td>' . getPelangganName($transaksi["id_pelanggan"]) . '</td>
                    <td>' . getCucianTotalItem($transaksi["id_cucian"]) . '</td>
                    <td>' . getCucianBerat($transaksi["id_cucian"]) . '</td>
                    <td>' . getCucianJenis($transaksi["id_cucian"]) . '</td>
                    <td>' . $transaksi["total_bayar"] . '</td>
                    <td>' . $transaksi["tgl_mulai"] . '</td>
                    <td>' . $transaksi["tgl_selesai"] . '</td>
                </tr>';
    }

    $table .= '</table>';
    return $table;
}

// fungsi untuk mendapatkan nama agen
function getAgenName($id_agen) {
    global $connect;
    $agen = mysqli_query($connect, "SELECT * FROM agen WHERE id_agen = '$id_agen'");
    $agen = mysqli_fetch_assoc($agen);
    return $agen["nama_laundry"];
}

// fungsi untuk mendapatkan nama pelanggan
function getPelangganName($id_pelanggan) {
    global $connect;
    $pelanggan = mysqli_query($connect, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
    $pelanggan = mysqli_fetch_assoc($pelanggan);
    return $pelanggan["nama"];
}

// fungsi untuk mendapatkan total item cucian
function getCucianTotalItem($id_cucian) {
    global $connect;
    $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$id_cucian'");
    $cucian = mysqli_fetch_assoc($cucian);
    return $cucian["total_item"];
}

// fungsi untuk mendapatkan berat cucian
function getCucianBerat($id_cucian) {
    global $connect;
    $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$id_cucian'");
    $cucian = mysqli_fetch_assoc($cucian);
    return $cucian["berat"];
}

// fungsi untuk mendapatkan jenis cucian
function getCucianJenis($id_cucian) {
    global $connect;
    $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = '$id_cucian'");
    $cucian = mysqli_fetch_assoc($cucian);
    return $cucian["jenis"];
}

if ($format == 'pdf') {
    // buat PDF
    $dompdf = new Dompdf();
    $html = '
    <h3>Laporan Keuangan</h3>
    <p>Periode: ' . $start_date . ' s/d ' . $end_date . '</p>
    ' . getTransaksiTable($result);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("laporan_keuangan.pdf", array("Attachment" => 1));
    exit();

} elseif ($format == 'excel') {
    // buat Excel
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
    while ($transaksi = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $row, $transaksi["kode_transaksi"]);
        $sheet->setCellValue('B' . $row, getAgenName($transaksi["id_agen"]));
        $sheet->setCellValue('C' . $row, getPelangganName($transaksi["id_pelanggan"]));
        $sheet->setCellValue('D' . $row, getCucianTotalItem($transaksi["id_cucian"]));
        $sheet->setCellValue('E' . $row, getCucianBerat($transaksi["id_cucian"]));
        $sheet->setCellValue('F' . $row, getCucianJenis($transaksi["id_cucian"]));
        $sheet->setCellValue('G' . $row, $transaksi["total_bayar"]);
        $sheet->setCellValue('H' . $row, $transaksi["tgl_mulai"]);
        $sheet->setCellValue('I' . $row, $transaksi["tgl_selesai"]);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_keuangan.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}