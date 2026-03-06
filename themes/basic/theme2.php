<?php
// basic/theme2.php
$guest_name = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Spesial';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan | Theme 2</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4A5D4E; /* Earthy Green */
            --bg-light: #F9FDF9;
            --text-dark: #2C3E30;
            --text-muted: #6B806E;
            --accent: #D4AF37;
            --font-serif: 'Cormorant Garamond', serif;
            --font-body: 'Montserrat', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background-color: var(--bg-light); color: var(--text-dark); overflow-x: hidden; line-height: 1.8; }
        .serif { font-family: var(--font-serif); }
        .text-center { text-align: center; }

        /* COVER */
        #cover {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            background: linear-gradient(rgba(249,253,249,0.9), rgba(249,253,249,0.9)), url('https://images.unsplash.com/photo-1460500063983-994d4c27756c?q=80&w=1000&auto=format&fit=crop') center/cover;
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1);
        }
        #cover.open { transform: translateY(-100%); }
        .cover-title { font-size: 4rem; color: var(--primary); margin: 20px 0; }
        .guest-box { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(74,93,78,0.1); margin-bottom: 30px; border: 1px solid rgba(74,93,78,0.2); }
        .guest-name { font-size: 1.5rem; font-weight: 600; color: var(--primary); margin: 10px 0; }
        .btn-open { background: var(--primary); color: #fff; border: none; padding: 15px 40px; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; border-radius: 4px; cursor: pointer; transition: 0.3s; }
        .btn-open:hover { background: var(--text-dark); }

        /* HERO */
        .hero { height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; background: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1000&auto=format&fit=crop') center/cover fixed; position: relative; color: white; }
        .hero::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(44,62,48,0.6); }
        .hero-content { position: relative; z-index: 2; text-align: center; border: 2px solid rgba(255,255,255,0.3); padding: 60px 40px; border-radius: 10px; backdrop-filter: blur(2px); }
        .hero-title { font-size: 4.5rem; margin: 20px 0; }
        .countdown { display: flex; gap: 20px; margin-top: 30px; justify-content: center; }
        .cd-box { text-align: center; }
        .cd-num { font-size: 2.5rem; font-family: var(--font-serif); }
        .cd-text { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; }

        /* SECTIONS */
        .section { padding: 100px 20px; text-align: center; max-width: 800px; margin: 0 auto; }
        .sec-title { font-size: 3rem; color: var(--primary); margin-bottom: 20px; }
        .leaf-icon { font-size: 2rem; color: var(--accent); margin-bottom: 20px; }

        /* COUPLE */
        .couple-wrapper { display: flex; flex-direction: column; gap: 60px; margin-top: 50px; }
        .person { display: flex; align-items: center; gap: 30px; text-align: left; }
        .person.reverse { flex-direction: row-reverse; text-align: right; }
        .person-img { width: 180px; height: 180px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary); padding: 5px; }
        .person-name { font-size: 2.2rem; color: var(--primary); margin-bottom: 5px; }
        .and-symbol { font-size: 3rem; color: var(--accent); font-family: var(--font-serif); font-style: italic; }

        /* EVENT */
        .event-card { background: #fff; padding: 50px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05); border-top: 5px solid var(--primary); }
        .event-title { font-size: 2rem; color: var(--primary); margin-bottom: 10px; }
        .btn-map { display: inline-block; background: var(--primary); color: #fff; padding: 12px 30px; border-radius: 30px; text-decoration: none; margin-top: 25px; font-size: 0.9rem; transition: 0.3s; }
        .btn-map:hover { background: var(--text-dark); }

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 40px; }
        .gallery-grid img { width: 100%; height: 250px; object-fit: cover; border-radius: 10px; }
        .gallery-grid img:first-child { grid-column: 1 / -1; height: 400px; }

        /* AUDIO */
        .audio-btn { position: fixed; bottom: 30px; left: 30px; width: 45px; height: 45px; background: #fff; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 1000; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid var(--primary); }

        @media (max-width: 768px) {
            .hero-title { font-size: 3.5rem; }
            .person { flex-direction: column; text-align: center; }
            .person.reverse { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY SAMPUL -->
    <div id="cover">
        <i class="fas fa-leaf leaf-icon" data-aos="fade-down"></i>
        <div class="serif" style="letter-spacing: 3px; font-size: 1.1rem; text-transform: uppercase;">Undangan Pernikahan</div>
        <h1 class="serif cover-title" data-aos="zoom-in">Romeo & Juliet</h1>
        
        <div class="guest-box" data-aos="fade-up" data-aos-delay="200">
            <p style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Kepada Yth:</p>
            <div class="guest-name serif"><?php echo $guest_name; ?></div>
            <p style="font-size: 0.8rem; color: var(--text-muted);">Di Tempat</p>
        </div>

        <button class="btn-open" onclick="bukaUndangan()" data-aos="fade-up" data-aos-delay="400">Buka Undangan</button>
    </div>

    <!-- AUDIO PLAYER -->
    <audio id="bg-music" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3" type="audio/mpeg"></audio>
    <div class="audio-btn" id="audioCtrl" onclick="toggleAudio()" style="display: none;"><i class="fas fa-pause"></i></div>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <section class="hero">
            <div class="hero-content">
                <p style="letter-spacing: 4px; text-transform: uppercase; font-size: 0.9rem;">Save The Date</p>
                <h1 class="hero-title serif">Romeo & Juliet</h1>
                <p style="font-weight: 300; letter-spacing: 2px;">20 . 12 . 2026</p>
                
                <div class="countdown">
                    <div class="cd-box"><div class="cd-num" id="days">00</div><div class="cd-text">Hari</div></div>
                    <div class="cd-box"><div class="cd-num" id="hours">00</div><div class="cd-text">Jam</div></div>
                    <div class="cd-box"><div class="cd-num" id="minutes">00</div><div class="cd-text">Mnt</div></div>
                    <div class="cd-box"><div class="cd-num" id="seconds">00</div><div class="cd-text">Dtk</div></div>
                </div>
            </div>
        </section>

        <section class="section">
            <i class="fas fa-seedling leaf-icon" data-aos="fade-up"></i>
            <h2 class="sec-title serif" data-aos="fade-up">Sang Mempelai</h2>
            <p data-aos="fade-up" style="color: var(--text-muted);">Dengan memohon rahmat dan ridho Tuhan Yang Maha Esa, kami mengundang Anda untuk hadir pada momen paling bahagia dalam hidup kami.</p>
            
            <div class="couple-wrapper">
                <div class="person" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Groom">
                    <div>
                        <h3 class="person-name serif">Romeo Montague</h3>
                        <p style="font-size: 0.9rem; color: var(--text-muted);">Putra dari Bpk. Montague & Ibu Montague</p>
                    </div>
                </div>
                
                <div class="and-symbol" data-aos="zoom-in">&</div>
                
                <div class="person reverse" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Bride">
                    <div>
                        <h3 class="person-name serif">Juliet Capulet</h3>
                        <p style="font-size: 0.9rem; color: var(--text-muted);">Putri dari Bpk. Capulet & Ibu Capulet</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" style="background-color: #f0f5f1;">
            <h2 class="sec-title serif" data-aos="fade-up">Detail Acara</h2>
            
            <div class="event-card" data-aos="zoom-in" data-aos-delay="200">
                <i class="far fa-calendar-alt" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 15px;"></i>
                <h3 class="event-title serif">Resepsi Pernikahan</h3>
                <p style="font-size: 1.1rem; font-weight: 500;">Minggu, 20 Desember 2026</p>
                <p style="color: var(--text-muted); margin-bottom: 20px;">Pukul 10.00 WITA - Selesai</p>
                <p><strong>Villa Hijau Bali</strong><br><span style="font-size: 0.9rem; color: var(--text-muted);">Jl. Munduk Indah, Buleleng, Bali</span></p>
                <a href="https://goo.gl/maps" target="_blank" class="btn-map">Buka Peta Lokasi</a>
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title serif" data-aos="fade-up">Memori Kita</h2>
            <div class="gallery-grid">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1000&auto=format&fit=crop" data-aos="zoom-in" alt="Gallery 1">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery 2">
                <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery 3">
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title serif">Terima Kasih</h2>
            <p style="color: var(--text-muted);">Kehadiran dan doa restu Anda adalah pelengkap kebahagiaan kami.</p>
            <h3 class="serif" style="font-size: 2.5rem; margin-top: 30px; color: var(--primary);">Romeo & Juliet</h3>
        </section>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1200, once: true });
        document.body.style.overflowY = "hidden";

        const audio = document.getElementById("bg-music");
        const audioCtrl = document.getElementById("audioCtrl");
        let isPlaying = false;

        function bukaUndangan() {
            document.getElementById("cover").classList.add("open");
            audioCtrl.style.display = "flex";
            audio.play().then(() => {
                isPlaying = true;
            });
            document.body.style.overflowY = "auto";
        }

        function toggleAudio() {
            if (isPlaying) { audio.pause(); audioCtrl.innerHTML = '<i class="fas fa-play"></i>'; } 
            else { audio.play(); audioCtrl.innerHTML = '<i class="fas fa-pause"></i>'; }
            isPlaying = !isPlaying;
        }

        const countDownDate = new Date("Dec 20, 2026 10:00:00").getTime();
        setInterval(function() {
            const now = new Date().getTime();
            const dist = countDownDate - now;
            document.getElementById("days").innerHTML = Math.floor(dist / (1000 * 60 * 60 * 24));
            document.getElementById("hours").innerHTML = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            document.getElementById("minutes").innerHTML = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
            document.getElementById("seconds").innerHTML = Math.floor((dist % (1000 * 60)) / 1000);
        }, 1000);
    </script>
</body>
</html>
