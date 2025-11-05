<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../config_db.php';

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function attempt_login($username, $password) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && function_exists('password_verify') && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return '';
    }
    return 'Incorrect username or password.';
}

function logout() {
    session_unset();
    session_destroy();
}

function current_user_display() {
    return $_SESSION['username'] ?? '';
}
?>

