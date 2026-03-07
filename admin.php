<?php 
// Setting Session Timeout (24 Jam)
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);

error_reporting(0);
session_start();
include 'config.php'; 

// ==========================================
// 1. SISTEM AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================
// Jika belum login, tendang ke login.php
if(!isset($_SESSION['admin_embun'])) {
    header("Location: login.php");
    exit;
}

// Timeout session idle PHP (15 menit = 900 detik)
$timeout_duration = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.php?msg=timeout");
    exit;
}
$_SESSION['last_activity'] = time();

if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// ==========================================
// 2. LOGIKA PROSES DATA (CRUD & DELETE)
// ==========================================
$notif = "";
if(isset($_SESSION['notif_pesan'])) {
    $notif = $_SESSION['notif_pesan'];
    unset($_SESSION['notif_pesan']);
}

// Definisi role & ID awal (dibutuhkan oleh semua handler POST di bawah)
$current_admin = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'admin';
$current_role  = isset($_SESSION['admin_role'])    ? $_SESSION['admin_role']    : 'Super Admin';
$my_id         = isset($_SESSION['admin_id'])      ? $_SESSION['admin_id']      : 1;

// Update Helper: Catat history ke Log
function catatLog($conn, $admin_id, $action, $target, $keterangan, $foto = null) {
    $action = mysqli_real_escape_string($conn, $action);
    $target = mysqli_real_escape_string($conn, $target);
    $ket = mysqli_real_escape_string($conn, $keterangan);
    mysqli_query($conn, "INSERT INTO audit_logs (admin_id, action_type, target_id, keterangan, screenshot_path) VALUES ('$admin_id', '$action', '$target', '$ket', '$foto')");
}

// ==========================================
// HANDLER DOWNLOAD FILE TEMPLATE
// ==========================================
if(isset($_GET['dl']) && $menu == 'template') {
    $rel_file = trim($_GET['dl'], '/\\');

    // Whitelist folder yang diizinkan untuk didownload
    $allowed_prefixes = ['tema/', 'themes/basic/'];
    $is_allowed = false;
    foreach($allowed_prefixes as $prefix) {
        if(strpos($rel_file, $prefix) === 0) { $is_allowed = true; break; }
    }

    // Cegah path traversal (../)
    if(strpos($rel_file, '..') !== false) $is_allowed = false;

    if($is_allowed) {
        $abs_file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel_file);
        if(file_exists($abs_file)) {
            $dl_name = basename($abs_file) . '.txt'; // Download as .txt agar mudah dibuka
            header('Content-Description: File Transfer');
            header('Content-Type: text/plain; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $dl_name . '"');
            header('Content-Length: ' . filesize($abs_file));
            header('Cache-Control: no-cache');
            readfile($abs_file);
            exit;
        } else {
            $_SESSION['notif_pesan'] = "Swal.fire('Error!', 'File tidak ditemukan.', 'error');";
        }
    } else {
        $_SESSION['notif_pesan'] = "Swal.fire('Akses Ditolak!', 'File tidak diizinkan untuk diunduh.', 'error');";
    }
    header("Location: admin.php?menu=template"); exit;
}

// A. Update Status Pembayaran Pesanan (Termasuk Upload Screenshot)
if(isset($_POST['update_status_pesanan'])){
    $id_pesanan = (int)$_POST['id_pesanan'];
    $status_baru = mysqli_real_escape_string($conn, $_POST['status_bayar']);
    
    // Ambil data lama
    $dt_lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT invoice, status_pembayaran FROM pesanan WHERE id='$id_pesanan'"));
    $invoice_lama = $dt_lama['invoice'];
    $status_awal = $dt_lama['status_pembayaran'];
    
    $foto_path = null;
    
    // Cek apakah statusnya benar berubah, jika sama tidak perlu dilog
        if($status_baru != $status_awal) {
        if(mysqli_query($conn, "UPDATE pesanan SET status_pembayaran='$status_baru' WHERE id='$id_pesanan'")){
            // Log activity
            $my_id_log = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
            $ket_log = "Mengubah status pembayaran dari [$status_awal] menjadi [$status_baru]";
            catatLog($conn, $my_id_log, 'Update Pesanan', $invoice_lama, $ket_log, null);

            // ── Kirim email notifikasi jika status berubah ke LUNAS
            if($status_baru === 'Lunas') {
                $dp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id WHERE p.id='$id_pesanan'"));
                if(!empty($dp['email_pemesan'])) {
                    require_once __DIR__ . '/includes/mailer.php';
                    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
                    $host = $_SERVER['HTTP_HOST'];
                    $dir = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
                    $checkout_url = "$protocol://$host" . rtrim($dir, '/') . "/invoice.php?inv=" . urlencode($dp['invoice']);
                    
                    $body = emailLunas($dp['nama_pemesan'], $dp['invoice'], $dp['nama_tema'] ?? '-', $dp['tanggal_acara'], $dp['total_tagihan'], $checkout_url);
                    $mail_sent = kirimEmail($dp['email_pemesan'], $dp['nama_pemesan'], '✅ Pembayaran Dikonfirmasi - Embun Visual', $body);
                    
                    if($mail_sent !== true) {
                        $_SESSION['notif_pesan'] = "Swal.fire('Perhatian!', 'Status diperbarui tp Gagal kirim email: " . addslashes($mail_sent) . "', 'warning');";
                    } else {
                        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Status diperbarui & Email konfirmasi terkirim.', 'success');";
                    }
                } else {
                    $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Status diperbarui (Email klien tidak diisi)', 'success');";
                }
            } else {
                $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Status pembayaran diperbarui.', 'success');";
            }
            header("Location: admin.php?menu=tugas"); exit;
        }
    } else {
        // Status tidak berubah, lempar kembali
        $_SESSION['notif_pesan'] = "Swal.fire('Info', 'Status pembayaran tidak ada yang berubah.', 'info');";
        header("Location: admin.php?menu=tugas"); exit;
    }
}

