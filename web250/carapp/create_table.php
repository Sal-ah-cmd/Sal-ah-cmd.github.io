<?php

include '../config_db.php'; 

$create_table_query = "
    CREATE TABLE IF NOT EXISTS INVENTORY 
    ( 
        VIN varchar(17) PRIMARY KEY, 
        YEAR INT, 
        Make varchar(50), 
        Model varchar(100), 
        TRIM varchar(50), 
        EXT_COLOR varchar (50), 
        INT_COLOR varchar (50), 
        ASKING_PRICE DECIMAL (10,2), 
        SALE_PRICE DECIMAL (10,2), 
        PURCHASE_PRICE DECIMAL (10,2), 
        MILEAGE int, 
        TRANSMISSION varchar (50), 
        PURCHASE_DATE DATE, 
        SALE_DATE DATE
    )
";

$mysqli->query($create_table_query);


if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

if ($action == 'delete' && isset($_GET['VIN'])) {
    $vin = $mysqli->real_escape_string($_GET['VIN']);
    $query = "DELETE FROM INVENTORY WHERE VIN='$vin'";
    if ($mysqli->query($query)) {
        $feedback_message = "<h3 style='color: green;'>Success! The vehicle with VIN **$vin** has been deleted.</h3>";
    } else {
        $feedback_message = "<h3 style='color: red;'>Error deleting car: " . $mysqli->error . "</h3>";
    }
    $action = '';
}

if ($action == 'add' || $action == 'edit') {
    $vin = $mysqli->real_escape_string(trim($_POST['VIN']));
    $make = $mysqli->real_escape_string(trim($_POST['Make']));
    $model = $mysqli->real_escape_string(trim($_POST['Model']));
    $year = $mysqli->real_escape_string(trim($_POST['YEAR']));
    $price = $mysqli->real_escape_string($_POST['ASKING_PRICE']);
    $mileage = $mysqli->real_escape_string($_POST['MILEAGE']);
    $trim = $mysqli->real_escape_string(trim($_POST['TRIM']));
    $ext_color = $mysqli->real_escape_string(trim($_POST['EXT_COLOR']));

    if ($action == 'add') {
        $query = "INSERT INTO inventory 
                  (VIN, YEAR, Make, Model, TRIM, EXT_COLOR, ASKING_PRICE, MILEAGE)
                  VALUES ('$vin', '$year', '$make', '$model', '$trim', '$ext_color', '$price', '$mileage')";
        $success_msg = "<h3 style='color: green;'>Success! You have successfully added **$make $model** into the inventory.</h3>";
        $error_msg = "<h3 style='color: red;'>Error adding car: " . $mysqli->error . "</h3>";
    } else { 
        $query = "UPDATE inventory SET 
                  YEAR='$year', 
                  Make='$make', 
                  Model='$model', 
                  TRIM='$trim',
                  EXT_COLOR='$ext_color',
                  ASKING_PRICE='$price',
                  MILEAGE='$mileage'
                  WHERE VIN='$vin'";
        $success_msg = "<h3 style='color: green;'>Success! The details for **$make $model (VIN: $vin)** have been updated.</h3>";
        $error_msg = "<h3 style='color: red;'>Error updating car: " . $mysqli->error . "</h3>";
    }

    if ($mysqli->query($query)) {
        $feedback_message = $success_msg;
    } else {
        $feedback_message = $error_msg;
    }
}

if ($action == 'edit_form' && isset($_GET['VIN'])) {
    $vin = $mysqli->real_escape_string($_GET['VIN']);
    $query = "SELECT * FROM inventory WHERE VIN='$vin'";
    if ($result = $mysqli->query($query)) {
        if ($result->num_rows > 0) {
            $edit_car_data = $result->fetch_assoc();
            $feedback_message = "<h3 style='color: blue;'>EDITING CAR: Please modify the fields for " . $edit_car_data['Make'] . " " . $edit_car_data['Model'] . " below.</h3>";
        }
    }
    $action = 'edit'; 
} else {
    $action = 'add'; 
}

function get_field_value($data, $field, $default = '') {
    return isset($data[$field]) ? htmlspecialchars($data[$field]) : $default;
}

$inventory_result = null;
$query = "SELECT * FROM inventory ORDER BY Make, Model";
if ($mysqli) {
    $inventory_result = $mysqli->query($query);
}
?>