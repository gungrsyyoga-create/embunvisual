<?php
include 'config.php';
// Any empty status_pembayaran gets set to 'Lunas' because they were previously 'Selesai Dikerjakan'
$q = "UPDATE pesanan SET status_pembayaran='Lunas' WHERE status_pembayaran=''";
if(mysqli_query($conn, $q)){
    echo "SUCCESS: Updated " . mysqli_affected_rows($conn) . " rows.";
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>
