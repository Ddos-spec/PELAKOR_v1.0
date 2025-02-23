<?php
// Fungsi untuk mengecek apakah admin sudah login
function cekAdmin() {
    if (!isset($_SESSION['admin'])) {
        header("Location: login.php");
        exit();
    }
}

// Fungsi untuk menghitung rating agen berdasarkan ID
function calculateAgentRating($connect, $idAgen) {
    // Check if the review table exists
    $tableExists = mysqli_query($connect, "SHOW TABLES LIKE 'review'");
    if (mysqli_num_rows($tableExists) === 0) {
        return 0; // Return 0 if the review table doesn't exist
    }

    $query = "SELECT AVG(rating) as avg_rating FROM review WHERE id_agen = $idAgen";
    $result = mysqli_query($connect, $query);
    
    if ($result === false) {
        return 0; // Return 0 if the query fails
    }

    if ($row = mysqli_fetch_assoc($result)) {
        return round($row['avg_rating'] ?? 0);
    }
    return 0;
}

// Fungsi untuk mendapatkan data pelanggan berdasarkan ID
function getCustomerData($connect, $idPelanggan) {
    $query = "SELECT * FROM pelanggan WHERE id_pelanggan = $idPelanggan LIMIT 1";
    $result = mysqli_query($connect, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    return null;
}

// Fungsi untuk mendapatkan data agen berdasarkan ID
function getAgentData($connect, $idAgen) {
    $query = "SELECT * FROM agen WHERE id_agen = $idAgen LIMIT 1";
    $result = mysqli_query($connect, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    return null;
}

// Fungsi untuk mengecek apakah pelanggan sudah login
function cekPelanggan() {
    if (!isset($_SESSION["pelanggan"])) {
        echo "
            <script>
                Swal.fire('Akses Ditolak','Anda harus login sebagai pelanggan','warning');
                document.location.href = 'login.php';
            </script>
        ";
        exit;
    }
}

// Fungsi untuk validasi format email
function validasiEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "
            <script>
                Swal.fire('Format Email Salah','Masukkan email yang valid','warning');
            </script>
        ";
        exit;
    }
}

// Fungsi untuk validasi username
function validasiUsername($username) {
    if (!preg_match('/^[a-zA-Z0-9]{5,}$/', $username)) {
        echo "
            <script>
                Swal.fire('Format Username Salah','Username harus alfanumerik dan minimal 5 karakter','warning');
            </script>
        ";
        exit;
    }
}

// Fungsi untuk mengecek apakah user sudah login
function cekLogin() {
    if (isset($_SESSION["login-admin"]) || isset($_SESSION["login-agen"]) || isset($_SESSION["login-pelanggan"])) {
        echo "
            <script>
                document.location.href = 'index.php';
            </script>
        ";
        exit;
    }
}

// Fungsi untuk mendapatkan harga paket dasar berdasarkan jenis layanan (cuci, setrika, komplit)
function getHargaPaket($serviceType, $idAgen, $connect) {
    try {
        cekAdmin();
        
        // Log price access
        error_log("Package price accessed by admin ID: " . $_SESSION['admin']);
        
        $query = "SELECT harga FROM harga WHERE id_agen = $idAgen LIMIT 1";
        $result = mysqli_query($connect, $query);
        
        if (!$result) {
            throw new Exception("Failed to fetch package price");
        }
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['harga'] ? $row['harga'] : 0;
        }
        
        // Default prices if not found
        $default = [
            'cuci' => 5000,
            'setrika' => 3000, 
            'komplit' => 7000
        ];
        
        return $default[$serviceType] ?? 0;
        
    } catch (Exception $e) {
        error_log("Error in getHargaPaket: " . $e->getMessage());
        return 0;
    }
}

// Fungsi untuk mendapatkan harga per item berdasarkan jenis pakaian
function getPerItemPrice($item, $idAgen, $connect) {
    try {
        cekAdmin();
        
        // Log price access
        error_log("Item price accessed by admin ID: " . $_SESSION['admin']);
        
        $column = '';
        switch(strtolower($item)) {
            case 'baju':
                $column = 'harga_baju';
                break;
            case 'celana':
                $column = 'harga_celana';
                break;
            case 'jaket':
                $column = 'harga_jaket';
                break;
            case 'karpet':
                $column = 'harga_karpet';
                break;
            case 'pakaian_khusus':
                $column = 'harga_pakaian_khusus';
                break;
            default:
                return 0;
        }
        
        $query = "SELECT $column FROM harga WHERE id_agen = $idAgen LIMIT 1";
        $result = mysqli_query($connect, $query);
        
        if (!$result) {
            throw new Exception("Failed to fetch item price");
        }
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row[$column] ? $row[$column] : 0;
        }
        
        return 0;
        
    } catch (Exception $e) {
        error_log("Error in getPerItemPrice: " . $e->getMessage());
        return 0;
    }
}

// Fungsi untuk menghitung total harga per item berdasarkan string item_type (misalnya "Baju (2), Celana (3)")
function getTotalPerItem($itemType, $idAgen, $connect) {
    $total = 0;
    $items = explode(',', $itemType);
    foreach ($items as $item) {
        if (trim($item) == '') continue;
        if (preg_match('/([^(]+)\((\d+)\)/', $item, $matches)) {
            $name = trim($matches[1]);
            $qty = (int)$matches[2];
            $price = getPerItemPrice($name, $idAgen, $connect);
            $total += $price * $qty;
        }
    }
    return $total;
}

// Fungsi untuk menghitung total harga transaksi (harga paket dasar + total harga per item)
function calculateTotalHarga($transaksi, $connect) {
    $basePrice = getHargaPaket($transaksi['jenis'], $transaksi['id_agen'], $connect);
    $perItemTotal = getTotalPerItem($transaksi['item_type'], $transaksi['id_agen'], $connect);
    return $basePrice + $perItemTotal;
}

// Fungsi untuk membuat pesanan baru
function createOrder($connect, $orderData) {
    $query = "INSERT INTO cucian (
        id_agen, 
        id_pelanggan, 
        tgl_mulai, 
        jenis, 
        alamat, 
        catatan, 
        item_type, 
        total_item
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "iisssssi",
        $orderData['id_agen'],
        $orderData['id_pelanggan'],
        $orderData['tgl_mulai'],
        $orderData['jenis'],
        $orderData['alamat'],
        $orderData['catatan'],
        $orderData['item_type'],
        $orderData['total_item']
    );
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}
?>
