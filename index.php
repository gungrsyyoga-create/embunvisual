<?php 
// Nyalakan pendeteksi error
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php'; 

// ==========================================
// 0. SISTEM MULTI-BAHASA (LOCALIZATION)
// ==========================================
if(isset($_GET['lang'])) {
    if($_GET['lang'] == 'en' || $_GET['lang'] == 'id') {
        $_SESSION['lang'] = $_GET['lang'];
    }
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

$i18n = [
    'id' => [
        'nav_katalog' => 'Koleksi Tema',
        'nav_custom' => 'Bespoke Design',
        'nav_contact' => 'Hubungi Kami',
        'hero_badge' => 'Digital Undangan Mewah',
        'hero_title' => 'Momen Berharga<br>dengan Keanggunan',
        'hero_desc' => 'Kami merancang undangan digital premium untuk berbagai momen berharga Anda, menceritakan kisah Anda dengan elegan, praktis, serta memberikan impresi mewah yang tak terlupakan.',
        'hero_btn' => 'Eksplorasi Koleksi',
        'cat_title' => 'Koleksi Premium',
        'cat_desc' => 'Temukan pilihan tema berkelas dari kami yang dirancang dengan detail sempurna, harmoni warna, dan tipografi estetis.',
        'cat_btn_demo' => 'Lihat Demo',
        'cat_btn_book' => 'Booking',
        'cat_empty' => 'Koleksi Belum Memiliki Tema',
        'cat_empty_desc' => 'Silakan login ke panel admin untuk menambahkan tema baru.',
        'gal_title' => 'Gallery PhotoShoot',
        'gal_desc' => 'Kumpulan memori indah yang diabadikan dengan sempurna melalui karya Partner Teduh Visual.',
        'gal_source' => 'Sumber Foto:',
        'gal_empty' => 'Koleksi foto sedang kami siapkan untuk Anda.',
        'cus_title' => 'Bespoke Service',
        'cus_desc' => 'Ingin konsep yang lebih personal? Tim desain kami siap berkolaborasi untuk merancang dari nol undangan digital eksklusif yang merefleksikan karakter dan tema impian acara spesialmu.',
        'cus_ph_name' => 'Nama Pasangan / Penyelenggara',
        'cus_ph_wa' => 'Nomor WhatsApp (Aktif)',
        'cus_ph_budget' => 'Estimasi Budget Eksklusif',
        'cus_opt_more' => 'Lebih dari Rp 1.000.000',
        'cus_ph_concept' => 'Ceritakan detail konsep yang diinginkan (warna, nuansa, mood)...',
        'cus_btn' => 'Kirim Permintaan',
        'faq_title' => 'Pertanyaan & Ketentuan',
        'faq_desc' => 'Informasi yang sering ditanyakan seputar layanan undangan digital Embun Visual serta syarat dan ketentuan layanan kami.',
        'faq_q1' => '1. Berapa lama proses pengerjaan undangan digital?',
        'faq_a1' => 'Untuk tema yang sudah tersedia di katalog (Koleksi Tema), proses pengerjaan memakan waktu <strong>1-3 hari kerja</strong>... Sedangkan untuk layanan <em>Bespoke Design</em> (Custom), waktu pengerjaan berkisar antara <strong>7-14 hari kerja</strong>.',
        'faq_q2' => '2. Apakah saya bisa merevisi data jika ada kesalahan?',
        'faq_a2' => 'Ya, tentu bisa. Kami memberikan batasan <strong>revisi minor sebanyak 3 kali</strong> sebelum hari H acara.',
        'faq_q3' => '3. Berapa lama masa aktif link undangan web?',
        'faq_a3' => 'Undangan web Anda akan tetap aktif dan dapat diakses publik hingga <strong>6 bulan setelah tanggal acara (Hari H)</strong> berlalu.',
        'faq_q4' => '4. Syarat dan Ketentuan Layanan (Terms & Conditions)',
        'faq_a4' => 'Dengan menggunakan layanan Embun Visual, Anda menyetujui pelunasan 100% sebelum pengerjaan final, menjamin foto bebas hak cipta, dan setuju untuk dimasukkan ke portfolio kecuali via NDA.',
        'footer_desc' => 'Membingkai cerita Anda menjadi mahakarya digital abadi, dirancang dengan presisi dan sentuhan keanggunan.',
        'footer_copy' => '&copy; 2024 Embun Visual. Seluruh Hak Cipta Dilindungi.',
        'mod_title' => 'Reservasi',
        'mod_sub' => 'Tema Terpilih:',
        'mod_ph_name' => 'Nama Pasangan / Penyelenggara',
        'mod_ph_wa' => 'Nomor WhatsApp',
        'mod_agree' => 'Saya telah membaca & menyetujui seluruh <a href="#" onclick="showTermsPopup(event)" style="color: var(--gold); text-decoration: underline;">Syarat & Ketentuan Layanan</a> Embun Visual.',
        'mod_btn' => 'Lanjut ke Eksekusi'
    ],
    'en' => [
        'nav_katalog' => 'Theme Collection',
        'nav_custom' => 'Bespoke Design',
        'nav_contact' => 'Contact Us',
        'hero_badge' => 'Luxury Digital Invitations',
        'hero_title' => 'Precious Moments<br>with Elegance',
        'hero_desc' => 'We design premium digital invitations for your special events, narrating your story with elegance and practicality, leaving an unforgettable luxurious impression.',
        'hero_btn' => 'Explore Collection',
        'cat_title' => 'Premium Collection',
        'cat_desc' => 'Discover our classy theme options designed with perfect detailing, color harmony, and aesthetic typography.',
        'cat_btn_demo' => 'View Demo',
        'cat_btn_book' => 'Book Now',
        'cat_empty' => 'No Themes Available Yet',
        'cat_empty_desc' => 'Please log in to the admin panel to add a new theme.',
        'gal_title' => 'PhotoShoot Gallery',
        'gal_desc' => 'A collection of beautiful memories perfectly captured by our partner Teduh Visual.',
        'gal_source' => 'Photo Source:',
        'gal_empty' => 'We are preparing the photo collection for you.',
        'cus_title' => 'Bespoke Service',
        'cus_desc' => 'Looking for a more personal concept? Our design team is ready to collaborate to design an exclusive digital invitation from scratch that reflects your character and dream event theme.',
        'cus_ph_name' => 'Couple\'s / Host\'s Name',
        'cus_ph_wa' => 'Active WhatsApp Number',
        'cus_ph_budget' => 'Exclusive Budget Estimation',
        'cus_opt_more' => 'More than Rp 1.000.000',
        'cus_ph_concept' => 'Tell us the details of your desired concept (colors, vibes, mood)...',
        'cus_btn' => 'Send Request',
        'faq_title' => 'FAQ & Terms',
        'faq_desc' => 'Frequently asked questions regarding Embun Visual digital invitation services and our terms and conditions.',
        'faq_q1' => '1. How long does the digital invitation creation process take?',
        'faq_a1' => 'For themes already available in the catalog, processing takes <strong>1-3 working days</strong>... For <em>Bespoke Design</em> (Custom), processing takes about <strong>7-14 working days</strong>.',
        'faq_q2' => '2. Can I revise data if there\'s a mistake?',
        'faq_a2' => 'Yes, absolutely. We provide a limit of <strong>3 minor revisions</strong> before the event day.',
        'faq_q3' => '3. How long is the web invitation link active?',
        'faq_a3' => 'Your web invitation will remain active and publicly accessible for up to <strong>6 months after the event date (D-Day)</strong>.',
        'faq_q4' => '4. Terms and Conditions of Service',
        'faq_a4' => 'By using Embun Visual services, you agree to 100% completion payment before final delivery, guarantee copyright-free photos, and agree to be included in our portfolio unless agreed otherwise via NDA.',
        'footer_desc' => 'Framing your story into a timeless digital masterpiece, designed with precision and a touch of elegance.',
        'footer_copy' => '&copy; 2024 Embun Visual. All Rights Reserved.',
        'mod_title' => 'Reservation',
        'mod_sub' => 'Selected Theme:',
        'mod_ph_name' => 'Couple\'s / Host\'s Name',
        'mod_ph_wa' => 'WhatsApp Number',
        'mod_agree' => 'I have read & agree to all Embun Visual <a href="#" onclick="showTermsPopup(event)" style="color: var(--gold); text-decoration: underline;">Terms & Conditions of Service</a>.',
        'mod_btn' => 'Proceed to Execution'
    ]
];
$txt = $i18n[$lang];


// ==========================================
// 1. PROSES FORM BOOKING (Diarahkan ke Checkout)
// ==========================================
if(isset($_POST['submit_booking'])){
    $invoice = "INV-" . date('Ymd') . "-" . rand(100,999);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $wa = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $tgl_acara = $_POST['tanggal_acara'];
    
    // Ambil data tema untuk harga
    $tema_id = $_POST['tema_id'];
    $cek_tema = mysqli_query($conn, "SELECT * FROM katalog_tema WHERE id='$tema_id'");
    if($cek_tema && mysqli_num_rows($cek_tema) > 0) {
        $data_tema = mysqli_fetch_assoc($cek_tema);
        $harga = $data_tema['harga'];

        // Simpan ke database
        $query_booking = "INSERT INTO pesanan (invoice, nama_pemesan, no_whatsapp, tema_id, tanggal_acara, total_tagihan) 
                          VALUES ('$invoice', '$nama', '$wa', '$tema_id', '$tgl_acara', '$harga')";
        
        if(mysqli_query($conn, $query_booking)){
            // Arahkan otomatis ke halaman checkout membawa nomor Invoice
            header("Location: checkout.php?inv=$invoice");
            exit;
        }
    }
}

// ==========================================
// 2. PROSES FORM REQUEST CUSTOM (Tetap ke WA)
// ==========================================
if(isset($_POST['submit_custom'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_custom']);
    $wa = mysqli_real_escape_string($conn, $_POST['wa_custom']);
    $budget = mysqli_real_escape_string($conn, $_POST['budget']);
    $konsep = mysqli_real_escape_string($conn, $_POST['konsep']);

    mysqli_query($conn, "INSERT INTO request_custom (nama_klien, no_whatsapp, budget_estimasi, deskripsi_konsep) VALUES ('$nama', '$wa', '$budget', '$konsep')");
    
    $nomor_admin = "6281234567890"; // GANTI DENGAN NOMOR WA KAMU
    $pesan_wa = "Halo *Embun Visual*!%0A%0ASaya ingin diskusi pembuatan *Tema Custom* khusus untuk acara saya.%0A*Nama:* $nama%0A*Estimasi Budget:* $budget%0A*Konsep:* $konsep%0A%0AApakah bisa dibantu?";
    
    echo "<script>window.open('https://api.whatsapp.com/send?phone=$nomor_admin&text=$pesan_wa', '_blank'); window.location.href='index.php?status=custom_success';</script>";
    exit;
}

// Ambil data katalog
$query_katalog = mysqli_query($conn, "SELECT * FROM katalog_tema ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embun Visual | Premium Digital Invitation</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #1A1614;       /* Elegant Dark Charcoal */
            --primary-light: #2A2420; 
            --bg-body: #FAF8F5;       /* Warm pearl white */
            --bg-texture: radial-gradient(circle at 15% 50%, rgba(212, 175, 55, 0.05), transparent 40%),
                          radial-gradient(circle at 85% 30%, rgba(212, 175, 55, 0.04), transparent 40%);
            --surface: #FFFFFF;
            --text-main: #2A2522; 
            --text-muted: #6B6560; 
            --gold: #D4AF37;          /* Classic Prada Gold */
            --gold-hover: #C59B27;
            --border: #EAE3D9;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
            --shadow-soft: 0 15px 40px rgba(0,0,0,0.06);
            --shadow-gold: 0 15px 35px rgba(212, 175, 55, 0.25);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        ::selection { background: var(--gold); color: #fff; }
        body { 
            font-family: var(--font-sans); 
            background-color: var(--bg-body); 
            background-image: var(--bg-texture), url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.04'/%3E%3C/svg%3E"); 
            background-attachment: fixed;
            background-size: 100% 100%, 150px 150px;
            color: var(--text-main); 
            scroll-behavior: smooth; 
            overflow-x: hidden; 
            -webkit-font-smoothing: antialiased;
        }
        .serif { font-family: var(--font-serif); }
        a { text-decoration: none; color: inherit; }
        
        /* Typography adjustments */
        h1, h2, h3, h4, h5, h6 { font-weight: 500; line-height: 1.2; }
        p { line-height: 1.8; }

        /* Navbar with Glassmorphism */
        .navbar { 
            position: fixed; width: 100%; top: 0; padding: 30px 6%; 
            display: flex; justify-content: space-between; align-items: center; 
            z-index: 1000; transition: var(--transition); 
            background: transparent; border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .navbar.scrolled { 
            background: rgba(255, 255, 255, 0.92); 
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            padding: 20px 6%; 
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 30px rgba(0,0,0,0.04);
        }
        /* Logo Styling */
        .ev-logo { 
            display: flex; align-items: center; gap: 10px;
            font-size: 1.4rem; font-weight: 700; color: #fff; 
            letter-spacing: 2px; transition: var(--transition); 
            text-shadow: 0 2px 10px rgba(0,0,0,0.3); font-family: 'Plus Jakarta Sans', var(--font-sans);
        }
        .ev-logo i { color: var(--gold); font-size: 1.3rem; }
        
        .navbar.scrolled .ev-logo { color: var(--primary); text-shadow: none; }
        .ev-logo:hover { transform: scale(1.02); }
        
        .nav-links { display: flex; align-items: center; gap: 40px; }
        .nav-links a { 
            color: #fff; font-weight: 400; font-size: 0.8rem; 
            text-transform: uppercase; letter-spacing: 2px; transition: var(--transition); 
            position: relative; text-shadow: 0 1px 5px rgba(0,0,0,0.5);
        }
        .navbar.scrolled .nav-links a { color: var(--text-main); text-shadow: none; font-weight: 500; }
        .nav-links a::after {
            content: ''; position: absolute; width: 0; height: 1px; 
            bottom: -5px; left: 0; background-color: var(--gold); 
            transition: var(--transition);
        }
        .nav-links a:hover::after { width: 100%; }
        .navbar.scrolled .nav-links a:hover { color: var(--gold); }
        
        /* Language Toggle Styling */
        .lang-toggle { display:flex; gap: 8px; align-items:center; margin-left: 10px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 20px; transition: var(--transition); }
        .navbar.scrolled .lang-toggle { border-left-color: var(--border); }
        .navbar.scrolled .lang-toggle a.active { color: var(--gold) !important; font-weight: 600; }
        .navbar.scrolled .lang-toggle a.inactive { color: var(--text-main) !important; font-weight: 400; }
        .navbar.scrolled .lang-toggle span { color: var(--border) !important; text-shadow: none !important; }

        .btn-nav { 
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.4); color: #fff !important; 
            padding: 12px 30px; font-size: 0.8rem; letter-spacing: 1px; backdrop-filter: blur(5px);
            border-radius: 4px; transition: var(--transition); text-shadow: none !important;
        }
        .btn-nav:hover { background: var(--gold); border-color: var(--gold); color: var(--primary) !important; }
        .btn-nav::after { display: none; }
        .navbar.scrolled .btn-nav { 
            border-color: var(--primary); color: var(--primary) !important; background: transparent;
        }
        .navbar.scrolled .btn-nav:hover { 
            background: var(--primary); color: #fff !important; 
        }

        /* Hero: Enhanced with deeper gradient and subtle overlay */
        .hero { 
            height: 100vh; min-height: 750px;
            display: flex; align-items: center; justify-content: center; text-align: center; 
            background: linear-gradient(to bottom, rgba(20, 18, 16, 0.4), rgba(20, 18, 16, 0.85)), 
                        url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1920&q=80') center/cover fixed; 
            padding: 0 20px; position: relative; 
        }
        .hero::before {
            content:''; position: absolute; inset: 20px; border: 1px solid rgba(212, 175, 55, 0.4); pointer-events: none; z-index: 1; border-radius: 4px;
        }
        .hero-content { position: relative; z-index: 2; max-width: 900px; padding-top: 60px; }
        
        .badge-hero { 
            font-size: 0.8rem; letter-spacing: 5px; text-transform: uppercase; 
            color: var(--gold); margin-bottom: 30px; display: inline-block; 
            position: relative; font-weight: 500;
        }
        .badge-hero::before, .badge-hero::after {
            content: ''; position: absolute; top: 50%; width: 40px; height: 1px; background: var(--gold); opacity: 0.6;
        }
        .badge-hero::before { left: -55px; }
        .badge-hero::after { right: -55px; }

        .hero h1 { 
            font-size: 5.5rem; color: #fff; line-height: 1.1; 
            margin-bottom: 30px; font-weight: 400; font-style: italic; text-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }
        .hero p { 
            font-size: 1.15rem; color: rgba(255,255,255,0.85); max-width: 650px; 
            margin: 0 auto 50px; font-weight: 300; line-height: 1.9; letter-spacing: 0.5px;
        }
        .btn-hero { 
            background: var(--gold); color: var(--primary); padding: 20px 50px; 
            font-weight: 600; display: inline-block; transition: var(--transition); 
            font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase;
            border: 1px solid var(--gold); box-shadow: var(--shadow-gold); border-radius: 4px;
        }
        .btn-hero:hover { 
            background: transparent; color: var(--gold); transform: translateY(-5px); 
        }

        /* Global Section */
        .section { padding: 140px 6%; max-width: 1400px; margin: 0 auto; position: relative; }
        .sec-header { text-align: center; margin-bottom: 80px; position: relative; }
        .sec-title { 
            font-size: 3.8rem; color: var(--primary); margin-bottom: 15px; 
            font-weight: 500; font-style: italic; position: relative;
        }
        .sec-title::before {
            content: ''; display: block; width: 100px; height: 35px; 
            background: url("data:image/svg+xml,%3Csvg width='100' height='35' viewBox='0 0 100 35' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M50,0 C35,15 25,35 0,35 C25,35 35,15 50,0 M50,0 C65,15 75,35 100,35 C75,35 65,15 50,0 Z' fill='%23D4AF37' opacity='0.9'/%3E%3Ccircle cx='50' cy='25' r='4' fill='%23D4AF37'/%3E%3C/svg%3E") no-repeat center;
            margin: 0 auto 20px;
        }
        .sec-title::after {
            content: ''; display: block; width: 60px; height: 2px; background: var(--gold); margin: 25px auto 0;
        }
        .sec-desc { 
            color: var(--text-muted); max-width: 600px; margin: 0 auto; 
            font-size: 1.05rem; line-height: 1.9; font-weight: 400; padding-top: 15px;
        }

        /* Grid Katalog - Modern Glass & Balinese Accent */
        .grid { 
            display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); 
            gap: 40px; 
        }
        .card { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            transition: var(--transition); 
            display: flex; flex-direction: column; 
            position: relative; border-radius: 20px; overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.06);
        }
        .card::before {
            content:''; position: absolute; inset:0; border: 1px solid transparent; z-index: 10; pointer-events: none; transition: var(--transition); border-radius: 20px;
        }
        .card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 30px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath fill='%23D4AF37' opacity='0.8' d='M0,0 h100 v8 c-10,10 -30,5 -50,-5 c-20,10 -40,15 -50,5 z'/%3E%3C/svg%3E") no-repeat top center;
            background-size: cover; z-index: 10; pointer-events: none;
            opacity: 0; transition: opacity 0.5s;
        }
        .card:hover { 
            transform: translateY(-12px); 
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 40px 80px rgba(0,0,0,0.1); 
            border-color: rgba(212, 175, 55, 0.8);
        }
        .card:hover::before { border-color: rgba(212, 175, 55, 0.6); inset: 8px; border-radius: 12px; }
        .card:hover::after { opacity: 1; }
        .c-img-wrapper { 
            height: 400px; overflow: hidden; position: relative; 
            border-top-left-radius: 20px; border-top-right-radius: 20px;
        }
        .c-img-wrapper::after {
            content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 40%); pointer-events: none; z-index: 1;
        }
        .c-img { 
            width: 100%; height: 100%; background-size: cover; 
            background-position: center; transition: transform 1.5s cubic-bezier(0.25, 1, 0.5, 1); 
        }
        .card:hover .c-img { transform: scale(1.1); }
        .c-badge { 
            position: absolute; top: 20px; left: 20px; 
            background: rgba(255,255,255,0.95); backdrop-filter: blur(5px); padding: 8px 18px; border-radius: 30px;
            font-size: 0.75rem; font-weight: 700; color: var(--primary); 
            letter-spacing: 2px; text-transform: uppercase; z-index: 2; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .c-body { 
            padding: 40px 30px; display: flex; flex-direction: column; 
            flex: 1; text-align: center; background: transparent; position: relative; z-index: 3;
        }
        .c-body h3 { 
            font-size: 2.2rem; margin-bottom: 8px; color: var(--primary); 
            font-weight: 500; font-style: italic;
        }
        .c-price { 
            color: var(--gold); font-weight: 600; font-size: 1.25rem; 
            margin-bottom: 25px; letter-spacing: 1px; font-family: var(--font-sans);
        }
        .c-desc { 
            color: var(--text-muted); font-size: 1rem; line-height: 1.8; 
            margin-bottom: 40px; flex: 1; font-weight: 400;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .c-actions { 
            display: flex; gap: 15px; margin-top: auto; 
        }
        .btn-outline, .btn-solid {
            flex: 1; padding: 16px 5px; text-align: center; font-size: 0.85rem; font-weight: 600;
            letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); border-radius: 30px;
        }
        .btn-outline { 
            border: 1px solid rgba(212, 175, 55, 0.5); color: var(--primary); background: transparent;
        }
        .btn-outline:hover { 
            border-color: var(--gold); background: var(--gold); color: #fff; box-shadow: var(--shadow-gold);
        }
        .btn-solid { 
            background: var(--gold); color: #fff; border: 1px solid var(--gold); cursor: pointer;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }
        .btn-solid:hover { 
            background: var(--primary); border-color: var(--primary); box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        /* Custom Area - Enhanced with deep blur and overlap */
        .custom-section { 
            background: var(--primary); padding: 140px 6%; position: relative;
        }
        .custom-section::before {
            content: ''; position: absolute; inset:0; background: url('https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1920&q=80') center/cover fixed; opacity: 0.15; pointer-events: none;
        }
        .custom-area { 
            display: flex; min-height: 650px; max-width: 1200px; margin: 0 auto;
            background: rgba(26, 22, 20, 0.75); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
            border-radius: 20px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.6); 
            overflow: hidden; border: 1px solid rgba(212, 175, 55, 0.2); position: relative; z-index: 2;
        }
        .custom-img {
            flex: 1; background: url('https://images.unsplash.com/photo-1604999333679-b86d54738315?auto=format&fit=crop&w=1000&q=80') center/cover;
            position: relative;
        }
        .custom-img::after {
            content: ''; position: absolute; inset:0; background: linear-gradient(to right, transparent 0%, rgba(26, 22, 20, 0.75) 100%);
        }
        .custom-form-wrapper { 
            flex: 1.2; padding: 70px 8%; 
            display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 3;
        }
        .custom-form-wrapper h2 { 
            font-size: 3.5rem; margin-bottom: 20px; color: var(--gold); font-style: italic; font-weight: 500; line-height: 1.1;
        }
        .custom-form-wrapper p { 
            color: rgba(255,255,255,0.7); font-weight: 300; margin-bottom: 40px; line-height: 1.8; font-size: 1.05rem;
        }
        
        .custom-form input, .custom-form select, .custom-form textarea { 
            width: 100%; padding: 18px 20px; margin-bottom: 20px; 
            border: 1px solid rgba(255,255,255,0.15); border-radius: 6px;
            font-family: inherit; font-size: 0.95rem; outline: none; 
            transition: var(--transition); background: rgba(0,0,0,0.3); 
            color: #fff; font-weight: 300;
        }
        .custom-form select option { color: var(--primary); }
        .custom-form input::placeholder, .custom-form textarea::placeholder, .custom-form select { 
            color: rgba(255,255,255,0.5); 
        }
        .custom-form input:focus, .custom-form select:focus, .custom-form textarea:focus { 
            border-color: var(--gold); background: rgba(0,0,0,0.6); box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
        }
        .btn-custom { 
            padding: 20px 40px; font-size: 0.9rem; background: var(--gold); border-radius: 6px;
            color: var(--primary); border: 1px solid var(--gold); cursor: pointer; box-shadow: var(--shadow-gold);
            letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); font-weight: 600;
            margin-top: 10px; display: inline-block; width: 100%; text-align: center;
        }
        .btn-custom:hover { 
            background: transparent; color: var(--gold); box-shadow: none;
        }

        /* FAQ Accordion - Elevated design */
        .faq-section { background: transparent; position: relative; }
        .faq-container { max-width: 850px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }
        .accordion-item {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px; 
            overflow: hidden;
            transition: var(--transition); box-shadow: var(--shadow-soft);
        }
        .accordion-item.active { border-color: var(--gold); background: #fff; box-shadow: 0 15px 35px rgba(212,175,55,0.15); }
        .accordion-header {
            width: 100%; text-align: left; padding: 25px 35px;
            background: transparent; border: none; cursor: pointer;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 1.1rem; color: var(--text-main); font-weight: 500; font-family: 'Inter', sans-serif;
        }
        .accordion-header i { color: var(--gold); transition: transform 0.4s ease; }
        .accordion-item.active .accordion-header i { transform: rotate(180deg); }
        .accordion-content {
            max-height: 0; overflow: hidden; transition: max-height 0.4s ease-out;
            background: var(--surface);
        }
        .accordion-item.active .accordion-content { max-height: 800px; }
        .accordion-body {
            padding: 0 35px 35px 35px; color: var(--text-muted); line-height: 1.9; font-size: 1rem; font-weight: 300; border-top: 1px solid transparent;
        }
        .accordion-item.active .accordion-body { border-top-color: rgba(212,175,55,0.1); padding-top: 25px; }

        /* Momen Ekstetik (Gallery) */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 50px;
        }
        .gallery-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 4/5;
            cursor: pointer; box-shadow: var(--shadow-soft);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; filter: grayscale(15%);
            transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1), filter 0.8s;
        }
        .gallery-item:hover img {
            transform: scale(1.08); filter: grayscale(0%);
        }
        .gallery-caption {
            position: absolute;
            bottom: 0; left: 0; width: 100%;
            padding: 30px 20px 20px;
            background: linear-gradient(to top, rgba(15,18,16,0.9), transparent);
            color: #fff;
            transform: translateY(100%);
            transition: transform 0.4s ease;
            font-size: 0.95rem; line-height: 1.5;
            font-weight: 300;
        }
        .gallery-item:hover .gallery-caption {
            transform: translateY(0);
        }

        /* Footer */
        footer { 
            text-align: center; padding: 120px 20px 60px; 
            background: var(--primary); position: relative; color: #fff;
            border-top: 5px solid var(--gold);
        }
        .f-brand { 
            margin-bottom: 25px; display: flex; align-items: center; justify-content: center; gap: 10px;
            font-size: 2.2rem; font-weight: 700; color: #fff; 
            letter-spacing: 2px; font-family: 'Plus Jakarta Sans', var(--font-sans);
        }
        .f-brand i { color: var(--gold); }
        .f-text { 
            color: rgba(255,255,255,0.6); font-size: 1.05rem; margin-bottom: 50px; font-weight: 300; 
            max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.9;
        }
        .social-links {
            display: flex; justify-content: center; gap: 20px; margin-bottom: 60px;
        }
        .social-links a {
            color: var(--primary); font-size: 1.2rem; transition: var(--transition);
            width: 50px; height: 50px; background: rgba(255,255,255,0.9);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }
        .social-links a:hover {
            color: #fff; background: var(--gold); transform: translateY(-3px); box-shadow: var(--shadow-gold);
        }
        .copyright {
            font-size: 0.8rem; color: rgba(255,255,255,0.3); letter-spacing: 2px; text-transform: uppercase;
        }

        /* Modal Booking (Luxury vibe) */
        .modal { 
            display: none; position: fixed; inset: 0; 
            background: rgba(26, 22, 20, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            z-index: 2000; align-items: center; justify-content: center; 
            opacity: 0; transition: opacity 0.4s ease; 
        }
        .modal.show { display: flex; opacity: 1; }
        .modal-content { 
            background: var(--bg-body); padding: 70px 60px; width: 100%; max-width: 550px; 
            position: relative; transform: translateY(30px) scale(0.98); 
            transition: var(--transition); border: 1px solid var(--gold); border-radius: 20px; box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            background-image: var(--bg-texture); background-attachment: fixed;
        }
        .modal.show .modal-content { transform: translateY(0) scale(1); }
        .close-modal { 
            position: absolute; top: 20px; right: 25px; font-size: 2.5rem; 
            cursor: pointer; color: var(--text-muted); transition: var(--transition); 
            width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; 
            font-weight: 300; line-height: 1;
        }
        .close-modal:hover { color: var(--primary); transform: rotate(90deg); }
        .modal-title {
            font-size: 2.8rem; color: var(--primary); margin-bottom: 10px; font-style: italic; text-align: center;
        }
        .modal-subtitle {
            color: var(--text-muted); margin-bottom: 40px; font-size: 1rem; text-align: center; font-weight: 300; line-height: 1.5;
        }
        .modal-subtitle strong { color: var(--gold); font-weight: 500; font-family: var(--font-serif); font-style: italic; font-size: 1.2rem; }
        
        .form-group { margin-bottom: 25px; }
        .form-control { 
            width: 100%; padding: 18px 0; border: none; border-bottom: 1px solid var(--border); 
            font-family: inherit; font-size: 1rem; transition: var(--transition); 
            background: transparent; color: var(--text-main); font-weight: 400;
        }
        .form-control:focus { 
            border-bottom-color: var(--gold); outline: none;
        }
        .btn-modal { 
            width: 100%; padding: 22px; margin-top: 30px; font-size: 0.9rem; font-weight: 600;
            background: var(--primary); color: white; border: 1px solid var(--primary); 
            cursor: pointer; letter-spacing: 3px; text-transform: uppercase; 
            transition: var(--transition); display: flex; justify-content: center; align-items: center; gap: 10px; border-radius: 6px;
        }
        .btn-modal:hover { 
            background: var(--gold); color: #fff; border-color: var(--gold); box-shadow: var(--shadow-gold);
        }

        @media (max-width: 900px) {
            .custom-area { flex-direction: column; }
            .custom-img { min-height: 400px; display: none; } /* Hide heavy image on tablets/mobile to focus on form */
            .hero h1 { font-size: 4rem; }
            .sec-title { font-size: 3rem; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .navbar { padding: 15px 5%; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
            .ev-logo { font-size: 1.2rem; }
            .ev-logo i { font-size: 1.1rem; }
            .nav-links { display: none; }
            
            .hero { padding: 0 15px; min-height: 100vh; }
            .hero-content { padding-top: 50px; }
            .hero h1 { font-size: 2.8rem; margin-bottom: 20px; }
            .hero p { font-size: 0.95rem; font-weight: 300; padding: 0; line-height: 1.6; margin-bottom: 35px; }
            .badge-hero { font-size: 0.7rem; margin-bottom: 20px; }
            .badge-hero::before, .badge-hero::after { width: 30px; }
            .badge-hero::before { left: -40px; }
            .badge-hero::after { right: -40px; }
            .btn-hero { padding: 16px 35px; font-size: 0.8rem; }

            .section { padding: 80px 5%; }
            .sec-header { margin-bottom: 50px; }
            .sec-title { font-size: 2.5rem; }
            .sec-desc { font-size: 0.95rem; line-height: 1.6; }

            .grid { grid-template-columns: 1fr; gap: 30px; }
            .c-img-wrapper { height: 320px; }
            .c-body { padding: 30px 20px; }
            .c-body h3 { font-size: 1.8rem; }
            .c-price { font-size: 1.1rem; }
            .c-desc { font-size: 0.9rem; }
            .c-actions { flex-direction: column; gap: 10px; }
            .btn-outline, .btn-solid { padding: 14px 5px; }

            .gallery-grid { grid-template-columns: 1fr; gap: 20px; margin-top: 30px; }
            .gallery-item { aspect-ratio: 1/1; border-radius: 12px; }

            .custom-section { padding: 80px 5%; }
            .custom-area { min-height: auto; border-radius: 12px; }
            .custom-form-wrapper { padding: 40px 6%; }
            .custom-form-wrapper h2 { font-size: 2.4rem; }
            .custom-form-wrapper p { font-size: 0.95rem; margin-bottom: 25px; }
            .custom-form input, .custom-form select, .custom-form textarea { padding: 15px; font-size: 0.9rem; margin-bottom: 15px; }
            .btn-custom { padding: 16px; font-size: 0.85rem; }

            .faq-container { gap: 15px; }
            .accordion-header { padding: 20px; font-size: 1rem; }
            .accordion-body { padding: 0 20px 20px 20px; font-size: 0.9rem; }

            footer { padding: 80px 20px 40px; border-top: 3px solid var(--gold); }
            .f-brand { font-size: 1.8rem; margin-bottom: 20px; }
            .f-text { font-size: 0.95rem; margin-bottom: 30px; }
            .social-links { gap: 15px; margin-bottom: 40px; }

            .modal-content { padding: 40px 20px; max-width: 90%; }
            .modal-title { font-size: 2rem; }
            .modal-subtitle { font-size: 0.9rem; margin-bottom: 25px; }
            .modal-subtitle strong { font-size: 1.1rem; }
            .close-modal { top: 10px; right: 15px; font-size: 2rem; }
            .form-control { font-size: 0.95rem; padding: 15px 0; }
            .btn-modal { padding: 18px; font-size: 0.85rem; margin-top: 20px; }
            .form-group label { font-size: 0.8rem !important; }
        }
    </style>
</head>
<body>

    <nav class="navbar" id="navbar">
        <a href="#" class="ev-logo">
            <i class="fas fa-leaf"></i> Embun Visual
        </a>
        <div class="nav-links">
            <a href="#katalog"><?= $txt['nav_katalog'] ?></a>
            <a href="#custom"><?= $txt['nav_custom'] ?></a>
            <a href="#faq">FAQ</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-nav"><?= $txt['nav_contact'] ?></a>
            <div class="lang-toggle">
                <a href="?lang=id" class="<?= $lang == 'id' ? 'active' : 'inactive' ?>" style="color: <?= $lang == 'id' ? 'var(--gold)' : '#fff' ?>;">ID</a>
                <span style="color: rgba(255,255,255,0.4); font-size:0.8rem;">|</span>
                <a href="?lang=en" class="<?= $lang == 'en' ? 'active' : 'inactive' ?>" style="color: <?= $lang == 'en' ? 'var(--gold)' : '#fff' ?>;">EN</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1500">
            <span class="badge-hero"><?= $txt['hero_badge'] ?></span>
            <h1 class="serif"><?= $txt['hero_title'] ?></h1>
            <p><?= $txt['hero_desc'] ?></p>
            <a href="#katalog" class="btn-hero"><?= $txt['hero_btn'] ?></a>
        </div>
    </section>

    <section class="section" id="katalog">
        <div class="sec-header" data-aos="fade-up">
            <h2 class="sec-title serif"><?= $txt['cat_title'] ?></h2>
            <p class="sec-desc"><?= $txt['cat_desc'] ?></p>
        </div>
        
        <div class="grid">
            <?php 
            if($query_katalog && mysqli_num_rows($query_katalog) > 0) {
                $delay = 100;
                while($tema = mysqli_fetch_assoc($query_katalog)) { 
            ?>
            <div class="card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                <div class="c-img-wrapper">
                    <div class="c-img" style="background-image: url('<?php echo $tema['gambar_url']; ?>');"></div>
                    <span class="c-badge"><?php echo $tema['kategori']; ?></span>
                </div>
                <div class="c-body">
                    <h3 class="serif"><?php echo $tema['nama_tema']; ?></h3>
                    <div class="c-price">Rp <?php echo number_format($tema['harga'],0,',','.'); ?></div>
                    <p class="c-desc"><?php echo $tema['deskripsi']; ?></p>
                    
                    <div class="c-actions">
                        <a href="<?php echo htmlspecialchars(trim($tema['slug_demo'])); ?>" target="_blank" class="btn-outline"><?= $txt['cat_btn_demo'] ?></a>
                        <button onclick="bukaModal('<?php echo $tema['id']; ?>', '<?php echo htmlspecialchars($tema['nama_tema'], ENT_QUOTES); ?>', '<?php echo $tema['harga']; ?>')" class="btn-solid"><?= $txt['cat_btn_book'] ?></button>
                    </div>
                </div>
            </div>
            <?php 
                $delay += 100; 
                } 
            } else {
                echo "<div style='grid-column: 1/-1; text-align:center; padding:80px 20px; background:#fff; border:1px solid #E5E2DC; border-radius:8px;' data-aos='fade-up'><h3 class='serif' style='font-size:2rem; color:var(--primary); margin-bottom:10px;'>{$txt['cat_empty']}</h3><p style='color:#737373; font-weight:300;'>{$txt['cat_empty_desc']}</p></div>";
            }
            ?>
        </div>
    </section>

    <!-- Galeri Momen Dinamis -->
    <section id="galeri" class="section">
        <div class="sec-header" data-aos="fade-up">
            <h2 class="sec-title serif"><?= $txt['gal_title'] ?></h2>
            <p class="sec-desc"><?= $txt['gal_desc'] ?></p>
        </div>
        
        <div class="gallery-grid" data-aos="fade-up" data-aos-delay="100">
            <?php 
            $query_galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC LIMIT 8");
            if($query_galeri && mysqli_num_rows($query_galeri) > 0) {
                while($g = mysqli_fetch_assoc($query_galeri)) { 
            ?>
            <div class="gallery-item">
                <img src="<?php echo htmlspecialchars($g['gambar']); ?>" alt="Embun Visual Gallery">
                <?php if(!empty($g['caption']) || !empty($g['sumber_nama'])) { ?>
                <div class="gallery-caption">
                    <?php if(!empty($g['caption'])) { echo "<div style='margin-bottom:8px; font-weight:500; font-size:1.1rem; color:var(--gold);'>".htmlspecialchars($g['caption'])."</div>"; } ?>
                    <?php if(!empty($g['sumber_nama'])) { ?>
                        <div style="font-size:0.8rem; color:rgba(255,255,255,0.7); letter-spacing:0.5px;">
                            <?= $txt['gal_source'] ?> 
                            <?php if(!empty($g['sumber_link'])) { ?>
                                <a href="<?php echo htmlspecialchars($g['sumber_link']); ?>" target="_blank" style="color:#fff; text-decoration:none; font-weight:500; transition:0.3s;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='#fff'"><?php echo htmlspecialchars($g['sumber_nama']); ?></a>
                            <?php } else { ?>
                                <span style="color:#fff; font-weight:500;"><?php echo htmlspecialchars($g['sumber_nama']); ?></span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <?php 
                } 
            } else {
                echo "<p style='grid-column: 1/-1; text-align:center; color: var(--text-muted);'>{$txt['gal_empty']}</p>";
            }
            ?>
        </div>
    </section>

    <section id="custom" class="custom-section">
        <div class="custom-area">
            <div class="custom-img" data-aos="fade-right" data-aos-duration="1500"></div>
            
            <div class="custom-form-wrapper" data-aos="fade-left" data-aos-duration="1500" data-aos-delay="200">
                <h2 class="serif"><?= $txt['cus_title'] ?></h2>
                <p><?= $txt['cus_desc'] ?></p>
                
                <form method="POST" class="custom-form">
                    <input type="text" name="nama_custom" placeholder="<?= $txt['cus_ph_name'] ?>" required>
                    <input type="number" name="wa_custom" placeholder="<?= $txt['cus_ph_wa'] ?>" required>
                    <select name="budget" required>
                        <option value="" disabled selected><?= $txt['cus_ph_budget'] ?></option>
                        <option value="Rp 300.000 - Rp 500.000">Rp 300.000 - Rp 500.000</option>
                        <option value="Rp 500.000 - Rp 1.000.000">Rp 500.000 - Rp 1.000.000</option>
                        <option value="> Rp 1.000.000"><?= $txt['cus_opt_more'] ?></option>
                    </select>
                    <textarea name="konsep" rows="3" placeholder="<?= $txt['cus_ph_concept'] ?>" required></textarea>
                    <button type="submit" name="submit_custom" class="btn-custom"><?= $txt['cus_btn'] ?></button>
                </form>
            </div>
        </div>
    </section>

    <!-- FAQ & Ketentuan Layanan -->
    <section class="section faq-section" id="faq">
        <div class="sec-header" data-aos="fade-up">
            <h2 class="sec-title serif"><?= $txt['faq_title'] ?></h2>
            <p class="sec-desc"><?= $txt['faq_desc'] ?></p>
        </div>

        <div class="faq-container" data-aos="fade-up" data-aos-delay="100">
            <!-- Item 1 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span><?= $txt['faq_q1'] ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <?= $txt['faq_a1'] ?>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span><?= $txt['faq_q2'] ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <?= $txt['faq_a2'] ?>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span><?= $txt['faq_q3'] ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <?= $txt['faq_a3'] ?>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span><?= $txt['faq_q4'] ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        <?= $txt['faq_a4'] ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer data-aos="fade-in" data-aos-duration="1500">
        <div class="f-brand">
            <i class="fas fa-leaf"></i> Embun Visual
        </div>
        <p class="f-text"><?= $txt['footer_desc'] ?></p>
        <div class="social-links">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-pinterest-p"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>
        </div>
        <p class="copyright"><?= $txt['footer_copy'] ?></p>
    </footer>

    <div class="modal" id="modalBooking">
        <div class="modal-content">
            <span class="close-modal" onclick="tutupModal()">&times;</span>
            <h3 class="modal-title serif"><?= $txt['mod_title'] ?></h3>
            <p class="modal-subtitle"><?= $txt['mod_sub'] ?> <br><strong id="judulTema"></strong></p>
            
            <form method="POST">
                <input type="hidden" name="tema_id" id="temaIdInput">
                <div class="form-group">
                    <input type="text" name="nama" class="form-control" placeholder="<?= $txt['mod_ph_name'] ?>" required>
                </div>
                <div class="form-group">
                    <input type="number" name="whatsapp" class="form-control" placeholder="<?= $txt['mod_ph_wa'] ?>" required>
                </div>
                <div class="form-group">
                    <input type="date" name="tanggal_acara" class="form-control" required style="color: #A0A0A0;" onfocus="this.style.color='var(--text-main)'">
                </div>
                <div class="form-group" style="display: flex; align-items: flex-start; gap: 12px; margin-top: 25px;">
                    <input type="checkbox" id="agreeTerms" name="agree_terms" required style="margin-top: 5px; cursor: pointer; accent-color: var(--gold);">
                    <label for="agreeTerms" style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; cursor: pointer;">
                        <?= $txt['mod_agree'] ?>
                    </label>
                </div>
                <button type="submit" name="submit_booking" class="btn-modal"><?= $txt['mod_btn'] ?> <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS Animation with softer easing
        AOS.init({
            once: true, 
            duration: 1200, 
            offset: 80,
            easing: 'ease-out-quart'
        });

        // Glass Navbar Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 60) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const modal = document.getElementById('modalBooking');
        function bukaModal(id, nama, harga) {
            modal.classList.add('show');
            document.getElementById('temaIdInput').value = id;
            document.getElementById('judulTema').innerText = nama + " (Rp " + parseInt(harga).toLocaleString('id-ID') + ")";
        }
        function tutupModal() { modal.classList.remove('show'); }
        window.onclick = function(event) { if (event.target == modal) tutupModal(); }

        // Popup Syarat Ketentuan
        function showTermsPopup(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Syarat & Ketentuan Layanan',
                html: `
                    <div style="text-align: left; font-size: 0.95rem; color: #555; line-height: 1.8;">
                        <ul style="padding-left: 15px; margin-bottom: 0;">
                            <li style="margin-bottom: 12px;">Pembayaran harus dilunasi 100% sebelum pengerjaan final, atau minimal DP 50%.</li>
                            <li style="margin-bottom: 12px;">Foto & video bebas hak cipta menjadi tanggung jawab klien.</li>
                            <li style="margin-bottom: 12px;">Revisi minor maksimal 3 kali sebelum Hari H acara.</li>
                            <li style="margin-bottom: 12px;">Masa aktif link undangan/web berlangsung selama 6 bulan setelah tanggal acara.</li>
                            <li>Tamu yang mengisi form kehadiran akan tercatat dengan aman dan dijaga privasinya.</li>
                        </ul>
                    </div>
                `,
                confirmButtonText: 'Tutup & Mengerti',
                confirmButtonColor: '#D4AF37',
                background: '#FAF8F5',
                color: '#1D1F1E',
                didOpen: () => {
                    const swalContainer = document.querySelector('.swal2-container');
                    if (swalContainer) {
                        swalContainer.style.zIndex = '9999';
                    }
                }
            });
        }
        
        // Accordion (FAQ) Logic
        const accordions = document.querySelectorAll('.accordion-header');
        accordions.forEach(acc => {
            acc.addEventListener('click', function() {
                accordions.forEach(otherAcc => {
                    if(otherAcc !== this && otherAcc.parentElement.classList.contains('active')) {
                        otherAcc.parentElement.classList.remove('active');
                    }
                });
                this.parentElement.classList.toggle('active');
            });
        });
        
        // SweetAlert for Custom Request Success
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'custom_success') {
            Swal.fire({ 
                icon: 'success', 
                title: 'Terkirim', 
                text: 'Tim desain kami akan mempelajari konsep Anda dan menghubungi melalui WhatsApp segera.', 
                confirmButtonColor: '#D4AF37',
                background: '#FAF8F5',
                color: '#1D1F1E'
            });
            // Clean URL after showing alert
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>