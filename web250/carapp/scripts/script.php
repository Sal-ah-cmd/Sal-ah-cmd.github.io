<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "<b>Error [$errno]</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
    return true;
});

include __DIR__ . '/../config_db.php';

if (!$mysqli) {
    die('DB not connected properly');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'delete' && isset($_GET['VIN'])) {
    $vin = $mysqli->real_escape_string($_GET['VIN']);
    
    $mysqli->query("DELETE FROM images WHERE VIN='$vin'");
    
    $query = "DELETE FROM INVENTORY WHERE VIN='$vin'";
    if ($mysqli->query($query)) {
        $feedback_message = "<h3 style='color: green;'>Success! Vehicle with VIN $vin deleted.</h3>";
    } else {
        $feedback_message = "<h3 style='color: red;'>Error deleting car: " . $mysqli->error . "</h3>";
    }
    $action = '';
}

if (($action === 'add' || $action === 'edit') && isset($_POST['VIN'])) {
    $vin = $mysqli->real_escape_string(trim($_POST['VIN']));
    $year = $mysqli->real_escape_string($_POST['YEAR']);
    $make = $mysqli->real_escape_string(trim($_POST['Make']));
    $model = $mysqli->real_escape_string(trim($_POST['Model']));
    $trim = $mysqli->real_escape_string(trim($_POST['TRIM']));
    $ext_color = $mysqli->real_escape_string(trim($_POST['EXT_COLOR']));
    $mileage = $mysqli->real_escape_string($_POST['MILEAGE']);
    $price = $mysqli->real_escape_string($_POST['ASKING_PRICE']);

    if ($action === 'add') {
        $query = "INSERT INTO INVENTORY (VIN, YEAR, Make, Model, TRIM, EXT_COLOR, MILEAGE, ASKING_PRICE)
                  VALUES ('$vin', '$year', '$make', '$model', '$trim', '$ext_color', '$mileage', '$price')";
        $success_msg = "<h3 style='color: green;'>Added $make $model successfully.</h3>";
        $error_msg = "<h3 style='color: red;'>Error adding car: " . $mysqli->error . "</h3>";
    } else {
        $query = "UPDATE INVENTORY SET YEAR='$year', Make='$make', Model='$model', TRIM='$trim', 
                  EXT_COLOR='$ext_color', MILEAGE='$mileage', ASKING_PRICE='$price' WHERE VIN='$vin'";
        $success_msg = "<h3 style='color: green;'>Updated $make $model successfully.</h3>";
        $error_msg = "<h3 style='color: red;'>Error updating car: " . $mysqli->error . "</h3>";
    }

    $feedback_message = $mysqli->query($query) ? $success_msg : $error_msg;
}

$edit_car_data = null;
if ($action === 'edit_form' && isset($_GET['VIN'])) {
    $vin = $mysqli->real_escape_string($_GET['VIN']);
    $result = $mysqli->query("SELECT * FROM INVENTORY WHERE VIN='$vin'");
    if ($result && $result->num_rows > 0) {
        $edit_car_data = $result->fetch_assoc();
        $feedback_message = "<h3 style='color: blue;'>Editing " . htmlspecialchars($edit_car_data['Make']) . " " . htmlspecialchars($edit_car_data['Model']) . "</h3>";
    }
    $action = 'edit';
} else {
    $action = 'add';
}

function get_field_value($data, $field, $default = '') {
    return isset($data[$field]) ? htmlspecialchars($data[$field]) : $default;
}

$inventory_result = $mysqli->query("SELECT * FROM INVENTORY ORDER BY Make, Model");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && isset($_POST['VIN'])) {
    $vin = $mysqli->real_escape_string($_POST['VIN']);
    $target_dir = __DIR__ . '/../uploads/';

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); 
    }

    $filename = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $filename_escaped = $mysqli->real_escape_string($filename);
        
        $check_query = "SELECT ImageFile FROM images WHERE VIN='$vin'";
        $result = $mysqli->query($check_query);
        
        if ($result && $result->num_rows > 0) {
            $update_query = "UPDATE images SET ImageFile='$filename_escaped' WHERE VIN='$vin'";
            $mysqli->query($update_query);
            $feedback_message = "<h3 style='color: green;'>Image updated and overwritten successfully for VIN $vin.</h3>";
            
        } else {
            $insert_query = "INSERT INTO images (VIN, ImageFile) VALUES ('$vin', '$filename_escaped')";
            $mysqli->query($insert_query);
            $feedback_message = "<h3 style='color: green;'>Image uploaded successfully for VIN $vin.</h3>";
        }
        

    } else {
        $feedback_message = "<h3 style='color: red;'>Failed to upload image for VIN $vin.</h3>";
    }
}
/*
if (isset($_GET['VIN'])) {
    $vin = $mysqli->real_escape_string($_GET['VIN']);
    $query = "SELECT * FROM images WHERE VIN='$vin'";
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $image_url = 'uploads/' . htmlspecialchars($row['ImageFile']);
            echo "<img src='$image_url' width='250'> ";
        }
    }
}
*/