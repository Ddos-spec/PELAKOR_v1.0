$(document).ready(function() {
    // Initialize modal
    $('.modal').modal();
    
    // Initialize select
    $('select').formSelect();
});

function showDetailTransaksi(id) {
    $('#loadingBar').show();
    $('#detailContent').hide();
    
    $.ajax({
        url: 'ajax/get-detail.php',
        data: {id: id},
        success: function(response) {
            const data = JSON.parse(response);
            $('#detailItems').html(data.items);
            $('#totalAmount').text(numberFormat(data.total));
            $('#loadingBar').hide();
            $('#detailContent').show();
            $('#detailModal').modal('open');
        }
    });
}

function exportData(type) {
    window.location.href = `export.php?type=${type}`;
}

function filterData(tipe) {
    $('.data-row').hide();
    if(tipe === 'all') {
        $('.data-row').show();
    } else {
        $(`.data-row[data-tipe="${tipe}"]`).show();
    }
}

function numberFormat(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}
