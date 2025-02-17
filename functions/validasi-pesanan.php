<?php
function validasiPesananBaru($data) {
    global $connect;
    
    $errors = [];

    // Validasi alamat
    if(empty($data['alamat'])) {
        $errors[] = 'Alamat pengambilan harus diisi';
    }

    // Validasi tipe layanan
    if($data['tipe_layanan'] == 'kiloan') {
        if(empty($data['jenis'])) {
            $errors[] = 'Jenis layanan harus dipilih';
        }
        if(empty($data['estimasi_item']) || !is_numeric($data['estimasi_item']) || $data['estimasi_item'] <= 0) {
            $errors[] = 'Estimasi item harus berupa angka positif';
        }
    } else {
        $hasItems = false;
        foreach($data['items'] as $id => $jumlah) {
            if($jumlah > 0) {
                $hasItems = true;
                if(!is_numeric($jumlah)) {
                    $errors[] = 'Jumlah item harus berupa angka';
                    break;
                }
            }
        }
        if(!$hasItems) {
            $errors[] = 'Pilih minimal 1 item';
        }
    }

    return $errors;
}
