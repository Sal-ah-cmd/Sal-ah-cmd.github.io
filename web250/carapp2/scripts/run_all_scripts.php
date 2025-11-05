<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../config_db.php';
require_once __DIR__ . '/auth.php';

$header_path = __DIR__ . '/../components/header.php';
if (file_exists($header_path)) {
    echo "<base href='../'>";
    include $header_path;
}

if (!is_logged_in()) {
    die("<h3 style='color:red;'>You must be logged in to run this script.</h3>");
}

echo "<h2>Well, you asked for it...</h2><br>";

$queries = [
    "DROP TABLE IF EXISTS images" => "Dropping images table...",
    "DROP TABLE IF EXISTS inventory" => "Dropping inventory table..."
];

foreach ($queries as $query => $desc) {
    echo "<p>$desc</p>";
    if ($mysqli->query($query)) {
        echo "<p style='color:green;'>Success.</p>";
    } else {
        echo "<p style='color:red;'>Error: {$mysqli->error}</p>";
    }
}

echo "<p>Creating inventory table...</p>";
$create_inventory = "
CREATE TABLE inventory (
    VIN VARCHAR(17) PRIMARY KEY,
    YEAR INT,
    Make VARCHAR(50),
    Model VARCHAR(50),
    TRIM VARCHAR(50),
    EXT_COLOR VARCHAR(50),
    INT_COLOR VARCHAR(50),
    MILEAGE INT,
    ASKING_PRICE DECIMAL(10,2),
    SALE_PRICE DECIMAL(10,2),
    PURCHASE_PRICE DECIMAL(10,2),
    TRANSMISSION VARCHAR(50),
    PURCHASE_DATE DATE,
    SALE_DATE DATE
)";
if ($mysqli->query($create_inventory)) {
    echo "<p style='color:green;'>Success.</p>";
} else {
    echo "<p style='color:red;'>Error creating inventory table: {$mysqli->error}</p>";
}

echo "<p>Creating images table...</p>";
$create_images = "
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    VIN VARCHAR(17),
    ImageFile VARCHAR(255),
    FOREIGN KEY (VIN) REFERENCES inventory(VIN) ON DELETE CASCADE
)";
if ($mysqli->query($create_images)) {
    echo "<p style='color:green;'>Success.</p>";
} else {
    echo "<p style='color:red;'>Error creating images table: {$mysqli->error}</p>";
}

echo "<p>Repopulating tables with sample data...</p>";
include __DIR__ . '/import_cars.php';

echo "<div style='margin-top:20px;'>
        <a href='../index.php' class='button' 
           style='padding:10px 20px; background:#FF8C00; color:white; border:none; border-radius:6px; text-decoration:none;'>
           Return
        </a>
      </div>";

include __DIR__ . '/../components/footer.php';
?>
