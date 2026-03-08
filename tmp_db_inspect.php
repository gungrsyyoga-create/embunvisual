<?php
include 'config.php';
$tables = ['pesanan', 'klien_premium', 'admin_users', 'katalog_tema', 'tamu_undangan'];
foreach ($tables as $table) {
    echo "--- $table ---\n";
    $q = mysqli_query($conn, "DESCRIBE $table");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
    echo "\n";
}
?>
