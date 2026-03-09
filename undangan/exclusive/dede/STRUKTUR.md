# 📁 STRUKTUR FOLDER - UNDANGAN DEDE

## Lokasi: `c:\xampp\htdocs\embunvisual\undangan\exclusive\dede\`

```
undangan/
└── exclusive/
    └── dede/                         ← FOLDER UNDANGAN DEDE
        ├── config.php                ← [CORE] Database config & utilities
        ├── setup.php                 ← [SETUP] Database initialization
        ├── index.php                 ← [MAIN] Invitation page
        ├── admin.php                 ← [ADMIN] Dashboard
        ├── api.php                   ← [API] JSON endpoints
        ├── start.php                 ← [UI] Landing/menu page
        │
        ├── README.md                 ← 📖 Complete documentation
        ├── CHECKLIST.md              ← ✅ System checklist
        └── STRUKTUR.md               ← 📁 This file
```

---

## 📊 File Classification

### 🔴 CORE FILES (Must work correctly)
| File | Purpose | Status |
|------|---------|--------|
| `config.php` | Database connection & utilities | ✅ |
| `setup.php` | Database table creation | ✅ |

### 🟢 FRONTEND FILES (User-facing)
| File | Purpose | Status |
|------|---------|--------|
| `start.php` | Menu/landing page | ✅ |
| `index.php` | Main invitation with RSVP & E-ticket | ✅ |

### 🔵 ADMIN FILES (Internal management)
| File | Purpose | Status |
|------|---------|--------|
| `admin.php` | Admin dashboard | ✅ |

### 🟡 API FILES (Data exchange)
| File | Purpose | Status |
|------|---------|--------|
| `api.php` | JSON API endpoints | ✅ |

### 📚 DOCUMENTATION FILES
| File | Purpose | Status |
|------|---------|--------|
| `README.md` | Complete user guide | ✅ |
| `CHECKLIST.md` | System checklist | ✅ |
| `STRUKTUR.md` | This file | ✅ |

---

## 🔄 Data Flow

### Setup Flow
```
User Accesses: start.php
       ↓
  Click: "Setup Database"
       ↓
    Browser: setup.php
       ↓
Database: Creates 5 tables
       ↓
   Output: Progress messages
       ↓
 Result: ✅ SETUP BERHASIL!
```

### Invitation Flow
```
User Accesses: index.php
       ↓
  Check: needs_setup()? → Redirect to setup.php if YES
       ↓
  Load: config.php (database connection)
       ↓
 Display: Welcome overlay
       ↓
    User: Masukkan nama & buka undangan
       ↓
  Show: 3 mode tabs (Undangan | RSVP | E-Tiket)
       ↓
    User: Isi RSVP form
       ↓
  Submit: POST ke index.php
       ↓
Database: INSERT tamu_undangan
       ↓
Database: INSERT etiket (auto-generate)
       ↓
Session: Simpan $_SESSION['rsvp_tamu']
       ↓
Display: E-Tiket tab dengan barcode
```

### Admin Dashboard Flow
```
User Accesses: admin.php
       ↓
  Check: needs_setup()? → Show setup message if YES
       ↓
  Load: config.php (database connection)
       ↓
Database: SELECT COUNT(*) untuk statistics
       ↓
Database: SELECT * dari tamu_undangan
       ↓
Database: SELECT * dari barcode_scans
       ↓
Display: Dashboard dengan 3 sections
         - Statistics grid (6 metrics)
         - RSVP list table
         - Check-in logs
```

### API Flow
```
Request: GET/POST api.php?action=...
       ↓
  Load: config.php
       ↓
Switch: action parameter
       ↓
 Execute: Sesuai action (generate, verify, scan, stats, list)
       ↓
Query: Database
       ↓
Return: JSON response
       ↓
Output: {"status": "success/error", "data": {...}}
```

---

## 🗄️ Database Integration

### Tables Created
```
embun_visual (Database)
├── undangan              [Master data]
├── tamu_undangan         [RSVP responses]
├── etiket                [E-tickets]
├── barcode_scans         [Check-in logs]
└── undangan_konfigurasi  [Settings]
```

### Relationships
```
undangan (1)
    ↓
    ├──→ tamu_undangan (Many)
    │        ↓
    │        └──→ etiket (Many)
    │               ↓
    │               └──→ barcode_scans (Many)
    │
    └──→ undangan_konfigurasi (Many)
```

---

## 🎯 Usage Sequence

### For End Users (Tamu Undangan)

1. **Akses Undangan**
   ```
   http://localhost/embunvisual/undangan/exclusive/dede/index.php
   ```

2. **Interaction**
   - [Welcome Overlay] Masukkan nama
   - [Tab 1] Lihat detail acara & countdown
   - [Tab 2] Isi RSVP form
   - [Tab 3] Lihat E-Tiket dengan barcode

