<?php
/**
 * DEMO: Complete Walkthrough untuk Folder & RSVP System
 * File ini menunjukkan alur lengkap dari folder generation hingga RSVP
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

include dirname(dirname(__FILE__)) . '/config.php';

$current_step = $_GET['step'] ?? 1;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>System Demo - Folder & RSVP Walkthrough</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 40px;
            text-align: center;
        }
        
        header h1 { font-size: 2.2rem; margin-bottom: 10px; }
        header p { font-size: 1.1rem; opacity: 0.9; }
        
        .steps-nav {
            display: flex;
            background: #f5f5f5;
            border-bottom: 1px solid #ddd;
            overflow-x: auto;
        }
        
        .step-btn {
            flex: 1;
            padding: 12px;
            text-align: center;
            border: none;
            background: #f5f5f5;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
            font-weight: 600;
            color: #666;
        }
        
        .step-btn.active {
            background: white;
            border-bottom-color: #667eea;
            color: #667eea;
        }
        
        .step-btn:hover {
            background: #f0f0f0;
        }
        
        .step-btn a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .content {
            padding: 40px;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
            animation: slideIn 0.3s ease-in;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .description {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            line-height: 1.6;
        }
        
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.5;
        }
        
        .info-box {
            background: #e7f3ff;
            border: 1px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            line-height: 1.6;
        }
        
        .success-box {
            background: #d4edda;
            border: 1px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            color: #155724;
            line-height: 1.6;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            color: #856404;
            line-height: 1.6;
        }
        
        .action-button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            margin: 10px 10px 10px 0;
            transition: all 0.3s;
            border: 2px solid #667eea;
            font-weight: 600;
            cursor: pointer;
        }
        
        .action-button:hover {
            background: white;
            color: #667eea;
        }
        
        .action-button.secondary {
            background: white;
            color: #667eea;
        }
        
        .action-button.secondary:hover {
            background: #667eea;
            color: white;
        }
        
        .screenshot-alt {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 0.85em;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        ul {
            margin-left: 20px;
            line-height: 1.8;
        }
        
        li {
            margin: 10px 0;
        }
        
        .divider {
            border-top: 2px solid #eee;
            margin: 30px 0;
        }
        
        footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 0.9em;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class='container'>
        <header>
            <h1>🚀 Folder & RSVP System Demo</h1>
            <p>Step-by-step walkthrough untuk mengimplementasikan sistem folder klien</p>
        </header>
        
        <div class='steps-nav'>
            <button class='step-btn <?= $current_step == 1 ? 'active' : '' ?>' onclick="window.location.href='?step=1'">1️⃣ Setup</button>
            <button class='step-btn <?= $current_step == 2 ? 'active' : '' ?>' onclick="window.location.href='?step=2'">2️⃣ Admin Login</button>
            <button class='step-btn <?= $current_step == 3 ? 'active' : '' ?>' onclick="window.location.href='?step=3'">3️⃣ Folder Gen</button>
            <button class='step-btn <?= $current_step == 4 ? 'active' : '' ?>' onclick="window.location.href='?step=4'">4️⃣ Test Tiers</button>
            <button class='step-btn <?= $current_step == 5 ? 'active' : '' ?>' onclick="window.location.href='?step=5'">5️⃣ RSVP Form</button>
            <button class='step-btn <?= $current_step == 6 ? 'active' : '' ?>' onclick="window.location.href='?step=6'">6️⃣ Wrap Up</button>
        </div>
        
        <div class='content'>
            <!-- STEP 1 -->
            <div class='step-content <?= $current_step == 1 ? 'active' : '' ?>'>
                <h2>1️⃣ Setup & Database Preparation</h2>
                <div class='description'>
                    Sebelum memulai, kita perlu memastikan database dan folder structure sudah siap.
                </div>
                
                <h3>✓ Checklist untuk Step ini:</h3>
                <ul>
                    <li>✅ Database schema updated (kolom tipe, folder_path, is_active)</li>
                    <li>✅ Template files created (tema_basic_rsvp.php, tema_premium_rsvp.php, tema_exclusive_rsvp.php)</li>
                    <li>✅ Test data pesanan created dengan status 'Lunas'</li>
                    <li>✅ /undangan folder structure dibuat</li>
                </ul>
                
                <div class='divider'></div>
                
                <h3>Jalankan Setup Script:</h3>
                <div class='action-button' onclick="window.open('/embunvisual/tools/setup_folder_system.php', '_blank')">
                    ▶️ Run Setup Script
                </div>
                
                <div class='divider'></div>
                
                <h3>Output yang diharapkan:</h3>
                <div class='success-box'>
                    <strong>✓ Setup Summary</strong><br>
                    Steps Completed: 10+<br>
                    Errors Found: 0
                </div>
                
                <div class='action-button' onclick="window.location.href='?step=2'">
                    Next: Admin Login →
                </div>
            </div>
            
            <!-- STEP 2 -->
            <div class='step-content <?= $current_step == 2 ? 'active' : '' ?>'>
                <h2>2️⃣ Login Sebagai Super Admin</h2>
                <div class='description'>
                    Hanya Super Admin yang bisa mengakses folder_manager. Login dengan kredensial admin Anda.
                </div>
                
                <h3>Login Steps:</h3>
                <ol style='margin-left: 20px; line-height: 1.8;'>
                    <li>Buka admin panel: <code>http://localhost/embunvisual/admin.php</code></li>
                    <li>Masukkan username & password admin Anda</li>
                    <li>Verify role = "Super Admin" (lihat di top-right dashboard)</li>
                </ol>
                
                <div class='info-box'>
                    <strong>💡 Tips:</strong><br>
                    Jika belum punya admin dengan role 'Super Admin', pastikan created saat setup
                </div>
                
                <div class='screenshot-alt'>Dashboard Super Admin
┌─────────────────────────────────┐
│ 👑 Super Admin                  │
│ embun_admin                      │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│ 📊 Dashboard                    │
│ 📋 Pesanan                      │
│ 👥 Klien Premium                │
│ 🗂️ Kelola Folder Klien       │  ← CLICK THIS
│ ⚙️ Pengaturan                  │
│ 🚪 Logout                       │
└─────────────────────────────────┘
                </div>
                
                <div class='action-button' onclick="window.open('/embunvisual/admin.php', '_blank')">
                    ▶️ Open Admin Panel
                </div>
                
                <div class='action-button' onclick="window.location.href='?step=3'">
                    Next: Folder Generation →
                </div>
            </div>
            
            <!-- STEP 3 -->
            <div class='step-content <?= $current_step == 3 ? 'active' : '' ?>'>
                <h2>3️⃣ Folder Generation via Super Admin</h2>
                <div class='description'>
                    Super Admin akan membuat folder untuk client dan assign tier (basic/premium/exclusive).
                    Proses ini otomatis membuat folder, copy template, dan update database.
                </div>
                
                <h3>Process Flow:</h3>
                <div class='screenshot-alt'>
1. Admin buka menu: 🗂️ Kelola Folder Klien
   ↓
2. Lihat daftar pesanan dengan status "Lunas"
   ├─ Invoice | Klien | Tema | Tier | Folder | Status | Aksi
   ├─ INV-001 | Client | -    | -    | Belum  | Aktif  | [Dropdown+Button]
   ├─ INV-002 | Client | -    | -    | Belum  | Aktif  | [Dropdown+Button]
   ↓
3. Pilih tier dari dropdown: basic / premium / exclusive
   ↓
4. Click tombol "Buat Folder"
   ↓
5. System otomatis:
   ├─ Validasi pesanan status = 'Lunas'
   ├─ Create folder: /undangan/{tier}/{nama-klien}/
   ├─ Copy template ke: {folder}/index.php
   ├─ Update DB: klien_premium.tipe = tier
   ├─ Update DB: klien_premium.folder_path = path
   ├─ Log activity ke audit_logs
   ↓
6. ✓ Berhasil! Folder path ditampilkan di tabel
                </div>
                
                <div class='divider'></div>
                
                <h3>Test Folder Generation:</h3>
                <div class='action-button' onclick="window.open('/embunvisual/tools/test_folder_rsvp.php', '_blank')">
                    ▶️ Go to Test Page
                </div>
                
                <div class='warning-box'>
                    <strong>⚠️ Manual Method:</strong><br>
                    Jika test page tidak bisa generate, bisa gunakan admin panel:<br>
                    Menu: 🗂️ Kelola Folder Klien (di sidebar)
                </div>
                
                <div class='action-button' onclick="window.location.href='?step=4'">
                    Next: Test All Tiers →
                </div>
            </div>
            
            <!-- STEP 4 -->
            <div class='step-content <?= $current_step == 4 ? 'active' : '' ?>'>
                <h2>4️⃣ Test Invitation Templates</h2>
                <div class='description'>
                    Setelah folder dibuat, akses invitation page untuk setiap tier.
                    Setiap tier memiliki tampilan dan fitur yang berbeda.
                </div>
                
                <h3>Test URLs (ganti dengan data Anda):</h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Tier</th>
                            <th>URL Pattern</th>
                            <th>Fitur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Basic</strong></td>
                            <td><code>/undangan/basic/nama/index.php?pid=1</code></td>
                            <td>Simple RSVP form, purple gradient</td>
                        </tr>
                        <tr>
                            <td><strong>Premium</strong></td>
                            <td><code>/undangan/premium/nama/index.php?pid=2</code></td>
                            <td>Countdown timer, RSVP stats, elegant design</td>
                        </tr>
                        <tr>
                            <td><strong>Exclusive</strong></td>
                            <td><code>/undangan/exclusive/nama/index.php?pid=3&mode=invitation</code></td>
                            <td>VIP theme, 3-mode tabs, barcode</td>
                        </tr>
                    </tbody>
                </table>
                
                <h3>Tier Comparison:</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Basic</th>
                            <th>Premium</th>
                            <th>Exclusive</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>RSVP Form</td>
                            <td>✓</td>
                            <td>✓</td>
                            <td>✓</td>
                        </tr>
                        <tr>
                            <td>Countdown Timer</td>
                            <td>✗</td>
                            <td>✓</td>
                            <td>✓</td>
                        </tr>
                        <tr>
                            <td>RSVP Statistics</td>
                            <td>✗</td>
                            <td>✓</td>
                            <td>✓</td>
                        </tr>
                        <tr>
                            <td>Barcode Scanning</td>
                            <td>✗</td>
                            <td>✗</td>
                            <td>✓</td>
                        </tr>
                        <tr>
                            <td>Mode Tabs</td>
                            <td>✗</td>
                            <td>✗</td>
                            <td>✓</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class='divider'></div>
                
                <h3>Test dengan membuka links di browser:</h3>
                <div class='action-button' onclick="window.open('/embunvisual/tools/test_folder_rsvp.php', '_blank')">
                    ▶️ Get Access URLs
                </div>
                
                <div class='action-button' onclick="window.location.href='?step=5'">
                    Next: Test RSVP Form →
                </div>
            </div>
            
            <!-- STEP 5 -->
            <div class='step-content <?= $current_step == 5 ? 'active' : '' ?>'>
                <h2>5️⃣ Test RSVP Submission</h2>
                <div class='description'>
                    Guest akan mengisi RSVP form dan data akan tersimpan di database.
                    Test dengan submit form dari berbagai tier.
                </div>
                
                <h3>Test RSVP Flow:</h3>
                <ol style='margin-left: 20px; line-height: 1.8;'>
                    <li>Buka invitation page (dari Step 4)</li>
                    <li>Scroll ke section "Konfirmasi Kehadiran"</li>
                    <li>Isi form RSVP:
                        <ul>
                            <li>Nama: "Budi Santoso"</li>
                            <li>Phone: "62812345678" (optional)</li>
                            <li>Status: "Hadir" atau "Tidak Hadir"</li>
                            <li>Jumlah: 1-10 orang</li>
                        </ul>
                    </li>
                    <li>Click "Kirim Respons"</li>
                    <li>Lihat success message: "✓ Terima kasih!"</li>
                </ol>
                
                <h3>Database Verification:</h3>
                <p>Data RSVP disimpan di tabel <code>tamu_undangan</code>:</p>
                
                <div class='code-block'>
SQL Query:
SELECT * FROM tamu_undangan 
WHERE pesanan_id = {pid}
ORDER BY created_at DESC
LIMIT 10;

Expected Result:
┌────┬────────────┬──────────────────┬──────────────┬──────────────┬─────────────┐
│ id │pesanan_id  │ nama_tamu        │ no_whatsapp  │ status_rsvp  │ jumlah_hadir│
├────┼────────────┼──────────────────┼──────────────┼──────────────┼─────────────┤
│ 1  │ 1          │ Budi Santoso     │ 62812345678  │ Hadir        │ 2           │
│ 2  │ 1          │ Ahmad Ibrahim    │ 62877654321  │ Tidak Hadir  │ 1           │
└────┴────────────┴──────────────────┴──────────────┴──────────────┴─────────────┘
                </div>
                
                <div class='divider'></div>
                
                <h3>Real-time Statistics:</h3>
                <p>Premium & Exclusive tier menampilkan statistic real-time:</p>
                <ul>
                    <li><strong>Hadir:</strong> COUNT(*) dari tamu_undangan WHERE status_rsvp='Hadir'</li>
                    <li><strong>Tidak Hadir:</strong> COUNT(*) dari tamu_undangan WHERE status_rsvp='Tidak Hadir'</li>
                    <li>Stats UPDATE otomatis setiap kali ada submit RSVP baru</li>
                </ul>
                
                <div class='action-button' onclick="window.location.href='?step=6'">
                    Next: Wrap Up →
                </div>
            </div>
            
            <!-- STEP 6 -->
            <div class='step-content <?= $current_step == 6 ? 'active' : '' ?>'>
                <h2>6️⃣ System Implementation Complete! 🎉</h2>
                <div class='success-box'>
                    <strong>✅ Semua komponen sudah siap!</strong>
                </div>
                
                <h3>Summary - Apa yang sudah diimplementasikan:</h3>
                
                <div class='info-box'>
                    <strong>✓ Database Schema</strong><br>
                    - klien_premium: tipe, folder_path, is_active<br>
                    - admin_users: role ENUM (Super Admin, Staff, Basic)<br>
                    - barcode_scans: untuk exclusive tier check-in<br>
                    - tamu_undangan: RSVP tracking
                </div>
                
                <div class='info-box'>
                    <strong>✓ Template Files (3 tier)</strong><br>
                    - tema_basic_rsvp.php: Simple purple design<br>
                    - tema_premium_rsvp.php: Gold elegant + countdown<br>
                    - tema_exclusive_rsvp.php: Dark VIP + barcode + 3-modes
                </div>
                
                <div class='info-box'>
                    <strong>✓ Admin Panel</strong><br>
                    - 🗂️ Kelola Folder Klien menu<br>
                    - Auto-generate folder structure<br>
                    - Template file copying<br>
                    - Tier assignment & database updates
                </div>
                
                <div class='info-box'>
                    <strong>✓ RSVP System</strong><br>
                    - Form validation & submission<br>
                    - Database storage di tamu_undangan<br>
                    - Real-time statistics display<br>
                    - Upsert logic (update or insert)
                </div>
                
                <div class='info-box'>
                    <strong>✓ Special Features</strong><br>
                    - Countdown timer (Premium & Exclusive)<br>
                    - Barcode generation via JsBarcode<br>
                    - Mode tabs (Exclusive: Undangan/Barcode/RSVP)<br>
                    - Role-based access control
                </div>
                
                <div class='divider'></div>
                
                <h3>🚀 Next Steps untuk Production:</h3>
                <ol style='margin-left: 20px; line-height: 1.8;'>
                    <li><strong>Staff Upload Interface:</strong> Biarkan staff upload custom code ke folder</li>
                    <li><strong>Barcode Scanning UI:</strong> Interface untuk scan barcode di event</li>
                    <li><strong>Analytics Dashboard:</strong> Reports RSVP & attendance</li>
                    <li><strong>Email Notifications:</strong> Notif ke guest saat RSVP submitted</li>
                    <li><strong>Custom CSS/JS:</strong> Allow client customize tampilan undangan</li>
                </ol>
                
                <div class='divider'></div>
                
                <h3>📚 Quick Links:</h3>
                <div class='action-button' onclick="window.open('/embunvisual/admin.php?menu=folder_manager', '_blank')">
                    🗂️ Kelola Folder Klien
                </div>
                
                <div class='action-button' onclick="window.open('/embunvisual/tools/test_folder_rsvp.php', '_blank')">
                    🧪 Test Page & URLs
                </div>
                
                <div class='action-button' onclick="window.open('/embunvisual/tools/setup_folder_system.php', '_blank')">
                    🔧 Setup Script
                </div>
                
                <div class='action-button secondary' onclick="window.location.href='?step=1'">
                    ↻ Start Over
                </div>
            </div>
        </div>
        
        <footer>
            <p>🎉 Folder & RSVP Management System v1.0 | March 8, 2026</p>
            <p>Created for Embun Visual - Digital Invitation Management Platform</p>
        </footer>
    </div>
    
    <script>
        // Auto-scroll ke header pada load
        document.querySelector('header').scrollIntoView(true);
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>
