function updateHargaPreview() {
    const tipe = $('#tipe_layanan').val();
    let total = 0;
    let html = '';

    if(tipe === 'kiloan') {
        const berat = parseFloat($('#berat').val()) || 0;
        const hargaPerKg = parseFloat($('#harga_per_kg').val()) || 0;
        total = berat * hargaPerKg;
        
        html = `<tr>
            <td>Cucian ${$('#jenis').val()}</td>
            <td>${berat} kg</td>
            <td>Rp ${numberFormat(hargaPerKg)}</td>
            <td>Rp ${numberFormat(total)}</td>
        </tr>`;
    } else {
        $('.item-satuan').each(function() {
            const jumlah = parseInt($(this).val()) || 0;
            const harga = parseFloat($(this).data('harga')) || 0;
            const subtotal = jumlah * harga;
            
            if(jumlah > 0) {
                html += `<tr>
                    <td>${$(this).data('nama')}</td>
                    <td>${jumlah}</td>
                    <td>Rp ${numberFormat(harga)}</td>
                    <td>Rp ${numberFormat(subtotal)}</td>
                </tr>`;
                total += subtotal;
            }
        });
    }

    $('#itemList').html(html);
    $('#totalHarga').text('Rp ' + numberFormat(total));
}

// Event listeners
$(document).ready(function() {
    $('#berat, #jenis, .item-satuan').on('change keyup', updateHargaPreview);
    $('#tipe_layanan').on('change', function() {
        $('.form-kiloan, .form-satuan').hide();
        $(`.form-${$(this).val()}`).show();
        updateHargaPreview();
    });
});
