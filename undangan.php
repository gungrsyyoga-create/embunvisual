<?php
include 'config.php';

// Ambil slug dari URL (contoh: undangan.php?nama=demo-sage)
$slug = isset($_GET['nama']) ? mysqli_real_escape_text($conn, $_GET['nama']) : '';

// Cari data di database
$query = mysqli_query($conn, "SELECT * FROM undangan WHERE slug = '$slug'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan, tampilkan pesan error
if (!$data) {
    echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h2>Undangan Tidak Ditemukan</h2>
            <p>Maaf, link undangan yang Anda tuju tidak valid.</p>
            <a href='index.php'>Kembali ke Beranda</a>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan | <?php echo $data['pria']; ?> & <?php echo $data['wanita']; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Plus+Jakarta+Sans:wght@300;400;500&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #5C715E; /* Sage Green */
            --bg: #F7F5F0;
            --text: #2C3E2D;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg); color: var(--text); overflow-x: hidden; }

        /* --- HERO SECTION --- */
        .hero {
            height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&q=80') center/cover;
            color: white;
            padding: 20px;
        }
        .hero h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 300; letter-spacing: 5px; text-transform: uppercase; font-size: 1rem; margin-bottom: 20px; }
        .hero h1 { font-family: 'Great Vibes', cursive; font-size: 5rem; margin-bottom: 10px; }
        .hero p { font-size: 1.1rem; opacity: 0.9; }

        /* --- COUPLE SECTION --- */
        .section { padding: 80px 20px; text-align: center; max-width: 800px; margin: 0 auto; }
        .couple-name { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--primary); margin: 15px 0; }
        .ampersand { font-family: 'Great Vibes', cursive; font-size: 3rem; color: #d4af37; margin: 10px 0; }

        /* --- COUNTDOWN --- */
        .countdown-container { display: flex; justify-content: center; gap: 15px; margin-top: 30px; }
        .count-box { background: white; padding: 15px; border-radius: 10px; min-width: 70px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .count-box span { display: block; font-size: 1.5rem; font-weight: 700; color: var(--primary); }
        .count-box label { font-size: 0.7rem; text-transform: uppercase; color: #888; }

        /* --- INFO ACARA --- */
        .event-card { background: white; padding: 40px; border-radius: 20px; margin-top: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .event-card i { font-size: 2rem; color: var(--primary); margin-bottom: 15px; }
        .btn-maps { display: inline-block; margin-top: 20px; padding: 12px 25px; background: var(--primary); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 0.9rem; }

        /* --- FOOTER --- */
        footer { padding: 40px; background: #fff; text-align: center; font-size: 0.9rem; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>

    <section class="hero">
        <div data-aos="fade-up" data-aos-duration="1500">
            <h2>The Wedding Of</h2>
            <h1><?php echo $data['pria']; ?> & <?php echo $data['wanita']; ?></h1>
            <p><?php echo date('d . m . Y', strtotime($data['tanggal'])); ?></p>
        </div>
    </section>

    <section class="section">
        <div data-aos="fade-up">
            <p>Maha Suci Allah yang telah menciptakan mahluk-Nya berpasang-pasangan. Ya Allah, perkenankanlah kami merangkaikan kasih sayang-Mu dalam ikatan suci pernikahan.</p>
            
            <h2 class="couple-name"><?php echo $data['pria']; ?></h2>
            <p style="color: #888;">Putra dari Bapak Fulan & Ibu Fulanah</p>
            
            <div class="ampersand">&</div>
            
            <h2 class="couple-name"><?php echo $data['wanita']; ?></h2>
            <p style="color: #888;">Putri dari Bapak Fulan & Ibu Fulanah</p>
        </div>

        <div class="countdown-container" id="countdown" data-aos="zoom-in">
            <div class="count-box"><span id="days">00</span><label>Hari</label></div>
            <div class="count-box"><span id="hours">00</span><label>Jam</label></div>
            <div class="count-box"><span id="mins">00</span><label>Menit</label></div>
            <div class="count-box"><span id="secs">00</span><label>Detik</label></div>
        </div>
    </section>

    <section class="section" style="background: #fff;">
        <div class="event-card" data-aos="fade-right">
            <i class="fas fa-calendar-alt"></i>
            <h3 class="font-serif" style="font-size: 1.5rem; margin-bottom: 10px;">Resepsi Pernikahan</h3>
            <p><strong><?php echo date('l, d F Y', strtotime($data['tanggal'])); ?></strong></p>
            <p>Pukul 10.00 WIB - Selesai</p>
            <hr style="margin: 20px 0; opacity: 0.1;">
            <i class="fas fa-map-marker-alt"></i>
            <p><?php echo nl2br($data['alamat']); ?></p>
            <a href="https://maps.google.com" target="_blank" class="btn-maps">Buka Google Maps</a>
        </div>
    </section>

    <footer>
        <p>Dibuat dengan ❤️ oleh <strong>Embun Visual</strong></p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // LOGIKA HITUNG MUNDUR (COUNTDOWN)
        const targetDate = new Date("<?php echo $data['tanggal']; ?> 10:00:00").getTime();

        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            const d = Math.floor(distance / (1000 * 60 * 60 * 24));
            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerHTML = d;
            document.getElementById("hours").innerHTML = h;
            document.getElementById("mins").innerHTML = m;
            document.getElementById("secs").innerHTML = s;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "ACARA SUDAH DIMULAI";
            }
        }, 1000);
    </script>
</body>
</html>