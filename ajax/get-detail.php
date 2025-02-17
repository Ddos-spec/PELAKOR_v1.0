<?php
session_start();
include '../connect-db.php';
include '../functions/functions.php';

// Cek akses
if(!isset($_SESSION['login-admin']) && !isset($_SESSION['login-agen'])) {
    die(json_encode(['error' => 'Unauthorized']));
}

$id = $_GET['id'];
$output = ['items' => '', 'total' => 0];

$cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $id");
$data = mysqli_fetch_assoc($cucian);

if($data['tipe_layanan'] == 'kiloan') {
    // Detail cucian kiloan
    $output['items'] = "<tr>
        <td>Cucian {$data['jenis']}</td>
        <td>{$data['berat']} kg</td>
        <td>Rp " . number_format($data['berat'] * $data['harga_per_kg']) . "</td>
    </tr>";
    $output['total'] = $data['berat'] * $data['harga_per_kg'];
} else {
    // Detail cucian satuan
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
        $output['total'] += $item['subtotal'];
    }
}

echo json_encode($output);
