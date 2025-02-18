Dokumentasi LaundryKu v1.0
![LaundryKu Logo]

Daftar Isi
Pengenalan
Persyaratan Sistem
Instalasi
Panduan Pengguna
Admin
Petugas
Fitur
Troubleshooting
FAQ
Pengenalan
LaundryKu adalah sistem manajemen laundry yang memungkinkan pemilik dan petugas laundry untuk mengelola transaksi, pelanggan, dan inventaris dengan efisien.

Fitur Utama:
Manajemen pelanggan
Pencatatan transaksi
Pelacakan status cucian
Laporan keuangan dan kinerja
Manajemen inventaris
Sistem multi-user (Admin & Petugas)
Persyaratan Sistem
PHP 7.4 atau lebih tinggi
MySQL 5.7 atau lebih tinggi
Web Server (Apache/Nginx)
Browser modern (Chrome, Firefox, Safari, Edge)
Bootstrap 5.x
Koneksi internet untuk resources eksternal
Instalasi
1. Persiapan Database
SQL
-- Buat database baru
CREATE DATABASE laundryku;

-- Import file SQL yang disediakan
mysql -u username -p laundryku < laundryku.sql
2. Konfigurasi Aplikasi
Copy file db_connection.php.example ke db_connection.php
Edit konfigurasi database:
PHP
$host = 'localhost';
$db = 'laundryku';
$user = 'your_username';
$pass = 'your_password';
3. Pengaturan Web Server
Pastikan folder aplikasi dapat diakses oleh web server dan memiliki permission yang tepat.

Panduan Pengguna
Admin
1. Login Admin
Buka aplikasi di browser
Masukkan credentials admin:
Username: admin
Password: (sesuai yang diberikan)
2. Dashboard Admin
Setelah login, Admin dapat mengakses:

Officer Management
Laundry Types Management
Reports
Inventory Management
2.1 Manajemen Petugas
Klik "Manage Officers"
Tambah petugas baru: Klik "Add New Officer"
Edit petugas: Klik tombol "Edit" pada baris petugas
Hapus petugas: Klik tombol "Delete"
2.2 Manajemen Jenis Laundry
Klik "Manage Types"
Tambah jenis: Klik "Add New Laundry Type"
Set harga per kilo
Edit/Hapus jenis yang ada
2.3 Laporan
Lihat laporan keuangan
Analisis kinerja petugas
Export data (jika tersedia)
Petugas
1. Login Petugas
Buka aplikasi
Masukkan credentials petugas
Akses dashboard petugas
2. Manajemen Pelanggan
a. Tambah Pelanggan Baru:

Klik "Manage Customers"
Klik "Add New Customer"
Isi form data pelanggan:
Nama
Nomor Telepon
Alamat (opsional)
b. Edit/Hapus Pelanggan:

Gunakan tombol aksi di tabel pelanggan
3. Transaksi
3.1 Membuat Transaksi Baru
Klik "Manage Transactions"
Klik "Add New Transaction"
Isi detail transaksi:
Pilih pelanggan
Pilih jenis laundry
Masukkan berat
Set tanggal selesai
Simpan dan cetak invoice
3.2 Pelacakan Status
Klik "Invoice Tracking"
Masukkan nomor invoice
Update status cucian:
Diterima
Dicuci
Selesai
Fitur
1. Manajemen Pelanggan
Pendaftaran pelanggan baru
Update data pelanggan
Riwayat transaksi pelanggan
2. Transaksi
Input transaksi baru
Cetak invoice
Update status cucian
Kalkulasi otomatis berdasarkan berat dan jenis
3. Laporan
Laporan keuangan harian/bulanan
Statistik pelanggan
Kinerja petugas
Status inventaris
4. Inventaris
Tracking stok
Notifikasi stok menipis
Riwayat penggunaan
Troubleshooting
Masalah Umum dan Solusi
Tidak Bisa Login
Periksa username dan password
Pastikan caps lock tidak aktif
Reset password jika perlu
Error Database
Periksa koneksi database
Pastikan service MySQL berjalan
Cek konfigurasi di db_connection.php
Halaman Tidak Muncul
Clear cache browser
Periksa permission file
Cek error log server
FAQ
Q: Bagaimana cara reset password? A: Hubungi admin sistem untuk reset password.

Q: Bagaimana jika salah input transaksi? A: Gunakan fitur edit transaksi atau hubungi admin untuk pembatalan.

Q: Apakah bisa export data laporan? A: Ya, gunakan tombol export pada halaman laporan.

