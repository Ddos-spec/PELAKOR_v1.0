<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

function exportPDF($data, $userType) {
    // Validasi akses
    if($userType != 'admin' && $userType != 'agen') {
        return false;
    }

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetTitle('Laporan Transaksi Laundry');
    
    // Header tabel
    $header = array('No', 'Tanggal', 'Pelanggan', 'Tipe', 'Items', 'Total');
    
    // Generate content
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 8);
    
    // Generate tabel
    foreach($data as $row) {
        $pdf->Cell(30, 10, $row['tgl_mulai'], 1);
        $pdf->Cell(40, 10, $row['nama_pelanggan'], 1);
        // ...content generation
    }
    
    return $pdf->Output('Laporan_Transaksi.pdf', 'D');
}

function exportExcel($data, $userType) {
    // Validasi akses
    if($userType != 'admin' && $userType != 'agen') {
        return false;
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Header styling
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    
    // Set header
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Pelanggan');
    $sheet->setCellValue('C1', 'Tipe');
    $sheet->setCellValue('D1', 'Items');
    $sheet->setCellValue('E1', 'Total');
    
    // Isi data
    $row = 2;
    foreach($data as $item) {
        $sheet->setCellValue('A'.$row, $item['tgl_mulai']);
        $sheet->setCellValue('B'.$row, $item['nama_pelanggan']);
        // ...data filling
        $row++;
    }
    
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan_Transaksi.xlsx"');
    header('Cache-Control: max-age=0');
    
    return $writer->save('php://output');
}
