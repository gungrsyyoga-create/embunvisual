<?php 
error_reporting(0);
session_start();
include 'config.php'; 

// ==========================================
// 1. SISTEM AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================
if(isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = md5($_POST['password']);
    
    $query_login = "SELECT * FROM admin_users WHERE username='$user' AND password='$pass'";
    $cek = mysqli_query($conn, $query_login);
    
    if (!$cek) {
        die("<div style='background:#fee2e2; color:#991b1b; padding:20px; font-family:sans-serif; text-align:center;'>
                <b>CRASH DATABASE DETECTED!</b><br>
                Pesan Error MySQL: " . mysqli_error($conn) . "
             </div>");
    }
    
    if(mysqli_num_rows($cek) > 0) {
        $_SESSION['admin_embun'] = true;
        header("Location: admin.php"); 
        exit;
    } else {
        $error_login = "Username atau Password salah!";
    }
}

if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// JIKA BELUM LOGIN, TAMPILKAN HALAMAN LOGIN
if(!isset($_SESSION['admin_embun'])) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login | Embun Visual Workspace</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F9FAF8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); width: 100%; max-width: 380px; text-align: center; border: 1px solid #f1f1f1; }
        input { width: 100%; padding: 14px; margin: 10px 0 20px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; transition: 0.3s; }
        input:focus { border-color: #4A5D4E; }
        button { width: 100%; padding: 14px; background: #4A5D4E; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        button:hover { background: #3b4b3e; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="color: #4A5D4E; margin:0 0 5px 0;">Embun Visual</h2>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 25px;">Secure Workspace Access</p>
        <?php if(isset($error_login)) echo "<div style='color:#ef4444; background:#fef2f2; padding:10px; border-radius:8px; font-size:0.85rem; margin-bottom:15px;'>$error_login</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>
</body>
</html>
<?php 
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

// A. Update Status Pembayaran Pesanan
if(isset($_POST['update_status_pesanan'])){
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status_bayar'];
    if(mysqli_query($conn, "UPDATE pesanan SET status_pembayaran='$status_baru' WHERE id='$id_pesanan'")){
        $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Status pembayaran diperbarui.', 'success');";
        header("Location: admin.php?menu=pesanan"); exit;
    }
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
    $cek_user = mysqli_query($conn, "SELECT * FROM admin_users WHERE username='$u'");
    if(mysqli_num_rows($cek_user) > 0) {
        $_SESSION['notif_pesan'] = "Swal.fire('Gagal!', 'Username tersebut sudah dipakai.', 'error');";
    } else {
        if(mysqli_query($conn, "INSERT INTO admin_users (username, password) VALUES ('$u', '$p')")){
            $_SESSION['notif_pesan'] = "Swal.fire('Berhasil!', 'Admin baru ditambahkan.', 'success');";
        }
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

// ==========================================
// 3. AMBIL STATISTIK UNTUK DASHBOARD
// ==========================================
$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan"))['jml'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_tagihan) as total FROM pesanan WHERE status_pembayaran='Lunas'"))['total'];
$total_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM request_custom WHERE status_request='Menunggu Review'"))['jml'];

$jml_menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE status_pembayaran='Menunggu Konfirmasi'"))['jml'];
$badge_notif = ($jml_menunggu > 0) ? "<span style='background:#ef4444; color:white; padding:2px 6px; border-radius:50px; font-size:0.7rem; margin-left:5px;'>$jml_menunggu</span>" : "";

$menu = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';
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
        :root { --primary: #4A5D4E; --bg: #F4F6F3; --surface: #FFFFFF; --text: #1E293B; --muted: #64748B; --border: #E2E8F0; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: var(--surface); border-right: 1px solid var(--border); padding: 25px 20px; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; }
        .brand { font-size: 1.4rem; font-weight: 700; color: var(--primary); margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 14px 16px; color: var(--muted); text-decoration: none; border-radius: 10px; font-weight: 500; margin-bottom: 8px; transition: 0.2s; }
        .nav-item:hover { background: #f8fafc; color: var(--primary); }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(74,93,78,0.2); }
        
        .main { flex: 1; margin-left: 260px; padding: 40px; }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 1.8rem; font-weight: 700; color: var(--text); }
        
        .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--surface); padding: 25px; border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .stat-title { font-size: 0.9rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
        .stat-value { font-size: 2rem; font-weight: 700; color: var(--primary); }
        
        .card { background: var(--surface); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 30px; }
        .card-header { padding: 20px 25px; border-bottom: 1px solid var(--border); background: #fafafa; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 25px; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
        th { font-weight: 600; color: var(--muted); background: white; }
        
        .badge { padding: 6px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-blue { background: #e0f2fe; color: #075985; }
        
        .btn-action { padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 0.85rem; transition: 0.2s; display:inline-flex; align-items:center; gap:5px; text-decoration:none;}
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #3b4b3e; }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fecaca; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
        
        .search-box { position: relative; max-width: 300px; display: inline-block; width: 100%; margin-top: 10px; }
        .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--muted); }
        .search-box input { width: 100%; padding: 10px 15px 10px 40px; border: 1px solid var(--border); border-radius: 50px; font-family: inherit; outline: none; transition: 0.3s; font-size: 0.85rem; }
        .search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(74,93,78,0.1); }
        
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; outline: none; }
        .form-control:focus { border-color: var(--primary); }
        .text-hint { color: var(--muted); font-size: 0.8rem; display: block; margin-top: 5px; }
    </style>
</head>
<body>

    <aside class="sidebar" data-aos="fade-right" data-aos-duration="800">
        <div class="brand"><i class="fas fa-leaf"></i> Embun Visual</div>
        
        <a href="?menu=dashboard" class="nav-item <?= $menu == 'dashboard' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="?menu=pesanan" class="nav-item <?= $menu == 'pesanan' ? 'active' : '' ?>"><i class="fas fa-receipt"></i> Pesanan Masuk <?= $badge_notif ?></a>
        <a href="?menu=katalog" class="nav-item <?= $menu == 'katalog' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Kelola Katalog</a>
        <a href="?menu=request" class="nav-item <?= $menu == 'request' ? 'active' : '' ?>"><i class="fas fa-paint-brush"></i> Request Custom</a>
        <a href="?menu=galeri" class="nav-item <?= $menu == 'galeri' ? 'active' : '' ?>"><i class="fas fa-images"></i> Pengaturan Galeri</a>
        <a href="?menu=admin" class="nav-item <?= $menu == 'admin' ? 'active' : '' ?>"><i class="fas fa-users-cog"></i> Kelola Admin</a>
        <a href="?menu=generator" class="nav-item <?= $menu == 'generator' ? 'active' : '' ?>"><i class="fas fa-magic"></i> Generator Link</a>
        
        <div style="margin-top: auto;">
            <a href="index.php" target="_blank" class="nav-item" style="background:#f1f5f9; color:#333;"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
            <a href="?logout=true" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Keluar System</a>
        </div>
    </aside>

    <main class="main">
        
        <?php if($menu == 'dashboard') { ?>
        <div class="page-header" data-aos="fade-down">
            <h1 class="page-title">Ikhtisar Bisnis</h1>
            <p style="color: var(--muted);">Selamat datang kembali, pantau performa Embun Visual hari ini.</p>
        </div>

        <div class="grid-stats">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-title">Total Pesanan</div>
                <div class="stat-value"><?= number_format($total_pesanan) ?></div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-title">Total Pendapatan (Lunas)</div>
                <div class="stat-value">Rp <?= number_format((float)$total_pendapatan,0,',','.') ?></div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-title">Menunggu Konfirmasi Transfer</div>
                <div class="stat-value" style="color:#0284c7;"><?= number_format($jml_menunggu) ?> <i class="fas fa-bell" style="font-size:1rem;"></i></div>
            </div>
        </div>
        
        <div class="card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header">Panduan Singkat</div>
            <div style="padding: 25px; line-height:1.8; color: var(--muted);">
                1. Jika ada klien memesan via website, data akan muncul di menu <b>Pesanan Masuk</b>.<br>
                2. Status <b>Menunggu Konfirmasi</b> artinya klien mengklaim sudah mentransfer dana. Silakan cek mutasi BCA/E-Wallet Anda.<br>
                3. Jika dana benar masuk, ubah status pembayarannya menjadi <b>Lunas</b>.
            </div>
        </div>

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
                        $q_pesanan = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id ORDER BY p.id DESC");
                        while($row = mysqli_fetch_assoc($q_pesanan)) { 
                            $badge = 'badge-yellow'; 
                            if($row['status_pembayaran'] == 'Lunas') $badge = 'badge-green';
                            if($row['status_pembayaran'] == 'Belum Bayar') $badge = 'badge-red';
                            if($row['status_pembayaran'] == 'Menunggu Konfirmasi') $badge = 'badge-blue';
                        ?>
                        <tr <?= ($row['status_pembayaran'] == 'Menunggu Konfirmasi') ? "style='background:#f0f9ff;'" : "" ?>>
                            <td style="font-weight:600; color:var(--primary);"><?= $row['invoice'] ?></td>
                            <td>
                                <b><?= $row['nama_pemesan'] ?></b><br>
                                <a href="https://wa.me/<?= $row['no_whatsapp'] ?>" target="_blank" style="color:#25D366; text-decoration:none; font-size:0.85rem;"><i class="fab fa-whatsapp"></i> <?= $row['no_whatsapp'] ?></a>
                            </td>
                            <td>
                                <?= $row['nama_tema'] ?><br>
                                <span style="font-size:0.8rem; color:var(--muted);">Tgl: <?= date('d M Y', strtotime($row['tanggal_acara'])) ?></span>
                            </td>
                            <td style="font-weight:600;">Rp <?= number_format($row['total_tagihan'],0,',','.') ?></td>
                            <td><span class="badge <?= $badge ?>"><?= $row['status_pembayaran'] ?></span></td>
                            <td style="display:flex; gap:10px; align-items:center;">
                                <form method="POST" style="display:flex; gap:5px;">
                                    <input type="hidden" name="id_pesanan" value="<?= $row['id'] ?>">
                                    <select name="status_bayar" class="form-control" style="padding:6px; width:auto; font-size:0.8rem; margin:0;">
                                        <option value="Belum Bayar" <?= $row['status_pembayaran']=='Belum Bayar'?'selected':'' ?>>Belum Bayar</option>
                                        <option value="Menunggu Konfirmasi" <?= $row['status_pembayaran']=='Menunggu Konfirmasi'?'selected':'' ?>>Cek Mutasi</option>
                                        <option value="Lunas" <?= $row['status_pembayaran']=='Lunas'?'selected':'' ?>>Lunas</option>
                                    </select>
                                    <button type="submit" name="update_status_pesanan" class="btn-action btn-primary"><i class="fas fa-check"></i></button>
                                </form>
                                <form method="POST" action="admin.php?menu=pesanan" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                    <input type="hidden" name="tabel" value="pesanan">
                                    <input type="hidden" name="id_hapus" value="<?= $row['id'] ?>">
                                    <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

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
                                <form method="POST" action="admin.php?menu=katalog" onsubmit="return confirm('Yakin ingin menghapus tema ini?');" style="margin:0;">
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
                            <form method="POST" action="admin.php?menu=request" onsubmit="return confirm('Yakin ingin menghapus request ini?');">
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

        <?php } elseif($menu == 'galeri') { ?>
        <div class="page-header" data-aos="fade-down">
            <h1 class="page-title">Galeri Landing Page</h1>
            <p style="color: var(--muted);">Tambahkan foto kenangan dari perangkat atau tautan eksternal untuk ditampilkan di halaman depan (index.php).</p>
        </div>

        <div style="display:flex; gap:30px; align-items: flex-start;">
            <div class="card" data-aos="fade-right" data-aos-delay="200" style="flex: 1; position: sticky; top: 40px;">
                <div class="card-header"><i class="fas fa-plus-circle"></i> Tambah Foto Baru</div>
                <div style="padding: 25px;">
                    <form method="POST" action="admin.php?menu=galeri" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label>Metode Input Gambar</label>
                            <select name="tipe_input" id="tipe_input" class="form-control" onchange="toggleInputType()">
                                <option value="url">Link Tautan (URL)</option>
                                <option value="upload">Upload File Lokal</option>
                            </select>
                        </div>

                        <div class="form-group" id="input_url">
                            <label>Tautan Gambar (URL) <span style="color:red">*</span></label>
                            <input type="text" name="gambar_url" class="form-control" placeholder="https://contoh.com/foto.jpg">
                            <span class="text-hint">Rekomendasi: Gunakan link Pinterest atau Unsplash untuk menghemat server.</span>
                        </div>

                        <div class="form-group" id="input_upload" style="display:none;">
                            <label>Pilih File Foto <span style="color:red">*</span></label>
                            <input type="file" name="gambar_file" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="padding-bottom: 35px;">
                            <span class="text-hint">Format: JPG, PNG, WEBP. Maksimum: 2MB.</span>
                        </div>

                        <div class="form-group">
                            <label>Caption / Judul (Opsional)</label>
                            <input type="text" name="caption" class="form-control" placeholder="Msl: Momen Pre-wedding di Bali">
                        </div>

                        <div class="form-group" style="display:flex; gap:15px;">
                            <div style="flex:1;">
                                <label>Nama Sumber Foto (Opsional)</label>
                                <input type="text" name="sumber_nama" class="form-control" placeholder="Msl: Teduh Visual">
                            </div>
                            <div style="flex:1;">
                                <label>Link Sumber Foto (Opsional)</label>
                                <input type="text" name="sumber_link" class="form-control" placeholder="Msl: https://instagram.com/...">
                            </div>
                        </div>

                        <button type="submit" name="tambah_galeri" class="btn-action btn-primary" style="width:100%; padding:14px; font-size:1rem; display:flex; justify-content:center; align-items:center; gap:8px;">
                            <i class="fas fa-upload"></i> Tambahkan ke Galeri
                        </button>
                    </form>
                </div>
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
                                <form method="POST" action="admin.php?menu=galeri" onsubmit="return confirm('Yakin ingin menghapus foto ini dari galeri beranda?');">
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
                document.querySelector('input[name="gambar_file"]').removeAttribute('required');
            } else {
                document.getElementById('input_url').style.display = 'none';
                document.getElementById('input_upload').style.display = 'block';
                document.querySelector('input[name="gambar_url"]').removeAttribute('required');
                document.querySelector('input[name="gambar_file"]').setAttribute('required', 'required');
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
                    <button type="submit" name="tambah_admin" class="btn-action btn-primary" style="width:100%; padding:12px; font-size:1rem; display:block; text-align:center;">Simpan Admin</button>
                </form>
            </div>

            <div class="card" data-aos="fade-left" data-aos-delay="300" style="flex: 2;">
                <div class="card-header"><i class="fas fa-users"></i> Daftar Admin Terdaftar</div>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Username</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_admin = mysqli_query($conn, "SELECT * FROM admin_users ORDER BY id ASC");
                        while($adm = mysqli_fetch_assoc($q_admin)) { ?>
                        <tr>
                            <td>#<?= $adm['id'] ?></td>
                            <td style="font-weight:600;"><?= $adm['username'] ?></td>
                            <td>
                                <?php if($adm['username'] != 'admin') { ?>
                                <form method="POST" action="admin.php?menu=admin" onsubmit="return confirm('Yakin ingin mencabut akses admin ini?');">
                                    <input type="hidden" name="tabel" value="admin_users">
                                    <input type="hidden" name="id_hapus" value="<?= $adm['id'] ?>">
                                    <button type="submit" name="hapus_data" class="btn-action btn-danger"><i class="fas fa-trash"></i> Cabut Akses</button>
                                </form>
                                <?php } else { echo "<span class='badge badge-yellow'>Super Admin</span>"; } ?>
                            </td>
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
        <?php } ?>

    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS Animations
        AOS.init({ once: true, duration: 800, offset: 50 });

        // Tampilkan Notifikasi Sistem
        <?= $notif; ?>

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
    </script>
</body>
</html>