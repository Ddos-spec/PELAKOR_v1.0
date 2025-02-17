<?php
function getTransactionDetails($idCucian) {
    global $connect;
    
    $query = "SELECT t.*, c.tipe_layanan, c.berat, c.jenis,
        a.nama_laundry, p.nama as nama_pelanggan,
        CASE 
            WHEN c.tipe_layanan = 'kiloan' THEN c.berat * h.harga
            ELSE (SELECT SUM(dc.jumlah * hs.harga) 
                  FROM detail_cucian dc 
                  JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan
                  WHERE dc.id_cucian = c.id_cucian)
        END as total_harga
        FROM transaksi t
        JOIN cucian c ON t.id_cucian = c.id_cucian
        JOIN agen a ON t.id_agen = a.id_agen
        JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
        LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
        WHERE t.id_cucian = ?";

    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $idCucian);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

function getDetailItems($idCucian, $tipeLayanan) {
    global $connect;
    
    if($tipeLayanan == 'kiloan') {
        $query = "SELECT c.jenis, c.berat, h.harga, (c.berat * h.harga) as subtotal
            FROM cucian c
            JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
            WHERE c.id_cucian = ?";
    } else {
        $query = "SELECT hs.nama_item, dc.jumlah, hs.harga, dc.subtotal
            FROM detail_cucian dc
            JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan
            WHERE dc.id_cucian = ?";
    }
    
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $idCucian);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}
