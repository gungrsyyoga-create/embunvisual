<?php
// basic/theme3.php
$guest_name = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Istimewa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan | Theme 3</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #D4AF37; /* Classic Gold */
            --bg-body: #FAFAFA;
            --text-dark: #333333;
            --text-muted: #888888;
            --font-script: 'Great Vibes', cursive;
            --font-body: 'Lato', sans-serif;
            --border-radius: 8px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background-color: var(--bg-body); color: var(--text-dark); overflow-x: hidden; line-height: 1.8; }
        .script { font-family: var(--font-script); }
        .text-center { text-align: center; }

        /* COVER */
        #cover {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            background: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.95)), url('https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=1000&auto=format&fit=crop') center/cover;
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; transition: opacity 1.5s ease-in-out, visibility 1.5s;
        }
        #cover.open { opacity: 0; visibility: hidden; }
        .cover-title { font-size: 5rem; color: var(--text-dark); margin: 30px 0; }
        .guest-box { border-top: 2px solid var(--primary); border-bottom: 2px solid var(--primary); padding: 30px 50px; margin-bottom: 40px; }
        .guest-name { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 10px 0; }
        .btn-open { background: var(--text-dark); color: #fff; border: 1px solid var(--text-dark); padding: 12px 35px; font-size: 1rem; border-radius: 30px; cursor: pointer; transition: 0.3s; }
        .btn-open:hover { background: transparent; color: var(--text-dark); }

        /* HERO */
        .hero { height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; background: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1000&auto=format&fit=crop') center/cover fixed; position: relative; }
        .hero::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(250,250,250,0.7); }
        .hero-content { position: relative; z-index: 2; text-align: center; padding: 40px; }
        .hero-title { font-size: 6rem; color: var(--text-dark); margin: 20px 0 0; line-height: 1; }
        .hero-date { font-size: 1.2rem; font-weight: 300; letter-spacing: 5px; color: var(--text-dark); margin-top: 30px; }
        .countdown { display: flex; gap: 30px; margin-top: 40px; justify-content: center; }
        .cd-box { border: 1px solid var(--primary); padding: 15px; border-radius: 5px; min-width: 90px; background: rgba(255,255,255,0.7); backdrop-filter: blur(5px); }
        .cd-num { font-size: 2rem; font-weight: 700; color: var(--text-dark); }
        .cd-text { font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); }

        /* SECTIONS */
        .section { padding: 100px 20px; text-align: center; max-width: 850px; margin: 0 auto; }
        .sec-title { font-size: 4rem; color: var(--text-dark); margin-bottom: 10px; }
        .sec-subtitle { font-weight: 300; letter-spacing: 2px; color: var(--text-muted); text-transform: uppercase; font-size: 0.9rem; margin-bottom: 50px; }

        /* COUPLE */
        .couple-wrapper { display: flex; flex-wrap: wrap; justify-content: space-around; gap: 40px; margin-top: 40px; }
        .person { max-width: 300px; }
        .person-img { width: 100%; height: 350px; object-fit: cover; border-radius: var(--border-radius); box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 25px; }
        .person-name { font-size: 2.5rem; color: var(--text-dark); margin-bottom: 15px; line-height: 1; }
        .person-parents { font-size: 0.9rem; color: var(--text-muted); }
        .and-symbol { font-size: 5rem; color: var(--primary); align-self: center; }

        /* EVENT */
        .event-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 50px; }
        .event-card { background: #fff; padding: 40px; border-radius: var(--border-radius); border: 1px solid #EAEAEA; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
        .event-title { font-size: 2rem; color: var(--text-dark); margin-bottom: 20px; }
        .btn-map { display: inline-block; border: 1px solid var(--text-dark); color: var(--text-dark); padding: 10px 25px; border-radius: 30px; text-decoration: none; margin-top: 25px; font-size: 0.85rem; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; }
        .btn-map:hover { background: var(--text-dark); color: #fff; }

        /* AUDIO */
        .audio-btn { position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 1000; box-shadow: 0 4px 15px rgba(212,175,55,0.4); }

        @media (max-width: 768px) {
            .hero-title { font-size: 4.5rem; }
            .cover-title { font-size: 3.5rem; }
            .and-symbol { display: none; }
            .countdown { gap: 10px; }
            .cd-box { min-width: 70px; padding: 10px; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY SAMPUL -->
    <div id="cover">
        <p style="letter-spacing: 4px; text-transform: uppercase; font-size: 0.9rem; color: var(--text-muted);" data-aos="fade-down">The Wedding Of</p>
        <h1 class="script cover-title" data-aos="zoom-in" data-aos-delay="200">Aditya & Bunga</h1>
        
        <div class="guest-box" data-aos="fade-up" data-aos-delay="400">
            <p style="font-size: 0.9rem; color: var(--text-muted);">Kepada Yth:</p>
            <div class="guest-name"><?php echo $guest_name; ?></div>
        </div>

        <button class="btn-open" onclick="bukaUndangan()" data-aos="fade-up" data-aos-delay="600">Buka Undangan</button>
    </div>

    <!-- AUDIO PLAYER -->
    <audio id="bg-music" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3" type="audio/mpeg"></audio>
    <div class="audio-btn" id="audioCtrl" onclick="toggleAudio()" style="display: none;"><i class="fas fa-music"></i></div>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <section class="hero">
            <div class="hero-content">
                <i class="fas fa-ring" style="font-size: 2rem; color: var(--primary); margin-bottom: 20px;"></i>
                <h1 class="hero-title script">Aditya & Bunga</h1>
                <p class="hero-date">15 JUNI 2026</p>
                
                <div class="countdown">
                    <div class="cd-box"><div class="cd-num" id="days">00</div><div class="cd-text">Hari</div></div>
                    <div class="cd-box"><div class="cd-num" id="hours">00</div><div class="cd-text">Jam</div></div>
                    <div class="cd-box"><div class="cd-num" id="minutes">00</div><div class="cd-text">Min</div></div>
                    <div class="cd-box"><div class="cd-num" id="seconds">00</div><div class="cd-text">Sec</div></div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title script" data-aos="fade-up">Sang Mempelai</h2>
            <p class="sec-subtitle" data-aos="fade-up">The Groom and Bride</p>
            
            <div class="couple-wrapper">
                <div class="person" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Groom">
                    <h3 class="person-name script">Aditya Pratama</h3>
                    <p class="person-parents">Putra Kedua dari<br>Bpk. Pratama & Ibu Pratama</p>
                </div>
                
                <div class="and-symbol script" data-aos="zoom-in">&</div>
                
                <div class="person" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Bride">
                    <h3 class="person-name script">Bunga Lestari</h3>
                    <p class="person-parents">Putri Pertama dari<br>Bpk. Lestari & Ibu Lestari</p>
                </div>
            </div>
        </section>

        <section class="section" style="background-color: #fff;">
            <h2 class="sec-title script" data-aos="fade-up">Waktu & Tempat</h2>
            <p class="sec-subtitle" data-aos="fade-up">Event Details</p>
            
            <div class="event-grid">
                <div class="event-card" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="event-title script">Pemberkatan</h3>
                    <p><strong>Minggu, 15 Juni 2026</strong></p>
                    <p>Pukul 09.00 - 11.00 WIB</p>
                    <hr style="border: 0; height: 1px; background: #EAEAEA; margin: 20px 0;">
                    <p><strong>Gereja Katedral Jakarta</strong><br><span style="color: var(--text-muted); font-size: 0.9rem;">Jl. Katedral No.7B, Jakarta Pusat</span></p>
                    <a href="#" class="btn-map">Peta Lokasi</a>
                </div>
                
                <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="event-title script">Resepsi</h3>
                    <p><strong>Minggu, 15 Juni 2026</strong></p>
                    <p>Pukul 19.00 - Selesai</p>
                    <hr style="border: 0; height: 1px; background: #EAEAEA; margin: 20px 0;">
                    <p><strong>Hotel Indonesia Kempinski</strong><br><span style="color: var(--text-muted); font-size: 0.9rem;">Jl. M.H. Thamrin No.1, Jakarta Pusat</span></p>
                    <a href="#" class="btn-map">Peta Lokasi</a>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title script">Thank You</h2>
            <p style="color: var(--text-muted);">Merupakan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.</p>
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
            audio.play().then(() => {
                isPlaying = true;
            });
            document.body.style.overflowY = "auto";
            
            // Remove cover from DOM after animation
            setTimeout(() => { document.getElementById("cover").style.display = 'none'; }, 1500);
        }

        function toggleAudio() {
            if (isPlaying) { audio.pause(); audioCtrl.innerHTML = '<i class="fas fa-play"></i>'; } 
            else { audio.play(); audioCtrl.innerHTML = '<i class="fas fa-music"></i>'; }
            isPlaying = !isPlaying;
        }

        const countDownDate = new Date("Jun 15, 2026 09:00:00").getTime();
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
