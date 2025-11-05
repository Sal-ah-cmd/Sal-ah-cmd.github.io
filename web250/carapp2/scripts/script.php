<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "<b>Error [$errno]</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
    return true;
});

require_once __DIR__ . '/auth.php';
include __DIR__ . '/../config_db.php';

if (!$mysqli) die('DB not connected properly');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'logout') {
    logout();
    header('Location: index.php');
    exit;
}

if ($action === 'delete' && isset($_GET['VIN'])) {
    if (!is_logged_in()) {
        $feedback_message = "<h3 style='color:red;'>You must be logged in to delete cars.</h3>";
    } else {
        $vin = $mysqli->real_escape_string($_GET['VIN']);
        $imgs = $mysqli->query("SELECT ImageFile FROM images WHERE VIN='$vin'");
        if ($imgs) {
            while ($r = $imgs->fetch_assoc()) {
                $path = __DIR__ . '/../uploads/' . $r['ImageFile'];
                if (is_file($path)) @unlink($path);
            }
        }
        $mysqli->query("DELETE FROM images WHERE VIN='$vin'");
        $query = "DELETE FROM inventory WHERE VIN='$vin'";
        $feedback_message = $mysqli->query($query)
            ? "<h3 style='color: green;'>Success! Vehicle with VIN $vin deleted.</h3>"
            : "<h3 style='color: red;'>Error deleting car: " . $mysqli->error . "</h3>";
    }
    $action = '';
}

if (($action === 'add' || $action === 'edit') && isset($_POST['VIN'])) {
    if (!is_logged_in()) {
        $feedback_message = "<h3 style='color:red;'>You must be logged in to add or edit cars.</h3>";
    } else {
        $vin = $mysqli->real_escape_string(trim($_POST['VIN']));
        $year = (int)($_POST['YEAR'] ?? 0);
        $make = $mysqli->real_escape_string(trim($_POST['Make'] ?? ''));
        $model = $mysqli->real_escape_string(trim($_POST['Model'] ?? ''));
        $trim = $mysqli->real_escape_string(trim($_POST['TRIM'] ?? ''));
        $ext_color = $mysqli->real_escape_string(trim($_POST['EXT_COLOR'] ?? ''));
        $int_color = $mysqli->real_escape_string(trim($_POST['INT_COLOR'] ?? ''));
        $mileage = (int)($_POST['MILEAGE'] ?? 0);
        $asking_price = (float)($_POST['ASKING_PRICE'] ?? 0);
        $sale_price = (float)($_POST['SALE_PRICE'] ?? 0);
        $purchase_price = (float)($_POST['PURCHASE_PRICE'] ?? 0);
        $trans = $mysqli->real_escape_string($_POST['TRANSMISSION'] ?? '');
        $purchase_date = !empty($_POST['PURCHASE_DATE']) ? $mysqli->real_escape_string($_POST['PURCHASE_DATE']) : null;
        $sale_date = !empty($_POST['SALE_DATE']) ? $mysqli->real_escape_string($_POST['SALE_DATE']) : null;

        if ($action === 'add') {
            $query = "INSERT INTO inventory (VIN, YEAR, Make, Model, TRIM, EXT_COLOR, INT_COLOR, MILEAGE, ASKING_PRICE, SALE_PRICE, PURCHASE_PRICE, TRANSMISSION, PURCHASE_DATE, SALE_DATE)
                      VALUES ('$vin', $year, '$make', '$model', '$trim', '$ext_color', '$int_color', $mileage, $asking_price, $sale_price, $purchase_price, '$trans', " .
                      ($purchase_date ? "'$purchase_date'" : "NULL") . ", " . ($sale_date ? "'$sale_date'" : "NULL") . ")";
            $success_msg = "<h3 style='color: green;'>Added $make $model successfully.</h3>";
            $error_msg = "<h3 style='color: red;'>Error adding car: " . $mysqli->error . "</h3>";
        } else {
            $query = "UPDATE inventory SET YEAR=$year, Make='$make', Model='$model', TRIM='$trim', EXT_COLOR='$ext_color', INT_COLOR='$int_color',
                      MILEAGE=$mileage, ASKING_PRICE=$asking_price, SALE_PRICE=$sale_price, PURCHASE_PRICE=$purchase_price, TRANSMISSION='$trans',
                      PURCHASE_DATE=" . ($purchase_date ? "'$purchase_date'" : "NULL") . ", SALE_DATE=" . ($sale_date ? "'$sale_date'" : "NULL") .
                      " WHERE VIN='$vin'";
            $success_msg = "<h3 style='color: green;'>Updated $make $model successfully.</h3>";
            $error_msg = "<h3 style='color: red;'>Error updating car: " . $mysqli->error . "</h3>";
        }

        $feedback_message = $mysqli->query($query) ? $success_msg : $error_msg;
    }
}

$edit_car_data = null;
if ($action === 'edit_form' && isset($_GET['VIN'])) {
    $vin = $mysqli->real_escape_string($_GET['VIN']);
    $result = $mysqli->query("SELECT * FROM inventory WHERE VIN='$vin'");
    if ($result && $result->num_rows > 0) {
        $edit_car_data = $result->fetch_assoc();
        $feedback_message = "<h3 style='color: blue;'>Editing " . htmlspecialchars($edit_car_data['Make']) . " " . htmlspecialchars($edit_car_data['Model']) . "</h3>";
    }
    $action = 'edit';
} else {
    if (!isset($action) || ($action !== 'edit' && $action !== 'add')) $action = 'add';
}

function get_field_value($data, $field, $default = '') {
    return isset($data[$field]) ? htmlspecialchars($data[$field]) : $default;
}

$per_page = 100;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;
$total_result = $mysqli->query("SELECT COUNT(*) as c FROM inventory");
$total_rows = ($total_result ? intval($total_result->fetch_assoc()['c']) : 0);
$inventory_result = $mysqli->query("SELECT * FROM inventory ORDER BY Make, Model LIMIT $per_page OFFSET $offset");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && isset($_POST['VIN'])) {
    if (!is_logged_in()) {
        $feedback_message = "<h3 style='color:red;'>You must be logged in to upload images.</h3>";
    } else {
        $vin = $mysqli->real_escape_string($_POST['VIN']);
        $target_dir = __DIR__ . '/../uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

        $filename = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES["image"]["name"]));
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $filename_escaped = $mysqli->real_escape_string($filename);
            $check_query = "SELECT ImageFile FROM images WHERE VIN='$vin'";
            $result = $mysqli->query($check_query);

            if ($result && $result->num_rows > 0) {
                $mysqli->query("UPDATE images SET ImageFile='$filename_escaped' WHERE VIN='$vin'");
                $feedback_message = "<h3 style='color: green;'>Image updated successfully for VIN $vin.</h3>";
            } else {
                $mysqli->query("INSERT INTO images (VIN, ImageFile) VALUES ('$vin', '$filename_escaped')");
                $feedback_message = "<h3 style='color: green;'>Image uploaded successfully for VIN $vin.</h3>";
            }
        } else {
            $feedback_message = "<h3 style='color: red;'>Failed to upload image for VIN $vin.</h3>";
        }
    }
}
?>
