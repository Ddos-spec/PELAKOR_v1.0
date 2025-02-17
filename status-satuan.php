<div class="card">
    <div class="card-content">
        <span class="card-title">Detail Cucian Satuan #<?= $idCucian ?></span>
        <table class="striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $details = mysqli_query($connect, "SELECT * FROM detail_cucian 
                    JOIN harga_satuan ON detail_cucian.id_harga_satuan = harga_satuan.id_harga_satuan 
                    WHERE id_cucian = $idCucian");
                while($item = mysqli_fetch_assoc($details)):
            ?>
                <tr>
                    <td><?= $item['nama_item'] ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td>Rp <?= number_format($item['harga']) ?></td>
                    <td>Rp <?= number_format($item['subtotal']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>