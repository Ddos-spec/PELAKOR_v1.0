<?php
session_start();
include '../connect-db.php';

header('Content-Type: application/json');

// Handle agent list request dengan rating
if (isset($_GET['action']) && $_GET['action'] == 'getAgents') {
    $keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($connect, $_GET['keyword']) : '';
    
    // Query untuk mendapatkan data agen dengan rating
    $query = "SELECT a.*, 
              COALESCE(AVG(NULLIF(t.rating, 0)), 0) as rating
              FROM agen a 
              LEFT JOIN transaksi t ON a.id_agen = t.id_agen";
    
    if (!empty($keyword)) {
        $query .= " WHERE a.nama_laundry LIKE '%$keyword%' 
                    OR a.kota LIKE '%$keyword%' 
                    OR a.alamat LIKE '%$keyword%'";
    }
    
    $query .= " GROUP BY a.id_agen 
                ORDER BY a.nama_laundry ASC";
    
    $result = mysqli_query($connect, $query);
    $agents = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $agents[] = [
            'id_agen' => $row['id_agen'],
            'nama_laundry' => htmlspecialchars($row['nama_laundry']),
            'alamat' => htmlspecialchars($row['alamat']),
            'kota' => htmlspecialchars($row['kota']),
            'telp' => htmlspecialchars($row['telp']),
            'foto' => htmlspecialchars($row['foto']),
            'rating' => round($row['rating'])
        ];
    }
    
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
    
    // Default price structure
    $defaultPrices = [
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
    ];
    
    echo json_encode($defaultPrices);
    exit;
}

// Return error if action not specified
echo json_encode(['error' => 'Invalid action']);
?>
