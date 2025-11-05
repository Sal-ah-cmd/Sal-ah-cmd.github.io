<?php
include __DIR__ . '/../config_db.php';

$drop_table_query = "DROP TABLE IF EXISTS users";
$mysqli->query($drop_table_query);

$create_table_query = "
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )
";
$mysqli->query($create_table_query);

$users = [
    ['admin', 'admin'],
    ['salami', 'sal123'],
    ['LetMeIn', '123']
];

foreach ($users as $user) {
    [$username, $password] = $user;
    $insert_query = "
        INSERT INTO users (username, password)
        VALUES ('$username', '$password')
    ";
    $mysqli->query($insert_query);
}

echo "Users table setup complete.";
?>
