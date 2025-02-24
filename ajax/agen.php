<?php
session_start();
include '../connect-db.php';

// Tambahkan header cache control
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

// Handle request daftar agen dengan rating + pagination
if (isset($_GET['action']) && $_GET['action'] == 'getAgents') {
    $keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($connect, $_GET['keyword']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $jumlahDataPerHalaman = 3; // Sesuaikan dengan index.php
    $awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;
    
    // Query dasar untuk menghitung total data (sebelum LIMIT)
    $baseQuery = "SELECT a.*, 
                  COALESCE(AVG(NULLIF(t.rating, 0)), 0) as rating
                  FROM agen a 
                  LEFT JOIN transaksi t ON a.id_agen = t.id_agen";
    
    // Tambahkan kondisi pencarian jika keyword tidak kosong
    if (!empty($keyword)) {
        $baseQuery .= " WHERE a.nama_laundry LIKE '%$keyword%'
                        OR a.kota LIKE '%$keyword%'
                        OR a.alamat LIKE '%$keyword%'";
    }
    
    $baseQuery .= " GROUP BY a.id_agen";
    
    // Hitung total data
    $countQuery = "SELECT COUNT(*) as total FROM ($baseQuery) as subquery";
    $countResult = mysqli_query($connect, $countQuery);
    $rowCount = mysqli_fetch_assoc($countResult);
    $totalData = $rowCount ? (int)$rowCount['total'] : 0;
    $totalPages = ceil($totalData / $jumlahDataPerHalaman);

    // Query final dengan LIMIT
    $query = $baseQuery . " ORDER BY a.nama_laundry ASC
                            LIMIT $awalData, $jumlahDataPerHalaman";
    $result = mysqli_query($connect, $query);
    $agents = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $agents[] = [
            'id_agen'      => $row['id_agen'],
            'nama_laundry' => htmlspecialchars($row['nama_laundry']),
            'alamat'       => htmlspecialchars($row['alamat']),
            'kota'         => htmlspecialchars($row['kota']),
            'telp'         => htmlspecialchars($row['telp']),
            'foto'         => htmlspecialchars($row['foto']),
            'rating'       => round($row['rating'])
        ];
    }

    // Kembalikan data agen beserta info pagination
    echo json_encode([
        'agents' => $agents,
        'pagination' => [
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalData,
            'perPage'      => $jumlahDataPerHalaman
        ]
    ]);
    exit;
}

// Handle request harga untuk dynamic price
if (isset($_GET['action']) && $_GET['action'] == 'getPrices') {
    try {
        $idAgen = intval($_GET['idAgen']);
        if ($idAgen <= 0) {
            throw new Exception("Invalid agent ID");
        }
        
        // Ambil data harga dari tabel harga berdasarkan id_agen
        $query = mysqli_query($connect, "SELECT * FROM harga WHERE id_agen = '$idAgen'");
        
        if (!$query) {
            throw new Exception(mysqli_error($connect));
        }
        
        $prices = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $prices[$row['jenis']] = $row['harga'];
        }
        
        // Kembalikan data harga secara langsung agar sesuai dengan pemanggilan JS dynamic price
        echo json_encode([
            'baju' => [
                'cuci'    => $prices['cuci'] ?? 5000,
                'setrika' => $prices['setrika'] ?? 3000,
                'komplit' => $prices['komplit'] ?? 7000
            ],
            'celana' => [
                'cuci'    => $prices['cuci'] ?? 4000,
                'setrika' => $prices['setrika'] ?? 2500,
                'komplit' => $prices['komplit'] ?? 6000
            ],
            'jaket' => [
                'cuci'    => $prices['cuci'] ?? 6000,
                'setrika' => $prices['setrika'] ?? 4000,
                'komplit' => $prices['komplit'] ?? 9000
            ],
            'karpet' => [
                'cuci'    => $prices['cuci'] ?? 8000,
                'setrika' => $prices['setrika'] ?? 5000,
                'komplit' => $prices['komplit'] ?? 10000
            ],
            'pakaian_khusus' => [
                'cuci'    => $prices['cuci'] ?? 10000,
                'setrika' => $prices['setrika'] ?? 6000,
                'komplit' => $prices['komplit'] ?? 12000
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Error in getPrices: " . $e->getMessage());
        echo json_encode([
            'status' => false,
            'error'  => $e->getMessage()
        ]);
    }
    exit;
}

// Kembalikan error jika action tidak valid
echo json_encode(['error' => 'Invalid action']);
?>
