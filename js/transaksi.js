function showDetail(idCucian) {
    $('#loadingBar').show();
    $('#detailContent').hide();
    
    $.ajax({
        url: 'ajax/get-detail.php',
        data: {id: idCucian},
        success: function(response) {
            $('#detailItems').html(response.items);
            $('#totalAmount').text(response.total);
            $('#loadingBar').hide();
            $('#detailContent').show();
        }
    });
}

function filterTransaksi(tipe) {
    $('.transaksi-row').hide();
    if(tipe == 'all') {
        $('.transaksi-row').show();
    } else {
        $('.transaksi-row[data-tipe="'+tipe+'"]').show();
    }
}
