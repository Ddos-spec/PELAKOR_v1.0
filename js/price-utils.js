function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

function updatePreviewHarga(type, data) {
    let total = 0;
    let breakdown = '';

    if(type === 'kiloan') {
        const berat = parseFloat(data.berat) || 0;
        const harga = parseFloat(data.harga) || 0;
        total = berat * harga;
        
        breakdown = `
            <tr>
                <td>Berat</td>
                <td>${berat} kg</td>
                <td>${formatRupiah(harga)}/kg</td>
                <td>${formatRupiah(total)}</td>
            </tr>
        `;
    } else {
        Object.keys(data).forEach(key => {
            const item = data[key];
            const subtotal = item.jumlah * item.harga;
            total += subtotal;
            
            if(item.jumlah > 0) {
                breakdown += `
                    <tr>
                        <td>${item.nama}</td>
                        <td>${item.jumlah}</td>
                        <td>${formatRupiah(item.harga)}</td>
                        <td>${formatRupiah(subtotal)}</td>
                    </tr>
                `;
            }
        });
    }

    $('#itemList').html(breakdown);
    $('#totalHarga').text(formatRupiah(total));
}
