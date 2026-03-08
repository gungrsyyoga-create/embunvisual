<?php
include 'config.php';

// Mencontohkan untuk pesanan ID 22 (Yoga & Ayu)
$pid = 22;

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT p.id, p.nama_pemesan, k.slug_demo FROM pesanan p JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pid'");
$d = mysqli_fetch_assoc($q);

if(!$d) {
    die("Pesanan ID $pid tidak ditemukan. Silakan cek database.");
}

echo "Memulai demonstrasi untuk: " . $d['nama_pemesan'] . "\n";
echo "Tema: " . $d['slug_demo'] . "\n";

// Logika yang sama dengan di admin.php (G2. Generate Folder Action)
$clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $d['nama_pemesan']));

// Cek atau set Tier (misal kita set Exclusive untuk contoh ini)
mysqli_query($conn, "UPDATE klien_premium SET tipe='Exclusive' WHERE pesanan_id='$pid'");
$tier = 'exclusive';

$target_dir = "undangan/$tier/$clean_name";
echo "Target Folder: $target_dir\n";

if(!is_dir("undangan")) mkdir("undangan");
if(!is_dir("undangan/$tier")) mkdir("undangan/$tier");
if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);

$theme_file = "tema/" . $d['slug_demo'];
if(file_exists($theme_file)) {
    $content = file_get_contents($theme_file);
    
    // Sesuaikan path agar tetap jalan di folder sub-materi
    $content = str_replace('dirname(__DIR__) . "/config.php"', 'dirname(dirname(dirname(__DIR__))) . "/config.php"', $content);
    $content = str_replace('../api_rsvp.php', '../../api_rsvp.php', $content);
    $content = str_replace('../config.php', '../../config.php', $content);
    
    // Injek pesanan_id yang sesuai
    $content = preg_replace('/\$pesanan_id = \d+;/', "\$pesanan_id = $pid;", $content);
    
    if(file_put_contents("$target_dir/index.php", $content)) {
        echo "BERHASIL: File index.php dibuat di $target_dir/index.php\n";
        
        // Update database folder_path
        mysqli_query($conn, "UPDATE klien_premium SET folder_path='$target_dir' WHERE pesanan_id='$pid'");
        echo "Database diperbarui dengan path folder.\n";
    } else {
        echo "GAGAL: Tidak bisa menulis file.\n";
    }
} else {
    echo "GAGAL: File tema tidak ditemukan di $theme_file\n";
}
?>
