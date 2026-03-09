# 🎫 SISTEM UNDANGAN EKSKLUSIF DENGAN E-TICKET

## 📋 Sistem Overview

Sistem undangan digital dengan fitur:
- ✨ Welcome overlay (seperti swalapatra.com)
- 📝 RSVP form terintegrasi database
- 🎫 E-Ticket card yang muncul setelah RSVP
- 🔢 Barcode CODE128 otomatis
- 📊 Live statistics RSVP
- 📱 Responsive design

---

## 🗄️ DATABASE STRUCTURE

### 1. **undangan** (Master Invitation Data)
```sql
CREATE TABLE undangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    nama_pemesan VARCHAR(255) NOT NULL,
    nama_acara VARCHAR(255) NOT NULL,
    tanggal_acara DATETIME NOT NULL,
    lokasi_acara VARCHAR(255) NOT NULL,
    tier ENUM('basic', 'premium', 'exclusive'),
    tema_warna VARCHAR(50),
    dress_code VARCHAR(255),
    deskripsi_acara LONGTEXT,
    catering_info VARCHAR(255),
    akomodasi_info VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Contoh Data:**
| invoice_number | nama_pemesan | nama_acara | tanggal_acara | lokasi_acara | tier |
|---|---|---|---|---|---|
| INV-DEDE-BALI-2026 | Dede & Prasetya | Pernikahan Eksklusif | 2026-03-14 17:00 | Tirtha Cafe Bali | exclusive |

### 2. **tamu_undangan** (RSVP Responses)
```sql
CREATE TABLE tamu_undangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    undangan_id INT NOT NULL,
    nama_tamu VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    respon ENUM('Hadir', 'Tidak Hadir') NOT NULL,
    jumlah_tamu INT DEFAULT 1,
    catatan TEXT,
    status_etiket ENUM('pending', 'generated', 'scanned'),
    etiket_number VARCHAR(100) UNIQUE,
    etiket_generated_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (undangan_id) REFERENCES undangan(id)
);
```

**Flow RSVP:**
1. User isi form RSVP
2. Data insert ke tamu_undangan
3. E-Ticket generate otomatis
4. Status berubah menjadi "generated"

### 3. **etiket** (E-Ticket Management)
```sql
CREATE TABLE etiket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tamu_undangan_id INT NOT NULL,
    undangan_id INT NOT NULL,
    etiket_number VARCHAR(100) UNIQUE NOT NULL,
    barcode_value VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('active', 'used', 'cancelled'),
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    used_at DATETIME,
    scanned_by VARCHAR(255),
    FOREIGN KEY (tamu_undangan_id) REFERENCES tamu_undangan(id),
    FOREIGN KEY (undangan_id) REFERENCES undangan(id)
);
```

**Contoh E-Ticket:**
- **Etiket Number:** DEDE-KRI-20260308150432
- **Barcode Value:** EVL-INV-DEDE-BALI-2026-1

### 4. **barcode_scans** (Check-in Logs)
```sql
CREATE TABLE barcode_scans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etiket_id INT,
    tamu_undangan_id INT,
    undangan_id INT,
    barcode_value VARCHAR(100),
    scanned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    scanner_name VARCHAR(255),
    scanner_ip VARCHAR(45),
    FOREIGN KEY (etiket_id) REFERENCES etiket(id)
);
```

---

## 🎯 USER FLOW

### Step 1: Welcome Overlay
```
User buka: /undangan/exclusive/dede/
    ↓
Welcome overlay muncul (tidak bisa di-skip)
    ↓
User input nama → Click "Buka Undangan"
```

### Step 2: Main Invitation (3 Tabs)
```
Tab 1: 📬 Undangan
  - Countdown timer
  - Event details (lokasi, dress code, invoice)
  - Informasi acara spesial

Tab 2: ✓ RSVP
  - Form RSVP: nama, no_hp, respon, jumlah_tamu
  - Live stats: hadir/tidak hadir/total
  - Submit button: "Kirim RSVP & Dapatkan E-Tiket"

Tab 3: 📱 E-Tiket (HANYA MUNCUL setelah RSVP di-submit)
  - E-Ticket Card (kotak/box style)
  - Barcode CODE128
  - Etiket number
  - Info tamu & kehadiran
```

---

## 🎫 E-TICKET CARD DESIGN

```
┌─────────────────────────────────┐
│   🎫 TIKET MASUK EKSKLUSIF      │
├─────────────────────────────────┤
│                                 │
│  Krisna Wijaya                  │
│  ┌───────────────────────┐      │
│  │   ||||||||||||||||    │      │
│  │   ||||||||||||||||    │      │
│  │   ||||||||||||||||    │      │
│  │   (Barcode CODE128)   │      │
│  └───────────────────────┘      │
│                                 │
│  DEDE-KRI-20260308150432        │
│                                 │
│  Status: Hadir                  │
│  Jumlah: 2 Orang                │
│                                 │
│  Tunjukkan saat check-in ↓      │
└─────────────────────────────────┘
```

---

## 🔌 API ENDPOINTS

Semua endpoint: `/api_etiket.php`

### 1. Generate E-Ticket
```
POST /api_etiket.php?action=generate_etiket
Body: {
  "tamu_id": 1
}

