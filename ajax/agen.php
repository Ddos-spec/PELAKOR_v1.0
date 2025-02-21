<?php
session_start();
include '../connect-db.php';

// Handle agent list request
if (isset($_GET['action']) && $_GET['action'] == 'getAgents') {
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    
    // Build query with keyword search
    $query = "SELECT * FROM agen WHERE 
        kota LIKE '%$keyword%' OR
        nama_laundry LIKE '%$keyword%'";
    
    $result = mysqli_query($connect, $query);
    $agents = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $agents[] = [
            'id_agen' => $row['id_agen'],
            'nama_laundry' => $row['nama_laundry'],
            'alamat' => $row['alamat'],
            'kota' => $row['kota'],
            'telp' => $row['telp'],
            'foto' => $row['foto']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($agents);
    exit;
}

// Handle price list request
if (isset($_GET['action']) && $_GET['action'] == 'getPrices') {
    $idAgen = intval($_GET['idAgen']);
    
    // Fetch prices from database
    $query = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen'");
    $prices = [];
    
    while ($row = mysqli_fetch_assoc($query)) {
        $prices[$row['jenis']] = $row['harga'];
    }
    
    // Return prices in JSON format
    header('Content-Type: application/json');
    echo json_encode([
        'baju' => [
            'cuci' => $prices['cuci'] ?? 5000,
            'setrika' => $prices['setrika'] ?? 3000,
            'komplit' => $prices['komplit'] ?? 7000
        ],
        'celana' => [
            'cuci' => $prices['cuci'] ?? 4000,
            'setrika' => $prices['setrika'] ?? 2500,
            'komplit' => $prices['komplit'] ?? 6000
        ],
        'jaket' => [
            'cuci' => $prices['cuci'] ?? 6000,
            'setrika' => $prices['setrika'] ?? 4000,
            'komplit' => $prices['komplit'] ?? 9000
        ],
        'karpet' => [
            'cuci' => $prices['cuci'] ?? 8000,
            'setrika' => $prices['setrika'] ?? 5000,
            'komplit' => $prices['komplit'] ?? 10000
        ],
        'pakaian_khusus' => [
            'cuci' => $prices['cuci'] ?? 10000,
            'setrika' => $prices['setrika'] ?? 6000,
            'komplit' => $prices['komplit'] ?? 12000
        ]
    ]);
    exit;
}
?>
