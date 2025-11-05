<?php
echo "<p>Current file: " . __FILE__ . "</p>";
echo "<p>Trying ../config_db.php ...</p>";

if (file_exists("../config_db.php")) {
    echo "<p style='color:green;'>✅ Found!</p>";
} else {
    echo "<p style='color:red;'>❌ Not found!</p>";
}
?>
