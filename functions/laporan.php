<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

function generateLaporan($idAgen = null) {
    global $connect;
    
    $query = "SELECT t.*, c.tipe_layanan, c.berat, c.jenis,
              p.nama as nama_pelanggan, a.nama_laundry
              FROM transaksi t
              JOIN cucian c ON t.id_cucian = c.id_cucian 
              JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
              JOIN agen a ON t.id_agen = a.id_agen";
              
    if($idAgen) {
        $query .= " WHERE t.id_agen = $idAgen";
    }
    
    return mysqli_query($connect, $query);
}

function exportPDF($data) {
    $pdf = new TCPDF();
    $pdf->SetTitle('Laporan Transaksi');
    $pdf->AddPage();
    
    // Header
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'LAPORAN TRANSAKSI LAUNDRY', 0, 1, 'C');
    
    // Table Header
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(30, 7, 'Tanggal', 1);
    $pdf->Cell(40, 7, 'Pelanggan', 1);
    $pdf->Cell(30, 7, 'Layanan', 1);
    $pdf->Cell(30, 7, 'Total', 1);
    $pdf->Ln();
    
    // Table Content
    $pdf->SetFont('helvetica', '', 10);
    while($row = mysqli_fetch_assoc($data)) {
        $pdf->Cell(30, 6, date('d/m/Y', strtotime($row['tgl_mulai'])), 1);
        $pdf->Cell(40, 6, $row['nama_pelanggan'], 1);
        $pdf->Cell(30, 6, $row['tipe_layanan'], 1);
        $pdf->Cell(30, 6, 'Rp '.number_format($row['total_bayar']), 1);
        $pdf->Ln();
    }
    
    $pdf->Output('Laporan_Transaksi.pdf', 'D');
}

function exportExcel($data) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Header
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Pelanggan');
    $sheet->setCellValue('C1', 'Layanan');
    $sheet->setCellValue('D1', 'Total');
    
    $row = 2;
    while($item = mysqli_fetch_assoc($data)) {
        $sheet->setCellValue('A'.$row, date('d/m/Y', strtotime($item['tgl_mulai'])));
        $sheet->setCellValue('B'.$row, $item['nama_pelanggan']);
        $sheet->setCellValue('C'.$row, $item['tipe_layanan']);
        $sheet->setCellValue('D'.$row, $item['total_bayar']);
        $row++;
    }
    
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan_Transaksi.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
}
