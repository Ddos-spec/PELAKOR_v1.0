<?php
function updateStatus($idCucian, $status, $idAgen) {
    global $connect;
    
    mysqli_begin_transaction($connect);
    try {
        // Validasi urutan status
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

        $cucian = mysqli_query($connect, "SELECT * FROM cucian WHERE id_cucian = $idCucian");
        $data = mysqli_fetch_assoc($cucian);
        
        // Cek urutan status valid
        if($statusOrder[$status] < $statusOrder[$data['status_cucian']]) {
            throw new Exception('Urutan status tidak valid');
        }

        // Update status
        mysqli_query($connect, "UPDATE cucian SET 
            status_cucian = '$status',
            updated_at = NOW() 
            WHERE id_cucian = $idCucian");

        // Jika status selesai
        if($status == 'Selesai') {
            $total = hitungTotal($idCucian); // Menggunakan fungsi centralized
            
            mysqli_query($connect, "INSERT INTO transaksi 
                (id_cucian, id_agen, id_pelanggan, tgl_mulai, tgl_selesai, total_bayar, rating)
                VALUES ($idCucian, $idAgen, {$data['id_pelanggan']}, 
                '{$data['tgl_mulai']}', NOW(), $total, 0)");
        }

        // Log perubahan status
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
