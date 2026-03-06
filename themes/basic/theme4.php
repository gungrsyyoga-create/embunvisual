<?php
// basic/theme4.php
$guest_name = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan | Theme 4</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600&family=Oswald:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #8C5A35; /* Rustic Brown */
            --bg-body: #F7EFE5; /* Cream */
            --text-dark: #4A3B2C;
            --text-muted: #9E8973;
            --font-script: 'Dancing Script', cursive;
            --font-body: 'Oswald', sans-serif;
            --border-radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background-color: var(--bg-body); color: var(--text-dark); overflow-x: hidden; line-height: 1.6; }
        .script { font-family: var(--font-script); }
        .text-center { text-align: center; }

        /* COVER */
        #cover {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            background: linear-gradient(rgba(247,239,229,0.85), rgba(247,239,229,0.95)), url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=1000&auto=format&fit=crop') center/cover;
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; transition: top 1.5s cubic-bezier(0.77, 0, 0.175, 1);
        }
        #cover.open { top: -100vh; }
        .cover-title { font-size: 5rem; color: var(--primary); margin: 20px 0; font-weight: 600; }
        .guest-box { background: rgba(255,255,255,0.6); padding: 25px 40px; border-radius: var(--border-radius); border: 2px dashed var(--primary); margin-bottom: 30px; box-shadow: 0 5px 15px rgba(140,90,53,0.1); }
        .guest-name { font-size: 1.8rem; font-weight: 500; color: var(--text-dark); margin: 10px 0; text-transform: uppercase; letter-spacing: 2px; }
        .btn-open { background: var(--primary); color: #fff; border: none; padding: 12px 35px; font-size: 1rem; border-radius: 5px; cursor: pointer; transition: 0.3s; font-family: var(--font-body); text-transform: uppercase; letter-spacing: 2px; }
        .btn-open:hover { background: var(--text-dark); transform: translateY(-3px); }

        /* HERO */
        .hero { height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; background: url('https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=1000&auto=format&fit=crop') center/cover fixed; position: relative; }
        .hero::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(74,59,44,0.6); }
        .hero-content { position: relative; z-index: 2; text-align: center; border: 4px solid #fff; padding: 60px 40px; color: #fff; max-width: 600px; }
        .hero-title { font-size: 6rem; margin: 10px 0; line-height: 1; }
        .hero-date { font-size: 1.2rem; font-weight: 400; letter-spacing: 4px; margin-top: 20px; }
        
        /* SECTIONS */
        .section { padding: 90px 20px; text-align: center; max-width: 900px; margin: 0 auto; }
        .sec-title { font-size: 4rem; color: var(--primary); margin-bottom: 15px; }
        .sec-subtitle { font-weight: 300; letter-spacing: 3px; color: var(--text-dark); text-transform: uppercase; font-size: 1rem; margin-bottom: 50px; }

        /* COUPLE */
        .couple-wrapper { display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; margin-top: 40px; }
        .person { background: #fff; padding: 20px; border-radius: var(--border-radius); box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 320px; }
        .person-img { width: 100%; height: 300px; object-fit: cover; border-radius: calc(var(--border-radius) - 5px); margin-bottom: 20px; border: 2px solid var(--bg-body); }
        .person-name { font-size: 2.8rem; color: var(--primary); margin-bottom: 5px; }

        /* EVENT */
        .event-wrapper { background: url('https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=1000&auto=format&fit=crop') center/cover fixed; position: relative; padding: 100px 20px; color: white; text-align: center; }
        .event-wrapper::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(140,90,53,0.85); }
        .event-content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; }
        .event-card { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); padding: 40px; border-radius: var(--border-radius); backdrop-filter: blur(5px); flex: 1; min-width: 300px; }
        .btn-map { display: inline-block; background: #fff; color: var(--primary); padding: 12px 30px; border-radius: 5px; text-decoration: none; margin-top: 20px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .btn-map:hover { background: var(--text-dark); color: #fff; }

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 40px; }
        .gallery-grid img { width: 100%; height: 250px; object-fit: cover; border-radius: 5px; cursor: pointer; transition: 0.3s; filter: sepia(30%); }
        .gallery-grid img:hover { filter: sepia(0%); transform: scale(1.02); }

        /* AUDIO */
        .audio-btn { position: fixed; bottom: 30px; left: 30px; width: 50px; height: 50px; background: rgba(255,255,255,0.8); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 1000; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px solid var(--primary); }

        @media (max-width: 768px) {
            .hero-title { font-size: 4rem; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
            .cover-title { font-size: 4rem; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY SAMPUL -->
    <div id="cover">
        <p style="letter-spacing: 5px; text-transform: uppercase; font-size: 1rem; color: var(--text-muted);" data-aos="fade-down">- The Wedding Of -</p>
        <h1 class="script cover-title" data-aos="zoom-in" data-aos-delay="200">Liam & Emma</h1>
        
        <div class="guest-box" data-aos="fade-up" data-aos-delay="400">
            <p style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Kepada Yth:</p>
            <div class="guest-name"><?php echo $guest_name; ?></div>
            <p style="font-size: 0.8rem; color: var(--text-muted);">Mohon maaf jika ada kesalahan penulisan gelar</p>
        </div>

        <button class="btn-open" onclick="bukaUndangan()" data-aos="fade-up" data-aos-delay="600">Buka Undangan</button>
    </div>

    <!-- AUDIO PLAYER -->
    <audio id="bg-music" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3" type="audio/mpeg"></audio>
    <div class="audio-btn" id="audioCtrl" onclick="toggleAudio()" style="display: none;"><i class="fas fa-pause"></i></div>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <section class="hero">
            <div class="hero-content">
                <p style="letter-spacing: 4px; text-transform: uppercase; font-size: 1.1rem; margin-bottom: 20px;">Save The Date</p>
                <h1 class="hero-title script">Liam & Emma</h1>
                <p class="hero-date">10 . 10 . 2026</p>
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title script" data-aos="fade-up">Sang Mempelai</h2>
            <p class="sec-subtitle" data-aos="fade-up">The Groom & Bride</p>
            
            <div class="couple-wrapper">
                <div class="person" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Groom">
                    <h3 class="person-name script">Liam Hemsworth</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted);">Putra dari Keluarga Bpk. Hemsworth</p>
                    <a href="#" style="color: var(--primary); font-size: 1.2rem; margin-top: 10px; display: inline-block;"><i class="fab fa-instagram"></i></a>
                </div>
                
                <div class="person" data-aos="fade-up" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Bride">
                    <h3 class="person-name script">Emma Watson</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted);">Putri dari Keluarga Bpk. Watson</p>
                    <a href="#" style="color: var(--primary); font-size: 1.2rem; margin-top: 10px; display: inline-block;"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </section>

        <div class="event-wrapper">
            <h2 class="script" style="font-size: 4.5rem; margin-bottom: 10px; position: relative; z-index: 2;" data-aos="fade-down">Waktu & Tempat</h2>
            <p style="letter-spacing: 4px; text-transform: uppercase; margin-bottom: 50px; position: relative; z-index: 2;" data-aos="fade-down">Event Detail</p>
            <div class="event-content">
                <div class="event-card" data-aos="zoom-in" data-aos-delay="100">
                    <h3 class="script" style="font-size: 3rem; margin-bottom: 15px; color: #fff;">Akad Nikah</h3>
                    <p style="font-size: 1.2rem; margin-bottom: 10px;">Sabtu, 10 Oktober 2026</p>
                    <p style="margin-bottom: 20px; font-weight: 300;">08.00 - 10.00 WIB</p>
                    <p><strong>Villa Rustic Bali</strong><br><span style="font-weight: 300; font-size: 0.9rem;">Ubud, Bali</span></p>
                    <a href="#" class="btn-map">Google Maps</a>
                </div>
                
                <div class="event-card" data-aos="zoom-in" data-aos-delay="300">
                    <h3 class="script" style="font-size: 3rem; margin-bottom: 15px; color: #fff;">Resepsi</h3>
                    <p style="font-size: 1.2rem; margin-bottom: 10px;">Sabtu, 10 Oktober 2026</p>
                    <p style="margin-bottom: 20px; font-weight: 300;">18.00 - Selesai</p>
                    <p><strong>Villa Rustic Bali</strong><br><span style="font-weight: 300; font-size: 0.9rem;">Ubud, Bali</span></p>
                    <a href="#" class="btn-map">Google Maps</a>
                </div>
            </div>
        </div>

        <section class="section">
            <h2 class="sec-title script">Gallery</h2>
            <p class="sec-subtitle">Our Sweet Memories</p>
            <div class="gallery-grid">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
            </div>
        </section>

        <section class="section" style="padding-bottom: 50px;">
            <p style="font-style: italic; color: var(--text-muted); font-size: 1.2rem;">"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu istri-istri dari jenismu sendiri..."</p>
            <h2 class="script" style="font-size: 4rem; color: var(--primary); margin-top: 40px;">Liam & Emma</h2>
        </section>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });
        document.body.style.overflowY = "hidden";

        const audio = document.getElementById("bg-music");
        const audioCtrl = document.getElementById("audioCtrl");
        let isPlaying = false;

        function bukaUndangan() {
            document.getElementById("cover").classList.add("open");
            audioCtrl.style.display = "flex";
            audio.play().then(() => { isPlaying = true; });
            document.body.style.overflowY = "auto";
        }

        function toggleAudio() {
            if (isPlaying) { audio.pause(); audioCtrl.innerHTML = '<i class="fas fa-play"></i>'; } 
            else { audio.play(); audioCtrl.innerHTML = '<i class="fas fa-pause"></i>'; }
            isPlaying = !isPlaying;
        }
    </script>
</body>
</html>
