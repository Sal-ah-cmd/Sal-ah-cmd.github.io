<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../config_db.php';
require_once __DIR__ . '/auth.php';

if (!is_logged_in()) {
    die("<h3 style='color:red;'>You must be logged in to import data.</h3>");
}

$json_file = __DIR__ . '/cars_sample.json';
$images_dir = __DIR__ . '/../images';

if (!file_exists($json_file)) {
    die("<h3 style='color:red;'>Error: cars_sample.json not found in scripts/ folder.</h3>");
}

$json_data = file_get_contents($json_file);
$cars = json_decode($json_data, true);

if (!$cars || !is_array($cars)) {
    die("<h3 style='color:red;'>Error: Invalid JSON format in cars_sample.json</h3>");
}

echo "<h3 style='color:blue;'>Starting import...</h3>";

$count = 0;
$missing_images = [];

foreach ($cars as $car) {
    $VIN = $mysqli->real_escape_string($car['VIN']);
    $YEAR = (int)$car['YEAR'];
    $Make = $mysqli->real_escape_string($car['Make']);
    $Model = $mysqli->real_escape_string($car['Model']);
    $TRIM = $mysqli->real_escape_string($car['TRIM']);
    $EXT_COLOR = $mysqli->real_escape_string($car['EXT_COLOR']);
    $INT_COLOR = $mysqli->real_escape_string($car['INT_COLOR']);
    $ASKING_PRICE = (float)$car['ASKING_PRICE'];
    $SALE_PRICE = (float)$car['SALE_PRICE'];
    $PURCHASE_PRICE = (float)$car['PURCHASE_PRICE'];
    $MILEAGE = (int)$car['MILEAGE'];
    $TRANSMISSION = $mysqli->real_escape_string($car['TRANSMISSION']);
    $PURCHASE_DATE = !empty($car['PURCHASE_DATE']) ? "'".$mysqli->real_escape_string($car['PURCHASE_DATE'])."'" : "NULL";
    $SALE_DATE = !empty($car['SALE_DATE']) ? "'".$mysqli->real_escape_string($car['SALE_DATE'])."'" : "NULL";
    $ImageFile = $mysqli->real_escape_string($car['ImageFile']);

    $query = "
        INSERT INTO inventory 
        (VIN, YEAR, Make, Model, TRIM, EXT_COLOR, INT_COLOR, ASKING_PRICE, SALE_PRICE, PURCHASE_PRICE, MILEAGE, TRANSMISSION, PURCHASE_DATE, SALE_DATE)
        VALUES
        ('$VIN', $YEAR, '$Make', '$Model', '$TRIM', '$EXT_COLOR', '$INT_COLOR', $ASKING_PRICE, $SALE_PRICE, $PURCHASE_PRICE, $MILEAGE, '$TRANSMISSION', $PURCHASE_DATE, $SALE_DATE)
        ON DUPLICATE KEY UPDATE
        YEAR=$YEAR, Make='$Make', Model='$Model', TRIM='$TRIM', EXT_COLOR='$EXT_COLOR', INT_COLOR='$INT_COLOR',
        ASKING_PRICE=$ASKING_PRICE, SALE_PRICE=$SALE_PRICE, PURCHASE_PRICE=$PURCHASE_PRICE,
        MILEAGE=$MILEAGE, TRANSMISSION='$TRANSMISSION', PURCHASE_DATE=$PURCHASE_DATE, SALE_DATE=$SALE_DATE
    ";
    $mysqli->query($query);

    $image_path = $images_dir . '/' . $ImageFile;
    if (file_exists($image_path)) {
        $img_query = "
            INSERT INTO images (VIN, ImageFile)
            VALUES ('$VIN', '$ImageFile')
            ON DUPLICATE KEY UPDATE ImageFile='$ImageFile'
        ";
        $mysqli->query($img_query);
    } else {
        $missing_images[] = $ImageFile;
    }

    $count++;
}

echo "<h3 style='color:green;'>Import complete! Imported or updated $count cars successfully.</h3>";

if (!empty($missing_images)) {
    echo "<h4 style='color:orange;'>Warning: The following image files were not found in /images/:</h4><ul>";
    foreach (array_unique($missing_images) as $img) {
        echo "<li>$img</li>";
    }
    echo "</ul>";
}
?>
