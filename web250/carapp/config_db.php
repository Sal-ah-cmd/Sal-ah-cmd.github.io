<?php

$DB_HOST = 'sql213.infinityfree.com';
$DB_USER = 'if0_40161635';       
$DB_PASS = 'cRFyJqBZX4pM';       
$DB_NAME = 'if0_40161635_cars';  

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
    die("Database Connection failed: " . mysqli_connect_error());
}

$feedback_message = "";
$edit_car_data = null;
$action = '';
?>