3. **Result**
   - Data tersimpan di database
   - E-Tiket digenerate otomatis
   - Barcode siap scan saat check-in

---

### For Admin (Penyelenggara)

1. **Setup (One-time)**
   ```
   http://localhost/embunvisual/undangan/exclusive/dede/setup.php
   ```

2. **Monitor RSVP**
   ```
   http://localhost/embunvisual/undangan/exclusive/dede/admin.php
   ```

3. **Data Review**
   - Total RSVP count
   - Hadir vs Tidak Hadir
   - Total tamu yang akan datang
   - E-ticket generation status
   - Check-in logs real-time

---

## 🔧 Customization Locations

### Event Details
**File:** `config.php`
```php
define('NAMA_ACARA', 'Pernikahan Dede & Bali');
define('TANGGAL_ACARA', '2026-03-14 17:00:00');
define('LOKASI_ACARA', 'Royal Bali Beach Club, Sanur');
```

### Visual Theme
**File:** `index.php` (CSS section)
```css
background: #0a0e27;  /* Dark background */
color: #d4af37;       /* Gold accent */
```

### Database Connection
**File:** `config.php`
```php
$mysqli = new mysqli("localhost", "root", "", "embun_visual");
```

---

## 📱 URLs Reference

| Purpose | URL |
|---------|-----|
| Menu/Start | `http://localhost/embunvisual/undangan/exclusive/dede/start.php` |
| Setup | `http://localhost/embunvisual/undangan/exclusive/dede/setup.php` |
| Invitation | `http://localhost/embunvisual/undangan/exclusive/dede/index.php` |
| Admin | `http://localhost/embunvisual/undangan/exclusive/dede/admin.php` |
| API Stats | `http://localhost/embunvisual/undangan/exclusive/dede/api.php?action=stats` |
| README | `http://localhost/embunvisual/undangan/exclusive/dede/README.md` |

---

## 🎓 File Size & Complexity

| File | Size | Complexity | Lines |
|------|------|-----------|-------|
| config.php | ~3 KB | Low | 75 |
| setup.php | ~12 KB | Medium | 250 |
| index.php | ~45 KB | High | 800+ |
| admin.php | ~18 KB | Medium | 350 |
| api.php | ~8 KB | Medium | 180 |
| start.php | ~6 KB | Low | 150 |

**Total System Size:** ~92 KB  
**Total Lines of Code:** ~1,800 lines

---

## 🔐 Security Files

### File Permissions
```
-rw-r--r--  config.php       (readable by web server)
-rw-r--r--  setup.php        (readable by web server)
-rw-r--r--  index.php        (readable by web server)
-rw-r--r--  admin.php        (readable by web server)
-rw-r--r--  api.php          (readable by web server)
```

### Sensitive Data
⚠️ **Currently:** Database credentials hardcoded in `config.php`  
✅ **For Production:** Use environment variables

```php
// Better approach
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'embun_visual';
```

---

## 📈 Scalability Notes

### Current Limitations
- Single undangan per folder (Dede Bali only)
- Fixed database connection
- No multi-language support
- No email integration yet

### Future Enhancements
- [ ] Multi-undangan support per folder
- [ ] Email notifications
- [ ] SMS notifications
- [ ] QR code alternative
- [ ] Mobile app integration
- [ ] Analytics dashboard
- [ ] Export to Excel/PDF
- [ ] Internationalization (i18n)

---

## 🆘 Troubleshooting Flow

```
Problem: Setup redirects to itself
Solution: Check config.php doesn't auto-redirect

Problem: RSVP not saving
Solution: Check database tables exist (run setup.php)

Problem: E-Tiket not showing
Solution: Check barcode_value is set correctly

Problem: Admin dashboard error
Solution: Run setup.php first

Problem: JavaScript errors
Solution: Check browser console for specific errors
```

---

## 📞 Support Information

For issues with:
- **Setup:** See `setup.php` output messages
- **Invitation:** Check browser console (F12)
- **Admin:** Review database queries in `admin.php`
- **API:** Test with curl or Postman

---

## 📋 Checklist for Deployment

- [ ] Run `setup.php` to initialize database
- [ ] Test `index.php` with test account
- [ ] Verify `admin.php` shows correct statistics
- [ ] Test API endpoints with curl
- [ ] Update email/WhatsApp settings if needed
- [ ] Test on mobile browsers
- [ ] Backup database before going live
- [ ] Update documentation with real event details
- [ ] Share invitation link with guests
- [ ] Monitor RSVP responses

---

**System Version:** 1.0.0  
**Created:** March 9, 2026  
**Status:** 🟢 Production Ready
