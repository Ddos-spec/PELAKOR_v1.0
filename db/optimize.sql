-- Index untuk pencarian transaksi
ALTER TABLE transaksi ADD INDEX idx_pelanggan_agen (id_pelanggan, id_agen);
ALTER TABLE cucian ADD INDEX idx_status (status_cucian);
ALTER TABLE detail_cucian ADD INDEX idx_cucian (id_cucian);

-- Index untuk harga
ALTER TABLE harga ADD INDEX idx_agen_jenis (id_agen, jenis);
ALTER TABLE harga_satuan ADD INDEX idx_agen (id_agen);

-- Optimasi query status
CREATE VIEW v_status_cucian AS
SELECT c.*, a.nama_laundry, p.nama as nama_pelanggan,
    COALESCE(
        (SELECT SUM(subtotal) FROM detail_cucian WHERE id_cucian = c.id_cucian),
        (c.berat * h.harga)
    ) as total_harga
FROM cucian c
LEFT JOIN agen a ON a.id_agen = c.id_agen
LEFT JOIN pelanggan p ON p.id_pelanggan = c.id_pelanggan
LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
WHERE c.status_cucian != 'Selesai';
