<?php
session_start();

// Session security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');

// Session timeout (30 minutes)
$inactive = 1800;
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit();
    }
}
$_SESSION['timeout'] = time();

// Validate session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Prevent session hijacking
if (isset($_SESSION['ip']) && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header('Location: login.php?security=1');
    exit();
}

if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: login.php?security=1');
    exit();
}
?>
