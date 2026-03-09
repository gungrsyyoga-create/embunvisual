<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan DEDE - Bali Exclusive</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            width: 100%;
            background: #1a1f3a;
            border-radius: 20px;
            border: 2px solid #d4af37;
            padding: 60px 40px;
            box-shadow: 0 30px 80px rgba(212, 175, 55, 0.25);
            text-align: center;
        }
        
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #b5a0ff;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }
        
        .info-box {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid #d4af37;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 40px;
            text-align: left;
            color: #e0e0e0;
        }
        
        .info-box p {
            margin: 10px 0;
            color: #b5a0ff;
        }
        
        .info-box strong {
            color: #d4af37;
        }
        
        .menu {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid #d4af37;
            border-radius: 15px;
            text-decoration: none;
            color: #e0e0e0;
            transition: all 0.3s;
            min-height: 150px;
        }
        
        .menu-item:hover {
            background: rgba(212, 175, 55, 0.15);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.3);
        }
        
        .menu-item .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .menu-item .title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #d4af37;
            margin-bottom: 5px;
        }
        
        .menu-item .desc {
            font-size: 0.85rem;
            color: #b5a0ff;
            text-align: center;
        }
        
        .status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            color: #00ff41;
            font-size: 0.9rem;
        }
        
        .status::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #00ff41;
            border-radius: 50%;
            display: inline-block;
        }
        
        .docs {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #b5a0ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            text-align: left;
        }
        
        .docs h3 {
            color: #d4af37;
            margin-bottom: 15px;
        }
        
        .docs ul {
            list-style: none;
            color: #b5a0ff;
        }
        
        .docs li {
            margin: 8px 0;
            padding-left: 20px;
            position: relative;
        }
        
        .docs li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: #d4af37;
        }
        
        .docs a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 600;
        }
        
        .docs a:hover {
            text-decoration: underline;
        }
        
        .footer {
            margin-top: 40px;
            color: #999;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .menu {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💍 DEDE & PRASETYA 💍</h1>
        <p class="subtitle">Pernikahan Eksklusif di Bali</p>
        
        <div class="info-box">
            <p>📅 <strong>14 Maret 2026</strong> | 17:00 WITA</p>
            <p>📍 <strong>Royal Bali Beach Club</strong>, Sanur</p>
            <p>👔 Dress Code: <strong>Black Tie Optional</strong></p>
        </div>
        
        <div class="menu">
            <a href="setup.php" class="menu-item">
                <div class="icon">⚙️</div>
                <div class="title">Setup Database</div>
                <div class="desc">Inisialisasi database<br>Jalankan sekali saja</div>
            </a>
            
            <a href="index.php" class="menu-item">
                <div class="icon">🎫</div>
                <div class="title">Buka Undangan</div>
                <div class="desc">Lihat undangan lengkap<br>dengan RSVP & E-Tiket</div>
            </a>
            
            <a href="admin.php" class="menu-item">
                <div class="icon">📊</div>
                <div class="title">Admin Dashboard</div>
                <div class="desc">Kelola RSVP<br>dan pantau check-in</div>
            </a>
            
            <a href="README.md" class="menu-item">
                <div class="icon">📖</div>
                <div class="title">Dokumentasi</div>
                <div class="desc">Panduan lengkap<br>dan troubleshooting</div>
            </a>
        </div>
        
        <div class="docs">
            <h3>🚀 Quick Start</h3>
            <ul>
                <li><strong>Step 1:</strong> Klik <a href="setup.php">Setup Database</a> untuk inisialisasi</li>
                <li><strong>Step 2:</strong> Klik <a href="index.php">Buka Undangan</a> untuk melihat undangan</li>
                <li><strong>Step 3:</strong> Isi form RSVP dan dapatkan E-Tiket</li>
                <li><strong>Step 4:</strong> Lihat semua RSVP di <a href="admin.php">Admin Dashboard</a></li>
            </ul>
        </div>
        
        <div class="status">
            System Ready
        </div>
        
        <div class="footer">
            <p>Powered by Embun Visual © 2026</p>
            <p style="margin-top: 10px;">Self-contained Undangan System</p>
        </div>
    </div>
</body>
</html>
