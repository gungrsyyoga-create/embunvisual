<?php
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Checking columns...\n";
$q = mysqli_query($conn, "SHOW COLUMNS FROM klien_premium");
$cols = [];
while($r = mysqli_fetch_assoc($q)) { $cols[] = $r['Field']; }

if (!in_array('folder_path', $cols)) {
    echo "Adding folder_path...\n";
    $res = mysqli_query($conn, "ALTER TABLE klien_premium ADD COLUMN folder_path VARCHAR(255) DEFAULT NULL AFTER tipe");
    if ($res) echo "SUCCESS: Column folder_path added.\n";
    else echo "FAIL: " . mysqli_error($conn) . "\n";
} else {
    echo "Column folder_path already exists.\n";
}

if (!in_array('tipe', $cols)) {
    echo "Adding tipe...\n";
    $res = mysqli_query($conn, "ALTER TABLE klien_premium ADD COLUMN tipe ENUM('Basic', 'Premium', 'Exclusive') NOT NULL DEFAULT 'Premium' AFTER password");
    if ($res) echo "SUCCESS: Column tipe added.\n";
    else echo "FAIL: " . mysqli_error($conn) . "\n";
} else {
    echo "Column tipe already exists.\n";
}
?>
