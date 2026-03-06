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
        'hero_title' => 'Merayakan Cinta<br>dengan Keanggunan',
        'hero_desc' => 'Kami merancang undangan pernikahan digital premium, menceritakan kisah cinta Anda dengan elegan, praktis, serta memberikan impresi mewah yang tak terlupakan.',
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
        'cus_desc' => 'Ingin konsep yang lebih personal? Tim desain kami siap berkolaborasi untuk merancang dari nol undangan digital eksklusif yang merefleksikan karakter dan tema impian hari bahagiamu.',
        'cus_ph_name' => 'Nama Pasangan (Cth: Romeo & Juliet)',
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
        'mod_ph_name' => 'Nama Pasangan Pengantin',
        'mod_ph_wa' => 'Nomor WhatsApp',
        'mod_agree' => 'Saya telah membaca & menyetujui seluruh <a href="#" onclick="showTermsPopup(event)" style="color: var(--primary); text-decoration: underline;">Syarat & Ketentuan Layanan</a> Embun Visual.',
        'mod_btn' => 'Lanjut ke Eksekusi'
    ],
    'en' => [
        'nav_katalog' => 'Theme Collection',
        'nav_custom' => 'Bespoke Design',
        'nav_contact' => 'Contact Us',
        'hero_badge' => 'Luxury Digital Invitations',
        'hero_title' => 'Celebrating Love<br>with Elegance',
        'hero_desc' => 'We design premium digital wedding invitations, narrating your love story with elegance and practicality, leaving an unforgettable luxurious impression.',
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
        'cus_desc' => 'Looking for a more personal concept? Our design team is ready to collaborate to design an exclusive digital invitation from scratch that reflects your character and dream wedding theme.',
        'cus_ph_name' => 'Couple\'s Name (Ex: Romeo & Juliet)',
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
        'mod_ph_name' => 'Bride & Groom Name',
        'mod_ph_wa' => 'WhatsApp Number',
        'mod_agree' => 'I have read & agree to all Embun Visual <a href="#" onclick="showTermsPopup(event)" style="color: var(--primary); text-decoration: underline;">Terms & Conditions of Service</a>.',
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
    $pesan_wa = "Halo *Embun Visual*!%0A%0ASaya ingin diskusi pembuatan *Tema Custom* khusus untuk pernikahan saya.%0A*Nama:* $nama%0A*Estimasi Budget:* $budget%0A*Konsep:* $konsep%0A%0AApakah bisa dibantu?";
    
    echo "<script>window.open('https://api.whatsapp.com/send?phone=$nomor_admin&text=$pesan_wa', '_blank'); window.location.href='index.php?status=custom_success';</script>";
    exit;
}

