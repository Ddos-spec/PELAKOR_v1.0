<?php
ob_start();
require('./Fpdf/fpdf.php');

function generate_invoice($transaction_details) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    
    // Invoice Title
    $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
    
    // Transaction Details
    $pdf->SetFont('Arial', '', 12);
    foreach ($transaction_details as $key => $value) {
        $pdf->Cell(0, 10, "$key: $value", 0, 1);
    }
    
    // Output the PDF
    ob_end_clean();
    ob_end_clean();
    $pdf->Output('I', 'invoice.pdf');
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
