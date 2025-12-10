<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = 'sql100.infinityfree.com'; 
$DB_USER = 'if0_40495066';       
$DB_PASS = 'vwhmBK7F9HHSwO';       
$DB_NAME = 'if0_40495066_database';  

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);


if (!$mysqli) {
    die('DB not connected properly');
}

if (mysqli_connect_errno()) {
    die("Database Connection failed: " . mysqli_connect_error());
}

$feedback_message = "";
$edit_car_data = null;
$action = '';

?>