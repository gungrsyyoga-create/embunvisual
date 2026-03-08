<?php
include 'config.php';
$cols = [];
$q = mysqli_query($conn, "SHOW COLUMNS FROM klien_premium");
if ($q) {
    while($r = mysqli_fetch_assoc($q)) {
        $cols[] = $r['Field'];
    }
    echo "Columns: " . implode(", ", $cols) . "\n";
} else {
    echo "Query failed.\n";
}

// Also check for specific columns we need
if (!in_array('tipe', $cols)) echo "MISSING: tipe\n";
if (!in_array('folder_path', $cols)) echo "MISSING: folder_path\n";
?>
