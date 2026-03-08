<?php
include 'config.php';
$res = mysqli_query($conn, "SELECT id, nama_tema, slug_demo FROM katalog_tema");
while($row = mysqli_fetch_assoc($res)) {
    echo "ID: " . $row['id'] . " | Nama: " . $row['nama_tema'] . " | Slug: " . $row['slug_demo'] . "\n";
}
?>
