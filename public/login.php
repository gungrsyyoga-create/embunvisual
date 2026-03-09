<?php
// Setting Session Timeout (24 Jam)
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);

// Load bootstrap dengan struktur baru
require_once __DIR__ . '/../config/bootstrap.php'; 

// Jika sudah login, tendang ke admin.php
if(isset($_SESSION['admin_embun'])) {
    header("Location: ../admin.php");
    exit;
}

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
        $row_admin = mysqli_fetch_assoc($cek);
        $_SESSION['admin_embun'] = true;
        $_SESSION['admin_id'] = $row_admin['id'];
        $_SESSION['admin_username'] = $row_admin['username'];
        $_SESSION['admin_role'] = $row_admin['role'];
        header("Location: ../admin.php");
        exit;
    } else {
        $error_login = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Login | Embun Visual HQ</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4A5D4E; /* Hijau Bumi (Embun Visual) */
            --primary-dark: #3b4b3e;
            --surface: #ffffff;
            --background: #f4f6f3;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--background);
            background-image: radial-gradient(circle at top right, rgba(74, 93, 78, 0.08) 0%, transparent 40%),
                              radial-gradient(circle at bottom left, rgba(74, 93, 78, 0.05) 0%, transparent 40%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-main);
            overflow: hidden;
        }

        /* Ambient Ornaments */
        .blob-1 {
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(74, 93, 78, 0.1);
            filter: blur(80px);
            border-radius: 50%;
            top: -100px; right: -100px;
            z-index: -1;
            animation: float 8s infinite alternate ease-in-out;
        }
        
        .blob-2 {
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(148, 163, 184, 0.15);
            filter: blur(60px);
            border-radius: 50%;
            bottom: -50px; left: -100px;
            z-index: -1;
            animation: float 6s infinite alternate-reverse ease-in-out;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 40px); }
        }

        /* Login Card Container */
        .login-wrapper {
            display: flex;
            background: var(--surface);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px;
            max-width: 95%;
            min-height: 550px;
            border: 1px solid rgba(255,255,255,0.4);
            position: relative;
            z-index: 10;
        }

        /* Left Side: Branding / Image */
        .login-brand {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
            opacity: 0.5;
            z-index: 1;
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
        }

        .brand-text {
            z-index: 2;
            margin-top: auto;
        }

        .brand-text h2 {
            font-size: 2.2rem;
            line-height: 1.2;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .brand-text p {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.5;
        }

        /* Right Side: Login Form */
        .login-form-container {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--surface);
        }

        .login-header {
            margin-bottom: 35px;
        }

        .login-header h3 {
            font-size: 1.8rem;
            color: var(--text-main);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
            background: #fdfdfd;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: var(--surface);
            box-shadow: 0 0 0 4px rgba(74, 93, 78, 0.1);
        }

        .form-control:focus + i {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(74, 93, 78, 0.25);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Error Alert */
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #ef4444;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                min-height: auto;
            }
            .login-brand {
                padding: 40px 30px;
            }
            .brand-text h2 {
                font-size: 1.6rem;
            }
            .login-form-container {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Background Blobs -->
    <div class="blob-1"></div>
    <div class="blob-2"></div>

    <div class="login-wrapper" data-aos="zoom-in" data-aos-duration="1000">
        
        <!-- Left Branding Panel -->
        <div class="login-brand">
            <div class="brand-logo" data-aos="fade-down" data-aos-delay="300">
                <i class="fas fa-leaf"></i> Embun Visual
            </div>
            <div class="brand-text" data-aos="fade-up" data-aos-delay="400">
                <h2>Kelola Bisnis<br>Dengan Sempurna.</h2>
                <p>Akses sistem manajemen jadwal, pesanan klien, dan pengiriman undangan digital terpadu dalam satu platform eksklusif.</p>
            </div>
        </div>

        <!-- Right Login Panel -->
        <div class="login-form-container">
            <div class="login-header" data-aos="fade-left" data-aos-delay="500">
                <h3>Selamat Datang</h3>
                <p>Silakan masuk ke akun Admin Workspace Anda.</p>
            </div>

            <?php if(isset($error_login)) { ?>
                <div class="alert-error" data-aos="fade-right">
                    <i class="fas fa-exclamation-circle"></i> <?= $error_login ?>
                </div>
            <?php } ?>

            <form method="POST" data-aos="fade-up" data-aos-delay="600">
                <div class="form-group">
                    <label>Username / Akses ID</label>
                    <div class="input-icon-wrapper">
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda..." required autocomplete="off">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password Rahasia</label>
                    <div class="input-icon-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">
                    Masuk ke Sistem <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div style="text-align:center; margin-top:30px; font-size:0.8rem; color:var(--text-muted);" data-aos="fade-up" data-aos-delay="800">
                <i class="fas fa-shield-alt"></i> Dilindungi enkripsi sistem Embun Visual
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true });
    </script>
</body>
</html>
