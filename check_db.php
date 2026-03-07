<?php
include 'config.php';
$response = [];

$tables = ['pesanan', 'request_custom', 'admin_users'];
foreach($tables as $t) {
    if($q = mysqli_query($conn, "DESCRIBE $t")) {
        $cols = [];
        while($r = mysqli_fetch_assoc($q)) {
            $cols[] = $r;
        }
        $response[$t] = $cols;
    }
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>
