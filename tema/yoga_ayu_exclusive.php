<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of Yoga & Ayu | Exclusive</title>

    <!-- Google Fonts Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-deep: #0a0a0a;
            --gold-primary: #D4AF37;
            --gold-light: #F9E5C9;
            --text-main: #f4f4f4;
            --font-serif: 'Playfair Display', serif;
            --font-script: 'Pinyon Script', cursive;
        }

        body { 
            margin: 0; padding: 0; background: var(--bg-deep); color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; 
        }

        .hero {
            height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1920&q=80') center/cover;
            text-align: center;
        }

        .hero h1 { font-family: var(--font-script); font-size: 5rem; color: var(--gold-light); margin: 10px 0; }
        .hero p { letter-spacing: 5px; text-transform: uppercase; color: var(--gold-primary); font-size: 0.9rem; }

        .section { padding: 80px 20px; max-width: 800px; margin: 0 auto; text-align: center; }
        .card { 
            background: #111; padding: 40px; border-radius: 15px; 
            border: 1px solid rgba(212, 175, 55, 0.2); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .form-control {
            width: 100%; padding: 15px; margin-bottom: 15px; border-radius: 8px;
            border: 1px solid #333; background: #000; color: #fff;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold-primary), #B8960C);
            color: #000; border: none; padding: 15px 30px; border-radius: 8px;
            font-weight: 700; cursor: pointer; text-transform: uppercase; width: 100%;
        }
    </style>
</head>
<body>

    <section class="hero">
        <div data-aos="zoom-in">
            <p>The Wedding Of</p>
            <h1>Yoga & Ayu</h1>
            <p>12 . 12 . 2026</p>
        </div>
    </section>

    <section class="section">
        <div class="card" data-aos="fade-up">
            <h2 style="font-family: var(--font-serif); color: var(--gold-light); margin-bottom: 20px;">Konfirmasi Kehadiran</h2>
            <p style="margin-bottom: 30px; font-size: 0.9rem; color: #aaa;">Silakan isi formulir di bawah untuk konfirmasi kehadiran Anda.</p>
            
            <form id="rsvpForm">
                <!-- ID Pesanan Tersembunyi (Kunci Penghubung ke Dashboard) -->
                <input type="hidden" name="pesanan_id" value="10"> 
                
                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                <select name="kehadiran" class="form-control" required>
                    <option value="Hadir">Saya Akan Hadir</option>
                    <option value="Tidak Hadir">Mohon Maaf, Berhalangan</option>
                </select>
                <textarea name="ucapan" class="form-control" rows="4" placeholder="Ucapan & Doa"></textarea>
                
                <button type="submit" class="btn-gold">Kirim Konfirmasi</button>
            </form>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        document.getElementById('rsvpForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../api_rsvp.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'ok') {
                    Swal.fire('Terima Kasih!', data.message, 'success');
                    this.reset();
                } else {
                    Swal.fire('Oops!', data.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Gagal terhubung ke server', 'error'));
        };
    </script>
</body>
</html>
