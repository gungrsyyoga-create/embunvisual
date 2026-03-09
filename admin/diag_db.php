<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';
$res = mysqli_query($conn, "DESCRIBE klien_premium");
echo "<pre>";
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
echo "</pre>";
