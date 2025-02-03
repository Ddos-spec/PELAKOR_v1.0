<?php
require('Fpdf/fpdf.php');

$order_id = $_POST['order_id']; // Get order ID from POST data

// Connect to the database
include '../connect-db.php';

// Fetch transaction details
$query = mysqli_query($connect, "SELECT * FROM transaksi WHERE kode_transaksi = '$order_id'");
$transaksi = mysqli_fetch_assoc($query);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');

// Order details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Customer Name: ' . $transaksi['id_pelanggan'], 0, 1);
$pdf->Cell(0, 10, 'Laundry Type: ' . $transaksi['jenis'], 0, 1);
$pdf->Cell(0, 10, 'Weight: ' . $transaksi['berat'] . ' kg', 0, 1);
$pdf->Cell(0, 10, 'Total Price: ' . $transaksi['total_bayar'], 0, 1);
$pdf->Cell(0, 10, 'Payment Status: ' . ($transaksi['status_bayar'] ? 'Paid' : 'Unpaid'), 0, 1);

// Print icon
$pdf->Image('path/to/print-icon.png', 10, 10, 30); // Adjust path and size as needed

// Output the PDF
$pdf->Output('I', 'Invoice_' . $order_id . '.pdf');

// Return response for AJAX call
$response = ['invoiceUrl' => 'Invoice_' . $order_id . '.pdf'];
echo json_encode($response);
?>
