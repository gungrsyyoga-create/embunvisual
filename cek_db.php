<?php
include 'config.php';
$res = mysqli_query($conn, "SELECT id, invoice, status_pembayaran, status_pengerjaan FROM pesanan ORDER BY id DESC LIMIT 5");
while($row = mysqli_fetch_assoc($res)){
    echo json_encode($row) . "\n";
}
?>
