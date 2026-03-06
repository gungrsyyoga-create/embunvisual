<?php
// Tampilan Admin Registrasi (Penerima Tamu / Scanner)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Registrasi Tamu | Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --bg-main: #f8fafc; 
            --bg-card: #FFFFFF;
            --primary: #C05C47; 
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-dark); display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .serif { font-family: 'Lora', serif; }

        .container {
            width: 100%; max-width: 500px;
        }

        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 2rem; color: var(--primary); margin-bottom: 5px; }
        .header p { color: var(--text-muted); font-size: 0.95rem; }

        .card-scanner {
            background: white; border-radius: 20px; padding: 30px;
            box-shadow: 0 10px 40px rgba(192, 92, 71, 0.1); border: 1px solid rgba(192, 92, 71, 0.1);
            text-align: center; margin-bottom: 20px;
        }

        .scanner-frame {
            width: 250px; height: 250px; margin: 0 auto 20px;
            border: 3px dashed var(--primary); border-radius: 15px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: #fafafa; color: var(--text-muted); position: relative;
        }
        
        /* Laser Animation */
        .scanner-frame::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 2px;
            background: var(--primary); box-shadow: 0 0 10px var(--primary);
            animation: scan 2s infinite linear alternate;
        }

        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }

        .form-manual { display: flex; gap: 10px; margin-top: 20px; }
        .input-manual {
            flex: 1; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px;
            font-size: 0.9rem; transition: 0.3s;
        }
        .input-manual:focus { outline: none; border-color: var(--primary); background: white; }
        
        .btn-check {
            background: var(--primary); color: white; border: none; padding: 12px 20px;
            border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s;
        }
        .btn-check:hover { background: #8B3A2B; }

        .log-panel {
            background: white; border-radius: 15px; padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;
        }
        .log-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .log-item:last-child { border-bottom: none; }
        
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1 class="serif">Buku Tamu Digital</h1>
            <p>Receptionist Dashboard • Romeo & Juliet</p>
        </div>

        <div class="card-scanner">
            <h3 style="margin-bottom: 20px; color: var(--text-dark);">Arahkan QR Code Tamu</h3>
            
            <div class="scanner-frame" onclick="simulateScan()">
                <i class="fas fa-qrcode" style="font-size: 4rem; opacity: 0.2; margin-bottom: 15px;"></i>
                <span style="font-size: 0.85rem; letter-spacing: 1px;">KAMERA AKTIF</span>
            </div>
            
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px;">Atau cari nama tamu secara manual:</p>
            
            <form class="form-manual" onsubmit="manualCheck(event)">
                <input type="text" id="manualName" class="input-manual" placeholder="Ketik nama tamu..." required>
                <button type="submit" class="btn-check"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="log-panel">
            <h4 style="margin-bottom: 15px; color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-history"></i> Log Kedatangan Terbaru</h4>
            <div id="logContainer">
                <!-- Log dummy -->
                <div class="log-item">
                    <div>
                        <p style="font-weight: 600; font-size: 0.95rem; color: var(--text-dark);">Bapak Haryanto</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">VIP • 2 Orang</p>
                    </div>
                    <span style="color: #10b981; font-size: 0.8rem; font-weight: 600;"><i class="fas fa-check-circle"></i> 09:15</span>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="index.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard Utama</a>
        </div>
    </div>

    <script>
        // Simulasi scan QR untuk demonstrasi desain (Karena JS QR butuh webcam lib)
        function simulateScan() {
            Swal.fire({
                title: 'Memindai QR...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => { Swal.showLoading() }
            }).then(() => {
                showSuccess("Andi Darmawan", 1);
            });
        }

        // Simulasi cari manual
        function manualCheck(e) {
            e.preventDefault();
            let name = document.getElementById("manualName").value;
            showSuccess(name, 2); // default 2 orang untuk demo
            document.getElementById("manualName").value = '';
        }

        function showSuccess(nama, pax) {
            // Animasi berhasil masuk
            Swal.fire({
                icon: 'success',
                title: 'Tamu Terverifikasi!',
                html: `
                    <h2 style="color: #C05C47; margin: 10px 0;">${nama}</h2>
                    <p style="font-weight: bold; color: #555;">Kapasitas: ${pax} Orang</p>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 10px;">Silakan persilakan tamu masuk.</p>
                `,
                confirmButtonColor: '#C05C47',
                confirmButtonText: 'Tamu Masuk ✅'
            });

            // Tambah ke log
            let time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            let newLog = `
                <div class="log-item" style="animation: fadeIn 0.5s;">
                    <div>
                        <p style="font-weight: 600; font-size: 0.95rem; color: var(--text-dark);">${nama}</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Tamu Reguler • ${pax} Orang</p>
                    </div>
                    <span style="color: #10b981; font-size: 0.8rem; font-weight: 600;"><i class="fas fa-check-circle"></i> ${time}</span>
                </div>
            `;
            document.getElementById("logContainer").insertAdjacentHTML('afterbegin', newLog);
        }
    </script>
</body>
</html>
