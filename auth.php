<?php
session_start();
require_once 'db_connection.php';

// Session security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rate limiting (basic implementation)
    if (isset($_SESSION['last_login_attempt']) && 
        time() - $_SESSION['last_login_attempt'] < 5) {
        $_SESSION['error'] = 'Too many attempts. Please wait.';
        header('Location: login.php');
        exit();
    }
    $_SESSION['last_login_attempt'] = time();

    // Validate and sanitize input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: login.php');
        exit();
    }

    // Check user credentials
    try {
        $query = "SELECT * FROM tb_akun WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set secure session variables
            $_SESSION['user_id'] = $user['id_akun'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

            $redirect = ($user['level'] === 'admin') ? 
                'admin_dashboard.php' : 'officer_dashboard.php';
            header("Location: $redirect?login=success");
            exit();
        }
    } catch (PDOException $e) {
        error_log('Login error: ' . $e->getMessage());
    }

    // Generic error message to prevent user enumeration
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: login.php');
    exit();
}
?>
