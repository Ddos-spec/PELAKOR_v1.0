<?php
function updateHargaKiloan($idAgen, $jenis, $hargaBaru) {
    global $connect;
    
    mysqli_begin_transaction($connect);
    try {
        // Ambil harga lama
        $query = mysqli_query($connect, "SELECT harga FROM harga 
            WHERE id_agen = $idAgen AND jenis = '$jenis'");
        $hargaLama = mysqli_fetch_assoc($query)['harga'];
        
        // Update harga
        mysqli_query($connect, "UPDATE harga SET harga = $hargaBaru 
            WHERE id_agen = $idAgen AND jenis = '$jenis'");
            
        // Catat history
        mysqli_query($connect, "INSERT INTO history_harga (
            id_agen, jenis, harga_lama, harga_baru, tanggal_ubah
        ) VALUES ($idAgen, '$jenis', $hargaLama, $hargaBaru, NOW())");
        
        mysqli_commit($connect);
        return true;
    } catch(Exception $e) {
        mysqli_rollback($connect);
        throw $e;
    }
}

function updateHargaSatuan($idHargaSatuan, $hargaBaru) {
    global $connect;
    
    mysqli_begin_transaction($connect);
    try {
        // Ambil harga lama
        $query = mysqli_query($connect, "SELECT harga FROM harga_satuan 
            WHERE id_harga_satuan = $idHargaSatuan");
        $hargaLama = mysqli_fetch_assoc($query)['harga'];
        
        // Update harga
        mysqli_query($connect, "UPDATE harga_satuan SET harga = $hargaBaru 
            WHERE id_harga_satuan = $idHargaSatuan");
            
        // Catat history
        mysqli_query($connect, "INSERT INTO history_harga_satuan (
            id_harga_satuan, harga_lama, harga_baru
        ) VALUES ($idHargaSatuan, $hargaLama, $hargaBaru)");
        
        mysqli_commit($connect);
        return true;
    } catch(Exception $e) {
        mysqli_rollback($connect);
        throw $e;
    }
}
