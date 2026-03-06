<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium E-Invitation | Romeo & Juliet</title>

    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-main: #FFFDFc; 
            --bg-card: #FFFFFF;
            --primary: #C05C47; /* Terracotta */
            --primary-dark: #8B3A2B;
            --primary-light: #F7EAE8;
            --text-dark: #2C2C2C;
            --text-muted: #888888;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-serif: 'Lora', serif;
            --shadow-sm: 0 4px 15px rgba(192, 92, 71, 0.05);
            --shadow-lg: 0 15px 40px rgba(192, 92, 71, 0.08);
            --radius-lg: 24px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--bg-main); color: var(--text-dark); overflow-x: hidden; }
        .serif { font-family: var(--font-serif); }

        /* Typography */
        h1, h2, h3, h4 { color: var(--primary-dark); font-weight: 500; }
        p { line-height: 1.7; }

        /* UI Elements */
        .btn-solid { 
            background: var(--primary); color: white; padding: 12px 30px; border: none; border-radius: 50px; 
            font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; 
            box-shadow: 0 8px 20px rgba(192, 92, 71, 0.25); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-solid:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 12px 25px rgba(192, 92, 71, 0.35); color: white; }
        
        .btn-outline { 
            background: transparent; color: var(--primary); border: 1.5px solid var(--primary); padding: 12px 30px; 
            border-radius: 50px; font-weight: 600; font-size: 0.95rem; text-decoration: none; 
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease; 
        }
        .btn-outline:hover { background: var(--primary-light); transform: translateY(-3px); }

        .card-elegant { background: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); padding: 40px 30px; position: relative; overflow: hidden; border: 1px solid rgba(192, 92, 71, 0.1); }

        /* Welcome Overlay */
        #welcome-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: var(--bg-main); 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: transform 1.2s cubic-bezier(0.86, 0, 0.07, 1);
            background-image: radial-gradient(circle at center, var(--bg-main) 60%, var(--primary-light) 200%);
        }
        #welcome-overlay.open { transform: translateY(-100vh); }
        .welcome-badge { font-size: 0.8rem; letter-spacing: 4px; text-transform: uppercase; color: var(--primary); margin-bottom: 20px; font-weight: 600; }
        .welcome-title { font-size: 4rem; color: var(--primary-dark); margin: 10px 0; }
        .welcome-to { color: var(--text-muted); margin: 30px 0 10px; font-size: 0.95rem; letter-spacing: 1px; }
        .guest-name { font-size: 1.4rem; font-weight: 600; color: var(--text-dark); padding: 10px 30px; background: var(--primary-light); border-radius: 50px; display: inline-block; margin-bottom: 40px; }

        /* Main Content */
        .section { padding: 80px 20px; max-width: 900px; margin: 0 auto; text-align: center; position: relative; z-index: 10; }
        .sec-title { font-size: 2.8rem; margin-bottom: 20px; position: relative; display: inline-block; }
        .sec-title::after { content: ''; display: block; width: 40px; height: 2px; background: var(--primary); margin: 15px auto 0; border-radius: 2px; }
        .sec-desc { color: var(--text-muted); max-width: 600px; margin: 0 auto 50px; font-size: 0.95rem; }

        /* Hero */
        .hero { 
            min-height: 100vh; display: flex; align-items: center; justify-content: center; position: relative;
            background: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        }
        .hero::before { content: ''; position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to bottom, rgba(255,253,252,0.6), rgba(255,253,252,1)); }
        
        .hero-content { position: relative; z-index: 2; text-align: center; padding: 40px; transition: all 1.5s ease; }
        .hero-hidden { opacity: 0; transform: translateY(30px) scale(0.95); }
        .hero-show { opacity: 1; transform: translateY(0) scale(1); }
        .hero-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; border: 1px solid var(--primary); color: var(--primary); font-size: 0.8rem; letter-spacing: 3px; margin-bottom: 20px; text-transform: uppercase; }
        .hero-title { font-size: 5rem; margin-bottom: 10px; line-height: 1.1; color: var(--primary-dark); }
        .hero-date { font-size: 1.2rem; letter-spacing: 5px; color: var(--text-dark); margin-top: 20px; font-weight: 300; }

        /* Couple */
        .couple-wrap { display: flex; flex-direction: column; gap: 40px; margin-top: 30px; }
        .couple-box { background: transparent; padding: 20px; }
        .img-frame { width: 200px; height: 260px; margin: 0 auto 25px; border-radius: 100px 100px 15px 15px; overflow: hidden; position: relative; box-shadow: var(--shadow-lg); border: 5px solid white; }
        .img-frame img { width: 100%; height: 100%; object-fit: cover; }
        .couple-name { font-size: 2.2rem; margin-bottom: 10px; }
        .couple-parents { color: var(--text-muted); font-size: 0.9rem; }

        /* Events */
        .event-grid { display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 10px; }
        .event-card { text-align: center; padding: 40px; }
        .event-icon { width: 60px; height: 60px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px; }
        .event-title { font-size: 2rem; margin-bottom: 15px; }
        .event-detail { margin-bottom: 25px; }
        .event-detail p { margin-bottom: 5px; }
        .event-time { font-weight: 600; color: var(--primary); }

        /* Gallery */
        .gallery-wrap { column-count: 2; column-gap: 15px; }
        .g-item { margin-bottom: 15px; break-inside: avoid; border-radius: 15px; overflow: hidden; box-shadow: var(--shadow-sm); cursor: pointer; position: relative; }
        .g-item img { width: 100%; display: block; transition: transform 0.5s ease; }
        .g-item:hover img { transform: scale(1.05); }

        /* Maps */
        .map-container { width: 100%; height: 350px; border-radius: 15px; overflow: hidden; border: 4px solid white; box-shadow: var(--shadow-sm); margin-bottom: 20px; }
        
        /* Gift & RSVP */
        .gift-box { display: flex; align-items: center; justify-content: space-between; padding: 20px; background: var(--primary-light); border-radius: 15px; margin-bottom: 15px; }
        .gift-info h4 { font-family: var(--font-sans); font-size: 1.1rem; color: var(--primary-dark); font-weight: 700; margin-bottom: 5px; }
        .gift-info p { margin: 0; color: var(--text-dark); font-size: 0.95rem; }
        
        .form-control { width: 100%; padding: 15px 20px; border-radius: 12px; border: 1px solid #E2E8F0; background: #F8FAFC; margin-bottom: 15px; font-family: inherit; font-size: 0.95rem; transition: all 0.3s; }
        .form-control:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px var(--primary-light); }

        /* Ticket */
        .ticket-wrapper { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 40px; border-radius: 20px; color: white; position: relative; box-shadow: var(--shadow-lg); overflow: hidden; }
        .ticket-wrapper::before, .ticket-wrapper::after { content:''; position:absolute; width: 40px; height: 40px; background: var(--bg-main); border-radius: 50%; top: 50%; transform: translateY(-50%); }
        .ticket-wrapper::before { left: -20px; }
        .ticket-wrapper::after { right: -20px; }

        /* Float Actions */
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: white; color: var(--primary); width: 50px; height: 50px; border-radius: 50%; border: none; font-size: 1.2rem; cursor: pointer; z-index: 100; box-shadow: var(--shadow-sm); transition: 0.3s; display: flex; align-items: center; justify-content: center; }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .floating-actions { position: fixed; bottom: 30px; left: 30px; z-index: 99; display: flex; gap: 10px; transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform: translateY(150px); opacity: 0; pointer-events: none; }
        .floating-actions.show { transform: translateY(0); opacity: 1; pointer-events: auto; }
        .btn-float { padding: 12px 22px; border-radius: 30px; font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; box-shadow: var(--shadow-sm); text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-float-rsvp { background: var(--primary); color: white; }
        .btn-float-map { background: white; color: var(--primary); border: 1px solid var(--primary-light); }
        .btn-float:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        @media (min-width: 768px) {
            .couple-wrap { flex-direction: row; justify-content: center; align-items: center; gap: 60px; }
            .event-grid { grid-template-columns: 1fr 1fr; }
            .gallery-wrap { column-count: 3; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY -->
    <div id="welcome-overlay">
        <div data-aos="zoom-in" data-aos-duration="1000">
            <p class="welcome-badge">Wedding Invitation</p>
            <h1 class="welcome-title serif">Romeo & Juliet</h1>
            <p class="welcome-to">Kepada Yth. Bapak/Ibu/Saudara/i</p>
            <div class="guest-name">
                <?php 
                    if(isset($_GET['to']) && !empty($_GET['to'])) {
                        echo htmlspecialchars($_GET['to']); 
                    } else {
                        echo "Tamu Kehormatan";
                    }
                ?>
            </div>
            <br>
            <button class="btn-solid" style="padding: 15px 40px; font-size: 1.1rem; border-radius: 50px;" onclick="bukaUndangan()"><i class="fas fa-envelope-open-heart"></i> Buka Undangan</button>
        </div>
    </div>

    <audio id="bgMusic" loop>
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3" type="audio/mpeg">
    </audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero" id="hero-sec">
            <div id="heroText" class="hero-content hero-hidden">
                <span class="hero-badge">We Are Getting Married</span>
                <h1 class="hero-title serif">Romeo <br>& Juliet</h1>
                <p class="hero-date">14 • 04 • 2026</p>
            </div>
        </section>

        <!-- KUTIPAN -->
        <section class="section">
            <div class="card-elegant" data-aos="fade-up">
                <i class="fas fa-quote-right" style="font-size: 2.5rem; color: var(--primary-light); margin-bottom: 20px;"></i>
                <p class="serif" style="font-size: 1.25rem; font-style: italic; color: var(--text-dark); line-height: 1.8;">
                    "Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya."
                </p>
                <p style="margin-top: 20px; font-size: 0.85rem; letter-spacing: 2px; color: var(--primary); font-weight: 600;">(QS. AR-RUM: 21)</p>
            </div>
        </section>

        <!-- MEMPELAI -->
        <section class="section">
            <h2 class="sec-title serif" data-aos="fade-up">Pasangan Mempelai</h2>
            <div class="couple-wrap">
                <div class="couple-box" data-aos="fade-up" data-aos-delay="100">
                    <div class="img-frame">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80">
                    </div>
                    <h3 class="couple-name serif">Romeo Montague</h3>
                    <p class="couple-parents">Putra Pertama dari<br>Bpk. Montague & Ibu Lady Montague</p>
                </div>
                
                <div style="font-size: 4rem; color: var(--primary-light); font-family: 'Lora', serif; line-height: 1;" data-aos="zoom-in">&</div>
                
                <div class="couple-box" data-aos="fade-up" data-aos-delay="200">
                    <div class="img-frame">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80">
                    </div>
                    <h3 class="couple-name serif">Juliet Capulet</h3>
                    <p class="couple-parents">Putri Bungsu dari<br>Bpk. Capulet & Ibu Lady Capulet</p>
                </div>
            </div>
        </section>

        <!-- ACARA -->
        <section class="section">
            <h2 class="sec-title serif" data-aos="fade-up">Rangkaian Acara</h2>
            <p class="sec-desc" data-aos="fade-up">Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk hadir pada acara pernikahan kami.</p>
            
            <div class="event-grid">
                <div class="card-elegant event-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="event-icon"><i class="fas fa-ring"></i></div>
                    <h3 class="event-title serif">Akad Nikah</h3>
                    <div class="event-detail">
                        <p style="font-size: 1.1rem; font-weight: 500;">Sabtu, 14 April 2026</p>
                        <p class="event-time">08.00 WIB - Selesai</p>
                    </div>
                    <p style="color: var(--text-muted); margin-bottom: 25px; line-height: 1.5;"><strong>Masjid Raya Al-Bina</strong><br>Jl. Pintu Satu Senayan, Gelora, Jakarta Pusat</p>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="https://maps.google.com" target="_blank" class="btn-solid"><i class="fas fa-map-marker-alt"></i> Lihat Lokasi</a>
                        <a href="https://calendar.google.com" target="_blank" class="btn-outline"><i class="far fa-calendar-alt"></i> Simpan Tanggal</a>
                    </div>
                </div>

                <div class="card-elegant event-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="event-icon"><i class="fas fa-glass-cheers"></i></div>
                    <h3 class="event-title serif">Resepsi</h3>
                    <div class="event-detail">
                        <p style="font-size: 1.1rem; font-weight: 500;">Sabtu, 14 April 2026</p>
                        <p class="event-time">11.00 - 14.00 WIB</p>
                    </div>
                    <p style="color: var(--text-muted); margin-bottom: 25px; line-height: 1.5;"><strong>Grand Ballroom Hotel Mulia</strong><br>Jl. Asia Afrika, Senayan, Jakarta Pusat</p>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="https://maps.google.com" target="_blank" class="btn-solid"><i class="fas fa-map-marker-alt"></i> Lihat Lokasi</a>
                        <a href="https://calendar.google.com" target="_blank" class="btn-outline"><i class="far fa-calendar-alt"></i> Simpan Tanggal</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAPS INTEGRATION -->
        <section class="section" style="padding-top: 20px;">
            <div class="card-elegant" data-aos="fade-up" style="padding: 30px;">
                <h3 class="serif" style="font-size: 1.8rem; margin-bottom: 20px; color: var(--primary-dark);">Denah Lokasi Venue</h3>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.051416450702!2d106.7918511!3d-6.2235282!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1406f52e25f%3A0xe7975e5da465ec8d!2sHotel%20Mulia%20Senayan%2C%20Jakarta!5e0!3m2!1sen!2sid!4v1709772000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <a href="https://maps.google.com" target="_blank" class="btn-solid"><i class="fas fa-location-arrow"></i> Buka via Google Maps</a>
            </div>
        </section>

        <!-- GALERI -->
        <section class="section">
            <h2 class="sec-title serif" data-aos="fade-up">Galeri Momen</h2>
            <div class="gallery-wrap">
                <div class="g-item" data-aos="zoom-in"><img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80"></div>
                <div class="g-item" data-aos="zoom-in" data-aos-delay="100"><img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&q=80"></div>
                <div class="g-item" data-aos="zoom-in" data-aos-delay="200"><img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?auto=format&fit=crop&w=600&q=80"></div>
                <div class="g-item" data-aos="zoom-in" data-aos-delay="100"><img src="https://images.unsplash.com/photo-1544465544-1b71aee9dfa3?auto=format&fit=crop&w=600&q=80"></div>
                <div class="g-item" data-aos="zoom-in" data-aos-delay="200"><img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=600&q=80"></div>
            </div>
        </section>

        <!-- E-TICKET -->
        <section class="section" data-aos="fade-up">
            <h2 class="sec-title serif">Akses Masuk (VIP Pass)</h2>
            <p class="sec-desc">Dapatkan QR Code E-Ticket untuk kemudahan akses masuk ke area resepsi tanpa perlu mengisi buku tamu manual.</p>
            
            <div class="ticket-wrapper">
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="flex: 1;">
                        <h3 class="serif" style="font-size:1.8rem; margin-bottom: 20px; color: white;"><i class="fas fa-ticket-alt"></i> Generate Pass</h3>
                        <form id="qrForm" onsubmit="generateQR(event)">
                            <label style="font-size:0.9rem; margin-bottom:5px; display:block; color: rgba(255,255,255,0.9);">Nama Lengkap:</label>
                            <input type="text" id="tamuNama" class="form-control" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: white;" placeholder="Msl: Budi Santoso" required>
                            
                            <label style="font-size:0.9rem; margin-bottom:5px; display:block; color: rgba(255,255,255,0.9);">Jumlah Kehadiran:</label>
                            <input type="number" id="tamuJumlah" class="form-control" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: white;" placeholder="Msl: 2" min="1" required>
                            
                            <button type="submit" class="btn-solid" style="background: white; color: var(--primary); width: 100%; margin-top: 10px;">Dapatkan QR Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- TANDA KASIH -->
        <section class="section" data-aos="fade-up">
            <h2 class="sec-title serif">Tanda Kasih</h2>
            <p class="sec-desc">Tanpa mengurangi rasa hormat, bagi Anda yang ingin memberikan tanda kasih untuk kami, dapat melalui nomor rekening berikut:</p>
            
            <div class="card-elegant" style="max-width: 600px; margin: 0 auto;">
                <div class="gift-box">
                    <div class="gift-info">
                        <h4>BCA</h4>
                        <p style="font-size: 1.3rem; font-weight: 600; letter-spacing: 2px; color: var(--primary);">123 456 7890</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem;">a.n Romeo Montague</p>
                    </div>
                    <button onclick="salinRekening('1234567890')" class="btn-outline" style="padding: 10px 20px; font-size: 0.8rem;"><i class="fas fa-copy"></i> Salin</button>
                </div>

                <div class="gift-box">
                    <div class="gift-info">
                        <h4>MANDIRI</h4>
                        <p style="font-size: 1.3rem; font-weight: 600; letter-spacing: 2px; color: var(--primary);">098 765 4321</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem;">a.n Juliet Capulet</p>
                    </div>
                    <button onclick="salinRekening('0987654321')" class="btn-outline" style="padding: 10px 20px; font-size: 0.8rem;"><i class="fas fa-copy"></i> Salin</button>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="section" id="rsvp-sec" data-aos="fade-up">
            <h2 class="sec-title serif">Konfirmasi Kehadiran</h2>
            <p class="sec-desc">Kirimkan konfirmasi kehadiran beserta doa dan harapan terbaik Anda untuk kami.</p>
            
            <div class="card-elegant" style="max-width: 600px; margin: 0 auto; text-align: left;">
                <form id="rsvpForm" onsubmit="kirimRSVP(event)">
                    <label style="font-size:0.9rem; margin-bottom:5px; display:block; font-weight:600;">Nama Anda:</label>
                    <input type="text" id="rsvpNama" class="form-control" placeholder="Ketik nama Anda" required>

                    <label style="font-size:0.9rem; margin-bottom:5px; display:block; font-weight:600;">Kehadiran:</label>
                    <select id="rsvpHadir" class="form-control" required>
                        <option value="" disabled selected>Apakah Anda akan hadir?</option>
                        <option value="Hadir">✔ Ya, Saya Akan Hadir</option>
                        <option value="Tidak Hadir">✖ Maaf, Saya Tidak Bisa Hadir</option>
                    </select>

                    <label style="font-size:0.9rem; margin-bottom:5px; display:block; font-weight:600;">Pesan & Doa:</label>
                    <textarea id="rsvpPesan" class="form-control" rows="4" placeholder="Tuliskan ucapan selamat dan doa untuk mempelai..." required></textarea>

                    <button type="submit" class="btn-solid" style="width: 100%;"><i class="fab fa-whatsapp"></i> Kirim via WhatsApp</button>
                </form>
            </div>
        </section>

        <!-- FOOTER -->
        <footer style="text-align: center; padding: 60px 20px 80px; background: white; margin-top: 50px; border-top: 1px solid rgba(192, 92, 71, 0.1);">
            <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom:20px;">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.</p>
            <h2 class="serif" style="color: var(--primary); font-size: 2.5rem; margin-bottom: 20px;">Romeo & Juliet</h2>
            <p style="font-size: 0.85rem; color: #a0aec0; letter-spacing: 1px;">PREMIUM TEMPLATE BY EMBUN VISUAL</p>
        </footer>

    </div>

    <!-- FLOATING ACTIONS -->
    <div id="floatingActions" class="floating-actions">
        <a href="#rsvp-sec" class="btn-float btn-float-rsvp"><i class="fas fa-envelope-open-text"></i> RSVP</a>
        <a href="https://maps.google.com" target="_blank" class="btn-float btn-float-map"><i class="fas fa-map-marker-alt"></i> Petunjuk Arah</a>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, duration: 1000, offset: 50 });

        const audio = document.getElementById("bgMusic");
        const musicBtn = document.getElementById("musicBtn");
        let isPlaying = false;

        document.body.style.overflow = "hidden"; 

        function bukaUndangan() {
            document.getElementById('welcome-overlay').classList.add('open');
            document.body.style.overflow = "auto"; 
            
            setTimeout(() => {
                document.getElementById('heroText').classList.remove('hero-hidden');
                document.getElementById('heroText').classList.add('hero-show');
            }, 500);

            audio.play().catch(e => console.log(e));
            isPlaying = true;
            musicBtn.classList.add("spin");
        }

        function toggleMusic() {
            if (isPlaying) { audio.pause(); musicBtn.classList.remove("spin"); } 
            else { audio.play(); musicBtn.classList.add("spin"); }
            isPlaying = !isPlaying;
        }

        // Float Actions Logic
        window.addEventListener('scroll', function() {
            const heroSection = document.getElementById('hero-sec');
            const floatingContainer = document.getElementById('floatingActions');
            if (window.scrollY > (heroSection.offsetHeight - 100)) {
                floatingContainer.classList.add('show');
            } else {
                floatingContainer.classList.remove('show');
            }
        });

        function salinRekening(rek) {
            navigator.clipboard.writeText(rek);
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Nomor Rekening Disalin!', showConfirmButton: false, timer: 2000 });
        }

        function generateQR(e) {
            e.preventDefault(); 
            let nama = document.getElementById('tamuNama').value;
            let pax = document.getElementById('tamuJumlah').value;
            let qrData = `Tamu: ${nama} | Jumlah: ${pax} Orang | VIP Access`;
            let qrImageURL = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrData)}&color=C05C47`;

            Swal.fire({
                title: 'VIP Pass Diterbitkan!',
                html: `
                    <p style="font-size:0.95rem; color:#666; margin-bottom:15px;">Simpan barcode ini dan tunjukkan di meja registrasi.</p>
                    <img src="${qrImageURL}" style="border-radius:15px; border:3px solid #C05C47; padding:15px; box-shadow: 0 5px 15px rgba(192,92,71,0.2);" alt="QR Code">
                    <h3 style="margin-top:20px; color:#C05C47; font-family: 'Lora', serif; font-size: 1.8rem;">${nama}</h3>
                    <p style="font-weight:600; font-size: 1.1rem; color: #444;">${pax} Orang</p>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#C05C47',
                customClass: { popup: 'card-elegant' }
            });

            document.getElementById('qrForm').reset();
        }

        function kirimRSVP(e) {
            e.preventDefault();
            let nama = document.getElementById('rsvpNama').value;
            let hadir = document.getElementById('rsvpHadir').value;
            let pesan = document.getElementById('rsvpPesan').value;
            let noMempelai = "6281234567890"; 
            let textWA = `Halo, saya *${nama}*.%0A%0AKonfirmasi Kehadiran: *${hadir}*.%0A%0A*Pesan & Doa:*%0A"${pesan}"`;

            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Konfirmasi Terkirim!',
                text: 'Terima kasih atas doa dan konfirmasi kehadiran Anda.',
                confirmButtonColor: '#C05C47'
            });

            document.getElementById('rsvpForm').reset();
        }
    </script>
</body>
</html>