$(document).ready(function() {
    // Format number input
    $('.number-only').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Real-time harga validation
    $('#form-satuan input[name="harga_satuan"]').on('input', function() {
        let value = $(this).val();
        if(value < 0) {
            $(this).val(0);
        }
        updatePreviewHarga();
    });

    // Auto format currency
    $('.currency').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(formatRupiah(value));
    });

    // Validasi jumlah item satuan
    $('.item-jumlah').on('input', function() {
        let jumlah = parseInt($(this).val()) || 0;
        if(jumlah < 0) {
            $(this).val(0);
        }
        updateTotalPreview();
    });

    // Validasi form sebelum submit
    $('form').on('submit', function(e) {
        let tipe = $('#tipe_layanan').val();
        let valid = true;
        let message = '';

        if(tipe === 'kiloan') {
            let estimasi = parseInt($('#estimasi_item').val());
            if(!estimasi || estimasi <= 0) {
                valid = false;
                message = 'Estimasi item harus diisi dengan benar';
            }
            if(!$('input[name="jenis"]:checked').length) {
                valid = false;
                message = 'Pilih jenis layanan';
            }
        } else {
            let hasItems = false;
            $('.item-jumlah').each(function() {
                if(parseInt($(this).val()) > 0) {
                    hasItems = true;
                }
            });
            if(!hasItems) {
                valid = false;
                message = 'Pilih minimal 1 item';
            }
        }

        if(!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: message
            });
        }
    });
});

function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function updatePreviewHarga() {
    let total = 0;
    $('.item-harga').each(function() {
        let harga = parseInt($(this).val()) || 0;
        let jumlah = parseInt($(this).closest('tr').find('.item-jumlah').val()) || 0;
        total += harga * jumlah;
    });
    $('#totalPreview').text(formatRupiah(total));
}