// Ambil data katalog
$query_katalog = mysqli_query($conn, "SELECT * FROM katalog_tema ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embun Visual | Premium Digital Wedding Invitation</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #121413;       /* Deeper, more luxurious dark tone */
            --primary-light: #2A2F2D; 
            --bg-body: #F9F8F6;       /* Clean, elegant off-white */
            --surface: #FFFFFF;
            --text-main: #1A1A1A; 
            --text-muted: #737373; 
            --gold: #C9A66B;          /* Soft, sophisticated gold */
            --gold-hover: #B08D55;
            --border: #E5E2DC;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        ::selection { background: var(--gold); color: #fff; }
        body { 
            font-family: var(--font-sans); 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            scroll-behavior: smooth; 
            overflow-x: hidden; 
            -webkit-font-smoothing: antialiased;
        }
        .serif { font-family: var(--font-serif); }
        a { text-decoration: none; color: inherit; }
        
        /* Typography adjustments */
        h1, h2, h3, h4, h5, h6 { font-weight: 500; line-height: 1.2; }
        p { line-height: 1.7; }

        /* Navbar */
        .navbar { 
            position: fixed; width: 100%; top: 0; padding: 30px 6%; 
            display: flex; justify-content: space-between; align-items: center; 
            z-index: 1000; transition: var(--transition); 
            background: transparent; border-bottom: 1px solid transparent;
        }
        .navbar.scrolled { 
            background: rgba(255, 255, 255, 0.98); 
            backdrop-filter: blur(10px); 
            padding: 20px 6%; 
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
        .nav-brand { 
            font-size: 1.3rem; font-weight: 600; color: #fff; 
            letter-spacing: 3px; text-transform: uppercase; transition: var(--transition); 
        }
        .navbar.scrolled .nav-brand { color: var(--primary); }
        
        .nav-links { display: flex; align-items: center; gap: 40px; }
        .nav-links a { 
            color: #fff; font-weight: 400; font-size: 0.8rem; 
            text-transform: uppercase; letter-spacing: 2px; transition: var(--transition); 
            position: relative;
        }
        .navbar.scrolled .nav-links a { color: var(--text-main); }
        .nav-links a::after {
            content: ''; position: absolute; width: 0; height: 1px; 
            bottom: -5px; left: 0; background-color: var(--gold); 
            transition: var(--transition);
        }
        .nav-links a:hover::after { width: 100%; }
        .navbar.scrolled .nav-links a:hover { color: var(--gold); }
        
        .btn-nav { 
            background: transparent; border: 1px solid #fff; color: #fff !important; 
            padding: 12px 30px; font-size: 0.8rem; letter-spacing: 1px;
        }
        .btn-nav:hover { background: #fff; color: var(--primary) !important; }
        .btn-nav::after { display: none; }
        .navbar.scrolled .btn-nav { 
            border-color: var(--primary); color: var(--primary) !important; 
        }
        .navbar.scrolled .btn-nav:hover { 
            background: var(--primary); color: #fff !important; 
        }

        /* Hero */
        .hero { 
            height: 100vh; min-height: 700px;
            display: flex; align-items: center; justify-content: center; text-align: center; 
            background: linear-gradient(to bottom, rgba(18, 20, 19, 0.4), rgba(18, 20, 19, 0.7)), 
                        url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=1920&q=80') center/cover fixed; 
            padding: 0 20px; position: relative; 
        }
        .hero-content { position: relative; z-index: 2; max-width: 900px; padding-top: 60px; }
        
        .badge-hero { 
            font-size: 0.75rem; letter-spacing: 4px; text-transform: uppercase; 
            color: var(--gold); margin-bottom: 30px; display: inline-block; 
            position: relative;
        }
        .badge-hero::before, .badge-hero::after {
            content: ''; position: absolute; top: 50%; width: 30px; height: 1px; background: var(--gold);
        }
        .badge-hero::before { left: -45px; }
        .badge-hero::after { right: -45px; }

        .hero h1 { 
            font-size: 5.5rem; color: #fff; line-height: 1.1; 
            margin-bottom: 30px; font-weight: 400; font-style: italic;
        }
        .hero p { 
            font-size: 1.1rem; color: rgba(255,255,255,0.8); max-width: 600px; 
            margin: 0 auto 50px; font-weight: 300; line-height: 1.9; letter-spacing: 0.5px;
        }
        .btn-hero { 
            background: var(--gold); color: #fff; padding: 18px 45px; 
            font-weight: 500; display: inline-block; transition: var(--transition); 
            font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase;
            border: 1px solid var(--gold);
        }
        .btn-hero:hover { 
            background: transparent; color: var(--gold); transform: translateY(-3px); 
        }

        /* Global Section */
        .section { padding: 140px 6%; max-width: 1300px; margin: 0 auto; }
        .sec-header { text-align: center; margin-bottom: 80px; }
        .sec-title { 
            font-size: 3.5rem; color: var(--primary); margin-bottom: 20px; 
            font-weight: 400; font-style: italic;
        }
        .sec-desc { 
            color: var(--text-muted); max-width: 600px; margin: 0 auto; 
            font-size: 1.05rem; line-height: 1.8; font-weight: 300;
        }

        /* Grid Katalog */
        .grid { 
            display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); 
            gap: 50px; 
        }
        .card { 
            background: var(--surface); 
            border: 1px solid transparent;
            transition: var(--transition); 
            display: flex; flex-direction: column; 
            position: relative;
        }
        .card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 50px rgba(0,0,0,0.06); 
            border-color: var(--border);
        }
        .c-img-wrapper { 
            height: 420px; overflow: hidden; position: relative; 
        }
        .c-img { 
            width: 100%; height: 100%; background-size: cover; 
            background-position: center; transition: transform 1.2s cubic-bezier(0.25, 1, 0.5, 1); 
        }
        .card:hover .c-img { transform: scale(1.05); }
        .c-badge { 
            position: absolute; top: 20px; left: 20px; 
            background: #fff; padding: 6px 15px; 
            font-size: 0.7rem; font-weight: 500; color: var(--text-main); 
            letter-spacing: 2px; text-transform: uppercase; z-index: 2; 
        }
        .c-body { 
            padding: 40px 30px; display: flex; flex-direction: column; 
            flex: 1; text-align: center; background: var(--surface); 
            border-left: 1px solid var(--border); border-right: 1px solid var(--border); border-bottom: 1px solid var(--border);
        }
        .c-body h3 { 
            font-size: 2rem; margin-bottom: 10px; color: var(--primary); 
            font-weight: 400; font-style: italic;
        }
        .c-price { 
            color: var(--gold); font-weight: 400; font-size: 1.1rem; 
            margin-bottom: 25px; letter-spacing: 1px;
        }
        .c-desc { 
            color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; 
            margin-bottom: 35px; flex: 1; font-weight: 300;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .c-actions { 
            display: flex; gap: 15px; margin-top: auto; 
        }
        .btn-outline, .btn-solid {
            flex: 1; padding: 15px; text-align: center; font-size: 0.8rem; 
            letter-spacing: 2px; text-transform: uppercase; transition: var(--transition);
        }
        .btn-outline { 
            border: 1px solid var(--border); color: var(--text-main); background: transparent;
        }
        .btn-outline:hover { 
            border-color: var(--primary); color: #fff; background: var(--primary); 
        }
        .btn-solid { 
            background: var(--primary); color: #fff; border: 1px solid var(--primary); cursor: pointer;
        }
        .btn-solid:hover { 
            background: transparent; color: var(--primary); 
        }

        /* Custom Area */
        .custom-section { 
            background: var(--bg-body); padding: 120px 6%; 
        }
        .custom-area { 
            display: flex; min-height: 600px; max-width: 1100px; margin: 0 auto;
            background: var(--surface); border-radius: 24px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.06); 
            overflow: hidden; border: 1px solid rgba(229, 226, 220, 0.7);
        }
        .custom-img {
            flex: 1; background: url('https://images.unsplash.com/photo-1520854221256-17451cc331bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80') center/cover;
            position: relative;
        }
        .custom-form-wrapper { 
            flex: 1.2; padding: 70px 8%; 
            display: flex; flex-direction: column; justify-content: center;
        }
        .custom-form-wrapper h2 { 
            font-size: 3rem; margin-bottom: 15px; color: var(--primary); font-style: italic;
        }
        .custom-form-wrapper p { 
            color: var(--text-muted); font-weight: 300; margin-bottom: 35px; line-height: 1.8;
        }
        
        .custom-form input, .custom-form select, .custom-form textarea { 
            width: 100%; padding: 16px 20px; margin-bottom: 20px; 
            border: 1px solid var(--border); border-radius: 12px;
            font-family: inherit; font-size: 0.95rem; outline: none; 
            transition: var(--transition); background: rgba(249, 248, 246, 0.6); 
            color: var(--text-main); font-weight: 300;
        }
        .custom-form input::placeholder, .custom-form textarea::placeholder, .custom-form select { 
            color: #A0A0A0; 
        }
        .custom-form input:focus, .custom-form select:focus, .custom-form textarea:focus { 
            border-color: var(--gold); background: #fff; box-shadow: 0 0 0 4px rgba(201, 166, 107, 0.1);
        }
        .btn-custom { 
            padding: 18px 40px; font-size: 0.85rem; background: var(--primary); border-radius: 12px;
            color: white; border: 1px solid var(--primary); cursor: pointer; box-shadow: 0 10px 20px rgba(18, 20, 19, 0.1);
            letter-spacing: 2px; text-transform: uppercase; transition: var(--transition);
            margin-top: 10px; display: inline-block; width: 100%; text-align: center;
        }
        .btn-custom:hover { 
            background: transparent; color: var(--primary); box-shadow: none;
        }

        /* Footer */
        footer { 
            text-align: center; padding: 100px 20px 50px; 
            background: var(--bg-body); 
        }
        .f-brand { 
            font-size: 1.8rem; color: var(--primary); margin-bottom: 20px; 
            letter-spacing: 4px; text-transform: uppercase; font-weight: 400; font-family: var(--font-serif);
            font-style: italic;
        }
        .f-text { 
            color: var(--text-muted); font-size: 1rem; margin-bottom: 40px; font-weight: 300; 
            max-width: 450px; margin-left: auto; margin-right: auto;
        }
        .social-links {
            display: flex; justify-content: center; gap: 20px; margin-bottom: 60px;
        }
        .social-links a {
            color: var(--text-main); font-size: 1.1rem; transition: var(--transition);
            width: 45px; height: 45px; border: 1px solid var(--border); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }
        .social-links a:hover {
            border-color: var(--gold); color: var(--gold); background: #fff;
        }
        .copyright {
            font-size: 0.75rem; color: #A0A0A0; letter-spacing: 2px; text-transform: uppercase;
        }

        /* Modal Booking */
        .modal { 
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(18, 20, 19, 0.85); backdrop-filter: blur(8px); 
            z-index: 2000; align-items: center; justify-content: center; 
            opacity: 0; transition: opacity 0.4s ease; 
        }
        .modal.show { display: flex; opacity: 1; }
        .modal-content { 
            background: var(--surface); padding: 60px 50px; width: 100%; max-width: 500px; 
            position: relative; transform: translateY(20px); 
            transition: var(--transition); border: 1px solid var(--border); 
        }
        .modal.show .modal-content { transform: translateY(0); }
        .close-modal { 
            position: absolute; top: 20px; right: 25px; font-size: 2rem; 
            cursor: pointer; color: #A0A0A0; transition: var(--transition); 
            width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; 
            font-weight: 300;
        }
        .close-modal:hover { color: var(--primary); transform: rotate(90deg); }
        .modal-title {
            font-size: 2.2rem; color: var(--primary); margin-bottom: 10px; font-style: italic; text-align: center;
        }
        .modal-subtitle {
            color: var(--text-muted); margin-bottom: 40px; font-size: 0.95rem; text-align: center; font-weight: 300;
        }
        .modal-subtitle strong { color: var(--gold); font-weight: 500; font-family: var(--font-serif); font-style: italic; font-size: 1.1rem; }
        
        .form-group { margin-bottom: 25px; }
        .form-control { 
            width: 100%; padding: 15px 0; border: none; border-bottom: 1px solid var(--border); 
            font-family: inherit; font-size: 0.95rem; transition: var(--transition); 
            background: transparent; color: var(--text-main); font-weight: 300;
        }
        .form-control:focus { 
            border-bottom-color: var(--gold); outline: none;
        }
        .btn-modal { 
            width: 100%; padding: 20px; margin-top: 20px; font-size: 0.85rem; 
            background: var(--primary); color: white; border: 1px solid var(--primary); 
            cursor: pointer; letter-spacing: 2px; text-transform: uppercase; 
            transition: var(--transition); display: flex; justify-content: center; align-items: center; gap: 10px;
        }
        .btn-modal:hover { 
            background: transparent; color: var(--primary); 
        }

        /* FAQ & Terms Accordion */
        .faq-section { background: var(--bg); position: relative; }
        .faq-container { max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 15px; }
        .accordion-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0px; 
            overflow: hidden;
            transition: var(--transition);
        }
        .accordion-item.active { border-color: var(--gold); box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .accordion-header {
            width: 100%; text-align: left; padding: 25px 30px;
            background: transparent; border: none; cursor: pointer;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 1.1rem; color: var(--text-main); font-weight: 400; font-family: 'Inter', sans-serif;
        }
        .accordion-header i { color: var(--gold); transition: transform 0.3s ease; }
        .accordion-item.active .accordion-header i { transform: rotate(180deg); }
        .accordion-content {
            max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;
            background: var(--surface);
        }
        .accordion-item.active .accordion-content { max-height: 600px; /* Arbitrary large number for CSS transition */ }
        .accordion-body {
            padding: 0 30px 30px 30px; color: var(--text-muted); line-height: 1.8; font-size: 0.95rem; font-weight: 300;
        }
        .accordion-body ul { padding-left: 20px; margin-top: 10px; }
        .accordion-body li { margin-bottom: 8px; }

        /* Momen Ekstetik (Gallery) */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        .gallery-item {
            position: relative;
            border-radius: 0px;
            overflow: hidden;
            aspect-ratio: 4/5;
            cursor: pointer;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.08);
        }
        .gallery-caption {
            position: absolute;
            bottom: 0; left: 0; width: 100%;
            padding: 30px 20px 20px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: #fff;
            transform: translateY(100%);
            transition: transform 0.4s ease;
            font-size: 0.9rem;
            font-weight: 300;
        }
        .gallery-item:hover .gallery-caption {
            transform: translateY(0);
        }

        @media (max-width: 900px) {
            .custom-area { flex-direction: column; }
            .custom-img { min-height: 400px; }
            .hero h1 { font-size: 4rem; }
            .sec-title { font-size: 2.8rem; }
        }

        @media (max-width: 768px) {
            .navbar { padding: 20px 5%; }
            .navbar.scrolled { padding: 15px 5%; }
            .nav-brand { font-size: 1.1rem; }
            .nav-links { display: none; }
            .hero h1 { font-size: 3rem; }
            .hero p { font-size: 1rem; }
            .custom-form-wrapper { padding: 60px 6%; }
            .modal-content { padding: 40px 30px; }
            .badge-hero::before, .badge-hero::after { width: 20px; }
            .badge-hero::before { left: -30px; }
            .badge-hero::after { right: -30px; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav class="navbar" id="navbar">
        <a href="#" class="nav-brand serif">Embun Visual</a>
        <div class="nav-links">
            <a href="#katalog"><?= $txt['nav_katalog'] ?></a>
            <a href="#custom"><?= $txt['nav_custom'] ?></a>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-nav"><?= $txt['nav_contact'] ?></a>
            <div style="display:flex; gap: 8px; align-items:center; margin-left: 10px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 20px;">
                <a href="?lang=id" style="color: <?= $lang == 'id' ? 'var(--gold)' : '#fff' ?>; font-weight: <?= $lang == 'id' ? '600' : '300' ?>;">ID</a>
                <span style="color: rgba(255,255,255,0.4); font-size:0.8rem;">|</span>
                <a href="?lang=en" style="color: <?= $lang == 'en' ? 'var(--gold)' : '#fff' ?>; font-weight: <?= $lang == 'en' ? '600' : '300' ?>;">EN</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1400">
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
                echo "<div style='grid-column: 1/-1; text-align:center; padding:80px 20px; background:#fff; border:1px solid #E5E2DC;' data-aos='fade-up'><h3 class='serif' style='font-size:2rem; color:var(--primary); margin-bottom:10px;'>{$txt['cat_empty']}</h3><p style='color:#737373; font-weight:300;'>{$txt['cat_empty_desc']}</p></div>";
            }
            ?>
        </div>
    </section>

    <!-- Galeri Momen Dinamis -->
    <section id="galeri" class="section" style="background-color: var(--surface);">
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
                    <?php if(!empty($g['caption'])) { echo "<div style='margin-bottom:5px; font-weight:500;'>".htmlspecialchars($g['caption'])."</div>"; } ?>
                    <?php if(!empty($g['sumber_nama'])) { ?>
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.7);">
                            <?= $txt['gal_source'] ?> 
                            <?php if(!empty($g['sumber_link'])) { ?>
                                <a href="<?php echo htmlspecialchars($g['sumber_link']); ?>" target="_blank" style="color:var(--gold); text-decoration:none; transition:0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--gold)'"><?php echo htmlspecialchars($g['sumber_nama']); ?></a>
                            <?php } else { ?>
                                <span style="color:var(--gold);"><?php echo htmlspecialchars($g['sumber_nama']); ?></span>
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
            <div class="custom-img" data-aos="fade-in" data-aos-duration="1500"></div>
            
            <div class="custom-form-wrapper" data-aos="fade-left" data-aos-duration="1200">
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

    <footer data-aos="fade-in" data-aos-duration="1400">
        <div class="f-brand">Embun Visual</div>
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
                    <input type="date" name="tanggal_acara" class="form-control" required style="color: #A0A0A0;" onfocus="this.style.color='#1A1A1A'">
                </div>
                <div class="form-group" style="display: flex; align-items: flex-start; gap: 10px; margin-top: 20px;">
                    <input type="checkbox" id="agreeTerms" name="agree_terms" required style="margin-top: 5px; cursor: pointer;">
                    <label for="agreeTerms" style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; cursor: pointer;">
                        <?= $txt['mod_agree'] ?>
                    </label>
                </div>
                <button type="submit" name="submit_booking" class="btn-modal"><?= $txt['mod_btn'] ?> <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS Animation
        AOS.init({
            once: true, 
            duration: 1200, 
            offset: 50,
            easing: 'ease-out-cubic'
        });

        // Glass Navbar Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
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
                    <div style="text-align: left; font-size: 0.9rem; color: #555; line-height: 1.6;">
                        <ul style="padding-left: 15px; margin-bottom: 0;">
                            <li style="margin-bottom: 10px;">Pembayaran harus dilunasi 100% sebelum pengerjaan final, atau minimal DP 50%.</li>
                            <li style="margin-bottom: 10px;">Foto & video bebas hak cipta menjadi tanggung jawab klien.</li>
                            <li style="margin-bottom: 10px;">Revisi minor maksimal 3 kali sebelum Hari H acara.</li>
                            <li style="margin-bottom: 10px;">Masa aktif link undangan/web berlangsung selama 6 bulan setelah tanggal acara.</li>
                            <li>Tamu yang mengisi form kehadiran akan tercatat dengan aman dan dijaga privasinya.</li>
                        </ul>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#1A1A1A',
                didOpen: () => {
                    // Paksa z-index SweetAlert agar selalu berada di atas modal bawaan yg memiliki z-index 1000
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
                // Tutup semua akordion lain terlebih dahulu (opsional, jika ingin satu-satu yang terbuka)
                accordions.forEach(otherAcc => {
                    if(otherAcc !== this && otherAcc.parentElement.classList.contains('active')) {
                        otherAcc.parentElement.classList.remove('active');
                    }
                });
                
                // Toggle active pada yang diklik 
                this.parentElement.classList.toggle('active');
            });
        });
        
        // SweetAlert
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'custom_success') {
            Swal.fire({ 
                icon: 'success', 
                title: 'Terkirim', 
                text: 'Tim desain kami akan mempelajari konsep Anda dan menghubungi melalui WhatsApp segera.', 
                confirmButtonColor: '#121413',
                customClass: {
                    popup: 'border-radius-0'
                }
            });
        }
    </script>
</body>
</html>