-- View untuk menghitung total cucian
CREATE OR REPLACE VIEW v_total_cucian AS
SELECT 
    c.*,
    CASE 
        WHEN c.tipe_layanan = 'kiloan' THEN (c.berat * h.harga)
        ELSE COALESCE(
            (SELECT SUM(dc.jumlah * hs.harga) 
             FROM detail_cucian dc 
             JOIN harga_satuan hs ON dc.id_harga_satuan = hs.id_harga_satuan
             WHERE dc.id_cucian = c.id_cucian), 0)
    END as total_harga
FROM cucian c
LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis;

-- Index untuk optimasi query
ALTER TABLE cucian ADD INDEX idx_tipe_status (tipe_layanan, status_cucian);
ALTER TABLE detail_cucian ADD INDEX idx_cucian_harga (id_cucian, id_harga_satuan);
