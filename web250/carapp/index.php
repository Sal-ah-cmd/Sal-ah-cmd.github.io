<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "<b>Error [$errno]</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
    return true;
});

include 'scripts/script.php';
include 'components/header.php';
/*
echo "<pre>DEBUG POST DATA:\n";
print_r($_POST);
echo "</pre>";
*/
if (isset($feedback_message)) {
    echo "<div style='text-align:center; margin-bottom:20px;'>{$feedback_message}</div>";
}
?>

<hr>

<div id="car-form">
    <h2><?php echo ($edit_car_data ? 'Edit Existing Car' : 'Add New Car'); ?></h2>
    
    <form method="POST" action="index.php">
        <input type="hidden" name="action" value="<?php echo ($edit_car_data ? 'edit' : 'add'); ?>">
        
        <p>
            <label for="VIN">VIN:</label>
            <input type="text" name="VIN" value="<?php echo get_field_value($edit_car_data, 'VIN'); ?>" 
                   <?php echo ($edit_car_data ? 'readonly' : 'required'); ?>>
        </p>
        
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

        <p><label for="MILEAGE">Mileage:</label>
        <input type="number" name="MILEAGE" value="<?php echo get_field_value($edit_car_data, 'MILEAGE'); ?>"></p>
        
        <p><label for="ASKING_PRICE">Asking Price:</label>
        <input type="number" step="0.01" name="ASKING_PRICE" value="<?php echo get_field_value($edit_car_data, 'ASKING_PRICE'); ?>" required></p>
        
        <p style="text-align:center; margin-top:20px;">
            <input type="submit" value="<?php echo ($edit_car_data ? 'Save Changes' : 'Add Car to Inventory'); ?>">
            <?php if ($edit_car_data): ?>
                <a href="index.php" style="margin-left:15px;">Cancel Edit</a>
            <?php endif; ?>
        </p>
    </form>
</div>

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
        <th>Actions</th>
        <th>Images</th> 
    </tr>

    <?php if ($inventory_result->num_rows > 0): 
        while ($car = $inventory_result->fetch_assoc()):
            $vin = $car['VIN'];
            $vin_encoded = urlencode($vin);
    ?>
    <tr>
        <td><?php echo htmlspecialchars($car['YEAR']); ?></td>
        <td><?php echo htmlspecialchars($car['Make']); ?></td>
        <td><?php echo htmlspecialchars($car['Model']); ?></td>
        <td><?php echo number_format($car['MILEAGE']); ?></td>
        <td>$<?php echo number_format($car['ASKING_PRICE'], 2); ?></td>
        
        <td>
            <a href="index.php?action=edit_form&VIN=<?php echo $vin_encoded; ?>">Edit</a> |
            <a href="index.php?action=delete&VIN=<?php echo $vin_encoded; ?>" 
               onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($car['Make'] . ' ' . $car['Model']); ?>?');">Delete</a>
            
            <form method="post" enctype="multipart/form-data" style="margin-top:5px;" id="uploadForm-<?php echo $vin; ?>">
                <label for="file-upload-<?php echo $vin; ?>" class="custom-file-upload">Upload</label>
                
                <input id="file-upload-<?php echo $vin; ?>" type="file" name="image" style="display: none;" onchange="document.getElementById('uploadForm-<?php echo $vin; ?>').submit();" required>
                
                <input type="hidden" name="VIN" value="<?php echo htmlspecialchars($vin); ?>">
            </form>
        </td>

        <td>
            <?php
            $images_result = $mysqli->query("SELECT * FROM images WHERE VIN='$vin'");
            if ($images_result && $images_result->num_rows > 0):
                while ($img = $images_result->fetch_assoc()):
                    $img_url = 'uploads/' . htmlspecialchars($img['ImageFile']);
            ?>
                <div style="margin-top:5px;">
                    <img src="<?php echo $img_url; ?>" width="100">
                </div>
            <?php endwhile; endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No cars found in inventory.</td></tr>
    <?php endif; ?>
</table>
<?php else: ?>
<p style="color:red;">Error retrieving inventory: <?php echo $mysqli->error; ?></p>
<?php endif; ?>

<?php 
include 'components/footer.php'; 
if (isset($mysqli)) $mysqli->close();
?>