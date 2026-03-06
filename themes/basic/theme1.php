<?php
// basic/theme1.php
$guest_name = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Dewa Dhar & Gek Cantik</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #C5A059; /* Gold Bali */
            --bg-dark: #1A1A1A; /* Dark theme */
            --text-light: #F4F4F4;
            --text-muted: #A0A0A0;
            --font-serif: 'Playfair Display', serif;
            --font-body: 'Lora', serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-body);
            background-color: var(--bg-dark);
            color: var(--text-light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        .serif { font-family: var(--font-serif); }
        .gold-text { color: var(--primary); }
        .text-center { text-align: center; }

        /* COVER OVERLAY (SAMPUL) */
        #cover {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1544644181-1484b3fdfc62?q=80&w=1000&auto=format&fit=crop') center/cover;
            z-index: 9999;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; color: white; transition: transform 1s cubic-bezier(0.86, 0, 0.07, 1);
        }
        #cover.open { transform: translateY(-100%); }
        .cover-title { font-size: 3.5rem; margin-bottom: 10px; font-style: italic; }
        .cover-subtitle { font-size: 1.2rem; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 40px; }
        .guest-box {
            background: rgba(26, 26, 26, 0.7); border: 1px solid var(--primary);
            padding: 20px 40px; border-radius: 10px; backdrop-filter: blur(5px);
            margin-bottom: 30px;
        }
        .guest-name { font-size: 1.8rem; font-weight: bold; color: var(--primary); margin: 10px 0; font-family: var(--font-serif); }
        .btn-open {
            background: var(--primary); color: #fff; border: none; padding: 15px 35px;
            font-size: 1rem; border-radius: 30px; cursor: pointer; display: flex; align-items: center; gap: 10px;
            transition: all 0.3s; font-family: var(--font-body); letter-spacing: 1px;
        }
        .btn-open:hover { background: #E6B864; transform: scale(1.05); }

        /* HERO CONTENT */
        .hero {
            height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;
            background: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=1000&auto=format&fit=crop') center/cover fixed;
            position: relative;
        }
        .hero::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); }
        .hero-content { position: relative; z-index: 2; text-align: center; }
        .hero-title { font-size: 4rem; color: var(--primary); margin: 15px 0; font-style: italic; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .countdown { display: flex; gap: 15px; margin-top: 30px; justify-content: center; }
        .cd-box {
            background: rgba(255,255,255,0.1); backdrop-filter: blur(5px); border: 1px solid var(--primary);
            padding: 15px; border-radius: 8px; min-width: 80px; text-align: center;
        }
        .cd-num { font-size: 2rem; font-weight: bold; font-family: var(--font-serif); }
        .cd-text { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }

        /* SECTION GLOBAL */
        .section { padding: 80px 20px; text-align: center; max-width: 900px; margin: 0 auto; }
        .sec-title { font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; font-style: italic; }
        .divider { width: 100px; height: 1px; background: var(--primary); margin: 0 auto 30px; position: relative; }
        .divider::after {
            content: '❖'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            color: var(--primary); background: var(--bg-dark); padding: 0 10px; font-size: 1.2rem;
        }

        /* COUPLE SECTION */
        .couple-wrapper { display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 40px; }
        .person { flex: 1; min-width: 280px; }
        .person-img {
            width: 200px; height: 200px; border-radius: 50%; object-fit: cover;
            border: 4px solid var(--primary); margin-bottom: 20px;
        }
        .person-name { font-size: 2rem; color: var(--primary); font-family: var(--font-serif); margin-bottom: 10px; }

        /* EVENT SECTION */
        .event-box {
            background: #252525; border: 1px solid #333; padding: 40px; border-radius: 12px; margin-bottom: 30px;
        }
        .event-title { font-size: 1.8rem; color: var(--primary); font-family: var(--font-serif); margin-bottom: 15px; }
        .btn-map {
            display: inline-block; background: transparent; border: 1px solid var(--primary); color: var(--primary);
            padding: 10px 25px; border-radius: 20px; text-decoration: none; margin-top: 20px; transition: 0.3s;
        }
        .btn-map:hover { background: var(--primary); color: #000; }

        /* GALLERY & PROTOCOL */
        .gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-top: 30px; }
        .gallery img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; transition: 0.3s; }
        .gallery img:hover { transform: scale(1.05); }

        /* AUDIO BUTTON */
        .audio-btn {
            position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px;
            background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #1a1a1a; font-size: 1.2rem; cursor: pointer; z-index: 1000; box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            animation: spin 4s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .audio-btn.paused { animation: none; }

        @media (max-width: 768px) {
            .hero-title { font-size: 3rem; }
            .cover-title { font-size: 2.5rem; }
            .couple-wrapper { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY SAMPUL -->
    <div id="cover">
        <div class="serif cover-subtitle" data-aos="fade-down">PAWIWAHAN</div>
        <h1 class="serif cover-title" data-aos="zoom-in" data-aos-delay="200">Dewa Dhar & Gek Cantik</h1>
        
        <div class="guest-box" data-aos="fade-up" data-aos-delay="400">
            <p>Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <div class="guest-name"><?php echo $guest_name; ?></div>
            <p style="font-size: 0.8rem; color: var(--text-muted);">Mohon maaf jika ada kesalahan penulisan nama/gelar</p>
        </div>

        <button class="btn-open" onclick="bukaUndangan()" data-aos="zoom-in" data-aos-delay="600">
            <i class="fas fa-envelope-open-text"></i> Buka Undangan
        </button>
    </div>

    <!-- AUDIO PLAYER -->
    <audio id="bg-music" loop>
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
    </audio>
    <div class="audio-btn paused" id="audioCtrl" onclick="toggleAudio()" style="display: none;">
        <i class="fas fa-music"></i>
    </div>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <section class="hero">
            <div class="hero-content">
                <p class="serif" style="letter-spacing: 3px; text-transform: uppercase;">The Wedding Of</p>
                <h1 class="hero-title">Dewa Dhar & Gek Cantik</h1>
                <p class="serif" style="font-size: 1.2rem;">22 Februari 2026</p>
                
                <div class="countdown">
                    <div class="cd-box"><div class="cd-num" id="days">00</div><div class="cd-text">Hari</div></div>
                    <div class="cd-box"><div class="cd-num" id="hours">00</div><div class="cd-text">Jam</div></div>
                    <div class="cd-box"><div class="cd-num" id="minutes">00</div><div class="cd-text">Menit</div></div>
                    <div class="cd-box"><div class="cd-num" id="seconds">00</div><div class="cd-text">Detik</div></div>
                </div>
            </div>
        </section>

        <section class="section">
            <p data-aos="fade-up" style="font-size: 1.1rem; font-style: italic; color: var(--primary);">"Ihaiva stam ma vi yaustam, visvam ayur vyasnutam..."</p>
            <p data-aos="fade-up" data-aos-delay="200" style="color: var(--text-muted); margin-bottom: 20px;">(Rg Veda X.85.42)</p>
            <p data-aos="fade-up" data-aos-delay="300">Wahai pasangan suami-istri, semoga kalian tetap bersatu dan tidak pernah terpisahkan. Semoga kalian mencapai hidup penuh kebahagiaan, tinggal di rumah yang penuh kegembiraan bersama seluruh keturunanmu.</p>
        </section>

        <section class="section">
            <h2 class="sec-title">Om Swastyastu</h2>
            <div class="divider"></div>
            <p>Atas Asung Kertha Wara Nugraha Ida Sang Hyang Widhi Wasa/Tuhan Yang Maha Esa, kami bermaksud mengundang Bapak/Ibu/Saudara/i pada Acara Pawiwahan Putra-Putri kami:</p>
            
            <div class="couple-wrapper">
                <div class="person" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Groom">
                    <h3 class="person-name">Dewa Gede Dharmendra</h3>
                    <p>Putra Ketiga dari pasangan<br><strong>Dewa Ketut Alit & Desak Putu Aryanti</strong></p>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Br. Menak Tulikup - Gianyar</p>
                </div>
                
                <div class="person" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=400&auto=format&fit=crop" class="person-img" alt="Bride">
                    <h3 class="person-name">I Gusti Ayu Diah</h3>
                    <p>Putri Pertama dari pasangan<br><strong>I Gusti Nyoman Ngurah & Ni Gusti Putu Luwes</strong></p>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Br. Prajamukti - Gianyar</p>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title">Acara Kami</h2>
            <div class="divider"></div>
            
            <div class="event-box" data-aos="zoom-in">
                <h3 class="event-title">Resepsi Pawiwahan</h3>
                <p><strong>Rabu, 22 Februari 2026</strong></p>
                <p>15.00 WITA - Selesai</p>
                <p style="margin-top: 15px; color: var(--text-muted);">Bertempat di:<br>Jalan Raya Tulikup, Gianyar, Bali</p>
                <a href="https://goo.gl/maps" target="_blank" class="btn-map"><i class="fas fa-map-marker-alt"></i> Buka Google Maps</a>
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title">Galeri Momen</h2>
            <div class="divider"></div>
            <div class="gallery">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" data-aos-delay="100" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" data-aos-delay="200" alt="Gallery">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=400&auto=format&fit=crop" data-aos="fade-up" data-aos-delay="300" alt="Gallery">
            </div>
        </section>

        <section class="section">
            <h2 class="sec-title">Matur Suksma</h2>
            <div class="divider"></div>
            <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kedua mempelai.</p>
            <p style="margin-top: 20px; font-style: italic; color: var(--primary);">Om Shanti Shanti Shanti Om</p>
            <br><br>
            <h3 class="serif" style="font-size: 2rem;">Dewa Dhar & Gek Cantik</h3>
        </section>
        
        <div style="text-align: center; padding: 20px; font-size: 0.8rem; background: #111; color: #555;">
            Dibuat oleh Embun Visual
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });

        const audio = document.getElementById("bg-music");
        const audioCtrl = document.getElementById("audioCtrl");
        let isPlaying = false;

        function bukaUndangan() {
            document.getElementById("cover").classList.add("open");
            audioCtrl.style.display = "flex";
            playAudio();
            
            // Allow scroll
            document.body.style.overflowY = "auto";
        }

        function playAudio() {
            audio.play().then(() => {
                isPlaying = true;
                audioCtrl.classList.remove("paused");
            }).catch(e => console.log("Audio autoplay prevented"));
        }

        function toggleAudio() {
            if (isPlaying) {
                audio.pause();
                audioCtrl.classList.add("paused");
            } else {
                audio.play();
                audioCtrl.classList.remove("paused");
            }
            isPlaying = !isPlaying;
        }

        // Prevent scrolling when cover is active
        document.body.style.overflowY = "hidden";

        // Countdown Timer
        const countDownDate = new Date("Feb 22, 2026 15:00:00").getTime();
        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            if (distance < 0) {
                clearInterval(x);
                document.querySelector(".countdown").innerHTML = "ACARA SEDANG BERLANGSUNG";
            }
        }, 1000);
    </script>
</body>
</html>
