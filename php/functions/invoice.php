<?php
require('Fpdf/fpdf.php');

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add banner image
$pdf->Image('../img/banner.png', 10, 10, 190);

// Set title
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
$pdf->Ln(10);

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// Connect to the database
include '../connect-db.php';

// Fetch transaction details
if ($order_id) {
    $stmt = $connect->prepare("SELECT * FROM transaksi WHERE kode_transaksi = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaksi = $result->fetch_assoc();
    
    // Fetch customer name
    if ($transaksi) {
        $customer_id = $transaksi['id_pelanggan'];
        $customer_query = $connect->prepare("SELECT nama FROM pelanggan WHERE id_pelanggan = ?");
        $customer_query->bind_param("s", $customer_id);
        $customer_query->execute();
        $customer_result = $customer_query->get_result();
        $customer = $customer_result->fetch_assoc();
        $customer_name = $customer ? $customer['nama'] : 'Unknown';

        // Display transaction details
        $pdf->Cell(0, 10, 'Transaction Code: ' . $transaksi['kode_transaksi'], 0, 1);
        $pdf->Cell(0, 10, 'Customer Name: ' . $customer_name, 0, 1);
        $pdf->Cell(0, 10, 'Item Total: ' . $transaksi['total_item'], 0, 1);
        $pdf->Cell(0, 10, 'Weight: ' . $transaksi['berat'], 0, 1);
        $pdf->Cell(0, 10, 'Service Type: ' . $transaksi['jenis'], 0, 1);
        $pdf->Cell(0, 10, 'Total Paid: ' . $transaksi['total_bayar'], 0, 1);
        $pdf->Cell(0, 10, 'Order Date: ' . $transaksi['tgl_mulai'], 0, 1);
        $pdf->Cell(0, 10, 'Complete Date: ' . $transaksi['tgl_selesai'], 0, 1);
    } else {
        $pdf->Cell(0, 10, 'No transaction found.', 0, 1);
    }
} else {
    $pdf->Cell(0, 10, 'No order ID provided.', 0, 1);
}

// Output the PDF
$pdf->Output('I', 'Invoice.pdf');
?>
