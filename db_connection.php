<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'laundryku');
define('DB_USER', 'root');
define('DB_PASS', '');

// Error handling configuration
define('DB_SHOW_ERRORS', true); // Set to true in development, false in production

try {
    // Create PDO instance with additional options
    $conn = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ]
    );
} catch (PDOException $e) {
    // Handle connection errors gracefully
    if (DB_SHOW_ERRORS) {
        error_log('Database connection error: ' . $e->getMessage());
        die('Database connection error. Please try again later.');
    } else {
        error_log('Database connection error: ' . $e->getMessage());
        die('Service unavailable. Please try again later.');
    }
}
?>
