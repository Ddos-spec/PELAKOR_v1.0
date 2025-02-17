-- Status History Table
CREATE TABLE status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cucian INT NOT NULL,
    status_lama VARCHAR(50),
    status_baru VARCHAR(50),
    waktu_ubah DATETIME,
    FOREIGN KEY (id_cucian) REFERENCES cucian(id_cucian)
);

-- Normalisasi tabel detail_cucian
CREATE TABLE kategori_item (
    id_kategori INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL
);
n keys
ALTER TABLE harga_satuan
    ADD COLUMN id_kategori INT, CONSTRAINT fk_transaksi_cucian 
    ADD CONSTRAINT fk_kategori_item IGN KEY (id_cucian) REFERENCES cucian(id_cucian)
    FOREIGN KEY (id_kategori) REFERENCES kategori_item(id_kategori);ICT;

-- Tambah tracking untuk perubahan harga satuanoperations
CREATE TABLE history_harga_satuan (
    id_history INT PRIMARY KEY AUTO_INCREMENT,
    id_harga_satuan INT,
    harga_lama INT,
    harga_baru INT,
    tanggal_ubah TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_harga_satuan) REFERENCES harga_satuan(id_harga_satuan)    a.nama_laundry,

WHERE c.status_cucian != 'Selesai';LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenisJOIN pelanggan p ON c.id_pelanggan = p.id_pelangganJOIN agen a ON c.id_agen = a.id_agenFROM cucian c    (SELECT COUNT(*) FROM detail_cucian WHERE id_cucian = c.id_cucian) as jumlah_item    h.harga as harga_kiloan,    p.nama as nama_pelanggan,    a.nama_laundry,    c.*,SELECT CREATE VIEW v_status_monitoring AS-- View untuk Status MonitoringCREATE INDEX idx_transaksi_date ON transaksi(tgl_mulai, tgl_selesai);CREATE INDEX idx_cucian_tipe ON cucian(tipe_layanan);CREATE INDEX idx_cucian_status ON cucian(status_cucian);-- Optimasi Index);    p.nama as nama_pelanggan,
    h.harga as harga_kiloan,
    (SELECT COUNT(*) FROM detail_cucian WHERE id_cucian = c.id_cucian) as jumlah_item
FROM cucian c
JOIN agen a ON c.id_agen = a.id_agen
JOIN pelanggan p ON c.id_pelanggan = p.id_pelanggan
LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis
WHERE c.status_cucian != 'Selesai';

-- View untuk melihat transaksi dengan detail lengkap
CREATE OR REPLACE VIEW v_transaksi_detail AS 
SELECT 
    t.*,
    c.tipe_layanan,
    c.berat,
    c.jenis,
    c.status_cucian,
    a.nama_laundry,
    p.nama as nama_pelanggan,
    CASE 
        WHEN c.tipe_layanan = 'kiloan' THEN c.berat * h.harga
        ELSE (SELECT SUM(subtotal) FROM detail_cucian WHERE id_cucian = c.id_cucian)
    END as total_harga


LEFT JOIN harga h ON h.id_agen = c.id_agen AND h.jenis = c.jenis;FROM transaksi t
JOIN cucian c ON t.id_cucian = c.id_cucian
JOIN agen a ON t.id_agen = a.id_agen
JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan