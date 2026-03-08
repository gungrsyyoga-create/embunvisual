<?php
include 'config.php';
$pid = 22;
$q = mysqli_query($conn, "SELECT p.id, p.nama_pemesan, k.slug_demo FROM pesanan p JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pid'");
$d = mysqli_fetch_assoc($q);
if(!$d) die("Error: Pesanan not found");

$clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $d['nama_pemesan']));
// Remove redundant hyphens
$clean_name = preg_replace('/-+/', '-', $clean_name);
$clean_name = trim($clean_name, '-');

mysqli_query($conn, "UPDATE klien_premium SET tipe='Exclusive' WHERE pesanan_id='$pid'");
$target_dir = "undangan/exclusive/$clean_name";
if(!is_dir("undangan")) mkdir("undangan");
if(!is_dir("undangan/exclusive")) mkdir("undangan/exclusive");
if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);

$theme_file = "tema/" . $d['slug_demo'];
$content = file_get_contents($theme_file);
$content = str_replace('dirname(__DIR__) . "/config.php"', 'dirname(dirname(dirname(__DIR__))) . "/config.php"', $content);
$content = str_replace('../api_rsvp.php', '../../api_rsvp.php', $content);
$content = str_replace('../config.php', '../../config.php', $content);
$content = preg_replace('/\$pesanan_id = \d+;/', "\$pesanan_id = $pid;", $content);

file_put_contents("$target_dir/index.php", $content);
mysqli_query($conn, "UPDATE klien_premium SET folder_path='$target_dir' WHERE pesanan_id='$pid'");
echo "DONE: $target_dir";
?>
