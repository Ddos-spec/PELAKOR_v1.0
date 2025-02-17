<?php
function validasiPesanan($data) {
    $errors = [];
    
    if(empty($data['alamat'])) {
        $errors[] = 'Alamat pengambilan harus diisi';
    }
    
    if($data['tipe_layanan'] == 'kiloan') {
        if(empty($data['jenis'])) {
            $errors[] = 'Pilih jenis layanan';
        }
        if(empty($data['estimasi_item']) || $data['estimasi_item'] <= 0) {
            $errors[] = 'Estimasi item tidak valid';
        }
    } else {
        if(empty($data['items'])) {
            $errors[] = 'Pilih minimal 1 item';
        }
    }
    
    return $errors;
}

function validasiPesananKiloan($data) {
    if(empty($data['berat']) || !is_numeric($data['berat'])) {
        throw new Exception('Berat harus diisi dengan angka');
    }
    if(empty($data['jenis'])) {
        throw new Exception('Jenis layanan harus dipilih');
    }
    if($data['estimasi_item'] < 1) {
        throw new Exception('Estimasi item tidak valid');
    }
}

function validasiPesananSatuan($items) {
    $total = 0;
    foreach($items as $item) {
        if($item['jumlah'] < 0) {
            throw new Exception('Jumlah item tidak valid');
        }
        $total += $item['jumlah'];
    }
    if($total == 0) {
        throw new Exception('Minimal pilih 1 item');
    }
}
?>