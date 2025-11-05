<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

require_once __DIR__ . '/auth.php';

$username = 'admin';
$password = 'admin';

$result = attempt_login($username, $password);

if ($result === '') {
    echo "Login successful! User: " . current_user_display();
} else {
    echo "Login failed: $result";
}
?>
