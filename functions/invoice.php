<?php
ob_start();
require('./Fpdf/fpdf.php');

function generate_invoice($transaction_details) {
    // Pastikan tidak ada output sebelumnya
    if (ob_get_contents()) ob_end_clean(); 

    // Validasi data
    if (!isset(
        $transaction_details['Transaction ID'], 
        $transaction_details['Agent Name'], 
        $transaction_details['Customer Name'], 
        $transaction_details['Total Amount'], 
        $transaction_details['Date']
    )) {
        die("Data transaksi tidak lengkap!");
    }

    // Generate PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Header
    $pdf->Image('img/logo.png', 10, 10, 30); // Sesuaikan path logo
    $pdf->Cell(0, 10, 'INVOICE LAUNDRYKU', 0, 1, 'C');
    $pdf->Ln(10);

    // Detail Transaksi
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'ID Transaksi:', 0);
    $pdf->Cell(0, 10, $transaction_details['Transaction ID'], 0, 1);
    $pdf->Cell(50, 10, 'Agen:', 0);
    $pdf->Cell(0, 10, $transaction_details['Agent Name'], 0, 1);
    $pdf->Cell(50, 10, 'Pelanggan:', 0);
    $pdf->Cell(0, 10, $transaction_details['Customer Name'], 0, 1);
    $pdf->Cell(50, 10, 'Total Bayar:', 0);
    $pdf->Cell(0, 10, 'Rp ' . number_format($transaction_details['Total Amount'], 0, ',', '.'), 0, 1);
    $pdf->Cell(50, 10, 'Tanggal:', 0);
    $pdf->Cell(0, 10, $transaction_details['Date'], 0, 1);

    // Output PDF (Force Download)
    $pdf->Output('D', 'invoice.pdf'); // 'D' = Force download
    exit;
}

// Example usage
// $transaction_details = [
//     'Transaction ID' => '12345',
//     'Agent Name' => 'John Doe',
//     'Amount' => '$100',
//     'Date' => date('Y-m-d H:i:s')
// ];
// generate_invoice($transaction_details);
?>
