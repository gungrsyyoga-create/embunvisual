# 🎉 Undangan DEDE - Bali Exclusive

Sistem undangan pernikahan eksklusif dengan E-ticket, RSVP database, dan admin dashboard.

## 📋 Struktur File

```
undangan/exclusive/dede/
├── config.php              # Konfigurasi database & utility functions
├── setup.php               # Setup database (jalankan sekali)
├── index.php               # Main invitation dengan RSVP & E-ticket
├── admin.php               # Admin dashboard
├── api.php                 # JSON API untuk E-ticket operations
└── README.md               # File ini
```

## 🚀 Quick Start

### Step 1: Setup Database
Buka browser dan akses:
```
http://localhost/embunvisual/undangan/exclusive/dede/setup.php
```

Tunggu sampai melihat pesan "✅ SETUP BERHASIL!" dengan semua tabel berwarna hijau.

### Step 2: Buka Undangan
Setelah setup berhasil, akses undangan:
```
http://localhost/embunvisual/undangan/exclusive/dede/
```

### Step 3: Test RSVP & E-Ticket
1. Masukkan nama Anda dan klik "Buka Undangan"
2. Lihat detail acara di tab "Undangan"
3. Klik tab "✓ RSVP" dan isi form
4. Setelah submit, tab "🎫 E-Tiket" akan muncul dengan barcode

### Step 4: Admin Dashboard
Lihat semua RSVP responses di:
```
http://localhost/embunvisual/undangan/exclusive/dede/admin.php
```

---

## 📁 File Descriptions

### config.php
Core configuration dan database connection.

**Konstan yang didefinisikan:**
- `UNDANGAN_ID` - ID undangan di database (1 = Dede Bali)
- `INVOICE_NUMBER` - Nomor invoice yang unik
- `NAMA_ACARA` - Nama acara pernikahan
- `TANGGAL_ACARA` - Tanggal dan waktu acara
- `TIER` - Tier undangan (exclusive)
- `BASE_URL` - URL dasar folder

**Fungsi utility:**
- `get_undangan()` - Ambil data undangan dari database
- `ensure_database_exists()` - Cek apakah semua table ada
- `check_undangan_record()` - Cek apakah record undangan ada
- `needs_setup()` - Return true jika perlu setup

### setup.php
**Fungsi:** Membuat semua table database dan insert sample data.

**Tabel yang dibuat:**
1. `undangan` - Master data undangan
2. `tamu_undangan` - RSVP responses
3. `etiket` - E-tickets yang generated
4. `barcode_scans` - Log check-in di event
5. `undangan_konfigurasi` - Settings per undangan

**Sample Data:**
- Undangan: Dede Bali (INV-DEDE-BALI-2026)
- 3 RSVP samples untuk testing

**Hanya jalankan sekali!** (Atau ulang jika ingin reset data)

### index.php
**Fungsi:** Main invitation page dengan 3 mode:

**Mode 1: Undangan**
- Welcome overlay dengan name input
- Event details
- Countdown timer ke tanggal acara
- RSVP statistics

**Mode 2: RSVP**
- Form untuk submit RSVP
- Fields: Nama, No. WA, Status Kehadiran, Jumlah Tamu
- Live statistics update
- Database integration

**Mode 3: E-Tiket**
- E-ticket card dengan design eksklusif
- CODE128 barcode (JsBarcode)
- E-ticket number
- Status kehadiran dan jumlah tamu

### admin.php
**Fungsi:** Dashboard untuk mengelola RSVP dan tracking.

**Fitur:**
- Live statistics (Total, Hadir, Tidak Hadir, Total Tamu, E-Tiket Generated, Scan Rate)
- RSVP list table dengan action buttons
- WhatsApp integration untuk setiap tamu
- Check-in logs (riwayat barcode scans)
- Export/Print buttons

### api.php
**Fungsi:** JSON API untuk E-ticket operations.

**Endpoints:**
- `POST /api.php?action=generate_etiket` - Generate e-ticket setelah RSVP
- `GET /api.php?action=verify_etiket&barcode=XXX` - Verify e-ticket sebelum check-in
- `POST /api.php?action=scan_etiket` - Log barcode scan saat check-in
- `GET /api.php?action=stats` - Get RSVP statistics
- `GET /api.php?action=list_rsvp` - Get semua RSVP responses

**Contoh usage:**

```bash
# Get stats
curl "http://localhost/embunvisual/undangan/exclusive/dede/api.php?action=stats"

# Response:
{
  "status": "success",
  "data": {
    "total": "3",
    "hadir": "2",
    "tidak_hadir": "1",
    "total_tamu": "3",
    "etiket_generated": "2"
  }
}
```

---

## 🗄️ Database Schema

