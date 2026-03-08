<?php
include 'config.php';

echo "Updating database...\n";

// Add tipe column if not exists
$res1 = mysqli_query($conn, "ALTER TABLE klien_premium ADD COLUMN tipe ENUM('Basic', 'Premium', 'Exclusive') NOT NULL DEFAULT 'Premium' AFTER password");
if ($res1) echo "Column 'tipe' added/updated.\n";
else echo "Note: 'tipe' might already exist or failed: " . mysqli_error($conn) . "\n";

// Add folder_path column if not exists
$res2 = mysqli_query($conn, "ALTER TABLE klien_premium ADD COLUMN folder_path VARCHAR(255) DEFAULT NULL AFTER tipe");
if ($res2) echo "Column 'folder_path' added.\n";
else echo "Note: 'folder_path' might already exist or failed: " . mysqli_error($conn) . "\n";

?>
