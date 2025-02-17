-- Create optimized view for status monitoring
CREATE OR REPLACE VIEW v_status_monitoring AS
SELECT 
    c.id_cucian,
    c.id_agen,
    c.id_pelanggan,
    c.tipe_layanan,
    c.berat,
    c.jenis,
    c.status_cucian,
    c.tgl_mulai,
    c.estimasi_item,
    a.nama_laundry,
    p.nama as nama_pelanggan,
    CASE 
        WHEN c.tipe_layanan = 'kiloan' THEN c.berat * h.harga
        ELSE (SELECT SUM(dc.subtotal) FROM detail_cucian dc WHERE dc.id_cucian = c.id_cucian)
    END as total_harga
FROM cucian c
JOIN agen a ON c.id_agen = a.id_agen
JOIN pelanggan p ON c.id_pelanggan = p.id_pelanggan
LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
WHERE c.status_cucian != 'Selesai';

-- Add indexes for better performance
CREATE INDEX idx_cucian_status ON cucian(status_cucian);
CREATE INDEX idx_cucian_dates ON cucian(tgl_mulai);
CREATE INDEX idx_transaksi_customer ON transaksi(id_pelanggan, id_agen);
