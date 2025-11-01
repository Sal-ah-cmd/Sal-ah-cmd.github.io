<?php
include 'scripts/script.php'; 
include 'components/header.php'; 
?>
    
    <div style="text-align: center; margin-bottom: 20px;">
        <?php echo $feedback_message; ?>
    </div>

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
            
            <p style="text-align: center; margin-top: 20px;">
                <input type="submit" value="<?php echo ($edit_car_data ? 'Save Changes' : 'Add Car to Inventory'); ?>">
                <?php if ($edit_car_data): ?>
                    <a href="index.php" style="margin-left: 15px;">Cancel Edit</a>
                <?php endif; ?>
            </p>
        </form>
    </div>

    <hr>
    
    <h2>Current Inventory</h2>
    
    <?php
    if ($inventory_result):
    ?>
        <table>
            <tr>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
                <th>Mileage</th>
                <th>Asking Price</th>
                <th>Actions</th>
            </tr>
            
            <?php if ($inventory_result->num_rows > 0): 
                while ($car = $inventory_result->fetch_assoc()):
                    $vin_encoded = urlencode($car['VIN']);
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($car['YEAR']); ?></td>
                    <td><?php echo htmlspecialchars($car['Make']); ?></td>
                    <td><?php echo htmlspecialchars($car['Model']); ?></td>
                    <td><?php echo number_format($car['MILEAGE']); ?></td>
                    <td>$<?php echo number_format($car['ASKING_PRICE'], 2); ?></td>
                    <td>
                        <a href="index.php?action=edit_form&VIN=<?php echo $vin_encoded; ?>" class="action-link edit-link">Edit</a> |
                        <a href="index.php?action=delete&VIN=<?php echo $vin_encoded; ?>" 
                           class="action-link delete-link" 
                           onclick="return confirm('Are you sure you want to delete the <?php echo htmlspecialchars($car['Make'] . ' ' . $car['Model']); ?>?');">Delete</a> |
                         <a href="AddImage.php?VIN=<?php echo $vin_encoded; ?>">Add Image</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align: center;">No cars found in the inventory.</td></tr>
            <?php endif; ?>
        </table>
        
        <?php $inventory_result->close(); ?>

    <?php else: ?>
        <p style="color: red;">Error retrieving inventory: <?php echo $mysqli->error; ?></p>
    <?php endif; ?>

<?php 
include 'components/footer.php'; 

if (isset($mysqli)) {
    $mysqli->close();
}
?>