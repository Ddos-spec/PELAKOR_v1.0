<?php
// Konfigurasi database
$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'laundryku'
];

// Membuat koneksi
$connect = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

// Cek koneksi
if (!$connect) {
    $error = mysqli_connect_error();
    error_log("Database connection failed: " . $error);
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed',
        'error' => $error
    ]));
}

// Set charset
mysqli_set_charset($connect, 'utf8mb4');
?>
