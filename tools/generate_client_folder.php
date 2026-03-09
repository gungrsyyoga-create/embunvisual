<?php
/**
 * tools/generate_client_folder.php
 * Script untuk auto-generate folder klien dengan template sesuai tier
 */

if (!isset($_POST['action']) || $_POST['action'] !== 'generate_folder') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid action']));
}

include '../config.php';

$pesanan_id = (int)($_POST['pesanan_id'] ?? 0);
$tier = $_POST['tier'] ?? 'basic';
$create_index = (bool)($_POST['create_index'] ?? true);

// Ambil data pesanan
$q_pesanan = mysqli_query($conn, "SELECT p.*, k.slug_demo FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pesanan_id'");

if ($q_pesanan && mysqli_num_rows($q_pesanan) > 0) {
    $pesanan_data = mysqli_fetch_assoc($q_pesanan);
    $nama_klien = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $pesanan_data['nama_pemesan']));
    
    // Mapping tier ke template default
    $template_map = [
        'basic' => 'tema_basic_simple.php',
        'premium' => 'tema_premium_elegant.php',
        'exclusive' => 'tema_exclusive_vip.php'
    ];
    
    $template_file = $template_map[$tier] ?? 'tema_basic_simple.php';
    
    // Buat struktur folder
    $base_dir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'undangan';
    $tier_dir = $base_dir . DIRECTORY_SEPARATOR . $tier;
    $client_dir = $tier_dir . DIRECTORY_SEPARATOR . $nama_klien;
    
    // Create directories
    if (!is_dir($base_dir)) mkdir($base_dir, 0755, true);
    if (!is_dir($tier_dir)) mkdir($tier_dir, 0755, true);
    if (!is_dir($client_dir)) mkdir($client_dir, 0755, true);
    
    // Copy template jika diminta
    if ($create_index) {
        $template_source = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'tema' . DIRECTORY_SEPARATOR . $template_file;
        $index_dest = $client_dir . DIRECTORY_SEPARATOR . 'index.php';
        
        if (file_exists($template_source)) {
            copy($template_source, $index_dest);
        } else {
            // Jika template tidak ditemukan, buat index.php minimal
            $minimal_code = "<?php
\$pesanan_id = " . $pesanan_id . ";
session_start();
include dirname(dirname(dirname(__FILE__))) . '/config.php';
// Template untuk " . ucfirst($tier) . " tier akan diisi di sini
?>";
            file_put_contents($index_dest, $minimal_code);
        }
    }
    
    // Update folder_path di klien_premium
    $folder_rel = "undangan/$tier/$nama_klien";
    mysqli_query($conn, "UPDATE klien_premium SET folder_path='$folder_rel' WHERE pesanan_id='$pesanan_id'");
    
    // Log activity
    $admin_id = $_POST['admin_id'] ?? 1;
    $invoice = $pesanan_data['invoice'];
    $action_log = "Generate folder klien: undangan/$tier/$nama_klien";
    
    mysqli_query($conn, "INSERT INTO audit_logs (admin_id, action_type, target_id, keterangan) 
    VALUES ('$admin_id', 'Generate Folder', '$invoice', '$action_log')");
    
    echo json_encode([
        'status' => 'ok',
        'path' => $folder_rel,
        'message' => 'Folder berhasil dibuat: ' . $folder_rel
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan']);
}
?>
