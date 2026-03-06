<?php
// Tampilan Admin Mempelai (Premium Theme Dashboard)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mempelai | Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-main: #FFFDFc; 
            --bg-card: #FFFFFF;
            --primary: #C05C47; /* Terracotta */
            --primary-dark: #8B3A2B;
            --primary-light: #F7EAE8;
            --text-dark: #2C2C2C;
            --text-muted: #888888;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-dark); }
        .serif { font-family: 'Lora', serif; }

        .sidebar {
            width: 260px; height: 100vh; background: var(--bg-card); position: fixed;
            left: 0; top: 0; padding: 30px 20px; box-shadow: 4px 0 15px rgba(192, 92, 71, 0.05);
            display: flex; flex-direction: column;
        }
        .logo { font-size: 1.5rem; color: var(--primary); text-align: center; margin-bottom: 50px; }
        
        .nav-menu { list-style: none; }
        .nav-item { margin-bottom: 15px; }
        .nav-link {
            display: flex; align-items: center; gap: 15px; padding: 12px 20px;
            color: var(--text-muted); text-decoration: none; border-radius: 12px; transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--primary-light); color: var(--primary); font-weight: 500;
        }

        .main-content { margin-left: 260px; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .greeting { font-size: 1.8rem; color: var(--text-dark); }
        .greeting span { color: var(--primary); }

        /* Stat Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card {
            background: white; padding: 25px; border-radius: 20px; display: flex; align-items: center; gap: 20px;
            box-shadow: 0 10px 30px rgba(192, 92, 71, 0.05); border: 1px solid rgba(192, 92, 71, 0.1);
        }
        .stat-icon {
            width: 60px; height: 60px; border-radius: 15px; background: var(--primary-light);
            color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        .stat-info h3 { font-size: 2rem; color: var(--text-dark); margin-bottom: 5px; }
        .stat-info p { color: var(--text-muted); font-size: 0.9rem; }

        /* RSVP Table */
        .panel { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(192, 92, 71, 0.05); border: 1px solid rgba(192, 92, 71, 0.1); }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .panel-title { font-size: 1.2rem; color: var(--text-dark); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f0f0f0; }
        th { color: var(--text-muted); font-weight: 500; font-size: 0.9rem; }
        td { color: var(--text-dark); font-size: 0.95rem; }
        
        .badge { padding: 5px 12px; border-radius: 30px; font-size: 0.8rem; font-weight: 500; }
        .badge-hadir { background: #e6f4ea; color: #1e8e3e; }
        .badge-tidakhadir { background: #fce8e6; color: #d93025; }
        .badge-waiting { background: #fef7e0; color: #f29900; }

        /* Wishes Messages */
        .wish-list { display: flex; flex-direction: column; gap: 15px; }
        .wish-item { background: #fafafa; padding: 20px; border-radius: 15px; border-left: 4px solid var(--primary); }
        .wish-name { font-weight: 600; color: var(--primary-dark); margin-bottom: 8px; font-size: 1rem; }
        .wish-msg { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; font-style: italic; }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="logo serif">R & J <br><span style="font-size: 0.9rem; color: var(--text-muted); letter-spacing: 2px;">Premium Admin</span></h2>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="index.php" class="nav-link active"><i class="fas fa-home"></i> Beranda</a>
            </li>
            <li class="nav-item">
                <a href="#rsvp-data" class="nav-link"><i class="fas fa-clipboard-list"></i> Data Kehadiran</a>
            </li>
            <li class="nav-item">
                <a href="#doa-tamu" class="nav-link"><i class="fas fa-envelope-open-text"></i> Pesan & Doa</a>
            </li>
            <li class="nav-item" style="margin-top: 50px;">
                <a href="registrasi.php" class="nav-link"><i class="fas fa-qrcode"></i> <span style="font-size: 0.85rem;">Ke Portal Penerima Tamu</span></a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 class="greeting serif">Halo, <span>Romeo & Juliet</span></h1>
            <div style="background: white; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); font-size: 0.9rem; color: var(--text-muted);">
                <i class="far fa-calendar-alt"></i> 14 April 2026
            </div>
        </div>

        <!-- 3 Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3>245</h3>
                    <p>Total Undangan Disebar</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #e6f4ea; color: #1e8e3e;"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3>180</h3>
                    <p>Konfirmasi Hadir</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fce8e6; color: #d93025;"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info">
                    <h3>15</h3>
                    <p>Tidak Hadir</p>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            
            <!-- Table RSVP -->
            <div class="panel" id="rsvp-data">
                <div class="panel-header">
                    <h2 class="panel-title serif">Daftar Konfirmasi Hadir (RSVP)</h2>
                    <button style="background: var(--primary); color: white; border: none; padding: 8px 15px; border-radius: 20px; cursor: pointer; font-size: 0.85rem;"><i class="fas fa-download"></i> Export Excel</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Status Kehadiran</th>
                            <th>Jumlah Hadir</th>
                            <th>Waktu Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data Dummy PHP/MySQL siap disematkan di sini -->
                        <tr>
                            <td>Budi Santoso</td>
                            <td><span class="badge badge-hadir">Hadir</span></td>
                            <td>2 Orang</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">Hari ini, 08:30</td>
                        </tr>
                        <tr>
                            <td>Siti Aminah</td>
                            <td><span class="badge badge-hadir">Hadir</span></td>
                            <td>1 Orang</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">Kemarin, 14:15</td>
                        </tr>
                        <tr>
                            <td>Andi Darmawan</td>
                            <td><span class="badge badge-tidakhadir">Tidak Hadir</span></td>
                            <td>0 Orang</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">Kemarin, 20:00</td>
                        </tr>
                        <tr>
                            <td>Keluarga Wijaya</td>
                            <td><span class="badge badge-waiting">Belum Konfirmasi</span></td>
                            <td>-</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pesan Doa -->
            <div class="panel" id="doa-tamu">
                <div class="panel-header">
                    <h2 class="panel-title serif">Pesan & Doa</h2>
                </div>
                <div class="wish-list">
                    <div class="wish-item">
                        <h4 class="wish-name">Budi Santoso</h4>
                        <p class="wish-msg">"Selamat menempuh hidup baru sahabatku Romeo, semoga menjadi keluarga yang sakinah, mawaddah, warahmah."</p>
                    </div>
                    <div class="wish-item">
                        <h4 class="wish-name">Siti Aminah</h4>
                        <p class="wish-msg">"Lancar-lancar sampai hari H ya Juliet. Bahagia selalu!"</p>
                    </div>
                </div>
                <button style="width: 100%; padding: 12px; margin-top: 15px; background: transparent; border: 1px solid var(--primary); color: var(--primary); border-radius: 12px; cursor: pointer; font-weight: 500;">Lihat Semua Pesan</button>
            </div>

        </div>
    </main>

</body>
</html>