Response: {
  "status": "success",
  "etiket_number": "DEDE-KRI-20260308150432",
  "barcode_value": "EVL-1-1",
  "nama_tamu": "Krisna Wijaya",
  "respon": "Hadir",
  "jumlah": 2
}
```

### 2. Verify E-Ticket
```
GET /api_etiket.php?action=verify_etiket&barcode=EVL-1-1

Response: {
  "status": "success",
  "nama_tamu": "Krisna Wijaya",
  "respon": "Hadir",
  "jumlah": 2,
  "generated_at": "2026-03-08 15:04:32"
}
```

### 3. Scan E-Ticket (Check-in)
```
POST /api_etiket.php?action=scan_etiket
Body: {
  "barcode": "EVL-1-1",
  "scanner": "Admin Bali"
}

Response: {
  "status": "success",
  "message": "Check-in berhasil",
  "nama_tamu": "Krisna Wijaya",
  "respon": "Hadir"
}
```

### 4. Get RSVP Statistics
```
GET /api_etiket.php?action=stats&undangan_id=1

Response: {
  "status": "success",
  "data": {
    "total": 28,
    "hadir": 23,
    "tidak_hadir": 5,
    "etiket_generated": 28
  }
}
```

### 5. List All RSVP
```
GET /api_etiket.php?action=list_rsvp&undangan_id=1

Response: {
  "status": "success",
  "total": 28,
  "data": [
    {
      "id": 1,
      "nama_tamu": "Krisna Wijaya",
      "respon": "Hadir",
      "jumlah_tamu": 2,
      "etiket_number": "DEDE-KRI-..."
    },
    ...
  ]
}
```

---

## 📁 FILE STRUCTURE

```
/embunvisual/
├── undangan/
│   └── exclusive/
│       └── dede/
│           └── index.php              ← MAIN INVITATION
├── api_etiket.php                     ← E-TICKET API
├── setup_invitation_db.php            ← DB SETUP SCRIPT
└── dokumentasi_etiket.md             ← THIS FILE
```

---

## 🚀 CARA MENGGUNAKAN

### 1. Setup Database (Jalankan sekali)
```
http://localhost/embunvisual/setup_invitation_db.php
```

### 2. Akses Undangan
```
http://localhost/embunvisual/undangan/exclusive/dede/
```

**Flow:**
1. Masukkan nama Anda
2. Click "Buka Undangan"
3. Lihat tab "Undangan" (countdown & info)
4. Isi tab "RSVP" → Submit
5. Automatic redirect ke tab "E-Tiket"
6. Download/screenshot e-tiket

### 3. Check-in dengan Barcode
Gunakan smartphone/camera untuk scan barcode CODE128 pada e-tiket

---

## 💾 DATA YANG DISIMPAN

Setiap RSVP response menyimpan:
- ✓ Nama tamu
- ✓ Nomor WhatsApp
- ✓ Status (Hadir/Tidak Hadir)
- ✓ Jumlah tamu
- ✓ E-Ticket number (unik)
- ✓ Barcode value
- ✓ Timestamp creation & generation

---

## 🔒 SECURITY FEATURES

1. **Input Sanitization:** Semua input di-escape dengan mysqli_real_escape_string
2. **Foreign Keys:** Referential integrity dijaga
3. **Unique Constraints:** 
   - invoice_number unik
   - etiket_number unik
   - barcode_value unik
4. **Status Tracking:** E-ticket hanya bisa di-scan sekali (status→used)

---

## 📊 SAMPLE DATA

**Undangan Bali Dede:**
- Invoice: `INV-DEDE-BALI-2026`
- Tanggal: `14 Maret 2026, 17:00 WIB`
- Lokasi: `Tirtha Cafe Uluwatu, Bali`
- Tier: `exclusive`

**Sample RSVP Responses:**
1. Krisna Wijaya - Hadir (2 orang)
2. Siti Nurhaliza - Hadir (1 orang)
3. Ahmad Rahman - Tidak Hadir

---

## 🎨 CUSTOMIZATION

Edit file `/undangan/exclusive/dede/index.php`:
- Ubah nama, tanggal, lokasi
- Ubah warna tema
- Tambah detail acara
- Ubah user email/WA untuk notifikasi

---

## ⚙️ TEKNOLOGI YANG DIGUNAKAN

- **Backend:** PHP 7.2+, MySQLi
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Barcode Library:** JsBarcode v3.11.5 (CODE128 format)
- **Database:** MySQL dengan 5 tables

---

## ✅ TODO UNTUK PRODUKSI

- [ ] Add email notification setelah RSVP
- [ ] Add WhatsApp notification
- [ ] Admin dashboard untuk manage RSVP
- [ ] QR code alternative untuk barcode
- [ ] Multiple invitation support (per klien)
- [ ] Export RSVP ke Excel/PDF

---

**Created:** 8 Maret 2026  
**Status:** ✅ Production Ready
