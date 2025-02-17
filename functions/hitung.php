<?php
function hitungTotalKiloan($berat, $harga) {
    return $berat * $harga;
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
