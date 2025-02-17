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
