<?php
include __DIR__ . '/../config_db.php';

$create_table_query = "
    CREATE TABLE IF NOT EXISTS inventory 
    ( 
        VIN varchar(17) PRIMARY KEY, 
        YEAR INT, 
        Make varchar(50), 
        Model varchar(100), 
        TRIM varchar(50), 
        EXT_COLOR varchar(50), 
        INT_COLOR varchar(50), 
        ASKING_PRICE DECIMAL(10,2), 
        SALE_PRICE DECIMAL(10,2), 
        PURCHASE_PRICE DECIMAL(10,2), 
        MILEAGE INT, 
        TRANSMISSION varchar(50), 
        PURCHASE_DATE DATE, 
        SALE_DATE DATE
    )
";

if ($mysqli->query($create_table_query)) {
    echo "Table 'inventory' created or already exists.";
} else {
    echo "Error creating table: " . $mysqli->error;
}
?>
