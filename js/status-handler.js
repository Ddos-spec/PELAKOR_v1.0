$(document).ready(function() {
    // Initialize tooltips
    $('.tooltipped').tooltip();
    
    // Setup status change handler
    $('#status_cucian').on('change', function() {
        const newStatus = $(this).val();
        const idCucian = $(this).data('id-cucian');
        
        Swal.fire({
            title: 'Ubah Status?',
            text: `Status akan diubah ke "${newStatus}"`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed) {
                updateStatus(idCucian, newStatus);
            } else {
                $(this).val($(this).data('current-status'));
            }
        });
    });
});

function updateStatus(idCucian, status) {
    $.ajax({
        url: 'ajax/update-status.php',
        method: 'POST',
        data: {
            id_cucian: idCucian,
            status: status
        },
        success: function(response) {
            if(response.success) {
                Swal.fire('Berhasil', 'Status berhasil diubah', 'success')
                    .then(() => location.reload());
            } else {
                Swal.fire('Gagal', response.message, 'error');
            }
        }
    });
}
