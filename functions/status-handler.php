<?php

function handleNewOrder($idCucian) {
    global $connect;
    
    $query = "UPDATE cucian SET status_cucian = 'Menunggu Konfirmasi' WHERE id_cucian = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $idCucian);
    
    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }
    
    return true;
}

function handleSimpleStatus($connect, $statusCucian, $idCucian, $idAgen) {
    mysqli_query($connect, "UPDATE cucian SET status_cucian = '$statusCucian' WHERE id_cucian = '$idCucian'");
    
    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                Swal.fire('Status Berhasil Di Ubah','','success').then(function() {
                    window.location = 'status.php';
                });
            </script>   
        ";
    }
}

function handleCompleteStatus($connect, $idCucian, $idAgen) {
    $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
    $cucian = mysqli_fetch_assoc($query);
    
    if($cucian['tipe_layanan'] == 'kiloan') {
        $qHarga = mysqli_query($connect, "SELECT harga FROM harga WHERE id_agen = '$idAgen' AND jenis = '{$cucian['jenis']}'");
        $hargaPerKg = mysqli_fetch_assoc($qHarga)['harga'];
        $total = $cucian['berat'] * $hargaPerKg;
    } else {
        $q = mysqli_query($connect, "SELECT SUM(subtotal) as total FROM detail_cucian WHERE id_cucian = $idCucian");
        $total = mysqli_fetch_assoc($q)['total'];
    }

    mysqli_query($connect, "INSERT INTO transaksi SET
        id_cucian = $idCucian,
        id_agen = $idAgen,
        id_pelanggan = {$cucian['id_pelanggan']},
        tgl_mulai = '{$cucian['tgl_mulai']}',
        tgl_selesai = NOW(),
        total_bayar = $total,
        rating = 0");

    return true;
}

function validateStatusTransition($currentStatus, $newStatus) {
    $allowedTransitions = [
        'Penjemputan' => ['Sedang di Cuci'], // For satuan
        'Sedang di Cuci' => ['Sedang di Jemur'],
        'Sedang di Jemur' => ['Sedang di Setrika'],
        'Sedang di Setrika' => ['Pengantaran'],
        'Pengantaran' => ['Selesai']
    ];
    
    // Special case - allow direct transition to Selesai from any status
    // This is needed for error handling and admin overrides
    if ($newStatus === 'Selesai') {
        return true;
    }
    
    // Special case - weight update for kiloan orders
    if ($currentStatus === 'Penjemputan' && $newStatus === 'Sedang di Cuci') {
        return true;
    }
    
    if (!isset($allowedTransitions[$currentStatus]) || 
        !in_array($newStatus, $allowedTransitions[$currentStatus])) {
        throw new Exception('Perubahan status tidak valid');
    }
    
    return true;
}

function logStatusChange($connect, $idCucian, $oldStatus, $newStatus, $catatan = '') {
    $timestamp = date("Y-m-d H:i:s");
    $catatan = mysqli_real_escape_string($connect, $catatan);
    
    return mysqli_query($connect, "UPDATE cucian SET 
        catatan_proses = CONCAT(IFNULL(catatan_proses, ''), 
        '\n[$timestamp] $oldStatus -> $newStatus: $catatan')
        WHERE id_cucian = $idCucian");
}

function handleStatusUpdate($connect, $idCucian, $statusBaru, $catatan = '', $idAgen = null) {
    mysqli_begin_transaction($connect);
    try {
        // Validate cucian exists
        $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
        if (!$query || mysqli_num_rows($query) === 0) {
            throw new Exception('Data cucian tidak ditemukan');
        }
        
        $cucian = mysqli_fetch_assoc($query);
        
        // Validate status transition
        validateStatusTransition($cucian['status_cucian'], $statusBaru);
        
        // Update status
        $result = mysqli_query($connect, "UPDATE cucian SET 
            status_cucian = '$statusBaru'
            WHERE id_cucian = '$idCucian'");
            
        if (!$result) {
            throw new Exception(mysqli_error($connect));
        }

        // Log status change
        logStatusChange($connect, $idCucian, $cucian['status_cucian'], $statusBaru, $catatan);

        if ($statusBaru === 'Selesai') {
            handleCompleteCucian($connect, $idCucian, $idAgen);
            mysqli_commit($connect);
            return ['redirect' => 'transaksi.php'];
        }

        mysqli_commit($connect);
        return ['success' => true];
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Status update error for cucian $idCucian: " . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

function handleCompleteCucian($connect, $idCucian, $idAgen) {
    // Validate cucian and pricing data
    $query = mysqli_query($connect, "SELECT c.*, h.harga 
        FROM cucian c 
        LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis 
        WHERE c.id_cucian = $idCucian");
        
    if (!$query || mysqli_num_rows($query) === 0) {
        throw new Exception('Data cucian tidak ditemukan');
    }
    
    $cucian = mysqli_fetch_assoc($query);
    
    // Validate weight for kiloan type
    if ($cucian['tipe_layanan'] == 'kiloan' && empty($cucian['berat'])) {
        throw new Exception('Berat cucian belum diisi');
    }
    
    // Calculate total
    $total = $cucian['tipe_layanan'] == 'kiloan' 
        ? $cucian['berat'] * $cucian['harga']
        : calculateSatuanTotal($connect, $idCucian);
        
    // Create transaction
    $result = mysqli_query($connect, "INSERT INTO transaksi SET
        id_cucian = $idCucian,
        id_agen = $idAgen,
        id_pelanggan = {$cucian['id_pelanggan']},
        tgl_mulai = '{$cucian['tgl_mulai']}',
        tgl_selesai = NOW(),
        total_bayar = $total,
        rating = 0");
        
    if (!$result) {
        throw new Exception('Gagal membuat transaksi: ' . mysqli_error($connect));
    }
    
    return true;
}

function handleWeightUpdate($connect, $berat, $idCucian, $catatan_berat) {
    if (!is_numeric($berat) || $berat <= 0) {
        throw new Exception('Berat harus lebih dari 0');
    }
    
    mysqli_begin_transaction($connect);
    try {
        $catatan_berat = mysqli_real_escape_string($connect, $catatan_berat);
        $timestamp = date("Y-m-d H:i:s");
        
        $result = mysqli_query($connect, "UPDATE cucian 
            SET berat = $berat,
                catatan_berat = '$catatan_berat',
                status_cucian = 'Sedang di Cuci',
                catatan_proses = CONCAT(IFNULL(catatan_proses, ''), 
                '\n[$timestamp] Input berat: $berat kg')
            WHERE id_cucian = $idCucian");
            
        if (!$result) {
            throw new Exception(mysqli_error($connect));
        }
        
        mysqli_commit($connect);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Weight update error for cucian $idCucian: " . $e->getMessage());
        throw $e;
    }
}

function handleAcceptOrder($connect, $idCucian, $catatan = '') {
    $timestamp = date("Y-m-d H:i:s");
    
    // Check order type
    $query = mysqli_query($connect, "SELECT tipe_layanan FROM cucian WHERE id_cucian = '$idCucian'");
    $cucian = mysqli_fetch_assoc($query);
    
    // For kiloan orders, keep status as Penjemputan until weight is confirmed
    $newStatus = ($cucian['tipe_layanan'] == 'kiloan') ? 'Penjemputan' : 'Sedang di Cuci';
    
    mysqli_begin_transaction($connect);
    try {
        mysqli_query($connect, "UPDATE cucian SET 
            status_cucian = '$newStatus',
            catatan_proses = CONCAT(IFNULL(catatan_proses, ''), '\n[$timestamp] Pesanan diterima: $catatan')
            WHERE id_cucian = '$idCucian'");
        
        if (mysqli_affected_rows($connect) <= 0) {
            throw new Exception("Gagal memperbarui status pesanan");
        }
        
        mysqli_commit($connect);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Error accepting order: " . $e->getMessage());
        return false;
    }
}

// Helper functions
function hitungTotalBayar($connect, $idCucian) {
    $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
    $cucian = mysqli_fetch_assoc($query);
    
    if($cucian['tipe_layanan'] == 'kiloan') {
        $qHarga = mysqli_query($connect, "SELECT harga FROM harga 
                                        WHERE id_agen = '{$cucian['id_agen']}' 
                                        AND jenis = '{$cucian['jenis']}'");
        $harga = mysqli_fetch_assoc($qHarga)['harga'];
        return $cucian['berat'] * $harga;
    } else {
        $qTotal = mysqli_query($connect, "SELECT SUM(subtotal) as total 
                                        FROM detail_cucian 
                                        WHERE id_cucian = $idCucian");
        return mysqli_fetch_assoc($qTotal)['total'];
    }
}

function calculateSatuanTotal($connect, $idCucian) {
    $query = mysqli_query($connect, "SELECT SUM(subtotal) as total 
        FROM detail_cucian WHERE id_cucian = $idCucian");
    if (!$query) {
        throw new Exception('Gagal menghitung total: ' . mysqli_error($connect));
    }
    return mysqli_fetch_assoc($query)['total'] ?? 0;
}
