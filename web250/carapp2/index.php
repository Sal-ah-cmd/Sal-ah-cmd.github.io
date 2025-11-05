<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "<b>Error [$errno]</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
    return true;
});

require_once __DIR__ . '/scripts/auth.php';
include __DIR__ . '/scripts/script.php';
include __DIR__ . '/components/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_username'])) {
    $msg = attempt_login($_POST['login_username'], $_POST['login_password']);
    if ($msg === '') {
        header('Location: index.php');
        exit;
    } else {
        $feedback_message = "<h3 style='color:red;'>$msg</h3>";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($feedback_message)) {
    echo "<div style='text-align:center; margin-bottom:20px;'>{$feedback_message}</div>";
}
?>

<hr>

<?php if (!is_logged_in()): ?>
    <div style="max-width:400px; margin:0 auto; background:#fff; padding:20px; border-radius:8px;">
        <h3>View Inventory</h3>
        <form method="POST" action="index.php" style="margin-top:10px;">
            <label for="login_username">Username:</label><br>
            <input type="text" name="login_username" required><br>
            <label for="login_password">Password:</label><br>
            <input type="password" name="login_password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </div>
<?php else: ?>
    <div style="text-align:center; margin-bottom:20px;">
        <strong>Welcome, <?php echo htmlspecialchars(current_user_display()); ?></strong> —
        <a href="index.php?action=logout">Logout</a><br>
        <a href="scripts/run_all_scripts.php"
           onclick="return confirm('WARNING:\\nThis will DROP and RECREATE the inventory and images tables and repopulate them.\\nIt will NOT drop the users table. Are you sure?');">
           Run All Scripts (Danger!)
        </a>
    </div>
<?php endif; ?>

<hr>

<?php if (is_logged_in()): ?>
<div id="car-form">
    <h2><?php echo ($edit_car_data ? 'Edit Existing Car' : 'Add New Car'); ?></h2>
    <form method="POST" action="index.php">
        <input type="hidden" name="action" value="<?php echo ($edit_car_data ? 'edit' : 'add'); ?>">

        <p><label for="VIN">VIN:</label>
        <input type="text" name="VIN" value="<?php echo get_field_value($edit_car_data, 'VIN'); ?>" <?php echo ($edit_car_data ? 'readonly' : 'required'); ?>></p>

        <p><label for="YEAR">Year:</label>
        <input type="number" name="YEAR" value="<?php echo get_field_value($edit_car_data, 'YEAR'); ?>" required></p>

        <p><label for="Make">Make:</label>
        <input type="text" name="Make" value="<?php echo get_field_value($edit_car_data, 'Make'); ?>" required></p>

        <p><label for="Model">Model:</label>
        <input type="text" name="Model" value="<?php echo get_field_value($edit_car_data, 'Model'); ?>" required></p>

        <p><label for="TRIM">Trim:</label>
        <input type="text" name="TRIM" value="<?php echo get_field_value($edit_car_data, 'TRIM'); ?>"></p>

        <p><label for="EXT_COLOR">Exterior Color:</label>
        <input type="text" name="EXT_COLOR" value="<?php echo get_field_value($edit_car_data, 'EXT_COLOR'); ?>"></p>

        <p><label for="INT_COLOR">Interior Color:</label>
        <input type="text" name="INT_COLOR" value="<?php echo get_field_value($edit_car_data, 'INT_COLOR'); ?>"></p>

        <p><label for="MILEAGE">Mileage:</label>
        <input type="number" name="MILEAGE" value="<?php echo get_field_value($edit_car_data, 'MILEAGE'); ?>"></p>

        <p><label for="ASKING_PRICE">Asking Price:</label>
        <input type="number" step="0.01" name="ASKING_PRICE" value="<?php echo get_field_value($edit_car_data, 'ASKING_PRICE'); ?>" required></p>

        <p><label for="SALE_PRICE">Sale Price:</label>
        <input type="number" step="0.01" name="SALE_PRICE" value="<?php echo get_field_value($edit_car_data, 'SALE_PRICE'); ?>"></p>

        <p><label for="PURCHASE_PRICE">Purchase Price:</label>
        <input type="number" step="0.01" name="PURCHASE_PRICE" value="<?php echo get_field_value($edit_car_data, 'PURCHASE_PRICE'); ?>"></p>

        <p><label for="TRANSMISSION">Transmission:</label>
        <input type="text" name="TRANSMISSION" value="<?php echo get_field_value($edit_car_data, 'TRANSMISSION'); ?>"></p>

        <p><label for="PURCHASE_DATE">Purchase Date:</label>
        <input type="date" name="PURCHASE_DATE" value="<?php echo get_field_value($edit_car_data, 'PURCHASE_DATE'); ?>"></p>

        <p><label for="SALE_DATE">Sale Date:</label>
        <input type="date" name="SALE_DATE" value="<?php echo get_field_value($edit_car_data, 'SALE_DATE'); ?>"></p>

        <p style="text-align:center; margin-top:20px;">
            <input type="submit" value="<?php echo ($edit_car_data ? 'Save Changes' : 'Add Car to Inventory'); ?>">
            <?php if ($edit_car_data): ?>
                <a href="index.php" style="margin-left:15px;">Cancel Edit</a>
            <?php endif; ?>
        </p>
    </form>
</div>
<?php else: ?>
<div id="car-form" style="text-align:center;">
    <p>Log in to add or edit cars.</p>
</div>
<?php endif; ?>

<hr>

<h2>Current Inventory</h2>
<?php if ($inventory_result): ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Year</th>
        <th>Make</th>
        <th>Model</th>
        <th>Mileage</th>
        <th>Asking Price</th>
        <th>Sale Price</th>
        <th>Purchase Price</th>
        <th>Transmission</th>
        <th>Purchase Date</th>
        <th>Sale Date</th>
        <th>Images</th>
        <th>Actions</th>
    </tr>

    <?php if ($inventory_result->num_rows > 0): while ($car = $inventory_result->fetch_assoc()): 
        $vin = $car['VIN'];
        $vin_encoded = urlencode($vin);
    ?>
    <tr>
        <td><?php echo htmlspecialchars($car['YEAR'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($car['Make'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($car['Model'] ?? ''); ?></td>
        <td><?php echo number_format((int)($car['MILEAGE'] ?? 0)); ?></td>
        <td>$<?php echo number_format((float)($car['ASKING_PRICE'] ?? 0), 2); ?></td>
        <td>$<?php echo number_format((float)($car['SALE_PRICE'] ?? 0), 2); ?></td>
        <td>$<?php echo number_format((float)($car['PURCHASE_PRICE'] ?? 0), 2); ?></td>
        <td><?php echo htmlspecialchars($car['TRANSMISSION'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($car['PURCHASE_DATE'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($car['SALE_DATE'] ?? ''); ?></td>
        <td>
            <?php
            $images_result = $mysqli->query("SELECT * FROM images WHERE VIN='" . $mysqli->real_escape_string($vin) . "'");
            if ($images_result && $images_result->num_rows > 0):
                while ($img = $images_result->fetch_assoc()):
                    $img_url = 'uploads/' . htmlspecialchars($img['ImageFile']);
            ?>
                <div style="margin-top:5px;">
                    <img src="<?php echo $img_url; ?>" width="100">
                </div>
            <?php endwhile; endif; ?>
        </td>

        <td>
            <?php if (is_logged_in()): ?>
            <a href="index.php?action=edit_form&VIN=<?php echo $vin_encoded; ?>">Edit</a> |
            <a href="index.php?action=delete&VIN=<?php echo $vin_encoded; ?>"
               onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($car['Make'] . ' ' . $car['Model']); ?>?');">Delete</a>

            <form method="post" enctype="multipart/form-data" style="margin-top:5px;" id="uploadForm-<?php echo $vin; ?>">
                <label for="file-upload-<?php echo $vin; ?>" class="custom-file-upload">Upload</label>
                <input id="file-upload-<?php echo $vin; ?>" type="file" name="image" style="display:none;"
                       onchange="document.getElementById('uploadForm-<?php echo $vin; ?>').submit();" required>
                <input type="hidden" name="VIN" value="<?php echo htmlspecialchars($vin); ?>">
            </form>
            <?php else: ?>
                <span style="color:gray;">Login to edit</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="12" style="text-align:center;">No cars found in inventory.</td></tr>
    <?php endif; ?>
</table>

<?php else: ?>
<p style="color:red;">Error retrieving inventory: <?php echo $mysqli->error; ?></p>
<?php endif; ?>

<?php 
include 'components/footer.php';
if (isset($mysqli)) $mysqli->close();
?>