// A1. Staff Upload Tugas Selesai
if(isset($_POST['update_tugas_selesai'])){
    $id_pesanan = (int)$_POST['id_pesanan'];
    $dt_lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT invoice, status_pengerjaan FROM pesanan WHERE id='$id_pesanan'"));
    $invoice_lama = $dt_lama['invoice'];
    $status_awal = $dt_lama['status_pengerjaan'];
    $foto_path = null;
    
    // Proses upload Bukti Selesai Tugas
    if(isset($_FILES['bukti_selesai']['name']) && $_FILES['bukti_selesai']['name'] != '') {
        $nama_file = $_FILES['bukti_selesai']['name'];
        $tmp_file = $_FILES['bukti_selesai']['tmp_name'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = array('jpg','jpeg','png','webp');
        if(in_array($ext, $allowed)){
            $new_name = 'tugas_'.$id_pesanan.'_'.time().'.'.$ext;
            $upload_dir = 'uploads/audit/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $upload_path = $upload_dir.$new_name;
            if(move_uploaded_file($tmp_file, $upload_path)){
                $foto_path = $upload_path;
            }
        } else {
            $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Format foto bukti harus JPG/PNG/WEBP.', 'error');";
            header("Location: admin.php?menu=tugas"); exit;
        }
    }

    if(mysqli_query($conn, "UPDATE pesanan SET status_pengerjaan='Menunggu Verifikasi' WHERE id='$id_pesanan'")){
        $my_id_log = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
        $ket_log = "Staff mengupload hasil tugas. Mengubah status pengerjaan menjadi [Menunggu Verifikasi]";
        catatLog($conn, $my_id_log, 'Upload Tugas', $invoice_lama, $ket_log, $foto_path);
        
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Tugas ditandai selesai dan dikirim ke Super Admin.', 'success');";
        header("Location: admin.php?menu=tugas"); exit;
    }
}

// A1b. Super Admin Verifikasi Tugas
if(isset($_POST['verifikasi_tugas'])){
    $id_pesanan = (int)$_POST['id_pesanan'];
    $dt_lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id WHERE p.id='$id_pesanan'"));
    if(mysqli_query($conn, "UPDATE pesanan SET status_pengerjaan='Selesai', catatan_revisi=NULL WHERE id='$id_pesanan'")){
        $my_id_log = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
        catatLog($conn, $my_id_log, 'Verifikasi Tugas', $dt_lama['invoice'], "Super Admin memverifikasi tugas. Status pengerjaan menjadi [Selesai]", null);

        // ── Kirim email notifikasi Proyek Selesai
        if(!empty($dt_lama['email_pemesan'])) {
            require_once __DIR__ . '/includes/mailer.php';
            $body = emailSelesai($dt_lama['nama_pemesan'], $dt_lama['invoice'], $dt_lama['nama_tema'] ?? '-');
            $mail_sent = kirimEmail($dt_lama['email_pemesan'], $dt_lama['nama_pemesan'], '🎉 Undangan Digital Anda Sudah Selesai! - Embun Visual', $body);
            
            if($mail_sent !== true) {
                $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Tugas diverifikasi tapi Gagal kirim email: " . addslashes($mail_sent) . "', 'warning');";
            } else {
                $_SESSION['notif_pesan'] = "Swal.fire('Disetujui!', 'Tugas staf telah diverifikasi dan email notifikasi dikirim.', 'success');";
            }
        } else {
            $_SESSION['notif_pesan'] = "Swal.fire('Disetujui!', 'Tugas staf telah diverifikasi.', 'success');";
        }
        header("Location: admin.php?menu=tugas"); exit;
    }
}

// A1c. Super Admin Kembalikan Tugas untuk Revisi
if(isset($_POST['kembalikan_revisi'])){
    if($current_role != 'Super Admin') { header("Location: admin.php?menu=dashboard"); exit; }
    $id_pesanan   = (int)$_POST['id_pesanan'];
    $catatan      = mysqli_real_escape_string($conn, trim($_POST['catatan_revisi']));
    $dt_rev = mysqli_fetch_assoc(mysqli_query($conn, "SELECT invoice FROM pesanan WHERE id='$id_pesanan'"));
    if(mysqli_query($conn, "UPDATE pesanan SET status_pengerjaan='Perlu Revisi', catatan_revisi='$catatan' WHERE id='$id_pesanan'")){
        $my_id_log = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
        catatLog($conn, $my_id_log, 'Kembalikan Revisi', $dt_rev['invoice'], "Tugas dikembalikan untuk direvisi. Catatan: $catatan", null);
        $_SESSION['notif_pesan'] = "Swal.fire('Dikembalikan!', 'Tugas dikembalikan ke staff dengan catatan revisi.', 'warning');";
    }
    header("Location: admin.php?menu=tugas"); exit;
}

// A2. Update Assign Admin (dipindah ke tab Tugas)
if(isset($_POST['update_assign'])){
    // Hanya Super Admin yang bisa assign
    if($current_role != 'Super Admin') {
        $_SESSION['notif_pesan'] = "Swal.fire('Akses Ditolak!', 'Hanya Super Admin yang bisa menugaskan staff.', 'error');";
        header("Location: admin.php?menu=dashboard"); exit;
    }
    $id_pesanan = (int)$_POST['id_pesanan'];
    $admin_id = $_POST['admin_assign_id']; 
    $invoice_val = mysqli_fetch_assoc(mysqli_query($conn, "SELECT invoice FROM pesanan WHERE id='$id_pesanan'"))['invoice'];

    if(empty($admin_id)) $admin_id = 'NULL';
    if(mysqli_query($conn, "UPDATE pesanan SET admin_id=$admin_id WHERE id='$id_pesanan'")){
        $my_id_log = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
        $txt_admin = ($admin_id == 'NULL') ? "Dikosongkan" : "Staff ID $admin_id";
        catatLog($conn, $my_id_log, 'Tugaskan Staff', $invoice_val, "Menugaskan pesanan ini kepada: $txt_admin");
        
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Penugasan staff diperbarui.', 'success');";
    }
    header("Location: admin.php?menu=tugas"); exit;
}

// B. Tambah Katalog Tema Baru
if(isset($_POST['tambah_tema'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $kat = mysqli_real_escape_string($conn, $_POST['kategori']); 
    $hrg = mysqli_real_escape_string($conn, $_POST['harga']);
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']); 
    $img = mysqli_real_escape_string($conn, $_POST['gambar_url']); 
    
    // FIX SPASI: Tambahkan trim() saat menyimpan link demo
    $link_demo = mysqli_real_escape_string($conn, trim($_POST['link_demo']));

    if(mysqli_query($conn, "INSERT INTO katalog_tema (nama_tema, kategori, harga, deskripsi, gambar_url, slug_demo) VALUES ('$nama', '$kat', '$hrg', '$desc', '$img', '$link_demo')")){
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Tema baru masuk katalog.', 'success');";
    } else {
        $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Gagal menambahkan tema.', 'error');";
    }
    header("Location: admin.php?menu=katalog"); exit;
}

// C. UPDATE (EDIT) KATALOG TEMA
if(isset($_POST['update_tema'])){
    $id_tema = $_POST['id_tema'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $kat = mysqli_real_escape_string($conn, $_POST['kategori']); 
    $hrg = mysqli_real_escape_string($conn, $_POST['harga']);
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']); 
    $img = mysqli_real_escape_string($conn, $_POST['gambar_url']); 
    
    // FIX SPASI: Tambahkan trim() saat update link demo
    $link_demo = mysqli_real_escape_string($conn, trim($_POST['link_demo']));

    $query_update = "UPDATE katalog_tema SET nama_tema='$nama', kategori='$kat', harga='$hrg', deskripsi='$desc', gambar_url='$img', slug_demo='$link_demo' WHERE id='$id_tema'";
    if(mysqli_query($conn, $query_update)){
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Tema berhasil diperbarui.', 'success');";
    } else {
        $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Gagal memperbarui tema.', 'error');";
    }
    header("Location: admin.php?menu=katalog"); exit;
}

// D. Tambah Admin Baru
if(isset($_POST['tambah_admin'])){
    $u = mysqli_real_escape_string($conn, $_POST['username_baru']);
    $p = md5($_POST['password_baru']);
    $r = mysqli_real_escape_string($conn, $_POST['role_baru']);
    $cek_user = mysqli_query($conn, "SELECT * FROM admin_users WHERE username='$u'");
    if(mysqli_num_rows($cek_user) > 0) {
        $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Username tersebut sudah dipakai.', 'error');";
    } else {
        if(mysqli_query($conn, "INSERT INTO admin_users (username, password, role) VALUES ('$u', '$p', '$r')")){
            $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Admin baru ditambahkan.', 'success');";
        }
    }
    header("Location: admin.php?menu=admin"); exit;
}

// D2. Ubah Password Admin
if(isset($_POST['ubah_password'])){
    $id = $_POST['id_admin'];
    $p = md5($_POST['password_baru']);
    if(mysqli_query($conn, "UPDATE admin_users SET password='$p' WHERE id='$id'")){
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Password admin berhasil diubah.', 'success');";
    }
    header("Location: admin.php?menu=admin"); exit;
}

// E. Tambah Galeri Homepage
if(isset($_POST['tambah_galeri'])){
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $sumber_nama = mysqli_real_escape_string($conn, $_POST['sumber_nama']);
    $sumber_link = mysqli_real_escape_string($conn, $_POST['sumber_link']);
    $type = $_POST['tipe_input'];
    
    if($type == 'url'){
        $gambar = mysqli_real_escape_string($conn, trim($_POST['gambar_url']));
        if(mysqli_query($conn, "INSERT INTO galeri (gambar, type, caption, sumber_nama, sumber_link) VALUES ('$gambar', 'url', '$caption', '$sumber_nama', '$sumber_link')")){
            $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Foto galeri dari URL ditambahkan.', 'success');";
        }
    } else if($type == 'upload') {
        $nama_file = $_FILES['gambar_file']['name'];
        $tmp_file = $_FILES['gambar_file']['tmp_name'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = array('jpg','jpeg','png','webp');
        
        if(in_array($ext, $allowed)){
            $new_name = uniqid().'.'.$ext;
            $upload_path = 'uploads/galeri/'.$new_name;
            
            if(move_uploaded_file($tmp_file, $upload_path)){
                if(mysqli_query($conn, "INSERT INTO galeri (gambar, type, caption, sumber_nama, sumber_link) VALUES ('$upload_path', 'upload', '$caption', '$sumber_nama', '$sumber_link')")){
                    $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Foto berhasil di-upload ke galeri.', 'success');";
                }
            } else {
                $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Gagal memindahkan file ke server.', 'error');";
            }
        } else {
            $_SESSION['notif_pesan'] = "Swal.fire('Format Salah!', 'Hanya JPG, PNG, WEBP.', 'warning');";
        }
    }
    header("Location: admin.php?menu=galeri"); exit;
}

// E2. Update Galeri Homepage
if(isset($_POST['update_galeri'])){
    $id = $_POST['id_galeri'];
    $caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $sumber_nama = mysqli_real_escape_string($conn, $_POST['sumber_nama']);
    $sumber_link = mysqli_real_escape_string($conn, $_POST['sumber_link']);
    $type = $_POST['tipe_input'];
    
    // Jika ada gambar baru dari URL
    if($type == 'url'){
        $gambar = mysqli_real_escape_string($conn, trim($_POST['gambar_url']));
        if(mysqli_query($conn, "UPDATE galeri SET gambar='$gambar', type='url', caption='$caption', sumber_nama='$sumber_nama', sumber_link='$sumber_link' WHERE id='$id'")){
            $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Data galeri berhasil diperbarui.', 'success');";
        }
    } 
    // Jika ada gambar baru lewat upload file
    else if($type == 'upload' && !empty($_FILES['gambar_file']['name'])) {
        $nama_file = $_FILES['gambar_file']['name'];
        $tmp_file = $_FILES['gambar_file']['tmp_name'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = array('jpg','jpeg','png','webp');
        
        if(in_array($ext, $allowed)){
            $new_name = uniqid().'.'.$ext;
            $upload_path = 'uploads/galeri/'.$new_name;
            
            if(move_uploaded_file($tmp_file, $upload_path)){
                if(mysqli_query($conn, "UPDATE galeri SET gambar='$upload_path', type='upload', caption='$caption', sumber_nama='$sumber_nama', sumber_link='$sumber_link' WHERE id='$id'")){
                    $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Foto galeri berhasil diperbarui.', 'success');";
                }
            }
        }
    }
    // Jika hanya memperbarui teks, tidak ubah foto lama
    else {
        if(mysqli_query($conn, "UPDATE galeri SET caption='$caption', sumber_nama='$sumber_nama', sumber_link='$sumber_link' WHERE id='$id'")){
            $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Keterangan galeri diperbarui.', 'success');";
        }
    }
    header("Location: admin.php?menu=galeri"); exit;
}

// F. FUNGSI HAPUS DATA
if(isset($_POST['hapus_data'])){
    $tabel = $_POST['tabel'];
    $id = $_POST['id_hapus'];
    $hal = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';
    
    if(mysqli_query($conn, "DELETE FROM $tabel WHERE id='$id'")){
        $_SESSION['notif_pesan'] = "Swal.fire('Terhapus!', 'Data berhasil dihapus dari sistem.', 'success');";
    } else {
        $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Tidak dapat menghapus data.', 'error');";
    }
    header("Location: admin.php?menu=$hal"); exit;
}

// G. BUAT / UPDATE Akun Premium Klien
if(isset($_POST['buat_akun_premium'])) {
    if($current_role != 'Super Admin') { header("Location: admin.php"); exit; }
    $pesanan_id = (int)$_POST['pesanan_id_prem'];
    $uname = mysqli_real_escape_string($conn, trim($_POST['prem_username']));
    $pass  = md5($_POST['prem_password']);
    $tipe  = mysqli_real_escape_string($conn, $_POST['prem_tipe'] ?? 'Premium');
    
    // Upsert: insert or update
    $cek = mysqli_query($conn, "SELECT id FROM klien_premium WHERE pesanan_id='$pesanan_id'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE klien_premium SET username='$uname', password='$pass', tipe='$tipe', is_active=1 WHERE pesanan_id='$pesanan_id'");
        $_SESSION['notif_pesan'] = "Swal.fire('Diperbarui!', 'Akun Premium & Tier berhasil diperbarui.', 'success');";
    } else {
        mysqli_query($conn, "INSERT INTO klien_premium (pesanan_id, username, password, tipe, created_by) VALUES ('$pesanan_id', '$uname', '$pass', '$tipe', '$my_id')");
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Akun Premium & Tier klien berhasil dibuat.', 'success');";
    }
    header("Location: admin.php?menu=premium"); exit;
}

// H. Balas Chat dari Admin / Staff
if(isset($_POST['balas_chat_admin'])) {
    $pesanan_id_chat = (int)$_POST['pesanan_id_chat'];
    $pesan_admin = mysqli_real_escape_string($conn, trim($_POST['pesan_admin']));
    if($pesan_admin !== '') {
        $nama_admin_chat = mysqli_real_escape_string($conn, $current_admin);
        mysqli_query($conn, "INSERT INTO pesan_proyek (pesanan_id, pengirim, nama_pengirim, pesan) VALUES ('$pesanan_id_chat', 'admin', '$nama_admin_chat', '$pesan_admin')");
        $_SESSION['notif_pesan'] = "Swal.fire({toast:true, position:'top-end', icon:'success', title:'Pesan terkirim!', showConfirmButton:false, timer:2000});";
    }
    header("Location: admin.php?menu=premium&pid=$pesanan_id_chat"); exit;
}

// ==========================================
// 3. AMBIL STATISTIK UNTUK DASHBOARD
// ==========================================
$current_admin = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'admin';
$current_role  = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Super Admin';
$my_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
$adminFilterStat = ($current_role != 'Super Admin') ? "WHERE admin_id='$my_id'" : "";
$adminFilterStatAnd = ($current_role != 'Super Admin') ? "AND admin_id='$my_id'" : "";

$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan $adminFilterStat"))['jml'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_tagihan) as total FROM pesanan WHERE status_pembayaran='Lunas' $adminFilterStatAnd"))['total'];
$total_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM request_custom WHERE status_request='Menunggu Review'"))['jml'];

$jml_menunggu_bayar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE status_pembayaran='Menunggu Konfirmasi' $adminFilterStatAnd"))['jml'];
$jml_menunggu = $jml_menunggu_bayar; // Alias agar tidak undefined di JS
$badge_notif_bayar = ($jml_menunggu_bayar > 0) ? "<span style='background:#ef4444; color:white; padding:2px 6px; border-radius:50px; font-size:0.7rem; margin-left:5px;'>$jml_menunggu_bayar</span>" : "";

if($current_role == 'Super Admin'){
    $jml_tugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE status_pengerjaan='Menunggu Verifikasi'"))['jml'];
} else {
    $jml_tugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE admin_id='$my_id' AND status_pembayaran='Lunas' AND status_pengerjaan NOT IN ('Selesai','Menunggu Verifikasi')"))['jml'];
}
$badge_notif_tugas = ($jml_tugas > 0) ? "<span style='background:#ef4444; color:white; padding:2px 6px; border-radius:50px; font-size:0.7rem; margin-left:5px;'>$jml_tugas</span>" : "";

$menu = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';

// ==========================================
// AKSES GUARD: Pesanan Masuk hanya untuk Super Admin
// ==========================================
if($menu == 'pesanan' && $current_role != 'Super Admin') {
    $_SESSION['notif_pesan'] = "Swal.fire('Akses Ditolak!', 'Halaman Pesanan Masuk hanya untuk Super Admin.', 'error');";
    header("Location: admin.php?menu=dashboard"); exit;
}

// 3B. TABEL KINERJA ADMIN (HANYA UNTUK SUPER ADMIN)
$kinerja_admin = [];
if($current_role == 'Super Admin') {
    $q_kinerja = mysqli_query($conn, "
        SELECT a.username, a.role,
               (SELECT COUNT(id) FROM pesanan WHERE admin_id = a.id) as total_tugas,
               (SELECT SUM(total_tagihan) FROM pesanan WHERE admin_id = a.id AND status_pembayaran='Lunas') as total_pendapatan
        FROM admin_users a
    ");
    while($row = mysqli_fetch_assoc($q_kinerja)) {
        // Jika hasil SUM NULL, set ke 0
        if(is_null($row['total_pendapatan'])) $row['total_pendapatan'] = 0;
        $kinerja_admin[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embun Visual | HQ Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #4A5D4E; --bg: #F0F4F2; --surface: #FFFFFF; --text: #1E293B; --muted: #64748B; --border: #E8EDE9; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--text); display: flex; min-height: 100vh; font-size: 15px; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar { width: 280px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-right: none; box-shadow: 2px 0 20px rgba(0,0,0,0.03); padding: 30px 25px; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; transition: all 0.3s ease; }
        .brand { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 45px; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; }
        .brand i { background: var(--primary); color: white; padding: 8px; border-radius: 10px; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(74,93,78,0.2); }
        
        .nav-item { display: flex; align-items: center; gap: 14px; padding: 15px 18px; color: var(--muted); text-decoration: none; border-radius: 12px; font-weight: 600; margin-bottom: 8px; transition: all 0.25s ease; font-size: 0.95rem; }
        .nav-item i { font-size: 1.1rem; width: 20px; text-align: center; }
        .nav-item:hover { background: #f8fafc; color: var(--primary); transform: translateX(5px); }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 8px 16px rgba(74,93,78,0.25); }
        
        .main { flex: 1; margin-left: 280px; padding: 45px 50px; transition: all 0.3s ease; }
        .page-header { margin-bottom: 35px; display: flex; justify-content: space-between; align-items: flex-end; }
        .page-title { font-size: 1.9rem; font-weight: 800; color: var(--text); letter-spacing: -0.5px; margin-bottom: 5px; }
        
        .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 45px; }
        .stat-card { background: var(--surface); padding: 28px; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.06); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary), #a3b1a5); opacity: 0; transition: opacity 0.3s ease; }
        .stat-card:hover::before { opacity: 1; }
        .stat-title { font-size: 0.85rem; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px; position: relative; z-index: 2; }
        .stat-value { font-size: 2.2rem; font-weight: 800; color: var(--text); position: relative; z-index: 2; display: flex; align-items: center; }
        .stat-value i { opacity: 0.1 !important; font-size: 4.5rem !important; color: var(--primary) !important; position: absolute; right: -10px; bottom: -10px; z-index: 1; }
        
        .card { background: var(--surface); border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 35px; }
        .card-header { padding: 22px 30px; border-bottom: 1px solid var(--border); background: white; font-weight: 700; font-size: 1.1rem; color: var(--text); display: flex; align-items: center; gap: 10px; }
        .card-header i { color: var(--primary); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th, td { padding: 18px 30px; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
        th { font-weight: 700; color: var(--muted); background: #fdfdfd; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
        tbody tr { transition: background 0.2s ease; }
        tbody tr:hover { background: #fbfcfa; }
        
        .badge { padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-blue { background: #e0f2fe; color: #075985; }
        
        .btn-action { padding: 10px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.85rem; transition: all 0.2s ease; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
        .btn-primary { background: var(--primary); color: white; box-shadow: 0 4px 10px rgba(74,93,78,0.2); }
        .btn-primary:hover { background: #3b4b3e; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(74,93,78,0.3); }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fecaca; }
        .btn-secondary { background: #f1f5f9; color: #334155; }
        .btn-secondary:hover { background: #e2e8f0; }
        
        .search-box { position: relative; max-width: 320px; display: inline-block; width: 100%; margin-top: 10px; }
        .search-box i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); }
        .search-box input { width: 100%; padding: 12px 18px 12px 45px; border: 1px solid transparent; border-radius: 50px; font-family: inherit; outline: none; transition: 0.3s; font-size: 0.9rem; background: #f1f5f9; font-weight: 500; }
        .search-box input:focus { background: white; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(74,93,78,0.1); }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; color: var(--text); }
        .form-control { width: 100%; padding: 14px; border: 1px solid var(--border); border-radius: 10px; font-family: inherit; outline: none; transition: all 0.2s ease; background: #fafafa; }
        .form-control:focus { background: white; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(74,93,78,0.1); }
        .text-hint { color: var(--muted); font-size: 0.8rem; display: block; margin-top: 6px; }

        /* Notification Toast */
        .notif-toast { position: fixed; top: 20px; right: -400px; background: white; border-left: 5px solid var(--primary); box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 10px; padding: 15px 25px; display: flex; align-items: center; gap: 15px; z-index: 9999; transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .notif-toast.show { right: 20px; }
        .notif-icon { width: 40px; height: 40px; border-radius: 50%; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .notif-content { flex: 1; }
        .notif-title { font-weight: 700; color: var(--text); font-size: 0.95rem; margin-bottom: 2px; }
        .notif-desc { color: var(--muted); font-size: 0.8rem; }
        .notif-close { cursor: pointer; color: #94a3b8; transition: color 0.2s; }
        .notif-close:hover { color: #ef4444; }
        
        /* Bell Ping Animation */
        @keyframes ring {
            0% { transform: rotate(0); }
            5% { transform: rotate(30deg); }
            10% { transform: rotate(-28deg); }
            15% { transform: rotate(34deg); }
            20% { transform: rotate(-32deg); }
            25% { transform: rotate(30deg); }
            30% { transform: rotate(-28deg); }
            35% { transform: rotate(26deg); }
            40% { transform: rotate(-24deg); }
            45% { transform: rotate(22deg); }
            50% { transform: rotate(-20deg); }
            55% { transform: rotate(18deg); }
            60% { transform: rotate(-16deg); }
            65% { transform: rotate(14deg); }
            70% { transform: rotate(-12deg); }
            75% { transform: rotate(10deg); }
            80% { transform: rotate(-8deg); }
            85% { transform: rotate(6deg); }
            90% { transform: rotate(-4deg); }
            95% { transform: rotate(2deg); }
            100% { transform: rotate(0); }
        }
        .bell-shake { animation: ring 2s ease 1; }
    </style>
</head>
<body>

    <!-- Notification Sound -->
    <audio id="notifSound" preload="auto">
        <source src="assets/sounds/notif.mp3" type="audio/mpeg">
    </audio>

    <!-- Notification UI Toast -->
    <div id="liveNotifToast" class="notif-toast">
        <div class="notif-icon"><i class="fas fa-bell"></i></div>
        <div class="notif-content">
            <div class="notif-title">Pemberitahuan Baru</div>
            <div class="notif-desc" id="notifDescText">Ada tugas baru yang perlu dicek.</div>
        </div>
        <i class="fas fa-times notif-close" onclick="closeNotif()"></i>
    </div>

    <aside class="sidebar" data-aos="fade-right" data-aos-duration="800">
        <div class="brand"><i class="fas fa-leaf"></i> Embun Visual</div>
        
        <a href="?menu=dashboard" class="nav-item <?= $menu == 'dashboard' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <?php if($current_role == 'Super Admin') { ?>
        <a href="?menu=pesanan" class="nav-item <?= $menu == 'pesanan' ? 'active' : '' ?>">
            <i class="fas fa-receipt" id="navIconPesanan"></i> Pesanan Masuk 
            <span id="badgePesanan"></span>
            <?= $badge_notif_bayar ?>
        </a>
        <?php } ?>
        <a href="?menu=tugas" class="nav-item <?= $menu == 'tugas' ? 'active' : '' ?>">
            <i class="fas fa-tasks"></i> Tugas & Verifikasi
            <?= $badge_notif_tugas ?>
        </a>
        <a href="?menu=template" class="nav-item <?= $menu == 'template' ? 'active' : '' ?>">
            <i class="fas fa-code"></i> Template Referensi
        </a>
        <?php if($current_role == 'Super Admin') { ?>
        <a href="?menu=katalog" class="nav-item <?= $menu == 'katalog' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Kelola Katalog</a>
        <?php } ?>
        <a href="?menu=request" class="nav-item <?= $menu == 'request' ? 'active' : '' ?>">
            <i class="fas fa-paint-brush"></i> Request Custom
            <span id="badgeRequest"></span>
        </a>
        <?php if($current_role == 'Super Admin') { ?>
        <a href="?menu=galeri" class="nav-item <?= $menu == 'galeri' ? 'active' : '' ?>"><i class="fas fa-images"></i> Pengaturan Galeri</a>
        <a href="?menu=admin" class="nav-item <?= $menu == 'admin' ? 'active' : '' ?>"><i class="fas fa-users-cog"></i> Kelola Admin</a>
        <a href="?menu=premium" class="nav-item <?= $menu == 'premium' ? 'active' : '' ?>" style="background: <?= $menu == 'premium' ? 'var(--primary)' : 'rgba(212,175,55,0.08)' ?>; color: <?= $menu == 'premium' ? 'white' : '#D4AF37' ?>;">
            <i class="fas fa-crown"></i> Premium Portal
        </a>
        <?php } ?>
        <a href="?menu=audit" class="nav-item <?= $menu == 'audit' ? 'active' : '' ?>"><i class="fas fa-history"></i> Aktivitas Log</a>
        <a href="?menu=kalender" class="nav-item <?= $menu == 'kalender' ? 'active' : '' ?>"><i class="fas fa-calendar-alt"></i> Kalender Booking</a>
        <a href="?menu=generator" class="nav-item <?= $menu == 'generator' ? 'active' : '' ?>"><i class="fas fa-magic"></i> Generator Link</a>
        
        <div style="margin-top: auto;">
            <a href="index.php" target="_blank" class="nav-item" style="background:#f1f5f9; color:#333;"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
            <a href="?logout=true" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Keluar System</a>
        </div>
    </aside>

    <main class="main">
        
        <?php if($menu == 'dashboard') { ?>
        <div class="welcome-banner" data-aos="fade-down" style="background: linear-gradient(135deg, var(--primary) 0%, #354538 100%); color: white; padding: 35px 40px; border-radius: 20px; margin-bottom: 35px; box-shadow: 0 10px 30px rgba(74,93,78,0.25); display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 2;">
                <h1 style="font-size: 2rem; margin-bottom: 8px; font-weight: 700;">Halo, <?= htmlspecialchars($current_admin) ?>! 👋</h1>
                <p style="opacity: 0.9; margin: 0; font-size: 1.05rem;">Selamat datang kembali di panel kontrol admin Embun Visual. Semoga harimu menyenangkan!</p>
            </div>
            <div style="font-size: 8rem; position: absolute; right: 20px; bottom: -30px; opacity: 0.1; color: white; transform: rotate(-15deg); z-index: 1;">
                <i class="fas fa-leaf"></i>
            </div>
        </div>
        
        <div class="page-header" data-aos="fade-down" data-aos-delay="50">
            <h1 class="page-title">Ikhtisar Bisnis</h1>
        </div>

        <div class="grid-stats">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-title"><?= ($current_role == 'Super Admin') ? 'Total Pesanan (Global)' : 'Total Pesanan Anda' ?></div>
                <div class="stat-value"><?= number_format($total_pesanan) ?> <i class="fas fa-shopping-bag" style="font-size:1rem; color:var(--muted);"></i></div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-title"><?= ($current_role == 'Super Admin') ? 'Total Pendapatan (Global)' : 'Total Pendapatan Anda' ?></div>
                <div class="stat-value">Rp <?= number_format((float)$total_pendapatan,0,',','.') ?></div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-title">Menunggu Konfirmasi Transfer</div>
                <div class="stat-value" style="color:#0284c7;"><?= number_format($jml_menunggu_bayar) ?> <i class="fas fa-bell" style="font-size:1rem;"></i></div>
            </div>
        </div>
        
        <?php if($current_role == 'Super Admin') { ?>
        <div class="card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header"><i class="fas fa-chart-line"></i> Laporan Kinerja Staf & Admin</div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Staf</th>
                            <th>Hak Akses</th>
                            <th>Total Klien / Proyek</th>
                            <th>Pendapatan (Lunas)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($kinerja_admin as $ka) { ?>
                        <tr>
                            <td style="font-weight:600;"><i class="fas fa-user-circle" style="color:var(--muted); margin-right:5px;"></i> <?= htmlspecialchars($ka['username']) ?></td>
                            <td><?= $ka['role'] == 'Super Admin' ? "<span class='badge badge-yellow'>Super Admin</span>" : "<span class='badge badge-blue'>Staff User</span>" ?></td>
                            <td style="font-weight:600; font-size:1.1rem;"><?= number_format($ka['total_tugas']) ?> Klien</td>
                            <td style="font-weight:600; color:var(--primary);">Rp <?= number_format($ka['total_pendapatan'],0,',','.') ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } else { ?>
        <div class="card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header"><i class="fas fa-info-circle"></i> Panduan Singkat Staff</div>
            <div style="padding: 25px; line-height:1.8; color: var(--muted);">
                1. Anda dapat melihat <b>Klien & Proyek</b> yang telah ditugaskan khusus untuk Anda oleh Super Admin di menu <b>Pesanan Masuk</b>.<br>
                2. Status <b>Menunggu Konfirmasi</b> artinya klien mengklaim sudah mentransfer dana. Harap cek rekening / mutasi dan validasi pembayaran.<br>
                3. Jika dana benar masuk, ubah status pembayarannya menjadi <b>Lunas</b>.
            </div>
        </div>
        <?php } ?>

        <?php } elseif($menu == 'pesanan') { ?>
        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title">Daftar Pesanan Booking</h1>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari info (Invoice, Nama, Status)...">
            </div>
        </div>

        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <div style="overflow-x: auto;">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Klien & Kontak</th>
                            <th>Tema Dipilih</th>
                            <th>Tagihan</th>
                            <th>Status Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $adminFilter = "";
                        $list_admins = [];
                        
                        // Perbaikan: Jika sesi lama masih menyala (admin_username kosong), anggap sebagai super admin
                        $current_admin = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'admin';
                        $my_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
                        
                        if($current_admin == 'admin') {
                            $rads = mysqli_query($conn, "SELECT id, username FROM admin_users");
                            while($ad = mysqli_fetch_assoc($rads)) { $list_admins[] = $ad; }
                        } else {
                            $adminFilter = "WHERE p.admin_id='$my_id'"; 
                        }
                        
                        $q_pesanan = mysqli_query($conn, "SELECT p.*, k.nama_tema, k.slug_demo FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id $adminFilter ORDER BY p.id DESC");
                        while($row = mysqli_fetch_assoc($q_pesanan)) { 
                            $badge = 'badge-yellow'; 
                            if($row['status_pembayaran'] == 'Lunas') $badge = 'badge-green';
                            if($row['status_pembayaran'] == 'Belum Bayar') $badge = 'badge-red';
                            if($row['status_pembayaran'] == 'Menunggu Konfirmasi') $badge = 'badge-blue';
                            if($row['status_pembayaran'] == 'Selesai Dikerjakan') $badge = 'badge-yellow';
                        ?>
                        <tr <?= ($row['status_pembayaran'] == 'Menunggu Konfirmasi' || $row['status_pembayaran'] == 'Selesai Dikerjakan') ? "style='background:#f0f9ff;'" : "" ?>>
                            <td style="font-weight:600; color:var(--primary);"><?= $row['invoice'] ?></td>
                            <td>
                                <b><?= $row['nama_pemesan'] ?></b><br>
                                <a href="https://wa.me/<?= $row['no_whatsapp'] ?>" target="_blank" style="color:#25D366; text-decoration:none; font-size:0.85rem;"><i class="fab fa-whatsapp"></i> <?= $row['no_whatsapp'] ?></a>
                            </td>
                            <td>
                                <?php 
                                    $safeName = str_replace([' ', '&'], ['+', '%26'], $row['nama_pemesan']); 
                                    $linkUndangan = "";
                                    if(!empty($row['slug_demo'])){
                                        $separator = strpos($row['slug_demo'], '?') !== false ? '&' : '?';
                                        $linkUndangan = trim($row['slug_demo']) . $separator . "to=" . $safeName;
                                    }
                                ?>
                                <?php if($linkUndangan){ ?>
                                    <a href="<?= $linkUndangan ?>" target="_blank" style="color:#0284c7; text-decoration:none; font-weight:600;"><i class="fas fa-external-link-alt"></i> <?= $row['nama_tema'] ?></a>
                                <?php } else { ?>
                                    <b><?= $row['nama_tema'] ?></b>
                                <?php } ?>
                                <br>
                                <span style="font-size:0.8rem; color:var(--muted);">Tgl: <?= date('d M Y', strtotime($row['tanggal_acara'])) ?></span>
                            </td>
                            <td style="font-weight:600;">Rp <?= number_format($row['total_tagihan'],0,',','.') ?></td>
                            <td><span class="badge <?= $badge ?>"><?= $row['status_pembayaran'] ?></span></td>
                            <td style="display:flex; flex-direction:column; gap:8px;">
                                <div style="display:flex; gap:10px; align-items:center;">
                                    <button type="button" onclick="openUpdateModal(<?= $row['id'] ?>, '<?= $row['status_pembayaran'] ?>')" class="btn-action btn-primary"><i class="fas fa-edit"></i> Ubah Status</button>
                                    
                                    <form method="POST" action="admin.php?menu=pesanan" onsubmit="return confirmDelete(event, this, 'Yakin ingin menghapus pesanan ini?');">
                                        <input type="hidden" name="tabel" value="pesanan">
                                        <input type="hidden" name="id_hapus" value="<?= $row['id'] ?>">
                                        <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                                <?php
                                    // Tampilkan info staff yang sudah di-assign (read-only di sini)
                                    if($row['admin_id']) {
                                        $staff_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM admin_users WHERE id='".(int)$row['admin_id']."'"));
                                        $staff_name = $staff_info ? htmlspecialchars($staff_info['username']) : 'Staff';
                                        echo "<div style='border-top:1px dashed var(--border); padding-top:6px; margin-top:2px;'>
                                                <span class='badge badge-blue'><i class='fas fa-user-check'></i> Ditugaskan: $staff_name</span>
                                              </div>";
                                    } else {
                                        echo "<div style='border-top:1px dashed var(--border); padding-top:6px; margin-top:2px;'>
                                                <span style='font-size:0.75rem; color:var(--muted);'><i class='fas fa-user-slash'></i> Belum di-assign</span>
                                              </div>";
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal Update Status -->
        <div id="modalUpdateStatus" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
            <div style="background:white; padding:30px; border-radius:15px; width:400px; max-width:90%;">
                <h3 style="margin-bottom:20px; color:var(--text);"><i class="fas fa-edit" style="color:var(--primary);"></i> Update Status Pesanan</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_pesanan" id="modal_id_pesanan">
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select name="status_bayar" id="modal_status_bayar" class="form-control" onchange="toggleBuktiUpload()" required>
                            <option value="Belum Bayar">Belum Bayar</option>
                            <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                            <option value="Selesai Dikerjakan">Selesai Dikerjakan (Upload Hasil)</option>
                            <?php if($current_role == 'Super Admin') { ?>
                            <option value="Lunas">Lunas (Verifikasi Selesai)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="fileUploadContainer" style="display:none; background:#f8fafc; padding:15px; border-radius:10px; border:1px dashed #cbd5e1;">
                        <label style="color:var(--text); font-size:0.85rem;"><i class="fas fa-file-image" style="color:#0284c7;"></i> Wajib melampirkan Screenshot Bukti Transfer</label>
                        <input type="file" name="bukti_selesai" id="bukti_selesai" class="form-control" accept="image/*" style="margin-top:10px; padding:8px; font-size:0.85rem;">
                        <span class="text-hint">Hanya file JPG/PNG max 2MB.</span>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:25px;">
                        <button type="button" onclick="closeUpdateModal()" class="btn-action btn-secondary">Batal</button>
                        <button type="submit" name="update_status_pesanan" class="btn-action btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function openUpdateModal(id, currentStatus) {
            document.getElementById('modal_id_pesanan').value = id;
            document.getElementById('modal_status_bayar').value = currentStatus;
            document.getElementById('modalUpdateStatus').style.display = 'flex';
            toggleBuktiUpload();
        }
        function closeUpdateModal() {
            document.getElementById('modalUpdateStatus').style.display = 'none';
        }
        function toggleBuktiUpload() {
            let status = document.getElementById('modal_status_bayar').value;
            let fileContainer = document.getElementById('fileUploadContainer');
            let fileInput = document.getElementById('bukti_selesai');
            
            if(status === 'Lunas' || status === 'Selesai Dikerjakan') {
                fileContainer.style.display = 'block';
                if(status === 'Selesai Dikerjakan') {
                    fileInput.setAttribute('required', 'required');
                } else {
                    fileInput.removeAttribute('required'); // Super Admin (Lunas) tak wajib upload ulang
                }
            } else {
                fileContainer.style.display = 'none';
                fileInput.removeAttribute('required');
            }
        }
        </script>

        <?php } elseif($menu == 'tugas') { 
            // Ambil daftar semua staff untuk dropdown assign
            $list_all_staff = [];
            $q_allstaff = mysqli_query($conn, "SELECT id, username FROM admin_users ORDER BY id ASC");
            while($ads = mysqli_fetch_assoc($q_allstaff)) { $list_all_staff[] = $ads; }
        ?>
        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title">Ruang Tugas & Verifikasi</h1>
                <p style="color:var(--muted); margin-top:5px;">
                    <?= $current_role == 'Super Admin' ? 'Konfirmasi pembayaran, tugaskan staff, dan verifikasi hasil pekerjaan.' : 'Daftar penugasan Anda yang aktif.' ?>
                </p>
            </div>
        </div>

        <?php if($current_role == 'Super Admin') { ?>

        <!-- ====================================================
             SUPER ADMIN: SECTION A — KONFIRMASI PEMBAYARAN
        ==================================================== -->
        <div class="card" data-aos="fade-up" data-aos-delay="150" style="margin-bottom:30px;">
            <div class="card-header" style="background:linear-gradient(90deg,#fff7ed,#fff); border-left:4px solid #f97316;">
                <i class="fas fa-money-bill-wave" style="color:#f97316;"></i> 
                Section A — Konfirmasi Pembayaran & Penugasan Staff
                <span style="font-size:0.8rem; font-weight:400; color:var(--muted); margin-left:8px;">Pesanan yang belum lunas. Konfirmasi bayar di sini, lalu assign ke staff.</span>
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Invoice & Klien</th>
                            <th>Tema & Tgl Acara</th>
                            <th>Tagihan</th>
                            <th>Status Bayar</th>
                            <th>Aksi Pembayaran & Penugasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_pending = mysqli_query($conn, "SELECT p.*, k.nama_tema, k.slug_demo, a.username as staff_name 
                            FROM pesanan p 
                            LEFT JOIN katalog_tema k ON p.tema_id = k.id 
                            LEFT JOIN admin_users a ON p.admin_id = a.id 
                            WHERE p.status_pembayaran != 'Lunas' 
                            ORDER BY 
                                CASE p.status_pembayaran 
                                    WHEN 'Menunggu Konfirmasi' THEN 1 
                                    WHEN 'Belum Bayar' THEN 2 
                                    ELSE 3 END, 
                                p.id DESC");
                        
                        $count_pending = 0;
                        while($rp = mysqli_fetch_assoc($q_pending)) { 
                            $count_pending++;
                            $badge_p = 'badge-red';
                            if($rp['status_pembayaran'] == 'Menunggu Konfirmasi') $badge_p = 'badge-blue';
                            if($rp['status_pembayaran'] == 'Selesai Dikerjakan') $badge_p = 'badge-yellow';
                        ?>
                        <tr style="<?= $rp['status_pembayaran'] == 'Menunggu Konfirmasi' ? 'background:#fffbeb;' : '' ?>">
                            <td>
                                <b style="color:var(--primary);"><?= $rp['invoice'] ?></b><br>
                                <span style="font-size:0.9rem;"><?= htmlspecialchars($rp['nama_pemesan']) ?></span><br>
                                <a href="https://wa.me/<?= $rp['no_whatsapp'] ?>" target="_blank" style="color:#25D366; text-decoration:none; font-size:0.8rem;"><i class="fab fa-whatsapp"></i> <?= $rp['no_whatsapp'] ?></a>
                            </td>
                            <td>
                                <b><?= htmlspecialchars($rp['nama_tema']) ?></b><br>
                                <span style="font-size:0.8rem; color:var(--muted);">Tgl: <?= date('d M Y', strtotime($rp['tanggal_acara'])) ?></span>
                            </td>
                            <td style="font-weight:700; white-space:nowrap;">Rp <?= number_format($rp['total_tagihan'],0,',','.') ?></td>
                            <td><span class="badge <?= $badge_p ?>"><?= $rp['status_pembayaran'] ?></span></td>
                            <td>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    <!-- Tombol Ubah Status Bayar -->
                                    <button type="button" onclick="openPayModal(<?= $rp['id'] ?>, '<?= $rp['status_pembayaran'] ?>')" class="btn-action btn-primary" style="font-size:0.85rem;">
                                        <i class="fas fa-coins"></i> Konfirmasi Bayar
                                    </button>
                                    <!-- Dropdown Assign Staff -->
                                    <form method="POST" action="admin.php?menu=tugas" style="display:flex; gap:6px; align-items:center;">
                                        <input type="hidden" name="id_pesanan" value="<?= $rp['id'] ?>">
                                        <select name="admin_assign_id" class="form-control" style="padding:5px; flex:1; font-size:0.8rem; border-color:var(--primary);">
                                            <option value="">-- Assign Staff --</option>
                                            <?php foreach($list_all_staff as $sad){ ?>
                                                <option value="<?= $sad['id'] ?>" <?= ($rp['admin_id'] == $sad['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($sad['username']) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" name="update_assign" class="btn-action btn-secondary" title="Simpan Penugasan" style="padding:5px 10px;">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    </form>
                                    <?php if($rp['staff_name']) { ?>
                                        <span style="font-size:0.75rem; color:#0284c7;"><i class="fas fa-user"></i> Tg: <b><?= htmlspecialchars($rp['staff_name']) ?></b></span>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } 
                        if($count_pending == 0) {
                            echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:var(--muted);'><i class='fas fa-check-circle fa-2x' style='color:#86efac; margin-bottom:10px; display:block;'></i>Semua pesanan sudah terkonfirmasi lunas!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ====================================================
             SUPER ADMIN: SECTION B — PENUGASAN & VERIFIKASI
        ==================================================== -->
        <div class="card" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header" style="background:linear-gradient(90deg,#f0fdf4,#fff); border-left:4px solid #22c55e;">
                <i class="fas fa-tasks" style="color:#22c55e;"></i> 
                Section B — Penugasan & Verifikasi Hasil
                <span style="font-size:0.8rem; font-weight:400; color:var(--muted); margin-left:8px;">Pesanan LUNAS. Assign ulang jika perlu, lalu verifikasi hasil kerja staff.</span>
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Invoice & Klien</th>
                            <th>Tema & Tgl Acara</th>
                            <th>Staff Bertugas</th>
                            <th>Status Pengerjaan</th>
                            <th>Aksi Assign & Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_lunas = mysqli_query($conn, "SELECT p.*, k.nama_tema, a.username as staff_name 
                            FROM pesanan p 
                            LEFT JOIN katalog_tema k ON p.tema_id = k.id 
                            LEFT JOIN admin_users a ON p.admin_id = a.id 
                            WHERE p.status_pembayaran = 'Lunas' AND p.status_pengerjaan NOT IN ('Selesai')
                            ORDER BY 
                                CASE p.status_pengerjaan 
                                    WHEN 'Menunggu Verifikasi' THEN 1 
                                    WHEN 'Perlu Revisi' THEN 2
                                    WHEN 'Sedang Dikerjakan' THEN 3 
                                    ELSE 4 END,
                                p.id DESC");
                        
                        $count_lunas = 0;
                        while($rl = mysqli_fetch_assoc($q_lunas)) { 
                            $count_lunas++;
                            $badge_werk = 'badge-yellow';
                            if($rl['status_pengerjaan'] == 'Menunggu Verifikasi') $badge_werk = 'badge-blue';
                            if($rl['status_pengerjaan'] == 'Sedang Dikerjakan') $badge_werk = 'badge-green';
                            if($rl['status_pengerjaan'] == 'Perlu Revisi') $badge_werk = 'badge-red';
                        ?>
                        <tr style="<?= $rl['status_pengerjaan'] == 'Menunggu Verifikasi' ? 'background:#eff6ff;' : '' ?>">
                            <td>
                                <b style="color:var(--primary);"><?= $rl['invoice'] ?></b><br>
                                <span style="font-size:0.9rem;"><?= htmlspecialchars($rl['nama_pemesan']) ?></span><br>
                                <a href="https://wa.me/<?= $rl['no_whatsapp'] ?>" target="_blank" style="color:#25D366; text-decoration:none; font-size:0.8rem;"><i class="fab fa-whatsapp"></i> <?= $rl['no_whatsapp'] ?></a>
                            </td>
                            <td>
                                <b><?= htmlspecialchars($rl['nama_tema']) ?></b><br>
                                <span style="font-size:0.8rem; color:var(--muted);">Tgl: <?= date('d M Y', strtotime($rl['tanggal_acara'])) ?></span>
                            </td>
                            <td>
                                <?php if($rl['staff_name']) { ?>
                                    <span class="badge badge-blue"><i class="fas fa-user"></i> <?= htmlspecialchars($rl['staff_name']) ?></span>
                                <?php } else { ?>
                                    <span style="color:var(--muted); font-size:0.85rem;"><i class="fas fa-user-slash"></i> Belum di-assign</span>
                                <?php } ?>
                            </td>
                            <td><span class="badge <?= $badge_werk ?>"><?= $rl['status_pengerjaan'] ?></span></td>
                            <td>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    <!-- Re-assign Staff -->
                                    <form method="POST" action="admin.php?menu=tugas" style="display:flex; gap:6px; align-items:center;">
                                        <input type="hidden" name="id_pesanan" value="<?= $rl['id'] ?>">
                                        <select name="admin_assign_id" class="form-control" style="padding:5px; flex:1; font-size:0.8rem; border-color:#22c55e;">
                                            <option value="">-- Ganti Staff --</option>
                                            <?php foreach($list_all_staff as $sad){ ?>
                                                <option value="<?= $sad['id'] ?>" <?= ($rl['admin_id'] == $sad['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($sad['username']) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" name="update_assign" class="btn-action btn-secondary" title="Simpan" style="padding:5px 10px;">
                                            <i class="fas fa-user-edit"></i>
                                        </button>
                                    </form>
                                    <?php if($rl['status_pengerjaan'] == 'Menunggu Verifikasi') { 
                                        // Cek bukti hasil kerja staff
                                        $inv_l = $rl['invoice'];
                                        $q_img_l = mysqli_query($conn, "SELECT screenshot_path FROM audit_logs WHERE target_id='$inv_l' AND action_type='Upload Tugas' AND screenshot_path IS NOT NULL ORDER BY id DESC LIMIT 1");
                                        $img_path_l = "";
                                        if(mysqli_num_rows($q_img_l) > 0) {
                                            $img_path_l = mysqli_fetch_assoc($q_img_l)['screenshot_path'];
                                        }
                                    ?>
                                    <div style="display:flex; gap:6px; flex-wrap:wrap; border-top:1px dashed var(--border); padding-top:6px;">
                                        <?php if($img_path_l) { ?>
                                            <a href="<?= $img_path_l ?>" target="_blank" class="btn-action btn-secondary" style="font-size:0.8rem;"><i class="fas fa-image"></i> Lihat Hasil</a>
                                        <?php } ?>
                                        <form method="POST" action="admin.php?menu=tugas" onsubmit="return confirm('Verifikasi dan tandai tugas ini selesai?');" style="margin:0;">
                                            <input type="hidden" name="id_pesanan" value="<?= $rl['id'] ?>">
                                            <button type="submit" name="verifikasi_tugas" class="btn-action btn-primary" style="font-size:0.8rem; background:#22c55e;">
                                                <i class="fas fa-check-circle"></i> Verifikasi Selesai
                                            </button>
                                        </form>
                                        <button type="button" onclick="openRevisiModal(<?= $rl['id'] ?>)" class="btn-action" style="font-size:0.8rem; background:#f97316; color:white;">
                                            <i class="fas fa-undo"></i> Kembalikan Revisi
                                        </button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } 
                        if($count_lunas == 0) {
                            echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:var(--muted);'><i class='fas fa-inbox fa-2x' style='color:#cbd5e1; margin-bottom:10px; display:block;'></i>Belum ada pesanan lunas yang perlu dikerjakan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Konfirmasi Pembayaran (untuk Section A) -->
        <div id="modalPayStatus" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
            <div style="background:white; padding:30px; border-radius:15px; width:420px; max-width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                <h3 style="margin-bottom:5px; color:var(--text);"><i class="fas fa-coins" style="color:#f97316;"></i> Konfirmasi Status Pembayaran</h3>
                <p style="color:var(--muted); font-size:0.85rem; margin-bottom:20px;">Ubah status pembayaran pesanan ini. Jika ditandai <b>Lunas</b>, pesanan akan otomatis pindah ke Section B.</p>
                <form method="POST" action="admin.php?menu=tugas" enctype="multipart/form-data">
                    <input type="hidden" name="id_pesanan" id="paymod_id_pesanan">
                    <div class="form-group">
                        <label>Status Pembayaran Baru</label>
                        <select name="status_bayar" id="paymod_status" class="form-control" required>
                            <option value="Belum Bayar">❌ Belum Bayar</option>
                            <option value="Menunggu Konfirmasi">🕐 Menunggu Konfirmasi</option>
                            <option value="Lunas">✅ Lunas (Konfirmasi Diterima)</option>
                        </select>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                        <button type="button" onclick="closePayModal()" class="btn-action btn-secondary">Batal</button>
                        <button type="submit" name="update_status_pesanan" class="btn-action btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
        function openPayModal(id, status) {
            document.getElementById('paymod_id_pesanan').value = id;
            document.getElementById('paymod_status').value = status;
            document.getElementById('modalPayStatus').style.display = 'flex';
        }
        function closePayModal() {
            document.getElementById('modalPayStatus').style.display = 'none';
        }
        </script>

        <!-- Modal Kembalikan Revisi (Section B) -->
        <div id="modalRevisi" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
            <div style="background:white; padding:30px; border-radius:15px; width:480px; max-width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                <h3 style="margin-bottom:5px; color:var(--text);"><i class="fas fa-undo" style="color:#f97316;"></i> Kembalikan Tugas untuk Revisi</h3>
                <p style="color:var(--muted); font-size:0.85rem; margin-bottom:18px;">Tugas akan dikembalikan ke staff. Sertakan catatan apa yang perlu diperbaiki.</p>
                <form method="POST" action="admin.php?menu=tugas">
                    <input type="hidden" name="id_pesanan" id="revmod_id_pesanan">
                    <div class="form-group">
                        <label style="font-weight:600; color:var(--text);">📝 Catatan / Saran Revisi <span style="color:#ef4444;">*</span></label>
                        <textarea name="catatan_revisi" id="revmod_catatan" class="form-control" rows="5" required
                            placeholder="Contoh: Foto background terlalu gelap, tolong ganti warna font nama tamu menjadi putih, dll."
                            style="resize:vertical; margin-top:6px; font-size:0.9rem; line-height:1.5;"></textarea>
                        <span class="text-hint">Tuliskan detail apa yang perlu direvisi oleh staff.</span>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                        <button type="button" onclick="closeRevisiModal()" class="btn-action btn-secondary">Batal</button>
                        <button type="submit" name="kembalikan_revisi" class="btn-action" style="background:#f97316; color:white;">
                            <i class="fas fa-paper-plane"></i> Kirim Revisi ke Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script>
        function openRevisiModal(id) {
            document.getElementById('revmod_id_pesanan').value = id;
            document.getElementById('revmod_catatan').value = '';
            document.getElementById('modalRevisi').style.display = 'flex';
        }
        function closeRevisiModal() {
            document.getElementById('modalRevisi').style.display = 'none';
        }
        </script>

        <?php } else { 
            // ========================================
            // TAMPILAN STAFF: Daftar Tugas Aktif Mereka
            // ======================================== 
        ?>
        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header"><i class="fas fa-clipboard-list"></i> Daftar Tugas Aktif Anda</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Klien & Tema</th>
                            <th>Status Pengerjaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $my_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
                        // Staff melihat semua tugas aktif: Sedang Dikerjakan DAN Perlu Revisi (belum Selesai/Menunggu Verifikasi)
                        $q_tugas_staff = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id WHERE p.admin_id='$my_id' AND p.status_pembayaran='Lunas' AND p.status_pengerjaan NOT IN ('Selesai','Menunggu Verifikasi') ORDER BY CASE p.status_pengerjaan WHEN 'Perlu Revisi' THEN 1 ELSE 2 END, p.id DESC");
                        
                        while($rt = mysqli_fetch_assoc($q_tugas_staff)) { 
                            $is_revisi = ($rt['status_pengerjaan'] == 'Perlu Revisi');
                        ?>
                        <tr style="<?= $is_revisi ? 'background:#fff1f2;' : '' ?>">
                            <td style="font-weight:600; color:var(--primary);"><?= $rt['invoice'] ?></td>
                            <td>
                                <b><?= htmlspecialchars($rt['nama_pemesan']) ?></b><br>
                                <span style="font-size:0.85rem; color:var(--muted);"><i class="fas fa-palette"></i> <?= htmlspecialchars($rt['nama_tema']) ?></span><br>
                                <span style="font-size:0.8rem; color:var(--muted);">Tgl Acara: <?= date('d M Y', strtotime($rt['tanggal_acara'])) ?></span>
                                <?php if($is_revisi && !empty($rt['catatan_revisi'])) { ?>
                                <div style="margin-top:8px; background:#fee2e2; border-left:3px solid #ef4444; border-radius:6px; padding:10px 12px;">
                                    <div style="font-size:0.78rem; font-weight:700; color:#991b1b; margin-bottom:4px;"><i class="fas fa-exclamation-triangle"></i> CATATAN REVISI DARI SUPER ADMIN:</div>
                                    <div style="font-size:0.85rem; color:#7f1d1d; white-space:pre-wrap;"><?= htmlspecialchars($rt['catatan_revisi']) ?></div>
                                </div>
                                <?php } ?>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column; gap:6px;">
                                    <?php if($rt['status_pengerjaan'] == 'Belum Dimulai') { ?>
                                        <span class="badge badge-yellow"><i class="fas fa-clock"></i> Belum Dimulai</span>
                                        <button onclick="updateProjectStatus(<?= $rt['id'] ?>, 'Sedang Dikerjakan')" class="btn-action" style="font-size:0.7rem; background:#3b82f6; color:#fff; border:none; padding:4px 8px;">
                                            <i class="fas fa-play"></i> Mulai Kerjakan
                                        </button>
                                    <?php } elseif($rt['status_pengerjaan'] == 'Sedang Dikerjakan' || $rt['status_pengerjaan'] == 'Dikerjakan') { ?>
                                        <span class="badge badge-blue"><i class="fas fa-tools"></i> Sedang Dikerjakan</span>
                                        <span style="font-size:0.7rem; color:var(--muted); font-style:italic;">Update otomatis ke klien</span>
                                    <?php } elseif($is_revisi) { ?>
                                        <span class="badge badge-red"><i class="fas fa-redo"></i> Perlu Revisi</span>
                                        <button onclick="updateProjectStatus(<?= $rt['id'] ?>, 'Sedang Dikerjakan (Revisi)')" class="btn-action" style="font-size:0.7rem; background:#f97316; color:#fff; border:none; padding:4px 8px;">
                                            <i class="fas fa-play"></i> Mulai Revisi
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                            <td>
                                <button type="button" onclick="openStaffUploadModal(<?= $rt['id'] ?>)" class="btn-action <?= $is_revisi ? '' : 'btn-primary' ?>" style="<?= $is_revisi ? 'background:#f97316; color:white;' : '' ?>">
                                    <i class="fas fa-<?= $is_revisi ? 'redo' : 'upload' ?>"></i> 
                                    <?= $is_revisi ? 'Upload Ulang (Revisi)' : 'Upload Tugas Selesai' ?>
                                </button>
                            </td>
                        </tr>
                        <?php } 
                        if(mysqli_num_rows($q_tugas_staff) == 0){
                            echo "<tr><td colspan='4' style='text-align:center; padding:30px; color:var(--muted);'><i class='fas fa-check-circle fa-2x' style='margin-bottom:10px; color:#86efac; display:block;'></i>Tidak ada tugas aktif. Tunggu penugasan dari Super Admin.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================
             STAFF: CHAT PANEL - Chat dengan Klien Premium
        ============================================================ -->
        <?php
        // Ambil semua pesanan yang ada klien premium & di-assign ke staff ini
        $q_chat_pesanan = mysqli_query($conn, "SELECT p.id, p.invoice, p.nama_pemesan, kp.id as kp_id 
            FROM pesanan p 
            JOIN klien_premium kp ON kp.pesanan_id = p.id AND kp.is_active = 1
            WHERE p.admin_id = '$my_id' AND p.status_pembayaran = 'Lunas'
            ORDER BY p.id DESC");
        $chat_pesanan_list = [];
        while ($cp = mysqli_fetch_assoc($q_chat_pesanan)) { $chat_pesanan_list[] = $cp; }
        $active_chat_pid = isset($_GET['cid']) ? (int)$_GET['cid'] : (empty($chat_pesanan_list) ? 0 : $chat_pesanan_list[0]['id']);
        $active_chat_msgs = [];
        $active_chat_info = null;
        $last_chat_id = 0;
        if ($active_chat_pid > 0) {
            foreach ($chat_pesanan_list as $cpl) { if ($cpl['id'] == $active_chat_pid) { $active_chat_info = $cpl; break; } }
            $q_msgs = mysqli_query($conn, "SELECT id, pengirim, nama_pengirim, pesan, gambar_path, created_at FROM pesan_proyek WHERE pesanan_id='$active_chat_pid' ORDER BY id ASC LIMIT 100");
            while ($m = mysqli_fetch_assoc($q_msgs)) { $active_chat_msgs[] = $m; $last_chat_id = $m['id']; }
        }
        ?>
        <div class="card" data-aos="fade-up" data-aos-delay="350" style="margin-top:30px; overflow:visible;">
            <div class="card-header" style="background:linear-gradient(90deg,#1A1614,#2d2520); color:#fff; border-left:4px solid #D4AF37; padding:16px 22px;">
                <i class="fas fa-comments" style="color:#D4AF37;"></i>
                Chat Klien Premium
                <span style="font-size:0.8rem; font-weight:400; margin-left:8px; opacity:0.7;">Pilih klien di bawah untuk memulai percakapan</span>
            </div>
            <div style="display:flex; height:520px; overflow:hidden;">
                <!-- Sidebar: Daftar Klien -->
                <div style="width:220px; border-right:1px solid var(--border); background:#fafaf9; flex-shrink:0; overflow-y:auto;">
                    <?php if (empty($chat_pesanan_list)): ?>
                    <div style="padding:30px 15px; text-align:center; color:var(--muted); font-size:0.82rem;">
                        <i class="fas fa-user-slash fa-2x" style="display:block; margin-bottom:10px; opacity:0.3;"></i>
                        Belum ada klien premium di tugas Anda.
                    </div>
                    <?php else: ?>
                    <?php foreach ($chat_pesanan_list as $cpl):
                        $is_active_c = ($cpl['id'] == $active_chat_pid);
                        $unread = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM pesan_proyek WHERE pesanan_id='".$cpl['id']."' AND pengirim='klien'"))['c'];
                    ?>
                    <a href="?menu=tugas&cid=<?= $cpl['id'] ?>" style="display:flex; align-items:center; gap:10px; padding:14px 16px; border-bottom:1px solid var(--border); text-decoration:none; background:<?= $is_active_c ? 'var(--primary)' : 'transparent' ?>; color:<?= $is_active_c ? '#fff' : 'var(--text)' ?>; transition:0.2s;">
                        <div style="width:38px; height:38px; border-radius:50%; background:<?= $is_active_c ? 'rgba(255,255,255,0.15)' : 'var(--primary)' ?>; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:0.9rem; flex-shrink:0;">
                            <?= strtoupper(substr($cpl['nama_pemesan'], 0, 1)) ?>
                        </div>
                        <div style="min-width:0; flex:1;">
                            <div style="font-weight:600; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars(explode(' ', $cpl['nama_pemesan'])[0]) ?></div>
                            <div style="font-size:0.72rem; opacity:0.7;"><?= htmlspecialchars($cpl['invoice']) ?></div>
                        </div>
                        <?php if ($unread > 0): ?>
                        <div style="background:#ef4444; color:#fff; border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; flex-shrink:0;"><?= $unread ?></div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Main Chat Area -->
                <div style="flex:1; display:flex; flex-direction:column; min-width:0; background:#fff;">
                    <?php if ($active_chat_info): ?>
                    <!-- Chat Header -->
                    <div style="padding:14px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:12px; background:#fff; flex-shrink:0;">
                        <div style="width:40px; height:40px; border-radius:50%; background:var(--primary); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700;">
                            <?= strtoupper(substr($active_chat_info['nama_pemesan'], 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:0.95rem;"><?= htmlspecialchars($active_chat_info['nama_pemesan']) ?></div>
                            <div style="font-size:0.75rem; color:var(--muted);"><?= htmlspecialchars($active_chat_info['invoice']) ?> · Klien Premium</div>
                        </div>
                        <div style="margin-left:auto; display:flex; align-items:center; gap:6px; font-size:0.78rem; color:#16a34a;">
                            <span style="width:8px; height:8px; background:#16a34a; border-radius:50%; display:inline-block;"></span> Online
                        </div>
                    </div>

                    <!-- Messages -->
                    <div id="staffChatBody" style="flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:12px; background:linear-gradient(to bottom, #fdfcfb, #fff);">
                        <?php if (empty($active_chat_msgs)): ?>
                        <div style="text-align:center; padding:60px 20px; color:var(--muted);">
                            <i class="fas fa-comment-dots fa-2x" style="display:block; margin-bottom:12px; opacity:0.3;"></i>
                            <p style="font-size:0.88rem;">Belum ada pesan. Kirim salam ke klien!</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($active_chat_msgs as $m):
                            $is_admin = ($m['pengirim'] === 'admin');
                            $time = date('H:i', strtotime($m['created_at']));
                            $date = date('d M', strtotime($m['created_at']));
                        ?>
                        <div style="display:flex; gap:8px; flex-direction:<?= $is_admin ? 'row-reverse' : 'row' ?>; align-items:flex-end;">
                            <div style="width:32px; height:32px; border-radius:50%; background:<?= $is_admin ? 'var(--primary)' : '#D4AF37' ?>; display:flex; align-items:center; justify-content:center; color:<?= $is_admin ? '#fff' : 'var(--primary)' ?>; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                <?= strtoupper(substr($m['nama_pengirim'], 0, 1)) ?>
                            </div>
                            <div style="max-width:60%;">
                                <?php if (!empty($m['gambar_path'])): ?>
                                <div style="margin-bottom:4px;">
                                    <a href="/embunvisual/<?= htmlspecialchars($m['gambar_path']) ?>" target="_blank">
                                        <img src="/embunvisual/<?= htmlspecialchars($m['gambar_path']) ?>" style="max-width:200px; max-height:160px; border-radius:12px; border:2px solid <?= $is_admin ? 'var(--primary)' : 'var(--border)' ?>; cursor:zoom-in; display:block;">
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($m['pesan'])): ?>
                                <div style="background:<?= $is_admin ? 'var(--primary)' : '#f0f0ef' ?>; color:<?= $is_admin ? '#fff' : 'var(--text)' ?>; padding:10px 14px; border-radius:16px; <?= $is_admin ? 'border-bottom-right-radius:4px;' : 'border-bottom-left-radius:4px;' ?> font-size:0.875rem; line-height:1.55; word-break:break-word;">
                                    <?= nl2br(htmlspecialchars($m['pesan'])) ?>
                                </div>
                                <?php endif; ?>
                                <div style="font-size:0.68rem; color:var(--muted); margin-top:3px; text-align:<?= $is_admin ? 'right' : 'left' ?>;">
                                    <?= htmlspecialchars($m['nama_pengirim']) ?> · <?= $time ?>, <?= $date ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Image Preview -->
                    <div id="staffImgPreview" style="display:none; padding:8px 16px; background:#f8f9fa; border-top:1px solid var(--border); flex-shrink:0;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img id="staffImgThumb" style="height:50px; border-radius:6px; border:1px solid var(--border);">
                            <span id="staffImgName" style="font-size:0.82rem; color:var(--muted); flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></span>
                            <button onclick="clearStaffImg()" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:1.1rem; padding:4px;">✕</button>
                        </div>
                    </div>

                    <!-- Emoji Picker Popup -->
                    <div id="staffEmojiPicker" style="display:none; position:absolute; bottom:70px; left:16px; background:#fff; border:1px solid var(--border); border-radius:16px; padding:14px; box-shadow:0 12px 40px rgba(0,0,0,0.12); z-index:500; width:300px;">
                        <div style="display:grid; grid-template-columns:repeat(8,1fr); gap:4px; font-size:1.3rem; max-height:180px; overflow-y:auto;">
                            <?php
                            $emojis = ['😊','😂','🥰','😍','🤩','😎','🥳','🤗','😇','🙏','👍','❤️','🔥','✨','🎉','🎊','💯','🌹','🌸','🌟','💪','🤝','👏','🎵','📸','🎨','💌','📋','✅','⏰','🏃','🌙','☀️','🌈','🦋','🍀','🌺','💎','🏆','🎁','📱','💻','🔑','🎯','💰','📅','🗓️','⭐','🌿'];
                            foreach ($emojis as $e) {
                                echo "<button onclick=\"insertEmoji('staffChatInput','$e')\" style=\"background:none;border:none;cursor:pointer;padding:4px;border-radius:6px;font-size:1.2rem;line-height:1;\" title=\"$e\">$e</button>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Input Bar -->
                    <div style="padding:12px 16px; border-top:1px solid var(--border); background:#fff; flex-shrink:0; position:relative;">
                        <div style="display:flex; gap:10px; align-items:center;">
                            <!-- Emoji button -->
                            <button id="staffEmojiBtn" onclick="toggleStaffEmoji(event)" title="Emoji" style="width:38px; height:38px; border-radius:50%; background:#f0f0ef; border:none; cursor:pointer; font-size:1.1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background 0.2s;">😊</button>
                            <!-- Image button -->
                            <label for="staffImageInput" title="Kirim Gambar" style="width:38px; height:38px; border-radius:50%; background:#f0f0ef; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:0.9rem; color:var(--muted); flex-shrink:0; transition:background 0.2s;">
                                <i class="fas fa-image"></i>
                            </label>
                            <input type="file" id="staffImageInput" accept="image/*" style="display:none;" onchange="previewStaffImg(this)">
                            <!-- Text Input -->
                            <input type="text" id="staffChatInput" placeholder="Ketik pesan..." autocomplete="off"
                                style="flex:1; padding:11px 18px; border:1px solid var(--border); border-radius:50px; font-size:0.9rem; outline:none; transition:all 0.25s; background:#f8f9f8;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.background='#fff';"
                                onblur="this.style.borderColor='var(--border)'; this.style.background='#f8f9f8';"
                                onkeydown="if(event.key==='Enter' && !event.shiftKey){kirimStaffChat();event.preventDefault();}">
                            <!-- Send button -->
                            <button onclick="kirimStaffChat()" style="width:42px; height:42px; border-radius:50%; background:var(--primary); border:none; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.95rem; flex-shrink:0; transition:background 0.25s;">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>

                    <?php else: ?>
                    <div style="flex:1; display:flex; align-items:center; justify-content:center; flex-direction:column; color:var(--muted); background:#fafaf9;">
                        <i class="fas fa-comments fa-3x" style="margin-bottom:15px; opacity:0.2;"></i>
                        <p style="font-size:0.9rem;">Pilih klien dari daftar untuk membuka chat.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
        // ── Staff Chat Logic ──
        const STAFF_PID      = <?= $active_chat_pid ?>;
        const STAFF_NAME     = <?= json_encode($_SESSION['username'] ?? 'Staff') ?>;
        let   staffLastId    = <?= $last_chat_id ?>;
        let   staffImgFile   = null;

        function scrollStaffChat() {
            const b = document.getElementById('staffChatBody');
            if (b) b.scrollTop = b.scrollHeight;
        }
        scrollStaffChat();

        function insertEmoji(inputId, emoji) {
            const inp = document.getElementById(inputId);
            if (!inp) return;
            const pos = inp.selectionStart;
            inp.value = inp.value.slice(0, pos) + emoji + inp.value.slice(pos);
            inp.selectionStart = inp.selectionEnd = pos + emoji.length;
            inp.focus();
            document.getElementById('staffEmojiPicker').style.display = 'none';
        }

        function toggleStaffEmoji(e) {
            e.stopPropagation();
            const p = document.getElementById('staffEmojiPicker');
            p.style.display = p.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', () => {
            const p = document.getElementById('staffEmojiPicker');
            if (p) p.style.display = 'none';
        });

        function previewStaffImg(input) {
            if (input.files && input.files[0]) {
                staffImgFile = input.files[0];
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('staffImgThumb').src = e.target.result;
                    document.getElementById('staffImgName').textContent = staffImgFile.name;
                    document.getElementById('staffImgPreview').style.display = 'block';
                };
                reader.readAsDataURL(staffImgFile);
            }
        }

        function clearStaffImg() {
            staffImgFile = null;
            document.getElementById('staffImageInput').value = '';
            document.getElementById('staffImgPreview').style.display = 'none';
        }

        function buildMsgHtml(m) {
            const isAdmin = m.pengirim === 'admin';
            const init = m.nama_pengirim.charAt(0).toUpperCase();
            const t = new Date(m.created_at).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
            const d = new Date(m.created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short'});
            const escaped = (m.pesan||'').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
            const imgHtml = m.gambar_path ? `<div style="margin-bottom:4px;"><a href="/embunvisual/${m.gambar_path}" target="_blank"><img src="/embunvisual/${m.gambar_path}" style="max-width:200px;max-height:160px;border-radius:12px;border:2px solid ${isAdmin?'var(--primary)':'var(--border)'};cursor:zoom-in;display:block;"></a></div>` : '';
            const txtHtml = escaped ? `<div style="background:${isAdmin?'var(--primary)':'#f0f0ef'};color:${isAdmin?'#fff':'var(--text)'};padding:10px 14px;border-radius:16px;${isAdmin?'border-bottom-right-radius:4px;':'border-bottom-left-radius:4px;'}font-size:0.875rem;line-height:1.55;word-break:break-word;">${escaped}</div>` : '';
            return `<div style="display:flex;gap:8px;flex-direction:${isAdmin?'row-reverse':'row'};align-items:flex-end;">
                        <div style="width:32px;height:32px;border-radius:50%;background:${isAdmin?'var(--primary)':'#D4AF37'};display:flex;align-items:center;justify-content:center;color:${isAdmin?'#fff':'var(--primary)'};font-size:0.75rem;font-weight:700;flex-shrink:0;">${init}</div>
                        <div style="max-width:60%;">${imgHtml}${txtHtml}<div style="font-size:0.68rem;color:var(--muted);margin-top:3px;text-align:${isAdmin?'right':'left'};">${m.nama_pengirim} · ${t}, ${d}</div></div>
                    </div>`;
        }

        function appendStaffMsg(m) {
            const b = document.getElementById('staffChatBody');
            if (!b) return;
            const empty = b.querySelector('div[style*="fa-comment-dots"]');
            if (empty) b.innerHTML = '';
            b.insertAdjacentHTML('beforeend', buildMsgHtml(m));
            scrollStaffChat();
        }

        function kirimStaffChat() {
            if (STAFF_PID === 0) return;
            const inp = document.getElementById('staffChatInput');
            const pesan = inp.value.trim();
            if (!pesan && !staffImgFile) { inp.focus(); return; }

            const fd = new FormData();
            fd.append('pid', STAFF_PID);
            fd.append('pesan', pesan);
            if (staffImgFile) fd.append('gambar', staffImgFile);

            inp.value = '';
            clearStaffImg();

            fetch('admin_premium/api_admin_chat.php?action=send', { method:'POST', body: fd })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok') {
                        appendStaffMsg({
                            id: Date.now(), pengirim:'admin', nama_pengirim: STAFF_NAME,
                            pesan: pesan, gambar_path: d.gambar_path || null,
                            created_at: new Date().toISOString()
                        });
                    }
                }).catch(console.error);
        }

        // Poll for new client messages every 5s
        function pollStaffChat() {
            if (STAFF_PID === 0) return;
            fetch(`admin_premium/api_admin_chat.php?action=fetch&pid=${STAFF_PID}&since=${staffLastId}`)
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok') {
                        d.messages.forEach(m => {
                            if (m.pengirim !== 'admin') { appendStaffMsg(m); }
                            staffLastId = Math.max(staffLastId, parseInt(m.id));
                        });
                    }
                }).catch(console.error);
        }
        setInterval(pollStaffChat, 5000);
        </script>


        <div id="modalStaffUpload" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
            <div style="background:white; padding:30px; border-radius:15px; width:400px; max-width:90%;">
                <h3 style="margin-bottom:20px; color:var(--text);"><i class="fas fa-cloud-upload-alt" style="color:var(--primary);"></i> Upload Bukti Selesai</h3>
                <form method="POST" action="admin.php?menu=tugas" enctype="multipart/form-data">
                    <input type="hidden" name="id_pesanan" id="staff_modal_id_pesanan">
                    <div class="form-group" style="background:#f8fafc; padding:15px; border-radius:10px; border:1px dashed #cbd5e1;">
                        <label style="color:var(--text); font-size:0.85rem;"><i class="fas fa-file-image" style="color:#0284c7;"></i> Screenshot Hasil Pengerjaan</label>
                        <input type="file" name="bukti_selesai" class="form-control" accept="image/*" required style="margin-top:10px; padding:8px; font-size:0.85rem;">
                        <span class="text-hint">Hanya file JPG/PNG max 2MB.</span>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:25px;">
                        <button type="button" onclick="closeStaffUploadModal()" class="btn-action btn-secondary">Batal</button>
                        <button type="submit" name="update_tugas_selesai" class="btn-action btn-primary"><i class="fas fa-paper-plane"></i> Kirim ke Super Admin</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function openStaffUploadModal(id) {
                document.getElementById('staff_modal_id_pesanan').value = id;
                document.getElementById('modalStaffUpload').style.display = 'flex';
            }
            function closeStaffUploadModal() {
                document.getElementById('modalStaffUpload').style.display = 'none';
            }
        </script>
        <?php
        // ── Auto popup jika ada tugas "Perlu Revisi" untuk staff ini
        $q_revisi_notif = mysqli_query($conn, "SELECT p.invoice, p.catatan_revisi, p.nama_pemesan FROM pesanan p WHERE p.admin_id='$my_id' AND p.status_pengerjaan='Perlu Revisi' AND p.catatan_revisi != '' ORDER BY p.id DESC");
        $list_revisi = [];
        while($rv = mysqli_fetch_assoc($q_revisi_notif)) { $list_revisi[] = $rv; }
        if(!empty($list_revisi)) {
            $html_isi = '';
            foreach($list_revisi as $rv) {
                $catatan_escaped = htmlspecialchars($rv['catatan_revisi']);
                $invoice_escaped = htmlspecialchars($rv['invoice']);
                $client_escaped  = htmlspecialchars($rv['nama_pemesan']);
                $html_isi .= "<div style='background:#fff7ed; border-left:4px solid #f97316; border-radius:8px; padding:12px 14px; margin-bottom:12px; text-align:left;'>"
                           . "<div style='font-weight:700; color:#c2410c; font-size:0.85rem; margin-bottom:6px;'>📄 Invoice: $invoice_escaped &nbsp;|&nbsp; $client_escaped</div>"
                           . "<div style='font-size:0.9rem; color:#1c1917; white-space:pre-wrap;'>$catatan_escaped</div>"
                           . "</div>";
            }
            $jumlah = count($list_revisi);
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: '⚠️ <?= $jumlah ?> Tugas Perlu Direvisi!',
                html: '<p style="color:#64748b; margin-bottom:14px; font-size:0.9rem;">Super Admin telah mengembalikan tugas berikut. Harap perbaiki dan upload ulang.</p>'
                    + '<?= addslashes($html_isi) ?>',
                confirmButtonText: 'Saya Mengerti, Segera Diperbaiki',
                confirmButtonColor: '#f97316',
                allowOutsideClick: false,
                width: 560,
            });
        });
        </script>
        <?php } ?>
        <?php } ?>


        <?php } elseif($menu == 'katalog') { 
            // Cek apakah sedang dalam mode edit
            $edit_mode = false;
            $edit_data = null;
            if(isset($_GET['edit_id'])){
                $edit_id = mysqli_real_escape_string($conn, $_GET['edit_id']);
                $q_edit = mysqli_query($conn, "SELECT * FROM katalog_tema WHERE id='$edit_id'");
                $edit_data = mysqli_fetch_assoc($q_edit);
                if($edit_data) $edit_mode = true;
            }
        ?>
        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title">Katalog Tema Beranda</h1>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama tema...">
            </div>
        </div>

        <div style="display:flex; gap:30px; align-items: flex-start;">
            
            <div class="card" data-aos="fade-right" data-aos-delay="200" style="flex: 1; position: sticky; top: 40px; <?= $edit_mode ? 'border: 2px solid var(--primary);' : '' ?>">
                <div class="card-header" <?= $edit_mode ? 'style="background: var(--primary); color: white;"' : '' ?>>
                    <i class="fas <?= $edit_mode ? 'fa-edit' : 'fa-plus-circle' ?>"></i> 
                    <?= $edit_mode ? 'Edit Tema: '.$edit_data['nama_tema'] : 'Tambah Tema Baru' ?>
                </div>
                <form method="POST" action="admin.php?menu=katalog" style="padding: 25px;">
                    
                    <?php if($edit_mode) { ?>
                        <input type="hidden" name="id_tema" value="<?= $edit_data['id'] ?>">
                    <?php } ?>

                    <div class="form-group">
                        <label>Nama Tema</label>
                        <input type="text" name="nama" class="form-control" value="<?= $edit_mode ? $edit_data['nama_tema'] : '' ?>" placeholder="Msl: Elegant Gold" required>
                    </div>
                    <div style="display:flex; gap:15px;">
                        <div class="form-group" style="flex:1;">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="Premium" <?= ($edit_mode && $edit_data['kategori'] == 'Premium') ? 'selected' : '' ?>>Premium</option>
                                <option value="Basic" <?= ($edit_mode && $edit_data['kategori'] == 'Basic') ? 'selected' : '' ?>>Basic</option>
                                <option value="Exclusive" <?= ($edit_mode && $edit_data['kategori'] == 'Exclusive') ? 'selected' : '' ?>>Exclusive</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Harga (Angka Saja)</label>
                            <input type="number" name="harga" class="form-control" value="<?= $edit_mode ? $edit_data['harga'] : '' ?>" placeholder="149000" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required><?= $edit_mode ? $edit_data['deskripsi'] : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>URL Gambar Thumbnail</label>
                        <input type="text" name="gambar_url" class="form-control" value="<?= $edit_mode ? $edit_data['gambar_url'] : '' ?>" placeholder="Msl: https://unsplash.com/..." required>
                    </div>
                    <div class="form-group">
                        <label>Link Demo Tema (URL / File)</label>
                        <input type="text" name="link_demo" class="form-control" value="<?= $edit_mode ? trim($edit_data['slug_demo']) : '' ?>" placeholder="Msl: tema_bali_nature.php" required>
                        <span class="text-hint">*Ketik nama file (cth: tema_sage.php) atau link website lengkap (cth: https://link.com)</span>
                    </div>

                    <?php if($edit_mode) { ?>
                        <div style="display:flex; gap:10px; margin-top:20px;">
                            <button type="submit" name="update_tema" class="btn-action btn-primary" style="flex:1; padding:12px; font-size:1rem; justify-content:center;">Simpan Perubahan</button>
                            <a href="admin.php?menu=katalog" class="btn-action btn-secondary" style="padding:12px; font-size:1rem; justify-content:center;">Batal</a>
                        </div>
                    <?php } else { ?>
                        <button type="submit" name="tambah_tema" class="btn-action btn-primary" style="width:100%; padding:12px; font-size:1rem; display:block; text-align:center;">Upload ke Website</button>
                    <?php } ?>
                </form>
            </div>

            <div class="card" data-aos="fade-left" data-aos-delay="300" style="flex: 2;">
                <div class="card-header">Tema Aktif</div>
                <table id="dataTable" style="font-size: 0.85rem;">
                    <tr><th>Nama Tema</th><th>Harga</th><th>Link Demo</th><th>Aksi</th></tr>
                    <?php 
                    $q_tema = mysqli_query($conn, "SELECT * FROM katalog_tema ORDER BY id DESC");
                    while($t = mysqli_fetch_assoc($q_tema)) { ?>
                    <tr <?= ($edit_mode && $edit_data['id'] == $t['id']) ? 'style="background:#f1f5f9;"' : '' ?>>
                        <td><b><?= $t['nama_tema'] ?></b><br><span style="color:var(--muted);"><?= $t['kategori'] ?></span></td>
                        <td>Rp <?= number_format($t['harga'],0,',','.') ?></td>
                        <td><a href="<?= trim($t['slug_demo']) ?>" target="_blank" style="color: #0284c7; text-decoration: none;"><i class="fas fa-external-link-alt"></i> <?= trim($t['slug_demo']) ?></a></td>
                        <td>
                            <div style="display:flex; gap:5px;">
                                <a href="admin.php?menu=katalog&edit_id=<?= $t['id'] ?>" class="btn-action btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <form method="POST" action="admin.php?menu=katalog" onsubmit="return confirmDelete(event, this, 'Yakin hapus tema dari katalog?');" style="margin:0;">
                                    <input type="hidden" name="tabel" value="katalog_tema">
                                    <input type="hidden" name="id_hapus" value="<?= $t['id'] ?>">
                                    <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

        <?php } elseif($menu == 'request') { ?>
        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title">Permintaan Desain Custom</h1>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari no WA atau Konsep...">
            </div>
        </div>

        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <table id="dataTable">
                <thead>
                    <tr><th>Tanggal</th><th>Nama Klien</th><th>Kontak WA</th><th>Estimasi Budget</th><th>Ide Konsep</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $q_req = mysqli_query($conn, "SELECT * FROM request_custom ORDER BY id DESC");
                    while($r = mysqli_fetch_assoc($q_req)) { ?>
                    <tr>
                        <td style="color:var(--muted);"><?= date('d/m/Y', strtotime($r['tanggal_request'])) ?></td>
                        <td><b><?= $r['nama_klien'] ?></b></td>
                        <td><a href="https://wa.me/<?= $r['no_whatsapp'] ?>" target="_blank" style="color:#25D366; text-decoration:none;"><i class="fab fa-whatsapp"></i> Hubungi</a></td>
                        <td><span class="badge badge-yellow"><?= $r['budget_estimasi'] ?></span></td>
                        <td style="max-width: 250px; line-height: 1.5;"><?= $r['deskripsi_konsep'] ?></td>
                        <td>
                            <form method="POST" action="admin.php?menu=request" onsubmit="return confirmDelete(event, this, 'Yakin ingin menghapus request ini?');">
                                <input type="hidden" name="tabel" value="request_custom">
                                <input type="hidden" name="id_hapus" value="<?= $r['id'] ?>">
                                <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php } elseif($menu == 'galeri') { 
            // Cek apakah mode Edit aktif
            $edit_gal_mode = false;
            $edit_gal_data = [];
            if(isset($_GET['edit_id'])) {
                $e_id = (int)$_GET['edit_id'];
                $q_edit = mysqli_query($conn, "SELECT * FROM galeri WHERE id='$e_id'");
                if(mysqli_num_rows($q_edit) > 0) {
                    $edit_gal_mode = true;
                    $edit_gal_data = mysqli_fetch_assoc($q_edit);
                }
            }
        ?>
        <div class="page-header" data-aos="fade-down">
            <h1 class="page-title">Pengaturan Galeri</h1>
        </div>

        <div style="display:flex; gap:30px; align-items: flex-start;">
            <div class="card" data-aos="fade-right" data-aos-delay="200" style="flex: 1; position: sticky; top: 40px; <?= $edit_gal_mode ? 'border: 2px solid var(--primary);' : '' ?>">
                <div class="card-header" <?= $edit_gal_mode ? 'style="background: var(--primary); color: white;"' : '' ?>><i class="fas fa-camera"></i> <?= $edit_gal_mode ? 'Edit Foto' : 'Tambah Foto Baru' ?></div>
                <form method="POST" action="admin.php?menu=galeri" enctype="multipart/form-data" style="padding: 25px;">
                    <?php if($edit_gal_mode): ?>
                        <input type="hidden" name="id_galeri" value="<?= $edit_gal_data['id'] ?>">
                        <div style="margin-bottom: 15px;">
                            <img src="<?= $edit_gal_data['gambar'] ?>" style="width:100px; height:70px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
                            <p style="font-size:0.8rem; color:var(--muted); margin-top:5px;">Foto saat ini. Biarkan kosong jika tidak ingin mengubah foto.</p>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Tipe Input Gambar</label>
                        <select name="tipe_input" id="tipe_input" class="form-control" onchange="toggleInputType()">
                            <option value="upload" <?= ($edit_gal_mode && $edit_gal_data['type'] == 'upload') ? 'selected' : '' ?>>Upload File (Maks 2MB)</option>
                            <option value="url" <?= ($edit_gal_mode && $edit_gal_data['type'] == 'url') ? 'selected' : '' ?>>Gunakan Link (URL)</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="input_upload" style="display: <?= (!$edit_gal_mode || ($edit_gal_data['type'] == 'upload')) ? 'block' : 'none' ?>;">
                        <label>Pilih File (JPG/PNG)</label>
                        <input type="file" name="gambar_file" class="form-control" accept="image/*" <?= $edit_gal_mode ? '' : 'required' ?>>
                    </div>
                    
                    <div class="form-group" id="input_url" style="display: <?= ($edit_gal_mode && $edit_gal_data['type'] == 'url') ? 'block' : 'none' ?>;">
                        <label>URL Gambar</label>
                        <input type="url" name="gambar_url" class="form-control" placeholder="Msl: https://source.unsplash.com/..." value="<?= ($edit_gal_mode && $edit_gal_data['type'] == 'url') ? trim($edit_gal_data['gambar']) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label>Caption Pendek (Opsional)</label>
                        <input type="text" name="caption" class="form-control" placeholder="Msl: Prewedding at Bali" value="<?= $edit_gal_mode ? $edit_gal_data['caption'] : '' ?>">
                    </div>
                    
                    <div style="display:flex; gap:15px;">
                        <div class="form-group" style="flex:1;">
                            <label>Nama Fotografer</label>
                            <input type="text" name="sumber_nama" class="form-control" placeholder="Msl: Teduh Visual" value="<?= $edit_gal_mode ? $edit_gal_data['sumber_nama'] : 'Teduh Visual' ?>">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Link Sosmed Fotografer</label>
                            <input type="text" name="sumber_link" class="form-control" placeholder="Link Instagram..." value="<?= $edit_gal_mode ? $edit_gal_data['sumber_link'] : 'https://instagram.com/teduh.visual' ?>">
                        </div>
                    </div>

                    <?php if($edit_gal_mode) { ?>
                        <div style="display:flex; gap:10px; margin-top:10px;">
                            <button type="submit" name="update_galeri" class="btn-action btn-primary" style="flex:1; padding:12px; font-size:1rem; justify-content:center;">Simpan Perubahan</button>
                            <a href="admin.php?menu=galeri" class="btn-action btn-secondary" style="padding:12px; font-size:1rem; justify-content:center;">Batal</a>
                        </div>
                    <?php } else { ?>
                        <button type="submit" name="tambah_galeri" class="btn-action btn-primary" style="width:100%; padding:12px; font-size:1rem; display:block; text-align:center;">Tambahkan ke Beranda</button>
                    <?php } ?>
                    
                </form>
            </div>

            <div class="card" data-aos="fade-left" data-aos-delay="300" style="flex: 2;">
                <div class="card-header"><i class="fas fa-images"></i> Daftar Foto Galeri</div>
                <table>
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Detail & Caption</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
                        while($g = mysqli_fetch_assoc($q_galeri)) { 
                            $img_src = ($g['type'] == 'upload') ? $g['gambar'] : $g['gambar'];
                        ?>
                        <tr>
                            <td style="width: 120px;">
                                <div style="width: 100px; height: 100px; border-radius: 8px; background-image: url('<?= $img_src ?>'); background-size: cover; background-position: center; border: 1px solid var(--border);"></div>
                            </td>
                            <td>
                                <b><?= !empty($g['caption']) ? htmlspecialchars($g['caption']) : '<i style="color:#A0A0A0;">Tanpa Caption</i>' ?></b><br>
                                <?php if(!empty($g['sumber_nama'])) { ?>
                                    <span style="font-size:0.85rem; color:var(--muted);">Sumber: <a href="<?= htmlspecialchars($g['sumber_link']) ?>" target="_blank" style="color:#0284c7; text-decoration:none;"><?= htmlspecialchars($g['sumber_nama']) ?></a></span><br>
                                <?php } ?>
                                <span class="badge <?= ($g['type'] == 'upload') ? 'badge-blue' : 'badge-green' ?>" style="margin-top: 5px; display:inline-block;">
                                    <i class="fas <?= ($g['type'] == 'upload') ? 'fa-upload' : 'fa-link' ?>"></i> 
                                    <?= ($g['type'] == 'upload') ? 'File Upload' : 'External URL' ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="admin.php?menu=galeri" onsubmit="return confirmDelete(event, this, 'Yakin ingin menghapus foto ini dari galeri beranda?');">
                                    <input type="hidden" name="tabel" value="galeri">
                                    <input type="hidden" name="id_hapus" value="<?= $g['id'] ?>">
                                    <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if(mysqli_num_rows($q_galeri) == 0) { ?>
                        <tr><td colspan="3" style="text-align:center; padding: 40px; color: var(--muted);"><i class="fas fa-info-circle fa-2x" style="margin-bottom:10px; opacity:0.3; display:block;"></i> Belum ada foto di dalam galeri ini.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        function toggleInputType() {
            var type = document.getElementById('tipe_input').value;
                if(type === 'url') {
                document.getElementById('input_url').style.display = 'block';
                document.getElementById('input_upload').style.display = 'none';
                document.querySelector('input[name="gambar_url"]').setAttribute('required', 'required');
                
                // Kondisi mode edit jika ganti radio input ke URL maka file input tidak perlu required
                let fileInput = document.querySelector('input[name="gambar_file"]');
                if (fileInput) fileInput.removeAttribute('required');
                
            } else {
                document.getElementById('input_url').style.display = 'none';
                document.getElementById('input_upload').style.display = 'block';
                
                let urlInput = document.querySelector('input[name="gambar_url"]');
                if (urlInput) urlInput.removeAttribute('required');
                
                // Jika sedang tidak edit mode, berikan required pada file
                let fileInput = document.querySelector('input[name="gambar_file"]');
                if (fileInput && !document.querySelector('input[name="id_galeri"]')) {
                    fileInput.setAttribute('required', 'required');
                }
            }
        }
        </script>

        <?php } elseif($menu == 'admin') { ?>
        <div class="page-header" data-aos="fade-down">
            <h1 class="page-title">Kelola Admin</h1>
        </div>

        <div style="display:flex; gap:30px; align-items: flex-start;">
            <div class="card" data-aos="fade-right" data-aos-delay="200" style="flex: 1;">
                <div class="card-header"><i class="fas fa-user-plus"></i> Tambah Admin Baru</div>
                <form method="POST" action="admin.php?menu=admin" style="padding: 25px;">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username_baru" class="form-control" placeholder="Ketik username..." required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password_baru" class="form-control" placeholder="Ketik password..." required>
                    </div>
                    <div class="form-group">
                        <label>Hak Akses (Role)</label>
                        <select name="role_baru" class="form-control" required>
                            <option value="Staff">Staff (Akses Terbatas Terhadap Penugasannya)</option>
                            <option value="Super Admin">Super Admin (Akses Penuh Seluruh Sistem)</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_admin" class="btn-action btn-primary" style="width:100%; padding:12px; font-size:1rem; display:block; text-align:center;">Simpan Admin</button>
                </form>
            </div>

            <div class="card" data-aos="fade-left" data-aos-delay="300" style="flex: 2;">
                <div class="card-header"><i class="fas fa-users"></i> Daftar Admin Terdaftar</div>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Username</th><th>Akses / Role</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_admin = mysqli_query($conn, "SELECT * FROM admin_users ORDER BY id ASC");
                        while($adm = mysqli_fetch_assoc($q_admin)) { ?>
                        <tr>
                            <td>#<?= $adm['id'] ?></td>
                            <td style="font-weight:600;"><?= $adm['username'] ?></td>
                            <td><?= $adm['role'] == 'Super Admin' ? "<span class='badge badge-yellow'><i class='fas fa-crown'></i> Super Admin</span>" : "<span class='badge badge-blue'>Staff User</span>" ?></td>
                            <td>
                                <?php if($adm['role'] != 'Super Admin') { ?>
                                <div style="display:flex; flex-direction:column; gap:5px; align-items:flex-start;">
                                    <form method="POST" action="admin.php?menu=admin" onsubmit="return confirmDelete(event, this, 'Yakin ingin mencabut akses admin ini?');" style="margin:0;">
                                        <input type="hidden" name="tabel" value="admin_users">
                                        <input type="hidden" name="id_hapus" value="<?= $adm['id'] ?>">
                                        <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i> Cabut Akses</button>
                                    </form>
                                    <button type="button" onclick="document.getElementById('pass_<?= $adm['id'] ?>').style.display='flex';" class="btn-action btn-secondary" style="font-size:0.75rem;"><i class="fas fa-key"></i> Pass Staff</button>
                                    <form method="POST" id="pass_<?= $adm['id'] ?>" style="display:none; gap:5px; align-items:center;">
                                        <input type="hidden" name="id_admin" value="<?= $adm['id'] ?>">
                                        <input type="password" name="password_baru" class="form-control" style="padding:4px; font-size:0.75rem; width:100px; height:auto;" placeholder="Pass baru..." required>
                                        <button type="submit" name="ubah_password" class="btn-action btn-primary" style="padding:4px 8px;"><i class="fas fa-check"></i></button>
                                    </form>
                                </div>
                                <?php } else { ?>
                                <div style="display:flex; flex-direction:column; gap:5px; align-items:flex-start;">
                                    <span style='font-size:0.8rem; color:var(--muted);'><i class='fas fa-shield-alt'></i> Terlindungi</span>
                                    <button type="button" onclick="document.getElementById('pass_<?= $adm['id'] ?>').style.display='flex';" class="btn-action btn-secondary" style="font-size:0.75rem;"><i class="fas fa-key"></i> Ubah Pass Saya</button>
                                    <form method="POST" id="pass_<?= $adm['id'] ?>" style="display:none; gap:5px; align-items:center;">
                                        <input type="hidden" name="id_admin" value="<?= $adm['id'] ?>">
                                        <input type="password" name="password_baru" class="form-control" style="padding:4px; font-size:0.75rem; width:100px; height:auto;" placeholder="Pass baru..." required>
                                        <button type="submit" name="ubah_password" class="btn-action btn-primary" style="padding:4px 8px;"><i class="fas fa-check"></i></button>
                                    </form>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php } elseif($menu == 'audit') { 
            // Akses Log Aktivitas
            // Super Admin lihat semua, Staff hanya lihat lognya sendiri
            $my_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;
            $where_audit = ($current_role != 'Super Admin') ? "WHERE a.admin_id='$my_id'" : "";
        ?>
        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title"><i class="fas fa-history" style="color:var(--primary); margin-right:10px;"></i> Riwayat Aktivitas Log (Audit Trail)</h1>
                <p style="color: var(--muted); margin-top:5px;">Lacak seluruh pergerakan status pesanan beserta bukti hasil kerjanya.</p>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari info / user...">
            </div>
        </div>

        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <div style="overflow-x: auto;">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Waktu (WIB)</th>
                            <th>Staff Username</th>
                            <th>Aktivitas</th>
                            <th>Tujuan / Invoice</th>
                            <th>Keterangan Tambahan</th>
                            <th>Bukti Screenshot</th>
                            <?php if($current_role == 'Super Admin') { echo "<th>Aksi</th>"; } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_audit = mysqli_query($conn, "SELECT a.*, u.username as nama_admin FROM audit_logs a LEFT JOIN admin_users u ON a.admin_id = u.id $where_audit ORDER BY a.id DESC LIMIT 150");
                        while($log = mysqli_fetch_assoc($q_audit)) { 
                        ?>
                        <tr>
                            <td style="color:var(--muted); font-size:0.85rem; white-space:nowrap;"><?= date('d M Y - H:i', strtotime($log['created_at'])) ?></td>
                            <td><span style="font-weight:700; color:var(--text);"><i class="fas fa-user-circle" style="color:#cbd5e1; margin-right:5px;"></i> <?= htmlspecialchars($log['nama_admin']) ?></span></td>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($log['action_type']) ?></span></td>
                            <td style="font-weight:600; font-family:monospace;"><?= htmlspecialchars($log['target_id']) ?></td>
                            <td style="font-size:0.85rem; max-width:250px;"><?= htmlspecialchars($log['keterangan']) ?></td>
                            <td style="text-align:center;">
                                <?php if(!empty($log['screenshot_path'])) { ?>
                                    <a href="<?= htmlspecialchars($log['screenshot_path']) ?>" target="_blank" class="btn-action" style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; font-size:0.75rem; padding:6px 10px;">
                                        <i class="fas fa-external-link-alt"></i> Lihat Bukti
                                    </a>
                                <?php } else { ?>
                                    <span style="color:#cbd5e1; font-size:0.8rem; font-style:italic;">-- Tidak ada --</span>
                                <?php } ?>
                            </td>
                            <?php if($current_role == 'Super Admin') { ?>
                            <td style="text-align:center;">
                                <form method="POST" action="admin.php?menu=audit" onsubmit="return confirmDelete(event, this, 'Yakin ingin menghapus log aktivitas ini?');" style="margin:0;">
                                    <input type="hidden" name="tabel" value="audit_logs">
                                    <input type="hidden" name="id_hapus" value="<?= $log['id'] ?>">
                                    <button type="submit" name="hapus_data" class="btn-action btn-danger" style="padding:4px 8px; font-size:0.8rem;"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php } elseif($menu == 'generator') { ?>
        <div class="page-header">
            <h1 class="page-title">Generator Link & Pesan WA</h1>
            <p style="color: var(--muted);">Buat ratusan link undangan unik secara otomatis untuk dikirim ke tamu.</p>
        </div>

        <div style="display:flex; gap:30px; align-items: flex-start;">
            <div class="card" style="flex: 1;">
                <div class="card-header"><i class="fas fa-cogs"></i> Pengaturan Pesan</div>
                <div style="padding: 25px;">
                    <div class="form-group">
                        <label>Pilih File / Tema Undangan</label>
                        <select id="base_url" class="form-control" style="cursor: pointer;">
                            <option value="" disabled selected>-- Pilih File --</option>
                            <?php
                                // Fungsi rekursif untuk mencari semua file .php di root folder dan subfolder
                                $root_dir = __DIR__;
                                $base_url_server = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER['PHP_SELF']);
                                
                                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root_dir));
                                $files_by_dir = [];
                                
                                foreach ($iterator as $file) {
                                    if ($file->isFile() && $file->getExtension() == 'php') {
                                        $relative_path = str_replace($root_dir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                                        $relative_path = str_replace('\\', '/', $relative_path); // Normalisasi path windows
                                        
                                        // Abaikan folder sistem/core agar dropdown tidak terlalu penuh
                                        if (strpos($relative_path, 'vendor/') === 0 || strpos($relative_path, 'node_modules/') === 0) continue;

                                        $dir_name = dirname($relative_path);
                                        if ($dir_name == '.') $dir_name = 'Root Folder';
                                        
                                        $files_by_dir[$dir_name][] = [
                                            'path' => $relative_path,
                                            'name' => basename($relative_path)
                                        ];
                                    }
                                }

                                // Tampilkan dalam select box dikelompokkan berdasarkan nama folder
                                ksort($files_by_dir);
                                foreach ($files_by_dir as $folder => $files) {
                                    echo "<optgroup label='" . strtoupper($folder) . "'>";
                                    foreach ($files as $f) {
                                        $full_link = $base_url_server . '/' . $f['path'];
                                        $display_name = ucwords(str_replace('_', ' ', basename($f['name'], '.php')));
                                        echo "<option value='{$full_link}'>{$display_name} ({$f['name']})</option>";
                                    }
                                    echo "</optgroup>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Daftar Nama Tamu <span class="text-hint">(Pisahkan dengan tombol Enter)</span></label>
                        <textarea id="guest_list" class="form-control" rows="8" placeholder="Budi Santoso&#10;Siti & Partner&#10;Keluarga Bapak Andi"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Template Pesan WhatsApp</label>
                        <span class="text-hint" style="margin-bottom:8px;">Gunakan kode <b>[nama]</b> untuk memanggil nama tamu dan <b>[link]</b> untuk link uniknya.</span>
                        <textarea id="wa_template" class="form-control" rows="6">Kepada Yth. Bapak/Ibu/Saudara/i,
*[nama]*

Tanpa mengurangi rasa hormat, perkenankan kami mengundang Anda untuk hadir di acara pernikahan kami.

Silakan buka tautan undangan berikut untuk info lebih detail:
[link]

Merupakan suatu kehormatan apabila Anda berkenan hadir. Terima kasih!</textarea>
                    </div>
                    <button onclick="generateLinks()" class="btn-action btn-primary" style="width: 100%; padding: 15px; font-size: 1rem; justify-content:center;">
                        <i class="fas fa-magic"></i> Generate Sekarang
                    </button>
                </div>
            </div>

            <div class="card" style="flex: 1; position: sticky; top: 40px;">
                <div class="card-header"><i class="fas fa-copy"></i> Hasil Pesan (Siap Sebar)</div>
                <div style="padding: 25px;">
                    <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 10px;">Salin teks di bawah ini untuk dibagikan secara manual, atau gunakan sebagai data broadcast.</p>
                    <textarea id="result_output" class="form-control" rows="25" style="background:#f8fafc; font-family: monospace; font-size: 0.85rem;" readonly placeholder="Hasil generate akan muncul di sini..."></textarea>
                </div>
            </div>
        </div>

        <script>
        function generateLinks() {
            let baseUrl = document.getElementById('base_url').value;
            
            if(!baseUrl) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Silakan pilih tema undangan terlebih dahulu!' });
                return;
            }

            let guests = document.getElementById('guest_list').value.split('\n');
            let template = document.getElementById('wa_template').value;
            let result = '';
            let count = 0;

            guests.forEach(name => {
                let cleanName = name.trim();
                if(cleanName !== '') {
                    count++;
                    
                    // PERBAIKAN: Spasi akan diubah jadi '+' agar URL bersih dari angka %
                    let safeName = encodeURIComponent(cleanName).replace(/%20/g, '+').replace(/%2B/g, '+');
                    
                    // Cek apakah base URL sudah mengandung parameter lain (menggunakan ? atau &)
                    let separator = baseUrl.includes('?') ? '&' : '?';
                    let uniqueLink = baseUrl + separator + 'to=' + safeName;
                    
                    // Replace text [nama] dan [link]
                    let msg = template.replace(/\[nama\]/g, cleanName).replace(/\[link\]/g, uniqueLink);
                    
                    result += msg + "\n\n\n";
                }
            });

            if(count > 0) {
                document.getElementById('result_output').value = result;
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: count + ' Pesan Dibuat!', showConfirmButton: false, timer: 3000 });
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Masukkan setidaknya satu nama tamu!' });
            }
        }
        </script>
        <?php } elseif($menu == 'kalender') { 
            // Ambil data pesanan untuk kalender
            $adminFilterKalender = "";
            $current_role  = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Super Admin';
            $my_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;

            if($current_role != 'Super Admin') {
                $adminFilterKalender = "AND p.admin_id='$my_id'"; 
            }

            $q_kalender = mysqli_query($conn, "SELECT p.nama_pemesan, p.tanggal_acara, p.status_pengerjaan 
                                               FROM pesanan p 
                                               WHERE p.status_pembayaran != 'Belum Bayar' $adminFilterKalender");
            $events = [];
            while($row = mysqli_fetch_assoc($q_kalender)){
                $is_verified = ($row['status_pengerjaan'] == 'Selesai');
                $events[] = array(
                    'title' => ($is_verified ? 'TERVERIFIKASI - ' : 'TERBOOKING - ') . $row['nama_pemesan'],
                    'start' => $row['tanggal_acara'],
                    'color' => $is_verified ? '#bbf7d0' : '#fca5a5',
                    'textColor' => $is_verified ? '#166534' : '#991b1b',
                    'allDay' => true
                );
            }
        ?>
        <div class="page-header" data-aos="fade-down">
            <h1 class="page-title">Cek Ketersediaan Jadwal</h1>
            <p style="color: var(--muted);">Klik tanggal kosong untuk booking. <span class="badge badge-red">MERAH</span> = Terisi.</p>
        </div>

        <div class="card" data-aos="fade-up" data-aos-delay="200" style="padding: 25px; min-height:600px;">
            <div id='calendar'></div>
        </div>
        
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
              initialView: 'dayGridMonth',
              themeSystem: 'standard',
              headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
              },
              buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Daftar'
              },
              locale: 'id',
              events: <?= json_encode($events) ?>
            });
            calendar.render();
          });
        </script>
        <style>
            .fc-event { border: none !important; border-radius: 4px; padding: 2px 4px; font-weight: 600; font-size: 0.8rem; }
            .fc-toolbar-title { font-size: 1.4rem !important; font-weight: 700 !important; color: var(--primary); }
            .fc-button-primary { background-color: var(--primary) !important; border: none !important; transition: 0.2s; }
            .fc-button-primary:hover { background-color: #3b4b3e !important; }
            .fc-day-today { background-color: #fefce8 !important; }
        </style>

        <?php } elseif($menu == 'premium') {
            if($current_role != 'Super Admin') { header("Location: admin.php?menu=dashboard"); exit; }
            // Fetch all paid orders for premium management
            $q_prem_orders = mysqli_query($conn, "
                SELECT p.id, p.invoice, p.nama_pemesan, p.status_pembayaran, p.status_pengerjaan, p.tanggal_acara,
                       k.nama_tema, a.username as staff_name,
                       kp.id as kp_id, kp.username as prem_user, kp.is_active as prem_active, kp.tipe as prem_tier
                FROM pesanan p
                LEFT JOIN katalog_tema k ON p.tema_id = k.id
                LEFT JOIN admin_users a ON p.admin_id = a.id
                LEFT JOIN klien_premium kp ON kp.pesanan_id = p.id
                ORDER BY p.id DESC
            ");
            // Chat for selected order
            $pid_chat = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
            $chat_messages = [];
            $chat_pesanan = null;
            if ($pid_chat > 0) {
                $chat_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.nama_pemesan, p.invoice FROM pesanan p WHERE p.id='$pid_chat'"));
                $qcm = mysqli_query($conn, "SELECT * FROM pesan_proyek WHERE pesanan_id='$pid_chat' ORDER BY id ASC LIMIT 80");
                while ($cm = mysqli_fetch_assoc($qcm)) { $chat_messages[] = $cm; }
            }
        ?>
        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title"><i class="fas fa-crown" style="color:#D4AF37; margin-right:8px;"></i> Premium Portal Manager</h1>
                <p style="color:var(--muted); margin-top:5px;">Buat akun klien premium dan pantau percakapan mereka dengan tim.</p>
            </div>
        </div>

        <div style="display:flex; gap:30px; align-items:flex-start;">

            <!-- LEFT: Table of all orders -->
            <div style="flex: 2;">
                <div class="card" data-aos="fade-up">
                    <div class="card-header"><i class="fas fa-list"></i> Semua Pesanan & Status Akun Premium</div>
                    <div style="overflow-x:auto;">
                        <table>
                            <thead><tr>
                                <th>Klien & Invoice</th>
                                <th>Tema</th>
                                <th>Status Bayar</th>
                                <th>Akun Premium</th>
                                <th>Aksi</th>
                            </tr></thead>
                            <tbody>
                            <?php while($po = mysqli_fetch_assoc($q_prem_orders)) { ?>
                            <tr style="<?= $po['kp_id'] ? 'background:#fefce8;' : '' ?>">
                                <td>
                                    <b style="color:var(--primary);"><?= htmlspecialchars($po['invoice']) ?></b><br>
                                    <span><?= htmlspecialchars($po['nama_pemesan']) ?></span>
                                </td>
                                <td style="font-size:0.88rem;"><?= htmlspecialchars($po['nama_tema'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $bp = 'badge-red';
                                    if($po['status_pembayaran']=='Lunas') $bp='badge-green';
                                    elseif($po['status_pembayaran']=='Menunggu Konfirmasi') $bp='badge-blue';
                                    ?>
                                    <span class="badge <?= $bp ?>"><?= $po['status_pembayaran'] ?></span>
                                </td>
                                <td>
                                    <?php if($po['kp_id']): ?>
                                        <div style="display:flex; align-items:center; gap:5px; margin-bottom:5px;">
                                            <?php if($po['prem_tier'] == 'Exclusive'): ?>
                                                <span class="badge" style="background:linear-gradient(135deg,#D4AF37,#B8960C); color:#fff; font-size:0.65rem; border:none;"><i class="fas fa-gem"></i> Exclusive</span>
                                            <?php else: ?>
                                                <span class="badge badge-yellow" style="font-size:0.65rem; border:none;"><i class="fas fa-crown"></i> Premium</span>
                                            <?php endif; ?>
                                            <span class="badge badge-green" style="font-size:0.65rem; border:none;"><i class="fas fa-check-circle"></i> Aktif</span>
                                        </div>
                                        <span style="font-size:0.78rem; color:var(--muted);">User: <b><?= htmlspecialchars($po['prem_user']) ?></b></span>
                                    <?php else: ?>
                                        <span class="badge badge-yellow"><i class="fas fa-minus-circle"></i> Belum Dibuat</span>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space:nowrap;">
                                    <button onclick="openPremModal(<?= $po['id'] ?>, '<?= htmlspecialchars($po['invoice'], ENT_QUOTES) ?>', '<?= htmlspecialchars($po['prem_user'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($po['prem_tier'] ?? 'Premium', ENT_QUOTES) ?>')" class="btn-action btn-primary" style="font-size:0.82rem; background:#4A5D4E; border:none; margin-bottom:5px;">
                                        <i class="fas fa-key"></i> <?= $po['kp_id'] ? 'Edit Akun' : 'Buat Akun' ?>
                                    </button>
                                    <a href="?menu=premium&pid=<?= $po['id'] ?>" class="btn-action btn-secondary" style="font-size:0.82rem; display:inline-flex;">
                                        <i class="fas fa-comments"></i> Chat (<?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM pesan_proyek WHERE pesanan_id='".$po['id']."'"))['c'] ?>)
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Chat Panel -->
            <div style="flex: 1; position:sticky; top:40px;">
                <?php if ($pid_chat > 0 && $chat_pesanan): ?>
                <div class="card" data-aos="fade-left">
                    <div class="card-header">
                        <i class="fas fa-comments" style="color:var(--primary);"></i>
                        Chat: <?= htmlspecialchars($chat_pesanan['nama_pemesan']) ?>
                        <span style="font-size:0.75rem; font-weight:400; color:var(--muted); margin-left:5px;"><?= htmlspecialchars($chat_pesanan['invoice']) ?></span>
                    </div>
                    <!-- Messages -->
                    <div id="adminChatBody" style="height:350px; overflow-y:auto; padding:15px 20px; display:flex; flex-direction:column; gap:10px; background:#fafaf9;">
                        <?php if (empty($chat_messages)): ?>
                            <p style="text-align:center; color:var(--muted); padding:40px 0; font-size:0.88rem;"><i class="fas fa-comment-slash fa-2x" style="display:block; margin-bottom:10px; opacity:0.3;"></i>Belum ada pesan.</p>
                        <?php else: ?>
                            <?php foreach ($chat_messages as $cm):
                                $is_admin = $cm['pengirim'] === 'admin';
                            ?>
                            <div style="display:flex; gap:8px; flex-direction:<?= $is_admin ? 'row-reverse' : 'row' ?>; align-items:flex-end;">
                                <div style="width:30px; height:30px; border-radius:50%; background:<?= $is_admin ? 'var(--primary)' : '#D4AF37' ?>; display:flex; align-items:center; justify-content:center; color:#fff; font-size:0.75rem; font-weight:700; flex-shrink:0;">
                                    <?= strtoupper(substr($cm['nama_pengirim'], 0, 1)) ?>
                                </div>
                                <div style="max-width:75%;">
                                    <div style="background:<?= $is_admin ? 'var(--primary)' : '#fff' ?>; color:<?= $is_admin ? '#fff' : 'var(--text)' ?>; padding:10px 14px; border-radius:14px; font-size:0.85rem; line-height:1.5; border:1px solid <?= $is_admin ? 'transparent' : 'var(--border)' ?>;">
                                        <?= nl2br(htmlspecialchars($cm['pesan'])) ?>
                                    </div>
                                    <div style="font-size:0.7rem; color:var(--muted); margin-top:3px; text-align:<?= $is_admin ? 'right' : 'left' ?>;">
                                        <?= htmlspecialchars($cm['nama_pengirim']) ?> · <?= date('H:i', strtotime($cm['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <!-- Reply Form -->
                    <form method="POST" action="admin.php?menu=premium&pid=<?= $pid_chat ?>" style="padding:12px 15px; border-top:1px solid var(--border); display:flex; gap:10px;">
                        <input type="hidden" name="pesanan_id_chat" value="<?= $pid_chat ?>">
                        <input type="text" name="pesan_admin" class="form-control" placeholder="Balas pesan klien..." required style="flex:1; border-radius:50px; padding:10px 18px; font-size:0.88rem;">
                        <button type="submit" name="balas_chat_admin" class="btn-action btn-primary" style="width:42px; height:42px; border-radius:50%; padding:0; justify-content:center;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
                <?php else: ?>
                <div class="card" style="padding:40px 25px; text-align:center; color:var(--muted);">
                    <i class="fas fa-comments fa-2x" style="opacity:0.3; display:block; margin-bottom:12px;"></i>
                    <p style="font-size:0.88rem;">Klik tombol <b>Chat</b> di tabel kiri untuk membuka percakapan dengan klien.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal Buat/Edit Akun Premium -->
        <div id="modalPremium" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:200; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
            <div style="background:white; padding:35px; border-radius:18px; width:440px; max-width:90%; box-shadow:0 40px 80px rgba(0,0,0,0.2); border-top:4px solid #D4AF37;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h3 style="color:var(--text);"><i class="fas fa-crown" style="color:#D4AF37;"></i> Akun Premium Klien</h3>
                    <button onclick="closePremModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--muted);">&times;</button>
                </div>
                <p id="premModalInvoice" style="font-size:0.8rem; color:var(--muted); margin-bottom:20px; padding:8px 12px; background:#f8f9fa; border-radius:8px; font-family:monospace;"></p>
                <form method="POST" action="admin.php?menu=premium">
                    <input type="hidden" name="pesanan_id_prem" id="premPesananId">
                    <div class="form-group">
                        <label>Tipe Akun (Tier)</label>
                        <select name="prem_tipe" id="premTipe" class="form-control" required>
                            <option value="Premium">Standard Premium</option>
                            <option value="Exclusive">Exclusive Tier (Gold Badge)</option>
                        </select>
                        <span class="text-hint">Tier menentukan badge dan fitur khusus di dashboard klien.</span>
                    </div>
                    <div class="form-group">
                        <label>Username Klien</label>
                        <input type="text" name="prem_username" id="premUsername" class="form-control" placeholder="contoh: romeo_juliet" required>
                        <span class="text-hint">Klien akan login dengan username ini di portal premium.</span>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="prem_password" class="form-control" placeholder="Buat password yang kuat" required>
                        <span class="text-hint">Min. 6 karakter. Berikan ke klien secara pribadi.</span>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                        <button type="button" onclick="closePremModal()" class="btn-action btn-secondary">Batal</button>
                        <button type="submit" name="buat_akun_premium" class="btn-action btn-primary" style="background:#4A5D4E; border:none;">
                            <i class="fas fa-save"></i> Simpan Akun
                        </button>
                    </div>
                </form>
                <div style="margin-top:15px; padding:12px; background:#fefce8; border-radius:10px; font-size:0.82rem; color:#854d0e;">
                    <i class="fas fa-info-circle"></i> Link portal klien: <code><?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) ?>/admin_premium/login.php</code>
                </div>
            </div>
        </div>
        <script>
        function openPremModal(id, invoice, existingUser, tier) {
            document.getElementById('premPesananId').value = id;
            document.getElementById('premModalInvoice').textContent = '📄 Invoice: ' + invoice;
            document.getElementById('premUsername').value = existingUser || '';
            document.getElementById('premTipe').value = tier || 'Premium';
            document.getElementById('modalPremium').style.display = 'flex';
        }
        function closePremModal() {
            document.getElementById('modalPremium').style.display = 'none';
        }
        // Auto scroll chat
        const acb = document.getElementById('adminChatBody');
        if (acb) acb.scrollTop = acb.scrollHeight;
        </script>

        <?php } elseif($menu == 'template') { 
            // ==========================================
            // HALAMAN TEMPLATE REFERENSI (Semua Role)
            // ==========================================
            $template_folders = [
                'Tema Utama'   => ['path' => __DIR__ . '/tema',          'rel' => 'tema'],
                'Tema Themes'  => ['path' => __DIR__ . '/themes/basic',   'rel' => 'themes/basic'],
            ];
            $base_url_tmpl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER['PHP_SELF']);
        ?>
        <style>
            .tmpl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 18px; padding: 20px; }
            .tmpl-card { background: white; border-radius: 14px; border: 1px solid var(--border); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; }
            .tmpl-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
            .tmpl-card-top { padding: 16px 18px 12px; background: linear-gradient(135deg,#f8fafc,#f1f5f9); border-bottom: 1px solid var(--border); }
            .tmpl-card-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 9px; }
            .tmpl-card-name { font-weight: 700; font-size: 0.95rem; color: var(--text); }
            .tmpl-card-meta { font-size: 0.76rem; color: var(--muted); margin-top: 2px; }
            .tmpl-card-foot { padding: 12px 18px; display: flex; gap: 8px; }
        </style>

        <div class="page-header" data-aos="fade-down">
            <div>
                <h1 class="page-title"><i class="fas fa-code" style="color:var(--primary);"></i> Template Referensi</h1>
                <p style="color:var(--muted); margin-top:5px;">Preview tampilan template dan unduh source code sebagai referensi pengerjaan.</p>
            </div>
        </div>

        <?php foreach($template_folders as $label => $info):
            $folder_path = $info['path'];
            $rel         = $info['rel'];
            $files = [];
            if(is_dir($folder_path)) {
                foreach(scandir($folder_path) as $f) {
                    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                    if(in_array($ext, ['php','html']) && $f[0] !== '.') {
                        $files[] = $f;
                    }
                }
            }
            if(empty($files)) continue;
        ?>
        <div class="card" data-aos="fade-up" data-aos-delay="100" style="margin-bottom:25px;">
            <div class="card-header">
                <i class="fas fa-folder-open" style="color:#f97316;"></i> <?= htmlspecialchars($label) ?>
                <span style="margin-left:8px; font-size:0.8rem; font-weight:400; color:var(--muted);"><?= count($files) ?> file ditemukan</span>
            </div>
            <div class="tmpl-grid">
                <?php foreach($files as $fname):
                    $ext  = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                    $fsize = file_exists($folder_path . DIRECTORY_SEPARATOR . $fname) ? round(filesize($folder_path . DIRECTORY_SEPARATOR . $fname) / 1024, 1) . ' KB' : '-';
                    $icon_bg  = ($ext === 'php') ? '#8b5cf622' : '#0284c722';
                    $icon_clr = ($ext === 'php') ? '#8b5cf6'   : '#0284c7';
                    $icon_fa  = ($ext === 'php') ? 'fa-php'    : 'fa-html5';
                    $nice_name = ucwords(str_replace(['_','-'], ' ', basename($fname, '.'.$ext)));
                    $preview_url  = $base_url_tmpl . '/' . $rel . '/' . $fname;
                    $download_url = 'admin.php?menu=template&dl=' . urlencode($rel . '/' . $fname);
                ?>
                <div class="tmpl-card">
                    <div class="tmpl-card-top">
                        <div class="tmpl-card-icon" style="background:<?= $icon_bg ?>; color:<?= $icon_clr ?>;"><i class="fab <?= $icon_fa ?>"></i></div>
                        <div class="tmpl-card-name"><?= htmlspecialchars($nice_name) ?></div>
                        <div class="tmpl-card-meta"><i class="fas fa-file-code"></i> <?= htmlspecialchars($fname) ?> &middot; <?= $fsize ?></div>
                    </div>
                    <div class="tmpl-card-foot">
                        <a href="<?= htmlspecialchars($preview_url) ?>" target="_blank" class="btn-action btn-secondary" style="flex:1; justify-content:center; font-size:0.82rem;">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                        <a href="<?= htmlspecialchars($download_url) ?>" class="btn-action btn-primary" style="flex:1; justify-content:center; font-size:0.82rem;">
                            <i class="fas fa-download"></i> Download Kode
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php } ?>

    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // ==========================================
        // AUTO LOGOUT JIKA TIDAK ADA INTERAKSI (15 MENIT)
        // ==========================================
        let inactivityTime = function () {
            let time;
            // 15 Menit = 900.000 ms
            const timeoutMillis = 900000;

            function logout() {
                Swal.fire({
                    title: 'Sesi Berakhir!',
                    text: 'Anda terlalu lama tidak aktif. Untuk keamanan, sistem akan mengeluarkan Anda.',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = 'admin.php?logout=true';
                });
            }

            function resetTimer() {
                clearTimeout(time);
                time = setTimeout(logout, timeoutMillis);
            }

            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onclick = resetTimer;
            document.onscroll = resetTimer;
        };
        inactivityTime();

        // Init AOS Animations
        AOS.init({ once: true, duration: 800, offset: 50 });

        // Tampilkan Notifikasi Sistem
        <?= $notif; ?>

        // Confirm Delete Animation (Tanya Konfirmasi dengan Swal)
        function confirmDelete(e, formElement, popupMessage) {
            e.preventDefault(); // Hentikan submit bawaan
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: popupMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Karena formElement.submit() Javascript tidak menyertakan tombol submit yang ditekan, 
                    // kita perlu membuat elemen input hidden dadakan agar terdeteksi di PHP isset($_POST['hapus_data'])
                    let hiddenInput = document.createElement("input");
                    hiddenInput.setAttribute("type", "hidden");
                    hiddenInput.setAttribute("name", "hapus_data");
                    hiddenInput.setAttribute("value", "true");
                    formElement.appendChild(hiddenInput);
                    
                    formElement.submit(); // Submit asli diteruskan
                }
            });
            return false;
        }

        // Fitur Live Search Table Modern
        function filterTable() {
            var input = document.getElementById("searchInput");
            if(!input) return;
            var filter = input.value.toLowerCase();
            var table = document.getElementById("dataTable");
            if(!table) return;
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) { // Abaikan header thead
                var showRow = false;
                var td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        var textValue = td[j].textContent || td[j].innerText;
                        if (textValue.toLowerCase().indexOf(filter) > -1) {
                            showRow = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = showRow ? "" : "none";
            }
        }

        // ==========================================
        // REAL-TIME NOTIFICATION AJAX POLLING
        // ==========================================
        let lastNotifCount = <?= (isset($jml_menunggu) ? $jml_menunggu : 0) + (isset($jml_request) ? $jml_request : 0) ?>;
        
        function showNotif(pesan) {
            document.getElementById('notifDescText').innerText = pesan;
            let toast = document.getElementById('liveNotifToast');
            toast.classList.add('show');
            
            // Play sound
            let audio = document.getElementById('notifSound');
            let playPromise = audio.play();
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.log("Audio autoplay prevented by browser.");
                });
            }

            // Bell Animation
            let iconPesanan = document.getElementById('navIconPesanan');
            if(iconPesanan) {
                iconPesanan.classList.add('bell-shake');
                setTimeout(() => { iconPesanan.classList.remove('bell-shake'); }, 2000);
            }

            setTimeout(() => { closeNotif(); }, 6000);
        }

        function closeNotif() {
            document.getElementById('liveNotifToast').classList.remove('show');
        }

        setInterval(() => {
            fetch('api_notif.php')
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    let newTotal = data.total_notif;
                    
                    // Update badges if they change
                    if(data.detail.menunggu_konfirmasi > 0) {
                        document.getElementById('badgePesanan').innerHTML = `<span style='background:#ef4444; color:white; padding:2px 6px; border-radius:50px; font-size:0.7rem; margin-left:5px;'>${data.detail.menunggu_konfirmasi}</span>`;
                    } else {
                        document.getElementById('badgePesanan').innerHTML = "";
                    }

                    if(data.detail.request_baru > 0 && document.getElementById('badgeRequest')) {
                        document.getElementById('badgeRequest').innerHTML = `<span style='background:#ef4444; color:white; padding:2px 6px; border-radius:50px; font-size:0.7rem; margin-left:5px;'>${data.detail.request_baru}</span>`;
                    } else if (document.getElementById('badgeRequest')) {
                        document.getElementById('badgeRequest').innerHTML = "";
                    }

                    // Trigger alert if there's a new item
                    if(newTotal > lastNotifCount) {
                        let diff = newTotal - lastNotifCount;
                        showNotif(`Ada ${diff} pembaruan pesanan/request menunggu Anda!`);
                    }
                    lastNotifCount = newTotal;
                }
            })
            .catch(error => console.log('Polling error:', error));
        }, 10000); // Check every 10 seconds

        // Real-time Status Update for Staff/Admin
        function updateProjectStatus(id, status) {
            Swal.fire({
                title: 'Update Status?',
                text: `Ubah status pengerjaan menjadi "${status}"? Klien akan melihat update ini secara realtime.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('pesanan_id', id);
                    formData.append('status_baru', status);

                    fetch('api_update_status.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            Swal.fire({
                                icon: 'success', title: 'Berhasil!', text: data.message,
                                showConfirmButton: false, timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan sistem.', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                    });
                }
            });
        }

    </script>
</body>
</html>