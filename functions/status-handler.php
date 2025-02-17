<?php
function updateStatus($idCucian, $status, $idAgen) {
    global $connect;
    
    mysqli_begin_transaction($connect);
    try {
        // Get existing data
        $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
        $data = mysqli_fetch_assoc($cucian);
        
        // Validate status transition
        $statusOrder = [
            'Menunggu Konfirmasi' => 1,
            'Penjemputan' => 2,
            'Diterima' => 3, 
            'Sedang di Cuci' => 4,
            'Sedang Di Jemur' => 5,
            'Sedang di Setrika' => 6,
            'Quality Control' => 7,
            'Siap Diantar' => 8,
            'Selesai' => 9
        ];
        
        if($statusOrder[$status] < $statusOrder[$data['status_cucian']]) {
            throw new Exception('Invalid status transition');
        }

        // Update status
        mysqli_query($connect, "UPDATE cucian SET 
            status_cucian = '$status',
            updated_at = NOW() 
            WHERE id_cucian = $idCucian");

        // If status is complete, create transaction
        if($status == 'Selesai') {
            $totalBayar = hitungTotalBayar($idCucian); // Centralized calculation
            
            mysqli_query($connect, "INSERT INTO transaksi 
                (id_cucian, id_agen, id_pelanggan, tgl_mulai, tgl_selesai, total_bayar, rating)
                VALUES (
                    $idCucian,
                    $idAgen,
                    {$data['id_pelanggan']},
                    '{$data['tgl_mulai']}',
                    NOW(),
                    $totalBayar,
                    0
                )"
            );
        }

        // Log status change
        mysqli_query($connect, "INSERT INTO status_history 
            (id_cucian, status_lama, status_baru, waktu_ubah)
            VALUES ($idCucian, '{$data['status_cucian']}', '$status', NOW())");

        mysqli_commit($connect);
        return true;
    } catch(Exception $e) {
        mysqli_rollback($connect);
        throw $e;
    }
}

function hitungTotalBayar($idCucian) {
    global $connect;
    
    $query = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
    $cucian = mysqli_fetch_assoc($query);
    
    if($cucian['tipe_layanan'] == 'kiloan') {
        $harga = mysqli_query($connect, "SELECT harga FROM harga 
            WHERE id_agen = {$cucian['id_agen']} 
            AND jenis = '{$cucian['jenis']}'");
        $harga = mysqli_fetch_assoc($harga);
        return $cucian['berat'] * $harga['harga'];
    } else {
        $detail = mysqli_query($connect, "SELECT SUM(dc.jumlah * hs.harga) as total
            FROM detail_cucian dc
            JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan 
            WHERE dc.id_cucian = $idCucian");
        $total = mysqli_fetch_assoc($detail);
        return $total['total'] ?? 0;
    }
}
