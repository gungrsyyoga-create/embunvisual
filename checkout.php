<?php
include 'config.php';

// Ambil nomor invoice dari URL (misal: checkout.php?inv=INV-20240101-123)
if (!isset($_GET['inv'])) {
    die("Invoice tidak ditemukan.");
}

$invoice = mysqli_real_escape_string($conn, $_GET['inv']);

// Cari data pesanan beserta nama temanya
$query = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id = k.id WHERE p.invoice = '$invoice'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Pesanan tidak valid.");
}

// PROSES KONFIRMASI PEMBAYARAN
if(isset($_POST['konfirmasi_bayar'])){
    // Simpan email pemesan jika diisi
    $email_pemesan = mysqli_real_escape_string($conn, trim($_POST['email_pemesan'] ?? ''));
    
    // Ubah status & simpan email
    mysqli_query($conn, "UPDATE pesanan SET status_pembayaran='Menunggu Konfirmasi', email_pemesan='$email_pemesan' WHERE invoice='$invoice'");
    
    // Siapkan pesan WA otomatis
    $nomor_admin = "6281234567890"; // <- GANTI NOMOR WA KAMU
    $email_info  = $email_pemesan ? "%0A*Email:* $email_pemesan" : '';
    $pesan_wa = "Halo Admin Embun Visual,%0A%0ASaya sudah melakukan pembayaran untuk:%0A*No. Invoice:* $invoice%0A*Nama:* {$data['nama_pemesan']}$email_info%0A*Total:* Rp " . number_format($data['total_tagihan'],0,',','.') . "%0A%0ABerikut saya lampirkan bukti transfernya.";
    
    // Arahkan ke WA
    echo "<script>
            alert('Terima kasih! Anda akan diarahkan ke WhatsApp untuk mengirimkan bukti transfer.');
            window.location.href = 'https://api.whatsapp.com/send?phone=$nomor_admin&text=$pesan_wa';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran | Embun Visual</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { 
            --primary: #121413;       
            --bg-body: #F9F8F6;       
            --surface: #FFFFFF;
            --text-main: #1A1A1A; 
            --text-muted: #737373; 
            --gold: #C9A66B;          
            --border: #E5E2DC;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: var(--font-sans); 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
            padding: 40px 20px;
            background-image: radial-gradient(circle at top right, rgba(201, 166, 107, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at bottom left, rgba(18, 20, 19, 0.03) 0%, transparent 40%);
        }
        
        .checkout-wrapper {
            width: 100%; max-width: 650px;
        }

        .checkout-container { 
            background: var(--surface); 
            border-radius: 24px; 
            box-shadow: 0 40px 80px rgba(0,0,0,0.06); 
            overflow: hidden; 
            border: 1px solid rgba(229, 226, 220, 0.7);
            position: relative;
        }
        
        .header { 
            background: var(--primary); color: white; 
            padding: 40px; text-align: center; 
            position: relative; overflow: hidden;
        }
        .header::before {
            content: ''; position: absolute; top: -50%; right: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(201, 166, 107, 0.1) 0%, transparent 60%);
            pointer-events: none;
        }
        
        .header h1 { 
            margin: 0 0 10px 0; font-size: 2.2rem; 
            font-family: var(--font-serif); font-style: italic; font-weight: 400;
            position: relative; z-index: 1;
        }
        .header p { 
            margin: 0; opacity: 0.8; font-size: 0.95rem; font-weight: 300; letter-spacing: 1px;
            position: relative; z-index: 1; margin-bottom: 10px;
        }
        .header-invoice-box {
            display: inline-flex; align-items: center; justify-content: center; gap: 15px;
            background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px; border-radius: 50px; position: relative; z-index: 1;
            backdrop-filter: blur(5px);
        }
        .header-invoice-box strong { font-family: var(--font-serif); font-weight: 600; font-size: 1.15rem; color: #fff; letter-spacing: 1px; }
        .btn-copy-inv {
            background: rgba(201, 166, 107, 0.9); color: white; border: none; width: 32px; height: 32px; 
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: var(--transition); font-size: 0.85rem;
        }
        .btn-copy-inv:hover { background: #fff; color: var(--gold); }

        .content { padding: 40px; }
        
        .status-container { text-align: center; margin-bottom: 30px; }
        .status-badge { 
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 500; 
            background: rgba(201, 166, 107, 0.1); color: #A88143; 
            border: 1px solid rgba(201, 166, 107, 0.2);
            letter-spacing: 1px; text-transform: uppercase;
        }
        
        .order-summary { 
            background: rgba(249, 248, 246, 0.6); 
            border: 1px solid var(--border); 
            padding: 25px 30px; border-radius: 16px; margin-bottom: 40px; 
        }
        .summary-row { 
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 15px; font-size: 0.95rem; font-weight: 300;
        }
        .summary-row:last-child:not(.total) { margin-bottom: 0; }
        .summary-row.total { 
            border-top: 1px dashed #D5D2CC; padding-top: 20px; margin-top: 20px; 
            font-family: var(--font-serif); font-style: italic; font-size: 1.4rem; color: var(--primary); 
        }
        
        .section-title {
            font-family: var(--font-serif); font-size: 1.4rem; color: var(--primary);
            margin-bottom: 20px; font-style: italic; font-weight: 400; text-align: center;
        }
        
        .payment-methods { margin-bottom: 35px; }
        .method-card { 
            border: 1px solid var(--border); border-radius: 16px; padding: 20px 25px; 
            margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between; 
            transition: var(--transition); background: var(--surface);
        }
        .method-card:hover { 
            border-color: var(--gold); box-shadow: 0 10px 25px rgba(201, 166, 107, 0.08); 
            transform: translateY(-2px);
        }
        
        .method-info { display: flex; align-items: center; gap: 20px; }
        .method-icon { 
            width: 50px; height: 50px; border-radius: 12px; background: rgba(249, 248, 246, 1);
            display: flex; justify-content: center; align-items: center;
            font-size: 1.4rem; color: var(--primary);
            border: 1px solid var(--border);
        }
        .method-details h4 { margin: 0 0 5px 0; color: var(--text-main); font-weight: 500; font-size: 1rem; }
        .method-details p { margin: 0; color: var(--text-muted); font-size: 0.95rem; font-weight: 400; }
        .method-details span { display: block; font-size: 0.8rem; color: #A0A0A0; margin-top: 3px; font-weight: 300;}
        
        .btn-copy { 
            background: transparent; color: var(--gold); border: 1px solid rgba(201, 166, 107, 0.4); 
            padding: 8px 18px; border-radius: 50px; font-size: 0.8rem; font-weight: 500; cursor: pointer; 
            transition: var(--transition); letter-spacing: 1px; text-transform: uppercase;
        }
        .btn-copy:hover { background: var(--gold); color: white; }

        .action-buttons {
            display: flex; gap: 15px; margin-top: 20px; 
        }

        .btn-confirm { 
            flex: 2; background: var(--primary); color: white; border: 1px solid var(--primary); 
            padding: 18px; border-radius: 12px; font-size: 0.95rem; font-weight: 400; 
            cursor: pointer; transition: var(--transition); 
            display: flex; justify-content: center; align-items: center; gap: 12px;
            letter-spacing: 2px; text-transform: uppercase;
            box-shadow: 0 15px 30px rgba(18, 20, 19, 0.15);
        }
        .btn-confirm:hover { 
            background: transparent; color: var(--primary); box-shadow: none;
        }

        .btn-print {
            flex: 1; background: var(--surface); color: var(--text-main); border: 1px solid var(--border); 
            padding: 18px; border-radius: 12px; font-size: 0.95rem; font-weight: 400; 
            cursor: pointer; transition: var(--transition); 
            display: flex; justify-content: center; align-items: center; gap: 12px;
            letter-spacing: 2px; text-transform: uppercase;
        }
        .btn-print:hover {
            border-color: var(--primary); background: rgba(18, 20, 19, 0.03); color: var(--primary);
        }

        .footer-note {
            text-align: center; color: var(--text-muted); font-size: 0.85rem; 
            margin-top: 25px; font-weight: 300; line-height: 1.7;
        }
        
        /* Back link */
        .back-link {
            display: block; text-align: center;
            font-size: 0.85rem; color: var(--text-muted);
            margin-top: 30px; text-decoration: none;
            transition: var(--transition); letter-spacing: 1px; text-transform: uppercase;
        }
        .back-link:hover { color: var(--gold); }
        .back-link i { margin-right: 8px; }

        @media (max-width: 600px) {
            .content { padding: 30px 20px; }
            .method-card { flex-direction: column; align-items: flex-start; gap: 15px; }
            .btn-copy { width: 100%; text-align: center; }
            .header h1 { font-size: 1.8rem; }
            .action-buttons { flex-direction: column; }
        }

        /* Print Styles */
        @media print {
            body { background: white; padding: 0; }
            .checkout-wrapper { max-width: 100%; }
            .checkout-container { border: none; box-shadow: none; border-radius: 0; }
            .header { padding: 30px 0; background: white; color: black; border-bottom: 2px solid #000; }
            .header h1 { color: black; }
            .header::before { display: none; }
            .header-invoice-box { border: none; padding: 0; background: transparent; color: black; }
            .header-invoice-box strong { color: black; font-size: 1.5rem; }
            .btn-copy, .btn-copy-inv, .action-buttons, .back-link, .footer-note, .status-badge { display: none !important; }
            .order-summary { border: 1px solid #000; background: white; }
            .summary-row.total { border-top: 1px dashed #000; color: black; }
            .section-title, .method-details h4 { color: black; }
            .payment-methods { page-break-inside: avoid; }
            .method-card { border: 1px solid #ccc; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="checkout-wrapper">
        <div class="checkout-container" data-aos="fade-up" data-aos-duration="1200">
            <div class="header">
                <div class="brand-logo" data-aos="fade-down" data-aos-delay="100" style="margin-bottom: 25px; position: relative; z-index: 1;">
                    <img src="assets/logo.png" alt="Embun Visual Logo" style="height: 40px; filter: brightness(0) invert(1);" onerror="this.onerror=null; this.outerHTML='<div style=\'font-family: var(--font-serif); font-size: 1.5rem; font-weight: 600; color: #fff; letter-spacing: 2px;\'><i class=\'fas fa-leaf\' style=\'color: var(--gold);\'></i> Embun Visual</div>'">
                </div>
                <h1 data-aos="fade-down" data-aos-delay="200">Penyelesaian Reservasi</h1>
                <p data-aos="fade-up" data-aos-delay="300">Invoice Pembayaran</p>
                <div class="header-invoice-box" data-aos="zoom-in" data-aos-delay="400">
                    <strong id="nomorInvoice"><?php echo $invoice; ?></strong>
                    <button class="btn-copy-inv" onclick="copyText('<?php echo $invoice; ?>')" title="Salin Invoice">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div class="content">
                <div class="status-container" data-aos="zoom-in" data-aos-delay="600">
                    <div class="status-badge"><i class="fas fa-history"></i> <?php echo $data['status_pembayaran']; ?></div>
                </div>

                <div class="order-summary" data-aos="fade-up" data-aos-delay="700">
                    <div class="summary-row">
                        <span style="color: var(--text-muted);">Nama Mempelai</span>
                        <span style="font-weight: 500; color: var(--text-main);"><?php echo $data['nama_pemesan']; ?></span>
                    </div>
                    <div class="summary-row">
                        <span style="color: var(--text-muted);">Koleksi Tema</span>
                        <span style="font-weight: 500; color: var(--text-main);"><?php echo $data['nama_tema']; ?></span>
                    </div>
                    <div class="summary-row">
                        <span style="color: var(--text-muted);">Tanggal Pernikahan</span>
                        <span style="font-weight: 500; color: var(--text-main);"><?php echo date('d M Y', strtotime($data['tanggal_acara'])); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Nilai</span>
                        <span>Rp <?php echo number_format($data['total_tagihan'],0,',','.'); ?></span>
                    </div>
                </div>

                <div class="payment-methods" data-aos="fade-up" data-aos-delay="800">
                    <h3 class="section-title">Pilihan Trasfer Bank</h3>
                    
                    <div class="method-card">
                        <div class="method-info">
                            <div class="method-icon"><i class="fas fa-university"></i></div>
                            <div class="method-details">
                                <h4>Bank Central Asia (BCA)</h4>
                                <p id="rekBCA">1234567890</p>
                                <span>a.n PT Embun Visual Indonesia</span>
                            </div>
                        </div>
                        <button class="btn-copy" onclick="copyText('1234567890')">Salin</button>
                    </div>

                    <div class="method-card">
                        <div class="method-info">
                            <div class="method-icon"><i class="fas fa-qrcode"></i></div>
                            <div class="method-details">
                                <h4>Dompet Digital (Gopay/OVO/DANA)</h4>
                                <p id="rekEwallet">081234567890</p>
                                <span>a.n Embun Visual Official</span>
                            </div>
                        </div>
                        <button class="btn-copy" onclick="copyText('081234567890')">Salin</button>
                    </div>
                </div>

                <!-- Form konfirmasi -->  
                <form method="POST" data-aos="fade-up" data-aos-delay="900" style="margin: 0;">
                    <div class="action-buttons">
                        <button type="submit" name="konfirmasi_bayar" class="btn-confirm">
                            Konfirmasi Pembayaran <i class="fab fa-whatsapp"></i>
                        </button>
                        <a href="invoice.php?inv=<?php echo urlencode($invoice); ?>" target="_blank" class="btn-print" style="text-decoration: none;">
                            <i class="fas fa-print"></i> Cetak Invoice
                        </a>
                    </div>
                </form>
                
                <p class="footer-note" data-aos="fade-in" data-aos-delay="1000">
                    Setelah konfirmasi, Anda akan diarahkan ke WhatsApp Admin untuk lampiran bukti transfer.<br>
                    <span style="color:var(--gold);">✉️</span> Notifikasi email akan dikirim saat pembayaran diverifikasi &amp; saat undangan selesai.
                </p>
            </div>
        </div>
        
        <a href="index.php" class="back-link" data-aos="fade-in" data-aos-delay="1200" data-aos-offset="0">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS Animation setup
        AOS.init({
            once: true, 
            easing: 'ease-out-cubic'
        });

        function copyText(text) {
            navigator.clipboard.writeText(text);
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: 'Berhasil Disalin!', 
                showConfirmButton: false, timer: 2000,
                background: '#121413',
                color: '#fff',
                iconColor: '#C9A66B',
                customClass: { popup: 'border-radius-0' }
            });
        }
    </script>
</body>
</html>