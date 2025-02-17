<?php
function hitungTotal($idCucian) {
    global $connect;
    
    $query = "SELECT 
        c.*,
        CASE 
            WHEN c.tipe_layanan = 'kiloan' 
            THEN c.berat * h.harga
            ELSE (
                SELECT COALESCE(SUM(dc.jumlah * hs.harga), 0)
                FROM detail_cucian dc
                JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan
                WHERE dc.id_cucian = c.id_cucian
            )
        END as total_harga
        FROM cucian c
        LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
        WHERE c.id_cucian = $idCucian";
    
    $result = mysqli_query($connect, $query);
    $data = mysqli_fetch_assoc($result);
    
    return $data['total_harga'];
}

function hitungTotalBayar($idCucian) {
    global $connect;
    
    $cucian = mysqli_query($connect, "SELECT * FROM v_status_cucian WHERE id_cucian = $idCucian");
    $data = mysqli_fetch_assoc($cucian);
    
    return $data['total_harga'];
}

function hitungTotalKiloan($berat, $hargaPerKg) {
    return $berat * $hargaPerKg;
}

function hitungTotalSatuan($items) {
    $total = 0;
    foreach($items as $item) {
        $total += $item['jumlah'] * $item['harga'];
    }
    return $total;
}

function generateExportData($transaksi, $tipe) {
    // Logic export sesuai tipe (PDF/Excel)
}
