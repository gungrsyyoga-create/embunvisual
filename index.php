<?php 
// Nyalakan pendeteksi error
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; 

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
            <a href="#katalog">Koleksi Tema</a>
            <a href="#custom">Bespoke Design</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-nav">Hubungi Kami</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1400">
            <span class="badge-hero">Digital Undangan Mewah</span>
            <h1 class="serif">Merayakan Cinta<br>dengan Keanggunan</h1>
            <p>Kami merancang undangan pernikahan digital premium, menceritakan kisah cinta Anda dengan elegan, praktis, serta memberikan impresi mewah yang tak terlupakan.</p>
            <a href="#katalog" class="btn-hero">Eksplorasi Koleksi</a>
        </div>
    </section>

    <section class="section" id="katalog">
        <div class="sec-header" data-aos="fade-up">
            <h2 class="sec-title serif">Koleksi Premium</h2>
            <p class="sec-desc">Temukan pilihan tema berkelas dari kami yang dirancang dengan detail sempurna, harmoni warna, dan tipografi estetis.</p>
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
                        <a href="<?php echo htmlspecialchars(trim($tema['slug_demo'])); ?>" target="_blank" class="btn-outline">Lihat Demo</a>
                        <button onclick="bukaModal('<?php echo $tema['id']; ?>', '<?php echo htmlspecialchars($tema['nama_tema'], ENT_QUOTES); ?>', '<?php echo $tema['harga']; ?>')" class="btn-solid">Booking</button>
                    </div>
                </div>
            </div>
            <?php 
                $delay += 100; 
                } 
            } else {
                echo "<div style='grid-column: 1/-1; text-align:center; padding:80px 20px; background:#fff; border:1px solid #E5E2DC;' data-aos='fade-up'><h3 class='serif' style='font-size:2rem; color:var(--primary); margin-bottom:10px;'>Koleksi Belum Memiliki Tema</h3><p style='color:#737373; font-weight:300;'>Silakan login ke panel admin untuk menambahkan tema baru.</p></div>";
            }
            ?>
        </div>
    </section>

    <!-- Galeri Momen Dinamis -->
    <section id="galeri" class="section" style="background-color: var(--surface);">
        <div class="sec-header" data-aos="fade-up">
            <h2 class="sec-title serif">Momen Estetik</h2>
            <p class="sec-desc">Kumpulan memori indah yang diabadikan dengan sempurna melalui karya visual kami.</p>
        </div>
        
        <div class="gallery-grid" data-aos="fade-up" data-aos-delay="100">
            <?php 
            $query_galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC LIMIT 8");
            if($query_galeri && mysqli_num_rows($query_galeri) > 0) {
                while($g = mysqli_fetch_assoc($query_galeri)) { 
            ?>
            <div class="gallery-item">
                <img src="<?php echo htmlspecialchars($g['gambar']); ?>" alt="Embun Visual Gallery">
                <?php if(!empty($g['caption'])) { ?>
                <div class="gallery-caption"><?php echo htmlspecialchars($g['caption']); ?></div>
                <?php } ?>
            </div>
            <?php 
                } 
            } else {
                echo "<p style='grid-column: 1/-1; text-align:center; color: var(--text-muted);'>Koleksi foto sedang kami siapkan untuk Anda.</p>";
            }
            ?>
        </div>
    </section>

    <section id="custom" class="custom-section">
        <div class="custom-area">
            <div class="custom-img" data-aos="fade-in" data-aos-duration="1500"></div>
            
            <div class="custom-form-wrapper" data-aos="fade-left" data-aos-duration="1200">
                <h2 class="serif">Bespoke Service</h2>
                <p>Ingin konsep yang lebih personal? Tim desain kami siap berkolaborasi untuk merancang dari nol undangan digital eksklusif yang merefleksikan karakter dan tema impian hari bahagiamu.</p>
                
                <form method="POST" class="custom-form">
                    <input type="text" name="nama_custom" placeholder="Nama Pasangan (Cth: Romeo & Juliet)" required>
                    <input type="number" name="wa_custom" placeholder="Nomor WhatsApp (Aktif)" required>
                    <select name="budget" required>
                        <option value="" disabled selected>Estimasi Budget Eksklusif</option>
                        <option value="Rp 300.000 - Rp 500.000">Rp 300.000 - Rp 500.000</option>
                        <option value="Rp 500.000 - Rp 1.000.000">Rp 500.000 - Rp 1.000.000</option>
                        <option value="> Rp 1.000.000">Lebih dari Rp 1.000.000</option>
                    </select>
                    <textarea name="konsep" rows="3" placeholder="Ceritakan detail konsep yang diinginkan (warna, nuansa, mood)..." required></textarea>
                    <button type="submit" name="submit_custom" class="btn-custom">Kirim Permintaan</button>
                </form>
            </div>
        </div>
    </section>

    <!-- FAQ & Ketentuan Layanan -->
    <section class="section faq-section" id="faq">
        <div class="sec-header" data-aos="fade-up">
            <h2 class="sec-title serif">Pertanyaan & Ketentuan</h2>
            <p class="sec-desc">Informasi yang sering ditanyakan seputar layanan undangan digital Embun Visual serta syarat dan ketentuan layanan kami.</p>
        </div>

        <div class="faq-container" data-aos="fade-up" data-aos-delay="100">
            <!-- Item 1 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span>1. Berapa lama proses pengerjaan undangan digital?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        Untuk tema yang sudah tersedia di katalog (Koleksi Tema), proses pengerjaan memakan waktu <strong>1-3 hari kerja</strong> terhitung sejak semua data dan foto dari klien kami terima secara lengkap. Sedangkan untuk layanan <em>Bespoke Design</em> (Custom), waktu pengerjaan berkisar antara <strong>7-14 hari kerja</strong> tergantung pada tingkat kerumitan desain.
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span>2. Apakah saya bisa merevisi data jika ada kesalahan?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        Ya, tentu bisa. Kami memberikan batasan <strong>revisi minor sebanyak 3 kali</strong> (seperti penggantian teks, typo nama, atau ganti foto) sebelum hari H acara. Namun, penggantian total tema atau struktur susunan acara yang fundamental akan dikenakan biaya tambahan.
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span>3. Berapa lama masa aktif link undangan web?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        Undangan web Anda akan tetap aktif dan dapat diakses publik hingga <strong>6 bulan setelah tanggal acara (Hari H)</strong> berlalu. Lewat dari masa tersebut, sistem kami akan secara otomatis menghapus database undangan beserta data kehadiran tamu (RSVP) dari server.
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="accordion-item">
                <button class="accordion-header">
                    <span>4. Syarat dan Ketentuan Layanan (Terms & Conditions)</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="accordion-body">
                        Dengan menggunakan layanan Embun Visual, Anda menyetujui poin-poin berikut:
                        <ul>
                            <li>Pembayaran harus dilunasi 100% sebelum tim kami memulai proses pengerjaan final, atau minimal DP 50% untuk menahan slot pengerjaan.</li>
                            <li>Foto dan video profil yang diberikan kepada kami harus menjadi hak milik klien atau sudah mendapat izin penggunaan (Membeli lisensi / Non-copyright infringement). Kami tidak bertanggung jawab atas tuntutan hak cipta dari pihak ketiga.</li>
                            <li>Data RSVP tamu dijaga privasinya oleh sistem kami dan tidak akan dijual ke pihak manapun. Namun, klien bertanggung jawab jika mendistribusikan link secara publik yang mengakibatkan <i>spam</i> dari tamu yang tak diundang.</li>
                            <li>Embun Visual berhak menggunakan <i>screenshot</i> undangan yang telah selesai sebagai portofolio publikasi kami kecuali klien meminta perjanjian <i>Non-Disclosure Agreement (NDA)</i> secara tertulis.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer data-aos="fade-in" data-aos-duration="1400">
        <div class="f-brand">Embun Visual</div>
        <p class="f-text">Membingkai cerita Anda menjadi mahakarya digital abadi, dirancang dengan presisi dan sentuhan keanggunan.</p>
        <div class="social-links">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-pinterest-p"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>
        </div>
        <p class="copyright">&copy; 2024 Embun Visual. Seluruh Hak Cipta Dilindungi.</p>
    </footer>

    <div class="modal" id="modalBooking">
        <div class="modal-content">
            <span class="close-modal" onclick="tutupModal()">&times;</span>
            <h3 class="modal-title serif">Reservasi</h3>
            <p class="modal-subtitle">Tema Terpilih: <br><strong id="judulTema"></strong></p>
            
            <form method="POST">
                <input type="hidden" name="tema_id" id="temaIdInput">
                <div class="form-group">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Pasangan Pengantin" required>
                </div>
                <div class="form-group">
                    <input type="number" name="whatsapp" class="form-control" placeholder="Nomor WhatsApp" required>
                </div>
                <div class="form-group">
                    <input type="date" name="tanggal_acara" class="form-control" required style="color: #A0A0A0;" onfocus="this.style.color='#1A1A1A'">
                </div>
                <div class="form-group" style="display: flex; align-items: flex-start; gap: 10px; margin-top: 20px;">
                    <input type="checkbox" id="agreeTerms" name="agree_terms" required style="margin-top: 5px; cursor: pointer;">
                    <label for="agreeTerms" style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; cursor: pointer;">
                        Saya telah membaca & menyetujui seluruh <a href="#" onclick="showTermsPopup(event)" style="color: var(--primary); text-decoration: underline;">Syarat & Ketentuan Layanan</a> Embun Visual.
                    </label>
                </div>
                <button type="submit" name="submit_booking" class="btn-modal">Lanjut ke Eksekusi <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></button>
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