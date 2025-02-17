<!-- Preload assets -->
<link rel="preload" href="css/modal.css" as="style">
<div id="detailModal" class="modal">
    <!-- Skeleton loading screen -->
    <div class="modal-content">
        <div class="skeleton-loader" id="modalLoader">
            <div class="skeleton-header"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-table">
                <div class="skeleton-row"></div>
                <div class="skeleton-row"></div>
            </div>
        </div>

        <div id="detailContent" style="display:none;">
            <div class="row">
                <div class="col s6">
                    <p><strong>Pelanggan:</strong> <span id="namaPelanggan"></span></p>
                    <p><strong>Agen:</strong> <span id="namaAgen"></span></p>
                </div>
                <div class="col s6">
                    <p><strong>Tanggal:</strong> <span id="tanggalTransaksi"></span></p>
                    <p><strong>Status:</strong> <span id="statusCucian"></span></p>
                </div>
            </div>

            <table class="striped">
                <thead>
                    <tr>
                        <th>Item/Jenis</th>
                        <th>Jumlah/Berat</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="detailItems"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="right-align"><strong>Total</strong></td>
                        <td><strong id="totalAmount">Rp 0</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Tutup</a>
    </div>
</div>

<style>
.skeleton-loader {
    animation: loading 1.5s infinite;
}

.skeleton-header {
    height: 32px;
    background: #f0f0f0;
    margin-bottom: 15px;
    border-radius: 4px;
}

.skeleton-line {
    height: 16px;
    background: #f0f0f0;
    margin: 10px 0;
    border-radius: 4px;
}

.skeleton-row {
    height: 40px;
    background: #f0f0f0;
    margin: 8px 0;
    border-radius: 4px;
}

@keyframes loading {
    0% { opacity: 0.6; }
    50% { opacity: 0.8; }
    100% { opacity: 0.6; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal with caching
    let modalInstance;
    let cachedData = {};

    $('.modal').modal({
        onOpenStart: function() {
            // Show skeleton loader
            $('#modalLoader').show();
            $('#detailContent').hide();
        }
    });

    window.showDetailTransaksi = function(id) {
        if (cachedData[id]) {
            updateModalContent(cachedData[id]);
            modalInstance.open();
        } else {
            $.get(`ajax/get-detail.php?id=${id}`, function(data) {
                cachedData[id] = data;
                updateModalContent(data);
                modalInstance.open();
            });
        }
    }
});
</script>