### Table: undangan
```
id (INT PRIMARY KEY AUTO_INCREMENT)
invoice_number (VARCHAR 100 UNIQUE)
nama_pemesan (VARCHAR 255)
nama_acara (VARCHAR 255)
tanggal_acara (DATETIME)
lokasi_acara (VARCHAR 255)
tier (ENUM: basic, premium, exclusive)
tema_warna (VARCHAR 50)
dress_code (VARCHAR 255)
created_at (TIMESTAMP)
```

### Table: tamu_undangan
```
id (INT PRIMARY KEY AUTO_INCREMENT)
undangan_id (INT FK → undangan)
nama_tamu (VARCHAR 255)
no_hp (VARCHAR 20)
respon (ENUM: Hadir, Tidak Hadir, Menunggu)
jumlah_tamu (INT)
etiket_number (VARCHAR 100 UNIQUE)
status_etiket (ENUM: pending, generated, used)
etiket_generated_at (DATETIME)
created_at (TIMESTAMP)
```

### Table: etiket
```
id (INT PRIMARY KEY AUTO_INCREMENT)
tamu_undangan_id (INT FK → tamu_undangan)
undangan_id (INT FK → undangan)
etiket_number (VARCHAR 100 UNIQUE)
barcode_value (VARCHAR 100 UNIQUE)
status (ENUM: generated, used, scanned)
generated_at (DATETIME DEFAULT NOW())
used_at (DATETIME)
scanned_by (VARCHAR 100)
```

### Table: barcode_scans
```
id (INT PRIMARY KEY AUTO_INCREMENT)
etiket_id (INT FK → etiket)
tamu_undangan_id (INT FK → tamu_undangan)
undangan_id (INT FK → undangan)
barcode_value (VARCHAR 100)
scanned_at (DATETIME DEFAULT NOW())
scanner_name (VARCHAR 100)
scanner_ip (VARCHAR 20)
```

### Table: undangan_konfigurasi
```
id (INT PRIMARY KEY AUTO_INCREMENT)
undangan_id (INT FK → undangan)
setting_key (VARCHAR 100)
setting_value (TEXT)
```

---

## 🔧 Customization

### Ubah Detail Acara
Edit di `config.php`:
```php
define('NAMA_ACARA', 'Nama Acara Anda');
define('NAMA_PEMESAN', 'Nama Pemesan');
define('TANGGAL_ACARA', '2026-MM-DD HH:MM:SS');
define('LOKASI_ACARA', 'Lokasi Acara');
```

### Ubah Warna & Tema
Di `index.php` ubah CSS variables:

```css
/* Main colors */
background: #0a0e27;  /* Dark background */
color: #d4af37;       /* Gold accent */
```

### Tambah Share Buttons
Di tab e-ticket, tambahkan:
```php
<a href="https://wa.me/?text=..." target="_blank">Share WhatsApp</a>
<a href="https://www.facebook.com/sharer/sharer.php?u=..." target="_blank">Share Facebook</a>
```

---

## 🐛 Troubleshooting

### Masalah: "Setup Diperlukan"
**Solusi:** Akses `setup.php` dan jalankan setup database.

### Masalah: RSVP tidak tersimpan
**Solusi:** 
- Cek apakah database sudah di-setup
- Periksa console browser untuk error message
- Cek apakah nama_tamu field terisi

### Masalah: E-Tiket tidak muncul
**Solusi:**
- Pastikan RSVP sudah berhasil disubmit
- Refresh browser
- Cek di tab "🎫 E-Tiket"

### Masalah: Admin dashboard error
**Solusi:**
- Setup database terlebih dahulu
- Pastikan akses dengan URL yang benar

---

## 📱 Browser Compatibility

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers fully supported

---

## 🔐 Security Notes

- Database credentials hardcoded (untuk production, gunakan environment variables)
- Input sanitasi menggunakan `mysqli_real_escape_string()`
- SQL queries menggunakan parameterized queries
- CORS headers dapat ditambahkan jika perlu

---

## 📊 Statistics & Metrics

Admin dashboard menampilkan:
- **Total RSVP**: Jumlah orang yang merespons
- **Akan Hadir**: Jumlah yang confirm hadir
- **Tidak Hadir**: Jumlah yang tidak dapat hadir
- **Total Tamu**: Jumlah total orang yang akan datang
- **E-Tiket Generated**: Jumlah e-ticket yang sudah di-generate
- **Scan Rate**: Persentase e-ticket yang sudah di-scan saat check-in

---

## 🎯 Next Steps

1. ✅ Setup database
2. ✅ Test invitation page
3. ✅ Test RSVP & E-ticket
4. ✅ Share link ke guests
5. ⏳ Monitor admin dashboard untuk RSVP updates
6. ⏳ Saat acara, scan barcode untuk check-in tracking

---

## 📞 Support

Untuk masalah atau pertanyaan, hubungi admin atau developer.

---

**System Version:** 1.0.0  
**Last Updated:** March 9, 2026  
**Status:** 🟢 Production Ready
