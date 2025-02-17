<?php
session_start();
include '../connect-db.php';

header('Content-Type: application/json');

$id = $_GET['id'];
$output = ['items' => '', 'total' => 0];

// Gunakan view untuk optimasi query
$query = "SELECT c.*, a.nama_laundry, p.nama as nama_pelanggan,
    CASE 
        WHEN c.tipe_layanan = 'kiloan' THEN (c.berat * h.harga)
        ELSE (SELECT SUM(dc.subtotal) FROM detail_cucian dc WHERE dc.id_cucian = c.id_cucian)
    END as total_harga
    FROM cucian c
    JOIN agen a ON c.id_agen = a.id_agen
    JOIN pelanggan p ON c.id_pelanggan = p.id_pelanggan
    LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis 
    WHERE c.id_cucian = ?";

$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if($data['tipe_layanan'] == 'kiloan') {
    $output['items'] = "<tr>
        <td>{$data['jenis']}</td>
        <td>{$data['berat']} kg</td>
        <td>Rp " . number_format($data['total_harga']/$data['berat']) . "</td>
        <td>Rp " . number_format($data['total_harga']) . "</td>
    </tr>";
} else {
    $items = mysqli_query($connect, "SELECT hs.nama_item, dc.jumlah, hs.harga, dc.subtotal 
        FROM detail_cucian dc 
        JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan 
        WHERE dc.id_cucian = $id");
    
    while($item = mysqli_fetch_assoc($items)) {
        $output['items'] .= "<tr>
            <td>{$item['nama_item']}</td>
            <td>{$item['jumlah']}</td>
            <td>Rp " . number_format($item['harga']) . "</td>
            <td>Rp " . number_format($item['subtotal']) . "</td>
        </tr>";
    }
}

$output['total'] = $data['total_harga'];
$output['nama_pelanggan'] = $data['nama_pelanggan'];
$output['nama_laundry'] = $data['nama_laundry'];
$output['status'] = $data['status_cucian'];
$output['tanggal'] = $data['tgl_mulai'];

echo json_encode($output);
