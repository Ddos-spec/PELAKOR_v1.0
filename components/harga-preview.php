<div class="price-preview card">
    <div class="card-content">
        <span class="card-title">Preview Harga</span>
        <div class="row">
            <div class="col s12">
                <div class="table-responsive">
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Item/Jenis</th>
                                <th>Jumlah/Berat</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="itemList"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="right-align"><strong>Total</strong></td>
                                <td><strong id="totalHarga">Rp 0</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.price-preview {
    margin: 1rem 0;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

@media only screen and (max-width: 600px) {
    .price-preview table {
        font-size: 14px;
    }
    
    .price-preview th, 
    .price-preview td {
        padding: 8px 5px;
    }
}
</style>
