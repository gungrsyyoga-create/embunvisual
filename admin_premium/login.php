<?php
/**
 * admin_premium/login.php - Client Portal Entry
 */
require_once dirname(__DIR__) . '/config/bootstrap.php';

// Auth Guard: Jika sudah login, tendang ke index.php
if (isset($_SESSION['klien_premium_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login_klien'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = md5($_POST['password']);

    $q = mysqli_query($conn, "
        SELECT kp.*, p.nama_pemesan, p.invoice, p.total_tagihan, p.tanggal_acara, p.tema_id, p.status_pembayaran, p.status_pengerjaan, 
               p.admin_id, k.nama_tema, a.username as nama_staff
        FROM klien_premium kp
        LEFT JOIN pesanan p ON kp.pesanan_id = p.id
        LEFT JOIN katalog_tema k ON p.tema_id = k.id
        LEFT JOIN admin_users a ON p.admin_id = a.id
        WHERE kp.username = '$username' AND kp.password = '$password' AND kp.is_active = 1
    ");

    if ($q && mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $_SESSION['klien_premium_id'] = $data['id'];
        $_SESSION['klien_pesanan_id'] = $data['pesanan_id'];
        $_SESSION['klien_nama'] = $data['nama_pemesan'];
        $_SESSION['klien_invoice'] = $data['invoice'];
        $_SESSION['klien_tema'] = $data['nama_tema'];
        $_SESSION['klien_tanggal'] = $data['tanggal_acara'];
        $_SESSION['klien_status_bayar'] = $data['status_pembayaran'];
        $_SESSION['klien_status_kerja'] = $data['status_pengerjaan'];
        $_SESSION['klien_admin_id'] = $data['admin_id'];
        $_SESSION['klien_nama_staff'] = $data['nama_staff'] ?? 'Tim Embun Visual';
        header("Location: index.php");
        exit;
    } else {
        $error = 'Username atau password salah. Hubungi admin untuk informasi akses.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Client • Embun Visual Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1A1614;
            --gold: #D4AF37;
            --gold-light: rgba(212,175,55,0.12);
            --bg: #FAF8F5;
            --border: #EAE3D9;
            --text: #2A2522;
            --muted: #6B6560;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-sans); background: var(--bg);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 20px;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(212,175,55,0.06), transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(212,175,55,0.04), transparent 50%);
        }
        .card {
            background: #fff; border-radius: 24px; padding: 60px 50px;
            width: 100%; max-width: 460px;
            border: 1px solid var(--border);
            box-shadow: 0 40px 80px rgba(0,0,0,0.07);
        }
        .logo {
            text-align: center; margin-bottom: 40px;
            font-family: var(--font-serif); font-size: 1.8rem; color: var(--primary);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
        }
        .logo i { color: var(--gold); font-size: 2rem; }
        .logo small { font-family: var(--font-sans); font-size: 0.75rem; letter-spacing: 4px; text-transform: uppercase; color: var(--muted); font-weight: 400; font-style: normal; }
        h1 {
            font-family: var(--font-serif); font-size: 1.8rem; color: var(--primary);
            font-style: italic; margin-bottom: 8px; text-align: center;
        }
        .sub { text-align: center; color: var(--muted); font-size: 0.9rem; margin-bottom: 35px; font-weight: 300; }
        .form-group { margin-bottom: 22px; }
        label { display: block; font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.9rem; }
        input {
            width: 100%; padding: 16px 18px 16px 44px;
            border: 1px solid var(--border); border-radius: 12px;
            font-family: var(--font-sans); font-size: 0.95rem;
            background: var(--bg); color: var(--text); outline: none;
            transition: all 0.3s;
        }
        input:focus { border-color: var(--gold); background: #fff; box-shadow: 0 0 0 3px rgba(212,175,55,0.1); }
        .btn {
            width: 100%; padding: 18px; background: var(--primary); color: #fff;
            border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600;
            letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
            transition: all 0.3s; margin-top: 10px;
        }
        .btn:hover { background: var(--gold); }
        .error {
            background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
            padding: 12px 16px; border-radius: 10px; font-size: 0.85rem;
            margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
        }
        .divider { text-align: center; color: var(--muted); font-size: 0.8rem; margin-top: 30px; }
        .divider a { color: var(--gold); text-decoration: none; }
        @media (max-width: 500px) { .card { padding: 40px 25px; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <i class="fas fa-leaf"></i>
            <span>Embun Visual</span>
            <small>Client Portal</small>
        </div>

        <h1>Selamat Datang</h1>
        <p class="sub">Masuk ke portal eksklusif Anda untuk memantau proyek undangan digital.</p>

        <?php if ($error): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username dari admin" required autocomplete="username">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password dari admin" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" name="login_klien" class="btn">
                <i class="fas fa-sign-in-alt"></i> Masuk ke Portal
            </button>
        </form>

        <p class="divider">
            Belum dapat akses? Hubungi kami via
            <a href="https://wa.me/6281234567890" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        </p>
    </div>
</body>
</html>
