<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: login.php');
        exit();
    }

    // Check user credentials
    $query = "SELECT * FROM tb_akun WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id_akun'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            // Redirect based on user level
            if ($user['level'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: officer_dashboard.php');
            }
            exit();
        }
    }

    // If authentication fails
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: login.php');
    exit();
}
?>
