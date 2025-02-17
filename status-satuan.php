function validasiPesanan($data) {
    if(empty($data['alamat'])) {
        throw new Exception('Alamat pengambilan harus diisi');
    }
    
    if($data['tipe_layanan'] == 'kiloan') {
        if(empty($data['jenis'])) {
            throw new Exception('Pilih jenis layanan (cuci/setrika/komplit)');
        }
        if(empty($data['estimasi_item'])) {
            throw new Exception('Estimasi jumlah item harus diisi');
        }
    } else {
        if(empty($data['items']) || count($data['items']) == 0) {
            throw new Exception('Pilih minimal 1 item untuk layanan satuan');
        }
    }
}