<?php
include 'config.php';

echo "Testing insert to audit_logs...\n";
$res = mysqli_query($conn, "INSERT INTO audit_logs (admin_id, action_type, target_id, keterangan) VALUES (1, 'Test', 'TestTarget', 'TestKet')");

if (!$res) {
    echo "Error inserting: " . mysqli_error($conn) . "\n";
} else {
    echo "Insert success.\n";
}

echo "\nData in audit_logs:\n";
$q = mysqli_query($conn, "SELECT * FROM audit_logs");
while($r = mysqli_fetch_assoc($q)) {
    print_r($r);
}

// Cek struktur admin_users
echo "\nAdmin users:\n";
$q2 = mysqli_query($conn, "SELECT * FROM admin_users");
while($r = mysqli_fetch_assoc($q2)) {
    echo $r['id'] . " - " . $r['username'] . " - " . $r['role'] . "\n";
}
?>
